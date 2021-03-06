<?php
/**
 * @version $Id: tool_processor_dnr.php
 * @package AEC - Account Control Expiration - Membership Manager
 * @subpackage Toolbox - Processor Delete and Replace
 * @copyright 2011-2015 Copyright (C) David Deutsch
 * @author David Deutsch <skore@valanx.org> & Team AEC - http://www.valanx.org
 * @license GNU/GPL v.3 http://www.gnu.org/licenses/gpl.html or, at your option, any later version
 */

// Dont allow direct linking
defined('_JEXEC') or die( 'Direct Access to this location is not allowed.' );

class tool_processor_dnr
{
	public function Info()
	{
		$info = array();
		$info['name'] = "Processor Delete&Replace";
		$info['desc'] = "There is no easy way to delete processors, so here is a complicated way.";

		return $info;
	}

	public function Settings()
	{
		$settings = array();
		$settings['delete']			= array( 'list', 'Delete this', 'This processor will be deleted permanently.', 0 );
		$settings['replace']		= array( 'list', 'Replace it with this', 'All references to the original processor (in plans, subscriptions, invoices and the transaction history) will be replaced with this.', 0 );

		if ( !isset( $_POST['delete'] ) ) {
			$_POST['delete'] = '';
		}

		if ( !isset( $_POST['replace'] ) ) {
			$_POST['replace'] = '';
		}

		$settings['lists']['delete'] = PaymentProcessorHandler::getProcessorSelectList( false, $_POST['delete'], 'delete' );
		$settings['lists']['replace'] = PaymentProcessorHandler::getProcessorSelectList( false, $_POST['replace'], 'replace' );

		return $settings;
	}

	public function Action()
	{
		if ( empty( $_POST['delete'] ) ) {
			return '<p>Please select a processor to remove.</p>';
		}

		if ( empty( $_POST['replace'] ) ) {
			return '<p>Please select a processor to replace the removed processor with.</p>';
		}

		$db = JFactory::getDBO();

		$replacepp = new PaymentProcessor();
		$replacepp->loadName( $_POST['replace'] );

		$deletepp = new PaymentProcessor();
		$deletepp->loadName( $_POST['delete'] );

		$query = 'UPDATE #__acctexp_invoices'
		. ' SET `method` = \'' . $replacepp->processor_name . '\''
		. ' WHERE `method` = \'' . $deletepp->processor_name . '\''
		;
		$db->setQuery( $query );
		$db->query();

		$query = 'UPDATE #__acctexp_subscr'
		. ' SET `type` = \'' . $replacepp->processor_name . '\''
		. ' WHERE `type` = \'' . $deletepp->processor_name . '\''
		;
		$db->setQuery( $query );
		$db->query();

		$query = 'UPDATE #__acctexp_log_history'
		. ' SET `proc_id` = \'' . $replacepp->id . '\', `proc_name` = \'' . $replacepp->processor_name . '\''
		. ' WHERE `proc_name` = \'' . $replacepp->processor_name . '\''
		;
		$db->setQuery( $query );
		$db->query();

		$planlist = SubscriptionPlanHandler::getPlanList();
		foreach ( $planlist as $planid ) {
			$plan = new SubscriptionPlan();
			$plan->load( $planid );

			if ( empty( $plan->params['processors'] ) ) {
				continue;
			}

			if ( in_array( $deletepp->id, $plan->params['processors'] ) ) {
				unset( $plan->params['processors'][array_search( $deletepp->id, $plan->params['processors'] )] );

				if ( !in_array( $replacepp->id, $plan->params['processors'] ) ) {
					$plan->params['processors'][] = $replacepp->id;
				}

				$plan->check();
				$plan->store();
			}
		}

		$query = 'DELETE FROM #__acctexp_config_processors'
		. ' WHERE `id` = \'' . $deletepp->id . '\''
		;
		$db->setQuery( $query );
		$db->query();

		return "<p>Alright, replaced and deleted!</p>";
	}

}
