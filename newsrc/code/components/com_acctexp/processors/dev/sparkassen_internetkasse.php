<?php
/**
* @version $Id: sparkassen_internetkasse.php
* @package AEC - Account Control Expiration - Membership Manager
* @subpackage Processors - Sparkassen Internetkasse Formularservice
* @copyright 2011 Copyright (C) David Deutsch
* @author David Deutsch <skore@skore.de> & Team AEC - http://www.valanx.org
* @license GNU/GPL v.2 http://www.gnu.org/licenses/old-licenses/gpl-2.0.html or, at your option, any later version
*/

// Dont allow direct linking
( defined('_JEXEC') || defined('_VALID_MOS') ) or die('Direct Access to this location is not allowed.');

class processor_sparkassen_internetkasse extends XMLprocessor
{
	function info()
	{
		$info = array();
		$info['name']			= 'sparkassen_internetkasse';
		$info['longname']		= 'Sparkassen-Internetkasse Formularservice';
		$info['statement']		= 'Sparkassen Internetkasse Formularservice - Die E-Payment-Lösung im Internet';
		$info['description']	= 'Die Sparkassen-Internetkasse ist ein Management-System für das E-Payment im Internet.';
		$info['currencies']		= 'EUR';
		$info['cc_list']		= 'visa,mastercard,eurocard';
		$info['languages']		= AECToolbox::getISO3166_1a2_codes();
		$info['secure']			= 1;

		return $info;
	}

	function settings()
	{
		$settings = array();
		$settings['testmode']			= 0;
		$settings['pseudocreditcard']	= 0;
		$settings['additionalnote']		= '';
		$settings['currency']			= 'EUR';        
		$settings['sslmerchant']		= '';
		$settings['sslmerchantpass']	= '';
		$settings['merchant']			= '';
		$settings['merchantpass']		= '';

		$settings['item_name']			= sprintf(_CFG_PROCESSOR_ITEM_NAME_DEFAULT, '[[cms_live_site]]', '[[user_name]]', '[[user_username]]');

		return $settings;
	}

	function backend_settings()
	{
		$settings = array();
		$settings['testmode']			= array('list_yesno');
		$settings['pseudocreditcard']	= array('list_yesno');
		$settings['additionalnote']		= array('inputC');
		$settings['sslmerchant']		= array('inputC');
		$settings['sslmerchantpass']	= array('inputC');
		$settings['merchant']			= array('inputC');
		$settings['merchantpass']		= array('inputC');

		$settings = AECToolbox::rewriteEngineInfo( null, $settings );

		return $settings;
	}

	function checkoutform( $request )
	{
		$var = array();
		$var['params']['useREG'] = array( 'hidden', 0 );
		$var['params']['usePPAN'] = array( 'hidden', 0 );

		if ( $this->settings['pseudocreditcard'] ) {
			$ppan = $this->getPPAN( $request->metaUser );

			if ( !empty( $ppan ) ) {
				$var['params']['usePPAN'] = array( 'hidden', 1 );
			}
		} else {
			$var['params']['useREG'] = array( 'hidden', 1 );
		}

		return $var;
	}

	function checkoutProcess( $request, $InvoiceFactory )
	{
		if ( !empty( $request->int_var['params']['usePPAN'] ) ) {
			$ppan = $this->getPPAN( $request->metaUser );

			if ( !empty( $ppan ) ) {
				// Make a form to confirm usage of PPAN
				$var = $this->getSIFvars( $request, $ppan );

				$var['params']['usePPAN'] = array( 'hidden', 1, 1 );

				return $var;
			}
		}

		$this->sanitizeRequest( $request );



		$path = '/request/request/prot/Request.po';
		if ( $this->settings['testmode'] ) {
			$url = 'https://testsystem.sparkassen-internetkasse.de' . $path;
		} else {
			$url = 'https://system.sparkassen-internetkasse.de' . $path;
		}

		$curlextra = array();
		$curlextra[CURLOPT_USERPWD] = $this->settings['merchant'] . ':' . $this->settings['merchantpass'];

		$resultstring = $this->transmitRequest( $url, $path, $this->arrayToNVP($var), 443, $curlextra );

		return $resultstring;
	}

	function createRequestXML( $request )
	{
		$var = $this->getSIFvars( $request );

		return $this->arrayToNVP( $var );
	}

	function getSIFvars( $request, $ppan )
	{
		$var = array();

		if ( $ppan ) {
			$var['command']			= 'authorization';
			$var['payment_options']	= 'creditcard';
			$var['orderid']			= $request->invoice->id;
			$var['basketnr']		= $request->invoice->invoice_number;
			$var['amount']			= (int) ( $request->int_var['amount'] * 100 );
			$var['currency']		= $this->settings['currency'];
			$var['ppan']			= $ppan;

			$path = '/request/request/prot/Request.po';
		} else {
			$var['amount']			= str_replace( '.', ',', $request->int_var['amount'] );
			$var['basketid']		= $request->invoice->invoice_number;
			$var['command']			= 'sslform';
			$var['currency']		= trim($this->settings['currency']);
			$var['orderid']			= date('YmdHis');
			$var['payment_options']	= 'cardholder;generate_ppan';
			$var['paymentmethod']	= 'creditcard';
			$var['sessionid']		= session_id();
			$var['sslmerchant']		= trim($this->settings['sslmerchant']);
			$var['transactiontype']	= 'authorization';
			$var['version']			= '1.5';

			$path = '/vbv/mpi_legacy';
		}

		if ( $this->settings['testmode'] ) {
			$url = 'https://testsystem.sparkassen-internetkasse.de' . $path;
		} else {
			$url = 'https://system.sparkassen-internetkasse.de' . $path;
		}

		$astring = array();
		foreach ( $var as $k => $v ) {
			$astring[] = $k.'='.$v;
		}

		$var['mac'] = $this->hmac( $this->settings['sslmerchantpass'], implode( '&amp;', $astring ) );

		$var['post_url'] = '/kasse';

		return $var;
	}

	function parseNotification($post)
	{
		$response = array();
		$response['amount_paid']	= $post['amount'] / 100;
		$response['amount_paid']	= $post['currency'];

		if ( !empty( $response['basketid'] ) ) {
			$response['invoice'] = $response['basketid'];
		} else {
			$response['invoice'] = $response['basketnr'];
		}

		return $response;
	}

	function validateNotification( $response, $post, $invoice )
	{
		$server = $this->settings['redirecturls_server'];

		if ($this->settings['testmode']) {
			$server = $this->settings['redirecturls_testserver'];
		}

		$response['valid'] = 0;

		if ( $response['directPosErrorCode'] == '0' ) {
			$response['valid'] = true;

			if ( $this->settings['pseudocreditcard'] && !empty( $post['ppan'] ) ) {
				$this->setPPAN( $request->metaUser, $post['ppan'] );
			}

			echo 'redirecturls=http://' . $server . '/transaction-success';
		} else {
			$error = $this->getError( $response['posherr'] );

			if ( empty( $error ) ) {
				$error = $post['directPosErrorMessage'];
			}

			$metaUser = new metaUser( $invoice->userid );

			$this->deletePpan( $metaUser );

			$response['pending_reason'] = $msg . ' Fehlercode ' . $response['directPosErrorCode'];
			echo 'redirecturlf=http://' . $server . '/transaction-error?msg=' . urlencode($response['pending_reason']);
		}

		return $response;
	}

	function getError( $errcode )
	{
		$errors = array(	'133' => 'Karte abgelaufen.',
							'344' => 'Karte in Deutschland nicht gültig.',
							'347' => 'Abbruch durch den Benutzer.',
							'349' => 'Karte Transaktionslimit überschritten.',
							'350' => 'Karte gesperrt.'
							);

		if ( isset( $errors[$errcode] ) ) {
			return $errors[$errcode];
		} else {
			return null;
		}
	}

	function setPPAN( $metaUser, $ppan )
	{
		$metaUser->meta->setCustomParams( array( 'ppan' => $ppan ) );
		$metaUser->meta->storeload();

		return true;
	}

	function getPPAN( $metaUser )
	{
		$uparams = $metaUser->meta->getCustomParams();
		
		if ( !empty( $uparams['ppan'] ) ) {
			return $uparams['ppan'];
		}

		return null;
	}

	function deletePPAN( $metaUser )
	{
		$metaUser->meta->setCustomParams( array( 'ppan' => '' ) );
		$metaUser->meta->storeload();

		return true;
	}

	function hmac( $key, $data )
	{
	   // RFC 2104 HMAC implementation for php.
	   // Creates an SHA-1 HMAC.
	   // Eliminates the need to install mhash to compute a HMAC
	   // Hacked by Lance Rushing

	   $b = 64; // byte length for SHA-1

	   $key  = str_pad($key, $b, chr(0x00));
	   $ipad = str_pad('', $b, chr(0x36));
	   $opad = str_pad('', $b, chr(0x5c));
	   $k_ipad = $key ^ $ipad ;
	   $k_opad = $key ^ $opad;

	   return sha1($k_opad  . pack("H*",sha1($k_ipad . $data)));
	}

}

?>