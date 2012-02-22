<?
// Double check that it's not the root group
if ( $list['group']['id'] > 1 ) { ?>
	<div class="aec_group_backlink">
		<?= Payment_HTML::planpageButton( $option, 'subscribe', '', JURI::root(true) . '/media/com_acctexp/images/site/back_button.png', array(), $userid, $passthrough, 'func_button' ) ?>
	</div>
	<h2><?= $list['group']['name'] ?></h2>
	<p><?= $list['group']['desc'] ?></p>
	<?
}
 ?>
