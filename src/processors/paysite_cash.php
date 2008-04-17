<?php
/**
 * @version $Id: paysite_cash.php
 * @package AEC - Account Expiration Control / Subscription management for Joomla
 * @subpackage Payment Processors
 * @author David Deutsch <skore@skore.de>
 * @copyright 2004-2007 David Deutsch
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

		return $i;
	}

	function settings()
	{
		$s = array();
		$s['siteid']	= "siteid";
		$s['secret']	= "secret";

		return $s;
	}

	function backend_settings()
	{
		$s = array();
		$s['siteid']		= array( 'inputC' );
		$s['secret']		= array( 'inputC' );

		$rewriteswitches	= array( 'cms', 'user', 'expiration', 'subscription', 'plan' );
		$settings = AECToolbox::rewriteEngineInfo( $rewriteswitches, $settings );
		return $s;
	}

	function createGatewayLink( $request )
	{
		if ( $this->settings['testmode'] ) {
			$var['test'] = 1;
		}

		$var['post_url'] = " https://billing.paysite-cash.biz/?";
		$var['site'] = $this->settings['siteid'];
		$var['montant'] = $request->int_var['amount'];
		$var['devise'] = $this->settings['currency'];

		$var['divers'] = base64_encode( md5( $this->settings['secret'] . $request->int_var['invoice'] . $request->int_var['amount'] . $this->settings['currency'] ) );

		$var['ref'] = $request->int_var['invoice'];

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
		switch ( $post['res'] ) {
			case 'ok':
				$misc = base64_encode( md5( $this->settings['secret'] . $post['ref'] . $post['montant_org'] . $post['devise_org'] ) );

				if ( $misc == $post['divers'] ) {
					$response['valid'] = true;
				} else {
					$response['valid'] = false;
				}

				$response['amount_paid'] = $post['montant_sent'];
				$response['amount_currency'] = $post['devise_sent'];
				break;
			case 'ko':
				$response['valid'] = false;
				break;
			case 'end':
				$response['valid'] = false;
				break;
			case 'refund':
				$response['valid'] = false;
				break;
			case 'chargeback':
				$response['valid'] = false;
				break;
		}

		return $response;
	}

}

?>
