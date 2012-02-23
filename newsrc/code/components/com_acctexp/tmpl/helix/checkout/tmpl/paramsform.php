<table width="100%" id="paramsbox">
	<tr>
		<td class="append_button">
			<form action="<?php echo AECToolbox::deadsureURL( 'index.php?option=com_acctexp&amp;task=InvoiceAddParams', $tmpl->cfg['ssl_signup'] ) ?>" method="post">
				<?php echo $params ?>
				<input type="hidden" name="option" value="<?php echo $option ?>" />
				<input type="hidden" name="task" value="InvoiceAddParams" />
				<input type="hidden" name="invoice" value="<?php echo $InvoiceFactory->invoice->invoice_number ?>" />
				<input type="submit" class="button" value="<?php echo JText::_('BUTTON_APPEND') ?>" />
				<?php echo JHTML::_( 'form.token' ) ?>
			</form>
		</td>
	</tr>
</table>
