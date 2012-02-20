<?php

if ( ( $aecConfig->cfg['customnotallowed'] != '' ) && !is_null( $aecConfig->cfg['customnotallowed'] ) ) {
	aecRedirect( $aecConfig->cfg['customnotallowed'] );
}

$gwnames = PaymentProcessorHandler::getInstalledNameList( true );

if ( count( $gwnames ) && $gwnames[0] && !empty($tmpl->cfg['gwlist']) ) {
	$processors = array();
	foreach ( $gwnames as $procname ) {
		if ( !in_array( $procname, $tmpl->cfg['gwlist'] ) ) {
			continue;
		}

		$processor = trim( $procname );
		$processors[$processor] = new PaymentProcessor();
		if ( $processors[$processor]->loadName( $processor ) ) {
			$processors[$processor]->init();
			$processors[$processor]->getInfo();
			$processors[$processor]->getSettings();
		} else {
			$short	= 'processor loading failure';
			$event	= 'When composing processor info list, tried to load processor: ' . $procname;
			$tags	= 'processor,loading,error';
			$params = array();

			$eventlog = new eventLog( $db );
			$eventlog->issue( $short, $tags, $event, 128, $params );

			unset( $processors[$processor] );
		}
	}
} else {
	$processors = false;
}

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

$tmpl->setTitle( JText::_('NOT_ALLOWED_HEADLINE') );

?>
