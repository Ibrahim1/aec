<?php
/**
 * @version $Id: upgrade_0_12_6.inc.php
 * @package AEC - Account Control Expiration - Membership Manager
 * @subpackage Install Includes
 * @copyright 2006-2015 Copyright (C) David Deutsch
 * @author David Deutsch <skore@valanx.org> & Team AEC - http://www.valanx.org
 * @license GNU/GPL v.3 http://www.gnu.org/licenses/gpl.html or, at your option, any later version
 */

// Dont allow direct linking
defined('_JEXEC') or die( 'Direct Access to this location is not allowed.' );

$eucaInstalldb->addColifNotExists( 'hidden', "int(4) NOT NULL default '0'", 'microintegrations' );

// Upgrade old processor notification storage
$db->setQuery( "SELECT max(id) FROM #__acctexp_log_history" );
$himax = $db->loadResult();

for ( $hid=1; $hid<=$himax; $hid++ ) {
	$db->setQuery( "SELECT * FROM #__acctexp_log_history WHERE `id` = $hid" );
	$history = $db->loadObject();

	if ( !is_object( $history ) ) {
		continue;
	}

	if ( ( $history->id != $hid ) || empty( $history->response ) ) {
		continue;
	}

	// Fix broken newlines
	$history->response = str_replace( '\n', "\n", $history->response );
	// Fix faulty parameters in old PayPal entries
	$history->response = str_replace( 'Validation: ', "paypal_verification=", $history->response );
	// Fix faulty parameters in extremely old PayPal entries
	$history->response = str_replace( 'VERIFIED ', "paypal_verification=VERIFIED\n", $history->response );

	// Resolve into Array
	$temp = parameterHandler::decode( $history->response );

	// Fix faulty parameters in old locaweb_pgcerto entries
	if ( ( count( $temp ) <= 1 ) && isset( $temp[0] ) ) {
		$temp2 = array( 'response' => $temp[0] );
		$temp = $temp2;
	} elseif ( !is_array( $temp ) ) {
		$temp2 = array( 'response' => $temp );
		$temp = $temp2;
	}

	foreach ( $temp as $k => $v ) {
		$temp[$k] = urldecode($v);
	}

	$query = 'UPDATE #__acctexp_log_history'
	. ' SET `response` = \'' . base64_encode( serialize( $temp ) ) . '\''
	. ' WHERE `id` = \'' . $hid . '\''
	;
	$db->setQuery( $query );
	$db->query();
}

/*
// Modifying MI tables for MI Scope

// Make sure we have MIs in the first place

// Loop through MIs putting settings in subarrays (convert old _exp _pre_exp stuff)

// Convert old interpretative pre-expiration into new event table scheme
// Get all MIs that have pre-exp
// Get plans that have that MI
// Get active users with that plan
// Create events for users

// Delete old mi table columns
$eucaInstalldb->dropColifExists( 'auto_check', 'microintegrations' );
$eucaInstalldb->dropColifExists( 'on_userchange', 'microintegrations' );
$eucaInstalldb->dropColifExists( 'pre_exp_check', 'microintegrations' );

*/
