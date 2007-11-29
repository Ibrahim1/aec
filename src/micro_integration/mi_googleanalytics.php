<?php
/**
 * @version $Id: mi_idevaffiliate.php 16 2007-07-02 13:29:29Z mic $
 * @package AEC - Account Control Expiration - Subscription component for Joomla! OS CMS
 * @subpackage Micro Integrations - Email
 * @copyright 2006/2007 Copyright (C) David Deutsch
 * @author David Deutsch <skore@skore.de> & Team AEC - http://www.globalnerd.org
 * @license GNU/GPL v.2 http://www.gnu.org/copyleft/gpl.html
 */

/**
 * TODO (mic): check variable variables!
 */

defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );

class mi_googleanalytics
{
	function Info()
	{
		$info = array();
		$info['name'] = _AEC_MI_NAME_GOOGLEANALYTICS;
		$info['desc'] = _AEC_MI_DESC_GOOGLEANALYTICS;

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
		$settings['account_id']		= array( 'inputB' );
		return $settings;
	}

	function action( $params, $userid, $plan )
	{
		global $database, $mosConfig_live_site, $mosConfig_sitename;

		$lastinvoice = AECfetchfromDB::lastClearedInvoiceIDbyUserID( $userid, $plan->id );

		$invoice = new Invoice($database);
		$invoice->load($lastinvoice);

		$text = '<script src="http://www.google-analytics.com/urchin.js" type="text/javascript">'
				. '</script>'
				. '<script type="text/javascript">'
				. '  _uacct="' . $params['account_id'] . '";'
				. '  urchinTracker();'
				. '</script>'
				. '<form style="display:none;" name="utmform">'
				. '<textarea id="utmtrans">UTM:T|' . $invoice->invoice_number . '|' . $mosConfig_sitename . '|' . $invoice->amount . '|0.00|0.00|||'
				. 'UTM:I|' . $invoice->invoice_number . '|' . $plan->id . '|' . $plan->name . '|subscription|' . $invoice->amount . '|1 </textarea>'
				. '</form>'
				. '<script type="text/javascript">'
				. '__utmSetTrans();'
				. '</script>';

		$displaypipeline = new displayPipeline($database);
		$displaypipeline->create( $userid, 1, 0, 0, null, 1, $text );

		return true;
	}
}
?>