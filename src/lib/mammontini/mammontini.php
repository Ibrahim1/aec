<?php
/**
 * @version $Id: mammontini.php
 * @package Mammontini!: General purpose Payment-related functionality
 * @copyright Copyright (C) 2008 David Deutsch, All Rights Reserved
 * @author David Deutsch <skore@skore.de>
 * @license GNU/GPL v.2 or later http://www.gnu.org/copyleft/gpl.html
 *
 *          _  _ ____ _  _ _  _ ____ __ _ ___ _ __ _ _  /
 *          |\/| |--| |\/| |\/| [__] | \|  |  | | \| | .  v1.0
 *
 * The lean library for the big money processing named after squirrels.
 */

/**
 * Terms Object, collation of a full payment description
 *
 * @author	David Deutsch <mails@globalnerd.org>
 * @package		AEC Component
 * @subpackage	Library - Mammontini!
 * @since 1.0
 */
class mammonTerms extends eucaObject
{
	/**
	 * Do the terms include a Trial?
	 *
	 * @var bool
	 */
	var $hasTrial	= null;

	/**
	 * Remember where the application is at
	 *
	 * @var int
	 */
	var $pointer	= 0;

	/**
	 * Term array
	 *
	 * @var array
	 */
	var $terms		= array();

	/**
	 * Read old style parameters into new style terms
	 *
	 * @access	public
	 * @return	boolean	True on success
	 * @since	1.0
	 */
	function readParams( $params )
	{
		// Old params only had trial and full
		$terms	= array( 'trial_', 'full_' );
		$return	= false;

		foreach ( $terms as $t ) {
			// Make sure this period is actually of substance
			if ( !empty( $params[$t.'period'] ) ) {
				$term = new mammonTerm();

				// If we have a trial, we need to mark this
				if ( $t == 'trial_' ) {
					$this->set( 'hasTrial', true );
					$term->set( 'type', 'trial' );
				} else {
					$term->set( 'type', 'term' );
				}

				if ( $t != 'trial_' && !empty( $params ) ) {
					$term['duration']['lifetime']	= true;
				} else {
					$term['duration']['period']	= $params[$t.'period'];
					$term['duration']['unit']	= $params[$t.'periodunit'];
				}

				if ( $params[$t.'free'] ) {
					$term['cost']['free']	= true;
					$term['cost']['amount']	= '0.00';
				} else {
					$term['cost']['free']	= false;
					$term['cost']['amount']	= $params[$t.'amount'];
				}

				$this->addTerm( $term );
				$return = true;
			}
		}

		return $return;
	}

	/**
	 * add Term to Terms Array
	 *
	 * @access	public
	 * @return	boolean	True on success
	 * @since	1.0
	 */
	function addTerm( $term )
	{
		array_push( $this->terms, $term );
	}
}

/**
 * Term Object, representing one Term in a payment description
 *
 * @author	David Deutsch <mails@globalnerd.org>
 * @package		AEC Component
 * @subpackage	Library - Mammontini!
 * @since 1.0
 */
class mammonTerm extends eucaObject
{
	/**
	 * Term type
	 *
	 * Regular values: trial, standard
	 *
	 * @var string
	 */
	var $type			= null;

	/**
	 * Term start
	 *
	 * @var array
	 */
	var $start			= array();

	/**
	 * Term duration
	 *
	 * @var array
	 */
	var $duration		= array();

	/**
	 * Term costs
	 *
	 * @var array
	 */
	var $cost			= array();

	/**
	 * Human Readable form of term duration
	 *
	 * @access	public
	 * @return	string
	 * @since	1.0
	 */
	function renderDuration()
	{
		echo $this->duration;
	}

	/**
	 * Human Readable form of term cost
	 *
	 * @access	public
	 * @return	string
	 * @since	1.0
	 */
	function renderCost()
	{
		echo $this->cost;
	}
}


?>
