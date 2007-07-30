<?php
/**
 * @version $Id: mi_acajoom.php 16 2007-07-01 12:07:07Z mic $
 * @package AEC - Account Control Expiration - Subscription component for Joomla! OS CMS
 * @subpackage Micro Integrations - AcaJoom
 * @copyright Copyright (C) 2007, All Rights Reserved, David Deutsch
 * @author David Deutsch <skore@skore.de> & Team AEC - http://www.gobalnerd.org
 * @license GNU/GPL v.2 http://www.gnu.org/copyleft/gpl.html
 */

// Dont allow direct linking
defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );

class mi_acajoom
{
	function Info()
	{
		$info = array();
		$info['name'] = _AEC_MI_NAME_ACAJOOM;
		$info['desc'] = _AEC_MI_DESC_ACAJOOM;

		return $info;
	}

	function Settings( $params )
	{
		$query = 'SELECT id, list_name, list_type'
		. ' FROM #__acajoom_lists'
		;
	 	$database->setQuery( $query );
	 	$lists = $database->loadObjectList();

		// Table fields for _acajoom_lists:
		// id, list_name, list_desc, list_type, sendername, senderemail, bounceadres, layout, template, subscribemessage, unsubscribemessage, unsubscribesend, auto_add, user_choose, cat_id, delay_min, delay_max, follow_up, html, hidden, published, createdate, acc_level, acc_id, notification, owner, footer, notify_id, next_date, start_date, params

		$li = array();
		foreach( $lists as $list ) {
			$li[] = mosHTML::makeOption( $list->id, $list->list_name );
		}

		$settings = array();
		$settings['lists']['list']		= mosHTML::selectList($li, 'list', 'size="4"', 'value', 'text', $params['list']);
		$settings['lists']['list_exp']	= mosHTML::selectList($li, 'list_exp', 'size="4"', 'value', 'text', $params['list_exp']);

		$settings['list'] = array( 'list' );
		$settings['list_exp'] = array( 'list' );

		return $settings;
	}

	function expiration_action( $params, $userid, $plan )
	{
		$acauser = $this->getSubscriberID( $userid );

		if ( !$acauser ) {
			$this->createSubscriber( $userid );
			$acauser = $this->getSubscriberID( $userid );
		}

		if ( $this->hasList( $acauser, $params['list'] ) )
			$this->deleteFromList( $acauser, $params['list'] );
		}
	}

	function action( $params, $userid, $plan )
	{
		$acauser = $this->getSubscriberID( $userid );

		if ( !$acauser ) {
			$this->createSubscriber( $userid );
			$acauser = $this->getSubscriberID( $userid );
		}

		if ( !$this->hasList( $acauser, $params['list'] ) )
			$this->addToList( $acauser, $params['list'] );
		}
	}

	function createSubscriber( $userid )
	{
		global $database;

		$user = new mosUser( $database );
		$user->load( $userid );

		$query  = 'INSERT INTO #__acajoom_subscribers'
		. ' (user_id, name, email, receive_html, confirmed, blacklist, timezone, language_iso, subscribe_date, params)'
		. ' VALUES(\'' . $userid . '\', \'' . $user->name . '\', \'' . $user->email . '\', \'1\', \'1\', \'0\', \'00:00:00\', \'eng\', \'' . date( 'Y-m-d H:i:s' ) . '\', \'\' )'
		;
		$database->setQuery( $query );
		$database->query();
	}

	function hasList( $subscriber_id, $listid )
	{
		$query = 'SELECT id'
		. ' FROM #__acajoom_queue'
		. ' WHERE subscriber_id = \'' . $subscriber_id . '\' AND list_id = \'' . $listid . '\''
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
		$query = 'SELECT id'
		. ' FROM #__acajoom_subscribers'
		. ' WHERE userid = \'' . $userid . '\''
		;
		$database->setQuery( $query );
		return $database->loadResult();
	}

	function addToList( $subscriber_id, $list_id )
	{
		$query  = 'INSERT INTO #__acajoom_queue'
		. ' (type, subscriber_id, list_id, mailing_id, issue_nb, send_date, suspend, delay, acc_level, published, params)'
		. ' VALUES(\'1\', \'' . $subscriber_id . '\', \'' . $list_id . '\', \'0\', \'0\', \'0000-00-00 00:00:00\', \'0\', \'0\', \'0\', \'0\', \'\' )'
		;
	}

	function deleteFromList( $subscriber_id, $list_id )
	{
		$query = 'DELETE FROM #__acajoom_queue'
		. ' WHERE subscriber_id = \'' . $subscriber_id . '\' AND list_id = \'' . $listid . '\''
		;
		$database->setQuery( $query );
		$database->query();
	}

}

?>