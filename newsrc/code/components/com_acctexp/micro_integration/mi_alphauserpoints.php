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

	function relayAction( $request )
	{
		if ( $request->action == 'action' ) {
			// Do NOT act on regular action call
			return null;
		}

		if ( !isset( $this->settings['plan_apply'.$request->area] ) ) {
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

	function getAlphaUserAccount( $userid )
	{
		$db	   =& JFactory::getDBO();	

		$query = "SELECT id FROM #__alpha_userpoints WHERE `referreid`='" . $userid . "'";
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
