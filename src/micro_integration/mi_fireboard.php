<?php
/**
 * @version $Id: mi_fireboard.php 01 2007-08-11 13:29:29Z SBS $
 * @package AEC - Account Control Expiration - Subscription component for Joomla! OS CMS
 * @subpackage Micro Integrations - Fireboard
 * @copyright 2006/2007 Copyright (C) David Deutsch
 * @author Calum Polwart & Team AEC - http://www.gobalnerd.org
 * @license GNU/GPL v.2 http://www.gnu.org/copyleft/gpl.html
 * Based on code from the mi_remository.php file from sk0re.
 */

// Dont allow direct linking
defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );

class mi_fireboard
{

	function Info()
	{
		$info = array();
		$info['name'] = _AEC_MI_NAME_FIREBOARD;
		$info['desc'] = _AEC_MI_DESC_FIREBOARD;

		return $info;
	}

	function Settings( $params )
	{
		global $database;

		$query = 'SELECT id, title'
	 	. ' FROM #__fb_groups'
	 	;
	 	$database->setQuery( $query );
	 	$groups = $database->loadObjectList();

		$sg = array();
		foreach ( $groups as $group ) {
			$sg[] = mosHTML::makeOption( $group->id, $group->title );
		}

        $settings = array();

		$settings['lists']['group']		= mosHTML::selectList($sg, 'group', 'size="4"', 'value', 'text', $params['group']);
		$settings['lists']['group_exp'] = mosHTML::selectList($sg, 'group_exp', 'size="4"', 'value', 'text',
										$params['group_exp']);

		$settings['set_group']			= array( 'list_yesno' );
		$settings['group']				= array( 'list' );
		$settings['set_group_exp']		= array( 'list_yesno' );
		$settings['group_exp']			= array('list');
		return $settings;
	}

	function detect_application()
	{
		global $mosConfig_absolute_path;

		return is_dir( $mosConfig_absolute_path . '/components/com_fireboard' );
	}

	function expiration_action( $params, $userid, $plan )
	{
		global $database;

		if ($params['set_group_exp']) {
			$query = 'UPDATE #__fb_users'
			. ' SET group_id = \'' . $params['group_exp'] . '\''
			. ' WHERE userid = \'' . $userid . '\''
			;

			// Carry out query
			$database->setQuery( $query );
			$database->query();
		}

		return true;
	}

	function action( $params, $userid, $plan )
	{
		global $database;

		if ( $params['set_group'] ) {
			// Check if exists - users only appear in FB users table normally when they have posted
			$query = 'SELECT group_id'
			. ' FROM #__fb_users'
			. ' WHERE userid = \'' . $userid . '\''
			;
			$database->setQuery( $query );

			// If already an entry exists -> update, if not -> create
			if ( $database->loadResult() ) {
				$query = 'UPDATE #__fb_users'
				. ' SET group_id = \'' . $params['group'] . '\''
				. ' WHERE userid = \'' . $userid . '\''
				;
			} else {
				$query = 'INSERT INTO #__fb_users'
				. ' ( `group_id` , `userid` )'
				. ' VALUES (\'' . $params['group'] . '\', \'' . $userid . '\')'
				;
			}

			// Carry out query
			$database->setQuery( $query );
			$database->query();
		}

		return true;
	}
}

?>