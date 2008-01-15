<?php
/**
 * @version $Id: verotel.php
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

class processor_ideal_advanced extends URLprocessor
{
	function info()
	{
		$i = array();
		$i['longname'] = _CFG_IDEAL_ADVANCED_LONGNAME;
		$i['statement'] = _CFG_IDEAL_ADVANCED_STATEMENT;
		$i['description'] = _CFG_IDEAL_ADVANCED_DESCRIPTION;
		$i['currencies'] = 'EUR';
		$i['languages'] = 'NL';
		$i['cc_list'] = 'visa,mastercard,discover,americanexpress,echeck';
		$info['recurring'] = 0;

		return $i;
	}

	function settings()
	{
		$s = array();
		$s['merchantid']	= "merchantid";
		$s['testmode']		= 0;
		$s['testmodestage']	= 1;
		$s['subid']			= "0";
		$s['language']		= "NL";
		$s['key']			= "key";
		$s['description']	= sprintf( _CFG_PROCESSOR_ITEM_NAME_DEFAULT, '[[cms_live_site]]', '[[user_name]]', '[[user_username]]' );

		return $s;
	}

	function backend_settings( $cfg )
	{
		$s = array();
		$s['merchantid']	= array( 'inputC' );
		$s['testmode']		= array( 'list_yesno' );
		$s['testmodestage']	= array( 'inputC' );
		$s['subid']			= array( 'inputC' );
		$s['language']		= array( 'list_language' );
		$s['key']			= array( 'inputC' );
		$s['description']	= array( 'inputE' );

		$rewriteswitches	= array( 'cms', 'user', 'expiration', 'subscription', 'plan' );
        $s['rewriteInfo']	= array( 'fieldset', _AEC_MI_REWRITING_INFO, AECToolbox::rewriteEngineInfo( $rewriteswitches ) );
		return $s;
	}

	function CustomPlanParams()
	{
		$p = array();
		$p['verotel_product']	= array( 'inputC' );

		return $p;
	}

	function createGatewayLink( $int_var, $cfg, $metaUser, $new_subscription )
	{
		if ( $cfg['testmode'] ) {
			$var['post_url']		= "https://ideal.secure-ing.com";
		} else {
			$var['post_url']		= "https://ideal.secure-ing.com";
		}

		$var['merchantID']			= $cfg['merchantid'];
		$var['subID']				= $cfg['subid'];
		$var['purchaseID']			= substr( $int_var['invoice'], 1 );

		if ( $cfg['testmode'] ) {
			$var['amount']			= max( 1, min( 7, (int) $cfg['testmodestage'] ) ) . '.00';
		} else {
			$var['amount']			= $int_var['amount'];
		}


		$var['currency']			= $cfg['currency'];
		$var['language']			= strtolower( $cfg['language'] );
		$var['description']			= substr( $cfg['description'], 0, 32);
		$var['itemNumber1']			= $metaUser->userid;
		$var['itemDescription1']	= substr( $cfg['description'], 0, 32);
		$var['itemQuantity1']		= 1;
		$var['itemPrice1']			= $int_var['amount'];
		$var['paymentType']			= 'ideal';
		$var['validUntil']			= $cfg['merchantid'];

		$shastring = $cfg['key'].$var['merchantID'].$var['subID'].$var['amount'].$var['purchaseID'].$var['paymentType'].$var['validUntil']
						.$var['itemNumber1'].$var['itemDescription1'].$var['itemQuantity1'].$var['itemPrice1'];

		$shastring = str_replace( " ", "", $shastring );
		$shastring = str_replace( "\t", "", $shastring );
		$shastring = str_replace( "\n", "", $shastring );
		$shastring = str_replace( "&amp;", "&", $shastring );
		$shastring = str_replace( "&lt;", "<", $shastring );
		$shastring = str_replace( "&gt;", ">", $shastring );
		$shastring = str_replace( "&quot;", "\"", $shastring );

		$shasign = sha1($shastring);

		$var['hash']				= $shasign;
		$var['urlSuccess']			= AECToolbox::deadsureURL( '/index.php?option=com_acctexp&amp;task=thanks' );
		$var['urlCancel']			= AECToolbox::deadsureURL( '/index.php?option=com_acctexp&amp;task=cancel' );
		$var['urlError']			= AECToolbox::deadsureURL( '/index.php?option=com_acctexp&amp;task=cancel' );
		$var['urlService']			= AECToolbox::deadsureURL( '/index.php' );

		return $var;
	}

	function parseNotification( $post, $cfg )
	{

		$response = array();
		$response['invoice'] = '';

		return $response;
	}

	function validateNotification( $response, $post, $cfg, $invoice )
	{
		return $response;
	}

}

?>
