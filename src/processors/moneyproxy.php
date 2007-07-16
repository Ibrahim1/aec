<?php
// Dont allow direct linking
defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );

class processor_moneyproxy {

	function processor_moneyproxy () {
		
	}

	function info () {
		$info = array();
		$info['name']			= 'moneyproxy';
		$info['longname']		= _CFG_MONEYPROXY_LONGNAME;
		$info['statement']		= _CFG_MONEYPROXY_STATEMENT;
		$info['description']	= _CFG_MONEYPROXY_DESCRIPTION;
		$info['currencies']		= "GAU,CAD,EUR,USD";
		$info['languages']		= "EN";
		$info['cc_list']		= "visa,mastercard,discover,americanexpress,echeck,giropay";
		$info['recurring']		= 0;

		return $info;
	}

	function settings () {
		$settings = array();
		$settings['merchant_id']			= "merchant_id";
		$settings['force_client_receipt']	= 0;
		$settings['secret_key']				= "secret_key";
		$settings['suggested_memo']			= "";
		$settings['language']				= 'EN';
		$settings['payment_id']				= sprintf( _CFG_PROCESSOR_ITEM_NAME_DEFAULT, '[[cms_live_site]]',
											'[[user_name]]', '[[user_username]]' );
		$settings['rewriteInfo']			= '';

		return $settings;
	}

	function backend_settings () {
		$settings = array();
		$rewriteswitches			= array( 'cms', 'user', 'expiration', 'subscription', 'plan' );

		$settings['merchant_id']			= array( 'inputC' );
		$settings['secret_key']				= array( 'inputC' );
		$settings['force_client_receipt']	= array( 'list_yesno' );
		$settings['suggested_memo']			= array( 'inputD' );
		$settings['language']				= array( 'list_language' );
		$settings['item_name']				= array( 'inputE' );

        $settings['rewriteInfo']			= array( 'fieldset', _AEC_MI_REWRITING_INFO,
        									AECToolbox::rewriteEngineInfo( $rewriteswitches ) );
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
		$var['payment_id']				= substr( AECToolbox::rewriteEngine( $cfg['payment_id'], $metaUser, $new_subscription ), 0, 10 );
		$var['force_client_receipt']	= $cfg['force_client_receipt'];
		$var['suggested_memo']			= substr( $cfg['suggested_memo'], 0, 40 );
		$var['language']				= strtolower( $cfg['language'] );
		$var['custom1']					= $int_var['invoice'];

		$var['input_hash']				= md5( implode( ':', $var ) . ':' . $cfg['secret_key'] );
		
		return $var;
	}

	function parseNotification ( $post, $cfg ) {

		$checkhash = implode( ':', array( $post['MERCHANT_ID'], $post['REFERENCE_NO'], $post['PAYMENT_ID'], $post['AMOUNT'], $post['CURRENCY'], $post['AMOUNT_GAU'], $post['EXRATE'], $post['MONEYPROXY_FEES_GAU'], $post['SYSTEM_FEES_GAU'], $post['PAYMENT_SYSTEM'], $post['CUSTOM1'], $cfg['secret_key'] ) );

		$response = array();
		$response['invoice'] = $post['CUSTOM1'];
		$response['amount_paid'] = $post['AMOUNT'];
		$response['amount_currency'] = $post['CURRENCY'];

		if( $post['HASH'] == $checkhash ) {
			$response['valid'] = true;
		}else{
			$response['valid'] = false;
		}

		return $response;
	}

}

?>