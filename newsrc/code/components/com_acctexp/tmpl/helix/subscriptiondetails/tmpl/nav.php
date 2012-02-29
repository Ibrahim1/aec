<?php
/**
 * @version $Id: invoices.php
 * @package AEC - Account Control Expiration - Membership Manager
 * @subpackage Main Frontend
 * @copyright 2012 Copyright (C) David Deutsch
 * @author David Deutsch <skore@valanx.org> & Team AEC - http://www.valanx.org
 * @license GNU/GPL v.3 http://www.gnu.org/licenses/gpl.html or, at your option, any later version
 */

// Dont allow direct linking
( defined('_JEXEC') || defined( '_VALID_MOS' ) ) or die( 'Direct Access to this location is not allowed.' ) ?>
<div id="aec_navlist_profile">
	<ul id="aec_navlist_profile">
	<?php
	foreach ( $tabs as $fieldlink => $fieldname ) {
		if ( $fieldlink == $sub ) {
			$id = ' id="current"';
		} else {
			$id = '';
		}
		echo '<li><a href="' . AECToolbox::deadsureURL( 'index.php?option=com_acctexp&task=subscriptiondetails&sub=' . $fieldlink, !empty( $tmpl->cfg['ssl_profile'] ) ) . '"'.$id.'>' . $fieldname . '</a></li>';
	}
	?>
	</ul>
</div>
