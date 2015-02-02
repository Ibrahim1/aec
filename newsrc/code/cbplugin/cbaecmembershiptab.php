<?php
/**
 * @version $Id: cbaecmembershiptab.php
 * @package AEC - Account Control Expiration - Membership Manager
 * @subpackage CommunityBuilder AEC Subscriptions Tab Displayer Plugin
 * @copyright 2011-2013 Copyright (C) Anton Skvortsov
 * @author Anton Skvortsov <anton@starlingwebdevelopment.com> & David Deutsch <skore@valanx.org> & Team AEC - http://www.valanx.org
 * @license GNU/GPL v.3 http://www.gnu.org/licenses/gpl.html or, at your option, any later version
 */

// Dont allow direct linking
defined('_JEXEC') or die( 'Direct Access to this location is not allowed.' );

class cbaecmembershipTab extends cbTabHandler
{

	public function cbaecmembershipTab()
	{
		$this->cbTabHandler();
	}

	public function getDisplayTab( $tab, $user, $ui )
	{
		return $this->getTab( $tab, $user, $ui, 'frontend' );
	}

	public function getEditTab($tab,$user,$ui)
	{
		return $this->getTab( $tab, $user, $ui, 'admin' );
	}

	/**
	 * @param string $location
	 */
	public function getTab( $tab, $user, $ui, $location )
	{
		global $_CB_framework;

		$_CB_framework->document->addHeadStyleSheet( '/components/com_comprofiler/plugin/user/plug_aecsubscriptions/cbaecmembershiptab_' . $location . '_tab.css' );

		return $this->displaySubscriptions( $tab, $user, $ui );
	}

	public function displaySubscriptions( $tab, $user, $ui )
	{
		$db = JFactory::getDBO();

		include_once( JPATH_SITE.'/components/com_acctexp/acctexp.class.php');

		$dateFormat = $this->params->get('dateFormat', "m/d/Y");

		$meta_user = new metaUser( $user->id );

		$html_return = ''
		.'<table class="aeceventlog_table" style="border-collapse: collapse">'
		.'<tr class="aeceventlog_row_header">'
		.'<th width="30%" class="text-center">Plan</th>'
		.'<th width="10%" class="text-center">Primary</th>'
		.'<th width="10%" class="text-center">Status</th>'
		.'<th width="10%" class="text-center">Started</th>'
		.'<th width="20%" class="text-center">Ends</th>'
		.'</tr>';

		$subscriptions	= $meta_user->getAllSubscriptions();
		$subscr_info	= $this->getAllCurrentSubscriptionsInfoComplete( $user->id );

		foreach ( $subscriptions as $i => $subscriptionid ) {
			$subscription = new Subscription();
			$subscription->load( $subscriptionid );

			$plan_name = ($subscr_info[$i]->name == '') ? "Excluded" : $subscr_info[$i]->name;

			$html_return .= '<tr>'
			.'<td width="30%" class="text-center">' . $plan_name . '</td>'
			.'<td width="10%" class="text-center">' . ( $subscription->primary ? "Yes" : "No" ) . '</td>'
			.'<td width="10%" class="text-center">' . $this->getIconHtml( $subscription->status ) . ' ' . $subscription->status . '</td>'
			.'<td width="10%" class="text-center">' . date( $dateFormat, strtotime($subscription->signup_date) ) . '</td>';

			$html_return .= '<td width="10%" class="text-center">';

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

	public function getAllCurrentSubscriptionsInfoComplete( $userid )
	{
		$db = JFactory::getDBO();

		$query = 'SELECT `a`.`id`, `a`.`plan`, `a`.`expiration`, `a`.`recurring`, `a`.`lifetime`, `b`.`name`'
				. ' FROM #__acctexp_subscr AS a'
				. ' INNER JOIN #__acctexp_plans AS b ON a.plan = b.id'
				. ' WHERE `userid` = \'' . (int) $userid . '\''
				. ' ORDER BY `lastpay_date` DESC';
		$db->setQuery( $query );
		return $db->loadObjectList();
	}

	/**
	 * @param string $status
	 */
	public function getIconHtml( $status )
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
