<?php
/**
 * @version $Id: upgrade_1_2_0.inc.php
 * @package AEC - Account Control Expiration - Membership Manager
 * @subpackage Install Includes
 * @copyright 2011-2012 Copyright (C) David Deutsch
 * @author David Deutsch <skore@valanx.org> & Team AEC - http://www.valanx.org
 * @license GNU/GPL v.3 http://www.gnu.org/licenses/gpl.html or, at your option, any later version
 */

$oldprocs = array(	"2checkout","airtoy","alertpay","allopass","authorize","authorize_aim","authorize_arb","authorize_cim",
					"billsafe","cardsave","ccbill","clickbank","cybermut","desjardins","dibs","epay",
					"epsnetpay","eway","ewayxml","generic_vpc","google_checkout","hsbc","iats","ideal_advanced",
					"ideal_basic","ipayment_silent","locaweb_pgcerto","mobio","moip","mollie_ideal","mollie_wallie","moneybookers",
					"moneyproxy","multisafepay","netcash","netdebit","netpay","nochex","notify","offline_payment",
					"ogone","onebip","payboxat","payboxfr","paycific","paycom","payer","payfast",
					"payments_gateway","payone","payos","paypal","paypal_payflow_link","paypal_subscription","paypal_wpp","paysignet",
					"paysite_cash","paystation","payza","psigate","realex_redirect","realex_remote","robokassa","sagepay",
					"secureandpay","skrill","smscoin","sofort","sofortueberweisung","sparkassen_internetkasse","suncorp_migs","usaepay",
					"vcs","verotel","viaklix","virtualmerchant","worldpay","worldpay_futurepay","zipzap");

$pph = new PaymentProcessorHandler;

// Figure out existing processors
$iprocs = $pph->getInstalledObjectList( false, true );

// Remove all assets of non-existing processors

// Copy possible ideal_advanced certificates in /processors

// Remove old processor integrations
// Remove old integration dirs

?>
