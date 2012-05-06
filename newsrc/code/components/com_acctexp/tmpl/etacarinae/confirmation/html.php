<?php
/**
 * @version $Id: confirmation/html.php
 * @package AEC - Account Control Expiration - Membership Manager
 * @subpackage Main Frontend
 * @copyright 2012 Copyright (C) David Deutsch
 * @author David Deutsch <skore@valanx.org> & Team AEC - http://www.valanx.org
 * @license GNU/GPL v.3 http://www.gnu.org/licenses/gpl.html or, at your option, any later version
 */

// Dont allow direct linking
( defined('_JEXEC') || defined( '_VALID_MOS' ) ) or die( 'Direct Access to this location is not allowed.' );

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
	$tmpl->addScript( JURI::root(true).'/media/com_acctexp/js/jquery/jquery-1.7.1.min.js' );
	$tmpl->addScript( JURI::root(true).'/media/com_acctexp/js/jquery/jquerync.js' );

	$js = '
jQuery(document).ready(function(jQuery) {
	jQuery("form#form-confirm :submit").click( function(event) {
			event.preventDefault();

			if ( jQuery("form#form-confirm .aec-tax").length > 0) {
				test = jQuery("select.aec-tax").val();
				if ( jQuery("select.aec-tax").val() == "0" ) {
					jQuery("div#confirmation-extra>div").addClass("alert-danger").removeClass("alert-success");

					return;
				}
			}

			if ( jQuery("#aec-tos").is(\':checked\') ) {
				jQuery("form#form-confirm").submit();
			} else {
				alert("' . html_entity_decode( JText::_('CONFIRM_TOS_ERROR') ) . '");
			}
	});
});
';

	$tmpl->addScriptDeclaration( $js );
}

$tmpl->setTitle( JText::_('CONFIRM_TITLE') );

$tmpl->addDefaultCSS();

@include( $tmpl->tmpl( 'confirmation' ) );
