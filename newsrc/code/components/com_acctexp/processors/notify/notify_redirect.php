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

header( 'Location: http://' . $_SERVER['HTTP_HOST'] . str_replace( '/components/com_acctexp/processors/notify/notify_redirect.php', '', $_SERVER['PHP_SELF'] ) . '/index.php?' . implode( '&', $get ) );

?>
