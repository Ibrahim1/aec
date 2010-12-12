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

define('_CFG_GOOGLE_CHECKOUT_MERCHANT_ID_NAME','Merchant ID');
define('_PP_GENERAL_GOOGLE_CHECKOUT_MERCHANT_ID_NAME','Your Google Merchant ID');
define('_CFG_GOOGLE_CHECKOUT_MERCHANT_KEY_NAME','Merchant Key');
define('_PP_GENERAL_GOOGLE_CHECKOUT_MERCHANT_KEY_NAME','Your Google Merchant Key');
define('_CFG_GOOGLE_CHECKOUT_NOTE_RETURN','David - pls arrange for the correct checkout headline/comment...');

class processor_google_checkout extends XMLprocessor
{
	function info()
	{
		$info = array();
		$info['name']					= 'google_checkout';
		$info['longname']				= _CFG_GOOGLE_CHECKOUT_LONGNAME;
		$info['statement']				= _CFG_GOOGLE_CHECKOUT_STATEMENT;
		$info['description']			= _CFG_GOOGLE_CHECKOUT_DESCRIPTION;
		$info['currencies']				= "usd,gbp"; // only USD and GBP are accepted by Google Checkout
		$info['cc_list']				= "visa,mastercard,discover,americanexpress,echeck,jcb,dinersclub";
		$info['notify_trail_thanks']	= true;

		return $info;
	}

	function settings()
	{
		$settings = array();
		$settings['merchant_id']		= '477250470079899';
		$settings['merchant_key']		= '5daHScuvdmf5awZFzAkc-g';
		$settings['testmode']			= true;
		$settings['currency']			= 'USD';
		$settings['item_name']			= sprintf( _CFG_PROCESSOR_ITEM_NAME_DEFAULT, '[[cms_live_site]]', '[[user_name]]', '[[user_username]]' );
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
		//require_once('google_checkout/library/googleshipping.php'); NYI
		//require_once('google_checkout/library/googletax.php'); NYI
		
		if ( $this->settings['testmode'] ) {
			$server_type = "sandbox";
		} else {
			$server_type = "Production";
		}
		
		$item_name			= $request->plan->name;
		$item_description 	= AECToolbox::rewriteEngineRQ( $this->settings['item_name'], $request );
		$merchantReturnURL	= AECToolbox::deadsureURL( "index.php?option=com_acctexp&task=google_checkoutnotification" );
		$merchant_id		= $this->settings['merchant_id'];
		$merchant_key		= $this->settings['merchant_key'];		
		$currency			= $this->settings['currency'];
		$amount				= $request->int_var['amount'];
      	$qty				= 1;
		
      	$cart	= new GoogleCart( $merchant_id, $merchant_key, $server_type, $currency );
      	$item_1 = new GoogleItem( $item_name, $item_description, $qty, $amount ); 
		
		$cart->AddItem($item_1);
            
		$cart->SetContinueShoppingUrl($merchantReturnURL);	
		
	    $cart->SetMerchantPrivateData( new MerchantPrivateData(array("invoice" => $request->invoice->invoice_number )));
			
//aecdebug($request);
			
		// Display the Google Checkout button instead of the normal checkout button.
		$return = '<p style="float:right;text-align:right;">' . $cart->CheckoutButtonCode("SMALL") . '</p>';
		
		return $return;
	}	
	
	function createRequestXML( $request )
	{
		// Nothing to do here.
		return "";
	}	
	
	function transmitRequestXML( $xml, $request )
	{
		// Nothing to do here until further notice...
		$response 				= array();
		$response['valid'] 		= true;	
		return $response;	
	}
	
	function parseNotification( $post )
	{
aecdebug($_REQUEST);
//comment: aecdebug($post) returns empty []
		require_once('google_checkout/library/googleresponse.php');
		require_once('google_checkout/library/googlemerchantcalculations.php');
		require_once('google_checkout/library/googleresult.php');
		require_once('google_checkout/library/googlerequest.php');

		$merchant_id		= $this->settings['merchant_id'];
		$merchant_key		= $this->settings['merchant_key'];		
		$currency			= $this->settings['currency'];

		if ( $this->settings['testmode'] ) {
			$server_type = "sandbox";
		} else {
			$server_type = "Production";
		}

  $Gresponse = new GoogleResponse($merchant_id, $merchant_key);

  $Grequest = new GoogleRequest($merchant_id, $merchant_key, $server_type, $currency);

  // Retrieve the XML sent in the HTTP POST request to the ResponseHandler
  $xml_response = isset($HTTP_RAW_POST_DATA)?
                    $HTTP_RAW_POST_DATA:file_get_contents("php://input");
  if (get_magic_quotes_gpc()) {
    $xml_response = stripslashes($xml_response);
  }
  list($root, $data) = $Gresponse->GetParsedXML($xml_response);
  $Gresponse->SetMerchantAuthentication($merchant_id, $merchant_key);

/*
  $status = $Gresponse->HttpAuthentication();
  if(! $status) {
    die('authentication failed');
  }
  */
aecdebug($xml_response);
  
aecdebug($Gresponse->GetParsedXML($xml_response));

		$response				= array();
		$response['valid']		= true;		
		$response['invoice'] 	= "ERROR"; // dummy test value forcing invoice error message...
		return $response;
	}
	
	function validateNotification( $response, $post, $invoice )
	{
		// Nothing to do here until further notice...
		$response				= array();
		$response['valid']		= true;
		return $response;
	}
}
?>
