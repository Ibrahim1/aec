<?php
/**
 * @version $Id: mod_acctexp.php
 * @package AEC - Account Control Expiration - Subscription component for Joomla! OS CMS
 * @subpackage Module
 * @copyright 2006-2010 Copyright (C) David Deutsch
 * @author David Deutsch <skore@skore.de> & Team AEC - http://www.valanx.org
 * @license GNU/GPL v.2 http://www.gnu.org/licenses/old-licenses/gpl-2.0.html or, at your option, any later version
 */

// Dont allow direct linking
( defined('_JEXEC') || defined( '_VALID_MOS' ) ) or die( 'Direct Access to this location is not allowed.' );

require_once( JApplicationHelper::getPath( 'class', 'com_acctexp' ) );

$class_sfx				= $params->get( 'moduleclass_sfx', "");
$pretext 				= $params->get( 'pretext' );
$posttext 				= $params->get( 'posttext' );
$showExpiration 		= $params->def( 'show_expiration', 0 );
$displaypipeline		= $params->get( 'displaypipeline', 0 );
$extended				= $params->get( 'extended', 0 );

$user = &JFactory::getUser();

if ( $user->id ) {

	echo '<div class="aec_module_inner' . $class_sfx . '">';

	if ( !empty( $pretext ) ) {
		echo $pretext;
	}

	if ( $showExpiration ) {
		$langPath = JPATH_SITE . '/modules/mod_acctexp/mod_acctexp_language/';

		$lang =& JFactory::getLanguage();

		$language = AECToolbox::oldLangConversion( $lang->getTag() );

		if ( file_exists( $langPath . $language . '.php' )) {
			include_once( $langPath . $language . '.php' );
		} else {
			include_once( $langPath. 'english.php' );
		}

		if ( !$extended ) {
			echo AECModuleHelper::getExpirationSimple( $user );
		} else {
			$metaUser = new metaUser( $user->id );

			$subscriptions = $metaUser->getAllCurrentSubscriptionsInfo();

			foreach ( $subscriptions as $subscription ) {
				echo '<div class="aec_module_subscription aec_module_subscriptionid_' . $subscription->plan  . '">';

				echo "<h4>" . $subscription->name . "</h4>";

				echo AECModuleHelper::textExpiration( $user, $subscription->expiration, $subscription->lifetime, $subscription->recurring );

				echo '</div>';
			}
		}

	}

	if ( $displaypipeline ) {
		$dph = new displayPipelineHandler;
		echo $dph->getUserPipelineEvents( $user->id );
	}

	if ( !empty( $posttext ) ) {
		echo $posttext;
	}

	echo '</div>';
}

class AECModuleHelper
{
	function getExpirationSimple( $user )
	{
		$db = &JFactory::getDBO();

		$expiration = null;
		$query = 'SELECT `id`, `expiration`, `lifetime`, `recurring`'
				. ' FROM #__acctexp_subscr'
				. ' WHERE `userid` = \'' . $user->id . '\''
				. ' AND `primary` = \'1\''
				. ' AND `status` != \'Excluded\'';
		$db->setQuery($query);
		$entry = $db->loadObject();

		if ( !empty( $entry->lifetime ) ) {
			return AECModuleHelper::textExpiration( $user, $entry->expiration, $entry->lifetime, $entry->recurring );
		}

		if ( empty( $entry->expiration ) ) {
			$query = 'SELECT `id`, `expiration`, `recurring`'
					. ' FROM #__acctexp_subscr'
					. ' WHERE `userid` = \'' . $user->id . '\''
	                . ' AND `status` != \'Excluded\'';
			$db->setQuery($query);
			$entry = $db->loadObject();
		}

		if ( empty( $entry->id ) ) {
			return null;
		} else {
			return AECModuleHelper::textExpiration( $user, $entry->expiration, $entry->lifetime, $entry->recurring );
		}
	}

	function textExpiration( $user, $expiration, $lifetime=null, $recurring=false )
	{
		if ( empty( $expiration ) || $lifetime ) {
			return AECModuleHelper::textUnlimited();
		} else {
			return AECModuleHelper::textExpirationDate( $expiration, $recurring );
		}
	}

	function textUnlimited()
	{
		return "<p>" . _ACCOUNT_UNLIMIT . "</p>";
	}

	function textExpirationDate( $expiration, $recurring )
	{
		global $aecConfig;

		$app = JFactory::getApplication();

		$ou = $app->getCfg( 'offset_user' );

		// compatibility with Mambo
		if ( !empty( $ou ) ) {
			$timeOffset = $ou;
		} else {
			$timeOffset = $app->getCfg( 'offset' );
		}

		$retVal = AECToolbox::formatDate( ( strtotime( $expiration ) + $timeOffset*3600 ) );

		if ( $recurring ) {
			return "<p>" . _ACCOUNT_RENEWAL . ": " . $retVal . "</p>";
		} else {
			return "<p>" . _ACCOUNT_EXPIRES . ": " . $retVal . "</p>";
		}
	}
}

?>
