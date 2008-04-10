<?php
/**
 * @version $Id: mammontini.php
 * @package Mammontini: General purpose Payment-related functionality
 * @copyright Copyright (C) 2007 David Deutsch, All Rights Reserved
 * @author David Deutsch <skore@skore.de>
 * @license GNU/GPL v.2 or later http://www.gnu.org/copyleft/gpl.html
 *
 *                                                  _   _       _
 *                                                 | | (_)     (_)
 *  _ __ ___   __ _ _ __ ___  _ __ ___   ___  _ __ | |_ _ _ __  _
 * | '_ ` _ \ / _` | '_ ` _ \| '_ ` _ \ / _ \| '_ \| __| | '_ \| |
 * | | | | | | (_| | | | | | | | | | | | (_) | | | | |_| | | | | |
 * |_| |_| |_|\__,_|_| |_| |_|_| |_| |_|\___/|_| |_|\__|_|_| |_|_| v1.0
 *
 * The lean library for the big money processing named after squirrels.
 */

class mammonTerms extends eucaObject
{
	// Various Standard Variables
	var $hasTrial		= null;
	var $pointer		= null;

	function readParams( $params )
	{

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
