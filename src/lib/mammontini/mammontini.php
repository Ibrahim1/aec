<?php
/**
 * @version $Id: mammontini.php
 * @package Mammontini: General purpose Payment-related functionality
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
 * Terms Object, representing the total of 
 *
 * @author	Louis Landry <louis.landry@joomla.org>
 * @package		Joomla
 * @subpackage	Content
 * @since 1.5
 */
class mammonTerms extends eucaObject
{
	/**
	 * Do the terms include a Trial?
	 *
	 * @var bool
	 */
	var $hasTrial		= null;

	/**
	 * At which point is the application at
	 *
	 * @var int
	 */
	var $pointer		= null;

	/**
	 * Term array
	 *
	 * @var array
	 */
	var $terms			= array();

	/**
	 * Read old style parameters into new style terms
	 *
	 * @access	public
	 * @return	boolean	True on success
	 * @since	1.0
	 */
	function readParams( $params )
	{
		$terms	= array( 'trial_', 'full_' );
		$return	= false;

		foreach ( $terms as $t ) {
			if ( !empty( $params[$t.'period'] ) ) {
				$term = array();

				if ( $t == 'trial_' ) {
					$this->set( 'hasTrial', true );
					$term['type'] = 'trial';
				} else {
					$term['type'] = 'term';
				}

				$term['duration']['period']	= $params[$t.'period'];
				$term['duration']['unit']	= $params[$t.'periodunit'];

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
	 * add Term to Terms
	 *
	 * @access	public
	 * @return	boolean	True on success
	 * @since	1.0
	 */
	function addTerm( $terms )
	{
		$term = new mammonTerm();

		foreach( $terms as $n => $v ) {
			$term->set( $n, $v );
		}

		array_push( $this->terms, $term );
	}
}

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

}


?>
