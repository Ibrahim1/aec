<?php
if ( empty( $tmpl->cfg['gwlist'] ) ) {
	return;
}

if ( !in_array( $processorObj->processor_name, $tmpl->cfg['gwlist'] ) ) {
	return;
}

?>
<tr>
	<td class="cc_gateway">
		<p align="center"><img src="<?php echo JURI::root(true) . '/media/' . $option . '/images/site/gwlogo_' . $processorObj->processor_name . '.png" alt="' . $processorObj->processor_name . '" title="' . $processorObj->processor_name; ?>" /></p>
	</td>
	<td class="cc_icons">
		<p>
			<?php
			if ( isset( $processorObj->info['description'] ) ) {
				echo $processorObj->info['description'];
			} ?>
		</p>
	</td>
</tr>
<?php
if ( $displaycc && !empty( $processorObj->info['cc_list'] ) ) { ?>
	<tr>
		<td class="cc_gateway"></td>
		<td class="cc_icons">
			<?php
			if ( isset( $processorObj->settings['cc_icons'] ) ) {
				echo Payment_HTML::getCCiconsHTML ( $option, $processorObj->settings['cc_icons'] );
			} else {
				echo Payment_HTML::getCCiconsHTML ( $option, $processorObj->info['cc_list'] );
			}
			?>
		</td>
	</tr>
	<?php
}
?><div class="aec_clearfix"></div><?php
?>
