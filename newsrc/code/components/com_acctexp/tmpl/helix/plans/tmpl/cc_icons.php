<?
if ( is_array( $cc_list ) ) {
	$cc_array = $cc_list;
} else {
	$cc_array = explode( ',', $cc_list );
}

for ( $i = 0; $i < count( $cc_array ); $i++ ) {
	echo '<img src="' . JURI::root(true) . '/media/' . $option
	. '/images/site/cc_icons/ccicon_' . $cc_array[$i] . '.png"'
	. ' alt="' . $cc_array[$i] . '"'
	. ' title="' . $cc_array[$i] . '"'
	. ' class="cc_icon" />';
}
 ?>
