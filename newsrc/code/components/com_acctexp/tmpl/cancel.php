<?php
HTML_frontend::aec_styling( $option );

?>
<div class="componentheading"><?php echo JText::_('CANCEL_TITLE'); ?></div>
<?php
if ( $cfg->cfg['customtext_cancel'] ) { ?>
	<p><?php echo $cfg->cfg['customtext_cancel']; ?></p>
	<?php
}
if ( $cfg->cfg['customtext_cancel_keeporiginal'] ) { ?>
	<div id="cancel_page">
	<p><?php echo JText::_('CANCEL_MSG'); ?></p>
	</div>
	<?php
}
