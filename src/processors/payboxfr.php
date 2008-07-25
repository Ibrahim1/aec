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
		$settings['identifiant']	= 'identifiant';
		$settings['publickey']		= 'publickey';
		$settings['path']			= '/cgi-bin/modulev2.cgi';
		$settings['currency']		= 'EUR';
		$settings['language']		= 'FR';
		$settings['customparams']	= "";

		return $settings;
	}

	function backend_settings()
	{
		$settings = array();
		$settings['site']			= array( 'inputC' );
		$settings['testmode']		= array( 'list_yesno' );
		$settings['rank']			= array( 'inputC' );
		$settings['identifiant']	= array( 'inputC' );
		$settings['publickey']		= array( 'inputD' );
		$settings['path']			= array( 'inputC' );
		$settings['info']			= array( 'fieldset' );
		$settings['currency']		= array( 'list_currency' );
		$settings['language']		= array( 'list_language' );
		$settings['customparams']	= array( 'inputD' );

		return $settings;
	}

	function createGatewayLink( $request )
	{
		global $mosConfig_live_site;

		$var['post_url']	= $this->settings['path'];

		if ( $this->settings['testmode'] ) {
			$var['PBX_AUTOSEULE'] = 'O';
		}

		$var['PBX_MODE']		= '1';
		$var['PBX_RUF1']		= 'POST';
		$var['PBX_SITE']		= $this->settings['site'];
		$var['PBX_RANG']		= $this->settings['rank'];
		$var['PBX_IDENTIFIANT']	= $this->settings['identifiant'];

		if ( is_array( $request->int_var['amount'] ) ) {
			switch ( $request->int_var['amount']['unit3'] ) {
				case 'D':
					$period = max( 1, ( (int) ( $request->int_var['amount']['period3'] / 30 ) ) );
					break;
				case 'W':
					$period = max( 1, ( (int) ( $request->int_var['amount']['period3'] / 4 ) ) );
					break;
				case 'M':
					$period = $request->int_var['amount']['period3'];
					break;
				case 'Y':
					$period = ( $request->int_var['amount']['period3'] * 12 );
					break;
			}

			$append = '';
			$svars = array();
			$svars['IBS_2MONT']		= '0000000000';
			$svars['IBS_NBPAIE']	= '00';
			$svars['IBS_FREQ']		= str_pad( $period, 2, '0', STR_PAD_LEFT );
			$svars['IBS_QUAND']		= '00';
			$svars['IBS_DELAIS']	= '000';

			foreach ( $svars as $svname => $svvar ) {
				$append .= $svname . $svvar;
			}

			$var['PBX_TOTAL']	= round( $request->int_var['amount']['amount3'] * 100 );

			$var['PBX_CMD']		= $request->int_var['invoice'] . $append;
		} else {
			$var['PBX_TOTAL']	= $request->int_var['amount'] * 100;
			$var['PBX_CMD']		= $request->int_var['invoice'];
		}

		$iso4217num = array( 'EUR' => 978, 'USD' => 840, 'GBP' => 826, 'AUD' => 036, 'CAD' => 124, 'JPY' => 392, 'NZD' => 554 );

		if ( isset( $iso4217num[$this->settings['currency']] ) ) {
			$var['PBX_DEVISE']	= $iso4217num[$this->settings['currency']];
		} else {
			$var['PBX_DEVISE']	= '978';
		}

		$var['PBX_PORTEUR']		= $request->metaUser->cmsUser->email;

		$iso639_2to3 = array( 'GB' => 'GBR', 'DE' => 'DEU', 'FR' => 'FRA', 'IT' => 'ITA', 'ES' => 'ESP', 'SW' => 'SWE', 'NL' => 'NLD' );

		$var['PBX_LANGUE']		= $iso639_2to3[$this->settings['language']];

		$var['PBX_EFFECTUE']	= $request->int_var['return_url'];
		$var['PBX_ANNULE']		= AECToolbox::deadsureURL( '/index.php?option=com_acctexp&amp;task=cancel' );

		$var['PBX_RETOUR']		= 'option:com_acctexp;task:payboxfrnotification;amount:M;invoice:R;authorization:A;transaction:T;subscriptionid:B;error:E;';//check:K';
//test
		if ( !empty( $this->settings['customparams'] ) ) {
			$custom = explode( "\n", $this->settings['customparams'] );

			foreach ( $custom as $c ) {
				$l = explode( '=', $c );

				if ( !empty( $l[0] ) && !empty( $l[1] ) ) {
					$var[trim($l[0])] = trim($l[1]);
				}
			}
		}

		return $var;
	}

	function parseNotification( $post )
	{
		global $database;

		$response = array();

		$returnstring = aecGetParam('invoice');
		$checkpos = strpos( 'IBS_2MO', $returnstring );

		if ( $checkpos ) {
			$response['invoice'] = substr( $returnstring, 0, $checkpos );
		} else {
			$response['invoice'] = $returnstring;
		}

		$response['amount_paid'] = aecGetParam('amount') / 100;

		return $response;
	}

	function validateNotification( $response, $post, $invoice )
	{
		$response['valid'] = 0;

		$gets = array( 'option', 'task', 'amount', 'invoice', 'authorization', 'transaction', 'subscriptionid', 'error', 'check' );

		$return = array();
		foreach ( $gets as $get ) {
			$return[$get] = aecGetParam($get);
		}

		if ( !isset( $return['check'] ) ) {
			$response['pending_reason']			= 'error: No checking string provided';
			return $response;
		} elseif ( !isset( $this->settings['publickey'] ) ) {
			$response['pending_reason']			= 'error: No Public Key provided';
			return $response;
		}

		$check = base64_decode( urldecode( $return['check'] ) );

		unset( $return['check'] );

		$carr = array();
		foreach ( $return as $rname => $rvalue ) {
			$carr[] = $rname . '=' . $rvalue;
		}

		$cstring = implode( '&', $carr );

		if ( crypt( sha1( $cstring ), $this->settings['publickey'] ) == $check ) {
			$response['valid'] = 1;
		} else {
			aecDebug($check);aecDebug(crypt( sha1( $cstring ), $this->settings['publickey'] ));
			$response['pending_reason']			= 'error: Public Key Mismatch';
		}

		return $response;
	}

}
?>