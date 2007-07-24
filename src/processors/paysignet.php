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

class processor_paysignet
{
	function processor_paysignet()
	{
		global $mosConfig_absolute_path;

		if ( file_exists( $mosConfig_absolute_path . '/components/com_acctexp/processors/com_acctexp_language_processors/'.$GLOBALS['mosConfig_lang'].'.php' ) ) {
				include_once( $mosConfig_absolute_path . '/components/com_acctexp/processors/com_acctexp_language_processors/'.$GLOBALS['mosConfig_lang'].'.php' );
		} else {
				include_once( $mosConfig_absolute_path . '/components/com_acctexp/processors/com_acctexp_language_processors/english.php' );
		}
	}

	function info()
	{
		$info = array();
		$info['name'] = "paysignet";
		$info['longname'] = "Paysignet";
		$info['statement'] = "Make payments with Paysignet!";
		$info['description'] = _DESCRIPTION_PAYSIGNET;
		$info['cc_list'] = "visa,mastercard,discover,americanexpress,echeck";
		$info['recurring'] = 0;

		return $info;
	}

	function settings()
	{
		$settings = array();
		$settings['merchant'] = "merchant";
		$settings['testmode'] = 0;

		return $settings;
	}

	function backend_settings()
	{
		$settings = array();
		$settings['testmode'] = array("list_yesno");
		$settings['merchant'] = array("inputC");

		return $settings;
	}

	function createGatewayLink( $int_var, $cfg, $metaUser, $new_subscription )
	{
		global $mosConfig_live_site;

		$var['post_url']	= "https://www.paysignet.com/validate/paysign_getdetails.asp";

		$var['epq_MMerchantOId']	= $int_var['invoice'];
		$var['epq_AAmountA1']		= $int_var['amount'];
		$var['epq_MMerchantB2']		= $cfg['merchant'];

		$var['epqb_NNameA1']		= $metaUser->cmsUser->name;

		return $var;
	}

	function parseNotification( $post, $cfg )
	{

		$order_id		= $post['order_id'];
		$bank_name		= $post['bank_name'];
		$trans_status	= $post['trans_status'];
		$success		= $post['success'];

		$response = array();
		$response['invoice'] = $post['order_id'];
		$response['valid'] = ($success == '1');

		return $response;
	}

}
?>