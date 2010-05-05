<?php
/**
 * @version $Id: mi_phpbb3.php 01 2007-08-11 13:29:29Z SBS $
 * @package AEC - Account Control Expiration - Subscription component for Joomla! OS CMS
 * @subpackage Micro Integrations - phpBB3
 * @copyright 2006/2007 Copyright (C) David Deutsch
 * @author Calum Polwart, Jon Goldman, David Deutsch & Team AEC - http://www.valanx.org
 * @license GNU/GPL v.2 http://www.gnu.org/copyleft/gpl.html
 * Based on code from the mi_remository.php and mi_juga.php by David Deutsch.
 */

// Dont allow direct linking
( defined('_JEXEC') || defined( '_VALID_MOS' ) ) or die( 'Direct Access to this location is not allowed.' );

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
		$database = $this->getDB();

		if ( !empty( $this->settings['table_prefix'] ) ) {
			$prefix = $this->settings['table_prefix'];
		} else {
			$prefix = 'phpbb_';
		}

		$query = 'SELECT `group_id`, `group_name`, `group_colour`'
			 	. ' FROM ' . $prefix . 'groups'
			 	;
	 	$database->setQuery( $query );
	 	$groups = $database->loadObjectList();

		$sg		= array();
		$sg2	= array();
		if ( !empty( $groups ) ) {
			foreach ( $groups as $group ) {
				$sg[] = mosHTML::makeOption( $group->group_id, $group->group_name );
				$sg2[] = mosHTML::makeOption( $group->group_colour, $group->group_name );
			}
		}

         // Explode the Groups to Exclude
         if ( !empty($this->settings['groups_exclude'] ) ) {
     		$selected_groups_exclude = array();

     		foreach ( $this->settings['groups_exclude'] as $group_exclude ) {
     			$selected_groups_exclude[]->value = $group_exclude;
     		}
     	} else {
     		$selected_groups_exclude			= '';
     	}

		$settings = array();

		$settings['use_altdb']		= array( 'list_yesno' );
		$settings['dbms']			= array( 'inputC' );
		$settings['dbhost']			= array( 'inputC' );
		$settings['dbuser']			= array( 'inputC' );
		$settings['dbpasswd']		= array( 'inputC' );
		$settings['dbname']			= array( 'inputC' );
		$settings['table_prefix']	= array( 'inputC' );

		$s = array( 'group', 'group_exp', 'group_colour', 'group_colour_exp' );

		foreach ( $s as $si ) {
			$v = null;
			if ( isset( $this->settings[$si] ) ) {
				$v = $this->settings[$si];
			}

			if ( strpos( $si, 'color' ) !== false ) {
				$settings['lists'][$si]	= mosHTML::selectList( $sg2, $si, 'size="4"', 'value', 'text', $v );
			} else {
				$settings['lists'][$si]	= mosHTML::selectList( $sg, $si, 'size="4"', 'value', 'text', $v );
			}
		}

		$settings['lists']['groups_exclude']	= mosHTML::selectList( $sg, 'groups_exclude[]', 'size="10" multiple="true"', 'value', 'text', $selected_groups_exclude );

		$settings['set_group']				= array( 'list_yesno' );
		$settings['group']					= array( 'list' );
		$settings['set_group_exp']			= array( 'list_yesno' );
		$settings['group_exp']				= array( 'list' );
		$settings['apply_colour_exp']		= array( 'list_yesno' );
		$settings['group_colour_exp']		= array( 'list' );
		$settings['groups_exclude']			= array( 'list' );
		$settings['set_groups_exclude']		= array( 'list_yesno' );
		$settings['set_clear_groups']		= array( 'list_yesno' );
		$settings['rebuild']				= array( 'list_yesno' );
		$settings['remove']					= array( 'list_yesno' );

		$userdetails = array(	'user_type', 'username', 'user_email', 'user_email_hash',
								'user_birthday', 'user_lang', 'user_timezone', 'user_dst',
								'user_dateformat', 'user_style', 'user_rank', 'user_colour',
								'user_from', 'user_website' );

		$settings['create_user']			= array( 'list_yesno' );

		foreach ( $userdetails as $key ) {
			$settings['groups_exclude']			= array( 'inputC' );
		}

		return $settings;
	}

	function Defaults()
	{
		$settings = array();

		$settings['use_altdb']		= 0;
		$settings['dbms']			= 'mysqli';
		$settings['dbhost']			= 'localhost';
		$settings['dbuser']			= '';
		$settings['dbpasswd']		= '';
		$settings['dbname']			= '';
		$settings['table_prefix']	= 'phpbb_';

		return $settings;
	}

	function expiration_action( $request )
	{
		$database = $this->getDB();

		if ( $this->settings['set_group_exp'] ) {
			$userid = $request->metaUser->userid;

			$bbuser = null;
			// Get user info from PHPBB3 User Record
			$query = 'SELECT `user_id`, `group_id`'
					. ' FROM ' . $this->settings['table_prefix'] . 'users'
					. ' WHERE LOWER(user_email) = \'' . strtolower( $request->metaUser->cmsUser->email ) . '\''
					;
			$database->setQuery( $query );
			if ( aecJoomla15check() ) {
				$bbuser = $database->loadObject();
			} else {
				$database->loadObject($bbuser);
			}

			// check PHPBB3 primary group not on excluded list
			if ( in_array( $bbuser->group_id, $this->settings['groups_exclude'] ) ) {
				$onExcludeList = true;
			} else {
				$onExcludeList = false;
			}

			// check PHPBB3 secondary groups not on excluded list as long as primary group isn't already
			if ( ( $this->settings['set_groups_exclude'] ) && ( !$onExcludeList ) ) {
				$secGroups = null;
				$query = 'SELECT `group_id`'
						. ' FROM ' . $this->settings['table_prefix'] . 'user_group'
						. ' WHERE `user_id` = \'' . $bbuser->user_id . '\''
						;
				$database->setQuery( $query );
				if ( aecJoomla15check() ) {
					$secGroups = $database->loadObject();
				} else {
					$database->loadObject($secGroups);
				}

			 	foreach ( $secGroups as $secGroup ) {
					if ( in_array( $secGroup, $this->settings['groups_exclude'] ) ) {
						$onExcludeList = true;
						break;
					}
				}
			}

			$queries = array();

			// If Not On Exclude List, apply expiration group & clear secondary groups (if set)
			if ( !$onExcludeList ) {
				// update PHPBB3 groups list
				$queries[] = 'UPDATE ' . $this->settings['table_prefix'] . 'user_group'
						. ' SET `group_id` = \'' . $this->settings['group_exp'] . '\''
						. ' WHERE `group_id` = \'' . $bbuser->group_id . '\''
						. ' AND `user_id` = \'' . $bbuser->user_id . '\''
						;

				if ( $this->settings['apply_colour_exp'] ) {
					$color = ', `user_colour` = \'' . $this->settings['group_colour_exp'] . '\'';
				} else {
					$color = '';
				}

				$queries[] = 'UPDATE ' . $this->settings['table_prefix'] . 'users'
							. ' SET `group_id` = \'' . $this->settings['group_exp'] . '\'' . $color
							. ' WHERE `user_id` = \'' . $bbuser->user_id . '\''
							;

				// Clear Secondary Groups (if flag set)
				if ( $this->settings['set_clear_groups'] ) {
					$queries[] = 'DELETE FROM ' . $this->settings['table_prefix'] . 'user_group'
							. ' WHERE `group_id` != \'' . $this->settings['group_exp'] . '\''
							. ' AND `user_id` = \'' . $bbuser->user_id . '\''
							;
				}
			}

			foreach ( $queries as $query ) {
				$database->setQuery( $query );
				$database->query();
			}
		}

		return true;
	}

	function action( $request )
	{
		$database = $this->getDB();

		if ( $this->settings['set_group'] ) {
			$bbuser = null;
			// get the user phpbb user id
			$query = 'SELECT `user_id`, `group_id`'
					. ' FROM ' . $this->settings['table_prefix'] . 'users'
					. ' WHERE LOWER(user_email) = \'' . strtolower( $request->metaUser->cmsUser->email ) . '\''
					;
			$database->setQuery( $query );
			if ( aecJoomla15check() ) {
				$bbuser = $database->loadObject();
			} else {
				$database->loadObject($bbuser);
			}

			// check PHPBB3 primary group not on excluded list
			if ( in_array( $bbuser->group_id, $this->settings['groups_exclude'] ) ) {
				$onExcludeList = true;
			} else {
				$onExcludeList = false;
			}

			// check PHPBB3 secondary groups not on excluded list as long as primary group isn't already
			if ( ( $this->settings['set_groups_exclude'] ) && ( !$onExcludeList ) && !empty( $this->settings['groups_exclude'] ) ) {
				$secGroups = null;
				$query = 'SELECT `group_id`'
						. ' FROM ' . $this->settings['table_prefix'] . 'user_group'
						. ' WHERE `user_id` = \'' . $bbuser->user_id . '\''
						;
				$database->setQuery( $query );
				if ( aecJoomla15check() ) {
					$secGroups = $database->loadObject();
				} else {
					$database->loadObject($secGroups);
				}

			 	foreach ( $secGroups as $secGroup ) {
					if ( in_array( $secGroup, $this->settings['groups_exclude'] ) ) {
						$onExcludeList = true;
						break;
					}
				}
			}

			// If Not On Exclude List, apply expiration group & clear secondary groups (if set)
			if ( !$onExcludeList ) {
				// update PHPBB3 groups list
				$queries[] = 'UPDATE ' . $this->settings['table_prefix'] . 'user_group'
						. ' SET `group_id` = \'' . $this->settings['group'] . '\''
						. ' WHERE `group_id` = \'' . $bbuser->group_id . '\''
						. ' AND `user_id` = \'' . $bbuser->user_id . '\''
						;
				// update PHPBB3 primary group
				if ( $this->settings['apply_colour'] ) {
					$color = ', `user_colour` = \'' . $this->settings['group_colour'] . '\'';
				} else {
					$color = '';
				}

				$queries[] = 'UPDATE ' . $this->settings['table_prefix'] . 'users'
							. ' SET `group_id` = \'' . $this->settings['group'] . '\'' . $color
							. ' WHERE `user_id` = \'' . $bbuser->user_id . '\''
							;
			}

			foreach ( $queries as $query ) {
				$database->setQuery( $query );
				$database->query();
			}
		}

		return true;
	}

	function phpbbUserid( $db, $email )
	{
		$query = 'SELECT `user_id`'
				. ' FROM ' . $this->settings['table_prefix'] . 'users'
				. ' WHERE LOWER( `user_email` ) = \'' . $email . '\''
				;
		$db->setQuery( $query );

		return $db->loadResult();
	}

	function createUser( $db )
	{

	}

	function updateUser( $db, $userid, $fields )
	{

	}

	function getUserFields( $db )
	{
		$excluded = array(	"user_id",
							"user_permissions", "user_perm_from", "user_ip", "user_regdate", "username_clean",
							"user_password", "user_passchg", "user_pass_convert", "user_email", "user_email_hash",
							"user_lastvisit", "user_lastmark", "user_lastpost_time", "user_lastpage", "user_last_confirm_key",
							"user_last_search", "user_warnings", "user_last_warning", "user_login_attempts", "user_inactive_reason",
							"user_inactive_time", "user_posts", "user_dateformat", "user_style", "user_new_privmsg",
							"user_unread_privmsg", "user_last_privmsg", "user_message_rules", "user_full_folder", "user_emailtime",
							"user_topic_show_days", "user_topic_sortby_type", "user_topic_sortby_dir", "user_post_show_days", "user_post_sortby_type",
							"user_post_sortby_dir", "user_notify", "user_notify_pm", "user_notify_type", "user_allow_pm",
							"user_allow_viewonline", "user_allow_viewemail", "user_allow_massemail", "user_options", "user_avatar",
							"user_avatar_type", "user_avatar_width", "user_avatar_height", "user_sig", "user_sig_bbcode_uid",
							"user_sig_bbcode_bitfield", "user_from", "user_icq", "user_aim", "user_yim",
							"user_msnm", "user_jabber", "user_website", "user_occ", "user_interests",
							"user_actkey", "user_newpasswd", "user_form_salt"
							);

		$query = 'SHOW COLUMNS FROM #__users';
		$db->setQuery( $query );

		$fields = $db->loadResultArray();

		$return = array();
		foreach ( $fields as $key ) {
			if ( !in_array( $key, $excluded ) ) {
				$return[] = $key;
			}
		}

		return $return;
	}

	function userGroups( $db, $userid )
	{
		$query = 'SELECT `group_id`'
				. ' FROM ' . $this->settings['table_prefix'] . 'user_group'
				. ' WHERE `user_id` = \'' . $userid . '\''
				;
		$db->setQuery( $query );

		return $db->loadResultArray();
	}

	function assignGroup( $db, $userid, $groupid )
	{
		$query = 'INSERT INTO ' . $this->settings['table_prefix'] . 'user_group'
				. ' (`user_id`, `group_id` )'
				. ' VALUES ( \'' . $userid . '\', \'' . $groupid . '\' )'
				;
		$db->setQuery( $query );

		return $db->loadResult();
	}

	function removeGroup( $db, $userid, $groupid )
	{
		$query = 'DELETE'
				. ' FROM ' . $this->settings['table_prefix'] . 'user_group'
				. ' WHERE `user_id` = \'' . $userid . '\''
				. ' AND `group_id` = \'' . $groupid . '\''
				;
		$db->setQuery( $query );

		return $db->loadResult();
	}

	function getDB()
	{
        if ( !empty( $this->settings['use_altdb'] ) ) {
	        $options = array(	'driver'	=> $this->settings['dbms'],
								'host'		=> $this->settings['dbhost'],
								'user'		=> $this->settings['dbuser'],
								'password'	=> $this->settings['dbpasswd'],
								'database'	=> $this->settings['dbname'],
								'prefix'	=> $this->settings['table_prefix']
								);

	        $database = &JDatabase::getInstance($options);
        } else {
        	$database = &JFactory::getDBO();
        }

		return $database;
	}

}

?>