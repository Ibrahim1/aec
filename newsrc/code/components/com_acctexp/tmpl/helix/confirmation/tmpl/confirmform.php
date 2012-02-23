<?php
/**
 * @version $Id: confirmform.php
 * @package AEC - Account Control Expiration - Membership Manager
 * @subpackage Main Frontend
 * @copyright 2012 Copyright (C) David Deutsch
 * @author David Deutsch <skore@valanx.org> & Team AEC - http://www.valanx.org
 * @license GNU/GPL v.2 http://www.gnu.org/licenses/old-licenses/gpl-2.0.html or, at your option, any later version
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
	<tr><td>
		<table>
			<?php if ( is_object( $InvoiceFactory->pp ) ) {
				HTML_frontEnd::processorInfo( $option, $InvoiceFactory->pp, $tmpl->cfg['displayccinfo'] );
			} ?>
		</table>
	</td></tr>
</table>
<?php echo JHTML::_( 'form.token' ) ?>
</form>
