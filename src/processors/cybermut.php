<?php
/**
 * @version $Id: cybermut.php
 * @package AEC - Account Expiration Control / Subscription management for Joomla
 * @subpackage Payment Processors
 * @author David Deutsch <skore@skore.de>
 * @copyright 2004-2007 David Deutsch
 * @license http://www.gnu.org/copyleft/gpl.html. GNU Public License
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

class processor_cybermut
{
	function info()
	{
		$info = array();
		$info['longname'] = "Cybermut";
		$info['statement'] = "Make payments with Cybermut";
		$info['description'] = "description";
		$info['currencies'] = "EUR";
		$info['languages'] = "AU,DE,FR,IT,GB,ES,US";
		$info['cc_list'] = "visa,mastercard,discover,americanexpress,echeck,giropay";

		return $info;
	}

	function settings()
	{
		$settings = array();
		$settings['variable'] = "default value";

		return $settings;
	}

	function backend_settings()
	{
		$settings = array();
		$settings['variable'] = array("type", "name", "description");

		return $settings;
	}

	function createGatewayLink( $int_var, $metaUser, $cfg, $new_subscription )
	{
		global $mosConfig_live_site;

		$var['post_url']	= "https://www.sandbox.paypal.com/cgi-bin/webscr";

		return $var;
	}

	function parseNotification( $post, $cfg )
	{
		$response = array();
		$response['invoice'] = "";
		$response['valid'] = 0;

		return $response;
	}

}

?>
