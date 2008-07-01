<?php
// Update routine 0.3.0 -> 0.6.0
if ( in_array( $mosConfig_dbprefix . "acctexp_payplans", $tables ) ) {
	// Check for existence of 'gid' column on table #__acctexp_payplans
	// It is existent only from version 0.6.0
	$result = null;
	$database->setQuery("SHOW COLUMNS FROM #__acctexp_payplans LIKE 'gid'");
	$database->loadObject($result);
	if (strcmp($result->Field, 'gid') === 0) {
		// You're already running version 0.6.0 or later. No action required.
	} else {
		// You're not running version 0.6.0 or later. Update required.
		$database->setQuery("ALTER TABLE #__acctexp_payplans ADD `gid` int(3) default NULL");
		if ( !$database->query() ) {
	    	$errors[] = array( $database->getErrorMsg(), $query );
		}
	}
}
?>