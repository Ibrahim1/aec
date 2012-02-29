<?php
/**
 * @version $Id: expired.php
 * @package AEC - Account Control Expiration - Membership Manager
 * @subpackage Main Frontend
 * @copyright 2012 Copyright (C) David Deutsch
 * @author David Deutsch <skore@valanx.org> & Team AEC - http://www.valanx.org
 * @license GNU/GPL v.3 http://www.gnu.org/licenses/gpl.html or, at your option, any later version
 */

// Dont allow direct linking
( defined('_JEXEC') || defined( '_VALID_MOS' ) ) or die( 'Direct Access to this location is not allowed.' ) ?>
<div id="aec">
	<div id="aec-expired">
		<div class="componentheading"><?php echo JText::_('EXPIRED_TITLE') ?></div>
		<h4><?php echo sprintf( JText::_('DEAR'), $metaUser->cmsUser->name ) ?></h4>
		<p><?php echo JText::_( $is_trial ? 'EXPIRED_TRIAL' : 'EXPIRED' ) . $expiration ?></p>
		<div class="alert alert-warning">
			<?php if ( $invoice ) { ?>
				<p><?php echo JText::_('PENDING_OPENINVOICE')
								. '&nbsp;'
								. $tmpl->lnk( array(	'task' => 'repeatPayment',
														'invoice' => $invoice,
														'userid' => $metaUser->id
														), JText::_('GOTO_CHECKOUT') ) ?>
				</p>
			<?php } ?>
			<?php if ( $is_continue ) { ?>
				<div id="renew_button">
					<?php echo $tmpl->btn( array(	'task' => 'renewSubscription',
											'userid' => $metaUser->userid,
											'usage' => $metaUser->focusSubscription->plan,
											'intro' => $intro
											), JText::_('RENEW_BUTTON_CONTINUE') ) ?>
				</div>
			<?php } ?>
			<div id="renew_button">
					<?php echo $tmpl->btn( array(	'task' => 'renewSubscription',
											'userid' => $metaUser->userid,
											'intro' => $intro
											), JText::_('RENEW_BUTTON') ) ?>
			</div>
		</div>
	</div>
</div>
