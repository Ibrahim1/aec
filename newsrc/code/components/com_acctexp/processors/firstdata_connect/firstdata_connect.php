<?php
/**
 * @version $Id: firstdata_connect.php
 * @package AEC - Account Control Expiration - Membership Manager
 * @subpackage Processors - FirstData Connect
 * @copyright 2013 Copyright (C) David Deutsch
 * @author David Deutsch <skore@valanx.org> & Team AEC - http://www.valanx.org
 * @license GNU/GPL v.3 http://www.gnu.org/licenses/gpl.html or, at your option, any later version
 */

// Dont allow direct linking
defined('_JEXEC') or die( 'Direct Access to this location is not allowed.' );

class processor_firstdata_connect extends POSTprocessor
{
	function info()
	{
		$info = array();
		$info['name']			= 'firstdata_connect';
		$info['longname'] 		= JText::_('CFG_FIRSTDATA_CONNECT_LONGNAME');

		$info['statement'] 		= JText::_('CFG_FIRSTDATA_CONNECT_STATEMENT');
		$info['description'] 	= JText::_('CFG_FIRSTDATA_CONNECT_DESCRIPTION');
		$info['currencies'] 	= 'EUR,USD,AUD,CAD,GBP,JPY,NZD,CHF,HKD,SGD,SEK,DKK,PLN,NOK,HUF,CZK,MXN,ILS,BRL,MYR,PHP,TWD,THB,ZAR';
		$info['languages'] 		= AECToolbox::getISO639_1_codes();
		$info['cc_list']		= 'visa,mastercard,discover,americanexpress,echeck,giropay';
		$info['recurring']		= 1;
		$info['actions']		= array( 'cancel' => array( 'confirm' ) );
		$info['notify_trail_thanks']	= 1;

		return $info;
	}

	function settings()
	{
		$settings = array();
		$settings['testmode']		= 0;
		$settings['password']		='';
		$settings['currency']		= 'USD';
		$settings['item_name']		= sprintf( JText::_('CFG_PROCESSOR_ITEM_NAME_DEFAULT'), '[[cms_live_site]]', '[[user_name]]', '[[user_username]]' );
		$settings['item_number']	= '[[user_id]]';

		return $settings;
	}

	function backend_settings()
	{
		$settings = array();
		$settings['password']				= array( 'inputC' );
		$settings['testmode']				= array( 'toggle' );

		$settings['currency']				= array( 'list_currency' );
		$settings['item_name']				= array( 'inputE' );
		$settings['item_number']			= array( 'inputE' );

		$settings = AECToolbox::rewriteEngineInfo( null, $settings );

		return $settings;
	}

	function createGatewayLink( $request )
	{
		if ( $this->settings['testmode'] ) {
			$var['post_url']	= 'https://www.staging.linkpointcentral.com/lpc/servlet/lppay';
		} else {
			$var['post_url']	= 'https://www.linkpointcentral.com/lpc/servlet/lppay';
		}

		$var['subtotal']			= $request->int_var['amount']['amount3'];
		$var['chargetotal']			= $request->int_var['amount']['amount3'];

		$var['storename']			= $this->settings['password'];
		$var['invoice']				= $request->invoice->invoice_number;
		$var['responseFailURL']		= AECToolbox::deadsureURL( 'index.php?option=com_acctexp&amp;task=cancel' );
    	$var['responseSuccessURL']	=AECToolbox::deadsureURL( 'index.php?option=com_acctexp&amp;task=firstdata_connectnotification' );
		$var['oid']					= AECToolbox::rewriteEngineRQ( $this->settings['item_number'], $request );
		$var['item_name']			= AECToolbox::rewriteEngineRQ( $this->settings['item_name'], $request );
		$var['return_url']			= $request->int_var['return_url'];
		$var['currency_code']		= $this->settings['currency'];

		return $var;
	}

	function parseNotification( $post )
	{
		if ( !empty( $post['invoice'] ) ) {
			$return['invoice'] = $post['invoice'];
			$return['secondary_ident'] = $post['approval_code'];
		}

		return $return;

	}

	function validateNotification( $response, $post, $invoice )
	{

		$return['valid'] = true;
		return $response;
	}

}
?>