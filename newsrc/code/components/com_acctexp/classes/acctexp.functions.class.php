<?php
/**
 * @version $Id: acctexp.functions.class.php
 * @package AEC - Account Control Expiration - Membership Manager
 * @subpackage Core Class
 * @copyright 2006-2013 Copyright (C) David Deutsch
 * @author David Deutsch <skore@valanx.org> & Team AEC - http://www.valanx.org
 * @license GNU/GPL v.3 http://www.gnu.org/licenses/gpl.html or, at your option, any later version
 */

function aecDebug( $text, $level = 128 )
{
	aecQuickLog( 'debug', 'debug', $text, $level );
}

function aecQuickLog( $short, $tags, $text, $level = 128 )
{
	$eventlog = new eventLog();
	if ( empty( $text ) ) {
		$eventlog->issue( $short, $tags, "[[EMPTY]]", $level );
	} elseif ( is_array( $text ) || is_object( $text ) ) {
		// Due to some weird error, json_encode sometimes throws a notice - even on a proper array or object
		$eventlog->issue( $short, $tags, @json_encode( $text ), $level );
	} elseif ( is_string( $text ) || is_bool( $text ) || is_float( $text ) ) {
		$eventlog->issue( $short, $tags, $text, $level );
	} elseif ( is_numeric( $text ) ) {
		$eventlog->issue( $short, $tags, $text, $level );
	} else {
		$eventlog->issue( $short, $tags, "[[UNSUPPORTED TYPE]]", $level );
	}
}

function aecGetParam( $name, $default='', $safe=false, $safe_params=array() )
{
	$return = JArrayHelper::getValue( $_REQUEST, $name, $default );

	if ( !isset( $_REQUEST[$name] ) && !isset( $_POST[$name] ) ) {
		return $default;
	}

	if ( !is_array( $return ) ) {
		$return = trim( $return );
	}

	if ( !empty( $_POST[$name] ) ) {
		if ( is_array( $_POST[$name] ) && !is_array( $return ) ) {
			$return = $_POST[$name];
		} elseif ( empty( $return ) ) {
			$return = $_POST[$name];
		}
	}

	if ( empty( $return ) && !empty( $_REQUEST[$name] ) ) {
		$return = $_REQUEST[$name];
	}

	if ( $safe ) {
		if ( is_array( $return ) ) {
			foreach ( $return as $k => $v ) {
				$return[$k] = aecEscape( $v, $safe_params );
			}
		} else {
			$return = aecEscape( $return, $safe_params );
		}

	}

	return $return;
}

function aecEscape( $value, $safe_params )
{
	if ( is_array( $value ) ) {
		$array = array();
		foreach ( $value as $k => $v ) {
			$array[$k] = aecEscape( $v, $safe_params );
		}

		return $array;
	}

	$regex = "#{aecjson}(.*?){/aecjson}#s";

	// find all instances of json code
	$matches = array();
	preg_match_all( $regex, $value, $matches, PREG_SET_ORDER );

	if ( count( $matches ) ) {
		$value = str_replace( $matches, array(''), $value );
	}

	if ( get_magic_quotes_gpc() ) {
		$return = stripslashes( $value );
	} else {
		$return = $value;
	}

	if ( in_array( 'clear_nonemail', $safe_params ) ) {
		if ( strpos( $value, '@' ) === false ) {
			if ( !in_array( 'clear_nonalnum', $safe_params ) ) {
				// This is not a valid email adress to begin with, so strip everything hazardous
				$safe_params[] = 'clear_nonalnum';
			}
		} else {
			$array = explode('@', $return, 2);

			$username = preg_replace( '/[^a-z0-9._+-]+/i', '', $array[0] );
			$domain = preg_replace( '/[^a-z0-9.-]+/i', '', $array[1] );

			$return = $username.'@'.$domain;
		}
	}

	if ( in_array( 'clear_nonalnumwhitespace', $safe_params ) ) {
		$return = preg_replace( "/[^a-z0-9@._+-\s]/i", '', $return );
	}

	if ( in_array( 'clear_nonalnum', $safe_params ) ) {
		$return = preg_replace( "/[^a-z0-9@._+-]/i", '', $return );
	}

	if ( !empty( $safe_params ) ) {
		foreach ( $safe_params as $param ) {
			switch ( $param ) {
				case 'word':
					$e = strpos( $return, ' ' );
					if ( $e !== false ) {
						$r = substr( $return, 0, $e );
					} else {
						$r = $return;
					}
					break;
				case 'badchars':
					$r = preg_replace( "#[<>\"'%;()&]#i", '', $return );
					break;
				case 'int':
					$r = (int) $return;
					break;
				case 'string':
					$r = (string) $return;
					break;
				case 'float':
					$r = (float) $return;
					break;
			}

			$return = $r;
		}

	}

	$db = &JFactory::getDBO();

	return xJ::escape( $db, $return );
}

function aecPostParamClear( $array, $safe=false, $safe_params=array( 'string', 'badchars' ) )
{
	$cleararray = array();
	foreach ( $array as $key => $value ) {
		$cleararray[$key] = aecGetParam( $key, $safe, $safe_params );
	}

	return $cleararray;
}

function aecRedirect( $url, $msg=null, $class=null )
{
	$app = JFactory::getApplication();

	$app->redirect( $url, $msg, $class );
}
