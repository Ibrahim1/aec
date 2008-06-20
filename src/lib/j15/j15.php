<?php
/**
 * @version $Id: j15.php
 * @package AEC - Account Control Expiration - Subscription component for Joomla! OS CMS
 * @subpackage Joomla 1.0->1.5 Compatibility Layer
 * @copyright Copyright (C) 2004-2007, David Deutsch, All Rights Reserved
 * @author David Deutsch <skore@skore.de> & Team AEC - http://www.gobalnerd.org
 * @license GNU/GPL v.2 http://www.gnu.org/copyleft/gpl.html
 */

// Dont allow direct linking
defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );

// Check whether we are on 1.5, otherwise load in some classes
if ( !class_exists( 'JObject' ) ) {
	//require_once( $mosConfig_absolute_path . '/components/com_acctexp/lib/j15/general.php' );
	require_once( $mosConfig_absolute_path . '/components/com_acctexp/lib/j15/object.php' );
}

?>
