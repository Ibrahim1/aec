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
		$info['type'] = array( 'aec.system', 'vendor.valanx' );

		return $info;
	}

	public function Settings()
	{
		$settings = array();

		if ( empty($this->settings['selected_service']) ) {
			$settings['lists'] = array(
				'selected_service' => JHTML::_(
					'select.genericlist',
					aecServiceList::getSelectList(),
					'selected_service',
					'size="1"',
					'value',
					'text',
					0
				)
			);

			$settings['selected_service'] = array('list');
			$settings['notice'] = array('p', 'Save once to show settings of the service');
		} else {
			$service = aecService::getById(
				$this->settings['selected_service']
			);

			$settings['notice'] = array('p', $service->name);

			$settings = array_merge(
				$settings,
				$service->getSettings()
			);
		}

		return $settings;
	}

	public function overrideService( &$service )
	{
		foreach ( $this->settings as $k => $v ) {
			if ( isset($service->params[$k]) && !empty($service->params[$k]) ) {
				$service->params[$k] = $v;
			}
		}
	}
}
