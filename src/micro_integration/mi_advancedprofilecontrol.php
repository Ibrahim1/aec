<?php

defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );

class mi_advancedprofilecontrol{

	function expiration_action($params, $userid) {
		global $database;
		$database->setQuery("select value from #__comprofiler_accesscontrol_settigns where key='integrateSubscription'");
		if($database->loadResult()=='AEC'){
			$database->setQuery("select `title` from #__comprofiler_accesscontrol_groups `default`");
			$default=$database->loadResult();
			$database->setQuery("update #__comprofiler set apc_type='$default' where id=$userid");
			$database->query();
		}
	}
	function action($params, $userid) {
		global $database;
		$id=$params['id'];
		$database->setQuery("select `value` from #__comprofiler_accesscontrol_settings where `key`='integrateSubscription'");
		if($database->loadResult()=='AEC'){
			$database->setQuery("update #__comprofiler set apc_type='$id' where id=$userid");
			$database->query();
		}
	}
	function Info(){
		return "Micro integration for Advanced Profile Control";
	}
}

?>