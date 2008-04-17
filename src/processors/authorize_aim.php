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

class processor_authorize_aim extends XMLprocessor
{
	function info()
	{
		$info = array();
		$info['name'] = 'authorize_aim';
		$info['longname'] = _CFG_AUTHORIZE_AIM_LONGNAME;
		$info['statement'] = _CFG_AUTHORIZE_AIM_STATEMENT;
		$info['description'] = _CFG_AUTHORIZE_AIM_DESCRIPTION;
		$info['currencies'] = 'AFA,DZD,ADP,ARS,AMD,AWG,AUD,AZM,BSD,BHD,THB,PAB,BBD,BYB,BEF,BZD,BMD,VEB,BOB,BRL,BND,BGN,BIF,CAD,CVE,KYD,GHC,XOF,XAF,XPF,CLP,COP,KMF,BAM,NIO,CRC,CUP,CYP,CZK,GMD,'.
								'DKK,MKD,DEM,AED,DJF,STD,DOP,VND,GRD,XCD,EGP,SVC,ETB,EUR,FKP,FJD,HUF,CDF,FRF,GIP,XAU,HTG,PYG,GNF,GWP,GYD,HKD,UAH,ISK,INR,IRR,IQD,IEP,ITL,JMD,JOD,KES,PGK,LAK,EEK,'.
								'HRK,KWD,MWK,ZMK,AOR,MMK,GEL,LVL,LBP,ALL,HNL,SLL,ROL,BGL,LRD,LYD,SZL,LTL,LSL,LUF,MGF,MYR,MTL,TMM,FIM,MUR,MZM,MXN,MXV,MDL,MAD,BOV,NGN,ERN,NAD,NPR,ANG,NLG,YUM,ILS,'.
								'AON,TWD,ZRN,NZD,BTN,KPW,NOK,PEN,MRO,TOP,PKR,XPD,MOP,UYU,PHP,XPT,PTE,GBP,BWP,QAR,GTQ,ZAL,ZAR,OMR,KHR,MVR,IDR,RUB,RUR,RWF,SAR,ATS,SCR,XAG,SGD,SKK,SBD,KGS,SOS,ESP,'.
								'LKR,SHP,ECS,SDD,SRG,SEK,CHF,SYP,TJR,BDT,WST,TZS,KZT,TPE,SIT,TTD,MNT,TND,TRL,UGX,ECV,CLF,USN,USS,USD,UZS,VUV,KRW,YER,JPY,CNY,ZWD,PLN';
		$info['cc_list'] = "visa,mastercard,discover,americanexpress,echeck,jcb,dinersclub";
		$info['recurring'] = 0;
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
		$settings['promptZipOnly']		= 0;
		$settings['totalOccurrences']	= 12;
		$settings['trialOccurrences']	= 1;
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
		$settings['promptAddress']		= array( "list_yesno" );
		$settings['promptZipOnly']		= array( "list_yesno" );
		$settings['totalOccurrences']	= array( "inputA" );
		$settings['trialOccurrences']	= array( "inputA" );
		$settings['item_name']			= array( "inputE" );
		$settings['customparams']		= array( 'inputD' );

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

		$var['params']['billFirstName'] = array( 'inputC', _AEC_AUTHORIZE_AIM_PARAMS_BILLFIRSTNAME_NAME, _AEC_AUTHORIZE_AIM_PARAMS_BILLFIRSTNAME_DESC, $name[0]);
		$var['params']['billLastName'] = array( 'inputC', _AEC_AUTHORIZE_AIM_PARAMS_BILLLASTNAME_NAME, _AEC_AUTHORIZE_AIM_PARAMS_BILLLASTNAME_DESC, $name[1]);

		if ( !empty( $this->settings['promptAddress'] ) || !empty( $this->settings['promptZipOnly'] ) ) {
			if ( empty( $this->settings['promptZipOnly'] ) ) {
				$var['params']['billAddress'] = array( 'inputC', _AEC_AUTHORIZE_AIM_PARAMS_BILLADDRESS_NAME );
				$var['params']['billCity'] = array( 'inputC', _AEC_AUTHORIZE_AIM_PARAMS_BILLCITY_NAME );
				$var['params']['billState'] = array( 'inputC', _AEC_AUTHORIZE_AIM_PARAMS_BILLSTATE_NAME );
			}

			$var['params']['billZip'] = array( 'inputC', _AEC_AUTHORIZE_AIM_PARAMS_BILLZIP_NAME );

			if ( empty( $this->settings['promptZipOnly'] ) ) {
				$var['params']['billCountry'] = array( 'inputC', _AEC_AUTHORIZE_AIM_PARAMS_BILLCOUNTRY_NAME );
			}
		}

		return $var;
	}

	function createRequestXML( $request )
	{
		global $mosConfig_live_site;

		$a = array();

		$a['x_login']			= trim( substr( $this->settings['login'], 0, 25 ) );
		$a['x_version']			= "3.1";
		$a['x_delim_char']		= "|";
		$a['x_delim_data']		= "TRUE";
		$a['x_url']				= "FALSE";
		$a['x_type']			= "AUTH_CAPTURE";
		$a['x_method']			= "CC";
		$a['x_tran_key']		= $this->settings['transaction_key'];
		$a['x_currency_code']	= $this->settings['currency'];
		$a['x_relay_response']	= "FALSE";
		$a['x_card_num']		= trim( $request->int_var['params']['cardNumber'] );
		$a['x_exp_date']		= str_pad( $request->int_var['params']['expirationMonth'], 2, '0', STR_PAD_LEFT ) . $request->int_var['params']['expirationYear'];
		$a['x_description']		= trim( substr( AECToolbox::rewriteEngine( $this->settings['item_name'], $request->metaUser, $request->new_subscription, $request->invoice ), 0, 20 ) );
		$a['x_invoice_num']		= $request->int_var['invoice'];
		$a['x_amount']			= $request->int_var['amount'];
		$a['x_first_name']		= trim( $request->int_var['params']['billFirstName'] );
		$a['x_last_name']		= trim( $request->int_var['params']['billLastName'] );

		if ( isset( $request->int_var['params']['billZip'] ) ) {
			if ( isset( $request->int_var['params']['billAddress'] ) ) {
				$a['x_address']		= trim( $request->int_var['params']['billAddress'] );
				$a['x_city']		= trim( $request->int_var['params']['billCity'] );
				$a['x_state']		= trim( $request->int_var['params']['billState'] );
			}

			$a['x_zip']			= trim( $request->int_var['params']['billZip'] );

			if ( isset( $request->int_var['params']['billAddress'] ) ) {
				$a['x_country']			= trim( $request->int_var['params']['billCountry'] );
			}
		}

		if ( $this->settings['testmode'] ) {
			$a['x_test_request']		= "TRUE";
		}

		$var = $this->customParams( $this->settings['customparams'], $var, $request );

		$stringarray = array();
		foreach ( $a as $name => $value ) {
			$stringarray[] = $name . '=' . urlencode( $value );
		}

		$string = implode( '&', $stringarray );

		return $string;
	}

	function transmitRequestXML( $xml, $request )
	{
		$path = "/gateway/transact.dll";
		if ( $this->settings['testmode'] ) {
			$url = "https://test.authorize.net" . $path;
		} else {
			$url = "https://secure.authorize.net" . $path;
		}

		$response = $this->transmitRequest( $url, $path, $xml, 443 );

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
				$stringarray[] = $name . '=' . urlencode( $value );
			}

			$return['raw'] = implode( "\n", $stringarray );
		}

		return $return;
	}
}
?>