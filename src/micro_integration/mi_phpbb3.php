<?php
/**
 * @version $Id: mi_fireboard.php 01 2007-08-11 13:29:29Z SBS $
 * @package AEC - Account Control Expiration - Subscription component for Joomla! OS CMS
 * @subpackage Micro Integrations - Fireboard
 * @copyright 2006/2007 Copyright (C) David Deutsch
 * @author Calum Polwart & Team AEC - http://www.globalnerd.org
 * @license GNU/GPL v.2 http://www.gnu.org/copyleft/gpl.html
 * Based on code from the mi_remository.php file from sk0re.
 */

// Dont allow direct linking
defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );

class mi_phpbb3
{

	function Info()
	{
		$info = array();
		$info['name'] = _AEC_MI_NAME_PHPBB3;
		$info['desc'] = _AEC_MI_DESC_PHPBB3;

		return $info;
	}

	function Settings()
	{
		global $database;

		$query = 'SELECT `group_id`, `group_name`, `group_colour`'
			 	. ' FROM phpbb_groups'
			 	;
	 	$database->setQuery( $query );
	 	$groups = $database->loadObjectList();

		$sg = array();
		foreach ( $groups as $group ) {
			$sg[] = mosHTML::makeOption( $group->group_id, $group->group_name );
						$sg2[] = mosHTML::makeOption( $group->group_colour, $group->group_name );
		}

		$settings = array();

		$settings['lists']['group']		= mosHTML::selectList($sg, 'group', 'size="4"', 'value', 'text', $this->settings['group']);
		$settings['lists']['group_exp'] = mosHTML::selectList($sg, 'group_exp', 'size="4"', 'value', 'text', $this->settings['group_exp']);
		$settings['lists']['group_colour'] = mosHTML::selectList($sg2, 'group_colour', 'size="4"', 'value', 'text', $this->settings['group_colour']);
		$settings['lists']['group_colour_exp'] = mosHTML::selectList($sg2, 'group_colour_exp', 'size="4"', 'value', 'text', $this->settings['group_colour_exp']);

		$settings['set_group']			= array( 'list_yesno' );
		$settings['group']				= array( 'list' );
		$settings['group_colour']        = array( 'list' );
		$settings['set_group_exp']		= array( 'list_yesno' );
		$settings['group_exp']			= array( 'list' );
		$settings['group_colour_exp']  = array( 'list' );
		$settings['rebuild']			= array( 'list_yesno' );
		$settings['remove']				= array( 'list_yesno' );

		return $settings;
	}

	function detect_application()
	{
		global $mosConfig_absolute_path;

		return is_dir( $mosConfig_absolute_path . '/distribution/' );
	}

	function expiration_action( $request )
	{
		global $database;

		if ( $this->settings['set_group_exp'] ) {
			$query = 'UPDATE phpbb_users'
						. ' SET `group_id` = \'' . $this->settings['group_exp'] . '\', `user_colour` = \'' . $this->settings['group_colour_exp'] . '\''
				. ' WHERE `username` = \'' . $request->metaUser->cmsUser->username . '\''
				;
			$database->setQuery( $query );
			$database->query();
		}

		return true;
	}

	function action( $request )
	{
		global $database;

		if ( $this->settings['set_group'] ) {
			// get the user phpbb user id - as its differnt from joomla
			$query = 'SELECT `user_id`'
					. ' FROM phpbb_users'
					. ' WHERE `username` = \'' . $request->metaUser->cmsUser->username . '\''
					;
			$database->setQuery( $query );
						$phpbbuser = $database->loadObjectList();
						$phpbbuser = $phpbbuser[0]->user_id;

			// If already an entry exists -> update, if not -> create
			if ( $phpbbuser ) {
								$query = 'UPDATE phpbb_users'
												. ' SET `group_id` = \'' . $this->settings['group'] . '\', `user_colour` = \'' . $this->settings['group_colour'] . '\''
												. ' WHERE `user_id` = \'' . $phpbbuser . '\''
												;
												// Carry out query
						$database->setQuery( $query );
						$database->query();
						$query = 'UPDATE phpbb_user_group'
												. ' SET `group_id` = \'' . $this->settings['group'] . '\''
												. ' WHERE `user_id` = \'' . $phpbbuser . '\''
												;
												// Carry out query
						$database->setQuery( $query );
						$database->query();
			} else {
				$GLOBALS['TEMP_USER'] = $user_data;
				global $phpbb_root_path, $phpEx;
				global $auth, $user, $template, $cache, $db, $config;

				//Include the bridge configuration
				$path = JPATH_ROOT.DS.'forum';
				require_once($path.DS.'includes'.DS.'helper.php');

				JForumHelper::loadPHPBB3($path);

				require_once($phpbb_root_path.DS.'includes/functions_user.php');

				//$fullname = $this->_fullNameSupport();
				$userid   = $this->_getUserId($username, $fullname);
				$login_name        = $user_data['username'];
				$username          = $request->metaUser->cmsUser->username;
				$username_clean    = utf8_clean_string($username);
				$user_email        = $request->metaUser->cmsUser->email;
				$user_email_hash   = crc32($user_email) . strlen($user_email);

				$query = 'INSERT INTO phpbb_users'
				. ' ( `user_id` ,`group_id` , `username`, `username_clean`, `user_email`, `user_email_hash`, `user_colour` )'
				. ' VALUES (\'' . $userid . '\',\'' . $this->settings['group'] . '\', \'' . $username . '\', \'' . $username_clean . '\', \'' . $user_email . '\', \'' . $user_email_hash . '\', \'' . $this->settings['group_colour'] . '\')'
				;
				$database->setQuery( $query );
				$database->query();
			}

		}

		return true;
	}


		/*
		 * function to get username based on fullname support
		 */
		function _getUserId($username, $fullname)
		{
				global $db;

				// if login_name exists use it
				if (!empty($fullname)) {
						$where = "login_name='" . $username . "'";
				} else {
						$where = "username_clean='" . utf8_clean_string($username) . "'";
				}


				// Get the user_id of the phpbb user
				$sql = "SELECT user_id FROM ".USERS_TABLE." WHERE " . $where;

				$result = $db->sql_query($sql);
				$userid = $db->sql_fetchrow($result);
				$db->sql_freeresult($result);

				return $userid;
		}
}

?>