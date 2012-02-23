<?php
/**
 * @version $Id: hold/html.php
 * @package AEC - Account Control Expiration - Membership Manager
 * @subpackage Main Frontend
 * @copyright 2012 Copyright (C) David Deutsch
 * @author David Deutsch <skore@valanx.org> & Team AEC - http://www.valanx.org
 * @license GNU/GPL v.2 http://www.gnu.org/licenses/old-licenses/gpl-2.0.html or, at your option, any later version
 */

// Dont allow direct linking
( defined('_JEXEC') || defined( '_VALID_MOS' ) ) or die( 'Direct Access to this location is not allowed.' );

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

$tmpl->addDefaultCSS();

@include( $tmpl->tmpl( 'hold' ) );
