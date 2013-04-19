<?php
/**
 * @version $Id: exception/html.php
 * @package AEC - Account Control Expiration - Membership Manager
 * @subpackage Main Frontend
 * @copyright 2012 Copyright (C) David Deutsch
 * @author David Deutsch <skore@valanx.org> & Team AEC - http://www.valanx.org
 * @license GNU/GPL v.3 http://www.gnu.org/licenses/gpl.html or, at your option, any later version
 */

// Dont allow direct linking
defined('_JEXEC') or die( 'Direct Access to this location is not allowed.' );

$tmpl->defaultHeader();

if ( $hasform ) {
	$tmpl->setTitle( JText::_('EXCEPTION_TITLE') );
} else {
	$tmpl->setTitle( JText::_('EXCEPTION_TITLE_NOFORM') );
}

@include( $tmpl->tmpl( 'exception' ) );
