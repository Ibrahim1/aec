<?php
/**
 * @version $Id: tool_miexport.php
 * @package AEC - Account Control Expiration - Membership Manager
 * @subpackage Toolbox - MI Export
 * @copyright 2010 Copyright (C) David Deutsch
 * @author David Deutsch <skore@skore.de> & Team AEC - http://www.valanx.org
 * @license GNU/GPL v.2 http://www.gnu.org/licenses/old-licenses/gpl-2.0.html or, at your option, any later version
 */

// Dont allow direct linking
( defined('_JEXEC') || defined( '_VALID_MOS' ) ) or die( 'Direct Access to this location is not allowed.' );

class tool_miexport
{
	function Info()
	{
		$info = array();
		$info['name'] = "Micro Integration Export";
		$info['desc'] = "Export Micro Integration Settings into a format that you can easily import on another server.";

		return $info;
	}

	function Settings()
	{
		$db = &JFactory::getDBO();

		$settings = array();
		$settings['micro_integrations']				= array( 'list', 'Micro Integration', 'Select one or more MIs to export' );

		// get available micro integrations
		$query = 'SELECT `id` AS value, CONCAT(`name`, " - ", `desc`) AS text'
				. ' FROM #__acctexp_microintegrations'
				. ' WHERE `active` = 1'
			 	. ' AND `hidden` = \'0\''
				. ' ORDER BY ordering'
				;
		$db->setQuery( $query );
		$mi_list = $db->loadObjectList();

		$lists['micro_integrations'] = JHTML::_('select.genericlist', $mi_list, 'micro_integrations[]', 'size="' . min((count( $mi_list ) + 1), 25) . '" multiple="multiple"', 'value', 'text', array());

		return $settings;
	}

	function Action( $request )
	{
		$db = &JFactory::getDBO();

		if ( !empty( $_POST['micro_integrations'] ) ) {
			foreach ( $_POST['micro_integrations'] as $mi ) {
				
			}
		}
	}

}
?>
