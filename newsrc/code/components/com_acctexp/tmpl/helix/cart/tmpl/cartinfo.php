<?php
/**
 * @version $Id: confirmform.php
 * @package AEC - Account Control Expiration - Membership Manager
 * @subpackage Main Frontend
 * @copyright 2012 Copyright (C) David Deutsch
 * @author David Deutsch <skore@valanx.org> & Team AEC - http://www.valanx.org
 * @license GNU/GPL v.3 http://www.gnu.org/licenses/gpl.html or, at your option, any later version
 */

// Dont allow direct linking
( defined('_JEXEC') || defined( '_VALID_MOS' ) ) or die( 'Direct Access to this location is not allowed.' ) ?>
<form name="confirmForm" action="<?php echo AECToolbox::deadsureURL( 'index.php?option=' . $option . '&task=confirmCart', $tmpl->cfg['ssl_signup'] ) ?>" method="post">
<table>
	<tr>
		<td id="confirmation_extra">
			<?
			@include( $tmpl->tmpl( 'confirmation.miform' ) );
			$tmpl->custom( 'customtext_confirm' );
			?>
		</td>
	</tr>
	<?php if ( $makegift ) {
		@include( $tmpl->tmpl( 'confirmation.giftform' ) );
	} ?>
	<tr>
		<td id="confirmation_button"><?php @include( $tmpl->tmpl( 'confirmationbtn' ) ) ?></td>
	</tr>
	<tr>
		<td><?php @include( $tmpl->tmpl( 'confirmation.processorinfo' ) ) ?></td>
	</tr>
</table>
<?php echo JHTML::_( 'form.token' ) ?>
</form>
