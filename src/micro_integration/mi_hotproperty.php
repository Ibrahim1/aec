<?php
/**
 * @version $Id: mi_hotproperty.php
 * @package AEC - Account Control Expiration - Subscription component for Joomla! OS CMS
 * @subpackage Micro Integrations - Mosets Hot Property
 * @copyright 2006/2007 Copyright (C) David Deutsch
 * @author David Deutsch <skore@skore.de> & Team AEC - http://www.globalnerd.org
 * @license GNU/GPL v.2 http://www.gnu.org/copyleft/gpl.html
 */

defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );

class mi_hotproperty extends MI
{
	function Settings()
	{
		global $database;

        $settings = array();
		$settings['create_agent']		= array( 'list_yesno' );
		$settings['agent_fields']		= array( 'inputD' );
		$settings['update_agent']		= array( 'list_yesno' );
		$settings['update_afields']		= array( 'inputD' );
		$settings['create_company']		= array( 'list_yesno' );
		$settings['company_fields']		= array( 'inputD' );
		$settings['update_company']		= array( 'list_yesno' );
		$settings['update_cfields']		= array( 'inputD' );
		$settings['publish_all']		= array( 'list_yesno' );
		$settings['unpublish_all']		= array( 'list_yesno' );

		$settings = $this->autoduplicatesettings( $settings );

		$settings['assoc_company']		= array( 'list_yesno' );
		$settings['rebuild']			= array( 'list_yesno' );
		$settings['remove']				= array( 'list_yesno' );

		$rewriteswitches				= array( 'cms', 'user', 'expiration', 'subscription', 'plan', 'invoice' );
		$settings['rewriteInfo']		= array( 'fieldset', _AEC_MI_SET11_EMAIL, AECToolbox::rewriteEngineInfo( $rewriteswitches ) );

		return $settings;
	}

	function Defaults()
	{
		$defaults = array();
		$defaults['agent_fields']	= "user=[[user_id]]\ncb_id=[[user_id]]\nname=[[user_name]]\nemail=[[user_email]]\ncompany=\nneed_approval=1";
		$defaults['company_fields']	= "name=[[user_name]]\naddress=\nsuburb=\ncountry=\nstate=\npostcode=\ntelephone=\nfax=\nwebsite=\ncb_id=[[user_id]]\nemail=[[user_email]]";

		return $defaults;
	}

	function relayAction( $request, $area )
	{
		$agent = null;
		$company = null;

		if ( $this->settings['create_agent'.$area] ){
			if ( !empty( $this->settings['agent_fields'.$area] ) ) {
				$agent = $this->createAgent( $this->settings['agent_fields'.$area], $request );
			}
		}

		if ( $agent === false ) {
			return false;
		}

		if ( $this->settings['update_agent'.$area] ){
			if ( !empty( $this->settings['update_afields'.$area] ) ) {
				if ( empty( $agent ) ) {
					$agent = $this->agentExists( $metaUser->userid );
				}

				if ( !empty( $agent ) ) {
					$agent = $this->update( 'agents', 'user', $this->settings['update_afields'.$area], $request );
				}
			}
		}

		if ( $agent === false ) {
			return false;
		}

		if ( $this->settings['create_company'.$area] ){
			if ( !empty( $this->settings['company_fields'.$area] ) ) {
				$company = $this->createCompany( $this->settings['company_fields'.$area], $this->settings['assoc_company'], $request );
			}
		}

		if ( $company === false ) {
			return false;
		}

		if ( $this->settings['update_company'.$area] ){
			if ( !empty( $this->settings['update_cfields'.$area] ) ) {
				if ( empty( $company ) ) {
					$company = $this->companyExists( $metaUser->userid );
				}

				if ( !empty( $company ) ) {
					$company = $this->update( 'companies', 'cb_id', $this->settings['update_cfields'.$area], $request );
				}
			}
		}

		if ( $this->settings['unpublish_all'.$area] ) {
			$this->unpublishProperties( $agent );
		}

		if ( $this->settings['publish_all'.$area] ) {
			$this->publishProperties( $agent );
		}

		if ( $company === false ) {
			return false;
		} else {
			return true;
		}
	}

	function agentExists( $userid )
	{
		global $database;

		$query = 'SELECT user FROM #__hp_agents'
				. ' WHERE user = \'' . $userid . '\''
				;
		$database->setQuery( $query );
		$id = $database->loadResult();

		if ( $id ) {
			return $id;
		} else {
			return false;
		}
	}

	function createAgent( $fields, $request )
	{
		global $database;

		$check = $this->agentExists( $request->metaUser->userid );

		if ( !empty( $check ) ) {
			return null;
		}

		$fields = AECToolbox::rewriteEngineRQ( $fields, $request );

		$fieldlist = explode( "\n", $fields );

		$keys = array();
		$values = array();
		foreach ( $fieldlist as $content ) {
			$c = explode( '=', $content, 2 );

			if ( !empty( $c[0] ) ) {
				$keys[] = trim( $c[0] );
				$values[] = trim( $c[1] );
			}
		}

		$query = 'INSERT INTO #__hp_agents'
				. ' (' . implode( ',', $keys ) . ')'
				. ' VALUES (\'' . implode( '\',\'', $values ) . '\')'
				;
		$database->setQuery( $query );
		$result = $database->query();

		if ( $result ) {
			return true;
		} else {
			$this->setError( $database->getErrorMsg() );
			return false;
		}
	}

	function companyExists( $userid )
	{
		global $database;

		$query = 'SELECT user FROM #__hp_companies'
				. ' WHERE cb_id = \'' . $userid . '\''
				;
		$database->setQuery( $query );
		$id = $database->loadResult();

		if ( $id ) {
			return $id;
		} else {
			return false;
		}
	}

	function createCompany( $fields, $assoc, $request )
	{
		global $database;

		$check = $this->agentExists( $request->metaUser->userid );
		if ( !empty( $check ) ) {
			return null;
		}

		$fields = AECToolbox::rewriteEngineRQ( $fields, $request );

		$fieldlist = explode( "\n", $fields );

		$keys = array();
		$values = array();
		foreach ( $fieldlist as $content ) {
			$c = explode( '=', $content, 2 );

			if ( !empty( $c[0] ) ) {
				$keys[] = trim( $c[0] );
				$values[] = trim( $c[1] );
			}
		}

		$query = 'INSERT INTO #__hp_companies'
				. ' (' . implode( ',', $keys ) . ')'
				. ' VALUES (\'' . implode( '\',\'', $values ) . '\')'
				;
		$database->setQuery( $query );
		$result = $database->query();

		if ( $result ) {
			if ( $assoc ) {
				$query = 'SELECT count(*)'
						. ' FROM #__hp_companies'
						;
				$database->setQuery( $query );
				$result = $database->query();

				if ( $result ) {
					$query = 'UPDATE #__hp_agents'
							. ' SET company = \'' . $result . '\''
							. ' WHERE cb_id = \'' . $request->metaUser->userid . '\''
							;

					$database->setQuery( $query );
					if ( $database->query() ) {
						return true;
					}
				}
			} else {
				return true;
			}
		}

		$this->setError( $database->getErrorMsg() );
		return false;
	}

	function update( $table, $id, $fields, $request )
	{
		global $database;

		$fields = AECToolbox::rewriteEngineRQ( $fields, $request );

		$fieldlist = explode( "\n", $fields, 2 );

		$set = array();
		foreach ( $fieldlist as $content ) {
			$c = explode( '=', $content, 2 );

			if ( !empty( $c[0] ) ) {
				$set[] = '`' . trim( $c[0] ) . '` = \'' . trim( $c[1] ) . '\'';
			}
		}

		$query = 'UPDATE #__hp_' . $table
				. ' SET ' . implode( ', ', $set )
				. ' WHERE ' . $id . ' = \'' . $request->metaUser->userid . '\''
				;

		$database->setQuery( $query );
		if ( $database->query() ) {
			return true;
		} else {
			$this->setError( $database->getErrorMsg() );
			return false;
		}
	}

	function publishProperties( $agentid )
	{
		global $database;

		$query = 'UPDATE #__hp_properties'
				. ' SET `published` = \'1\''
				. ' WHERE `agent` = \'' . $agentid . '\''
				;
		$database->setQuery( $query );
		if ( $database->query() ) {
			return true;
		} else {
			$this->setError( $database->getErrorMsg() );
			return false;
		}
	}

	function unpublishProperties( $agentid )
	{
		global $database;

		$query = 'UPDATE #__hp_properties'
				. ' SET `published` = \'0\''
				. ' WHERE `agent` = \'' . $agentid . '\''
				;
		$database->setQuery( $query );
		if ( $database->query() ) {
			return true;
		} else {
			$this->setError( $database->getErrorMsg() );
			return false;
		}
	}

}

?>