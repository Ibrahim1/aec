<?php

class processor_suncorp_migs extends POSTprocessor
{
	function info()
	{
		$info = array();
		$info['name']					= "suncorp_migs";
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
		$settings['vpc_AccessCode']		= "ACCESSCODE";
		$settings['vpc_Merchant']		= "MERCHANTCODE";
		$settings['vpc_Locale']			= "en";
		$settings['vpc_SecureSecret']	= "SECRET CODE";
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

	function parseNotification( $post )
	{
		$response = array();

		$response['invoice']	= $_GET['vpc_MerchTxnRef'];
		$response['amount']		= $_GET['vpc_Amount'] / 100;

		return $response;
	}

	function validateNotification( $response, $post, $invoice )
	{
		$response['valid'] = ( $post['vpc_TxnResponseCode'] == 0 );

		return $response;
	}

}
?>