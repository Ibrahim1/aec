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
        $settings['email']			= array( 'inputC' );
        $settings['password']		= array( 'inputC' );
        $settings['user_key']		= array( 'inputC' );
        $settings['pardot_lists']	= array( 'inputD' );

		if ( !empty( $this->settings['email'] ) && !empty( $this->settings['password'] ) && !empty( $this->settings['user_key'] ) ) {
			$db = &JFactory::getDBO();

			$pc = new PardotConnector( $db );
			$pc->get( $this->settings );

        	$settings['api_key']		= array( 'p', null, null, "API Key currently in use: ".$pc->api_key );
		} else {
			$settings['api_key']		= array( 'p', null, null, "API Key currently in use: Please fill in the above details to request an API key" );
		}


		return $settings;
	}

	function checkInstallation()
	{
		$db = &JFactory::getDBO();

		$app = JFactory::getApplication();

		$tables	= array();
		$tables	= $db->getTableList();

		return in_array( $app->getCfg( 'dbprefix' ) . 'acctexp_mi_pardot_marketing', $tables );
	}

	function install()
	{
		$db = &JFactory::getDBO();

		$query = 'CREATE TABLE IF NOT EXISTS `#__acctexp_mi_pardot_marketing` ('
		. '`id` int(11) NOT NULL auto_increment,'
		. '`created_on` datetime NOT NULL default \'0000-00-00 00:00:00\','
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
		if ( $request->action != 'action' ) {
			return null;
		}

		$db = &JFactory::getDBO();

		$pc = new PardotConnector( $db );
		$pc->get( $this->settings );

		return $pc->createUser( $this->settings, $request->metaUser->cmsUser->email );
	}

}

class PardotConnector extends serialParamDBTable
{
	/** @var int Primary key */
	var $id					= null;
	/** @var int */
	var $created_on			= null;
	/** @var int */
	var $api_key			= null;

	function PardotConnector( &$db )
	{
		parent::__construct( '#__acctexp_mi_pardot_marketing', 'id', $db );
	}

	function get( $settings )
	{
	 	$this->load(1);

		if ( empty( $this->created_on ) || ( $this->created_on == '0000-00-00 00:00:00' ) ) {
			global $aecConfig;

			$db = &JFactory::getDBO();

			$query = 'INSERT INTO #__acctexp_mi_pardot_marketing'
			. ' VALUES( \'1\', \'' . date( 'Y-m-d H:i:s', ((int) gmdate('U')) ) . '\', \'\' )'
			;
			$db->setQuery( $query );
			$db->query() or die( $db->stderr() );

			$this->load(1);
		}

		$diff = strtotime( $this->created_on ) - ((int) gmdate('U'));

		// Check whether key is null or old
		if ( ( $diff > 3300 ) || empty( $this->api_key ) || ( $this->api_key == 'Login failed' ) ) {
			$request = $this->getAPIkey( $settings );

			if ( strpos( $request, 'stat="fail"' ) !== false ) {
				$this->api_key = 'Login failed';
			} else {
				$this->api_key = XMLprocessor::XMLsubstring_tag( $request, 'api_key' );
			}

			$this->storeload();
		}
	}

	function getAPIkey( $settings )
	{
		$params = array( 'email' => $settings['email'], 'password' => $settings['password'], 'user_key' => $settings['user_key'] );

		return $this->fetch( 'login', null, $params );
	}

	function createUser( $settings, $email )
	{
		$params = array( 'user_key' => $settings['user_key'], 'api_key' => $this->api_key );

		if ( !empty( $settings['pardot_lists'] ) ) {
			$li = explode( "\n", $settings['pardot_lists'] );
			
			foreach ( $li as $k ) {
				$params[$k] = "1";
			}
		}

		return $this->fetch( 'prospect', 'do/create/email/'.$email, $params );
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
aecDebug("calling Pardot: ".$url);
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
aecDebug($response);
		return $response;
	}
}

?>
