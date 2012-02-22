<?php
/**
 * @version $Id: access_denied.php
 * @package AEC - Account Control Expiration - Membership Manager
 * @subpackage Main Frontend
 * @copyright 2012 Copyright (C) David Deutsch
 * @author David Deutsch <skore@valanx.org> & Team AEC - http://www.valanx.org
 * @license GNU/GPL v.2 http://www.gnu.org/licenses/old-licenses/gpl-2.0.html or, at your option, any later version
 */

// Dont allow direct linking
( defined('_JEXEC') || defined( '_VALID_MOS' ) ) or die( 'Direct Access to this location is not allowed.' ); ?>

<?php if ( $tmpl->cfg['customtext_notallowed_keeporiginal'] ) { ?>
	<div class="componentheading"><?php echo JText::_('NOT_ALLOWED_HEADLINE'); ?></div>
	<p>
		<?php if ( $loggedin ) {
			echo JText::_('NOT_ALLOWED_FIRSTPAR_LOGGED'); ?>&nbsp;
			<a href="<?php echo $registerlink; ?>" title="<?php echo JText::_('NOT_ALLOWED_REGISTERLINK_LOGGED'); ?>"><?php echo JText::_('NOT_ALLOWED_REGISTERLINK_LOGGED'); ?></a>
			<?php
		} else {
			echo JText::_('NOT_ALLOWED_FIRSTPAR'); ?>&nbsp;
			<a href="<?php echo $registerlink; ?>" title="<?php echo JText::_('NOT_ALLOWED_REGISTERLINK'); ?>"><?php echo JText::_('NOT_ALLOWED_REGISTERLINK'); ?></a>
			<?php
		} ?>
	</p>
<?php }

$tmpl->custom( 'customtext_notallowed' );

if ( !empty( $processors ) && !empty( $tmpl->cfg['gwlist'] ) ) { ?>
	<p>&nbsp;</p>
	<p><?php echo JText::_('NOT_ALLOWED_SECONDPAR'); ?></p>
	<table id="cc_list">
		<?php foreach ( $processors as $processor ) {
			HTML_frontEnd::processorInfo( $option, $processor, $tmpl->cfg['displayccinfo'] );
		} ?>
	</table>
<?php }
