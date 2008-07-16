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
		$settings['create_albums']		= array( 'list_yesno' );
		$settings['albums_name']		= array( 'inputC' );

		$query = 'SELECT `g_id`, `g_groupType`, `g_groupName`'
			 	. ' FROM g2_Group'
			 	;
	 	$database->setQuery( $query );
	 	$groups = $database->loadObjectList();

		$sg = array();
		$sgs = array();

		$gr = array();
		foreach( $groups as $group ) {
			$desc = $group->g_groupName . '' . substr( strip_tags( "" ), 0, 30 );

			$gr[] = mosHTML::makeOption( $group->g_id, $desc );

			if ( !empty( $this->settings['groups'] ) ) {
				if ( in_array( $group->g_id, $this->settings['groups'] ) ) {
					$sg[] = mosHTML::makeOption( $group->g_id, $desc );
				}
			}

			if ( !empty( $this->settings['groups_sel_scope'] ) ) {
				if ( in_array( $group->g_id, $this->settings['groups_sel_scope'] ) ) {
					$sgs[] = mosHTML::makeOption( $group->g_id, $desc );
				}
			}
		}

		$settings['groups']				= array( 'list' );
		$settings['lists']['groups']	= mosHTML::selectList( $gr, 'groups[]', 'size="6" multiple="multiple"', 'value', 'text', $sg );
		$settings['groups_sel_scope']			= array( 'list' );
		$settings['lists']['groups_sel_scope']	= mosHTML::selectList( $gr, 'groups_sel_scope[]', 'size="6" multiple="multiple"', 'value', 'text', $sgs );

		return $settings;
	}

	function getMIform()
	{
		global $database;

		$settings = array();

		if ( $this->settings['set_groups_user'] ) {
			$query = 'SELECT `g_id`, `g_groupType`, `g_groupName`'
				 	. ' FROM g2_Group'
				 	. ' WHERE `g_id` IN (' . implode( ',', $this->settings['groups_sel_scope'] ) . ')'
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
		global $database;

		$g2userid = $this->catchG2userid( $request->metaUser );

		$groups = array();

		if ( $this->settings['set_groups'] ) {
			$groups = $this->settings['groups'];
			foreach ( $groups as $groupid ) {
				$this->mapUserToGroup( $g2userid, $groupid );
				$groups[] = $groupid;
			}
		}

		if ( $this->settings['set_groups_user'] ) {
			for ( $i=0; $i<$this->settings['groups_sel_amt']; $i++ ) {
				if ( isset( $request->params['g2group_'.$i] ) ) {
					$this->mapUserToGroup( $g2userid, $request->params['g2group_'.$i] );
					$groups[] = $request->params['g2group_'.$i];
				}
			}
		}

		if ( !empty( $groups ) && !empty( $this->settings['create_albums'] ) && !empty( $this->settings['albums_name'] ) ) {
			array_unique( $groups );

			foreach ( $groups as $groupid ) {
				$query = 'SELECT `g_groupName`'
					 	. ' FROM g2_Group'
					 	. ' WHERE `g_id` = \'' . $groupid . '\''
					 	;
			 	$database->setQuery( $query );
			 	$groupname = $database->loadResult();

				if ( empty( $groupname ) ) {
					continue;
				}

				$query = 'SELECT `g_id`'
					 	. ' FROM g2_Item'
					 	. ' WHERE `g_title` = \'' . $groupname . '\''
					 	;
			 	$database->setQuery( $query );
			 	$parent = $database->loadResult();

				if ( empty( $parent ) ) {
					continue;
				}

				$this->createAlbumInAlbum( $g2userid, $parent, AECToolbox::rewriteEngineRQ( $this->settings['albums_name'], $request ) );
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

	function createAlbumInAlbum( $g2userid, $parentid, $albumname )
	{
		global $database;

		// Check that we don't create a duplicate
		$query = 'SELECT g_id'
				. ' FROM g2_Item'
				. ' WHERE `g_ownerId` = \'' . $g2userid . '\''
				. ' AND `g_title` = \'' . $albumname . '\''
				;
		$database->setQuery( $query );
		$eid = $database->loadResult();

		if ( $eid ) {
			$query = 'SELECT g_parentId'
					. ' FROM g2_ChildEntity'
					. ' WHERE `g_id` = \'' . $eid . '\''
					;
			$database->setQuery( $query );
			$pid = $database->loadResult();

			if ( $pid == $parentid ) {
				return null;
			}
		}

		// Fallback sanity check in case the user has renamed the albums
		$query = 'SELECT count(*)'
				. ' FROM g2_Item'
				. ' WHERE `g_ownerId` = \'' . $g2userid . '\''
				;
		$database->setQuery( $query );
		$entries = $database->loadResult();

		if ( $entries >= $this->settings['groups_sel_amt'] ) {
			return null;
		}

		// Create Entity
		$query = 'SELECT max(g_id)'
				. ' FROM g2_Entity'
				;
		$database->setQuery( $query );

		$entityid = $database->loadResult() + 1;

		$query = 'SELECT max(g_serialNumber)'
				. ' FROM g2_Entity'
				. ' WHERE `g_entityType` = \'GalleryAlbumItem\''
				;
		$database->setQuery( $query );

		$serial = $database->loadResult() + 1;

		$query = 'INSERT INTO g2_Entity'
				. ' ( `g_id`, `g_creationTimestamp`, `g_isLinkable`, `g_linkId`, `g_modificationTimestamp`, `g_serialNumber`, `g_entityType`, `g_onLoadHandlers` )'
				. ' VALUES ( \'' . $entityid . '\', \'' . time() . '\', \'0\', NULL, \'' . time() . '\', \'1\', \'GalleryAlbumItem\', NULL )'
				;
		$database->setQuery( $query );

		if ( !$database->query() ) {
			$this->setError( $database->getErrorMsg() );
			return false;
		}

		$query = 'INSERT INTO g2_Item'
				. ' ( `g_id`, `g_canContainChildren`, `g_description`, `g_keywords`, `g_ownerId`, `g_renderer`, `g_summary`, `g_title`, `g_viewedSinceTimestamp`, `g_originationTimestamp` )'
				. ' VALUES ( \'' . $entityid . '\', \'1\', \'\', NULL, \'' . $g2userid . '\', NULL, \'' . $albumname . '\', \'' . $albumname . '\', \'' . time() . '\', \'' . time() . '\' )'
				;
		$database->setQuery( $query );

		if ( !$database->query() ) {
			$this->setError( $database->getErrorMsg() );
			return false;
		}

		$query = 'INSERT INTO g2_AlbumItem'
				. ' ( `g_id`, `g_theme`, `g_orderBy`, `g_orderDirection` )'
				. ' VALUES ( \'' . $entityid . '\', \'\', \'\', \'asc\' )'
				;
		$database->setQuery( $query );

		if ( !$database->query() ) {
			$this->setError( $database->getErrorMsg() );
			return false;
		}

		$query = 'INSERT INTO g2_ChildEntity'
				. ' ( `g_id`, `g_parentId` )'
				. ' VALUES ( \'' . $entityid . '\', \'' . $parentid . '\' )'
				;
		$database->setQuery( $query );

		if ( !$database->query() ) {
			$this->setError( $database->getErrorMsg() );
			return false;
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
			return $this->createG2User( $metaUser );
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

		return $database->loadResult();
	}

	function createG2User( $metaUser )
	{
		global $database;

		$query = 'SELECT max(g_id)'
				. ' FROM g2_Entity'
				;
		$database->setQuery( $query );

		$userid = $database->loadResult() + 1;

		$query = 'SELECT max(g_serialNumber)'
				. ' FROM g2_Entity'
				. ' WHERE `g_entityType` = \'GalleryUser\''
				;
		$database->setQuery( $query );

		$serial = $database->loadResult() + 1;

		$query = 'INSERT INTO g2_Entity'
				. ' ( `g_id`, `g_creationTimestamp`, `g_isLinkable`, `g_linkId`, `g_modificationTimestamp`, `g_serialNumber`, `g_entityType`, `g_onLoadHandlers` )'
				. ' VALUES ( \'' . $userid . '\', \'' . time() . '\', \'0\', NULL, \'' . time() . '\', \'1\', \'GalleryUser\', NULL )'
				;
		$database->setQuery( $query );
		if ( !$database->query() ) {
			$this->setError( $database->getErrorMsg() );
			return false;
		}

		$query = 'SELECT max(g_id)'
				. ' FROM g2_User'
				;
		$database->setQuery( $query );

		$g2id = $database->loadResult() + 1;

		$query = 'INSERT INTO g2_User'
				. ' ( `g_id`, `g_userName`, `g_fullName`, `g_hashedPassword`, `g_email`, `g_language`, `g_locked` )'
				. ' VALUES ( \'' . $userid . '\', \'' . $metaUser->cmsUser->username . '\', \'' . $metaUser->cmsUser->name . '\', \'' . $metaUser->cmsUser->password . '\', \'' . $metaUser->cmsUser->email . '\', NULL, \'0\' )'
				;
		$database->setQuery( $query );
		if ( !$database->query() ) {
			$this->setError( $database->getErrorMsg() );
			return false;
		}

		$query = 'INSERT INTO g2_ExternalIdMap'
				. ' ( `g_externalId`, `g_entityType`, `g_entityId` )'
				. ' VALUES ( \'' . $metaUser->cmsUser->id . '\', \'GalleryUser\', \'' . $userid . '\' )'
				;
		$database->setQuery( $query );
		if ( !$database->query() ) {
			$this->setError( $database->getErrorMsg() );
			return false;
		}

		// Add to standard groups
		$this->mapUserToGroup( $userid, 2 );
		$this->mapUserToGroup( $userid, 4 );

		if ( $database->query() ) {
			return $userid;
		} else {
			$this->setError( $database->getErrorMsg() );
			return false;
		}
	}

}

?>