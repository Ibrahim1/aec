<?php
$user = &JFactory::getUser();

$db = &JFactory::getDBO();

global $aecConfig;

$app = JFactory::getApplication();

$userid		= aecGetParam( 'userid', 0, true, array( 'word', 'int' ) );
$usage		= aecGetParam( 'usage', 0, true, array( 'word', 'string', 'clear_nonalnum' ) );
$group		= aecGetParam( 'group', 0, true, array( 'word', 'int' ) );
$processor	= aecGetParam( 'processor', '', true, array( 'word', 'string', 'clear_nonalnum' ) );
$username	= aecGetParam( 'username', 0, true, array( 'word', 'int' ) );

$passthrough = array();
if ( isset( $_POST['aec_passthrough'] ) ) {
	if ( is_array( $_POST['aec_passthrough'] ) ) {
		$passthrough = $_POST['aec_passthrough'];
	} else {
		$passthrough = unserialize( base64_decode( $_POST['aec_passthrough'] ) );
	}
}

if ( $aecConfig->cfg['plans_first'] && !empty( $usage ) && empty( $username ) && empty( $passthrough['username'] ) && !$userid && !$user->id ) {
	if ( GeneralInfoRequester::detect_component( 'anyCB' ) ) {
		// This is a CB registration, borrowing their code to register the user
		include_once( JPATH_SITE . '/components/com_comprofiler/comprofiler.html.php' );
		include_once( JPATH_SITE . '/components/com_comprofiler/comprofiler.php' );

		registerForm( $option, $app->getCfg( 'emailpass' ), null );
	} else {
		// This is a joomla registration
		joomlaregisterForm( $option, $app->getCfg( 'useractivation' ) );
	}
} else {
	if ( !empty( $usage ) ) {
		$iFactory = new InvoiceFactory( $userid, $usage, $group, $processor );
		$iFactory->confirm( $option );
	} else {
		subscribe( $option );
	}
}
?>
