<?php
/**
 * @version $Id: mi_idevaffiliate.php 16 2007-07-02 13:29:29Z mic $
 * @package AEC - Account Control Expiration - Subscription component for Joomla! OS CMS
 * @subpackage Micro Integrations - Email
 * @copyright 2006/2007 Copyright (C) David Deutsch
 * @author David Deutsch <skore@skore.de> & Team AEC - http://www.gobalnerd.org
 * @license GNU/GPL v.2 http://www.gnu.org/copyleft/gpl.html
 */

/**
 * TODO (mic): check variable variables!
 */

defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );

class mi_idevaffiliate
{
	function Info()
	{
		$info = array();
		$info['name'] = _AEC_MI_NAME_IDEV;
		$info['desc'] = _AEC_MI_DESC_IDEV;

		return $info;
	}

	function checkInstallation()
	{
		return true;
	}

	function install()
	{
		return true;
	}

	function Settings( $params )
	{
		$settings = array();
		$settings['mi_order_id']		= array( 'inputA' );
		$settings['mi_order_subtotal']	= array( 'inputB' );
		return $settings;
	}

	function action( $params, $userid, $plan )
	{
		global $database;

		$query = 'SELECT idev_vartotal, idev_order'
		. ' FROM #__idevaff_integration'
		;
		$database->setQuery( $query );
		$idevconfig = $database->loadRow();

		$varname		= $idevconfig[0];
		$idev_tracking	= $idevconfig[1];

		if ( strpos( $params['mi_order_id'], '[invoice]' ) ) {
			$query = 'SELECT invoice_number'
			. ' FROM #__acctexp_invoices'
			. ' WHERE userid = \'' . $userid . '\''
			. ' ORDER BY transaction_date DESC'
			;
			$database->setQuery( $query );
			$idev_tracking = $database->loadResult(); // $$idev_tracking
		} elseif ( strpos( $params['mi_order_id'], '[planid]' ) ) {
			$metaUser = new metaUser( $userid );
			$idev_tracking = $metaUser->objSubscription->plan;
		} elseif ( strpos( $params['mi_order_id'], '[userid]' ) ) {
			$metaUser = new metaUser( $userid );
			$idev_tracking = $metaUser->cmsUser->id;
		} else {
			$idev_tracking = $params['mi_order_id'];
		}

		if ( strpos( $params['mi_order_subtotal'], '[invoice]' ) ) {
			$query = 'SELECT amount'
			. ' FROM #__acctexp_invoices'
			. ' WHERE userid = \'' . $userid . '\''
			. ' ORDER BY transaction_date DESC'
			;
			$database->setQuery( $query );
			$varname = $database->loadResult(); // $$varname
		} else {
			$varname = number_format( $params['mi_order_subtotal'], 2, '.', '' );
		}

		include( $GLOBALS['mosConfig_absolute_path'] . '/components/com_idevaffiliate/sale.php' );

		$fr = fopen( $GLOBALS['mosConfig_absolute_path'] . '/jstmp.txt', 'a+' );
		fputs( $fr, _AEC_MI_DIV1_IDEV . '\n' );
		foreach ( $params as $key=>$value ) {
			fputs( $fr, _AEC_MI_DIV2_IDEV . ' ' . $key . ' ' . _AEC_MI_DIV3_IDEV . ' ' . $value . '\n' );
		}
		fclose( $fr );

		return true;
	}
}
?>