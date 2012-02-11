<?php
/**
 * @version $Id: admin.acctexp.html.php
 * @package AEC - Account Control Expiration - Membership Manager
 * @subpackage Main HTML Backend
 * @copyright 2006-2012 Copyright (C) David Deutsch
 * @author David Deutsch <skore@valanx.org> & Team AEC - http://www.valanx.org
 * @license GNU/GPL v.2 http://www.gnu.org/licenses/old-licenses/gpl-2.0.html or, at your option, any later version
 */

// Dont allow direct linking
( defined('_JEXEC') || defined( '_VALID_MOS' ) ) or die( 'Direct Access to this location is not allowed.' );

class HTML_myCommon
{
	function Valanx()
	{
		?>
		<div align="center" id="aec_footer">
			<table width="500" border="0">
			<tr>
				<td align="center">
					<img src="<?php echo JURI::root(); ?>media/com_acctexp/images/admin/icons/aec_logo_small_footer.png" border="0" alt="aec" />
				</td>
				<td align="center">
					<div align="center" class="smallgrey">
						<p><strong>Account Expiration Control</strong> Component<br />Version <?php echo str_replace( 'omega', '&Omega;', _AEC_VERSION ); ?>, Revision <?php echo _AEC_REVISION ?><br />
					</div>
					<div align="center">
						<p><?php echo JText::_('AEC_FOOT_TX_GPL'); ?></p>
						<p><?php printf( JText::_('AEC_FOOT_CREDIT'), AECToolbox::backendTaskLink( 'credits', htmlentities( JText::_('AEC_FOOT_CREDIT_LTEXT') ) ) ); ?></p>
					</div>
				</td>
				<td align="center">
					<a href="https://www.valanx.org" target="_blank"><img src="<?php echo JURI::root(); ?>media/com_acctexp/images/admin/gfx/valanx_logo_tiny.png" border="0" alt="valanx" /></a>
				</td>
			</tr>
			</table>
		</div>
		<?php
	}

	function addBackendCSS()
	{
		?>
		<link rel="stylesheet" type="text/css" media="all" href="<?php echo JURI::root(); ?>media/com_acctexp/css/bootstrap.css" />
		<link rel="stylesheet" type="text/css" media="all" href="<?php echo JURI::root(); ?>media/com_acctexp/css/toggleswitch/toggleswitch.css" />
		<link rel="stylesheet" type="text/css" media="all" href="<?php echo JURI::root(); ?>media/com_acctexp/css/admin.css" />
		<?php
	}

	function addBackendJS()
	{
		$document=& JFactory::getDocument();

		$document->addScript( JURI::root(true).'/media/com_acctexp/js/jquery/jquery-1.7.1.min.js' );
		$document->addScript( JURI::root(true).'/media/com_acctexp/js/jquery/jquerync.js' );
		$document->addScript( JURI::root(true).'/media/com_acctexp/js/bootstrap/bootstrap.js' );
		$document->addScript( JURI::root(true).'/media/com_acctexp/js/aec-backend.js' );
	}

	function addReadoutCSS()
	{
		?>
		<link rel="stylesheet" type="text/css" media="all" href="<?php echo JURI::root(); ?>media/com_acctexp/css/readout.css" />
		<?php
	}

	function startCommon( $id='aec_wrap' )
	{
		HTML_myCommon::addBackendCSS();
		HTML_myCommon::addBackendJS();

		echo '<div id="' . $id . '">';
		echo HTML_AcctExp::menuBar();
	}

	function endCommon( $footer=true )
	{
		if ( $footer ) {
			HTML_myCommon::Valanx();
		}

		echo '</div>';
	}

	function getHeader( $page, $image, $extratext="" )
	{
		?><div class="adminheading">
			<?php HTML_myCommon::getSymbol( $image ); ?>
			<h2><?php echo ( empty($page) ? '' : JText::_($page) ) . ( !empty( $extratext ) ? ' - ' . $extratext : '' ); ?></h2>
		</div>
		<?php
	}

	function getSymbol( $name )
	{
		?><div class="aec-symbol aec-symbol-<?php echo $name; ?>"></div><?php
	}

	function getButtons( $buttons, $object )
	{
		$v = new JVersion();
		?><div class="aec-buttons"><?php
		foreach ( $buttons as $action => $button ) {
			if ( isset( $button['groupstart'] ) ) {
				echo '<div class="btn-group">';
			} elseif ( isset( $button['groupend'] ) ) {
				echo '</div>';
			} elseif ( !isset( $button['style'] ) ) {
				echo '<span class="btn-hl"></span>';
			} else {
				echo '<a class="btn btn-' . $button['style'] . '" onclick="javascript: ' . ( $v->isCompatible('2.5') ? 'Joomla.' : '' ) . 'submitbutton(\'' . $action . $object . '\')"' . ( !empty($button['actionable']) ? ' disabled="disabled"' : '' ) . ' href="#" rel="tooltip" data-original-title="' . $button['text'] . '"><i class="bsicon-' . $button['icon'] . ' bsicon-white"></i></a>';
			}
		}
		?></div><?php
	}
}

class HTML_AcctExp
{
	function HTML_AcctExp() {}

	function userForm( $option, $metaUser, $invoices, $coupons, $mi, $lists, $nexttask, $aecHTML )
	{
		HTML_myCommon::startCommon();

		JHTML::_('behavior.calendar');

		if ( defined( 'JPATH_MANIFESTS' ) ) {
			$edituserlink		= 'index.php?option=com_users&amp;task=user.edit&amp;id=' . $metaUser->userid;
			$activateuserlink	= 'index.php?option=com_users&amp;task=registration.activate&amp;token=' . $metaUser->cmsUser->activation;
		} else {
			$edituserlink		= 'index.php?option=com_users&amp;view=user&amp;task=edit&amp;cid[]=' . $metaUser->userid;
			$activateuserlink	= 'index.php?option=com_user&amp;task=activate&amp;activation=' . $metaUser->cmsUser->activation;
		}

		$exp = $icon = $status = "";

		if ( $metaUser->hasSubscription ) {
			if ( isset( $metaUser->focusSubscription->expiration ) ) {
				$exp = $metaUser->focusSubscription->expiration;
			}

			switch( $metaUser->focusSubscription->status ) {
				case 'Excluded':
					$icon = 'repeat';
					$status	= JText::_('AEC_CMN_EXCLUDED');
					break;
				case 'Trial':
					$icon 	= 'star';
					$status	= JText::_('AEC_CMN_TRIAL');
					break;
				case 'Pending':
					$icon 	= 'star';
					$status	= JText::_('AEC_CMN_PENDING');
					break;
				case 'Active':
					$icon	= 'ok';
					$status	= JText::_('AEC_CMN_ACTIVE');
					break;
				case 'Cancel':
					$icon	= 'warning-sign';
					$status	= JText::_('AEC_CMN_CANCEL');
					break;
				case 'Hold':
					$icon	= 'warning-sign';
					$status	= JText::_('AEC_CMN_HOLD');
					break;
				case 'Expired':
					$icon	= 'remove';
					$status	= JText::_('AEC_CMN_EXPIRED');
					break;
				case 'Closed':
					$icon	= 'remove';
					$status	= JText::_('AEC_CMN_CLOSED');
					break;
				default:
					$icon	= 'remove-circle';
					$status	= JText::_('AEC_CMN_NOT_SET');
					break;
			}
		}

		HTML_myCommon::getHeader( 'AEC_HEAD_SETTINGS', 'edit', $metaUser->cmsUser->username . ' (' . JText::_('AEC_CMN_ID') . ': ' . $metaUser->userid . ')' );

		$buttons = array(	'apply' => array( 'style' => 'info', 'text' => JText::_('APPLY'), 'icon' => 'ok-sign' ),
							'save' => array( 'style' => 'success', 'text' => JText::_('SAVE'), 'icon' => 'ok' ),
							'hl1' => array(),
							'cancel' => array( 'style' => 'danger', 'text' => JText::_('CANCEL'), 'icon' => 'remove' )
						);
		HTML_myCommon::getButtons( $buttons, '' );

		$tabs = new bsPaneTabs;

		$tabs->startTabs();
		$tabs->newTab( 'user', JText::_('AEC_HEAD_PLAN_INFO'), true );
		$tabs->newTab( 'mis', JText::_('AEC_USER_MICRO_INTEGRATION') );
		$tabs->endTabs();

		?>
		<script type="text/javascript">
		jQuery(document).ready(function() {
			if ( jQuery("input#ck_lifetime").is(':checked') ) {
				jQuery("input#expiration").attr("disabled", "disabled");
			}

			jQuery("input#ck_lifetime").on('click', function() {
				if (jQuery(this).is(':checked')) {
					jQuery("input#expiration").attr("disabled", "disabled");
				} else {
					jQuery("input#expiration").removeAttr("disabled");
				}
			});
		});
		</script>


		<form action="index.php" method="post" name="adminForm" id="adminForm" class="form-horizontal">
			<?php
			$tabs->startPanes();
			$tabs->startPane( 'user', true );
			?>
			<table class="aecadminform">
				<tr>
					<td width="50%" valign="top">
						<div class="aec_userinfobox_sub">
							<h4><?php echo JText::_('AEC_USER_SUBSCRIPTION'); ?></h4>
							<?php if ( $metaUser->hasSubscription ) { ?>
							<div class="control-group">
								<label class="control-label" for="expiration"><span><?php echo JText::_('AEC_USER_SUBSCRIPTIONS_ID'); ?></span></label>
								<div class="controls"><span><?php echo $metaUser->focusSubscription->id; ?></span></div>
							</div>
							<div class="control-group">
								<label class="control-label" for="expiration"><span><?php echo JText::_('AEC_USER_CURR_SUBSCR_PLAN'); ?></span></label>
								<div class="controls"><span>#<?php echo $metaUser->focusSubscription->plan; ?> - "<?php echo ( $metaUser->focusSubscription->plan ? HTML_AcctExp::SubscriptionName( $metaUser->focusSubscription->plan ) : '<span style="color:#FF0000;">' . JText::_('AEC_CMN_NOT_SET') . '</span>' ); ?>"</span></div>
							</div>
							<div class="control-group">
								<label class="control-label" for="expiration"><span><?php echo JText::_('AEC_USER_STATUS'); ?></span></label>
								<div class="controls"><span><?php echo aecHTML::Icon( $icon ); ?>&nbsp;<?php echo $status; ?></span></div>
							</div>
							<div class="control-group">
								<label class="control-label" for="expiration"><span><?php echo JText::_('AEC_USER_PAYMENT_PROC'); ?></span></label>
								<div class="controls"><span><?php echo $metaUser->focusSubscription->type ? $metaUser->focusSubscription->type : JText::_('AEC_CMN_NOT_SET'); ?></span></div>
							</div>
							<div class="control-group">
								<label class="control-label" for="ck_primary">
									<span><?php echo JText::_('AEC_USER_CURR_SUBSCR_PLAN_PRIMARY'); ?></span>
								</label>
								<input type="hidden" value="0" name="ck_primary"/>
								<div class="controls">
									<div class="toggleswitch">
										<label onclick="" class="toggleswitch">
											<input type="checkbox"<?php echo $metaUser->focusSubscription->primary ? ' checked="checked" disabled="disabled"" ' : ''; ?> name="ck_primary" id="ck_primary" value="1"/>
											<span class="toggleswitch-inner">
												<span class="toggleswitch-on">Yes</span>
												<span class="toggleswitch-off">No</span>
												<span class="toggleswitch-handle"></span>
											</span>
										</label>
									</div>
								</div>
							</div>
							<div class="control-group">
								<label class="control-label" for="expiration_current">
									<span><?php echo JText::_('AEC_USER_CURR_EXPIRE_DATE'); ?></span>
								</label>
								<div class="controls">
									<span><?php echo $metaUser->focusSubscription->lifetime ? JText::_('AEC_USER_LIFETIME') : HTML_AcctExp::DisplayDateInLocalTime( $exp ); ?></span>
								</div>
							</div>
							<div class="control-group">
								<label class="control-label" for="ck_lifetime">
									<span><?php echo JText::_('AEC_USER_LIFETIME'); ?></span>
								</label>
								<input type="hidden" value="0" name="ck_lifetime"/>
								<div class="controls">
									<div class="toggleswitch">
										<label onclick="" class="toggleswitch">
											<input type="checkbox"<?php echo $metaUser->focusSubscription->lifetime ? ' checked="checked" ' : ''; ?> name="ck_lifetime" id="ck_lifetime" value="1"/>
											<span class="toggleswitch-inner">
												<span class="toggleswitch-on">Yes</span>
												<span class="toggleswitch-off">No</span>
												<span class="toggleswitch-handle"></span>
											</span>
										</label>
									</div>
								</div>
							</div>
							<div class="control-group">
								<label class="control-label" for="expiration">
									<span><?php echo JText::_('AEC_USER_RESET_EXP_DATE'); ?></span>
								</label>
								<div class="controls">
									<?php echo JHTML::_('calendar', $exp, 'expiration', 'expiration', '%Y-%m-%d %H:%M:%S', array('class'=>'inputbox span5', 'size'=>'25',  'maxlength'=>'19' )); ?>
									<input type="hidden" name="expiration_check" id="expiration_check" value="<?php echo ( !empty( $exp ) ? $exp : date( 'Y-m-d H:i:s' ) ); ?>"/>
								</div>
							</div>
							<div class="control-group">
								<label class="control-label" for="set_status">
									<span><?php echo JText::_('AEC_USER_RESET_STATUS'); ?></span>
								</label>
								<div class="controls">
									<?php echo $lists['set_status']; ?>
								</div>
							</div>
							<?php } else { ?>
							<?php } ?>
							<div class="control-group">
								<label class="control-label" for="assignto_plan">
									<span><?php echo JText::_('AEC_USER_ASSIGN_TO_PLAN'); ?></span>
								</label>
								<div class="controls">
									<?php echo $lists['assignto_plan']; ?>
								</div>
							</div>
						</div>
						<div class="aec_userinfobox_sub">
							<h4><?php echo JText::_('AEC_USER_SUBSCRIPTION'); ?> History</h4>
								<?php if ( $metaUser->hasSubscription ) { ?>
								<div class="control-group">
									<label class="control-label" for="expiration"><span><?php echo JText::_('AEC_USER_PREV_SUBSCR_PLAN'); ?></span></label>
									<div class="controls"><span>#<?php echo $metaUser->focusSubscription->previous_plan; ?> - "<?php echo ( $metaUser->focusSubscription->previous_plan ? HTML_AcctExp::SubscriptionName( $metaUser->focusSubscription->previous_plan ) : '<span style="color:#FF0000;">' . JText::_('AEC_CMN_NOT_SET') . '</span>' ); ?>"</span></div>
								</div>
								<div class="control-group">
									<label class="control-label" for="expiration"><span><?php echo JText::_('AEC_USER_USED_PLANS'); ?></span></label>
									<div class="controls">
										<span>
											<?php if ( !empty( $metaUser->meta->plan_history->used_plans ) ) { ?>
												<ul>
												<?php foreach ( $metaUser->meta->plan_history->used_plans as $used => $amount ) { ?>
													<li>#<?php echo $used; ?> - "<?php echo HTML_AcctExp::SubscriptionName( $used ); ?>" (<?php echo $amount . " " . ( ( $amount > 1 ) ? JText::_('AEC_USER_TIMES') : JText::_('AEC_USER_TIME') ); ?>)</li>
												<?php } ?>
												</ul>
											<?php } else {
												echo JText::_('AEC_USER_NO_PREV_PLANS');
											} ?>
										</span>
									</div>
								</div>
								<?php } ?>
								<?php if ( $metaUser->hasSubscription && !empty( $metaUser->allSubscriptions ) ) { ?>
									<br />
									<p><strong><?php echo JText::_('AEC_USER_ALL_SUBSCRIPTIONS');?>:</strong></p>
									<table class="infobox_table table-striped">
										<tr>
											<th>&nbsp;</th>
											<th>&nbsp;</th>
											<th><?php echo JText::_('AEC_USER_SUBSCRIPTIONS_ID');?></th>
											<th><?php echo JText::_('AEC_USER_SUBSCRIPTIONS_STATUS');?></th>
											<th><?php echo JText::_('AEC_USER_SUBSCRIPTIONS_PROCESSOR');?></th>
											<th><?php echo JText::_('AEC_USER_SUBSCRIPTIONS_SINGUP');?></th>
											<th><?php echo JText::_('AEC_USER_SUBSCRIPTIONS_EXPIRATION');?></th>
										</tr>
										<?php foreach ( $metaUser->allSubscriptions as $subs ) { ?>
											<tr<?php echo isset( $subs->current_focus ) ? ' class="current-focus"' : ''; ?>>
												<td><?php echo isset( $subs->current_focus ) ? '<strong>&rArr;</strong>' : '&nbsp;'; ?></td>
												<td><?php echo $subs->primary ? aecHTML::Icon( 'star' ) : '&nbsp;'; ?></td>
												<td><?php echo !isset( $subs->current_focus ) ? '<a href="index.php?option=com_acctexp&amp;task=edit&subscriptionid=' . $subs->id . '">' . $subs->id . '</a>' : $subs->id; ?></td>
												<td><?php echo $subs->status; ?></td>
												<td><?php echo $subs->type; ?></td>
												<td><?php echo $subs->signup_date; ?></td>
												<td><?php echo $subs->lifetime ? JText::_('AEC_CMN_LIFETIME') : HTML_AcctExp::DisplayDateInLocalTime( $subs->expiration ); ?></td>
											</tr>
											<?php
										} ?>
									</table>
								<?php } elseif ( $metaUser->hasSubscription ) { ?>
									<p><?php echo JText::_('AEC_USER_ALL_SUBSCRIPTIONS_NOPE');?></p>
								<?php } else { ?>
									<div class="alert-message block-message warning" style="width:200px;">
										<p><?php echo JText::_('AEC_USER_ALL_SUBSCRIPTIONS_NONE');?></p>
									</div>
								<?php } ?>
						</div>
						<div class="aec_userinfobox_sub">
							<h4><?php echo 'Notes'; ?></h4>
							<textarea style="width:90%" cols="450" rows="10" name="notes" id="notes" ><?php echo ( !empty( $metaUser->focusSubscription->customparams['notes'] ) ? $metaUser->focusSubscription->customparams['notes'] : "" ); ?></textarea>
						</div>
					</td>
					<td width="50%" style="vertical-align:top;">
						<div class="aec_userinfobox_sub">
							<h4><?php echo JText::_('AEC_USER_USER_INFO'); ?></h4>
							<div class="aec_userinfobox_sub_inline" style="width:49%;">
								<div class="control-group">
									<label class="control-label" for="expiration"><span><?php echo JText::_('AEC_USER_USERID'); ?></span></label>
									<div class="controls"><span><?php echo $metaUser->userid; ?></div>
								</div>
								<div class="control-group">
									<label class="control-label" for="expiration"><span><?php echo JText::_('AEC_USER_STATUS'); ?></span></label>
									<div class="controls"><span><?php echo !$metaUser->cmsUser->block ? aecHTML::Icon( 'ok' ) . '&nbsp;' . JText::_('AEC_USER_ACTIVE') . '</strong>' : aecHTML::Icon( 'warning-sign' ) . '&nbsp;' . JText::_('AEC_USER_BLOCKED') . '</strong>' . ( ( $metaUser->cmsUser->activation == '' ) ? '' : ' (<a href="' . JURI::root() . $activateuserlink . '" target="_blank">' . JText::_('AEC_USER_ACTIVE_LINK') . '</a>)' ); ?></span></div>
								</div>
								<div class="control-group">
									<label class="control-label" for="expiration"><span><?php echo JText::_('AEC_USER_PROFILE'); ?></span></label>
									<div class="controls"><span><a href="<?php echo $edituserlink; ?>"><?php echo aecHTML::Icon( 'user' ); ?>&nbsp;<?php echo JText::_('AEC_USER_PROFILE_LINK'); ?></a></div>
								</div>
								<?php if ( GeneralInfoRequester::detect_component('anyCB') ) { ?>
									<div class="control-group">
										<label class="control-label" for="expiration"><span>CB Profile</span></label>
										<div class="controls"><span><?php echo '<a href="index.php?option=com_comprofiler&amp;task=edit&amp;cid=' . $metaUser->userid . '">' . aecHTML::Icon( 'user' ) . '&nbsp;' . JText::_('AEC_USER_PROFILE_LINK') . '</a>'; ?></span></div>
									</div>
								<?php } ?>
								<?php if ( GeneralInfoRequester::detect_component('JOMSOCIAL') ) { ?>
									<div class="control-group">
										<label class="control-label" for="expiration"><span>JomSocial Profile</span></label>
										<div class="controls"><span><?php echo '<a href="index.php?option=com_community&amp;view=users&amp;layout=edit&amp;id=' . $metaUser->userid . '">' . aecHTML::Icon( 'user' ) . '&nbsp;' . JText::_('AEC_USER_PROFILE_LINK') . '</a>'; ?></span></div>
									</div>
								<?php } ?>
							</div>
							<div class="aec_userinfobox_sub_inline" style="width:49%">
								<div class="control-group">
									<label class="control-label" for="expiration"><span><?php echo JText::_('AEC_USER_USERNAME'); ?></span></label>
									<div class="controls"><span><?php echo $metaUser->cmsUser->username; ?></span></div>
								</div>
								<div class="control-group">
									<label class="control-label" for="expiration"><span><?php echo JText::_('AEC_USER_NAME'); ?></span></label>
									<div class="controls"><span><?php echo $metaUser->cmsUser->name; ?></span></div>
								</div>
								<div class="control-group">
									<label class="control-label" for="expiration"><span><?php echo JText::_('AEC_USER_EMAIL'); ?></span></label>
									<div class="controls"><span><?php echo $metaUser->cmsUser->email; ?><br />(<a href="mailto:<?php echo $metaUser->cmsUser->email; ?>">&nbsp;<?php echo aecHTML::Icon( 'envelope' ); ?>&nbsp;<?php echo JText::_('AEC_USER_SEND_MAIL'); ?></a>)</div>
								</div>
								<?php if ( !defined( 'JPATH_MANIFESTS' ) ) { ?>
									<div class="control-group">
										<label class="control-label" for="expiration"><span><?php echo JText::_('AEC_USER_TYPE'); ?></span></label>
										<div class="controls"><span><?php echo $metaUser->cmsUser->usertype; ?></span></div>
									</div>
								<?php } ?>
								<div class="control-group">
									<label class="control-label" for="expiration"><span><?php echo JText::_('AEC_USER_REGISTERED'); ?></span></label>
									<div class="controls"><span><?php echo aecHTML::Icon( 'calendar' ); ?><?php echo $metaUser->cmsUser->registerDate; ?></span></div>
								</div>
								<div class="control-group">
									<label class="control-label" for="expiration"><span><?php echo JText::_('AEC_USER_LAST_VISIT'); ?></span></label>
									<div class="controls"><span><?php echo $metaUser->cmsUser->lastvisitDate; ?></span></div>
								</div>
							</div>
						</div>
						<div class="aec_userinfobox_sub">
							<h4><?php echo JText::_('AEC_USER_INVOICES'); ?></h4>
							<table class="infobox_table table-striped">
								<tr>
									<th><?php echo JText::_('HISTORY_COL_INVOICE');?></th>
									<th><?php echo JText::_('HISTORY_COL_AMOUNT');?></th>
									<th><?php echo JText::_('HISTORY_COL_DATE');?></th>
									<th><?php echo JText::_('HISTORY_COL_METHOD');?></th>
									<th><?php echo JText::_('HISTORY_COL_PLAN');?></th>
									<th><?php echo JText::_('HISTORY_COL_ACTION');?></th>
								</tr>
								<?php
								if ( !empty( $invoices ) ) {
									foreach ( $invoices as $invoice ) { ?>
										<tr<?php echo $invoice['rowstyle']; ?>>
											<td><?php echo $invoice['invoice_number']; ?></td>
											<td><?php echo $invoice['amount']; ?></td>
											<td><?php echo $invoice['status']; ?></td>
											<td><?php echo $invoice['processor']; ?></td>
											<td><?php echo $invoice['usage']; ?></td>
											<td style="text-align:center;"><?php echo $invoice['actions']; ?></td>
										</tr>
										<?php
									}

									echo '</table>';

									if ( $aecHTML->invoice_pages > 1 ) {
										echo '<div class="aec-invoices-pagination"><p>';
										$plist = array();
										for ( $i=0; $i<$aecHTML->invoice_pages; $i++ ) {
											if ( $i == $aecHTML->invoice_page ) {
												$plist[] = ( $i + 1 );
											} else {
												$plist[] = '<a href="index.php?option=com_acctexp&amp;task=edit&subscriptionid=' . $aecHTML->sid . '&page=' . $i . '">' . ( $i + 1 ) . '</a>';
											}
										}
										echo implode( '&nbsp;&middot;&nbsp;', $plist ) . '</p></div>';
									}
								} else {
									echo '<tr><td colspan="6" style="text-align:center;">&gt;&gt;&nbsp;'
									. JText::_('AEC_USER_NO_INVOICES')
									. '&nbsp;&lt;&lt;</td></tr>' . "\n";

									echo '</table>';
								} ?>
						</div>
						<div class="aec_userinfobox_sub">
							<h4><?php echo JText::_('AEC_USER_COUPONS'); ?></h4>
							<table class="infobox_table table-striped">
								<tr>
									<th><?php echo JText::_('HISTORY_COL_COUPON_CODE');?></th>
									<th><?php echo JText::_('HISTORY_COL_INVOICE');?></th>
								</tr>
								<?php
								if ( !empty( $coupons ) ) {
									foreach ( $coupons as $coupon ) { ?>
										<tr>
											<td><?php echo $coupon['coupon_code']; ?></td>
											<td><?php echo $coupon['invoices']; ?></td>
										</tr>
										<?php
									}
								} else {
									echo '<tr><td colspan="2" style="text-align:center;">&gt;&gt;&nbsp;'
									. JText::_('AEC_USER_NO_COUPONS')
									. '&nbsp;&lt;&lt;</td></tr>' . "\n";
								} ?>
							</table>
						</div>
					</td>
				</tr>
			</table>
			<?php
			$tabs->endPane();
			$tabs->startPane( 'mis' );
			?>
			<table class="aecadminform">
				<tr>
					<td>
						<div class="aec_userinfobox_sub">
						<?php if ( $metaUser->hasSubscription ) { ?>
							<p><a href="index.php?"><?php echo JText::_('AEC_USER_QUICKFIRE_GO'); ?></a></p>
						<?php } else { ?>
							<p><?php echo JText::_('AEC_USER_QUICKFIRE_UNAVAILABLE'); ?></p>
						<?php } ?>
						</div>
						<?php if ( !empty( $mi['profile'] ) || !empty( $mi['profile_form'] ) ) { ?>
							<div class="aec_userinfobox_sub">
							<?php if ( !empty( $mi['profile'] ) ) { ?>
								<?php foreach ( $mi['profile'] as $mix ) { ?>
									<div class="profileinfobox">
										<h4><?php echo $mix['name']; ?></h4>
										<p><?php echo $mix['info']; ?></p>
									</div>
								<?php } ?>
							<?php }
							if ( !empty( $mi['profile_form'] ) ) { ?>
								<?php foreach ( $mi['profile_form'] as $k ) { ?>
										<?php echo $aecHTML->createSettingsParticle( $k ); ?>
								<?php } ?>
							<?php } ?>
							</div>
						<?php } ?>
						<?php if ( !empty( $mi['admin'] ) || !empty( $mi['admin_form'] ) ) { ?>
							<div class="aec_userinfobox_sub">
							<?php if ( !empty( $mi['admin'] ) ) { ?>
								<?php foreach ( $mi['admin'] as $mix ) { ?>
									<div class="admininfobox">
										<h4><?php echo $mix['name']; ?></h4>
										<p><?php echo $mix['info']; ?></p>
									</div>
								<?php } ?>
							<?php }
							if ( !empty( $mi['admin_form'] ) ) { ?>
								<?php foreach ( $mi['admin_form'] as $k ) { ?>
										<?php echo $aecHTML->createSettingsParticle( $k ); ?>
								<?php } ?>
							<?php } ?>
							</div>
						<?php }
						if ( !empty( $metaUser->meta->params->mi ) ) { ?>
									<pre><?php print_r( $metaUser->meta->params->mi ); ?></pre>
						<?php } ?>
						</div>
					</td>
				</tr>
			</table>
			<?php
			$tabs->endPane();
			$tabs->endPanes();
			?>
			<input type="hidden" name="option" value="<?php echo $option; ?>" />
			<input type="hidden" name="id" value="<?php echo !empty( $metaUser->focusSubscription->id ) ? $metaUser->focusSubscription->id : ''; ?>" />
			<input type="hidden" name="userid" value="<?php echo $metaUser->userid; ?>" />
			<input type="hidden" name="task" value="" />
			<input type="hidden" name="nexttask" value="<?php echo $nexttask;?>" />
		</form>
		<?php
 		HTML_myCommon::endCommon();
	}

	function SubscriptionName( $subscriptionid )
	{
		$db = &JFactory::getDBO();

		$subscription = new SubscriptionPlan($db);
		$subscription->load($subscriptionid);

		return $subscription->name;
	}

	/**
	 * Builds a link plus button
	 *
	 * @param string	$link
	 * @param string	$image
	 * @param string	$text
	 * @param bool		$hideMenu
	 */
	function quickiconButton( $link, $image, $text, $hideMenu = false )
	{
		if ( $hideMenu ) {
			$hideMenu = '&amp;hidemainmenu=1';
		} ?>
		<div class="btn">
			<a href="<?php echo $link . $hideMenu; ?>">
				<?php HTML_myCommon::getSymbol( $image ); ?>
				<span><?php echo $text; ?></span>
			</a>
		</div>
	<?php
	}

	function quickSearchBar( $display, $searchcontent=null )
	{
		?>
		<div class="central_quicksearch aec_userinfobox_sub">
			<h3><?php echo JText::_('AEC_QUICKSEARCH'); ?></h3>
			<p><?php echo JText::_('AEC_QUICKSEARCH_DESC'); ?></p>
			<form action="<?php echo JURI::base(); ?>index.php?option=com_acctexp&amp;task=quicklookup" method="post">
			<input type="text" size="80" name="search" class="inputbox" value="<?php echo htmlspecialchars($searchcontent); ?>" />
			<input type="submit" class="btn btn-primary"/>
			</form>
			<?php
			if ( !empty( $display ) ) {
			?>
				<?php if ( !strpos( $display, '</div>' ) ) { ?>
				<h2><?php echo JText::_('AEC_QUICKSEARCH_MULTIRES'); ?></h2>
				<p><?php echo JText::_('AEC_QUICKSEARCH_MULTIRES_DESC'); ?></p>
				<?php } ?>
				<p><?php echo $display; ?></p>
			<?php
			}
			?>
		</div>
		<?php
	}

	function menuBar()
	{
		$menu = self::getMenu();

		$linkroot = "index.php?option=com_acctexp&amp;task=";
		?>
		<div class="topbar">
			<div class="topbar-inner">
			<div class="container">
				<a href="<?php echo $linkroot.'central' ?>" class="brand">&nbsp;</a>
				<ul class="nav">
				<?php foreach ( $menu as $m ) { ?>
					<?php if ( isset( $m['items'] ) ) { ?>
						<li class="dropdown">
							<a class="dropdown-toggle" data-toggle="dropdown" href="#"><?php echo $m['short'] ?><span class="caret"></span></a>
							<ul class="dropdown-menu">
							<?php
							foreach ( $m['items'] as $item ) {
								echo '<li><a id="aecmenu-' . str_replace( " ", "-", strtolower( $item[2] ) ) . '" href="' . $linkroot.$item[0] . '">' . $item[2] . '</a></li>';
							}
							?>
							</ul>
						</li>
					<?php } else { ?>
						<li>
							<a href="#" id="aecmenu-<?php echo str_replace( " ", "-", strtolower( $m['short'] ) ) ?>"><?php echo $m['short'] ?></a>
						</li>
					<?php } ?>
				<?php } ?>
				</ul>
				<form action="#" class="pull-right">
					<input type="text" rel="popover" class="span2" placeholder="Quicksearch" id="quicksearch" data-content="<?php echo JText::_('AEC_QUICKSEARCH_DESC'); ?>" data-original-title="Quicksearch">
				</form>
	        </div>
	      </div>
	    </div>
    <?php
	}

	function getMenu()
	{
		return array(	'memberships'	=> array(	'name'	=> JText::_('AEC_CENTR_AREA_MEMBERSHIPS'),
													'short'	=> JText::_('AEC_CENTR_AREA_MEMBERSHIPS'),
													'items'	=> array(	array( 'showExcluded', 'excluded', JText::_('AEC_CENTR_EXCLUDED') ),
																	array( 'showPending', 'pending', JText::_('AEC_CENTR_PENDING') ),
																	array( 'showActive', 'active', JText::_('AEC_CENTR_ACTIVE') ),
																	array( 'showExpired', 'expired', JText::_('AEC_CENTR_EXPIRED') ),
																	array( 'showCancelled', 'cancelled', JText::_('AEC_CENTR_CANCELLED') ),
																	array( 'showHold', 'hold', JText::_('AEC_CENTR_HOLD') ),
																	array( 'showClosed', 'closed', JText::_('AEC_CENTR_CLOSED') ),
																	array( 'showManual', 'manual', JText::_('AEC_CENTR_MANUAL') )
																	)
												),
						'payment' 		=> array(	'name'	=> JText::_('AEC_CENTR_AREA_PAYMENT'),
													'short'	=> JText::_('AEC_CENTR_AREA_PAYMENT_SHORT'),
													'items'	=> array(	array( 'showSubscriptionPlans', 'plans', JText::_('AEC_CENTR_PLANS') ),
																	array( 'showItemGroups', 'itemgroups', JText::_('AEC_CENTR_GROUPS') ),
																	array( 'showMicroIntegrations', 'microintegrations', JText::_('MI_TITLE'), JText::_('AEC_CENTR_M_INTEGRATION') ),
																	array( 'invoices', 'invoices', JText::_('AEC_CENTR_V_INVOICES') ),
																	array( 'showCoupons', 'coupons', JText::_('AEC_CENTR_COUPONS') ),
																	array( 'showCouponsStatic', 'coupons-static', JText::_('AEC_CENTR_COUPONS_STATIC') )
																	)
												),
						'settings' 		=> array(	'name'	=> JText::_('AEC_CENTR_AREA_SETTINGS'),
													'short'	=> JText::_('AEC_CENTR_AREA_SETTINGS_SHORT'),
													'items'	=> array(	array( 'showSettings', 'settings', JText::_('AEC_CENTR_SETTINGS') ),
																	array( 'showProcessors', 'settings', JText::_('AEC_CENTR_PROCESSORS') ),
																	array( 'editCSS', 'css', JText::_('AEC_CENTR_EDIT_CSS') ),
																	array( 'toolbox', 'toolbox', JText::_('AEC_CENTR_TOOLBOX') )
																	)
												),
						'data' 			=> array(	'name'	=> JText::_('AEC_CENTR_AREA_DATA'),
													'short'	=> JText::_('AEC_CENTR_AREA_DATA_SHORT'),
													'items'	=> array(	array( 'stats', 'stats', JText::_('AEC_CENTR_STATS') ),
																	array( 'exportmembers', 'export', JText::_('AEC_CENTR_EXPORT_MEMBERS') ),
																	array( 'exportsales', 'export', JText::_('AEC_CENTR_EXPORT_SALES') ),
																	array( 'import', 'import', JText::_('AEC_CENTR_IMPORT') ),
																	array( 'readout', 'export', JText::_('AEC_READOUT') ),
																	array( 'history', 'history', JText::_('AEC_CENTR_VIEW_HISTORY') ),
																	array( 'eventlog', 'eventlog', JText::_('AEC_CENTR_LOG') )
																	)
												),
						'help'			=> array(	'name'	=> JText::_('AEC_CENTR_AREA_HELP'),
													'short'	=> JText::_('AEC_CENTR_AREA_HELP')
												)
		);
	}

	function central( $display=null, $notices=null, $furthernotices=null, $searchcontent=null )
	{
		global $aecConfig;

		HTML_myCommon::startCommon();
		?>
		<table class="aecadminform_seamless">
			<tr>
				<td valign="top">
					<div id="aec_center">
						<?php
						$linkroot = "index.php?option=com_acctexp&amp;task=";

						$menu = self::getMenu();

						foreach ( $menu as $m ) {
							if ( empty( $m['items'] ) ) {
								continue;
							}

							?><div class="central_buttons aec_userinfobox_sub"><h3><?php echo $m['name']; ?></h3><div class="central_group"><?php

							foreach ( $m['items'] as $item ) {
								if ( !empty( $item[3] ) ) {
									HTML_AcctExp::quickiconButton( $linkroot.$item[0], ''.$item[1], $item[3] );
								} else {
									HTML_AcctExp::quickiconButton( $linkroot.$item[0], ''.$item[1], $item[2] );
								}
							}
							?></div></div><?php
						}

						if ( !empty( $notices ) ) {
						?>
						<div class="central_notices aec_userinfobox_sub">
							<h3><?php echo JText::_('AEC_NOTICES_FOUND'); ?></h3>
							<p><?php echo JText::_('AEC_NOTICES_FOUND_DESC'); ?></p>
							<p><a href="index.php?option=com_acctexp&amp;task=readAllNotices"><?php echo JText::_('AEC_NOTICE_MARK_ALL_READ'); ?></a></p>
							<div id="aec-alertlist">
								<?php
								$noticex = array( 2 => 'success', 8 => 'info', 32 => 'warning', 128 => 'error' );
								foreach( $notices as $notice ) {
								?>
									<div class="alert alert-<?php echo $noticex[$notice->level]; ?>" id="alert-<?php echo $notice->id; ?>">
										<a class="close" href="#<?php echo $notice->id; ?>" onclick="readNotice(<?php echo $notice->id; ?>)">&times;</a>
										<h5><strong><?php echo JText::_( "AEC_NOTICE_NUMBER_" . $notice->level ); ?>: <?php echo $notice->short; ?></strong></h5>
										<p><?php echo substr( htmlentities( stripslashes( $notice->event ) ), 0, 256 ); ?></p>
										<span class="help-block"><?php echo $notice->datetime; ?></span>
									</div>
								<?php
								}
								?>
							</div>
							<?php if ( $furthernotices > 0 ) { ?>
								<p id="further-notices"><span><?php echo $furthernotices; ?></span> <?php echo JText::_('further notice(s)'); ?></p>
							<?php } ?>
						</div>
						<?php
						}
						?>
					</div>
				</td>
				<td valign="top" class="centerlogo">
					<br />
					<center><img src="<?php echo JURI::root(); ?>media/com_acctexp/images/admin/icons/aec_logo_big.png" border="0" alt="AEC" width="200" height="232" /></center>
					<br />
					<div style="margin-left:auto;margin-right:auto;width:400px;text-align:center;"><p><strong>Account Expiration Control</strong> Component<br />Version <?php echo str_replace( 'omega', '&Omega;', _AEC_VERSION );; ?>, Revision <?php echo _AEC_REVISION ?></p>
						<p><img src="<?php echo JURI::root(); ?>media/com_acctexp/images/admin/gfx/aec_dist_title.jpg" border="0" alt="eta carinae nebula" class="dist-title" /></p>
						<p><?php echo JText::_('AEC_FOOT_TX_CHOOSING'); ?></p>
						<p>Please post a rating and a review for AEC<br />at the <a href="http://bit.ly/yGSrZQ" target="_blank">Joomla! Extensions Directory.</a></p>
						<div style="margin: 0 auto;text-align:center;">
							<a href="http://www.valanx.org"><img src="<?php echo JURI::root(); ?>media/com_acctexp/images/admin/gfx/valanx_logo.png" border="0" alt="valanx" /></a>
							<p><?php echo JText::_('AEC_FOOT_TX_GPL'); ?></p>
							<p><?php echo JText::_('AEC_FOOT_TX_SUBSCRIBE'); ?></p>
							<p><?php printf( JText::_('AEC_FOOT_CREDIT'), AECToolbox::backendTaskLink( 'credits', JText::_('AEC_FOOT_CREDIT_LTEXT') ) ); ?></p>
						</div>
					</div>
				</td>
			</tr>
		</table>
		<?php

		HTML_myCommon::endCommon(false);
	}

	function credits()
	{
		HTML_myCommon::startCommon();
		?>
		<style type="text/css">
			.installnote {
				width: 92%;
				margin: 6px 24px;
				color: #ddd;
			}
			.installnote h1 {
				color: #ddd;
				padding: 0;
			}
			.installnote p {
				color: #ddd;
				padding: 0 12px;
			}</style>
		<div style="width: 1024px; margin: 0 auto;">
		<div style="float: left; width: 600px; background: #000 url(<?php echo JURI::root(); ?>media/com_acctexp/images/admin/icons/aec_dist_gfx_0_14.png) no-repeat top right; margin: 0 6px;">
			<div style="width: 100%; height: 290px;"></div>
			<div class="installnote">
				<h1>Leading Programmer</h1>
				<p>David Deutsch</p>
				<h1>Contributing Programmers</h1>
				<p>Calum Polwart, William Jacobs, Muriel Grabert</p>
				<h1>Past Contributing Programmers</h1>
				<p>Helder 'hlblog' Garcia (started the first versions of AEC), Michael 'mic' Pagler, Steven 'corephp' Pignataro, Ben 'Slinky' Ingram, Charles 'Slydder' Williams, Mati 'mtk' Kochen, Ethan 'ethanchai' Chai Voon Chong</p>
				<h1>Graphics</h1>
				<p>All layout and graphics design as well as images are <a href="http://creativecommons.org/licenses/by-nc-sa/3.0/">CC-BY-NC-SA 3.0</a> 2006-2012 David 'skOre' Deutsch unless otherwise noted.</p>
				<p>Trademarks, Logos and other trade signs are property of their respective owners.</p>
				<h1>Libraries</h1>
				<p>The import function uses a modified parsecsv library by Jim Myhrberg - <a href="http://code.google.com/p/parsecsv-for-php/">code.google.com/p/parsecsv-for-php</a>.</p>
				<p>Furthermore, these libraries are used in one place or another: <a href="http://www.blueprintcss.org/">blueprint</a>, <a href="http://www.mootools.net/">mootools</a>, <a href="http://www.jquery.com/">jquery</a>, <a href="http://sourceforge.net/projects/nusoap">nusoap</a>, <a href="http://recaptcha.net/">recaptcha</a>.</p>
				<h1>Eternal Gratitude</h1>
				<p>These are the people without whom I could not have kept up the pace:</p>
				<p>William 'Jake' Jacobs, Aaron Varga, Calum 'polc1410' Polwart</p>
				<h1>Beta-Testers</h1>
				<p>People who have helped to check releases before they went out:</p>
				<p>Calum 'polc1410' Polwart, Aleksey Pikulik, Alex aka Sirooff</p>
				<h1>Contributors</h1>
				<p>People who have helped on our code by submitting additions and patches at one place or another:</p>
				<p>Kirk Lampert (who found lots and lots of rather embarrassing bugs), Rasmus Dahl-Sorensen, Paul van Jaarsveld, Tobias Bornakke, Levi Carter, Joel Bassett, Emmanuel Danan, Casey Eyring, Dioscouri Design, Carsten Engel, Joel Bassett, Emmanuel Danan, Rebekah Pitt, Daniel Lowhorn, berner, Mitchell Mink, Joshua Tan, Casey Eyring, Thailo van Ree, David Henry, Matthew Weeks, Francois Gagnon, Haris Agic</p>
				<h1>Translators</h1>
				<p>Jarno en Mark Baselier from Q5 Grafisch Webdesign (for help on Dutch translation), anderscarlen (Swedish translation), David Mara (Czech translation), Francois Gagnon (French translation), Ronny Buelund (Danish translation), Alexandros Seitaridis (Greek translation), Kristian from JOKR Solutions (Swedish translation), Masato Sato (Japanese translation), Christian Trujillo (Spanish Translation)</p>
				<p>Traduction fran&ccedil;aise par Garstud, Johnpoulain, Cobayes, cb75ter, Sharky</p>
			</div>
			<div style="width: 100%; height: 60px;"></div>
		</div>
		<div style="float: left; width: 400px; margin: 0 6px;">
			<div style="margin-left:auto;margin-right:auto;text-align:center;">
				<br />
				<p><img src="<?php echo JURI::root(); ?>media/com_acctexp/images/admin/icons/aec_logo_big.png" border="0" alt="" /></p>
				<br /><br />
				<p><strong>Account Expiration Control</strong> Component - Version <?php echo str_replace( 'omega', '&Omega;', _AEC_VERSION ); ?></p>
				<p><?php echo JText::_('AEC_FOOT_TX_CHOOSING'); ?></p>
				<div style="margin: 0 auto;text-align:center;">
					<a href="https://www.valanx.org" target="_blank"><img src="<?php echo JURI::root(); ?>media/com_acctexp/images/admin/gfx/valanx_logo.png" border="0" alt="valanx.org" /></a>
					<p><?php echo JText::_('AEC_FOOT_TX_GPL'); ?></p>
					<p><?php echo JText::_('AEC_FOOT_TX_SUBSCRIBE'); ?></p>
				</div>
			</div>
		</div>
		</div>
		<?php
	}

	function hacks ( $option, $hacks )
	{
		$infohandler	= new GeneralInfoRequester();
		HTML_myCommon::startCommon();
		?>
		<form action="index.php" method="post" name="adminForm" id="adminForm">
			<input type="hidden" name="option" value="<?php echo $option;?>" />
			<input type="hidden" name="task" value="" />
			<input type="hidden" name="returnTask" value="" />
			<input type="hidden" name="boxchecked" value="0" />
		</form>
		<?php HTML_myCommon::getHeader( 'AEC_HEAD_HACKS', 'settings' ); ?>
		<table class="aecadminform">
			<tr><td>
				<div style="width:100%; float:left;">
					<div class="alert-message block-message warning" style="width:350px; margin:5px;">
						<h2 style="color: #FF0000;"><?php echo JText::_('AEC_HACKS_NOTICE'); ?>:</h2>
						<p><?php echo JText::_('AEC_HACKS_NOTICE_DESC'); ?></p>
						<p><?php echo JText::_('AEC_HACKS_NOTICE_DESC2'); ?></p>
						<p><?php echo JText::_('AEC_HACKS_NOTICE_DESC3'); ?></p>
					</div>
				</div>
				<?php
				foreach ( $hacks as $handle => $content ) {
					if ( !$content['status'] ) {
						if ( isset($content['uncondition'] ) ) {
							if ( !empty( $hacks[$content['uncondition']]['status'] ) ) {
								continue ;
							}
						}
						if ( isset($content['condition'] ) ) {
							if ( empty( $hacks[$content['condition']]['status'] ) ) {
								continue ;
							}
						}
						if ( !empty($content['legacy'] ) ) {
							continue;
						}
					} ?>
					<div class="userinfobox">
						<a name="<?php echo $handle; ?>"></a>
						<h3><?php echo $content['name']; ?></h3>
						<div class="action">
							<?php
							echo aecHTML::Icon( $content['status'] ? 'ok' : 'remove' )
							. ' ' . ( $content['status'] ? JText::_('AEC_HACKS_ISHACKED') : JText::_('AEC_HACKS_NOTHACKED') ) ; ?>
							&nbsp;|&nbsp;
							 <a href="<?php echo 'index.php?option=com_acctexp&amp;task=hacks&amp;filename=' . $handle . '&amp;undohack=' . $content['status'] ?>#<?php echo $handle; ?>"><?php echo $content['status'] ? JText::_('AEC_HACKS_UNDO') : JText::_('AEC_HACKS_COMMIT') ; ?></a>
						</div>
						<?php
						if ( !empty( $content['important'] ) && !$content['status'] ) { ?>
							<div class="important">&nbsp;</div>
							<?php
						} ?>
						<p style="width:60%; padding:3px;">
							<?php echo $content['desc']; ?>
						</p>
						<?php if ( isset( $content['filename'] ) ) { ?>
							<div class="explainblock">
								<p>
									<strong><?php echo JText::_('AEC_HACKS_FILE'); ?>:&nbsp;<?php echo $content['filename']; ?></strong>
								</p>
							<?php
							if ( ( strcmp( $content['type'], 'file' ) === 0 ) && !$content['status'] ) {
								if ( empty( $content['legacy'] ) ) { ?>
									<p><?php echo JText::_('AEC_HACKS_LOOKS_FOR'); ?>:</p>
									<pre><?php print htmlentities( $content['read'] ); ?></pre>
									<p><?php echo JText::_('AEC_HACKS_REPLACE_WITH'); ?>:</p>
									<pre><?php print htmlentities( $content['insert'] ); ?></pre>
									<?php
								}
							} ?>
							</div>
							<?php
						} ?>
					</div>
					<?php
				} ?>
			</td></tr>
		</table>

 		<?php
 		HTML_myCommon::endCommon();
	}

	function Settings( $option, $aecHTML, $tab_data, $editors )
	{
		jimport( 'joomla.html.editor' );

		HTML_myCommon::startCommon();
		?>
		<form action="index.php" method="post" name="adminForm" id="adminForm" class="form-horizontal">
		<?php
		
		HTML_myCommon::getHeader( 'AEC_HEAD_SETTINGS', 'settings' );

		$buttons = array(	'apply' => array( 'style' => 'info', 'text' => JText::_('APPLY'), 'icon' => 'ok-sign' ),
							'save' => array( 'style' => 'success', 'text' => JText::_('SAVE'), 'icon' => 'ok' ),
							'hl1' => array(),
							'cancel' => array( 'style' => 'danger', 'text' => JText::_('CANCEL'), 'icon' => 'remove' )
						);
		HTML_myCommon::getButtons( $buttons, 'Settings' );

		$tabs = new bsPaneTabs;
		$tabs->startTabs();

		$first = true;
		foreach( $tab_data as $tab ) {
			$tabs->newTab( strtolower( str_replace( ' ', '-', $tab[0] ) ), $tab[0], $first );

			$first = false;
		}

		$tabs->endTabs();
		$tabs->startPanes();

		$first = true;
		foreach( $tab_data as $tab ) {
			$tabs->startPane( strtolower( str_replace( ' ', '-', $tab[0] ) ), $first );
			$first = false;

			echo '<table width="100%" class="aecadminform"><tr><td>';

			foreach ( $aecHTML->rows as $rowname => $rowcontent ) {
				echo $aecHTML->createSettingsParticle( $rowname );
				unset( $aecHTML->rows[$rowname] );
				// Skip to next tab if last item in this one reached
				if ( strcmp( $rowname, $tab[1] ) === 0 ) {
					echo '</td></tr></table>';
					$tabs->endPane();
					continue 2;
				}
			}

			echo '</td></tr></table>';
			$tabs->endPane();
		}

		$tabs->endPanes();
		?>
		<input type="hidden" name="id" value="1" />
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="option" value="<?php echo $option; ?>" />
		</form>
		<?php
		echo $aecHTML->loadJS();

 		HTML_myCommon::endCommon();
	}

	function listProcessors( $rows, $pageNav, $option )
	{
		HTML_myCommon::startCommon(); ?>
		<form action="index.php" method="post" name="adminForm" id="adminForm">
			<?php
			HTML_myCommon::getHeader( 'PROCESSORS_TITLE', 'settings' );

			$buttons = array(	'edit' => array( 'style' => 'warning', 'text' => JText::_('EDIT_PAYPLAN'), 'actionable' => true, 'icon' => 'pencil' ),
								'hl1' => array(),
								'pgs' => array( 'groupstart' => true ),
								'publish' => array( 'style' => 'info', 'text' => JText::_('PUBLISH_PAYPLAN'), 'actionable' => true, 'icon' => 'eye-open' ),
								'unpublish' => array( 'style' => 'danger', 'text' => JText::_('UNPUBLISH_PAYPLAN'), 'actionable' => true, 'icon' => 'eye-close' ),
								'pge' => array( 'groupend' => true ),
								'hl2' => array(),
								'new' => array( 'style' => 'success', 'text' => JText::_('NEW_PAYPLAN'), 'icon' => 'plus' )
							);
			HTML_myCommon::getButtons( $buttons, 'Processor' );
			?>
			<div class="aecadminform">
			<table class="adminlist">
				<thead><tr>
					<th width="1%">#</th>
					<th width="1%">id</th>
					<th width="1%"><input type="checkbox" name="toggle" value="" onClick="checkAll(<?php echo count( $rows ); ?>);" /></th>
					<th width="15%" align="left" nowrap="nowrap"><?php echo JText::_('PROCESSOR_NAME'); ?></th>
					<th align="left" nowrap="nowrap"><?php echo JText::_('PROCESSOR_INFO'); ?></th>
					<th width="3%" nowrap="nowrap"><?php echo JText::_('PROCESSOR_ACTIVE'); ?></th>
				</tr></thead>

		<?php
		$k = 0;
		for( $i=0, $n=count( $rows ); $i < $n; $i++ ) {
			$row = &$rows[$i]; ?>
				<tr class="row<?php echo $k; ?>">
					<td width="1%" align="center"><?php echo $i + 1 + $pageNav->limitstart; ?></td>
					<td width="1%" align="center"><?php echo $row->processor->id; ?></td>
					<td width="1%"><?php echo JHTML::_('grid.id', $i, $row->processor->id, false, 'id' ); ?></td>
					<td width="15%">
						<a href="#edit" onclick="return listItemTask('cb<?php echo $i; ?>','editProcessor')" title="<?php echo JText::_('AEC_CMN_CLICK_TO_EDIT'); ?>"><?php echo $row->processor->info['longname']; ?></a>
					</td>
					<td><?php echo $row->processor->info['statement']; ?></td>
					<td width="3%" align="center">
						<a href="javascript:void(0);" onClick="return listItemTask('cb<?php echo $i;?>','<?php echo $row->processor->active ? 'unpublishProcessor' : 'publishProcessor'; ?>')">
							<img src="<?php echo JURI::base(); ?>images/<?php echo $row->processor->active ? 'publish_g.png' : 'publish_x.png'; ?>" width="12" height="12" border="0" alt="<?php echo $row->processor->active ? JText::_('AEC_CMN_YES') : JText::_('AEC_CMN_NO'); ?>" title="<?php echo $row->processor->active ? JText::_('AEC_CMN_YES') : JText::_('AEC_CMN_NO'); ?>" />
						</a>
					</td>
				</tr>
			<?php
			$k = 1 - $k;
		} ?>
		<tfoot>
			<tr>
				<td colspan="6">
 					<?php echo $pageNav->getListFooter(); ?>
				</td>
			</tr>
		</tfoot>
		</table>
		</div>
		<input type="hidden" name="option" value="<?php echo $option; ?>" />
		<input type="hidden" name="task" value="showProcessors" />
		<input type="hidden" name="returnTask" value="showProcessors" />
		<input type="hidden" name="boxchecked" value="0" />
		</form>

		<?php
 		HTML_myCommon::endCommon();
	}

	function editProcessor( $option, $aecHTML )
	{
		HTML_myCommon::startCommon();

		HTML_myCommon::getHeader( 'AEC_HEAD_SETTINGS', 'settings', ( !empty( $aecHTML->pp->info['longname'] ) ? $aecHTML->pp->info['longname'] : '' ) );

		$buttons = array(	'apply' => array( 'style' => 'info', 'text' => JText::_('APPLY'), 'actionable' => true, 'icon' => 'ok-sign' ),
							'save' => array( 'style' => 'success', 'text' => JText::_('SAVE'), 'actionable' => true, 'icon' => 'ok' ),
							'hl1' => array(),
							'cancel' => array( 'style' => 'danger', 'text' => JText::_('CANCEL'), 'icon' => 'remove' )
						);
		HTML_myCommon::getButtons( $buttons, 'Processor' );

		$id = 0;
		if ( !empty( $aecHTML->pp ) ) {
			echo '<div class="aec-filters">';
			echo '<p style="text-align: center;"><img src="' . JURI::root( true ) . '/media/' . $option . '/images/site/gwlogo_' . $aecHTML->pp->processor_name . '.png" alt="' . $aecHTML->pp->processor_name . '" title="' . $aecHTML->pp->processor_name .'" class="plogo" /></p>';
			echo '</div>';

			$id = $aecHTML->pp->id;
		}
		
		?>
		<form action="index.php" method="post" name="adminForm" id="adminForm" class="form-horizontal">
            <table width="100%" class="aecadminform">
				<tr>
					<td valign="top">
						<div class="aec_userinfobox_sub">
							<h4><?php echo JText::_('AEC_HEAD_SETTINGS'); ?></h4>
							<?php
							foreach ( $aecHTML->rows as $rowname => $rowcontent ) {
								echo $aecHTML->createSettingsParticle( $rowname );
							}
							?>
						</div>
					</td>
				</tr>
			</table>
			<input type="hidden" name="id" value="<?php echo $id; ?>" />
			<input type="hidden" name="task" value="" />
			<input type="hidden" name="option" value="<?php echo $option; ?>" />
		</form>
		<?php
		echo $aecHTML->loadJS();

 		HTML_myCommon::endCommon();
	}

	function listSubscriptions( $rows, $pageNav, $search, $option, $lists, $subscriptionid, $action )
	{
		$user = &JFactory::getUser();

		HTML_myCommon::startCommon(); ?>
		<form action="index.php" method="post" name="adminForm" id="adminForm">
			<?php HTML_myCommon::getHeader( $action[1], '' . $action[0] ); ?>
			<div class="aec-filters">
				<div class="filter-sub">
					<?php echo $lists['groups'];?>
				</div>
				<div class="filter-sub">
					<?php if ( $action[0] != 'manual' ) { ?>
						<label><?php echo $lists['filterplanid']; ?></label>
					<?php } ?>
					<label><span><?php echo JText::_('ORDER_BY'); ?></span><?php echo $lists['orderNav']; ?></label>
					<label><span><?php echo JText::_('AEC_CMN_SEARCH'); ?></span><input type="text" name="search" value="<?php echo htmlspecialchars($search); ?>" class="inputbox span2" /></label>
				</div>
				<div class="filter-sub">
						<p>With selected users:</p>
						<label><?php echo $lists['planid']; ?></label>
						<?php if ( $action[0] != 'manual' ) { ?>
							<label><?php echo $lists['set_expiration']; ?></label>
						<?php } ?>
						<input type="button" class="btn btn-primary" onclick="document.adminForm.submit();" value="<?php echo JText::_('AEC_CMN_APPLY'); ?>"/>
				</div>
				<a id="filter-button" href="#">&#94;&#94;&nbsp;<?php echo JText::_('more'); ?>&nbsp;&#94;&#94;</a>
			</div>
			<div class="aecadminform">
			<table class="adminlist">
				<thead><tr>
					<th width="20">#</th>
					<th width="20"><input type="checkbox" name="toggle" value="" onClick="checkAll(<?php echo count( $rows ); ?>);" /></th>
					<th width="20">&nbsp;</th>
					<th width="15%" align="left" nowrap="nowrap"><?php echo JText::_('CNAME'); ?></th>
					<th width="10%" align="left" nowrap="nowrap"><?php echo JText::_('USERLOGIN'); ?></th>
					<th width="10%" align="left" nowrap="nowrap"><?php echo JText::_('AEC_CMN_STATUS'); ?></th>
					<th width="15%" align="left" nowrap="nowrap"><?php echo JText::_('SUBSCR_DATE'); ?></th>
					<?php if ( $action[0] != 'manual' ) { ?>
						<th width="15%" align="left" nowrap="nowrap"><?php echo JText::_('LASTPAY_DATE'); ?></th>
						<th width="10%" align="left" nowrap="nowrap"><?php echo JText::_('METHOD'); ?></th>
						<th width="10%" align="left" nowrap="nowrap"><?php echo JText::_('USERPLAN'); ?></th>
						<th width="27%" align="left" nowrap="nowrap"><?php echo JText::_('EXPIRATION'); ?></th>
					<?php } else { ?>
						<th width="15%" align="left" nowrap="nowrap"></th>
						<th width="10%" align="left" nowrap="nowrap"></th>
						<th width="10%" align="left" nowrap="nowrap"></th>
						<th width="27%" align="left" nowrap="nowrap"></th>
					<?php } ?>
				</tr></thead>
				<?php
				$k = 0;
				for( $i=0; $i < count( $rows ); $i++ ) {
					$row = &$rows[$i];

					if ( !isset( $row->status ) ) {
						$row->status		= '-';
						$row->lastpay_date	= '-';
						$row->type			= '-';
						$row->plan_name		= '-';
						$row->lifetime		= '-';
						$row->expiration	= '-';
					}

					$rowstyle = '';
					if ( is_array( $subscriptionid ) ) {
						if ( in_array( $row->id, $subscriptionid ) ) {
							$rowstyle = ' style="border: 2px solid #DD0;"';
						}
					} ?>
						<tr class="row<?php echo $k; ?>"<?php echo $rowstyle; ?>>
							<td width="20" align="center"><?php echo $i + 1 + $pageNav->limitstart; ?></td>
							<td width="20"><?php echo JHTML::_('grid.id', $i, $row->id, false, ( ( $action[0] == 'manual' ) ? 'userid' : 'subscriptionid' ) ); ?></td>
							<td width="20"><?php echo !empty( $row->primary ) ? aecHTML::Icon( 'star' ) : '&nbsp;'; ?></td>
							<td width="15%" align="left"><a href="#edit" onclick="return listItemTask('cb<?php echo $i; ?>','edit')" title="<?php echo JText::_('AEC_CMN_CLICK_TO_EDIT'); ?>"><?php echo $row->name; ?></a></td>
							<td width="10%" align="left"><?php echo $row->username; ?></td>
							<td width="10%" align="left"><?php echo $row->status; ?></td>
							<td width="15%" align="left"><?php echo HTML_AcctExp::DisplayDateInLocalTime( $row->signup_date ); ?></td>
							<?php if ( $action[0] != 'manual' ) { ?>
								<td width="15%" align="left"><?php echo HTML_AcctExp::DisplayDateInLocalTime( $row->lastpay_date ); ?></td>
								<td width="10%" align="left"><?php echo $row->type; ?></td>
								<td width="10%" align="left"><?php echo $row->plan_name; ?></td>
								<td width="27%" align="left"><?php echo $row->lifetime ? JText::_('AEC_CMN_LIFETIME') : HTML_AcctExp::DisplayDateInLocalTime($row->expiration); ?></td>
							<?php } else { ?>
								<td width="15%" align="left"></td>
								<td width="10%" align="left"></td>
								<td width="10%" align="left"></td>
								<td width="27%" align="left"></td>
							<?php } ?>
						</tr>
					<?php
					$k = 1 - $k;
				} ?>
		<tfoot>
			<tr>
				<td colspan="11">
 					<?php echo $pageNav->getListFooter(); ?>
				</td>
			</tr>
		</tfoot>
		</table>
		</div>
			<input type="hidden" name="option" value="<?php echo $option;?>" />
			<input type="hidden" name="task" value="showActive" />
			<input type="hidden" name="returnTask" value="showActive" />
			<input type="hidden" name="boxchecked" value="0" />
		</form>
 		<?php
 		HTML_myCommon::endCommon();
	}

	function listMicroIntegrations( $rows, $pageNav, $option, $lists, $search, $ordering )
	{
		HTML_myCommon::startCommon(); ?>
		<form action="index.php" method="post" name="adminForm" id="adminForm">
			<?php
			HTML_myCommon::getHeader( 'MI_TITLE', 'microintegrations' );

			$buttons = array(	'egs' => array( 'groupstart' => true ),
								'edit' => array( 'style' => 'warning', 'text' => JText::_('EDIT_PAYPLAN'), 'actionable' => true, 'icon' => 'pencil' ),
								'copy' => array( 'style' => 'info', 'text' => JText::_('COPY_PAYPLAN'), 'actionable' => true, 'icon' => 'share' ),
								'remove' => array( 'style' => 'danger', 'text' => JText::_('REMOVE_PAYPLAN'), 'actionable' => true, 'icon' => 'trash' ),
								'ege' => array( 'groupend' => true ),
								'hl1' => array(),
								'pgs' => array( 'groupstart' => true ),
								'publish' => array( 'style' => 'info', 'text' => JText::_('PUBLISH_PAYPLAN'), 'actionable' => true, 'icon' => 'eye-open' ),
								'unpublish' => array( 'style' => 'danger', 'text' => JText::_('UNPUBLISH_PAYPLAN'), 'actionable' => true, 'icon' => 'eye-close' ),
								'pge' => array( 'groupend' => true ),
								'hl2' => array(),
								'new' => array( 'style' => 'success', 'text' => JText::_('NEW_PAYPLAN'), 'icon' => 'plus' )
							);
			HTML_myCommon::getButtons( $buttons, 'MicroIntegration' );
			?>
			<div class="aec-filters">
			<table class="adminheading">
				<tr>
					<td style="text-align:center;">
						<?php echo JText::_('PLAN_FILTER'); ?>
						&nbsp;
						<?php echo $lists['filterplanid'] . '<br />' . JText::_('ORDER_BY') . $lists['orderNav']; ?>
						<input type="button" class="btn" onclick="document.adminForm.submit();" value="<?php echo JText::_('AEC_CMN_APPLY'); ?>" style="margin:2px;text-align:center;" />
					</td>
					<td style="white-space:nowrap; float:right; text-align:left; padding:3px; margin:3px;">
						<br />
						<br />
						<?php echo JText::_('AEC_CMN_SEARCH'); ?>
						<br />
						<input type="text" name="search" value="<?php echo htmlspecialchars($search); ?>" class="inputbox" onChange="document.adminForm.submit();" />
					</td>
				</tr>
				<tr><td></td></tr>
			</table>
			</div>

			<div class="aecadminform">
			<table class="adminlist">
				<thead><tr>
					<th width="20">#</th>
					<th width="20">id</th>
					<th width="20"><input type="checkbox" name="toggle" value="" onClick="checkAll(<?php echo count( $rows ); ?>);" /></th>
					<th width="15%" align="left" nowrap="nowrap"><?php echo JText::_('MI_NAME'); ?></th>
					<th width="20%" align="left" nowrap="nowrap" ><?php echo JText::_('MI_DESC'); ?></th>
					<th width="3%" nowrap="nowrap"><?php echo JText::_('MI_ACTIVE'); ?></th>
					<?php if ( $ordering ) { ?>
						<th width="5%" colspan="2" nowrap="nowrap"><?php echo JText::_('MI_REORDER'); ?></th>
					<?php } ?>
					<th width="5%" align="right" nowrap="nowrap"><?php echo JText::_('MI_FUNCTION'); ?></th>
				</tr></thead>

		<?php
		$k = 0;
		for( $i=0, $n=count( $rows ); $i < $n; $i++ ) {
			$row = &$rows[$i]; ?>
				<tr class="row<?php echo $k; ?>">
					<td width="20" align="center"><?php echo $i + 1 + $pageNav->limitstart; ?></td>
					<td width="20" align="center"><?php echo $row->id; ?></td>
					<td width="20"><?php echo JHTML::_('grid.id', $i, $row->id, false, 'id' ); ?></td>
					<td width="15%">
						<?php
						if (!isset($row->id)) {
							echo $row->name;
						} else { ?>
							<a href="#edit" onclick="return listItemTask('cb<?php echo $i; ?>','editMicroIntegration')" title="<?php echo JText::_('AEC_CMN_CLICK_TO_EDIT'); ?>"><?php echo $row->name; ?></a> <?php
						} ?>
					</td>
					<td width="20%" align="left">
						<?php
						echo $row->desc ? ( strlen( strip_tags( $row->desc ) > 50 ) ? substr( strip_tags( $row->desc ), 0, 50) . ' ...' : strip_tags( $row->desc ) ) : ''; ?>
						</td>
					<td width="3%" align="center">
						<a href="javascript:void(0);" onClick="return listItemTask('cb<?php echo $i;?>','<?php echo ($row->active) ? 'unpublishMicroIntegration' : 'publishMicroIntegration'; ?>')">
							<img src="<?php echo JURI::base(); ?>images/<?php echo ( $row->active ) ? 'publish_g.png' : 'publish_x.png'; ?>" width="12" height="12" border="0" alt="<?php echo ( $row->active ) ? JText::_('AEC_CMN_YES') : JText::_('AEC_CMN_NO'); ?>" title="<?php echo ( $row->active ) ? JText::_('AEC_CMN_YES') : JText::_('AEC_CMN_NO'); ?>" />
						</a>
					</td>
					<?php if ( $ordering ) { ?>
						<td align="right"><?php echo $pageNav->orderUpIcon( $i, true, 'ordermiup' ); ?></td>
						<td align="right"><?php echo $pageNav->orderDownIcon( $i, $n, true, 'ordermidown' ); ?></td>
					<?php } ?>
					<td width="45%" align="right"><?php echo $row->class_name; ?></td>
				</tr>
			<?php
			$k = 1 - $k;
		} ?>
		<tfoot>
			<tr>
				<td colspan="9">
 					<?php echo $pageNav->getListFooter(); ?>
				</td>
			</tr>
		</tfoot>
		</table>
		</div>
		<input type="hidden" name="option" value="<?php echo $option; ?>" />
		<input type="hidden" name="task" value="showMicroIntegrations" />
		<input type="hidden" name="returnTask" value="showMicroIntegrations" />
		<input type="hidden" name="boxchecked" value="0" />
		</form>
		<?php
 		HTML_myCommon::endCommon();
	}

	function editMicroIntegration( $option, $row, $lists, $aecHTML )
	{
		HTML_myCommon::startCommon();
		?>
		<script type="text/javascript">
			/* <![CDATA[ */
			/*
			function swap() {
				lifechecked = document.adminForm.ck_lifetime.checked;
				if (lifechecked==false) {
					document.adminForm.expiration.disabled = false;
					document.adminForm.reset.disabled = false;
				} else {
					document.adminForm.expiration.disabled = true;
					document.adminForm.reset.disabled = true;
				}
			}
			*/
			/* ]]> */
		</script>
		<?php
		HTML_myCommon::getHeader( 'AEC_HEAD_SETTINGS', 'microintegrations', $row->id ? $row->name : JText::_('AEC_CMN_NEW') );

		$buttons = array(	'apply' => array( 'style' => 'info', 'text' => JText::_('APPLY'), 'actionable' => true, 'icon' => 'ok-sign' ),
							'save' => array( 'style' => 'success', 'text' => JText::_('SAVE'), 'actionable' => true, 'icon' => 'ok' ),
							'hl1' => array(),
							'cancel' => array( 'style' => 'danger', 'text' => JText::_('CANCEL'), 'icon' => 'remove' )
						);
		HTML_myCommon::getButtons( $buttons, 'MicroIntegration' );

		$tabs = new bsPaneTabs;
		$tabs->startTabs();

		$tabs->newTab( 'mi', JText::_('MI_E_TITLE'), true );

		if ( $aecHTML->hasSettings ) {
			$tabs->newTab( 'settings', JText::_('MI_E_SETTINGS') );
		}

		if ( !empty( $aecHTML->customparams ) ) {
			foreach ( $aecHTML->customparams as $name ) {
				if ( strpos( $name, 'aectab_' ) === 0 ) {
					$tabs->newTab( $name, $aecHTML->rows[$name][1] );
				}
			}
		}

		$tabs->endTabs();

		?>
		<form action="index.php" method="post" name="adminForm" id="adminForm" enctype="multipart/form-data" class="form-horizontal">
			<?php
			$tabs->startPanes();
            $tabs->startPane( 'mi', true );
            ?>
            <table width="100%" class="aecadminform">
				<tr>
				<td valign="top">
					<div class="aec_userinfobox_sub">
						<h4><?php echo JText::_('MI_E_TITLE_LONG'); ?></h4>
						<?php echo $aecHTML->createSettingsParticle( 'active' ); ?>
						<?php echo $aecHTML->createSettingsParticle( 'name' ); ?>
						<?php echo $aecHTML->createSettingsParticle( 'desc' ); ?>
						<?php echo $aecHTML->createSettingsParticle( '_aec_action' ); ?>
						<?php echo $aecHTML->createSettingsParticle( '_aec_only_first_bill' ); ?>
						<?php echo $aecHTML->createSettingsParticle( 'auto_check' ); ?>
						<?php echo $aecHTML->createSettingsParticle( '_aec_global_exp_all' ); ?>
						<?php echo $aecHTML->createSettingsParticle( 'on_userchange' ); ?>
						<?php echo $aecHTML->createSettingsParticle( 'pre_exp_check' ); ?>
					</div>
				</td>
				<td valign="top">
					<div class="aec_userinfobox_sub">
						<h4><?php echo JText::_('MI_E_DETAILS') . ' - ' . JText::_('MI_E_FUNCTION_NAME'); ?></h4>
						<div style="position:relative;">
						<?php if ( !$aecHTML->hasSettings ) {
							if ( $lists['class_name'] ) {
								echo $lists['class_name']; ?>
								
								<?php
								echo "<p>" . JText::_('MI_E_FUNCTION_DESC') . "</p>";
							} else {
								echo "<p>" . JText::_('AEC_MSG_MIS_NOT_DEFINED') . "</p>";
							}
						} else {
							echo "<p><strong>" . $row->class_name . "</p></strong>";
						}
						?>
						</div>
					</div>
					<?php if ( !empty( $aecHTML->hasHacks ) ) { ?>
						<div class="aec_userinfobox_sub">
						<h4><?php echo JText::_('MI_E_HACKS_NAME'); ?></h4>
							<div style="position:relative;">
							<?php echo JText::sprintf('MI_E_HACKS_DETAILS', "index.php?option=com_acctexp&amp;task=hacks"); ?>
							</div>
						</div>
					<?php } ?>
					</td>
				</tr>
			</table>
			<?php
            $tabs->endPane();

			if ( $aecHTML->hasSettings ) {
	            $tabs->startPane( 'settings' ); ?>
	            <table width="100%" class="aecadminform">
					<tr>
						<td valign="top">
			            	<div class="aec_userinfobox_sub">
			            	<h4><?php echo JText::_('MI_E_SETTINGS'); ?></h4>
							<?php
							foreach ( $aecHTML->customparams as $name ) {
								if ( strpos( $name, 'aectab_' ) === 0 ) {
									?>
											</div>
										</td>
									</tr>
									</table>
									<?php
									$tabs->endPane();
									$tabs->startPane( $name ); ?>
					                <table width="100%" class="aecadminform">
										<tr>
											<td valign="top">
								            	<div class="aec_userinfobox_sub">
								            	<h4><?php echo $aecHTML->rows[$name][1]; ?></h4>
									<?php
								} else {
									if ( strpos( $aecHTML->rows[$name][1], 'editlinktip hasTip' ) ) {
										echo '<tr><td>';
									}

			                		echo $aecHTML->createSettingsParticle( $name );

			                		if ( strpos( $aecHTML->rows[$name][1], 'editlinktip hasTip' ) ) {
										echo '</td></tr>';
									}
			            		}
							} ?>
							</div>
						</td>
					</tr>
				</table>
				<?php
	            $tabs->endPane();
			}

            $tabs->endPanes(); ?>

			<input type="hidden" name="id" value="<?php echo $row->id; ?>" />
			<input type="hidden" name="option" value="<?php echo $option; ?>" />
			<input type="hidden" name="task" value="" />
		</form>

		<?php
		echo $aecHTML->loadJS();

 		HTML_myCommon::endCommon();
	}

	function listSubscriptionPlans( $rows, $lists, $pageNav, $option )
	{
		HTML_myCommon::startCommon(); ?>
		<form action="index.php" method="post" name="adminForm" id="adminForm">
			<?php
			HTML_myCommon::getHeader( 'PAYPLANS_TITLE', 'plans' );

			$buttons = array(	'egs' => array( 'groupstart' => true ),
								'edit' => array( 'style' => 'warning', 'text' => JText::_('EDIT_PAYPLAN'), 'actionable' => true, 'icon' => 'pencil' ),
								'copy' => array( 'style' => 'info', 'text' => JText::_('COPY_PAYPLAN'), 'actionable' => true, 'icon' => 'share' ),
								'remove' => array( 'style' => 'danger', 'text' => JText::_('REMOVE_PAYPLAN'), 'actionable' => true, 'icon' => 'trash' ),
								'ege' => array( 'groupend' => true ),
								'hl1' => array(),
								'pgs' => array( 'groupstart' => true ),
								'publish' => array( 'style' => 'info', 'text' => JText::_('PUBLISH_PAYPLAN'), 'actionable' => true, 'icon' => 'eye-open' ),
								'unpublish' => array( 'style' => 'danger', 'text' => JText::_('UNPUBLISH_PAYPLAN'), 'actionable' => true, 'icon' => 'eye-close' ),
								'pge' => array( 'groupend' => true ),
								'hl2' => array(),
								'new' => array( 'style' => 'success', 'text' => JText::_('NEW_PAYPLAN'), 'icon' => 'plus' )
							);
			HTML_myCommon::getButtons( $buttons, 'SubscriptionPlan' );
			?>
			<div class="aec-filters">
			<?php echo $lists['filter_group'];?>
			<input type="button" class="btn" onclick="document.adminForm.submit();" value="<?php echo JText::_('AEC_CMN_APPLY'); ?>" style="margin:2px;text-align:center;" />
			</div>

			<div class="aecadminform">
			<table class="adminlist">
				<thead><tr>
					<th width="1%">#</th>
					<th width="1%"><?php echo JText::_('AEC_CMN_ID'); ?></th>
					<th width="1%"><input type="checkbox" name="toggle" value="" onClick="checkAll(<?php echo count( $rows ); ?>);" /></th>
					<th width="1%" align="left" nowrap="nowrap"><?php echo JText::_('PAYPLAN_GROUP'); ?></th>
					<th width="10%" align="left" nowrap="nowrap"><?php echo JText::_('PAYPLAN_NAME'); ?></th>
					<th width="20%" align="left" nowrap="nowrap"><?php echo JText::_('PAYPLAN_DESC'); ?></th>
					<th width="3%" nowrap="nowrap"><?php echo JText::_('PAYPLAN_ACTIVE'); ?></th>
					<th width="3%" nowrap="nowrap"><?php echo JText::_('PAYPLAN_VISIBLE'); ?></th>
					<th width="5%" colspan="2" nowrap="nowrap"><?php echo JText::_('PAYPLAN_REORDER'); ?></th>
					<th width="5%" nowrap="nowrap" align="center"><?php echo JText::_('PAYPLAN_USERCOUNT'); ?></th>
					<th width="5%" nowrap="nowrap" align="center"><?php echo JText::_('PAYPLAN_EXPIREDCOUNT'); ?></th>
					<th width="5%" nowrap="nowrap" align="center"><?php echo JText::_('PAYPLAN_TOTALCOUNT'); ?></th>
				</tr></thead>

		<?php
		$k = 0;
		for( $i=0, $n=count( $rows ); $i < $n; $i++ ) {
				switch( $rows[$i]->visible ) {
					case '1':
						$vaction	= 'invisible';
						$vicon		= 'eye-open';
						break;

					case '0':
						$vaction	= 'visible';
						$vicon		= 'eye-close';
						break;
				}

				switch( $rows[$i]->active ) {
					case '1':
						$aaction	= 'unpublish';
						$aicon		= 'ok-circle';
						break;

					case '0':
						$aaction	= 'publish';
						$aicon		= 'remove-circle';
						break;
				}

				if ( !is_null( $rows[$i]->desc ) ) {
					$description = strip_tags( $rows[$i]->desc );
					if ( strlen( $description ) > 50 ) {
						$description = substr( $description, 0, 50) . ' ...';
					}
				} else {
					$description = '';
				}

				?>
				<tr class="row<?php echo $k; ?>">
					<td align="center"><?php echo $i + 1 + $pageNav->limitstart; ?></td>
					<td align="center"><?php echo $rows[$i]->id; ?></td>
					<td align="center"><?php echo JHTML::_('grid.id', $i, $rows[$i]->id, false, 'id' ); ?></td>
					<td align="center" style="background: #<?php echo $rows[$i]->color; ?>;"><?php echo $rows[$i]->group; ?></td>
					<td><a href="#edit" onclick="return listItemTask('cb<?php echo $i; ?>','editSubscriptionPlan')" title="<?php echo JText::_('AEC_CMN_CLICK_TO_EDIT'); ?>"><?php echo stripslashes( $rows[$i]->name ); ?></a></td>
					<td  align="left">
						<?php
						echo stripslashes( $description ); ?>
					</td>
					<td align="center">
						<a href="javascript:void(0);" onClick="return listItemTask('cb<?php echo $i;?>','<?php echo $aaction; ?>SubscriptionPlan')">
							<?php echo aecHTML::Icon( $aicon ); ?>
						</a>
					</td>
					<td align="center">
						<a href="javascript:void(0);" onClick="return listItemTask('cb<?php echo $i;?>','<?php echo $vaction; ?>SubscriptionPlan')">
							<?php echo aecHTML::Icon( $vicon ); ?>
						</a>
					</td>
					<td align="right"><?php echo $pageNav->orderUpIcon( $i, true, 'orderplanup' ); ?></td>
					<td align="right"><?php echo $pageNav->orderDownIcon( $i, $n, true, 'orderplandown' ); ?></td>
					<td align="center"><a href="index.php?option=com_acctexp&amp;task=showSubscriptions&amp;plan=<?php echo $rows[$i]->id; ?>"><strong><?php echo $rows[$i]->usercount; ?></strong></a></td>
					<td align="center"><a href="index.php?option=com_acctexp&amp;task=showExpired&amp;plan=<?php echo $rows[$i]->id; ?>"><?php echo $rows[$i]->expiredcount; ?></a></td>
					<td align="center"><a href="index.php?option=com_acctexp&amp;task=showAllSubscriptions&amp;plan=<?php echo $rows[$i]->id; ?>"><strong><?php echo $rows[$i]->usercount + $rows[$i]->expiredcount; ?></strong></a></td>
				</tr>
			<?php
			$k = 1 - $k;
		} ?>
		<tfoot>
			<tr>
				<td colspan="13">
 					<?php echo $pageNav->getListFooter(); ?>
				</td>
			</tr>
		</tfoot>
		</table>
		</div>
		<input type="hidden" name="option" value="<?php echo $option;?>" />
		<input type="hidden" name="task" value="showSubscriptionPlans" />
		<input type="hidden" name="returnTask" value="showSubscriptionPlans" />
		<input type="hidden" name="boxchecked" value="0" />
	</form>

		<?php
 		HTML_myCommon::endCommon();
	}

	function editSubscriptionPlan( $option, $aecHTML, $row, $hasrecusers )
	{
		jimport( 'joomla.html.editor' );

		$editor =& JFactory::getEditor();

		HTML_myCommon::startCommon();

		HTML_myCommon::getHeader( '', 'plans', $row->id ? $row->getProperty( 'name' ) : JText::_('AEC_HEAD_PLAN_INFO') . JText::_('AEC_CMN_NEW') );

		$buttons = array(	'apply' => array( 'style' => 'info', 'text' => JText::_('APPLY'), 'actionable' => true, 'icon' => 'ok-sign' ),
							'save' => array( 'style' => 'success', 'text' => JText::_('SAVE'), 'actionable' => true, 'icon' => 'ok' ),
							'hl1' => array(),
							'cancel' => array( 'style' => 'danger', 'text' => JText::_('CANCEL'), 'icon' => 'remove' )
						);
		HTML_myCommon::getButtons( $buttons, 'SubscriptionPlan' );

		$tabs = new bsPaneTabs;
		$tabs->startTabs();

		$tabs->newTab( 'plan', JText::_('PAYPLAN_DETAIL_TITLE'), true );
		$tabs->newTab( 'processors', JText::_('PAYPLAN_PROCESSORS_TITLE') );
		$tabs->newTab( 'text', JText::_('PAYPLAN_TEXT_TITLE') );
		$tabs->newTab( 'restrictions', JText::_('PAYPLAN_RESTRICTIONS_TITLE') );
		$tabs->newTab( 'trial', JText::_('PAYPLAN_TRIAL_TITLE') );
		$tabs->newTab( 'relations', JText::_('PAYPLAN_RELATIONS_TITLE') );
		$tabs->newTab( 'mis', JText::_('PAYPLAN_MI') );
		$tabs->endTabs();

		?>
		<form action="index.php" method="post" name="adminForm" id="adminForm" enctype="multipart/form-data" class="form-horizontal">
			<?php
			$tabs->startPanes();
			$tabs->startPane( 'plan', true );
			?>
			<table class="aecadminform">
				<tr>
					<td valign="top">
						<div style="position:relative;float:left;width:33.225%;">
							<div class="userinfobox">
								<div class="aec_userinfobox_sub">
									<h4>General</h4>
									<?php echo $aecHTML->createSettingsParticle( 'name' ); ?>
									<?php echo $aecHTML->createSettingsParticle( 'active' ); ?>
									<?php echo $aecHTML->createSettingsParticle( 'visible' ); ?>
									<div style="position:relative;width:100%;">
										<?php
										if ( $row->id ) { ?>
											<p style="padding:8px;padding-left:80px;">
												<a href="<?php echo str_replace("/administrator/", "/", AECToolbox::deadsureURL( 'index.php?option=com_acctexp&task=subscribe&usage=' . $row->id ) ); ?>" title="<?php echo JText::_('AEC_CGF_LINK_ABO_FRONTEND'); ?>" target="_blank"><?php echo JText::_('AEC_CGF_LINK_ABO_FRONTEND'); ?></a>
												&nbsp;|&nbsp;<a href="<?php echo str_replace("/administrator/", "/", AECToolbox::deadsureURL( 'index.php?option=com_acctexp&task=addtocart&usage=' . $row->id ) ); ?>" title="<?php echo JText::_('AEC_CGF_LINK_CART_FRONTEND'); ?>" target="_blank"><?php echo JText::_('AEC_CGF_LINK_CART_FRONTEND'); ?></a>
											</p>
											<?php
										}
										?>
									</div>
								</div>
								<div class="aec_userinfobox_sub">
									<h4>Details</h4>
									<?php echo $aecHTML->createSettingsParticle( 'make_active' ); ?>
									<?php echo $aecHTML->createSettingsParticle( 'make_primary' ); ?>
									<?php echo $aecHTML->createSettingsParticle( 'update_existing' ); ?>
									<?php echo $aecHTML->createSettingsParticle( 'fixed_redirect' ); ?>
								</div>

							</div>
							<div class="userinfobox">
								<div class="aec_userinfobox_sub">
									<h4><?php echo JText::_('ITEMGROUPS_TITLE'); ?></h4>
									<table style="width:100%;">
										<tr>
											<th>ID</td>
											<th>Name</td>
											<th>delete</td>
										</tr>
										<?php
										if ( !empty( $aecHTML->customparams->groups ) ) {
											foreach ( $aecHTML->customparams->groups as $id => $group ) {
												?>
												<tr>
													<td align="right" style="background: #<?php echo $group['color']; ?>;"><?php echo $group['group']; ?></td>
													<td><?php echo $group['name']; ?></td>
													<td><?php echo $aecHTML->createSettingsParticle( 'group_delete_'.$id ); ?></td>
												</tr>
												<?php
											}
										}
										?>
										<tr>
											<td><?php echo JText::_('NEW_ITEMGROUP'); ?>:</td>
											<td colspan="2"><?php echo $aecHTML->createSettingsParticle( 'add_group' ); ?></td>
										</tr>
									</table>
								</div>
							</div>
						</div>
						<div style="position:relative;float:left;width:33.225%;">
							<div class="userinfobox">
								<div class="aec_userinfobox_sub">
									<h4>Cost&amp;Details</h4>
									<?php echo $aecHTML->createSettingsParticle( 'full_free' ); ?>
									<?php echo $aecHTML->createSettingsParticle( 'full_amount' ); ?>
									<?php echo $aecHTML->createSettingsParticle( 'lifetime' ); ?>
									<?php echo $aecHTML->createSettingsParticle( 'full_period' ); ?>
									<?php echo $aecHTML->createSettingsParticle( 'full_periodunit' ); ?>
									<?php echo $aecHTML->createSettingsParticle( 'hide_duration_checkout' ); ?>
									<?php if ( $hasrecusers ) { ?>
										<div class="alert-message block-message warning" style="width:200px;">
											<strong><?php echo JText::_('PAYPLAN_AMOUNT_EDITABLE_NOTICE'); ?></strong>
										</div>
									<?php } ?>
								</div>
							</div>
							<div class="userinfobox">
								<div class="aec_userinfobox_sub">
									<h4>Joomla User</h4>
									<?php echo $aecHTML->createSettingsParticle( 'gid_enabled' ); ?>
									<?php echo $aecHTML->createSettingsParticle( 'gid' ); ?>
									<?php echo $aecHTML->createSettingsParticle( 'override_activation' ); ?>
									<?php echo $aecHTML->createSettingsParticle( 'override_regmail' ); ?>
								</div>
							</div>
						</div>
						<div style="position:relative;float:left;width:33.225%;">
							<div class="userinfobox">
								<div class="aec_userinfobox_sub">
									<h4>Plan Relation</h4>
									<?php echo $aecHTML->createSettingsParticle( 'fallback' ); ?>
									<?php echo $aecHTML->createSettingsParticle( 'fallback_req_parent' ); ?>
									<?php echo $aecHTML->createSettingsParticle( 'standard_parent' ); ?>
								</div>
								<div class="aec_userinfobox_sub">
									<h4>Shopping Cart</h4>
									<?php echo $aecHTML->createSettingsParticle( 'cart_behavior' ); ?>
									<?php echo $aecHTML->createSettingsParticle( 'addtocart_redirect' ); ?>
								</div>
							</div>
							<div class="userinfobox">
								<div class="aec_userinfobox_sub">
									<h4><?php echo 'Notes'; ?></h4>
									<div style="text-align: left;">
										<?php echo $aecHTML->createSettingsParticle( 'notes' ); ?>
									</div>
								</div>
							</div>
						</div>
					</td>
				</tr>
			</table>
			<?php
            $tabs->endPane();
            $tabs->startPane( 'processors' );
			?>
			<table width="100%" class="aecadminform"><tr><td>
				<?php
				if ( !empty( $aecHTML->customparams->pp ) ) {
					foreach ( $aecHTML->customparams->pp as $id => $processor ) {
						?>
						<div class="aec_userinfobox_sub clear" style="max-width: 600px;margin: 4px auto;">
							<h2 style="clear:both;"><?php echo $processor['name']; ?></h2>
							<p><a href="<?php echo str_replace("/administrator/", "/", AECToolbox::deadsureURL( 'index.php?option=com_acctexp&task=subscribe&usage=' . $row->id . '&processor=' . $processor['handle'] ) ); ?>" title="<?php echo JText::_('AEC_CGF_LINK_ABO_FRONTEND'); ?>" target="_blank"><?php echo JText::_('AEC_CGF_LINK_ABO_FRONTEND'); ?></a></p>
							<?php
							foreach ( $processor['params'] as $customparam ) {
								echo $aecHTML->createSettingsParticle( $customparam );
							}
							?>
						</div>
						<?php
					}
				}
				?>
			</td></tr></table>
			<?php
            $tabs->endPane();
            $tabs->startPane( 'text' );
            ?>
            <table width="100%" class="aecadminform"><tr><td>
				<div class="aec_userinfobox_sub">
					<?php echo $aecHTML->createSettingsParticle( 'customamountformat' ); ?>
					<?php echo $aecHTML->createSettingsParticle( 'desc' ); ?>
					<?php echo $aecHTML->createSettingsParticle( 'email_desc' ); ?>
					<?php echo $aecHTML->createSettingsParticle( 'customthanks' ); ?>
					<?php echo $aecHTML->createSettingsParticle( 'customtext_thanks_keeporiginal' ); ?>
					<?php echo $aecHTML->createSettingsParticle( 'customtext_thanks' ); ?>
				</div>
			</td></tr></table>
			<?php
            $tabs->endPane();
            $tabs->startPane( 'restrictions' );
            ?>
			<table class="aecadminform">
				<tr><td>
					<div style="position:relative;float:left;width:49%;padding:4px;">
						<div class="aec_userinfobox_sub">
							<h4><?php echo JText::_('AEC_RESTRICTIONS_INVENTORY_HEADER'); ?></h4>
							<?php echo $aecHTML->createSettingsParticle( 'inventory_amount_enabled' ); ?>
							<?php echo $aecHTML->createSettingsParticle( 'inventory_amount' ); ?>
							<?php echo $aecHTML->createSettingsParticle( 'inventory_amount_used' ); ?>
						</div>
					</div>
					<div style="position:relative;float:left;width:49%;padding:4px;">
						<div class="aec_userinfobox_sub">
							<h4><?php echo JText::_('AEC_RESTRICTIONS_REDIRECT_HEADER'); ?></h4>
							<?php echo $aecHTML->createSettingsParticle( 'notauth_redirect' ); ?>
						</div>
					</div>
				</td></tr>
				<?php echo aecRestrictionHelper::echoSettings( $aecHTML ); ?>
				<tr><td>
						<div class="aec_userinfobox_sub">
							<h4><?php echo JText::_('AEC_RESTRICTIONS_CUSTOM_HEADER'); ?></h4>
							<?php echo $aecHTML->createSettingsParticle( 'custom_restrictions_enabled' ); ?>
							<?php echo $aecHTML->createSettingsParticle( 'custom_restrictions' ); ?>
							<br />
							<?php echo $aecHTML->createSettingsParticle( 'rewriteInfo' ); ?>
						</div>
				</td></tr>
			</table>
			<?php
            $tabs->endPane();
            $tabs->startPane( 'trial' );
			?>
			<table width="100%" class="aecadminform"><tr><td>
				<div class="aec_userinfobox_sub">
					<h4><?php echo JText::_('PAYPLAN_TRIAL_TITLE'); ?></h4>
					<?php echo $aecHTML->createSettingsParticle( 'trial_free' ); ?>
					<?php echo $aecHTML->createSettingsParticle( 'trial_amount' ); ?>
					<?php echo $aecHTML->createSettingsParticle( 'trial_period' ); ?>
					<?php echo $aecHTML->createSettingsParticle( 'trial_periodunit' ); ?>
					<div class="alert-message block-message warning" style="width:200px;">
						<?php echo JText::_('PAYPLAN_AMOUNT_NOTICE_TEXT'); ?>
					</div>
				</div>
			</td></tr></table>
			<?php
            $tabs->endPane();
            $tabs->startPane( 'relations' );
			?>
			<table width="100%" class="aecadminform"><tr><td>
				<div class="aec_userinfobox_sub">
					<h4><?php echo JText::_('PAYPLAN_RELATIONS_TITLE'); ?></h4>
					<?php echo $aecHTML->createSettingsParticle( 'similarplans' ); ?>
					<?php echo $aecHTML->createSettingsParticle( 'equalplans' ); ?>
				</div>
			</td></tr></table>
			<?php
            $tabs->endPane();
            $tabs->startPane( 'mis' );
            ?>
            <table width="100%" class="aecadminform"><tr><td>
				<div class="aec_userinfobox_sub">
					<h4><?php echo JText::_('Inherited Micro Integrations'); ?></h4>
					<?php
					if ( !empty( $aecHTML->customparams->mi['inherited'] ) ) {
						echo '<p>' . JText::_('These MIs were inherited from groups that this subscription plan is in') . '</p>';
						echo '<ul>';
						foreach ( $aecHTML->customparams->mi['inherited'] as $id => $mi ) {
							?>
							<li>
								<p>
									<input type="checkbox" name="inherited_micro_integrations[]" value="<?php echo $mi->id; ?>" checked="checked" disabled="disabled" />
									<strong><?php echo $mi->name; ?></strong> (#<?php echo $mi->id; ?>)
									(<a href="index.php?option=com_acctexp&amp;task=editmicrointegration&amp;id=<?php echo $mi->id; ?>" target="_blank"><?php echo JText::_('edit'); ?></a>)
								</p>
								<p><?php echo $mi->desc; ?></p>
							</li>
							<?php
						}
						echo '</ul>';
					} else {
						echo '<p>' . JText::_('No inherited MIs - A subscription plan can inherit MIs from groups that it is in') . '</p>';
					}
					?>
					<h4><?php echo JText::_('Attached Micro Integrations'); ?></h4>
					<?php
					if ( !empty( $aecHTML->customparams->mi['attached'] ) ) {
						echo '<table style="margin: 0 auto;">';
						foreach ( $aecHTML->customparams->mi['attached'] as $id => $mi ) {
							?>
							<tr>
								<td>
									<h5>
										<strong><?php echo $mi->name; ?></strong>
										(#<?php echo $mi->id; ?>)
										<?php echo $mi->inherited ? ( ' (' . JText::_('inherited from group, see above') . '!)' ) : ''; ?>
										(<a href="index.php?option=com_acctexp&amp;task=editmicrointegration&amp;id=<?php echo $mi->id; ?>" target="_blank"><?php echo JText::_('edit'); ?></a>)
									</h5>
								</td>
								<td>
									<div class="controls">
										<div class="toggleswitch">
											<label class="toggleswitch" onclick="">
												<input id="micro_integrations_<?php echo $mi->id; ?>" type="checkbox" name="micro_integrations[]"<?php echo $mi->attached ? ' checked="checked"' : ''; ?> value="<?php echo $mi->id; ?>"/>
												<span class="toggleswitch-inner">
													<span class="toggleswitch-on"><?php echo JText::_( 'yes' ) ?></span>
													<span class="toggleswitch-off"><?php echo JText::_( 'no' ) ?></span>
													<span class="toggleswitch-handle"></span>
												</span>
											</label>
										</div>
									</div>
								</td>
							</tr>
							<tr>
								<td colspan="2" style="border-bottom: 1px dashed #999;">
									<p><?php echo $mi->desc; ?></p>
								</td>
							</tr>
							<?php
						}
						echo '</table>';
					} else {
						echo '<p>' . JText::_('No MIs to attach') . '<a href="index.php?option=com_acctexp&amp;task=newmicrointegration" target="_blank">(' . JText::_('create one now?') . ')</a></p>';
					}
					?>
				</div>
				<?php if ( !empty( $aecHTML->customparams->hasperplanmi ) ) { ?>
				<div class="aec_userinfobox_sub">
					<?php echo $aecHTML->createSettingsParticle( 'micro_integrations_plan' ); ?>
					<?php echo $aecHTML->createSettingsParticle( 'micro_integrations_hidden' ); ?>
				</div>
				<?php } ?>
				<?php
				if ( !empty( $aecHTML->customparams->mi['custom'] ) ) {
					foreach ( $aecHTML->customparams->mi['custom'] as $id => $mi ) {
						?>
						<div class="aec_userinfobox_sub clear">
							<h2 style="clear:both;"><?php echo $mi['name']; ?></h2>
							<?php
							foreach ( $mi['params'] as $customparam ) {
								echo $aecHTML->createSettingsParticle( $customparam );
							}
							?>
						</div>
						<?php
					}
				}
				?>
			</td></tr></table>
			<?php
            $tabs->endPane();
            $tabs->endPanes();
			?>
		<br />
		<input type="hidden" name="id" value="<?php echo $row->id; ?>" />
		<input type="hidden" name="option" value="<?php echo $option; ?>" />
		<input type="hidden" name="task" value="" />
		</form>

		<?php
 		HTML_myCommon::endCommon();
	}

	function listItemGroups( $rows, $pageNav, $option )
	{
		HTML_myCommon::startCommon(); ?>
		<form action="index.php" method="post" name="adminForm" id="adminForm">
			<?php
			HTML_myCommon::getHeader( 'ITEMGROUPS_TITLE', 'itemgroups' );

			$buttons = array(	'egs' => array( 'groupstart' => true ),
								'edit' => array( 'style' => 'warning', 'text' => JText::_('EDIT_PAYPLAN'), 'actionable' => true, 'icon' => 'pencil' ),
								'copy' => array( 'style' => 'info', 'text' => JText::_('COPY_PAYPLAN'), 'actionable' => true, 'icon' => 'share' ),
								'remove' => array( 'style' => 'danger', 'text' => JText::_('REMOVE_PAYPLAN'), 'actionable' => true, 'icon' => 'trash' ),
								'ege' => array( 'groupend' => true ),
								'hl1' => array(),
								'pgs' => array( 'groupstart' => true ),
								'publish' => array( 'style' => 'info', 'text' => JText::_('PUBLISH_PAYPLAN'), 'actionable' => true, 'icon' => 'eye-open' ),
								'unpublish' => array( 'style' => 'danger', 'text' => JText::_('UNPUBLISH_PAYPLAN'), 'actionable' => true, 'icon' => 'eye-close' ),
								'pge' => array( 'groupend' => true ),
								'hl2' => array(),
								'new' => array( 'style' => 'success', 'text' => JText::_('NEW_PAYPLAN'), 'icon' => 'plus' )
							);
			HTML_myCommon::getButtons( $buttons, 'ItemGroup' );
			?>

			<div class="aecadminform">
			<table class="adminlist">
				<thead><tr>
					<th width="1%">#</th>
					<th width="1%"><?php echo JText::_('AEC_CMN_ID'); ?></th>
					<th width="1%"><input type="checkbox" name="toggle" value="" onClick="checkAll(<?php echo count( $rows ); ?>);" /></th>
					<th width="5%"></th>
					<th width="15%" align="left" nowrap="nowrap"><?php echo JText::_('ITEMGROUP_NAME'); ?></th>
					<th width="20%" align="left" nowrap="nowrap"><?php echo JText::_('ITEMGROUP_DESC'); ?></th>
					<th width="3%" nowrap="nowrap"><?php echo JText::_('ITEMGROUP_ACTIVE'); ?></th>
					<th width="3%" nowrap="nowrap"><?php echo JText::_('ITEMGROUP_VISIBLE'); ?></th>
					<th width="5%" colspan="2" nowrap="nowrap"><?php echo JText::_('ITEMGROUP_REORDER'); ?></th>
				</tr></thead>

		<?php
		$k = 0;
		for( $i=0, $n=count( $rows ); $i < $n; $i++ ) {
				switch( $rows[$i]->visible ) {
					case '1':
						$vaction	= 'invisible';
						$vicon		= 'eye-open';
						break;

					case '0':
						$vaction	= 'visible';
						$vicon		= 'eye-close';
						break;
				}

				switch( $rows[$i]->active ) {
					case '1':
						$aaction	= 'unpublish';
						$aicon		= 'ok-circle';
						break;

					case '0':
						$aaction	= 'publish';
						$aicon		= 'remove-circle';
						break;
				}

				if ( !is_null( $rows[$i]->desc ) ) {
					$description = strip_tags( $rows[$i]->desc );
					if ( strlen( $description ) > 50 ) {
						$description = substr( $description, 0, 50) . ' ...';
					}
				} else {
					$description = '';
				}

				?>
				<tr class="row<?php echo $k; ?>">
					<td align="center"><?php echo $i + 1 + $pageNav->limitstart; ?></td>
					<td align="right"><?php echo $rows[$i]->id; ?></td>
					<td><?php echo JHTML::_('grid.id', $i, $rows[$i]->id, false, 'id' ); ?></td>
					<td align="right" style="background: #<?php echo $rows[$i]->color; ?>;"><?php echo $rows[$i]->group; ?></td>
					<td><a href="#edit" onclick="return listItemTask('cb<?php echo $i; ?>','editItemGroup')" title="<?php echo JText::_('AEC_CMN_CLICK_TO_EDIT'); ?>"><?php echo $rows[$i]->name; ?></a></td>
					<td  align="left">
						<?php
						echo $description; ?>
					</td>
					<td align="center">
						<a href="javascript:void(0);" onClick="return listItemTask('cb<?php echo $i;?>','<?php echo $aaction; ?>ItemGroup')">
							<?php echo aecHTML::Icon( $aicon ); ?>
						</a>
					</td>
					<td align="center">
						<a href="javascript:void(0);" onClick="return listItemTask('cb<?php echo $i;?>','<?php echo $vaction; ?>ItemGroup')">
							<?php echo aecHTML::Icon( $vicon ); ?>
						</a>
					</td>
					<td align="right"><?php echo $pageNav->orderUpIcon( $i, true, 'ordergroupup' ); ?></td>
					<td align="right"><?php echo $pageNav->orderDownIcon( $i, $n, true, 'ordergroupdown' ); ?></td>
				</tr>
			<?php
			$k = 1 - $k;
		} ?>
		<tfoot>
			<tr>
				<td colspan="10">
 					<?php echo $pageNav->getListFooter(); ?>
				</td>
			</tr>
		</tfoot>
		</table>
		</div>
		<input type="hidden" name="option" value="<?php echo $option;?>" />
		<input type="hidden" name="task" value="showItemGroups" />
		<input type="hidden" name="returnTask" value="showItemGroups" />
		<input type="hidden" name="boxchecked" value="0" />
		</form>

		<?php
 		HTML_myCommon::endCommon();
	}

	function editItemGroup( $option, $aecHTML, $row )
	{
		$user = &JFactory::getUser();

		HTML_myCommon::startCommon();

		HTML_myCommon::getHeader( 'AEC_HEAD_ITEMGROUP_INFO', 'itemgroups', $row->id ? $row->name : JText::_('AEC_CMN_NEW') );

		$buttons = array(	'apply' => array( 'style' => 'info', 'text' => JText::_('APPLY'), 'actionable' => true, 'icon' => 'ok-sign' ),
							'save' => array( 'style' => 'success', 'text' => JText::_('SAVE'), 'actionable' => true, 'icon' => 'ok' ),
							'hl1' => array(),
							'cancel' => array( 'style' => 'danger', 'text' => JText::_('CANCEL'), 'icon' => 'remove' )
						);
		HTML_myCommon::getButtons( $buttons, 'ItemGroup' );

		$tabs = new bsPaneTabs;

		$tabs->startTabs();
		$tabs->newTab( 'group', JText::_('ITEMGROUP_DETAIL_TITLE'), true );
		$tabs->newTab( 'restrictions', JText::_('ITEMGROUP_RESTRICTIONS_TITLE') );
		$tabs->newTab( 'mis', JText::_('AEC_USER_MICRO_INTEGRATION') );
		$tabs->endTabs();

		$tabs->startPanes();
		?>
		<form action="index.php" method="post" name="adminForm" id="adminForm" enctype="multipart/form-data" class="form-horizontal">
			<?php
			$tabs->startPanes();
			$tabs->startPane( 'group', true );
			?>
			<table class="aecadminform">
				<tr>
					<td valign="top">
						<div style="position:relative;float:left;width:33.225%;">
							<div class="aec_userinfobox_sub">
								<h4>General</h4>
								<?php echo $aecHTML->createSettingsParticle( 'active' ); ?>
								<?php echo $aecHTML->createSettingsParticle( 'visible' ); ?>
								<?php echo $aecHTML->createSettingsParticle( 'color' ); ?>
								<?php echo $aecHTML->createSettingsParticle( 'icon' ); ?>
								<div style="position:relative;width:100%;">
									<?php
									echo $aecHTML->createSettingsParticle( 'name' );
									if ( $row->id ) { ?>
										<p><a href="<?php echo str_replace("/administrator/", "/", AECToolbox::deadsureURL( 'index.php?option=com_acctexp&task=subscribe&group=' . $row->id ) ); ?>" title="<?php echo JText::_('AEC_CGF_LINK_ABO_FRONTEND'); ?>" target="_blank"><?php echo JText::_('AEC_CGF_LINK_ABO_FRONTEND'); ?></a></p>
										<?php
									} ?>
								</div>
							</div>
						</div>
						<div style="position:relative;float:left;width:33.225%;">
							<div class="aec_userinfobox_sub">
								<h4>Details</h4>
								<?php echo $aecHTML->createSettingsParticle( 'reveal_child_items' ); ?>
								<?php echo $aecHTML->createSettingsParticle( 'symlink' ); ?>
								<?php echo $aecHTML->createSettingsParticle( 'symlink_userid' ); ?>
								<?php echo $aecHTML->createSettingsParticle( 'notauth_redirect' ); ?>
							</div>
						</div>
						<div style="position:relative;float:left;width:33.225%;">
							<div class="aec_userinfobox_sub">
								<h4><?php echo JText::_('ITEMGROUPS_TITLE'); ?></h4>
								<table style="width:100%;">
									<tr>
										<th>ID</td>
										<th>Name</td>
										<th>delete</td>
									</tr>
									<?php
									if ( !empty( $aecHTML->customparams->groups ) ) {
										foreach ( $aecHTML->customparams->groups as $id => $group ) {
											?>
											<tr>
												<td align="right" style="background: #<?php echo $group['color']; ?>;"><?php echo $group['group']; ?></td>
												<td><?php echo $group['name']; ?></td>
												<td><?php echo $aecHTML->createSettingsParticle( 'group_delete_'.$id ); ?></td>
											</tr>
											<?php
										}
									}
									?>
								<?php if ( $row->id > 1 ) { ?>
									<tr>
										<td><?php echo JText::_('NEW_ITEMGROUP'); ?>:</td>
										<td colspan="2"><?php echo $aecHTML->createSettingsParticle( 'add_group' ); ?></td>
									</tr>
								<?php } ?>
								</table>
							</div>
						</div>
						<div style="position:relative;float:left;width:100%;">
							<div class="aec_userinfobox_sub">
								<?php echo $aecHTML->createSettingsParticle( 'desc' ); ?>
							</div>
						</div>
					</td>
				</tr>
			</table>
			<?php
            $tabs->endPane();
            $tabs->startPane( 'restrictions' );
            ?>
			<table class="aecadminform">
				<?php echo aecRestrictionHelper::echoSettings( $aecHTML ); ?>
			<tr><td>
				<div class="aec_userinfobox_sub">
					<h4><?php echo JText::_('AEC_RESTRICTIONS_CUSTOM_HEADER'); ?></h4>
					<?php echo $aecHTML->createSettingsParticle( 'custom_restrictions_enabled' ); ?>
					<?php echo $aecHTML->createSettingsParticle( 'custom_restrictions' ); ?>
					<br />
					<?php echo $aecHTML->createSettingsParticle( 'rewriteInfo' ); ?>
				</div>
			</td></tr>
			</table>
			<?php
            $tabs->endPane();
            $tabs->startPane( 'mis' );
            ?>
            <table width="100%" class="aecadminform"><tr><td>
				<div class="aec_userinfobox_sub">
					<h4><?php echo JText::_('Inherited Micro Integrations'); ?></h4>
					<?php if ( $row->id > 1 ) {
						if ( !empty( $aecHTML->customparams->mi['inherited'] ) ) {
							echo '<p>' . JText::_('These MIs were inherited from groups that this group is in') . '</p>';
							echo '<ul>';
							foreach ( $aecHTML->customparams->mi['inherited'] as $id => $mi ) {
								?>
								<li>
									<p>
										<input type="checkbox" name="inherited_micro_integrations[]" value="<?php echo $mi->id; ?>" checked="checked" disabled="disabled" />
										<strong><?php echo $mi->name; ?></strong> (#<?php echo $mi->id; ?>)
										(<a href="index.php?option=com_acctexp&amp;task=editmicrointegration&amp;id=<?php echo $mi->id; ?>" target="_blank"><?php echo JText::_('edit'); ?></a>)
									</p>
									<p><?php echo $mi->desc; ?></p>
								</li>
								<?php
							}
							echo '</ul>';
						} else {
							echo '<p>' . JText::_('No inherited MIs - A group can inherit MIs from groups that it is in') . '</p>';
						}
					} ?>
					<h4><?php echo JText::_('Attached Micro Integrations'); ?></h4>
					<?php
					if ( !empty( $aecHTML->customparams->mi['attached'] ) ) {
						echo '<table style="margin: 0 auto;">';
						foreach ( $aecHTML->customparams->mi['attached'] as $id => $mi ) {
							?>
							<tr>
								<td>
									<h5>
										<strong><?php echo $mi->name; ?></strong>
										(#<?php echo $mi->id; ?>)
										<?php echo $mi->inherited ? ( ' (' . JText::_('inherited from group, see above') . '!)' ) : ''; ?>
										(<a href="index.php?option=com_acctexp&amp;task=editmicrointegration&amp;id=<?php echo $mi->id; ?>" target="_blank"><?php echo JText::_('edit'); ?></a>)
									</h5>
								</td>
								<td>
									<input type="hidden" name="micro_integrations[]" value="0" />
									<div class="controls">
										<div class="toggleswitch">
											<label class="toggleswitch" onclick="">
												<input id="micro_integrations_<?php echo $mi->id; ?>" type="checkbox" name="micro_integrations[]"<?php echo $mi->attached ? ' checked="checked"' : ''; ?> value="<?php echo $mi->id; ?>"/>
												<span class="toggleswitch-inner">
													<span class="toggleswitch-on"><?php echo JText::_( 'yes' ) ?></span>
													<span class="toggleswitch-off"><?php echo JText::_( 'no' ) ?></span>
													<span class="toggleswitch-handle"></span>
												</span>
											</label>
										</div>
									</div>
								</td>
							</tr>
							<tr>
								<td colspan="2" style="border-bottom: 1px dashed #999;">
									<p><?php echo $mi->desc; ?></p>
								</td>
							</tr>
							<?php
						}
						echo '</table>';
					} else {
						echo '<p>' . JText::_('No MIs to attach') . '<a href="index.php?option=com_acctexp&amp;task=newmicrointegration" target="_blank">(' . JText::_('create one now?') . ')</a></p>';
					}
					?>
				</div>
			</td></tr></table>
			<?php
            $tabs->endPane();
            $tabs->endPanes();
			?>
		<br />
		<input type="hidden" name="id" value="<?php echo $row->id; ?>" />
		<input type="hidden" name="option" value="<?php echo $option; ?>" />
		<input type="hidden" name="task" value="" />
		</form>

		<?php
 		HTML_myCommon::endCommon();
	}

	function listCoupons( $rows, $pageNav, $option, $type )
	{
		HTML_myCommon::startCommon(); ?>
		<form action="index.php" method="post" name="adminForm" id="adminForm">
			<?php HTML_myCommon::getHeader( 'COUPON_TITLE'. ( $type ? '_STATIC' : '' ), 'coupons' . ( $type ? '_static' : '' ) );

			$buttons = array(	'egs' => array( 'groupstart' => true ),
								'edit' => array( 'style' => 'warning', 'text' => JText::_('EDIT_PAYPLAN'), 'actionable' => true, 'icon' => 'pencil' ),
								'copy' => array( 'style' => 'info', 'text' => JText::_('COPY_PAYPLAN'), 'actionable' => true, 'icon' => 'share' ),
								'remove' => array( 'style' => 'danger', 'text' => JText::_('REMOVE_PAYPLAN'), 'actionable' => true, 'icon' => 'trash' ),
								'ege' => array( 'groupend' => true ),
								'hl1' => array(),
								'pgs' => array( 'groupstart' => true ),
								'publish' => array( 'style' => 'info', 'text' => JText::_('PUBLISH_PAYPLAN'), 'actionable' => true, 'icon' => 'eye-open' ),
								'unpublish' => array( 'style' => 'danger', 'text' => JText::_('UNPUBLISH_PAYPLAN'), 'actionable' => true, 'icon' => 'eye-close' ),
								'pge' => array( 'groupend' => true ),
								'hl2' => array(),
								'new' => array( 'style' => 'success', 'text' => JText::_('NEW_PAYPLAN'), 'icon' => 'plus' )
							);
			HTML_myCommon::getButtons( $buttons, 'Coupon' . ($type ? 'Static' : '') );?>

			<div class="aecadminform">
			<table class="adminlist">
				<thead><tr>
					<th width="1%">#</th>
					<th width="1%"><input type="checkbox" name="toggle" value="" onClick="checkAll(<?php echo count( $rows ); ?>);" /></th>
					<th width="15%" align="left" nowrap="nowrap"><?php echo JText::_('COUPON_NAME'); ?></th>
					<th width="20%" align="center" nowrap="nowrap" ><?php echo JText::_('COUPON_CODE'); ?></th>
					<th width="20%" align="left" nowrap="nowrap" ><?php echo JText::_('COUPON_DESC'); ?></th>
					<th width="3%" nowrap="nowrap"><?php echo JText::_('COUPON_ACTIVE'); ?></th>
					<th width="5%" colspan="2" nowrap="nowrap"><?php echo JText::_('COUPON_REORDER'); ?></th>
					<th width="5%" nowrap="nowrap" align="center"><?php echo JText::_('COUPON_USECOUNT'); ?></th>
				</tr></thead>

		<?php
		$k = 0;
		for( $i=0, $n=count( $rows ); $i < $n; $i++ ) {
			?>
				<tr class="row<?php echo $k; ?>">
					<td align="center"><?php echo $i + 1 + $pageNav->limitstart; ?></td>
					<td><?php echo JHTML::_('grid.id', $i, $rows[$i]->id, false, 'id' ); ?></td>
					<td><a href="#edit" onclick="return listItemTask('cb<?php echo $i; ?>','editCoupon<?php echo $type ? "Static" : ""; ?>')" title="<?php echo JText::_('AEC_CMN_CLICK_TO_EDIT'); ?>"><?php echo $rows[$i]->name; ?></a></td>
					<td align="center"><strong><?php echo $rows[$i]->coupon_code; ?></strong></td>
					<td align="left">
						<?php
						echo $rows[$i]->desc ? ( strlen( strip_tags( $rows[$i]->desc ) > 50 ) ? substr( strip_tags( $rows[$i]->desc ), 0, 50) . ' ...' : strip_tags( $rows[$i]->desc ) ) : ''; ?>
					</td>
					<td align="center">
						<a href="javascript:void(0);" onClick="return listItemTask('cb<?php echo $i;?>','<?php echo ( ( $rows[$i]->active ? 'unpublishCoupon' : 'publishCoupon') . ( $type ? 'Static' : '' ) ); ?>')">
							<?php echo aecHTML::Icon( ( $rows[$i]->active ) ? 'ok-circle' : 'remove-circle' ); ?>
						</a>
					</td>
					<td align="right"><?php echo $pageNav->orderUpIcon( $i, true, 'ordercoupon' . ( $type ? 'static' : '' ) . 'up' ); ?></td>
					<td align="right"><?php echo $pageNav->orderDownIcon( $i, $n, true, 'ordercoupon' . ( $type ? 'static' : '' ) . 'down' ); ?></td>
					<td align="center"><strong><?php echo $rows[$i]->usecount; ?></strong></td>
				</tr>
			<?php
			$k = 1 - $k;
		} ?>
		<tfoot>
			<tr>
				<td colspan="9">
 					<?php echo $pageNav->getListFooter(); ?>
				</td>
			</tr>
		</tfoot>
		</table>
		</div>
		<input type="hidden" name="option" value="<?php echo $option;?>" />
		<input type="hidden" name="task" value="showCoupons<?php echo $type ? 'Static' : ''; ?>" />
		<input type="hidden" name="returnTask" value="showCoupons<?php echo $type ? 'Static' : ''; ?>" />
		<input type="hidden" name="boxchecked" value="0" />
		</form>

		<?php
 		HTML_myCommon::endCommon();
	}

	function editCoupon( $option, $aecHTML, $row, $type )
	{
		$user = &JFactory::getUser();

		HTML_myCommon::startCommon();

		JHTML::_('behavior.calendar');

		HTML_myCommon::getHeader( 'AEC_COUPON', 'coupons' . ($type ? '_static' : ''), ($row->id ? $row->name : JText::_('AEC_CMN_NEW')) );
		
		$buttons = array(	'apply' => array( 'style' => 'info', 'text' => JText::_('APPLY'), 'actionable' => true, 'icon' => 'ok-sign' ),
							'save' => array( 'style' => 'success', 'text' => JText::_('SAVE'), 'actionable' => true, 'icon' => 'ok' ),
							'hl1' => array(),
							'cancel' => array( 'style' => 'danger', 'text' => JText::_('CANCEL'), 'icon' => 'remove' )
						);
		HTML_myCommon::getButtons( $buttons, 'Coupon' . ($type ? 'Static' : '') );

		$tabs = new bsPaneTabs;

		$tabs->startTabs();
		$tabs->newTab( 'coupon', JText::_('COUPON_DETAIL_TITLE'), true );
		$tabs->newTab( 'restrictions', JText::_('COUPON_RESTRICTIONS_TITLE') );
		$tabs->newTab( 'mis', JText::_('COUPON_MI') );
		$tabs->endTabs();

		?>
		<form action="index.php" method="post" name="adminForm" id="adminForm" enctype="multipart/form-data" class="form-horizontal">
			<?php
			$tabs->startPanes();
            $tabs->startPane( 'coupon', true );
            ?>
			<table class="aecadminform">
				<tr>
					<td>
						<div style="position:relative;float:left;width:49%;padding:4px;">
								<div class="aec_userinfobox_sub">
									<h4>General</h4>
									<?php echo $aecHTML->createSettingsParticle( 'name' ); ?>
									<?php echo $aecHTML->createSettingsParticle( 'coupon_code' ); ?>
									<?php echo $aecHTML->createSettingsParticle( 'active' ); ?>
									<?php echo $aecHTML->createSettingsParticle( 'type' ); ?>
									<?php echo $aecHTML->createSettingsParticle( 'desc' ); ?>
								</div>
						</div>
						<div style="position:relative;float:left;width:49%;padding:4px;">
								<div class="aec_userinfobox_sub">
									<h4>Terms</h4>
									<?php echo $aecHTML->createSettingsParticle( 'amount_use' ); ?>
									<?php echo $aecHTML->createSettingsParticle( 'amount' ); ?>
									<?php echo $aecHTML->createSettingsParticle( 'amount_percent_use' ); ?>
									<?php echo $aecHTML->createSettingsParticle( 'amount_percent' ); ?>
									<?php echo $aecHTML->createSettingsParticle( 'percent_first' ); ?>
									<?php echo $aecHTML->createSettingsParticle( 'useon_trial' ); ?>
									<?php echo $aecHTML->createSettingsParticle( 'useon_full' ); ?>
									<?php echo $aecHTML->createSettingsParticle( 'useon_full_all' ); ?>
								</div>
						</div>
						<div style="position:relative;float:left;width:100%;padding:4px;">
								<div class="aec_userinfobox_sub">
									<h4>Date &amp; User Restrictions</h4>
									<div class="aec_userinfobox_sub_inline form-stacked" style="width:214px;">
										<?php echo $aecHTML->createSettingsParticle( 'has_start_date' ); ?>
										<?php echo $aecHTML->createSettingsParticle( 'start_date' ); ?>
									</div>
									<div class="aec_userinfobox_sub_inline form-stacked" style="width:214px;">
										<?php echo $aecHTML->createSettingsParticle( 'has_expiration' ); ?>
										<?php echo $aecHTML->createSettingsParticle( 'expiration' ); ?>
									</div>
									<div class="aec_userinfobox_sub_inline form-stacked" style="width:214px;">
										<?php echo $aecHTML->createSettingsParticle( 'has_max_reuse' ); ?>
										<?php echo $aecHTML->createSettingsParticle( 'max_reuse' ); ?>
										<?php echo $aecHTML->createSettingsParticle( 'usecount' ); ?>
									</div>
									<div class="aec_userinfobox_sub_inline form-stacked" style="width:214px;">
										<?php echo $aecHTML->createSettingsParticle( 'has_max_peruser_reuse' ); ?>
										<?php echo $aecHTML->createSettingsParticle( 'max_peruser_reuse' ); ?>
									</div>
									<div class="aec_userinfobox_sub_inline form-stacked" style="width:214px;">
										<?php echo $aecHTML->createSettingsParticle( 'usage_plans_enabled' ); ?>
										<?php echo $aecHTML->createSettingsParticle( 'usage_plans' ); ?>
									</div>
									<div class="aec_userinfobox_sub_inline form-stacked" style="width:214px;">
										<?php echo $aecHTML->createSettingsParticle( 'usage_cart_full' ); ?>
										<?php echo $aecHTML->createSettingsParticle( 'cart_multiple_items' ); ?>
										<?php echo $aecHTML->createSettingsParticle( 'cart_multiple_items_amount' ); ?>
									</div>
								</div>
						</div>
					</td>
				</tr>
			</table>
			<?php
            $tabs->endPane();
            $tabs->startPane( 'restrictions' );
            ?>
			<table class="aecadminform">
				<tr><td>
					<div class="aec_userinfobox_sub">
						<h4>Restrict Combintations</h4>
						<div class="aec_userinfobox_sub_inline form-stacked" style="width:214px;">
							<?php echo $aecHTML->createSettingsParticle( 'depend_on_subscr_id' ); ?>
							<?php echo $aecHTML->createSettingsParticle( 'subscr_id_dependency' ); ?>
							<?php echo $aecHTML->createSettingsParticle( 'allow_trial_depend_subscr' ); ?>
						</div>
						<div class="aec_userinfobox_sub_inline form-stacked" style="width:214px;">
							<?php echo $aecHTML->createSettingsParticle( 'restrict_combination' ); ?>
							<?php echo $aecHTML->createSettingsParticle( 'bad_combinations' ); ?>
						</div>
						<div class="aec_userinfobox_sub_inline form-stacked" style="width:214px;">
							<?php echo $aecHTML->createSettingsParticle( 'allow_combination' ); ?>
							<?php echo $aecHTML->createSettingsParticle( 'good_combinations' ); ?>
						</div>
						<div class="aec_userinfobox_sub_inline form-stacked" style="width:214px;">
							<?php echo $aecHTML->createSettingsParticle( 'restrict_combination_cart' ); ?>
							<?php echo $aecHTML->createSettingsParticle( 'bad_combinations_cart' ); ?>
						</div>
						<div class="aec_userinfobox_sub_inline form-stacked" style="width:214px;">
							<?php echo $aecHTML->createSettingsParticle( 'allow_combination_cart' ); ?>
							<?php echo $aecHTML->createSettingsParticle( 'good_combinations_cart' ); ?>
						</div>
					</div>
				</td></tr>
				<?php echo aecRestrictionHelper::echoSettings( $aecHTML ); ?>
			</table>
			<?php
            $tabs->endPane();
            $tabs->startPane( 'mis' );
            ?>
            <table width="100%" class="aecadminform">
	            <tr><td>
					<div class="aec_userinfobox_sub">
						<h4>Micro Integrations</h4>
						<?php echo $aecHTML->createSettingsParticle( 'micro_integrations' ); ?>
					</div>
				</td></tr>
			</table>
			<?php
            $tabs->endPane();
            $tabs->endPanes();
			?>
		<br />
		<input type="hidden" name="id" value="<?php echo $row->id; ?>" />
		<input type="hidden" name="option" value="<?php echo $option; ?>" />
		<input type="hidden" name="task" value="" />
		</form>

		<?php
 		HTML_myCommon::endCommon();
	}

	function viewinvoices( $option, $rows, $search, $pageNav )
	{
		$user = &JFactory::getUser();

		HTML_myCommon::startCommon();
		?>
		<form action="index.php" method="post" name="adminForm" id="adminForm">
		<?php HTML_myCommon::getHeader( 'INVOICE_TITLE', 'invoices' ); ?>
		<div class="aec-filters">
			<?php echo JText::_('INVOICE_SEARCH'); ?>: <br />
			<input type="text" name="search" value="<?php echo htmlspecialchars($search);?>" class="text_area" onChange="document.adminForm.submit();" />
		</div>

		<div class="aecadminform">
		<table class="adminlist">
		<thead><tr>
			<th width="5%">#</th>
			<th align="left" width="10%"><?php echo JText::_('INVOICE_USERID'); ?></th>
			<th align="center" width="10%"><?php echo JText::_('INVOICE_INVOICE_NUMBER'); ?></th>
			<th align="center" width="10%"><?php echo JText::_('INVOICE_SECONDARY_IDENT'); ?></th>
			<th align="center" width="30%"><?php echo JText::_('INVOICE_CREATED_DATE'); ?></th>
			<th align="center" width="30%"><?php echo JText::_('INVOICE_TRANSACTION_DATE'); ?></th>
			<th align="center" width="10%"><?php echo JText::_('USERPLAN'); ?></th>
			<th align="center" width="10%"><?php echo JText::_('INVOICE_COUPONS'); ?></th>
			<th align="center" width="10%"><?php echo JText::_('INVOICE_METHOD'); ?></th>
			<th align="center" width="10%"><?php echo JText::_('INVOICE_AMOUNT'); ?></th>
			<th width="10%"><?php echo JText::_('INVOICE_CURRENCY'); ?></th>
		  </tr></thead>
		<?php
		$k = 0;
		for( $i=0, $n=count( $rows ); $i < $n; $i++ ) {
			?>
			<tr class="row<?php echo $k; ?>">
				<td><?php echo $i + 1 + $pageNav->limitstart; ?></td>
				<td><a href="index.php?option=com_acctexp&amp;task=edit&userid=<?php echo $rows[$i]->userid; ?>"><?php echo $rows[$i]->username; ?></a></td>
				<td align="center"><a href="<?php echo AECToolbox::deadsureURL( 'administrator/index.php?option=' . $option . '&task=invoiceprint&invoice=' . $rows[$i]->invoice_number ); ?>" target="_blank"><?php echo $rows[$i]->invoice_number_formatted; ?></a></td>
				<td align="center"><?php echo $rows[$i]->secondary_ident; ?></td>
				<td align="center"><?php echo $rows[$i]->created_date; ?></td>
				<td align="center"><?php echo $rows[$i]->transaction_date; ?></td>
	  			<td align="center"><?php echo $rows[$i]->usage; ?></td>
	  			<td align="center"><?php echo $rows[$i]->coupons; ?></td>
	  			<td align="center"><?php echo $rows[$i]->method; ?></td>
				<td align="center"><?php echo $rows[$i]->amount; ?></td>
				<td align="center"><?php echo $rows[$i]->currency; ?></td>
			</tr>
			<?php
			$k = 1 - $k;
		} ?>
		<tfoot>
			<tr>
				<td colspan="11">
 					<?php echo $pageNav->getListFooter(); ?>
				</td>
			</tr>
		</tfoot>
		</table>
		</div>
		<input type="hidden" name="option" value="<?php echo $option;?>" />
		<input type="hidden" name="task" value="invoices" />
		<input type="hidden" name="returnTask" value="invoices" />
		<input type="hidden" name="boxchecked" value="0" />
		</form>

		<?php
 		HTML_myCommon::endCommon();
	}

	function viewhistory( $option, $rows, $search, $pageNav )
	{
		$user = &JFactory::getUser();

		HTML_myCommon::startCommon();
		?>
		<form action="index.php" method="post" name="adminForm" id="adminForm">
		<?php HTML_myCommon::getHeader( 'HISTORY_TITLE2', 'history' ); ?>
		<div class="aec-filters">
			<?php echo JText::_('HISTORY_SEARCH'); ?>: <br />
			<input type="text" name="search" value="<?php echo htmlspecialchars($search);?>" class="text_area" onChange="document.adminForm.submit();" />
		</div>

		<div class="aecadminform">
		<table class="adminlist">
		<thead><tr>
			<th><?php echo JText::_('HISTORY_USERID'); ?></th>
			<th><?php echo JText::_('HISTORY_INVOICE_NUMBER'); ?></th>
			<th><?php echo JText::_('HISTORY_PLAN_NAME'); ?></th>
			<th><?php echo JText::_('HISTORY_TRANSACTION_DATE'); ?></th>
			<th><?php echo JText::_('HISTORY_METHOD'); ?></th>
			<th><?php echo JText::_('HISTORY_AMOUNT'); ?></th>
			<th><?php echo JText::_('HISTORY_RESPONSE'); ?></th>
		  </tr></thead>
		<?php
		$k = 0;
		foreach ( $rows as $row ) { ?>
			<tr class="row<?php echo $k; ?>">
				<td align="center"><?php echo $row->user_name; ?></td>
				<td align="center"><?php echo $row->invoice_number; ?></td>
				<td align="center"><?php echo $row->plan_name; ?></td>
	  			<td align="center"><?php echo $row->transaction_date; ?></td>
				<td align="center"><?php echo $row->proc_name; ?></td>
				<td align="center"><?php echo $row->amount; ?></td>
				<td>
					<?php if ( !empty( $row->response ) && ( strlen( $row->response ) > 8) ) {
							$field = unserialize( base64_decode( $row->response ) );

							echo '<pre class="prettyprint">'.print_r($field,true).'</pre>';
						} ?>
				</td>
			</tr>
			<?php
			$k = 1 - $k;
		} ?>
		<tfoot>
			<tr>
				<td colspan="7">
 					<?php echo $pageNav->getListFooter(); ?>
				</td>
			</tr>
		</tfoot>
		</table>
		</div>
		<input type="hidden" name="option" value="<?php echo $option;?>" />
		<input type="hidden" name="task" value="history" />
		<input type="hidden" name="returnTask" value="history" />
		<input type="hidden" name="boxchecked" value="0" />
		</form>

		<?php
 		HTML_myCommon::endCommon();
	}

	function eventlog( $option, $events, $search, $pageNav )
	{
		HTML_myCommon::startCommon(); ?>
		<form action="index.php" method="post" name="adminForm" id="adminForm">
		<?php HTML_myCommon::getHeader( 'AEC_HEAD_LOG', 'eventlog' ); ?>
		<div class="aec-filters">
			<?php echo JText::_('HISTORY_SEARCH'); ?>: <br />
			<input type="text" name="search" value="<?php echo htmlspecialchars($search);?>" class="text_area" onChange="document.adminForm.submit();" />
		</div>

		<div class="aecadminform">
		<table class="adminlist">
		<thead><tr>
			<th align="left" width="30"><?php echo JText::_('AEC_CMN_ID'); ?></th>
			<th align="left" width="120"><?php echo JText::_('AEC_CMN_DATE'); ?></th>
			<th align="left">&nbsp;</th>
			<th align="left">&nbsp;</th>
			<th align="left"><?php echo JText::_('AEC_CMN_EVENT'); ?></th>
			<th align="left"><?php echo JText::_('AEC_CMN_TAGS'); ?></th>
			<th align="left"><?php echo JText::_('AEC_CMN_ACTION'); ?></th>
			<th align="left"><?php echo JText::_('AEC_CMN_PARAMETER'); ?></th>
		  </tr></thead>
		<?php
		$k = 0;
		foreach ( $events as $row ) { ?>
			<tr class="row<?php echo $k; ?>">
				<td><?php echo $row->id; ?></td>
				<td align="left" width="120"><?php echo $row->datetime; ?></td>
				<td align="left"><?php echo $row->notify ? aecHTML::Icon( 'star' ) : '&nbsp;'; ?></td>
				<td class="notice_level_<?php echo $row->level; ?>"><?php echo JText::_( "AEC_NOTICE_NUMBER_" . $row->level ); ?>
				<td align="left"><?php echo $row->short; ?></td>
	  			<td align="left"><?php echo $row->tags; ?></td>
				<td align="left" class="aec_bigcell"><?php echo $row->event ?></td>
				<td align="left"><?php echo ( $row->params ? $row->params : JText::_('AEC_CMN_NONE') ); ?></td>
			</tr>
			<?php
			$k = 1 - $k;
		} ?>
		<tfoot>
			<tr>
				<td colspan="8">
 					<?php echo $pageNav->getListFooter(); ?>
				</td>
			</tr>
		</tfoot>
		</table>
		</div>
		<input type="hidden" name="option" value="<?php echo $option;?>" />
		<input type="hidden" name="task" value="eventlog" />
		<input type="hidden" name="returnTask" value="eventlog" />
		<input type="hidden" name="boxchecked" value="0" />
		</form>

		<?php
 		HTML_myCommon::endCommon();
	}

	function stats( $option, $page, $stats )
	{
		HTML_myCommon::startCommon(); ?>
		<link rel="stylesheet" type="text/css" media="all" href="<?php echo JURI::root(); ?>media/com_acctexp/css/admin.stats.css" />
		<form action="index.php" method="post" name="adminForm" id="adminForm">
		<div id="stats">
		<div id="statnav">
			<?php HTML_myCommon::getHeader( 'AEC_HEAD_STATS', 'stats' ); ?>
			<ul>
		<?php
			$menus = array( 'overview' => "Overview",
							'daily' => "Today",
							'compare' => "Compare",
							'users' => "Users",
							'sales' => "Sales Graph",
							'all_time' => "All Time Sales" 
			);

			foreach ( $menus as $menu => $menutext ) {
				echo '<li' . ( ( $page == $menu ) ? ' class="current"' : '' ) . '><a href="index.php?option=com_acctexp&task=stats2&page=' . $menu . '">' . $menutext . '</a></li>';
			}
		?>
			</ul>
		</div>
			<div class="gallery" id="chart">
				<script type="text/javascript" src="<?php echo JURI::root(true) . '/media/' . $option; ?>/js/stats/grouped_sales.js"></script>
					<script type="text/javascript">
						var	amount_format = d3.format(".2f"),
							amount_currency = "€",
							range_start=2007,
							range_end=2012,
							request_url="index.php?option=com_acctexp&task=statrequest",
							max_sale = <?php echo $stats['max_sale']; ?>;
					</script>
		<?php
			switch ( $page ) {
				case 'overview':
					?>
					<div id="overview-day" class="overview-container"><h3><?php echo gmdate('l, jS M Y'); ?></h3></div>
					<div id="overview-week" class="overview-container"><h3><?php $w = gmdate('W'); $d = substr($w, -1, 1); $ds = array("th","st","nd","rd");echo $w . ( $d > 3 ? 'th' : $ds[$d] ); ?> Week</h3></div>
					<div id="overview-month" class="overview-container"><h3><?php echo gmdate('F'); ?></h3></div>
					<div id="overview-year" class="overview-container"><h3><?php echo gmdate('Y'); ?></h3></div>
					<script type="text/javascript">
						charts = new vCharts();
						charts.source("sales");
						charts.canvas(200, 200, 10);
						charts.pushTarget("div#overview-day");
						charts.range(	"<?php echo gmdate('Y-m-d') .' 00:00:00'; ?>",
										"<?php echo gmdate('Y-m-d') . ' 23:59:59'; ?>");
						charts.create("Sunburst", 200);

						charts.pushTarget("div#overview-week");
						charts.range(	"<?php echo gmdate('Y-m-d', (gmdate("N") == 1) ? gmdate("U") : strtotime("last Monday",gmdate("U"))) .' 00:00:00'; ?>",
										"<?php echo gmdate('Y-m-d', ((gmdate("N") == 1) ? gmdate("U") : strtotime("last Monday",gmdate("U")))+86400*7) . ' 23:59:59'; ?>");
						charts.create("Sunburst", 200);
						//charts.create("Stacked", 55);

						charts.pushTarget("div#overview-month");
						charts.range(	"<?php echo gmdate('Y-m-01') .' 00:00:00'; ?>",
										"<?php echo gmdate('Y-m-d') . ' 23:59:59'; ?>");
						charts.create("Sunburst", 200);
						//charts.create("Stacked", 55);

						charts.canvas(1000, 200, 10);
						charts.pushTarget("div#overview-year");
						charts.range(	"<?php echo gmdate('Y-01-01') .' 00:00:00'; ?>",
										"<?php echo gmdate('Y-m-d') . ' 23:59:59'; ?>");
						//charts.create("Sunburst", 200);
						charts.create("Cellular", 14);

						//cellular_years( "div#overview-year", <?php echo gmdate('Y') ?>,<?php echo gmdate('Y')+1 ?> );
					</script>
					<?php
					break;
				case 'daily':
					?>
					<div id="daily-yesterday" class="daily-container"><h3>Yesterday</h3></div>
					<div id="daily-today" class="daily-container"><h3>Today</h3></div>
					<script type="text/javascript">
						//sunburst_sales( "div#daily-yesterday", "<?php echo gmdate('Y-m-d') .' 00:00:00'; ?>", "<?php echo gmdate('Y-m-d') . ' 23:59:59'; ?>" );
						//sunburst_sales( "div#daily-today", "<?php echo gmdate('Y-m-d', gmdate("U")-86400) . ' 00:00:00'; ?>", "<?php echo gmdate('Y-m-d', gmdate("U")-86400) . ' 23:59:59'; ?>" );
					</script>
					<?php
					break;
				case 'compare':
					?>
					<div id="overview-day" class="overview-container"><h3><?php echo gmdate('l, jS M Y', gmdate("U")-86400*7); ?> &rarr; <?php echo gmdate('l, jS M Y'); ?></h3></div>
					<div id="overview-week" class="overview-container"><h3><?php $w = gmdate('W', gmdate("U")-86400*7); $d = substr($w, -1, 1); echo $w . ( $d > 3 ? 'th' : $ds[$d] ); ?> Week &rarr; <?php $w = gmdate('W'); $d = substr($w, -1, 1); $ds = array("th","st","nd","rd");echo $w . ( $d > 3 ? 'th' : $ds[$d] ); ?> Week</h3></div>
					<div id="overview-month" class="overview-container"><h3><?php echo gmdate('F', strtotime("last month",gmdate("U"))); ?> &rarr; <?php echo gmdate('F'); ?></h3></div>
					<div id="overview-year" class="overview-container"><h3><?php echo gmdate('Y', strtotime("last year",gmdate("U"))); ?> &rarr; <?php echo gmdate('Y'); ?></h3></div>
					<script type="text/javascript">
						charts = new vCharts();
						charts.attach({	source:"sales",
										canvas:[200,200,10],
										target:"div#overview-day",
										range:["<?php echo gmdate('Y-m-d', gmdate("U")-86400*7) .' 00:00:00'; ?>",
										"<?php echo gmdate('Y-m-d', gmdate("U")-86400*7) . ' 23:59:59'; ?>"]
									});

						charts = new vCharts();
						charts.source("sales");
						charts.canvas(200, 200, 10);
						charts.pushTarget("div#overview-day");
						charts.range(	"<?php echo gmdate('Y-m-d', gmdate("U")-86400*7) .' 00:00:00'; ?>",
										"<?php echo gmdate('Y-m-d', gmdate("U")-86400*7) . ' 23:59:59'; ?>");
						charts.create("Sunburst", 200);

						charts.pushTarget("div#overview-day");
						charts.range(	"<?php echo gmdate('Y-m-d') .' 00:00:00'; ?>",
										"<?php echo gmdate('Y-m-d') . ' 23:59:59'; ?>");
						charts.create("Sunburst", 200);

						charts.pushTarget("div#overview-week");
						charts.range(	"<?php echo gmdate('Y-m-d', ((gmdate("N") == 1) ? gmdate("U") : strtotime("last Monday",gmdate("U")))-86400*7) .' 00:00:00'; ?>",
										"<?php echo gmdate('Y-m-d', ((gmdate("N") == 1) ? gmdate("U") : strtotime("last Monday",gmdate("U")))-86400) . ' 23:59:59'; ?>");
						charts.create("Sunburst", 200);
						charts.pushTarget("div#overview-week");
						charts.range(	"<?php echo gmdate('Y-m-d', (gmdate("N") == 1) ? gmdate("U") : strtotime("last Monday",gmdate("U"))) .' 00:00:00'; ?>",
										"<?php echo gmdate('Y-m-d', ((gmdate("N") == 1) ? gmdate("U") : strtotime("last Monday",gmdate("U")))+86400*7) . ' 23:59:59'; ?>");
						charts.create("Sunburst", 200);

						charts.pushTarget("div#overview-month");
						charts.range(	"<?php echo gmdate('Y-m-01', strtotime("-1 month",gmdate("U")) ) .' 00:00:00'; ?>",
										"<?php echo gmdate('Y-m-d', strtotime(gmdate('Y-m-01', gmdate("U")))-86400) . ' 23:59:59'; ?>");
						charts.create("Sunburst", 200);
						charts.pushTarget("div#overview-month");
						charts.range(	"<?php echo gmdate('Y-m-01') .' 00:00:00'; ?>",
										"<?php echo gmdate('Y-m-d') . ' 23:59:59'; ?>");
						charts.create("Sunburst", 200);

						charts.pushTarget("div#overview-year");
						charts.range(	"<?php echo gmdate('Y-01-01', strtotime(gmdate('Y-01-01', gmdate("U")))-56400) .' 00:00:00'; ?>",
										"<?php echo gmdate('Y-m-d', strtotime(gmdate('Y-01-01', gmdate("U")))-56400) . ' 23:59:59'; ?>");
						charts.create("Sunburst", 200);
						charts.pushTarget("div#overview-year");
						charts.range(	"<?php echo gmdate('Y-01-01') .' 00:00:00'; ?>",
										"<?php echo gmdate('Y-m-d') . ' 23:59:59'; ?>");
						charts.create("Sunburst", 200);
					</script>
					<?php
					break;
				case 'users':
					break;
				case 'plans':
					break;
				case 'all_time':
					?>
					<div id="all-time-cells" class"all-time-container"><h3>Daily Cells</h3></div>
					<script type="text/javascript">
						charts = new vCharts();
						charts.source("sales");
						charts.range(	"<?php echo gmdate('1960-01-01') .' 00:00:00'; ?>",
										"<?php echo gmdate('Y-m-d') . ' 23:59:59'; ?>");
						charts.canvas(400, 400, 20);
						charts.pushTarget("div#chart");
						charts.create("Sunburst", 200);
						charts.range("<?php echo gmdate('2011-1-1') .' 00:00:00'; ?>", "<?php echo gmdate('Y-m-d') . ' 23:59:59'; ?>");
						charts.canvas(800, 900, 10);
						charts.pushTarget("div#chart");
						charts.create("Cellular", 14);

						//cellular_years( "div#chart", 2010, 2010 );
					</script>
					<div id="all-time-suns" class"all-time-container"><h3>Yearly Totals</h3></div>
					<?php
					break;
			}
		?>
		</div>
		</div>

		<?php
 		HTML_myCommon::endCommon();
	}

	function stats2( $option, $stats )
	{
		HTML_myCommon::startCommon(); ?>
		<form action="index.php" method="post" name="adminForm" id="adminForm">
		<table class="adminheading">
		<tr>
			<th width="100%" class="aec_backend_page_heading" style="background: url(<?php echo JURI::root(); ?>media/com_acctexp/images/admin/icons/aec_symbol_stats.png) no-repeat left;" rowspan="2" nowrap="nowrap">
				<?php echo JText::_('AEC_HEAD_STATS'); ?>
			</th>
		</tr>
		</table>

		<h1>Stats aren't done yet, please check back here in the stable release</h1>

		<?php
 		HTML_myCommon::endCommon();
	}

	function readoutSetup( $option, $aecHTML )
	{
		HTML_myCommon::startCommon();
		
		HTML_myCommon::getHeader( 'AEC_READOUT', 'export' );
		?>
		<form action="index.php" method="post" name="adminForm" id="adminForm">
            <table width="100%" class="aecadminform">
				<tr>
					<td valign="top">
						<div class="userinfobox">
						<div class="aec_readout aec_userinfobox_sub">
							<table style="width:320px;">
								<tr>
									<td valign="top">
										<?php foreach ( $aecHTML->rows as $rowname => $rowcontent ) {
											echo $aecHTML->createSettingsParticle( $rowname );
										} ?>
									</td>
								</tr>
							</table>
							<input type="hidden" name="option" value="<?php echo $option;?>" />
							<input type="hidden" name="task" value="readout" />
							<input type="hidden" name="returnTask" value="readout" />
							<input type="hidden" name="display" value="1" />
							<br />
							<input type="submit" class="btn btn-primary" />
							<br />
						</div>
						</div>
					</td>
				</tr>
			</table>
		</form>

		<?php
		echo $aecHTML->loadJS();

 		HTML_myCommon::endCommon();
	}

	function readout( $option, $readout )
	{
		HTML_myCommon::addReadoutCSS();
		HTML_myCommon::startCommon( 'aec_wrap_over' );

		if ( isset( $_POST['column_headers'] ) ) {
			$ch = $_POST['column_headers'];
		} else {
			$ch = 20;
		}

		?>

		<table class="aec_bg aecadminform"><tr><td>
			<?php foreach ( $readout as $part ) { ?>
				<?php
				echo '<div class="aec_userinfobox_sub">';

				if ( !empty( $part['head'] ) ) {
					if ( !empty( $part['sub'] ) ) {
						echo "<h2>" . $part['head'] . "</h2>";
					} else {
						echo "<h1>" . $part['head'] . "</h1>";
					}
				}

				if ( !empty( $part['type'] ) ) {
				switch ( $part['type'] ) {
					case 'table':
						echo "<table class=\"aec_readout_bit\">";

						$i = 0; $j = 0;
						foreach ( $part['set'] as $entry ) {
							if ( $j%$ch == 0 ) {
								echo "<tr>";
								$k = 0;
								foreach ( $part['def'] as $def => $dc ) {
									if ( is_array( $dc[0] ) ) {
										$dn = $dc[0][0].'_'.$dc[0][1];
									} else {
										$dn = $dc[0];
									}

									echo "<th class=\"col".$k." ".$dn."\">" . $def . "</th>";
									$k = $k ? 0 : 1;
								}
								echo "</tr>";
							}

							echo "<tr class=\"row".$i."\">";

							foreach ( $part['def'] as $def => $dc ) {
								if ( is_array( $dc[0] ) ) {
									$dn = $dc[0][0].'_'.$dc[0][1];
								} else {
									$dn = $dc[0];
								}

								$tdclass = $dn;

								if ( isset( $entry[$dn] ) ) {
									$dcc = $entry[$dn];
								} else {
									$dcc = "";
								}

								if ( isset( $dc[1] ) ) {
									$types = explode( ' ', $dc[1] );

									foreach ( $types as $tt ) {
										switch ( $tt ) {
											case 'bool';
												$dcc = $dcc ? 'Yes' : 'No';
												$tdclass .= " bool".$dcc;
												break;
										}
									}
								} else {
									if ( is_array( $dcc ) ) {
										$dcc = implode( ', ', $dcc );
									}
								}

								echo "<td class=\"".$tdclass."\">" . $dcc . "</td>";
							}

							echo "</tr>";

							$i = $i ? 0 : 1;
							$j++;
						}

						echo "</table>";
						break;
				}
				} ?>
				</div>
			<?php } ?>
		</td></tr></table>
		<?php

 		HTML_myCommon::endCommon();
	}

	function readoutCSV( $option, $readout )
	{
		// Send download header
		header("Pragma: public");
		header("Expires: 0");
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");

		header("Content-Type: application/force-download");
		header("Content-Type: application/octet-stream");

		header("Content-Type: application/download");
		header('Content-Disposition: inline; filename="aec_readout.csv"');

		// Load Exporting Class
		$filename = JPATH_SITE . '/components/com_acctexp/lib/export/csv.php';
		$classname = 'AECexport_csv';

		include_once( $filename );

		$exphandler = new $classname();


		if ( isset( $_POST['column_headers'] ) ) {
			$ch = $_POST['column_headers'];
		} else {
			$ch = 20;
		}

		foreach ( $readout as $part ) {
			if ( !empty( $part['head'] ) ) {
				echo $exphandler->export_line( array( $part['head'] ) );
			}

			switch ( $part['type'] ) {
				case 'table':

					$i = 0; $j = 0;
					foreach ( $part['set'] as $entry ) {
						if ( $j%$ch == 0 ) {
							$array = array();
							foreach ( $part['def'] as $k => $v ) {
								$array[] = $k;
							}
							echo $exphandler->export_line( $array );
						}

						$array = array();
						foreach ( $part['def'] as $def => $dc ) {
							if ( is_array( $dc[0] ) ) {
								$dn = $dc[0][0].'_'.$dc[0][1];
							} else {
								$dn = $dc[0];
							}

							$dcc = $entry[$dn];

							if ( isset( $dc[1] ) ) {
								$types = explode( ' ', $dc[1] );

								foreach ( $types as $tt ) {
									switch ( $tt ) {
										case 'bool';
											$dcc = $dcc ? 'Yes' : 'No';
											break;
									}
								}
							}

							if ( is_array( $dcc ) ) {
								$dcc = implode( ', ', $dcc );
							}

							$array[] = $dcc;
						}

						echo $exphandler->export_line( $array );

						$j++;
					}

					echo "\n\n";
					break;
			}
		}
 		exit;
	}

	function import( $option, $aecHTML )
	{
		HTML_myCommon::startCommon();
		?>
		<form action="index.php" enctype="multipart/form-data" method="post" name="adminForm" id="adminForm" class="form-horizontal">
		<?php
		HTML_myCommon::getHeader( 'AEC_HEAD_IMPORT', 'import' );

		$buttons = array(	'cancel' => array( 'style' => 'danger', 'text' => JText::_('CANCEL'), 'icon' => 'remove' ),
							'' => array( 'style' => 'success', 'text' => JText::_('Import'), 'actionable' => true, 'icon' => 'ok' )
							);
		HTML_myCommon::getButtons( $buttons, 'Import' );
		?>
		<table class="aecadminform">
			<tr>
				<td valign="top">
					<div class="userinfobox">
						<div class="aec_import<?php echo $aecHTML->form ? '' : '_large'; ?> aec_userinfobox_sub form-stacked">
									<?php
									if ( $aecHTML->done ) {
										echo '<p>Import ran through successfully.</p>';
										if ( !empty( $aecHTML->errors ) ) {
											echo '<p>However, the import failed on ' . $aecHTML->errors . ' entries. This might mean it wasn\'t successful at all.</p>';
										}
									} elseif ( $aecHTML->form ) {
										foreach ( $aecHTML->rows as $rowname => $rowcontent ) {
											echo $aecHTML->createSettingsParticle( $rowname );
										}
									} else {
										echo $aecHTML->createSettingsParticle( 'file_select' );
			
										echo '<p>Please let us know what the columns in your .csv file mean:</p><p></p>';
			
										echo '<table id="aec_import_table">';
			
										echo '<tr>';
										foreach ( $aecHTML->columns as $column ) {
											echo '<th>' . $aecHTML->createSettingsParticle( $column ) . '</th>';
										}
										echo '</tr>';
			
										foreach ( $aecHTML->user_rows as $row ) {
											echo '<tr>';
			
											foreach ( $row as $k => $v ) {
												echo '<td>' . $v . '</td>';
											}
			
											echo '</tr>';
										}
			
										echo '</table>';
										echo '<p>Showing an selection of the rows in your .csv file. The total number of rows is ' . $aecHTML->user_rows_count . '</p><p></p>';
			
										echo $aecHTML->createSettingsParticle( 'assign_plan' );
									}
									?>
						</div>
					</div>
				</td>
			</tr>
		</table>

		<input type="hidden" name="option" value="<?php echo $option;?>" />
		<input type="hidden" name="task" value="import" />
		<input type="hidden" name="returnTask" value="import" />
		</form>

		<?php
		echo $aecHTML->loadJS();

 		HTML_myCommon::endCommon();
	}

	function export( $option, $task, $aecHTML )
	{
		HTML_myCommon::startCommon();
		?>
		<form action="index.php" method="post" name="adminForm" id="adminForm" class="form-horizontal">
		<?php
		HTML_myCommon::getHeader( 'AEC_HEAD_EXPORT', 'export' );

		$buttons = array( 'cancel' => array( 'style' => 'danger', 'text' => JText::_('CANCEL'), 'icon' => 'remove' ) );

		HTML_myCommon::getButtons( $buttons, 'Settings' );
		?>

		<table class="aecadminform">
			<tr>
				<td valign="top">
					<?php foreach ( $aecHTML->rows as $rowname => $rowcontent ) {
						echo $aecHTML->createSettingsParticle( $rowname );
					} ?>
				</td>
			</tr>
		</table>

		<input type="hidden" name="option" value="<?php echo $option;?>" />
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="returnTask" value="<?php echo $task;?>" />
		</form>

		<?php
		echo $aecHTML->loadJS();

 		HTML_myCommon::endCommon();
	}

	function toolBox( $option, $cmd, $result, $title=null )
	{
		JHTML::_('behavior.calendar');
		HTML_myCommon::startCommon();
		if ( empty( $cmd ) ) {
			HTML_myCommon::getHeader( 'AEC_HEAD_TOOLBOX', 'toolbox', $title );
		} else {
			HTML_myCommon::getHeader( 'AEC_HEAD_TOOLBOX', 'toolbox' );
		} ?>
		<form action="index.php" enctype="multipart/form-data" method="post" name="adminForm" id="adminForm" class="form-horizontal">
            <table width="100%" class="aecadminform">
				<tr>
					<td valign="top">
						<div class="aec_userinfobox_sub">
						<?php if ( is_array( $result ) ) { ?>
							<div id="aec-toolbox-list">
							<?php foreach ( $result as $x => $litem ) {
								echo '<a href="' . $litem['link'] . '"><h3>' . $litem['name'] . '</h3></a><p>' . $litem['desc'] . '</p>';
								echo '<p><a href="' . $litem['link'] . '" class="btn btn-success pull-right" style="margin-top: -48px; margin-right: 48px;"><i class="bsicon-cog bsicon-white"></i> Use</a></p>';
								echo '<hr />';
							} ?>
							</div>
						<?php } else { ?>
							<?php echo $result; ?>
						<?php } ?>
						</div>
					</td>
				</tr>
			</table>
		<input type="hidden" name="option" value="<?php echo $option;?>" />
		<input type="hidden" name="task" value="toolbox" />
		<input type="hidden" name="cmd" value="<?php echo $cmd;?>" />
		</form>

		<?php

 		HTML_myCommon::endCommon();
	}

	function editCSS( &$content, $option )
	{
		$file = JPATH_SITE . '/media/' . $option . '/css/site.css';
		HTML_myCommon::startCommon();

		HTML_myCommon::getHeader( 'AEC_HEAD_CSS_EDITOR', 'css' );

		$buttons = array(	'save' => array( 'style' => 'success', 'text' => JText::_('SAVE'), 'icon' => 'ok' ),
							'hl1' => array(),
							'cancel' => array( 'style' => 'danger', 'text' => JText::_('CANCEL'), 'icon' => 'remove' )
						);
		HTML_myCommon::getButtons( $buttons, 'CSS' );
		?>
		<form action="index.php" method="post" name="adminForm" id="adminForm" class="form-horizontal">
			<div class="aec-filters">
			<table cellpadding="1" cellspacing="1" border="0" width="100%">
				<tr>
					<td>
						<span class="componentheading"><?php echo $file; ?>:&nbsp;
							<?php echo is_writable( $file ) ? '<span style="color:green;">' . JText::_('AEC_CMN_WRITEABLE') . '</span>' : '<span style="color:red;">' . JText::_('AEC_CMN_UNWRITEABLE') . '</span>'; ?>
						</span>
					</td>
					<?php
					jimport('joomla.filesystem.path');

					$chmod = JPath::canChmod( $file );

					if ( $chmod ) {
						if ( is_writable( $file ) ) { ?>
							<td>
								<input type="checkbox" id="disable_write" name="disable_write" value="1" />
								<label for="disable_write"><?php echo JText::_('AEC_CMN_UNWRITE_AFTER_SAVE'); ?></label>
							</td>
							<?php
						} else {
							?>
							<td>
								<input type="checkbox" id="enable_write" name="enable_write" value="1" />
								<label for="enable_write"><?php echoJText::_('AEC_CMN_OVERRIDE_WRITE_PROT'); ?></label>
							</td>
							<?php
						}
					} ?>
				</tr>
			</table>
			</div>
			<table class="aecadminform">
				<tr>
					<td>
						<textarea style="width:100%;height:500px" cols="110" rows="25" name="filecontent" class="inputbox"><?php echo $content; ?></textarea>
					</td>
				</tr>
			</table>
			<input type="hidden" name="option" value="<?php echo $option;?>" />
			<input type="hidden" name="task" value="" />
		</form>

		<?php HTML_myCommon::endCommon();
	}

	/**
	 * Formats a given date
	 *
	 * @param string	$SQLDate
	 * @return string	formatted date
	 */
	function DisplayDateInLocalTime( $SQLDate )
	{
		if ( $SQLDate == '' || $SQLDate == '-' || $SQLDate == '0000-00-00 00:00:00')  {
			return JText::_('AEC_CMN_NOT_SET');
		} else {
			return AECToolbox::formatDate( $SQLDate, true );
		}
	}
}

class bsPaneTabs
{
	function __construct( $params = array() )
	{
		static $loaded = false;

		if (!$loaded) {
			self::_loadBehavior($params);
			$loaded = true;
		}
	}

	function startTabs() { echo '<ul class="nav nav-pills">'; }
	function endTabs() { echo '</ul>'; }
	function newTab( $handle, $title, $current=false ) { echo '<li' . ( $current ? ' class="active"' : '' ) . '><a href="#' . $handle . '" data-toggle="pill">' . $title . '</a></li>'; }

	function startPanes() { echo '<div class="tab-content">'; }
	function endPanes() { echo '</div>'; }

	function startPane( $id, $current=false ) { echo '<div id="' . $id . '" class="tab-pane' . ( $current ? ' active' : '' ) . '">'; }
	function endPane() { echo "</div>"; }

	function _loadBehavior($params = array())
	{
		$document =& JFactory::getDocument();
		$document->addScriptDeclaration( 'jQuery(document).ready(function($) {
			jQuery(\'.nav-pills\').tab()
		});' );
	}
}

jimport('joomla.html.pagination');

class bsPagination extends JPagination
{
	function getListFooter()
	{
		$footer = parent::getListFooter();

		$search = array( JText::_('Start'), JText::_('Prev'), JText::_('Next'), JText::_('End') );

		foreach ( $search as $i => $s ) {
			$search[$i] = '>'.$s.'<';
		}

		$replace = array(	'><i class="bsicon-fast-backward bsicon"></i><',
							'><i class="bsicon-backward bsicon"></i><',
							'><i class="bsicon-forward bsicon"></i><',
							'><i class="bsicon-fast-forward bsicon"></i><'
						);

		$footer = str_replace( $search, $replace, $footer );

		return $footer;
	}
}

?>
