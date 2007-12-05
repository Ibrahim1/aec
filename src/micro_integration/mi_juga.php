<?php
/**
 * @version $Id: mi_juga.php 01 2007-07-21  $
 * @package AEC - Account Control Expiration - Subscription component for Joomla! OS CMS
 * @subpackage Micro Integrations - JUGA
 * @copyright 2006/2007 Copyright (C) David Deutsch
 * @author David Deutsch <skore@skore.de> & Team AEC - http://www.globalnerd.org
 * @author Calum Polwart (Shiny Black Shoe Systems)
 * @license GNU/GPL v.2 http://www.gnu.org/copyleft/gpl.html
 *
 * based on some of David Deutsch's DocMan MI group handling
 */

defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );

class mi_juga
{
	function Info()
	{
		$info = array();
		$info['name'] = _AEC_MI_NAME_JUGA;
		$info['desc'] = _AEC_MI_DESC_JUGA;

		return $info;
	}

	function Settings( $params )
	{
		global $database;

		$query = 'SELECT id, title, description'
	 	. ' FROM #__juga_groups'
	 	;
	 	$database->setQuery( $query );
	 	$groups = $database->loadObjectList();

		$sg = array();
		foreach ( $groups as $group ) {
			$sg[] = mosHTML::makeOption( $group->id, $group->title . ' - '
			. substr( strip_tags( $group->description ), 0, 30 ) );
		}

        $settings = array();

		// Explode the selected groups
		if ( !empty( $params['enroll_group'] ) ) {
			$gplist = explode( ';', $params['enroll_group'] );
			$selected_enroll_gps = array();
			foreach ( $gplist as $enroll_group) {
				$selected_enroll_gps[]->value = $enroll_group;
			}
		} else {
			$selected_enroll_gps		= '';
		}

		if ( !empty( $params['enroll_group_exp'] ) ) {
			$gplist = explode( ';', $params['enroll_group_exp'] );
			$selected_enroll_gps_exp = array();
			foreach ( $gplist as $enroll_group_exp) {
				$selected_enroll_gps_exp[]->value = $enroll_group_exp;
			}
		} else {
			$selected_enroll_gps_exp		= '';
		}

		$settings['lists']['enroll_group']		= mosHTML::selectList( $sg, 'enroll_group[]', 'size="4" multiple="true"', 'value', 'text', $selected_enroll_gps );
		$settings['lists']['enroll_group_exp']	= mosHTML::selectList( $sg, 'enroll_group_exp[]', 'size="4" multiple="true"', 'value', 'text', $selected_enroll_gps_exp );

		$settings['set_remove_group']			= array( 'list_yesno' );
		$settings['set_enroll_group']			= array( 'list_yesno' );
		$settings['enroll_group']				= array( 'list' );
		$settings['set_remove_group_exp']		= array( 'list_yesno' );
		$settings['set_enroll_group_exp']		= array( 'list_yesno' );
		$settings['enroll_group_exp']			= array( 'list' );
		$settings['rebuild']					= array( 'list_yesno' );

		return $settings;
	}

	function detect_application()
	{
		global $mosConfig_absolute_path;

		return is_dir( $mosConfig_absolute_path . '/components/com_juga' );
	}

	function saveparams( $params )
	{
		global $mosConfig_absolute_path, $database;
		$newparams = $params;

		// Arrays -> semicolon separated fields
		$newparams['enroll_group'] = implode( ';', $params['enroll_group']);
		$newparams['enroll_group_exp'] = implode( ';', $params['enroll_group_exp']);

		if ( $params['rebuild'] ) {
			$planlist = MicroIntegrationHandler::getPlansbyMI( $params['MI_ID'] );

			foreach ( $planlist as $planid ) {
				$userlist = SubscriptionPlanHandler::getPlanUserlist( $planid );
				foreach ( $userlist as $userid ) {
					if ( $params['set_enroll_group'] ) {
						foreach ( $params['enroll_group'] as $groupid ) {
							$this->AddUserToGroup( $userid, $groupid );
						}
					}
				}
			}

			$newparams['rebuild'] = 0;
		}

		return $newparams;
	}

	function expiration_action( $params, $metaUser, $plan )
	{
		global $database;

		if ( $params['set_remove_group_exp'] ) {
			$groups = explode( ';', $params['enroll_group'] );
			foreach ( $groups as $groupid ) {
				$this->DeleteUserFromGroup( $metaUser->userid, $groupid );
			}
		}

		if ( $params['set_enroll_group_exp'] ) {
			if ( !empty( $params['enroll_group_exp'] ) ) {
				$gplist = explode( ';', $params['enroll_group_exp'] );
				foreach ( $gplist as $enroll_group_exp) {
					$this->AddUserToGroup( $metaUser->userid, $enroll_group_exp );
				}
			}
		}

		return true;
	}

	function action( $params, $metaUser, $invoice, $plan )
	{
		global $database;

		if ( $params['set_remove_group'] ) {
			$this->DeleteUserFromGroup( $metaUser->userid );
		}

		if ( $params['set_enroll_group'] ) {
			if( !empty( $params['enroll_group'] ) ) {
				$gplist = explode( ';', $params['enroll_group'] );
				foreach( $gplist as $enroll_group) {
					$this->AddUserToGroup( $metaUser->userid, $enroll_group );
				}
			}
		}
	}

	function AddUserToGroup( $userid, $groupid )
	{
		global $database;

		// Check user is not already a member of the group.
		$query = 'SELECT user_id'
		. ' FROM #__juga_u2g'
		. ' WHERE group_id = \'' . $groupid . '\' AND user_id = \''.$userid . '\''
		;

		$database->setQuery( $query );
		$user = $database->loadResult();

		if( $user !== $userid ) {
			// then the user is not already a member of this group and can be set

			$query = 'INSERT INTO #__juga_u2g'
			. ' SET group_id = \'' . $groupid . '\', user_id = \''.$userid . '\''
			;

			$database->setQuery( $query );
			$database->query();

			return true;
		} else {
			return false;
		}
	}

	function DeleteUserFromGroup( $userid, $groupid=null )
	{
		global $database;

		$query = 'DELETE FROM #__juga_u2g'
		. ' WHERE user_id = \''. $userid . '\''
		;

		if ( !empty( $groupid ) ) {
			$query .= ' AND group_id = \''. $groupid . '\'';
		}

		$database->setQuery( $query );
		$database->query();

		return true;
	}
}
?>