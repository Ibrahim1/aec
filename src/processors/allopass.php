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
// ------------------------------------
// "AlloPass" feature contributed by:
// educ
// Jul 2006
// Thanks!
// ------------------------------------

// Dont allow direct linking
defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );

class processor_allopass extends POSTprocessor
{
	function info()
	{
		$info = array();
		$info['name'] = "allopass";
		$info['longname'] = "Allopass";
		$info['statement'] = "Make payments with Allopass!";
		$info['description'] = _DESCRIPTION_ALLOPASS;
		$info['cc_list'] = "visa,mastercard";
		$info['recurring'] = 0;
		$info['notify_trail_thanks'] = 1;

		return $info;
	}

	function settings()
	{
		$settings = array();
		$settings['siteid'] = "siteid";
		$settings['docid'] = "docid";
		$settings['auth'] = "auth";
		$settings['testmode'] = 0;
		$settings['item_name']		= sprintf( _CFG_PROCESSOR_ITEM_NAME_DEFAULT, '[[cms_live_site]]', '[[user_name]]', '[[user_username]]' );

		return $settings;
	}

	function backend_settings( $cfg )
	{
		$settings = array();
		$settings['testmode'] = array("list_yesno");
		$settings['siteid'] = array("inputC");
		$settings['docid'] = array("inputC");
		$settings['auth'] = array("inputC");
		$settings['item_name'] = array("inputE");
 		$rewriteswitches = array("cms", "user", "expiration", "subscription", "plan");
		$settings = AECToolbox::rewriteEngineInfo( $rewriteswitches, $settings );

		return $settings;
	}

	function Params( $cfg, $params )
	{
		$var['params']['CODE0'] = array("inputC", "Allopass Code", "Please [FIXME]");
	}

	function createGatewayLink( $int_var, $cfg, $metaUser, $new_subscription )
	{
		global $mosConfig_live_site;

		$var['post_url']       = AECToolbox::deadsureURL("/index.php?option=com_acctexp&amp;task=allopassnotification");
		$var['ssl_test_mode'] = $cfg['testmode'] ? "true" : "false";

		$var['params_array']['CODE0']	= array("inputA", "CODE0", "Code", $int_var['params']['CODE0']);

		$var['CODE0']					= $int_var['params']['CODE0'];
		$var['SITE_ID']					= $cfg['siteid'];
		$var['DOC_ID']					= $cfg['docid'];
		$var['AUTH']					= $cfg['auth'];
		$var['RECALL']					= "1" ;

		$var['currency_code']			= $cfg['currency_code'];
		$var['ssl_merchant_id']			= $var['SITE_ID'];
		$var['ssl_user_id']				= $var['DOC_ID'];
		$var['ssl_pin']					= $var['AUTH'];
		$var['ssl_invoice_number']		= $int_var['invoice'];
		$var['ssl_salestax']			= "0";
		$var['ssl_result_format']		= "HTML";
		$var['ssl_receipt_link_method']	= "POST";
		$var['ssl_receipt_link_url']	= AECToolbox::deadsureURL("/index.php?option=com_acctexp&amp;task=allopassnotification");
		$var['ssl_receipt_link_text']	= "Continue";
		$var['ssl_amount'] = $int_var['amount'];

		$var['ssl_customer_code']	= $metaUser->cmsUser->id;
		$var['ssl_description']		= AECToolbox::rewriteEngine($cfg['item_name'], $metaUser, $new_subscription);

		return $var;
	}

	function parseNotification( $post, $cfg )
	{

   		$ssl_amount = mosGetParam( $_REQUEST, 'ssl_amount', '' ) ;

		$response = array();
		$response['invoice'] = $post['ssl_invoice_number'];

		return $response;
	}

	function validateNotification( $response, $post, $cfg, $invoice )
	{
		if (trim($post['RECALL'])=="") {
			$response['valid'] = false;
		} else {
			$r=@file("http://www.allopass.com/check/vf.php4?CODE=" . $post['CODE0'] . "&AUTH=" . $cfg['auth'] )  ;

			$test_ap = substr($r[0],0,2);
			if ( $test_ap != "OK") {
				$response['valid'] = false;
			} elseif ($test_ap = "OK") {
				$response['valid'] = true;
				return;
			} else {
				$response['valid'] = false;
			}
		}

		return $response;
	}

}
?>