<?php
/**
 * @version $Id: mi_rstickets.php
 * @package AEC - Account Control Expiration - Membership Manager
 * @subpackage Micro Integrations - RStickets
 * @copyright 2006-2009 Copyright (C) David Deutsch
 * @author David Deutsch <skore@skore.de> & Team AEC - http://www.valanx.org
 * @license GNU/GPL v.2 http://www.gnu.org/licenses/old-licenses/gpl-2.0.html or, at your option, any later version
 */

// Dont allow direct linking
( defined('_JEXEC') || defined( '_VALID_MOS' ) ) or die( 'Direct Access to this location is not allowed.' );

class mi_rstickets extends MI
{
	function Info()
	{
		$info = array();
		$info['name'] = _AEC_MI_NAME_RSTICKETS;
		$info['desc'] = _AEC_MI_DESC_RSTICKETS;

		return $info;
	}

	function Settings()
	{
		$settings = array();
		$settings['userid']				= array( 'inputE' );
		$settings['email']				= array( 'inputE' );
		$settings['department']			= array( 'list' );

		$settings['subject']			= array( 'inputE' );
		$settings['text']				= array( 'inputD' );

		$settings['priority']			= array( 'list' );

		$rewriteswitches				= array( 'cms', 'user', 'expiration', 'subscription', 'plan', 'invoice' );

		$settings						= AECToolbox::rewriteEngineInfo( $rewriteswitches, $settings );

		$this->loadRStickets();

		if ( !isset( $this->settings['department'] ) ) {
			$this->settings['department'] = 0;
		}

		if ( !isset( $this->settings['priority'] ) ) {
			$this->settings['priority'] = 0;
		}

		if ( !function_exists( 'rst_get_departments' ) ) {
			$settings['info']				= array( 'p', 'Notice', 'You need to have RStickets installed to use this MI!' );
		} else {
			$departments = rst_get_departments();

			$deps_list = array();
			foreach ( $departments as $dep ) {
				$deps_list[] = JHTML::_('select.option', $dep['DepartmentId'], $dep['DepartmentPrefix'] . ' - ' . $dep['DepartmentName'] );
			}

			$settings['lists']['department'] = JHTML::_( 'select.genericlist', $deps_list, 'department', 'size="1"', 'value', 'text', $this->settings['department'] );
		}

 		$priorities = array();
		$priorities[] = JHTML::_('select.option', "low", "low" );
		$priorities[] = JHTML::_('select.option', "normal", "normal" );
		$priorities[] = JHTML::_('select.option', "high", "high" );

		$settings['lists']['priority'] = JHTML::_( 'select.genericlist', $priorities, 'priority', 'size="1"', 'value', 'text', $this->settings['priority'] );

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
		}

		return true;
	}

	function loadRStickets()
	{
		if ( file_exists( JPATH_SITE . '/components/com_rstickets/config.php' ) ) {
			require_once( JPATH_SITE . '/components/com_rstickets/config.php' );
			require_once( JPATH_SITE . '/components/com_rstickets/functions.php' );
		}
	}
}
?>
