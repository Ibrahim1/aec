<?php
/**
 * @version $Id: access_denied/html.php
 * @package AEC - Account Control Expiration - Membership Manager
 * @subpackage Main Frontend
 * @copyright 2012 Copyright (C) David Deutsch
 * @author David Deutsch <skore@valanx.org> & Team AEC - http://www.valanx.org
 * @license GNU/GPL v.3 http://www.gnu.org/licenses/gpl.html or, at your option, any later version
 */

// Dont allow direct linking
defined('_JEXEC') or die( 'Direct Access to this location is not allowed.' );

$tmpl->setTitle( JText::_('NOT_ALLOWED_HEADLINE') );

if ( $user->id ) {
	$registerlink = AECToolbox::deadsureURL( 'index.php?option=com_acctexp&task=renewsubscription' );
	$loggedin = 1;
} else {
	$loggedin = 0;
	if ( aecComponentHelper::detect_component( 'anyCB' ) ) {
		$registerlink = AECToolbox::deadsureURL( 'index.php?option=com_comprofiler&task=registers' );
	} else {
		if ( defined( 'JPATH_MANIFESTS' ) ) {
			$registerlink = AECToolbox::deadsureURL( 'index.php?option=com_users&view=registration' );
		} else {
			$registerlink = AECToolbox::deadsureURL( 'index.php?option=com_user&view=register' );
		}
	}
}

if ( $loggedin ) {
	$loginlink = JText::_('NOT_ALLOWED_FIRSTPAR_LOGGED') . '&nbsp;' . $tmpl->lnk( $registerlink, JText::_('NOT_ALLOWED_REGISTERLINK_LOGGED'), 'btn btn-default' );
} else {
	$loginlink = JText::_('NOT_ALLOWED_FIRSTPAR') . '&nbsp;' . $tmpl->lnk( $registerlink, JText::_('NOT_ALLOWED_REGISTERLINK'), 'btn btn-default' );
}

$tmpl->defaultHeader();

@include( $tmpl->tmpl( 'access_denied' ) );
