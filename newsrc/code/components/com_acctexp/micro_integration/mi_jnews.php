<?php
/**
 * @version $Id: mi_jnews.php 16 2007-07-01 12:07:07Z mic $
 * @package AEC - Account Control Expiration - Membership Manager
 * @subpackage Micro Integrations - JNews
 * @copyright 2010 Copyright (C) David Deutsch
 * @author David Deutsch <skore@skore.de> & Team AEC - http://www.valanx.org
 * @license GNU/GPL v.2 http://www.gnu.org/licenses/old-licenses/gpl-2.0.html or, at your option, any later version
 */

// Dont allow direct linking
( defined('_JEXEC') || defined( '_VALID_MOS' ) ) or die( 'Direct Access to this location is not allowed.' );

class mi_jnews
{
	function Info()
	{
		$info = array();
		$info['name'] = _AEC_MI_NAME_JNEWS;
		$info['desc'] = _AEC_MI_DESC_JNEWS;

		return $info;
	}

	function Settings()
	{
		$database = &JFactory::getDBO();

		$query = 'SELECT `id`, `list_name`, `list_type`'
				. ' FROM #__jnews_lists'
				;
	 	$database->setQuery( $query );
	 	$lists = $database->loadObjectList();

		$li = array();
		foreach( $lists as $list ) {
			$li[] = mosHTML::makeOption( $list->id, $list->list_name );
		}

		$settings = array();
		$settings['lists']['list']		= mosHTML::selectList($li, 'list', 'size="4"', 'value', 'text', $this->settings['list']);
		$settings['lists']['list_exp']	= mosHTML::selectList($li, 'list_exp', 'size="4"', 'value', 'text', $this->settings['list_exp']);

		$settings['list']			= array( 'list' );
		$settings['list_exp']		= array( 'list' );
		$settings['user_checkbox']	= array( 'list_yesno' );
		$settings['custominfo']		= array( 'inputD' );

		return $settings;
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
			$settings['exp'] = array( 'p', "", _MI_MI_JNEWS_DEFAULT_NOTICE );
		}

		$settings['get_newsletter'] = array( 'checkbox', 'mi_'.$this->id.'_get_newsletter', 1, 0, "Sign up to our Newsletter" );

		return $settings;
	}

	function expiration_action( $request )
	{
		$is_allowed = false;

		if ( empty( $this->settings['user_checkbox'] ) ) {
			$is_allowed = true;
		} elseif ( !empty( $this->settings['user_checkbox'] ) && !empty( $request->params['get_newsletter'] ) ) {
			$is_allowed = true;
		}

		$acauser = $this->getSubscriberID( $request->metaUser->userid );

		if ( !$acauser && $is_allowed ) {
			$this->createSubscriber( $request->metaUser->userid );
			$acauser = $this->getSubscriberID( $request->metaUser->userid );
		}

		if ( !$acauser ) {
			return null;
		}

		if ( $this->hasList( $acauser, $this->settings['list'] ) ) {
			$this->deleteFromList( $acauser, $this->settings['list'] );
		}

		if ( !$this->hasList( $acauser, $this->settings['list_exp'] ) && $is_allowed ) {
			$this->addToList( $acauser, $this->settings['list_exp'] );
		}
	}

	function action( $request )
	{
		$is_allowed = false;

		if ( empty( $this->settings['user_checkbox'] ) ) {
			$is_allowed = true;
		} elseif ( !empty( $this->settings['user_checkbox'] ) && !empty( $request->params['get_newsletter'] ) ) {
			$is_allowed = true;
		}

		$acauser = $this->getSubscriberID( $request->metaUser->userid );

		if ( !$acauser ) {
			$this->createSubscriber( $request->metaUser->userid );
			$acauser = $this->getSubscriberID( $request->metaUser->userid );
		}

		if ( !$acauser ) {
			return null;
		}

		if ( $this->hasList( $acauser, $this->settings['list_exp'] ) ) {
			$this->deleteFromList( $acauser, $this->settings['list_exp'] );
		}

		if ( !$this->hasList( $acauser, $this->settings['list'] ) && $is_allowed ) {
			$this->addToList( $acauser, $this->settings['list'] );
		}
	}

	function createSubscriber( $userid )
	{
		$database = &JFactory::getDBO();

		$user = new JTableUser( $database );
		$user->load( $userid );

		$query  = 'INSERT INTO #__jnews_subscribers'
				. ' (user_id, name, email, receive_html, confirmed, blacklist, timezone, language_iso, subscribe_date, params)'
				. ' VALUES(\'' . $userid . '\', \'' . $user->name . '\', \'' . $user->email . '\', \'1\', \'1\', \'0\', \'00:00:00\', \'eng\', \'' . date( 'Y-m-d H:i:s' ) . '\', \'\' )'
				;
		$database->setQuery( $query );
		$database->query();
	}

	function hasList( $subscriber_id, $listid )
	{
		$database = &JFactory::getDBO();
		$query = 'SELECT `qid`'
				. ' FROM #__jnews_queue'
				. ' WHERE `subscriber_id` = \'' . $subscriber_id . '\''
				. ' AND `list_id` = \'' . $listid . '\''
				;
		$database->setQuery( $query );
		if ( $database->loadResult() ) {
			return true;
		} else {
			return false;
		}
	}

	function getSubscriberID( $userid )
	{
		$database = &JFactory::getDBO();
		$query = 'SELECT `id`'
				. ' FROM #__jnews_subscribers'
				. ' WHERE `user_id` = \'' . $userid . '\''
				;
		$database->setQuery( $query );
		return $database->loadResult();
	}

	function addToList( $subscriber_id, $list_id )
	{
		$database = &JFactory::getDBO();

		$query  = 'INSERT INTO #__jnews_queue'
				. ' (type, subscriber_id, list_id, mailing_id, issue_nb, send_date, suspend, delay, acc_level, published, params)'
				. ' VALUES(\'1\', \'' . $subscriber_id . '\', \'' . $list_id . '\', \'0\', \'0\', \'' . date( 'Y-m-d H:i:s',  time() + $GLOBALS['mosConfig_offset'] *60*60 ) . '\', \'0\', \'0\', \'0\', \'0\', \'\' )'
				;
		$database->setQuery( $query );
		return $database->query();
	}

	function deleteFromList( $subscriber_id, $list_id )
	{
		$database = &JFactory::getDBO();
		$query = 'DELETE FROM #__jnews_queue'
				. ' WHERE `subscriber_id` = \'' . $subscriber_id . '\''
				. ' AND `list_id` = \'' . $list_id . '\''
				;
		$database->setQuery( $query );
		$database->query();
	}

}

?>