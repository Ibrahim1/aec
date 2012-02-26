<?php
/**
 * @version $Id: cancel/html.php
 * @package AEC - Account Control Expiration - Membership Manager
 * @subpackage Main Frontend
 * @copyright 2012 Copyright (C) David Deutsch
 * @author David Deutsch <skore@valanx.org> & Team AEC - http://www.valanx.org
 * @license GNU/GPL v.3 http://www.gnu.org/licenses/gpl.html or, at your option, any later version
 */

// Dont allow direct linking
( defined('_JEXEC') || defined( '_VALID_MOS' ) ) or die( 'Direct Access to this location is not allowed.' );

if ( empty( $user->id ) ) {
	if ( $userid ) {
		if ( AECToolbox::quickVerifyUserID( $userid ) === true ) {
			// This user is not expired, so he could log in...
			return aecNotAuth();
		}
	} else {
		return aecNotAuth();
	}
} else {
	$userid = $user->id;
}

$invoiceid = AECfetchfromDB::InvoiceIDfromNumber( $invoice_number, $userid );

// Only allow a user to access existing and own invoices
if ( $invoiceid ) {
	$objInvoice = new Invoice( $db );
	$objInvoice->load( $invoiceid );

	$objInvoice->cancel();
} else {
	return aecNotAuth();
}

if ( !$pending ) {
	if ( !empty( $return ) ) {
		aecRedirect( base64_decode( $return ) );
	} else {
		subscriptionDetails( $option, 'invoices' );
	}
}

$tmpl->addDefaultCSS();

@include( $tmpl->tmpl( 'cancel' ) );
