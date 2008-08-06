<?php
// Check whether the config is on 0.12.6 status
$query = 'SELECT `settings` FROM #__acctexp_config'
. ' WHERE `id` = \'1\''
;
$database->setQuery( $query );
$set = $database->loadResult();

$serialupdate	= false;
$jsonconversion	= false;
if ( ( strpos( $set, '{' ) !== 0 ) && ( ( strpos( $set, '\\' ) !== false ) || ( strpos( $set, "\n" ) !== false ) ) && !empty( $set ) ) {
	$settings = parameterHandler::decode( $set );

	if ( isset( $settings['milist'] ) ) {
		$temp = explode( ';', $settings['milist'] );
		$settings['milist'] = $temp;
	}

	$query = 'UPDATE #__acctexp_config'
	. ' SET `settings` = \'' . base64_encode( serialize( $settings ) ) . '\''
	. ' WHERE `id` = \'1\''
	;
	$database->setQuery( $query );
	$database->query();

	$serialupdate = true;
} elseif ( strpos( $set, '{' ) === 0 ) {
	$jsonconversion = true;
} elseif ( empty( $set ) ) {
	$newinstall = true;
}
?>