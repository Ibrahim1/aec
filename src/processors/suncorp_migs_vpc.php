<?php

class processor_suncorp_migs_vpc extends POSTprocessor
{
	function info()
	{
		$info = array();
		$info['name']					= "suncorp_migs_vpc";
		$info['longname']				= "MIGS";
		$info['statement']				= "Suncorp VPC MIGS";
		$info['description']			= 'Suncorp VPC MIGS';
		$info['cc_list']				= "visa,mastercard";
		$info['recurring']				= 0;
		$info['notify_trail_thanks']	= 1;

		return $info;
	}
	function settings()
	{
		$settings = array();
		$settings['vpc_Version']		= "1";
		$settings['vpc_Command']		= "pay";
		$settings['vpc_AccessCode']		= "DD3A01D1";
		$settings['vpc_Merchant']		= "TEST59075896";
		$settings['vpc_Locale']			= "en";
		$settings['vpc_SecureHash']		= "80DB29544054DF49F75F1DAAD4E7A07D";
		$settings['vpc_OrderInfo']		= "VPC test";
		$settings['testmode']			= 0;
		$settings['vpc_TicketNo']		= "xxx";
		$settings['customparams']		= "";

		return $settings;
	}
	function backend_settings()
	{
		$settings = array();
		$settings['testmode']			= array( "list_yesno" );
		$settings['vpc_Version']		= array( "inputC" );
		$settings['vpc_Command']		= array( "inputC" );
		$settings['vpc_AccessCode']		= array( "inputC" );
		$settings['vpc_Merchant']		= array( "inputC" );
		$settings['vpc_Locale']			= array( "inputC" );
		$settings['vpc_ReturnURL']		= array( "inputC" );
		$settings['vpc_SecureHash']		= array( "inputC" );
		$settings['vpc_TicketNo']		= array( "inputC" );
		$settings['customparams']		= array( 'inputD' );

		$settings = AECToolbox::rewriteEngineInfo( null, $settings );

		return $settings;
	}

	function createGatewayLink( $request )
	{
		global $mosConfig_live_site;


		$var['post_url']	= 'http://www.worldmuaythaimagazine.com/cms/vpc/PHP_VPC_3Party_DO.php';

		$var['virtualPaymentClientURL']	= "https://migs.mastercard.com.au/vpcpay";

		$var['vpc_Version']		= $this->settings['vpc_Version'];
		$var['vpc_Command']		= $this->settings['vpc_Command'];
		$var['vpc_AccessCode']	= $this->settings['vpc_AccessCode'];
		$var['vpc_MerchTxnRef']	= $request->int_var['invoice'];
		$var['vpc_Merchant']	= $this->settings['vpc_Merchant'];
		$var['vpc_OrderInfo']	= $this->settings['vpc_OrderInfo'];
		$var['vpc_Amount']		= ($request->int_var['amount'])*100;
		$var['vpc_ReturnURL']	= AECToolbox::deadsureURL("index.php?option=com_acctexp&amp;task=migsnotification");
		$var['vpc_Locale']		= $this->settings['vpc_Locale'];
		$var['vpc_TicketNo']	= $this->settings['vpc_TicketNo'];

		return $var;
	}

/**
 * 350     2009-06-08 09:03:46     star     Error     Failed Invoice Payment     invoice, processor, payment, error     Processor migs notification for has failed - invoice number does not exist:     invoice_number()
 * 349     2009-06-08 09:03:46     star     Error     debug     debug     "POST:[]"     None
 * 348     2009-06-08 09:03:46     star     Error     debug     debug
 * "GET:{
 * "option":"com_acctexp",
 * "task":"migsnotification",
 * "Itemid":"",
 * "AgainLink":"...url...",
 * "vpc_Amount":"100",
 * "vpc_BatchNo":"0",
 * "vpc_Command":"pay",
 * "vpc_Locale":"en",
 * "vpc_MerchTxnRef":"IMTI3MWE3MDI5Yzlk",
 * "vpc_Merchant":"TEST59075896",
 * "vpc_Message":"Access code [--- ] for merchant [TEST59075896] is incorrect.",
 * "vpc_OrderInfo":"VPC test",
 * "vpc_SecureHash":"0B1E0E9817D38864FC1E50FDA57DDB7A",
 * "vpc_TransactionNo":"0",
 * "vpc_TxnResponseCode":"7",
 * "vpc_Version":"1"
 * }"     None
 * 344     2009-06-08 08:56:46     star     Error     debug     debug "
 * GET:{
 * "option":"com_acctexp",
 * "task":"migsnotification",
 * "Itemid":"",
 * "AgainLink":"...url...",
 * "vpc_Amount":"100",
 * "vpc_BatchNo":"0",
 * "vpc_Command":"pay",
 * "vpc_Locale":"en",
 * "vpc_MerchTxnRef":"IMjA4ODVjNzJjYTM1",
 * "vpc_Merchant":"TEST59075896",
 * "vpc_Message":"Access code [--- ] for merchant [TEST59075896] is incorrect.",
 * "vpc_OrderInfo":"VPC test",
 * "vpc_SecureHash":"FD93A72309097D0534C7075F80F42FED",
 * "vpc_TransactionNo":"0",
 * "vpc_TxnResponseCode":"7",
 * "vpc_Version":"1"
 * }"     None
 * 343     2009-06-08 08:56:46     star     Error     debug     debug "ResponseFunction:processNotification"
 */

	function parseNotification( $post )
	{
		$response = array();

		$response['invoice'] = $post['vpc_MerchTxnRef'];
		$response['invoice'] = $post['vpc_MerchTxnRef'];

		return $response;
	}

	function validateNotification( $response, $post, $invoice )
	{
		$response['valid'] = ( $post['vpc_TxnResponseCode'] == 0 );

		return $response;
	}

}
?>