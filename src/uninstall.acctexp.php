<?php
/**
* AcctExp Uninstallation
* @package AEC - Account Control Expiration - Membership Manager
* @copyright 2006-2008 Copyright (C) David Deutsch
* @license GNU/GPL v.2 http://www.gnu.org/licenses/old-licenses/gpl-2.0.html or, at your option, any later version
* @version $Revision: 1.2 $
* @author David Deutsch <skore@skore.de> & Team AEC - http://www.globalnerd.org 
**/
//
// Copyright (C) 2004-2007 Helder Garcia, David Deutsch
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
//
// Dont allow direct linking
defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );

global $mainframe;

require_once( $mainframe->getPath( 'class', "com_acctexp" ) );

function com_uninstall()
{
	global $database, $my;

	$short = "AEC uninstall";
	$event = "AEC has been removed";
	$tags = "uninstall,system";

	$eventlog = new eventLog($database);
	$params = array("userid" => $my->id);
	$eventlog->issue( $short, $tags, $event, 2, $params );

	echo "Component successfully uninstalled";
}



?>
