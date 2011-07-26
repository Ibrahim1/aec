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
define( 'USER_AGENT', 'Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)' );
    // User Agent for cURL

// Messages
    // Error
define( 'PF_ERR_AMOUNT_MISMATCH', 'Amount mismatch' );
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
    
define( 'PF_EPSILON', 0.01 );
define( 'PF_DEBUG', true );

class processor_payfast extends POSTprocessor
{	

	function info()
	{                
		$info = array();
		$info['name']			= 'payfast';
		$info['longname']		= JText::_('CFG_PAYFAST_LONGNAME');
		$info['statement']		= JText::_('CFG_PAYFAST_STATEMENT');
		$info['description']		= JText::_('CFG_PAYFAST_DESCRIPTION');
		$info['currencies']		= 'ZAR';
		$info['languages']		= AECToolbox::getISO3166_1a2_codes();
		$info['cc_list']		= 'visa,mastercard';
		$info['recurring']		= 0;

		return $info;
	}

	function settings()
	{
	   //default_value

		$settings = array();
		$settings['testmode']		= 1;
		$settings['notification_type'] = 'ITN';
		$settings['merchant_id']		= '-';		
		$settings['merchant_key']	= '-';
		$settings['merchant_email_confirmation'] = 0;
		$settings['merchant_email'] = '';
		$settings['pdt_key'] = '-';		
		$settings['name_first']	= 'Test';
		$settings['name_last'] = 'User 01';
		$settings['email_address']	= '-';		
      $settings['currency']		= 'ZAR';	
		$settings['item_name']		= sprintf( JText::_('CFG_PROCESSOR_ITEM_NAME_DEFAULT'), '[[cms_live_site]]', '[[user_name]]', '[[user_username]]' );                		
		$settings['custom_str1']	= "";
		$settings['custom_str2']	= "";
		$settings['custom_str3']	= "";
		$settings['custom_str4']	= "";
		$settings['custom_str5']	= "";
		$settings['custom_int1']	= "";
		$settings['custom_int2']	= "";
		$settings['custom_int3']	= "";
		$settings['custom_int4']	= "";
		$settings['custom_int5']	= "";
               
		return $settings;
	}

	function backend_settings()
	{	
		$options = array();			
		$options[]	= JHTML::_('select.option', 'ITN', 'Instant Transaction Notification' );
		$options[]	= JHTML::_('select.option', 'PDT', 'Payment Data Transfer' );		

	   //$settings['variable'] = array("type", "name", "description");
		$settings = array();

		$settings['testmode']		= array( 'list_yesno' );
		$settings['notification_type'] = array('list');
		$settings['merchant_id']		= array( 'inputC' );	
		$settings['merchant_key']	= array( 'inputC' );
		$settings['merchant_email_confirmation'] = array( 'list_yesno' );
		$settings['merchant_email'] = array( 'inputC' );
      $settings['pdt_key'] = array( 'inputD' );
		$settings['name_first']	= array( 'inputC' );
		$settings['name_last'] = array( 'inputC' );
		$settings['email_address']	= array( 'inputC' );
		$settings['currency']		= array( 'list_currency' );		
		$settings['item_name']		= array( 'inputE' );
		$settings['custom_str1']	= array( 'inputC' );
		$settings['custom_str2']	= array( 'inputC' );
		$settings['custom_str3']	= array( 'inputC' );
		$settings['custom_str4']	= array( 'inputC' );
		$settings['custom_str5']	= array( 'inputC' );
		$settings['custom_int1']	= array( 'inputC' );
		$settings['custom_int2']	= array( 'inputC' );
		$settings['custom_int3']	= array( 'inputC' );
		$settings['custom_int4']	= array( 'inputC' );
		$settings['custom_int5']	= array( 'inputC' );


		$settings['lists']['notification_type'] = JHTML::_( 'select.genericlist', $options, 'notification_type',
		 'size="2"', 'value', 'text', $this->settings['notification_type'] );

		$settings = AECToolbox::rewriteEngineInfo( null, $settings );

		return $settings;
	}

   function replaceAmp($addr) {
      return str_replace ( '&amp;', '&' , $addr );
   }
	function createGatewayLink( $request )
	{  
	   $this->settings['amount']		= $request->int_var['amount'];                             
      /* set all the payfast variables from request and settings */
		//Receiver details
		$var['merchant_id'] = $this->settings['merchant_id'];
		$var['merchant_key'] = $this->settings['merchant_key'];
		$var['return_url']			= $this->replaceAmp($request->int_var['return_url']);		
		$var['cancel_url']	= $this->replaceAmp(AECToolbox::deadsureURL( 'index.php?option=com_acctexp&task=cancel' ));	
		$var['notify_url']	= $this->replaceAmp(AECToolbox::deadsureURL( 'index.php?option=com_acctexp&task=payfastnotification' ));
		
		//Payer details
		   //$request->metaUser->cmsUser->id;, $request->metaUser->cmsUser->username;
		 
		$var['name_first'] = $this->settings['name_first'];/*$request->metaUser->cmsUser->name;*/
		$var['name_last'] = $this->settings['name_last'];                
		$var['email_address'] = $this->settings['email_address'];/*$request->metaUser->cmsUser->email;*/
		
		//Transaction details		
		$var['m_payment_id']	= $request->invoice->invoice_number;
		$var['amount']		= $request->int_var['amount'];
	   $var['item_name'] = AECToolbox::rewriteEngineRQ( $this->settings['item_name'], $request );
      $var['item_description'] = AECToolbox::rewriteEngineRQ( $this->settings['item_name'], $request );
      if ( !empty( $this->settings['custom_str1'] )) 
         $var['custom_str1'] = $this->settings['custom_str1']; 
		if ( !empty( $this->settings['custom_str2'] ))
         $var['custom_str2'] = $this->settings['custom_str2'];
		if ( !empty( $this->settings['custom_str3'] ))
         $var['custom_str3'] = $this->settings['custom_str3'];
		if ( !empty( $this->settings['custom_str4'] )) $var['custom_str4'] = $this->settings['custom_str4'];
		if ( !empty( $this->settings['custom_str5'] )) $var['custom_str5'] = $this->settings['custom_str5'];
		if ( !empty( $this->settings['custom_int1'] ) && $this->settings['custom_int1'] > 0 )
         $var['custom_int1'] = $this->settings['custom_int1'];
		if ( !empty( $this->settings['custom_int2'] ) && $this->settings['custom_int2'] > 0 )
         $var['custom_int2'] = $this->settings['custom_int2'];
		if ( !empty( $this->settings['custom_int3'] ) && $this->settings['custom_int3'] > 0 )
         $var['custom_int3'] = $this->settings['custom_int3'];
		if ( !empty( $this->settings['custom_int4'] ) && $this->settings['custom_int4'] > 0 )
         $var['custom_int4'] = $this->settings['custom_int4'];
		if ( !empty( $this->settings['custom_int5'] ) && $this->settings['custom_int5'] > 0 )
         $var['custom_int5'] = $this->settings['custom_int5'];

      
		//Transaction options
		if ($this->settings['merchant_email_confirmation']) {
			$var['email_confirmation'] = $this->settings['merchant_email_confirmation'];
	      if ( $var['email_confirmation']) 
	          $var['confirmation_address'] = $this->settings['merchant_email'];
	   }       
		
		//Security				
		$this->pfValidSignature( $var, $pfParamString, $signature );      
      $this->pflog("paramstring: ".$pfParamString);
      $this->pflog("my signature: ".$signature);
                
		$var['signature'] = $signature;
	
		if ( $this->settings['testmode'] ) { //https?
			$var['post_url']	= 'https://sandbox.payfast.co.za/eng/process';//'sandbox.payfast.co.za';
		} else {
			$var['post_url']	= 'https://www.payfast.co.za/eng/process';//'www.payfast.co.za';
		}	

		return $var;
	}

   
   //Receive the data posted by PayFast
   function parseNotification( $post ) //$post = full response from PF
   {
      $response = array();      
      $pfData = $this->pfGetData($post);
      if( $pfData === false )
      {
        $response['error'] = 1;
        $response['errormsg'] = PF_ERR_BAD_ACCESS;        
        return $response;
      }
      
      if( !$this->pfValidSignature( $pfData, $pfParamString ) )
      {
         $response['error'] = 1;
			$response['errormsg'] = PF_ERR_INVALID_SIGNATURE;		       
      }
      else {         
		   $response['m_payment_id'] = $pfData['m_payment_id'];//transaction ID on receiver's system
		   $response['pf_payment_id'] = $pfData['pf_payment_id'];//transaction ID on PayFast
		   $response['payment_status'] = $pfData['payment_status'];
		   $response['item_name'] = $pfData['item_name'];
		   $response['item_description'] = $pfData['item_description'];
		   $response['amount_gross'] = $pfData['amount_gross']; //total amount paid by payer
		   $response['amount_fee'] = $pfData['amount_fee']; //total amount in fees deducted from amount
		   $response['amount_net'] = $pfData['amount_net']; //net amount credited to receiver's account
		   $response['custom_str1'] = $pfData['custom_str1'];
		   $response['custom_str2'] = $pfData['custom_str2'];
		   $response['custom_str3'] = $pfData['custom_str3'];
		   $response['custom_str4'] = $pfData['custom_str4'];
		   $response['custom_str5'] = $pfData['custom_str5'];
		   $response['custom_int1'] = $pfData['custom_int1'];
		   $response['custom_int2'] = $pfData['custom_int2'];
		   $response['custom_int3'] = $pfData['custom_int3'];
		   $response['custom_int4'] = $pfData['custom_int4'];
		   $response['custom_int5'] = $pfData['custom_int5'];
		   $response['name_first'] = $pfData['name_first'];
		   $response['name_last'] = $pfData['name_last'];
		   $response['email_address'] = $pfData['email_address'];
		   $response['merchant_id'] = $pfData['merchant_id'];
		    
	      $response['invoice'] = $pfData['m_payment_id'];		
		   $response['amount_paid'] = $pfData['amount_gross'];		
		   $response['param_string'] = $pfParamString;
		}   
					
	   return $response;
   }

   /*Perform security checks:
   * 1. Verify signature
   * 2. Verify the source IP Address
   * 3. Verify data received - post to https://www.payfast.co.za/eng/query/validate (VALID/INVALID returned)
   * 4. Verify payment amount matches order amount
   * 5. Verify that the order hasn't been processed already
   * 6. Process order - update to paid, email buyer confirming payment (AEC?)  
   */ 
   function validateNotification( $response, $post, $invoice )
   {
      $response['valid'] = 0;
      $response['error'] = 1;	   
      if( !$this->pfValidIP( $_SERVER['REMOTE_ADDR'] ) )
      {
        $response['errormsg'] = PF_ERR_BAD_SOURCE_IP;                
        return $response; 
      }
            
      $pfHost = (PAYFAST_SERVER == 'LIVE') ? 'www.payfast.co.za' : 'sandbox.payfast.co.za'; 
      $pfValid = $this->pfValidData( $pfHost, $response['param_string'] );      
      if( !$pfValid )
      {
        $response['errormsg'] = PF_ERR_INVALID_DATA;      
        return $response; 
      }
      
      if ( !is_object( $invoice ) ) {
           $response['errormsg'] = PF_ERR_INVOICE_DATA;      
           return $response;
      }
                     
      if( !$this->pfAmountsEqual( $invoice->amount, $response['amount_paid'] ) )
      {	      
        $response['errormsg'] = PF_ERR_AMOUNT_MISMATCH;
        $this->pflog("ERROR: ".PF_ERR_AMOUNT_MISMATCH);
        return $response; 
      }   		       
                  
      $pf_id = $this->payFastId($invoice); //payfast transaction id                  
      if ($this->orderProcessedAlready($response,$post,$invoice,$pf_id)) {      
        $response['errormsg'] = PF_ERR_ORDER_ALREADY_PROCESSED;
        $this->pflog("ERROR: ".PF_ERR_ORDER_ALREADY_PROCESSED);
        return $response; 
      }
            
      if (!$this->notifyIfComplete($response,$post,$invoice,$pf_id)) {
        //pending or failed
        $this->pflog("Payment status not complete");
        return $response;
      } 
                        
      $this->pflog("Payment status complete, no errors");                  
      $response['error'] = 0;                  
      $response['valid'] = 1;
      return $response;
   }
   
   function payFastId($invoice)    
   {
		$pf_id = '';
		$invoiceparams = $invoice->getParams();
		if ( isset( $invoiceparams['pf_payment_id'] ) ) { //or secondary_ident?
		   $pf_id = $invoice->params['pf_payment_id'];
		} 
		return $pf_id;
   }
   
   function orderProcessedAlready($response,$post,$invoice,$pf_id) 
   {                
        //check if order has not been processed already
        //status of internal order: m_payment_id
        //notification has not been received already: check pf_payment_id                           
        if (($post['m_payment_id'] == $invoice->invoice_number) && !empty($pf_id) &&
           ($post['pf_payment_id'] == $pf_id)) {           
           //order already processed           
           return true;           
        }
        return false;
   }	

   function notifyIfComplete($response,$post,$invoice,$pf_id) 
   {
        $this->pflog("check payment status");
        if( $post['payment_status'] == 'COMPLETE') {            	        	         
	        if (empty($pf_id)) { //store payfast transaction id	          
				  $invoice->addParams( array( 'pf_payment_id' => $post['pf_payment_id'] ) );
				  $invoice->check();
				  $invoice->store();			
			  }
	        // Notify PayFast that information has been received
	        //ITN callback successful if PF receives "HTTP 200 OK" from notify page
		     header( 'HTTP/1.0 200 OK' );
		     flush();  
		     $complete = true;
        }
        elseif ( $post['payment_status'] == 'PENDING' ) {
              $complete = false;
              $response['pending']= 1;
              //$response['pending_reason']=$ErrMsg;
              $response['errormsg'] = PF_MSG_PENDING;
              $this->pflog("ERROR: ".PF_MSG_PENDING);
	     }   	           
        elseif( $post['payment_status'] == 'FAILED' ) {
              $complete = false;              
              $response['errormsg'] = PF_MSG_FAILED;
              $this->pflog("ERROR: ".PF_MSG_FAILED);
        }
      return $complete;   
   }
   
	/**
	 * pfGetData
	 *  
	 * @author Jonathan Smit
	 */
	function pfGetData($post)
	{
	    // Posted variables from ITN
	    $pfData = $post;
	
	    // Strip any slashes in data
	    foreach( $pfData as $key => $val )
	        $pfData[$key] = stripslashes( $val );
	
	    // Return "false" if no data was received
	    if( sizeof( $pfData ) == 0 )
	        return( false );
	    else
	        return( $pfData );
	}
	
	/**
	 * pfValidSignature
	 * 
	 * @author Jonathan Smit
	 */
	function pfValidSignature( $pfData = null, &$pfParamString = null, &$signature = null )
	{
	    // Dump the submitted variables and calculate security signature
	    foreach( $pfData as $key => $val )
	    {
	    	if( $key != 'signature' )
	    		$pfParamString .= $key .'='. urlencode( $val ) .'&';
	    }
	
	    // Remove the last '&' from the parameter string
	    $pfParamString = substr( $pfParamString, 0, -1 );
	    $signature = md5( $pfParamString );
	    
	    $result = ( $pfData['signature'] == $signature );
	
	    $this->pflog( 'Signature = '. ( $result ? 'valid' : 'invalid' ) );
	
	    return( $result );
	}
	/**
	 * pfValidIP
	 *
	 * @author Jonathan Smit
	 * @param $sourceIP String Source IP address 
	 */
	function pfValidIP( $sourceIP )
	{
	    // Variable initialization
	    $validHosts = array(
	        'www.payfast.co.za',
	        'sandbox.payfast.co.za',
	        'w1w.payfast.co.za',
	        'w2w.payfast.co.za',
	        );
	
	    $validIps = array();
	
	    foreach( $validHosts as $pfHostname )
	    {
	        $ips = gethostbynamel( $pfHostname );
	
	        if( $ips !== false )
	            $validIps = array_merge( $validIps, $ips );
	    }
	
	    // Remove duplicates
	    $validIps = array_unique( $validIps );
	
	    $this->pflog( "Valid IPs:\n". print_r( $validIps, true ) );
	
	    if( in_array( $sourceIP, $validIps ) )
	        return( true );
	    else
	        return( false );
	}
	/**
	 * pfValidData
	 *
	 * @author Jonathan Smit
	 * @param $pfHost String Hostname to use 
	 * @param $pfParamString String Parameter string to send
	 * @param $proxy String Address of proxy to use or NULL if no proxy
	 */
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
	        curl_setopt( $ch, CURLOPT_USERAGENT, PF_USER_AGENT );  // Set user agent
	        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );      // Return output as string rather than outputting it
	        curl_setopt( $ch, CURLOPT_HEADER, false );             // Don't include header in output
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
	/**
	 * pfAmountsEqual
	 * 
	 * Checks to see whether the given amounts are equal using a proper floating
	 * point comparison with an Epsilon which ensures that insignificant decimal
	 * places are ignored in the comparison.
	 * 
	 * eg. 100.00 is equal to 100.0001
	 *
	 * @author Jonathan Smit
	 * @param $amount1 Float 1st amount for comparison 
	 * @param $amount2 Float 2nd amount for comparison
	 */
	function pfAmountsEqual( $amount1, $amount2 )
	{	   
	    $this->pflog("amounts: ".$amount1." ,".$amount2);
	    if (empty( $amount1 ) || empty( $amount2 )) {
	       return false;
	    }
	    if( abs( floatval( $amount1 ) - floatval( $amount2 ) ) > PF_EPSILON ) {
	        $this->pflog("am not equal");
	        return( false );
	    }    
	    else {
	        $this->pflog("am equal");
	        return( true );
	    } 	    
	}
	
	/**
	 * pflog
	 *
	 * Log function for logging output.
	 *
	 * @author Riekie Botha
	 * @param $msg String Message to log
	 */
	function pflog( $msg = '')
	{	    	   	
	    // Only log if debugging is enabled
	    if( PF_DEBUG )
	    {
	       error_log("=====PAYFAST===== ".$msg."\n", 3, "/home/jfundico/public_html/log_Jbn2/payfast.log");          
  	    }
	}

}
?>
