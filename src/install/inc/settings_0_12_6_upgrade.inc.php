<?php
/**
 * @version $Id: settings_0_12_6_upgrade.inc.php
 * @package AEC - Account Control Expiration - Membership Manager
 * @subpackage Install Includes
 * @copyright 2006-2008 Copyright (C) David Deutsch
 * @author David Deutsch <skore@skore.de> & Team AEC - http://www.globalnerd.org
 * @license GNU/GPL v.2 http://www.gnu.org/licenses/old-licenses/gpl-2.0.html or, at your option, any later version
 */

$serialupdate	= false;
$newinstall 	= false;
$jsonconversion = false;

// Check whether the config is on 0.12.6 status
$query = 'SELECT `settings` FROM #__acctexp_config'
. ' WHERE `id` = \'1\''
;
$database->setQuery( $query );
$res = $database->loadResult();
$set = stripslashes( $res );

if ( ( ( strpos( $set, '{' ) === 0 ) || ( strpos( $set, "\n" ) !== false ) ) && !empty( $set ) ) {
	if ( strpos( $set, '{' ) === 0 ) {
		$settings = jsoonHandler::decode( $set );
		$jsonconversion = true;
	} else {
		$settings = parameterHandler::decode( $set );
		$serialupdate = true;

		if ( isset( $settings['milist'] ) ) {
			$temp = explode( ';', $settings['milist'] );
			$settings['milist'] = $temp;
		}

		if ( isset( $settings['gwlist'] ) ) {
			$temp = explode( ';', $settings['gwlist'] );
			$settings['gwlist'] = $temp;
		}
	}

	$entry = base64_encode( serialize( $settings ) );

	$query = 'UPDATE #__acctexp_config'
	. ' SET `settings` = \'' . $entry . '\''
	. ' WHERE `id` = \'1\''
	;
	$database->setQuery( $query );
	$database->query();
} elseif ( empty( $set ) ) {
	$newinstall = true;
}
?>