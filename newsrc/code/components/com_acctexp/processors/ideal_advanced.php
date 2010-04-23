<?php
/**
 * @version $Id: ideal_advanced.php
 * @package AEC - Account Control Expiration - Membership Manager
 * @subpackage Processors - iDeal Advanced
 * @author David Deutsch <skore@skore.de> & Team AEC - http://www.valanx.org
 * @copyright 2006-2008 Copyright (C) David Deutsch
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
		$settings['testmodestage']  = 0;
		$settings['secure_path']    = 'components/com_acctexp/processors/ideal_advanced/includes/security';
		$settings['cache_path']     = 'cache';
		$settings['description']	= sprintf( _CFG_PROCESSOR_ITEM_NAME_DEFAULT, '[[cms_live_site]]', '[[user_name]]', '[[user_username]]' );
		return $settings;
	}

	function backend_settings()
	{
		$settings = array();
		$settings['testmode']		= array( 'list_yesno' );
		$settings['testmodestage']  = array( 'inputA') ;
		$settings['secure_path']    = array( 'inputD') ;
		$settings['cache_path']     = array( 'inputD') ;
		$settings['description']	= array( 'inputE' );

		$settings = AECToolbox::rewriteEngineInfo( null, $settings );

		return $settings;
	}

	function checkoutform( $request )
	{
		//  See if a the cached file is still of today
		if ( $this->settings['testmode'] == 0 ) {
			$cachefile = $this->settings['cache_path'].'/ideal_issuerlist.txt';
		} else {
			$cachefile = $this->settings['cache_path'].'/ideal_issuertestlist.txt';
		}

		$datetime = filemtime ($cachefile);
		$currentdatetime = time();

		if ( time() - $datetime > 86400 ) {
			$ideal = $this->loadConnector();

			$response = $ideal->GetIssuerList();

			if ( $response->IsResponseError() )  {
				aecQuickLog( "ideal_advanced", 'processor,error,issuerlist', $response->getErrorCode() . ': ' . $response->getErrorMessage() . ' | ' . $response->getConsumerMessage() );
			} else {
				$IssuerList =& $response->getIssuerFullList();

				$options = array();

				$handle = fopen($cachefile, "w");

				foreach ( $IssuerList as $issuerName => $entry ) {
					$data = '&issuer[]='.$entry->getIssuerID(). '#'. $entry->getIssuerName();
					fwrite ( $handle, $data );
				}

				fclose( $handle );
			}
		}

		$vcontent	= '';

		$handle		= fopen( $cachefile, "r" );
		$vcontent	= fread( $handle, 8192 );

		$issuer		= array();

		parse_str($vcontent);

		foreach ( $issuer as $issuer ) {
			$pos = strpos( $issuer, '#' );

			if ( $pos > 0 ) {
				$issuerId	= substr( $issuer, 0, $pos );
				$issuerName	= substr( $issuer, $pos+1, 20 );

				$options[]	= mosHTML::makeOption( $issuerId, $issuerName );
			}
		}

		$var['params']['lists']['issuerId'] = mosHTML::selectList( $options, 'issuerId', 'size="1" style="width:120px;"', 'value', 'text', $vcontent );
		$var['params']['issuerId'] = array( 'list', 'Selecteer je bank', $vcontent );

		return $var;
	}

	function createRequestXML( $request )
	{
		return true;
	}

	function transmitRequestXML( $xml, $request )
	{
		$return = array();

		$ideal = $this->loadConnector();

		$issuerId = $request->int_var['params']['issuerId'];

		if ( empty( $issuerId ) ) {
			$return['error'] = 'Missing ISSUERID';
			return $return;
		}

		// Create Variables
		$entranceCode	= $request->invoice->invoice_number;
		$purchaseId		= substr( $request->invoice->invoice_number,1,16 );

		if ( $this->settings['testmode'] == true && $this->settings['testmodestage'] > 0) {
			$amount	= $this->settings['testmodestage'] * 100;
		} else {
			$amount	= $request->int_var['amount'] * 100;
		}

		$description = substr(AECToolbox::rewriteEngineRQ( $this->settings['description'], $request ),0,32);
		$expirationPeriod = 'PT1H';
		$merchantReturnURL= AECToolbox::deadsureURL("index.php?option=com_acctexp&task=ideal_advancednotification") ;

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

	function loadConnector()
	{
		require_once( dirname(__FILE__) . "/ideal_advanced/iDEALConnector.php" );
		define( "SECURE_PATH", $this->settings['secure_path'] );

		// Initialising MPI
		$iDEAL = new iDEALConnector();

		return $iDEAL;
	}
}
?>
