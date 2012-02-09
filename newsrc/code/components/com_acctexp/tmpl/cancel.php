<?php
global $aecConfig;

HTML_frontend::aec_styling( $option );

?>
<div class="componentheading"><?php echo JText::_('CANCEL_TITLE'); ?></div>
<?php
if ( $aecConfig->cfg['customtext_cancel'] ) { ?>
	<p><?php echo $aecConfig->cfg['customtext_cancel']; ?></p>
	<?php
}
if ( $aecConfig->cfg['customtext_cancel_keeporiginal'] ) { ?>
	<div id="cancel_page">
	<p><?php echo JText::_('CANCEL_MSG'); ?></p>
	</div>
	<?php
}
?><div class="aec_clearfix"></div><?php
?>
