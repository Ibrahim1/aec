<?php
/*
 * <name>mi_agora</name>
 * <creationDate>January 2010</creationDate>
 * <version>1.5.0</version>
 * <author>Joel Bassett</author>
 * <authorEmail>joel@aqsg.com.au</authorEmail>
 * <authorUrl>http://www.aqsg.com.au</authorUrl>
 * <copyright>(C) 2005-2010 Australian Quality Solutions Group. All rights reserved.</copyright>
 * <license>AGPLv3 - http://www.aqsg.com.au/products/simple-meta-management-suite/licence.html</license>
 */
// Dont allow direct linking
defined('_JEXEC') OR defined( '_VALID_MOS' ) OR die( 'Direct Access to this location is not allowed.' );

class mi_agora {
	function Info() {
		$info = array();
		$info['name'] = 'Agora Forum';
		$info['desc'] = 'Assign a Group and Role to an Agora user';

		return $info;
	}

	function Settings() {
		$db = &JFactory::getDBO();
		$settings = array();

		$db->setQuery('SELECT id, name FROM ' . $db->nameQuote('#__agora_group'));
		$groups = $db->loadObjectList();
		$grouplistsValues[] = JHTML::_('select.option', 1, '--- Global ---' );		
		foreach ($groups as $id => $row) {	
			if ($row->id > 1)
				$grouplistsValues[] = JHTML::_('select.option', $row->id, $row->name);		
		}
		
		$db->setQuery('SELECT id, name FROM ' . $db->nameQuote('#__agora_roles'));
		$roles = $db->loadObjectList();
		foreach ($roles as $id => $row) {	
			$roleslistsValues[] = JHTML::_('select.option', $row->id, ucfirst($row->name));		
		}

		$settings['sub_group'] 		= array( 'list','[Activation] Change the users group to the selected group', 'Select the group you want to apply to the user on plan activation' );
		$settings['sub_role'] 		= array( 'list','[Activation] Change the users role to the following role', 'Select the role you want to apply to the user from on plan activation' );
		$settings['exp_group'] 		= array( 'list','[Expiration] Remove the user from the selected group', 'Select the group you want to remove the user from on plan expiration');
		$settings['exp_role'] 		= array( 'list','[Expiration] Remove the user from the selected role', 'Select the group you want to remove the user from on plan expiration');
		
		$settings['lists']['sub_group']		= JHTML::_('select.genericlist',   $grouplistsValues, 'sub_group', 	'class="inputbox"', 'value', 'text', empty($this->settings['sub_group']) ? '' : $this->settings['sub_group']);
		$settings['lists']['sub_role']		= JHTML::_('select.genericlist',   $roleslistsValues, 'sub_role', 	'class="inputbox"', 'value', 'text', empty($this->settings['sub_role']) ? '' : $this->settings['sub_role']);
		$settings['lists']['exp_group']		= JHTML::_('select.genericlist',   $grouplistsValues, 'exp_group', 	'class="inputbox"', 'value', 'text', empty($this->settings['exp_group']) ? '' : $this->settings['exp_group']);
		$settings['lists']['exp_role']		= JHTML::_('select.genericlist',   $roleslistsValues, 'exp_role', 	'class="inputbox"', 'value', 'text', empty($this->settings['exp_role']) ? '' : $this->settings['exp_role']);

		return $settings;
	}

	function expiration_action( $request ) {
		$this->changeRank($request->metaUser->userid, $this->settings['exp_group'], $this->settings['exp_role']);
	}

	function action( $request ){
		$this->changeRank($request->metaUser->userid, $this->settings['sub_group'], $this->settings['sub_role']);
	}

	function checkAgoraUser($userid) {
		$db = &JFactory::getDBO();
		$db->setQuery('SELECT COUNT(*) FROM ' . $db->nameQuote('#__agora_users') . ' WHERE ' . $db->nameQuote('jos_id') . ' = ' . $db->Quote($userid));
		$count = $db->loadResult();
		if ($count < 1)
			$answer = false;
		else
			$answer = true;
		return $answer;
	}
	
	function checkRank($userid, $groupid) {
		$db = &JFactory::getDBO();
		$db->setQuery('SELECT COUNT(*) FROM ' . $db->nameQuote('#__agora_user_group') . ' WHERE ' . $db->nameQuote('user_id') . ' = ' . $db->Quote($userid) . ' AND ' . $db->nameQuote('group_id') . ' = ' . $db->Quote($groupid));
		$count = $db->loadResult();
		if ($count < 1)
			$answer = false;
		else
			$answer = true;
		return $answer;
	}

	function createUser($userid, $groupid) {
		$db = &JFactory::getDBO();
		$db->setQuery('SELECT username, email, registerDate, lastvisitDate FROM ' . $db->nameQuote('#__users') . ' WHERE ' . $db->nameQuote('id') . ' = ' . $db->Quote($userid));
		$user = $db->loadRow();
		$query = 'INSERT INTO ' . $db->nameQuote('#__agora_users') . ' (' . $db->nameQuote('jos_id') . ', ' . $db->nameQuote('username') . ', ' . $db->nameQuote('email') . ', ' . $db->nameQuote('registered') . ', ' . $db->nameQuote('last_visit') . ') VALUES (' . $userid . ', ' . $db->Quote($user[0]) . ', ' . $db->Quote($user[1]) . ', ' . $db->Quote(intval(strtotime($user[2]))) . ', ' . $db->Quote(intval(strtotime($user[3]))) . ')';
		$db->setQuery($query);
		if (!$db->query()) {
			JError::raiseError( 500, $db->stderr() );
			return false;
		}
		return true;
	}

	function removeRank($userid, $groupid, $roleid) {
		$db = &JFactory::getDBO();
		$query = 'DELETE FROM ' . $db->nameQuote('#__agora_user_group') . ' WHERE ' . $db->nameQuote('user_id') . ' = ' . $db->Quote($userid) . ' AND ' . $db->nameQuote('group_id') . ' = ' . $db->Quote($groupid) . ' AND ' . $db->nameQuote('role_id') . ' = ' . $db->Quote($roleid);
		$db->setQuery($query);
		if (!$db->query()) {
			JError::raiseError( 500, $db->stderr() );
			return false;
		}
		return true;
	}
	
	function createRank($userid, $groupid, $roleid) {
		$db = &JFactory::getDBO();
		$db->setQuery('SELECT id FROM #__agora_users WHERE jos_id = ' . intval($userid));
		$agoraid = $db->loadResult();
		$query = 'INSERT INTO ' . $db->nameQuote('#__agora_user_group') . ' (' . $db->nameQuote('user_id') . ', ' . $db->nameQuote('group_id') . ', ' . $db->nameQuote('role_id') . ') VALUES (' . $agoraid . ', ' . $groupid . ', ' . $roleid . ')';
		$db->setQuery($query);
		if (!$db->query()) {
			JError::raiseError( 500, $db->stderr() );
			return false;
		}
		return true;
	}
	
	function changeRank($userid, $groupid, $roleid) {
		$db = &JFactory::getDBO();
		$isUser 	= $this->checkAgoraUser($userid);
		$hasRank 	= $this->checkRank($userid, $groupid);
		if (!$isUser) {
			$this->createUser($userid, $groupid);
			if (!$hasRank)
				$this->createRank($userid, $groupid, $roleid);
			else
				$this->updateRank($userid, $groupid, $roleid);
		} else {
			if (!$hasRank)
				$this->createRank($userid, $groupid, $roleid);
			else
				$this->updateRank($userid, $groupid, $roleid);		
		}
	}
	
}