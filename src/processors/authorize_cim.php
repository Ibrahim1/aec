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
		if ( isset( $_POST['billFirstName'] ) && ( strpos( $request->int_var['params']['cardNumber'], 'X' ) === false ) ) {
			$cim = new AuthNetCim( $this->settings['login'], $this->settings['transaction_key'], $this->settings['testmode'] );

			$profileid = $metaUser->getCMSparams( 'customerProfileId' );

			$cim->setParameter( 'customerProfileId', $profileid );
			$cim->getCustomerProfileRequest();

			$udata = array( 'billTo_firstName' => 'billFirstName', 'billTo_lastName' => 'billLastName', 'billTo_company' => 'billCompany', 'billTo_address' => 'billAddress',
							'billTo_city' => 'billCity', 'billTo_state' => 'billState', 'billTo_zip' => 'billZip', 'billTo_country' => 'billCountry',
							'billTo_phoneNumber' => 'billPhone', 'billTo_faxNumber' => 'billFax',
							'shipTo_firstName' => 'billFirstName', 'shipTo_lastName' => 'billLastName', 'shipTo_company' => 'billCompany', 'shipTo_address' => 'billAddress',
							'shipTo_city' => 'shipTo_city', 'shipTo_state' => 'billState', 'shipTo_zip' => 'billZip', 'shipTo_country' => 'billCountry',
							'shipTo_phoneNumber' => 'billPhone', 'shipTo_faxNumber' => 'billFax'
							);

			foreach ( $udata as $authvar => $aecvar ) {
				if ( !empty( $_POST[$aecvar] ) ) {
					$cim->setParameter( $authvar, trim( $_POST[$aecvar] ) );
				}
			}

			$cim->setParameter( 'customerProfileId',		$cim->customerProfileId );
			$cim->setParameter( 'customerPaymentProfileId',	$cim->customerPaymentProfileId );
			$cim->setParameter( 'customerAddressId',		$cim->customerAddressId );

			if ( !empty( $_POST['cardNumber'] ) ) {
				$basicdata = array(	'paymentType'			=> 'creditcard',
									'cardNumber'			=> trim( $_POST['cardNumber'] ),
									'expirationDate'		=> $_POST['expirationYear'] . '-' . $_POST['expirationMonth']
									);

				foreach ( $basicdata as $key => $value ) {
					$cim->setParameter( $key, $value );
				}

				$cim->updateCustomerPaymentProfileRequest();

				$cim->setParameter( 'customerProfileId',		$cim->customerProfileId );
				$cim->setParameter( 'customerPaymentProfileId',	$cim->customerPaymentProfileId );
				$cim->setParameter( 'customerAddressId',		$cim->customerAddressId );

				foreach ( $udata as $authvar => $aecvar ) {
					if ( !empty( $_POST[$aecvar] ) ) {
						$cim->setParameter( $authvar, trim( $_POST[$aecvar] ) );
					}
				}
			}

			$cim->updateCustomerShippingAddressRequest();

			$cim = null;
			$cim = new AuthNetCim( $this->settings['login'], $this->settings['transaction_key'], $this->settings['testmode'] );
			$cim->setParameter( 'customerProfileId', $profileid );
			$cim->getCustomerProfileRequest();
		} else {
			$cim = null;
			$profileid = null;
		}

		$var = $this->checkoutform( $request, $cim, $profileid );

		$return = '<form action="' . AECToolbox::deadsureURL( '/index.php?option=com_acctexp&amp;task=authorize_cim_details', true ) . '" method="post">' . "\n";
		$return .= $this->getParamsHTML( $var ) . '<br /><br />';
		$return .= '<input type="hidden" name="userid" value="' . $request->metaUser->userid . '" />' . "\n";
		$return .= '<input type="hidden" name="task" value="subscriptiondetails" />' . "\n";
		$return .= '<input type="hidden" name="sub" value="authorize_cim_details" />' . "\n";
		$return .= '<input type="submit" class="button" value="' . _BUTTON_APPLY . '" /><br /><br />' . "\n";
		$return .= '</form>' . "\n";

		return $return;
	}

	function checkoutform( $request, $cim=null, $profileid=null )
	{
		$var = array();
		$hascim = false;

		if ( empty( $profileid ) ) {
			$profileid = $metaUser->getCMSparams( 'customerProfileId' );
		}

		if ( empty( $cim ) && !empty( $profileid ) ) {
			$cim = new AuthNetCim( $this->settings['login'], $this->settings['transaction_key'], $this->settings['testmode'] );

			$cim->setParameter( 'customerProfileId', $profileid );
			$cim->getCustomerProfileRequest();

			if ( $cim->isSuccessful() ) {
				$hascim = true;
			}
		} elseif ( !empty( $cim ) && !empty( $profileid ) ) {
			$hascim = true;
		}

		if ( $hascim ) {
			$var['params']['billUpdateInfo'] = array( 'p', _AEC_CCFORM_UPDATE_NAME, _AEC_CCFORM_UPDATE_DESC, '' );

			$vcontent['card_number'] = $cim->substring_between( $cim->response,'<cardNumber>','</cardNumber>' );

			$firstname = $cim->substring_between( $cim->response,'<firstName>','</firstName>' );
			$lastname = $cim->substring_between( $cim->response,'<lastName>','</lastName>' );
		} else {
			$firstname = '';
			$lastname = '';
			$vcontent = '';
		}

		$var = $this->getCCform( $var, array( 'card_number', 'card_exp_month', 'card_exp_year', 'card_cvv2' ), $vcontent );

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
						'shipTo_city' => 'billCity', 'shipTo_state' => 'billState', 'shipTo_zip' => 'billZip', 'shipTo_country' => 'billCountry',
						'shipTo_phoneNumber' => 'billPhone', 'shipTo_faxNumber' => 'billFax'
						);

		foreach ( $udata as $authvar => $aecvar ) {
			if ( !empty( $request->int_var['params'][$aecvar] ) ) {
				$cim->setParameter( $authvar, trim( $request->int_var['params'][$aecvar] ) );
			}
		}

		$profileid = $metaUser->getCMSparams( 'customerProfileId' );

		if ( $profileid ) {
			$cim->setParameter( 'customerProfileId', $profileid );
			$cim->getCustomerProfileRequest();

			$cim->setParameter( 'customerProfileId',		$cim->customerProfileId );
			$cim->setParameter( 'customerPaymentProfileId',	$cim->customerPaymentProfileId );
			$cim->setParameter( 'customerAddressId',		$cim->customerAddressId );

			if ( strpos( $request->int_var['params']['cardNumber'], 'X' ) === false ) {
				$cim->updateCustomerPaymentProfileRequest();
				$cim->updateCustomerShippingAddressRequest();
			}
		} else {
			$cim->createCustomerProfileRequest();

			if ( $cim->isSuccessful() ) {
				$request->metaUser->setCMSparams( array( 'customerProfileId' => $cim->customerProfileId ) );
			}

			$cim->setParameter( 'customerProfileId',		$cim->customerProfileId );
			$cim->setParameter( 'customerPaymentProfileId',	$cim->customerPaymentProfileId );
			$cim->setParameter( 'customerAddressId',		$cim->customerAddressId );
		}

		if ( $cim->isSuccessful() ) {
			/*$li['itemId']		= $request->int_var['invoice'];
			$li['name']			= trim( substr( AECToolbox::rewriteEngine( $this->settings['item_name'], $request->metaUser, $request->new_subscription, $request->invoice ), 0, 30 ) );
			$li['description']	= trim( AECToolbox::rewriteEngine( $this->settings['item_name'], $request->metaUser, $request->new_subscription, $request->invoice ));
			$li['quantity']		= '1';
			$li['unitPrice']	= $request->int_var['amount'];
			$li['taxable']		= 'false';

			$cim->LineItems = array( $li );*/

			if ( is_array( $request->int_var['amount'] ) ) {
				//$cim->setParameter( 'transactionRecurringBilling',	'true' );
				$cim->setParameter( 'transaction_amount',	$request->int_var['amount']['amount3'] );
				// Need a system to do the rebills internally
			} else {
				$cim->setParameter( 'transaction_amount',	$request->int_var['amount'] );
			}

			$cim->setParameter( 'transactionType',		'profileTransAuthCapture' );
			$cim->setParameter( 'transactionCardCode',	trim( $request->int_var['params']['cardVV2'] ) );

			$cim->createCustomerProfileTransactionRequest();

			if ( $cim->isSuccessful() ) {
				$return['valid']	= true;
				$return['invoice']	= $cim->refId;
			} else {
				$return['error']	= $cim->code . ": " . $cim->text;
			}
		} else {
			$return['error']		= $cim->code . ": " . $cim->text;
		}

		return $return;
	}

	function prepareValidation( $subscription_list )
	{
		return true;
	}

	function validateSubscription( $subscription_id )
	{
		global $database;

		$invoice = new Invoice( $database );
		$invoice->loadbySubscriptionId( $subscription_id );

		if ( $invoice->id ) {
			$profileid = $metaUser->getCMSparams( 'customerProfileId' );

			$cim->setParameter( 'customerProfileId',		 $profileid );
			$cim->getCustomerProfileRequest();

			$cim->setParameter( 'customerProfileId',		$cim->customerProfileId );
			$cim->setParameter( 'customerPaymentProfileId',	$cim->customerPaymentProfileId );
			$cim->setParameter( 'customerAddressId',		$cim->customerAddressId );

			$invoice->computeAmount();

			$cim->setParameter( 'transaction_amount',		$invoice->amount );

			$cim->setParameter( 'refId',					$invoice->invoice_number );
			$cim->setParameter( 'merchantCustomerId',		$invoice->userid );

			$cim->setParameter( 'transactionType',			'profileTransAuthCapture' );
			$cim->setParameter( 'transactionCardCode',		trim( $request->int_var['params']['cardVV2'] ) );

			$cim->createCustomerProfileTransactionRequest();

			if ( $cim->isSuccessful() ) {
				$invoice->pay();
				return true;
			}
		}

		return false;
	}

}
?>