<?php
/**
 * @version $Id: overview.php
 * @package AEC - Account Control Expiration - Membership Manager
 * @subpackage Main Frontend
 * @copyright 2012 Copyright (C) David Deutsch
 * @author David Deutsch <skore@valanx.org> & Team AEC - http://www.valanx.org
 * @license GNU/GPL v.3 http://www.gnu.org/licenses/gpl.html or, at your option, any later version
 */

// Dont allow direct linking
( defined('_JEXEC') || defined( '_VALID_MOS' ) ) or die( 'Direct Access to this location is not allowed.' );

if ( !empty( $metaUser->objSubscription->signup_date ) ) {
	echo '<p>' . JText::_('MEMBER_SINCE') . '&nbsp;' . $tmpl->date( $metaUser->objSubscription->signup_date ) .'</p>';
}

if ( $properties['hascart'] ) { ?>
<form name="confirmForm" action="<?php echo AECToolbox::deadsureURL( 'index.php?option=' . $option . '&task=cart', $tmpl->cfg['ssl_signup'] ); ?>" method="post">
<div id="update_button"><button type="submit" class="btn"><?php echo JText::_('AEC_BTN_YOUR_CART') ?></button></div>
<?php echo JHTML::_( 'form.token' ); ?>
</form><br /><br />
<?php }

if ( !empty( $properties['showcheckout'] ) ) {
	?>
	<br /><br />
	<p>
		<?php echo JText::_('PENDING_OPENINVOICE'); ?>&nbsp;
		<a href="<?php echo AECToolbox::deadsureURL( 'index.php?option=' . $option . '&task=repeatPayment&invoice=' . $properties['showcheckout'] . '&userid=' . $metaUser->userid.'&'. JUtility::getToken() .'=1' ); ?>" title="<?php echo JText::_('GOTO_CHECKOUT'); ?>"><?php echo JText::_('GOTO_CHECKOUT'); ?></a>
	</p>
	<br /><br />
	<?php
}

if ( $metaUser->hasSubscription ) {
	if ( !empty( $subscriptions ) ) {
		foreach ( $subscriptions as $sid => $subscription ) {
			switch ( $sid ) {
				case 0:
					echo '<h2>' . JText::_('YOUR_SUBSCRIPTION') . '</h2>';
					break;
				case 1:
					echo '<div style="clear:both"></div><h2>' . JText::_('YOUR_FURTHER_SUBSCRIPTIONS') . '</h2>';
					break;
			}

			?><div class="subscription_info"><?php

			echo '<p><strong>' . $subscription->objPlan->getProperty( 'name' ) . '</strong></p>';
			echo '<p>' . $subscription->objPlan->getProperty( 'desc' ) . '</p>';
			if ( !empty( $subscription->objPlan->proc_actions ) ) {
				echo '<p>' . JText::_('PLAN_PROCESSOR_ACTIONS') . ' ' . implode( " | ", $subscription->objPlan->proc_actions ) . '</p>';
			}
			if ( !empty( $subscription->lifetime ) ) {
				echo '<p>' . JText::_('AEC_ISLIFETIME') . '</p>';
			} else {
				if ( $subscription->recurring && ( in_array( $subscription->status, array( 'Active', 'Trial' ) ) ) ) {
					echo '<p>' . JText::_('AEC_WILLRENEW') . ': ' . $tmpl->date( $subscription->expiration ) . '</p>';
				} else {
					echo '<p>' . JText::_('AEC_WILLEXPIRE') . ': ' . $tmpl->date( $subscription->expiration ) . '</p>';
				}
			}

			?></div><?php
		}
	}
	?>
	<div id="box_expired">
	<div id="alert_level_<?php echo $properties['alert']['level']; ?>">
		<div id="expired_greeting">
			<?php
			$lifetime = false;
			if ( !empty( $metaUser->objSubscription->lifetime ) ) {
				$lifetime = true;
			}

			if ( $lifetime ) { ?>
				<p><strong><?php echo JText::_('RENEW_LIFETIME'); ?></strong></p><?php
			} else { ?>
				<p>
					<?php echo $tmpl->date( $metaUser->focusSubscription->expiration, true, true, $trial ); ?>
				</p>
				<?php
			}
			?>
		</div>
		<div id="days_left">
			<p><strong><?php echo $daysleft; ?></strong>&nbsp;&nbsp;<?php echo $daysleft_append; ?></p>
		</div>
		<?php
		if ( !empty( $properties['upgrade_button'] ) ) { ?>
			<div id="upgrade_button">
				<form action="<?php echo AECToolbox::deadsureURL( 'index.php?option=com_acctexp&task=renewsubscription', !empty( $tmpl->cfg['ssl_signup'] ) ); ?>" method="post">
					<input type="hidden" name="option" value="<?php echo $option; ?>" />
					<input type="hidden" name="task" value="renewsubscription" />
					<input type="hidden" name="userid" value="<?php echo $metaUser->cmsUser->id; ?>" />
					<input type="submit" class="button btn" value="<?php echo JText::_('RENEW_BUTTON_UPGRADE');?>" />
					<?php echo JHTML::_( 'form.token' ); ?>
				</form>
			</div>
			<?php
		}
		?>
		</div>
	</div>
<?php
}
