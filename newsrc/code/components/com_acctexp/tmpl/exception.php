<?php


HTML_frontend::aec_styling( $option );

?>
<div class="componentheading"><?php echo $hasform ? JText::_('EXCEPTION_TITLE') : JText::_('EXCEPTION_TITLE_NOFORM') ; ?></div>
<div id="checkout">
	<?php
	if ( $cfg->cfg['customtext_exception_keeporiginal'] ) { ?>
		<p><?php echo $hasform ? JText::_('EXCEPTION_INFO') : ""; ?></p>
		<?php
	}
	if ( $cfg->cfg['customtext_exception'] ) { ?>
		<p><?php echo $cfg->cfg['customtext_exception']; ?></p>
		<?php
	} ?>
	<form action="<?php echo AECToolbox::deadsureURL( 'index.php?option=com_acctexp&amp;task=addressException', $cfg->cfg['ssl_signup'] ); ?>" method="post">
	<table id="aec_checkout">
	<?php
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
		<tr><th><?php echo JText::_('CONFIRM_TITLE'); ?></th></tr>
		<tr>
			<td class="checkout_action">
					<input type="hidden" name="option" value="<?php echo $option; ?>" />
					<input type="hidden" name="task" value="addressException" />
					<?php
					if ( !empty( $InvoiceFactory->invoice->invoice_number ) ) {
						?><input type="hidden" name="invoice" value="<?php echo $InvoiceFactory->invoice->invoice_number; ?>" /><?php
					}
					if ( !empty( $InvoiceFactory->cartobject->id ) ) {
						?><input type="hidden" name="cart" value="<?php echo $InvoiceFactory->cartobject->id; ?>" /><?php
					}
					?>
					<input type="hidden" name="userid" value="<?php echo $InvoiceFactory->metaUser->userid; ?>" />
					<input type="submit" class="button" value="<?php echo JText::_('BUTTON_CONFIRM'); ?>" />
			</td>
		</tr>
	</table>
	<?php echo JHTML::_( 'form.token' ); ?>
	</form>
	<table width="100%">
		<tr><td>
			<?php
			if ( !empty( $InvoiceFactory->pp ) ) {
				if ( is_object( $InvoiceFactory->pp ) ) {
					HTML_frontEnd::processorInfo( $option, $InvoiceFactory->pp, $cfg->cfg['displayccinfo'] );
				}
			}
			?>
		</td></tr>
	</table>
</div>
<div class="aec_clearfix"></div>
<?php
?>
