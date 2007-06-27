<?php
/**
 * @version $Id: acctexp.html.php 16 2007-06-27 09:04:04Z mic $
 * @package AEC - Account Control Expiration - Subscription component for Joomla! OS CMS
 * @subpackage Main HTML Frontend
 * @copyright Copyright (C) 2004-2007, All Rights Reserved, Helder Garcia, David Deutsch
 * @author Helder Garcia <helder.garcia@gmail.com>, David Deutsch <skore@skore.de> & Team AEC - http://www.gobalnerd.org
 * @license GNU/GPL v.2 http://www.gnu.org/copyleft/gpl.html
 */

/**
 * ----------- CONTRIBUTIONS --------------
 * "Expire Now" feature contributed by:
 * Rasmus Dahl-Sorensen (ford) - 2004.11.11
 * ----------------------------------------
 */

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

class HTML_frontEnd {

	function HTML_frontEnd() {
		$this->aec_styling( 'com_acctexp' );
	}

	function aec_styling( $option ) {
		global $mainframe;

    	$html = '<link rel="stylesheet" type="text/css" media="all" href="'
    	. $mainframe->getCfg( 'live_site' ) . '/components/' . $option . '/style.css" />';

    	$mainframe->addCustomHeadTag( $html );
    	$mainframe->setPageTitle( 'AEC- Account Evolution' );
 		$mainframe->appendMetaTag( 'description', 'AEC Account Expiration Control' );
    	$mainframe->appendMetaTag( 'keywords', 'AEC Account Expiration Control' );
	}

	function expired($option, $userid, $expiration, $name, $username, $invoice, $trial) {
		global $database;

		$cfg = new Config_General( $database ); ?>
		<div class="componentheading"><?php echo _EXPIRED_TITLE; ?></div>
		<?php
		if ($cfg->cfg['customtext_expired_keeporiginal']) {?>
			<div id="expired_greeting">
				<p><?php echo sprintf( _DEAR, $name ); ?></p><p><?php
					if( $trial ) {
						echo _EXPIRED_TRIAL;
					}else{
						echo _EXPIRED;
					}
					echo $expiration; ?>
				</p>
			</div>
			<?php
		}
		if( $cfg->cfg['customtext_expired'] ) { ?>
			<p><?php echo $cfg->cfg['customtext_expired']; ?></p>
			<?php
		} ?>
		<div id="box_expired">
			<div id="alert_level_1">
				<?php
				if ($invoice) { ?>
					<p>
						<?php echo _PENDING_OPENINVOICE; ?>&nbsp;
						<a href="<?php echo AECToolbox::deadsureURL( 'index.php?option=' . $option . '&amp;task=repeatPayment&amp;invoice=' . $invoice . '&amp;Itemid=' . $userid ); ?>" title="<?php echo _GOTO_CHECKOUT; ?>"><?php echo _GOTO_CHECKOUT; ?></a>
					</p>
					<?php
				} ?>
				<div id="renew_button">
					<form action="<?php echo AECToolbox::deadsureURL( 'index.php?option=com_acctexp&task=renewSubscription' ); ?>" method="post">
					<input type="hidden" name="option" value="<?php echo $option; ?>" />
					<input type="hidden" name="Itemid" value="<?php echo $userid; ?>" />
					<input type="hidden" name="task" value="renewSubscription" />
					<input type="submit" class="button" value="<?php echo _RENEW_BUTTON;?>" />
					</form>
				</div>
			</div>
		</div>
		<?php
	}

	function pending($option, $objUser, $invoice, $reason = 0) {
		global $database;

		$actions =	_PENDING_OPENINVOICE
		. ' <a href="'
		.  AECToolbox::deadsureURL( 'index.php?option=' . $option . '&amp;task=repeatPayment&amp;invoice='
		. $invoice . '&amp;Itemid=' . $objUser->id ) . '" title="' . _GOTO_CHECKOUT . '">'
		. _GOTO_CHECKOUT
		. '</a>'
		. ', ' . _GOTO_CHECKOUT_CANCEL . ' '
		. '<a href="'
		. AECToolbox::deadsureURL( 'index.php?option=' . $option . '&amp;task=cancelPayment&amp;invoice='
		. $invoice . '&amp;Itemid=' . $objUser->id . '&amp;pending=1' )
		. '" title="' . _HISTORY_ACTION_CANCEL . '">'
		. _HISTORY_ACTION_CANCEL
		. '</a>';

		if( $reason !== 0 ) {
			$actions .= ' ' . $reason;
		}

		$cfg = new Config_General( $database ); ?>

		<div class="componentheading"><?php echo _PENDING_TITLE; ?></div>
		<?php
		if( $cfg->cfg['customtext_pending_keeporiginal'] ) { ?>
			<p class="expired_dear"><?php echo _DEAR . $objUser->name . ','; ?></p>
			<p class="expired_date"><?php echo _WARN_PENDING; ?></p>
			<?php
		}
		if( $cfg->cfg['customtext_pending'] ) { ?>
			<p><?php echo $cfg->cfg['customtext_pending']; ?></p>
			<?php
		} ?>
		<div id="box_pending">
		<?php
		if( strcmp($invoice, "none") === 0 ) { ?>
			<p><?php echo _PENDING_NOINVOICE; ?></p>
			<div id="upgrade_button">
				<form action="<?php echo AECToolbox::deadsureURL( 'index.php?option=com_acctexp&task=renewSubscription' ); ?>" method="post">
					<input type="hidden" name="option" value="<?php echo $option; ?>" />
					<input type="hidden" name="Itemid" value="<?php echo $objUser->id; ?>" />
					<input type="hidden" name="task" value="renewSubscription" />
					<input type="submit" class="button" value="<?php echo _PENDING_NOINVOICE_BUTTON;?>" />
				</form>
			</div>
			<?php
		}elseif( $invoice ) { ?>
			<p><?php echo $actions; ?></p>
			<?php
		} ?>
		</div>
		<?php
	}

	function subscriptionDetails( $option, $invoices, $metaUser, $recurring, $pp, $mi, $alert, $selected_plan = false ) {
		global $database;

		$cfg = new Config_General( $database ); ?>
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
				foreach( $invoices as $invoice ) { ?>
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
			if( is_object( $selected_plan ) ) { ?>
				<h2><?php echo $selected_plan->name; ?></h2>
				<p><?php echo $selected_plan->desc; ?></p>
				<?php
			}
			if( $mi ) {
				echo $mi;
			} ?>
			<div id="box_expired">
			<div id="alert_level_<?php echo $alert['level']; ?>">
				<div id="expired_greeting"><?php
					if( $metaUser->objSubscription->lifetime == 1 ) { ?>
						<p><strong><?php echo _RENEW_LIFETIME; ?></strong></p><?php
					}else{ ?>
						<p>
							<?php echo _EXPIRE_INFO;?>:&nbsp;
							<strong><?php echo HTML_frontend::DisplayDateInLocalTime( $metaUser->objExpiration->expiration ); ?></strong>
						</p>
						<?php
					} ?>
				</div>
				<div id="days_left">
					<?php
					if( strcmp( $alert['daysleft'], 'infinite' ) === 0 ) {
						$daysleft			= _RENEW_DAYSLEFT_INFINITE;
						$daysleft_append	= _RENEW_DAYSLEFT;
					}elseif( strcmp( $alert['daysleft'], 'excluded' ) === 0 ) {
						$daysleft			= _RENEW_DAYSLEFT_EXCLUDED;
						$daysleft_append	= "";
					}else{
						$daysleft			= $alert['daysleft'];
						$daysleft_append	= _RENEW_DAYSLEFT;
					} ?>
					<p><strong><?php echo $daysleft; ?></strong>&nbsp;&nbsp;<?php echo $daysleft_append; ?></p>
				</div>
				<?php
				if( $recurring == 0 ) { ?>
					<div id="upgrade_button">
						<form action="<?php echo AECToolbox::deadsureURL( 'index.php?option=com_acctexp&amp;task=renewsubscription' ); ?>" method="post">
							<input type="hidden" name="option" value="<?php echo $option; ?>" />
							<input type="hidden" name="task" value="renewsubscription" />
							<input type="hidden" name="userid" value="<?php echo $metaUser->cmsUser->id; ?>" />
							<input type="submit" class="button" value="<?php echo _RENEW_BUTTON_UPGRADE;?>" />
						</form>
					</div>
					<?php
				}else{ ?>
					<p><strong><?php echo _RENEW_INFO; ?></strong></p>
					<?php
					if( is_object( $pp ) ) {
						if( isset( $pp->info['cancel_info'] ) ) { ?>
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

	function notAllowed( $option, $processors, $registerlink, $loggedin = 0 ) {
		global $database;

		$cfg = new Config_General( $database );

		if( !is_object( $this ) ) {
			HTML_frontEnd::aec_styling();
		} ?>
		<div class="componentheading"><?php echo _NOT_ALLOWED_HEADLINE; ?></div>
		<?php
		if( $cfg->cfg['customtext_notallowed_keeporiginal'] ) {?>
			<p>
				<?php
				if( $loggedin ) {
					echo _NOT_ALLOWED_FIRSTPAR_LOGGED; ?>&nbsp;
					<a href="<?php echo $registerlink; ?>" title="<?php echo _NOT_ALLOWED_REGISTERLINK_LOGGED; ?>"><?php echo _NOT_ALLOWED_REGISTERLINK_LOGGED; ?></a>
					<?php
				}else{
					echo _NOT_ALLOWED_FIRSTPAR; ?>&nbsp;
					<a href="<?php echo $registerlink; ?>" title="<?php echo _NOT_ALLOWED_REGISTERLINK; ?>"><?php echo _NOT_ALLOWED_REGISTERLINK; ?></a>
					<?php
				} ?>
			</p>
			<?php
		}
		if( $cfg->cfg['customtext_notallowed'] ) { ?>
			<p><?php echo $cfg->cfg['customtext_notallowed']; ?></p>
			<?php
		} ?>
		<p></p>
		<?php
		if( is_array( $processors ) ) { ?>
			<p><?php echo _NOT_ALLOWED_SECONDPAR; ?></p>
			<table id="cc_list">
				<?php
				foreach( $processors as $processor ) {
					HTML_frontEnd::processorInfo( $option, $processor, $cfg->cfg['displayccinfo'] );
				} ?>
			</table>
			<?php
		}
	}

	function processorInfo( $option, $processorObj, $displaycc = 1 ) {
		global $mosConfig_live_site; ?>

		<tr>
			<td class="cc_gateway">
				<p align="center"><img src="<?php echo $mosConfig_live_site . '/components/' . $option . '/images/gwlogo_' . $processorObj->processor_name . '.png" alt="' . $processorObj->processor_name . '" title="' . $processorObj->processor_name; ?>" /></p>
			</td>
			<td class="cc_icons">
				<p>
					<?php
					if( isset( $processorObj->info['description'] ) ) {
						echo $processorObj->info['description'];
					} ?>
				</p>
			</td>
		</tr>
		<?php
		if( $displaycc ) { ?>
			<tr>
				<td class="cc_gateway"></td>
				<td class="cc_icons">
					<?php echo Payment_HTML::getCCiconsHTML ( $option, $processorObj->info['cc_list'] ); ?>
				</td>
			</tr>
			<?php
		}
	}

	function DisplayDateInLocalTime( $SQLDate ){
		if( $SQLDate == '' ) {
			return _AEC_EXPIRE_NOT_SET;
		}else{
			global $mosConfig_offset_user, $database;

			$cfg = new Config_General( $database );

			return strftime( $cfg->cfg['display_date_backend'], ( strtotime( $SQLDate ) + $mosConfig_offset_user * 3600 ) );
		}
	}

}

class HTML_Results {

	function thanks( $option, $msg ) {
		HTML_frontend::aec_styling( $option );
		?>
		<div class="componentheading"><?php echo _THANKYOU_TITLE; ?></div>
		<div id="thankyou_page">
			<p><?php echo $msg; ?></p>
		</div>
		<?php
	}

	function cancel( $option ) {
		HTML_frontend::aec_styling( $option ); ?>
		<div class="componentheading"><?php echo _CANCEL_TITLE; ?></div>
		<div id="cancel_page">
			<p><?php echo _CANCEL_MSG; ?></p>
		</div>
		<?php
	}
}

class Payment_HTML {

	function selectSubscriptionPlanForm( $option, $userid, $plans, $expired, $passthrough = false, $register = false ) {
		global $mosConfig_live_site, $database;

		HTML_frontend::aec_styling( $option );

		$cfg = new Config_General( $database ); ?>
		<div class="componentheading"><?php echo _PAYPLANS_HEADER; ?></div>
		<?php
		if( $cfg->cfg['customtext_plans'] ) { ?>
			<p><?php echo $cfg->cfg['customtext_plans']; ?></p>
			<?php
		} ?>
		<div class="subscriptions">
			<?php
			foreach( $plans as $plan ) { ?>
				<table>
					<th><?php echo $plan['name']; ?></th>
					<tr><td><?php echo $plan['desc']; ?></td></tr>
					<tr>
						<td class="buttons">
							<?php
							echo Payment_HTML::getPayButtonHTML( $plan['gw'], $plan['id'], $userid, $passthrough, $register ); ?>
						</td>
					</tr>
				</table>
				<?php
			}
			?>
		</div>
		<?php
	}

	function getPayButtonHTML( $gw, $planid, $userid, $passthrough = false, $register = false ) {
		global $mosConfig_live_site, $database, $my;

		$cfg = new Config_General( $database );

		$html_code = '';

		foreach( $gw as $n => $processor ) {
			$gw_current = strtolower( $processor['name'] );

			if( $register ) {
				if( GeneralInfoRequester::detect_component( 'CB' ) || GeneralInfoRequester::detect_component( 'CBE' ) ) {
					$option	= 'com_comprofiler';
					$task	= 'registers';
				}else{
					$option	= 'com_registration';
					$task	= 'register';
				}
			}else{
				$option		= 'com_acctexp';
				$task		= 'confirm';
			}

			$urlbutton = $mosConfig_live_site . '/components/com_acctexp/images/gw_button_' . $processor['name'] . '.png';

			$html_code .= '<div class="gateway_button">' . "\n"
			. '<form action="' . AECToolbox::deadsureURL( 'index.php?option=' . $option . '&amp;task=' . $task ) . '"'
			. ' "method="post">' . "\n"
			. '<input type="image" src="' . $urlbutton
			. '" border="0" name="submit" alt="' . $processor['statement'] . '" />' . "\n"
			. '<input type="hidden" name="option" value="' . $option . '" />' . "\n"
			. '<input type="hidden" name="task" value="' . $task . '" />' . "\n"
			. '<input type="hidden" name="processor" value="' . strtolower( $processor['name'] ) . '" />' . "\n"
			. '<input type="hidden" name="usage" value="' . $planid . '" />' . "\n"
			. '<input type="hidden" name="userid" value="' . ( $userid ? $userid : 0 ) . '" />' . "\n";

			if( $passthrough != false ) {
				foreach( $passthrough as $key => $value ) {
					$html_code .= '<input type="hidden" name="' . $key . '" value="' . $value . '" />' . "\n";
				}
			}
			$html_code .= '</form></div>' . "\n";
		}

		return $html_code;
	}

	function getCCiconsHTML( $option, $cc_list ) {
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
			. ' alt="' . $cc_array[$i]
			. ' title="' . $cc_array[$i]
			. '" class="cc_icon" />';
		}

		return $html_code;
	}

	function confirmForm( $option, $InvoiceFactory, $user, $tos=null, $passthrough = false) {
		global $database;

		HTML_frontend::aec_styling( $option );

		$cfg = new Config_General( $database ); ?>
		<div class="componentheading"><?php echo _CONFIRM_TITLE; ?></div>
		<?php
		if( $tos ) { ?>
			<script type="text/javascript">
				/* <![CDATA[ */
				function submitPayment() {
					if( document.confirmForm.tos.checked ) {
						document.confirmForm.submit();
					}else{
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
						if( $user->name ) { ?>
							<p><?php echo _CONFIRM_ROW_NAME; ?> <?php echo $user->name; ?></p>
							<?php
						} ?>
						<p><?php echo _CONFIRM_ROW_USERNAME; ?> <?php echo $user->username; ?></p>
						<p><?php echo _CONFIRM_ROW_EMAIL; ?> <?php echo $user->email; ?></p>
					</td>
					<td><p><?php echo $InvoiceFactory->usage->name; ?></p></td>
					<td><p>
						<?php
						if( $InvoiceFactory->payment->amount ) {
							echo $InvoiceFactory->payment->amount . ' ' . $InvoiceFactory->payment->currency; ?>&nbsp;-&nbsp;
							<?php
						}
						echo $InvoiceFactory->payment->method_name; ?>
					</p></td>
				</tr>
				<tr>
					<td colspan="4" style="text-align: left;"><?php echo $InvoiceFactory->usage->desc; ?></td>
				</tr>
			</table>
			<?php
			if( $cfg->cfg['customtext_confirm'] ) { ?>
				<p><?php echo $cfg->cfg['customtext_confirm']; ?></p>
				<?php
			}
			if( $cfg->cfg['customtext_confirm_keeporiginal'] ) { ?>
				<p><?php echo _CONFIRM_INFO; ?></p>
				<?php
			}
			if( $InvoiceFactory->coupons['active'] ) { ?>
				<p><?php echo _CONFIRM_COUPON_INFO; ?></p>
				<?php
			} ?>
			<table>
				<tr>
					<td class="confirmation_button">
						<form name="confirmForm" action="<?php echo AECToolbox::deadsureURL( 'index.php?option=' . $option ); ?>" method="post">
						<input type="hidden" name="option" value="<?php echo $option; ?>" />
						<input type="hidden" name="userid" value="<?php echo $user->id ? $user->id : 0; ?>" />
						<input type="hidden" name="task" value="saveSubscription" />
						<input type="hidden" name="usage" value="<?php echo $InvoiceFactory->usage; ?>" />
						<input type="hidden" name="processor" value="<?php echo $InvoiceFactory->processor; ?>" />
						<?php
						if( $tos ) { ?>
							<p><input name="tos" type="checkbox" /><?php echo sprintf( _CONFIRM_TOS, $tos ); ?></p>
							<input type="button" onclick="javascript:submitPayment()" class="button" value="<?php echo _BUTTON_CONFIRM; ?>" />
							<?php
						}else{ ?>
							<input type="submit" class="button" value="<?php echo _BUTTON_CONFIRM; ?>" />
							<?php
						}
						if( $passthrough != false ) {
							foreach( $passthrough as $key => $value ) { ?>
								<input type="hidden" name="<?php echo $key; ?>" value="<?php echo $value; ?>" />
								<?php
							}
						} ?>
						</form>
					</td>
				</tr>
				<tr><td>
					<table>
						<?php
						if( is_object( $InvoiceFactory->pp ) ) {
							HTML_frontEnd::processorInfo( $option, $InvoiceFactory->pp );
						} ?>
					</table>
				</td></tr>
			</table>
		</div>
		<?php
	}

	function checkoutForm( $option, $var, $params = null, $InvoiceFactory, $repeat = 0 ) {
		global $database;

		HTML_frontend::aec_styling( $option );

		$posturl = $var['post_url'];
		unset( $var['post_url'] );
		$introtext = '_CHECKOUT_INFO' . ( isset( $var['transferinfo'] ) ? '_TRANSFER' : '' ) . ( $repeat ? '_REPEAT' : '' );

		$cfg = new Config_General( $database ); ?>

		<div class="componentheading"><?php echo _CHECKOUT_TITLE; ?></div>
		<div id="checkout">
			<?php
			if( $cfg->cfg['customtext_checkout_keeporiginal'] ) { ?>
				<p><?php echo constant( $introtext ); ?></p>
				<?php
			}
			if( $cfg->cfg['customtext_checkout'] ) { ?>
				<p><?php echo $cfg->cfg['customtext_checkout']; ?></p>
				<?php
			} ?>
			<div style="border:#DDD solid 1px;">
				<table class="amount" style="padding-top:16px;">
					<tr>
						<td class="item"><?php echo _CHECKOUT_INVOICE_AMOUNT; ?></td>
						<td class="amount">
							<?php echo $InvoiceFactory->payment->amount . '&nbsp;' . $InvoiceFactory->payment->currency; ?>
						</td>
					</tr>
					<?php
					if( $InvoiceFactory->coupons['active'] && $InvoiceFactory->coupons['coupons'] ) {
						foreach( $InvoiceFactory->coupons['coupons'] as $id => $coupon ) { ?>
							<tr>
								<td class="item<?php echo $coupon['nodirectaction'] ? 'later' : ''; ?>">
									<?php echo _CHECKOUT_INVOICE_COUPON; ?> (<?php echo $coupon['action']; ?>)
									&nbsp;[
									<a href="<?php echo AECToolbox::deadsureURL( 'index.php?option=' . $option . '&amp;task=InvoiceRemoveCoupon&amp;invoice=' . $InvoiceFactory->invoice . '&amp;coupon_code=' . $coupon['code'] ); ?>" title="<?php echo _CHECKOUT_INVOICE_COUPON_REMOVE; ?>"><?php echo _CHECKOUT_INVOICE_COUPON_REMOVE; ?></a>
									]
								</td>
								<td class="<?php echo $coupon['nodirectaction'] ? 'amount_later' : 'amount'; ?>">-<?php echo $coupon['discount'] . ' ' . $InvoiceFactory->payment->currency; ?></td>
							</tr>
							<?php
						}
					} ?>
					<tr class="total">
						<td class="item"><?php echo _CHECKOUT_INVOICE_TOTAL_AMOUNT; ?></td>
						<td class="amount">
							<?php echo $InvoiceFactory->objInvoice->amount . '&nbsp;' . $InvoiceFactory->payment->currency; ?>
						</td>
					</tr>
				</table>
				<?php
				if( $InvoiceFactory->coupons['active'] ) { ?>
					<p><?php echo _CHECKOUT_COUPON_INFO; ?></p>
					<table width="100%" style="background-color:#DDD;">
						<tr>
							<td class="confirmation_button" style="background-color:#EEE;">
								<strong><?php echo _CHECKOUT_COUPON_CODE; ?></strong>
							</td>
						</tr>
						<tr>
							<td class="confirmation_button">
								<?php
								if( $InvoiceFactory->coupons['warning'] ) { ?>
									<div class="couponwarning">
										<p><strong><?php echo constant( $InvoiceFactory->coupons['warningmsg'] ); ?></strong></p>
									</div>
									<?php
								}
								if( $InvoiceFactory->coupons['error'] ) { ?>
									<div class="couponerror">
										<p>
											<strong><?php echo _COUPON_ERROR_PRETEXT; ?></strong>
											&nbsp;
											<?php echo constant( $InvoiceFactory->coupons['errormsg'] ); ?>
										</p>
									</div>
									<?php
								} ?>
								<form action="<?php echo AECToolbox::deadsureURL( 'index.php?option=com_acctexp&amp;task=InvoiceAddCoupon' ); ?>" method="post">
									<input type="text" size="20" name="coupon_code" class="inputbox" value="" />
									<input type="hidden" name="option" value="<?php echo $option; ?>" />
									<input type="hidden" name="task" value="InvoiceAddCoupon" />
									<input type="hidden" name="invoice" value="<?php echo $InvoiceFactory->invoice; ?>" />
									<input type="submit" class="button" value="<?php echo _BUTTON_APPEND; ?>" />
								</form>
							</td>
						</tr>
					</table>
					<?php
				}
				if( $params ) { ?>
					<table width="100%" style="margin-top:24px;background-color:#DDD;">
						<tr>
							<td class="append_button">
								<form action="<?php echo AECToolbox::deadsureURL( 'index.php?option=com_acctexp&amp;task=InvoiceAddParams' ); ?>" method="post">
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
			</div>
			<table width="100%" style="margin-top:24px;background-color:#DDD;">
				<tr>
					<td class="confirmation_button">
						<?php
						if( isset( $var['transferinfo'] ) ) { ?>
							<p><?php echo $var['transferinfo']; ?></p>
							<?php
						}else{ ?>
							<form action="<?php echo $posturl; ?>" method="post">
								<?php
								foreach( $var as $key => $value ) { ?>
									<input type="hidden" name="<?php echo $key; ?>" value="<?php echo $value; ?>" />
									<?php
								} ?>
								<input type="submit" class="button" value="<?php echo _BUTTON_CHECKOUT; ?>" />
							</form>
							<?php
						} ?>
					</td>
				</tr>
			</table>
			<table width="100%">
				<?php
				if( is_object( $InvoiceFactory->pp ) ) {
					HTML_frontEnd::processorInfo( $option, $InvoiceFactory->pp, $cfg->cfg['displayccinfo'] );
				} ?>
			</table>
		</div>
		<?php
	}

    function errorAP( $option, $planid, $userid, $username, $name, $recurring ) {
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

    function generalError ( $option ) {
		HTML_frontend::aec_styling( $option );
   		echo _AEC_GEN_ERROR;
	}
}
?>