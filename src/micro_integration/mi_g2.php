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
		global $database;

		$settings = array();
		$settings['set_groups']			= array( 'list_yesno' );
		$settings['groups']				= array( 'list' );
		$settings['set_groups_user']	= array( 'list_yesno' );
		$settings['groups_sel_amt']		= array( 'inputA' );
		$settings['groups_sel_scope']	= array( 'list' );
		$settings['del_groups_exp']		= array( 'list_yesno' );

		$query = 'SELECT `g_id`, `g_groupType`, `g_groupName`'
			 	. ' FROM g2_Group'
			 	;
	 	$database->setQuery( $query );
	 	$groups = $database->loadObjectList();

		$g = explode( ';', $this->settings['groups'] );
		$gs = explode( ';', $this->settings['groups_sel_scope'] );
		$sg = array();
		$sgs = array();

		$gr = array();
		foreach( $groups as $group ) {
			$desc = $group->g_groupName . '' . substr( strip_tags( "" ), 0, 30 );

			$gr[] = mosHTML::makeOption( $group->g_id, $desc );

			if ( in_array( $group->g_id, $g ) ) {
				$sg[] = mosHTML::makeOption( $group->g_id, $desc );
			}

			if ( in_array( $group->g_id, $gs ) ) {
				$sgs[] = mosHTML::makeOption( $group->g_id, $desc );
			}
		}

		$settings['groups']				= array( 'list' );
		$settings['lists']['groups']	= mosHTML::selectList( $gr, 'groups[]', 'size="6" multiple="multiple"', 'value', 'text', $sg );
		$settings['groups_sel_scope']			= array( 'list' );
		$settings['lists']['groups_sel_scope']	= mosHTML::selectList( $gr, 'groups_sel_scope[]', 'size="6" multiple="multiple"', 'value', 'text', $sgs );

		return $settings;
	}

	function saveparams( $params )
	{
		global $mosConfig_absolute_path, $database;

		$subgroups = array( 'groups', 'groups_sel_scope' );

		foreach ( $subgroups as $groupname ) {
			$temp = implode( ';', $params[$groupname] );
			$params[$groupname] = $temp;
		}

		return $params;
	}

	function getMIform()
	{
		global $database;

		$settings = array();

		if ( $this->settings['set_groups_user'] ) {
			$gs = explode( ';', $this->settings['groups_sel_scope'] );

			$query = 'SELECT `g_id`, `g_groupType`, `g_groupName`'
				 	. ' FROM g2_Group'
				 	. ' WHERE `g_id` IN (' . implode( ',', $gs ) . ')'
				 	;
		 	$database->setQuery( $query );
		 	$groups = $database->loadObjectList();

			$gr = array();
			foreach ( $groups as $group ) {
				$desc = $group->g_groupName . '' . substr( strip_tags( "" ), 0, 30 );

				$gr[] = mosHTML::makeOption( $group->g_id, $desc );
			}

			for ( $i=0; $i<$this->settings['groups_sel_amt']; $i++ ) {
				$settings['g2group_'.$i]			= array( 'list', _MI_MI_G2_USERSELECT_GROUP_NAME, _MI_MI_G2_USERSELECT_GROUP_DESC );
				$settings['lists']['g2group_'.$i]	= mosHTML::selectList( $gr, 'g2group_'.$i, 'size="6"', 'value', 'text', '' );
			}
		} else {
			return false;
		}

		return $settings;
	}

	function pre_expiration_action( $request )
	{}

	function expiration_action( $request )
	{}

	function action( $request )
	{
		$g2userid = $this->catchG2userid( $request->metaUser );

		if ( $this->settings['set_groups'] ) {
			$groups = explode( ';', $this->settings['groups'] );
			foreach ( $groups as $groupid ) {
				$this->mapUserToGroup( $g2userid, $groupid );
			}
		}

		if ( $this->settings['set_groups_user'] ) {
			for ( $i=0; $i<$this->settings['groups_sel_amt']; $i++ ) {
				if ( isset( $request->params['g2group_'.$i] ) ) {
					$this->mapUserToGroup( $g2userid, $request->params['g2group_'.$i] );
				}
			}
		}

		return null;
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

			if ( $database->query() ) {
				return true;
			} else {
				$this->setError( $database->getErrorMsg() );
				return false;
			}
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

		if ( $database->query() ) {
			return true;
		} else {
			$this->setError( $database->getErrorMsg() );
			return false;
		}
	}

	function catchG2userid( $metaUser )
	{
		$g2id = $this->hasG2userid( $metaUser );

		if ( $g2id ) {
			// User found, return id
			return $g2id;
		} else {
			// User not found, create user, then recurse
			$this->createG2User( $metaUser );
			$this->catchG2userid( $metaUser );
		}
	}

	function hasG2userid( $metaUser )
	{
		global $database;

		$query = 'SELECT g_id'
				. ' FROM g2_User'
				. ' WHERE `g_userName` = \'' . $metaUser->cmsUser->username . '\''
				;
		$database->setQuery( $query );

		$g2id = $database->loadResult();
	}

	function createG2User( $metaUser )
	{
		global $database;

		// Create Entity
		$query = 'SELECT max(g_id)'
				. ' FROM g2_User'
				;
		$database->setQuery( $query );

		$entityid = $database->loadResult() + 1;

		$query = 'SELECT max(g_serialNumber)'
				. ' FROM g2_Entity'
				. ' WHERE `g_entityType` = \'GalleryUser\''
				;
		$database->setQuery( $query );

		$serial = $database->loadResult() + 1;

		$query = 'INSERT INTO g2_Entity'
				. ' ( `g_id`, `g_creationTimestamp`, `g_isLinkable`, `g_linkId`, `g_modificationTimestamp`, `g_serialNumber`, `g_entityType`, `g_onLoadHandlers` )'
				. ' VALUES ( \'' . $entityid . '\', \'' . time() . '\', \'0\', NULL, \'' . time() . '\', \'' . $serial . '\', \'GalleryUser\', NULL )'
				;
		$database->setQuery( $query );
		$database->query();

		$g2id = $entityid;

		$query = 'INSERT INTO g2_User'
				. ' ( `g_id`, `g_userName`, `g_fullName`, `g_hashedPassword`, `g_email`, `g_language`, `g_locked` )'
				. ' VALUES ( \'' . $g2id . '\', \'' . $metaUser->cmsUser->username . '\', \'' . $metaUser->cmsUser->name . '\', \'' . $metaUser->cmsUser->password . '\', \'' . $metaUser->cmsUser->email . '\', NULL, \'0\' )'
				;
		$database->setQuery( $query );
		$database->query();

		$query = 'INSERT INTO g2_ExternalIdMap'
				. ' ( `g_externalId`, `g_entityType`, `g_entityId` )'
				. ' VALUES ( \'' . $metaUser->cmsUser->id . '\', \'GalleryUser\', \'' . $g2id . '\' )'
				;
		$database->setQuery( $query );
		$database->query();

		// Add to standard groups
		$this->mapUserToGroup( $g2id, 2 );
		$this->mapUserToGroup( $g2id, 4 );

		if ( $database->query() ) {
			return true;
		} else {
			$this->setError( $database->getErrorMsg() );
			return false;
		}
	}

	function createEntity()
	{

	}
}

?>