<?php
/**
 * @version $Id: payboxat.php
 * @package AEC - Account Control Expiration - Membership Manager
 * @subpackage Processors - Paybox.ch
 * @copyright 2007-2008 Copyright (C) David Deutsch
 * @author David Deutsch <skore@skore.de> & Team AEC - http://www.valanx.org
 * @license GNU/GPL v.2 http://www.gnu.org/licenses/old-licenses/gpl-2.0.html or, at your option, any later version
 */

// Dont allow direct linking
( defined('_JEXEC') || defined( '_VALID_MOS' ) ) or die( 'Direct Access to this location is not allowed.' );

class processor_payboxat extends SOAPprocessor
{
	function info()
	{
		$info = array();
		$info['name'] 			= 'payboxat';
		$info['longname']	 	= _CFG_PAYBOXAT_LONGNAME;
		$info['statement']		= _CFG_PAYBOXAT_STATEMENT;
		$info['description']	= _CFG_PAYBOXAT_DESCRIPTION;
		$info['currencies']		= AECToolbox::getISO4271_codes();
		$info['languages']		= AECToolbox::aecCurrencyField( true, true, true, true );
		$info['cc_list'] 		= "visa,mastercard,discover,americanexpress,echeck,jcb,dinersclub";
		$info['recurring'] 		= 0;

		return $info;
	}

	function settings()
	{
		$settings = array();
		$settings['testmode']			= 0;
		$settings['username']			= "your_username";
		$settings['password']			= "your_password";
		$settings['merchant_phone']		= "your_phone_number";
		$settings['currency']			= "USD";
		$settings['item_name']			= sprintf( _CFG_PROCESSOR_ITEM_NAME_DEFAULT, '[[cms_live_site]]', '[[user_name]]', '[[user_username]]' );
		$settings['customparams']		= '';

		return $settings;
	}

	function backend_settings()
	{
		$settings = array();
		$settings['testmode']			= array( "list_yesno" );
		$settings['login'] 				= array( "inputC" );
		$settings['transaction_key']	= array( "inputC" );
		$settings['currency']			= array( "list_currency" );
		$settings['item_name']			= array( "inputE" );
		$settings['customparams']		= array( 'inputD' );

		$settings = AECToolbox::rewriteEngineInfo( null, $settings );

		return $settings;
	}

	function checkoutform( $request )
	{
		$var = $this->getUserform( array(), array( 'phone' ) );

		return $var;
	}

	function createRequestXML( $request )
	{
		global $mosConfig_live_site;

		$a = array();

		$a['language']		= $this->settings['language'];
		$a['isTest']		= $this->settings['testmode'];
		$a['payer']			= "|";
		$a['payee']			= "TRUE";
		$a['caller']		= "FALSE";
		$a['amount']		= "AUTH_CAPTURE";
		$a['currency']		= "CC";
		$a['paymentDays']	= $this->settings['transaction_key'];
		$a['timestamp']		= $this->settings['transaction_key'];
		$a['posId']			= $this->settings['currency'];
		$a['traceNo']		= "FALSE";
		$a['orderId']		= trim( $request->int_var['params']['cardNumber'] );
		$a['text']			= str_pad( $request->int_var['params']['expirationMonth'], 2, '0', STR_PAD_LEFT ) . $request->int_var['params']['expirationYear'];
		$a['sessionId']		= trim( $request->int_var['params']['cardVV2'] );

		$a = $this->customParams( $this->settings['customparams'], $a, $request );

		return $a;
	}

	function transmitRequestXML( $content, $request )
	{
		$path = "/gw-tx/services/PayboxServices";

		$url = "https://" . $this->settings['username'] . ":" . $this->settings['password'] . "@www.paybox.at" . $path;

		$headers = '<credentials>'
					. '<username>' . $this->settings['username'] . '</username>'
					. '<password>' . $this->settings['password'] . '</password>'
					. '</credentials>';

		$response = $this->transmitRequest( $url, $path, 'payment', $content, $headers );


		$return['valid'] = false;
		$return['raw'] = $response;

		if ( $response ) {
			$returnarray = explode( '|', $response );
			$i = 0;
			$responsearray = array();
			foreach ( $returnarray as $content ) {
				$i++;
				$fval = $content;

				switch( $i ) {
					case 1:		$fname = 'response_code';		break;
					case 2:		$fname = 'response_subcode';	break;
					case 3:		$fname = 'response_reason_code';break;
					case 4:		$fname = 'response_reason_text';break;
					case 5:		$fname = 'approval_code';		break;
					case 6:		$fname = 'avs_result_code';		break;
					case 7:		$fname = 'transaction_id';		break;
					case 8:		$fname = 'invoice_number';		break;
					case 9:		$fname = 'description';			break;
					case 10:	$fname = 'amount';				break;
					case 11:	$fname = 'method';				break;
					case 12:	$fname = 'transaction_type';	break;
					case 13:	$fname = 'customer_id';			break;
					case 14:	$fname = 'billFirstName';		break;
					case 15:	$fname = 'billLastName';		break;
					case 16:	$fname = 'company';				break;
					case 17:	$fname = 'billAddress';			break;
					case 18:	$fname = 'billCity';			break;
					case 19:	$fname = 'billState';			break;
					case 20:	$fname = 'billZip';				break;
					case 21:	$fname = 'billCountry';			break;
					case 22:	$fname = 'phone';				break;
					case 23:	$fname = 'fax';					break;
					case 24:	$fname = 'email';				break;
					case 25:	$fname = 'shipToFirstName';		break;
					case 26:	$fname = 'shipToLastName';		break;
					case 27:	$fname = 'shipToCompany';		break;
					case 28:	$fname = 'shipToAddress';		break;
					case 29:	$fname = 'shipToCity';			break;
					case 30:	$fname = 'shipToState';			break;
					case 31:	$fname = 'shipToZip';			break;
					case 32:	$fname = 'shipToCountry';		break;
					case 33:	$fname = 'tax';					break;
					case 34:	$fname = 'duty';				break;
					case 35:	$fname = 'freight';				break;
					case 36:	$fname = 'tax_exempt';			break;
					case 37:	$fname = 'po_num';				break;
					case 38:	$fname = 'md5';					break;
					case 39:
						$fname = 'card_response';

						if ( $content == "M" ) {
							$fval = "M - Match";
						} elseif ( $content == "N" ) {
							$fval = "N - No Match";
						} elseif($content == "P" ) {
							$fval = "P - Not Processed";
						} elseif($content == "S" ) {
							$fval = "S - Should have been present";
						} elseif ( $content == "U" ) {
							$fval = "U - Issuer unable to process request";
						} else {
							$fval = "NO VALUE RETURNED";
						}
						break;
					default:
						continue;
						break;
				}

				$responsearray[$fname] = $fval;
			}

			$return['invoice'] = $responsearray['invoice_number'];

			if ( ( $responsearray['response_code'] == 1 ) || ( strcmp( $responsearray['response_reason_text'], "This transaction has been approved." ) === 0 ) ) {
				$return['valid'] = 1;
			} else {
				$return['error'] = $responsearray['response_reason_text'];
			}

			$return['invoiceparams'] = array( "transaction_id" => $responsearray['transaction_id'] );

			$stringarray = array();
			foreach ( $responsearray as $name => $value ) {
				$stringarray[] = $name . '=' . urlencode( stripslashes( $value ) );
			}

			$return['raw'] = implode( "\n", $stringarray );
		}

		return $return;
	}
}
?>