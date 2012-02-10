<?php
$db = &JFactory::getDBO();

$reason = "";

if ( $userid > 0 ) {
	$metaUser = new metaUser( $userid );

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

	$document=& JFactory::getDocument();

	$document->setTitle( html_entity_decode( JText::_('PENDING_TITLE'), ENT_COMPAT, 'UTF-8' ) );

	$frontend = new HTML_frontEnd();
	$frontend->pending( $option, $metaUser->cmsUser, $invoice, $reason );
} else {
	aecRedirect( AECToolbox::deadsureURL( 'index.php' ) );
}
?>
