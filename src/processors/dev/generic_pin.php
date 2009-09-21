<?php
/**
 * @version $Id: generic_pin.php
 * @package AEC - Account Control Expiration - Membership Manager
 * @subpackage Processors - Generic PIN
 * @copyright 2006-2009 Copyright (C) David Deutsch
 * @author David Deutsch <skore@skore.de> & Team AEC - http://www.valanx.org
 * @license GNU/GPL v.2 http://www.gnu.org/licenses/old-licenses/gpl-2.0.html or, at your option, any later version
 */

// Dont allow direct linking
( defined('_JEXEC') || defined( '_VALID_MOS' ) ) or die( 'Direct Access to this location is not allowed.' );

class processor_generic_pin extends XMLprocessor
{
	function info()
	{
		$info = array();
		$info['name']			= 'generic_pin';
		$info['longname']		= _CFG_GENERIC_PIN_LONGNAME;
		$info['statement']		= _CFG_GENERIC_PIN_STATEMENT;
		$info['description']	= _CFG_GENERIC_PIN_DESCRIPTION;
		$info['currencies']		= AECToolbox::aecCurrencyField( true, true, true, true );
		$info['cc_list']		= "";
		$info['recurring']		= 0;
		$info['actions']		= array('email');

		return $info;
	}

	function settings()
	{
		$settings = array();
		$settings['currency']			= '';
		$settings['pin_list_file']		= '';
		$settings['tracking_type']		= '';
		$settings['currency']			= array( 'list_currency' );
		$settings['pin_filepath']		= '';
		$settings['dbms']				= '';
		$settings['dbhost']				= '';
		$settings['dbuser']				= '';
		$settings['dbpasswd']			= '';
		$settings['dbname']				= '';

		return $settings;
	}

	function backend_settings()
	{
		$settings = array();
		$settings['currency']			= array( 'list_currency' );
		$settings['pin_filepath']		= array( 'inputC' );
		$settings['dbms']				= array( 'inputC' );
		$settings['dbhost']				= array( 'inputC' );
		$settings['dbuser']				= array( 'inputC' );
		$settings['dbpasswd']			= array( 'inputC' );
		$settings['dbname']				= array( 'inputC' );

 		$rewriteswitches				= array( 'cms', 'user', 'expiration', 'subscription', 'plan', 'invoice' );
		$settings						= AECToolbox::rewriteEngineInfo( $rewriteswitches, $settings );

		return $settings;
	}

	function checkoutform( $request )
	{
		$var = array();
		$var['params']['pin_code'] = array( 'inputC', _AEC_GENERIC_PIN_PARAMS_PIN_CODE_NAME, _AEC_GENERIC_PIN_PARAMS_PIN_CODE_DESC);

		return $var;
	}


	function createRequestXML( $request )
	{
		return "";
	}

	function transmitRequestXML( $content, $request )
	{
		$return['valid']	= false;
		$return['raw']		= "AEC Generic PIN Processor Payment";
		$return['error']	= "Please provide a valid pin_code";

		if ( !empty( $request->int_var['params']['pin_code'] ) ) {
			if ( $this->usePIN( $request->int_var['params']['pin_code'] ) ) {

			}
		}

		return $return;
	}

	function usePIN( $pin )
	{
		// Look up PIN in DB
		// If fails -> return false;
		// If succeeds
		// take note in DB
		// return true;
	}
}

?>
