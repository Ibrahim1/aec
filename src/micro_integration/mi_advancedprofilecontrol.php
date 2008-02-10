<?php

defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );

class mi_advancedprofilecontrol
{
	function Info()
	{
		$info = array();
		$info['name'] = _AEC_MI_NAME_APC;
		$info['desc'] = _AEC_MI_DESC_APC;

		return $info;
	}

	function expiration_action( $params, $userid )
	{
		global $database;

		if( $this->integrationActive() ){
			$query = 'SELECT `title`'
					. ' FROM #__comprofiler_accesscontrol_groups `default`'
					;
			$database->setQuery( $query );
			$default = $database->loadResult();

			$query = 'UPDATE #__comprofiler'
					. ' SET `apc_type` = \'' . $default . '\''
					. ' WHERE `id` = \'' . $userid . '\''
					;
			$database->setQuery( $query );
			$database->query();
		}
	}

	function action( $params, $userid )
	{
		global $database;
		$id = $params['id'];

		if( $this->integrationActive() ){
			$query = 'UPDATE #__comprofiler'
					. ' SET `apc_type` = \'' . $id . '\''
					. ' WHERE `id` = \'' . $userid . '\''
					;
			$database->setQuery( $query );
			$database->query();
		}
	}


	function integrationActive()
	{
		global $database;

		$query = 'SELECT `value`'
				. ' FROM #__comprofiler_accesscontrol_settings'
				. ' WHERE `key` = \'integrateSubscription\''
				;
		$database->setQuery( $query );
		return ( $database->loadResult() == 'AEC' );
	}
}

?>