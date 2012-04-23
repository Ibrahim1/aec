<?php
/**
 * @version $Id: mi_iproperty.php
 * @package AEC - Account Control Expiration - Membership Manager
 * @subpackage Micro Integrations - DocMan
 * @copyright 2006-2012 Copyright (C) David Deutsch
 * @author David Deutsch <skore@valanx.org> & Team AEC - http://www.valanx.org
 * @license GNU/GPL v.3 http://www.gnu.org/licenses/gpl.html or, at your option, any later version
 */

// Dont allow direct linking
( defined('_JEXEC') || defined( '_VALID_MOS' ) ) or die( 'Direct Access to this location is not allowed.' );

class mi_iproperty
{
	function Info()
	{
		$info = array();
		$info['name'] = JText::_('AEC_MI_NAME_IPROPERTY');
		$info['desc'] = JText::_('AEC_MI_DESC_IPROPERTY');

		return $info;
	}

	function Settings()
	{
		$db = &JFactory::getDBO();

        $settings = array();
		$settings['add_listings']		= array( 'inputA' );
		$settings['set_listings']		= array( 'inputA' );

		$settings['add_agents']			= array( 'inputA' );
		$settings['set_agents']			= array( 'inputA' );

		$settings['add_flistings']		= array( 'inputA' );
		$settings['set_flistings']		= array( 'inputA' );

		$settings['add_fagents']		= array( 'inputA' );
		$settings['set_fagents']		= array( 'inputA' );

		$settings['add_listings']		= array( 'inputA' );
		$settings['set_listings']		= array( 'inputA' );

		$settings['create_agent']		= array( 'toggle' );
		$settings['agent_fields']		= array( 'inputD' );

		$settings['add_agentlistings']	= array( 'inputA' );
		$settings['set_agentlistings']	= array( 'inputA' );
		$settings['add_agentflistings']	= array( 'inputA' );
		$settings['set_agentflistings']	= array( 'inputA' );

		$settings['update_agent']		= array( 'toggle' );
		$settings['update_afields']		= array( 'inputD' );

		$settings['create_company']		= array( 'toggle' );
		$settings['company_fields']		= array( 'inputD' );

		$settings['update_company']		= array( 'toggle' );
		$settings['update_cfields']		= array( 'inputD' );

		$settings['rebuild']			= array( 'toggle' );
		$settings['remove']				= array( 'toggle' );

		return $settings;
	}

	function action( $request )
	{
		$db = &JFactory::getDBO();

 		if ( $this->settings['delete_on_set'] == "All" ) {
			$groups = $this->GetUserGroups( $request->metaUser->userid );

			foreach ( $groups as $group ) {
				$this->DeleteUserFromGroup( $request->metaUser->userid, $group );
			}
		}

		if ( $this->settings['set_group'] && !empty( $this->settings['group'] ) ) {
			foreach ( $this->settings['group'] as $group ) {
				$this->AddUserToGroup( $request->metaUser->userid, $group );
			}
		}

		$mi_ipropertyhandler = new docman_restriction( $db );
		$id = $mi_ipropertyhandler->getIDbyUserID( $request->metaUser->userid );
		$mi_id = $id ? $id : 0;
		$mi_ipropertyhandler->load( $mi_id );

		if ( !$mi_id ) {
			$mi_ipropertyhandler->userid = $request->metaUser->userid;
		}

		$mi_ipropertyhandler->active = 1;

		if ( $this->settings['set_downloads'] ) {
			$mi_ipropertyhandler->setDownloads( $this->settings['set_downloads'] );
		} elseif ( $this->settings['add_downloads'] ) {
			$mi_ipropertyhandler->addDownloads( $this->settings['add_downloads'] );
		}
		if ( $this->settings['set_unlimited'] ) {
			$mi_ipropertyhandler->unlimited_downloads = true ;
		}
		$mi_ipropertyhandler->check();
		$mi_ipropertyhandler->store();

		return true;
	}

	function createCompany()
	{
		
	}

	function createAgent( $metaUser )
	{
		$fields = array();

		return $this->createQuery( $fields, 'agents' );
	}

	function createQuery( $fields, $table )
	{
		if ( empty( $fields ) ) {
			return false;
		}

		$db = &JFactory::getDBO();

		$query  = 'INSERT INTO #__iproperty_' . $table
				. ' (' . implode(', ', array_keys($fields) ) . ') '
				. ' VALUES(\'' . implode('\', \'', array_keys($fields) ) . '\')'
				;
		$db->setQuery( $query );

		return $db->query();
	}

	function getCompanyParams( $company_id )
	{
		$db = &JFactory::getDBO();

		$query = 'SELECT `params`'
				. ' FROM #__iproperty_companies'
				. ' WHERE `id` = \'' . $company_id . '\''
				;
		$db->setQuery( $query );

		return json_decode( $db->loadResult() );
	}

	function updateCompanyParams( $company_id, $params )
	{
		$db = &JFactory::getDBO();

		$query = 'UPDATE #__iproperty_companies'
				. ' SET `params` = \'' . $db->escape( json_encode( $params ) ) . '\''
				. ' WHERE `id` = \'' . $company_id . '\''
				;
		$db->setQuery( $query );

		return $db->query();
	}

	function publishProperties()
	{
		
	}

	function unpublishProperties()
	{
		
	}


}

?>
