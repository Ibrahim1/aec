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
		$info['currencies']	= 'EUR,USD,GBP,AUD,CAD,JPY,NZD,CHF,HKD,SGD,SEK,DKK,PLN,NOK,HUF,CZK';
		$info['languages']		= 'GB,DE,FR,IT,ES,US';
		$info['cc_list']		= 'visa,mastercard,discover,americanexpress,echeck,giropay';
		$info['recurring']		= 2;
		$info['actions']		= 'cancel';
		$info['secure']		= 1;

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

	function backend_settings( $cfg )
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

		$settings['lists']['country'] = mosHTML::selectList( $country_sel, 'country', 'size="2"', 'value', 'text', $cfg['country'] );

 		$rewriteswitches 					= array("cms", "user", "expiration", "subscription", "plan");
		$settings = AECToolbox::rewriteEngineInfo( $rewriteswitches, $settings );

		return $settings;
	}

	function checkoutform( $int_var, $cfg, $metaUser, $new_subscription )
	{
		global $mosConfig_live_site;

		$var = array();

		$values = array( 'card_type', 'card_number', 'card_exp_month', 'card_exp_year', 'card_cvv2' );
		$var = $this->getCCform( $var, $values );

		$values = array( 'firstname', 'lastname', 'address', 'address2', 'city', 'state_us', 'zip', 'country' );
		$var = $this->getUserform( $var, $values, $metaUser );

		return $var;
	}

	function createRequestXML( $int_var, $cfg, $metaUser, $new_subscription, $invoice )
	{
		global $mosConfig_live_site;

		$var = array();

		if ( is_array( $int_var['amount'] ) ) {
			$var['Method']				= 'CreateRecurringPaymentsProfile';
		} else {
			$var['Method']				= 'DoDirectPayment';
		}

		$var['Version']			= '3.0';
		$var['user']				= $cfg['api_user'];
		$var['pwd']					= $cfg['api_password'];
		$var['signature']			= $cfg['signature'];

		$var['paymentAction']		= 'Sale';
		$var['IPaddress']			= $_SERVER['REMOTE_ADDR'];
		$var['firstName']			= trim( $int_var['params']['billFirstName'] );
		$var['lastName']			= trim( $int_var['params']['billLastName'] );
		$var['creditCardType']	= $int_var['params']['cardType'];
		$var['acct']				= $int_var['params']['cardNumber'];
		$var['expDate']			= str_pad( $int_var['params']['expirationMonth'], 2, '0', STR_PAD_LEFT ).$int_var['params']['expirationYear'];

		if ( is_array( $int_var['amount'] ) ) {
			$var['CardVerificationValue'] = $int_var['params']['cardVV2'];
		} else {
			$var['cvv2']			= $int_var['params']['cardVV2'];
		}

		$var['street']				= $int_var['params']['billAddress'];

		if ( !empty( $int_var['params']['billAddress2'] ) ) {
			$var['street2']			= $int_var['params']['billAddress2'];
		}

		$var['city']				= $int_var['params']['billCity'];
		$var['state']				= $int_var['params']['billState'];
		$var['zip']					= $int_var['params']['billZip'];
		$var['countryCode']		= $cfg['country'];
		$var['NotifyUrl']			= AECToolbox::deadsureURL( '/index.php?option=com_acctexp&amp;task=paypal_wppnotification' );
		$var['desc']				= AECToolbox::rewriteEngine( $cfg['item_name'], $metaUser, $new_subscription );
		$var['InvNum']				= $int_var['invoice'];;

		if ( is_array( $int_var['amount'] ) ) {
			// $var['InitAmt'] = 'Initial Amount'; // Not Supported Yet
			// $var['FailedInitAmtAction'] = 'ContinueOnFailure'; // Not Supported Yet (optional)

			if ( isset( $int_var['amount']['amount1'] ) ) {
				$trial = $this->convertPeriodUnit( $int_var['amount']['period3'], $int_var['amount']['unit3'] );

				$var['TrialBillingPeriod']		= $trial['period'];
				$var['TrialBillingFrequency']	= $trial['unit'];
				$var['TrialAmt']					= $int_var['amount']['amount1'];
				// $var['TrialTotalBillingCycles'] = $int_var['amount']['amount1']; // Not Supported Yet
			}

			$full = $this->convertPeriodUnit( $int_var['amount']['period3'], $int_var['amount']['unit3'] );

			$var['ProfilerStartDate']	= trim( date( 'Y-m-d' ) );
			$var['BillingPeriod']			= $full['period'];
			$var['BillingFrequency']		= $full['unit'];
			$var['amt']						= $int_var['amount']['amount3'];
		} else {
			$var['amt']						= $int_var['amount'];
		}

		$var['currencyCode']		= $cfg['currency'];

		$content = array();
		foreach ( $var as $name => $value ) {
			$content[] .= strtoupper( $name ) . '=' . urlencode( $value );
		}

		return implode( '&', $content );
	}

	function transmitRequestXML( $xml, $int_var, $settings, $metaUser, $new_subscription, $invoice )
	{
		$path = "/nvp";

		if ( $settings['testmode'] ) {
			$url = "https://api.sandbox.paypal.com" . $path;
		} else {
			if ( $settings['use_certificate'] ) {
				$url = "https://api-3t.sandbox.paypal.com" . $path;
			} else {
				$url = "https://api.paypal.com" . $path;
			}
		}

		$curlextra = array();
		$curlextra[CURLOPT_VERBOSE] = 1;

		$response = $this->transmitRequest( $url, $path, $xml, 443, $curlextra );

		$return['valid'] = false;
		$return['raw'] = $response;

		// converting NVPResponse to an Associative Array
		$nvpResArray = $this->deformatNVP( $response );
		//$nvpReqArray = $this->deformatNVP( $xml );

		if ( $response ) {
			$return['invoice'] = $invoice->invoice_number;
			$resultCode = strtoupper( $response["ACK"] );

			if ( strcmp( $resultCode, 'SUCCESS' ) === 0) {
				if ( is_array( $int_var['amount'] ) ) {
					$subscriptionId = $response['PROFILEID'];
					$return['invoiceparams'] = array( "subscriptionid" => $subscriptionId );

					if ( strtoupper( $response['STATUS'] ) == 'ACTIVEPROFILE' ) {
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
				$return['unit'] = 'years';
				$return['period'] = $period;
				break;
		}

		return $return;
	}

	function customaction_cancel( $pp, $cfg, $invoice, $metaUser )
	{
		$this->ManageRecurringPaymentsProfileStatus( $pp, $cfg, $invoice, $metaUser, 'Cancel', $cfg['cancel_note'] );
	}

	function ManageRecurringPaymentsProfileStatus( $pp, $cfg, $invoice, $metaUser, $command, $note )
	{
		$var['Method']				= 'ManageRecurringPaymentsProfileStatus';
		$var['Version']			= '3.0';
		$var['user']				= $cfg['api_user'];
		$var['pwd']					= $cfg['api_password'];
		$var['signature']			= $cfg['signature'];

		$invoiceparams = $invoice->getParams();

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

		if ( $cfg['testmode'] ) {
			$url = "https://api.sandbox.paypal.com" . $path;
		} else {
			if ( $cfg['use_certificate'] ) {
				$url = "https://api-3t.sandbox.paypal.com" . $path;
			} else {
				$url = "https://api.paypal.com" . $path;
			}
		}

		$curlextra = array();
		$curlextra[CURLOPT_VERBOSE] = 1;

		$response = $this->transmitRequest( $url, $path, $xml, 443, $curlextra );

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
			Payment_HTML::error( 'com_acctexp', $metaUser->cmsUser, $invoice, "An error occured while cancelling your subscription. Please contact the system administrator!", true );
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