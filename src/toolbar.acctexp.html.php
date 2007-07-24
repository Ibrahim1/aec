<?php
/**
 * @version $Id: toolbar.acctexp.html.php 16 2007-06-27 09:04:04Z mic $
 * @package AEC - Account Control Expiration - Subscription component for Joomla! OS CMS
 * @subpackage Menubar HTML Writer
 * @copyright Copyright (C) 2004-2007, All Rights Reserved, Helder Garcia, David Deutsch
 * @author Helder Garcia <helder.garcia@gmail.com>, David Deutsch <skore@skore.de> & Team AEC - http://www.gobalnerd.org
 * @license GNU/GPL v.2 http://www.gnu.org/copyleft/gpl.html
 */

// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License (GPL)
// as published by the Free Software Foundation; either version 2
// of the License, or (at your option) any later version.
//
// Please note that the GPL states that any headers in files and
// Copyright notices as well as credits in headers, source files
// and output (screens, prints, etc.) can not be removed.
// You can extend them with your own credits, though...
//
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with this program; if not, write to the Free Software
// Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.

// Dont allow direct linking
defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );

class ComponentMenu
{
	/**
	* Draws the menu
	*/
	function NEW_MENU()
	{  // To be deleted?
		mosMenuBar::startTable();
		mosMenuBar::custom( 'save', 'save.png',  'save_f2.png', _SAVE, false );
		mosMenuBar::custom( 'cancel', 'cancel.png',  'cancel_f2.png', _CANCEL, false );
		mosMenuBar::custom( 'showCentral', 'extensions.png',  'extensions_f2.png', _CENTRAL_PAGE , false );
		mosMenuBar::endTable();
	}

	function NEW_EXPIRATION()
	{
		mosMenuBar::startTable();
		mosMenuBar::custom( 'add', 'edit.png',  'edit_f2.png', _CONFIGURE );
		mosMenuBar::custom( 'showCentral', 'extensions.png',  'extensions_f2.png', _CENTRAL_PAGE , false );
		mosMenuBar::endTable();
	}

	function EDIT_EXPIRATION()
	{
		mosMenuBar::startTable();
		mosMenuBar::custom( 'edit', 'edit.png',  'edit_f2.png', _CONFIGURE );
		mosMenuBar::custom( 'expire', 'restore.png',  'restore_f2.png', _REMOVE );
		mosMenuBar::custom( 'showCentral', 'extensions.png',  'extensions_f2.png', _CENTRAL_PAGE , false );
		mosMenuBar::endTable();
	}

	function ADD_MENU()
	{
		mosMenuBar::startTable();
		mosMenuBar::custom( 'save', 'save.png',  'save_f2.png', _SAVE, false );
		mosMenuBar::custom( 'cancel', 'cancel.png',  'cancel_f2.png', _CANCEL, false );
		mosMenuBar::endTable();
	}

	function EDIT_MENU()
	{
		mosMenuBar::startTable();
		mosMenuBar::custom( 'save', 'save.png',  'save_f2.png', _SAVE, false );
		mosMenuBar::custom( 'cancel', 'cancel.png',  'cancel_f2.png', _CANCEL, false );
		mosMenuBar::endTable();
	}

	function EDIT_SETTINGS()
	{
		mosMenuBar::startTable();
		mosMenuBar::custom( 'saveSettings', 'save.png',  'save_f2.png', _SAVE, false );
		mosMenuBar::custom( 'cancelSettings', 'cancel.png',  'cancel_f2.png', _CANCEL, false );
		mosMenuBar::custom( 'showCentral', 'extensions.png',  'extensions_f2.png', _CENTRAL_PAGE , false );
		mosMenuBar::endTable();
	}

	function LIST_SUBSCRIPTIONPLANS()
	{
		mosMenuBar::startTable();
		mosMenuBar::custom( 'publishSubscriptionPlan', 'publish.png',  'publish_f2.png', _PUBLISH_PAYPLAN, true );
		mosMenuBar::custom( 'unpublishSubscriptionPlan', 'unpublish.png',  'unpublish_f2.png', _UNPUBLISH_PAYPLAN, true );
		mosMenuBar::custom( 'newSubscriptionPlan', 'new.png',  'new_f2.png', _NEW_PAYPLAN, false );
		mosMenuBar::custom( 'editSubscriptionPlan', 'edit.png',  'edit_f2.png', _EDIT_PAYPLAN, true );
		mosMenuBar::custom( 'removeSubscriptionPlan', 'delete.png',  'delete_f2.png', _REMOVE_PAYPLAN, true );
		mosMenuBar::custom( 'showCentral', 'extensions.png',  'extensions_f2.png', _CENTRAL_PAGE , false );
		mosMenuBar::endTable();
	}

	function EDIT_SUBSCRIPTIONPLAN()
	{
		mosMenuBar::startTable();
		mosMenuBar::custom( 'saveSubscriptionPlan', 'save.png',  'save_f2.png', _SAVE_PAYPLAN, false );
		mosMenuBar::custom( 'cancelSubscriptionPlan', 'cancel.png',  'cancel_f2.png', _CANCEL_PAYPLAN, false );
		mosMenuBar::endTable();
	}

	function LIST_MICROINTEGRATIONS()
	{
		mosMenuBar::startTable();
		mosMenuBar::custom( 'publishMicroIntegration', 'publish.png',  'publish_f2.png', _PUBLISH_PAYPLAN, true );
		mosMenuBar::custom( 'unpublishMicroIntegration', 'unpublish.png',  'unpublish_f2.png', _UNPUBLISH_PAYPLAN, true );
		mosMenuBar::custom( 'newMicroIntegration', 'new.png',  'new_f2.png', _NEW_PAYPLAN, false );
		mosMenuBar::custom( 'editMicroIntegration', 'edit.png',  'edit_f2.png', _EDIT_PAYPLAN, true );
		mosMenuBar::custom( 'removeMicroIntegration', 'delete.png',  'delete_f2.png', _REMOVE_PAYPLAN, true );
		mosMenuBar::custom( 'showCentral', 'extensions.png',  'extensions_f2.png', _CENTRAL_PAGE , false );
		mosMenuBar::endTable();
	}

	function EDIT_MICROINTEGRATION()
	{
		mosMenuBar::startTable();
		mosMenuBar::custom( 'saveMicroIntegration', 'save.png',  'save_f2.png', _SAVE_PAYPLAN, false );
		mosMenuBar::custom( 'cancelMicroIntegration', 'cancel.png',  'cancel_f2.png', _CANCEL_PAYPLAN, false );
		mosMenuBar::endTable();
	}

	function LIST_COUPONS()
	{
		mosMenuBar::startTable();
		mosMenuBar::custom( 'publishCoupon', 'publish.png',  'publish_f2.png', _PUBLISH_PAYPLAN, true );
		mosMenuBar::custom( 'unpublishCoupon', 'unpublish.png',  'unpublish_f2.png', _UNPUBLISH_PAYPLAN, true );
		mosMenuBar::custom( 'newCoupon', 'new.png',  'new_f2.png', _NEW_PAYPLAN, false );
		mosMenuBar::custom( 'editCoupon', 'edit.png',  'edit_f2.png', _EDIT_PAYPLAN, true );
		mosMenuBar::custom( 'removeCoupon', 'delete.png',  'delete_f2.png', _REMOVE_PAYPLAN, true );
		mosMenuBar::custom( 'showCentral', 'extensions.png',  'extensions_f2.png', _CENTRAL_PAGE , false );
		mosMenuBar::endTable();
	}

	function EDIT_COUPON()
	{
		mosMenuBar::startTable();
		mosMenuBar::custom( 'saveCoupon', 'save.png',  'save_f2.png', _SAVE_PAYPLAN, false );
		mosMenuBar::custom( 'cancelCoupon', 'cancel.png',  'cancel_f2.png', _CANCEL_PAYPLAN, false );
		mosMenuBar::endTable();
	}

	function LIST_COUPONS_STATIC()
	{
		mosMenuBar::startTable();
		mosMenuBar::custom( 'publishCouponStatic', 'publish.png',  'publish_f2.png', _PUBLISH_PAYPLAN, true );
		mosMenuBar::custom( 'unpublishCouponStatic', 'unpublish.png',  'unpublish_f2.png', _UNPUBLISH_PAYPLAN, true );
		mosMenuBar::custom( 'newCouponStatic', 'new.png',  'new_f2.png', _NEW_PAYPLAN, false );
		mosMenuBar::custom( 'editCouponStatic', 'edit.png',  'edit_f2.png', _EDIT_PAYPLAN, true );
		mosMenuBar::custom( 'removeCouponStatic', 'delete.png',  'delete_f2.png', _REMOVE_PAYPLAN, true );
		mosMenuBar::custom( 'showCentral', 'extensions.png',  'extensions_f2.png', _CENTRAL_PAGE , false );
		mosMenuBar::endTable();
	}

	function EDIT_COUPON_STATIC()
	{
		mosMenuBar::startTable();
		mosMenuBar::custom( 'saveCouponStatic', 'save.png',  'save_f2.png', _SAVE_PAYPLAN, false );
		mosMenuBar::custom( 'cancelCouponStatic', 'cancel.png',  'cancel_f2.png', _CANCEL_PAYPLAN, false );
		mosMenuBar::endTable();
	}

	function NO_MENU()
	{
		mosMenuBar::startTable();
		mosMenuBar::back();
		mosMenuBar::endTable();
	}

	function MINI_MENU()
	{
		aecMiniBar::_aecstartTable();
		aecMiniBar::_aeccustom( 'showCentral', 'aec_logo_tiny_grey.png',  'aec_logo_tiny.png', _CENTRAL_PAGE , false );
		aecMiniBar::_aecendTable();
	}
}

class CommonMenu
{
	function EDIT_CSS_MENU()
	{
		mosMenuBar::startTable();
		mosMenuBar::save( 'saveCSS' );
		mosMenuBar::cancel('cancelCSS');
		mosMenuBar::custom( 'showCentral', 'extensions.png',  'extensions_f2.png', _CENTRAL_PAGE , false );
		mosMenuBar::endTable();
	}
}

/**
 * adopted class from core
 *
 */
class aecMiniBar
{
	/**
	* Writes the start of the button bar table
	*/
	function _aecstartTable()
	{
		?>
		<script type="text/javascript">
		    /* <![CDATA[ */
            function MM_swapImgRestore() { //v3.0
            	var i,x,a=document.MM_sr; for(i=0;a&&i<a.length&&(x=a[i])&&x.oSrc;i++) x.src=x.oSrc;
            }
            /* ]]> */
		</script>
		<table cellpadding="0" cellspacing="0" border="0" id="aectoolbar">
		<tr valign="middle" align="center">
		<?php
	}

	/**
	* Writes the end of the menu bar table
	*/
	function _aecendTable()
	{
    	?>
		</tr>
		</table>
		<?php
	}

	/**
	* Writes a custom option and task button for the button bar WITHOUT text (only alt & title)
	* @param string		The task to perform (picked up by the switch ($task) blocks
	* @param string		The image to display
	* @param string		The image to display when moused over
	* @param string		The alt text for the icon image
	* @param boolean	True if required to check that a standard list item is checked
	* @param string		Hide menu
	*/
	function _aeccustom( $task='', $icon='', $iconOver='', $alt='', $listSelect = true, $path = '', $prefix = '' )
	{
		global $adminLanguage;

		if ( !$path ) {
			$path = '/administrator/components/com_acctexp/images/icons/';
		}

		$image	= mosAdminMenus::ImageCheckAdmin( $icon, $path, NULL, NULL, $alt, $task, 0 );
		$icon 	= ( $iconOver ? $iconOver : $icon );
		$image2	= mosAdminMenus::ImageCheckAdmin( $icon, $path, NULL, NULL, $alt, $task, 0 );

		if ( $listSelect ) {
			$js =  rawurlencode( 'if(document.adminForm.boxchecked.value==0){alert(\''
			. aecMiniBar::encodeENT( _AEC_ALERT_SELECT_FIRST_TO . ' ' . $alt )
			. '\');}else{' . $prefix . 'submitbutton(\''
			. $task . '\');}' )
			;
		} else {
			$js = $prefix .'submitbutton(\'' . $task . '\')';
		}

		$alt_tmp = str_replace( '<br />', ' ', $alt );

		if ( $icon && $iconOver ){
			?>
			<td>
				<a class="aectoolbar" href="javascript:<?php echo $js; ?>;" onmouseout="MM_swapImgRestore();" onmouseover="MM_swapImage('<?php echo $task; ?>','','<?php echo $image2; ?>',1);" title="<?php echo $alt_tmp; ?>"><img name="<?php echo $task; ?>" src="<?php echo $image; ?>" alt="<?php echo $alt_tmp; ?>" border="0" align="middle" /></a>
			</td>
            <?php
		} else {
			?>
			<td>
				<a class="aectoolbar" href="<?php echo $href; ?>" title="<?php echo $alt_tmp; ?>"></a>
			</td>
			<?php
		}
	}

	/**
	* Writes a custom option and task button for the button bar
	* same as _aeccustom PLUS hide menu items
	*/
	function _aeccustomX( $task='', $icon='', $iconOver='', $alt='', $listSelect=true, $path )
	{
        mosMenuBar::custom( $task, $icon, $iconOver, $alt, $listSelect, $path, 'hideMainMenu();' );
    }

    /**
	* encodes given language string and send encoded text back
	* new function by [ mic - www.joomx.com ] 2006.11.30
	*/
	function encodeENT( $text1, $text2='' )
	{
		$text = sprintf( html_entity_decode( $text1 ), html_entity_decode( $text2 ) );
		return $text;
	}
}
?>