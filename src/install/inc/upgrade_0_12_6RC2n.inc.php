<?php
/**
 * @version $Id: upgrade_0_12_6_RC2n.inc.php
 * @package AEC - Account Control Expiration - Membership Manager
 * @subpackage Install Includes
 * @copyright 2006-2008 Copyright (C) David Deutsch
 * @author David Deutsch <skore@skore.de> & Team AEC - http://www.globalnerd.org
 * @license GNU/GPL v.2 http://www.gnu.org/licenses/old-licenses/gpl-2.0.html or, at your option, any later version
 */

// Making up for old thoughts
$database->setQuery("UPDATE #__acctexp_itemxgroup SET group_id='1' WHERE group_id='0'");
$database->query();

// Check for a root group
$database->setQuery("SELECT id FROM  #__acctexp_itemgroups WHERE id='1'");

// Create root group completely out of thin air (tadaa!)
if ( $database->loadResult() != 1 ) {
	$rootgroup = new ItemGroup( $database );

	$rootgroup->id = 0;
	$rootgroup->active = 1;
	$rootgroup->visible = 1;
	$rootgroup->name = _AEC_INST_ROOT_GROUP_NAME;
	$rootgroup->desc = _AEC_INST_ROOT_GROUP_DESC;
	$rootgroup->params = array( 'color' => 'bbddff', 'icon' => 'flag_blue', 'reveal_child_items' => 1 );

	$rootgroup->storeload();

	if ( $rootgroup->id != 1 ) {
		$database->setQuery("UPDATE #__acctexp_itemgroups SET id='1' WHERE id='" . $rootgroup->id . "'");
		$database->query();
	}
}

// Adding in root group relation for all plans
$planlist = SubscriptionPlanHandler::listPlans();

$database->setQuery("SELECT count(*) FROM  #__acctexp_itemxgroup");

if ( count( $planlist ) > $database->loadResult() ) {
	ItemGroupHandler::setChildren( 1, $planlist );
}

// Fixing secondary invoice numbers for CCBill
$query = 'SELECT id FROM #__acctexp_config_processors WHERE name = \'ccbill\'';
$database->setQuery( $query );

$ccid = $database->loadResult();

// Checking whether CCBill is installed at all
if ( $ccid ) {
	// Get all history entries for CCBill
	$query = 'SELECT id FROM #__acctexp_log_history WHERE proc_id = \'' . $ccid . '\'';
	$database->setQuery( $query );

	$list = $database->loadResultArray();

	if ( !empty( $list ) ) {
		foreach ( $list as $hid ) {
			$history = new logHistory( $database );
			$history->load( $hid );

			$params = parameterHandler::decode( stripslashes( $history->response ) );

			// Check for the parameters we need
			if ( isset( $params['subscription_id'] ) && isset( $params['invoice'] ) ) {
				$query = 'UPDATE #__acctexp_invoices SET `secondary_ident` = \'' . $params['subscription_id'] . '\' WHERE invoice_number = \'' . $params['invoice'] . '\'';
				$database->setQuery( $query );
				$database->query();
			}
		}
	}
}

// Haunted by ghosts of xmas past
$query = 'SELECT `id` FROM #__acctexp_subscr WHERE `params` LIKE \'%_jsoon%\'';
$database->setQuery( $query );

$list = $database->loadResultArray();

if ( !empty( $list ) ) {
	foreach ( $list as $sid ) {
		$query = 'SELECT `params` FROM #__acctexp_subscr WHERE `id` = \'' . $sid . '\'';
		$database->setQuery( $query );

		$params = $database->loadResult();
		$decode = stripslashes( str_replace( array( '\n', '\t', '\r' ), array( "\n", "\t", "\r" ), trim($params) ) );
		$temp = jsoonHandler::decode( $decode );

		$query = 'UPDATE #__acctexp_subscr SET `params` = \'' . base64_encode( serialize( $temp ) ) . '\' WHERE `id` = \'' . $sid . '\'';
		$database->setQuery( $query );
		$database->query();
	}
}

/*
// Modifying MI tables for MI Scope

// Make sure we have MIs in the first place

// Loop through MIs putting settings in subarrays (convert old _exp _pre_exp stuff)

// Convert old interpretative pre-expiration into new event table scheme
// Get all MIs that have pre-exp
// Get plans that have that MI
// Get active users with that plan
// Create events for users

// Delete old mi table columns
$eucaInstalldb->dropColifExists( 'auto_check', 'microintegrations' );
$eucaInstalldb->dropColifExists( 'on_userchange', 'microintegrations' );
$eucaInstalldb->dropColifExists( 'pre_exp_check', 'microintegrations' );

*/

?>