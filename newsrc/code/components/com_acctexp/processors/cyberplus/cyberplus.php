<?php
/**
 * @version $Id: cyberplus.php
 * @package AEC - Account Control Expiration - Membership Manager
 * @subpackage Payment Processors
 * @author David Deutsch <skore@valanx.org> & Team AEC - http://www.valanx.org
 * @copyright 2006-2012 Copyright (C) David Deutsch
 * @license GNU/GPL v.3 http://www.gnu.org/licenses/gpl.html or, at your option, any later version
 */

// Dont allow direct linking
( defined('_JEXEC') || defined( '_VALID_MOS' ) ) or die( 'Direct Access to this location is not allowed.' );

class processor_cyberplus extends POSTprocessor
{
	function info()
	{
		$info = array();
		$info['name']			= 'cyberplus';
		$info['longname']		= JText::_('CFG_CYBERPLUS_LONGNAME');
		$info['statement']		= JText::_('CFG_CYBERPLUS_STATEMENT');
		$info['description']	= JText::_('CFG_CYBERPLUS_DESCRIPTION');
		$info['currencies']		= AECToolbox::aecCurrencyField( true, true, true, true );
		$info['languages']		= "DE,EN,ZH,ES,FR,IT,JP";
		$info['cc_list']		= "visa,mastercard,discover,americanexpress,echeck";
		$info['recurring']		= 2;

		return $info;
	}

	function settings()
	{
		$settings = array();
		$settings['testmode']			= 0;
		$settings['key']				= '0123456789ABCDEF0123456789ABCDEF01234567';
		$settings['currency']			= 'EUR';
		$settings['language']			= 'FR';
		$settings['server']				= 0;
		$settings['totalOccurrences']	= 12;
		$settings['item_name']			= sprintf( JText::_('CFG_PROCESSOR_ITEM_NAME_DEFAULT'), '[[cms_live_site]]', '[[user_name]]', '[[user_username]]' );
		$settings['customparams']		= "";

		$settings = AECToolbox::rewriteEngineInfo( null, $settings );

		return $settings;
	}


	function backend_settings()
	{
		$settings = array();
		$settings['testmode']			= array( 'toggle' );
		$settings['tpe']				= array( 'inputC' );
		$settings['pass']				= array( 'inputC' );
		$settings['key']				= array( 'inputC' );
		$settings['language']			= array( 'list_language' );
		$settings['totalOccurrences']	= array( 'inputA' );
		$settings['item_name']			= array( 'inputE' );
		$settings['customparams']		= array( 'inputD' );

		return $settings;
	}

	function createGatewayLink( $request )
	{
		$var['post_url']			= "https://paiement.systempay.fr/vads-payment/";

		$var['vads_site_id']		= $this->settings['siteid'];
		$var['vads_ctx_mode']		= 'PRODUCTION';
		$var['vads_version']		= 'V2';
		$var['vads_language']		= strtoupper( $this->settings['language'] );
		$var['vads_currency']		= AECToolbox::aecNumCurrency( $this->settings['currency'] );

		if ( is_array( $request->int_var['amount'] ) ) {
			$var['vads_amount']			= $request->int_var['amount']['amount3']*100;
		} else {
			$var['vads_amount']			= $request->int_var['amount']*100;
		}

		$var['vads_page_action']	= 'PAYMENT';
		$var['vads_action_mode']	= 'INTERACTIVE';
		$var['vads_validation_mode']= '0';
		$var['vads_payment_cards']	= '';

		if ( is_array( $request->int_var['amount'] ) ) {
			$vars = array();
			if ( isset( $request->int_var['amount']['amount1'] ) ) {
				$vars['first'] = $request->int_var['amount']['amount1']*100;
			}

			$vars['count'] = $this->settings['totalOccurrences'];
			$vars['period'] = $this->convertPeriodUnit( $request->int_var['amount']['unit3'], $request->int_var['amount']['period3'] );

			$varr = array();
			foreach ( $vars as $k => $v ) {
				$varr[] = $k.'='.$v;
			}

			$var['vads_payment_config']	= 'MULTI:'.implode(';', $varr);
		} else {
			$var['vads_payment_config']	= 'SINGLE';
		}

		$var['vads_capture_delay']	= '0';
		$var['vads_order_id']		= $request->invoice->invoice_number;
		$var['vads_cust_id']		= $request->metaUser->userid;
		$var['vads_cust_name']		= $request->metaUser->cmsUser->name;
		$var['vads_cust_email']		= $request->metaUser->cmsUser->email;
		$var['vads_cust_country']	= $this->settings['language'];
		$var['vads_return_mode']	= 'GET';
		$var['vads_redirect_success_timeout'] 	= '5';
		$var['vads_redirect_success_message'] 	= 'Redirection vers la boutique dans quelques instants';
		$var['vads_redirect_error_timeout'] 	= '5';
		$var['vads_redirect_error_message'] 	= 'Redirection vers la boutique dans quelques instants';

		$var['vads_trans_id']		= str_pad($request->invoice->id, 6, "0", STR_PAD_LEFT);
		$var['vads_trans_date']		= gmdate("YmdHis",time());
		$var['vads_url_return']		= AECToolbox::deadsureURL( "index.php?option=com_acctexp&amp;task=thanks" );
		$var['vads_url_refused']	= AECToolbox::deadsureURL( "index.php?option=com_acctexp&amp;task=cancel" );
		$var['vads_url_cancel']		= AECToolbox::deadsureURL( "index.php?option=com_acctexp&amp;task=cancel" );
		$var['vads_url_error']		= AECToolbox::deadsureURL( "index.php?option=com_acctexp&amp;task=cancel" );

		$var['signature'] = $this->getHash( $var );

		return $var;
	}

	function convertPeriodUnit( $unit, $period )
	{
		$return = array();
		switch ( $unit ) {
			case 'D':
				return $period;
				break;
			case 'W':
				return $period*7;
				break;
			case 'M':
				return $period*30;
				break;
			case 'Y':
				return $period*365;
				break;
		}

		return $return;
	}

	function parseNotification( $post )
	{
		$response = array();
		$response['invoice']			= $post['vads_order_id'];
		$response['amount_paid']		= $post['vads_amount']/100;
		$response['amount_currency']	= AECToolbox::aecNumCurrency( $post['vads_currency'], true );

		return $response;
	}

	function validateNotification( $response, $post, $invoice )
	{
		$response['valid'] = false;

		if ( empty( $post['signature'] ) ) {
			$response['error'] = true;
			$response['errormsg'] = 'signature missing';

			return $response;
		}

		if ( $post['signature'] !== $this->getHash( $post ) ) {
			$response['error'] = true;
			$response['errormsg'] = 'invalid signature';

			return $response;
		}

		switch( $post['vads_result'] ) {
			case '00':
				$response['valid']	= true;
				break;
			case '17':
				$response['cancel']	= 1;
				break;
			default:
				$response['error']	= true;
				if ( !empty( $post['vads_extra_result'] ) ) {
					$response['errormsg'] = $post['vads_result'].': '.$post['vads_extra_result'];
				} else {
					$response['errormsg'] = $post['vads_result'];
				}
				break;
		}

		return $response;
	}

	function getHash( $array )
	{
		ksort( $array );

		$glue = '+';

		foreach ( $array as $k => $v ) {
			if ( strpos( $k, 'vads_' ) === 0 ) {
				$check[] = $v;
			}
		}

		$check[] = $this->settings['tpe'];

		$string = implode( $glue, $check );

		return sha1( $string );
	}

}

?>
