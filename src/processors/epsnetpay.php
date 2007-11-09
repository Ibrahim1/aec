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

class processor_epsnetpay extends POSTprocessor
{
	function info()
	{
		$info = array();
		$info['name'] = "epsnetpay";
		$info['longname'] = "epsNetpay";
		$info['statement'] = "Bezahlen sie mit epsNetpay!";
		$info['description'] = _DESCRIPTION_EPSNETPAY;
		$info['cc_list'] = "visa,mastercard,discover,americanexpress,echeck,giropay";
		$info['recurring'] = 0;

		return $info;
	}

	function settings()
	{
		$settings = array();

		$banks = array();
		$banks[] = "BANK AUSTRIA CREDITANSTALT";
		$banks[] = "BAWAG P.S.K. GRUPPE";
		$banks[] = "ERSTE BANK und SPARKASSEN";
		$banks[] = "RAIFFEISEN Bankengruppe";
		$banks[] = "Bankhaus Carl Sp&auml;ngler & Co. AG";
		$banks[] = "VOLKSBANKEN Gruppe";
		$banks[] = "HYPO Banken - Allgemeines Rechenzentrum";
		$banks[] = "HYPO Banken - Raiffeisen Rechenzentrum";
		$banks[] = "HYPO Banken - Tirol";
		$banks[] = "Nieder&ouml;sterreichische Landesbank";
		$banks[] = "Vorarlberger Landes- und Hypothekenbank";
		$banks[] = "Investkredit Bank";
		$banks[] = "Bank für &Auml;rzte und Freie Berufe";

		$n = 0;
		foreach ($banks as $bankname) {
			$settings['merchantname_' . $n] = $bankname;
			$settings['merchantactive_' . $n] = 0;
			$settings['merchantpin_' . $n] = "merchant pin";
			$settings['merchantid_' . $n] = "merchant id";
			$n++;
		}

		$settings['testmode'] = 0;
		$settings['acceptvok'] = 0;

		return $settings;
	}

	function backend_settings( $cfg )
	{
		$settings = array();
		$settings['testmode'] = array("list_yesno");
		$settings['acceptvok'] = array("list_yesno");

		$vars = $this->settings();
		foreach ($vars as $name => $var) {
			if (strpos($name, "id")) {
				$id = str_replace("merchantid_", "", $name);

				$bankname = $vars["merchantname_" . $id];

				$settings["merchantactive_" . $id] = array("list_yesno", _CFG_EPSNETPAY_ACTIVATE_NAME, _CFG_EPSNETPAY_ACTIVATE_DESC);
				$settings["merchantname_" . $id] = array("inputC", "Name:", $bankname);

				$idfieldname = $bankname . ": " . _CFG_EPSNETPAY_MERCHANTID_NAME;
				$settings[$name] = array("inputC", $idfieldname, ($bankname . ": " . _CFG_EPSNETPAY_MERCHANTID_DESC));
				$pinfieldname = $bankname . ": " . _CFG_EPSNETPAY_MERCHANTPIN_NAME;
				$settings["merchantpin_" . $id] = array("inputC", $pinfieldname, ($bankname . ": " .  _CFG_EPSNETPAY_MERCHANTPIN_DESC));
			}
		}

		return $settings;
	}

	function createGatewayLink( $int_var, $cfg, $metaUser, $new_subscription )
	{
		global $mosConfig_live_site;

		$sapPopStsURL			= $mosConfig_live_site . "/index.php";
		$var['sapInfoVersion']	= "3"; //Current Version
		$var['language']		= "DE"; // Must be german
		$var['sapPopRequestor']	= $cfg['merchantid_' . $int_var['params']['bank_selection']]; // Marchant ID
		$var['sapPopServer']	= "yes"; // Server-to-Server notification
		$var['sapPopStsURL']	= $sapPopStsURL;

		$StsPar = array();
		$StsPar[] = array("option", "com_acctexp");
		$StsPar[] = array("task", "epsnetpaynotification");

		$var['sapPopStsParCnt']	= count($StsPar); // Number of custom values

		$epsparams = "";
		for ( $i=0, $j=1; $i < count($StsPar); $i++, $j++ ) {
			$var['sapPopStsParName' . $j] = $StsPar[$i][0];
			$var['sapPopStsParValue' . $j] = $StsPar[$i][1];
			$epsparams .= $StsPar[$i][0] . $StsPar[$i][1];
		}

		$var['sapPopOkUrl']		= AECToolbox::deadsureURL("/index.php?option=com_acctexp&amp;task=thanks");
		$var['sapPopNokUrl']	= AECToolbox::deadsureURL("/index.php?option=com_acctexp&amp;task=cancel");
		$sapUgawwhg				= "EUR"; // HAS TO BE EUR !!
		$var['sapUgawwhg']		= $sapUgawwhg;
		$sapUkddaten			= $metaUser->cmsUser->id;
		$var['sapUkddaten']		= $sapUkddaten;
		$sapUvwzweck			= $int_var['invoice'];
		$var['sapUvwzweck']		= $sapUvwzweck;
		$sapUzusatz				= $int_var['invoice'];
		$var['sapUzusatz']		= $sapUzusatz;
		$value					= preg_split("/[\.,]/", $int_var['amount']);

		$sapUgawVK = $value[0]; // (only the stuff before the comma)
		$sapUgawNK = $value[1]; // (only the stuff AFTER the comma)
		$var['sapUgawVK']	= $sapUgawVK;
		$var['sapUgawNK']	= $sapUgawNK;

		$fingerprint = $cfg['merchantpin_' . $int_var['params']['bank_selection']].$cfg['merchantid_' . $int_var['params']['bank_selection']].$sapUgawVK.$sapUgawNK.$sapUgawwhg.$sapUvwzweck.$sapUkddaten.$sapUzusatz.$sapPopStsURL.$epsparams;

		$var['sapPopFingerPrint'] = md5($fingerprint); // Fingerprint

		$bank = array();
		// BANK AUSTRIA CREDITANSTALT
		$bank[] = "https://pop.ba-ca.com/servlet/PopBACAEntry";
		// BAWAG P.S.K. GRUPPE
		$bank[] = "https://ebanking.bawag.com/InternetBanking/EPS?d=eps_vlogin";
		// ERSTE BANK und SPARKASSEN
		$bank[] = "https://vendor.netpay.at/webPay/vendorLogin";
		// RAIFFEISEN Bankengruppe
		$bank[] = "https://banking.raiffeisen.at/html/service?smi.lib=payment";
		// Bankhaus Carl Sp&auml;ngler & Co. AG
		$bank[] = "https://www.banking.co.at/appl/ebp/eps/transinit.html?resource=011";
		// VOLKSBANKEN Gruppe
		$bank[] = "https://www.banking.co.at/appl/ebp/eps/transinit.html?resource=101";
		// HYPO Banken - Allgemeines Rechenzentrum
		$bank[] = "https://www.banking.co.at/appl/ebp/eps/transinit.html?resource=015";
		// HYPO Banken - Raiffeisen Rechenzentrum
		$bank[] = "https://banking.hypo.at/html/service?smi.lib=payment";
		// HYPO Banken - Tirol
		$bank[] = "https://www.banking.co.at/appl/ebp/eps/transinit.html?resource=002";
		// Nieder&ouml;sterreichische Landesbank
		$bank[] = "https://www.banking.co.at/appl/ebp/eps/transinit.html?resource=029";
		// Vorarlberger Landes- und Hypothekenbank
		$bank[] = "https://www.banking.co.at/appl/ebp/eps/transinit.html?resource=019";
		// Investkredit Bank
		$bank[] = "https://www.banking.co.at/appl/ebp/eps/transinit.html?resource=109";
		// Bank für &Auml;rzte und Freie Berufe
		$bank[] = "https://www.banking.co.at/appl/ebp/eps/transinit.html?resource=093";

		if ($cfg['testmode']) {
			$var['post_url']	= "https://qvendor.netpay.at/webPay/vendorLogin";
		} else {
			$var['post_url']	= $bank[$int_var['params']['bank_selection']];
		}

		return $var;
	}

	function Params( $cfg, $params )
	{
		$merchantnumber = 0;
		$bank_selection = array();
		while ( isset( $cfg['merchantactive_' . $merchantnumber] ) ) {
			if ($cfg['merchantactive_' . $merchantnumber]) {
				$bank_selection[] = mosHTML::makeOption( $merchantnumber, $cfg['merchantname_' . $merchantnumber] );
			}
			$merchantnumber++;
		}

		if ( empty( $params['bank_selection'] ) ) {
			$selected = 0;
		} else {
			$selected = $params['bank_selection'];
		}

		$var['params']['lists']['bank_selection'] = mosHTML::selectList($bank_selection, 'bank_selection', 'size="5"', 'value', 'text', $selected);
		$var['params']['bank_selection'] = array("list", "Bank Auswahl", "Bitte w&auml;hlen Sie die gew&uuml;nschte Bank aus.");

		return $var;
	}

	function parseNotification( $post, $cfg )
	{
		$invoiceID				= $post['sapPopStsVwzweck'];
		$userid					= $post['sapPopStsRechnr'];

		$sapUgawVK				= $post['sapUgawVK']; // Amount. Value before the comma
		$sapUgawNK				= $post['sapUgawNK']; // Amount. Decimal places

		$response = array();
		$response['invoice'] = $post['sapPopStsVwzweck'];

		return $response;
	}

	function validateNotification( $response, $post, $cfg, $invoice )
	{
		global $mosConfig_live_site;

		$params = $invoice->getParams("params");

		$merchantid = $cfg['merchantid_' . $params['bank_selection']];
		$merchantpin = $cfg['merchantpin_' . $params['bank_selection']];
		$sapPopStsReturnStatus	= $post['sapPopStsReturnStatus']; // Statuscode (OK/NOK/VOK)


		$StsPar = array();
		$StsPar[] = array("option", "com_acctexp");
		$StsPar[] = array("task", "epsnetpaynotification");

		$var['sapPopStsParCnt']	= count($StsPar); // Number of custom values

		$epsparams = "";
		for ( $i=0, $j=1; $i < count($StsPar); $i++, $j++ ) {
			$var['sapPopStsParName' . $j] = $StsPar[$i][0];
			$var['sapPopStsParValue' . $j] = $StsPar[$i][1];
			$epsparams .= $StsPar[$i][0] . $StsPar[$i][1];
		}

		$sapPopStsURL = $mosConfig_live_site . "/index.php";

		$sapPopStsDurchfDatum = isset($post['sapPopStsDurchfDatum']) ? @$post['sapPopStsDurchfDatum'] : "";


		// Check Fingerprint
		if (($fingerprint = md5($post['sapPopStsReturnStatus'].$merchantpin.$merchantid.$post['sapPopStsEmpfname'].$post['sapPopStsEmpfnr'].$post['sapPopStsEmpfblz'].$post['sapPopStsGawVK'].$post['sapPopStsGawNK'].$post['sapPopStsGawWhg'].$post['sapPopStsVwzweck'].$post['sapPopStsRechnr'].$post['sapPopStsZusatz'].$sapPopStsDurchfDatum.$sapPopStsURL.$epsparams)) == $post['sapPopStsReturnFingerPrint']) {
			if ($cfg['acceptvok']) {
				$response['valid'] = ( ($sapPopStsReturnStatus == 'OK') || ($sapPopStsReturnStatus == 'VOK'));
			} else {
	    		$response['valid'] = ($sapPopStsReturnStatus == 'OK');
			}
		} else {
			$response['valid'] = false;
			$response['pending_reason'] = "fingerprint mismatch";
		}


		return $response;
	}

}
?>