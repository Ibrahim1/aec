<?php
/**
 * @version $Id: mi_idevaffiliate.php 16 2007-07-02 13:29:29Z mic $
 * @package AEC - Account Control Expiration - Subscription component for Joomla! OS CMS
 * @subpackage Micro Integrations - iDevAffiliate
 * @copyright 2006/2007 Copyright (C) David Deutsch
 * @author David Deutsch <skore@skore.de> & Team AEC - http://www.globalnerd.org
 * @license GNU/GPL v.2 http://www.gnu.org/copyleft/gpl.html
 */

defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );

/*
 * <img border="0" src="http://www.YOURSITE.com/idevaffiliate/sale.php?profile=72198&idev_saleamt=XXX&idev_ordernum=YYY" width="1" height="1">
 *
 * XXX needs replaced with the variable that holds the sale amount.
 * YYY needs replaced with the variable that holds the order number.
 *
 * profile=72198&idev_saleamt=XXX&idev_ordernum=YYY&idev_option_1=AAA&idev_option_2=BBB&idev_option_3=CCC
 *
 * AAA, BBB and CCC can be any value from the cart you want.
 *
 * If you build it in to pass these 3 optional pieces of data, I just need to know what you're passing such as the customer name,
 * customer phone, etc.  The name of these custom fields are set in the admin center and I can pre-set those to whatever you want to send.
 */

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
		$settings['setupinfo'] = array( 'fieldset' );
		return $settings;
	}

	function action( $params, $userid, $plan )
	{
		global $database, $mosConfig_live_site;

		$lastinvoice = AECfetchfromDB::lastClearedInvoiceIDbyUserID( $userid, $plan->id );

		$invoice = new Invoice( $database );
		$invoice->load( $lastinvoice );

		$text = '<img border="0" '
				.'src="' . $mosConfig_live_site .'/components/com_idevaffiliate/sale.php?idev_paypal_1=' . $invoice->amount . '&amp;idev_paypal_2=' . $invoice->invoice_number . '" '
				.'width="1" height="1" />';

		$displaypipeline = new displayPipeline($database);
		$displaypipeline->create( $userid, 1, 0, 0, null, 1, $text );

		return true;
	}

}
?>