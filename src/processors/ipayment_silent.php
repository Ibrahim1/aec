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
		$info['secure'] = 1;

		return $info;
	}

	function settings()
	{
		$settings = array();

		$settings['testmode']		= 0;
		$settings['fake_account']	= 0;
		$settings['user_id']		= "user_id";
		$settings['account_id']		= 'account_id';
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
		$settings['account_id']		= array("inputC");
		$settings['password']		= array("inputC");
		$settings['currency']		= array("list_currency");
		$settings['promptAddress']	= array("list_yesno");
		$settings['item_name']		= array("inputE");
 		$rewriteswitches 			= array("cms", "user", "expiration", "subscription", "plan");
		$settings = AECToolbox::rewriteEngineInfo( $rewriteswitches, $settings );

		return $settings;
	}

	function checkoutform( $int_var, $cfg, $metaUser, $new_subscription )
	{
		global $mosConfig_live_site;

		$var['params']['billInfo'] = array( 'p', _CFG_IPAYMENT_SILENT_PARAMS_BILLINFO_ELV_NAME, _CFG_IPAYMENT_SILENT_PARAMS_BILLINFO_ELV_DESC );
		$var['params']['accountName'] = array( 'inputC', _AEC_WTFORM_ACCOUNTNAME_NAME, _AEC_WTFORM_ACCOUNTNAME_NAME, $metaUser->cmsUser->name );
		$var['params']['accountNumber'] = array( 'inputC', _AEC_WTFORM_ACCOUNTNUMBER_NAME, _AEC_WTFORM_ACCOUNTNUMBER_NAME, '' );
		$var['params']['bankNumber'] = array( 'inputC', _AEC_WTFORM_BANKNUMBER_NAME, _AEC_WTFORM_BANKNUMBER_NAME, '' );
		$var['params']['bankName'] = array( 'inputC', _AEC_WTFORM_BANKNAME_NAME, _AEC_WTFORM_BANKNAME_NAME, '' );

		$name = explode( ' ', $metaUser->cmsUser->name );

		if ( empty( $name[1] ) ) {
			$name[1] = "";
		}

		$var['params']['billInfo2'] = array( 'p', _CFG_IPAYMENT_SILENT_PARAMS_BILLINFO_CC_NAME, _CFG_IPAYMENT_SILENT_PARAMS_BILLINFO_CC_DESC );

		$var = $this->getCCform( $var );

		$var['params']['billInfo'] = array( 'p', _AEC_IPAYMENT_SILENT_PARAMS_BILLINFO_NAME, _AEC_IPAYMENT_SILENT_PARAMS_BILLINFO_DESC );
		$var['params']['billFirstName'] = array( 'inputC', _AEC_IPAYMENT_SILENT_PARAMS_BILLFIRSTNAME_NAME, _AEC_IPAYMENT_SILENT_PARAMS_BILLFIRSTNAME_DESC, $name[0] );
		$var['params']['billLastName'] = array( 'inputC', _AEC_IPAYMENT_SILENT_PARAMS_BILLLASTNAME_NAME, _AEC_IPAYMENT_SILENT_PARAMS_BILLLASTNAME_DESC, $name[1] );

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

	function createRequestXML( $request )
	{
		global $mosConfig_live_site, $database;

		$invoice_params = $invoice->getParams();

		if ( isset( $invoice_params['creator_ip'] ) ) {
			$ip = $invoice_params['creator_ip'];
		} else {
			$ip = $_SERVER['REMOTE_ADDR'];
		}

		$a = array();

		if ( empty( $int_var['params']['cc_number'] ) ) {
			$a['trx_paymenttyp']	= 'elv';
		} else {
			$a['trx_paymenttyp']	= 'cc';
		}

		if ( !empty( $cfg['fake_account'] ) ) {
			$a['trxuser_id']		= '99999';
			$a['trxpassword']		= '0';
		} else {
			$a['trxuser_id']		= $cfg['user_id'];
			$a['trxpassword']		= $cfg['password'];
		}

		$a['order_id']		= AECfetchfromDB::InvoiceIDfromNumber( $int_var['invoice'] );
		$a['from_ip']		= $ip;
		$a['trx_currency']	= $cfg['currency'];
		$a['trx_amount']	= (int) ( $int_var['amount'] * 100 );
		$a['trx_typ']		= 'auth';
		$a['invoice_text']	= $int_var['invoice'];
		$a['addr_email']	= $metaUser->cmsUser->email;

		$varray = array(	'addr_name'	=>	'billFirstName',
							'addr_street'	=>	'billAddress',
							'addr_city'	=>	'billCity',
							'addr_zip'	=>	'billZip',
							'addr_country'	=>	'billCountry',
							'addr_state'	=>	'billState',
							'addr_telefon'	=>	'billTelephone',
							'cc_number'	=>	'cardNumber',
							'cc_expdate_month'	=>	'expirationMonth',
							'cc_expdate_year'	=>	'expirationYear',
							'cc_checkcode'	=>	'',
							'bank_accountnumber'	=>	'accountNumber',
							'bank_code'	=>	'bankNumber',
							'bank_name'	=>	'bankName'
						);
		foreach ( $varray as $n => $p ) {
			if ( !empty( $int_var['params'][$p] ) ) {
				if ( ( ( $n == 'cc_expdate_month' ) || ( $n == 'cc_expdate_year' ) ) && empty( $int_var['params']['cc_number'] ) ) {
					continue;
				}

				$a[$n] = $int_var['params'][$p];
			}
		}

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

	function transmitRequestXML( $xml, $request )
	{
		$path = '/merchant/';
		if ( $settings['testmode'] || $settings['fake_account'] ) {
			if ( $settings['fake_account'] ) {
				$path .= "99999/example.php";
			} else {
				$path .= $settings['account_id'] . "/example.php";
			}
		} else {
			$path .= $settings['account_id'] . "/processor.php";
		}

		$url = "https://ipayment.de" . $path . '?' . $xml;
echo "<h1>Variablen:</h1>";
echo '<p>';
foreach( $int_var as $key => $value ) { if ( strpos( $key, '_') === false ) { echo $key . ' = ' . $value . '</p><p>'; } }
echo '</p>';
echo "<h1>Rechnung:</h1>";
echo '<p>';
foreach( $invoice as $key => $value ) { if ( strpos( $key, '_') === false ) { echo $key . ' = ' . $value . '</p><p>'; } }
echo '</p>';
echo "<h1>Senden der Daten:</h1>";
echo '<p>';
echo $xml;
echo '</p>';
echo '<h1>';
echo "an:";
echo '</h1>';
echo '<p>';
echo $url;
echo '</p>';
		$response = $this->transmitRequest( $url, $path, $xml, 443 );
echo '<h1>R&uuml;ckmeldung:</h1>';
echo '<p>';
echo $response;
echo '</p>';
		$return['valid'] = false;
		$return['raw'] = $response;

		if ( $response ) {
			if ( strpos( '&', $response ) ) {
				$resp_array = explode( "&", $response );

				foreach ( $resp_array as $arr_id => $arr_content ) {
					$ac = explode( "=", $arr_content );
					$resp_array[$ac[0]] = $ac[1];

					unset( $resp_array[$arr_id] );
				}

				$return['invoice'] = $resp_array['invoice_text'];

				if ( isset( $resp_array['ret_errormsg'] ) ) {
					$return['error'] = $resp_array['ret_errormsg'];
				} else {
					$return['valid'] = 1;
				}

				$return['invoiceparams'] = array( "subscriptionid" => $resp_array['ret_booknr'] );
			} else {
				$return['valid'] = 0;
			}
		}
echo '<h1>Resultat:</h1>';
echo '<p>';
foreach( $return as $key => $value ) { if ( strpos( $key, '_') === false ) { echo $key . ' = ' . $value . '</p><p>'; } }
echo '</p>';
echo '<h1>Formular:</h1>';
echo '<form action="' . "https://ipayment.de" . $path . '" method="post" >Formular:</h1>';

$p = explode( '&', $xml );

foreach ( $p as $c ) {
	$cc = explode( '=', $c );
	echo '<input type="hidden" name="' . $cc[0] . '" value="' . $cc[1] . '" />';
}
echo '<input type="submit">';
echo '</form>';
exit;
		return $return;
	}

}
?>