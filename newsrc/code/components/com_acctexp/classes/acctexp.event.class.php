<?php
/**
 * @version $Id: acctexp.event.class.php
 * @package AEC - Account Control Expiration - Membership Manager
 * @subpackage Core Class
 * @copyright 2006-2013 Copyright (C) David Deutsch
 * @author David Deutsch <skore@valanx.org> & Team AEC - http://www.valanx.org
 * @license GNU/GPL v.3 http://www.gnu.org/licenses/gpl.html or, at your option, any later version
 */

// Dont allow direct linking
defined('_JEXEC') or die( 'Direct Access to this location is not allowed.' );

class aecEventHandler
{
	static function pingEvents()
	{
		$db = JFactory::getDBO();

		// Load all events happening now or before now
		$query = 'SELECT `id`'
				. ' FROM #__acctexp_event'
				. ' WHERE `due_date` <= \'' . date( 'Y-m-d H:i:s', ( (int) gmdate('U') ) ) . '\''
	 			. ' AND `status` = \'waiting\''
				;
		$db->setQuery( $query );
		$events = xJ::getDBArray( $db );

		// Call each event individually
		foreach ( $events as $evid ) {
			$event = new aecEvent();
			$event->load( $evid );
			$event->trigger();
		}
	}

	// TODO: Finish function that, according to setting, cleans out old entries (like more than two weeks old default)
	public function deleteOldEvents()
	{
		$db = JFactory::getDBO();

		// Load all events happening now or before now
		$query = 'SELECT `id`'
				. ' FROM #__acctexp_event'
				. ' WHERE `due_date` <= \'' . date( 'Y-m-d H:i:s', ( (int) gmdate('U') ) ) . '\''
	 			. ' AND `status` = \'waiting\''
				;
		$db->setQuery( $query );
		$events = xJ::getDBArray( $db );

		// Call each event individually
		foreach ( $events as $evid ) {
			$event = new aecEvent();
			$event->load( $evid );
			$event->trigger();
		}
	}
}

class aecEvent extends serialParamDBTable
{
	/** @var int Primary key */
	var $id					= null;
	/** @var int */
	var $userid				= null;
	/** @var int */
	var $status				= null;
	/** @var string */
	var $type		 		= null;
	/** @var string */
	var $subtype	 		= null;
	/** @var int */
	var $appid				= null;
	/** @var string */
	var $event		 		= null;
	/** @var datetime */
	var $created_date		= null;
	/** @var datetime */
	var $due_date			= null;
	/** @var string */
	var $context 			= array();
	/** @var string */
	var $params 			= array();
	/** @var string */
	var $customparams		= array();

	public function aecEvent()
	{
		parent::__construct( '#__acctexp_event', 'id' );
	}

	public function declareParamFields()
	{
		return array( 'context', 'params', 'customparams' );
	}

	public function issue( $type, $subtype, $appid, $event, $userid, $due_date, $context=array(), $params=array(), $customparams=array() )
	{
		$this->userid			= $userid;
		$this->status			= 'waiting';

		$this->type				= $type;
		$this->subtype			= $subtype;
		$this->appid			= $appid;
		$this->event			= $event;
		$this->created_date 	= date( 'Y-m-d H:i:s', ( (int) gmdate('U') ) );
		$this->due_date 		= $due_date;

		$this->context			= $context;
		$this->params			= $params;
		$this->customparams		= $customparams;

		$this->storeload();

		return $this->id;
	}

	public function trigger()
	{
		if ( empty( $this->type ) ) {
			return null;
		}

		if ( empty( $this->event ) ) {
			return null;
		}

		$obj = null;

		switch ( $this->type ) {
			case 'mi':
				$obj = new microIntegration();
				$obj->load( $this->appid );
				break;
		}

		if ( !empty( $obj ) ) {
			$return = $obj->aecEventHook( $this );

			if ( !is_array( $return ) ) {
				$this->status = 'done';
			} else {
				if ( isset( $return['reset_due_date'] ) ) {
					$this->status	= 'waiting';
					$this->due_date	= $return['reset_due_date'];
				}
			}

			return $this->storeload();
		}

		return true;
	}

}
