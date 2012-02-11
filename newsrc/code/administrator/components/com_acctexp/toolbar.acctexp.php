<?php
/**
 * @version $Id: toolbar.acctexp.php
 * @package AEC - Account Control Expiration - Membership Manager
 * @subpackage Menubar Handler
 * @copyright 2006-2012 Copyright (C) David Deutsch
 * @author David Deutsch <skore@valanx.org> & Team AEC - http://www.valanx.org
 * @license GNU/GPL v.2 http://www.gnu.org/licenses/old-licenses/gpl-2.0.html or, at your option, any later version
 */

// Dont allow direct linking
( defined('_JEXEC') || defined( '_VALID_MOS' ) ) or die( 'Direct Access to this location is not allowed.' );

require_once( JApplicationHelper::getPath( 'toolbar_html' ) );

switch ( strtolower( $task ) ) {

	case 'add':
		ComponentMenu::ADD_MENU();
		break;

	case 'edit':
		ComponentMenu::EDIT_MENU();
		break;

	case 'showsettings':
		ComponentMenu::EDIT_SETTINGS();
		break;

	case 'showprocessors':
		ComponentMenu::LIST_PROCESSORS();
		break;

	case 'newprocessor':
		ComponentMenu::EDIT_PROCESSOR();
		break;

	case 'editprocessor':
		ComponentMenu::EDIT_PROCESSOR();
		break;

	case 'showsubscriptionplans':
		ComponentMenu::LIST_SUBSCRIPTIONPLANS();
		break;

	case 'newsubscriptionplan':
		ComponentMenu::EDIT_SUBSCRIPTIONPLAN();
		break;

	case 'editsubscriptionplan':
		ComponentMenu::EDIT_SUBSCRIPTIONPLAN();
		break;

	case 'showitemgroups':
		ComponentMenu::LIST_ITEMGROUPS();
		break;

	case 'newitemgroup':
		ComponentMenu::EDIT_ITEMGROUP();
		break;

	case 'edititemgroup':
		ComponentMenu::EDIT_ITEMGROUP();
		break;

	case 'showmicrointegrations':
		ComponentMenu::LIST_MICROINTEGRATIONS();
		break;

	case 'newmicrointegration':
		ComponentMenu::EDIT_MICROINTEGRATION();
		break;

	case 'editmicrointegration':
		ComponentMenu::EDIT_MICROINTEGRATION();
		break;

	case 'showcoupons':
		ComponentMenu::LIST_COUPONS();
		break;

	case 'newcoupon':
		ComponentMenu::EDIT_COUPON();
		break;

	case 'editcoupon':
		ComponentMenu::EDIT_COUPON();
		break;

	case 'showcouponsstatic':
		ComponentMenu::LIST_COUPONS_STATIC();
		break;

	case 'newcouponstatic':
		ComponentMenu::EDIT_COUPON_STATIC();
		break;

	case 'editcouponstatic':
		ComponentMenu::EDIT_COUPON_STATIC();
		break;

	case 'loadexportsales':
	case 'applyexportsales':
	case 'saveexportsales':
	case 'exportexportsales':
	case 'exportsales':
	case 'loadexportmembers':
	case 'applyexportmembers':
	case 'saveexportmembers':
	case 'exportexportmembers':
	case 'exportmembers':
		ComponentMenu::EDIT_EXPORT();
		break;

	case 'import':
		ComponentMenu::EDIT_IMPORT();
		break;

	case 'editcss':
		CommonMenu::EDIT_CSS_MENU();
		break;

	case 'showactive':
	case 'showcancelled':
	case 'showclosed':
	case 'showexpired':
	case 'showexcluded':
	case 'showmanual':
	case 'showpending':
	case 'showsubscriptions':
		ComponentMenu::EDIT_EXPIRATION();
		break;

	case 'eventlog':
	case 'hacks':
	case 'help':
	case 'history':
	case 'invoices':
		ComponentMenu::NO_MENU();
		break;

	default:
		ComponentMenu::NO_MENU();
		break;
}
?>
