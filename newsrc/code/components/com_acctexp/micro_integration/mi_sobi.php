<?php
/**
 * @version $Id: mi_sobi.php
 * @package AEC - Account Control Expiration - Membership Manager
 * @subpackage Micro Integrations - Sigsiu Online Business Index
 * @copyright 2006-2010 Copyright (C) David Deutsch
 * @author David Deutsch <skore@skore.de> & Team AEC - http://www.valanx.org
 * @license GNU/GPL v.2 http://www.gnu.org/licenses/old-licenses/gpl-2.0.html or, at your option, any later version
 */

( defined('_JEXEC') || defined( '_VALID_MOS' ) ) or die( 'Direct Access to this location is not allowed.' );

class mi_sobi extends MI
{
	function Settings()
	{
		$db = &JFactory::getDBO();

        $settings = array();
		$settings['publish_all']		= array( 'list_yesno' );
		$settings['unpublish_all']		= array( 'list_yesno' );

		$settings = $this->autoduplicatesettings( $settings );

		$settings['rebuild']			= array( 'list_yesno' );
		$settings['remove']				= array( 'list_yesno' );

		$rewriteswitches				= array( 'cms', 'user', 'expiration', 'subscription', 'plan', 'invoice' );
		$settings						= AECToolbox::rewriteEngineInfo( $rewriteswitches, $settings );

		return $settings;
	}

	function Defaults()
	{
		$defaults = array();
		$defaults['agent_fields']	= "user=[[user_id]]\ncb_id=[[user_id]]\nname=[[user_name]]\nemail=[[user_email]]\ncompany=\nneed_approval=1";
		$defaults['company_fields']	= "name=[[user_name]]\naddress=\nsuburb=\ncountry=\nstate=\npostcode=\ntelephone=\nfax=\nwebsite=\ncb_id=[[user_id]]\nemail=[[user_email]]";

		return $defaults;
	}

	function relayAction( $request )
	{
		if ( isset( $this->settings['unpublish_all'.$request->area] ) ) {
			if ( $this->settings['unpublish_all'.$request->area] ) {
				$this->unpublishItems( $request->metaUser );

				$this->clearSOBIcache();
			}
		}

		if ( isset( $this->settings['publish_all'.$request->area] ) ) {
			if ( $this->settings['publish_all'.$request->area] ) {
				$this->publishItems( $request->metaUser );

				$this->clearSOBIcache();
			}
		}

		return true;
	}

	function publishItems( $metaUser )
	{
		$db = &JFactory::getDBO();

		$query = 'UPDATE #__sobi2_item'
				. ' SET `published` = \'1\''
				. ' WHERE `owner` = \'' . $metaUser->userid . '\''
				;
		$db->setQuery( $query );

		if ( $db->query() ) {
			return true;
		} else {
			$this->setError( $db->getErrorMsg() );
			return false;
		}
	}

	function unpublishItems( $metaUser )
	{
		$db = &JFactory::getDBO();

		$query = 'UPDATE #__sobi2_item'
				. ' SET `published` = \'0\''
				. ' WHERE `owner` = \'' . $metaUser->userid . '\''
				;
		$db->setQuery( $query );

		if ( $db->query() ) {
			return true;
		} else {
			$this->setError( $db->getErrorMsg() );
			return false;
		}
	}

	function clearSOBIcache()
	{
		$db = &JFactory::getDBO();

		$query = 'TRUNCATE #__sobi2_cache'
				;
		$db->setQuery( $query );

		if ( $db->query() ) {
			return true;
		} else {
			$this->setError( $db->getErrorMsg() );
			return false;
		}
	}
}

?>
