<?php
/**
 * @version $Id: passwordprompt/html.php
 * @package AEC - Account Control Expiration - Membership Manager
 * @subpackage Main Frontend
 * @copyright 2012 Copyright (C) David Deutsch
 * @author David Deutsch <skore@valanx.org> & Team AEC - http://www.valanx.org
 * @license GNU/GPL v.3 http://www.gnu.org/licenses/gpl.html or, at your option, any later version
 */

// Dont allow direct linking
defined('_JEXEC') or die( 'Direct Access to this location is not allowed.' );

$tmpl->setTitle( JText::_('AEC_PROMPT_PASSWORD') );

$tmpl->defaultHeader();

@include( $tmpl->tmpl( 'passwordprompt' ) );
