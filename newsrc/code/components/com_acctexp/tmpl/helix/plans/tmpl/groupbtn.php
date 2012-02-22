<h2><?= $litem['name']?></h2>
<p><?= $litem['desc']?></p>
<div class="aec_groupbutton">
	<?= Payment_HTML::planpageButton( $option, 'subscribe', '', JURI::root(true) . '/media/com_acctexp/images/site/select_button.png', array( array( 'group', $litem['id'] ) ), $userid, $passthrough ) ?>
</div>
