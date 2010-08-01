<?php
/**
 * @version $Id: tool_supporthours.php
 * @package AEC - Account Control Expiration - Membership Manager
 * @subpackage Toolbox - Support Hours
 * @copyright 2010 Copyright (C) David Deutsch
 * @author David Deutsch <skore@skore.de> & Team AEC - http://www.valanx.org
 * @license GNU/GPL v.2 http://www.gnu.org/licenses/old-licenses/gpl-2.0.html or, at your option, any later version
 */

// Dont allow direct linking
( defined('_JEXEC') || defined( '_VALID_MOS' ) ) or die( 'Direct Access to this location is not allowed.' );

class tool_supporthours
{
	function Info()
	{
		$info = array();
		$info['name'] = "Support Hours";
		$info['desc'] = "Gets rid of subscriptions for users that don't exist anymore.";

		return $info;
	}

	function Settings()
	{
		return array();
	}

	function Action()
	{
		$database = &JFactory::getDBO();

		$query = 'SELECT `id`'
				. ' FROM #__acctexp_microintegrations'
				. ' WHERE `class_name` LIKE \'%mi_supporttimetracker%\''
				;
		$database->setQuery( $query );
		$mis = $database->loadResultArray();

		$planlist = array();
		foreach ( $mis as $mi ) {
			$plans = MicroIntegrationHandler::getPlansbyMI( $mi );

			$planlist = array_merge( $planlist, $plans );
		}

		$planlist = array_unique( $planlist );

		$userlist = array();
		foreach ( $planlist as $planid ) {
			$users = SubscriptionPlanHandler::getPlanUserlist( $planid );

			$userlist = array_merge( $userlist, $users );
		}

		$userlist = array_unique( $userlist );

		$historylist = array();
		foreach ( $userlist as $userid ) {
			$metaUser = new metaUser( $userid );

			$uparams = $metaUser->meta->getCustomParams();

			if ( !empty( $uparams['support_minutes_history'] ) ) {
				if ( is_array( $uparams['support_minutes_history'] ) ) {
					foreach( $uparams['support_minutes_history'] as $history ) {
						if ( !empty( $history['userid'] ) ) {
							$historylist[$history['userid']][] = $history['support_minutes_used'] . ' - ' . $history['details'];
						}
					}
				}
			}
		}

		return "<pre>" . obsafe_print_r( $historylist, true) . "</pre>";
	}

}
?>
