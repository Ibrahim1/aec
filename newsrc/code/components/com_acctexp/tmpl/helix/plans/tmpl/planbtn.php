<h2><?php echo $litem['name']; ?></h2>
<p><?php echo $litem['desc']; ?></p>
<div class="aec_groupbutton">
	<?php
	$urlbutton = JURI::root(true) . '/media/com_acctexp/images/site/select_button.png';
	$hidden = array( array( 'group', $litem['id'] ) );
	echo Payment_HTML::planpageButton( $option, 'subscribe', '', $urlbutton, $hidden, $userid, $passthrough );
	?>
</div>
