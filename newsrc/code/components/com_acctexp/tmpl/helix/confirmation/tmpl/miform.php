<?php
/**
 * @version $Id: miform.php
 * @package AEC - Account Control Expiration - Membership Manager
 * @subpackage Main Frontend
 * @copyright 2012 Copyright (C) David Deutsch
 * @author David Deutsch <skore@valanx.org> & Team AEC - http://www.valanx.org
 * @license GNU/GPL v.3 http://www.gnu.org/licenses/gpl.html or, at your option, any later version
 */

// Dont allow direct linking
( defined('_JEXEC') || defined( '_VALID_MOS' ) ) or die( 'Direct Access to this location is not allowed.' ) ?>
<?php
if ( !empty( $InvoiceFactory->mi_form ) ) {
	if ( !empty( $InvoiceFactory->mi_error ) ) {
		echo '<div id="confirmation_error">';
		foreach ( $InvoiceFactory->mi_error as $error ) {
			echo '<p>' . $error . '</p>';
		}
		echo '</div>';
	}
	echo '<div id="confirmation_extra">' . $InvoiceFactory->mi_form . '</div>';
}
?>
