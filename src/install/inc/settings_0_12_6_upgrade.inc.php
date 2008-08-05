<?php
// Check whether the config is on 0.12.6 status
$query = 'SELECT `settings` FROM #__acctexp_config'
. ' WHERE `id` = \'1\''
;
$database->setQuery( $query );
$set = $database->loadResult();

$jsonupdate = false;
if ( ( strpos( $set, '{' ) !== 0 ) && !empty( $set ) ) {
	$settings = parameterHandler::decode( $set );

	if ( isset( $settings['milist'] ) ) {
		$temp = explode( ';', $settings['milist'] );
		$settings['milist'] = $temp;
	}

	$query = 'UPDATE #__acctexp_config'
	. ' SET `settings` = \'' . $database->getEscaped( jsoonHandler::encode( $settings ) ) . '\''
	. ' WHERE `id` = \'1\''
	;
	$database->setQuery( $query );
	$database->query();

	$jsonupdate = true;
} elseif ( empty( $set ) ) {
	$newinstall = true;
}
?>