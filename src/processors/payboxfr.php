<?php
/**
 * @version $Id: payboxfr.php 16 2007-06-25 09:04:04Z mic $
 * @package AEC - Account Expiration Control / Subscription management for Joomla
 * @subpackage Payment Processors
 * @author David Deutsch <skore@skore.de>
 * @copyright 2008 David Deutsch
 * @license http://www.gnu.org/copyleft/gpl.html. GNU Public License
 */

// Copyright (C) 2006-2007 David Deutsch
// All rights reserved.
// This source file is part of the Account Expiration Control Component, a  Joomla
// custom Component By Helder Garcia and David Deutsch - http://www.globalnerd.org
//
// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License (GPL)
// as published by the Free Software Foundation; either version 2
// of the License, or (at your option) any later version.
//
// Please note that the GPL states that any headers in files and
// Copyright notices as well as credits in headers, source files
// and output (screens, prints, etc.) can not be removed.
// You can extend them with your own credits, though...
//
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with this program; if not, write to the Free Software
// Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
//
// The "GNU General Public License" (GPL) is available at
// http://www.gnu.org/copyleft/gpl.html.

// Dont allow direct linking
defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );

class processor_payboxfr extends POSTprocessor
{
	function info()
	{
		$info = array();
		$info['name']				= 'payboxfr';
		$info['longname']			= _CFG_PAYBOXFR_LONGNAME;
		$info['statement']			= _CFG_PAYBOXFR_STATEMENT;
		$info['description'] 		= _CFG_PAYBOXFR_DESCRIPTION;
		$info['currencies']			= 'EUR,USD,GBP,AUD,CAD,JPY,NZD';
		$info['languages']			= 'GB,DE,FR,IT,ES,SV,NL';
		$info['cc_list']			= 'visa,mastercard,discover,americanexpress,echeck,giropay';
		$info['recurring']			= 2;

		return $info;
	}

	function settings()
	{
		$settings = array();
		$settings['site']			= 'site';
		$settings['testmode']		= 1;
		$settings['rank']			= 'rank';
		$settings['currency']		= 'EUR';
		$settings['language']		= 'FR';
		$settings['recurring']		= 0;
		$settings['item_name']		= sprintf( _CFG_PROCESSOR_ITEM_NAME_DEFAULT, '[[cms_live_site]]', '[[user_name]]', '[[user_username]]' );
		$settings['rewriteInfo']	= '';

		return $settings;
	}

	function backend_settings( $cfg )
	{
		$settings = array();
		$rewriteswitches			= array( 'cms', 'user', 'expiration', 'subscription', 'plan' );

		$settings['site']			= array( 'inputC' );
		$settings['testmode']		= array( 'list_yesno' );
		$settings['rank']			= array( 'inputC' );
		$settings['currency']		= array( 'list_currency' );
		$settings['language']		= array( 'list_language' );
		$settings['recurring']		= array( 'list_recurring' );
		$settings['no_shipping']	= array( 'list_yesno' );
		$settings['altipnurl']		= array( 'inputC' );
		$settings['item_name']		= array( 'inputE' );
		$settings['rewriteInfo']	= array( 'fieldset', _AEC_MI_REWRITING_INFO, AECToolbox::rewriteEngineInfo( $rewriteswitches ) );

		return $settings;
	}

	function createGatewayLink( $int_var, $cfg, $metaUser, $new_subscription )
	{
		global $mosConfig_live_site;

		if ( $cfg['testmode'] ) {
			$var['post_url']	= 'https://www.sandbox.payboxfr.com/cgi-bin/webscr';
		} else {
			$var['post_url']	= 'https://www.payboxfr.com/cgi-bin/webscr';
		}

		$var['PBX_MODE']		= '1';
		$var['PBX_SITE']		= $cfg['site'];
		$var['PBX_RANG']		= $cfg['rank'];
		$var['PBX_TOTAL']		= $int_var['amount'] * 100;

		$iso4217num = array( 'EUR' => 978, 'USD' => 840, 'GBP' => 826, 'AUD' => 036, 'CAD' => 124, 'JPY' => 392, 'NZD' => 554 );

		if ( isset( $iso4217num[$cfg['currency']] ) ) {
			$var['PBX_DEVISE']		= $iso4217num[$cfg['currency']];
		} else {
			$var['PBX_DEVISE']		= '978';
		}

		$var['PBX_CMD']			= $int_var['invoice'];
		$var['PBX_PORTEUR']		= $metaUser->cmsUser->email;

		$iso639_2to3 = array( 'GB' => 'GBR', 'DE' => 'DEU', 'FR' => 'FRA', 'IT' => 'ITA', 'ES' => 'ESP', 'SW' => 'SWE', 'NL' => 'NLD' );

		$var['PBX_LANGUE']		= $iso639_2to3[$cfg['language']];

		$var['cancel']			= AECToolbox::deadsureURL( '/index.php?option=com_acctexp&amp;task=cancel' );

		$var['notify_url']		= AECToolbox::deadsureURL( '/index.php?option=com_acctexp&amp;task=payboxfrnotification' );

		$var['item_number']		= $metaUser->cmsUser->id;
		$var['item_name']		= AECToolbox::rewriteEngine( $cfg['item_name'], $metaUser, $new_subscription );

		$var['no_shipping']		= $cfg['no_shipping'];
		$var['no_note']			= '1';
		$var['rm']				= '2';

		$var['return']			= $int_var['return_url'];
		$var['currency_code']	= $cfg['currency'];

		return $var;
	}

	function parseNotification( $post, $cfg )
	{
		global $database;

		$mc_gross			= $post['mc_gross'];
		if ( $mc_gross == '' ) {
			$mc_gross 		= $post['mc_amount1'];
		}
		$mc_currency		= $post['mc_currency'];

		$response = array();
		$response['invoice'] = $post['invoice'];
		$response['amount_paid'] = $mc_gross;
		$response['amount_currency'] = $mc_currency;

		return $response;
	}

	function validateNotification( $response, $post, $cfg, $invoice )
	{
		if ($cfg['testmode']) {
			$ppurl = 'www.sandbox.payboxfr.com';
		} else {
			$ppurl = 'www.payboxfr.com';
		}

		$req = 'cmd=_notify-validate';

		foreach ( $post as $key => $value ) {
			$value = urlencode( stripslashes( $value ) );
			$req .= "&$key=$value";
		}

		$fp = null;
		// try to use fsockopen. some hosting systems disable fsockopen (godaddy.com)
		$fp = $this->doTheHttp( $ppurl, $req );
		if (!$fp) {
			// If fsockopen doesn't work try using curl
			$fp = $this->doTheCurl( $ppurl, $req );
		}

		$res = $fp;

		$response['responsestring'] = 'payboxfr_verification=' . $res . "\n" . $response['responsestring'];

		$txn_type			= $post['txn_type'];
		$receiver_email		= $post['receiver_email'];
		$payment_status		= $post['payment_status'];
		$payment_type		= $post['payment_type'];

		$response['valid'] = 0;

		if ( strcmp( $receiver_email, $cfg['business'] ) != 0 && $cfg['checkbusiness'] ) {
			$response['pending_reason'] = 'checkbusiness error';
		} elseif ( strcmp( $res, 'VERIFIED' ) == 0 ) {
			// Process payment: Paypal Subscription & Buy Now
			if ( strcmp( $txn_type, 'web_accept' ) == 0 || strcmp( $txn_type, 'subscr_payment' ) == 0 ) {

				$recurring = ( strcmp( $txn_type, 'subscr_payment' ) == 0 );

				if ( ( strcmp( $payment_type, 'instant' ) == 0 ) && ( strcmp( $payment_status, 'Pending' ) == 0 ) ) {
					$response['pending_reason'] = $post['pending_reason'];
				} elseif ( strcmp( $payment_type, 'instant' ) == 0 && strcmp( $payment_status, 'Completed' ) == 0 ) {
					$response['valid']			= 1;
				} elseif ( strcmp( $payment_type, 'echeck' ) == 0 && strcmp( $payment_status, 'Pending' ) == 0 ) {
					if ( $cfg['acceptpendingecheck'] ) {
						if ( is_object( $invoice ) ) {
							$invoice->setParams( array( 'acceptedpendingecheck' => 1 ) );
							$invoice->check();
							$invoice->store();
						}

						$response['valid']			= 1;
						$response['pending_reason'] = 'echeck';
					} else {
						$response['pending']		= 1;
						$response['pending_reason'] = 'echeck';
					}
				} elseif ( strcmp( $payment_type, 'echeck' ) == 0 && strcmp( $payment_status, 'Completed' ) == 0 ) {
					if ( $cfg['acceptpendingecheck'] ) {
						if ( is_object( $invoice ) ) {
							$invoiceparams = $invoice->getParams();

							if ( isset( $invoiceparams['acceptedpendingecheck'] ) ) {
								$response['valid']		= 0;
								$response['duplicate']	= 1;

								$invoice->delParams( array( 'acceptedpendingecheck' ) );
								$invoice->check();
								$invoice->store();
							}
						} else {
							$response['valid']			= 1;
						}
					} else {
						$response['valid']			= 1;
					}
				}
			} elseif ( strcmp( $txn_type, 'subscr_signup' ) == 0 ) {
				$response['pending']		= 1;
				$response['pending_reason'] = 'signup';
			} elseif ( strcmp( $txn_type, 'subscr_eot' ) == 0 ) {
				$response['eot']				= 1;
			} elseif ( strcmp( $txn_type, 'subscr_cancel' ) == 0 ) {
				$response['cancel']				= 1;
			}
		} else {
			$response['pending_reason']			= 'error: ' . $res;
		}

		return $response;
	}

}
?>