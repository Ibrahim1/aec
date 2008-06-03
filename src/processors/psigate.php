<?php

/**
 * PSIGate process interface
 *
 * @copyright 2007 Ben Ingram
 * @license http://www.gnu.org/copyleft/gpl.html. GNU Public License
 * @version $Revision: 1.0d $
 *   Revision a  - changed flag to process instead of Auth.
 *   Revision b  - Fixed duplicate order ID problem
 *   Revision c  - small code cleanups
 *   Revision d  - more security checks in return from processor
 *   Revision e  - more detail in transaction history
 *   Revision f  - more detail in debug
 *  * *  *
 * @author Ben Ingram <beningram@hotmail.com>
 **
 *********************************************************************
 ** If you find this useful, why not make my day and send a donation to me by PayPal
 ** My account is beningram@hotmail.com - thanks and happy trading
 *********************************************************************
 **/
//
// Copyright (C) 2007 Ben Ingram
// All rights reserved.
// This source file works with the Account Expiration Control Component, a  Joomla
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

class processor_psigate extends POSTprocessor
{
	function info()
	{
		$info = array();
		$info['name'] = "psigate";
		$info['longname'] = "psigate";
		$info['statement'] = "Make payments with PSIGate!";
		$info['description'] = "PSIGate";
		$info['cc_list'] = "visa,mastercard,discover,echeck,jcb";
		$info['currencies'] = "USD,CAD";
		$info['recurring'] = 0;
		$info['notify_trail_thanks'] = 1;

		return $info;
	}

	function settings()
	{
		$settings = array();
		$settings['testmode']		= 0;
		$settings['StoreKey']		= "StoreKey";
		$settings['secretWord']		= "Secret Word";
		$settings['customparams']	= "";

		return $settings;
	}

	function backend_settings()
	{
		$settings = array();

		$settings['testmode']		= array( "list_yesno", "Test Mode", "Operate in PSIGate TEST mode" );
		$settings['StoreKey']		= array( "inputC","Store Key","Your Alphanumeric ID assigned by PSIGate" );
		$settings['secretWord']		= array( "inputC","Secret Word","Used to encrypt and protect transactions" );
		$settings['customparams']	= array( 'inputD' );
		return $settings;
	}

	function createGatewayLink( $request )
	{
		global $mosConfig_live_site;
		if ( $this->settings['testmode'] ) {
			$var['post_url']	= "https://dev.psigate.com/HTMLPost/HTMLMessenger";
		} else {
			$var['post_url']	= "https://checkout.psigate.com/HTMLPost/HTMLMessenger";
		}

		$var['StoreKey']		= $this->settings['StoreKey'];
		$var['CustomerRefNo']	= $request->int_var['invoice'];
		//$var['OrderID']			= md5($this->settings['secretWord'] . $request->int_var['amount']);
		$var['SubTotal']		= $request->int_var['amount'];
		$var['PaymentType']		= "CC";
		$var['ThanksURL']		= AECToolbox::deadsureURL( "/index.php?option=com_acctexp&amp;task=psigatenotification" );
		$var['NoThanksURL']		= AECToolbox::deadsureURL( "/index.php?option=com_acctexp&amp;task=psigatenotification" );
		$var['CardAction']		= "0";
		$var['test123']			= "tester123";
		return $var;
	}


	function parseNotification ( $post )
	{
		if (isset(aecGetParam('ReturnCode')) && aecGetParam('ReturnCode') != "") {
			$ReturnCode = aecGetParam('ReturnCode');
		} else {
			$ReturnCode = "NA";
		}
		if (isset(aecGetParam('CustomerRefNo')) && aecGetParam('CustomerRefNo') != "") {
			$CustomerRefNo = aecGetParam('CustomerRefNo');
		} else {
			$CustomerRefNo = "NA";
		}
		if (isset(aecGetParam('TransRefNumber')) && aecGetParam('TransRefNumber') != "") {
			$TransRefNumber = aecGetParam('TransRefNumber');
		} else {
			$TransRefNumber = "NA";
		}
		if (isset(aecGetParam('Approved')) && aecGetParam('Approved') != "") {
			$Approved = aecGetParam('Approved');
		} else {
			$Approved = "NA";
		}
		if (isset(aecGetParam('ErrMsg')) && aecGetParam('CustomerRefNo') != "") {
			$ErrMsg = aecGetParam('ErrMsg');
		} else {
			$ErrMsg = "NA";
		}
		if (isset(aecGetParam('FullTotal')) && aecGetParam('FullTotal') != "") {
			$FullTotal = aecGetParam('FullTotal');
		} else {
			$FullTotal = "NA";
		}
		if (isset(aecGetParam('CardNumber')) && aecGetParam('CardNumber') != "") {
			$CardNumber = aecGetParam('CardNumber');
		} else {
			$CardNumber = "NA";
		}
		if (isset(aecGetParam('OrderID')) && aecGetParam('OrderID') != "") {
			$OrderID = aecGetParam('OrderID');
		} else {
			$OrderID = "NA";
		}

		$checksum = md5($OrderID . $FullTotal);

		$response = array();
		$response['TransRefNumber']	= $TransRefNumber;
		$response['Approved']		= $Approved;
		$response['FullTotal']		= $FullTotal;
		$response['CardNumber']		= $CardNumber;
		$response['OrderID']		= $OrderID;
		$response['invoice']		= $CustomerRefNo;


		$validate			= md5($this->settings['secretWord'] . $FullTotal);
		$response['valid']	= (strcmp($validate, $checksum) == 0);

		if ( $response['valid'] = 1 ){
			if ( substr( $ReturnCode, 0, 1 ) == "Y" ) {
				print_r("<b>Thankyou! - Your Card was approved</b><br/>");
				print_r("</br>");
				print_r("<b>Card No:</b>". $CardNumber . "<br/>");
				print_r("<b>Total Charged:</b>". $FullTotal . "<br/>");
				print_r("<br/>");
			} else {
				$response['valid']			= 0;
				$response['pending']		= 1;
				$response['pending_reason']	= $ErrMsg;
				print_r("<b>Transaction Declined <br/>Reason: </b>" .$ErrMsg . "<br/>");
			}
		} else  {
			$response['valid'] = 0;
			$response['pending']=1;
			$response['pending_reason']=$ErrMsg;
			print_r("<b>Transaction Declined (cs)<br/>Reason: </b>" .$ErrMsg . "<br/>");

		}

		print_r("<b>TransRefNumber:</b>". $response['TransRefNumber'] . "<br/>");
		print_r("<b>Invoice:</b>". $response['invoice'] . "<br/>");
		print_r("<b>OrderID:</b>". $response['OrderID']. "<br/>");

		return $response;
	}
}
?>