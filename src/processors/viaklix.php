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

class processor_viaklix {

	function processor_viaklix () {
		global $mosConfig_absolute_path;

		if (file_exists( $mosConfig_absolute_path . '/components/com_acctexp/processors/com_acctexp_language_processors/'.$GLOBALS['mosConfig_lang'].'.php' )) {
				include_once( $mosConfig_absolute_path . '/components/com_acctexp/processors/com_acctexp_language_processors/'.$GLOBALS['mosConfig_lang'].'.php' );
		} else {
				include_once( $mosConfig_absolute_path . '/components/com_acctexp/processors/com_acctexp_language_processors/english.php' );
		}
	}

	function info () {
		$info = array();
		$info['name'] = "viaklix";
		$info['longname'] = "Viaklix";
		$info['statement'] = "Make payments with Viaklix!";
		$info['description'] = _DESCRIPTION_VIAKLIX;
		$info['cc_list'] = "visa,mastercard,discover,americanexpress,echeck,giropay";
		$info['recurring'] = 0;

		return $info;
	}

	function settings () {
		$settings = array();
		$settings['accountid'] = "your account id";
		$settings['userid'] = "your user id";
		$settings['pin'] = "your pin";
		$settings['testmode'] = 0;
		$settings['item_name']		= sprintf( _AEC_MI_SET1_INAME, '[[cms_live_site]]',
									'[[user_name]]', '[[user_username]]' );
		$settings['rewriteInfo']	= ''; // added mic


		return $settings;
	}

	function backend_settings () {
		$settings = array();
		$settings['testmode'] = array("list_yesno");
		$settings['accountid'] = array("inputC");
		$settings['userid'] = array("inputC");
		$settings['pin'] = array("inputC");
		$settings['item_name'] = array("inputE");
 		$rewriteswitches = array("cms", "user", "expiration", "subscription", "plan");
        $settings['rewriteInfo'] = array("fieldset", "Rewriting Info", AECToolbox::rewriteEngineInfo($rewriteswitches));

		return $settings;
	}

	function createGatewayLink ( $int_var, $cfg, $metaUser, $new_subscription ) {
		global $mosConfig_live_site;

		$var['post_url']	= "https://www.viaKLIX.com/process.asp";
		$var['ssl_test_mode'] = $cfg['testmode'] ? "true" : "false";

		$var['ssl_merchant_id']			= $cfg['accountid'];
		$var['ssl_user_id']				= $cfg['userid'];
		$var['ssl_pin']					= $cfg['pin'];
		$var['ssl_invoice_number']		= $int_var['invoice'];
		$var['ssl_salestax']			= "0";
		$var['ssl_result_format']		= "HTML";
		$var['ssl_receipt_link_method']	= "POST";
		$var['ssl_receipt_link_url']	= AECToolbox::deadsureURL("/index.php?option=com_acctexp&amp;task=viaklixnotification");
		$var['ssl_receipt_link_text']	= "Continue";
		$var['ssl_amount'] = $int_var['amount'];
		$var['currency_code']			= $cfg['currency_code'];

		$var['item_number']		= $row->id;
		$var['item_name']		= AECToolbox::rewriteEngine($cfg['item_name'], $metaUser, $new_subscription);
		$var['custom']			= $int_var['usage'];

		return $var;
	}

	function parseNotification ( $post, $cfg ) {
		$ssl_result				= $_POST['ssl_result'];
		$ssl_result_message		= $_POST['ssl_result_message'];
		$ssl_txn_id				= $_POST['ssl_txn_id'];
		$ssl_approval_code		= $_POST['ssl_approval_code'];
		$ssl_cvv2_response		= $_POST['ssl_cvv2_response'];
		$ssl_avs_response		= $_POST['ssl_avs_response'];
		$ssl_transaction_type	= $_POST['ssl_transaction_type'];

		$ssl_amount				= $_POST['ssl_amount'];
		$ssl_email				= $_POST['ssl_email'];
		$ssl_description		= $_POST['ssl_description'];
		$userid					= $_POST['ssl_customer_code'];

		$response = array();
		$response['invoice'] = $_POST['ssl_invoice_number'];
		$response['valid'] = ( $ssl_result == 0 ) && ( strcmp ( $ssl_result_message, "APPROVED") == 0 );

		return $response;
	}

}
?>