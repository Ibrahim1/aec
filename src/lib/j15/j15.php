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

// Check whether we are on 1.5, otherwise load in some classes
if ( !class_exists( 'JObject' ) ) {
	//require_once( $mosConfig_absolute_path . '/components/com_acctexp/lib/j15/general.php' );
	require_once( $mosConfig_absolute_path . '/components/com_acctexp/lib/j15/object.php' );
}

?>
