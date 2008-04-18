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

		$settings['agentid']		= '';
		$settings['key1']			= '';
		$settings['key2']			= '';
		$settings['tax']			= '';
		$settings['item_name']		= sprintf( _CFG_PROCESSOR_ITEM_NAME_DEFAULT, '[[cms_live_site]]', '[[user_name]]', '[[user_username]]' );
		$settings['testmode']		= 0;
		$settings['tax']			= '0';
		$settings['currency']		= 'SEK';
		$settings['lc']				= 'sv';
		$settings['payment_method']	= 'card';
		$settings['debugmode']		= 'silent';

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
		$settings['payment_method']	= array( 'list' );
		$settings['debugmode']		= array( 'inputA' );

 		$payment_method = array();
		$payment_method[] = mosHTML::makeOption ( "sms", "sms" );
		$payment_method[] = mosHTML::makeOption ( "card", "card" );
		$payment_method[] = mosHTML::makeOption ( "bank", "bank" );
		$payment_method[] = mosHTML::makeOption ( "phone", "phone" );
		$payment_method[] = mosHTML::makeOption ( "invoice", "invoice" );

		$pm = explode( ';', $this->settings['$payment_method'] );
		foreach ($this->settings['$payment_method'] as $name ) {
			$selected_methods[] = mosHTML::makeOption( $name, $name );
		}

		$settings['lists']['payment_method'] = mosHTML::selectList( $payment_method, 'payment_method', 'size="5" multiple="multiple"', 'value', 'text', $selected_methods );

        $settings = AECToolbox::rewriteEngineInfo( null, $settings );

		return $settings;
	}

	function createGatewayLink( $request )
	{
		global $mosConfig_live_site, $mosConfig_absolute_path, $my;

		$baseurl		= AECToolbox::deadsureURL( '/index.php?option=com_acctexp&amp;task=payernotification', false, true );
		$Auth_url		= $baseurl . '&action=authenticate';
		$Settle_url		= $baseurl . '&action=settle';
		$Success_url	= $request->int_var['return_url'];
		$Shop_url		= $mosConfig_live_site . "/index.php";

		require_once($mosConfig_absolute_path . "/components/com_acctexp/processors/payer/payread_post_api.php");

		// Header
		$xml  = "<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
		$xml .= "<payread_post_api_0_2 ".
				"xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\" ".
				"xsi:noNamespaceSchemaLocation=\"payread_post_api_0_2.xsd\"".
				">";
		// Seller details
		$xml .= "<seller_details>" .
					"<agent_id>"		. htmlspecialchars( $this->settings['agentid'] )		. "</agent_id>" .
				"</seller_details>";
		// Buyer details
		$xml .= "<buyer_details>" .
					"<first_name>"		. htmlspecialchars($this->myBuyerInfo["FirstName"])		. "</first_name>" .
					"<last_name>"		. htmlspecialchars($this->myBuyerInfo["LastName"])		. "</last_name>" .
					"<address_line_1>"	. htmlspecialchars($this->myBuyerInfo["AddressLine1"])	. "</address_line_1>" .
					"<address_line_2>"	. htmlspecialchars($this->myBuyerInfo["AddressLine2"])	. "</address_line_2>" .
					"<postal_code>"		. htmlspecialchars($this->myBuyerInfo["Postalcode"])	. "</postal_code>" .
					"<city>"			. htmlspecialchars($this->myBuyerInfo["City"])			. "</city>" .
					"<country_code>"	. htmlspecialchars($this->myBuyerInfo["CountryCode"])	. "</country_code>" .
					"<phone_home>"		. htmlspecialchars($this->myBuyerInfo["PhoneHome"])		. "</phone_home>" .
					"<phone_work>"		. htmlspecialchars($this->myBuyerInfo["PhoneWork"])		. "</phone_work>" .
					"<phone_mobile>"	. htmlspecialchars($this->myBuyerInfo["PhoneMobile"])	. "</phone_mobile>" .
					"<email>"			. $this->myBuyerInfo["Email"]							. "</email>" .
				"</buyer_details>";
		// Purchase
		$xml .= "<purchase>" .
					"<currency>"		. $this->settings['currency']		. "</currency>";
		// Add RefId if used
		$xml .=		"<reference_id>" . $request->int_var['invoice']		. "</reference_id>";
		// Start the Purchase list
		$xml .=		"<purchase_list>";

		$desc = AECToolbox::rewriteEngine( $this->settings['item_name'], $request->metaUser, $request->new_subscription, $request->invoice );

		// Purchase list (freeform purchases)
		$xml .= 		"<freeform_purchase>" .
							"<line_number>"			.  htmlspecialchars(1)								. "</line_number>" .
							"<description>"			.  htmlspecialchars($desc)							. "</description>" .
							"<price_including_vat>"	.  htmlspecialchars($request->int_var['amount'])	. "</price_including_vat>" .
							"<vat_percentage>"		.  htmlspecialchars($this->settings['tax'])			. "</vat_percentage>" .
							"<quantity>"			.  htmlspecialchars(1)								. "</quantity>" .
						"</freeform_purchase>";

		$xml .= 	"</purchase_list>" .
				"</purchase>";
		//Processing control
		$xml .=	"<processing_control>" .
					"<success_redirect_url>"		. htmlspecialchars($this->mySuccessRedirectUrl)			. "</success_redirect_url>";
					"<authorize_notification_url>"	.  htmlspecialchars($this->myAuthorizeNotificationUrl)	. "</authorize_notification_url>" .
					"<settle_notification_url>"		.  htmlspecialchars($this->mySettleNotificationUrl)		. "</settle_notification_url>" .
					"<redirect_back_to_shop_url>" 	.  htmlspecialchars($this->myRedirectBackToShopUrl)		. "</redirect_back_to_shop_url>" .
				"</processing_control>";

		// Database overrides
		$xml .= "<database_overrides>";

		// Payment methods
		$xml .= 	"<accepted_payment_methods>";
		$methods = explode( ';', $this->settings["payment_method"] );
		foreach ( $methods as $method )	{
			$xml .=		"<payment_method>"		. $method		. "</payment_method>";
		}
		$xml .= 	"</accepted_payment_methods>";

		// Debug mode
		$xml .= 	"<debug_mode>"		. 'silent'	. "</debug_mode>";
		// Test mode
		$xml .=		"<test_mode>"		. $this->settings['testmode']		. "</test_mode>";
		// Language
		$xml .=		"<language>"		. $this->settings['lc']		. "</language>";
		$xml .=		"</database_overrides>";

		// Footer
		$xml .= "</payread_post_api_0_2>";

		$var['post_url']			= "https://secure.pay-read.se/PostAPI_V1/InitPayFlow";
		$var['payread_agentid']		= $this->settings['agentid'];
		$var['payread_xml_writer']	= "payread_php_0_2";
		$var['payread_data']		= base64_encode( $xml );
		$var['payread_checksum']	= md5( $this->settings['key1'] . $xml . $this->settings['key2'] );

		return $var;
	}

	function parseNotification( $post )
	{
		global $database;

		$response = array();
		$response['valid']		= false;
		$response['invoice']	= $_GET['Invoice'];

		$allowed = array( '83.241.130.100', '83.241.130.101', '10.4.49.11', '192.168.100.222', '127.0.0.1', '217.151.207.84', '83.241.130.102' );

		if ( in_array( $_SERVER["REMOTE_ADDR"], $allowed ) ) {
			global $mosConfig_absolute_path;

			require_once( $mosConfig_absolute_path . "/components/com_acctexp/processors/payer/payread_post_api.php" );

			$postAPI = new payread_post_api( $this->settings );

			$requesturl = ( ( $_SERVER["SERVER_PORT"] == "80" ) ? "http://" : "https://" ) . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];

			$strippedUrl = substr( $requesturl, 0, strpos( $requesturl, "&md5sum" ) );
			$md5 = strtolower( md5( $this->settings['key1'] . $strippedUrl . $this->settings['key2'] ) );

			if ( strpos( strtolower( $requesturl ), $md5 ) >= 7 ) {
				if ( $_GET['action'] == 'authenticate' ) {
					$response['pending']		= 1;
					$response['pending_reason']	= 'authentication';
				} elseif ( $_GET['action'] == 'settle' ) {
					$response['valid']			= 1;
				}
			}
		}

		return $response;
	}

}
?>