<?php
/**
 * @version $Id: mi_adsmanager.php
 * @package AEC - Account Control Expiration - Membership Manager
 * @subpackage Micro Integrations - Adsmanager
 * @copyright 2011 Copyright (C) David Deutsch
 * @author David Deutsch <skore@valanx.org> & Team AEC - http://www.valanx.org
 * @license GNU/GPL v.2 http://www.gnu.org/licenses/old-licenses/gpl-2.0.html or, at your option, any later version
 */

// Dont allow direct linking
( defined('_JEXEC') || defined( '_VALID_MOS' ) ) or die( 'Direct Access to this location is not allowed.' );

class mi_adsmanager extends MI
{
	function Info()
	{
		$info = array();
		$info['name'] = JText::_('AEC_MI_ADSMANAGER_NAME');
		$info['desc'] = JText::_('AEC_MI_ADSMANAGER_DESC');

		return $info;
	}

	function Settings()
	{
		$db = &JFactory::getDBO();

        $settings = array();
		$settings['publish_all']	= array( 'list_yesno' );
		$settings['unpublish_all']	= array( 'list_yesno' );

		$settings = $this->autoduplicatesettings( $settings, array(), true, true );

		$xsettings = array();
		$xsettings['rebuild']		= array( 'list_yesno' );
		$xsettings['remove']		= array( 'list_yesno' );

		return array_merge( $xsettings, $settings );
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
		$db = &JFactory::getDBO();

		$query = 'UPDATE #__adsmanager_ads'
				. ' SET `published` = \'1\''
				. ' WHERE `userid` = \'' . $metaUser->userid . '\''
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

		$query = 'UPDATE #__adsmanager_ads'
				. ' SET `published` = \'0\''
				. ' WHERE `userid` = \'' . $metaUser->userid . '\''
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
