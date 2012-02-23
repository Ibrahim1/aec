<form name="confirmForm" action="<?php echo AECToolbox::deadsureURL( 'index.php?option=' . $option . '&task=saveSubscription', $tmpl->cfg['ssl_signup'] ) ?>" method="post">
<table>
	<tr>
		<td id="confirmation_extra">
			<?
			@include( $tmpl->tmpl( 'miform' ) );
			$tmpl->custom( 'customtext_confirm' );
			@include( $tmpl->tmpl( 'couponform' ) );
			?>
		</td>
	</tr>
	<?php if ( $makegift ) { @include( $tmpl->tmpl( 'giftform' ) ); } ?>
	<tr>
		<td id="confirmation_button"><?php @include( $tmpl->tmpl( 'confirmationbutton' ) ) ?></td>
	</tr>
	<tr><td>
		<table>
			<?php if ( is_object( $InvoiceFactory->pp ) ) {
				HTML_frontEnd::processorInfo( $option, $InvoiceFactory->pp, $tmpl->cfg['displayccinfo'] );
			} ?>
		</table>
	</td></tr>
</table>
<?php echo JHTML::_( 'form.token' ) ?>
</form>
