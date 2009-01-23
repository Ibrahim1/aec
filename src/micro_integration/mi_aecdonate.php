<?php
/**
 * @version $Id: mi_aeconate.php
 * @package AEC - Account Control Expiration - Membership Manager
 * @subpackage Micro Integrations - AEC Donations
 * @copyright 2006-2008 Copyright (C) David Deutsch
 * @author David Deutsch <skore@skore.de> & Team AEC - http://www.globalnerd.org
 * @license GNU/GPL v.2 http://www.gnu.org/licenses/old-licenses/gpl-2.0.html or, at your option, any later version
 */

// Dont allow direct linking
( defined('_JEXEC') || defined( '_VALID_MOS' ) ) or die( 'Direct Access to this location is not allowed.' );

class mi_aecdonate
{
	function Settings()
	{
		global $database;

		$settings = array();

		$settings['min'] = array( 'inputB' );
		$settings['max'] = array( 'inputB' );

		return $settings;
	}

	function saveParams( $params )
	{
		foreach ( $params as $n => $v ) {
			$params[$n] = AECToolbox::correctAmount( $v );
		}

		return $params;
	}

	function getMIform()
	{
		global $database;

		$settings = array();

		$settings['amt'] = array( 'inputB', _MI_MI_AECDONATE_USERSELECT_AMT_NAME, _MI_MI_AECDONATE_USERSELECT_AMT_DESC, $this->settings['min'] );

		return $settings;
	}

	function modifyPrice( $request )
	{
		$price = AECToolbox::correctAmount( $request->params['amt'] );

		if ( ( $price >= $this->settings['min'] ) && ( $price <= $this->settings['max'] ) ) {
			$request->add->price = $price;
		} elseif ( $price > $this->settings['max'] ) {
			$request->add->price = $this->settings['max'];
		} elseif ( $price < $this->settings['min'] ) {
			$request->add->price = $this->settings['min'];
		}

		return null;
	}

}
?>
