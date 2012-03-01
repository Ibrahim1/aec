<?php
/**
 * @version $Id: btn.php
 * @package AEC - Account Control Expiration - Membership Manager
 * @subpackage Main Frontend
 * @copyright 2012 Copyright (C) David Deutsch
 * @author David Deutsch <skore@valanx.org> & Team AEC - http://www.valanx.org
 * @license GNU/GPL v.3 http://www.gnu.org/licenses/gpl.html or, at your option, any later version
 */

// Dont allow direct linking
( defined('_JEXEC') || defined( '_VALID_MOS' ) ) or die( 'Direct Access to this location is not allowed.' ) ?>
<div class="well">
	<div id="confirmation-button">
		<p><?php echo JText::_('CONFIRM_INFO') ?></p>
		<input type="hidden" name="option" value="<?php echo $option ?>" />
		<input type="hidden" name="userid" value="<?php echo $userid ? $userid : 0 ?>" />
		<input type="hidden" name="task" value="saveSubscription" />
		<input type="hidden" name="usage" value="<?php echo $InvoiceFactory->usage ?>" />
		<input type="hidden" name="processor" value="<?php echo $InvoiceFactory->processor ?>" />
		<?php if ( isset( $InvoiceFactory->recurring ) ) { ?>
			<input type="hidden" name="recurring" value="<?php echo $InvoiceFactory->recurring ?>" />
		<?php } ?>
		<?php if ( $passthrough != false ) { ?>
			<input type="hidden" name="aec_passthrough" value="<?php echo $passthrough ?>" />
		<?php } ?>
		<?php if ( !empty( $tmpl->cfg['tos_iframe'] ) && !empty( $tmpl->cfg['tos'] ) ) { ?>
			<iframe src="<?php echo $tmpl->cfg['tos'] ?>" width="100%" height="150px"></iframe>
			<p><input name="tos" type="checkbox" /><?php echo JText::_('CONFIRM_TOS_IFRAME') ?></p>
			<button type="submit" onClick="javascript:submitPayment()" class="button btn btn-success"><i class="icon-ok icon-white"></i><?php echo JText::_('BUTTON_CONFIRM') ?></button>
			<?php
		} elseif ( !empty( $tmpl->cfg['tos'] ) ) { ?>
			<p><input name="tos" type="checkbox" /><?php echo JText::sprintf( 'CONFIRM_TOS', $tmpl->cfg['tos'] ) ?></p>
			<button type="submit" onClick="javascript:submitPayment()" class="button btn btn-success"><i class="icon-ok icon-white"></i><?php echo JText::_('BUTTON_CONFIRM') ?></button>
			<?php
		} else { ?>
			<button type="submit" class="button btn btn-success"><i class="icon-ok icon-white"></i><?php echo JText::_('BUTTON_CONFIRM') ?></button>
			<?php
		} ?>
	</div>
</div>
