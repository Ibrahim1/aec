<?php
// Copyright (C) 2006-2007 David Deutsch
// All rights reserved.
// This source file is part of the Account Expiration Control Component, a  Joomla
// custom Component By Helder Garcia and David Deutsch - http://www.globalnerd.org
//
// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License (GPL)
// as published by the Free Software Foundation; either version 2
// of the License, or (at your option) any later version.
//
// Please note that the GPL states that any headers in files and
// Copyright notices as well as credits in headers, source files
// and output (screens, prints, etc.) can not be removed.
// You can extend them with your own credits, though...
//
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with this program; if not, write to the Free Software
// Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
//
// The "GNU General Public License" (GPL) is available at
// http://www.gnu.org/copyleft/gpl.html.

// Dont allow direct linking
defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );

class processor_authorize_arb extends XMLprocessor
{
	function info()
	{
		$info = array();
		$info['name'] = 'authorize_arb';
		$info['longname'] = _CFG_AUTHORIZE_ARB_LONGNAME;
		$info['statement'] = _CFG_AUTHORIZE_ARB_STATEMENT;
		$info['description'] = _CFG_AUTHORIZE_ARB_DESCRIPTION;
		$info['currencies'] = 'AFA,DZD,ADP,ARS,AMD,AWG,AUD,AZM,BSD,BHD,THB,PAB,BBD,BYB,BEF,BZD,BMD,VEB,BOB,BRL,BND,BGN,BIF,CAD,CVE,KYD,GHC,XOF,XAF,XPF,CLP,COP,KMF,BAM,NIO,CRC,CUP,CYP,CZK,GMD,'.
								'DKK,MKD,DEM,AED,DJF,STD,DOP,VND,GRD,XCD,EGP,SVC,ETB,EUR,FKP,FJD,HUF,CDF,FRF,GIP,XAU,HTG,PYG,GNF,GWP,GYD,HKD,UAH,ISK,INR,IRR,IQD,IEP,ITL,JMD,JOD,KES,PGK,LAK,EEK,'.
								'HRK,KWD,MWK,ZMK,AOR,MMK,GEL,LVL,LBP,ALL,HNL,SLL,ROL,BGL,LRD,LYD,SZL,LTL,LSL,LUF,MGF,MYR,MTL,TMM,FIM,MUR,MZM,MXN,MXV,MDL,MAD,BOV,NGN,ERN,NAD,NPR,ANG,NLG,YUM,ILS,'.
								'AON,TWD,ZRN,NZD,BTN,KPW,NOK,PEN,MRO,TOP,PKR,XPD,MOP,UYU,PHP,XPT,PTE,GBP,BWP,QAR,GTQ,ZAL,ZAR,OMR,KHR,MVR,IDR,RUB,RUR,RWF,SAR,ATS,SCR,XAG,SGD,SKK,SBD,KGS,SOS,ESP,'.
								'LKR,SHP,ECS,SDD,SRG,SEK,CHF,SYP,TJR,BDT,WST,TZS,KZT,TPE,SIT,TTD,MNT,TND,TRL,UGX,ECV,CLF,USN,USS,USD,UZS,VUV,KRW,YER,JPY,CNY,ZWD,PLN';
		$info['cc_list'] = "visa,mastercard,discover,americanexpress,echeck,jcb,dinersclub";
		$info['recurring'] = 1;
		$info['actions'] = 'cancel';
		$info['secure'] = 1;

		return $info;
	}

	function settings()
	{
		$settings = array();
		$settings['login']				= "login";
		$settings['transaction_key']	= "transaction_key";
		$settings['testmode']			= 0;
		$settings['currency']			= "USD";
		$settings['promptAddress']		= 0;
		$settings['totalOccurrences']	= 12;
		$settings['trialOccurrences']	= 1;
		$settings['useSilentPostResponse']	= 1;
		$settings['item_name']			= sprintf( _CFG_PROCESSOR_ITEM_NAME_DEFAULT, '[[cms_live_site]]', '[[user_name]]', '[[user_username]]' );

		return $settings;
	}

	function backend_settings()
	{
		$settings = array();
		$settings['testmode']			= array("list_yesno");
		$settings['login'] 				= array("inputC");
		$settings['transaction_key']	= array("inputC");
		$settings['currency']			= array("list_currency");
		$settings['promptAddress']		= array("list_yesno");
		$settings['totalOccurrences']	= array("inputA");
		$settings['trialOccurrences']	= array("inputA");
		$settings['useSilentPostResponse']		= array("list_yesno");
		$settings['SilentPost_info']			= array( 'fieldset' );
		$settings['item_name']			= array("inputE");

		$settings = AECToolbox::rewriteEngineInfo( null, $settings );

		return $settings;
	}

	function checkoutform( $request )
	{
		global $mosConfig_live_site;

		$var = $this->getCCform();

		$name = explode( ' ', $request->metaUser->cmsUser->name );

		if ( empty( $name[1] ) ) {
			$name[1] = "";
		}

		$var['params']['billFirstName'] = array( 'inputC', _AEC_AUTHORIZE_ARB_PARAMS_BILLFIRSTNAME_NAME, _AEC_AUTHORIZE_ARB_PARAMS_BILLFIRSTNAME_DESC, $name[0]);
		$var['params']['billLastName'] = array( 'inputC', _AEC_AUTHORIZE_ARB_PARAMS_BILLLASTNAME_NAME, _AEC_AUTHORIZE_ARB_PARAMS_BILLLASTNAME_DESC, $name[1]);

		if ( !empty( $this->settings['promptAddress'] ) ) {
			$var['params']['billAddress'] = array( 'inputC', _AEC_AUTHORIZE_ARB_PARAMS_BILLADDRESS_NAME );
			$var['params']['billCity'] = array( 'inputC', _AEC_AUTHORIZE_ARB_PARAMS_BILLCITY_NAME );
			$var['params']['billState'] = array( 'inputC', _AEC_AUTHORIZE_ARB_PARAMS_BILLSTATE_NAME );
			$var['params']['billZip'] = array( 'inputC', _AEC_AUTHORIZE_ARB_PARAMS_BILLZIP_NAME );
			$var['params']['billCountry'] = array( 'inputC', _AEC_AUTHORIZE_ARB_PARAMS_BILLCOUNTRY_NAME );
		}

		return $var;
	}

	function createRequestXML( $request )
	{
		global $mosConfig_live_site;

		// Start xml, add login and transaction key, as well as invoice number
		$content =	'<?xml version="1.0" encoding="utf-8"?>'
					. '<ARBCreateSubscriptionRequest xmlns="AnetApi/xml/v1/schema/AnetApiSchema.xsd">'
					. '<merchantAuthentication>'
					. '<name>' . trim( substr( $this->settings['login'], 0, 25 ) ) . '</name>'
					. '<transactionKey>' . trim( substr( $this->settings['transaction_key'], 0, 16 ) ) . '</transactionKey>'
					. '</merchantAuthentication>'
					. '<refId>' . $request->int_var['invoice'] . '</refId>';

		$full = $this->convertPeriodUnit( $request->int_var['amount']['period3'], $request->int_var['amount']['unit3'] );

		// Add Payment information
		$content .=	'<subscription>'
					. '<name>' . trim( substr( AECToolbox::rewriteEngine( $this->settings['item_name'], $request->metaUser, $request->new_subscription, $request->invoice ), 0, 20 ) ) . '</name>'
					. '<paymentSchedule>'
					. '<interval>'
					. '<length>' . trim( $full['period'] ) . '</length>'
					. '<unit>' . trim( $full['unit'] ) . '</unit>'
					. '</interval>'
					. '<startDate>' . trim( date( 'Y-m-d' ) ) . '</startDate>'
					. '<totalOccurrences>' . trim( $this->settings['totalOccurrences'] ) . '</totalOccurrences>';

		if ( isset( $request->int_var['amount']['amount1'] ) ) {
			$content .=	'<trialOccurrences>' . trim( $this->settings['trialOccurrences'] ) . '</trialOccurrences>';
		}

		$content .=	 '</paymentSchedule>'
					. '<amount>' . trim( $request->int_var['amount']['amount3'] ) .'</amount>';

		if ( isset( $request->int_var['amount']['amount1'] ) ) {
			$content .=	 '<trialAmount>' . trim( $request->int_var['amount']['amount1'] ) . '</trialAmount>';
		}

		$expirationDate =  $request->int_var['params']['expirationYear'] . '-' . str_pad( $request->int_var['params']['expirationMonth'], 2, '0', STR_PAD_LEFT );

		$content .=	'<payment>'
					. '<creditCard>'
					. '<cardNumber>' . trim( $request->int_var['params']['cardNumber'] ) . '</cardNumber>'
					. '<expirationDate>' . trim( $expirationDate ) . '</expirationDate>'
					. '</creditCard>'
					. '</payment>'
					;
		$content .=	 '<billTo>'
					. '<firstName>'. trim( $request->int_var['params']['billFirstName'] ) . '</firstName>'
					. '<lastName>' . trim( $request->int_var['params']['billLastName'] ) . '</lastName>'
					;

		if ( isset( $request->int_var['params']['billAddress'] ) ) {
		$content .=	 '<address>'. trim( $request->int_var['params']['billAddress'] ) . '</address>'
					. '<city>' . trim( $request->int_var['params']['billCity'] ) . '</city>'
					. '<state>'. trim( $request->int_var['params']['billState'] ) . '</state>'
					. '<zip>' . trim( $request->int_var['params']['billZip'] ) . '</zip>'
					. '<country>'. trim( $request->int_var['params']['billCountry'] ) . '</country>'
					;
		}

		$content .=	'</billTo>';
		$content .=	'</subscription>';

		// Close Request
		$content .=	'</ARBCreateSubscriptionRequest>';

		return $content;
	}

	function transmitRequestXML( $xml, $request )
	{
		$path = "/xml/v1/request.api";
		if ( $this->settings['testmode'] ) {
			$url = "https://apitest.authorize.net" . $path;
		} else {
			$url = "https://api.authorize.net" . $path;
		}

		$response = $this->transmitRequest( $url, $path, $xml, 443 );

		$return['valid'] = false;
		$return['raw'] = $response;

		if ( $response ) {
			$return['invoice'] = $this->substring_between($response,'<refId>','</refId>');
			$resultCode = $this->substring_between($response,'<resultCode>','</resultCode>');

			$code = $this->substring_between($response,'<code>','</code>');
			$text = $this->substring_between($response,'<text>','</text>');

			if ( strcmp( $resultCode, 'Ok' ) === 0) {
				$return['valid'] = 1;
			} else {
				$return['error'] = $text;
			}

			if ( ( $this->settings['totalOccurrences'] > 1 ) && !$this->settings['useSilentPostResponse'] ) {
				$return['multiplicator'] = $this->settings['totalOccurrences'];
			}

			$subscriptionId = $this->substring_between($response,'<subscriptionId>','</subscriptionId>');

			$return['invoiceparams'] = array( "subscriptionid" => $subscriptionId );
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

	function convertPeriodUnit( $period, $unit )
	{
		$return = array();
		switch ( $unit ) {
			case 'D':
				$return['unit'] = 'days';
				$return['period'] = $period;
				break;
			case 'W':
				if ( $period%4 == 0 ) {
					$return['unit'] = 'months';
					$return['period'] = $period/4;
				} else {
					$return['unit'] = 'days';
					$return['period'] = $period*7;
				}
				break;
			case 'M':
				$return['unit'] = 'months';
				$return['period'] = $period;
				break;
			case 'Y':
				$return['unit'] = 'months';
				$return['period'] = $period*12;
				break;
		}

		return $return;
	}

	function customaction_cancel( $request )
	{
		$content =	'<?xml version="1.0" encoding="utf-8"?>'
					. '<ARBCancelSubscriptionRequest xmlns="AnetApi/xml/v1/schema/AnetApiSchema.xsd">'
					. '<merchantAuthentication>'
					. '<name>' . trim( substr( $this->settings['login'], 0, 25 ) ) . '</name>'
					. '<transactionKey>' . trim( substr( $this->settings['transaction_key'], 0, 16 ) ) . '</transactionKey>'
					. '</merchantAuthentication>'
					. '<refId>' . $request->invoice->invoice_number . '</refId>';

		$invoiceparams = $request->invoice->getParams();

		// Add Payment information
		$content .=	'<subscriptionId>' . $invoiceparams['subscriptionid'] . '</subscriptionId>';

		// Close Request
		$content .=	'</ARBCancelSubscriptionRequest>';

		$path = "/xml/v1/request.api";
		if ( $this->settings['testmode'] ) {
			$url = "https://apitest.authorize.net" . $path;
		} else {
			$url = "https://api.authorize.net" . $path;
		}

		$response = $this->transmitRequest( $url, $path, $content, 443 );

		if ( !empty( $response ) ) {
			$responsestring = $response;

			$resultCode = $this->substring_between( $response,'<resultCode>','</resultCode>' );

			$code = $this->substring_between( $response,'<code>','</code>' );
			$text = $this->substring_between( $response,'<text>','</text>' );

			if ( strcmp( $resultCode, 'Ok' ) === 0 ) {
				$return['valid'] = 0;
				$return['cancel'] = true;
			} else {
				$return['valid'] = 0;
				$return['error'] = $text;
			}

			return $return;
		} else {
			Payment_HTML::error( 'com_acctexp', $request->metaUser->cmsUser, $request->invoice, "An error occured while cancelling your subscription. Please contact the system administrator!", true );
		}
	}

	function parseNotification( $post )
	{
		$x_description			= $post['x_description'];

		$x_amount				= $post['x_amount'];
		$userid					= $post['x_cust_id'];

		$response = array();
		$response['invoice'] = $post['x_invoice_num'];

		return $response;
	}

	function validateNotification( $response, $post, $invoice )
	{
		if ( $post['x_subscription_paynum'] > 1 ) {
			$x_response_code		= $post['x_response_code'];
			$x_response_reason_text	= $post['x_response_reason_text'];

			$response['valid'] = ($x_response_code == '1');
		} else {
			$response['valid'] = 0;
			$response['duplicate'] = true;
		}

		return $response;
	}

/**
 * 2008-02-19-17:41:15
Array
(
    [x_response_code] => 1
    [x_response_subcode] => 1
    [x_response_reason_code] => 1
    [x_response_reason_text] => This transaction has been approved.
    [x_auth_code] =>
    [x_avs_code] => P
    [x_trans_id] => 1736352859
    [x_invoice_num] => IMGNkYjJjMTFlOWRm
    [x_description] => Account Cancellation
    [x_amount] => 299.00
    [x_method] => CC
    [x_type] => credit
    [x_cust_id] =>
    [x_first_name] => Fay
    [x_last_name] => Jozoff
    [x_company] =>
    [x_address] => 430 E. 56th Street
    [x_city] => New York
    [x_state] => NY
    [x_zip] => 10022
    [x_country] => U.S.A.
    [x_phone] => 347-678-5711
    [x_fax] => 212-230-1225
    [x_email] => fayannlee@gmail.com
    [x_ship_to_first_name] => Fay
    [x_ship_to_last_name] => Jozoff
    [x_ship_to_company] =>
    [x_ship_to_address] => 430 E. 56th Street
    [x_ship_to_city] => New York
    [x_ship_to_state] => NY
    [x_ship_to_zip] => 10022
    [x_ship_to_country] => U.S.A.
    [x_tax] => 0.0000
    [x_duty] => 0.0000
    [x_freight] => 0.0000
    [x_tax_exempt] => FALSE
    [x_po_num] =>
    [x_MD5_Hash] => DB9B84B6D350D7B8259AA807FBF6C229
    [x_cvv2_resp_code] =>
    [x_cavv_response] =>
    [x_test_request] => false
    [x_customer_id] =>
)
 */

}
?>