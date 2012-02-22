<tr>
	<td class="cc_gateway">
		<p align="center"><img src="<?= JURI::root(true) . '/media/' . $option . '/images/site/gwlogo_' . $processor->processor_name . '.png" alt="' . $processor->processor_name . '" title="' . $processorObj->processor_name?>" /></p>
	</td>
	<td class="cc_icons">
		<p>
			<? if ( isset( $processor->info['description'] ) ) {
				echo $processor->info['description'];
			} ?>
		</p>
	</td>
</tr>
<? if ( $displaycc && !empty( $processor->info['cc_list'] ) ) { ?>
	<tr>
		<td class="cc_gateway"></td>
		<td class="cc_icons">
			<?
			if ( isset( $processor->settings['cc_icons'] ) ) {
				$cc_list = $processor->settings['cc_icons'];
			} else {
				$cc_list = $processor->info['cc_icons'];
			}

			$tmpl->tmpl( 'cc_icons' );
			?>
		</td>
	</tr>
	<?
} ?>
<div class="aec_clearfix"></div>
