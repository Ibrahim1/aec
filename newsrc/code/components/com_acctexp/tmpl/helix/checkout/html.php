<?php
/**
 * @version $Id: checkout/html.php
 * @package AEC - Account Control Expiration - Membership Manager
 * @subpackage Main Frontend
 * @copyright 2012 Copyright (C) David Deutsch
 * @author David Deutsch <skore@valanx.org> & Team AEC - http://www.valanx.org
 * @license GNU/GPL v.3 http://www.gnu.org/licenses/gpl.html or, at your option, any later version
 */

// Dont allow direct linking
( defined('_JEXEC') || defined( '_VALID_MOS' ) ) or die( 'Direct Access to this location is not allowed.' );

$makegift = false;

if ( !empty( $tmpl->cfg['checkout_as_gift'] ) ) {
	if ( !empty( $tmpl->cfg['checkout_as_gift_access'] ) ) {
		if ( $InvoiceFactory->metaUser->hasGroup( $tmpl->cfg['checkout_as_gift_access'] ) ) {
			$makegift = true;
		}
	} else {
		$makegift = true;
	}
}

$InvoiceFactory->checkout['customtext_checkout_keeporiginal']	= $tmpl->cfg['customtext_checkout_keeporiginal'];
$InvoiceFactory->checkout['customtext_checkout']				= $tmpl->rwrq( $tmpl->cfg['customtext_checkout'], $InvoiceFactory );

if ( isset( $tmpl->cfg['checkout_display_descriptions'] ) ) {
	$InvoiceFactory->checkout['checkout_display_descriptions']		= $tmpl->cfg['checkout_display_descriptions'];
} else {
	$InvoiceFactory->checkout['checkout_display_descriptions']		= 0;
}

$tmpl->addDefaultCSS();

$tmpl->setTitle( $InvoiceFactory->checkout['checkout_title'] );

if ( $tmpl->cfg['checkoutform_jsvalidation'] ) {
	$tmpl->addScript( JURI::root(true) . '/media/com_acctexp/js/ccvalidate.js' );
}


if ( strpos( $var, 'class="tab-content"' ) ) {
	// Include Mootools tabber
	$tmpl->addCustomTag( '<script type="text/javascript" src="' . JURI::root(true) . 'components/com_acctexp/lib/mootools/mootools.js"></script>' );
	$tmpl->addCustomTag( '<script type="text/javascript" src="' . JURI::root(true) . 'components/com_acctexp/lib/mootools/Tabs.js"></script>' );
	$tmpl->addCustomTag( '<script type="text/javascript" src="' . JURI::root(true) . 'components/com_acctexp/lib/mootools/Fx.Tabs.js"></script>' );
	$tmpl->addCustomTag( '<script type="text/javascript" charset="utf-8">window.addEvent(\'domready\', function() {var fxTabs = new Fx.Tabs();});}</script>' );
}

@include( $tmpl->tmpl( 'checkout' ) );
