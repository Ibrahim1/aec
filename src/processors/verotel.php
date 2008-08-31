<?php
/**
 * @version $Id: verotel.php
 * @package AEC - Account Control Expiration - Membership Manager
 * @subpackage Payment Processors
 * @author David Deutsch <skore@skore.de> & Team AEC - http://www.globalnerd.org 
 * @copyright 2006-2008 Copyright (C) David Deutsch
 * @license GNU/GPL v.2 http://www.gnu.org/licenses/old-licenses/gpl-2.0.html or, at your option, any later version
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

class processor_verotel extends URLprocessor
{
	function info()
	{
		$i = array();
		$i['longname']				= _CFG_VEROTEL_LONGNAME;
		$i['statement']				= _CFG_VEROTEL_STATEMENT;
		$i['description']			= _CFG_VEROTEL_DESCRIPTION;
		$i['currencies']			= 'USD';
		$i['languages']				= 'AU,DE,FR,IT,GB,ES,US';
		$i['cc_list']				= 'visa,mastercard,discover,americanexpress,echeck';
		$i['notify_trail_thanks']	= 1;

		return $i;
	}

	function settings()
	{
		$s = array();
		$s['merchantid']		= "merchantid";
		$s['resellerid']		= "resellerid";
		$s['siteid']			= "siteid";
		$s['secretcode']		= "secretcode";
		$s['use_ticketsclub']	= 1;
		$s['customparams']		= "";

		return $s;
	}

	function backend_settings()
	{
		$s = array();

		$s['merchantid']		= array( 'inputC' );
		$s['resellerid']		= array( 'inputC' );
		$s['siteid']			= array( 'inputC' );
		$s['secretcode']		= array( 'inputC' );
		$s['use_ticketsclub']	= array( 'list_yesno' );
		$s['customparams']		= array( 'inputD' );

		return $s;
	}

	function CustomPlanParams()
	{
		$p = array();

		$p['verotel_product']	= array( 'inputC' );
		$p['recurring']			= array( 'list_yesno' );

		return $p;
	}

	function createGatewayLink( $request )
	{
		// Payment Plans are required to have a productid assigned
		if ( empty( $request->int_var['planparams']['verotel_product'] ) ) {
			$product = $this->settings['siteid'];
		} else {
			$product = $request->int_var['planparams']['verotel_product'];
		}

		if ( $this->settings['use_ticketsclub'] ) {
			$var['post_url']			= "https://secure.ticketsclub.com/cgi-bin/boxoffice-one.tc?";
			$var['fldcustomerid']		= $this->settings['merchantid'];
			$var['fldwebsitenr']		= $this->settings['siteid'];
			$var['tc_usercode']			= $request->metaUser->cmsUser->username;
			$var['tc_passcode']			= "xxxxxxxx";
			$var['tc_custom1']			= $request->int_var['invoice'];
			$var['tc_custom2']			= $request->metaUser->cmsUser->username;
		} else {
			$var['post_url']			= "https://secure.verotel.com/cgi-bin/vtjp.pl?";
			$var['verotel_id']			= $this->settings['merchantid'];
			$var['verotel_product']		= $product;
			$var['verotel_website']		= $this->settings['siteid'];
			$var['verotel_usercode']	= $request->metaUser->cmsUser->username;
			$var['verotel_passcode']	= "xxxxxxxx";
			$var['verotel_custom1']		= $request->int_var['invoice'];
		}

		return $var;
	}

	function parseNotification( $post )
	{
		$res = explode(":", aecGetParam('vercode'));

		$secret		= $res[2];
		$action     = $res[3];
		$amount     = $res[4];
		$payment_id = $res[5];
		$pnref 	    = $res[6];

		$response = array();
		$response['invoice'] = $payment_id;

		return $response;
	}

	function validateNotification( $response, $post, $invoice )
	{
		$res = explode(":", aecGetParam('vercode'));

		if( $this->settings['secretcode'] == $res[2] ) {
			$response['valid'] = 1;
		} else {
			$response['valid'] = 0;
			$response['pending_reason'] = 'INVALID SECRET WORD, provided: ' . $res[2];
		}

		switch ( $res[3] ) {
			case 'add':
				$response['valid'] = 1;
				if ( !empty( $post['planparams']['recurring'] ) ) {
					$response['multiplicator'] = 'lifetime';
				}
				break;
			case 'cancel':
				$response['cancel'] = 1;
				$response['valid'] = 0;
				break;
			case 'delete':
				$response['delete'] = 1;
				$response['valid'] = 0;
				break;
			case 'rebill':
				$response['amount_paid'] = $res[4];
				break;
		}

		return $response;
	}

}

?>
