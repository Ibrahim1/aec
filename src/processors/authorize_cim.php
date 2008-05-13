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

require('authorizenet_cim/authorizenet.cim.class.php');

class processor_authorize_cim extends XMLprocessor
{
	function info()
	{
		$info = array();
		$info['name'] = 'authorize_cim';
		$info['longname'] = _CFG_AUTHORIZE_CIM_LONGNAME;
		$info['statement'] = _CFG_AUTHORIZE_CIM_STATEMENT;
		$info['description'] = _CFG_AUTHORIZE_CIM_DESCRIPTION;
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

	function checkoutform( $request ) {
		$var = array();
		$cim = new AuthNetCim($this->settings['login'], $this->settings['transaction_key'], $this->settings['testmode']);

		$cim->setParameter('customerProfileId', $this->getCustomerProfileID($request->metaUser->cmsUser->id));
		$cim->getCustomerProfileRequest();

		/*
		if ($cim->isSuccessful()) {
			$cardNumber = $cim->substring_between($cim->response,'<cardNumber>','</cardNumber>');
			$cardExpiry = $cim->substring_between($cim->response,'<expirationDate>','</expirationDate>');

			$var['params']['cardNumberStatic'] = array( 'strong', _AEC_CCFORM_CARDNUMBER_NAME, strtolower( "XXXXXXXX" . $cardNumber ));
			$var['params']['cardExpiryStatic'] = array( 'strong', _AEC_CCFORM_CARDNUMBER_NAME, strtolower( $cardExpiry ));
		}
		*/

		$var = $this->getCCform( $var, array( 'card_number', 'card_exp_month', 'card_exp_year', 'card_cvv2' ) );

		$billFirstName = $cim->substring_between($cim->response,'<firstName>','</firstName>');
		$billLastName = $cim->substring_between($cim->response,'<lastName>','</lastName>');

		$var['params']['billFirstName'] = array( 'inputC', _AEC_AUTHORIZE_CIM_PARAMS_BILLFIRSTNAME_NAME, _AEC_AUTHORIZE_CIM_PARAMS_BILLFIRSTNAME_DESC, $billFirstName);
		$var['params']['billLastName'] = array( 'inputC', _AEC_AUTHORIZE_CIM_PARAMS_BILLLASTNAME_NAME, _AEC_AUTHORIZE_CIM_PARAMS_BILLLASTNAME_DESC, $billLastName);

		if ( !empty( $this->settings['promptAddress'] ) ) {

			$billCompany = $cim->substring_between($cim->response,'<company>','</company>');
			$billAddress = $cim->substring_between($cim->response,'<address>','</address>');
			$billCity = $cim->substring_between($cim->response,'<city>','</city>');
			$billState = $cim->substring_between($cim->response,'<state>','</state>');
			$billZip = $cim->substring_between($cim->response,'<zip>','</zip>');
			$billCountry = $cim->substring_between($cim->response,'<country>','</country>');
			$billPhone = $cim->substring_between($cim->response,'<phoneNumber>','</phoneNumber>');
			$billFax = $cim->substring_between($cim->response,'<faxNumber>','</faxNumber>');

			$var['params']['billCompany'] = array( 'inputC', _AEC_AUTHORIZE_CIM_PARAMS_BILLCOMPANY_NAME, _AEC_AUTHORIZE_CIM_PARAMS_BILLCOMPANY_DESC, $billCompany );
			$var['params']['billAddress'] = array( 'inputC', _AEC_AUTHORIZE_CIM_PARAMS_BILLADDRESS_NAME, _AEC_AUTHORIZE_CIM_PARAMS_BILLADDRESS_DESC, $billAddress );
			$var['params']['billCity'] = array( 'inputC', _AEC_AUTHORIZE_CIM_PARAMS_BILLCITY_NAME, _AEC_AUTHORIZE_CIM_PARAMS_BILLCITY_DESC, $billCity );
			$var['params']['billState'] = array( 'inputC', _AEC_AUTHORIZE_CIM_PARAMS_BILLSTATE_NAME, _AEC_AUTHORIZE_CIM_PARAMS_BILLSTATE_DESC, $billState );
			$var['params']['billZip'] = array( 'inputC', _AEC_AUTHORIZE_CIM_PARAMS_BILLZIP_NAME, _AEC_AUTHORIZE_CIM_PARAMS_BILLZIP_DESC, $billZip );
			$var['params']['billCountry'] = array( 'inputC', _AEC_AUTHORIZE_CIM_PARAMS_BILLCOUNTRY_NAME, _AEC_AUTHORIZE_CIM_PARAMS_BILLCOUNTRY_DESC, $billCountry );
			$var['params']['billPhone'] = array( 'inputC', _AEC_AUTHORIZE_CIM_PARAMS_BILLPHONE_NAME, _AEC_AUTHORIZE_CIM_PARAMS_BILLPHONE_DESC, $billPhone );
			$var['params']['billFax'] = array( 'inputC', _AEC_AUTHORIZE_CIM_PARAMS_BILLFAX_NAME, _AEC_AUTHORIZE_CIM_PARAMS_BILLFAX_DESC, $billFax );
		}

		return $var;
	}

	function createRequestXML( $request ){
		return "";
	}

	function transmitRequestXML( $xml, $request ) {
		$return['valid'] = false;

		$cim = new AuthNetCim($this->settings['login'], $this->settings['transaction_key'], $this->settings['testmode']);

		$customerProfileID = $this->getCustomerProfileID($request->metaUser->cmsUser->id);

		$cim->setParameter('refId', $request->int_var['invoice']);

		$cim->setParameter('merchantCustomerId', $request->metaUser->cmsUser->id);

		$cim->setParameter('description', $request->metaUser->cmsUser->name);

		$cim->setParameter('email', $request->metaUser->cmsUser->email);

		$cim->setParameter('paymentType', 'creditcard');
		$cim->setParameter('cardNumber', trim( $request->int_var['params']['cardNumber'] ));
		$cim->setParameter('expirationDate', $request->int_var['params']['expirationYear'] . '-' . $request->int_var['params']['expirationMonth']);

		$cim->setParameter('billTo_firstName', trim( $request->int_var['params']['billFirstName'] ));
		$cim->setParameter('billTo_lastName', trim( $request->int_var['params']['billLastName'] ));
		$cim->setParameter('billTo_company', trim( $request->int_var['params']['billCompany'] ));
		$cim->setParameter('billTo_address', trim( $request->int_var['params']['billAddress'] ));
		$cim->setParameter('billTo_city', trim( $request->int_var['params']['billCity'] ));
		$cim->setParameter('billTo_state', trim( $request->int_var['params']['billState'] ));
		$cim->setParameter('billTo_zip', trim( $request->int_var['params']['billZip'] ));
		$cim->setParameter('billTo_country', trim( $request->int_var['params']['billCountry'] ));
		$cim->setParameter('billTo_phoneNumber', trim( $request->int_var['params']['billPhone'] ));
		$cim->setParameter('billTo_faxNumber', trim( $request->int_var['params']['billFax'] ));

		$cim->setParameter('shipTo_firstName', trim( $request->int_var['params']['billFirstName'] ));
		$cim->setParameter('shipTo_lastName', trim( $request->int_var['params']['billLastName'] ));
		$cim->setParameter('shipTo_company', trim( $request->int_var['params']['billCompany'] ));
		$cim->setParameter('shipTo_address', trim( $request->int_var['params']['billAddress'] ));
		$cim->setParameter('shipTo_city', trim( $request->int_var['params']['billCity'] ));
		$cim->setParameter('shipTo_state', trim( $request->int_var['params']['billState'] ));
		$cim->setParameter('shipTo_zip', trim( $request->int_var['params']['billZip'] ));
		$cim->setParameter('shipTo_country', trim( $request->int_var['params']['billCountry'] ));
		$cim->setParameter('shipTo_phoneNumber', trim( $request->int_var['params']['billPhone'] ));
		$cim->setParameter('shipTo_faxNumber', trim( $request->int_var['params']['billFax'] ));

		$cim->setParameter('customerProfileId', $customerProfileID);
		$cim->getCustomerProfileRequest();

		if ($cim->isSuccessful()) {
			$cim->setParameter('customerProfileId', $cim->customerProfileId);
			$cim->setParameter('customerPaymentProfileId', $cim->customerPaymentProfileId);
			$cim->setParameter('customerAddressId', $cim->customerAddressId);

			$cim->updateCustomerPaymentProfileRequest();
			$cim->updateCustomerShippingAddressRequest();
		} else {
			$cim->createCustomerProfileRequest();
		}

		if ($cim->isSuccessful()) {
			$cim->setParameter('transaction_amount', $request->int_var['amount']);
			$LineItem = array();
			$i = 1;
			$LineItem[$i]['itemId'] = $request->int_var['invoice'];
			$LineItem[$i]['name'] = trim( substr( AECToolbox::rewriteEngine( $this->settings['item_name'], $request->metaUser, $request->new_subscription, $request->invoice ), 0, 30 ) );
			$LineItem[$i]['description'] = trim( AECToolbox::rewriteEngine( $this->settings['item_name'], $request->metaUser, $request->new_subscription, $request->invoice ));
			$LineItem[$i]['quantity'] = '1';
			$LineItem[$i]['unitPrice'] = $request->int_var['amount'];
			$LineItem[$i]['taxable'] = 'false';

			$cim->LineItems = $LineItem;

			$cim->setParameter('transactionType', 'profileTransAuthCapture');

			$cim->setParameter('transactionCardCode', trim( $request->int_var['params']['cardVV2'] ));

			$cim->createCustomerProfileTransactionRequest();

			if ($cim->isSuccessful()) {
				$return['valid'] = true;
				$return['invoice'] =$cim->refId;
			} else {
				$return['error'] = $cim->code . ": " . $cim->text;
			}
		} else {
			$return['error'] = $cim->code . ": " . $cim->text;
		}

		return $return;
	}

	function getCustomerProfileID($userID){
		global $database;
		$row = new mosUser( $database );
		$row->load( (int)$userID );
		$userParams = & new mosParameters( $row->params );

		return (int)$userParams->get('customerProfileId');
	}
}
?>