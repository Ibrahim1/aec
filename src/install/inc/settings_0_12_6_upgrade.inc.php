<?php
// Check whether the config is on 0.12.6 status
$query = 'SELECT `settings` FROM #__acctexp_config'
. ' WHERE `id` = \'1\''
;
$database->setQuery( $query );
$set = $database->loadResult();

$serialupdate	= false;
$newinstall 	= true;

if ( ( strpos( $set, '{' ) !== 0 ) && !empty( $set ) ) {
	$settings = parameterHandler::decode( $set );

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

	$serialupdate = true;
} elseif ( strpos( $set, '{' ) === 0 ) {
	$jsonconversion = true;
} elseif ( empty( $set ) ) {
	$newinstall = true;
}
?>