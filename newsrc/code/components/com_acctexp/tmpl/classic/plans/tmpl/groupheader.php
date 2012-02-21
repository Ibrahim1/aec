<?php
// Double check that it's not the root group
if ( $list['group']['id'] > 1 ) { ?>
	<div class="aec_group_backlink">
		<?php
		$urlbutton = JURI::root(true) . '/media/com_acctexp/images/site/back_button.png';
		echo Payment_HTML::planpageButton( $option, 'subscribe', '', $urlbutton, array(), $userid, $passthrough, 'func_button' );
		?>
	</div>
	<h2><?php echo $list['group']['name']; ?></h2>
	<p><?php echo $list['group']['desc']; ?></p>
	<?php
}
?>
