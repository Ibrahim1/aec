<?php
/**
 * @version $Id: mi_jarc.php 16 2007-07-01 12:07:07Z mic $
 * @package AEC - Account Control Expiration - Subscription component for Joomla! OS CMS
 * @subpackage Micro Integrations - jarc
 * @copyright Copyright (C) 2007, All Rights Reserved, David Deutsch
 * @author David Deutsch <skore@skore.de> & Team AEC - http://www.globalnerd.org
 * @license GNU/GPL v.2 http://www.gnu.org/copyleft/gpl.html
 */

defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );

class mi_jarc
{
	function Info()
	{
		$info = array();
		$info['name'] = _AEC_MI_NAME_JARC;
		$info['desc'] = _AEC_MI_DESC_JARC;

		return $info;
	}

	function Settings( $params )
	{
		global $mosConfig_absolute_path;

		$settings = array();
		$settings['create_affiliates']	= array( 'list_yesno' );
		$settings['log_payments']			= array( 'list_yesno' );

		return $settings;
	}

	function action( $params, $metaUser, $invoice, $plan )
	{
		return $this->logpayment( $invoice );
	}

	function on_userchange_action( $params, $row, $post, $trace )
	{
		global $database;

		// Only do something on registration
		if ( strcmp( $trace, 'registration' ) === 0 ) {
			// Make sure that we do not create a double entry
			if ( !$this->checkaffiliate( $row->id ) ) {
				// Create the affiliate
				return $this->createaffiliate( $row->id );
			} else {
				return null;
			}
		}

		return true;
	}

	function checkaffiliate( $userid )
	{
		global $database;

		$query = 'SELECT affiliate_id'
				. ' FROM #__jarc_affiliate_network'
				. ' WHERE `affiliate_id` = \'' . $userid . '\''
				;
		$database->setQuery( $query );

		if ( $database->query() )  {
			return true;
		} else {
			return false;
		}
	}

	function createaffiliate( $userid )
	{
		global $database;

		$query = 'INSERT INTO #__jarc_affiliate_network'
				. ' SET `affiliate_id` = \'' . $userid . '\','
				. ' `parent_id` = \'' . $_SESSION['affId'] . '\''
				;
		$database->setQuery( $query );

		if ( !$database->query() )  {
			return false;
		} else {
			return true;
		}
	}

	function logpayment( $invoice )
	{
		global $mosConfig_offset_user;

		$query = 'INSERT INTO #__jarc_payments' .
				' SET `date` = \'' . gmstrftime ( '%Y-%m-%d %H:%M:%S', time() + $mosConfig_offset_user*3600 ) . '\','
				. ' `user_id` = \'' . $invoice->userid . '\','
				. ' `payment_type` = \''.$invoice->method.'\','
				. ' `payment_status` = \'2\','
				. ' `amount` = \'' . $invoice->amount . '\''
				;
		$database->setQuery( $query );

		if ( !$database->query() ) {
			return false;
		} else {
			return true;
		}
	}
}

?>