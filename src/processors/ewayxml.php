<?php
/**
 * @version $Id: ewayxml.php
 * @package AEC - Account Control Expiration - Membership Manager
 * @subpackage Processors - eWay XML
 * @copyright 2006-2008 Copyright (C) David Deutsch
 * @author David Deutsch <skore@skore.de> & Team AEC - http://www.globalnerd.org
 * @license GNU/GPL v.2 http://www.gnu.org/licenses/old-licenses/gpl-2.0.html or, at your option, any later version
 */

// Dont allow direct linking
( defined('_JEXEC') || defined( '_VALID_MOS' ) ) or die( 'Direct Access to this location is not allowed.' );

/**
* AcctExp Component
* @package AEC - Account Control Expiration - Membership Manager
* @subpackage processor
* @copyright 2006-2008 Copyright (C) David Deutsch
* @license GNU/GPL v.2 http://www.gnu.org/licenses/old-licenses/gpl-2.0.html or, at your option, any later version
* @author David Deutsch <skore@skore.de> & Team AEC - http://www.globalnerd.org
**/

class processor_ewayXML extends XMLprocessor
{
	var $processor_name = 'ewayXML';

	function info()
	{
		$info = array();
		$info['name']			= 'ewayXML';
		$info['longname']		= _CFG_EWAYXML_LONGNAME;
		$info['statement']		= _CFG_EWAYXML_STATEMENT;
		$info['description']	= _CFG_EWAYXML_DESCRIPTION;
		$info['cc_list']		= 'visa,mastercard';
		$info['recurring']		= 0;

		return $info;
	}

	function settings()
	{
		$this->settings = array();
		$this->settings['testmode']		= "1";
		$this->settings['custId']		= "87654321";
		$this->settings['tax']			= "10";
		$this->settings['autoRedirect']	= 1;
		$this->settings['testAmount']	= "00";
		$this->settings['item_name']	= sprintf( _CFG_PROCESSOR_ITEM_NAME_DEFAULT, '[[cms_live_site]]', '[[user_name]]', '[[user_username]]' );
		$this->settings['rewriteInfo']	= ''; // added mic
		$this->settings['SiteTitle']	= '';

		return $this->settings;
	}

	function backend_settings()
	{
		$this->settings = array();
		$this->settings['testmode']		= array( 'list_yesno' );
		$this->settings['custId']		= array( 'inputC' );
		$this->settings['autoRedirect']	= array( 'list_yesno' ) ;
		$this->settings['SiteTitle']	= array( 'inputC' );
		$this->settings['item_name']	= array( 'inputE' );

        $this->settings = AECToolbox::rewriteEngineInfo( null, $this->settings );

		return $this->settings;
	}

	function createRequestXML( $request )
	{

		$order_total = $request->int_var['amount'] * 100;
		$my_trxn_number = uniqid( "eway_" );

		$nodes = array(	"ewayCustomerID" => $this->settings['custId'],
					"ewayTotalAmount" => $order_total,
					"ewayCustomerFirstName" => $request->metaUser->cmsUser->username,
					"ewayCustomerLastName" => $request->metaUser->cmsUser->name,
					"ewayCustomerInvoiceDescription" => AECToolbox::rewriteEngine( $this->settings['item_name'], $request->metaUser, $request->new_subscription, $request->invoice ),
					"ewayCustomerInvoiceRef" => $request->int_var['invoice'],
					"ewayOption1" => $request->metaUser->cmsUser->id, //Send in option1, the id of the user
					"ewayOption2" => $request->int_var['invoice'], //Send in option2, the invoice number
					"ewayTrxnNumber" => $my_trxn_number,
					"ewaySiteTitle" => $this->settings['SiteTitle'],
					"ewayCardHoldersName" => $request->int_var['params']['cardHolder'],
					"ewayCardNumber" => $request->int_var['params']['cardNumber'],
					"ewayCardExpiryMonth" => $request->int_var['params']['expirationMonth'],
					"ewayCardExpiryYear" => $request->int_var['params']['expirationYear'],
					"ewayCustomerEmail" => $request->metaUser->cmsUser->email,
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

	function transmitRequestXML( $xml, $request )
	{
		if ( $this->settings['testmode'] ) {
			$url = 'https://www.eway.com.au/gateway/xmltest/testpage.asp';
		} else {
			$url = 'https://www.eway.com.au/gateway/xmlpayment.asp';
		}
		$response = array();

		if ( $objResponse = simplexml_load_string( $this->transmitRequest( $url, '', $xml ) ) ) {
			$response['amount_paid'] = $objResponse->ewayReturnAmount / 100;
			$response['invoice'] = $objResponse->ewayTrxnOption2;
			//$response['raw'] = $objResponse->ewayTrxnError;

			if($objResponse->ewayTrxnStatus == 'True'){
				$response['valid'] = 1;
			}else{
				$response['valid'] = 0;
				$response['error'] = $objResponse->ewayTrxnError;
			}
		}else{
			$response['valid'] = 0;
			$response['error'] = _CFG_EWAYXML_CONNECTION_ERROR;
		}

		return $response;
	}

	function checkoutform()
	{
		$var['params']['cardHolder'] = array( 'inputC', _AEC_CCFORM_CARDHOLDER_NAME, _AEC_CCFORM_CARDHOLDER_NAME, '');
		// Request the Card number
		$var['params']['cardNumber'] = array( 'inputC', _AEC_CCFORM_CARDNUMBER_NAME, _AEC_CCFORM_CARDNUMBER_NAME, '');

		// Create a selection box with 12 months
		$months = array();
		for( $i = 1; $i < 13; $i++ ){
			$month = str_pad( $i, 2, "0", STR_PAD_LEFT );
			$months[] = mosHTML::makeOption( $month, $month );
		}

		$var['params']['lists']['expirationMonth'] = mosHTML::selectList($months, 'expirationMonth', 'size="4"', 'value', 'text', 0);
		$var['params']['expirationMonth'] = array( 'list', _AEC_CCFORM_EXPIRATIONMONTH_NAME, _AEC_CCFORM_EXPIRATIONMONTH_DESC);

		// Create a selection box with the next 10 years
		$year = date('Y');
		$years = array();
		for( $i = $year; $i < $year + 10; $i++ ) {
			$years[] = mosHTML::makeOption( $i, $i );
		}

		$var['params']['lists']['expirationYear'] = mosHTML::selectList($years, 'expirationYear', 'size="4"', 'value', 'text', 0);
		$var['params']['expirationYear'] = array( 'list', _AEC_CCFORM_EXPIRATIONYEAR_NAME, _AEC_CCFORM_EXPIRATIONYEAR_DESC);

		return $var;
	}

	function doTheCurl( $url, $content )
	{
		$ch = curl_init();
		curl_setopt( $ch, CURLOPT_URL, $url );
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
		curl_setopt( $ch, CURLOPT_HTTPHEADER, Array("Content-Type: text/xml") );
		curl_setopt( $ch, CURLOPT_POST, 1 );
		curl_setopt( $ch, CURLOPT_POSTFIELDS, $content );
		curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, FALSE );
		$response = curl_exec( $ch );
		curl_close( $ch );

		return $response;
	}
}
?>
