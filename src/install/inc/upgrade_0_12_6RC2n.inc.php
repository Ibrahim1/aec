<?php
// Adding in root group relation for all plans
$planlist = SubscriptionPlanHandler::listPlans();
aecDebug($planlist);
ItemGroupHandler::setChildren( 0, $planlist );

?>