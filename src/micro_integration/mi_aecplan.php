<?php
/**
 * @version $Id: mi_aecplan.php
 * @package AEC - Account Control Expiration - Membership Manager
 * @subpackage Micro Integrations - AEC Plan Application
 * @copyright 2006-2008 Copyright (C) David Deutsch
 * @author David Deutsch <skore@skore.de> & Team AEC - http://www.valanx.org
 * @license GNU/GPL v.2 http://www.gnu.org/licenses/old-licenses/gpl-2.0.html or, at your option, any later version
 */

// Dont allow direct linking
( defined('_JEXEC') || defined( '_VALID_MOS' ) ) or die( 'Direct Access to this location is not allowed.' );

class mi_aecplan
{
	function Settings()
	{
		global $database;

		$query = 'SELECT `id` AS value, `name` AS text'
				. ' FROM #__acctexp_plans'
				. ' WHERE `active` = 1'
				;
		$database->setQuery( $query );
		$payment_plans = $database->loadObjectList();

		$total_plans = min( max( (count( $payment_plans ) + 1 ), 4 ), 20 );

		$settings = array();

		$settings['plan_apply_first']				= array( 'list' );
		$settings['lists']['plan_apply_first']		= mosHTML::selectList( $payment_plans, 'plan_apply_first', 'size="' . $total_plans, 'value', 'text', $this->settings['plan_apply_first'] );

		$settings['plan_apply']						= array( 'list' );
		$settings['lists']['plan_apply']			= mosHTML::selectList( $payment_plans, 'plan_apply', 'size="' . $total_plans, 'value', 'text', $this->settings['plan_apply'] );

		$settings['plan_apply_pre_exp']				= array( 'list' );
		$settings['lists']['plan_apply_pre_exp']	= mosHTML::selectList( $payment_plans, 'plan_apply_pre_exp', 'size="' . $total_plans, 'value', 'text', $this->settings['plan_apply_pre_exp'] );

		$settings['plan_apply_exp']					= array( 'list' );
		$settings['lists']['plan_apply_exp']		= mosHTML::selectList( $payment_plans, 'plan_apply_exp', 'size="' . $total_plans, 'value', 'text', $this->settings['plan_apply_exp'] );

		return $settings;
	}

	function relayAction( $request, $area )
	{
		if ( $area == '' ) {
			if ( !empty( $this->settings['plan_apply_first'] ) ) {
				if ( empty( $request->metaUser->objSubscription->previous_plan ) ) {
					$area = '_first';
				}
			}
		}

		global $database;

		$new_plan = new SubscriptionPlan( $database );
		$new_plan->load( $this->settings['plan_apply'.$area] );

		$request->metaUser->establishFocus( $new_plan, 'none', false );

		$request->metaUser->focusSubscription->applyUsage( $this->settings['plan_apply'.$area], 'none', 0, 1 );

		return true;
	}
}
?>
