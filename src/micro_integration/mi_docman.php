<?php
/**
 * @version $Id: mi_docman.php 16 2007-07-01 12:07:07Z mic $
 * @package AEC - Account Control Expiration - Subscription component for Joomla! OS CMS
 * @subpackage Micro Integrations - DocMan
 * @copyright 2006/2007 Copyright (C) David Deutsch
 * @author David Deutsch <skore@skore.de> & Team AEC - http://www.globalnerd.org
 * @author Calum Polwart (Shiny Black Shoe Systems)
 * @license GNU/GPL v.2 http://www.gnu.org/copyleft/gpl.html
 *
 * based on David Deutsch's reMOSitory MI
 */

defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );

class mi_docman
{
	function Info()
	{
		$info = array();
		$info['name'] = _AEC_MI_NAME_DOCMAN;
		$info['desc'] = _AEC_MI_DESC_DOCMAN;

		return $info;
	}

	function checkInstallation()
	{
		global $database, $mosConfig_dbprefix;

		$tables	= array();
		$tables	= $database->getTableList();

		return in_array( $mosConfig_dbprefix . 'acctexp_mi_docman', $tables );
	}

	function install()
	{
		global $database;

		$query = 'CREATE TABLE IF NOT EXISTS `#__acctexp_mi_docman` ('
		. '`id` int(11) NOT NULL auto_increment,'
		. '`userid` int(11) NOT NULL,'
		. '`active` int(4) NOT NULL default \'1\','
		. '`granted_downloads` int(11) NULL,'
		. '`used_downloads` int(11) NULL,'
		. '`params` text NULL,'
		. ' PRIMARY KEY (`id`)'
		. ')'
		;
		$database->setQuery( $query );
		$database->query();
		return;
	}

	function Settings( $params )
	{
		global $database;

		$query = 'SELECT groups_id, groups_name, groups_description'
	 	. ' FROM #__docman_groups'
	 	;
	 	$database->setQuery( $query );
	 	$groups = $database->loadObjectList();

		$sg = array();
		foreach( $groups as $group ) {
			$sg[] = mosHTML::makeOption( $group->groups_id, $group->groups_name . ' - '
			. substr( strip_tags( $group->groups_description ), 0, 30 ) );
		}

 		$del_opts = array();
		$del_opts[0] = mosHTML::makeOption ("No", "Just apply group below."); // Should probably be langauge file defined?
		$del_opts[1] = mosHTML::makeOption ("All", "Delete ALL, then apply group below.");
		$del_opts[2] = mosHTML::makeOption ("Set","Delete Group Set on Application, then apply group below.");

        $settings = array();
		$settings['add_downloads']		= array( 'inputA' );
		$settings['set_downloads']		= array( 'inputA' );

		$settings['lists']['group']		= mosHTML::selectList( $sg, 'group', 'size="4"', 'value', 'text', $params['group'] );
		$settings['lists']['group_exp'] = mosHTML::selectList( $sg, 'group_exp', 'size="4"', 'value', 'text',
										$params['group_exp'] );
		$settings['set_group']			= array( 'list_yesno' );
		$settings['group']				= array( 'list' );
		$settings['set_group_exp']		= array( 'list_yesno' );
		$settings['delete_on_exp'] 		= array('list');
		$settings['group_exp']			= array( 'list' );
		$settings['rebuild']			= array( 'list_yesno' );

		return $settings;
	}

	function detect_application()
	{
		global $mosConfig_absolute_path;

		return is_dir( $mosConfig_absolute_path . '/components/com_docman' );
	}

	function saveparams( $params )
	{
		global $mosConfig_absolute_path, $database;
		$newparams = $params;

		if ( $params['rebuild'] ) {
			$planlist = MicroIntegrationHandler::getPlansbyMI( $params['MI_ID'] );

			foreach ( $planlist as $planid ) {
				$userlist = SubscriptionPlanHandler::getPlanUserlist( $planid );
				foreach ( $userlist as $userid ) {
					if ( $params['set_group'] ) {
						$this->AddUserToGroup( $userid, $params['group'] );
					}
				}
			}

			$newparams['rebuild'] = 0;
		}

		return $newparams;
	}

	function hacks()
	{
		global $mosConfig_absolute_path;

		$hacks = array();

		$downloadhack =	'// AEC HACK docmandownloadphp START' . "\n"
		. 'global $my, $mosConfig_absolute_path;' . "\n"
		. 'include( $mosConfig_absolute_path . \'/components/com_acctexp/micro_integration/mi_docman.php\');' . "\n\n"
		. '$restrictionhandler = new docman_restriction( $database );' . "\n"
		. '$restrict_id = $restrictionhandler->getIDbyUserID( $my->id );' . "\n"
		. '$restrictionhandler->load( $restrict_id );' . "\n\n"
		. 'if (!$restrictionhandler->hasDownloadsLeft()) {' . "\n"
		. "\t" . '$restrictionhandler->noDownloadsLeft();' . "\n"
		. '}else{' . "\n"
		. "\t" . '$restrictionhandler->useDownload();' . "\n"
		. '}' . "\n"
		. '// AEC HACK docmandownloadphp END' . "\n"
		;

		$n = 'docmandownloadphp';
		$hacks[$n]['name']				=	'download.php';
		$hacks[$n]['desc']				=	_AEC_MI_HACK1_DOCMAN;
		$hacks[$n]['type']				=	'file';
		$hacks[$n]['filename']			=	$mosConfig_absolute_path . '/components/com_docman/includes_frontend/download.php';
		$hacks[$n]['read']				=	'// If the remote host is not allowed';
		$hacks[$n]['insert']			=	$downloadhack . "\n"  . $hacks[$n]['read'];

		return $hacks;
	}

	function expiration_action($params, $metaUser, $plan)
	{
		global $database;

 		if ( $params['delete_on_exp']=="Set" ) {
			$this->DeleteUserFromGroup( $metaUser->userid, $params['group'] );
		}

		if ( $params['delete_on_exp']=="All" ) {
			$groups = $this->GetUserGroups( $metaUser->userid );
			foreach ($groups as $group) {
				$this->DeleteUserFromGroup( $metaUser->userid, $group );
			}
		}

		if ( $params['set_group_exp'] ) {
			$this->AddUserToGroup( $metaUser->userid, $params['group_exp'] );
		}

		$mi_docmanhandler = new docman_restriction( $database );
		$id = $mi_docmanhandler->getIDbyUserID( $metaUser->userid );
		$mi_id = $id ? $id : 0;
		$mi_docmanhandler->load( $mi_id );

		if ( $mi_id ) {
			$mi_docmanhandler->active = 0;
			$mi_docmanhandler->check();
			$mi_docmanhandler->store();
		}

		return true;
	}

	function action( $params, $metaUser, $invoice, $plan )
	{
		global $database;

		if ( $params['set_group'] ) {
			$this->AddUserToGroup( $metaUser->userid, $params['group'] );
		}

		$mi_docmanhandler = new docman_restriction( $database );
		$id = $mi_docmanhandler->getIDbyUserID( $metaUser->userid );
		$mi_id = $id ? $id : 0;
		$mi_docmanhandler->load( $mi_id );

		if ( !$mi_id ) {
			$mi_docmanhandler->userid = $metaUser->userid;
			$mi_docmanhandler->active = 1;
		}

		if ( $params['set_downloads'] ) {
			$mi_docmanhandler->setDownloads( $params['set_downloads'] );
		} elseif ( $params['add_downloads'] ) {
			$mi_docmanhandler->addDownloads( $params['add_downloads'] );
		}

		$mi_docmanhandler->check();
		$mi_docmanhandler->store();

		return true;
	}

	function GetUserGroups( $userid )
	{
		global $database;

		$query = 'SELECT `groups_id`'
				. ' FROM #__docman_groups'
				;
		$database->setQuery( $query );
		$ids = $database->loadResultArray();

		$groups = array();
		foreach ( $ids as $groupid ) {
			$query = 'SELECT `groups_members`'
					. ' FROM #__docman_groups'
					. ' WHERE `groups_id` = \'' . $groupid . '\''
					;
			$database->setQuery( $query );
			$users = explode( ',', $database->loadResult() );

			if ( in_array( $userid, $users ) ) {
				$groups[] = $groupid;
			}
		}

		return $groups;
	}

	function AddUserToGroup( $userid, $groupid )
	{
		global $database;

		$this->DeleteUserFromGroup( $userid, $groupid );

		$query = 'SELECT `groups_members`'
			. ' FROM #__docman_groups'
			. ' WHERE `groups_id` = \'' . $groupid . '\''
			;
		$database->setQuery( $query );
		$users = explode( ',', $database->loadResult() );

		$users[] = $userid;

		// Make sure we have no empty value
		$search = 0;
		while ( $search !== false ) {
			$search = array_search( '', $users );
			if ( $search !== false ) {
				unset( $users[$search] );
			}
		}

		$query = 'UPDATE #__docman_groups'
			. ' SET `groups_members` = \'' . implode( ',', $users ) . '\''
			. ' WHERE `groups_id` = \'' . $groupid . '\''
			;
		$database->setQuery( $query );
		$database->query();

		return true;
	}

	function DeleteUserFromGroup( $userid, $groupid )
	{
		global $database;

		$query = 'SELECT `groups_members`'
			. ' FROM #__docman_groups'
			. ' WHERE `groups_id` = \'' . $groupid . '\''
			;
		$database->setQuery( $query );
		$users = explode( ',', $database->loadResult() );

		if ( in_array( $userid, $users ) ) {
			$key = array_search( $userid, $users );
			unset( $users[$key] );

			// Make sure we have no empty value
			$search = 0;
			while ( $search !== false ) {
				$search = array_search( '', $users );
				if ( $search !== false ) {
					unset( $users[$search] );
				}
			}

			$query = 'UPDATE #__docman_groups'
				. ' SET `groups_members` = \'' . implode( ',', $users ) . '\''
				. ' WHERE `groups_id` = \'' . $groupid . '\''
				;
			$database->setQuery( $query );
			$database->query();

			return true;
		} else {
			return false;
		}
	}
}

class docman_restriction extends mosDBTable {
	/** @var int Primary key */
	var $id					= null;
	/** @var int */
	var $userid 			= null;
	/** @var int */
	var $active				= null;
	/** @var int */
	var $granted_downloads	= null;
	/** @var text */
	var $used_downloads		= null;
	/** @var text */
	var $params				= null;

	function getIDbyUserID( $userid ) {
		global $database;

		$query = 'SELECT `id`'
			. ' FROM #__acctexp_mi_docman'
			. ' WHERE `userid` = \'' . $userid . '\''
			;
		$database->setQuery( $query );
		return $database->loadResult();
	}

	function docman_restriction( &$db ) {
		$this->mosDBTable( '#__acctexp_mi_docman', 'id', $db );
	}

	function is_active()
	{
		if ( $this->active ) {
			return true;
		} else {
			return false;
		}
	}

	function getDownloadsLeft()
	{
		$downloads_left = $this->granted_downloads - $this->used_downloads;
		return $downloads_left;
	}

	function hasDownloadsLeft()
	{
		if ( $this->getDownloadsLeft() > 0 ) {
			return true;
		} else {
			return false;
		}
	}

	function noDownloadsLeft()
	{
		if ( !defined( '_AEC_LANG_INCLUDED_MI' ) ) {
			global $mainframe;

			$langPathMI = $mainframe->getCfg( 'absolute_path' ) . '/components/com_acctexp/micro_integration/language/';
			if ( file_exists( $langPathMI . $mainframe->getCfg( 'lang' ) . '.php' ) ) {
				include_once( $langPathMI . $mainframe->getCfg( 'lang' ) . '.php' );
			} else {
				include_once( $langPathMI . 'english.php' );
			}
		}

		mosRedirect(  'index.php?option=com_docman' , _AEC_MI_DOCMAN_NOCREDIT );
	}

	function useDownload()
	{
		if ( $this->hasDownloadsLeft() && $this->is_active() ) {
			$this->used_downloads++;
			$this->check();
			$this->store();
			return true;
		} else {
			return false;
		}
	}

	function setDownloads( $set )
	{
		$this->granted_downloads = $set;
	}

	function addDownloads( $add )
	{
		$this->granted_downloads += $add;
	}
}
?>