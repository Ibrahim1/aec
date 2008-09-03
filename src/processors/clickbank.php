<?php
/**
 * @version $Id: clickbank.php
 * @package AEC - Account Control Expiration - Membership Manager
 * @subpackage Processors - Clickbank
 * @copyright 2007-2008 Copyright (C) David Deutsch, Pasapum Naonan
 * @author David Deutsch <skore@skore.de> & Team AEC - http://www.globalnerd.org
 * @license GNU/GPL v.2 http://www.gnu.org/licenses/old-licenses/gpl-2.0.html or, at your option, any later version
 */

// Dont allow direct linking
defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );

class processor_clickbank extends GETprocessor
{
	function info()
	{
		$info = array();
		$info['name']					= 'clickbank';
		$info['longname'] 				= _CFG_CLICKBANK_LONGNAME;
		$info['statement'] 				= _CFG_CLICKBANK_STATEMENT;
		$info['description'] 			= _DESCRIPTION_CLICKBANK_SUBSCRIPTION;
		$info['cc_list'] 				= "visa,mastercard,americanexpress,discover,dinersclub,jcb,paypal";
		$info['currencies']				= "USD";
		$info['recurring'] 				= 2;

		return $info;
	}

	function settings()
	{
		$settings = array();
		$settings['publisher']			= 'clickbank';
		$settings['secret_key']			= 'secret_key';
		$settings['item_number']		='';
		$settings['customparams']		= "";

		return $settings;
	}

	function backend_settings()
	{
		$settings = array();
		$settings['publisher']			= array( 'inputC' );
		$settings['secret_key']			= array( 'inputC' );
		$settings['info']				= array( 'fieldset' );
		$settings['item_number']		= array( 'inputC' );
		$settings['customparams']		= array( 'inputD' );

		$settings = AECToolbox::rewriteEngineInfo( null, $settings );

		return $settings;
	}

	function createGatewayLink( $request )
	{
		global $mosConfig_live_site;
		$url['item_number']					= $this->settings['item_number'];
		$url['publisher']					= $this->settings['publisher'];

		$var['post_url']		= 'http://'.$url['item_number'].'.'.$url['publisher'].'.pay.clickbank.net';

		$var['invoice']			= $request->int_var['invoice']; //pass internal invoice to clickbank, so it will pass back to us for internal checking

		//$var['total']				= $request->int_var['amount'];

		$var['user_id']				= $request->metaUser->cmsUser->id;
		$var['cart_order_id']		= AECToolbox::rewriteEngine( $this->settings['item_number'], $request->metaUser, $request->new_subscription, $request->invoice );
		$var['username']			= $request->metaUser->cmsUser->username;
		$var['name']				= $request->metaUser->cmsUser->name;
		$var['email']				= $request->metaUser->cmsUser->email;

		return $var;
	}

	function parseNotification( $request )
	{

		$cbreceipt		= $request['cbreceipt']; 	//ClickBank receipt number (cbreceipt)
		//echo $cbreceipt;
		$time			= $request->int_var['time'];		//Epoch time of the order (time & seconds)
		$item			= $request->int_var['item'];		//ClickBank item number (item)
		$cbpop			= $request->int_var['cbpop'];		//ClickBank proof of purchase (cbpop)
		$cname			= $request->int_var['cname'];		//Customer name, first+last (cname)
		$cemail			= $request->int_var['cemail'];		//Customer e-mail (cemail)
		$czip			= $request->int_var['czip'];		//Customer zip (czip) New!
		$ccountry		= $request->int_var['ccountry'];	//Customer country (ccountry) New!
		$cbaffi			= $request->int_var['cbaffi'];		//Affiliate nickname (cbaffi)
		$invoice		= $request->int_var['invoice'];  // AEC invoice_number that we pass through clickbank, now it pass back
		$userid			= $request->int_var['user_id'];
		$username		= $request->int_var['username'];


		$response = array();
		$response['invoice'] = $invoice;
		$response['username'] = $username;
		//$response['cbreceipt'] = $cbreceipt;
		//$response['time'] = $time;
		//$response['item'] = $item;
		//$response['cbpop'] = $cbpop;
		//$response['cname'] = $cname;
		//$response['cemail'] = $cemail;
		//$response['czip'] = $czip;
		//$response['ccountry'] = $ccountry;
		//$response['cbaffi'] = $cbaffi;
		//$response['Itemid'] = $item;
		//		$response['userid'] = $userid;
		//				$response['planid'] = $planid;

						//$var['pay_to_email']= $cfg['pay_to_mail'];
		//				$response['transaction_id']= $int_var['invoice'];
		//				$response['return_url']=AECToolbox::deadsureURL('/index.php?option=com_acctexp&amp;task=thanks');
		//				$response['cancel_url']=AECToolbox::deadsureURL('/index.php?option=com_acctexp&amp;task=cancel');
		//				$response['status_url']=AECToolbox::deadsureURL('/index.php?option=com_acctexp&amp;task=clickbanknotification');

		return $response;
	}

	function validateNotification( $response, $request, $invoice )
	{
		$key		=	$this->settings['secret_key'];
		$rcpt		=	$request->int_var['cbreceipt'];
		$time		=	$request->int_var['time'];
		$item		=	$request->int_var['item'];
		$cbpop	=	$request->int_var['cbpop'];
		$xxpop = sha1("$key|$rcpt|$time|$item");
		$xxpop = strtoupper(substr($xxpop,0,8));

		if ( $cbpop == $xxpop){
			$response['valid'] = 1;
		} else {
			$response['valid'] = 0;
		}

		return $response;

	}

}
?>