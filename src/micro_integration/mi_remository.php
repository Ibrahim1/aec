<?php
/**
 * @version $Id: mi_mysql_query.php 16 2007-07-02 13:29:29Z mic $
 * @package AEC - Account Control Expiration - Subscription component for Joomla! OS CMS
 * @subpackage Micro Integrations - MySQL Query
 * @copyright 2006/2007 Copyright (C) David Deutsch
 * @author David Deutsch <skore@skore.de> & Team AEC - http://www.globalnerd.org
 * @license GNU/GPL v.2 http://www.gnu.org/copyleft/gpl.html
 */

defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );

class mi_remository
{
	function Info()
	{
		$info = array();
		$info['name'] = _AEC_MI_NAME_REMOS;
		$info['desc'] = _AEC_MI_DESC_REMOS;

		return $info;
	}

	function checkInstallation()
	{
		global $database, $mosConfig_dbprefix;

		$tables	= array();
		$tables	= $database->getTableList();

		return in_array( $mosConfig_dbprefix . 'acctexp_mi_remository', $tables );
	}

	function install()
	{
		global $database;

		$query = 'CREATE TABLE IF NOT EXISTS `#__acctexp_mi_remository` ('
		. '`id` int(11) NOT NULL auto_increment,'
		. '`userid` int(11) NOT NULL,'
		. '`active` int(4) NOT NULL default \'1\','
		. '`granted_downloads` int(11) NULL,'
		. '`unlimited_downloads` int(3) NULL,'
		. '`used_downloads` int(11) NULL,'
		. '`params` text NULL,'
		. ' PRIMARY KEY (`id`)'
		. ')'
		;
		$database->setQuery( $query );
		$database->query();
		return;
	}

	function Settings()
	{
		global $database;

		$query = 'SELECT `group_id`, `group_name`, `group_description`'
			 	. ' FROM #__mbt_group'
			 	;
	 	$database->setQuery( $query );
	 	$groups = $database->loadObjectList();

		$sg = array();
		if ( !empty( $groups ) ) {
			foreach ( $groups as $group ) {
				$sg[] = mosHTML::makeOption( $group->group_id, $group->group_name . ' - ' . substr( strip_tags( $group->group_name ), 0, 30 ) );
			}
		}

 		$del_opts = array();
		$del_opts[0] = mosHTML::makeOption ( "No", "Just apply group(s) below." ); // Should probably be langauge file defined?
		$del_opts[1] = mosHTML::makeOption ( "All", "Delete ALL, then apply group(s) below." );
		$del_opts[2] = mosHTML::makeOption ( "Set", "Delete Group Set on Application, then apply group(s) below." );

        $settings = array();
		$settings['add_downloads']		= array( 'inputA' );
		$settings['set_downloads']		= array( 'inputA' );
		$settings['set_unlimited']		= array( 'list_yesno' );

		$settings['lists']['group']		= mosHTML::selectList($sg, 'group', 'size="4" multiple="multiple"', 'value', 'text', $this->settings['group']);
		$settings['lists']['group_exp']	= mosHTML::selectList($sg, 'group_exp', 'size="4" multiple="multiple"', 'value', 'text', $this->settings['group_exp']);

		$settings['set_group']				= array( 'list_yesno' );
		$settings['group']					= array( 'list' );
		$settings['lists']['delete_on_exp'] = mosHTML::selectList( $del_opts, 'delete_on_exp', 'size="3"', 'value', 'text', $this->settings['delete_on_exp'] );
		$settings['delete_on_exp']		= array( 'list' );
		$settings['set_group_exp']		= array( 'list_yesno' );
		$settings['group_exp']				= array( 'list' );

		return $settings;
	}

	function saveparams( $params )
	{
		$subgroups = array( 'group', 'group_exp' );

		foreach ( $subgroups as $groupname ) {
			$temp = implode( ';', $params[$groupname] );
			$params[$groupname] = $temp;
		}

		return $params;
	}

	function detect_application()
	{
		global $mosConfig_absolute_path;

		return is_dir( $mosConfig_absolute_path . '/components/com_remository/c-classes' );
	}

	function hacks()
	{
		global $mosConfig_absolute_path;

		$hacks = array();

		$downloadhack =	'// AEC HACK remositorystartdown START' . "\n"
		. 'global $my, $mosConfig_absolute_path;' . "\n"
		. 'include( $mosConfig_absolute_path . \'/components/com_acctexp/micro_integration/mi_remository.php\' );' . "\n\n"
		. '$restrictionhandler = new remository_restriction( $database );' . "\n"
		. '$restrict_id = $restrictionhandler->getIDbyUserID( $my->id );' . "\n"
		. '$restrictionhandler->load( $restrict_id );' . "\n\n"
		. 'if( !$restrictionhandler->hasDownloadsLeft() ) {' . "\n"
		. '	mosRedirect( \'index.php?option=com_remository\', \'' . _AEC_MI_HACK1_REMOS . '\' );' . "\n"
		. '}else{' . "\n"
		. '	$restrictionhandler->useDownload();' . "\n"
		. '}' . "\n"
		. '// AEC HACK remositorystartdown END' . "\n"
		;

		$n = 'remositorystartdown';
		$hacks[$n]['name']				=	'com_remository_startdown.php';
		$hacks[$n]['desc']				=	_AEC_MI_HACK2_REMOS;
		$hacks[$n]['type']				=	'file';
		$hacks[$n]['filename']			=	$mosConfig_absolute_path
											. '/components/com_remository/c-classes/remository_download_Controller.php';
		$hacks[$n]['read']				=	'$this->writeHeaders($ctype, $displayname, $len);';
		$hacks[$n]['insert']			=	$downloadhack . "\n"  . $hacks[$n]['read'];

		return $hacks;
	}

	function expiration_action( $request )
	{
		global $database;

 		if ( $this->settings['delete_on_exp']=="Set" ) {
 			$query = 'DELETE FROM #__mbt_group_member'
		 			. ' WHERE `member_id` = \'' . $request->metaUser->userid.'\''
		 			. ' AND `group_id` = \'' .$this->settings['group'].'\''
		 			;
 			$database->setQuery( $query );
		}

		if ( $this->settings['delete_on_exp']=="All" ) {
 			$query = 'DELETE FROM #__mbt_group_member'
		 			. ' WHERE `member_id` = \'' . $request->metaUser->userid.'\''
		 			;
 			$database->setQuery( $query );
		}

		if ($this->settings['set_group_exp']) {
			// Check if exists
			$query = 'SELECT `group_id`'
					. ' FROM #__mbt_group_member'
					. ' WHERE `member_id` = \'' . $request->metaUser->userid . '\''
					;
			$database->setQuery( $query );

			$groups = $database->loadResultArray();
			$groups = is_array( $groups ) ? $groups : array();

			// If already an entry exists -> update, if not -> create
			if ( !in_array( $this->settings['group_exp'], $groups ) ) {
				$query = 'INSERT INTO #__mbt_group_member'
				. ' ( `group_id` , `member_id` )'
				. ' VALUES (\'' . $this->settings['group_exp'] . '\', \'' . $request->metaUser->userid . '\')'
				;
				$database->setQuery( $query );
				$database->query();
			}

		}

		$mi_remositoryhandler = new remository_restriction( $database );
		$id = $mi_remositoryhandler->getIDbyUserID( $request->metaUser->userid );
		$mi_id = $id ? $id : 0;
		$mi_remositoryhandler->load( $mi_id );

		if ( $mi_id ) {
			$mi_remositoryhandler->active = 0;
			$mi_remositoryhandler->check();
			$mi_remositoryhandler->store();
		}

		return true;
	}

	function action( $request )
	{
		global $database;

		if ( $this->settings['set_group'] ) {
			// Check if exists
			$query = 'SELECT `group_id`'
					. ' FROM #__mbt_group_member'
					. ' WHERE `member_id` = \'' . $request->metaUser->userid . '\''
					;
			$database->setQuery( $query );

			$groups = $database->loadResultArray();
			$groups = is_array( $groups ) ? $groups : array();

			// If already an entry exists -> update, if not -> create
			if ( !in_array( $this->settings['group'], $groups ) ) {
				$query = 'INSERT INTO #__mbt_group_member'
						. ' ( `group_id` , `member_id` )'
						. ' VALUES (\'' . $this->settings['group'] . '\', \'' . $request->metaUser->userid . '\')'
						;
				$database->setQuery( $query );
				$database->query();
			}
		}

		$mi_remositoryhandler = new remository_restriction( $database );
		$id = $mi_remositoryhandler->getIDbyUserID( $request->metaUser->userid );
		$mi_id = $id ? $id : 0;
		$mi_remositoryhandler->load( $mi_id );

		if ( !$mi_id ) {
			$mi_remositoryhandler->userid = $request->metaUser->userid;
			$mi_remositoryhandler->active = 1;
		}

		if ( $this->settings['set_downloads'] ) {
			$mi_remositoryhandler->setDownloads( $this->settings['set_downloads'] );
		} elseif ( $this->settings['add_downloads'] ) {
			$mi_remositoryhandler->addDownloads( $this->settings['add_downloads'] );
		}

		$mi_remositoryhandler->check();
		$mi_remositoryhandler->store();

		return true;
	}
}

class remository_restriction extends mosDBTable {
	/** @var int Primary key */
	var $id					= null;
	/** @var int */
	var $userid 			= null;
	/** @var int */
	var $active				= null;
	/** @var int */
	var $granted_downloads	= null;
	/** @var int */
	var $unlimited_downloads	= null;
	/** @var text */
	var $used_downloads		= null;
	/** @var text */
	var $params				= null;

	function getIDbyUserID( $userid )
	{
		global $database;

		$query = 'SELECT `id`'
				. ' FROM #__acctexp_mi_remository'
				. ' WHERE `userid` = \'' . $userid . '\''
				;
		$database->setQuery( $query );
		return $database->loadResult();
	}

	function remository_restriction( &$db )
	{
		$this->mosDBTable( '#__acctexp_mi_remository', 'id', $db );
	}

	function is_active()
	{
		if( $this->active ) {
			return true;
		}else{
			return false;
		}
	}

	function getDownloadsLeft()
	{
		if ( !empty( $this->unlimited_downloads ) ) {
			return true;
		} else {
			$downloads_left = $this->granted_downloads - $this->used_downloads;
			return $downloads_left;
		}
	}

	function hasDownloadsLeft()
	{
		if( $this->getDownloadsLeft() > 0 ) {
			return true;
		}else{
			return false;
		}
	}

	function useDownload()
	{
		if( $this->hasDownloadsLeft() && $this->is_active() ) {
			$this->used_downloads++;
			$this->check();
			$this->store();
			return true;
		}else{
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