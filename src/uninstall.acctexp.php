<?php
/**
* AcctExp Uninstallation
* @package AEC - Account Control Expiration - Membership Manager
* @copyright 2006-2008 Copyright (C) David Deutsch
* @license GNU/GPL v.2 http://www.gnu.org/licenses/old-licenses/gpl-2.0.html or, at your option, any later version
* @version $Revision: 1.2 $
* @author David Deutsch <skore@skore.de> & Team AEC - http://www.globalnerd.org
**/

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
