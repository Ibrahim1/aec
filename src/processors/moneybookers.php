<?php
// Dont allow direct linking
defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );

class processor_moneybookers extends POSTprocessor
{
	function info()
	{
		$info = array();
		$info['name']				= 'moneybookers';
		$info['longname']			= _CFG_MONEYBOOKERS_LONGNAME;
		$info['statement']			= _CFG_MONEYBOOKERS_STATEMENT;
		$info['description']		= _CFG_MONEYBOOKERS_DESCRIPTION;
		$info['currencies']			= 'EUR,USD,GBP,AUD,CAD,JPY,NZD,CHF,HKD,SGD,SEK,DKK,PLN,NOK,HUF,CZK';
		$info['languages']			= 'EN,DE,ES,FR,IT,PL,GR,RO,RU,TR,CN,CZ,NL.';
		$info['cc_list']			= 'visa,mastercard';
		$info['recurring']			= 0;

		return $info;
	}

	function settings()
	{
		global $mosConfig_sitename;

		$settings = array();
		$settings['pay_to_email']				= '';
		$settings['secret_word']				= '';
		$settings['recipient_description']		= $mosConfig_sitename;
		$settings['logo_url'] 					= AECToolbox::deadsureURL( '/images/logo.png' );
		$settings['language'] 					= 'EN';
		$settings['hide_login'] 				= 1;
		$settings['currency'] 					= 'USD';
		$settings['confirmation_note']			= "Thank you for subscribing on $mosConfig_sitename!";
		$settings['item_name']					= sprintf( _CFG_PROCESSOR_ITEM_NAME_DEFAULT, '[[cms_live_site]]', '[[user_name]]', '[[user_username]]' );
		return $settings;
	}

	function backend_settings( $cfg )
	{
		$settings = array();
		$settings['pay_to_email']				= array( 'inputC');
		$settings['secret_word']				= array( 'inputC');
		$settings['recipient_description']		= array( 'inputE');
		$settings['logo_url']					= array( 'inputE');
		$settings['language'] 					= array( 'list_language' );
		$settings['hide_login']					= array( 'list_yesno');
		$settings['currency'] 					= array( 'list_currency' );
		$settings['confirmation_note']			= array( 'inputE');
		$settings['item_name']					= array( 'inputE');
 		$rewriteswitches						= array( 'cms', 'user', 'expiration', 'subscription', 'plan');
		$settings = AECToolbox::rewriteEngineInfo( $rewriteswitches, $settings );

		return $settings;
	}

	function createGatewayLink( $request )
	{
		global $mosConfig_live_site;

		$var['post_url']				= 'https://www.moneybookers.com/app/payment.pl';
		$var['pay_to_email']			= $cfg['pay_to_email'];
		$var['recipient_description']	= $cfg['recipient_description'];
		$var['logo_url']				= $cfg['logo_url'];
		$var['transaction_id']			= $int_var['invoice'];

		$var['return_url']				= $int_var['return_url'];
		$var['cancel_url']				= AECToolbox::deadsureURL( '/index.php?option=com_acctexp&amp;task=cancel' );
		$var['status_url']				= AECToolbox::deadsureURL( '/index.php?option=com_acctexp&amp;task=moneybookersnotification' );

		$var['language']				= $cfg['language'];
		$var['hide_login']				= $cfg['hide_login'];
		$var['pay_from_email']			= $metaUser->cmsUser->email;
		$var['amount']					= $int_var['amount'];
		$var['detail1_description']		= AECToolbox::rewriteEngine($cfg['item_name'], $metaUser, $new_subscription);
		$var['detail1_text']			= $metaUser->cmsUser->id;
		$var['currency']				= $cfg['currency'];
		$var['confirmation_note']		= $cfg['confirmation_note'];

		return $var;
	}

	function parseNotification( $post, $cfg )
	{
		$response = array();
		$response['invoice']			= $post['transaction_id'];
		$response['amount_paid']		= $post['mb_amount'];
		$response['amount_currency']	= $post['mb_currency'];

		return $response;
	}

	function validateNotification( $response, $post, $cfg, $invoice )
	{
		$response['valid'] = false;

		$md5sig = md5( $post['merchant_id'] . $post['transaction_id'] . $cfg['secret_word'] . $post['mb_amount'] . $post['mb_currency'] . $post['status'] );

		if ( ( $post['status'] == '2' ) && ( $md5sig == $post['md5sig'] ) ) {
			$response['valid'] = true;
		}

		return $response;
	}

}

?>
