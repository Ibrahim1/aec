<?php
/**
 * @version $Id: invoice_standalone.php
 * @package AEC - Account Control Expiration - Membership Manager
 * @subpackage Main Frontend
 * @copyright 2012 Copyright (C) David Deutsch
 * @author David Deutsch <skore@valanx.org> & Team AEC - http://www.valanx.org
 * @license GNU/GPL v.3 http://www.gnu.org/licenses/gpl.html or, at your option, any later version
 */

// Dont allow direct linking
( defined('_JEXEC') || defined( '_VALID_MOS' ) ) or die( 'Direct Access to this location is not allowed.' ); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title><?php echo $data['page_title'] ?></title>
	<meta http-equiv="Content-type" content="text/html;charset=UTF-8" />
	<link rel="stylesheet" href="<?php echo JURI::root(true) . '/media/' . $option ?>/css/invoice.css" type="text/css" media="screen, print" />
	<link rel="stylesheet" href="<?php echo JURI::root(true) . '/media/' . $option ?>/css/invoice_print.css" type="text/css" media="print" />
	<?php if ( !empty( $tmpl->cfg['invoice_address_allow_edit'] ) ) { ?>
	<script type="text/javascript" src="<?php echo JURI::root(true) . '/media/' . $option ?>/js/jquery/jquery-1.3.2.min.js"></script>
	<script type="text/javascript">
	jQuery(document).ready(function() {
		jQuery('textarea[name=address]').keyup(function() {
			jQuery('#address pre').text($(this).val());
		});
	 });
	</script>
	<?php } ?>
</head>
<body>
	<?php if ( !empty( $tmpl->cfg['invoice_address_allow_edit'] ) ) { ?>
		<div id="printbutton">
			<div id="printbutton_inner">
				<textarea align="left" cols="40" rows="5" name="address" /><?php echo $data['address'] ?></textarea>
				<button onClick="window.print()" id="printbutton"><?php echo JText::_('INVOICEPRINT_PRINT') ?></button>
			</div>
			<p><?php echo JText::_('INVOICEPRINT_BLOCKNOTICE') ?></p>
		</div>
	<?php } else { ?>
		<div id="printbutton">
			<div id="printbutton_inner">
				<textarea align="left" cols="40" rows="5" name="address" disabled="disabled" /><?php echo $data['address'] ?></textarea>
				<button onClick="window.print()" id="printbutton"><?php echo JText::_('INVOICEPRINT_PRINT') ?></button>
			</div>
		</div>
	<?php } ?>
	<?php @include( $tmpl->tmpl( 'invoice' ) ) ?>
</body>
