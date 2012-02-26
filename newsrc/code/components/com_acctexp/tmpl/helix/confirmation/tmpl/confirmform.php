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
<form name="confirmForm" action="<?php echo AECToolbox::deadsureURL( 'index.php?option=' . $option . '&task=saveSubscription', $tmpl->cfg['ssl_signup'] ) ?>" method="post">
<table>
	<tr>
		<td id="confirmation_extra">
			<?php
			@include( $tmpl->tmpl( 'miform' ) );
			$tmpl->custom( 'customtext_confirm' );
			@include( $tmpl->tmpl( 'couponform' ) );
			?>
		</td>
	</tr>
	<?php if ( $makegift ) { @include( $tmpl->tmpl( 'giftform' ) ); } ?>
	<tr>
		<td id="confirmation_button"><?php @include( $tmpl->tmpl( 'confirmationbutton' ) ) ?></td>
	</tr>
	<tr>
		<td><table><?php @include( $tmpl->tmpl( 'plans.processor_details' ) ) ?></table></td>
	</tr>
</table>
<?php echo JHTML::_( 'form.token' ) ?>
</form>
