<?php
/**
 * @version $Id: invoice/html.php
 * @package AEC - Account Control Expiration - Membership Manager
 * @subpackage Main Frontend
 * @copyright 2012 Copyright (C) David Deutsch
 * @author David Deutsch <skore@valanx.org> & Team AEC - http://www.valanx.org
 * @license GNU/GPL v.3 http://www.gnu.org/licenses/gpl.html or, at your option, any later version
 */

// Dont allow direct linking
( defined('_JEXEC') || defined( '_VALID_MOS' ) ) or die( 'Direct Access to this location is not allowed.' );

$iFactory = new InvoiceFactory( $user->id );
$iFactory->invoiceprint( $option, $invoice );

$tmpl->addDefaultCSS();

if ( $standalone ) {
	@include( $tmpl->tmpl( 'invoice_standalone' ) );

	exit;
} else {
	$document=& JFactory::getDocument();
	$document->addCustomTag( '<link rel="stylesheet" type="text/css" media="screen, print" href="' . JURI::root(true) . '/media/' . $option . '/css/invoice_embed.css" />' );
	$document->addCustomTag( '<link rel="stylesheet" type="text/css" media="print" href="' . JURI::root(true) . '/media/' . $option . '/css/invoice_print.css" />' );

	@include( $tmpl->tmpl( 'invoice' ) );
}

