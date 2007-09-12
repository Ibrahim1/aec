<?php
/**
 * @version $Id: eucalib.php
 * @package Eucalib: Component library the Joomla! CMS
 * @subpackage Abstract Library for Joomla Components
 * @copyright Copyright (C) 2007 David Deutsch, All Rights Reserved
 * @author David Deutsch <skore@skore.de>
 * @license GNU/GPL v.2 or later http://www.gnu.org/copyleft/gpl.html
 *
 *                         _ _ _
 *                        | (_) |
 *     ___ _   _  ___ __ _| |_| |__
 *    / _ \ | | |/ __/ _` | | | '_ \
 *   |  __/ |_| | (_| (_| | | | |_) |
 *    \___|\__,_|\___\__,_|_|_|_.__/  v1.0
 *
 * The Extremely Useful Component LIBrary will rock your socks. Seriously. Reuse it!
 */

defined( '_VALID_MOS' ) or die( 'Restricted access' );

/**
* parameterized Database Table entry
*
* For use with as an abstract class that adds onto table entries
*/
class paramDBTable extends mosDBTable
{
	/**
	 * Receive Parameters and decode them into an array
	 * @return array
	 */
	function getParams( $field = 'params' )
	{
		if ( empty( $this->$field ) ) {
			return false;
		}

		$params = explode( "\n", $this->$field );

		$array = array();
		foreach ( $params as $chunk ) {
			$k = explode( '=', $chunk, 2 );
			if ( !empty( $k[0] ) ) {
				// Strip slashes, but preserve special characters
				$array[$k[0]] = stripslashes( str_replace( array( '\n', '\t', '\r' ), array( "\n", "\t", "\r" ), $k[1] ) );
			}
			unset( $k );
		}
		return $array;
	}

	/**
	 * Encode array and set Parameter field
	 */
	function setParams( $array, $field = 'params' )
	{
		$params = array();

		foreach ( $array as $key => $value ) {
			if ( !empty( $key ) ) {
				if ( get_magic_quotes_gpc() ) {
					$value = stripslashes($value);
				}
				$value = $this->_db->getEscaped( $value );

				$params[] = $key . '=' . $value;
			}
		}

		$this->$field = implode( "\n", $params );
	}

	/**
	 * Add an array of Parameters to an existing parameter field
	 */
	function addParams( $array, $field = 'params', $overwrite = true )
	{
		$params = $this->getParams( $field );
		foreach ( $array as $key => $value ) {
			if ( $overwrite ) {
				$params[$key] = $value;
			} else {
				if ( !isset( $params[$key] ) ) {
					$params[$key] = $value;
				}
			}
		}
		$this->setParams( $params, $field );
	}

	/**
	 * Delete a set of Parameters providing an array of key names
	 */
	function delParams( $array, $field = 'params' )
	{
		$params = $this->getParams( $field );
		foreach ( $array as $key ) {
			if ( isset( $params[$key] ) ) {
				unset( $params[$key] );
			}
		}
		$this->setParams( $params, $field );
	}

	/**
	 * Return the differences between a new set of Parameters and the existing one
	 */
	function diffParams( $array, $field = 'params' )
	{
		$diff = array();

		$params = $this->getParams( $field );
		foreach ( $array as $key => $value ) {
			if ( isset( $params[$key] ) ) {
				if( !( ( $value === $params[$key] ) || ( addslashes( $value ) === $params[$key] ) ) ) {
					$diff[$key] = array( $params[$key], $value );
				}
			}
		}

		if ( count( $diff ) ) {
			return $diff;
		} else {
			return false;
		}
	}
}

class languageFile
{

	function languageFile( $filepath )
	{
		$this->filepath = $filepath;
	}

	function getConstantsArray()
	{
		$file = fopen( $this->filepath, "r" );

		$array = array();
		while ( !feof( $file ) ) {
			$buffer = fgets($file, 4096);
			if ( strpos( $buffer, 'define') !== false ) {
				$linearray = explode( '\'', $buffer );
				if ( count( $linearray ) === 5 ) {
					$array[$linearray[1]] = $linearray[3];
				}
			}
    	}

		return $array;
	}

	function getHTML()
	{
		$file = fopen( $this->filepath, "r" );

		$array = array();
		while ( !feof( $file ) ) {
			$buffer = fgets($file, 4096);
			if ( strpos( $buffer, 'define') !== false ) {
				$linearray = explode( '\'', $buffer );
				if ( count( $linearray ) === 5 ) {
					$array[$linearray[1]] = $linearray[3];
				}
			}
    	}

		return $array;
	}
}
?>