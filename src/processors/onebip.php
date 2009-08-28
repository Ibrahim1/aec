<?php
/**
 * @version $Id: onebip.php
 * @package AEC - Account Control Expiration - Membership Manager
 * @subpackage Processors - OneBip Buy Now
 * @copyright 2007-2008 Copyright (C) David Deutsch
 * @author David Deutsch <skore@skore.de> & Team AEC - http://www.valanx.org
 * @license GNU/GPL v.2 http://www.gnu.org/licenses/old-licenses/gpl-2.0.html or, at your option, any later version
 */

// Dont allow direct linking
( defined('_JEXEC') || defined( '_VALID_MOS' ) ) or die( 'Direct Access to this location is not allowed.' );

class processor_onebip extends POSTprocessor
{
	function info()
	{
		$info = array();
		$info['name']				= 'onebip';
		$info['longname']			= 'OneBip';
		$info['statement']			= 'OneBip Mobile Phone Payment';
		$info['description'] 		= 'OneBip Mobile Phone Payment';
		$info['currencies']			= 'EUR,USD,GBP,AUD,CAD,JPY,NZD,CHF,HKD,SGD,SEK,DKK,PLN,NOK,HUF,CZK,MXN,ILS';
		$info['languages']			= 'US';
		$info['recurring']			= 2;
		$info['notify_trail_thanks']	= 1;
		$info['recurring_buttons']		= 2;

		return $info;
	}

	function settings()
	{
		$settings = array();
		$settings['username']	= 'your@onebip.com';
		$settings['site_id']	= '0';
		$settings['currency']	= 'USD';
		$settings['item_name']	= '';

		return $settings;
	}

	function backend_settings()
	{
		$settings = array();

		$settings['username']	= array( 'inputC' );
		$settings['site_id']	= array( 'inputC' );
		$settings['currency']	= array( 'list_currency' );
		$settings['item_name']	= array( 'inputE' );

		$settings = AECToolbox::rewriteEngineInfo( null, $settings );

		return $settings;
	}

	function createGatewayLink( $request )
	{
		global $mosConfig_live_site;

		$var['post_url']	= 'https://www.onebip.com/otms/';
		$var['command']		= 'standard_pay';

		$var['username']	= $this->settings['username'];
		$var['site_id']		= $this->settings['site_id'];

		$var['custom[invoice]']	= $request->invoice->invoice_number;

		$var['price']		= ($request->int_var['amount'])*100;
		$var['item_name']	= AECToolbox::rewriteEngineRQ( $this->settings['item_name'], $request );
		$var['currency']	= $this->settings['currency'];

		$var['cancel_url']	= AECToolbox::deadsureURL( 'index.php?option=com_acctexp&amp;task=cancel' );
		$var['notify_url']	= AECToolbox::deadsureURL( 'index.php?option=com_acctexp&amp;task=onebipnotification' );
		$var['return_url']	= $request->int_var['return_url'];

		return $var;
	}

	function parseNotification( $post )
	{
		$response = array();

		$response['invoice']			= $post['invoice'];

		$response['amount_currency']	= $post['currency'];
		$response['amount_paid']		= $post['price'];

		return $response;
	}

	function validateNotification( $response, $post, $invoice )
	{
		$response['valid'] = 0;

		if ( !$post['error'] && !empty( $response['amount_paid'] ) ) {

			if ( is_object( $invoice ) ) {
				$invoice->setParams( array( 'acceptedpendingecheck' => 1 ) );
				$invoice->storeload();
			}

			$response['valid'] = 1;
		}

		return $response;
	}

}
?>
