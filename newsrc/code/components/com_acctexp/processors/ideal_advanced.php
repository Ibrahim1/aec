<?php
/**
 * @version $Id: ideal_advanced.php
 * @package AEC - Account Control Expiration - Membership Manager
 * @subpackage Processors - iDeal Advanced
 * @author David Deutsch <skore@skore.de> & Team AEC - http://www.valanx.org
 * @copyright 2006-2010 Copyright (C) David Deutsch
 * @license GNU/GPL v.2 http://www.gnu.org/licenses/old-licenses/gpl-2.0.html or, at your option, any later version
 */

// Dont allow direct linking
( defined('_JEXEC') || defined( '_VALID_MOS' ) ) or die( 'Direct Access to this location is not allowed.' );

class processor_ideal_advanced extends XMLprocessor
{
	function info()
	{
		$info = array();
		$info['name']			= 'ideal_advanced';
		$info['longname']		= _CFG_IDEAL_ADVANCED_LONGNAME;
		$info['statement']		= _CFG_IDEAL_ADVANCED_STATEMENT;
		$info['description']	= _CFG_IDEAL_ADVANCED_DESCRIPTION;
		$info['currencies']	    = 'EUR';
		$info['languages']		= 'NL';
		$info['recurring']	    = 0;
		return $info;
	}

	function settings()
	{
		$settings = array();
		$settings['testmode']		= 0;
		$settings['currency']		= "EUR";
		$settings['testmodestage']  = 0;
		$settings['secure_path']    = '/full/system/path/to/components/com_acctexp/processors/includes/security';
		$settings['description']	= sprintf( _CFG_PROCESSOR_ITEM_NAME_DEFAULT, '[[cms_live_site]]', '[[user_name]]', '[[user_username]]' );
		return $settings;
	}

	function backend_settings()
	{
		$settings = array();
		$settings['testmode']		= array( 'list_yesno' );
		$settings['currency']		= array( 'list_currency' );
		$settings['testmodestage']  = array( 'inputA') ;
		$settings['secure_path']    = array( 'inputD') ;
		$settings['description']	= array( 'inputE' );

		$settings = AECToolbox::rewriteEngineInfo( null, $settings );

		return $settings;
	}

	function checkoutform( $request )
	{
		$issuerlist = $this->getIssuerList();

		require_once( dirname(__FILE__) . "/ideal_advanced/IssuerEntry.php" );

		foreach ( $issuerlist as $IssuerEntry ) {
			if ( is_a( $IssuerEntry, 'IssuerEntry' ) ) {
				$options[]	= mosHTML::makeOption( $IssuerEntry->getIssuerID(), $IssuerEntry->getIssuerName() );
			}
		}

		$var['params']['lists']['issuerId'] = mosHTML::selectList( $options, 'issuerId', 'size="1"', 'value', 'text', null );
		$var['params']['issuerId'] = array( 'list', 'Selecteer je bank', null );

		return $var;
	}

	function createRequestXML( $request )
	{
		return true;
	}

	function transmitRequestXML( $xml, $request )
	{
		global $aecprocessor;

		$return = array();

		$ideal = $this->loadConnector();

		$issuerId = $request->int_var['params']['issuerId'];

		if ( empty( $issuerId ) ) {
			$return['error'] = 'Selecteer je bank!';
			return $return;
		}

		// Create Variables
		$entranceCode	= $request->invoice->invoice_number;

		// Use the unique part of the Invoice Number as purchase ID
		$purchaseId		= substr( $request->invoice->invoice_number,1,16 );

		if ( $this->settings['testmode'] == true && $this->settings['testmodestage'] > 0) {
			$amount	= $this->settings['testmodestage'] * 100;
		} else {
			$amount	= $request->int_var['amount'] * 100;
		}

		$description = substr(AECToolbox::rewriteEngineRQ( $this->settings['description'], $request ),0,32);

		$expirationPeriod = 'PT1H';

		$merchantReturnURL = AECToolbox::deadsureURL("index.php?option=com_acctexp&task=ideal_advancednotification") ;

		$aecprocessor = $this;

		// Opsturen van de request. De response staat in $response.
		$iDEALresponse = $ideal->RequestTransaction( $issuerId, $purchaseId, $amount, $description, $entranceCode, $expirationPeriod, $merchantReturnURL );

		if ( $iDEALresponse->IsResponseError() ) {
			$return['error'] = $iDEALresponse->getConsumerMessage();
		} else {
			aecRedirect( $iDEALresponse->getIssuerAuthenticationURL() );
		}

		return $return;
	}

	function parseNotification( $post )
	{
		$response = array();
		$response['valid'] = false;

		$entrancecode = trim( aecGetParam( 'ec' ) );
		$transactionID = trim( aecGetParam( 'trxid' ) );

		$response['invoice'] = $entrancecode;

		$ideal = $this->loadConnector();

		$iDEALresponse = $ideal->RequestTransactionStatus( $transactionID );

		if ( $iDEALresponse->IsResponseError() ) {
			$response['error']		= true;
			$response['errormsg']	= $iDEALresponse->getConsumerMessage();
		} else {
			$transactionID = $iDEALresponse->getTransactionID();

			switch ( $iDEALresponse->getStatus() ) {
				case IDEAL_TX_STATUS_SUCCESS:
					$response['valid'] = true;
					break;
				case IDEAL_TX_STATUS_INVALID:
				case IDEAL_TX_STATUS_CANCELLED:
				case IDEAL_TX_STATUS_EXPIRED:
				case IDEAL_TX_STATUS_FAILURE:
				case IDEAL_TX_STATUS_OPEN:
				default:
					$response['valid'] = false;
					$redirect = AECToolbox::deadsureURL("index.php?option=com_acctexp&task=cancel");
					aecRedirect ($redirect);
			}
		}

		return $response;
	}

	function notificationError( $response, $notificationerror )
	{
		$redirect = AECToolbox::deadsureURL("index.php?option=com_acctexp&task=cancel");
		aecRedirect($redirect);
	}

	function getIssuerList()
	{
		if ( $this->settings['testmode'] == 0 ) {
			$type = 'issuerlist_test';
		} else {
			$type = 'issuerlist';
		}

		$getissuerlist = false;
		if ( !isset( $this->params[$type.'_tstamp'] ) ) {
			$getissuerlist = true;
		} else {
			if ( time() - $this->params[$type.'_tstamp'] > 86400 ) {
				$getissuerlist = true;
			}
		}

		if ( $getissuerlist ) {
			$ideal = $this->loadConnector();

			$response = $ideal->GetIssuerList();

			if ( empty( $response->issuerList ) )  {
				aecQuickLog( "ideal_advanced", 'processor,error,issuerlist', $response->getErrorCode() . ': ' . $response->getErrorMessage() . ' | ' . $response->getConsumerMessage() );
			} else {
				// Store the Issuer List into the params
				$this->params[$type] =& $response->getIssuerList();

				// Take note of the time
				$this->params[$type.'_tstamp'] = time();

				$this->storeload();
			}
		}

		return $this->params[$type];
	}

	function loadConnector()
	{
		define( "SECURE_PATH", $this->settings['secure_path'] );

		require_once( dirname(__FILE__) . "/ideal_advanced/iDEALConnector.php" );
		require_once( dirname(__FILE__) . "/ideal_advanced/IssuerEntry.php" );

		// Initialising MPI
		$ideal = new iDEALConnector();

		return $ideal;
	}
}
?>
