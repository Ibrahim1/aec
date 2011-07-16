<?php
/**
 * @version $Id: mi_pardot_marketing.php
 * @package AEC - Account Control Expiration - Membership Manager
 * @subpackage Micro Integrations - Pardot Marketing
 * @copyright 2011 Copyright (C) David Deutsch
 * @author David Deutsch <skore@skore.de> & Team AEC - http://www.valanx.org
 * @license GNU/GPL v.2 http://www.gnu.org/licenses/old-licenses/gpl-2.0.html or, at your option, any later version
 */

// Dont allow direct linking
( defined('_JEXEC') || defined( '_VALID_MOS' ) ) or die( 'Direct Access to this location is not allowed.' );

class mi_pardot_marketing extends MI
{
	function Info()
	{
		$info = array();
		$info['name'] = JText::_('AEC_MI_NAME_PARDOT_MARKETING');
		$info['desc'] = JText::_('AEC_MI_DESC_PARDOT_MARKETING');

		return $info;
	}

	function Settings()
	{
        $settings = array();
        $settings['url']			= array( 'inputE' );
        $settings['query']			= array( 'inputD' );

        $settings					= $this->autoduplicatesettings( $settings, array(), true, true );

		return $settings;
	}

	function install()
	{
		$db = &JFactory::getDBO();

		$query = 'CREATE TABLE IF NOT EXISTS `#__acctexp_mi_pardot_marketing` ('
		. '`id` int(11) NOT NULL auto_increment,'
		. '`userid` int(11) NOT NULL,'
		. '`active` int(4) NOT NULL default \'1\','
		. '`granted_listings` int(11) NULL,'
		. '`used_listings` int(11) NULL,'
		. '`api_key` text NULL,'
		. ' PRIMARY KEY (`id`)'
		. ')'
		;
		$db->setQuery( $query );
		$db->query();
		return;
	}

	function relayAction( $request )
	{
		if ( !isset( $this->settings['url'.$request->area] ) ) {
			return null;
		}

		$url = AECToolbox::rewriteEngineRQ( $this->settings['url'.$request->area], $request );
		$query = AECToolbox::rewriteEngineRQ( $this->settings['query'.$request->area], $request );

		return $this->fetchURL( $this->createURL( $url, $query ) );
	}

	function createURL( $url, $query ) {
		$urlsplit = explode( '?', $url );

		$p = explode( "\n", $query );

		if ( !empty( $urlsplit[1] ) ) {
			$p2 = explode( '&', $urlsplit[1] );

			if ( !empty( $p2 ) ) {
				$p = array_merge( $p2, $p );
			}
		}

		$fullp = array();
		foreach ( $p as $entry ) {
			$e = explode( '=', $entry );

			if ( !empty( $e[0] ) && !empty( $e[1] ) ) {
				$fullp[] = urlencode( trim($e[0]) ) . '=' . urlencode( trim($e[1]) );
			}
		}

		return $urlsplit[0] . '?' . implode( '&', $fullp );
	}

	function fetchURL( $url )
	{
		global $aecConfig;

		if ( strpos( $url, '://' ) === false ) {
			$purl = 'http://' . $url;
		} else {
			$purl = $url;
		}

		$url_parsed = parse_url( $purl );

		$host = $url_parsed["host"];

		if ( empty( $url_parsed["port"] ) ) {
			$port = 80;
		} else {
			$port = $url_parsed["port"];
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

		if ( $aecConfig->cfg['curl_default'] ) {
			$response = processor::doTheCurl( $url, '' );
		} else {
			$response = processor::doTheHttp( $url, $path, '', $port );
		}

		return true;
	}
}
?>
