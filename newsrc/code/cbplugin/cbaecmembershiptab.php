<?php
/**
 * @version $Id: cbaecmembershiptab.php
 * @package AEC - Account Control Expiration - Membership Manager
 * @subpackage CommunityBuilder AEC Subscriptions Tab Displayer Plugin
 * @copyright 2011 Copyright (C) Anton Skvortsov
 * @author Anton Skvortsov <anton@starlingwebdevelopment.com> & David Deutsch <skore@skore.de> & Team AEC - http://www.valanx.org
 * @license GNU/GPL v.2 http://www.gnu.org/licenses/old-licenses/gpl-2.0.html or, at your option, any later version
 */

// Dont allow direct linking
( defined('_JEXEC') || defined( '_VALID_MOS' ) ) or die( 'Direct Access to this location is not allowed.' );

class cbaecmembershipTab extends cbTabHandler
{

	function cbaecmembershipTab()
	{
		$this->cbTabHandler();
	}

	function getDisplayTab( $tab, $user, $ui )
	{
		return $this->getTab( $tab, $user, $ui, 'frontend' );
	}

	function getEditTab($tab,$user,$ui)
	{
		return $this->getTab( $tab, $user, $ui, 'admin' );
	}

	function getTab( $tab, $user, $ui, $location )
	{
		global $_CB_framework;

		$_CB_framework->document->addHeadStyleSheet( '/components/com_comprofiler/plugin/user/plug_aecsubscriptions/cbaecmembershiptab_' . $location . '_tab.css' );

		return $this->displaySubscriptions( $tab, $user, $ui );
	}

	function displaySubscriptions( $tab, $user, $ui )
	{
		$db = &JFactory::getDBO();

		include_once( JPATH_SITE . DS . 'components' . DS . 'com_acctexp' . DS . 'acctexp.class.php');

		$dateFormat = $this->params->get('dateFormat', "m/d/Y");

		$meta_user = new metaUser( $user->id );

		$html_return = ''
		.'<table class="aeceventlog_table" style="border-collapse: collapse">'
		.'<tr class="aeceventlog_row_header">'
		.'<th width="30%" align="center">Plan</th>'
		.'<th width="10%" align="center">Primary</th>'
		.'<th width="10%" align="center">Status</th>'
		.'<th width="10%" align="center">Started</th>'
		.'<th width="20%" align="center">Ends</th>'
		.'</tr>';

		$subscriptions	= $meta_user->getAllSubscriptions();
		$subscr_info	= $this->getAllCurrentSubscriptionsInfoComplete( $user->id );

		foreach ( $subscriptions as $i => $subscriptionid ) {
			$subscription = new Subscription( $db );
			$subscription->load( $subscriptionid );

			$plan_name = ($subscr_info[$i]->name == '') ? "Excluded" : $subscr_info[$i]->name;

			$html_return .= '<tr>'
			.'<td width="30%" align="center">' . $plan_name . '</td>'
			.'<td width="10%" align="center">' . ( $subscription->primary ? "Yes" : "No" ) . '</td>'
			.'<td width="10%" align="center">' . $this->_getIconHtml( $subscription->status ) . ' ' . $subscription->status . '</td>'
			.'<td width="10%" align="center">' . date( $dateFormat, strtotime($subscription->signup_date) ) . '</td>';

			$html_return .= '<td width="10%" align="center">';

			if ( !empty( $subscription->lifetime ) ) {
				$html_return .=  JText::_('AEC_ISLIFETIME');
			} else {
				if ( $subscription->recurring && ( in_array( $subscription->status, array('Active', 'Trial') ) ) ) {
					$html_return .=  JText::_('AEC_WILLRENEW') . ': ' . date( $dateFormat, strtotime($subscription->expiration) );
				} else {
					$html_return .=  date( $dateFormat, strtotime($subscription->expiration) );
				}
			}

			$html_return .= '</td></tr>';
		}

		$html_return .= '</table>';

		return $html_return;
	}
	
	function getAllCurrentSubscriptionsInfoComplete( $userid )
	{
		$db = &JFactory::getDBO();

		$query = 'SELECT `a`.`id`, `a`.`plan`, `a`.`expiration`, `a`.`recurring`, `a`.`lifetime`, `b`.`name`'
				. ' FROM #__acctexp_subscr AS a'
				. ' INNER JOIN #__acctexp_plans AS b ON a.plan = b.id'
				. ' WHERE `userid` = \'' . (int) $userid . '\''
				. ' ORDER BY `lastpay_date` DESC';
		$db->setQuery( $query );
		return $db->loadObjectList();
	}
	
	function getIconHtml( $status )
	{
		switch( $status ) {
		case 'Excluded':
			$icon = 'cut_red.png';
			break;
		case 'Trial':
			$icon 	= 'star.png';
			break;
		case 'Pending':
			$icon 	= 'star.png';
			break;
		case 'Active':
			$icon	= 'tick.png';
			break;
		case 'Cancel':
			$icon	= 'exclamation.png';
			break;
		case 'Hold':
			$icon	= 'exclamation.png';
			break;
		case 'Expired':
			$icon	= 'cancel.png';
			break;
		case 'Closed':
			$icon	= 'cancel.png';
			break;
		default:
			$icon = '';
			break;
		}
		
		if($icon != '')
			return '<img src="/media/com_acctexp/images/site/icons/'.$icon.'" class="aecicon" border="0" />';
		else
			return "";
	}
}
