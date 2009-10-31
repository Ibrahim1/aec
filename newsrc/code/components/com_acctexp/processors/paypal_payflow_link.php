<?php
/**
 * @version $Id: paypal_payflow_link.php
 * @package AEC - Account Control Expiration - Membership Manager
 * @subpackage Processors - PayPal Subscription
 * @copyright 2007-2008 Copyright (C) David Deutsch
 * @author David Deutsch <skore@skore.de> & Team AEC - http://www.valanx.org
 * @license GNU/GPL v.2 http://www.gnu.org/licenses/old-licenses/gpl-2.0.html or, at your option, any later version
 */

// Dont allow direct linking
( defined('_JEXEC') || defined( '_VALID_MOS' ) ) or die( 'Direct Access to this location is not allowed.' );

class processor_paypal_payflow_link extends POSTprocessor
{
	function info()
	{
		$info = array();
		$info['name']			= 'paypal_payflow_link';
		$info['longname'] 		= _CFG_PAYPAL_PAYFLOW_LINK_LONGNAME;
		$info['statement'] 		= _CFG_PAYPAL_PAYFLOW_LINK_STATEMENT;
		$info['description'] 	= _CFG_PAYPAL_PAYFLOW_LINK_DESCRIPTION;
		$info['currencies'] 	= 'EUR,USD,AUD,CAD,GBP,JPY,NZD,CHF,HKD,SGD,SEK,DKK,PLN,NOK,HUF,CZK,MXN,ILS';
		$info['languages'] 		= AECToolbox::getISO4271_codes();
		$info['cc_list']		= 'visa,mastercard,discover,americanexpress,echeck,giropay';
		$info['recurring']		= 1;
		$info['cancel_info']	= _PAYPAL_SUBSCRIPTION_CANCEL_INFO;

		return $info;
	}

	function settings()
	{
		$settings = array();
		$settings['business']		= 'your@paypal@account.com';
		$settings['brokenipnmode']	= 0;
		$settings['checkbusiness']	= 0;
		$settings['acceptpendingecheck'] = 0;
		$settings['srt']			= '';
		$settings['lc']				= 'US';
		$settings['altipnurl']		= '';
		$settings['item_name']		= sprintf( _CFG_PROCESSOR_ITEM_NAME_DEFAULT, '[[cms_live_site]]', '[[user_name]]', '[[user_username]]' );
		$settings['customparams']	= "";

		return $settings;
	}

	function backend_settings()
	{
		$settings = array();
		$settings['business']				= array( 'inputC' );
		$settings['brokenipnmode']			= array( 'list_yesno' );
		$settings['checkbusiness']			= array( 'list_yesno' );
		$settings['acceptpendingecheck']	= array( 'list_yesno' );
		$settings['partner']				= array( 'inputC' );
		$settings['altipnurl']				= array( 'inputC' );
		$settings['item_name']				= array( 'inputE' );
		$settings['customparams']			= array( 'inputD' );

        $settings = AECToolbox::rewriteEngineInfo( null, $settings );

		return $settings;
	}

	function createGatewayLink( $request )
	{
		$var['post_url']	= 'https://payflowlink.paypal.com';

		$var['LOGIN']	= $this->settings['business'];
		$var['PARTNER']	= $this->settings['partner'];

		if ( is_array( $request->int_var['amount'] ) ) {
			$var['a1'] = $request->int_var['amount']['amount1'];
			$var['p1'] = $request->int_var['amount']['period1'];
			$var['t1'] = $request->int_var['amount']['unit1'];

			if ( isset( $request->int_var['amount']['amount2'] ) ) {
				$var['a2'] = $request->int_var['amount']['amount2'];
				$var['p2'] = $request->int_var['amount']['period2'];
				$var['t2'] = $request->int_var['amount']['unit2'];
			}

			$var['a3'] = $request->int_var['amount']['amount3'];
			$var['p3'] = $request->int_var['amount']['period3'];
			$var['t3'] = $request->int_var['amount']['unit3'];
		} else {
			$var['AMOUNT']	= $request->int_var['amount'];
		}

		$var['TYPE']	= "S";

		$var['INVOICE']			= $request->invoice->invoice_number;

		$var['DESCRIPTION']		= AECToolbox::rewriteEngineRQ( $this->settings['item_name'], $request );

		$var['return']			= $request->int_var['return_url'];
		$var['currency_code']	= $this->settings['currency'];

		return $var;
	}

	function parseNotification( $post )
	{
		$response = array();
		$response['invoice']		= $post['INVOICE'];
		$response['amount_paid']	= $post['AMOUNT'];

		return $response;
	}

	function validateNotification( $response, $post, $invoice )
	{
		$path = '/cgi-bin/webscr';
		$ppurl = 'https://www.paypal.com' . $path;

		$req = 'cmd=_notify-validate';

		foreach ( $post as $key => $value ) {
			$value = urlencode( stripslashes( $value ) );
			$req .= "&$key=$value";
		}

		$fp = null;
		// try to use fsockopen. some hosting systems disable fsockopen (godaddy.com)
		$fp = $this->transmitRequest( $ppurl, $path, $req );

		$res = $fp;

		$response['fullresponse']['paypal_verification'] = $res;

		$response['valid'] = 0;

		if ( strcmp( $receiver_email, $this->settings['business'] ) != 0 && $this->settings['checkbusiness'] ) {
			$response['pending_reason'] = 'checkbusiness error';
		} elseif ( ( strcmp( $res, 'VERIFIED' ) == 0 ) || ( empty( $res ) && !empty( $this->settings['brokenipnmode'] ) ) ) {
			if ( empty( $res ) && !empty( $this->settings['brokenipnmode'] ) ) {
				$response['fullresponse']['paypal_verification'] = "MANUAL_OVERRIDE";
			}

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
							$invoice->storeload();
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
							if ( isset( $invoice->params['acceptedpendingecheck'] ) ) {
								$response['valid']		= 0;
								$response['duplicate']	= 1;

								$invoice->delParams( array( 'acceptedpendingecheck' ) );
								$invoice->storeload();
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
			} elseif ( strcmp( $txn_type, 'paymentreview' ) == 0 ) {
				$response['pending']			= 1;
				$response['pending_reason']	 = 'paymentreview';
			} elseif ( strcmp( $txn_type, 'subscr_eot' ) == 0 ) {
				$response['eot']				= 1;
			} elseif ( strcmp( $txn_type, 'subscr_failed' ) == 0 ) {
				$response['null']				= 1;
				$response['explanation']		= 'Subscription Payment Failed';
			} elseif ( strcmp( $txn_type, 'subscr_cancel' ) == 0 ) {
				$response['cancel']				= 1;
			} elseif ( strcmp( $reason_code, 'refund' ) == 0 ) {
				$response['delete']				= 1;
			}
		} else {
			$response['pending_reason']			= 'error: ' . $res;
		}

		return $response;
	}

}
?>