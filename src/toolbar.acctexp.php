<?php
/**
 * @version $Id: toolbar.acctexp.php 16 2007-06-27 09:04:04Z mic $
 * @package AEC - Account Control Expiration - Subscription component for Joomla! OS CMS
 * @subpackage Menubar Handler
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

require_once( $mainframe->getPath( 'toolbar_html' ) );

switch ( $task ) {

	case 'add':
		ComponentMenu::ADD_MENU();
		break;

	case 'edit':
		ComponentMenu::EDIT_MENU();
		break;

	case 'showSettings':
		ComponentMenu::EDIT_SETTINGS();
		break;

	case 'showSubscriptionPlans':
		ComponentMenu::LIST_SUBSCRIPTIONPLANS();
		break;

	case 'newSubscriptionPlan':
		ComponentMenu::EDIT_SUBSCRIPTIONPLAN();
		break;

	case 'editSubscriptionPlan':
		ComponentMenu::EDIT_SUBSCRIPTIONPLAN();
		break;

	case 'showMicroIntegrations':
		ComponentMenu::LIST_MICROINTEGRATIONS();
		break;

	case 'newMicroIntegration':
		ComponentMenu::EDIT_MICROINTEGRATION();
		break;

	case 'editMicroIntegration':
		ComponentMenu::EDIT_MICROINTEGRATION();
		break;

	case 'showCoupons':
		ComponentMenu::LIST_COUPONS();
		break;

	case 'newCoupon':
		ComponentMenu::EDIT_COUPON();
		break;

	case 'editCoupon':
		ComponentMenu::EDIT_COUPON();
		break;

	case 'showCouponsStatic':
		ComponentMenu::LIST_COUPONS_STATIC();
		break;

	case 'newCouponStatic':
		ComponentMenu::EDIT_COUPON_STATIC();
		break;

	case 'editCouponStatic':
		ComponentMenu::EDIT_COUPON_STATIC();
		break;

	case 'export':
		ComponentMenu::EDIT_EXPORT();
		break;

	case 'import':
		ComponentMenu::EDIT_IMPORT();
		break;

	case 'editCSS':
		CommonMenu::EDIT_CSS_MENU();
		break;

	case 'eventlog':
	case 'hacks':
	case 'help':
	case 'history':
	case 'invoices':
	case 'showActive':
	case 'showCancelled':
	case 'showClosed':
	case 'showExpired':
	case 'showExcluded':
	case 'showManual':
	case 'showPending':
		ComponentMenu::NO_MENU();
		break;

	default:
		ComponentMenu::NO_MENU();
		break;
}
?>