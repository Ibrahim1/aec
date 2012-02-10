<?php
$reason = "";

if ( $userid == 0 ) {
	return aecRedirect( AECToolbox::deadsureURL( 'index.php' ) );
}

if ( $metaUser->hasSubscription ) {
	// Make sure this really is pending
	if ( strcmp($metaUser->objSubscription->status, 'Pending') !== 0 ) {
		return aecNotAuth();
	}
}

$invoices = AECfetchfromDB::InvoiceCountbyUserID( $userid );

if ( $invoices ) {
	$invoice = AECfetchfromDB::lastUnclearedInvoiceIDbyUserID( $userid );

	$objInvoice = new Invoice( $db );
	$objInvoice->loadInvoiceNumber( $invoice );

	$params = $objInvoice->params;

	if ( isset( $params['pending_reason'] ) ) {
		$lang = JFactory::getLanguage();
		if ( $lang->hasKey( 'PENDING_REASON_' . strtoupper( $params['pending_reason'] ) ) ) {
			$reason = JText::_( 'PENDING_REASON_' . strtoupper( $params['pending_reason'] ) );
		} else {
			$reason = $params['pending_reason'];
		}
	} elseif ( strcmp( $objInvoice->method, 'transfer' ) === 0 ) {
		$reason = 'transfer';
	} else {
		$reason = 0;
	}
} else {
	$invoice = 'none';
}
$tmpl->setTitle( JText::_('PENDING_TITLE') );
?>
