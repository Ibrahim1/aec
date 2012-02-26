<?
/**
 * @version $Id: access_denied.php
 * @package AEC - Account Control Expiration - Membership Manager
 * @subpackage Main Frontend
 * @copyright 2012 Copyright (C) David Deutsch
 * @author David Deutsch <skore@valanx.org> & Team AEC - http://www.valanx.org
 * @license GNU/GPL v.3 http://www.gnu.org/licenses/gpl.html or, at your option, any later version
 */

// Dont allow direct linking
( defined('_JEXEC') || defined( '_VALID_MOS' ) ) or die( 'Direct Access to this location is not allowed.' ) ?>

<div id="box_pending">
	<p><?php echo JText::_('AEC_PROMPT_PASSWORD'); ?></p>
	<?php
		if ( $wrong ) {
			echo '<p><strong>' . JText::_('AEC_PROMPT_PASSWORD_WRONG') . '</strong></p>';
		}
	?>
	<div id="upgrade_button">
		<form action="<?php echo AECToolbox::deadsureURL( 'index.php?option=com_acctexp&task=subscribe', $aecConfig->cfg['ssl_signup'] ); ?>" method="post">
			<input type="password" size="20" class="inputbox" id="password" name="password"/>
			<?php if ( $passthrough != false ) {
				$pt = unserialize( base64_decode( $passthrough ) );

				if ( isset( $pt['task'] ) ) {
					echo '<input type="hidden" name="task" value="' . $pt['task'] . '" />';
				}

				if ( isset( $pt['userid'] ) ) {
					echo '<input type="hidden" name="userid" value="' . $pt['userid'] . '" />';
				} ?>
				<input type="hidden" name="aec_passthrough" value="<?php echo $passthrough; ?>" />
			<?php } ?>
			<input type="submit" class="button" value="<?php echo JText::_('AEC_PROMPT_PASSWORD_BUTTON');?>" />
			<?php echo JHTML::_( 'form.token' ); ?>
		</form>
	</div>
</div>
<div class="aec_clearfix"></div>