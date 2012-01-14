<?php
/**
 * @version $Id: admin.acctexp.html.php
 * @package AEC - Account Control Expiration - Membership Manager
 * @subpackage Main HTML Backend
 * @copyright 2006-2011 Copyright (C) David Deutsch
 * @author David Deutsch <skore@valanx.org> & Team AEC - http://www.valanx.org
 * @license GNU/GPL v.2 http://www.gnu.org/licenses/old-licenses/gpl-2.0.html or, at your option, any later version
 */

// Dont allow direct linking
( defined('_JEXEC') || defined( '_VALID_MOS' ) ) or die( 'Direct Access to this location is not allowed.' );

class HTML_myCommon
{
	function ContentLegend()
	{
		?>
		<table cellspacing="0" cellpadding="4" border="0" align="center">
			<tr align="center">
				<td><?php echo aecHTML::Icon( 'accept.png', JText::_('AEC_CMN_PUBLISHED') ); ?></td>
				<td><?php echo JText::_('AEC_CMN_PUBLISHED'); ?>&nbsp;|</td>
				<td><?php echo aecHTML::Icon( 'cancel.png', JText::_('AEC_CMN_NOT_PUBLISHED') . '/' . JText::_('AEC_CMN_INVISIBLE') ); ?></td>
				<td><?php echo JText::_('AEC_CMN_NOT_PUBLISHED') . '/' . JText::_('AEC_CMN_INVISIBLE'); ?>&nbsp;|</td>
				<td><?php echo aecHTML::Icon( 'eye.png', JText::_('PAYPLAN_VISIBLE') ); ?></td>
				<td><?php echo JText::_('PAYPLAN_VISIBLE'); ?></td>
			</tr>
			<tr>
				<td colspan="6" align="center"><?php echo JText::_('AEC_CMN_CLICK_TO_CHANGE'); ?></td>
			</tr>
		</table>
		<?php
	}

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
					<a href="https://www.valanx.org" target="_blank"><img src="<?php echo JURI::root(); ?>media/com_acctexp/images/admin/icons/valanx_logo_tiny.png" border="0" alt="valanx" /></a>
				</td>
			</tr>
			</table>
		</div>
		<?php

		if ( _EUCA_DEBUGMODE ) {
			global $eucaDebug;

			$eucaDebug->displayDebug();
		}
	}

	function addBackendCSS()
	{
		?>
		<link rel="stylesheet" type="text/css" media="all" href="<?php echo JURI::root(); ?>media/com_acctexp/css/bootstrap.css" />
		<link rel="stylesheet" type="text/css" media="all" href="<?php echo JURI::root(); ?>media/com_acctexp/css/admin.css" />
		<?php
	}

	function addBackendJS()
	{
		$document=& JFactory::getDocument();

		$document->addScript( '/media/com_acctexp/js/jquery/jquery-1.7.1.min.js' );
		$document->addScript( '/media/com_acctexp/js/jquery/jquerync.js' );
		$document->addScript( '/media/com_acctexp/js/bootstrap/bootstrap-dropdown.js' );
		$document->addScriptDeclaration( 'jQuery(document).ready(function($) {
			jQuery(\'#topbar\').dropdown()
		});' );

		//print_r($document);exit;
	}

	function addReadoutCSS()
	{
		?>
		<link rel="stylesheet" type="text/css" media="all" href="<?php echo JURI::root(); ?>media/com_acctexp/css/readout.css" />
		<?php
	}

	function startCommon()
	{
		HTML_myCommon::addBackendCSS();
		HTML_myCommon::addBackendJS();
		JHTML::_('behavior.tooltip');

		echo '<div id="aec_wrap">';
		echo HTML_AcctExp::menuBar();
	}

	function endCommon( $footer=true )
	{
		if ( $footer ) {
			HTML_myCommon::Valanx();
		}

		echo '</div>';
	}

	function getHeader( $page, $image )
	{
		?><div class="adminheading">
			<img src="<?php echo JURI::root(); ?>media/com_acctexp/images/admin/icons/<?php echo $image; ?>.png">
			<h2><?php echo JText::_($page); ?></h2>
		</div>
		<?php
	}
}

class formParticles
{
	function formParticles()
	{

	}

	function createSettingsParticle( $rows, $lists )
	{
		$thisrow_type	= $rows[0];
		$thisrow_name	= $rows[1];
		$thisrow_desc	= $rows[2];
		$thisrow_var	= $rows[3];
		$thisrow_extra	= $rows[4];

		switch( $thisrow_type ) {
			case 'subtitle': ?>
				<tr align="center" valign="middle">
					<th colspan="3" width="20%"><?php echo $thisrow_desc; ?></th>
				</tr>
				<?php
				break;

			case 'inputA': ?>
				<tr align="left" valign="middle" >
					<td width="10%" align="right"><?php echo $thisrow_name; ?></td>
        			<td align="left">
	        			<input name="<?php echo $thisrow_extra; ?>" type="text" size="4" maxlength="5" value="<?php echo $thisrow_var; ?>" />
					</td>
					<td><?php echo $thisrow_desc; ?></td>
				</tr>
				<?php
				break;

			case 'inputB': ?>
				<tr>
					<td width="10%"><?php echo $thisrow_name; ?></td>
					<td width="10%"><input class="inputbox" type="text" name="<?php echo $thisrow_extra; ?>" size="2" maxlength="10" value="<?php echo $thisrow_var; ?>" /></td>
					<td align="left"><?php echo $thisrow_desc; ?></td>
				</tr>
				<?php
				break;

			case 'inputC': ?>
				<tr align="left" valign="middle" >
					<td width="10%" align="right"><?php echo $thisrow_name; ?></td>
					<td width="10%"><input type="text" size="20" name="<?php echo $thisrow_extra; ?>" class="inputbox" value="<?php echo $thisrow_var; ?>" /></td>
					<td><?php echo $thisrow_desc; ?></td>
				</tr>
				<?php
				break;

			case 'inputD': ?>
				<tr align="left" valign="middle" >
					<td width="10%" align="right"><?php echo $thisrow_name; ?></td>
					<td width="10%"><textarea align="left" cols="60" rows="5" name="<?php echo $thisrow_extra; ?>" /><?php echo $thisrow_var; ?></textarea></td>
					<td><?php echo $thisrow_desc; ?></td>
				</tr>
				<?php
				break;

			case 'inputE': ?>
				<tr align="left" valign="middle" >
					<td><?php echo aecHTML::ToolTip( $thisrow_desc, $thisrow_name, null ); ?><?php echo $thisrow_name; ?></td>
					<td colspan="2" align="left"><input type="text" size="70" name="<?php echo $thisrow_extra; ?>" class="inputbox" value="<?php echo $thisrow_var; ?>" /></td>
				</tr>
				<?php
				break;

			case 'editor': ?>
        		<tr align="left" valign="middle" >
					<td width="10%" align="right">
					<?php echo aecHTML::ToolTip( $thisrow_desc, $thisrow_name, null ); ?>
					<?php echo $thisrow_name; ?>
					</td>
					<td width="10%" colspan="2">
					<!-- <textarea name="<?php echo $thisrow_extra; ?>" align="left" cols="60" maxlength="2048" rows="5"><?php echo $thisrow_var; ?></textarea> //-->
					<?php
					$editor = &JFactory::getEditor();

					$return .= $editor->display( $thisrow_extra,  $thisrow_var , '100%', '250', '50', '20' );
					?>
					</td>
				</tr>
				<?php
				break;

			case 'list': ?>
				<tr>
					<td width="10%"><?php echo $thisrow_name; ?></td>
					<td width="10%" align="left"><?php echo $lists[$thisrow_extra]; ?></td>
					<td align="left"><?php echo $thisrow_desc; ?></td>
				</tr>
				<?php
				break;

			case 'list_big': ?>
				<tr>
					<td><?php echo aecHTML::ToolTip( $thisrow_desc, $thisrow_name, null ); ?><?php echo $thisrow_name; ?></td>
					<td colspan="2" align="left"><?php echo $lists[$thisrow_extra]; ?></td>
				</tr>
				<?php
				break;

			case 'fieldset': ?>
				<tr><td colspan="3" >
					<fieldset><legend><?php echo $thisrow_name; ?></legend>
						<table cellpadding="1" cellspacing="1" border="0">
							<tr align="left" valign="middle">
								<td><?php echo $thisrow_desc; ?></td>
							</tr>
						</table>
					</fieldset>
					</td>
				</tr>
				<?php
				break;
		}
	}
}

class General_css
{
	function editCSSSource( &$content, $option )
	{
		$file = JPATH_SITE . '/media/' . $option . '/css/site.css';
		HTML_myCommon::startCommon(); ?>
		<form action="index.php" method="post" name="adminForm">
			<table cellpadding="1" cellspacing="1" border="0" width="100%">
				<tr>
					<td width="180">
						<table class="adminheading">
							<tr><th class="templates" style="color: #586c79;"><?php echo JText::_('AEC_HEAD_CSS_EDITOR'); ?></th></tr>
						</table>
					</td>
					<td width="260">
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
			<table class="aecadminform">
				<tr>
					<th>
						<?php echo $file; ?>
					</th>
				</tr>
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

		$cb = GeneralInfoRequester::detect_component('anyCB');

		$js = GeneralInfoRequester::detect_component('JOMSOCIAL');

		$exp = "";

		if ( $metaUser->hasSubscription ) {
			if ( isset( $metaUser->focusSubscription->expiration ) ) {
				$exp = $metaUser->focusSubscription->expiration;
			}
		}

		?>
		<script type="text/javascript">
			/* <![CDATA[ */
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
			/* ]]> */
		</script>

		<table class="adminheading">
			<tr>
				<th width="100%" class="aec_backend_page_heading" style="background: url(<?php echo JURI::root(); ?>media/com_acctexp/images/admin/icons/aec_symbol_edit.png) no-repeat left;">
					<?php echo JText::_('AEC_HEAD_SUBCRIBER'); ?>:
					&nbsp;
					<small><?php echo !empty( $metaUser->userid ) ? $metaUser->cmsUser->username . ' (' . JText::_('AEC_CMN_ID') . ': ' . $metaUser->userid . ')' : JText::_('AEC_CMN_NEW'); ?></small>
	        	</th>
			</tr>
		</table>
		<?php HTML_myCommon::getHeader( 'AEC_HEAD_SETTINGS', 'aec_symbol_settings' ); ?>

		<form action="index.php" method="post" name="adminForm" id="adminForm">
			<?php
			$tabs = new bsPaneTabs;
			echo $tabs->startPane( 'settings' );

			echo $tabs->startPanel(JText::_('AEC_HEAD_PLAN_INFO'), JText::_('AEC_HEAD_PLAN_INFO'));
			?>
			<table class="aecadminform">
				<tr>
					<td width="50%" style="padding:10px;" valign="top">
						<div class="aec_userinfobox_sub">
							<h4><?php echo JText::_('AEC_USER_USER_INFO'); ?></h4>
							<p>
								<?php echo JText::_('AEC_USER_USERID'); ?>:&nbsp;<strong><?php echo $metaUser->userid; ?></strong>
								&nbsp;|&nbsp;
								<?php echo JText::_('AEC_USER_STATUS'); ?>:&nbsp;
								<strong><?php echo !$metaUser->cmsUser->block ? aecHTML::Icon( 'accept.png', JText::_('AEC_USER_ACTIVE') ) . '&nbsp;' . JText::_('AEC_USER_ACTIVE') . '</strong>' : aecHTML::Icon( 'exclamation.png', JText::_('AEC_USER_BLOCKED') ) . '&nbsp;' . JText::_('AEC_USER_BLOCKED') . '</strong>' . ( ( $metaUser->cmsUser->activation == '' ) ? '' : ' (<a href="' . JURI::root() . $activateuserlink . '" target="_blank">' . JText::_('AEC_USER_ACTIVE_LINK') . '</a>)' ); ?>
							</p>
							<p>
								<?php echo JText::_('AEC_USER_PROFILE'); ?>:
								<a href="<?php echo $edituserlink; ?>"><?php echo aecHTML::Icon( 'user.png', JText::_('AEC_USER_PROFILE_LINK') ); ?>&nbsp;<?php echo JText::_('AEC_USER_PROFILE_LINK'); ?></a>
								<?php echo $cb ? (' | CB Profile: <a href="index.php?option=com_comprofiler&amp;task=edit&amp;cid=' . $metaUser->userid . '">' . aecHTML::Icon( 'user_orange.png', JText::_('AEC_USER_PROFILE_LINK') ) . '&nbsp;' . JText::_('AEC_USER_PROFILE_LINK') . '</a>') : ''; ?>
								<?php echo $js ? (' | JomSocial Profile: <a href="index.php?option=com_community&amp;view=users&amp;layout=edit&amp;id=' . $metaUser->userid . '">' . aecHTML::Icon( 'user_green.png', JText::_('AEC_USER_PROFILE_LINK') ) . '&nbsp;' . JText::_('AEC_USER_PROFILE_LINK') . '</a>') : ''; ?>
							</p>
							<p>
								<?php echo JText::_('AEC_USER_USERNAME'); ?>:&nbsp;
								<strong><?php echo $metaUser->cmsUser->username; ?></strong>
								&nbsp;|&nbsp;
								<?php echo JText::_('AEC_USER_NAME'); ?>:&nbsp;<strong>
								<?php echo $metaUser->cmsUser->name; ?></strong>
							</p>
							<p>
								<?php echo JText::_('AEC_USER_EMAIL'); ?>:&nbsp;<strong><?php echo $metaUser->cmsUser->email; ?></strong>
							 	(<a href="mailto:<?php echo $metaUser->cmsUser->email; ?>"><?php echo aecHTML::Icon( 'email.png', JText::_('AEC_USER_SEND_MAIL') ); ?>&nbsp;<?php echo JText::_('AEC_USER_SEND_MAIL'); ?></a>)
							</p>
							<?php if ( !defined( 'JPATH_MANIFESTS' ) ) { ?>
							<p><?php echo JText::_('AEC_USER_TYPE'); ?>:&nbsp;<strong><?php echo $metaUser->cmsUser->usertype; ?></strong></p>
							<?php } ?>
							<p>
								<?php echo JText::_('AEC_USER_REGISTERED'); ?>:&nbsp;<?php echo aecHTML::Icon( 'date.png', JText::_('AEC_USER_REGISTERED') ); ?>&nbsp;
								<strong><?php echo $metaUser->cmsUser->registerDate; ?></strong>
								&nbsp;|&nbsp;
								<?php echo JText::_('AEC_USER_LAST_VISIT'); ?>:&nbsp;
								<strong><?php echo aecHTML::Icon( 'door_in.png', JText::_('AEC_USER_LAST_VISIT') ); ?>&nbsp;<?php echo $metaUser->cmsUser->lastvisitDate; ?></strong>
							</p>
						</div>
						<div class="aec_userinfobox_sub">
							<h4><?php echo JText::_('AEC_USER_EXPIRATION'); ?></h4>
							<?php
							if ( !empty( $exp ) && $metaUser->focusSubscription->lifetime ) { ?>
								<p>
								<?php echo JText::_('AEC_USER_CURR_EXPIRE_DATE'); ?>:&nbsp;
								<?php echo aecHTML::Icon( 'clock_pause.png', JText::_('AEC_USER_LIFETIME') ); ?>&nbsp;
								<strong><?php echo JText::_('AEC_USER_LIFETIME'); ?></strong></p>
								<p>
									<?php echo JText::_('AEC_USER_LIFETIME'); ?>:&nbsp;
									<input class="checkbox" type="checkbox" name="ck_lifetime" id="ck_lifetime" checked="checked" onclick="swap();" />
								</p>
								<p>
									<?php echo JText::_('AEC_USER_RESET_EXP_DATE'); ?>:&nbsp;
									<?php echo aecHTML::Icon( 'clock_edit.png', JText::_('AEC_USER_RESET_EXP_DATE') ); ?>
									<?php echo JHTML::_('calendar', $exp, 'expiration', 'expiration', '%Y-%m-%d %H:%M:%S', array('class'=>'inputbox', 'size'=>'25',  'maxlength'=>'19', 'disabled'=>"disabled" )); ?>
									<input type="hidden" name="expiration_check" id="expiration_check" value="<?php echo ( !empty( $exp ) ? $exp : date( 'Y-m-d H:i:s' ) ); ?>"/>
								</p>
								<?php
							} else {
								?>
								<p>
									<?php echo JText::_('AEC_USER_CURR_EXPIRE_DATE'); ?>:&nbsp;
									<?php echo aecHTML::Icon( 'clock_red.png', JText::_('AEC_USER_CURR_EXPIRE_DATE') ); ?>&nbsp;
									<strong><?php echo HTML_AcctExp::DisplayDateInLocalTime( $exp ); ?></strong>
								</p>
								<p>
									<?php echo JText::_('AEC_USER_LIFETIME'); ?>:&nbsp;
									<input class="checkbox" type="checkbox" name="ck_lifetime" id="ck_lifetime" onclick="swap();" />
								</p>
								<p>
									<?php echo JText::_('AEC_USER_RESET_EXP_DATE'); ?>:&nbsp;<?php echo aecHTML::Icon( 'clock_edit.png', JText::_('AEC_USER_RESET_EXP_DATE') ); ?>
									<?php echo JHTML::_('calendar', $exp, 'expiration', 'expiration', '%Y-%m-%d %H:%M:%S', array('class'=>'inputbox', 'size'=>'25',  'maxlength'=>'19')); ?>
									<input type="hidden" name="expiration_check" id="expiration_check" value="<?php echo ( !empty( $exp ) ? $exp : date( 'Y-m-d H:i:s' ) ); ?>"/>
								</p>
								<?php
							} ?>
							<p><?php echo JText::_('AEC_USER_RESET_STATUS'); ?>:&nbsp;<?php echo $lists['set_status']; ?></p>
						</div>
						<div class="aec_userinfobox_sub">
							<h4><?php echo JText::_('AEC_USER_SUBSCRIPTION'); ?></h4>
								<?php if ( $metaUser->hasSubscription ) { ?>
								<table>
									<tr>
										<td width="160"><?php echo JText::_('AEC_USER_SUBSCRIPTIONS_ID');?>:</td>
										<td><strong><?php echo $metaUser->focusSubscription->id; ?></strong></td>
									</tr>
									<tr>
										<td width="160"><?php echo JText::_('AEC_USER_STATUS'); ?>:</td>
										<?php
										switch( $metaUser->focusSubscription->status ) {
											case 'Excluded':
												$icon = 'cut_red.png';
												$status	= JText::_('AEC_CMN_EXCLUDED');
												break;
											case 'Trial':
												$icon 	= 'star.png';
												$status	= JText::_('AEC_CMN_TRIAL');
												break;
											case 'Pending':
												$icon 	= 'star.png';
												$status	= JText::_('AEC_CMN_PENDING');
												break;
											case 'Active':
												$icon	= 'tick.png';
												$status	= JText::_('AEC_CMN_ACTIVE');
												break;
											case 'Cancel':
												$icon	= 'exclamation.png';
												$status	= JText::_('AEC_CMN_CANCEL');
												break;
											case 'Hold':
												$icon	= 'exclamation.png';
												$status	= JText::_('AEC_CMN_HOLD');
												break;
											case 'Expired':
												$icon	= 'cancel.png';
												$status	= JText::_('AEC_CMN_EXPIRED');
												break;
											case 'Closed':
												$icon	= 'cancel.png';
												$status	= JText::_('AEC_CMN_CLOSED');
												break;
											default:
												$icon	= 'thumb_down.png';
												$status	= JText::_('AEC_CMN_NOT_SET');
												break;
										} ?>
										<td><strong><?php echo aecHTML::Icon( $icon, $status ); ?>&nbsp;<?php echo $status; ?></strong></td>
									</tr>
									<tr>
										<td width="160"><?php echo JText::_('AEC_USER_PAYMENT_PROC'); ?>:</td>
										<td><strong><?php echo aecHTML::Icon( 'money_dollar.png', JText::_('AEC_USER_PAYMENT_PROC') ); ?>&nbsp;<?php echo $metaUser->focusSubscription->type ? $metaUser->focusSubscription->type : JText::_('AEC_CMN_NOT_SET'); ?></strong></td>
									</tr>
									<tr>
										<td width="160"><?php echo JText::_('AEC_USER_CURR_SUBSCR_PLAN_PRIMARY'); ?>:</td>
										<td><input class="checkbox" type="checkbox" name="ck_primary" id="ck_primary" <?php echo $metaUser->focusSubscription->primary ? 'checked="checked" disabled="disabled" ' : ''; ?>/></td>
									</tr>
									<tr>
										<td width="160"><?php echo JText::_('AEC_USER_CURR_SUBSCR_PLAN'); ?>:</td>
										<td><strong>#<?php echo $metaUser->focusSubscription->plan; ?></strong> - "<?php echo ( $metaUser->focusSubscription->plan ? HTML_AcctExp::SubscriptionName( $metaUser->focusSubscription->plan ) : '<span style="color:#FF0000;">' . JText::_('AEC_CMN_NOT_SET') . '</span>' ); ?>"</td>
									</tr>
									<tr>
										<td><?php echo JText::_('AEC_USER_PREV_SUBSCR_PLAN'); ?>:</td>
										<td><strong>#<?php echo $metaUser->focusSubscription->previous_plan; ?></strong> - "<?php echo ( $metaUser->focusSubscription->previous_plan ? HTML_AcctExp::SubscriptionName( $metaUser->focusSubscription->previous_plan ) : '<span style="color: #FF0000;">' . JText::_('AEC_CMN_NOT_SET') . '</span>' ); ?>"</td>
									</tr>
									<tr>
										<td><?php echo JText::_('AEC_USER_USED_PLANS'); ?>:</td>
										<td>
											<?php
											if ( !empty( $metaUser->meta->plan_history->used_plans ) ) {
												foreach ( $metaUser->meta->plan_history->used_plans as $used => $amount ) { ?>
													<strong>#<?php echo $used; ?></strong> - "<?php echo HTML_AcctExp::SubscriptionName( $used ); ?>" (<?php echo $amount . " " . ( ( $amount > 1 ) ? JText::_('AEC_USER_TIMES') : JText::_('AEC_USER_TIME') ); ?>)
													<?php
												}
											} else {
												echo '<span style="color: #FF0000;">' . JText::_('AEC_USER_NO_PREV_PLANS') . '</span>';
											} ?>
										</td>
									</tr>
								</table>
								<?php } ?>
								<p>
									<span style="vertical-align:top;"><?php echo JText::_('AEC_USER_ASSIGN_TO_PLAN'); ?>:</span>&nbsp;
									<?php echo $lists['assignto_plan']; ?>
								</p>
								<?php if ( $metaUser->hasSubscription && !empty( $metaUser->allSubscriptions ) ) { ?>
									<br />
									<p><strong><?php echo JText::_('AEC_USER_ALL_SUBSCRIPTIONS');?>:</strong></p>
									<table>
										<tr>
											<td>&nbsp;</td>
											<td>&nbsp;</td>
											<td><?php echo JText::_('AEC_USER_SUBSCRIPTIONS_ID');?></td>
											<td><?php echo JText::_('AEC_USER_SUBSCRIPTIONS_STATUS');?></td>
											<td><?php echo JText::_('AEC_USER_SUBSCRIPTIONS_PROCESSOR');?></td>
											<td><?php echo JText::_('AEC_USER_SUBSCRIPTIONS_SINGUP');?></td>
											<td><?php echo JText::_('AEC_USER_SUBSCRIPTIONS_EXPIRATION');?></td>
										</tr>
										<tr>
											<td colspan="7" style="border-top: 2px solid #999999;"></td>
										</tr>
										<?php foreach ( $metaUser->allSubscriptions as $subs ) { ?>
											<tr<?php echo isset( $subs->current_focus ) ? ' style="background-color:#eff;"' : ''; ?>>
												<td><?php echo isset( $subs->current_focus ) ? '&rArr;' : '&nbsp;'; ?></td>
												<td><?php echo $subs->primary ? aecHTML::Icon( 'star.png', JText::_('AEC_USER_SUBSCRIPTIONS_PRIMARY') ) : '&nbsp;'; ?></td>
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
					</td>
					<td width="50%" style="padding:10px; padding-right:20px; vertical-align:top;">
						<div class="aec_userinfobox_sub">
							<h4><?php echo JText::_('AEC_USER_INVOICES'); ?></h4>
							<div style="float: left; text-align: left;">
								<table width="100%">
										<tr>
											<td><?php echo JText::_('HISTORY_COL_INVOICE');?></td>
											<td><?php echo JText::_('HISTORY_COL_AMOUNT');?></td>
											<td><?php echo JText::_('HISTORY_COL_DATE');?></td>
											<td><?php echo JText::_('HISTORY_COL_METHOD');?></td>
											<td><?php echo JText::_('HISTORY_COL_PLAN');?></td>
											<td><?php echo JText::_('HISTORY_COL_ACTION');?></td>
										</tr>
										<tr>
											<td colspan="6" style="border-top: 2px solid #999999;"></td>
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
						</div>
						<div class="aec_userinfobox_sub">
							<h4><?php echo JText::_('AEC_USER_COUPONS'); ?></h4>
							<div style="float: left; text-align: left;">
								<table width="100%">
										<tr>
											<td><?php echo JText::_('HISTORY_COL_COUPON_CODE');?></td>
											<td><?php echo JText::_('HISTORY_COL_INVOICE');?></td>
										</tr>
										<tr>
											<td colspan="6" style="border-top: 2px solid #999999;"></td>
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
											echo '<tr><td colspan="6" style="text-align:center;">&gt;&gt;&nbsp;'
											. JText::_('AEC_USER_NO_COUPONS')
											. '&nbsp;&lt;&lt;</td></tr>' . "\n";
										} ?>
								</table>
							</div>
						</div>
						<div class="aec_userinfobox_sub">
							<h4><?php echo 'Notes'; ?></h4>
							<div style="float: left; text-align: left;">
							<textarea style="width:90%" cols="450" rows="10" name="notes" id="notes" ><?php echo ( !empty( $metaUser->focusSubscription->customparams['notes'] ) ? $metaUser->focusSubscription->customparams['notes'] : "" ); ?></textarea>
							</div>
						</div>
					</td>
				</tr>
			</table>
			<?php
			echo $tabs->endPanel();
			echo $tabs->startPanel(JText::_('AEC_USER_MICRO_INTEGRATION'), JText::_('AEC_USER_MICRO_INTEGRATION'));
			?>
			<div class="aec_userinfobox_sub">
			<?php if ( $metaUser->hasSubscription ) { ?>
				<p><a href="index.php?"><?php echo JText::_('AEC_USER_QUICKFIRE_GO'); ?></a></p>
			<?php } else { ?>
				<p><?php echo JText::_('AEC_USER_QUICKFIRE_UNAVAILABLE'); ?></p>
			<?php } ?>
			</div>

			<div class="aec_userinfobox_sub">
			<?php if ( !empty( $mi['profile'] ) || !empty( $mi['profile_form'] ) ) {
				if ( !empty( $mi['profile'] ) ) { ?>
				<table class="aecadminform">
					<tr>
						<td valign="top" style="padding: 10px;">
							<?php foreach ( $mi['profile'] as $mix ) { ?>
									<div class="profileinfobox">
										<h4><?php echo $mix['name']; ?></h4>
										<p><?php echo $mix['info']; ?></p>
									</div>
								<?php
							} ?>
						</td>
					</tr>
				</table>
				<?php }
				if ( !empty( $mi['profile_form'] ) ) { ?>
				<table class="aecadminform">
					<tr>
						<td valign="top" style="padding: 10px;">
							<?php foreach ( $mi['profile_form'] as $k ) { ?>
									<?php echo $aecHTML->createSettingsParticle( $k ); ?>
								<?php
							} ?>
						</td>
					</tr>
				</table>
				<?php }
			} ?>
			<?php if ( !empty( $mi['admin'] ) || !empty( $mi['admin_form'] ) ) {
				if ( !empty( $mi['admin'] ) ) { ?>
				<table class="aecadminform">
					<tr>
						<td valign="top" style="padding: 10px;">
							<?php foreach ( $mi['admin'] as $mix ) { ?>
									<div class="admininfobox">
										<h3><?php echo $mix['name']; ?></h3>
										<p><?php echo $mix['info']; ?></p>
									</div>
								<?php
							} ?>
						</td>
					</tr>
				</table>
				<?php }
				if ( !empty( $mi['admin_form'] ) ) { ?>
				<table class="aecadminform">
					<tr>
						<td valign="top" style="padding: 10px;">
							<?php foreach ( $mi['admin_form'] as $k ) { ?>
									<?php echo $aecHTML->createSettingsParticle( $k ); ?>
								<?php
							} ?>
						</td>
					</tr>
				</table>
				<?php }
			}

			if ( !empty( $metaUser->meta->params->mi ) ) { ?>
			<table class="aecadminform">
				<tr>
					<td valign="top" style="padding: 10px;">
						<pre><?php print_r( $metaUser->meta->params->mi ); ?></pre>
					</td>
				</tr>
			</table>
			</div>
			<?php
			}
			echo $tabs->endPanel();
			echo $tabs->endPane();
			?>
			<input type="hidden" name="option" value="<?php echo $option; ?>" />
			<input type="hidden" name="id" value="<?php echo !empty( $metaUser->focusSubscription->id ) ? $metaUser->focusSubscription->id : ''; ?>" />
			<input type="hidden" name="userid" value="<?php echo $metaUser->userid; ?>" />
			<input type="hidden" name="task" value="" />
			<input type="hidden" name="nexttask" value="<?php echo $nexttask;?>" />
		</form>

		<?php

		if ( _EUCA_DEBUGMODE ) {
			krumo( $option, $metaUser, $invoices, $lists, $nexttask );
		}

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
				<img border="0" align="middle" alt="<?php echo $text; ?>" src="<?php echo JURI::root() . "media/com_acctexp/images/admin/icons/" . $image; ?>"/>
				<span><?php echo $text; ?></span>
			</a>
		</div>
	<?php
	}

	function quickSearchBar( $display, $searchcontent=null )
	{
		?>
		<div class="central_quicksearch aec_userinfobox_sub">
			<h2><?php echo JText::_('AEC_QUICKSEARCH'); ?></h2>
			<p><?php echo JText::_('AEC_QUICKSEARCH_DESC'); ?></p>
			<form action="<?php echo JURI::base(); ?>index.php?option=com_acctexp&amp;task=quicklookup" method="post">
			<input type="text" size="80" name="search" class="inputbox" value="<?php echo htmlspecialchars($searchcontent); ?>" />
			<input type="submit" class="btn primary"/>
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
				<a href="<?php echo $linkroot.'central' ?>" class="brand">AEC</a>
				<ul class="nav">
				<?php foreach ( $menu as $m ) { ?>
					<li class="dropdown" data-dropdown="dropdown">
						<a class="dropdown-toggle" href="#"><?php echo $m['short'] ?></a>
						<ul class="dropdown-menu">
						<?php
						foreach ( $m['items'] as $item ) {
							echo '<li><a href="' . $linkroot.$item[0] . '">' . $item[2] . '</a></li>';
						}
						?>
						</ul>
					</li>
				<?php } ?>
				<form action="" class="pull-left">
					<input type="text" placeholder="Quicksearch">
				</form>
	          </ul>
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
																	array( 'showMicroIntegrations', 'microintegrations', JText::_('AEC_CENTR_M_INTEGRATION') ),
																	array( 'invoices', 'invoices', JText::_('AEC_CENTR_V_INVOICES') ),
																	array( 'showCoupons', 'coupons', JText::_('AEC_CENTR_COUPONS') ),
																	array( 'showCouponsStatic', 'coupons_static', JText::_('AEC_CENTR_COUPONS_STATIC') )
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
												)
		);
	}

	function central( $display=null, $notices=null, $searchcontent=null )
	{
		global $aecConfig;

		HTML_myCommon::startCommon();
		?>
		<table class="aecadminform">
			<tr>
				<td valign="top">
					<div id="aec_center">
						<?php if ( !empty( $aecConfig->cfg['quicksearch_top'] ) ) {
							HTML_AcctExp::quickSearchBar( $display, $searchcontent );
						} ?>
						<?php
						$linkroot = "index.php?option=com_acctexp&amp;task=";

						$menu = self::getMenu();

						foreach ( $menu as $m ) {
							?><div class="central_buttons aec_userinfobox_sub"><h3><?php echo $m['name']; ?></h3><div class="central_group"><?php

							foreach ( $m['items'] as $item ) {
								HTML_AcctExp::quickiconButton( $linkroot.$item[0], 'aec_symbol_'.$item[1].'.png', $item[2] );
							}
							?></div></div><?php
						}

						if ( empty( $aecConfig->cfg['quicksearch_top'] ) ) {
							HTML_AcctExp::quickSearchBar( $display, $searchcontent );
						}

						if ( !empty( $notices ) ) {
						?>
						<div class="central_notices aec_userinfobox_sub">
							<h2><?php echo JText::_('AEC_NOTICES_FOUND'); ?></h2>
							<p><?php echo JText::_('AEC_NOTICES_FOUND_DESC'); ?></p>
							<p><a href="index.php?option=com_acctexp&amp;task=readAllNotices"><?php echo JText::_('AEC_NOTICE_MARK_ALL_READ'); ?></a></p>
							<?php
							$noticex = array( 2 => 'success', 8 => 'info', 32 => 'warning', 128 => 'error' );
							foreach( $notices as $notice ) {
							?>
								<div class="alert-message <?php echo $noticex[$notice->level]; ?>">
									<a class="close" href="index.php?option=com_acctexp&amp;task=readNotice&amp;id=<?php echo $notice->id; ?>">&times;</a>
									<h5><strong><?php echo JText::_( "AEC_NOTICE_NUMBER_" . $notice->level ); ?>: <?php echo $notice->short; ?></strong></h5>
									<p><?php echo substr( htmlentities( stripslashes( $notice->event ) ), 0, 256 ); ?></p>
									<span class="help-block"><?php echo $notice->datetime; ?></span>
								</div>
							<?php
							}
							?>
						</div>
						<?php
						}
						?>
					</div>
				</td>
				<td width="320" valign="top" class="centerlogo">
					<br />
					<center><img src="<?php echo JURI::root(); ?>media/com_acctexp/images/admin/icons/aec_logo_big.png" border="0" alt="AEC" width="200" height="232" /></center>
					<br />
					<div style="margin-left:auto;margin-right:auto;width:400px;text-align:center;"><p><strong>Account Expiration Control</strong> Component<br />Version <?php echo str_replace( 'omega', '&Omega;', _AEC_VERSION );; ?>, Revision <?php echo _AEC_REVISION ?></p>
						<p><?php echo JText::_('AEC_FOOT_TX_CHOOSING'); ?></p>
						<div style="margin: 0 auto;text-align:center;">
							<a href="http://www.valanx.org"> <img src="<?php echo JURI::root(); ?>media/com_acctexp/images/admin/icons/valanx_logo.png" border="0" alt="valanx" /></a>
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
				<p>David 'skOre' Deutsch</p>
				<h1>Contributing Programmers</h1>
				<p>Calum 'polc1410' Polwart, William 'Jake' Jacobs, Anton 'crashedfx' Skvortsov</p>
				<h1>Past Contributing Programmers</h1>
				<p>Helder 'hlblog' Garcia (started the first versions of AEC), Michael 'mic' Pagler, Steven 'corephp' Pignataro, Ben 'Slinky' Ingram, Charles 'Slydder' Williams, Mati 'mtk' Kochen, Ethan 'ethanchai' Chai Voon Chong</p>
				<h1>Graphics</h1>
				<p>All layout and graphics design as well as images are <a href="http://creativecommons.org/licenses/by-nc-sa/3.0/">CC-BY-NC-SA 3.0</a> 2006-2011 David 'skOre' Deutsch unless otherwise noted.</p>
				<p>Additional icons are the silk icon set by Mark James (<a href="http://www.famfamfam.com/">famfamfam.com</a>).</p>
				<p>Trademarks, Logos and other trade signs are property of their respective owners.</p>
				<h1>Libraries</h1>
				<p>The import function uses a modified parsecsv library by Jim Myhrberg - <a href="http://code.google.com/p/parsecsv-for-php/">code.google.com/p/parsecsv-for-php</a>.</p>
				<p>Furthermore, these libraries are used in one place or another: <a href="http://www.blueprintcss.org/">blueprint</a>, <a href="http://krumo.sourceforge.net/">krumo</a>, <a href="http://www.mootools.net/">mootools</a>, <a href="http://www.jquery.com/">jquery</a>, <a href="http://sourceforge.net/projects/nusoap">nusoap</a>, <a href="http://recaptcha.net/">recaptcha</a>.</p>
				<h1>Eternal Gratitude</h1>
				<p>These are the people without whom I could not have kept up the pace:</p>
				<p>William 'Jake' Jacobs, Aaron Varga, Calum 'polc1410' Polwart</p>
				<h1>Beta-Testers</h1>
				<p>People who have helped to check releases before they went out:</p>
				<p>Calum 'polc1410' Polwart, Aleksey Pikulik</p>
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
					<a href="https://www.valanx.org" target="_blank"><img src="<?php echo JURI::root(); ?>media/com_acctexp/images/admin/icons/valanx_logo.png" border="0" alt="valanx.org" /></a>
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
		<form action="index.php" method="post" name="adminForm">
			<input type="hidden" name="option" value="<?php echo $option;?>" />
			<input type="hidden" name="task" value="" />
			<input type="hidden" name="returnTask" value="" />
			<input type="hidden" name="boxchecked" value="0" />
		</form>
		<?php HTML_myCommon::getHeader( 'AEC_HEAD_HACKS', 'aec_symbol_hacks' ); ?>
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
							echo aecHTML::Icon( $content['status'] ? 'tick.png' : 'stop.png' )
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
							<?php
							if ( !empty( $content['legacy'] ) ) { ?>
								<img src="<?php echo JURI::root();?>media/com_acctexp/images/admin/icons/aec_symbol_importance_3.png" title="<?php echo JText::_('AEC_HACKS_LEGACY'); ?>" alt="<?php echo JText::_('AEC_HACKS_LEGACY'); ?>" />
								<?php
							} ?>
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
		if ( _EUCA_DEBUGMODE ) {
			krumo( $option, $hacks );
		}

 		HTML_myCommon::endCommon();
	}

	function Settings( $option, $aecHTML, $tab_data, $editors )
	{
		jimport( 'joomla.html.editor' );

		HTML_myCommon::startCommon();
		?>
		<script type="text/javascript">
        function submitbutton(pressbutton) {
        	//alert('test');
        	submitform(pressbutton);
            //return 'if (tinyMCE.get(editor).isHidden()) {tinyMCE.get(editor).show()}; tinyMCE.get(editor).save();';';
        }
		</script>
		<form action="index.php" method="post" name="adminForm">
		<?php HTML_myCommon::getHeader( 'AEC_HEAD_SETTINGS', 'aec_symbol_settings' ); ?>
		<?php

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

		if ( _EUCA_DEBUGMODE ) {
			krumo( $option, $aecHTML, $tab_data, $editors );
		}

 		HTML_myCommon::endCommon();
	}

	function listProcessors( $rows, $pageNav, $option )
	{
		HTML_myCommon::startCommon(); ?>
		<form action="index.php" method="post" name="adminForm">
			<?php HTML_myCommon::getHeader( 'PROCESSORS_TITLE', 'aec_symbol_settings' ); ?>
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
 		<?php HTML_myCommon::ContentLegend(); ?>
		<input type="hidden" name="option" value="<?php echo $option; ?>" />
		<input type="hidden" name="task" value="showProcessors" />
		<input type="hidden" name="returnTask" value="showProcessors" />
		<input type="hidden" name="boxchecked" value="0" />
		</form>

		<?php
		if ( _EUCA_DEBUGMODE ) {
			krumo( $rows, $pageNav, $option );
		}

 		HTML_myCommon::endCommon();
	}

	function editProcessor( $option, $aecHTML )
	{
		HTML_myCommon::startCommon();
		?>
		<form action="index.php" method="post" name="adminForm">
		<?php HTML_myCommon::getHeader( 'AEC_HEAD_SETTINGS', 'aec_symbol_settings' ); ?>
		<?php echo JText::_('AEC_HEAD_SETTINGS') . ': ' . ( !empty( $aecHTML->pp->info['longname'] ) ? $aecHTML->pp->info['longname'] : '' ); ?>
		<?php

		$tabs = new bsPaneTabs;
		echo $tabs->startPane( 'settings' );
		if ( !empty( $aecHTML->pp ) ) {
			echo $tabs->startPanel( $aecHTML->pp->processor_name, $aecHTML->pp->info['longname'] );

			echo '<img src="' . JURI::root( true ) . '/media/' . $option . '/images/site/gwlogo_' . $aecHTML->pp->processor_name . '.png" alt="' . $aecHTML->pp->processor_name . '" title="' . $aecHTML->pp->processor_name .'" class="plogo" />';
			echo '</div>';

			echo '<div class="aec_userinfobox_sub">';
			$id = $aecHTML->pp->id;
		} else {
			echo $tabs->startPanel( 'new processor', 'new processor' );

			echo '<div class="aec_userinfobox_sub">';

			$id = 0;
		}

		foreach ( $aecHTML->rows as $rowname => $rowcontent ) {
			echo $aecHTML->createSettingsParticle( $rowname );
		}

		echo '</div>';

		echo $tabs->endPanel();
		?>
		<input type="hidden" name="id" value="<?php echo $id; ?>" />
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="option" value="<?php echo $option; ?>" />
		</form>
		<?php
		// close pane and include footer
		echo $tabs->endPane();

		echo $aecHTML->loadJS();

		if ( _EUCA_DEBUGMODE ) {
			krumo( $option, $aecHTML );
		}

 		HTML_myCommon::endCommon();
	}

	function listSubscriptions( $rows, $pageNav, $search, $option, $lists, $subscriptionid, $action )
	{
		$user = &JFactory::getUser();

		HTML_myCommon::startCommon(); ?>
		<form action="index.php" method="post" name="adminForm">
			<table class="adminheading" cellpadding="2" cellspacing="2">
				<tr>
					<th width="100%" class="aec_backend_page_heading" style="background: url(<?php echo JURI::root(); ?>media/com_acctexp/images/admin/icons/aec_symbol_<?php echo $action[0]; ?>.png) no-repeat left;"><?php echo $action[1]; ?></th>
					<td nowrap="nowrap"><?php echo $lists['groups'];?></td>
					<td style="text-align:center;">
						<?php if ( $action[0] != 'manual' ) { ?>
							<?php echo JText::_('PLAN_FILTER') . '<br />' . $lists['filterplanid'] ?>
						<?php } ?>
						<?php echo JText::_('ORDER_BY') . '<br />' . $lists['orderNav']; ?>
						<input type="button" class="button" onclick="document.adminForm.submit();" value="<?php echo JText::_('AEC_CMN_APPLY'); ?>" style="margin:2px;text-align:center;" />
					</td>
					<td style="white-space:nowrap; float:right; text-align:left; padding:3px; margin:3px;">
						<?php echo $lists['planid']; ?>
						<br />
						<?php if ( $action[0] != 'manual' ) { ?>
						<?php echo $lists['set_expiration']; ?>
						<br />
						<?php } ?>
						<?php echo JText::_('AEC_CMN_SEARCH'); ?>
						<br />
						<input type="text" name="search" value="<?php echo htmlspecialchars($search); ?>" class="inputbox" onChange="document.adminForm.submit();" />
					</td>
				</tr>
				<tr><td></td></tr>
			</table>
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
							<td width="20"><?php echo !empty( $row->primary ) ? aecHTML::Icon( 'star.png', JText::_('AEC_USER_SUBSCRIPTIONS_PRIMARY') ) : '&nbsp;'; ?></td>
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
			<input type="hidden" name="option" value="<?php echo $option;?>" />
			<input type="hidden" name="task" value="showActive" />
			<input type="hidden" name="returnTask" value="showActive" />
			<input type="hidden" name="boxchecked" value="0" />
		</form>

 		<?php
		if ( _EUCA_DEBUGMODE ) {
			krumo( $user, $rows, $pageNav, $search, $option, $lists, $subscriptionid, $action );
		}

 		HTML_myCommon::endCommon();
	}

	function listMicroIntegrations( $rows, $pageNav, $option, $lists, $search, $ordering )
	{
		HTML_myCommon::startCommon(); ?>
		<form action="index.php" method="post" name="adminForm">
			<table class="adminheading">
				<tr>
					<th width="100%" class="aec_backend_page_heading" style="background: url(<?php echo JURI::root(); ?>media/com_acctexp/images/admin/icons/aec_symbol_microintegrations.png) no-repeat left;"><?php echo JText::_('MI_TITLE'); ?></th>
					<td style="text-align:center;">
						<?php echo JText::_('PLAN_FILTER'); ?>
						&nbsp;
						<?php echo $lists['filterplanid'] . JText::_('ORDER_BY') . $lists['orderNav']; ?>
						<input type="button" class="button" onclick="document.adminForm.submit();" value="<?php echo JText::_('AEC_CMN_APPLY'); ?>" style="margin:2px;text-align:center;" />
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
 		<?php HTML_myCommon::ContentLegend(); ?>
		<input type="hidden" name="option" value="<?php echo $option; ?>" />
		<input type="hidden" name="task" value="showMicroIntegrations" />
		<input type="hidden" name="returnTask" value="showMicroIntegrations" />
		<input type="hidden" name="boxchecked" value="0" />
		</form>

		<?php
		if ( _EUCA_DEBUGMODE ) {
			krumo( $rows, $pageNav, $option );
		}

 		HTML_myCommon::endCommon();
	}

	function editMicroIntegration( $option, $row, $lists, $aecHTML )
	{
		//$Returnid = intval( aecGetParam( $_REQUEST, 'Returnid', 0 ) );

		$tabs = new bsPaneTabs;
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
		<table class="adminheading">
			<tr>
				<th width="100%" class="aec_backend_page_heading" style="background: url(<?php echo JURI::root(); ?>media/com_acctexp/images/admin/icons/aec_symbol_microintegrations.png) no-repeat left;">
					<?php echo JText::_('AEC_HEAD_MICRO_INTEGRATION'); ?>:&nbsp;
					<small><?php echo $row->id ? $row->name : JText::_('AEC_CMN_NEW'); ?></small>
	        	</th>
			</tr>
		</table>
		<?php HTML_myCommon::getHeader( 'AEC_HEAD_SETTINGS', 'aec_symbol_settings' ); ?>
		<form action="index.php" method="post" name="adminForm" enctype="multipart/form-data">
			<table cellspacing="0" cellpadding="0" width="100%">
				<tr>
					<td valign="top">
						<?php
		                echo $tabs->startPane( 'createMicroIntegration' );
		                echo $tabs->startPanel( JText::_('MI_E_TITLE'), JText::_('MI_E_TITLE') );
		                ?>
		                <table width="100%" class="aecadminform">
							<tr>
							<td valign="top">
								<h2><?php echo JText::_('MI_E_TITLE_LONG'); ?></h2>
								<div class="aec_userinfobox_sub">
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
								<h2><?php echo JText::_('MI_E_DETAILS'); ?></h2>
								<div class="aec_userinfobox_sub">
									<h4><?php echo JText::_('MI_E_FUNCTION_NAME'); ?></h4>
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
							<?php if ( $aecHTML->hasSettings ) {
			                echo $tabs->endPanel();
			                echo $tabs->startPanel( JText::_('MI_E_SETTINGS'), JText::_('MI_E_SETTINGS') ); ?>
				                <table width="100%" class="aecadminform">
				                	<div class="aec_userinfobox_sub">
									<?php
									foreach ( $aecHTML->customparams as $name ) {
										if ( strpos( $name, 'aectab_' ) === 0 ) {
											?></table><?php
											echo $tabs->endPanel();
											echo $tabs->startPanel( $aecHTML->rows[$name][1], $aecHTML->rows[$name][1] ); ?>
							                <table width="100%" class="aecadminform">
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
								</table>
								<?php
							}
			                echo $tabs->endPanel();
			                echo $tabs->endPane(); ?>
						</td>
					</tr>
				</table>
			<input type="hidden" name="id" value="<?php echo $row->id; ?>" />
			<input type="hidden" name="option" value="<?php echo $option; ?>" />
			<input type="hidden" name="task" value="" />
		</form>

		<?php

		echo $aecHTML->loadJS();

		if ( _EUCA_DEBUGMODE ) {
			krumo( $option, $row, $lists, $aecHTML );
		}

 		HTML_myCommon::endCommon();
	}

	function listSubscriptionPlans( $rows, $lists, $pageNav, $option )
	{
		HTML_myCommon::startCommon(); ?>
		<form action="index.php" method="post" name="adminForm">
			<?php HTML_myCommon::getHeader( 'PAYPLANS_TITLE', 'aec_symbol_plans' ); ?>
			<?php echo $lists['filter_group'];?>
			<input type="button" class="button" onclick="document.adminForm.submit();" value="<?php echo JText::_('AEC_CMN_APPLY'); ?>" style="margin:2px;text-align:center;" />

			<table class="adminlist">
				<thead><tr>
					<th width="1%">#</th>
					<th width="1%"><?php echo JText::_('AEC_CMN_ID'); ?></th>
					<th width="1%"><input type="checkbox" name="toggle" value="" onClick="checkAll(<?php echo count( $rows ); ?>);" /></th>
					<th width="2%" align="left" nowrap="nowrap"><?php echo JText::_('PAYPLAN_GROUP'); ?></th>
					<th width="15%" align="left" nowrap="nowrap"><?php echo JText::_('PAYPLAN_NAME'); ?></th>
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
						$vaction	= 'invisibleSubscriptionPlan';
						$vicon		= 'eye.png';
						$vtext		= JText::_('PAYPLAN_VISIBLE');
						break;

					case '0':
						$vaction	= 'visibleSubscriptionPlan';
						$vicon		= 'cancel.png';
						$vtext		= JText::_('AEC_CMN_INVISIBLE');
						break;
				}

				switch( $rows[$i]->active ) {
					case '1':
						$aaction	= 'unpublishSubscriptionPlan';
						$aicon		= 'accept.png';
						$atext		= JText::_('AEC_CMN_PUBLISHED');
						break;

					case '0':
						$aaction	= 'publishSubscriptionPlan';
						$aicon		= 'cancel.png';
						$atext		= JText::_('AEC_CMN_NOT_PUBLISHED');
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
					<td><a href="#edit" onclick="return listItemTask('cb<?php echo $i; ?>','editSubscriptionPlan')" title="<?php echo JText::_('AEC_CMN_CLICK_TO_EDIT'); ?>"><?php echo stripslashes( $rows[$i]->name ); ?></a></td>
					<td  align="left">
						<?php
						echo stripslashes( $description ); ?>
					</td>
					<td align="center">
						<a href="javascript:void(0);" onClick="return listItemTask('cb<?php echo $i;?>','<?php echo $aaction; ?>')">
							<?php echo aecHTML::Icon( $aicon, $atext ); ?>
						</a>
					</td>
					<td align="center">
						<a href="javascript:void(0);" onClick="return listItemTask('cb<?php echo $i;?>','<?php echo $vaction; ?>')">
							<?php echo aecHTML::Icon( $vicon, $vtext ); ?>
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
 		<?php HTML_myCommon::ContentLegend(); ?>
		<input type="hidden" name="option" value="<?php echo $option;?>" />
		<input type="hidden" name="task" value="showSubscriptionPlans" />
		<input type="hidden" name="returnTask" value="showSubscriptionPlans" />
		<input type="hidden" name="boxchecked" value="0" />
	</form>

		<?php
		if ( _EUCA_DEBUGMODE ) {
			krumo( $rows, $pageNav, $option );
		}

 		HTML_myCommon::endCommon();
	}

	function editSubscriptionPlan( $option, $aecHTML, $row, $hasrecusers )
	{
		$user = &JFactory::getUser();

		jimport( 'joomla.html.editor' );

		$editor =& JFactory::getEditor();

		HTML_myCommon::startCommon(); ?>

		<script type="text/javascript">
		    /* <![CDATA[ */
			function submitbutton(pressbutton) {
				<?php echo $editor->save( 'desc' ); ?>;
				submitform( pressbutton );
			}
			/* ]]> */
		</script>
		<?php HTML_myCommon::getHeader( 'AEC_HEAD_PLAN_INFO', 'aec_symbol_plans' ); ?>
		<small><?php echo $row->id ? $row->getProperty( 'name' ) : JText::_('AEC_CMN_NEW'); ?></small>
		<form action="index.php" method="post" name="adminForm" enctype="multipart/form-data">
			<table cellspacing="0" cellpadding="0" width="100%">
				<tr>
					<td valign="top">
						<?php
						$tabs = new bsPaneTabs;
		                echo $tabs->startPane( 'editSubscriptionPlan' );
		                echo $tabs->startPanel( JText::_('PAYPLAN_DETAIL_TITLE'), JText::_('PAYPLAN_DETAIL_TITLE') );
		                ?>
						<table class="aecadminform">
							<tr>
								<td style="padding:10px;" valign="top">
									<div style="position:relative;float:left;width:32%;padding:4px;">
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
									<div style="position:relative;float:left;width:32%;padding:4px;">
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
									<div style="position:relative;float:left;width:32%;padding:4px;">
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
		                echo $tabs->endPanel();
		                echo $tabs->startPanel( JText::_('PAYPLAN_PROCESSORS_TITLE'), JText::_('PAYPLAN_PROCESSORS_TITLE') );
						?>
						<table width="100%" class="aecadminform"><tr><td>
							<?php
							if ( !empty( $aecHTML->customparams->pp ) ) {
								foreach ( $aecHTML->customparams->pp as $id => $processor ) {
									?>
									<div class="aec_userinfobox_sub clear">
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
		                echo $tabs->endPanel();
		                echo $tabs->startPanel( JText::_('PAYPLAN_TEXT_TITLE'), JText::_('PAYPLAN_TEXT_TITLE') );
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
		                echo $tabs->endPanel();
		                echo $tabs->startPanel( JText::_('PAYPLAN_RESTRICTIONS_TITLE'), JText::_('PAYPLAN_RESTRICTIONS_TITLE') );
		                ?>
						<table class="aecadminform">
							<tr><td>
								<div class="aec_userinfobox_sub">
									<h4><?php echo JText::_('AEC_RESTRICTIONS_INVENTORY_HEADER'); ?></h4>
									<?php echo $aecHTML->createSettingsParticle( 'inventory_amount_enabled' ); ?>
									<?php echo $aecHTML->createSettingsParticle( 'inventory_amount' ); ?>
									<?php echo $aecHTML->createSettingsParticle( 'inventory_amount_used' ); ?>
								</div>
							</td></tr>
							<tr><td>
								<div class="aec_userinfobox_sub">
									<h4><?php echo JText::_('AEC_RESTRICTIONS_REDIRECT_HEADER'); ?></h4>
									<?php echo $aecHTML->createSettingsParticle( 'notauth_redirect' ); ?>
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
		                echo $tabs->endPanel();
		                echo $tabs->startPanel( JText::_('PAYPLAN_TRIAL_TITLE'), JText::_('PAYPLAN_TRIAL_TITLE') );
						?>
						<table width="100%" class="aecadminform"><tr><td>
							<div class="aec_userinfobox_sub">
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
		                echo $tabs->endPanel();
		                echo $tabs->startPanel( JText::_('PAYPLAN_RELATIONS_TITLE'), JText::_('PAYPLAN_RELATIONS_TITLE') );
						?>
						<table width="100%" class="aecadminform"><tr><td>
							<div class="aec_userinfobox_sub">
								<?php echo $aecHTML->createSettingsParticle( 'similarplans' ); ?>
								<?php echo $aecHTML->createSettingsParticle( 'equalplans' ); ?>
							</div>
						</td></tr></table>
						<?php
		                echo $tabs->endPanel();
		                echo $tabs->startPanel( JText::_('PAYPLAN_MI'), JText::_('PAYPLAN_MI') );
		                ?>
		                <table width="100%" class="aecadminform"><tr><td>
							<div class="aec_userinfobox_sub">
								<h2><?php echo JText::_('Inherited Micro Integrations'); ?></h2>
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
								<h2><?php echo JText::_('Attached Micro Integrations'); ?></h2>
								<?php
								if ( !empty( $aecHTML->customparams->mi['attached'] ) ) {
									echo '<ul>';
									foreach ( $aecHTML->customparams->mi['attached'] as $id => $mi ) {
										?>
										<li>
											<p>
												<input type="checkbox" name="micro_integrations[]" value="<?php echo $mi->id; ?>" <?php echo $mi->attached ? 'checked="checked"' : ''; ?> />
												<strong><?php echo $mi->name; ?></strong>
												(#<?php echo $mi->id; ?>)
												<?php echo $mi->inherited ? ( ' (' . '<input type="checkbox" name="inherited_micro_integrations[]" value="' . $mi->id . '" checked="checked" disabled="disabled" />' . JText::_('Inherited! See above.') . ')' ) : ''; ?>
												(<a href="index.php?option=com_acctexp&amp;task=editmicrointegration&amp;id=<?php echo $mi->id; ?>" target="_blank"><?php echo JText::_('edit'); ?></a>)
											</p>
											<p><?php echo $mi->desc; ?></p>
										</li>
										<?php
									}
									echo '</ul>';
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
		                echo $tabs->endPanel();
		                echo $tabs->endPane();
						?>
					</td>
				</tr>
			</table>
		<br />
		<input type="hidden" name="id" value="<?php echo $row->id; ?>" />
		<input type="hidden" name="option" value="<?php echo $option; ?>" />
		<input type="hidden" name="task" value="" />
		</form>
		<!--<script>swap();</script>-->

		<?php
		if ( _EUCA_DEBUGMODE ) {
			krumo( $option, $aecHTML, $row, $hasrecusers );
		}

 		HTML_myCommon::endCommon();
	}

	function listItemGroups( $rows, $pageNav, $option )
	{
		HTML_myCommon::startCommon(); ?>
		<form action="index.php" method="post" name="adminForm">
			<?php HTML_myCommon::getHeader( 'ITEMGROUPS_TITLE', 'aec_symbol_itemgroups' ); ?>

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
						$vaction	= 'invisibleItemGroup';
						$vicon		= 'eye.png';
						$vtext		= JText::_('AEC_CMN_VISIBLE');
						break;

					case '0':
						$vaction	= 'visibleItemGroup';
						$vicon		= 'cancel.png';
						$vtext		= JText::_('AEC_CMN_INVISIBLE');
						break;
				}

				switch( $rows[$i]->active ) {
					case '1':
						$aaction	= 'unpublishItemGroup';
						$aicon		= 'accept.png';
						$atext		= JText::_('AEC_CMN_PUBLISHED');
						break;

					case '0':
						$aaction	= 'publishItemGroup';
						$aicon		= 'cancel.png';
						$atext		= JText::_('AEC_CMN_NOT_PUBLISHED');
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
						<a href="javascript:void(0);" onClick="return listItemTask('cb<?php echo $i;?>','<?php echo $aaction; ?>')">
							<?php echo aecHTML::Icon( $aicon, $atext ); ?>
						</a>
					</td>
					<td align="center">
						<a href="javascript:void(0);" onClick="return listItemTask('cb<?php echo $i;?>','<?php echo $vaction; ?>')">
							<?php echo aecHTML::Icon( $vicon, $vtext ); ?>
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
 		<?php HTML_myCommon::ContentLegend(); ?>
		<input type="hidden" name="option" value="<?php echo $option;?>" />
		<input type="hidden" name="task" value="showItemGroups" />
		<input type="hidden" name="returnTask" value="showItemGroups" />
		<input type="hidden" name="boxchecked" value="0" />
	</form>

		<?php
		if ( _EUCA_DEBUGMODE ) {
			krumo( $rows, $pageNav, $option );
		}

 		HTML_myCommon::endCommon();
	}

	function editItemGroup( $option, $aecHTML, $row )
	{
		$user = &JFactory::getUser();

		HTML_myCommon::startCommon(); ?>
		<?php HTML_myCommon::getHeader( 'AEC_HEAD_ITEMGROUP_INFO', 'aec_symbol_itemgroups' ); ?>
		<small><?php echo $row->id ? $row->name : JText::_('AEC_CMN_NEW'); ?></small>
		<form action="index.php" method="post" name="adminForm" enctype="multipart/form-data">
			<table cellspacing="0" cellpadding="0" width="100%">
				<tr>
					<td valign="top">
						<?php
						$tabs = new bsPaneTabs;
		                echo $tabs->startPane( 'editItemGroup' );
		                echo $tabs->startPanel( JText::_('ITEMGROUP_DETAIL_TITLE'), JText::_('ITEMGROUP_DETAIL_TITLE') );
		                ?>
		                <h2><?php echo JText::_('ITEMGROUP_DETAIL_TITLE'); ?></h2>
						<table class="aecadminform">
							<tr>
								<td style="padding:10px;" valign="top">
									<div style="position:relative;float:left;width:32%;padding:4px;">
										<div class="aec_userinfobox_sub">
											<div style="position:relative;float:left;width:100%;">
												<?php
												echo $aecHTML->createSettingsParticle( 'name' );
												if ( $row->id ) { ?>
													<p><a href="<?php echo str_replace("/administrator/", "/", AECToolbox::deadsureURL( 'index.php?option=com_acctexp&task=subscribe&group=' . $row->id ) ); ?>" title="<?php echo JText::_('AEC_CGF_LINK_ABO_FRONTEND'); ?>" target="_blank"><?php echo JText::_('AEC_CGF_LINK_ABO_FRONTEND'); ?></a></p>
													<?php
												} ?>
											</div>
											<?php echo $aecHTML->createSettingsParticle( 'active' ); ?>
											<?php echo $aecHTML->createSettingsParticle( 'visible' ); ?>
											<?php echo $aecHTML->createSettingsParticle( 'color' ); ?>
											<?php echo $aecHTML->createSettingsParticle( 'icon' ); ?>
											<?php echo $aecHTML->createSettingsParticle( 'reveal_child_items' ); ?>
											<?php echo $aecHTML->createSettingsParticle( 'symlink' ); ?>
											<?php echo $aecHTML->createSettingsParticle( 'symlink_userid' ); ?>
											<?php echo $aecHTML->createSettingsParticle( 'notauth_redirect' ); ?>
										</div>
									</div>
									<div style="position:relative;float:left;width:32%;padding:4px;">
										<div class="aec_userinfobox_sub">
											<h2 style="clear:both;"><?php echo JText::_('ITEMGROUPS_TITLE'); ?></h2>
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
									<div style="position:relative;float:left;width:98%;padding:4px;">
										<div class="aec_userinfobox_sub">
											<?php echo $aecHTML->createSettingsParticle( 'desc' ); ?>
										</div>
									</div>
								</td>
							</tr>
						</table>
						<?php
		                echo $tabs->endPanel();
		                echo $tabs->startPanel( JText::_('ITEMGROUP_RESTRICTIONS_TITLE'), JText::_('ITEMGROUP_RESTRICTIONS_TITLE') );
		                ?>
		                <h2><?php echo JText::_('ITEMGROUP_RESTRICTIONS_TITLE'); ?></h2>
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
		                echo $tabs->endPanel();
		                echo $tabs->startPanel( JText::_('PAYPLAN_MI'), JText::_('PAYPLAN_MI') );
		                ?>
		                <table width="100%" class="aecadminform"><tr><td>
							<div class="aec_userinfobox_sub">
								<?php if ( $row->id > 1 ) { ?>
								<h2><?php echo JText::_('Inherited Micro Integrations'); ?></h2>
								<?php
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
								?>
								<?php } ?>
								<h2><?php echo JText::_('Attached Micro Integrations'); ?></h2>
								<?php
								if ( !empty( $aecHTML->customparams->mi['attached'] ) ) {
									echo '<ul>';
									foreach ( $aecHTML->customparams->mi['attached'] as $id => $mi ) {
										?>
										<li>
											<p>
												<input type="checkbox" name="micro_integrations[]" value="<?php echo $mi->id; ?>" <?php echo $mi->attached ? 'checked="checked"' : ''; ?> />
												<strong><?php echo $mi->name; ?></strong>
												(#<?php echo $mi->id; ?>)
												<?php echo $mi->inherited ? ( ' (' . '<input type="checkbox" name="inherited_micro_integrations[]" value="' . $mi->id . '" checked="checked" disabled="disabled" />' . JText::_('Inherited! See above.') . ')' ) : ''; ?>
												(<a href="index.php?option=com_acctexp&amp;task=editmicrointegration&amp;id=<?php echo $mi->id; ?>" target="_blank"><?php echo JText::_('edit'); ?></a>)
											</p>
											<p><?php echo $mi->desc; ?></p>
										</li>
										<?php
									}
									echo '</ul>';
								}
								?>
							</div>
						</td></tr></table>
						<?php
		                echo $tabs->endPanel();
		                echo $tabs->endPane();
						?>
					</td>
				</tr>
			</table>
		<br />
		<input type="hidden" name="id" value="<?php echo $row->id; ?>" />
		<input type="hidden" name="option" value="<?php echo $option; ?>" />
		<input type="hidden" name="task" value="" />
		</form>
		<!--<script>swap();</script>-->

		<?php
		if ( _EUCA_DEBUGMODE ) {
			krumo( $option, $aecHTML, $row );
		}

 		HTML_myCommon::endCommon();
	}

	function listCoupons( $rows, $pageNav, $option, $type )
	{
		HTML_myCommon::startCommon(); ?>
		<form action="index.php" method="post" name="adminForm">
			<?php HTML_myCommon::getHeader( 'COUPON_TITLE'. ( $type ? '_STATIC' : '' ), 'aec_symbol_coupons' . ( $type ? '_static' : '' ) ); ?>

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
							<?php echo aecHTML::Icon( ( $rows[$i]->active ) ? 'accept.png' : 'cancel.png' ); ?>
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
 		<?php HTML_myCommon::ContentLegend(); ?>
		<input type="hidden" name="option" value="<?php echo $option;?>" />
		<input type="hidden" name="task" value="showCoupons<?php echo $type ? 'Static' : ''; ?>" />
		<input type="hidden" name="returnTask" value="showCoupons<?php echo $type ? 'Static' : ''; ?>" />
		<input type="hidden" name="boxchecked" value="0" />
		</form>

		<?php
		if ( _EUCA_DEBUGMODE ) {
			krumo( $rows, $pageNav, $option, $type );
		}

 		HTML_myCommon::endCommon();
	}

	function editCoupon( $option, $aecHTML, $row, $type )
	{
		$user = &JFactory::getUser();

		HTML_myCommon::startCommon();

		JHTML::_('behavior.calendar');
		?>
		<?php HTML_myCommon::getHeader( 'AEC_COUPON', 'aec_symbol_coupons' . ($type ? '_static' : '') ); ?>
		<small><?php echo $row->id ? $row->name : JText::_('AEC_CMN_NEW'); ?></small>
		<!--<form action="index.php" method="post" name="adminForm" enctype="multipart/form-data" onLoad="swap();" >-->
		<form action="index.php" method="post" name="adminForm" enctype="multipart/form-data">
			<table cellspacing="0" cellpadding="0" width="100%">
				<tr>
					<td valign="top">
						<?php
						$tabs = new bsPaneTabs;
		                echo $tabs->startPane( 'editSubscriptionPlan' );
		                echo $tabs->startPanel( JText::_('COUPON_DETAIL_TITLE'), JText::_('COUPON_DETAIL_TITLE') ); ?>
		                <h2><?php echo JText::_('COUPON_DETAIL_TITLE'); ?></h2>
						<table class="aecadminform">
							<tr>
								<td style="padding:10px;" valign="top">
									<div style="position:relative;float:left;width:48%;padding:4px;">
										<div class="aec_userinfobox_sub">
											<div style="position:relative;float:left;width:100%;">
												<?php echo $aecHTML->createSettingsParticle( 'name' ); ?>
												<?php echo $aecHTML->createSettingsParticle( 'coupon_code' ); ?>
												<div style="position:relative;float:left;">
													<?php echo $aecHTML->createSettingsParticle( 'active' ); ?>
													<?php echo $aecHTML->createSettingsParticle( 'type' ); ?>
												</div>
												<div style="position:relative;float:left;">
												<?php echo $aecHTML->createSettingsParticle( 'desc' ); ?>
												</div>
											</div>
										</div>
									</div>
									<div style="position:relative;float:left;width:48%;padding:4px;">
										<div class="aec_userinfobox_sub">
											<div style="position:relative;float:left;">
												<?php echo $aecHTML->createSettingsParticle( 'amount_use' ); ?>
												<?php echo $aecHTML->createSettingsParticle( 'amount' ); ?>
											</div>
											<div style="position:relative;float:left;">
												<?php echo $aecHTML->createSettingsParticle( 'amount_percent_use' ); ?>
												<?php echo $aecHTML->createSettingsParticle( 'amount_percent' ); ?>
												<?php echo $aecHTML->createSettingsParticle( 'percent_first' ); ?>
											</div>
											<div style="position:relative;float:left;">
												<?php echo $aecHTML->createSettingsParticle( 'useon_trial' ); ?>
												<?php echo $aecHTML->createSettingsParticle( 'useon_full' ); ?>
												<?php echo $aecHTML->createSettingsParticle( 'useon_full_all' ); ?>
											</div>
										</div>
									</div>
									<div style="position:relative;float:left;width:90%;padding:4px;">
										<div class="aec_userinfobox_sub">
											<div style="position:relative;float:left;">
												<?php echo $aecHTML->createSettingsParticle( 'has_start_date' ); ?>
												<?php echo $aecHTML->createSettingsParticle( 'start_date' ); ?>
											</div>
											<div style="position:relative;float:left;">
												<?php echo $aecHTML->createSettingsParticle( 'has_expiration' ); ?>
												<?php echo $aecHTML->createSettingsParticle( 'expiration' ); ?>
											</div>
											<div style="position:relative;float:left;">
												<?php echo $aecHTML->createSettingsParticle( 'has_max_reuse' ); ?>
												<?php echo $aecHTML->createSettingsParticle( 'max_reuse' ); ?>
												<?php echo $aecHTML->createSettingsParticle( 'usecount' ); ?>
											</div>
											<div style="position:relative;float:left;">
												<?php echo $aecHTML->createSettingsParticle( 'has_max_peruser_reuse' ); ?>
												<?php echo $aecHTML->createSettingsParticle( 'max_peruser_reuse' ); ?>
											</div>
											<div style="position:relative;float:left;">
												<?php echo $aecHTML->createSettingsParticle( 'usage_plans_enabled' ); ?>
												<?php echo $aecHTML->createSettingsParticle( 'usage_plans' ); ?>
											</div>
											<div style="position:relative;float:left;">
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
		                echo $tabs->endPanel();
		                echo $tabs->startPanel( JText::_('COUPON_RESTRICTIONS_TITLE'), JText::_('COUPON_RESTRICTIONS_TITLE') );
		                ?>
		                <h2><?php echo JText::_('COUPON_RESTRICTIONS_TITLE_FULL'); ?></h2>
						<table class="aecadminform">
							<tr><td>
								<div class="aec_userinfobox_sub">
									<div style="position:relative;float:left;width:200px;">
										<?php echo $aecHTML->createSettingsParticle( 'depend_on_subscr_id' ); ?>
										<?php echo $aecHTML->createSettingsParticle( 'subscr_id_dependency' ); ?>
										<?php echo $aecHTML->createSettingsParticle( 'allow_trial_depend_subscr' ); ?>
									</div>
									<div style="position:relative;float:left;width:200px;">
										<?php echo $aecHTML->createSettingsParticle( 'restrict_combination' ); ?>
										<?php echo $aecHTML->createSettingsParticle( 'bad_combinations' ); ?>
									</div>
									<div style="position:relative;float:left;width:200px;">
										<?php echo $aecHTML->createSettingsParticle( 'allow_combination' ); ?>
										<?php echo $aecHTML->createSettingsParticle( 'good_combinations' ); ?>
									</div>
									<div style="position:relative;float:left;width:240px;">
										<?php echo $aecHTML->createSettingsParticle( 'restrict_combination_cart' ); ?>
										<?php echo $aecHTML->createSettingsParticle( 'bad_combinations_cart' ); ?>
									</div>
									<div style="position:relative;float:left;width:240px;">
										<?php echo $aecHTML->createSettingsParticle( 'allow_combination_cart' ); ?>
										<?php echo $aecHTML->createSettingsParticle( 'good_combinations_cart' ); ?>
									</div>
								</div>
							</td></tr>
							<?php echo aecRestrictionHelper::echoSettings( $aecHTML ); ?>
						</table>
						<?php
		                echo $tabs->endPanel();
		                echo $tabs->startPanel( JText::_('COUPON_MI'), JText::_('COUPON_MI') );
		                ?>
		                <table width="100%" class="aecadminform"><tr><td>
							<div class="aec_userinfobox_sub">
								<h2><?php echo JText::_('COUPON_MI_FULL'); ?></h2>
								<?php echo $aecHTML->createSettingsParticle( 'micro_integrations' ); ?>
							</div>
						</td></tr></table>
						<?php
		                echo $tabs->endPanel();
		                echo $tabs->endPane();
						?>
					</td>
				</tr>
			</table>
		<br />
		<input type="hidden" name="id" value="<?php echo $row->id; ?>" />
		<input type="hidden" name="option" value="<?php echo $option; ?>" />
		<input type="hidden" name="task" value="" />
		</form>
		<!--<script>swap();</script>-->

		<?php
		if ( _EUCA_DEBUGMODE ) {
			krumo( $option, $aecHTML, $row, $type );
		}

 		HTML_myCommon::endCommon();
	}

	function viewinvoices( $option, $rows, $search, $pageNav )
	{
		$user = &JFactory::getUser();

		HTML_myCommon::startCommon();
		?>
		<form action="index.php" method="post" name="adminForm">
		<?php HTML_myCommon::getHeader( 'INVOICE_TITLE', 'aec_symbol_invoices' ); ?>
			<?php echo JText::_('INVOICE_SEARCH'); ?>: <br />
			<input type="text" name="search" value="<?php echo htmlspecialchars($search);?>" class="text_area" onChange="document.adminForm.submit();" />

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
		<input type="hidden" name="option" value="<?php echo $option;?>" />
		<input type="hidden" name="task" value="invoices" />
		<input type="hidden" name="returnTask" value="invoices" />
		<input type="hidden" name="boxchecked" value="0" />
		</form>

		<?php
		if ( _EUCA_DEBUGMODE ) {
			krumo( $option, $rows, $search, $pageNav );
		}

 		HTML_myCommon::endCommon();
	}

	function viewhistory( $option, $rows, $search, $pageNav )
	{
		$user = &JFactory::getUser();

		HTML_myCommon::startCommon();
		?>
		<form action="index.php" method="post" name="adminForm">
		<?php HTML_myCommon::getHeader( 'HISTORY_TITLE2', 'aec_symbol_history' ); ?>
		<?php echo JText::_('HISTORY_SEARCH'); ?>: <br />
		<input type="text" name="search" value="<?php echo htmlspecialchars($search);?>" class="text_area" onChange="document.adminForm.submit();" />

		<table class="adminlist">
		<thead><tr>
			<th align="left" width="15%"><?php echo JText::_('HISTORY_USERID'); ?></th>
			<th align="center" width="10%"><?php echo JText::_('HISTORY_INVOICE_NUMBER'); ?></th>
			<th width="10%"><?php echo JText::_('HISTORY_PLAN_NAME'); ?></th>
			<th align="center" width="15%"><?php echo JText::_('HISTORY_TRANSACTION_DATE'); ?></th>
			<th align="center" width="10%"><?php echo JText::_('HISTORY_METHOD'); ?></th>
			<th align="center" width="10%"><?php echo JText::_('HISTORY_AMOUNT'); ?></th>
			<th width="30%"><?php echo JText::_('HISTORY_RESPONSE'); ?></th>
		  </tr></thead>
		<?php
		$k = 0;
		foreach ( $rows as $row ) { ?>
			<tr class="row<?php echo $k; ?>">
				<td><?php echo $row->user_name; ?></td>
				<td align="center"><?php echo $row->invoice_number; ?></td>
				<td align="center"><?php echo $row->plan_name; ?></td>
	  			<td align="center"><?php echo $row->transaction_date; ?></td>
				<td align="center"><?php echo $row->proc_name; ?></td>
				<td align="center"><?php echo $row->amount; ?></td>
				<td align="left">
					<?php
						if ( !empty( $row->response ) && ( strlen( $row->response ) > 8) ) {
							$field = unserialize( base64_decode( $row->response ) );

							if ( is_array( $field ) ) {
								if ( count( $field ) == 1 ) {
									foreach ( $field as $n => $v) {
										if ( !empty( $n ) && ( strlen( $n ) > 8) ) {
											$field = unserialize( base64_decode( $n ) );
										}
									}
								}

								if ( is_array( $field ) ) {
									foreach ( $field as $n => $v) {
										echo $n." = ".$v."<br />";
									}
								} else {
									echo $field."<br />";
								}
							} else {
								echo $field."<br />";
							}
						}
					?>
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
		<input type="hidden" name="option" value="<?php echo $option;?>" />
		<input type="hidden" name="task" value="history" />
		<input type="hidden" name="returnTask" value="history" />
		<input type="hidden" name="boxchecked" value="0" />
		</form>

		<?php
		if ( _EUCA_DEBUGMODE ) {
			krumo( $option, $rows, $search, $pageNav );
		}

 		HTML_myCommon::endCommon();
	}

	function eventlog( $option, $events, $search, $pageNav )
	{
		HTML_myCommon::startCommon(); ?>
		<form action="index.php" method="post" name="adminForm">
		<?php HTML_myCommon::getHeader( 'AEC_HEAD_LOG', 'aec_symbol_eventlog' ); ?>
		<?php echo JText::_('HISTORY_SEARCH'); ?>: <br />
		<input type="text" name="search" value="<?php echo htmlspecialchars($search);?>" class="text_area" onChange="document.adminForm.submit();" />

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
				<td align="left"><?php echo $row->notify ? aecHTML::Icon( 'star.png', '' ) : '&nbsp;'; ?></td>
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
		<input type="hidden" name="option" value="<?php echo $option;?>" />
		<input type="hidden" name="task" value="eventlog" />
		<input type="hidden" name="returnTask" value="eventlog" />
		<input type="hidden" name="boxchecked" value="0" />
		</form>

		<?php
		if ( _EUCA_DEBUGMODE ) {
			krumo( $option, $events, $search, $pageNav );
		}

 		HTML_myCommon::endCommon();
	}

	function stats( $option, $page, $stats )
	{
		HTML_myCommon::startCommon(); ?>
		<link rel="stylesheet" type="text/css" media="all" href="<?php echo JURI::root(); ?>media/com_acctexp/css/admin.stats.css" />
		<form action="index.php" method="post" name="adminForm">
		<div id="stats">
		<div id="statnav">
			<?php HTML_myCommon::getHeader( 'AEC_HEAD_STATS', 'aec_symbol_stats' ); ?>
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
		if ( _EUCA_DEBUGMODE ) {
			krumo( $option, $stats );
		}

 		HTML_myCommon::endCommon();
	}

	function stats2( $option, $stats )
	{
		HTML_myCommon::startCommon(); ?>
		<form action="index.php" method="post" name="adminForm">
		<table class="adminheading">
		<tr>
			<th width="100%" class="aec_backend_page_heading" style="background: url(<?php echo JURI::root(); ?>media/com_acctexp/images/admin/icons/aec_symbol_stats.png) no-repeat left;" rowspan="2" nowrap="nowrap">
				<?php echo JText::_('AEC_HEAD_STATS'); ?>
			</th>
		</tr>
		</table>

		<h1>Stats aren't done yet, please check back here in the stable release</h1>

		<?php
		if ( _EUCA_DEBUGMODE ) {
			krumo( $option, $stats );
		}

 		HTML_myCommon::endCommon();
	}

	function readoutSetup( $option, $aecHTML )
	{
		HTML_myCommon::startCommon();
		?>
		<form action="index.php" method="post" name="adminForm">
		<?php HTML_myCommon::getHeader( 'AEC_READOUT', 'aec_symbol_export' ); ?>

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
			<input type="submit" class="btn primary" />
			<br />
		</div>

		</form>


		<?php
		echo $aecHTML->loadJS();

		if ( _EUCA_DEBUGMODE ) {
			krumo( $option, $aecHTML );
		}

 		HTML_myCommon::endCommon();
	}

	function readout( $option, $readout )
	{
		HTML_myCommon::addReadoutCSS();
		HTML_myCommon::startCommon();

		if ( isset( $_POST['column_headers'] ) ) {
			$ch = $_POST['column_headers'];
		} else {
			$ch = 20;
		}

		?>

		<table class="aec_bg"><tr><td>
			<?php foreach ( $readout as $part ) { ?>
				<?php
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
						echo "<table class=\"aec_readout\">";

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

								$dcc = $entry[$dn];

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
		<form action="index.php" enctype="multipart/form-data" method="post" name="adminForm">
		<?php HTML_myCommon::getHeader( 'AEC_HEAD_IMPORT', 'aec_symbol_import' ); ?>
		<div class="aec_import<?php echo $aecHTML->form ? '' : '_large'; ?> aec_userinfobox_sub">
			<table style="width:100%;">
				<tr>
					<td valign="top">
						<?php
						if ( $aecHTML->done ) {
							echo '<p>Import ran through successfully.</p>';
							if ( $aecHTML->errors ) {
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
					</td>
				</tr>
			</table>
		</div>

		<input type="hidden" name="option" value="<?php echo $option;?>" />
		<input type="hidden" name="task" value="import" />
		<input type="hidden" name="returnTask" value="import" />
		</form>

		<?php
		echo $aecHTML->loadJS();

		if ( _EUCA_DEBUGMODE ) {
			krumo( $option, $aecHTML );
		}

 		HTML_myCommon::endCommon();
	}

	function export( $option, $task, $aecHTML )
	{
		HTML_myCommon::startCommon();
		?>
		<form action="index.php" method="post" name="adminForm">
		<?php HTML_myCommon::getHeader( 'AEC_HEAD_EXPORT', 'aec_symbol_export' ); ?>

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

		if ( _EUCA_DEBUGMODE ) {
			krumo( $option, $aecHTML );
		}

 		HTML_myCommon::endCommon();
	}

	function toolBox( $option, $cmd, $result, $title=null )
	{
		JHTML::_('behavior.calendar');
		HTML_myCommon::startCommon();
		?>
		<form action="index.php" enctype="multipart/form-data" method="post" name="adminForm">
		<?php HTML_myCommon::getHeader( 'AEC_HEAD_TOOLBOX', 'aec_symbol_toolbox' ); ?>
		<?php echo JText::_('AEC_HEAD_TOOLBOX') . ( $title ? ( ': ' . $title ) : '' ); ?>
		<?php if ( empty( $cmd ) ) { ?>
		<p>This is an experimental part of AEC. It can destroy lots of data with the click of a button. Please backup your data extensively.</p>
		<?php } ?>
		<?php if ( is_array( $result ) ) { ?>
			<div id="aec-toolbox-list">
			<?php foreach ( $result as $x => $litem ) {
				echo '<a href="' . $litem['link'] . '"><h3>' . $litem['name'] . '</h3></a><p>' . $litem['desc'] . '</p>';
				echo '<hr />';
			} ?>
			</div>
		<?php } else { ?>
			<?php echo $result; ?>
		<?php } ?>
		<input type="hidden" name="option" value="<?php echo $option;?>" />
		<input type="hidden" name="task" value="toolbox" />
		<input type="hidden" name="cmd" value="<?php echo $cmd;?>" />
		</form>

		<?php

 		HTML_myCommon::endCommon();
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

	function startTabs() { echo '<ul class="pills">'; }
	function endTabs() { echo '</ul>'; }
	function newTab( $handle, $title, $current=false ) { echo '<li' . ( $current ? ' class="active"' : '' ) . '><a href="#' . $handle . '">' . $title . '</a></li>'; }

	function startPanes() { echo '<div class="pill-content">'; }
	function endPanes() { echo '</div>'; }

	function startPane( $id, $current=false ) { echo '<div id="' . $id . '" class="tab-pane' . ( $current ? ' active' : '' ) . '">'; }
	function endPane() { echo "</div>"; }

	function _loadBehavior($params = array())
	{
		$document =& JFactory::getDocument();
		$document->addScript( '/media/com_acctexp/js/bootstrap/bootstrap-tabs.js' );
		$document->addScriptDeclaration( 'jQuery(document).ready(function($) {
			jQuery(\'.pills\').pills()
		});' );
	}
}

?>
