<?php


// Dont allow direct linking
( defined('_JEXEC') || defined( '_VALID_MOS' ) ) or die( 'Direct Access to this location is not allowed.' );

class processor_realexrd extends POSTprocessor
{
	function info()
	{
		$info = array();
		$info['name']			= 'realexrd';
		$info['longname']		= _AEC_PROC_INFO_RXRD_LNAME;
		$info['statement']		= _AEC_PROC_INFO_RXRD_STMNT;
		$info['description']	= _DESCRIPTION_REALEX;
		$info['currencies']		= 'EUR,USD,GBP,AUD,CAD,JPY,NZD,CHF,HKD,SGD,SEK,DKK,PLN,NOK,HUF,CZK,MXN,ILS,BRL,MYR,PHP,TWD,THB,ZAR';
		$info['languages']		= AECToolbox::getISO3166_1a2_codes();
		$info['cc_list']		= 'visa,mastercard,laser';
		$info['recurring']		= 0;

		return $info;
	}

	function settings()
	{
		$settings = array();
		$settings['merchantid']		= 'yourmerchantid';
		$settings['account']		= 'youraccount';
		$settings['secret']		= 'yoursecret';
		$settings['testmode']		= 1;
		$settings['currency']		= 'EUR';
		
		return $settings;
	}

	function backend_settings()
	{
		$settings = array();

    $settings['merchantid']		= array( 'inputC' );
		$settings['account']		= array( 'inputC' );
		$settings['secret']		= array( 'inputC' );
		$settings['testmode']				= array( 'list_yesno' );
		$settings['currency']				= array( 'list_currency' );
		//$settings['generic_buttons']	= array( 'list_yesno' );

	//	$settings['image_url']				= array( 'inputE' );


		
/*
		$settings['business']				= array( 'inputC' );

		$settings['brokenipnmode']			= array( 'list_yesno' );
		$settings['invoice_tax']			= array( 'list_yesno' );
		$settings['tax']					= array( 'inputA' );

		$settings['checkbusiness']			= array( 'list_yesno' );
		$settings['acceptpendingecheck']	= array( 'list_yesno' );
		$settings['lc']						= array( 'list_language' );
		$settings['no_shipping']			= array( 'list_yesno' );
		$settings['altipnurl']				= array( 'inputC' );
		$settings['item_name']				= array( 'inputE' );
		$settings['item_number']			= array( 'inputE' );
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
*/
		$settings = AECToolbox::rewriteEngineInfo( null, $settings );

		return $settings;
	}

	function createGatewayLink( $request )
	{
		if ( $this->settings['testmode'] ) {
			$var['post_url']	= 'https://epage.payandshop.com/epage.cgi';
		} else {
			$var['post_url']	= 'https://epage.payandshop.com/epage.cgi';
		}

		//$var['cmd']				= '_xclick';

    
		/*if ( !empty( $this->settings['invoice_tax'] ) ) {
			foreach ( $request->items->tax as $tax ) {
				$tax += $tax['cost'];
			}

			$var['tax']			= $tax;

			$var['amount']		= $request->items->total->cost['amount'];
		} elseif ( !empty( $this->settings['tax'] ) && $this->settings['tax'] > 0 ) {
			$tax				= $request->int_var['amount'] / ( 100 + $this->settings['tax'] ) * 100;
			$var['tax']			= round( ( $request->int_var['amount'] - $tax ), 2 );
			$var['amount']		= round( $tax, 2 );
		} else {
			$var['amount']		= $request->int_var['amount'];
		}
$settings['merchantid']		= array( 'inputC' );
		$settings['account']		= array( 'inputC' );
		$settings['secret']		= array( 'inputC' );
		$settings['testmode']				= array( 'list_yesno' );
		$settings['currency']				= array( 'list_currency' );


		
    */

			//$var['AMOUNT']		= $request->items->total->cost['amount'];
			
		//The code below is used to create the timestamp format required by Realex Payments
		$timestamp = strftime("%Y%m%d%H%M%S");
		mt_srand((double)microtime()*1000000);


		$orderid = $timestamp.mt_rand(1, 999);
		$amt = round(100*$request->items->total->cost['amount']);
    $curr = $this->settings['currency']	;
		



		/*-----------------------------------------------
		Below is the code for creating the digital signature using the MD5 algorithm provided
		by PHP. you can use the SHA1 algorithm alternatively. 
		*/
		$mid=$this->settings['merchantid'];
		$ss= $this->settings['secret'];
		
		$tmp = "$timestamp.$mid.$orderid.$amt.$curr";
		$md5hash = md5($tmp);
		$tmp = "$md5hash.$ss";
		$md5hash = md5($tmp);		
		
		$var['MERCHANT_ID'] = $this->settings['merchantid'];
		$var['ORDER_ID'] = $orderid;
		$var['ACCOUNT'] = $this->settings['account'];
		$var['CURRENCY'] = $curr;
		$var['AMOUNT']= $amt;
		$var['TIMESTAMP'] = $timestamp;
		$var['MD5HASH'] = $md5hash;
		$var['AUTO_SETTLE_FLAG'] = 1;
		
		

		//$var['business']		= $this->settings['business'];
		//$var['invoice']			= $request->invoice->invoice_number;
		//$var['cancel_return']	= AECToolbox::deadsureURL( 'index.php?option=com_acctexp&amp;task=cancel' );
/*
		if ( strpos( $this->settings['altipnurl'], 'http://' ) === 0 ) {
			$var['notify_url']	= $this->settings['altipnurl'] . 'index.php?option=com_acctexp&amp;task=paypalnotification';
		} else {
			$var['notify_url']	= AECToolbox::deadsureURL( 'index.php?option=com_acctexp&amp;task=paypalnotification' );
		}

		$var['item_number']		= AECToolbox::rewriteEngineRQ( $this->settings['item_number'], $request );
		$var['item_name']		= AECToolbox::rewriteEngineRQ( $this->settings['item_name'], $request );

		$var['no_shipping']		= $this->settings['no_shipping'];
		$var['no_note']			= '1';
		$var['rm']				= '2';

		$var['return']			= $request->int_var['return_url'];
		$var['currency_code']	= $this->settings['currency'];
		$var['lc']				= $this->settings['lc'];

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
    */
		return $var;
	}

	function parseNotification( $post )
	{
		$db = &JFactory::getDBO();

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
			$ppurl = 'https://www.sandbox.paypal.com' . $path;
		} else {
			$ppurl = 'https://www.paypal.com' . $path;
		}

		$req = 'cmd=_notify-validate';

		if ( isset( $post['planparams'] ) ) {
			unset( $post['planparams'] );
		}

		foreach ( $post as $key => $value ) {
			$value = urlencode( stripslashes( $value ) );
			$req .= "&$key=$value";
		}

		$res = $this->transmitRequest( $ppurl, $path, $req );

		$response['fullresponse']['paypal_verification'] = $res;

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
