<?php
/**
 * @version $Id: mi_mysql_query.php 16 2007-07-02 13:29:29Z mic $
 * @package AEC - Account Control Expiration - Subscription component for Joomla! OS CMS
 * @subpackage Micro Integrations - MySQL Query
 * @copyright 2006/2007 Copyright (C) David Deutsch
 * @author David Deutsch <skore@skore.de> & Team AEC - http://www.globalnerd.org
 * @license GNU/GPL v.2 http://www.gnu.org/copyleft/gpl.html
 */

defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );

class mi_virtuemart
{
	function Info()
	{
		$info = array();
		$info['name'] = _AEC_MI_NAME_VIRTM;
		$info['desc'] = _AEC_MI_DESC_VIRTM;

		return $info;
	}

	function Settings( $params )
	{
		global $database;

		$query = 'SELECT shopper_group_id, shopper_group_name'
		. ' FROM #__vm_shopper_group'
		;
	 	$database->setQuery( $query );
	 	$shopper_groups = $database->loadObjectList();

		$sg = array();
		foreach ( $shopper_groups as $group ) {
			$sg[] = mosHTML::makeOption( $group->shopper_group_id, $group->shopper_group_name );
		}

		$settings = array();
		$settings['lists']['shopper_group']		= mosHTML::selectList( $sg, 'shopper_group', 'size="4"', 'value', 'text',
												$params['shopper_group'] );
		$settings['lists']['shopper_group_exp'] = mosHTML::selectList( $sg, 'shopper_group_exp', 'size="4"', 'value', 'text',
												$params['shopper_group_exp'] );

		$settings['set_shopper_group']		= array( 'list_yesno' );
		$settings['shopper_group']			= array( 'list' );
		$settings['set_shopper_group_exp']	= array( 'list_yesno' );
		$settings['shopper_group_exp']		= array( 'list' );
		$settings['create_account']			= array( 'list_yesno' );
		$settings['rebuild']			= array( 'list_yesno' );

		return $settings;
	}

	function saveparams( $params )
	{
		global $mosConfig_absolute_path, $database;
		$newparams = $params;

		if ( $params['rebuild'] ) {
			$planlist = MicroIntegrationHandler::getPlansbyMI( $params['MI_ID'] );

			foreach ( $planlist as $planid ) {
				$userlist = SubscriptionPlanHandler::getPlanUserlist( $planid );
				foreach ( $userlist as $userid ) {
					if ( $params['set_shopper_group'] ) {
						if ( $this->checkVMuserexists( $userid ) ) {
							$this->updateVMuserSgroup( $userid, $params['shopper_group'] );
						} elseif ( $params['create_account'] ) {
							$this->createVMuser( $userid, $params['shopper_group'] );
						}

						return true;
					}
				}
			}

			$newparams['rebuild'] = 0;
		}

		return $newparams;
	}

	function expiration_action( $params, $metaUser, $plan )
	{
		if ( $params['set_shopper_group_exp'] ) {
			if ( $this->checkVMuserexists( $metaUser->userid ) ) {
				$this->updateVMuserSgroup( $metaUser->userid, $params['shopper_group_exp'] );
			} elseif ( $params['create_account'] ) {
				$this->createVMuser( $metaUser->userid, $params['shopper_group_exp'] );
			}

			return true;
		} else {
			return false;
		}
	}

	function action( $params, $metaUser, $invoice, $plan )
	{
		global $database;

		if ( $params['set_shopper_group'] ) {
			if ( $this->checkVMuserexists( $metaUser->userid ) ) {
				$this->updateVMuserSgroup( $metaUser->userid, $params['shopper_group'] );
			} elseif ( $params['create_account'] ) {
				$this->createVMuser( $metaUser->userid, $params['shopper_group'] );
			}

			return true;
		}else{
			return false;
		}
	}

	function checkVMuserexists( $userid )
	{
		global $database;

		$query  = 'SELECT id'
		. ' FROM #__vm_user_info'
		. ' WHERE user_id = \'' . $userid . '\''
		;
		$database->setQuery( $query );
		return $database->loadResult();
	}

	function updateVMuserSgroup( $userid, $shoppergroup )
	{
		global $database;

		$query  = 'UPDATE #__vm_shopper_vendor_xref'
		. ' SET shopper_group_id = \'' . $shoppergroup . '\''
		. ' WHERE user_id = \'' . $userid . '\''
		;
		$database->setQuery( $query );
		$database->query();
	}

	function createVMuser( $userid, $shoppergroup )
	{
		global $database;

		$metaUser = new metaUser( $database );

		$name = explode( ' ', $metaUser->cmsUser->name );
		$namount = count( $name );
		if ( $namount >= 3 ) {
			$firstname = $name[0];
			$mname = '';
			for( $i=0; $i<$namount; $i++ ) {
				$mname[] = $name[$i];
			}
			$middlename = implode( ' ', $mname );
			$lastname = $name[$namount];
		} elseif ( count( $name ) == 2 ) {
			$firstname = $name[0];
			$middlename = '';
			$lastname = $name[2];
		} else {
			$firstname = $name[0];
			$middlename = '';
			$lastname = '';
		}

		$numberofrows	= 1;
		while ( $numberofrows ) {
			// seed random number generator
			srand( (double) microtime() * 10000 );
			$inum =	strtolower( substr( base64_encode( md5( rand() ) ), 0, 32 ) );
			// Check if already exists
			$query = 'SELECT count(*)'
			. ' FROM #__vm_user_info'
			. ' WHERE user_info_id = \'' . $inum . '\''
			;
			$database->setQuery( $query );
			$numberofrows = $database->loadResult();
		}

		// Create Useraccount
		$query  = 'INSERT INTO #__vm_user_info'
		. ' (user_info_id, user_id, address_type, last_name, first_name, middle_name, user_email, cdate, mdate, perms, bank_account_type)'
		. ' VALUES(\'' . $inum . '\', \'' . $userid . '\', \'BT\', \'' . $lastname . '\', \'' . $firstname . '\', \'' . $middlename . '\', \'' . $metaUser->cmsUser->email . '\', \'' . time() . '\', \'' . time() . '\', \'shopper\', \'Checking\')'
		;
		$database->setQuery( $query );
		$database->query();

		// Create Shopper -ShopperGroup - Relationship
		$query  = 'INSERT INTO #__vm_shopper_vendor_xref'
		. ' (user_id, shopper_group_id)'
		. ' VALUES(\'' . $userid . '\', \'' . $shoppergroup . '\')'
		;
		$database->setQuery( $query );
		$database->query();
	}
}
?>