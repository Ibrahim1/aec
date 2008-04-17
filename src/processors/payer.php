<?php
defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );

class processor_payer extends POSTprocessor
{
	function info()
	{
		$info = array();
		$info['name']				= 'payer';
		$info['longname']			= "Payer";
		$info['statement']			= "Make payments with Payer!";
		$info['description'] 		= "Payer payment provider description";
		$info['currencies']			= 'EUR,USD,GBP,AUD,CAD,JPY,NZD,CHF,HKD,SGD,SEK,DKK,PLN,NOK,HUF,CZK';
		$info['languages']			= 'GB,DE,FR,IT,ES,US,SV';
		$info['cc_list']			= 'visa,mastercard';
		$info['recurring']			= 0;

		return $info;
	}

	function settings()
	{
		$settings = array();

		$settings['item_name']		= sprintf( _CFG_PROCESSOR_ITEM_NAME_DEFAULT, '[[cms_live_site]]', '[[user_name]]', '[[user_username]]' );
		$settings['testmode']		= 0;
		$settings['tax']			= '0';
		$settings['currency']		= 'SEK';
		$settings['lc']				= 'sv';
		$settings['payment_method']	= 'card';
		$settings['debugmode']		= 'silent';
		$settings['customparams']	= "";

		return $settings;
	}

	function backend_settings()
	{
		$settings = array();
		$settings['item_name']		= array( 'inputE' );
		$settings['testmode']		= array( 'list_yesno' );
		$settings['tax']			= array( 'inputA' );
		$settings['currency']		= array( 'list_currency' );
		$settings['lc']				= array( 'list_language' );
		$settings['payment_method']	= array( 'inputA' );
		$settings['debugmode']		= array( 'inputA' );
		$settings['customparams']	= array( 'inputD' );

        $settings = AECToolbox::rewriteEngineInfo( null, $settings );

		return $settings;
	}

	function createGatewayLink( $request )
	{
		global $mosConfig_live_site, $mosConfig_absolute_path, $my;

		$Auth_url		= $mosConfig_live_site . "/components/com_acctexp/processors/payer/authenticate.php";
		$Settle_url		= $mosConfig_live_site . "/components/com_acctexp/processors/payer/settle.php";
		$Success_url	= $mosConfig_live_site . "/index.php?option=com_acctexp&task=payernotification&Invoice=" . $request->int_var['invoice'];
		$Shop_url		= $mosConfig_live_site . "/index.php";

		require_once($mosConfig_absolute_path . "/components/com_acctexp/processors/payer/payread_post_api.php");

		$thePayreadApi = new payread_post_api;
		$thePayreadApi->add_buyer_info("firstname", "lastname", "address_1", "address_2", "postalcode", "city", "se", "phone_home", "phone_work", "phone_mobile", "email");

		$thePayreadApi->add_freeform_purchase(1, AECToolbox::rewriteEngine( $this->settings['item_name'], $request->metaUser, $request->new_subscription, $request->invoice ), $request->int_var['amount'], $this->settings['tax'], 1);

		$thePayreadApi->add_payment_method($this->settings["payment_method"]);

		$thePayreadApi->set_authorize_notification_url($Auth_url);
		$thePayreadApi->set_settle_notification_url($Settle_url);
		$thePayreadApi->set_success_redirect_url($Success_url);
		$thePayreadApi->set_redirect_back_to_shop_url($Shop_url);

		$thePayreadApi->set_language($this->settings['lc']);

		$thePayreadApi->set_currency($this->settings['currency']);

		$thePayreadApi->set_debug_mode($this->settings["debugmode"]);

		if ( $this->settings['testmode'] )
			$thePayreadApi->set_test_mode(true);
		else
			$thePayreadApi->set_test_mode(false);

		$var['post_url'] = $thePayreadApi->get_server_url();
		$var['payread_agentid'] = $thePayreadApi->get_agentid();
		$var['payread_xml_writer'] = $thePayreadApi->get_api_version();
		$var['payread_data'] = $thePayreadApi->get_xml_data();
		$var['payread_checksum'] = $thePayreadApi->get_checksum();

		return $var;
	}

	function parseNotification( $post )
	{
		global $database;
		$response = array();
		$response['invoice'] = $_GET['Invoice'];
		$response['valid'] = 1;

		return $response;
	}
}
?>