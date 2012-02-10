<?php
	$db = &JFactory::getDBO();

	$user = &JFactory::getUser();

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
		aecNotAuth();
		return;
	}

	if ( $pending ) {
		pending( $option, $userid );
	} else {
		if ( !empty( $return ) ) {
			aecRedirect( base64_decode( $return ) );
		} else {
			subscriptionDetails( $option, 'invoices' );
		}
	}
?>
