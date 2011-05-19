<?php


// Dont allow direct linking
( defined('_JEXEC') || defined( '_VALID_MOS' ) ) or die( 'Direct Access to this location is not allowed.' );
class processor_sagepay extends XMLprocessor
	{
		function info()
		{	
		$info = array();
		$info['name']			= 'sagepay';
		$info['longname']		= 'Sage Pay';/*_AEC_PROC_INFO_RXRM_LNAME;*/
		$info['statement']		= 'Sage Pay';/*_AEC_PROC_INFO_RXRM_STMNT;*/
		$info['description']	= 'Sage Pay';/*_DESCRIPTION_REALEX;*/
		$info['currencies']		= 'EUR,USD,GBP,AUD,CAD,JPY,NZD,CHF,HKD,SGD,SEK,DKK,PLN,NOK,HUF,CZK,MXN,ILS,BRL,MYR,PHP,TWD,THB,ZAR';
		$info['languages']		= AECToolbox::getISO3166_1a2_codes();
		$info['cc_list']		= 'visa,mastercard';
		$info['recurring']		= 0;
		$info['secure']				= 0;
		return $info;
			
		}

		function settings()
		{
			$settings = array();
			$settings['vid']		= 'joomextn';
			/*$settings['account']		= 'youraccount'; */
			$settings['secret']		= '';
			$settings['testmode']		= 1;
			$settings['threed'] = 0;
			$settings['currency']		= 'EUR';
			$settings['country']			= 'GB';
			return $settings;
		}

		function backend_settings()
		{
			$settings = array();

			$settings['vid']		= array( 'inputC' );
		/*	$settings['account']		= array( 'inputC' ); */
			$settings['secret']		= array( 'inputC' );
			$settings['testmode']				= array( 'list_yesno' );
			$settings['threed'] 				= array( 'list_yesno' );
			$settings['currency']				= array( 'list_currency' );
			$settings['country'] 				= array( 'list_country' );

		$country_sel = array();
		$country_sel[] = JHTML::_('select.option', 'GB','GB' );
		//$country_sel[] = JHTML::_('select.option', 'UK', 'UK' );

		$settings['lists']['country'] = JHTML::_( 'select.genericlist', $country_sel, 'sagepay_country', 'size="2"', 'value', 'text', $this->settings['country'] );

		$settings = AECToolbox::rewriteEngineInfo( null, $settings );


		/*
		$settings['testmode']	= array( 'list_yesno' );
		$settings['currency']	= array( 'list_currency' );
		$settings['custId']		= array( 'inputC' );
		$settings['SiteTitle']	= array( 'inputC' );
		$settings['item_name']	= array( 'inputE' );

 		$rewriteswitches		= array( 'cms', 'user', 'expiration', 'subscription', 'plan');
		$settings = AECToolbox::rewriteEngineInfo( $rewriteswitches, $settings );
		*/
		return $settings;
		}
		
		function createRequestXML( $request )	
		{

		//$timestamp = strftime("%Y%m%d%H%M%S");
		//mt_srand((double)microtime()*1000000);

		//$var['invoice'] = $request->invoice->invoice_number;


		//$orderid = $timestamp.mt_rand(1, 999);
		//$amt = round(100*$request->items->total->cost['amount']);
		//$curr = $this->settings['currency']	;
		//$accnt = $this->settings['account'];
		
		//Values 
		$strProtocol="2.23";
		$strVendorName=$this->settings['vid'];
		$strAuthoriseVendorTxCode =$request->invoice->invoice_number;
		$sngAuthoriseAmount=$request->items->total->cost['amount'];
		$strAuthoriseDescription='Decsription of the Item';
		$strSecurityKey =$this->settings['secret'];
		$strCardType=$request->int_var['params']['cardType'];
		$strCardHolder=$request->metaUser->cmsUser->username;
		$strCardNumber=$request->int_var['params']['cardNumber'];
		$strExpiryDate=$request->int_var['params']['expirationMonth'].substr($request->int_var['params']['expirationYear'],-2);
		$strCV2=$request->int_var['params']['cardVV2'];
		$strCurrency =$this->settings['currency'];

		$strCardHolder=$request->int_var['params']['billFirstName'] . ' ' . $request->int_var['params']['billLastName'];
		

		$strFname=$request->int_var['params']['billFirstName'];
		$strSname=$request->int_var['params']['billLastName'];
		$strAddr=$request->int_var['params']['billAddress'];
		$strCity=$request->int_var['params']['billCity'];
		$strPin=$request->int_var['params']['billZip'];
		$strState=$request->int_var['params']['billState'];
		$strCountry=$request->int_var['params']['billCountry'];
		
		if ($this->settings['threed'] == 1)
			$apply3D="1";
		else
			$apply3D="0";
			
		/*$strStartDate=cleanInput($_REQUEST["StartDate"],"Number");
		$strIssueNumber=cleanInput($_REQUEST["IssueNumber"],"Number"); */
		
		/*
		$ss= $this->settings['secret'];
		$cno =$request->int_var['params']['cardNumber'];
		$expdate = $request->int_var['params']['expirationMonth'].substr($request->int_var['params']['expirationYear'],-2); 
		$cvv2 = $request->int_var['params']['cardVV2'];
		$cardtype = $request->int_var['params']['cardType'];
		*/
		
		//substr($request->int_var['params']['expirationYear'],-2)
		
		//$cardname = $request->metaUser->cmsUser->username;
		//$cardtype ="MC";

		
		//$tmp = "$timestamp.$mid.$orderid.$amt.$curr";
		//$tmp = "$timestamp.$mid.$orderid.$amt.$curr.$cno";

		//$md5hash = md5($tmp);
		//$tmp = "$md5hash.$ss";
		//$md5hash = md5($tmp);

		
		// Create and initialise XML parser
		//$xml_parser = xml_parser_create();
		//xml_set_element_handler($xml_parser, "startElement", "endElement");
		//xml_set_character_data_handler($xml_parser, "cDataHandler");

		//A number of variables are needed to generate the request xml that is send to Realex Payments.
		

			$strPost="VPSProtocol=" . $strProtocol;
			/*$strPost=$strPost . "&TxType=AUTHORISE";*/
			$strPost=$strPost . "&TxType=PAYMENT";
			$strPost=$strPost . "&Vendor=" . $strVendorName;
			$strPost=$strPost . "&VendorTxCode=" . $strAuthoriseVendorTxCode;
			$strPost=$strPost . "&Amount=" . number_format($sngAuthoriseAmount,2);
			$strPost=$strPost . "&Description=" . $strAuthoriseDescription;
			/*$strPost=$strPost . "&RelatedVPSTxId=" . $strVPSTxId; 
			$strPost=$strPost . "&RelatedVendorTxCode=" . $strVendorTxCode; */
			$strPost=$strPost . "&RelatedSecurityKey=" . $strSecurityKey;
			/* $strPost=$strPost . "&RelatedTxAuthNo=" . $strTxAuthNo; 
			$strPost=$strPost . "&ApplyAVSCV2=0"; */
			$strPost=$strPost . "&Currency=" . $strCurrency;
			$strPost=$strPost . "&CardHolder=" . $strCardHolder;
			$strPost=$strPost . "&CardNumber=" . $strCardNumber;
			$strPost=$strPost . "&CardType=" . $strCardType;
			$strPost=$strPost . "&ExpiryDate=" . $strExpiryDate;
			$strPost=$strPost . "&CV2=" . $strCV2;
			$strPost=$strPost . "&ClientIPAddress=" . $_SERVER['REMOTE_ADDR'];
			$strPost=$strPost . "&Apply3DSecure=" . $apply3D;

			$strPost=$strPost . "&BillingSurname=" . $strSname;
			$strPost=$strPost . "&BillingFirstnames=" . $strFname;
			$strPost=$strPost . "&BillingAddress1=" . $strAddr;
			$strPost=$strPost . "&BillingCity=" . $strCity;
			$strPost=$strPost . "&BillingPostCode=" . $strPin;
			$strPost=$strPost . "&BillingState=" . $strState;
			$strPost=$strPost . "&BillingCountry=" . $strCountry;

			$strPost=$strPost . "&DeliverySurname=" . $strSname;
			$strPost=$strPost . "&DeliveryFirstnames=" . $strFname;
			$strPost=$strPost . "&DeliveryAddress1=" . $strAddr;
			$strPost=$strPost . "&DeliveryCity=" . $strCity;
			$strPost=$strPost . "&DeliveryPostCode=" . $strPin;
			$strPost=$strPost . "&DeliveryState=" . $strState;
			$strPost=$strPost . "&DeliveryCountry=" . $strCountry;			

			/*
			$strPost=$strPost . "&DeliverySurname=Paul";
			$strPost=$strPost . "&DeliveryFirstnames=Swagata";
			$strPost=$strPost . "&DeliveryAddress1=AD501";
			$strPost=$strPost . "&DeliveryCity=Kolkata";
			$strPost=$strPost . "&DeliveryPostCode=55555";
			$strPost=$strPost . "&DeliveryCountry=IN";
			*/
			

return $strPost;
}
function transmitRequestXML( $xml, $request )
{
	if ( $this->settings['testmode'] ) {
			$url = 'https://test.sagepay.com/Simulator/VSPDirectGateway.asp';
			//$url = 'https://test.sagepay.com/gateway/service/vspdirect-register.vsp';
		} else {
			$url = 'https://live.sagepay.com/gateway/service/vspdirect-register.vsp';
		}
		$arrResponse = array();

// Set a one-minute timeout for this script
	set_time_limit(60);

	// Initialise output variable
	$output = array();

	// Open the cURL session
	$curlSession = curl_init();

	// Set the URL
	curl_setopt ($curlSession, CURLOPT_URL, $url);
	// No headers, please
	curl_setopt ($curlSession, CURLOPT_HEADER, 0);
	// It's a POST request
	curl_setopt ($curlSession, CURLOPT_POST, 1);
	// Set the fields for the POST
	curl_setopt ($curlSession, CURLOPT_POSTFIELDS, $xml);
	// Return it direct, don't print it out
	curl_setopt($curlSession, CURLOPT_RETURNTRANSFER,1); 
	// This connection will timeout in 30 seconds
	curl_setopt($curlSession, CURLOPT_TIMEOUT,30); 
	//The next two lines must be present for the kit to work with newer version of cURL
	//You should remove them if you have any problems in earlier versions of cURL
    curl_setopt($curlSession, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($curlSession, CURLOPT_SSL_VERIFYHOST, 1);

	//Send the request and store the result in an array
	
	$rawresponse = curl_exec($curlSession);

		
	//	$arrResponse=$this->transmitRequest( $url, '', $xml );
	//echo 'ppp';
//print_r($request->metaUser->cmsUser);
//echo 'xxxx';	
	//print_r($rawresponse);
	//echo 'ppp';
//Store the raw response for later as it's useful to see for integration and understanding 
	$_SESSION["rawresponse"]=$rawresponse;
	//Split response into name=value pairs
	$response = split(chr(10), $rawresponse);
	// Check that a connection was made
	if (curl_error($curlSession)){
		// If it wasn't...
		$output['Status'] = "FAIL";
		$output['StatusDetail'] = curl_error($curlSession);
	}

	// Close the cURL session
	curl_close ($curlSession);

	// Tokenise the response
	for ($i=0; $i<count($response); $i++){
		// Find position of first "=" character
		$splitAt = strpos($response[$i], "=");
		// Create an associative (hash) array with key/value pairs ('trim' strips excess whitespace)
		$output[trim(substr($response[$i], 0, $splitAt))] = trim(substr($response[$i], ($splitAt+1)));
	} // END for ($i=0; $i<count($response); $i++)

	// Return the output


//Tidy it up
//$arrResponse = eregi_replace ( "[[:space:]]+", " ", $response );
//$arrResponse = eregi_replace ( "[\n\r]", "", $response );


//$arrStatus=split(" ",$arrResponse["Status"]);
$strStatus=$output["Status"];
//echo 'ooooo';
//print_r($arrResponse);
//echo 'ooooo';
//echo '</br>';
if ($strStatus == "OK")
{
	//An OK status mean that the transaction has been successfully Authoriseed **
	$strResult="SUCCESS : The transaction was Authorised successfully and a new Authorise transaction was created.";
	//Get the other values from the POST for storage in the database
	$strAuthorise['VPSTxId']=$arrResponse["VPSTxId"];
	$strAuthorise['TxAuthNo']=$arrResponse["TxAuthNo"];
	$strAuthorise['SecurityKey']=$arrResponse["SecurityKey"];
	$strAuthorise['AVSCV2']=$arrResponse["AVSCV2"];
	$strAuthorise['AddressResult']=$arrResponse["AddressResult"];
	$strAuthorise['PostCodeResult']=$arrResponse["PostCodeResult"];				
	$strAuthorise['CV2Result']=$arrResponse["CV2Result"];

//print_r($strAuthorise);
	
	$response1['valid'] = 1;
	$response1['invoiceparams'] = array( 'VPSTxId' => $strAuthorise['VPSTxId'], 'TxAuthNo' => $strAuthorise['TxAuthNo'], 'AVSCV2'=>$strAuthorise['AVSCV2'] );
	$response1['raw'] = $rawresponse;
}
else{
	$response['valid'] = 0;
	$response1['error']  = $output['StatusDetail'];
	$response1['raw'] = $rawresponse;
}
return $response1;

}

	function checkoutform()
	{
		

		
	//define( '_AEC_USERFORM_BILLZIP_NAME', 'Zip');
	//define( '_AEC_USERFORM_BILLZIP_DESC', 'Zip');
		$var = $this->getUserform();

		$values = array( 'firstname', 'lastname' );
		$values = array_merge( $values, array( 'address', 'city', 'zip', 'country_list' ) );
		$var = $this->getUserform( $var, $values, $request->metaUser );
		
		//$var['params']['billInfo1']			= array( 'div', 'b122', '&nbsp' );
		
		//$var['params']['billInfo1']			= array( 'div', 'b123', '<hr></br>Credit Card Details' );

		$var = $this->getCCform( $var, array( 'card_type','card_number', 'card_exp_month', 'card_exp_year', 'card_cvv2' ),null);
		
		//$var = $this->getCCform( $var );
		return $var;
	}

}
?>
