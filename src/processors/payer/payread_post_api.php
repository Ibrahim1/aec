<?
/**
 * payread_post_api.php - API to send transactions to payread
 *
 * This file contains all the methods you will need to be able to send transactions
 * to PAYER.
 *
 * This file should NOT be modified since new versions might overwrite
 * changes made by you.
 *
 *
 * Filename: payread_post_api.php
 * Project: payread_post_api
 * Created on 2005-nov-23
 *
 * @author  Pay-Read Installation <installation@pay-read.se>
 * @package payread_post_api
 *
 */
	class payread_post_api
	{

		/**
		 * This is the default constructor, used by the POST API to read your
		 * authorization settings.
		 *
		 * You will never have to call this method
		 */
	 	function payread_post_api()
		{
			require("PayReadConf.php");

			// Set defaults
			$this->myPostApiVersion   = "payread_php_0_2";
			$this->myAgentId          = $PayRead_AgentId;
			$this->myKeys["A"]        = $PayRead_Key1;
			$this->myKeys["B"]        = $PayRead_Key2;
			$this->myPayReadServerUrl = "https://secure.pay-read.se/PostAPI_V1/InitPayFlow";
			$this->myCurrency         = "SEK";
			$this->myLanguage         = "sv";
			$this->myDebugMode        = "silent";
			$this->myTestMode         = "true";
		}

		/**
		 * This is the method that will print out the form data with the necessary parameters as hidden fields.
		 *
         * example:
         * 	<input type="hidden" name="payread_agentid" value="get_agentid()">
         * 	<input type="hidden" name="payread_xml_writer" value="get_api_version()">
         * 	<input type="hidden" name="payread_data" value="get_xml_data()">
         * 	<input type="hidden" name="payread_checksum" value="get_checksum()">
		 * @return   nothing
   		 */
		function generate_form()
		{
			echo "<input type=\"hidden\" name=\"payread_agentid\" value=\""		. $this->get_agentid()		. "\" />\n";
			echo "<input type=\"hidden\" name=\"payread_xml_writer\" value=\""	. $this->get_api_version()	. "\" />\n";
			echo "<input type=\"hidden\" name=\"payread_data\" value=\""		. $this->get_xml_data()		. "\" />\n";
			echo "<input type=\"hidden\" name=\"payread_checksum\" value=\""	. $this->get_checksum()		. "\" />\n";
		}

		/**
		 * This method will return your agentid (which is the identification id for your shop).
		 * It you want, you can use the generate_form() method instead and then you don't need to call this method. Otherwise you will need to put this in the hidden variable "payread_agentid".
		 * @return int agentid
		 */
		function get_agentid()
		{
			return $this->myAgentId;
		}

		/**
		 * This method will return which version of the POST API you are using.
		 * It you want, you can use the generate_form() method instead and then you don't need to call this method.	Otherwise you will need to put this in the hidden variable "payread_xml_writer".
		 * @return string api version
		 */
		function get_api_version()
		{
			return $this->myPostApiVersion;
		}

		/**
		 * This method will return the xml in base64 format which needs to be posted to PAYER.
		 * It you want, you can use the generate_form() method instead and then you don't need to call this method.	Otherwise you will need to put this in the hidden variable "payread_data".
		 * @return string xml data
		 */
		function get_xml_data()
		{
			$this->generate_purchase_xml();
			$this->encrypt_data($this->myXmlData);
			return $this->myXmlData;
		}

		/**
		 * This method will return the checksum for the postdata. You need to post this checksum to PAYER.
		 * It you want, you can use the generate_form() method instead and then you don't need to call this method. Otherwise you will need to put this in the hidden variable "payread_checksum
		 * @return string Md5 checksum
		 */
		function get_checksum()
		{
			$this->checksum_data();
			return $this->myChecksum;
		}

		/**
		 * This method will return the URL to the POST-API located on PAYERs server.
		 * @return string url to PAYER post-api
		 * @access private
		 */
		function get_server_url()
		{
			return $this->myPayReadServerUrl;
		}

		/**
		 * This method will set the url that later the get_server_url will return - for testpurposes only
		 * @param string $url the URL
		 * @return nothing 
		 */
		function set_server_url($url)
		{
			$this->myPayReadServerUrl=$url;
		}

		/**
		 * This method will set which currency the transaction is in. Use 3 letters in uppercase.
		 * @param string $theCurrency 3 letter uppercase currency (ie "SEK") (required)
		 * @return   nothing
		 */
		function set_currency($theCurrency)
		{
			if(strlen($theCurrency) < 4)
				$this->myCurrency = $theCurrency;
		}

		/**
		 * This method will set a general description of the purchase, that will be used in
		 * various situations, e.g. in the Pay&Read admin web, or communication with the
		 * buyer when its impossible to present the full specification.
		 * @param string $theDescription is one-line short description of the purchase. Notice that
		 * this description may be truncated depending on where it is presented, the maximum length
		 * that will be stored by Pay&read is 255 characters, but try to keep it below 32
		 * characters
		 * @return   nothing
		 */
		function set_description($theDescription)
		{
				$this->myDescription = $theDescription;
		}

		/**
		 * This method will set a reference Id for the purchase.
		 * @param string $theReferenceId is the reference Id. It is possible that this
		 * string might be presented to the buyer.
		 * @return   nothing
		 */
		function set_reference_id($theReferenceId)
		{
				$this->myReferenceId = $theReferenceId;
		}

		/**
		 * This method is not yet used
		 * @access private
		 * @return   nothing
		 */
		function add_catalog_purchase($theLineNumber, $theId, $theQuantity)
		{
			$this->myCatalogPurchases[] = array("LineNo" => $theLineNumber, "Id" => $theId, "Quantity" => $theQuantity);
		}

		/**
		 * This method must be called at least one call.
		 *
		 * This method will add a product, use this method multiple times per each product the buyer will pay for.
		 * @param string $theLineNumber order of the output lines of the products buyed, starting at 1. (required)
		 * @param string $theDescription decription of the product buyed. (required)
		 * @param int $thePrice price of the product buyed. (required)
		 * @param int $theVat vat of the product buyed. (required)
		 * @param int $theQuantity quantity of the product buyed. (required)
		 * @return   nothing
		 */
		function add_freeform_purchase($theLineNumber, $theDescription, $thePrice, $theVat, $theQuantity)
		{
			$this->myFreeformPurchases[] = array("LineNo" => $theLineNumber, "Description" => $theDescription, "Price" => $thePrice, "Vat" => $theVat, "Quantity" => $theQuantity);
		}

		/**
		 * This method is optional
		 *
		 * This method will add additional information static text that the buyer will see when he goes to PAYER website. Use this method multiple times per each information line.
		 * @param string $theLineNumber which product you want additional information for. (required)
		 * @param string $theText the additional decription of the product buyed. (required)
		 * @return   nothing
		 */
		function add_info_line($theLineNumber, $theText)
		{
			$this->myInfoLines[] = array("LineNo" => $theLineNumber, "Text" => $theText);
		}

		/**
		 * This method must be called.
		 *
		 * This method set the buyer information that will be posted to PAYER
		 * @param string $theFirstName buyers firstname (optional)
		 * @param string $theLastName buyers lastname (optional)
		 * @param string $theAddressLine1 buyers adressline1 (optional)
		 * @param string $theAddressLine2 buyers adressline1 (optional)
		 * @param string $thePostalcode buyers postalcode (optional)
		 * @param string $theCity buyers city (optional)
		 * @param string $theCountryCode buyers countrycode (optional)
		 * @param string $thePhoneHome buyers phonenumber home (optional)
		 * @param string $thePhoneWork buyers phonenumber work (optional)
		 * @param string $thePhoneMobile buyers phonenumber mobile (optional)
		 * @param string $theEmail buyers email (optional)
		 * @return   nothing
		 */
		function add_buyer_info($theFirstName, $theLastName, $theAddressLine1, $theAddressLine2, $thePostalcode, $theCity, $theCountryCode, $thePhoneHome, $thePhoneWork, $thePhoneMobile, $theEmail)
		{
			$this->myBuyerInfo["FirstName"]    = $theFirstName;
			$this->myBuyerInfo["LastName"]     = $theLastName;
			$this->myBuyerInfo["AddressLine1"] = $theAddressLine1;
			$this->myBuyerInfo["AddressLine2"] = $theAddressLine2;
			$this->myBuyerInfo["Postalcode"]   = $thePostalcode;
			$this->myBuyerInfo["City"]         = $theCity;
			$this->myBuyerInfo["CountryCode"]  = $theCountryCode;
			$this->myBuyerInfo["PhoneHome"]    = $thePhoneHome;
			$this->myBuyerInfo["PhoneWork"]    = $thePhoneWork;
			$this->myBuyerInfo["PhoneMobile"]  = $thePhoneMobile;
			$this->myBuyerInfo["Email"]  	   = $theEmail;
		}

		/**
		 * This method must be called.
		 *
		 * This method will set the payment method the buyer can use to pay with.
		 * @param string $theMethod
		 * @return   nothing
		 */
		function add_payment_method($theMethod)
		{
			if($theMethod == "sms"    ||
			   $theMethod == "card"   ||
			   $theMethod == "bank"  ||			   
			   $theMethod == "phone"  ||
			   $theMethod == "invoice" )
				$this->myPaymentMethods[] = $theMethod;
		}

		/**
		 * This method is optional
		 *
		 * If you want the recipt to be handled by your shop, this method will set the URL where the buyer will be redirected. If you don't use this method the buyer will get a recipt on PAYER server.
		 * @param string $theUrl URL to your recipt if handled by shop.	(required)
		 * @return   nothing
		 */
		function set_success_redirect_url($theUrl)
		{
			$this->mySuccessRedirectUrl = $theUrl;
		}


		/**
		 * This method must be called.
		 *
		 * This method will set the URL where your Authorize webpage is located, remember that you will need to respond "TRUE" if everything is ok, or "FALSE" if something goes wrong, on your page.
		 * @param string $theUrl URL to your authorize notification page. (required)
		 * @return   nothing
		 */
		function set_authorize_notification_url($theUrl)
		{
			$this->myAuthorizeNotificationUrl = $theUrl;
		}

		/**
		 * This method must be called.
		 *
		 * This method will set the URL where your Settle webpage is located, remember that you will need to respond "TRUE" if everything is ok, or "FALSE" if something goes wrong, on your page.
		 * @param string $theUrl URL to your settle notification page. (required)
		 * @return   nothing
		 */
		function set_settle_notification_url($theUrl)
		{
			$this->mySettleNotificationUrl = $theUrl;
		}

		/**
		 * This method must be called.
		 *
		 * This method will set the URL where your frontpage of the shop is located.
		 * @param string $theUrl URL to your frontpage of the shop. (required)
		 * @return   nothing
		 */
		function set_redirect_back_to_shop_url($theUrl)
		{
			$this->myRedirectBackToShopUrl = $theUrl;
		}

		/**
		 * This method is optional, default value is "silent"
		 *
		 * This method will set the debug mode, if set to verbose you will be able to see the parameters posted at the page where you enter bankcard information
		 * @param string $theDebugMode debug mode, set as "silent"/"brief"/"verbose" (required)
		 * @return   nothing
		 */
		function set_debug_mode($theDebugMode)
		{
			if($theDebugMode == "silent" ||
			   $theDebugMode == "brief"  ||
			   $theDebugMode == "verbose" )
			 $this->myDebugMode = $theDebugMode;
		}

		/**
		 * This method is optional, default value is true
		 *
		 * This method will set the testmode, if set to true, PAYER will not contact the bank and no money will be taken from the bank account connected to the bankcard, otherwise everything will act like a real transaction.
		 * @param boolean $theDebugMode test mode, set as true/false (required)
		 * @return   nothing
		 */
		function set_test_mode($theTestMode)
		{
			if($theTestMode == true)
				$this->myTestMode = "true";
			else
				$this->myTestMode = "false";
		}

		/**
		 * This method is optional, default value is "sv"
		 *
		 * This method will set which language the buyer will see when he enters bankcard information. The input should be in lowercase and you should enter language code (2 letters) not countrycode ie "sv" not "se".
		 * @param string $theLanguageCode 2 letter uppercase language (ie "sv") (required)
		 * @return   nothing
		 */
		function set_language($theLanguageCode)
		{
			if(strlen($theLanguageCode) == 2)
				$this->myLanguage = $theLanguageCode;
		}

		/**
		 * This method must be called from the settle & authorize pages for security reasons.
		 *
		 * This method will validate that the callback orginates from PAYERs server. This method should be called from your authorize and settle pages.
		 * @param string $theUrl URL to be validated (required)
		 * @return boolean true/false
		 */
		function validate_callback_url($theUrl)
		{
			// strip the &md5sum from url
			$strippedUrl = substr($theUrl, 0, strpos($theUrl, "&md5sum"));

			// add the Key1 and Key2 from the stripped url and calculate checksum
			$md5 = strtolower(md5($this->myKeys["A"].$strippedUrl.$this->myKeys["B"]));

			// do we find the calculated checksum in in the original URL somewhere ?
			if (strpos(strtolower($theUrl), $md5)>=7)
				return true; // yes - this is authentic
			else
				return false; // no - whis is not a properly signed url
		}

	    /**#@+  beginning of docblock template area
	     * @access private
	     * @var string
	     * Internal variables and functions
	     */

		var $myClientVersion;
		var $myAgentId;
		var $myKeys;
		var $myPayReadServerUrl;

		var $myCurrency;
		var $myDescription;
		var $myReferenceId;
		var $myCatalogPurchases;
		var $myFreeformPurchases;
		var $myInfoLines;
		var $myBuyerInfo;
		var $myPaymentMethods;
		var $mySuccessRedirectUrl;
		var $myAuthorizeNotificationUrl;
		var $mySettleNotificationUrl;
		var $myRedirectBackToShopUrl;
		var $myDebugMode;
		var $myTestMode;
		var $myLanguage;

		var $myXmlData;
		var $myChecksum;

		/**
		 * This method will encrypt the data using base64
		 * @access private
		 */
		function encrypt_data($theData, $theEncryptionMethod = "base64")
		{
			switch($theEncryptionMethod)
			{
				case "base64" :
					$this->myXmlData = base64_encode($this->myXmlData);
					break;
			}
		}

		/**
		 * This method will set the checksum
		 * @access private
		 */
		function checksum_data($theAuthMethod = "md5")
		{
			switch($theAuthMethod)
			{
				case "md5" :
					$this->myChecksum = md5($this->myKeys["A"] . $this->myXmlData . $this->myKeys["B"]);
					break;
			}
		}

		/**
		 * This method will generate the xml data that you need to post på the Post-API.
		 * @access private
		 */
		function generate_purchase_xml()
		{
			// Header
			$this->myXmlData  = "<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
			$this->myXmlData .= "<payread_post_api_0_2 ".
								"xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\" ".
								"xsi:noNamespaceSchemaLocation=\"payread_post_api_0_2.xsd\"".
								">";

			// Seller details
			$this->myXmlData .= "<seller_details>" .
								"<agent_id>"		.  htmlspecialchars($this->myAgentId)					. "</agent_id>" .
								"</seller_details>";

			// Buyer details
			$this->myXmlData .= "<buyer_details>" .
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
								"<email>"	. $this->myBuyerInfo["Email"]			. "</email>" .
								"</buyer_details>";

			// Purchase
			$this->myXmlData .= "<purchase>" .
								"<currency>"		. $this->myCurrency		. "</currency>";
			// Add Descr if used
			if (!empty($this->myDescription)) {
				$this->myXmlData .= "<description>" . $this->myDescription		. "</description>";
			}
			
			// Add RefId if used
			if (!empty($this->myReferenceId)) {
				$this->myXmlData .= "<reference_id>" . $this->myReferenceId		. "</reference_id>";
			}
			// Start the Purchase list					
			$this->myXmlData .=	"<purchase_list>";

			// Purchase list (catalog purchases)
			@reset($this->myCatalogPurchases);
			while( list(, $thePurchase) = @each($this->myCatalogPurchases) )
			{
				$this->myXmlData .= "<catalog_purchase>" .
									"<line_number>"	.  htmlspecialchars($thePurchase["LineNo"])		. "</line_number>" .
									"<id>"			.  htmlspecialchars($thePurchase["Id"])			. "</id>" .
									"<quantity>"	.  htmlspecialchars($thePurchase["Quantity"])	. "</quantity>" .
									"</catalog_purchase>";
			}

			// Purchase list (freeform purchases)
			@reset($this->myFreeformPurchases);
			while( list(, $thePurchase) = @each($this->myFreeformPurchases) )
			{
				$this->myXmlData .= "<freeform_purchase>" .
									"<line_number>"	.  htmlspecialchars($thePurchase["LineNo"])			. "</line_number>" .
									"<description>"	.  htmlspecialchars($thePurchase["Description"])	. "</description>" .
									"<price_including_vat>"	.  htmlspecialchars($thePurchase["Price"])	. "</price_including_vat>" .
									"<vat_percentage>"		.  htmlspecialchars($thePurchase["Vat"])	. "</vat_percentage>" .
									"<quantity>"	.  htmlspecialchars($thePurchase["Quantity"])		. "</quantity>" .
									"</freeform_purchase>";
			}

			// Purchase list (info lines)
			@reset($this->myInfoLines);
			while( list(, $theValues) = @each($this->myInfoLines) )
			{
				$this->myXmlData .= "<info_line>" .
									"<line_number>"	.  htmlspecialchars($theValues["LineNo"])	. "</line_number>" .
									"<text>"		.  htmlspecialchars($theValues["Text"])		. "</text>" .
									"</info_line>";
			}

			$this->myXmlData .= "</purchase_list>" .
								"</purchase>";


			//Processing control
			$this->myXmlData .=
				"<processing_control>" ;
				if (!empty($this->mySuccessRedirectUrl))
				$this->myXmlData .=	"<success_redirect_url>"	. htmlspecialchars($this->mySuccessRedirectUrl)	. "</success_redirect_url>";
			$this->myXmlData .=
				"  <authorize_notification_url>"	.  htmlspecialchars($this->myAuthorizeNotificationUrl)	. "</authorize_notification_url>" .
				"  <settle_notification_url>"		.  htmlspecialchars($this->mySettleNotificationUrl)	. "</settle_notification_url>" .
				"  <redirect_back_to_shop_url>" 	.  htmlspecialchars($this->myRedirectBackToShopUrl) . "</redirect_back_to_shop_url>" .
				"</processing_control>";

			// Database overrides
			$this->myXmlData .= "<database_overrides>";

			// Payment methods
			$this->myXmlData .= "<accepted_payment_methods>";
			@reset($this->myPaymentMethods);
			while( list(, $thePaymentMethod) = @each($this->myPaymentMethods) )
			{
				$this->myXmlData .= "<payment_method>"		. $thePaymentMethod		. "</payment_method>";
			}
			$this->myXmlData .= "</accepted_payment_methods>";

			// Debug mode
			$this->myXmlData .= "<debug_mode>"		. $this->myDebugMode	. "</debug_mode>";

			// Test mode
			$this->myXmlData .= "<test_mode>"		. $this->myTestMode		. "</test_mode>";

			// Language
			$this->myXmlData .= "<language>"		. $this->myLanguage		. "</language>";

			$this->myXmlData .= "</database_overrides>";

			// Footer
			$this->myXmlData .= "</payread_post_api_0_2>";
		}
	}
?>
