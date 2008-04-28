<?php
/**
 * $Id:$
 *
* @author Axel Sauerh�fer <axel@willcodejoomlaforfood.de>
* @copyright Copyright &copy; 2007, Axel Sauerh�fer
* @version 0.8
* @package MySMS
 *
 * All rights reserved.  MySMS Component for Joomla!
 *
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
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

	function Settings( $params )
	{
		$settings = array();
		$settings['add_credits']		= array( 'inputA' );
		$settings['disable_exp']		= array( 'list_yesno' );
		return $settings;
	}

	function expiration_action( $params, $metaUser, $plan, $invoice )
	{
		global $database;

		if ( !empty( $params['disable_exp'] ) ) {
			// unpublish the user
			$query = 'UPDATE #__mysms_joomlauser' .
					' SET `status` = \'0\'' .
					' WHERE `userid` = \'' . $metaUser->userid . '\'' .
					' LIMIT 1';
			$database->setQuery( $query );
			$database->query();
		}

		return true;
	}

	function action( $params, $metaUser, $plan, $invoice )
	{
		global $database;

		if ( !empty( $params['add_credits'] ) ) {
			$credits = (int) $params['add_credits'];

			//set the user active and the new credits
			$query = 'UPDATE #__mysms_joomlauser' .
					' SET `state` = \'1\',' .
					' `credits` = credits+' . $credits .
					' WHERE `userid` = \'' . $metaUser->userid . '\'' .
					' LIMIT 1';
			$database->setQuery( $query );
			$database->query();
		}

		return true;
	}
}
?>