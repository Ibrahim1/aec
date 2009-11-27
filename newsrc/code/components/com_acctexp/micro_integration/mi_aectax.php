<?php
/**
 * @version $Id: mi_aectax.php
 * @package AEC - Account Control Expiration - Membership Manager
 * @subpackage Micro Integrations - Overall Tax Management MI
 * @copyright 2006-2008 Copyright (C) David Deutsch
 * @author David Deutsch <skore@skore.de> & Team AEC - http://www.valanx.org
 * @license GNU/GPL v.2 http://www.gnu.org/licenses/old-licenses/gpl-2.0.html or, at your option, any later version
 */

// Dont allow direct linking
( defined('_JEXEC') || defined( '_VALID_MOS' ) ) or die( 'Direct Access to this location is not allowed.' );

class mi_aectax
{
	function Info()
	{
		$info = array();
		$info['name'] = _AEC_MI_NAME_AECTAX;
		$info['desc'] = _AEC_MI_DESC_AECTAX;

		return $info;
	}

	function Settings()
	{
		$settings = array();
		$settings['locations']	= array( 'inputA' );

		// Tax Modes
		// Multi-Select offer tax modes

		return $settings;
	}

	function getMIform()
	{
		$database = &JFactory::getDBO();

		$settings = array();

		$locations = $this->getLocations();

		if ( !empty( $locations ) ) {
			$settings['exp'] = array( 'p', _MI_MI_USER_CHOICE_FILES_NAME, _MI_MI_USER_CHOICE_FILES_DESC );

			$list = explode( "\n", $this->settings['desc_list'] );

			$gr = array();
			foreach ( $list as $id => $choice ) {
				$choice = trim( $choice );

				if ( $this->settings['max_choices'] > 1 ) {
					$settings['ef'.$id] = array( 'checkbox', 'mi_'.$this->id.'_mi_email_files[]', $id, true, $choice );
				} else {
					$settings['ef'.$id] = array( 'radio', 'mi_'.$this->id.'_mi_email_files', $id, true, $choice );
				}
			}
			$settings['mi_email_files'] = array( 'hidden', null, 'mi_'.$this->id.'_mi_email_files[]' );
		} else {
			return false;
		}

		return $settings;
	}

	function invoice_printout( $request )
	{
		// Append Tax Data to content

		return true;
	}

	function getLocationList()
	{
		$locations = array();

		$l = explode( "\n", $this->settings['locations'] );

		if ( !empty( $l ) ) {
			foreach ( $l as $loc ) {
				$location = explode( "|", $loc );

				$locations[] = array( 'Text' );
			}
		}

		return $locations;
	}
}
?>
