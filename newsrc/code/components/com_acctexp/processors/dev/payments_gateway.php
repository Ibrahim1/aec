<?php
/**
 * @version $Id: payments_gateway.php
 * @package AEC - Account Control Expiration - Membership Manager
 * @subpackage Processors - Payments Gateway
 * @copyright 2012 Copyright (C) David Deutsch
 * @author David Deutsch <skore@valanx.org> & Team AEC - http://www.valanx.org
 * @license GNU/GPL v.2 http://www.gnu.org/licenses/old-licenses/gpl-2.0.html or, at your option, any later version
 */

// Dont allow direct linking
( defined('_JEXEC') || defined( '_VALID_MOS' ) ) or die( 'Direct Access to this location is not allowed.' );

class processor_payments_gateway extends POSTprocessor
{
	function info()
	{
		$info = array();
		$info['name']			= 'payments_gateway';
		$info['longname']		= JText::_('CFG_PAYMENTS_GATEWAY_LONGNAME');
		$info['statement']		= JText::_('CFG_PAYMENTS_GATEWAY_STATEMENT');
		$info['description']	= JText::_('CFG_PAYMENTS_GATEWAY_DESCRIPTION');
		$info['currencies']		= 'USD';
		$info['languages']		= AECToolbox::getISO639_1_codes();
		$info['cc_list']		= 'visa,mastercard,discover,americanexpress,echeck';
		$info['recurring']		= 2;
		$info['notify_trail_thanks']	= 1;

		return $info;
	}

	function settings()
	{
		$settings = array();
		$settings['api_login_id']	= '112233445566';
		$settings['api_key']		= '112233445566';
		$settings['testmode']		= 0;
		$settings['invoice_tax']	= 0;
		$settings['tax']			= '';
		$settings['currency']		= 'USD';
		$settings['customparams']	= "";

		return $settings;
	}

	function backend_settings()
	{
		$settings = array();

		$settings['api_login_id']			= array( 'inputC' );
		$settings['api_key']				= array( 'inputC' );
		$settings['testmode']				= array( 'list_yesno' );
		$settings['invoice_tax']			= array( 'list_yesno' );
		$settings['tax']					= array( 'inputA' );
		$settings['currency']				= array( 'list_currency' );
		$settings['customparams']			= array( 'inputD' );

		$settings = AECToolbox::rewriteEngineInfo( null, $settings );

		return $settings;
	}

	function createGatewayLink( $request )
	{
		if ( $this->settings['testmode'] ) {
			$var['post_url']	= 'https://sandbox.paymentsgateway.net/swp/co/default.aspx';
		} else {
			$var['post_url']	= 'https://swp.paymentsgateway.net/co/default.aspx';
		}

		$namearray		= $request->metaUser->explodeName();

		$var['pg_billto_postal_name_first']	= $namearray['first'];
		$var['pg_billto_postal_name_last']	= $namearray['last'];

		if ( !empty( $this->settings['invoice_tax'] ) && isset( $request->items->tax ) ) {
			$tax = 0;

			foreach ( $request->items->tax as $itax ) {
				$tax += $itax['cost'];
			}

			$var['pg_sales_tax_amount']			= $tax;

			$var['pg_total_amount']		= $request->items->total->cost['amount'];
		} elseif ( !empty( $this->settings['tax'] ) && $this->settings['tax'] > 0 ) {
			$amount				= $request->int_var['amount'] / ( 100 + $this->settings['tax'] ) * 100;
			$var['pg_sales_tax_amount']			= AECToolbox::correctAmount( ( $request->int_var['amount'] - $amount ), 2 );
			$var['pg_total_amount']		= AECToolbox::correctAmount( $amount, 2 );
		} else {
			$var['pg_total_amount']		= $request->int_var['amount'];
		}

		$var['pg_api_login_id']					= $this->settings['api_login_id'];
		$var['pg_consumerorderid']				= $request->invoice->invoice_number;

		$var['pg_return_url']					= AECToolbox::deadsureURL( 'index.php?option=com_acctexp&amp;task=payments_gatewaynotification' );
		$var['pg_return_method']				= 'AsyncPost';

		$var['pg_version_number']				= '1.0';
		$var['pg_utc_time']						= (int) gmdate('U');
		$var['pg_transaction_order_number']		= $request->invoice->id;

		$var['pg_ts_hash']	= $this->hmac( implode(" | ", array(	$var['pg_api_login_id'],
																	pg_transaction_type,
																	$var['pg_version_number'],
																	$var['pg_total_amount'],
																	$var['pg_utc_time'],
																	$var['pg_transaction_order_number']
																)
													), $this->settings['api_key'] ); 

		return $var;
	}

	function parseNotification( $post )
	{
		$response = array();
		$response['invoice'] = $post['invoice'];
		$response['amount_currency'] = $post['mc_currency'];

		switch ( $post['txn_type'] ) {
			case "web_accept":
			case "subscr_payment":
				$response['amount_paid'] = $post['mc_gross'];
				break;
			case "subscr_signup":
			case "subscr_cancel":
			case "subscr_modify":
				// Docs suggest mc_amount1 is set with signup, cancel or modify
				// Testing shows otherwise
				$response['amount_paid'] = isset($post['mc_amount1']) ? $post['mc_amount1'] : null;
			break;
			case "subscr_failed":
			case "subscr_eot":
				// May create a problem somewhere donw the line, but NULL
				// is a more representative value
			break;
			default:
			// Either a fraud attempt, or PayPal has changed its API
			// TODO: Raise Error
			$response['amount_paid'] = null;
		}

		return $response;
	}

	function validateNotification( $response, $post, $invoice )
	{
		$path = '/cgi-bin/webscr';
		if ($this->settings['testmode']) {
			$ppurl = 'https://www.sandbox.payments_gateway.com' . $path;
		} else {
			$ppurl = 'https://www.payments_gateway.com' . $path;
		}

		$req = 'cmd=_notify-validate';

		if ( isset( $post['planparams'] ) ) {
			unset( $post['planparams'] );
		}

		foreach ( $post as $key => $value ) {
			$value = str_replace('\r\n', "QQLINEBREAKQQ", $value);

			$value = urlencode( stripslashes($value) );

			$value = str_replace( "QQLINEBREAKQQ", "\r\n", $value ); // linebreak fix

			$req .= "&$key=".$value;
		}

		$res = $this->transmitRequest( $ppurl, $path, $req );

		$response['fullresponse']['payments_gateway_verification'] = $res;

		$receiver_email	= null;
		$txn_type		= null;
		$payment_type	= null;
		$payment_status	= null;
		$reason_code	= null;
		$pending_reason	= null;

		$getposts = array( 'txn_type', 'receiver_email', 'payment_status', 'payment_type', 'reason_code', 'pending_reason' );

		foreach ( $getposts as $n ) {
			if ( isset( $post[$n] ) ) {
				$$n = $post[$n];
			} else {
				$$n = null;
			}
		}

		$response['valid'] = 0;

		if ( strcmp( $receiver_email, $this->settings['business'] ) != 0 && $this->settings['checkbusiness'] ) {
			$response['pending_reason'] = 'checkbusiness error';
		} elseif ( ( strcmp( $res, 'VERIFIED' ) == 0 ) || ( empty( $res ) && !empty( $this->settings['brokenipnmode'] ) ) ) {
			if ( empty( $res ) && !empty( $this->settings['brokenipnmode'] ) ) {
				$response['fullresponse']['payments_gateway_verification'] = "MANUAL_OVERRIDE";
			}

			$recurring = ( $txn_type == 'subscr_payment' ) || ( $txn_type == 'recurring_payment' );

			// Process payment: Paypal Subscription & Buy Now
			if ( ( $txn_type == 'web_accept' ) || $recurring ) {

				if ( ( strcmp( $payment_type, 'instant' ) == 0 ) && ( strcmp( $payment_status, 'Pending' ) == 0 ) ) {
					$response['pending_reason'] = $post['pending_reason'];
				} elseif ( strcmp( $payment_type, 'instant' ) == 0 && strcmp( $payment_status, 'Completed' ) == 0 ) {
					$response['valid']			= 1;
				} elseif ( strcmp( $payment_type, 'echeck' ) == 0 && strcmp( $payment_status, 'Pending' ) == 0 ) {
					if ( $this->settings['acceptpendingecheck'] ) {
						if ( is_object( $invoice ) ) {
							$invoice->addParams( array( 'acceptedpendingecheck' => 1 ) );
							$invoice->storeload();
						}

						$response['valid']			= 1;
					} else {
						$response['pending']		= 1;
						$response['pending_reason'] = 'echeck';
					}
				} elseif ( strcmp( $payment_type, 'echeck' ) == 0 && strcmp( $payment_status, 'Completed' ) == 0 ) {
					$response['valid']		= 1;

					if ( is_object( $invoice ) ) {
						if ( isset( $invoice->params['acceptedpendingecheck'] ) ) {
							$response['valid']		= 0;
							$response['duplicate']	= 1;
						}
					}
				}
			} elseif ( strcmp( $txn_type, 'subscr_signup' ) == 0 ) {
				$response['pending']			= 1;
				$response['pending_reason']	 = 'signup';
			} elseif ( ( strcmp( $txn_type, 'paymentreview' ) == 0 ) || ( strcmp( $pending_reason, 'paymentreview' ) == 0 ) ) {
				$response['pending']			= 1;
				$response['pending_reason']	 = 'paymentreview';
			} elseif ( strcmp( $pending_reason, 'intl' ) == 0 ) {
				$response['pending']			= 1;
				$response['pending_reason']	 	= 'no auto-accept';
				$response['explanation']		= 'Configure your PayPal Account to automatically accept incoming payments.';
			} elseif ( strcmp( $txn_type, 'subscr_eot' ) == 0 ) {
				$response['eot']				= 1;
			} elseif ( strcmp( $txn_type, 'subscr_failed' ) == 0 ) {
				$response['null']				= 1;
				$response['explanation']		= 'Subscription Payment Failed';
			} elseif ( strcmp( $txn_type, 'subscr_cancel' ) == 0 ) {
				$response['cancel']				= 1;
			} elseif ( strcmp( $reason_code, 'refund' ) == 0 ) {
				$response['delete']				= 1;
			} elseif ( strcmp( $payment_status, 'Reversed' ) == 0 ) {
				$response['chargeback']			= 1;
			}
		} else {
			$response['pending_reason']			= 'error: ' . $res;
		}

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
