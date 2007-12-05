<?php
/**
* @version		$Id: aecaccess.php
* @package		AEC - Account Expiration Control - Access Plugin
* @copyright	Copyright (C) 2007 David Deutsch. All rights reserved.
* @license		GNU/GPL Version 2.0 or later - http://www.gnu.org/copyleft/gpl.html
*/

/**
* This program is free software; you can redistribute it and/or
* modify it under the terms of the GNU General Public License (GPL)
* as published by the Free Software Foundation; either version 2
* of the License, or (at your option) any later version.
*
* Please note that the GPL states that any headers in files and
* Copyright notices as well as credits in headers, source files
* and output (screens, prints, etc.) can not be removed.
* You can extend them with your own credits, though...
*
* This program is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
* GNU General Public License for more details.
*
* You should have received a copy of the GNU General Public License
* along with this program; if not, write to the Free Software
* Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
*/

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

jimport('joomla.event.plugin');

/**
 * AEC Authentication plugin
 *
 * @author David Deutsch <mails@globalnerd.org>
 * @package AEC Component
 */
class plgAuthenticationAECaccess extends JPlugin
{

	/**
	 * Constructor
	 *
	 * For php4 compatability we must not use the __constructor as a constructor for plugins
	 * because func_get_args ( void ) returns a copy of all passed arguments NOT references.
	 * This causes problems with cross-referencing necessary for the observer design pattern.
	 *
	 * @param object $subject The object to observe
	 * @param array  $config  An array that holds the plugin configuration
	 * @since 1.5
	 */
	function plgAuthenticationAECaccess(& $subject, $config) {
		parent::__construct($subject, $config);
	}

	/**
	 * This method should handle any authentication and report back to the subject
	 *
	 * @access	public
	 * @param   array 	$credentials Array holding the user credentials
	 * @param 	array   $options     Array of extra options
	 * @param	object	$response	 Authentication response object
	 * @return	boolean
	 * @since 1.5
	 */
	function onAuthenticate( $credentials, $options, &$response )
	{
		jimport('joomla.user.helper');
/*
		include_once( JPATH_BASE .DS.'components'.DS.'com_acctexp'.DS.'acctexp.php' );

		if ( AECToolbox::VerifyUsername( $credentials['username'] ) ) {
			$response->status = JAUTHENTICATE_STATUS_SUCCESS;
		} else {
			$response->status = JAUTHENTICATE_STATUS_FAILURE;
		}
*/
$response->status = JAUTHENTICATE_STATUS_SUCCESS;

	}
}
