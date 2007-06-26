<?php
// Dont allow direct linking
defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );

class mi_example {

	function checkInstallation () {
		return true;
	}

	function install () {
		return;
	}

	function Settings ( $params ) {
		$settings = array();
		$settings[] = array("inputA", "Name", "description");
		$settings[] = array("inputD", "SET", "description2");

		return $settings;
	}

	function pre_expiration_action($params, $userid, $plan, $mi_id) {
	}

	function expiration_action($params, $userid, $plan) {
	}

	function action($params, $userid, $plan) {
	}

	function on_userchange_action($params, $row, $post, $trace) {
	}

	function delete($params) {
	}

}

?>