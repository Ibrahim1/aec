<?php
/**
 * @version $Id: mi_juga.php 01 2007-07-21  $
 * @package AEC - Account Control Expiration - Subscription component for Joomla! OS CMS
 * @subpackage Micro Integrations - JUGA
 * @copyright 2006/2007 Copyright (C) David Deutsch
 * @author David Deutsch <skore@skore.de> & Team AEC - http://www.gobalnerd.org
 * @author Calum Polwart (Shiny Black Shoe Systems)
 * @license GNU/GPL v.2 http://www.gnu.org/copyleft/gpl.html
 *
 * based on some of David Deutsch's DocMan MI group handling
 */

defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );

/**
 * THIS SECTION BELONGS IN THE MI LANGUAGE FILE BUT IS PROVIDED HERE FOR LAZYNESS...
 *
 */




class mi_juga {

/** This section can be removed before deployment - its here as notes! SBS
 * Some info about how juga holds group allocations:
 * juga groups are defined in: jos_juga_groups
 * fields being id, title, description (id is unique primary key) also has checkout fields but not interested ;-)
 * users are allocated to ONE or MORE GROUPS
 * in a table called jos_juga_u2g
 * fields being: user_id (joomla user id) and group_id (the id above)
 *
 * Since this MI will allocate to a group it also needs to remove from a group,
 * Since the a user can be in multiple groups at once - allocating a diferent group at paln expiry is not the same
 * as removing
 */

	function Info () {
		$info = array();
		$info['name'] = _AEC_MI_NAME_JUGA;
		$info['desc'] = _AEC_MI_DESC_JUGA;

		return $info;
	}

	function checkInstallation () {
		//No action required;
	}

	function install () {
		// No action required;
	}

	function Settings( $params ) {
		global $database;

		$query = 'SELECT id, title, description'
	 	. ' FROM #__juga_groups'
	 	;
	 	$database->setQuery( $query );
	 	$groups = $database->loadObjectList();

		$sg = array();
		foreach( $groups as $group ) {
			$sg[] = mosHTML::makeOption( $group->id, $group->title . ' - '
			. substr( strip_tags( $group->description ), 0, 30 ) );
		}

        $settings = array();

	//Explode the selected groups
	if( !empty( $params['enroll_group'] ) ) {
		$gplist = explode( ';', $params['enroll_group'] );
		$selected_enrole_gps = array();
		foreach( $gplist as $enrole_group) {
			$selected_enrole_gps[]->value = $enrole_group;
		}
	}else{
		$selected_enrole_gps		= '';
	}

	if( !empty( $params['enroll_group_exp'] ) ) {
		$gplist = explode( ';', $params['enroll_group_exp'] );
		$selected_enrole_gps_exp = array();
		foreach( $gplist as $enrole_group_exp) {
			$selected_enrole_gps_exp[]->value = $enrole_group_exp;
		}
	}else{
		$selected_enrole_gps_exp		= '';
	}




$settings['lists']['enroll_group']		= mosHTML::selectList( $sg, 'enroll_group[]', 'size="4" multiple="true"', 'value', 'text', $selected_enrole_gps );
$settings['lists']['enroll_group_exp']	= mosHTML::selectList( $sg, 'enroll_group_exp[]', 'size="4" multiple="true"', 'value', 'text', $selected_enrole_gps_exp );
$settings['set_remove_group']			= array( 'list_yesno' );
$settings['set_enroll_group']			= array( 'list_yesno' );
$settings['enroll_group']				= array( 'list' );
$settings['set_enroll_group_exp']		= array( 'list_yesno' );
$settings['set_remove_group_exp']		= array( 'list_yesno' );
$settings['enroll_group_exp']			= array( 'list' );




		return $settings;
	}

	function detect_application () {
		global $mosConfig_absolute_path;

		return is_dir( $mosConfig_absolute_path . '/components/com_juga' );
	}

	function saveparams ( $params ) {
		global $mosConfig_absolute_path, $database;
		

		

		// IMPLODE THE ARRAYS
	
		$enrole_groups = implode( ';', $params['enroll_group']);
		$params['enroll_group'] = $enrole_groups;
		$enrole_groups_exp = implode( ';', $params['enroll_group_exp']);
		$params['enroll_group_exp'] = $enrole_groups_exp;

		//

		$newparams = $params;

		return $newparams;
	}

	function hacks () {
		// No hacks should be needed :-)
	}

	function expiration_action($params, $userid, $plan) {
		global $database;

		
		if ($params['set_remove_group_exp']) {
			$this->DeleteUserFromGroup( $userid );
		}
		else {
		// No action 
		}
		
		if( $params['set_enroll_group_exp'] ) {

			if( !empty( $params['enroll_group_exp'] ) ) {
				$gplist = explode( ';', $params['enroll_group_exp'] );
				$selected_enrole_gps_exp = array();
				foreach( $gplist as $enrole_group_exp) {
					$this->AddUserToGroup( $userid, $enrole_group_exp );
				}
			}
		}
		else {
		// No action
		}


		return true;
	}

	function action($params, $userid, $plan) {
		
		global $database;

		if ($params['set_remove_group']) {
			$this->DeleteUserFromGroup( $userid );
		}
		else {
		// No action
		}


		if ($params['set_enroll_group']) {
			if( !empty( $params['enroll_group'] ) ) {
				$gplist = explode( ';', $params['enroll_group'] );
				$selected_enrole_gps = array();
				foreach( $gplist as $enrole_group) {
					$this->AddUserToGroup( $userid, $enrole_group );
				}
			}
		}
else {
// No action
	


	}
}


	function AddUserToGroup ( $userid, $groupid ) {
		global $database;
		// Check user is not already a member of the group.
		$query = 'SELECT user_id'
		. ' FROM #__juga_u2g'
		. ' WHERE group_id = \'' . $groupid . '\' AND user_id = \''.$userid . '\''
		;

		$database->setQuery( $query );
		$user = $database->loadResult();

		//if( $user !== $userid ) {
			// then the user is not already a member of this group and can be set

			$query = 'INSERT INTO #__juga_u2g'
			. ' SET group_id = \'' . $groupid . '\', user_id = \''.$userid . '\''
			;


			$database->setQuery( $query );
			$database->query();


			return true;
		//}else
		//{
			return false;  //likely because already a member of group
		//}
	}

	function DeleteUserFromGroup ( $userid ) {
		global $database;

		
			$query = 'DELETE FROM #__juga_u2g'
			. ' WHERE user_id = \''.$userid . '\''
			;

			$database->setQuery( $query );
			$database->query();


			return true;
		
	}
}


?>