<?php
/**
 * @version $Id: mi_g2.php 16 2007-07-02 13:29:29Z mic $
 * @package AEC - Account Control Expiration - Subscription component for Joomla! OS CMS
 * @subpackage Micro Integrations - G2
 * @copyright 2008 Copyright (C) David Deutsch
 * @author David Deutsch <skore@skore.de> & Team AEC - http://www.globalnerd.org
 * @license GNU/GPL v.2 http://www.gnu.org/copyleft/gpl.html
 */

// Dont allow direct linking
defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );

class mi_g2 extends MI
{
	function Info()
	{
		$info = array();
		$info['name'] = _AEC_MI_NAME_G2;
		$info['desc'] = _AEC_MI_DESC_G2;

		return $info;
	}

	function Settings()
	{
		$settings = array();
		$settings['set_groups']		= array( 'list_yesno' );
		$settings['groups']			= array( 'inputA' );
		$settings['groups_scope']	= array( 'list' );
		$settings['del_groups_exp']	= array( 'list_yesno' );

		$query = 'SELECT `g_id`, `g_groupType`, `g_groupName`'
			 	. ' FROM g2_Group'
			 	;
	 	$database->setQuery( $query );
	 	$groups = $database->loadObjectList();

		$g = explode( ';', $this->settings['group_scope'] );
		$sg = array();

		$gr = array();
		foreach( $groups as $group ) {
			$desc = $group->g_groupName . '' . substr( strip_tags( "" ), 0, 30 );

			$gr[] = mosHTML::makeOption( $group->g_id, $desc );

			if ( in_array( $group->g_id, $g ) ) {
				$sg[] = mosHTML::makeOption( $group->g_id, $desc );
			}
		}

		$sel_groups = array();
		foreach ($groups as $name ) {
			$selected_groups[] = mosHTML::makeOption( $name, $name );
		}

		return $settings;
	}

	function pre_expiration_action( $request )
	{}

	function expiration_action( $request )
	{}

	function action( $request )
	{
		$g2userid = $this->G2userid( $request->metaUser );

		if ( $this->settings['set_groups'] ) {
			for ( $i=0; $i<$this->settings['groups']; $i++ ) {
				if ( isset( $request->params['g2group_'.$i] ) ) {
					$this->mapUserToGroup( $g2userid, $request->params['g2group_'.$i] );
				}
			}
		}
	}

	function on_userchange_action( $request )
	{}

	function profile_info( $userid )
	{}

	function mapUserToGroup( $g2userid, $groupid )
	{
		global $database;

		$query = 'SELECT g_userId'
				. ' FROM g2_UserGroupMap'
				. ' WHERE `g_userId` = \'' . $g2userid . '\' AND `g_groupId` = \'' . $groupid . '\''
				;
		$database->setQuery( $query );

		if ( !$database->loadResult() ) {
			$query = 'INSERT INTO g2_UserGroupMap'
					. ' ( `g_userId`, `g_groupId` )'
					. ' VALUES ( \'' . $g2userid . '\', \'' . $groupid . '\' )'
					;
			$database->setQuery( $query );
			return $database->query();
		} else {
			return null;
		}
	}

	function deleteUserFromGroup( $g2userid, $groupid )
	{
		global $database;

		$query = 'DELETE FROM g2_UserGroupMap'
				. ' WHERE `g_userId` = \'' . $g2userid . '\' AND `g_groupId` = \'' . $groupid . '\''
				;
		$database->setQuery( $query );
		return $database->query();
	}

	function G2userid( $metaUser )
	{
		global $database;

		$query = 'SELECT g_id'
				. ' FROM g2_User'
				. ' WHERE `g_username` = \'' . $metaUser->username . '\''
				;
		$database->setQuery( $query );

		$g2id = $database->loadResult();

		if ( $g2id ) {
			// User found, return id
			return $g2id;
		} else {
			// User not found, create user, then recurse
			$this->createG2User( $metaUser );
			$this->G2userid( $metaUser);
		}
	}

	function createG2User( $metaUser )
	{
		global $database;

		$query = 'INSERT INTO g2_User'
				. ' ( `g_userName`, `g_fullName`, `g_hashedPassword`, `g_email` )'
				. ' VALUES ( \'' . $metaUser->username . '\', \'' . $metaUser->name . '\', \'' . $metaUser->password . '\', \'' . $metaUser->email . '\' )'
				;
		$database->setQuery( $query );

		if ( $database->query() ) {
			return true;
		} else {
			$this->setError( $database->getErrorMsg() );
			return false;
		}
	}
}

?>