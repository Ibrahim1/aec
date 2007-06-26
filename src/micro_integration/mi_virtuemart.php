<?php
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

class mi_virtuemart {

	function Info () {
		$info = array();
		$info['name'] = "VirtueMart";
		$info['desc'] = "Choose the VM usergroup this user should be applied to";

		return $info;
	}

	function Settings ( $params ) {
		global $database;

	 	$database->setQuery( "SELECT shopper_group_id, shopper_group_name FROM #__vm_shopper_group");
	 	$shopper_groups = $database->loadObjectList();

		$sg = array();
		foreach ($shopper_groups as $group) {
			$sg[] = mosHTML::makeOption( $group->shopper_group_id, $group->shopper_group_name );
		}

		$settings = array();
		$settings['lists']['shopper_group'] = mosHTML::selectList($sg, 'shopper_group', 'size="4"', 'value', 'text', $params['shopper_group']);
		$settings['lists']['shopper_group_exp'] = mosHTML::selectList($sg, 'shopper_group_exp', 'size="4"', 'value', 'text', $params['shopper_group_exp']);

		$settings['set_shopper_group'] = array("list_yesno", "Set Shopper group", "Choose Yes if you want this MI to set the Shopper Group when it is called.");
		$settings['shopper_group'] = array("list", "Shopper group", "The VirtueMart Shopper group that you want the user to be in.");
		$settings['set_shopper_group_exp'] = array("list_yesno", "Set group Expiration", "Choose Yes if you want this MI to set the Shopper Group when the calling payment plan expires.");
		$settings['shopper_group_exp'] = array("list", "Expiration Shopper group", "The VirtueMart Shopper group that you want the user to be in when the subscription runs out.");

		return $settings;
	}

	function expiration_action($params, $userid, $plan) {
		global $database;

		if ($params['set_shopper_group_exp']) {
			// Check vendor relationship
			$query = "SELECT vendor_id #__vm_auth_user_vendor WHERE user_id='" . $userid . "'";
			$database->setQuery($query);
			$vendor = $database->loadResult();
	
			// Insert Shopper -ShopperGroup - Relationship
			$query  = "UPDATE #__vm_shopper_vendor_xref SET shopper_group_id = '" . $params['shopper_group_exp'] . "' WHERE user_id='" . $userid . "'";
			$database->setQuery($query);
			$database->query();

			return true;
		} else {
			return false;
		}
	}

	function action($params, $userid, $plan) {
		global $database;

		if ($params['set_shopper_group']) {
			// Check vendor relationship
			$query = "SELECT vendor_id #__vm_auth_user_vendor WHERE user_id='" . $userid . "'";
			$database->setQuery($query);
			$vendor = $database->loadResult();
	
			// Insert Shopper -ShopperGroup - Relationship
			$query  = "UPDATE #__vm_shopper_vendor_xref SET shopper_group_id = '" . $params['shopper_group'] . "' WHERE user_id='" . $userid . "'";
			$database->setQuery($query);
			$database->query();

			return true;
		} else {
			return false;
		}
	}

}

?>