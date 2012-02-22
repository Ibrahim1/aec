<?php
// Always rewrite to session userid
if ( !empty( $user->id ) ) {
	$userid = $user->id;
}

$invoiceid = AECfetchfromDB::InvoiceIDfromNumber( $invoice_number, $userid );

// Only allow a user to access existing and own invoices
if ( $invoiceid ) {
	$iFactory = new InvoiceFactory( $userid, null, null, $processor );
	$iFactory->touchInvoice( $option, $invoice_number );
	$iFactory->internalcheckout( $option );
} else {
	aecNotAuth();
	return;
}
?>
