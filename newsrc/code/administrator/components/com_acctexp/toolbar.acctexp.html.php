<?php
/**
 * @version $Id: toolbar.acctexp.html.php
 * @package AEC - Account Control Expiration - Membership Manager
 * @subpackage Menubar HTML Writer
 * @copyright 2006-2011 Copyright (C) David Deutsch
 * @author David Deutsch <skore@skore.de> & Team AEC - http://www.valanx.org
 * @license GNU/GPL v.2 http://www.gnu.org/licenses/old-licenses/gpl-2.0.html or, at your option, any later version
 */

// Dont allow direct linking
( defined('_JEXEC') || defined( '_VALID_MOS' ) ) or die( 'Direct Access to this location is not allowed.' );

class ComponentMenu
{
	/**
	* Draws the menu
	*/
	function NEW_MENU()
	{  // To be deleted?
		JToolBarHelper::custom( 'save', 'save.png',  'save_f2.png', JText::_('SAVE'), false );
		JToolBarHelper::custom( 'cancel', 'cancel.png',  'cancel_f2.png', JText::_('CANCEL'), false );
		JToolBarHelper::custom( 'showCentral', 'extensions.png',  'extensions_f2.png', JText::_('CENTRAL_PAGE') , false );
	}

	function NEW_EXPIRATION()
	{
		JToolBarHelper::custom( 'add', 'edit.png',  'edit_f2.png', JText::_('CONFIGURE') );
		JToolBarHelper::custom( 'showCentral', 'extensions.png',  'extensions_f2.png', JText::_('CENTRAL_PAGE') , false );
	}

	function EDIT_EXPIRATION()
	{
		JToolBarHelper::custom( 'edit', 'edit.png',  'edit_f2.png', JText::_('CONFIGURE') );
		JToolBarHelper::custom( 'expire', 'restore.png',  'restore_f2.png', JText::_('REMOVE') );
		JToolBarHelper::custom( 'showCentral', 'extensions.png',  'extensions_f2.png', JText::_('CENTRAL_PAGE') , false );
	}

	function ADD_MENU()
	{
		JToolBarHelper::custom( 'save', 'save.png',  'save_f2.png', JText::_('SAVE'), false );
		JToolBarHelper::custom( 'cancel', 'cancel.png',  'cancel_f2.png', JText::_('CANCEL'), false );
	}

	function EDIT_MENU()
	{
		JToolBarHelper::custom( 'save', 'save.png',  'save_f2.png', JText::_('SAVE'), false );
		JToolBarHelper::custom( 'apply', 'apply.png',  'apply_f2.png', JText::_('APPLY_PAYPLAN'), false );
		JToolBarHelper::custom( 'cancel', 'cancel.png',  'cancel_f2.png', JText::_('CANCEL'), false );
	}

	function EDIT_SETTINGS()
	{
		JToolBarHelper::custom( 'applySettings', 'apply.png',  'apply_f2.png', JText::_('APPLY_PAYPLAN'), false );
		JToolBarHelper::custom( 'saveSettings', 'save.png',  'save_f2.png', JText::_('SAVE'), false );
		JToolBarHelper::custom( 'cancelSettings', 'cancel.png',  'cancel_f2.png', JText::_('CANCEL'), false );
		JToolBarHelper::custom( 'showCentral', 'extensions.png',  'extensions_f2.png', JText::_('CENTRAL_PAGE') , false );
	}

	function LIST_PROCESSORS()
	{
		JToolBarHelper::custom( 'publishProcessor', 'publish.png',  'publish_f2.png', JText::_('PUBLISH_PROCESSOR'), true );
		JToolBarHelper::custom( 'unpublishProcessor', 'unpublish.png',  'unpublish_f2.png', JText::_('UNPUBLISH_PROCESSOR'), true );
		JToolBarHelper::custom( 'newProcessor', 'new.png',  'new_f2.png', JText::_('NEW_PROCESSOR'), false );
		JToolBarHelper::custom( 'editProcessor', 'edit.png',  'edit_f2.png', JText::_('EDIT_PROCESSOR'), true );
		JToolBarHelper::custom( 'showCentral', 'extensions.png',  'extensions_f2.png', JText::_('CENTRAL_PAGE') , false );
	}

	function EDIT_PROCESSOR()
	{
		JToolBarHelper::custom( 'saveProcessor', 'save.png',  'save_f2.png', JText::_('SAVE_PROCESSOR'), false );
		JToolBarHelper::custom( 'applyProcessor', 'apply.png',  'apply_f2.png', JText::_('APPLY_PROCESSOR'), false );
		JToolBarHelper::custom( 'cancelProcessor', 'cancel.png',  'cancel_f2.png', JText::_('CANCEL_PROCESSOR'), false );
	}

	function LIST_SUBSCRIPTIONPLANS()
	{
		JToolBarHelper::custom( 'publishSubscriptionPlan', 'publish.png',  'publish_f2.png', JText::_('PUBLISH_PAYPLAN'), true );
		JToolBarHelper::custom( 'unpublishSubscriptionPlan', 'unpublish.png',  'unpublish_f2.png', JText::_('UNPUBLISH_PAYPLAN'), true );
		JToolBarHelper::custom( 'newSubscriptionPlan', 'new.png',  'new_f2.png', JText::_('NEW_PAYPLAN'), false );
		JToolBarHelper::custom( 'editSubscriptionPlan', 'edit.png',  'edit_f2.png', JText::_('EDIT_PAYPLAN'), true );
		JToolBarHelper::custom( 'copySubscriptionPlan', 'copy.png', 'copy_f2.png', JText::_('COPY_PAYPLAN'), true );
		JToolBarHelper::custom( 'removeSubscriptionPlan', 'delete.png',  'delete_f2.png', JText::_('REMOVE_PAYPLAN'), true );
		JToolBarHelper::custom( 'showCentral', 'extensions.png',  'extensions_f2.png', JText::_('CENTRAL_PAGE') , false );
	}

	function EDIT_SUBSCRIPTIONPLAN()
	{
		JToolBarHelper::custom( 'saveSubscriptionPlan', 'save.png',  'save_f2.png', JText::_('SAVE_PAYPLAN'), false );
		JToolBarHelper::custom( 'applySubscriptionPlan', 'apply.png',  'apply_f2.png', JText::_('APPLY_PAYPLAN'), false );
		JToolBarHelper::custom( 'cancelSubscriptionPlan', 'cancel.png',  'cancel_f2.png', JText::_('CANCEL_PAYPLAN'), false );
	}

	function LIST_ITEMGROUPS()
	{
		JToolBarHelper::custom( 'publishItemGroup', 'publish.png',  'publish_f2.png', JText::_('PUBLISH_ITEMGROUP'), true );
		JToolBarHelper::custom( 'unpublishItemGroup', 'unpublish.png',  'unpublish_f2.png', JText::_('UNPUBLISH_ITEMGROUP'), true );
		JToolBarHelper::custom( 'newItemGroup', 'new.png',  'new_f2.png', JText::_('NEW_ITEMGROUP'), false );
		JToolBarHelper::custom( 'editItemGroup', 'edit.png',  'edit_f2.png', JText::_('EDIT_ITEMGROUP'), true );
		JToolBarHelper::custom( 'copyItemGroup', 'copy.png', 'copy_f2.png', JText::_('COPY_ITEMGROUP'), true );
		JToolBarHelper::custom( 'removeItemGroup', 'delete.png',  'delete_f2.png', JText::_('REMOVE_ITEMGROUP'), true );
		JToolBarHelper::custom( 'showCentral', 'extensions.png',  'extensions_f2.png', JText::_('CENTRAL_PAGE') , false );
	}

	function EDIT_ITEMGROUP()
	{
		JToolBarHelper::custom( 'saveItemGroup', 'save.png',  'save_f2.png', JText::_('SAVE_ITEMGROUP'), false );
		JToolBarHelper::custom( 'applyItemGroup', 'apply.png',  'apply_f2.png', JText::_('APPLY_ITEMGROUP'), false );
		JToolBarHelper::custom( 'cancelItemGroup', 'cancel.png',  'cancel_f2.png', JText::_('CANCEL_ITEMGROUP'), false );
	}

	function LIST_MICROINTEGRATIONS()
	{
		JToolBarHelper::custom( 'publishMicroIntegration', 'publish.png',  'publish_f2.png', JText::_('PUBLISH_PAYPLAN'), true );
		JToolBarHelper::custom( 'unpublishMicroIntegration', 'unpublish.png',  'unpublish_f2.png', JText::_('UNPUBLISH_PAYPLAN'), true );
		JToolBarHelper::custom( 'newMicroIntegration', 'new.png',  'new_f2.png', JText::_('NEW_PAYPLAN'), false );
		JToolBarHelper::custom( 'editMicroIntegration', 'edit.png',  'edit_f2.png', JText::_('EDIT_PAYPLAN'), true );
		JToolBarHelper::custom( 'copyMicroIntegration', 'copy.png', 'copy_f2.png', JText::_('COPY_PAYPLAN'), true );
		JToolBarHelper::custom( 'removeMicroIntegration', 'delete.png',  'delete_f2.png', JText::_('REMOVE_PAYPLAN'), true );
		JToolBarHelper::custom( 'showCentral', 'extensions.png',  'extensions_f2.png', JText::_('CENTRAL_PAGE') , false );
	}

	function EDIT_MICROINTEGRATION()
	{
		JToolBarHelper::custom( 'saveMicroIntegration', 'save.png',  'save_f2.png', JText::_('SAVE_PAYPLAN'), false );
		JToolBarHelper::custom( 'applyMicroIntegration', 'apply.png',  'apply_f2.png', JText::_('APPLY_PAYPLAN'), false );
		JToolBarHelper::custom( 'cancelMicroIntegration', 'cancel.png',  'cancel_f2.png', JText::_('CANCEL_PAYPLAN'), false );
	}

	function LIST_COUPONS()
	{
		JToolBarHelper::custom( 'publishCoupon', 'publish.png',  'publish_f2.png', JText::_('PUBLISH_PAYPLAN'), true );
		JToolBarHelper::custom( 'unpublishCoupon', 'unpublish.png',  'unpublish_f2.png', JText::_('UNPUBLISH_PAYPLAN'), true );
		JToolBarHelper::custom( 'newCoupon', 'new.png',  'new_f2.png', JText::_('NEW_PAYPLAN'), false );
		JToolBarHelper::custom( 'editCoupon', 'edit.png',  'edit_f2.png', JText::_('EDIT_PAYPLAN'), true );
		JToolBarHelper::custom( 'copyCoupon', 'copy.png', 'copy_f2.png', 'Copy' );
		JToolBarHelper::custom( 'removeCoupon', 'delete.png',  'delete_f2.png', JText::_('REMOVE_PAYPLAN'), true );
		JToolBarHelper::custom( 'showCentral', 'extensions.png',  'extensions_f2.png', JText::_('CENTRAL_PAGE') , false );
	}

	function EDIT_COUPON()
	{
		JToolBarHelper::custom( 'saveCoupon', 'save.png',  'save_f2.png', JText::_('SAVE_PAYPLAN'), false );
		JToolBarHelper::custom( 'applyCoupon', 'apply.png',  'apply_f2.png', JText::_('APPLY_PAYPLAN'), false );
		JToolBarHelper::custom( 'cancelCoupon', 'cancel.png',  'cancel_f2.png', JText::_('CANCEL_PAYPLAN'), false );
	}

	function LIST_COUPONS_STATIC()
	{
		JToolBarHelper::custom( 'publishCouponStatic', 'publish.png',  'publish_f2.png', JText::_('PUBLISH_PAYPLAN'), true );
		JToolBarHelper::custom( 'unpublishCouponStatic', 'unpublish.png',  'unpublish_f2.png', JText::_('UNPUBLISH_PAYPLAN'), true );
		JToolBarHelper::custom( 'newCouponStatic', 'new.png',  'new_f2.png', JText::_('NEW_PAYPLAN'), false );
		JToolBarHelper::custom( 'editCouponStatic', 'edit.png',  'edit_f2.png', JText::_('EDIT_PAYPLAN'), true );
		JToolBarHelper::custom( 'copyCouponStatic', 'copy.png', 'copy_f2.png', JText::_('COPY_PAYPLAN'), true );
		JToolBarHelper::custom( 'removeCouponStatic', 'delete.png',  'delete_f2.png', JText::_('REMOVE_PAYPLAN'), true );
		JToolBarHelper::custom( 'showCentral', 'extensions.png',  'extensions_f2.png', JText::_('CENTRAL_PAGE') , false );
	}

	function EDIT_COUPON_STATIC()
	{
		JToolBarHelper::custom( 'saveCouponStatic', 'save.png',  'save_f2.png', JText::_('SAVE_PAYPLAN'), false );
		JToolBarHelper::custom( 'applyCouponStatic', 'apply.png',  'apply_f2.png', JText::_('APPLY_PAYPLAN'), false );
		JToolBarHelper::custom( 'cancelCouponStatic', 'cancel.png',  'cancel_f2.png', JText::_('CANCEL_PAYPLAN'), false );
	}

	function EDIT_EXPORT()
	{
		JToolBarHelper::custom( 'loadExport', 'next.png',  'next_f2.png', JText::_('EXPORT_LOAD'), false );
		JToolBarHelper::custom( 'applyExport', 'apply.png',  'apply_f2.png', JText::_('EXPORT_APPLY'), false );
		JToolBarHelper::custom( 'exportExport', 'download.png',  'download_f2.png', JText::_('AEC_HEAD_EXPORT'), false );
		JToolBarHelper::custom( 'saveExport', 'save.png',  'save_f2.png', JText::_('SAVE_PAYPLAN'), false );
		JToolBarHelper::custom( 'cancelSettings', 'cancel.png',  'cancel_f2.png', JText::_('CANCEL'), false );
	}

	function EDIT_IMPORT()
	{
		JToolBarHelper::custom( 'import', 'apply.png',  'apply_f2.png', JText::_('APPLY_PAYPLAN'), false );
		JToolBarHelper::custom( 'cancelSettings', 'cancel.png',  'cancel_f2.png', JText::_('CANCEL'), false );
	}

	function NO_MENU()
	{
		JToolBarHelper::back();
	}
}

class CommonMenu
{
	function EDIT_CSS_MENU()
	{
		JToolBarHelper::save( 'saveCSS' );
		JToolBarHelper::cancel('cancelCSS');
		JToolBarHelper::custom( 'showCentral', 'extensions.png',  'extensions_f2.png', JText::_('CENTRAL_PAGE') , false );
	}
}

?>
