<?php
/**
 * @version $Id: hold.php
 * @package AEC - Account Control Expiration - Membership Manager
 * @subpackage Main Frontend
 * @copyright 2012 Copyright (C) David Deutsch
 * @author David Deutsch <skore@valanx.org> & Team AEC - http://www.valanx.org
 * @license GNU/GPL v.2 http://www.gnu.org/licenses/old-licenses/gpl-2.0.html or, at your option, any later version
 */

// Dont allow direct linking
( defined('_JEXEC') || defined( '_VALID_MOS' ) ) or die( 'Direct Access to this location is not allowed.' ); ?>

<?php if ($tmpl->cfg['customtext_hold_keeporiginal'] ) { ?>
	<div class="componentheading"><?php echo JText::_('HOLD_TITLE'); ?></div>
	<div id="expired_greeting">
		<p><?php echo sprintf( JText::_('DEAR'), $metaUser->cmsUser->name ); ?></p>
		<p><?php echo JText::_('HOLD_EXPLANATION'); ?></p>
	</div>
	<?php
}
if ( $tmpl->cfg['customtext_hold'] ) { ?>
	<p><?php echo $tmpl->rw( $tmpl->cfg['customtext_hold'] ); ?></p>
<?php } ?>
