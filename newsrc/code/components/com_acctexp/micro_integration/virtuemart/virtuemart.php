<?php
/**
 * @version $Id: mi_virtuemart.php
 * @package AEC - Account Control Expiration - Membership Manager
 * @subpackage Micro Integrations - MySQL Query
 * @copyright 2006-2013 Copyright (C) David Deutsch
 * @author David Deutsch <skore@valanx.org> & Team AEC - http://www.valanx.org
 * @license GNU/GPL v.3 http://www.gnu.org/licenses/gpl.html or, at your option, any later version
 */

// Dont allow direct linking
defined('_JEXEC') or die( 'Direct Access to this location is not allowed.' );

class mi_virtuemart
{
	function mi_virtuemart()
	{
		$db = JFactory::getDBO();
	 	$db->setQuery( 'SHOW TABLES LIKE \'%' . $db->getPrefix() . 'virtuemart_shoppergroups%\'' );

	 	$this->isv2 = $db->loadResult() ? true : false;
	}

	function Info()
	{
		$info = array();
		$info['name'] = JText::_('AEC_MI_NAME_VIRTM');
		$info['desc'] = JText::_('AEC_MI_DESC_VIRTM');
		$info['type'] = array( 'ecommerce.shopping_cart', 'vendor.virtuemart' );

		return $info;
	}

	function Settings()
	{
		$shopper_groups = $this->getShopperGroups();

		$sg = array();
		if ( !empty( $shopper_groups ) ) {
			foreach ( $shopper_groups as $group ) {
				$sg[] = JHTML::_('select.option', $group->shopper_group_id, $group->shopper_group_name );
			}
		}

		if ( $this->isv2 ) {
			$check = array( 'shopper_group', 'shopper_group_exp', 'remove_shopper_group', 'remove_shopper_group_exp' );
		} else {
			$check = array( 'shopper_group', 'shopper_group_exp' );
		}

		foreach ( $check as $k ) {
			if ( !isset( $this->settings[$k] ) ) {
				$this->settings[$k] = 0;
			}
		}

		$settings = array();

		$settings['create_account'] = array( 'toggle' );

		if ( $this->isv2 ) {
			$settings['lists']['shopper_group']				= JHTML::_( 'select.genericlist', $sg, 'shopper_group[]', 'size="4"', 'value', 'text', $this->settings['shopper_group'] );
			$settings['lists']['shopper_group_exp']			= JHTML::_( 'select.genericlist', $sg, 'shopper_group_exp[]', 'size="4"', 'value', 'text', $this->settings['shopper_group_exp'] );
			$settings['lists']['remove_shopper_group']		= JHTML::_( 'select.genericlist', $sg, 'remove_shopper_group[]', 'size="4"', 'value', 'text', $this->settings['remove_shopper_group'] );
			$settings['lists']['remove_shopper_group_exp']	= JHTML::_( 'select.genericlist', $sg, 'remove_shopper_group_exp[]', 'size="4"', 'value', 'text', $this->settings['remove_shopper_group_exp'] );

			$settings['set_shopper_group']				= array( 'toggle' );
			$settings['shopper_group']					= array( 'list' );
			$settings['set_remove_shopper_group']		= array( 'toggle' );
			$settings['remove_shopper_group']			= array( 'list' );

			$settings['rebuild']	= array( 'toggle' );
			$settings['remove']		= array( 'toggle' );

			$settings['aectab_exp']						= array( 'tab', 'Expiration Action', 'Expiration Action' );
			$settings['set_shopper_group_exp']			= array( 'toggle' );
			$settings['shopper_group_exp']				= array( 'list' );
			$settings['set_remove_shopper_group_exp']	= array( 'toggle' );
			$settings['remove_shopper_group_exp']		= array( 'list' );
		} else {
			$settings['lists']['shopper_group']		= JHTML::_( 'select.genericlist', $sg, 'shopper_group', 'size="4"', 'value', 'text', $this->settings['shopper_group'] );
			$settings['lists']['shopper_group_exp'] = JHTML::_( 'select.genericlist', $sg, 'shopper_group_exp', 'size="4"', 'value', 'text', $this->settings['shopper_group_exp'] );

			$settings['set_shopper_group']	= array( 'toggle' );
			$settings['shopper_group']		= array( 'list' );

			$settings['rebuild']	= array( 'toggle' );
			$settings['remove']		= array( 'toggle' );

			$settings['aectab_exp']				= array( 'tab', 'Expiration Action', 'Expiration Action' );
			$settings['set_shopper_group_exp']	= array( 'toggle' );
			$settings['shopper_group_exp']		= array( 'list' );
		}

		return $settings;
	}

	function expiration_action( $request )
	{
		if ( !$this->checkVMuserexists( $request->metaUser->userid ) && $this->settings['create_account'] ) {
			$this->createVMuser( $request->metaUser, $this->settings['shopper_group_exp'] );
		}

		if ( !empty($this->settings['set_shopper_group_exp']) ) {
			$this->addVMuserSgroup( $request->metaUser->userid, $this->settings['shopper_group_exp'] );
		}

		if ( !empty($this->settings['set_remove_shopper_group_exp']) ) {
			$this->addVMuserSgroup( $request->metaUser->userid, $this->settings['remove_shopper_group_exp'] );
		}
	}

	function action( $request )
	{
		if ( !$this->checkVMuserexists( $request->metaUser->userid ) && $this->settings['create_account'] ) {
			$this->createVMuser( $request->metaUser, $this->settings['shopper_group'] );
		}

		if ( !empty($this->settings['set_shopper_group']) ) {
			if ( is_array( $this->settings['shopper_group'] ) ) {
				foreach ( $this->settings['shopper_group'] as $g ) {
					$this->addVMuserSgroup( $request->metaUser->userid, $g );
				}
			} else {
				$this->addVMuserSgroup( $request->metaUser->userid, $this->settings['shopper_group'] );
			}
		}

		if ( !empty($this->settings['set_remove_shopper_group']) ) {
			if ( is_array( $this->settings['remove_shopper_group'] ) ) {
				foreach ( $this->settings['remove_shopper_group'] as $g ) {
					$this->removeVMuserSgroup( $request->metaUser->userid, $g );
				}
			} else {
				$this->removeVMuserSgroup( $request->metaUser->userid, $this->settings['shopper_group'] );
			}
		}
	}

	function getShopperGroups()
	{
		$db = JFactory::getDBO();

		if ( $this->isv2 ) {
			$query = 'SELECT `virtuemart_shoppergroup_id` AS `shopper_group_id`, `shopper_group_name`'
					. ' FROM #__virtuemart_shoppergroups'
					;
		} else {
			$query = 'SELECT `shopper_group_id`, `shopper_group_name`'
					. ' FROM #__vm_shopper_group'
					;
		}
	 	$db->setQuery( $query );
	 	return $db->loadObjectList();
	}

	function checkVMuserexists( $userid )
	{
		$db = JFactory::getDBO();

		if ( $this->isv2 ) {
			$query = 'SELECT `virtuemart_user_id`'
					. ' FROM #__virtuemart_userinfos'
					. ' WHERE `virtuemart_user_id` = \'' . $userid . '\''
					;
		} else {
			$query = 'SELECT `user_id`'
					. ' FROM #__vm_user_info'
					. ' WHERE `user_id` = \'' . $userid . '\''
					;
		}

		$db->setQuery( $query );
		return $db->loadResult();
	}

	function addVMuserSgroup( $userid, $shoppergroup )
	{
		$db = JFactory::getDBO();

		if ( $this->isv2 ) {
			if ( $this->hasVMuserSgroup( $userid, $shoppergroup ) ) {
				return null;
			}

			$query = 'INSERT INTO #__virtuemart_vmuser_shoppergroups'
					. ' (`virtuemart_shoppergroup_id`, `virtuemart_user_id`) '
					. ' VALUES (\'' . $shoppergroup . '\',\'' . $userid . '\')'
					;
		} else {
			$query = 'UPDATE #__vm_shopper_vendor_xref'
					. ' SET `shopper_group_id` = \'' . $shoppergroup . '\''
					. ' WHERE `user_id` = \'' . $userid . '\''
					;
		}
		$db->setQuery( $query );
		$db->query();
	}

	function removeVMuserSgroup( $userid, $shoppergroup )
	{
		if ( $this->hasVMuserSgroup( $userid, $shoppergroup ) ) {
			return null;
		}

		$db = JFactory::getDBO();

		$query = 'DELETE FROM #__virtuemart_vmuser_shoppergroups'
			. ' WHERE `virtuemart_shoppergroup_id` = \'' . $shoppergroup . '\''
			. ' AND `virtuemart_user_id` = \'' . $userid . '\''
		;

		$db->setQuery( $query );
		$db->query();
	}

	function hasVMuserSgroup( $userid, $shoppergroup )
	{
		$db = JFactory::getDBO();

		$query = 'SELECT id FROM #__virtuemart_vmuser_shoppergroups'
			. ' WHERE `virtuemart_shoppergroup_id` = \'' . $shoppergroup . '\''
			. ' AND `virtuemart_user_id` = \'' . $userid . '\''
		;

		$db->setQuery( $query );
		return $db->loadResult() ? true : false;
	}

	function createVMuser( $metaUser, $shoppergroup )
	{
		$app = JFactory::getApplication();

		$db = JFactory::getDBO();

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

		// Create Useraccount
		if ( $this->isv2 ) {
			$query  = 'INSERT INTO #__virtuemart_vmusers'
					. ' (virtuemart_user_id, virtuemart_vendor_id, user_is_vendor, perms, agreed, created_on, modified_on) '
					. ' VALUES(\'' . $metaUser->userid . '\', \'0\', \'0\', \'shopper\',\'1\',\'' . ( (int) gmdate('U') ) . '\', \'' . ( (int) gmdate('U') ) . '\')'
					;
			$db->setQuery( $query );
			$db->query();

			$query  = 'INSERT INTO #__virtuemart_userinfos'
					. ' ( virtuemart_user_id, address_type, last_name, first_name, middle_name, created_on, modified_on) '
					. ' VALUES(\'' . $metaUser->userid . '\', \'BT\', \'' . $lastname . '\', \'' . $firstname . '\', \'' . $middlename . '\', \'' . ( (int) gmdate('U') ) . '\', \'' . ( (int) gmdate('U') ) . '\')'
					;
		} else {
			$query  = 'INSERT INTO #__vm_user_info'
					. ' (user_info_id, user_id, address_type, last_name, first_name, middle_name, user_email, cdate, mdate, perms, bank_account_type)'
					. ' VALUES(\'' . $this->uniqueID() . '\', \'' . $metaUser->userid . '\', \'BT\', \'' . $lastname . '\', \'' . $firstname . '\', \'' . $middlename . '\', \'' . $metaUser->cmsUser->email . '\', \'' . ( (int) gmdate('U') ) . '\', \'' . ( (int) gmdate('U') ) . '\', \'shopper\', \'Checking\')'
					;
		}

		$db->setQuery( $query );
		$db->query();

		$this->addVMuserSgroup( $metaUser->userid, $shoppergroup );

		$db->setQuery( $query );
		$db->query();
	}

	function uniqueID()
	{
		$db = JFactory::getDBO();

		$inum = '';

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

		return $inum;
	}
}
