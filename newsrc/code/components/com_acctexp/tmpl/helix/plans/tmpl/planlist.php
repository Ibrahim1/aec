<?
/**
 * @version $Id: planlist.php
 * @package AEC - Account Control Expiration - Membership Manager
 * @subpackage Main Frontend
 * @copyright 2012 Copyright (C) David Deutsch
 * @author David Deutsch <skore@valanx.org> & Team AEC - http://www.valanx.org
 * @license GNU/GPL v.2 http://www.gnu.org/licenses/old-licenses/gpl-2.0.html or, at your option, any later version
 */

// Dont allow direct linking
( defined('_JEXEC') || defined( '_VALID_MOS' ) ) or die( 'Direct Access to this location is not allowed.' ) ?>
<table class="aec_items">
	<?php foreach ( $list as $litem ) { ?>
		<tr>
			<td>
				<div class="aec_ilist_<?php echo $litem['type'] ?> aec_ilist_<?php echo $litem['type'] . '_' . $litem['id'] ?>">
					<?php if ( $litem['type'] == 'group' ) {
						@include( $tmpl->tmpl( 'groupbtn' ) );
					} else {
						@include( $tmpl->tmpl( 'planbtn' ) );
					} ?>
				</div>
			</td>
		</tr>
	<?} ?>
</table>
