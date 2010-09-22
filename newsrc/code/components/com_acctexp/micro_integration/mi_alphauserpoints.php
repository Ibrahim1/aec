<?php
/**
 * @version $Id: mi_alphauserpoints.php
 * @package AEC - Account Control Expiration - Membership Manager
 * @subpackage Micro Integrations - Alpha User Points
 * @copyright 2006-2010 Copyright (C) David Deutsch
 * @author David Deutsch <skore@skore.de> & Team AEC - http://www.valanx.org
 * @license GNU/GPL v.2 http://www.gnu.org/licenses/old-licenses/gpl-2.0.html or, at your option, any later version
 */

// Dont allow direct linking
( defined('_JEXEC') || defined( '_VALID_MOS' ) ) or die( 'Direct Access to this location is not allowed.' );

class mi_alphauserpoints
{
	function Settings()
	{
		$settings = array();

		$settings['first_plan_not_membership']		= array( 'list_yesno' );

		$settings['plan_apply_first']				= array( 'list' );
		$settings['lists']['plan_apply_first']		= mosHTML::selectList( $payment_plans, 'plan_apply_first', 'size="' . $total_plans . '"', 'value', 'text', $this->settings['plan_apply_first'] );
		$settings['first_plan_copy_expiration']		= array( 'list_yesno' );

		$settings['plan_apply']						= array( 'list' );
		$settings['lists']['plan_apply']			= mosHTML::selectList( $payment_plans, 'plan_apply', 'size="' . $total_plans . '"', 'value', 'text', $this->settings['plan_apply'] );
		$settings['plan_copy_expiration']			= array( 'list_yesno' );

		$settings['plan_apply_pre_exp']				= array( 'list' );
		$settings['lists']['plan_apply_pre_exp']	= mosHTML::selectList( $payment_plans, 'plan_apply_pre_exp', 'size="' . $total_plans . '"', 'value', 'text', $this->settings['plan_apply_pre_exp'] );

		$settings['plan_apply_exp']					= array( 'list' );
		$settings['lists']['plan_apply_exp']		= mosHTML::selectList( $payment_plans, 'plan_apply_exp', 'size="' . $total_plans . '"', 'value', 'text', $this->settings['plan_apply_exp'] );

		return $settings;
	}

	function relayAction( $request )
	{
		if ( $request->action == 'action' ) {
			// Do NOT act on regular action call
			return null;
		}

		if ( $request->area == 'afteraction' ) {
			// But on after action
			$request->area = '';

			// Or maybe this is a first plan?
			if ( !empty( $this->settings['plan_apply_first'] ) ) {
				if ( !empty( $this->settings['first_plan_not_membership'] ) ) {
					$used_plans = $request->metaUser->meta->getUsedPlans();

					if ( empty( $used_plans ) ) {
						$request->area = '_first';
					} else {
						if ( !in_array( $request->plan->id, $used_plans ) ) {
							$request->area = '_first';
						}
					}
				} else {
					if ( empty( $request->metaUser->objSubscription->previous_plan ) ) {
						$request->area = '_first';
					}
				}
			}
		}

		if ( !isset( $this->settings['plan_apply'.$request->area] ) ) {
			return null;
		}

		if ( empty( $this->settings['plan_apply'.$request->area] ) ) {
			return null;
		}

		if ( $request->action == 'action' ) {
			if ( !empty( $this->settings['plan_apply_first'] ) ) {
				if ( empty( $request->metaUser->objSubscription->previous_plan ) ) {
					$request->area = '_first';
				}
			}
		}

		return true;
	}

	function loadAUP()
	{
		$api_AUP = JPATH_SITE.DS.'components'.DS.'com_alphauserpoints'.DS.'helper.php'; 

		if ( file_exists( $api_AUP ) ) { 
			include_once( $api_AUP ); 

			return true; 
		} else {
			return false;
		}
	}
}
?>
