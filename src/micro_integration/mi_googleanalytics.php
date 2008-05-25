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

	function Settings()
	{
		$settings = array();
		$settings['account_id']		= array( 'inputB' );
		return $settings;
	}

	function action( $request )
	{
		global $database, $mosConfig_live_site, $mosConfig_sitename;

		$text = '<script src="http://www.google-analytics.com/urchin.js" type="text/javascript">'
				. '</script>'
				. '<script type="text/javascript">'
				. '  _uacct="' . $this->settings['account_id'] . '";'
				. '  urchinTracker();'
				. '</script>'
				. '<form style="display:none;" name="utmform">'
				. '<textarea id="utmtrans">UTM:T|' . $request->invoice->invoice_number . '|' . $mosConfig_sitename . '|' . $request->invoice->amount . '|0.00|0.00|||'
				. 'UTM:I|' . $request->invoice->invoice_number . '|' . $request->plan->id . '|' . $request->plan->name . '|subscription|' . $request->invoice->amount . '|1 </textarea>'
				. '</form>'
				. '<script type="text/javascript">'
				. '__utmSetTrans();'
				. '</script>';

		$displaypipeline = new displayPipeline($database);
		$displaypipeline->create( $request->metaUser->userid, 1, 0, 0, null, 1, $text );

		return true;
	}
}
?>