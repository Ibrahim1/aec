<?
/**
 * @version $Id: processor_list.php
 * @package AEC - Account Control Expiration - Membership Manager
 * @subpackage Main Frontend
 * @copyright 2012 Copyright (C) David Deutsch
 * @author David Deutsch <skore@valanx.org> & Team AEC - http://www.valanx.org
 * @license GNU/GPL v.2 http://www.gnu.org/licenses/old-licenses/gpl-2.0.html or, at your option, any later version
 */

// Dont allow direct linking
( defined('_JEXEC') || defined( '_VALID_MOS' ) ) or die( 'Direct Access to this location is not allowed.' ) ?>
<?
$gwnames = PaymentProcessorHandler::getInstalledNameList( true );

if ( count( $gwnames ) && $gwnames[0] && !empty($tmpl->cfg['gwlist']) ) {
	$processors = array();
	foreach ( $gwnames as $procname ) {
		if ( !in_array( $procname, $tmpl->cfg['gwlist'] ) ) {
			continue;
		}

		$processor = trim( $procname );
		$processors[$processor] = new PaymentProcessor();
		if ( $processors[$processor]->loadName( $processor ) ) {
			$processors[$processor]->init();
			$processors[$processor]->getInfo();
			$processors[$processor]->getSettings();
		} else {
			unset( $processors[$processor] );
		}
	}
} else {
	$processors = false;
}

if ( !empty( $processors ) && !empty( $tmpl->cfg['gwlist'] ) ) { ?>
	<p>&nbsp;</p>
	<p><?php echo JText::_('NOT_ALLOWED_SECONDPAR') ?></p>
	<table id="cc_list">
		<?php foreach ( $processors as $processor ) {
			@include( $tmpl->tmpl( 'processor_details' ) );
		} ?>
	</table>
<?php }

 ?>
