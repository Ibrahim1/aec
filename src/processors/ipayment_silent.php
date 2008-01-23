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

class processor_ipaymeffnt_silent extends XMLprocessor
{
	function info()
	{
		$info = array();
		$info['name'] = 'ipayment_silent';
		$info['longname'] = _CFG_IPAYMENT_SILENT_LONGNAME;
		$info['statement'] = _CFG_IPAYMENT_SILENT_STATEMENT;
		$info['description'] = _CFG_IPAYMENT_SILENT_DESCRIPTION;
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
		$settings['item_name']			= sprintf( _CFG_PROCESSOR_ITEM_NAME_DEFAULT, '[[cms_live_site]]', '[[user_name]]', '[[user_username]]' );
		$settings['rewriteInfo']		= '';

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
		$settings['item_name']			= array("inputE");
 		$rewriteswitches 				= array("cms", "user", "expiration", "subscription", "plan");
		$settings['rewriteInfo']		= array("fieldset", "Rewriting Info", AECToolbox::rewriteEngineInfo($rewriteswitches));

		return $settings;
	}

	function checkoutform( $int_var, $cfg, $metaUser, $new_subscription )
	{
		global $mosConfig_live_site;

		$var = $this->getCCform();

		$name = explode( ' ', $metaUser->cmsUser->name );

		if ( !empty( $name[1] ) ) {
			$name[1] = "";
		}

		$var['params']['billFirstName'] = array( 'inputC', _AEC_IPAYMENT_SILENT_PARAMS_BILLFIRSTNAME_NAME, _AEC_IPAYMENT_SILENT_PARAMS_BILLFIRSTNAME_DESC, $name[0]);
		$var['params']['billLastName'] = array( 'inputC', _AEC_IPAYMENT_SILENT_PARAMS_BILLLASTNAME_NAME, _AEC_IPAYMENT_SILENT_PARAMS_BILLLASTNAME_DESC, $name[1]);

		if ( !empty( $cfg['promptAddress'] ) ) {
			$var['params']['billAddress'] = array( 'inputC', _AEC_IPAYMENT_SILENT_PARAMS_BILLADDRESS_NAME );
			$var['params']['billCity'] = array( 'inputC', _AEC_IPAYMENT_SILENT_PARAMS_BILLCITY_NAME );
			$var['params']['billState'] = array( 'inputC', _AEC_IPAYMENT_SILENT_PARAMS_BILLSTATE_NAME );
			$var['params']['billZip'] = array( 'inputC', _AEC_IPAYMENT_SILENT_PARAMS_BILLZIP_NAME );
			$var['params']['billCountry'] = array( 'inputC', _AEC_IPAYMENT_SILENT_PARAMS_BILLCOUNTRY_NAME );
		}

		return $var;
	}

	function createRequestXML( $int_var, $cfg, $metaUser, $new_subscription )
	{
		global $mosConfig_live_site;

		if ( is_object( $invoice ) ) {
			$invoice_params	= $invoice->getParams();
		} else {
			$invoice_params	= array();
		}

		$a = array();

		$a['silent']			= trim( substr( $cfg['login'], 0, 25 ) );
		$a['trx_paymenttyp']			= ;
		$a['trxuser_id']			= ;
		$a['trxpassword']			= ;
		$a['$content']			= ;
		$a['strict_id_check']			= ;
		$a['from_ip']			= ;
		$a['trx_currency']			= ;
		$a['trx_amount']			= ;
		$a['trx_typ']			= ;
		$a['addr_email']			= ;
		$a['addr_street']			= ;
		$a['addr_city']			= ;
		$a['addr_zip']			= ;
		$a['addr_country']			= ;
		$a['addr_state']			= ;
		$a['addr_telefon']			= ;
		$a['redirect_url']			= ;
		$a['silent_error_url']			= ;
		$a['client_name']			= 'aec';
		$a['client_version']			= '0.12';

      $process_button_string = tep_draw_hidden_field('silent', '1') .
                               tep_draw_hidden_field('trx_paymenttyp', 'cc') .
                               tep_draw_hidden_field('trxuser_id', MODULE_PAYMENT_IPAYMENT_CC_USER_ID) .
                               tep_draw_hidden_field('trxpassword', MODULE_PAYMENT_IPAYMENT_CC_PASSWORD) .
//                               tep_draw_hidden_field('order_id', '') .
//                               tep_draw_hidden_field('strict_id_check', 1) .
                               tep_draw_hidden_field('from_ip', tep_get_ip_address()) .
                               tep_draw_hidden_field('trx_currency', $currency) .
                               tep_draw_hidden_field('trx_amount', $this->format_raw($order->info['total'])*100) .
                               tep_draw_hidden_field('trx_typ', ((MODULE_PAYMENT_IPAYMENT_CC_TRANSACTION_METHOD == 'Capture') ? 'auth' : 'preauth')) .
                               tep_draw_hidden_field('addr_email', $order->customer['email_address']) .
                               tep_draw_hidden_field('addr_street', $order->billing['street_address']) .
                               tep_draw_hidden_field('addr_city', $order->billing['city']) .
                               tep_draw_hidden_field('addr_zip', $order->billing['postcode']) .
                               tep_draw_hidden_field('addr_country', $order->billing['country']['iso_code_2']) .
                               tep_draw_hidden_field('addr_state', $zone_code) .
                               tep_draw_hidden_field('addr_telefon', $order->customer['telephone']) .
                               tep_draw_hidden_field('redirect_url', tep_href_link(FILENAME_CHECKOUT_PROCESS, '', 'SSL', true)) .
                               tep_draw_hidden_field('silent_error_url', tep_href_link(FILENAME_CHECKOUT_PAYMENT, 'payment_error=' . $this->code, 'SSL', true)) .
                               tep_draw_hidden_field('client_name', 'oscommerce') .
                               tep_draw_hidden_field('client_version', PROJECT_VERSION);

		$stringarray = array();
		foreach ( $a as $name => $value ) {
			$stringarray[] = $name . '=' . urlencode( $value );
		}

		$string = implode( '&', $stringarray );

		return $string;
	}

/*
Simulation:
Account-ID:               99999
User-ID:                  99999
Transaktions-Passwort:         0
Administrations-Passwort: 5cfgRT34xsdedtFLdfHxj7tfwx24fe
*/
	function transmitRequestXML( $xml, $int_var, $settings, $metaUser, $new_subscription )
	{
		if ( $settings['testmode'] ) {
			$url = "https://ipayment.de/merchant/" . $settings['account_id'] . "/example.php";
		} else {
			$url = "https://ipayment.de/merchant/" . $settings['account_id'] . "/processor.php";
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

			if ( $settings['totalOccurrences'] > 1 ) {
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

			$invoice->processorResponse( $pp, $return, $responsestring );
		} else {
			Payment_HTML::error( 'com_acctexp', $metaUser->cmsUser, $invoice, "An error occured when cancelling your subscription. Please contact the system administrator!", true );
		}
	}
}
?>