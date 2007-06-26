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

class mi_idevaffiliate {

	function Info () {
		$info = array();
		$info['name'] = "iDevAffiliate";
		$info['desc'] = "Connect your sales to iDevAffiliate";

		return $info;
	}

	function checkInstallation () {
		return true;
	}

	function install () {
		return true;
	}

	function Settings ( $params ) {
		$settings = array();
		$settings['mi_order_id'] = array("inputA", "Order ID", "The order number. <br />Automatic mappings:<br />'[invoice]' = invoice number<br />'[planid]' = plan id<br />'[userid]' = user id");
		$settings['mi_order_subtotal'] = array("inputB", "Order Subtotal", "The amount spent on this order. Type '[auto]' to assign the invoice amount.");
		return $settings;
	}

	function action($params, $userid, $plan) {
		global $database;

		$database->setQuery( "SELECT idev_vartotal, idev_order FROM #__idevaff_integration" );
		$idevconfig = $database->loadRow();
		$varname = $idevconfig[0];
		$idev_tracking = $idevconfig[1];

		if (strpos($params['mi_order_id'], '[invoice]')) {
			$query = "SELECT invoice_number FROM #__acctexp_invoices WHERE userid='" . $userid . "' ORDER BY transaction_date DESC";
			$database->setQuery( $query );
			$$idev_tracking = $database->loadResult();
		} elseif (strpos($params['mi_order_id'], '[planid]')) {
			$metaUser = new metaUser($userid);
			$$idev_tracking = $metaUser->objSubscription->plan;
		} elseif (strpos($params['mi_order_id'], '[userid]')) {
			$metaUser = new metaUser($userid);
			$$idev_tracking = $metaUser->cmsUser->id;
		} else {
			$$idev_tracking = $params['mi_order_id'];
		}

		if (strpos($params['mi_order_subtotal'], '[invoice]')) {
			$query = "SELECT amount FROM #__acctexp_invoices WHERE userid='" . $userid . "' ORDER BY transaction_date DESC";
			$database->setQuery( $query );
			$$varname = $database->loadResult();
		} else {
			$$varname = number_format($params['mi_order_subtotal'],2,".","");
		}

		include ($GLOBALS['mosConfig_absolute_path'] . '/components/com_idevaffiliate/sale.php');
		$fr = fopen($GLOBALS['mosConfig_absolute_path'] . '/jstmp.txt', 'a+');
		fputs($fr, 'an access has been made\n');
		foreach($params as $key=>$value) {
			fputs($fr, 'key is '.$key.' and value = '.$value.'\n');
		}
		fclose($fr);

		return true;
	}

}

?>
