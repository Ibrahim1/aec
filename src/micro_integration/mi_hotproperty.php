<?php
/**
 * @version $Id: mi_hotproperty.php
 * @package AEC - Account Control Expiration - Subscription component for Joomla! OS CMS
 * @subpackage Micro Integrations - Advanced Profile Control
 * @copyright 2006/2007 Copyright (C) David Deutsch
 * @author David Deutsch <skore@skore.de> & Team AEC - http://www.globalnerd.org
 * @license GNU/GPL v.2 http://www.gnu.org/copyleft/gpl.html
 */

defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );

class mi_hotproperty extends MI
{
	function Settings( $params )
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

	function saveparams( $params )
	{
		global $mosConfig_absolute_path, $database;
		$newparams = $params;

		if ( $params['rebuild'] ) {
			$planlist = MicroIntegrationHandler::getPlansbyMI( $this->id );

			foreach ( $planlist as $planid ) {
				$plan = new SubscriptionPlan( $database );
				$plan->load( $planid );

				$userlist = SubscriptionPlanHandler::getPlanUserlist( $planid );
				foreach ( $userlist as $userid ) {
					$metaUser = new metaUser( $userid );

					$this->action( $params, $metaUser, null, $plan );
				}
			}

			$newparams['rebuild'] = 0;
		}

		return $newparams;
	}

	function relayAction( $params, $metaUser, $plan, $invoice, $area )
	{
		$agent = null;
		$company = null;

		if ( $params['create_agent'.$area] ){
			if ( !empty( $params['agent_fields'.$area] ) ) {
				$agent = $this->createAgent( $metaUser, $params['agent_fields'.$area], $invoice, $plan );
			}
		}

		if ( $agent === false ) {
			return false;
		}

		if ( $params['update_agent'.$area] ){
			if ( !empty( $params['update_afields'.$area] ) ) {
				if ( empty( $agent ) ) {
					$agent = $this->agentExists( $metaUser->userid );
				}

				if ( !empty( $agent ) ) {
					$agent = $this->update( 'agents', 'user', $metaUser, $params['update_afields'.$area], $invoice, $plan );
				}
			}
		}

		if ( $agent === false ) {
			return false;
		}

		if ( $params['create_company'.$area] ){
			if ( !empty( $params['company_fields'.$area] ) ) {
				$company = $this->createCompany( $metaUser, $params['company_fields'.$area], $params['assoc_company'], $invoice, $plan );
			}
		}

		if ( $company === false ) {
			return false;
		}

		if ( $params['update_company'.$area] ){
			if ( !empty( $params['update_cfields'.$area] ) ) {
				if ( empty( $company ) ) {
					$company = $this->companyExists( $metaUser->userid );
				}

				if ( !empty( $company ) ) {
					$company = $this->update( 'companies', 'cb_id', $metaUser, $params['update_cfields'.$area], $invoice, $plan );
				}
			}
		}

		if ( $params['unpublish_all'.$area] ) {
			$this->unpublishProperties( $params, $agent );
		}

		if ( $params['publish_all'.$area] ) {
			$this->publishProperties( $params, $agent );
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

	function createAgent( $metaUser, $fields, $invoice, $plan )
	{
		global $database;

		$check = $this->agentExists( $metaUser->userid );

		if ( !empty( $check ) ) {
			return null;
		}

		$fields = AECToolbox::rewriteEngine( $fields, $metaUser, $plan, $invoice );

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
				. ' WHERE cb_id = \'' . $metaUser->userid . '\''
				;
		$database->setQuery( $query );
		$id = $database->loadResult();

		if ( $id ) {
			return $id;
		} else {
			return false;
		}
	}

	function createCompany( $metaUser, $fields, $assoc, $invoice, $plan )
	{
		global $database;

		$check = $this->agentExists( $metaUser->userid );
		if ( !empty( $check ) ) {
			return null;
		}

		$fields = AECToolbox::rewriteEngine( $fields, $metaUser, $plan, $invoice );

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
							. ' WHERE cb_id = \'' . $metaUser->userid . '\''
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

	function update( $table, $id, $metaUser, $fields, $invoice, $plan )
	{
		global $database;

		$fields = AECToolbox::rewriteEngine( $fields, $metaUser, $plan, $invoice );

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
				. ' WHERE ' . $id . ' = \'' . $metaUser->userid . '\''
				;

		$database->setQuery( $query );
		if ( $database->query() ) {
			return true;
		} else {
			$this->setError( $database->getErrorMsg() );
			return false;
		}
	}

	function publishProperties( $params, $agentid )
	{
		global $database;

		$query = 'UPDATE #__mt_links'
				. ' SET `published` = \'1\''
				. ' WHERE `user_id` = \'' . $metaUser->userid . '\''
				;
		$database->setQuery( $query );

		return $database->query();
	}

	function unpublishProperties( $params, $agentid )
	{
		global $database;

		$query = 'UPDATE #__hp_properties'
				. ' SET `published` = \'0\''
				. ' WHERE `user_id` = \'' . $metaUser->userid . '\''
				;
		$database->setQuery( $query );

		return $database->query();
	}

}

?>