<?php
/**
 * @version $Id: acctexp.service.class.php
 * @package AEC - Account Control Expiration - Membership Manager
 * @subpackage Core Class
 * @copyright 2006-2013 Copyright (C) David Deutsch
 * @author David Deutsch <skore@valanx.org> & Team AEC - http://www.valanx.org
 * @license GNU/GPL v.3 http://www.gnu.org/licenses/gpl.html or, at your option, any later version
 */

// Dont allow direct linking
defined('_JEXEC') or die( 'Direct Access to this location is not allowed.' );

function serviceCall( $type, $cmd, $request )
{
	$list = aecServiceList::getFlatList();

	$id = 0;
	foreach ( $list as $li ) {
		if ( $li->type == $type ) {
			$id = $li->id;
		}
	}

	if ( empty($id) ) {
		header("HTTP/1.0 401 Unauthorized"); die; // die, die
	}

	$service = aecService::getByType($type);

	if ( empty($service->id) ) {
		header("HTTP/1.0 401 Unauthorized"); die; // die, die
	}

	if ( !is_array($request) ) {
		if ( get_magic_quotes_gpc() ) {
			$request = stripslashes($request);
		}

		if ( strpos($request, '{') ) {
			$request = json_decode( $request );
		}
	} elseif ( !is_object($request) ) {
		$request = (object) $request;
	}

	if ( is_object($request) && isset($request->plan) ) {
		$service->overloadByPlan((int)$request->plan);
	}

	if ( !$service->testCmd($cmd, $request) ) {
		header("HTTP/1.0 401 Unauthorized"); die; // die, die
	}

	header( "HTTP/1.0 200 OK" );

	$return = $service->execCmd($cmd, $request);

	echo json_encode( $return ); die; // regular die
}

class aecServiceList
{
	public static function getDir()
	{
		return JPATH_SITE . '/components/com_acctexp/services';
	}

	public static function getServicePath( $name )
	{
		return self::getDir() . '/' . $name;
	}

	public static function getList()
	{
		$db = JFactory::getDBO();

		$db->setQuery(
			'SELECT `id`, `type`'
			. ' FROM #__acctexp_services'
		);

		return $db->loadObjectList();
	}

	public static function getSelectList()
	{
		$db = JFactory::getDBO();

		$query = 'SELECT `id` AS value, `name` AS text'
			. ' FROM #__acctexp_services'
			. ' WHERE `active` = 1'
		;
		$db->setQuery( $query );
		return $db->loadObjectList();
	}

	public static function getAvailableServices()
	{
		$list = xJUtility::getFileArray( self::getDir(), null, true, true );

		$services_list = array();
		foreach ( $list as $name ) {
			$path = self::getServicePath($name);

			if ( !is_dir($path) ) continue;

			// Only add directories with the proper structure
			if ( file_exists( $path . '/' . $name . '.php' ) ) {
				$services_list[] = $name;
			}
		}

		return $services_list;
	}

	public static function getAvailableServiceClasses( $noninstalled=false )
	{
		$installed = self::getFlatList();

		$list = self::getAvailableServices();

		foreach ( $list as $k => $name ) {
			if ( $noninstalled && !empty($installed) ) {
				foreach ( $installed as $service ) {
					if ( $name == $service->type ) continue;
				}
			}

			$class = 'service_' . $name;

			include_once self::getServicePath($name) . '/' . $name . '.php';

			$list[$k] = new $class();
		}

		return $list;
	}

	public static function getFlatList()
	{
		$db = JFactory::getDBO();

		$db->setQuery(
			'SELECT *'
			. ' FROM #__acctexp_services'
		);

		return $db->loadObjectList();
	}
}

class aecService extends serialParamDBTable
{
	/** @var int Primary key */
	var $id					= null;
	/** @var int */
	var $active 			= null;
	/** @var int */
	var $ordering			= null;
	/** @var string */
	var $name				= null;
	/** @var string */
	var $type				= null;
	/** @var string */
	var $params				= null;
	/** @var string */
	var $data				= null;

	public function __construct()
	{
		parent::__construct( '#__acctexp_services', 'id' );
	}

	/**
	 * @param int $id
	 *
	 * @return bool|aecService
	 */
	public static function getById( $id )
	{
		$db = JFactory::getDBO();

		$db->setQuery(
			'SELECT `type`'
			. ' FROM #__acctexp_services'
			. ' WHERE `id` = ' . $id . ' '
		);

		$type = $db->loadResult();

		if ( $type ) {
			include_once aecServiceList::getServicePath($type) . '/' . $type . '.php';

			$name = 'service_' . $type;

			$service = new $name;
			$service->load($id);

			return $service;
		} else {
			return false;
		}
	}

	/**
	 * @param string $type
	 *
	 * @return bool|aecService
	 */
	public static function getByType( $type )
	{
		$db = JFactory::getDBO();

		$db->setQuery(
			'SELECT `id`'
			. ' FROM #__acctexp_services'
			. ' WHERE `type` = \'' . $type . '\' '
		);

		$id = $db->loadResult();

		include_once aecServiceList::getServicePath($type) . '/' . $type . '.php';

		if ( $id ) {
			$name = 'service_' . $type;

			$service = new $name;
			$service->load($id);

			return $service;
		} else {
			$name = 'service_' . $type;

			$service = new $name;

			return $service;
		}
	}

	public function loadByType( $type )
	{
		$db = JFactory::getDBO();

		$db->setQuery(
			'SELECT `id`'
			. ' FROM #__acctexp_services'
			. ' WHERE `type` = \'' . $type . '\' '
		);

		$id = $db->loadResult();

		if ( $id ) {
			return $this->load($id);
		} else {
			return false;
		}
	}

	public function overloadByPlan( $plan )
	{
		// Get list of service MIs
		$mis = microIntegrationHandler::getMIsbyPlan($plan);

		if ( empty($mis) ) return;
print_r($this);
		foreach ( $mis as $miid ) {
			$mi = new microIntegration();
			$mi->load($miid);

			if ( !$mi->callIntegration() ) continue;

			if ( method_exists($mi->mi_class, 'overrideService') ) {
				$mi->mi_class->overrideService($this);
			}
		}print_r($this);
	}

	private function loadLanguage()
	{
		$basepath = JPATH_SITE . '/components/com_acctexp/services/' . $this->type;

		if ( empty( $this->id ) ) $this->copyAssets();

		xJLanguageHandler::loadList( array(	'com_acctexp.services.' . $this->type => JPATH_SITE ) );
	}

	public function copyAssets()
	{
		$syslangpath = JPATH_SITE . '/language';

		$languages = xJLanguageHandler::getSystemLanguages();

		$langpath = JPATH_SITE . '/components/com_acctexp/services/' . $this->type . '/language';

		foreach ( $languages as $l ) {
			$lpath = $langpath . '/' . $l;

			if ( !is_dir( $lpath ) || !is_dir( $syslangpath . '/' . $l ) ) continue;

			$filename = $l . '.com_acctexp.services.' . $this->type . '.ini';

			$source = $lpath . '/' . $filename;

			if ( !file_exists( $source ) ) continue;

			$dest = $syslangpath . '/' . $l . '/' . $filename;

			copy( $source, $dest );
		}
	}

	public function declareParamFields()
	{
		return array( 'params', 'data' );
	}

	public function testCmd( $cmd, $request )
	{
		$cmd = AECToolbox::camelize($cmd);

		$method = 'testCmd' . $cmd;

		if ( !method_exists( $this, $method ) ) {
			return true;
		}

		return $this->$method($request);
	}

	public function execCmd( $cmd, $request )
	{
		$cmd = AECToolbox::camelize($cmd);

		$method = 'cmd' . $cmd;

		if ( !method_exists( $this, $method ) ) {
			return false;
		}

		return $this->$method($request);
	}

	public function getCmds( $cmd, $request )
	{
		// TODO: FINISH
		return array();
	}

	public function savePOSTsettings( $array )
	{
		// Strip out params that we don't need
		$params = $this->stripNonParams( $array );

		// Check whether there is a custom function for saving params
		$new_params = $this->stripNonParams($params);

		$this->name		= $array['name'];
		$this->active	= $array['active'];
		$this->type		= $array['type'];

		$this->params = $new_params;

		return true;
	}

	public function stripNonParams( $array )
	{
		// All variables of the class have to be stripped out
		$vars = get_class_vars( 'aecService' );

		foreach ( $vars as $name => $blind ) {
			if ( isset( $array[$name] ) ) {
				unset( $array[$name] );
			}
		}

		return $array;
	}
}
