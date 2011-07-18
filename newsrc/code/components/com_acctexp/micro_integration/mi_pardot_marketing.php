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
		. '`created_on` datetime NOT NULL default \'0000-00-00 00:00:00\''
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

	function getAPIkey()
	{
		
	}

}

class PardotConnector
{
	function getAPIkey( $settings )
	{
		$params = array( 'email' => $settings['email'], 'password' => $settings['password'], 'user_key' => $settings['user_key'] );

		return $this->fetch( 'login', null, $params );
	}

	function fetch( $area, $cmd, $params )
	{
		global $aecConfig;

		$url = 'https://pi.pardot.com/api/' . $area . '/version/3';

		if ( !empty( $cmd ) ) {
			 $url .= '/'.$cmd;
		}

		if ( !empty( $params ) ) {
			$url .= '?';

			$ps = array();
			foreach ( $params as $k => $v ) {
				$ps[] = urlencode( $k ) . "=" . urlencode( $v );
			}

			$url .= implode( '&', $ps );
		}

		$url_parsed = parse_url( $url );

		$host = $url_parsed["host"];

		if ( empty( $url_parsed["port"] ) ) {
			$port = 80;
		} else {
			$port = $url_parsed["port"];
		}

		$path = $url_parsed["path"];

		// Prevent 400 Error
		if ( empty( $path ) ) {
			$path = "/";
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

class aec_pardot_marketing extends serialParamDBTable
{
	/** @var int Primary key */
	var $id					= null;
	/** @var int */
	var $created_on			= null;
	/** @var int */
	var $api_key			= null;

	function aec_pardot_marketing( &$db )
	{
		parent::__construct( '#__acctexp_mi_pardot_marketing', 'id', $db );
	}

	function get( $settings )
	{
	 	$this->load(1);

		if ( empty( $this->created_on ) || ( $this->created_on == '0000-00-00 00:00:00' ) ) {
			global $aecConfig;

			$query = 'INSERT INTO #__acctexp_mi_pardot_marketing'
			. ' VALUES( \'1\', \'' . date( 'Y-m-d H:i:s', ((int) gmdate('U')) ) . '\' )'
			;
			$db->setQuery( $query );
			$db->query() or die( $db->stderr() );

			$this->load(1);
		}

		$diff = strtotime( $this->created_on ) - ((int) gmdate('U'));

		if ( $diff > 3300 ) {
			// Request new API key
			$pc = new PardotConnector();
			
			$this->api_key = $pc->getAPIkey( $settings );
		}
	}
}

?>
