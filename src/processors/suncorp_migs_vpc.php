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
		$settings['vpc_MerchTxnRef']	= sprintf( _CFG_PROCESSOR_ITEM_NAME_DEFAULT, '[[cms_live_site]]', '[[user_name]]', '[[user_username]]' );
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
		$settings['vpc_MerchTxnRef']	= array( "inputE" );
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
		$var['vpc_MerchTxnRef']	= AECToolbox::rewriteEngine( $this->settings['vpc_MerchTxnRef'], $request->metaUser, $request->new_subscription, $request->invoice );
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

		$invoice = explode( "_", $post['vpc_MerchTxnRef'] );

		$response['invoice'] = $invoice[1];

		return $response;
	}

	function validateNotification( $response, $post, $invoice )
	{
		$response['valid'] = ( $post['vpc_TxnResponseCode'] == 0 );

		return $response;
	}

}
?>