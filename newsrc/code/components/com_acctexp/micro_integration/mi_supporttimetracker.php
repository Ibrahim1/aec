<?php
/**
 * @version $Id: mi_supporttimetracker.php
 * @package AEC - Account Control Expiration - Membership Manager
 * @subpackage Micro Integrations - Support Time tracker
 * @copyright 2006-2009 Copyright (C) David Deutsch
 * @author David Deutsch <skore@skore.de> & Team AEC - http://www.valanx.org
 * @license GNU/GPL v.2 http://www.gnu.org/licenses/old-licenses/gpl-2.0.html or, at your option, any later version
 */

// Dont allow direct linking
( defined('_JEXEC') || defined( '_VALID_MOS' ) ) or die( 'Direct Access to this location is not allowed.' );

class mi_supporttimetracker extends MI
{
	function Info()
	{
		$info = array();
		$info['name'] = _AEC_MI_NAME_SUPPORTTIMETRACKER;
		$info['desc'] = _AEC_MI_DESC_SUPPORTTIMETRACKER;

		return $info;
	}

	function Settings()
	{
		$settings = array();
		$settings['add_minutes']		= array( 'inputE' );
		$settings['email']				= array( 'inputE' );
		$settings['department']			= array( 'list' );

		$settings['subject']			= array( 'inputE' );
		$settings['text']				= array( 'inputD' );

		$settings['responsible_admin']	= array( 'inputC' );
		$settings['send_readout']		= array( 'list_yesno' );
		$settings['clear_readout']		= array( 'list_yesno' );

		$rewriteswitches				= array( 'cms', 'user', 'expiration', 'subscription', 'plan', 'invoice' );
		$settings['rewriteInfo']		= array( 'fieldset', _AEC_MI_SET11_EMAIL, AECToolbox::rewriteEngineInfo( $rewriteswitches ) );

		return $settings;
	}

	function Defaults()
	{
		$defaults = array();
		$defaults['userid']	= "[[user_id]]";
		$defaults['email']	= "[[user_email]]";

		return $defaults;
	}

	function relayAction( $request )
	{
		if ( $request->action == 'action' ) {
			$this->loadRStickets();

			$text		= AECToolbox::rewriteEngineRQ( $this->settings['text'], $request );
			$subject	= AECToolbox::rewriteEngineRQ( $this->settings['subject'], $request );

			$userid		= AECToolbox::rewriteEngineRQ( $this->settings['userid'], $request );
			$email		= AECToolbox::rewriteEngineRQ( $this->settings['email'], $request );

			$r = rst_add_ticket( $this->settings['department'], $subject, $text, $this->settings['priority'], $userid, $email, 0, array(), true );

			aecDebug($r);
		}

		return true;
	}

	function admin_form( $request )
	{
		$database = &JFactory::getDBO();

		$settings = array();

		$settings['log_minutes'] = array( 'inputC', _MI_MI_SUPPORTTIMETRACKER_ADMIN_LOG_MINUTES_NAME, _MI_MI_SUPPORTTIMETRACKER_ADMIN_LOG_MINUTES_NAME );

		return $settings;
	}

}
?>
