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
		$settings['assoc_company']		= array( 'list_yesno' );

		$settings = $this->autoduplicatesettings( $settings );

		$settings['rebuild']			= array( 'list_yesno' );

		$rewriteswitches				= array( 'cms', 'user', 'expiration', 'subscription', 'plan', 'invoice' );
		$settings['rewriteInfo']		= array( 'fieldset', _AEC_MI_SET11_EMAIL, AECToolbox::rewriteEngineInfo( $rewriteswitches ) );

		return $settings;
	}

	function Defaults()
	{
		$defaults = array();
		$defaults['agent_fields']	= "user=[[user_id]]\nsellertype=\ncb_id=[[user_id]]\nname=[[user_name]]\nemail=[[user_email]]\ncompany=\nneed_approval=1";
		$defaults['company_fields']	= "name=[[user_name]]\naddress=\nsuburb=\ncountry=\nstate=\npostcode=\ntelephone=\nfax=\nwebsite=\ncb_id=[[user_id]]\nemail=[[user_email]]";

		return $defaults;
	}

	function saveparams( $params )
	{
		global $mosConfig_absolute_path, $database;
		$newparams = $params;

		if ( $params['rebuild'] ) {
			$planlist = MicroIntegrationHandler::getPlansbyMI( $params['MI_ID'] );

			foreach ( $planlist as $planid ) {
				$plan = new SubscriptionPlan( $database );
				$plan->load( $planid );

				$userlist = SubscriptionPlanHandler::getPlanUserlist( $planid );
				foreach ( $userlist as $userid ) {
					$metaUser = new metaUser( $userid );

					$this->action( $params, $metaUser, $plan );
				}
			}

			$newparams['rebuild'] = 0;
		}

		return $newparams;
	}

	function relayAction( $params, $metaUser, $invoice, $plan, $stage )
	{
		if( $params['create_agent'] ){
			if ( !empty( $params['agent_fields'] ) ) {
				$agentid = $this->createAgent( $metaUser, $params['agent_fields'], $invoice, $plan );
			}
		}

		if( $params['create_company'] ){
			if ( !empty( $params['company_fields'] ) ) {
				return $this->createCompany( $metaUser, $params['company_fields'], $params['assoc_company'], $invoice, $plan );
			}
		}
	}

	function createAgent( $metaUser, $fields, $invoice, $plan )
	{
		global $database;

		$query = 'SELECT user FROM #__hp_agents'
				. ' WHERE user = \'' . $metaUser->userid . '\''
				;
		$database->setQuery( $query );
		$id = $database->loadResult();

		if ( $id ) {
			return $id;
		}

		$fields = AECToolbox::rewriteEngine( $fields, $metaUser, $plan, $invoice );

		$fieldlist = explode( "\n", $fields );

		$keys = array();
		$values = array();
		foreach ( $fieldlist as $content ) {
			$c = explode( '=', $content );

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
			return $this->createAgent( $metaUser, $fields, $invoice, $plan );
		} else {
			$this->error = $database->getErrorMsg();
			return false;
		}
	}

	function createCompany( $metaUser, $fields, $assoc, $invoice, $plan )
	{
		global $database;

		$query = 'SELECT user FROM #__hp_companies'
				. ' WHERE cb_id = \'' . $metaUser->userid . '\''
				;
		$database->setQuery( $query );
		$id = $database->loadResult();

		if ( $id ) {
			return $id;
		}

		$fields = AECToolbox::rewriteEngine( $fields, $metaUser, $plan, $invoice );

		$fieldlist = explode( "\n", $fields );

		$keys = array();
		$values = array();
		foreach ( $fieldlist as $content ) {
			$c = explode( '=', $content );

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
						return $this->createCompany( $metaUser, $fields, $assoc, $invoice, $plan );
					}
				}
			} else {
				return $this->createCompany( $metaUser, $fields, $assoc, $invoice, $plan );
			}
		}

		$this->error = $database->getErrorMsg();
		return false;
	}

}

?>