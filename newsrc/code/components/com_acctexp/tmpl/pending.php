<?php
/**
 * @version $Id: pending.php
 * @package AEC - Account Control Expiration - Membership Manager
 * @subpackage Main Frontend
 * @copyright 2012 Copyright (C) David Deutsch
 * @author David Deutsch <skore@valanx.org> & Team AEC - http://www.valanx.org
 * @license GNU/GPL v.2 http://www.gnu.org/licenses/old-licenses/gpl-2.0.html or, at your option, any later version
 */

// Dont allow direct linking
( defined('_JEXEC') || defined( '_VALID_MOS' ) ) or die( 'Direct Access to this location is not allowed.' ); ?>

<?php
$actions =	JText::_('PENDING_OPENINVOICE')
. ' <a href="'
.  AECToolbox::deadsureURL( 'index.php?option=' . $option . '&task=repeatPayment&invoice='
. $invoice . '&amp;userid=' . $objUser->id ) . '" title="' . JText::_('GOTO_CHECKOUT') . '">'
. JText::_('GOTO_CHECKOUT')
. '</a>'
. ', ' . JText::_('GOTO_CHECKOUT_CANCEL') . ' '
. '<a href="'
. AECToolbox::deadsureURL( 'index.php?option=' . $option . '&task=cancelPayment&invoice='
. $invoice . '&amp;userid=' . $objUser->id . '&amp;pending=1' )
. '" title="' . JText::_('HISTORY_ACTION_CANCEL') . '">'
. JText::_('HISTORY_ACTION_CANCEL')
. '</a>';

if ( $reason !== 0 ) {
	$actions .= ' ' . $reason;
}

if ( $cfg->cfg['customtext_pending_keeporiginal'] ) { ?>
	<div class="componentheading"><?php echo JText::_('PENDING_TITLE'); ?></div>
	<p class="expired_dear"><?php echo sprintf( JText::_('DEAR'), $objUser->name ) . ','; ?></p>
	<p class="expired_date"><?php echo JText::_('WARN_PENDING'); ?></p>
	<?php
}
if ( $cfg->cfg['customtext_pending'] ) { ?>
	<p><?php echo $cfg->cfg['customtext_pending']; ?></p>
	<?php
} ?>
<div id="box_pending">
<?php
if ( strcmp($invoice, "none") === 0 ) { ?>
	<p><?php echo JText::_('PENDING_NOINVOICE'); ?></p>
	<div id="upgrade_button">
		<form action="<?php echo AECToolbox::deadsureURL( 'index.php?option=com_acctexp&task=renewSubscription', $cfg->cfg['ssl_signup'] ); ?>" method="post">
			<input type="hidden" name="option" value="<?php echo $option; ?>" />
			<input type="hidden" name="userid" value="<?php echo $objUser->id; ?>" />
			<input type="hidden" name="task" value="renewSubscription" />
			<input type="submit" class="button" value="<?php echo JText::_('PENDING_NOINVOICE_BUTTON');?>" />
			<?php echo JHTML::_( 'form.token' ); ?>
		</form>
	</div>
	<?php
} elseif ( $invoice ) { ?>
	<p><?php echo $actions; ?></p>
	<?php
} ?>
</div>
<div style="clear:both"></div>
