<?php
// Copyright (C) 2007 Calum Polwart / Shiny Black Shoe Systems
// Adapted from code from David Deutsch used for idevAffiliate and code from
// AffiliatePro Website
// This code can be referred to as (c) David Deutsch if supplied as part of AEC in future
//
// All rights reserved.
// This source file is to function as part of the Account Expiration Control Component, a 
// Joomla custom Component By Helder Garcia and David Deutsch - http://www.globalnerd.org
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

class mi_affiliatepro {

	function Info () {
		$info = array();
		$info['name'] = "AffiliatePRO";
		$info['desc'] = "Connect your AEC sales to AffiliatePRO";

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
		$settings['mi_additional_info'] = array("inputC", "Additional Info", $params['mi_additional_info'], "Any additional info to be passed to AffiliatePRO.");
		$settings['mi_affPRO_url'] = array("inputB", "Affiliate PRO URL", $params['mi_affPRO_url'], "Enter the AffiliatePRO Url (without the http://) that points to your your AffiliatePRO installation.");
		$settings['mi_affPRO_group_id'] = array("inputC", "Affiliate PRO Group ID for commission", $params['mi_affPRO_group_id'], "Enter the Affiliate PRO product group identity to be used to calculate commission.");

		return $settings;
	}

	function action($params, $userid, $plan) {
		global $database;

		$passvars = array();

		$query = "SELECT invoice_number FROM #__acctexp_invoices WHERE userid='" . $userid . "' AND active='1' ORDER BY transaction_date DESC LIMIT 1";
		$database->setQuery( $query );
		$passvars['txn_id'] = $database->loadResult();

		$passvars['aff_id'] = ""; //REPLACE with Affiliate Id - how will AEC know this?

		$passvars['group_id'] = $params['mi_affPRO_group_id'];

		//Get the transaction amount from AEC
		$query = "SELECT amount FROM #__acctexp_invoices WHERE invoice_number='" . $passvars['txn_id'] . "'";
		$database->setQuery( $query );
		$passvars['amount'] = $database->loadResult();

		//Get the currency from AEC - NOTE this MAY NOT WORK - does AEC use ISO currency codes??
		$query = "SELECT currency FROM #__acctexp_invoices WHERE invoice_number='" . $passvars['txn_id'] . "'";
		$database->setQuery( $query );
		$passvars['cur'] = $database->loadResult();

		$passvars['country_code'] = "";// REPLACE with Country Code? Where could that come from?

		$passvars['add_info'] = "userid_".$userid."_plan_".$plan->id."_".$params['mi_additional_info'];

		$url = "http://".$params['mi_affPRO_url']."/callbacks/callback_sample.php?";

		$vars_encode = array();
		foreach ($passvars as $key => $value) {
			$vars_encode[] = $key . "=" . $value;
		}

		$url .= implode("&", $vars_encode);

		$request=fopen($url, "r");
		fclose($request);

		return true;
	}
}

?>