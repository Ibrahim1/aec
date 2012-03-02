<?php
/**
 * @version $Id: plans/html.php
 * @package AEC - Account Control Expiration - Membership Manager
 * @subpackage Main Frontend
 * @copyright 2012 Copyright (C) David Deutsch
 * @author David Deutsch <skore@valanx.org> & Team AEC - http://www.valanx.org
 * @license GNU/GPL v.3 http://www.gnu.org/licenses/gpl.html or, at your option, any later version
 */

// Dont allow direct linking
( defined('_JEXEC') || defined( '_VALID_MOS' ) ) or die( 'Direct Access to this location is not allowed.' );

$tmpl->addDefaultCSS();

$tmpl->setTitle( JText::_('PAYPLANS_HEADER') );

if ( !empty( $csslist ) ) {
	foreach ( $csslist as $css ) {
		$tmpl->addCSSDeclaration( str_replace( 'btn', 'aec-btn', $css ) );
	}
}

// Prefix some stuff
foreach ( $list as $li => $lv ) {
	if ( $lv['type'] == 'group' ) {
		continue;
	}

	foreach ( $lv['gw'] as $gwid => $pp ) {
		foreach ( $list[$li]['gw'][$gwid]->btn as $k => $v ) {
			$list[$li]['gw'][$gwid]->btn[$k] = str_replace( 'btn', 'aec-btn', $v );
		}
	}
}

@include( $tmpl->tmpl( 'plans' ) );
