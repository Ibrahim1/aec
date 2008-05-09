<?php
/**
 * @version $Id: acctexp.html.php
 * @package AEC - Account Control Expiration - Subscription component for Joomla! OS CMS
 * @subpackage Main HTML Frontend
 * @copyright Copyright (C) 2004-2007, All Rights Reserved, Helder Garcia, David Deutsch
 * @author Helder Garcia <helder.garcia@gmail.com>, David Deutsch <skore@skore.de> & Team AEC - http://www.gobalnerd.org
 * @license GNU/GPL v.2 http://www.gnu.org/copyleft/gpl.html
 */
//
// Copyright (C) 2004-2007 Helder Garcia, David Deutsch
// All rights reserved.
// This source file is part of the Account Expiration Control Component, a  Joomla
// custom Component By Helder Garcia and David Deutsch - http://www.globalnerd.org
//
// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License (GPL)
// as published by the Free Software Foundation; either version 2
// of the License, or (at your option) any later version.
//
// Please note that the GPL states that any headers in files and
// Copyright notices as well as credits in headers, source files
// and output (screens, prints, etc.) can not be removed.
// You can extend them with your own credits, though...
//
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with this program; if not, write to the Free Software
// Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.

// Dont allow direct linking
defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );

global $mosConfig_absolute_path;

class HTML_frontEnd
{
	function HTML_frontEnd()
	{
		$this->aec_styling( 'com_acctexp' );
	}

	function aec_styling( $option )
	{
		global $mainframe;

		$html = '<link rel="stylesheet" type="text/css" media="all" href="'
		. $mainframe->getCfg( 'live_site' ) . '/components/' . $option . '/style.css" />';

		$mainframe->addCustomHeadTag( $html );
    	// $mainframe->setPageTitle( 'AEC - Account Evolution' ); // mic: reserved
 		$mainframe->appendMetaTag( 'description', 'AEC Account Expiration Control' );
    	$mainframe->appendMetaTag( 'keywords', 'AEC Account Expiration Control' );
	}

	function expired($option, $userid, $expiration, $name, $username, $invoice, $trial)
	{
		global $database, $aecConfig;

		if ($aecConfig->cfg['customtext_expired_keeporiginal']) {?>
			<div class="componentheading"><?php echo _EXPIRED_TITLE; ?></div>
			<div id="expired_greeting">
				<p><?php echo sprintf( _DEAR, $name ); ?></p><p><?php
					if ( $trial ) {
						echo _EXPIRED_TRIAL;
					} else {
						echo _EXPIRED;
					}
					echo $expiration; ?>
				</p>
			</div>
			<?php
		}
		if ( $aecConfig->cfg['customtext_expired'] ) { ?>
			<p><?php echo $aecConfig->cfg['customtext_expired']; ?></p>
			<?php
		} ?>
		<div id="box_expired">
			<div id="alert_level_1">
				<?php
				if ($invoice) { ?>
					<p>
						<?php echo _PENDING_OPENINVOICE; ?>&nbsp;
						<a href="<?php echo AECToolbox::deadsureURL( '/index.php?option=' . $option . '&amp;task=repeatPayment&amp;invoice=' . $invoice . '&amp;userid=' . $userid ); ?>" title="<?php echo _GOTO_CHECKOUT; ?>"><?php echo _GOTO_CHECKOUT; ?></a>
					</p>
					<?php
				} ?>
				<div id="renew_button">
					<form action="<?php echo AECToolbox::deadsureURL( '/index.php?option=com_acctexp&task=renewSubscription', $aecConfig->cfg['ssl_signup'] ); ?>" method="post">
					<input type="hidden" name="option" value="<?php echo $option; ?>" />
					<input type="hidden" name="userid" value="<?php echo $userid; ?>" />
					<input type="hidden" name="task" value="renewSubscription" />
					<input type="submit" class="button" value="<?php echo _RENEW_BUTTON;?>" />
					</form>
				</div>
			</div>
		</div>
		<?php
	}

	function pending( $option, $objUser, $invoice, $reason=0 )
	{
		global $database, $aecConfig;

		$actions =	_PENDING_OPENINVOICE
		. ' <a href="'
		.  AECToolbox::deadsureURL( '/index.php?option=' . $option . '&amp;task=repeatPayment&amp;invoice='
		. $invoice . '&amp;userid=' . $objUser->id ) . '" title="' . _GOTO_CHECKOUT . '">'
		. _GOTO_CHECKOUT
		. '</a>'
		. ', ' . _GOTO_CHECKOUT_CANCEL . ' '
		. '<a href="'
		. AECToolbox::deadsureURL( '/index.php?option=' . $option . '&amp;task=cancelPayment&amp;invoice='
		. $invoice . '&amp;userid=' . $objUser->id . '&amp;pending=1' )
		. '" title="' . _HISTORY_ACTION_CANCEL . '">'
		. _HISTORY_ACTION_CANCEL
		. '</a>';

		if ( $reason !== 0 ) {
			$actions .= ' ' . $reason;
		}

		if ( $aecConfig->cfg['customtext_pending_keeporiginal'] ) { ?>
			<div class="componentheading"><?php echo _PENDING_TITLE; ?></div>
			<p class="expired_dear"><?php echo sprintf( _DEAR, $objUser->name ) . ','; ?></p>
			<p class="expired_date"><?php echo _WARN_PENDING; ?></p>
			<?php
		}
		if ( $aecConfig->cfg['customtext_pending'] ) { ?>
			<p><?php echo $aecConfig->cfg['customtext_pending']; ?></p>
			<?php
		} ?>
		<div id="box_pending">
		<?php
		if ( strcmp($invoice, "none") === 0 ) { ?>
			<p><?php echo _PENDING_NOINVOICE; ?></p>
			<div id="upgrade_button">
				<form action="<?php echo AECToolbox::deadsureURL( '/index.php?option=com_acctexp&task=renewSubscription', $aecConfig->cfg['ssl_signup'] ); ?>" method="post">
					<input type="hidden" name="option" value="<?php echo $option; ?>" />
					<input type="hidden" name="userid" value="<?php echo $objUser->id; ?>" />
					<input type="hidden" name="task" value="renewSubscription" />
					<input type="submit" class="button" value="<?php echo _PENDING_NOINVOICE_BUTTON;?>" />
				</form>
			</div>
			<?php
		} elseif ( $invoice ) { ?>
			<p><?php echo $actions; ?></p>
			<?php
		} ?>
		</div>
		<?php
	}

	function subscriptionDetails( $option, $invoices, $metaUser, $recurring, $pp, $mi, $alert, $selected_plan = false, $subscriptions = null )
	{
		global $database, $aecConfig;
		?>

		<div class="componentheading"><?php echo _HISTORY_TITLE;?></div>
		<div id="subscription_details">
			<h2>
				<?php echo _HISTORY_SUBTITLE . '&nbsp;'
				. HTML_frontend::DisplayDateInLocalTime( $metaUser->objSubscription->signup_date ); ?>
			</h2>
			<table>
				<tr>
					<th><?php echo _HISTORY_COL1_TITLE;?></th>
					<th><?php echo _HISTORY_COL2_TITLE;?></th>
					<th><?php echo _HISTORY_COL3_TITLE;?></th>
					<th><?php echo _HISTORY_COL4_TITLE;?></th>
					<th><?php echo _HISTORY_COL5_TITLE;?></th>
				</tr>
				<?php
				foreach ( $invoices as $invoice ) { ?>
					<tr<?php echo $invoice['rowstyle'] ?>>
						<td><?php echo $invoice['invoice_number']; ?></td>
						<td><?php echo $invoice['amount'] . '&nbsp;' . $invoice['currency_code']; ?></td>
						<td><?php echo $invoice['transactiondate']; ?></td>
						<td><?php echo $invoice['processor']; ?></td>
						<td><?php echo $invoice['actions']; ?></td>
					</tr>
					<?php
				} ?>
			</table>
			<?php
			if ( $mi ) {
				echo $mi;
			}
			if ( is_object( $selected_plan ) ) { ?>
				<h2><?php echo _YOUR_SUBSCRIPTION; ?></h2>
				<p><strong><?php echo $selected_plan->getProperty( 'name' ); ?></strong></p>
				<p><?php echo $selected_plan->getProperty( 'desc' ); ?></p>
				<?php
				if ( isset( $selected_plan->proc_actions ) ) { ?>
					<p><?php echo _PLAN_PROCESSOR_ACTIONS . ' ' . implode( " | ", $selected_plan->proc_actions ); ?></p>
					<?php
				}
			}
			if ( is_array( $subscriptions ) ) { ?>
				<h2><?php echo _YOUR_FURTHER_SUBSCRIPTIONS; ?></h2>
				<?php
				foreach ( $subscriptions as $subscription ) { ?>
					<p><strong><?php echo $subscription->getProperty( 'name' ); ?></strong></p>
					<p><?php echo $subscription->getProperty( 'desc' ); ?></p>
					<?php
					if ( isset( $subscription->proc_actions ) ) { ?>
						<p><?php echo _PLAN_PROCESSOR_ACTIONS . ' ' . implode( " | ", $subscription->proc_actions ); ?></p>
						<?php
					}
				}
			}
			?>
			<div id="box_expired">
			<div id="alert_level_<?php echo $alert['level']; ?>">
				<div id="expired_greeting"><?php
					if ( $metaUser->objSubscription->lifetime == 1 ) { ?>
						<p><strong><?php echo _RENEW_LIFETIME; ?></strong></p><?php
					} else { ?>
						<p>
							<?php echo HTML_frontend::DisplayDateInLocalTime( $metaUser->focusSubscription->expiration, true, true ); ?>
						</p>
						<?php
					} ?>
				</div>
				<div id="days_left">
					<?php
					if ( strcmp( $alert['daysleft'], 'infinite' ) === 0 ) {
						$daysleft			= _RENEW_DAYSLEFT_INFINITE;
						$daysleft_append	= _RENEW_DAYSLEFT;
					} elseif ( strcmp( $alert['daysleft'], 'excluded' ) === 0 ) {
						$daysleft			= _RENEW_DAYSLEFT_EXCLUDED;
						$daysleft_append	= '';
					} else {
						if ( $alert['daysleft'] >= 0 ) {
							$daysleft			= $alert['daysleft'];
							$daysleft_append	= _RENEW_DAYSLEFT;
						} else {
							$daysleft			= $alert['daysleft'];
							$daysleft_append	= _AEC_DAYS_ELAPSED;
						}
					} ?>
					<p><strong><?php echo $daysleft; ?></strong>&nbsp;&nbsp;<?php echo $daysleft_append; ?></p>
				</div>
				<?php
				if ( $recurring == 0 ) { ?>
					<div id="upgrade_button">
						<form action="<?php echo AECToolbox::deadsureURL( '/index.php?option=com_acctexp&amp;task=renewsubscription', $aecConfig->cfg['ssl_signup'] ); ?>" method="post">
							<input type="hidden" name="option" value="<?php echo $option; ?>" />
							<input type="hidden" name="task" value="renewsubscription" />
							<input type="hidden" name="userid" value="<?php echo $metaUser->cmsUser->id; ?>" />
							<input type="submit" class="button" value="<?php echo _RENEW_BUTTON_UPGRADE;?>" />
						</form>
					</div>
					<?php
				} else { ?>
					<p><strong><?php echo _RENEW_INFO; ?></strong></p>
					<?php
					if ( is_object( $pp ) ) {
						if ( isset( $pp->info['cancel_info'] ) ) { ?>
							<p><?php echo $pp->info['cancel_info'];?></p>
							<?php
						}
					}
				} ?>
				</div>
			</div>
		</div>
		<?php
	}

	function notAllowed( $option, $processors, $registerlink, $loggedin = 0 )
	{
		global $database, $aecConfig;

		if ( !is_object( $this ) ) {
			HTML_frontEnd::aec_styling();
		} ?>
		<?php
		if ( $aecConfig->cfg['customtext_notallowed_keeporiginal'] ) {?>
			<div class="componentheading"><?php echo _NOT_ALLOWED_HEADLINE; ?></div>
			<p>
				<?php
				if ( $loggedin ) {
					echo _NOT_ALLOWED_FIRSTPAR_LOGGED; ?>&nbsp;
					<a href="<?php echo $registerlink; ?>" title="<?php echo _NOT_ALLOWED_REGISTERLINK_LOGGED; ?>"><?php echo _NOT_ALLOWED_REGISTERLINK_LOGGED; ?></a>
					<?php
				} else {
					echo _NOT_ALLOWED_FIRSTPAR; ?>&nbsp;
					<a href="<?php echo $registerlink; ?>" title="<?php echo _NOT_ALLOWED_REGISTERLINK; ?>"><?php echo _NOT_ALLOWED_REGISTERLINK; ?></a>
					<?php
				} ?>
			</p>
			<?php
		}
		if ( $aecConfig->cfg['customtext_notallowed'] ) { ?>
			<?php echo $aecConfig->cfg['customtext_notallowed']; ?>
			<?php
		} ?>
		<p></p>
		<?php
		if ( is_array( $processors ) ) { ?>
			<p><?php echo _NOT_ALLOWED_SECONDPAR; ?></p>
			<table id="cc_list">
				<?php
				foreach ( $processors as $processor ) {
					HTML_frontEnd::processorInfo( $option, $processor, $aecConfig->cfg['displayccinfo'] );
				} ?>
			</table>
			<?php
		}
	}

	function processorInfo( $option, $processorObj, $displaycc = 1 )
	{
		global $mosConfig_live_site; ?>

		<tr>
			<td class="cc_gateway">
				<p align="center"><img src="<?php echo $mosConfig_live_site . '/components/' . $option . '/images/gwlogo_' . $processorObj->processor_name . '.png" alt="' . $processorObj->processor_name . '" title="' . $processorObj->processor_name; ?>" /></p>
			</td>
			<td class="cc_icons">
				<p>
					<?php
					if ( isset( $processorObj->info['description'] ) ) {
						echo $processorObj->info['description'];
					} ?>
				</p>
			</td>
		</tr>
		<?php
		if ( $displaycc ) { ?>
			<tr>
				<td class="cc_gateway"></td>
				<td class="cc_icons">
					<?php echo Payment_HTML::getCCiconsHTML ( $option, $processorObj->info['cc_list'] ); ?>
				</td>
			</tr>
			<?php
		}
	}

	/**
	 * Formats a given date
	 *
	 * @param string	$SQLDate
	 * @param bool		$check		check time diference
	 * @param bool		$display	out with text (only in combination with $check)
	 * @return formatted date
	 */
	function DisplayDateInLocalTime( $SQLDate, $check = false, $display = false )
	{
		global $aecConfig;

		if ( $SQLDate == '' ) {
			return _AEC_EXPIRE_NOT_SET;
		} else {
			global $database;

			$retVal = strftime( $aecConfig->cfg['display_date_frontend'], strtotime( $SQLDate ) );

			if ( $check ) {
				$timeDif = strtotime( $SQLDate ) - time();
				if ( $timeDif < 0 ) {
					$retVal = _AEC_EXPIRE_PAST . ':&nbsp;<strong>' . $retVal . '</strong>';
				} elseif ( ( $timeDif >= 0 ) && ( $timeDif < 86400 ) ) {
					$retVal = _AEC_EXPIRE_TODAY;
				} else {
					$retVal = _AEC_EXPIRE_FUTURE . ': ' . $retVal;
				}
			}

			return $retVal;
		}
	}

}

class HTML_Results
{
	function thanks( $option, $msg )
	{
		HTML_frontend::aec_styling( $option );
		?>
		<div class="componentheading"><?php echo _THANKYOU_TITLE; ?></div>
		<div id="thankyou_page">
			<p><?php echo $msg; ?></p>
		</div>
		<?php
	}

	function cancel( $option )
	{
		HTML_frontend::aec_styling( $option ); ?>
		<div class="componentheading"><?php echo _CANCEL_TITLE; ?></div>
		<div id="cancel_page">
			<p><?php echo _CANCEL_MSG; ?></p>
		</div>
		<?php
	}
}

class Payment_HTML
{
	function selectSubscriptionPlanForm( $option, $userid, $plans, $expired, $passthrough = false, $register = false )
	{
		global $mosConfig_live_site, $database, $aecConfig;

		HTML_frontend::aec_styling( $option );
		?>

		<div class="componentheading"><?php echo _PAYPLANS_HEADER; ?></div>
		<?php
		if ( $aecConfig->cfg['customtext_plans'] ) { ?>
			<p><?php echo $aecConfig->cfg['customtext_plans']; ?></p>
			<?php
		} ?>
		<div class="subscriptions">
			<?php
			foreach ( $plans as $plan ) { ?>
				<table>
					<th><?php echo $plan['name']; ?></th>
					<tr><td><?php echo $plan['desc']; ?></td></tr>
					<tr>
						<td class="buttons">
							<?php echo Payment_HTML::getPayButtonHTML( $plan['gw'], $plan['id'], $userid, $passthrough, $register ); ?>
						</td>
					</tr>
				</table>
				<?php
			}
			?>
		</div>
		<?php
	}

	function getPayButtonHTML( $gw, $planid, $userid, $passthrough = false, $register = false )
	{
		global $mosConfig_live_site, $database, $my, $aecConfig;

		$html_code = '';

		foreach ( $gw as $processor ) {
			$gw_current = strtolower( $processor['name'] );
			$hidden = array();

			if ( $register ) {
				if ( GeneralInfoRequester::detect_component( 'CB' ) || GeneralInfoRequester::detect_component( 'CBE' ) ) {
					$option	= 'com_comprofiler';
					$task	= 'registers';
				} else {
					$option	= 'com_acctexp';
					$task	= 'subscribe';
				}
			} else {
				$option		= 'com_acctexp';
				$task		= 'confirm';
			}

			$urlbutton = $mosConfig_live_site . '/components/com_acctexp/images/gw_button_' . $processor['name'] . '.png';

			$html_code .= '<div class="gateway_button">' . "\n"
			. '<form action="' . AECToolbox::deadsureURL( '/index.php?option=' . $option . '&amp;task=' . $task, $aecConfig->cfg['ssl_signup'] ) . '"'
			. ' method="post">' . "\n"
			. '<input type="image" src="' . $urlbutton;
			if ( !empty( $processor['statement'] ) ) {
				$html_code .= '" border="0" name="submit" alt="' . $processor['statement'] . '" />' . "\n";
			} else {
				$html_code .= '" border="0" name="submit" alt="' . $processor['name'] . '" />' . "\n";
			}

			$hidden['option']		= $option;
			$hidden['task']			= $task;
			$hidden['processor']	= strtolower( $processor['name'] );
			$hidden['usage']		= $planid;
			$hidden['userid']		= $userid ? $userid : 0;

			if ( isset( $processor['recurring'] ) ) {
				if ( $processor['recurring'] ) {
					$hidden['recurring'] = 1;
				} else {
					$hidden['recurring'] = 0;
				}
			}

			// Rewrite Passthrough
			if ( $passthrough != false ) {
				foreach ( $passthrough as $key => $array ) {
					$hidden[$array[0]] = $array[1];
				}
			}

			// Assemble hidden fields
			foreach ( $hidden as $key => $value ) {
				$html_code .= '<input type="hidden" name="' . $key . '" value="' . $value . '" />' . "\n";
			}

			$html_code .= '</form></div>' . "\n";
		}

		return $html_code;
	}

	function getCCiconsHTML( $option, $cc_list )
	{
		global $mosConfig_live_site;
		// This function first looks whether a specific gateway has been asked for,
		// if that is the case, it creates a cc list that is to be passed on.
		// If there was no gateway specified, it passes the cc list on to the next function
		$html_code	= '';
		// Function to create a string of images by a list of CreditCards
		$cc_array = explode( ',', $cc_list );

		for( $i = 0; $i < count( $cc_array ); $i++ ) {
			$html_code .= '<img src="' . $mosConfig_live_site . '/components/' . $option
			. '/images/cc_icons/ccicon_' . $cc_array[$i] . '.png"'
			. ' alt="' . $cc_array[$i] . '"'
			. ' title="' . $cc_array[$i] . '"'
			. ' class="cc_icon" />';
		}

		return $html_code;
	}

	function promptpassword( $option, $var, $wrong )
	{
		global $database, $aecConfig;
		?>
		<div id="box_pending">
			<p><?php echo _AEC_PROMPT_PASSWORD; ?></p>
			<?php
				if ($wrong ) {
					echo '<p><strong>' . _AEC_PROMPT_PASSWORD_WRONG . '</strong></p>';
				}
			?>
			<div id="upgrade_button">
				<form action="<?php echo AECToolbox::deadsureURL( '/index.php?option=com_acctexp', $aecConfig->cfg['ssl_signup'] ); ?>" method="post">
					<input type="password" size="10" class="inputbox" id="password" name="password"/>
					<?php
						foreach ( $var as $name => $array ) {
							echo '<input type="hidden" name="' . $array[0] . '" value="' . $array[1] . '" />';
						}
					?>
					<input type="submit" class="button" value="<?php echo _AEC_PROMPT_PASSWORD_BUTTON;?>" />
				</form>
			</div>
		</div>
		<?php
	}

	function confirmForm( $option, $InvoiceFactory, $user, $passthrough = false)
	{
		global $database, $aecConfig;

		HTML_frontend::aec_styling( $option );
		?>

		<div class="componentheading"><?php echo _CONFIRM_TITLE; ?></div>
		<?php
		if ( !empty( $aecConfig->cfg['tos'] ) ) { ?>
			<script type="text/javascript">
				/* <![CDATA[ */
				function submitPayment() {
					if ( document.confirmForm.tos.checked ) {
						document.confirmForm.submit();
					} else {
						alert("<?php echo html_entity_decode( _CONFIRM_TOS_ERROR ); ?>");
					}
				}
				/* ]]> */
			</script>
			<?php
		} ?>
		<div id="confirmation">
			<table>
				<tr>
					<th><?php echo _CONFIRM_COL1_TITLE; ?></th>
					<th><?php echo _CONFIRM_COL2_TITLE; ?></th>
					<th><?php echo _CONFIRM_COL3_TITLE; ?></th>
				</tr>
				<tr>
					<td>
						<?php
						if ( $user->name ) { ?>
							<p><?php echo _CONFIRM_ROW_NAME; ?> <?php echo $user->name; ?></p>
							<?php
						} ?>
						<p><?php echo _CONFIRM_ROW_USERNAME; ?> <?php echo $user->username; ?></p>
						<p><?php echo _CONFIRM_ROW_EMAIL; ?> <?php echo $user->email; ?></p>
					</td>
					<td><p><?php echo $InvoiceFactory->objUsage->name; ?></p></td>
					<td><p>
						<?php
						if ( ( !empty( $InvoiceFactory->payment->amount ) && ( $InvoiceFactory->payment->amount != '0.00' ) ) && !$InvoiceFactory->payment->freetrial ) {
							echo AECToolbox::formatAmount( $InvoiceFactory->payment->amount, $InvoiceFactory->payment->currency); ?>&nbsp;-&nbsp;
							<?php
						} elseif ( $InvoiceFactory->payment->freetrial ) {
							echo _CONFIRM_FREETRIAL . '&nbsp;-&nbsp;';
						}
						echo $InvoiceFactory->payment->method_name; ?>
					</p></td>
				</tr>
				<tr>
					<td colspan="4" class="confirmation_description"><?php echo stripslashes( $InvoiceFactory->objUsage->desc ); ?></td>
				</tr>
			</table>
			<?php
			if ( $aecConfig->cfg['customtext_confirm'] ) { ?>
				<p><?php echo $aecConfig->cfg['customtext_confirm']; ?></p>
				<?php
			}
			if ( $aecConfig->cfg['customtext_confirm_keeporiginal'] ) { ?>
				<p><?php echo _CONFIRM_INFO; ?></p>
				<?php
			}
			if ( $InvoiceFactory->coupons['active'] ) {
				if ( !empty( $aecConfig->cfg['confirmation_coupons'] ) ) {
					?><p><?php echo _CONFIRM_COUPON_INFO_BOTH; ?></p><?php
				} else {
					?><p><?php echo _CONFIRM_COUPON_INFO; ?></p><?php
				}
			} ?>
			<table>
				<tr>
					<td class="confirmation_button">
						<form name="confirmForm" action="<?php echo AECToolbox::deadsureURL( '/index.php?option=' . $option, $aecConfig->cfg['ssl_signup'] ); ?>" method="post">
						<?php if ( !empty( $aecConfig->cfg['confirmation_coupons'] ) ) { ?>
							<strong><?php echo _CHECKOUT_COUPON_CODE; ?></strong>
							<input type="text" size="20" name="coupon_code" class="inputbox" value="" />
						<?php } ?>
						<input type="hidden" name="option" value="<?php echo $option; ?>" />
						<input type="hidden" name="userid" value="<?php echo $user->id ? $user->id : 0; ?>" />
						<input type="hidden" name="task" value="saveSubscription" />
						<input type="hidden" name="usage" value="<?php echo $InvoiceFactory->usage; ?>" />
						<input type="hidden" name="processor" value="<?php echo $InvoiceFactory->processor; ?>" />
						<?php if ( isset( $InvoiceFactory->recurring ) ) { ?>
						<input type="hidden" name="recurring" value="<?php echo $InvoiceFactory->recurring;?>" />
						<?php } ?>
						<?php
						if ( !empty( $aecConfig->cfg['tos'] ) ) { ?>
							<p><input name="tos" type="checkbox" /><?php echo sprintf( _CONFIRM_TOS, $aecConfig->cfg['tos'] ); ?></p>
							<input type="button" onclick="javascript:submitPayment()" class="button" value="<?php echo _BUTTON_CONFIRM; ?>" />
							<?php
						} else { ?>
							<input type="submit" class="button" value="<?php echo _BUTTON_CONFIRM; ?>" />
							<?php
						}
						if ( $passthrough != false ) {
							foreach ( $passthrough as $key => $array ) { ?>
								<input type="hidden" name="<?php echo $array[0]; ?>" value="<?php echo $array[1]; ?>" />
								<?php
							}
						} ?>
						</form>
					</td>
				</tr>
				<tr><td>
					<table>
						<?php
						if ( is_object( $InvoiceFactory->pp ) ) {
							HTML_frontEnd::processorInfo( $option, $InvoiceFactory->pp );
						} ?>
					</table>
				</td></tr>
			</table>
		</div>
		<?php
	}

	function checkoutForm( $option, $var, $params = null, $InvoiceFactory, $repeat = 0 )
	{
		global $database, $aecConfig;

		HTML_frontend::aec_styling( $option );

		$terms = $InvoiceFactory->terms->getTerms();

		$introtext = '_CHECKOUT_INFO' . ( $repeat ? '_REPEAT' : '' );
		?>

		<div class="componentheading"><?php echo _CHECKOUT_TITLE; ?></div>
		<div id="checkout">
			<?php
			if ( $aecConfig->cfg['customtext_checkout_keeporiginal'] ) { ?>
				<p><?php echo constant( $introtext ); ?></p>
				<?php
			}
			if ( $aecConfig->cfg['customtext_checkout'] ) { ?>
				<p><?php echo $aecConfig->cfg['customtext_checkout']; ?></p>
				<?php
			} ?>
<?php
/**
 * Ideas:
 * - Mark Recurring periods and how often the recurring happens
 * - Do not forget Processor-specific hooks (like info fields
 *   etc.) - people WILL ask about them anyhoo...
 * - Checkout Button may also be influenced by processors
 *
 */
?>

			<table id="aec_checkout">
			<?php
				foreach ( $terms as $tid => $term ) {
					$ttype = 'aec_termtype_' . $term->type;

					//$future = ( $tid > $InvoiceFactory->terms->pointer ) ? '&nbsp;('._AEC_CHECKOUT_FUTURETERM.')' : '';
					$applicable = ( $tid >= $InvoiceFactory->terms->pointer ) ? '' : '&nbsp;('._AEC_CHECKOUT_NOTAPPLICABLE.')';

					$current = ( $tid == $InvoiceFactory->terms->pointer ) ? ' current_period' : '';

					// Headline - What type is this term
					echo '<tr class="aec_term_typerow' . $current . '"><th colspan="2" class="' . $ttype . '">' . constant( strtoupper( '_' . $ttype ) ) . $applicable . '</th></tr>';
					// Subheadline - specify the details of this term
					echo '<tr class="aec_term_durationrow' . $current . '"><td colspan="2" class="aec_term_duration">' . _AEC_CHECKOUT_DURATION . ': ' . $term->renderDuration() . '</td></tr>';

					// Iterate through costs
					foreach ( $term->renderCost() as $citem ) {
						$t = constant( strtoupper( '_aec_checkout_' . $citem->type ) );
						$c = AECToolbox::formatAmount( $citem->cost['amount'], $InvoiceFactory->payment->currency );

						switch ( $citem->type ) {
							case 'discount':
								$ta = $t;
								if ( !empty( $citem->cost['details'] ) ) {
									$ta .= '&nbsp;(' . $citem->cost['details'] . ')';
								}
								$ta .= '&nbsp;[<a href="'
									. AECToolbox::deadsureURL( '/index.php?option=' . $option
									. '&amp;task=InvoiceRemoveCoupon&amp;invoice=' . $InvoiceFactory->invoice
									. '&amp;coupon_code=' . $citem->cost['coupon'] )
									. '" title="' . _CHECKOUT_INVOICE_COUPON_REMOVE . '">'
									. _CHECKOUT_INVOICE_COUPON_REMOVE . '</a>]';

								$t = $ta;

								$c = AECToolbox::formatAmount( $citem->cost['amount'] );

								// Strip out currency symbol and replace with blanks
								if ( !$aecConfig->cfg['amount_currency_symbolfirst'] ) {
									$strlen = 2;

									if ( !$aecConfig->cfg['amount_currency_symbol'] ) {
										$strlen = 1 + strlen( $InvoiceFactory->payment->currency ) * 2;
									}

									for( $i=0; $i<=$strlen;$i++ ) {
										$c .= '&nbsp;';
									}
								}
								break;
							case 'cost': break;
							case 'total': break;
							default: break;
						}

						echo '<tr class="aec_term_' . $citem->type . 'row' . $current . '"><td class="aec_term_' . $citem->type . 'title">' . $t . ':' . '</td><td class="aec_term_' . $citem->type . 'amount">' . $c . '</td></tr>';
					}

					// Draw Separator Line
					echo '<tr class="aec_term_row_sep"><td colspan="2"></td></tr>';
				}
			?>
			</table>

			<?php
			if ( !empty( $aecConfig->cfg['enable_coupons'] ) ) { ?>
				<table width="100%" id="couponsbox">
					<tr>
						<td class="couponinfo">
							<strong><?php echo _CHECKOUT_COUPON_CODE; ?></strong>
						</td>
					</tr>
					<tr>
						<td class="coupondetails">
							<?php
							if ( isset( $InvoiceFactory->terms->errors ) ) {
								foreach ( $InvoiceFactory->terms->errors as $error ) { ?>
									<div class="couponerror">
										<p>
											<strong><?php echo _COUPON_ERROR_PRETEXT; ?></strong>
											&nbsp;
											<?php echo $error; ?>
										</p>
									</div>
									<?php
								}
							} ?>
							<p><?php echo _CHECKOUT_COUPON_INFO; ?></p>
							<form action="<?php echo AECToolbox::deadsureURL( '/index.php?option=com_acctexp&amp;task=InvoiceAddCoupon', $aecConfig->cfg['ssl_signup'] ); ?>" method="post">
								<input type="text" size="20" name="coupon_code" class="inputbox" value="" />
								<input type="hidden" name="option" value="<?php echo $option; ?>" />
								<input type="hidden" name="task" value="InvoiceAddCoupon" />
								<input type="hidden" name="invoice" value="<?php echo $InvoiceFactory->invoice; ?>" />
								<input type="submit" class="button" value="<?php echo _BUTTON_APPLY; ?>" />
							</form>
						</td>
					</tr>
				</table>
				<?php
			}
			if ( !empty( $params ) ) { ?>
				<table width="100%" id="paramsbox">
					<tr>
						<td class="append_button">
							<form action="<?php echo AECToolbox::deadsureURL( '/index.php?option=com_acctexp&amp;task=InvoiceAddParams', $aecConfig->cfg['ssl_signup'] ); ?>" method="post">
								<?php echo $params; ?>
								<input type="hidden" name="option" value="<?php echo $option; ?>" />
								<input type="hidden" name="task" value="InvoiceAddParams" />
								<input type="hidden" name="invoice" value="<?php echo $InvoiceFactory->invoice; ?>" />
								<input type="submit" class="button" value="<?php echo _BUTTON_APPEND; ?>" />
							</form>
						</td>
					</tr>
				</table>
				<?php
			} ?>
		<table width="100%" id="checkoutbox">
			<tr><th><?php echo _CHECKOUT_TITLE; ?></th></tr>
			<tr>
				<td class="checkout_action">
					<?php
					print $var;
					?>
				</td>
			</tr>
		</table>
		<table width="100%">
			<?php
			if ( is_object( $InvoiceFactory->pp ) ) {
				HTML_frontEnd::processorInfo( $option, $InvoiceFactory->pp, $aecConfig->cfg['displayccinfo'] );
			} ?>
		</table>
		</div>
		<?php
	}

	function printInvoice( $option, $var, $params = null, $InvoiceFactory, $repeat = 0 )
	{
		global $database, $aecConfig;

		HTML_frontend::aec_styling( $option );
		?>
		<h3>Name</h3>
		<p>Details</p>
		<p>Details</p>
		<h4>Order Information </h4>
		<table width="100%" border="0" cellspacing="5" cellpadding="0">
			<tr>
				<td>Invoice Number</td>
				<td>{inv_no}</td>
			</tr>
			<tr>
				<td width="74%">Order Date </td>
				<td width="26%">{date}</td>
			</tr>
		</table>
		<h4>Customer Details	</h4>
		<table border="0" cellspacing="5" cellpadding="0">
			<tr>
				<td width="146">Name:</td>
				<td width="281">{name}</td>
			</tr>
			<tr>
				<td>Username:</td>
				<td>{username}</td>
			</tr>
			<tr>
				<td>Company:</td>
				<td>{company}</td>
			</tr>
			<tr>
				<td>Address:</td>
				<td>{address}</td>
			</tr>
			<tr>
				<td>City:</td>
				<td>{city}</td>
			</tr>
			<tr>
				<td>State:</td>
				<td>{state}</td>
			</tr>
			<tr>
				<td>Postcode:</td>
				<td>{postcode}</td>
			</tr>
			<tr>
				<td>Country:</td>
				<td>{country}</td>
			</tr>
			<tr>
				<td>Phone:</td>
				<td>{phone}</td>
			</tr>
			<tr>
				<td>Email:</td>
				<td>{email}</td>
			</tr>
		</table>
		<h4>Order Items	</h4>
		<table style="border-width: 0px; width: 100%" border="0" cellspacing="5" cellpadding="0">
			<tbody>
				<tr>
					<td>{invoice_desc}:</td>
					<td align="right">{cost}			</td>
				</tr>
				<tr>
					<td>GST (for Australian customers):</td>
					<td align="right">{gst}			</td>
				</tr>
				<tr>
					<td><strong>Total:</strong></td>
					<td align="right"><strong>{total}
					</strong></td>
				</tr>
			</tbody>
		</table>
		<h4>Payment Infomation </h4>
		<p>{pay_method}</p>
		<?php
	}

	function error( $option, $objUser, $invoice, $error=false, $suppressactions=false )
	{
		global $database, $aecConfig;

		if ( !$suppressactions ) {
			$actions =	_CHECKOUT_ERROR_OPENINVOICE
			. ' <a href="'
			.  AECToolbox::deadsureURL( '/index.php?option=' . $option . '&amp;task=repeatPayment&amp;invoice='
			. $invoice . '&amp;userid=' . $objUser->id ) . '" title="' . _GOTO_CHECKOUT . '">'
			. _GOTO_CHECKOUT
			. '</a>'
			. ', ' . _GOTO_CHECKOUT_CANCEL . ' '
			. '<a href="'
			. AECToolbox::deadsureURL( '/index.php?option=' . $option . '&amp;task=cancelPayment&amp;invoice='
			. $invoice . '&amp;userid=' . $objUser->id . '&amp;pending=1' )
			. '" title="' . _HISTORY_ACTION_CANCEL . '">'
			. _HISTORY_ACTION_CANCEL
			. '</a>'
			;
		} else {
			$actions = '';
		}
		?>

		<div class="componentheading"><?php echo _CHECKOUT_ERROR_TITLE; ?></div>
		<div id="box_pending">
			<p><?php echo _CHECKOUT_ERROR_EXPLANATION . ( $error ? ( ': ' . $error) : '' ); ?></p>
			<p><?php echo $actions; ?></p>
		</div>
		<?php
	}

    function errorAP( $option, $planid, $userid, $username, $name, $recurring )
    {
		HTML_frontend::aec_styling($option);?>
       	<table class="single_subscription">
       		<th class="heading"><?php echo _REGTITLE ?> <?php echo _ERRORCODE ?></th>
       		<tr><td class="description"><?php echo _FTEXTA ?><br /><?php echo _RECODE ?></td></tr>
			<tr><td class="buttons">
				<div class="gateway_button">
					<?php
					$html_code	= '';
					Payment_HTML::getPayButtonHTML( $gw->name, $gw->recurring, $gw->currency_code, $row->id, $userid, $username, $name, $html_code );
					echo $html_code; ?>
				</div>
				</td>
			</tr>
		</table>
		<?php
	}

    function generalError( $option )
    {
		HTML_frontend::aec_styling( $option );
   		echo _AEC_GEN_ERROR;
	}
}

function joomlaregisterForm($option, $useractivation)
{
	global $aecConfig, $mosConfig_absolute_path;

	if (  defined( 'JPATH_BASE' ) ) {
	?>
	<script type="text/javascript">
	<!--
		Window.onDomReady(function(){
			document.formvalidator.setHandler('passverify', function (value) { return ($('password').value == value); }	);
		});
	// -->
	</script>

	<form action="<?php echo JRoute::_( 'index.php?option=com_acctexp' ); ?>" method="post" id="josForm" name="josForm" class="form-validate">

	<div class="componentheading">
		<?php echo JText::_( 'Registration' ); ?>
	</div>

	<table cellpadding="0" cellspacing="0" border="0" width="100%" class="contentpane">
	<tr>
		<td width="30%" height="40">
			<label id="namemsg" for="name">
				<?php echo JText::_( 'Name' ); ?>:
			</label>
		</td>
	  	<td>
	  		<input type="text" name="name" id="name" size="40" value="" class="inputbox required" maxlength="50" /> *
	  	</td>
	</tr>
	<tr>
		<td height="40">
			<label id="usernamemsg" for="username">
				<?php echo JText::_( 'Username' ); ?>:
			</label>
		</td>
		<td>
			<input type="text" id="username" name="username" size="40" value="" class="inputbox required validate-username" maxlength="25" /> *
		</td>
	</tr>
	<tr>
		<td height="40">
			<label id="emailmsg" for="email">
				<?php echo JText::_( 'Email' ); ?>:
			</label>
		</td>
		<td>
			<input type="text" id="email" name="email" size="40" value="" class="inputbox required validate-email" maxlength="100" /> *
		</td>
	</tr>
	<tr>
		<td height="40">
			<label id="pwmsg" for="password">
				<?php echo JText::_( 'Password' ); ?>:
			</label>
		</td>
	  	<td>
	  		<input class="inputbox required validate-password" type="password" id="password" name="password" size="40" value="" /> *
	  	</td>
	</tr>
	<tr>
		<td height="40">
			<label id="pw2msg" for="password2">
				<?php echo JText::_( 'Verify Password' ); ?>:
			</label>
		</td>
		<td>
			<input class="inputbox required validate-passverify" type="password" id="password2" name="password2" size="40" value="" /> *
		</td>
	</tr>
	<?php
	if ( $aecConfig->cfg['use_recaptcha'] && !empty( $aecConfig->cfg['recaptcha_publickey'] ) ) {
		require_once( $mosConfig_absolute_path . '/components/com_acctexp/lib/recaptcha/recaptchalib.php' );
		?>
		<tr>
		<td height="40">
			<label>
			</label>
		</td>
		<td>
		<?php
		echo recaptcha_get_html( $aecConfig->cfg['recaptcha_publickey'] );
		?>
		</td>
		</tr>
		<?php
	}
	?>
	</table>
	<button class="button validate" type="submit"><?php echo JText::_('Register'); ?></button>
	<input type="hidden" name="task" value="saveRegistration" />
	<input type="hidden" name="id" value="0" />
	<input type="hidden" name="gid" value="0" />
	<input type="hidden" name="usage" value="<?php echo $_POST['usage'];?>" />
	<input type="hidden" name="processor" value="<?php echo $_POST['processor'];?>" />
	<?php if ( isset( $_POST['recurring'] ) ) { ?>
	<input type="hidden" name="recurring" value="<?php echo $_POST['recurring'];?>" />
	<?php } ?>
	<?php echo JHTML::_( 'form.token' ); ?>
	</form>
	<?php
	} else {
	// used for spoof hardening
	if ( function_exists( 'josSpoofValue' ) ) {
		$validate = josSpoofValue();
	} else {
		$validate = '';
	}
	?>
	<script type="text/javascript">
		/* <![CDATA[ */
		function submitbutton_reg() {
			var form = document.mosForm;
			var r = new RegExp("[\<|\>|\"|\'|\%|\;|\(|\)|\&|\+|\-]", "i");

			// do field validation
			if (form.name.value == "") {
				alert( "<?php echo html_entity_decode(_REGWARN_NAME);?>" );
			} else if (form.username.value == "") {
				alert( "<?php echo html_entity_decode(_REGWARN_UNAME);?>" );
			} else if (r.exec(form.username.value) || form.username.value.length < 3) {
				alert( "<?php printf( html_entity_decode(_VALID_AZ09_USER), html_entity_decode(_PROMPT_UNAME), 2 );?>" );
			} else if (form.email.value == "") {
				alert( "<?php echo html_entity_decode(_REGWARN_MAIL);?>" );
			} else if (form.password.value.length < 6) {
				alert( "<?php echo html_entity_decode(_REGWARN_PASS);?>" );
			} else if (form.password2.value == "") {
				alert( "<?php echo html_entity_decode(_REGWARN_VPASS1);?>" );
			} else if ((form.password.value != "") && (form.password.value != form.password2.value)){
				alert( "<?php echo html_entity_decode(_REGWARN_VPASS2);?>" );
			} else if (r.exec(form.password.value)) {
				alert( "<?php printf( html_entity_decode(_VALID_AZ09), html_entity_decode(_REGISTER_PASS), 6 );?>" );
			} else {
				form.submit();
			}
		}
		/* ]]> */
	</script>
	<form action="<?php echo AECToolbox::deadsureURL( '/index.php?option=com_acctexp&amp;task=saveRegistration' ); ?>" method="post" name="mosForm">

	<div class="componentheading">
		<?php echo _REGISTER_TITLE; ?>
	</div>

	<table cellpadding="0" cellspacing="0" border="0" width="100%" class="contentpane">
	<tr>
		<td colspan="2"><?php echo _REGISTER_REQUIRED; ?></td>
	</tr>
	<tr>
		<td width="30%">
			<?php echo _REGISTER_NAME; ?> *
		</td>
	  	<td>
	  		<input type="text" name="name" size="40" value="" class="inputbox" maxlength="50" />
	  	</td>
	</tr>
	<tr>
		<td>
			<?php echo _REGISTER_UNAME; ?> *
		</td>
		<td>
			<input type="text" name="username" size="40" value="" class="inputbox" maxlength="25" />
		</td>
	</tr>
	<tr>
		<td>
			<?php echo _REGISTER_EMAIL; ?> *
		</td>
		<td>
			<input type="text" name="email" size="40" value="" class="inputbox" maxlength="100" />
		</td>
	</tr>
	<tr>
		<td>
			<?php echo _REGISTER_PASS; ?> *
		</td>
	  	<td>
	  		<input class="inputbox" type="password" name="password" size="40" value="" />
	  	</td>
	</tr>
	<tr>
		<td>
			<?php echo _REGISTER_VPASS; ?> *
		</td>
		<td>
			<input class="inputbox" type="password" name="password2" size="40" value="" />
		</td>
	</tr>
	<tr>
		  <td colspan="2">
		  </td>
	</tr>
	<?php
	if ( $aecConfig->cfg['use_recaptcha'] && !empty( $aecConfig->cfg['recaptcha_publickey'] ) ) {
		require_once( $mosConfig_absolute_path . '/components/com_acctexp/lib/recaptcha/recaptchalib.php' );
		?>
		<tr>
		<td></td>
		<td>
		<?php
		echo recaptcha_get_html( $aecConfig->cfg['recaptcha_publickey'] );
		?>
		</td>
		</tr>
		<?php
	}
	?>
	</table>
	<input type="hidden" name="id" value="0" />
	<input type="hidden" name="gid" value="0" />
	<input type="hidden" name="useractivation" value="<?php echo $useractivation;?>" />
	<input type="hidden" name="option" value="com_acctexp" />
	<input type="hidden" name="task" value="saveRegistration" />
	<input type="hidden" name="usage" value="<?php echo $_POST['usage'];?>" />
	<input type="hidden" name="processor" value="<?php echo $_POST['processor'];?>" />
	<?php if ( isset( $_POST['recurring'] ) ) { ?>
	<input type="hidden" name="recurring" value="<?php echo $_POST['recurring'];?>" />
	<?php } ?>
	<input type="button" value="<?php echo _BUTTON_SEND_REG; ?>" class="button" onclick="submitbutton_reg()" />
	<input type="hidden" name="<?php echo $validate; ?>" value="1" />
	</form>
	<?php
	}
}
?>