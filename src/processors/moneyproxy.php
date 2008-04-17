<?php
// Dont allow direct linking
defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );

class processor_moneyproxy extends POSTprocessor
{
	function info()
	{
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

	function settings()
	{
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

	function backend_settings()
	{
		$settings = array();

		$settings['merchant_id']			= array( 'inputC' );
		$settings['secret_key']				= array( 'inputC' );
		$settings['force_client_receipt']	= array( 'list_yesno' );
		$settings['suggested_memo']			= array( 'inputD' );
		$settings['language']				= array( 'list_language' );
		$settings['item_name']				= array( 'inputE' );

        $settings = AECToolbox::rewriteEngineInfo( null, $settings );

		return $settings;
	}

	function createGatewayLink( $request )
	{
		global $mosConfig_live_site;

		$var['merchant_id']				= $this->settings['merchant_id'];
		$var['amount']					= $request->int_var['amount'];
		$var['status_url']				= AECToolbox::deadsureURL( '/index.php?option=com_acctexp&amp;task=moneyproxynotification' );
		$var['return_success_url']		= $request->int_var['return_url'];
		$var['return_success_method']	= 'LINK';
		$var['return_failure_url']		= AECToolbox::deadsureURL( '/index.php?option=com_acctexp&amp;task=cancel' );
		$var['return_failure_method']	= 'LINK';
		$var['payment_id']				= substr( AECToolbox::rewriteEngine( $this->settings['payment_id'], $request->metaUser, $request->new_subscription, $request->invoice ), 0, 10 );
		$var['force_client_receipt']	= $this->settings['force_client_receipt'];
		$var['suggested_memo']			= substr( $this->settings['suggested_memo'], 0, 40 );
		$var['language']				= strtolower( $this->settings['language'] );
		$var['custom1']					= $request->int_var['invoice'];

		$var['input_hash']				= md5( implode( ':', $var ) . ':' . $this->settings['secret_key'] );

		return $var;
	}

	function parseNotification( $post )
	{
		$response = array();
		$response['invoice'] = $post['CUSTOM1'];
		$response['amount_paid'] = $post['AMOUNT'];
		$response['amount_currency'] = $post['CURRENCY'];

		return $response;
	}

	function validateNotification( $response, $post, $invoice )
	{
		$checkhash = implode( ':', array( $post['MERCHANT_ID'], $post['REFERENCE_NO'], $post['PAYMENT_ID'], $post['AMOUNT'], $post['CURRENCY'], $post['AMOUNT_GAU'], $post['EXRATE'], $post['MONEYPROXY_FEES_GAU'], $post['SYSTEM_FEES_GAU'], $post['PAYMENT_SYSTEM'], $post['CUSTOM1'], $this->settings['secret_key'] ) );

		if ( $post['HASH'] == $checkhash ) {
			$response['valid'] = true;
		} else {
			$response['valid'] = false;
		}

		return $response;
	}

}
?>