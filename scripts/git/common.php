<?php

function fsnr( $path, $search, $count )
{
	$content = file_get_contents( $path );

	$start = strpos( $content, $search )+strlen($search);

	$delim = substr( $search, -1, 1 );

	$length = strpos( $content, $delim, $start ) - $start;

	$old = substr( $content, $start, $length );

	if ( $old != $count ) {
		$content = str_replace( $search.$old.$delim, $search.$count.$delim, $content );

		return file_put_contents( $path, $content );
	} else {
		return null;
	}
}
