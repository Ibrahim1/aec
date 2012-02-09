<?php
global $aecConfig;

if ( !is_object( $this ) ) {
	HTML_frontEnd::aec_styling();
} ?>
<?php if ( $aecConfig->cfg['customtext_notallowed_keeporiginal'] ) { ?>
	<div class="componentheading"><?php echo JText::_('NOT_ALLOWED_HEADLINE'); ?></div>
	<p>
		<?php
		if ( $loggedin ) {
			echo JText::_('NOT_ALLOWED_FIRSTPAR_LOGGED'); ?>&nbsp;
			<a href="<?php echo $registerlink; ?>" title="<?php echo JText::_('NOT_ALLOWED_REGISTERLINK_LOGGED'); ?>"><?php echo JText::_('NOT_ALLOWED_REGISTERLINK_LOGGED'); ?></a>
			<?php
		} else {
			echo JText::_('NOT_ALLOWED_FIRSTPAR'); ?>&nbsp;
			<a href="<?php echo $registerlink; ?>" title="<?php echo JText::_('NOT_ALLOWED_REGISTERLINK'); ?>"><?php echo JText::_('NOT_ALLOWED_REGISTERLINK'); ?></a>
			<?php
		} ?>
	</p>
	<?php
}
if ( $aecConfig->cfg['customtext_notallowed'] ) { ?>
	<?php echo $aecConfig->cfg['customtext_notallowed']; ?>
<?php } ?>
<?php
if ( !empty( $processors ) && !empty( $aecConfig->cfg['gwlist'] ) ) { ?>
	<p>&nbsp;</p>
	<p><?php echo JText::_('NOT_ALLOWED_SECONDPAR'); ?></p>
	<table id="cc_list">
		<?php
		foreach ( $processors as $processor ) {
			HTML_frontEnd::processorInfo( $option, $processor, $aecConfig->cfg['displayccinfo'] );
		} ?>
	</table>
	<?php
}
?><div class="aec_clearfix"></div><?php
?>
