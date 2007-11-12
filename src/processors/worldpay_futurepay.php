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

class processor_worldpay_futurepay extends POSTprocessor
{
	function info()
	{
		$info = array();
		$info['name']				= 'worldpay_futurepay';
		$info['longname']			= _CFG_WORLDPAY_FUTUREPAY_LONGNAME;
		$info['statement']			= _CFG_WORLDPAY_FUTUREPAY_STATEMENT;
		$info['description']		= _CFG_WORLDPAY_FUTUREPAY_DESCRIPTION;
		$info['currencies']			= 'AFA,ALL,DZD,AON,ARS,AWG,AUD,EUR,BSD,BHD,BDT,BBD,BZD,BMD,BOB,BAD,BWP,BRL,BND,BGL,XOF,'
									. 'BIF,KHR,XAF,CAD,CVE,KYD,CLP,CNY,COP,KMF,CRC,HRK,CUP,CYP,CZK,DKK,DJF,XCD,DOP,TPE,ECS,'
									. 'EGP,SVC,EEK,ETB,FKP,FJD,XPF,GMD,GHC,GIP,GTQ,GNF,GWP,GYD,HTG,HNL,HKD,HUF,ISK,INR,IDR,'
									. 'IRR,IQD,ILS,JMD,JPY,JOD,KZT,KES,KRW,KPW,KWD,KGS,LAK,LVL,LBP,LSL,LRD,LYD,LTL,MOP,MKD,'
									. 'MGF,MWK,MYR,MVR,MTL,MRO,MUR,MXN,MNT,MAD,MZM,MMK,NAD,NPR,ANG,NZD,NIO,NGN,NOK,OMR,PKR,'
									. 'PAB,PGK,PYG,PEN,PHP,PLN,QAR,ROL,RUB,RWF,WST,STD,SAR,SCR,SLL,SGD,SKK,SIT,SBD,SOS,ZAR,'
									. 'LKR,SHP,SDP,SRG,SZL,SEK,CHF,SYP,TWD,TJR,TZS,THB,TOP,TTD,TND,TRY,UGX,UAH,AED,GBP,USD,'
									. 'UYU,VUV,VEB,VND,YER,YUM,ZRN,ZMK,ZWD'
									;
		// mic: prepared to use instead of above
		// $info['currencies'] 		= _aecCurrencies::_CurrencyField( true, true, true );
		$info['cc_list']			= 'visa,mastercard,discover,americanexpress,echeck,giropay';
		$info['recurring']			= 0;

		return $info;
	}

	function settings()
	{
		$settings = array();
		$settings['instId']			= 'instID';
		$settings['testmode'] 		= 0;
		$settings['currency'] 		= 'USD';
		$settings['item_name']		= sprintf( _CFG_PROCESSOR_ITEM_NAME_DEFAULT, '[[cms_live_site]]', '[[user_name]]', '[[user_username]]' );
		$settings['callbackPW'] 	= '';
		$settings['rewriteInfo']	= '';


		return $settings;
	}

	function backend_settings( $cfg )
	{
		$settings = array();
		$settings['testmode']		= array( 'list_yesno');
		$settings['instId']			= array( 'inputC');
		$settings['currency']		= array( 'list_currency');
		$settings['item_name']		= array( 'inputE');
 		$settings['callbackPW']		= array( 'inputC');
 		$rewriteswitches			= array( 'cms', 'user', 'expiration', 'subscription', 'plan');
        $settings['rewriteInfo']	= array( 'fieldset', _AEC_MI_REWRITING_INFO, AECToolbox::rewriteEngineInfo($rewriteswitches));

		return $settings;
	}

	function createGatewayLink( $int_var, $cfg, $metaUser, $new_subscription )
	{
		global $mosConfig_live_site;

		$var['post_url']	= 'https://select.worldpay.com/wcc/purchase';
		if ($cfg->testmode) {
			$var['testMode'] = '100';
		}

		$var['instId']		= $cfg['instId'];
		$var['currency']	= $cfg['currency'];
		$var['cartId']		= $int_var['invoice'];
		$var['desc']	= AECToolbox::rewriteEngine($cfg['item_name'], $metaUser, $new_subscription);

		$var['futurePayType']		= 'regular';
		$var['option']		= '0';

		$units = array( 'D' => '1', 'W' => '2', 'M' => '3', 'Y' => '4' );

		$var['intervalUnit'] = $units[$int_var['amount']['unit3']];
		$var['intervalMult'] = $int_var['amount']['period3'];

		if ( isset( $int_var['amount']['amount1'] ) ) {
			$var['initialAmount'] = $int_var['amount']['amount1'];
		}

		$var['normalAmount'] = $int_var['amount']['amount1'];

		return $var;
	}

/*
 * POSTBACK variables
 * instId=38290
 * email=tiq%40uk.worldpay.com
 * transTime=999178402000
 * country=GB
 * rawAuthCode=A
 * amount=14.99
 * installation=38290
 * tel=0123+456789012
 * address=
 * est+Road%0D%0ATest+Town%0D%0ATest+City
 * futurePayId=76486
 * MC_log=2379&
 * awAuthMessage=authorised+(testMode+always+Yes)
 * authAmount=23.11
 * amountString=%26%23163%3B14.99
 * cardType=Visa
 * AVS=0001
 * cost=14.99
 * currency=GBP
 * testMode=100
 * authAmountString=EUR23.11
 * fax=01234+5678901
 * lang=en
 * transStatus=Y
 * compName=Ian+Richardson
 * authCurrency=EUR
 * postcode=AB1+2CD
 * authCost=23.11
 * desc=Test+Item
 * countryMatch=S
 * cartId=Test+Item
 * transId=12227758
 * callbackPW=38290
 * M_var1=fred
 * authMode=E
 * countryString=United+Kingdom
 * name=WorldPay+Test
 */

	function parseNotification( $post, $cfg )
	{
		$response = array();
		$response['invoice'] = $post['cartId'];
		$response['amount_paid'] = $post['authAmount'];
		$response['amount_currency'] = $post['authCurrency'];

		return $response;
	}

	function validateNotification( $response, $post, $cfg, $invoice )
	{
		$response['valid'] = 0;
		$response['valid'] = ( strcmp( $post['transStatus'], 'Y') === 0 );

		if ( $response['valid'] ) {
			if ( !empty( $cfg['callbackPW'] ) ) {
				if ( isset( $post['callbackPW'] ) ) {
					if ( $cfg['callbackPW'] != $post['callbackPW'] ) {
						$response['valid'] = 0;
						$response['pending_reason'] = 'callback Password set wrong at either Worldpay or within the AEC';
					}
				} else {
					$response['valid'] = 0;
					$response['pending_reason'] = 'no callback Password set at Worldpay!!!';
				}
			}
		}

		return $response;
	}

}

?>
