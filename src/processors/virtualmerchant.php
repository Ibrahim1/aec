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

class processor_virtualmerchant extends POSTprocessor
{
	function info()
	{
		$info = array();
		$info['name']					= "virtualmerchant";
		$info['longname']				= "VirtualMerchant";
		$info['statement']				= "Make payments with VirtualMerchant!";
		$info['description']			= _DESCRIPTION_VIRTUALMERCHANT;
		$info['cc_list']				= "visa,mastercard,discover,americanexpress,echeck,giropay";
		$info['recurring']				= 0;
		$info['notify_trail_thanks']	= 1;

		return $info;
	}

	function settings()
	{
		$settings = array();

		$settings['accountid']	= "your account id";
		$settings['userid']		= "your user id";
		$settings['pin']		= "your pin";
		$settings['testmode']	= 0;
		$settings['item_name']	= sprintf( _CFG_PROCESSOR_ITEM_NAME_DEFAULT, '[[cms_live_site]]', '[[user_name]]', '[[user_username]]' );

		return $settings;
	}

	function backend_settings()
	{
		$settings = array();

		$settings['testmode']	= array( "list_yesno" );
		$settings['accountid']	= array( "inputC" );
		$settings['userid']		= array( "inputC" );
		$settings['pin']		= array( "inputC" );
		$settings['item_name']	= array( "inputE" );

		$settings = AECToolbox::rewriteEngineInfo( null, $settings );

		return $settings;
	}

	function createGatewayLink( $request )
	{
		global $mosConfig_live_site;

		$var['post_url']				= "https://www.myvirtualmerchant.com/VirtualMerchant/process.do";
		$var['ssl_test_mode']			= $this->settings['testmode'] ? "true" : "false";
        $var['ssl_transaction_type']	= "ccsale";
		$var['ssl_merchant_id']			= $this->settings['accountid'];
		$var['ssl_user_id']				= $this->settings['userid'];
		$var['ssl_pin']					= $this->settings['pin'];
		$var['ssl_invoice_number']		= $request->int_var['invoice'];
        $var['ssl_customer_code']		= $request->metaUser->cmsUser->username;
		$var['ssl_salestax']			= "0";
		$var['ssl_result_format']		= "HTML";
		$var['ssl_receipt_link_method']	= "POST";
		$var['ssl_receipt_link_url']	= AECToolbox::deadsureURL("/index.php?option=com_acctexp&amp;task=virtualmerchantnotification");
		$var['ssl_receipt_link_text']	= "Continue";
		$var['ssl_amount']				= $request->int_var['amount'];
		$var['currency_code']			= $this->settings['currency_code'];

		$var['item_number']				= $row->id;
		$var['item_name']				= AECToolbox::rewriteEngine( $this->settings['item_name'], $request->metaUser, $request->new_subscription, $request->invoice );
		$var['custom']					= $request->int_var['usage'];

		return $var;
	}

	function parseNotification( $post )
	{
		$ssl_result				= $post['ssl_result'];
		$ssl_result_message		= $post['ssl_result_message'];
		$ssl_txn_id				= $post['ssl_txn_id'];
		$ssl_approval_code		= $post['ssl_approval_code'];
		$ssl_cvv2_response		= $post['ssl_cvv2_response'];
		$ssl_avs_response		= $post['ssl_avs_response'];
		$ssl_transaction_type	= $post['ssl_transaction_type'];

		$ssl_amount				= $post['ssl_amount'];
		$ssl_email				= $post['ssl_email'];
		$ssl_description		= $post['ssl_description'];
		$ssl_customer_code		= $post['ssl_customer_code'];

		$response = array();
		$response['invoice']	= $post['ssl_invoice_number'];

		return $response;
	}

	function validateNotification( $response, $post, $invoice )
	{
		$response['valid'] = ( $post['ssl_result'] == 0 ) && ( strcmp ( $post['ssl_result_message'], "APPROVED") == 0 );

		return $response;
	}

}
?>