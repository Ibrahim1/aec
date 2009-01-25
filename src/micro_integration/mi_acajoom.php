<?php
/**
 * @version $Id: mi_acajoom.php 16 2007-07-01 12:07:07Z mic $
 * @package AEC - Account Control Expiration - Membership Manager
 * @subpackage Micro Integrations - AcaJoom
 * @copyright 2006-2008 Copyright (C) David Deutsch
 * @author David Deutsch <skore@skore.de> & Team AEC - http://www.globalnerd.org
 * @license GNU/GPL v.2 http://www.gnu.org/licenses/old-licenses/gpl-2.0.html or, at your option, any later version
 */

// Dont allow direct linking
( defined('_JEXEC') || defined( '_VALID_MOS' ) ) or die( 'Direct Access to this location is not allowed.' );

class mi_acajoom
{
	function Info()
	{
		$info = array();
		$info['name'] = _AEC_MI_NAME_ACAJOOM;
		$info['desc'] = _AEC_MI_DESC_ACAJOOM;

		return $info;
	}

	function Settings()
	{
		global $database;

		$query = 'SELECT `id`, `list_name`, `list_type`'
				. ' FROM #__acajoom_lists'
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

		$settings['list'] = array( 'list' );
		$settings['list_exp'] = array( 'list' );

		return $settings;
	}

	function expiration_action( $request )
	{
		$acauser = $this->getSubscriberID( $request->metaUser->userid );

		if ( !$acauser ) {
			$this->createSubscriber( $request->metaUser->userid );
			$acauser = $this->getSubscriberID( $request->metaUser->userid );
		}

		if ( $this->hasList( $acauser, $this->settings['list'] ) ) {
			$this->deleteFromList( $acauser, $this->settings['list'] );
		}
	}

	function action( $request )
	{
		$acauser = $this->getSubscriberID( $request->metaUser->userid );

		if ( !$acauser ) {
			$this->createSubscriber( $request->metaUser->userid );
			$acauser = $this->getSubscriberID( $request->metaUser->userid );
		}

		if ( !$this->hasList( $acauser, $this->settings['list'] ) ) {
			$this->addToList( $acauser, $this->settings['list'] );
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
		global $database;
		$query = 'SELECT `qid`'
				. ' FROM #__acajoom_queue'
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
		global $database;
		$query = 'SELECT `id`'
				. ' FROM #__acajoom_subscribers'
				. ' WHERE `user_id` = \'' . $userid . '\''
				;
		$database->setQuery( $query );
		return $database->loadResult();
	}

	function addToList( $subscriber_id, $list_id )
	{
		global $database;

		$query  = 'INSERT INTO #__acajoom_queue'
				. ' (type, subscriber_id, list_id, mailing_id, issue_nb, send_date, suspend, delay, acc_level, published, params)'
				. ' VALUES(\'1\', \'' . $subscriber_id . '\', \'' . $list_id . '\', \'0\', \'0\', \'0000-00-00 00:00:00\', \'0\', \'0\', \'0\', \'0\', \'\' )'
				;
		$database->setQuery( $query );
		return $database->query();
	}

	function deleteFromList( $subscriber_id, $list_id )
	{
		global $database;
		$query = 'DELETE FROM #__acajoom_queue'
				. ' WHERE `subscriber_id` = \'' . $subscriber_id . '\''
				. ' AND `list_id` = \'' . $list_id . '\''
				;
		$database->setQuery( $query );
		$database->query();
	}

}

?>
