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

	function Settings()
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
		$settings['set_default']		= array( 'list_yesno' );
		$settings['group']				= array( 'list' );
		$settings['set_group_exp']		= array( 'list_yesno' );
		$settings['set_default_exp']	= array( 'list_yesno' );
		$settings['group_exp']			= array( 'list' );
		$settings['rebuild']			= array( 'list_yesno' );

		$settings['lists']['group']		= mosHTML::selectList( $sg, 'group', 'size="4"', 'value', 'text', $params['group'] );
		$settings['lists']['group_exp'] = mosHTML::selectList( $sg, 'group_exp', 'size="4"', 'value', 'text', $params['group_exp'] );

		return $settings;
	}

	function saveparams( $params )
	{
		global $mosConfig_absolute_path, $database;
		$newparams = $params;

		if ( $params['rebuild'] && $params['set_group'] ) {
			$planlist = MicroIntegrationHandler::getPlansbyMI( $params['MI_ID'] );

			foreach ( $planlist as $planid ) {
				$userlist = SubscriptionPlanHandler::getPlanUserlist( $planid );
				foreach ( $userlist as $userid ) {
					$this->setGroupId( $userid, $params['group'], $params['set_default'] );
				}
			}

			$newparams['rebuild'] = 0;
		}

		return $newparams;
	}

	function expiration_action( $request )
	{
		global $database;

		if( $this->integrationActive() ){
			if ( $params['set_group_exp'] ) {
				return $this->setGroupId( $metaUser->userid, $params['group_exp'], $params['set_default_exp'] );
			}
		}
	}

	function action( $request )
	{
		if( $this->integrationActive() ){
			if ( $params['set_group'] ) {
				return $this->setGroupId( $metaUser->userid, $params['group'], $params['set_default'] );
			}
		}
	}

	function setGroupId( $userid, $groupid, $default = false )
	{
		global $database;

		if ( $default ) {
			$query = 'SELECT title'
		 	. ' FROM #__comprofiler_accesscontrol_groups'
		 	. ' WHERE default = \'1\''
		 	;
		 	$database->setQuery( $query );
		 	$group = $database->loadResult();
		} else {
			$query = 'SELECT title'
		 	. ' FROM #__comprofiler_accesscontrol_groups'
		 	. ' WHERE groupid = \'' . $groupid . '\''
		 	;
		 	$database->setQuery( $query );
		 	$group = $database->loadResult();
		}

		if ( !empty( $group ) ) {
			$query = 'UPDATE #__comprofiler'
					. ' SET `apc_type` = \'' . $group . '\''
					. ' WHERE `id` = \'' . (int) $this->userid . '\''
					;
			$database->setQuery( $query );
		} else {
			return false;
		}
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