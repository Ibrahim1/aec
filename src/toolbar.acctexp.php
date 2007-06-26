<?php /* $Id: toolbar.acctexp.php,v 1.2 2005/12/15 16:20:38 helder Exp $ */
/**
 * AcctExp Menubar Handler
 * @package AcctExp
* @copyright 2004-2007 Helder Garcia, David Deutsch
* @license http://www.gnu.org/copyleft/gpl.html. GNU Public License
* @version $Revision: 1.2 $
* @author Helder Garcia <helder.garcia@gmail.com>, David Deutsch <skore@skore.de>
**/
//
// Copyright (C) 2004-2007 Helder Garcia, David Deutsch
// All rights reserved.
// This source file is part of the Account Expiration Control Component, a  Joomla
// custom Component By Helder Garcia and David Deutsch - http://www.globalnerd.org
//
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
//
// The "GNU General Public License" (GPL) is available at
// http://www.gnu.org/copyleft/gpl.html.
//
// Dont allow direct linking
defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );

require_once( $mainframe->getPath( 'toolbar_html' ) );

if (!defined('_AEC_VERSION')) {
	if (file_exists( $mosConfig_absolute_path . '/administrator/components/com_acctexp/com_acctexp_language/'.$GLOBALS['mosConfig_lang'].'.php' )) {
			include( $mosConfig_absolute_path . '/administrator/components/com_acctexp/com_acctexp_language/'.$GLOBALS['mosConfig_lang'].'.php' );
	} else {
			include( $mosConfig_absolute_path . '/administrator/components/com_acctexp/com_acctexp_language/english.php' );
	}
}

switch ( $task ) {

	case "add":
		ComponentMenu::ADD_MENU();
		break;

	case "edit":
		ComponentMenu::EDIT_MENU();
		break;

	case "showSettings":
		ComponentMenu::EDIT_SETTINGS();
		break;

	case "showSubscriptionPlans":
		ComponentMenu::LIST_SUBSCRIPTIONPLANS();
		break;

	case "newSubscriptionPlan":
		ComponentMenu::EDIT_SUBSCRIPTIONPLAN();
		break;

	case "editSubscriptionPlan":
		ComponentMenu::EDIT_SUBSCRIPTIONPLAN();
		break;

	case "showMicroIntegrations":
		ComponentMenu::LIST_MICROINTEGRATIONS();
		break;

	case "newMicroIntegration":
		ComponentMenu::EDIT_MICROINTEGRATION();
		break;

	case "editMicroIntegration":
		ComponentMenu::EDIT_MICROINTEGRATION();
		break;

	case "showCoupons":
		ComponentMenu::LIST_COUPONS();
		break;

	case "newCoupon":
		ComponentMenu::EDIT_COUPON();
		break;

	case "editCoupon":
		ComponentMenu::EDIT_COUPON();
		break;

	case "showCouponsStatic":
		ComponentMenu::LIST_COUPONS_STATIC();
		break;

	case "newCouponStatic":
		ComponentMenu::EDIT_COUPON_STATIC();
		break;

	case "editCouponStatic":
		ComponentMenu::EDIT_COUPON_STATIC();
		break;

	case "editCSS":
		CommonMenu::EDIT_CSS_MENU();
		break;
		
	default:
		ComponentMenu::NO_MENU();
		break;
	}

?>

