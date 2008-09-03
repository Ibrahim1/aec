<?php
/**
 * @version $Id: mi_affiliatepro.php 16 2007-07-01 12:07:07Z mic $
 * @package AEC - Account Control Expiration - Membership Manager
 * @subpackage Micro Integrations - AffiliatePRO
 * @copyright 2006-2008 Copyright (C) David Deutsch
 * @author David Deutsch <skore@skore.de> & Team AEC - http://www.globalnerd.org
 * @author David Deutsch <skore@skore.de> & Team AEC - http://www.globalnerd.org
 * @license GNU/GPL v.2 http://www.gnu.org/licenses/old-licenses/gpl-2.0.html or, at your option, any later version
 */

// Dont allow direct linking
defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );

class mi_affiliatepro
{
	function Info()
	{
		$info = array();
		$info['name'] = _AEC_MI_NAME_AFFPRO;
		$info['desc'] = _AEC_MI_DESC_AFFPRO;

		return $info;
	}

	function Settings()
	{
		$settings = array();
		$settings['url']		= array( 'inputC' );

		return $settings;
	}

	function action( $request )
	{
		global $database, $mosConfig_live_site, $mosConfig_sitename;

		$text = '<script id="pap_x2s6df8d" src="' . $this->settings['url'] . '" type="text/javascript"></script>'
				. '<script type="text/javascript"><!--'
				. 'var TotalCost="' . $request->invoice->amount . ';'
				. 'var OrderID="' . $request->invoice->invoice_number . '";'
				. 'var ProductID="' . $request->plan->id . '";'
				. 'papSale();'
				. '--></script>';

		$displaypipeline = new displayPipeline( $database );
		$displaypipeline->create( $metaUser->userid, 1, 0, 0, null, 1, $text );

		return true;
	}
}
?>
