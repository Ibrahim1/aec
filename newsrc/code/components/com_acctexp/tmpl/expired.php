<?php if ( $cfg->cfg['customtext_expired_keeporiginal'] ) { ?>
<div class="componentheading"><?php echo JText::_('EXPIRED_TITLE'); ?></div>
<div id="expired_greeting">
	<p><?php echo sprintf( JText::_('DEAR'), $metaUser->cmsUser->name ); ?></p><p><?php
		if ( $is_trial ) {
			echo JText::_('EXPIRED_TRIAL');
		} else {
			echo JText::_('EXPIRED');
		}
		echo $expiration; ?>
	</p>
</div>
<?php } ?>

<?php if ( $cfg->cfg['customtext_expired'] ) { ?>
	<p><?php echo AECToolbox::rewriteEngine( $cfg->cfg['customtext_expired'], $metaUser ); ?></p>
<?php } ?>

<div id="box_expired">
	<div id="alert_level_1">
		<?php if ( $invoice ) { ?>
			<p>
				<?php echo JText::_('PENDING_OPENINVOICE'); ?>&nbsp;
				<a href="<?php echo AECToolbox::deadsureURL( 'index.php?option=' . $option . '&task=repeatPayment&invoice=' . $invoice . '&userid=' . $metaUser->userid.'&'. JUtility::getToken() .'=1' ); ?>" title="<?php echo JText::_('GOTO_CHECKOUT'); ?>"><?php echo JText::_('GOTO_CHECKOUT'); ?></a>
			</p>
		<?php } ?>
		<?php if ( $is_continue ) { ?>
			<div id="renew_button">
				<form action="<?php echo AECToolbox::deadsureURL( 'index.php?option=com_acctexp&task=renewSubscription'.$intro, $cfg->cfg['ssl_signup'] ); ?>" method="post">
				<input type="hidden" name="option" value="<?php echo $option; ?>" />
				<input type="hidden" name="userid" value="<?php echo $metaUser->userid; ?>" />
				<input type="hidden" name="usage" value="<?php echo $metaUser->focusSubscription->plan; ?>" />
				<input type="hidden" name="task" value="renewSubscription" />
				<input type="submit" class="button" value="<?php echo JText::_('RENEW_BUTTON_CONTINUE');?>" />
				<?php echo JHTML::_( 'form.token' ); ?>
				</form>
			</div>
		<?php } ?>
		<div id="renew_button">
			<form action="<?php echo AECToolbox::deadsureURL( 'index.php?option=com_acctexp&task=renewSubscription'.$intro, $cfg->cfg['ssl_signup'] ); ?>" method="post">
			<input type="hidden" name="option" value="<?php echo $option; ?>" />
			<input type="hidden" name="userid" value="<?php echo $metaUser->userid; ?>" />
			<input type="hidden" name="task" value="renewSubscription" />
			<input type="submit" class="button" value="<?php echo JText::_('RENEW_BUTTON');?>" />
			<?php echo JHTML::_( 'form.token' ); ?>
			</form>
		</div>
	</div>
</div>
