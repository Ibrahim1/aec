<?php
/**
 * @version $Id: form.php
 * @package AEC - Account Control Expiration - Membership Manager
 * @subpackage Main Frontend
 * @copyright 2012 Copyright (C) David Deutsch
 * @author David Deutsch <skore@valanx.org> & Team AEC - http://www.valanx.org
 * @license GNU/GPL v.3 http://www.gnu.org/licenses/gpl.html or, at your option, any later version
 */

// Dont allow direct linking
defined('_JEXEC') or die( 'Direct Access to this location is not allowed.' ) ?>
<form id="form-confirm" action="<?php echo $tmpl->url( array( 'task' => 'saveSubscription') ) ?>" method="post">
	<div id="confirmation-extra">
		<?php if ( !empty( $InvoiceFactory->mi_error ) || !empty( $InvoiceFactory->mi_form ) || $InvoiceFactory->coupons['active'] || $makegift ) { ?>
		<?php @include( $tmpl->tmpl( 'mierror' ) ); ?>
		<div class="alert alert-success">
			<div id="confirmation-form">
				<?php
				@include( $tmpl->tmpl( 'miform' ) );

				@include( $tmpl->tmpl( 'couponform' ) );

				if ( $makegift ) { @include( $tmpl->tmpl( 'giftform' ) ); }
				?>
			</div>
		</div>
		<?php } ?>
	</div>
	<?php

	if ( !empty($InvoiceFactory->processorSelect) ) { ?>
		<div class="col-sm-12">
			<p>I want to pay with:</p>
			<input id="processor" name="processor" value="" type="hidden">
			<?php foreach ( $InvoiceFactory->processorSelect as $processor ) { ?>
				<div class="form-group">
					<label for="processor_<?php echo $processor->id; ?>_select">
						<input id="processor_<?php echo $processor->id; ?>_select" name="processor"<?php echo $processor->selected ? ' checked="checked"' : ''; ?> value="<?php echo $processor->id; ?>" type="radio">
					</label><?php echo $processor->name; ?>
				</div>
			<?php } ?>
		</div>
	<?php }

	@include( $tmpl->tmpl( 'btn' ) );

	@include( $tmpl->tmpl( 'processorinfo' ) );

	echo JHTML::_( 'form.token' ) ?>
</form>
