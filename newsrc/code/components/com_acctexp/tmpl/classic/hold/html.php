<?php
if ( $userid > 0 ) {
	$metaUser = new metaUser( $userid );

	if ( $metaUser->hasSubscription ) {
		// Make sure this really is pending
		if ( strcmp($metaUser->objSubscription->status, 'Hold') !== 0 ) {
			return aecNotAuth();
		}
	}

	$document=& JFactory::getDocument();

	$document->setTitle( html_entity_decode( JText::_('HOLD_TITLE'), ENT_COMPAT, 'UTF-8' ) );

	$frontend = new HTML_frontEnd();
	$frontend->hold( $option, $metaUser );
} else {
	aecRedirect( AECToolbox::deadsureURL( 'index.php' ) );
}
?>
