<?php
/**
 * @version $Id: list.php
 * @package AEC - Account Control Expiration - Membership Manager
 * @subpackage Main Frontend
 * @copyright 2012 Copyright (C) David Deutsch
 * @author David Deutsch <skore@valanx.org> & Team AEC - http://www.valanx.org
 * @license GNU/GPL v.3 http://www.gnu.org/licenses/gpl.html or, at your option, any later version
 */

// Dont allow direct linking
( defined('_JEXEC') || defined( '_VALID_MOS' ) ) or die( 'Direct Access to this location is not allowed.' ) ?>
<div id="exception-list">
	<?php foreach ( $InvoiceFactory->exceptions as $eid => $ex ) { ?>
		<div class="exception">
			<h5><?php echo $ex['head'] ?></h5>
			<p><?php echo $ex['desc'] ?></p>
			<div class="form-exception">
				<?php foreach ( $ex['rows'] as $rid => $row ) { ?>
					<div class="form-exception-part">
						<?php echo $aecHTML->createFormParticle( $eid.'_'.$rid ) ?>
					</div>
				<?php } ?>
			</div>
		</div>
	<?php } ?>
</div>
