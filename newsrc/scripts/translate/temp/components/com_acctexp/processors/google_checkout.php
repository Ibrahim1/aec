<?php
/**
 * @version $Id: google_checkout.php
 * @package AEC - Account Control Expiration - Membership Manager
 * @subpackage Processors - Google Checkout
 * @copyright 2010 Copyright (C) David Deutsch
 * @author David Deutsch <skore@skore.de> & Team AEC - http://www.valanx.org
 * @license GNU/GPL v.2 http://www.gnu.org/licenses/old-licenses/gpl-2.0.html or, at your option, any later version
 */

// Dont allow direct linking
( defined('_JEXEC') || defined( '_VALID_MOS' ) ) or die( 'Direct Access to this location is not allowed.' );

class processor_google_checkout extends XMLprocessor
{
	function info()
	{
		$info = array();
		$info['name']					= 'google_checkout';
		$info['longname']				= JText::_('CFG_GOOGLE_CHECKOUT_LONGNAME');
		$info['statement']				= JText::_('CFG_GOOGLE_CHECKOUT_STATEMENT');
		$info['description']			= JText::_('CFG_GOOGLE_CHECKOUT_DESCRIPTION');
		$info['currencies']				= "USD,GBP"; // only USD and GBP are accepted by Google Checkout
		$info['cc_list']				= "visa,mastercard,discover,americanexpress,echeck,jcb,dinersclub";
		$info['notify_trail_thanks']	= true;

		return $info;
	}

	function settings()
	{
		$settings = array();
		$settings['merchant_id']		= '--';
		$settings['merchant_key']		= '--';
		$settings['testmode']			= true;
		$settings['currency']			= 'USD';
		$settings['item_name']			= sprintf( JText::_('CFG_PROCESSOR_ITEM_NAME_DEFAULT'), '[[cms_live_site]]', '[[user_name]]', '[[user_username]]' );
		$settings['customparams']		= '';

		return $settings;
	}

	function backend_settings()
	{
		$settings = array();
		$settings['testmode']			= array( 'list_yesno' );
		$settings['merchant_id'] 		= array( 'inputC' );
		$settings['merchant_key']		= array( 'inputC' );
		$settings['currency']			= array( 'list_currency' );
		$settings['item_name']			= array( 'inputE' );
		$settings['customparams']		= array( 'inputD' );

		$settings = AECToolbox::rewriteEngineInfo( null, $settings );

		return $settings;
	}
	
	function checkoutform( $request )
	{
		$var = array();
		return $var;
	}
	
	function checkoutAction( $request, $InvoiceFactory=null )
	{
		require_once('google_checkout/library/googlecart.php');
		require_once('google_checkout/library/googleitem.php');

		if ( $this->settings['testmode'] ) {
			$server_type = "sandbox";
		} else {
			$server_type = "Production";
		}

		$item_name			= $request->plan->name;
		$item_description 	= AECToolbox::rewriteEngineRQ( $this->settings['item_name'], $request );
		$merchant_id		= $this->settings['merchant_id'];
		$merchant_key		= $this->settings['merchant_key'];		
		$currency			= $this->settings['currency'];
		$amount				= $request->int_var['amount'];
      	$qty				= 1;

		$cart	= new GoogleCart( $merchant_id, $merchant_key, $server_type, $currency );
		$item_1 = new GoogleItem( $item_name, $item_description, $qty, $amount );

		$cart->AddItem( $item_1 );

		$cart->SetContinueShoppingUrl( $request->int_var['return_url'] );

	    $cart->SetMerchantPrivateData( new MerchantPrivateData(array("invoice" => $request->invoice->invoice_number )) );

		// Display the Google Checkout button instead of the normal checkout button.
		$return = '<p style="float:right;text-align:right;">' . $cart->CheckoutButtonCode("SMALL") . '</p>';

		return $return;
	}

	function createRequestXML( $request )
	{
		return "";
	}
	
	function transmitRequestXML( $xml, $request )
	{
		$response 				= array();
		$response['valid'] 		= true;

		return $response;	
	}

	function parseNotification( $post )
	{
		require_once('google_checkout/library/googlerequest.php');
		
		$response			= array();
		$response['valid'] = false;

		$merchant_id		= $this->settings['merchant_id'];
		$merchant_key		= $this->settings['merchant_key'];
		$currency			= $this->settings['currency'];

		$serial_number		= $_POST["serial-number"];

		if ( $this->settings['testmode'] ) {
			$server_type = "sandbox";
		} else {
			$server_type = "Production";
		}

		$googleRequest = new GoogleRequest( $merchant_id, $merchant_key, $server_type, $currency, $serial_number );
		
		$googleRequest->SendAcknowledgementRequest();

		list( $res, $xml_response ) = $googleRequest->SendHistoryRequest();

		if ( $res != 200 ) {
			return $response;
		}

		// Quick way of filtering out notifications
		$filter = array( 'order-state-change-notification', 'charge-amount-notification', 'risk-information-notification' );

		foreach ( $filter as $f ) {
			if ( strpos( $xml_response, $f ) !== false ) {
				$response['null']			= 1;
				$response['explanation']	= 'An additional notification for order ' . $serial_number . ' has arrived.';

				switch ( $f ) {
					case 'order-state-change-notification':
						$prev_fullf = $this->XMLsubstring_tag( $xml_response, 'previous-fulfillment-order-state' );
						$curr_fullf = $this->XMLsubstring_tag( $xml_response, 'new-fulfillment-order-state' );

						$prev_finan = $this->XMLsubstring_tag( $xml_response, 'previous-financial-order-state' );
						$curr_finan = $this->XMLsubstring_tag( $xml_response, 'new-financial-order-state' );

						if ( $prev_fullf != $curr_fullf ) {
							$response['explanation'] .= ' Fullfillment State changed from ' . $prev_fullf . ' to ' . $curr_fullf . '.';
						}

						if ( $prev_finan != $curr_finan ) {
							$response['explanation'] .= ' Financial State changed from ' . $prev_finan . ' to ' . $curr_finan . '.';
						}

						break;
					case 'charge-amount-notification':
						$response['explanation'] .= ' The amount has been charged.';

						break;
					default:
						$response['explanation'] .= $f;

						break;
				}

				return $response;
			}
		}

		$response['valid']					= true;
		$response['invoice']				= $this->XMLsubstring_tag( $xml_response, 'invoice' );
		$response['google-order-number']	= $this->XMLsubstring_tag( $xml_response, 'google-order-number' );
		$response['serial_number']			= $serial_number;
		$response['server_type']			= $server_type;

		return $response;
	}

	function validateNotification( $response, $post, $invoice )
	{
		if ( $response['valid'] ) {
			$googleRequest = new GoogleRequest(	$this->settings['merchant_id'],
											$this->settings['merchant_key'],
											$response['server_type'],
											$this->settings['currency'],
											$response['serial_number']
											);

			$order_number = $response['google-order-number'];

			unset( $response['google-order-number'] );
			unset( $response['server_type'] );
			unset( $response['serial_number'] );

			list( $res, $xml_response ) = $googleRequest->SendDeliverOrder( $order_number );

			if ( $res != 200 ) {
				$response['valid']			= false;
				$response['pending_reason']	= 'Sending Deliver Order failed';

				return $response;
			}

			list( $res, $xml_response ) = $googleRequest->SendChargeOrder( $order_number );

			if ( $res != 200 ) {
				$response['valid']			= false;
				$response['pending_reason']	= 'Sending Charge Order failed';

				return $response;
			}
		} else {
			unset( $response['google-order-number'] );
			unset( $response['server_type'] );
			unset( $response['serial_number'] );
		}

		return $response;
	}

}
?>
