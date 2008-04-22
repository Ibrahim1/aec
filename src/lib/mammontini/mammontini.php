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
	 * Is it free?
	 *
	 * @var bool
	 */
	var $free			= null;

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
					$duration['lifetime']	= true;
				} else {
					$duration['period']		= $params[$t.'period'];
					$duration['unit']		= $params[$t.'periodunit'];
				}

				$term->set( 'duration', $duration );

				if ( $params[$t.'free'] ) {
					$term->addCost( '0.00' );
				} else {
					$term->addCost( $params[$t.'amount'] );
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

	/**
	 * get Terms Array
	 *
	 * @access	public
	 * @return	boolean	True on success
	 * @since	1.0
	 */
	function getTerms()
	{
		return $this->terms;
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
	var $cost			= null;

	/**
	 * Is it free?
	 *
	 * @var bool
	 */
	var $free			= null;

	/**
	 * Digestible form of term duration
	 *
	 * @access	public
	 * @return	string
	 * @since	1.0
	 */
	function renderDuration()
	{
		return $this->duration;
	}

	/**
	 * Digestible form of term cost
	 *
	 * @access	public
	 * @return	string
	 * @since	1.0
	 */
	function renderCost()
	{
		if ( count( $this->cost ) <= 2 ) {
			return array( $this->cost[0] );
		} else {
			return $this->cost;
		}
	}

	/**
	 * Adding a cost item, either the root amount, or a discount.
	 * Will automatically compute the total.
	 *
	 * @access	public
	 * @return	string
	 * @since	1.0
	 */
	function addCost( $amount, $info=null )
	{
		$type = 'cost';

		if ( !empty( $this->cost ) ) {
			// Delete current total, if exists
			$cam = count( $this->cost );
			if ( $cam > 1 ) {
				unset( $this->cost[$cam] );
			}

			// Switch this to discount if its negative
			if ( $amount < 0 ) {
				$type = 'discount';
			}
		}

		$cost = new mammonCost();
		$cost->set( 'type', $type );

		if ( !empty( $info ) && is_array( $info ) ) {
			$content = array_merge( array( 'amount' => $amount ), $info );

			$cost->set( 'cost', $content );
		} else {
			$cost->set( 'cost', array( 'amount' => $amount ) );
		}

		array_push( $this->cost, $cost );

		// Compute value of total cost
		$total = 0;
		foreach ( $this->cost as $citem ) {
			$total += $citem->renderCost();
		}

		// Set total cost object
		$cost = new mammonCost();
		$cost->set( 'type', 'total' );
		$cost->set( 'cost', array( 'amount' => $total ) );

		array_push( $this->cost, $cost );

		if ( $cost->isFree() ) {
			$this->free = true;
		}
	}

	/**
	 * add Discount
	 *
	 * @access	public
	 * @return	string
	 * @since	1.0
	 */
	function discount( $amount, $percent=null, $info=null  )
	{
		// Only apply if its not already free
		if ( !$this->cost->isFree() ) {
			// discount amount
			if ( !empty( $amount ) ) {
				$am = 0 - $amount;
				$this->addCost( $am, $info );
			}

			// discount percentage
			if ( !empty( $percent ) ) {
				$total = $this->cost->renderTotal();

				$am = 0 - ( ( $total / 100 ) * $percent );
				$this->addCost( $am, $info );
			}
		}
	}

}

/**
 * Cost Object, representing cost of one term
 *
 * @author	David Deutsch <mails@globalnerd.org>
 * @package		AEC Component
 * @subpackage	Library - Mammontini!
 * @since 1.0
 */
class mammonCost extends eucaObject
{
	/**
	 * Cost type
	 *
	 * Regular values: cost, discount, total
	 *
	 * @var string
	 */
	var $type			= null;

	/**
	 * Costs
	 *
	 * @var array
	 */
	var $cost			= array();

	/**
	 * Simple amount return
	 *
	 * @access	public
	 * @return	string
	 * @since	1.0
	 */
	function renderCost()
	{
		return $this->cost['amount'];
	}

	/**
	 * Simple total return
	 *
	 * @access	public
	 * @return	string
	 * @since	1.0
	 */
	function renderTotal()
	{
		return $this->cost[count($this->cost)]->renderCost();
	}

	/**
	 * Returns true if this costs nothing
	 *
	 * @access	public
	 * @return	string
	 * @since	1.0
	 */
	function isFree()
	{
		if ( $this->renderCost() <= 0 ) {
			return true;
		} else {
			return false;
		}
	}
}

?>