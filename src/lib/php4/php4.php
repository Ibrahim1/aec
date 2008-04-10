<?php
/**
 * @version $Id: php4.php
 * @package AEC - Account Control Expiration - Subscription component for Joomla! OS CMS
 * @subpackage PHP4 Compatibility Layer
 * @copyright Copyright (C) 2004-2007, David Deutsch, All Rights Reserved
 * @author David Deutsch <skore@skore.de> & Team AEC - http://www.gobalnerd.org
 * @license GNU/GPL v.2 http://www.gnu.org/copyleft/gpl.html
 */

// Dont allow direct linking
defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );

// If we haven't got native JSON, we must include it
if ( !function_exists( 'json_decode' ) ) {
	// Make sure no other service has loaded this library somewhere else
	if ( !class_exists( "Services_JSON" ) ) {
		require_once( $mosConfig_absolute_path . '/components/com_acctexp/lib/php4/json/json.php' );
	}

	// Create dummy encoding function
	function json_encode( $value )
	{
		$JSONenc = new Services_JSON();
		return $JSONenc->encode( $value );
	}

	// Create dummy decoding function
	function json_decode( $value )
	{
		$JSONdec = new Services_JSON();
		return $JSONdec->decode( $value );
	}

}

if ( !function_exists( 'str_split' ) ) {
	function str_split( $text, $split = 1 ) {
		// place each character of the string into and array
		$array = array();
		for ( $i=0; $i < strlen( $text ); ){
			$key = NULL;
			for ( $j = 0; $j < $split; $j++, $i++ ) {
				$key .= $text[$i];
			}
			array_push( $array, $key );
		}
		return $array;
	}
}

?>
