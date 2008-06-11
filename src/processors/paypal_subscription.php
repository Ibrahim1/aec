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

class processor_paypal_subscription extends POSTprocessor
{
	function info()
	{
		$info = array();
		$info['name']			= 'paypal_subscription';
		$info['longname'] 		= _AEC_PROC_INFO_PPS_LNAME;
		$info['statement'] 		= _AEC_PROC_INFO_PPS_STMNT;
		$info['description'] 	= _DESCRIPTION_PAYPAL_SUBSCRIPTION;
		$info['currencies'] 	= 'EUR,USD,AUD,CAD,GBP,JPY,NZD,CHF,HKD,SGD,SEK,DKK,PLN,NOK,HUF,CZK';
		$info['languages'] 		= 'GB,DE,FR,IT,ES,US,AU,NL';
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
		$settings['acceptpendingecheck'] = 0;
		$settings['srt']			= '';
		$settings['lc']				= 'US';
		$settings['no_shipping']	= 1;
		$settings['altipnurl']		= '';
		$settings['item_name']		= sprintf( _CFG_PROCESSOR_ITEM_NAME_DEFAULT, '[[cms_live_site]]', '[[user_name]]', '[[user_username]]' );
		$settings['customparams']	= "";

		// Customization Options
		$settings['cbt']					= '';
		$settings['cn']						= '';
		$settings['cpp_header_image']		= '';
		$settings['cpp_headerback_color']	= '';
		$settings['cpp_headerborder_color']	= '';
		$settings['cpp_payflow_color']		= '';
		$settings['cs']						= 0;
		$settings['image_url']				= '';
		$settings['page_style']				= '';

		return $settings;
	}

	function backend_settings()
	{
		$settings = array();
		$settings['business']				= array( 'inputC' );
		$settings['testmode']				= array( 'list_yesno' );
		$settings['tax']					= array( 'inputA' );
		$settings['currency']				= array( 'list_currency' );
		$settings['checkbusiness']			= array( 'list_yesno' );
		$settings['acceptpendingecheck']	= array( 'list_yesno' );
		$settings['srt']					= array( 'inputA' );
		$settings['lc']						= array( 'list_language' );
		$settings['no_shipping']			= array( 'list_yesno' );
		$settings['altipnurl']				= array( 'inputC' );
		$settings['item_name']				= array( 'inputE' );
		$settings['customparams']			= array( 'inputD' );

		// Customization Options
		$settings['cbt']					= array( 'inputE' );
		$settings['cn']						= array( 'inputE' );
		$settings['cpp_header_image']		= array( 'inputE' );
		$settings['cpp_headerback_color']	= array( 'inputC' );
		$settings['cpp_headerborder_color']	= array( 'inputC' );
		$settings['cpp_payflow_color']		= array( 'inputC' );
		$settings['cs']						= array( 'list_yesno' );
		$settings['image_url']				= array( 'inputE' );
		$settings['page_style']				= array( 'inputE' );

        $settings = AECToolbox::rewriteEngineInfo( null, $settings );

		return $settings;
	}

	function createGatewayLink( $request )
	{
		global $mosConfig_live_site;

		if ( $this->settings['testmode'] ) {
			$var['post_url']	= 'https://www.sandbox.paypal.com/cgi-bin/webscr';
		} else {
			$var['post_url']	= 'https://www.paypal.com/cgi-bin/webscr';
		}

		$var['cmd']	= '_xclick-subscriptions';
		$var['src']	= "1";
		$var['sra']	= "1";

		if ( isset( $request->int_var['amount']['amount1'] ) ) {
			$var['a1'] = $request->int_var['amount']['amount1'];
			$var['p1'] = $request->int_var['amount']['period1'];
			$var['t1'] = $request->int_var['amount']['unit1'];
		}

		if ( isset( $request->int_var['amount']['amount2'] ) ) {
			$var['a2'] = $request->int_var['amount']['amount2'];
			$var['p2'] = $request->int_var['amount']['period2'];
			$var['t2'] = $request->int_var['amount']['unit2'];
		}

		$var['a3'] = $request->int_var['amount']['amount3'];
		$var['p3'] = $request->int_var['amount']['period3'];
		$var['t3'] = $request->int_var['amount']['unit3'];

		if ( !empty( $this->settings['tax'] ) && $this->settings['tax'] > 0 ) {
			$tax = $var['a3']/(100+$this->settings['tax'])*100;
			$var['tax'] = round(($var['a3'] - $tax), 2);
			$var['a3'] = round($tax, 2);
		}

		$var['business']		= $this->settings['business'];
		$var['invoice']			= $request->int_var['invoice'];
		$var['cancel']			= AECToolbox::deadsureURL( '/index.php?option=com_acctexp&amp;task=cancel' );

		if ( strpos( $this->settings['altipnurl'], 'http://' ) === 0 ) {
			$var['notify_url']	= $this->settings['altipnurl'] . '/index.php?option=com_acctexp&amp;task=paypal_subscriptionnotification';
		} else {
			$var['notify_url']	= AECToolbox::deadsureURL( '/index.php?option=com_acctexp&amp;task=paypal_subscriptionnotification' );
		}

		$var['item_number']		= $request->metaUser->cmsUser->id;
		$var['item_name']		= AECToolbox::rewriteEngineRQ( $this->settings['item_name'], $request );

		$var['no_shipping']		= $this->settings['no_shipping'];
		$var['no_note']			= '1';
		$var['rm']					= '2';

		$var['return']			= $request->int_var['return_url'];
		$var['currency_code']	= $this->settings['currency'];
		$var['lc']				= $this->settings['lc'];

		if ( !empty( $this->settings['srt'] ) ) {
			$var['srt']			=  $this->settings['srt'];
		}

		// Customizations
		$customizations = array( 'cbt', 'cn', 'cpp_header_image', 'cpp_headerback_color', 'cpp_headerborder_color', 'cpp_payflow_color', 'image_url', 'page_style' );

		foreach ( $customizations as $cust ) {
			if ( !empty( $this->settings[$cust] ) ) {
				$var[$cust] = $this->settings[$cust];
			}
		}

		if ( isset( $this->settings['cs'] ) ) {
			if ( $this->settings['cs'] != 0 ) {
				$var['cs'] = $this->settings['cs'];
			}
		}

		return $var;
	}

	function parseNotification( $post )
	{
		global $database;

		$mc_gross			= $post['mc_gross'];
		if ( $mc_gross == '' ) {
			$mc_gross 		= $post['mc_amount1'];
		}
		$mc_currency		= $post['mc_currency'];

		$response = array();
		$response['invoice'] = $post['invoice'];
		$response['amount_paid'] = $mc_gross;
		$response['amount_currency'] = $mc_currency;

		return $response;
	}

	function validateNotification( $response, $post, $invoice )
	{
		$path = '/cgi-bin/webscr';
		if ($this->settings['testmode']) {
			$ppurl = 'www.sandbox.paypal.com' . $path;
		} else {
			$ppurl = 'www.paypal.com' . $path;
		}

		$req = 'cmd=_notify-validate';

		foreach ( $post as $key => $value ) {
			$value = urlencode( stripslashes( $value ) );
			$req .= "&$key=$value";
		}

		$fp = null;
		// try to use fsockopen. some hosting systems disable fsockopen (godaddy.com)
		$fp = transmitRequest( $ppurl, $path, $req );

		$res = $fp;

		$response['responsestring'] = 'paypal_verification=' . $res . "\n" . $response['responsestring'];

		$txn_type			= $post['txn_type'];
		$receiver_email		= $post['receiver_email'];
		$payment_status		= $post['payment_status'];
		$payment_type		= $post['payment_type'];

		$response['valid'] = 0;

		if ( strcmp( $receiver_email, $this->settings['business'] ) != 0 && $this->settings['checkbusiness'] ) {
			$response['pending_reason'] = 'checkbusiness error';
		} elseif ( strcmp( $res, 'VERIFIED' ) == 0 ) {
			// Process payment: Paypal Subscription & Buy Now
			if ( strcmp( $txn_type, 'web_accept' ) == 0 || strcmp( $txn_type, 'subscr_payment' ) == 0 ) {

				$recurring = ( strcmp( $txn_type, 'subscr_payment' ) == 0 );

				if ( ( strcmp( $payment_type, 'instant' ) == 0 ) && ( strcmp( $payment_status, 'Pending' ) == 0 ) ) {
					$response['pending_reason'] = $post['pending_reason'];
				} elseif ( strcmp( $payment_type, 'instant' ) == 0 && strcmp( $payment_status, 'Completed' ) == 0 ) {
					$response['valid']			= 1;
				} elseif ( strcmp( $payment_type, 'echeck' ) == 0 && strcmp( $payment_status, 'Pending' ) == 0 ) {
					if ( $this->settings['acceptpendingecheck'] ) {
						if ( is_object( $invoice ) ) {
							$invoice->setParams( array( 'acceptedpendingecheck' => 1 ) );
							$invoice->check();
							$invoice->store();
						}

						$response['valid']			= 1;
						$response['pending_reason'] = 'echeck';
					} else {
						$response['pending']		= 1;
						$response['pending_reason'] = 'echeck';
					}
				} elseif ( strcmp( $payment_type, 'echeck' ) == 0 && strcmp( $payment_status, 'Completed' ) == 0 ) {
					if ( $this->settings['acceptpendingecheck'] ) {
						if ( is_object( $invoice ) ) {
							$invoiceparams = $invoice->getParams();

							if ( isset( $invoiceparams['acceptedpendingecheck'] ) ) {
								$response['valid']		= 0;
								$response['duplicate']	= 1;

								$invoice->delParams( array( 'acceptedpendingecheck' ) );
								$invoice->check();
								$invoice->store();
							}
						} else {
							$response['valid']			= 1;
						}
					} else {
						$response['valid']			= 1;
					}
				}
			} elseif ( strcmp( $txn_type, 'subscr_signup' ) == 0 ) {
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

}
?>