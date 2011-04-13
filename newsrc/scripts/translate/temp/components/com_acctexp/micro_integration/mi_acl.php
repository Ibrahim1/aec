<?php
/**
 * @version $Id: mi_acl.php
 * @package AEC - Account Control Expiration - Membership Manager
 * @subpackage Micro Integrations - ACL
 * @copyright 2006-2010 Copyright (C) David Deutsch
 * @author David Deutsch <skore@skore.de> & Team AEC - http://www.valanx.org
 * @license GNU/GPL v.2 http://www.gnu.org/licenses/old-licenses/gpl-2.0.html or, at your option, any later version
 */

// Dont allow direct linking
( defined('_JEXEC') || defined( '_VALID_MOS' ) ) or die( 'Direct Access to this location is not allowed.' );

class mi_acl
{
	function Info()
	{
		$info = array();
		$info['name'] = JText::_('AEC_MI_NAME_ACL');
		$info['desc'] = JText::_('AEC_MI_DESC_ACL');

		return $info;
	}

	function Settings()
	{
		$user = &JFactory::getUser();

		$settings = array();
		$settings['change_session']	= array( 'list_yesno' );

		$settings['set_gid']			= array( 'list_yesno' );
		$settings['gid']				= array( 'list' );
		$settings['set_gid_exp']		= array( 'list_yesno' );
		$settings['gid_exp']			= array( 'list' );
		$settings['set_gid_pre_exp']	= array( 'list_yesno' );
		$settings['gid_pre_exp']		= array( 'list' );

		$settings['jaclpluspro']		= array( 'list_yesno' );
		$settings['delete_subgroups']	= array( 'list_yesno' );

		$settings['sub_set_gid']			= array( 'list_yesno' );
		$settings['sub_gid_del']			= array( 'list' );
		$settings['sub_gid']				= array( 'list' );
		$settings['sub_set_gid_exp']		= array( 'list_yesno' );
		$settings['sub_gid_exp_del']		= array( 'list' );
		$settings['sub_gid_exp']			= array( 'list' );
		$settings['sub_set_gid_pre_exp']	= array( 'list_yesno' );
		$settings['sub_gid_pre_exp_del']	= array( 'list' );
		$settings['sub_gid_pre_exp']		= array( 'list' );

		$gtree = aecACLhandler::getGroupTree( array( 28, 29, 30 ) );

		$settings['lists']['gid'] 			= JHTML::_('select.genericlist', $gtree, 'gid', 'size="6"', 'value', 'text', ( empty( $this->settings['gid'] ) ? 18 : $this->settings['gid'] ) );
		$settings['lists']['gid_exp'] 		= JHTML::_('select.genericlist', $gtree, 'gid_exp', 'size="6"', 'value', 'text', ( empty( $this->settings['gid_exp'] ) ? 18 : $this->settings['gid_exp'] ) );
		$settings['lists']['gid_pre_exp'] 	= JHTML::_('select.genericlist', $gtree, 'gid_pre_exp', 'size="6"', 'value', 'text', ( empty( $this->settings['gid_pre_exp'] ) ? 18 : $this->settings['gid_pre_exp'] ) );

		$subgroups = array( 'sub_gid_del', 'sub_gid', 'sub_gid_exp_del', 'sub_gid_exp', 'sub_gid_pre_exp_del', 'sub_gid_pre_exp' );

		foreach ( $subgroups as $groupname ) {
			$selected = array();
			if ( !empty( $this->settings[$groupname] ) ) {
				foreach ( $this->settings[$groupname] as $value ) {
					$selected[]->value = $value;
				}
			}

			$settings['lists'][$groupname] = JHTML::_('select.genericlist', $gtree, $groupname.'[]', 'size="6" multiple="multiple"', 'value', 'text', $selected );
		}

		return $settings;
	}

	function relayAction( $request )
	{
		if ( !empty( $this->settings['sub_set_gid' . $request->area] ) ) {
			$this->jaclplusGIDchange( $request->metaUser, 'sub_gid' . $request->area );
		}

		if ( !empty( $this->settings['set_gid' . $request->area] ) ) {
			$this->instantGIDchange( $request->metaUser, $this->settings['gid' . $request->area] );
		}

		return true;
	}

	function instantGIDchange( $metaUser, $gid )
	{
		$db = &JFactory::getDBO();

		$acl = &JFactory::getACL();

		$sessionextra = array();

		if ( $this->settings['jaclpluspro'] ) {
			$gid_name = $acl->get_group_name( $gid, 'ARO' );

			// Check for main entry
			$query = 'SELECT `group_id`'
					. ' FROM #__jaclplus_user_group'
					. ' WHERE `id` = \'' . (int) $metaUser->userid . '\''
					. ' AND `group_type` = \'main\''
					;
			$db->setQuery( $query );

			if ( $db->loadResult() ) {
				$query = 'UPDATE #__jaclplus_user_group'
						. ' SET `group_id` = \'' . (int) $gid . '\''
						. ' WHERE `id` = \'' . (int) $metaUser->userid . '\''
						. ' AND `group_type` = \'main\''
						;
				$db->setQuery( $query );
				$db->query() or die( $db->stderr() );
			} else {
				$query = 'INSERT INTO #__jaclplus_user_group'
						. ' VALUES( \'' . (int) $metaUser->userid . '\', \'main\', \'' . (int) $gid . '\', \'\' )'
						;
				$db->setQuery( $query );
				$db->query() or die( $db->stderr() );
			}

			if ( $this->settings['change_session'] ) {
				// Get Session
				$query = 'SELECT *'
						. ' FROM #__session'
						. ' WHERE `userid` = \'' . (int) $metaUser->userid . '\''
						;
				$db->setQuery( $query );
				$session = $db->loadObject();

				if ( !empty( $session->userid ) ) {
					$query = 'SELECT `group_id`'
							. ' FROM #__jaclplus_user_group'
							. ' WHERE `id` = \'' . (int) $metaUser->userid . '\''
							;
					$db->setQuery( $query );
					$groups = $db->loadResultArray();

					$query = 'SELECT `value`'
							. ' FROM #__core_acl_aro_groups'
							. ' WHERE `id` IN (' . implode( ',', $groups ) . ')'
							;
					$db->setQuery( $query );
					$valuelist = $db->loadResultArray();

					$sessiongroups = array();
					foreach ( $valuelist as $vlist ) {
						$values = explode( ',', $vlist );

						$sessiongroups = array_merge( $sessiongroups, $values );
					}

					$sessiongroups = array_unique( $sessiongroups );

					asort( $sessiongroups );

					$sessionextra['gids']		= $gid;
					$sessionextra['jaclplus']	= implode( ',', $sessiongroups );
				}
			}

		}

		$metaUser->instantGIDchange( $gid, $this->settings['change_session'], $sessionextra );

		return true;
	}

	function jaclplusGIDchange( $metaUser, $section )
	{
		$db = &JFactory::getDBO();

		$acl = &JFactory::getACL();

		if ( $this->settings['delete_subgroups'] ) {
			// Delete sub entries
			$query = 'DELETE FROM #__jaclplus_user_group'
					. ' WHERE `id` = \'' . (int) $metaUser->userid . '\''
					. ' AND `group_type` = \'sub\''
					;
			$db->setQuery( $query );
			$db->query();

			$groups = array();
		} else {
			// Check for sub entries
			$query = 'SELECT `group_id`'
					. ' FROM #__jaclplus_user_group'
					. ' WHERE `id` = \'' . (int) $metaUser->userid . '\''
					. ' AND `group_type` = \'sub\''
					;
			$db->setQuery( $query );
			$groups = $db->loadResultArray();
		}

		if ( !empty( $this->settings[$section.'_del'] ) ) {
			foreach ( $this->settings[$section.'_del'] as $gid ) {
				if ( in_array( $gid, $groups ) ) {
					$query = 'DELETE FROM #__jaclplus_user_group'
							. ' WHERE `id` = \'' . (int) $metaUser->userid . '\''
							. ' AND `group_type` = \'sub\''
							. ' AND `group_id` = \'' . (int) $gid . '\''
							;
					$db->setQuery( $query );
					$db->query() or die( $db->stderr() );
				}


			}
		}

		if ( !empty( $this->settings[$section] ) ) {
			foreach ( $this->settings[$section] as $gid ) {
				if ( !in_array( $gid, $groups ) ) {
					$query = 'INSERT INTO #__jaclplus_user_group'
							. ' VALUES( \'' . (int) $metaUser->userid . '\', \'sub\', \'' . $gid . '\', \'\' )'
							;
					$db->setQuery( $query );
					$db->query() or die( $db->stderr() );
				}
			}
		}

		return true;
	}

}
?>
