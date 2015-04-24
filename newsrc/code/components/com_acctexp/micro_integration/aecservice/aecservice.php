<?php
/**
 * @version $Id: mi_aecservice.php
 * @package AEC - Account Control Expiration - Membership Manager
 * @subpackage Micro Integrations - AEC Points
 * @copyright 2015 Copyright (C) David Deutsch
 * @author David Deutsch <skore@valanx.org> & Team AEC - http://www.valanx.org
 * @license GNU/GPL v.3 http://www.gnu.org/licenses/gpl.html or, at your option, any later version
 */

// Dont allow direct linking
defined('_JEXEC') or die( 'Direct Access to this location is not allowed.' );

class mi_aecservice extends MI
{
	public function Info()
	{
		$info = array();
		$info['name'] = JText::_('AEC_MI_NAME_AECSERVICE');
		$info['desc'] = JText::_('AEC_MI_DESC_AECSERVICE');
		$info['type'] = array( 'ecommerce.credits', 'vendor.valanx' );

		return $info;
	}

	public function Settings()
	{
		$settings = array();

		$settings['change_points']			= array( 'inputB' );

		$settings = $this->autoduplicatesettings( $settings );

		$xsettings = array();
		$xsettings['checkout_discount']			= array( 'toggle' );
		$xsettings['checkout_conversion']		= array( 'inputB' );

		return array_merge( $xsettings, $settings );
	}

	public function overrideService( &$service )
	{
	}
}
