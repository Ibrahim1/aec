<?php
/**
 * @version $Id: mi_jarc.php 16 2007-07-01 12:07:07Z mic $
 * @package AEC - Account Control Expiration - Subscription component for Joomla! OS CMS
 * @subpackage Micro Integrations - jarc
 * @copyright Copyright (C) 2007, All Rights Reserved, David Deutsch
 * @author David Deutsch <skore@valanx.org> & Team AEC - http://www.globalnerd.org
 * @license GNU/GPL v.2 http://www.gnu.org/copyleft/gpl.html
 */

( defined('_JEXEC') || defined( '_VALID_MOS' ) ) or die( 'Direct Access to this location is not allowed.' );

class mi_jarc
{
	function Info()
	{
		$info = array();
		$info['name'] = JText::_('AEC_MI_NAME_JARC');
		$info['desc'] = JText::_('AEC_MI_DESC_JARC');
		$info['type'] = array( 'tracking.affiliate' );

		return $info;
	}

	function Settings( $params=null )
	{
		$app = JFactory::getApplication();

		$settings = array();
		$settings['create_affiliates']	= array( 'toggle' );
		$settings['log_payments']		= array( 'toggle' );
		$settings['log_sales']			= array( 'toggle' );

		return $settings;
	}

	function action( $request )
	{
		return $this->logpayment( $request->invoice );
	}

	function on_userchange_action( $request )
	{
		$db = &JFactory::getDBO();

		if ( !$this->settings['create_affiliates'] ) {
			return null;
		}

		// Only do something on registration
		if ( strcmp( $request->trace, 'registration' ) === 0 ) {
			// Make sure that we do not create a double entry
			if ( !$this->checkaffiliate( $request->row->id ) ) {
				// Create the affiliate
				return $this->createaffiliate( $request->row->id );
			} else {
				return null;
			}
		}

		return true;
	}


	function checkaffiliate( $userid )
	{
		$app = JFactory::getApplication();
		$db = &JFactory::getDBO();

		$query = 'SELECT affiliate_id'
				. ' FROM #__jarc_affiliate_network'
				. ' WHERE `affiliate_id` = \'' . $userid . '\''
				;
		$db->setQuery( $query );

		if ( $db->loadResult() )  {
				return true;
		} else {
				return false;
		}
	}

	function createaffiliate( $userid )
	{
		$app = JFactory::getApplication();
		$db = &JFactory::getDBO();
		$session = &JFactory::getSession();
		// Get affiliate ID from cookie.
		//$cookie_name   = mosMainframe::sessionCookieName() . '_JARC';
		$cookie_name   = $session->getName() . '_JARC';
		$sessioncookie = JRequest::getVar( $cookie_name, null,$_COOKIE );

		list($cookie_aid, $cookie_count) = split(':',$sessioncookie,2);
		$query = 'INSERT INTO #__jarc_affiliate_network'
		. ' SET `affiliate_id` = \'' . $userid . '\','
		. ' `parent_id` = \'' . $cookie_aid . '\''
		;
		$db->setQuery( $query );

		if ( !$db->query() )  {
				return false;
		} else {
				return true;
		}
	}

	function logpayment( $invoice )
	{
		$db = &JFactory::getDBO();
		$session = &JFactory::getSession();
		// Get affiliate ID from cookie.
		//$cookie_name   = mosMainframe::sessionCookieName() . '_JARC';
		$cookie_name   = $session->getName() . '_JARC';
		$sessioncookie = JRequest::getVar( $cookie_name, null, $_COOKIE );
		list($cookie_aid, $cookie_count) = split(':',$sessioncookie,2);

		require_once( JPATH_BASE'/components/com_jarc/jarc.class.php' );
		$affiliate = new jarc_affiliate($db);
		$affiliate->findById( intval($cookie_aid) );

		$query = 'INSERT INTO #__jarc_payments' .
				' SET `date` = \'' . date( 'Y-m-d H:i:s', ( (int) gmdate('U') ) ) . '\','
				. ' `user_id` = \'' . $invoice->userid . '\','
				. ' `payment_type` = \''.$invoice->method.'\','
				. ' `payment_status` = \'2\','
				. ' `amount` = \'' . $invoice->amount . '\','
				. ' `commission_id` = \'' . $affiliate->commission_id . '\','
				. ' `affiliate_id` = \'' . intval($cookie_aid) . '\''
				;
		$db->setQuery( $query );

		if ( !$db->query() ) {
				var_dump( $db );exit();
				return false;
		} else {
				return true;
		}
	}
}

?>