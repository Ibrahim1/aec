<?php
/**
 * @version $Id: ideal_basic.php
 * @package AEC - Account Control Expiration - Membership Manager
 * @subpackage Processors - iDeal Basic
 * @author David Deutsch <skore@skore.de> & Team AEC - http://www.valanx.org
 * @copyright 2006-2008 Copyright (C) David Deutsch
 * @license GNU/GPL v.2 http://www.gnu.org/licenses/old-licenses/gpl-2.0.html or, at your option, any later version
 */

// Dont allow direct linking
( defined('_JEXEC') || defined( '_VALID_MOS' ) ) or die( 'Direct Access to this location is not allowed.' );

class processor_ideal_basic extends POSTprocessor
{
	function info()
	{
		$i = array();
		$i['name']					= 'ideal_basic';
		$i['longname']				= _CFG_IDEAL_BASIC_LONGNAME;
		$i['statement']				= _CFG_IDEAL_BASIC_STATEMENT;
		$i['description']			= _CFG_IDEAL_BASIC_DESCRIPTION;
		$i['currencies']			= 'EUR';
		$i['languages']				= 'NL';
		$i['cc_list']				= 'rabobank,ing';
		$i['recurring']				= 0;
		$i['notify_trail_thanks']	= 1;

		return $i;
	}

	function settings()
	{
		$s = array();
		$s['merchantid']	= "merchantid";
		$s['testmode']		= 0;
		$s['testmodestage']	= 1;
		$s['bank']			= "ing";
		$s['subid']			= "0";
		$s['language']		= "NL";
		$s['key']			= "key";
		$s['description']	= sprintf( _CFG_PROCESSOR_ITEM_NAME_DEFAULT, '[[cms_live_site]]', '[[user_name]]', '[[user_username]]' );
		$s['customparams']	= "";

		return $s;
	}

	function backend_settings()
	{
		$s = array();
		$s['aec_experimental']	= array( "p" );
		$s['aec_insecure']		= array( "p" );
		$s['merchantid']		= array( 'inputC' );
		$s['testmode']			= array( 'list_yesno' );
		$s['testmodestage']		= array( 'inputC' );
		$s['bank']				= array( 'list' );
		$s['subid']				= array( 'inputC' );
		$s['language']			= array( 'list_language' );
		$s['key']				= array( 'inputC' );
		$s['description']		= array( 'inputE' );
		$s['customparams']		= array( 'inputD' );

 		$banks = array();
		$banks[] = mosHTML::makeOption ( "ing", "ING" );
		$banks[] = mosHTML::makeOption ( "rabo", "Rabobank" );

		if ( !empty( $this->settings['bank'] ) ) {
			$ba = $this->settings['bank'];
		} else {
			$ba = "ing";
		}

		$s['lists']['bank']	= mosHTML::selectList( $banks, 'bank', 'size="2"', 'value', 'text', $ba );

		return $s;
	}

	function createGatewayLink( $request )
	{
		if ( $this->settings['testmode'] ) {
			$sub = 'idealtest';
		} else {
			$sub = 'ideal';
		}

		if ( $this->settings['bank'] == 'ing' ) {
			$var['post_url']		= "https://" . $sub . ".secure-ing.com/ideal/mpiPayInitIng.do";
		} else {
			$var['post_url']		= "https://" . $sub . ".rabobank.nl/ideal/mpiPayInitRabo.do";
		}

		$var['merchantID']			= $this->settings['merchantid'];
		$var['subID']				= $this->settings['subid'];
		$var['purchaseID']			= substr( $request->invoice->invoice_number, 1 );

		if ( $this->settings['testmode'] ) {
			$var['amount']			= max( 1, min( 7, (int) $this->settings['testmodestage'] ) ) . '.00';
		} else {
			$var['amount']			= $request->int_var['amount'];
		}


		$var['currency']			= $this->settings['currency'];
		$var['language']			= strtolower( $this->settings['language'] );
		$var['description']			= substr( $this->settings['description'], 0, 32);
		$var['itemNumber1']			= $request->metaUser->userid;
		$var['itemDescription1']	= substr( $this->settings['description'], 0, 32);
		$var['itemQuantity1']		= 1;
		$var['itemPrice1']			= $request->int_var['amount'];
		$var['paymentType']			= 'ideal';
		$var['validUntil']			= date('Y-m-d\TG:i:s\Z', strtotime('+1 hour'));

		$shastring = $this->settings['key']
					.$var['merchantID']
					.$var['subID']
					.$var['amount']
					.$var['purchaseID']
					.$var['paymentType']
					.$var['validUntil']
					.$var['itemNumber1']
					.$var['itemDescription1']
					.$var['itemQuantity1']
					.$var['itemPrice1'];

		$shastring = html_entity_decode( $shastring );

		$shastring = str_replace( array("\t", "\n", "\r", " "), '', $shastring );

		$var['hash']				= sha1( $shastring );
		$var['urlSuccess']			= AECToolbox::deadsureURL( 'index.php?option=com_acctexp&amp;task=ideal_basicnotification' );
		$var['urlCancel']			= AECToolbox::deadsureURL( 'index.php?option=com_acctexp&amp;task=cancel' );
		$var['urlError']			= AECToolbox::deadsureURL( 'index.php?option=com_acctexp&amp;task=cancel' );
		$var['urlService']			= AECToolbox::deadsureURL( 'index.php' );

		return $var;
	}

	function parseNotification( $post )
	{
		$response = array();
		$response['invoice'] = 'I'.$_GET['ideal']['order'];

		return $response;
	}

	function validateNotification( $response, $post, $invoice )
	{
		$response['valid'] = 0;
		if ( !isset( $_GET['ideal']['status'] ) ) {
			return $response;
		}

		switch ( strtolower( $_GET['ideal']['status'] ) ) {
			case 'success':
				$response['valid'] = 1;
				break;
			case 'error':
				$response['error'] = $_GET['ideal'];
				break;
		}

		return $response;
	}

}

?>
