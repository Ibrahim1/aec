<?php
/**
 * @version $Id: usaepay.php
 * @package AEC - Account Control Expiration - Membership Manager
 * @subpackage Processors - Psigate
 * @copyright 2007-2011-2012 Copyright (C) David Deutsch
 * @author David Deutsch <skore@valanx.org> & Team AEC - http://www.valanx.org
 * @license GNU/GPL v.2 http://www.gnu.org/licenses/old-licenses/gpl-2.0.html or, at your option, any later version
 */

// Dont allow direct linking
( defined('_JEXEC') || defined( '_VALID_MOS' ) ) or die( 'Direct Access to this location is not allowed.' );

class processor_usaepay extends POSTprocessor
{
	function info()
	{
		$info = array();
		$info['name']					= "usaepay";
		$info['longname']				= "usaepay";
		$info['statement']				= "Make payments with USAEpay!";
		$info['description']			= "USAEpay";
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
		$settings['TestKey']		= "StoreKey";
		$settings['secretWord']		= "Secret Word";
		$settings['customparams']	= "";

		return $settings;
	}

	function backend_settings()
	{
		$settings = array();

		$settings['testmode']		= array( "list_yesno", "Test Mode", "Operate in USAePay TEST mode" );
		$settings['StoreKey']		= array( "inputC","Store Key","Your Alphanumeric ID assigned by USAePay" );
		$settings['secretWord']		= array( "inputC","Secret Word","Used to encrypt and protect transactions" );
		$settings['customparams']	= array( 'inputD' );
		return $settings;
	}

	function recurringDateCalculation()
	{
		$recurringChargeDate = date("Ymd", ( (int) gmdate('U') ) + (15 * 24 * 60 * 60));
		print_r("<br/><br/>15 days from today = ".$recurringChargeDate);
		return $recurringChargeDate;
	}

	function createGatewayLink( $request )
	{
		if ( $this->settings['testmode'] ) {
			$var['UMkey']		= $this->settings['TestKey'];

			$var['post_url']	= "https://sandbox.usaepay.com/interface/epayform/" . $var['UMkey'] . "/";
		} else {
			$var['UMkey']		= $this->settings['custId'];

			$var['post_url']	= "https://secure.usaepay.com/interface/epayform/" . $var['UMkey'] . "/";
		}

		$var['UMinvoice']		= $request->invoice->invoice_number;
		$var['UMdescription']	= "Subscription at Help-My-Finances.com";
		$var['UMamount']		= 1.95;
		$var['UMbillamount']	= 9.95;

		$var['UMrecurring']		= "yes";
		$var['UMstart']			= date("Ymd", ( (int) gmdate('U') ) + (15 * 24 * 60 * 60));  //UMstart Must be entered in YYYYMMDD

		return $var;
	}

	function parseNotification ( $post )
	{
		$response = array();
		$response['invoice'] = $post['UMinvoice'];

		return $response;
	}

	function validateNotification( $response, $post, $invoice )
	{
		$ReturnCode	= aecGetParam('ReturnCode', 'NA');
		$ErrMsg		= aecGetParam('ErrMsg', 'NA');
		$FullTotal	= aecGetParam('FullTotal', 'NA');
		$CardNumber	= aecGetParam('CardNumber', 'NA');
		$OrderID	= aecGetParam('UMinvoice', 'NA');

		$checksum	= md5($OrderID . $FullTotal);

		$response = array();

		//$validate			= md5($this->settings['secretWord'] . $FullTotal);
		//$response['valid']	= (strcmp($validate, $checksum) == 0);

		//if ( $response['valid'] = 1 )
		if  ( $post['UMstatus'] == "Approved" ) {
			$response['valid'] = 1;
			$response['pending']= 0;
		} else  {
			$response['valid'] = 0;
			$response['pending']= 1;
			$response['pending_reason']=$ErrMsg;

		}

		//print_r("<b>TransRefNumber:</b>". $response['TransRefNumber'] . "<br/>");
		print_r("<b>Invoice:</b>". $response['invoice'] . "<br/><br/><br/>");
		//print_r("<b>OrderID:</b>". $response['OrderID']. "<br/>");
		//print_r("<b>Approval:</b>". $response['Approved'] . "<br/>");
		//print_r("<b>Valid Code:</b>". $response['valid'] . "<br/>");
		//print_r("<b>Pending Code:</b>". $response['pending']. "<br/>");

		return $response;
	}

}
?>
