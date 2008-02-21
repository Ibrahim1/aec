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

class mi_hotproperty
{
	function Info()
	{
		$info = array();
		$info['name'] = _AEC_MI_NAME_HOTPROPERTY;
		$info['desc'] = _AEC_MI_DESC_HOTPROPERTY;

		return $info;
	}

	function Settings( $params )
	{
		global $database;

        $settings = array();
		$settings['create_agent']		= array( 'list_yesno' );
		$settings['agent_fields']		= array( 'inputD' );
		$settings['create_company']		= array( 'list_yesno' );
		$settings['company_fields']		= array( 'inputD' );
		$settings['assoc_company']		= array( 'list_yesno' );
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

	function action( $params, $metaUser, $plan )
	{
		if( $params['create_agent'] ){
			if ( !empty( $params['agent_fields'] ) ) {
				return $this->createAgent( $metaUser, $params['agent_fields'], $plan );
			}
		}

		if( $params['create_company'] ){
			if ( !empty( $params['company_fields'] ) ) {
				return $this->createAgent( $metaUser, $params['company_fields'], $params['assoc_company'], $plan );
			}
		}
	}

	function createAgent( $metaUser, $fields, $plan )
	{
		global $database;

		$query = 'SELECT user FROM #__hp_agents'
				. ' WHERE user = \'' . $metaUser->userid . '\''
				;
		$database->setQuery( $query );

		if ( $database->loadResult() ) {
			return null;
		}

		$fields = AECToolbox::rewriteEngine( $fields, $metaUser, $plan );

		$fieldlist = explode( "\n", $fields );

		$keys = array();
		$values = array();
		foreach ( $fieldlist as $k => $v ) {
			if ( !empty( $k ) ) {
				$keys[] = $k;
				$values[] = $v;
			}
		}

		$query = 'INSERT INTO #__hp_agents'
				. ' (' . implode( ',', $keys ) . ')'
				. ' VALUES (\'' . implode( '\',\'', $values ) . '\')'
				;
		$database->setQuery( $query );

		if ( $database->query() ) {
			return true;
		} else {
			return false;
		}

	}

	function createCompany( $metaUser, $fields, $assoc, $plan )
	{
		global $database;

		$query = 'SELECT user FROM #__hp_companies'
				. ' WHERE cb_id = \'' . $metaUser->userid . '\''
				;
		$database->setQuery( $query );

		if ( $database->loadResult() ) {
			return null;
		}

		$fields = AECToolbox::rewriteEngine( $fields, $metaUser, $plan );

		$fieldlist = explode( "\n", $fields );

		$keys = array();
		$values = array();
		foreach ( $fieldlist as $k => $v ) {
			if ( !empty( $k ) ) {
				$keys[] = $k;
				$values[] = $v;
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

		return false;
	}

}

?>