<?
/**
 * @version $Id: checkout.php
 * @package AEC - Account Control Expiration - Membership Manager
 * @subpackage Main Frontend
 * @copyright 2012 Copyright (C) David Deutsch
 * @author David Deutsch <skore@valanx.org> & Team AEC - http://www.valanx.org
 * @license GNU/GPL v.3 http://www.gnu.org/licenses/gpl.html or, at your option, any later version
 */

// Dont allow direct linking
( defined('_JEXEC') || defined( '_VALID_MOS' ) ) or die( 'Direct Access to this location is not allowed.' ) ?>

<div class="componentheading"><?php echo $InvoiceFactory->checkout['checkout_title'] ?></div>
<div id="checkout">
	<?
	$tmpl->custom( 'customtext_checkout', 'introtext', $InvoiceFactory->checkout );

	$InvoiceFactory->invoice->deformatInvoiceNumber();

	@include( $tmpl->tmpl( 'itemlist' ) );
	@include( $tmpl->tmpl( 'couponform' ) );

	if ( $makegift ) {
		@include( $tmpl->tmpl( 'giftform' ) );
	}

	if ( !empty( $params ) ) {
		@include( $tmpl->tmpl( 'paramsform' ) );
	}



if ( !empty( $var ) ) { ?>
<table width="100%" id="checkoutbox">
	<?php if ( ( strpos( $var, '<tr class="aec_formrow">' ) !== false ) || is_string( $InvoiceFactory->display_error ) ) { ?>
		<tr><th class="checkout_head"><?php echo $InvoiceFactory->checkout['customtext_checkout_table'] ?></th></tr>
	<?php } ?>
<?php if ( is_string( $InvoiceFactory->display_error ) ) { ?>
	<tr>
		<td class="checkout_error">
			<p><?php echo JText::_('CHECKOUT_ERROR_EXPLANATION') . ":" ?></p>
			<p><strong><?php echo $InvoiceFactory->display_error ?></strong></p>
			<p><?php echo JText::_('CHECKOUT_ERROR_FURTHEREXPLANATION') ?></p>
		</td>
	</tr>
<?php } ?>
<?php if ( !empty( $InvoiceFactory->checkout['processor_addin'] ) ) { ?>
	<tr>
		<td class="checkout_processor_addin">
			<?php echo $InvoiceFactory->checkout['processor_addin'] ?>
		</td>
	</tr>
<?php } ?>
<?php if ( is_string( $var ) ) { ?>
	<tr>
		<td class="checkout_action">
			<?php print $var ?>
		</td>
	</tr>
<?php } ?>
<?php } ?>
</table>
<table width="100%">
	<tr><td>
		<?
		if ( !empty( $InvoiceFactory->pp ) ) {
			if ( is_object( $InvoiceFactory->pp ) ) {
				$processor = $InvoiceFactory->pp;
				@include( $tmpl->tmpl( 'plans.processor_details' ) );
			}
		}
		?>
	</td></tr>
</table>
</div>
