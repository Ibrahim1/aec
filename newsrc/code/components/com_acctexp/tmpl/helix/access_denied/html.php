<?php

if ( ( $tmpl->cfg['customnotallowed'] != '' ) && !is_null( $tmpl->cfg['customnotallowed'] ) ) {
	aecRedirect( $tmpl->cfg['customnotallowed'] );
}

$tmpl->setTitle( JText::_('NOT_ALLOWED_HEADLINE') );

if ( $user->id ) {
	$registerlink = AECToolbox::deadsureURL( 'index.php?option=com_acctexp&task=renewsubscription' );
	$loggedin = 1;
} else {
	$loggedin = 0;
	if ( GeneralInfoRequester::detect_component( 'anyCB' ) ) {
		$registerlink = AECToolbox::deadsureURL( 'index.php?option=com_comprofiler&task=registers' );
	} else {
		$registerlink = AECToolbox::deadsureURL( 'index.php?option=com_user&view=register' );
	}
}

 ?>
