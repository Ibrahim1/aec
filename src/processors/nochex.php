<?php
/**
 * @version $Id: nochex.php
 * @package AEC - Account Control Expiration - Membership Manager
 * @subpackage Processors - Nochex
 * @copyright 2006-2008 Copyright (C) David Deutsch
 * @author David Deutsch <skore@skore.de> & Team AEC - http://www.globalnerd.org
 * @license GNU/GPL v.2 http://www.gnu.org/licenses/old-licenses/gpl-2.0.html or, at your option, any later version
 */

// Dont allow direct linking
( defined('_JEXEC') || defined( '_VALID_MOS' ) ) or die( 'Direct Access to this location is not allowed.' );

class processor_nochex extends POSTprocessor
{
	function info()
	{
		$info = array();
		$info['name']			= 'nochex';
		$info['longname']		= _CFG_NOCHEX_LONGNAME;
		$info['statement']		= _CFG_NOCHEX_STATEMENT;
		$info['description']	= _CFG_NOCHEX_DESCRIPTION;
		$info['cc_list']		= 'visa,mastercard';
		$info['recurring']		= 0;

		return $info;
	}

	function settings()
	{
		$settings = array();
		$settings['testmode'] 		= 1;
		$settings['merchant_id']	= 'nochex@aec.com';
		$settings['item_name']		= sprintf( _CFG_PROCESSOR_ITEM_NAME_DEFAULT, '[[cms_live_site]]', '[[user_name]]', '[[user_username]]' );
		$settings['customparams']		= "";

		return $settings;
	}

	function backend_settings()
	{
		$settings = array();
		$settings['testmode']		= array( 'list_yesno');
		$settings['merchant_id']	= array( 'inputC');
		$settings['item_name']		= array( 'inputE');
		$settings['customparams']	= array( 'inputD' );

		$settings = AECToolbox::rewriteEngineInfo( null, $settings );

		return $settings;
	}

	function createGatewayLink( $request )
	{
		global $mosConfig_live_site;

		$var['post_url']	= 'https://secure.nochex.com/';
		if ( $this->settings['testmode'] == '1' ) {
			$var['test_transaction'] = '100';
			$var['test_success_url'] = AECToolbox::deadsureURL( 'index?option=com_acctexp&amp;task=nochexnotification' );
		}

		$var['merchant_id']			= $this->settings['merchant_id'];
		$var['description']			= AECToolbox::rewriteEngine( $this->settings['item_name'], $request->metaUser, $request->new_subscription, $request->invoice );
		$var['order_id']			= $request->int_var['invoice'];
		$var['amount']				= $request->int_var['amount'];
		$var['success_url']			= $request->int_var['return_url'];
		$var['cancel_url']			= AECToolbox::deadsureURL( 'index?option=com_acctexp&amp;task=cancel' );
		$var['declined_url']		= AECToolbox::deadsureURL( 'index?option=com_acctexp&amp;task=cancel' );
		$var['callback_url']		= AECToolbox::deadsureURL( 'index?option=com_acctexp&amp;task=nochexnotification' );
		$var['billing_fullname']	= $request->metaUser->cmsUser->name;
		$var['email_address']		= $request->metaUser->cmsUser->email;

		return $var;
	}

	function parseNotification( $post )
	{
		$response = array();
		$response['invoice'] = $post['order_id'];

		return $response;
	}

	function validateNotification( $response, $post, $invoice )
	{
		// TODO: There needs to be some way to verify this!!!
		$response['valid'] = 0;
		if ( $response['invoice'] == $post['order_id'] ) {
			$response['valid'] = 1;
		}

		return $response;
	}

}

?>
