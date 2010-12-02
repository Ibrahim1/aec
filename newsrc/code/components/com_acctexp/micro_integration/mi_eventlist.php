<?php
/**
 * @version $Id: mi_eventlist.php
 * @package AEC - Account Control Expiration - Membership Manager
 * @subpackage Micro Integrations - Event List
 * @copyright Copyright (C) 2010 David Deutsch
 * @author David Deutsch <skore@skore.de> & Team AEC - http://www.valanx.org
 * @license GNU/GPL v.2 http://www.gnu.org/licenses/old-licenses/gpl-2.0.html or, at your option, any later version
 */

// Dont allow direct linking
defined('_JEXEC') OR defined( '_VALID_MOS' ) OR die( 'Direct Access to this location is not allowed.' );

class mi_eventlist extends MI
{
	function Info()
	{
		$info = array();
		$info['name'] = _AEC_MI_NAME_EVENTLIST;
		$info['desc'] = _AEC_MI_DESC_EVENTLIST;

		return $info;
	}

	function Settings()
	{
		$settings = array();

		if ( !@include_once( rtrim( JPATH_ROOT, DS ) . DS . 'components' . DS . 'com_eventlist' . DS . 'helpers' . DS . 'helper.php' ) ) {
			echo 'This module can not work without the Event List Component';
			return $settings;
		}

		$db = &JFactory::getDBO();
		$db->setQuery( 'SELECT * FROM #__eventlist_events WHERE `registra` = 1 AND `published` =1 ' );

		$events = $db->loadObjectList();

		$eventslist = array();
		$eventslist[] = JHTML::_('select.option', 0, "--- --- ---" );

		foreach ( $events as $id => $row ) {
			$eventslist[] = JHTML::_('select.option', $row->id, $row->id . ': ' . $row->title );
		}

		$settings['event']	= array( 'list' );

		$settings = $this->autoduplicatesettings( $settings );

		foreach ( $settings as $k => $v ) {
			if ( isset( $this->settings[$k] ) ) {
				$settings['lists'][$k]	= JHTML::_( 'select.genericlist', $eventslist, $k, 'size="1"', 'value', 'text', $this->settings[$k] );
			} 
		}

		$xsettings = array();

		return array_merge( $xsettings, $settings );
	}

	function relayAction( $request )
	{
		if ( !empty( $this->settings['event' . $request->area] ) ) {
			$this->regEvent( $request->metaUser->userid, $this->settings['event' . $request->area] );
		}
	}

	function regEvent( $userid, $newevent )
	{
		$db = &JFactory::getDBO();

		$query = 'INSERT INTO #__eventlist_register'
				. ' ( `event` , `uid` )'
				. ' VALUES (\'' . $newevent . '\', \'' . $userid . '\')'
				;

		$db->setQuery( $query );
		$db->query();
	}

}
?>