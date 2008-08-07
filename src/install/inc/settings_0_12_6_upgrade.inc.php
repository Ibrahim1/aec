<?php

$serialupdate	= false;
$newinstall 	= false;
$jsonconversion = false;

// Check whether the config is on 0.12.6 status
$query = 'SELECT `settings` FROM #__acctexp_config'
. ' WHERE `id` = \'1\''
;
$database->setQuery( $query );
$set = $database->loadResult();

if ( ( ( strpos( $set, '{' ) === 0 ) || ( strpos( $set, "\n" ) !== false ) ) && !empty( $set ) ) {
	if ( strpos( $set, '{' ) === 0 ) {
		$settings = jsoonHandler::decode( $set );
		$jsonconversion = true;
	} else {
		$settings = parameterHandler::decode( $set );
		$serialupdate = true;
	}

	if ( isset( $settings['milist'] ) ) {
		$temp = explode( ';', $settings['milist'] );
		$settings['milist'] = $temp;
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