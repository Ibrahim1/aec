<?php
/**
 * @version $Id: acctexp.php
 * @package AEC - Account Control Expiration - Membership Manager
 * @subpackage Main Frontend
 * @copyright 2006-2008 Copyright (C) David Deutsch
 * @author David Deutsch <skore@skore.de> & Team AEC - http://www.globalnerd.org
 * @license GNU/GPL v.2 http://www.gnu.org/licenses/old-licenses/gpl-2.0.html or, at your option, any later version
 */

// Dont allow direct linking
( defined('_JEXEC') || defined( '_VALID_MOS' ) ) or die( 'Direct Access to this location is not allowed.' );

global $mainframe, $mosConfig_absolute_path, $aecConfig;

define( '_AEC_FRONTEND', 1 );

if ( !defined( '_AEC_LANG' ) ) {
	$langPath = $mosConfig_absolute_path . '/components/com_acctexp/com_acctexp_language/';
	if ( file_exists( $langPath . $GLOBALS['mosConfig_lang'] . '.php' ) ) {
		include_once( $langPath . $GLOBALS['mosConfig_lang'] . '.php' );
	} else {
		include_once( $langPath . 'english.php' );
	}
	define( '_AEC_LANG', 1 );
}

include_once( $mosConfig_absolute_path . '/administrator/components/com_acctexp/com_acctexp_language_backend/general.php' );

require_once( $mainframe->getPath( 'class',			'com_acctexp' ) );
require_once( $mainframe->getPath( 'front_html',	'com_acctexp' ) );

if ( !defined( '_EUCA_DEBUGMODE' ) ) {
	define( '_EUCA_DEBUGMODE', $aecConfig->cfg['debugmode'] );
}

if ( _EUCA_DEBUGMODE ) {
	global $eucaDebug;

	$eucaDebug = new eucaDebug();
}

$task = trim( aecGetParam( 'task' ) );
//aecDebug( $task );
if ( !empty( $task ) ) {
	switch ( strtolower( $task ) ) {
		case 'register':
			$intro = aecGetParam( 'intro', 0, true, array( 'word', 'int' ) );
			$usage = aecGetParam( 'usage', 0, true, array( 'word', 'string', 'clear_nonalnum' ) );
			$group = aecGetParam( 'group', 0, true, array( 'word', 'int' ) );

			$invoicefact = new InvoiceFactory();
			$invoicefact->create( $option, $intro, $usage, $group );
			break;

		// Catch hybrid CB registration
		case 'saveregisters':
		// Catch hybrid jUser registration
		case 'saveuserregistration':
		// Catch hybrid CMS registration
		case 'saveregistration':
		case 'subscribe':
			subscribe( $option );
			break;

		case 'rerouteregister':
			rerouteRegister( $option, $task );
			break;

		case 'confirm':
			confirmSubscription($option);
			break;

		case 'addressexception':
			$invoice	= aecGetParam( 'invoice', 0, true, array( 'word', 'string', 'clear_nonalnum' ) );
			$userid		= aecGetParam( 'userid', 0 );

			repeatInvoice( $option, $invoice, $userid );
			break;

		case 'savesubscription':
			$userid		= aecGetParam( 'userid', 0, true, array( 'word', 'int' ) );
			$usage		= aecGetParam( 'usage', 0, true, array( 'word', 'string', 'clear_nonalnum' ) );
			$group		= aecGetParam( 'group', 0, true, array( 'word', 'int' ) );
			$processor	= aecGetParam( 'processor', '', true, array( 'word', 'string', 'clear_nonalnum' ) );
			$coupon		= aecGetParam( 'coupon_code', '', true, array( 'word', 'string', 'clear_nonalnum' ) );

			$invoicefact = new InvoiceFactory( $userid, $usage, $group, $processor );
			$invoicefact->save( $option, $_POST, $coupon );
			break;

		case 'addtocart':
			$userid		= aecGetParam( 'userid', 0, true, array( 'word', 'int' ) );
			$usage		= aecGetParam( 'usage', '', true, array( 'word', 'string', 'clear_nonalnum' ) );

			$invoicefact = new InvoiceFactory( $userid );
			$invoicefact->addtoCart( $option, $usage );
			$invoicefact->cart( $option );
			break;

		case 'cart':
			$userid		= aecGetParam( 'userid', 0, true, array( 'word', 'int' ) );

			$invoicefact = new InvoiceFactory( $userid );
			$invoicefact->cart( $option );
			break;

		case 'updatecart':
			$userid		= aecGetParam( 'userid', 0, true, array( 'word', 'int' ) );

			$invoicefact = new InvoiceFactory( $userid );
			$invoicefact->updateCart( $option, $_POST );
			$invoicefact->cart( $option );
			break;

		case 'confirmcart':
			$userid		= aecGetParam( 'userid', 0, true, array( 'word', 'int' ) );
			$coupon		= aecGetParam( 'coupon_code', '', true, array( 'word', 'string', 'clear_nonalnum' ) );

			$invoicefact = new InvoiceFactory( $userid );
			$invoicefact->confirmcart( $option, $coupon );
			break;

		case 'checkout':
			$invoice	= aecGetParam( 'invoice', 0 );
			$userid		= aecGetParam( 'userid', 0, true, array( 'word', 'int' ) );

			internalcheckout( $option, $invoice, $userid );
			break;

		case 'backsubscription':
			backSubscription( $option );
			break;

		case 'thanks':
			$renew		= aecGetParam( 'renew', 0, true, array( 'word', 'int' ) );
			$free		= aecGetParam( 'free', 0, true, array( 'word', 'int' ) );

			$usage		= aecGetParam( 'usage', 0, true, array( 'word', 'string', 'clear_nonalnum' ) );

			if ( empty( $usage ) ) {
				$usage = aecGetParam( 'u', true, array( 'word', 'string', 'clear_nonalnum' ) );
			}

			$invoicefact = new InvoiceFactory();

			if ( !empty( $usage ) ) {
				$invoicefact->objUsage = new SubscriptionPlan( $database );
				$invoicefact->objUsage->load( $usage );
			}

			$invoicefact->thanks( $option, $renew, $free );
			break;

		case 'cancel':
			cancelPayment( $option );
			break;

		case 'errap':
			$usage		= aecGetParam( 'usage', true, array( 'word', 'string', 'clear_nonalnum' ) );
			$userid		= aecGetParam( 'userid', true, array( 'word', 'int' ) );
			$username	= aecGetParam( 'username', true, array( 'string', 'clear_nonalnum' ) );
			$name		= aecGetParam( 'name', true, array( 'string', 'clear_nonalnum' ) );
			$recurring	= aecGetParam( 'recurring', 0, true, array( 'word', 'int' ) );

			errorAP( $option, $usage, $userid, $username, $name, $recurring);
			break;

		case 'subscriptiondetails':
			$sub		= aecGetParam( 'sub', '', true, array( 'word', 'string' ) );

			subscriptionDetails( $option, $sub );
			break;

		case 'renewsubscription':
			$userid		= aecGetParam( 'userid', 0, true, array( 'word', 'int' ) );

			$invoicefact = new InvoiceFactory( $userid );
			$invoicefact->create( $option );
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

			repeatInvoice( $option, $invoice, $userid, $first );
			break;

		case 'cancelpayment':
			$invoice	= aecGetParam( 'invoice', 0, true, array( 'word', 'string', 'clear_nonalnum' ) );
			$pending	= aecGetParam( 'pending', 0 );
			$userid		= aecGetParam( 'userid', 0 );

			cancelInvoice( $option, $invoice, $pending, $userid );
			break;

		case 'planaction':
			$action	= aecGetParam( 'action', 0, true, array( 'word', 'int' ) );
			$subscr	= aecGetParam( 'subscr', '', true, array( 'word', 'int' ) );

			planaction( $option, $action, $subscr );
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

		// Legacy - to be deprecated after thorough check
		case 'ipn': processNotification($option, "paypal"); break;
		case 'activateft': activateFT( $option ); break;

		default:
			if ( strpos( $task, 'notification' ) > 0 ) {
				$processor = str_replace( 'notification', '', $task );

				processNotification( $option, $processor );
			} else {
				$userid		= aecGetParam( 'userid', true, array( 'word', 'int' ) );
				$expiration = aecGetParam( 'expiration', true, array( 'word', 'int' ) );

				if ( !empty( $userid ) && !empty( $userid ) ) {
					expired( $option, $userid, $expiration );
				} else {
					subscribe( $option );
				}
			}
			break;
	}
}

function hold( $option, $userid )
{
	global $mainframe;

	if ( $userid > 0 ) {
		$metaUser = new metaUser( $userid );

		$mainframe->SetPageTitle( _HOLD_TITLE );

		$frontend = new HTML_frontEnd ();
		$frontend->hold( $option, $metaUser );
	} else {
		mosRedirect( sefRelToAbs( 'index.php' ) );
	}
}

function expired( $option, $userid, $expiration )
{
	global $database, $mainframe, $aecConfig;

	if ( $userid > 0 ) {
		$metaUser = new metaUser( $userid );

		$expired = strtotime( $metaUser->objSubscription->expiration );

		if ( $metaUser->hasSubscription ) {
			$trial = (strcmp($metaUser->objSubscription->status, 'Trial' ) === 0 );
			if (!$trial) {
				$params = $metaUser->objSubscription->params;
				if ( isset( $params['trialflag'])) {
					$trial = 1;
				}
			}
		} else {
			$trial = false;
		}

		$invoices = AECfetchfromDB::InvoiceCountbyUserID( $userid );

		if ( $invoices ) {
			$invoice = AECfetchfromDB::lastUnclearedInvoiceIDbyUserID( $userid );
		} else {
			$invoice = false;
		}

		$expiration	= strftime( $aecConfig->cfg['display_date_frontend'], $expired);

		$mainframe->SetPageTitle( _EXPIRED_TITLE );

		$continue = false;
		if ( $aecConfig->cfg['continue_button'] ) {
			$status = SubscriptionPlanHandler::PlanStatus( $metaUser->focusSubscription->plan );
			if ( !empty( $status ) ) {
				$continue = true;
			}
		}

		$frontend = new HTML_frontEnd ();
		$frontend->expired( $option, $metaUser, $expiration, $invoice, $trial, $continue );
	} else {
		mosRedirect( sefRelToAbs( 'index.php' ) );
	}
}

function pending( $option, $userid )
{
	global $database, $mainframe;

	if ( $userid > 0 ) {
		$objUser = new mosUser( $database );
		$objUser->load( $userid );

		$invoices = AECfetchfromDB::InvoiceCountbyUserID( $userid );

		if ( $invoices ) {
			$invoice = AECfetchfromDB::lastUnclearedInvoiceIDbyUserID( $userid );
			$objInvoice = new Invoice( $database );
			$objInvoice->loadInvoiceNumber( $invoice );
			$params = $objInvoice->params;

			if ( isset( $params['pending_reason'] ) ) {
				if ( defined( '_PENDING_REASON_' . strtoupper( $params['pending_reason'] ) ) ) {
					$reason = constant( '_PENDING_REASON_' . strtoupper( $params['pending_reason'] ) );
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

		$mainframe->SetPageTitle( _PENDING_TITLE );

		$frontend = new HTML_frontEnd ();
		$frontend->pending( $option, $objUser, $invoice, $reason );
	} else {
		mosRedirect( sefRelToAbs( 'index.php' ) );
	}
}

function rerouteRegister( $option, $task )
{
	if ( GeneralInfoRequester::detect_component( 'anyCB' ) ) {
		global $mainframe;

		if ( $task == 'rerouteRegister' ) {
			$task = 'registers';
		}

		$savetask = $task;
		$_REQUEST['task'] = 'done';
		$_REQUEST['option'] = "com_acctexp";

		include_once( $mainframe->getCfg( 'absolute_path' ) . '/components/com_comprofiler/comprofiler.php' );
		include_once( $mainframe->getCfg( 'absolute_path' ) . '/components/com_comprofiler/comprofiler.html.php' );

		$task = $savetask;
		registerForm($option, $mainframe->getCfg( 'emailpass' ), null);

		if ( GeneralInfoRequester::detect_component( 'CB1.2' ) ) {
			global $_CB_framework;

			echo $_CB_framework->getAllJsPageCodes();
		}
	} else {
		if ( aecJoomla15check() ) {
			$usersConfig =& JComponentHelper::getParams( 'com_users' );
			$activation = $usersConfig->get('useractivation');
		} else {
			global $mainframe;
			$activation = $mainframe->getCfg( 'useractivation' );
		}

		joomlaregisterForm( $option, $activation );
	}
}

function subscribe( $option )
{
	global $my, $database, $mosConfig_uniquemail, $aecConfig;

	$intro		= aecGetParam( 'intro', 0, true, array( 'word', 'int' ) );
	$usage		= aecGetParam( 'usage', 0, true, array( 'word', 'string', 'clear_nonalnum' ) );
	$group		= aecGetParam( 'group', 0, true, array( 'word', 'int' ) );
	$processor	= aecGetParam( 'processor', '', true, array( 'word', 'string', 'clear_nonalnum' ) );
	$userid		= aecGetParam( 'userid', 0, true, array( 'word', 'int' ) );
	$username	= aecGetParam( 'username', '', true, array( 'string' ) );
	$email		= aecGetParam( 'email', '', true, array( 'string' ) );

	$isJoomla15 = aecJoomla15check();

	if ( !empty( $username ) && $usage ) {
		$CB = ( GeneralInfoRequester::detect_component( 'anyCB' ) );
		if ( $isJoomla15 && !$CB ) {
			// Joomla 1.5 Sanity Check

			// Get required system objects
			$user 		= clone(JFactory::getUser());

			$duplicationcheck = checkDuplicateUsernameEmail( $username, $email );

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
		} elseif ( !$isJoomla15 && !$CB ) {
			// Joomla 1.0 Sanity Check
			$row = new mosUser( $database );

			if (!$row->bind( $_POST, 'usertype' )) {
				mosErrorAlert( $row->getError() );
			}

			$row->name		= trim( $row->name );
			$row->email		= trim( $row->email );
			$row->username	= trim( $row->username );
			$row->password	= trim( $row->password );

			mosMakeHtmlSafe($row);

			if (!$row->check()) {
				echo "<script> alert('".html_entity_decode($row->getError())."'); window.history.go(-1); </script>\n";
				exit();
			}
		} else {
			if ( isset( $_POST['username'] ) && isset( $_POST['email'] ) ) {
				$check = checkDuplicateUsernameEmail( $username, $email );
				if ( $check !== true ) {
					return $check;
				}
			}
		}

		$invoicefact = new InvoiceFactory( $userid, $usage, $group, $processor );
		$invoicefact->confirm( $option, $_POST );
	} else {
		$CB = ( GeneralInfoRequester::detect_component( 'anyCB' ) );
		if ( $isJoomla15 && !$CB ) {
			//if Joomla15 - add the form validation JS
			JHTML::_('behavior.formvalidation');
		}

		if ( $my->id ) {
			$userid			= $my->id;
			$passthrough	= false;
		} elseif ( !empty( $userid ) && !isset( $_POST['username'] ) ) {
			$passthrough	= false;
		} elseif ( empty( $userid ) ) {
			if ( !empty( $_POST['username'] ) && !empty( $_POST['email'] ) ) {
				$check = checkDuplicateUsernameEmail( $username, $email );
				if ( $check !== true ) {
					return $check;
				}
			}

			$nopass = array( 'option', 'task', 'intro', 'usage', 'group', 'processor', 'recurring', 'Itemid', 'submit_x', 'submit_y', 'userid' );

			$passafter = array();
			foreach ( $nopass as $varname ) {
				if ( isset( $_POST[$varname] ) ) {
					$passafter[$varname] = $_POST[$varname];
					unset( $_POST[$varname] );
				}
			}

			if ( !empty( $_POST ) ) {
				if ( empty( $_POST['username'] ) || empty( $_POST['email'] ) || empty( $_POST['password'] ) ) {
					if ( !aecJoomla15check() ) {
						mosErrorAlert( _REGWARN_INUSE );
						return false;
					} else {
						mosErrorAlert( JText::_( 'WARNREG_INUSE' ) );
						return JText::_( 'WARNREG_INUSE' );
					}
				}

				$passthrough = array();
				foreach ( $_POST as $ke => $va ) {
					if ( is_array( $va ) ) {
						foreach ( $va as $con ) {
							$passthrough[] = array( $ke . '[]', $con );
						}
					} else {
						$passthrough[] = array( $ke, $va );
					}
				}
			} else {
				$passthrough = false;
			}


			if ( !empty( $passafter ) ) {
				foreach ( $passafter as $varname => $varcontent ) {
					$_POST[$varname] = $varcontent;
				}
			}
		}

		$invoicefact = new InvoiceFactory( $userid, $usage, $group, $processor );
		$invoicefact->create( $option, $intro, $usage, $group, $processor, 0, $passthrough );
	}
}

function checkDuplicateUsernameEmail( $username, $email )
{
	global $database, $mosConfig_uniquemail;

	$query = 'SELECT `id`'
			. ' FROM #__users'
			. ' WHERE `username` = \'' . $username . '\''
			;
	$database->setQuery( $query );
	if ( $database->loadResult() ) {
		if ( !aecJoomla15check() ) {
			mosErrorAlert( _REGWARN_INUSE );
			return false;
		} else {
			mosErrorAlert( JText::_( 'WARNREG_INUSE' ) );
			return JText::_( 'WARNREG_INUSE' );
		}
	}

	if ( !empty( $email ) ) {
		if ( $mosConfig_uniquemail || aecJoomla15check() ) { // J1.5 forces unique email
			// check for existing email
			$query = 'SELECT `id`'
					. ' FROM #__users'
					. ' WHERE `email` = \'' . $email . '\''
					;
			$database->setQuery( $query );
			if ( $database->loadResult() ) {
				if ( !aecJoomla15check() ) {
					mosErrorAlert( _REGWARN_EMAIL_INUSE );
					return _REGWARN_EMAIL_INUSE;
				} else {
					mosErrorAlert( JText::_( 'WARNREG_EMAIL_INUSE' ) );
					return JText::_( 'WARNREG_EMAIL_INUSE' );
				}
			}
		}
	}

	return true;
}

function confirmSubscription( $option )
{
	global $mosConfig_absolute_path, $mosConfig_emailpass, $mosConfig_useractivation, $mainframe, $my, $aecConfig;

	$userid		= aecGetParam( 'userid', 0, true, array( 'word', 'int' ) );
	$usage		= aecGetParam( 'usage', 0, true, array( 'word', 'string', 'clear_nonalnum' ) );
	$group		= aecGetParam( 'group', 0, true, array( 'word', 'int' ) );
	$processor	= aecGetParam( 'processor', '', true, array( 'word', 'string', 'clear_nonalnum' ) );
	$username	= aecGetParam( 'username', 0, true, array( 'word', 'int' ) );

	if ( $aecConfig->cfg['plans_first'] && !empty( $usage ) && !$username && !$userid && !$my->id ) {
		if ( GeneralInfoRequester::detect_component( 'anyCB' ) ) {
			// This is a CB registration, borrowing their code to register the user
			include_once( $mosConfig_absolute_path . '/components/com_comprofiler/comprofiler.html.php' );
			include_once( $mosConfig_absolute_path . '/components/com_comprofiler/comprofiler.php' );

			registerForm( $option, $mosConfig_emailpass, null );
		} else {
			// This is a joomla registration
			joomlaregisterForm( $option, $mainframe->getCfg( 'useractivation' ) );
		}
	} else {
		$invoicefact = new InvoiceFactory( $userid, $usage, $group, $processor );
		$invoicefact->confirm( $option, $_POST );
	}
}

function subscriptionDetails( $option, $sub='' )
{
	global $database, $my, $mainframe, $aecConfig;

	if ( !$my->id ) {
		notAllowed( $option );
	} else {
		if ( !empty( $aecConfig->cfg['ssl_profile'] ) && empty( $_SERVER['HTTPS'] ) && !$aecConfig->cfg['override_reqssl'] ) {
			mosRedirect( AECToolbox::deadsureURL( "index.php?option=" . $option . "&task=subscriptiondetails", true, false ) );
			exit();
		};

		$metaUser = new metaUser( $my->id );

		if ( !$metaUser->hasSubscription ) {
			subscribe( $option );
			return;
		}

		$sf = array( 'overview', 'invoices', 'details' );

		$subfields = array();
		foreach ( $sf as $fname ) {
			$subfields[$fname] = constant( strtoupper( '_aec_subdetails_tab_' . $fname ) );
		}

		if ( empty( $sub ) ) {
			$sub = 'overview';
		}

		$custom = null;

		switch ( strtolower( $metaUser->objSubscription->type ) ) {
			case 'free':
			case 'none':
			case 'transfer':
			case '':
				$pp = false;
				break;

			default:
				$pp = new PaymentProcessor();
				if ( $pp->loadName( $metaUser->objSubscription->type ) ) {
					$pp->init();
					$pp->getInfo();

					$addtabs = $pp->registerProfileTabs();

					if ( !empty( $addtabs ) ) {
						foreach ( $addtabs as $atk => $atv ) {
							$action = $pp->processor_name . '_' . $atk;
							if ( !isset( $subfields[$action] ) ) {
								$subfields[$action] = $atv;

								if ( $action == $sub ) {
									$custom = $pp->customProfileTab( $atk, $metaUser );
								}
							}
						}
					}
				} else {
					$pp = false;
				}
				break;
		}

		if ( strcmp( $metaUser->objSubscription->status, 'Cancelled' ) == 0 ) {
			$recurring = 0;
		} else {
			$recurring = $metaUser->objSubscription->recurring;
		}

		if ( $aecConfig->cfg['renew_button_never'] ) {
			$upgrade_button = false;
		} elseif ( $aecConfig->cfg['renew_button_nolifetimerecurring'] ) {
			if ( $recurring || $metaUser->objSubscription->lifetime ) {
				$upgrade_button = false;
			} else {
				$upgrade_button = true;
			}
		} else {
			$upgrade_button = true;
		}

		$mi_info = '';

		$subscriptions = array();

		$actionprocs = array();

		if ( $metaUser->objSubscription->plan ) {
			$selected_plan = new SubscriptionPlan( $database );
			$selected_plan->load( $metaUser->objSubscription->plan );

			$mis = $selected_plan->micro_integrations;

			if ( count( $mis ) ) {
				foreach ( $mis as $mi_id ) {
					if ( $mi_id ) {
						$mi = new MicroIntegration( $database );
						$mi->load( $mi_id );

						if ( !$mi->callIntegration() ) {
							continue;
						}

						$info = $mi->profile_info( $my->id );
						if ( $info !== false ) {
							$mi_info .= $info;
						}
					}

					$addtabs = $mi->registerProfileTabs();

					if ( !empty( $addtabs ) ) {
						foreach ( $addtabs as $atk => $atv ) {
							$action = $mi->class_name . '_' . $atk;
							if ( !isset( $subfields[$action] ) ) {
								$subfields[$action] = $atv;

								if ( $action == $sub ) {
									$custom = $mi->customProfileTab( $atk, $metaUser );
								}
							}
						}
					}
				}
			}

			if ( $pp != false ) {
				if ( !empty( $pp->info['actions'] ) && ( ( strcmp( $metaUser->objSubscription->status, 'Active' ) === 0 ) || ( strcmp( $metaUser->objSubscription->status, 'Trial' ) === 0 )) ) {
					$actions = $pp->info['actions'];

					$selected_plan->proc_actions = array();
					foreach ( $actions as $action ) {
						$selected_plan->proc_actions[] = '<a href="' . AECToolbox::deadsureURL( 'index.php?option=com_acctexp&amp;task=planaction&amp;action=' . $action . '&amp;subscr=' . $metaUser->objSubscription->id, !empty( $aecConfig->cfg['ssl_profile'] ) ) . '">' . $action . '</a>';
					}
				}
			}

			$subscriptions[] = $selected_plan;

			if ( !empty( $selected_plan->proc_actions ) ) {
				$actionprocs[$metaUser->objSubscription->id] = count( $subscriptions ) - 1;
			}
		}

		if ( empty( $mi_info ) ) {
			unset( $subfields['details'] );
		}

		$alert = array();
		if ( strcmp( $metaUser->objSubscription->status, 'Excluded' ) === 0 ) {
			$alert['level']		= 3;
			$alert['daysleft']	= 'excluded';
		} elseif ( !empty( $metaUser->objSubscription->lifetime ) ) {
			$alert['level']		= 3;
			$alert['daysleft']	= 'infinite';
		} else {
			$alert = $metaUser->objSubscription->GetAlertLevel();
		}

		$user_subscriptions = $metaUser->getSecondarySubscriptions();

		$processors = array();

		if ( !empty( $user_subscriptions ) ) {
			foreach( $user_subscriptions as $subscription ) {
				if ( empty( $subscription->id ) || empty( $subscription->plan ) ) {
					continue;
				}

				$secondary_plan = new SubscriptionPlan( $database );
				$secondary_plan->load( $subscription->plan );

				if ( !isset( $processors[$subscription->type] ) ) {
					$spp = new PaymentProcessor();
					if ( $spp->loadName( $subscription->type ) ) {
						$spp->init();
						$spp->getInfo();

						$addtabs = $spp->registerProfileTabs();

						if ( !empty( $addtabs ) ) {
							foreach ( $addtabs as $atk => $atv ) {
								$action = $spp->processor_name . '_' . $atk;
								if ( !isset( $subfields[$action] ) ) {
									$subfields[$action] = $atv;

									if ( $action == $sub ) {
										$custom = $spp->customProfileTab( $atk, $metaUser );
									}
								}
							}
						}
					} else {
						$spp = false;
					}

					$processors[$subscription->type] = $spp;
				} else {
					$spp = $processors[$subscription->type];
				}


				if ( !empty( $spp->info['actions'] ) && ( ( strcmp( $metaUser->objSubscription->status, 'Active' ) === 0 ) || ( strcmp( $metaUser->objSubscription->status, 'Trial' ) === 0 ) ) ) {
					$actions = $spp->info['actions'];

					$secondary_plan->proc_actions = array();
					foreach ( $actions as $action ) {
						$secondary_plan->proc_actions[] = '<a href="' . AECToolbox::deadsureURL( 'index.php?option=com_acctexp&amp;task=planaction&amp;action=' . $action . '&amp;subscr=' . $subscription->id, !empty( $aecConfig->cfg['ssl_profile'] ) ) . '">' . $action . '</a>';
					}
				}

				$subscriptions[] = $secondary_plan;

				if ( !empty( $secondary_plan->proc_actions ) ) {
					$actionprocs[$subscription->id] = count( $subscriptions ) - 1;
				}
			}
		}

		// count number of payments from user
		$query = 'SELECT count(*)'
				. ' FROM #__acctexp_invoices'
				. ' WHERE `userid` = \'' . $my->id . '\''
				. ' AND `active` = \'1\''
				;
		$database->setQuery( $query );
		$rows_total	= $database->loadResult();

		$rows_limit	= 20;
		$min_limit	= ( $rows_total > $rows_limit ) ? ( $rows_total - $rows_limit ) : 0;

		// get payments from user
		$query = 'SELECT `id`'
				. ' FROM #__acctexp_invoices'
				. ' WHERE `userid` = \'' . $my->id . '\''
				. ' AND `active` = \'1\''
				. ' ORDER BY `transaction_date` DESC'
				. ' LIMIT ' . $min_limit . ',' . $rows_limit
				;
		$database->setQuery( $query );
		$rows = $database->loadResultArray();
		if ( $database->getErrorNum() ) {
			echo $database->stderr();
			return false;
		}

		$invoices = array();
		foreach ( $rows as $rowid ) {
			$row = new Invoice( $database );
			$row->load( $rowid );

			$hassubstuff = array_key_exists( $row->subscr_id, $actionprocs );

			if ( ( $row->transaction_date == '0000-00-00 00:00:00' ) || ( $row->subscr_id  ) || $hassubstuff ) {
				$transactiondate = 'uncleared';

				if ( !empty( $row->params ) && is_array( $row->params ) ) {
					if ( in_array( 'pending_reason', $row->params ) ) {
						$array = array();
						foreach ( $row->params as $chunk ) {
							$k = explode( '=', $chunk );
							$array[$k[0]] = stripslashes( $k[1] );
						}

						if ( isset( $array['pending_reason'] ) ) {
							if ( defined( '_PAYMENT_PENDING_REASON_' . strtoupper( $array['pending_reason'] ) ) ) {
								$transactiondate = constant( '_PAYMENT_PENDING_REASON_' . strtoupper( $array['pending_reason'] ) );
							} else {
								$transactiondate = $array['pending_reason'];
							}
						}
					}
				}

				$actionsarray = array();

				if ( $row->transaction_date == '0000-00-00 00:00:00' ) {
					$actionsarray[] = '<a href="'
					.  AECToolbox::deadsureURL( 'index.php?option=' . $option . '&amp;task=repeatPayment&amp;invoice='
					. $row->invoice_number, !empty( $aecConfig->cfg['ssl_profile'] ) ) . '">' . _HISTORY_ACTION_REPEAT
					. '</a>';

					if ( is_null( $row->fixed ) || !$row->fixed ) {
						$actionsarray[] = '<a href="'
						. AECToolbox::deadsureURL( 'index.php?option=' . $option . '&amp;task=cancelPayment&amp;invoice='
						. $row->invoice_number, !empty( $aecConfig->cfg['ssl_profile'] ) ) . '">' . _HISTORY_ACTION_CANCEL
						. '</a>';
					}
				} else {
					$transactiondate = HTML_frontend::DisplayDateInLocalTime( $row->transaction_date );
				}

				if ( $hassubstuff && !empty( $subscriptions[$actionprocs[$row->subscr_id]]->proc_actions ) && is_array( $subscriptions[$actionprocs[$row->subscr_id]]->proc_actions ) ) {
					$actionsarray = array_merge( $subscriptions[$actionprocs[$row->subscr_id]]->proc_actions, $actionsarray );
				}

				$actions = implode( ' | ', $actionsarray );

				$rowstyle = ' style="background-color:#fee;"';
			} else {
				$transactiondate	= HTML_frontend::DisplayDateInLocalTime( $row->transaction_date );
				$actions			= '- - -';
				$rowstyle			= '';
			}

			if ( !isset( $processors[$row->method] ) ) {
				$processors[$row->method] = new PaymentProcessor();
				if ( $processors[$row->method]->loadName( $row->method ) ) {
					$processors[$row->method]->init();
					$processors[$row->method]->getInfo();

					$processor = $processors[$row->method]->info['longname'];
				} else {
					$processor = $row->method;
				}
			} else {
				$processor = $processors[$row->method]->info['longname'];
			}

			$row->formatInvoiceNumber();

			$invoices[$rowid]['invoice_number']	= $row->invoice_number;
			$invoices[$rowid]['amount']			= $row->amount;
			$invoices[$rowid]['currency_code']	= $row->currency;
			$invoices[$rowid]['processor']		= $processor;
			$invoices[$rowid]['actions']		= $actions;
			$invoices[$rowid]['rowstyle']		= $rowstyle;
			$invoices[$rowid]['transactiondate'] = $transactiondate;
		}

		if ( empty( $invoices ) ) {
			unset( $sf[array_search( 'invoices', $sf )] );
		}

		$cart = aecCartHelper::getCartbyUserid( $metaUser->userid );

		$hascart = $cart->id;

		$mainframe->SetPageTitle( _MYSUBSCRIPTION_TITLE . ' - ' . $subfields[$sub] );

		$html = new HTML_frontEnd();
		$html->subscriptionDetails( $option, $subfields, $sub, $invoices, $metaUser, $upgrade_button, $pp, $mi_info, $alert, $subscriptions, $custom, $hascart );
	}
}

function internalCheckout( $option, $invoice_number, $userid )
{
	global $database, $my;

	// Always rewrite to session userid
	if ( !empty( $my->id ) ) {
		$userid = $my->id;
	}

	$invoiceid = AECfetchfromDB::InvoiceIDfromNumber( $invoice_number, $userid );

	// Only allow a user to access existing and own invoices
	if ( $invoiceid ) {
		$invoicefact = new InvoiceFactory( $userid );
		$invoicefact->touchInvoice( $option, $invoice_number );
		$invoicefact->internalcheckout( $option );
	} else {
		mosNotAuth();
		return;
	}
}

function repeatInvoice( $option, $invoice_number, $userid, $first=0 )
{
	global $database, $my;

	// Always rewrite to session userid
	if ( !empty( $my->id ) ) {
		$userid = $my->id;
	}

	$invoiceid = AECfetchfromDB::InvoiceIDfromNumber( $invoice_number, $userid );

	// Only allow a user to access existing and own invoices
	if ( $invoiceid ) {
		if ( !isset( $_POST['invoice'] ) ) {
			$_POST['option']	= $option;
			$_POST['task']		= 'repeatPayment';
			$_POST['invoice']	= $invoice_number;
			$_POST['userid']	= $userid;
		}

		$invoicefact = new InvoiceFactory( $userid );
		$invoicefact->touchInvoice( $option, $invoice_number );
		$invoicefact->checkout( $option, !$first );
	} else {
		mosNotAuth();
		return;
	}
}

function cancelInvoice( $option, $invoice_number, $pending=0, $userid )
{
	global $database, $my;

	if ( empty($my->id ) ) {
		if ( $userid ) {
			if ( AECToolbox::quickVerifyUserID( $userid ) === true ) {
				// This user is not expired, so he could log in...
				mosNotAuth();
				return;
			}
		} else {
			mosNotAuth();
		}
	} else {
		$userid = $my->id;
	}

	$invoiceid = AECfetchfromDB::InvoiceIDfromNumber( $invoice_number, $userid );

	// Only allow a user to access existing and own invoices
	if ( $invoiceid ) {
		$objInvoice = new Invoice( $database );
		$objInvoice->load( $invoiceid );

		if ( !$objInvoice->fixed ) {
			$objInvoice->active = 0;
			$objInvoice->params = array( 'deactivated' => 'cancel' );
			$objInvoice->check();
			$objInvoice->store();
		}
	} else {
		mosNotAuth();
		return;
	}

	if ( $pending ) {
		pending( $option, $userid );
	} else {
		subscriptionDetails( $option );
	}

}

function planaction( $option, $action, $subscr )
{
	global $database, $my;

	// Always rewrite to session userid
	if ( !empty( $my->id ) ) {
		$userid = $my->id;

		$invoicefact = new InvoiceFactory( $userid );
		$invoicefact->planprocessoraction( $action, $subscr );
	} else {
		mosNotAuth();
		return;
	}
}

function InvoiceAddParams( $option )
{
	global $database;

	$invoice = aecGetParam( 'invoice', 0, true, array( 'word', 'string', 'clear_nonalnum' ) );

	$objinvoice = new Invoice( $database );
	$objinvoice->loadInvoiceNumber( $invoice );
	$objinvoice->savePostParams( $_POST );
	$objinvoice->check();
	$objinvoice->store();

	repeatInvoice( $option, $invoice, $objinvoice->userid );
}

function InvoiceAddCoupon( $option )
{
	global $database;

	$invoice		= aecGetParam( 'invoice', 0, true, array( 'word', 'string', 'clear_nonalnum' ) );
	$coupon_code	= aecGetParam( 'coupon_code', 0, true, array( 'word', 'string', 'clear_nonalnum' ) );

	$objinvoice = new Invoice( $database );
	$objinvoice->loadInvoiceNumber( $invoice );
	$objinvoice->addCoupon( $coupon_code );
	$objinvoice->storeload();

	repeatInvoice( $option, $invoice, $objinvoice->userid );
}

function InvoiceRemoveCoupon( $option )
{
	global $database;

	$invoice		= aecGetParam( 'invoice', 0, true, array( 'word', 'string', 'clear_nonalnum' ) );
	$coupon_code	= aecGetParam( 'coupon_code', 0, true, array( 'word', 'string', 'clear_nonalnum' ) );

	$objinvoice = new Invoice( $database );
	$objinvoice->loadInvoiceNumber( $invoice );
	$objinvoice->removeCoupon( $coupon_code );
	$objinvoice->storeload();

	repeatInvoice( $option, $invoice, $objinvoice->userid );
}

function notAllowed( $option )
{
	global $database, $mainframe, $aecConfig, $my;

	if ( ( $aecConfig->cfg['customnotallowed'] != '' ) && !is_null( $aecConfig->cfg['customnotallowed'] ) ) {
		mosRedirect( $aecConfig->cfg['customnotallowed'] );
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
				// TODO: Log error
				unset( $processors[$processor] );
			}
		}
	} else {
		$processors = false;
	}

	$CB = ( GeneralInfoRequester::detect_component( 'anyCB' ) );

	if ( $my->id ) {
		$registerlink = AECToolbox::deadsureURL( 'index.php?option=com_acctexp&amp;task=renewsubscription' );
		$loggedin = 1;
	} else {
		$loggedin = 0;
		if ( $CB ) {
			$registerlink = AECToolbox::deadsureURL( 'index.php?option=com_comprofiler&amp;task=registers' );
		} else {
			$registerlink = AECToolbox::deadsureURL( 'index.php?option=com_registration&amp;task=register' );
		}
	}

	$mainframe->SetPageTitle( _NOT_ALLOWED_HEADLINE );

	$frontend = new HTML_frontEnd ();
	$frontend->notAllowed( $option, $processors, $registerlink, $loggedin );
}

function backSubscription( $option )
{
	global $mainframe, $database, $my, $acl;

	// Rebuild array
	foreach ( $_POST as $key => $value ) {
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
	foreach ( $unset as $key ) {
		if ( isset($var[$key] ) ) {
			unset( $var[$key] );
		}
	}

	$mainframe->SetPageTitle( _REGISTER_TITLE );
	Payment_HTML::subscribeForm( $option, $var, $objplan, null, $objuser );
}

function processNotification( $option, $processor )
{
	global $database;

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

	// aecDebug( "ResponseFunction:processNotification" );aecDebug( "GET:".json_encode( $_GET ) );aecDebug( "POST:".json_encode( $_POST ) );

	$response = array();
	$response['fullresponse'] = $_POST;

	// parse processor notification
	$pp = new PaymentProcessor( $processor );
	if ( $pp->loadName( $processor ) ) {
		$pp->init();
		$response = array_merge( $response, $pp->parseNotification( $_POST ) );
	} else {
		return;
		// TODO: Log error
	}

	// Get Invoice record
	$id = AECfetchfromDB::InvoiceIDfromNumber( $response['invoice'] );

	if ( !$id ) {
		$short	= _AEC_MSG_PROC_INVOICE_FAILED_SH;
		$event	= sprintf( _AEC_MSG_PROC_INVOICE_FAILED_EV, $processor, $objInvoice->invoice_number )
				. ' ' . $database->getErrorMsg();
		$tags	= 'invoice,processor,payment,error';
		$params = array( 'invoice_number' => $objInvoice->invoice_number );

		$eventlog = new eventLog( $database );
		$eventlog->issue( $short, $tags, $event, 128, $params );

		$error = 'Invoice Number not found. Invoice number provided: "' . $response['invoice'] . '"';

		$pp->notificationError( $response, $error );
		return;
	} else {
		$invoicefact = new InvoiceFactory( null, null, null, null, $response['invoice'] );
		$invoicefact->processorResponse( $option, $response );
	}
}

function errorAP( $option, $usage, $userid, $username, $name, $recurring )
{
	Payment_HTML::errorAP( $option, $usage, $userid, $username, $name, $recurring );
}

function cancelPayment( $option )
{
	global $database, $aecConfig, $mainframe;

	$userid = aecGetParam( 'itemnumber', true, array( 'word', 'int' ) );
	// The user cancel the payment operation
	// But user is already created as blocked on database, so we need to delete it
	$obj = new mosUser( $database );
	$obj->load( $userid );

	if ( $obj->id ) {
		if ( (  (strcasecmp( $obj->type, 'Super Administrator' ) != 0 ) || ( strcasecmp( $obj->type, 'superadministrator' ) != 0 ) ) && ( strcasecmp( $obj->type, 'Administrator' ) != 0 ) && ( $obj->block == 1 ) ) {
			// If the user is not blocked this can be a false cancel
			// So just delete user if he is blocked and is not an administrator or super admnistrator
			$obj->delete();
		}
	}

	// Look whether we have a custom Cancel page
	if ( $aecConfig->cfg['customcancel'] ) {
		mosRedirect( $aecConfig->cfg['customcancel'] );
	} else {
		$mainframe->SetPageTitle( _CANCEL_TITLE );

		HTML_Results::cancel( $option );
	}
}

?>
