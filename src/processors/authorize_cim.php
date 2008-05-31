<?php
// Copyright (C) 2008 David Deutsch
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

require( 'authorizenet_cim/authorizenet.cim.class.php' );

class processor_authorize_cim extends XMLprocessor
{
	function info()
	{
		$info = array();
		$info['name']			= 'authorize_cim';
		$info['longname']		= _CFG_AUTHORIZE_CIM_LONGNAME;
		$info['statement']		= _CFG_AUTHORIZE_CIM_STATEMENT;
		$info['description']	= _CFG_AUTHORIZE_CIM_DESCRIPTION;
		$info['currencies']		= 'AFA,DZD,ADP,ARS,AMD,AWG,AUD,AZM,BSD,BHD,THB,PAB,BBD,BYB,BEF,BZD,BMD,VEB,BOB,BRL,BND,BGN,BIF,CAD,CVE,KYD,GHC,XOF,XAF,XPF,CLP,COP,KMF,BAM,NIO,CRC,CUP,CYP,CZK,GMD,'.
								'DKK,MKD,DEM,AED,DJF,STD,DOP,VND,GRD,XCD,EGP,SVC,ETB,EUR,FKP,FJD,HUF,CDF,FRF,GIP,XAU,HTG,PYG,GNF,GWP,GYD,HKD,UAH,ISK,INR,IRR,IQD,IEP,ITL,JMD,JOD,KES,PGK,LAK,EEK,'.
								'HRK,KWD,MWK,ZMK,AOR,MMK,GEL,LVL,LBP,ALL,HNL,SLL,ROL,BGL,LRD,LYD,SZL,LTL,LSL,LUF,MGF,MYR,MTL,TMM,FIM,MUR,MZM,MXN,MXV,MDL,MAD,BOV,NGN,ERN,NAD,NPR,ANG,NLG,YUM,ILS,'.
								'AON,TWD,ZRN,NZD,BTN,KPW,NOK,PEN,MRO,TOP,PKR,XPD,MOP,UYU,PHP,XPT,PTE,GBP,BWP,QAR,GTQ,ZAL,ZAR,OMR,KHR,MVR,IDR,RUB,RUR,RWF,SAR,ATS,SCR,XAG,SGD,SKK,SBD,KGS,SOS,ESP,'.
								'LKR,SHP,ECS,SDD,SRG,SEK,CHF,SYP,TJR,BDT,WST,TZS,KZT,TPE,SIT,TTD,MNT,TND,TRL,UGX,ECV,CLF,USN,USS,USD,UZS,VUV,KRW,YER,JPY,CNY,ZWD,PLN';
		$info['cc_list']		= "visa,mastercard,discover,americanexpress,echeck,jcb,dinersclub";
		$info['recurring']		= 2;
		$info['actions']		= 'cancel';
		$info['secure']			= 1;

		return $info;
	}

	function settings()
	{
		$settings = array();
		$settings['login']				= 'login';
		$settings['transaction_key']	= 'transaction_key';
		$settings['testmode']			= 0;
		$settings['currency']			= 'USD';
		$settings['promptAddress']		= 0;
		$settings['promptZipOnly']		= 0;
		$settings['item_name']			= sprintf( _CFG_PROCESSOR_ITEM_NAME_DEFAULT, '[[cms_live_site]]', '[[user_name]]', '[[user_username]]' );
		$settings['customparams']		= '';

		return $settings;
	}

	function backend_settings()
	{
		$settings = array();
		$settings['testmode']			= array( 'list_yesno' );
		$settings['login'] 				= array( 'inputC' );
		$settings['transaction_key']	= array( 'inputC' );
		$settings['currency']			= array( 'list_currency' );
		$settings['promptAddress']		= array( 'list_yesno' );
		$settings['promptZipOnly']		= array( 'list_yesno' );
		$settings['item_name']			= array( 'inputE' );
		$settings['customparams']		= array( 'inputD' );

		$settings = AECToolbox::rewriteEngineInfo( null, $settings );

		return $settings;
	}

	function registerProfileTabs()
	{
		$tab			= array();
		$tab['details']	= _AEC_USERFORM_BILLING_DETAILS_NAME;

		return $tab;
	}

	function customtab_details( $request )
	{
		if ( isset( $_POST['billFirstName'] ) ) {
			$cim = new AuthNetCim( $this->settings['login'], $this->settings['transaction_key'], $this->settings['testmode'] );

			$cim->setParameter( 'customerProfileId', $this->getCustomerProfileID( $request->metaUser->userid ) );
			$cim->getCustomerProfileRequest();

			$udata = array( 'billTo_firstName' => 'billFirstName', 'billTo_lastName' => 'billLastName', 'billTo_company' => 'billCompany', 'billTo_address' => 'billAddress',
							'billTo_city' => 'billCity', 'billTo_state' => 'billState', 'billTo_zip' => 'billZip', 'billTo_country' => 'billCountry',
							'billTo_phoneNumber' => 'billPhone', 'billTo_faxNumber' => 'billFax',
							'shipTo_firstName' => 'billFirstName', 'shipTo_lastName' => 'billLastName', 'shipTo_company' => 'billCompany', 'shipTo_address' => 'billAddress',
							'shipTo_city' => 'shipTo_city', 'shipTo_state' => 'billState', 'shipTo_zip' => 'billZip', 'shipTo_country' => 'billCountry',
							'shipTo_phoneNumber' => 'billPhone', 'shipTo_faxNumber' => 'billFax'
							);

			foreach ( $udata as $authvar => $aecvar ) {
				$cim->setParameter( $authvar, trim( $_POST[$aecvar] ) );
			}

			$cim->setParameter( 'customerProfileId',		$cim->customerProfileId );
			$cim->setParameter( 'customerPaymentProfileId',	$cim->customerPaymentProfileId );
			$cim->setParameter( 'customerAddressId',		$cim->customerAddressId );

			$cim->updateCustomerPaymentProfileRequest();
			$cim->updateCustomerShippingAddressRequest();
		} else {
			$cim = null;
		}

		$var = $this->checkoutform( $request, $cim );

		$return = '<form action="' . AECToolbox::deadsureURL( '/index.php?option=com_acctexp&amp;task=checkout', true ) . '" method="post">' . "\n";
		$return .= $this->getParamsHTML( $var ) . '<br /><br />';
		$return .= '<input type="hidden" name="userid" value="' . $request->metaUser->userid . '" />' . "\n";
		$return .= '<input type="hidden" name="task" value="checkout" />' . "\n";
		$return .= '<input type="hidden" name="sub" value="authorize_cim_details" />' . "\n";
		$return .= '<input type="submit" class="button" value="' . _BUTTON_CHECKOUT . '" /><br /><br />' . "\n";
		$return .= '</form>' . "\n";

		return $return;
	}

	function checkoutform( $request, $cim=null )
	{
		$var = array();
		$hascim = false;

		$profileid = $this->getCustomerProfileID( $request->metaUser->userid );

		if ( empty( $cim ) && !empty( $profileid ) ) {
			$cim = new AuthNetCim( $this->settings['login'], $this->settings['transaction_key'], $this->settings['testmode'] );

			if ( empty( $profileid ) ) {
				$cim->getCustomerProfileRequest();
			}
			$cim->setParameter( 'customerProfileId', $profileid );
			$cim->getCustomerProfileRequest();

			if ( $cim->isSuccessful() ) {
				$hascim = true;
			}
		}

		if ( $hascim ) {
			/*$cardNumber = $cim->substring_between($cim->response,'<cardNumber>','</cardNumber>');
			$cardExpiry = $cim->substring_between($cim->response,'<expirationDate>','</expirationDate>');
			$var['params']['cardNumberStatic'] = array( 'strong', _AEC_CCFORM_CARDNUMBER_NAME, strtolower( "XXXXXXXX" . $cardNumber ));
			$var['params']['cardExpiryStatic'] = array( 'strong', _AEC_CCFORM_CARDNUMBER_NAME, strtolower( $cardExpiry ));*/

			$firstname = $cim->substring_between( $cim->response,'<firstName>','</firstName>' );
			$lastname = $cim->substring_between( $cim->response,'<lastName>','</lastName>' );
		} else {
			$firstname = '';
			$lastname = '';
		}

		$var = $this->getCCform( $var, array( 'card_number', 'card_exp_month', 'card_exp_year', 'card_cvv2' ) );

		$var['params']['billFirstName'] = array( 'inputC', _AEC_USERFORM_BILLFIRSTNAME_NAME, _AEC_USERFORM_BILLFIRSTNAME_NAME, $firstname );
		$var['params']['billLastName'] = array( 'inputC', _AEC_USERFORM_BILLLASTNAME_NAME, _AEC_USERFORM_BILLLASTNAME_NAME, $lastname );

		if ( !empty( $this->settings['promptAddress'] ) ) {
			$uservalues = array( 'company', 'address', 'city', 'state', 'zip', 'country', 'phone', 'fax' );

			foreach ( $uservalues as $uv ) {
				$constant = constant( strtoupper( '_aec_userform_bill'.$uv.'_name' ) );

				if ( $hascim ) {
					if ( ( $uv == 'phone' ) || ( $uv == 'fax' ) ) {
						$value = $cim->substring_between( $cim->response,'<' . $uv . 'Number>','</' . $uv . 'Number>' );
					} else {
						$value = $cim->substring_between( $cim->response,'<' . $uv . '>','</' . $uv . '>' );
					}
				} else {
					$value = '';
				}

				$var['params']['bill'.ucfirst($uv)] = array( 'inputC', $constant, $constant, $value );
			}
		}

		return $var;
	}

	function createRequestXML( $request )
	{
		return "";
	}

	function transmitRequestXML( $xml, $request )
	{
		$return['valid'] = false;

		$cim = new AuthNetCim( $this->settings['login'], $this->settings['transaction_key'], $this->settings['testmode'] );

		$basicdata = array(	'refId'					=> $request->int_var['invoice'],
							'merchantCustomerId'	=> $request->metaUser->cmsUser->id,
							'description'			=> $request->metaUser->cmsUser->name,
							'email'					=> $request->metaUser->cmsUser->email,
							'paymentType'			=> 'creditcard',
							'cardNumber'			=> trim( $request->int_var['params']['cardNumber'] ),
							'expirationDate'		=> $request->int_var['params']['expirationYear'] . '-' . $request->int_var['params']['expirationMonth']
							);

		foreach ( $basicdata as $key => $value ) {
			$cim->setParameter( $key, $value );
		}

		$udata = array( 'billTo_firstName' => 'billFirstName', 'billTo_lastName' => 'billLastName', 'billTo_company' => 'billCompany', 'billTo_address' => 'billAddress',
						'billTo_city' => 'billCity', 'billTo_state' => 'billState', 'billTo_zip' => 'billZip', 'billTo_country' => 'billCountry',
						'billTo_phoneNumber' => 'billPhone', 'billTo_faxNumber' => 'billFax',
						'shipTo_firstName' => 'billFirstName', 'shipTo_lastName' => 'billLastName', 'shipTo_company' => 'billCompany', 'shipTo_address' => 'billAddress',
						'shipTo_city' => 'shipTo_city', 'shipTo_state' => 'billState', 'shipTo_zip' => 'billZip', 'shipTo_country' => 'billCountry',
						'shipTo_phoneNumber' => 'billPhone', 'shipTo_faxNumber' => 'billFax'
						);

		foreach ( $udata as $authvar => $aecvar ) {
			$cim->setParameter( $authvar, trim( $request->int_var['params'][$aecvar] ) );
		}

		$profileid = $this->getCustomerProfileID( $request->metaUser->userid );

		if ( $profileid ) {aecDebug('profileid found');
			$cim->setParameter( 'customerProfileId', $profileid );
			$cim->getCustomerProfileRequest();

			$cim->setParameter( 'customerProfileId',		$cim->customerProfileId );
			$cim->setParameter( 'customerPaymentProfileId',	$cim->customerPaymentProfileId );
			$cim->setParameter( 'customerAddressId',		$cim->customerAddressId );

			$cim->updateCustomerPaymentProfileRequest();
			$cim->updateCustomerShippingAddressRequest();
		} else {aecDebug('profileid NOT found');
			$cim->createCustomerProfileRequest();aecDebug(json_encode($cim));
		}

		if ( $cim->isSuccessful() ) {aecDebug('call instantly works');
			/*$li['itemId']		= $request->int_var['invoice'];
			$li['name']			= trim( substr( AECToolbox::rewriteEngine( $this->settings['item_name'], $request->metaUser, $request->new_subscription, $request->invoice ), 0, 30 ) );
			$li['description']	= trim( AECToolbox::rewriteEngine( $this->settings['item_name'], $request->metaUser, $request->new_subscription, $request->invoice ));
			$li['quantity']		= '1';
			$li['unitPrice']	= $request->int_var['amount'];
			$li['taxable']		= 'false';

			$cim->LineItems = array( $li );*/

			$cim->setParameter( 'transaction_amount',	$request->int_var['amount'] );
			$cim->setParameter( 'transactionType',		'profileTransAuthCapture' );
			$cim->setParameter( 'transactionCardCode',	trim( $request->int_var['params']['cardVV2'] ) );

			$cim->createCustomerProfileTransactionRequest();

			if ( $cim->isSuccessful() ) {aecDebug('Transaction call instantly works');
				$return['valid']	= true;
				$return['invoice']	= $cim->refId;
			} else {aecDebug('Transaction call instantly fails');
				$return['error']	= $cim->code . ": " . $cim->text;
			}
		} else {aecDebug('call instantly fails');
			$return['error']		= $cim->code . ": " . $cim->text;
		}

		return $return;
	}

	function getCustomerProfileID( $userID )
	{
		global $database;
		$row = new mosUser( $database );
		$row->load( (int) $userID );

		$userParams =& new mosParameters( $row->params );

		return (int) $userParams->get( 'customerProfileId' );
	}
}
?>