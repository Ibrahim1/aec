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
		$info['currencies']		= 'EUR';
		$info['languages']		= "FR,EN,DE,IT,ES,NL";
		$info['cc_list']		= "visa,mastercard,discover,americanexpress,echeck";
		$info['recurring']		= 2;

		return $info;
	}

	function settings()
	{
		$settings = array();
		$settings['testmode']		= 0;
		$settings['key']			= '0123456789ABCDEF0123456789ABCDEF01234567';
		$settings['currency']		= 'EUR';
		$settings['language']		= 'FR';
		$settings['server']			= 0;
		$settings['item_name']		= sprintf( JText::_('CFG_PROCESSOR_ITEM_NAME_DEFAULT'), '[[cms_live_site]]', '[[user_name]]', '[[user_username]]' );
		$settings['customparams']	= "";

		$settings = AECToolbox::rewriteEngineInfo( null, $settings );

		return $settings;
	}


	function backend_settings()
	{
		$settings = array();
		$settings['testmode']		= array( 'toggle' );
		$settings['tpe']			= array( 'inputC' );
		$settings['soc']			= array( 'inputC' );
		$settings['pass']			= array( 'inputC' );
		$settings['key']			= array( 'inputC' );
		$settings['language']		= array( 'list_language' );
		$settings['item_name']		= array( 'inputE' );
		$settings['customparams']	= array( 'inputD' );

		return $settings;
	}

	function createGatewayLink( $request )
	{
		$var['post_url']			= "https://paiement.systempay.fr/vads-payment/";

		$var['vads_site_id']		= $this->settings['siteid'];
		$var['vads_ctx_mode']		= 'PRODUCTION';
		$var['vads_version']		= 'V2';
		$var['vads_language']		= 'fr';
		$var['vads_currency']		= '978';
		$var['vads_amount']			= $request->int_var['amount']*100;
		$var['vads_page_action']	= 'PAYMENT';
		$var['vads_action_mode']	= 'INTERACTIVE';
		$var['vads_validation_mode']= '0';
		$var['vads_payment_cards']	= '';

		if ( is_array( $request->int_var['amount'] ) ) {
			$var['vads_payment_config']	= 'MULTI:first=900;count=12;period=30';
		} else {
			$var['vads_payment_config']	= 'SINGLE';
		}

		$var['vads_capture_delay']	= '0';
		$var['vads_order_id']		= $request->invoice->invoice_number;
		$var['vads_cust_id']		= $request->metaUser->userid;
		$var['vads_cust_name']		= $request->metaUser->cmsUser->name;
		$var['vads_cust_email']		= $request->metaUser->cmsUser->email;
		$var['vads_cust_country']	= $this->settings['language'];
		$var['societe']				= $this->settings['soc'];
		$var['vads_return_mode']	= 'GET';
		$var['vads_redirect_success_timeout'] 	= '5';
		$var['vads_redirect_success_message'] 	= 'Redirection vers la boutique dans quelques instants';
		$var['vads_redirect_error_timeout'] 	= '5';
		$var['vads_redirect_error_message'] 	= 'Redirection vers la boutique dans quelques instants';

		$var['vads_trans_id']		= str_pad($request->invoice->id, 6, "0", STR_PAD_LEFT);
		$var['vads_trans_date']		= gmdate("YmdHis",time());
		$var['vads_url_return']		= AECToolbox::deadsureURL( "index.php?option=com_acctexp&amp;task=thanks" );
		$var['url_refused']			= AECToolbox::deadsureURL( "index.php?option=com_acctexp&amp;task=cancel" );
		$var['vads_url_cancel']		= AECToolbox::deadsureURL( "index.php?option=com_acctexp&amp;task=cancel" );
		$var['url_error']			= AECToolbox::deadsureURL( "index.php?option=com_acctexp&amp;task=cancel" );

		$var['signature'] = $this->getHash( $var );

		foreach ( $var as $k => $v ) {
			$var[$k] = $this->HtmlEncode( $v );
		}

		return $var;
	}

	function parseNotification( $post )
	{
		$response = array();
		$response['invoice'] = $post['texte-libre'];

		return $response;
	}

	function validateNotification( $response, $post, $invoice )
	{
		switch( $post['retour'] ) {
			case 'payetest':
				$response['valid']	= $this->settings['testmode'] ? true : false;
				break;
			case 'paiement':
				$response['valid']	= true;
				break;
			case 'annulation':
				$response['valid']	= false;
				$response['cancel']	= 1;
				break;
		}

		if ( $post['MAC'] !== $this->getHash( $post, false ) ) {
			$response['pending_reason'] = 'invalid HMAC';
			$response['valid'] = false;
		}

		$response['amount_paid'] = substr( $response['montant'], -3 );
		$response['amount_currency'] = str_replace( $response['amount_paid'], '', $response['montant'] );

		return $response;
	}

	function HtmlEncode( $data )
	{
		$SAFE_OUT_CHARS = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890._-";
		$encoded_data = "";
		$result = "";
		for ($i=0; $i<strlen($data); $i++) {
			if (strchr($SAFE_OUT_CHARS, $data{$i})) {
				$result .= $data{$i};
			} else if (($var = bin2hex(substr($data,$i,1))) <= "7F") {
				$result .= "&#x" . $var . ";";
			} else {
				$result .= $data{$i};
			}
		}
		return $result;
	}

	function getHash( $array, $send=true )
	{
		if ( $send ) {
			$array['lupper']	= strtoupper( $array['vads_language'] );
			$array['key']		= $this->settings['tpe'];

			$check = array();

			$keys = array(	'vads_action_mode', 'vads_amount', 'vads_capture_delay', 'vads_ctx_mode',
							'vads_currency', 'lupper', 'vads_cust_email', 'vads_cust_id',
							'vads_cust_name', 'vads_language', 'vads_order_id', 'vads_page_action',
							'vads_payment_cards', 'vads_payment_config', 'vads_redirect_error_message', 'vads_redirect_error_timeout',
							'vads_redirect_success_message', 'vads_redirect_success_timeout', 'vads_return_mode', 'vads_site_id',
							'vads_trans_date', 'vads_trans_id', 'vads_url_cancel', 'vads_url_return',
							'vads_validation_mode', 'vads_version', 'vads_version', 'vads_code'
						);

			$glue = '+';
		} else {
			$array['version']	= '3.0';
			$array['key']		= $this->settings['tpe'];

			$check = array( $this->settings['tpe'] );

			$keys = array(	'key', 'date', 'montant', 'reference', 'texte-libre', 'version',
							'code-retour', 'cvx', 'vld', 'brand', 'status3ds', 'numauto',
							'motifrefus', 'originecb', 'bincb', 'hpancb', 'ipclient', 'originetr',
							'veres', 'pares'
						);

			$glue = '*';
		}

		foreach ( $keys as $key ) {
			if ( isset( $array[$key] ) ) {
				$check[] = $array[$key];
			} else {
				$check[] = '';
			}
		}

		$string = implode( $glue, $check );

		if ( $send ) {
			return sha1( $string );
		} else {
			return $this->CMIC_hmac( $string );
		}
	}

	function CMCIC_hmac( $data="")
	{
		$k1 = pack( "H*", sha1( $this->settings['pass'] ) );
		$l1 = strlen( $k1 );
		$k2 = pack( "H*", $this->settings['key'] );
		$l2 = strlen( $k2 );

		if ( $l1 > $l2 ) {
			//$k2 = str_pad( $k2, $l1, chr(0x00) );
		} elseif ( $l2 > $l1 ) {
			$k1 = str_pad( $k1, $l2, chr(0x00) );
		}

		if ( $data == "" ) {
			$d = "CtlHmac" . $this->settings['ver'] . $this->settings['tpe'];
		} else {
			$d = $data;
		}

		return strtolower( $this->hmac( $k2, $d ) );
		// return strtolower( $this->hmac( $k1 ^ $k2, $d ) );
	}

	function hmac( $key, $data )
	{
		// RFC 2104 HMAC implementation for php.
		// Creates an md5 HMAC.
		// Eliminates the need to install mhash to compute a HMAC
		// Hacked by Lance Rushing

		$b = 64; // byte length for md5

		if ( strlen( $key ) > $b ) {
			$key = pack( "H*", md5( $key ) );
		}
		$key  = str_pad( $key, $b, chr(0x00) );
		$ipad = str_pad( '', $b, chr(0x36) );
		$opad = str_pad( '', $b, chr(0x5c) );
		$k_ipad = $key ^ $ipad;
		$k_opad = $key ^ $opad;

		return md5( $k_opad  . pack( "H*", md5( $k_ipad . $data ) ) );
	}

}

?>
