<?php
/**
 * @version $Id: mod_acctexp.php 16 2007-06-27 09:04:04Z mic $
 * @package AEC - Account Control Expiration - Subscription component for Joomla! OS CMS
 * @subpackage Module
 * @copyright Copyright (C) 2004-2007, All Rights Reserved, Helder Garcia, David Deutsch
 * @author David Deutsch <skore@skore.de> - http://www.gobalnerd.org
 * @license GNU/GPL v.2 http://www.gnu.org/copyleft/gpl.html
 */

// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License (GPL)
// as published by the Free Software Foundation; either version 2
// of the License, or (at your option) any later version.
//
// Please note that the GPL states that any headers in files and
// Copyright notices as well as credits in headers, source files
// and output (screens, prints, etc.) can not be removed.
// You can extend them with your own credits, though...
//
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with this program; if not, write to the Free Software
// Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.

// Dont allow direct linking
defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );

global $mosConfig_absolute_path, $mainframe;

require_once( $mainframe->getPath( 'class', 'com_acctexp' ) );

$class_sfx				= $params->get( 'moduleclass_sfx', "");
$pretext 				= $params->get( 'pretext' );
$posttext 				= $params->get( 'posttext' );
$showExpiration 		= $params->def( 'show_expiration', 0 );
$displaypipeline		= $params->get( 'displaypipeline', 0 );

if ( $my->id ) {
// Logout output
// ie HTML when already logged in and trying to logout
	if ( $name ) {
		$name = $my->name;
	} else {
		$name = $my->username;
	}

	if ( $pretext ) {
		echo $pretext;
	}

	if ( $showExpiration ) {
		$langPath = $mosConfig_absolute_path . '/modules/mod_acctexp_language/';
		if ( file_exists( $langPath . $GLOBALS['mosConfig_lang'] . '.php' )) {
				include_once( $langPath . $GLOBALS['mosConfig_lang'] . '.php' );
		} else {
				include_once( $langPath. 'english.php' );
		}

		$expiration = null;
		$query = 'SELECT expiration'
				. ' FROM #__acctexp'
				. ' WHERE userid=' . $my->id
				. ' AND expiration<>\'9999-12-31 00:00:00\'';
		$database->setQuery($query);
		$expiration = $database->loadResult();

		if ( $expiration == NULL ) {
			?>
			<p><?php echo _ACCOUNT_UNLIMIT; ?></p>
			<?php
		} else {
			?>
			<p><?php echo _ACCOUNT_EXPIRES; ?></p>
			<p><?php echo DisplayShortDateInLocalTime( $expiration ); ?></p>
			<?php
		}
	}

	if ( $displaypipeline ) {
		$dph = new displayPipelineHandler;
		echo $dph->getUserPipelineEvents( $my->id );
	}

	if ( $posttext ) {
		echo $posttext;
	}

}

/**
 * Formats a given date
 *
 * @param string	$SQLDate
 * @param bool		$check		check time diference
 * @param bool		$display	out with text (only in combination with $check)
 * @return formatted date
 */
function DisplayDateInLocalTime( $SQLDate )
{
	global $mosConfig_offset_user, $database;

	// compatibility with Mambo
	if ( !empty( $mosConfig_offset_user ) ) {
		$timeOffset = $mosConfig_offset_user * 3600;
	} else {
		global $mosConfig_offset;
		$timeOffset = $mosConfig_offset * 3600;
	}

	$cfg = new Config_General( $database );

	$retVal = strftime( $cfg->cfg['display_date_frontend'], ( strtotime( $SQLDate ) + $timeOffset ) );

	return $retVal;
}

?>