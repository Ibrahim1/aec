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

class processor_worldpay {

	function processor_worldpay () {
		global $mosConfig_absolute_path;

		if (file_exists( $mosConfig_absolute_path . '/components/com_acctexp/processors/com_acctexp_language_processors/'.$GLOBALS['mosConfig_lang'].'.php' )) {
				include_once( $mosConfig_absolute_path . '/components/com_acctexp/processors/com_acctexp_language_processors/'.$GLOBALS['mosConfig_lang'].'.php' );
		} else {
				include_once( $mosConfig_absolute_path . '/components/com_acctexp/processors/com_acctexp_language_processors/english.php' );
		}
	}

	function info () {
		$info = array();
		$info['name'] = "worldpay";
		$info['longname'] = "Worldpay";
		$info['statement'] = "Make payments with Worldpay!";
		$info['description'] = _DESCRIPTION_PAYPAL;
		$info['currencies'] = 'AFA,ALL,DZD,AON,ARS,AWG,AUD,EUR,BSD,BHD,BDT,BBD,BZD,BMD,BOB,BAD,BWP,BRL,BND,BGL,XOF,BIF,KHR,XAF,CAD,CVE,KYD,CLP,CNY,COP,KMF,CRC,HRK,CUP,CYP,CZK,DKK,DJF,XCD,DOP,'.
								'TPE,ECS,EGP,SVC,EEK,ETB,FKP,FJD,XPF,GMD,GHC,GIP,GTQ,GNF,GWP,GYD,HTG,HNL,HKD,HUF,ISK,INR,IDR,IRR,IQD,ILS,JMD,JPY,JOD,KZT,KES,KRW,KPW,KWD,KGS,LAK,LVL,LBP,LSL,LRD,'.
								'LYD,LTL,MOP,MKD,MGF,MWK,MYR,MVR,MTL,MRO,MUR,MXN,MNT,MAD,MZM,MMK,NAD,NPR,ANG,NZD,NIO,NGN,NOK,OMR,PKR,PAB,PGK,PYG,PEN,PHP,PLN,QAR,ROL,RUB,RWF,WST,STD,SAR,SCR,SLL,'.
								'SGD,SKK,SIT,SBD,SOS,ZAR,LKR,SHP,SDP,SRG,SZL,SEK,CHF,SYP,TWD,TJR,TZS,THB,TOP,TTD,TND,TRY,UGX,UAH,AED,GBP,USD,UYU,VUV,VEB,VND,YER,YUM,ZRN,ZMK,ZWD';
		$info['cc_list'] = "visa,mastercard,discover,americanexpress,echeck,giropay";
		$info['recurring'] = 0;

		return $info;
	}

	function settings () {
		$settings = array();
		$settings['instId'] = "your instId";
		$settings['testmode'] = 0;
		$settings['currency'] = "USD";
		$settings['item_name']		= sprintf( _AEC_MI_SET1_INAME, '[[cms_live_site]]',
									'[[user_name]]', '[[user_username]]' );
		$settings['rewriteInfo']	= ''; // added mic


		return $settings;
	}

	function backend_settings () {
		$settings = array();
		$settings['testmode'] = array("list_yesno");
		$settings['instId'] = array("inputC");
		$settings['currency'] = array("list_currency");
		$settings['item_name'] = array("inputE");
 		$rewriteswitches = array("cms", "user", "expiration", "subscription", "plan");
        $settings['rewriteInfo'] = array("fieldset", "Rewriting Info", AECToolbox::rewriteEngineInfo($rewriteswitches));

		return $settings;
	}

	function createGatewayLink ( $int_var, $cfg, $metaUser, $new_subscription ) {
		global $mosConfig_live_site;

		$var['post_url']	= "https://select.worldpay.com/wcc/purchase";
		if ($cfg->testmode) {
			$var['testMode'] = "100";
		}

		$var['instId']		= $cfg['instId'];
		$var['currency']	= $cfg['currency'];
		$var['cartId']		= $int_var['invoice'];
		$var['amount']		= $int_var['amount'];

		$var['desc']	= AECToolbox::rewriteEngine($cfg['item_name'], $metaUser, $new_subscription);

		return $var;
	}

	function parseNotification ( $post, $cfg ) {

		$description			= $post['description'];
		$key					= $post['key'];
		$cart_order_id			= $post['cart_order_id'];
		$total					= $post['total'];
		$userid					= $post['cust_id'];

		$response = array();
		$response['invoice'] = $post['cart_order_id'];
		$response['valid'] = 0;

		return $response;
	}

}

?>
