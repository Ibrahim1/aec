<?php
/**
 * @version $Id: mi_g2.php 16 2007-07-02 13:29:29Z mic $
 * @package AEC - Account Control Expiration - Subscription component for Joomla! OS CMS
 * @subpackage Micro Integrations - G2
 * @copyright 2008 Copyright (C) David Deutsch
 * @author David Deutsch <skore@skore.de> & Team AEC - http://www.globalnerd.org
 * @license GNU/GPL v.2 http://www.gnu.org/copyleft/gpl.html
 */

// Dont allow direct linking
defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );

class mi_g2
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