<?php
/**
 * @version $Id: invoices.php
 * @package AEC - Account Control Expiration - Membership Manager
 * @subpackage Main Frontend
 * @copyright 2012 Copyright (C) David Deutsch
 * @author David Deutsch <skore@valanx.org> & Team AEC - http://www.valanx.org
 * @license GNU/GPL v.2 http://www.gnu.org/licenses/old-licenses/gpl-2.0.html or, at your option, any later version
 */

// Dont allow direct linking
( defined('_JEXEC') || defined( '_VALID_MOS' ) ) or die( 'Direct Access to this location is not allowed.' );

if ( !empty( $metaUser->objSubscription->signup_date ) ) {
	echo '<p>' . JText::_('MEMBER_SINCE') . '&nbsp;' . HTML_frontend::DisplayDateInLocalTime( $metaUser->objSubscription->signup_date ) .'</p>';
}

if ( $properties['hascart'] ) { ?>
<form name="confirmForm" action="<?php echo AECToolbox::deadsureURL( 'index.php?option=' . $option . '&task=cart', $aecConfig->cfg['ssl_signup'] ); ?>" method="post">
<div id="update_button"><input type="image" src="<?php echo JURI::root(true) . '/media/com_acctexp/images/site/your_cart_button.png'; ?>" border="0" name="submit" alt="submit" /></div>
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
					echo '<p>' . JText::_('AEC_WILLRENEW') . ': ' . HTML_frontend::DisplayDateInLocalTime( $subscription->expiration ) . '</p>';
				} else {
					echo '<p>' . JText::_('AEC_WILLEXPIRE') . ': ' . HTML_frontend::DisplayDateInLocalTime( $subscription->expiration ) . '</p>';
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
					<?php echo HTML_frontend::DisplayDateInLocalTime( $metaUser->focusSubscription->expiration, true, true, $trial ); ?>
				</p>
				<?php
			}
			?>
		</div>
		<div id="days_left">
			<?php
			if ( strcmp( $properties['alert']['daysleft'], 'infinite' ) === 0 ) {
				$daysleft			= JText::_('RENEW_DAYSLEFT_INFINITE');
				$daysleft_append	= $trial ? JText::_('RENEW_DAYSLEFT_TRIAL') : JText::_('RENEW_DAYSLEFT');
			} elseif ( strcmp( $properties['alert']['daysleft'], 'excluded' ) === 0 ) {
				$daysleft			= JText::_('RENEW_DAYSLEFT_EXCLUDED');
				$daysleft_append	= '';
			} else {
				if ( $properties['alert']['daysleft'] >= 0 ) {
					$daysleft			= $properties['alert']['daysleft'];
					$daysleft_append	= $trial ? JText::_('RENEW_DAYSLEFT_TRIAL') : JText::_('RENEW_DAYSLEFT');
				} else {
					$daysleft			= $properties['alert']['daysleft'];
					$daysleft_append	= JText::_('AEC_DAYS_ELAPSED');
				}
			}
			?>
			<p><strong><?php echo $daysleft; ?></strong>&nbsp;&nbsp;<?php echo $daysleft_append; ?></p>
		</div>
		<?php
		if ( !empty( $properties['upgrade_button'] ) ) { ?>
			<div id="upgrade_button">
				<form action="<?php echo AECToolbox::deadsureURL( 'index.php?option=com_acctexp&task=renewsubscription', !empty( $aecConfig->cfg['ssl_signup'] ) ); ?>" method="post">
					<input type="hidden" name="option" value="<?php echo $option; ?>" />
					<input type="hidden" name="task" value="renewsubscription" />
					<input type="hidden" name="userid" value="<?php echo $metaUser->cmsUser->id; ?>" />
					<input type="submit" class="button" value="<?php echo JText::_('RENEW_BUTTON_UPGRADE');?>" />
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
