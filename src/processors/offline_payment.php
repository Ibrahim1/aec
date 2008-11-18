<?php
/**
 * @version $Id: offline_payment.php
 * @package AEC - Account Control Expiration - Membership Manager
 * @subpackage Processors - Offline Payment
 * @copyright 2006-2008 Copyright (C) David Deutsch
 * @author David Deutsch <skore@skore.de> & Team AEC - http://www.globalnerd.org
 * @license GNU/GPL v.2 http://www.gnu.org/licenses/old-licenses/gpl-2.0.html or, at your option, any later version
 */

// Dont allow direct linking
( defined('_JEXEC') || defined( '_VALID_MOS' ) ) or die( 'Direct Access to this location is not allowed.' );

class processor_offline_payment extends processor
{
	function info()
	{
		$info = array();
		$info['longname']		= _CFG_OFFLINE_PAYMENT_LONGNAME;
		$info['statement']		= _CFG_OFFLINE_PAYMENT_STATEMENT;
		$info['description']	= _CFG_OFFLINE_PAYMENT_DESCRIPTION;
		$info['currencies']		= AECToolbox::_aecCurrencyField( true, true, true, true );
		$info['cc_list']		= "";
		$info['recurring']		= 0;

		return $info;
	}

	function settings()
	{
		$settings = array();
		$settings['info']			= '';
		$settings['waitingplan']	= 0;
		$settings['currency']		='';

		return $settings;
	}

	function backend_settings()
	{
		$settings = array();
		$settings['waitingplan']	= array( 'list_plan' );
		$settings['info']			= array( 'editor' );
		$settings['currency']		= array( 'list_currency' );

		return $settings;
	}

	function invoiceCreationAction( $objInvoice )
	{
		if ( $this->settings['waitingplan'] ) {
			global $database;

			$metaUser = new metaUser( $objInvoice->userid );

			if ( !$metaUser->hasSubscription || in_array( $metaUser->objSubscription->status, array( 'Expired', 'Closed' ) ) ) {
				if ( !$metaUser->hasSubscription ) {
					$payment_plan = new SubscriptionPlan( $database );
					$payment_plan->load( $this->settings['waitingplan'] );

					$metaUser->establishFocus( $payment_plan, 'offline_payment' );
				}

				$metaUser->objSubscription->applyUsage( $this->settings['waitingplan'], 'none', 0 );

				$short	= 'waiting plan';
				$event	= 'Offline Payment waiting plan assigned for ' . $objInvoice->invoice_number;
				$tags	= 'processor,waitingplan';
				$params = array( 'invoice_number' => $objInvoice->invoice_number );

				$eventlog = new eventLog( $database );
				$eventlog->issue( $short, $tags, $event, 2, $params );
			}
		}
	}

}

?>
