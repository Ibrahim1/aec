<?
/**
 * @version $Id: exception.php
 * @package AEC - Account Control Expiration - Membership Manager
 * @subpackage Main Frontend
 * @copyright 2012 Copyright (C) David Deutsch
 * @author David Deutsch <skore@valanx.org> & Team AEC - http://www.valanx.org
 * @license GNU/GPL v.2 http://www.gnu.org/licenses/old-licenses/gpl-2.0.html or, at your option, any later version
 */

// Dont allow direct linking
( defined('_JEXEC') || defined( '_VALID_MOS' ) ) or die( 'Direct Access to this location is not allowed.' )?>

<div class="componentheading"><?= $hasform ? JText::_('EXCEPTION_TITLE') : JText::_('EXCEPTION_TITLE_NOFORM') ?></div>
<div id="checkout">
	<?
	if ( $tmpl->cfg['customtext_exception_keeporiginal'] ) { ?>
		<p><?= $hasform ? JText::_('EXCEPTION_INFO') : ""?></p>
		<?
	}
	if ( $tmpl->cfg['customtext_exception'] ) { ?>
		<p><?= $tmpl->cfg['customtext_exception']?></p>
		<?
	} ?>
	<form action="<?= AECToolbox::deadsureURL( 'index.php?option=com_acctexp&amp;task=addressException', $tmpl->cfg['ssl_signup'] )?>" method="post">
	<table id="aec_checkout">
	<?
		foreach ( $InvoiceFactory->exceptions as $eid => $ex ) {
			if ( !empty( $ex['head'] ) ) {
				// Headline - What type is this term
				echo '<tr><th colspan="2">' . $ex['head'] . '</th></tr>';
			}

			if ( !empty( $ex['desc'] ) ) {
				// Subheadline - specify the details of this term
				echo '<tr><td colspan="2">' . $ex['desc'] . '</td></tr>';
			}

			// Iterate through costs
			foreach ( $ex['rows'] as $rid => $row ) {
				echo '<tr><td colspan="2">' . $aecHTML->createFormParticle( $eid.'_'.$rid ) . '</td></tr>';
			}

			// Draw Separator Line
			echo '<tr class="aec_term_row_sep"><td colspan="2"></td></tr>';
		}
	?>
	</table>

	<table width="100%" id="checkoutbox">
		<tr><th><?= JText::_('CONFIRM_TITLE')?></th></tr>
		<tr>
			<td class="checkout_action">
					<input type="hidden" name="option" value="<?= $option?>" />
					<input type="hidden" name="task" value="addressException" />
					<?
					if ( !empty( $InvoiceFactory->invoice->invoice_number ) ) {
						?><input type="hidden" name="invoice" value="<?= $InvoiceFactory->invoice->invoice_number?>" /><?
					}
					if ( !empty( $InvoiceFactory->cartobject->id ) ) {
						?><input type="hidden" name="cart" value="<?= $InvoiceFactory->cartobject->id?>" /><?
					}
					?>
					<input type="hidden" name="userid" value="<?= $InvoiceFactory->metaUser->userid?>" />
					<input type="submit" class="button" value="<?= JText::_('BUTTON_CONFIRM')?>" />
			</td>
		</tr>
	</table>
	<?= JHTML::_( 'form.token' )?>
	</form>
	<table width="100%">
		<tr><td>
			<?
			if ( !empty( $InvoiceFactory->pp ) ) {
				if ( is_object( $InvoiceFactory->pp ) ) {
					HTML_frontEnd::processorInfo( $option, $InvoiceFactory->pp, $tmpl->cfg['displayccinfo'] );
				}
			}
			?>
		</td></tr>
	</table>
</div>
