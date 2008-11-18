<?php
/**
 * @version $Id: paysite_cash.php
 * @package AEC - Account Control Expiration - Membership Manager
 * @subpackage Processors - Paysite Cash
 * @author David Deutsch <skore@skore.de> & Team AEC - http://www.globalnerd.org
 * @copyright 2006-2008 Copyright (C) David Deutsch
 * @license GNU/GPL v.2 http://www.gnu.org/licenses/old-licenses/gpl-2.0.html or, at your option, any later version
 */

// Dont allow direct linking
( defined('_JEXEC') || defined( '_VALID_MOS' ) ) or die( 'Direct Access to this location is not allowed.' );

class processor_paysite_cash extends URLprocessor
{
	function info()
	{
		$i = array();
		$i['longname'] = _CFG_PAYSITE_CASH_LONGNAME;
		$i['statement'] = _CFG_PAYSITE_CASH_STATEMENT;
		$i['description'] = _CFG_PAYSITE_CASH_DESCRIPTION;
		$i['currencies'] = 'EUR,USD,CAD,GBP,CHF';
		$i['languages'] = 'FR,US';
		$i['cc_list'] = 'visa,mastercard,discover,americanexpress,echeck';
		$i['notify_trail_thanks'] = 1;
		$i['recurring'] = 2;

		return $i;
	}

	function settings()
	{
		$s = array();
		$s['siteid']		= "siteid";
		$s['secret']		= "secret";
		$s['customparams']	= "";

		return $s;
	}

	function backend_settings()
	{
		$s = array();
		$s['siteid']		= array( 'inputC' );
		$s['secret']		= array( 'inputC' );
		$s['customparams']	= array( 'inputD' );

		return $s;
	}

	function createGatewayLink( $request )
	{
		if ( $this->settings['testmode'] ) {
			$var['test'] = 1;
		}

		$var['post_url'] = " https://billing.paysite-cash.biz/?";
		$var['site'] = $this->settings['siteid'];
		$var['devise'] = $this->settings['currency'];

		if ( is_array( $request->int_var['amount'] ) ) {
			$suffix = '';
			if ( isset( $request->int_var['amount']['amount1'] ) ) {
				$var['periode'] = $request->int_var['amount']['period1'] . strtolower( $request->int_var['amount']['unit1'] );
				$var['montant'] = $request->int_var['amount']['amount1'];
				$suffix = '2';
			}

			$var['periode'.$suffix] = $request->int_var['amount']['period3'] . strtolower( $request->int_var['amount']['unit3'] );
			$var['montant'.$suffix] = $request->int_var['amount']['amount3'];

			$var['redebit'] = 'x';
			$var['subscription'] = 1;
		} else {
			$var['montant'] = $request->int_var['amount'];
		}

		$var['divers'] = base64_encode( md5( $this->settings['secret'] . $request->int_var['invoice'] ) );

		$var['ref'] = $request->int_var['invoice'];

		$var['email'] = $request->int_var['invoice'];
		$var['user'] = $request->metaUser->cmsUser->username;
		$var['pass'] = 'xxxx';

		foreach ( $var as $key => $value ) {
			if ( $key != 'post_url' ) {

			}
		}

		return $var;
	}

	function parseNotification( $post )
	{
		$response = array();
		$response['invoice'] = $post['ref'];

		return $response;
	}

	function validateNotification( $response, $post, $invoice )
	{
		$response['valid'] = false;

		switch ( $post['etat'] ) {
			case 'ok':
				$misc = base64_encode( md5( $this->settings['secret'] . $post['ref'] ) );

				if ( $misc == $post['divers'] ) {
					$response['valid'] = true;
				} else {
					$response['valid'] = false;
				}

				/*$response['amount_paid']		= $post['montant_sent'];
				$response['amount_currency']	= $post['devise_sent'];*/
				break;
			case 'ko':
				$response['valid'] = false;
				break;
			case 'end':
				$response['eot'] = true;
				break;
			case 'refund':
				$response['delete'] = true;
				break;
			case 'chargeback':
				$response['chargeback'] = true;
				break;
		}

		return $response;
	}

}

?>
