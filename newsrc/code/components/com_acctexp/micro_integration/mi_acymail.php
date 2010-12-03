<?php
/**
 * @version $Id: mi_acymail.php
 * @package AEC - Account Control Expiration - Membership Manager
 * @subpackage Micro Integrations - Kunena
 * @copyright Copyright (C) 2010 David Deutsch
 * @author David Deutsch <skore@skore.de> & Team AEC - http://www.valanx.org
 * @license GNU/GPL v.2 http://www.gnu.org/licenses/old-licenses/gpl-2.0.html or, at your option, any later version
 */

// Dont allow direct linking
defined('_JEXEC') OR defined( '_VALID_MOS' ) OR die( 'Direct Access to this location is not allowed.' );

class mi_acymail extends MI
{
	function Info()
	{
		$info = array();
		$info['name'] = _AEC_MI_NAME_ACYMAIL;
		$info['desc'] = _AEC_MI_DESC_ACYMAIL;

		return $info;
	}

	function Settings()
	{
		$settings = array();

		if ( !file_exists( rtrim( JPATH_ROOT, DS ) . DS . 'administrator' . DS . 'components' . DS . 'com_acymailing' . DS . 'helpers' . DS . 'list.php' ) ) {
			echo 'This module can not work without the ACY Mailing Component';

			return $settings;
		}

		$db = &JFactory::getDBO();
		$db->setQuery( 'SELECT * FROM #__acymailing_list' );

		$lists = $db->loadObjectList();

		$listslist = array();
		$listslist[] = JHTML::_('select.option', 0, "--- --- ---" );

		foreach ( $lists as $id => $row ) {
			$listslist[] = JHTML::_('select.option', $row->listid, $row->listid . ': ' . $row->name );
		}

		$settings['addlist']	= array( 'list' );
		$settings['removelist']	= array( 'list' );

		$settings = $this->autoduplicatesettings( $settings );

		foreach ( $settings as $k => $v ) {
			if ( isset( $this->settings[$k] ) ) {
				$settings['lists'][$k]	= JHTML::_( 'select.genericlist', $listslist, $k, 'size="1"', 'value', 'text', $this->settings[$k] );
			} else {
				$settings['lists'][$k]	= JHTML::_( 'select.genericlist', $listslist, $k, 'size="1"', 'value', 'text', '' );
			}
		}

		$xsettings = array();
		$xsettings['user_checkbox']		= array( 'list_yesno' );
		$xsettings['custominfo']		= array( 'inputD' );

		return array_merge( $xsettings, $settings );
	}

	function getMIform( $request )
	{
		$settings = array();

		if ( empty( $this->settings['user_checkbox'] ) ) {
			return $settings;
		}

		if ( !empty( $this->settings['custominfo'] ) ) {
			$settings['exp'] = array( 'p', "", $this->settings['custominfo'] );
		} else {
			$settings['exp'] = array( 'p', "", _MI_MI_ACAJOOM_DEFAULT_NOTICE );
		}

		$settings['get_newsletter'] = array( 'checkbox', 'mi_'.$this->id.'_get_newsletter', 1, 0, "Sign up to our Newsletter" );

		return $settings;
	}

	function relayAction( $request )
	{
		$is_allowed = false;

		if ( empty( $this->settings['user_checkbox'] ) ) {
			$is_allowed = true;
		} elseif ( !empty( $this->settings['user_checkbox'] ) && !empty( $request->params['get_newsletter'] ) ) {
			$is_allowed = true;
		}

		$subscriberid = $this->getSubscriberID( $request, $is_allowed );

		if ( empty( $subscriberid ) ) {
			return null;
		}

		if ( !empty( $this->settings['addlist' . $request->area] ) && $is_allowed ) {
			$this->addToList( $this->settings['addlist' . $request->area], $subscriberid );
		}

		if ( !empty( $this->settings['removelist' . $request->area] ) ) {
			$this->removeFromList( $this->settings['removelist' . $request->area], $subscriberid );
		}
	}

	function getSubscriberID( $request, $create_user )
	{
		$db = &JFactory::getDBO();

		$query = 'SELECT `subid`'
				. ' FROM #__acymailing_subscriber'
				. ' WHERE `userid` = \'' . $request->metaUser->userid . '\''
				;
		$db->setQuery( $query );

		$sid = $db->loadResult();

		if ( empty( $sid ) && $create_user ) {
			$this->createSubscriber( $request );
			
			return $this->getSubscriberID( $request, $create_user );
		} else {
			return $sid;
		}
	}

	function createSubscriber( $request )
	{
		$db = &JFactory::getDBO();

		$query = 'INSERT INTO #__acymailing_subscriber'
				. ' ( `email` , `userid` , `name` , `confirmed`)'
				. ' VALUES (\'' . $request->metaUser->cmsUser->email . '\', \'' . $request->metaUser->userid . '\',\'' . $request->metaUser->cmsUser->name . '\', \'1\')'
				;

		$db->setQuery( $query );
		$db->query();
	}

	function addToList( $list, $user )
	{
		$db = &JFactory::getDBO();

		$query = 'INSERT INTO #__acymailing_listsub'
				. ' ( `listid` , `subid` , `subdate` , `status`)'
				. ' VALUES (\'' . $list . '\', \'' . $user . '\',\'' . time() . '\', \'1\')'
				;

		$db->setQuery( $query );
		$db->query();
	}

	function removeFromList( $list, $user )
	{
		$db = &JFactory::getDBO();

		$query = 'DELETE FROM #__acymailing_listsub'
				. ' WHERE `listid` = \'' . $list . '\''
				. ' AND `subid` = \'' . $user . '\' LIMIT 1'
				;

		$db->setQuery( $query );
		$db->query();
	}

}
?>
