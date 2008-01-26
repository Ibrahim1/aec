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

class processor_ipayment_silent extends XMLprocessor
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

		$settings['testmode']		= 0;
		$settings['fake_account']	= 0;
		$settings['user_id']		= "user_id";
		$settings['password']		= "password";
		$settings['currency']		= "USD";
		$settings['promptAddress']	= 0;
		$settings['item_name']		= sprintf( _CFG_PROCESSOR_ITEM_NAME_DEFAULT, '[[cms_live_site]]', '[[user_name]]', '[[user_username]]' );
		$settings['rewriteInfo']	= '';

		return $settings;
	}

	function backend_settings( $cfg )
	{
		$settings = array();
		$settings['testmode']		= array("list_yesno");
		$settings['fake_account']	= array("list_yesno");
		$settings['user_id'] 		= array("inputC");
		$settings['password']		= array("inputC");
		$settings['currency']		= array("list_currency");
		$settings['promptAddress']	= array("list_yesno");
		$settings['item_name']		= array("inputE");
 		$rewriteswitches 			= array("cms", "user", "expiration", "subscription", "plan");
		$settings['rewriteInfo']	= array("fieldset", "Rewriting Info", AECToolbox::rewriteEngineInfo($rewriteswitches));

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
			$var['params']['billTelephone'] = array( 'inputC', _AEC_IPAYMENT_SILENT_PARAMS_BILLTELEPHONE_NAME );
		}

		return $var;
	}

	function createRequestXML( $int_var, $cfg, $metaUser, $new_subscription )
	{
		global $mosConfig_live_site;

		$subscr_params = $metaUser->focusSubscription->getParams();

		if ( isset( $subscr_params['creator_ip'] ) ) {
			$ip = $subscr_params['creator_ip'];
		} else {
			$ip = $_SERVER['REMOTE_ADDR'];
		}

		$a = array();

		$a['silent']			= trim( substr( $cfg['login'], 0, 25 ) );
		$a['trx_paymenttyp']	= 'cc';

		if ( $cfg['fake_account'] ) {
			$a['trxuser_id']		= '99999';
			$a['trxpassword']		= '0';
		} else {
			$a['trxuser_id']		= $cfg['user_id'];
			$a['trxpassword']		= $cfg['password'];
		}

		$a['order_id']			= AECfetchfromDB::InvoiceIDfromNumber( $int_var['invoice'] );
		$a['from_ip']			= $ip;
		$a['trx_currency']		= $cfg['currency'];
		$a['trx_amount']		= $int_var['amount'];
		$a['trx_typ']			= 'auth';
		$a['invoice_text']		= $int_var['invoice'];
		$a['addr_email']		= $metaUser->cmsUser->email;

		$varray = array(	'addr_street'	=>	'billAddress',
							'addr_city'	=>	'billCity',
							'addr_zip'	=>	'billZip',
							'addr_country'	=>	'billCountry',
							'addr_state'	=>	'billState',
							'addr_telefon'	=>	'billTelephone',
							'cc_number'	=>	'cardNumber',
							'cc_expdate_month'	=>	'expirationMonth',
							'cc_expdat_year'	=>	'expirationYear',
							'cc_checkcode'	=>	''
						);


		$a['addr_street']		= trim( $int_var['params']['billAddress'] );
		$a['addr_city']			= trim( $int_var['params']['billCity'] );
		$a['addr_zip']			= trim( $int_var['params']['billZip'] );
		$a['addr_country']		= trim( $int_var['params']['billCountry'] );
		$a['addr_state']		= trim( $int_var['params']['billState'] );
		$a['addr_telefon']		= trim( $int_var['params']['billTelephone'] );
		$a['client_name']		= 'aec';
		$a['client_version']	= '0.12';
		$a['silent']			= 1;

		$stringarray = array();
		foreach ( $a as $name => $value ) {
			$stringarray[] = $name . '=' . urlencode( $value );
		}

		$string = implode( '&', $stringarray );

		return $string;
	}

	function transmitRequestXML( $xml, $int_var, $settings, $metaUser, $new_subscription )
	{
		if ( $settings['testmode'] || $settings['fake_account'] ) {
			if ( $settings['fake_account'] ) {
				$path = "99999/example.php";
			} else {
				$url = $settings['account_id'] . "/example.php";
			}
		} else {
			$url = $settings['account_id'] . "/processor.php";
		}

		$url = "https://ipayment.de/merchant/" . $path;

		$response = $this->transmitRequest( $url, $path, $xml, 443 );

		$return['valid'] = false;
		$return['raw'] = $response;

		if ( $response ) {
			$resp_array = explode( "&", $response );

			foreach ( $resp_array as $arr_id => $arr_content ) {
				$ac = explode( "=", $arr_content );
				$resp_array[$ac[0]] = $ac[1];

				unset( $resp_array[$arr_id] );
			}


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