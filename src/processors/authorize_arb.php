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

	function backend_settings( $cfg )
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
 		$rewriteswitches 				= array("cms", "user", "subscription", "plan", "invoice");
		$settings = AECToolbox::rewriteEngineInfo( $rewriteswitches, $settings );

		return $settings;
	}

	function checkoutform( $int_var, $cfg, $metaUser, $new_subscription )
	{
		global $mosConfig_live_site;

		$var = $this->getCCform();

		$name = explode( ' ', $metaUser->cmsUser->name );

		if ( empty( $name[1] ) ) {
			$name[1] = "";
		}

		$var['params']['billFirstName'] = array( 'inputC', _AEC_AUTHORIZE_ARB_PARAMS_BILLFIRSTNAME_NAME, _AEC_AUTHORIZE_ARB_PARAMS_BILLFIRSTNAME_DESC, $name[0]);
		$var['params']['billLastName'] = array( 'inputC', _AEC_AUTHORIZE_ARB_PARAMS_BILLLASTNAME_NAME, _AEC_AUTHORIZE_ARB_PARAMS_BILLLASTNAME_DESC, $name[1]);

		if ( !empty( $cfg['promptAddress'] ) ) {
			$var['params']['billAddress'] = array( 'inputC', _AEC_AUTHORIZE_ARB_PARAMS_BILLADDRESS_NAME );
			$var['params']['billCity'] = array( 'inputC', _AEC_AUTHORIZE_ARB_PARAMS_BILLCITY_NAME );
			$var['params']['billState'] = array( 'inputC', _AEC_AUTHORIZE_ARB_PARAMS_BILLSTATE_NAME );
			$var['params']['billZip'] = array( 'inputC', _AEC_AUTHORIZE_ARB_PARAMS_BILLZIP_NAME );
			$var['params']['billCountry'] = array( 'inputC', _AEC_AUTHORIZE_ARB_PARAMS_BILLCOUNTRY_NAME );
		}

		return $var;
	}

	function createRequestXML( $int_var, $cfg, $metaUser, $new_subscription, $invoice )
	{
		global $mosConfig_live_site;

		// Start xml, add login and transaction key, as well as invoice number
		$content =	'<?xml version="1.0" encoding="utf-8"?>'
					. '<ARBCreateSubscriptionRequest xmlns="AnetApi/xml/v1/schema/AnetApiSchema.xsd">'
					. '<merchantAuthentication>'
					. '<name>' . trim( substr( $cfg['login'], 0, 25 ) ) . '</name>'
					. '<transactionKey>' . trim( substr( $cfg['transaction_key'], 0, 16 ) ) . '</transactionKey>'
					. '</merchantAuthentication>'
					. '<refId>' . $int_var['invoice'] . '</refId>';

		$full = $this->convertPeriodUnit( $int_var['amount']['period3'], $int_var['amount']['unit3'] );

		// Add Payment information
		$content .=	'<subscription>'
					. '<name>' . trim( substr( AECToolbox::rewriteEngine( $cfg['item_name'], $metaUser, $new_subscription, $invoice ), 0, 20 ) ) . '</name>'
					. '<paymentSchedule>'
					. '<interval>'
					. '<length>' . trim( $full['period'] ) . '</length>'
					. '<unit>' . trim( $full['unit'] ) . '</unit>'
					. '</interval>'
					. '<startDate>' . trim( date( 'Y-m-d' ) ) . '</startDate>'
					. '<totalOccurrences>' . trim( $cfg['totalOccurrences'] ) . '</totalOccurrences>';

		if ( isset( $int_var['amount']['amount1'] ) ) {
			$content .=	'<trialOccurrences>' . trim( $cfg['trialOccurrences'] ) . '</trialOccurrences>';
		}

		$content .=	 '</paymentSchedule>'
					. '<amount>' . trim( $int_var['amount']['amount3'] ) .'</amount>';

		if ( isset( $int_var['amount']['amount1'] ) ) {
			$content .=	 '<trialAmount>' . trim( $int_var['amount']['amount1'] ) . '</trialAmount>';
		}

		$expirationDate =  $int_var['params']['expirationYear'] . '-' . str_pad( $int_var['params']['expirationMonth'], 2, '0', STR_PAD_LEFT );

		$content .=	'<payment>'
					. '<creditCard>'
					. '<cardNumber>' . trim( $int_var['params']['cardNumber'] ) . '</cardNumber>'
					. '<expirationDate>' . trim( $expirationDate ) . '</expirationDate>'
					. '</creditCard>'
					. '</payment>'
					;
		$content .=	 '<billTo>'
					. '<firstName>'. trim( $int_var['params']['billFirstName'] ) . '</firstName>'
					. '<lastName>' . trim( $int_var['params']['billLastName'] ) . '</lastName>'
					;

		if ( isset( $int_var['params']['billAddress'] ) ) {
		$content .=	 '<address>'. trim( $int_var['params']['billAddress'] ) . '</address>'
					. '<city>' . trim( $int_var['params']['billCity'] ) . '</city>'
					. '<state>'. trim( $int_var['params']['billState'] ) . '</state>'
					. '<zip>' . trim( $int_var['params']['billZip'] ) . '</zip>'
					. '<country>'. trim( $int_var['params']['billCountry'] ) . '</country>'
					;
		}

		$content .=	'</billTo>';
		$content .=	'</subscription>';

		// Close Request
		$content .=	'</ARBCreateSubscriptionRequest>';

		return $content;
	}

	function transmitRequestXML( $xml, $int_var, $settings, $metaUser, $new_subscription, $invoice )
	{
		$path = "/xml/v1/request.api";
		if ( $settings['testmode'] ) {
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

			if ( ( $settings['totalOccurrences'] > 1 ) && !$settings['useSilentPostResponse'] ) {
				$return['multiplicator'] = $settings['totalOccurrences'];
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

	function customaction_cancel( $pp, $cfg, $invoice, $metaUser )
	{
		$content =	'<?xml version="1.0" encoding="utf-8"?>'
					. '<ARBCancelSubscriptionRequest xmlns="AnetApi/xml/v1/schema/AnetApiSchema.xsd">'
					. '<merchantAuthentication>'
					. '<name>' . trim( substr( $cfg['login'], 0, 25 ) ) . '</name>'
					. '<transactionKey>' . trim( substr( $cfg['transaction_key'], 0, 16 ) ) . '</transactionKey>'
					. '</merchantAuthentication>'
					. '<refId>' . $invoice->invoice_number . '</refId>';

		$invoiceparams = $invoice->getParams();

		// Add Payment information
		$content .=	'<subscriptionId>' . $invoiceparams['subscriptionid'] . '</subscriptionId>';

		// Close Request
		$content .=	'</ARBCancelSubscriptionRequest>';

		$path = "/xml/v1/request.api";
		if ( $cfg['testmode'] ) {
			$url = "https://apitest.authorize.net" . $path;
		} else {
			$url = "https://api.authorize.net" . $path;
		}

		$response = $this->transmitRequest( $url, $path, $content, 443 );

		if ( !empty( $response ) ) {
			$responsestring = $response;

			$resultCode = $this->substring_between( $response,'<resultCode>','</resultCode>' );

			$code = $this->substring_between($response,'<code>','</code>');
			$text = $this->substring_between($response,'<text>','</text>');

			if ( strcmp( $resultCode, 'Ok' ) === 0 ) {
				$return['valid'] = 0;
				$return['cancel'] = true;
			} else {
				$return['valid'] = 0;
				$return['error'] = $text;
			}

			return $return;
		} else {
			Payment_HTML::error( 'com_acctexp', $metaUser->cmsUser, $invoice, "An error occured while cancelling your subscription. Please contact the system administrator!", true );
		}
	}

}
?>