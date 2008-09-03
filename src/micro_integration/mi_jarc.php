<?php
/**
 * @version $Id: mi_jarc.php
 * @package AEC - Account Control Expiration - Membership Manager
 * @subpackage Micro Integrations - jarc
 * @copyright 2006-2008 Copyright (C) David Deutsch
 * @author David Deutsch <skore@skore.de> & Team AEC - http://www.globalnerd.org
 * @license GNU/GPL v.2 http://www.gnu.org/licenses/old-licenses/gpl-2.0.html or, at your option, any later version
 */

// Dont allow direct linking
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

	function Settings()
	{
		global $mosConfig_absolute_path;

		$settings = array();
		$settings['create_affiliates']	= array( 'list_yesno' );
		$settings['log_payments']		= array( 'list_yesno' );

		return $settings;
	}

	function action( $request )
	{
		if ( $this->settings['log_payments'] ) {
			return $this->logpayment( $request->invoice );
		} else {
			return null;
		}
	}

	function on_userchange_action( $request )
	{
		global $database;

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
				' SET `date` = \'' . date( 'Y-m-d H:i:s', time() + $mosConfig_offset_user*3600 ) . '\','
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
