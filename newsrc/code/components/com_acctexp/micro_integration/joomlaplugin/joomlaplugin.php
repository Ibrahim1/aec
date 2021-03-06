<?php
/**
 * @version $Id: mi_joomlaplugin.php
 * @package AEC - Account Control Expiration - Membership Manager
 * @subpackage Micro Integrations - Joomla Plugin
 * @copyright 2006-2015 Copyright (C) David Deutsch
 * @author David Deutsch <skore@valanx.org> & Team AEC - http://www.valanx.org
 * @license GNU/GPL v.3 http://www.gnu.org/licenses/gpl.html or, at your option, any later version
 */

// Dont allow direct linking
defined('_JEXEC') or die( 'Direct Access to this location is not allowed.' );

class mi_joomlaplugin
{
	public function Info()
	{
		$info = array();
		$info['name'] = JText::_('AEC_MI_NAME_JOOMLAPLUGIN');
		$info['desc'] = JText::_('AEC_MI_DESC_JOOMLAPLUGIN');
		$info['type'] = array( 'basic.joomla', 'system', 'vendor.valanx' );

		return $info;
	}

	public function Settings()
	{
		$db = JFactory::getDBO();

		$settings = array();
		$settings['plugin'] = array( 'list' );

		$query = 'SELECT p.*, u.name AS editor, g.name AS groupname'
			. ' FROM #__plugins AS p'
			. ' LEFT JOIN #__users AS u ON u.id = p.checked_out'
			. ' LEFT JOIN #__groups AS g ON g.id = p.access'
			. ' GROUP BY p.id'
			;
		$db->setQuery( $query );
		$rows = $db->loadObjectList();

		$pl = array();
		foreach( $rows as $plugin ) {
			$desc = $plugin->folder . ' - ' . substr( strip_tags( $plugin->name ), 0, 30 );

			$pl[] = JHTML::_('select.option', $plugin->folder."_".$plugin->element, $desc );
		}

		$settings['lists']['plugin'] 	= JHTML::_( 'select.genericlist', $pl, 'plugin', 'size="10"', 'value', 'text', $this->settings['plugin'] );

		return $settings;
	}

	public function action( $request )
	{
		if ( empty( $this->settings['plugin'] ) ) {
			return null;
		}

		include_once( JPATH_SITE . '/libraries/joomla/user/authentication.php' );

		$db = JFactory::getDBO();

		$p = explode( "_", $this->settings['plugin'], 2 );

		JPluginHelper::importPlugin( $p[0], $p[1] );

		$query = 'SELECT folder AS type, element AS name, params'
			. ' FROM #__plugins'
			. ' WHERE published >= 1'
			. ' ORDER BY ordering';

		$db->setQuery( $query );

		$plugin = $db->loadObject();

		// Create authentication response
		$response = new JAuthenticationResponse();

		$credentials = array();
		$credentials['username'] = $request->metaUser->cmsUser->name;
		$credentials['password'] = $request->metaUser->cmsUser->password;

		$className = 'plg'.$p[0].$p[1];
		if (class_exists( $className )) {
			$plugin = new $className($this, (array)$plugin);

			// Try to authenticate
			if ( method_exists( $plugin, 'onAuthenticate' ) ) {
				$plugin->onAuthenticate($credentials, array(), $response);
			}
		}
	}

	public function attach( &$observer)
	{
		// Make sure we haven't already attached this object as an observer
		if (is_object($observer))
		{
			$class = get_class($observer);
			foreach ($this->_observers as $check) {
				if (is_a($check, $class)) {
					return;
				}
			}
			$this->_observers[] =& $observer;
		} else {
			$this->_observers[] =& $observer;
		}
	}
}
