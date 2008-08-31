<?php
/**
 * $Id:$
 *
* @author David Deutsch <skore@skore.de> & Team AEC - http://www.globalnerd.org 
* @copyright 2006-2008 Copyright (C) David Deutsch
* @version 0.8
* @package AEC - Account Control Expiration - Membership Manager
 *
 * All rights reserved.  MySMS Component for Joomla!
 *
 * @license GNU/GPL v.2 http://www.gnu.org/licenses/old-licenses/gpl-2.0.html or, at your option, any later version
 * MySMS! is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 *
 */
defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );

/**
 * MicroIntegration for com_mysms
 *
 */
class mi_mysms
{

	function Info()
	{
		$info = array();
		$info['name'] = _AEC_MI_NAME_MYSMS;
		$info['desc'] = _AEC_MI_DESC_MYSMS;

		return $info;
	}

	function Settings()
	{
		$settings = array();
		$settings['add_credits']		= array( 'inputA' );
		$settings['disable_exp']		= array( 'list_yesno' );
		return $settings;
	}

	function expiration_action( $request )
	{
		global $database;

		if ( !empty( $this->settings['disable_exp'] ) ) {
			// unpublish the user
			$query = 'UPDATE #__mysms_joomlauser' .
					' SET `status` = \'0\'' .
					' WHERE `userid` = \'' . $request->metaUser->userid . '\'' .
					' LIMIT 1';
			$database->setQuery( $query );
			$database->query();
		}

		return true;
	}

	function action( $request )
	{
		global $database;

		if ( !empty( $this->settings['add_credits'] ) ) {
			$credits = (int) $this->settings['add_credits'];

			//set the user active and the new credits
			$query = 'UPDATE #__mysms_joomlauser' .
					' SET `state` = \'1\',' .
					' `credits` = credits+' . $credits .
					' WHERE `userid` = \'' . $request->metaUser->userid . '\'' .
					' LIMIT 1';
			$database->setQuery( $query );
			$database->query();
		}

		return true;
	}
}
?>
