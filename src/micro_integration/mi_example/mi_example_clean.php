<?php
// Dont allow direct linking
defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );

class mi_example
{
	function Info()
	{
		$info = array();
		$info['name'] = '';
		$info['desc'] = '';

		return $info;
	}

	function checkInstallation()
	{
		return true;
	}

	function install()
	{
		return;
	}

	function Settings( $params )
	{
		$settings = array();
		$settings['param1'] = array( 'inputA' );
		$settings['param2'] = array( 'inputD' );

		return $settings;
	}

	function pre_expiration_action( $params, $metaUser, $plan )
	{}

	function expiration_action( $params, $metaUser, $plan )
	{}

	function action( $params, $metaUser, $invoice, $plan )
	{}

	function on_userchange_action( $params, $row, $post, $trace )
	{}

	function delete( $params )
	{}

	function profile_info( $params, $userid )
	{}

}

?>