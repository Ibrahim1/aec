<?php
/**
 * @version $Id: mi_adsmanager.php
 * @package AEC - Account Control Expiration - Membership Manager
 * @subpackage Micro Integrations - Adsmanager
 * @copyright 2010 Copyright (C) David Deutsch
 * @author David Deutsch <skore@skore.de> & Team AEC - http://www.valanx.org
 * @license GNU/GPL v.2 http://www.gnu.org/licenses/old-licenses/gpl-2.0.html or, at your option, any later version
 */

// Dont allow direct linking
( defined('_JEXEC') || defined( '_VALID_MOS' ) ) or die( 'Direct Access to this location is not allowed.' );

class mi_adsmanager extends MI
{
	function Settings()
	{
		$database = &JFactory::getDBO();

        $settings = array();
		$settings['publish_all']		= array( 'list_yesno' );
		$settings['unpublish_all']		= array( 'list_yesno' );

		$settings = $this->autoduplicatesettings( $settings );

		$settings['rebuild']			= array( 'list_yesno' );
		$settings['remove']				= array( 'list_yesno' );

		$rewriteswitches				= array( 'cms', 'user', 'expiration', 'subscription', 'plan', 'invoice' );
		$settings['rewriteInfo']		= array( 'fieldset', _AEC_MI_SET11_EMAIL, AECToolbox::rewriteEngineInfo( $rewriteswitches ) );

		return $settings;
	}


	function relayAction( $request )
	{
		if ( $this->settings['unpublish_all'.$request->area] ) {
			$this->unpublishItems( $request->metaUser );
		}

		if ( $this->settings['publish_all'.$request->area] ) {
			$this->publishItems( $request->metaUser );
		}

		return true;
	}

	function publishItems( $metaUser )
	{
		$database = &JFactory::getDBO();

		$query = 'UPDATE #__adsmanager_ads'
				. ' SET `published` = \'1\''
				. ' WHERE `userid` = \'' . $metaUser->userid . '\''
				;
		$database->setQuery( $query );
		if ( $database->query() ) {
			return true;
		} else {
			$this->setError( $database->getErrorMsg() );
			return false;
		}
	}

	function unpublishItems( $metaUser )
	{
		$database = &JFactory::getDBO();

		$query = 'UPDATE #__adsmanager_ads'
				. ' SET `published` = \'0\''
				. ' WHERE `userid` = \'' . $metaUser->userid . '\''
				;
		$database->setQuery( $query );
		if ( $database->query() ) {
			return true;
		} else {
			$this->setError( $database->getErrorMsg() );
			return false;
		}
	}

}

?>
