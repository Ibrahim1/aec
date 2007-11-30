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
		$settings['profile'] = array( 'inputC' );
		$settings['rooturl'] = array( 'inputC' );
		$settings['calcrooturl'] = array( 'fieldset', '',  );
		return $settings;
	}

	function action( $params, $userid, $plan )
	{
		global $database, $mosConfig_live_site;

		$lastinvoice = AECfetchfromDB::lastClearedInvoiceIDbyUserID( $userid, $plan->id );

		$invoice = new Invoice( $database );
		$invoice->load( $lastinvoice );

		$rooturl = $this->getPath( $params );

		$text = '<img border="0" '
				.'src="' . $rooturl .'/sale.php?profile=' . $params['profile'] . '&amp;idev_saleamt=' . $invoice->amount . '&amp;idev_ordernum=' . $invoice->invoice_number . '" '
				.'width="1" height="1" />';

		$displaypipeline = new displayPipeline($database);
		$displaypipeline->create( $userid, 1, 0, 0, null, 1, $text );

		return true;
	}

	function getPath( $params )
	{
		global $mosConfig_live_site;

		if ( !empty( $params['rooturl'] ) ) {
			if ( ( strpos( $params['rooturl'], 'http://' ) === 0 ) || ( strpos( $params['rooturl'], 'https://' ) === 0 ) ) {
				$rooturl = $params['rooturl'];
			} else {
				$rooturl = $mosConfig_live_site . $params['rooturl'];
			}
		} else {
			$rooturl = $mosConfig_live_site . '/idevaffiliate';
		}
	}
}
?>