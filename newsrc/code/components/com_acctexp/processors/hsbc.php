<?php
/**
 * @version $Id: hsbc.php
 * @package AEC - Account Control Expiration - Membership Manager
 * @subpackage Processors - HSBC
 * @copyright 2007-2010 Copyright (C) David Deutsch
 * @author David Deutsch <skore@skore.de> & Team AEC - http://www.valanx.org
 * @license GNU/GPL v.2 http://www.gnu.org/licenses/old-licenses/gpl-2.0.html or, at your option, any later version
 */

// Dont allow direct linking
( defined('_JEXEC') || defined( '_VALID_MOS' ) ) or die( 'Direct Access to this location is not allowed.' );

class processor_hsbc extends XMLprocessor
{
	function info()
	{
		$info = array();
		$info['name'] = 'hsbc';
		$info['longname'] = _CFG_HSBC_LONGNAME;
		$info['statement'] = _CFG_HSBC_STATEMENT;
		$info['description'] = _CFG_HSBC_DESCRIPTION;
		$info['currencies'] = AECToolbox::aecCurrencyField( true, true, true, true );
		$info['cc_list'] = "visa,mastercard,discover,americanexpress,echeck,jcb,dinersclub";
		$info['recurring'] = 2;
		$info['secure'] = 1;

		return $info;
	}

	function getActions( $invoice, $subscription )
	{
		$actions = parent::getActions( $invoice, $subscription );

		if ( ( $subscription->status == 'Cancelled' ) || ( $invoice->transaction_date == '0000-00-00 00:00:00' ) ) {
			if ( isset( $actions['cancel'] ) ) {
				unset( $actions['cancel'] );
			}
		}

		return $actions;
	}

	function settings()
	{
		$settings = array();
		$settings['testmode']			= 0;
		$settings['clientid']			= "clientid";
		$settings['name']				= "name";
		$settings['password']			= "password";
		$settings['pas']			= 0;
		$settings['pas_id']				= "";
		$settings['pas_url']			= "https://www.ccpa.hsbc.com/ccpa";
		$settings['currency']			= "USD";
		$settings['promptAddress']		= 0;
		$settings['item_name']			= sprintf( _CFG_PROCESSOR_ITEM_NAME_DEFAULT, '[[cms_live_site]]', '[[user_name]]', '[[user_username]]' );

		return $settings;
	}

	function backend_settings()
	{
		$settings = array();
		$settings['testmode']			= array("list_yesno");
		$settings['clientid'] 			= array("inputC");
		$settings['name'] 				= array("inputC");
		$settings['password'] 			= array("inputC");
		$settings['pas']			= array("list_yesno");
		$settings['pas_id'] 			= array("inputC");
		$settings['pas_url'] 			= array("inputC");
		$settings['currency']			= array("list_currency");
		$settings['promptAddress']		= array("list_yesno");
		$settings['item_name']			= array("inputE");

		$settings = AECToolbox::rewriteEngineInfo( null, $settings );

		return $settings;
	}

	function checkoutAction( $request )
	{aecDebug("checkoutAction");
		if ( $this->settings['pas'] && !empty( $request->int_var['params']['cardNumber'] ) && !isset( $request->int_var['params']['CcpaResultsCode'] ) ) {
			$var = $this->createGatewayLink( $request );
aecDebug("preparing PAS form");
			return POSTprocessor::checkoutAction( $request, $var );
		} else {aecDebug("preparing regular checkout");
			if ( $this->settings['pas'] ) {
				$check = aecGetParam( 'CcpaResultsCode', null, true, array( 'int' ) );

				if ( !is_null( $check ) ) {
					if ( $check == 0 ) {
						return parent::checkoutProcess( $request );
					} else {
						$response = array( 'error' => 'Security Check failed, Error Number: ' . $check );
aecDebug($response);
						return parent::checkoutResponse( $request, $response );
					}
				} else {
					$var = $this->createGatewayLink( $request );

					return POSTprocessor::checkoutAction( $request, $var );
				}
			}
		}

		return parent::checkoutAction( $request );
	}

	function createGatewayLink( $request )
	{aecDebug("createGatewayLink");
		$var['post_url']			= $this->settings['pas_url'];

		$var['CardExpiration']		= substr( $request->int_var['params']['expirationYear'], 2, 2 ) . $request->int_var['params']['expirationMonth'];
		$var['CardholderPan']		= $request->int_var['params']['cardNumber'];
		$var['CcpaClientId']		= $this->settings['pas_id'];
		$var['CurrencyExponent']	= AECToolbox::aecCurrencyExp( $this->settings['currency'] );
		$var['MD']					= AECToolbox::rewriteEngineRQ( $this->settings['item_name'], $request );
		$var['PurchaseAmount']		= AECToolbox::getCurrencySymbol( $this->settings['currency'] )." ".$request->int_var['amount'];
		$var['PurchaseAmountRaw']	= (int) ( $request->int_var['amount'] * 100 );
		$var['PurchaseCurrency']	= AECToolbox::aecNumCurrency( $this->settings['currency'] );
		$var['PurchaseDesc']		= $request->invoice->invoice_number;
		$var['ResultUrl']			= AECToolbox::deadsureURL( 'index.php?option=com_acctexp&amp;task=checkout&amp;invoice='.$request->invoice->invoice_number );
aecDebug($var);
		return $var;
	}

	function checkoutform( $request )
	{aecDebug("checkoutform");
		$var = array();

		if ( 0/*$this->settings['pas']*/ ) {
			$values = array( 'card_number', 'card_exp_month', 'card_exp_year' );

			$var = $this->getCCform( $var, $values );
		} else {
			$values = array( 'card_number', 'card_exp_month', 'card_exp_year', 'card_cvv2' );

			$var = $this->getCCform( $var, $values );

			if ( !empty( $this->settings['promptAddress'] ) ) {
				$values = array( 'firstname', 'lastname', 'address', 'city', 'state_usca', 'zip', 'country_list' );
			} else {
				$values = array( 'firstname', 'lastname' );
			}

			$var = $this->getUserform( $var, $values, $request->metaUser );
		}

		return $var;
	}

	function checkoutProcess( $request )
	{aecDebug("checkoutProcess");
		if ( $this->settings['pas'] ) {aecDebug("marking double checkout, preparing PAS");
			$request->invoice->preparePickup( $request->int_var['params'] );
aecDebug($request->invoice->params );
			return array( 'doublecheckout' => true );
		} else {aecDebug("checking out data");
			return parent::checkoutProcess( $request );
		}
	}

	function createRequestXML( $request )
	{aecDebug("createRequestXML");
		// Start xml, add login and transaction key, as well as invoice number
		$content =	'<?xml version="1.0" encoding="utf-8"?>'
					. '<EngineDocList>'
					. '<DocVersion DataType="String">1.0</DocVersion>'
					. '<EngineDoc>'
					. '<ContentType>OrderFormDoc</ContentType>'
					. '<User>'
					. '<ClientId DataType="S32">' . trim( substr( $this->settings['clientid'], 0, 32 ) ) . '</ClientId>'
					. '<Name DataType="String">' . $this->settings['name'] . '</Name>'
					. '<Password DataType="String">' . $this->settings['password'] . '</Password>'
					. '</User>'
					;

		// Instructions
		$content .=	'<Instructions>'
					. '<Pipeline DataType="String">Payment</Pipeline>'
					. '</Instructions>';

		// Add Payment information
		$content .=	'<OrderFormDoc>'
					. '<Id DataType="String">' . $request->invoice->invoice_number . '</Id>'
					. '<Mode DataType="String">' . ( $this->settings['testmode'] ? "Y" : "P" ) . '</Mode>'
					;

		if ( is_array( $request->int_var['amount'] ) ) {
			$pu = $this->convertPeriodUnit( $request->int_var['amount']['period3'], $request->int_var['amount']['unit3'] );

			$content .=	'<PbOrder>'
						. '<OrderType>' . trim( $request->int_var['params']['billCountry'] ) . '</OrderType>'
						. '<OrderFrequencyCycle>' . $pu['unit'] . '</OrderFrequencyCycle>'
						. '<OrderFrequencyInterval>' . $pu['period'] . '</OrderFrequencyInterval>'
						. '</PbOrder>'
						;
		}

		$expirationDate =  $request->int_var['params']['expirationMonth'] . '/' . str_pad( $request->int_var['params']['expirationYear'], 2, '0', STR_PAD_LEFT );

		// Customer/Credit Card Details
		$content .=	'<Consumer>'
					. '<PaymentMech>'
					. '<Type DataType="String">CreditCard</Type>'
					. '<CreditCard>'
					. '<Cvv2Indicator DataType="String">1</Cvv2Indicator>'
					. '<Cvv2Val DataType="String">' . trim( $request->int_var['params']['cardVV2'] ) . '</Cvv2Val>'
					. '<Number DataType="String">' . trim( $request->int_var['params']['cardNumber'] ) . '</Number>'
					. '<Expires DataType="ExpirationDate" Locale="826">' . trim( $expirationDate ) . '</Expires>'
					. '</CreditCard>'
					. '</PaymentMech>'
					;

		$content .=	'<BillTo>'
					. '<Address>'
					;

		if ( !empty( $this->settings['promptAddress'] ) ) {
			$content .=	'<City DataType="String">' . trim( $request->int_var['params']['billCity'] ) . '</City>'
						. '<Country DataType="String">' . trim( $request->int_var['params']['billCountry'] ) . '</Country>'
						. '<FirstName DataType="String">' . trim( $request->int_var['params']['billFirstName'] ) . '</FirstName>'
						. '<LastName DataType="String">' . trim( $request->int_var['params']['billLastName'] ) . '</LastName>'
						. '<PostalCode DataType="String">' . trim( $request->int_var['params']['billZip'] ) . '</PostalCode>'
						. '<StateProv DataType="String">' . trim( $request->int_var['params']['billState'] ) . '</StateProv>'
						;
		} else {
			$content .=	'<FirstName DataType="String">' . trim( $request->int_var['params']['billFirstName'] ) . '</Type>'
						. '<LastName DataType="String">' . trim( $request->int_var['params']['billLastName'] ) . '</LastName>'
						;
		}

		$content .=	'</Address>'
					. '</BillTo>'
					;

		$content .=	'</Consumer>';

		$content .=	 '<Transaction>'
					. '<Type DataType="String">Auth</Type>'
					. '<CurrentTotals>'
					. '<Totals>'
					;

		if ( is_array( $request->int_var['amount'] ) ) {
			$amount = $request->int_var['amount']['amount3'];
		} else {
			$amount = $request->int_var['amount'];
		}

		$content .=	 '<Total DataType="Money" Currency="' . AECToolbox::aecNumCurrency( $request->invoice->currency ) . '">' . ( (int) ( $amount * 100 ) ) . '</Total>';

		$content .=	  '</Totals>'
					. '</CurrentTotals>'
					. '</Transaction>'
					;

		$content .=	'</OrderFormDoc>';

		// Close Request
		$content .=	'</EngineDoc>';
		$content .=	'</EngineDocList>';
aecDebug($content);
		return $content;
	}

	function transmitRequestXML( $xml, $request )
	{
		if ( $this->settings['testmode'] ) {
			$url = "https://www.uat.apixml.netq.hsbc.com/";
		} else {
			$url = "https://www.secure-epayments.apixml.hsbc.com/";
		}

		$response = $this->transmitRequest( $url, "", $xml, 443 );

		$return['valid'] = false;
		$return['raw'] = $response;

		if ( $response ) {
			$resultCode = $this->substring_between($response,'<TRANSACTIONSTATUS>','</TRANSACTIONSTATUS>');

			$code = $this->substring_between($response,'<CCERRCODE>','</CCERRCODE>');
			$text = $this->substring_between($response,'<CCRETURNMSG>','</CCRETURNMSG>');

			switch ( $resultCode ) {
				case "A":
				case "C":
					$return['valid'] = 1;
					break;
				default:
					$return['error'] = $text;
			}

			if ( strcmp( $resultCode, 'Ok' ) === 0) {
				$return['valid'] = 1;
			} else {
				$return['error'] = $text;
			}

		}

		return $return;
	}

	function prepareValidation( $subscription_list )
	{
		return true;
	}

	function validateSubscription( $subscription_id )
	{
		$database = &JFactory::getDBO();

		$subscription = new Subscription( $database );
		$subscription->load( $subscription_id );

		$allowed = array( "Trial", "Active" );

		if ( !in_array( $subscription->status, $allowed ) ) {
			// Do not renew when the account has been canceled
			return false;
		}

		return true;
	}

	function convertPeriodUnit( $period, $unit )
	{
		$return['unit'] = $unit;
		$return['period'] = $period;

		$return = array();
		switch ( $unit ) {
			case 'Y':
				$return['unit'] = 'M';
				$return['period'] = $period*12;
				break;
		}

		return $return;
	}

	function substring_between( $haystack, $start, $end )
	{
		if ( strpos( $haystack, $start ) === false || strpos( $haystack, $end ) === false ) {
			return false;
		 } else {
			$start_position = strpos( $haystack, $start ) + strlen( $start );
			$end_position = strpos( $haystack, $end );
			return substr( $haystack, $start_position, $end_position - $start_position );
		}
	}

}
?>