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

class processor_verotel extends POSTprocessor
{
	function info()
	{
		$i = array();
		$i['longname'] = _AEC_VEROTEL_LONGNAME;
		$i['statement'] = _AEC_VEROTEL_STATEMENT;
		$i['description'] = _AEC_VEROTEL_DESCRIPTION;
		$i['currencies'] = 'USD';
		$i['languages'] = 'AU,DE,FR,IT,GB,ES,US';
		$i['cc_list'] = 'visa,mastercard,discover,americanexpress,echeck';

		return $i;
	}

	function settings()
	{
		$s = array();
		$s['merchantid']	= "merchantid";
		$s['siteid']		= "siteid";
		$s['secretcode']	= "secretcode";

		return $s;
	}

	function backend_settings()
	{
		$s = array();
		$s['merchantid']	= array( 'inputC' );
		$s['siteid']		= array( 'inputC' );
		$s['secretcode']	= array( 'inputC' );
		$s['custom_name']	= array( 'inputE' );

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

	function createGatewayLink( $int_var, $metaUser, $cfg, $new_subscription )
	{
		// Payment Plans are required to have a productid assigned
		if ( empty( $int_var['planparams']['verotel_product'] ) ) {
			$product = $cfg['siteid'];
		} else {
			$product = $int_var['planparams']['verotel_product'];
		}

        $var = array(
			'post_url'	=> "https://secure.verotel.com/cgi-bin/vtjp.pl?",
			'verotel_id'    => $cfg['merchant_id'],
            'verotel_product' => $product,
            'verotel_website'     => $product,
            'verotel_usercode'  => $metaUser->cmsUser->username,
            'verotel_custom1'      => $int_var['invoice'],
            'verotel_custom2'      => AECToolbox::rewriteEngine( $cfg['custom_name'], $metaUser, $new_subscription ),
        );

		return $var;
	}

	function parseNotification( $post, $cfg )
	{
		$vercode = $post['vercode'];
		$res = split(":", $vercode);

		$action     = $post['trn'];
		$amount     = $post['amount'];
		$payment_id = intval($post['custom1']);
		$pnref 	    = $post['trn_id'];
		$secret = $res[2];


		$response = array();
		$response['invoice'] = $post['custom1'];
		$response['valid'] = 0;

		switch ( $action ) {
			case 'add':
				$response['amount_paid'] = $post['amount'];
				break;
			case 'cancel':
				break;
			case 'rebill':
				$response['amount_paid'] = $post['amount'];
				break;
		}


		return $response;
	}

}

?>
