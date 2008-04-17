<?php
/**
 * @version $Id: ideal_advanced.php
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

class processor_ideal_advanced extends XMLprocessor
{
	function info()
	{
		$i = array();
		$i['longname'] = _CFG_IDEAL_ADVANCED_LONGNAME;
		$i['statement'] = _CFG_IDEAL_ADVANCED_STATEMENT;
		$i['description'] = _CFG_IDEAL_ADVANCED_DESCRIPTION;
		$i['currencies'] = 'EUR';
		$i['languages'] = 'NL';
		$i['cc_list'] = 'rabobank,abnamro,ing,postbank,fortis';
		$info['recurring'] = 0;

		return $i;
	}

	function settings()
	{
		$s = array();
		$s['merchantid']	= "merchantid";
		$s['testmode']		= 0;
		$s['bank']			= "ING";
		$s['language']		= "NL";
		$s['description']	= sprintf( _CFG_PROCESSOR_ITEM_NAME_DEFAULT, '[[cms_live_site]]', '[[user_name]]', '[[user_username]]' );

		return $s;
	}

	function backend_settings()
	{
		$s = array();
		$s['merchantid']	= array( 'inputC' );
		$s['testmode']		= array( 'list_yesno' );
		$s['bank']			= array( 'list' );
		$s['language']		= array( 'list_language' );
		$s['description']	= array( 'inputE' );

		$banks = array( 'ING', 'POSTBANK', 'RABOBANK', 'ABNAMRO' );

		$bank_selection = array();
		foreach ( $banks as $name ) {
			$bank_selection[] = mosHTML::makeOption( $name, $name );
		}

		$s['lists']['bank'] = mosHTML::selectList($bank_selection, 'bank', 'size="5"', 'value', 'text', $this->settings['bank'] );

		$rewriteswitches	= array( 'cms', 'user', 'expiration', 'subscription', 'plan' );
		$s = AECToolbox::rewriteEngineInfo( $rewriteswitches, $s );

		return $s;
	}

	function checkoutform( $request )
	{
		global $mosConfig_live_site;

		$var = $this->getCCform();

		$name = explode( ' ', $metaUser->cmsUser->name );

		if ( empty( $name[1] ) ) {
			$name[1] = "";
		}

		$var['params']['billFirstName'] = array( 'inputC', _AEC_AUTHORIZE_ARB_PARAMS_BILLFIRSTNAME_NAME, _AEC_AUTHORIZE_ARB_PARAMS_BILLFIRSTNAME_DESC, $name[0]);
		$var['params']['billLastName'] = array( 'inputC', _AEC_AUTHORIZE_ARB_PARAMS_BILLLASTNAME_NAME, _AEC_AUTHORIZE_ARB_PARAMS_BILLLASTNAME_DESC, $name[1]);

		if ( !empty( $this->settings['promptAddress'] ) ) {
			$var['params']['billAddress'] = array( 'inputC', _AEC_AUTHORIZE_ARB_PARAMS_BILLADDRESS_NAME );
			$var['params']['billCity'] = array( 'inputC', _AEC_AUTHORIZE_ARB_PARAMS_BILLCITY_NAME );
			$var['params']['billState'] = array( 'inputC', _AEC_AUTHORIZE_ARB_PARAMS_BILLSTATE_NAME );
			$var['params']['billZip'] = array( 'inputC', _AEC_AUTHORIZE_ARB_PARAMS_BILLZIP_NAME );
			$var['params']['billCountry'] = array( 'inputC', _AEC_AUTHORIZE_ARB_PARAMS_BILLCOUNTRY_NAME );
		}

		return $var;
	}

	function createRequestXML( $request )
	{
		require_once( dirname(__FILE__) . "/ideal_advanced/ThinMPI.php" );
		require_once( dirname(__FILE__) . "/ideal_advanced/AcquirerTrxRequest.php" );

		$data = & new AcquirerTrxRequest();

		//Set parameters for TransactionRequest
		$data->setIssuerID( $metaUser->userid );
		$data->setPurchaseID( $request->int_var['invoice'] );
		$data->setAmount( $request->int_var['amount']*100 );
		$data->setCurrency( $request->int_var['currency'] );
		$data->setEntranceCode( $this->settings['entrance_code'] );
		$data->setMerchantReturnURL( AECToolbox::deadsureURL( '/index.php?option=com_acctexp&amp;task=ideal_advancednotification' ) );

		return $data;
	}

	function transmitRequestXML( $xml, $request )
	{

		$idealcfg = array();

		$idealcfg['IDEAL_AcquirerPORT'] 		= '443';
		$idealcfg['IDEAL_AcquirerDirPath'] 		= '/ideal/iDeal';
		$idealcfg['IDEAL_AcquirerTransPath']	= '/ideal/iDeal';
		$idealcfg['IDEAL_AcquirerStatPath']		= '/ideal/iDeal';
		$idealcfg['IDEAL_AcquirerDirFile'] 		= '';
		$idealcfg['IDEAL_AcquirerTransFile']	= '';
		$idealcfg['IDEAL_AcquirerStatFile']		= '';

		switch( $this->settings['bank'] ) {
			case 'ING':
				if ( $this->settings['testmode'] ) {
					$idealcfg['IDEAL_AcquirerURL']		= 'ssl://idealtest.secure-ing.com';
					$idealcfg['IDEAL_Certificate0']		= 'test_ing_ideal.cer';
				} else {
					$idealcfg['IDEAL_AcquirerURL']		= 'ssl://ideal.secure-ing.com';
					$idealcfg['IDEAL_Certificate0']		= 'productie_ing_ideal.cer';
				}
				break;
			case 'POSTBANK':
				if ( $this->settings['testmode'] ) {
					$idealcfg['IDEAL_AcquirerURL']		= 'ssl://idealtest.secure-ing.com';
					$idealcfg['IDEAL_Certificate0']		= 'test_postbank_ideal.cer';
				} else {
					$idealcfg['IDEAL_AcquirerURL']		= 'ssl://ideal.secure-ing.com';
					$idealcfg['IDEAL_Certificate0']		= 'productie_postbank_ideal.cer';
				}
				break;
			case 'RABOBANK':
				if ( $this->settings['testmode'] ) {
					$idealcfg['IDEAL_AcquirerURL']		= 'ssl://idealtest.rabobank.nl';
					$idealcfg['IDEAL_Certificate0']		= 'test_rabobank_ideal.cer';
				} else {
					$idealcfg['IDEAL_AcquirerURL']		= 'ssl://ideal.rabobank.nl';
					$idealcfg['IDEAL_Certificate0']		= 'productie_rabobank_ideal.cer';
				}
				break;
			case 'ABNAMRO':
				if ( $this->settings['testmode'] ) {
					$idealcfg['IDEAL_AcquirerURL']		= 'ssl://idealm-et.abnamro.nl';
					$idealcfg['IDEAL_Certificate0']		= 'test_abnamro_ideal.cer';
				} else {
					$idealcfg['IDEAL_AcquirerURL']		= 'ssl://idealm.abnamro.nl';
					$idealcfg['IDEAL_Certificate0']		= 'productie_abnamro_ideal.cer';
				}

				$idealcfg['IDEAL_AcquirerDirPath'] 		= '/nl/issuerInformation/';
				$idealcfg['IDEAL_AcquirerTransPath']	= '/nl/acquirerTrxRegistration/';
				$idealcfg['IDEAL_AcquirerStatPath']		= '/nl/acquirerStatusInquiry/';
				$idealcfg['IDEAL_AcquirerDirFile'] 		= 'getIssuerInformation.xml';
				$idealcfg['IDEAL_AcquirerTransFile']	= 'getAcquirerTrxRegistration.xml';
				$idealcfg['IDEAL_AcquirerStatFile']		= 'getAcquirerStatusInquiry.xml';
				break;
		}

		//Create ThinMPI instance
		$rule = new ThinMPI( $idealcfg );
		$result = new AcquirerTrxResponse();

		//Process Request
		$result = $rule->ProcessRequest( $xml );

		if( $result->isOK() ) {
			$transactionID = $result->getTransactionID();

			$ISSURL = $result->getIssuerAuthenticationURL();
			$ISSURL = html_entity_decode($ISSURL);

			//Redirect the browser to the issuer URL
			header("Location: $ISSURL");
			exit();
		} else {
			$return['error'] = $result->getErrorMessage();
		}

		return $return;
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
