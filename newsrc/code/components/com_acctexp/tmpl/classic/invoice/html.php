<?php

if ( empty( $user->id ) ) {
	return aecNotAuth();
}

$iFactory = new InvoiceFactory( $user->id );
$iFactory->invoiceprint( $option, $invoice );

?>
