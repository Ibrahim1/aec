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

class processor_paypal_wpp extends XMLprocessor
{
	function info()
	{
		$info = array();
		$info['name']			= 'paypal_wpp';
		$info['longname']		= _CFG_PAYPAL_WPP_LONGNAME;
		$info['statement']		= _CFG_PAYPAL_WPP_STATEMENT;
		$info['description']	= _CFG_PAYPAL_WPP_DESCRIPTION;
		$info['currencies']		= 'EUR,USD,GBP,AUD,CAD,JPY,NZD,CHF,HKD,SGD,SEK,DKK,PLN,NOK,HUF,CZK';
		$info['languages']		= 'GB,DE,FR,IT,ES,US,NL';
		$info['cc_list']		= 'visa,mastercard,discover,americanexpress,echeck,giropay';
		$info['recurring']		= 2;
		$info['actions']		= 'cancel';
		$info['secure']			= 1;

		return $info;
	}

	function settings()
	{
		$settings = array();
		$settings['testmode']				= 0;
		$settings['currency']				= 'USD';

		$settings['api_user']				= '';
		$settings['api_password']			= '';
		$settings['use_certificate']		= '';
		$settings['certificate_path']	= '';
		$settings['signature']				= '';
		$settings['country']				= 'US';

		$settings['item_name']				= sprintf( _CFG_PROCESSOR_ITEM_NAME_DEFAULT, '[[cms_live_site]]', '[[user_name]]', '[[user_username]]' );

		return $settings;
	}

	function backend_settings()
	{
		$settings = array();
		$settings['testmode']				= array( 'list_yesno' );
		$settings['currency']				= array( 'list_currency' );

		$settings['api_user']				= array( 'inputC' );
		$settings['api_password']			= array( 'inputC' );
		$settings['use_certificate']		= array( 'list_yesno' );
		$settings['certificate_path']	= array( 'inputC' );
		$settings['signature'] 			= array( 'inputC' );
		$settings['country'] 				= array( 'list' );

		$settings['cancel_note']			= array( 'inputE' );
		$settings['item_name']				= array( 'inputE' );

		$country_sel = array();
		$country_sel[] = mosHTML::makeOption( 'US', 'US' );
		$country_sel[] = mosHTML::makeOption( 'UK', 'UK' );

		$settings['lists']['country'] = mosHTML::selectList( $country_sel, 'country', 'size="2"', 'value', 'text', $this->settings['country'] );

		$settings = AECToolbox::rewriteEngineInfo( null, $settings );

		return $settings;
	}

	function checkoutform( $request )
	{
		global $mosConfig_live_site;

		$var = array();

		$values = array( 'card_type', 'card_number', 'card_exp_month', 'card_exp_year', 'card_cvv2' );
		$var = $this->getCCform( $var, $values );

		$values = array( 'firstname', 'lastname', 'address', 'address2', 'city', 'state_usca', 'zip', 'country_list' );
		$var = $this->getUserform( $var, $values, $request->metaUser );

		return $var;
	}

	function createRequestXML( $request )
	{
		global $mosConfig_live_site, $mosConfig_offset_user;

		$var = array();

		if ( is_array( $request->int_var['amount'] ) ) {
			$var['Method']			= 'CreateRecurringPaymentsProfile';
		} else {
			$var['Method']			= 'DoDirectPayment';
		}

		if ( is_array( $request->int_var['amount'] ) ) {
			$var['Version']			= '50.0';
		} else {
			$var['Version']			= '3.2';
		}

		$var['user']				= $this->settings['api_user'];
		$var['pwd']					= $this->settings['api_password'];
		$var['signature']			= $this->settings['signature'];

		$var['paymentAction']		= 'Sale';
		$var['IPaddress']			= $_SERVER['REMOTE_ADDR'];
		$var['firstName']			= trim( $request->int_var['params']['billFirstName'] );
		$var['lastName']			= trim( $request->int_var['params']['billLastName'] );
		$var['creditCardType']		= $request->int_var['params']['cardType'];
		$var['acct']				= $request->int_var['params']['cardNumber'];
		$var['expDate']				= str_pad( $request->int_var['params']['expirationMonth'], 2, '0', STR_PAD_LEFT ).$request->int_var['params']['expirationYear'];

		$var['CardVerificationValue'] = $request->int_var['params']['cardVV2'];
		$var['cvv2']				= $request->int_var['params']['cardVV2'];

		$var['street']				= $request->int_var['params']['billAddress'];

		if ( !empty( $request->int_var['params']['billAddress2'] ) ) {
			$var['street2']			= $request->int_var['params']['billAddress2'];
		}

		$var['city']				= $request->int_var['params']['billCity'];
		$var['state']				= $request->int_var['params']['billState'];
		$var['zip']					= $request->int_var['params']['billZip'];
		$var['countrycode']			= $request->int_var['params']['billCountry'];
		$var['NotifyUrl']			= AECToolbox::deadsureURL( '/index.php?option=com_acctexp&amp;task=paypal_wppnotification' );
		$var['desc']				= AECToolbox::rewriteEngine( $this->settings['item_name'], $request->metaUser, $request->new_subscription, $request->invoice );
		$var['InvNum']				= $request->int_var['invoice'];;

		if ( is_array( $request->int_var['amount'] ) ) {
			// $var['InitAmt'] = 'Initial Amount'; // Not Supported Yet
			// $var['FailedInitAmtAction'] = 'ContinueOnFailure'; // Not Supported Yet (optional)

			if ( isset( $request->int_var['amount']['amount1'] ) ) {
				$trial = $this->convertPeriodUnit( $request->int_var['amount']['period1'], $request->int_var['amount']['unit1'] );

				$var['TrialBillingPeriod']		= $trial['unit'];
				$var['TrialBillingFrequency']	= $trial['period'];
				$var['TrialAmt']				= $request->int_var['amount']['amount1'];
				$var['TrialTotalBillingCycles'] = 1; // Not Fully Supported Yet

				switch ( $request->int_var['amount']['unit1'] ) {
					case 'D': $offset = $request->int_var['amount']['period1'] * 3600 * 24; break;
					case 'W': $offset = $request->int_var['amount']['period1'] * 3600 * 24 * 7; break;
					case 'M': $offset = $request->int_var['amount']['period1'] * 3600 * 24 * 31; break;
					case 'Y': $offset = $request->int_var['amount']['period1'] * 3600 * 24 * 356; break;
				}

				$timestamp = time() - ($mosConfig_offset_user*3600) + $offset;
			} else {
				$timestamp = time() - $mosConfig_offset_user*3600;
			}

			$var['ProfileStartDate']    = date( 'Y-m-d', $timestamp ) . 'T' . date( 'H:i:s', $timestamp ) . 'Z';

			$full = $this->convertPeriodUnit( $request->int_var['amount']['period3'], $request->int_var['amount']['unit3'] );

			$var['BillingPeriod']		= $full['unit'];
			$var['BillingFrequency']	= $full['period'];
			$var['amt']					= $request->int_var['amount']['amount3'];
			$var['ProfileReference']	= $request->int_var['invoice'];
		} else {
			$var['amt']					= $request->int_var['amount'];
		}

		$var['currencyCode']			= $this->settings['currency'];

		$content = array();
		foreach ( $var as $name => $value ) {
			$content[] .= strtoupper( $name ) . '=' . urlencode( stripslashes( $value ) );
		}

		return implode( '&', $content );
	}

	function transmitRequestXML( $xml, $request )
	{
		$path = "/nvp";

		if ( $this->settings['testmode'] ) {
			if ( $this->settings['use_certificate'] ) {
				$url = "https://api.sandbox.paypal.com" . $path;
			} else {
				$url = "https://api-3t.sandbox.paypal.com" . $path;
			}
		} else {
			if ( $this->settings['use_certificate'] ) {
				$url = "https://api.paypal.com" . $path;
			} else {
				$url = "https://api-3t.paypal.com" . $path;
			}
		}

		$curlextra = array();
		$curlextra[CURLOPT_VERBOSE] = 1;

		$response = $this->transmitRequest( $url, $path, $xml, 443, $curlextra );

		$return['valid'] = false;
		$return['raw'] = $response;

		// converting NVPResponse to an Associative Array
		$nvpResArray = $this->deformatNVP( $response );

		if ( $response ) {
			$return['invoice'] = $request->invoice->invoice_number;
			$resultCode = strtoupper( $nvpResArray["ACK"] );

			if ( strcmp( $resultCode, 'SUCCESS' ) === 0) {
				if ( is_array( $request->int_var['amount'] ) ) {
					$subscriptionId = $nvpResArray['PROFILEID'];
					$return['invoiceparams'] = array( "subscriptionid" => $subscriptionId );

					if ( !isset( $nvpResArray['STATUS'] ) ) {
						$return['valid'] = 1;
					} elseif ( strtoupper( $response['STATUS'] ) == 'ACTIVEPROFILE' ) {
						$return['valid'] = 1;
					} else {
						$response['pending_reason'] = 'pending';
					}
				} else {
					$return['valid'] = 1;
				}
			} else {
				$count = 0;
				while ( isset( $nvpResArray["L_SHORTMESSAGE".$count] ) ) {
						$return['error'] .= 'Error ' . $nvpResArray["L_ERRORCODE".$count] . ' = ' . $nvpResArray["L_SHORTMESSAGE".$count] . ' (' . $nvpResArray["L_LONGMESSAGE".$count] . ')' . "\n";
						$count=$count+1;
				}
			}
		}

		return $return;
	}

	function convertPeriodUnit( $period, $unit )
	{
		$return = array();
		switch ( $unit ) {
			case 'D':
				$return['unit'] = 'Day';
				$return['period'] = $period;
				break;
			case 'W':
				$return['unit'] = 'Week';
				$return['period'] = $period;
				break;
			case 'M':
				$return['unit'] = 'Month';
				$return['period'] = $period;
				break;
			case 'Y':
				$return['unit'] = 'Year';
				$return['period'] = $period;
				break;
		}

		return $return;
	}

	function customaction_cancel( $request )
	{
		return $this->ManageRecurringPaymentsProfileStatus( $request, 'Cancel', $this->settings['cancel_note'] );
	}

	function ManageRecurringPaymentsProfileStatus( $request, $command, $note )
	{
		$var['Method']				= 'ManageRecurringPaymentsProfileStatus';
		$var['Version']			= '3.0';
		$var['user']				= $this->settings['api_user'];
		$var['pwd']					= $this->settings['api_password'];
		$var['signature']			= $this->settings['signature'];

		$invoiceparams = $request->invoice->getParams();

		// Add Payment information
		$var['profileid'] = $invoiceparams['subscriptionid'];

		$var['action']				= $command;
		$var['note']				= $note;

		$content = array();
		foreach ( $var as $name => $value ) {
			$content[] .= strtoupper( $name ) . '=' . urlencode( $value );
		}

		$xml =  implode( '&', $content );

		$path = "/nvp";

		if ( $this->settings['testmode'] ) {
			$url = "https://api.sandbox.paypal.com" . $path;
		} else {
			if ( $this->settings['use_certificate'] ) {
				$url = "https://api-3t.sandbox.paypal.com" . $path;
			} else {
				$url = "https://api.paypal.com" . $path;
			}
		}

		$curlextra = array();
		$curlextra[CURLOPT_VERBOSE] = 1;

		$response = $this->transmitRequest( $url, $path, $xml, 443, $curlextra );

		// converting NVPResponse to an Associative Array
		$nvpResArray = $this->deformatNVP( $response );

		if ( !empty( $response ) ) {
			$return['invoice'] = $request->invoice->invoice_number;

			if ( isset( $nvpResArray['PROFILEID'] ) ) {
				if ( $nvpResArray['PROFILEID'] == $var['profileid'] ) {
					$return['valid'] = 0;
					$return['cancel'] = true;
				} else {
				$return['valid'] = 0;
				$return['error'] = 'Could not transmit Cancel Message - Wrong Profile ID returned';
				}
			} else {
				$return['valid'] = 0;
				$return['error'] = 'Could not transmit Cancel Message - General Failure';
			}

			return $return;
		} else {
			Payment_HTML::error( 'com_acctexp', $request->metaUser->cmsUser, $request->invoice, "An error occured while cancelling your subscription. Please contact the system administrator!", true );
		}
	}

	function deformatNVP( $nvpstr )
	{

		$intial=0;
	 	$nvpArray = array();
		while ( strlen( $nvpstr ) ) {
			//postion of Key
			$keypos= strpos($nvpstr,'=');
			//position of value
			$valuepos = strpos($nvpstr,'&') ? strpos($nvpstr,'&'): strlen($nvpstr);

			/*getting the Key and Value values and storing in a Associative Array*/
			$keyval=substr($nvpstr,$intial,$keypos);
			$valval=substr($nvpstr,$keypos+1,$valuepos-$keypos-1);
			//decoding the respose
			$nvpArray[urldecode($keyval)] =urldecode( $valval);
			$nvpstr=substr($nvpstr,$valuepos+1,strlen($nvpstr));
		}

		return $nvpArray;
	}

}

?>