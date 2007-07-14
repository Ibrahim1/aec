<?php
/**
* AcctExp Component
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

global $mainframe, $mosConfig_absolute_path;

define( '_AEC_FRONTEND', 1 );

if( !defined( '_AEC_LANG' ) ) {
	$langPath = $mosConfig_absolute_path . '/components/com_acctexp/com_acctexp_language/';
	if( file_exists( $langPath . $GLOBALS['mosConfig_lang'] . '.php' ) ) {
		include_once( $langPath . $GLOBALS['mosConfig_lang'] . '.php' );
	}else{
		include_once( $langPath . 'english.php' );
	}
	define( '_AEC_LANG', 1 );
}

include_once( $mosConfig_absolute_path . '/administrator/components/com_acctexp/com_acctexp_language_backend/general.php' );

require_once( $mainframe->getPath( 'front_html',	'com_acctexp' ) );
require_once( $mainframe->getPath( 'class',			'com_acctexp' ) );

$task = trim( mosGetParam( $_REQUEST, 'task', '' ) );

if ($task) {
	switch ( strtolower( $task ) ) {
		case 'register':
			$intro = trim( mosGetParam( $_REQUEST, 'intro', 0 ) );
			$usage = trim( mosGetParam( $_REQUEST, 'usage', 0 ) );

			$invoicefact = new InvoiceFactory(0);
			$invoicefact->create($option, $intro, $usage);
			break;

		// Catch hybrid CB registration
		case 'saveregisters':
		// Catch hybrid CMS registration
		case 'saveregistration':
		case 'subscribe':
			subscribe($option);
			break;

		case 'confirm':
			confirmSubscription($option);
			break;

		case 'savesubscription':
			$userid = trim( mosGetParam( $_REQUEST, 'userid', 0 ) );
			$usage = trim( mosGetParam( $_REQUEST, 'usage', 0 ) );

			$invoicefact = new InvoiceFactory($userid, $usage);
			$invoicefact->save($option, $_POST);
			break;

		case 'backsubscription':
			backSubscription( $option );
			break;

		case 'ipn':
			processNotification($option, "paypal");
			break;

		case 'thanks':
			$renew = trim( mosGetParam( $_REQUEST, 'renew', 0 ) );
			$free = trim( mosGetParam( $_REQUEST, 'free', 0 ) );
			thanks( $option, $renew, $free );
			break;

		case 'cancel':
			cancelPayment( $option );
			break;

		case 'errap':
			$usage         = trim( mosGetParam( $_REQUEST, 'usage', '' ) );
			$userid         = trim( mosGetParam( $_REQUEST, 'userid', '' ) );
			$username       = trim( mosGetParam( $_REQUEST, 'username', '' ) );
			$name           = trim( mosGetParam( $_REQUEST, 'name', '' ) );
			$recurring      = trim( mosGetParam( $_REQUEST, 'recurring', 0 ) );
			errorAP( $option, $usage, $userid, $username, $name, $recurring);
			break;

		case 'activateft':
			activateFT( $option );
			break;

		case 'subscriptiondetails':
			subscriptionDetails( $option );
			break;

		case 'renewsubscription':
			$itemid		= trim( mosGetParam( $_REQUEST, 'Itemid', 0 ) );

			$invoicefact = new InvoiceFactory( $itemid );
			$invoicefact->create( $option );
			break;

		case 'expired':
			$userid		= trim( mosGetParam( $_REQUEST, 'userid', 0 ) );
			$expiration = trim( mosGetParam( $_REQUEST, 'expiration', 0 ) );
			$itemid		= trim( mosGetParam( $_REQUEST, 'Itemid', 0 ) );

			if( $itemid ) {
				$userid = $itemid;
			}

			expired( $option, $userid, $expiration );
			break;

		case 'pending':
			$userid		= trim( mosGetParam( $_REQUEST, 'userid', '' ) );
			$itemid		= trim( mosGetParam( $_REQUEST, 'Itemid', 0 ) );

			if( $itemid ){
				$userid = $itemid;
			}

			pending( $option, $userid );
			break;

		case 'repeatpayment':
			$invoice	= trim( mosGetParam( $_REQUEST, 'invoice', 0 ) );
			$itemid		= trim( mosGetParam( $_REQUEST, 'Itemid', 0 ) );

			repeatInvoice($option, $invoice, $itemid);
			break;

		case 'cancelpayment':
			$invoice	= trim( mosGetParam( $_REQUEST, 'invoice', 0 ) );
			$pending	= trim( mosGetParam( $_REQUEST, 'pending', 0 ) );
			$itemid		= trim( mosGetParam( $_REQUEST, 'Itemid', 0 ) );

			cancelInvoice( $option, $invoice, $pending, $itemid );
			break;

		case 'invoiceaddcoupon':
			InvoiceAddCoupon( $option );
			break;

		case 'invoiceremovecoupon':
			InvoiceRemoveCoupon( $option );
			break;

		case 'invoiceaddparams':
			InvoiceAddParams( $option );
			break;

		case 'notallowed':
			notAllowed( $option );
			break;

		default:
			if( strpos( $task, 'notification' ) > 0 ) {
				$processor = str_replace( 'notification', '', $task );

				processNotification( $option, $processor );
			}else{
				$userid		= trim( mosGetParam( $_REQUEST, 'userid', '' ) );
				$expiration = trim( mosGetParam( $_REQUEST, 'expiration', '' ) );
				expired( $option, $userid, $expiration );
			}
			break;
	}
}

function expired ( $option, $userid, $expiration ) {
	global $mosConfig_live_site, $database;

	if( $userid > 0 ) {
		$metaUser = new metaUser( $userid );

		$expired = strtotime( $metaUser->objExpiration->expiration );

		if( $metaUser->hasSubscription ) {
			$trial = (strcmp($metaUser->objSubscription->status, 'Trial' ) === 0 );
			if (!$trial) {
				$params = $metaUser->objSubscription->getParams( 'params' );
				if( isset( $params['trialflag'])) {
					$trial = 1;
				}
			}
		}else{
			$trial = false;
		}

		$invoices = AECfetchfromDB::InvoiceCountbyUserID( $userid );

		if( $invoices ) {
			$invoice = AECfetchfromDB::lastUnclearedInvoiceIDbyUserID( $userid );
		}else{
			$invoice = false;
		}

		$frontend = new HTML_frontEnd ();
		$frontend->expired( $option, $userid, strftime( _ACCT_DATE_FORMAT, $expired), $metaUser->cmsUser->name, $metaUser->cmsUser->username, $invoice, $trial );
	}else{
		mosRedirect( sefRelToAbs( 'index.php' ) );
	}
}

function pending ( $option, $userid ) {
	global $database;

	if( $userid > 0 ) {
		$objUser = new mosUser( $database );
		$objUser->load( $userid );

		$invoices = AECfetchfromDB::InvoiceCountbyUserID( $userid );

		if( $invoices ) {
			$invoice = AECfetchfromDB::lastUnclearedInvoiceIDbyUserID( $userid );
			$objInvoice = new Invoice( $database );
			$objInvoice->loadInvoiceNumber( $invoice );
			$params = $objInvoice->getParams( 'params' );

			if( isset( $params['pending_reason'] ) ) {
				if( defined( '_PENDING_REASON_' . strtoupper( $params['pending_reason'] ) ) ) {
					$reason = constant( '_PENDING_REASON_' . strtoupper( $params['pending_reason'] ) );
				}else{
					$reason = $params['pending_reason'];
				}
			}elseif( strcmp( $objInvoice->method, 'transfer' ) === 0 ) {
				$reason = 'transfer';
			}else{
				$reason = 0;
			}
		}else{
			$invoice = 'none';
		}

		$frontend = new HTML_frontEnd ();
		$frontend->pending( $option, $objUser, $invoice, $reason );
	}else{
		mosRedirect( sefRelToAbs( 'index.php' ) );
	}
}

function subscribe ( $option ) {
	global $my, $database, $mosConfig_uniquemail;

	$intro	= trim( mosGetParam( $_REQUEST, 'intro', 0 ) );
	$usage	= trim( mosGetParam( $_REQUEST, 'usage', 0 ) );
	$userid = trim( mosGetParam( $_REQUEST, 'userid', 0 ) );
	$itemid = trim( mosGetParam( $_REQUEST, 'Itemid', 0 ) );

	if( isset( $_POST['username'] ) && $usage ) {
		$query = 'SELECT id'
		. ' FROM #__users'
		. ' WHERE username = \'' . $_POST['username'] . '\''
		;
		$database->setQuery( $query );
		if( $database->loadResult() ) {
			mosErrorAlert( _REGWARN_INUSE );
		}

		if( isset( $_POST['email'] ) ) {
			if( $mosConfig_uniquemail ) {
				// check for existing email
				$query = 'SELECT id'
				. ' FROM #__users'
				. ' WHERE email = \'' . $_POST['email'] . '\''
				;
				$database->setQuery( $query );
				if( $database->loadResult() ) {
					mosErrorAlert( _REGWARN_EMAIL_INUSE );
				}
			}
		}

		$invoicefact = new InvoiceFactory( $userid, $usage );
		$invoicefact->confirm( $option, $_POST );
	}else{
		if( $my->id ) {
			$userid			= $my->id;
			$passthrough	= false;
		}elseif( $itemid ) {
			$userid			= $itemid;
			$passthrough	= false;
		}elseif( !$userid ) {
			unset( $_POST['option'] );
			unset( $_POST['task'] );
			if( isset( $_POST['usage'] ) ) {
				unset( $_POST['intro'] );
				unset( $_POST['usage'] );
				unset( $_POST['Itemid'] );
			}

			if( isset( $_POST['username'] ) ) {
				$query = 'SELECT id'
				. ' FROM #__users'
				. ' WHERE username = \'' . $_POST['username'] . '\''
				;
				$database->setQuery( $query );
				if( $database->loadResult() ) {
					mosErrorAlert( _REGWARN_INUSE );
				}
			}

			if( isset( $_POST['email'] ) ) {
				if( $mosConfig_uniquemail ) {
					// check for existing email
					$query = 'SELECT id'
					. ' FROM #__users'
					. ' WHERE email = \'' . $_POST['email'] . '\''
					;
					$database->setQuery( $query );
					if( $database->loadResult() ) {
						mosErrorAlert( _REGWARN_EMAIL_INUSE );
					}
				}
			}

			$passthrough = array();
			// We have to support arrays for CB
			foreach( $_POST as $ke => $va ) {
				if( is_array( $va ) ) {
					foreach( $va as $con ) {
						$passthrough[$ke . '[]'] = $con;
					}
				}else{
					$passthrough[$ke] = $va;
				}
			}
		}

		$invoicefact = new InvoiceFactory( $userid );
		$invoicefact->create( $option, $intro, $usage, 0, $passthrough );
	}
}

function confirmSubscription ( $option ) {
	global $mosConfig_absolute_path, $mosConfig_emailpass, $mosConfig_useractivation, $mainframe, $my;

	$userid		= trim( mosGetParam( $_REQUEST, 'userid', 0 ) );
	$usage		= trim( mosGetParam( $_REQUEST, 'usage', 0 ) );
	$username	= trim( mosGetParam( $_REQUEST, 'username', 0 ) );

	if( ( $usage > 0 ) && !$username && !$userid && !$my->id ) {
		if( GeneralInfoRequester::detect_component( 'CB' ) || GeneralInfoRequester::detect_component( 'CBE' ) ) {
			// This is a CB registration, borrowing their code to register the user
			include_once( $mosConfig_absolute_path . '/components/com_comprofiler/comprofiler.html.php' );
			include_once( $mosConfig_absolute_path . '/components/com_comprofiler/comprofiler.php' );

			registerForm( $option, $mosConfig_emailpass, null );
		}else{
			// This is a joomla registration, borrowing their code to register the user
			include_once( $mosConfig_absolute_path . '/components/com_registration/registration.php' );

			registerForm( $option, $mosConfig_useractivation );
		}
	}else{
		$invoicefact = new InvoiceFactory( $userid, $usage );
		$invoicefact->confirm( $option, $_POST );
	}
}

function subscriptionDetails ( $option ) {
	global $database, $my, $mainframe;

	if( !$my->id ) {
		notAllowed( $option );
	}else{
		$metaUser = new metaUser( $my->id );

		if( !$metaUser->hasSubscription ) {
			subscribe( $option );
			exit();
		}

		switch( strtolower( $metaUser->objSubscription->type ) ) {
			case 'free':
			case 'none':
			case 'transfer':
			case '':
				$pp = false;
				break;

			default:
				$pp = new PaymentProcessor();
				if( $pp->loadName( $metaUser->objSubscription->type ) ) {
					$pp->init();
					$pp->getInfo();
				}else{
					$pp = false;
				}
				break;
		}

		if( strcmp( $metaUser->objSubscription->status, 'Cancelled' ) == 0 ) {
			$recurring = 0;
		}else{
			$recurring = $metaUser->objSubscription->recurring;
		}

		$mi_info = '';
		$selected_plan = new SubscriptionPlan( $database );

		if( $metaUser->objSubscription->plan ) {
			$selected_plan->load( $metaUser->objSubscription->plan );

			$mis = explode( ';', $selected_plan->micro_integrations );

			if( count( $mis ) ) {
				foreach( $mis as $mi_id ) {
					if( $mi_id ) {
						$mi = new MicroIntegration( $database );
						$mi->load( $mi_id );
						if( $mi->callIntegration() ) {
							$info = $mi->profile_info( $my->id );
							if( !( $info === false ) ) {
								$mi_info .= $info;
							}
						}
					}
				}
			}
		}else{
			$selected_plan	= false;
			$mi				= false;
		}

		$alert = array();
		if( strcmp( $metaUser->objSubscription->status, 'Excluded' ) === 0 ) {
			$alert['level']		= 3;
			$alert['daysleft']	= 'excluded';
		}elseif( $metaUser->objSubscription->lifetime ) {
			$alert['level']		= 3;
			$alert['daysleft']	= 'infinite';
		}else{
			$alert = $metaUser->objSubscription->GetAlertLevel();
		}

		// count number of payments from user
		$query = 'SELECT count(*)'
		. ' FROM #__acctexp_invoices'
		. ' WHERE userid = \'' . $my->id . '\''
		;
		$database->setQuery( $query );
		$rows_total	= $database->loadResult();

		$rows_limit	= 10;	// Returns last 10 payments
		$min_limit	= ( $rows_total > $rows_limit ) ? ( $rows_total - $rows_limit ) : 0;

		// get payments from user
		$query = 'SELECT invoice_number, transaction_date, method, amount, currency, params'
		. ' FROM #__acctexp_invoices'
		. ' WHERE userid = \'' . $my->id . '\''
		. ' AND active = \'1\''
		. ' ORDER BY transaction_date DESC'
		. ' LIMIT ' . $min_limit . ',' . $rows_limit
		;
		$database->setQuery( $query );
		$rows = $database->loadObjectList();
		if( $database->getErrorNum() ) {
			echo $database->stderr();
			return false;
		}

		$invoices = array();
		foreach( $rows as $rowid => $row ) {
			if( strcmp( $row->transaction_date, '0000-00-00 00:00:00' ) === 0 ) {
				if( strpos( $row->params, 'pending_reason' ) ) {
					$params = explode( "\n", $row->params );

					$array = array();
					foreach( $params as $chunk ) {
						$k = explode( '=', $chunk );
						$array[$k[0]] = stripslashes( $k[1] );
					}

					if( isset( $array['pending_reason'] ) ) {
						if( defined( '_PAYMENT_PENDING_REASON_' . strtoupper( $array['pending_reason'] ) ) ) {
							$transactiondate = constant( '_PAYMENT_PENDING_REASON_' . strtoupper( $array['pending_reason'] ) );
						}else{
							$transactiondate = $array['pending_reason'];
						}
					}else{
						$transactiondate = 'uncleared';
					}
				}else{
					$transactiondate = 'uncleared';
				}

				$actions = '<a href="'
				.  AECToolbox::deadsureURL( '/index.php?option=' . $option . '&amp;task=repeatPayment&amp;invoice='
				. $row->invoice_number ) . '">' . _HISTORY_ACTION_REPEAT
				. '</a>';

				if( is_null( $row->fixed ) || !$row->fixed ) {
					$actions .= ' | '
					. '<a href="'
					. AECToolbox::deadsureURL( '/index.php?option=' . $option . '&amp;task=cancelPayment&amp;invoice='
					. $row->invoice_number) . '">' . _HISTORY_ACTION_CANCEL
					. '</a>';
				}
				$rowstyle = ' style="background-color:#fee;"';
			}else{
				$transactiondate	= HTML_frontend::DisplayDateInLocalTime( $row->transaction_date );
				$actions			= '- - -';
				$rowstyle			= '';
			}

			$invoices[$rowid]['invoice_number']	= $row->invoice_number;
			$invoices[$rowid]['amount']			= $row->amount;
			$invoices[$rowid]['currency_code']	= $row->currency;
			$invoices[$rowid]['processor']		= $row->method;
			$invoices[$rowid]['actions']		= $actions;
			$invoices[$rowid]['rowstyle']		= $rowstyle;
			$invoices[$rowid]['transactiondate'] = $transactiondate;
		}

		$html = new HTML_frontEnd();
		$html->subscriptionDetails( $option, $invoices, $metaUser, $recurring, $pp, $mi_info, $alert, $my->id, $selected_plan );
	}
}

function repeatInvoice ( $option, $invoice_number, $userid ){
	global $database, $my;

	// Always rewrite to session userid
	if( !empty( $my->id ) ) {
		$userid = $my->id;
	}

	$invoiceid = AECfetchfromDB::InvoiceIDfromNumber( $invoice_number, $userid );

	// Only allow a user to access existing and own invoices
	if( $invoiceid ) {
		$invoicefact = new InvoiceFactory( $userid );
		$invoicefact->touchInvoice( $option, $invoice_number );
		$invoicefact->checkout( $option );
	}else{
		mosNotAuth();
		return;
	}
}

function cancelInvoice ( $option, $invoice_number, $pending=0, $userid ){
	global $database, $my;

	if( empty($my->id ) ) {
		if( $userid ) {
			if( AECToolbox::quickVerifyUserID($userid) === true ) {
				// This user is not expired, so he could log in...
				mosNotAuth();
				return;
			}
		}else{
			mosNotAuth();
		}
	}else{
		$userid = $my->id;
	}

	$invoiceid = AECfetchfromDB::InvoiceIDfromNumber( $invoice_number, $userid );

	// Only allow a user to access existing and own invoices
	if( $invoiceid ) {
		$objInvoice = new Invoice( $database );
		$objInvoice->load( $invoiceid );

		if( !$objInvoice->fixed ) {
			$objInvoice->active = 0;
			$objInvoice->setParams( array('deactivated' => 'cancel') );
			$objInvoice->check();
			$objInvoice->store();
		}
	}else{
		mosNotAuth();
		return;
	}

	if( $pending ) {
		pending( $option, $userid );
	}else{
		subscriptionDetails( $option );
	}

}

function InvoiceAddParams ( $option ){
	global $database;

	$invoice = new Invoice( $database );
	$invoice->loadInvoiceNumber( $_POST['invoice'] );
	$invoice->savePostParams( $_POST );
	$invoice->check();
	$invoice->store();

	repeatInvoice( $option, $_POST['invoice'], $invoice->userid );
}

function InvoiceAddCoupon ( $option ){
	global $database;

	$invoice = new Invoice( $database );
	$invoice->loadInvoiceNumber( $_POST['invoice'] );
	$invoice->addCoupon( $_POST['coupon_code'] );
	$invoice->check();
	$invoice->store();

	repeatInvoice( $option, $_POST['invoice'], $invoice->userid );
}

function InvoiceRemoveCoupon ( $option ){
	global $database;

	$invoice = new Invoice( $database );
	$invoice->loadInvoiceNumber( $_REQUEST['invoice'] );
	$invoice->removeCoupon( $_REQUEST['coupon_code'] );
	$invoice->check();
	$invoice->store();

	repeatInvoice( $option, $_REQUEST['invoice'], $invoice->userid );
}

function notAllowed ( $option ) {
	global $database, $my;
	$cfg = new Config_General( $database );

	if( ( $cfg->cfg['customnotallowed'] != '' ) && !is_null( $cfg->cfg['customnotallowed'] ) ) {
		mosRedirect( $cfg->cfg['customnotallowed'] );
	}

	$gwnames = explode( ';', $cfg->cfg['gwlist'] );

	if( count( $gwnames ) && $gwnames[0] ) {
		$processors = array();
		foreach( $gwnames as $procname ) {
			$processor = trim( $procname );
			$processors[$processor] = new PaymentProcessor();
			if( $processors[$processor]->loadName( $processor ) ) {
				$processors[$processor]->init();
				$processors[$processor]->getInfo();
			}else{
				// TODO: Log error
				unset( $processors[$processor] );
			}
		}
	}else{
		$processors = false;
	}

	$CB = ( GeneralInfoRequester::detect_component( 'CB' ) || GeneralInfoRequester::detect_component( 'CBE' ) );

	if( $my->id ) {
		$registerlink = AECToolbox::deadsureURL( '/index.php?option=com_acctexp&amp;task=renewsubscription' );
		$loggedin = 1;
	}else{
		$loggedin = 0;
		if( $CB ) {
			$registerlink = AECToolbox::deadsureURL( '/index.php?option=com_comprofiler&amp;task=registers' );
		}else{
			$registerlink = AECToolbox::deadsureURL( '/index.php?option=com_registration&amp;task=register' );
		}
	}

	$frontend = new HTML_frontEnd ();
	$frontend->notAllowed( $option, $processors, $registerlink, $loggedin );
}

function backSubscription ( $option ) {
	global $mainframe, $database, $my, $acl;

	// Rebuild array
	foreach( $_POST as $key => $value ) {
		$var[$key]	= $value;
	}

	// Get other values to show in confirmForm
	$userid	= $var['userid'];
	$usage	= $var['usage'];

 	// get the payment plan
	$objplan = new SubscriptionPlan( $database );
	$objplan->load( $usage );

 	// get the user object
	$objuser = new mosUser( $database );
	$objuser->load( $userid );

	$unset = array( 'id', 'gid', 'task', 'option', 'name', 'username', 'email', 'password', '', 'password2' );
	foreach( $unset as $key ) {
		if( isset($var[$key] ) ) {
			unset( $var[$key] );
		}
	}

	$mainframe->SetPageTitle( _REGISTER_TITLE );
	Payment_HTML::subscribeForm( $option, $var, $objplan, null, $objuser );
}

function processNotification ( $option, $processor ) {
	global $database;

	// Init flags
	$recurring	= 0;
	$free		= 0;

	// Legacy naming support
	switch( $processor ) {
		case 'vklix':
			$processor = 'viaklix';
			break;
		case 'auth':
			$processor = 'authorize';
			break;
		case '2co':
			$processor = '2checkout';
			break;
		case 'eps':
			$processor = 'epsnetpay';
			break;
	}

	// Create Response String for History
	$responsestring = '';
	$post = array();
	foreach( $_POST as $key => $value ) {
		$value = urlencode( stripslashes( $value ) );
		$responsestring .= $key . '=' . $value . "\n";
	}

	switch ( strtolower($processor) ) {
		case 'free':
			$free = 1;
			break;
		case 'transfer':
			break;
		default:
			// parse processor notification
			$pp = new PaymentProcessor( $processor );
			if( $pp->loadName( $processor ) ) {
				$pp->init();
				$response = $pp->parseNotification( $_POST );
				if( isset( $response['processorresponse'] ) ) {
					$responsestring = $response['processorresponse'] . "\n" . $responsestring;
				}
			}else{
				exit();
				// TODO: Log error
			}
			break;
	}

	// Get Invoice record
	$id = AECfetchfromDB::InvoiceIDfromNumber( $response['invoice'] );

	if( $id ) {
		$objInvoice = new Invoice( $database );
		$objInvoice->load( $id );
		$objInvoice->computeAmount();
	}else{
		$short	= _AEC_MSG_PROC_INVOICE_FAILED_SH;
		$event	= sprintf( _AEC_MSG_PROC_INVOICE_FAILED_EV, $processor, $objInvoice->invoice_number )
				. ' ' . $database->getErrorMsg();
		$tags	= 'invoice,processor,payment,error';
		$params = array( 'invoice_number' => $objInvoice->invoice_number );

		$eventlog = new eventLog($database);
		$eventlog->issue( $short, $tags, $event, $params );
		return;
	}

	// Create history entry
	$history = new logHistory( $database );
	$history->entryFromInvoice ( $objInvoice, $responsestring, $processor );

	$short = _AEC_MSG_PROC_INVOICE_ACTION_SH;
	$event = _AEC_MSG_PROC_INVOICE_ACTION_EV . "\n";
	foreach ($response as $key => $value) {
		$event .= $key . "=" . $value . "\n";
	}
	$event	.= _AEC_MSG_PROC_INVOICE_ACTION_EV_STATUS;
	$tags	= 'invoice,processor';
	$params = array( 'invoice_number' => $objInvoice->invoice_number );

	$event .= ' ';

	if( $response['valid'] ) {
		$break = 0;

		if( isset( $response['amount_paid'] ) ) {
			if( $response['amount_paid'] != $objInvoice->amount ) {
				// Amount Fraud, cancel payment and create error log addition
				$event	.= sprintf( _AEC_MSG_PROC_INVOICE_ACTION_EV_FRAUD, $response['amount_paid'], $objInvoice->amount );
				$tags	.= ',fraud_attempt,amount_fraud';
				$break	= 1;
			}
		}
		if( isset($response['amount_currency'] ) ) {
			if( $response['amount_currency'] != $objInvoice->currency ) {
				// Amount Fraud, cancel payment and create error log addition
				$event	.= sprintf( _AEC_MSG_PROC_INVOICE_ACTION_EV_CURR, $response['amount_currency'], $objInvoice->currency );
				$tags	.= ',fraud_attempt,currency_fraud';
				$break	= 1;
			}
		}

		if( !$break ) {
			$renew	= $objInvoice->pay();
			thanks( $option, $renew, $free );
			$event	.= _AEC_MSG_PROC_INVOICE_ACTION_EV_VALID;
			$tags	.= ',payment,action';
		}
	}else{
		if( isset( $response['pending'] ) ) {
			if( strcmp( $response['pending_reason'], 'signup' ) === 0 ) {
				$plan = new SubscriptionPlan( $database );
				$plan->load( $objInvoice->usage );
				$params = $this->getParams( 'params' );

				if( $params['trial_free'] ) {
					$objInvoice->pay();
					$objInvoice->setParams( array( 'free_trial' => $response['pending_reason'] ) );
					$event	.= _AEC_MSG_PROC_INVOICE_ACTION_EV_TRIAL;
					$tags	.= ',payment,action,trial';
				}
			}else{
				$objInvoice->setParams( array( 'pending_reason' => $response['pending_reason'] ) );
				$event	.= sprintf( _AEC_MSG_PROC_INVOICE_ACTION_EV_PEND, $response['pending_reason'] );
				$tags	.= ',payment,pending' . $response['pending_reason'];
			}
			$objInvoice->check();
			$objInvoice->store();
		}elseif( isset( $response['cancel'] ) ) {
			$metaUser = new metaUser();
			$metaUser->load( $objInvoice->userid );
			$event	.= _AEC_MSG_PROC_INVOICE_ACTION_EV_CANCEL;
			$tags	.= ',cancel';

			if( $metaUser->objSubscription->hasSubscription ) {
				$metaUser->objSubscription->setStatus( 'Cancelled' );
				$event .= _AEC_MSG_PROC_INVOICE_ACTION_EV_USTATUS;
			}
		} elseif (isset($response['eot'])) {
			$metaUser = new metaUser();
			$metaUser->load($objInvoice->userid);
			$event	.= _AEC_MSG_PROC_INVOICE_ACTION_EV_EOT;
			$tags	.= ',eot';
		} else {
			$event	.= _AEC_MSG_PROC_INVOICE_ACTION_EV_U_ERROR;
			$tags	.= ',general_error';
		}
	}

	$eventlog = new eventLog( $database );
	$eventlog->issue( $short, $tags, $event, $params );
}

function errorAP ( $option, $usage, $userid, $username, $name, $recurring ) {
	Payment_HTML::errorAP( $option, $usage, $userid, $username, $name, $recurring );
}

function thanks ( $option, $renew, $free ) {
	global $database, $mosConfig_useractivation, $ueConfig, $mosConfig_dbprefix;

	$cfg = new Config_General( $database );

	if( $mosConfig_useractivation && ( $cfg->cfg['activate_paid'] || $free ) ) {
		$activation = 1;
	}else{
		$activation = 0;
	}

	if( $renew ) {
		$msg = _SUB_FEPARTICLE_HEAD_RENEW
		. '</p><p>'
		. _SUB_FEPARTICLE_THANKSRENEW;
		if( $free ) {
			$msg .= _SUB_FEPARTICLE_LOGIN;
		}else{
			$msg .= _SUB_FEPARTICLE_PROCESSPAY
			. _SUB_FEPARTICLE_MAIL;
		}
	}else{
		$msg = _SUB_FEPARTICLE_HEAD
		. '</p><p>'
		. _SUB_FEPARTICLE_THANKS;
		if( $free ) {
			$msg .= _SUB_FEPARTICLE_PROCESS
			. _SUB_FEPARTICLE_MAIL;
		}else{
			$msg .= _SUB_FEPARTICLE_PROCESSPAY
			. _SUB_FEPARTICLE_MAIL;
		}
	}

	// Look whether we have a custom ThankYou page
	if( $cfg->cfg['customthanks'] ) {
		mosRedirect( $cfg->cfg['customthanks'] );
	}else{
		HTML_Results::thanks( $option, $msg );
	}
}

function cancelPayment ( $option ) {
	global $database;

	$cfg = new Config_General( $database );

	$userid = trim( mosGetParam( $_REQUEST, 'itemnumber', '' ) );
	// The user cancel the payment operation
	// But user is already created as blocked on database, so we need to delete it
	$obj = new mosUser( $database );
	$obj->load( $userid );

	if( $obj->id ) {
		if( strcmp( $obj->type, 'Super Administrator' ) != 0 && strcmp( $obj->type, 'Administrator' ) != 0 && $obj->block == 1 ) {
			// If the user is not blocked this can be a false cancel
			// So just delete user if he is blocked and is not an administrator or super admnistrator
			$obj->delete();
		}
	}

	// Look whether we have a custom Cancel page
	if( $cfg->cfg['customcancel'] ) {
		mosRedirect( $cfg->cfg['customcancel'] );
	}else{
		HTML_Results::cancel( $option );
	}
}
?>