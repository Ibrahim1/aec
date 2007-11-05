<?php
/**
 * @version $Id: admin.acctexp.php 16 2007-06-27 09:04:04Z mic $
 * @package AEC - Account Control Expiration - Subscription component for Joomla! OS CMS
 * @subpackage Main Backend
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

// no direct access
defined( '_VALID_MOS' ) or die( 'Restricted access' );

global $aecConfig;

require_once( $mosConfig_absolute_path . '/components/com_acctexp/lib/eucalib/eucalib.php' );
require_once( $mosConfig_absolute_path . '/components/com_acctexp/lib/eucalib/eucalib.proxy.php' );

require_once( $mainframe->getPath( 'admin_html' ) );
require_once( $mainframe->getPath( 'class' ) );

$aecConfig = new Config_General( $database );

if ( !defined( '_EUCA_DEBUGMODE' ) ) {
	define( '_EUCA_DEBUGMODE', $aecConfig->cfg['debugmode'] );
}

if ( _EUCA_DEBUGMODE ) {
	global $eucaDebug;

	$eucaDebug = new eucaDebug();
}

if (!$acl->acl_check( 'administration', 'config', 'users', $my->usertype )) {

	if ( !( ( strcmp( $my->usertype, 'Administrator' ) === 0) && $aecConfig->cfg['adminaccess'] ) ) {
		mosRedirect( 'index2.php', _NOT_AUTH );
	}
}

$langPathBE = $mosConfig_absolute_path . '/administrator/components/com_acctexp/com_acctexp_language_backend/';
if ( file_exists( $langPathBE . $GLOBALS['mosConfig_lang'] . '.php' ) ) {
	include_once( $langPathBE . $GLOBALS['mosConfig_lang'] . '.php' );
} else {
	include_once( $langPathBE . 'english.php' );
}

$langPathPROC = $mosConfig_absolute_path . '/components/com_acctexp/processors/com_acctexp_language_processors/';
if ( file_exists( $langPathPROC . $GLOBALS['mosConfig_lang']. '.php' ) ) {
	include_once( $langPathPROC . $GLOBALS['mosConfig_lang'] . '.php' );
} else {
	include_once( $langPathPROC . 'english.php' );
}

include_once( $langPathBE . 'general.php' );

$task		= trim( mosGetParam( $_REQUEST, 'task', null ) );
$returnTask = trim( mosGetParam( $_REQUEST, 'returnTask', null ) );
$userid		= mosGetParam( $_REQUEST, 'userid', null );
$id			= mosGetParam( $_REQUEST, 'id', null );

// Auto Heartbeat renew every one hour to make sure that the admin gets a view as recent as possible
$heartbeat = new aecHeartbeat( $database );
$heartbeat->backendping();

switch( strtolower( $task ) ) {
	case 'heartbeat':
	case 'beat':
		// Manual Heartbeat
		aecHeartbeat::beat();
		echo "wolves teeth";
		break;

	case 'add':
		editUser( null, $userid, $option, 'notconfig' );
		break;

	case 'edit':
		if ( !is_array( $userid ) ) {
			$userid = array();
			$userid[0] = trim( mosGetParam( $_REQUEST, 'userid', '' ) );
		}

		editUser( $userid, $option, $returnTask );
		break;

	case 'save':
		saveUser( $option );
		break;

	case 'cancel':
		cancel( $option );
		break;

	case 'showcentral':
		HTML_AcctExp::central();
		break;

	case 'clearpayment':
		$invoice	= trim( mosGetParam( $_REQUEST, 'invoice', '' ) );
		$applyplan	= trim( mosGetParam( $_REQUEST, 'applyplan', '0' ) );
		clearInvoice($option, $invoice, $applyplan, $returnTask);
		break;

	case 'cancelpayment':
		$invoice	= trim( mosGetParam( $_REQUEST, 'invoice', '' ) );
		cancelInvoice( $option, $invoice, $returnTask );
		break;

	case 'removeclosed':
		removeClosedSubscription( $userid, $option );
		break;

	case 'removeuser':
		removeUser( $userid, $option );
		break;

	case 'removepending':
		removePendingSubscription( $userid, $option );
		break;

	case 'activatepending':
		activatePendingSubscription( $userid, $option, 0 );
		break;

	case 'renewoffline':
		activatePendingSubscription( $userid, $option, 1 );
		break;

	case 'closeactive':
		closeActiveSubscription( $userid, $option, $returnTask );
		break;

	case 'showsubscriptions':
		listSubscriptions( $option, 'active', $userid );
		break;

	case 'showexcluded':
		listSubscriptions( $option, 'excluded', $userid );
		break;

	case 'showactive':
		listSubscriptions( $option, 'active', $userid );
		break;

	case 'showexpired':
		listSubscriptions( $option, 'expired', $userid );
		break;

	case 'showpending':
		listSubscriptions( $option, 'pending', $userid );
		break;

	case 'showcancelled':
		listSubscriptions( $option, 'cancelled', $userid );
		break;

	case 'showclosed':
		listSubscriptions( $option, 'closed', $userid );
		break;

	case 'showmanual':
		listSubscriptions( $option, 'notconfig', $userid );
		break;

	case 'showsettings':
		editSettings( $option );
		break;

	case 'savesettings':
		saveSettings( $option );
		break;

	case 'apply':
        saveSettings( $option );
        break;

	case 'cancelsettings':
		cancelSettings( $option );
		break;

    case 'remove':
		remove( $id, $option, $returnTask );
		break;

    case 'exclude':
		exclude( $id, $option, $returnTask );
		break;

	case 'showsubscriptionplans':
		listSubscriptionPlans( $option );
		break;

	case 'newsubscriptionplan':
		editSubscriptionPlan( 0, $option );
		break;

	case 'editsubscriptionplan':
		editSubscriptionPlan( $id[0], $option );
		break;

	case 'savesubscriptionplan':
		saveSubscriptionPlan( $option );
		break;

	case 'publishsubscriptionplan':
		changeSubscriptionPlan( $id, 1, 'active', $option );
		break;

	case 'unpublishsubscriptionplan':
		changeSubscriptionPlan( $id, 0, 'active', $option );
		break;

	case 'visiblesubscriptionplan':
		changeSubscriptionPlan( $id, 1, 'visible', $option );
		break;

	case 'invisiblesubscriptionplan':
		changeSubscriptionPlan( $id, 0, 'visible', $option );
		break;

	case 'removesubscriptionplan':
		removeSubscriptionPlan( $id, $option, $returnTask );
		break;

	case 'cancelsubscriptionplan':
		cancelSubscriptionPlan( $option );
		break;

    case 'orderplanup':
	    global $database;
	    $row = new SubscriptionPlan( $database );
	    $row->load( $id[0] );
	    $row->move( -1 );
	    mosRedirect( 'index2.php?option='. $option . '&task=showSubscriptionPlans' );
        break;

    case 'orderplandown':
	    global $database;
	    $row = new SubscriptionPlan( $database );
	    $row->load( $id[0] );
	    $row->move( 1 );
	    mosRedirect( 'index2.php?option='. $option . '&task=showSubscriptionPlans' );
        break;

	case 'showmicrointegrations':
		listMicroIntegrations( $option );
		break;

	case 'newmicrointegration':
		editMicroIntegration( 0, $option );
		break;

	case 'editmicrointegration':
		editMicroIntegration( $id[0], $option );
		break;

	case 'savemicrointegration':
		saveMicroIntegration( $option );
		break;

	case 'publishmicrointegration':
		changeMicroIntegration( $id, 1, $option );
		break;

	case 'unpublishmicrointegration':
		changeMicroIntegration( $id, 0, $option );
		break;

	case 'removemicrointegration':
		removeMicroIntegration( $id, $option, $returnTask );
		break;

	case 'cancelmicrointegration':
		cancelMicroIntegration( $option );
		break;

    case 'ordermiup':
	    global $database;
	    $row = new microIntegration( $database );
	    $row->load( $id[0] );
	    $row->move( -1 );
	    mosRedirect( 'index2.php?option='. $option . '&task=showMicroIntegrations' );
        break;

    case 'ordermidown':
	    global $database;
	    $row = new microIntegration( $database );
	    $row->load( $id[0] );
	    $row->move( 1 );
	    mosRedirect( 'index2.php?option='. $option . '&task=showMicroIntegrations' );
        break;

	case 'showcoupons':
		listCoupons( $option, 0);
		break;

	case 'newcoupon':
		editCoupon( 0, $option, 1, 0 );
		break;

	case 'editcoupon':
		editCoupon( $id[0], $option, 0, 0 );
		break;

	case 'savecoupon':
		saveCoupon( $option, 0 );
		break;

	case 'publishcoupon':
		changeCoupon( $id, 1, $option, 0 );
		break;

	case 'unpublishcoupon':
		changeCoupon( $id, 0, $option, 0 );
		break;

	case 'removecoupon':
		removeCoupon( $id, $option, $returnTask, 0 );
		break;

	case 'cancelcoupon':
		cancelCoupon( $option, 0 );
		break;

	case 'showcouponsstatic':
		listCoupons( $option, 1);
		break;

	case 'newcouponstatic':
		editCoupon( 0, $option, 1, 1 );
		break;

	case 'editcouponstatic':
		editCoupon( $id[0], $option, 0, 1 );
		break;

	case 'savecouponstatic':
		saveCoupon( $option, 1 );
		break;

	case 'publishcouponstatic':
		changeCoupon( $id, 1, $option, 1 );
		break;

	case 'unpublishcouponstatic':
		changeCoupon( $id, 0, $option, 1 );
		break;

	case 'removecouponstatic':
		removeCoupon( $id, $option, $returnTask, 1 );
		break;

	case 'cancelcouponstatic':
		cancelCoupon( $option, 1 );
		break;

    case 'ordercouponup':
	    global $database;
	    $row = new coupon( $database, 0 );
	    $row->load( $id[0] );
	    $row->move( -1 );
	    mosRedirect( 'index2.php?option='. $option . '&task=showCoupons' );
        break;

    case 'ordercoupondown':
	    global $database;
	    $row = new coupon( $database, 0 );
	    $row->load( $id[0] );
	    $row->move( 1 );
	    mosRedirect( 'index2.php?option='. $option . '&task=showCoupons' );
        break;

    case 'ordercouponstaticup':
	    global $database;
	    $row = new coupon( $database, 1 );
	    $row->load( $id[0] );
	    $row->move( -1 );
	    mosRedirect( 'index2.php?option='. $option . '&task=showCouponsStatic' );
        break;

    case 'ordercouponstaticdown':
	    global $database;
	    $row = new coupon( $database, 1 );
	    $row->load( $id[0] );
	    $row->move( 1 );
	    mosRedirect( 'index2.php?option='. $option . '&task=showCouponsStatic' );
        break;

	case 'editcss':
		editCSS( $option );
		break;

	case 'savecss':
		saveCSS( $option );
		break;

	case 'cancelcss':
		cancelCSS( $option );
		break;

    case 'about':
		about( );
		break;

    case 'hacks':
		$undohack	= mosGetParam( $_REQUEST, 'undohack', 0 );
		$filename	= mosGetParam( $_REQUEST, 'filename', 0 );
		$check_hack	= $filename ? 0 : 1;

		hackcorefile( $option, $filename, $check_hack, $undohack );

		HTML_AcctExp::hacks( $option, hackcorefile( $option, 0, 1, 0 ) );
		break;

    case 'help':
		help ( $option );
		break;

	case 'invoices':
		invoices ( $option );
		break;

	case 'history':
		history ( $option );
		break;

	case 'eventlog':
		eventlog ( $option );
		break;

	case 'credits':
		HTML_AcctExp::credits();
		break;

	case 'migrate':
		migrate ( $option );
		break;

	case 'quicklookup':
		$return = quicklookup ( $option );

		if ( strlen( $return ) > 32 ) {
			HTML_AcctExp::central( $return );
		} elseif ( $return ) {
			mosRedirect( 'index2.php?option=' . $option . '&task=edit&userid=' . $return, _AEC_QUICKSEARCH_THANKS );
		} else {
			mosRedirect( 'index2.php?option=' . $option . '&task=showcentral', _AEC_QUICKSEARCH_NOTFOUND );
		}
		break;

	default:
		HTML_AcctExp::central();
		break;
}

/**
* Remove user from list of expirable accounts
*/
function remove( $userid, $option, $task )
{
	global $database;

	// $userid contains values corresponding to id field of #__acctexp table
    if ( !is_array( $userid ) || count( $userid ) < 1 ) {
	    echo '<script>alert(\'' . _AEC_ALERT_SELECT_FIRST . '\');window.history.go(-1);</script>' . "\n";
	    exit();
    }

	foreach ( $userid as $id ) {
		$subscription = new Subscription( $database );
		$subscription->loadUserid( $userid );

		$subscription->setStatus( 'Excluded' );
	}

	$userids = implode( ',', $userid );

	$query = 'DELETE'
	. ' FROM #__acctexp'
	. ' WHERE userid IN (' . $userids . ')'
	;
 	$database->setQuery( $query );
	if ( !$database->query() ) {
		echo '<script>alert(\'' . $database->getErrorMsg() . '\');window.history.go(-1); </script>' . "\n";
	}

	mosRedirect( 'index2.php?option=' . $option . '&task=' . $task, _REMOVED );
}

/**
* Cancels an edit operation
*/
function cancel( $option )
{
	global $database, $mainframe, $mosConfig_list_limit;

 	$limit		= $mainframe->getUserStateFromRequest( "viewlistlimit", 'limit', $mosConfig_list_limit );
	$limitstart = $mainframe->getUserStateFromRequest( "viewnotconf{$option}limitstart", 'limitstart', 0 );
	$nexttask	= mosGetParam( $_REQUEST, 'nexttask', 'config' ) ;

	mosRedirect( 'index2.php?option=' . $option . '&task=' . $nexttask, _CANCELED );
}

function help( $option )
{
	global $database, $mainframe, $mosConfig_live_site, $aecConfig;

	// diagnostic is the overall array that stores relational data that either gets transferred directly into the
	// diagnose array or that is used in the process of that
	$diagonstic = array();

	// Check for correct Global Settings:
	$diagnostic['reachable']			= ( substr_count( $mainframe->getCfg( 'live_site' ), 'http://' ) || substr_count( $mainframe->getCfg( 'live_site' ), 'https://' ) );
	$diagnostic['offline']				= $mainframe->getCfg( 'offline' );
	$diagnostic['user_registration']	= $mainframe->getCfg( 'allowUserRegistration' );
	$diagnostic['login_possible']		= $mainframe->getCfg( 'frontend_login' );

	$paypal = new PaymentProcessor();
	$paypal->loadName( 'paypal' );
	if ( $paypal->id ) {
		$paypal->init();
		$paypal->getSettings();

		$diagnostic['paypal']			= 1;
		$diagnostic['pp_checkbusiness'] = $paypal->settings['checkbusiness'];
	}

	$hacks = hackcorefile( $option, 0, 1, 0 );

	if ( isset( $_SERVER['SERVER_SOFTWARE'] ) ) {
		$diagnostic['server_software']	= $_SERVER['SERVER_SOFTWARE'];
	}

	// Test for Components to be integrated
	$diagnostic['cb']					= GeneralInfoRequester::detect_component( 'CB' );
	$diagnostic['cbe']					= GeneralInfoRequester::detect_component( 'CBE' );
	$diagnostic['jacl']					= GeneralInfoRequester::detect_component( 'JACL' );
	// general
	$diagnostic['permission_problem'] 	= 0;
	$diagnostic['hacks_legacy']			= 0;

	// Test for file permissions
	foreach ( $hacks as $hack_name => $hack_content ) {
		$diagnostic['hack_' . $hack_name] = ( !empty( $hack_content['status'] ) && $hack_content['status'] > 0 ) ? 1 : 0;

		if ( !empty( $hack_content['legacy'] ) && ( $hack_content['status'] != 0 ) ) {
			$diagnostic['hacks_legacy'] = 1;
		}
		if ( isset( $hack_content['fileinfo']['gecos'] ) ) {
			$diagnostic['hack_' . $hack_name . '_permission'] = ( strpos( $hack_content['fileinfo']['gecos'], 'apache' ) ) ? 1 : 0;
			if ( !$diagnostic['hack_' . $hack_name] ) {
				if ( !$diagnostic['hack_' . $hack_name . '_permission'] && ( $hack_content['status'] == 0 ) ) {
					$diagnostic['permission_problem']++;
				}
			}
		}
	}

	$diagnostic['posix_getpwuid_available'] = function_exists( 'posix_getpwuid' );

	$objentry	= null;

	$query = 'SELECT *'
	. ' FROM #__acctexp_plans'
 	. ' WHERE active = \'1\''
 	;
 	$database->setQuery( $query );
 	$database->loadObject( $objentry );

 	if ( $objentry ) { // mic: fixed php.notice if no active plans / $objentry->id
 		$diagnostic['no_plan'] = 0;
 	} else {
 		$diagnostic['no_plan'] = 1;
 	}

 	if ( $aecConfig->cfg['entry_plan'] ) {
 		$diagnostic['global_entry'] = 1;
 	} else {
 		$diagnostic['global_entry'] = 0;
 	}

	// TODO: No gateway notify

	// Check for Modules and whether they are enabled
	$modules = array();
	$modules[] = array( 'mod_comprofilermoderator',	'cb_comprofilermoderator', 'cb_comprofilermoderator_enabled' );

	$mod_check = null;
	for( $i = 0; $i < count( $modules ); $i++ ) {
		$result = null;
		$module = $modules[$i][0];

		$query = 'SELECT published'
		. ' FROM #__modules'
		. ' WHERE module = \'' . $module . '\''
		;
		$database->setQuery( $query );
		$result = $database->loadResult();

		if ( $result == 1 ) {
			$diagnostic[$modules[$i][1]] = 1;
			$diagnostic[$modules[$i][2]] = 1;
		} elseif ( $result == 0 ) {
			$diagnostic[$modules[$i][1]] = 1;
			$diagnostic[$modules[$i][2]] = 0;
		} elseif ( $result == null ) {
			$diagnostic[$modules[$i][1]] = 0;
			$diagnostic[$modules[$i][2]] = 0;
		}
	}

	// Paypal specific

	// TODO: Check for joomla New User Activation setting

	/*	The idea for this is to provide one page that checks the most neccessary recommendations:
		- Activate different kinds of integrations (Started)
			- No: Better only show that the AEC has detected components and is already integrating
				-SMF? Both integrations/bridges? SEO?
	*/

	/**
	 * Diagnose Helper
	 * Syntax:
	 * 1. Name
	 * 2. Status
	 * 3. Importance (1 - Low | 2 - Recommended | 3 - Critical)
	 * 4. Explaination
	 * 5. Advice
	 * 6. Detect Only (0:No, 1:Yes -Don't display if Status=0)
	 */
	$pdfPath = $mainframe->getCfg( 'live_site' ) . '/administrator/components/com_acctexp/manual/';
	if ( file_exists( $mainframe->getCfg( 'absolute_path' ) . '/administrator/components/com_acctexp/manual/AEC_Quickstart.' . _AEC_LANGUAGE . '.pdf' ) ) {
		$pdfHelp = $pdfPath . 'AEC_Quickstart.' . _AEC_LANGUAGE . '.pdf';
	} else {
		$pdfHelp = $pdfPath . 'AEC_Quickstart.pdf';
	}

	$diagnose	= array();
	$diagnose[]	= array( _AEC_HELP_QS_HEADER, 1, 1, aecHTML::Icon( 'page_white_acrobat.png' ) . sprintf( _AEC_HELP_QS_DESC, '<a href="' . $pdfHelp . '" target="_blank" title="' . _AEC_HELP_QS_DESC_LTEXT . '">' . _AEC_HELP_QS_DESC_LTEXT . '</a>' ), '', 0 );

/*
	$diagnose[]	= array("AEC Version", $diagnostic['AEC_stable'], 1, "You are running the most recent stable Version of the AEC", 0, 1);
	$diagnose[]	= array("AEC Version", !$diagnostic['AEC_stable'], 3, "There appears to be a more recent Version of the AEC available on <a href=\"http://www.globalnerd.org\">www.globalnerd.org</a>", 0, 1);
*/
	// Apache related file permission problems
	if ( substr_count( $diagnostic['server_software'], 'Apache' ) ) {
		if ( $diagnostic['posix_getpwuid_available'] ) {
				$diagnose[]	= array(
					_AEC_HELP_SER_SW_DIAG1,
					$diagnostic['permission_problem'],
					3,
					_AEC_HELP_SER_SW_DIAG1_DESC,
					_AEC_HELP_SER_SW_DIAG1_DESC2,
					1
				);
				$diagnose[]	= array(
					_AEC_HELP_SER_SW_DIAG2,
					!@$diagnostic['hack_joomlaphp_permission'],
					3,
					_AEC_HELP_SER_SW_DIAG2_DESC,
					_AEC_HELP_SER_SW_DIAG2_DESC2,
					1
				);
				$diagnose[]	= array(
					_AEC_HELP_SER_SW_DIAG3,
					$diagnostic['hacks_legacy'],
					3,
					_AEC_HELP_SER_SW_DIAG3_DESC,
					_AEC_HELP_SER_SW_DIAG3_DESC2,
					1
				);
			} else {
			$diagnose[]	= array(
				_AEC_HELP_SER_SW_DIAG4,
				!$diagnostic['posix_getpwuid_available'],
				3,
				_AEC_HELP_SER_SW_DIAG4_DESC,
				_AEC_HELP_SER_SW_DIAG4_DESC2,
				1
			);
		}
	}

	// generic CMS changes
	$diagnose[]	= array(
		_AEC_HELP_DIAG_CMN1,
		$diagnostic['hack_joomlaphp4'],
		3,
		_AEC_HELP_DIAG_CMN1_DESC,
		_AEC_HELP_DIAG_CMN1_DESC2,
		0
	);

	// menu entry
	$diagnose[]	= array(
		_AEC_HELP_DIAG_CMN2,
		$diagnostic['hack_menuentry'],
		2,
		_AEC_HELP_DIAG_CMN2_DESC,
		_AEC_HELP_DIAG_CMN2_DESC2,
		0
	);

	// no active plan
	$diagnose[]	= array(
		_AEC_HELP_DIAG_NO_PAY_PLAN,
		$diagnostic['no_plan'],
		3,
		_AEC_HELP_DIAG_NO_PAY_PLAN_DESC,
		0,
		1
	);

	// global entry plan
	$diagnose[]	= array(
		_AEC_HELP_DIAG_GLOBAL_PLAN,
		$diagnostic['global_entry'],
		1,
		_AEC_HELP_DIAG_GLOBAL_PLAN_DESC,
		0,
		1
	);

	// server is not reachable
	$diagnose[]	= array(
		_AEC_HELP_DIAG_SERVER_NOT_REACHABLE,
		!$diagnostic['reachable'],
		3,
		_AEC_HELP_DIAG_SERVER_NOT_REACHABLE_DESC,
		0,
		1
	);

	// site offline
	$diagnose[]	= array(
		_AEC_HELP_DIAG_SITE_OFFLINE,
		$diagnostic['offline'],
		3,
		_AEC_HELP_DIAG_SITE_OFFLINE_DESC,
		0,
		1
	);

	// disabled registration
	$diagnose[]	= array(
		_AEC_HELP_DIAG_REG_DISABLED,
		!$diagnostic['user_registration'],
		2,
		_AEC_HELP_DIAG_REG_DISABLED_DESC,
		0,
		1
	);

	// login disabled
	$diagnose[]	= array(
		_AEC_HELP_DIAG_LOGIN_DISABLED,
		!$diagnostic['login_possible'],
		2,
		_AEC_HELP_DIAG_LOGIN_DISABLED_DESC,
		0,
		1
	);

	// check JACL
	$diagnose[]	= array(
		_AEC_HELP_DIAG_CMN3,
		!$diagnostic['jacl'],
		1,
		_AEC_HELP_DIAG_CMN3_DESC,
		0,
		1
	);

	if ( $diagnostic['paypal'] ) {
		$diagnose[]	= array(
			_AEC_HELP_DIAG_PAYPAL_BUSS_ID,
			$diagnostic['pp_checkbusiness'],
			2,
			_AEC_HELP_DIAG_PAYPAL_BUSS_ID_DESC,
			_AEC_HELP_DIAG_PAYPAL_BUSS_ID_DESC1,
			1
		);
	}

	HTML_AcctExp::help( $option, $diagnose ) ;
}

function editUser( $userid, $option, $task )
{
	global $database, $mainframe;

	$lists = array();

	$expirationid = AECfetchfromDB::ExpirationIDfromUserID( $userid[0] );

	if ( !$expirationid ) {
		// Excluded
		$expiration->userid = $userid[0];
	} else {
		$expiration = new AcctExp( $database );
		$expiration->load( $expirationid );
	}

	$user = new mosUser( $database );
	$user->load( $userid[0] );

	$subscription = new Subscription( $database );
	$subscription->loadUserID( $user->id );

 	// count number of payments of user
 	$query = 'SELECT count(*)'
 	. ' FROM #__acctexp_invoices'
 	. ' WHERE userid = \'' . $userid[0] . '\''
 	;
 	$database->setQuery( $query );

 	$invoices_total	= $database->loadResult();
	$invoices_limit	= 10;	// Returns last 10 payments
	if ( $invoices_total > $invoices_limit ) {
 		$invoices_min_limit	= $invoices_total - $invoices_limit;
	} else {
		$invoices_min_limit	= 0;
	}

 	// get payments of user
 	$query = 'SELECT id'
 	. ' FROM #__acctexp_invoices'
 	. ' WHERE userid = \'' . $userid[0] . '\''
 	. ' ORDER BY transaction_date DESC'
 	. ' LIMIT ' . $invoices_min_limit . ',' . $invoices_limit
 	;
 	$database->setQuery( $query );
 	$invoice_ids = $database->loadResultArray();
 	if ( $database->getErrorNum() ) {
 		echo $database->stderr();
 		return false;
 	}

	$group_selection = array();
	$group_selection[] = mosHTML::makeOption( '',			_EXPIRE_SET );
	$group_selection[] = mosHTML::makeOption( 'now',		_EXPIRE_NOW );
	$group_selection[] = mosHTML::makeOption( 'exclude',	_EXPIRE_EXCLUDE );
	$group_selection[] = mosHTML::makeOption( 'include',	_EXPIRE_INCLUDE );
	$group_selection[] = mosHTML::makeOption( 'close',		_EXPIRE_CLOSE );

	$lists['set_status'] = mosHTML::selectList( $group_selection, 'set_status', 'class="inputbox" size="1"', 'value', 'text', '' );

	$invoices = array();
	foreach ( $invoice_ids as $inv_id ) {
		$invoice = new Invoice( $database );
		$invoice->load ($inv_id );

		$status = 'uncleared';

		$params = $invoice->getParams();
		if ( isset( $params['deactivated'] ) ) {
			$status = 'deactivated';
		} elseif ( isset( $params['pending_reason'] ) ) {
			if (  defined( '_PAYMENT_PENDING_REASON_' . strtoupper( $params['pending_reason'] ) ) ) {
				$status = constant( '_PAYMENT_PENDING_REASON_' . strtoupper($params['pending_reason'] ) );
			} else {
				$status = $params['pending_reason'];
			}
		}

		if ( strcmp( $invoice->transaction_date, '0000-00-00 00:00:00' ) === 0 ) {
			$actions = '<a href="'
			. AECToolbox::deadsureURL( '/index.php?option=' . $option . '&amp;task=repeatPayment&amp;invoice='
			. $invoice->invoice_number ) . '">'
			. aecHTML::Icon( 'arrow_redo.png' ) . "&nbsp;"
			. _USERINVOICE_ACTION_REPEAT . '</a>'
			. '<br />'
			. '<a href="'
			. AECToolbox::deadsureURL( '/administrator/index2.php?option=' . $option . '&amp;task=cancelpayment&amp;invoice='
			. $invoice->invoice_number . '&amp;returnTask=edit' ) . '">'
			. aecHTML::Icon( 'delete.png' ) . '&nbsp;'
			. _USERINVOICE_ACTION_CANCEL . '</a>'
			. '<br />'
			. '<a href="'
			. AECToolbox::deadsureURL( '/administrator/index2.php?option=' . $option . '&amp;task=clearpayment&amp;invoice='
			. $invoice->invoice_number . '&amp;returnTask=edit' ) . '">'
			. aecHTML::Icon( 'coins.png' ) . '&nbsp;'
			. _USERINVOICE_ACTION_CLEAR . '</a>'
			. '<br />'
			. '<a href="'
			. AECToolbox::deadsureURL( '/administrator/index2.php?option=' . $option . '&amp;task=clearpayment&amp;invoice='
			. $invoice->invoice_number . '&amp;applyplan=1&amp;returnTask=edit' ) . '">'
			. aecHTML::Icon( 'coins_add.png' ) . '&nbsp;'
			. _USERINVOICE_ACTION_CLEAR_APPLY . '</a>'
			. '<br />';
			$rowstyle = ' style="background-color:#fee;"';
		} else {
			$status		= $invoice->transaction_date;
			$actions	= '- - -';
			$rowstyle	= '';
		}

		$invoices[$inv_id] = array();
		$invoices[$inv_id]['rowstyle']			= $rowstyle;
		$invoices[$inv_id]['invoice_number']	= $invoice->invoice_number;
		$invoices[$inv_id]['amount']			= $invoice->amount . '&nbsp;' . $invoice->currency;
		$invoices[$inv_id]['status']			= $status;
		$invoices[$inv_id]['processor']			= $invoice->method;
		$invoices[$inv_id]['usage']				= $invoice->usage;
		$invoices[$inv_id]['actions']			= $actions;
	}

	// get available plans
	$available_plans	= array();
	$available_plans[]	= mosHTML::makeOption( '0', _PAYPLAN_NOPLAN );

	$query = 'SELECT id AS value, name AS text'
	. ' FROM #__acctexp_plans'
	. ' WHERE active = \'1\''
	;
	$database->setQuery( $query	);

	$dbaplans = $database->loadObjectList();

	if ( is_array( $dbaplans ) ) {
 		$available_plans	= array_merge( $available_plans, $database->loadObjectList() );
	}
	$total_plans		= count( $available_plans ) + 1;

	$selected_plan = isset( $row->fallback ) ? $row->fallback : '';

	$lists['assignto_plan'] = mosHTML::selectList( $available_plans, 'assignto_plan', 'size="5"', 'value', 'text', 0 );

	HTML_AcctExp::userForm( $option, $expiration, $subscription, $user, $invoices, $lists, $task );
}

function saveUser( $option )
{
	global $database, $mainframe, $mosConfig_list_limit;

	$metaUser = new metaUser( $_POST['userid'] );

	if ( $_POST['assignto_plan'] ) {
		if ( !$metaUser->hasSubscription ) {
			$metaUser->objSubscription = new Subscription( $database );
			$metaUser->objSubscription->createNew( $_POST['userid'], '', 1 );
		}
		$metaUser->objSubscription->applyUsage( $_POST['assignto_plan'], 'none', 1 );

		// We have to reload the metaUser object because it was changed underway
		$metaUser = new metaUser( $_POST['userid'] );
	}

	$ck_lifetime = mosGetParam( $_POST, 'ck_lifetime', 'off' );

	if ( !$metaUser->hasExpiration ) {
		$metaUser->objExpiration->load(0);
		$metaUser->objExpiration->userid = $_POST['userid'];
	}

	if ( strcmp( $ck_lifetime, 'on' ) == 0 ) {
		$metaUser->objExpiration->expiration	= '9999-12-31 00:00:00';
		$metaUser->objSubscription->status		= 'Active';
		$metaUser->objSubscription->lifetime	= 1;
	} elseif ( !empty( $_POST['expiration'] ) ) {
		if ( $_POST['expiration'] != $metaUser->objExpiration->expiration ) {
			if ( strpos( $_POST, ':' ) === false ) {
				$metaUser->objExpiration->expiration = $_POST['expiration'] . ' 00:00:00';
			} else {
				$metaUser->objExpiration->expiration = $_POST['expiration'];
			}
			$metaUser->objSubscription->status = 'Active';
			$metaUser->objSubscription->lifetime = 0;
		}
	}

	$set_status = trim( mosGetParam( $_REQUEST, 'set_status', null ) );

	if ( !is_null( $set_status ) ) {
		if ( strcmp( $set_status, 'now' ) === 0 ) {
			$metaUser->objSubscription->expire();
		} elseif ( strcmp( $set_status, 'exclude' ) === 0 ) {
			$metaUser->objSubscription->setStatus( 'Excluded' );
		} elseif ( strcmp( $set_status, 'close' ) === 0 ) {
			$metaUser->objSubscription->setStatus( 'Closed' );
		} elseif ( strcmp( $set_status, 'include' ) === 0 ) {
			$metaUser->objSubscription->setStatus( 'Active' );
		}
	}

	if ( !$metaUser->objExpiration->check() ) {
		echo "<script> alert('".$metaUser->objExpiration->getError()."'); window.history.go(-1); </script>\n";
		exit();
	}
	if ( !$metaUser->objExpiration->store() ) {
		echo "<script> alert('".$metaUser->objExpiration->getError()."'); window.history.go(-1); </script>\n";
		exit();
	}

	if ( !$metaUser->objSubscription->check() ) {
		echo "<script> alert('".$metaUser->objSubscription->getError()."'); window.history.go(-1); </script>\n";
		exit();
	}
	if ( !$metaUser->objSubscription->store() ) {
		echo "<script> alert('".$metaUser->objSubscription->getError()."'); window.history.go(-1); </script>\n";
		exit();
	}

 	$limit		= $mainframe->getUserStateFromRequest( "viewlistlimit", 'limit', $mosConfig_list_limit );
	$limitstart	= $mainframe->getUserStateFromRequest( "viewnotconf{$option}limitstart", 'limitstart', 0 );

	$nexttask	= mosGetParam( $_REQUEST, 'nexttask', 'config' ) ;

	mosRedirect( 'index2.php?option=' . $option . '&task=' . $nexttask, _SAVED );
}

function removeUser( $userid, $option )
{
	global $database, $my, $acl;

	// $userid contains values corresponding to id field of #__acctexp table
    if ( !is_array( $userid ) || count( $userid ) < 1 ) {
	    echo "<script> alert('" . _AEC_ALERT_SELECT_FIRST . "'); window.history.go(-1);</script>\n";
	    exit;
    }

	$userids	= implode( ',', $userid );
	$msg		= _REMOVED;

	if ( count( $userid ) ) {
		$obj = new mosUser( $database );
		foreach ( $userid as $id ) {
			// Get REAL UserID
			$query = 'SELECT userid'
			. ' FROM #__acctexp'
			. ' WHERE id = ' . $id
			;
			$uid = null;
			$database->setQuery( $query );
			$uid = $database->loadResult();

			if ( $uid ) {
				// check for a super admin ... can't delete them
				$groups		= $acl->get_object_groups( 'users', $uid, 'ARO' );
				$this_group = strtolower( $acl->get_group_name( $groups[0], 'ARO' ) );

				if ( $this_group == 'super administrator' ) {
					$msg = _AEC_MSG_NODELETE_SUPERADMIN;
				} elseif ( $uid == $my->id ){
					$msg = _AEC_MSG_NODELETE_YOURSELF;
				} elseif ( ( $this_group == 'administrator' ) && ( $my->gid == 24 ) ){
					$msg = _AEC_MSG_NODELETE_EXCEPT_SUPERADMIN;
				} else {
					$query = 'DELETE FROM #__acctexp'
					. ' WHERE userid = ' . $uid
					;
				 	$database->setQuery( $query );
					if (!$database->query()) {
						echo "<script> alert('".$database->getErrorMsg()."'); window.history.go(-1); </script>\n";
					}
					if ( !$obj->delete( $uid ) ) {
						$msg = $obj->getError();
					}
				}
			}
		}
	}

	mosRedirect( 'index2.php?option=' . $option . '&task=showManual', $msg );

}

function removeClosedSubscription( $userid, $option )
{
	global $database, $my, $acl, $mosConfig_dbprefix;

	// $userid contains values corresponding to id field of #__acctexp table
    if ( !is_array( $userid ) || count( $userid ) < 1 ) {
	    echo "<script> alert('" . _AEC_ALERT_SELECT_FIRST . "'); window.history.go(-1);</script>\n";
	    exit;
    }

	$userids = implode(',', $userid);
	$query  = 'DELETE FROM #__acctexp'
	. ' WHERE userid IN (' . $userids . ')'
	;
 	$database->setQuery( $query );
	if ( !$database->query() ) {
		echo "<script> alert('".$database->getErrorMsg()."'); window.history.go(-1); </script>\n";
	}

	// Delete from the payment history
	$query = 'DELETE FROM #__acctexp_log_history'
	. ' WHERE user_id IN (' . $userids . ')'
	;
 	$database->setQuery( $query );
	if ( !$database->query() ) {
		echo "<script> alert('".$database->getErrorMsg()."'); window.history.go(-1); </script>\n";
	}

	// CB&CBE Integration
	$tables	= array();
	$tables	= $database->getTableList();

	if ( GeneralInfoRequester::detect_component('CB') && GeneralInfoRequester::detect_component('CBE') ) {
		$query = 'DELETE FROM #__comprofiler'
		. ' WHERE id IN (' . $userids . ')'
		;
		$database->setQuery($query);
		$database->query();
	}

	$msg = _REMOVED;
	if ( count( $userid ) ) {
		$obj = new mosUser( $database );
		foreach ( $userid as $id ) {
			// check for a super admin ... can't delete them
			$groups		= $acl->get_object_groups( 'users', $id, 'ARO' );
			$this_group	= strtolower( $acl->get_group_name( $groups[0], 'ARO' ) );

			if ( $this_group == 'super administrator' ) {
				$msg = _AEC_MSG_NODELETE_SUPERADMIN;
			}else if ( $id == $my->id ){
				$msg = _AEC_MSG_NODELETE_YOURSELF;
			}else if ( ( $this_group == 'administrator' ) && ( $my->gid == 24 ) ){
				$msg = _AEC_MSG_NODELETE_EXCEPT_SUPERADMIN;
			} else {
				if ( !$obj->delete( $id ) ) {
					$msg = $obj->getError();
				}
			}
		}
	}

	$query = 'DELETE FROM #__acctexp_subscr'
	. ' WHERE userid IN (' . $userids . ')'
	;
 	$database->setQuery( $query );
	if ( !$database->query() ) {
		echo "<script> alert('".$database->getErrorMsg()."'); window.history.go(-1); </script>\n";
	}

	mosRedirect( 'index2.php?option=' . $option . '&task=showClosed', $msg );

}

function activateClosedSubscription( $userid, $option )
{
	global $database, $my, $acl, $mosConfig_dbprefix;
}

function removePendingSubscription( $userid, $option )
{
	global $database, $my, $acl, $mosConfig_dbprefix;

	// $userid contains values corresponding to id field of #__acctexp table
    if ( !is_array( $userid ) || count( $userid ) < 1 ) {
	    echo "<script> alert('" . _AEC_ALERT_SELECT_FIRST . "'); window.history.go(-1);</script>\n";
	    exit;
    }

	$userids = implode(',', $userid);

	$query = 'DELETE FROM #__acctexp'
	. ' WHERE userid IN (' . $userids . ')'
	;
 	$database->setQuery( $query );
	if ( !$database->query() ) {
		echo "<script> alert('".$database->getErrorMsg()."'); window.history.go(-1); </script>\n";
	}

	$query = 'DELETE FROM #__acctexp_log_history'
	. ' WHERE user_id IN (' . $userids . ')'
	;
 	$database->setQuery( $query );
	if ( !$database->query() ) {
		echo "<script> alert('".$database->getErrorMsg()."'); window.history.go(-1); </script>\n";
	}

	// CB&CBE Integration
	$tables	= array();
	$tables	= $database->getTableList();

	if ( GeneralInfoRequester::detect_component( 'CB' ) && GeneralInfoRequester::detect_component( 'CBE' ) ) {
		$query = 'DELETE FROM #__comprofiler'
		. ' WHERE id IN (' . $userids . ')'
		;
		$database->setQuery( $query );
		$database->query();
	}

	$msg = _REMOVED;
	if ( count( $userid ) ) {
		$obj = new mosUser( $database );
		foreach ($userid as $id) {
			// check for a super admin ... can't delete them
			$groups         = $acl->get_object_groups( 'users', $id, 'ARO' );
			$this_group = strtolower( $acl->get_group_name( $groups[0], 'ARO' ) );
			if ( $this_group == 'super administrator' ) {
				$msg = _AEC_MSG_NODELETE_SUPERADMIN;
			}else if ( $id == $my->id ){
				$msg = _AEC_MSG_NODELETE_YOURSELF;
			}else if ( ( $this_group == 'administrator' ) && ( $my->gid == 24 ) ){
				$msg = _AEC_MSG_NODELETE_EXCEPT_SUPERADMIN;
			} else {
				if ( !$obj->delete( $id ) ) {
					$msg = $obj->getError();
				}
			}
		}
	}

	$query = 'DELETE FROM #__acctexp_subscr'
	. ' WHERE userid IN (' . $userids . ')'
	;
 	$database->setQuery( $query );
	if ( !$database->query() ) {
		echo "<script> alert('".$database->getErrorMsg()."'); window.history.go(-1); </script>\n";
	}

	mosRedirect( 'index2.php?option=' . $option . '&task=showPending', $msg );

}

function activatePendingSubscription( $userid, $option, $renew )
{
	global $database;

    if (!is_array( $userid ) || count( $userid ) < 1) {
	    echo "<script> alert('" . _AEC_ALERT_SELECT_FIRST . "'); window.history.go(-1);</script>\n";
	    exit;
    }

	$n = 0;

	foreach ( $userid as $id ) {
		$n++;

		$user_subscription = new Subscription( $database );

		if ( $userid ) {
			$user_subscription->loadUserID( $id );
		} else {
			return;
		}

		$invoiceid = AECfetchfromDB::lastUnclearedInvoiceIDbyUserID( $id );

		if ( $invoiceid ) {
			$invoice = new Invoice( $database );
			$invoice->load( $invoiceid );
			$plan = $invoice->usage;
			$invoice->setTransactionDate();
		} else {
			$plan = $user_subscription->plan;
		}

		$renew = $user_subscription->applyUsage( $plan, 'none', 1 );
	}
	if ( $renew ) {
		// Admin confirmed an offline payment for a renew
		// He is working on the Active queue
		$msg = $n . ' ' . _AEC_MSG_SUBS_RENEWED;
		mosRedirect( 'index2.php?option=' . $option . '&task=showActive', $msg );
	} else {
		// Admin confirmed an offline payment for a new subscription
		// He is working on the Pending queue
		$msg = $n . ' ' . _AEC_MSG_SUBS_ACTIVATED;
		mosRedirect( 'index2.php?option=' . $option . '&task=showPending', $msg );
	}
}

function listSubscriptions( $option, $set_group, $userid )
{
	global $database, $mainframe, $mosConfig_list_limit;

	$limit			= $mainframe->getUserStateFromRequest( "viewlistlimit", 'limit', $mosConfig_list_limit );
	$limitstart		= $mainframe->getUserStateFromRequest( "viewconf{$option}limitstart", 'limitstart', 0 );

	$orderby		= $mainframe->getUserStateFromRequest( "order", 'orderby', 'name ASC' );
	$search			= $mainframe->getUserStateFromRequest( "search{$option}", 'search', '' );
	$search			= $database->getEscaped( trim( strtolower( $search ) ) );

	$filter_planid	= intval( $mainframe->getUserStateFromRequest( "filter_planid{$option}", 'filter_planid', 0 ) );

	if ( !empty( $_REQUEST['groups'] ) ) {
		if ( is_array($_REQUEST['groups'] ) ) {
			$groups 	= $_REQUEST['groups'];
			$set_group	= $_REQUEST['groups'][0];
		}
	} else {
		$groups		= array();
		$groups[]	= $set_group;
	}

	// define displaying at html
	$action = array();
	switch( $set_group ){
		case 'active':
			$action[0]	= 'active';
			$action[1]	= _AEC_HEAD_ACTIVE_SUBS;
			break;

		case 'excluded':
			$action[0]	= 'excluded';
			$action[1]	= _AEC_HEAD_EXCLUDED_SUBS;
			break;

		case 'expired':
			$action[0]	= 'expired';
			$action[1]	= _AEC_HEAD_EXPIRED_SUBS;
			break;

		case 'pending':
			$action[0]	= 'pending';
			$action[1]	= _AEC_HEAD_PENDING_SUBS;
			break;

		case 'cancelled':
			$action[0]	= 'cancelled';
			$action[1]	= _AEC_HEAD_CANCELLED_SUBS;
			break;

		case 'closed':
			$action[0]	= 'closed';
			$action[1]	= _AEC_HEAD_CLOSED_SUBS;
		break;

		case 'notconfig':
			$action[0]	= 'manual';
			$action[1]	= _AEC_HEAD_MANUAL_SUBS;
			break;
	}

	$filter		= '';
	$where		= array();
	$where_or	= array();
	$notconfig	= 0;

	$planid = trim( mosGetParam( $_REQUEST, 'assign_planid', null ) );
	if ( $planid > 0 && is_array( $userid ) && count( $userid ) > 0 ) {
		foreach ($userid as $k) {
			$subscriptionid = AECfetchfromDB::SubscriptionIDfromUserID( $k );

			$subscriptionHandler = new Subscription( $database );

			if ( $subscriptionid ) {
				$subscriptionHandler->load( $subscriptionid );
			} else {
				$subscriptionHandler->load( 0 );
				$subscriptionHandler->createNew( $k, '', 1 );
			}

			$subscriptionHandler->applyUsage( $planid, 'none', 1 );

			// Also show active users now
			if ( !in_array( 'active', $groups ) ) {
				$groups[] = 'active';
			}
		}
	}

	$expire = trim( mosGetParam( $_REQUEST, 'set_expiration', null ) );

	if ( !is_null($expire) && is_array( $userid ) && count( $userid ) > 0 ) {
		foreach ( $userid as $k ) {
			$subscriptionid = AECfetchfromDB::SubscriptionIDfromUserID( $k );
			$expirationid = AECfetchfromDB::ExpirationIDfromUserID( $k );

			$subscriptionHandler = new Subscription( $database );

			if ( $subscriptionid ) {
				$subscriptionHandler->load( 0 );
				$subscriptionHandler->load( $subscriptionid );
			} else {
				$subscriptionHandler->createNew( $k, '', 1 );
			}

			$expirationHandler = new AcctExp( $database );

			if ( $expirationid ) {
				$expirationHandler->load( $expirationid );
			} else {
				$expirationHandler->load( 0 );
				$expirationHandler->userid = $k;
			}

			if ( strcmp( $expire, 'now' ) === 0) {
				$subscriptionHandler->expire();

				if ( !in_array( 'expired', $groups ) ) {
					$groups[] = 'expired';
				}
			} elseif ( strcmp( $expire, 'exclude' ) === 0 ) {
				$subscriptionHandler->setStatus( 'Excluded' );

				if ( !in_array( 'excluded', $groups ) ) {
					$groups[] = 'excluded';
				}
			} elseif ( strcmp( $expire, 'close' ) === 0 ) {
				$subscriptionHandler->setStatus( 'Closed' );

				if ( !in_array( 'closed', $groups ) ) {
					$groups[] = 'closed';
				}
			} elseif ( strcmp( $expire, 'include' ) === 0 ) {
				$subscriptionHandler->setStatus( 'Active' );

				if ( !in_array( 'active', $groups ) ) {
					$groups[] = 'active';
				}
			} elseif ( strpos( $expire, 'set' ) === 0 ) {
				$expirationHandler->setExpiration( 'M', substr( $expire, 4 ), 0 );
				$expirationHandler->check();
				$expirationHandler->store();
				$subscriptionHandler->lifetime = 0;
				$subscriptionHandler->setStatus( 'Active' );

				if ( !in_array( 'active', $groups ) ) {
					$groups[] = 'active';
				}
			} elseif ( strpos( $expire, 'add' ) === 0 ) {
				if ( $subscriptionHandler->lifetime) {
					$expirationHandler->setExpiration( 'M', substr( $expire, 4 ), 0 );
				} else {
					$expirationHandler->setExpiration( 'M', substr( $expire, 4 ), 1 );
				}
				$expirationHandler->check();
				$expirationHandler->store();
				$subscriptionHandler->lifetime = 0;
				$subscriptionHandler->setStatus( 'Active' );

				if ( !in_array( 'active', $groups ) ) {
					$groups[] = 'active';
				}
			}
		}
	}

	if ( is_array(  $groups ) ) {
		if ( in_array( 'notconfig', $groups ) ) {
 			$notconfig = 1;
 			$groups = array( 'notconfig' );
		}
		if ( in_array( 'excluded', $groups ) ) {
			$where_or[] = "a.status = 'Excluded'";
		}
		if ( in_array( 'expired', $groups ) ) {
			$where_or[] = "a.status = 'Expired'";
		}
		if ( in_array( 'active', $groups ) ) {
			$where_or[] = "(a.status = 'Active' || a.status = 'Trial')";
		}
		if ( in_array( 'pending', $groups ) ) {
			$where_or[] = "a.status = 'Pending'";
		}
		if ( in_array( 'cancelled', $groups ) ) {
			$where_or[] = "a.status = 'Cancelled'";
		}
		if ( in_array( 'closed', $groups ) ) {
 			$where_or[] = "a.status = 'Closed'";
		}
	}

	if ( isset( $search ) && $search!= '' ) {
		$where[] = "(b.username LIKE '%$search%' OR b.name LIKE '%$search%')";
	}

	if ( isset($filter_planid) && $filter_planid > 0 ) {
		$where[] = "(a.plan='$filter_planid')";
	}

	// get the total number of records
	if ( $notconfig ) {
		$query	= 'SELECT userid'
		. ' FROM #__acctexp_subscr'
		. ' WHERE userid != \'\''
		;
		$database->setQuery( $query );
		$userarray = $database->loadResultArray();

		$query = 'SELECT count(*)'
		. ' FROM #__users'
		;
		if ( count( $userarray ) > 0 ) {
			$query .= ' WHERE id NOT IN (' . implode(',', $userarray) . ')';
		}
	} else {
		$query = 'SELECT count(*)'
		. ' FROM #__acctexp_subscr AS a'
		. ' INNER JOIN #__users AS b ON a.userid = b.id'
		;

		if ( count( $where_or ) ) {
			$where[] = ( count( $where_or ) ? '(' . implode( ' OR ', $where_or ) . ')' : '' );
		}

		$query .= (count( $where ) ? ' WHERE ' . implode( ' AND ', $where ) : '' );
	}

	$database->setQuery( $query );
	$total = $database->loadResult();
	echo $database->getErrorMsg();

	require_once( $mainframe->getCfg( 'absolute_path' ) . '/administrator/includes/pageNavigation.php' );
	$pageNav = new mosPageNav( $total, $limitstart, $limit );

	// get the subset (based on limits) of required records
	if ( $notconfig ) {
		$query = 'SELECT userid'
		. ' FROM #__acctexp_subscr'
		. ' WHERE userid != \'\''
		;
		$database->setQuery( $query );
		$userarray = $database->loadResultArray();

	    foreach ( $userarray as $i => $v ) {
	        if ( empty( $v ) ){
	        	unset( $userarray[$i] );
	        }
	    }

		$query = 'SELECT id AS userid, name, username, usertype'
		. ' FROM #__users'
		;
		if ( count( $userarray ) > 0) {
			$query .= ' WHERE id NOT IN (' . implode( ',', $userarray ) . ')';
		}
			$query .=	' LIMIT ' . $pageNav->limitstart . ',' . $pageNav->limit;
	} else {
		$query = 'SELECT a.*, b.name, b.username, b.email, c.name AS plan_name, d.expiration AS expiration'
		. ' FROM #__acctexp_subscr AS a'
		. '  INNER JOIN #__users AS b ON a.userid = b.id'
		. ' LEFT JOIN #__acctexp_plans AS c ON a.plan = c.id'
		. ' LEFT JOIN #__acctexp AS d ON a.userid = d.userid'
		. ( count( $where ) ? ' WHERE ' . implode( ' AND ', $where ) : '' )
		. ' ORDER BY ' . $orderby
		. ' LIMIT ' . $pageNav->limitstart . ',' . $pageNav->limit
		;
	}

	$database->setQuery( 'SET SQL_BIG_SELECTS=1');
	$database->query();

	$database->setQuery( $query );
	$rows = $database->loadObjectList();

	if ( $database->getErrorNum() ) {
		echo $database->stderr();
		return false;
	}

	$database->setQuery( 'SET SQL_BIG_SELECTS=0');
	$database->query();

	$sel = array();
	$sel[] = mosHTML::makeOption( 'expiration ASC',		_EXP_ASC );
	$sel[] = mosHTML::makeOption( 'expiration DESC',	_EXP_DESC );
	$sel[] = mosHTML::makeOption( 'name ASC',			_NAME_ASC );
	$sel[] = mosHTML::makeOption( 'name DESC',			_NAME_DESC );
	$sel[] = mosHTML::makeOption( 'username ASC',		_LOGIN_ASC );
	$sel[] = mosHTML::makeOption( 'username DESC',		_LOGIN_DESC );
	$sel[] = mosHTML::makeOption( 'signup_date ASC',	_SIGNUP_ASC );
	$sel[] = mosHTML::makeOption( 'signup_date DESC',	_SIGNUP_DESC );
	$sel[] = mosHTML::makeOption( 'lastpay_date ASC',	_LASTPAY_ASC );
	$sel[] = mosHTML::makeOption( 'lastpay_date DESC',	_LASTPAY_DESC );
	$sel[] = mosHTML::makeOption( 'plan_name ASC',		_PLAN_ASC );
	$sel[] = mosHTML::makeOption( 'plan_name DESC',		_PLAN_DESC );
	$sel[] = mosHTML::makeOption( 'status ASC',			_STATUS_ASC );
	$sel[] = mosHTML::makeOption( 'status DESC',		_STATUS_DESC );
	$sel[] = mosHTML::makeOption( 'type ASC',			_TYPE_ASC );
	$sel[] = mosHTML::makeOption( 'type DESC',			_TYPE_DESC );

	$lists['orderNav'] = mosHTML::selectList( $sel, 'orderby', 'class="inputbox" size="1" onchange="document.adminForm.submit();"', 'value', 'text', $orderby );

	// Get list of plans for filter
	$query = 'SELECT id, name'
	. ' FROM #__acctexp_plans'
	. ' ORDER BY ordering'
	;
	$database->setQuery( $query );
	$db_plans = $database->loadObjectList();

	$plans[] = mosHTML::makeOption( '0', _FILTER_PLAN, 'id', 'name' );
	if ( is_array( $db_plans ) ) {
		$plans = array_merge( $plans, $db_plans );
	}
	$lists['filterplanid']	= mosHTML::selectList( $plans, 'filter_planid', 'class="inputbox" size="1" onchange="document.adminForm.submit( );"', 'id', 'name', $filter_planid );

	$plans2[] = mosHTML::makeOption( '0', _BIND_USER, 'id', 'name' );
	if ( is_array( $db_plans ) ) {
		$plans2 = array_merge( $plans2, $db_plans );
	}
	$lists['planid']	= mosHTML::selectList( $plans2, 'assign_planid', 'class="inputbox" size="1" onchange="document.adminForm.submit( );"', 'id', 'name', 0 );

	$group_selection = array();
	$group_selection[] = mosHTML::makeOption( 'excluded',	_AEC_SEL_EXCLUDED );
	$group_selection[] = mosHTML::makeOption( 'pending',	_AEC_SEL_PENDING );
	$group_selection[] = mosHTML::makeOption( 'active',		_AEC_SEL_ACTIVE );
	$group_selection[] = mosHTML::makeOption( 'expired',	_AEC_SEL_EXPIRED );
	$group_selection[] = mosHTML::makeOption( 'closed',		_AEC_SEL_CLOSED );
	$group_selection[] = mosHTML::makeOption( 'cancelled',	_AEC_SEL_CANCELLED );
	$group_selection[] = mosHTML::makeOption( 'notconfig',	_AEC_SEL_NOT_CONFIGURED );

	$selected_groups = array();
	if ( is_array( $groups ) ) {
		foreach ($groups as $name ) {
			$selected_groups[] = mosHTML::makeOption( $name, $name );
		}
	}

	$lists['groups'] = mosHTML::selectList($group_selection, 'groups[]', 'size="5" multiple="multiple"', 'value', 'text', $selected_groups);

	$group_selection = array();
	$group_selection[] = mosHTML::makeOption( '',			_EXPIRE_SET );
	$group_selection[] = mosHTML::makeOption( 'now',		_EXPIRE_NOW );
	$group_selection[] = mosHTML::makeOption( 'exclude',	_EXPIRE_EXCLUDE );
	$group_selection[] = mosHTML::makeOption( 'include',	_EXPIRE_INCLUDE );
	$group_selection[] = mosHTML::makeOption( 'close',		_EXPIRE_CLOSE );
	$group_selection[] = mosHTML::makeOption( 'add_1',		_EXPIRE_ADD01MONTH );
	$group_selection[] = mosHTML::makeOption( 'add_3',		_EXPIRE_ADD03MONTH );
	$group_selection[] = mosHTML::makeOption( 'add_12',		_EXPIRE_ADD12MONTH );
	$group_selection[] = mosHTML::makeOption( 'set_1',		_EXPIRE_01MONTH );
	$group_selection[] = mosHTML::makeOption( 'set_3',		_EXPIRE_03MONTH );
	$group_selection[] = mosHTML::makeOption( 'set_12',		_EXPIRE_12MONTH );

	$lists['set_expiration'] = mosHTML::selectList($group_selection, 'set_expiration', 'class="inputbox" size="1" onchange="document.adminForm.submit( );"', 'value', 'text', "");

	HTML_AcctExp::listSubscriptions( $rows, $pageNav, $search, $option, $lists, $userid, $action );
}

function editSettings( $option )
{
	global $database, $acl, $my, $aecConfig;

	// See whether we have a duplication
	if ( $aecConfig->RowDuplicationCheck() ) {
		// Clean out duplication and reload settings
		$aecConfig->CleanDuplicatedRows();
		$aecConfig = new Config_General( $database );
	}

	$lists = array();

	$lists['simpleurls']			= mosHTML::yesnoSelectList('simpleurls', '', $aecConfig->cfg['simpleurls']);
	$lists['require_subscription']	= mosHTML::yesnoSelectList('require_subscription', '', $aecConfig->cfg['require_subscription']);
	$lists['plans_first']			= mosHTML::yesnoSelectList('plans_first', '', $aecConfig->cfg['plans_first']);
	$lists['enable_coupons']		= mosHTML::yesnoSelectList('enable_coupons', '', $aecConfig->cfg['enable_coupons']);
	$lists['enable_mimeta']			= mosHTML::yesnoSelectList('enable_mimeta', '', ( !empty( $aecConfig->cfg['enable_mimeta'] ) ? $aecConfig->cfg['enable_mimeta'] : '' ) );
	$lists['displayccinfo']			= mosHTML::yesnoSelectList('displayccinfo', '', $aecConfig->cfg['displayccinfo']);
	$lists['adminaccess']			= mosHTML::yesnoSelectList('adminaccess', '', $aecConfig->cfg['adminaccess']);
	$lists['noemails']				= mosHTML::yesnoSelectList('noemails', '', $aecConfig->cfg['noemails']);
	$lists['nojoomlaregemails']		= mosHTML::yesnoSelectList('nojoomlaregemails', '', $aecConfig->cfg['nojoomlaregemails']);
	$lists['debugmode']				= mosHTML::yesnoSelectList('debugmode', '', $aecConfig->cfg['debugmode']);
	$lists['override_reqssl']		= mosHTML::yesnoSelectList('override_reqssl', '', $aecConfig->cfg['override_reqssl']);

	$lists['customtext_confirm_keeporiginal']		= mosHTML::yesnoSelectList('customtext_confirm_keeporiginal', '', $aecConfig->cfg['customtext_confirm_keeporiginal']);
	$lists['customtext_checkout_keeporiginal']		= mosHTML::yesnoSelectList('customtext_checkout_keeporiginal', '', $aecConfig->cfg['customtext_checkout_keeporiginal']);
	$lists['customtext_notallowed_keeporiginal']	= mosHTML::yesnoSelectList('customtext_notallowed_keeporiginal', '', $aecConfig->cfg['customtext_notallowed_keeporiginal']);
	$lists['customtext_expired_keeporiginal']		= mosHTML::yesnoSelectList('customtext_expired_keeporiginal', '', $aecConfig->cfg['customtext_expired_keeporiginal']);
	$lists['customtext_pending_keeporiginal']		= mosHTML::yesnoSelectList('customtext_pending_keeporiginal', '', $aecConfig->cfg['customtext_pending_keeporiginal']);


	$currency_code_list	= AECToolbox::_aecCurrencyField( true, true, true );
	$lists['currency_code_general'] = mosHTML::selectList( $currency_code_list, ( 'currency_code_general' ), 'size="10"', 'value', 'text', ( !empty( $aecConfig->cfg['currency_code_general'] ) ? $aecConfig->cfg['currency_code_general'] : '' ) );

	// get entry Plan selection
	$available_plans	= array();
	$available_plans[]	= mosHTML::makeOption( '0', _PAYPLAN_NOPLAN );

	$query = 'SELECT id AS value, name AS text'
	. ' FROM #__acctexp_plans'
	. ' WHERE active = \'1\''
	;
	$database->setQuery( $query );
	$dbaplans = $database->loadObjectList();

 	if ( is_array( $dbaplans ) ) {
 		$available_plans	= array_merge( $available_plans, $dbaplans );
 	}
	$total_plans		= count( $available_plans ) + 1;

	$selected_plan = isset($aecConfig->cfg['entry_plan']) ? $aecConfig->cfg['entry_plan'] : '0';

	$lists['entry_plan'] = mosHTML::selectList($available_plans, 'entry_plan', 'size="' . $total_plans . '"', 'value', 'text', $selected_plan);

	// Create MI Selection List
	$mi_handler = new microIntegrationHandler();
	$mi_list = $mi_handler->getIntegrationList();

	$mi_htmllist = array();
	$mi_htmllist[]	= mosHTML::makeOption( '', _AEC_CMN_NONE_SELECTED );

	foreach ( $mi_list as $name ) {
		$mi = new microIntegration( $database );
		$mi->class_name = $name;
		if ( $mi->callIntegration() ){
			$len = 30 - AECToolbox::visualstrlen( trim( $mi->name ) );
			$fullname = str_replace( '#', '&nbsp;', str_pad( $mi->name, $len, '#' ) ) . ' - ' . substr($mi->desc, 0, 120);
			$mi_htmllist[] = mosHTML::makeOption( $name, $fullname );
		}
	}

	if ( !empty( $aecConfig->cfg['milist'] ) ) {
		$milist = explode( ';', $aecConfig->cfg['milist'] );
		$selected_mis = array();
		foreach ( $milist as $mi_name ) {
			$selected_mis[]->value = $mi_name;
		}
	} else {
		$aecConfig->cfg['milist'] = null;
		$selected_mis		= '';
	}

	$lists['milist'] = mosHTML::selectList( $mi_htmllist, 'milist[]', 'size="' . min( ( count( $mi_list ) + 1 ), 25 ) . '" multiple', 'value', 'text', $selected_mis );

	// Each Tab needs a name and a short description
	// Start then by specifying how many values you want to show up and choose their type
	// subtitle	= Subtitle line
	// inputA-D	= Inputs with varying sizes
	// list		= List choice
	// fieldset	= Descriptive field

	$tab_data = array();
	$tab_data[0] = array();
	$tab_data[0][] = _CFG_TAB1_TITLE;
	$tab_data[0][] = array( 'subtitle', '0', _CFG_TAB1_SUBTITLE, '0', '0');
	$tab_data[0][] = array( 'list', _CFG_TAB1_OPT9NAME, _CFG_TAB1_OPT9DESC, '0', 'require_subscription');
	$tab_data[0][] = array( 'inputA', _CFG_TAB1_OPT3NAME, _CFG_TAB1_OPT3DESC, $aecConfig->cfg['alertlevel2'], 'alertlevel2');
	$tab_data[0][] = array( 'inputA', _CFG_TAB1_OPT4NAME, _CFG_TAB1_OPT4DESC, $aecConfig->cfg['alertlevel1'], 'alertlevel1');
	$tab_data[0][] = array( 'list', _CFG_TAB1_OPT20NAME, _CFG_TAB1_OPT20DESC, '0', 'gwlist_enabled');
	$tab_data[0][] = array( 'list', _CFG_TAB1_OPT10NAME, _CFG_TAB1_OPT10DESC, '0', 'gwlist');
	$tab_data[0][] = array( 'list', _CFG_TAB1_OPT5NAME, _CFG_TAB1_OPT5DESC, '0', 'entry_plan');
	$tab_data[0][] = array( 'list', _CFG_GENERAL_DISPLAYCCINFO_NAME, _CFG_GENERAL_DISPLAYCCINFO_DESC, '0', 'displayccinfo');
	$tab_data[0][] = array( 'inputC', _CFG_TAB1_OPT15NAME, _CFG_TAB1_OPT15DESC, $aecConfig->cfg['bypassintegration'], 'bypassintegration');
	$tab_data[0][] = array( 'list', _CFG_TAB1_OPT21NAME, _CFG_TAB1_OPT21DESC, '0', 'plans_first');
	$tab_data[0][] = array( 'list', _CFG_TAB1_OPT16NAME, _CFG_TAB1_OPT16DESC, '0', 'simpleurls');
	$tab_data[0][] = array( 'inputA', _CFG_TAB1_OPT17NAME, _CFG_TAB1_OPT17DESC, $aecConfig->cfg['expiration_cushion'], 'expiration_cushion');
	$tab_data[0][] = array( 'inputA', _CFG_TAB1_OPT18NAME, _CFG_TAB1_OPT18DESC, $aecConfig->cfg['heartbeat_cycle'], 'heartbeat_cycle');
	$tab_data[0][] = array( 'inputA', _CFG_GENERAL_HEARTBEAT_CYCLE_BACKEND_NAME, _CFG_GENERAL_HEARTBEAT_CYCLE_BACKEND_DESC, $aecConfig->cfg['heartbeat_cycle_backend'], 'heartbeat_cycle_backend');
	$tab_data[0][] = array( 'list', _CFG_GENERAL_ENABLE_COUPONS_NAME, _CFG_GENERAL_ENABLE_COUPONS_DESC, '0', 'enable_coupons');
	$tab_data[0][] = array( 'list', _CFG_GENERAL_ADMINACCESS_NAME, _CFG_GENERAL_ADMINACCESS_DESC, '0', 'adminaccess');
	$tab_data[0][] = array( 'list', _CFG_GENERAL_NOEMAILS_NAME, _CFG_GENERAL_NOEMAILS_DESC, '0', 'noemails');
	$tab_data[0][] = array( 'list', _CFG_GENERAL_NOJOOMLAREGEMAILS_NAME, _CFG_GENERAL_NOJOOMLAREGEMAILS_DESC, '0', 'nojoomlaregemails');
	$tab_data[0][] = array( 'list', _CFG_GENERAL_OVERRIDE_REQSSL_NAME, _CFG_GENERAL_OVERRIDE_REQSSL_DESC, '0', 'override_reqssl');
	$tab_data[0][] = array( 'list', _CFG_GENERAL_DEBUGMODE_NAME, _CFG_GENERAL_DEBUGMODE_DESC, '0', 'debugmode');

	$tab_data[1] = array();
	$tab_data[1][] = _CFG_TAB_CUSTOMIZATION_TITLE;
	$tab_data[1][] = array( 'inputC', _CFG_TAB1_OPT12NAME, _CFG_TAB1_OPT12DESC, $aecConfig->cfg['customintro'], 'customintro');
	$tab_data[1][] = array( 'inputC', _CFG_TAB1_OPT13NAME, _CFG_TAB1_OPT13DESC, $aecConfig->cfg['customthanks'], 'customthanks');
	$tab_data[1][] = array( 'inputC', _CFG_TAB1_OPT14NAME, _CFG_TAB1_OPT14DESC, $aecConfig->cfg['customcancel'], 'customcancel');
	$tab_data[1][] = array( 'inputC', _CFG_GENERAL_CUSTOMNOTALLOWED_NAME, _CFG_GENERAL_CUSTOMNOTALLOWED_DESC, $aecConfig->cfg['customnotallowed'], 'customnotallowed');

	$tab_data[1][] = array( 'inputC', _CFG_TAB1_OPT19NAME, _CFG_TAB1_OPT19DESC, $aecConfig->cfg['tos'], 'tos');
	$tab_data[1][] = array( 'inputC', _CFG_GENERAL_DISPLAY_DATE_FRONTEND_NAME, _CFG_GENERAL_DISPLAY_DATE_FRONTEND_DESC, $aecConfig->cfg['display_date_frontend'], 'display_date_frontend');
	$tab_data[1][] = array( 'inputC', _CFG_GENERAL_DISPLAY_DATE_BACKEND_NAME, _CFG_GENERAL_DISPLAY_DATE_BACKEND_DESC, $aecConfig->cfg['display_date_backend'], 'display_date_backend');

	$tab_data[1][] = array( 'editor', _CFG_GENERAL_CUSTOMTEXT_PLANS_NAME, _CFG_GENERAL_CUSTOMTEXT_PLANS_DESC, $aecConfig->cfg['customtext_plans'], 'customtext_plans');
	$tab_data[1][] = array( 'list', _CFG_GENERAL_CUSTOMTEXT_CONFIRM_KEEPORIGINAL_NAME, _CFG_GENERAL_CUSTOMTEXT_CONFIRM_KEEPORIGINAL_DESC, '0', 'customtext_confirm_keeporiginal');
	$tab_data[1][] = array( 'editor', _CFG_GENERAL_CUSTOMTEXT_CONFIRM_NAME, _CFG_GENERAL_CUSTOMTEXT_CONFIRM_DESC, $aecConfig->cfg['customtext_confirm'], 'customtext_confirm');
	$tab_data[1][] = array( 'list', _CFG_GENERAL_CUSTOMTEXT_CHECKOUT_KEEPORIGINAL_NAME, _CFG_GENERAL_CUSTOMTEXT_CHECKOUT_KEEPORIGINAL_DESC, '0', 'customtext_checkout_keeporiginal');
	$tab_data[1][] = array( 'editor', _CFG_GENERAL_CUSTOMTEXT_CHECKOUT_NAME, _CFG_GENERAL_CUSTOMTEXT_CHECKOUT_DESC, $aecConfig->cfg['customtext_checkout'], 'customtext_checkout');

	$tab_data[1][] = array( 'list', _CFG_GENERAL_CUSTOMTEXT_NOTALLOWED_KEEPORIGINAL_NAME, _CFG_GENERAL_CUSTOMTEXT_NOTALLOWED_KEEPORIGINAL_DESC, '0', 'customtext_notallowed_keeporiginal');
	$tab_data[1][] = array( 'editor', _CFG_GENERAL_CUSTOMTEXT_NOTALLOWED_NAME, _CFG_GENERAL_CUSTOMTEXT_NOTALLOWED_DESC, $aecConfig->cfg['customtext_notallowed'], 'customtext_notallowed');
	$tab_data[1][] = array( 'list', _CFG_GENERAL_CUSTOMTEXT_PENDING_KEEPORIGINAL_NAME, _CFG_GENERAL_CUSTOMTEXT_PENDING_KEEPORIGINAL_DESC, '0', 'customtext_pending_keeporiginal');
	$tab_data[1][] = array( 'editor', _CFG_GENERAL_CUSTOMTEXT_PENDING_NAME, _CFG_GENERAL_CUSTOMTEXT_PENDING_DESC, $aecConfig->cfg['customtext_pending'], 'customtext_pending');
	$tab_data[1][] = array( 'list', _CFG_GENERAL_CUSTOMTEXT_EXPIRED_KEEPORIGINAL_NAME, _CFG_GENERAL_CUSTOMTEXT_EXPIRED_KEEPORIGINAL_DESC, '0', 'customtext_expired_keeporiginal');
	$tab_data[1][] = array( 'editor', _CFG_GENERAL_CUSTOMTEXT_EXPIRED_NAME, _CFG_GENERAL_CUSTOMTEXT_EXPIRED_DESC, $aecConfig->cfg['customtext_expired'], 'customtext_expired');

	$tab_data[2] = array();
	$tab_data[2][] = _CFG_TAB_MICROINTEGRATION_TITLE;
	$tab_data[2][] = array( 'list_big', _CFG_MI_ACTIVELIST_NAME, _CFG_MI_ACTIVELIST_DESC, $aecConfig->cfg['milist'], 'milist' );
//	$tab_data[2][] = array('list', _CFG_MI_META_NAME, _CFG_MI_META_DESC, $aecConfig->cfg['enable_mimeta'], 'enable_mimeta');

	// TODO: reparse settings with new style aecSettings
	/*
	foreach ($tab_data as $tab => $items) {
		foreach ($items as $item => $content) {

		}
	}
	*/

	$pph					= new PaymentProcessorHandler();
	$gwlist					= $pph->getProcessorList();
	$gw_installed_list		= $pph->getInstalledObjectList( true );

	$gw_list_enabled		= array();
	$gw_list_enabled_html	= array();
	$gw_list_html			= array();

	$gw_list_html[]			= mosHTML::makeOption( 'none', _AEC_CMN_NONE_SELECTED );
	$gw_list_enabled_html[] = mosHTML::makeOption( 'none', _AEC_CMN_NONE_SELECTED );

	if ( !empty( $aecConfig->cfg['gwlist'] ) ) {
		$desc_list = explode( ';', $aecConfig->cfg['gwlist'] );
	} else {
		$desc_list = array();
	}
	$gwlist_selected = array();

	asort($gwlist);

	foreach ( $gwlist as $gwname ) {
		// Load Payment Processor
		$pp = new PaymentProcessor();
		if ( $pp->loadName( $gwname ) ) {
			if ( $pp->id ) {
				// Init Info and Settings
				$pp->fullInit();

				if ( $pp->processor->active ) {
					// Get Backend Settings
					$settings_array = $pp->getBackendSettings();

					if ( isset( $settings_array['lists'] ) ) {
						$lists = array_merge( $lists, $settings_array['lists'] );
						unset( $settings_array['lists'] );
					}

					// Create new Tab
					$tab = array();

					// Set Name and Subtitle from Info
					$tab[] = $pp->info['longname'];
					$tab[] = array( 'subtitle', '0', $pp->info['longname'], '0', '0' );

					// Iterate through settings form assigning the db settings
					foreach ( $settings_array as $name => $values ) {
						$setting_name = $pp->processor_name . '_' . $name;

						switch( $settings_array[$name][0] ) {
							case 'list_yesno':
								$lists[$setting_name] = mosHTML::yesnoSelectList( $setting_name, '', $pp->settings[$name] );

								$settings_array[$name][0] = 'list';
								break;

							case 'list_currency':
								// Get currency list
								$currency_array	= explode( ',', $pp->info['currencies'] );

								// Transform currencies into OptionArray
								$currency_code_list = array();
								foreach ( $currency_array as $currency ) {
									if ( defined( '_CURRENCY_' . $currency )) {
										$currency_code_list[] = mosHTML::makeOption( $currency, constant( '_CURRENCY_' . $currency ) );
									}
								}

								// Create list
								$lists[$setting_name] = mosHTML::selectList( $currency_code_list, $setting_name, 'size="10"', 'value', 'text', $pp->settings[$name] );
								$settings_array[$name][0] = 'list';
								break;

							case 'list_language':
								// Get language list
								$language_array	= explode( ',', $pp->info['languages'] );

								// Transform languages into OptionArray
								$language_code_list = array();
								foreach ( $language_array as $language ) {
									$language_code_list[] = mosHTML::makeOption( $language, ( defined( '_AEC_LANG_' . $language  ) ? constant( '_AEC_LANG_' . $language ) : $language ) );
								}
								// Create list
								$lists[$setting_name] = mosHTML::selectList( $language_code_list, $setting_name, 'size="10"', 'value', 'text', $pp->settings[$name] );
								$settings_array[$name][0] = 'list';
								break;

							case 'list_plan':
								// Create list
								$lists[$setting_name] = mosHTML::selectList($available_plans, $setting_name, 'size="' . $total_plans . '"', 'value', 'text', $pp->settings[$name] );
								$settings_array[$name][0] = 'list';
								break;

							default:
								break;
						}

						if ( !isset( $settings_array[$name][1] ) ) {
							// Create constant names
							$constantname = '_CFG_' . strtoupper( $gwname ) . '_' . strtoupper($name) . '_NAME';
							$constantdesc = '_CFG_' . strtoupper( $gwname ) . '_' . strtoupper($name) . '_DESC';

							// If the constantname does not exists, try a generic name or insert an error
							if ( defined( $constantname ) ) {
								$settings_array[$name][1] = constant( $constantname );
							} else {
								$genericname = '_CFG_PROCESSOR_' . strtoupper($name) . '_NAME';
								if ( defined( $genericname ) ) {
									$settings_array[$name][1] = constant( $genericname );
								} else {
									$settings_array[$name][1] = sprintf( _AEC_CMN_LANG_CONSTANT_IS_MISSING, $constantname );
								}
							}

							// If the constantname does not exists, try a generic name or insert an error
							if ( defined( $constantdesc ) ) {
								$settings_array[$name][2] = constant( $constantdesc );
							} else {
								$genericdesc = '_CFG_PROCESSOR_' . strtoupper($name) . '_DESC';
								if ( defined( $genericname ) ) {
									$settings_array[$name][2] = constant( $genericdesc );
								} else {
									$settings_array[$name][2] = sprintf( _AEC_CMN_LANG_CONSTANT_IS_MISSING, $constantdesc );
								}
							}
						}

						// It might be that the processor has got some new properties, so we need to double check here
						$new_settings = $pp->processor->settings();

						if ( isset( $pp->settings[$name] ) ) {
							$tab[] = array_merge( $settings_array[$name], array( $pp->settings[$name], $setting_name ) );
						} else {
							$tab[] = array_merge( $settings_array[$name], array( $new_settings[$name], $setting_name ) );
						}
					}

					$longname		= $gwname . '_info_longname';
					$description	= $gwname . '_info_description';

					$tab[] = array( 'inputC', _CFG_PROCESSOR_NAME_NAME, _CFG_PROCESSOR_NAME_DESC, $pp->info['longname'], $longname);
					$tab[] = array( 'editor', _CFG_PROCESSOR_DESC_NAME, _CFG_PROCESSOR_DESC_DESC, $pp->info['description'], $description);

					// Add to Settings Tabs
					$tab_data[] = $tab;

					// Add to Active List
					$gw_list_enabled[]->value = $gwname;

					// Add to selected Description List if existing in db entry
					if ( !empty( $desc_list ) ) {
						if ( in_array( $gwname, $desc_list ) ) {
							$gwlist_selected[]->value = $gwname;
						}
					}

					// Add to Description List
					$gw_list_enabled_html[] = mosHTML::makeOption( $gwname, $pp->info['longname'] );

				} else {
					$pp->Init();
					$pp->getInfo();
				}


			} else {
				$pp->info = $pp->processor->info();
			}

			// Add to general PP List
			$gw_list_html[] = mosHTML::makeOption( $gwname, $pp->info['longname'] );
		} else {
			// TODO: Log error
		}
	}

	$lists['gwlist_enabled']	= mosHTML::selectList($gw_list_html, 'gwlist_enabled[]', 'size="' . max(min(count($gwlist), 12), 2) . '" multiple', 'value', 'text', $gw_list_enabled);
	$lists['gwlist']			= mosHTML::selectList($gw_list_enabled_html, 'gwlist[]', 'size="' . max(min(count($gw_list_enabled), 12), 3) . '" multiple', 'value', 'text', $gwlist_selected);

	$editors = array();
	foreach ( $tab_data as $tab ) {
		foreach ( $tab as $st_content ) {
			if ( strcmp( $st_content[0], 'editor' ) === 0 ) {
				$editors[] = $st_content[4];
			}
		}
	}

	HTML_AcctExp::Settings( $option, $lists, $tab_data, $editors );
}

/**
* Cancels an configure operation
*/
function cancelSettings( $option )
{
	mosRedirect( 'index2.php?option=' . $option . '&task=showCentral', _AEC_CONFIG_CANCELLED );
}


function saveSettings( $option )
{
	global $database, $mainframe, $my, $acl, $aecConfig;

	$pplist_enabled		= mosGetParam( $_POST,'gwlist_enabled', '' );
	$pplist_installed	= PaymentProcessorHandler::getInstalledNameList();
	if ( is_array( $pplist_enabled ) && is_array( $pplist_installed ) ) {
		$total_processors = array_merge( $pplist_installed, $pplist_enabled );
	} else {
		$total_processors = array();
	}

	foreach ( $total_processors as $procname ) {
		$pp = new PaymentProcessor();

		// Go next if this is the zero option selected
		if ( !$procname ) {
			continue;
		}

		if ( $pp->loadName( $procname ) ) {
			$pp->fullInit();
			$pp->processor->active = in_array( $procname, $pplist_enabled );

			$pp->processor->check();
			$pp->processor->store();

			$longname		= $procname . '_info_longname';
			$description	= $procname . '_info_description';

			if ( isset( $_POST[$longname] ) ) {
				$pp->info['longname'] = $_POST[$longname];
				unset( $_POST[$longname] );
			}
			if ( isset( $_POST[$description] ) ) {
				$pp->info['description'] = $_POST[$description];
				unset( $_POST[$description] );
			}

			$pp->setInfo();

			$settings = $pp->processor->settings();

			foreach ( $settings as $name => $value ) {
				$postname = $procname  . '_' . $name;

				if ( isset( $_POST[$postname] ) ) {
					$val = $_POST[$postname];

					if ( empty( $val ) ) {
						switch( $name ) {
							case 'currency':
								$val = 'USD';
								break;
							default:
								break;
						}
					}

					$pp->settings[$name] = $_POST[$postname];
					unset( $_POST[$postname] );
				}
			}

			$pp->setSettings();
		} else {
			// TODO: Log error
		}
	}

	unset( $_POST['gwlist_enabled'] );
	unset( $_POST['id'] );
	unset( $_POST['task'] );
	unset( $_POST['option'] );

	$general_settings = array();

	foreach ( $_POST as $name => $value ) {
		if ( is_array( $value ) ) {
			$general_settings[$name] = implode( ';', $value );
		} else {
			$general_settings[$name] = $value;
		}
	}

	$diff = $aecConfig->diffParams($general_settings, 'settings');
	$difference = '';

	if ( is_array( $diff ) ) {
		$newdiff = array();
		foreach ( $diff as $value => $change ) {
			$newdiff[] = $value . '(' . implode( '->', $change ) . ')';
		}
		$difference = implode( ',', $newdiff );
	} else {
		$difference = 'none';
	}

	$aecConfig->cfg = $general_settings;
	$aecConfig->saveSettings();

	$ip = AECToolbox::_aecIP();

	$short	= _AEC_LOG_SH_SETT_SAVED;
	$event	= _AEC_LOG_LO_SETT_SAVED . ' ' . $difference;
	$tags	= 'settings,system';
	$params = array(	'userid' => $my->id,
						'ip' => $ip['ip'],
						'isp' => $ip['isp'] );

	$eventlog = new eventLog( $database );
	$eventlog->issue( $short, $tags, $event, $params );

	mosRedirect( 'index2.php?option=' . $option . '&task=showSettings', _AEC_CONFIG_SAVED );
}

function listSubscriptionPlans( $option )
{
 	global $database, $mainframe, $mosConfig_list_limit;

 	$limit = $mainframe->getUserStateFromRequest( "viewlistlimit", 'limit', $mosConfig_list_limit );
	$limitstart = $mainframe->getUserStateFromRequest( "viewconf{$option}limitstart", 'limitstart', 0 );

 	// get the total number of records
 	$query = 'SELECT count(*)'
 	. ' FROM #__acctexp_plans'
 	;
 	$database->setQuery( $query );
 	$total = $database->loadResult();
 	echo $database->getErrorMsg();

 	if ( $limit > $total ) {
 		$limitstart = 0;
 	}

 	require_once( $mainframe->getCfg( 'absolute_path' ) . '/administrator/includes/pageNavigation.php' );
	$pageNav = new mosPageNav( $total, $limitstart, $limit );

 	// get the subset (based on limits) of records
 	$query = 'SELECT *'
 	. ' FROM #__acctexp_plans'
 	. ' GROUP BY id'
 	. ' ORDER BY ordering'
 	. ' LIMIT ' . $pageNav->limitstart . ',' . $pageNav->limit
 	;
	$database->setQuery( $query );

 	$rows = $database->loadObjectList();
 	if ( $database->getErrorNum() ) {
 		echo $database->stderr();
 		return false;
 	}

	foreach ( $rows as $n => $row ) {
		$query = 'SELECT count(*)'
		. 'FROM #__users AS a'
		. ' LEFT JOIN #__acctexp_subscr AS b ON a.id = b.userid'
		. ' WHERE b.plan = ' . $row->id
		. ' AND (b.status = \'Active\' OR b.status = \'Trial\')'
		;
		$database->setQuery( $query	);

	 	$rows[$n]->usercount = $database->loadResult();
	 	if ( $database->getErrorNum() ) {
	 		echo $database->stderr();
	 		return false;
	 	}

	 	$query = 'SELECT count(*)'
		. ' FROM #__users AS a'
		. ' LEFT JOIN #__acctexp_subscr AS b ON a.id = b.userid'
		. ' WHERE b.plan = ' . $row->id
		. ' AND (b.status = \'Expired\')'
		;
		$database->setQuery( $query	);

	 	$rows[$n]->expiredcount = $database->loadResult();
	 	if ( $database->getErrorNum() ) {
	 		echo $database->stderr();
	 		return false;
	 	}
	}

 	HTML_AcctExp::listSubscriptionPlans( $rows, $pageNav, $option );
 }

function editSubscriptionPlan( $id, $option )
{
	global $database, $my, $acl;

	$lists = array();
	$params_values = array();
	$restrictions_values = array();
	$customparams_values = array();

	$row = new SubscriptionPlan( $database );
	$row->load( $id );

	if ( !$row->id ) {
		$row->ordering	= 9999;
		$hasrecusers	= false;

		$params_values['active']	= 1;
		$params_values['visible']	= 0;
		$params_values['processors'] = 0;

		$restrictions_values['gid_enabled']	= 1;
		$restrictions_values['gid']			= 18;
	} else {
		$params_values = $row->getParams( 'params' );
		$restrictions_values = $row->getParams( 'restrictions' );
		$customparams_values = $row->getParams( 'custom_params' );

		// We need to convert the values that are set as object properties
		$params_values['active']				= $row->active;
		$params_values['visible']				= $row->visible;
		$params_values['email_desc']			= $row->email_desc;
		$params_values['name']					= $row->name;
		$params_values['desc']					= $row->getProperty( 'desc' );
		$params_values['micro_integrations']	= $row->micro_integrations;

		// Checking if there is already a user, which disables certain actions
		$query  = 'SELECT count(*)'
		. ' FROM #__users AS a'
		. ' LEFT JOIN #__acctexp_subscr AS b ON a.id = b.userid'
		. ' WHERE b.plan = ' . $row->id
		. ' AND (b.status = \'Active\' OR b.status = \'Trial\')'
		. ' AND recurring =\'1\''
		;
		$database->setQuery( $query );
		$hasrecusers = ( $database->loadResult() > 0 ) ? true : false;
	}

	// params and their type values
	$params['active']					= array( 'list_yesno', 1 );
	$params['visible']					= array( 'list_yesno', 0 );

	$params['name']						= array( 'inputC', '' );
	$params['desc']						= array( 'editor', '' );
	$params['email_desc']				= array( 'inputD', '' );
	$params['micro_integrations']		= array( 'list', '' );

	$params['params_remap']				= array( 'subarea_change', 'params' );

	$params['full_free']				= array( 'list_yesno', '' );
	$params['full_amount']				= array( 'inputB', '' );
	$params['full_period']				= array( 'inputB', '' );
	$params['full_periodunit']			= array( 'list', 'D' );
	$params['trial_free']				= array( 'list_yesno', '' );
	$params['trial_amount']				= array( 'inputB', '' );
	$params['trial_period']				= array( 'inputB', '' );
	$params['trial_periodunit']			= array( 'list', 'D' );

	$params['gid_enabled']				= array( 'list_yesno', 1 );
	$params['gid']						= array( 'list', 18 );
	$params['lifetime']					= array( 'list_yesno', 0 );
	$params['processors']				= array( 'list', '' );
	$params['fallback']					= array( 'list', '' );
	$params['make_active']				= array( 'list_yesno', 1 );

	$params['similarplans']				= array( 'list', '' );
	$params['equalplans']				= array( 'list', '' );

	$params['restr_remap']				= array( 'subarea_change', 'restrictions' );

	$params['mingid_enabled']			= array( 'list_yesno', 0 );
	$params['mingid']					= array( 'list', 18 );
	$params['fixgid_enabled']			= array( 'list_yesno', 0 );
	$params['fixgid']					= array( 'list', 19 );
	$params['maxgid_enabled']			= array( 'list_yesno', 0 );
	$params['maxgid']					= array( 'list', 21 );
	$params['previousplan_req_enabled'] = array( 'list_yesno', 0 );
	$params['previousplan_req']			= array( 'list', 0 );
	$params['currentplan_req_enabled']	= array( 'list_yesno', 0 );
	$params['currentplan_req']			= array( 'list', 0 );
	$params['overallplan_req_enabled']	= array( 'list_yesno', 0 );
	$params['overallplan_req']			= array( 'list', 0 );
	$params['used_plan_min_enabled']	= array( 'list_yesno', 0 );
	$params['used_plan_min_amount']		= array( 'inputB', 0 );
	$params['used_plan_min']			= array( 'list', 0 );
	$params['used_plan_max_enabled']	= array( 'list_yesno', 0 );
	$params['used_plan_max_amount']		= array( 'inputB', 0 );
	$params['used_plan_max']			= array( 'list', 0 );
	$params['custom_restrictions_enabled']		= array( 'list_yesno', '' );
	$params['custom_restrictions']		= array( 'inputD', '' );
	$rewriteswitches					= array( 'cms', 'user' );
	$params['rewriteInfo']				= array( 'fieldset', '', AECToolbox::rewriteEngineInfo( $rewriteswitches ) );


	// ensure user can't add group higher than themselves
	$my_groups = $acl->get_object_groups( 'users', $my->id, 'ARO' );
	if ( is_array( $my_groups ) && count( $my_groups ) > 0) {
		$ex_groups = $acl->get_group_children( $my_groups[0], 'ARO', 'RECURSE' );
	} else {
		$ex_groups = array();
	}

	$gtree = $acl->get_group_children_tree( null, 'USERS', false );

	// mic: exclude public front- & backend
	$ex_groups[] = 29;
	$ex_groups[] = 30;

	// remove users 'above' me
	$i = 0;
	while ( $i < count( $gtree ) ) {
		if ( in_array( $gtree[$i]->value, $ex_groups ) ) {
			array_splice( $gtree, $i, 1 );
		} else {
			$i++;
		}
	}

	$lists['gid'] 		= mosHTML::selectList( $gtree, 'gid', 'size="6"', 'value', 'text', arrayValueDefault($params_values, 'gid', 18) );
	$lists['mingid'] 	= mosHTML::selectList( $gtree, 'mingid', 'size="6"', 'value', 'text', arrayValueDefault($restrictions_values, 'mingid', 18) );
	$lists['fixgid'] 	= mosHTML::selectList( $gtree, 'fixgid', 'size="6"', 'value', 'text', arrayValueDefault($restrictions_values, 'fixgid', 19) );
	$lists['maxgid'] 	= mosHTML::selectList( $gtree, 'maxgid', 'size="6"', 'value', 'text', arrayValueDefault($restrictions_values, 'maxgid', 21) );

	// make the select list for first trial period units
	$perunit[] = mosHTML::makeOption( 'D', _PAYPLAN_PERUNIT1 );
	$perunit[] = mosHTML::makeOption( 'W', _PAYPLAN_PERUNIT2 );
	$perunit[] = mosHTML::makeOption( 'M', _PAYPLAN_PERUNIT3 );
	$perunit[] = mosHTML::makeOption( 'Y', _PAYPLAN_PERUNIT4 );

	$lists['trial_periodunit'] = mosHTML::selectList($perunit, 'trial_periodunit', 'size="4"', 'value', 'text', arrayValueDefault($params_values, 'trial_periodunit', "D"));
	$lists['full_periodunit'] = mosHTML::selectList($perunit, 'full_periodunit', 'size="4"', 'value', 'text', arrayValueDefault($params_values, 'full_periodunit', "D"));

	$params['processors_remap'] = array("subarea_change", "plan_params");

	$pps = PaymentProcessorHandler::getInstalledObjectList( 1 );

	$plan_procs = explode(";", $params_values['processors']);

	$firstarray = array();
	$secndarray = array();
	foreach ( $pps as $ppo ) {
		if ( in_array( $ppo->id, $plan_procs ) && !empty( $customparams_values[$ppo->id . '_aec_overwrite_settings'] ) ) {
			$firstarray[] = $ppo;
		} else {
			$secndarray[] = $ppo;
		}
	}

	$pps = array_merge( $firstarray, $secndarray );

	$selected_gw = array();
	$customparamsarray = array();
	foreach ( $pps as $ppobj ) {
		if ( $ppobj->active ) {
			$pp = new PaymentProcessor();

			if ( $pp->loadName( $ppobj->name ) ) {
				$pp->getInfo();

				$customparamsarray[$pp->id] = array();
				$customparamsarray[$pp->id]['name'] = $pp->info['longname'];
				$customparamsarray[$pp->id]['params'] = array();

				$params['processor_' . $pp->id] = array( 'checkbox', _PAYPLAN_PROCESSORS_ACTIVATE_NAME, _PAYPLAN_PROCESSORS_ACTIVATE_DESC  );
				$customparamsarray[$pp->id]['params'][] = 'processor_' . $pp->id;

				$params[$pp->id . '_aec_overwrite_settings'] = array( 'checkbox', _PAYPLAN_PROCESSORS_OVERWRITE_SETTINGS_NAME, _PAYPLAN_PROCESSORS_OVERWRITE_SETTINGS_DESC );
				$customparamsarray[$pp->id]['params'][] = $pp->id . '_aec_overwrite_settings';

				if ( in_array( $pp->id, $plan_procs ) ) {
					$params_values['processor_' . $pp->id] = 1;

					$customparams = $pp->getCustomPlanParams();
					if ( is_array( $customparams ) ) {
						foreach ( $customparams as $customparam => $cpcontent ) {
							// Write the params field
							$cp_name = constant( strtoupper( "_CFG_" . $pp->processor_name . "_plan_params_" . $customparam . "_name" ) );
							$cp_desc = constant( strtoupper( "_CFG_" . $pp->processor_name . "_plan_params_" . $customparam . "_desc" ) );
							$shortname = $pp->id . "_" . $customparam;
							$params[$shortname] = array_merge( $cpcontent, array( $cp_name, $cp_desc ) );
							$customparamsarray[$pp->id]['params'][] = $shortname;
						}
					}

					if ( isset( $customparams_values[$pp->id . '_aec_overwrite_settings'] ) ) {
						if ( !$customparams_values[$pp->id . '_aec_overwrite_settings'] ) {
							continue;
						}
					} else {
						continue;
					}

					$settings_array = $pp->getBackendSettings();

					if ( isset( $settings_array['lists'] ) ) {
						foreach ( $settings_array['lists'] as $listname => $listcontent ) {
							$lists[$pp->id . '_' . $listname] = $listcontent;
						}

						unset( $settings_array['lists'] );
					}

					// Iterate through settings form assigning the db settings
					foreach ( $settings_array as $name => $values ) {
						$setting_name = $pp->id . '_' . $name;

						switch( $settings_array[$name][0] ) {
							case 'list_yesno':
								$lists[$setting_name] = mosHTML::yesnoSelectList( $setting_name, '', $pp->settings[$name] );

								$settings_array[$name][0] = 'list';
								break;

							case 'list_currency':
								// Get currency list
								$currency_array	= explode( ',', $pp->info['currencies'] );

								// Transform currencies into OptionArray
								$currency_code_list = array();
								foreach ( $currency_array as $currency ) {
									if ( defined( '_CURRENCY_' . $currency )) {
										$currency_code_list[] = mosHTML::makeOption( $currency, constant( '_CURRENCY_' . $currency ) );
									}
								}

								// Create list
								$lists[$setting_name] = mosHTML::selectList( $currency_code_list, $setting_name, 'size="10"', 'value', 'text', $pp->settings[$name] );
								$settings_array[$name][0] = 'list';
								break;

							case 'list_language':
								// Get language list
								$language_array	= explode( ',', $pp->info['languages'] );

								// Transform languages into OptionArray
								$language_code_list = array();
								foreach ( $language_array as $language ) {
									$language_code_list[] = mosHTML::makeOption( $language, ( defined( '_AEC_LANG_' . $language  ) ? constant( '_AEC_LANG_' . $language ) : $language ) );
								}
								// Create list
								$lists[$setting_name] = mosHTML::selectList( $language_code_list, $setting_name, 'size="10"', 'value', 'text', $pp->settings[$name] );
								$settings_array[$name][0] = 'list';
								break;

							case 'list_plan':
								unset( $settings_array[$name] );
								break;

							default:
								break;
						}

						if ( !isset( $settings_array[$name][1] ) ) {
							// Create constant names
							$constantname = '_CFG_' . strtoupper( $ppobj->name ) . '_' . strtoupper($name) . '_NAME';
							$constantdesc = '_CFG_' . strtoupper( $ppobj->name ) . '_' . strtoupper($name) . '_DESC';

							// If the constantname does not exists, try a generic name or insert an error
							if ( defined( $constantname ) ) {
								$settings_array[$name][1] = constant( $constantname );
							} else {
								$genericname = '_CFG_PROCESSOR_' . strtoupper($name) . '_NAME';
								if ( defined( $genericname ) ) {
									$settings_array[$name][1] = constant( $genericname );
								} else {
									$settings_array[$name][1] = sprintf( _AEC_CMN_LANG_CONSTANT_IS_MISSING, $constantname );
								}
							}

							// If the constantname does not exists, try a generic name or insert an error
							if ( defined( $constantdesc ) ) {
								$settings_array[$name][2] = constant( $constantdesc );
							} else {
								$genericdesc = '_CFG_PROCESSOR_' . strtoupper($name) . '_DESC';
								if ( defined( $genericname ) ) {
									$settings_array[$name][2] = constant( $genericdesc );
								} else {
									$settings_array[$name][2] = sprintf( _AEC_CMN_LANG_CONSTANT_IS_MISSING, $constantdesc );
								}
							}
						}

						$params[$pp->id . '_' . $name] = $settings_array[$name];
						$customparamsarray[$pp->id]['params'][] = $pp->id . '_' . $name;
					}

				}

			}
		}
	}

	// get available active plans
	$available_plans = array();
	$available_plans[] = mosHTML::makeOption( '0', _PAYPLAN_NOPLAN );

	$query = 'SELECT id AS value, name AS text'
	. ' FROM #__acctexp_plans'
	. ' WHERE active = 1'
	. ' AND id <> ' . $id
	;
	$database->setQuery( $query );
	$payment_plans = $database->loadObjectList();

 	if ( is_array( $payment_plans ) ) {
 		$active_plans	= array_merge( $available_plans, $payment_plans );
 	}
	$total_plans	= min( max( (count( $active_plans ) + 1 ), 4 ), 20 );

	$lists['fallback'] = mosHTML::selectList($active_plans, 'fallback', 'size="' . $total_plans . '"', 'value', 'text', arrayValueDefault($params_values, 'fallback', 0));

	// get similar plans
	if ( !empty( $params_values['similarplans'] ) ) {
		$query = 'SELECT id AS value, name As text'
		. ' FROM #__acctexp_plans'
		. ' WHERE id IN (' . implode( ',', explode( ';', $params_values['similarplans'] ) ) .')'
		;
		$database->setQuery( $query );

	 	$sel_similar_plans = $database->loadObjectList();
	} else {
		$sel_similar_plans = 0;
	}

	$lists['similarplans'] = mosHTML::selectList($payment_plans, 'similarplans[]', 'size="' . $total_plans . '" multiple', 'value', 'text', $sel_similar_plans);

	// get equal plans
	if ( !empty( $params_values['equalplans'] ) ) {
		$query = 'SELECT id AS value, name AS text'
		. ' FROM #__acctexp_plans'
		. ' WHERE id IN (' . implode( ',', explode( ';', $params_values['equalplans'] ) ) .')'
		;
		$database->setQuery( $query );

	 	$sel_equal_plans = $database->loadObjectList();
	} else {
		$sel_equal_plans = 0;
	}

	$lists['equalplans'] = mosHTML::selectList($payment_plans, 'equalplans[]', 'size="' . $total_plans . '" multiple', 'value', 'text', $sel_equal_plans);

	// get available plans
	$available_plans = array();
	$available_plans[] = mosHTML::makeOption( '0', _PAYPLAN_NOPLAN );

	$query = 'SELECT id AS value, name AS text'
	. ' FROM #__acctexp_plans'
	;
	$database->setQuery( $query );
	$plans = $database->loadObjectList();

 	if ( is_array( $plans ) ) {
 		$all_plans	= array_merge( $available_plans, $plans );
 	} else {
 		$all_plans	= $available_plans;
 	}
	$total_all_plans	= min( max( ( count( $all_plans ) + 1 ), 4 ), 20 );

	$lists['previousplan_req']	= mosHTML::selectList($all_plans, 'previousplan_req', 'size="' . $total_all_plans . '"',
									'value', 'text', arrayValueDefault($restrictions_values, 'previousplan_req', 0));
	$lists['currentplan_req']	= mosHTML::selectList($all_plans, 'currentplan_req', 'size="' . $total_all_plans . '"',
									'value', 'text', arrayValueDefault($restrictions_values, 'currentplan_req', 0));
	$lists['overallplan_req']	= mosHTML::selectList($all_plans, 'overallplan_req', 'size="' . $total_all_plans . '"',
									'value', 'text', arrayValueDefault($restrictions_values, 'overallplan_req', 0));
	$lists['used_plan_min']		= mosHTML::selectList($all_plans, 'used_plan_min', 'size="' . $total_all_plans . '"',
									'value', 'text', arrayValueDefault($restrictions_values, 'used_plan_min', 0));
	$lists['used_plan_max']		= mosHTML::selectList($all_plans, 'used_plan_max', 'size="' . $total_all_plans . '"',
									'value', 'text', arrayValueDefault($restrictions_values, 'used_plan_max', 0));

	// get available micro integrations
	$query = 'SELECT id AS value, CONCAT(`name`, " - ", `desc`) AS text'
	. ' FROM #__acctexp_microintegrations'
	. ' WHERE active = 1'
	. ' ORDER BY ordering'
	;
	$database->setQuery( $query );
	$mi_list = $database->loadObjectList();

	$query = 'SELECT id AS value, CONCAT(`name`, " - ", `desc`) AS text'
	. ' FROM #__acctexp_microintegrations'
	. ' WHERE id IN (' . implode( ',', explode( ';', $row->micro_integrations ) ) . ')'
	;
 	$database->setQuery( $query );
	$selected_mi = $database->loadObjectList();

	$lists['micro_integrations'] = mosHTML::selectList($mi_list, 'micro_integrations[]', 'size="' . min((count( $mi_list ) + 1), 25) . '" multiple', 'value', 'text', $selected_mi);

	$settings = new aecSettings ( 'payplan', 'general' );
	if ( is_array( $customparams_values ) ) {
		$settingsparams = array_merge( $params_values, $customparams_values, $restrictions_values );
	} else {
		$settingsparams = array_merge( $params_values, $restrictions_values );
	}
	$settings->fullSettingsArray( $params, $settingsparams, $lists) ;
//print_r($params);print_r($lists);exit();
	// Call HTML Class
	$aecHTML = new aecHTML( $settings->settings, $settings->lists );
	if ( !empty( $customparamsarray ) ) {
		$aecHTML->customparams = $customparamsarray;
	}

	HTML_AcctExp::editSubscriptionPlan( $option, $aecHTML, $row, $hasrecusers );
}

function arrayValueDefault( $array, $name, $default )
{
	if ( isset( $array[$name] ) ) {
		return $array[$name];
	} else {
		return $default;
	}
}

function saveSubscriptionPlan( $option )
{
	global $database, $mosConfig_live_site;

	$row = new SubscriptionPlan( $database );
	$row->load( $_POST['id'] );

	$post = AECToolbox::cleanPOST( $_POST );

	$row->savePOSTsettings( $post );

	if ( !$row->check() ) {
		echo "<script> alert('".$row->getError()."'); window.history.go(-2); </script>\n";
		exit();
	}
	if ( !$row->store() ) {
		echo "<script> alert('".$row->getError()."'); window.history.go(-2); </script>\n";
		exit();
	}

	$row->updateOrder();
	mosRedirect( 'index2.php?option=' . $option . '&task=showSubscriptionPlans' );

}

function removeSubscriptionPlan( $id, $option )
{
	global $database, $mosConfig_live_site;

	$ids = implode( ',', $id );

	$query = 'SELECT count(*)'
	. ' FROM #__acctexp_plans'
	. ' WHERE id IN (' . $ids . ')'
	;
	$database->setQuery( $query );
	$total = $database->loadResult();

	if ( $total == 0 ) {
		echo "<script> alert('" . html_entity_decode( _AEC_MSG_NO_ITEMS_TO_DELETE ) . "'); window.history.go(-1);</script>\n";
		exit;
	}

	// See if we have registered users on this plan.
	// If we have it, the plan(s) cannot be removed
	$query = 'SELECT count(*)'
	. ' FROM #__users AS a'
	. ' LEFT JOIN #__acctexp_subscr AS b ON a.id = b.userid'
	. ' WHERE b.plan = ' . $row->id
	. ' AND (b.status = \'Active\' OR b.status = \'Trial\')'
	;
	$database->setQuery( $query );
	$subscribers = $database->loadResult();

	if ( $subscribers > 0 ) {
		$msg = _AEC_MSG_NO_DEL_W_ACTIVE_SUBSCRIBER;
	} else {
		// Delete plans
		$query = 'DELETE FROM #__acctexp_plans'
		. ' WHERE id IN (' . $ids . ')'
		;
		$database->setQuery( $query );
		if ( !$database->query() ) {
			echo "<script> alert('".$database->getErrorMsg()."'); window.history.go(-1); </script>\n";
			exit();
		}

		$msg = $total . ' ' . _AEC_MSG_ITEMS_DELETED;
	}
	mosRedirect( 'index2.php?option=' . $option . '&task=showSubscriptionPlans', $msg );
}

function cancelSubscriptionPlan( $option )
{
	global $mosConfig_live_site;

	mosRedirect( 'index2.php?option=' . $option . '&task=showSubscriptionPlans', _AEC_CMN_EDIT_CANCELLED );
}

function changeSubscriptionPlan( $cid=null, $state=0, $type, $option )
{
	global $database, $mosConfig_live_site;

	if ( count( $cid ) < 1 ) {
		echo "<script> alert('" . _AEC_ALERT_SELECT_FIRST . "'); window.history.go(-1);</script>\n";
		exit;
	}

	$total	= count( $cid );
	$cids	= implode( ',', $cid );

	$query = 'UPDATE #__acctexp_plans'
	. ' SET ' . $type . ' = \'' . $state . '\''
	. ' WHERE id IN (' . $cids . ')'
	;
	$database->setQuery( $query );

	if ( !$database->query() ) {
		echo "<script> alert('".$database->getErrorMsg()."'); window.history.go(-1); </script>\n";
		exit();
	}

	if ( $state == '1' ) {
		$msg = ( ( strcmp( $type, 'active' ) === 0 ) ? _AEC_CMN_PUBLISHED : _AEC_CMN_MADE_VISIBLE );
	} elseif ( $state == '0' ) {
		$msg = ( ( strcmp( $type, 'active' ) === 0 ) ? _AEC_CMN_NOT_PUBLISHED : _AEC_CMN_MADE_INVISIBLE );
	}

	$msg = sprintf( _AEC_MSG_ITEMS_SUCESSFULLY, $total ) . ' ' . $msg;

	mosRedirect( 'index2.php?option=' . $option . '&task=showSubscriptionPlans', $msg );
}

function listMicroIntegrations( $option )
{
 	global $database, $mainframe, $mosConfig_list_limit;

 	$limit		= $mainframe->getUserStateFromRequest( "viewlistlimit", 'limit', $mosConfig_list_limit );
	$limitstart	= $mainframe->getUserStateFromRequest( "viewconf{$option}limitstart", 'limitstart', 0 );

 	// get the total number of records
 	$query = 'SELECT count(*)'
 	. ' FROM #__acctexp_microintegrations'
 	;
 	$database->setQuery( $query );
 	$total = $database->loadResult();
 	echo $database->getErrorMsg();

 	if ( $limit > $total ) {
 		$limitstart = 0;
 	}

 	require_once( $mainframe->getCfg( 'absolute_path' ) . '/administrator/includes/pageNavigation.php' );
	$pageNav = new mosPageNav( $total, $limitstart, $limit );

 	// get the subset (based on limits) of required records
 	$query = 'SELECT *'
 	. ' FROM #__acctexp_microintegrations'
 	. ' GROUP BY id'
 	. ' ORDER BY ordering'
 	. ' LIMIT ' . $pageNav->limitstart . ',' . $pageNav->limit
 	;
 	$database->setQuery( $query );

 	$rows = $database->loadObjectList();
 	if ( $database->getErrorNum() ) {
 		echo $database->stderr();
 		return false;
 	}

 	HTML_AcctExp::listMicroIntegrations( $rows, $pageNav, $option );
 }

function editMicroIntegration ( $id, $option )
{
	global $database, $my, $acl, $aecConfig;

	$lists	= array();
	$mi		= new microIntegration( $database );
	$mi->load( $id );

	$aecHTML = null;

	if ( !$mi->id ) {
		// Set lowest ordering
		$mi->ordering	= 9999;
		$mi_list		= array();
		$mi_htmllist	= array();

		if ( $aecConfig->cfg['milist'] ) {
			$mi_list = explode( ';', $aecConfig->cfg['milist'] );
		}

		if ( count( $mi_list ) > 0 ) {
			foreach ( $mi_list as $name ) {
				$mi_item = new microIntegration( $database );
				$mi_item->class_name = $name;
				if ( $mi_item->callIntegration() ) {
					$len = 30 - AECToolbox::visualstrlen( trim( $mi->name ) );
					$fullname = str_replace( '#', '&nbsp;', str_pad( $mi_item->name, $len, '#' ) )
					. ' - ' . $mi_item->desc;
					$mi_htmllist[] = mosHTML::makeOption( $name, $fullname );
				}
			}

			// mic: to avoid displaing an empty list, show instead message at html.page
			$lists['class_name'] = mosHTML::selectList( $mi_htmllist, 'class_name', 'size="' . min( ( count( $mi_list ) + 1 ), 25 ) . '"', 'value', 'text', '' );
		} else {
			$lists['class_name'] = '';
		}
	} else {
		// Call MI (override active check) and Settings
		if ( $mi->callIntegration( 1 ) ) {
			$mi_settings = $mi->getSettings();

			// Get lists supplied by the MI
			if ( @is_array($mi_settings['lists']) ) {
				$lists = array_merge( $lists, $mi_settings['lists'] );
				unset( $mi_settings['lists'] );
			}

			$settings = new aecSettings( 'MI', $mi->class_name );
			$settings->fullSettingsArray( $mi_settings, array(), $lists );

			// Call HTML Class
			$aecHTML = new aecHTML( $settings->settings, $settings->lists );
		} else {
			// TODO: log error
		}
	}

	// make select lists
	$lists['yesno']				= mosHTML::yesnoSelectList('active', '', $mi->active);
	$lists['yesno_auto']		= mosHTML::yesnoSelectList('auto_check', '', $mi->auto_check);
	$lists['yesno_userupdate']	= mosHTML::yesnoSelectList('on_userchange', '', $mi->on_userchange);

	HTML_AcctExp::editMicroIntegration( $option, $mi, $lists, $aecHTML );
}

function saveMicroIntegration( $option )
{
	global $database, $mosConfig_live_site;

	unset( $_POST['option'] );
	unset( $_POST['task'] );

	$id = isset( $_POST['id'] ) ? $_POST['id'] : 0;

	$mi = new microIntegration( $database );
	$mi->load( $id );

	$mi->name			= $_POST['name'];
	$mi->desc			= $_POST['desc'];
	$mi->active			= $_POST['active'];
	if ($_POST['class_name']) {
		$mi->class_name	= $_POST['class_name'];
	}
	$mi->auto_check		= $_POST['auto_check'];
	$mi->on_userchange	= $_POST['on_userchange'];
	$mi->pre_exp_check	= $_POST['pre_exp_check'];

	if ( $mi->callIntegration() ) {
		$mi->savePostParams( $_POST );

		if ( !$mi->check() ) {
			echo "<script> alert('".$mi->getError()."'); window.history.go(-2); </script>\n";
			exit();
		}
		if ( !$mi->store() ) {
			echo "<script> alert('".$mi->getError()."'); window.history.go(-2); </script>\n";
			exit();
		}
	} else {
		// TODO: log error
	}

	if ( !$id ) {
		$query = "SELECT max(id) FROM #__acctexp_microintegrations";
		$database->setQuery( $query );
		$newid = $database->loadResult();
	}

	$mi->updateOrder();

	if ( $id ) {
		mosRedirect( 'index2.php?option=' . $option . '&task=showMicroIntegrations', _AEC_MSG_SUCESSFULLY_SAVED );
	} else {
		mosRedirect( 'index2.php?option=' . $option . '&task=editMicroIntegration&id=' . $newid, _AEC_MSG_SUCESSFULLY_SAVED );
	}

}

function removeMicroIntegration( $id, $option )
{
	global $database, $mosConfig_live_site;

	$ids = implode( ',', $id );

	$query = 'SELECT count(*)'
	. ' FROM #__acctexp_microintegrations'
	. ' WHERE id IN (' . $ids . ')'
	;
	$database->setQuery( $query );
	$total = $database->loadResult();

	if ( $total==0 ) {
		echo "<script> alert('" . html_entity_decode( _AEC_MSG_NO_ITEMS_TO_DELETE ) . "'); window.history.go(-1);</script>\n";
		exit;
	}

	// Call On-Deletion function
	foreach ( $id as $k ) {
		$mi = new microIntegration($database);
		$mi->load($k);
		if ( $mi->callIntegration() ) {
			$mi->delete();
		}
	}

	// Micro Integrations from table
	$query = 'DELETE FROM #__acctexp_microintegrations'
	. ' WHERE id IN (' . $ids . ')'
	;
	$database->setQuery( $query	);

	if ( !$database->query() ) {
		echo "<script> alert('".$database->getErrorMsg()."'); window.history.go(-1); </script>\n";
		exit();
	}

	$msg = $total . ' ' . _AEC_MSG_ITEMS_DELETED;

	mosRedirect( 'index2.php?option=' . $option . '&task=showMicroIntegrations', $msg );
}

function cancelMicroIntegration( $option )
{
	global $mosConfig_live_site;

	mosRedirect( 'index2.php?option=' . $option . '&task=showMicroIntegrations', _AEC_CMN_EDIT_CANCELLED );
}

// Changes the state of one or more content pages
// @param array An array of unique plan id numbers
// @param integer 0 if unpublishing, 1 if publishing
//

function changeMicroIntegration( $cid=null, $state=0, $option )
{
	global $database, $mosConfig_live_site;

	if ( count( $cid ) < 1 ) {
		$action = $state == 1 ? _AEC_CMN_TOPUBLISH: _AEC_CMN_TOUNPUBLISH;
		echo "<script> alert('" . sprintf( html_entity_decode( _AEC_ALERT_SELECT_FIRST_TO ), $action ) . "'); window.history.go(-1);</script>\n";
		exit;
	}

	$total = count( $cid );
	$cids = implode( ',', $cid );

	$query = 'UPDATE #__acctexp_microintegrations'
	. ' SET active = \'' . $state . '\''
	. ' WHERE id IN (' . $cids . ')'
	;
	$database->setQuery( $query );
	if ( !$database->query() ) {
		echo "<script> alert('".$database->getErrorMsg()."'); window.history.go(-1); </script>\n";
		exit();
	}

	if ( $state == '1' ) {
		$msg = $total . ' ' . _AEC_MSG_ITEMS_SUCC_PUBLISHED;
	} elseif ( $state == '0' ) {
		$msg = $total . ' ' . _AEC_MSG_ITEMS_SUCC_UNPUBLISHED;
	}

	mosRedirect( 'index2.php?option=' . $option . '&task=showMicroIntegrations', $msg );
}

function listCoupons( $option, $type )
{
 	global $database, $mainframe, $mosConfig_list_limit;

 	$limit		= $mainframe->getUserStateFromRequest( "viewlistlimit", 'limit', $mosConfig_list_limit );
	$limitstart = $mainframe->getUserStateFromRequest( "viewconf{$option}limitstart", 'limitstart', 0 );

	$total = 0;

	if ( !$type ) {
	 	$table = '#__acctexp_coupons';
	} else {
	 	$table = '#__acctexp_coupons_static';
	}

	$query = 'SELECT count(*)'
	. ' FROM ' . $table
	;
 	$database->setQuery( $query );
 	$total = $database->loadResult();

 	if ( $limit > $total ) {
 		$limitstart = 0;
 	}

 	require_once( $mainframe->getCfg( 'absolute_path' ) . '/administrator/includes/pageNavigation.php' );
	$pageNav = new mosPageNav( $total, $limitstart, $limit );

 	// get the subset (based on limits) of required records
 	$query = 'SELECT *'
 	. ' FROM ' . $table
 	. ' GROUP BY id'
 	. ' ORDER BY ordering'
 	. ' LIMIT ' . $pageNav->limitstart . ',' . $pageNav->limit
 	;
 	$database->setQuery( $query	);

 	$rows = $database->loadObjectList();
 	if ( $database->getErrorNum() ) {
 		echo $database->stderr();
 		return false;
 	}

	HTML_AcctExp::listCoupons( $rows, $pageNav, $option, $type );
 }

function editCoupon( $id, $option, $new, $type )
{
	global $database, $my, $acl;

	$lists					= array();
	$params_values			= array();
	$restrictions_values	= array();

	$cph = new couponHandler();

	if ( !$new ) {
		$cph->coupon = new Coupon($database, $type);
		$cph->coupon->load( $id );

		$params_values			= $cph->coupon->getParams( 'params' );
		$discount_values		= $cph->coupon->getParams( 'discount' );
		$restrictions_values	= $cph->coupon->getParams( 'restrictions' );
	} else {
		$cph->coupon = new coupon($database, 1);
		$cph->coupon->createNew();
		// mic_ fix php.error
		$discount_values		= array();
		$restrictions_values	= array();
	}

	// We need to convert the values that are set as object properties
	$params_values['active']				= $cph->coupon->active;
	$params_values['type']					= $type;
	$params_values['name']					= $cph->coupon->name;
	$params_values['desc']					= $cph->coupon->desc;
	$params_values['coupon_code']			= $cph->coupon->coupon_code;
	$params_values['usecount']				= $cph->coupon->usecount;
	$params_values['micro_integrations']	= $cph->coupon->micro_integrations;

	// params and their type values
	$params['active']						= array( 'list_yesno',		1 );
	$params['type']							= array( 'list_yesno',		1 );
	$params['name']							= array( 'inputC',			'' );
	$params['desc']							= array( 'inputE',			'' );
	$params['coupon_code']					= array( 'inputC',			'' );
	$params['micro_integrations']			= array( 'list',			'' );

	$params['params_remap']					= array( 'subarea_change',	'params' );

	$params['amount_use']					= array( 'list_yesno',		'' );
	$params['amount']						= array( 'inputB',			'' );
	$params['amount_percent_use']			= array( 'list_yesno',		'' );
	$params['amount_percent']				= array( 'inputB',			'' );
	$params['percent_first']				= array( 'list_yesno',		'' );
	$params['useon_trial']					= array( 'list_yesno',		'' );
	$params['useon_full']					= array( 'list_yesno',		'' );
	$params['useon_full_all']				= array( 'list_yesno',		'' );

	$params['has_start_date']				= array( 'list_yesno',		1 );
	$params['start_date']					= array( 'list_date',		date( 'Y-m-d' ) );
	$params['has_expiration']				= array( 'list_yesno',		0);
	$params['expiration']					= array( 'list_date',		date( 'Y-m-d' ) );
	$params['has_max_reuse']				= array( 'list_yesno',		1 );
	$params['max_reuse']					= array( 'inputB',			1 );
	$params['usecount']						= array( 'inputB',			0 );

	$params['usage_plans_enabled']			= array( 'list_yesno',		0 );
	$params['usage_plans']					= array( 'list',			0 );

	$params['restr_remap']					= array( 'subarea_change',	'restrictions' );

	$params['mingid_enabled']				= array( 'list_yesno',		0 );
	$params['mingid']						= array( 'list',			18 );
	$params['fixgid_enabled']				= array( 'list_yesno',		0 );
	$params['fixgid']						= array( 'list',			19 );
	$params['maxgid_enabled']				= array( 'list_yesno',		0 );
	$params['maxgid']						= array( 'list',			21 );
	$params['previousplan_req_enabled']		= array( 'list_yesno',		0 );
	$params['previousplan_req']				= array( 'list',			'' );
	$params['currentplan_req_enabled']		= array( 'list_yesno',		0 );
	$params['currentplan_req']				= array( 'list',			'' );
	$params['overallplan_req_enabled']		= array( 'list_yesno',		0 );
	$params['overallplan_req']				= array( 'list',			'' );
	$params['used_plan_min_enabled']		= array( 'list_yesno',		0 );
	$params['used_plan_min_amount']			= array( 'inputB',			0 );
	$params['used_plan_min']				= array( 'list',			'' );
	$params['used_plan_max_enabled']		= array( 'list_yesno',		0 );
	$params['used_plan_max_amount']			= array( 'inputB',			0 );
	$params['used_plan_max']				= array( 'list',			'' );
	$params['restrict_combination']			= array( 'list_yesno',		0 );
	$params['bad_combinations']				= array( 'list',			'' );

	// ensure user can't add group higher than themselves
	$my_groups = $acl->get_object_groups( 'users', $my->id, 'ARO' );
	if ( is_array( $my_groups ) && count( $my_groups ) > 0 ) {
		$ex_groups = $acl->get_group_children( $my_groups[0], 'ARO', 'RECURSE' );
	} else {
		$ex_groups = array();
	}

	$gtree = $acl->get_group_children_tree( null, 'USERS', false );

	// mic: exclude public front- & backend
	$ex_groups[] = 29;
	$ex_groups[] = 30;

	// remove users 'above' me
	$i = 0;
	while ( $i < count( $gtree ) ) {
		if ( in_array( $gtree[$i]->value, $ex_groups ) ) {
			array_splice( $gtree, $i, 1 );
		} else {
			$i++;
		}
	}

	$lists['mingid']			= mosHTML::selectList( $gtree, 'mingid', 'size="6"', 'value', 'text',
									arrayValueDefault($restrictions_values, 'mingid', 18) );
	$lists['fixgid']			= mosHTML::selectList( $gtree, 'fixgid', 'size="6"', 'value', 'text',
									arrayValueDefault($restrictions_values, 'fixgid', 19) );
	$lists['maxgid']			= mosHTML::selectList( $gtree, 'maxgid', 'size="6"', 'value', 'text',
									arrayValueDefault($restrictions_values, 'maxgid', 21) );

	// get available plans
	$available_plans = array();
	$available_plans[]			= mosHTML::makeOption( '0', _PAYPLAN_NOPLAN );

	$query = 'SELECT id as value, name as text'
	. ' FROM #__acctexp_plans'
	;
	$database->setQuery( $query );
	$plans = $database->loadObjectList();

 	if ( is_array( $plans ) ) {
 		$all_plans					= array_merge( $available_plans, $plans );
 	} else {
 		$all_plans					= $available_plans;
 	}
	$total_all_plans			= min( max( ( count( $all_plans ) + 1 ), 4 ), 20 );

	$lists['previousplan_req']	= mosHTML::selectList($all_plans, 'previousplan_req', 'size="' . $total_all_plans . '"',
									'value', 'text', arrayValueDefault($restrictions_values, 'previousplan_req', 0));
	$lists['currentplan_req']	= mosHTML::selectList($all_plans, 'currentplan_req', 'size="' . $total_all_plans . '"',
									'value', 'text', arrayValueDefault($restrictions_values, 'currentplan_req', 0));
	$lists['overallplan_req']	= mosHTML::selectList($all_plans, 'overallplan_req', 'size="' . $total_all_plans . '"',
									'value', 'text', arrayValueDefault($restrictions_values, 'overallplan_req', 0));
	$lists['used_plan_min']		= mosHTML::selectList($all_plans, 'used_plan_min', 'size="' . $total_all_plans . '"',
									'value', 'text', arrayValueDefault($restrictions_values, 'used_plan_min', 0));
	$lists['used_plan_max']		= mosHTML::selectList($all_plans, 'used_plan_max', 'size="' . $total_all_plans . '"',
									'value', 'text', arrayValueDefault($restrictions_values, 'used_plan_max', 0));

	// get usages
	if ( !empty( $restrictions_values['usage_plans'] ) ) {
		$query = 'SELECT id AS value, name as text'
		. ' FROM #__acctexp_plans'
		. ' WHERE id IN (' . implode( ',', explode( ';', $restrictions_values['usage_plans'] ) ) . ')'
		;
		$database->setQuery( $query );

	 	$sel_usage_plans = $database->loadObjectList();
	} else {
		$sel_usage_plans = 0;
	}

	$lists['usage_plans']		= mosHTML::selectList($all_plans, 'usage_plans[]', 'size="' . $total_all_plans . '" multiple',
									'value', 'text', $sel_usage_plans);

	// get available micro integrations
	$available_mi = array();

	$query = 'SELECT id AS value, CONCAT(`name`, " - ", `desc`) AS text'
	. ' FROM #__acctexp_microintegrations'
	. ' WHERE active = 1'
	. ' ORDER BY ordering'
	;
	$database->setQuery( $query );
	$mi_list = $database->loadObjectList();

 	// mic: fix for wrong select
 	$query = 'SELECT id AS value, CONCAT(`name`, " - ", `desc`) AS text'
 	. ' FROM #__acctexp_microintegrations'
 	. ( $mi_list ? ' WHERE id IN (' . implode( ',', explode( ',', $mi_list->value ) ) . ')' : '' )
 	;
 	$database->setQuery( $query );
	$selected_mi = $database->loadObjectList();

	$lists['micro_integrations'] = mosHTML::selectList( $mi_list, 'micro_integrations[]', 'size="' . min((count( $mi_list ) + 1), 25) . '" multiple', 'value', 'text', $selected_mi );

	$query = 'SELECT coupon_code as value, coupon_code as text'
	. ' FROM #__acctexp_coupons'
	. ' WHERE coupon_code != \'' . $cph->coupon->coupon_code . '\''
	;
	$database->setQuery( $query );
	$coupons = $database->loadObjectList();

	$query = 'SELECT coupon_code as value, coupon_code as text'
	. ' FROM #__acctexp_coupons_static'
	. ' WHERE coupon_code != \'' . $cph->coupon->coupon_code . '\''
	;
	$database->setQuery( $query );
	$coupons = array_merge( $database->loadObjectList(), $coupons );

	if ( !empty( $restrictions_values['bad_combinations'] ) ) {
		$query = 'SELECT coupon_code as value, coupon_code as text'
		. ' FROM #__acctexp_coupons'
		. ' WHERE coupon_code IN (\'' . implode( '\',\'', explode( ';', $restrictions_values['bad_combinations'] ) ) . '\')'
		;
		$database->setQuery( $query );
		$sel_coupons = $database->loadObjectList();

		$query = 'SELECT coupon_code as value, coupon_code as text'
		. ' FROM #__acctexp_coupons_static'
		. ' WHERE coupon_code IN (\'' . implode( '\',\'', explode( ';', $restrictions_values['bad_combinations'] ) ) . '\')'
		;
		$database->setQuery( $query );
		$sel_coupons = array_merge( $database->loadObjectList(), $sel_coupons );
	} else {
		$sel_coupons = '';
	}

	$lists['bad_combinations']		= mosHTML::selectList($coupons, 'bad_combinations[]', 'size="' . min((count( $coupons ) + 1), 25) . '" multiple',
									'value', 'text', $sel_coupons);

	$settings = new aecSettings( 'coupon', 'general' );
	$settings->fullSettingsArray( $params, array_merge( (array) $params_values, (array) $discount_values, (array) $restrictions_values ), $lists );

	// Call HTML Class
	$aecHTML = new aecHTML( $settings->settings, $settings->lists );

	HTML_AcctExp::editCoupon( $option, $aecHTML, $cph->coupon, $type );
}

function saveCoupon( $option, $type )
{
	global $database, $mosConfig_live_site;

	$new = 0;
	$type = $_POST['type'];

	if ( $_POST['coupon_code'] != '' ) {

		$cph = new couponHandler();

		if ( $_POST['id'] ) {
			$cph->coupon = new Coupon( $database, $type );
			$cph->coupon->load( $_POST['id'] );
			if ( !$cph->coupon->id ) {
				$cph->coupon = new Coupon( $database, !$type );
				$cph->coupon->load( $_POST['id'] );
			}
			if ( $cph->coupon->id ) {
				$cph->status = 1;
			}
		} else {
			$cph->load( $_POST['coupon_code'] );
		}

		if ( !$cph->status ) {
			$cph->coupon = new coupon($database, $type);
			$cph->coupon->createNew($_POST['coupon_code']);
			$cph->status = true;
			$new = 1;
		}

		if ( $cph->status ) {

			if ( !$new ) {
				if ( $cph->type != $_POST['type'] ) {
					// The type has been changed - switching this over to the other table
					// Deleting old entry
					$originaldate = $cph->coupon->created_date;
					$cph->coupon->delete();
					// Create new entry
					$cph->coupon = new coupon($database, $_POST['type']);
					$cph->coupon->createNew($_POST['coupon_code'], $originaldate);
				}
			}

			unset( $_POST['type'] );
			unset( $_POST['id'] );
			$post = AECToolbox::cleanPOST( $_POST );

			$cph->coupon->savePOSTsettings( $post );

			if ( !$cph->coupon->check() ) {
				echo "<script> alert('".$cph->coupon->getError()."'); window.history.go(-2); </script>\n";
				exit();
			}
			if ( !$cph->coupon->store() ) {
				echo "<script> alert('".$cph->coupon->getError()."'); window.history.go(-2); </script>\n";
				exit();
			}
		} else {
			// TODO: log error
		}

		$cph->coupon->updateOrder();
		mosRedirect( 'index2.php?option=' . $option . '&task=showCoupons' . ( $type ? 'Static' : '' ) );
	} else {
		mosRedirect( 'index2.php?option=' . $option . '&task=showCoupons' . ( $type ? 'Static' : '' ), _AEC_MSG_NO_COUPON_CODE );
	}

}

function removeCoupon( $id, $option, $returnTask, $type )
{
	global $database, $mosConfig_live_site;

	$ids = implode( ',', $id );

	// Delete Coupons from table
	$query = 'DELETE FROM #__acctexp_coupons'
	. ( $type ? '_static' : '' )
	. ' WHERE id IN (' . $ids . ')'
	;
	$database->setQuery( $query	);

	if ( !$database->query() ) {
		echo "<script> alert('".$database->getErrorMsg()."'); window.history.go(-1); </script>\n";
		exit();
	}

	$msg = _AEC_MSG_ITEMS_DELETED;

	mosRedirect( 'index2.php?option=' . $option . '&task=showCoupons' . ( $type ? 'Static' : '' ), $msg );
}

function cancelCoupon( $option, $type )
{
	global $mosConfig_live_site;

	mosRedirect( 'index2.php?option=' . $option . '&task=showCoupons' . ($type ? 'Static' : '' ), _AEC_CMN_EDIT_CANCELLED );
}

function changeCoupon( $cid=null, $state=0, $option, $type )
{
	global $database, $mosConfig_live_site;

	if ( count( $cid ) < 1 ) {
		$action = $state == 1 ? _AEC_CMN_TOPUBLISH : _AEC_CMN_TOUNPUBLISH;
		echo "<script> alert('" . sprintf( html_entity_decode( _AEC_ALERT_SELECT_FIRST_TO ) ), $action . "'); window.history.go(-1);</script>\n";
		exit;
	}

	$total	= count( $cid );
	$cids	= implode( ',', $cid );

	$query = 'UPDATE #__acctexp_coupons'
	. ( $type ? '_static' : '' )
	. ' SET active = \'' . $state . '\''
	. ' WHERE id IN (' . $cids . ')'
	;
	$database->setQuery( $query	);
	$database->query();

	if ( $state ) {
		$msg = $total . ' ' . _AEC_MSG_ITEMS_SUCC_PUBLISHED;
	} else {
		$msg = $total . ' ' . _AEC_MSG_ITEMS_SUCC_UNPUBLISHED;
	}

	mosRedirect( 'index2.php?option=' . $option . '&task=showCoupons' . ( $type ? 'Static' : '' ), $msg );
}

function editCSS( $option ) {
	global $mosConfig_absolute_path;

	$file = $mosConfig_absolute_path . '/components/' . $option . '/style.css';

	if ( $fp = fopen( $file, 'r' ) ) {
		$content = fread( $fp, filesize( $file ) );
		$content = htmlspecialchars( $content );
		General_css::editCSSSource( $content, $option );
	} else {
		mosRedirect( 'index2.php?option='. $option .'&task=editCSS', sprintf( _AEC_MSG_OP_FAILED, $file ) );
	}
}

function saveCSS( $option )
{
	global $mosConfig_absolute_path;

	$filecontent = mosGetParam( $_POST, 'filecontent', '', _MOS_ALLOWHTML );

	if ( !$filecontent ) {
		mosRedirect( 'index2.php?option='. $option .'&task=editCSS', _AEC_MSG_OP_FAILED_EMPTY );
	}

	$file			= $mosConfig_absolute_path .'/components/' . $option . '/style.css';
	$enable_write	= mosGetParam( $_POST, 'enable_write', 0 );
	$oldperms		= fileperms( $file );

	if ( $enable_write ) {
		@chmod( $file, $oldperms | 0222 );
	}

	clearstatcache();
	if ( is_writable( $file ) == false ) {
		mosRedirect( 'index2.php?option='. $option .'&task=editCSS', _AEC_MSG_OP_FAILED_NOT_WRITEABLE );
	}

	if ( $fp = fopen ($file, 'wb') ) {
		fputs( $fp, stripslashes( $filecontent ) );
		fclose( $fp );
		if ( $enable_write ) {
			@chmod( $file, $oldperms );
		} elseif ( mosGetParam( $_POST, 'disable_write', 0 ) ) {
			@chmod( $file, $oldperms & 0777555 );
		}
		mosRedirect( 'index2.php?option='. $option .'&task=editCSS', _AEC_CMN_FILE_SAVED );
	} elseif ( $enable_write ) {
		@chmod($file, $oldperms);
		mosRedirect( 'index2.php?option='. $option .'&task=editCSS', _AEC_MSG_OP_FAILED_NO_WRITE );
	}

}

function cancelCSS ( $option )
{
	mosRedirect( 'index2.php?option='. $option );
}

function invoices( $option )
{
	global $database, $mainframe, $mosConfig_list_limit;

	$limit 		= intval( $mainframe->getUserStateFromRequest( "viewlistlimit", 'limit', $mosConfig_list_limit ) );
	$limitstart = intval( $mainframe->getUserStateFromRequest( "view{$option}limitstart", 'limitstart', 0 ) );
	$search 	= $mainframe->getUserStateFromRequest( "search{$option}_invoices", 'search', '' );

	$where = array();
	if ( $search ) {
		$where[] = 'LOWER(invoice_number) LIKE \'%' . $database->getEscaped( trim( strtolower( $search ) ) ) . '%\'';
	}

	// get the total number of records
	$query = 'SELECT count(*)'
	. ' FROM #__acctexp_invoices'
	;
	$database->setQuery( $query );
	$total = $database->loadResult();
	echo $database->getErrorMsg();

	require_once( $mainframe->getCfg( 'absolute_path' ) . '/administrator/includes/pageNavigation.php' );
	$pageNav = new mosPageNav( $total, $limitstart, $limit );

	// Lets grab the data and fill it in.
	$query = 'SELECT *'
	. ' FROM #__acctexp_invoices'
	. ( count( $where ) ? ' WHERE ' . implode( ' AND ', $where ) : '' )
	. ' ORDER BY created_date DESC'
	. ' LIMIT ' . $pageNav->limitstart . ',' . $pageNav->limit;
	;
	$database->setQuery( $query );
	$rows = $database->loadObjectList();

	if ( $database->getErrorNum() ) {
		echo $database->stderr();
		return false;
	}

	HTML_AcctExp::viewinvoices( $option, $rows, $search, $pageNav );
}

function clearInvoice( $option, $invoice_number, $applyplan, $task )
{
	global $database;

	$invoiceid = AECfetchfromDB::InvoiceIDfromNumber( $invoice_number );

	if ( $invoiceid ) {
		$objInvoice = new Invoice( $database );
		$objInvoice->load( $invoiceid );

		if ( $applyplan ) {
			$objInvoice->pay();
		} else {
			$objInvoice->setTransactionDate();
		}

		if ( strcmp( $task, 'edit' ) == 0) {
			$userid = '&userid=' . $objInvoice->userid;
		} else {
			$userid = '';
		}
	}

	mosRedirect( 'index2.php?option=' . $option . '&task=' . $task . $userid, _AEC_MSG_INVOICE_CLEARED );
}

function cancelInvoice( $option, $invoice_number, $task )
{
	global $database;

	$invoiceid = AECfetchfromDB::InvoiceIDfromNumber( $invoice_number );

	if ( $invoiceid ) {
		$objInvoice = new Invoice( $database );
		$objInvoice->load( $invoiceid );

		$query = 'DELETE FROM #__acctexp_invoices'
		. ' WHERE invoice_number = \'' . $invoice_number . '\''
		;
		$database->setQuery( $query );
		$database->query();

		if ( strcmp( $task, 'edit' ) == 0) {
			$userid = '&userid=' . $objInvoice->userid;
		} else {
			$userid = '';
		}
	}

	mosRedirect( 'index2.php?option=' . $option . '&task=' . $task . $userid, _REMOVED );
}

function history( $option )
{
	global $database, $mainframe, $mosConfig_list_limit;

	$limit 		= intval( $mainframe->getUserStateFromRequest( "viewlistlimit", 'limit', $mosConfig_list_limit ) );
	$limitstart = intval( $mainframe->getUserStateFromRequest( "view{$option}limitstart", 'limitstart', 0 ) );
	$search 	= $mainframe->getUserStateFromRequest( "search{$option}_log_history", 'search', '' );

	$where = array();
	if ( $search ) {
		$where[] = 'LOWER(user_name) LIKE \'%' . $database->getEscaped( trim( strtolower( $search ) ) ) . '%\'';
	}

	// get the total number of records
	$query = 'SELECT count(*)'
	. '  FROM #__acctexp_log_history'
	;
	$database->setQuery( $query );
	$total = $database->loadResult();
	echo $database->getErrorMsg();

	require_once( $mainframe->getCfg( 'absolute_path' ) . '/administrator/includes/pageNavigation.php' );
	$pageNav = new mosPageNav( $total, $limitstart, $limit );

	// Lets grab the data and fill it in.
	$query = 'SELECT *'
	. ' FROM #__acctexp_log_history'
	. ( count( $where ) ? ' WHERE ' . implode( ' AND ', $where ) : '' )
	. ' GROUP BY transaction_date'
	. ' ORDER BY transaction_date DESC'
	. ' LIMIT ' . $pageNav->limitstart . ',' . $pageNav->limit
	;
	$database->setQuery( $query );
	$rows = $database->loadObjectList();

	if ( $database->getErrorNum() ) {
		echo $database->stderr();
		return false;
	}

	HTML_AcctExp::viewhistory( $option, $rows, $search, $pageNav );
}

function eventlog( $option )
{
	global $database, $mainframe, $mosConfig_list_limit;

	$limit 		= intval( $mainframe->getUserStateFromRequest( "viewlistlimit", 'limit', $mosConfig_list_limit ) );
	$limitstart = intval( $mainframe->getUserStateFromRequest( "view{$option}limitstart", 'limitstart', 0 ) );
	$search 	= $mainframe->getUserStateFromRequest( "search{$option}_invoices", 'search', '' );

	$where = array();
	if ( $search ) {
		$where[] = 'LOWER(event) LIKE \'%' . $database->getEscaped( trim( strtolower( $search ) ) ) . '%\'';
	}

	$tags = ( !empty( $_REQUEST['tags'] ) ? $_REQUEST['tags'] : null );

	if ( is_array( $tags ) ) {
		foreach ( $tags as $tag ) {
			$where[] = 'LOWER(tags) LIKE \'%' . trim( strtolower( $tag ) ) . '%\'';
		}
	}

	// get the total number of records
	$query = 'SELECT count(*)'
	. ' FROM #__acctexp_eventlog'
	;
	$database->setQuery( $query );
	$total = $database->loadResult();
	echo $database->getErrorMsg();

	require_once( $mainframe->getCfg( 'absolute_path' ) . '/administrator/includes/pageNavigation.php' );
	$pageNav = new mosPageNav( $total, $limitstart, $limit );

	// Lets grab the data and fill it in.
	$query = 'SELECT *'
	. ' FROM #__acctexp_eventlog'
	. ( count( $where ) ? ' WHERE ' . implode( ' AND ', $where ) : '' )
	. ' ORDER BY id DESC'
	. ' LIMIT ' . $pageNav->limitstart . ',' . $pageNav->limit
	;
	$database->setQuery( $query );
	$rows = $database->loadObjectList();

	if ( $database->getErrorNum() ) {
		echo $database->stderr();
		return false;
	}

	$events = array();
	foreach ( $rows as $id => $row ) {
		$events[$id]->id		= $row->id;
		$events[$id]->datetime	= $row->datetime;
		$events[$id]->short		= $row->short;
		$events[$id]->tags		= implode( ', ', explode( ',', $row->tags ) );
		$events[$id]->event		= $row->event;

		$params = array();

		if ( $row->params ) {
			foreach ( explode( "\n", $row->params ) as $param ) {
				$p = explode( '=', $param);
				if ( !($p[0] == '' ) ) {
					$params[] = $p[0] . '(' . $p[1] . ')';
				}
			}
		}
		$events[$id]->params = implode( ', ', $params );
	}

	HTML_AcctExp::eventlog( $option, $events, $search, $pageNav );
}

function migrate( $option )
{
	global $database;

	$query = 'SELECT id'
	. ' FROM #__users'
	. ' WHERE LOWER( usertype ) != \'superadministrator\''
	. ' OR LOWER( usertype ) != \'super administrator\''
	;
	$database->setQuery( $query );
	$rows = $database->loadResultArray();

	foreach ( $rows as $userid ) {

		$metaUser = new metaUser($userid);
		if ( $metaUser->hasSubscription ) {
			if ($metaUser->objSubscription->plan == 1) {
				$metaUser->instantGIDchange(31);
			}
		}

/*
		$mosUser = new mosUser( $database );
		$mosUser->load( $userid );


		// Fixing mosLock broken user accounts
		// it sometimes seems to forget setting the usertype
		if ( $mosUser->usertype == '' ) {
			$mosUser->usertype = 'Registered';
			$mosUser->check();
			$mosUser->store();
		}

		// If subscription control was previously done by blocking,
		// unblock users
		if ( $mosUser->block == 1 ) {
			$mosUser->block = 0;
			$mosUser->check();
			$mosUser->store();
		}

		// Create dummy active subscription entry
		$subscriptionid	= AECfetchfromDB::SubscriptionIDfromUserID( $userid );
		$expirationid	= AECfetchfromDB::ExpirationIDfromUserID( $userid );

		$subscriptionHandler = new mosSubscription( $database );

		if ( !$subscriptionid ) {
			$subscriptionHandler->load( 0 );

			$subscriptionHandler->plan			= 1;
			$subscriptionHandler->signup_date	= $mosUser->registerDate;

			$subscriptionHandler->check();
			$subscriptionHandler->store();
		}

		// Set Expiration date one year after registration
		$expirationHandler = new mosAcctExp( $database );

		if ( !$expirationid ) {
			$expirationHandler->load(0);
			$expirationHandler->userid = $userid;

			$timestamp			= strtotime( $mosUser->registerDate );
			$registrationdate	= date( 'Y-m-d H:i:s', $timestamp );

			$expirationHandler->expiration = $registrationdate;
			$expirationHandler->setexpiration( 'Y', 1, 1 );

			$expirationHandler->check();
			$expirationHandler->store();
		}*/
	}
		exit();
// Fix JACLplus associations after uninstall/reinstall
$query = 'SELECT id'
. ' FROM #__users'
. ' WHERE gid = \'31\''
;
$database->setQuery( $query );
$rows = $database->loadResultArray();

foreach ( $rows as $userid ){
	$user = new mosUser($database);
	$user->load(1);
	print_r($user);
}


}

function quicklookup( $option )
{
	global $database, $mosConfig_live_site;

	$search	= mosGetParam( $_REQUEST, 'search', 0 );
	$search = $database->getEscaped( trim( strtolower( $search ) ) );

	$userid = 0;

	// Try to find this as a username or name
	$query = 'SELECT count(*)'
	. ' FROM #__users'
	. ' WHERE username LIKE \'%' . $search . '%\' OR name LIKE \'%' . $search . '%\''
	;
	$database->setQuery( $query );
	$request = $database->loadResult();

	$query = 'SELECT id'
	. ' FROM #__users'
	. ' WHERE username LIKE \'%' . $search . '%\' OR name LIKE \'%' . $search . '%\''
	;
	$database->setQuery( $query );

	if ( $request > 1 ) {
		$users = $database->loadResultArray();

		$return = array();
		foreach ( $users as $user ) {
			$mosUser = new mosUser( $database );
			$mosUser->load( $user );
			$userlink = '<a href="';
			$userlink .= $mosConfig_live_site . '/administrator/index2.php?option=com_acctexp&amp;task=edit&amp;userid=' . $mosUser->id;
			$userlink .= '">';
			$userlink .= $mosUser->name . ' (' . $mosUser->username . ')';
			$userlink .= '</a>';

			$return[] = $userlink;
		}

		return implode( ', ', $return );
	}

	$userid = $database->loadResult();

	// If its not that, how about the user email?
	if ( !$userid ) {
		$query = 'SELECT id'
		. ' FROM #__users'
		. ' WHERE email = \'' . $search . '\''
		;
		$database->setQuery( $query );

		$userid = $database->loadResult();
	} else {
		return $userid;
	}

	// Or maybe its an invoice number?
	if ( !$userid ) {
		$query = 'SELECT userid'
		. ' FROM #__acctexp_invoices'
		. ' WHERE invoice_number = \'' . $search . '\''
		;
		$database->setQuery( $query );

		$userid = $database->loadResult();
	} else {
		return $userid;
	}

	// Try to find this as a username or name
	$query = 'SELECT id'
	. ' FROM #__users'
	. ' WHERE id LIKE \'' . $search . '\''
	;
	$database->setQuery( $query );

	return $database->loadResult();
}

function hackcorefile( $option, $filename, $check_hack, $undohack )
{
	global $mosConfig_absolute_path, $database;
	global $mosConfig_debug;

	if ( !defined( '_AEC_LANG_INCLUDED_MI' ) ) {
		global $mainframe;

		$langPathMI = $mainframe->getCfg( 'absolute_path' ) . '/components/com_acctexp/micro_integration/language/';
		if ( file_exists( $langPathMI . $mainframe->getCfg( 'lang' ) . '.php' ) ) {
			include_once( $langPathMI . $mainframe->getCfg( 'lang' ) . '.php' );
		} else {
			include_once( $langPathMI . 'english.php' );
		}
	}

	$cmsname = strtolower( GeneralInfoRequester::getCMSName() );

	if ( strcmp( $cmsname, 'joomla15' ) === 0 ) {
		$cmsname = 'joomla';
		$v15 = true;
	} else {
		$v15 = false;
	}

	$aec_hack_start				= "// AEC HACK %s START" . "\n";
	$aec_hack_end				= "// AEC HACK %s END" . "\n";
	$aec_condition_start		= 'if (file_exists( $mosConfig_absolute_path . "/components/com_acctexp/acctexp.class.php")) {' . "\n";
	$aec_condition_end			= '}' . "\n";
	$aec_include_class			= 'include_once($mosConfig_absolute_path . "/components/com_acctexp/acctexp.class.php");' . "\n";
	$aec_verification_check		= "AECToolBox::VerifyUsername( %s );" . "\n";
	$aec_userchange_clause		= '$mih = new microIntegrationHandler();' . "\n" . '$mih->userchange($row, $_POST, \'%s\');' . "\n";
	$aec_userchange_clause15	= '$mih = new microIntegrationHandler();' . "\n" . '$mih->userchange($userid, $post, \'%s\');' . "\n";
	$aec_userregchange_clause15	= '$mih = new microIntegrationHandler();' . "\n" . '$mih->userchange($user, $post, \'%s\');' . "\n";
	$aec_global_call			= 'global $mosConfig_live_site, $mosConfig_absolute_path;' . "\n";
	$aec_redirect_notallowed	= 'mosRedirect( $mosConfig_live_site . "/index.php?option=com_acctexp&task=NotAllowed" );' . "\n";
	$aec_redirect_subscribe		= 'mosRedirect( $mosConfig_live_site . "/index.php?option=com_acctexp&task=subscribe" );' . "\n";

	$aec_normal_hack = $aec_hack_start
	. $aec_global_call
	. $aec_condition_start
	. $aec_redirect_notallowed
	. $aec_condition_end
	. $aec_hack_end;

	$aec_jhack1 = $aec_hack_start
					. 'function mosNotAuth($override=false) {' . "\n"
					. $aec_global_call
					. $aec_condition_start
					. 'if (!$override) {' . "\n"
					. $aec_redirect_notallowed
					. $aec_condition_end
					. $aec_condition_end
					. $aec_hack_end;

	$aec_jhack2 = $aec_hack_start
					. $aec_global_call
					. $aec_condition_start
					. $aec_redirect_notallowed
					. $aec_condition_end
					. $aec_hack_end;

	$aec_jhack3 = $aec_hack_start
					. $aec_global_call
					. $aec_condition_start
					. $aec_include_class
					. sprintf( $aec_verification_check, '$row->username' )
					. $aec_condition_end
					. $aec_hack_end;

	$aec_cbmhack =	$aec_hack_start
					. "mosNotAuth(true);" . "\n"
					. $aec_hack_end;

	$aec_uchangehack =	$aec_hack_start
						. $aec_global_call
						. $aec_condition_start
						. $aec_include_class
						. $aec_userchange_clause
						. $aec_condition_end
						. $aec_hack_end;

	$aec_uchangehack15 =	$aec_hack_start
						. $aec_global_call
						. $aec_condition_start
						. $aec_include_class
						. $aec_userregchange_clause15
						. $aec_condition_end
						. $aec_hack_end;

	$aec_uchangereghack15 =	$aec_hack_start
						. $aec_global_call
						. $aec_condition_start
						. $aec_include_class
						. $aec_userchange_clause15
						. $aec_condition_end
						. $aec_hack_end;

	$aec_rhackbefore =	$aec_hack_start
						. $aec_global_call
						. $aec_condition_start
						. 'if (!isset($_POST[\'planid\'])) {' . "\n"
						. $aec_include_class
						. 'mosRedirect($mosConfig_live_site . "/index.php?option=com_acctexp&amp;task=subscribe");' . "\n"
						. $aec_condition_end
						. $aec_condition_end
						. $aec_hack_end;

	$aec_rhackbefore_fix = str_replace("planid", "usage", $aec_rhackbefore);

	$aec_rhackbefore2 =	$aec_hack_start
						. $aec_global_call . 'global $mainframe;' . "\n"
						. $aec_condition_start
						. 'if (!isset($_POST[\'usage\'])) {' . "\n"
						. $aec_include_class
						. 'mosRedirect($mosConfig_live_site . "/index.php?option=com_acctexp&amp;task=subscribe");' . "\n"
						. $aec_condition_end
						. $aec_condition_end
						. $aec_hack_end;

	$aec_optionhack =	$aec_hack_start
						. $aec_global_call
						. $aec_condition_start
						. '$option = "com_acctexp";' . "\n"
						. $aec_condition_end
						. $aec_hack_end;

	$aec_regvarshack =	'<?php' . "\n"
						. $aec_hack_start
						. $aec_global_call
						. $aec_condition_start
						. '?>' . "\n"
						. '<input type="hidden" name="planid" value="<?php echo $_POST[\'planid\'];?>" />' . "\n"
						. '<input type="hidden" name="processor" value="<?php echo $_POST[\'processor\'];?>" />' . "\n"
						. '<?php' . "\n"
						. $aec_condition_end
						. $aec_hack_end
						. '?>' . "\n";

	$aec_regvarshack_fix = str_replace( 'planid', 'usage', $aec_regvarshack);

	$aec_regredirect = $aec_hack_start
					. $aec_global_call
					. $aec_condition_start
					. $aec_redirect_subscribe
					. $aec_condition_end
					. $aec_hack_end;

	// menu entry
	$n = 'menuentry';
	$hacks[$n]['name'] =	_AEC_HACKS_MENU_ENTRY;
	$hacks[$n]['desc'] =	_AEC_HACKS_MENU_ENTRY_DESC;
	$hacks[$n]['type'] =	'menuentry';

	if ( !$v15 ) {
		// general section - checks core files
		// joomla-/mambo.php
		$n = 'joomlaphp';
		$hacks[$n]['name']				=	$cmsname . '.php';
		$hacks[$n]['desc']				=	_AEC_HACKS_LEGACY;
		$hacks[$n]['type']				=	'file';
		$hacks[$n]['filename']			=	$mosConfig_absolute_path . '/includes/' . $cmsname . '.php';
		$hacks[$n]['read']				=	'echo _NOT_AUTH;';
		$hacks[$n]['insert']			=	sprintf($aec_normal_hack, $n, $n) .	$hacks[$n]['read'];
		$hacks[$n]['legacy']			=	1;
		$hacks[$n]['important']			=	1;
	}

	if ( ( strcmp($cmsname, "joomla") === 0 ) && !$v15 ) {
		$n = 'joomlaphp1';
		$hacks[$n]['name']			=	$cmsname . '.php ' . _AEC_HACK_HACK . ' #1';
		$hacks[$n]['desc']			=	_AEC_HACKS_NOTAUTH;
		$hacks[$n]['uncondition']	=	'joomlaphp';
		$hacks[$n]['type']			=	'file';
		$hacks[$n]['filename']		=	$mosConfig_absolute_path . '/includes/' . $cmsname . '.php';
		$hacks[$n]['read']			=	"function mosNotAuth() {";
		$hacks[$n]['insert']		=	sprintf( $aec_jhack1, $n, $n );

		$n = 'joomlaphp2';
		$hacks[$n]['name']			=	$cmsname . '.php ' . _AEC_HACK_HACK . ' #2';
		$hacks[$n]['desc']			=	_AEC_HACKS_NOTAUTH;
		$hacks[$n]['uncondition']	=	'joomlaphp';
		$hacks[$n]['type']			=	'file';
		$hacks[$n]['filename']		=	$mosConfig_absolute_path . '/includes/' . $cmsname . '.php';
		$hacks[$n]['read']			=	'function notAllowed( $name ) {';
		$hacks[$n]['insert']		=	$hacks[$n]['read'] . "\n" . sprintf( $aec_jhack2, $n, $n );
	}

	if ( !$v15 ) {
		$n = 'joomlaphp3';
		$hacks[$n]['name']				=	$cmsname . '.php ' . _AEC_HACK_HACK . ' #3';
		$hacks[$n]['desc']				=	_AEC_HACKS_LEGACY;
		$hacks[$n]['uncondition']		=	'joomlaphp';
		$hacks[$n]['type']				=	'file';
		$hacks[$n]['filename']			=	$mosConfig_absolute_path . '/includes/' . $cmsname . '.php';
		$hacks[$n]['read']				=	'if ($row->block == 1) {';
		$hacks[$n]['insert']			=	sprintf( $aec_jhack3, $n, $n ) . "\n" . $hacks[$n]['read'];
		$hacks[$n]['legacy']			=	1;
	}

	$n = 'joomlaphp4';
	$hacks[$n]['name']				=	$v15 ? ( 'authentication.php' ) : ( $cmsname . '.php ' . _AEC_HACK_HACK . ' #4' );
	$hacks[$n]['desc']				=	_AEC_HACKS_SUB_REQUIRED;
	$hacks[$n]['uncondition']		=	'joomlaphp';
	$hacks[$n]['type']				=	'file';

	switch( $cmsname ) {
		case 'joomla':
			$hacks[$n]['filename']	=	$v15 ? ( $mosConfig_absolute_path . '/libraries/joomla/user/authentication.php' ) : ( $mosConfig_absolute_path . '/includes/' . $cmsname . '.php' );
			$hacks[$n]['read'] 		=	$v15 ? 'if(empty($response->username)) {' : '// initialize session data';
			break;

		case 'mambo':
			$hacks[$n]['filename']	=	$mosConfig_absolute_path . '/includes/authenticator.php';
			$hacks[$n]['read']		=	'// fudge the group stuff';
			break;
	}

	$hacks[$n]['insert']			=	sprintf($aec_jhack3, $n, $n) . "\n" . $hacks[$n]['read'];
	$hacks[$n]['important']			=	1;

	// registration.php
	if ( !$v15 ) {
		$message = ( strcmp( $cmsname, 'mambo' ) === 0 ) ? 'Direct Access to this location is not allowed.' : 'Restricted access';
		$n = 'registrationphp';
		$hacks[$n]['name']				=	'registration.php';
		$hacks[$n]['desc']				=	_AEC_HACKS_LEGACY;
		$hacks[$n]['type']				=	'file';
		$hacks[$n]['filename']			=	$mosConfig_absolute_path . '/components/com_registration/registration.php';
		$hacks[$n]['read']				=	'defined( \'_VALID_MOS\' ) or die( \'' . $message . '\' );';
		$hacks[$n]['insert']			=	$hacks[$n]['read'] . "\n" . sprintf( $aec_normal_hack, $n, $n );
		$hacks[$n]['legacy']			=	1;
		$hacks[$n]['important']			=	1;
	}

	if ( GeneralInfoRequester::detect_component( 'UHP2' ) ) {
		$n = 'uhp2menuentry';
		$hacks[$n]['name']			=	_AEC_HACKS_UHP2;
		$hacks[$n]['desc']			=	_AEC_HACKS_UHP2_DESC;
		$hacks[$n]['uncondition']	=	'uhp2managephp';
		$hacks[$n]['type']			=	'file';
		$hacks[$n]['filename']		=	$mosConfig_absolute_path . '/modules/mod_uhp2_manage.php';
		$hacks[$n]['read']			=	'<?php echo "$settings"; ?></a>';
		$hacks[$n]['insert']		=	sprintf( $hacks[$n]['read'] . "\n</li>\n<?php " . $aec_hack_start . '?>'
		. '<li class="latest<?php echo $moduleclass_sfx; ?>">'
		. '<a href="index.php?option=com_acctexp&task=subscriptionDetails" class="latest<?php echo $moduleclass_sfx; ?>">'
		. _AEC_SPEC_MENU_ENTRY . '</a>'."\n<?php ".$aec_hack_end."?>", $n, $n );
	}

	if ( GeneralInfoRequester::detect_component( 'CB' ) ) {
		$n = 'comprofilerphp2';
		$hacks[$n]['name']			=	'comprofiler.php ' . _AEC_HACK_HACK . ' #2';
		$hacks[$n]['desc']			=	_AEC_HACKS_CB2;
		$hacks[$n]['type']			=	'file';
		$hacks[$n]['filename']		=	$mosConfig_absolute_path . '/components/com_comprofiler/comprofiler.php';
		$hacks[$n]['read']			=	'if ($regErrorMSG===null) {';
		$hacks[$n]['insert']		=	sprintf($aec_optionhack, $n, $n) . "\n" . $hacks[$n]['read'];

		if ( !$v15 ) {
			$n = 'comprofilerphp3';
			$hacks[$n]['name']			=	'comprofiler.php ' . _AEC_HACK_HACK . ' #3';
			$hacks[$n]['desc']			=	_AEC_HACKS_LEGACY;
			$hacks[$n]['type']			=	'file';
			$hacks[$n]['condition']		=	'comprofilerphp2';
			$hacks[$n]['filename']		=	$mosConfig_absolute_path . '/components/com_comprofiler/comprofiler.php';
			$hacks[$n]['read']			=	'HTML_comprofiler::registerForm';
			$hacks[$n]['insert']		=	sprintf($aec_rhackbefore, $n, $n) . "\n" . $hacks[$n]['read'];
			$hacks[$n]['legacy']		=	1;
		}

		$n = 'comprofilerphp6';
		$hacks[$n]['name']			=	'comprofiler.php ' . _AEC_HACK_HACK . ' #6';
		$hacks[$n]['desc']			=	_AEC_HACKS_CB6;
		$hacks[$n]['type']			=	'file';
		$hacks[$n]['condition']		=	'comprofilerphp2';
		$hacks[$n]['uncondition']	=	'comprofilerphp3';
		$hacks[$n]['filename']		=	$mosConfig_absolute_path . '/components/com_comprofiler/comprofiler.php';
		$hacks[$n]['read']			=	'HTML_comprofiler::registerForm';
		$hacks[$n]['insert']		=	sprintf($aec_rhackbefore_fix, $n, $n) . "\n" . $hacks[$n]['read'];

		if ( !$v15 ) {
			$n = 'comprofilerhtml';
			$hacks[$n]['name']			=	'comprofiler.html.php ' . _AEC_HACK_HACK . ' #1';
			$hacks[$n]['desc']			=	_AEC_HACKS_LEGACY;
			$hacks[$n]['type']			=	'file';
			$hacks[$n]['condition']		=	'comprofilerphp3';
			$hacks[$n]['filename']		=	$mosConfig_absolute_path . '/components/com_comprofiler/comprofiler.html.php';
			$hacks[$n]['read']			=	'<input type="hidden" name="task" value="saveregisters" />';
			$hacks[$n]['insert']		=	$hacks[$n]['read'] . "\n" . sprintf($aec_regvarshack, $n, $n);
			$hacks[$n]['legacy']		=	1;
		}

		$n = 'comprofilerhtml2';
		$hacks[$n]['name']			=	'comprofiler.html.php ' . _AEC_HACK_HACK . ' #2';
		$hacks[$n]['desc']			=	_AEC_HACKS_CB_HTML2;
		$hacks[$n]['type']			=	'file';
		$hacks[$n]['condition']		=	'comprofilerphp6';
		$hacks[$n]['uncondition']	=	'comprofilerhtml';
		$hacks[$n]['filename']		=	$mosConfig_absolute_path . '/components/com_comprofiler/comprofiler.html.php';
		$hacks[$n]['read']			=	'<input type="hidden" name="task" value="saveregisters" />';
		$hacks[$n]['insert']		=	$hacks[$n]['read'] . "\n" . sprintf($aec_regvarshack_fix, $n, $n);
		$hacks[$n]['important']		=	1;

	} elseif ( GeneralInfoRequester::detect_component( 'CBE' ) ) {
		$n = 'comprofilerphp2';
		$hacks[$n]['name']			=	'comprofiler.php ' . _AEC_HACK_HACK . ' #2';
		$hacks[$n]['desc']			=	_AEC_HACKS_CB2;
		$hacks[$n]['type']			=	'file';
		$hacks[$n]['filename']		=	$mosConfig_absolute_path . '/components/com_comprofiler/comprofiler.php';
		$hacks[$n]['read']			=	'$rowFieldValues=array();';
		$hacks[$n]['insert']		=	sprintf($aec_optionhack, $n, $n) . "\n" . $hacks[$n]['read'];

		if ( !$v15 ) {
			$n = 'comprofilerphp3';
			$hacks[$n]['name']			=	'comprofiler.php ' . _AEC_HACK_HACK . ' #3';
			$hacks[$n]['desc']			=	_AEC_HACKS_LEGACY;
			$hacks[$n]['type']			=	'file';
			$hacks[$n]['condition']		=	'comprofilerphp2';
			$hacks[$n]['filename']		=	$mosConfig_absolute_path . '/components/com_comprofiler/comprofiler.php';
			$hacks[$n]['read']			=	'HTML_comprofiler::registerForm';
			$hacks[$n]['insert']		=	sprintf($aec_rhackbefore, $n, $n) . "\n" . $hacks[$n]['read'];
			$hacks[$n]['legacy']		=	1;
		}

		$n = 'comprofilerphp6';
		$hacks[$n]['name']			=	'comprofiler.php ' . _AEC_HACK_HACK . ' #6';
		$hacks[$n]['desc']			=	_AEC_HACKS_CB6;
		$hacks[$n]['type']			=	'file';
		$hacks[$n]['condition']		=	'comprofilerphp2';
		$hacks[$n]['filename']		=	$mosConfig_absolute_path . '/components/com_comprofiler/comprofiler.php';
		$hacks[$n]['read']			=	'HTML_comprofiler::registerForm';
		$hacks[$n]['insert']		=	sprintf($aec_rhackbefore2, $n, $n) . "\n" . $hacks[$n]['read'];

		if ( !$v15 ) {
			$n = 'comprofilerhtml';
			$hacks[$n]['name']			=	'comprofiler.html.php ' . _AEC_HACK_HACK . ' #1';
			$hacks[$n]['desc']			=	_AEC_HACKS_LEGACY;
			$hacks[$n]['type']			=	'file';
			$hacks[$n]['condition']		=	'comprofilerphp6';
			$hacks[$n]['filename']		=	$mosConfig_absolute_path . '/components/com_comprofiler/comprofiler.html.php';
			$hacks[$n]['read']			=	'<input type="hidden" name="task" value="saveRegistration" />';
			$hacks[$n]['insert']		=	$hacks[$n]['read'] . "\n" . sprintf($aec_regvarshack, $n, $n);
			$hacks[$n]['legacy']		=	1;
		}

		$n = 'comprofilerhtml2';
		$hacks[$n]['name']			=	'comprofiler.html.php ' . _AEC_HACK_HACK . ' #2';
		$hacks[$n]['desc']			=	_AEC_HACKS_CB_HTML2;
		$hacks[$n]['type']			=	'file';
		$hacks[$n]['condition']		=	'comprofilerphp6';
		$hacks[$n]['uncondition']	=	'comprofilerhtml';
		$hacks[$n]['filename']		=	$mosConfig_absolute_path . '/components/com_comprofiler/comprofiler.html.php';
		$hacks[$n]['read']			=	'<input type="hidden" name="task" value="saveRegistration" />';
		$hacks[$n]['insert']		=	$hacks[$n]['read'] . "\n" . sprintf($aec_regvarshack_fix, $n, $n);
		$hacks[$n]['important']		=	1;
	} else {
		$n = 'registrationphp2';
		$hacks[$n]['name']			=	'registration.php ' . _AEC_HACK_HACK . ' #2';
		$hacks[$n]['desc']			=	_AEC_HACKS_REG2;
		$hacks[$n]['type']			=	'file';
		$hacks[$n]['filename']		=	$v15 ? ( $mosConfig_absolute_path . '/components/com_user/controller.php' ) : ( $mosConfig_absolute_path . '/components/com_registration/registration.php' );
		$hacks[$n]['read']			=	$v15 ? ( 'JRequest::setVar(\'view\', \'register\');' ) : ( '$mainframe->SetPageTitle(_REGISTER_TITLE);' );
		$hacks[$n]['insert']		=	$hacks[$n]['read'] . "\n" . sprintf( $aec_optionhack, $n, $n );

		if ( !$v15 ) {
			$n = 'registrationphp3';
			$hacks[$n]['name']			=	'registration.php ' . _AEC_HACK_HACK . ' #3';
			$hacks[$n]['desc']			=	_AEC_HACKS_LEGACY;
			$hacks[$n]['type']			=	'file';
			$hacks[$n]['condition']		=	'registrationphp3';
			$hacks[$n]['filename']		=	$mosConfig_absolute_path . '/components/com_registration/registration.php';
			$hacks[$n]['read']			=	'HTML_registration::registerForm';
			$hacks[$n]['insert']		=	sprintf($aec_rhackbefore, $n, $n) . "\n" . $hacks[$n]['read'];
			$hacks[$n]['legacy']		=	1;
		}

		if ( !$v15 ) {
		$n = 'registrationhtml';
			$hacks[$n]['name']			=	'registration.html.php ' . _AEC_HACK_HACK . ' #1';
			$hacks[$n]['desc']			=	_AEC_HACKS_LEGACY;
			$hacks[$n]['type']			=	'file';
			$hacks[$n]['condition']		=	'registrationhtml';
			$hacks[$n]['filename']		=	$mosConfig_absolute_path . '/components/com_registration/registration.html.php';
			$hacks[$n]['read']			=	'<input type="hidden" name="task" value="saveRegistration" />';
			$hacks[$n]['insert']		=	$hacks[$n]['read'] . "\n" . sprintf($aec_regvarshack, $n, $n);
			$hacks[$n]['legacy']		=	1;
		}

		/*$n = 'registrationhtml2';
		$hacks[$n]['name']			=	'registration.html.php ' . _AEC_HACK_HACK . ' #2';
		$hacks[$n]['desc']			=	_AEC_HACKS_REG4;
		$hacks[$n]['type']			=	'file';
		$hacks[$n]['uncondition']	=	'registrationhtml';
		$hacks[$n]['condition']		=	'registrationphp2';
		$hacks[$n]['filename']		=	$v15 ? ( $mosConfig_absolute_path . '/components/com_user/views/register/tmpl/default.php' ) : ( $mosConfig_absolute_path . '/components/com_registration/registration.html.php' );
		$hacks[$n]['read']			=	$v15 ? ( '<input type="hidden" name="task" value="register_save" />' ) : ( '<input type="hidden" name="task" value="saveRegistration" />' );
		$hacks[$n]['insert']		=	$hacks[$n]['read'] . "\n" . sprintf($aec_regvarshack_fix, $n, $n);
		$hacks[$n]['important']		=	1;*/

		if ( !$v15 ) {
			$n = 'registrationphp5';
			$hacks[$n]['name']			=	'registration.php ' . _AEC_HACK_HACK . ' #5';
			$hacks[$n]['desc']			=	_AEC_HACKS_LEGACY;
			$hacks[$n]['type']			=	'file';
			$hacks[$n]['filename']		=	$mosConfig_absolute_path . '/components/com_registration/registration.php';
			$hacks[$n]['read']			=	'// no direct access';
			$hacks[$n]['insert']		=	$hacks[$n]['read'] . "\n" . sprintf($aec_regredirect, $n, $n);
			$hacks[$n]['legacy']		=	1;
		}

		$n = 'registrationphp6';
		$hacks[$n]['name']			=	$v15 ? 'user.php' : ( 'registration.php ' . _AEC_HACK_HACK . ' #6' );
		$hacks[$n]['desc']			=	_AEC_HACKS_REG5;
		$hacks[$n]['type']			=	'file';
		$hacks[$n]['uncondition']	=	'registrationphp5';
		$hacks[$n]['filename']		=	$v15 ? ( $mosConfig_absolute_path . '/components/com_user/controller.php' ) : ( $mosConfig_absolute_path . '/components/com_registration/registration.php' );
		$hacks[$n]['read']			=	$v15 ? ( 'JRequest::setVar(\'view\', \'register\');' ) : ( 'case \'register\':' );
		$hacks[$n]['insert']		=	$hacks[$n]['read'] . "\n" . sprintf($aec_regredirect, $n, $n);
	}

	if ( GeneralInfoRequester::detect_component( 'CB' ) || GeneralInfoRequester::detect_component( 'CBE' ) ) {
		$n = 'comprofilerphp4';
		$hacks[$n]['name']			=	'comprofiler.php ' . _AEC_HACK_HACK . ' #4';
		$hacks[$n]['desc']			=	_AEC_HACKS_MI1;
		$hacks[$n]['type']			=	'file';
		$hacks[$n]['filename']		=	$mosConfig_absolute_path . "/components/com_comprofiler/comprofiler.php";
		$hacks[$n]['read']			=	'$_PLUGINS->trigger( \'onAfterUserRegistrationMailsSent\',';
		$hacks[$n]['insert']		=	sprintf($aec_uchangehack, $n, "user", $n) . "\n" . $hacks[$n]['read'];
		$hacks[$n]['legacy']		=	1;

		$n = 'comprofilerphp5';
		$hacks[$n]['name']			=	'comprofiler.php ' . _AEC_HACK_HACK . ' #5';
		$hacks[$n]['desc']			=	_AEC_HACKS_MI2;
		$hacks[$n]['type']			=	'file';
		$hacks[$n]['filename']		=	$mosConfig_absolute_path . "/components/com_comprofiler/comprofiler.php";
		$hacks[$n]['read']			=	'$_PLUGINS->trigger( \'onAfterUserUpdate\', array($row, $rowExtras, true));';
		$hacks[$n]['insert']		=	$hacks[$n]['read'] . "\n" . sprintf($aec_uchangehack, $n, "registration",$n);
		$hacks[$n]['legacy']		=	1;

		$n = 'comprofilerphp7';
		$hacks[$n]['name']			=	'comprofiler.php ' . _AEC_HACK_HACK . ' #7';
		$hacks[$n]['desc']			=	_AEC_HACKS_MI1;
		$hacks[$n]['type']			=	'file';
		$hacks[$n]['uncondition']	=	'comprofilerphp4';
		$hacks[$n]['filename']		=	$mosConfig_absolute_path . '/components/com_comprofiler/comprofiler.php';
		$hacks[$n]['read']			=	'$_PLUGINS->trigger( \'onAfterUserRegistrationMailsSent\',';
		$hacks[$n]['insert']		=	sprintf( $aec_uchangehack, $n, 'registration', $n ) . "\n" . $hacks[$n]['read'];

		$n = 'comprofilerphp8';
		$hacks[$n]['name']			=	'comprofiler.php ' . _AEC_HACK_HACK . ' #8';
		$hacks[$n]['desc']			=	_AEC_HACKS_MI1;
		$hacks[$n]['type']			=	'file';
		$hacks[$n]['uncondition']	=	'comprofilerphp5';
		$hacks[$n]['filename']		=	$mosConfig_absolute_path . '/components/com_comprofiler/comprofiler.php';
		$hacks[$n]['read']			=	'$_PLUGINS->trigger( \'onAfterUserUpdate\', array($row, $rowExtras, true));';
		$hacks[$n]['insert']		=	$hacks[$n]['read'] . "\n" . sprintf( $aec_uchangehack, $n, 'user', $n );

		// TODO: Check and rework
		/*
		$n = 'admincomprofilerphp1';
		$hacks[$n]['name']			=	'admin.user.php';
		$hacks[$n]['desc']			=	_AEC_HACKS_MI1;
		$hacks[$n]['type']			=	'file';
		$hacks[$n]['filename']		=	$mosConfig_absolute_path . 'administrator/components/com_users/admin.users.php';
		$hacks[$n]['read']			=	'$row->checkin();';
		$hacks[$n]['insert']		=	sprintf( $aec_uchangehack, $n, 'adminuser', $n ) . "\n" . $hacks[$n]['read'];
		*/
	} else {
		$n = 'userphp';
		$hacks[$n]['name']			=	'user.php';
		$hacks[$n]['desc']			=	_AEC_HACKS_MI1;
		$hacks[$n]['type']			=	'file';
		$hacks[$n]['filename']		=	$v15 ? ( $mosConfig_absolute_path . '/components/com_user/controller.php' ) : ( $mosConfig_absolute_path . '/components/com_user/user.php' );
		$hacks[$n]['read']			=	$v15 ? ( 'if ($model->store($post)) {' ) : ( '// check if username has been changed' );
		$hacks[$n]['insert']		=	sprintf( ( $v15 ? $aec_uchangehack15 : $aec_uchangehack ), $n, "user", $n ) . "\n" . $hacks[$n]['read'];

		$n = 'registrationphp1';
		$hacks[$n]['name']			=	'registration.php ' . _AEC_HACK_HACK . ' #1';
		$hacks[$n]['desc']			=	_AEC_HACKS_MI2;
		$hacks[$n]['type']			=	'file';
		$hacks[$n]['filename']		=	$v15 ? ( $mosConfig_absolute_path . '/components/com_user/controller.php' ) : ( $mosConfig_absolute_path . '/components/com_registration/registration.php' );
		$hacks[$n]['read']			=	$v15 ? 'if ( !$user->save() ) ' : '$row->checkin();';
		$hacks[$n]['insert']		=	$hacks[$n]['read'] . "\n" . sprintf( ( $v15 ? $aec_uchangereghack15 : $aec_uchangehack ), $n, "registration", $n );
	}

	$n = 'adminuserphp';
	$hacks[$n]['name']			=	'admin.user.php';
	$hacks[$n]['desc']			=	_AEC_HACKS_MI3;
	$hacks[$n]['type']			=	'file';
	$hacks[$n]['filename']		=	$v15 ? ( $mosConfig_absolute_path . '/administrator/components/com_users/controller.php' ) : ( $mosConfig_absolute_path . '/administrator/components/com_users/admin.users.php' );
	$hacks[$n]['read']			=	$v15 ? 'if (!$user->save())' : '$row->checkin();';
	$hacks[$n]['insert']		=	sprintf( ( $v15 ? $aec_uchangehack15 : $aec_uchangehack ), $n, 'adminuser', $n ) . "\n" . $hacks[$n]['read'];

	if ( !$v15 ) {
		if ( GeneralInfoRequester::detect_component( 'CB' ) || GeneralInfoRequester::detect_component( 'CBE' ) ) {
			$n = 'comprofilerphp';
			$hacks[$n]['name']			=	"comprofiler.php";
			$hacks[$n]['desc']			=	_AEC_HACKS_LEGACY;
			$hacks[$n]['type']			=	'file';
			$hacks[$n]['filename']		=	$mosConfig_absolute_path . '/components/com_comprofiler/comprofiler.php';
			$hacks[$n]['read']			=	'case "registers":';
			$hacks[$n]['insert']		=	$hacks[$n]['read'] . "\n" . sprintf($aec_normal_hack, $n, $n);
			$hacks[$n]['legacy']		=	1;
			$hacks[$n]['important']		=	1;
		}
	}

	if ( GeneralInfoRequester::detect_component( 'CBM' ) ) {
		$n = 'comprofilermoderator';
		$hacks[$n]['name']			=	'comprofilermoderator.php';
		$hacks[$n]['desc']			=	_AEC_HACKS_CBM;
		$hacks[$n]['type']			=	'file';
		$hacks[$n]['filename']		=	$mosConfig_absolute_path . '/modules/mod_comprofilermoderator.php';
		$hacks[$n]['read']			=	'mosNotAuth();';
		$hacks[$n]['insert']		=	sprintf( $aec_cbmhack, $n, $n );
	}

	$mih = new microIntegrationHandler();
	$new_hacks = $mih->getHacks();

	if ( is_array( $new_hacks ) ) {
		$hacks = array_merge( $hacks, $new_hacks );
	}

	// Receive the status for the hacks
	foreach ( $hacks as $name => $hack ) {

		// mic: initialize var and set to 0
		$hacks[$name]['status'] = 0;

		if ( $hack['type'] ) {
			switch( $hack['type'] ) {
				case 'file':
					// mic: fix if CMS is not Joomla or Mambo
					if ( $hack['filename'] != 'UNKNOWN' ) {
						$originalFileHandle = fopen( $hack['filename'], 'r' ) or die ( "Cannot open $originalFile<br>" );
						$oldData			= fread( $originalFileHandle, filesize($hack['filename'] ) );
						fclose( $originalFileHandle );

						if ( strpos( $oldData, 'AEC HACK START' ) || strpos( $oldData, 'AEC CHANGE START' )) {
							$hacks[$name]['status'] = 'legacy';
						} else {
							if ( ( strpos( $oldData, 'AEC HACK ' . $name . ' START' ) > 0 ) || ( strpos( $oldData, 'AEC CHANGE ' . $name . ' START' ) > 0 )) {
								$hacks[$name]['status'] = 1;
							}
						}

						if ( function_exists( 'posix_getpwuid' ) ) {
							$hacks[$name]['fileinfo'] = posix_getpwuid( fileowner( $hack['filename'] ) );
						}
					}
					break;

				case 'menuentry':
					$count = 0;
					$query = 'SELECT COUNT(*)'
					. ' FROM #__menu'
					. ' WHERE link = \'index.php?option=com_acctexp&task=subscriptionDetails\''
					;
					$database->setQuery( $query );
					$count = $database->loadResult();

					if ( $count ) {
						$hacks[$name]['status'] = 1;
					}
					break;
			}
		}
	}

	// Commit the hacks
	if ( !$check_hack ) {

		switch( $hacks[$filename]['type'] ) {
			case 'file':
				// mic: fix if CMS is not Joomla or Mambo
				if ( $hack['filename'] != 'UNKNOWN' ) {
					$originalFileHandle = fopen( $hacks[$filename]['filename'], 'r' ) or die ("Cannot open $originalFile<br>");
					// Transfer File into variable $oldData
					$oldData = fread( $originalFileHandle, filesize( $hacks[$filename]['filename'] ) );
					fclose( $originalFileHandle );

					if ( !$undohack ) { // hack
						$newData			= str_replace( $hacks[$filename]['read'], $hacks[$filename]['insert'], $oldData );

					    //make a backup
					    if ( !backupFile( $hacks[$filename]['filename'], $hacks[$filename]['filename'] . '.aec-backup' ) ) {
							// Echo error message
					    }

					} else { // undo hack
						if ( strcmp( $hacks[$filename]['status'], 'legacy' ) === 0 ) {
							$newData = preg_replace( '/\/\/.AEC.(HACK|CHANGE).START\\n.*\/\/.AEC.(HACK|CHANGE).END\\n/s', $hacks[$filename]['read'], $oldData );
						} else {
							if ( strpos( $oldData, $hacks[$filename]['insert'] ) ) {
								$newData = str_replace( $hacks[$filename]['insert'], $hacks[$filename]['read'], $oldData );
							} else {
								$newData = preg_replace( '/\/\/.AEC.(HACK|CHANGE).' . $filename . '.START\\n.*\/\/.AEC.(HACK|CHANGE).' . $filename . '.END\\n/s', $hacks[$filename]['read'], $oldData );
							}
						}
					}

				    $oldperms = fileperms( $hacks[$filename]['filename'] );
				    @chmod( $hacks[$filename]['filename'], $oldperms | 0222 );

				    if ( $fp = fopen( $hacks[$filename]['filename'], 'wb' ) ) {
				        fwrite( $fp, $newData, strlen( $newData ) );
				        fclose( $fp );
				        @chmod( $hacks[$filename]['filename'], $oldperms );
				    }
				}
				break;

			case 'menuentry':
				if ( !$undohack ) { // Create menu entry
					$query = 'INSERT INTO #__menu'
					. ' VALUES (\'\', \'usermenu\', \'' . _AEC_SPEC_MENU_ENTRY . '\', \'index.php?option=com_acctexp&task=subscriptionDetails\', \'url\', 1, 0, 0, 0, 6, 0, \'0000-00-00 00:00:00\', 0, 0, 1, 0, \'menu_image=-1\')'
					;
				} else { // Remove menu entry
					$query = 'DELETE'
					. ' FROM #__menu'
					. ' WHERE link LIKE \'index.php?option=com_acctexp%\''
					;
				}

				$database->setQuery( $query );
				$res = $database->query();
				break;
		}
	}

	return $hacks;
}

function backupFile( $file, $file_new )
{
    if ( !copy( $file, $file_new ) ) {
        return false;
    }
    return true;
}
?>