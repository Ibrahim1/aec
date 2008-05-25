<?php
/**
 * @version $Id: mi_directory.php 16 2007-07-02 13:29:29Z mic $
 * @package AEC - Account Control Expiration - Subscription component for Joomla! OS CMS
 * @subpackage Micro Integrations - MySQL Query
 * @copyright 2006/2007 Copyright (C) David Deutsch
 * @author David Deutsch <skore@skore.de> & Team AEC - http://www.globalnerd.org
 * @license GNU/GPL v.2 http://www.gnu.org/copyleft/gpl.html
 */

defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );

class mi_directory
{
	function Info()
	{
		$info = array();
		$info['name'] = _AEC_MI_NAME_DIRECTORY;
		$info['desc'] = _AEC_MI_DESC_DIRECTORY;

		return $info;
	}

	function Settings()
	{
        $settings = array();
        $settings['mkdir']			= array( 'inputD' );
        $settings['mkdir_mode']		= array( 'inputC' );
        $settings['mkdir_exp']		= array( 'inputD' );
        $settings['mkdir_mode_exp']	= array( 'inputC' );
        $settings['mkdir_pre_exp']	= array( 'inputD' );
        $settings['mkdir_mode_pre_exp']		= array( 'inputC' );
		$rewriteswitches			= array( 'cms', 'user', 'expiration', 'subscription', 'plan', 'invoice' );
		$settings['rewriteInfo']	= array( 'fieldset', _AEC_MI_SET4_MYSQL, AECToolbox::rewriteEngineInfo( $rewriteswitches ) );

		return $settings;
	}

	function Defaults()
	{
        $defaults = array();
        $defaults['mkdir_mode']			= '0644';
        $defaults['mkdir_mode_exp']		= '0644';
        $defaults['mkdir_mode_pre_exp']	= '0644';

		return $defaults;
	}

	function relayAction( $request, $area )
	{
		return $this->makedir( $this->settings['mkdir'.$area], $this->settings['mkdir_mode'.$area], $metaUser, $plan, $invoice );
	}

	function makedir( $path, $mode, $metaUser, $plan, $invoice=null )
	{
		if ( empty( $path ) || empty( $mode ) ) {
			return null;
		}

		$fullpath = AECToolbox::rewriteEngine( $path, $metaUser, $plan, $invoice );

		if ( !file_exists( $fullpath ) ) {
			return mkdir( $fullpath, $mode );
		} else {
			return true;
		}
	}

}
?>