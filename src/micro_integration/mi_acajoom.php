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
	 	$database->setQuery( "SELECT id, list_name, list_type FROM #__acajoom_lists");
	 	$lists = $database->loadObjectList();

		// Table fields for _acajoom_lists:
		// id, list_name, list_desc, list_type, sendername, senderemail, bounceadres, layout, template, subscribemessage, unsubscribemessage, unsubscribesend, auto_add, user_choose, cat_id, delay_min, delay_max, follow_up, html, hidden, published, createdate, acc_level, acc_id, notification, owner, footer, notify_id, next_date, start_date, params

		$li = array();
		foreach ($lists as $list) {
			$li[] = mosHTML::makeOption( $list->id, $list->list_name );
		}

		$settings = array();
		$settings['lists']['list'] = mosHTML::selectList($li, 'list', 'size="4"', 'value', 'text', $params['list']);
		$settings['lists']['list_exp'] = mosHTML::selectList($li, 'list_exp', 'size="4"', 'value', 'text', $params['list_exp']);

		$settings[] = array("inputA", "Name", "description");
		$settings[] = array("inputD", "SET", "description2");

		return $settings;
	}

	function pre_expiration_action($params, $userid, $plan, $mi_id) {
	}

	function expiration_action($params, $userid, $plan) {
	}

	function action($params, $userid, $plan) {
		$database->setQuery( "SELECT * FROM #__acajoom_subscribers WHERE userid='" . $userid . "'" );
		$acauser = $database->loadResultObject();

		// Table fields for _acajoom_subscribers:
		// id, user_id, name, email, receive_html, confirmed, blacklist, timezone, language_iso, subscribe_date, params

		if (!$acauser->id) {
			$database->setQuery( "SELECT * FROM #__acajoom_subscribers WHERE userid='" . $userid . "'" );
			$acauser = $database->loadObject();
		}
	}

	function on_userchange_action($params, $row, $post, $trace) {
	}

	function delete($params) {
	}

}

?>