<div id="confirmation_info">
	<table>
		<tr>
			<th><?php echo JText::_('CONFIRM_COL1_TITLE') ?></th>
			<th><?php echo JText::_('CONFIRM_COL2_TITLE') ?></th>
			<th><?php echo JText::_('CONFIRM_COL3_TITLE') ?></th>
		</tr>
		<tr>
			<td><?php echo $InvoiceFactory->userdetails ?></td>
			<td><p><?php echo $InvoiceFactory->plan->name ?></p></td>
			<td><p><?php echo $InvoiceFactory->payment->amount_format ?></p></td>
		</tr>
	<?php if ( empty( $userid ) && $tmpl->cfg['confirmation_changeusername'] ) { ?>
		<tr>
			<td><?php @include( $tmpl->tmpl( 'backdetailsbtn' ) ) ?></td>
			<?php if ( empty( $userid ) && $tmpl->cfg['confirmation_changeusage'] ) { ?>
				<td colspan="2"><?php @include( $tmpl->tmpl( 'backusagebtn' ) ) ?></td>
			<?php } ?>
		</tr>
	<?php } ?>
	<?php if ( !empty( $InvoiceFactory->plan->desc ) && $tmpl->cfg['confirmation_display_descriptions'] ) { ?>
		<tr>
			<td colspan="3" class="aec_left">
				<strong><?php echo JText::_('CONFIRM_YOU_HAVE_SELECTED') ?>:</strong><br />
				<?php echo stripslashes( $InvoiceFactory->plan->desc ) ?>
			</td>
		</tr>
	<?php } ?>
	<?php if ( $tmpl->cfg['confirmation_changeusage'] && !( empty( $userid ) && $tmpl->cfg['confirmation_changeusername'] ) ) { ?>
		<tr>
			<td colspan="3" class="aec_left"><?php @include( $tmpl->tmpl( 'backusagebtn' ) ) ?></td>
		</tr>
	<?php } ?>
	</table>
</div>
