<?php
/**
 * @version $Id: offline_payment.php
 * @package AEC - Account Control Expiration - Subscription component for Joomla! OS CMS
 * @subpackage Processors - Offline Payment
 * @copyright Copyright (C) 2007 David Deutsch
 * @author Davin Deutsch
 * @license GNU/GPL v.2 http://www.gnu.org/copyleft/gpl.html
 */
// Copyright (C) 2006-2007 David Deutsch
// All rights reserved.
// This source file is part of the Account Expiration Control Component, a  Joomla
// custom Component By Helder Garcia and David Deutsch - http://www.globalnerd.org
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

class processor_offline_payment extends processor
{
	function info()
	{
		$info = array();
		$info['longname']		= _CFG_OFFLINE_PAYMENT_LONGNAME;
		$info['statement']		= _CFG_OFFLINE_PAYMENT_STATEMENT;
		$info['description']	= _CFG_OFFLINE_PAYMENT_DESCRIPTION;
		$info['currencies']		= AECToolbox::_aecCurrencyField( true, true, true, true );
		$info['cc_list']		= "";
		$info['recurring']		= 0;

		return $info;
	}

	function settings()
	{
		$settings = array();
		$settings['info'] = '';
		$settings['waitingplan'] = 0;

		return $settings;
	}

	function backend_settings( $cfg )
	{
		$settings = array();
		$settings['waitingplan'] = array( 'list_plan' );
		$settings['info'] = array( 'editor' );

		return $settings;
	}

	function invoiceCreationAction( $cfg, $objInvoice )
	{
		if ( $cfg['waitingplan'] ) {
			global $database;

			$metaUser = new metaUser( $objInvoice->userid );

			if ( $metaUser->hasSubscription ) {
				$metaUser->objSubscription->applyUsage( $cfg['waitingplan'], 'none', 1 );

				$short	= 'waiting plan';
				$event	= 'Offline Payment waiting plan assigned for ' . $objInvoice->invoice_number;
				$tags	= 'processor,waitingplan';
				$params = array( 'invoice_number' => $objInvoice->invoice_number );

				$eventlog = new eventLog( $database );
				$eventlog->issue( $short, $tags, $event, $params );
			}
		}
	}

}

?>
