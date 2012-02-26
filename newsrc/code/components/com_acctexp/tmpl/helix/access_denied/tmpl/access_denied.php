<?
/**
 * @version $Id: access_denied.php
 * @package AEC - Account Control Expiration - Membership Manager
 * @subpackage Main Frontend
 * @copyright 2012 Copyright (C) David Deutsch
 * @author David Deutsch <skore@valanx.org> & Team AEC - http://www.valanx.org
 * @license GNU/GPL v.3 http://www.gnu.org/licenses/gpl.html or, at your option, any later version
 */

// Dont allow direct linking
( defined('_JEXEC') || defined( '_VALID_MOS' ) ) or die( 'Direct Access to this location is not allowed.' ) ?>

<?php if ( $tmpl->cfg['customtext_notallowed_keeporiginal'] ) { ?>
	<div class="componentheading"><?php echo JText::_('NOT_ALLOWED_HEADLINE') ?></div>
	<p>
		<?php if ( $loggedin ) {
			echo JText::_('NOT_ALLOWED_FIRSTPAR_LOGGED')
				. '&nsbp;'
				. $tmpl->lnk( $registerlink, JText::_('NOT_ALLOWED_REGISTERLINK_LOGGED') );
		} else {
			echo JText::_('NOT_ALLOWED_FIRSTPAR')
				. '&nsbp;'
				. $tmpl->lnk( $registerlink, JText::_('NOT_ALLOWED_REGISTERLINK') );
		} ?>
	</p>
<?php }

$tmpl->custom( 'customtext_notallowed' );

@include( $tmpl->tmpl( 'plans.processor_list' ) );
