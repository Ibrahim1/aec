<?php
/**
 * @version $Id: upgrade_0_12_6_RC2n.inc.php
 * @package AEC - Account Control Expiration - Membership Manager
 * @subpackage Install Includes
 * @copyright 2006-2008 Copyright (C) David Deutsch
 * @author David Deutsch <skore@skore.de> & Team AEC - http://www.globalnerd.org
 * @license GNU/GPL v.2 http://www.gnu.org/licenses/old-licenses/gpl-2.0.html or, at your option, any later version
 */

// Adding in root group relation for all plans
$planlist = SubscriptionPlanHandler::listPlans();

$database->setQuery("SELECT count(*) FROM  #__acctexp_itemxgroup");

if ( count( $planlist ) > $database->loadResult() ) {
	ItemGroupHandler::setChildren( 0, $planlist );
}

?>