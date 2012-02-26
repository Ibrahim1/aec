<?php
/**
 * @version $Id: expired.php
 * @package AEC - Account Control Expiration - Membership Manager
 * @subpackage Main Frontend
 * @copyright 2012 Copyright (C) David Deutsch
 * @author David Deutsch <skore@valanx.org> & Team AEC - http://www.valanx.org
 * @license GNU/GPL v.3 http://www.gnu.org/licenses/gpl.html or, at your option, any later version
 */

// Dont allow direct linking
( defined('_JEXEC') || defined( '_VALID_MOS' ) ) or die( 'Direct Access to this location is not allowed.' );

if ( $standalone ) { ?>
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
<?php } else {
	$document=& JFactory::getDocument();
	$document->addCustomTag( '<link rel="stylesheet" type="text/css" media="screen, print" href="' . JURI::root(true) . '/media/' . $option . '/css/invoice_embed.css" />' );
	$document->addCustomTag( '<link rel="stylesheet" type="text/css" media="print" href="' . JURI::root(true) . '/media/' . $option . '/css/invoice_print.css" />' );
} ?>
	<div id="invoice_wrap">
		<div id="before_invoice_header"><?php echo $data['before_header'] ?></div>
		<div id="invoice_header">
			<?php echo $data['header'] ?>
		</div>
		<div id="after_invoice_header"><?php echo $data['after_header'] ?></div>
		<div id="address"><pre><?php echo $data['address'] ?></pre></div>
		<div id="invoice_details">
			<table id="invoice_details">
				<tr><th><?php echo JText::_('INVOICEPRINT_DATE') ?></th></tr>
				<tr><td><?php echo $data['invoice_date'] ?></td></tr>
				<tr><th><?php echo JText::_('INVOICEPRINT_ID') ?></th></tr>
				<tr><td><?php echo $data['invoice_id'] ?></td></tr>
				<tr><th><?php echo JText::_('INVOICEPRINT_REFERENCE_NUMBER') ?></th></tr>
				<tr><td><?php echo $data['invoice_number'] ?></td></tr>
			</table>
		</div>
		<div id="text_before_content"><?php echo $data['before_content'] ?></div>
		<div id="invoice_content">
			<table id="invoice_content">
				<tr>
					<th><?php echo JText::_('INVOICEPRINT_ITEM_NAME') ?></th>
					<th><?php echo JText::_('INVOICEPRINT_UNIT_PRICE') ?></th>
					<th><?php echo JText::_('INVOICEPRINT_QUANTITY') ?></th>
					<th><?php echo JText::_('INVOICEPRINT_TOTAL') ?></th>
				</tr>
				<?php echo implode( "\r\n", $data['itemlist'] ) ?>
				<?php echo implode( "\r\n", $data['totallist'] ) ?>
			</table>
		</div>
		<div id="text_after_content"><?php echo $data['after_content'] ?></div>
		<?php if ( !empty( $data['recurringstatus'] ) && !empty( $data['invoice_billing_history'] ) ) { ?>
			<div id="invoice_paidstatus">
				<p><?php echo $data['paidstatus'] ?></p>
				<div id="invoice_billing_history">
					<table id="invoice_billing_history">
						<tr>
							<th><?php echo JText::_('HISTORY_COL3_TITLE') ?></th>
							<th><?php echo JText::_('HISTORY_COL2_TITLE') ?></th>
							<th><?php echo JText::_('HISTORY_COL4_TITLE') ?></th>
						</tr>
						<?php echo $data['invoice_billing_history'] ?>
					</table>
				</div>
			</div>
			<div id="invoice_recurringstatus"><?php echo $data['recurringstatus'] ?></div>
		<?php } else { ?>
			<div id="invoice_paidstatus"><p><?php echo $data['paidstatus'] ?></p></div>
		<?php } ?>
		<div id="before_footer"><?php echo $data['before_footer'] ?></div>
		<div id="footer"><?php echo $data['footer'] ?></div>
		<div id="after_footer"><?php echo $data['after_footer'] ?></div>
	</div>
<?php if ( $standalone ) { ?>
</body>
<?php
exit();
}
