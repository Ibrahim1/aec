<?php
/**
 * @version $Id: mi_mysql_query.php 16 2007-07-02 13:29:29Z mic $
 * @package AEC - Account Control Expiration - Subscription component for Joomla! OS CMS
 * @subpackage Micro Integrations - MySQL Query
 * @copyright 2006/2007 Copyright (C) David Deutsch
 * @author David Deutsch <skore@skore.de> & Team AEC - http://www.globalnerd.org
 * @license GNU/GPL v.2 http://www.gnu.org/copyleft/gpl.html
 */

/**
 * This version copyright (c) 2008 by Martin Brampton
 * martin@remository.com
 * http://www.remository.com
 * Modified to correctly integrate with Remository
 */

defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );

global $mainframe;
require_once($mainframe->getCfg('absolute_path').'/components/com_remository/remository.interface.php');
require_once($mainframe->getCfg('absolute_path').'/components/com_remository/remository.class.php');
require_once($mainframe->getCfg('absolute_path').'/components/com_remository/p-classes/remositoryAuthoriser.php');

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

	function Settings( $params )
	{
		$authoriser =& aliroAuthorisationAdmin::getInstance();
		foreach ($authoriser->getAllRoles() as $role) {
			$sg[] = mosHTML::makeOption( $role, $role);
		}

 		$del_opts = array();
		$del_opts[0] = mosHTML::makeOption ( "No", "Just apply group(s) below." ); // Should probably be langauge file defined?
		$del_opts[1] = mosHTML::makeOption ( "All", "Delete ALL, then apply group(s) below." );
		$del_opts[2] = mosHTML::makeOption ( "Set", "Delete Group Set on Application, then apply group(s) below." );

        	$settings = array();
		$settings['add_downloads']		= array( 'inputA' );
		$settings['set_downloads']		= array( 'inputA' );
		$settings['set_unlimited']		= array( 'list_yesno' );

		$settings['lists']['group']		= mosHTML::selectList($sg, 'group', 'size="4" multiple="multiple"', 'value', 'text', $params['group']);
		$settings['lists']['group_exp']	= mosHTML::selectList($sg, 'group_exp', 'size="4" multiple="multiple"', 'value', 'text', $params['group_exp']);

		$settings['set_group']				= array( 'list_yesno' );
		$settings['group']					= array( 'list' );
		$settings['lists']['delete_on_exp'] = mosHTML::selectList( $del_opts, 'delete_on_exp', 'size="3"', 'value', 'text', $params['delete_on_exp'] );
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

	function expiration_action( $params, $metaUser, $plan )
	{
		global $database;

		$authoriser =& aliroAuthorisationAdmin::getInstance();
 		if ( $params['delete_on_exp']=="Set" ) {
			$authoriser->unassign($params['group']. 'aUser', $metaUser->userid);
		}

		if ( $params['delete_on_exp']=="All" ) {
			$authoriser->dropAccess('aUser', $metaUser->userid);
		}

		if ($params['set_group_exp']) {
			$authoriser->assign($params['group_exp'], 'aUser', $metaUser->userid);
		}

		$mi_remositoryhandler = new remository_restriction( $database );
		$id = $mi_remositoryhandler->getIDbyUserID( $metaUser->userid );
		$mi_id = $id ? $id : 0;
		$mi_remositoryhandler->load( $mi_id );

		if ( $mi_id ) {
			$mi_remositoryhandler->active = 0;
			$mi_remositoryhandler->check();
			$mi_remositoryhandler->store();
		}

		return true;
	}

	function action( $params, $metaUser, $invoice, $plan )
	{
		global $database;

		$authoriser =& aliroAuthorisationAdmin::getInstance();
		if ( $params['set_group'] ) {
			$authoriser->assign($params['group'], 'aUser', $metaUser->userid);
		}

		$mi_remositoryhandler = new remository_restriction( $database );
		$id = $mi_remositoryhandler->getIDbyUserID( $metaUser->userid );
		$mi_id = $id ? $id : 0;
		$mi_remositoryhandler->load( $mi_id );

		if ( !$mi_id ) {
			$mi_remositoryhandler->userid = $metaUser->userid;
			$mi_remositoryhandler->active = 1;
		}

		if ( $params['set_downloads'] ) {
			$mi_remositoryhandler->setDownloads( $params['set_downloads'] );
		} elseif ( $params['add_downloads'] ) {
			$mi_remositoryhandler->addDownloads( $params['add_downloads'] );
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