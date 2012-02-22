<table class="aec_items">
	<? foreach ( $list as $litem ) { ?>
		<tr>
			<td>
				<div class="aec_ilist_<?= $litem['type']?> aec_ilist_<?= $litem['type'] . '_' . $litem['id']?>">
					<? if ( $litem['type'] == 'group' ) {
						$tmpl->tmpl( 'groupbtn' );
					} else {
						$tmpl->tmpl( 'planbtn' );
					} ?>
				</div>
			</td>
		</tr>
	<?} ?>
</table>
