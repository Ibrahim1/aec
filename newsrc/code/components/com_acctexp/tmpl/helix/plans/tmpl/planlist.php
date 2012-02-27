<?php
/**
 * @version $Id: planlist.php
 * @package AEC - Account Control Expiration - Membership Manager
 * @subpackage Main Frontend
 * @copyright 2012 Copyright (C) David Deutsch
 * @author David Deutsch <skore@valanx.org> & Team AEC - http://www.valanx.org
 * @license GNU/GPL v.3 http://www.gnu.org/licenses/gpl.html or, at your option, any later version
 */

// Dont allow direct linking
( defined('_JEXEC') || defined( '_VALID_MOS' ) ) or die( 'Direct Access to this location is not allowed.' ) ?>
<table class="aec_items">
	<?php foreach ( $list as $litem ) { ?>
		<tr>
			<td>
				<div class="aec_ilist_<?php echo $litem['type'] ?> aec_ilist_<?php echo $litem['type'] . '_' . $litem['id'] ?>">
					<h2><?php echo $litem['name'] ?></h2>
					<p><?php echo $litem['desc'] ?></p>
					<?php if ( $litem['type'] == 'group' ) { ?>
						<div class="aec_groupbutton">
							<?php @include( $tmpl->tmpl( 'groupbtn' ) ); ?>
						</div>
					<?php } else { ?>
						<div class="aec_procbuttons">
							<?php foreach ( $litem['gw'] as $gwitem ) { ?>
								<?php @include( $tmpl->tmpl( 'planbtn' ) ); ?>
							<?php } ?>
						</div>
					<?php } ?>
				</div>
			</td>
		</tr>
	<?php } ?>
</table>
