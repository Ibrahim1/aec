<?php
/**
 * @version $Id: netpay.php
 * @package AEC - Account Control Expiration - Membership Manager
 * @subpackage Processors - Netpay
 * @copyright 2006-2008 Copyright (C) David Deutsch
 * @author David Deutsch <skore@skore.de> & Team AEC - http://www.globalnerd.org
 * @license GNU/GPL v.2 http://www.gnu.org/licenses/old-licenses/gpl-2.0.html or, at your option, any later version
 */

// Dont allow direct linking
( defined('_JEXEC') || defined( '_VALID_MOS' ) ) or die( 'Direct Access to this location is not allowed.' );

class processor_netpay extends POSTprocessor
{
	function info()
	{
		$info = array();
		$info['name']			= "netpay";
		$info['longname']		= "Net Builder";
		$info['statement']		= "Make payments with NetPay - it\'s fast, free and secure!";
		$info['currencies']		= "MYR";
		$info['description']	= "NetPay is the easiest and most affordable payment gateway in Malaysia. Process credit card payments via NetPay\'s own secure Shared Payment Page in real-time.";
		$info['cc_list']		= "visa,mastercard";
		$info['recurring']		= 0;
		return $info;
	}

	function settings()
	{
		$settings = array();
		$settings['testmode']		= "1";
		$settings['custId']			= "test438";
		$settings['tax']			= "0";
		$settings['autoRedirect']	= 1;
		$settings['testAmount']		= "00";
		$settings['item_name']		= sprintf( _CFG_PROCESSOR_ITEM_NAME_DEFAULT, '[[cms_live_site]]', '[[user_name]]', '[[user_username]]' );
		$settings['rewriteInfo']	= ''; // added mic

		return $settings;
	}

	function backend_settings()
	{
		$settings = array();
		$rewriteswitches			= array( 'cms', 'user', 'expiration', 'subscription', 'plan' );

		$settings['testmode']		= array( 'list_yesno' );
		$settings['custId']			= array( 'inputC' );
		$settings['autoRedirect']	= array( 'list_yesno' ) ;
		$settings['SiteTitle']		= array( 'inputC' );
		$settings['item_name']		= array( 'inputE' );

				$settings['rewriteInfo']	= array( 'fieldset', _AEC_MI_REWRITING_INFO, AECToolbox::rewriteEngineInfo( $rewriteswitches ) );

		return $settings;
	}

	function createGatewayLink( $int_var, $cfg, $metaUser, $new_subscription )
	{
		global $mosConfig_live_site;

		//URL returned by eWay
	//	$return_url = AECToolbox::deadsureURL("/index.php?option=com_acctexp&amp;task=ewaynotification");

		$var = array(	"post_url" => "https://www.onlinepayment.com.my/NBepay/pay/test438/?",
						"orderid" => $int_var['invoice'], //The invoice number
						"bill_name" => $metaUser->cmsUser->name,
						"bill_email" => $metaUser->cmsUser->email,
						"bill_mobile" =>'',
						"amount" => $int_var['amount'],
						"bill_desc" => AECToolbox::rewriteEngine( $cfg['item_name'], $metaUser, $new_subscription )
					);
		return $var;
	}

/*
	<?PHP
$passwd="xxxxx";
//------ below don't change ---------------
$tranID =$_POST['tranID'];
$orderid =$_POST['orderid'];
$status =$_POST['status'];
$domain =$_POST['domain'];
$amount =$_POST['amount'];
$currency =$_POST['currency'];
$appcode =$_POST['appcode'];
$paydate =$_POST['paydate'];
$skey =$_POST['skey'];
// All undeclared variables below are coming from POST method
$key0 = md5( $tranID.$orderid.$status.$domain.$amount.$currency );
$key1 = md5( $paydate.$domain.$key0.$appcode.$passwd );
if( $skey != $key1 ) $status= -1; // invalid transaction
//-------------------------------------------
If ( $status == "00" ){
// success action, e.g. update order status to paid
// write your script here .....
} else {
// failure action
// write your script here .....
}
?>
*/

	function parseNotification( $post, $cfg )
	{
		$passwd="ce848";
		//------ below don't change ---------------
		$tranID =$_POST['tranID'];
		$orderid =$_POST['orderid'];
		$status =$_POST['status'];
		$domain =$_POST['domain'];
		$amount =$_POST['amount'];
		$currency =$_POST['currency'];
		$appcode =$_POST['appcode'];
		$paydate =$_POST['paydate'];
		$skey =$_POST['skey'];
		// All undeclared variables below are coming from POST method
		$key0 = md5( $tranID.$orderid.$status.$domain.$amount.$currency );
		$key1 = md5( $paydate.$domain.$key0.$appcode.$passwd );
		if( $skey != $key1 ) $status= -1; // invalid transaction

		$response = array();

		$response['invoice'] = $post['orderid'];

		if ( $post['status'] == "00" && isset( $appcode ) ) {
			$response['valid'] = 1;    //Means Status is OK and there is a value in the Approval Code, then update 1
		} else {
			$response['valid'] = 0;
		}

		return $response;
	}



}

?>
