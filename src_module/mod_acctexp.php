<?php
/**
 * @version $Id: mod_acctexp.php
 * @package AEC - Account Control Expiration - Subscription component for Joomla! OS CMS
 * @subpackage Module
 * @copyright 2006-2008 Copyright (C) David Deutsch
 * @author David Deutsch <skore@skore.de> & Team AEC - http://www.globalnerd.org
 * @license GNU/GPL v.2 http://www.gnu.org/licenses/old-licenses/gpl-2.0.html or, at your option, any later version
 */

// Dont allow direct linking
defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );

global $mosConfig_absolute_path, $mainframe;

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

require_once( $mainframe->getPath( 'class', 'com_acctexp' ) );

$class_sfx				= $params->get( 'moduleclass_sfx', "");
$pretext 				= $params->get( 'pretext' );
$posttext 				= $params->get( 'posttext' );
$showExpiration 		= $params->def( 'show_expiration', 0 );
$displaypipeline		= $params->get( 'displaypipeline', 0 );

if ( $my->id ) {
// Logout output
// ie HTML when already logged in and trying to logout
	if ( !empty( $pretext ) ) {
		echo $pretext;
	}

	if ( $showExpiration ) {
		$langPath = $mosConfig_absolute_path . '/modules/' . ( aecJoomla15check() ? 'mod_acctexp/' : '' ) . 'mod_acctexp_language/';
		if ( file_exists( $langPath . $GLOBALS['mosConfig_lang'] . '.php' )) {
				include_once( $langPath . $GLOBALS['mosConfig_lang'] . '.php' );
		} else {
				include_once( $langPath. 'english.php' );
		}

		$expiration = null;
		$query = 'SELECT expiration'
				. ' FROM #__acctexp_subscr'
				. ' WHERE userid = \'' . $my->id . '\''
				. ' AND primary = \'1\''
				. ' AND recurring != \'1\''
				. ' AND lifetime != \'1\'' 
				. ' AND status != \'Excluded\'';
		$database->setQuery($query);
		$expiration = $database->loadResult();

		if ( empty( $expiration ) ) {
			$query = 'SELECT expiration'
					. ' FROM #__acctexp_subscr'
					. ' WHERE userid = \'' . $my->id . '\''
					. ' AND recurring != \'1\''
	                . ' AND lifetime != \'1\'' 
	                . ' AND status != \'Excluded\'';
			$database->setQuery($query);
			$expiration = $database->loadResult();
		}

		if ( empty( $expiration ) ) {
			?>
			<p><?php echo _ACCOUNT_UNLIMIT; ?></p>
			<?php
		} else {
			?>
			<p><?php echo _ACCOUNT_EXPIRES; ?></p>
			<p><?php echo DisplayDateInLocalTime( $expiration ); ?></p>
			<?php
		}
	}

	if ( $displaypipeline ) {
		$dph = new displayPipelineHandler;
		echo $dph->getUserPipelineEvents( $my->id );
	}

	if ( !empty( $posttext ) ) {
		echo $posttext;
	}

}

?>
