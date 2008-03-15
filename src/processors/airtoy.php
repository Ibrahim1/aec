<?php
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

class processor_airtoy extends XMLprocessor
{
	function info()
	{
		$info = array();
		$info['name'] = 'airtoy';
		$info['longname'] = _CFG_AIRTOY_LONGNAME;
		$info['statement'] = _CFG_AIRTOY_STATEMENT;
		$info['description'] = _CFG_AIRTOY_DESCRIPTION;
		$info['currencies'] = 'EUR';
		$info['cc_list'] = "";
		$info['recurring'] = 0;

		return $info;
	}

	function settings()
	{
		$settings = array();
		$settings['testmode']			= 0;
		$settings['phone_number']		= "222222";
		$settings['response']			= "";

		return $settings;
	}

	function backend_settings( $cfg )
	{
		$settings = array();
		$settings['testmode']			= array("list_yesno");
		$settings['phone_number']		= array("inputC");

		$settings['response']			= array("inputE");
 		$rewriteswitches 				= array("cms", "user", "subscription", "plan", "invoice");
		$settings = AECToolbox::rewriteEngineInfo( $rewriteswitches, $settings );

		return $settings;
	}

	function CustomPlanParams()
	{
		$p = array();
		$p['smscode_prefix']	= array( 'inputC' );

		return $p;
	}

	function checkoutform( $int_var, $cfg, $metaUser, $new_subscription )
	{
		global $mosConfig_live_site, $database;

		$invoice = new Invoice( $database );
		$invoice->loadInvoiceNumber( $int_var['invoice'] );

		$code = $int_var['planparams']['smscode_prefix'] . ' ' . $invoice->id;

		$var['params']['explain'] = array( 'p', _AEC_AIRTOY_PARAMS_EXPLAIN_NAME, sprintf( _AEC_AIRTOY_PARAMS_EXPLAIN_DESC, $code, $cfg['phone_number'] ) );
		$var['params']['smscode'] = array( 'inputC', _AEC_AIRTOY_PARAMS_SMSCODE_NAME, _AEC_AIRTOY_PARAMS_SMSCODE_DESC);

		return $var;
	}

	function createRequestXML( $int_var, $cfg, $metaUser, $new_subscription, $invoice )
	{
		return true;
	}

	function transmitRequestXML( $xml, $int_var, $settings, $metaUser, $new_subscription, $invoice )
	{
		$return['valid'] = false;

		if ( empty( $int_var['params']['smscode'] ) ) {
			$return['error'] = _AEC_AIRTOY_ERROR_NOCODE;
			return $return;
		}

		$invoiceparams = $invoice->getParams();

		$compare = ( strcmp( $int_var['params']['smscode'], $invoiceparams['airtoy_smscode'] ) === 0 );

		if ( $compare ) {
			$return['valid'] = true;
		} else {
			$return['error'] = _AEC_AIRTOY_CODE_WRONG;
		}


		if ( $settings['testmode'] ) {
			$url = "http://82.113.44.50/";
		} else {
			$url = "http://195.47.87.164/";
		}

		if ( $return['valid'] ) {
			$resp = "OK;" . AECToolbox::rewriteEngine( $settings['response'], $metaUser, $new_subscription, $invoice ) . ";on;;";
			$response = $this->transmitRequest( $url, '', $resp, 443 );
		}

		return $return;
	}


	function parseNotification( $post, $cfg )
	{
		global $database;

		$smscode = $_GET['sms'];

		$sms = explode( ' ', $smscode );

		$invoice = new Invoice( $database );
		$invoice->load( $sms[1] );

		if ( $invoice->id ) {
			$returncode = rand( 111111, 999999 );

			$invoice->addParams( array( 'airtoy_smscode' => $returncode ) );
			$invoice->check();
			$invoice->store();


			if ( $cfg['testmode'] ) {
				$url = "http://82.113.44.50/";
			} else {
				$url = "http://195.47.87.164/";
			}

			$resp = "OK;" . $returncode . ";1";
			$response = $this->transmitRequest( $url, '', $resp, 443 );

			echo $returncode;
		}
		exit;
	}

}
?>