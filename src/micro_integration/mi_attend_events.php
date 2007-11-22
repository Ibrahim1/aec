<?php
/**
 * @version $Id: mi_attend_events.php
 * @package AEC - Account Control Expiration - Subscription component for Joomla! OS CMS
 * @subpackage Micro Integrations - Attend Events
 * @copyright 2006/2007 Copyright (C) David Deutsch
 * @author David Deutsch <skore@skore.de> & Team AEC - http://www.gobalnerd.org
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

	function Settings( $params )
	{
		$settings = array();
		$settings['setupinfo'] = array( 'fieldset' );
		return $settings;
	}


	function action( $params, $userid, $plan )
	{
		global $database, $mosConfig_live_site;



		return true;
	}
}
?>