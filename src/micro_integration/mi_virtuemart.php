<?php
/**
 * @version $Id: mi_mysql_query.php 16 2007-07-02 13:29:29Z mic $
 * @package AEC - Account Control Expiration - Subscription component for Joomla! OS CMS
 * @subpackage Micro Integrations - MySQL Query
 * @copyright 2006/2007 Copyright (C) David Deutsch
 * @author David Deutsch <skore@skore.de> & Team AEC - http://www.gobalnerd.org
 * @license GNU/GPL v.2 http://www.gnu.org/copyleft/gpl.html
 */

defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );

class mi_virtuemart {

	function Info () {
		$info = array();
		$info['name'] = _AEC_MI_NAME_VIRTM;
		$info['desc'] = _AEC_MI_DESC_VIRTM;

		return $info;
	}

	function Settings( $params ) {
		global $database;

		$query = 'SELECT shopper_group_id, shopper_group_name'
		. ' FROM #__vm_shopper_group'
		;
	 	$database->setQuery( $query );
	 	$shopper_groups = $database->loadObjectList();

		$sg = array();
		foreach( $shopper_groups as $group ) {
			$sg[] = mosHTML::makeOption( $group->shopper_group_id, $group->shopper_group_name );
		}

		$settings = array();
		$settings['lists']['shopper_group']		= mosHTML::selectList( $sg, 'shopper_group', 'size="4"', 'value', 'text',
												$params['shopper_group'] );
		$settings['lists']['shopper_group_exp'] = mosHTML::selectList( $sg, 'shopper_group_exp', 'size="4"', 'value', 'text',
												$params['shopper_group_exp'] );

		$settings['set_shopper_group']			= array( 'list_yesno' );
		$settings['shopper_group']				= array( 'list' );
		$settings['set_shopper_group_exp']		= array( 'list_yesno' );
		$settings['shopper_group_exp']			= array( 'list' );

		return $settings;
	}

	function expiration_action( $params, $userid, $plan ) {
		global $database;

		if( $params['set_shopper_group_exp'] ) {
			// Check vendor relationship
			$query = 'SELECT vendor_id'
			. ' FROM #__vm_auth_user_vendor'
			. ' WHERE user_id = \'' . $userid . '\''
			;
			$database->setQuery( $query );
			$vendor = $database->loadResult();

			// Insert Shopper -ShopperGroup - Relationship
			$query  = 'UPDATE #__vm_shopper_vendor_xref'
			. ' SET shopper_group_id = \'' . $params['shopper_group_exp'] . '\''
			. ' WHERE user_id = \'' . $userid . '\''
			;
			$database->setQuery( $query );
			$database->query();

			return true;
		}else{
			return false;
		}
	}

	function action( $params, $userid, $plan ) {
		global $database;

		if( $params['set_shopper_group'] ) {
			// Check vendor relationship
			$query = 'SELECT vendor_id'
			. ' FROM #__vm_auth_user_vendor'
			. ' WHERE user_id = \'' . $userid . '\''
			;
			$database->setQuery( $query );
			$vendor = $database->loadResult();

			// Insert Shopper -ShopperGroup - Relationship
			$query  = 'UPDATE #__vm_shopper_vendor_xref'
			. ' SET shopper_group_id = \'' . $params['shopper_group'] . '\''
			. ' WHERE user_id = \'' . $userid . '\''
			;
			$database->setQuery( $query );
			$database->query();

			return true;
		}else{
			return false;
		}
	}
}
?>