<?php
/**
 * @version $Id: processor_details.php
 * @package AEC - Account Control Expiration - Membership Manager
 * @subpackage Main Frontend
 * @copyright 2012 Copyright (C) David Deutsch
 * @author David Deutsch <skore@valanx.org> & Team AEC - http://www.valanx.org
 * @license GNU/GPL v.2 http://www.gnu.org/licenses/old-licenses/gpl-2.0.html or, at your option, any later version
 */

// Dont allow direct linking
( defined('_JEXEC') || defined( '_VALID_MOS' ) ) or die( 'Direct Access to this location is not allowed.' ) ?>
<tr>
	<td class="cc_gateway">
		<p align="center"><img src="<?php echo JURI::root(true) . '/media/' . $option . '/images/site/gwlogo_' . $processor->processor_name . '.png" alt="' . $processor->processor_name . '" title="' . $processorObj->processor_name ?>" /></p>
	</td>
	<td class="cc_icons">
		<p>
			<?php if ( isset( $processor->info['description'] ) ) {
				echo $processor->info['description'];
			} ?>
		</p>
	</td>
</tr>
<?php if ( $displaycc && !empty( $processor->info['cc_list'] ) ) { ?>
	<tr>
		<td class="cc_gateway"></td>
		<td class="cc_icons">
			<?php
			if ( isset( $processor->settings['cc_icons'] ) ) {
				$cc_list = $processor->settings['cc_icons'];
			} else {
				$cc_list = $processor->info['cc_icons'];
			}

			@include( $tmpl->tmpl( 'cc_icons' ) );
			?>
		</td>
	</tr>
	<?php
} ?>
<div class="aec_clearfix"></div>
