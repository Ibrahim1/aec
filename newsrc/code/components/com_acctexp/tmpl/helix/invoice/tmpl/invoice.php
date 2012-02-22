<?
/**
 * @version $Id: expired.php
 * @package AEC - Account Control Expiration - Membership Manager
 * @subpackage Main Frontend
 * @copyright 2012 Copyright (C) David Deutsch
 * @author David Deutsch <skore@valanx.org> & Team AEC - http://www.valanx.org
 * @license GNU/GPL v.2 http://www.gnu.org/licenses/old-licenses/gpl-2.0.html or, at your option, any later version
 */

// Dont allow direct linking
( defined('_JEXEC') || defined( '_VALID_MOS' ) ) or die( 'Direct Access to this location is not allowed.' );

if ( $standalone ) {
 ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title><?= $data['page_title'] ?></title>
	<meta http-equiv="Content-type" content="text/html;charset=UTF-8" />
	<link rel="stylesheet" href="<?= JURI::root(true) . '/media/' . $option ?>/css/invoice.css" type="text/css" media="screen, print" />
	<link rel="stylesheet" href="<?= JURI::root(true) . '/media/' . $option ?>/css/invoice_print.css" type="text/css" media="print" />
	<? if ( !empty( $tmpl->cfg['invoice_address_allow_edit'] ) ) { ?>
	<script type="text/javascript" src="<?= JURI::root(true) . '/media/' . $option ?>/js/jquery/jquery-1.3.2.min.js"></script>
	<script type="text/javascript">
	jQuery(document).ready(function() {
		jQuery('textarea[name=address]').keyup(function() {
			jQuery('#address pre').text($(this).val());
		});
	 });
	</script>
	<? } ?>
</head>
<body>
	<? if ( !empty( $tmpl->cfg['invoice_address_allow_edit'] ) ) { ?>
		<div id="printbutton">
			<div id="printbutton_inner">
				<textarea align="left" cols="40" rows="5" name="address" /><?= $data['address'] ?></textarea>
				<button onClick="window.print()" id="printbutton"><?= JText::_('INVOICEPRINT_PRINT') ?></button>
			</div>
			<p><?= JText::_('INVOICEPRINT_BLOCKNOTICE') ?></p>
		</div>
	<? } else { ?>
		<div id="printbutton">
			<div id="printbutton_inner">
				<textarea align="left" cols="40" rows="5" name="address" disabled="disabled" /><?= $data['address'] ?></textarea>
				<button onClick="window.print()" id="printbutton"><?= JText::_('INVOICEPRINT_PRINT') ?></button>
			</div>
		</div>
	<? } ?>
<? } else {
	$document=& JFactory::getDocument();
	$document->addCustomTag( '<link rel="stylesheet" type="text/css" media="screen, print" href="' . JURI::root(true) . '/media/' . $option . '/css/invoice_embed.css" />' );
	$document->addCustomTag( '<link rel="stylesheet" type="text/css" media="print" href="' . JURI::root(true) . '/media/' . $option . '/css/invoice_print.css" />' );
} ?>
	<div id="invoice_wrap">
		<div id="before_invoice_header"><?= $data['before_header'] ?></div>
		<div id="invoice_header">
			<?= $data['header'] ?>
		</div>
		<div id="after_invoice_header"><?= $data['after_header'] ?></div>
		<div id="address"><pre><?= $data['address'] ?></pre></div>
		<div id="invoice_details">
			<table id="invoice_details">
				<tr><th><?= JText::_('INVOICEPRINT_DATE') ?></th></tr>
				<tr><td><?= $data['invoice_date'] ?></td></tr>
				<tr><th><?= JText::_('INVOICEPRINT_ID') ?></th></tr>
				<tr><td><?= $data['invoice_id'] ?></td></tr>
				<tr><th><?= JText::_('INVOICEPRINT_REFERENCE_NUMBER') ?></th></tr>
				<tr><td><?= $data['invoice_number'] ?></td></tr>
			</table>
		</div>
		<div id="text_before_content"><?= $data['before_content'] ?></div>
		<div id="invoice_content">
			<table id="invoice_content">
				<tr>
					<th><?= JText::_('INVOICEPRINT_ITEM_NAME') ?></th>
					<th><?= JText::_('INVOICEPRINT_UNIT_PRICE') ?></th>
					<th><?= JText::_('INVOICEPRINT_QUANTITY') ?></th>
					<th><?= JText::_('INVOICEPRINT_TOTAL') ?></th>
				</tr>
				<?= implode( "\r\n", $data['itemlist'] ) ?>
				<?= implode( "\r\n", $data['totallist'] ) ?>
			</table>
		</div>
		<div id="text_after_content"><?= $data['after_content'] ?></div>
		<? if ( !empty( $data['recurringstatus'] ) && !empty( $data['invoice_billing_history'] ) ) { ?>
			<div id="invoice_paidstatus">
				<p><?= $data['paidstatus'] ?></p>
				<div id="invoice_billing_history">
					<table id="invoice_billing_history">
						<tr>
							<th><?= JText::_('HISTORY_COL3_TITLE') ?></th>
							<th><?= JText::_('HISTORY_COL2_TITLE') ?></th>
							<th><?= JText::_('HISTORY_COL4_TITLE') ?></th>
						</tr>
						<?= $data['invoice_billing_history'] ?>
					</table>
				</div>
			</div>
			<div id="invoice_recurringstatus"><?= $data['recurringstatus'] ?></div>
		<? } else { ?>
			<div id="invoice_paidstatus"><p><?= $data['paidstatus'] ?></p></div>
		<? } ?>
		<div id="before_footer"><?= $data['before_footer'] ?></div>
		<div id="footer"><?= $data['footer'] ?></div>
		<div id="after_footer"><?= $data['after_footer'] ?></div>
	</div>
<? if ( $standalone ) { ?>
</body>
<?
exit();
}
