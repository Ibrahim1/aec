<?php
/**
 * @version $Id: ogone.php
 * @package AEC - Account Control Expiration - Membership Manager
 * @subpackage Processors - Ogone
 * @author Vijay Jawalapersad
 * @license GNU/GPL v.2 http://www.gnu.org/licenses/old-licenses/gpl-2.0.html or, at your option, any later version
 */

// Dont allow direct linking
( defined('_JEXEC') || defined( '_VALID_MOS' ) ) or die( 'Direct Access to this location is not allowed.' );

class processor_ogone extends POSTprocessor
{
	function info()
	{
		$info = array();
		$info['name']					= 'Ogone';
		$info['description']			= 'Ogone';
		$info['currencies']				= AECToolbox::aecCurrencyField( true, true, true, true );
		$info['cc_list']				= 'americanexpress';
		$info['recurring']				= 0;
		$info['notify_trail_thanks']	= 1;

		return $info;
	}

	function settings()
	{
		$settings = array();
		$settings['testmode']		= '0';
		$settings['psid']			= 'PS ID';
		$settings['secret']			= 'PS Secret';
		$settings['currency']		= 'EUR';
		$settings['customparams']	= "";

		return $settings;
	}

	function backend_settings()
	{
		$settings = array();
		$settings['testmode']		= array( 'toggle' );
		$settings['psid']			= array( 'inputC' );
		$settings['secret']			= array( 'inputC' );
		$settings['currency']		= array( 'list_currency' );
		$settings['customparams']	= array( 'inputD' );
		
		return $settings;
	}

	function createGatewayLink( $request )
	{
		$var = array();
		$var['orderID']		= $request->invoice->invoice_number;
		$var['amount']		= (int) $request->int_var['amount']*100;
		$var['currency']	= $this->settings['currency'];
		$var['psid']		= $this->settings['psid'];

		$var['SHASign']		= $this->getHash($var);

		$var['accepturl']	= AECToolbox::deadsureURL( 'index.php?option=com_acctexp&task=ogoneeurnotification', false, true );
		$var['language']	= 'en_US';
		$var['PMLIST']		= 'iDEAL;American Express';

		if ( $this->settings['testmode'] ) {
			$var['post_url']	= 'https://secure.ogone.com/ncol/test/orderstandard.asp';
		} else {
			$var['post_url']	= 'https://secure.ogone.com/ncol/prod/orderstandard.asp';
		}

		return $var;
	}

	function parseNotification( $post )	
	{	
		$response = array();
		$response['invoice']			= $_GET['orderID'];
		$response['amount_paid']		= (float) $_GET['amount']/100;
		$response['amount_currency']	= $_GET['currency'];

		return $response;
	}

	function validateNotification( $response, $post, $invoice )
	{
		$response['valid'] = 0;
		
		$vars = array( 'ORDERID', 'CURRENCY', 'AMOUNT', 'PM', 'ACCEPTANCE', 'STATUS', 'CARDNO', 'ALIAS', 'PAYID', 'NCERROR', 'BRAND' );
		
		$source = array();
		foreach ( $vars as $v ) {
			$source[$v] = $_GET[$v];
		}
		
		if ( (($_GET['STATUS'] == 5)||($_GET['STATUS'] == 9)) && ($this->getHash($source) == $_GET['SHASIGN']) ) {
			$response['valid'] = 1;
		} else {
			$response['valid'] = 0;
		}
		return $response;
	}

	function getHash( $source )
	{
		$return = '';
		foreach ( $source as $k => $v ) {
			$return .= $v;
		}

		return sha1( $return.$this->settings['secure'] );
	}
}

