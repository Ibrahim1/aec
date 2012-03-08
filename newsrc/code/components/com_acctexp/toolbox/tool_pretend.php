<?php
/**
 * @version $Id: tool_pretend.php
 * @package AEC - Account Control Expiration - Membership Manager
 * @subpackage Toolbox - Pretend
 * @copyright 2011-2012 Copyright (C) David Deutsch
 * @author David Deutsch <skore@valanx.org> & Team AEC - http://www.valanx.org
 * @license GNU/GPL v.3 http://www.gnu.org/licenses/gpl.html or, at your option, any later version
 */

// Dont allow direct linking
( defined('_JEXEC') || defined( '_VALID_MOS' ) ) or die( 'Direct Access to this location is not allowed.' );

class tool_pretend
{
	function Info()
	{
		$info = array();
		$info['name'] = "Pretend";
		$info['desc'] = "Create artificial membership and sales data.";

		return $info;
	}

	function Settings()
	{
		$settings = array();
		$settings['plans']			= array( 'inputB', 'Plans', 'Number of Membership Plans', 10 );

		return $settings;
	}

	function createPlans( $plans )
	{
		$class = array( 'copper', 'silver', 'titanium', 'gold', 'platinum', 'diamond' );
		$color = array( 'azure', 'scarlet', 'jade', 'lavender', 'mustard' );

		$offset = ( rand(0, (count($class)+count($color))) );

		for ( $i=0; $i<$plans; $i++ ) {
			if ( $plans > count($class) ) {
				
			}

			$name = "Membership";

			$offset++;
		}
	}

	function Action()
	{
		$db = &JFactory::getDBO();

	}

}
?>
