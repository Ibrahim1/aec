<?php
/**
 * @version $Id: mi_aecuserdetails.php
 * @package AEC - Account Control Expiration - Membership Manager
 * @subpackage Micro Integrations - AEC Donations
 * @copyright 2006-2008 Copyright (C) David Deutsch
 * @author David Deutsch <skore@skore.de> & Team AEC - http://www.valanx.org
 * @license GNU/GPL v.2 http://www.gnu.org/licenses/old-licenses/gpl-2.0.html or, at your option, any later version
 */

// Dont allow direct linking
( defined('_JEXEC') || defined( '_VALID_MOS' ) ) or die( 'Direct Access to this location is not allowed.' );

class mi_aecuserdetails
{
	function Settings()
	{
		global $database;

		$settings = array();

		$settings['settings']	= array( 'inputB' );

		if ( !empty( $this->settings['settings'] ) ) {
			for ( $i=0; $i<=$this->settings['settings']; $i++ ) {
				$p = $i . '_';

				$settings[$p.'short']	= array( 'inputC', sprintf( _MI_MI_AECUSERDETAILS_SET_SHORT_NAME, $i+1 ), _MI_MI_AECUSERDETAILS_SET_SHORT_DESC );
				$settings[$p.'name']	= array( 'inputC', sprintf( _MI_MI_AECUSERDETAILS_SET_NAME_NAME, $i+1 ), _MI_MI_AECUSERDETAILS_SET_NAME_DESC );
				$settings[$p.'desc']	= array( 'inputC', sprintf( _MI_MI_AECUSERDETAILS_SET_DESC_NAME, $i+1 ), _MI_MI_AECUSERDETAILS_SET_DESC_DESC );
				$settings[$p.'type']	= array( 'inputC', sprintf( _MI_MI_AECUSERDETAILS_SET_TYPE_NAME, $i+1 ), _MI_MI_AECUSERDETAILS_SET_TYPE_DESC );
				$settings[$p.'default']	= array( 'inputC', sprintf( _MI_MI_AECUSERDETAILS_SET_DEFAULT_NAME, $i+1 ), _MI_MI_AECUSERDETAILS_SET_DEFAULT_DESC );
			}
		}

		return $settings;
	}

	function saveParams( $params )
	{
		foreach ( $params as $n => $v ) {
			if ( !empty( $v ) && ( strpos( $n, '_short' ) ) ) {
				$params[$n] = preg_replace( '/[^a-z0-9._+-]+/i', '', trim( $v ) );
			}
		}

		return $params;
	}

	function getMIform()
	{
		global $database;

		$settings = array();

		if ( !empty( $this->settings['settings'] ) ) {
			for ( $i=0; $i<=$this->settings['settings']; $i++ ) {
				$p = $i . '_';

				if ( !empty( $this->settings[$p.'short'] ) ) {
					$settings[$this->settings[$p.'short']] = array( $this->settings[$p.'type'], $this->settings[$p.'name'], $this->settings[$p.'desc'], $this->settings[$p.'default'] );
				}
			}
		}

		return $settings;
	}

	function verifyMIform( $request )
	{
		global $database;

		$params = array();
		if ( !empty( $this->settings['settings'] ) ) {
			for ( $i=0; $i<=$this->settings['settings']; $i++ ) {
				$p = $i . '_';

				if ( !empty( $this->settings[$p.'short'] ) ) {
					$params[$this->settings[$p.'short']] = $request->params[$this->settings[$p.'short']];
				}
			}
		}

		$request->metaUser->addCustomParams( $params );
		$request->metaUser->storeload();

		return array();
	}

}
?>
