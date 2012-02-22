<?php
if ( $userid == 0 ) {
	return aecRedirect( AECToolbox::deadsureURL( 'index.php' ) );
}

if ( $metaUser->hasSubscription ) {
	// Make sure this really is pending
	if ( strcmp($metaUser->objSubscription->status, 'Hold') !== 0 ) {
		return aecNotAuth();
	}
}

$tmpl->setTitle( JText::_('HOLD_TITLE') );

 ?>
