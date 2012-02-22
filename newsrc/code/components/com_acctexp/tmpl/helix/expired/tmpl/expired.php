<?
/**
 * @version $Id: expired.php
 * @package AEC - Account Control Expiration - Membership Manager
 * @subpackage Main Frontend
 * @copyright 2012 Copyright (C) David Deutsch
 * @author David Deutsch <skore@valanx.org> & Team AEC - http://www.valanx.org
 * @license GNU/GPL v.2 http://www.gnu.org/licenses/old-licenses/gpl-2.0.html or, at your option, any later version
 */

// Dont allow direct linking
( defined('_JEXEC') || defined( '_VALID_MOS' ) ) or die( 'Direct Access to this location is not allowed.' )?>

<? if ( $tmpl->cfg['customtext_expired_keeporiginal'] ) { ?>
<div class="componentheading"><?= JText::_('EXPIRED_TITLE')?></div>
<div id="expired_greeting">
	<p><?= sprintf( JText::_('DEAR'), $metaUser->cmsUser->name )?></p><p><?
		if ( $is_trial ) {
			echo JText::_('EXPIRED_TRIAL');
		} else {
			echo JText::_('EXPIRED');
		}
		echo $expiration?>
	</p>
</div>
<? } ?>

<? if ( $tmpl->cfg['customtext_expired'] ) { ?>
	<p><?= AECToolbox::rewriteEngine( $tmpl->cfg['customtext_expired'], $metaUser )?></p>
<? } ?>

<div id="box_expired">
	<div id="alert_level_1">
		<? if ( $invoice ) { ?>
			<p><?= JText::_('PENDING_OPENINVOICE')
							. '&nbsp;'
							. $tmpl->lnk( array(	'task' => 'repeatPayment',
													'invoice' => $invoice,
													'userid' => $metaUser->id
													), JText::_('GOTO_CHECKOUT') )?>
			</p>
		<? } ?>
		<? if ( $is_continue ) { ?>
			<div id="renew_button">
				<?= $tmpl->btn( array(	'task' => 'renewSubscription',
												'userid' => $objUser->id,
												'usage' => $metaUser->focusSubscription->plan,
												'intro' => $intro
												), JText::_('RENEW_BUTTON_CONTINUE') )?>
			</div>
		<? } ?>
		<div id="renew_button">
				<?= $tmpl->btn( array(	'task' => 'renewSubscription',
												'userid' => $objUser->id,
												'intro' => $intro
												), JText::_('RENEW_BUTTON') )?>
		</div>
	</div>
</div>
