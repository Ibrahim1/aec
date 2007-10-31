<?php
// Dont allow direct linking
defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );

/**
* AcctExp Component
* @package AcctExp
* @subpackage processor
* @copyright 2007 Helder Garcia, David Deutsch
* @license http://www.gnu.org/copyleft/gpl.html. GNU Public License
* @author Bruno Pourtier <bruno.pourtier@gmail.com>
**/

class processor_eway extends XMLprocessor
{
	var $processor_name = 'eWay';
	
	function info()
	{
		$info = array();
		$info['name']			= 'eway';
		$info['longname']		= _CFG_EWAY_LONGNAME;
		$info['statement']		= _CFG_EWAY_STATEMENT;
		$info['description']	= _CFG_EWAY_DESCRIPTION;
		$info['cc_list']		= 'visa,mastercard';
		$info['recurring']		= 0;

		return $info;
	}

	function settings()
	{
		$settings = array();
		$settings['testmode']		= "1";
		$settings['custId']			= "87654321";
		$settings['tax']			= "10";
		$settings['autoRedirect']	= 1;
		$settings['testAmount']		= "00";
		$settings['item_name']		= sprintf( _CFG_PROCESSOR_ITEM_NAME_DEFAULT, '[[cms_live_site]]', '[[user_name]]', '[[user_username]]' );
		$settings['rewriteInfo']	= ''; // added mic
		$settings['SiteTitle']		= '';

		return $settings;
	}

	function backend_settings( $cfg )
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
	/*
	function createGatewayLink( $int_var, $cfg, $metaUser, $new_subscription )
	{
		global $mosConfig_live_site;

		//URL returned by eWay
		$return_url = AECToolbox::deadsureURL("/index.php?option=com_acctexp&amp;task=ewaynotification");

		//Genere un identifiant unique pour la transaction
		$my_trxn_number = uniqid( "eway_" );

		$order_total = $int_var['amount'] * 100;

		$var = array(	"post_url" => "https://www.eWAY.com.au/gateway/payment.asp",
						"ewayCustomerID" => $cfg['custId'],
						"ewayTotalAmount" => $order_total,
						"ewayCustomerFirstName" => $metaUser->cmsUser->username,
						"ewayCustomerLastName" => $metaUser->cmsUser->name,
						"ewayCustomerInvoiceDescription" => AECToolbox::rewriteEngine( $cfg['item_name'], $metaUser, $new_subscription ),
						"ewayCustomerInvoiceRef" => $int_var['invoice'],
						"ewayOption1" => $metaUser->cmsUser->id, //Send in option1, the id of the user
						"ewayOption2" => $int_var['invoice'], //Send in option2, the invoice number
						"eWAYTrxnNumber" => $my_trxn_number,
						"eWAYAutoRedirect" => $cfg['autoRedirect'],
						"eWAYSiteTitle" => $cfg['SiteTitle'],
						"eWAYURL" => $return_url
					);

		return $var;
	}

	function parseNotification( $post, $cfg )
	{
		$eWAYResponseText = $post['eWAYresponseText'];
		$eWAYTrxnNumber = $post['ewayTrxnNumber'];
		$eWAYResponseCode = $post['eWAYresponseCode'];
		$ewayTrxnReference = $post['ewayTrxnReference'];
		$eWAYAuthCode = $post['eWAYAuthCode'];
		$total = $post['eWAYReturnAmount'];
		$userid = $post['eWAYoption1'];

		$response = array();
		$response['invoice'] = $post['eWAYoption2'];
		if ( $post['ewayTrxnStatus'] == "True" && isset( $eWAYAuthCode ) ) {
			$response['valid'] = 1;
		} else {
			$response['valid'] = 0;
		}

		return $response;
	}
	function processRequest($cfg,$int_var,$new_subscription,$metaUser,$card){
		
		//Genere un identifiant unique pour la transaction
		$my_trxn_number = uniqid( "eway_" );
		
		$order_total = $int_var['amount'] * 100;
		echo 'int var amount: '.$int_var['amount'];
		echo 'amount before request: '.$order_total;
		
		$nodes = array(	"ewayCustomerID" => $cfg['custId'],
						"ewayTotalAmount" => $order_total,
						"ewayCustomerFirstName" => $metaUser->cmsUser->username,
						"ewayCustomerLastName" => $metaUser->cmsUser->name,
						"ewayCustomerInvoiceDescription" => AECToolbox::rewriteEngine( $cfg['item_name'], $metaUser, $new_subscription ),
						"ewayCustomerInvoiceRef" => $int_var['invoice'],
						"ewayOption1" => $metaUser->cmsUser->id, //Send in option1, the id of the user
						"ewayOption2" => $int_var['invoice'], //Send in option2, the invoice number
						"ewayTrxnNumber" => $my_trxn_number,
						"ewaySiteTitle" => $cfg['SiteTitle'],
						"ewayCardHoldersName" => $card['card_name'],
						"ewayCardNumber" => $card['card_no'], 
						"ewayCardExpiryMonth" => $card['expiry_m'], 
						"ewayCardExpiryYear" => $card['expiry_y'],
						"ewayCustomerEmail" => $metaUser->cmsUser->email, 
						"ewayCustomerAddress" => '', 
						"ewayCustomerPostcode" => '', 
						"ewayOption3" => ''
					);
		
		
		
		$ewayConnector = new GatewayConnector("https://www.eway.com.au/gateway/xmltest/testpage.asp");
		$ewayRequest = new GatewayRequest("ewaygateway",$nodes);
		
		$result = null;
		
		if($ewayConnector->processRequest($ewayRequest)){
			$response = $ewayConnector->Response();
			
			list($message,$code) = split(",",$response->getNode('ewayTrxnError'));
			
			$result['message'] = $message;
			$result['code'] = $code;
			$result['transaction_no'] = trim($response->getNode('ewayTrxnNumber'));
			$result['amount_paid'] = trim($response->getNode('ewayReturnAmount'))/100;
			
			if(trim($response->getNode('ewayTrxnStatus')) == "True"){
				$result['status'] = true;
			}else{
				$result['status'] = false;
			}
		}else{
			$result['status'] = false;
			$result['mesasage'] = "Could not connect to payment gateway";
			$result['code'] = -1;
			$result['transaction_no'] = '';
			$result['amount_paid'] = 00;
		}
		return $result;
	}*/
	function createRequestXML($int_var, $settings, $metaUser, $new_subscription){
		
		$order_total = $int_var['amount'] * 100;
		$my_trxn_number = uniqid( "eway_" );
		
		$nodes = array(	"ewayCustomerID" => $settings['custId'],
					"ewayTotalAmount" => $order_total,
					"ewayCustomerFirstName" => $metaUser->cmsUser->username,
					"ewayCustomerLastName" => $metaUser->cmsUser->name,
					"ewayCustomerInvoiceDescription" => AECToolbox::rewriteEngine( $settings['item_name'], $metaUser, $new_subscription ),
					"ewayCustomerInvoiceRef" => $int_var['invoice'],
					"ewayOption1" => $metaUser->cmsUser->id, //Send in option1, the id of the user
					"ewayOption2" => $int_var['invoice'], //Send in option2, the invoice number
					"ewayTrxnNumber" => $my_trxn_number,
					"ewaySiteTitle" => $settings['SiteTitle'],
					"ewayCardHoldersName" => $int_var['params']['cardName'],
					"ewayCardNumber" => $int_var['params']['cardNumber'], 
					"ewayCardExpiryMonth" => $int_var['params']['expirationMonth'], 
					"ewayCardExpiryYear" => $int_var['params']['expirationYear'],
					"ewayCustomerEmail" => $metaUser->cmsUser->email, 
					"ewayCustomerAddress" => '', 
					"ewayCustomerPostcode" => '', 
					"ewayOption3" => ''
					);
		$xml = '<ewaygateway>';
		
		foreach($nodes as $name => $value){
			$xml .= "<" . $name . ">" . $value . "</" . $name . ">";
		}
		$xml .= '</ewaygateway>';
		
		return $xml;
	}
	function transmitRequestXML($xml, $int_var, $settings, $metaUser, $new_subscription){
		if($settings['testmode']){
			$url = 'https://www.eway.com.au/gateway/xmltest/testpage.asp';
		}else{
			$url = 'https://www.eway.com.au/gateway/xmlpayment.asp';
		}
		
		if($objResponse = simplexml_load_string($this->transmitRequest($url,'',$xml))){
			//echo '<pre>'.print_r($objResponse,true).'</pre>';
			
			$response['amount_paid'] = $objResponse->ewayReturnAmount / 100;
			$response['invoice'] = $objResponse->ewayTrxnOption2;
			$response['raw'] = $objResponse->ewayTrxnError;
			
			if($objResponse->ewayTrxnStatus == 'True'){
				$response['valid'] = 1;
			}else{
				$response['valid'] = 0;
				$response['error'] = $objResponse->ewayTrxnError;
			}
		}else{
			$response['valid'] = 0;
			$response['error'] = _CFG_PROCESSOR_CONNECTION_ERROR;
		}
		return $response;
	}
}
?>