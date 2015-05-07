<?php
/**
 * @version $Id: upgrade_1_2_0.inc.php
 * @package AEC - Account Control Expiration - Membership Manager
 * @subpackage Install Includes
 * @copyright 2011-2015 Copyright (C) David Deutsch
 * @author David Deutsch <skore@valanx.org> & Team AEC - http://www.valanx.org
 * @license GNU/GPL v.3 http://www.gnu.org/licenses/gpl.html or, at your option, any later version
 */

// Dont allow direct linking
defined('_JEXEC') or die( 'Direct Access to this location is not allowed.' );

$eucaInstalldb->addColifNotExists( 'hidden', "int(4) NOT NULL default '0'", 'microintegrations' );

$eucaInstalldb->addColifNotExists( 'restrictions', "text NULL", 'microintegrations' );

// Due to a bug around 1.2 development, ALL processors were installed. Frequently.
// This fixes them.
$processors = PaymentProcessorHandler::getInstalledObjectList();

if ( count($processors) > 50 ) {
	$plans = SubscriptionPlanHandler::getPlanList();

	$used_processors = array();
	foreach ( $plans as $planid ) {
		$plan = new SubscriptionPlan();
		$plan->load($planid);

		if ( empty($plan->params['processors']) ) continue;

		foreach ( $plan->params['processors'] as $processor_id ) {
			if ( in_array($processor_id, $used_processors) ) continue;

			$used_processors[] = $processor_id;
		}
	}

	foreach ( $processors as $processor ) {
		if ( in_array( $processor->id, $used_processors ) ) continue;

		$used_proc = new processor();
		$used_proc->load($processor->id);

		$used_proc->delete();
	}
}
