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
		$info['currencies'] 	= 'USD';
		$info['languages'] 		= AECToolbox::getISO639_1_codes();
		$info['cc_list']		= 'visa,mastercard,discover,americanexpress,echeck,giropay';
		$info['recurring']		= 2;
		$info['actions']		= array( 'cancel' => array( 'confirm' ) );
		$info['notify_trail_thanks']	= 1;

		return $info;
	}

	function settings()
	{
		$settings = array();
		$settings['testmode']		= 0;
		$settings['password']		= '';
		$settings['currency']		= 'USD';
		$settings['item_number']	= '[[invoice_id]]';
		$settings['customparams']	= "bname=\nbaddr1=\nbaddr2=\nbcity=\nbstate=\nbzip=\nbemail=\n";

		return $settings;
	}

	function backend_settings()
	{
		$settings = array();
		$settings['password']				= array( 'inputC' );
		$settings['testmode']				= array( 'toggle' );

		$settings['currency']				= array( 'list_currency' );
		$settings['item_number']			= array( 'inputE' );
		$settings['customparams']			= array( 'inputD' );

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

		if ( is_array( $request->int_var['amount'] ) ) {
			$var['chargetotal']		= $request->int_var['amount']['amount3'];
		} else {
			$var['chargetotal']		= $request->int_var['amount'];
		}

		$var['storename']			= $this->settings['password'];
		$var['txntype']				= 'sale';

		$var['oid']					= AECToolbox::rewriteEngineRQ( $this->settings['item_number'], $request );

		if ( isset( $request->items->tax ) ) {
			$tax = 0;

			foreach ( $request->items->tax as $itax ) {
				$tax += $itax['cost'];
			}

			$var['tax']			= AECToolbox::correctAmount( $tax );
		}

		if ( is_array( $request->int_var['amount'] ) ) {
			$var['subchargetotal']	= $request->int_var['amount']['amount3'];
		} else {
			$var['subchargetotal']	= $request->int_var['amount'];
		}

		$var['responseURL']			= $request->int_var['return_url'];
		$var['responseFailURL']		= AECToolbox::deadsureURL( 'index.php?option=com_acctexp&amp;task=cancel' );
		$var['responseSuccessURL']	= AECToolbox::deadsureURL( 'index.php?option=com_acctexp&amp;task=firstdata_connectnotification' );

		$var['invoice_number']		= $request->invoice->invoice_number;

		$custom = $this->customParams( $this->settings['customparams'], array(), $request );

		foreach ( $custom as $k => $v ) {
			if ( !empty( $v ) ) {
				$var[$k] = $v;
			}
		}

		if ( is_array( $request->int_var['amount'] ) ) {
			$var['submode']			= 'periodic';
			$var['periodicity']		= strtolower($request->int_var['amount']['unit3']).$request->int_var['amount']['period3'];
			$var['startdate']		= date( 'Ymd' );
			$var['installments']	= '99';
		}

		return $var;
	}

	function parseNotification( $post )
	{
		if ( !empty( $post['invoice'] ) ) {
			$return['invoice'] = $post['invoice_number'];
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