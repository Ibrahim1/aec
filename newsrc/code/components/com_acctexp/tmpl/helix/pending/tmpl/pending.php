<?
/**
 * @version $Id: pending.php
 * @package AEC - Account Control Expiration - Membership Manager
 * @subpackage Main Frontend
 * @copyright 2012 Copyright (C) David Deutsch
 * @author David Deutsch <skore@valanx.org> & Team AEC - http://www.valanx.org
 * @license GNU/GPL v.2 http://www.gnu.org/licenses/old-licenses/gpl-2.0.html or, at your option, any later version
 */

// Dont allow direct linking
( defined('_JEXEC') || defined( '_VALID_MOS' ) ) or die( 'Direct Access to this location is not allowed.' );

if ( $tmpl->cfg['customtext_pending_keeporiginal'] ) { ?>
	<div class="componentheading"><?= JText::_('PENDING_TITLE') ?></div>
	<p class="expired_dear"><?= sprintf( JText::_('DEAR'), $objUser->name ) . ',' ?></p>
	<p class="expired_date"><?= JText::_('WARN_PENDING') ?></p>
<? }

if ( $tmpl->cfg['customtext_pending'] ) { ?>
	<p><?= $tmpl->cfg['customtext_pending'] ?></p>
<? } ?>
<div id="box_pending">
	<? if ( strcmp($invoice, "none") === 0 ) { ?>
		<p><?= JText::_('PENDING_NOINVOICE') ?></p>
		<? $tmpl->tmpl( 'upgrade_button' ) ?>
	<? } elseif ( $invoice ) { ?>
		<p><? echo JText::_('PENDING_OPENINVOICE');
				$tmpl->tmpl( 'invoice_links' );
				echo ( !empty($reason) ? ' '.$reason : '' );
		 ?></p>
	<? } ?>
</div>
<div style="clear:both"></div>
