<?php
/**
 * @version $Id: mi_mysql_query.php
 * @package AEC - Account Control Expiration - Membership Manager
 * @subpackage Micro Integrations - MySQL Query
 * @copyright 2006-2010 Copyright (C) David Deutsch
 * @author David Deutsch <skore@skore.de> & Team AEC - http://www.valanx.org
 * @license GNU/GPL v.2 http://www.gnu.org/licenses/old-licenses/gpl-2.0.html or, at your option, any later version
 */

// Dont allow direct linking
( defined('_JEXEC') || defined( '_VALID_MOS' ) ) or die( 'Direct Access to this location is not allowed.' );

class mi_virtuemart
{
	function Info()
	{
		$info = array();
		$info['name'] = JText::_('_AEC_MI_NAME_VIRTM');
		$info['desc'] = JText::_('_AEC_MI_DESC_VIRTM');

		return $info;
	}

	function Settings()
	{
		$db = &JFactory::getDBO();

		$query = 'SELECT `shopper_group_id`, `shopper_group_name`'
				. ' FROM #__vm_shopper_group'
				;
	 	$db->setQuery( $query );
	 	$shopper_groups = $db->loadObjectList();

		$sg = array();
		if ( !empty( $shopper_groups ) ) {
			foreach ( $shopper_groups as $group ) {
				$sg[] = JHTML::_('select.option', $group->shopper_group_id, $group->shopper_group_name );
			}
		}

		if ( !isset( $this->settings['shopper_group'] ) ) {
			$this->settings['shopper_group'] = 0;
		}

		if ( !isset( $this->settings['shopper_group_exp'] ) ) {
			$this->settings['shopper_group_exp'] = 0;
		}

		$settings = array();
		$settings['lists']['shopper_group']		= JHTML::_( 'select.genericlist', $sg, 'shopper_group', 'size="4"', 'value', 'text', $this->settings['shopper_group'] );
		$settings['lists']['shopper_group_exp'] = JHTML::_( 'select.genericlist', $sg, 'shopper_group_exp', 'size="4"', 'value', 'text', $this->settings['shopper_group_exp'] );

		$settings['set_shopper_group']		= array( 'list_yesno' );
		$settings['shopper_group']			= array( 'list' );
		$settings['set_shopper_group_exp']	= array( 'list_yesno' );
		$settings['shopper_group_exp']		= array( 'list' );
		$settings['create_account']			= array( 'list_yesno' );
		$settings['rebuild']				= array( 'list_yesno' );
		$settings['remove']					= array( 'list_yesno' );

		return $settings;
	}

	function expiration_action( $request )
	{
		if ( $this->settings['set_shopper_group_exp'] ) {
			if ( $this->checkVMuserexists( $request->metaUser->userid ) ) {
				$this->updateVMuserSgroup( $request->metaUser->userid, $this->settings['shopper_group_exp'] );
			} elseif ( $this->settings['create_account'] ) {
				$this->createVMuser( $request->metaUser, $this->settings['shopper_group_exp'] );
			}

			return true;
		} else {
			return false;
		}
	}

	function action( $request )
	{
		$db = &JFactory::getDBO();

		if ( $this->settings['set_shopper_group'] ) {
			if ( $this->checkVMuserexists( $request->metaUser->userid ) ) {
				$this->updateVMuserSgroup( $request->metaUser->userid, $this->settings['shopper_group'] );
			} elseif ( $this->settings['create_account'] ) {
				$this->createVMuser( $request->metaUser, $this->settings['shopper_group'] );
			}

			return true;
		}else{
			return false;
		}
	}

	function checkVMuserexists( $userid )
	{
		$db = &JFactory::getDBO();

		$query = 'SELECT `user_id`' // Jonathan Appleton changed this from id to user_id - good find indeed!
				. ' FROM #__vm_user_info'
				. ' WHERE `user_id` = \'' . $userid . '\''
				;
		$db->setQuery( $query );
		return $db->loadResult();
	}

	function updateVMuserSgroup( $userid, $shoppergroup )
	{
		$db = &JFactory::getDBO();

		$query = 'UPDATE #__vm_shopper_vendor_xref'
				. ' SET `shopper_group_id` = \'' . $shoppergroup . '\''
				. ' WHERE `user_id` = \'' . $userid . '\''
				;
		$db->setQuery( $query );
		$db->query();
	}

	function createVMuser( $metaUser, $shoppergroup )
	{
		$app = JFactory::getApplication();

		$db = &JFactory::getDBO();

		// TODO: Replace with RWEngine call
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
			$lastname = $name[1];
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
					. ' WHERE `user_info_id` = \'' . $inum . '\''
					;
			$db->setQuery( $query );
			$numberofrows = $db->loadResult();
		}

		// Create Useraccount
		$query  = 'INSERT INTO #__vm_user_info'
				. ' (user_info_id, user_id, address_type, last_name, first_name, middle_name, user_email, cdate, mdate, perms, bank_account_type)'
				. ' VALUES(\'' . $inum . '\', \'' . $metaUser->userid . '\', \'BT\', \'' . $lastname . '\', \'' . $firstname . '\', \'' . $middlename . '\', \'' . $metaUser->cmsUser->email . '\', \'' . ( time() + ( $app->getCfg( 'offset' ) * 3600 ) ) . '\', \'' . ( time() + ( $app->getCfg( 'offset' ) * 3600 ) ) . '\', \'shopper\', \'Checking\')'
				;
		$db->setQuery( $query );
		$db->query();

		// Create Shopper -ShopperGroup - Relationship
		$query  = 'INSERT INTO #__vm_shopper_vendor_xref'
				. ' (user_id, shopper_group_id)'
				. ' VALUES(\'' . $metaUser->userid . '\', \'' . $shoppergroup . '\')'
				;
		$db->setQuery( $query );
		$db->query();
	}
}
?>
