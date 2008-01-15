<?php
/**
 * $Id:$
 *
* @author Axel Sauerhöfer <axel@willcodejoomlaforfood.de>
* @copyright Copyright &copy; 2007, Axel Sauerhöfer
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
	/**
	 * Return the information about this microintegration, show in backend
	 *
	 * @return array $info
	 */
	function Info()
	{
		$info = array();
		$info['name'] = '_AEC_MI_NAME_MYSMS';
		$info['desc'] = '_AEC_MI_DESC_MYSMS';

		return $info;
	}

	/**
	 * Return the settings, which are configurable in backend.
	 *
	 * The only setting we needed is the amount of credits, which should be
	 * added, when a user subscribe.
	 *
	 * @param array $params
	 * @return array $settings
	 */
	function Settings( $params )
	{
		$settings = array();
		$settings['add_credits']		= array( 'inputA' );
		return $settings;
	}

	/**
	 * Check if our com_mysms is installed, if we find our com_mysms folder,
	 * we return true otherwise false.
	 *
	 * @return bool true if com_mysms is installed, otherwise false
	 */
	function detect_application()
	{
		global $mosConfig_absolute_path;
		return is_dir( $mosConfig_absolute_path . '/components/com_mysms' );
	}

	/**
	 * The subscription is expired, we take the user offline
	 *
	 * @param array $params
	 * @param int $userid
	 * @param array $plan
	 */
	function expiration_action($params, $userid, $plan)
	{
		global $database;
		
		//unpublish the user
		$sql = "UPDATE #__mysms_joomlauser SET state=0 WHERE userid=$userid LIMIT 1";
		$database->setQuery( $sql );
		$database->query();

		return true;
	}

	/**
	 * The customer subscribed to a plan, now mark the user as active
	 */
	function action( $params, $userid, $plan )
	{
		global $database;

		$credits = (int)$params['add_credits'];

		//set the user active and the new credits
		$sql = "UPDATE #__mysms_joomlauser SET state=1, credits=credits+$credits WHERE userid=$userid LIMIT 1";
		$database->setQuery( $sql );
		$database->query();

		return true;
	}
}
?>