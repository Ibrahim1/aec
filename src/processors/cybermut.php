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
		$info['currencies'] = "EUR";
		$info['languages'] = "AU,DE,FR,IT,GB,ES,US";
		$info['cc_list'] = "visa,mastercard,discover,americanexpress,echeck,giropay";

		return $info;
	}

	function settings()
	{
		$settings = array();
		$settings['tpe']		= '7654321';
		$settings['ver']		= '1.2open';
		$settings['soc']		= 'doNot';
		$settings['key']		= '000102030405060708090A0B0C0D0E0F10111213';
		$settings['language']	= 'FR';
		$settings['server']		= 0;
		$settings['item_name']	= sprintf( _CFG_PROCESSOR_ITEM_NAME_DEFAULT, '[[cms_live_site]]', '[[user_name]]', '[[user_username]]' );

		return $settings;
	}


	function backend_settings( $cfg )
	{
		$settings = array();
		$settings['tpe']		= array( 'inputC' );
		$settings['ver']		= array( 'inputC' );
		$settings['soc']		= array( 'inputC' );
		$settings['pass']		= array( 'inputC' );
		$settings['key']		= array( 'inputC' );
		$settings['server']		= array( 'list' );
		$settings['language']	= array( 'list_language' );
		$settings['item_name']	= array( 'inputE' );

		$servers = array( 'paiement.creditmutuel.fr/VAD', 'ssl.paiement.cic-banques.fr', 'ssl.paiement.banque-obc.fr', 'paiement.caixanet.fr', 'www.creditmutuel.fr/telepaiement' );

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

		$servers = array( 'paiement.creditmutuel.fr/VAD', 'ssl.paiement.cic-banques.fr', 'ssl.paiement.banque-obc.fr', 'paiement.caixanet.fr', 'www.creditmutuel.fr/telepaiement' );

		$var['post_url']		= "https://" . $servers[$cfg['server']] . "/paiement.cgi";
		$var['version']			= $cfg['ver'];
		$var['TPE']				= $cfg['tpe'];
		$var['date']			= date( "d/m/Y:H:i:s" );
		$var['montant']			= $int_var['amount'] . $int_var['currency'];
		$var['reference']		= $int_var['invoice'];

		$HMAC = $var['TPE']."*".$var['date']."*".$int_var['amount'].$int_var['currency']."*".$var['reference']."*".$var['comment']."*".$var['version']."*".$var['lgue']."*".$cfg['soc']."*";

		$var['MAC']				= "V1.03.sha1.php4--CtlHmac-" . $cfg['ver'] . "-[" . $cfg['tpe'] . "]-" . $this->CMCIC_hmac( $cfg, $HMAC );
		$var['url_retour']		= AECToolbox::deadsureURL( '/index.php?option=com_acctexp&amp;task=thanks' );
		$var['url_retour_ok']	= AECToolbox::deadsureURL( '/index.php?option=com_acctexp&amp;task=cybermutnotification' );
		$var['url_retour_err']	= AECToolbox::deadsureURL( '/index.php?option=com_acctexp&amp;task=cancel' );
		$var['lgue']			= $cfg['language'];

		$var['societe']			= $cfg['key'];
		$var['texte-libre']		= AECToolbox::rewriteEngine( $cfg['item_name'], $metaUser, $new_subscription );

		return $var;
	}

	function parseNotification( $post, $cfg )
	{
		$response = array();
		$response['invoice'] = "";
		$response['valid'] = 0;

		return $response;
	}

	function CMCIC_hmac( $cfg, $data="")
	{
		$k1 = pack( "H*", sha1( $cfg['pass'] ) );
		$l1 = strlen( $k1 );
		$k2 = pack( "H*", $cfg['key'] );
		$l2 = strlen( $k2 );

		if ( $l1 > $l2 ) {
			$k2 = str_pad( $k2, $l1, chr(0x00) );
		} elseif ( $l2 > $l1 ) {
			$k1 = str_pad( $k1, $l2, chr(0x00) );
		}

		if ( $data == "" ) {
			$d = "CtlHmac" . $cfg['ver'] . $cfg['tpe'];
		} else {
			$d = $data;
		}

		return strtolower( $this->hmac( $k1 ^ $k2, $d ) );
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
