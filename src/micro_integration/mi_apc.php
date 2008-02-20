<?php
/**
 * @version $Id: mi_apc.php
 * @package AEC - Account Control Expiration - Subscription component for Joomla! OS CMS
 * @subpackage Micro Integrations - Advanced Profile Control
 * @copyright 2006/2007 Copyright (C) David Deutsch
 * @author David Deutsch <skore@skore.de> & Team AEC - http://www.globalnerd.org
 * @license GNU/GPL v.2 http://www.gnu.org/copyleft/gpl.html
 */

defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );

class mi_apc
{
	function Info()
	{
		$info = array();
		$info['name'] = _AEC_MI_NAME_APC;
		$info['desc'] = _AEC_MI_DESC_APC;

		return $info;
	}

	function Settings( $params )
	{
		global $database;

		$query = 'SELECT groupid, title, description'
	 	. ' FROM #__comprofiler_accesscontrol_groups'
	 	;
	 	$database->setQuery( $query );
	 	$groups = $database->loadObjectList();

		$sg = array();
		foreach( $groups as $group ) {
			$sg[] = mosHTML::makeOption( $group->groupid, $group->title . ' - ' . substr( strip_tags( $group->description ), 0, 30 ) );
		}

        $settings = array();
		$settings['set_group']			= array( 'list_yesno' );
		$settings['group']				= array( 'list' );
		$settings['set_group_exp']	= array( 'list_yesno' );
		$settings['group_exp']			= array( 'list' );
		$settings['delete_on_exp'] 	= array( 'list_yesno' );
		$settings['rebuild']			= array( 'list_yesno' );

		$settings['lists']['group']		= mosHTML::selectList( $sg, 'group', 'size="4"', 'value', 'text', $params['group'] );
		$settings['lists']['group_exp'] = mosHTML::selectList( $sg, 'group_exp', 'size="4"', 'value', 'text', $params['group_exp'] );

		return $settings;
	}

	function expiration_action( $params, $metaUser, $plan )
	{
		global $database;

		if( $this->integrationActive() ){
			if ( $params['set_group_exp'] ) {
				$this->setGroupId( $metaUser->userid, $params['group_exp']  );
			}
		}
	}

	function action( $params, $metaUser, $invoice, $plan )
	{
		if( $this->integrationActive() ){
			if ( $params['set_group'] ) {
				$this->setGroupId( $metaUser->userid, $params['group']  );
			}
		}
	}

	function setGroupId( $userid, $groupid )
	{
		global $database;

		$query = 'SELECT title'
	 	. ' FROM #__comprofiler_accesscontrol_groups'
	 	. ' WHERE groupid = \'' .  . '\''
	 	;
	 	$database->setQuery( $query );
	 	$groups = $database->loadResult();
	}

	function integrationActive()
	{
		// I don't really think its necessary to check the integration every time, but the original integration had this
		global $database;

		$query = 'SELECT `value`'
				. ' FROM #__comprofiler_accesscontrol_settings'
				. ' WHERE `key` = \'integrateSubscription\''
				;
		$database->setQuery( $query );
		return ( $database->loadResult() == 'AEC' );
	}
}

?>