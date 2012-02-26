<?php
/**
 * @version $Id: ogone.php
 * @package AEC - Account Control Expiration - Membership Manager
 * @subpackage Processors - Ogone
 * @author Vijay Jawalapersad
 * @license GNU/GPL v.3 http://www.gnu.org/licenses/gpl.html or, at your option, any later version
 */

// Dont allow direct linking
( defined('_JEXEC') || defined( '_VALID_MOS' ) ) or die( 'Direct Access to this location is not allowed.' );

class processor_ogone_visa extends POSTprocessor
{
	function info()
	{
		$info = array();
		$info['name']			= 'ogone';
		$info['description']	= 'Ogone ReAT tv account.';
		$info['currencies']		= 'USD';
		$info['cc_list']		= 'visa';
		$info['recurring']		= 0;

		return $info;
	}

	function settings()
	{
		$settings = array();
		$settings['currency']	= 'USD';
		$settings['item_number']	= '[[user_id]]';
		$settings['customparams']	= "";
		return $settings;
	}

	function backend_settings()
	{
		$settings = array();
		$settings['customparams']	= "";
		return $settings;
	}


	function createGatewayLink( $request )
	{

		//TESTACCOUNT url
		$var['post_url']	= 'https://secure.ogone.com/ncol/test/orderstandard.asp';
	
		$var['PSPID']		= 'reattv';
		$var['orderID']		= $request->invoice->invoice_number;
			
		$bedragString	 = ereg_replace("[^0-9]", "", $request->int_var['amount']); 
		$bedragInt		 = (int)$bedragString;
		
		$var['amount']		= $bedragInt;
		$var['currency']	= $this->settings['currency'];
		$var['language']	= 'en_US';
		$var['PM']			= 'CreditCard';
		$var['accepturl']	= 'http://www.reat.tv/new/index.php?option=com_acctexp&task=ogone_visanotification';
		
		//TESTACCOUNT SHA String (to verify the POST variables have not been tampered with)
		$Shastring = 'ACCEPTURL='.$var['accepturl'].'1234?reatsecrsigAMOUNT='.$var['amount'].'1234?reatsecrsigCURRENCY='.$var['currency'].'1234?reatsecrsigLANGUAGE=en_US1234?reatsecrsigORDERID='.$var['orderID'].'1234?reatsecrsigPM='.$var['PM'].'1234?reatsecrsigPSPID=reattv1234?reatsecrsig';
		$Shastr = strtoupper(sha1($Shastring));
		$var['SHASign']		= $Shastr;
		
		return $var;
	}

	function parseNotification( $post )	
	{	
		$response = array();
		
		$response['invoice']			= $post['orderID'];
		
		return $response;
	}

	function validateNotification( $response, $post, $invoice )
	{
		$response = array();
		$response['valid'] = 1;
		return $response;
	}
}
