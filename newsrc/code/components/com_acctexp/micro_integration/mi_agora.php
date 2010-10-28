<?php
/**
 * @version $Id: mi_agora.php
 * @package AEC - Account Control Expiration - Membership Manager
 * @subpackage Micro Integrations - Agora
 * @copyright Copyright (C) 2010 David Deutsch
 * @author David Deutsch <skore@skore.de> & Team AEC - http://www.valanx.org
 * @license GNU/GPL v.2 http://www.gnu.org/licenses/old-licenses/gpl-2.0.html or, at your option, any later version
 */

// Dont allow direct linking
defined('_JEXEC') OR defined( '_VALID_MOS' ) OR die( 'Direct Access to this location is not allowed.' );

class mi_agora extends MI
{
	function Info()
	{
		$info = array();
		$info['name'] = _AEC_MI_NAME_AGORA;
		$info['desc'] = _AEC_MI_DESC_AGORA;

		return $info;
	}

	function Settings()
	{
		$db = &JFactory::getDBO();
		$db->setQuery( 'SELECT `id`, `name` FROM #__agora_group' );

		$groups = $db->loadObjectList();

		$grouplist = array();
		$grouplist[] = JHTML::_('select.option', 0, "--- --- ---" );

		if ( !empty( $groups ) ) {
			foreach ( $groups as $id => $row ) {
				$grouplist[] = JHTML::_('select.option', $row->id, $row->id . ': ' . $row->name );
			}
		}

		$db = &JFactory::getDBO();
		$db->setQuery( 'SELECT `id`, `name` FROM #__agora_roles' );

		$roles = $db->loadObjectList();

		$rolelist = array();
		$rolelist[] = JHTML::_('select.option', 0, "--- --- ---" );

		if ( !empty( $roles ) ) {
			foreach ( $roles as $id => $row ) {
				$rolelist[] = JHTML::_('select.option', $row->id, $row->id . ': ' . $row->name );
			}
		}

		$settings['group']		= array( 'list' );
		$settings['role']		= array( 'list' );
		$settings['ungroup']	= array( 'list' );
		$settings['unrole']		= array( 'list' );

		$settings = $this->autoduplicatesettings( $settings );

		foreach ( $settings as $k => $v ) {
			if ( isset( $this->settings[$k] ) ) {
				$value = $this->settings[$k];
			} else {
				$value = '';
			}

			if ( strpos( $k, "role" ) !== false ) {
				$settings['lists'][$k]	= JHTML::_('select.genericlist', $rolelist, $k, 'size="1"', 'value', 'text', $value );
			} else {
				$settings['lists'][$k]	= JHTML::_('select.genericlist', $grouplist, $k, 'size="1"', 'value', 'text', $value );
			}
		}

		$settings['rebuild']	= array( 'list_yesno' );
		$settings['remove']		= array( 'list_yesno' );

		return $settings;
	}

	function relayAction( $request )
	{
		$agora_userid = $this->AgoraUserId( $request->metaUser->userid );

		if ( !$agora_userid ) {
			$this->createUser( $request->metaUser );

			$agora_userid = $this->AgoraUserId( $request->metaUser->userid );
		}

		if ( !empty( $this->settings['group' . $request->area] ) && !empty( $this->settings['role' . $request->area] ) ) {
			$role = $this->getUserGroupRole( $agora_userid, $this->settings['group' . $request->area] );

			if ( empty( $role ) ) {
				$this->addGroup( $agora_userid, $this->settings['group' . $request->area], $this->settings['role' . $request->area] );
			} else {
				$this->updateRole( $agora_userid, $this->settings['group' . $request->area], $this->settings['role' . $request->area] );
			}
		}

		if ( !empty( $this->settings['ungroup' . $request->area] ) ) {
			$role = $this->getUserGroupRole( $agora_userid, $this->settings['ungroup' . $request->area] );

			if ( !empty( $role ) ) {
				$this->removeGroup( $agora_userid, $this->settings['ungroup' . $request->area], $this->settings['unrole' . $request->area] );
			}
		}

		return true;
	}

	function AgoraUserId( $userid )
	{
		$db = &JFactory::getDBO();

		$query = 'SELECT id FROM #__agora_users'
				. ' WHERE `jos_id` = \'' . $userid . '\''
				;
		$db->setQuery( $query );

		return $db->loadResult();
	}

	function getUserGroupRole( $userid, $groupid )
	{
		$db = &JFactory::getDBO();

		$query = 'SELECT `role` FROM #__agora_user_group'
				. ' WHERE `user_id` = \'' . $userid . '\''
				. ' AND `group_id` = \'' . $groupid . '\''
				;
		$db->setQuery( $query );

		return $db->loadResult();
	}

	function createUser( $metaUser )
	{
		$db = &JFactory::getDBO();

		$query = 'INSERT INTO #__agora_users'
				. ' (`jos_id`,  `username`, `email`, `registered`, `last_visit` )'
				. ' VALUES (\'' . $metaUser->userid . '\', \'' . $metaUser->cmsUser->username . '\', \''
				. $metaUser->cmsUser->email . '\', \'' . intval( strtotime( $metaUser->cmsUser->registerDate )) . '\', \''
				. intval( strtotime( $metaUser->cmsUser->lastvisitDate )) . '\', )'
				;
		$db->setQuery( $query );

		return $db->query();
	}

	function removeGroup( $userid, $groupid, $roleid )
	{
		$db = &JFactory::getDBO();

		$query = 'DELETE FROM #__agora_user_group'
				. ' WHERE `user_id` = \'' . $userid . '\''
				. ' AND `group_id` = \'' . $groupid . '\''
				;

		if ( !empty( $roleid ) ) {
			$query .= ' AND `role_id` = \'' . $roleid . '\'';
		}

		$db->setQuery( $query );

		return $db->query();
	}

	function addGroup( $userid, $groupid, $roleid )
	{
		$db = &JFactory::getDBO();

		$query = 'INSERT INTO #__agora_user_group'
				. ' (`user_id`,  `group_id`, `role_id` )'
				. ' VALUES (\'' . $userid . '\', \'' . $groupid . '\', \'' . $roleid . '\' )'
				;
		$db->setQuery( $query );

		return $db->query();
	}

	function updateRole( $userid, $groupid, $roleid )
	{
		$db = &JFactory::getDBO();

		$query = 'UPDATE #__agora_user_group'
				. ' SET `role_id` = \'' . $roleid . '\''
				. ' WHERE `user_id` = \'' . $userid . '\''
				. ' AND `group_id` = \'' . $groupid . '\''
				;
		$db->setQuery( $query );

		return $db->loadResult();
	}

}