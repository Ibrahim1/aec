<?php
/**
 * @version $Id: confirmation.php
 * @package AEC - Account Control Expiration - Membership Manager
 * @subpackage Main Frontend
 * @copyright 2012 Copyright (C) David Deutsch
 * @author David Deutsch <skore@valanx.org> & Team AEC - http://www.valanx.org
 * @license GNU/GPL v.2 http://www.gnu.org/licenses/old-licenses/gpl-2.0.html or, at your option, any later version
 */

// Dont allow direct linking
( defined('_JEXEC') || defined( '_VALID_MOS' ) ) or die( 'Direct Access to this location is not allowed.' ); ?>

<div class="componentheading"><?php echo JText::_('CONFIRM_TITLE'); ?></div>
<?php
if ( !empty( $tmpl->cfg['tos'] ) ) { ?>
	<script type="text/javascript">
		/* <![CDATA[ */
		function submitPayment() {
			if ( document.confirmForm.tos.checked ) {
				document.confirmForm.submit();
			} else {
				alert("<?php echo html_entity_decode( JText::_('CONFIRM_TOS_ERROR') ); ?>");
			}
		}
		/* ]]> */
	</script>
	<?php
} ?>
<div id="confirmation">
	<div id="confirmation_info">
		<table>
			<tr>
				<th><?php echo JText::_('CONFIRM_COL1_TITLE'); ?></th>
				<th><?php echo JText::_('CONFIRM_COL2_TITLE'); ?></th>
				<th><?php echo JText::_('CONFIRM_COL3_TITLE'); ?></th>
			</tr>
			<tr>
				<td><?php echo $InvoiceFactory->userdetails; ?></td>
				<td><p><?php echo $InvoiceFactory->plan->name; ?></p></td>
				<td><p><?php echo $InvoiceFactory->payment->amount_format ?></p></td>
			</tr>
			<?php if ( empty( $userid ) && $tmpl->cfg['confirmation_changeusername'] ) { ?>
				<tr>
					<td><?php $this->tmpl( 'backdetailsbtn' ); ?></td>
					<?php if ( empty( $userid ) && $tmpl->cfg['confirmation_changeusage'] ) { ?>
						<td colspan="2"><?php $this->tmpl( 'backusagebtn' ); ?></td>
					<?php } ?>
				</tr>
			<?php } ?>
			<?php if ( !empty( $InvoiceFactory->plan->desc ) && $tmpl->cfg['confirmation_display_descriptions'] ) { ?>
			<tr>
				<td colspan="3" class="aec_left">
					<strong><?php echo JText::_('CONFIRM_YOU_HAVE_SELECTED'); ?>:</strong><br />
					<?php echo stripslashes( $InvoiceFactory->plan->desc ); ?>
				</td>
			</tr>
			<?php } ?>
			<?php if ( $tmpl->cfg['confirmation_changeusage'] && !( empty( $userid ) && $tmpl->cfg['confirmation_changeusername'] ) ) { ?>
			<tr>
				<td colspan="3" class="aec_left"><?php $this->tmpl( 'backusagebtn' ); ?></td>
			</tr>
			<?php } ?>
		</table>
	</div>
	<form name="confirmForm" action="<?php echo AECToolbox::deadsureURL( 'index.php?option=' . $option . '&task=saveSubscription', $tmpl->cfg['ssl_signup'] ); ?>" method="post">
	<table>
		<tr>
			<td id="confirmation_extra">
				<?php
				$tmpl->tmpl( 'miform' );
				$tmpl->custom( 'customtext_confirm' );
				$tmpl->tmpl( 'couponform' );
				?>
			</td>
		</tr>
		<?php $this->tmpl( 'giftform' ); ?>
		<tr>
			<td id="confirmation_button"><?php $this->tmpl( 'confirmationbutton' ); ?></td>
		</tr>
		<tr><td>
			<table>
				<?php if ( is_object( $InvoiceFactory->pp ) ) {
					HTML_frontEnd::processorInfo( $option, $InvoiceFactory->pp, $tmpl->cfg['displayccinfo'] );
				} ?>
			</table>
		</td></tr>
	</table>
	<?php echo JHTML::_( 'form.token' ); ?>
	</form>
</div>
