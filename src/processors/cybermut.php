<?php
/**
 * @version $Id: cybermut.php
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

class processor_cybermut extends POSTprocessor
{
	function info()
	{
		$info = array();
		$info['longname'] = _CFG_CYBERMUT_LONGNAME;
		$info['statement'] = _CFG_CYBERMUT_STATEMENT;
		$info['description'] = _CFG_CYBERMUT_DESCRIPTION;
		$info['currencies'] = "EUR,USD,GBP,CHF";
		$info['languages'] = "FR,EN,DE,IT,ES,NL";
		$info['cc_list'] = "visa,mastercard,discover,americanexpress,echeck,giropay";

		return $info;
	}

	function settings()
	{
		$settings = array();
		$settings['testmode']	= 0;
		$settings['tpe']		= '7654321';
		$settings['ver']		= '1.2open';
		$settings['soc']		= 'societe';
		$settings['pass']		= 'passphrase';
		$settings['key']		= '000102030405060708090A0B0C0D0E0F10111213';
		$settings['currency']	= 'EUR';
		$settings['language']	= 'FR';
		$settings['server']		= 0;
		$settings['item_name']	= sprintf( _CFG_PROCESSOR_ITEM_NAME_DEFAULT, '[[cms_live_site]]', '[[user_name]]', '[[user_username]]' );

		return $settings;
	}


	function backend_settings( $cfg )
	{
		$settings = array();
		$settings['testmode']	= array( 'list_yesno' );
		$settings['tpe']		= array( 'inputC' );
		$settings['ver']		= array( 'inputC' );
		$settings['soc']		= array( 'inputC' );
		$settings['pass']		= array( 'inputC' );
		$settings['key']		= array( 'inputC' );
		$settings['currency']		= array( 'list_currency' );
		$settings['server']		= array( 'list' );
		$settings['language']	= array( 'list_language' );
		$settings['item_name']	= array( 'inputE' );

		$servers = array( 'paiement.creditmutuel.fr', 'ssl.paiement.cic-banques.fr', 'ssl.paiement.banque-obc.fr', 'paiement.caixanet.fr', 'creditmutuel.fr/telepaiement' );

		$server_selection = array();
		foreach ( $servers as $i => $server ) {
			$server_selection[] = mosHTML::makeOption( $i, $server );
		}

		$settings['lists']['cybermut_server'] = mosHTML::selectList( $server_selection, 'cybermut_server', 'size="5"', 'value', 'text', $cfg['server'] );

		return $settings;
	}

	function createGatewayLink( $int_var, $cfg, $metaUser, $new_subscription )
	{
		global $mosConfig_live_site;

		$servers = array( 'paiement.creditmutuel.fr', 'ssl.paiement.cic-banques.fr', 'ssl.paiement.banque-obc.fr', 'paiement.caixanet.fr', 'creditmutuel.fr/telepaiement' );

		if ( $cfg['testmode'] ) {
			$var['post_url'] = "https://" . $servers[$cfg['server']] . "/test/paiement.cgi";
		} else {
			$var['post_url'] = "https://" . $servers[$cfg['server']] . "/paiement.cgi";
		}

		$var['version']			= $cfg['ver'];
		$var['TPE']				= $cfg['tpe'];
		$var['date']			= date( "d/m/Y:H:i:s" );
		$var['montant']			= $int_var['amount'] . $cfg['currency'];
		$var['reference']		= $metaUser->userid;
		$var['texte-libre']		= $int_var['invoice'];
		$var['lgue']			= $cfg['language'];
		$var['societe']			= $cfg['soc'];
//print_r($var);print_r($int_var);exit();
		$HMAC = $var['TPE']."*".$var['date']."*".$var['montant']."*".$var['reference']."*".$var['texte-libre']."*".$var['version']."*".$var['lgue']."*".$var['societe']."*";

		//$var['MAC']				= "V1.03.sha1.php4--CtlHmac-" . $cfg['ver'] . "-[" . $cfg['tpe'] . "]-" . $this->CMCIC_hmac( $cfg, $HMAC );
		$var['MAC']				= $this->CMCIC_hmac( $cfg, $HMAC );

		/*$var['retourPLUS']		= $int_var['return_url'];
		$var['societe']			= $cfg['key'];*/

		$var['url_retour']		= $mosConfig_live_site . '/index.php';
		$var['url_retour_ok']	= $mosConfig_live_site . '/index.php?option=com_acctexp&task=thanks';
		$var['url_retour_err']	= $mosConfig_live_site . '/index.php?option=com_acctexp&task=cancel';

		foreach ( $var as $k => $v ) {
			$var[$k] = $this->HtmlEncode( $v );
		}

		return $var;
	}

	function parseNotification( $post, $cfg )
	{
		$response = array();
		$response['invoice'] = $post['texte-libre'];

		return $response;
	}

	function validateNotification( $response, $post, $cfg, $invoice )
	{
		switch( $post['retour'] ) {
			case 'payetest':
				$response['valid'] = $cfg['testmode'] ? true : false;
				break;
			case 'paiement':
				$response['valid'] = true;
				break;
			case 'annulation':
				$response['valid'] = false;
				$response['cancel'] = 1;
				break;
		}

		$HMAC = $post['retourPLUS']."+".$cfg['tpe']."+".$post['date']."+".$post['montant']."+".$post['reference']."+".$post['texte-libre']."+".$cfg['version']."+".$post['retour']."+";

		if ( $post['MAC'] !== $this->CMIC_hmac( $cfg, $HMAC ) ) {
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

	function CMCIC_hmac( $cfg, $data="")
	{
		$k1 = pack( "H*", sha1( $cfg['pass'] ) );
		$l1 = strlen( $k1 );
		$k2 = pack( "H*", $cfg['key'] );
		$l2 = strlen( $k2 );

		if ( $l1 > $l2 ) {
			//$k2 = str_pad( $k2, $l1, chr(0x00) );
		} elseif ( $l2 > $l1 ) {
			$k1 = str_pad( $k1, $l2, chr(0x00) );
		}

		if ( $data == "" ) {
			$d = "CtlHmac" . $cfg['ver'] . $cfg['tpe'];
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
