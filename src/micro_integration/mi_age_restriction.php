<?php
/**
 * @version $Id: mi_age_restriction.php
 * @package AEC - Account Control Expiration - Membership Manager
 * @subpackage Micro Integrations - Age Restriction
 * @copyright 2006-2008 Copyright (C) David Deutsch
 * @author David Deutsch <skore@skore.de> & Team AEC - http://www.valanx.org
 * @license GNU/GPL v.2 http://www.gnu.org/licenses/old-licenses/gpl-2.0.html or, at your option, any later version
 */

// Dont allow direct linking
( defined('_JEXEC') || defined( '_VALID_MOS' ) ) or die( 'Direct Access to this location is not allowed.' );

class mi_age_restriction extends MI
{
	function Settings()
	{
		global $database;

        $settings = array();
		$settings['min_age']			= array( 'inputA' );
		$settings['max_age']			= array( 'inputA' );
		$settings['restrict_calendar']	= array( 'list_yesno' );

		return $settings;
	}

	function getMIform()
	{
		global $database;

		$settings = array();

		$settings['birthday'] = array( 'inputC', _MI_MI_AGE_RESTRICTION_USERSELECT_BIRTHDAY_NAME, _MI_MI_AGE_RESTRICTION_USERSELECT_BIRTHDAY_DESC );

		return $settings;
	}

	function verifyMIform( $params )
	{
		global $database;

		$return = array();

		if ( empty( $params['birthday'] ) ) {
			$return['error'] = "Please fill in your date of birth";
			return $return;
		}

		$age = $this->getAge( $params['birthday'] );

		if ( empty( $age ) ) {
			$return['error'] = "Please fill in your date of birth";
			return $return;
		}

		if ( !empty( $this->settings['min_age'] ) ) {
			if ( $age < $this->settings['min_age'] ) {
				$return['error'] = "You must be at least " . $this->settings['min_age'] . " years old!";
				return $return;
			}
		}

		if ( !empty( $this->settings['max_age'] ) ) {
			if ( $age > $this->settings['max_age'] ) {
				$return['error'] = "You cannot purchase this if you are older than " . $this->settings['max_age'] . " years!";
				return $return;
			}
		}

		return $return;
	}

	function relayAction( $request, $area )
	{
		if ( ( $area == '' ) && !empty( $this->settings['max_age'] ) ) {
			$age = $this->getAge( $request->params['birthday'] );

			$due_date = strtotime( "+" . $this->settings['max_age'] . " years", strtotime( $request->params['birthday'] ) );

			$event_id = $this->issueUniqueEvent( $request, 'BirthdayExpiration', date( 'Y-m-d H:i:s', $due_date ) );
		}

		return true;
	}

	function aecEventHookBirthdayExpiration( $request )
	{
		if ( !empty( $request->metaUser->focusSubscription->id ) ) {
			return $request->metaUser->focusSubscription->expire();
		}

		return null;
	}

	function getAge( $bd )
	{
		return round( abs( ( time() - strtotime( $bd ) ) / 31556926 ) );
	}
}
?>
