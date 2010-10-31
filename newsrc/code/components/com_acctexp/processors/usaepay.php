<?php
/**
 * @version $Id: usaepay.php
 * @package AEC - Account Control Expiration - Membership Manager
 * @subpackage Processors - USA ePay
 * @copyright 2007-2008 Copyright (C) David Deutsch
 * @author David Deutsch <skore@skore.de> & Team AEC - http://www.valanx.org
 * @license GNU/GPL v.2 http://www.gnu.org/licenses/old-licenses/gpl-2.0.html or, at your option, any later version
 */

// Dont allow direct linking
( defined('_JEXEC') || defined( '_VALID_MOS' ) ) or die( 'Direct Access to this location is not allowed.' );

class processor_usaepay extends XMLprocessor
{
	function info()
	{
		$info = array();
		$info['name']					= "usaepay";
		$info['longname']				= "USAepay";
		$info['statement']				= "Make payments with USAepay!";
		$info['description']			= "USAepay";
		$info['cc_list']				= "visa,mastercard,discover,jcb";
		$info['currencies']				= "USD";
		$info['recurring']				= 1;
		$info['notify_trail_thanks']	= 1;

		return $info;
	}

	function settings()
	{
		$settings = array();

		$settings['testmode']		= 0;
		$settings['StoreKey']		= "StoreKey";
		$settings['secretWord']		= "Secret Word";
		$settings['item_name']		= sprintf( _CFG_PROCESSOR_ITEM_NAME_DEFAULT, '[[cms_live_site]]', '[[user_name]]', '[[user_username]]' );
		$settings['customparams']	= "";

		return $settings;
	}

	function backend_settings()
	{
		$settings = array();

		$settings['testmode']		= array( "list_yesno", "Test Mode", "Operate in USAePay TEST mode" );
		$settings['StoreKey']		= array( "inputC","Store Key","Your Alphanumeric ID assigned by USAePay" );
		$settings['secretWord']		= array( "inputC","Secret Word","Used to encrypt and protect transactions" );
		$settings['item_name']		= array( 'inputE' );
		$settings['customparams']	= array( 'inputD' );
		

		return $settings;
	}

	function checkoutform( $request )
	{
		$var = $this->getCCform();

		$values = array( 'firstname', 'lastname', 'address', 'city', 'state_usca', 'zip', 'country_list' );

		$var = $this->getUserform( $var, $values, $request->metaUser );

		return $var;
	}

	function createRequestXML( $request )
	{
		$var['UMkey']			= $this->settings['StoreKey'];

		$var['UMinvoice']		= $request->invoice->invoice_number;
		$var['UMdescription']	= AECToolbox::rewriteEngineRQ( $this->settings['item_name'], $request );
		$var['UMamount']		= 1.95;
		$var['UMbillamount']	= 9.95;
                
		$var['UMrecurring']		= "yes";                                          
		$var['UMstart']			= date("Ymd", time() + (15 * 24 * 60 * 60));  //UMstart Must be entered in YYYYMMDD
               		
		return $this->getNVPstring( $var );
	}

	function transmitRequestXML( $xml, $request )
	{
		$path = '/interface/epayform/' . $this->settings['StoreKey'] . '/';

		if ( $this->settings['testmode'] ) {
			$url	= "https://sandbox.usaepay.com" . $path;
			                        
		} else {
			$url	= "https://secure.usaepay.com" . $path;
		}

		$response = $this->transmitRequest( $url, $path, $xml );

		
	}

	function parseNotification ( $post )
	{
		$response = array();

		$response['invoice']	= $post['UMinvoice'];
		$response['amount']		= $post['UMauthAmount'];

		return $response;
	}

	function validateNotification( $response, $post, $invoice )
	{
		$response['valid'] = false;

		$ReturnCode	= aecGetParam('ReturnCode', 'NA');
		$ErrMsg		= aecGetParam('ErrMsg', 'NA');
		$FullTotal	= 1.95;   //aecGetParam('FullTotal', 'NA');  //   <========== working
		$CardNumber	= aecGetParam('CardNumber', 'NA');
		$OrderID	= aecGetParam('UMinvoice', 'NA');

		$checksum	= md5($OrderID . $FullTotal);

		$response = array();
		$response['TransRefNumber']	= aecGetParam('UMrefNum', 'NA');
		$response['Approved']		= aecGetParam('UMstatus', 'NA');
		$response['FullTotal']		= $FullTotal;
		$response['CardNumber']		= $CardNumber;
		$response['OrderID']		= $OrderID;
		$response['invoice']		= aecGetParam('UMinvoice', 'NA');
                //$response['valid']              = aecGetParam('valid', '0');
		//$validate			= md5($this->settings['secretWord'] . $FullTotal);
		//$response['valid']	= (strcmp($validate, $checksum) == 0);
                
		//if ( $response['valid'] = 1 )
                if  ($response['Approved'] == "Approved" ) {
			//if ( substr( $ReturnCode, 0, 1 ) == "Y" ) {
			        print_r("Response was...". $response['Approved'] . "<br/>");
				print_r("<b>Thankyou! - Your Card was approved</b><br/>");
				print_r("</br>");
				//print_r("<b>Card No:</b>". $CardNumber . "<br/>");
				print_r("<b>Total Charged:</b>". $FullTotal . "<br/>");
				//print_r("<br/>");
         			$response['valid'] = 1;
	          		$response['pending']= 0;
                                  				
			//} else {
			//	$response['valid']		= 0;
			//	$response['pending']		= 1;
			//	$response['pending_reason']	= $ErrMsg;
			//	print_r("<b>Transaction Declined <br/>Reason: </b>" .$ErrMsg . "<br/>");
			//}
		} else  {
			$response['valid'] = 0;
			$response['pending']= 1;
			$response['pending_reason']=$ErrMsg;
			print_r("<b>Transaction Declined (cs)<br/>Reason: </b>" .$ErrMsg . "<br/>");

		}
	}
}
?>
