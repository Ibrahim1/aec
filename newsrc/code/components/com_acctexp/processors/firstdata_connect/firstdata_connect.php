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
		$info['name']					= 'firstdata_connect';
		$info['longname']				= JText::_('CFG_FIRSTDATA_CONNECT_LONGNAME');

		$info['statement']				= JText::_('CFG_FIRSTDATA_CONNECT_STATEMENT');
		$info['description']			= JText::_('CFG_FIRSTDATA_CONNECT_DESCRIPTION');
		$info['currencies']				= 'USD';
		$info['languages']				= AECToolbox::getISO639_1_codes();
		$info['cc_list']				= 'visa,mastercard,discover,americanexpress,echeck,giropay';
		$info['recurring']				= 2;
		$info['actions']				= array( 'cancel' => array( 'confirm' ) );
		$info['notify_trail_thanks']	= 1;

		return $info;
	}

	function settings()
	{
		$settings = array();
		$settings['testmode']		= 0;
		$settings['version2']		= 1;

		$settings['storename']		= '';
		$settings['secret']			= '';

		$settings['currency']		= 'USD';
		$settings['customparams']	= "bname=\nbaddr1=\nbaddr2=\nbcity=\nbstate=\nbzip=\nbemail=\n";

		return $settings;
	}

	function backend_settings()
	{
		$settings = array();
		$settings['testmode']		= array( 'toggle' );
		$settings['version2']		= array( 'toggle' );

		$settings['storename']		= array( 'inputC' );
		$settings['secret']			= array( 'inputC' );

		$settings['currency']		= array( 'list_currency' );
		$settings['customparams']	= array( 'inputD' );

		$settings = AECToolbox::rewriteEngineInfo( null, $settings );

		return $settings;
	}

	function createGatewayLink( $request )
	{
		$v2 = $this->settings['version2'];

		if ( $v2 ) {
			if ( $this->settings['testmode'] ) {
				$var['post_url']	= 'https://www.staging.linkpointcentral.com/lpc/servlet/lppay';
			} else {
				$var['post_url']	= 'https://www.linkpointcentral.com/lpc/servlet/lppay';
			}
		} else {
			if ( $this->settings['testmode'] ) {
				$var['post_url']	= 'https://connect.merchanttest.firstdataglobalgateway.com/IPGConnect/gateway/processing';
			} else {
				$var['post_url']	= 'https://connect.firstdataglobalgateway.com/IPGConnect/gateway/processing';
			}
		}

		if ( is_array( $request->int_var['amount'] ) ) {
			$var['chargetotal']		= $request->int_var['amount']['amount3'];
		} else {
			$var['chargetotal']		= $request->int_var['amount'];
		}

		$var['txntype']				= 'sale';

		if ( $v2 ) {
			$var['timezone']		= 'sale';
			$var['txndatetime']		= date( "Y:m:d-H:i:s", strtotime( $request->invoice->created_date ) );

			$var['hash']			= $this->sendHash( $var['chargetotal'], $var['txndatetime'] );
		}

		$var['storename']			= $this->settings['storename'];

		$var['oid']					= $request->invoice->id;

		if ( isset( $request->items->tax ) ) {
			$tax = 0;

			foreach ( $request->items->tax as $itax ) {
				$tax += $itax['cost'];
			}

			$var['tax']			= AECToolbox::correctAmount( $tax );
		}

		if ( is_array( $request->int_var['amount'] ) ) {
			$var[$v2?'subtotal':'subchargetotal']	= $request->int_var['amount']['amount3'];
		} else {
			$var[$v2?'subtotal':'subchargetotal']	= $request->int_var['amount'];
		}

		$var['responseFailURL']		= AECToolbox::deadsureURL( 'index.php?option=com_acctexp&amp;task=cancel' );
		$var['responseSuccessURL']	= AECToolbox::deadsureURL( 'index.php?option=com_acctexp&amp;task=firstdata_connectnotification' );

		$var[$v2?'invoicenumber':'invoice_number']		= $request->invoice->invoice_number;

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
		$return = array();
		if ( !empty( $post['oid'] ) ) {
			$return['invoice'] = aecInvoiceHelper::InvoiceIDfromNumber( $post['oid'] );
		} elseif ( !empty( $post['invoicenumber'] ) ) {
			$return['invoice'] = $post['invoicenumber'];
		} elseif ( !empty( $post['invoice_number'] ) ) {
			$return['invoice'] = $post['invoice_number'];
		}

		return $return;
	}

	function validateNotification( $response, $post, $invoice )
	{
		$return = array();
		$return['valid'] = false;

		if ( $this->settings['version2'] ) {
			$hash = $this->receiveHash( $invoice->amount,
										date( "Y:m:d-H:i:s", strtotime( $invoice->created_date ) ),
										$post['approval_code']
									);

			if ( $this->settings['response_hash'] != $hash ) {
				$return['error'] = true;
				$return['errormsh'] = 'Hash Mismatch';
			} else {
				$return['valid'] = strtolower($post['status']) == 'approved';
			}
		} else {
			// Version < 2.0 has no validation
			$return['valid'] = strtolower($post['status']) == 'approved';
		}

		if ( !$return['valid'] && !empty( $post['r-error'] ) ) {
			$return['error'] = true;
			$return['errormsh'] = $post['r-error'];
		}

		return $response;
	}

	function sendHash( $amount, $datetime )
	{
		return $this->hash( $this->settings['storename'] . $datetime . $amount . $this->settings['secret'] );
	}

	function receiveHash( $amount, $datetime, $approval )
	{
		// TODO: This is possibly missing an occurance of storename at the end, documentation has typos
		return $this->hash( $this->settings['secret'] . $approval . $amount . $this->settings['currency'] . $datetime );
	}

	function hash( $string )
	{
		$hex_str = '';
		for ( $i=0; $i<strlen($string); $i++ ) {
			$hex_str .= dechex( ord($string[$i]) );
		}

		return hash( 'sha256', $hex_str );
	}
}

?>
