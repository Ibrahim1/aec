<?php
$get = array();

if ( !isset( $_GET['option'] ) ) {
	$_GET['option'] = 'com_acctexp';
}

if ( !isset( $_GET['task'] ) ) {
	$_GET['task'] = 'notification';
}

foreach ( $_GET as $k => $v ) {
	$get[] = $k . "=" . $v;
}

header( 'Location: index.php?' . implode( '&', $get ) );

?>
