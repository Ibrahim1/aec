<?php
/**
 * @version $Id: mi_googleanalytics.php
 * @package AEC - Account Control Expiration - Membership Manager
 * @subpackage Micro Integrations - Google Analytics
 * @copyright 2006-2008 Copyright (C) David Deutsch
 * @author David Deutsch <skore@skore.de> & Team AEC - http://www.valanx.org
 * @license GNU/GPL v.2 http://www.gnu.org/licenses/old-licenses/gpl-2.0.html or, at your option, any later version
 */

// Dont allow direct linking
( defined('_JEXEC') || defined( '_VALID_MOS' ) ) or die( 'Direct Access to this location is not allowed.' );

class mi_googleanalytics
{
	function Info()
	{
		$info = array();
		$info['name'] = _AEC_MI_NAME_GOOGLEANALYTICS;
		$info['desc'] = _AEC_MI_DESC_GOOGLEANALYTICS;

		return $info;
	}

	function Settings()
	{
		$settings = array();
		$settings['account_id']		= array( 'inputB' );
		return $settings;
	}

	function action( $request )
	{
		$database = &JFactory::getDBO();

		global $mainframe;
				
		$text = '<script type="text/javascript">'
				. 'var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");'
				. 'document.write(unescape("%3Cscript src=\'" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript\'%3E%3C/script%3E"));'
				. '</script>'
				. '<script type="text/javascript">'
				. 'try {'
				. 'var pageTracker = _gat._getTracker("' . $this->settings['account_id'] . '");'
				. 'pageTracker._addTrans('
				. '"' . $request->invoice->invoice_number . '",	// Order ID'
				. '"' . $mainframe->getCfg( 'sitename' ) . '",	// Affiliation'
				. '"' . $request->invoice->amount . '",			// Total'
				. '"",				// Tax'
				. '"",				// Shipping'
				. '"",				// City'
				. '"",				// State'
				. '""				// Country'
				. ');'
				. 'pageTracker._addItem('
				. '"' . $request->invoice->invoice_number . '",	// Order ID'
				. '"' . $request->plan->id . '",				// SKU'
				. '"' . $request->plan->name . '",				// Product Name' 
				. '"' . $request->invoice->amount . '",			// Price'
				. '"1"		// Quantity'
				. ');'
				. 'pageTracker._trackTrans();'
				. '} catch(err) {}</script>'
				;

		$displaypipeline = new displayPipeline($database);
		$displaypipeline->create( $request->metaUser->userid, 1, 0, 0, null, 1, $text );

		return true;
	}
}
?>
