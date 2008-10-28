<?php
/**
 * @version $Id: chase_paymentech.php
 * @package AEC - Account Control Expiration - Membership Manager
 * @subpackage Processors - Chase Paymentech Orbital
 * @copyright 2008 Copyright (C) David Deutsch
 * @author David Deutsch <skore@skore.de> & Team AEC - http://www.globalnerd.org
 * @license GNU/GPL v.2 http://www.gnu.org/licenses/old-licenses/gpl-2.0.html or, at your option, any later version
 */

// Dont allow direct linking
defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );

class processor_chase_paymentech extends XMLprocessor
{
	function info()
	{
		$info = array();
		$info['name']				= 'chase_paymentech';
		$info['longname']			= _CFG_CHASE_PAYMENTECH_LONGNAME;
		$info['statement']			= _CFG_CHASE_PAYMENTECH_STATEMENT;
		$info['description']		= _CFG_CHASE_PAYMENTECH_DESCRIPTION;
		$info['currencies']			= AECToolbox::_aecCurrencyField( true, true, true, true );
		$info['cc_list']			= "visa,mastercard,discover,americanexpress,echeck,jcb,dinersclub";
		$info['recurring']			= 2;
		$info['recurring_buttons']	= 2;
		$info['actions']			= array('cancel');
		$info['secure']				= 1;

		return $info;
	}

	function settings()
	{
		$settings = array();
		$settings['testmode']			= 0;
		$settings['merchant_id']		= 'login';
		$settings['terminal_id']		= '001';
		$settings['BIN']				= '000002';
		$settings['currency']			= 'USD';
		$settings['pay_types']			= array( 'cc' );
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
		$settings['merchant_id'] 		= array( 'inputC' );
		$settings['terminal_id'] 		= array( 'inputC' );
		$settings['BIN']		 		= array( 'inputC' );
		$settings['currency']			= array( 'list_currency' );
		$settings['pay_types']			= array( 'list' );
		$settings['promptAddress']		= array( 'list_yesno' );
		$settings['promptZipOnly']		= array( 'list_yesno' );
		$settings['item_name']			= array( 'inputE' );
		$settings['customparams']		= array( 'inputD' );

		$paytypes = array( 'cc', 'echeck', 'eudd', 'gc', 'debit' );

		$paytypes = array();
		foreach ( $paytypes as $name ) {
			$desc = constant( '_AEC_'.strtoupper($name).'FORM_TABNAME' );

			$paytypes_selection[] = mosHTML::makeOption( $name, $desc );

			if ( in_array( $name, $this->settings['pay_types'] ) ) {
				$pt[] = mosHTML::makeOption( $name, $desc );
			}
		}

		$s['lists']['bank']	= mosHTML::selectList( $paytypes_selection, 'chase_paymentech_pay_types', 'size="5"', 'value', 'text', $pt );

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
		$ppParams = $request->metaUser->meta->getProcessorParams( $request->parent->id );

		$post = aecPostParamClear( $_POST, true );

		if ( !empty( $post['edit_payprofile'] ) && ( $post['payprofileselect'] != "new" ) ) {
			$ppParams->paymentprofileid = $post['payprofileselect'];
		}

		if ( isset( $post['billFirstName'] ) && ( strpos( $post['cardNumber'], 'X' ) === false ) ) {

			if ( !empty( $post['cardNumber'] ) || !empty( $post['account_no'] ) ) {
				if( !empty( $post['account_no'] ) ) {
					$basicdata['paymentType']		= 'echeck';
					$basicdata['accountType']		= 'checking';
					$basicdata['routingNumber']		= $post['routing_no'];
					$basicdata['accountNumber']		= $post['account_no'];
					$basicdata['nameOnAccount']		= $post['account_name'];
					$basicdata['echeckType']		= 'CCD';
					$basicdata['bankName']			= $post['bank_name'];
				} else {
					$basicdata['paymentType']		= 'creditcard';
					$basicdata['cardNumber']		= trim( $post['cardNumber'] );
					$basicdata['expirationDate']	= $post['expirationYear'] . '-' . $post['expirationMonth'];
				}

				if ( $post['payprofileselect'] == "new" ) {
					$ppParams = $this->createProfileRequest();

					if ( !empty( $profileid ) ) {
						$ppParams = $this->payProfileAdd( $request, $profileid, $post, $ppParams );
					}
				} else {
					if ( isset( $ppParams->paymentProfiles->{$post['payprofileselect']} ) ) {
						$stored_spid = $ppParams->paymentProfiles->{$post['payprofileselect']}->profileid;
						$cim->setParameter( 'customerPaymentProfileId', $stored_spid );
						$cim->updateCustomerPaymentProfileRequest();

						if ( $cim->isSuccessful() ) {
							$this->payProfileUpdate( $request, $post['payprofileselect'], $post, $ppParams );
						}
					}
				}

				$cim->updateCustomerPaymentProfileRequest();

				$cim->setParameter( 'customerProfileId',		$cim->customerProfileId );
				$cim->setParameter( 'customerPaymentProfileId',	$cim->customerPaymentProfileId );
			}

		}

		$var = $this->ppProfileSelect( array(), $ppParams, true, $ppParams );
		$var2 = $this->checkoutform( $request );

		$return = '<form action="' . AECToolbox::deadsureURL( '/index.php?option=com_acctexp&amp;task=subscriptiondetails', true ) . '" method="post">' . "\n";
		$return .= $this->getParamsHTML( $var ) . '<br /><br />';
		$return .= $this->getParamsHTML( $var2 ) . '<br /><br />';
		$return .= '<input type="hidden" name="userid" value="' . $request->metaUser->userid . '" />' . "\n";
		$return .= '<input type="hidden" name="task" value="subscriptiondetails" />' . "\n";
		$return .= '<input type="hidden" name="sub" value="chase_paymentech_details" />' . "\n";
		$return .= '<input type="submit" class="button" value="' . _BUTTON_APPLY . '" /><br /><br />' . "\n";
		$return .= '</form>' . "\n";

		return $return;
	}

	function checkoutform( $request, $nobill=false, $ppParams=false )
	{
		$var = array();
		$vcontent = array();

		if ( $ppParams === false ) {
			$ppParams = $request->metaUser->meta->getProcessorParams( $request->parent->id );
		}

		if ( !$nobill ) {

			$vcontent = array();

			$cccontent['card_number'] = $cim->substring_between( $cim->response,'<cardNumber>','</cardNumber>' );
			$cccontent['account_no'] = $cim->substring_between( $cim->response,'<accountNumber>','</accountNumber>' );
			$cccontent['routing_no'] = $cim->substring_between( $cim->response,'<routingNumber>','</routingNumber>' );

			if ( empty( $this->settings['pay_types'] ) ) {
				$this->settings['pay_types'] = array( 'cc' );
			}

			foreach ( $this->settings['pay_types'] as $type ) {
				switch ( $type ) {
					case 'cc':
						$array[$type] = array( 'values' => array( 'card_number', 'card_exp_month', 'card_exp_year', 'card_cvv2' ), 'vcontent' => $cccontent );
						break;
					case 'echeck':
						$array[$type] = array( 'values' => array( 'card_number', 'card_exp_month', 'card_exp_year', 'card_cvv2' ), 'vcontent' => $vcontent );
						break;
					case 'eudd':
						$array[$type] = array( 'values' => array( 'card_number', 'card_exp_month', 'card_exp_year', 'card_cvv2' ), 'vcontent' => $vcontent );
						break;
					case 'gc':
						$array[$type] = array( 'values' => array( 'card_number', 'card_exp_month', 'card_exp_year', 'card_cvv2' ), 'vcontent' => $vcontent );
						break;
					case 'debit':
						$array[$type] = array( 'values' => array( 'card_number', 'card_exp_month', 'card_exp_year', 'card_cvv2' ), 'vcontent' => $vcontent );
						break;
				}
			}

			$this->getMULTIPAYform( $var, $array );

		}

		if ( !empty( $this->settings['promptAddress'] ) ) {
			$uservalues = array( 'firstName', 'lastName', 'company', 'address', 'address2', 'city', 'state', 'zip', 'country', 'phone', 'fax' );

			$content = array();

			foreach ( $uservalues as $uv ) {
				if ( in_array( $uv, array( 'phone', 'fax' ) ) ) {
					$content[$uv] = $cim->substring_between( $cim->response,'<' . $uv . 'Number>','</' . $uv . 'Number>' );
				} else {
					if ( $nobill && ( $uv == 'address' ) ) {
						$content[$uv] = $cim->substring_between( $cim->response,'<' . $uv . '>','</' . $uv . '>', 1 );
					} else {
						$content[$uv] = $cim->substring_between( $cim->response,'<' . $uv . '>','</' . $uv . '>' );
					}
				}
			}

			$var = $this->getUserform( $var, $uservalues, $request->metaUser, $content );
		}

		return $var;
	}

	function checkoutAction( $request )
	{
		global $aecConfig;

		$ppParams = $request->metaUser->meta->getProcessorParams( $request->parent->id );

		// Actual form, with ProfileID reference numbers as options

		$return = '<form action="' . AECToolbox::deadsureURL( '/index.php?option=com_acctexp&amp;task=checkout', true ) . '" method="post">' . "\n";

		if ( !empty( $ppParams ) ) {
			$var = array();
			$var = $this->ppProfileSelect( $var, $ppParams, false, false );

			$return .= $this->getParamsHTML( $var ) . '<br /><br />';
		}

		$return .= $this->getParamsHTML( $this->checkoutform( $request ) ) . '<br /><br />';
		$return .= '<input type="hidden" name="invoice" value="' . $request->int_var['invoice'] . '" />' . "\n";
		$return .= '<input type="hidden" name="userid" value="' . $request->metaUser->userid . '" />' . "\n";
		$return .= '<input type="hidden" name="task" value="checkout" />' . "\n";
		$return .= '<input type="submit" class="button" value="' . _BUTTON_CHECKOUT . '" /><br /><br />' . "\n";
		$return .= '</form>' . "\n";

		return $return;
	}

	function fetchProfile( $ppParams )
	{
		$dom = new DOMDocument( '1.0', 'utf-8' );

		$R = $dom->appendChild( new DOMElement( 'Request' ) );
		$P = $R->appendChild( new DOMElement( 'Profile' ) );

		$var = array();

		$var['CustomerBin']			= $this->settings['BIN'];
		$var['CustomerMerchantID']	= $this->settings['merchant_id'];

		foreach ( $var as $k => $v ) {
			$P->appendChild( new DOMElement( $k, $v ) );
		}

		$xml = $this->extractXML( $this->transmitChase( $dom->saveXML() ) );

		if ( isset( $xml['Response']['profileResp'] ) ) {
			return $xml['Response']['profileResp'];
		} else {
aecDebug( $xml );
			return array();
		}
	}

	function createRequestXML( $request )
	{
		$dom = new DOMDocument( '1.0', 'utf-8' );

		$R = $dom->appendChild( new DOMElement( 'Request' ) );
		$NO = $R->appendChild( new DOMElement( 'NewOrder' ) );

		$var = array();

		if ( is_array( $request->int_var['amount'] ) ) {
			$var['IndustryType']	= 'RC';
		} else {
			$var['IndustryType']	= 'EC';
		}

		$var['MessageType']			= 'A';

		$this->appendAccountData( $var );
		$this->appendCurrencyData( $var );

		$this->appendPayData( $var, $request );

		foreach ( $var as $k => $v ) {
			$NO->appendChild( new DOMElement( $k, $v ) );
		}

		return $dom->saveXML();
	}

	function appendAccountData( &$var )
	{
		$var['BIN']			= $this->settings['BIN'];
		$var['MerchantID']	= $this->settings['merchant_id'];
		$var['TerminalID']	= $this->settings['terminal_id'];
	}

	function appendCurrencyData( &$var )
	{
		$var['CurrencyCode']		= AECToolbox::_aecNumCurrency( $this->settings['currency'] );
		$var['CurrencyExponent']	= AECToolbox::_aecCurrencyExp( $this->settings['currency'] );
	}

	function appendPayData( &$var, $request )
	{
		if( !empty( $request->int_var['params']['account_no'] ) ) {
			$basicdata['CheckDDA']		= $request->int_var['params']['account_no'];
		} else {
			$var['AccountNum']	= $request->int_var['params']['cardNumber'];
			$var['Exp']			= $request->int_var['params']['expirationYear'] . $request->int_var['params']['expirationMonth'];

			if ( !empty( $request->int_var['params']['cvv2'] ) ) {
				$var['CardSecValInd']	= '1';
				$var['CardSecVal']	= $request->int_var['params']['cvv2'];
			}
		}
	}

	function transmitChase( $xml )
	{
		$search = '<?xml version="1.0" encoding="utf-8"?>';

		if ( strpos( $search, $xml ) !== false ) {
			$xml = str_replace( $search, '', $xml );
		}

		$path = '/authorize';

		if ( $this->settings['testmode'] ) {
			$url = 'https://orbitalvar1.paymentech.net' . $path;
		} else {
			$url = 'https://orbital1.paymentech.net' . $path;
		}

		$curlextra = array();

		$response = $this->transmitRequest( $url, $path, $xml, 443, $curlextra );

		return $this->extractXML( simplexml_load_string( $response ) );
	}

	function extractXML( $xml )
	{
		if (!($xml->children())) {
			return (string) $xml;
		}

		foreach ( $xml->children() as $child ) {
			$name = $child->getName();

			if ( count( $xml->$name ) == 1 ) {
				$element[$name] = $this->extractXML( $child );
			} else {
				$element[][$name] = $this->extractXML( $child );
			}
		}

		return $element;
	}

	function transmitRequestXML( $xml, $request )
	{
		$return['valid'] = false;

		$response = $this->transmitChase( $xml );

		if ( isset( $response['Response']['NewOrderResp'] ) ) {
			$r = $response['Response']['NewOrderResp'];
		} else {
			$r = array();
aecDebug( $response );
		}

		if ( $cim->isSuccessful() ) {
			if ( is_array( $request->int_var['amount'] ) ) {
				$cim->setParameter( 'transactionRecurringBilling',	'true' );
				if ( isset( $request->int_var['amount']['amount1'] ) ) {
					$cim->setParameter( 'transaction_amount',	$request->int_var['amount']['amount1'] );
				} else {
					$cim->setParameter( 'transaction_amount',	$request->int_var['amount']['amount3'] );
				}
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

	function ppProfileSelect( $var, $ppParams, $select=false, $btn=true )
	{
		$profiles = get_object_vars( $ppParams->paymentProfiles );

		$var['params'][] = array( 'p', _AEC_USERFORM_BILLING_DETAILS_NAME, '' );

		if ( !empty( $profiles ) ) {
			// Single-Select Payment Option
			foreach ( $profiles as $pid => $pobj ) {
				$info = array();

				$info_array = get_object_vars( $pobj->profilehash );

				foreach ( $info_array as $iak => $iav ) {
					if ( !empty( $iav ) ) {
						$info[] = $iav;
					}
				}

				if ( $ppParams->paymentprofileid == $pid ) {
					$text = '<strong>' . implode( '<br />', $info ) . '</strong>';
				} else {
					$text = implode( '<br />', $info );
				}

				$var['params'][] = array( 'radio', 'payprofileselect', $pid, $ppParams->paymentprofileid, $text );
			}

			if ( count( $profiles ) < 10 ) {
				$var['params'][] = array( 'radio', 'payprofileselect', "new", $ppParams->paymentprofileid, 'create new profile' );
			}

			if ( $btn ) {
				$var['params']['edit_payprofile'] = array( 'submit', '', '', ( $select ? _BUTTON_SELECT : _BUTTON_EDIT ) );
			}
		}

		return $var;
	}

	function ProfileAdd( $request, $profileid )
	{
		$ppParams = new stdClass();

		$ppParams->profileid			= $profileid;

		$ppParams->paymentprofileid		= '';
		$ppParams->paymentProfiles		= new stdClass();

		$ppParams->shippingprofileid	= '';
		$ppParams->shippingProfiles		= new stdClass();

		$request->metaUser->meta->setProcessorParams( $request->parent->id, $ppParams );

		return $ppParams;
	}

	function payProfileAdd( $request, $profileid, $post, $ppParams )
	{
		$profiles = get_object_vars( $ppParams->paymentProfiles );
		$pointer = count( $profiles );

		$data = new stdClass();
		$data->profileid = $profileid;
		$data->profilehash = $this->payProfileHashFromPost( $post );

		$ppParams->paymentProfiles->$pointer = $data;

		$ppParams->paymentprofileid = $pointer;

		$request->metaUser->meta->setProcessorParams( $request->parent->id, $ppParams );

		return $ppParams;
	}

	function payProfileUpdate( $request, $profileid, $post, $ppParams )
	{
		$ppParams->paymentProfiles->$profileid->profilehash = $this->payProfileHashFromPost( $post );

		$ppParams->paymentprofileid = $profileid;

		$request->metaUser->meta->setProcessorParams( $request->parent->id, $ppParams );

		return $ppParams;
	}

	function payProfileHashFromPost( $post )
	{
		$hash = new stdClass();
		$hash->name		= $post['billFirstName'] . ' ' . $post['billLastName'];
		$hash->address	= $post['billAddress'];
		$hash->zipcity	= $post['billZip'] . ' ' . $post['billCity'];

		if ( !empty( $post['account_no'] ) ) {
			$hash->cc		= 'XXXX' . substr( $post['account_no'], -4 );
		} else {
			$hash->cc		= 'XXXX' . substr( $post['cardNumber'], -4 );
		}

		return $hash;
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

		$metaUser = new metaUser( $invoice->userid );
		$ppParams = $metaUser->meta->getProcessorParams( $request->parent->id );

		if ( !empty( $ppParams->profileid ) ) {

			if ( true ) {
				$invoice->pay();
				return true;
			}
		}

		return false;
	}

}
?>