<?php
/**
 * @version $Id: tool_rawedit.php
 * @package AEC - Account Control Expiration - Membership Manager
 * @subpackage Toolbox - Raw Data Edit
 * @copyright 2010 Copyright (C) David Deutsch
 * @author David Deutsch <skore@skore.de> & Team AEC - http://www.valanx.org
 * @license GNU/GPL v.2 http://www.gnu.org/licenses/old-licenses/gpl-2.0.html or, at your option, any later version
 */

// Dont allow direct linking
( defined('_JEXEC') || defined( '_VALID_MOS' ) ) or die( 'Direct Access to this location is not allowed.' );

class tool_rawedit
{
	function Info()
	{
		$info = array();
		$info['name'] = "Raw Data Edit";
		$info['desc'] = "Some Item types (Metauser information, Processors and Invoices) lack a proper edit screen. With this, you can at least edit their raw data.";

		return $info;
	}

	function Settings()
	{
		$db = &JFactory::getDBO();

		$settings = array();

		if ( !empty( $_POST['type'] ) && !empty( $_POST['id'] ) && empty( $_POST['edit'] ) ) {
			$db = &JFactory::getDBO();

			$settings['edit']	= array( 'hidden', 1 );
			$settings['type']	= array( 'hidden', $_POST['type'] );

			$fixed = array();

			switch ( $_POST['type'] ) {
				case 'metauser':
					$fixed = array( 'userid' );

					$object = new metaUserDB( $db );
					break;
				case 'processor':
					$object = new processor( $db );
					break;
				case 'invoice':
					$object = new Invoice( $db );
					break;
			}

			$vars = get_object_vars( $object );

			$encoded = $object->declareParamFields();

			foreach ( $vars as $k => $v ) {
				if ( $k == 'id' ) {
					$settings['id']	= array( 'hidden', $v );
				} elseif ( in_array( $k, $fixed ) ) {
					$settings[$k]	= array( 'p', $k, $k, $v );
				} elseif ( in_array( $k, $encoded ) ) {
					$settings[$k]	= array( 'inputD', $k, $k, jsoonHandler::encode( $v ) );
				} else {
					$settings[$k]	= array( 'inputD', $k, $k, $v );
				}
			}
		} else {
			$settings['type']	= array( 'list', 'Item Type', 'The type of Item you want to edit' );
			$settings['id']		= array( 'inputC', 'Item ID', 'Identification for your Item' );

			$types = array( 'metauser' => 'MetaUser Information', 'processor' => 'Payment Processor', 'invoice' => 'Invoice' );

			$typelist = array();
			foreach ( $types as $type => $typename ) {
				$typelist[] = JHTML::_('select.option', $type, $typename );
			}

			$settings['lists']['type'] = JHTML::_('select.genericlist', $typelist, 'type', 'size="3"', 'value', 'text', array());
		}

		return $settings;
	}

	function Action()
	{
		if ( empty( $_POST['edit'] ) ) {
			return null;
		}

		$db = &JFactory::getDBO();

		switch ( $_POST['type'] ) {
			case 'metauser':
				$object = new metaUserDB( $db );
				break;
			case 'processor':
				$object = new processor( $db );
				break;
			case 'invoice':
				$object = new Invoice( $db );

				$_POST['id'] = AECfetchfromDB::InvoiceIDfromNumber( $_POST['id'] );
				break;
		}

		$object->load( $_POST['id'] );

		if ( $object->id != $_POST['id'] ) {
			return "<h3>Error - could not find item.</h3>";
		}

		$vars = get_object_vars( $object );

		$encoded = $object->declareParamFields();

		foreach ( $vars as $k => $v ) {
			if ( in_array( $k, $encoded ) ) {
				$object->$k	= jsoonHandler::decode( $v );
			} else {
				$object->$k	= $v;
			}
		}

		$object->check();

		if ( $object->store() ) {
			return "<h3>Success! Item updated.</h3>";
		} else {
			return "<h3>Error - could not store item.</h3>";
		}
	}

}
?>
