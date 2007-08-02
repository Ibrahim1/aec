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

	function Settings( $params )
	{
		$settings = array();
		return $settings;
	}

	function action( $params, $userid, $plan )
	{
		global $database, $mosConfig_live_site;

		$lastinvoice = AECfetchfromDB::lastClearedInvoiceIDbyUserID( $userid, $plan->id );
print_r($database);
		$invoice = new Invoice( $database );
		$invoice->load( $lastinvoice );
print_r($lastinvoice);print_r($invoice);exit();
		$text = '<img border="0" '
				.'src="' . $mosConfig_live_site .'/components/com_idevaffiliate/sale.php?idev_paypal_1=' . $invoice->amount . '&idev_paypal_2=' . $invoice->invoice_number . '" '
				.'width="1" height="1">';

		$displaypipeline = new displayPipeline($database);
		$displaypipeline->create( $userid, 1, 0, 0, null, 1, $text );

		return true;
	}
}
?>