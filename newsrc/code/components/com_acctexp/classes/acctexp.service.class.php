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
	global $aecConfig;

	$list = aecServiceListe::getFlatList();

	$id = 0;
	foreach ( $list as $li ) {
		if ( $li->type == $type ) {
			$id = $li->id;
		}
	}

	if ( empty($id) ) {
		header("HTTP/1.0 401 Unauthorized"); die; // die, die
	}

	$service = new aecService();

	if ( !$service->loadByType($type) ) {
		header("HTTP/1.0 401 Unauthorized"); die; // die, die
	}

	if ( get_magic_quotes_gpc() ) {
		$request = stripslashes($request);
	}

	$req = json_decode( $request );

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

	public static function getList()
	{
		$db->setQuery(
			'SELECT `id`'
			. ' FROM #__acctexp_services'
		);

		$list = $db->loadObjectList();
	}

	public static function getAvailableServices()
	{
		$list = xJUtility::getFileArray( self::getDir(), null, true, true );

		$services_list = array();
		foreach ( $list as $name ) {
			if ( is_dir( self::getDir() . '/' . $name ) ) {
				// Only add directories with the proper structure
				if ( file_exists( self::getDir() . '/' . $name . '/' . $name . '.php' ) ) {
					$services_list[] = $name;
				}
			}
		}

		return $services_list;
	}

	public static function getFlatList()
	{
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

	public function loadByType( $type )
	{
		$db->setQuery(
			'SELECT `id`'
			. ' FROM #__acctexp_services'
			. 'WHERE `type` = \'' . $type . '\' '
		);

		$id = $db->loadResult();

		if ( $id ) {
			return $this->load($id);
		} else {
			return false;
		}
	}

	public function declareParamFields()
	{
		return array( 'params', 'data' );
	}

	public function testCmd( $cmd, $request )
	{
		// TODO: FINISH
		return true;
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

}
