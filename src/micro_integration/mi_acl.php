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
		$settings['sender']				= array( 'inputE' );
		$settings['sender_name']		= array( 'inputE' );

		$settings['recipient']			= array( 'inputE' );


		$settings['set_gid']			= array( 'list_yesno' );
		$settings['gid']				= array( 'list' );
		$settings['set_gid_exp']		= array( 'list_yesno' );
		$settings['gid_exp']			= array( 'list' );
		$settings['set_gid_pre_exp']	= array( 'list_yesno' );
		$settings['gid_pre_exp']		= array( 'list' );


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

		return $settings;
	}

	function pre_expiration_action( $params, $metaUser, $plan )
	{
		if ( $params['set_gid_pre_exp'] ) {
			$this->instantGIDchange( $metaUser, $params['gid_pre_exp'] );
		}
		return true;
	}

	function expiration_action( $params, $metaUser, $plan )
	{
		if ( $params['set_gid_pre'] ) {
			$this->instantGIDchange( $metaUser, $params['gid_exp'] );
		}
		return true;
	}

	function action( $params, $metaUser, $invoice, $plan )
	{
		if ( $params['set_gid'] ) {
			$this->instantGIDchange( $metaUser, $params['gid'] );
		}
		return true;
	}

	function instantGIDchange( $metaUser, $gid )
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
		. ' SET group_id = \'' . (int) $gid . '\''
		. ' WHERE aro_id = \'' . $aro_id . '\''
		;
		$database->setQuery( $query );
		$database->query() or die( $database->stderr() );

		// Moxie Mod - updated to add usertype to users table and update session table for immediate access to usertype features
		$gid_name = $acl->get_group_name( $gid, 'ARO' );

		$query = 'UPDATE #__users'
		. ' SET gid = \'' .  (int) $gid . '\', usertype = \'' . $gid_name . '\''
		. ' WHERE id = \''  . (int) $metaUser->userid . '\''
		;
		$database->setQuery( $query );
		$database->query() or die( $database->stderr() );

		$query = 'UPDATE #__session'
		. ' SET usertype = \'' . $gid_name . '\''
		. ' WHERE userid = \'' . (int) $metaUser->userid . '\''
		;
		$database->setQuery( $query );
		$database->query() or die( $database->stderr() );

		return true;
	}
}
?>