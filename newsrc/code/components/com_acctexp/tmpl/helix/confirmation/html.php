<?php
/**
 * @version $Id: confirmation/html.php
 * @package AEC - Account Control Expiration - Membership Manager
 * @subpackage Main Frontend
 * @copyright 2012 Copyright (C) David Deutsch
 * @author David Deutsch <skore@valanx.org> & Team AEC - http://www.valanx.org
 * @license GNU/GPL v.2 http://www.gnu.org/licenses/old-licenses/gpl-2.0.html or, at your option, any later version
 */

// Dont allow direct linking
( defined('_JEXEC') || defined( '_VALID_MOS' ) ) or die( 'Direct Access to this location is not allowed.' );

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

if ( $tmpl->cfg['plans_first'] && !empty( $usage ) && empty( $username ) && empty( $passthrough['username'] ) && !$userid && !$user->id ) {
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
	if ( empty( $usage ) ) {
		subscribe( $option );
	}
}

$iFactory = new InvoiceFactory( $userid, $usage, $group, $processor );
$iFactory->confirm( $option );

$makegift = false;

if ( !empty( $tmpl->cfg['confirm_as_gift'] ) ) {
	if ( !empty( $tmpl->cfg['checkout_as_gift_access'] ) ) {
		if ( $InvoiceFactory->metaUser->hasGroup( $tmpl->cfg['checkout_as_gift_access'] ) ) {
			$makegift = true;
		}
	} else {
		$makegift = true;
	}
}

if ( !empty( $tmpl->cfg['tos'] ) ) {
	$js = 'function submitPayment() {
		if ( document.confirmForm.tos.checked ) {
			document.confirmForm.submit();
		} else {
			alert("' . html_entity_decode( JText::_('CONFIRM_TOS_ERROR') ) . ' )");
		}
	}';

	$tmpl->addScriptDeclaration( $js );
}

$tmpl->setTitle( JText::_('CONFIRM_TITLE') );

$tmpl->addDefaultCSS();

@include( $tmpl->tmpl( 'confirmation' ) );
