<?php
/**
 * @version $Id: mi_attend_events.php
 * @package AEC - Account Control Expiration - Subscription component for Joomla! OS CMS
 * @subpackage Micro Integrations - Attend Events
 * @copyright 2006/2007 Copyright (C) David Deutsch
 * @author David Deutsch <skore@skore.de> & Team AEC - http://www.globalnerd.org
 * @license GNU/GPL v.2 http://www.gnu.org/copyleft/gpl.html
 */

defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );

class mi_attend_events
{
	function Info()
	{
		$info = array();
		$info['name'] = _AEC_MI_NAME_ATTEND_EVENTS;
		$info['desc'] = _AEC_MI_DESC_ATTEND_EVENTS;

		return $info;
	}

	function Settings()
	{
		$settings = array();
		return $settings;
	}

	function action( $request )
	{
		global $database, $mosConfig_live_site, $mosConfig_absolute_path;

		include_once( $mosConfig_absolute_path . '/components/com_attend_events/attend_events.class.php' );

		$database->setQuery("SELECT transaction_id FROM #__events_transactions WHERE ( registration_id = '" . $this->settings['registration_id'] . "' )");
		$transaction_id = $database->loadResult();

		// mark ae invoice as cleared
		$transaction = new comAETransaction( $database );
		$transaction->load( $transaction_id );
		$transaction->bind( $_POST );
		$transaction->gateway = 'Cybermut';
		$transaction->check();
		$transaction->store();

		return true;
	}
}
?>