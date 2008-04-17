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

class processor_authorize extends POSTprocessor
{
	function info()
	{
		$info = array();
		$info['name'] = "authorize";
		$info['longname'] = "Authorize.net";
		$info['statement'] = "Make payments with Authorize.net!";
		$info['description'] = _DESCRIPTION_AUTHORIZE;
		$info['currencies'] = 'AFA,DZD,ADP,ARS,AMD,AWG,AUD,AZM,BSD,BHD,THB,PAB,BBD,BYB,BEF,BZD,BMD,VEB,BOB,BRL,BND,BGN,BIF,CAD,CVE,KYD,GHC,XOF,XAF,XPF,CLP,COP,KMF,BAM,NIO,CRC,CUP,CYP,CZK,GMD,'.
								'DKK,MKD,DEM,AED,DJF,STD,DOP,VND,GRD,XCD,EGP,SVC,ETB,EUR,FKP,FJD,HUF,CDF,FRF,GIP,XAU,HTG,PYG,GNF,GWP,GYD,HKD,UAH,ISK,INR,IRR,IQD,IEP,ITL,JMD,JOD,KES,PGK,LAK,EEK,'.
								'HRK,KWD,MWK,ZMK,AOR,MMK,GEL,LVL,LBP,ALL,HNL,SLL,ROL,BGL,LRD,LYD,SZL,LTL,LSL,LUF,MGF,MYR,MTL,TMM,FIM,MUR,MZM,MXN,MXV,MDL,MAD,BOV,NGN,ERN,NAD,NPR,ANG,NLG,YUM,ILS,'.
								'AON,TWD,ZRN,NZD,BTN,KPW,NOK,PEN,MRO,TOP,PKR,XPD,MOP,UYU,PHP,XPT,PTE,GBP,BWP,QAR,GTQ,ZAL,ZAR,OMR,KHR,MVR,IDR,RUB,RUR,RWF,SAR,ATS,SCR,XAG,SGD,SKK,SBD,KGS,SOS,ESP,'.
								'LKR,SHP,ECS,SDD,SRG,SEK,CHF,SYP,TJR,BDT,WST,TZS,KZT,TPE,SIT,TTD,MNT,TND,TRL,UGX,ECV,CLF,USN,USS,USD,UZS,VUV,KRW,YER,JPY,CNY,ZWD,PLN';
		$info['cc_list'] = "visa,mastercard,discover,americanexpress,echeck,jcb,dinersclub";
		$info['recurring'] = 0;
		$info['notify_trail_thanks'] = 1;

		return $info;
	}

	function settings()
	{
		$settings = array();
		$settings['login']				= "login";
		$settings['transaction_key']	= "transaction_key";
		$settings['testmode']			= 0;
		$settings['currency']			= "USD";
		$settings['timestamp_offset']	= '';
        $settings['item_name']			= sprintf( _CFG_PROCESSOR_ITEM_NAME_DEFAULT, '[[cms_live_site]]', '[[user_name]]', '[[user_username]]' );
        $settings['customparams']		= "";

		// Customization
		$settings['x_logo_url']				= '';
		$settings['x_background_url']		= '';
		$settings['x_color_background']		= '';
		$settings['x_color_link']			= '';
		$settings['x_color_text']			= '';
		$settings['x_header_html_receipt']	= '';
		$settings['x_footer_html_receipt']	= '';

		return $settings;
	}

	function backend_settings()
	{
		$settings = array();
		$settings['testmode']			= array("list_yesno");
		$settings['login'] 				= array("inputC");
		$settings['transaction_key']	= array("inputC");
		$settings['currency']			= array("list_currency");
		$settings['timestamp_offset']	= array("inputC");
		$settings['item_name']			= array("inputE");
		$settings['customparams']		= array( 'inputD' );

 		$rewriteswitches 				= array("cms", "user", "expiration", "subscription", "plan");
		$settings = AECToolbox::rewriteEngineInfo( $rewriteswitches, $settings );

		// Customization
		$settings['x_logo_url']				= array( 'inputE' );
		$settings['x_background_url']		= array( 'inputE' );
		$settings['x_color_background']		= array( 'inputC' );
		$settings['x_color_link']			= array( 'inputC' );
		$settings['x_color_text']			= array( 'inputC' );
		$settings['x_header_html_receipt']	= array( 'inputE' );
		$settings['x_footer_html_receipt']	= array( 'inputE' );

		return $settings;
	}

	function createGatewayLink( $request )
	{
		global $mosConfig_live_site;

		if ( $this->settings['testmode'] ) {
			$var['post_url']	= "https://test.authorize.net/gateway/transact.dll";
		} else {
			$var['post_url']	= "https://secure.authorize.net/gateway/transact.dll";
		}

		$var['x_login']					= $this->settings['login'];
		$var['x_invoice_num']			= $request->int_var['invoice'];
		$var['x_receipt_link_method']	= "POST";
		//$var['x_receipt_link_url']		= AECToolbox::deadsureURL("/index.php?option=com_acctexp&amp;task=authorizenotification");
		//$var['x_receipt_link_text']		= "Continue";
		$var['x_show_form']				= "PAYMENT_FORM";
		//$var['x_relay_response']		= "True";
		//$var['x_relay_url']			= AECToolbox::deadsureURL('/index.php?option=com_acctexp&task=authnotification');

		$var['x_amount'] = $request->int_var['amount'];
		srand(time());
		$sequence = rand(1, 1000);

		if ( !empty( $this->settings['timestamp_offset'] ) ) {
			$tstamp = time() + $this->settings['timestamp_offset'];
		} else {
			$tstamp = time();
		}

		// Calculate fingerprint
		$data = $this->settings['login'] . "^" . $sequence . "^" . $tstamp . "^" . $request->int_var['amount'] . "^" . "";
		$fingerprint = $this->hmac($this->settings['transaction_key'], $data);
		// Insert the form elements required for SIM
		$var['x_fp_sequence']	= $sequence;

		$var['x_fp_timestamp']	= $tstamp;
		$var['x_fp_hash']		= $fingerprint;

		$var['x_cust_id']			= $metaUser->cmsUser->id;
		$var['x_description']		= AECToolbox::rewriteEngine($this->settings['item_name'], $metaUser, $new_subscription);

		$var = $this->customParams( $this->settings['customparams'], $var, $metaUser, $new_subscription, $invoice );

		if ( !empty( $this->settings['customparams'] ) ) {
			$rw_params = AECToolbox::rewriteEngine( $this->settings['customparams'], $metaUser, $new_subscription );

			$cps = explode( "\n", $rw_params );

			foreach ( $cps as $cp ) {
				$cpa = explode( '=', $cp );

				if ( !empty( $cpa[0] ) && isset( $cp[1] ) ) {
					$var[$cpa[0]] = $cpa[1];
				}
			}
		}

		return $var;
	}

	function parseNotification( $post )
	{
		$x_description			= $post['x_description'];

		$x_amount				= $post['x_amount'];
		$userid					= $post['x_cust_id'];

		$response = array();
		$response['invoice'] = $post['x_invoice_num'];

		return $response;
	}

	function validateNotification( $response, $post, $invoice )
	{
		$x_response_code		= $post['x_response_code'];
		$x_response_reason_text	= $post['x_response_reason_text'];

		$response['valid'] = ($x_response_code == '1');

		return $response;
	}

	function hmac( $key, $data )
	{
	   // RFC 2104 HMAC implementation for php.
	   // Creates an md5 HMAC.
	   // Eliminates the need to install mhash to compute a HMAC
	   // Hacked by Lance Rushing

	   $b = 64; // byte length for md5

	   if (strlen($key) > $b) {
	       $key = pack("H*",md5($key));
	   }
	   $key  = str_pad($key, $b, chr(0x00));
	   $ipad = str_pad('', $b, chr(0x36));
	   $opad = str_pad('', $b, chr(0x5c));
	   $k_ipad = $key ^ $ipad ;
	   $k_opad = $key ^ $opad;

	   return md5($k_opad  . pack("H*",md5($k_ipad . $data)));
	}

}
?>