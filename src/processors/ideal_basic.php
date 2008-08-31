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

class processor_ideal_basic extends URLprocessor
{
	function info()
	{
		$i = array();
		$i['longname']		= _CFG_IDEAL_BASIC_LONGNAME;
		$i['statement']		= _CFG_IDEAL_BASIC_STATEMENT;
		$i['description']	= _CFG_IDEAL_BASIC_DESCRIPTION;
		$i['currencies']	= 'EUR';
		$i['languages']		= 'NL';
		$i['cc_list']		= 'rabobank,abnamro,ing,postbank,fortis';
		$info['recurring']	= 0;

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
		$s['customparams']		= "";

		return $s;
	}

	function backend_settings()
	{
		$s = array();
		$s['merchantid']	= array( 'inputC' );
		$s['testmode']		= array( 'list_yesno' );
		$s['testmodestage']	= array( 'inputC' );
		$s['subid']			= array( 'inputC' );
		$s['language']		= array( 'list_language' );
		$s['key']			= array( 'inputC' );
		$s['description']	= array( 'inputE' );
		$s['customparams']	= array( 'inputD' );

		return $s;
	}

	function createGatewayLink( $request )
	{
		if ( $this->settings['testmode'] ) {
			$var['post_url']		= "https://ideal.secure-ing.com";
		} else {
			$var['post_url']		= "https://ideal.secure-ing.com";
		}

		$var['merchantID']			= $this->settings['merchantid'];
		$var['subID']				= $this->settings['subid'];
		$var['purchaseID']			= substr( $request->int_var['invoice'], 1 );

		if ( $this->settings['testmode'] ) {
			$var['amount']			= max( 1, min( 7, (int) $this->settings['testmodestage'] ) ) . '.00';
		} else {
			$var['amount']			= $request->int_var['amount'];
		}


		$var['currency']			= $this->settings['currency'];
		$var['language']			= strtolower( $this->settings['language'] );
		$var['description']			= substr( $this->settings['description'], 0, 32);
		$var['itemNumber1']			= $request->metaUser->userid;
		$var['itemDescription1']	= substr( $this->settings['description'], 0, 32);
		$var['itemQuantity1']		= 1;
		$var['itemPrice1']			= $request->int_var['amount'];
		$var['paymentType']			= 'ideal';
		$var['validUntil']			= $this->settings['merchantid'];

		$shastring = $this->settings['key'].$var['merchantID'].$var['subID'].$var['amount'].$var['purchaseID'].$var['paymentType'].$var['validUntil']
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

	function parseNotification( $post )
	{

		$response = array();
		$response['invoice'] = '';

		return $response;
	}

	function validateNotification( $response, $post, $invoice )
	{
		return $response;
	}

}

?>
