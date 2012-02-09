<?php
global $aecConfig;

if ($aecConfig->cfg['customtext_hold_keeporiginal'] ) {?>
	<div class="componentheading"><?php echo JText::_('HOLD_TITLE'); ?></div>
	<div id="expired_greeting">
		<p><?php echo sprintf( JText::_('DEAR'), $metaUser->cmsUser->name ); ?></p>
		<p><?php echo JText::_('HOLD_EXPLANATION'); ?></p>
	</div>
	<?php
}
if ( $aecConfig->cfg['customtext_hold'] ) { ?>
	<p><?php echo AECToolbox::rewriteEngine( $aecConfig->cfg['customtext_hold'], $metaUser ); ?></p>
	<?php
} ?>
