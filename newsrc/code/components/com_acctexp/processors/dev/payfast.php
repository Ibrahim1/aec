<?php
/**
 * @version $Id: payfast.php
 * @package AEC - Account Control Expiration - Membership Manager
 * @subpackage Processors - PayFast Buy Now
 * @copyright 2011 Copyright (C) R Botha
 * @author Riekie Botha <riekie@jfundi.com> 
 * @license GNU/GPL v.2 http://www.gnu.org/licenses/old-licenses/gpl-2.0.html or, at your option, any later version
 */
// Dont allow direct linking
( defined('_JEXEC') || defined( '_VALID_MOS' ) ) or die( 'Direct Access to this location is not allowed.' );

define( 'PAYFAST_SERVER', 'TEST' );

// Messages
	// Error
define( 'PF_ERR_BAD_SOURCE_IP', 'Bad source IP address' );
define( 'PF_ERR_CONNECT_FAILED', 'Failed to connect to PayFast' );
define( 'PF_ERR_BAD_ACCESS', 'Bad access of page' );
define( 'PF_ERR_INVALID_SIGNATURE', 'Security signature mismatch' );
define( 'PF_ERR_CURL_ERROR', 'An error occurred executing cURL' );
define( 'PF_ERR_INVALID_DATA', 'The data received is invalid' );
define( 'PF_ERR_UKNOWN', 'Unkown error occurred' );
define( 'PF_ERR_ORDER_ALREADY_PROCESSED', 'Order has already been processed' );
define( 'PF_ERR_INVOICE_DATA', 'Invoice data invalid' );

	// General
define( 'PF_MSG_OK', 'Payment was successful' );
define( 'PF_MSG_FAILED', 'Payment has failed' );
define( 'PF_MSG_PENDING',
	'The payment is pending. Please note, you will receive another Instant'.
	' Transaction Notification when the payment status changes to'.
	' "Completed", or "Failed"' );

class processor_payfast extends POSTprocessor
{	

	function info()
	{					
		$info = array();
		$info['name']					= 'payfast';
		$info['longname']				= JText::_('CFG_PAYFAST_LONGNAME');
		$info['statement']				= JText::_('CFG_PAYFAST_STATEMENT');
		$info['description']			= JText::_('CFG_PAYFAST_DESCRIPTION');
		$info['currencies']				= 'ZAR';
		$info['languages']				= AECToolbox::getISO3166_1a2_codes();
		$info['cc_list']				= 'visa,mastercard';
		$info['recurring']				= 0;
		$info['notify_trail_thanks']	= false;

		return $info;
	}

	function settings()
	{
	   //default_value

		$settings = array();
		$settings['testmode']		= 1;
		$settings['notification_type'] = 'ITN';
		$settings['merchant_id']	= '';		
		$settings['merchant_key']	= '';
		$settings['merchant_email_confirmation'] = 0;
		$settings['merchant_email'] = '';
		$settings['pdt_key']		= '';		
		$settings['currency']		= 'ZAR';	
		$settings['item_name']		= sprintf( JText::_('CFG_PROCESSOR_ITEM_NAME_DEFAULT'), '[[cms_live_site]]', '[[user_name]]', '[[user_username]]' );							

		for ( $i=1; $i<6; $i++ ) {
			$settings['custom_str'.$i]	= "";
			$settings['custom_int'.$i]	= "";
		}
  
		return $settings;
	}

	function backend_settings()
	{	
		$settings = array();

		$settings['testmode']						= array( 'list_yesno' );
		$settings['notification_type']				= array('list');
		$settings['merchant_id']					= array( 'inputC' );	
		$settings['merchant_key']					= array( 'inputC' );
		$settings['merchant_email_confirmation']	= array( 'list_yesno' );
		$settings['merchant_email']					= array( 'inputC' );

		$settings['pdt_key']		= array( 'inputD' );
		$settings['currency']		= array( 'list_currency' );		
		$settings['item_name']		= array( 'inputE' );
		$settings['item_desc']		= array( 'inputE' );

		for ( $i=1; $i<6; $i++ ) {
			$settings['custom_int'.$i]	= array( 'inputC' );
		}

		for ( $i=1; $i<6; $i++ ) {
			$settings['custom_str'.$i]	= array( 'inputC' );
		}

		$options = array();			
		$options[]	= JHTML::_('select.option', 'ITN', 'Instant Transaction Notification' );
		$options[]	= JHTML::_('select.option', 'PDT', 'Payment Data Transfer' );		

		$settings['lists']['notification_type'] = JHTML::_( 'select.genericlist', $options, 'notification_type', 'size="2"', 'value', 'text', $this->settings['notification_type'] );

		$settings = AECToolbox::rewriteEngineInfo( null, $settings );

		return $settings;
	}

	function createGatewayLink( $request )
	{  
		if ( $this->settings['testmode'] ) {
			$var['post_url']	= 'https://sandbox.payfast.co.za/eng/process';
		} else {
			$var['post_url']	= 'https://www.payfast.co.za/eng/process';
		}	

		// Receiver details
		$var['merchant_id']		= $this->settings['merchant_id'];
		$var['merchant_key']	= $this->settings['merchant_key'];
		$var['return_url']		= str_replace( '&amp;', '&' , $request->int_var['return_url'] );		
		$var['cancel_url']		= AECToolbox::deadsureURL( 'index.php?option=com_acctexp&task=cancel', false, true );	
		$var['notify_url']		= AECToolbox::deadsureURL( 'index.php?option=com_acctexp&task=payfastnotification', false, true );

		$name = $request->metaUser->explodeName();

		// Payer details
		$var['name_first']		= $name['first'];
		$var['name_last']		= $name['last'];					
		$var['email_address']	= $request->metaUser->cmsUser->email;
		
		// Transaction details		
		$var['m_payment_id']	= $request->invoice->invoice_number;
		$var['amount']			= $request->int_var['amount'];
		$var['item_name']		= AECToolbox::rewriteEngineRQ( $this->settings['item_name'], $request );
		$var['item_description']	= AECToolbox::rewriteEngineRQ( $this->settings['item_desc'], $request );

		// Custom variables
		for ( $i=1; $i<6; $i++ ) {
			if ( !empty( $this->settings['custom_str'.$i] ) ) {
				$var['custom_str'.$i] = $this->settings['custom_str'.$i];
			}

			if ( !empty( $this->settings['custom_int'.$i] ) ) {
				$var['custom_int'.$i] = $this->settings['custom_int'.$i];
			}
		}

		// Transaction options
		if ( $this->settings['merchant_email_confirmation'] ) {
			$var['email_confirmation'] = $this->settings['merchant_email_confirmation'];

			if ( $var['email_confirmation'] ) {
				$var['confirmation_address'] = $this->settings['merchant_email'];
			}
		}		 
		
		// Security				
		$var['signature'] = $this->getSignature( $var );

		return $var;
	}

	function parseNotification( $post )
	{
		$response = array();
		$response['invoice'] = $post['m_payment_id'];
		$response['amount_paid'] = $post['amount_gross'];

	   return $response;
   }

	function validateNotification( $response, $post, $invoice )
	{
		$response['valid'] = 0;

		if ( !$this->pfValidIP( $_SERVER['REMOTE_ADDR'] ) ) {
			$response['pending_reason'] = PF_ERR_BAD_SOURCE_IP;					

			return $response; 
		}

		if ( $this->getSignature( $post ) != $post['signature'] ) {
			$response['pending_reason'] = PF_ERR_INVALID_SIGNATURE;					

			return $response; 
		}

		if ( !$this->pfValidate( 'sandbox.payfast.co.za', $response['param_string'] ) ) {
		  $response['pending_reason'] = PF_ERR_INVALID_DATA;

		  return $response;
		}

		if ( $post['payment_status'] == 'COMPLETE' ) {
			$response['valid'] = 1;
		}

		return $response;
   }

	function getSignature( $data )
	{
		if ( isset( $data['signature'] ) ) {
			unset( $data['signature'] );
		}

		return md5( XMLprocessor::arrayToNVP( $data ) );
	}

	function pfValidIP( $sourceIP )
	{
		$validHosts = array( 'www.payfast.co.za', 'sandbox.payfast.co.za', 'w1w.payfast.co.za', 'w2w.payfast.co.za' );

		$validIps = array();
		foreach ( $validHosts as $pfHostname ) {
			$ips = gethostbynamel( $pfHostname );
	
			if ( $ips !== false ) {
				$validIps = array_merge( $validIps, $ips );
			}
		}
	
		$validIps = array_unique( $validIps );
	
		return in_array( $sourceIP, $validIps );
	}

	function pfValidate( $data )
	{
		$path = '/eng/query/validate';
		if ( $this->settings['testmode'] ) {
			$url = 'https://sandbox.payfast.co.za' . $path;
		} else {
			$url = 'https://www.payfast.co.za' . $path;
		}

		if ( isset( $post['planparams'] ) ) {
			unset( $post['planparams'] );
		}

		$extra_header = array( 'User-Agent' => 'Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)' );

		$res = $this->transmitRequest( $url, $path, XMLprocessor::arrayToNVP( $data ) );

		$lines = explode( "\r\n", $res );
		$verifyResult = trim( $lines[0] );

		return ( strcasecmp( $verifyResult, 'VALID' ) == 0 );

	}

	function pfValidData( $pfHost = 'www.payfast.co.za', $pfParamString = '', $pfProxy = null )
	{
		$this->pflog( 'Host = '. $pfHost );
		$this->pflog( 'Params = '. $pfParamString );
	
		// Use cURL (if available)
		if( function_exists( 'curl_init' ) )
		{
			  // Variable initialization
			  $url = 'https://'. $pfHost .'/eng/query/validate';
	
			  // Create default cURL object
			  $ch = curl_init();
		
			  // Set cURL options - Use curl_setopt for freater PHP compatibility
			  // Base settings
			  curl_setopt( $ch, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)' );
			  curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );		// Return output as string rather than outputting it
			  curl_setopt( $ch, CURLOPT_HEADER, false );				 // Don't include header in output
			  curl_setopt( $ch, CURLOPT_SSL_VERIFYHOST, true );
			  curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false );
			  
			  // Standard settings
			  curl_setopt( $ch, CURLOPT_URL, $url );
			  curl_setopt( $ch, CURLOPT_POST, true );
			  curl_setopt( $ch, CURLOPT_POSTFIELDS, $pfParamString );
			  curl_setopt( $ch, CURLOPT_TIMEOUT, PF_TIMEOUT );
			  if( !empty( $pfProxy ) )
					curl_setopt( $ch, CURLOPT_PROXY, $proxy );
		
			  // Execute CURL
			  $response = curl_exec( $ch );
			  curl_close( $ch );
		}
		// Use fsockopen
		else
		{
			  // Variable initialization
			  $header = '';
			  $res = '';
			  $headerDone = false;
			   
			  // Construct Header
			  $header = "POST /eng/query/validate HTTP/1.0\r\n";
			 	$header .= "Host: ". $pfHost ."\r\n";
			  $header .= "User-Agent: ". PF_USER_AGENT ."\r\n";
			  $header .= "Content-Type: application/x-www-form-urlencoded\r\n";
			  $header .= "Content-Length: " . strlen( $pfParamString ) . "\r\n\r\n";
	 
			  // Connect to server
			  $socket = fsockopen( 'ssl://'. $pfHost, 443, $errno, $errstr, PF_TIMEOUT );
	 
			  // Send command to server
			  fputs( $socket, $header . $pfParamString );
	 
			  // Read the response from the server
			  while( !feof( $socket ) )
			  {
					$line = fgets( $socket, 1024 );
	 
					// Check if we are finished reading the header yet
					if( strcmp( $line, "\r\n" ) == 0 )
					{
						// read the header
						$headerDone = true;
					}
					// If header has been processed
					else if( $headerDone )
					{
						// Read the main response
						$response .= $line;
					}
			  }
			  
		}
	
		$this->pflog( "Response:\n". print_r( $response, true ) );
	
		// Interpret Response
		$lines = explode( "\r\n", $response );
		$verifyResult = trim( $lines[0] );
	
		if( strcasecmp( $verifyResult, 'VALID' ) == 0 )
			  return( true );
		else
			  return( false );
	}

}
?>
