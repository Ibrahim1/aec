<?
if ( !empty( $InvoiceFactory->mi_form ) ) {
	if ( !empty( $InvoiceFactory->mi_error ) ) {
		echo '<div id="confirmation_error">';
		foreach ( $InvoiceFactory->mi_error as $error ) {
			echo '<p>' . $error . '</p>';
		}
		echo '</div>';
	}
	echo '<div id="confirmation_extra">' . $InvoiceFactory->mi_form . '</div>';
}
?>
