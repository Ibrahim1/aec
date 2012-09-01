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
	$InvoiceFactory->jsvalidation['rules']['tos'] = array( 'required' => true );
}

if ( !empty( $InvoiceFactory->jsvalidation ) ) {
	$tmpl->addScript( JURI::root(true).'/media/com_acctexp/js/jquery/jquery-1.8.1.min.js' );
	$tmpl->addScript( JURI::root(true).'/media/com_acctexp/js/jquery/jquery.validate.js' );
	$tmpl->addScript( JURI::root(true).'/media/com_acctexp/js/jquery/jquery.validate.additional-methods.js' );
	$tmpl->addScript( JURI::root(true).'/media/com_acctexp/js/jquery/jquerync.js' );

	$js = "
jQuery(document).ready(function(){
	jQuery('#form-confirm').validate(
	{
	rules: " . json_encode( $InvoiceFactory->jsvalidation['rules'] ) . ",
	highlight: function(label) {
		jQuery(label).closest('.well').addClass('well-highlight');
		jQuery(label).closest('.control-group').addClass('error');
		jQuery(label).closest('.label-important').prepend('<i class=\"icon-ban-circle icon-white\"></i>');
		jQuery('#form-confirm button#confirmation').attr('disabled','disabled');
	},
	unhighlight: function(label) {
		jQuery(label).closest('.well').removeClass('well-highlight');
		jQuery(label).closest('.control-group').removeClass('error');
		if ( jQuery(\"form#form-confirm .label-important\").length > 0) {
			jQuery('#form-confirm button#confirmation').attr('disabled','disabled');
		} else {
			jQuery('#form-confirm button#confirmation').attr(\"disabled\", false);
		}
	},
	success: function(label) {
		label.remove();

		jQuery('#form-confirm button#confirmation').attr(\"disabled\", false);
	},
	errorClass: 'label label-important',
	submitHandler: function(form) {
		if ( jQuery('#form-confirm').valid() ) {
			form.submit();
		} else {
			jQuery('#form-confirm button#confirmation').attr('disabled','disabled');
		}
	}
	});

	
	
});
	";

	$tmpl->addScriptDeclaration( $js );
}

$tmpl->setTitle( JText::_('CONFIRM_TITLE') );

$tmpl->addDefaultCSS();

@include( $tmpl->tmpl( 'confirmation' ) );
