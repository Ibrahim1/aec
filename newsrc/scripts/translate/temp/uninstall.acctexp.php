<?php
/**
* AcctExp Uninstallation
* @package AEC - Account Control Expiration - Membership Manager
* @copyright 2006-2010 Copyright (C) David Deutsch
* @license GNU/GPL v.2 http://www.gnu.org/licenses/old-licenses/gpl-2.0.html or, at your option, any later version
* @version $Revision: 1.2 $
* @author David Deutsch <skore@skore.de> & Team AEC - http://www.valanx.org
**/

// Dont allow direct linking
//( defined('_JEXEC') || defined( '_VALID_MOS' ) ) or die( 'Direct Access to this location is not allowed.' );

require_once( JApplicationHelper::getPath( 'class', "com_acctexp" ) );

function delTree($path)
{
	if (is_dir($path) === true) {
		$files = array_diff(scandir($path), array('.', '..'));

		foreach ($files as $file) {
			delTree(realpath($path) . '/' . $file);
		}

		return rmdir($path);
	} else if (is_file($path) === true) {
			return unlink($path);
	}

	return false;
}


function com_uninstall()
{
	global $aecConfig;

	$db = &JFactory::getDBO();

	$user = &JFactory::getUser();

	if ( $aecConfig->cfg['delete_tables'] && $aecConfig->cfg['delete_tables_sure'] ) {
		$app = JFactory::getApplication();

		$tables		= $db->getTableList();

		$tfound = false;
		foreach ( $tables as $tname ) {
			if ( strpos( $tname, $app->getCfg( 'dbprefix' ) . 'acctexp_' ) === 0 ) {
				$db->setQuery( "DROP TABLE IF EXISTS $tname" );
				$db->query();

				$tfound = true;
			}
		}

		if ( $tfound ) {
			echo "Component successfully uninstalled, all tables have been deleted";
		} else {
			echo "Component successfully uninstalled. The component tables are still in the database and will be preserved for the next install or upgrade of the component.";
		}
	} else {
		$user = &JFactory::getUser();

		$short = "AEC uninstall";
		$event = "AEC has been removed";
		$tags = "uninstall,system";

		$eventlog = new eventLog($db);
		$params = array("userid" => $user->id);
		$eventlog->issue( $short, $tags, $event, 2, $params );
print_r($eventlog);
		echo "Component successfully uninstalled. The component tables are still in the database and will be preserved for the next install or upgrade of the component.";
	}

	return true;
}

?>
