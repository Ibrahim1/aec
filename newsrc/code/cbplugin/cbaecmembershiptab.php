<?php
/**
 * @version $Id: cbaecmembershiptab.php
 * @package AEC - Account Control Expiration - Membership Manager
 * @subpackage CommunityBuilder Eventlog Plugin
 * @copyright 2011 Copyright (C) Anton Skvortsov
 * @author Anton Skvortsov & David Deutsch <skore@skore.de> & Team AEC - http://www.valanx.org
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
		$this->getTab( $tab, $user, $ui, 'frontend' );
	}

	function getEditTab($tab,$user,$ui)
	{
		$this->getTab( $tab, $user, $ui, 'admin' );
	}

	function getTab( $tab, $user, $ui, $location )
	{
		global $_CB_framework;

		$_CB_framework->document->addHeadStyleSheet( '/components/com_comprofiler/plugin/user/plug_aecsubscriptions/cbaecmembershiptab_' . $location . '_tab.css' );

		if ( ( $user->id == $_CB_framework->myId() ) || in_array( $_CB_framework->myUserType(), array( "Administrator", "Super Administrator" ) ) ) {
			return $this->displaySubscriptions( $tab, $user, $ui );
		} else {
			$html_return = '<div style="margin: 10px; padding 10px;">' 
			. '<h4>Account Subscriptions</h4>'
			. $this->params->get('notAuthMessage', 'Account subscriptions can only be viewed by its respective owners.<br/> Open your own profile and instead of this message you will see your subscription details/history')
			. '</div>';

			return $html_return;
		}

	}

	function displaySubscriptions($tab,$user,$ui)
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
		$subscr_info	= $meta_user->getAllCurrentSubscriptionsInfo();

		foreach ( $subscriptions as $i => $subscriptionid ) {
			$subscription = new Subscription( $db );
			$subscription->load( $subscriptionid );

			$html_return .= '<tr>'
			.'<td width="30%" align="center">' . $subscr_info[$i]->name . '</td>'
			.'<td width="10%" align="center">' . ( $subscription->primary ? "Yes" : "No" ) . '</td>'
			.'<td width="10%" align="center">' . $subscription->status . '</td>'
			.'<td width="10%" align="center">' . date( $dateFormat, strtotime($subscription->signup_date) ) . '</td>';

			if ( !empty( $subscription->lifetime ) ) {
				$html_return .=  JText::_('AEC_ISLIFETIME');
			} else {
				if ( $subscription->recurring && ( in_array( $subscription->status, array('Active', 'Trial') ) ) ) {
					$html_return .=  JText::_('AEC_WILLRENEW') . ': ' . date( $dateFormat, strtotime($subscription->expiration) );
				} else {
					$html_return .=  date( $dateFormat, strtotime($subscription->expiration) );
				}
			}

			$html_return .= '</tr>';
		}

		$html_return .= '</table>';

		return $html_return;
	}
}
