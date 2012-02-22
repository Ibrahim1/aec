<?
/**
 * @version $Id: plans.php
 * @package AEC - Account Control Expiration - Membership Manager
 * @subpackage Main Frontend
 * @copyright 2012 Copyright (C) David Deutsch
 * @author David Deutsch <skore@valanx.org> & Team AEC - http://www.valanx.org
 * @license GNU/GPL v.2 http://www.gnu.org/licenses/old-licenses/gpl-2.0.html or, at your option, any later version
 */

// Dont allow direct linking
( defined('_JEXEC') || defined( '_VALID_MOS' ) ) or die( 'Direct Access to this location is not allowed.' ) ?>

<div class="componentheading"><?= JText::_('PAYPLANS_HEADER') ?></div>

<? if ( !empty( $cart ) ) { $tmpl->tmpl( 'backtocart' ); } ?>

<div class="subscriptions">
	<?
	$tmpl->custom( 'customtext_plans' );

	if ( isset( $list['group'] ) && $selected ) {
		$tmpl->tmpl( 'groupheader' );
		unset( $list['group'] );
	}
	
	$tmpl->tmpl( 'planlist' );
	 ?>
</div>
<div class="aec_clearfix"></div>