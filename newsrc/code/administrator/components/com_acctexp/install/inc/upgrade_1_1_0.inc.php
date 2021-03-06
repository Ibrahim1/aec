<?php
/**
 * @version $Id: upgrade_1_1_0.inc.php
 * @package AEC - Account Control Expiration - Membership Manager
 * @subpackage Install Includes
 * @copyright 2011-2015 Copyright (C) David Deutsch
 * @author David Deutsch <skore@valanx.org> & Team AEC - http://www.valanx.org
 * @license GNU/GPL v.3 http://www.gnu.org/licenses/gpl.html or, at your option, any later version
 */

// Dont allow direct linking
defined('_JEXEC') or die( 'Direct Access to this location is not allowed.' );

$testprocs = xJUtility::getFileArray( PaymentProcessorHandler::pp_dir(), false, false );

// Check if there are old processor files
if ( count( $testprocs ) > 5 ) {
	$oldprocs = array(
		"2checkout","airtoy","alertpay","allopass","authorize","authorize_aim","authorize_arb","authorize_cim",
		"billsafe","cardsave","ccbill","clickbank","cybermut","desjardins","dibs","epay",
		"epsnetpay","eway","ewayxml","generic_vpc","google_checkout","hsbc","iats","ideal_advanced",
		"ideal_basic","ipayment_silent","locaweb_pgcerto","mobio","moip","mollie_ideal","mollie_wallie","moneybookers",
		"moneyproxy","multisafepay","netcash","netdebit","netpay","nochex","notify","offline_payment",
		"offline_payment2","offline_payment3","ogone","onebip","payboxat","payboxfr","paycific","paycom",
		"payer","payfast","payments_gateway","payone","payos","paypal","paypal_payflow_link","paypal_subscription",
		"paypal_wpp","paysignet",	"paysite_cash","paystation","payza","psigate","realex_redirect","realex_remote",
		"robokassa","sagepay","secureandpay","skrill","smscoin","sofort","sofortueberweisung","sparkassen_internetkasse",
		"suncorp_migs","usaepay","vcs","verotel","viaklix","virtualmerchant","worldpay","worldpay_futurepay",
		"zipzap"
	);

	$images = array(
		"2checkout","airtoy","alertpay","authorize","beanstream","cardsave","ccbill","chase_paymentech",
		"clickbank","cybermut","desjardins","dibs","epsnetpay","eway","fastcharge","google_checkout",
		"hsbc","ideal","ipayment","mobio","moip","mollie_ideal","mollie","moneybookers",
		"moneyproxy","mpay24","multisafepay","netdebit","nochex","ogone","onebip","payboxat",
		"payboxfr","paycific","paycom","payer","payfast","payments_gateway","payone","payos",
		"paypal","paysite_cash","payson","paystation","payza","psigate","realex","sagepay",
		"secureandpay","siamcb","skrill","smscoin","sofort_dauerauftrag","sofortueberweisung","sparkasse_internetkasse","ticketmaster",
		"verotel","wepay","worldpay","zipzap","zombaio"
	);

	// Figure out existing processors
	$iprocs = PaymentProcessorHandler::getInstalledObjectList( false, true );

	// Remove all assets of non-existing processors
	$noninstalled = array_diff( $images, $iprocs );

	foreach ( $noninstalled as $image ) {
		$imgpath = JPATH_SITE . '/media/com_acctexp/images/site/' . $image . '.png';

		if ( file_exists( $imgpath ) ) {
			unlink( $imgpath );
		}
	}

	// Copy possible ideal_advanced certificates in /processors
	if ( in_array( "ideal_advanced", $iprocs ) ) {
		$oldidealssl = PaymentProcessorHandler::pp_dir() . '/ideal_advanced/ssl';
		$newidealssl = PaymentProcessorHandler::pp_dir() . '/ideal_advanced/lib/ssl';

		$eucaInstall->rrmdir( $newidealssl );

		rename($oldidealssl, $newidealssl);
	}

	// Clear up other old crap - we're keeping some processor dirs, so we need to be careful
	$crappy = array( 'cybermut', 'ideal_advanced', 'google_checkout', 'vcs' );

	foreach ( $crappy as $ccproc ) {
		$ibase = PaymentProcessorHandler::pp_dir() . '/' . $ccproc;

		if ( is_dir( $ibase ) ) {
			$idir = xJUtility::getFileArray( $ibase, false, true );

			if ( empty( $idir ) ) {
				continue;
			}

			$protect = array( 'index.html', $ccproc.'.php', 'fail.php', 'success.php', 'lib', 'lang', 'media' );

			foreach ( $idir as $di ) {
				$die = $ibase . '/' . $di;

				if ( in_array( $di, $protect ) ) {
					continue;
				}

				if ( is_dir( $die ) ) {
					$eucaInstall->rrmdir( $die );
				} else {
					unlink( $die );
				}
			}
		}
	}

	// Remove other old integration dirs
	$olddirs = array( 'authorizenet_cim', 'dibs', 'mollie', 'pp_example' );

	foreach( $olddirs as $olddir ) {
		$eucaInstall->rrmdir( PaymentProcessorHandler::pp_dir() . '/' . $olddir );
	}

	// Remove old processor integrations
	foreach ( $oldprocs as $proc ) {
		$ppath = PaymentProcessorHandler::pp_dir() . '/' . $proc . '.php';

		if ( file_exists( $ppath ) ) {
			unlink( $ppath );
		}
	}

	$customprocs = xJUtility::getFileArray( PaymentProcessorHandler::pp_dir(), false, false );

	foreach( $customprocs as $cproc ) {
		if ( strpos( $cproc, '.php' ) ) {
			$newdir = PaymentProcessorHandler::pp_dir() . '/' . str_replace( '.php', '', $cproc );

			mkdir( $newdir );

			rename( PaymentProcessorHandler::pp_dir() . '/' . $cproc, $newdir . '/' . $cproc );
		}
	}

}

$mih = new microIntegrationHandler;

$testmis = xJUtility::getFileArray( $mih->mi_dir, false, false );

// Check if there are old mi files
if ( count( $testmis ) > 5 ) {
// Remove other old integration dirs
	$olddirs = array( 'mi_example', 'mi_htaccess' );

	foreach( $olddirs as $olddir ) {
		$eucaInstall->rrmdir( $mih->mi_dir . '/' . $olddir );
	}

	// Try to preserve custom MIs
	foreach( $testmis as $mik ) {
		$handle = str_replace( array( 'mi_', '.php' ), '', $mik );

		if ( is_dir( $mih->mi_dir . '/' . $mik ) || ( strpos( $mik, '.php' ) === false ) ) {
			continue;
		}

		if ( is_dir( $mih->mi_dir . '/' . $handle ) ) {
			unlink( $mih->mi_dir . '/' . $mik );

			continue;
		}

		$newdir = $mih->mi_dir . '/' . $handle;

		mkdir( $newdir );

		rename( $mih->mi_dir . '/' . $mik, $newdir . '/' . $handle . '.php' );
	}

	$milist = microIntegrationHandler::getDetailedList();

	$mixsearch = array( 'mi_hotproperty', 'docman', 'uddeim' );

	$mixhits = array();
	foreach ( $milist as $mix ) {
		if ( in_array( $mix->class_name, $mixsearch ) && !in_array( $mix->class_name, $mixhits ) ) {
			$mixhits[] = $mix->class_name;
		}
	}

	if ( !empty( $mixhits ) ) {
		foreach ( $mixhits as $mixclass ) {
			$errors[] = array( "The directory structure for the MicroIntegrations has changed. Please update the hacks for the following MI: " . $mixclass );
		}
	}
}
