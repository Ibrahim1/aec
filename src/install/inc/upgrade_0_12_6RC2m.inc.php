<?php
$query = 'SELECT `id`'
		. ' FROM #__acctexp_metauser'
		;
$database->setQuery( $query );
$entries = $database->loadResultArray();

/*
 * This may seem odd, but due to unforseen consequences, json encoding and decoding
 * actually fixes some numeric properties so that we can switch them over to arrays,
 * which is done with get_object_vars as its the quickest AND, uhm, dirtiest method.
 * without the encoding and decoding, get_object_vars just purrs out an empty array.
 */

foreach ( $entries as $eid ) {
	$meta = new metaUserDB( $database );
	$meta->load( $eid );

	if ( !empty( $meta->params ) ) {
		if ( is_object( $meta->params ) ) {
			if ( is_object( $meta->params->mi ) ) {
				$new = get_object_vars( json_decode( json_encode( $meta->params->mi ) ) );

				$meta->params->mi = $new;
			}
		}
	}

	if ( !empty( $meta->plan_params ) ) {
		if ( is_object( $meta->plan_params ) ) {
			if ( is_object( $meta->plan_params ) ) {
				$temp = get_object_vars( json_decode( json_encode( $meta->plan_params ) ) );

				$new = array();
				foreach( $temp as $pid => $param ) {
					$new[$pid] = get_object_vars( json_decode( json_encode( $param ) ) );
				}

				$meta->plan_params = $new;
			}
		}
	}

	$meta->check();
	$meta->store();
}

// Adding in root group relation for all plans
$planlist = SubscriptionPlanHandler::listPlans();

ItemGroupHandler::setChildren( 0, $planlist );

?>