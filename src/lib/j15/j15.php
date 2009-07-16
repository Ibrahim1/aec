<?php
/**
 * @version $Id: j15.php
 * @package AEC - Account Control Expiration - Membership Manager
 * @subpackage Joomla 1.0->1.5 Compatibility Layer
 * @copyright 2006-2008 Copyright (C) David Deutsch
 * @author David Deutsch <skore@skore.de> & Team AEC - http://www.valanx.org
 * @license GNU/GPL v.2 http://www.gnu.org/licenses/old-licenses/gpl-2.0.html or, at your option, any later version
 */

// Dont allow direct linking
( defined('_JEXEC') || defined( '_VALID_MOS' ) ) or die( 'Direct Access to this location is not allowed.' );

if ( !defined( 'JPATH_SITE' ) ) {
	global $mosConfig_absolute_path;

	define( 'JPATH_SITE', $mosConfig_absolute_path );
}

if ( !class_exists( 'JURI' ) ) {
	class JURI
	{
	    function base( $what ) {
	        global $mosConfig_live_site;

			return $mosConfig_live_site;
	    }
	}
}

// Check whether we are on 1.5, otherwise load in some classes
if ( !class_exists( 'JObject' ) ) {
	//require_once( JPATH_SITE . '/components/com_acctexp/lib/j15/general.php' );
	require_once( JPATH_SITE . '/components/com_acctexp/lib/j15/object.php' );
}

if ( !class_exists( 'JTable' ) && class_exists( 'mosDBTable' ) ) {
	class JTable extends mosDBTable
	{
	    function __construct( $table, $key, &$database ) {
	        $this->mosDBTable( $table, $key, $database );
	    }
	}
}

if ( !class_exists( 'JFactory' ) ) {
	class JFactory
	{
	    function getDBO()
	    {
	        global $database;

			return $database;
	    }

		function getUser()
		{
			global $my;

			return $my;
		}
	}
}

if ( !class_exists( 'JToolBarHelper' ) ) {
	if ( !class_exists( 'mosMenuBar' ) ) {
		global $mosConfig_absolute_path;

		require_once( $mosConfig_absolute_path . '/administrator/includes/menubar.html.php' );
	}

	class JToolBarHelper extends mosMenuBar
	{}
}

if ( !class_exists( 'JTableUser' ) && class_exists( 'mosUser' ) ) {
	class JTableUser extends mosUser
	{}
}

?>
