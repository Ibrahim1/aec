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

class mi_sobipro extends MI
{
	function Info()
	{
		$info = array();
		$info['name'] = 'SobiPro'; 
		$info['desc'] = 'A Micro-Integrator for SobiPro component. This is a port of Sobi2 MI adapted for use with SobiPro';

		return $info;
	}

	function Settings()
	{
		$db = &JFactory::getDBO();

        $settings = array();
		$settings['publish_all']		= array( 'list_yesno' );
		$settings['unpublish_all']		= array( 'list_yesno' );

		$settings = $this->autoduplicatesettings( $settings );

		$xsettings = array();
		$xsettings['rebuild']			= array( 'list_yesno' );
		$xsettings['remove']			= array( 'list_yesno' );

		return array_merge( $xsettings, $settings );
	}

	function relayAction( $request )
	{
		if ( isset( $this->settings['unpublish_all'.$request->area] ) ) {
			if ( $this->settings['unpublish_all'.$request->area] ) {
				$this->processItems( $request->metaUser, 0 );
			}
		}

		if ( isset( $this->settings['publish_all'.$request->area] ) ) {
			if ( $this->settings['publish_all'.$request->area] ) {
				$this->processItems( $request->metaUser, 1 );
			}
		}

		return true;
	}

	function processItems( $metaUser, $publishVar )
	{
		$sobiPath = implode( DS, array( JPATH_ROOT, 'components', 'com_sobipro', 'lib', 'sobi.php' ) );

		if(!file_exists($sobiPath))
		{
			$this->setError( 'SobiPro cannot be found on this site! Path: ' . $sobiPath );
			return false;
		}
		else
		{
			require_once( $sobiPath );
			SPFactory::db()->update( 'spdb_object', array( 'state' => $publishVar ), array( 'owner' => $metaUser->userid ) );
		}
		
		return true;
	}
}

?>
