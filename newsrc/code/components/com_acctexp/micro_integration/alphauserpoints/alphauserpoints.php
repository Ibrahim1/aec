<?php
/**
 * @version $Id: mi_alphauserpoints.php
 * @package AEC - Account Control Expiration - Membership Manager
 * @subpackage Micro Integrations - Alpha User Points
 * @copyright 2006-2015 Copyright (C) David Deutsch
 * @author David Deutsch <skore@valanx.org> & Team AEC - http://www.valanx.org
 * @license GNU/GPL v.3 http://www.gnu.org/licenses/gpl.html or, at your option, any later version
 */

// Dont allow direct linking
defined('_JEXEC') or die( 'Direct Access to this location is not allowed.' );

class mi_alphauserpoints extends MI
{
	public function Info()
	{
		$info = array();
		$info['name'] = JText::_('AEC_MI_NAME_ALPHAUSERPOINTS');
		$info['desc'] = JText::_('AEC_MI_DESC_ALPHAUSERPOINTS');
		$info['type'] = array( 'ecommerce.credits', 'vendor.alphaplug' );

		return $info;
	}

	public function Settings()
	{
		$settings = array();

		$settings['change_points']			= array( 'inputB' );

		$settings = $this->autoduplicatesettings( $settings );

		$xsettings = array();
		$xsettings['checkout_discount']			= array( 'toggle' );
		$xsettings['checkout_conversion']		= array( 'inputB' );

		return array_merge( $xsettings, $settings );
	}

	public function getMIform( $request )
	{
		$settings = array();

		$points = $this->getPoints( $request->metaUser->userid );

		if ( $this->settings['checkout_discount'] && $points ) {
			if ( !empty( $request->invoice->currency ) ) {
				$currency = $request->invoice->currency;
			} else {
				global $aecConfig;

				$currency = $aecConfig->cfg['standard_currency'];
			}

			$value = AECToolbox::formatAmount( $this->settings['checkout_conversion'], $currency, false );
			$total = AECToolbox::formatAmount( ( $points * $this->settings['checkout_conversion'] ), $currency );

			$settings['vat_desc'] = array( 'p', sprintf( JText::_('MI_MI_ALPHAUSERPOINTS_CONVERSION_INFO'), $points, $value, $total ) );
			$settings['use_points'] = array( 'inputC', JText::_('MI_MI_ALPHAUSERPOINTS_USE_POINTS_NAME'), JText::_('MI_MI_ALPHAUSERPOINTS_USE_POINTS_DESC'), '' );

			$settings['validation']['rules'] = array();
			$settings['validation']['rules']['use_points'] = array( 'max' => $this->getPoints( $request->metaUser->userid ) );
		}

		return $settings;
	}

	public function verifyMIform( $request )
	{
		$return = array();

		if ( !empty( $request->params['use_points'] ) ) {
			$request->params['use_points'] = (int) $request->params['use_points'];

			if ( $request->params['use_points'] > $this->getPoints( $request->metaUser->userid ) ) {
				$return['error'] = JText::_('MI_MI_ALPHAUSERPOINTS_ERROR_NOT_ENOUGH');
			}
		}

		return $return;
	}

	public function relayAction( $request )
	{
		if ( $request->action == 'action' ) {
			if ( !empty( $this->settings['plan_apply_first'] ) ) {
				if ( empty( $request->metaUser->objSubscription->previous_plan ) ) {
					$request->area = '_first';
				}
			}
		}

		$invoice = "";
		if ( !empty( $request->invoice->invoice_number ) ) {
			$invoice = $request->invoice->invoice_number;
		}

		if ( $request->action == 'action' ) {
			$params = $request->metaUser->meta->getMIParams( $request->parent->id, $request->plan->id );

			if ( isset($params['use_points_final']) ) {
				$points = $params['use_points_final'];

				unset( $params['use_points_final'] );

				if ( isset($params['use_points']) ) {
					unset( $params['use_points'] );
				}
			} elseif ( $params['use_points'] > 0 ) {
				$points = $params['use_points'];

				unset( $params['use_points'] );
			}

			if ( !empty($points) ) {
				$points = -$points;

				$this->updatePoints( $request->metaUser->userid, $points, 'mi_action_'.$request->action, $invoice );

				$request->metaUser->meta->setMIParams( $request->parent->id, $request->plan->id, $params, true );

				$request->metaUser->meta->storeload();
			}
		}

		if ( empty( $this->settings['change_points'.$request->area] ) ) {
			return null;
		}

		$this->updatePoints( $request->metaUser->userid, $this->settings['change_points'.$request->area], 'mi_action_'.$request->action, $invoice );

		return true;
	}

	public function invoice_item_cost( &$request )
	{
		if ( $this->settings['checkout_discount'] && !empty( $this->settings['checkout_conversion'] ) && !empty( $request->params['use_points'] ) ) {
			return $this->modifyPrice( $request );
		} else {
			return null;
		}
	}

	public function modifyPrice( $request )
	{
		$discount = AECToolbox::correctAmount( $request->params['use_points'] * $this->settings['checkout_conversion'] );

		$original_price = $request->add['terms']->nextterm->renderTotal();

		if ( $discount > $original_price ) {
			$discount = $original_price;

			$request->params['use_points'] = (int) $discount / $this->settings['checkout_conversion'];

			while ( ( $request->params['use_points'] * $this->settings['checkout_conversion'] ) < $original_price ) {
				$request->params['use_points']++;
			}

			$request->params['use_points_final'] = $request->params['use_points'];

			$request->metaUser->meta->setMIParams( $request->parent->id, $request->plan->id, $request->params, true );

			$request->metaUser->meta->storeload();
		}

		$request->add['terms']->nextterm->discount( $discount, null, array( 'details' => $request->params['use_points'] . " Points" ) );

		return true;
	}

	public function getPoints( $userid )
	{
		$db	   = JFactory::getDBO();

		$query = "SELECT points FROM #__alpha_userpoints WHERE `userid`='" . $userid . "'";
		$db->setQuery( $query );

		return $db->loadResult();
	}

	public function updatePoints( $userid, $points, $comment )
	{
		if ( empty( $points ) ) {
			return;
		}

		$db	   = JFactory::getDBO();

		$query = "SELECT id, userid, referreid, points FROM #__alpha_userpoints WHERE `userid`='" . $userid . "'";
		$db->setQuery( $query );

		$aupUser = $db->loadObject();

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
