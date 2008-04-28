<?php
/**
 * @version $Id: mi_affiliatepro.php 16 2007-07-01 12:07:07Z mic $
 * @package AEC - Account Control Expiration - Subscription component for Joomla! OS CMS
 * @subpackage Micro Integrations - AffiliatePRO
 * @copyright 2007 Calum Polwart / Shiny Black Shoe Systems
 * @author Calum Polwart
 * @author adopted by David Deutsch <skore@skore.de> & Team AEC - http://www.globalnerd.org
 * @license GNU/GPL v.2 http://www.gnu.org/copyleft/gpl.html
 */

/**
 * mic: TODO: optimize DB.Calls
 */

// Copyright (C) 2007 Calum Polwart / Shiny Black Shoe Systems
// Adapted from code from David Deutsch used for idevAffiliate and code from
// AffiliatePro Website
// This code can be referred to as (c) David Deutsch if supplied as part of AEC in future
//
// All rights reserved.
// This source file is to function as part of the Account Expiration Control Component, a
// Joomla custom Component By Helder Garcia and David Deutsch - http://www.globalnerd.org
//
// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License (GPL)
// as published by the Free Software Foundation; either version 2
// of the License, or (at your option) any later version.
//
// Please note that the GPL states that any headers in files and
// Copyright notices as well as credits in headers, source files
// and output (screens, prints, etc.) can not be removed.
// You can extend them with your own credits, though...
//
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with this program; if not, write to the Free Software
// Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
//
// The "GNU General Public License" (GPL) is available at
// http://www.gnu.org/copyleft/gpl.html.

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

	function Settings( $params )
	{
		$settings = array();
		$settings['url']		= array( 'inputC' );

		return $settings;
	}

	function action( $params, $metaUser, $plan, $invoice )
	{
		global $database, $mosConfig_live_site, $mosConfig_sitename;

		$lastinvoice = AECfetchfromDB::lastClearedInvoiceIDbyUserID( $metaUser->userid, $plan->id );

		$invoice = new Invoice($database);
		$invoice->load($lastinvoice);

		$text = '<script id="pap_x2s6df8d" src="' . $params['url'] . '" type="text/javascript"></script>'
				. '<script type="text/javascript"><!--'
				. 'var TotalCost="' . $invoice->amount . ';'
				. 'var OrderID="' . $invoice->invoice_number . '";'
				. 'var ProductID="' . $plan->id . '";'
				. 'papSale();'
				. '--></script>';

		$displaypipeline = new displayPipeline($database);
		$displaypipeline->create( $metaUser->userid, 1, 0, 0, null, 1, $text );

		return true;
	}
}
?>