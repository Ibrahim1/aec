<?php
/**
 * @version $Id: acctexp.php
 * @package AEC - Account Control Expiration - Membership Manager
 * @subpackage Main Frontend
 * @copyright 2006-2011 Copyright (C) David Deutsch
 * @author David Deutsch <skore@valanx.org> & Team AEC - http://www.valanx.org
 * @license GNU/GPL v.2 http://www.gnu.org/licenses/old-licenses/gpl-2.0.html or, at your option, any later version
 */

// Dont allow direct linking
( defined('_JEXEC') || defined( '_VALID_MOS' ) ) or die( 'Direct Access to this location is not allowed.' );

global $aecConfig;

define( '_AEC_FRONTEND', 1 );

require_once( JApplicationHelper::getPath( 'class',		'com_acctexp' ) );
require_once( JApplicationHelper::getPath( 'front_html',	'com_acctexp' ) );

if ( !defined( '_EUCA_DEBUGMODE' ) ) {
	define( '_EUCA_DEBUGMODE', $aecConfig->cfg['debugmode'] );
}

if ( _EUCA_DEBUGMODE ) {
	global $eucaDebug;

	$eucaDebug = new eucaDebug();
}

$user = &JFactory::getUser();

$task = trim( aecGetParam( 'view', '', true, array( 'word', 'string', 'clear_nonalnum' ) ) );

if ( empty( $task ) ) {
	// Regular mode - try to get the task
	$task = trim( aecGetParam( 'task', '', true, array( 'word', 'string', 'clear_nonalnum' ) ) );
} else {
	$params = &JComponentHelper::getParams( 'com_acctexp' );

	$menuitemid = JRequest::getInt( 'Itemid' );
	if ( $menuitemid ) {
		$menu = JSite::getMenu();
		$menuparams = $menu->getParams( $menuitemid );
		$params->merge( $menuparams );
	}

	$translate = array( 'usage', 'group', 'processor', 'intro', 'sub' );

	foreach ( $translate as $k ) {
		// Do not overwrite stuff that our forms supplied
		if ( !isset( $_REQUEST[$k] ) ) {
			$_REQUEST[$k]	= $params->get( $k );
			$_POST[$k]		= $params->get( $k );
		}
	}

	$layout = trim( aecGetParam( 'layout', '', true, array( 'word', 'string', 'clear_nonalnum' ) ) );

	if ( !empty( $layout ) ) {
		if ( $layout != 'default' ) {
			$task = $layout;
		}
	}
}

if ( !empty( $task ) ) {
	switch ( strtolower( $task ) ) {
		case 'heartbeat':
		case 'beat':
			// Manual Heartbeat
			$hash = aecGetParam( 'hash', 0, true, array( 'word', 'string' ) );

			$db = &JFactory::getDBO();

			$heartbeat = new aecHeartbeat( $db );
			$heartbeat->frontendping( true, $hash );
			break;

		case 'register':
			$intro = aecGetParam( 'intro', 0, true, array( 'word', 'int' ) );
			$usage = aecGetParam( 'usage', 0, true, array( 'word', 'string', 'clear_nonalnum' ) );
			$group = aecGetParam( 'group', 0, true, array( 'word', 'int' ) );

			$iFactory = new InvoiceFactory();
			$iFactory->create( $option, $intro, $usage, $group );
			break;

		// Catch hybrid CB registration
		case 'saveregisters':
		// Catch hybrid jUser registration
		case 'saveuserregistration':
		// Catch hybrid CMS registration
		case 'saveregistration':
		case 'subscribe':
		case 'signup':
		case 'login':
			subscribe( $option );
			break;

		case 'confirm':
		case 'confirmation':
			confirmSubscription($option);
			break;

		case 'addressexception':
			JRequest::checkToken() or die( 'Invalid Token' );

			$invoice	= aecGetParam( 'invoice', 0, true, array( 'word', 'string', 'clear_nonalnum' ) );
			$cart		= aecGetParam( 'cart', 0, true, array( 'word', 'string', 'clear_nonalnum' ) );
			$userid		= aecGetParam( 'userid', 0 );

			if ( !empty( $user->id ) ) {
				$userid = $user->id;
			}

			repeatInvoice( $option, $invoice, $cart, $userid );
			break;

		case 'savesubscription':
			JRequest::checkToken() or die( 'Invalid Token' );

			$userid		= aecGetParam( 'userid', 0, true, array( 'word', 'int' ) );
			$usage		= aecGetParam( 'usage', 0, true, array( 'word', 'string', 'clear_nonalnum' ) );
			$group		= aecGetParam( 'group', 0, true, array( 'word', 'int' ) );
			$processor	= aecGetParam( 'processor', '', true, array( 'word', 'string', 'clear_nonalnum' ) );
			$coupon		= aecGetParam( 'coupon_code', '', true, array( 'word', 'string', 'clear_nonalnum' ) );

			$iFactory = new InvoiceFactory( $userid, $usage, $group, $processor );
			$iFactory->save( $option, $coupon );
			break;

		case 'addtocart':
			$userid			= aecGetParam( 'userid', 0, true, array( 'word', 'int' ) );
			$usage			= aecGetParam( 'usage', '', true, array( 'word', 'string', 'clear_nonalnum' ) );
			$returngroup	= aecGetParam( 'returngroup', '', true, array( 'word', 'int' ) );

			if ( !empty( $user->id ) ) {
				$userid = $user->id;
			}

			if ( !$user->id ) {
				notAllowed( $option );
			} else {
				$iFactory = new InvoiceFactory( $userid );
				$iFactory->addtoCart( $option, $usage, $returngroup );
			}
			break;

		case 'cart':
			$user = &JFactory::getUser();

			if ( !$user->id ) {
				notAllowed( $option );
			} else {
				$userid		= aecGetParam( 'userid', 0, true, array( 'word', 'int' ) );

				if ( !empty( $user->id ) ) {
					$userid = $user->id;
				}

				$iFactory = new InvoiceFactory( $userid );
				$iFactory->cart( $option );
			}
			break;

		case 'updatecart':
			JRequest::checkToken() or die( 'Invalid Token' );

			$userid		= aecGetParam( 'userid', 0, true, array( 'word', 'int' ) );

			if ( !empty( $user->id ) ) {
				$userid = $user->id;
			}

			if ( !$user->id ) {
				notAllowed( $option );
			} else {
				$iFactory = new InvoiceFactory( $userid );
				$iFactory->updateCart( $option, $_POST );
				$iFactory->cart( $option );
			}
			break;

		case 'clearcart':
			JRequest::checkToken( 'get' ) or die( 'Invalid Token' );

			$userid		= aecGetParam( 'userid', 0, true, array( 'word', 'int' ) );

			if ( !empty( $user->id ) ) {
				$userid = $user->id;
			}

			if ( !$user->id ) {
				notAllowed( $option );
			} else {
				$iFactory = new InvoiceFactory( $userid );
				$iFactory->clearCart( $option );

				$iFactory = new InvoiceFactory( $userid );
				$iFactory->cart( $option );
			}
			break;

		case 'clearcartitem':
			JRequest::checkToken( 'get' ) or die( 'Invalid Token' );

			$item		= aecGetParam( 'item', 0, true, array( 'word', 'int' ) );

			if ( !empty( $user->id ) ) {
				$userid = $user->id;
			}

			if ( !$user->id ) {
				notAllowed( $option );
			} else {
				$iFactory = new InvoiceFactory( $userid );
				$iFactory->clearCartItem( $option, $item );

				$iFactory = new InvoiceFactory( $userid );
				$iFactory->cart( $option );
			}
			break;

		case 'confirmcart':
			JRequest::checkToken() or die( 'Invalid Token' );

			$userid		= aecGetParam( 'userid', 0, true, array( 'word', 'int' ) );
			$coupon		= aecGetParam( 'coupon_code', '', true, array( 'word', 'string', 'clear_nonalnum' ) );

			if ( !empty( $user->id ) ) {
				$userid = $user->id;
			}

			if ( !$user->id ) {
				notAllowed( $option );
			} else {
				$iFactory = new InvoiceFactory( $userid );
				$iFactory->confirmcart( $option, $coupon );
			}
			break;

		case 'checkout':
			$invoice	= aecGetParam( 'invoice', 0, true, array( 'word', 'string', 'clear_nonalnum' ) );
			$processor	= aecGetParam( 'processor', 0, true, array( 'word', 'string', 'clear_nonalnum' ) );
			$userid		= aecGetParam( 'userid', 0, true, array( 'word', 'int' ) );

			internalCheckout( $option, $invoice, $processor, $userid );
			break;

		case 'backsubscription':
			backSubscription( $option );
			break;

		case 'thanks':
			$renew		= aecGetParam( 'renew', 0, true, array( 'word', 'int' ) );
			$free		= aecGetParam( 'free', 0, true, array( 'word', 'int' ) );

			$usage		= aecGetParam( 'usage', 0, true, array( 'word', 'string', 'clear_nonalnum' ) );

			if ( empty( $usage ) ) {
				$usage = aecGetParam( 'u', 0, true, array( 'word', 'string', 'clear_nonalnum' ) );
			}

			$iFactory = new InvoiceFactory();

			if ( !empty( $usage ) ) {
				$db = &JFactory::getDBO();

				$iFactory->plan = new SubscriptionPlan( $db );
				$iFactory->plan->load( $usage );
			}

			$iFactory->thanks( $option, $renew, $free );
			break;

		case 'cancel':
			cancelPayment( $option );
			break;

		case 'subscriptiondetails':
			$sub		= aecGetParam( 'sub', 'overview', true, array( 'word', 'string' ) );
			$page		= aecGetParam( 'page', '0', true, array( 'word', 'int' ) );

			subscriptionDetails( $option, $sub, $page );
			break;

		case 'renewsubscription':
			JRequest::checkToken() or die( 'Invalid Token' );

			$userid		= aecGetParam( 'userid', 0, true, array( 'word', 'int' ) );
			$intro		= aecGetParam( 'intro', 0, true, array( 'word', 'int' ) );
			$usage		= aecGetParam( 'usage', 0, true, array( 'word', 'int' ) );

			$iFactory = new InvoiceFactory( $userid );
			if ( $iFactory->checkAuth( $option ) ) {
				$iFactory->create( $option, $intro, $usage );
			}
			break;

		case 'expired':
			$userid		= aecGetParam( 'userid', 0, true, array( 'word', 'int' ) );
			$expiration = aecGetParam( 'expiration', 0, true, array( 'string' ) );

			expired( $option, $userid, $expiration );
			break;

		case 'hold':
			$userid		= aecGetParam( 'userid', 0, true, array( 'word', 'int' ) );

			hold( $option, $userid );
			break;

		case 'pending':
			$userid		= aecGetParam( 'userid', true, array( 'word', 'int' ) );

			pending( $option, $userid );
			break;

		case 'repeatpayment':
			$invoice	= aecGetParam( 'invoice', 0, true, array( 'word', 'string', 'clear_nonalnum' ) );
			$userid		= aecGetParam( 'userid', 0 );
			$first		= aecGetParam( 'first', 0 );

			repeatInvoice( $option, $invoice, null, $userid, $first );
			break;

		case 'cancelpayment':
			$invoice	= aecGetParam( 'invoice', 0, true, array( 'word', 'string', 'clear_nonalnum' ) );
			$pending	= aecGetParam( 'pending', 0 );
			$userid		= aecGetParam( 'userid', 0 );

			cancelInvoice( $option, $invoice, $pending, $userid );
			break;

		case 'planaction':
			$action	= aecGetParam( 'action', 0, true, array( 'word', 'string' ) );
			$subscr	= aecGetParam( 'subscr', '', true, array( 'word', 'int' ) );

			planaction( $option, $action, $subscr );
			break;

		case 'invoiceprint':
			$invoice	= aecGetParam( 'invoice', '', true, array( 'word', 'string', 'clear_nonalnum' ) );

			InvoicePrintout( $option, $invoice );
			break;

		case 'invoiceaction':
			$action		= aecGetParam( 'action', 0, true, array( 'word', 'string' ) );
			$invoice	= aecGetParam( 'invoice', '', true, array( 'word', 'string', 'clear_nonalnum' ) );

			invoiceAction( $option, $action, $invoice );
			break;

		case 'invoicemakegift':
			JRequest::checkToken() or die( 'Invalid Token' );

			InvoiceMakeGift( $option );
			break;

		case 'invoiceremovegift':
			JRequest::checkToken() or die( 'Invalid Token' );

			InvoiceRemoveGift( $option );
			break;

		case 'invoiceremovegiftcart':
			JRequest::checkToken() or die( 'Invalid Token' );

			InvoiceRemoveGiftCart( $option );
			break;

		case 'invoiceremovegiftconfirm':
			JRequest::checkToken() or die( 'Invalid Token' );

			InvoiceRemoveGiftConfirm( $option );
			break;

		case 'invoiceaddcoupon':
			JRequest::checkToken() or die( 'Invalid Token' );

			InvoiceAddCoupon( $option );
			break;

		case 'invoiceremovecoupon':
			InvoiceRemoveCoupon( $option );
			break;

		case 'invoiceaddparams':
			JRequest::checkToken() or die( 'Invalid Token' );

			InvoiceAddParams( $option );
			break;

		case 'notallowed':
			notAllowed( $option );
			break;

		// Legacy - to be deprecated after thorough check
		case 'ipn':
			processNotification( $option, "paypal" );
			break;

		case 'api':
			$app		= aecGetParam( 'app', 0, true, array( 'word', 'string' ) );
			$key		= aecGetParam( 'key', 0, true, array( 'word', 'string' ) );
			$request	= aecGetParam( 'request' );

			apiCall( $app, $key, $request );
			break;

		default:
			if ( strpos( $task, 'notification' ) > 0 ) {
				$processor = str_replace( 'notification', '', $task );

				processNotification( $option, $processor );
			} else {
				$userid		= aecGetParam( 'userid', true, array( 'word', 'int' ) );
				$expiration = aecGetParam( 'expiration', true, array( 'word', 'int' ) );

				if ( !empty( $userid ) && empty( $user->id ) ) {
					expired( $option, $userid, $expiration );
				} elseif ( !empty( $user->id )) {
					subscriptionDetails( $option );
				} else {
					subscribe( $option );
				}
			}
			break;
	}
}

function apiCall( $app, $key, $request )
{
	global $aecConfig;

	if ( empty( $aecConfig->cfg['apiapplist'] ) ) {
		header("HTTP/1.0 401 Unauthorized"); die; // die, die
	}

	if ( isset( $aecConfig->cfg['apiapplist'][$app] ) ) {
		if ( trim($key) == trim($aecConfig->cfg['apiapplist'][$app]) ) {
			if ( empty( $request ) ) {
				header( "HTTP/1.0 400 Bad Request" ); die;
			}

			if ( get_magic_quotes_gpc() ) {
				$request = stripslashes( $request );
			}

			$req = json_decode( $request );

			if ( is_null( $request ) ) {
				header( "HTTP/1.0 415 Unsupported Media Type" ); die;
			}

			if ( !is_array($req) ) {
				$req = array( $req );
			}

			header( "HTTP/1.0 200 OK" );

			$api = new aecAPI();

			$return = array();
			foreach ( $req as $r ) {
				$api->load( $r );

				$r = new stdClass();
				$r->response	= new stdClass();
				$r->error		= null;

				if ( empty( $api->error ) ) {
					$api->resolve();

					$r->response	= $api->response;
				} else {
					$r->response->result = false;
				}

				$r->error	= $api->error;

				$return[] = $r;
			}

			if ( count( $return ) == 1 ) {
				$return = $return[0];
			}

			echo json_encode( $return ); die; // regular die
		}
	}

	header("HTTP/1.0 401 Unauthorized"); die; // die, die
}

function hold( $option, $userid )
{
	if ( $userid > 0 ) {
		$metaUser = new metaUser( $userid );

		if ( $metaUser->hasSubscription ) {
			// Make sure this really is pending
			if ( strcmp($metaUser->objSubscription->status, 'Hold') !== 0 ) {
				return aecNotAuth();
			}
		}

		$document=& JFactory::getDocument();

		$document->setTitle( html_entity_decode( JText::_('HOLD_TITLE'), ENT_COMPAT, 'UTF-8' ) );

		$frontend = new HTML_frontEnd();
		$frontend->hold( $option, $metaUser );
	} else {
		aecRedirect( AECToolbox::deadsureURL( 'index.php' ) );
	}
}

function expired( $option, $userid, $expiration )
{
	global $aecConfig;

	$db = &JFactory::getDBO();

	if ( !empty( $userid ) ) {
		$metaUser = new metaUser( $userid );

		$trial		= false;
		$expired	= false;
		$invoice	= false;

		if ( $metaUser->hasSubscription ) {
			// Make sure this really is expired
			if ( !$metaUser->objSubscription->is_expired() ) {
				return aecNotAuth();
			}

			$expired = strtotime( $metaUser->objSubscription->expiration );

			$trial = ( strcmp($metaUser->objSubscription->status, 'Trial') === 0 );
			if ( !$trial ) {
				$params = $metaUser->objSubscription->params;
				if ( isset( $params['trialflag'])) {
					$trial = 1;
				}
			}
		}

		$invoices = AECfetchfromDB::InvoiceCountbyUserID( $userid );

		if ( $invoices ) {
			$invoice = AECfetchfromDB::lastUnclearedInvoiceIDbyUserID( $userid );
		}

		$expiration	= AECToolbox::formatDate( $expired );

		$document=& JFactory::getDocument();

		$document->setTitle( html_entity_decode( JText::_('EXPIRED_TITLE'), ENT_COMPAT, 'UTF-8' ) );

		$continue = false;
		if ( $aecConfig->cfg['continue_button'] && $metaUser->hasSubscription ) {
			$status = SubscriptionPlanHandler::PlanStatus( $metaUser->focusSubscription->plan );
			if ( !empty( $status ) ) {
				$continue = true;
			}
		}

		$frontend = new HTML_frontEnd();
		$frontend->expired( $option, $metaUser, $expiration, $invoice, $trial, $continue );
	} else {
		aecRedirect( AECToolbox::deadsureURL( 'index.php' ) );
	}
}

function pending( $option, $userid )
{
	$db = &JFactory::getDBO();

	$reason = "";

	if ( $userid > 0 ) {
		$metaUser = new metaUser( $userid );

		if ( $metaUser->hasSubscription ) {
			// Make sure this really is pending
			if ( strcmp($metaUser->objSubscription->status, 'Pending') !== 0 ) {
				return aecNotAuth();
			}
		}

		$invoices = AECfetchfromDB::InvoiceCountbyUserID( $userid );

		if ( $invoices ) {
			$invoice = AECfetchfromDB::lastUnclearedInvoiceIDbyUserID( $userid );

			$objInvoice = new Invoice( $db );
			$objInvoice->loadInvoiceNumber( $invoice );

			$params = $objInvoice->params;

			if ( isset( $params['pending_reason'] ) ) {
				$lang = JFactory::getLanguage();
				if ( $lang->hasKey( 'PENDING_REASON_' . strtoupper( $params['pending_reason'] ) ) ) {
					$reason = JText::_( 'PENDING_REASON_' . strtoupper( $params['pending_reason'] ) );
				} else {
					$reason = $params['pending_reason'];
				}
			} elseif ( strcmp( $objInvoice->method, 'transfer' ) === 0 ) {
				$reason = 'transfer';
			} else {
				$reason = 0;
			}
		} else {
			$invoice = 'none';
		}

		$document=& JFactory::getDocument();

		$document->setTitle( html_entity_decode( JText::_('PENDING_TITLE'), ENT_COMPAT, 'UTF-8' ) );

		$frontend = new HTML_frontEnd();
		$frontend->pending( $option, $metaUser->cmsUser, $invoice, $reason );
	} else {
		aecRedirect( AECToolbox::deadsureURL( 'index.php' ) );
	}
}

function subscribe( $option )
{
	global $aecConfig;

	$db = &JFactory::getDBO();

	$user = &JFactory::getUser();

	if ( defined( 'JPATH_MANIFESTS' ) && !empty( $_REQUEST['jform'] ) ) {
		foreach ( $_REQUEST['jform'] as $k => $v ) {
			$map = array( 'password1' => 'password', 'email1' => 'email' );
			
			if ( isset( $map[$k] ) ) {
				$_POST[$map[$k]] = $v;
			} else {
				$_POST[$k] = $v;
			}
		}
	}

	$task		= aecGetParam( 'task', 0, true, array( 'word', 'string' ) );
	$intro		= aecGetParam( 'intro', 0, true, array( 'word', 'int' ) );
	$usage		= aecGetParam( 'usage', 0, true, array( 'word', 'string', 'clear_nonalnum' ) );
	$group		= aecGetParam( 'group', 0, true, array( 'word', 'int' ) );
	$processor	= aecGetParam( 'processor', '', true, array( 'word', 'string', 'clear_nonalnum' ) );
	$userid		= aecGetParam( 'userid', 0, true, array( 'word', 'int' ) );
	$username	= aecGetParam( 'username', '', true, array( 'string' ) );
	$email		= aecGetParam( 'email', '', true, array( 'string', 'clear_nonemail' ) );

	$token		= aecGetParam( 'aectoken', 0, true, array( 'string' ) );

	$forget		= aecGetParam( 'forget', '', true, array( 'string' ) );

	$k2mode		= false;

	if ( $token ) {
		$temptoken = new aecTempToken( $db );
		$temptoken->getComposite();

		if ( !empty( $temptoken->content['handler'] ) ) {
			if ( $temptoken->content['handler'] == 'k2' ) {
				$k2mode = true;
			}
		}

		if ( !empty( $temptoken->content ) ) {
			$password = null;

			$details = array();

			if ( $forget == 'usage' ) {
				$details[] = 'usage';
				$details[] = 'processor';
				$details[] = 'recurring';
			}

			if ( $forget == 'userdetails' ) {
				$details[] = 'username';
				$details[] = 'email';
				$details[] = 'password';
				$details[] = 'password2';
			}

			foreach ( $temptoken->content as $k => $v ) {
				if ( !in_array( $k, $details ) ) {
					$$k = $v;

					$_POST[$k] = $v;
				}
			}

			if ( !empty( $username ) ) {
				$query = 'SELECT id'
				. ' FROM #__users'
				. ' WHERE username = \'' . $username . '\''
				;
				$db->setQuery( $query );
				$id = $db->loadResult();

				if ( !empty( $id ) ) {
					$userid = $id;

					$metaUser = new metaUser( $id );
					$metaUser->setTempAuth( $password );
				}
			}
		}
	}

	if ( !empty( $username ) && $usage ) {
		$CB = ( GeneralInfoRequester::detect_component( 'anyCB' ) );
		$AL = ( GeneralInfoRequester::detect_component( 'ALPHA' ) );
		$JS = ( GeneralInfoRequester::detect_component( 'JOMSOCIAL' ) );

		if ( !$AL && !$CB && !$JS && !$k2mode ) {
			// Joomla 1.5 Sanity Check

			// Get required system objects
			$user 		= clone(JFactory::getUser());

			$duplicationcheck = checkUsernameEmail( $username, $email );

			// Bind the post array to the user object
			if ( !$user->bind( JRequest::get('post'), 'usertype' ) || ( $duplicationcheck !== true ) ) {
				$binderror = $user->getError();

				if ( !empty( $binderror ) ) {
					JError::raiseError( 500, $user->getError() );
				} else {
					JError::raiseError( 500, $duplicationcheck );
				}

				unset($_POST);
				subscribe();
				return false;
			}

			JRequest::checkToken() or die( 'Invalid Token' );
		} elseif ( empty( $token ) ) {
			if ( isset( $_POST['username'] ) && isset( $_POST['email'] ) ) {
				$check = checkUsernameEmail( $username, $email );
				if ( $check !== true ) {
					return $check;
				}
			}
		}

		$iFactory = new InvoiceFactory( $userid, $usage, $group, $processor );
		$iFactory->confirm( $option );
	} else {
		if ( $user->id ) {
			$userid			= $user->id;
			$passthrough	= array();
		} elseif ( !empty( $userid ) && !isset( $_POST['username'] ) ) {
			$passthrough	= array();
		} elseif ( empty( $userid ) ) {
			if ( !empty( $_POST['username'] ) && !empty( $_POST['email'] ) ) {
				$check = checkUsernameEmail( $username, $email );
				if ( $check !== true ) {
					return $check;
				}
			}

			$nopass = array( 'option', 'task', 'intro', 'usage', 'group', 'processor', 'recurring', 'Itemid', 'submit_x', 'submit_y', 'userid', 'id', 'gid' );

			$passthrough = array();
			foreach ( $_POST as $k => $v ) {
				if ( in_array( $k, $nopass ) ) {
					unset( $_POST[$k] );
				} else {
					$passthrough[$k] = $v;
				}
			}
		}

		if ( !empty( $userid ) ) {
			$passthrough['userid'] = $userid;

			$password = aecGetParam( 'password', '', true, array( 'string' ) );

			if ( !empty( $password ) ) {
				$passthrough['password'] = $password;
			}
		}

		$iFactory = new InvoiceFactory( $userid, $usage, $group, $processor, null, $passthrough, false );

		if ( !$iFactory->authed ) {
			if ( !$iFactory->checkAuth( $option ) ) {
				return;
			}
		}

		if ( !empty( $iFactory->passthrough['invoice'] ) ) {
			repeatInvoice( $option, $iFactory->passthrough['invoice'], null, $userid );
		} else {
			$iFactory->create( $option, $intro, $usage, $group, $processor, 0 );
		}
	}
}

function checkUsernameEmail( $username, $email )
{
	// Implementing the Javascript check in case that is broken on the site
	$regex = preg_match( '`[\<|\>|\"|\'|\%|\;|\(|\)]`Di', '', $username );

	if ( ( strlen( $username ) < 2 ) || $regex ) {
		aecErrorAlert( JText::sprintf( 'VALID_AZ09', JText::_( 'Username' ), 2 ) );
		return JText::sprintf( 'VALID_AZ09', JText::_( 'Username' ), 2 );
	}

	$db = &JFactory::getDBO();

	$query = 'SELECT `id`'
			. ' FROM #__users'
			. ' WHERE `username` = \'' . $username . '\''
			;
	$db->setQuery( $query );

	if ( $db->loadResult() ) {
		aecErrorAlert( JText::_( 'WARNREG_INUSE' ) );
		return JText::_( 'WARNREG_INUSE' );
	}

	if ( !empty( $email ) ) {
		$query = 'SELECT `id`'
				. ' FROM #__users'
				. ' WHERE `email` = \'' . $email . '\''
				;
		$db->setQuery( $query );

		if ( $db->loadResult() ) {
			aecErrorAlert( JText::_( 'WARNREG_INUSE' ) );
			return JText::_( 'WARNREG_INUSE' );
		}
	}

	return true;
}

function confirmSubscription( $option )
{
	$user = &JFactory::getUser();

	$db = &JFactory::getDBO();

	global $aecConfig;

	$app = JFactory::getApplication();

	$userid		= aecGetParam( 'userid', 0, true, array( 'word', 'int' ) );
	$usage		= aecGetParam( 'usage', 0, true, array( 'word', 'string', 'clear_nonalnum' ) );
	$group		= aecGetParam( 'group', 0, true, array( 'word', 'int' ) );
	$processor	= aecGetParam( 'processor', '', true, array( 'word', 'string', 'clear_nonalnum' ) );
	$username	= aecGetParam( 'username', 0, true, array( 'word', 'int' ) );

	$passthrough = array();
	if ( isset( $_POST['aec_passthrough'] ) ) {
		if ( is_array( $_POST['aec_passthrough'] ) ) {
			$passthrough = $_POST['aec_passthrough'];
		} else {
			$passthrough = unserialize( base64_decode( $_POST['aec_passthrough'] ) );
		}
	}

	if ( $aecConfig->cfg['plans_first'] && !empty( $usage ) && empty( $username ) && empty( $passthrough['username'] ) && !$userid && !$user->id ) {
		if ( GeneralInfoRequester::detect_component( 'anyCB' ) ) {
			// This is a CB registration, borrowing their code to register the user
			include_once( JPATH_SITE . '/components/com_comprofiler/comprofiler.html.php' );
			include_once( JPATH_SITE . '/components/com_comprofiler/comprofiler.php' );

			registerForm( $option, $app->getCfg( 'emailpass' ), null );
		} else {
			// This is a joomla registration
			joomlaregisterForm( $option, $app->getCfg( 'useractivation' ) );
		}
	} else {
		if ( !empty( $usage ) ) {
			$iFactory = new InvoiceFactory( $userid, $usage, $group, $processor );
			$iFactory->confirm( $option );
		} else {
			subscribe( $option );
		}
	}
}

function subscriptionDetails( $option, $sub='overview', $page=0 )
{
	$db = &JFactory::getDBO();
	$user = &JFactory::getUser();

	if ( !$user->id ) {
		return notAllowed( $option );
	}

	global $aecConfig;

	$ssl		= !empty( $aecConfig->cfg['ssl_profile'] );

	// Redirect to SSL if the config requires it
	if ( !empty( $aecConfig->cfg['ssl_profile'] ) && empty( $_SERVER['HTTPS'] ) && !$aecConfig->cfg['override_reqssl'] ) {
		aecRedirect( AECToolbox::deadsureURL( "index.php?option=" . $option . "&task=subscriptiondetails", true, false ) );
		exit();
	}

	// Load metaUser and invoice data
	$metaUser	= new metaUser( $user->id );
	$invoiceno	= AECfetchfromDB::InvoiceCountbyUserID( $metaUser->userid );
	$properties	= array();

	$properties['showcheckout'] = false;

	// Do not let the user in without a subscription or at least an invoice
	if ( !$metaUser->hasSubscription && empty( $invoiceno ) ) {
		subscribe( $option );
		return;
	} elseif ( !$metaUser->hasSubscription && !empty( $invoiceno ) ) {
		$properties['showcheckout'] = AECfetchfromDB::lastUnclearedInvoiceIDbyUserID( $metaUser->userid );
	}

	// Prepare Main Tabs
	$tabs = array();
	foreach ( array( 'overview', 'invoices' ) as $fname ) {
		$tabs[$fname] = JText::_( strtoupper( 'aec_subdetails_tab_' . $fname ) );
	}

	// If we have a cart, we want to link to it
	$cart = aecCartHelper::getCartbyUserid( $metaUser->userid );

	$properties['hascart']	= $cart->id;
	$properties['alert']	= $metaUser->getAlertLevel();

	// Load a couple of basic variables
	$subscriptions	= array();
	$pplist			= array();
	$excludedprocs	= array( 'free', 'error' );
	$custom			= null;
	$mi_info		= null;

	// Start off the processor list with objSubscription
	if ( !empty( $metaUser->objSubscription->type ) ) {
		$pplist = array( $metaUser->objSubscription->type );
	}

	// The upgrade button might only show on some occasions
	$properties['upgrade_button'] = true;
	if ( $aecConfig->cfg['renew_button_never'] ) {
		$properties['upgrade_button'] = false;
	} elseif ( $aecConfig->cfg['renew_button_nolifetimerecurring'] ) {
		if ( !empty( $metaUser->objSubscription->lifetime ) ) {
			$properties['upgrade_button'] = false;
		} elseif ( $metaUser->isRecurring() ) {
			$properties['upgrade_button'] = false;
		}
	}

	// Build the User Subscription List
	$sList = $metaUser->getSecondarySubscriptions();
	if ( !empty( $metaUser->objSubscription->plan ) ) {
		$sList = array_merge( array( $metaUser->objSubscription ), $sList );
	}

	$subList = array();

	// Prepare Payment Processors attached to active subscriptions
	if ( !empty( $sList ) ) {
		foreach( $sList as $usid => $subscription ) {
			if ( empty( $subscription->id ) || empty( $subscription->plan ) ) {
				continue;
			}

			$subList[$usid] = $subscription;

			$subList[$usid]->objPlan = new SubscriptionPlan( $db );
			$subList[$usid]->objPlan->load( $subscription->plan );

			if ( !empty( $subscription->type ) ) {
				if ( !in_array( $subscription->type, $pplist ) ) {
					$pplist[] = $subscription->type;
				}
			}
		}
	}

	$pagesize = 15;

	$invoiceList = AECfetchfromDB::InvoiceIdList( $metaUser->userid, $page*$pagesize, $pagesize );

	$properties['invoice_pages'] = (int) ( $invoiceno / $pagesize);
	$properties['invoice_page'] = $page;

	$invoices = array();
	foreach ( $invoiceList as $invoiceid ) {
		$invoices[$invoiceid] = array();

		$invoice = new Invoice( $db );
		$invoice->load( $invoiceid );

		$rowstyle		= '';
		$actionsarray	= array();

		if ( !in_array( $invoice->method, $excludedprocs ) ) {
			$actionsarray[] = array( 	'task'	=> 'invoicePrint',
										'add'	=> 'invoice=' . $invoice->invoice_number,
										'text'	=> JText::_('HISTORY_ACTION_PRINT'),
										'insert' => ' target="_blank" ' );
		}

		if ( ( $invoice->transaction_date == '0000-00-00 00:00:00' ) || ( $invoice->subscr_id  ) ) {
			if ( $invoice->transaction_date == '0000-00-00 00:00:00' ) {
				$actionsarray[] = array( 	'task'	=> 'repeatPayment',
											'add'	=> 'invoice=' . $invoice->invoice_number . '&'. JUtility::getToken() .'=1',
											'text'	=> JText::_('HISTORY_ACTION_REPEAT') );

				if ( is_null( $invoice->fixed ) || !$invoice->fixed ) {
					$actionsarray[] = array('task'	=> 'cancelPayment',
											'add'	=> 'invoice=' . $invoice->invoice_number,
											'text'	=> JText::_('HISTORY_ACTION_CANCEL') );
				}
			}

			$rowstyle = ' style="background-color:#fee;"';
		}

		if ( !in_array( $invoice->method, $pplist ) ) {
			$pplist[] = $invoice->method;
		}

		$invoice->formatInvoiceNumber();

		$invoices[$invoiceid]['object']				= $invoice;
		$invoices[$invoiceid]['invoice_number']		= $invoice->invoice_number;
		$invoices[$invoiceid]['amount']				= $invoice->amount;
		$invoices[$invoiceid]['currency_code']		= $invoice->currency;
		$invoices[$invoiceid]['actions']			= $actionsarray;
		$invoices[$invoiceid]['rowstyle']			= $rowstyle;
		$invoices[$invoiceid]['transactiondate']	= $invoice->getTransactionStatus();
	}

	$pps = PaymentProcessorHandler::getObjectList( $pplist, true );

	// Get the tabs information from the plan
	if ( !empty( $subList ) ) {
		foreach( $subList as $usid => $subscription ) {
			$mis = $subscription->objPlan->micro_integrations;

			if ( !count( $mis ) ) {
				continue;
			}

			foreach ( $mis as $mi_id ) {
				if ( $mi_id ) {
					$mi = new MicroIntegration( $db );
					$mi->load( $mi_id );

					if ( !$mi->callIntegration() ) {
						continue;
					}

					$info = $mi->profile_info( $metaUser );
					if ( $info !== false ) {
						$mi_info .= '<div class="' . $mi->class_name . ' mi_' . $mi->id . '">' . $info . '</div>';
					}
				}

				$addtabs = $mi->registerProfileTabs();

				if ( empty( $addtabs ) ) {
					continue;
				}

				foreach ( $addtabs as $atk => $atv ) {
					$action = $mi->class_name . '_' . $atk;
					if ( isset( $subfields[$action] ) ) {
						continue;
					}

					$subfields[$action] = $atv;

					if ( $action == $sub ) {
						$custom = $mi->customProfileTab( $atk, $metaUser );
					}
				}
			}
		}
	}

	// Add Details tab for MI Stuff
	if ( !empty( $mi_info ) ) {
		$tabs['details'] = JText::_('AEC_SUBDETAILS_TAB_DETAILS');
	}

	$invoiceactionlink = 'index.php?option=' . $option . '&amp;task=%s&amp;%s';

	$handledsubs = array();
	foreach ( $invoiceList as $invoiceid ) {
		$invoice = $invoices[$invoiceid]['object'];

		$actionsarray = $invoices[$invoiceid]['actions'];

		if ( ( $invoice->method != 'free' ) && isset( $pps[$invoice->method] ) ) {
			$pp = $pps[$invoice->method];
		} else {
			$pp = null;
		}

		if ( !empty( $pp->info['longname'] ) ) {
			$invoices[$invoiceid]['processor'] = $pp->info['longname'];
		} else {
			$invoices[$invoiceid]['processor'] = $invoice->method;
		}

		if ( !empty( $metaUser->objSubscription->status ) ) {
			$activeortrial = ( ( strcmp( $metaUser->objSubscription->status, 'Active' ) === 0 ) || ( strcmp( $metaUser->objSubscription->status, 'Trial' ) === 0 ) );
		} else {
			$activeortrial = false;
		}

		$found = false;
		if ( !in_array( $invoice->subscr_id, $handledsubs ) && !empty( $subList ) ) {
			foreach ( $subList as $ssub ) {
				if ( $ssub->id == $invoice->subscr_id ) {
					$tempsubscription = $ssub;

					$found = true;

					$handledsubs[] = $ssub->id;
					continue;
				}
			}
		}

		if ( $found ) {
			if ( !empty( $pp->info['actions'] ) && $activeortrial ) {
				$actions = $pp->getActions( $invoice, $tempsubscription );

				foreach ( $actions as $action ) {
					$actionsarray[] = array('task'		=> 'planaction',
											'add'		=> 'action=' . $action['action'] . '&amp;subscr=' . $tempsubscription->id,
											'insert'	=> $action['insert'],
											'text'		=> $action['action'] );
				}
			}
		}

		if ( !empty( $actionsarray ) ) {
			foreach ( $actionsarray as $aid => $a ) {
				if ( is_array( $a ) ) {
					$link = AECToolbox::deadsureURL( sprintf( $invoiceactionlink, $a['task'], $a['add'] ), $ssl );

					$insert = '';
					if ( !empty( $a['insert'] ) ) {
						$insert = $a['insert'];
					}

					$actionsarray[$aid] = '<a href="' . $link . '"' . $insert . '>' . $a['text'] . '</a>';
				}
			}

			$actions = implode( ' | ', $actionsarray );
		} else {
			$actions = ' - - - ';
		}

		$invoices[$invoiceid]['actions']			= $actions;
	}

	// Get Custom Processor Tabs
	foreach ( $pps as $pp ) {
		$pptabs = $pp->getProfileTabs();

		foreach ( $pptabs as $tname => $tcontent ) {
			if ( $sub == $tname ) {
				$custom = $pp->customProfileTab( $sub, $metaUser );
			}

			$tabs[$tname] = $tcontent;
		}
	}

	$document=& JFactory::getDocument();

	$document->setTitle( html_entity_decode( JText::_('MYSUBSCRIPTION_TITLE') . ' - ' . $tabs[$sub], ENT_COMPAT, 'UTF-8' ) );

	$html = new HTML_frontEnd();
	$html->subscriptionDetails( $option, $tabs, $sub, $invoices, $metaUser, $mi_info, $subList, $custom, $properties );
}

function internalCheckout( $option, $invoice_number, $processor, $userid )
{
	$db = &JFactory::getDBO();

	$user = &JFactory::getUser();

	// Always rewrite to session userid
	if ( !empty( $user->id ) ) {
		$userid = $user->id;
	}

	$invoiceid = AECfetchfromDB::InvoiceIDfromNumber( $invoice_number, $userid );

	// Only allow a user to access existing and own invoices
	if ( $invoiceid ) {
		$iFactory = new InvoiceFactory( $userid, null, null, $processor );
		$iFactory->touchInvoice( $option, $invoice_number );
		$iFactory->internalcheckout( $option );
	} else {
		aecNotAuth();
		return;
	}
}

function repeatInvoice( $option, $invoice_number, $cart, $userid, $first=0 )
{
	$db = &JFactory::getDBO();

	$user = &JFactory::getUser();

	// Always rewrite to session userid
	if ( !empty( $user->id ) ) {
		$userid = $user->id;
	} elseif ( AECToolbox::quickVerifyUserID( $userid ) === true ) {
			// This user is not expired, so he could log in...
			return aecNotAuth();
	} else {
		$userid = AECfetchfromDB::UserIDfromInvoiceNumber( $invoice_number );
	}

	$invoiceid = null;

	if ( empty( $cart ) ) {
		$invoiceid = AECfetchfromDB::InvoiceIDfromNumber( $invoice_number, $userid );
	}

	// Only allow a user to access existing and own invoices
	if ( $invoiceid ) {
		global $aecConfig;

		if ( !isset( $_POST['invoice'] ) ) {
			$_POST['option']	= $option;
			$_POST['task']		= 'repeatPayment';
			$_POST['invoice']	= $invoice_number;
			$_POST['userid']	= $userid;
		}

		$iFactory = new InvoiceFactory( $userid );
		$iFactory->touchInvoice( $option, $invoice_number );

		$status = $iFactory->usageStatus();

		if ( $status || ( !$status && $aecConfig->cfg['allow_invoice_unpublished_item'] ) ) {
			if ( !$iFactory->checkAuth( $option ) ) {
				return aecNotAuth();
			}
		} else {
			return aecNotAuth();
		}

		return $iFactory->save( $option, null );
	} elseif ( $cart ) {
		$iFactory = new InvoiceFactory( $userid );

		$iFactory->usage = 'c.'.$cart;

		if ( !empty( $invoice_number ) ) {
			$iFactory->invoice_number = $invoice_number;
		}

		return $iFactory->confirmcart( $option, null, true );
	} else {
		return aecNotAuth();
	}
}

function cancelInvoice( $option, $invoice_number, $pending=0, $userid )
{
	$db = &JFactory::getDBO();

	$user = &JFactory::getUser();

	if ( empty( $user->id ) ) {
		if ( $userid ) {
			if ( AECToolbox::quickVerifyUserID( $userid ) === true ) {
				// This user is not expired, so he could log in...
				return aecNotAuth();
			}
		} else {
			return aecNotAuth();
		}
	} else {
		$userid = $user->id;
	}

	$invoiceid = AECfetchfromDB::InvoiceIDfromNumber( $invoice_number, $userid );

	// Only allow a user to access existing and own invoices
	if ( $invoiceid ) {
		$objInvoice = new Invoice( $db );
		$objInvoice->load( $invoiceid );

		$objInvoice->cancel();
	} else {
		aecNotAuth();
		return;
	}

	if ( $pending ) {
		pending( $option, $userid );
	} else {
		subscriptionDetails( $option, 'invoices' );
	}

}

function planaction( $option, $action, $subscr )
{
	$db = &JFactory::getDBO();

	$user = &JFactory::getUser();

	if ( !empty( $user->id ) ) {
		$userid = $user->id;

		$iFactory = new InvoiceFactory( $userid );
		$iFactory->planprocessoraction( $action, $subscr );

		subscriptionDetails( $option, 'invoices' );
	} else {
		aecNotAuth();
		return;
	}
}

function invoiceAction( $option, $action, $invoice_number )
{
	$user = &JFactory::getUser();

	if ( empty( $user->id ) ) {
		return aecNotAuth();
	} else {
		$iFactory = new InvoiceFactory( $user->id );
		$iFactory->touchInvoice( $option, $invoice_number );
		$iFactory->invoiceprocessoraction( $option, $action );

		subscriptionDetails( $option, 'invoices' );
	}
}

function InvoicePrintout( $option, $invoice )
{
	$user = &JFactory::getUser();

	if ( empty( $user->id ) ) {
		return aecNotAuth();
	} else {
		$iFactory = new InvoiceFactory( $user->id );
		$iFactory->invoiceprint( $option, $invoice );
	}
}

function InvoiceAddParams( $option )
{
	$db = &JFactory::getDBO();

	$invoice = aecGetParam( 'invoice', 0, true, array( 'word', 'string', 'clear_nonalnum' ) );

	$objinvoice = new Invoice( $db );
	$objinvoice->loadInvoiceNumber( $invoice );
	$objinvoice->savePostParams( $_POST );
	$objinvoice->check();
	$objinvoice->store();

	repeatInvoice( $option, $invoice, null, $objinvoice->userid );
}

function InvoiceMakeGift( $option )
{
	$db = &JFactory::getDBO();

	$invoice	= aecGetParam( 'invoice', 0, true, array( 'word', 'string', 'clear_nonalnum' ) );
	$user_ident	= aecGetParam( 'user_ident', 0, true, array( 'string', 'clear_nonemail' ) );

	unset( $_POST['user_ident'] );
	unset( $_REQUEST['user_ident'] );

	$objinvoice = new Invoice( $db );
	$objinvoice->loadInvoiceNumber( $invoice );

	$iFactory = new InvoiceFactory( $objinvoice->userid );
	$iFactory->touchInvoice( $option, $objinvoice->invoice_number );

	if ( $iFactory->invoice->addTargetUser( strtolower( $user_ident ) ) ) {
		$iFactory->invoice->storeload();
	}

	repeatInvoice( $option, $invoice, null, $objinvoice->userid );
}

function InvoiceRemoveGift( $option )
{
	$db = &JFactory::getDBO();

	$invoice	= aecGetParam( 'invoice', 0, true, array( 'word', 'string', 'clear_nonalnum' ) );

	$objinvoice = new Invoice( $db );
	$objinvoice->loadInvoiceNumber( $invoice );

	$iFactory = new InvoiceFactory( $objinvoice->userid );
	$iFactory->touchInvoice( $option, $objinvoice->invoice_number );

	if ( $iFactory->invoice->removeTargetUser() ) {
		$iFactory->invoice->storeload();
	}

	repeatInvoice( $option, $invoice, null, $objinvoice->userid );
}

function InvoiceRemoveGiftConfirm( $option )
{
	$db = &JFactory::getDBO();

	$invoice	= aecGetParam( 'invoice', 0, true, array( 'word', 'string', 'clear_nonalnum' ) );
	$userid		= aecGetParam( 'userid', 0, true, array( 'word', 'int' ) );
	$usage		= aecGetParam( 'usage', 0, true, array( 'word', 'string', 'clear_nonalnum' ) );
	$group		= aecGetParam( 'group', 0, true, array( 'word', 'int' ) );
	$processor	= aecGetParam( 'processor', '', true, array( 'word', 'string', 'clear_nonalnum' ) );
	$username	= aecGetParam( 'username', 0, true, array( 'word', 'int' ) );

	$objinvoice = new Invoice( $db );
	$objinvoice->loadInvoiceNumber( $invoice );

	if ( $objinvoice->removeTargetUser() ) {
		$objinvoice->storeload();
	}

	$iFactory = new InvoiceFactory( $userid, $usage, $group, $processor, $invoice );
	$iFactory->confirm( $option, $_POST );
}

function InvoiceRemoveGiftCart( $option )
{
	$db = &JFactory::getDBO();

	$invoice	= aecGetParam( 'invoice', 0, true, array( 'word', 'string', 'clear_nonalnum' ) );
	$userid		= aecGetParam( 'userid', 0, true, array( 'word', 'int' ) );

	$objinvoice = new Invoice( $db );
	$objinvoice->loadInvoiceNumber( $invoice );

	$iFactory = new InvoiceFactory( $objinvoice->userid );
	$iFactory->touchInvoice( $option, $objinvoice->invoice_number );

	if ( $iFactory->invoice->removeTargetUser() ) {
		$iFactory->invoice->storeload();
	}

	$iFactory = new InvoiceFactory( $userid );
	$iFactory->cart( $option );
}

function InvoiceAddCoupon( $option )
{
	$db = &JFactory::getDBO();

	$invoice		= aecGetParam( 'invoice', 0, true, array( 'word', 'string', 'clear_nonalnum' ) );
	$coupon_code	= aecGetParam( 'coupon_code', 0, true, array( 'word', 'string', 'clear_nonalnum' ) );

	$objinvoice = new Invoice( $db );
	$objinvoice->loadInvoiceNumber( $invoice );

	$objinvoice->addCoupon( $coupon_code );

	$objinvoice->storeload();

	repeatInvoice( $option, $invoice, null, $objinvoice->userid );
}

function InvoiceRemoveCoupon( $option )
{
	$db = &JFactory::getDBO();

	$invoice		= aecGetParam( 'invoice', 0, true, array( 'word', 'string', 'clear_nonalnum' ) );
	$coupon_code	= aecGetParam( 'coupon_code', 0, true, array( 'word', 'string', 'clear_nonalnum' ) );

	$objinvoice = new Invoice( $db );
	$objinvoice->loadInvoiceNumber( $invoice );

	$objinvoice->removeCoupon( $coupon_code );

	$objinvoice->computeAmount();

	repeatInvoice( $option, $invoice, null, $objinvoice->userid );
}

function notAllowed( $option )
{
	$db = &JFactory::getDBO();

	$user = &JFactory::getUser();

	global $aecConfig;

	if ( ( $aecConfig->cfg['customnotallowed'] != '' ) && !is_null( $aecConfig->cfg['customnotallowed'] ) ) {
		aecRedirect( $aecConfig->cfg['customnotallowed'] );
	}

	$gwnames = PaymentProcessorHandler::getInstalledNameList( true );

	if ( count( $gwnames ) && $gwnames[0] ) {
		$processors = array();
		foreach ( $gwnames as $procname ) {
			$processor = trim( $procname );
			$processors[$processor] = new PaymentProcessor();
			if ( $processors[$processor]->loadName( $processor ) ) {
				$processors[$processor]->init();
				$processors[$processor]->getInfo();
				$processors[$processor]->getSettings();
			} else {
				$short	= 'processor loading failure';
				$event	= 'When composing processor info list, tried to load processor: ' . $procname;
				$tags	= 'processor,loading,error';
				$params = array();

				$eventlog = new eventLog( $db );
				$eventlog->issue( $short, $tags, $event, 128, $params );

				unset( $processors[$processor] );
			}
		}
	} else {
		$processors = false;
	}

	$CB = ( GeneralInfoRequester::detect_component( 'anyCB' ) );

	if ( $user->id ) {
		$registerlink = AECToolbox::deadsureURL( 'index.php?option=com_acctexp&task=renewsubscription' );
		$loggedin = 1;
	} else {
		$loggedin = 0;
		if ( $CB ) {
			$registerlink = AECToolbox::deadsureURL( 'index.php?option=com_comprofiler&task=registers' );
		} else {
			$registerlink = AECToolbox::deadsureURL( 'index.php?option=com_user&view=register' );
		}
	}

	$document=& JFactory::getDocument();

	$document->setTitle( html_entity_decode( JText::_('NOT_ALLOWED_HEADLINE'), ENT_COMPAT, 'UTF-8' ) );

	$frontend = new HTML_frontEnd();
	$frontend->notAllowed( $option, $processors, $registerlink, $loggedin );
}

function backSubscription( $option )
{
	$db = &JFactory::getDBO();

	$user = &JFactory::getUser();

	// Rebuild array
	foreach ( $_POST as $key => $value ) {
		$var[$key]	= $value;
	}

	// Get other values to show in confirmForm
	$userid	= $var['userid'];
	$usage	= $var['usage'];

 	// get the payment plan
	$objplan = new SubscriptionPlan( $db );
	$objplan->load( $usage );

 	// get the user object
	$objuser = new JTableUser( $db );
	$objuser->load( $userid );

	$unset = array( 'id', 'gid', 'task', 'option', 'name', 'username', 'email', 'password', '', 'password2' );
	foreach ( $unset as $key ) {
		if ( isset($var[$key] ) ) {
			unset( $var[$key] );
		}
	}

	$document=& JFactory::getDocument();

	$document->setTitle( html_entity_decode( _REGISTER_TITLE, ENT_COMPAT, 'UTF-8' ) );

	Payment_HTML::subscribeForm( $option, $var, $objplan, null, $objuser );
}

function processNotification( $option, $processor )
{
	$db = &JFactory::getDBO();

	// Legacy naming support
	switch ( $processor ) {
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

	//aecDebug( "ResponseFunction:processNotification" );aecDebug( "GET:".json_encode( $_GET ) );aecDebug( "POST:".json_encode( $_POST ) );

	$response = array();
	$response['fullresponse'] = aecPostParamClear( $_POST );

	// parse processor notification
	$pp = new PaymentProcessor();
	if ( $pp->loadName( $processor ) ) {
		$pp->init();
		$response = array_merge( $response, $pp->parseNotification( $response['fullresponse'] ) );
	} else {
		$db = &JFactory::getDBO();
		$short	= 'processor loading failure';
		$event	= 'When receiving payment notification, tried to load processor: ' . $processor;
		$tags	= 'processor,loading,error';
		$params = array();

		$eventlog = new eventLog( $db );
		$eventlog->issue( $short, $tags, $event, 128, $params );

		return;
	}

	// Get Invoice record
	if ( !empty( $response['invoice'] ) ) {
		$id = AECfetchfromDB::InvoiceIDfromNumber( $response['invoice'] );
	} else {
		$id = false;

		$response['invoice'] = 'empty';
	}

	if ( !$id ) {
		$short	= JText::_('AEC_MSG_PROC_INVOICE_FAILED_SH');

		if ( isset( $response['null'] ) ) {
			if ( isset( $response['explanation'] ) ) {
				$short	= JText::_('AEC_MSG_PROC_INVOICE_ACTION_SH');

				$event .= $response['explanation'];
			} else {
				$event	.= JText::_('AEC_MSG_PROC_INVOICE_ACTION_EV_NULL');
			}

			$tags	.= 'invoice,processor,payment,null';
		} else {
			$event	= sprintf( JText::_('AEC_MSG_PROC_INVOICE_FAILED_EV'), $processor, $response['invoice'] )
					. ' ' . $db->getErrorMsg();
			$tags	= 'invoice,processor,payment,error';
		}

		$params = array();

		$eventlog = new eventLog( $db );

		if ( isset( $response['null'] ) ) {
			if ( isset( $response['error'] ) ) {
				$eventlog->issue( $short, $tags, $response['error'], 128, $params );
			} else {
				$eventlog->issue( $short, $tags, $event, 8, $params );
			}
		} else {
			$eventlog->issue( $short, $tags, $event, 128, $params );

			$error = 'Invoice Number not found. Invoice number provided: "' . $response['invoice'] . '"';

			$pp->notificationError( $response, $error );
		}

		return;
	} else {
		$iFactory = new InvoiceFactory( null, null, null, null, $response['invoice'] );
		$iFactory->processorResponse( $option, $response );
	}
}

function cancelPayment( $option )
{
	$db = &JFactory::getDBO();

	global $aecConfig;

	// Look whether we have a custom Cancel page
	if ( $aecConfig->cfg['customcancel'] ) {
		aecRedirect( $aecConfig->cfg['customcancel'] );
	} else {
		$document=& JFactory::getDocument();

		$document->setTitle( html_entity_decode( JText::_('CANCEL_TITLE'), ENT_COMPAT, 'UTF-8' ) );

		HTML_Results::cancel( $option );
	}
}

function aecThanks( $option, $renew, $free, $plan=null )
{
	global $aecConfig;

	$app = JFactory::getApplication();

	if ( !empty( $plan ) ) {
		if ( is_object( $plan ) ) {
			if ( !empty( $plan->params['customthanks'] ) ) {
				aecRedirect( $plan->params['customthanks'] );
			} elseif ( $aecConfig->cfg['customthanks'] ) {
				aecRedirect( $aecConfig->cfg['customthanks'] );
			}
		} else {
			return aecSimpleThanks( $option, $renew, $free );
		}
	} else {
		return aecSimpleThanks( $option, $renew, $free );
	}

	if ( $renew ) {
		$msg = JText::_('SUB_FEPARTICLE_HEAD_RENEW') . '</p><p>' . JText::_('SUB_FEPARTICLE_THANKSRENEW');
		if ( $free ) {
			$msg .= JText::_('SUB_FEPARTICLE_LOGIN');
		} else {
			$msg .= JText::_('SUB_FEPARTICLE_PROCESSPAY') . JText::_('SUB_FEPARTICLE_MAIL');
		}
	} else {
		$msg = JText::_('SUB_FEPARTICLE_HEAD') . '</p><p>' . JText::_('SUB_FEPARTICLE_THANKS');

		$msg .=  $free ? JText::_('SUB_FEPARTICLE_PROCESS') : JText::_('SUB_FEPARTICLE_PROCESSPAY');

		$msg .= $app->getCfg( 'useractivation' ) ? JText::_('SUB_FEPARTICLE_ACTMAIL') : JText::_('SUB_FEPARTICLE_MAIL');
	}

	$b = '';
	if ( $aecConfig->cfg['customtext_thanks_keeporiginal'] ) {
		$b .= '<div class="componentheading">' . JText::_('THANKYOU_TITLE') . '</div>';
	}

	if ( $aecConfig->cfg['customtext_thanks'] ) {
		$b .= $aecConfig->cfg['customtext_thanks'];
	}

	if ( $aecConfig->cfg['customtext_thanks_keeporiginal'] ) {
		$b .= '<div id="thankyou_page">' . '<p>' . $msg . '</p>' . '</div>';
	}

	$up =& $plan->params;

	$msg = "";
	if ( !empty( $up['customtext_thanks'] ) ) {
		if ( isset( $up['customtext_thanks_keeporiginal'] ) ) {
			if ( empty( $up['customtext_thanks_keeporiginal'] ) ) {
				$msg = $up['customtext_thanks'];
			} else {
				$msg = $b . $up['customtext_thanks'];
			}
		} else {
			$msg = $up['customtext_thanks'];
		}
	} else {
		$msg = $b;
	}

	$document=& JFactory::getDocument();

	$document->setTitle( html_entity_decode( JText::_('THANKYOU_TITLE'), ENT_COMPAT, 'UTF-8' ) );

	HTML_Results::thanks( $option, $msg );
}

function aecSimpleThanks( $option, $renew, $free )
{
	global $aecConfig;

	$app = JFactory::getApplication();

	// Look whether we have a custom ThankYou page
	if ( $aecConfig->cfg['customthanks'] ) {
		aecRedirect( $aecConfig->cfg['customthanks'] );
	}

	if ( $renew ) {
		$msg = JText::_('SUB_FEPARTICLE_HEAD_RENEW') . '</p><p>' . JText::_('SUB_FEPARTICLE_THANKSRENEW');
		if ( $free ) {
			$msg .= JText::_('SUB_FEPARTICLE_LOGIN');
		} else {
			$msg .= JText::_('SUB_FEPARTICLE_PROCESSPAY') . JText::_('SUB_FEPARTICLE_MAIL');
		}
	} else {
		$msg = JText::_('SUB_FEPARTICLE_HEAD') . '</p><p>' . JText::_('SUB_FEPARTICLE_THANKS');

		$msg .=  $free ? JText::_('SUB_FEPARTICLE_PROCESS') : JText::_('SUB_FEPARTICLE_PROCESSPAY');

		$msg .= $app->getCfg( 'useractivation' ) ? JText::_('SUB_FEPARTICLE_ACTMAIL') : JText::_('SUB_FEPARTICLE_MAIL');
	}

	$b = '';
	if ( $aecConfig->cfg['customtext_thanks_keeporiginal'] ) {
		$b .= '<div class="componentheading">' . JText::_('THANKYOU_TITLE') . '</div>';
	}

	if ( $aecConfig->cfg['customtext_thanks'] ) {
		$b .= $aecConfig->cfg['customtext_thanks'];
	}

	if ( $aecConfig->cfg['customtext_thanks_keeporiginal'] ) {
		$b .= '<div id="thankyou_page">' . '<p>' . $msg . '</p>' . '</div>';
	}

	$document=& JFactory::getDocument();

	$document->setTitle( html_entity_decode( JText::_('THANKYOU_TITLE'), ENT_COMPAT, 'UTF-8' ) );

	HTML_Results::thanks( $option, $b );
}

function aecErrorAlert( $text, $action='window.history.go(-1);', $mode=1 )
{
	$app = JFactory::getApplication();

	$text = strip_tags( addslashes( nl2br( $text ) ) );

	switch ( $mode ) {
		case 2:
			echo "<script>$action</script> \n";
			break;

		case 1:
		default:
			echo "<script>alert('$text'); $action</script> \n";
			echo '<noscript>';
			echo "$text\n";
			echo '</noscript>';
			break;
	}

	$app->close();
}

function aecNotAuth()
{
	$user =& JFactory::getUser();

	echo JText::_('ALERTNOTAUTH');
	if ( $user->get('id') < 1 ) {
		echo "<br />" . JText::_( 'You need to login.' );
	}
}

?>
