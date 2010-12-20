<?php
/**
 * @version $Id: mi_alphauserpoints.php
 * @package AEC - Account Control Expiration - Membership Manager
 * @subpackage Micro Integrations - Alpha User Points
 * @copyright 2006-2010 Copyright (C) David Deutsch
 * @author David Deutsch <skore@skore.de> & Team AEC - http://www.valanx.org
 * @license GNU/GPL v.2 http://www.gnu.org/licenses/old-licenses/gpl-2.0.html or, at your option, any later version
 */

// Dont allow direct linking
( defined('_JEXEC') || defined( '_VALID_MOS' ) ) or die( 'Direct Access to this location is not allowed.' );

class mi_alphauserpoints extends MI
{
	function Info()
	{
		$info = array();
		$info['name'] = "Alpha User Points";
		$info['desc'] = "Granting or Charging points, as well as giving the user the option to 'pay' with points.";

		return $info;
	}

	function Settings()
	{
		$settings = array();

		$settings['add_points']			= array( 'inputB' );
		$settings['subtract_points']	= array( 'inputB' );

		$settings = $this->autoduplicatesettings( $settings );

		$xsettings = array();
		$xsettings['aup_checkout_discount']			= array( 'list_yesno' );
		$xsettings['aup_checkout_showconversion']	= array( 'list_yesno' );
		$xsettings['aup_checkout_conversion']		= array( 'inputB' );

		return array_merge( $xsettings, $settings );
	}

	function getMIform( $request )
	{
		$settings = array();

		if ( $this->settings['aup_checkout_discount'] ) {
			$settings['vat_desc'] = array( 'p', "", sprintf( _MI_MI_ALPHAUSERPOINTS_CONVERSION_INFO ) );
			$settings['use_points'] = array( 'inputC', _MI_MI_ALPHAUSERPOINTS_USE_POINTS_NAME, _MI_MI_ALPHAUSERPOINTS_USE_POINTS_DESC, '' );
		}

		return $settings;
	}

	function relayAction( $request )
	{
		if ( $request->action == 'action' ) {
			// Do NOT act on regular action call
			return null;
		}

		if ( empty( $this->settings['plan_apply'.$request->area] ) ) {
			return null;
		}

		if ( $request->action == 'action' ) {
			if ( !empty( $this->settings['plan_apply_first'] ) ) {
				if ( empty( $request->metaUser->objSubscription->previous_plan ) ) {
					$request->area = '_first';
				}
			}
		}

		return true;
	}

	function invoice_item_cost( $request )
	{
		// 
		print_r($request);

		return $request;
	}

	function getAlphaUserAccount( $userid )
	{
		$db	   =& JFactory::getDBO();	

		$query = "SELECT id FROM #__alpha_userpoints WHERE `userid`='" . $userid . "'";
		$db->setQuery( $query );

		$referrerUser = $db->loadResult();
		
		JTable::addIncludePath(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_alphauserpoints'.DS.'tables');				
		$row =& JTable::getInstance('userspoints');
			
		// update points into alpha_userpoints table
		$row->load( intval($referrerUser) );
	}

	function AlphaUserPoints( $AUPObject )
	{
		
	}
}
?>
