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

class processor_paypal_subscription
{
	function processor_paypal_subscription()
	{
		global $mosConfig_absolute_path;

		if ( !defined( '_AEC_LANG_PROCESSOR' ) ) {
			$langPath = $mosConfig_absolute_path . '/components/com_acctexp/processors/com_acctexp_language_processors/';
			if (file_exists( $langPath . $GLOBALS['mosConfig_lang'] . '.php' )) {
				include_once( $langPath . $GLOBALS['mosConfig_lang'] . '.php' );
			} else {
				include_once( $langPath . 'english.php' );
			}
		}
	}

	function info()
	{
		$info = array();
		$info['name']			= 'paypal_subscription';
		$info['longname'] 		= _AEC_PROC_INFO_PPS_LNAME;
		$info['statement'] 		= _AEC_PROC_INFO_PPS_STMNT;
		$info['description'] 	= _DESCRIPTION_PAYPAL_SUBSCRIPTION;
		$info['currencies'] 	= 'EUR,USD,AUD,CAD,GBP,JPY,NZD,CHF,HKD,SGD,SEK,DKK,PLN,NOK,HUF,CZK';
		$info['languages'] 		= 'GB,DE,FR,IT,ES,US,AU';
		$info['cc_list']		= 'visa,mastercard,discover,americanexpress,echeck,giropay';
		$info['recurring']		= 1;
		$info['cancel_info']	= _PAYPAL_SUBSCRIPTION_CANCEL_INFO;

		return $info;
	}

	function settings()
	{
		$settings = array();
		$settings['business']		= 'your@business.com';
		$settings['testmode']		= 0;
		$settings['tax']			= '';
		$settings['currency']		= 'USD';
		$settings['checkbusiness']	= 0;
		$settings['lc']				= 'US';
		$settings['no_shipping']	= 1;
		$settings['altipnurl']		= '';
		$settings['item_name']		= sprintf( _CFG_PROCESSOR_ITEM_NAME_DEFAULT, '[[cms_live_site]]',
									'[[user_name]]', '[[user_username]]' );
		$settings['rewriteInfo']	= ''; // added mic

		return $settings;
	}

	function backend_settings()
	{
		$settings = array();
		$rewriteswitches			= array( 'cms', 'user', 'expiration', 'subscription', 'plan' );

		$settings['business']		= array( 'inputC' );
		$settings['testmode']		= array( 'list_yesno' );
		$settings['tax']			= array( 'inputA' );
		$settings['currency']		= array( 'list_currency' );
		$settings['checkbusiness']	= array( 'list_yesno' );
		$settings['lc']				= array( 'list_language' );
		$settings['no_shipping']	= array( 'list_yesno' );
		$settings['altipnurl']		= array( 'inputC' );
		$settings['item_name']		= array( 'inputE' );

        $settings['rewriteInfo']	= array( 'fieldset', _AEC_MI_REWRITING_INFO,
        							AECToolbox::rewriteEngineInfo( $rewriteswitches ) );

		return $settings;
	}

	function createGatewayLink( $int_var, $cfg, $metaUser, $new_subscription )
	{
		global $mosConfig_live_site;

		if ( $cfg['testmode'] ) {
			$var['post_url']	= 'https://www.sandbox.paypal.com/cgi-bin/webscr';
		} else {
			$var['post_url']	= 'https://www.paypal.com/cgi-bin/webscr';
		}

		$var['cmd']	= '_xclick-subscriptions';
		$var['src']	= "1";
		$var['sra']	= "1";

		if ( isset( $int_var['amount']['amount1'] ) ) {
			$var['a1'] = $int_var['amount']['amount1'];
			$var['p1'] = $int_var['amount']['period1'];
			$var['t1'] = $int_var['amount']['unit1'];
		}

		if ( isset( $int_var['amount']['amount2'] ) ) {
			$var['a2'] = $int_var['amount']['amount2'];
			$var['p2'] = $int_var['amount']['period2'];
			$var['t2'] = $int_var['amount']['unit2'];
		}

		$var['a3'] = $int_var['amount']['amount3'];
		$var['p3'] = $int_var['amount']['period3'];
		$var['t3'] = $int_var['amount']['unit3'];

		if ( !empty( $cfg['tax'] ) && $cfg['tax'] > 0 ) {
			$tax = $var['a3']/(100+$cfg['tax'])*100;
			$var['tax'] = round(($var['a3'] - $tax), 2);
			$var['a3'] = round($tax, 2);
		}

		$var['business']		= $cfg['business'];
		$var['invoice']			= $int_var['invoice'];
		$var['cancel']			= AECToolbox::deadsureURL( '/index.php?option=com_acctexp&amp;task=cancel' );

		if ( strpos( $cfg['altipnurl'], 'http://' ) === 0 ) {
			$var['notify_url']	= $cfg['altipnurl'] . '/index.php?option=com_acctexp&amp;task=paypal_subscriptionnotification';
		} else {
			$var['notify_url']	= AECToolbox::deadsureURL( '/index.php?option=com_acctexp&amp;task=paypal_subscriptionnotification' );
		}

		$var['item_number']		= $metaUser->cmsUser->id;
		$var['item_name']		= AECToolbox::rewriteEngine( $cfg['item_name'], $metaUser, $new_subscription );

		$var['no_shipping']		= $cfg['no_shipping'];
		$var['no_note']			= '1';
		$var['rm']				= '2';

		$var['return']			= $int_var['return_url'];
		$var['currency_code']	= $cfg['currency'];
		$var['lc']				= $cfg['lc'];

		return $var;
	}

	function parseNotification( $post, $cfg )
	{
		global $database;

		$txn_type			= $post['txn_type'];
		$item_number		= $post['item_number'];
		$mc_gross			= $post['mc_gross'];
		if ( $mc_gross == '' ) {
			$mc_gross 		= $post['mc_amount1'];
		}
		$mc_currency		= $post['mc_currency'];
		$receiver_email		= $post['receiver_email'];
		$payment_status		= $post['payment_status'];
		$payment_type		= $post['payment_type'];
		$subscr_date		= $post['subscr_date'];
		if ( isset( $_POST['amount1'] ) ) {
			$amount1			= $post['amount1'];
		}
		$invoice_number		= $post['invoice'];
		$custom				= trim($post['custom']);

		if ( $cfg['testmode'] ) {
			$ppurl = 'www.sandbox.paypal.com';
		} else {
			$ppurl = 'www.paypal.com';
		}

		$req = 'cmd=_notify-validate';

		foreach ( $post as $key => $value ) {
			$value = urlencode(stripslashes($value));
			$req .= "&$key=$value";
		}

		$fp = null;
		// try to use fsockopen. some hosting systems disable fsockopen (godaddy.com)
		$fp = $this->doTheHttp( $ppurl, $req );
		if (!$fp) {
			// If fsockopen doesn't work try using curl
			$fp = $this->doTheCurl( $ppurl, $req );
		}

		$res = $fp;

		$response = array();
		$response['invoice'] = $_POST['invoice'];
		$response['processorresponse'] = $res;
		$response['amount_paid'] = $mc_gross;
		$response['amount_currency'] = $mc_currency;

		$objInvoice = new Invoice($database);
		$objInvoice->loadInvoiceNumber($_POST['invoice']);
		$objInvoice->computeAmount();
		$invoiceamount = $objInvoice->amount;

		if ( strcmp( $receiver_email, $cfg['business'] ) != 0 && $cfg['checkbusiness'] ) {
			$response['valid'] = 0;
			$response['pending_reason'] = "checkbusiness error";
		} elseif ( strcmp($res, "VERIFIED") == 0 ) {
			$response['valid'] = 0; // mic: set generic, if true will be set below
			// Process payment: Paypal Subscription & Buy Now
			if ( strcmp( $txn_type, 'web_accept' ) == 0 || strcmp( $txn_type, 'subscr_payment' ) == 0 ) {

				$recurring = ( strcmp( $txn_type, 'subscr_payment' ) == 0 );

				if ( strcmp( $payment_type, 'instant' ) == 0 && strcmp( $payment_status, 'Pending' ) == 0 ) {
					$response['pending_reason'] = $post['pending_reason'];
				} elseif ( strcmp( $payment_type, 'instant' ) == 0 && strcmp( $payment_status, 'Completed' ) == 0 ) {
					$response['valid']			= 1;
				} elseif ( strcmp( $payment_type, 'echeck' ) == 0 && strcmp( $payment_status, 'Pending' ) == 0 ) {
					$response['pending']		= 1;
					$response['pending_reason'] = 'echeck';
				} elseif ( strcmp( $payment_type, 'echeck' ) == 0 && strcmp( $payment_status, 'Completed' ) == 0 ) {
					$response['valid']			= 1;
				}
			} elseif ( strcmp( $txn_type, 'subscr_signup' ) == 0 ) {
				$response['valid']			= 0;
				$response['pending']		= 1;
				$response['pending_reason'] = 'signup';
			} elseif ( strcmp( $txn_type, 'subscr_eot' ) == 0 ) {
				$response['eot']				= 1;
			} elseif ( strcmp( $txn_type, 'subscr_cancel' ) == 0 ) {
				$response['cancel']				= 1;
			}
		} else {
			$response['pending_reason']			= 'error: ' . $res;
		}

		return $response;
	}

	function doTheCurl( $url, $req )
	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_VERBOSE, 1);
		//curl_setopt ($ch, CURLOPT_HTTPPROXYTUNNEL, TRUE);
		curl_setopt ($ch, CURLOPT_PROXYTYPE, CURLPROXY_HTTP);
		curl_setopt ($ch, CURLOPT_PROXY,"http://proxy.shr.secureserver.net:3128");
		curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt ($ch, CURLOPT_URL, $url);
		curl_setopt ($ch, CURLOPT_POST, 1);
		curl_setopt ($ch, CURLOPT_POSTFIELDS, $req);
		curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt ($ch, CURLOPT_TIMEOUT, 120);
		$fp = curl_exec ($ch);
		curl_close($ch);

		return $fp;
	}

	function doTheHttp( $url, $req )
	{
		$header  = ""
		. "POST /cgi-bin/webscr HTTP/1.0\r\n"
		. "Host: " . $url  . ":80\r\n"
		. "Content-Type: application/x-www-form-urlencoded\r\n"
		. "Content-Length: " . strlen($req) . "\r\n\r\n"
		;
		$fp = fsockopen( $url, 80, $errno, $errstr, 30 );

		if ( !$fp ) {
			return 'ERROR';
		} else {
			fwrite( $fp, $header . $req );

			while ( !feof( $fp ) ) {
				$res = fgets( $fp, 1024 );
				if ( strcmp( $res, 'VERIFIED' ) == 0 ) {
					return 'VERIFIED';
				} elseif ( strcmp( $res, 'INVALID' ) == 0 ) {
					return 'INVALID';
				}
			}
			fclose( $fp );
		}
		return 'ERROR';
	}
}
?>