<?php
/**
 * @version $Id: eucalib.php 16 2007-06-25 09:04:04Z mic $
 * @package AEC - Account Control Expiration - Subscription component for Joomla! OS CMS
 * @subpackage Abstract Library for Joomla Components
 * @copyright Copyright (C) 2007 David Deutsch, All Rights Reserved
 * @author David Deutsch <skore@skore.de>
 * @license GNU/GPL v.2 http://www.gnu.org/copyleft/gpl.html
 */
// Copyright (C) 2006-2007 David Deutsch
// All rights reserved.
// This source file an abstract Library for Joomla! Components,
// originally developed for the Account Expiration Control Component
//
// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License (GPL)
// as published by the Free Software Foundation; either version 2
// of the License, or (at your option) any later version.
//
// Please note that the GPL states that any headers in files and
// Copyright notices as well as credits in headers, source files
// and output (screens, prints, etc.) can not be removed.
// You can extend them with your own credits, though...
//
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with this program; if not, write to the Free Software
// Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
//
// The "GNU General Public License" (GPL) is available at
// http://www.gnu.org/copyleft/gpl.html.

// The Extremely Useful Component LIBrary will rock your socks. Seriously. Reuse it!

/**
* parameterized Database Table entry
*
* For use with as an abstract class that adds onto table entries
*/
class paramDBTable extends mosDBTable {

	/**
	 * Receive Parameters and decode them into an array
	 * @return array
	 */
	function getParams( $field = 'params' ) {
		if( empty( $this->$field ) ) {
			return false;
		}

		$params = explode( "\n", $this->$field );

		$array = array();
		foreach( $params as $chunk ) {
			$k = explode( '=', $chunk, 2 );
			$array[$k[0]] = stripslashes( $k[1] );
			unset( $k );
		}
		return $array;
	}

	/**
	 * Encode array and set Parameter field
	 */
	function setParams( $array, $field = 'params' ) {
		$params = array();

		foreach( $array as $key => $value ) {
			if( !is_null( $key ) ) {
				$value = trim( $value );
				if( !get_magic_quotes_gpc() ) {
					$value = addslashes( $value );
				}

				$params[] = $key . '=' . $value;
			}
		}

		$this->$field = implode( "\n", $params );
	}

	/**
	 * Add an array of Parameters to an existing parameter field
	 */
	function addParams( $array, $field = 'params' ) {
		$params = $this->getParams( $field );
		foreach( $array as $key => $value ) {
			$params[$key] = $value;
		}
		$this->setParams ($params, $field);
	}

	/**
	 * Delete a set of Parameters providing an array of key names
	 */
	function delParams( $array, $field = 'params' ) {
		$params = $this->getParams( $field );
		foreach( $array as $key ) {
			if( isset( $params[$key] ) ) {
				unset( $params );
			}
		}
		$this->setParams( $params, $field );
	}

	/**
	 * Return the differences between a new set of Parameters and the existing one
	 */
	function diffParams( $array, $field = 'params' ) {
		$diff = array();

		$params = $this->getParams( $field );
		foreach( $array as $key => $value ) {
			if( isset( $params[$key] ) ) {
				if( $value !== $params[$key] ) {
					$diff[$key] = array( $value, $params[$key] );
				}
			}
		}

		if( count( $diff ) ) {
			return $diff;
		}else{
			return false;
		}
	}
}
?>