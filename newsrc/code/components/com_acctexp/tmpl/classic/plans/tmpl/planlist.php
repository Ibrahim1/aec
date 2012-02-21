<table class="aec_items">
	<?php foreach ( $list as $litem ) { ?>
		<tr>
			<td>
				<div class="aec_ilist_<?php echo $litem['type']; ?> aec_ilist_<?php echo $litem['type'] . '_' . $litem['id']; ?>">
					<?php if ( $litem['type'] == 'group' ) {
						$tmpl->tmpl( 'groupbtn' );
					} else {
						$tmpl->tmpl( 'planbtn' );
					} ?>
				</div>
			</td>
		</tr>
	<?php} ?>
</table>
