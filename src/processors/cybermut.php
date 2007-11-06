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
		$settings['testmode']	= 0;
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
		$settings['testmode']	= array( 'list_yesno' );
		$settings['tpe']		= array( 'inputC' );
		$settings['ver']		= array( 'inputC' );
		$settings['soc']		= array( 'inputC' );
		$settings['pass']		= array( 'inputC' );
		$settings['key']		= array( 'inputC' );
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
			$var['post_url'] = "https://www.creditmutuel.fr/telepaiement/test/paiement.cgi";
		} else {
			$var['post_url'] = "https://" . $servers[$cfg['server']] . "/paiement.cgi";
		}

		$var['version']			= $cfg['ver'];
		$var['TPE']				= $cfg['tpe'];
		$var['date']			= date( "d/m/Y:H:i:s" );
		$var['montant']			= $int_var['amount'] . $int_var['currency'];
		$var['reference']		= $int_var['invoice'];

		$HMAC = $var['TPE']."*".$var['date']."*".$int_var['amount'].$int_var['currency']."*".$var['reference']."*".$var['comment']."*".$var['version']."*".$var['lgue']."*".$cfg['soc']."*";

		//$var['MAC']				= "V1.03.sha1.php4--CtlHmac-" . $cfg['ver'] . "-[" . $cfg['tpe'] . "]-" . $this->CMCIC_hmac( $cfg, $HMAC );
		$var['MAC']				= $this->CMCIC_hmac( $cfg, $HMAC );
		$var['url_retour']		= AECToolbox::deadsureURL( '/index.php?option=com_acctexp&amp;task=thanks' );
		$var['url_retour_ok']	= AECToolbox::deadsureURL( '/index.php?option=com_acctexp&amp;task=cybermutnotification' );
		$var['url_retour_err']	= AECToolbox::deadsureURL( '/index.php?option=com_acctexp&amp;task=cancel' );
		$var['lgue']			= $cfg['language'];
		$var['texte-libre']		= AECToolbox::rewriteEngine( $cfg['item_name'], $metaUser, $new_subscription );

		/*$var['retourPLUS']		= $int_var['return_url'];
		$var['societe']			= $cfg['key'];*/

		return $var;
	}
/*<form action="https://paiement.creditmutuel.fr/paiement.cgi" method="post">
<input type="hidden" name="version" value="1.2" />
<input type="hidden" name="TPE" value="0044917" />
<input type="hidden" name="date" value="05/11/19107:23:41:59" />
<input type="hidden" name="montant" value="86.11EUR" />
<input type="hidden" name="reference" value="100004090324" />
<input type="hidden" name="MAC" value="3e65627bcc58eab4098af2ffc71ff153e1a60b7a" />
<input type="hidden" name="url_retour" value="https://www.gandi.net" />
<input type="hidden" name="url_retour_ok" value="https://www.gandi.net/domain/renew/payment/done" />
<input type="hidden" name="url_retour_err" value="https://www.gandi.net/domain/renew/payment/failed" />
<input type="hidden" name="lgue" value="anglais" /> <input type="hidden" name="societe" value="gandi" />
<input type="hidden" name="texte-libre" value="Domain(s) purchased" />
<input type="submit" value="Accept" class="button" />
</form>*/
	function parseNotification( $post, $cfg )
	{
		$response = array();
		$response['invoice'] = $post['order_id'];
		$response['valid'] = strcmp( $post['retour'], 'ok' ) ? true : false ;

		mosMail( 'dungdt@gmail.com', 'Duong Tien Dung', 'skore@skore.de', 'cybermut debug', $this->obsafe_print_r( $_REQUEST, true) );

		return $response;
	}

	function obsafe_print_r($var, $return = false, $html = false, $level = 0) {
	    $spaces = "";
	    $space = $html ? "&nbsp;" : " ";
	    $newline = $html ? "<br />" : "\n";
	    for ($i = 1; $i <= 6; $i++) {
	        $spaces .= $space;
	    }
	    $tabs = $spaces;
	    for ($i = 1; $i <= $level; $i++) {
	        $tabs .= $spaces;
	    }
	    if (is_array($var)) {
	        $title = "Array";
	    } elseif (is_object($var)) {
	        $title = get_class($var)." Object";
	    }
	    $output = $title . $newline . $newline;
	    foreach($var as $key => $value) {
	        if (is_array($value) || is_object($value)) {
	            $level++;
	            $value = obsafe_print_r($value, true, $html, $level);
	            $level--;
	        }
	        $output .= $tabs . "[" . $key . "] => " . $value . $newline;
	    }
	    if ($return) return $output;
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
