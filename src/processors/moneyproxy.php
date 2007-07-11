<?php
// Dont allow direct linking
defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );

class processor_moneyproxy {

	function processor_moneyproxy () {
		
	}

	function info () {
		$info = array();
		$info['longname'] = "Money Proxy";
		$info['statement'] = "Make Payments in different digital currencies with Money Proxy!";
		$info['description'] = "Accept payments on a website in different digital currencies with a single merchant account.";
		$info['currencies'] = "GAU,CAD,EUR,USD";
		$info['languages'] = "EN";
		$info['cc_list'] = "visa,mastercard,discover,americanexpress,echeck,giropay";

		return $info;
	}

	function settings () {
		$settings = array();
		$settings['merchant_id'] = "merchant_id";
		$settings['force_client_receipt'] = 0;
		$settings['secret_key'] = "secret_key";
		$settings['suggested_memo'] = "";
		$settings['language'] = 'EN';

		return $settings;
	}

	function backend_settings () {
		$settings = array();
		$settings['variable'] = array("type", "name", "description");

		return $settings;
	}

	function createGatewayLink ( $int_var, $metaUser, $cfg, $new_subscription ) {
		global $mosConfig_live_site;

		$var['merchant_id']				= $cfg['merchant_id'];
		$var['amount']					= $int_var['amount'];
		$var['status_url']				= AECToolbox::deadsureURL( '/index.php?option=com_acctexp&amp;task=moneyproxynotification' );
		$var['return_success_url']		= $int_var['return_url'];
		$var['return_success_method']	= 'LINK';
		$var['return_failure_url']		= AECToolbox::deadsureURL( '/index.php?option=com_acctexp&amp;task=cancel' );
		$var['return_failure_method']	= 'LINK';
		$var['payment_id']				= $cfg['merchant_id'];
		$var['force_client_receipt']	= $cfg['force_client_receipt'];
		$var['suggested_memo']			= $cfg['suggested_memo'];
		$var['language']				= $cfg['language'];
		$var['custom1']					= $int_var['invoice'];

		$var['input_hash']				= md5( implode( ':', $var ) );
		
		return $var;
	}

	function parseNotification ( $post, $cfg ) {

		$response = array();
		$response['invoice'] = "";
		$response['valid'] = 0;

		return $response;
	}

}

?>
