<?php
/**
 * @version $Id: mi_acl.php
 * @package AEC - Account Control Expiration - Subscription component for Joomla! OS CMS
 * @subpackage Micro Integrations - ACL
 * @copyright 2006/2007 Copyright (C) David Deutsch
 * @author David Deutsch <skore@skore.de> & Team AEC - http://www.globalnerd.org
 * @license GNU/GPL v.2 http://www.gnu.org/copyleft/gpl.html
 */

// Copyright (C) 2006-2007 David Deutsch
// All rights reserved.
// This source file is part of the Account Expiration Control Component, a  Joomla
// custom Component By Helder Garcia and David Deutsch - http://www.globalnerd.org
//
// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License (GPL)
// as published by the Free Software Foundation; either version 2
// of the License, or (at your option) any later version.
//
// Please note that the GPL states that any headers in files and
// Copyright notices as well as credits in headers, source files
// and output (screens, prints, etc.) can not be removed.
// You can extend them with your own credits, though...
//
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with this program; if not, write to the Free Software
// Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
//
// The "GNU General Public License" (GPL) is available at
// http://www.gnu.org/copyleft/gpl.html.

// Dont allow direct linking
defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );

class mi_acl
{
	function Info()
	{
		$info = array();
		$info['name'] = _AEC_MI_NAME_ACL;
		$info['desc'] = _AEC_MI_DESC_ACL;

		return $info;
	}

	function Settings( $params )
	{
		global $my, $acl;

		$settings = array();
		$settings['sender']			= array( 'inputE' );
		$settings['sender_name']		= array( 'inputE' );

		$settings['recipient']			= array( 'inputE' );

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
		$settings['sub_gid']				= array( 'list' );
		$settings['sub_set_gid_exp']		= array( 'list_yesno' );
		$settings['sub_gid_exp']			= array( 'list' );
		$settings['sub_set_gid_pre_exp']	= array( 'list_yesno' );
		$settings['sub_gid_pre_exp']		= array( 'list' );

		// ensure user can't add group higher than themselves
		$my_groups = $acl->get_object_groups( 'users', $my->id, 'ARO' );
		if ( is_array( $my_groups ) && count( $my_groups ) > 0) {
			$ex_groups = $acl->get_group_children( $my_groups[0], 'ARO', 'RECURSE' );
		} else {
			$ex_groups = array();
		}

		$gtree = $acl->get_group_children_tree( null, 'USERS', false );

		// mic: exclude public front- & backend
		$ex_groups[] = 29;
		$ex_groups[] = 30;

		// remove users 'above' me
		$i = 0;
		while ( $i < count( $gtree ) ) {
			if ( in_array( $gtree[$i]->value, $ex_groups ) ) {
				array_splice( $gtree, $i, 1 );
			} else {
				$i++;
			}
		}

		$settings['lists']['gid'] 			= mosHTML::selectList( $gtree, 'gid', 'size="6"', 'value', 'text', ( empty( $params['gid'] ) ? 18 : $params['gid'] ) );
		$settings['lists']['gid_exp'] 		= mosHTML::selectList( $gtree, 'gid_exp', 'size="6"', 'value', 'text', ( empty( $params['gid_exp'] ) ? 18 : $params['gid_exp'] ) );
		$settings['lists']['gid_pre_exp'] 	= mosHTML::selectList( $gtree, 'gid_pre_exp', 'size="6"', 'value', 'text', ( empty( $params['gid_pre_exp'] ) ? 18 : $params['gid_pre_exp'] ) );

		$subgroups = array( 'sub_gid_del', 'sub_gid', 'sub_gid_exp_del', 'sub_gid_exp', 'sub_gid_pre_exp_del', 'sub_gid_pre_exp' );

		foreach ( $subgroups as $groupname ) {
			$list = explode( ';', $params[$groupname] );

			$selected = array();
			foreach ( $list as $value ) {
				$selected[]->value = $value;
			}

			$params[$groupname] = $selected;
		}

		$settings['lists']['sub_gid_del'] 			= mosHTML::selectList( $gtree, 'sub_gid_del', 'size="6" multiple="multiple"', 'value', 'text', $params['sub_gid_del'] );
		$settings['lists']['sub_gid'] 				= mosHTML::selectList( $gtree, 'sub_gid', 'size="6" multiple="multiple"', 'value', 'text',  $params['sub_gid'] );
		$settings['lists']['sub_gid_exp_del'] 		= mosHTML::selectList( $gtree, 'sub_gid_exp_del', 'size="6" multiple="multiple"', 'value', 'text', $params['sub_gid_exp_del'] );
		$settings['lists']['sub_gid_exp'] 			= mosHTML::selectList( $gtree, 'sub_gid_exp', 'size="6" multiple="multiple"', 'value', 'text', $params['sub_gid_exp'] );
		$settings['lists']['sub_gid_pre_exp_del'] 	= mosHTML::selectList( $gtree, 'sub_gid_pre_exp_del', 'size="6" multiple="multiple"', 'value', 'text', $params['sub_gid_pre_exp_del'] );
		$settings['lists']['sub_gid_pre_exp'] 		= mosHTML::selectList( $gtree, 'sub_gid_pre_exp', 'size="6" multiple="multiple"', 'value', 'text', $params['sub_gid_pre_exp'] );

		return $settings;
	}

	function saveparams( $params )
	{
		$subgroups = array( 'sub_gid_del', 'sub_gid', 'sub_gid_exp_del', 'sub_gid_exp', 'sub_gid_pre_exp_del', 'sub_gid_pre_exp' );

		foreach ( $subgroups as $groupname ) {
			$params[$groupname] = implode( ';', $params[$groupname] );
		}

		return $params;
	}

	function pre_expiration_action( $params, $metaUser, $plan )
	{
		if ( $params['set_gid_pre_exp'] ) {
			$this->instantGIDchange( $metaUser, $params, 'gid_pre_exp' );
		}

		if ( $params['sub_set_gid_pre_exp'] ) {
			$this->jaclplusGIDchange( $metaUser, $params, 'sub_gid_pre_exp' );
		}

		return true;
	}

	function expiration_action( $params, $metaUser, $plan )
	{
		if ( $params['set_gid_pre'] ) {
			$this->instantGIDchange( $metaUser, $params, 'gid_exp' );
		}

		if ( $params['sub_set_gid_exp'] ) {
			$this->jaclplusGIDchange( $metaUser, $params, 'sub_gid_exp' );
		}

		return true;
	}

	function action( $params, $metaUser, $invoice, $plan )
	{
		if ( $params['set_gid'] ) {
			$this->instantGIDchange( $metaUser, $params, 'gid' );
		}

		if ( $params['sub_set_gid'] ) {
			$this->jaclplusGIDchange( $metaUser, $params, 'sub_gid' );
		}

		return true;
	}

	function instantGIDchange( $metaUser, $params, $section )
	{
		global $database, $acl;

		// Always protect last administrator
		if ( $metaUser->cmsUser->gid >= 24 ) {
			$query = 'SELECT count(*)'
			. ' FROM #__core_acl_groups_aro_map'
			. ' WHERE group_id = \'25\''
			;
			$database->setQuery( $query );
			if ( $database->loadResult() <= 1) {
				return false;
			}
		}

		// Get ARO ID for user
		$query = 'SELECT aro_id'
		. ' FROM #__core_acl_aro'
		. ' WHERE value = \'' . (int) $metaUser->userid . '\''
		;
		$database->setQuery( $query );
		$aro_id = $database->loadResult();

		// Carry out ARO ID -> ACL group mapping
		$query = 'UPDATE #__core_acl_groups_aro_map'
		. ' SET group_id = \'' . (int) $params[$section] . '\''
		. ' WHERE aro_id = \'' . $aro_id . '\''
		;
		$database->setQuery( $query );
		$database->query() or die( $database->stderr() );

		// Moxie Mod - updated to add usertype to users table and update session table for immediate access to usertype features
		$gid_name = $acl->get_group_name( $params[$section], 'ARO' );

		$query = 'UPDATE #__users'
		. ' SET gid = \'' .  (int) $params[$section] . '\', usertype = \'' . $gid_name . '\''
		. ' WHERE id = \''  . (int) $metaUser->userid . '\''
		;
		$database->setQuery( $query );
		$database->query() or die( $database->stderr() );

		if ( $params['change_session'] ) {
			$query = 'UPDATE #__session'
			. ' SET usertype = \'' . $gid_name . '\''
			. ' WHERE userid = \'' . (int) $metaUser->userid . '\''
			;
			$database->setQuery( $query );
			$database->query() or die( $database->stderr() );
		}

		if ( $params['jaclpluspro'] ) {
			// Check for main entry
			$query = 'SELECT group_id'
			. ' FROM #__jaclplus_user_group'
			. ' WHERE id = \'' . (int) $metaUser->userid . '\''
			. ' AND group_type = \'main\''
			;
			$database->setQuery( $query );

			if ( !empty( $database->loadResult() ) ) {
				$query = 'UPDATE #__jaclplus_user_group'
				. ' SET group_id = \'' . (int)$params[$section] . '\''
				. ' WHERE id = \'' . (int) $metaUser->userid . '\''
				. ' AND group_type = \'main\''
				;
				$database->setQuery( $query );
				$database->query() or die( $database->stderr() );
			} else {
				$query = 'INSERT INTO #__jaclplus_user_group'
				. ' VALUES( \'' . (int) $metaUser->userid . '\', \'main\', \'' . (int) $params[$section] . '\' )'
				;
				$database->setQuery( $query );
				$database->query() or die( $database->stderr() );
			}
		}

		return true;
	}

	function instantGIDchange( $metaUser, $params, $section )
	{
		global $database, $acl;

		if ( $params['delete_subgroups'] ) {
			// Delete sub entries
			$query = 'DELETE FROM #__jaclplus_user_group'
			. ' WHERE id = \'' . (int) $metaUser->userid . '\''
			. ' AND group_type = \'sub\''
			;
			$database->setQuery( $query );
			$database->query();

			$groups = array();
		} else {
			// Check for sub entries
			$query = 'SELECT group_id'
			. ' FROM #__jaclplus_user_group'
			. ' WHERE id = \'' . (int) $metaUser->userid . '\''
			. ' AND group_type = \'sub\''
			;
			$database->setQuery( $query );
			$groups = $database->loadResultArray();
		}

		if ( !empty( $params[$section.'_del'] ) ) {
			foreach ( $params[$section.'_del'] as $gid ) {
				if ( in_array( $gid, $groups ) ) {
					$query = 'DELETE FROM #__jaclplus_user_group'
					. ' WHERE id = \'' . (int) $metaUser->userid . '\''
					. ' AND group_type = \'sub\''
					. ' AND group_id = \'' . (int) $gid . '\''
					;
					$database->setQuery( $query );
					$database->query() or die( $database->stderr() );
				}
			}
		}

		if ( !empty( $params[$section] ) ) {
			foreach ( $params[$section] as $gid ) {
				if ( !in_array( $gid, $groups ) ) {
					$query = 'INSERT INTO #__jaclplus_user_group'
					. ' VALUES( \'' . (int) $metaUser->userid . '\', \'sub\', \'' . $gid . '\' )'
					;
					$database->setQuery( $query );
					$database->query() or die( $database->stderr() );
				}
			}
		}

		if ( !empty( $database->loadResult() ) ) {
			$query = 'UPDATE #__jaclplus_user_group'
			. ' SET group_id = \'' . (int)$params[$section] . '\''
			. ' WHERE userid = \'' . (int) $metaUser->userid . '\''
			. ' AND group_type = \'main\''
			;
			$database->setQuery( $query );
			$database->query() or die( $database->stderr() );
		} else {
			$query = 'INSERT INTO #__jaclplus_user_group'
			. ' VALUES( \'' . (int) $metaUser->userid . '\', \'main\', \'' . (int) $params[$section] . '\' )'
			;
			$database->setQuery( $query );
			$database->query() or die( $database->stderr() );
		}

		return true;
	}

}
?>