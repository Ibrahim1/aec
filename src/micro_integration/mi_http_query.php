<?php
/**
 * @version $Id: mi_mysql_query.php 16 2007-07-02 13:29:29Z mic $
 * @package AEC - Account Control Expiration - Subscription component for Joomla! OS CMS
 * @subpackage Micro Integrations - MySQL Query
 * @copyright 2006/2007 Copyright (C) David Deutsch
 * @author David Deutsch <skore@skore.de> & Team AEC - http://www.globalnerd.org
 * @license GNU/GPL v.2 http://www.gnu.org/copyleft/gpl.html
 */

defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );

class mi_http_query
{
	function Info()
	{
		$info = array();
		$info['name'] = _AEC_MI_NAME_HTTP_QUERY;
		$info['desc'] = _AEC_MI_DESC_HTTP_QUERY;

		return $info;
	}

	function Settings()
	{
        $settings = array();
        $settings['url']			= array( 'inputE' );
        $settings['query']			= array( 'inputD' );
        $settings['url_exp']		= array( 'inputE' );
        $settings['query_exp']		= array( 'inputD' );
        $settings['url_pre_exp']	= array( 'inputE' );
        $settings['query_pre_exp']	= array( 'inputD' );
		$rewriteswitches			= array( 'cms', 'user', 'expiration', 'subscription', 'plan', 'invoice' );
		$settings['rewriteInfo']	= array( 'fieldset', _AEC_MI_SET4_MYSQL, AECToolbox::rewriteEngineInfo( $rewriteswitches ) );

		return $settings;
	}

	function relayAction( $request, $area )
	{
		return $this->fetchURL( AECToolbox::rewriteEngine( $this->createURL( $params['url'.$area], $params['query'.$area] ), $metaUser, $plan, $invoice ) );
	}

	function createURL( $url, $params ) {
		$urlsplit = explode( '?', $url );

		$p = explode( "\n", $params );

		if ( !empty( $urlsplit[1] ) ) {
			$p2 = explode( '&', $urlsplit[1] );

			if ( !empty( $p2 ) ) {
				$p = array_merge( $p2, $p );
			}
		}

		return $urlsplit[0] . '?' . implode( '&', $p );
	}

	function fetchURL( $url ) {
		$url_parsed = parse_url($url);

		$host = $url_parsed["host"];
		$port = $url_parsed["port"];
		if ( $port == 0 ) {
			$port = 80;
		}
		$path = $url_parsed["path"];

		//if url is http://example.com without final "/"
		//I was getting a 400 error
		if ( empty( $path ) ) {
			$path="/";
		}

		if ( $url_parsed["query"] != "" ) {
			$path .= "?".$url_parsed["query"];
		}

		$out = "GET $path HTTP/1.0\r\nHost: $host\r\n\r\n";
		$fp = fsockopen( $host, $port, $errno, $errstr, 30 );

		if ( $fp ) {
			fwrite( $fp, $out );

			$return = '';
			while ( !feof( $fp ) ) {
				$return .= fgets($fp, 1024);
			}

			fclose( $fp );

			return $return;
		} else {
			return false;
		}
	}
}
?>