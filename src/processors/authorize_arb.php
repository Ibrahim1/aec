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
		$info['name'] = "authorize_arb";
		$info['longname'] = "Authorize.net ARB";
		$info['statement'] = "Make payments with Authorize.net!";
		$info['description'] = _DESCRIPTION_AUTHORIZE_ARB;
		$info['currencies'] = 'AFA,DZD,ADP,ARS,AMD,AWG,AUD,AZM,BSD,BHD,THB,PAB,BBD,BYB,BEF,BZD,BMD,VEB,BOB,BRL,BND,BGN,BIF,CAD,CVE,KYD,GHC,XOF,XAF,XPF,CLP,COP,KMF,BAM,NIO,CRC,CUP,CYP,CZK,GMD,'.
								'DKK,MKD,DEM,AED,DJF,STD,DOP,VND,GRD,XCD,EGP,SVC,ETB,EUR,FKP,FJD,HUF,CDF,FRF,GIP,XAU,HTG,PYG,GNF,GWP,GYD,HKD,UAH,ISK,INR,IRR,IQD,IEP,ITL,JMD,JOD,KES,PGK,LAK,EEK,'.
								'HRK,KWD,MWK,ZMK,AOR,MMK,GEL,LVL,LBP,ALL,HNL,SLL,ROL,BGL,LRD,LYD,SZL,LTL,LSL,LUF,MGF,MYR,MTL,TMM,FIM,MUR,MZM,MXN,MXV,MDL,MAD,BOV,NGN,ERN,NAD,NPR,ANG,NLG,YUM,ILS,'.
								'AON,TWD,ZRN,NZD,BTN,KPW,NOK,PEN,MRO,TOP,PKR,XPD,MOP,UYU,PHP,XPT,PTE,GBP,BWP,QAR,GTQ,ZAL,ZAR,OMR,KHR,MVR,IDR,RUB,RUR,RWF,SAR,ATS,SCR,XAG,SGD,SKK,SBD,KGS,SOS,ESP,'.
								'LKR,SHP,ECS,SDD,SRG,SEK,CHF,SYP,TJR,BDT,WST,TZS,KZT,TPE,SIT,TTD,MNT,TND,TRL,UGX,ECV,CLF,USN,USS,USD,UZS,VUV,KRW,YER,JPY,CNY,ZWD,PLN';
		$info['cc_list'] = "visa,mastercard,discover,americanexpress,echeck,jcb,dinersclub";
		$info['recurring'] = 0;

		return $info;
	}

	function settings()
	{
		$settings = array();
		$settings['login']				= "login";
		$settings['transaction_key']	= "transaction_key";
		$settings['testmode']			= 0;
		$settings['currency']			= "USD";
		$settings['timestamp_offset']	= '';
		$settings['item_name']			= sprintf( _CFG_PROCESSOR_ITEM_NAME_DEFAULT, '[[cms_live_site]]', '[[user_name]]', '[[user_username]]' );
		$settings['rewriteInfo']		= '';
		$settings['totalOccurrences']	= 12;
		$settings['trialOccurrences']	= 1;

		return $settings;
	}

	function backend_settings( $cfg )
	{
		$settings = array();
		$settings['testmode']			= array("list_yesno");
		$settings['login'] 				= array("inputC");
		$settings['transaction_key']	= array("inputC");
		$settings['currency']			= array("list_currency");
		$settings['timestamp_offset']	= array("inputC");
		$settings['item_name']			= array("inputE");
 		$rewriteswitches 				= array("cms", "user", "expiration", "subscription", "plan");
		$settings['item_name']			= sprintf( _CFG_PROCESSOR_ITEM_NAME_DEFAULT, '[[cms_live_site]]', '[[user_name]]', '[[user_username]]' );
		$settings['rewriteInfo']		= array("fieldset", "Rewriting Info", AECToolbox::rewriteEngineInfo($rewriteswitches));
		$settings['totalOccurrences']	= array("inputA");
		$settings['trialOccurrences']	= array("inputA");

		return $settings;
	}

	function checkoutform( $int_var, $cfg, $metaUser, $new_subscription )
	{
		global $mosConfig_live_site;

		if ( $cfg['testmode'] ) {
			$var['post_url']	= "https://certification.authorize.net/gateway/transact.dll";
		} else {
			$var['post_url']	= "https://secure.authorize.net/gateway/transact.dll";
		}

		$var['x_login']					= $cfg['login'];
		$var['x_invoice_num']			= $int_var['invoice'];
		$var['x_receipt_link_method']	= "POST";
		$var['x_receipt_link_url']		= AECToolbox::deadsureURL("/index.php?option=com_acctexp&amp;task=authorizenotification");
		$var['x_receipt_link_text']		= "Continue";
		$var['x_show_form']				= "PAYMENT_FORM";
		//$var['x_relay_response']		= "True";
		//$var['x_relay_url']			= AECToolbox::deadsureURL('/index.php?option=com_acctexp&task=authnotification');

		$var['x_amount'] = $int_var['amount'];
		srand(time());
		$sequence = rand(1, 1000);
		$tstamp = time();
		// Calculate fingerprint
		$data = $cfg['login'] . "^" . $sequence . "^" . $tstamp . "^" . $int_var['amount'] . "^" . "";
		$fingerprint = $this->hmac($cfg['transaction_key'], $data);
		// Insert the form elements required for SIM
		$var['x_fp_sequence']	= $sequence;
		$var['x_fp_hash']		= $fingerprint;

		$var['x_cust_id']			= $metaUser->cmsUser->id;
		$var['x_description']		= AECToolbox::rewriteEngine($cfg['item_name'], $metaUser, $new_subscription);

		return $var;
	}

	function creatRequestXML( $int_var, $cfg, $metaUser, $new_subscription )
	{
		global $mosConfig_live_site;

		// Start xml, add login and transaction key, as well as invoice number
		$content =	'<?xml version="1.0" encoding="utf-8"?>'
					. '<ARBCreateSubscriptionRequest xmlns=\"AnetApi/xml/v1/schema/AnetApiSchema.xsd\">'
					. '<merchantAuthentication>'
					. '<name>' . substr( $cfg['login'], 0, 25 ) . '</name>'
					. '<transactionKey>' . substr( $cfg['transaction_key'], 0, 16 ) . '</transactionKey>'
					. '</merchantAuthentication>'
					. '<refId>' . $int_var['invoice'] . '</refId>';


		// Add Payment information
		$content .=	'<subscription>'
					. '<name>' . substr( AECToolbox::rewriteEngine( $cfg['item_name'], $metaUser, $new_subscription ), 0, 20 ) . '</name>'
					. '<paymentSchedule>'
					. '<interval>'
					. '<length>' . $int_var['amount']['period3'] . '</length>'
					. '<unit>' . $int_var['amount']['unit3'] . '</unit>'
					. '</interval>'
					. '<startDate>' . date( 'Y-m-d' ) . '</startDate>'
					. '<totalOccurrences>' . $cfg['totalOccurrences'] . '</totalOccurrences>';

		if ( isset( $int_var['amount']['amount1'] ) ) {
			$content .=	'<trialOccurrences>' . $cfg['trialOccurrences'] . '</trialOccurrences>';
		}

		$content .=	 '</paymentSchedule>'
					. '<amount>' . $int_var['amount']['amount3'] .'</amount>';

		if ( isset( $int_var['amount']['amount1'] ) ) {
			$content .=	 '<trialAmount>' . $int_var['amount']['amount1'] . '</trialAmount>';
		}

		$expirationDate =  $int_var['params']['expirationYear'] . '-' . str_pad( $int_var['params']['expirationMonth'], 2, '0', STR_PAD_LEFT );

		$content .=	'<payment>'
					. '<creditCard>'
					. '<cardNumber>' . $int_var['params']['cardNumber'] . '</cardNumber>'
					. '<expirationDate>' . $expirationDate . '</expirationDate>'
					. '</creditCard>'
					. '</payment>'
					. '<billTo>'
					. '<firstName>'. $int_var['params']['billFirstName'] . '</firstName>'
					. '<lastName>' . $int_var['params']['billLastName'] . '</lastName>'
					. '</billTo>'
					. '</subscription>';

		// Close Request
		$content .=	'</ARBCreateSubscriptionRequest>';

		return $content;
	}

}
?>