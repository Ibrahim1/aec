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

		$settings['change_points']			= array( 'inputB' );

		$settings = $this->autoduplicatesettings( $settings );

		$xsettings = array();
		$xsettings['checkout_discount']			= array( 'list_yesno' );
		$xsettings['checkout_conversion']		= array( 'inputB' );

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

	function verifyMIform( $request )
	{
		$return = array();

		$request->params['use_points'] = (int) $request->params['use_points'];

		if ( $request->params['use_points'] > $this->getPoints( $request->metaUser->userid ) ) {
			$return['error'] = "You don't have that many points'";
		}

		return $return;
	}

	function relayAction( $request )
	{
		if ( $request->action == 'action' ) {
			if ( !empty( $this->settings['plan_apply_first'] ) ) {
				if ( empty( $request->metaUser->objSubscription->previous_plan ) ) {
					$request->area = '_first';
				}
			}
		}

		if ( empty( $this->settings['change_points'.$request->area] ) ) {
			return null;
		}

		$this->updatePoints( $metaUser->userid, $this->settings['change_points'.$request->area], $request->invoice->invoice_number );

		return true;
	}

	function invoice_item_cost( $request )
	{
		$this->modifyPrice( $request );

		return true;
	}

	function modifyPrice( $request )
	{
		if ( !isset( $request->params['amt'] ) ) {
			return null;
		}

		$price = AECToolbox::correctAmount( $request->params['amt'] );

		if ( !empty( $this->settings['max'] ) ) {
			if ( $price > $this->settings['max'] ) {
				$price = $this->settings['max'];
			}
		}

		if ( !empty( $this->settings['min'] ) ) {
			if ( $price < $this->settings['min'] ) {
				$price = $this->settings['min'];
			}
		}

		$price = AECToolbox::correctAmount( $price );

		$request->add['terms']->nextterm->setCost( $price );

		return null;
	}

	function getPoints( $userid )
	{
		$db	   =& JFactory::getDBO();

		$query = "SELECT points FROM #__alpha_userpoints WHERE `userid`='" . $userid . "'";
		$db->setQuery( $query );

		return $db->loadResult();
	}

	function updatePoints( $userid, $points, $comment )
	{
		$db	   =& JFactory::getDBO();

		$query = "SELECT id, referreid, points FROM #__alpha_userpoints WHERE `userid`='" . $userid . "'";
		$db->setQuery( $query );

		$aupUser = $db->loadResultObject();

		$query = 'UPDATE #__alpha_userpoints'
				. ' SET `points` = \'' . ( $aupUser->points + $points ) . '\''
				. ' WHERE `userid` = \'' . $aupUser->userid . '\''
				;
		$db->setQuery( $query );
		$db->query();

		$query  = 'INSERT INTO #__alpha_userpoints_details'
				. ' (referreid, points, insert_date, status, rule, approved, datareference)'
				. ' VALUES(\'' . $aupUser->referreid . '\', \'' . $points . '\', \'' . date( 'Y-m-d H:i:s' ) . '\', \'1\', \'1\', \'1\', \'' . $comment . '\' )'
				;
		$db->setQuery( $query );
		$db->query();
	}
}
?>
