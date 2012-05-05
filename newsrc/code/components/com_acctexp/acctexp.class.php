<?php
/**
 * @version $Id: acctexp.class.php
 * @package AEC - Account Control Expiration - Membership Manager
 * @subpackage Core Class
 * @copyright 2006-2012 Copyright (C) David Deutsch
 * @author David Deutsch <skore@valanx.org> & Team AEC - http://www.valanx.org
 * @license GNU/GPL v.3 http://www.gnu.org/licenses/gpl.html or, at your option, any later version
 */

// Dont allow direct linking
( defined('_JEXEC') || defined( '_VALID_MOS' ) ) or die( 'Direct Access to this location is not allowed.' );

global $aecConfig;

// Make sure we are compatible with php4
if ( version_compare( phpversion(), '5.0' ) < 0 ) {
	include_once( JPATH_SITE . '/components/com_acctexp/lib/php4/php4.php' );
}

// and php5.0<>5.2
if (  ( version_compare( phpversion(), '5.0') >= 0 )  && ( version_compare( phpversion(), '5.2' ) < 0 ) ) {
	include_once( JPATH_SITE . '/components/com_acctexp/lib/php4/phplt5_2.php' );
}

// Get old language file names
JLoader::register('JTableUser', JPATH_LIBRARIES.DS.'joomla'.DS.'database'.DS.'table'.DS.'user.php');

$langlist = array(	'com_acctexp' => JPATH_SITE,
					'com_acctexp.microintegrations' => JPATH_SITE,
					'com_acctexp.processors' => JPATH_SITE
					);

aecLanguageHandler::loadList( $langlist );

define( '_AEC_VERSION', '1.0beta' );
define( '_AEC_REVISION', '5093' );

if ( !class_exists( 'paramDBTable' ) ) {
	include_once( JPATH_SITE . '/components/com_acctexp/lib/eucalib/eucalib.php' );
}

// Load teh moniez
include_once( JPATH_SITE . '/components/com_acctexp/lib/mammontini/mammontini.php' );

function aecDebug( $text, $level = 128 )
{
	aecQuickLog( 'debug', 'debug', $text, $level );
}

function aecQuickLog( $short, $tags, $text, $level = 128 )
{
	$db = &JFactory::getDBO();

	$eventlog = new eventLog( $db );
	if ( empty( $text ) ) {
		$eventlog->issue( $short, $tags, "[[EMPTY]]", $level );
	} elseif ( is_array( $text ) || is_object( $text ) ) {
		// Due to some weird error, json_encode sometimes throws a notice - even on a proper array or object
		$eventlog->issue( $short, $tags, @json_encode( $text ), $level );
	} elseif ( is_string( $text ) || is_bool( $text ) || is_float( $text ) ) {
		$eventlog->issue( $short, $tags, $text, $level );
	} elseif ( is_numeric( $text ) ) {
		$eventlog->issue( $short, $tags, $text, $level );
	} else {
		$eventlog->issue( $short, $tags, "[[UNSUPPORTED TYPE]]", $level );
	}
}

function aecGetParam( $name, $default='', $safe=false, $safe_params=array() )
{
	$return = JArrayHelper::getValue( $_REQUEST, $name, $default );

	if ( !isset( $_REQUEST[$name] ) && !isset( $_POST[$name] ) ) {
		return $default;
	}

	if ( !is_array( $return ) ) {
		$return = trim( $return );
	}

	if ( !empty( $_POST[$name] ) ) {
		if ( is_array( $_POST[$name] ) && !is_array( $return ) ) {
			$return = $_POST[$name];
		} elseif ( empty( $return ) ) {
			$return = $_POST[$name];
		}
	}

	if ( empty( $return ) && !empty( $_REQUEST[$name] ) ) {
		$return = $_REQUEST[$name];
	}

	if ( $safe ) {
		if ( is_array( $return ) ) {
			foreach ( $return as $k => $v ) {
				$return[$k] = aecEscape( $v, $safe_params );
			}
		} else {
			$return = aecEscape( $return, $safe_params );
		}

	}

	return $return;
}

function aecEscape( $value, $safe_params )
{
	if ( is_array( $value ) ) {
		$array = array();
		foreach ( $value as $k => $v ) {
			$array[$k] = aecEscape( $v, $safe_params );
		}

		return $array;
	}

	$db = &JFactory::getDBO();

	$regex = "#{aecjson}(.*?){/aecjson}#s";

	// find all instances of json code
	$matches = array();
	preg_match_all( $regex, $value, $matches, PREG_SET_ORDER );

	if ( count( $matches ) ) {
		$value = str_replace( $matches, array(''), $value );
	}

	if ( get_magic_quotes_gpc() ) {
		$return = stripslashes( $value );
	} else {
		$return = $value;
	}

	if ( in_array( 'clear_nonemail', $safe_params ) ) {
		if ( strpos( $value, '@' ) === false ) {
			if ( !in_array( 'clear_nonalnum', $safe_params ) ) {
				// This is not a valid email adress to begin with, so strip everything hazardous
				$safe_params[] = 'clear_nonalnum';
			}
		} else {
			$array = explode('@', $return, 2);

			$username = preg_replace( '/[^a-z0-9._+-]+/i', '', $array[0] );
			$domain = preg_replace( '/[^a-z0-9.-]+/i', '', $array[1] );

			$return = $username.'@'.$domain;
		}
	}

	if ( in_array( 'clear_nonalnum', $safe_params ) ) {
		$return = preg_replace( "/[^a-z0-9@._+-]/i", '', $return );
	}

	if ( !empty( $safe_params ) ) {
		foreach ( $safe_params as $param ) {
			switch ( $param ) {
				case 'word':
					$e = strpos( $return, ' ' );
					if ( $e !== false ) {
						$r = substr( $return, 0, $e );
					} else {
						$r = $return;
					}
					break;
				case 'badchars':
					$r = preg_replace( "#[<>\"'%;()&]#i", '', $return );
					break;
				case 'int':
					$r = (int) $return;
					break;
				case 'string':
					$r = (string) $return;
					break;
				case 'float':
					$r = (float) $return;
					break;
			}

			$return = $r;
		}

	}

	return $db->getEscaped( $return );
}

function aecPostParamClear( $array, $safe=false, $safe_params=array( 'string', 'badchars' ) )
{
	$cleararray = array();
	foreach ( $array as $key => $value ) {
		$cleararray[$key] = aecGetParam( $key, $safe, $safe_params );
	}

	return $cleararray;
}

function aecRedirect( $url, $msg=null, $class=null )
{
	$app = JFactory::getApplication();

	$app->redirect( $url, $msg, $class );
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

function getView( $view, $args=null )
{
	global $aecConfig;

	$db = &JFactory::getDBO();

	$user = &JFactory::getUser();

	$metaUser = null;
	if ( $user->id ) {
		$userid = $user->id;

		$metaUser = new metaUser( $user->id );
	} else {
		$userid		= aecGetParam( 'userid', 0, true, array( 'word', 'int' ) );

		$metaUser = new metaUser( $userid );
	}

	$app = JFactory::getApplication();

	$option = 'com_acctexp';

	$dbtmpl = new configTemplate($db);
	$dbtmpl->loadDefault();

	$tmpl = $dbtmpl->template;

	if ( !empty( $dbtmpl->settings ) ) {
		$tmpl->cfg = array_merge( $aecConfig->cfg, $dbtmpl->settings );
	} else {
		$tmpl->cfg = $aecConfig->cfg;
	}

	$tmpl->option = 'com_acctexp';
	$tmpl->metaUser = $metaUser;
	$tmpl->system_template = $app->getTemplate();

	$tmpl->template = $dbtmpl->name;
	$tmpl->view = $view;

	$tmpl->paths['base'] = JPATH_SITE . '/components/com_acctexp/tmpl';
	$tmpl->paths = array(	'default' => $tmpl->paths['base'] . '/default',
							'current' => $tmpl->paths['base'] . '/' . $tmpl->template,
							'site' => JPATH_SITE . '/templates/' . $tmpl->system_template . '/html/com_acctexp'
						);

	$hphp = '/'.$view.'/html.php';
	$tphp = '/'.$view.'/tmpl/'.$view.'.php';

	if ( !empty( $args ) ) {
		foreach ( $args as $n => $v ) {
			$$n = $v;
		}
	}

	if ( file_exists( $tmpl->paths['site'].$hphp ) ) {
		include( $tmpl->paths['site'].$hphp );
	} elseif ( file_exists( $tmpl->paths['current'].$hphp ) ) {
		include( $tmpl->paths['current'].$hphp );
	} elseif ( file_exists( $tmpl->paths['default'].$hphp ) ) {
		include( $tmpl->paths['default'].$hphp );
	} elseif ( file_exists( $tmpl->paths['site'].$tphp ) ) {
		include( $tmpl->paths['site'].$tphp );
	} elseif ( file_exists( $tmpl->paths['current'].$tphp ) ) {
		include( $tmpl->paths['current'].$tphp );
	} elseif ( file_exists( $tmpl->paths['default'].$tphp ) ) {
		include( $tmpl->paths['default'].$tphp );
	}
}

class metaUser
{
	/** @var int */
	var $userid				= null;
	/** @var object */
	var $cmsUser			= null;
	/** @var object */
	var $objSubscription	= null;
	/** @var int */
	var $hasSubscription	= null;

	function metaUser( $userid, $subscriptionid=null )
	{
		$db = &JFactory::getDBO();

		if ( empty( $userid ) && !empty( $subscriptionid ) ) {
			$userid = AECfetchfromDB::UserIDfromSubscriptionID( $subscriptionid );
		}

		$this->meta = new metaUserDB( $db );
		$this->meta->loadUserid( $userid );

		$this->cmsUser = false;
		$this->hasCBprofile = false;
		$this->hasJSprofile = false;
		$this->userid = 0;

		$this->hasSubscription = 0;
		$this->objSubscription = null;
		$this->focusSubscription = null;

		if ( $userid ) {
			$this->cmsUser = new JTableUser( $db );
			$this->cmsUser->load( $userid );

			$this->userid = $userid;

			if ( !empty( $subscriptionid ) ) {
				$aecid = $subscriptionid;
			} else {
				$aecid = AECfetchfromDB::SubscriptionIDfromUserID( $userid );
			}

			if ( $aecid ) {
				$this->objSubscription = new Subscription( $db );
				$this->objSubscription->load( $aecid );
				$this->focusSubscription = new Subscription( $db );
				$this->focusSubscription->load( $aecid );
				$this->hasSubscription = 1;
				$this->temporaryRFIX();
			}
		}
	}

	function dummyUser( $passthrough )
	{
		$this->hasSubscription = false;

		$this->cmsUser = new stdClass();
		$this->cmsUser->gid = 29;

		if ( is_array( $passthrough ) && !empty( $passthrough ) && !empty( $passthrough['username'] ) ) {
			$cpass = $passthrough;
			unset( $cpass['id'] );

			$cmsfields = array( 'name', 'username', 'email', 'password' );

			// Create dummy CMS user
			foreach( $cmsfields as $cmsfield ) {
				foreach ( $cpass as $k => $v ) {
					if ( $k == $cmsfield ) {
						$this->cmsUser->$cmsfield = $v;
						unset( $cpass[$k] );
					}
				}
			}

			if ( empty( $this->cmsUser->name ) && ( !empty( $cpass['firstname'] ) || !empty( $cpass['middlename'] ) || !empty( $cpass['lastname'] ) ) ) {
				$names = array( 'firstname', 'middlename', 'lastname' );

				$namearray = array();
				foreach ( $names as $n ) {
					if ( !empty( $cpass[$n] ) ) {
						$namearray[] = $cpass[$n];
					}
				}

				$this->cmsUser->name = implode( " ", $namearray );
			}

			// Create dummy CB/CBE user
			if ( GeneralInfoRequester::detect_component( 'anyCB' ) ) {
				$this->hasCBprofile = 1;
				$this->cbUser = new stdClass();

				foreach ( $cpass as $cbfield => $cbvalue ) {
					if ( is_array( $cbvalue ) ) {
						$this->cbUser->$cbfield = implode( ';', $cbvalue );
					} else {
						$this->cbUser->$cbfield = $cbvalue;
					}
				}
			}

			if ( isset( $this->_incomplete ) ) {
				unset( $this->_incomplete );
			}
		} else {
			$this->_incomplete = true;

			return true;
		}
	}

	function temporaryRFIX()
	{
		if ( !empty( $this->meta->plan_history->used_plans ) ) {
			$used_plans = $this->meta->plan_history->used_plans;
		} else {
			$used_plans = array();
		}

		$previous_plan = $this->meta->getPreviousPlan();

		$this->focusSubscription->used_plans = $used_plans;
		$this->focusSubscription->previous_plan = $previous_plan;
		$this->objSubscription->used_plans = $used_plans;
		$this->objSubscription->previous_plan = $previous_plan;
	}

	function getCMSparams( $name )
	{
		$userParams = new JParameter( $this->cmsUser->params );

		if ( is_array( $name ) ) {
			$array = array();

			foreach ( $name as $n ) {
				$array[$n] = $userParams->get( $n );
			}

			return $array;
		} else {
			return (int) $userParams->get( $name );
		}
	}

	function setCMSparams( $array )
	{
		$db = &JFactory::getDBO();

		$params = explode( "\n", $this->cmsUser->params );

		$oldarray = array();
		foreach ( $params as $chunk ) {
			$k = explode( '=', $chunk, 2 );
			if ( !empty( $k[0] ) ) {
				// Strip slashes, but preserve special characters
				$oldarray[$k[0]] = stripslashes( str_replace( array( '\n', '\t', '\r' ), array( "\n", "\t", "\r" ), $k[1] ) );
			}
			unset( $k );
		}

		foreach ( $array as $n => $v  ) {
			$oldarray[$n] = $v;
		}

		$params = array();
		foreach ( $array as $key => $value ) {
			if ( !is_null( $key ) ) {
				if ( is_array( $value ) ) {
					$temp = implode( ';', $value );
					$value = $temp;
				}

				if ( get_magic_quotes_gpc() ) {
					$value = stripslashes( $value );
				}

				$value = $db->getEscaped( $value );

				$params[] = $key . '=' . $value;
			}
		}

		$this->cmsUser->params = implode( "\n", $params );

		$this->cmsUser->check();
		return $this->cmsUser->store();
	}

	function getTempAuth()
	{
		$return = false;

		// Only authorize if user IP is matching and the grant is not expired
		if ( isset( $this->meta->custom_params['tempauth_exptime'] ) && isset( $this->meta->custom_params['tempauth_ip'] ) ) {
			$app = JFactory::getApplication();

			if ( ( $this->meta->custom_params['tempauth_ip'] == $_SERVER['REMOTE_ADDR'] ) && ( $this->meta->custom_params['tempauth_exptime'] >= ( (int) gmdate('U') ) ) ) {
				return true;
			}
		}

		return false;
	}

	function setTempAuth( $password=false )
	{
		global $aecConfig;

		$app = JFactory::getApplication();

		if ( !empty( $this->cmsUser->password ) ) {
			// Make sure we catch traditional and new joomla passwords
			if ( ( $password !== false ) ) {
				if ( strpos( $this->cmsUser->password, ':') === false ) {
					if ( $this->cmsUser->password != md5( $password ) ) {
						return false;
					}
				} else {
					list( $hash, $salt ) = explode(':', $this->cmsUser->password);
					$cryptpass = md5( $password . $salt );
					if ( $hash != $cryptpass ) {
						return false;
					}
				}
			}
		}

		// Set params
		$params = array();
		$params['tempauth_ip'] = $_SERVER['REMOTE_ADDR'];
		$params['tempauth_exptime'] = strtotime( '+' . max( 10, $aecConfig->cfg['temp_auth_exp'] ) . ' minutes', ( (int) gmdate('U') ) );

		// Save params either to subscription or to _user entry
		$this->meta->addCustomParams( $params );
		$this->meta->storeload();

		return true;
	}

	function getAllSubscriptions()
	{
		$db = &JFactory::getDBO();

		$query = 'SELECT `id`'
				. ' FROM #__acctexp_subscr'
				. ' WHERE `userid` = \'' . (int) $this->userid . '\''
				;
		$db->setQuery( $query );
		return $db->loadResultArray();
	}

	function getAllCurrentSubscriptionsInfo()
	{
		$db = &JFactory::getDBO();

		$query = 'SELECT `a`.`id`, `a`.`plan`, `a`.`expiration`, `a`.`recurring`, `a`.`lifetime`, `b`.`name`'
				. ' FROM #__acctexp_subscr AS a'
				. ' INNER JOIN #__acctexp_plans AS b ON a.plan = b.id'
				. ' WHERE `userid` = \'' . (int) $this->userid . '\''
				. ' AND `status` != \'Expired\''
				. ' AND `status` != \'Closed\''
				. ' AND `status` != \'Hold\''
				. ' ORDER BY `lastpay_date` DESC'
				;
		$db->setQuery( $query );
		return $db->loadObjectList();
	}

	function getAllCurrentSubscriptions()
	{
		$db = &JFactory::getDBO();

		$query = 'SELECT `id`'
				. ' FROM #__acctexp_subscr'
				. ' WHERE `userid` = \'' . (int) $this->userid . '\''
				. ' AND `status` != \'Expired\''
				. ' AND `status` != \'Closed\''
				. ' AND `status` != \'Hold\''
				. ' ORDER BY `lastpay_date` DESC'
				;
		$db->setQuery( $query );
		return $db->loadResultArray();
	}

	function getAllCurrentSubscriptionPlans()
	{
		$db = &JFactory::getDBO();

		$query = 'SELECT `plan`'
				. ' FROM #__acctexp_subscr'
				. ' WHERE `userid` = \'' . (int) $this->userid . '\''
				. ' AND `status` != \'Expired\''
				. ' AND `status` != \'Closed\''
				. ' AND `status` != \'Hold\''
				. ' ORDER BY `lastpay_date` DESC'
				;
		$db->setQuery( $query );
		return $db->loadResultArray();
	}

	function getSecondarySubscriptions( $simple=false )
	{
		$db = &JFactory::getDBO();

		$query = 'SELECT `id`' . ( $simple ? '' : ', `status`, `plan`, `type`, `expiration`, `recurring`, `lifetime`' )
				. ' FROM #__acctexp_subscr'
				. ' WHERE `userid` = \'' . (int) $this->userid . '\''
				. ' AND `primary` = \'0\''
				. ' AND `status` != \'Expired\''
				. ' AND `status` != \'Closed\''
				. ' ORDER BY `lastpay_date` DESC'
				;
		$db->setQuery( $query );
		if ( $simple ) {
			return $db->loadResultArray();
		} else {
			return $db->loadObjectList();
		}
	}

	function getMIlist()
	{
		$plans = $this->getAllCurrentSubscriptionPlans();

		$milist = array();
		foreach ( $plans as $plan_id ) {
			$mis = microIntegrationHandler::getMIsbyPlan( $plan_id );

			if ( !empty( $mis ) ) {
				foreach ( $mis as $mi ) {
					if ( array_key_exists( $mi, $milist ) ) {
						$milist[$mi]++;
					} else {
						$milist[$mi] = 1;
					}
				}
			}
		}

		return $milist;
	}

	function getMIcount( $mi_id )
	{
		$plans = $this->getAllCurrentSubscriptionPlans();

		$count = 0;
		foreach ( $plans as $plan_id ) {
			$mis = microIntegrationHandler::getMIsbyPlan( $plan_id );

			if ( !empty( $mis ) ) {
				foreach ( $mis as $mi ) {
					if ( $mi == $mi_id ) {
						$count++;
					}
				}
			}
		}

		return $count;
	}

	function procTriggerCreate( $user, $payment, $usage )
	{
		$db = &JFactory::getDBO();

		global $aecConfig;

		$app = JFactory::getApplication();

		// Create a new cmsUser from user details - only allowing basic details so far
		// Try different types of usernames to make sure we have a unique one
		$usernames = array( $user['username'],
							$user['username'] . substr( md5( $user['name'] ), 0, 3 ),
							$user['username'] . substr( md5( ( $user['name'] . ( (int) gmdate('U') ) ) ), 0, 3 )
							);

		// Iterate through semi-random and pseudo-random usernames until a non-existing is found
		$id = 1;
		$k = 0;
		while ( $id ) {
			$username = $usernames[min( $k, ( count( $usernames ) - 1 ) )];

			$query = 'SELECT `id`'
					. ' FROM #__users'
					. ' WHERE `username` = \'' . $username . '\''
					;
			$db->setQuery( $query );

			$id = $db->loadResult();
			$k++;
		}

		$var['id'] 			= 0;
		$var['gid'] 		= 0;
		$var['username']	= $username;
		$var['name']		= $user['name'];
		$var['email']		= $user['email'];
		$var['password']	= $user['password'];

		$userid = AECToolbox::saveUserRegistration( 'com_acctexp', $var, true );

		// Create a new invoice with $invoiceid as secondary ident
		$invoice = new Invoice( $db );
		$invoice->create( $userid, $usage, $payment['processor'], $payment['secondary_ident'] );

		// return nothing, the invoice will be handled by the second ident anyways
		return;
	}

	function establishFocus( $payment_plan, $processor='none', $silent=false, $bias=null )
	{
		$db = &JFactory::getDBO();

		if ( !is_object( $payment_plan ) ) {
			$planid = $payment_plan;

			$payment_plan = new SubscriptionPlan( $db );
			$payment_plan->load( $planid );

			if ( empty( $payment_plan->id ) ) {
				return false;
			}
		}

		if ( is_object( $this->focusSubscription ) ) {
			if ( $this->focusSubscription->plan == $payment_plan->id ) {
				return 'existing';
			}
		}

		$plan_params = $payment_plan->params;

		if ( !isset( $plan_params['make_primary'] ) ) {
			$plan_params['make_primary'] = 1;
		}

		if ( $plan_params['make_primary'] && $this->hasSubscription ) {
			if ( $this->objSubscription->primary ) {
				$this->focusSubscription = $this->objSubscription;

				return 'existing';
			} else {
				$existing_record = $this->objSubscription->getSubscriptionID( $this->userid );

				if ( $existing_record ) {
					$this->objSubscription = new Subscription( $db );
					$this->objSubscription->load( $existing_record );

					$this->focusSubscription = $this->objSubscription;

					return 'existing';
				}
			}
		}

		// If we are not dealing with a primary (or an otherwise unclear situation),
		// we need to figure out how to prepare the switch

		$existing_record = 0;
		$existing_status = false;

		// Check whether a record exists
		if ( $this->hasSubscription ) {
			$existing_record = $this->focusSubscription->getSubscriptionID( $this->userid, $payment_plan->id, $plan_params['make_primary'], false, $bias );

			if ( !empty( $existing_record ) ) {
				$query = 'SELECT `status`'
						. ' FROM #__acctexp_subscr'
						. ' WHERE `id` = \'' . (int) $existing_record . '\''
						;
				$db->setQuery( $query );
				$existing_status = $db->loadResult();
			}
		} else {
			$existing_record = 0;
		}

		$return = false;

		// To be failsafe, a new subscription may have to be added in here
		if ( empty( $this->hasSubscription ) || !$plan_params['make_primary'] || $plan_params['update_existing'] ) {
			if ( !empty( $existing_record ) && ( ( $existing_status == 'Trial' ) || ( $existing_status == 'Pending' ) || $plan_params['update_existing'] || $plan_params['make_primary'] ) ) {
				// Update existing non-primary subscription
				if ( $this->focusSubscription->id !== $existing_record ) {
					$this->focusSubscription = new Subscription( $db );
					$this->focusSubscription->load( $existing_record );
				}

				$return = 'existing';
			} else {
				if ( !empty( $this->hasSubscription ) ) {
					$existing_parent = $this->focusSubscription->getSubscriptionID( $this->userid, $plan_params['standard_parent'], null );
				} else {
					$existing_parent = false;
				}

				// Create a root new subscription
				if ( empty( $this->hasSubscription ) && !$plan_params['make_primary'] && !empty( $plan_params['standard_parent'] ) && empty( $existing_parent ) ) {
					$this->objSubscription = new Subscription( $db );
					$this->objSubscription->load( 0 );

					if ( $this->objSubscription->createNew( $this->userid, 'none', 1, 1, $plan_params['standard_parent'] ) ) {
						$this->objSubscription->applyUsage( $plan_params['standard_parent'], 'none', $silent, 0 );
					}
				} elseif ( !$plan_params['make_primary'] && !empty( $plan_params['standard_parent'] ) && $existing_parent ) {
					$this->objSubscription = new Subscription( $db );
					$this->objSubscription->load( $existing_parent );

					if ( $this->objSubscription->is_expired() ) {
						$this->objSubscription->applyUsage( $plan_params['standard_parent'], 'none', $silent, 0 );
					}
				}

				// Create new subscription
				$this->focusSubscription = new Subscription( $db );
				$this->focusSubscription->load( 0 );
				$this->focusSubscription->createNew( $this->userid, $processor, 1, $plan_params['make_primary'], $payment_plan->id );
				$this->hasSubscription = 1;

				if ( $plan_params['make_primary'] ) {
					$this->objSubscription = clone( $this->focusSubscription );
				}

				$return = 'new';
			}

		}

		if ( empty( $this->objSubscription ) && !empty( $this->focusSubscription ) ) {
			$this->objSubscription = clone( $this->focusSubscription );
		}

		$this->temporaryRFIX();

		return $return;
	}

	function moveFocus( $subscrid )
	{
		$db = &JFactory::getDBO();

		$subscription = new Subscription( $db );
		$subscription->load( $subscrid );

		// If Subscription exists, move the focus to that one
		if ( $subscription->id ) {
			if ( $subscription->userid == $this->userid ) {
				$this->focusSubscription = $subscription;
				$this->temporaryRFIX();
				return true;
			} else {
				// This subscription does not belong to the user!
				return false;
			}
		} else {
			// This subscription does not exist
			return false;
		}
	}

	function loadSubscriptions()
	{
		$db = &JFactory::getDBO();

		// Get all the users subscriptions
		$query = 'SELECT id'
				. ' FROM #__acctexp_subscr'
				. ' WHERE `userid` = \'' . (int) $this->userid . '\''
				;
		$db->setQuery( $query );
		$subscrids = $db->loadResultArray();

		if ( count( $subscrids ) > 1 ) {
			$this->allSubscriptions = array();

			foreach ( $subscrids as $subscrid ) {
				$subscription = new Subscription( $db );
				$subscription->load( $subscrid );

				$this->allSubscriptions[] = $subscription;
			}

			return true;
		} else {
			// There is only the one that is probably already loaded
			$this->allSubscriptions = false;
			return false;
		}
	}

	function instantGIDchange( $gid, $removegid=array(), $sessionextra=null )
	{
		if ( empty( $this->cmsUser ) ) {
			return null;
		}

		// Always protect last administrator
		if ( $this->isAdmin() ) {
			if ( aecACLhandler::countAdmins() < 2 ) {
				return false;
			}
		}

		$shandler = new aecSessionHandler();

		$shandler->instantGIDchange( $this->userid, $gid, $removegid, $sessionextra );
	}

	function isAdmin()
	{
		if ( defined( 'JPATH_MANIFESTS' ) ) {
			$acl = &JFactory::getACL();

			$allowed_groups = aecACLhandler::getAdminGroups( true );

			$usergroups = $acl->getGroupsByUser( $this->cmsUser->id );

			if ( count( array_intersect( $allowed_groups, $usergroups ) ) ) {
				return true;
			}
		} else {
			if ( ( $this->cmsUser->gid == 24 ) || ( $this->cmsUser->gid == 25 ) ) {
				return true;
			}
		}

		return false;
	}

	function hasGroup( $group )
	{
		if ( is_array( $group ) ) {
			$usergroups = $this->getGroups();

			foreach ( $group as $g ) {
				if ( in_array( $g, $usergroups ) ) {
					return true;
				}
			}

			return false;
		} else {
			return in_array( $group, $this->getGroups() );
		}
		
	}

	function getGroups()
	{
		if ( defined( 'JPATH_MANIFESTS' ) ) {
			$db = &JFactory::getDBO();

			$query = 'SELECT `group_id`'
					. ' FROM #__user_usergroup_map'
					. ' WHERE `user_id`= \'' . (int) $this->userid . '\''
					;
			$db->setQuery( $query );

			$groups = $db->loadResultArray();

			$lower = array();
			foreach ( $groups as $group ) {
				$lower = array_merge( $lower, aecACLhandler::getLowerACLGroups( $group ) );
			}

			$groups = array_merge( $groups, $lower );

			return array_unique( $groups );
		} else {
			return array_merge( aecACLhandler::getLowerACLGroups( $this->cmsUser->gid ), array( $this->cmsUser->gid ) );
		}
	}

	function is_renewing()
	{
		if ( !empty( $this->meta ) ) {
			return ( $this->meta->is_renewing() ? 1 : 0 );
		} else {
			return 0;
		}
	}

	function loadJProfile()
	{
		$db = &JFactory::getDBO();

		$query = 'SELECT DISTINCT `profile_key`'
				. ' FROM #__user_profiles';
		$db->setQuery( $query );
		$pkeys = $db->loadResultArray();

		$query = 'SELECT `profile_key`, `profile_value`'
				. ' FROM #__user_profiles'
				. ' WHERE `user_id` = \'' . $this->userid . '\'';
		$db->setQuery( $query );
		$objects = $db->loadObjectList();

		$fields = array();
		foreach ( $pkeys as $k ) {
			if ( !empty( $objects ) ) {
				foreach ( $objects as $oid => $object ) {
					if ( $k == $object->profile_key ) {
						$fields[str_replace( ".", "_", $k )] = $object->profile_value;

						unset( $objects[$oid] );
					}
				}
			} else {
				$fields[str_replace( ".", "_", $k )] = "";
			}
		}

		$this->jProfile = $fields;

		if ( !empty( $this->jProfile ) ) {
			$this->hasJProfile = true;
		}
	}

	function loadCBuser()
	{
		$db = &JFactory::getDBO();

		$query = 'SELECT *'
			. ' FROM #__users AS u, #__comprofiler AS ue'
			. ' WHERE `user_id` = \'' . (int) $this->userid . '\' AND u.id = ue.id';
		$db->setQuery( $query );
		$this->cbUser = $db->loadObject();

		if ( is_object( $this->cbUser ) ) {
			$this->hasCBprofile = true;
		}
	}

	function loadJSuser()
	{
		$db = &JFactory::getDBO();

		$query = 'SELECT `id`'
				. ' FROM #__community_fields'
				. ' WHERE `type` != \'group\''
				;
		$db->setQuery( $query );
		$ids = $db->loadResultArray();

		$query = 'SELECT `field_id`, `value`'
				. ' FROM #__community_fields_values'
					. ' WHERE `field_id` IN (' . implode( ',', $ids ) . ')'
					. ' AND `user_id` = \'' . (int) $this->userid . '\'';
				;
		$db->setQuery( $query );
		$fields = $db->loadObjectList();

		$this->jsUser = $fields;

		if ( !empty( $this->jsUser ) ) {
			$this->hasJSprofile = true;
		}
	}

	function explodeName()
	{
		if ( !empty( $this->cmsUser->name ) ) {
			return $this->_explodeName( $this->cmsUser->name );
		} else {
			return $this->_explodeName( "" );
		}
	}

	function _explodeName( $name )
	{
		$return = array();
		$return['first_first']	= "";
		$return['first']		= "";
		$return['last']			= "";

		// Explode Name
		if ( !empty( $name ) ) {
			if ( is_array( $name ) ) {
				$namearray	= $name;
			} else {
				$namearray	= explode( " ", $name );
			}

			$return['first_first']	= $namearray[0];
			$maxname				= count($namearray) - 1;
			$return['last']			= $namearray[$maxname];

			unset( $namearray[$maxname] );

			$return['first']			= implode( ' ', $namearray );

			if ( empty( $return['first'] ) ) {
				$return['first'] = $return['first_first'];
			}
		}

		return $return;
	}

	function CustomRestrictionResponse( $restrictions )
	{
		$s = array();
		$n = 0;
		if ( is_array( $restrictions ) && !empty( $restrictions ) ) {
			foreach ( $restrictions as $restriction ) {
				$check1 = AECToolbox::rewriteEngine( $restriction[0], $this );
				$check2 = AECToolbox::rewriteEngine( $restriction[2], $this );
				$eval = $restriction[1];

				if ( ( $check1 === $restriction[0] ) && ( reWriteEngine::isRWEstring( $restriction[0] ) ) ) {
					$check1 = null;
				}

				if ( ( $check2 === $restriction[2] ) && ( reWriteEngine::isRWEstring( $restriction[2] ) ) ) {
					$check2 = null;
				}

				$s['customchecker'.$n] = AECToolbox::compare( $eval, $check1, $check2 );
				$n++;
			}
		}

		return $s;
	}

	function permissionResponse( $restrictions )
	{
		if ( is_array( $restrictions ) && !empty( $restrictions ) ) {
			$return = array();
			foreach ( $restrictions as $name => $value ) {
				// Might be zero, so do an expensive check
				if ( !is_null( $value ) && !( $value === "" ) ) {
					// Switch flag for inverted call
					if ( strpos( $name, '_excluded' ) !== false ) {
						$invert = true;
						$name = str_replace( '_excluded', '', $name );
					} else {
						$invert = false;
					}

					// Convert values to array or explode to array if none
					if ( !is_array( $value ) ) {
						if ( strpos( $value, ';' ) !== false ) {
							$check = explode( ';', $value );
						} else {
							$check = array( (int) $value );
						}
					} else {
						$check = $value;
					}

					$status = false;

					switch ( $name ) {
						// Check for set userid
						case 'userid':
							if ( is_object( $this->cmsUser ) ) {
								if ( $this->cmsUser->id === $value ) {
									$status = true;
								}
							}
							break;
						// Check for a certain GID
						case 'fixgid':
							if ( is_object( $this->cmsUser ) ) {
								if ( $this->hasGroup( $value ) ) {
									$status = true;
								}
							}
							break;
						// Check for Minimum GID
						case 'mingid':
							if ( is_object( $this->cmsUser ) ) {
								if ( $this->hasGroup( $value ) ) {
									$status = true;
								}
							}
							break;
						// Check for Maximum GID
						case 'maxgid':
							if ( is_object( $this->cmsUser ) ) {
								$groups = aecACLhandler::getHigherACLGroups( $value );
								if ( !$this->hasGroup( $groups ) ) {
									$status = true;
								}
							} else {
								// New user, so will always pass a max GID test
								$status = true;
							}
							break;
						// Check whether the user is currently in the right plan
						case 'plan_present':
							if ( $this->hasSubscription ) {
								$subs = $this->getAllCurrentSubscriptionPlans();

								foreach ( $subs as $subid ) {
									if ( in_array( (int) $subid, $check ) ) {
										$status = true;
									}
								}
							} else {
								if ( in_array( 0, $check ) ) {
									// "None" chosen, so will always pass if new user
									$status = true;
								}
							}
							break;
						// Check whether the user was in the correct plan before
						case 'plan_previous':
							if ( $this->hasSubscription ) {
								$previous = (int) $this->getPreviousPlan();

								if (
									( in_array( $previous, $check ) )
									|| ( ( in_array( 0, $check ) ) && is_null( $previous ) )
									) {
									$status = true;
								}
							} else {
								if ( in_array( 0, $check ) ) {
									// "None" chosen, so will always pass if new user
									$status = true;
								}
							}
							break;
						// Check whether the user has used the right plan before
						case 'plan_overall':
							if ( $this->hasSubscription ) {
								$subs = $this->getAllCurrentSubscriptionPlans();

								$array = $this->meta->getUsedPlans();
								foreach ( $check as $v ) {
									if ( ( !empty( $array[(int) $v] ) || in_array( $v, $subs ) ) ) {
										$status = true;
									}
								}
							} else {
								if ( in_array( 0, $check ) ) {
									// "None" chosen, so will always pass if new user
									$status = true;
								}
							}
							break;
						// Check whether the user has used the plan at least a certain number of times
						case 'plan_amount_min':
							if ( $this->hasSubscription ) {
								$subs = $this->getAllCurrentSubscriptionPlans();

								$usage = $this->meta->getUsedPlans();

								if ( !is_array( $value ) ) {
									$check = array( $value );
								}

								foreach ( $check as $v ) {
									$c = explode( ',', $v );

									// Make sure we have an entry if the user is currently in this plan
									if ( in_array( $c[0], $subs ) ) {
										if ( !isset( $usage[(int) $c[0]] ) ) {
											$usage[(int) $c[0]] = 1;
										}
									}

									if ( isset( $usage[(int) $c[0]] ) ) {
										if ( $usage[(int) $c[0]] >= (int) $c[1] ) {
											$status = true;
										}
									}
								}
							}
							break;
						// Check whether the user has used the plan at max a certain number of times
						case 'plan_amount_max':
							if ( $this->hasSubscription ) {
								$subs = $this->getAllCurrentSubscriptionPlans();

								$usage = $this->meta->getUsedPlans();

								if ( !is_array( $value ) ) {
									$check = array( $value );
								}

								foreach ( $check as $v ) {
									$c = explode( ',', $v );

									// Make sure we have an entry if the user is currently in this plan
									if ( in_array( $c[0], $subs ) ) {
										if ( !isset( $usage[(int) $c[0]] ) ) {
											$usage[(int) $c[0]] = 1;
										}
									}

									if ( isset( $usage[(int) $c[0]] ) ) {
										if ( $usage[(int) $c[0]] <= (int) $c[1] ) {
											$status = true;
										}
									}
								}
							} else {
								// New user will always pass max plan amount test
								$status = true;
							}
							break;
						default:
							// If it's not there, it's super OK!
							$status = true;
							break;
					}
				}

				// Swap if inverted and reestablish name
				if ( $invert ) {
					$name .= '_excluded';
					$return[$name] = !$status;
				} else {
					$return[$name] = $status;
				}
			}

			return $return;
		} else {
			return array();
		}
	}

	function usedCoupon ( $couponid, $type )
	{
		$db = &JFactory::getDBO();

		$query = 'SELECT `usecount`'
				. ' FROM #__acctexp_couponsxuser'
				. ' WHERE `userid` = \'' . $this->userid . '\''
				. ' AND `coupon_id` = \'' . $couponid . '\''
				. ' AND `coupon_type` = \'' . $type . '\''
				;
		$db->setQuery( $query );
		$usecount = $db->loadResult();

		if ( $usecount ) {
			return $usecount;
		} else {
			return false;
		}
	}

	function getProperty( $key, $test=false )
	{
		return AECToolbox::getObjectProperty( $this, $key, $test );
	}

	function getPreviousPlan()
	{
		$current = $this->getAllCurrentSubscriptions();

		if ( empty( $current ) ) {
			return null;
		} else {
			return $this->meta->getPreviousPlan();
		}
	}

	function getUserMIs()
	{
		$db = &JFactory::getDBO();

		if ( empty( $this->focusSubscription->id ) ) {
			return array();
		}

		$focus = $this->focusSubscription->id;

		$return = array();
		if ( !empty( $this->objSubscription->plan ) ) {
			$selected_plan = new SubscriptionPlan( $db );
			$selected_plan->load( $this->objSubscription->plan );

			$mis = $selected_plan->getMicroIntegrations();

			if ( empty( $mis ) ) {
				$mis = array();
			}

			$sec = $this->getSecondarySubscriptions( true );

			if ( !empty( $sec ) ) {
				foreach ( $sec as $pid ) {
					if ( $this->moveFocus( $pid ) ) {
						$selected_plan = new SubscriptionPlan( $db );
						$selected_plan->load( $this->focusSubscription->plan );

						$miis = $selected_plan->getMicroIntegrations();

						if ( !empty( $miis ) ) {
							$mis = array_merge( $mis, $miis );
						}
					}
				}
			}

			if ( count( $mis ) ) {
				$mis = array_unique( $mis );

				foreach ( $mis as $mi_id ) {
					if ( $mi_id ) {
						$mi = new MicroIntegration( $db );
						$mi->load( $mi_id );

						if ( !$mi->callIntegration() ) {
							continue;
						}

						$return[] = $mi;
					}
				}
			}
		}

		// Go back to initial focus, if it has been changed
		if ( $this->focusSubscription->id != $focus ) {
			$this->moveFocus( $focus );
		}

		return $return;
	}

	function getAlertLevel()
	{
		$alert = array();

		if ( !empty( $this->objSubscription->status ) ) {
			if ( strcmp( $this->objSubscription->status, 'Excluded' ) === 0 ) {
				$alert['level']		= 3;
				$alert['daysleft']	= 'excluded';
			} elseif ( !empty( $this->objSubscription->lifetime ) ) {
				$alert['level']		= 3;
				$alert['daysleft']	= 'infinite';
			} else {
				$alert = $this->objSubscription->GetAlertLevel();
			}
		}

		return $alert;
	}

	function isRecurring()
	{
		if ( !empty( $this->objSubscription->status ) ) {
			if ( strcmp( $this->objSubscription->status, 'Cancelled' ) != 0 ) {
				return $this->objSubscription->recurring;
			}
		}

		return false;
	}

	function delete()
	{
		$subids = $this->getAllSubscriptions();

		foreach ( $subids as $id ) {
			$subscription = new Subscription();
			$subscription->load( $id );

			$subscription->delete();
		}

		$this->meta->delete();
	}
}

class metaUserDB extends serialParamDBTable
{
	/** @var int Primary key */
	var $id					= null;
	/** @var int */
	var $userid				= null;
	/** @var datetime */
	var $created_date		= null;
	/** @var datetime */
	var $modified_date		= null;
	/** @var serialized object */
	var $plan_history		= null;
	/** @var serialized object */
	var $processor_params	= null;
	/** @var serialized object */
	var $plan_params		= null;
	/** @var serialized object */
	var $params 			= null;
	/** @var serialized object */
	var $custom_params		= null;

	/**
	* @param database A database connector object
	*/
	function metaUserDB( &$db )
	{
		parent::__construct( '#__acctexp_metauser', 'id', $db );
	}

	function declareParamFields()
	{
		return array( 'plan_history', 'processor_params', 'plan_params', 'params', 'custom_params' );
	}

	/**
	 * loads specified user
	 *
	 * @param int $userid
	 */
	function loadUserid( $userid )
	{
		$id = $this->getIDbyUserid( $userid );

		if ( $id ) {
			$this->load( $id );
		} else {
			$this->createNew( $userid );
		}
	}

	function getIDbyUserid( $userid )
	{
		$db = &JFactory::getDBO();

		$query = 'SELECT `id`'
				. ' FROM #__acctexp_metauser'
				. ' WHERE `userid` = \'' . $userid . '\''
				;
		$db->setQuery( $query );

		return $db->loadResult();
	}

	function createNew( $userid )
	{
		$app = JFactory::getApplication();

		$this->userid			= $userid;
		$this->created_date		= date( 'Y-m-d H:i:s', ( (int) gmdate('U') ) );

		$this->storeload();
	}

	function storeload()
	{
		$this->modified_date	= date( 'Y-m-d H:i:s', ( (int) gmdate('U') ) );

		parent::storeload();
	}

	function getProcessorParams( $processorid )
	{
		if ( isset( $this->processor_params[$processorid] ) ) {
			return $this->processor_params[$processorid];
		} else {
			return false;
		}
	}

	function setProcessorParams( $processorid, $params )
	{
		$app = JFactory::getApplication();

		if ( empty( $this->processor_params ) ) {
			$this->processor_params = array();
		}

		if ( empty( $this->processor_params[$processorid] ) ) {
			$this->processor_params[$processorid] = array();
		}

		$this->processor_params[$processorid] = $params;

		$this->storeload();
	}

	function getMIParams( $miid, $usageid=false, $strict=true )
	{
		if ( $usageid ) {
			if ( is_object( $this->plan_params ) ) {
				$this->plan_params = array();
			}

			if ( isset( $this->plan_params[$usageid] ) ) {
				if ( isset( $this->plan_params[$usageid][$miid] ) ) {
					$return = $this->plan_params[$usageid][$miid];
				}
			} elseif ( !$strict ) {
				$return = $this->getMIParams( $miid );
			}
		} else {
			if ( isset( $this->params->mi[$miid] ) ) {
				$return = $this->params->mi[$miid];
			}
		}

		if ( empty( $return ) ) {
			return array();
		} elseif( is_array( $return ) ) {
			return $return;
		} else {
			return array();
		}
	}

	function setMIParams( $miid, $usageid=false, $params, $replace=false )
	{
		if ( $usageid ) {
			if ( is_object( $this->plan_params ) ) {
				$this->plan_params = array();
			}

			if ( isset( $this->plan_params[$usageid] ) ) {
				if ( isset( $this->plan_params[$usageid][$miid] ) && !$replace ) {
					if ( is_object( $this->plan_params[$usageid][$miid] ) ) {
						$this->plan_params[$usageid][$miid] = get_object_vars( $this->plan_params[$usageid][$miid] );
					}

					$this->plan_params[$usageid][$miid] = $this->mergeParams( $this->plan_params[$usageid][$miid], $params );
				} else {
					$this->plan_params[$usageid][$miid] = $params;
				}
			} else {
				$this->plan_params[$usageid] = array();
				$this->plan_params[$usageid][$miid] = $params;
			}
		}

		if ( isset( $this->params->mi[$miid] ) && !$replace ) {
			$this->params->mi[$miid] = $this->mergeParams( $this->params->mi[$miid], $params );
		} else {
			$this->params->mi[$miid] = $params;
		}

		$this->modified_date	= date( 'Y-m-d H:i:s', ( (int) gmdate('U') ) );

		return true;
	}

	function getCustomParams()
	{
		return $this->custom_params;
	}

	function addCustomParams( $params )
	{
		$app = JFactory::getApplication();

		$this->addParams( $params, 'custom_params' );

		$this->modified_date	= date( 'Y-m-d H:i:s', ( (int) gmdate('U') ) );
	}

	function setCustomParams( $params )
	{
		$app = JFactory::getApplication();

		$this->addParams( $params, 'custom_params', true );

		$this->modified_date	= date( 'Y-m-d H:i:s', ( (int) gmdate('U') ) );
	}

	function addPreparedMIParams( $plan_mi, $mi=false )
	{
		$app = JFactory::getApplication();

		$this->addParams( $plan_mi, 'plan_params' );

		if ( $mi === false ) {
			// TODO: Write function that recreates pure MI data from plan_mi construct
		}

		if ( !empty( $mi ) ) {
			if ( isset( $this->params->mi ) ) {
				$this->params->mi = $this->mergeParams( $this->params->mi, $mi );
			} else {
				$this->params->mi = $mi;
			}
		}

		return $this->storeload();
	}

	function addPlanID( $id )
	{
		$app = JFactory::getApplication();

		$this->plan_history->plan_history[] = $id;

		if ( isset( $this->plan_history->used_plans[$id] ) ) {
			$this->plan_history->used_plans[$id]++;
		} else {
			$this->plan_history->used_plans[$id] = 1;
		}

		return $this->storeload();
	}

	function is_renewing()
	{
		if ( !empty( $this->plan_history->used_plans ) ) {
			return true;
		} else {
			return false;
		}
	}

	function getUsedPlans()
	{
		if ( !empty( $this->plan_history->used_plans ) ) {
			return $this->plan_history->used_plans;
		} else {
			return array();
		}
	}

	function getPreviousPlan()
	{
		if ( empty( $this->plan_history ) ) {
			return null;
		}

		$last = count( $this->plan_history->plan_history ) - 2;

		if ( $last < 0 ) {
			return null;
		} elseif ( isset( $this->plan_history->plan_history[$last] ) ) {
			return $this->plan_history->plan_history[$last];
		} else {
			return null;
		}
	}
}

class aecLanguageHandler
{
	function loadList( $list )
	{
		if ( empty( $list ) ) {
			return;
		}

		$lang =& JFactory::getLanguage();

		foreach ( $list as $name => $path ) {
			$lang->load( $name, $path, 'en-GB', true );
			$lang->load( $name, $path, $lang->get('tag'), true );
		}

		if ( !defined( 'JPATH_MANIFESTS' ) ) {
			foreach ( $lang->_strings as $k => $v ) {
				$lang->_strings[$k]= str_replace( '"_QQ_"', '"', $v );
			}
		}

		return;
	}
}

class aecACLhandler
{
	function getSuperAdmins()
	{
		if ( defined( 'JPATH_MANIFESTS' ) ) {
			$groups = aecACLhandler::getAdminGroups();

			$users = aecACLhandler::getUsersByGroup( $groups );

			return aecACLhandler::getUserObjects( $users );
		} else {
			$db = &JFactory::getDBO();

			$query = 'SELECT `id`, `name`, `email`, `sendEmail`'
					. ' FROM #__users'
					. ' WHERE LOWER( usertype ) = \'superadministrator\''
					. ' OR LOWER( usertype ) = \'super administrator\''
					;
			$db->setQuery( $query );
			return $db->loadObjectList();
		}
	}

	function getAdminGroups( $regular_admins=true )
	{
		$db = &JFactory::getDBO();

		$query = 'SELECT `id`'
				. ' FROM #__usergroups'
				. ' WHERE `id` = 8'
				. ( $regular_admins ? ' OR `id` = 7' : '' )
				;
		$db->setQuery( $query );

		return $db->loadResultArray();
	}

	function getManagerGroups()
	{
		// Thank you, I hate myself /quite/ enough
		return array(6);
	}

	function getUsersByGroup( $group )
	{
		$acl = &JFactory::getACL();

		if ( is_array( $group ) ) {
			$groups = $group;
		} else {
			$groups[] = $group;
		}

		$users = array();
		foreach ( $groups as $group_id ) {
			$users = array_merge( $users, $acl->getUsersByGroup( $group_id ) );
		}

		return array_unique( $users );
	}

	function getUserObjects( $users )
	{
		$db = &JFactory::getDBO();

		$query = 'SELECT `id`, `name`, `email`, `sendEmail`'
				. ' FROM #__users'
				. ' WHERE id IN (' . implode( ',', $users ) . ')'
				;
		$db->setQuery( $query );

		return $db->loadObjectList();
	}

	function removeGIDs( $userid, $gids )
	{
		if ( defined( 'JPATH_MANIFESTS' ) ) {
			$db = &JFactory::getDBO();

			foreach ( $gids as $gid ) {
				$query = 'DELETE'
						. ' FROM #__user_usergroup_map'
						. ' WHERE `user_id` = \'' . ( (int) $userid ) . '\''
						. ' AND `group_id` = \'' . ( (int) $gid ) . '\''
						;
				$db->setQuery( $query );
				$db->query();
			}
		}
	}

	function setGIDs( $userid, $gids )
	{
		$info = array();
		foreach ( $gids as $gid ) {
			$info[$gid] = aecACLhandler::setGIDsTakeNames( $userid, $gid );

			aecACLhandler::setGID( $userid, $gid, $info[$gid] );
		}

		return $info;
	}

	function setGID( $userid, $gid, $gid_name )
	{
		$db = &JFactory::getDBO();

		if ( defined( 'JPATH_MANIFESTS' ) ) {
			// Make sure the user does not have this group assigned yet
			$query = 'SELECT `user_id`'
					. ' FROM #__user_usergroup_map'
					. ' WHERE `user_id` = \'' . $userid . '\''
					. ' AND `group_id` = \'' . $gid . '\''
					;
			$db->setQuery( $query );
			$id = $db->loadResult();

			if ( empty( $id ) ) {
				$query = 'INSERT INTO `#__user_usergroup_map` (`user_id`, `group_id`)'
						. ' VALUES ('.$userid.', '.$gid.')'
						;
				$db->setQuery( $query );
				$db->query() or die( $db->stderr() );
			}
		} else {
			$query = 'UPDATE #__users'
					. ' SET `gid` = \'' .  (int) $gid . '\', `usertype` = \'' . $gid_name . '\''
					. ' WHERE `id` = \''  . (int) $userid . '\''
					;
			$db->setQuery( $query );
			$db->query() or die( $db->stderr() );
		}
	}

	function setGIDsTakeNames( $userid, $gid )
	{
		$db = &JFactory::getDBO();

		$acl = &JFactory::getACL();

		if ( !defined( 'JPATH_MANIFESTS' ) ) {
			// Get ARO ID for user
			$query = 'SELECT `id`'
					. ' FROM #__core_acl_aro'
					. ' WHERE `value` = \'' . (int) $userid . '\''
					;
			$db->setQuery( $query );
			$aro_id = $db->loadResult();

			// If we have no aro id, something went wrong and we need to create it
			if ( empty( $aro_id ) ) {
				$metaUser = new metaUser( $userid );

				$query2 = 'INSERT INTO #__core_acl_aro'
						. ' (`section_value`, `value`, `order_value`, `name`, `hidden` )'
						. ' VALUES ( \'users\', \'' . $userid . '\', \'0\', \'' . $metaUser->cmsUser->name . '\', \'0\' )'
						;
				$db->setQuery( $query2 );
				$db->query();
	
				$db->setQuery( $query );
				$aro_id = $db->loadResult();
			}

			// Carry out ARO ID -> ACL group mapping
			$query = 'UPDATE #__core_acl_groups_aro_map'
					. ' SET `group_id` = \'' . (int) $gid . '\''
					. ' WHERE `aro_id` = \'' . $aro_id . '\''
					;
			$db->setQuery( $query );
			$db->query() or die( $db->stderr() );

			$gid_name = $acl->get_group_name( $gid, 'ARO' );
		} else {
			$query = 'SELECT `title`'
					. ' FROM #__usergroups'
					. ' WHERE `id` = \'' . $gid . '\''
					;
			$db->setQuery( $query );
			$gid_name = $db->loadResult();
		}

		return $gid_name;
	}

	function adminBlock()
	{
		global $aecConfig;

		$user = &JFactory::getUser();

		$acl = &JFactory::getACL();

		$block = false;

		if ( defined( 'JPATH_MANIFESTS' ) ) {
			$allowed_groups = aecACLhandler::getAdminGroups( $aecConfig->cfg['adminaccess'] );

			if ( $aecConfig->cfg['manageraccess'] ) {
				$allowed_groups = array_merge( $allowed_groups, aecACLhandler::getManagerGroups() );
			}

			$usergroups = $acl->getGroupsByUser( $user->id );

			if ( !count( array_intersect( $allowed_groups, $usergroups ) ) ) {
				$block = true;
			}
		} else {
			$acl->addACL( 'administration', 'config', 'users', 'super administrator' );

			$acpermission = $acl->acl_check( 'administration', 'config', 'users', $user->usertype );

			if ( !$acpermission ) {
				if (
					!( ( strcmp( $user->usertype, 'Administrator' ) === 0 ) && $aecConfig->cfg['adminaccess'] )
					&& !( ( strcmp( $user->usertype, 'Manager' ) === 0 ) && $aecConfig->cfg['manageraccess'] )
				 ) {
					$block = true;
				}
			}
		}

		if ( $block ) {
			$app = JFactory::getApplication();

			$app->redirect( 'index.php', _NOT_AUTH );
		}
	}

	function userDelete( $userid, $msg )
	{
		if ( $userid == $user->id ) {
			return JText::_('AEC_MSG_NODELETE_YOURSELF');
		} 

		$acl = &JFactory::getACL();

		$user = &JFactory::getUser();

		if ( defined( 'JPATH_MANIFESTS' ) ) {
			$superadmins = aecACLhandler::getAdminGroups( false );

			$alladmins = aecACLhandler::getAdminGroups();

			$groups = $acl->getGroupsByUser( $userid );

			if ( count( array_intersect( $groups, $superadmins ) ) ) {
				return JText::_('AEC_MSG_NODELETE_SUPERADMIN');
			}

			$is_admin = false;
			if ( count( array_intersect( $groups, $alladmins ) ) ) {
				$is_admin = true;
			}

			$usergroups = $acl->getGroupsByUser( $user->id );

			$deletor_admin = true;
			if ( count( array_intersect( $usergroups, $superadmins ) ) ) {
				$deletor_admin = false;
			}
		} else {
			$groups		= $acl->get_object_groups( 'users', $userid, 'ARO' );

			$this_group	= strtolower( $acl->get_group_name( $groups[0], 'ARO' ) );
			
			$deletor_admin = $user->gid == 24;
			
			if( $this_group == 'super administrator' ) {
				return JText::_('AEC_MSG_NODELETE_SUPERADMIN');
			}

			$is_admin		= $this_group == 'administrator';
		}

		if ( $is_admin && $deletor_admin ) {
			return JText::_('AEC_MSG_NODELETE_EXCEPT_SUPERADMIN');
		} else {
			$db = &JFactory::getDBO();

			$obj = new JTableUser( $db );

			if ( !$obj->delete( $userid ) ) {
				return $obj->getError();
			}
		}
	}

	function getGroupTree( $ex=array() )
	{
		$acl = &JFactory::getACL();

		$user = &JFactory::getUser();

		$ex_groups = array();

		if ( defined( 'JPATH_MANIFESTS' ) ) {
			$db = JFactory::getDbo();

			$db->setQuery(
				'SELECT a.id AS value, a.title AS text, COUNT(DISTINCT b.id) AS level'
				. ' FROM #__usergroups AS a'
				. ' LEFT JOIN `#__usergroups` AS b ON a.lft > b.lft AND a.rgt < b.rgt'
				. ' WHERE a.parent_id != 0'
				. ' GROUP BY a.id'
				. ' ORDER BY a.lft ASC'
			);

			$gtree = $db->loadObjectList();

			foreach ( $gtree as &$option ) {
				$option->text = str_repeat('- ',$option->level).$option->text;
			}

			$usergroups = $acl->getGroupsByUser( $user->id );

			$superadmins = aecACLhandler::getAdminGroups( false );

			$alladmins = aecACLhandler::getAdminGroups();

			if ( !count( array_intersect( $usergroups, $superadmins ) ) ) {
				$ex_groups = array_merge( $ex_groups, $superadmins );
			} else {
				$ex_groups = array_merge( $ex_groups, $alladmins );
			}
		} else {
			$gtree = $acl->get_group_children_tree( null, 'USERS', true );

			$my_groups = $acl->get_object_groups( 'users', $user->id, 'ARO' );

			if ( is_array( $my_groups ) && count( $my_groups ) > 0) {
				$ex_groups = $acl->get_group_children( $my_groups[0], 'ARO', 'RECURSE' );
			} else {
				$ex_groups = array();
			}

			$ex_groups = array_merge( $ex_groups, $ex );
		}

		// remove groups 'above' current user
		$i = 0;
		while ( $i < count( $gtree ) ) {
			if ( in_array( $gtree[$i]->value, $ex_groups ) ) {
				array_splice( $gtree, $i, 1 );
			} else {
				$i++;
			}
		}

		return $gtree;
	}

	function countAdmins()
	{
		if ( defined( 'JPATH_MANIFESTS' ) ) {
			return count( aecACLhandler::getUsersByGroup( aecACLhandler::getAdminGroups() ) );
		} else {
			$db = &JFactory::getDBO();

			$query = 'SELECT count(*)'
					. ' FROM #__core_acl_groups_aro_map'
					. ' WHERE `group_id` IN (\'25\',\'24\')'
					;
			$db->setQuery( $query );
			return $db->loadResult();
		}
	}

	function aclList()
	{
		$list = array();
		if ( defined( 'JPATH_MANIFESTS' ) ) {
			$db = &JFactory::getDBO();
	
			$query = 'SELECT `id`, `title`'
					. ' FROM #__usergroups'
					;
			$db->setQuery( $query );
	
			$acllist = $db->loadObjectList();

			foreach ( $acllist as $aclli ) {
				$acll = new stdClass();

				$acll->group_id	= $aclli->id;
				$acll->name		= $aclli->title;
				
				$list[] = $acll;
			}
		} else {
			$acl =& JFactory::getACL();

			$acllist = $acl->get_group_children( 28, 'ARO', 'RECURSE' );

			foreach ( $acllist as $aclli ) {
				$acldata = $acl->get_group_data( $aclli );

				$list[$aclli] = new stdClass();

				$list[$aclli]->group_id	= $acldata[0];
				$list[$aclli]->name		= $acldata[3];
			}
		}

		return $list;
	}

	function getLowerACLGroups( $group_id )
	{
		$db = &JFactory::getDBO();

		if ( defined( 'JPATH_MANIFESTS' ) ) {
			$tbl = 'usergroups';
		} else {
			$tbl = 'core_acl_aro_groups';
		}

		$query = 'SELECT g2.id'
				. ' FROM #__' . $tbl . ' AS g1'
				. ' INNER JOIN #__' . $tbl . ' AS g2 ON g1.lft > g2.lft AND g1.rgt < g2.rgt'
				. ' WHERE g1.id = ' . $group_id
				. ' GROUP BY g2.id'
				. ' ORDER BY g2.lft'
				;
		$db->setQuery( $query );

		return $db->loadResultArray();
	}

	function getHigherACLGroups( $group_id )
	{
		$db = &JFactory::getDBO();

		if ( defined( 'JPATH_MANIFESTS' ) ) {
			$tbl = 'usergroups';
		} else {
			$tbl = 'core_acl_aro_groups';
		}

		$query = 'SELECT g2.id'
				. ' FROM #__' . $tbl . ' AS g1'
				. ' INNER JOIN #__' . $tbl . ' AS g2 ON g1.lft < g2.lft AND g1.rgt > g2.rgt'
				. ' WHERE g1.id = ' . $group_id
				. ' GROUP BY g2.id'
				. ' ORDER BY g2.lft'
				;
		$db->setQuery( $query );

		return $db->loadResultArray();
	}
}

class aecSessionHandler
{
	function instantGIDchange( $userid, $gid, $removegid=array(), $sessionextra=null )
	{
		$user = &JFactory::getUser();

		if ( !is_array( $gid ) && !empty( $gid ) ) {
			$gid = array( $gid );
		} elseif ( empty( $gid ) ) {
			$gid = array();
		}

		if ( !is_array( $removegid ) && !empty( $removegid ) ) {
			$removegid = array( $removegid );
		}

		if ( !empty( $removegid ) ) {
			aecACLhandler::removeGIDs( (int) $userid, $removegid );
		}

		// Set GID and usertype
		if ( !empty( $gid ) ) {
			$info = aecACLhandler::setGIDs( (int) $userid, $gid );
		}

		$session = $this->getSession( $userid );

		if ( empty( $session ) ) {
			return true;
		}

		if ( !empty( $sessionextra ) ) {
			if ( is_array( $sessionextra ) ) {
				foreach ( $sessionextra as $sk => $sv ) {
					$session['user']->$sk = $sv;

					if ( $userid == $user->id ) {
						$user->$sk	= $sv;
					}
				}
			}
		}

		if ( isset( $session['user'] ) && !defined( 'JPATH_MANIFESTS' ) ) {
				if ( !empty( $gid[0] ) ) {
				$session['user']->gid		= $gid[0];
				$session['user']->usertype	= $info[$gid[0]];

				if ( $userid == $user->id ) {
					$user->gid		= $gid[0];
					$user->usertype	= $info[$gid[0]];
				}
			}
		} elseif ( isset( $session['user'] ) ) {
			$user = &JFactory::getUser();

			$sgsids = JAccess::getGroupsByUser( $userid );

			if ( !empty( $gid ) ) {
				foreach ( $gid as $g ) {
					if ( !in_array( $g, $sgsids ) ) {
						$sgsids[] = $g;
					}
				}
			}

			if ( !empty( $removegid ) ) {
				foreach ( $sgsids as $k => $g ) {
					if ( in_array( $g, $removegid ) ) {
						unset( $sgsids[$k] );
					}
				}
			}

			$db = &JFactory::getDBO();

			$query = 'SELECT `title`, `id`'
					. ' FROM #__usergroups'
					. ' WHERE `id` IN (' . implode( ',', $sgsids ) . ')'
					;
			$db->setQuery( $query );
			$sgslist = $db->loadObjectList();

			$sgs = array();

			foreach ( $sgslist as $gidgroup ) {
				if ( !in_array( $gidgroup->id, $removegid ) ) {
					$sgs[$gidgroup->title] = $gidgroup->id;
				}
			}

			if ( $userid == $user->id ) {
				$user->set( 'groups', $sgs );
				
				$user->set( '_authLevels', aecSessionHandler::getAuthorisedViewLevels($userid) );
				$user->set( '_authGroups', aecSessionHandler::getGroupsByUser($userid) );
			}

			$session['user']->set( 'groups', $sgs );

			$session['user']->set( '_authLevels', aecSessionHandler::getAuthorisedViewLevels($userid) );
			$session['user']->set( '_authGroups', aecSessionHandler::getGroupsByUser($userid) );
		}

		$this->putSession( $userid, $session, $gid[0], $info[$gid[0]] );
	}

	// The following two functions copied from joomla to circle around their hardcoded caching

	function getGroupsByUser( $userId, $recursive=true )
	{
		$db	= JFactory::getDBO();

		// Build the database query to get the rules for the asset.
		$query	= $db->getQuery(true);
		$query->select($recursive ? 'b.id' : 'a.id');
		$query->from('#__user_usergroup_map AS map');
		$query->where('map.user_id = '.(int) $userId);
		$query->leftJoin('#__usergroups AS a ON a.id = map.group_id');

		// If we want the rules cascading up to the global asset node we need a self-join.
		if ($recursive) {
			$query->leftJoin('#__usergroups AS b ON b.lft <= a.lft AND b.rgt >= a.rgt');
		}

		// Execute the query and load the rules from the result.
		$db->setQuery($query);
		$result	= $db->loadResultArray();

		// Clean up any NULL or duplicate values, just in case
		JArrayHelper::toInteger($result);

		if (empty($result)) {
			$result = array('1');
		}
		else {
			$result = array_unique($result);
		}

		return $result;
	}

	function getAuthorisedViewLevels($userId)
	{
		// Get all groups that the user is mapped to recursively.
		$groups = self::getGroupsByUser($userId);

		// Only load the view levels once.
		if (empty($viewLevels)) {
			// Get a database object.
			$db	= JFactory::getDBO();

			// Build the base query.
			$query	= $db->getQuery(true);
			$query->select('id, rules');
			$query->from('`#__viewlevels`');

			// Set the query for execution.
			$db->setQuery((string) $query);

			// Build the view levels array.
			foreach ($db->loadAssocList() as $level) {
				$viewLevels[$level['id']] = (array) json_decode($level['rules']);
			}
		}

		// Initialise the authorised array.
		$authorised = array(1);

		// Find the authorized levels.
		foreach ($viewLevels as $level => $rule)
		{
			foreach ($rule as $id)
			{
				if (($id < 0) && (($id * -1) == $userId)) {
					$authorised[] = $level;
					break;
				}
				// Check to see if the group is mapped to the level.
				elseif (($id >= 0) && in_array($id, $groups)) {
					$authorised[] = $level;
					break;
				}
			}
		}

		return $authorised;
	}

	function getSession( $userid )
	{
		$db = &JFactory::getDBO();

		$query = 'SELECT data'
		. ' FROM #__session'
		. ' WHERE `userid` = \'' . (int) $userid . '\''
		;
		$db->setQuery( $query );
		$data = $db->loadResult();

		if ( !empty( $data ) ) {
			$session = $this->joomunserializesession( $data );

			$key = array_pop( array_keys( $session ) );

			$this->sessionkey = $key;

			return $session[$key];
		} else {
			return array();
		}
	}

	function putSession( $userid, $data, $gid=null, $gid_name=null )
	{
		$db = &JFactory::getDBO();

		$sdata = $this->joomserializesession( array( $this->sessionkey => $data) );

		if ( defined( 'JPATH_MANIFESTS' ) ) {
			$query = 'UPDATE #__session'
					. ' SET `data` = \'' . $db->getEscaped( $sdata ) . '\''
					. ' WHERE `userid` = \'' . (int) $userid . '\''
					;
		} elseif ( isset( $data['user'] ) ) {
			if ( empty( $gid ) ) {
				$query = 'UPDATE #__session'
						. ' SET `data` = \'' . $db->getEscaped( $sdata ) . '\''
						. ' WHERE `userid` = \'' . (int) $userid . '\''
						;
			} else {
				$query = 'UPDATE #__session'
						. ' SET `gid` = \'' .  (int) $gid . '\', `usertype` = \'' . $gid_name . '\', `data` = \'' . $db->getEscaped( $sdata ) . '\''
						. ' WHERE `userid` = \'' . (int) $userid . '\''
						;
			}
		}

		$db->setQuery( $query );
		$db->query() or die( $db->stderr() );
	}

	function joomunserializesession( $data )
	{
		$se = explode( "|", $data, 2 );

		return array( $se[0] => unserialize( $se[1] ) );
	}

	function joomserializesession( $data )
	{
		$key = array_pop( array_keys( $data ) );

		return $key . "|" . serialize( $data[$key] );
	}
}

class Config_General extends serialParamDBTable
{
	/** @var int Primary key */
	var $id 				= null;
	/** @var text */
	var $settings 			= null;

	function Config_General( &$db )
	{
		parent::__construct( '#__acctexp_config', 'id', $db );

		$this->load(1);

		// If we have no settings, init them
		if ( empty( $this->settings ) ) {
			$this->initParams();
		}
	}

	function declareParamFields()
	{
		return array( 'settings' );
	}

	function load( $id )
	{
		parent::load( $id );

		$this->cfg =& $this->settings;
	}

	function check( $fields=array() )
	{
		unset( $this->cfg );

		return parent::check();
	}

	function paramsList()
	{
		$def = array();
		$def['require_subscription']			= 0;
		$def['alertlevel2']						= 7;
		$def['alertlevel1']						= 3;
		$def['expiration_cushion']				= 12;
		$def['heartbeat_cycle']					= 24;
		$def['heartbeat_cycle_backend']			= 1;
		$def['plans_first']						= 0;
		$def['simpleurls']						= 0;
		$def['display_date_backend']			= "";
		$def['enable_coupons']					= 0;
		$def['skip_confirmation']				= 0;
		// new 0.12.4
		$def['bypassintegration']				= '';
		// new 0.12.4.2
		$def['adminaccess']						= 1;
		$def['noemails']						= 0;
		$def['nojoomlaregemails']				= 0;
		// new 0.12.4.12
		$def['override_reqssl']					= 0;
		// new 0.12.4.16
		$def['invoicenum_doformat']				= 0;
		$def['invoicenum_formatting']			= '{aecjson}{"cmd":"concat","vars":[{"cmd":"date","vars":["Y",{"cmd":"rw_constant",'
													. '"vars":"invoice_created_date"}]},"-",{"cmd":"rw_constant","vars":"invoice_id"}]}'
													.'{/aecjson}';
		$def['use_recaptcha']					= 0;
		$def['ssl_signup']						= 0;
		$def['error_notification_level']		= 32;
		$def['email_notification_level']		= 128;
		$def['temp_auth_exp']					= 15;
		$def['show_fixeddecision']				= 0;
		$def['confirmation_coupons']			= 0;
		$def['breakon_mi_error']				= 0;
		$def['curl_default']					= 1;
		$def['amount_currency_symbol']			= 0;
		$def['amount_currency_symbolfirst']		= 0;
		$def['amount_use_comma']				= 0;
		$def['use_proxy']						= 0;
		$def['proxy']							= '';
		$def['proxy_port']						= '';
		$def['ssl_profile']						= 0;
		$def['overrideJ15']						= 0;
		$def['proxy_username']					= '';
		$def['proxy_password']					= '';
		$def['gethostbyaddr']					= 1;
		$def['root_group']						= 1;
		$def['root_group_rw']					= '';
		$def['integrate_registration']			= 1;
		$def['enable_shoppingcart']				= 0;
		$def['additem_stayonpage']				= '';
		$def['gwlist']							= array();
		$def['altsslurl']						= '';
		$def['checkout_as_gift']				= 0;
		$def['checkout_as_gift_access']			= 23;
		$def['invoice_cushion']					= 10; //Minutes
		$def['allow_frontend_heartbeat']		= 0;
		$def['disable_regular_heartbeat']		= 0;
		$def['custom_heartbeat_securehash']		= "";
		$def['delete_tables']					= "";
		$def['delete_tables_sure']				= "";
		$def['standard_currency']				= "USD";
		$def['manageraccess']					= 0;
		$def['per_plan_mis']					= 0;
		$def['intro_expired']					= 0;
		$def['email_default_admins']			= 1;
		$def['email_extra_admins']				= "";
		$def['countries_available']				= "";
		$def['countries_top']					= "";
		$def['checkoutform_jsvalidation']		= 0;
		$def['allow_invoice_unpublished_item']	= 0;
		$def['itemid_default']					= "";
		$def['itemid_cart']						= "";
		$def['itemid_checkout']					= "";
		$def['itemid_confirmation']				= "";
		$def['itemid_subscribe']				= "";
		$def['itemid_exception']				= "";
		$def['itemid_thanks']					= "";
		$def['itemid_expired']					= "";
		$def['itemid_hold']						= "";
		$def['itemid_notallowed']				= "";
		$def['itemid_pending']					= "";
		$def['itemid_subscriptiondetails']		= "";
		$def['itemid_cb']						= "";
		$def['itemid_joomlauser']				= "";
		$def['checkout_coupons']				= 1;
		$def['customAppAuth']					= "";
		$def['user_checkout_prefill']			= "firstname=[[user_first_name]]\nlastname=[[user_last_name]]\naddress=\naddress2=\n"
													. "city=\nstate=\nzip=\ncountry=\nphone=\nfax=\ncompany=";
		$def['noemails_adminoverride']			= 1;

		return $def;
	}

	function initParams()
	{
		// Insert a new entry if there is none yet
		if ( empty( $this->settings ) ) {
			$db = &JFactory::getDBO();

			$query = 'SELECT * FROM #__acctexp_config'
			. ' WHERE `id` = \'1\''
			;
			$db->setQuery( $query );

			if ( !$db->loadResult() ) {
				$query = 'INSERT INTO #__acctexp_config'
				. ' VALUES( \'1\', \'\' )'
				;
				$db->setQuery( $query );
				$db->query() or die( $db->stderr() );
			}

			$this->id = 1;
			$this->settings = '';
		}

		// Write to Params, do not overwrite existing data
		$this->addParams( $this->paramsList(), 'settings', false );

		$this->storeload();

		return true;
	}

	function saveSettings()
	{
		// Extra check for duplicated rows
		if ( $this->RowDuplicationCheck() ) {
			$this->CleanDuplicatedRows();
			$this->load(1);
		}

		$this->storeload();
	}

	function RowDuplicationCheck()
	{
		$db = &JFactory::getDBO();

		$query = 'SELECT count(*)'
				. ' FROM #__acctexp_config'
				;
		$db->setQuery( $query );
		$rows = $db->loadResult();

		if ( $rows > 1 ) {
			return true;
		} else {
			return false;
		}
	}

	function CleanDuplicatedRows()
	{
		$db = &JFactory::getDBO();

		$query = 'SELECT max(id)'
				. ' FROM #__acctexp_config'
				;
		$db->setQuery( $query );
		$db->query();
		$max = $db->loadResult();

		$query = 'DELETE'
				. ' FROM #__acctexp_config'
				. ' WHERE `id` != \'' . $max . '\''
				;
		$db->setQuery( $query );
		$db->query();

		if ( !( $max == 1 ) ) {
			$query = 'UPDATE #__acctexp_config'
					. ' SET `id` = \'1\''
					. ' WHERE `id` =\'' . $max . '\''
					;
			$db->setQuery( $query );
			$db->query();
		}
	}
}

if ( !is_object( $aecConfig ) ) {
	$db = &JFactory::getDBO();

		global $aecConfig;

	$aecConfig = new Config_General( $db );
}

class configTemplate extends serialParamDBTable
{
	/** @var int Primary key */
	var $id					= null;
	/** @var int */
	var $name				= null;
	/** @var int */
	var $default			= null;
	/** @var text */
	var $settings			= null;

	/**
	* @param database A database connector object
	*/
	function configTemplate( &$db )
	{
		parent::__construct( '#__acctexp_config_templates', 'id', $db );
	}

	function declareParamFields()
	{
		return array( 'settings' );
	}

	function loadDefault()
	{
		$db = &JFactory::getDBO();

		// See if the processor is installed & set id
		$query = 'SELECT name'
				. ' FROM #__acctexp_config_templates'
				. ' WHERE `default` = 1'
				;
		$db->setQuery( $query );
		$name = $db->loadResult();

		if ( !empty( $name ) ) {
			$this->loadName($name);
		} else {
			$this->loadName('etacarinae');
		}
	}

	function loadName( $name )
	{
		$db = &JFactory::getDBO();

		$this->name = $name;

		// See if the processor is installed & set id
		$query = 'SELECT id'
				. ' FROM #__acctexp_config_templates'
				. ' WHERE `name` = \'' . $name . '\''
				;
		$db->setQuery( $query );
		$res = $db->loadResult();

		if ( !empty( $res ) ) {
			$this->load($res);
		} else {
			$this->load(0);
		}

		$file = JPATH_SITE . '/components/com_acctexp/tmpl/' . $name . '/template.php';

		if ( file_exists( $file ) ) {
			// Call Integration file
			include_once ( $file );

			// Initiate Payment Processor Class
			$class_name = 'template_' . $name;
			$this->template = new $class_name();
			$this->template->default = $this->default;

			$this->info = $this->template->info();
		}
	}

}

class aecTemplate
{
	function stdSettings()
	{
		$info = $this->info();

		$params = array();
		$params[] = array( 'userinfobox_sub', JText::_('TEMPLATE_TITLE') );
		$params[] = array( 'div', '<div class="alert alert-info">' );
		$params[] = array( 'p', '<p>'.$info['description'].'</p>' );
		$params['default'] = array( ($this->default ? 'toggle_disabled':'toggle'), '' );
		$params[] = array( 'div_end', 0 );
		$params[] = array( 'div_end', 0 );

		return $params;
	}

	function setTitle( $title )
	{
		$document=& JFactory::getDocument();
		$document->setTitle( html_entity_decode( $title, ENT_COMPAT, 'UTF-8' ) );
	}

	function addDefaultCSS()
	{
		$this->addCSS( JURI::root(true) . '/media/' . $this->option . '/css/template.' . $this->template . '.css' );
	}

	function addCSS( $path )
	{
		$document=& JFactory::getDocument();
		$document->addCustomTag( '<link rel="stylesheet" type="text/css" media="all" href="' . $path . '" />' );
	}

	function addCSSDeclaration( $css )
	{
		$document=& JFactory::getDocument();
		$document->addStyleDeclaration( $css );
	}

	function addScriptDeclaration( $js )
	{
		$document=& JFactory::getDocument();
		$document->addScriptDeclaration( $js );
	}

	function addScript( $js )
	{
		$document=& JFactory::getDocument();
		$document->addScript( $js );
	}

	function btn( $params, $value, $class='btn' )
	{
		if ( empty( $params['option'] ) ) {
			$params['option'] = 'com_acctexp';
		}

		$xurl = 'index.php?option='.$params['option'];

		if ( !empty( $params['task'] ) ) {
			$xurl .= '&task='.$params['task'];
		}

		if ( !empty( $params['view'] ) ) {
			$xurl .= '&view='.$params['view'];
		}

		if ( $params['option'] == 'com_acctexp' ) {
			$url = AECToolbox::deadsureURL( $xurl, $this->cfg['ssl_signup'] );
		} else {
			if ( !empty( $id ) ) {
				$xurl	.= '&Itemid=' . $id;
			}
			
			$uri    = JURI::getInstance();
			$prefix = $uri->toString( array( 'scheme', 'host', 'port' ) );

			$url = $prefix.JRoute::_( $xurl );

			if ( $params['option'] == 'com_community' ) {
				// Judge me all you want.
				$url = str_replace( '/component/community/', '/jomsocial/', $url );
			}
		}

		$btn = '<form action="'.$url.'" method="post">';

		if ( isset( $params['class'] ) ) {
			unset( $params['class'] );
		}

		if ( isset( $params['content'] ) ) {
			unset( $params['content'] );
		}

		if ( empty( $params['task'] ) ) {
			unset( $params['task'] );
		}

		foreach ( $params as $k => $v ) {
			$btn .= '<input type="hidden" name="'.$k.'" value="'.$v.'" />';
		}

		$btn .= '<button type="submit" class="'.$class.'">'.$value.'</button>';

		$btn .= JHTML::_( 'form.token' );
		$btn .= '</form>';

		return $btn;
	}

	function lnk( $params, $value, $class="", $profile=false )
	{
		if ( is_array( $params ) ) {
			$url = $this->url( $params, $profile );
		} else {
			$url = $params;
		}

		return '<a href="'.$url.'"'.( !empty($class) ? ' class="'.$class.'"':'').'>'.$value.'</a>';
	}

	function url( $params, $profile=false )
	{
		if ( empty( $params['option'] ) ) {
			$params = array_merge( array( 'option' => 'com_acctexp' ), $params );
		}

		$params[JUtility::getToken()] = '1';

		$p = array();
		foreach ( $params as $k => $v ) {
			$p[] = $k.'='.$v;
		}

		if ( $profile ) {
			$secure = $this->cfg['ssl_profile'];
		} else {
			$secure = $this->cfg['ssl_signup'];
		}

		return AECToolbox::deadsureURL( 'index.php?'.implode("&",$p), $secure );
	}

	function rw( $string )
	{
		return AECToolbox::rewriteEngine( $string, $this->metaUser );
	}

	function rwrq( $string, $request )
	{
		return AECToolbox::rewriteEngineRQ( $string, $request );
	}

	function custom( $setting, $original=null, $obj=null )
	{
		if ( empty( $obj ) ) {
			$obj = $this->cfg;
		}

		if ( !empty( $original ) && isset( $obj[$setting.'_keeporiginal'] ) ) {
			echo '<p>' . $obj[$original] . '</p>';
		}

		if ( !empty( $obj[$setting] ) ) {
			echo '<p>' . $obj[$setting] . '</p>';
		}
	}

	function date( $SQLDate, $check = false, $display = false, $trial = false )
	{
		if ( $SQLDate == '' ) {
			return JText::_('AEC_EXPIRE_NOT_SET');
		} else {
			$retVal = AECToolbox::formatDate( $SQLDate );

			if ( $check ) {
				$timeDif = strtotime( $SQLDate ) - ( (int) gmdate('U') );
				if ( $timeDif < 0 ) {
					$retVal = ( $trial ? JText::_('AEC_EXPIRE_TRIAL_PAST') : JText::_('AEC_EXPIRE_PAST') ) . ':&nbsp;<strong>' . $retVal . '</strong>';
				} elseif ( ( $timeDif >= 0 ) && ( $timeDif < 86400 ) ) {
					$retVal = ( $trial ? JText::_('AEC_EXPIRE_TRIAL_TODAY') : JText::_('AEC_EXPIRE_TODAY') );
				} else {
					$retVal = ( $trial ? JText::_('AEC_EXPIRE_TRIAL_FUTURE') : JText::_('AEC_EXPIRE_FUTURE') ) . ': ' . $retVal;
				}
			}

			return $retVal;
		}
	}

	function tmpl( $name )
	{
		$t = explode( '.', $name );

		if ( count($t) > 2 ) {
			// Load from another template
			return $this->tmplPath( $t[1], $t[0], $t[2] );
		} elseif ( count($t) == 2 ) {
			// Load from another view
			return $this->tmplPath( $t[1], $t[0] );
		} else {
			// Load within view
			return $this->tmplPath( $t[0] );
		}
	}

	function tmplPath( $subview, $view=null, $template=null )
	{
		if ( empty( $view ) ) {
			$view = $this->view;
		}

		if ( empty( $template ) ) {
			$current = $this->paths['current'];
		} else {
			$current = $this->paths['base'].'/'.$this->template;
		}

		$t = '/'.$view.'/tmpl/'.$subview.'.php';

		if ( file_exists( $this->paths['site'].$t ) ) {
			return $this->paths['site'].$t;
		} elseif ( file_exists( $current.$t ) ) {
			return $current.$t;
		} elseif ( file_exists( $this->paths['default'].$t ) ) {
			return $this->paths['default'].$t;
		}
	}
}

class aecHeartbeat extends JTable
{
 	/** @var int Primary key */
	var $id				= null;
 	/** @var datetime */
	var $last_beat 		= null;

	/**
	 * @param database A database connector object
	 */
	function aecHeartbeat( &$db )
	{
	 	$db = &JFactory::getDBO();

	 	parent::__construct( '#__acctexp_heartbeat', 'id', $db );
	 	$this->load(1);

		if ( empty( $this->last_beat ) ) {
			global $aecConfig;

			$query = 'INSERT INTO #__acctexp_heartbeat'
			. ' VALUES( \'1\', \'' . date( 'Y-m-d H:i:s', ( ( (int) gmdate('U') ) - $aecConfig->cfg['heartbeat_cycle'] * 3600 ) ) . '\' )'
			;
			$db->setQuery( $query );
			$db->query() or die( $db->stderr() );

			$this->load(1);
		}
	}

	function frontendping( $custom=false, $hash=null )
	{
		global $aecConfig;

		if ( !empty( $aecConfig->cfg['disable_regular_heartbeat'] ) && empty( $custom ) ) {
			return;
		}

		if ( empty( $aecConfig->cfg['allow_frontend_heartbeat'] ) && !empty( $custom ) ) {
			return;
		}

		if ( !empty( $custom ) && !empty( $aecConfig->cfg['custom_heartbeat_securehash'] ) ) {
			if ( empty( $hash ) ) {
				return;
			} elseif( $hash != $aecConfig->cfg['custom_heartbeat_securehash'] ) {
				$db = &JFactory::getDBO();
				$short	= 'custom heartbeat failure';
				$event	= 'Custom Frontend Heartbeat attempted, but faile due to false hashcode: "' . $hash . '"';
				$tags	= 'heartbeat, failure';
				$params = array();

				$eventlog = new eventLog( $db );
				$eventlog->issue( $short, $tags, $event, 128, $params );

				return;
			}
		}

		if ( !empty( $aecConfig->cfg['allow_frontend_heartbeat'] ) && !empty( $custom ) ) {
			aecHeartbeat::ping( 0 );
		} elseif ( !empty( $aecConfig->cfg['heartbeat_cycle'] ) ) {
			aecHeartbeat::ping( $aecConfig->cfg['heartbeat_cycle'] );
		}
	}

	function backendping()
	{
		global $aecConfig;

		if ( !empty( $aecConfig->cfg['heartbeat_cycle_backend'] ) ) {
			$this->ping( $aecConfig->cfg['heartbeat_cycle_backend'] );
		}
	}

	function ping( $configCycle )
	{
		$app = JFactory::getApplication();

		if ( empty( $this->last_beat ) ) {
			$this->load(1);
		}

		if ( empty( $configCycle ) ) {
			$ping = 0;
		} elseif ( $this->last_beat ) {
			$ping	= strtotime( $this->last_beat ) + $configCycle*3600;
		} else {
			$ping = 0;
		}

		if ( ( $ping - ( (int) gmdate('U') ) ) <= 0 ) {
			$this->last_beat = date( 'Y-m-d H:i:s', ( (int) gmdate('U') ) );
			$this->check();
			$this->store();
			$this->load(1);

			$this->beat();
		} else {
			// sleep, mechanical Hound, but do not sleep
			// kept awake with
			// wolves teeth
		}
	}

	function beat()
	{
		$db = &JFactory::getDBO();

		$this->processors = array();
		$this->proc_prepare = array();

		$this->result = array(	'fail_expired' => 0,
								'expired' => 0,
								'pre_expired' => 0,
								'pre_exp_actions' => 0
								);

		// Some cleanup
		$this->deleteTempTokens();

		// Receive maximum pre expiration time
		$pre_expiration = microIntegrationHandler::getMaxPreExpirationTime();

		$subscription_list = $this->getSubscribers( $pre_expiration );

		// Efficient way to check for expired users without checking on each one
		if ( !empty( $subscription_list ) ) {
			foreach ( $subscription_list as $sid => $sub_id ) {
				$subscription = new Subscription( $db );
				$subscription->load( $sub_id );

				if ( !AECfetchfromDB::UserExists( $subscription->userid ) ) {
					unset( $subscription_list[$sid] );

					continue;
				}

				// Check whether this user really is expired
				// If this check fails, the following subscriptions might still be pre-expiration events
				if ( $subscription->is_expired() ) {
					// If we don't have any validation response, expire
					$validate = $this->processorValidation( $subscription, $subscription_list );

					if ( $validate === false ) {
						// There was some fatal error, return.
						return;
					} elseif ( $validate !== true ) {
						if ( $subscription->expire() ) {
							$this->result['expired']++;
						} else {
							$this->result['fail_expired']++;
						}
					}
					
					unset( $subscription_list[$sid] );
				} elseif ( !$subscription->recurring ) {
					break;
				}
			}

			// Only go for pre expiration action if we have at least one user for it
			if ( $pre_expiration && !empty( $subscription_list ) ) {
				// Get all the MIs which have a pre expiration check
				$mi_pexp = microIntegrationHandler::getPreExpIntegrations();

				// Find plans which have the MIs assigned
				$expmi_plans = microIntegrationHandler::getPlansbyMI( $mi_pexp );

				// Filter out the users which dont have the correct plan
				$query = 'SELECT `id`, `userid`'
						. ' FROM #__acctexp_subscr'
						. ' WHERE `id` IN (' . implode( ',', $subscription_list ) . ')'
						. ' AND `plan` IN (' . implode( ',', $expmi_plans ) . ')'
						;
				$db->setQuery( $query );
				$sub_list = $db->loadObjectList();

				if ( !empty( $sub_list ) ) {
					foreach ( $sub_list as $sl ) {
						$metaUser = new metaUser( $sl->userid );
						$metaUser->moveFocus( $sl->id );

						$res = $metaUser->focusSubscription->triggerPreExpiration( $metaUser, $mi_pexp );

						if ( $res ) {
							$this->result['pre_exp_actions'] += $res;
							$this->result['pre_expired']++;
						}
					}
				}
			}
		}

		aecEventHandler::pingEvents();

		// And we're done.
		$this->fileEventlog();
	}

	function getProcessor( $name )
	{
		if ( !isset( $this->processors[$name] ) ) {
			$processor = new PaymentProcessor();
			if ( $processor->loadName( $name ) ) {
				$processor->init();

				$this->processors[$name] = $processor;
			} else {
				// Processor does not exist
				$this->processors[$name] = false;
			}
		}

		return $this->processors[$name];
	}

	function getSubscribers( $pre_expiration )
	{
		$db = &JFactory::getDBO();

		$expiration_limit = $this->getExpirationLimit( $pre_expiration );

		// Select all the users that are Active and have an expiration date
		$query = 'SELECT `id`'
				. ' FROM #__acctexp_subscr'
				. ' WHERE `expiration` <= \'' . $expiration_limit . '\''
				. ' AND `status` != \'Expired\''
				. ' AND `status` != \'Closed\''
				. ' AND `status` != \'Excluded\''
				. ' ORDER BY `expiration` ASC'
				;
		$db->setQuery( $query );
		return $db->loadResultArray();
	}

	function getExpirationLimit( $pre_expiration )
	{
		$app = JFactory::getApplication();

		if ( $pre_expiration ) {
			// pre-expiration found, search limit set to the maximum pre-expiration time
			return AECToolbox::computeExpiration( ( $pre_expiration + 1 ), 'D', ( (int) gmdate('U') ) );
		} else {
			// No pre-expiration actions found, limiting search to all users who expire until tomorrow (just to be safe)
			return AECToolbox::computeExpiration( 1, 'D', ( (int) gmdate('U') ) );
		}
	}

	function processorValidation( $subscription, $subscription_list )
	{
		$db = &JFactory::getDBO();

		$pp = $this->getProcessor( $subscription->type );

		if ( $pp != false ) {
			$prepval = null;

			if ( !isset( $this->proc_prepare[$subscription->type] ) ) {
				// Prepare validation function
				$prepval = $pp->prepareValidation( $subscription_list );
			
				// But only once
				$this->proc_prepare[$subscription->type] = true;
			}

			if ( $prepval === null ) {
				// This Processor has no such function, set to false to ignore later calls
				$this->processors[$subscription->type] = false;
			} elseif ( $prepval === false ) {
				$db = &JFactory::getDBO();

				// Break - we have a problem with one processor
				$eventlog = new eventLog( $db );
				$eventlog->issue( 'heartbeat failed - processor', 'heartbeat, failure,'.$subscription->type, 'The payment processor failed to respond to validation request - waiting for next turn', 128 );
				return false;
			}

			$validation = null;

			// Carry out validation if possible
			if ( !empty( $pp ) ) {
				if ( $subscription->recurring ) {
					$validation = $pp->validateSubscription( $subscription->id, $subscription_list );
				}
			}

			return $validation;
		} else {
			return null;
		}
	}

	function fileEventlog()
	{
		$db = &JFactory::getDBO();

		// Make sure we have all the language stuff loaded
		$langlist = array( 'com_acctexp.admin' => JPATH_ADMINISTRATOR );

		aecLanguageHandler::loadList( $langlist );

		$short	= JText::_('AEC_LOG_SH_HEARTBEAT');
		$event	= JText::_('AEC_LOG_LO_HEARTBEAT') . ' ';
		$tags	= array( 'heartbeat' );
		$level	= 2;

		if ( $this->result['expired'] ) {
			if ( $this->result['expired'] > 1 ) {
				$event .= 'Expires ' . $this->result['expired'] . ' subscriptions';
			} else {
				$event .= 'Expires 1 subscription';
			}

			if ( $this->result['pre_exp_actions'] || $this->result['fail_expired'] ) {
				$event .= ', ';
			}

			$tags[] = 'expiration';
		}

		if ( $this->result['fail_expired'] ) {
			if ( $this->result['fail_expired'] > 1 ) {
				$event .= 'Failed to expire ' . $this->result['fail_expired'] . ' subscriptions';
			} else {
				$event .= 'Failed to expire 1 subscription';
			}

			$event .= ', please check your subscriptions for problems';

			if ( $this->result['pre_exp_actions'] ) {
				$event .= ', ';
			}

			$tags[] = 'error';
			$level	= 128;
		}

		if ( $this->result['pre_exp_actions'] ) {
			$event .= $this->result['pre_exp_actions'] . ' Pre-expiration action';
			$event .= ( $this->result['pre_exp_actions'] > 1 ) ? 's' : '';
			$event .= ' for ' . $this->result['pre_expired'] . ' subscription';
			$event .= ( $this->result['pre_expired'] > 1 ) ? 's' : '';

			$tags[] = 'pre-expiration';
		}

		if ( strcmp( JText::_('AEC_LOG_LO_HEARTBEAT') . ' ', $event ) === 0 ) {
			$event .= JText::_('AEC_LOG_AD_HEARTBEAT_DO_NOTHING');
		}

		$eventlog = new eventLog( $db );
		$eventlog->issue( $short, implode( ',', $tags ), $event, $level );
	}

	function deleteTempTokens()
	{
		$app = JFactory::getApplication();

		$db = &JFactory::getDBO();

		// Delete old token entries
		$query = 'DELETE'
				. ' FROM #__acctexp_temptoken'
				. ' WHERE `created_date` <= \'' . AECToolbox::computeExpiration( "-3", 'H', ( (int) gmdate('U') ) ) . '\''
				;
		$db->setQuery( $query );
		$db->query();
	}

}

class displayPipelineHandler
{
	function displayPipelineHandler()
	{

	}

	function getUserPipelineEvents( $userid )
	{
		$db = &JFactory::getDBO();

		$app = JFactory::getApplication();

		// Entries for this user only
		$query = 'SELECT `id`'
				. ' FROM #__acctexp_displaypipeline'
				. ' WHERE `userid` = \'' . $userid . '\' AND `only_user` = \'1\''
				;
		$db->setQuery( $query );
		$events = $db->loadResultArray();

		// Entries for all users
		$query = 'SELECT `id`'
				. ' FROM #__acctexp_displaypipeline'
				. ' WHERE `only_user` = \'0\''
				;
		$db->setQuery( $query );
		$events = array_merge( $events, $db->loadResultArray() );

		$return = '';
		if ( empty( $events ) ) {
			return $return;
		}

		foreach ( $events as $eventid ) {
			$displayPipeline = new displayPipeline( $db );
			$displayPipeline->load( $eventid );
			if ( $displayPipeline->id ) {

				// If expire & expired -> delete
				if ( $displayPipeline->expire ) {
					$expstamp = strtotime( $displayPipeline->expstamp );
					if ( ( $expstamp - ( (int) gmdate('U') ) ) < 0 ) {
						$displayPipeline->delete();
						continue;
					}
				}

				// If displaymax exceeded -> delete
				$displayremain = $displayPipeline->displaymax - $displayPipeline->displaycount;
				if ( $displayremain <= 0 ) {
					$displayPipeline->delete();
					continue;
				}

				// If this can only be displayed once per user, prevent it from being displayed again
				if ( $displayPipeline->once_per_user ) {
					$params = $displayPipeline->params;

					if ( isset( $displayPipeline->params['displayedto'] ) ) {
						$users = $displayPipeline->params['displayedto'];
						if ( in_array( $userid, $users ) ) {
							continue;
						} else {
							$users[] = $userid;
							$displayPipeline->params['displayedto'] = $users;
						}
					}
				}

				// Ok, now append text
				$return .= stripslashes( $displayPipeline->displaytext );

				// Update display if at least one display would remain
				if ( $displayremain > 1 ) {
					$displayPipeline->displaycount = $displayPipeline->displaycount + 1;
					$displayPipeline->check();
					$displayPipeline->store();
				} else {
					$displayPipeline->delete();
				}
			}
		}

		// Return the string
		return $return;
	}
}

class displayPipeline extends serialParamDBTable
{
	/** @var int Primary key */
	var $id				= null;
	/** @var int */
	var $userid			= null;
	/** @var int */
	var $only_user		= null;
	/** @var int */
	var $once_per_user	= null;
	/** @var datetime */
	var $timestamp		= null;
	/** @var int */
	var $expire			= null;
	/** @var datetime */
	var $expstamp 		= null;
	/** @var int */
	var $displaycount	= null;
	/** @var int */
	var $displaymax		= null;
	/** @var text */
	var $displaytext	= null;
	/** @var text */
	var $params			= null;

	/**
	 * @param database A database connector object
	 */
	function displayPipeline( &$db )
	{
	 	parent::__construct( '#__acctexp_displaypipeline', 'id', $db );
	}

	function declareParamFields()
	{
		return array( 'params', 'displaytext' );
	}

	function create( $userid, $only_user, $once_per_user, $expire, $expiration, $displaymax, $displaytext, $params=null )
	{
		$app = JFactory::getApplication();

		$this->id				= 0;
		$this->userid			= $userid;
		$this->only_user		= $only_user;
		$this->once_per_user	= $once_per_user;
		$this->timestamp		= date( 'Y-m-d H:i:s', ( (int) gmdate('U') ) );
		$this->expire			= $expire ? 1 : 0;
		if ( $expire ) {
			$this->expstamp		= date( 'Y-m-d H:i:s', strtotime( $expiration ) );
		}
		$this->displaycount		= 0;
		$this->displaymax		= $displaymax;

		$this->displaytext		= $displaytext;

		if ( is_array( $params ) ) {
			$this->params = $params;
		}

		$this->check();

		if ( $this->store() ) {
			return true;
		} else {
			return false;
		}
	}
}

class eventLog extends serialParamDBTable
{
	/** @var int Primary key */
	var $id			= null;
	/** @var datetime */
	var $datetime	= null;
	/** @var string */
	var $short 		= null;
	/** @var text */
	var $tags 		= null;
	/** @var text */
	var $event 		= null;
	/** @var int */
	var $level		= null;
	/** @var int */
	var $notify		= null;
	/** @var text */
	var $params		= null;

	/**
	 * @param database A database connector object
	 */
	function eventLog( &$db )
	{
	 	parent::__construct( '#__acctexp_eventlog', 'id', $db );
	}

	function declareParamFields()
	{
		return array( 'params' );
	}

	function issue( $short, $tags, $text, $level = 2, $params = null, $force_notify = 0, $force_email = 0 )
	{
		global $aecConfig;

		$app = JFactory::getApplication();

		$lang = JFactory::getLanguage();

		// Event, Notice, Warning, Error
		$legal_levels = array( 2, 8, 32, 128 );

		if ( !in_array( (int) $level, $legal_levels ) ) {
			$level = $legal_levels[0];
		}

		$this->datetime	= date( 'Y-m-d H:i:s', ( (int) gmdate('U') ) );
		$this->short	= $short;
		$this->tags		= $tags;
		$this->event	= $text;
		$this->level	= (int) $level;

		// Create a notification link if this matches the desired level
		if ( $this->level >= $aecConfig->cfg['error_notification_level'] ) {
			$this->notify	= 1;
		} else {
			$this->notify	= $force_notify ? 1 : 0;
		}

		// Mail out notification to all admins if this matches the desired level
		if ( ( $this->level >= $aecConfig->cfg['email_notification_level'] ) || $force_email ) {
			// check if Global Config `mailfrom` and `fromname` values exist
			if ( $app->getCfg( 'mailfrom' ) != '' && $app->getCfg( 'fromname' ) != '' ) {
				$adminName2 	= $app->getCfg( 'fromname' );
				$adminEmail2 	= $app->getCfg( 'mailfrom' );
			} else {
				$rows = aecACLhandler::getSuperAdmins();

				$adminName2 	= $rows[0]->name;
				$adminEmail2 	= $rows[0]->email;
			}

			if ( !$lang->hasKey( "AEC_NOTICE_NUMBER_" . $this->level ) ) {
				$lang =& JFactory::getLanguage();
				
				$lang->load( 'com_acctexp.admin', JPATH_ADMINISTRATOR );
			}

			// Send notification to all administrators
			$subject2	= sprintf( JText::_('AEC_ASEND_NOTICE'), JText::_( "AEC_NOTICE_NUMBER_" . $this->level ), $this->short, $app->getCfg( 'sitename' ) );
			$message2	= sprintf( JText::_('AEC_ASEND_NOTICE_MSG'), $this->event  );

			$subject2	= html_entity_decode( $subject2, ENT_QUOTES, 'UTF-8' );
			$message2	= html_entity_decode( $message2, ENT_QUOTES, 'UTF-8' );

			// get email addresses of all admins and superadmins set to recieve system emails
			$admins = AECToolbox::getAdminEmailList();

			foreach ( $admins as $adminemail ) {
				if ( !empty( $adminemail ) ) {
					JUTility::sendMail( $adminEmail2, $adminEmail2, $adminemail, $subject2, $message2 );
				}
			}
		}

		if ( !empty( $params ) && is_array( $params ) ) {
			$this->params = $params;
		}

		$this->check();
		$this->store();
	}

}

class aecEventHandler
{
	function pingEvents()
	{
		$db = &JFactory::getDBO();

		// Load all events happening now or before now
		$query = 'SELECT `id`'
				. ' FROM #__acctexp_event'
				. ' WHERE `due_date` <= \'' . date( 'Y-m-d H:i:s' ) . '\''
	 			. ' AND `status` = \'waiting\''
				;
		$db->setQuery( $query );
		$events = $db->loadResultArray();

		// Call each event individually
		foreach ( $events as $evid ) {
			$event = new aecEvent( $db );
			$event->load( $evid );
			$event->trigger();
		}
	}

	// TODO: Finish function that, according to setting, cleans out old entries (like more than two weeks old default)
	function deleteOldEvents()
	{
		$db = &JFactory::getDBO();

		// Load all events happening now or before now
		$query = 'SELECT `id`'
				. ' FROM #__acctexp_event'
				. ' WHERE `due_date` <= \'' . date( 'Y-m-d H:i:s' ) . '\''
	 			. ' AND `status` = \'waiting\''
				;
		$db->setQuery( $query );
		$events = $db->loadResultArray();

		// Call each event individually
		foreach ( $events as $evid ) {
			$event = new aecEvent( $db );
			$event->load( $evid );
			$event->trigger();
		}
	}
}

class aecEvent extends serialParamDBTable
{
	/** @var int Primary key */
	var $id					= null;
	/** @var int */
	var $userid				= null;
	/** @var int */
	var $status				= null;
	/** @var string */
	var $type		 		= null;
	/** @var string */
	var $subtype	 		= null;
	/** @var int */
	var $appid				= null;
	/** @var string */
	var $event		 		= null;
	/** @var datetime */
	var $created_date		= null;
	/** @var datetime */
	var $due_date			= null;
	/** @var text */
	var $context 			= array();
	/** @var text */
	var $params 			= array();
	/** @var text */
	var $customparams		= array();

	/**
	* @param database A database connector object
	*/
	function aecEvent( &$db )
	{
		parent::__construct( '#__acctexp_event', 'id', $db );
	}

	function declareParamFields()
	{
		return array( 'context', 'params', 'customparams' );
	}

	function issue( $type, $subtype, $appid, $event, $userid, $due_date, $context=array(), $params=array(), $customparams=array() )
	{
		$app = JFactory::getApplication();

		$this->userid			= $userid;
		$this->status			= 'waiting';

		$this->type				= $type;
		$this->subtype			= $subtype;
		$this->appid			= $appid;
		$this->event			= $event;
		$this->created_date 	= date( 'Y-m-d H:i:s', ( (int) gmdate('U') ) );
		$this->due_date 		= $due_date;

		$this->context			= $context;
		$this->params			= $params;
		$this->customparams		= $customparams;

		$this->storeload();

		return $this->id;
	}

	function trigger()
	{
		$db = &JFactory::getDBO();

		if ( empty( $this->type ) ) {
			return null;
		}

		if ( empty( $this->event ) ) {
			return null;
		}

		$obj = null;

		switch ( $this->type ) {
			case 'mi':
				$obj = new microIntegration( $db );
				$obj->load( $this->appid );
				break;
		}

		if ( !empty( $obj ) ) {
			$return = $obj->aecEventHook( $this );

			if ( !is_array( $return ) ) {
				$this->status = 'done';
			} else {
				if ( isset( $return['reset_due_date'] ) ) {
					$this->status	= 'waiting';
					$this->due_date	= $return['reset_due_date'];
				}
			}

			return $this->storeload();
		}
	}
}

class PaymentProcessorHandler
{

	function PaymentProcessorHandler()
	{
		$this->pp_dir = JPATH_SITE . '/components/com_acctexp/processors';
	}

	function getProcessorList()
	{
		$list = AECToolbox::getFileArray( $this->pp_dir, 'php', false, true );

		$pp_list = array();
		foreach ( $list as $name ) {
			$parts		= explode( '.', $name );
			$pp_list[] = $parts[0];
		}

		return $pp_list;
	}

	function getProcessorIdfromName( $name )
	{
		$db = &JFactory::getDBO();

		$query = 'SELECT `id`'
				. ' FROM #__acctexp_config_processors'
				. ' WHERE `name` = \'' . $db->getEscaped( $name ) . '\'';
		$db->setQuery( $query );

		return $db->loadResult();
	}

	function getProcessorNamefromId( $id )
	{
		$db = &JFactory::getDBO();

		$query = 'SELECT `name`'
				. ' FROM #__acctexp_config_processors'
				. ' WHERE `id` = \'' . $db->getEscaped( $id ) . '\'';
		$db->setQuery( $query );

		return $db->loadResult();
	}

	/**
	 * gets installed and active processors
	 *
	 * @param bool	$active		get only active objects
	 * @return array of (active) payment processors
	 */
	function getInstalledObjectList( $active = false, $simple = false )
	{
		$db = &JFactory::getDBO();

		$query = 'SELECT `name`' . ( $simple ? '' : ', `active`, `id`' )
				. ' FROM #__acctexp_config_processors'
				;
		if ( $active ) {
			$query .= ' WHERE `active` = \'1\'';
		}
		$db->setQuery( $query );

		if ( $simple ) {
			return $db->loadResultArray();
		} else {
			return $db->loadObjectList();
		}
	}

	function getInstalledNameList($active=false)
	{
		$db = &JFactory::getDBO();

		$query = 'SELECT `name`'
				. ' FROM #__acctexp_config_processors'
				;
		if ( $active !== false ) {
			$query .= ' WHERE `active` = \'' . $active . '\'';
		}
		$db->setQuery( $query );

		return $db->loadResultArray();
	}

	function getObjectList( $array, $getinfo=false, $getsettings=false )
	{
		$excluded = array( 'free', 'none', 'transfer' );

		$list = array();
		foreach ( $array as $ppname ) {
			if ( empty( $ppname ) || in_array( $ppname, $excluded ) ) {
				continue;
			}

			$pp = new PaymentProcessor();

			if ( $pp->loadName( $ppname ) ) {
				$pp->init();

				if ( $getinfo ) {
					$pp->getInfo();
				}

				if ( $getsettings ) {
					$pp->getSettings();
				}
			}

			$list[$ppname] = $pp;
		}

		return $list;
	}

	function getSelectList( $selection="", $installed=false )
	{
		$pplist					= $this->getProcessorList();
		$pp_installed_list		= $this->getInstalledObjectList( false, true );

		asort($pplist);

		$pp_list_html			= array();
		foreach ( $pplist as $ppname ) {
			if ( in_array( $ppname, $pp_installed_list ) && !$installed ) {
				continue;
			} elseif ( !in_array( $ppname, $pp_installed_list ) && $installed ) {
				continue;
			}

			$readppname = ucwords( str_replace( '_', ' ', strtolower( $ppname ) ) );

			// Load Payment Processor
			$pp = new PaymentProcessor();
			if ( $pp->loadName( $ppname ) ) {
				$pp->getInfo();

				// Add to general PP List
				$pp_list_html[] = JHTML::_('select.option', $ppname, $readppname );
			}
		}

		$size = $installed ? 1 : max(min(count($pplist), 24), 2);

		return JHTML::_('select.genericlist', $pp_list_html, 'processor', 'size="' . $size . '"', 'value', 'text', $selection );
	}
}

class PaymentProcessor
{
	/** var object **/
	var $pph = null;
	/** var int **/
	var $id = null;
	/** var string **/
	var $processor_name = null;
	/** var object **/
	var $processor = null;
	/** var array **/
	var $settings = null;
	/** var array **/
	var $info = null;

	function PaymentProcessor()
	{
		// Init Payment Processor Handler
		$this->pph = new PaymentProcessorHandler ();
	}

	function loadName( $name )
	{
		if ( (strtolower( $name ) == 'free') || (strtolower( $name ) == 'none') ) {
			return null;
		}

		$db = &JFactory::getDBO();

		// Set Name
		$this->processor_name = strtolower( $name );

		// See if the processor is installed & set id
		$query = 'SELECT id, active'
				. ' FROM #__acctexp_config_processors'
				. ' WHERE `name` = \'' . $this->processor_name . '\''
				;
		$db->setQuery( $query );
		$res = $db->loadObject();

		if ( !empty( $res ) && is_object( $res ) ) {
			$this->id = $res->id ? $res->id : 0;
		}

		$file = $this->pph->pp_dir . '/' . $this->processor_name . '.php';

		// Check whether processor exists
		if ( file_exists( $file ) ) {
			// Call Integration file
			include_once $this->pph->pp_dir . '/' . $this->processor_name . '.php';

			// Initiate Payment Processor Class
			$class_name = 'processor_' . $this->processor_name;

			$this->processor = new $class_name( $db );
			$this->processor->load( $this->id );
			$this->processor->name = $this->processor_name;

			if ( is_object( $res ) ) {
				$this->processor->active = $res->active;
			} else {
				$this->processor->active = 0;
			}

			return true;
		} else {
			$short	= 'processor loading failure';
			$event	= 'When composing processor info list, tried to load processor: ' . $name;
			$tags	= 'processor,loading,error';
			$params = array();

			$eventlog = new eventLog( $db );
			$eventlog->issue( $short, $tags, $event, 128, $params );

			return false;
		}
	}

	function getNameById( $ppid )
	{
		$db = &JFactory::getDBO();

		// Fetch name from db and load processor
		$query = 'SELECT `name`'
				. ' FROM #__acctexp_config_processors'
				. ' WHERE `id` = \'' . $ppid . '\''
				;
		$db->setQuery( $query );
		$name = $db->loadResult();

		if ( $name ) {
			return $name;
		} else {
			return false;
		}
	}

	function loadId( $ppid )
	{
		$name = $this->getNameById( $ppid );

		if ( $name ) {
			return $this->loadName( $name );
		} else {
			return false;
		}
	}

	function fullInit()
	{
		if ( $this->init() ) {
			$this->getInfo();
			$this->getSettings();

			return true;
		} else {
			return false;
		}
	}

	function init()
	{
		if ( !$this->id ) {
			// Install and recurse
			$this->install();
			$this->init();
		} else {
			// Initiate processor from db
			if ( is_object( $this->processor ) && empty( $this->processor->id ) ) {
				return $this->processor->load( $this->id );
			} else {
				return true;
			}
		}
	}

	function install()
	{
		$db = &JFactory::getDBO();

		// Create new db entry
		$this->processor->load( 0 );

		// Call default values for Info and Settings
		$this->getInfo();
		$this->getSettings();

		// Set name and activate
		$this->processor->name		= $this->processor_name;
		$this->processor->active	= 1;

		// Set values from defaults and store
		$this->processor->info = $this->info;
		$this->processor->settings = $this->settings;
		$this->processor->storeload();

		$this->id = $this->processor->id;
	}

	function getInfo()
	{
		if ( !is_object( $this->processor ) ) {
			return false;
		}

		$this->info	=& $this->processor->info;
		$original	= $this->processor->info();

		foreach ( $original as $name => $var ) {
			if ( !isset( $this->info[$name] ) ) {
				$this->info[$name] = $var;
			}
		}
	}

	function getParamLang( $name )
	{
		$lang = JFactory::getLanguage();

		$nname = 'CFG_' . strtoupper( $this->processor_name ) . '_' . strtoupper($name);
		$gname = 'CFG_PROCESSOR_' . strtoupper($name);

		if ( $lang->hasKey( $nname ) ) {
			return JText::_( $nname );
		} elseif ( $lang->hasKey( $gname ) ) {
			return JText::_( $gname );
		} else {
			return JText::_( $nname );
		}
	}

	function getSettings()
	{
		if ( !is_object( $this->processor ) ) {
			return false;
		}

		$this->settings	=& $this->processor->settings;
		$original		= $this->processor->settings();

		if ( !is_array( $this->settings ) ) {
			$this->settings = $original;
		}

		if ( !isset( $this->settings['recurring'] ) && is_int( $this->is_recurring() ) ) {
			$original['recurring'] = 1;
		}

		foreach ( $original as $name => $var ) {
			if ( !isset( $this->settings[$name] ) ) {
				$this->settings[$name] = $var;
			}
		}
	}

	function exchangeSettings( $exchange )
	{
		 if ( !empty( $exchange ) ) {
			 foreach ( $exchange as $key => $value ) {
				if( is_string( $value ) ) {
					if ( strcmp( $value, '[[SET_TO_NULL]]' ) === 0 ) {
						// Exception for NULL case
						$this->settings[$key] = null;
					} else {
						if ( !is_null( $value ) || ( $value === "" ) ) {
							$this->settings[$key] = $value;
						}
					}
				} else {
					$this->settings[$key] = $value;
				}
			 }
		 }
	}

	function setSettings()
	{
		$this->processor->storeload();
	}

	function exchangeSettingsByPlan( $plan, $plan_params=null )
	{
		if ( empty( $this->settings ) ) {
			$this->getSettings();
		}

		if ( empty( $plan_params ) ) {
			$plan_params = $plan->getProcessorParameters( $this );
		}

		if ( isset( $plan_params['aec_overwrite_settings'] ) ) {
			unset( $plan_params['aec_overwrite_settings'] );
		}

		$this->exchangeSettings( $plan_params );
	}

	function getLogoImg()
	{
		$fname = $this->processor->getLogoFilename();

		if ( !empty( $fname ) ) {
			return '<img src="' . $this->getLogoPath() . '" alt="' . $this->processor_name . '" title="' . $this->processor_name .'" class="plogo" />';
		} else {
			return $this->info['longname'];
		}
	}

	function getLogoPath()
	{
		return JURI::root(true) . '/media/com_acctexp/images/site/' . $this->processor->getLogoFilename();
	}

	function is_recurring( $choice=null, $test=false )
	{
		// Warning: Here be Voodoo

		if ( isset( $this->is_recurring ) && !$test ) {
			return $this->is_recurring;
		}

		// Check for bogus choice
		if ( empty( $choice ) && ( $choice !== 0 ) && ( $choice !== '0' ) ) {
			$choice = null;
		}

		$return = false;

		// Load Info if not loaded yet
		if ( !isset( $this->info ) ) {
			$this->getInfo();
		}

		if ( isset( $this->settings['recurring'] ) ) {
			$rec_set = $this->info['recurring'];
		} else {
			$rec_set = null;
		}


		if ( !isset( $this->info['recurring'] ) ) {
			// Keep false
		} elseif ( ( $this->info['recurring'] > 1 ) && ( $rec_set !== 1 ) ) {
			if ( empty( $this->settings ) ) {
				$this->getSettings();
			}

			// If recurring = 2, the processor can
			// set this property on a per-plan basis
			if ( isset( $this->settings['recurring'] ) ) {
				$return = (int) $this->settings['recurring'];
			} else {
				$return = (int) $this->info['recurring'];
			}

			if ( ( !is_null( $choice ) ) && ( $return > 1 ) ) {
				$return = (int) $choice;
			}
		} elseif ( !empty( $this->info['recurring'] ) ) {
			$return = true;
		}

		$this->is_recurring = $return;

		return $return;
	}

	function storeload()
	{
		$this->processor->storeload();
	}

	function getBackendSettings()
	{
		if ( empty( $this->settings ) ) {
			$this->getSettings();
		}

		if ( $this->info['recurring'] == 2 ) {
			$settings = array_merge( array( 'recurring' => array( 'list_recurring' ) ), $this->processor->backend_settings() );
		} else {
			$settings = $this->processor->backend_settings();
		}

		$settings['generic_buttons']	= array( 'toggle' );

		if ( isset( $settings['aec_experimental'] ) ) {
			$settings['aec_experimental'] = "p";
			$this->settings['aec_experimental'] = '<div class="aec_processor_experimentalnote"><h1>' . JText::_('PP_GENERAL_PLEASE_NOTE') . '</h1><p>' . JText::_('PP_GENERAL_EXPERIMENTAL') . '</p></div>';
		}

		if ( isset( $settings['aec_insecure'] ) ) {
			$settings['aec_experimental'] = "p";
			$this->settings['aec_insecure'] = '<div class="aec_processor_experimentalnote"><h1>' . JText::_('PP_GENERAL_PLEASE_NOTE') . '</h1><p>' . JText::_('PP_GENERAL_INSECURE') . '</p></div>';
		}

		if ( !isset( $this->info ) ) {
			$this->getInfo();
		}

		if ( !empty( $this->info['cc_list'] ) ) {
			$settings['cc_icons']			= array( 'list' );

			$cc_array = explode( ',', $this->info['cc_list'] );

			if ( isset( $this->settings['cc_icons'] ) ) {
				$set = $this->settings['cc_icons'];
			} else {
				$set = $cc_array;
			}

			$cc = array();
			$ccs = array();
			foreach ( $cc_array as $ccname ) {
				$cc[] = JHTML::_('select.option', $ccname, $ccname );

				if ( in_array( $ccname, $set ) ) {
					$ccs[] = JHTML::_('select.option', $ccname, $ccname );
				}
			}

			$settings['lists']['cc_icons'] = JHTML::_( 'select.genericlist', $cc, $this->processor_name.'_cc_icons[]', 'size="4" multiple="multiple"', 'value', 'text', $ccs );
		}

		return $settings;
	}

	function checkoutAction( $int_var=null, $metaUser=null, $plan=null, $InvoiceFactory=null, $cart=null )
	{
		if ( empty( $this->settings ) ) {
			$this->getSettings();
		}

		if ( isset( $int_var['planparams']['aec_overwrite_settings'] ) ) {
			if ( !empty( $int_var['planparams']['aec_overwrite_settings'] ) ) {
				$this->exchangeSettingsByPlan( null, $int_var['planparams'] );
			}
		}

		if ( empty( $plan ) && !empty( $cart ) ) {
			$plan = aecCartHelper::getFirstCartItemObject( $cart );
		}

		$request = new stdClass();
		$request->parent			=& $this;
		$request->int_var			=& $int_var;
		$request->metaUser			=& $metaUser;
		$request->plan				=& $plan;
		$request->invoice			=& $InvoiceFactory->invoice;
		$request->items				=& $InvoiceFactory->items;
		$request->cart				=& $cart;

		return $this->processor->checkoutAction( $request, $InvoiceFactory );
	}

	function checkoutProcess( $int_var=null, $metaUser=null, $plan=null, $InvoiceFactory=null, $cart=null )
	{
		if ( empty( $this->settings ) ) {
			$this->getSettings();
		}

		if ( isset( $int_var['planparams']['aec_overwrite_settings'] ) ) {
			if ( !empty( $int_var['planparams']['aec_overwrite_settings'] ) ) {
				$this->exchangeSettingsByPlan( null, $int_var['planparams'] );
			}
		}

		if ( empty( $plan ) && !empty( $cart ) ) {
			$plan = aecCartHelper::getFirstCartItemObject( $cart );
		}

		$request = new stdClass();
		$request->parent			=& $this;
		$request->int_var			=& $int_var;
		$request->metaUser			=& $metaUser;
		$request->plan				=& $plan;
		$request->invoice			=& $InvoiceFactory->invoice;
		$request->items				=& $InvoiceFactory->items;
		$request->cart				=& $cart;

		return $this->processor->checkoutProcess( $request, $InvoiceFactory );
	}

	function customAction( $action, $invoice, $metaUser, $int_var=null )
	{
		if ( empty( $this->settings ) ) {
			$this->getSettings();
		}

		if ( ( $action == 'cancel' ) && !empty( $this->settings['minOccurrences'] ) ) {
			if ( ( $this->settings['minOccurrences'] > 1 ) && ( $invoice->counter < $this->settings['minOccurrences'] ) ) {
				$return['valid'] = 0;
				$return['error'] = 'Could not cancel your membership - a minimum of ' . $this->settings['minOccurrences'] . ' periods have to be paid.'
									. ' You have now paid for ' . $invoice->counter . ' cycles.';

				return $return;
			}
		}

		$method = 'customaction_' . $action;

		if ( method_exists( $this->processor, $method ) ) {
			$request = new stdClass();
			$request->parent			=& $this;
			$request->metaUser			=& $metaUser;
			$request->invoice			=& $invoice;
			$request->plan				=& $invoice->getObjUsage();
			$request->int_var			=& $int_var;

			return $this->processor->$method( $request );
		} else {
			return false;
		}
	}

	function customProfileTab( $action, $metaUser )
	{
		$s = $this->processor_name . '_';
		if ( strpos( $action, $s ) !== false ) {
			$action = str_replace( $s, '', $action );
		}

		if ( empty( $this->settings ) ) {
			$this->getSettings();
		}

		$method = 'customtab_' . $action;

		if ( method_exists( $this->processor, $method ) ) {
			$db = &JFactory::getDBO();

			$request = new stdClass();
			$request->parent			=& $this;
			$request->metaUser			=& $metaUser;

			$invoice = new Invoice( $db );
			$invoice->loadbySubscriptionId( $metaUser->objSubscription->id );

			$request->invoice			=& $invoice;


			return $this->processor->$method( $request );
		} else {
			return false;
		}
	}

	function getParamsHTML( $params, $values )
	{
		$return = null;
		if ( !empty( $values['params'] ) ) {
			if ( is_array( $values['params'] ) ) {
				if ( isset( $values['params']['lists'] ) ) {
					$lists = $values['params']['lists'];
					unset( $values['params']['lists'] );
				} else {
					$lists = null;
				}

				if ( count( $params['params'] ) > 2 ) {
					$table = 1;
					$return .= '<table>';
				} else {
					$table = 0;
				}

				foreach ( $values['params'] as $name => $entry ) {
					if ( !is_null( $name ) && !( $name == '' ) ) {
						$return .= aecHTML::createFormParticle( $name, $entry, $lists, $table ) . "\n";
					}
				}

				$return .= $table ? '</table>' : '';
			}
		}

		return $return;
	}

	function getParams( $params )
	{
		if ( empty( $this->settings ) ) {
			$this->getSettings();
		}

		if ( method_exists( $this->processor, 'Params' ) ) {
			return $this->processor->Params( $params );
		} else {
			return false;
		}
	}

	function getCustomPlanParams()
	{
		if ( empty( $this->settings ) ) {
			$this->getSettings();
		}

		if ( !isset( $this->info['recurring'] ) ) {
			$this->info['recurring'] = 0;
		}

		if ( $this->info['recurring'] == 2 ) {
			$settings = array_merge( array( 'recurring' => array( 'list_recurring' ) ), $this->processor->backend_settings() );
		} else {
			$settings = $this->processor->backend_settings();
		}

		$params = array();

		if ( $this->info['recurring'] == 2 ) {
			$params = array_merge( array( 'recurring' => array( 'list_recurring' ) ), $params );
		}

		if ( method_exists( $this->processor, 'CustomPlanParams' ) ) {
			$params = array_merge( $params, $this->processor->CustomPlanParams() );
		}

		if ( !empty( $params ) ) {
			return $params;
		} else {
			return false;
		}
	}

	function invoiceCreationAction( $objinvoice )
	{
		if ( empty( $this->settings ) ) {
			$this->getSettings();
		}

		if ( method_exists( $this->processor, 'invoiceCreationAction' ) ) {
			$this->processor->invoiceCreationAction( $objinvoice );
		} else {
			return false;
		}
	}

	function parseNotification( $post )
	{
		$db = &JFactory::getDBO();

		if ( empty( $this->settings ) ) {
			$this->getSettings();
		}

		$return = $this->processor->parseNotification( $post );

		// Check whether this is an ad-hoc notification
		if ( !empty( $return['_aec_createuser'] ) && empty( $return['invoice'] ) ) {
			// Identify usage
			$usage = 1;

			// Create new user account and fetch id
			$userid = AECToolbox::saveUserRegistration( 'com_acctexp', $return['_aec_createuser'], true, true, false );

			// Create Invoice
			$invoice = new Invoice( $db );
			$invoice->create( $userid, $usage, $this->processor_name );
			$invoice->computeAmount();

			// Set new return variable - we now know what invoice this is
			$return['invoice'] = $invoice->invoice_number;
		}

		// Always amend secondary ident codes
		if ( !empty( $return['secondary_ident'] )&& !empty( $return['invoice'] ) ) {

			$invoice = new Invoice( $db );
			$invoice->loadInvoiceNumber( $return['invoice'] );
			$invoice->secondary_ident = $return['secondary_ident'];
			$invoice->storeload();
		}

		if ( !empty( $return['_aec_createuser'] ) ) {
			unset( $return['_aec_createuser'] );
		}

		return $return;
	}

	function notificationError( $response, $error )
	{
		if ( method_exists( $this->processor, 'notificationError' ) ) {
			$this->processor->notificationError( $response, $error );
		}
	}

	function notificationSuccess( $response )
	{
		if ( method_exists( $this->processor, 'notificationSuccess' ) ) {
			$this->processor->notificationSuccess( $response );
		}
	}

	function validateNotification( $response, $post, $invoice )
	{
		if ( method_exists( $this->processor, 'validateNotification' ) ) {
			$response = $this->processor->validateNotification( $response, $post, $invoice );
		}

		return $response;
	}

	function instantvalidateNotification( $response, $post, $invoice )
	{
		if ( method_exists( $this->processor, 'instantvalidateNotification' ) ) {
			$response = $this->processor->instantvalidateNotification( $response, $post, $invoice );
		}

		return $response;
	}

	function prepareValidation( $subscription_list )
	{
		if ( empty( $this->settings ) ) {
			$this->getSettings();
		}

		if ( method_exists( $this->processor, 'prepareValidation' ) ) {
			$response = $this->processor->prepareValidation( $subscription_list );
		} else {
			$response = null;
		}

		return $response;
	}

	function validateSubscription( $subscription_id )
	{
		if ( empty( $this->settings ) ) {
			$this->getSettings();
		}

		$response = false;
		if ( method_exists( $this->processor, 'validateSubscription' ) ) {
			$db = &JFactory::getDBO();

			$subscription = new Subscription( $db );
			$subscription->load( $subscription_id );

			$allowed = array( "Trial", "Active" );

			if ( !in_array( $subscription->status, $allowed ) ) {
				return null;
			}

			$invoice = new Invoice( $db );
			$invoice->loadbySubscriptionId( $subscription_id );

			if ( empty( $invoice->id ) ) {
				return null;
			}

			$option = 'com_acctexp';

			$iFactory = new InvoiceFactory( null, null, null, $this->processor_name );

			$iFactory->userid = $subscription->userid;
			$iFactory->usage = $invoice->usage;
			$iFactory->processor = $this->processor_name;

			$iFactory->loadMetaUser();

			$iFactory->touchInvoice( $option, $invoice->invoice_number );

			$iFactory->puffer( $option );

			$iFactory->loadItems();

			$iFactory->loadItemTotal();

			$result = $this->processor->validateSubscription( $iFactory, $subscription );

			$resp = array();
			if ( isset( $result['raw'] ) ) {
				if ( is_array( $result['raw'] ) ) {
					$resp = $result['raw'];
				} else {
					$resp['response'] = $result['raw'];
				}
			}

			$iFactory->invoice->processorResponse( $iFactory, $result, $resp, true );

			if ( !empty( $result['valid'] ) ) {
				$response = true;
			}
		} else {
			$response = null;
		}

		return $response;
	}

	function registerProfileTabs()
	{
		if ( method_exists( $this->processor, 'registerProfileTabs' ) ) {
			$response = $this->processor->registerProfileTabs();
		} else {
			$response = null;
		}

		return $response;
	}

	function modifyCheckout( &$int_var, &$InvoiceFactory )
	{
		if ( method_exists( $this->processor, 'modifyCheckout' ) ) {
			$this->processor->modifyCheckout( $int_var, $InvoiceFactory );
		}
	}

	function notify_trail( $InvoiceFactory, $response )
	{
		if ( method_exists( $this->processor, 'notify_trail' ) ) {
			return $this->processor->notify_trail( $InvoiceFactory, $response );
		} else {
			return array();
		}
	}

	function getProfileTabs()
	{
		$addtabs = $this->registerProfileTabs();

		if ( empty( $addtabs ) ) {
			return array();
		}

		foreach ( $addtabs as $atk => $atv ) {
			$action = $this->processor_name . '_' . $atk;
			if ( isset( $tabs[$action] ) ) {
				continue;
			}

			$tabs[$action] = $atv;
		}

		return $tabs;
	}

	function getActions( $invoice, $subscription )
	{
		$actions = array();

		$actionarray = $this->processor->getActions( $invoice, $subscription );

		if ( !empty( $actionarray ) ) {
			foreach ( $actionarray as $action => $aoptions ) {
				$action = array( 'action' => $action, 'insert' => '' );

				if ( !empty( $aoptions ) ) {
					foreach ( $aoptions as $opt ) {
						switch ( $opt ) {
							case 'confirm':
								$action['insert'] .= ' onclick="return show_confirm(\'' . JText::_('AEC_YOUSURE') . '\')" ';
								break;
							default:
								break;
						}
					}
				}
			}

			$actions[] = $action;
		}

		return $actions;
	}
}

class processor extends serialParamDBTable
{
	/** @var int Primary key */
	var $id					= null;
	/** @var int */
	var $name				= null;
	/** @var int */
	var $active				= null;
	/** @var text */
	var $info				= null;
	/** @var text */
	var $settings			= null;
	/** @var text */
	var $params				= null;

	/**
	* @param database A database connector object
	*/
	function processor( &$db )
	{
		parent::__construct( '#__acctexp_config_processors', 'id', $db );
	}

	function declareParamFields()
	{
		return array( 'info', 'settings', 'params' );
	}

	function getLogoFilename()
	{
		return $this->name.'.png';
	}

	function loadName( $name )
	{
		$db = &JFactory::getDBO();

		$query = 'SELECT `id`'
				. ' FROM #__acctexp_config_processors'
				. ' WHERE `name` = \'' . $db->getEscaped( $name ) . '\''
				;
		$db->setQuery( $query );
		
		$id = $db->loadResult();

		if ( $id ) {
			return $this->load( $db->loadResult() );
		} else {
			return false;
		}
	}

	function createNew( $name, $info, $settings )
	{
		$this->id		= 0;
		$this->name		= $name;
		$this->active	= 1;
		$this->info		= $info;
		$this->settings	= $settings;

		$this->storeload();
	}

	function checkoutText()
	{
		return JText::_('CHECKOUT_BTN_INFO');
	}

	function checkoutAction( $request, $InvoiceFactory=null )
	{
		return '<p>' . AECToolbox::rewriteEngineRQ( $this->settings['info'], $request ) . '</p>';
	}

	function fileError( $text, $level=128, $tags="", $params=array() )
	{
		$db = &JFactory::getDBO();

		$eventlog = new eventLog( $db );

		$t = explode( ',', $tags );
		$t[] = $this->name;

		if ( !is_string( $text ) ) {
			$eventlog->issue( 'processor error', implode( ',', $t ), json_encode( $text ), $level );
		} else {
			$eventlog->issue( 'processor error', implode( ',', $t ), $text, $level, $params );
		}
	}

	function exchangeSettings( $settings, $exchange )
	{
		 if ( !empty( $exchange ) ) {
			 foreach ( $exchange as $key => $value ) {
				if ( !is_null( $value ) && ( $value != '' ) ) {
					if( is_string( $value ) ) {
						if ( strcmp( $value, '[[SET_TO_NULL]]' ) === 0 ) {
							// Exception for NULL case
							$settings[$key] = null;
						} else {
							$settings[$key] = $value;
						}
					} else {
						$settings[$key] = $value;
					}
				}
			 }
		 }

		return $settings;
	}

	function getActions( $invoice, $subscription )
	{
		if ( !empty( $this->info['actions'] ) ) {
			return $this->info['actions'];
		} else {
			return array();
		}
	}

	function customParams( $custom, $var, $request )
	{
		if ( !empty( $custom ) ) {
			$rw_params = AECToolbox::rewriteEngineRQ( $custom, $request );

			$params = explode( "\n", $rw_params );

			foreach ( $params as $custom ) {
				$paramsarray = explode( '=', $custom, 2 );

				if ( !empty( $paramsarray[0] ) && isset( $paramsarray[1] ) ) {
					$var[$paramsarray[0]] = $paramsarray[1];
				}
			}
		}

		return $var;
	}

	function parseNotification( $post )
	{
		$response = array();

		return $response;
	}

	function validateNotification( $response, $post, $invoice )
	{
		$response['valid'] = 0;

		return $response;
	}

	function transmitRequest( $url, $path, $content=null, $port=443, $curlextra=null, $header=null )
	{
		global $aecConfig;

		$response = null;

		if ( $aecConfig->cfg['curl_default'] ) {
			$response = $this->doTheCurl( $url, $content, $curlextra, $header );
			if ( $response === false ) {
				// If curl doesn't work try using fsockopen
				$response = $this->doTheHttp( $url, $path, $content, $port, $header, $curlextra );
			}
		} else {
			$response = $this->doTheHttp( $url, $path, $content, $port, $header );
			if ( $response === false ) {
				// If fsockopen doesn't work try using curl
				$response = $this->doTheCurl( $url, $content, $curlextra, $header, $curlextra );
			}
		}

		return $response;
	}

	function doTheHttp( $url, $path, $content, $port=443, $extra_header=null, $curlextra=null )
	{
		global $aecConfig;

		if ( strpos( $url, '://' ) === false ) {
			if ( $port == 443 ) {
				$purl = 'https://' . $url;
			} else {
				$purl = 'http://' . $url;
			}
		} else {
			$purl = $url;
		}

		$url_info = parse_url( $purl );

		if ( empty( $url_info ) ) {
				return false;
		}

		switch ( $url_info['scheme'] ) {
				case 'https':
						$scheme = 'ssl://';
						$port = 443;
						break;
				case 'http':
				default:
						$scheme = '';
						$port = 80;
						break;
		}

		$url = $scheme . $url_info['host'];

		if ( !empty( $aecConfig->cfg['use_proxy'] ) && !empty( $aecConfig->cfg['proxy'] ) ) {
			if ( !empty( $aecConfig->cfg['proxy_port'] ) ) {
				$proxyport = $aecConfig->cfg['proxy_port'];
			} else {
				$proxyport = $port;
			}

			$connection = fsockopen( $aecConfig->cfg['proxy'], $proxyport, $errno, $errstr, 30 );
		} else {
			$connection = fsockopen( $url, $port, $errno, $errstr, 30 );
		}

		// Emulate some cURL functionality
		if ( !empty( $curlextra ) && function_exists( "stream_context_set_params" ) ) {
			if ( isset( $curlextra['verify_peer'] ) && isset( $curlextra['allow_self_signed'] ) ) {
				$set_params = array( 'ssl' => array( 'verify_peer' => $curlextra['verify_peer'],'allow_self_signed' => $curlextra['allow_self_signed'] ) );

				stream_context_set_params( $connection, $set_params );
			}
		}

		if ( $connection === false ) {
			$db = &JFactory::getDBO();

			if ( $errno == 0 ) {
				$errstr .= " This is usually an SSL error.  Check if your server supports fsocket open via SSL.";
			}

			$short	= 'fsockopen failure';
			$event	= 'Trying to establish connection with ' . $url . ' failed with Error #' . $errno . ' ( "' . $errstr . '" ) - will try cURL instead. If Error persists and cURL works, please permanently switch to using that!';
			$tags	= 'processor,payment,phperror';
			$params = array();

			$eventlog = new eventLog( $db );
			$eventlog->issue( $short, $tags, $event, 128, $params );

			return false;
		} else {
		    if ( !empty( $aecConfig->cfg['use_proxy'] ) && !empty( $aecConfig->cfg['proxy'] ) ) {
				$hosturl = $aecConfig->cfg['proxy'];
		    } else {
		    	$hosturl = $url_info['host'];
		    }

			$header_array["Host"] = $hosturl;

			if ( !empty( $aecConfig->cfg['use_proxy'] ) && !empty( $aecConfig->cfg['proxy'] ) ) {
				if ( !empty( $aecConfig->cfg['proxy_username'] ) && !empty( $aecConfig->cfg['proxy_password'] ) ) {
					$header_array["Proxy-Authorization"] = "Basic ". base64_encode( $aecConfig->cfg['proxy_username'] . ":" . $aecConfig->cfg['proxy_password'] );
				}
			}

			$header_array["User-Agent"] = "PHP Script";
			$header_array["Content-Type"] = "application/x-www-form-urlencoded";

			if ( !empty( $content ) ) {
				$header_array["Content-Length"] = strlen( $content );
			}

			if ( !empty( $extra_header ) ) {
				foreach ( $extra_header as $h => $v ) {
					$header_array[$h] = $v;
				}
			}

			$header_array["Connection"] = "Close";

			if ( !empty( $content ) ) {
				$header = "POST " . $path . " HTTP/1.0\r\n";
			} else {
				$header = "GET " . $path . " HTTP/1.0\r\n";
			}

			foreach ( $header_array as $h => $v ) {
				$header .=	$h . ": " . $v . "\r\n";
			}

			$header .= "\r\n";

			if ( !empty( $content ) ) {
				$header .= $content;
			}

			fwrite( $connection, $header );

			$res = "";
			if ( function_exists( 'stream_set_timeout' ) ) {
				stream_set_timeout( $connection, 300 );

				$info = stream_get_meta_data( $connection );

				while ( !feof( $connection ) && ( !$info["timed_out"] ) ) {
					$res = fgets( $connection, 8192 );
				}

		        if ( $info["timed_out"] ) {
					$db = &JFactory::getDBO();

					$short	= 'fsockopen failure';
					$event	= 'Trying to establish connection with ' . $url . ' timed out - will try cURL instead. If Error persists and cURL works, please permanently switch to using that!';
					$tags	= 'processor,payment,phperror';
					$params = array();

					$eventlog = new eventLog( $db );
					$eventlog->issue( $short, $tags, $event, 128, $params );
		        }
			} else {
				while ( !feof( $connection ) ) {
					$res = fgets( $connection, 1024 );
				}
			}

			fclose( $connection );

			return $res;
		}
	}

	function doTheCurl( $url, $content, $curlextra=null, $header=null )
	{
		global $aecConfig;

		if ( !function_exists( 'curl_init' ) ) {
			$response = false;

			$db = &JFactory::getDBO();
			$short	= 'cURL failure';
			$event	= 'Trying to establish connection with ' . $url . ' failed - curl_init is not available - will try fsockopen instead. If Error persists and fsockopen works, please permanently switch to using that!';
			$tags	= 'processor,payment,phperror';
			$params = array();

			$eventlog = new eventLog( $db );
			$eventlog->issue( $short, $tags, $event, 128, $params );
			return false;
		}

		if ( empty( $curlextra ) ) {
			$curlextra = array();
		}

		// Preparing cURL variables as array, to possibly overwrite them with custom settings by the processor
		$curl_calls = array();
		$curl_calls[CURLOPT_URL]			= $url;
		$curl_calls[CURLOPT_RETURNTRANSFER]	= true;
		$curl_calls[CURLOPT_HTTPHEADER]		= array( 'Content-Type: text/xml' );
		$curl_calls[CURLOPT_HEADER]			= false;

		if ( !empty( $content ) ) {
			$curl_calls[CURLOPT_POST]			= true;
			$curl_calls[CURLOPT_POSTFIELDS]		= $content;
		}

		if ( !empty( $aecConfig->cfg['ssl_verifypeer'] ) ) {
			$curl_calls[CURLOPT_SSL_VERIFYPEER]	= $aecConfig->cfg['ssl_verifypeer'];
		} else {
			$curl_calls[CURLOPT_SSL_VERIFYPEER]	= false;
		}

		if ( !empty( $aecConfig->cfg['ssl_verifyhost'] ) ) {
			$curl_calls[CURLOPT_SSL_VERIFYHOST]	= $aecConfig->cfg['ssl_verifyhost'];
		} else {
			$curl_calls[CURLOPT_SSL_VERIFYHOST]	= false;
		}

		if ( !empty( $aecConfig->cfg['use_proxy'] ) && !empty( $aecConfig->cfg['proxy'] ) ) {
			$curl_calls[CURLOPT_HTTPPROXYTUNNEL]	= true;
			$curl_calls[CURLOPT_PROXY]				= $aecConfig->cfg['proxy'];

			if ( !empty( $aecConfig->cfg['proxy_port'] ) ) {
				$curl_calls[CURLOPT_PROXYPORT]		= $aecConfig->cfg['proxy_port'];
			}

			if ( !empty( $aecConfig->cfg['proxy_username'] ) && !empty( $aecConfig->cfg['proxy_password'] ) ) {
				$curl_calls[CURLOPT_PROXYUSERPWD]	= $aecConfig->cfg['proxy_username'].":".$aecConfig->cfg['proxy_password'];
			}
		}

		// Set or replace cURL params
		if ( !empty( $curlextra ) ) {
			foreach( $curlextra as $name => $value ) {
				if ( $value == '[[unset]]' ) {
					if ( isset( $curl_calls[$name] ) ) {
						unset( $curl_calls[$name] );
					}
				} else {
					$curl_calls[$name] = $value;
				}
			}
		}

		// Set cURL params
		$ch = curl_init();
		foreach ( $curl_calls as $name => $value ) {
			curl_setopt( $ch, $name, $value );
		}

		$response = curl_exec( $ch );

		if ( $response === false ) {
			$db = &JFactory::getDBO();

			$short	= 'cURL failure';
			$event	= 'Trying to establish connection with ' . $url . ' failed with Error #' . curl_errno( $ch ) . ' ( "' . curl_error( $ch ) . '" ) - will try fsockopen instead. If Error persists and fsockopen works, please permanently switch to using that!';
			$tags	= 'processor,payment,phperror';
			$params = array();

			$eventlog = new eventLog( $db );
			$eventlog->issue( $short, $tags, $event, 128, $params );
		}

		curl_close( $ch );

		return $response;
	}

}

class XMLprocessor extends processor
{
	function checkoutAction( $request, $InvoiceFactory=null )
	{
		global $aecConfig;

		if ( method_exists( $this, 'checkoutform' ) ) {
			$var = $this->checkoutform( $request );
		} else {
			$var = array();
		}

		if ( isset( $var['aec_alternate_checkout'] ) ) {
			$url = $var['aec_alternate_checkout'];

			unset( $var['aec_alternate_checkout'] );
		} else {
			if ( isset( $this->info['secure'] ) ) {
				$secure = $this->info['secure'];
			} else {
				$secure = false;
			}
			
			$url = AECToolbox::deadsureURL( 'index.php?option=com_acctexp&task=checkout', $secure );
		}

		if ( isset( $var['aec_remove_std_vars'] ) ) {
			$stdvars = false;

			unset( $var['aec_remove_std_vars'] );
		} else {
			$stdvars = true;
		}

		$return = '<form action="' . $url . '" method="post"' . ( $aecConfig->cfg['checkoutform_jsvalidation'] ? ' class="form-validate"' : '' ) . '>' . "\n";
		$return .= $this->getParamsHTML( $var ) . '<br /><br />';

		if ( $stdvars ) {
			$return .= $this->getStdFormVars( $request );
		}

		$return .= '<button type="submit" class="button aec-btn btn btn-primary' . ( $aecConfig->cfg['checkoutform_jsvalidation'] ? ' validate' : '' ) . '" id="aec-checkout-btn"><i class="icon-shopping-cart icon-white"></i>' . JText::_('BUTTON_CHECKOUT') . '</button>' . "\n";
		$return .= '</form>' . "\n";

		return $return;
	}

	function getStdFormVars( $request )
	{
		$return = '<input type="hidden" name="invoice" value="' . $request->int_var['invoice'] . '" />' . "\n";
		$return .= '<input type="hidden" name="processor" value="' . $this->name . '" />' . "\n";
		$return .= '<input type="hidden" name="userid" value="' . $request->metaUser->userid . '" />' . "\n";
		$return .= '<input type="hidden" name="task" value="checkout" />' . "\n";

		return $return;
	}

	function getParamsHTML( $params )
	{
		$return = null;
		if ( !empty( $params['params'] ) ) {
			if ( is_array( $params['params'] ) ) {
				if ( isset( $params['params']['lists'] ) ) {
					$lists = $params['params']['lists'];
					unset( $params['params']['lists'] );
				} else {
					$lists = null;
				}

				$hastabs = false;
				foreach ( $params['params'] as $entry ) {
					if ( $entry[0] == 'tabberstart' ) {
						$hastabs = true;
					}
				}

				if ( !$hastabs ) {
					$return .= '<div class="aec-checkout-params">';
				}

				if ( count( $params['params'] ) > 2 ) {
					$table = 1;
					$return .= '<table>';
				} else {
					$table = 0;
				}

				foreach ( $params['params'] as $name => $entry ) {
					if ( !empty( $name ) || ( $name === 0 ) ) {
						if ( $entry[0] == 'tabberstart' ) {
							$return .= '</td></tr></table>';
						}

						$return .= aecHTML::createFormParticle( $name, $entry, $lists, $table ) . "\n";

						if ( $entry[0] == 'tabberend' ) {
							$return .= '<div class="aec-checkout-params"><table><tr><td>';
						}
					}
				}

				$return .= $table ? '</table>' : '';
				$return .= '</div>';
			}
		}

		return $return;
	}

	function getMULTIPAYform( $var, $array )
	{
		$app = JFactory::getApplication();

		$nlist	= array();
		$prefix	= array();
		$main	= array();

		// We need to separate two blocks - prefix tabberstart generation and put the content inside
		$prefix[] = array( 'tabberstart', '', '', '' );
		$prefix[] = array( 'tabregisterstart', '', '', '' );

		foreach ( $array as $name => $content ) {
			$nu = strtoupper( $name );

			$fname = 'get'.$nu.'form';

			// Only allow to pass if std function exists
			if ( function_exists( 'XMLprocessor::'.$fname ) ) {
				$nl = strtolower( $name );

				// Register tab in prefix
				$prefix[] = array( 'tabregister', $nl.'details', JText::_( 'AEC_'.$nu.'FORM_TABNAME' ), true );

				// Actual tab code
				$main[] = array( 'tabstart', $nl.'details', true, '' );
				$main = $this->$fname( $main, $content['values'], $content['vcontent'] );
				$main[] = array( 'tabend', '', '', '' );
			}
		}

		$prefix[] = array( 'tabregisterend', '', '', '' );

		$var['params'] = array_merge( $var['params'], $prefix );
		$var['params'] = array_merge( $var['params'], $main );

		$var['params'][] = array( 'tabberend', '', '', '' );

		return $var;
	}

	function getCCform( $var=array(), $values=null, $content=null )
	{
		if ( empty( $values ) ) {
			$values = array( 'card_number', 'card_exp_month', 'card_exp_year' );
		}

		foreach ( $values as $value ) {
			if ( strpos( $value, '*' ) ) {
				$pf = '*';

				$value = substr( $value, 0, -1 );
			} else {
				$pf = '';
			}

			$translatelist = array( 'card_type' => 'cardType',
									'card_number' => 'cardNumber',
									'card_exp_month' => 'expirationMonth',
									'card_exp_year' => 'expirationYear',
									'card_cvv2' => 'cardVV2'
									);

			if ( isset( $content[$value] ) ) {
				$vcontent = $content[$value];
			} elseif ( isset( $content[$translatelist[$value]] ) ) {
				$vcontent = $content[$translatelist[$value]];
			} else {
				$vcontent = '';
			}

			switch ( strtolower( $value ) ) {
				case 'card_type':
					$cctlist = array(	'visa' => 'Visa',
										'mastercard' => 'MasterCard',
										'discover' => 'Discover',
										'amex' => 'American Express'
										);

					$options = array();
					foreach ( $cctlist as $ccname => $cclongname ) {
						$options[] = JHTML::_('select.option', $ccname, $cclongname );
					}

					$var['params']['lists']['cardType'] = JHTML::_( 'select.genericlist', $options, 'cardType', 'size="1" style="width:120px;" class="aec_formfield" title="'.JText::_('AEC_CCFORM_CARDNUMBER_DESC').'" autocomplete="off" ', 'value', 'text', $vcontent );
					$var['params']['cardType'] = array( 'list', JText::_('AEC_CCFORM_CARDTYPE_NAME').$pf );
					break;
				case 'card_number':
					// Request the Card number
					$var['params']['cardNumber'] = array( 'inputC', JText::_('AEC_CCFORM_CARDNUMBER_NAME').$pf, JText::_('AEC_CCFORM_CARDNUMBER_DESC') . '" autocomplete="off', $vcontent );
					break;
				case 'card_exp_month':
					// Create a selection box with 12 months
					$months = array();
					for( $i = 1; $i < 13; $i++ ){
						$month = str_pad( $i, 2, "0", STR_PAD_LEFT );
						$months[] = JHTML::_('select.option', $month, $month );
					}

					$var['params']['lists']['expirationMonth'] = JHTML::_( 'select.genericlist', $months, 'expirationMonth', 'size="1" class="aec_formfield" style="width:50px;" title="'.JText::_('AEC_CCFORM_EXPIRATIONMONTH_DESC').'" autocomplete="off"', 'value', 'text', $vcontent );
					$var['params']['expirationMonth'] = array( 'list', JText::_('AEC_CCFORM_EXPIRATIONMONTH_NAME').$pf, JText::_('AEC_CCFORM_EXPIRATIONMONTH_DESC') );
					break;
				case 'card_exp_year':
					// Create a selection box with the next 10 years
					$year = date('Y');
					$years = array();

					for ( $i = $year; $i < $year + 15; $i++ ) {
						$years[] = JHTML::_('select.option', $i, $i );
					}

					$var['params']['lists']['expirationYear'] = JHTML::_( 'select.genericlist', $years, 'expirationYear', 'size="1" class="aec_formfield" style="width:70px;" title="'.JText::_('AEC_CCFORM_EXPIRATIONYEAR_DESC').'" autocomplete="off"', 'value', 'text', $vcontent );
					$var['params']['expirationYear'] = array( 'list', JText::_('AEC_CCFORM_EXPIRATIONYEAR_NAME').$pf, JText::_('AEC_CCFORM_EXPIRATIONYEAR_DESC') );
					break;
				case 'card_cvv2':
					$var['params']['cardVV2'] = array( 'inputC', JText::_('AEC_CCFORM_CARDVV2_NAME').$pf, JText::_('AEC_CCFORM_CARDVV2_DESC') . '" autocomplete="off', null );
					break;
			}
		}

		return $var;
	}

	function getECHECKform( $var=array(), $values=null, $content=null )
	{
		if ( empty( $values ) ) {
			$values = array( 'routing_no', 'account_no', 'account_name', 'bank_name' );
		}

		foreach ( $values as $value ) {
			if ( strpos( $value, '*' ) ) {
				$pf = '*';

				$value = substr( $value, 0, -1 );
			} else {
				$pf = '';
			}

			if ( isset( $content[$value] ) ) {
				$vcontent = $content[$value];
			} else {
				$vcontent = '';
			}

			switch ( strtolower( $value ) ) {
				case 'routing_no':
					$var['params']['routing_no'] = array( 'inputC', JText::_('AEC_ECHECKFORM_ROUTING_NO_NAME').$pf, JText::_('AEC_ECHECKFORM_ROUTING_NO_DESC') . '" autocomplete="off', $vcontent );
					break;
				case 'account_no':
					$var['params']['account_no'] = array( 'inputC', JText::_('AEC_ECHECKFORM_ACCOUNT_NO_NAME').$pf, JText::_('AEC_ECHECKFORM_ACCOUNT_NO_DESC') . '" autocomplete="off', $vcontent );
					break;
				case 'account_name':
					$var['params']['account_name'] = array( 'inputC', JText::_('AEC_ECHECKFORM_ACCOUNT_NAME_NAME').$pf, JText::_('AEC_ECHECKFORM_ACCOUNT_NAME_DESC') . '" autocomplete="off', $vcontent );
					break;
				case 'bank_name':
					$var['params']['bank_name'] = array( 'inputC', JText::_('AEC_ECHECKFORM_BANK_NAME_NAME').$pf, JText::_('AEC_ECHECKFORM_BANK_NAME_DESC') . '" autocomplete="off', $vcontent );
					break;
			}
		}

		return $var;
	}

	function getUserform( $var=array(), $values=null, $metaUser=null, $content=array() )
	{
		$lang = JFactory::getLanguage();

		global $aecConfig;

		if ( empty( $values ) ) {
			$values = array( 'firstname', 'lastname' );
		}

		$name = array( '', '' );

		if ( is_object( $metaUser ) ) {
			if ( isset( $metaUser->cmsUser->name ) ) {
				$name = explode( ' ', $metaUser->cmsUser->name );

				if ( empty( $vcontent['firstname'] ) ) {
					$vcontent['firstname'] = $name[0];
				}

				if ( empty( $vcontent['lastname'] ) && isset( $name[1] ) ) {
					$vcontent['lastname'] = $name[1];
				}
			}
		}

		$aecConfig->cfg['user_checkout_prefill'];

		$fieldlist = explode( "\n", AECToolbox::rewriteEngine( $aecConfig->cfg['user_checkout_prefill'], $metaUser ) );

		$cfgarray = array();
		foreach ( $fieldlist as $content ) {
			$c = explode( '=', $content, 2 );

			if ( !empty( $c[0] ) ) {
				if ( !empty( $c[1] ) ) {
					$cfgarray[$c[0]] = trim( $c[1] );
				} else {
					$cfgarray[$c[0]] = "";
				}
			}
		}

		$translatelist = array( 'firstname' => 'billFirstName',
								'lastname' => 'billLastName',
								'address' => 'billAddress',
								'address2' => 'billAddress2',
								'city' => 'billCity',
								'nonus' => 'billNonUs',
								'state' => 'billState',
								'state_us' => 'billState',
								'state_usca' => 'billState',
								'zip' => 'billZip',
								'country_list' => 'billCountry',
								'country3_list' => 'billCountry',
								'country' => 'billCountry',
								'phone' => 'billPhone',
								'fax' => 'billFax',
								'company' => 'billCompany'
								);

		$cfgtranslatelist = array( 'state_us' => 'state',
								'state_usca' => 'state',
								'country_list' => 'country',
								'country3_list' => 'country'
								);

		foreach ( $values as $value ) {
			if ( strpos( $value, '*' ) ) {
				$pf = '*';

				$value = substr( $value, 0, -1 );
			} else {
				$pf = '';
			}

			$vcontent = '';
			if ( isset( $content[$value] ) ) {
				$vcontent = $content[$value];
			} elseif( isset( $translatelist[$value] ) ) {
				if ( isset( $content[$translatelist[$value]] ) ) {
					$vcontent = $content[$translatelist[$value]];
				}
			}

			if ( empty( $vcontent ) ) {
				if ( isset( $cfgtranslatelist[$value] ) ) {
					$xvalue = $cfgtranslatelist[$value];
				} else {
					$xvalue = $value;
				}

				if ( !empty( $cfgarray[$xvalue] ) ) {
					$vcontent = $cfgarray[$xvalue];
				}
			}

			switch ( strtolower( $value ) ) {
				case 'firstname':
					$var['params']['billFirstName'] = array( 'inputC', JText::_('AEC_USERFORM_BILLFIRSTNAME_NAME').$pf, JText::_('AEC_USERFORM_BILLFIRSTNAME_DESC'), $vcontent );
					break;
				case 'lastname':
					$var['params']['billLastName'] = array( 'inputC', JText::_('AEC_USERFORM_BILLLASTNAME_NAME').$pf, JText::_('AEC_USERFORM_BILLLASTNAME_DESC'), $vcontent );
					break;
				case 'address':
					$var['params']['billAddress'] = array( 'inputC', JText::_('AEC_USERFORM_BILLADDRESS_NAME').$pf, JText::_('AEC_USERFORM_BILLADDRESS_DESC'), $vcontent );
					break;
				case 'address2':
					$var['params']['billAddress2'] = array( 'inputC', JText::_('AEC_USERFORM_BILLADDRESS2_NAME').$pf, JText::_('AEC_USERFORM_BILLADDRESS2_DESC'), $vcontent );
					break;
				case 'city':
					$var['params']['billCity'] = array( 'inputC', JText::_('AEC_USERFORM_BILLCITY_NAME').$pf, JText::_('AEC_USERFORM_BILLCITY_DESC'), $vcontent );
					break;
				case 'nonus':
					$var['params']['billNonUs'] = array( 'checkbox', JText::_('AEC_USERFORM_BILLNONUS_NAME').$pf, 1, $vcontent, JText::_('AEC_USERFORM_BILLNONUS_DESC') );
					break;
				case 'state':
					$var['params']['billState'] = array( 'inputC', JText::_('AEC_USERFORM_BILLSTATE_NAME').$pf, JText::_('AEC_USERFORM_BILLSTATE_DESC'), $vcontent );
					break;
				case 'state_us':
					$states = array( '', '--- United States ---', 'AK', 'AL', 'AR', 'AZ', 'CA', 'CO', 'CT', 'DC', 'DE', 'FL', 'GA', 'HI',
										'IA', 'ID', 'IL', 'IN', 'KS', 'KY', 'LA', 'MA', 'MD', 'ME',
										'MI', 'MN', 'MO', 'MS', 'MT', 'NC', 'ND', 'NE', 'NH', 'NJ',
										'NM', 'NV', 'NY', 'OH', 'OK', 'OR', 'PA', 'RI', 'SC', 'SD',
										'TN', 'TX', 'UT', 'VA', 'VT', 'WA', 'WI', 'WV', 'WY', 'AA',
										'AE', 'AP', 'AS', 'FM', 'GU', 'MH', 'MP', 'PR', 'PW', 'VI'
										);

					$statelist = array();
					foreach ( $states as $state ) {
						if ( strpos( $state, '---' ) !== false ) {
							$statelist[] = JHTML::_('select.option', '" disabled="disabled', $state );
						} else {
							$statelist[] = JHTML::_('select.option', $state, $state );
						}
					}

					$var['params']['lists']['billState'] = JHTML::_( 'select.genericlist', $statelist, 'billState', 'size="1" class="aec_formfield" title="'.JText::_('AEC_USERFORM_BILLSTATE_DESC').'"', 'value', 'text', $vcontent );
					$var['params']['billState'] = array( 'list', JText::_('AEC_USERFORM_BILLSTATE_NAME').$pf, JText::_('AEC_USERFORM_BILLSTATE_DESC') );
					break;
				case 'state_usca':
					$states = array( '', '--- United States ---', 'AK', 'AL', 'AR', 'AZ', 'CA', 'CO', 'CT', 'DC', 'DE', 'FL', 'GA', 'HI',
										'IA', 'ID', 'IL', 'IN', 'KS', 'KY', 'LA', 'MA', 'MD', 'ME',
										'MI', 'MN', 'MO', 'MS', 'MT', 'NC', 'ND', 'NE', 'NH', 'NJ',
										'NM', 'NV', 'NY', 'OH', 'OK', 'OR', 'PA', 'RI', 'SC', 'SD',
										'TN', 'TX', 'UT', 'VA', 'VT', 'WA', 'WI', 'WV', 'WY', 'AA',
										'AE', 'AP', 'AS', 'FM', 'GU', 'MH', 'MP', 'PR', 'PW', 'VI',
										'--- Canada ---','AB','BC','MB','NB','NL','NT','NS','NU','ON','PE','QC','SK','YT'
										);

					$statelist = array();
					foreach ( $states as $state ) {
						if ( strpos( $state, '---' ) !== false ) {
							$statelist[] = JHTML::_('select.option', '" disabled="disabled', $state );
						} else {
							$statelist[] = JHTML::_('select.option', $state, $state );
						}
					}

					$var['params']['lists']['billState'] = JHTML::_( 'select.genericlist', $statelist, 'billState', 'size="1" class="aec_formfield" title="'.JText::_('AEC_USERFORM_BILLSTATEPROV_DESC').'"', 'value', 'text', $vcontent );
					$var['params']['billState'] = array( 'list', JText::_('AEC_USERFORM_BILLSTATEPROV_NAME').$pf, JText::_('AEC_USERFORM_BILLSTATEPROV_DESC') );
					break;
				case 'zip':
					$var['params']['billZip'] = array( 'inputC', JText::_('AEC_USERFORM_BILLZIP_NAME').$pf, JText::_('AEC_USERFORM_BILLZIP_DESC'), $vcontent );
					break;
				case 'country_list':
					$countries = AECToolbox::getCountryCodeList();

					$countrylist[] = JHTML::_('select.option', '" disabled="disabled', JText::_('COUNTRYCODE_SELECT') );

					if ( empty( $vcontent ) ) {
						$vcontent = 'US';
					}

					$countrylist = array();
					foreach ( $countries as $country ) {
						if ( !empty( $country ) ) {
							$cname = JText::_( 'COUNTRYCODE_' . $country );

							if ( $vcontent == $cname ) {
								$vcontent = $country;
							}

							$countrylist[] = JHTML::_('select.option', $country, $cname );
						}
					}

					$var['params']['lists']['billCountry'] = JHTML::_( 'select.genericlist', $countrylist, 'billCountry', 'size="1" class="aec_formfield" title="'.JText::_('AEC_USERFORM_BILLCOUNTRY_DESC').'"', 'value', 'text', $vcontent );
					$var['params']['billCountry'] = array( 'list', JText::_('AEC_USERFORM_BILLCOUNTRY_NAME').$pf, JText::_('AEC_USERFORM_BILLCOUNTRY_DESC') );
					break;
				case 'country3_list':
					$countries = AECToolbox::getCountryCodeList( 'num' );

					if ( empty( $vcontent ) ) {
						$vcontent = 826;
					}

					$conversion = AECToolbox::ISO3166_conversiontable( 'num', 'a2' );

					$countrylist = array();
					$countrylist[] = JHTML::_('select.option', '" disabled="disabled', JText::_('COUNTRYCODE_SELECT') );

					foreach ( $countries as $country ) {
						if ( $lang->hasKey( 'COUNTRYCODE_' . $conversion[$country] ) ) {
							$cname = JText::_( 'COUNTRYCODE_' . $conversion[$country] );

							if ( $vcontent == $cname ) {
								$vcontent = $country;
							}

							$countrylist[] = JHTML::_('select.option', $country, $cname );
						} elseif ( is_null( $country ) ) {
							$countrylist[] = JHTML::_('select.option', '" disabled="disabled', " -- -- -- -- -- -- " );
						}
					}

					$var['params']['lists']['billCountry'] = JHTML::_( 'select.genericlist', $countrylist, 'billCountry', 'size="1" class="aec_formfield" title="'.JText::_('AEC_USERFORM_BILLCOUNTRY_DESC').'"', 'value', 'text', $vcontent );
					$var['params']['billCountry'] = array( 'list', JText::_('AEC_USERFORM_BILLCOUNTRY_NAME').$pf, JText::_('AEC_USERFORM_BILLCOUNTRY_DESC') );
					break;
				case 'country':
					$var['params']['billCountry'] = array( 'inputC', JText::_('AEC_USERFORM_BILLCOUNTRY_NAME').$pf, JText::_('AEC_USERFORM_BILLCOUNTRY_DESC'), $vcontent );
					break;
				case 'phone':
					$var['params']['billPhone'] = array( 'inputC', JText::_('AEC_USERFORM_BILLPHONE_NAME').$pf, JText::_('AEC_USERFORM_BILLPHONE_DESC'), $vcontent );
					break;
				case 'fax':
					$var['params']['billFax'] = array( 'inputC', JText::_('AEC_USERFORM_BILLFAX_NAME').$pf, JText::_('AEC_USERFORM_BILLFAX_DESC'), $vcontent );
					break;
				case 'company':
					$var['params']['billCompany'] = array( 'inputC', JText::_('AEC_USERFORM_BILLCOMPANY_NAME').$pf, JText::_('AEC_USERFORM_BILLCOMPANY_DESC'), $vcontent );
					break;
			}
		}

		return $var;
	}

	function getFormInfo( $var=array(), $values=null )
	{
		if ( empty( $values ) ) {
			$values = array( 'asterisk' );
		}

		foreach ( $values as $value ) {
			switch ( strtolower( $value ) ) {
				case 'asterisk':
					$var['params']['asteriskInfo'] = array( 'p', 0, JText::_('AEC_FORMINFO_ASTERISK'), null, ' class="asterisk-info"' );
					break;
			}
		}

		return $var;
	}

	function sanitizeRequest( &$request )
	{
		if ( isset( $request->int_var['params']['cardNumber'] ) ) {
			$pfx = "";
			if ( strpos( $request->int_var['params']['cardNumber'], 'XXXX' ) !== false ) {
				$pfx = "XXX";
			}

			$request->int_var['params']['cardNumber'] = $pfx . preg_replace( '/[^0-9]+/i', '', $request->int_var['params']['cardNumber'] );
		}

		return true;
	}

	function checkoutProcess( $request, $InvoiceFactory )
	{
		$this->sanitizeRequest( $request );

		// Create the xml string
		$xml = $this->createRequestXML( $request );

		// Transmit xml to server
		$response = $this->transmitRequestXML( $xml, $request );

		if ( empty( $response['invoice'] ) ) {
			$response['invoice'] = $request->invoice->invoice_number;
		}

		if ( $request->invoice->invoice_number != $response['invoice'] ) {
			$db = &JFactory::getDBO();

			$request->invoice = new Invoice( $db );
			$request->invoice->loadInvoiceNumber( $response['invoice'] );
		}

		return $this->checkoutResponse( $request, $response, $InvoiceFactory );
	}

	function transmitRequest( $url, $path, $content=null, $port=443, $curlextra=null, $header=null )
	{
		if ( is_array( $header ) ) {
			if ( !isset( $header["Content-Type"] ) ) {
				$header["Content-Type"] = "text/xml";
			}
		} else {
			$header = array( "Content-Type" => "text/xml" );
		}

		return parent::transmitRequest( $url, $path, $content, $port, $curlextra, $header );
	}

	function checkoutResponse( $request, $response, $InvoiceFactory=null )
	{
		if ( !empty( $response['error'] ) ) {
			return $response;
		}

		if ( $response != false ) {
			$resp = array();
			if ( isset( $response['raw'] ) ) {
				if ( is_array( $response['raw'] ) ) {
					$resp = $response['raw'];
				} else {
					$resp['response'] = $response['raw'];
				}
				unset( $response['raw'] );
			}

			return $request->invoice->processorResponse( $InvoiceFactory, $response, $resp, true );
		} else {
			return false;
		}
	}

	function simpleCheckoutMod( $array )
	{
		if ( empty( $this->aec_checkout_mod ) ) {
			$this->aec_checkout_mod = array();
		}

		foreach ( $array as $k => $v ) {
			$this->aec_checkout_mod[$k] = $v;
		}
	}

	function modifyCheckout( &$int_var, &$InvoiceFactory )
	{
		if ( !empty( $this->aec_checkout_mod ) ) {
			foreach ( $this->aec_checkout_mod as $k => $v ) {
				$InvoiceFactory->checkout[$k] = $v;
			}
		}
	}

	function XMLtoArray( $xml )
	{
		if ( !( $xml->children() ) ) {
			return (string) $xml;
		}

		foreach ( $xml->children() as $child ) {
			$name = $child->getName();

			if ( count( $xml->$name ) == 1 ) {
				$element[$name] = $this->XMLtoArray( $child );
			} else {
				$element[][$name] = $this->XMLtoArray( $child );
			}
		}

		return $element;
	}

	function NVPtoArray( $nvpstr )
	{
		$intial = 0;
	 	$nvpArray = array();

		while ( strlen( $nvpstr ) ) {
			// postion of Key
			$keypos = strpos( $nvpstr, '=' );

			// position of value
			$valuepos = strpos( $nvpstr, '&' ) ? strpos( $nvpstr, '&' ) : strlen( $nvpstr );

			// getting the Key and Value values and storing in a Associative Array
			$keyval = substr( $nvpstr, $intial, $keypos );
			$valval = substr( $nvpstr, $keypos+1, $valuepos-$keypos-1 );

			// decoding the respose
			$nvpArray[urldecode( $keyval )] = urldecode( $valval );
			$nvpstr = substr( $nvpstr, $valuepos+1, strlen( $nvpstr ) );
		}

		return $nvpArray;
	}

	function arrayToNVP( $var, $uppercase=false )
	{
		$content = array();
		foreach ( $var as $name => $value ) {
			if ( $uppercase ) {
				$content[] .= strtoupper( $name ) . '=' . urlencode( stripslashes( $value ) );
			} else {
				$content[] .= $name . '=' . urlencode( stripslashes( $value ) );
			}
		}

		return implode( '&', $content );
	}

	function XMLsubstring_tag( $haystack, $tag )
	{
		return XMLprocessor::substring_between( $haystack, '<' . $tag . '>', '</' . $tag . '>' );
	}

	function substring_between( $haystack, $start, $end )
	{
		if ( strpos( $haystack, $start ) === false || strpos( $haystack, $end ) === false ) {
			return false;
		 } else {
			$start_position = strpos( $haystack, $start ) + strlen( $start );
			$end_position = strpos( $haystack, $end );
			return substr( $haystack, $start_position, $end_position - $start_position );
		}
	}

}

class SOAPprocessor extends XMLprocessor
{
	function transmitRequest( $url, $path, $command, $content, $headers=null, $options=null )
	{
		global $aecConfig;

		$this->soapclient = new SoapClient( $url, $options );
		
		if ( method_exist( $this->soapclient, '__soapCall' ) ) {
			$response['raw'] = $this->soapclient->__soapCall( $command, $content );
		} elseif ( method_exist( $this->soapclient, 'soapCall' ) ) {
			$response['raw'] = $this->soapclient->soapCall( $command, $content );
		} else {
			$response['raw'] = $this->soapclient->call( $command, $content );
		}

		if ( $response['raw']->error != 0 ) {
			$response['error'] = "Error calling native SOAP function: " . $response['raw']->error . ": " . $response['raw']->errorDescription;
		}

		return $response;
	}

	function followupRequest( $command, $content )
	{
		if ( empty( $this->soapclient ) ) {
			return null;
		}

		if ( !is_object( $this->soapclient ) ) {
			return null;
		}

		$response = array();

		if ( is_a( $this->soapclient, 'SoapClient' ) ) {
			$response['raw'] = $this->soapclient->__soapCall( $command, $content );

			if ( $return_val->error != 0 ) {
				$response['error'] = "Error calling SOAP function: " . $return_val->error;
			}

			return $response;
		} else {
			$response['raw'] = $this->soapclient->call( $command, $content );

			$err = $this->soapclient->getError();

			if ( $err != false ) {
				$response['error'] = "Error calling SOAP function: " . $err;
			}

			return $response;
		}
	}
}

class PROFILEprocessor extends XMLprocessor
{

	function ProfileAdd( $request, $profileid )
	{
		$ppParams = new stdClass();

		$ppParams->profileid			= $profileid;

		$ppParams->paymentprofileid		= '';
		$ppParams->paymentProfiles		= array();

		$request->metaUser->meta->setProcessorParams( $request->parent->id, $ppParams );

		return $ppParams;
	}

	function payProfileSelect( $var, $ppParams, $select=false, $btn=true )
	{
		$var['params'][] = array( 'p', '', JText::_('AEC_USERFORM_BILLING_DETAILS_NAME') );

		if ( !empty( $ppParams->paymentProfiles ) ) {
			// Single-Select Payment Option
			foreach ( $ppParams->paymentProfiles as $pid => $pobj ) {
				$info = array();

				$info_array = get_object_vars( $pobj->profilehash );

				foreach ( $info_array as $iak => $iav ) {
					if ( !empty( $iav ) ) {
						$info[] = $iav;
					}
				}

				if ( empty( $ppParams->paymentprofileid ) ) {
					$ppParams->paymentprofileid = $pid;
				}

				if ( $ppParams->paymentprofileid == $pid ) {
					$text = '<strong>' . implode( '<br />', $info ) . '</strong>';
				} else {
					$text = implode( '<br />', $info );
				}

				$var['params'][] = array( 'radio', 'payprofileselect', $pid, $ppParams->paymentprofileid, $text );
			}

			if ( count( $ppParams->paymentProfiles ) < 10 ) {
				$var['params'][] = array( 'radio', 'payprofileselect', "new", "", 'new billing details' );
			}

			if ( $btn ) {
				$var['params']['edit_payprofile'] = array( 'submit', '', '', ( $select ? JText::_('BUTTON_SELECT') : JText::_('BUTTON_EDIT') ) );
			}
		}

		return $var;
	}

	function payProfileAdd( $request, $profileid, $details, $ppParams )
	{
		$pointer = count( $ppParams->paymentProfiles );

		$data = new stdClass();
		$data->profileid	= $profileid;
		$data->profilehash	= $this->payProfileHash( $details );

		$ppParams->paymentProfiles[$pointer] = $data;

		$ppParams->paymentprofileid = $pointer;

		$request->metaUser->meta->setProcessorParams( $request->parent->id, $ppParams );

		return $ppParams;
	}

	function payProfileUpdate( $request, $profileid, $details, $ppParams )
	{
		$ppParams->paymentProfiles[$profileid]->profilehash = $this->payProfileHash( $details );

		$ppParams->paymentprofileid = $profileid;

		$request->metaUser->meta->setProcessorParams( $request->parent->id, $ppParams );

		return $ppParams;
	}

	function payProfileHash( $post )
	{
		$hash = new stdClass();
		$hash->name		= $post['billFirstName'] . ' ' . $post['billLastName'];
		$hash->address	= $post['billAddress'];
		$hash->zipcity	= $post['billZip'] . ' ' . $post['billCity'];

		if ( !empty( $post['account_no'] ) ) {
			$hash->cc		= 'XXXX' . substr( $post['account_no'], -4 );
		} else {
			$hash->cc		= 'XXXX' . substr( $post['cardNumber'], -4 );
		}

		return $hash;
	}

	function shipProfileSelect( $var, $ppParams, $select=false, $btn=true, $new=true )
	{
		$var['params'][] = array( 'p', '', JText::_('AEC_USERFORM_SHIPPING_DETAILS_DESC') );

		if ( !empty( $ppParams->shippingProfiles ) ) {
			// Single-Select Shipment Data
			foreach ( $ppParams->shippingProfiles as $pid => $pobj ) {
				$info = array();

				$info_array = get_object_vars( $pobj->profilehash );

				foreach ( $info_array as $iak => $iav ) {
					if ( !empty( $iav ) ) {
						$info[] = $iav;
					}
				}

				if ( empty( $ppParams->shippingprofileid ) ) {
					$ppParams->shippingprofileid = $pid;
				}

				if ( $ppParams->shippingprofileid == $pid ) {
					$text = '<strong>' . implode( '<br />', $info ) . '</strong>';
				} else {
					$text = implode( '<br />', $info );
				}

				$var['params'][] = array( 'radio', 'shipprofileselect', $pid, $ppParams->shippingprofileid, $text );
			}

			if ( ( count( $ppParams->shippingProfiles ) < 10 ) && $new ) {
				$var['params'][] = array( 'radio', 'shipprofileselect', "new", "", 'new shipping details' );
			}

			if ( $btn ) {
				$var['params']['edit_shipprofile'] = array( 'submit', '', '', ( $select ? JText::_('BUTTON_SELECT') : JText::_('BUTTON_EDIT') ) );
			}
		}

		return $var;
	}

	function shipProfileAdd( $request, $profileid, $post, $ppParams )
	{
		$pointer = count( $ppParams->paymentProfiles );

		$ppParams->shippingProfiles[$pointer] = new stdClass();
		$ppParams->shippingProfiles[$pointer]->profileid = $profileid;

		$ppParams->shippingProfiles[$pointer]->profilehash = $this->shipProfileHash( $post );

		$ppParams->shippingprofileid = $pointer;

		$request->metaUser->meta->setProcessorParams( $request->parent->id, $ppParams );

		return $ppParams;
	}

	function shipProfileUpdate( $request, $profileid, $post, $ppParams )
	{
		$ppParams->shippingProfiles[$profileid]->profilehash = $this->shipProfileHash( $post );

		$ppParams->shippingprofileid = $profileid;

		$request->metaUser->meta->setProcessorParams( $request->parent->id, $ppParams );

		return $ppParams;
	}

	function shipProfileHash( $post )
	{
		$hash = new stdClass();
		$hash->name		= $post['billFirstName'] . ' ' . $post['billLastName'];
		$hash->address	= $post['billAddress'];
		$hash->zipcity	= $post['billZip'] . ' ' . $post['billCity'];

		return $hash;
	}

}

class POSTprocessor extends processor
{
	function checkoutAction( $request, $InvoiceFactory=null, $xvar=null, $text=null )
	{
		if ( empty( $xvar ) ) {
			$var = $this->createGatewayLink( $request );

			if ( !empty( $this->settings['customparams'] ) ) {
				$var = $this->customParams( $this->settings['customparams'], $var, $request );
			}
		} else {
			$var = $xvar;
		}

		$onclick = "";
		if ( isset( $var['_aec_checkout_onclick'] ) ) {
			$onclick = 'onclick="' . $var['_aec_checkout_onclick'] . '"';
			unset( $var['_aec_checkout_onclick'] );
		}

		$return = '<form action="' . $var['post_url'] . '" method="post">' . "\n";
		unset( $var['post_url'] );

		foreach ( $var as $key => $value ) {
			$return .= '<input type="hidden" name="' . $key . '" value="' . $value . '" />' . "\n";
		}

		if ( empty( $text ) ) {
			$text = JText::_('BUTTON_CHECKOUT'); 
		}

		$return .= '<button type="submit" class="button aec-btn btn btn-primary" id="aec-checkout-btn" ' . $onclick . '><i class="icon-shopping-cart icon-white"></i>' . $text . '</button>' . "\n";
		$return .= '</form>' . "\n";

		return $return;
	}
}

class GETprocessor extends processor
{
	function checkoutAction( $request, $InvoiceFactory=null )
	{
		$var = $this->createGatewayLink( $request );

		if ( !empty( $this->settings['customparams'] ) ) {
			$var = $this->customParams( $this->settings['customparams'], $var, $request );
		}

		$onclick = "";
		if ( isset( $var['_aec_checkout_onclick'] ) ) {
			$onclick = ' onclick="' . $var['_aec_checkout_onclick'] . '"';
			unset( $var['_aec_checkout_onclick'] );
		}

		$return = '<form action="' . $var['post_url'] . '" method="get">' . "\n";
		unset( $var['post_url'] );

		foreach ( $var as $key => $value ) {
			$return .= '<input type="hidden" name="' . $key . '" value="' . $value . '" />' . "\n";
		}

		$return .= '<button type="submit" class="button aec-btn btn btn-primary" id="aec-checkout-btn" ' . $onclick . '><i class="icon-shopping-cart icon-white"></i>' . JText::_('BUTTON_CHECKOUT') . '</button>' . "\n";
		$return .= '</form>' . "\n";

		return $return;
	}
}

class URLprocessor extends processor
{
	function checkoutAction( $request, $InvoiceFactory=null )
	{
		$var = $this->createGatewayLink( $request );

		if ( isset( $var['_aec_html_head'] ) ) {
			$document=& JFactory::getDocument();

			if ( is_array( $var['_aec_html_head'] ) ) {
				foreach ( $var['_aec_html_head'] as $content ) {
					$document->addCustomTag( $content );
				}
			} else {
				$document->addCustomTag( $var['_aec_html_head'] );
			}

			unset( $var['_aec_html_head'] );
		}

		if ( !empty( $this->settings['customparams'] ) ) {
			$var = $this->customParams( $this->settings['customparams'], $var, $request );
		}

		if ( isset( $var['_aec_checkout_onclick'] ) ) {
			$onclick = ' onclick="' . $var['_aec_checkout_onclick'] . '"';
			unset( $var['_aec_checkout_onclick'] );
		} else {
			$onclick = '';
		}

		$return = '<a href="' . $var['post_url'];
		unset( $var['post_url'] );

		if ( substr( $return, -1, 1 ) !== '?' ) {
			$return .= '?';
		}

		$vars = array();
		if ( !empty( $var ) ) {
			foreach ( $var as $key => $value ) {
				$vars[] .= urlencode( $key ) . '=' . urlencode( $value );
			}

			$return .= implode( '&amp;', $vars );
		}

		$return .= '"' . $onclick . ' class="button aec-btn btn btn-primary" ><i class="icon-shopping-cart icon-white"></i>' . JText::_('BUTTON_CHECKOUT') . '</a>' . "\n";

		return $return;
	}
}

class aecHTML
{

	function aecHTML( $rows, $lists=null, $js=array() )
	{
		//$this->area = $area;
		//$this->fallback = $fallback;

		$this->rows		= $rows;
		$this->lists	= $lists;
		$this->js		= $js;
	}

	function createSettingsParticle( $name, $notooltip=false, $insertlabel=null, $insertctrl=null )
	{
		if ( !isset( $this->rows[$name] ) ) {
			return;
		}

		$row	= $this->rows[$name];
		$type	= $row[0];

		$return = '';

		if ( isset( $row[2] ) ) {
			if ( isset( $row[3] ) ) {
				$value = $row[3];
			} else {
				$value = '';
			}

			if ( !empty( $row[1] ) && !empty( $row[2] ) && !$notooltip ) {
				$return = '<div class="control-group">';
				$return .= '<label class="control-label bstooltip" for="' . $name . '" rel="tooltip" class="bstooltip" data-original-title="';

				if ( strnatcmp( phpversion(),'5.2.3' ) >= 0 ) {
					$return .= htmlentities( $row[2], ENT_QUOTES, "UTF-8", false );
					$return .= '">' . htmlentities( $row[1], ENT_QUOTES, "UTF-8", false );
				} else {
					$return .= htmlentities( $row[2], ENT_QUOTES, "UTF-8" );
					$return .= '">' . htmlentities( $row[1], ENT_QUOTES, "UTF-8" );
				}

				$return .= $insertlabel;

				$return .= '</label>';
			} else {
				$return = '<div class="control-group">';
				$return .= '<label class="control-label" for="' . $name . '"><div class="controls"></div></label>';
			}
		} else {
			if ( isset( $row[1] ) ) {
				$value = $row[1];
			} else {
				$value = '';
			}
		}

		switch ( $type ) {
			case 'inputA':
				$return .= '<div class="controls">';
				$return .= '<input id="' . $name . '" class="span2" name="' . $name . '" type="text" value="' . $value . '" />';
				$return .= $insertctrl;
				$return .= '</div></div>';
				break;
			case 'inputB':
				$return .= '<div class="controls">';
				$return .= '<input id="' . $name . '" class="span3" type="text" name="' . $name . '" value="' . $value . '" />';
				$return .= $insertctrl;
				$return .= '</div></div>';
				break;
			case 'inputC':
				$return .= '<div class="controls">';
				$return .= '<input id="' . $name . '" class="span6" type="text" name="' . $name . '" class="inputbox" value="' . $value . '" />';
				$return .= $insertctrl;
				$return .= '</div></div>';
				break;
			case 'inputD':
				$return .= '<div class="controls">';
				$return .= '<textarea id="' . $name . '" class="span6" rows="5" name="' . $name . '" >' . $value . '</textarea>';
				$return .= $insertctrl;
				$return .= '</div></div>';
				break;
			case 'inputE':
				$return .= '<div class="controls">';
				$return .= '<textarea id="' . $name . '" class="span6" cols="450" rows="1" name="' . $name . '" >' . $value . '</textarea>';
				$return .= $insertctrl;
				$return .= '</div></div>';
				break;
			case 'checkbox':
				$return .= '<div class="controls">';
				$return .= '<input type="hidden" name="' . $name . '" value="0"/>';
				$return .= '<input id="' . $name . '" type="checkbox" name="' . $name . '" ' . ( $value ? 'checked="checked" ' : '' ) . ' value="1"/>';
				$return .= $insertctrl;
				$return .= '</div></div>';
				break;
			case 'toggle':
				$return .= '<input type="hidden" name="' . $name . '" value="0"/>';
				$return .= '<div class="controls">';
				$return .= '<div class="toggleswitch">';
				$return .= '<label class="toggleswitch" onclick="">';
				$return .= '<input id="' . $name . '" type="checkbox" name="' . $name . '"' . ( $value ? ' checked="checked" ' : '' ) . ' value="1"/>';
				$return .= '<span class="toggleswitch-inner">';
				$return .= '<span class="toggleswitch-on">' . JText::_( 'yes' ) . '</span>';
				$return .= '<span class="toggleswitch-off">' . JText::_( 'no' ) . '</span>';
				$return .= '<span class="toggleswitch-handle"></span>';
				$return .= '</span>';
				$return .= '</label>';
				$return .= '</div>';
				$return .= $insertctrl;
				$return .= '</div></div>';
				break;
			case 'toggle_disabled':
				$return .= '<input type="hidden" name="' . $name . '" value="' . $value . '"/>';
				$return .= '<div class="controls">';
				$return .= '<div class="toggleswitch">';
				$return .= '<label class="toggleswitch" onclick="">';
				$return .= '<input id="' . $name . '" type="checkbox" name="' . $name . '"' . ( $value ? ' checked="checked" ' : '' ) . ' disabled="disabled" value="1"/>';
				$return .= '<span class="toggleswitch-inner">';
				$return .= '<span class="toggleswitch-on">' . JText::_( 'yes' ) . '</span>';
				$return .= '<span class="toggleswitch-off">' . JText::_( 'no' ) . '</span>';
				$return .= '<span class="toggleswitch-handle"></span>';
				$return .= '</span>';
				$return .= '</label>';
				$return .= '</div>';
				$return .= $insertctrl;
				$return .= '</div></div>';
				break;
			case 'editor':
				$return .= '<div class="controls">';

				$editor = &JFactory::getEditor();

				$return .= '<div>' . $editor->display( $name,  $value , '', '250', '50', '20' ) . '</div>';
				$return .= $insertctrl;
				$return .= '</div></div>';
				break;
			case 'textarea':
				$return .= '<textarea style="width:90%" cols="450" rows="10" name="' . $name . '" id="' . $name . '" >' . $value . '</textarea></div>';
				break;
			case 'list':
				$return .= '<div class="controls">';

				if ( strpos( $this->lists[$name], '[]"' ) ) {
					$return .= '<input type="hidden" name="' . $name . '" value="0" />';
					$return .= str_replace( '<select', '<select class="jqui-multiselect"', $this->lists[$name] );
				} else {
					$return .= $this->lists[$name];
				}
				
				$return .= $insertctrl;
				$return .= '</div></div>';
				break;
			case 'file':
				$return .= '<div class="controls">';
				$return .= '<input id="' . $name . '" name="' . $name . '" type="file" />';
				$return .= $insertctrl;
				$return .= '</div></div>';
				break;
			case 'accordion_start':
				if ( !isset( $this->accordions ) ) {
					$this->accordionitems = 1;
					$this->accordions = 1;
				} else {
					$this->accordions++;
				}

				$return = '<div id="accordion' . $this->accordions . '" class="accordion"' . '>';
				break;
			case 'accordion_itemstart':
				$return = '<div class="accordion-group">';
				$return .= '<div class="accordion-heading"><a href="#collapse' . ($this->accordions+$this->accordionitems) . '" data-parent="#accordion' . $this->accordions . '" data-toggle="collapse" class="accordion-toggle">' . $value . '</a></div>';
				$return .= '<div class="accordion-body collapse" id="collapse' . ($this->accordions+$this->accordionitems) . '"><div class="accordion-inner">';
				break;
			case 'accordion_itemend':
				$this->accordionitems++;

				$return = '</div></div></div>';
				break;
			case 'div_end':
				$return = '</div>';
				break;
			case '2div_end':
				$return = '</div></div>';
				break;
			case 'userinfobox':
				$return = '<div style="position:relative;float:left;width:' . $value . '%;"><div class="userinfobox">';
				break;
			case 'userinfobox_sub':
				$return = '<div class="aec_userinfobox_sub">' . ( !empty( $value ) ? '<h4>' . $value . '</h4>' : '' );
				break;
			case 'userinfobox_sub_stacked':
				$return = '<div class="aec_userinfobox_sub form-stacked">' . ( !empty( $value ) ? '<h4>' . $value . '</h4>' : '' );
				break;
			case 'fieldset':
				$return = '<div class="controls">' . "\n"
				. '<fieldset><legend>' . $row[1] . '</legend>' . "\n"
				. $row[2] . "\n"
				. '</fieldset>' . "\n"
				. '</div>'
				;
				break;
			case 'hidden':
				$return = '';
				if ( is_array( $value ) ) {
					foreach ( $value as $v ) {
						$return .= '<input id="' . $name . '" type="hidden" name="' . $name . '[]" value="' . $v . '" />';
					}
				} else {
					$return .= '<input id="' . $name . '" type="hidden" name="' . $name . '" value="' . $value . '" />';
				}
				break;
			default:
				$return = $value;
				break;
		}
		return $return;
	}

	function loadJS( $return=null )
	{
		if ( !empty( $this->js ) || !empty( $return ) ) {
			$js = "\n" . '<script type="text/javascript">';

			if ( !empty( $this->js ) ) {
				foreach ( $this->js as $scriptblock ) {
					$js .= "\n";
					$js .= $scriptblock;
				}
			}

			$js .= $return;
			$js .= "\n" . '</script>';

			$return = $js;
		}

		return $return;
	}

	function returnFull( $notooltip=false, $formsonly=false, $table=false )
	{
		$return = '';
		foreach ( $this->rows as $rowname => $rowcontent ) {
			if ( $formsonly ) {
				$return .= $this->createFormParticle( $rowname, $this->rows[$rowname], $this->lists, $table );
			} else {
				$return .= $this->createSettingsParticle( $rowname, $notooltip );
			}
		}

		return $return;
	}

	function printFull( $notooltip=false, $formsonly=false )
	{
		echo $this->returnFull( $notooltip, $formsonly );
	}

	function createFormParticle( $name, $row=null, $lists=array(), $table=0 )
	{
		if ( is_null( $row ) && !empty( $this->rows ) ) {
			if ( isset( $this->rows[$name] ) ) {
				$row = $this->rows[$name];
			} else {
				return '';
			}
		}

		if ( empty( $lists ) && !empty( $this->lists ) ) {
			$lists = $this->lists;
		}

		global $aecConfig;

		$return = '';
		if ( isset( $row[3] ) ) {
			$value = $row[3];
		} else {
			$value = '';
		}

		if ( !empty( $row[2] ) ) {
			$return .= $table ? '<tr class="aec_formrow"><td class="cleft">' : '<p>';
		} else {
			$return .= $table ? '<tr class="aec_formrow"><td class="cleft">' : '<p>';
		}

		$return .= '<label id="' . $name . 'msg" for="' . $name . '">';

		$sx = "";
		$sxx = false;
		if ( !empty( $row[1] ) ) {
			if ( strpos( $row[1], '*' ) ) {
				$sx = '<span class="aec_required">*</span>';
				$sxx = true;

				$row[1] = substr( $row[1], 0, -1 );
			}

			$return .= '<strong>' . $row[1] . ( ( strpos( $row[1], '?' ) == ( strlen( $row[1] ) ) - 1 ) ? '' : ':' ) . '</strong>';
		}

		$return .= '</label>';

		$return .= $table ? '</td><td class="cright">' : ' ';

		$noappend = false;
		switch ( $row[0] ) {
			case 'submit':
				$return .= '<input type="submit" class="button btn aec_formfield' . ( $aecConfig->cfg['checkoutform_jsvalidation'] ? ' validate-'.$name : '' ) . ( $sxx ? " required" : "" ) . '" id="' . $name . '" name="' . $name . '" value="' . $value . '" title="' . $row[2] . '" />' . "\n";
				break;
			case "inputA":
				$return .= '<input type="text" class="inputbox aec_formfield' . ( $aecConfig->cfg['checkoutform_jsvalidation'] ? ' validate-'.$name : '' ) . ( $sxx ? " required" : "" ) . '" id="' . $name . '" name="' . $name . '" size="4" maxlength="5" value="' . $value . '" title="' . $row[2] . '" />' . $sx;
				break;
			case "inputB":
				$return .= '<input type="text" class="inputbox aec_formfield' . ( $aecConfig->cfg['checkoutform_jsvalidation'] ? ' validate-'.$name : '' ) . ( $sxx ? " required" : "" ) . '" id="' . $name . '" name="' . $name . '" size="2" maxlength="10" value="' . $value . '" title="' . $row[2] . '" />' . $sx;
				break;
			case "inputC":
				$return .= '<input type="text" class="inputbox aec_formfield' . ( $aecConfig->cfg['checkoutform_jsvalidation'] ? ' validate-'.$name : '' ) . ( $sxx ? " required" : "" ) . '" id="' . $name . '" name="' . $name . '" size="30" value="' . $value . '" title="' . $row[2] . '"/>' . $sx;
				break;
			case "inputD":
				$return .= '<textarea align="left" cols="60" rows="5" id="' . $name . '" name="' . $name . '" title="' . $row[2] . '" class="aec_formfield' . ( $aecConfig->cfg['checkoutform_jsvalidation'] ? ' validate-'.$name : '' ) . '' . ( $aecConfig->cfg['checkoutform_jsvalidation'] ? ' form-validate' : '' ) . ( $sxx ? " required" : "" ) . '"/>' . $value . '</textarea>' . $sx;
				break;
			case 'radio':
				$return = '<tr><td class="cleft">';
				$return .= '<input type="radio" id="' . $name . '" name="' . $row[1] . '"' . ( ( $row[3] == $row[2] ) ? ' checked="checked"' : '' ) . ' value="' . $row[2] . '" title="' . /*$row[2] .*/ '" class="aec_formfield' . ( $aecConfig->cfg['checkoutform_jsvalidation'] ? ' validate-'.$name : '' ) . ( $sxx ? " required" : "" ) . '"/>';
				$return .= '</td><td class="cright">' . $row[4];
				break;
			case 'checkbox':
				$return = '<tr><td class="cleft">';
				$return .= '<input type="checkbox" id="' . $name . '" name="' . $row[1] . '"' . ( ( $row[3] == $row[2] ) ? ' checked="checked"' : '' ) . ' value="' . $row[2] . '" class="aec_formfield' . ( $aecConfig->cfg['checkoutform_jsvalidation'] ? ' validate-'.$name : '' ) . ( $sxx ? " required" : "" ) . '"/>' . $sx;
				$return .= '</td><td class="cright">' . $row[4];
				break;
			case "list":
				if ( $aecConfig->cfg['checkoutform_jsvalidation'] ) {
					$title = "";
					if ( isset( $row[2] ) ) {
						$title = $row[2];
					} elseif ( isset( $row[1] ) ) {
						$title = $row[1];
					}
					
					$search = 'class="';
					$replace = 'title="' . $title . '" class="' . ( $aecConfig->cfg['checkoutform_jsvalidation'] ? 'validate-'.$name.' ' : '' ) . ( $sxx ? 'required ' : '' );

					$return .= str_replace( $search, $replace, $lists[$value ? $value : $name] ) . $sx;
				} else {
					$return .= $lists[$value ? $value : $name] . $sx;
				}
				break;
			case 'tabberstart':
				$return = '<div class="checkout-tabs">';
				break;
			case 'tabregisterstart':
				$return = '<ul class="nav nav-tabs">';
				break;
			case 'tabregister':
				$return = '<li' . ($row[3] ? ' class="active"': '') . '><a href="#' . $row[1] . '" data-toggle="tab">' . $row[2] . '</a></li> ';
				break;
			case 'tabregisterend':
				$return = '</ul><div class="tab-content">';
				break;
			case 'tabstart':
				$act = false;
				if ( isset( $row[2] ) ) {
					$act = $row[2];
				}
				$return = '<div id="' . $row[1] . '" class="tab-pane' . ($act ? ' active': '') . '"><table>';
				break;
			case 'tabend':
				$return = '</table></div>';
				break;
			case 'tabberend':
				$return = '</div></div>';
				break;
			case 'divstart':
				if ( isset( $row[4] ) ) {
					$return = '<div id="' . $row[4] . '">';
				} else {
					$return = '<div class="' . $row[3] . '">';
				}
				break;
			case 'divend':
				$return = '</div>';
				break;
			case 'hidden':
				if ( !empty( $row[2] ) ) {
					$name = $row[2];
				}

				$return = '';
				if ( is_array( $value ) ) {
					foreach ( $value as $v ) {
						$return .= '<input type="hidden" id="' . $name . '" name="' . $name . '[]" value="' . $v . '" />';
					}
				} else {
					$return .= '<input type="hidden" id="' . $name . '" name="' . $name . '" value="' . $value . '" />';
				}
				break;
			default:
				if ( !empty( $row[0] ) ) {
					if ( empty( $row[1] ) ) {
						if ( isset( $row[4] ) ) {
							$return = '<tr><td class="cboth" colspan="2"><' . $row[0] . $row[4] . '>' . $row[2] . $value . '</' . $row[0] . '></td></tr>';
						} else {
							$return = '<tr><td class="cboth" colspan="2"><' . $row[0] . '>' . $row[2] . $value . '</' . $row[0] . '></td></tr>';
						}
					} else {
						if ( isset( $row[4] ) ) {
							$return = '<' . $row[0] . $row[4] . '>' . $row[2] . $value . '</' . $row[0] . '>';
						} else {
							if ( empty( $row[2] ) ) {
								$return = '<' . $row[0] . '>' . $row[1] . $value . '</' . $row[0] . '>';
							} else {
								$return = '<' . $row[0] . '>' . $row[2] . $value . '</' . $row[0] . '>';
							}
						}
					}
				} elseif ( empty( $row[0] ) && empty( $row[2] ) ) {
					$return .= '<' . $row[1] . $value . ' />';
				} else {
					$return .= $row[2] . $value;
				}
				break;
		}

		if ( strpos( $return, ($table ? '<tr class="aec_formrow"><td class="cleft">' : '<p>') ) !== false ) {
			$return .= $table ? '</td></tr>' : '</p>';
		}

		return $return;
	}

	/**
	* Utility function to provide ToolTips
	* @param string ToolTip text
	* @param string Box title
	* @returns HTML code for ToolTip
	*/
	function ToolTip( $tooltip, $title='', $width='', $image='help.png', $text='', $href='#', $link=1 )
	{
		if ( $width ) {
			$width = ', WIDTH, \''.$width .'\'';
		}
		if ( $title ) {
			$title = ', CAPTION, \''.$title .'\'';
		}
		if ( !$text ) {
			$image 	= JURI::root() . 'media/com_acctexp/images/admin/icons/'. $image;
			$text 	= '<img src="'. $image .'" border="0" alt=""/>';
		}
		$style = 'style="text-decoration: none; color: #586C79;"';
		if ( $href ) {
			$style = '';
		} else{
			$href = '#';
		}

		if ( strnatcmp( phpversion(),'5.2.3' ) >= 0 ) {
			$mousover = 'return overlib(\''. htmlentities( $tooltip, ENT_QUOTES, "UTF-8", false ) .'\''. $title .', BELOW, RIGHT'. $width .');';
		} else {
			$mousover = 'return overlib(\''. htmlentities( $tooltip, ENT_QUOTES, "UTF-8" ) .'\''. $title .', BELOW, RIGHT'. $width .');';
		}

		$tip = '';
		if ( $link ) {
			$tip .= '<a href="'. $href .'" onmouseover="'. $mousover .'" onmouseout="return nd();" '. $style .'>'. $text .'</a>';
		} else {
			$tip .= '<span onmouseover="'. $mousover .'" onmouseout="return nd();" '. $style .'>'. $text .'</span>';
		}

		return $tip . '&nbsp;';
	}

	function Icon( $icon='fire', $white=false )
	{
		return '<i class="bsicon-'. $icon .' bsicon'. ( $white ? '-white' : '' ) .'"></i>';
	}

	function Button( $icon='fire', $text='', $style='', $link='', $js='' )
	{
		$white = true;

		if ( empty( $style ) ) {
			$white = false;
		} else {
			$style = ' btn-'.$style;
		}

		if ( empty( $link ) ) {
			$link = '#';
		}

		if ( !empty( $js ) ) {
			$js = 'onclick="javascript: submitbutton(\''.$js.'\')"';
		}

		return '<a data-original-title="'.JText::_($text).'" rel="tooltip" href="'.$link.'"'.$js.' class="btn'.$style.'">'.aecHTML::Icon( $icon, $white ).'</a>';
	}

}

class aecSettings
{

	function aecSettings( $area, $subarea='' )
	{
		$this->area				= $area;
		$this->original_subarea	= $subarea;
		$this->subarea			= $subarea;
	}

	function fullSettingsArray( $params, $params_values, $lists = array(), $settings = array(), $showmissing=true ) {
		$this->params			= $params;
		$this->params_values	= $params_values;
		$this->lists			= $lists;
		$this->settings			= $settings;
		$this->prefix			= '';

		$lang = JFactory::getLanguage();

		foreach ( $this->params as $name => $content ) {
			if ( !isset( $content[0] ) ) {
				continue;
			}

			// $content[0] = type
			// $content[1] = value
			// $content[2] = disabled?
			// $content[3] = set name
			// $content[4] = set description

			$cname = $name;

			if ( !empty( $this->prefix ) ) {
				if ( strpos( $name, $this->prefix ) === 0 ) {
					$cname = str_replace( $this->prefix, '', $name );
				}
			}

			$name = $this->prefix . $cname;

			if ( !isset( $this->params_values[$name] ) ) {
				if ( isset( $content[3] ) ) {
					$this->params_values[$name] = $content[3];
				} elseif ( isset( $content[1] ) && !isset( $content[2] ) ) {
					$this->params_values[$name] = $content[1];
				} else {
					$this->params_values[$name] = '';
				}
			}

			if ( isset( $this->params_values[$name] ) ) {
				$value = $this->params_values[$name];
			}

			// Checking for remap functions
			$remap = 'remap_' . $content[0];

			if ( method_exists( $this, $remap ) ) {
				$type = $this->$remap( $name, $value );
			} else {
				$type = $content[0];
			}

			if ( strcmp( $type, 'DEL' ) === 0 ) {
				continue;
			}

			if ( !isset( $content[2] ) || ( ( $content[2] === false ) && ( $content[2] !== '' ) ) ) {
				// Create constant names
				$constant_generic	= strtoupper($this->area)
										. '_' . strtoupper( $this->original_subarea )
										. '_' . strtoupper( $cname );
				$constant			= strtoupper( $this->area )
										. '_' . strtoupper( $this->subarea )
										. '_' . strtoupper( $cname );
				$constantname		= $constant . '_NAME';
				$constantdesc		= $constant . '_DESC';
				
				// If the constantname does not exists, try a generic name
				if ( $lang->hasKey( $constantname ) ) {
					$info_name = JText::_( $constantname );
				} else {
					$info_name = JText::_( $constant_generic . '_NAME' );
				}

				// If the constantdesc does not exists, try a generic desc
				if ( $lang->hasKey( $constantdesc ) ) {
					$info_desc = JText::_( $constantdesc );
				} else {
					$info_desc = JText::_( $constant_generic . '_DESC' );
				}
			} else {
				$info_name = $content[1];
				$info_desc = $content[2];
			}

			if ( isset( $content[4] ) ) {
				$this->settings[$name] = array( $type, $info_name, $info_desc, $value, $content[4] );
			} else {
				$this->settings[$name] = array( $type, $info_name, $info_desc, $value );
			}
		}
	}

	function remap_add_prefix( $name, $value )
	{
		$this->prefix = $value;
		return 'DEL';
	}

	function remap_area_change( $name, $value )
	{
		$this->area = $value;
		$this->prefix = '';
		return 'DEL';
	}

	function remap_subarea_change( $name, $value )
	{
		$this->subarea = $value;
		$this->prefix = '';
		return 'DEL';
	}

	function remap_list_yesno( $name, $value )
	{
		$arr = array(
			JHTML::_('select.option', 0, JText::_( 'no' ) ),
			JHTML::_('select.option', 1, JText::_( 'yes' ) ),
		);

		$this->lists[$name] = JHTML::_('select.genericlist', $arr, $name, '', 'value', 'text', (int) $value );
		return 'list';
	}

	function remap_list_currency( $name, $value )
	{
		$currency_code_list = AECToolbox::aecCurrencyField( true, true, true );

		$this->lists[$name] = JHTML::_( 'select.genericlist', $currency_code_list, $name, 'size="10"', 'value', 'text', $value );

		return 'list';
	}

	function remap_list_country( $name, $value )
	{
		$country_code_list = AECToolbox::getCountryCodeList();

		$code_list = array();
		foreach ( $country_code_list as $country ) {
			$code_list[] = JHTML::_('select.option', $country, JText::_( 'COUNTRYCODE_' . $country ) );
		}

		$this->lists[$name] = JHTML::_( 'select.genericlist', $code_list, $name.'[]', 'size="10" multiple="multiple"', 'value', 'text', $value );

		return 'list';
	}

	function remap_list_country_full( $name, $value )
	{
		$country_code_list = AECToolbox::getISO3166_1a2_codes();

		$code_list = array();
		foreach ( $country_code_list as $country ) {
			$code_list[] = JHTML::_('select.option', $country, $country . " - " . JText::_( 'COUNTRYCODE_' . $country ) );
		}

		$this->lists[$name] = JHTML::_( 'select.genericlist', $code_list, $name.'[]', 'size="10" multiple="multiple"', 'value', 'text', $value );

		return 'list';
	}

	function remap_list_yesnoinherit( $name, $value )
	{
		$arr = array(
			JHTML::_('select.option', '0', JText::_('AEC_CMN_NO') ),
			JHTML::_('select.option', '1', JText::_('AEC_CMN_YES') ),
			JHTML::_('select.option', '1', JText::_('AEC_CMN_INHERIT') ),
		);

		$this->lists[$name] = JHTML::_( 'select.genericlist', $arr, $name, '', 'value', 'text', $value );
		return 'list';
	}

	function remap_list_recurring( $name, $value )
	{
		$recurring[] = JHTML::_('select.option', 0, JText::_('AEC_SELECT_RECURRING_NO') );
		$recurring[] = JHTML::_('select.option', 1, JText::_('AEC_SELECT_RECURRING_YES') );
		$recurring[] = JHTML::_('select.option', 2, JText::_('AEC_SELECT_RECURRING_BOTH') );

		$this->lists[$name] = JHTML::_( 'select.genericlist', $recurring, $name, 'size="3"', 'value', 'text', $value );

		return 'list';
	}

	function remap_list_date( $name, $value )
	{
		$this->lists[$name] = '<input id="datepicker-' . $name . '" name="' . $name . '" class="jqui-datepicker" type="text" value="' . $value . '">';

		return 'list';
	}
}

class ItemGroupHandler
{
	function getGroups()
	{
		$db = &JFactory::getDBO();

		$query = 'SELECT id'
				. ' FROM #__acctexp_itemgroups'
				;
		$db->setQuery( $query );
		return $db->loadResultArray();
	}

	function getTree()
	{
		$db = &JFactory::getDBO();

		// Filter out groups that have no relationship
		$query = 'SELECT id'
				. ' FROM #__acctexp_itemxgroup'
				. ' WHERE `type` = \'group\''
				;
		$db->setQuery( $query );
		$nitems = $db->loadResultArray();

		$query = 'SELECT id'
				. ' FROM #__acctexp_itemgroups'
				. ( !empty( $nitems ) ? ' WHERE `id` NOT IN (' . implode( ',', $nitems ) . ')' : '' )
				;
		$db->setQuery( $query );
		$items = $db->loadResultArray();

		$list = array();
		$tree = ItemGroupHandler::resolveTreeItem( 1 );

		ItemGroupHandler::indentList( $tree, $list );

		return $list;
	}

	function indentList( $tree, &$list, $indent=0 )
	{
		$list[] = array( $tree['id'], str_repeat( '&nbsp;', $indent ) . ( ( $indent > 0 ) ? '-' : '' ) . $tree['name'] . ' (#' . $tree['id'] . ')' );

		if ( isset( $tree['children'] ) ) {
			foreach ( $tree['children'] as $id => $co ) {
				ItemGroupHandler::indentList( $co, $list, $indent+1 );
			}
		}

		return $list;
	}

	function resolveTreeItem( $id )
	{
		$tree = array();
		$tree['id']		= $id;
		$tree['name']	= ItemGroupHandler::groupName( $id );

		$groups = ItemGroupHandler::getChildren( $id, 'group' );

		if ( !empty( $groups ) ) {
			// Has children, append them
			$tree['children'] = array();
			foreach ( $groups as $child_id ) {
				$tree['children'][] = ItemGroupHandler::resolveTreeItem( $child_id );
			}
		}

		return $tree;
	}

	function groupName( $groupid )
	{
		$db = &JFactory::getDBO();

		$query = 'SELECT name'
				. ' FROM #__acctexp_itemgroups'
				. ' WHERE `id` = \'' . $groupid . '\''
				;
		$db->setQuery( $query );
		return $db->loadResult();
	}

	function groupColor( $groupid )
	{
		$db = &JFactory::getDBO();

		$group = new ItemGroup( $db );
		$group->load( $groupid );

		return $group->params['color'];
	}

	function parentGroups( $item_id, $type='item' )
	{
		$db = &JFactory::getDBO();

		$query = 'SELECT group_id'
				. ' FROM #__acctexp_itemxgroup'
				. ' WHERE `type` = \'' . $type . '\''
				. ' AND `item_id` = \'' . $item_id . '\''
				;
		$db->setQuery( $query );
		return $db->loadResultArray();
	}

	function updateChildRelation( $item_id, $groups, $type='item' )
	{
		$currentParents	= ItemGroupHandler::parentGroups( $item_id, $type );

		// Filtering out which groups will stay
		$keepGroups		= array_intersect( $currentParents, $groups );

		// Which will be newly added
		$addGroups		= array_diff( $groups, $keepGroups );
		ItemGroupHandler::setChildren( $item_id, $addGroups, $type );

		// And which removed
		$delGroups		= array_diff( $currentParents, $keepGroups, $addGroups );
		ItemGroupHandler::removeChildren( $item_id, $delGroups, $type );
	}

	function setChild( $child_id, $group_id, $type='item' )
	{
		$db = &JFactory::getDBO();

		if ( $type == 'group' ) {
			// Don't let a group be assigned to itself
			if ( ( $group_id == $child_id ) ) {
				continue;
			}

			$children = ItemGroupHandler::getChildren( $child_id, 'group' );

			// Don't allow circular assignment
			if ( in_array( $group_id, $children ) ) {
				continue;
			}
		}

		$ig = new itemXgroup( $db );
		return $ig->createNew( $type, $child_id, $group_id );
	}

	function setChildren( $group_id, $children, $type='item' )
	{
		$db = &JFactory::getDBO();

		$success = false;
		foreach ( $children as $child_id ) {
			// Check bogus assignments
			if ( $type == 'group' ) {
				// Don't let a group be assigned to itself
				if ( ( $child_id == $group_id ) ) {
					continue;
				}

				$children = ItemGroupHandler::getChildren( $child_id, 'group' );

				// Don't allow circular assignment
				if ( in_array( $group_id, $children ) ) {
					continue;
				}
			}

			$ig = new itemXgroup( $db );

			if ( !$ig->createNew( $type, $child_id, $group_id ) ) {
				return false;
			} else {
				$success = true;
			}
		}

		return $success;
	}

	function getParents( $item_id, $type='item' )
	{
		if ( ( $item_id == 1 ) && ( $type == 'group' ) ) {
			return array();
		}
		
		$itemParents = ItemGroupHandler::parentGroups( $item_id, $type );

		$allParents = $itemParents;
		foreach ( $itemParents as $parentid ) {
			$parentParents = ItemGroupHandler::getParents( $parentid, 'group' );

			$allParents = array_merge( $allParents, $parentParents );
		}

		return $allParents;
	}

	function getChildren( $groups, $type )
	{
		$db = &JFactory::getDBO();

		$where = array();

		if ( is_array( $groups ) ) {
			$where[] = '`group_id` IN (' . implode( ',', $groups ) . ')';
		} else {
			$where[] = '`group_id` = ' . $groups . '';
		}

		if ( !empty( $type ) ) {
			$where[] = '`type` = \'' . $type . '\'';
		}

		$query = 'SELECT item_id'
				. ' FROM #__acctexp_itemxgroup'
				;

		if ( !empty( $where ) ) {
			$query .= ' WHERE ( ' . implode( ' AND ', $where ) . ' )';
		}

		$db->setQuery( $query );
		$result = $db->loadResultArray();

		if ( !empty( $result ) ) {
			foreach ( $result as $k => $v ) {
				if ( empty( $v ) ) {
					unset($result[$k]);
				}
			}

			// Order results
			$query = 'SELECT id'
					. ' FROM #__acctexp_' . ( ( $type == 'group' ) ? 'itemgroups' : 'plans' )
					. ' WHERE id IN (' . implode( ',', $result ) . ')'
					. ' ORDER BY `ordering`'
					;
			$db->setQuery( $query );

			return $db->loadResultArray();
		} else {
			return $result;
		}
	}

	function getGroupsPlans( $groups )
	{
		static $groupstore;

		$plans = array();
		foreach ( $groups as $group ) {
			if ( !isset( $groupstore[$group] ) ) {
				$groupstore[$group] = ItemGroupHandler::getTotalChildItems( array( $group ) );

				$groupstore[$group] = array_unique( $groupstore[$group] );
			}

			$plans = array_merge( $plans, $groupstore[$group] );
		}

		if ( !empty( $plans ) ) {
			return $plans;
		} else {
			return array();
		}
	}

	function checkParentRestrictions( $item, $type, $metaUser )
	{
		$db = &JFactory::getDBO();

		$parents = ItemGroupHandler::parentGroups( $item->id, $type );

		if ( !empty( $parents ) ) {
			foreach ( $parents as $parent ) {
				$g = new ItemGroup( $db );
				$g->load( $parent );

				// Only check for permission, visibility might be overridden
				if ( !$g->checkPermission( $metaUser ) ) {
					return false;
				}

				if ( !ItemGroupHandler::checkParentRestrictions( $g, 'group', $metaUser ) ) {
					return false;
				}
			}
		}

		return true;
	}

	function hasVisibleChildren( $group, $metaUser )
	{
		$db = &JFactory::getDBO();

		$children = ItemGroupHandler::getChildren( $group->id, 'item' );
		if ( !empty( $children ) ) {
			$i = 0;
			foreach( $children as $itemid ) {
				$plan = new SubscriptionPlan( $db );
				$plan->load( $itemid );

				if ( $plan->checkVisibility( $metaUser ) ) {
					return true;
				}
			}
		}

		$groups = ItemGroupHandler::getChildren( $group->id, 'group' );
		if ( !empty( $groups ) ) {
			foreach ( $groups as $groupid ) {
				$g = new ItemGroup( $db );
				$g->load( $groupid );

				if ( !$g->checkVisibility( $metaUser ) ) {
					continue;
				}

				if ( ItemGroupHandler::hasVisibleChildren( $g, $metaUser ) ) {
					return true;
				}
			}
		}

		return false;
	}

	function getTotalChildItems( $gids, $list=array() )
	{
		$groups = ItemGroupHandler::getChildren( $gids, 'group' );

		foreach ( $groups as $groupid ) {
			$list = ItemGroupHandler::getTotalChildItems( $groupid, $list );
		}

		$items = ItemGroupHandler::getChildren( $gids, 'item' );

		return array_merge( $list, $items );
	}

	function getTotalAllowedChildItems( $gids, $metaUser, $list=array() )
	{
		$db = &JFactory::getDBO();

		$groups = ItemGroupHandler::getChildren( $gids, 'group' );

		if ( !empty( $groups ) ) {
			foreach ( $groups as $groupid ) {
				$group = new ItemGroup( $db );
				$group->load( $groupid );

				if ( !$group->checkVisibility( $metaUser ) ) {
					continue;
				}

				if ( $group->params['reveal_child_items'] && empty( $group->params['symlink'] ) ) {
					$list = ItemGroupHandler::getTotalAllowedChildItems( $groupid, $metaUser, $list );
				} else {
						if ( ItemGroupHandler::hasVisibleChildren( $group, $metaUser ) ) {
							$list[] = ItemGroupHandler::getGroupListItem( $group );
						}
					}
			}
		}

		$items = ItemGroupHandler::getChildren( $gids, 'item' );

		if ( !empty( $items ) ) {
			foreach( $items as $itemid ) {
				$plan = new SubscriptionPlan( $db );
				$plan->load( $itemid );

				if ( !$plan->checkVisibility( $metaUser ) ) {
					continue;
				}

				$list[] = ItemGroupHandler::getItemListItem( $plan );
			}
		}

		return $list;
	}

	function getGroupListItem( $group )
	{
		$details = array(	'type'		=> 'group',
							'id'		=> $group->id,
							'name'		=> $group->getProperty( 'name' ),
							'desc'		=> $group->getProperty( 'desc' ),
							'meta'	=> array()
							);

		if ( !empty( $group->params['meta'] ) ) {
			$details['meta'] = parameterHandler::decode( $group->params['meta'] );
		}

		return $details;
	}

	function getItemListItem( $plan )
	{
		$details = array(	'type'		=> 'item',
							'id'		=> $plan->id,
							'plan'		=> $plan,
							'name'		=> $plan->getProperty( 'name' ),
							'desc'		=> $plan->getProperty( 'desc' ),
							'ordering'	=> $plan->ordering,
							'lifetime'	=> $plan->params['lifetime'],
							'meta'	=> array()
							);

		if ( !empty( $plan->params['meta'] ) ) {
			$details['meta'] = parameterHandler::decode( $plan->params['meta'] );
		}

		return $details;
	}

	function removeChildren( $items, $groups, $type='item' )
	{
		$db = &JFactory::getDBO();

		$query = 'DELETE'
				. ' FROM #__acctexp_itemxgroup'
				. ' WHERE `type` = \'' . $type . '\''
				;

		if ( is_array( $items ) ) {
			$query .= ' AND `item_id` IN (' . implode( ',', $items ) . ')';
		} else {
			$query .= ' AND `item_id` = \'' . $items . '\'';
		}

		if ( !empty( $groups ) ) {
			$query .= ' AND `group_id` IN (' . implode( ',', $groups ) . ')';
		}

		$db->setQuery( $query );
		return $db->query();
	}

}

class ItemGroup extends serialParamDBTable
{
	/** @var int Primary key */
	var $id 				= null;
	/** @var int */
	var $active				= null;
	/** @var int */
	var $visible			= null;
	/** @var int */
	var $ordering			= null;
	/** @var string */
	var $name				= null;
	/** @var string */
	var $desc				= null;
	/** @var text */
	var $params 			= null;
	/** @var text */
	var $custom_params		= null;
	/** @var text */
	var $restrictions		= null;

	function ItemGroup( &$db )
	{
		parent::__construct( '#__acctexp_itemgroups', 'id', $db );
	}

	function getProperty( $name )
	{
		if ( isset( $this->$name ) ) {
			return stripslashes( $this->$name );
		} else {
			return null;
		}
	}

	function declareParamFields()
	{
		return array( 'params', 'custom_params', 'restrictions' );
	}

	function checkVisibility( $metaUser )
	{
		if ( !$this->visible ) {
			return false;
		} else {
			return $this->checkPermission( $metaUser );
		}
	}

	function checkPermission( $metaUser )
	{
		if ( !$this->active ) {
			return false;
		}

		$restrictions = $this->getRestrictionsArray();

		return aecRestrictionHelper::checkRestriction( $restrictions, $metaUser );
	}

	function getRestrictionsArray()
	{
		return aecRestrictionHelper::getRestrictionsArray( $this->restrictions );
	}

	function getMicroIntegrationsSeparate( $strip_inherited=false )
	{
		$db = &JFactory::getDBO();

		if ( empty( $this->params['micro_integrations'] ) ) {
			$milist = array();
		} else {
			$milist = $this->params['micro_integrations'];
		}

		// Find parent ItemGroups to attach their MIs
		$parents = ItemGroupHandler::getParents( $this->id );

		$gmilist = array();
		if ( !empty( $parents ) ) {
			foreach ( $parents as $parent ) {
				$g = new ItemGroup( $db );
				$g->load( $parent );

				if ( !empty( $g->params['micro_integrations'] ) ) {
					$gmilist = array_merge( $gmilist, $g->params['micro_integrations'] );
				}
			}
		}

		if ( empty( $milist ) && empty( $gmilist ) ) {
			return array( 'group' => array(), 'inherited' => array() );
		}

		$milist = microIntegrationHandler::getActiveListbyList( $milist );
		$gmilist = microIntegrationHandler::getActiveListbyList( $gmilist );

		if ( empty( $milist ) && empty( $gmilist ) ) {
			return array( 'group' => array(), 'inherited' => array() );
		}

		// Remove entries from the group MIs that are already inherited
		if ( !empty( $gmilist ) && !empty( $milist ) && $strip_inherited ) {
			$theintersect = array_intersect( $gmilist, $milist );

			if ( !empty( $theintersect ) ) {
				foreach ( $theintersect as $value ) {
					// STAY IN THE CAR
					unset( $milist[array_search( $value, $milist )] );
				}
			}
		}

		return array( 'group' => $milist, 'inherited' => $gmilist );
	}

	function savePOSTsettings( $post )
	{
		$db = &JFactory::getDBO();

		// Fake knowing the planid if is zero.
		if ( !empty( $post['id'] ) ) {
			$groupid = $post['id'];
		} else {
			$groupid = $this->getMax() + 1;
		}

		if ( isset( $post['id'] ) ) {
			unset( $post['id'] );
		}

		if ( isset( $post['inherited_micro_integrations'] ) ) {
			unset( $post['inherited_micro_integrations'] );
		}

		if ( !empty( $post['add_group'] ) ) {
			ItemGroupHandler::setChildren( $post['add_group'], array( $groupid ), 'group' );
		}

		if ( $this->id == 1 ) {
			$post['active']				= 1;
			$post['visible']			= 1;
			$post['name']				= JText::_('AEC_INST_ROOT_GROUP_NAME');
			$post['desc']				= JText::_('AEC_INST_ROOT_GROUP_DESC');
			$post['reveal_child_items']	= 1;
		}

		// Filter out fixed variables
		$fixed = array( 'active', 'visible', 'name', 'desc' );

		foreach ( $fixed as $varname ) {
			$this->$varname = $post[$varname];
			unset( $post[$varname] );
		}

		// Filter out params
		$fixed = array(	'color', 'icon', 'reveal_child_items', 'symlink',
						'symlink_userid', 'notauth_redirect', 'micro_integrations', 'meta' );

		$params = array();
		foreach ( $fixed as $varname ) {
			if ( !isset( $post[$varname] ) ) {
				continue;
			}

			$params[$varname] = $post[$varname];

			unset( $post[$varname] );
		}

		$this->saveParams( $params );

		// Filter out restrictions
		$fixed = aecRestrictionHelper::paramList();

		$restrictions = array();
		foreach ( $fixed as $varname ) {
			if ( !isset( $post[$varname] ) ) {
				continue;
			}

			$restrictions[$varname] = $post[$varname];

			unset( $post[$varname] );
		}

		$this->restrictions = $restrictions;

		// There might be deletions set for groups
		foreach ( $post as $varname => $content ) {
			if ( ( strpos( $varname, 'group_delete_' ) !== false ) && $content ) {
				$parentid = (int) str_replace( 'group_delete_', '', $varname );

				ItemGroupHandler::removeChildren( $groupid, array( $parentid ), 'group' );

				unset( $post[$varname] );
			}
		}

		// The rest of the vars are custom params
		$custom_params = array();
		foreach ( $post as $varname => $content ) {
			if ( substr( $varname, 0, 4 ) != 'mce_' ) {
				$custom_params[$varname] = $content;
			}
			unset( $post[$varname] );
		}

		$this->custom_params = $custom_params;
	}

	function saveParams( $params )
	{
		$this->params = $params;
	}

	function delete()
	{
		$db = &JFactory::getDBO();

		if ( $this->id == 1 ) {
			return false;
		}

		// Delete possible item connections
		$query = 'DELETE FROM #__acctexp_itemxgroup'
				. ' WHERE `group_id` = \'' . $this->id . '\''
				. ' AND `type` = \'item\''
				;
		$db->setQuery( $query );
		if ( !$db->query() ) {
			echo "<script> alert('".$db->getErrorMsg()."'); window.history.go(-1); </script>\n";
			exit();
		}

		// Delete possible group connections
		$query = 'DELETE FROM #__acctexp_itemxgroup'
				. ' WHERE `group_id` = \'' . $this->id . '\''
				. ' AND `type` = \'group\''
				;
		$db->setQuery( $query );
		if ( !$db->query() ) {
			echo "<script> alert('".$db->getErrorMsg()."'); window.history.go(-1); </script>\n";
			exit();
		}

		return parent::delete();
	}

	function copy()
	{
		$pid = $this->id;

		$this->id = 0;
		$this->storeload();

		$parents = ItemGroupHandler::parentGroups( $pid, 'group' );

		foreach ( $parents as $parentid ) {
			ItemGroupHandler::setChild( $this->id, $parentid, 'group' );
		}
	}
}

class itemXgroup extends JTable
{
	/** @var int Primary key */
	var $id					= null;
	/** @var string */
	var $type				= null;
	/** @var int */
	var $item_id			= null;
	/** @var int */
	var $group_id			= null;

	function itemXgroup( &$db )
	{
		parent::__construct( '#__acctexp_itemxgroup', 'id', $db );
	}

	function createNew( $type, $item_id, $group_id )
	{
		$this->id		= 0;
		$this->type		= $type;
		$this->item_id	= $item_id;
		$this->group_id	= $group_id;

		$this->check();
		$this->store();

		return true;
	}

}

class SubscriptionPlanHandler
{
	function getPlanList( $limitstart=false, $limit=false, $use_order=false )
	{
		$db = &JFactory::getDBO();

		$query = 'SELECT id'
			 	. ' FROM #__acctexp_plans'
			 	. ' ORDER BY ' . ( $use_order ? '`ordering`' : '`id`' )
			 	;

		if ( !empty( $limitstart ) && !empty( $limit ) ) {
			$query .= 'LIMIT ' . $limitstart . ',' . $limit;
		}

		$db->setQuery( $query );

		$rows = $db->loadResultArray();
		if ( $db->getErrorNum() ) {
			echo $db->stderr();
			return false;
		} else {
			return $rows;
		}
	}

	function getActivePlanList()
	{
		$db = &JFactory::getDBO();

		// get entry Plan selection
		$available_plans	= array();
		$available_plans[]	= JHTML::_('select.option', '0', JText::_('PAYPLAN_NOPLAN') );

		$query = 'SELECT `id` AS value, `name` AS text'
				. ' FROM #__acctexp_plans'
				. ' WHERE `active` = \'1\''
				;
		$db->setQuery( $query );
		$dbaplans = $db->loadObjectList();

	 	if ( is_array( $dbaplans ) ) {
	 		$available_plans = array_merge( $available_plans, $dbaplans );
	 	}

		return $available_plans;
	}

	function getFullPlanList( $limitstart=false, $limit=false, $subselect=array() )
	{
		$db = &JFactory::getDBO();

		$query = 'SELECT *'
				. ' FROM #__acctexp_plans'
				. ( empty( $subselect ) ? '' : ' WHERE id IN (' . implode( ',', $subselect ) . ')' )
				. ' GROUP BY `id`'
				. ' ORDER BY `ordering`'
			 	;

		if ( ( $limitstart !== false ) && ( $limit !== false ) ) {
			$query .= ' LIMIT ' . $limitstart . ',' . $limit;
		}

		$db->setQuery( $query );

		$rows = $db->loadObjectList();
		if ( $db->getErrorNum() ) {
			echo $db->stderr();
			return false;
		} else {
			return $rows;
		}
	}

	function getPlanUserlist( $planid )
	{
		$db = &JFactory::getDBO();

		$query = 'SELECT `userid`'
				. ' FROM #__acctexp_subscr'
				. ' WHERE `plan` = \'' . $db->getEscaped( $planid ) . '\' AND ( `status` = \'Active\' OR `status` = \'Trial\' ) '
				;
		$db->setQuery( $query );

		return $db->loadResultArray();
	}

	function PlanStatus( $planid )
	{
		$db = &JFactory::getDBO();

		$plan = new SubscriptionPlan( $db );
		$plan->load( $planid );

		return $plan->active && $plan->checkInventory();
	}

	function planName( $planid )
	{
		$db = &JFactory::getDBO();

		$query = 'SELECT name'
				. ' FROM #__acctexp_plans'
				. ' WHERE `id` = \'' . $planid . '\''
				;
		$db->setQuery( $query );
		return $db->loadResult();
	}

	function listPlans()
	{
		$db = &JFactory::getDBO();

		$query = 'SELECT id'
				. ' FROM #__acctexp_plans'
				;
		$db->setQuery( $query );

		return $db->loadResultArray();
	}
}

class SubscriptionPlan extends serialParamDBTable
{
	/** @var int Primary key */
	var $id 				= null;
	/** @var int */
	var $active				= null;
	/** @var int */
	var $visible			= null;
	/** @var int */
	var $ordering			= null;
	/** @var string */
	var $name				= null;
	/** @var string */
	var $desc				= null;
	/** @var string */
	var $email_desc			= null;
	/** @var text */
	var $params 			= null;
	/** @var text */
	var $custom_params		= null;
	/** @var text */
	var $restrictions		= null;
	/** @var text */
	var $micro_integrations	= null;

	function SubscriptionPlan( &$db )
	{
		parent::__construct( '#__acctexp_plans', 'id', $db );
	}

	function declareParamFields()
	{
		return array( 'params', 'custom_params', 'restrictions', 'micro_integrations' );
	}

	function getProperty( $name )
	{
		if ( isset( $this->$name ) ) {
			return stripslashes( $this->$name );
		} else {
			return null;
		}
	}

	function checkVisibility( $metaUser )
	{
		if ( !$this->visible ) {
			return false;
		} else {
			return $this->checkPermission( $metaUser );
		}
	}

	function checkPermission( $metaUser )
	{
		if ( !$this->active ) {
			return false;
		}

		return $this->checkAuthorized( $metaUser ) === true;
	}

	function checkAuthorized( $metaUser )
	{
		$authorized = true;

		if ( !empty( $this->params['fixed_redirect'] ) ) {
			return $this->params['fixed_redirect'];
		} else {
			$authorized = $this->checkInventory();
				
			if ( $authorized ) {
				$restrictions = $this->getRestrictionsArray();

				if ( aecRestrictionHelper::checkRestriction( $restrictions, $metaUser ) !== false ) {
					if ( !ItemGroupHandler::checkParentRestrictions( $this, 'item', $metaUser ) ) {
						$authorized = false;
					}
				} else {
					$authorized = false;
				}
			}

			if ( !$authorized && !empty( $this->params['notauth_redirect'] ) ) {
				return $this->params['notauth_redirect'];
			}
		}

		return $authorized;
	}

	function checkInventory()
	{
		if ( !empty( $this->restrictions['inventory_amount_enabled'] ) ) {
			if ( $this->restrictions['inventory_amount_used'] >= $this->restrictions['inventory_amount'] ) {
				return false;
			}
		}

		return true;
	}

	function incrementInventory()
	{
		if ( !empty( $this->restrictions['inventory_amount_enabled'] ) ) {
			$this->restrictions['inventory_amount_used']++;
		}

		return $this->storeload();
	}

	function applyPlan( $user, $processor = 'none', $silent = 0, $multiplicator = 1, $invoice = null, $tempparams = null )
	{
		$db = &JFactory::getDBO();

		global $aecConfig;

		$app = JFactory::getApplication();

		$forcelifetime = false;

		if ( is_string( $multiplicator ) ) {
			if ( strcmp( $multiplicator, 'lifetime' ) === 0 ) {
				$forcelifetime = true;
			}
		} elseif ( is_int( $multiplicator ) && ( $multiplicator < 1 ) ) {
			$multiplicator = 1;
		}

		if ( empty( $user ) ) {
			return false;
		}

		if ( is_object( $user ) ) {
			if ( is_a( $user, 'metaUser' ) ) {
				$metaUser = $user;
			} elseif( is_a( $user, 'Subscription' ) ) {
				$metaUser = new metaUser( $user->userid );

				$metaUser->focusSubscription = $user;
			}
		} else {
			$metaUser = new metaUser( $user );
		}

		if ( !isset( $this->params['make_primary'] ) ) {
			$this->params['make_primary'] = 1;
		}

		$fstatus = $metaUser->establishFocus( $this, $processor, false );

		// TODO: Figure out why $status returns 'existing' - even on a completely fresh subscr

		if ( $fstatus != 'existing' ) {
			$is_pending	= ( strcmp( $metaUser->focusSubscription->status, 'Pending' ) === 0 );
			$is_trial	= ( strcmp( $metaUser->focusSubscription->status, 'Trial' ) === 0 );
		} else {
			$is_pending	= false;
			$is_trial	= ( strcmp( $metaUser->focusSubscription->status, 'Trial' ) === 0 );
		}

		$comparison		= $this->doPlanComparison( $metaUser->focusSubscription );
		$renew 			= $metaUser->is_renewing();

		$lifetime		= $metaUser->focusSubscription->lifetime;

		$amount = "0.00";

		if ( ( $comparison['total_comparison'] === false ) || $is_pending ) {
			// If user is using global trial period he still can use the trial period of a plan
			if ( ( $this->params['trial_period'] > 0 ) && !$is_trial ) {
				$trial		= true;
				$value		= $this->params['trial_period'];
				$perunit	= $this->params['trial_periodunit'];
				$this->params['lifetime']	= 0; // We are entering the trial period. The lifetime will come at the renew.

				$amount		= $this->params['trial_amount'];
			} else {
				$trial		= false;
				$value		= $this->params['full_period'];
				$perunit	= $this->params['full_periodunit'];

				$amount		= $this->params['full_amount'];
			}
		} elseif ( !$is_pending ) {
			$trial		= false;
			$value		= $this->params['full_period'];
			$perunit	= $this->params['full_periodunit'];
			$amount		= $this->params['full_amount'];
		} else {
			return false;
		}

		if ( $this->params['lifetime'] || $forcelifetime ) {
			$metaUser->focusSubscription->expiration = '9999-12-31 00:00:00';
			$metaUser->focusSubscription->lifetime = 1;
		} else {
			$metaUser->focusSubscription->lifetime = 0;

			$value *= $multiplicator;

			if ( ( $comparison['comparison'] == 2 ) && !$lifetime ) {
				$metaUser->focusSubscription->setExpiration( $perunit, $value, 1 );
			} else {
				$metaUser->focusSubscription->setExpiration( $perunit, $value, 0 );
			}
		}

		if ( $is_pending ) {
			// Is new = set signup date
			$metaUser->focusSubscription->signup_date = date( 'Y-m-d H:i:s', ( (int) gmdate('U') ) );
			if ( ( $this->params['trial_period'] ) > 0 && !$is_trial ) {
				$status = 'Trial';
			} else {
				if ( $this->params['full_period'] || $this->params['lifetime'] ) {
					if ( !isset( $this->params['make_active'] ) ) {
						$status = 'Active';
					} else {
						$status = ( $this->params['make_active'] ? 'Active' : 'Pending' );
					}
				} else {
					// This should not happen
					$status = 'Pending';
				}
			}
		} else {
			// Renew subscription - Do NOT set signup_date
			if ( !isset( $this->params['make_active'] ) ) {
				$status = $trial ? 'Trial' : 'Active';
			} else {
				$status = ( $this->params['make_active'] ? ( $trial ? 'Trial' : 'Active' ) : 'Pending' );
			}
		}

		$metaUser->focusSubscription->status = $status;
		$metaUser->focusSubscription->plan = $this->id;

		$metaUser->temporaryRFIX();

		$metaUser->focusSubscription->lastpay_date = date( 'Y-m-d H:i:s', ( (int) gmdate('U') ) );
		$metaUser->focusSubscription->type = $processor;

		$recurring_choice = null;
		if ( is_object( $invoice ) ) {
			if ( !empty( $invoice->params ) ) {
				$tempparam = array();
				if ( !empty( $invoice->params['creator_ip'] ) ) {
					$tempparam['creator_ip'] = $invoice->params['creator_ip'];
				}

				if ( !empty( $tempparam ) ) {
					$metaUser->focusSubscription->addParams( $tempparam, 'params', false );
					$metaUser->focusSubscription->storeload();
				}
			}
		}

		$pp = new PaymentProcessor();
		if ( $pp->loadName( strtolower( $processor ) ) ) {
			$pp->init();
			$pp->getInfo();

			$recurring_choice = null;
			if ( is_object( $invoice ) ) {
				if ( !empty( $invoice->params ) ) {
					if ( isset( $invoice->params["userselect_recurring"] ) ) {
						$recurring_choice = $invoice->params["userselect_recurring"];
					}
				}
			}

			// Check whether we have a custome choice set
			if ( !is_null( $recurring_choice ) ) {
				$metaUser->focusSubscription->recurring = $pp->is_recurring( $recurring_choice );
			} else {
				$metaUser->focusSubscription->recurring = $pp->is_recurring();
			}
		} else {
			$metaUser->focusSubscription->recurring = 0;
		}

		$metaUser->focusSubscription->storeload();

		if ( empty( $invoice->id ) ) {
			$invoice = new stdClass();
			$invoice->amount = $amount;
		}

		$exchange = $add = null;

		$result = $this->triggerMIs( 'action', $metaUser, $exchange, $invoice, $add, $silent );

		if ( $result === false ) {
			return false;
		} elseif ( $result === true ) {
			// MIs might have changed the subscription. Reload it.
			$metaUser->focusSubscription->reload();
		}

		if ( $this->params['gid_enabled'] ) {
			$metaUser->instantGIDchange( $this->params['gid'] );
		}

		$metaUser->focusSubscription->storeload();

		if ( !( $silent || $aecConfig->cfg['noemails'] ) || $aecConfig->cfg['noemails_adminoverride'] ) {
			$adminonly = ( $this->id == $aecConfig->cfg['entry_plan'] ) || ( $aecConfig->cfg['noemails'] && $aecConfig->cfg['noemails_adminoverride'] );

			$metaUser->focusSubscription->sendEmailRegistered( $renew, $adminonly, $invoice );
		}

		$metaUser->meta->addPlanID( $this->id );

		$result = $this->triggerMIs( 'afteraction', $metaUser, $exchange, $invoice, $add, $silent );

		if ( $result === false ) {
			return false;
		}

		$this->incrementInventory();

		return $renew;
	}

	function getTermsForUser( $recurring, $metaUser )
	{
		if ( $metaUser->hasSubscription ) {
			return $this->getTerms( $recurring, $metaUser->objSubscription, $metaUser );
		} else {
			return $this->getTerms( $recurring, false, $metaUser );
		}
	}

	function getTerms( $recurring=false, $user_subscription=false, $metaUser=false )
	{
		$plans_comparison		= false;
		$plans_comparison_total	= false;

		if ( is_object( $metaUser ) ) {
			if ( is_object( $metaUser->objSubscription ) ) {
				$comparison				= $this->doPlanComparison( $metaUser->focusSubscription );
				$plans_comparison		= $comparison['comparison'];
				$plans_comparison_total	= $comparison['total_comparison'];
			}
		} elseif ( is_object( $user_subscription ) ) {
			$comparison				= $this->doPlanComparison( $user_subscription );
			$plans_comparison		= $comparison['comparison'];
			$plans_comparison_total	= $comparison['total_comparison'];
		}

		if ( !isset( $this->params['full_free'] ) ) {
			$this->params['full_free'] = false;
		}

		$allow_trial = ( $plans_comparison === false ) && ( $plans_comparison_total === false );

		$terms = new mammonTerms();
		$terms->readParams( $this->params, $allow_trial );

		if ( !$allow_trial && ( count( $terms->terms ) > 1 ) ) {
			$terms->incrementPointer();
		}

		return $terms;
	}

	function doPlanComparison( $user_subscription )
	{
		$db = &JFactory::getDBO();

		$return['total_comparison']	= false;
		$return['comparison']		= false;

		if ( !empty( $user_subscription->plan ) ) {
			if ( !empty( $user_subscription->used_plans ) ) {
				$plans_comparison	= false;

				if ( is_array( $user_subscription->used_plans ) ) {
					foreach ( $user_subscription->used_plans as $planid => $pusage ) {
						if ( $planid ) {
							if ( empty( $planid ) ){
								continue;
							}

							$used_subscription = new SubscriptionPlan( $db );
							$used_subscription->load( $planid );

							if ( $this->id === $used_subscription->id ) {
								$used_comparison = 2;
							} elseif ( empty( $this->params['similarplans'] ) && empty( $this->params['equalplans'] ) ) {
								$used_comparison = false;
							} else {
								$used_comparison = $this->compareToPlan( $used_subscription );
							}

							if ( $used_comparison > $plans_comparison ) {
								$plans_comparison = $used_comparison;
							}
							unset( $used_subscription );
						}
					}
					$return['total_comparison'] = $plans_comparison;
				}
			}

			$last_subscription = new SubscriptionPlan( $db );
			$last_subscription->load( $user_subscription->plan );

			if ( $this->id === $last_subscription->id ) {
				$return['comparison'] = 2;
			} else {
				$return['comparison'] = $this->compareToPlan( $last_subscription );
			}
		}

		$return['full_comparison'] = ( ( $return['comparison'] === false ) && ( $return['total_comparison'] === false ) );

		return $return;
	}

	function compareToPlan( $plan )
	{
		if ( !isset( $this->params['similarplans'] ) ) {
			$this->params['similarplans'] = array();
		}

		if ( !isset( $plan->params['similarplans'] ) ) {
			$plan->params['similarplans'] = array();
		}

		if ( !isset( $this->params['equalplans'] ) ) {
			$this->params['equalplans'] = array();
		}

		if ( !isset( $plan->params['equalplans'] ) ) {
			$plan->params['equalplans'] = array();
		}

		$spg1	= $this->params['similarplans'];
		$spg2	= $plan->params['similarplans'];

		$epg1	= $this->params['equalplans'];
		$epg2	= $plan->params['equalplans'];

		if ( empty( $spg1 ) && empty( $spg2 ) && empty( $epg1 ) && empty( $epg2 ) ) {
			return false;
		}

		if ( in_array( $this->id, $epg2 ) || in_array( $plan->id, $epg1 ) ) {
			return 2;
		} elseif ( in_array( $this->id, $spg2 ) || in_array( $plan->id, $spg1 ) ) {
			return 1;
		} else {
			return false;
		}
	}

	function getMIformParams( $metaUser, $errors=array() )
	{
		$mis = $this->getMicroIntegrations();

		if ( !empty( $mis ) ) {
			$db = &JFactory::getDBO();

			$params = array();
			$lists = array();
			foreach ( $mis as $mi_id ) {

				$mi = new MicroIntegration( $db );
				$mi->load( $mi_id );

				if ( !$mi->callIntegration() ) {
					continue;
				}

				$miform_params = $mi->getMIformParams( $this, $metaUser, $errors );

				if ( !empty( $miform_params['lists'] ) ) {
					foreach ( $miform_params['lists'] as $lname => $lcontent ) {
						$lists[$lname] = $lcontent;
					}

					unset( $miform_params['lists'] );
				}

				foreach ( $miform_params as $pk => $pv ) {
					$params[$pk] = $pv;
				}
			}

			$params['lists'] = $lists;

			return $params;
		} else {
			return false;
		}
	}

	function verifyMIformParams( $metaUser, $params=null )
	{
		$mis = $this->getMicroIntegrations();

		if ( !empty( $mis ) ) {
			$db = &JFactory::getDBO();

			$v = array();
			foreach ( $mis as $mi_id ) {
				$mi = new MicroIntegration( $db );
				$mi->load( $mi_id );

				if ( !$mi->callIntegration() ) {
					continue;
				}

				if ( !is_null( $params ) ) {
					if ( !empty( $params[$this->id][$mi->id] ) ) {
						$verify = $mi->verifyMIform( $this, $metaUser, $params[$this->id][$mi->id] );
					} else {
						$verify = $mi->verifyMIform( $this, $metaUser, array() );
					}
				} else {
					$verify = $mi->verifyMIform( $this, $metaUser );
				}

				if ( !empty( $verify ) && is_array( $verify ) ) {
					$v[] = array_merge( array( 'id' => $mi->id ), $verify );
				}
			}

			if ( empty( $v ) ) {
				return true;
			} else {
				return $v;
			}
		} else {
			return true;
		}
	}

	function getMIforms( $metaUser, $errors=array() )
	{
		$params = $this->getMIformParams( $metaUser, $errors );

		if ( empty( $params ) ) {
			return false;
		} else {
			$lists = $params['lists'];
			unset( $params['lists'] );

			if ( !empty( $params ) ) {
				$settings = new aecSettings ( 'mi', 'frontend_forms' );
				$settings->fullSettingsArray( $params, array(), $lists, array(), false ) ;

				$aecHTML = new aecHTML( $settings->settings, $settings->lists );
				return "<table>" . $aecHTML->returnFull( true, true, true ) . "</table>";
			} else {
				return null;
			}
		}
	}

	function getMicroIntegrations( $separate=false )
	{
		$db = &JFactory::getDBO();

		if ( empty( $this->micro_integrations ) ) {
			$milist = array();
		} else {
			$milist = $this->micro_integrations;
		}

		// Find parent ItemGroups to attach their MIs
		$parents = ItemGroupHandler::getParents( $this->id );

		foreach ( $parents as $parent ) {
			$g = new ItemGroup( $db );
			$g->load( $parent );

			if ( !empty( $g->params['micro_integrations'] ) ) {
				$milist = array_merge( $milist, $g->params['micro_integrations'] );
			}
		}

		if ( empty( $milist ) ) {
			return false;
		}

		$milist = microIntegrationHandler::getActiveListbyList( $milist );

		if ( empty( $milist ) ) {
			return false;
		}

		return $milist;
	}

	function getMicroIntegrationsSeparate( $strip_inherited=false )
	{
		$db = &JFactory::getDBO();

		if ( empty( $this->micro_integrations ) ) {
			$milist = array();
		} else {
			$milist = $this->micro_integrations;
		}

		// Find parent ItemGroups to attach their MIs
		$parents = ItemGroupHandler::getParents( $this->id );

		$pmilist = array();
		foreach ( $parents as $parent ) {
			$g = new ItemGroup( $db );
			$g->load( $parent );

			if ( !empty( $g->params['micro_integrations'] ) ) {
				$pmilist = array_merge( $pmilist, $g->params['micro_integrations'] );
			}
		}

		if ( empty( $milist ) && empty( $pmilist ) ) {
			return array( 'plan' => array(), 'inherited' => array() );
		}

		$milist = microIntegrationHandler::getActiveListbyList( $milist );
		$pmilist = microIntegrationHandler::getActiveListbyList( $pmilist );

		if ( empty( $milist ) && empty( $pmilist ) ) {
			return array( 'plan' => array(), 'inherited' => array() );
		}

		// Remove entries from the plan MIs that are already inherited
		if ( !empty( $pmilist ) && !empty( $milist ) && $strip_inherited ) {
			$theintersect = array_intersect( $pmilist, $milist );

			if ( !empty( $theintersect ) ) {
				foreach ( $theintersect as $value ) {
					// STAY IN THE CAR
					unset( $milist[array_search( $value, $milist )] );
				}
			}
		}

		return array( 'plan' => $milist, 'inherited' => $pmilist );
	}

	function triggerMIs( $action, &$metaUser, &$exchange, &$invoice, &$add, &$silent )
	{
		global $aecConfig;

		$db = &JFactory::getDBO();

		$micro_integrations = $this->getMicroIntegrations();

		if ( is_array( $micro_integrations ) ) {
			foreach ( $micro_integrations as $mi_id ) {
				$mi = new microIntegration( $db );

				if ( !$mi->mi_exists( $mi_id ) ) {
					continue;
				}

				$mi->load( $mi_id );

				if ( !$mi->callIntegration() ) {
					continue;
				}

				$is_email = strcmp( $mi->class_name, 'mi_email' ) === 0;

				// TODO: Only trigger if this is not email or made not silent
				if ( method_exists( $metaUser, $action ) ) {
					if ( $mi->$action( $metaUser, null, $invoice, $this ) === false ) {
						if ( $aecConfig->cfg['breakon_mi_error'] ) {
							return false;
						}
					}
				} else {
					$params = array();
					if ( isset( $invoice->params['userMIParams'] ) ) {
						if ( is_array( $invoice->params['userMIParams'] ) ) {
							if ( isset( $invoice->params['userMIParams'][$this->id][$mi->id] ) ) {
								$params = $invoice->params['userMIParams'][$this->id][$mi->id];
							}
						}
					}

					if ( $mi->relayAction( $metaUser, $exchange, $invoice, $this, $action, $add, $params ) === false ) {
						if ( $aecConfig->cfg['breakon_mi_error'] ) {
							return false;
						}
					}
				}
			}
		} else {
			return null;
		}

		return true;
	}

	function getProcessorParameters( $processor )
	{
		$procparams = array();
		$filter = array();
		if ( !empty( $this->custom_params ) ) {
			if ( empty( $this->custom_params[$processor->id.'_aec_overwrite_settings'] ) ) {
				if ( method_exists( $processor->processor, 'CustomPlanParams' ) ) {
					$filter = $processor->processor->CustomPlanParams();
				}

				if ( empty( $filter ) ) {
					return $procparams;
				}
			}

			foreach ( $this->custom_params as $name => $value ) {
				$realname = explode( '_', $name, 2 );

				if ( !empty( $filter ) ) {
					if ( !array_key_exists( $realname[1], $filter ) ) {
						continue;
					}
				}

				if ( ( $realname[0] == $processor->id ) && isset( $realname[1] ) ) {
					$procparams[$realname[1]] = $value;
				}
			}
		}

		return $procparams;
	}

	function getRestrictionsArray()
	{
		return aecRestrictionHelper::getRestrictionsArray( $this->restrictions );
	}

	function savePOSTsettings( $post )
	{
		$db = &JFactory::getDBO();

		if ( !empty( $post['id'] ) ) {
			$planid = $post['id'];
		} else {
			// Fake knowing the planid if is zero.
			$planid = $this->getMax() + 1;
		}

		if ( isset( $post['id'] ) ) {
			unset( $post['id'] );
		}

		if ( isset( $post['inherited_micro_integrations'] ) ) {
			unset( $post['inherited_micro_integrations'] );
		}

		if ( !empty( $post['add_group'] ) ) {
			ItemGroupHandler::setChildren( $post['add_group'], array( $planid ) );
			unset( $post['add_group'] );
		}

		if ( empty( $post['micro_integrations'] ) ) {
			$post['micro_integrations'] = array();
		}

		if ( !empty( $post['micro_integrations_plan'] ) ) {
			foreach ( $post['micro_integrations_plan'] as $miname ) {
				// Create new blank MIs
				$mi = new microIntegration( $db );
				$mi->load(0);

				$mi->class_name = $miname;

				if ( !$mi->callIntegration( true ) ) {
					continue;
				}

				$mi->hidden = 1;

				$mi->storeload();

				// Add in new MI id
				$post['micro_integrations'][] = $mi->id;
			}

			$mi->reorder();

			unset( $post['micro_integrations_plan'] );
		}

		if ( !empty( $post['micro_integrations_hidden'] ) ) {
			// Recover hidden MI relation to full list
			$post['micro_integrations'] = array_merge( $post['micro_integrations'], $post['micro_integrations_hidden'] );

			unset( $post['micro_integrations_hidden'] );
		}

		if ( !empty( $post['micro_integrations_inherited'] ) ) {
			unset( $post['micro_integrations_inherited'] );
		}

		// Update MI settings
		foreach ( $post['micro_integrations'] as $miid ) {
			$mi = new microIntegration( $db );
			$mi->load( $miid );

			// Only act special on hidden MIs
			if ( !$mi->hidden ) {
				continue;
			}

			$prefix = 'MI_' . $miid . '_';

			// Get Settings from post array
			$settings = array();
			foreach ( $post as $name => $value ) {
				if ( strpos( $name, $prefix ) === 0 ) {
					$rname = str_replace( $prefix, '', $name );

					$settings[$rname] = $value;
					unset( $post[$name] );
				}
			}

			// If we indeed HAVE settings, more to come here
			if ( !empty( $settings ) ) {
				$mi->savePostParams( $settings );

				// First, check whether there is already an MI with the exact same settings
				$similarmis = microIntegrationHandler::getMIList( false, false, true, false, $mi->classname );

				$similarmi = false;
				if ( !empty( $similarmis ) ) {
					foreach ( $similarmis as $miobj ) {
						if ( $miobj->id == $mi->id ) {
							continue;
						}

						if ( microIntegrationHandler::compareMIs( $mi, $miobj->id ) ) {
							$similarmi = $miobj->id;
						}
					}
				}

				if ( $similarmi ) {
					// We have a similar MI - unset old reference
					$ref = array_search( $mi->id, $post['micro_integrations'] );
					unset( $post['micro_integrations'][$ref] );

					// No MI is similar, lets check for other plans
					$plans = microIntegrationHandler::getPlansbyMI( $mi->id );

					foreach ( $plans as $cid => $pid ) {
						if ( $pid == $this->id ) {
							unset( $plans[$cid] );
						}
					}

					if ( count( $plans ) <= 1 ) {
						// No other plan depends on this MI, just delete it
						$mi->delete;
					}

					// Set new MI
					$post['micro_integrations'][] = $similarmi;
				} else {
					// No MI is similar, lets check for other plans
					$plans = microIntegrationHandler::getPlansbyMI( $mi->id );

					foreach ( $plans as $cid => $pid ) {
						if ( $pid == $this->id ) {
							unset( $plans[$cid] );
						}
					}

					if ( count( $plans ) > 1 ) {
						// We have other plans depending on THIS setup of the MI, unset original reference
						$ref = array_search( $mi->id, $post['micro_integrations'] );
						unset( $post['micro_integrations'][$ref] );

						// And create new MI
						$mi->id = 0;

						$mi->storeload();

						// Set new MI
						$post['micro_integrations'][] = $mi->id;
					} else {
						$mi->storeload();
					}
				}
			}
		}

		// Filter out fixed variables
		$fixed = array( 'active', 'visible', 'name', 'desc', 'email_desc', 'micro_integrations' );

		foreach ( $fixed as $varname ) {
			if ( isset( $post[$varname] ) ) {
				$this->$varname = $post[$varname];

				unset( $post[$varname] );
			} else {
				$this->$varname = '';
			}
		}

		// Get selected processors ( have to be filtered out )

		$processors = array();
		foreach ( $post as $key => $value ) {
			if ( ( strpos( $key, 'processor_' ) === 0 ) && $value ) {
				$ppid = str_replace( 'processor_', '', $key );

				if ( !in_array( $ppid, $processors ) ) {
					$processors[] = $ppid;
					unset( $post[$key] );
				}
			}
		}

		// Filter out params
		$fixed = array( 'full_free', 'full_amount', 'full_period', 'full_periodunit',
						'trial_free', 'trial_amount', 'trial_period', 'trial_periodunit',
						'gid_enabled', 'gid', 'lifetime', 'standard_parent',
						'fallback', 'fallback_req_parent', 'similarplans', 'equalplans', 'make_active',
						'make_primary', 'update_existing', 'customthanks', 'customtext_thanks_keeporiginal',
						'customamountformat', 'customtext_thanks', 'override_activation', 'override_regmail',
						'notauth_redirect', 'fixed_redirect', 'hide_duration_checkout', 'addtocart_redirect',
						'cart_behavior', 'notes', 'meta'
						);

		$params = array();
		foreach ( $fixed as $varname ) {
			if ( !isset( $post[$varname] ) ) {
				continue;
			}

			$params[$varname] = $post[$varname];

			unset( $post[$varname] );
		}

		$params['processors'] = $processors;

		$this->saveParams( $params );

		// Filter out restrictions
		$fixed = aecRestrictionHelper::paramList();

		$fixed = array_merge( $fixed, array( 'inventory_amount_enabled', 'inventory_amount', 'inventory_amount_used' ) );

		$restrictions = array();
		foreach ( $fixed as $varname ) {
			if ( !isset( $post[$varname] ) ) {
				continue;
			}

			$restrictions[$varname] = $post[$varname];

			unset( $post[$varname] );
		}

		$this->restrictions = $restrictions;

		// There might be deletions set for groups
		foreach ( $post as $varname => $content ) {
			if ( ( strpos( $varname, 'group_delete_' ) !== false ) && $content ) {
				$parentid = (int) str_replace( 'group_delete_', '', $varname );

				ItemGroupHandler::removeChildren( $planid, array( $parentid ) );

				unset( $post[$varname] );
			}
		}

		// The rest of the vars are custom params
		$custom_params = array();
		foreach ( $post as $varname => $content ) {
			if ( substr( $varname, 0, 4 ) != 'mce_' ) {
				$custom_params[$varname] = $content;
			}
			unset( $post[$varname] );
		}

		$this->custom_params = $custom_params;
	}

	function saveParams( $params )
	{
		$db = &JFactory::getDBO();

		// If the admin wants this to be a free plan, we have to make this more explicit
		// Setting processors to zero and full_free
		if ( $params['full_free'] && ( $params['processors'] == '' ) ) {
			$params['processors']	= '';
		} elseif ( !$params['full_amount'] || ( $params['full_amount'] == '0.00' ) || ( $params['full_amount'] == '' ) ) {
			$params['full_free']	= 1;
			$params['processors']	= '';
		}

		// Correct a malformed Full Amount
		if ( !strlen( $params['full_amount'] ) ) {
			$params['full_amount']	= '0.00';
			$params['full_free']	= 1;
			$params['processors']	= '';
		} else {
			$params['full_amount'] = AECToolbox::correctAmount( $params['full_amount'] );
		}

		// Correct a malformed Trial Amount
		if ( strlen( $params['trial_amount'] ) ) {
			$params['trial_amount'] = AECToolbox::correctAmount( $params['trial_amount'] );
		}

		// Prevent setting Trial Amount to 0.00 if no free trial was asked for
		if ( !$params['trial_free'] && ( strcmp( $params['trial_amount'], "0.00" ) === 0 ) ) {
			$params['trial_amount'] = '';
		}

		$this->params = $params;
	}

	function copy()
	{
		$pid = $this->id;

		$this->id = 0;
		$this->storeload();

		$parents = ItemGroupHandler::parentGroups( $pid, 'item' );

		foreach ( $parents as $parentid ) {
			ItemGroupHandler::setChild( $this->id, $parentid, 'item' );
		}
	}

	function delete()
	{
		ItemGroupHandler::removeChildren( $this->id );

		return parent::delete();
	}

}

class logHistory extends serialParamDBTable
{
	/** @var int Primary key */
	var $id					= null;
	/** @var int */
	var $proc_id;
	/** @var string */
	var $proc_name;
	/** @var int */
	var $user_id;
	/** @var string */
	var $user_name;
	/** @var int */
	var $plan_id;
	/** @var string */
	var $plan_name;
	/** @var datetime */
	var $transaction_date	= null;
	/** @var string */
	var $amount;
	/** @var string */
	var $invoice_number;
	/** @var string */
	var $response;

	/**
	* @param database A database connector object
	*/
	function logHistory( &$db )
	{
		parent::__construct( '#__acctexp_log_history', 'id', $db );
	}

	function declareParamFields()
	{
		return array( 'response' );
	}

	function load( $id )
	{
		parent::load( $id );

		if ( $this->cleanup() ) {
			$this->storeload();
		}
	}

	function cleanup()
	{
		if ( is_array( $this->response ) ) {
			if ( count( $this->response ) == 1 ) {
				foreach( $this->response as $k => $v ) {
					if ( !is_array( $v ) ) {
						$this->response = unserialize( base64_decode( $k ) );
						
						if ( !is_array( $this->response ) ) {
							return false;
						}

						return true;
					} elseif ( !is_array( $k ) ) {
						$this->response = unserialize( base64_decode( $v ) );

						if ( !is_array( $this->response ) ) {
							return false;
						}

						return true;
					}
				}
			}
		}

		return false;
	}

	function entryFromInvoice( $objInvoice, $response, $pp )
	{
		$db = &JFactory::getDBO();

		$user = new JTableUser( $db );
		$user->load( $objInvoice->userid );

		$plan = new SubscriptionPlan( $db );
		$plan->load( $objInvoice->usage );

		if ( $pp->id ) {
			$this->proc_id			= $pp->id;
			$this->proc_name		= $pp->processor_name;
		}

		$this->user_id			= $user->id;
		$this->user_name		= $user->username;

		if ( $plan->id ) {
			$this->plan_id			= $plan->id;
			$this->plan_name		= $plan->name;
		}

		$this->transaction_date	= date( 'Y-m-d H:i:s', ( ( (int) gmdate('U') ) ) );
		$this->amount			= $objInvoice->amount;
		$this->invoice_number	= $objInvoice->invoice_number;
		$this->response			= $response;

		$this->cleanup();

		$short	= 'history entry';
		$event	= 'Processor (' . $pp->processor_name . ') notification for ' . $objInvoice->invoice_number;
		$tags	= 'history,processor,payment';
		$params = array( 'invoice_number' => $objInvoice->invoice_number );

		$eventlog = new eventLog( $db );
		$eventlog->issue( $short, $tags, $event, 2, $params );

		$this->check();
		$this->store();
	}
}

class aecTempToken extends serialParamDBTable
{
	/** @var int Primary key */
	var $id					= null;
	/** @var string */
	var $token 				= null;
	/** @var text */
	var $content 			= null;
	/** @var datetime */
	var $created_date	 	= null;
	/** @var string */
	var $ip		 			= null;

	function aecTempToken(&$db)
	{
		parent::__construct( '#__acctexp_temptoken', 'id', $db );
	}

	function declareParamFields()
	{
		return array( 'content' );
	}

	function getToken()
	{
		$session =& JFactory::getSession();
		return $session->getToken();
	}

	function getComposite()
	{
		$token = $this->getToken();

		$this->getByToken( $token );

		if ( empty( $this->content ) && !empty( $_COOKIE['aec_token'] ) ) {
			$token = $_COOKIE['aec_token'];

			$this->getByToken( $token );
		}

		if ( empty( $this->token ) ) {
			$this->token = $token;
		}

		if ( empty( $this->ip ) ) {
			$app = JFactory::getApplication();

			$this->created_date	= date( 'Y-m-d H:i:s', ( (int) gmdate('U') ) );
			$this->ip			= $_SERVER['REMOTE_ADDR'];
		}
	}

	function getByToken( $token )
	{
		$db = &JFactory::getDBO();

		$query = 'SELECT `id`'
		. ' FROM #__acctexp_temptoken'
		. ' WHERE `token` = \'' . $token . '\''
		;
		$db->setQuery( $query );
		$id = $db->loadResult();

		if ( empty( $id ) ) {
			$query = 'SELECT `id`'
			. ' FROM #__acctexp_temptoken'
			. ' WHERE `ip` = \'' . $_SERVER['REMOTE_ADDR'] . '\''
			;
			$db->setQuery( $query );
			$id = $db->loadResult();
		}

		$this->load( $id );

		if ( $this->ip != $_SERVER['REMOTE_ADDR'] ) {
			$this->delete();

			$this->load(0);
		}
	}

	function create( $content, $token=null )
	{
		$db = &JFactory::getDBO();

		$app = JFactory::getApplication();

		if ( empty( $token ) ) {
			$session =& JFactory::getSession();
			$token = $session->getToken();
		}

		$query = 'SELECT `id`'
		. ' FROM #__acctexp_temptoken'
		. ' WHERE `token` = \'' . $token . '\''
		. ' OR `ip` = \'' . $_SERVER['REMOTE_ADDR'] . '\''
		;
		$db->setQuery( $query );
		$id = $db->loadResult();

		if ( $id ) {
			$this->id		= $id;
		}

		if ( empty( $token ) ) {
			$token = date( 'Y-m-d H:i:s', ( (int) gmdate('U') ) );
		}

		$this->token		= $token;
		$this->content		= $content;
		$this->created_date	= date( 'Y-m-d H:i:s', ( (int) gmdate('U') ) );
		$this->ip			= $_SERVER['REMOTE_ADDR'];

		setcookie( 'aec_token', $token, ( (int) gmdate('U') )+ 600 );

		return $this->storeload();
	}
}

class InvoiceFactory
{
	/** @var int */
	var $userid			= null;
	/** @var string */
	var $usage			= null;
	/** @var string */
	var $processor		= null;
	/** @var string */
	var $invoice		= null;
	/** @var int */
	var $confirmed		= null;

	function InvoiceFactory( $userid=null, $usage=null, $group=null, $processor=null, $invoice=null, $passthrough=null, $alert=true, $forceinternal=false )
	{
		$this->initUser( $userid, $alert, $forceinternal );

		// Init variables
		$this->usage			= $usage;
		$this->group			= $group;
		$this->processor		= $processor;
		$this->invoice_number	= $invoice;

		$this->initPassthrough( $passthrough );

		$this->verifyUsage();
	}

	function initUser( $userid, $alert=true, $forceinternal=false )
	{
		$user = &JFactory::getUser();

		$this->userid = $userid;
		$this->authed = false;

		// Check whether this call is legitimate
		if ( empty( $user->id ) || $forceinternal ) {
			if ( !$this->userid || $forceinternal ) {
				// It's ok, this is a registration/subscription hybrid call
				$this->authed = true;
			} elseif ( $this->userid ) {
				if ( AECToolbox::quickVerifyUserID( $this->userid ) === true ) {
					// This user is not expired, so she could log in...
					if ( $alert ) {
						return aecNotAuth();
					}
				} else {
					$db = &JFactory::getDBO();

					$this->userid = $db->getEscaped( $userid );

					// Delete set userid if it doesn't exist
					if ( !is_null( $this->userid ) ) {
						$query = 'SELECT `id`'
								. ' FROM #__users'
								. ' WHERE `id` = \'' . $this->userid . '\'';
						$db->setQuery( $query );

						if ( !$db->loadResult() ) {
							$this->userid = null;
						}
					}
				}
			}
		} else {
			// Overwrite the given userid when user is logged in
			$this->userid = $user->id;
			$this->authed = true;
		}
	}

	function initPassthrough( $passthrough )
	{
		if ( empty( $passthrough ) ) {
			$passthrough = aecPostParamClear( $_POST, '', true );
		}

		if ( isset( $passthrough['aec_passthrough'] ) ) {
			if ( is_array( $passthrough['aec_passthrough'] ) ) {
				$this->passthrough = $passthrough['aec_passthrough'];
			} else {
				$this->passthrough = unserialize( base64_decode( $passthrough['aec_passthrough'] ) );
			}

			unset( $passthrough['aec_passthrough'] );

			if ( !empty( $passthrough ) ) {
				foreach ( $passthrough as $k => $v ) {
					$this->passthrough[$k] = $v;
				}
			}
		} else {
			$this->passthrough = $passthrough;
		}
	}

	function verifyUsage()
	{
		if ( empty( $this->usage ) ) {
			return null;
		}

		$this->loadMetaUser();

		$db = &JFactory::getDBO();

		$plan = new SubscriptionPlan( $db );
		$plan->load( $this->usage );

		$restrictions = $plan->getRestrictionsArray();

		if ( !aecRestrictionHelper::checkRestriction( $restrictions, $this->metaUser ) ) {
			return aecNotAuth();
		}

		if ( !ItemGroupHandler::checkParentRestrictions( $plan, 'item', $this->metaUser ) ) {
			return aecNotAuth();
		}
	}

	function usageStatus()
	{
		if ( !empty( $this->usage ) && ( strpos( $this->usage, 'c' ) !== false ) ) {
			$this->getCart();

			foreach ( $this->cart as $citem ) {
				if ( is_object( $citem['obj'] ) ) {
					if ( !$citem['obj']->active || !$citem['obj']->checkInventory() ) {
						return false;
					}
				}
			}

			return true;
		} elseif ( !empty( $this->usage ) ) {
			return SubscriptionPlanHandler::PlanStatus( $this->usage );
		} else {
			return true;
		}
	}

	function getPassthrough( $unset=null )
	{
		if ( !empty( $this->passthrough ) ) {
			$passthrough = $this->passthrough;

			$unsets = array( 'id', 'gid', 'forget', 'task', 'option' );

			switch ( $unset ) {
				case 'userdetails':
					$unsets = array_merge( $unsets, array( 'name', 'username', 'password', 'password2', 'email' ) );
					break;
				case 'usage':
					$unsets = array_merge( $unsets, array( 'usage', 'processor', 'recurring' ) );
					break;
				default:
					break;
			}

			foreach ( $unsets as $n ) {
				if ( isset( $passthrough[$n] ) ) {
					unset( $passthrough[$n] );
				}
			}

			return base64_encode( serialize( $passthrough ) );
		} else {
			return "";
		}
	}

	function puffer( $option, $testmi=false )
	{
		$this->loadPlanObject( $option, $testmi );

		$this->loadProcessorObject();

		$this->loadRenewStatus();

		$this->loadPaymentInfo();

		return;
	}

	function loadPlanObject( $option, $testmi=false, $quick=false )
	{
		$db = &JFactory::getDBO();

		if ( !empty( $this->usage ) && ( strpos( $this->usage, 'c' ) === false ) ) {
			// get the payment plan
			$this->plan = new SubscriptionPlan( $db );
			$this->plan->load( $this->usage );

			if ( empty( $this->processor ) ) {
				// Recover from missing processor selection
				if (
					// If it is either made free through coupons
					!empty( $this->invoice->made_free )
					// Or a free full period that the user CAN use and no trial
					|| ( $this->plan->params['full_free'] && empty( $this->invoice->counter ) && empty( $this->plan->params['trial_period'] ) )
					// Or a free full period that the user CAN use and a skipped trial
					|| ( $this->plan->params['full_free'] && $this->invoice->counter )
					// Or a free trial that the user CAN use
					|| ( $this->plan->params['trial_free'] && empty( $this->invoice->counter ) )
				) {
					if ( !isset( $this->recurring ) ) {
						$this->recurring = 0;
					}

					// Only allow clearing while recurring if everything is free
					if ( !( $this->recurring && ( empty( $this->plan->params['full_free'] ) || empty( $this->plan->params['trial_free'] ) ) ) ) {
						$this->processor = 'free';
					}
				}

				if ( empty( $this->processor ) ) {
					// It's not free, so select the only processor we have available
					if ( !empty( $this->plan->params['processors'] ) ) {
						foreach ( $this->plan->params['processors'] as $proc ) {
							$pp = new PaymentProcessor();

							if ( !$pp->loadId( $proc ) ) {
								continue;
							}

							$this->processor = $pp->processor_name;
							break;
						}
					}
				}
			}

			if ( !is_object( $this->plan ) ) {
				return aecNotAuth();
			}
		} elseif ( !empty( $this->usage ) ) {
			if ( empty( $this->metaUser ) ) {
				return aecNotAuth();
			}

			if ( !isset( $this->cartprocexceptions ) ) {
				$this->getCart();

				$this->usage = 'c.' . $this->cartobject->id;

				$procs = aecCartHelper::getCartProcessorList( $this->cartobject );

				if ( count( $procs ) > 1 ) {
					$this->cartItemsPPselectForm( $option );
				} else {
					if ( isset( $procs[0] ) ) {
						$pgroups = aecCartHelper::getCartProcessorGroups( $this->cartobject );

						$proc = $pgroups[0]['processors'][0];

						if ( strpos( $proc, '_recurring' ) ) {
							$this->recurring = 1;

							$proc = str_replace( '_recurring', '', $proc );
						}

						$procname = PaymentProcessorHandler::getProcessorNamefromId( $proc );

						if ( !empty( $procname ) ) {
							$this->processor = $procname;
						}

						$this->plan = aecCartHelper::getCartItemObject( $this->cartobject, 0 );
					} else {
						$am = $this->cartobject->getAmount( $this->metaUser, 0, $this );

						if ( $am['amount'] == "0.00" ) {
							$this->processor = 'free';
						} else {
							$this->processor = 'none';
						}
					}
				}

				$this->cartprocexceptions = true;
			}

			if ( !isset( $this->mi_error ) ) {
				$this->mi_error = array();
			}

			$offset = 0;

			if ( !empty( $this->exceptions ) ) {
				$offset = count( $this->exceptions );
			}

			if ( empty( $this->cart ) || $quick ) {
				return;
			}

			foreach ( $this->cart as $cid => $cartitem ) {
				$mi_form = null;
				if ( empty( $cartitem['obj'] ) ) {
					continue;
				}

				$mi_form = $cartitem['obj']->getMIformParams( $this->metaUser, $this->mi_error );

				$lists = null;
				if ( isset( $mi_form['lists'] ) && !empty( $mi_form['lists'] ) ) {
					$lists = $mi_form['lists'];

					unset( $mi_form['lists'] );
				}

				if ( empty( $mi_form ) ) {
					continue;
				}

				if ( !empty( $lists ) ) {
					// Rewrite lists so they fit a multi-plan context
					foreach( $lists as $lkey => $litem ) {
						$mi_form['lists'][($offset).'_'.$lkey] = str_replace( $lkey, ($offset).'_'.$lkey, $litem );
					}
				}

				$this->mi_error = array();

				$check = $this->verifyMIForms( $cartitem['obj'], $mi_form,  $offset.'_' );

				$offset++;

				if ( !$check ) {
					$ex = array();
					$ex['head'] = "";

					if ( !empty( $this->mi_error ) ) {
						$ex['desc'] = "<p>" . implode( "</p><p>", $this->mi_error ) . "</p>";
					} else {
						$ex['desc'] = "";
					}

					$ex['rows'] = $mi_form;

					$this->raiseException( $ex );
				}

				if ( is_array( $this->passthrough ) ) {
					foreach ( $mi_form as $mik => $miv ) {
						if ( $mik == 'lists' ) {
							continue;
						}

						foreach ( $this->passthrough as $pid => $pk ) {
							if ( !is_array( $pk ) ) {
								continue;
							}

							if ( ( $pk[0] == $mik ) || ( $pk[0] == $mik.'[]' ) ) {
								unset($this->passthrough[$pid]);
							}
						}
					}
				}
			}
		}
	}

	function cartItemsPPselectForm( $option )
	{
		$pgroups = aecCartHelper::getCartProcessorGroups( $this->cartobject );

		$c				= false;
		$exception		= false;
		$selected		= array();
		$selected_form	= array();
		$single_select	= true;

		foreach ( $pgroups as $pgid => $pgroup ) {
			if ( count( $pgroup['processors'] ) < 2 ) {
				if ( !empty( $pgroup['processors'][0] ) ) {
					$pgroups[$pgid]['processor'] = $pgroup['processors'][0];
				}

				continue;
			}

			$ex = array();
			if ( $c ) {
				$ex['head'] = null;
				$ex['desc'] = null;
			} else {
				$ex['head'] = "Select Payment Processor";
				$ex['desc'] = "There are a number of possible payment processors for one or more of your items, please select one below:<br />";
			}

			$ex['rows'] = array();

			$fname = 'cartgroup_'.$pgid.'_processor';

			$pgsel = aecGetParam( $fname, null, true, array( 'word', 'string' ) );

			if ( empty( $pgsel ) ) {
				$pgsel = aecGetParam( $pgid.'_'.$fname, null, true, array( 'word', 'string' ) );
			}

			$selection = false;
			if ( !is_null( $pgsel ) ) {
				if ( in_array( $pgsel, $pgroup['processors'] ) ) {
					$selection = $pgsel;
				}
			}

			if ( !empty( $selection ) ) {
				if ( count( $selected ) > 0 ) {
					if ( !in_array( $selection, $selected ) ) {
						$single_select = false;
					}
				}

				$pgroups[$pgid]['processor'] = $selection;
				$selected[] = $selection;

				$selected_form[] = array( 'hidden', $pgsel, $fname, $pgsel );

				$this->processor_userselect = true;

				continue;
			} else {
				$c = true;

				$ex['desc'] .= "<ul>";

				foreach ( $pgroup['members'] as $pgmember ) {
					$ex['desc'] .= "<li><strong>" . $this->cart[$pgmember]['name'] . "</strong><br /></li>";
				}

				$ex['desc'] .= "</ul>";

				foreach ( $pgroup['processors'] as $pid => $pgproc ) {
					$pgex = $pgproc;

					if ( strpos( $pgproc, '_recurring' ) ) {
						$pgex = str_replace( '_recurring', '', $pgproc );
						$recurring = true;
					} else {
						$recurring = false;
					}

					$ex['rows'][] = array( 'radio', $fname, $pgproc, true, $pgroup['processor_names'][$pid].( $recurring ? ' (recurring billing)' : '') );
				}
			}

			if ( !empty( $ex['rows'] ) && $c ) {
				$this->raiseException( $ex );

				$exception = true;
			}
		}

		if ( $exception && !empty( $selected_form ) ) {
			$ex = array();
			$ex['head'] = null;
			$ex['desc'] = null;
			$ex['rows'] = array();

			foreach ( $selected_form as $silent ) {
				$ex['rows'][] = $silent;
			}

			$this->raiseException( $ex );
		}

		$finalinvoice = null;
		if ( $single_select ) {
			if ( !empty( $selection ) ) {
				$this->processor = PaymentProcessor::getNameById( str_replace( '_recurring', '', $selection ) );
			}
		} else {
			$db = &JFactory::getDBO();

			// We have different processors selected for this cart
			$prelg = array();
			foreach ( $pgroups as $pgid => $pgroup ) {
				$prelg[$pgroup['processor']][] = $pgroup;
			}

			foreach ( $prelg as $processor => $pgroups ) {
				if ( strpos( $processor, '_recurring' ) ) {
					$processor_name = PaymentProcessor::getNameById( str_replace( '_recurring', '', $processor ) );

					$procrecurring = true;
				} else {
					$processor_name = PaymentProcessor::getNameById( $processor );

					if ( isset( $_POST['recurring'] ) ) {
						$procrecurring = $_POST['recurring'];
					} else {
						$procrecurring = false;
					}
				}

				$mpg = array_pop( array_keys( $pgroups ) );
				if ( ( count( $pgroups ) > 1 ) || ( count( $pgroups[$mpg]['members'] ) > 1 ) ) {
					// We have more than one item for this processor, create temporary cart
					$tempcart = new aecCart( $db );
					$tempcart->userid = $this->userid;

					foreach ( $pgroups as $pgr ) {
						foreach ( $pgr['members'] as $member ) {
							$r = $tempcart->addItem( array(), $this->cartobject->content[$member]['id'] );
						}
					}

					$tempcart->storeload();

					$carthash = 'c.' . $tempcart->id;

					// Create a cart invoice
					$invoice = new Invoice( $db );
					$invoice->create( $this->userid, $carthash, $processor_name, null, true, $this, $procrecurring );
				} else {
					// Only one item in this, create a simple invoice
					$member = $pgroups[$mpg]['members'][0];

					$invoice = new Invoice( $db );
					$invoice->create( $this->userid, $this->cartobject->content[$member]['id'], $processor_name, null, true, $this, $procrecurring );
				}

				if ( $invoice->amount == "0.00" ) {
					$invoice->pay();
				} else {
					$finalinvoice = $invoice;
				}
			}

			$ex['head'] = "Invoice split up";
			$ex['desc'] = "The contents of your shopping cart cannot be processed in one go. This is why we have split up the invoice - you can pay for the first part right now and access the other parts as separate invoices later from your membership page.";
			$ex['rows'] = array();

			$this->raiseException( $ex );

			$this->invoice_number = $finalinvoice->invoice_number;
			$this->invoice = $finalinvoice;

			$this->touchInvoice( $option );

			$objUsage = $this->invoice->getObjUsage();

			if ( is_a( $objUsage, 'aecCart' ) ) {
				$this->cartobject = $objUsage;

				$this->getCart();
			} else {
				$this->plan = $objUsage;
			}
		}
	}

	function loadProcessorObject()
	{
		if ( !empty( $this->processor ) ) {
			$this->pp					= false;
			$this->payment->method_name = JText::_('AEC_PAYM_METHOD_NONE');
			$this->payment->currency	= '';

			if ( !isset( $this->recurring ) ) {
				$this->recurring		= 0;
			}

			switch ( $this->processor ) {
				case 'free': $this->payment->method_name = JText::_('AEC_PAYM_METHOD_FREE'); break;
				case 'none': break;
				default:
					$this->pp = new PaymentProcessor();
					if ( $this->pp->loadName( $this->processor ) ) {
						$this->pp->fullInit();
						if ( !empty( $this->plan ) ) {
							$this->pp->exchangeSettingsByPlan( $this->plan );
						}

						$this->payment->method_name	= $this->pp->info['longname'];

						// Check whether we have a recurring payment
						// If it has been selected just now, or earlier, check whether that is still permitted
						if ( isset( $_POST['recurring'] ) ) {
							$this->recurring	= $this->pp->is_recurring( $_POST['recurring'] );
						} else {
							$this->recurring	= $this->pp->is_recurring( $this->recurring );
						}

						$this->payment->currency	= isset( $this->pp->settings['currency'] ) ? $this->pp->settings['currency'] : '';
					} else {
						$db = &JFactory::getDBO();

						$short	= 'processor loading failure';
						$event	= 'Tried to load processor: ' . $this->processor;
						$tags	= 'processor,loading,error';
						$params = array();

						$eventlog = new eventLog( $db );
						$eventlog->issue( $short, $tags, $event, 128, $params );
					}
					break;
			}
		}
	}

	function loadRenewStatus()
	{
		$user_subscription = false;
		$this->renew = 0;

		if ( !empty( $this->userid ) ) {
			if ( !empty( $this->metaUser ) ) {
				$this->renew = $this->metaUser->meta->is_renewing();
			} elseif ( AECfetchfromDB::SubscriptionIDfromUserID( $this->userid ) ) {
				$db = &JFactory::getDBO();

				$user_subscription = new Subscription( $db );
				$user_subscription->loadUserID( $this->userid );

				if ( ( strcmp( $user_subscription->lastpay_date, '0000-00-00 00:00:00' ) !== 0 )  ) {
					$this->renew = true;
				}
			}
		}
	}

	function loadPaymentInfo()
	{
		$this->payment->freetrial = 0;
		$this->payment->amount = null;

		if ( empty( $this->cart ) && !empty( $this->plan ) ) {
			if ( !isset( $this->recurring ) ) {
				$this->recurring = 0;
			}

			$terms = $this->plan->getTermsForUser( $this->recurring, $this->metaUser );

			if ( !empty( $terms ) ) {
				if ( is_object( $terms->nextterm ) ) {
					$this->payment->amount = $terms->nextterm->renderTotal();

					if ( $terms->nextterm->free && ( $terms->nextterm->get( 'type' ) == 'trial' ) ) {
						$this->payment->freetrial = 1;
					}
				}
			} else {
				$this->payment->amount = null;
			}

			$this->items->itemlist[] = array( 'item' => array( 'obj' => $this->plan ), 'terms' => $terms );
		} elseif ( !empty( $this->cartobject->id ) || ( $this->passthrough['task'] == 'confirmCart' ) ) {
			$this->getCart();

			$this->payment->amount = $this->cartobject->getAmount( $this->metaUser, 0, $this );
		} else {
			$this->payment->amount = $this->invoice->amount;
		}

		$this->payment->amount = AECToolbox::correctAmount( $this->payment->amount );

		if ( empty( $this->payment->currency ) && !empty( $this->invoice->currency ) ) {
			$this->payment->currency = $this->invoice->currency;
		}

		// Amend ->payment
		if ( !empty( $this->payment->currency ) ) {
			$this->payment->currency_symbol = AECToolbox::getCurrencySymbol( $this->payment->currency );
		} else {
			$this->payment->currency_symbol = '';
		}

		if ( !empty( $this->plan ) ) {
			$this->payment->amount_format = AECToolbox::formatAmountCustom( $this, $this->plan );
		} else {
			if ( !empty( $this->payment->currency ) ) {
				$this->payment->amount_format = AECToolbox::formatAmount( $this->payment->amount, $this->payment->currency );
			} else {
				$this->payment->amount_format = AECToolbox::formatAmount( $this->payment->amount );
			}
		}
	}

	function raiseException( $exception )
	{
		if ( empty( $this->exceptions ) ) {
			$this->exceptions = array();
		}

		$this->exceptions[] = $exception;
	}

	function hasExceptions()
	{
		return !empty( $this->exceptions );
	}

	function addressExceptions( $option )
	{
		$hasform = false;

		$lists = array();

		$params = array();
		foreach ( $this->exceptions as $eid => $ex ) {
			// Convert Exception into actionable form

			if ( !empty( $ex['rows'] ) ) {
				$hasform = true;
			}

			if ( isset( $ex['rows']['lists'] ) ) {
				$lists = array_merge( $lists, $ex['rows']['lists'] );

				unset( $ex['rows']['lists'] );
			}

			foreach ( $ex['rows'] as $rid => $row ) {
				if ( $row[0] == 'radio' ) {
					$row[1] = $eid.'_'.$row[1];
				}

				if ( $row[0] == 'hidden' ) {
					$row[2] = $eid.'_'.$row[2];
				}

				$params[$eid.'_'.$rid] = $row;
			}
		}

		$settings = new aecSettings ( 'exception', 'frontend_exception' );
		$settings->fullSettingsArray( $params, array(), $lists ) ;

		$aecHTML = new aecHTML( $settings->settings, $settings->lists );

		getView( 'exception', array( 'InvoiceFactory' => $this, 'aecHTML' => $aecHTML, 'hasform' => $hasform ) );
	}

	function getCart()
	{
		if ( empty( $this->cartobject ) ) {
			$this->cartobject = aecCartHelper::getCartbyUserid( $this->userid );
		}

		if ( empty( $this->cartobject->content ) && !empty( $this->invoice->params['cart'] ) ) {
			$this->cartobject = clone( $this->invoice->params['cart'] );
		}

		$this->loadMetaUser();

		if ( !empty( $this->cartobject->id ) ) {
			$this->cart = $this->cartobject->getCheckout( $this->metaUser, 0, $this );
		}

		if ( empty( $this->usage ) ) {
			$this->usage = 'c.'.$this->cartobject->id;
		}
	}

	function loadItems( $force=false )
	{
		$this->items = new stdClass();
		$this->items->itemlist = array();

		if ( !empty( $this->usage ) && ( strpos( $this->usage, 'c' ) === false ) ) {
			$db = &JFactory::getDBO();

			$terms = $this->plan->getTermsForUser( $this->recurring, $this->metaUser );

			if ( !empty( $this->plan ) ) {
				$c = $this->plan->doPlanComparison( $this->metaUser->objSubscription );

				// Do not allow a Trial if the user has used this or a similar plan
				if ( $terms->hasTrial && !$c['full_comparison'] ) {
					$terms->incrementPointer();
				}
			}

			$params = array();
			if ( !empty( $this->plan->params['hide_duration_checkout'] ) ) {
				$params['hide_duration_checkout'] = true;
			} else {
				$params['hide_duration_checkout'] = false;
			}

			$this->items->itemlist[] = array(	'obj'		=> $this->plan,
												'name'		=> $this->plan->getProperty( 'name' ),
												'desc'		=> $this->plan->getProperty( 'desc' ),
												'quantity'	=> 1,
												'terms'		=> $terms,
												'params'	=> $params
											);

			$cid = array_pop( array_keys( $this->items->itemlist ) );

			$this->cartobject = new aecCart( $db );
			$this->cartobject->addItem( array(), $this->plan );
		} elseif ( !empty( $this->usage ) ) {
			$this->getCart();

			foreach ( $this->cart as $cid => $citem ) {
				if ( $citem['obj'] !== false ) {
					$this->items->itemlist[$cid] = $citem;

					$terms = $citem['obj']->getTermsForUser( $this->recurring, $this->metaUser );

					$c = $citem['obj']->doPlanComparison( $this->metaUser->focusSubscription );

					// Do not allow a Trial if the user has used this or a similar plan
					if ( $terms->hasTrial && !$c['full_comparison'] ) {
						$terms->incrementPointer();
					}

					$this->items->itemlist[$cid]['terms'] = $terms;

					$params = array();
					if ( !empty( $citem['obj']->params['hide_duration_checkout'] ) ) {
						$params['hide_duration_checkout'] = true;
					} else {
						$params['hide_duration_checkout'] = false;
					}

					$this->items->itemlist[$cid]['params'] = $params;
				}
			}
		}

		$exchange = $silent = null;

		if ( !empty( $this->items->itemlist ) ) {
			foreach ( $this->items->itemlist as $cid => $citem ) {
				$this->triggerMIs( 'invoice_item_cost', $exchange, $this->items->itemlist[$cid], $silent );
			}
		}

		$this->applyCoupons();

		if ( !empty( $this->items->itemlist ) ) {
			foreach ( $this->items->itemlist as $cid => $citem ) {
				$this->triggerMIs( 'invoice_item', $exchange, $this->items->itemlist[$cid], $silent );
			}
		}
	}

	function loadItemTotal()
	{
		if ( empty( $this->items->itemlist ) ) {
			return null;
		}

		$cost = null;
		foreach ( $this->items->itemlist as $cid => $citem ) {
			if ( $citem['obj'] == false ) {
				continue;
			}

			$citem['terms']->nextterm->computeTotal();

			if ( empty( $cost ) ) {
				$ccost = $citem['terms']->nextterm->getBaseCostObject( false, true );

				$cost = clone( $ccost );

				if ( $citem['quantity'] > 1 ) {
					$cost->cost['amount'] = $ccost->cost['amount'] * $citem['quantity'];
				}
			} else {
				$ccost = $citem['terms']->nextterm->getBaseCostObject( false, true );

				$cost->cost['amount'] = $cost->cost['amount'] + ( $ccost->cost['amount'] * $citem['quantity'] );
			}
		}

		$this->items->total = $cost;

		if ( is_object( $cost ) ) {
			$this->items->grand_total = clone( $this->items->total );
		} else {
			$this->items->grand_total = $this->items->total;
		}

		if ( !empty( $this->items->discount ) ) {
			foreach ( $this->items->discount as $discount ) {
				foreach ( $discount as $term ) {
					foreach ( $term->cost as $cost ) {
						if ( $cost->type == 'total' ) {
							if ( is_object( $this->items->grand_total ) ) {
								$this->items->grand_total->cost['amount'] += $cost->cost['amount'];
							} else {
								$this->items->grand_total += $cost->cost['amount'];
							}
						}
					}
				}
			}
		}

		$exchange = $silent = null;

		$this->triggerMIs( 'invoice_items_total', $exchange, $this->items, $silent );

		// Reset Invoice Price
		$this->invoice->amount = $this->items->grand_total->cost['amount'];

		$this->invoice->storeload();
	}

	function applyCoupons()
	{
		global $aecConfig;

		if ( empty( $aecConfig->cfg['enable_coupons'] ) && empty( $this->invoice->coupons ) ) {
			return null;
		}

		$coupons = $this->invoice->coupons;

		$cpsh = new couponsHandler( $this->metaUser, $this, $coupons );

		if ( !empty( $this->cartobject ) && !empty( $this->cart ) ) {
			$this->items->itemlist = $cpsh->applyToCart( $this->items->itemlist, $this->cartobject, $this->cart );

			if ( count( $cpsh->delete_list ) ) {
				foreach ( $cpsh->delete_list as $couponcode ) {
					$this->invoice->removeCoupon( $couponcode );
				}

				$this->invoice->storeload();
			}

			if ( $cpsh->affectedCart ) {
				// Reload cart object and cart - was changed by $cpsh
				$this->cartobject->reload();
				$this->getCart();

				$cpsh = new couponsHandler( $this->metaUser, $this, $coupons );
				$this->items->itemlist = $cpsh->applyToCart( $this->items->itemlist, $this->cartobject, $this->cart );
			}
		} else {
			$this->items->itemlist = $cpsh->applyToItemList( $this->items->itemlist );

			if ( count( $cpsh->delete_list ) ) {
				foreach ( $cpsh->delete_list as $couponcode ) {
					$this->invoice->removeCoupon( $couponcode );
				}

				$this->invoice->storeload();
			}
		}

		$cpsh_err = $cpsh->getErrors();

		if ( !empty( $cpsh_err ) ) {
			$this->errors = $cpsh_err;
		}

		if ( !empty( $this->cartobject ) && !empty( $this->cart ) ) {
			$cpsh_exc = $cpsh->getExceptions();

			if ( count( $cpsh_exc ) ) {
				foreach ( $cpsh_exc as $exception ) {
					$this->raiseException( $exception );
				}
			}
		}

		if ( !empty( $this->cartobject ) && !empty( $this->cart ) ) {
			$this->items = $cpsh->applyToTotal( $this->items, $this->cartobject, $this->cart );
		} else {
			$this->items = $cpsh->applyToTotal( $this->items );
		}

		if ( !empty( $this->cart ) ) {
			$this->payment->amount = $this->cartobject->getAmount( $this->metaUser, 0, $this );
		}
	}

	function addtoCart( $option, $usage, $returngroup=null )
	{
		global $aecConfig;
		
		$db = &JFactory::getDBO();

		if ( empty( $this->cartobject ) ) {
			$this->cartobject = aecCartHelper::getCartbyUserid( $this->userid );
		}

		if ( !is_array( $usage ) ) {
			$id = $usage;

			$usage = array( $id );
		}

		foreach ( $usage as $us ) {
			$this->cartobject->action( 'addItem', $us );

			$plan = new SubscriptionPlan( $db );
			$plan->load( $us );
		}

		if ( !empty( $plan->params['addtocart_redirect'] ) ) {
			return aecRedirect( $plan->params['addtocart_redirect'] );
		} elseif ( $aecConfig->cfg['additem_stayonpage'] ) {
			if ( !empty( $returngroup ) ) {
				return $this->create( $option, 0, 0, $returngroup );
			} else {
				return $this->create( $option );
			}
		} else {
			$this->cart( $option );
		}
	}

	function updateCart( $option, $data )
	{
		$update = array();
		foreach ( $data as $dn => $dv ) {
			if ( strpos( $dn, 'cartitem_' ) !== false ) {
				$n = str_replace( 'cartitem_', '', $dn );

				$update[$n] = aecGetParam( $dn, 0, true, array( 'word', 'int' ) );
			}
		}

		if ( empty( $this->cartobject ) ) {
			$this->cartobject = aecCartHelper::getCartbyUserid( $this->userid );
		}

		$this->cartobject->action( 'updateItems', $update );
	}

	function clearCart( $option )
	{
		if ( empty( $this->cartobject ) ) {
			$this->cartobject = aecCartHelper::getCartbyUserid( $this->userid );
		}

		$this->cartobject->action( 'clearCart' );
	}

	function clearCartItem( $option, $item )
	{
		if ( empty( $this->cartobject ) ) {
			$this->cartobject = aecCartHelper::getCartbyUserid( $this->userid );
		}

		$this->cartobject->action( 'updateItems', array( $item => 0 ) );
	}

	function touchInvoice( $option, $invoice_number=false, $storenew=false, $anystatus=false )
	{
		// Checking whether we are trying to repeat an invoice
		if ( !empty( $invoice_number ) ) {
			// Make sure the invoice really exists and that its the correct user carrying out this action
			if ( AECfetchfromDB::InvoiceIDfromNumber( $invoice_number, $this->userid, $anystatus ) ) {
				$this->invoice_number = $invoice_number;
			}
		}

		$recurring = null;
		if ( !empty( $this->invoice_number ) ) {
			if ( $this->loadInvoice( $option ) === false ) {
				$recurring = $this->createInvoice( $storenew );
			}
		} else {
			$recurring = $this->createInvoice( $storenew );
		}

		if ( is_null( $recurring ) ) {
			$recurring = aecGetParam( 'recurring', null );
		}

		if ( isset( $this->userMIParams ) ) {
			if ( empty( $this->invoice->params['userMIParams'] ) ) {
				$this->invoice->params['userMIParams'] = array();
			}

			foreach ( $this->userMIParams as $planid => $mis ) {
				foreach ( $mis as $miid => $content ) {
					foreach ( $content as $k => $v ) {
						$this->invoice->params['userMIParams'][$planid][$miid][$k] = $v;
					}
				}
			}

			$this->invoice->storeload();
		}

		if ( isset( $this->invoice->params['userselect_recurring'] ) ) {
			$this->recurring = $this->invoice->params['userselect_recurring'];
		} elseif ( !is_null( $recurring ) ) {
			$this->invoice->addParams( array( 'userselect_recurring' => $recurring ) );
			$this->invoice->storeload();
		}

		return true;
	}

	function loadInvoice( $option, $redirect=true )
	{
		if ( !isset( $this->invoice ) ) {
			$this->invoice = null;
		}

		if ( !is_object( $this->invoice ) ) {
			$db = &JFactory::getDBO();
			$this->invoice = new Invoice( $db );
		}

		if ( $this->invoice->invoice_number != $this->invoice_number ) {
			$this->invoice->loadInvoiceNumber( $this->invoice_number );

			if ( empty( $this->invoice->id ) ) {
				return false;
			}
		}

		if ( !empty( $this->invoice->usage ) ) {
			$this->usage = $this->invoice->usage;
		}

		$this->invoice->computeAmount( $this, empty( $this->invoice->id ) );

		if ( !empty( $this->invoice->method ) && empty( $this->processor_userselect ) ) {
			$this->processor = $this->invoice->method;
		}

		if ( !$redirect ) {
			return true;
		}

		if ( empty( $this->usage ) && empty( $this->invoice->conditions ) && empty( $this->invoice->amount ) ) {
			return $this->create( $option, 0, 0, $this->invoice_number );
		} elseif ( empty( $this->processor ) && ( strpos( $this->usage, 'c' ) === false ) ) {
			return $this->create( $option, 0, $this->usage, $this->invoice_number );
		}

		return true;
	}

	function createInvoice( $storenew=false )
	{
		$db = &JFactory::getDBO();

		$this->invoice = new Invoice( $db );

		$id = 0;
		if ( !empty( $this->usage ) && strpos( $this->usage, 'c' ) !== false ) {
			$id = aecCartHelper::getInvoiceIdByCart( $this->cartobject );
		}

		$recurring = false;

		if ( $id ) {
			$this->invoice->load( $id );
		} else {
			if ( strpos( $this->processor, '_recurring' ) !== false ) {
				$processor = str_replace( '_recurring', '', $this->processor );
				$recurring = true;
			} else {
				$processor = $this->processor;
				$recurring = null;
			}

			$this->invoice->create( $this->userid, $this->usage, $processor, null, $storenew, null, $recurring );

			if ( $storenew ) {
				$this->storeInvoice();
			}
		}

		// Reset parameters
		if ( !empty( $this->invoice->method ) ) {
			$this->processor = $this->invoice->method;
		}

		if ( !empty( $this->invoice->usage ) ) {
			$this->usage = $this->invoice->usage;
		}

		return $recurring;
	}

	function InvoiceAddCoupon( $coupon )
	{
		if ( !empty( $coupon ) ) {
			$this->invoice->addCoupon( $coupon );

			$this->invoice->computeAmount( $this, true );
		}
	}

	function storeInvoice()
	{
		$db = &JFactory::getDBO();

		$this->invoice->computeAmount( $this, true );

		if ( is_object( $this->pp ) ) {
			$this->pp->invoiceCreationAction( $this->invoice );
		}

		$exchange = $add = $silent = null;

		$this->triggerMIs( 'invoice_creation', $exchange, $add, $silent );

		// Delete TempToken - the data is now safe with the invoice
		$temptoken = new aecTempToken( $db );
		$temptoken->getComposite();

		if ( $temptoken->id ) {
			$temptoken->delete();
		}
	}

	function triggerMIs( $action, &$exchange, &$add, &$silent )
	{
		if ( !empty( $this->cart ) && !empty( $this->cartobject ) ) {
			$this->cartobject->triggerMIs( $action, $this->metaUser, $exchange, $this->invoice, $add, $silent );
		} elseif ( !empty( $this->plan ) ) {
			$this->plan->triggerMIs( $action, $this->metaUser, $exchange, $this->invoice, $add, $silent );
		}
	}

	function loadMetaUser( $force=false )
	{
		if ( isset( $this->metaUser ) ) {
			if ( is_object( $this->metaUser ) && !$force ) {
				if ( !isset( $this->metaUser->_incomplete ) ) {
					return true;
				}
			}
		}

		if ( empty( $this->userid ) ) {
			// Creating a dummy user object
			$this->metaUser = new metaUser( 0 );
			$this->metaUser->dummyUser( $this->passthrough );

			return false;
		} else {
			// Loading the actual user
			$this->metaUser = new metaUser( $this->userid );
			return true;
		}
	}

	function checkAuth( $option )
	{
		$return = true;

		$this->loadMetaUser();

		// Add in task in case this is not set in passthrough
		if ( !isset( $this->passthrough['task'] ) ) {
			$this->passthrough['task'] = 'subscribe';
		}

		// Add in userid in case this is not set in passthrough
		if ( !isset( $this->passthrough['userid'] ) ) {
			$this->passthrough['userid'] = $this->userid;
		}

		if ( empty( $this->authed ) ) {
			if ( !$this->metaUser->getTempAuth() ) {
				if ( isset( $this->passthrough['password'] ) ) {
					if ( !$this->metaUser->setTempAuth( $this->passthrough['password'] ) ) {
						unset( $this->passthrough['password'] );
						$this->promptpassword( $option, true );
						$return = false;
					}
				} elseif ( !empty( $this->metaUser->cmsUser->password ) ) {
					$this->promptpassword( $option );
					$return = false;
				}
			}
		}

		return $return;
	}

	function promptpassword( $option, $wrong=false )
	{
		getView( 'passwordprompt', array( 'passthrough' => $this->getPassthrough(), 'wrong' => $wrong ) );
	}

	function create( $option, $intro=0, $usage=0, $group=0, $processor=null, $invoice=0, $autoselect=false )
	{
		$db = &JFactory::getDBO();

		global $aecConfig;

		$register = !$this->loadMetaUser( true );

		if ( !$register ) {
			if ( $this->metaUser->hasSubscription ) {
				$this->expired = $this->metaUser->objSubscription->is_expired();
			} else {
				$this->expired = false;
			}
		} else {
			$this->expired = true;
		}

		if ( empty( $this->usage ) && empty( $group ) ) {
			// Check if the user has already subscribed once, if not - link to intro
			if ( $this->metaUser->hasSubscription && !$aecConfig->cfg['customintro_always'] ) {
				$intro = false;
			}

			if ( !$intro && !empty( $aecConfig->cfg['customintro'] ) ) {
				if ( !empty( $aecConfig->cfg['customintro_userid'] ) ) {
					aecRedirect( $aecConfig->cfg['customintro'], $this->userid, "aechidden" );
				} else {
					aecRedirect( $aecConfig->cfg['customintro'] );
				}
			}
		}

		$recurring = aecGetParam( 'recurring', null );

		if ( !is_null( $recurring ) ) {
			$this->recurring = $recurring;
		} else {
			$this->recurring = null;
		}

		$list = $this->getPlanList( $usage, $group );

		$this->checkListProblems( $list );

		$list = $this->explodePlanList( $list );

		$nochoice = false;

		$passthrough = $this->getPassthrough();

		// There is no choice if we have only one group or only one item with one payment option
		if ( count( $list ) === 1 ) {
			if ( $list[0]['type'] == 'item' ) {
				if ( count( $list[0]['gw'] ) === 1 ) {
					$nochoice = true;
				}
			} else {
				// Jump back and use the only group we've found
				return $this->create( $option, $intro, 0, $list[0]['id'], null, 0, true );
			}
		}

		// If we have only one processor on one plan, there is no need for a decision
		if ( $nochoice && !( $aecConfig->cfg['show_fixeddecision'] && empty( $processor ) ) ) {
			// If the user also needs to register, we need to guide him there after the selection has now been made
			if ( $register ) {
				$this->registerRedirect( $option, $intro, $list[0] );
			} else {
				// Existing user account - so we need to move on to the confirmation page with the details
				$this->usage		= $list[0]['id'];

				if ( isset( $list[0]['gw'][0]->recurring ) ) {
					$this->recurring	= $list[0]['gw'][0]->recurring;
				} else {
					$this->recurring	= 0;
				}

				$this->processor	= $list[0]['gw'][0]->processor_name;

				if ( ( $invoice != 0 ) && !is_null( $invoice ) ) {
					$this->invoice_number	= $invoice;
				}

				$this->confirm( $option );
			}
		} else {
			// Reset $register if we seem to have all data
			if ( $register && !empty( $this->passthrough['username'] ) ) {
				$register = 0;
			}

			if ( $group ) {
				$g = new ItemGroup( $db );
				$g->load( $group );

				$list['group'] = ItemGroupHandler::getGroupListItem( $g );
			}

			if ( $this->userid ) {
				$cart = aecCartHelper::getCartidbyUserid( $this->userid );
			} else {
				$cart = false;
			}

			if ( ( !empty( $group ) || !empty( $usage ) ) && !$autoselect ) {
				$selected = true;
			} else {
				$selected = false;
			}

			if ( !$selected && !empty( $list['group'] ) ) {
				unset( $list['group'] );
			}

			$csslist = array();
			foreach ( $list as $li => $lv ) {
				if ( $lv['type'] == 'group' ) {
					continue;
				}

				foreach ( $lv['gw'] as $gwid => $pp ) {
					$btnarray = array();

					if ( strtolower( $pp->processor_name ) == 'add_to_cart' ) {
						$btnarray['option']		= 'com_acctexp';
						$btnarray['task']		= 'addtocart';
						$btnarray['class']		= 'btn btn-processor';
						$btnarray['content']	= '<i class="icon-plus narrow"></i>' . JText::_('AEC_BTN_ADD_TO_CART');

						$btnarray['usage'] = $lv['id'];

						if ( $aecConfig->cfg['additem_stayonpage'] ) {
							$btnarray['returngroup'] = $group;
						}
					} else {
						$btnarray['view'] = '';

						if ( $register ) {
							if ( GeneralInfoRequester::detect_component( 'anyCB' ) ) {
								$btnarray['option']	= 'com_comprofiler';
								$btnarray['task']	= 'registers';
							} elseif ( GeneralInfoRequester::detect_component( 'JOMSOCIAL' ) ) {
								$btnarray['option']	= 'com_community';
								$btnarray['view'] 	= 'register';
							} else {
								if ( defined( 'JPATH_MANIFESTS' ) ) {
									$btnarray['option']	= 'com_users';
									$btnarray['task']	= '';
									$btnarray['view'] 	= 'registration';
								} else {
									$btnarray['option']	= 'com_user';
									$btnarray['task']	= '';
									$btnarray['view'] 	= 'register';
								}
							}
						} else {
							$btnarray['option']		= 'com_acctexp';
							$btnarray['task']		= 'confirm';
						}

						$btnarray['class'] = 'btn btn-processor';

						if ( $pp->processor_name == 'free' ) {
							$btnarray['content'] = JText::_('AEC_PAYM_METHOD_FREE');
						} elseif( is_object($pp->processor) ) {
							if ( $pp->processor->getLogoFilename() == '' ) {
								$btnarray['content'] = '<span class="btn-tallcontent">'.$pp->info['longname'].'</span>';
							} else {
								if ( !array_key_exists($pp->processor_name, $csslist) ) {
									$csslist[$pp->processor_name] = '.btn-processor-' . $pp->processor_name
																	. ' { background-image: url(' . $pp->getLogoPath() .  ') !important; }';
								}
							}
						}

						if ( !empty( $pp->settings['generic_buttons'] ) ) {
							if ( !empty( $pp->recurring ) ) {
								$btnarray['content'] = JText::_('AEC_PAYM_METHOD_SUBSCRIBE');
							} else {
								$btnarray['content'] = JText::_('AEC_PAYM_METHOD_BUYNOW');
							}
						} else {
							$btnarray['class'] .= ' btn-processor-'.$pp->processor_name;

							if ( ( isset( $pp->recurring ) || isset( $pp->info['recurring'] ) ) && !empty( $pp->info['recurring'] ) ) {
								if ( $pp->info['recurring'] == 2 ) {
									if ( !empty( $pp->recurring ) ) {
										$btnarray['content'] = '<i class="btn-overlay">' . JText::_('AEC_PAYM_METHOD_RECURRING_BILLING') . '</i>';
									} else {
										$btnarray['content'] = '<i class="btn-overlay">' . JText::_('AEC_PAYM_METHOD_ONE_TIME_BILLING') . '</i>';
									}
								} elseif ( $pp->info['recurring'] == 1 ) {
									$btnarray['content'] = '<i class="btn-overlay">' . JText::_('AEC_PAYM_METHOD_RECURRING_BILLING') . '</i>';
								}
							}
						}

						if ( !empty( $pp->recurring ) ) {
							$btnarray['recurring'] = 1;
						} else {
							$btnarray['recurring'] = 0;
						}

						$btnarray['processor'] = $pp->processor_name;

						$btnarray['usage'] = $lv['id'];
					}

					$btnarray['userid'] = ( $this->userid ? $this->userid : 0 );

					// Rewrite Passthrough
					if ( !empty( $passthrough ) ) {
						$btnarray['aec_passthrough'] = $passthrough;
					}

					$list[$li]['gw'][$gwid]->btn = $btnarray;
				}
			}

			getView( 'plans', array(	'userid' => $this->userid, 'list' => $list, 'passthrough' => $this->getPassthrough(), 'register' => $register,
										'cart' => $cart, 'selected' => $selected, 'group' => $group, 'csslist' => $csslist ) );
		}
	}

	function getPlanList( $usage, $group )
	{
		global $aecConfig;

		$db = &JFactory::getDBO();

		$auth_problem = null;

		if ( !empty( $usage ) ) {
			$query = 'SELECT `id`'
					. ' FROM #__acctexp_plans'
					. ' WHERE `id` = \'' . $usage . '\' AND `active` = \'1\''
					;
			$db->setQuery( $query );
			$id = $db->loadResult();

			if ( $id ) {
				$plan = new SubscriptionPlan( $db );
				$plan->load( $id );

				$authorized = $plan->checkAuthorized( $this->metaUser );

				if ( $authorized === true ) {
					$list[] = ItemGroupHandler::getItemListItem( $plan );
				} elseif ( $authorized === false ) {
					$auth_problem = true;
				} else {
					$auth_problem = $authorized;
				}
			} else {
				// Plan does not exist
				$auth_problem = true;
			}
		} else {
			if ( !empty( $group ) ) {
				$gid = $group;
			} else {
				if ( !empty( $aecConfig->cfg['root_group_rw'] ) ) {
					$gid = AECToolbox::rewriteEngine( $aecConfig->cfg['root_group_rw'], $this->metaUser );
				} else {
					$gid = array( $aecConfig->cfg['root_group'] );
				}
			}

			if ( is_array( $gid ) ) {
				$gid = $gid[0];
			}

			$g = new ItemGroup( $db );
			$g->load( $gid );

			if ( $g->checkPermission( $this->metaUser ) ) {
				if ( !empty( $g->params['symlink_userid'] ) && !empty( $g->params['symlink'] ) ) {
					aecRedirect( $g->params['symlink'], $this->userid, "aechidden" );
				} elseif ( !empty( $g->params['symlink'] ) ) {
					return $g->params['symlink'];
				}

				$list = ItemGroupHandler::getTotalAllowedChildItems( array( $gid ), $this->metaUser );

				if ( count( $list ) == 0 ) {
					$auth_problem = true;
				}
			} else {
				$auth_problem = true;
			}

			if ( $auth_problem && !empty( $g->params['notauth_redirect'] ) ) {
				$auth_problem = $g->params['notauth_redirect'];
			}
		}

		if ( !is_null( $auth_problem ) ) {
			return $auth_problem;
		} else {
			return $list;
		}
	}

	function checkListProblems( $list )
	{
		// If we run into an Authorization problem, or no plans are available, redirect.
		if ( !is_array( $list ) ) {
			if ( $list ) {
				if ( is_bool( $list ) ) {
					return aecRedirect( AECToolbox::deadsureURL( 'index.php', false, true ), JText::_('NOPLANS_AUTHERROR') );
				} else {
					if ( strpos( $list, 'option=com_acctexp' ) ) {
						$list .= '&userid=' . $this->userid;
					}

					return aecRedirect( $list );
				}
			} else {
				return aecRedirect( AECToolbox::deadsureURL( 'index.php', false, true ), JText::_('NOPLANS_ERROR') );
			}
		}

		// After filtering out the processors, no plan or group can be used, so we have to again issue an error
		 if ( count( $list ) == 0 ) {
			return aecRedirect( AECToolbox::deadsureURL( 'index.php', false, true ), JText::_('NOPLANS_ERROR') );
		}
	}

	function explodePlanList( $list )
	{
		global $aecConfig;

		$groups	= array();
		$plans	= array();

		$gs = array();
		$ps = array();
		// Break apart groups and items, make sure we have no duplicates
		foreach ( $list as $litem ) {
			if ( $litem['type'] == 'group' ) {
				if ( !in_array( $litem['id'], $gs ) ) {
					$gs[] = $litem['id'];
					$groups[] = $litem;
				}
			} else {
				if ( !in_array( $litem['id'], $ps ) ) {

					if ( ItemGroupHandler::checkParentRestrictions( $litem['plan'], 'item', $this->metaUser ) ) {
						$ps[] = $litem['id'];
						$plans[] = $litem;
					}
				}
			}
		}

		foreach ( $plans as $pid => $plan ) {
			if ( !isset( $plan['plan']->params['cart_behavior'] ) ) {
				$plan['plan']->params['cart_behavior'] = 0;
			}

			if ( $this->userid && !$this->expired && ( $aecConfig->cfg['enable_shoppingcart'] || ( $plan['plan']->params['cart_behavior'] == 1 ) ) && ( $plan['plan']->params['cart_behavior'] != 2 ) ) {
				// We have a shopping cart situation, care about processors later

				if ( ( $plan['plan']->params['processors'] == '' ) || is_null( $plan['plan']->params['processors'] ) ) {
					if ( !$plan['plan']->params['full_free'] ) {
						continue;
					}
				}

				$plans[$pid]['gw'][0]						= new stdClass();
				$plans[$pid]['gw'][0]->processor_name		= 'add_to_cart';
				$plans[$pid]['gw'][0]->info['statement']	= '';
				$plans[$pid]['gw'][0]->recurring			= 0;

				continue;
			}

			if ( $plan['plan']->params['full_free'] ) {
				$plans[$pid]['gw'][0]						= new stdClass();
				$plans[$pid]['gw'][0]->processor_name		= 'free';
				$plans[$pid]['gw'][0]->info['statement']	= '';
				$plans[$pid]['gw'][0]->recurring			= 0;
			} else {
				if ( ( $plan['plan']->params['processors'] != '' ) && !is_null( $plan['plan']->params['processors'] ) ) {
					$processors = $plan['plan']->params['processors'];

					// Restrict to pre-chosen processor (if set)
					if ( !empty( $this->processor ) ) {
						$processorid = PaymentProcessorHandler::getProcessorIdfromName( $this->processor );
						if ( in_array( $processorid, $processors ) ) {
							$processors = array( $processorid );
						}
					}

					$plan_gw = array();
					if ( count( $processors ) ) {
						foreach ( $processors as $n ) {
							if ( empty( $n ) ) {
								continue;
							}

							$pp = new PaymentProcessor();

							if ( !$pp->loadId( $n ) ) {
								continue;
							}

							if ( !$pp->processor->active ) {
								continue;
							}

							$pp->init();
							$pp->getInfo();

							$pp->exchangeSettingsByPlan( $plan['plan'] );

							$recurring = $pp->is_recurring( $this->recurring, true );

							if ( $recurring > 1 ) {
								$pp->recurring = 0;
								$plan_gw[] = $pp;

								if ( !$plan['plan']->params['lifetime'] ) {
									$pp = new PaymentProcessor();

									$pp->loadId( $n );
									$pp->init();
									$pp->getInfo();
									$pp->exchangeSettingsByPlan( $plan['plan'] );

									$pp->recurring = 1;
									$plan_gw[] = $pp;
								}
							} elseif ( !( $plan['plan']->params['lifetime'] && $recurring ) ) {
								if ( is_int( $recurring ) ) {
									$pp->recurring	= $recurring;
								}
								$plan_gw[] = $pp;
							}
						}
					}

					if ( !empty( $plan_gw ) ) {
						$plans[$pid]['gw'] = $plan_gw;
					} else {
						unset( $plans[$pid] );
					}
				}
			}
		}

		return array_merge( $groups, $plans );
	}

	function registerRedirect( $option, $intro, $plan )
	{
		$_POST['intro'] = $intro;

		// The plans are supposed to be first, so the details form should hold the values
		if ( !empty( $plan['id'] ) ) {
			$_POST['usage']		= $plan['id'];
			$_POST['processor']	= $plan['gw'][0]->processor_name;

			if ( isset( $plan['gw'][0]->recurring ) ) {
				$_POST['recurring']	= $plan['gw'][0]->recurring;
			}
		}

		// Send to registration handler
		if ( GeneralInfoRequester::detect_component( 'anyCB' ) ) {
			$this->registerRedirectCB( $option, $plan );
		} elseif ( GeneralInfoRequester::detect_component( 'JUSER' ) ) {
			$this->registerRedirectJUser( $option );
		} elseif ( GeneralInfoRequester::detect_component( 'JOMSOCIAL' ) ) {
			$this->registerRedirectJomSocial( $option, $plan );
		} else {
			$this->registerRedirectJoomla( $option, $intro, $plan );
		}
	}

	function registerRedirectJoomla( $option, $intro, $plan )
	{
		$app = JFactory::getApplication();

		if ( isset( $plan['gw'][0]->recurring ) ) {
			$recurring = $plan['gw'][0]->recurring;
		} else {
			$recurring = 0;
		}

		if ( defined( 'JPATH_MANIFESTS' ) ) {
			$app->redirect( 'index.php?option=com_users&view=registration&usage=' . $plan['id'] . '&processor=' . $plan['gw'][0]->processor_name . '&recurring=' . $recurring );
		} else {
			$app->redirect( 'index.php?option=com_user&view=register&usage=' . $plan['id'] . '&processor=' . $plan['gw'][0]->processor_name . '&recurring=' . $recurring );
		}
	}

	function registerRedirectCB( $option, $plan )
	{
		if ( GeneralInfoRequester::detect_component( 'CB1.2' ) ) {
			$this->TempTokenFromPlan( $plan );

			if ( !empty( $_GET['fname'] ) ) {
				setcookie( "fname", $_GET['fname'], ( (int) gmdate('U') )+60*10 );
			}

			if ( !empty( $_GET['femail'] ) ) {
				setcookie( "femail", $_GET['femail'], ( (int) gmdate('U') )+60*10 );
			}

			aecRedirect( 'index.php?option=com_comprofiler&task=registers' );
		} else {
			global $task, $_PLUGINS, $ueConfig, $_CB_database;;

			$app = JFactory::getApplication();

			$savetask	= $task;
			$_REQUEST['task'] = 'done';

			include_once( JPATH_SITE . '/components/com_comprofiler/comprofiler.php' );
			include_once( JPATH_SITE . '/components/com_comprofiler/comprofiler.html.php' );

			$task = $savetask;

			registerForm( $option, $app->getCfg( 'emailpass' ), null );
		}
	}

	function registerRedirectJUser( $option )
	{
		global $task;

		$savetask	= $task;
		$task		= 'blind';

		include_once( JPATH_SITE . '/components/com_juser/juser.html.php' );
		include_once( JPATH_SITE . '/components/com_juser/juser.php' );

		$task = $savetask;

		userRegistration( $option, null );
	}

	function registerRedirectJomSocial( $option, $plan )
	{
		$this->TempTokenFromPlan( $plan );

		aecRedirect( 'index.php?option=com_community&view=register' );
	}

	function TempTokenFromPlan( $plan )
	{
		$db = &JFactory::getDBO();

		$temptoken = new aecTempToken( $db );
		$temptoken->getComposite();

		if ( empty( $temptoken->content ) ) {
			$content = array();
			$content['usage']		= $plan['id'];
			$content['processor']	= $plan['gw'][0]->processor_name;
			if ( isset( $plan['gw'][0]->recurring ) ) {
				$content['recurring']	= $plan['gw'][0]->recurring;
			}

			$temptoken->create( $content );
		} elseif ( empty( $temptoken->content['usage'] ) || ( $temptoken->content['usage'] !== $plan['id'] ) ) {
			$temptoken->content['usage']		= $plan['id'];
			$temptoken->content['processor']	= $plan['gw'][0]->processor_name;
			if ( isset( $plan['gw'][0]->recurring ) ) {
				$temptoken->content['recurring']	= $plan['gw'][0]->recurring;
			}

			$temptoken->storeload();
		}
	}

	function confirm( $option )
	{
		global $aecConfig;

		if ( empty( $this->passthrough ) ) {
			if ( !$this->checkAuth( $option ) ) {
				return false;
			}
		}

		$this->reCaptchaCheck();

		$this->puffer( $option );

		$this->coupons = array();
		$this->coupons['active'] = $aecConfig->cfg['enable_coupons'];

		if ( empty( $this->mi_error ) ) {
			$this->mi_error = array();
		}

		if ( !empty( $this->plan ) ) {
			$this->mi_form = $this->plan->getMIforms( $this->metaUser, $this->mi_error );
		}

		if ( !empty( $this->mi_form ) && is_array( $this->passthrough ) ) {
			$params = $this->plan->getMIformParams( $this->metaUser );

			foreach ( $params as $mik => $miv ) {
				if ( $mik == 'lists' ) {
					continue;
				}

				foreach ( $this->passthrough as $pid => $pk ) {
					if ( !is_array( $pk ) ) {
						continue;
					}

					if ( ( $pk[0] == $mik ) || ( $pk[0] == $mik.'[]' ) ) {
						unset($this->passthrough[$pid]);
					}
				}
			}
		}

		if ( !( $aecConfig->cfg['skip_confirmation'] && empty( $this->mi_form ) ) ) {
			$this->userdetails = "";

			if ( !empty( $this->metaUser->cmsUser->name ) ) {
				$this->userdetails .= '<p>' . JText::_('CONFIRM_ROW_NAME') . "&nbsp;" . $this->metaUser->cmsUser->name . '</p>';
			}

			$this->userdetails .= '<p>' . JText::_('CONFIRM_ROW_USERNAME') . "&nbsp;" . $this->metaUser->cmsUser->username . '</p>';
			$this->userdetails .= '<p>' . JText::_('CONFIRM_ROW_EMAIL') . "&nbsp;" . $this->metaUser->cmsUser->email . '</p>';

			getView( 'confirmation', array( 'InvoiceFactory' => $this, 'passthrough' => $this->getPassthrough() ) );
		} else {
			$this->getPassthrough();

			$this->save( $option );
		}
	}

	function cart( $option )
	{
		global $aecConfig;

		$this->getCart();

		$this->coupons = array( 'active' => $aecConfig->cfg['enable_coupons'] );

		$in = AECfetchfromDB::InvoiceNumberbyCartId( $this->userid, $this->cartobject->id );

		if ( !empty( $in ) ) {
			$this->invoice_number = $in;

			$this->touchInvoice( $option );
		}

		getView( 'cart', array( 'InvoiceFactory' => $this ) );
	}

	function confirmcart( $option, $coupon=null, $testmi=false )
	{
		global $task;

		$app = JFactory::getApplication();

		$this->confirmed = 1;

		$this->loadMetaUser( false, true );

		$this->metaUser->setTempAuth();

		$this->puffer( $option );

		$this->touchInvoice( $option );

		if ( $this->hasExceptions() ) {
			return $this->addressExceptions( $option );
		} else {
			$this->checkout( $option, 0, null, $coupon );
		}
	}

	function save( $option, $coupon=null )
	{
		$this->confirmed = 1;

		$this->loadPlanObject( $option );

		if ( empty( $this->userid ) ) {
			$this->saveUserRegistration( $option );
		}

		$this->loadMetaUser( true );
		$this->metaUser->setTempAuth();

		if ( !empty( $this->plan ) ) {
			if ( $this->verifyMIForms( $this->plan ) === false ) {
				$this->confirmed = 0;
				return $this->confirm( $option );
			}
		} elseif ( !empty( $this->cart ) ) {
			$check = true;
			foreach( $this->cart as $ci ) {
				if ( $this->verifyMIForms( $ci['obj'] ) === false ) {
					$check = false;
				}
			}

			if ( !$check ) {
				$this->confirmed = 0;
				return $this->confirm( $option );
			}
		}

		$this->checkout( $option, 0, null, $coupon );
	}

	function saveUserRegistration( $option )
	{
		if ( !empty( $this->plan ) ) {
			if ( !isset( $this->plan->params['override_activation'] ) ) {
				$this->plan->params['override_activation'] = false;
			}

			if ( !isset( $this->plan->params['override_regmail'] ) ) {
				$this->plan->params['override_regmail'] = false;
			}

			$this->userid = AECToolbox::saveUserRegistration( $option, $this->passthrough, false, $this->plan->params['override_activation'], $this->plan->params['override_regmail'] );
		} else {
			$this->userid = AECToolbox::saveUserRegistration( $option, $this->passthrough );
		}
	}

	function verifyMIForms( $plan, $mi_form=null, $prefix="" )
	{
		if ( empty( $plan ) ) {
			return null;
		} elseif ( !is_object( $plan ) ) {
			return null;
		}

		if ( empty( $mi_form ) ) {
			$mi_form = $plan->getMIformParams( $this->metaUser );
		}

		if ( !empty( $mi_form ) ) {
			$params = array();
			foreach ( $mi_form as $key => $value ) {
				if ( strpos( $key, '[]' ) ) {
					$key = str_replace( '[]', '', $key );
				}

				$value = aecGetParam( $prefix.$key, '__DEL' );

				if ( !empty( $prefix ) ) {
					if ( strpos( $key, $prefix ) !== false ) {
						$key = str_replace( $prefix, '', $key );
					}
				}

				if ( $value !== '__DEL' ) {
					$k = explode( '_', $key, 3 );

					if ( !isset( $params[$k[1]] ) ) {
						$params[$k[1]] = array();
					}

					$params[$k[1]][$k[2]] = $value;
				}
			}

			if ( !empty( $params ) ) {
				foreach ( $params as $mi_id => $content ) {
					if ( is_object( $this->invoice ) ) {
						$this->invoice->params['userMIParams'][$plan->id][$mi_id] = $content;
					} else {
						$this->userMIParams[$plan->id][$mi_id] = $content;
					}
				}

				if ( is_object( $this->invoice ) ) {
					$userMIParams = $this->invoice->params['userMIParams'];

					$this->invoice->storeload();
				} else {
					$userMIParams = $this->userMIParams;
				}
			} elseif ( !empty( $this->invoice->params['userMIParams'] ) ) {
				$userMIParams = $this->invoice->params['userMIParams'];
			}

			if ( empty( $userMIParams ) ) {
				$userMIParams = array();
			}

			$verifymi = $plan->verifyMIformParams( $this->metaUser, $userMIParams );

			$this->mi_error = array();
			if ( is_array( $verifymi ) && !empty( $verifymi ) ) {
				foreach ( $verifymi as $vmi ) {
					if ( !is_array( $vmi ) ) {
						continue;
					}

					if ( !empty( $vmi['error'] ) ) {
						$this->mi_error[$vmi['id']] = $vmi['error'];
					}
				}
			}

			if ( !empty( $this->mi_error ) ) {
				$this->confirmed = 0;
				return false;
			}
		}

		return true;
	}

	function checkout( $option, $repeat=0, $error=null, $coupon=null )
	{
		global $aecConfig;

		if ( !$this->checkAuth( $option ) ) {
			return false;
		}

		$this->puffer( $option );

		$this->touchInvoice( $option, false, true );

		if ( $this->invoice->method != $this->processor ) {
			$this->invoice->method = $this->processor;

			$this->invoice->storeload();
		}

		if ( !empty( $coupon ) ) {
			$this->InvoiceAddCoupon( $coupon );
		}

		$user_ident	= aecGetParam( 'user_ident', 0, true, array( 'string', 'clear_nonemail' ) );

		if ( !empty( $user_ident ) && !empty( $this->invoice->id ) ) {
			if ( $this->invoice->addTargetUser( strtolower( $user_ident ) ) ) {
				$this->invoice->storeload();
			}
		}

		$repeat = empty( $repeat ) ? 0 : $repeat;

		$exceptproc = array( 'none', 'free' );

		if ( !in_array( strtolower( $this->processor ), $exceptproc ) ) {
			if ( isset( $this->invoice->params['userselect_recurring'] ) ) {
				$recurring_choice = $this->invoice->params['userselect_recurring'];
			} else {
				$recurring_choice = null;
			}

			$recurring = $this->pp->is_recurring( $recurring_choice );
		} else {
			$recurring = false;
		}

		// If this is marked as supposedly free
		if ( in_array( strtolower( $this->processor ), $exceptproc ) && !empty( $this->plan ) ) {
			// Double Check Amount for made_free
			$this->invoice->computeAmount( $this );

			if (
				// If it is either made free through coupons
				!empty( $this->invoice->made_free )
				// Or a free full period that the user CAN use and no trial
				|| ( $this->plan->params['full_free'] && empty( $this->invoice->counter ) && empty( $this->plan->params['trial_period'] ) )
				// Or a free full period that the user CAN use and a skipped trial
				|| ( $this->plan->params['full_free'] && $this->invoice->counter )
				// Or a free trial that the user CAN use
				|| ( $this->plan->params['trial_free'] && empty( $this->invoice->counter ) )
			) {
				// Only allow clearing while recurring if everything is free
				if ( !( $recurring && ( empty( $this->plan->params['full_free'] ) || empty( $this->plan->params['trial_free'] ) ) ) ) {
					// mark paid
					if ( $this->invoice->pay() !== false ) {
						return $this->thanks( $option, false, true );
					}
				}
			}

			return getView( 'access_denied' );
		} elseif ( in_array( strtolower( $this->processor ), $exceptproc ) ) {
			if ( !empty( $this->invoice->made_free ) ) {
				// mark paid
				if ( $this->invoice->pay() !== false ) {
					return $this->thanks( $option, false, true );
				}
			}

			return getView( 'access_denied' );
		} elseif ( strcmp( strtolower( $this->processor ), 'error' ) === 0 ) {
			// Nope, won't work buddy
			return getView( 'access_denied' );
		}

		if ( !empty( $this->pp->info['secure'] ) && empty( $_SERVER['HTTPS'] ) && !$aecConfig->cfg['override_reqssl'] ) {
			aecRedirect( AECToolbox::deadsureURL( "index.php?option=" . $option . "&task=repeatPayment&invoice=" . $this->invoice->invoice_number . "&first=" . ( $repeat ? 0 : 1 ) . '&'. JUtility::getToken() .'=1', true, true ) );
			exit();
		}

		$this->loadItems();

		$this->loadItemTotal();

		$exchange = $silent = null;

		$this->triggerMIs( 'invoice_items_checkout', $exchange, $this->items, $silent );

		// Either this is fully free, or the next term is free and this is non recurring
		if ( !empty( $this->items->grand_total ) && !$recurring ) {
			if ( $this->items->grand_total->isFree() && !$recurring ) {
				$this->invoice->pay();

				return $this->thanks( $option, false, true );
			}
		}

		$this->InvoiceToCheckout( $option, $repeat, $error );
	}

	function InvoiceToCheckout( $option, $repeat=0, $error=null, $data=null )
	{
		global $aecConfig;

		if ( $this->hasExceptions() ) {
			return $this->addressExceptions( $option );
		}

		if ( !empty( $data ) ) {
			$int_var = $data;
		} else {
			$int_var = $this->invoice->getWorkingData( $this );
		}

		// Assemble Checkout Response
		if ( !empty( $int_var['objUsage'] ) ) {
			if ( is_a( $int_var['objUsage'], 'SubscriptionPlan' ) ) {
				$int_var['var']		= $this->pp->checkoutAction( $int_var, $this->metaUser, $int_var['objUsage'], $this );
			} else {
				$int_var['var']		= $this->pp->checkoutAction( $int_var, $this->metaUser, null, $this, $int_var['objUsage'] );
			}
		} else {
			$int_var['var']		= $this->pp->checkoutAction( $int_var, $this->metaUser, null, $this, $int_var['objUsage'] );
		}

		$int_var['params']	= $this->pp->getParamsHTML( $int_var['params'], $this->pp->getParams( $int_var['params'] ) );

		$this->invoice->formatInvoiceNumber();

		$introtext = JText::_('CHECKOUT_INFO'. ( $repeat ? '_REPEAT' : '' ));

		$this->checkout = array();
		$this->checkout['checkout_title']					= JText::_('CHECKOUT_TITLE');
		$this->checkout['introtext']						= sprintf( $introtext, $this->invoice->invoice_number );
		
		if ( isset( $aecConfig->cfg['checkout_coupons'] ) ) {
			$this->checkout['enable_coupons']					= $aecConfig->cfg['enable_coupons'] ? $aecConfig->cfg['checkout_coupons'] : false;
		} else {
			$this->checkout['enable_coupons']					= $aecConfig->cfg['enable_coupons'];
		}

		$this->checkout['customtext_checkout_table']		= JText::_('CHECKOUT_TITLE');

		$this->display_error = $error;

		if ( is_object( $this->pp ) ) {
			$this->pp->modifyCheckout( $int_var, $this );
		}

		$exchange = $silent = null;

		$this->triggerMIs( '_checkout_form', $exchange, $int_var, $silent );

		getView( 'checkout', array( 'var' => $int_var['var'], 'params' => $int_var['params'], 'InvoiceFactory' => $this ) );
	}

	function getObjUsage()
	{
		if ( isset( $this->invoice->usage ) ) {
			return $this->invoice->getObjUsage();
		} elseif ( !empty( $this->usage ) ) {
			$db = &JFactory::getDBO();

			$u = explode( '.', $this->usage );

			switch ( strtolower( $u[0] ) ) {
				case 'c':
				case 'cart':
					$objUsage = new aecCart( $db );
					$objUsage->load( $u[1] );
					break;
				case 'p':
				case 'plan':
				default:
					if ( !isset( $u[1] ) ) {
						$u[1] = $u[0];
					}

					$objUsage = new SubscriptionPlan( $db );
					$objUsage->load( $u[1] );
					break;
			}

			return $objUsage;
		} else {
			return null;
		}
	}

	function internalcheckout( $option )
	{
		$this->metaUser = new metaUser( $this->userid );

		$this->touchInvoice( $option );

		$this->puffer( $option );

		$objUsage = $this->getObjUsage();

		if ( is_a( $objUsage, 'SubscriptionPlan' ) ) {
			$new_subscription = $objUsage;
		} else {
			$new_subscription = $objUsage->getTopPlan();
		}

		$badbadvars = array( 'userid', 'invoice', 'task', 'option' );
		foreach ( $badbadvars as $badvar ) {
			if ( isset( $_POST[$badvar] ) ) {
				unset( $_POST[$badvar] );
			}
		}

		$this->loadItems();

		$this->loadItemTotal();

		$var = $this->invoice->getWorkingData( $this );

		$post = aecPostParamClear( $_POST );

		foreach ( $post as $pk => $pv ) {
			$var['params'][$pk] = $pv;
		}

		if ( !empty( $this->invoice->params['target_user'] ) ) {
			$targetUser = new metaUser( $this->invoice->params['target_user'] );
		} else {
			$targetUser =& $this->metaUser;
		}

		if ( !empty( $this->cartobject ) && !empty( $this->cart ) ) {
			$response = $this->pp->checkoutProcess( $var, $targetUser, $new_subscription, $this, $this->cart );
		} else {
			$response = $this->pp->checkoutProcess( $var, $targetUser, $new_subscription, $this );
		}

		if ( isset( $response['error'] ) ) {
			unset( $this->cart );
			unset( $this->cartobject );
			unset( $this->items );
			unset( $this->pp );

			$this->checkout( $option, true, $response['error'] );
		} elseif ( isset( $response['doublecheckout'] ) ) {
			$exchange = $silent = null;

			$this->triggerMIs( 'invoice_items_checkout', $exchange, $this->items, $silent );

			$this->InvoiceToCheckout( $option, true, null, $var );
		} else {
			$this->thanks( $option );
		}
	}

	function processorResponse( $option, $response )
	{
		$this->touchInvoice( $option );

		$this->userid = $this->invoice->userid;
		$this->loadMetaUser();

		// Provide MI Params so they're correct for invoice modifications
		if ( is_object( $this->metaUser ) ) {
			if ( !empty( $this->invoice->params['userMIParams'] ) ) {
				foreach ( $this->invoice->params['userMIParams'] as $plan => $mis ) {
					foreach ( $mis as $mi_id => $content ) {
						$this->metaUser->meta->setMIParams( $mi_id, $plan, $content );
					}
				}
			}
		}

		$this->puffer( $option );

		$this->loadItems();

		$this->loadItemTotal();

		$response = $this->invoice->processorResponse( $this, $response, '', false );

		if ( isset( $response['error'] ) ) {
			if ( !empty( $this->pp->info['custom_notify_trail'] ) ) {
				$this->pp->notify_trail( $this, $response );
			} else {
				unset( $this->cart );
				unset( $this->cartobject );
				unset( $this->items );
				unset( $this->pp );

				$this->checkout( $option, true, $response['error'] );
			}
		} elseif ( isset( $response['customthanks'] ) ) {
			if ( !empty( $response['customthanks_strict'] ) ) {
				echo $response['customthanks'];
				exit;
			} else {
				getView( 'thanks', array( 'customthanks' => $response['customthanks'] ) );
			}
		} else {
			if ( !empty( $this->pp->info['notify_trail_thanks'] ) ) {
				$this->thanks( $option );
			} elseif ( !empty( $this->pp->info['custom_notify_trail'] ) ) {
				$this->pp->notify_trail( $this, $response );
			} else {
				header("HTTP/1.0 200 OK");
				exit;
			}
		}
	}

	function planprocessoraction( $action, $subscr=null )
	{
		$db = &JFactory::getDBO();

		$this->loadMetaUser();

		$this->invoice = new Invoice( $db );

		if ( !empty( $subscr ) ) {
			if ( $this->metaUser->moveFocus( $subscr ) ) {
				$this->invoice->loadLatest( $this->metaUser->userid, $this->metaUser->focusSubscription->plan, $this->metaUser->focusSubscription->id );
			}
		} else {
			$this->invoice->loadLatest( $this->metaUser->userid, $this->metaUser->focusSubscription->plan );
		}

		if ( empty( $this->usage ) ) {
			$this->usage = $this->invoice->usage;
		}

		if ( empty( $this->processor ) ) {
			$this->processor = $this->invoice->method;
		}

		$this->puffer( 'com_acctexp' );

		$this->loadItems();

		$this->loadItemTotal();

		if ( $this->pp->id ) {
			$this->pp->fullInit();

			$usage = $this->getObjUsage();

			if ( is_a( $usage, 'aecCart' ) ) {
				foreach ( $usage->content as $c ) {
					$new_plan = new SubscriptionPlan( $db );
					$new_plan->load( $c['id'] );

					$this->pp->exchangeSettingsByPlan( $new_plan );
				}
			} elseif ( is_a( $usage, 'SubscriptionPlan' ) ) {
				$this->pp->exchangeSettingsByPlan( $usage );
			} else {
				return aecNotAuth();
			}

			$response = $this->pp->customAction( $action, $this->invoice, $this->metaUser );

			$response = $this->invoice->processorResponse( $this, $response, '', true );

			if ( isset( $response['cancel'] ) ) {
				getView( 'cancel' );
			}
		} else {
			return aecNotAuth();
		}
	}

	function invoiceprocessoraction( $option, $action, $invoiceNum=null )
	{
		$db = &JFactory::getDBO();

		$this->loadMetaUser();

		$this->puffer( $option );

		$this->loadItems();

		$this->loadItemTotal();

		$var = $this->invoice->getWorkingData( $this );

		$response = $this->pp->customAction( $action, $this->invoice, $this->metaUser, $var );

		if ( isset( $response['InvoiceToCheckout'] ) ) {
			$this->InvoiceToCheckout( 'com_acctexp', true, false );
		} else {
			$response = $this->invoice->processorResponse( $this, $response, '', true );

			if ( isset( $response['cancel'] ) ) {
				getView( 'cancel' );
			}
		}
	}

	function invoiceprint( $option, $invoice_number, $standalone=true, $extradata=null, $forcecleared=false, $forcecounter=null )
	{
		$this->loadMetaUser();

		$this->touchInvoice( $option, $invoice_number, false, true );

		if ( $this->invoice->invoice_number != $invoice_number ) {
			return aecNotAuth();
		}

		$this->puffer( $option );

		$this->loadItems();

		$this->loadItemTotal();

		$this->invoice->formatInvoiceNumber();

		$data = $this->invoice->getPrintout( $this, $forcecleared, $forcecounter );

		$data['standalone'] = $standalone;

		$exchange = $silent = null;

		if ( !empty( $extradata ) ) {
			foreach ( $extradata as $k => $v ) {
				$data[$k] = $v;
			}
		}

		$this->triggerMIs( 'invoice_printout', $exchange, $data, $silent );

		getView( 'invoice', array( 'data' => $data, 'standalone' => $standalone, 'InvoiceFactory' => $this ) );
	}

	function thanks( $option, $renew=false, $free=false )
	{
		global $aecConfig;

		$app = JFactory::getApplication();

		if ( $this->userid ) {
			$this->loadMetaUser();

			if ( isset( $this->renew ) ) {
				$renew = $this->renew;
			} else {
				$renew = $this->metaUser->is_renewing();
			}
		}

		if ( !empty( $this->plan ) ) {
			getView( 'thanks', array( 'renew' => $renew, 'free' => $free, 'plan' => $this->plan ) );
		} else {
			getView( 'thanks', array( 'renew' => $renew, 'free' => $free ) );
		}
	}

	function error( $option, $objUser, $invoice, $error )
	{
		$document=& JFactory::getDocument();

		$document->setTitle( html_entity_decode( JText::_('CHECKOUT_ERROR_TITLE'), ENT_COMPAT, 'UTF-8' ) );

		getView( 'error', array( 'objUser' => $objUser, 'invoice' => $invoice, 'error' => $error ) );
	}

	function reCaptchaCheck()
	{
		global $aecConfig;

		if ( $aecConfig->cfg['use_recaptcha'] && !empty( $aecConfig->cfg['recaptcha_privatekey'] ) && empty( $this->userid ) ) {
			// require the recaptcha library
			require_once( JPATH_SITE . '/components/com_acctexp/lib/recaptcha/recaptchalib.php' );

			if ( !isset( $_POST["recaptcha_challenge_field"] ) || !isset( $_POST["recaptcha_response_field"] ) ) {
				echo "<script> alert('The reCAPTCHA was not correct. Please try again.'); window.history.go(-1);</script>\n";

				return;
			}

			// finally chack with reCAPTCHA if the entry was correct
			$resp = recaptcha_check_answer ( $aecConfig->cfg['recaptcha_privatekey'], $_SERVER["REMOTE_ADDR"], $_POST["recaptcha_challenge_field"], $_POST["recaptcha_response_field"] );

			// if the response is INvalid, then go back one page, and try again. Give a nice message
			if (!$resp->is_valid) {
				echo "<script> alert('The reCAPTCHA was not correct. Please try again.'); window.history.go(-1);</script>\n";

				return;
			}
		}
	}
}

class Invoice extends serialParamDBTable
{
	/** @var int Primary key */
	var $id					= null;
	/** @var int */
	var $active 			= null;
	/** @var int */
	var $counter 			= null;
	/** @var int */
	var $userid 			= null;
	/** @var int */
	var $subscr_id 			= null;
	/** @var string */
	var $invoice_number 	= null;
	/** @var string */
	var $invoice_number_format 	= null;
	/** @var string */
	var $secondary_ident 	= null;
	/** @var datetime */
	var $created_date	 	= null;
	/** @var datetime */
	var $transaction_date 	= null;
	/** @var string */
	var $method 			= null;
	/** @var string */
	var $amount 			= null;
	/** @var string */
	var $currency 			= null;
	/** @var string */
	var $usage				= null;
	/** @var int */
	var $fixed	 			= null;
	/** @var text */
	var $coupons 			= null;
	/** @var text */
	var $transactions		= null;
	/** @var text */
	var $params 			= null;
	/** @var text */
	var $conditions			= null;

	function Invoice(&$db)
	{
		parent::__construct( '#__acctexp_invoices', 'id', $db );
	}

	function load( $id )
	{
		parent::load( $id );

		if ( empty( $this->counter ) && ( $this->transaction_date != '0000-00-00 00:00:00' ) && !is_null( $this->transaction_date ) ) {
			$this->counter = 1;
		}
	}

	function loadLatest( $userid, $plan, $subscr=null )
	{
		if ( !empty( $subscr ) ) {
			$this->loadbySubscriptionId( $subscr, $userid );
		}

		if ( empty( $this->id ) ) {
			$this->load( AECfetchfromDB::lastClearedInvoiceIDbyUserID( $userid, $plan ) );
		}

		if ( empty( $this->id ) ) {
			$this->load( AECfetchfromDB::lastUnclearedInvoiceIDbyUserID( $userid, $plan ) );
		}
	}

	function declareParamFields()
	{
		return array( 'coupons', 'transactions', 'params', 'conditions' );
	}

	function check()
	{
		$unset = array( 'made_free' );

		foreach ( $unset as $varname ) {
			if ( isset( $this->$varname ) ) {
				unset( $this->$varname );
			}
		}

		parent::check();

		return true;
	}

	function loadInvoiceNumber( $invoiceNum )
	{
		$db = &JFactory::getDBO();

		$query = 'SELECT id'
		. ' FROM #__acctexp_invoices'
		. ' WHERE invoice_number = \'' . $invoiceNum . '\''
		. ' OR secondary_ident = \'' . $invoiceNum . '\''
		;
		$db->setQuery( $query );
		$this->load($db->loadResult());
	}

	function formatInvoiceNumber( $invoice=null, $nostore=false )
	{
		global $aecConfig;

		if ( empty( $invoice ) ) {
			$subject = $this;
		} else {
			$subject = $invoice;
		}

		$invoice_number = $subject->invoice_number;

		if ( empty( $subject->invoice_number_format ) && $aecConfig->cfg['invoicenum_doformat'] ) {
			$invoice_number = AECToolbox::rewriteEngine( $aecConfig->cfg['invoicenum_formatting'], null, null, $subject );
		} elseif ( !empty( $subject->invoice_number_format ) ) {
			$invoice_number = $subject->invoice_number_format;
		}

		if ( empty( $invoice ) ) {
			if ( $aecConfig->cfg['invoicenum_doformat'] && empty( $this->invoice_number_format ) && !empty( $invoice_number ) && !$nostore ) {
				if ( $invoice_number != "JSON PARSE ERROR - Malformed String!" ) {
					$this->invoice_number_format = $invoice_number;
					$this->storeload();
				}
			}

			$this->invoice_number = $invoice_number;
			return true;
		} else {
			return $invoice_number;
		}

	}

	function deformatInvoiceNumber()
	{
		$db = &JFactory::getDBO();

		global $aecConfig;

		$query = 'SELECT invoice_number'
		. ' FROM #__acctexp_invoices'
		. ' WHERE id = \'' . $db->getEscaped( $this->id ) . '\''
		. ' OR secondary_ident = \'' . $db->getEscaped( $this->invoice_number ) . '\''
		;
		$db->setQuery( $query );

		$this->invoice_number = $db->loadResult();
	}

	function loadbySubscriptionId( $subscrid, $userid=null )
	{
		$db = &JFactory::getDBO();

		$query = 'SELECT `id`'
				. ' FROM #__acctexp_invoices'
				. ' WHERE `subscr_id` = \'' . $subscrid . '\''
				;

		if ( !empty( $userid ) ) {
			$query .= ' AND `userid` = \'' . $userid . '\'';
		}

		$query .= ' ORDER BY `transaction_date` DESC';

		$db->setQuery( $query );
		$this->load( $db->loadResult() );
	}

	function hasDuplicate( $userid, $invoiceNum )
	{
		$db = &JFactory::getDBO();

		$query = 'SELECT count(*)'
				. ' FROM #__acctexp_invoices'
				. ' WHERE `userid` = ' . (int) $userid
				. ' AND `invoice_number` = \'' . $invoiceNum . '\''
				;
		$db->setQuery( $query );
		return $db->loadResult();
	}

	function isRecurring()
	{
		if ( !empty( $this->subscr_id ) ) {
			$db = &JFactory::getDBO();

			$query = 'SELECT `recurring`'
					. ' FROM #__acctexp_subscr'
					. ' WHERE `id` = \'' . $this->subscr_id . '\''
					;

			$db->setQuery( $query );
			return $db->loadResult();
		}

		if ( isset( $this->params['userselect_recurring'] ) ) {
			$recurring_choice = $this->params['userselect_recurring'];
		} else {
			$recurring_choice = null;
		}

		if ( ( $this->method !== 'none' ) && ( $this->method !== 'free' ) ) {
			$pp = new PaymentProcessor();
			if ( $pp->loadName( $this->method ) ) {
				$pp->fullInit();

				return $pp->is_recurring( $recurring_choice );
			}
		}

		return false;
	}

	function computeAmount( $InvoiceFactory=null, $save=true, $recurring_choice=null )
	{
		$db = &JFactory::getDBO();

		if ( !empty( $InvoiceFactory->metaUser ) ) {
			$metaUser = $InvoiceFactory->metaUser;
		} else {
			$metaUser = new metaUser( $this->userid ? $this->userid : 0 );
		}

		$pp = null;

		$madefree = false;

		if ( is_null( $recurring_choice ) && isset( $this->params['userselect_recurring'] ) ) {
			$recurring_choice = $this->params['userselect_recurring'];
		}

		if ( !is_null( $this->usage ) && !( $this->usage == '' ) ) {
			$recurring = 0;

			$original_amount = $this->amount;

			if ( !empty( $this->method ) ) {
				switch ( $this->method ) {
					case 'none':
					case 'free':
						break;
					default:
						$pp = new PaymentProcessor();
						if ( $pp->loadName( $this->method ) ) {
							$pp->fullInit();

							if ( $pp->is_recurring( $recurring_choice ) ) {
								$recurring = $pp->is_recurring( $recurring_choice );
							}

							if ( empty( $this->currency ) ) {
								$this->currency = isset( $pp->settings['currency'] ) ? $pp->settings['currency'] : '';
							}
						} else {
							$short	= 'processor loading failure';
							$event	= 'When computing invoice amount, tried to load processor: ' . $this->method;
							$tags	= 'processor,loading,error';
							$params = array();

							$eventlog = new eventLog( $db );
							$eventlog->issue( $short, $tags, $event, 128, $params );

							return;
						}
				}
			}

			$usage = explode( '.', $this->usage );

			// Update old notation
			if ( !isset( $usage[1] ) ) {
				$temp = $usage[0];
				$usage[0] = 'p';
				$usage[1] = $temp;
			}

			$allfree = false;

			switch ( strtolower( $usage[0] ) ) {
				case 'c':
				case 'cart':
					$cart = $this->getObjUsage();

					if ( $cart->id ) {
						if ( !empty( $this->coupons ) ) {
							foreach ( $this->coupons as $coupon ) {
								if ( !$cart->hasCoupon( $coupon ) ) {
									$cart->addCoupon( $coupon );
								}
							}
						}

						$return = $cart->getAmount( $metaUser, $this->counter, $this );

						$allfree = $cart->checkAllFree( $metaUser, $this->counter, $this );

						$this->amount = $return;
					} elseif ( isset( $this->params['cart'] ) ) {
						// Cart has been deleted, use copied data
						$vars = get_object_vars( $this->params['cart'] );
						foreach ( $vars as $v => $c ) {
							// Make extra sure we don't put through any _properties
							if ( strpos( $v, '_' ) !== 0 ) {
								$cart->$v = $c;
							}
						}

						$return = $cart->getAmount( $metaUser, $this->counter, $this );

						$this->amount = $return;
					} else {
						$this->amount = '0.00';
					}
					break;
				case 'p':
				case 'plan':
				default:
					$plan = $this->getObjUsage();

					if ( is_object( $pp ) ) {
						$pp->exchangeSettingsByPlan( $plan );

						if ( $pp->is_recurring( $recurring_choice ) ) {
							$recurring = $pp->is_recurring( $recurring_choice );
						} else {
							$recurring = 0;
						}
					}

					$terms = $plan->getTermsForUser( $recurring, $metaUser );

					$terms->incrementPointer( $this->counter );

					$item = array( 'item' => array( 'obj' => $plan ), 'terms' => $terms );

					if ( $this->coupons ) {
						$cpsh = new couponsHandler( $metaUser, $InvoiceFactory, $this->coupons );

						$item = $cpsh->applyAllToItems( 0, $item );

						$terms = $item['terms'];
					}

					// Coupons might have changed the terms - reset pointer
					$terms->setPointer( $this->counter );

					$allfree = $terms->checkFree();

					if ( is_object( $terms->nextterm ) ) {
						$this->amount = $terms->nextterm->renderTotal();
					} else {
						$this->amount = '0.00';
					}
				break;
			}

			$this->amount = AECToolbox::correctAmount( $this->amount );

			if ( !$recurring || $allfree ) {
				if ( ( strcmp( $this->amount, '0.00' ) === 0 ) ) {
					$this->method = 'free';
					$madefree = true;
				} elseif ( ( strcmp( $this->amount, '0.00' ) === 0 ) && ( strcmp( $this->method, 'free' ) !== 0 ) ) {
					$short	= 'invoice amount error';
					$event	= 'When computing invoice amount: Method error, amount 0.00, but method = ' . $this->method;
					$tags	= 'processor,loading,error';
					$params = array();

					$eventlog = new eventLog( $db );
					$eventlog->issue( $short, $tags, $event, 128, $params );

					$this->method = 'error';
				}
			}

			if ( $save ) {
				$this->storeload();
			}

			if ( $madefree ) {
				$this->made_free = true;
			}
		}
	}

	function create( $userid, $usage, $processor, $second_ident=null, $store=true, $InvoiceFactory=null, $recurring_choice=null )
	{
		if ( !$userid ) {
			return false;
		}

		$app = JFactory::getApplication();

		$invoice_number			= $this->generateInvoiceNumber();

		$this->load(0);
		$this->invoice_number	= $invoice_number;

		if ( !is_null( $second_ident ) ) {
			$this->secondary_ident		= $second_ident;
		}

		$this->active			= 1;
		$this->fixed			= 0;
		$this->created_date		= date( 'Y-m-d H:i:s', ( (int) gmdate('U') ) );
		$this->transaction_date	= '0000-00-00 00:00:00';
		$this->userid			= $userid;
		$this->method			= $processor;
		$this->usage			= $usage;

		$this->params = array( 'creator_ip' => $_SERVER['REMOTE_ADDR'] );

		if ( !is_null( $recurring_choice ) ) {
			$this->params['userselect_recurring'] = $recurring_choice;
		}

		$this->computeAmount( $InvoiceFactory, $store, $recurring_choice );

		return true;
	}

	function generateInvoiceNumber( $maxlength = 16 )
	{
		$db = &JFactory::getDBO();

		$numberofrows	= 1;
		while ( $numberofrows ) {
			$inum =	'I' . substr( base64_encode( md5( rand() ) ), 0, $maxlength );
			// Check if already exists
			$query = 'SELECT count(*)'
					. ' FROM #__acctexp_invoices'
					. ' WHERE `invoice_number` = \'' . $inum . '\''
					. ' OR `secondary_ident` = \'' . $inum . '\''
					;
			$db->setQuery( $query );
			$numberofrows = $db->loadResult();
		}
		return $inum;
	}

	function processorResponse( $InvoiceFactory, $response, $resp='', $altvalidation=false )
	{
		global $aecConfig;

		$db = &JFactory::getDBO();

		if ( !is_array( $response ) ) {
			$response = array( 'original_response' => $response );
		}

		$db = &JFactory::getDBO();

		$this->amount = $InvoiceFactory->items->grand_total->cost['amount'];

		$objUsage = $this->getObjUsage();

		if ( is_a( $objUsage, 'SubscriptionPlan' ) ) {
			$plan = $objUsage;
		} else {
			$plan = $objUsage->getTopPlan();
		}

		$post = aecPostParamClear( $_POST );
		$post['planparams'] = $plan->getProcessorParameters( $InvoiceFactory->pp );

		$response['userid'] = $this->userid;

		$InvoiceFactory->pp->exchangeSettingsByPlan( $plan, $plan->params );

		if ( $altvalidation ) {
			$response = $InvoiceFactory->pp->instantvalidateNotification( $response, $post, $this );
		} else {
			$response = $InvoiceFactory->pp->validateNotification( $response, $post, $this );
		}

		if ( !empty( $aecConfig->cfg['invoice_cushion'] ) && ( $this->transaction_date !== '0000-00-00 00:00:00' ) ) {
			$app = JFactory::getApplication();aecDebug($response);

			if ( ( strtotime( $this->transaction_date ) + ( $aecConfig->cfg['invoice_cushion']*60 ) ) > ( (int) gmdate('U') ) ) {
				if ( $InvoiceFactory->pp->processor_name == 'desjardins' ) {
					// Desjardins is the only exception so far... bad bad bad
				} elseif ( $response['valid'] ) {
					// The last notification has not been too long ago - skipping this one
					// But only skip actual payment notifications - errors are OK

					$short = JText::_('AEC_MSG_PROC_INVOICE_ACTION_SH');
					$event = JText::_('AEC_MSG_PROC_INVOICE_ACTION_EV_DUPLICATE') . "\n";

					$tags	= 'invoice,processor,duplicate';
					$level	= 2;
					$params = array( 'invoice_number' => $this->invoice_number );

					$eventlog = new eventLog( $db );
					$eventlog->issue( $short, $tags, $event, $level, $params );

					return $response;
				}
			}aecDebug("after cushion!");
		}

		if ( isset( $response['userid'] ) ) {
			unset( $response['userid'] );
		}

		if ( isset( $response['invoiceparams'] ) ) {
			$this->addParams( $response['invoiceparams'] );
			$this->storeload();
			unset( $response['invoiceparams'] );
		}

		if ( isset( $response['multiplicator'] ) ) {
			$multiplicator = $response['multiplicator'];
			unset( $response['multiplicator'] );
		} else {
			$multiplicator = 1;
		}

		if ( isset( $response['fullresponse'] ) ) {
			$resp = $response['fullresponse'];
			unset( $response['fullresponse'] );
		}

		if ( empty( $resp ) && !empty( $response['raw'] ) ) {
			$resp = $response['raw'];
		}

		if ( isset( $response['break_processing'] ) ) {
			unset( $response['break_processing'] );

			return $response;
		}

		$metaUser = new metaUser( $this->userid );

		$mi_event = null;

		// Create history entry
		$history = new logHistory( $db );
		$history->entryFromInvoice( $this, $resp, $InvoiceFactory->pp );

		$short = JText::_('AEC_MSG_PROC_INVOICE_ACTION_SH');
		$event = JText::_('AEC_MSG_PROC_INVOICE_ACTION_EV') . "\n";

		if ( !empty( $response ) ) {
			foreach ( $response as $key => $value ) {
				$event .= $key . "=" . $value . "\n";
			}
		}

		$event	.= JText::_('AEC_MSG_PROC_INVOICE_ACTION_EV_STATUS');
		$tags	= 'invoice,processor';
		$level	= 2;
		$params = array( 'invoice_number' => $this->invoice_number );

		$forcedisplay = false;

		$event .= ' ';

		$notificationerror = null;

		if ( $response['valid'] ) {
			$break = 0;

			// If not in Testmode, check for amount and currency
			if ( empty( $InvoiceFactory->pp->settings['testmode'] ) ) {
				if ( isset( $response['amount_paid'] ) ) {
					if ( $response['amount_paid'] != $this->amount ) {
						// Amount Fraud, cancel payment and create error log addition
						$event	.= sprintf( JText::_('AEC_MSG_PROC_INVOICE_ACTION_EV_FRAUD'), $response['amount_paid'], $this->amount );
						$tags	.= ',fraud_attempt,amount_fraud';
						$break	= 1;

						$notificationerror = 'Wrong amount for invoice. Amount provided: "' . $response['amount_paid'] . '"';
					}
				}

				if ( isset( $response['amount_currency'] ) ) {
					if ( $response['amount_currency'] != $this->currency ) {
						// Amount Fraud, cancel payment and create error log addition
						$event	.= sprintf( JText::_('AEC_MSG_PROC_INVOICE_ACTION_EV_CURR'), $response['amount_currency'], $this->currency );
						$tags	.= ',fraud_attempt,currency_fraud';
						$break	= 1;

						$notificationerror = 'Wrong currency for invoice. Currency provided: "' . $response['amount_currency'] . '"';
					}
				}
			}

			if ( !$break ) {
				if ( $this->pay( $multiplicator ) === false ) {
					$notificationerror = 'Item Application failed. Please contact the System Administrator';

					// Something went wrong
					$event	.= JText::_('AEC_MSG_PROC_INVOICE_ACTION_EV_VALID_APPFAIL');
					$tags	.= ',payment,action_failed';
				} else {
					$event	.= JText::_('AEC_MSG_PROC_INVOICE_ACTION_EV_VALID');
					$tags	.= ',payment,action';
				}
			} else {
				$level = 128;
			}
		} else {
			if ( isset( $response['pending'] ) ) {
				if ( strcmp( $response['pending_reason'], 'signup' ) === 0 ) {
					if ( $plan->params['trial_free'] || ( $this->amount == '0.00' ) ) {
						$this->pay( $multiplicator );

						$this->addParams( array( 'free_trial' => $response['pending_reason'] ), 'params', true );

						$event	.= JText::_('AEC_MSG_PROC_INVOICE_ACTION_EV_TRIAL');
						$tags	.= ',payment,action,trial';
					}
				} else {
					$this->addParams( array( 'pending_reason' => $response['pending_reason'] ), 'params', true );
					$event	.= sprintf( JText::_('AEC_MSG_PROC_INVOICE_ACTION_EV_PEND'), $response['pending_reason'] );
					$tags	.= ',payment,pending' . $response['pending_reason'];

					$mi_event = '_payment_pending';
				}

				$this->storeload();
			} elseif ( isset( $response['cancel'] ) ) {
				$mi_event = '_payment_cancel';

				$event	.= JText::_('AEC_MSG_PROC_INVOICE_ACTION_EV_CANCEL');
				$tags	.= ',cancel';

				if ( $metaUser->hasSubscription ) {
					if ( !empty( $this->subscr_id ) ) {
						$metaUser->moveFocus( $this->subscr_id );
					}

					if ( isset( $response['cancel_expire'] ) ) {
						$mi_event = '_payment_cancel_expire';

						$metaUser->focusSubscription->expire();
						$tags	.= ',expire';
					} else {
						$metaUser->focusSubscription->cancel( $this );
					}

					$event .= JText::_('AEC_MSG_PROC_INVOICE_ACTION_EV_USTATUS');
				}
			} elseif ( isset( $response['chargeback'] ) ) {
				$mi_event = '_payment_chargeback';

				$event	.= JText::_('AEC_MSG_PROC_INVOICE_ACTION_EV_CHARGEBACK');
				$tags	.= ',chargeback';
				$level = 128;

				if ( $metaUser->hasSubscription ) {
					if ( !empty( $this->subscr_id ) ) {
						$metaUser->moveFocus( $this->subscr_id );
					}

					$metaUser->focusSubscription->hold( $this );

					$event .= JText::_('AEC_MSG_PROC_INVOICE_ACTION_EV_USTATUS_HOLD');
				}
			} elseif ( isset( $response['chargeback_settle'] ) ) {
				$mi_event = '_payment_chargeback_settle';

				$event	.= JText::_('AEC_MSG_PROC_INVOICE_ACTION_EV_CHARGEBACK_SETTLE');
				$tags	.= ',chargeback_settle';
				$level = 8;
				$forcedisplay = true;

				if ( $metaUser->hasSubscription ) {
					if ( !empty( $this->subscr_id ) ) {
						$metaUser->moveFocus( $this->subscr_id );
					}

					$metaUser->focusSubscription->hold_settle( $this );

					$event .= JText::_('AEC_MSG_PROC_INVOICE_ACTION_EV_USTATUS_ACTIVE');
				}
			} elseif ( isset( $response['delete'] ) ) {
				$mi_event = '_payment_refund';

				$event	.= JText::_('AEC_MSG_PROC_INVOICE_ACTION_EV_REFUND');
				$tags	.= ',refund';
				if ( $metaUser->hasSubscription ) {
					if ( !empty( $this->subscr_id ) ) {
						$metaUser->moveFocus( $this->subscr_id );
					}

					$usage = $this->getObjUsage();

					if ( is_a( $usage, 'SubscriptionPlan' ) ) {
						// Check whether we're really expiring the right membership,
						// Maybe the user was already switched to a different plan
						if ( $metaUser->focusSubscription->plan == $usage->id ) {
							$metaUser->focusSubscription->expire( false, 'refund' );
							$event .= JText::_('AEC_MSG_PROC_INVOICE_ACTION_EV_EXPIRED');
						}
					} else {
						$metaUser->focusSubscription->expire( false, 'refund' );
						$event .= JText::_('AEC_MSG_PROC_INVOICE_ACTION_EV_EXPIRED');
					}
				}
			} elseif ( isset( $response['eot'] ) ) {
				$mi_event = '_payment_eot';

				$event	.= JText::_('AEC_MSG_PROC_INVOICE_ACTION_EV_EOT');
				$tags	.= ',eot';
			} elseif ( isset( $response['duplicate'] ) ) {
				$mi_event = '_payment_duplicate';

				$event	.= JText::_('AEC_MSG_PROC_INVOICE_ACTION_EV_DUPLICATE');
				$tags	.= ',duplicate';
			} elseif ( isset( $response['null'] ) ) {
				$mi_event = '_payment_null';

				$event	.= JText::_('AEC_MSG_PROC_INVOICE_ACTION_EV_NULL');
				$tags	.= ',null';
			} elseif ( isset( $response['error'] ) && isset( $response['errormsg'] ) ) {
				$mi_event = '_payment_error';

				$event	.= JText::_('AEC_MSG_PROC_INVOICE_ACTION_EV_U_ERROR') . ' Error:' . $response['errormsg'] ;
				$tags	.= ',error';
				$level = 128;

				$notificationerror = $response['errormsg'];
			} else {
				$event	.= JText::_('AEC_MSG_PROC_INVOICE_ACTION_EV_U_ERROR');
				$tags	.= ',general_error';
				$level = 128;

				$notificationerror = 'General Error. Please contact the System Administrator.';
			}
		}

		if ( !empty( $mi_event ) && !empty( $this->usage ) ) {
			$objUsage = new SubscriptionPlan( $db );
			$objUsage->load( $this->usage );

			$exchange = $silent = null;

			$objUsage->triggerMIs( $mi_event, $metaUser, $exchange, $this, $response, $silent );
		}

		if ( isset( $response['explanation'] ) ) {
			$event .= " (" . $response['explanation'] . ")";
		}

		$eventlog = new eventLog( $db );
		$eventlog->issue( $short, $tags, $event, $level, $params, $forcedisplay );

		if ( !empty( $notificationerror ) ) {
			$InvoiceFactory->pp->notificationError( $response, $notificationerror );
		} else {
			$InvoiceFactory->pp->notificationSuccess( $response );
		}

		return $response;
	}

	function pay( $multiplicator=1, $noclear=false )
	{
		$db = &JFactory::getDBO();

		global $aecConfig;

		$metaUser	= false;
		$new_plan	= false;
		$plans		= array();

		if ( !empty( $this->userid ) ) {
			$metaUser = new metaUser( $this->userid );
		}

		if ( !empty( $this->params['target_user'] ) ) {
			$targetUser = new metaUser( $this->params['target_user'] );
		} else {
			$targetUser =& $metaUser;
		}

		if ( !empty( $this->params['aec_pickup'] ) ) {
			if ( is_array( $this->params['aec_pickup'] ) ) {
				foreach ( $this->params['aec_pickup'] as $key ) {
					if ( isset( $this->params[$key] ) ) {
						unset( $this->params[$key] );
					}
				}
			}

			unset( $this->params['aec_pickup'] );
		}

		$override_permissioncheck = $this->isRecurring() && ( $this->counter > 1 ); 

		if ( !empty( $this->usage ) ) {
			$usage = explode( '.', $this->usage );

			// Update old notation
			if ( !isset( $usage[1] ) ) {
				$temp = $usage[0];
				$usage[0] = 'p';
				$usage[1] = $temp;
			}

			switch ( strtolower( $usage[0] ) ) {
				case 'c':
				case 'cart':
					if ( empty( $this->params['cart']->content ) ) {
						$this->params['cart'] = new aecCart( $db );
						$this->params['cart']->load( $usage[1] );

						if ( !empty( $this->params['cart']->content ) ) {
							foreach ( $this->params['cart']->content as $c ) {
								$new_plan = new SubscriptionPlan( $db );
								$new_plan->load( $c['id'] );

								for ( $i=0; $i<$c['quantity']; $i++ ) {
									$plans[] = $new_plan;
								}
							}
						}

						$this->params['cart']->clear();

						$this->storeload();

						// Load and delete original entry
						$cart = new aecCart( $db );
						$cart->load( $usage[1] );
						if ( $cart->id ) {
							$cart->delete();
						}
					} else {
						foreach ( $this->params['cart']->content as $c ) {
							$new_plan = new SubscriptionPlan( $db );
							$new_plan->load( $c['id'] );

							if ( !$override_permissioncheck ) {
								if ( $new_plan->checkPermission( $metaUser ) === false ) {
									return false;
								}
							}

							for ( $i=0; $i<$c['quantity']; $i++ ) {
								$plans[] = $new_plan;
							}
						}
					}
					break;
				case 'p':
				case 'plan':
				default:
					$new_plan = new SubscriptionPlan( $db );
					$new_plan->load( $this->usage );

					if ( !$override_permissioncheck ) {
						if ( $new_plan->checkPermission( $metaUser ) === false ) {
							return false;
						}
					}

					$plans[] = $new_plan;
					break;
			}

		}

		if ( is_object( $metaUser ) ) {
			if ( !empty( $this->params['userMIParams'] ) ) {
				foreach ( $this->params['userMIParams'] as $plan => $mis ) {
					foreach ( $mis as $mi_id => $content ) {
						$metaUser->meta->setMIParams( $mi_id, $plan, $content );
					}
				}

				$metaUser->meta->storeload();

				unset( $this->params['userMIParams'] );
			}
		}

		foreach ( $plans as $plan ) {
			if ( is_object( $targetUser ) && is_object( $plan ) ) {
				if ( $targetUser->userid ) {
					if ( !empty( $this->subscr_id ) ) {
						$targetUser->establishFocus( $plan, $this->method, false, $this->subscr_id );
					} else {
						$targetUser->establishFocus( $plan, $this->method );
					}

					$this->subscr_id = $targetUser->focusSubscription->id;

					// Apply the Plan
					$application = $targetUser->focusSubscription->applyUsage( $plan->id, $this->method, 0, $multiplicator, $this );
				} else {
					$application = $plan->applyPlan( 0, $this->method, 0, $multiplicator, $this );
				}
			}
		}

		$micro_integrations = false;

		if ( !empty( $this->conditions ) ) {
			if ( strpos( $this->conditions, 'mi_attendevents' ) ) {
				$micro_integration['name'] = 'mi_attendevents';
				$micro_integration['parameters'] = array( 'registration_id' => $this->substring_between( $this->conditions, '<registration_id>', '</registration_id>' ) );

				$micro_integrations = array();
				$micro_integrations[] = $micro_integration;
			}
		}

		if ( !empty( $micro_integrations ) ) {
			if ( is_array( $micro_integrations ) ) {
				foreach ( $micro_integrations as $micro_int ) {
					$mi = new microIntegration( $db );

					if ( isset( $micro_integration['parameters'] ) ) {
						$exchange = $micro_integration['parameters'];
					} else {
						$exchange = null;
					}

					if ( isset( $micro_int['name'] ) ) {
						if ( $mi->callDry( $micro_int['name'] ) ) {
							if ( is_object( $metaUser ) ) {
								$mi->action( $metaUser, $exchange, $this, $new_plan );
							} else {
								$mi->action( false, $exchange, $this, $new_plan );
							}
						}
					} elseif ( isset( $micro_int['id'] ) ) {
						if ( $mi->mi_exists( $micro_int['id'] ) ) {
							$mi->load( $micro_int['id'] );
							if ( $mi->callIntegration() ) {
								if ( is_object( $metaUser ) ) {
									$mi->action( $metaUser, $exchange, $this, $new_plan );
								} else {
									$mi->action( false, $exchange, $this, $new_plan );
								}
							}
						}
					}

					unset( $mi );
				}
			}
		}

		if ( $this->coupons ) {
			foreach ( $this->coupons as $coupon_code ) {
				$cph = new couponHandler();
				$cph->load( $coupon_code );

				$cph->triggerMIs( $metaUser, $this, $metaUser );
			}
		}

		// We need to at least warn the admin if there is an invoice with nothing to do
		if ( empty( $this->usage ) && empty( $this->conditions ) && empty( $this->coupons ) ) {
			$short	= 'Nothing to do';
			$event	= JText::_('AEC_MSG_PROC_INVOICE_ACTION_EV_VALID_APPFAIL');
			$tags	= 'invoice,application,payment,action_failed';
			$params = array( 'invoice_number' => $this->invoice_number );

			$eventlog = new eventLog( $db );
			$eventlog->issue( $short, $tags, $event, 32, $params );
		}

		if ( !$noclear ) {
			$this->setTransactionDate();
		}			

		return true;
	}

	function cancel()
	{
		if ( $this->fixed ) {
			return false;
		}

		$db = &JFactory::getDBO();

		$this->active = 0;
		$this->params['deactivated'] = 'cancel';
		$this->storeload();

		$usage = null;
		if ( !empty( $this->usage ) ) {
			$usage = $this->usage;
		}

		if ( !empty( $usage ) ) {
			$u = explode( '.', $usage );

			switch ( strtolower( $u[0] ) ) {
				case 'c':
				case 'cart':
					// Delete Carts referenced in this Invoice as well
					$query = 'DELETE FROM #__acctexp_cart WHERE `id` = \'' . $u[1] . '\'';
					$db->setQuery( $query );
					$db->query();
					break;
			}
		}

		return true;
	}

	function substring_between( $haystack, $start, $end )
	{
		if ( strpos( $haystack, $start ) === false || strpos( $haystack, $end ) === false ) {
			return false;
		} else {
			$start_position = strpos( $haystack, $start ) + strlen( $start );
			$end_position = strpos( $haystack, $end );
			return substr( $haystack, $start_position, $end_position - $start_position );
		}
	}

	function setTransactionDate()
	{
		$db = &JFactory::getDBO();

		global $aecConfig;

		$app = JFactory::getApplication();

		$tdate				= strtotime( $this->transaction_date );
		$time_passed		= ( ( ( (int) gmdate('U') ) ) - $tdate ) / 3600;
		$transaction_date	= date( 'Y-m-d H:i:s', ( (int) gmdate('U') ) );

		if ( !empty( $aecConfig->cfg['invoicecushion'] ) ) {
			$cushion = $aecConfig->cfg['invoicecushion']*60;
		} else {
			$cushion = 0;
		}

		if ( $time_passed > $cushion ) {
			$this->counter += 1;
			$this->transaction_date	= $transaction_date;

			$c = new stdClass();

			$c->timestamp	= $transaction_date;
			$c->amount		= $this->amount;
			$c->currency	= $this->currency;
			$c->processor	= $this->method;

			$this->transactions[] = $c;

			$this->storeload();
		} else {
			return;
		}
	}

	function getWorkingData( $InvoiceFactory )
	{
		$db = &JFactory::getDBO();

		$int_var = array();

		// Defaults
		$int_var['params']		= array();
		$int_var['invoice']		= $this->invoice_number;
		$int_var['usage']		= $this->usage;
		$int_var['amount']		= $this->amount;

		if ( isset( $InvoiceFactory->recurring ) ) {
			$int_var['recurring']	= $InvoiceFactory->recurring;
		} else {
			$int_var['recurring']	= 0;
		}

		if ( is_array( $this->params ) ) {
			$int_var['params'] = $this->params;

			// Filter non-processor params
			$nonproc = array( 'pending_reason', 'deactivated' );

			foreach ( $nonproc as $param ) {
				if ( isset( $int_var['params'][$param] ) ) {
					unset( $int_var['params'][$param] );
				}
			}
		}

		$int_var['objUsage'] = $this->getObjUsage();

		$urladd = '';
		$doublecheck = false;
		if ( !empty( $int_var['objUsage'] ) ) {
			if ( !is_a( $int_var['objUsage'], 'SubscriptionPlan' ) ) {
				if ( !empty( $InvoiceFactory->items->itemlist ) ) {
					if ( count( $InvoiceFactory->items->itemlist ) === 1 ) {
						$int_var['objUsage'] = $InvoiceFactory->cart[0]['obj'];

						$doublecheck = true;
					}
				}
			}

			if ( is_a( $int_var['objUsage'], 'SubscriptionPlan' ) ) {
				if ( is_object( $InvoiceFactory->pp ) ) {
					$int_var['planparams'] = $int_var['objUsage']->getProcessorParameters( $InvoiceFactory->pp );

					if ( isset( $int_var['params']['userselect_recurring'] ) ) {
						$int_var['recurring'] = $InvoiceFactory->pp->is_recurring( $int_var['params']['userselect_recurring'], true );
					} else {
						$int_var['recurring'] = $InvoiceFactory->pp->is_recurring();
					}
				} else {
					$int_var['planparams'] = array();

					$int_var['recurring'] = false;
				}

				if ( !empty( $InvoiceFactory->items->itemlist ) ) {
					$max = array_pop( array_keys( $InvoiceFactory->items->itemlist ) );

					$terms = $InvoiceFactory->items->itemlist[$max]['terms'];
				} else {
					$terms = $int_var['objUsage']->getTermsForUser( $int_var['recurring'], $InvoiceFactory->metaUser );
				}

				$int_var['amount']		= $terms->getOldAmount( $int_var['recurring'] );

				if ( !empty( $int_var['objUsage']->params['customthanks'] ) || !empty( $int_var['objUsage']->params['customtext_thanks'] ) ) {
					$urladd = '&amp;u=' . $this->usage;
				}
			} else {
				if ( !empty( $InvoiceFactory->cart ) && !empty( $InvoiceFactory->cartobject ) ) {
					$int_var['objUsage'] = $InvoiceFactory->cartobject;
				}

				if ( is_object( $InvoiceFactory->items->grand_total ) ) {
					$int_var['amount'] = $InvoiceFactory->items->grand_total->renderCost();
				} else {
					$int_var['amount'] = $InvoiceFactory->items->grand_total;
				}
			}

			if ( $doublecheck ) {
				if ( $InvoiceFactory->cart[0]['quantity'] > 1 ) {
					if ( is_array( $int_var['amount'] ) ) {
						foreach ( $int_var['amount'] as $k => $v ) {
							if ( strpos( $k, 'amount' ) !== false ) {
								$int_var['amount'][$k] = AECToolbox::correctAmount( $v * $InvoiceFactory->cart[0]['quantity'] );
							}
						}
					} else {
						$int_var['amount'] = AECToolbox::correctAmount( $int_var['amount'] * $InvoiceFactory->cart[0]['quantity'] );
					}
				}
			} else {
				if ( is_array( $int_var['amount'] ) ) {
					foreach ( $int_var['amount'] as $k => $v ) {
						if ( strpos( $k, 'amount' ) !== false ) {
							$int_var['amount'][$k] = AECToolbox::correctAmount( $v );
						}
					}
				} else {
					$int_var['amount'] = AECToolbox::correctAmount( $int_var['amount'] );
				}
			}
		} else {
			$int_var['amount'] = $this->amount;
		}

		if ( is_object( $InvoiceFactory->metaUser ) ) {
			$renew = $InvoiceFactory->metaUser->is_renewing();
		} else {
			$renew = 0;
		}

		$int_var['return_url']	= AECToolbox::deadsureURL( 'index.php?option=com_acctexp&task=thanks&amp;renew=' . $renew . $urladd );

		return $int_var;
	}

	function getObjUsage()
	{
		$db = &JFactory::getDBO();

		$usage = null;
		if ( !empty( $this->usage ) ) {
			$usage = $this->usage;
		}

		if ( !empty( $usage ) ) {
			$u = explode( '.', $usage );

			switch ( strtolower( $u[0] ) ) {
				case 'c':
				case 'cart':
					if ( isset( $this->params['cart'] ) ) {
						$objUsage = $this->params['cart'];
					} else {
						$objUsage = new aecCart( $db );
						$objUsage->load( $u[1] );
					}
					break;
				case 'p':
				case 'plan':
				default:
					if ( !isset( $u[1] ) ) {
						$u[1] = $u[0];
					}

					$objUsage = new SubscriptionPlan( $db );
					$objUsage->load( $u[1] );
					break;
			}

			return $objUsage;
		} else {
			return null;
		}
	}

	function addTargetUser( $user_ident )
	{
		global $aecConfig;

		if ( !empty( $aecConfig->cfg['checkout_as_gift'] ) ) {
			if ( !empty( $aecConfig->cfg['checkout_as_gift_access'] ) ) {
				$metaUser = new metaUser( $this->userid );

				if ( !$metaUser->hasGroup( $aecConfig->cfg['checkout_as_gift_access'] ) ) {
					return false;
				}
			}
		} else {
			return false;
		}

		$queries = array();

		// Try username and name
		$queries[] = 'FROM #__users'
					. ' WHERE LOWER( `username` ) LIKE \'%' . $user_ident . '%\''
					;

		// If its not that, how about the user email?
		$queries[] = 'FROM #__users'
					. ' WHERE LOWER( `email` ) = \'' . $user_ident . '\''
					;

		// Try to find this as a userid
		$queries[] = 'FROM #__users'
					. ' WHERE `id` = \'' . $user_ident . '\''
					;

		// Try to find this as a full name
		$queries[] = 'FROM #__users'
					. ' WHERE LOWER( `name` ) LIKE \'%' . $user_ident . '%\''
					;

		$db = &JFactory::getDBO();

		foreach ( $queries as $base_query ) {
			$query = 'SELECT `id`, `username`, `email` ' . $base_query;
			$db->setQuery( $query );
			$res = $db->loadObject();

			if ( !empty( $res ) ) {
				$this->params['target_user'] = $res->id;
				$this->params['target_username'] = $user_ident;
				return true;
			}
		}

		return false;
	}

	function removeTargetUser()
	{
		if ( isset( $this->params['target_user'] ) ) {
			unset( $this->params['target_user'] );
			unset( $this->params['target_username'] );

			return true;
		} else {
			return null;
		}
	}

	function addCoupon( $couponcode )
	{
		if ( !empty( $this->coupons ) ) {
			if ( !is_array( $this->coupons ) ) {
				$oldcoupons = explode( ';', $this->coupons );
			} else {
				$oldcoupons = $this->coupons;
			}
		} else {
			$oldcoupons = array();
		}

		if ( !in_array( $couponcode, $oldcoupons ) ) {
			$oldcoupons[] = $couponcode;

			$cph = new couponHandler();
			$cph->load( $couponcode );

			if ( $cph->status ) {
				$cph->incrementCount( $this );
			}
		}

		$this->coupons = $oldcoupons;
	}

	function removeCoupon( $coupon_code )
	{
		$db = &JFactory::getDBO();

		$oldcoupons = $this->coupons;

		if ( !is_array( $oldcoupons ) ) {
			$oldcoupons = array();
		}

		if ( in_array( $coupon_code, $oldcoupons ) ) {
			foreach ( $oldcoupons as $id => $cc ) {
				if ( $cc == $coupon_code ) {
					unset( $oldcoupons[$id] );
				}
			}

			$cph = new couponHandler();
			$cph->load( $coupon_code );
			if ( !empty( $cph->coupon->id ) ) {
				$cph->decrementCount( $this );
			}

			if ( !empty( $this->usage ) ) {
				$usage = explode( '.', $this->usage );

				// Update old notation
				if ( !isset( $usage[1] ) ) {
					$temp = $usage[0];
					$usage[0] = 'p';
					$usage[1] = $temp;
				}

				switch ( strtolower( $usage[0] ) ) {
					case 'c':
					case 'cart':
						$cart = new aecCart( $db );
						$cart->load( $usage[1] );

						$cart->removeCoupon( $coupon_code );
						$cart->storeload();
						break;
				}

			}
		}

		$this->coupons = $oldcoupons;
	}

	function preparePickup( $array )
	{
		// Prevent double-saving of system parameters by bad integrations
		$exceptions = array( 'creator_ip', 'userselect_recurring' );

		foreach ( $exceptions as $key ) {
			if ( isset( $array[$key] ) ) {
				unset( $array[$key] );
			}
		}

		$this->addParams( array( 'aec_pickup' => array_keys( $array ) ) );

		$this->addParams( $array );

		$this->storeload();
	}

	function savePostParams( $array )
	{
		$delete = array( 'task', 'option', 'invoice' );

		foreach ( $delete as $key ) {
			if ( isset( $array[$key] ) ) {
				unset( $array[$key] );
			}
		}

		$this->addParams( $array );
		return true;
	}

	function getPrintout( $InvoiceFactory, $forcecleared=false, $forcecounter=null )
	{
		global $aecConfig;

		if ( is_null( $forcecounter ) ) {
			$this->counter = $forcecounter;
		}

		if ( ( $this->transaction_date == '0000-00-00 00:00:00' ) && $forcecleared ) {
			$app = JFactory::getApplication();

			$this->transaction_date = date( 'Y-m-d H:i:s', ( (int) gmdate('U') ) );
		}

		$data = $this->getWorkingData( $InvoiceFactory );

		$data['invoice_id'] = $this->id;
		$data['invoice_number'] = $this->invoice_number;

		$data['invoice_date'] = aecTemplate::date( $InvoiceFactory->invoice->created_date );

		$data['itemlist'] = array();
		$total = 0;
		$break = 0;
		foreach ( $InvoiceFactory->items->itemlist as $iid => $item ) {
			if ( isset( $item['obj'] ) ) {
				$amt =  $item['terms']->nextterm->cost[0]->cost['amount'];

				$data['itemlist'][] = '<tr id="invoice_content_item">'
					. '<td>' . $item['name'] . '</td>'
					. '<td>' . AECToolbox::formatAmount( $amt, $InvoiceFactory->invoice->currency ) . '</td>'
					. '<td>' . $item['quantity'] . '</td>'
					. '<td>' . AECToolbox::formatAmount( $amt * $item['quantity'], $InvoiceFactory->invoice->currency ) . '</td>'
					. '</tr>';

				foreach ( $item['terms']->nextterm->cost as $cid => $cost ) {
					if ( $cid != 0 ) {
						if ( $cost->type == 'discount' ) {
							if ( !empty( $cost->cost['details'] ) ) {
								$ta = '&nbsp;(' . $cost->cost['details'] . ')';
							} else {
								$ta = "";
							}

							$data['itemlist'][] = '<tr id="invoice_content_item">'
							. '<td>' . JText::_('AEC_CHECKOUT_DISCOUNT') . $ta . '</td>'
							. '<td></td>'
							. '<td></td>'
							. '<td>' . AECToolbox::formatAmount( $cost->cost['amount'], $InvoiceFactory->invoice->currency ) . '</td>'
							. '</tr>';
						} elseif ( $cost->type == 'cost' ) {
							if ( !empty( $cost->cost['details'] ) ) {
								$ta = '&nbsp;(' . $cost->cost['details'] . ')';
							} else {
								$ta = "";
							}

							$data['itemlist'][] = '<tr id="invoice_content_item">'
							. '<td>' . $ta . '</td>'
							. '<td></td>'
							. '<td></td>'
							. '<td>' . AECToolbox::formatAmount( $cost->cost['amount'], $InvoiceFactory->invoice->currency ) . '</td>'
							. '</tr>';
						}
					}
				}
			}
		}

		$data['totallist'][] = '<tr id="invoice_content_item_separator">'
			. '<td colspan="4"></td>'
			. '</tr>';

		if ( isset( $InvoiceFactory->items->tax ) ) {
			if ( isset( $InvoiceFactory->items->total ) ) {
				$data['totallist'][] = '<tr id="invoice_content_item_total">'
					. '<td>' . JText::_('INVOICEPRINT_TOTAL') . '</td>'
					. '<td></td>'
					. '<td></td>'
					. '<td>' . AECToolbox::formatAmount( $InvoiceFactory->items->total->cost['amount'], $InvoiceFactory->invoice->currency ) . '</td>'
					. '</tr>';
			}

			foreach ( $InvoiceFactory->items->tax as $item ) {
				$details = null;
				foreach ( $item['terms']->terms[0]->cost as $citem ) {
					if ( $citem->type == 'tax' ) {
						$details = $citem->cost['details'];
					}
				}

				$data['totallist'][] = '<tr id="invoice_content_item_tax">'
					. '<td>Tax' . '&nbsp;( ' . $details . ' )' . '</td>'
					. '<td></td>'
					. '<td></td>'
					. '<td>' . AECToolbox::formatAmount( $item['cost'], $InvoiceFactory->invoice->currency ) . '</td>'
					. '</tr>';
			}
		}

		if ( isset( $InvoiceFactory->items->grand_total ) ) {
			$data['totallist'][] = '<tr id="invoice_content_item_total">'
				. '<td>' . JText::_('INVOICEPRINT_GRAND_TOTAL') . '</td>'
				. '<td></td>'
				. '<td></td>'
				. '<td>' . AECToolbox::formatAmount( $InvoiceFactory->items->grand_total->cost['amount'], $InvoiceFactory->invoice->currency ) . '</td>'
				. '</tr>';
		}

		if ( $this->transaction_date == '0000-00-00 00:00:00' ) {
			if ( !$this->active ) {
				$data['paidstatus'] = JText::_('INVOICEPRINT_PAIDSTATUS_CANCEL');
			} else {
				$data['paidstatus'] = JText::_('INVOICEPRINT_PAIDSTATUS_UNPAID');
			}
		} else {
			if ( !$this->active ) {
				$data['paidstatus'] = JText::_('INVOICEPRINT_PAIDSTATUS_CANCEL');
			} else {
				$date = AECToolbox::formatDate( $this->transaction_date );

				$data['paidstatus'] = sprintf( JText::_('INVOICEPRINT_PAIDSTATUS_PAID'), $date );
			}
		}

		$pplist = array();

		if ( $this->method != 'none' ) {
			$pp = new PaymentProcessor();
			if ( $pp->loadName( $this->method ) ) {
				$pp->init();

				if ( !empty( $InvoiceFactory->plan->id ) ) {
					$pp->exchangeSettingsByPlan( $InvoiceFactory->plan->id, $InvoiceFactory->plan->params );
				}
			}
		} else {
			$pp = null;
		}

		$pplist[$this->method] = $pp;

		$recurring = $pplist[$this->method]->is_recurring();

		$data['recurringstatus'] = "";
		if ( $recurring ) {
			$data['recurringstatus'] = JText::_('INVOICEPRINT_RECURRINGSTATUS_RECURRING');
		} elseif ( !empty( $InvoiceFactory->plan->id ) ) {
			if ( !empty( $InvoiceFactory->plan->params['trial_amount'] ) && $InvoiceFactory->plan->params['trial_period'] ) {
				$data['recurringstatus'] = JText::_('INVOICEPRINT_RECURRINGSTATUS_ONCE');
			}
		}

		$data['invoice_billing_history'] = "";
		if ( !empty( $this->transactions ) ) {
			if ( ( ( count( $this->transactions ) > 0 ) && !empty( $data['recurringstatus'] ) ) && ( $this->method != 'none' ) ) {
				$data['paidstatus'] = sprintf( JText::_('INVOICEPRINT_PAIDSTATUS_PAID'), "" );

				foreach ( $this->transactions as $transaction ) {
					if ( !isset( $pplist[$transaction->processor] ) ) {
						$pp = new PaymentProcessor();

						if ( $pp->loadName( $transaction->processor ) ) {
							$pp->getInfo();

							$pplist[$transaction->processor] = $pp;
						}
					}

					$data['invoice_billing_history'] .= '<tr><td>' . AECToolbox::formatDate( $transaction->timestamp ) . '</td><td>' . $transaction->amount . '&nbsp;' . $transaction->currency . '</td><td>' . $pplist[$transaction->processor]->info['longname'] . '</td></tr>';
				}
			}
		}

		$otherfields = array( "page_title", "before_header", "header", "after_header", "address", "before_content", "after_content", "before_footer", "footer", "after_footer" );

		foreach ( $otherfields as $field ) {
			if ( !empty( $aecConfig->cfg["invoice_".$field] ) ) {
				$data[$field] = AECToolbox::rewriteEngineRQ( $aecConfig->cfg["invoice_".$field], $InvoiceFactory );
			} else {
				$data[$field] = "";
			}
		}

		return $data;
	}

	function getTransactionStatus()
	{
		$lang = JFactory::getLanguage();

		if ( $this->transaction_date == '0000-00-00 00:00:00' ) {
			$transactiondate = 'uncleared';

			if ( empty( $this->params ) || empty( $row->params['pending_reason'] ) ) {
				return $transactiondate;
			}

			if ( $lang->hasKey( 'PAYMENT_PENDING_REASON_' . strtoupper( $row->params['pending_reason'] ) ) ) {
				$transactiondate = JText::_( 'PAYMENT_PENDING_REASON_' . strtoupper( $row->params['pending_reason'] ) );
			} else {
				$transactiondate = $row->params['pending_reason'];
			}
		} else {
			$transactiondate = aecTemplate::date( $this->transaction_date );
		}

		return $transactiondate;
	}

	function savePOSTsettings( $post )
	{
		$db = &JFactory::getDBO();

		if ( isset( $post['id'] ) ) {
			unset( $post['id'] );
		}

		// Filter out fixed variables
		$fixed = array( 'active', 'userid', 'usage', 'fixed', 'method', 'created_date', 'amount' );

		foreach ( $fixed as $varname ) {
			if ( isset( $post[$varname] ) ) {
				$this->$varname = $post[$varname];

				unset( $post[$varname] );
			} else {
				$this->$varname = '';
			}
		}

		if ( empty( $this->created_date ) ) {
			$this->created_date = date( 'Y-m-d H:i:s', ( (int) gmdate('U') ) );
		}

		if ( empty( $this->invoice_number ) ) {
			$this->invoice_number = $this->generateInvoiceNumber();
		}

		if ( !empty( $this->usage ) ) {
			$this->computeAmount();
		}

		//$this->saveParams( $params );
	}

}

class aecCartHelper
{
	function getCartidbyUserid( $userid )
	{
		$db = &JFactory::getDBO();

		$query = 'SELECT `id`'
				. ' FROM #__acctexp_cart'
				. ' WHERE userid = \'' . $userid . '\''
				;

		$db->setQuery( $query );
		return $db->loadResult();

	}

	function getCartbyUserid( $userid )
	{
		$db = &JFactory::getDBO();

		$id = aecCartHelper::getCartidbyUserid( $userid );

		$cart = new aecCart( $db );
		$cart->load( $id );

		if ( empty( $id ) ) {
			$cart->userid = $userid;
		}

		return $cart;
	}

	function getCartItemObject( $cart, $id )
	{
		$item = $cart->getItem( $id );
		if ( !empty( $item ) ) {
			$db = &JFactory::getDBO();

			$plan = new SubscriptionPlan( $db );
			$plan->load( $item['id'] );

			return $plan;
		}
	}

	function getFirstCartItemObject( $cart )
	{
		if ( !empty( $cart->content ) ) {
			foreach ( $cart->content as $cid => $c ) {
				return aecCartHelper::getCartItemObject( $cart, $cid );
			}
		}

		return null;
	}

	function getFirstSortedCartItemObject( $cart )
	{
		$db = &JFactory::getDBO();

		$highest = 0;
		$cursor = 1000000;

		foreach ( $cart->content as $cid => $c ) {
			$query = 'SELECT ordering'
			. ' FROM #__acctexp_plans'
			. ' WHERE `id` = \'' . $c['id'] . '\''
			;
			$db->setQuery( $query );
			$ordering = $db->loadResult();

			if ( $ordering < $cursor ) {
				$highest = $cid;
				$cursor = $ordering;
			}
		}

		return aecCartHelper::getCartItemObject( $cart, $highest );
	}

	function getCartProcessorList( $cart, $nofree=true )
	{
		$proclist = array();

		if ( empty( $cart->content ) ) {
			return $proclist;
		}

		foreach ( $cart->content as $cid => $c ) {
			$cartitem = aecCartHelper::getCartItemObject( $cart, $cid );

			if ( is_array( $cartitem->params['processors'] ) && !empty( $cartitem->params['processors'] ) ) {
				foreach ( $cartitem->params['processors'] as $pid ) {
					$sid = $pid;

					/*if ( $cartitem->custom_params[$pid . '_aec_overwrite_settings'] ) {
						if ( !empty( $cartitem->custom_params[$pid . '_recurring'] ) ) {
							if ( $cartitem->custom_params[$pid . '_recurring'] == 2 ) {
								if ( array_search( $sid, $proclist ) === false ) {
									$proclist[] = $sid;
								}
							}

							$sid .= '_recurring';
						}
					}*/

					if ( array_search( $sid, $proclist ) === false ) {
						$proclist[] = $sid;
					}
				}
			}
		}

		return $proclist;
	}

	function getCartProcessorGroups( $cart )
	{
		$pgroups	= array();

		foreach ( $cart->content as $cid => $c ) {
			$cartitem = aecCartHelper::getCartItemObject( $cart, $cid );

			$pplist			= array();
			$pplist_names	= array();
			if ( !empty( $cartitem->params['processors'] ) ) {
				foreach ( $cartitem->params['processors'] as $n ) {
					$pp = new PaymentProcessor();

					if ( !$pp->loadId( $n ) ) {
						continue;
					}

					$pp->init();
					$pp->getInfo();
					$pp->exchangeSettingsByPlan( $cartitem );

					if ( isset( $this->recurring ) ) {
						$recurring = $pp->is_recurring( $this->recurring );
					} else {
						$recurring = $pp->is_recurring();
					}

					if ( $recurring > 1 ) {
						$pplist[]		= $pp->id;
						$pplist_names[]	= $pp->info['longname'];

						if ( !$cartitem->params['lifetime'] ) {
							$pplist[]		= $pp->id . '_recurring';
							$pplist_names[]	= $pp->info['longname'];
						}
					} elseif ( !$cartitem->params['lifetime'] && $recurring ) {
						$pplist[]		= $pp->id . '_recurring';
						$pplist_names[]	= $pp->info['longname'];
					} else {
						$pplist[]		= $pp->id;
						$pplist_names[]	= $pp->info['longname'];
					}
				}
			}

			if ( empty( $pgroups ) ) {
				$pg = array();
				$pg['members']			= array( $cid );
				$pg['processors']		= $pplist;
				$pg['processor_names']	= $pplist_names;

				$pgroups[] = $pg;
			} else {
				$create = true;

				foreach ( $pgroups as $pgid => $pgroup ) {
					$pg = array();

					if ( count( $pplist ) == count( $pgroup['processors'] ) ) {
						$a = true;
						foreach ( $pplist as $k => $v ) {
							if ( $pgroup['processors'][$k] != $v ) {
								$a = false;
							}
						}

						if ( $a ) {
							$pgroups[$pgid]['members'][] = $cid;
							$create = false;
						}
					}
				}

				if ( $create ) {
					$pg['members']			= array( $cid );
					$pg['processors']		= $pplist;
					$pg['processor_names']	= $pplist_names;

					$pgroups[] = $pg;
				}
			}
		}

		return $pgroups;
	}

	function getInvoiceIdByCart( $cart )
	{
		$db = &JFactory::getDBO();

		$query = 'SELECT id'
		. ' FROM #__acctexp_invoices'
		. ' WHERE `usage` = \'c.' . $cart->id . '\''
		. ' AND active = \'1\''
		;
		$db->setQuery( $query );
		return $db->loadResult();
	}
}

class aecCart extends serialParamDBTable
{
	/** @var int Primary key */
	var $id					= null;
	/** @var int */
	var $userid				= null;
	/** @var datetime */
	var $created_date		= null;
	/** @var datetime */
	var $last_updated		= null;
	/** @var text */
	var $content 			= array();
	/** @var text */
	var $history 			= array();
	/** @var text */
	var $params 			= array();
	/** @var text */
	var $customparams		= array();

	/**
	* @param database A database connector object
	*/
	function aecCart( &$db )
	{
		parent::__construct( '#__acctexp_cart', 'id', $db );
	}

	function declareParamFields()
	{
		return array( 'content', 'history', 'params', 'customparams' );
	}

	function check()
	{
		$vars = get_class_vars( 'aecCart' );
		$props = get_object_vars( $this );

		foreach ( $props as $n => $prop ) {
			if ( !array_key_exists( $n, $vars  ) ) {
				unset( $this->$n );
			}
		}

		return parent::check();
	}

	function save()
	{
		$app = JFactory::getApplication();

		if ( !$this->id || ( strcmp( $user_subscription->created_date, '0000-00-00 00:00:00' ) !== 0 ) ) {
			$this->created_date = date( 'Y-m-d H:i:s', ( (int) gmdate('U') ) );
		}

		$this->last_updated = date( 'Y-m-d H:i:s', ( (int) gmdate('U') ) );

		return parent::save();
	}

	function action( $action, $details=null )
	{
		if ( $action == "clearCart" ) {
			$db = &JFactory::getDBO();

			// Delete Invoices referencing this Cart as well
			$query = 'DELETE FROM #__acctexp_invoices WHERE `usage` = \'c.' . $this->id . '\'';
			$db->setQuery( $query );
			$db->query();

			return $this->delete();
		}

		if ( method_exists( $this, $action ) ) {
			$a = array( 'action' => 'action',
						'event' => $action,
						'details' => $details
			);

			$return = $this->$action( $a, $details );

			$this->issueHistoryEvent( $return['action'], $return['event'], $return['details'] );
		} else {
			$this->issueHistoryEvent( 'error', 'action_not_found', array( $action, $details ) );
		}

		$this->storeload();
	}

	function addItem( $return, $item )
	{
		if ( is_object( $item ) ) {
			$id = $item->id;
		} else {
			$id = $item;
		}

		if ( !empty( $id ) ) {
			$element			= array();
			$element['type']	= 'plan';
			$element['id']		= $id;
			$element['quantity']	= 1;

			$return['details'] = array( 'type' => 'plan', 'id' => $id );

			$update = false;
			if ( !empty( $this->content ) ) {
				foreach ( $this->content as $iid => $item ) {
					if ( ( $item['type'] == $element['type'] ) && ( $item['id'] == $element['id'] ) ) {
						$return['event'] = 'updateItem';
						$this->content[$iid]['quantity']++;
						$update = true;
						break;
					}
				}
			}

			if ( !$update ) {
				$this->content[] = $element;
			}
		} else {
			$return['action']	= 'error';
			$return['event']	= 'no_item_provided';
			$return['details']	= array( 'type' => 'plan', 'item' => $item );
		}

		return $return;
	}

	function addCoupon( $coupon_code, $id=null )
	{
		if ( is_null( $id ) ) {
			if ( !isset( $this->params['overall_coupons'] ) ) {
				$this->params['overall_coupons'] = array();
			}

			if ( !in_array( $coupon_code, $this->params['overall_coupons'] ) ) {
				$this->params['overall_coupons'][] = $coupon_code;
			}
		} elseif ( isset( $this->content[$id] ) ) {
			if ( !isset( $this->content[$id]['coupons'] ) ) {
				$this->content[$id]['coupons'] = array();
			}

			if ( !in_array( $coupon_code, $this->content[$id]['coupons'] ) ) {
				$this->content[$id]['coupons'][] = $coupon_code;
			}
		}
	}

	function removeCoupon( $coupon_code, $id=null )
	{
		foreach ( $this->content as $cid => $content ) {
			if ( !is_null( $id ) ) {
				if ( $id !== $cid ) {
					continue;
				}
			}

			if ( !empty( $content['coupons'] ) ) {
				foreach ( $content['coupons'] as $ccid => $code ) {
					if ( $code == $coupon_code ) {
						unset( $this->content[$cid]['coupons'][$ccid] );
					}
				}
			}
		}

		if ( is_null( $id ) ) {
			if ( is_array( $this->params['overall_coupons'] ) && !empty( $this->params['overall_coupons'] ) ) {
				if ( in_array( $coupon_code, $this->params['overall_coupons'] ) ) {
					$oid = array_search( $coupon_code, $this->params['overall_coupons'] );
					unset( $this->params['overall_coupons'][$oid] );
				}
			}
		}
	}

	function hasCoupon( $coupon_code, $id=null )
	{
		if ( is_null( $id ) ) {
			if ( !empty( $this->params['overall_coupons'] ) ) {
				return in_array( $coupon_code, $this->params['overall_coupons'] );
			} else {
				return false;
			}
		} elseif ( isset( $this->content[$id] ) ) {
			if ( !empty( $this->content[$id]['coupons'] ) ) {
				return in_array( $coupon_code, $this->content[$id]['coupons'] );
			} else {
				return false;
			}
		}

		return false;
	}

	function getItemIdArray()
	{
		$array = array();
		foreach ( $this->content as $cid => $content ) {
			$array[$cid] = $content['id'];
		}

		return $array;
	}

	function getItem( $item )
	{
		if ( isset( $this->content[$item] ) ) {
			return $this->content[$item];
		} else {
			return null;
		}
	}

	function removeItem( $return, $itemid )
	{
		if ( isset( $this->content[$itemid] ) ) {
			$return['details'] = array( 'item_id' => $itemid, 'item' => $this->content[$itemid] );
			unset( $this->content[$itemid] );
		} else {
			$return = array(	'action' => 'error',
								'event' => 'item_not_found',
								'details' => array( 'item_id' => $itemid )
								);
		}

		return $return;
	}

	function updateItems( $return, $updates )
	{
		foreach ( $updates as $uid => $count ) {
			if ( isset( $this->content[$uid] ) ) {
				if ( empty( $count ) ) {
					unset( $this->content[$uid] );
				} else {
					$this->content[$uid]['quantity'] = $count;
				}
			}
		}

		return $return;
	}

	function getCheckout( $metaUser, $counter=0, $InvoiceFactory=null )
	{
		$db = &JFactory::getDBO();

		$c = array();

		$totalcost = 0;

		if ( empty( $this->content ) ) {
			return array();
		}

		$return = array();
		foreach ( $this->content as $cid => $content ) {
			// Cache items
			if ( !isset( $c[$content['type']][$content['id']] ) ) {
				switch ( $content['type'] ) {
					case 'plan':
						$obj = new SubscriptionPlan( $db );
						$obj->load( $content['id'] );

						$o = array();
						$o['obj']	= $obj;
						$o['name']	= $obj->getProperty( 'name' );
						$o['desc']	= $obj->getProperty( 'desc' );

						$terms = $obj->getTermsForUser( false, $metaUser );

						if ( $counter ) {
							$terms->incrementPointer( $counter );
						}

						$o['terms']	= $terms;
						$o['cost']	= $terms->nextterm->renderCost();

						$c[$content['type']][$content['id']] = $o;
						break;
				}
			}

			$entry = array();
			$entry['obj']			= $c[$content['type']][$content['id']]['obj'];
			$entry['fullamount']	= $c[$content['type']][$content['id']]['cost'];

			$entry['name']			= $c[$content['type']][$content['id']]['name'];
			$entry['desc']			= $c[$content['type']][$content['id']]['desc'];
			$entry['terms']			= $c[$content['type']][$content['id']]['terms'];

			$item = array( 'item' => array( 'obj' => $entry['obj'] ), 'terms' => $entry['terms'] );

			if ( !empty( $content['coupons'] ) ) {
				$cpsh = new couponsHandler( $metaUser, false, $content['coupons'] );

				$item = $cpsh->applyAllToItems( 0, $item );

				$entry['terms'] = $item['terms'];
			}

			$entry['cost'] = $entry['terms']->nextterm->renderTotal();

			if ( $entry['cost'] > 0 ) {
				$total = $content['quantity'] * $entry['cost'];

				$entry['cost_total']	= AECToolbox::correctAmount( $total );
			} else {
				$entry['cost_total']	= AECToolbox::correctAmount( '0.00' );
			}

			if ( $entry['cost_total'] == '0.00' ) {
				$entry['free'] = true;
			} else {
				$entry['free'] = false;
			}

			$entry['cost']			= AECToolbox::correctAmount( $entry['cost'] );

			$entry['quantity']		= $content['quantity'];

			$totalcost += $entry['cost_total'];

			$return[$cid] = $entry;
		}

		if ( !empty( $this->params['overall_coupons'] ) ) {
			$cpsh = new couponsHandler( $metaUser, $InvoiceFactory, $this->params['overall_coupons'] );

			$totalcost_ncp = $totalcost;
			$totalcost = $cpsh->applyToAmount( $totalcost );
		} else {
			$totalcost_ncp = $totalcost;
		}

		// Append total cost
		$return[] = array( 'name' => '',
							'count' => '',
							'cost' => AECToolbox::correctAmount( $totalcost_ncp ),
							'cost_total' => AECToolbox::correctAmount( $totalcost ),
							'is_total' => true,
							'obj' => false
							);

		return $return;
	}

	function getAmount( $metaUser=null, $counter=0, $InvoiceFactory=null )
	{
		$checkout = $this->getCheckout( $metaUser, $counter, $InvoiceFactory );

		if ( !empty( $checkout ) ) {
			$max = array_pop( array_keys( $checkout ) );

			return $checkout[$max]['cost_total'];
		} else {
			return '0.00';
		}
	}

	function checkAllFree( $metaUser, $counter=0, $InvoiceFactory=null )
	{
		$co = $this->getCheckout( $metaUser, $counter, $InvoiceFactory );

		foreach ( $co as $entry ) {
			if ( is_object( $entry ) ) {
				if ( !$entry['terms']->checkFree() ) {
					return false;
				}
			}
		}

		return true;
	}

	function getTopPlan()
	{
		return aecCartHelper::getFirstSortedCartItemObject( $this );
	}

	function triggerMIs( $action, &$metaUser, &$exchange, &$invoice, &$add, &$silent )
	{
		if ( is_array( $add ) ) {
			if ( !empty( $add['obj'] ) ) {
				$add['obj']->triggerMIs( $action, $metaUser, $exchange, $invoice, $add, $silent );
			}
		} else {
			if ( is_object( $add ) ) {
				foreach ( $add->itemlist as $nadd ) {
					$nadd['obj']->triggerMIs( $action, $metaUser, $exchange, $invoice, $add, $silent );
				}
			}
		}
	}

	function issueHistoryEvent( $class, $event, $details )
	{
		$app = JFactory::getApplication();

		if ( $class == 'error' ) {
			$this->_error = $event;
		}

		if ( !is_array( $this->history ) ) {
			$this->history = array();
		}

		$this->history[] = array(
							'timestamp'	=> date( 'Y-m-d H:i:s', ( (int) gmdate('U') ) ),
							'class'		=> $class,
							'event'		=> $event,
							'details'	=> $details,
							);

		return true;
	}
}

class Subscription extends serialParamDBTable
{
	/** @var int Primary key */
	var $id					= null;
	/** @var int */
	var $userid				= null;
	/** @var int */
	var $primary			= null;
	/** @var string */
	var $type				= null;
	/** @var string */
	var $status				= null;
	/** @var datetime */
	var $signup_date		= null;
	/** @var datetime */
	var $lastpay_date		= null;
	/** @var datetime */
	var $cancel_date		= null;
	/** @var datetime */
	var $eot_date			= null;
	/** @var string */
	var $eot_cause			= null;
	/** @var int */
	var $plan				= null;
	/** @var string */
	var $recurring			= null;
	/** @var int */
	var $lifetime			= null;
	/** @var datetime */
	var $expiration			= null;
	/** @var text */
	var $params 			= null;
	/** @var text */
	var $customparams		= null;

	/**
	* @param database A database connector object
	*/
	function Subscription( &$db )
	{
		parent::__construct( '#__acctexp_subscr', 'id', $db );
	}

	function declareParamFields()
	{
		return array( 'params', 'customparams' );
	}

	function check()
	{
		$vars = get_class_vars( 'Subscription' );
		$props = get_object_vars( $this );

		foreach ( $props as $n => $prop ) {
			if ( !array_key_exists( $n, $vars  ) ) {
				unset( $this->$n );
			}
		}

		return parent::check();
	}

	/**
	 * loads specified user
	 *
	 * @param int $userid
	 */
	function loadUserid( $userid )
	{
		$this->load( $this->getSubscriptionID( $userid ) );
	}

	function getSubscriptionID( $userid, $usage=null, $primary=1, $similar=false, $bias=null )
	{
		$db = &JFactory::getDBO();

		$query = 'SELECT `id`'
				. ' FROM #__acctexp_subscr'
				. ' WHERE `userid` = \'' . $userid . '\''
				;

		if ( !empty( $usage ) ) {
			$plan = new SubscriptionPlan( $db );
			$plan->load( $usage );

			if ( ( !empty( $plan->params['similarplans'] ) && $similar ) || !empty( $plan->params['equalplans'] ) ) {
				$allplans = array( $usage );

				if ( !empty( $plan->params['similarplans'] ) || !empty( $plan->params['equalplans'] ) ) {
					if ( empty( $plan->params['similarplans'] ) ) {
						$plan->params['similarplans'] = array();
					}

					if ( empty( $plan->params['equalplans'] ) ) {
						$plan->params['equalplans'] = array();
					}

					if ( $similar ) {
						$allplans = array_merge( $plan->params['similarplans'], $plan->params['equalplans'], $allplans );
					} else {
						$allplans = array_merge( $plan->params['equalplans'], $allplans );
					}
				}

				foreach ( $allplans as $apid => $pid ) {
					$allplans[$apid] = '`plan` = \'' . $pid . '\'';
				}

				$query .= ' AND (' . implode( ' OR ', $allplans ) . ')';
			} else {
				$query .= ' AND ' . '`plan` = \'' . $usage . '\'';
			}
		}

		if ( !empty( $primary ) ) {
			$query .= ' AND `primary` = \'1\'';
		} elseif ( !is_null( $primary ) ) {
			$query .= ' AND `primary` = \'0\'';
		}

		$db->setQuery( $query );

		if ( !empty( $bias ) ) {
			$subscriptionids = $db->loadResultArray();

			if ( in_array( $bias, $subscriptionids ) ) {
				$subscriptionid = $bias;
			}
		} else {
			$subscriptionid = $db->loadResult();
		}

		if ( !isset( $subscriptionid ) ) {
			$subscriptionid = null;
		}

		if ( empty( $subscriptionid ) && !$similar ) {
			return $this->getSubscriptionID( $userid, $usage, false, true, $bias );
		}

		return $subscriptionid;
	}

	function makePrimary()
	{
		$db = &JFactory::getDBO();

		$query = 'UPDATE #__acctexp_subscr'
				. ' SET `primary` = \'0\''
				. ' WHERE `userid` = \'' . $this->userid . '\''
				;
		$db->setQuery( $query );
		$db->query();

		$this->primary = 1;
		$this->storeload();
	}

	function manualVerify()
	{
		if ( $this->is_expired() ) {
			aecRedirect( AECToolbox::deadsureURL( 'index.php?option=com_acctexp&task=expired&userid=' . (int) $this->userid ), false, true );
			return false;
		} else {
			return true;
		}
	}

	function createNew( $userid, $processor, $pending, $primary=1, $plan=null )
	{
		if ( !$userid ) {
			return false;
		}

		$app = JFactory::getApplication();

		$this->userid		= $userid;
		$this->primary		= $primary;
		$this->signup_date	= date( 'Y-m-d H:i:s', ( (int) gmdate('U') ) );
		$this->expiration	= date( 'Y-m-d H:i:s', ( (int) gmdate('U') ) );
		$this->status		= $pending ? 'Pending' : 'Active';
		$this->type			= $processor;

		if ( !empty( $plan ) ) {
			$this->plan		= $plan;
		}

		return $this->storeload();
	}

	function is_expired( $offset=false )
	{
		global $aecConfig;

		$app = JFactory::getApplication();

		if ( $this->status == 'Expired' ) {
			return true;
		} elseif ( !$this->is_lifetime() ) {
			if ( $offset ) {
				$expstamp = strtotime( ( '-' . $offset . ' days' ), strtotime( $this->expiration ) );
			} else {
				$expstamp = strtotime( ( '+' . $aecConfig->cfg['expiration_cushion'] . ' hours' ), strtotime( $this->expiration ) );
			}

			$localtime = (int) gmdate('U');

			$is_past = ( $expstamp - $localtime ) < 0;

			if ( ( $expstamp > 0 ) && $is_past ) {
				return true;
			} elseif ( ( $expstamp <= 0 ) ) {
				return true;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}

	function is_lifetime()
	{
		if ( ( $this->expiration === '9999-12-31 00:00:00' ) || ( $this->expiration === '0000-00-00 00:00:00' ) ) {
			return true;
		} else {
			return false;
		}
	}

	function setExpiration( $unit, $value, $extend )
	{
		$app = JFactory::getApplication();

		$now = (int) gmdate('U');

		if ( $extend ) {
			$current = strtotime( $this->expiration );

			if ( $current < $now ) {
				$current = $now;
			}
		} else {
			$current = $now;
		}

		$this->expiration = AECToolbox::computeExpiration( $value, $unit, $current );
	}


	/**
	* Get alert level for a subscription
	* @param int user id
	* @return Object alert
	* alert['level'] = -1 means no threshold has been reached
	* alert['level'] =  0 means subscription expired
	* alert['level'] =  1 means most critical threshold has been reached (default: 7 days or less to expire)
	* alert['level'] =  2 means second level threshold has been reached (default: 14 days or less to expire)
	* alert['daysleft'] = number of days left to expire
	*/
	function GetAlertLevel()
	{
		$db = &JFactory::getDBO();

		global $aecConfig;

		$app = JFactory::getApplication();

		if ( $this->expiration ) {
			$alert['level']		= -1;
			$alert['daysleft']	= 0;

			$expstamp = strtotime( $this->expiration );

			// Get how many days left to expire (86400 sec = 1 day)
			$alert['daysleft']	= round( ( $expstamp - ( (int) gmdate('U') ) ) / 86400 );

			if ( $alert['daysleft'] < 0 ) {
				// Subscription already expired. Alert Level 0!
				$alert['level']	= 1;
			} else {
				// Get alert levels
				if ( $alert['daysleft'] <= $aecConfig->cfg['alertlevel1'] ) {
					// Less than $numberofdays to expire! This is a level 1
					$alert['level']		= 1;
				} elseif ( ( $alert['daysleft'] > $aecConfig->cfg['alertlevel1'] ) && ( $alert['daysleft'] <= $aecConfig->cfg['alertlevel2'] ) ) {
					$alert['level']		= 2;
				} elseif ( $alert['daysleft'] > $aecConfig->cfg['alertlevel2'] ) {
					// Everything is ok. Level 3 means no threshold was reached
					$alert['level']		= 3;
				}
			}
		}

		return $alert;
	}

	function verifylogin( $block, $metaUser=false )
	{
		$db = &JFactory::getDBO();

		global $aecConfig;

		if ( strcmp( $this->status, 'Excluded' ) === 0 ) {
			$expired = false;
		} elseif ( strcmp( $this->status, 'Expired' ) === 0 ) {
			$expired = true;
		} else {
			$expired = $this->is_expired();
		}

		if ( $expired ) {
			$pp = new PaymentProcessor();

			if ( $pp->loadName( $subscription->type ) ) {
				$validation = $pp->validateSubscription();
			} else {
				$validation = false;
			}
		}

		if ( ( $expired || ( strcmp( $this->status, 'Closed' ) === 0 ) ) && $aecConfig->cfg['require_subscription'] ) {
			if ( $metaUser !== false ) {
				$metaUser->setTempAuth();
			}

			if ( strcmp( $this->status, 'Expired' ) === 0 ) {
				aecRedirect( AECToolbox::deadsureURL( 'index.php?option=com_acctexp&task=expired&userid=' . $this->userid ), false, true );
			} else {
				if ( $this->expire() ) {
					aecRedirect( AECToolbox::deadsureURL( 'index.php?option=com_acctexp&task=expired&userid=' . $this->userid ), false, true );
				}
			}
		} elseif ( ( strcmp( $this->status, 'Pending' ) === 0 ) || $block ) {
			if ( $metaUser !== false ) {
				$metaUser->setTempAuth();
			}
			aecRedirect( AECToolbox::deadsureURL( 'index.php?option=com_acctexp&task=pending&userid=' . $this->userid ), false, true );
		} elseif ( ( strcmp( $this->status, 'Hold' ) === 0 ) || $block ) {
			aecRedirect( AECToolbox::deadsureURL( 'index.php?option=com_acctexp&task=hold&userid=' . $this->userid ), false, true );
		}
	}

	function verify( $block, $metaUser=false )
	{
		$db = &JFactory::getDBO();

		global $aecConfig;

		if ( strcmp( $this->status, 'Excluded' ) === 0 ) {
			$expired = false;
		} elseif ( strcmp( $this->status, 'Expired' ) === 0 ) {
			$expired = true;
		} else {
			$expired = $this->is_expired();
		}

		if ( $expired ) {
			$pp = new PaymentProcessor();

			if ( $pp->loadName( $this->type ) ) {
				$expired = !$pp->validateSubscription( $this );
			}
		}

		if ( ( $expired || ( strcmp( $this->status, 'Closed' ) === 0 ) ) && $aecConfig->cfg['require_subscription'] ) {
			if ( $metaUser !== false ) {
				$metaUser->setTempAuth();
			}

			if ( strcmp( $this->status, 'Expired' ) === 0 ) {
				return 'expired';
			} else {
				if ( $this->expire() ) {
					return 'expired';
				}
			}
		} elseif ( ( strcmp( $this->status, 'Pending' ) === 0 ) || $block ) {
			return 'pending';
		} elseif ( ( strcmp( $this->status, 'Hold' ) === 0 ) || $block ) {
			return 'hold';
		}

		return true;
	}

	function expire( $overridefallback=false, $special=null )
	{
		$db = &JFactory::getDBO();

		// Users who are excluded cannot expire
		if ( strcmp( $this->status, 'Excluded' ) === 0 ) {
			return false;
		}

		// Load plan variables, otherwise load dummies
		if ( $this->plan ) {
			$subscription_plan = new SubscriptionPlan( $db );
			$subscription_plan->load( $this->plan );
		} else {
			$subscription_plan = false;
		}

		$metaUser = new metaUser( $this->userid );

		// Move the focus Subscription		
		if ( !$metaUser->moveFocus( $this->id ) ) {
			return null;
		}

		// Recognize the fallback plan, if not overridden
		if ( !empty( $subscription_plan->params['fallback'] ) && !$overridefallback ) {
			if ( !$subscription_plan->params['make_primary'] && !empty( $subscription_plan->params['fallback_req_parent'] ) ) {
				if ( $metaUser->focusSubscription->id != $metaUser->objSubscription->id ) {
					if ( $metaUser->objSubscription->is_expired() ) {
						$overridefallback = true;
					}
				}
			}

			if ( !$overridefallback ) {
				$metaUser->focusSubscription->applyUsage( $subscription_plan->params['fallback'], 'none', 1 );
				$this->reload();
				return false;
			}
		} else {
			// Set a Trial flag if this is an expired Trial for further reference
			if ( strcmp( $this->status, 'Trial' ) === 0 ) {
				$metaUser->focusSubscription->addParams( array( 'trialflag' => 1 ) );
			} elseif ( is_array( $this->params ) ) {
				if ( in_array( 'trialflag', $this->params ) ) {
					$metaUser->focusSubscription->delParams( array( 'trialflag' ) );
				}
			}

			if ( !( strcmp( $metaUser->focusSubscription->status, 'Expired' ) === 0 ) && !( strcmp( $metaUser->focusSubscription->status, 'Closed' ) === 0 ) ) {
				$metaUser->focusSubscription->setStatus( 'Expired' );
			}

			// Call Expiration MIs
			if ( $subscription_plan !== false ) {
				$mih = new microIntegrationHandler();
				$mih->userPlanExpireActions( $metaUser, $subscription_plan, $special );
			}
		}

		$this->reload();

		return true;
	}

	function cancel( $invoice=null )
	{
		$db = &JFactory::getDBO();

		// Since some processors do not notify each period, we need to check whether the expiration
		// lies too far in the future and cut it down to the end of the period the user has paid

		if ( $this->plan ) {
			$app = JFactory::getApplication();

			$subscription_plan = new SubscriptionPlan( $db );
			$subscription_plan->load( $this->plan );

			// Resolve blocks that we are going to substract from the set expiration date
			$unit = 60*60*24;
			switch ( $subscription_plan->params['full_periodunit'] ) {
				case 'W':
					$unit *= 7;
					break;
				case 'M':
					$unit *= 31;
					break;
				case 'Y':
					$unit *= 365;
					break;
			}

			$periodlength = $subscription_plan->params['full_period'] * $unit;

			$newexpiration = strtotime( $this->expiration );
			$now = (int) gmdate('U');

			// ...cut away blocks until we are in the past
			while ( $newexpiration > $now ) {
				$newexpiration -= $periodlength;
			}

			// Be extra sure that we did not overachieve
			if ( $newexpiration < $now ) {
				$newexpiration += $periodlength;
			}

			// And we get the bare expiration date
			$this->expiration = date( 'Y-m-d H:i:s', $newexpiration );
		}

		$this->setStatus( 'Cancelled' );

		return true;
	}

	function hold( $invoice=null )
	{
		$this->setStatus( 'Hold' );

		return true;
	}

	function hold_settle( $invoice=null )
	{
		$this->setStatus( 'Active' );

		return true;
	}

	function setStatus( $status )
	{
		$this->status = $status;

		$this->storeload();
	}

	function applyUsage( $usage = 0, $processor = 'none', $silent = 0, $multiplicator = 1, $invoice=null )
	{
		$db = &JFactory::getDBO();

		if ( !$usage ) {
			$usage = $this->plan;
		}

		$new_plan = new SubscriptionPlan( $db );
		$new_plan->load( $usage );

		if ( $new_plan->id ) {
			return $new_plan->applyPlan( $this, $processor, $silent, $multiplicator, $invoice );
		} else {
			return false;
		}
	}

	function triggerPreExpiration( $metaUser, $mi_pexp )
	{
		$db = &JFactory::getDBO();

		$actions = 0;

		// No actions on expired or recurring
		if ( ( strcmp( $this->status, 'Expired' ) === 0 ) || $this->recurring ) {
			return $actions;
		}

		$subscription_plan = new SubscriptionPlan( $db );
		$subscription_plan->load( $this->plan );

		$micro_integrations = $subscription_plan->getMicroIntegrations();

		if ( empty( $micro_integrations ) ) {
			return $actions;
		}

		foreach ( $micro_integrations as $mi_id ) {
			if ( !in_array( $mi_id, $mi_pexp ) ) {
				continue;
			}

			$mi = new microIntegration( $db );

			if ( !$mi->mi_exists( $mi_id ) ) {
				continue;
			}

			$mi->load( $mi_id );

			if ( !$mi->callIntegration() ) {
				continue;
			}

				// Do the actual pre expiration check on this MI
			if ( $this->status != 'Trial' ) {
				if ( $this->is_expired( $mi->pre_exp_check ) ) {
					$result = $mi->pre_expiration_action( $metaUser, $subscription_plan );
					if ( $result ) {
						$actions++;
					}
				}
			}


			unset( $mi );
		}

		return $actions;
	}

	function sendEmailRegistered( $renew, $adminonly=false, $invoice=null )
	{
		$db = &JFactory::getDBO();

		$app = JFactory::getApplication();

		$lang =& JFactory::getLanguage();

		global $aecConfig;

		$free = ( strcmp( strtolower( $this->type ), 'none' ) == 0 || strcmp( strtolower( $this->type ), 'free' ) == 0 );

		$urow = new JTableUser( $db );
		$urow->load( $this->userid );

		$plan = new SubscriptionPlan( $db );
		$plan->load( $this->plan );

		$name			= $urow->name;
		$email			= $urow->email;
		$username		= $urow->username;
		$pwd			= $urow->password;
		$activationcode	= $urow->activation;

		$message = sprintf( JText::_('ACCTEXP_MAILPARTICLE_GREETING'), $name );

		// Assemble E-Mail Subject & Message
		if ( $renew ) {
			$subject = sprintf( JText::_('ACCTEXP_SEND_MSG_RENEW'), $name, $app->getCfg( 'sitename' ) );

			$message .= sprintf( JText::_('ACCTEXP_MAILPARTICLE_THANKSREN'), $app->getCfg( 'sitename' ) );

			if ( $plan->email_desc ) {
				$message .= "\n\n" . $plan->email_desc . "\n\n";
			} else {
				$message .= " ";
			}

			if ( $free ) {
				$message .= sprintf( JText::_('ACCTEXP_MAILPARTICLE_LOGIN'), JURI::root() );
			} else {
				$message .= JText::_('ACCTEXP_MAILPARTICLE_PAYREC') . " "
				. sprintf( JText::_('ACCTEXP_MAILPARTICLE_LOGIN'), JURI::root() );
			}
		} else {
			$subject = sprintf( JText::_('ACCTEXP_SEND_MSG'), $name, $app->getCfg( 'sitename' ) );

			$message .= sprintf(JText::_('ACCTEXP_MAILPARTICLE_THANKSREG'), $app->getCfg( 'sitename' ) );

			if ( $plan->email_desc ) {
				$message .= "\n\n" . $plan->email_desc . "\n\n";
			} else {
				$message .= " ";
			}

			if ( $free ) {
				$message .= sprintf( JText::_('ACCTEXP_MAILPARTICLE_LOGIN'), JURI::root() );
			} else {
				$message .= JText::_('ACCTEXP_MAILPARTICLE_PAYREC') . " "
				. sprintf( JText::_('ACCTEXP_MAILPARTICLE_LOGIN'), JURI::root() );
			}
		}

		$message .= JText::_('ACCTEXP_MAILPARTICLE_FOOTER');

		$subject = html_entity_decode( $subject, ENT_QUOTES, 'UTF-8' );
		$message = html_entity_decode( $message, ENT_QUOTES, 'UTF-8' );

		// Send email to user
		if ( $app->getCfg( 'mailfrom' ) != '' && $app->getCfg( 'fromname' ) != '' ) {
			$adminName2		= $app->getCfg( 'fromname' );
			$adminEmail2	= $app->getCfg( 'mailfrom' );
		} else {
			$rows = aecACLhandler::getSuperAdmins();
			$row2 			= $rows[0];

			$adminName2		= $row2->name;
			$adminEmail2	= $row2->email;
		}

		if ( !$adminonly ) {
			JUTility::sendMail( $adminEmail2, $adminEmail2, $email, $subject, $message );
		}

		$aecUser = array();
		if ( is_object( $invoice ) ) {
			if ( !empty( $invoice->params['creator_ip'] ) ) {
				$aecUser['ip'] 	= $invoice->params['creator_ip'];

				// user Hostname (if not deactivated)
				if ( $aecConfig->cfg['gethostbyaddr'] ) {
					$aecUser['isp'] = gethostbyaddr( $invoice->params['creator_ip'] );
				} else {
					$aecUser['isp'] = 'deactivated';
				}
			}
		}

		if ( empty( $aecUser ) ) {
			$aecUser = AECToolbox::aecIP();
		}

		// Send notification to all administrators

		if ( $renew ) {
			$subject2 = sprintf( JText::_('ACCTEXP_SEND_MSG_RENEW'), $name, $app->getCfg( 'sitename' ) );
			$message2 = sprintf( JText::_('ACCTEXP_ASEND_MSG_RENEW'), $adminName2, $app->getCfg( 'sitename' ), $name, $email, $username, $plan->id, $plan->name, $aecUser['ip'], $aecUser['isp'] );
		} else {
			$subject2 = sprintf( JText::_('ACCTEXP_SEND_MSG'), $name, $app->getCfg( 'sitename' ) );
			$message2 = sprintf( JText::_('ACCTEXP_ASEND_MSG'), $adminName2, $app->getCfg( 'sitename' ), $name, $email, $username, $plan->id, $plan->name, $aecUser['ip'], $aecUser['isp'] );
		}

		$subject2 = html_entity_decode( $subject2, ENT_QUOTES, 'UTF-8' );
		$message2 = html_entity_decode( $message2, ENT_QUOTES, 'UTF-8' );

		$admins = AECToolbox::getAdminEmailList();

		foreach ( $admins as $adminemail ) {
			if ( !empty( $adminemail ) ) {
				JUTility::sendMail( $adminEmail2, $adminEmail2, $adminemail, $subject2, $message2 );
			}
		}
	}

	function getMIflags( $usage, $mi )
	{
		// Create the Params Prefix
		$flag_name = 'MI_FLAG_USAGE_' . strtoupper( $usage ) . '_MI_' . strtoupper( $mi );

		// Filter out the params for this usage and MI
		$mi_params = array();
		if ( $this->params ) {
			foreach ( $this->params as $name => $value ) {
				if ( strpos( $name, $flag_name ) == 0 ) {
					$paramname = strtolower( substr( strtoupper( $name ), strlen( $flag_name ) + 1 ) );
					$mi_params[$paramname] = $value;
				}
			}
		}

		// Only return params if they exist
		if ( count( $mi_params ) ) {
			return $mi_params;
		} else {
			return false;
		}
	}

	function setMIflags( $usage, $mi, $flags )
	{
		// Create the Params Prefix
		$flag_name = 'MI_FLAG_USAGE_' . strtoupper( $usage ) . '_MI_' . $mi;

		// Write to $params array
		foreach ( $flags as $name => $value ) {
			$param_name = $flag_name . '_' . strtoupper( $name );
			$this->params[$param_name] = $value;
		}

		$this->storeload();
	}
}

class GeneralInfoRequester
{
	/**
	 * Check whether a component is installed
	 * @return Bool
	 */
	function detect_component( $component )
	{
		$db = &JFactory::getDBO();

		global $aecConfig;

		$app = JFactory::getApplication();

		$tables	= array();
		$tables	= $db->getTableList();

		if ( !empty( $aecConfig->cfg['bypassintegration'] ) ) {
			$bypass = str_replace( ',', ' ', $aecConfig->cfg['bypassintegration'] );

			$overrides = explode( ' ', $bypass );

			foreach ( $overrides as $i => $c ) {
				$overrides[$i] = trim($c);
			}

			if ( in_array( 'CB', $overrides ) ) {
				$overrides[] = 'CB1.2';
			}

			if ( in_array( 'CB', $overrides ) || in_array( 'CB1.2', $overrides ) || in_array( 'CBE', $overrides ) ) {
				$overrides[] = 'anyCB';
			}

			if ( in_array( $component, $overrides ) ) {
				return false;
			}
		}

		$pathCB		= JPATH_SITE . '/components/com_comprofiler';

		switch ( $component ) {
			case 'anyCB': // any Community Builder
				return is_dir( $pathCB );
				break;

			case 'CB1.2': // Community Builder 1.2
				$is_cbe	= ( is_dir( $pathCB. '/enhanced' ) || is_dir( $pathCB . '/enhanced_admin' ) );
				$is_cb	= ( is_dir( $pathCB ) && !$is_cbe );

				$is12 = file_exists( $pathCB . '/js/cb12.js' );

				return ( $is_cb && $is12 );
				break;

			case 'CB': // Community Builder
				$is_cbe	= ( is_dir( $pathCB. '/enhanced' ) || is_dir( $pathCB . '/enhanced_admin' ) );
				$is_cb	= ( is_dir( $pathCB ) && !$is_cbe );
				return $is_cb;
				break;

			case 'CBE': // Community Builder Enhanced
				$is_cbe = ( is_dir( $pathCB . '/enhanced' ) || is_dir( $pathCB . '/enhanced_admin' ) );
				return $is_cbe;
				break;

			case 'CBM': // Community Builder Moderator for Workflows
				return file_exists( JPATH_SITE . '/modules/mod_comprofilermoderator.php' );
				break;

			case 'ALPHA': // AlphaRegistration
				return file_exists( JPATH_SITE . '/components/com_alpharegistration/alpharegistration.php' );
				break;

			case 'UE': // User Extended
				return in_array( $app->getCfg( 'dbprefix' ) . 'user_extended', $tables );
				break;

			case 'SMF': // Simple Machines Forum
				$pathSMF	= JPATH_SITE . '/administrator/components/com_smf';

				return file_exists( $pathSMF . '/config.smf.php') || file_exists( $pathSMF . '/smf.php' );
				break;

			case 'VM': // VirtueMart
				return in_array( $app->getCfg( 'dbprefix' ) . 'vm_orders', $tables );
				break;

			case 'JACL': // JACL
				return in_array( $app->getCfg( 'dbprefix' ) . 'jaclplus', $tables );
				break;

			case 'UHP2':
				return file_exists( JPATH_SITE . '/modules/mod_uhp2_manage.php' );
				break;

			case 'JUSER':
				return file_exists( JPATH_SITE . '/components/com_juser/juser.php' );
				break;

			case 'JOMSOCIAL':
				return file_exists( JPATH_SITE . '/components/com_community/community.php' );
				break;
		}
	}
}

class AECfetchfromDB
{
	function UserIDfromInvoiceNumber( $invoice_number )
	{
		$db = &JFactory::getDBO();

		$query = 'SELECT `userid`'
				. ' FROM #__acctexp_invoices'
				. ' WHERE `invoice_number` = \'' . $invoice_number . '\''
				;
		$db->setQuery( $query );
		return $db->loadResult();
	}

	function InvoiceIDfromNumber( $invoice_number, $userid = 0, $override_active = false )
	{
		$db = &JFactory::getDBO();

		$query = 'SELECT `id`'
				. ' FROM #__acctexp_invoices'
				;

		if ( $override_active ) {
			$query .= ' WHERE';
		} else {
			$query .= ' WHERE `active` = \'1\' AND';
		}

		$query .= ' ( `invoice_number` LIKE \'' . $invoice_number . '\''
				. ' OR `secondary_ident` LIKE \'' . $invoice_number . '\' )'
				;

		if ( $userid ) {
			$query .= ' AND `userid` = \'' . ( (int) $userid ) . '\'';
		}

		$db->setQuery( $query );
		return $db->loadResult();
	}

	function InvoiceNumberfromId( $id, $override_active = false )
	{
		$db = &JFactory::getDBO();

		$query = 'SELECT `invoice_number`'
				. ' FROM #__acctexp_invoices'
				;

		if ( $override_active ) {
			$query .= ' WHERE';
		} else {
			$query .= ' WHERE `active` = \'1\' AND';
		}

		$query .= ' `id` = \'' . ( (int) $id ) . '\'';

		$db->setQuery( $query );
		return $db->loadResult();
	}

	function lastUnclearedInvoiceIDbyUserID( $userid, $excludedusage=null )
	{
		global $aecConfig;

		if ( empty( $excludedusage ) ) {
			$excludedusage = array();
		}

		$db = &JFactory::getDBO();

		$query = 'SELECT `id`, `invoice_number`, `usage`'
				. ' FROM #__acctexp_invoices'
				. ' WHERE `userid` = \'' . ( (int) $userid ) . '\''
				. ' AND `transaction_date` = \'0000-00-00 00:00:00\''
				. ' AND `active` = \'1\''
				. ' ORDER BY `id` DESC'
				;
		$db->setQuery( $query );
		$invoice_list = $db->loadObjectList();

		if ( empty( $invoice_list ) ) {
			return false;
		}

		foreach ( $invoice_list as $invoice ) {
			if ( strpos( $invoice->usage, '.' ) ) {
				return $invoice->invoice_number;
			} elseif ( !in_array( $invoice->usage, $excludedusage ) ) {
				$status = SubscriptionPlanHandler::PlanStatus( $invoice->usage );
				if ( $status || ( !$status && $aecConfig->cfg['allow_invoice_unpublished_item'] ) ) {
					return $invoice->invoice_number;
				} else {
					// Plan is not active anymore, try the next invoice.
					$excludedusage[] = $invoice->usage;

					return AECfetchfromDB::lastUnclearedInvoiceIDbyUserID( $userid, $excludedusage );
				}
			}
		}

		return false;
	}

	function lastClearedInvoiceIDbyUserID( $userid, $planid=0 )
	{
		$db = &JFactory::getDBO();

		$query = 'SELECT id'
				. ' FROM #__acctexp_invoices'
				. ' WHERE `userid` = \'' . (int) $userid . '\''
				;

		if ( $planid ) {
			$query .= ' AND `usage` = \'' . (int) $planid . '\'';
		}

		$query .= ' ORDER BY `transaction_date` DESC';

		$db->setQuery( $query );
		return $db->loadResult();
	}

	function InvoiceCountbyUserID( $userid )
	{
		$db = &JFactory::getDBO();

		$query = 'SELECT count(*)'
				. ' FROM #__acctexp_invoices'
				. ' WHERE `userid` = \'' . (int) $userid . '\''
				. ' AND `active` = \'1\''
				;
		$db->setQuery( $query );
		return $db->loadResult();
	}

	function UnpaidInvoiceCountbyUserID( $userid )
	{
		$db = &JFactory::getDBO();

		$query = 'SELECT count(*)'
				. ' FROM #__acctexp_invoices'
				. ' WHERE `userid` = \'' . (int) $userid . '\''
				. ' AND `active` = \'1\''
				. ' AND `transaction_date` = \'0000-00-00 00:00:00\''
				;
		$db->setQuery( $query );
		return $db->loadResult();
	}

	function PaidInvoiceCountbyUserID( $userid )
	{
		$db = &JFactory::getDBO();

		$query = 'SELECT count(*)'
				. ' FROM #__acctexp_invoices'
				. ' WHERE `userid` = \'' . (int) $userid . '\''
				. ' AND `active` = \'1\''
				. ' AND `transaction_date` != \'0000-00-00 00:00:00\''
				;
		$db->setQuery( $query );
		return $db->loadResult();
	}

	function InvoiceNumberbyCartId( $userid, $cartid )
	{
		$db = &JFactory::getDBO();

		$query = 'SELECT `invoice_number`'
				. ' FROM #__acctexp_invoices'
				. ' WHERE `userid` = \'' . $userid . '\''
				. ' AND `usage` = \'c.' . $cartid . '\''
				;

		$db->setQuery( $query );
		return $db->loadResult();
	}

	function InvoiceIdList( $userid, $start, $limit, $sort='`transaction_date` DESC' )
	{
		$db = &JFactory::getDBO();

		$query = 'SELECT `id`'
				. ' FROM #__acctexp_invoices'
				. ' WHERE `userid` = \'' . $userid . '\''
				. ' AND `active` = \'1\''
				. ' ORDER BY ' . $sort . ', `id` DESC'
				. ' LIMIT ' . $start . ',' . $limit
				;
		$db->setQuery( $query );
		return $db->loadResultArray();
	}

	function SubscriptionIDfromUserID( $userid )
	{
		$db = &JFactory::getDBO();

		$query = 'SELECT `id`'
				. ' FROM #__acctexp_subscr'
				. ' WHERE `userid` = \'' . (int) $userid . '\''
				. ' ORDER BY `primary` DESC'
				;
		$db->setQuery( $query );
		return $db->loadResult();
	}

	function RecurringStatusfromSubscriptionID( $subscriptionid )
	{
		$db = &JFactory::getDBO();

		$query = 'SELECT `recurring`'
				. ' FROM #__acctexp_subscr'
				. ' WHERE `id` = \'' . (int) $subscriptionid . '\''
				. ' ORDER BY `primary` DESC'
				;
		$db->setQuery( $query );
		return $db->loadResult();
	}

	function UserIDfromUsername( $username )
	{
		$db = &JFactory::getDBO();

		$query = 'SELECT id'
		. ' FROM #__users'
		. ' WHERE username = \'' . aecEscape( $username, array( 'string', 'badchars' ) ) . '\''
		;
		$db->setQuery( $query );
		return $db->loadResult();
	}

	function UserIDfromSubscriptionID( $susbcriptionid )
	{
		$db = &JFactory::getDBO();

		$query = 'SELECT `userid`'
				. ' FROM #__acctexp_subscr'
				. ' WHERE `id` = \'' . (int) $susbcriptionid . '\''
				. ' ORDER BY `primary` DESC'
				;
		$db->setQuery( $query );
		return $db->loadResult();
	}

	function UserExists( $userid )
	{
		$db = &JFactory::getDBO();

		$query = 'SELECT `id`'
				. ' FROM #__users'
				. ' WHERE `id` = \'' . $userid . '\''
				;
		$db->setQuery( $query );
		return $db->loadResult();
	}
	
}

class reWriteEngine
{
	function reWriteEngine()
	{

	}

	function isRWEstring( $string )
	{
		if ( ( strpos( $string, '[[' ) !== false ) && ( strpos( $string, ']]' ) !== false ) ) {
			return true;
		}

		if ( ( strpos( $string, '{aecjson}' ) !== false ) && ( strpos( $string, '{/aecjson}' ) !== false ) ) {
			return true;
		}

		return false;
	}

	function info( $switches=array(), $params=null )
	{
		$lang = JFactory::getLanguage();

		if ( is_array( $switches ) ) {
			if ( !count( $switches ) ) {
				$switches = array( 'cms', 'user', 'subscription', 'invoice', 'plan', 'system' );
			}
		} else {
			if ( empty( $switches ) ) {
				$switches = array( 'cms', 'user', 'subscription', 'invoice', 'plan', 'system' );
			} else {
				$temp = $switches;
				$switches = array( $temp );
			}
		}

		$rewrite = array();

		if ( in_array( 'system', $switches ) ) {
			$rewrite['system'][] = 'timestamp';
			$rewrite['system'][] = 'timestamp_backend';
			$rewrite['system'][] = 'server_timestamp';
			$rewrite['system'][] = 'server_timestamp_backend';
		}

		if ( in_array( 'cms', $switches ) ) {
			$rewrite['cms'][] = 'absolute_path';
			$rewrite['cms'][] = 'live_site';
		}

		$newlang = array();

		if ( in_array( 'user', $switches ) ) {
			$rewrite['user'][] = 'id';
			$rewrite['user'][] = 'username';
			$rewrite['user'][] = 'name';
			$rewrite['user'][] = 'first_name';
			$rewrite['user'][] = 'first_first_name';
			$rewrite['user'][] = 'last_name';
			$rewrite['user'][] = 'email';
			$rewrite['user'][] = 'activationcode';
			$rewrite['user'][] = 'activationlink';

			if ( defined( 'JPATH_MANIFESTS' ) ) {
				$db = &JFactory::getDBO();

				$query = 'SELECT DISTINCT `profile_key`'
						. ' FROM #__user_profiles';
				$db->setQuery( $query );
				$pkeys = $db->loadResultArray();

				if ( is_array( $pkeys ) ) {
					foreach ( $pkeys as $pkey ) {
						$content = str_replace( ".", "_", $pkey );

						$rewrite['user'][] = $content;

						$name = 'REWRITE_KEY_USER_' . strtoupper( $content );
						if ( !$lang->hasKey( $name ) ) {
							$newlang[$name] = $content;
						}
					}
				}
			}

			if ( GeneralInfoRequester::detect_component( 'anyCB' ) ) {
				$db = &JFactory::getDBO();

				$query = 'SELECT name, title'
						. ' FROM #__comprofiler_fields'
						. ' WHERE `table` != \'#__users\''
						. ' AND name != \'NA\'';
				$db->setQuery( $query );
				$objects = $db->loadObjectList();

				if ( is_array( $objects ) ) {
					foreach ( $objects as $object ) {
						$rewrite['user'][] = $object->name;

						if ( strpos( $object->title, '_' ) === 0 ) {
							$content = $object->name;
						} else {
							$content = $object->title;
						}

						$name = 'REWRITE_KEY_USER_' . strtoupper( $object->name );
						if ( !$lang->hasKey( $name ) ) {
							$newlang[$name] = $content;
						}
					}
				}
			}

			if ( GeneralInfoRequester::detect_component( 'JOMSOCIAL' ) ) {
				$db = &JFactory::getDBO();

				$query = 'SELECT `id`, `name`'
						. ' FROM #__community_fields'
						. ' WHERE `type` != \'group\''
						;
				$db->setQuery( $query );
				$fields = $db->loadObjectList();

				if ( is_array( $fields ) ) {
					foreach ( $fields as $field ) {
						$rewrite['user'][] = 'js_' . $field->id;

						$content = $field->name;

						$name = 'REWRITE_KEY_USER_JS_' . $field->id;
						if ( !$lang->hasKey( $name ) ) {
							$newlang[$name] = $content;
						}
					}
				}
			}
		}

		if ( !empty( $newlang ) ) {
			if ( !isset( $lang->_strings ) ) {
				$lang->_strings = $newlang;
			} else {
				$lang->_strings = array_merge( $newlang, $lang->_strings );
			}
		}

		if ( in_array( 'subscription', $switches ) ) {
			$rewrite['subscription'][] = 'id';
			$rewrite['subscription'][] = 'type';
			$rewrite['subscription'][] = 'status';
			$rewrite['subscription'][] = 'signup_date';
			$rewrite['subscription'][] = 'lastpay_date';
			$rewrite['subscription'][] = 'plan';
			$rewrite['subscription'][] = 'previous_plan';
			$rewrite['subscription'][] = 'recurring';
			$rewrite['subscription'][] = 'lifetime';
			$rewrite['subscription'][] = 'expiration_date';
			$rewrite['subscription'][] = 'expiration_date_backend';
			$rewrite['subscription'][] = 'expiration_daysleft';
			$rewrite['subscription'][] = 'notes';
		}

		if ( in_array( 'invoice', $switches ) ) {
			$rewrite['invoice'][] = 'id';
			$rewrite['invoice'][] = 'number';
			$rewrite['invoice'][] = 'number_format';
			$rewrite['invoice'][] = 'created_date';
			$rewrite['invoice'][] = 'transaction_date';
			$rewrite['invoice'][] = 'method';
			$rewrite['invoice'][] = 'amount';
			$rewrite['invoice'][] = 'currency';
			$rewrite['invoice'][] = 'coupons';
		}

		if ( in_array( 'plan', $switches ) ) {
			$rewrite['plan'][] = 'name';
			$rewrite['plan'][] = 'desc';
			$rewrite['plan'][] = 'notes';
		}

		if ( !empty( $params ) ) {
			$params[] = array( 'accordion_start', 'small_accordion' );

			$params[] = array( 'accordion_itemstart', JText::_('REWRITE_ENGINE_TITLE') );
			$list = '<div class="rewriteinfoblock">' . "\n"
			. '<p>' . JText::_('REWRITE_ENGINE_DESC') . '</p>' . "\n"
			. '</div>' . "\n";
			$params[] = array( 'literal', $list );
			$params[] = array( 'accordion_itemend', '' );

			foreach ( $rewrite as $area => $keys ) {
				$params[] = array( 'accordion_itemstart', JText::_( 'REWRITE_AREA_' . strtoupper( $area ) ) );

				$list = '<div class="rewriteinfoblock">' . "\n"
				. '<ul>' . "\n";

				foreach ( $keys as $key ) {
					if ( $lang->hasKey( 'REWRITE_KEY_' . strtoupper( $area . "_" . $key ) ) ) {
						$list .= '<li>[[' . $area . "_" . $key . ']] =&gt; ' . JText::_( 'REWRITE_KEY_' . strtoupper( $area . "_" . $key ) ) . '</li>' . "\n";
					} else {
						$list .= '<li>[[' . $area . "_" . $key . ']] =&gt; ' . ucfirst( str_replace( '_', ' ', $key ) ) . '</li>' . "\n";
					}
				}
				$list .= '</ul>' . "\n"
				. '</div>' . "\n";

				$params[] = array( 'literal', $list );
				$params[] = array( 'accordion_itemend', '' );
			}

			$params[] = array( 'accordion_itemstart', JText::_('REWRITE_ENGINE_AECJSON_TITLE' ) );
			$list = '<div class="rewriteinfoblock">' . "\n"
			. '<p>' . JText::_('REWRITE_ENGINE_AECJSON_DESC') . '</p>' . "\n"
			. '</div>' . "\n";
			$params[] = array( 'literal', $list );
			$params[] = array( 'accordion_itemend', '' );

			$params[] = array( 'div_end', '' );

			return $params;
		} else {
			$return = '';
			foreach ( $rewrite as $area => $keys ) {
				$return .= '<div class="rewriteinfoblock">' . "\n"
				. '<p><strong>' . JText::_( 'REWRITE_AREA_' . strtoupper( $area ) ) . '</strong></p>' . "\n"
				. '<ul>' . "\n";

				foreach ( $keys as $key ) {
					if ( $lang->hasKey( 'REWRITE_KEY_' . strtoupper( $area . "_" . $key ) ) ) {
						$return .= '<li>[[' . $area . "_" . $key . ']] =&gt; ' . JText::_( 'REWRITE_KEY_' . strtoupper( $area . "_" . $key ) ) . '</li>' . "\n";
					} else {
						$return .= '<li>[[' . $area . "_" . $key . ']] =&gt; ' . ucfirst( str_replace( '_', ' ', $key ) ) . '</li>' . "\n";
					}
				}
				$return .= '</ul>' . "\n"
				. '</div>' . "\n";
			}

			$return .= '<div class="rewriteinfoblock">' . "\n"
			. '<p><strong>' . JText::_('REWRITE_ENGINE_AECJSON_TITLE') . '</strong></p>' . "\n"
			. '<p>' . JText::_('REWRITE_ENGINE_AECJSON_DESC') . '</p>' . "\n"
			. '</div>' . "\n";

			return $return;
		}
	}

	function resolveRequest( $request )
	{
		$rqitems = get_object_vars( $request );

		$data = array();

		foreach ( $rqitems as $rqitem => $content ) {
			if ( is_object( $content ) || is_array( $content ) ) {
				$data[$rqitem] = $content;
			}
		}

		$this->feedData( $data );

		return true;
	}

	function feedData( $data )
	{
		if ( !isset( $this->data ) ) {
			$this->data = $data;

			return true;
		}

		foreach ( $data as $name => $content ) {
			$this->data[$name] = $content;
		}

		return true;
	}

	function armRewrite()
	{
		$db = &JFactory::getDBO();

		$app = JFactory::getApplication();

		global $aecConfig;

		$this->rewrite = array();

		$this->rewrite['system_timestamp']					= AECToolbox::formatDate( ( (int) gmdate('U') ), false, false );
		$this->rewrite['system_timestamp_backend']			= AECToolbox::formatDate( (int) gmdate('U'), true, false );
		$this->rewrite['system_serverstamp_time']			= AECToolbox::formatDate( ( (int) gmdate('U') ) );
		$this->rewrite['system_server_timestamp_backend']	= AECToolbox::formatDate( ( (int) gmdate('U') ), true );

		$this->rewrite['cms_absolute_path']	= JPATH_SITE;
		$this->rewrite['cms_live_site']		= JURI::root();

		if ( empty( $this->data['invoice'] ) ) {
			$this->data['invoice'] = null;
		}

		if ( !empty( $this->data['metaUser'] ) ) {
			if ( is_object( $this->data['metaUser'] ) ) {
				if ( isset( $this->data['metaUser']->cmsUser->id ) ) {
					$this->rewrite['user_id']				= $this->data['metaUser']->cmsUser->id;
				} else {
					$this->rewrite['user_id']				= 0;
				}

				if ( !empty( $this->data['metaUser']->cmsUser->username ) ) {
					$this->rewrite['user_username']			= $this->data['metaUser']->cmsUser->username;
				} else {
					$this->rewrite['user_username']				= "";
				}

				if ( !empty( $this->data['metaUser']->cmsUser->name ) ) {
					$this->rewrite['user_name']				= $this->data['metaUser']->cmsUser->name;
				} else {
					$this->rewrite['user_name']				= "";
				}

				$name = $this->data['metaUser']->explodeName();

				$this->rewrite['user_first_name']		= $name['first'];
				$this->rewrite['user_first_first_name']	= $name['first_first'];
				$this->rewrite['user_last_name']		= $name['last'];

				if ( !empty( $this->data['metaUser']->cmsUser->email ) ) {
					$this->rewrite['user_email']			= $this->data['metaUser']->cmsUser->email;
				} else {
					$this->rewrite['user_name']				= "";
				}

				if ( defined( 'JPATH_MANIFESTS' ) ) {
					if ( empty( $this->data['metaUser']->hasJProfile ) ) {
						$this->data['metaUser']->loadJProfile();
					}

					if ( !empty( $this->data['metaUser']->hasJProfile ) ) {
						foreach ( $this->data['metaUser']->jProfile as $field => $value ) {
							$this->rewrite['user_'.$field] = $value;
						}
					}
				}

				if ( GeneralInfoRequester::detect_component( 'JOMSOCIAL' ) ) {
					if ( !$this->data['metaUser']->hasJSprofile ) {
						$this->data['metaUser']->loadJSuser();
					}

					if ( !empty( $this->data['metaUser']->hasJSprofile ) ) {
						foreach ( $this->data['metaUser']->jsUser as $field ) {
							$this->rewrite['user_js_' . $field->field_id] = $field->value;
						}
					}
				}

				if ( GeneralInfoRequester::detect_component( 'anyCB' ) ) {
					if ( !$this->data['metaUser']->hasCBprofile ) {
						$this->data['metaUser']->loadCBuser();
					}

					if ( !empty( $this->data['metaUser']->hasCBprofile ) ) {
						$fields = get_object_vars( $this->data['metaUser']->cbUser );

						if ( !empty( $fields ) ) {
							foreach ( $fields as $fieldname => $fieldcontents ) {
								$this->rewrite['user_' . $fieldname] = $fieldcontents;
							}
						}

						if ( isset( $this->data['metaUser']->cbUser->cbactivation ) ) {
							$this->rewrite['user_activationcode']		= $this->data['metaUser']->cbUser->cbactivation;
							$this->rewrite['user_activationlink']		= JURI::root()."index.php?option=com_comprofiler&task=confirm&confirmcode=" . $this->data['metaUser']->cbUser->cbactivation;
						} else {
							$this->rewrite['user_activationcode']		= "";
							$this->rewrite['user_activationlink']		= "";
						}
					} else {
						if ( isset( $this->data['metaUser']->cmsUser->activation ) ) {
							$this->rewrite['user_activationcode']		= $this->data['metaUser']->cmsUser->activation;

							if ( defined( 'JPATH_MANIFESTS' ) ) {
								$this->rewrite['user_activationlink']	= 'index.php?option=com_users&amp;task=registration.activate&amp;token=' . $this->data['metaUser']->cmsUser->activation;
							} else {
								$this->rewrite['user_activationlink']	= 'index.php?option=com_user&amp;task=activate&amp;activation=' . $this->data['metaUser']->cmsUser->activation;
							}
						} else {
							$this->rewrite['user_activationcode']		= "";
							$this->rewrite['user_activationlink']		= "";
						}
					}
				} else {
					if ( isset( $this->data['metaUser']->cmsUser->activation ) ) {
						$this->rewrite['user_activationcode']			= $this->data['metaUser']->cmsUser->activation;
						$this->rewrite['user_activationlink']		= JURI::root()."index.php?option=com_user&task=activate&activation=" . $this->data['metaUser']->cmsUser->activation;
					}
				}

				if ( !empty( $this->data['metaUser']->meta->custom_params ) ) {
					foreach ( $this->data['metaUser']->meta->custom_params as $k => $v ) {
						if ( is_array( $v ) ) {
							foreach ( $v as $xk => $xv ) {
								if ( is_array( $xv ) ) {
									foreach ( $xv as $xyk => $xyv ) {
										$this->rewrite['user_'.$k.'_'.$xk.'_'.$xyk] = $xyv;
									}
								} else {
									$this->rewrite['user_'.$k.'_'.$xk] = $xv;
								}
							}
						} else {
							$this->rewrite['user_' . $k] = $v;
						}
					}
				}

				if ( $this->data['metaUser']->hasSubscription ) {
					$this->rewrite['subscription_id']				= $this->data['metaUser']->focusSubscription->id;
					$this->rewrite['subscription_type']				= $this->data['metaUser']->focusSubscription->type;
					$this->rewrite['subscription_status']			= $this->data['metaUser']->focusSubscription->status;
					$this->rewrite['subscription_signup_date']		= $this->data['metaUser']->focusSubscription->signup_date;
					$this->rewrite['subscription_lastpay_date']		= $this->data['metaUser']->focusSubscription->lastpay_date;
					$this->rewrite['subscription_plan']				= $this->data['metaUser']->focusSubscription->plan;

					if ( !empty( $this->data['metaUser']->focusSubscription->previous_plan ) ) {
						$this->rewrite['subscription_previous_plan']	= $this->data['metaUser']->focusSubscription->previous_plan;
					} else {
						$this->rewrite['subscription_previous_plan']	= "";
					}

					$this->rewrite['subscription_recurring']		= $this->data['metaUser']->focusSubscription->recurring;
					$this->rewrite['subscription_lifetime']			= $this->data['metaUser']->focusSubscription->lifetime;
					$this->rewrite['subscription_expiration_date']	= AECToolbox::formatDate( $this->data['metaUser']->focusSubscription->expiration );
					$this->rewrite['subscription_expiration_date_backend']	= AECToolbox::formatDate( $this->data['metaUser']->focusSubscription->expiration, true );

					$this->rewrite['subscription_expiration_daysleft']	= round( ( strtotime( $this->data['metaUser']->focusSubscription->expiration ) - ( (int) gmdate('U') ) ) / 86400 );

					if ( !empty( $this->data['metaUser']->focusSubscription->customparams['notes'] ) ) {
						$this->rewrite['subscription_notes']		=  $this->data['metaUser']->focusSubscription->customparams['notes'];
					} else {
						$this->rewrite['subscription_notes']		=  '';
					}
				}

				if ( empty( $this->data['invoice'] ) && !empty( $this->data['metaUser']->cmsUser->id ) ) {
					$lastinvoice = AECfetchfromDB::lastClearedInvoiceIDbyUserID( $this->data['metaUser']->cmsUser->id );

					$this->data['invoice'] = new Invoice( $db );
					$this->data['invoice']->load( $lastinvoice );
				}
			}
		}

		if ( is_object( $this->data['invoice'] ) ) {
			if ( !empty( $this->data['invoice']->id ) ) {
				$this->rewrite['invoice_id']				= $this->data['invoice']->id;
				$this->rewrite['invoice_number']			= $this->data['invoice']->invoice_number;
				$this->rewrite['invoice_created_date']		= $this->data['invoice']->created_date;
				$this->rewrite['invoice_transaction_date']	= $this->data['invoice']->transaction_date;
				$this->rewrite['invoice_method']			= $this->data['invoice']->method;
				$this->rewrite['invoice_amount']			= $this->data['invoice']->amount;
				$this->rewrite['invoice_currency']			= $this->data['invoice']->currency;

				if ( !empty( $this->data['invoice']->coupons ) && is_array( $this->data['invoice']->coupons ) ) {
					$this->rewrite['invoice_coupons']		=  implode( ';', $this->data['invoice']->coupons );
				} else {
					$this->rewrite['invoice_coupons']		=  '';
				}

				if ( !empty( $this->data['metaUser'] ) && !empty( $this->data['invoice'] ) ) {
					if ( !empty( $this->data['invoice']->id ) ) {
						$this->data['invoice']->formatInvoiceNumber();
						$this->rewrite['invoice_number_format']	= $this->data['invoice']->invoice_number;
						$this->data['invoice']->deformatInvoiceNumber();
					}
				}
			}
		}

		if ( !empty( $this->data['plan'] ) ) {
			if ( is_object( $this->data['plan'] ) ) {
				$this->rewrite['plan_name'] = $this->data['plan']->getProperty( 'name' );
				$this->rewrite['plan_desc'] = $this->data['plan']->getProperty( 'desc' );

				if ( !empty( $this->data['plan']->params['notes'] ) ) {
					$this->rewrite['plan_notes'] = $this->data['plan']->params['notes'];
				} else {
					$this->rewrite['plan_notes'] = '';
				}
			}
		}
	}

	function resolve( $subject )
	{
		// Check whether a replacement exists at all
		if ( ( strpos( $subject, '[[' ) === false ) && ( strpos( $subject, '{aecjson}' ) === false ) ) {
			return $subject;
		}

		if ( empty( $this->rewrite ) ) {
			$this->armRewrite();
		}

		if ( strpos( $subject, '{aecjson}' ) !== false ) {
			if ( ( strpos( $subject, '[[' ) !== false ) && ( strpos( $subject, ']]' ) !== false ) ) {
				// also found classic tags, doing that rewrite first
				$subject = $this->classicRewrite( $subject );
			}

			// We have at least one JSON object, switching to JSON mode
			return $this->decodeTags( $subject );
		} else {
			// No JSON found, do traditional parsing
			return $this->classicRewrite( $subject );
		}
	}

	function classicRewrite( $subject )
	{
		$search = array();
		$replace = array();
		foreach ( $this->rewrite as $name => $replacement ) {
			if ( is_array( $replacement ) ) {var_dump($name);var_dump($replacement);
				$replacement = implode( $replacement );
			}

			$search[]	= '[[' . $name . ']]';
			$replace[]	= $replacement;
		}

		return str_replace( $search, $replace, $subject );
	}

	function decodeTags( $subject )
	{
		// Example:
		// {aecjson} {"cmd":"concat","vars":["These ",{"cmd":"condition","vars":{"cmd":"compare","vars":["apples","=","oranges"]},"appl","orang"},"es"} {/aecjson}
		// ...would return either "These apples" or "These oranges", depending on whether the compare function thinks that they are the same

		$regex = "#{aecjson}(.*?){/aecjson}#s";

		// find all instances of json code
		$matches = array();
		preg_match_all( $regex, $subject, $matches, PREG_SET_ORDER );

		if ( count( $matches ) < 1 ) {
			return $subject;
		}

		foreach ( $matches as $match ) {
			$json = jsoonHandler::decode( $match[1] );

			$result = $this->resolveJSONitem( $json );

			$subject = str_replace( $match, $result, $subject );
		}

		return $subject;
	}

	function resolveJSONitem( $current, $safe=false )
	{
		if ( is_object( $current ) ) {
			if ( !isset( $current->cmd ) || !isset( $current->vars ) ) {
				// Malformed String
				return "JSON PARSE ERROR - Malformed String!";
			}

			$variables = $this->resolveJSONitem( $current->vars, $safe );

			$current = $this->executeCommand( $current->cmd, $variables );
		} elseif ( is_array( $current ) ) {
			foreach( $current as $id => $item ) {
				$current[$id] = $this->resolveJSONitem( $item, $safe );
			}
		}

		return $current;
	}

	function executeCommand( $command, $vars, $safe=false )
	{
		$result = '';
		switch( $command ) {
			case 'rw_constant':
				if ( isset( $this->rewrite[$vars] ) ) {
					$result = $this->rewrite[$vars];
				}
				break;
			case 'data':
				if ( empty( $this->data ) ) {
					return false;
				}

				$result = AECToolbox::getObjectProperty( $this->data, $vars );
				break;
			case 'safedata':
				if ( empty( $this->data ) ) {
					return false;
				}

				if ( AECToolbox::getObjectProperty( $this->data, $vars, true ) ) {
					$result = AECToolbox::getObjectProperty( $this->data, $vars );
				}
				break;
			case 'checkdata':
				if ( empty( $this->data ) ) {
					return false;
				}

				$result = AECToolbox::getObjectProperty( $this->data, $vars, true );
				break;
			case 'checkdata_notempty':
				if ( empty( $this->data ) ) {
					return false;
				}

				$check = AECToolbox::getObjectProperty( $this->data, $vars, true );

				if ( AECToolbox::getObjectProperty( $this->data, $vars, true ) ) {
					$check = AECToolbox::getObjectProperty( $this->data, $vars );

					$result = !empty( $check );
				}
				break;
			case 'metaUser':
				if ( !is_object( $this->data['metaUser'] ) ) {
					return false;
				}

				// We also support dot notation for the vars,
				// so explode if that is what the admin wants here
				if ( !is_array( $vars ) && ( strpos( $vars, '.' ) !== false ) ) {
					$temp = explode( '.', $vars );
					$vars = $temp;
				} elseif ( !is_array( $vars ) ) {
					return false;
				}

				$result = $this->data['metaUser']->getProperty( $vars );
				break;
			case 'invoice_count':
				if ( !is_object( $this->data['metaUser'] ) ) {
					return false;
				}

				return AECfetchfromDB::InvoiceCountbyUserID( $this->data['metaUser']->userid );

				break;
			case 'invoice_count_paid':
				if ( !is_object( $this->data['metaUser'] ) ) {
					return false;
				}

				return AECfetchfromDB::PaidInvoiceCountbyUserID( $this->data['metaUser']->userid );

				break;
			case 'invoice_count_unpaid':
				if ( !is_object( $this->data['metaUser'] ) ) {
					return false;
				}

				return AECfetchfromDB::UnpaidInvoiceCountbyUserID( $this->data['metaUser']->userid );

				break;
			case 'jtext':
				$result = JText::_( $vars );
				break;
			case 'constant':
				if ( defined( $vars ) ) {
					$result = constant( $vars );
				} else {
					$result = JText::_( $vars );
				}
				break;
			case 'global':
				if ( is_array( $vars ) ) {
					if ( isset( $vars[0] ) && isset( $vars[1] ) ) {
						$call = strtoupper( $vars[0] );

						$v = $vars[1];

						$allowed = array( 'SERVER', 'GET', 'POST', 'FILES', 'COOKIE', 'SESSION', 'REQUEST', 'ENV' );

						if ( in_array( $call, $allowed ) ) {
							switch ( $call ) {
								case 'SERVER':
									if ( isset( $_SERVER[$v] ) && !$safe ) {
										$result = $_SERVER[$v];
									}
									break;
								case 'GET':
									if ( isset( $_GET[$v] ) ) {
										$result = $_GET[$v];
									}
									break;
								case 'POST':
									if ( isset( $_POST[$v] ) ) {
										$result = $_POST[$v];
									}
									break;
								case 'FILES':
									if ( isset( $_FILES[$v] ) && !$safe ) {
										$result = $_FILES[$v];
									}
									break;
								case 'COOKIE':
									if ( isset( $_COOKIE[$v] ) ) {
										$result = $_COOKIE[$v];
									}
									break;
								case 'SESSION':
									if ( isset( $_SESSION[$v] ) ) {
										$result = $_SESSION[$v];
									}
									break;
								case 'REQUEST':
									if ( isset( $_REQUEST[$v] ) ) {
										$result = $_REQUEST[$v];
									}
									break;
								case 'ENV':
									if ( isset( $_ENV[$v] ) && !$safe ) {
										$result = $_ENV[$v];
									}
									break;
							}
						}
					}
				} else {
					if ( isset( $GLOBALS[$vars] ) ) {
						$result = $GLOBALS[$vars];
					}
				}
				break;
			case 'condition':
				if ( empty( $vars[0] ) || !isset( $vars[1] ) ) {
					if ( isset( $vars[2] ) ) {
						$result = $vars[2];
					} else {
						$result = '';
					}
				} elseif ( isset( $vars[1] ) ) {
					$result = $vars[1];
				} else {
					$result = '';
				}
				break;
			case 'hastext':
				$result = ( strpos( $vars[0], $vars[1] ) !== false ) ? 1 : 0;
				break;
			case 'uppercase':
				$result = strtoupper( $vars );
				break;
			case 'lowercase':
				$result = strtoupper( $vars );
				break;
			case 'concat':
				$result = implode( $vars );
				break;
			case 'date':
				$result = date( $vars[0], strtotime( $vars[1] ) );
				break;
			case 'date_distance':
				$result = round( $vars - ( (int) gmdate('U') ) );
				break;
			case 'date_distance_days':
				$result = round( ( $vars - ( (int) gmdate('U') ) ) / 86400 );
				break;
			case 'crop':
				if ( isset( $vars[2] ) ) {
					$result = substr( $vars[0], (int) $vars[1], (int) $vars[2] );
				} else {
					$result = substr( $vars[0], (int) $vars[1] );
				}
				break;
			case 'pad':
				if ( isset( $vars[3] ) ) {
					$result = str_pad( $vars[0], (int) $vars[1], $vars[2], JText::_( "STR_PAD_" . strtoupper( $vars[3] ) ) );
				} elseif ( isset( $vars[2] ) ) {
					$result = str_pad( $vars[0], (int) $vars[1], $vars[2] );
				} else {
					$result = str_pad( $vars[0], (int) $vars[1] );
				}
				break;
			case 'chunk':
				$chunks = str_split( $vars[0], (int) $vars[1] );

				if ( isset( $vars[2] ) ) {
					$result = implode( $vars[2], $chunks );
				} else {
					$result = implode( ' ', $chunks );
				}
				break;
			case 'compare':
				if ( isset( $vars[2] ) ) {
					$result = AECToolbox::compare( $vars[1], $vars[0], $vars[2] );
				} else {
					$result = 0;
				}
				break;
			case 'math':
				if ( isset( $vars[2] ) ) {
					$result = AECToolbox::math( $vars[1], (float) $vars[0], (float) $vars[2] );
				} else {
					$result = 0;
				}
				break;
			case 'randomstring':
				$result = AECToolbox::randomstring( (int) $vars );
				break;
			case 'randomstring_alphanum':
				$result = AECToolbox::randomstring( (int) $vars, true );
				break;
			case 'randomstring_alphanum_large':
				$result = AECToolbox::randomstring( (int) $vars, true, true );
				break;
			case 'php_function':
				if ( !$safe ) {
					if ( isset( $vars[1] ) ) {
						$result = call_user_func_array( $vars[0], $vars[1] );
					} else {
						$result = call_user_func_array( $vars[0] );
					}
				}
				break;
			case 'php_method':
				if ( !$safe ) {
					if ( function_exists( 'call_user_method_array' ) ) {
						if ( isset( $vars[2] ) ) {
							$result = call_user_method_array( $vars[0], $vars[1], $vars[2] );
						} else {
							$result = call_user_method_array( $vars[0], $vars[1] );
						}
					} else {
						$callback = array( $vars[0], $vars[1] );
	
						if ( isset( $vars[2] ) ) {
							$result = call_user_func_array( $callback, $vars[2] );
						} else {
							$result = call_user_func_array( $callback );
						}
					}
				}
				break;
			default:
				$result = $command . ' is no command';
				break;
		}

		return $result;
	}

	function explain( $subject )
	{
		// Check whether a replacement exists at all
		if ( ( strpos( $subject, '[[' ) === false ) && ( strpos( $subject, '{aecjson}' ) === false ) ) {
			return $subject;
		}

		if ( empty( $this->rewrite ) ) {
			$this->armRewrite();
		}

		if ( strpos( $subject, '{aecjson}' ) !== false ) {
			if ( ( strpos( $subject, '[[' ) !== false ) && ( strpos( $subject, ']]' ) !== false ) ) {
				// also found classic tags, doing that rewrite first
				$subject = $this->classicExplain( $subject );
			}

			// We have at least one JSON object, switching to JSON mode
			return $this->explainTags( $subject );
		} else {
			// No JSON found, do traditional parsing
			return $this->classicExplain( $subject );
		}
	}

	function classicExplain( $subject )
	{
		$regex = "#\[\[(.*?)\]\]#s";

		// find all instances of json code
		$matches = array();
		preg_match_all( $regex, $subject, $matches, PREG_SET_ORDER );

		foreach ( $matches as $match ) {
			$subject = str_replace( $match[0], $match[1], $subject );
		}

		return $subject;
	}

	function explainTags( $subject )
	{
		$regex = "#{aecjson}(.*?){/aecjson}#s";

		// find all instances of json code
		$matches = array();
		preg_match_all( $regex, $subject, $matches, PREG_SET_ORDER );

		if ( count( $matches ) < 1 ) {
			return $subject;
		}

		foreach ( $matches as $match ) {
			$json = jsoonHandler::decode( $match[1] );

			$result = $this->explainJSONitem( $json );

			$subject = str_replace( $match, $result, $subject );
		}

		return $subject;
	}

	function explainJSONitem( $current )
	{
		if ( is_object( $current ) ) {
			if ( !isset( $current->cmd ) || !isset( $current->vars ) ) {
				// Malformed String
				return "JSON PARSE ERROR - Malformed String!";
			}

			$variables = $this->explainJSONitem( $current->vars );

			$current = $this->explainCommand( $current->cmd, $variables );
		} elseif ( is_array( $current ) ) {
			foreach( $current as $id => $item ) {
				$current[$id] = $this->explainJSONitem( $item );
			}
		}

		return $current;
	}

	function explainCommand( $command, $vars )
	{
		switch( $command ) {
			case 'rw_constant': return $vars; break;
			case 'checkdata_notempty':
			case 'checkdata':
			case 'safedata':
			case 'data':
				if ( empty( $this->data ) ) {
					return false;
				} elseif ( is_array( $vars ) ) {
					$vars = implode( '.', $vars );
				}

				return $vars;
				break;
			case 'metaUser':
				if ( !is_object( $this->data['metaUser'] ) ) {
					return false;
				} elseif ( is_array( $vars ) ) {
					$vars = implode( '.', $vars );
				}

				return 'metaUser.'.$vars;
				break;
			case 'jtext': return JText::_( $vars ); break;
			case 'constant': return $vars; break;
			case 'global':
				if ( is_array( $vars ) ) {
					if ( isset( $vars[0] ) && isset( $vars[1] ) ) {
						return $vars[0].'.'.$vars[1];
					}
				} else {
					return $vars;
				}
				break;
			case 'condition':
				if ( isset( $vars[2] ) ) {
					$result = $vars[2];
					return $command.':'.$vars[1].'||'.$vars[2].'?'; break;
				} else {
					return $command.':'.$vars[1].'?'; break;
				}
				break;
			case 'hastext': return $command.'-'.$vars[1]; break;
			case 'lowercase':
			case 'uppercase': return $command.'-'.$vars; break;
			case 'concat': return $command.'-'.implode( '|', $vars ); break;
			case 'date': return $command.'-'.$vars[0]; break;
			case 'date_distance':
			case 'date_distance_days': return $command.'-'.$vars; break;
			case 'crop':
				if ( isset( $vars[2] ) ) {
					return $command.'-'.$vars[0].'['.(int) $vars[1].'-'.(int) $vars[2].']'; break;
				} else {
					return $command.'-'.$vars[0].'['.(int) $vars[1].']'; break;
				}
				break;
			case 'pad':
				if ( isset( $vars[3] ) ) {
					return $command.'-'.$vars[0].'['.(int) $vars[1].'-'.(int) $vars[2].'-'.(int) $vars[3].']'; break;
				} elseif ( isset( $vars[2] ) ) {
					return $command.'-'.$vars[0].'['.(int) $vars[1].'-'.(int) $vars[2].']'; break;
				} else {
					return $command.'-'.$vars[0].'['.(int) $vars[1].']'; break;
				}
				break;
			case 'chunk':
				if ( isset( $vars[2] ) ) {
					return $command.'-'.$vars[0].'['.(int) $vars[1].'-'.(int) $vars[2].']'; break;
				} else {
					return $command.'-'.$vars[0].'['.(int) $vars[1].']'; break;
				}
				break;
			case 'compare':
				if ( isset( $vars[2] ) ) {
					return $vars[0].$vars[1].$vars[2];
				} else {
					return $command;
				}
				break;
			case 'math':
				if ( isset( $vars[2] ) ) {
					return $vars[0].$vars[1].$vars[2];
				} else {
					return $command;
				}
				break;
			case 'randomstring':
			case 'randomstring_alphanum':
			case 'randomstring_alphanum_large':
				return $command;
				break;
			case 'php_function':
				return $command.'-'.$vars[0];
			case 'php_method':
				if ( isset( $vars[2] ) ) {
					return $command.'-'.get_class($vars[0]).'::'. $vars[1].'[' . implode(',',$vars[2]) . ']';
				} else {
					return $command.'-'.get_class($vars[0]).'::'. $vars[1];
				}
				break;
			default: return $command . ' is no command'; break;
		}
	}

}

class AECToolbox
{
	/**
	 * Builds a list of valid currencies
	 *
	 * @param bool	$currMain	main (most important currencies)
	 * @param bool	$currGen	second important currencies
	 * @param bool	$currOth	rest of the world currencies
	 * @since 0.12.4
	 * @return array
	 */
	function aecCurrencyField( $currMain = false, $currGen = false, $currOth = false, $list_only = false )
	{
		$currencies = array();

		if ( $currMain ) {
			$currencies[] = 'EUR,USD,CHF,CAD,DKK,SEK,NOK,GBP,JPY';
		}

		if ( $currGen ) {
			$currencies[]	= 'AUD,CYP,CZK,EGP,HUF,GIP,HKD,UAH,ISK,'
			. 'EEK,HRK,GEL,LVL,RON,BGN,LTL,MTL,FIM,MDL,ILS,NZD,ZAR,RUB,SKK,'
			. 'TRY,PLN'
			;
		}

		if ( $currOth ) {
			$currencies[]	= 'AFA,DZD,ARS,AMD,AWG,AZM,'
			. 'BSD,BHD,THB,PAB,BBD,BYB,BZD,BMD,VEB,BOB,'
			. 'BRL,BND,BIF,CVE,KYD,GHC,XOF,XAF,XPF,CLP,'
			. 'COP,KMF,BAM,NIO,CRC,CUP,GMD,MKD,AED,DJF,'
			. 'STD,DOP,VND,XCD,SVC,ETB,FKP,FJD,CDF,FRF,'
			. 'HTG,PYG,GNF,GWP,GYD,HKD,UAH,INR,IRR,IQD,'
			. 'JMD,JOD,KES,PGK,LAK,KWD,MWK,ZMK,AOR,MMK,'
			. 'LBP,ALL,HNL,SLL,LRD,LYD,SZL,LSL,MGF,MYR,'
			. 'TMM,MUR,MZM,MXN,MXV,MAD,ERN,NAD,NPR,ANG,'
			. 'AON,TWD,ZRN,BTN,KPW,PEN,MRO,TOP,PKR,XPD,'
			. 'MOP,UYU,PHP,XPT,BWP,QAR,GTQ,ZAL,OMR,KHR,'
			. 'MVR,IDR,RWF,SAR,SCR,XAG,SGD,SBD,KGS,SOS,'
			. 'LKR,SHP,ECS,SDD,SRG,SYP,TJR,BDT,WST,TZS,'
			. 'KZT,TPE,TTD,MNT,TND,UGX,ECV,CLF,USN,USS,'
			. 'UZS,VUV,KRW,YER,CNY,ZWD'
			;
		}

		if ( $list_only ) {
			$currency_code_list = implode( ',', $currencies);
		} else {
			$currency_code_list = array();

			foreach ( $currencies as $currencyfield ) {
				$currency_array = explode( ',', $currencyfield );
				foreach ( $currency_array as $currency ) {
					$currency_code_list[] = JHTML::_('select.option', $currency, JText::_( 'CURRENCY_' . $currency ) );
				}

				$currency_code_list[] = JHTML::_('select.option', '" disabled="disabled', '- - - - - - - - - - - - - -' );
			}
		}

		return $currency_code_list;
	}

	function aecNumCurrency( $string )
	{
		$iso4217num = array( 'AED' => '784', 'AFN' => '971', 'ALL' => '008' ,'AMD' => '051', 'ANG' => '532',
							'AOA' => '973', 'ARS' => '032', 'AUD' => '036', 'AWG' => '533', 'AZN' => '944',
							'BAM' => '977', 'BBD' => '052', 'BDT' => '050', 'BGN' => '975', 'BHD' => '048',
							'BIF' => '108', 'BMD' => '060', 'BND' => '096', 'BOB' => '068', 'BOV' => '984',
							'BRL' => '986', 'BSD' => '044', 'BTN' => '064', 'BWP' => '072', 'BYR' => '974',
							'BZD' => '084', 'CAD' => '124', 'CDF' => '976', 'CHE' => '947', 'CHF' => '756',
							'CHW' => '948', 'CLF' => '990', 'CLP' => '152', 'CNY' => '156', 'COP' => '170',
							'COU' => '970', 'CRC' => '188', 'CUP' => '192', 'CVE' => '132', 'CZK' => '203',
							'DJF' => '262', 'DKK' => '208', 'DOP' => '214', 'DZD' => '012', 'EEK' => '233',
							'EGP' => '818', 'ERN' => '232', 'ETB' => '230', 'EUR' => '978', 'FJD' => '242',
							'FKP' => '238', 'GBP' => '826', 'GEL' => '981', 'GHS' => '936', 'GIP' => '292',
							'GMD' => '270', 'GNF' => '324', 'GTQ' => '320', 'GYD' => '328', 'HKD' => '344',
							'HNL' => '340', 'HRK' => '191', 'HTG' => '332', 'HUF' => '348', 'IDR' => '360',
							'ILS' => '376', 'INR' => '356', 'IQD' => '368', 'IRR' => '364', 'ISK' => '352',
							'JMD' => '388', 'JOD' => '400', 'JPY' => '392', 'KES' => '404', 'KGS' => '417',
							'KHR' => '116', 'KMF' => '174', 'KPW' => '408', 'KRW' => '410', 'KWD' => '414',
							'KYD' => '136', 'KZT' => '398', 'LAK' => '418', 'LBP' => '422', 'LKR' => '144',
							'LRD' => '430', 'LSL' => '426', 'LTL' => '440', 'LVL' => '428', 'LYD' => '434',
							'MAD' => '504', 'MDL' => '498', 'MGA' => '969', 'MKD' => '807', 'MMK' => '104',
							'MNT' => '496', 'MOP' => '446', 'MRO' => '478', 'MUR' => '480', 'MVR' => '462',
							'MWK' => '454', 'MXN' => '484', 'MXV' => '979', 'MYR' => '458', 'MZN' => '943',
							'NAD' => '516', 'NGN' => '566', 'NIO' => '558', 'NOK' => '578', 'NPR' => '524',
							'NZD' => '554', 'OMR' => '512', 'PAB' => '590', 'PEN' => '604', 'PGK' => '598',
							'PHP' => '608', 'PKR' => '586', 'PLN' => '985', 'PYG' => '600', 'QAR' => '634',
							'RON' => '946', 'RSD' => '941', 'RUB' => '643', 'RWF' => '646', 'SAR' => '682',
							'SBD' => '090', 'SCR' => '690', 'SDG' => '938', 'SEK' => '752', 'SGD' => '702',
							'SHP' => '654', 'SKK' => '703', 'SLL' => '694', 'SOS' => '706', 'SRD' => '968',
							'STD' => '678', 'SYP' => '760', 'SZL' => '748', 'THB' => '764', 'TJS' => '972',
							'TMM' => '795', 'TND' => '788', 'TOP' => '776', 'TRY' => '949', 'TTD' => '780',
							'TWD' => '901', 'TZS' => '834', 'UAH' => '980', 'UGX' => '800', 'USD' => '840',
							'USN' => '997', 'USS' => '998', 'UYU' => '858', 'UZS' => '860', 'VEF' => '937',
							'VND' => '704', 'VUV' => '548', 'WST' => '882', 'XAF' => '950', 'XAG' => '961',
							'XAU' => '959', 'XBA' => '955', 'XBB' => '956', 'XBC' => '957', 'XBD' => '958',
							'XCD' => '951', 'XDR' => '960', 'XFU' => 'Nil', 'XOF' => '952', 'XPD' => '964',
							'XPF' => '953', 'XPT' => '962', 'XTS' => '963', 'XXX' => '999', 'YER' => '886',
							'ZMK' => '894', 'ZWD' => '716'
							);

		if ( isset( $iso4217num[$string] ) ) {
			return $iso4217num[$string];
		} else {
			return '';
		}
	}

	function aecCurrencyExp( $string )
	{
		$iso4217exp3 = array( 'BHD', 'IQD', 'JOD', 'KRW', 'LYD', 'OMR', 'TND'  );
		$iso4217exp0 = array( 'BIF', 'BYR', 'CLF', 'CLP', 'DJF', 'GNF', 'ISK', 'JPY', 'KMF', 'KRW',
								'PYG', 'RWF', 'VUV', 'XAF', 'XAG', 'XAU', 'XBA', 'XBB', 'XBC', 'XBD',
								'XDR', 'XFU', 'XOF', 'XPD', 'XPF', 'XPT', 'XTS', 'XXX' );
		$iso4217exp07 = array( 'MGA', 'MRO' );

		if ( in_array( $string, $iso4217exp0 ) ) {
			return 0;
		} elseif ( in_array( $string, $iso4217exp3 ) ) {
			return 3;
		} elseif ( in_array( $string, $iso4217exp07 ) ) {
			return 0.7;
		} else {
			return 2;
		}
	}

	function getCountryCodeList( $format=null )
	{
		global $aecConfig;

		$regular = AECToolbox::getISO3166_1a2_codes();

		if ( !empty( $aecConfig->cfg['countries_available'] ) ) {
			$countries = $aecConfig->cfg['countries_available'];
		} else {
			$countries = $regular;
		}

		if ( !empty( $aecConfig->cfg['countries_top'] ) ) {
			// Merge top countries to beginning of list
			$countries = array_merge( $aecConfig->cfg['countries_top'], array( null ), $countries );
		}

		if ( $format ) {
			switch ( $format ) {
				case 'a2':
					return $countries;
					break;
				default:
					$conversion = AECToolbox::ISO3166_conversiontable( 'a2', $format );

					$newlist = array();
					foreach ( $countries as $c ) {
						$newlist[] = $conversion[$c];
					}

					return $newlist;
					break;
			}
		} else {
			return $countries;
		}
	}

	function ISO3166_conversiontable( $type1, $type2 )
	{
		$list1 = call_user_func( array( 'AECToolbox', 'getISO3166_1' . $type1 . '_codes' ) );
		$list2 = call_user_func( array( 'AECToolbox', 'getISO3166_1' . $type2 . '_codes' ) );

		return array_combine( $list1, $list2 );
	}

	function getISO3166_1a2_codes()
	{
		return array(	'AF','AX','AL','DZ','AS','AD','AO','AI','AQ','AG','AR','AM','AW','AU',
						'AT','AZ','BS','BH','BD','BB','BY','BE','BZ','BJ','BM','BT','BO','BA',
						'BW','BV','BR','IO','BN','BG','BF','BI','KH','CM','CA','CV','KY','CF',
						'TD','CL','CN','CX','CC','CO','KM','CG','CD','CK','CR','CI','HR','CU',
						'CY','CZ','DK','DJ','DM','DO','EC','EG','SV','GQ','ER','EE','ET','FK',
						'FO','FJ','FI','FR','GF','PF','TF','GA','GM','GE','DE','GH','GI','GR',
						'GL','GD','GP','GU','GT','GG','GN','GW','GY','HT','HM','VA','HN','HK',
						'HU','IS','IN','ID','IR','IQ','IE','IM','IL','IT','JM','JP','JE','JO',
						'KZ','KE','KI','KP','KR','KW','KG','LA','LV','LB','LS','LR','LY','LI',
						'LT','LU','MO','MK','MG','MW','MY','MV','ML','MT','MH','MQ','MR','MU',
						'YT','MX','FM','MD','MC','MN','ME','MS','MA','MZ','MM','NA','NR','NP',
						'NL','AN','NC','NZ','NI','NE','NG','NU','NF','MP','NO','OM','PK','PW',
						'PS','PA','PG','PY','PE','PH','PN','PL','PT','PR','QA','RE','RO','RU',
						'RW','BL','SH','KN','LC','MF','PM','VC','WS','SM','ST','SA','SN','RS',
						'SC','SL','SG','SK','SI','SB','SO','ZA','GS','ES','LK','SD','SR','SJ',
						'SZ','SE','CH','SY','TW','TJ','TZ','TH','TL','TG','TK','TO','TT','TN',
						'TR','TM','TC','TV','UG','UA','AE','GB','US','UM','UY','UZ','VU','VE',
						'VN','VG','VI','WF','EH','YE','ZM','ZW'
					);
	}

	function getISO3166_1a3_codes()
	{
		return array(	'AFG','ALA','ALB','DZA','ASM','AND','AGO','AIA','ATA','ATG','ARG','ARM','ABW','AUS',
						'AUT','AZE','BHS','BHR','BGD','BRB','BLR','BEL','BLZ','BEN','BMU','BTN','BOL','BIH',
						'BWA','BVT','BRA','IOT','BRN','BGR','BFA','BDI','KHM','CMR','CAN','CPV','CYM','CAF',
						'TCD','CHL','CHN','CXR','CCK','COL','COM','COG','COD','COK','CRI','CIV','HRV','CUB',
						'CYP','CZE','DNK','DJI','DMA','DOM','ECU','EGY','SLV','GNQ','ERI','EST','ETH','FLK',
						'FRO','FJI','FIN','FRA','GUF','PYF','ATF','GAB','GMB','GEO','DEU','GHA','GIB','GRC',
						'GRL','GRD','GLP','GUM','GTM','GGY','GIN','GNB','GUY','HTI','HMD','VAT','HND','HKG',
						'HUN','ISL','IND','IDN','IRN','IRQ','IRL','IMN','ISR','ITA','JAM','JPN','JEY','JOR',
						'KAZ','KEN','KIR','PRK','KOR','KWT','KGZ','LAO','LVA','LBN','LSO','LBR','LBY','LIE',
						'LTU','LUX','MAC','MKD','MDG','MWI','MYS','MDV','MLI','MLT','MHL','MTQ','MRT','MUS',
						'MYT','MEX','FSM','MDA','MCO','MNG','MNE','MSR','MAR','MOZ','MMR','NAM','NRU','NPL',
						'NLD','ANT','NCL','NZL','NIC','NER','NGA','NIU','NFK','MNP','NOR','OMN','PAK','PLW',
						'PSE','PAN','PNG','PRY','PER','PHL','PCN','POL','PRT','PRI','QAT','REU','ROU','RUS',
						'RWA','BLM','SHN','KNA','LCA','MAF','SPM','VCT','WSM','SMR','STP','SAU','SEN','SRB',
						'SYC','SLE','SGP','SVK','SVN','SLB','SOM','ZAF','SGS','ESP','LKA','SDN','SUR','SJM',
						'SWZ','SWE','CHE','SYR','TWN','TJK','TZA','THA','TLS','TGO','TKL','TON','TTO','TUN',
						'TUR','TKM','TCA','TUV','UGA','UKR','ARE','GBR','USA','UMI','URY','UZB','VUT','VEN',
						'VNM','VGB','VIR','WLF','ESH','YEM','ZMB','ZWE'
					);
	}

	function getISO3166_1num_codes()
	{
		return array(	'004','248','008','012','016','020','024','660','010','028','032','051','533','036',
						'040','031','044','048','050','052','112','056','084','204','060','064','068','070',
						'072','074','076','086','096','100','854','108','116','120','124','132','136','140',
						'148','152','156','162','166','170','174','178','180','184','188','384','191','192',
						'196','203','208','262','212','214','218','818','222','226','232','233','231','238',
						'234','242','246','250','254','258','260','266','270','268','276','288','292','300',
						'304','308','312','316','320','831','324','624','328','332','334','336','340','344',
						'348','352','356','360','364','368','372','833','376','380','388','392','832','400',
						'398','404','296','408','410','414','417','418','428','422','426','430','434','438',
						'440','442','446','807','450','454','458','462','466','470','584','474','478','480',
						'175','484','583','498','492','496','499','500','504','508','104','516','520','524',
						'528','530','540','554','558','562','566','570','574','580','578','512','586','585',
						'275','591','598','600','604','608','612','616','620','630','634','638','642','643',
						'646','652','654','659','662','663','666','670','882','674','678','682','686','688',
						'690','694','702','703','705','090','706','710','239','724','144','736','740','744',
						'748','752','756','760','158','762','834','764','626','768','772','776','780','788',
						'792','795','796','798','800','804','784','826','840','581','858','860','548','862',
						'704','092','850','876','732','887','894','716'
					);
	}

	function getISO639_1_codes()
	{
		return array(	'ab','aa','af','ak','sq','am','ar','an','hy','as','av','ae','ay','az','bm','ba','eu',
						'be','bn','bh','bi','bs','br','bg','my','ca','ch','ce','ny','zh','cv','kw','co','cr',
						'hr','cs','da','dv','nl','dz','en','eo','et','ee','fo','fj','fi','fr','ff','gl','ka',
						'de','el','gn','gu','ht','ha','he','hz','hi','ho','hu','ia','id','ie','ga','ig','ik',
						'io','is','it','iu','ja','jv','kl','kn','kr','ks','kk','km','ki','rw','ky','kv','kg',
						'ko','ku','kj','la','lb','lg','li','ln','lo','lt','lu','lv','gv','mk','mg','ms','ml',
						'mt','mi','mr','mh','mn','na','nv','nb','nd','ne','ng','nn','no','ii','nr','oc','oj',
						'cu','om','or','os','pa','pi','fa','pl','ps','pt','qu','rm','rn','ro','ru','sa','sc',
						'sd','se','sm','sg','sr','gd','sn','si','sk','sl','so','st','es','su','sw','ss','sv',
						'ta','te','tg','th','ti','bo','tk','tl','tn','to','tr','ts','tt','tw','ty','ug','uk',
						'ur','uz','ve','vi','vo','wa','cy','wo','fy','xh','yi','yo','za','zu'
					);
	}

	/**
	 * get user ip & isp
	 *
	 * @return array w/ values
	 */
	function aecIP()
	{
		global $aecConfig;

		// user IP
		$aecUser['ip'] 	= $_SERVER['REMOTE_ADDR'];

		// user Hostname (if not deactivated)
		if ( $aecConfig->cfg['gethostbyaddr'] ) {
			$aecUser['isp'] = gethostbyaddr( $_SERVER['REMOTE_ADDR'] );
		} else {
			$aecUser['isp'] = 'deactivated';
		}

		return $aecUser;
	}

	function in_ip_range( $ip_one, $ip_two=false )
	{
		if ( $ip_two === false ) {
			if ( $ip_one == $_SERVER['REMOTE_ADDR'] ) {
				$ip = true;
			} else {
				$ip = false;
			}
		} else {
			if ( ( ip2long( $ip_one ) <= ip2long( $_SERVER['REMOTE_ADDR'] ) ) && ( ip2long( $ip_two ) >= ip2long( $_SERVER['REMOTE_ADDR'] ) ) ) {
				$ip = true;
			} else {
				$ip = false;
			}
		}
		return $ip;
	}

	/**
	 * Return a URL based on the sef and user settings
	 * @parameter url
	 * @return string
	 */
	function backendTaskLink( $task, $text )
	{
		return '<a href="' .  JURI::root() . 'administrator/index.php?option=com_acctexp&amp;task=' . $task . '" title="' . $text . '">' . $text . '</a>';
	}

	/**
	 * Return a URL based on the sef and user settings
	 * @parameter url
	 * @return string
	 */
	function deadsureURL( $url, $secure=false, $internal=false )
	{
		$db = &JFactory::getDBO();

		global $aecConfig;

		$base = JURI::root();

		if ( $secure ) {
			if ( $aecConfig->cfg['override_reqssl'] ) {
				$secure = false;
			} elseif ( !empty( $aecConfig->cfg['altsslurl'] ) ) {
				$base = $aecConfig->cfg['altsslurl'];
			}
		}

		if ( $aecConfig->cfg['simpleurls'] ) {
			$new_url = $base . $url;
		} else {
			if ( !strpos( strtolower( $url ), 'itemid' ) ) {
				$parts = explode( '&', $url );

				$task	= $sub = $option = "";
				foreach ( $parts as $part ) {
					if ( strpos( $part, 'task=' ) === 0 ) {
						$task = strtolower( str_replace( 'task=', '', $part ) );
					} elseif ( strpos( $part, 'sub=' ) === 0 ) {
						$sub = strtolower( str_replace( 'sub=', '', $part ) );
					} elseif ( strpos( $part, 'option=' ) === 0 ) {
						$option = strtolower( str_replace( 'option=', '', $part ) );
					}
				}

				if ( !empty( $task ) ) {
					$translate = array(	'saveregistration' => 'confirm',
										'renewsubscription' => 'plans',
										'addtocart' => 'cart',
										'clearcart' => 'cart',
										'clearcartitem' => 'cart',
										'savesubscription' => 'checkout'
										);

					if ( array_key_exists( $task, $translate ) ) {
						$task = $translate[$task];
					}
				}

				// Do not assign an ItemID on a notification
				if ( strpos( $task, 'notification' ) === false ) {
					if ( !empty( $aecConfig->cfg['itemid_' . $task.'_'.$sub] ) ) {
						$url .= '&Itemid=' . $aecConfig->cfg['itemid_' . $task.'_'.$sub];
					} elseif ( !empty( $aecConfig->cfg['itemid_' . $task] ) ) {
						$url .= '&Itemid=' . $aecConfig->cfg['itemid_' . $task];
					} elseif ( ( $option == 'com_comprofiler') && !empty( $aecConfig->cfg['itemid_cb'] ) ) {
						$url .= '&Itemid=' . $aecConfig->cfg['itemid_cb'];
					} elseif ( ( $option == 'com_user') && !empty( $aecConfig->cfg['itemid_joomlauser'] ) ) {
						$url .= '&Itemid=' . $aecConfig->cfg['itemid_joomlauser'];
					} elseif ( !empty( $aecConfig->cfg['itemid_default'] ) ) {
						$url .= '&Itemid=' . $aecConfig->cfg['itemid_default'];
					} else {
						// No Itemid found - try to get something else
						global $Itemid;
						if ( $Itemid ) {
							$url .= '&Itemid=' . $Itemid;
						} else {
							$url .= '&Itemid=';
						}
					}
				}
			}

			// Replace all &amp; with & as the router doesn't understand &amp;
			$xurl = str_replace('&amp;', '&', $url);

			if ( substr( strtolower( $xurl ), 0, 9 ) != "index.php" ) {
				 $new_url = $xurl;
			} else {
				$uri    = JURI::getInstance();
				$prefix = $uri->toString( array( 'scheme', 'host', 'port' ) );

				$new_url = $prefix.JRoute::_( $xurl );
			}

			if ( !( strpos( $new_url, $base ) === 0 ) ) {
				// look out for malformed live_site
				if ( strpos( $base, '/' ) === strlen( $base ) ) {
					$new_url = substr( $base, 0, -1 ) . $new_url;
				} else {
					// It seems we have a problem malfunction - subdirectory is not appended
					$metaurl = explode( '/', $base );
					$rooturl = $metaurl[0] . '//' . $metaurl[2];

					// Replace root to include subdirectory - if all fails, just prefix the live site
					if ( strpos( $new_url, $rooturl ) === 0 ) {
						$new_url = $base . substr( $new_url, strlen( $rooturl ) );
					} else {
						$new_url = $base . '/' . $new_url;
					}
				}
			}
		}

		if ( strpos( $new_url, '//administrator' ) !== 0 ) {
			$new_url = str_replace( '//administrator', '/administrator', $new_url );
		}

		if ( $secure && ( strpos( $new_url, 'https:' ) !== 0 ) ) {
			$new_url = str_replace( 'http:', 'https:', $new_url );
		}

		if ( $internal ) {
			$new_url = str_replace( '&amp;', '&', $new_url );
		} else {
			if ( strpos( $new_url, '&amp;' ) ) {
				$new_url = str_replace( '&amp;', '&', $new_url );
			}

			$new_url = str_replace( '&', '&amp;', $new_url );
		}

		return $new_url;
	}

	/**
	 * Return true if the user exists and is not expired, false if user does not exist
	 * Will reroute the user if he is expired
	 * @parameter username
	 * @return bool
	 */
	function VerifyUsername( $username )
	{
		$db = &JFactory::getDBO();

		$heartbeat = new aecHeartbeat( $db );
		$heartbeat->frontendping();

		$userid = AECfetchfromDB::UserIDfromUsername( $username );

		$metaUser = new metaUser( $userid );

		if ( $metaUser->hasSubscription ) {
			return $metaUser->objSubscription->verifylogin( $metaUser->cmsUser->block, $metaUser );
		} else {
			global $aecConfig;

			if ( $aecConfig->cfg['require_subscription'] ) {
				if ( $aecConfig->cfg['entry_plan'] ) {
					$db = &JFactory::getDBO();

					$payment_plan = new SubscriptionPlan( $db );
					$payment_plan->load( $aecConfig->cfg['entry_plan'] );

					$metaUser->establishFocus( $payment_plan, 'free', false );

					$metaUser->focusSubscription->applyUsage( $payment_plan->id, 'free', 1, 0 );

					return AECToolbox::VerifyUserID( $userid );
				} else {
					$invoices = AECfetchfromDB::InvoiceCountbyUserID( $metaUser->userid );

					if ( $invoices ) {
						$invoice = AECfetchfromDB::lastUnclearedInvoiceIDbyUserID( $metaUser->userid );

						if ( $invoice ) {
							$metaUser->setTempAuth();
							return aecRedirect( AECToolbox::deadsureURL( 'index.php?option=com_acctexp&task=pending&userid=' . $userid ), false, true );
						}
					}

					$metaUser->setTempAuth();
					return aecRedirect( AECToolbox::deadsureURL( 'index.php?option=com_acctexp&task=subscribe&userid=' . $userid . '&intro=1' ), false, true );
				}
			}
		}

		return true;
	}

	function VerifyUser( $username )
	{
		$db = &JFactory::getDBO();

		$heartbeat = new aecHeartbeat( $db );
		$heartbeat->frontendping();

		$id = AECfetchfromDB::UserIDfromUsername( $username );

		return AECToolbox::VerifyUserID( $id );
	}

	function VerifyUserID( $userid )
	{
		if ( empty( $userid ) ) {
			return false;
		}

		$metaUser = new metaUser( $userid );

		return AECToolbox::VerifyMetaUser( $metaUser );
	}

	function VerifyMetaUser( $metaUser )
	{
		global $aecConfig;

		if ( !$aecConfig->cfg['require_subscription'] ) {
			return true;
		}

		if ( $metaUser->hasSubscription ) {
				$result = $metaUser->objSubscription->verify( $metaUser->cmsUser->block, $metaUser );

				if ( ( $result == 'expired' ) || ( $result == 'pending' ) ) {
					$metaUser->setTempAuth();
				}

				return $result;
		}

		if ( !empty( $aecConfig->cfg['entry_plan'] ) ) {
			$db = &JFactory::getDBO();

			$payment_plan = new SubscriptionPlan( $db );
			$payment_plan->load( $aecConfig->cfg['entry_plan'] );

			$metaUser->establishFocus( $payment_plan, 'free', false );

			$metaUser->focusSubscription->applyUsage( $payment_plan->id, 'free', 1, 0 );

			return AECToolbox::VerifyUser( $metaUser->cmsUser->username );
		} else {
			$invoices = AECfetchfromDB::InvoiceCountbyUserID( $metaUser->userid );

			$metaUser->setTempAuth();

			if ( $invoices ) {
				$invoice = AECfetchfromDB::lastUnclearedInvoiceIDbyUserID( $metaUser->userid );

				if ( $invoice ) {
					return 'open_invoice';
				}
			}

			return 'subscribe';
		}
	}

	function saveUserRegistration( $option, $var, $internal=false, $overrideActivation=false, $overrideEmails=false, $overrideJS=false )
	{
		$db = &JFactory::getDBO();

		global $task, $aecConfig;

		$app = JFactory::getApplication();

		ob_start();

		// Let CB/JUSER think that everything is going fine
		if ( GeneralInfoRequester::detect_component( 'anyCB' ) ) {
			if ( GeneralInfoRequester::detect_component( 'CBE' ) || $overrideActivation ) {
				global $ueConfig;
			}

			$savetask	= $task;
			$_REQUEST['task']	= 'done';
			include_once ( JPATH_SITE . '/components/com_comprofiler/comprofiler.php' );
			$task		= $savetask;

			if ( $overrideActivation ) {
				$ueConfig['reg_confirmation'] = 0;
			}

			if ( $overrideEmails ) {
				$ueConfig['reg_welcome_sub'] = '';

				// Only disable "Pending Approval / Confirmation" emails if it makes sense
				if ( !$ueConfig['reg_confirmation'] || !$ueConfig['reg_admin_approval'] ) {
					$ueConfig['reg_pend_appr_sub'] = '';
				}
			}
		} elseif ( GeneralInfoRequester::detect_component( 'JUSER' ) ) {
			$savetask	= $task;
			$task		= 'blind';
			include_once( JPATH_SITE . '/components/com_juser/juser.php' );
			include_once( JPATH_SITE .'/administrator/components/com_juser/juser.class.php' );
			$task		= $savetask;
		} elseif ( GeneralInfoRequester::detect_component( 'JOMSOCIAL' ) ) {

		}

		// For joomla and CB, we must filter out some internal variables before handing over the POST data
		$badbadvars = array( 'userid', 'method_name', 'usage', 'processor', 'recurring', 'currency', 'amount', 'invoice', 'id', 'gid' );
		foreach ( $badbadvars as $badvar ) {
			if ( isset( $var[$badvar] ) ) {
				unset( $var[$badvar] );
			}
		}

		if ( empty( $var['name'] ) ) {
			// Must be K2
			$var['name'] = aecEscape( $var['jform']['name'], array( 'string', 'clear_nonalnum' ) );

			unset($var['jform']);
		}

		$_POST = $var;

		$var['username'] = aecEscape( $var['username'], array( 'string', 'badchars' ) );

		$savepwd = aecEscape( $var['password'], array( 'string', 'badchars' ) );

		if ( GeneralInfoRequester::detect_component( 'anyCB' ) ) {
			// This is a CB registration, borrowing their code to save the user
			if ( $internal && !GeneralInfoRequester::detect_component( 'CBE' ) ) {
				include_once( JPATH_SITE . '/components/com_acctexp/lib/codeofshame/cbregister.php' );

				if ( empty( $_POST['firstname'] ) && !empty( $_POST['name'] ) ) {
					$name = metaUser::_explodeName( $_POST['name'] );

					$_POST['firstname'] = $name['first'];

					if ( empty( $name['last'] ) ) {
						$_POST['lastname'] = $name['first'];
					} else {
						$_POST['lastname'] = $name['last'];
					}
				}

				$_POST['password__verify'] = $_POST['password2'];

				unset( $_POST['password2'] );

				@saveRegistrationNOCHECKSLOL( $option );
			} else {
				@saveRegistration( $option );

				$cbreply = ob_get_contents();

				$indicator = '<script type="text/javascript">alert(\'';

				$alertstart = strpos( $cbreply, $indicator );

				// Emergency fallback
				if ( $alertstart !== false ) {
					ob_clean();

					$alertend = strpos( $cbreply, '\'); </script>', $alertstart );

					$alert = substr( $cbreply, $alertstart+strlen($indicator), $alertend-$alertstart-strlen($indicator) );

					if ( $aecConfig->cfg['plans_first'] ) {
						return aecErrorAlert( $alert, $action='window.history.go(-2);' );
					} else {
						return aecErrorAlert( $alert, $action='window.history.go(-3);' );
					}
				}
			}
		} elseif ( GeneralInfoRequester::detect_component( 'JUSER' ) ) {
			// This is a JUSER registration, borrowing their code to save the user
			saveRegistration( $option );

			$query = 'SELECT `id`'
					. ' FROM #__users'
					. ' WHERE `username` = \'' . $var['username'] . '\''
					;
			$db->setQuery( $query );
			$uid = $db->loadResult();
			JUser::saveUser_ext( $uid );
			//synchronize dublicate user data
			$query = 'SELECT `id`' .
					' FROM #__juser_integration' .
					' WHERE `published` = \'1\'' .
					' AND `export_status` = \'1\'';
			$db->setQuery( $query );
			$components = $db->loadObjectList();
			if ( !empty( $components ) ) {
				foreach ( $components as $component ) {
					$synchronize = require_integration( $component->id );
					$synchronize->synchronizeFrom( $uid );
				}
			}
		} elseif ( GeneralInfoRequester::detect_component( 'JOMSOCIAL' ) && !$overrideJS ) {

		} else {
			$app = JFactory::getApplication();

			$data = array(	'username' => $var['username'],
							'password' => $var['password'],
							'password2' => $var['password2'],
							'email' => $var['email'],
							'name' => $var['name'],
							);

			if ( isset( $var['jform']['profile'] ) ) {
				$data['profile'] = $var['jform']['profile'];
			}

			if ( defined( 'JPATH_MANIFESTS' ) ) {
				$params = JComponentHelper::getParams('com_users');

				// Initialise the table with JUser.
				JUser::getTable('User', 'JTable');
				$user = new JUser();

				// Prepare the data for the user object.
				$useractivation = $params->get('useractivation');

				// Check if the user needs to activate their account.
				if ( (($useractivation == 1) || ($useractivation == 2)) && !$overrideActivation ) {
					jimport('joomla.user.helper');
					$data['activation'] = JUtility::getHash(JUserHelper::genRandomPassword());
					$data['block'] = 1;
				}

				// Bind the data.
				if (!$user->bind($data)) {
					JError::raiseError( 500, JText::sprintf('COM_USERS_REGISTRATION_BIND_FAILED', $user->getError()));
					return false;
				}

				// Load the users plugin group.
				JPluginHelper::importPlugin('users');

				// Store the data.
				if (!$user->save()) {
					JError::raiseError( 500, JText::sprintf('COM_USERS_REGISTRATION_SAVE_FAILED', $user->getError()));
					return false;
				}
			} else {
				// This is a joomla registration, borrowing their code to save the user

				// Check for request forgeries
				if ( !$internal ) {
					JRequest::checkToken() or die( 'Invalid Token' );
				}

				// Get required system objects
				$user 		= clone(JFactory::getUser());
				//$pathway 	=& $app->getPathway();
				$config		=& JFactory::getConfig();
				$authorize	=& JFactory::getACL();
				$document   =& JFactory::getDocument();

				// If user registration is not allowed, show 403 not authorized.
				$usersConfig = &JComponentHelper::getParams( 'com_users' );
				if ($usersConfig->get('allowUserRegistration') == '0') {
					JError::raiseError( 403, JText::_( 'Access Forbidden' ));
					return;
				}

				// Initialize new usertype setting
				$newUsertype = $usersConfig->get( 'new_usertype' );
				if (!$newUsertype) {
					$newUsertype = 'Registered';
				}

				// Bind the post array to the user object
				if (!$user->bind( $data )) {
					JError::raiseError( 500, $user->getError());

					unset($_POST);
					subscribe();
					return false;
				}

				// Set some initial user values
				$user->set('id', 0);
				$user->set('usertype', '');
				$user->set('gid', $authorize->get_group_id( '', $newUsertype, 'ARO' ));
				$user->set('sendEmail', 0);

				$user->set('registerDate', date('Y-m-d H:i:s'));

				// If user activation is turned on, we need to set the activation information
				$useractivation = $usersConfig->get( 'useractivation' );
				if ( ($useractivation == '1') &&  !$overrideActivation )
				{
					jimport('joomla.user.helper');
					$user->set('activation', md5( JUserHelper::genRandomPassword()) );
					$user->set('block', '1');
				}

				// If there was an error with registration, set the message and display form
				if ( !$user->save() )
				{
					JError::raiseWarning('', JText::_( $user->getError()));
					echo JText::_( $user->getError());
					return false;
				}
			}

			$row = $user;

			$mih = new microIntegrationHandler();
			$mih->userchange($row, $_POST, 'registration');

			$name 		= $row->name;
			$email 		= $row->email;
			$username 	= $row->username;

			$subject 	= sprintf ( JText::_('AEC_SEND_SUB'), $name, $app->getCfg( 'sitename' ) );
			$subject 	= html_entity_decode( $subject, ENT_QUOTES, 'UTF-8' );

			$usersConfig = &JComponentHelper::getParams( 'com_users' );
			$activation = $usersConfig->get('useractivation');

			if ( ( $activation > 0 ) && !$overrideActivation ) {
				$atext = JText::_('AEC_USEND_MSG_ACTIVATE');

				if ( defined( 'JPATH_MANIFESTS' ) ) {
					$activation_link	= JURI::root() . 'index.php?option=com_users&amp;task=registration.activate&amp;token=' . $row->activation;

					if ( $activation == 2 ) {
						$atext = JText::_('COM_USERS_MSG_ADMIN_ACTIVATE');
					}
				} else {
					$activation_link	= JURI::root() . 'index.php?option=com_user&amp;task=activate&amp;activation=' . $row->activation;
				}

				$message = sprintf( $atext, $name, $app->getCfg( 'sitename' ), $activation_link, JURI::root(), $username, $savepwd );
			} else {
				$message = sprintf( JText::_('AEC_USEND_MSG'), $name, $app->getCfg( 'sitename' ), JURI::root() );
			}

			$message = html_entity_decode( $message, ENT_QUOTES, 'UTF-8' );

			// check if Global Config `mailfrom` and `fromname` values exist
			if ( $app->getCfg( 'mailfrom' ) != '' && $app->getCfg( 'fromname' ) != '' ) {
				$adminName2 	= $app->getCfg( 'fromname' );
				$adminEmail2 	= $app->getCfg( 'mailfrom' );
			} else {
				// use email address and name of first superadmin for use in email sent to user
				$rows = aecACLhandler::getSuperAdmins();
				$row2 			= $rows[0];

				$adminName2 	= $row2->name;
				$adminEmail2 	= $row2->email;
			}

			// Send email to user
			if ( !( $aecConfig->cfg['nojoomlaregemails'] || $overrideEmails ) ) {
				JUTility::sendMail( $adminEmail2, $adminEmail2, $email, $subject, $message );
			}

			// Send notification to all administrators
			$aecUser	= AECToolbox::aecIP();

			$subject2	= sprintf( JText::_('AEC_SEND_SUB'), $name, $app->getCfg( 'sitename' ) );
			$message2	= sprintf( JText::_('AEC_ASEND_MSG_NEW_REG'), $adminName2, $app->getCfg( 'sitename' ), $row->name, $email, $username, $aecUser['ip'], $aecUser['isp'] );

			$subject2	= html_entity_decode( $subject2, ENT_QUOTES, 'UTF-8' );
			$message2	= html_entity_decode( $message2, ENT_QUOTES, 'UTF-8' );

			// get email addresses of all admins and superadmins set to recieve system emails
			$admins = AECToolbox::getAdminEmailList();

			foreach ( $admins as $adminemail ) {
				if ( !empty( $adminemail ) ) {
					JUTility::sendMail( $adminEmail2, $adminEmail2, $adminemail, $subject2, $message2 );
				}
			}
		}

		ob_clean();

		// We need the new userid, so we're fetching it from the newly created entry here
		$query = 'SELECT `id`'
				. ' FROM #__users'
				. ' WHERE `username` = \'' . $var['username'] . '\''
				;
		$db->setQuery( $query );
		return $db->loadResult();
	}

	function searchUser( $search )
	{
		$db = &JFactory::getDBO();

		$k = 0;

		if ( strpos( $search, "@" ) !== false ) {
			// Try user email
			$queries[$k] = 'FROM #__users'
						. ' WHERE LOWER( `email` ) = \'' . $search . '\''
						;
			$qfields[$k] = 'id';
			$k++;

			// If its not that, how about the username and name?
			$queries[$k] = 'FROM #__users'
						. ' WHERE LOWER( `username` ) LIKE \'%' . $search . '%\' OR LOWER( `name` ) LIKE \'%' . $search . '%\''
						;
			$qfields[$k] = 'id';
			$k++;
		} else {
			// Try username and name
			$queries[$k] = 'FROM #__users'
						. ' WHERE LOWER( `username` ) LIKE \'%' . $search . '%\' OR LOWER( `name` ) LIKE \'%' . $search . '%\''
						;
			$qfields[$k] = 'id';
			$k++;

			// If its not that, how about the user email?
			$queries[$k] = 'FROM #__users'
						. ' WHERE LOWER( `email` ) = \'' . $search . '\''
						;
			$qfields[$k] = 'id';
			$k++;
		}

		// Try to find this as a userid
		$queries[$k] = 'FROM #__users'
					. ' WHERE `id` = \'' . $search . '\''
					;
		$qfields[$k] = 'id';
		$k++;

		// Or maybe its an invoice number?
		$queries[$k] = 'FROM #__acctexp_invoices'
					. ' WHERE LOWER( `invoice_number` ) = \'' . $search . '\''
					. ' OR LOWER( `secondary_ident` ) = \'' . $search . '\''
					;
		$qfields[$k] = 'userid';
		$k++;

		foreach ( $queries as $qid => $base_query ) {
			$query = 'SELECT count(*) ' . $base_query;
			$db->setQuery( $query );
			$existing = $db->loadResult();

			if ( $existing ) {
				$query = 'SELECT `' . $qfields[$qid] . '` ' . $base_query;
				$db->setQuery( $query );

				return $db->loadResultArray();
			}
		}

		return array();
	}

	function randomstring( $length=16, $alphanum_only=false, $uppercase=false )
	{
		$random = "";
		for ( $i=0; $i<$length; $i++ ) {

			if ( $alphanum_only ) {
				// Only get alpha-numeric characters
				if ( $uppercase ) {
					$rarray = array( rand( 0, 9 ), chr( rand( 65, 90 ) ) );

					$random .= $rarray[rand( 0, 1 )];
				} else {
					$rarray = array( rand( 0, 9 ), chr( rand( 65, 90 ) ), chr( rand( 97, 122 ) ) );

					$random .= $rarray[rand( 0, 2 )];
				}
			} else {
				// Only get non-crazy characters
				$random .= chr( rand( 33, 126 ) );
			}
		}

		return $random;
	}

	function quickVerifyUserID( $userid )
	{
		if ( empty( $userid ) ) {
			return null;
		}

		$db = &JFactory::getDBO();

		$query = 'SELECT `status`'
				. ' FROM #__acctexp_subscr'
				. ' WHERE `userid` = \'' . (int) $userid . '\''
				. ' AND `primary` = \'1\''
				;
		$db->setQuery( $query );
		$aecstatus = $db->loadResult();

		if ( $aecstatus ) {
			if ( ( strcmp( $aecstatus, 'Active' ) === 0 ) || ( strcmp( $aecstatus, 'Trial' ) === 0 ) ) {
				return true;
			} else {
				return false;
			}
		} else {
			return null;
		}
	}

	function formatDate( $date, $backend=false, $offset=true )
	{
		global $aecConfig;

		if ( is_string( $date ) ) {
			$date = strtotime( $date );
		}

		if ( $offset ) {
			$app = JFactory::getApplication();

			$date += ( $app->getCfg( 'offset' ) * 3600 );
		}

		if ( $backend ) {
			if ( empty( $aecConfig->cfg['display_date_backend'] ) ) {
				return JHTML::_( 'date', $date, JText::_('DATE_FORMAT_LC2') );
			} else {
				return strftime( $aecConfig->cfg['display_date_backend'], $date );
			}
		} else {
			if ( empty( $aecConfig->cfg['display_date_frontend'] ) ) {
				return JHTML::_( 'date', $date, JText::_('DATE_FORMAT_LC4') );
			} else {
				return strftime( $aecConfig->cfg['display_date_frontend'], $date );
			}
		}
	}

	function formatAmountCustom( $request, $plan, $forcedefault=false, $proposed=null )
	{
		if ( empty( $plan->params['customamountformat'] ) || $forcedefault ) {
			$format = '{aecjson}{"cmd":"condition","vars":[{"cmd":"data","vars":"payment.freetrial"},'
						.'{"cmd":"concat","vars":[{"cmd":"constant","vars":"CONFIRM_FREETRIAL"},"&nbsp;",{"cmd":"data","vars":"payment.method_name"}]},'
						.'{"cmd":"concat","vars":[{"cmd":"data","vars":"payment.amount"},{"cmd":"data","vars":"payment.currency_symbol"},"&nbsp;-&nbsp;",{"cmd":"data","vars":"payment.method_name"}]}'
						.']}{/aecjson}'
						;
		} else {
			$format = $plan->params['customamountformat'];
		}

		$rwEngine = new reWriteEngine();
		$rwEngine->resolveRequest( $request );

		$amount = $rwEngine->resolve( $format );

		if ( strpos( $amount, 'JSON PARSE ERROR' ) !== false ) {
			if ( !$forcedefault ) {
				return AECToolbox::formatAmountCustom( $request, $plan, true, $amount );
			} else {
				return $proposed;
			}
		}

		return $amount;
	}

	function formatAmount( $amount, $currency=null, $round=true )
	{
		global $aecConfig;

		$amount = AECToolbox::correctAmount( $amount, $round );

		$a = explode( '.', $amount );

		if ( $aecConfig->cfg['amount_use_comma'] ) {
			$amount = number_format( $amount, ( $round ? 2 : strlen($a[1]) ), ',', '.' );
		} else {
			$amount = number_format( $amount, ( $round ? 2 : strlen($a[1]) ), '.', ',' );
		}

		if ( !empty( $currency ) ) {
			if ( !empty( $aecConfig->cfg['amount_currency_symbol'] ) ) {
				$currency = AECToolbox::getCurrencySymbol( $currency );
			}

			if ( $aecConfig->cfg['amount_currency_symbolfirst'] ) {
				$amount = $currency . '&nbsp;' . $amount;
			} else {
				$amount .= '&nbsp;' . $currency;
			}
		}

		return $amount;
	}

	function correctAmount( $amount, $round=true )
	{
		if ( strpos( $amount, '.' ) === 0 ) {
			$amount = '0' . $amount;
		} elseif ( strpos( $amount, '.') === false ) {
			if ( strpos( $amount, ',' ) !== false ) {
				$amount = str_replace( ',', '.', $amount );
			} else {
				$amount = $amount . '.00';
			}
		}

		if ( $round ) {
			$amount = (string) AECToolbox::roundAmount( $amount );
		} else {
			$amount = (string) $amount;
		}

		$a = explode( '.', $amount );

		if ( empty( $a[1] ) ) {
			$amount = $a[0] . '.00';
		} else {
			if ( $round ) {
				$amount = $a[0] . '.' . substr( str_pad( $a[1], 2, '0' ), 0, 2 );
			} else {
				$amount = $a[0] . '.' . str_pad( $a[1], 2, '0' );
			}
		}

		return $amount;
	}

	function roundAmount( $amount )
	{
		$pow = pow( 10, 2 );

		$ceil = ceil( $amount * $pow ) / $pow;
		$floor = floor( $amount * $pow ) / $pow;

		$pow = pow( 10, 3 );

		$diffCeil	= $pow * ( $ceil - $amount );
		$diffFloor	= $pow * ( $amount - $floor ) + ( $amount < 0 ? -1 : 1 );

		if ( $diffCeil >= $diffFloor ) {
			return $floor;
		} else {
			return $ceil;
		}
	}

	function getCurrencySymbol( $currency )
	{
		global $aecConfig;

		$cursym = array(	'AUD' => 'AU$', 'AWG' => '&#402;', 'ANG' => '&#402;', 'BDT' => '&#2547;',
							'BRL' => 'R$', 'BWP' => 'P', 'BYR' => 'Br', 'CAD' => '$CAD', 'CHF' => 'Fr.',
							'CLP' => '$', 'CNY' => '&#165;', 'CRC' => '&#8353;', 'CVE' => '$',
							'CZK' => '&#75;&#269;', 'DKK' => 'kr', 'EUR' => '&euro;', 'GBP' => '&pound;',
							'GHS' => '&#8373;', 'GTQ' => 'Q', 'HUF' => 'Ft', 'HKD' => 'HK$',
							'INR' => '&#8360;', 'IDR' => 'Rp', 'ILS' => '&#8362;', 'IRR' => '&#65020;',
							'ISK' => 'kr', 'JPY' => '&yen;', 'KRW' => '&#8361;', 'KPW' => '&#8361;',
							'LAK' => '&#8365;', 'LBP' => '&#1604;.&#1604;', 'LKR' => '&#8360;', 'MYR' => 'RM',
							'MUR' => '&#8360;', 'MVR' => 'Rf', 'MNT' => '&#8366;', 'NDK' => 'kr',
							'NGN' => '&#8358;', 'NIO' => 'C$', 'NPR' => '&#8360;', 'NZD' => 'NZ$',
							'PAB' => 'B/.', 'PEH' => 'S/.', 'PEN' => 'S/.', 'PCA' => 'PC&#1044;',
							'PHP' => '&#8369;', 'PKR' => '&#8360;', 'PLN' => '&#122;&#322;', 'PYG' => '&#8370;',
							'RUB' => '&#1088;&#1091;&#1073;', 'SCR' => '&#8360;', 'SEK' => 'kr', 'SGD' => 'S$',
							'SRC' => '&#8353;', 'THB' => '&#3647;', 'TOP' => 'T$', 'TRY' => 'TL',
							'USD' => '$', 'UAH' => '&#8372;', 'VND' => '&#8363;', 'VEF' => 'Bs. F',
							'ZAR' => 'R',
							);

		if ( array_key_exists( $currency, $cursym ) ) {
			return $cursym[$currency];
		} elseif( array_key_exists( $aecConfig->cfg['standard_currency'], $cursym ) ) {
			return $cursym[$aecConfig->cfg['standard_currency']];
		} else {
			return '&#164;';
		}
	}

	function computeExpiration( $value, $unit, $timestamp )
	{
		return date( 'Y-m-d H:i:s', AECToolbox::offsetTime( $value, $unit, $timestamp ) );
	}

	function offsetTime( $value, $unit, $timestamp )
	{
		$sign = strpos( $value, '-' ) ? '-' : '+';

		switch ( $unit ) {
			case 'H':
				$add = $sign . $value . ' hour';
				break;
			case 'D':
				$add = $sign . $value . ' day';
				break;
			case 'W':
				$add = $sign . $value . ' week';
				break;
			case 'M':
				$add = $sign . $value . ' month';
				break;
			case 'Y':
				$add = $sign . $value . ' year';
				break;
		}

		return strtotime( $add, $timestamp );
	}

	function cleanPOST( $post, $safe=true )
	{
		$badparams = array( 'option', 'task' );

		foreach ( $badparams as $param ) {
			if ( isset( $post[$param] ) ) {
				unset( $post[$param] );
			}
		}

		if ( $safe ) {
			return aecPostParamClear( $post );
		} else {
			return $post;
		}
	}

	function getFileArray( $dir, $extension=false, $listDirectories=false, $keepDots=false )
	{
		$dirArray	= array();
		$handle		= dir( $dir );

		while ( ( $file = $handle->read() ) !== false ) {
			if ( ( $file != '.' && $file != '..' ) || $keepDots ) {
				if ( !$listDirectories ) {
					if ( is_dir( $dir.'/'.$file ) ) {
						continue;
					}
				}
				if ( !empty( $extension ) ) {
					if ( !is_dir( $dir.'/'.$file ) ) {
						if ( strpos( basename( $file ), $extension ) === false ) {
							continue;
						}
					}
				}

				array_push( $dirArray, basename( $file ) );
			}
		}
		$handle->close();
		return $dirArray;
	}

	function versionSort( $array )
	{
		// Bastardized Quicksort
		if ( !isset( $array[2] ) ) {
			return $array;
		}

		$piv = $array[0];
		$x = $y = array();
		$len = count( $array );
		$i = 1;

		while ( $i < $len ) {
			if ( version_compare( AECToolbox::normVersionName( $array[$i] ), AECToolbox::normVersionName( $piv ), '<' ) ) {
				$x[] = $array[$i];
			} else {
				$y[] = $array[$i];
			}
			++$i;
		}

		return array_merge( AECToolbox::versionSort($x), array($piv), AECToolbox::versionSort($y) );
	}

	function normVersionName( $name )
	{
		$str = str_replace( "RC", "_", $name );

		$lastchar = substr( $str, -1, 1 );

		if ( !is_numeric( $lastchar ) ) {
			$str = substr( $str, 0, strlen( $str )-1 ) . "_" . ord( $lastchar );
		}

		return $str;
	}

	function visualstrlen( $string )
	{
		// Narrow Chars
		$srt = array( 'I', 'J', 'i', 'j', 'l', 'r', 't', '(', ')', '[', ']', ',', '.', '-' );
		// Thick Chars
		$lng = array( 'w', 'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'K', 'L', 'N', 'O', 'P', 'Y', 'R', 'S', 'T', 'U', 'V', 'X', 'Y', 'Z' );
		// Very Thick Chars
		$llng = array( 'm', 'M', 'Q', 'W' );

		// Break String into individual characters
		$char_array = preg_split( '#(?<=.)(?=.)#s', $string );

		$vlen = 0;
		// Iterate through array counting the visual length of the string
		foreach ( $char_array as $char ) {
			if ( in_array( $char, $srt ) ) {
				$vlen += 0.4;
			} elseif ( in_array( $char, $lng ) ) {
				$vlen += 1.5;
			} elseif ( in_array( $char, $llng ) ) {
				$vlen += 1.8;
			} else {
				$vlen += 1;
			}
		}

		return (int) $vlen;
	}

	function rewriteEngineInfo( $switches=array(), $params=null )
	{
		$rwEngine = new reWriteEngine();
		return $rwEngine->info( $switches, $params );
	}

	function rewriteEngine( $content, $metaUser=null, $subscriptionPlan=null, $invoice=null )
	{
		return AECToolbox::rewriteEngineRQ( $content, null, $metaUser, $subscriptionPlan, $invoice );
	}

	function rewriteEngineRQ( $content, $request, $metaUser=null, $subscriptionPlan=null, $invoice=null )
	{
		if ( !is_object( $request ) ) {
			$request = new stdClass();
		}

		if ( !empty( $metaUser ) ) {
			$request->metaUser = $metaUser;
		}

		if ( !empty( $subscriptionPlan ) ) {
			$request->plan = $subscriptionPlan;
		}

		if ( !empty( $invoice ) ) {
			$request->invoice = $invoice;
		}

		$rwEngine = new reWriteEngine();
		$rwEngine->resolveRequest( $request );

		return $rwEngine->resolve( $content );
	}

	function rewriteEngineExplain( $content )
	{
		$rwEngine = new reWriteEngine();

		return $rwEngine->explain( $content );
	}

	function compare( $eval, $check1, $check2 )
	{
		$status = false;
		switch ( $eval ) {
			case '=':
				$status = (bool) ( $check1 == $check2 );
				break;
			case '!=':
			case '<>':
				$status = (bool) ( $check1 != $check2 );
				break;
			case '<=':
				$status = (bool) ( $check1 <= $check2 );
				break;
			case '>=':
				$status = (bool) ( $check1 >= $check2 );
				break;
			case '>':
				$status = (bool) ( $check1 > $check2 );
				break;
			case '<':
				$status = (bool) ( $check1 < $check2 );
				break;
		}

		return $status;
	}

	function math( $sign, $val1, $val2 )
	{
		$result = false;
		switch ( $sign ) {
			case '+':
				$result = $val1 + $val2;
				break;
			case '-':
				$result = $val1 - $val2;
				break;
			case '*':
				$result = $val1 * $val2;
				break;
			case '/':
				$result = $val1 / $val2;
				break;
			case '%':
				$result = $val1 % $val2;
				break;
		}

		return $result;
	}

	function getObjectProperty( $object, $key, $test=false )
	{
		if ( !is_array( $key ) ) {
			if ( strpos( $key, '.' ) !== false ) {
				$key = explode( '.', $key );
			}
		}

		if ( !is_array( $key ) ) {
			if ( isset( $object->$key ) ) {
				if ( $test ) {
					return true;
				} else {
					return $object->$key;
				}
			} elseif ( $test ) {
				return false;
			} else {
				return null;
			}
		} else {
			$return = $object;

			$err = 'AECjson cmd:data Syntax Error';
			$erp = 'aecjson,data,syntax,error';
			$erx = 'Syntax Parser cannot parse next property: ';

			if ( empty( $key ) ) {
				$erx .= 'No Key Found';

				$db = &JFactory::getDBO();

				$eventlog = new eventLog( $db );
				$eventlog->issue( $err, $erp, $erx, 128, array() );
				return false;
			}

			foreach ( $key as $k ) {
				// and use {}/variable variables instead
				$subject =& $return;

				if ( is_object( $subject ) ) {
					if ( property_exists( $subject, $k ) ) {
						if ( $test ) {
							return true;
						} else {
							$return =& $subject->$k;
						}
					} elseif ( $test ) {
						return false;
					} else {
						$db = &JFactory::getDBO();

						$props = array_keys( get_object_vars( $subject ) );

						$event = $erx . $k . ' does not exist! Possible object values are: ' . implode( ';', $props );

						$eventlog = new eventLog( $db );
						$eventlog->issue( $err, $erp, $event, 128, array() );
					}
				} elseif ( is_array( $subject ) ) {
					if ( isset( $subject[$k] ) ) {
						if ( $test ) {
							return true;
						} else {
							$return =& $subject[$k];
						}
					} elseif ( $test ) {
						return false;
					} else {
						$db = &JFactory::getDBO();

						$props = array_keys( $subject );

						$event = $erx . $k . ' does not exist! Possible array values are: ' . implode( ';', $props );

						$eventlog = new eventLog( $db );
						$eventlog->issue( $err, $erp, $event, 128, array() );
					}

				} elseif ( $test ) {
					return false;
				} else {
					$db = &JFactory::getDBO();

					$event = $erx . $k . '; neither property nor array field';

					$eventlog = new eventLog( $db );
					$eventlog->issue( $err, $erp, $event, 128, array() );
					return false;
				}
			}

			return $return;
		}
	}

	function getAdminEmailList()
	{
		global $aecConfig;

		$adminlist = array();
		if ( $aecConfig->cfg['email_default_admins'] ) {
			$admins = aecACLhandler::getSuperAdmins();

			foreach ( $admins as $admin ) {
				if ( !empty( $admin->sendEmail ) ) {
					$adminlist[] = $admin->email;
				}
			}
		}

		if ( !empty( $aecConfig->cfg['email_extra_admins'] ) ) {
			$al = explode( ',', $aecConfig->cfg['email_extra_admins'] );

			$adminlist = array_merge( $adminlist, $al );
		}

		return $adminlist;
	}
}

class microIntegrationHandler
{
	function microIntegrationHandler()
	{
		$app = JFactory::getApplication();

		$this->mi_dir = JPATH_SITE . '/components/com_acctexp/micro_integration';
	}

	function getMIList( $limitstart=false, $limit=false, $use_order=false, $name=false, $classname=false )
	{
		$db = &JFactory::getDBO();

		$query = 'SELECT id, class_name' . ( $name ? ', name' : '' )
			 	. ' FROM #__acctexp_microintegrations'
		 		. ' WHERE `hidden` = \'0\''
		 		. ( !empty( $classname ) ? ' AND `class_name` = \'' . $classname . '\'' : '' )
			 	. ' GROUP BY ' . ( $use_order ? '`ordering`' : '`id`' )
			 	. ' ORDER BY `class_name`'
			 	;

		if ( !empty( $limitstart ) && !empty( $limit ) ) {
			$query .= 'LIMIT ' . $limitstart . ',' . $limit;
		}

		$db->setQuery( $query );

		$rows = $db->loadObjectList();
		if ( $db->getErrorNum() ) {
			echo $db->stderr();
			return false;
		} else {
			return $rows;
		}
	}

	function compareMIs( $mi, $cmi_id )
	{
		$db = &JFactory::getDBO();

		$excluded_props = array( 'id' );

		$cmi = new microIntegration( $db );
		$cmi->load( $cmi_id );

		if ( !$cmi->callIntegration( true ) ) {
			return false;
		}

		$props = get_object_vars( $mi );

		$similar = true;
		foreach ( $props as $prop => $value ) {
			if ( ( strpos( $prop, '_' ) === 0 ) || in_array( $prop, $excluded_props ) ) {
				// This is an internal or excluded variable
				continue;
			}

			if ( $cmi->$prop != $mi->$prop ) {
				// Nope, this one is different
				$similar = false;
			}
		}

		return $similar;
	}

	function getIntegrationList()
	{
		$list = AECToolbox::getFileArray( $this->mi_dir, 'php', false, true );

		asort( $list );

		$integration_list = array();
		foreach ( $list as $name ) {
			$parts = explode( '.', $name );
			$integration_list[] = $parts[0];
		}

		return $integration_list;
	}

	function getMIsbyPlan( $plan_id )
	{
		$db = &JFactory::getDBO();

		$plan = new SubscriptionPlan( $db );
		$plan->load( $plan_id );

		return $plan->getMicroIntegrations();
	}

	function getPlansbyMI( $mi_id )
	{
		$db = &JFactory::getDBO();

		$query = 'SELECT `id`'
				. ' FROM #__acctexp_plans'
				;
		$db->setQuery( $query );
		$plans = $db->loadResultArray();

		$plan_list = array();
		foreach ( $plans as $planid ) {
			$plan = new SubscriptionPlan( $db );
			$plan->load( $planid );

			$mis = $plan->getMicroIntegrations();
			if ( !empty( $mis ) ) {
				if ( is_array( $mi_id ) ) {
					if ( array_intersect( $mi_id, $mis ) ) {
						$plan_list[] = $planid;
					}
				} else {
					if ( in_array( $mi_id, $mis ) ) {
						$plan_list[] = $planid;
					}
				}
			}
		}

		return $plan_list;
	}

	function userPlanExpireActions( $metaUser, $subscription_plan, $special=null )
	{
		$db = &JFactory::getDBO();

		$mi_autointegrations = $this->getAutoIntegrations();

		if ( is_array( $mi_autointegrations ) || ( $subscription_plan !== false ) ) {
			$mis = $subscription_plan->getMicroIntegrations();

			if ( is_array( $mis ) ) {
				$user_auto_integrations = array_intersect( $mis, $mi_autointegrations );
			} else {
				return null;
			}

			if ( count( $user_auto_integrations ) ) {
				foreach ( $user_auto_integrations as $mi_id ) {
					$mi = new microIntegration( $db );
					$mi->load( $mi_id );
					if ( $mi->callIntegration() ) {
						$invoice = null;
						if ( !empty( $metaUser->focusSubscription->id ) ) {
							$invoice = new Invoice( $db );
							$invoice->loadbySubscriptionId( $metaUser->focusSubscription->id );
							
							if ( empty( $invoice->id ) ) {
								$invoice = null;
							}
						}

						$mi->expiration_action( $metaUser, $subscription_plan, $invoice );
						
						if ( !empty( $special ) ) {
							$mi->relayAction( $metaUser, null, $invoice, $subscription_plan, $special );
						}
					}
				}
			}
		}
	}

	function getHacks()
	{
		$integrations = $this->getIntegrationList();

		$hacks = array();
		foreach ( $integrations as $n => $name ) {
			$file = $this->mi_dir . '/' . $name . '.php';

			if ( file_exists( $file ) ) {
				include_once $file;

				$mi = new $name();

				if ( method_exists( $mi, 'hacks' ) ) {
					if ( method_exists( $mi, 'detect_application' ) ) {
						if ( $mi->detect_application() ) {
							$mihacks = $mi->hacks();
							if ( is_array( $mihacks ) ) {
								$hacks = array_merge( $hacks, $mihacks );
							}
						}
					}
				}
			}
		}

		return $hacks;
	}

	function getPreExpIntegrations()
	{
		$db = &JFactory::getDBO();

		$query = 'SELECT `id`'
				. ' FROM #__acctexp_microintegrations'
				. ' WHERE `active` = \'1\''
				. ' AND `pre_exp_check` > 0'
				;
		$db->setQuery( $query );
		return $db->loadResultArray();
	}

	function getAutoIntegrations()
	{
		$db = &JFactory::getDBO();

		$query = 'SELECT `id`'
				. ' FROM #__acctexp_microintegrations'
				. ' WHERE `active` = \'1\''
				. ' AND `auto_check` = \'1\''
				;
		$db->setQuery( $query );
		return $db->loadResultArray();
	}

	function getUserChangeIntegrations()
	{
		$db = &JFactory::getDBO();

		$query = 'SELECT id'
				. ' FROM #__acctexp_microintegrations'
				. ' WHERE `active` = \'1\''
				. ' AND `on_userchange` = \'1\''
				;
		$db->setQuery( $query );
		return $db->loadResultArray();
	}

	function userchange( $row, $post, $trace = '' )
	{
		$db = &JFactory::getDBO();

		$mi_list = $this->getUserChangeIntegrations();

		if ( is_int( $row ) ) {
			$userid = $row;
		} elseif ( is_string( $row ) ){
			$query = 'SELECT id'
			. ' FROM #__users'
			. ' WHERE username = \'' . $row . '\''
			;
			$db->setQuery( $query );
			$userid = $db->loadResult();
		} elseif ( is_array( $row ) ) {
			$userid = $row['id'];
		} elseif ( !is_object( $row ) ) {
			$userid = $row;
		}

		if ( !is_object( $row ) ) {
			$row = new JTableUser( $db );
			$row->load( $userid );
		}

		if ( !empty( $mi_list ) ) {
			foreach ( $mi_list as $mi_id ) {;
				if ( !is_null( $mi_id ) && ( $mi_id != '' ) && $mi_id ) {
					$mi = new microIntegration($db);
					$mi->load( $mi_id );
					if ( $mi->callIntegration() ) {
						$mi->on_userchange_action( $row, $post, $trace );
					}
				}
			}
		}
	}

	function getActiveListbyList( $milist )
	{
		if ( empty( $milist ) ) {
			return array();
		}
		
		$db = &JFactory::getDBO();

		$milist = array_unique( $milist );

		$query = 'SELECT `id`'
				. ' FROM #__acctexp_microintegrations'
				. ' WHERE `id` IN (' . $db->getEscaped( implode( ',', $milist ) ) . ')'
	 			. ' AND `active` = \'1\''
				. ' ORDER BY `ordering` ASC'
				;
		$db->setQuery( $query );
		return $db->loadResultArray();
	}

	function getMaxPreExpirationTime()
	{
		$db = &JFactory::getDBO();

		$query = 'SELECT MAX(pre_exp_check)'
				. ' FROM #__acctexp_microintegrations'
				. ' WHERE `active` = \'1\''
				;
		$db->setQuery( $query );
		return $db->loadResult();
	}

	function getDetailedList()
	{
		$db = &JFactory::getDBO();

		$query = 'SELECT `id`, `name`, `desc`, `class_name`'
				. ' FROM #__acctexp_microintegrations'
				. ' WHERE `active` = 1'
			 	. ' AND `hidden` = \'0\''
				. ' ORDER BY ordering'
				;
		$db->setQuery( $query );
		return $db->loadObjectList();
	}
}

class MI
{
	function autoduplicatesettings( $settings, $ommit=array(), $collate=true, $rwEngine=false )
	{
		if ( isset( $settings['lists'] ) ) {
			$lists = $settings['lists'];
			unset( $settings['lists'] );
		} else {
			$lists = array();
		}

		$new_settings = array();
		$new_lists = array();
		foreach ( $settings as $name => $content ) {
			if ( in_array( $name, $ommit ) ) {
				continue;
			}

			if ( $collate ) {
				$new_settings[$name]				= $content;
				$new_settings_exp[$name.'_exp']		= $content;
				$new_settings_pxp[$name.'_pre_exp']	= $content;
			} else {
				$new_settings[$name]			= $content;
				$new_settings[$name.'_exp']		= $content;
				$new_settings[$name.'_pre_exp']	= $content;
			}
		}

		if ( $collate ) {
			$rewriteswitches			= array( 'cms', 'user', 'expiration', 'subscription', 'plan', 'invoice' );

			$new_settings				= AECToolbox::rewriteEngineInfo( $rewriteswitches, $new_settings );
			$new_settings_exp			= AECToolbox::rewriteEngineInfo( $rewriteswitches, $new_settings_exp );
			$new_settings_pxp			= AECToolbox::rewriteEngineInfo( $rewriteswitches, $new_settings_pxp );

			$new_settings = array_merge(	$new_settings,
											array( 'aectab_exp_'.$name => array( 'tab', JText::_('MI_E_AUTO_CHECK_NAME'), JText::_('MI_E_AUTO_CHECK_NAME') ) ),
											$new_settings_exp,
											array( 'aectab_pxp_'.$name => array( 'tab', JText::_('MI_E_PRE_EXP_CHECK_NAME'), JText::_('MI_E_PRE_EXP_CHECK_NAME') ) ),
											$new_settings_pxp
										);
		}

		if ( !empty( $new_lists ) ) {
			$new_settings['lists'] = $lists;
		}

		return $new_settings;
	}

	function setError( $error )
	{
		if ( !isset( $this->error ) ) {
			$this->error = array();
		}

		$this->error[] = $error;
	}

	function setWarning( $warning )
	{
		if ( !isset( $this->warning ) ) {
			$this->warning = array();
		}

		$this->warning[] = $warning;
	}

	function issueUniqueEvent( $request, $event, $due_date, $context=array(), $params=array(), $customparams=array() )
	{
		$db = &JFactory::getDBO();

		$query = 'SELECT `id`'
				. ' FROM #__acctexp_event'
				. ' WHERE `userid` = \'' . $request->metaUser->userid . '\''
				. ' AND `appid` = \'' . $this->id . '\''
				. ' AND `event` = \'' . $event . '\''
				. ' AND `type` = \'mi\''
	 			. ' AND `status` = \'waiting\''
				;
		$db->setQuery( $query );
		$id = $db->loadResult();

		if ( $id ) {
			return null;
		} else {
			return $this->issueEvent( $request, $event, $due_date, $context, $params, $customparams );
		}
	}

	function redateUniqueEvent( $request, $event, $due_date, $context=array(), $params=array(), $customparams=array() )
	{
		$db = &JFactory::getDBO();

		$query = 'SELECT `id`'
				. ' FROM #__acctexp_event'
				. ' WHERE `userid` = \'' . $request->metaUser->userid . '\''
				. ' AND `appid` = \'' . $this->id . '\''
				. ' AND `event` = \'' . $event . '\''
				. ' AND `type` = \'mi\''
	 			. ' AND `status` = \'waiting\''
				;
		$db->setQuery( $query );
		$id = $db->loadResult();

		if ( $id ) {
			$aecEvent = new aecEvent( $db );
			$aecEvent->load( $id );

			if ( $aecEvent->due_date != $due_date ) {
				$aecEvent->due_date = $due_date;
				$aecEvent->storeload();
			}
		} else {
			return $this->issueEvent( $request, $event, $due_date, $context, $params, $customparams );
		}
	}

	function removeEvents( $request, $event )
	{
		$db = &JFactory::getDBO();

		$query = 'DELETE'
				. ' FROM #__acctexp_event'
				. ' WHERE `userid` = \'' . $request->metaUser->userid . '\''
				. ' AND `appid` = \'' . $this->id . '\''
				. ' AND `event` = \'' . $event . '\''
				. ' AND `type` = \'mi\''
	 			. ' AND `status` = \'waiting\''
				;
		$db->setQuery( $query );
		$db->query();
	}

	function issueEvent( $request, $event, $due_date, $context=array(), $params=array(), $customparams=array() )
	{
		$db = &JFactory::getDBO();

		if ( !empty( $request->metaUser ) ) {
			$context['user_id']	= $request->metaUser->userid;
			$userid				= $request->metaUser->userid;
		} else {
			$context['user_id']	= 0;
			$userid				= 0;
		}

		if ( !empty( $request->metaUser->focusSubscription->id ) ) {
			$context['subscription_id'] = $request->metaUser->focusSubscription->id;
		}

		if ( !empty( $request->invoice->id ) ) {
			$context['invoice_id'] = $request->invoice->id;
		}

		if ( !empty( $request->invoice->invoice_number ) ) {
			$context['invoice_number'] = $request->invoice->invoice_number;
		}

		$aecEvent = new aecEvent( $db );

		return $aecEvent->issue( 'mi', $this->info['name'], $this->id, $event, $userid, $due_date, $context, $params, $customparams );
	}

	function aecEventHook( $event )
	{
		$db = &JFactory::getDBO();

		$method = 'aecEventHook' . $event->event;

		if ( !method_exists( $this, $method ) ) {
			return null;
		}

		$request = new stdClass();

		$request->parent	=& $this;
		$request->event		=& $event;

		// Establish metaUser object
		if ( !empty( $event->userid ) ) {
			$request->metaUser = new metaUser( $event->userid );
		} else {
			$request->metaUser = false;
		}

		// Select correct subscription
		if ( !empty( $event->context['subscription_id'] ) && !empty( $request->metaUser ) ) {
			$request->metaUser->moveFocus( $event->context['subscription_id'] );
		}

		// Select correct invoice
		if ( !empty( $event->context['invoice_id'] ) ) {
			$request->invoice = new Invoice( $db );
			$request->invoice->load( $event->context['invoice_id'] );
		}

		return $this->$method( $request );
	}
}

class microIntegration extends serialParamDBTable
{
	/** @var int Primary key */
	var $id					= null;
	/** @var int */
	var $active 			= null;
	/** @var int */
	var $system 			= null;
	/** @var int */
	var $hidden 			= null;
	/** @var int */
	var $ordering			= null;
	/** @var string */
	var $name				= null;
	/** @var text */
	var $desc				= null;
	/** @var string */
	var $class_name			= null;
	/** @var text */
	var $params				= null;
	/** @var text */
	var $restrictions		= null;
	/** @var int */
	var $auto_check			= null;
	/** @var int */
	var $pre_exp_check		= null;
	/** @var int */
	var $on_userchange		= null;

	function microIntegration(&$db)
	{
		parent::__construct( '#__acctexp_microintegrations', 'id', $db );
	}

	function declareParamFields()
	{
		return array( 'params', 'restrictions' );
	}

	function functionProxy( $function, $data, $default=null )
	{
		if ( method_exists( $this->mi_class, $function ) ) {
			return $this->mi_class->$function( $data );
		} else {
			return $default;
		}
	}

	function check()
	{
		if ( isset( $this->settings ) ) {
			unset( $this->settings );
		}

		if ( isset( $this->mi_class ) ) {
			unset( $this->mi_class );
		}

		if ( isset( $this->info ) ) {
			unset( $this->info );
		}

		return parent::check();
	}

	function mi_exists( $mi_id )
	{
		$db = &JFactory::getDBO();

		$query = 'SELECT count(*)'
				. ' FROM #__acctexp_microintegrations'
				. ' WHERE `id` = \'' . $mi_id . '\''
				;
		$db->setQuery( $query );
		return $db->loadResult();
	}

	function callDry( $mi_name )
	{
		$this->class_name = $mi_name;

		return $this->callIntegration( true );
	}

	function callIntegration( $override = 0 )
	{
		$filename = JPATH_SITE . '/components/com_acctexp/micro_integration/' . $this->class_name . '.php';

		$file_exists = file_exists( $filename );

		if ( ( ( !$this->active && !empty( $this->id ) ) || !$file_exists ) && !$override ) {
			// MI does not exist or is deactivated
			return false;
		} elseif ( $file_exists ) {
			include_once $filename;

			$class = $this->class_name;

			$this->mi_class = new $class();
			$this->mi_class->id = $this->id;

			$this->getInfo();

			if ( is_null( $this->name ) || ( $this->name == '' ) ) {
				$this->name = $this->info['name'];
			}

			if ( is_null( $this->desc ) || ( $this->desc == '' ) ) {
				$this->desc = $this->info['desc'];
			}

			$this->settings				=& $this->params;
			$this->mi_class->info		=& $this->info;
			$this->mi_class->settings	=& $this->settings;

			return true;
		} else {
			return false;
		}
	}

	function checkPermission( $metaUser, $invoice )
	{
		$permission = true;

		if ( !empty( $this->restrictions['has_restrictions'] ) ) {
			if ( is_object( $invoice ) ) { 
				if ( !empty( $invoice->params['stickyMIpermissions'][$this->id] ) ) {
					return true;
				}
			}

			$restrictions = $this->getRestrictionsArray();

			$permission = aecRestrictionHelper::checkRestriction( $restrictions, $metaUser );

			if ( !empty( $this->restrictions['sticky_permissions'] ) && is_object( $invoice ) ) {
				if ( empty( $invoice->params['stickyMIpermissions'] ) ) {
					$invoice->params['stickyMIpermissions'] = array();
				}

				$invoice->params['stickyMIpermissions'][$this->id] = $permission;
				$invoice->storeload();
			}

			return $permission;
		} else {
			return true;
		}
	}

	function getRestrictionsArray()
	{
		return aecRestrictionHelper::getRestrictionsArray( $this->restrictions );
	}

	function action( &$metaUser, $exchange=null, $invoice=null, $objplan=null )
	{
		$add = $params = false;

		return $this->relayAction( $metaUser, $exchange, $invoice, $objplan, 'action', $add, $params );
	}

	function pre_expiration_action( &$metaUser, $objplan=null )
	{
		if ( method_exists( $this->mi_class, 'pre_expiration_action' ) || method_exists( $this->mi_class, 'relayAction' ) ) {
			$app = JFactory::getApplication();

			$userflags = $metaUser->meta->getMIParams( $this->id, $objplan->id );

			// We need the standard variables and their uppercase pendants
			// System MI vars have to be stored and will automatically converted to uppercase
			$spc	= strtoupper( 'system_preexp_call' );
			$spca	= strtoupper( 'system_preexp_call_abandoncheck' );

			$current_expiration = strtotime( $metaUser->focusSubscription->expiration );

			// Check whether we have userflags to work with
			if ( is_array( $userflags ) && !empty( $userflags ) ) {
				// Check whether flags exist
				if ( isset( $userflags[$spc] ) ) {
					if ( $current_expiration == $userflags[$spc] ) {
						// This is a retrigger as expiration dates are equal => break
						return false;
					} else {
						if ( ( (int) gmdate('U') ) > $current_expiration ) {
							// This trigger comes too late as the expiration already happened => break
							return false;
						}
					}
				}

				if ( isset( $userflags[$spca] ) ) {
					if ( ( ( (int) gmdate('U') ) + 300 ) > $userflags[$spca] ) {
						// There already was a trigger in the last 5 minutes
						return false;
					}
				}
			}

			$userflags[$spc]	= $current_expiration;
			$userflags[$spca]	= (int) gmdate('U');

			// Create the new flags
			$metaUser->meta->setMIParams( $this->id, $objplan->id, $userflags );

			$metaUser->meta->storeload();

			$add = $params = false;

			return $this->relayAction( $metaUser, null, null, $objplan, 'pre_expiration_action', $add, $params );
		} else {
			return null;
		}
	}

	function expiration_action( &$metaUser, $objplan=null, $invoice=null )
	{
		// IF ExpireAllInstances=0 AND hasMoreThanOneInstance -> return null
		if ( empty( $this->settings['_aec_global_exp_all'] ) ) {
			if ( $metaUser->getMIcount( $this->id ) > 1 ) {
				// We have more instances than this one attached to the user, pass on.
				return null;
			}
		}

		$add = $params = false;

		return $this->relayAction( $metaUser, null, $invoice, $objplan, 'expiration_action', $add, $params );
	}

	function relayAction( &$metaUser, $exchange=null, $invoice=null, $objplan=null, $stage='action', &$add, &$params )
	{
		if ( $stage == 'action' ) {
			if ( isset( $this->settings['_aec_action'] ) ) {
				if ( !$this->settings['_aec_action'] ) {
					return null;
				}
			}

			if ( isset( $this->settings['_aec_only_first_bill'] ) && !empty( $invoice ) ) {
				if ( $this->settings['_aec_only_first_bill'] && ( $invoice->counter > 1 ) ) {
					return null;
				}
			}
		}

		if ( !$this->checkPermission( $metaUser, $invoice ) ) {
			return null;
		}

		$db = &JFactory::getDBO();

		// Exchange Settings
		if ( is_array( $exchange ) && !empty( $exchange ) ) {
			$this->exchangeSettings( $exchange );
		}

		$request = new stdClass();
		$request->action	=	$stage;
		$request->parent	=&	$this;
		$request->metaUser	=&	$metaUser;
		$request->invoice	=&	$invoice;
		$request->plan		=&	$objplan;

		if ( empty( $params ) ) {
			$params	=&	$metaUser->meta->getMIParams( $this->id, $objplan->id );
		}

		$request->params	=&	$params;

		if ( $add !== false ) {
			$request->add	=& $add;
		} else {
			$request->add	= null;
		}

		// Call Action
		if ( method_exists( $this->mi_class, $stage ) ) {
			$return = $this->mi_class->$stage( $request );
		} elseif ( method_exists( $this->mi_class, 'relayAction' ) ) {
			switch ( $stage ) {
				case 'action':
					$request->area = '';
					break;
				case 'pre_expiration_action':
					$request->area = '_pre_exp';
					break;
				case 'expiration_action':
					$request->area = '_exp';
					break;
				default:
					$request->area = $stage;
					break;
			}

			$return = $this->mi_class->relayAction( $request );
		} else {
			return null;
		}

		// Gather Errors and Warnings
		$errors = $this->getErrors();
		$warnings = $this->getWarnings();

		if ( ( $errors !== false ) || ( $warnings !== false )  ) {
			$level = 2;
			$error = 'The MI "' . $this->name . '" ('.$this->class_name.') encountered problems.';

			if ( $warnings !== false ) {
				$error .= ' ' . $warnings;
				$level = 32;
			}

			if ( $errors !== false ) {
				$error .= ' ' . $errors;
				$level = 128;
			}

			if ( !empty( $request->invoice->invoice_number ) ) {
				$params = array( 'invoice_number' => $request->invoice->invoice_number );
			} else {
				$params = array();
			}

			$eventlog = new eventLog( $db );
			$eventlog->issue( 'MI application problems', 'mi, problems, '.$this->class_name, $error, $level, $params );
		}

		// If returning fatal error, issue additional entry
		if ( $return === false ) {
			$db = &JFactory::getDBO();

			$error = 'The MI "' . $this->name . '" ('.$this->class_name.') could not be carried out due to errors, plan application was halted';

			$err = $db->getErrorMsg();
			if ( !empty( $err ) ) {
				$error .= ' Last Database Error: ' . $err;
			}

			if ( !empty( $request->invoice->invoice_number ) ) {
				$params = array( 'invoice_number' => $request->invoice->invoice_number );
			} else {
				$params = array();
			}

			$eventlog = new eventLog( $db );
			$eventlog->issue( 'MI application failed', 'mi, failure, '.$this->class_name, $error, 128, $params );
		}

		return $return;
	}

	function getMIform( $plan, $metaUser )
	{
		$params	= $metaUser->meta->getMIParams( $this->id, $plan->id, false );

		$request = new stdClass();
		$request->action	=	'getMIform';
		$request->parent	=&	$this;
		$request->metaUser	=&	$metaUser;
		$request->plan		=&	$plan;
		$request->params	=&	$params;

		return $this->functionProxy( 'getMIform', $request );
	}

	function verifyMIform( $plan, $metaUser, $params=null )
	{
		if ( is_null( $params ) ) {
			$params	= $metaUser->meta->getMIParams( $this->id, $plan->id, false );
		}

		$request = new stdClass();
		$request->action	=	'verifyMIform';
		$request->parent	=&	$this;
		$request->metaUser	=&	$metaUser;
		$request->plan		=&	$plan;
		$request->params	=&	$params;

		return $this->functionProxy( 'verifyMIform', $request );
	}

	function getMIformParams( $plan, $metaUser, $errors )
	{
		$mi_form = $this->getMIform( $plan, $metaUser );

		$params	= array();
		$lists	= array();
		if ( !empty( $mi_form ) ) {
			$pref = 'mi_'.$this->id.'_';

			if ( !empty( $mi_form['lists'] ) ) {
				foreach ( $mi_form['lists'] as $lname => $lcontent ) {
					$tempname = $pref.$lname;
					$lists[$tempname] = str_replace( '"'.$lname.'"', '"'.$tempname.'"', $lcontent );
				}

				unset( $mi_form['lists'] );
			}

			$params[$pref.'remap_area'] = array( 'subarea_change', $this->class_name );

			if ( array_key_exists( $this->id, $errors ) ) {
				$params[] = array( 'divstart', null, null, 'confirmation_error_bg' );
				//$params[] = array( 'h2', $errors[$mi->id] );
			}

			foreach ( $mi_form as $fname => $fcontent ) {
				$params[$pref.$fname] = $fcontent;
			}

			if ( array_key_exists( $this->id, $errors ) ) {
				$params[] = array( 'divend' );
			}
		}

		$params['lists'] = $lists;

		return $params;
	}

	function getErrors()
	{
		if ( !empty( $this->mi_class->error ) && is_array( $this->mi_class->error ) ) {
			if ( count( $this->mi_class->error ) > 1 ) {
				$return = 'Error:';
			} else {
				$return = 'Errors:';
			}

			foreach ( $this->mi_class->error as $error ) {
				$return .= ' ' . $error;
			}
		} else {
			return false;
		}

		return $return;
	}

	function getWarnings()
	{
		if ( !empty( $this->mi_class->warning ) ) {
			if ( count( $this->mi_class->warning ) > 1 ) {
				$return = 'Warning:';
			} else {
				$return = 'Warnings:';
			}

			foreach ( $this->mi_class->warning as $warning ) {
				$return .= ' ' . $warning;
			}
		} else {
			return false;
		}

		return $return;
	}

	function aecEventHook( $event )
	{
		if ( empty( $this->mi_class ) ) {
			$this->callIntegration();
		}

		return $this->functionProxy( 'aecEventHook', $event );
	}

	function on_userchange_action( $row, $post, $trace )
	{
		$request = new stdClass();
		$request->parent			=& $this;
		$request->row				=& $row;
		$request->post				=& $post;
		$request->trace				=& $trace;

		return $this->functionProxy( 'on_userchange_action', $request );
	}

	function profile_info( $metaUser )
	{
		$request = new stdClass();
		$request->parent	=&	$this;
		$request->metaUser	=&	$metaUser;

		return $this->functionProxy( 'profile_info', $request );
	}

	function admin_info( $metaUser )
	{
		$request = new stdClass();
		$request->parent	=&	$this;
		$request->metaUser	=&	$metaUser;

		return $this->functionProxy( 'admin_info', $request );
	}

	function profile_form( $metaUser )
	{
		$request = new stdClass();
		$request->parent	=&	$this;
		$request->metaUser	=&	$metaUser;
		$request->params	=&	$metaUser->meta->getMIParams( $this->id );

		$settings = $this->functionProxy( 'profile_form', $request, array() );

		if ( !empty( $settings ) ) {
			foreach ( $settings as $k => $v ) {
				if ( isset( $request->params[$k] ) && !isset( $v[3] ) ) {
					$settings[$v][3] = $request->params[$k];
				}
			}
		}

		return $settings;
	}

	function profile_form_save( $metaUser, $params )
	{
		$request = new stdClass();
		$request->parent		=&	$this;
		$request->metaUser		=&	$metaUser;
		$request->old_params	=	$metaUser->meta->getMIParams( $this->id );
		$request->params		=	$params;

		return $this->functionProxy( 'profile_form_save', $request );
	}

	function admin_form( $metaUser )
	{
		$request = new stdClass();
		$request->parent	=&	$this;
		$request->metaUser	=&	$metaUser;
		$request->params	=&	$metaUser->meta->getMIParams( $this->id );

		$settings = $this->functionProxy( 'admin_form', $request, array() );

		if ( !empty( $settings ) ) {
			foreach ( $settings as $k => $v ) {
				if ( isset( $request->params[$k] ) && !isset( $v[3] ) ) {
					$settings[$k][3] = $request->params[$k];
				}
			}
		}

		return $settings;
	}

	function admin_form_save( $metaUser, $params )
	{
		$request = new stdClass();
		$request->parent		=&	$this;
		$request->metaUser		=&	$metaUser;
		$request->old_params	=	$metaUser->meta->getMIParams( $this->id );
		$request->params		=	$params;

		return $this->functionProxy( 'admin_form_save', $request );
	}

	function getInfo()
	{
		$lang = JFactory::getLanguage();

		if ( method_exists( $this->mi_class, 'Info' ) ) {
			$this->info = $this->mi_class->Info();
		} else {
			$nname = strtoupper( 'aec_' . $this->class_name . '_name' );
			$ndesc = strtoupper( 'aec_' . $this->class_name . '_desc' );

			$this->info = array();
			if ( $lang->hasKey( $nname ) && $lang->hasKey( $ndesc ) ) {
				$this->info['name'] = JText::_( $nname );
				$this->info['desc'] = JText::_( $ndesc );
			} else {
				$this->info['name'] = 'NONAME';
				$this->info['desc'] = 'NODESC';
			}
		}
	}

	function getGeneralSettings()
	{
		$settings['name']					= array( 'inputC', '' );
		$settings['desc']					= array( 'inputD', '' );
		$settings['active']					= array( 'toggle', 1 );
		$settings['_aec_action']			= array( 'toggle', 1 );
		$settings['_aec_only_first_bill']	= array( 'toggle', 0 );
		$settings['auto_check']				= array( 'toggle', 1 );
		$settings['_aec_global_exp_all']	= array( 'toggle', 0 );
		$settings['on_userchange']			= array( 'toggle', 1 );
		$settings['pre_exp_check']			= array( 'inputB', '' );
		$settings['has_restrictions']		= array( 'toggle', 0 );
		$settings['sticky_permissions']		= array( 'toggle', 1 );

		return $settings;
	}

	function getCommonData()
	{
		$common = array();
		if ( method_exists( $this->mi_class, 'CommonData' ) && empty( $this->settings ) ) {
			$common_data = $this->mi_class->CommonData();

			if ( !empty( $common_data ) ) {
					$db = &JFactory::getDBO();

					$query = 'SELECT id'
						 	. ' FROM #__acctexp_microintegrations'
						 	. ' WHERE `class_name` = \'' . $this->class_name . '\''
						 	. ' ORDER BY `id` DESC'
						 	;
					$db->setQuery( $query );
					$last_id = $db->loadResult();

					if ( $last_id ) {
						$last_mi = new microIntegration( $db );
						$last_mi->load( $last_id );

						foreach ( $common_data as $key ) {
							// Give the defaults a chance if this instance has empty fields
							if ( !empty( $last_mi->settings[$key] ) ) {
								$common[$key] = $last_mi->settings[$key];
							}
						}
					}
			}
		}

		return $common;
	}

	function getSettings()
	{
		// See whether an install is neccessary (and possible)
		if ( method_exists( $this->mi_class, 'checkInstallation' ) && method_exists( $this->mi_class, 'install' ) ) {
			if ( !$this->mi_class->checkInstallation() ) {
				$this->mi_class->install();
			}
		}

		if ( method_exists( $this->mi_class, 'Settings' ) ) {
			if ( empty( $this->settings ) ) {
				$common = $this->getCommonData();
			} else {
				$common = array();
			}

			if ( method_exists( $this->mi_class, 'Defaults' ) && ( count( $this->settings ) < 4 ) ) {
				$defaults = $this->mi_class->Defaults();
			} else {
				$defaults = array();
			}

			$this->mi_class->_parent =& $this;

			$settings = $this->mi_class->Settings();

			// Autoload Params if they have not been called in by the MI
			foreach ( $settings as $name => $setting ) {
				// Do we have a parameter at first position?
				if ( isset( $setting[1] ) && !isset( $setting[3] ) ) {
					if ( isset( $this->settings[$name] ) ) {
						$settings[$name][3] = $this->settings[$name];
					} elseif( isset( $common[$name] ) ) {
						$settings[$name][3] = $common[$name];
					} elseif( isset( $defaults[$name] ) ) {
						$settings[$name][3] = $defaults[$name];
					}
				} else {
					if ( isset( $this->settings[$name] ) ) {
						$settings[$name][1] = $this->settings[$name];
					} elseif( isset( $common[$name] ) ) {
						$settings[$name][1] = $common[$name];
					} elseif( isset( $defaults[$name] ) ) {
						$settings[$name][1] = $defaults[$name];
					}
				}
			}

			return $settings;
		} else {
			return false;
		}
	}

	function exchangeSettings( $exchange )
	{
		 if ( !empty( $exchange ) ) {
			 foreach ( $exchange as $key => $value ) {
				if( is_string( $value ) ) {
					if ( strcmp( $value, '[[SET_TO_NULL]]' ) === 0 ) {
						// Exception for NULL case
						$this->settings[$key] = null;
					} else {
						$this->settings[$key] = $value;
					}
				} else {
					$this->settings[$key] = $value;
				}
			 }
		 }
	}

	function savePostParams( $array )
	{
		// Strip out params that we don't need
		$params = $this->stripNonParams( $array );

		// Filter out restrictions
		$fixed = aecRestrictionHelper::paramList();

		$fixed[] = 'has_restrictions';
		$fixed[] = 'sticky_permissions';

		$restrictions = array();
		foreach ( $fixed as $varname ) {
			if ( !isset( $array[$varname] ) ) {
				continue;
			}

			$restrictions[$varname] = $array[$varname];

			unset( $array[$varname] );
		}

		$this->restrictions = $restrictions;

		// Check whether there is a custom function for saving params
		$new_params = $this->functionProxy( 'saveparams', $params, $params );

		$this->name				= $array['name'];
		$this->desc				= $array['desc'];
		$this->active			= $array['active'];
		$this->auto_check		= $array['auto_check'];
		$this->on_userchange	= $array['on_userchange'];
		$this->pre_exp_check	= $array['pre_exp_check'];

		if ( !empty( $new_params['rebuild'] ) ) {
			$db = &JFactory::getDBO();

			$planlist = MicroIntegrationHandler::getPlansbyMI( $this->id );

			foreach ( $planlist as $planid ) {
				$plan = new SubscriptionPlan( $db );
				$plan->load( $planid );

				$userlist = SubscriptionPlanHandler::getPlanUserlist( $planid );
				foreach ( $userlist as $userid ) {
					$metaUser = new metaUser( $userid );

					if ( $metaUser->cmsUser->id ) {
						$this->action( $metaUser, $params, null, $plan );
					}
				}
			}

			$newparams['rebuild'] = 0;
		}

		if ( !empty( $new_params['remove'] ) ) {
			$db = &JFactory::getDBO();

			$planlist = MicroIntegrationHandler::getPlansbyMI( $this->id );

			foreach ( $planlist as $planid ) {
				$plan = new SubscriptionPlan( $db );
				$plan->load( $planid );

				$userlist = SubscriptionPlanHandler::getPlanUserlist( $planid );
				foreach ( $userlist as $userid ) {
					$metaUser = new metaUser( $userid );

					$this->expiration_action( $metaUser, $plan );
				}
			}

			$newparams['remove'] = 0;
		}

		$this->params = $new_params;

		return true;
	}

	function stripNonParams( $array )
	{
		// All variables of the class have to be stripped out
		$vars = get_class_vars( 'microIntegration' );

		foreach ( $vars as $name => $blind ) {
			if ( isset( $array[$name] ) ) {
				unset( $array[$name] );
			}
		}

		return $array;
	}

	function registerProfileTabs()
	{
		if ( method_exists( $this->mi_class, 'registerProfileTabs' ) ) {
			$response = $this->mi_class->registerProfileTabs();
		} else {
			$response = null;
		}

		return $response;
	}

	function customProfileTab( $action, $metaUser )
	{
		if ( empty( $this->settings ) ) {
			$this->getSettings();
		}

		$method = 'customtab_' . $action;

		if ( method_exists( $this->mi_class, $method ) ) {
			$db = &JFactory::getDBO();

			$request = new stdClass();
			$request->parent			=& $this;
			$request->metaUser			=& $metaUser;

			$invoice = new Invoice( $db );
			$invoice->loadbySubscriptionId( $metaUser->objSubscription->id );

			$request->invoice			=& $invoice;


			return $this->mi_class->$method( $request );
		} else {
			return false;
		}
	}

	function delete ()
	{
		// Maybe this function needs special actions on delete?
		// TODO: There should be a way to manage complete deletion of use of an MI type
		if ( method_exists( $this->mi_class, 'delete' ) ){
			$this->mi_class->delete();
		}
	}
}

class couponsHandler extends eucaObject
{
	/** @var bool - Was the cart changed? Needed to signal reload action */
	var $affectedCart		= false;
	/** @var array - Coupons that should not be applied on any later action */
	var $noapplylist		= array();
	/** @var array - List of coupons */
	var $coupons_list		= array();
	/** @var array - Coupons that will be applied to the whole cart */
	var $fullcartlist		= array();
	/** @var array - Global List of coupon mix rules */
	var $mixlist	 		= array();
	/** @var array - Global List of applied coupons */
	var $global_applied 	= array();
	/** @var array - Local List of excluding coupons */
	var $item_applied 		= array();
	/** @var array - Coupons that need to be deleted */
	var $delete_list	 	= array();
	/** @var array - Exceptions that need to be addressed (by the user) */
	var $exceptions			= array();

	function couponsHandler( $metaUser, $InvoiceFactory, $coupons )
	{
		$this->metaUser			=& $metaUser;
		$this->InvoiceFactory	=& $InvoiceFactory;
		$this->coupons			=& $coupons;
	}

	function raiseException( $exception )
	{
		$this->exceptions[] = $exception;
	}

	function getExceptions()
	{
		return $this->exceptions;
	}

	function addCouponToRecord( $itemid, $coupon_code, $ccombo )
	{
		if ( !empty( $ccombo['bad_combinations_cart'] ) ) {
			if ( !empty( $this->mixlist['global']['restrictmix'] ) ) {
				$this->mixlist['global']['restrictmix'] = array_merge( $this->mixlist['global']['restrictmix'], $ccombo['bad_combinations_cart'] );
			} else {
				$this->mixlist['global']['restrictmix'] = $ccombo['bad_combinations_cart'];
			}
		}

		if ( !empty( $ccombo['good_combinations_cart'] ) ) {
			if ( !empty( $this->mixlist['global']['allowmix'] ) ) {
				$this->mixlist['global']['allowmix'] = array_merge( $this->mixlist['global']['allowmix'], $ccombo['good_combinations_cart'] );
			} else {
				$this->mixlist['global']['allowmix'] = $ccombo['good_combinations_cart'];
			}
		}

		$this->global_applied[] = $coupon_code;

		if ( $itemid !== false ) {
			if ( !empty( $ccombo['bad_combinations'] ) ) {
				if ( !empty( $this->mixlist['local'][$itemid]['restrictmix'] ) ) {
					$this->mixlist['local'][$itemid]['restrictmix'] = array_merge( $this->mixlist['local'][$itemid]['restrictmix'], $ccombo['bad_combinations'] );
				} else {
					$this->mixlist['local'][$itemid]['restrictmix'] = $ccombo['bad_combinations'];
				}
			}

			if ( !empty( $ccombo['good_combinations'] ) ) {
				if ( !empty( $this->mixlist['local'][$itemid]['allowmix'] ) ) {
					$this->mixlist['local'][$itemid]['allowmix'] = array_merge( $this->mixlist['local'][$itemid]['allowmix'], $ccombo['good_combinations'] );
				} else {
					$this->mixlist['local'][$itemid]['allowmix'] = $ccombo['good_combinations'];
				}
			}

			$this->item_applied[$itemid][] = $coupon_code;
		}
	}

	function mixCheck( $itemid, $coupon_code, $ccombo )
	{
		// First check whether any other coupon in the cart could block this
		if ( !empty( $this->mixlist['global']['allowmix'] ) ) {
			// Or maybe it just blocks everything?
			if ( !is_array( $this->mixlist['global']['allowmix'] ) ) {
				return false;
			} else {
				// Nope, check which ones it blocks
				if ( !in_array( $coupon_code, $this->mixlist['global']['allowmix'] ) ) {
					return false;
				}
			}
		}

		if ( $itemid !== false ) {
			// Now check whether any other coupon for this item could block this
			if ( !empty( $this->mixlist['local'][$itemid]['allowmix'] ) ) {
				// Or maybe it just blocks everything?
				if ( !is_array( $this->mixlist['local'][$itemid]['allowmix'] ) ) {
					return false;
				} else {
					// Nope, check which ones it blocks
					if ( !in_array( $coupon_code, $this->mixlist['local'][$itemid]['allowmix'] ) ) {
						return false;
					}
				}
			}
		}

		if ( !empty( $this->global_applied ) && !empty( $ccombo['good_combinations_cart'] ) ) {
			// Now check whether any other coupon in the cart could interfere with this ones restrictions
			// Maybe it just blocks everything?
			if ( !is_array( $ccombo['good_combinations_cart'] ) ) {
				return false;
			} else {
				// Nope, check which ones it blocks
				if ( !count( array_intersect( $this->global_applied, $ccombo['good_combinations_cart'] ) ) ) {
					return false;
				}
			}
		}

		if ( $itemid !== false ) {
			if ( !empty( $this->item_applied[$itemid] ) && !empty( $ccombo['good_combinations'] ) ) {
				// Now check whether any other coupon for this item could interfere with this ones restrictions
				// Maybe it just blocks everything?
				if ( !is_array( $ccombo['good_combinations'] ) ) {
					return false;
				} else {
					// Nope, check which ones it blocks
					if ( !count( array_intersect( $this->item_applied[$itemid], $ccombo['good_combinations'] ) ) ) {
						return false;
					}
				}
			}
		}

		// Now check for restrictions the other way around
		if ( !empty( $this->mixlist['global']['restrictmix'] ) && is_array( $this->mixlist['global']['restrictmix'] ) ) {
			if ( in_array( $coupon_code, $this->mixlist['global']['restrictmix'] ) ) {
				return false;
			}
		}

		if ( $itemid !== false ) {
			if ( !empty( $this->mixlist['local'][$itemid]['restrictmix'] ) && is_array( $this->mixlist['local'][$itemid]['restrictmix'] ) ) {
				if ( in_array( $coupon_code, $this->mixlist['local'][$itemid]['restrictmix'] ) ) {
					return false;
				}
			}
		}

		if ( !empty( $this->global_applied ) && !empty( $ccombo['bad_combinations_cart'] ) && is_array( $ccombo['bad_combinations_cart'] ) ) {
			if ( count( array_intersect( $this->global_applied, $ccombo['bad_combinations_cart'] ) ) ) {
				return false;
			}
		}

		if ( $itemid !== false ) {
			if ( !empty( $this->item_applied[$itemid] ) && !empty( $ccombo['bad_combinations'] ) && is_array( $ccombo['bad_combinations'] ) ) {
				if ( count( array_intersect( $this->item_applied[$itemid], $ccombo['bad_combinations'] ) ) ) {
					return false;
				}
			}
		}

		return true;
	}

	function loadCoupon( $coupon_code, $strict=true )
	{
		if ( in_array( $coupon_code, $this->delete_list ) && $strict ) {
			return false;
		}

		$cph = new couponHandler();
		$cph->load( $coupon_code );

		if ( empty( $cph->coupon->id ) ) {
			$this->setError( "The code entered is not valid" );

			$this->delete_list[] = $coupon_code;
			return false;
		}

		if ( $cph->coupon->coupon_code !== $coupon_code ) {
			$this->setError( "The code entered is not valid" );

			$this->delete_list[] = $coupon_code;
			return false;
		}

		if ( !$cph->status ) {
			$this->setError( $cph->error );

			$this->delete_list[] = $coupon_code;
			return false;
		}

		if ( $cph->coupon->restrictions['cart_multiple_items'] && !empty( $cph->coupon->restrictions['cart_multiple_items_amount'] ) ) {
			if ( !array_key_exists( $coupon_code, $this->max_amount_list ) ) {
				$this->coupons_list[] = array( 'coupon_code' => $coupon_code );

				$this->max_amount_list[$coupon_code] = $this->coupon->restrictions['cart_multiple_items_amount'];
			}

			if ( $this->max_amount_list[$coupon_code] ) {
				$this->max_amount_list[$coupon_code]--;
			} else {
				return false;
			}
		} else {
			$this->coupons_list[] = array( 'coupon_code' => $coupon_code );
		}

		$this->cph = $cph;

		return true;
	}

	function applyToTotal( $items, $cart=false, $fullcart=false )
	{
		$itemcount = 0;
		foreach ( $items->itemlist as $item ) {
			if ( is_object( $item ) ) {
				$itemcount += $item->quantitiy;
			} else {
				$itemcount++;
			}
		}

		if ( !empty( $this->fullcartlist ) ) {
			foreach ( $this->fullcartlist as $coupon_code ) {
				if ( $this->loadCoupon( $coupon_code ) ) {
					if ( $this->cph->discount['amount_use'] ) {
						$this->cph->discount['amount'] = $this->cph->discount['amount'] / $itemcount;
					}

					$cost = null;
					$costarray = array();
					foreach ( $items->itemlist as $cid => $citem ) {
						if ( $citem['obj'] == false ) {
							continue;
						}

						$citem['terms']->nextterm->computeTotal();

						if ( empty( $cost ) ) {
							$cost = clone( $citem['terms']->nextterm->getBaseCostObject() );

							$costarray[$cid] = $cost->cost['amount'];
						} else {
							$ccost = $citem['terms']->nextterm->getBaseCostObject();

							$cost->cost['amount'] = $cost->cost['amount'] + ( $ccost->cost['amount'] * $citem['quantity'] );

							$costarray[$cid] = $ccost->cost['amount'];
						}

						$items->itemlist[$cid]['terms'] = $this->cph->applyToTerms( $items->itemlist[$cid]['terms'], true );
					}

				}
			}

			$discounttypes = array();
			$discount_col = array();
			foreach ( $items->itemlist as $item ) {
				foreach ( $item['terms']->nextterm->cost as $cost ) {
					if ( $cost->type == 'discount' ) {
						$cc = $cost->cost['coupon'] . ' - ' . $cost->cost['details'];

						if ( in_array( $cc, $discounttypes ) ) {
							$typeid = array_search( $cc, $discounttypes );
						} else {
							$discounttypes[] = $cc;

							$typeid = count( $discounttypes ) - 1;
						}

						if ( !isset( $discount_col[$typeid] ) ) {
							$discount_col[$typeid] = 0;
						}

						$discount_col[$typeid] += $cost->renderCost();
					}
				}
			}

			if ( !empty( $discount_col ) ) {
				// Dummy terms
				$terms = new mammonTerms();
				$term = new mammonTerm();

				foreach ( $discount_col as $cid => $discount ) {
					$cce = explode( ' - ', $discounttypes[$cid], 2 );

					$term->addCost( $discount, array( 'amount' => $discount, 'coupon' => $cce[0], 'details' => $cce[1] ) );
				}

				$terms->addTerm( $term );

				if ( empty( $items->discount ) ) {
					$items->discount = array();
				}

				$items->discount[] = $terms->terms;
			}

		}

		return $items;
	}

	function applyToCart( $items, $cart=false, $fullcart=false )
	{
		$this->prefilter( $items, $cart, $fullcart );

		foreach ( $items as $iid => $item ) {
			$items[$iid] = $this->applyAllToItems( $iid, $item, $cart );
		}

		return $items;
	}

	function prefilter( $items, $cart=false, $fullcart=false )
	{
		foreach ( $this->coupons as $ccid => $coupon_code ) {
			if ( !$this->loadCoupon( $coupon_code ) ) {
				continue;
			}

			if ( $this->cph->coupon->restrictions['usage_cart_full'] ) {
				if ( !in_array( $coupon_code, $this->fullcartlist ) ) {
					$this->fullcartlist[] = $coupon_code;

					if ( !empty( $cart ) ) {
						if ( !$cart->hasCoupon( $coupon_code ) ) {
							$cart->addCoupon( $coupon_code );
							$cart->storeload();

							$this->affectedCart = true;
						}
					}

					continue;
				}
			}

			if ( empty( $cart ) && empty( $fullcart ) ) {
				return;
			}

			$plans = $cart->getItemIdArray();

			if ( $this->cph->coupon->restrictions['usage_plans_enabled'] ) {
				$allowed = array_intersect( $plans, $this->cph->coupon->restrictions['usage_plans'] );

				if ( empty( $allowed ) ) {
					$allowed = false;
				}
			} else {
				$allowed = $plans;
			}

			foreach ( $cart->content as $iid => $c ) {
				if ( $cart->hasCoupon( $coupon_code, $iid ) ) {
					continue 2;
				}
			}

			if ( !is_array( $allowed ) ) {
				continue;
			}

			$fname = 'cartcoupon_'.$ccid.'_item';

			$pgsel = aecGetParam( $fname, null, true, array( 'word', 'int' ) );

			if ( ( count( $allowed ) == 1 ) ) {
				$min = array_shift( array_keys( $allowed ) );

				foreach ( $items as $iid => $item ) {
					if ( $item['obj']->id == $allowed[$min] ) {
						$pgsel = $iid;
					}
				}
			}

			if ( !is_null( $pgsel ) ) {
				$items[$pgsel] == $this->applyToItem( $pgsel, $items[$pgsel], $coupon_code );

				if ( !$cart->hasCoupon( $coupon_code, $pgsel ) ) {
					$cart->addCoupon( $coupon_code, $pgsel );
					$cart->storeload();

					$this->affectedCart = true;
				}
			} else {
				$found = false;
				foreach ( $cart->content as $cid => $content ) {
					if ( $cart->hasCoupon( $coupon_code, $cid ) ) {
						$items[$cid] == $this->applyToItem( $cid, $items[$cid], $coupon_code );
						$found = true;

						$this->noapplylist[] = $coupon_code;
					}
				}

				if ( !$found ) {
					$ex = array();
					$ex['head'] = "Select Item for Coupon \"" . $coupon_code . "\"";
					$ex['desc'] = "The coupon you have entered can be applied to one of the following items:<br />";

					$ex['rows'] = array();

					foreach ( $allowed as $cid => $objid ) {
						if ( empty( $fullcart[$cid]['free'] ) ) {
							$ex['rows'][] = array( 'radio', $fname, $cid, true, $fullcart[$cid]['name'] );
						}
					}

					if ( !empty( $ex['rows'] ) ) {
						$this->raiseException( $ex );
					}
				}
			}
		}
	}

	function applyToItemList( $items )
	{
		foreach ( $items as $iid => $item ) {
			$items[$iid] = $this->applyAllToItems( $iid, $item );
		}

		return $items;
	}

	function applyAllToItems( $id, $item, $cart=false )
	{
		$this->global_applied = array();

		$hasterm = !empty( $item['terms'] );

		if ( $hasterm ) {
			if ( !empty( $item['terms']->terms[0]->type ) ) {
				$termtype = $item['terms']->terms[0]->type;
			} else {
				$termtype = null;
			}
		} else {
			$termtype = null;
		}

		if ( empty( $item['obj'] ) && ( !$hasterm || ( $termtype == "total" ) ) ) {
			// This is the total item - apply total coupons - totally
			foreach ( $this->coupons as $coupon_code ) {
				if ( in_array( $coupon_code, $this->fullcartlist ) ) {
					$item = $this->applyToItem( $id, $item, $coupon_code );
				}
			}
		} else {
			if ( !empty( $this->coupons ) ) {
				foreach ( $this->coupons as $coupon_code ) {
					if ( in_array( $coupon_code, $this->noapplylist ) ) {
						continue;
					}

					if ( $this->loadCoupon( $coupon_code ) ) {
						if ( $cart != false ) {
							if ( $cart->hasCoupon( $coupon_code, $id ) ) {
								$item = $this->applyToItem( $id, $item, $coupon_code );
							}
						} else {
							$item = $this->applyToItem( $id, $item, $coupon_code );
						}
					}
				}
			}
		}

		$item['terms']->checkFree();

		return $item;
	}

	function applyToItem( $id, $item, $coupon_code )
	{
		if ( !$this->loadCoupon( $coupon_code, false ) ) {
			return $item;
		}

		if ( !empty( $this->item_applied[$id] ) ) {
			if ( in_array( $coupon_code, $this->item_applied[$id] ) ) {
				return $item;
			}
		}

		if ( isset( $item['terms'] ) ) {
			$terms = $item['terms'];
		} elseif ( isset( $item['obj'] ) ) {
			if ( !empty( $this->invoice ) ) {
				$terms = $item['obj']->getTerms( false, $this->metaUser->focusSubscription, $this->metaUser, $this->invoice );
			} else {
				$terms = $item['obj']->getTerms( false, $this->metaUser->focusSubscription, $this->metaUser );
			}
		} elseif ( isset( $item['cost'] ) ) {
			$terms = $item['cost'];
		} else {
			return $item;
		}

		$ccombo		= $this->cph->getCombinations();

		if ( !empty( $item['obj']->id ) ) {
			$this->InvoiceFactory->usage = $item['obj']->id;
			
			$usage = $item['obj']->id;
		} elseif ( !empty( $this->InvoiceFactory->usage ) ) {
			$usage = $this->InvoiceFactory->usage;
		} else {
			$usage = 0;
		}

		if ( !$this->mixCheck( $id, $coupon_code, $ccombo ) ) {
			$this->setError( JText::_('COUPON_ERROR_COMBINATION') );
		} else {
			if ( $this->cph->status ) {
				// Coupon approved, checking restrictions
				$r = $this->cph->checkRestrictions( $this->metaUser, $terms, $usage );

				if ( $this->cph->status ) {
					$item['terms'] = $this->cph->applyToTerms( $terms );

					$this->addCouponToRecord( $id, $coupon_code, $ccombo );

					return $item;
				} else {
					$this->setError( $this->cph->error );
				}
			}
		}

		$this->delete_list[] = $coupon_code;

		return $item;
	}

	function applyToAmount( $amount, $original_amount=null )
	{
		if ( empty( $this->coupons ) || !is_array( $this->coupons ) ) {
			return $amount;
		}

		foreach ( $this->coupons as $coupon_code ) {
			if ( !$this->loadCoupon( $coupon_code ) ) {
				continue;
			}

			$ccombo	= $this->cph->getCombinations();

			if ( !$this->mixCheck( false, $coupon_code, $ccombo ) ) {
				$this->setError( JText::_('COUPON_ERROR_COMBINATION') );
			} else {
				if ( $this->cph->status ) {
					// Coupon approved, checking restrictions
					$this->cph->checkRestrictions( $this->metaUser, $amount, $original_amount, $this->InvoiceFactory->usage );

					if ( $this->cph->status ) {
						$amount = $this->cph->applyCoupon( $amount );

						$this->addCouponToRecord( false, $coupon_code, $ccombo );
					}
				}
			}
		}

		$this->setError( $this->cph->error );

		return $amount;
	}

}

class couponHandler
{
	/** @var bool */
	var $status				= null;
	/** @var string */
	var $error				= null;
	/** @var object */
	var $coupon				= null;

	function couponHandler(){}

	function setError( $error )
	{
		$this->status = false;

		$this->error = $error;
	}

	function idFromCode( $coupon_code )
	{
		$db = &JFactory::getDBO();

		$return = array();

		// Get this coupons id from the static table
		$query = 'SELECT `id`'
				. ' FROM #__acctexp_coupons_static'
				. ' WHERE `coupon_code` = \'' . $coupon_code . '\''
				;
		$db->setQuery( $query );
		$couponid = $db->loadResult();

		if ( $couponid ) {
			// Its static, so set type to 1
			$return['type'] = 1;
		} else {
			// Coupon not found, take the regular table
			$query = 'SELECT `id`'
					. ' FROM #__acctexp_coupons'
					. ' WHERE `coupon_code` = \'' . $coupon_code . '\''
					;
			$db->setQuery( $query );
			$couponid = $db->loadResult();

			// Its not static, so set type to 0
			$return['type'] = 0;
		}

		$return['id'] = $couponid;

		return $return;
	}

	function load( $coupon_code )
	{
		$app = JFactory::getApplication();

		$db = &JFactory::getDBO();

		$cc = $this->idFromCode( $coupon_code );

		$this->type = $cc['type'];

		if ( $cc['id'] ) {
			// Status = OK
			$this->status = true;

			// establish coupon object
			$this->coupon = new coupon( $db, $this->type );
			$this->coupon->load( $cc['id'] );

			// Check whether coupon is active
			if ( !$this->coupon->active ) {
				$this->setError( JText::_('COUPON_ERROR_EXPIRED') );
			}

			// load parameters into local array
			$this->discount		= $this->coupon->discount;
			$this->restrictions = $this->coupon->restrictions;

			// Check whether coupon can be used yet
			if ( $this->restrictions['has_start_date'] && !empty( $this->restrictions['start_date'] ) ) {
				$expstamp = strtotime( $this->restrictions['start_date'] );

				// Error: Use of this coupon has not started yet
				if ( ( $expstamp > 0 ) && ( ( $expstamp - ( (int) gmdate('U') ) ) > 0 ) ) {
					$this->setError( JText::_('COUPON_ERROR_NOTSTARTED') );
				}
			}

			// Check whether coupon is expired
			if ( $this->restrictions['has_expiration'] ) {
				$expstamp = strtotime( $this->restrictions['expiration'] );

				// Error: Use of this coupon has expired
				if ( ( $expstamp > 0 ) && ( ( $expstamp - ( (int) gmdate('U') ) ) < 0 ) ) {
					$this->setError( JText::_('COUPON_ERROR_EXPIRED') );
					$this->coupon->deactivate();
				}
			}

			// Check for max reuse
			if ( !empty( $this->restrictions['has_max_reuse'] ) ) {
				if ( !empty( $this->restrictions['max_reuse'] ) ) {
					// Error: Max Reuse of this coupon is exceeded
					if ( (int) $this->coupon->usecount > (int) $this->restrictions['max_reuse'] ) {
						$this->setError( JText::_('COUPON_ERROR_MAX_REUSE') );
						return;
					}
				}
			}

			// Check for dependency on subscription
			if ( !empty( $this->restrictions['depend_on_subscr_id'] ) ) {
				if ( $this->restrictions['subscr_id_dependency'] ) {
					// See whether this subscription is active
					$query = 'SELECT `status`'
							. ' FROM #__acctexp_subscr'
							. ' WHERE `id` = \'' . $this->restrictions['subscr_id_dependency'] . '\''
							;
					$db->setQuery( $query );

					$subscr_status = strtolower( $db->loadResult() );

					// Error: The Subscription this Coupon depends on has run out
					if ( ( strcmp( $subscr_status, 'active' ) === 0 ) || ( ( strcmp( $subscr_status, 'trial' ) === 0 ) && $this->restrictions['allow_trial_depend_subscr'] ) ) {
						$this->setError( JText::_('COUPON_ERROR_SPONSORSHIP_ENDED') );
						return;
					}
				}
			}
		} else {
			// Error: Coupon does not exist
			$this->setError( JText::_('COUPON_ERROR_NOTFOUND') );
		}
	}

	function forceload( $coupon_code )
	{
		$db = &JFactory::getDBO();

		$cc = $this->idFromCode( $coupon_code );

		$this->type = $cc['type'];

		if ( $cc['id'] ) {
			// Status = OK
			$this->status = true;

			// establish coupon object
			$this->coupon = new coupon( $db, $this->type );
			$this->coupon->load( $cc['id'] );
			return true;
		} else {
			return false;
		}
	}

	function switchType()
	{
		$db = &JFactory::getDBO();

		$oldtype = $this->coupon->type;

		// Duplicate Coupon at other table
		$coupon = new coupon( $db, !$oldtype );
		$coupon->createNew( $this->coupon->coupon_code, $this->coupon->created_date );

		// Switch id over to new table max
		$oldid = $this->coupon->id;

		$this->coupon->delete();

		$this->coupon = $coupon;

		// Migrate usage entries
		$query = 'UPDATE #__acctexp_couponsxuser'
				. ' SET `coupon_id` = \'' . $this->coupon->id . '\', `coupon_type` = \'' . $this->coupon->type . '\''
				. ' WHERE `coupon_id` = \'' . $oldid . '\' AND `coupon_type` = \'' . $oldtype . '\''
				;

		$db->setQuery( $query );
		$db->query();
	}

	function incrementCount( $invoice )
	{
		$db = &JFactory::getDBO();

		// Get existing coupon relations for this user
		$query = 'SELECT `id`'
				. ' FROM #__acctexp_couponsxuser'
				. ' WHERE `userid` = \'' . $invoice->userid . '\''
				. ' AND `coupon_id` = \'' . $this->coupon->id . '\''
				. ' AND `coupon_type` = \'' . $this->type . '\''
				;

		$db->setQuery( $query );
		$id = $db->loadResult();

		$couponxuser = new couponXuser( $db );

		if ( !empty( $id ) ) {
			// Relation exists, update count
			$app = JFactory::getApplication();

			$couponxuser->load( $id );
			$couponxuser->usecount += 1;
			$couponxuser->addInvoice( $invoice->invoice_number );
			$couponxuser->last_updated = date( 'Y-m-d H:i:s', ( (int) gmdate('U') ) );
			$couponxuser->storeload();
		} else {
			// Relation does not exist, create one
			$couponxuser->createNew( $invoice->userid, $this->coupon, $this->type );
			$couponxuser->addInvoice( $invoice->invoice_number );
			$couponxuser->storeload();
		}

		$this->coupon->incrementcount();
	}

	function decrementCount( $invoice )
	{
		$db = &JFactory::getDBO();

		// Get existing coupon relations for this user
		$query = 'SELECT `id`'
				. ' FROM #__acctexp_couponsxuser'
				. ' WHERE `userid` = \'' . $invoice->userid . '\''
				. ' AND `coupon_id` = \'' . $this->coupon->id . '\''
				. ' AND `coupon_type` = \'' . $this->type . '\''
				;

		$db->setQuery( $query );
		$id = $db->loadResult();

		$couponxuser = new couponXuser( $db );

		// Only do something if a relation exists
		if ( $id ) {
			$app = JFactory::getApplication();

			// Decrement use count
			$couponxuser->load( $id );
			$couponxuser->usecount -= 1;
			$couponxuser->last_updated = date( 'Y-m-d H:i:s', ( (int) gmdate('U') ) );

			if ( $couponxuser->usecount ) {
				// Use count is 1 or above - break invoice relation but leave overall relation intact
				$couponxuser->delInvoice( $invoice->invoice_number );
				$couponxuser->storeload();
			} else {
				// Use count is 0 or below - delete relationship
				$couponxuser->delete();
			}
		}

		$this->coupon->decrementCount();
	}

	function checkRestrictions( $metaUser, $terms=null, $usage=null )
	{
		if ( empty( $metaUser ) ) {
			return false;
		}

		$restrictionHelper = new aecRestrictionHelper();

		// Load Restrictions and resulting Permissions
		$restrictions	= $restrictionHelper->getRestrictionsArray( $this->restrictions );
		$permissions	= $metaUser->permissionResponse( $restrictions );

		// Check for a set usage
		if ( !empty( $this->restrictions['usage_plans_enabled'] ) && !is_null( $usage ) ) {
			if ( !empty( $this->restrictions['usage_plans'] ) ) {
				// Check whether this usage is restricted
				$plans = $this->restrictions['usage_plans'];

				if ( in_array( $usage, $plans ) ) {
					$permissions['usage'] = true;
				} else {
					$permissions['usage'] = false;
				}
			}
		}

		// Check for Trial only
		if ( $this->discount['useon_trial'] && !$this->discount['useon_full'] && is_object( $terms ) ) {
			$permissions['trial_only'] = false;

			if ( $terms->nextterm->type == 'trial' ) {
				$permissions['trial_only'] = true;
			}
		}

		// Check for max reuse per user
		if ( !empty( $this->restrictions['has_max_peruser_reuse'] ) && !empty( $this->restrictions['max_peruser_reuse'] ) ) {
			$used = $metaUser->usedCoupon( $this->coupon->id, $this->type );

			if ( $used == false ) {
				$permissions['max_peruser_reuse'] = true;
			} elseif ( (int) $used  <= (int) $this->restrictions['max_peruser_reuse'] ) {
				// use count was set immediately before, so <= is accurate
				$permissions['max_peruser_reuse'] = true;
			} else {
				$permissions['max_peruser_reuse'] = false;
			}
		}

		// Plot out error messages
		if ( count( $permissions ) ) {
			foreach ( $permissions as $name => $status ) {
				if ( !$status ) {
					$errors = array(	'fixgid'			=> 'permission',
										'mingid'			=> 'permission',
										'maxgid'			=> 'permission',
										'setgid'			=> 'permission',
										'usage'				=> 'wrong_usage',
										'trial_only'		=> 'trial_only',
										'plan_previous'		=> 'wrong_plan_previous',
										'plan_present'		=> 'wrong_plan',
										'plan_overall'		=> 'wrong_plans_overall',
										'plan_amount_min'	=> 'wrong_plan',
										'plan_amount_max'	=> 'wrong_plans_overall',
										'max_reuse'			=> 'max_reuse',
										'max_peruser_reuse'	=> 'max_reuse'
									);

					if ( isset( $errors[$name] ) ) {
						$this->setError( JText::_( strtoupper( '_coupon_error_' . $errors[$name] ) ) );
					} else {
						$this->status = false;
					}

					return false;
				}
			}
		}

		return true;
	}

	function getInfo( $amount )
	{
		$this->code = $this->coupon->coupon_code;
		$this->name = $this->coupon->name;

		if ( is_array( $amount ) ) {
			$newamount = $this->applyCoupon( $amount['amount'] );
		} else {
			$newamount = $this->applyCoupon( $amount );
		}

		// Load amount or convert amount array to current amount
		if ( is_array( $newamount ) ) {
			if ( isset( $newamount['amount1'] ) ) {
				$this->amount = $newamount['amount1'];
			} elseif ( isset( $newamount['amount2'] ) ) {
				$this->amount = $newamount['amount2'];
			} elseif ( isset( $newamount['amount3'] ) ) {
				$this->amount = $newamount['amount3'];
			}
		} else {
			$this->amount = $newamount;
		}

		// Load amount or convert discount amount array to current amount
		if ( is_array( $newamount ) ) {
			if ( isset( $newamount['amount1'] ) ) {
				$this->discount_amount = $amount['amount']['amount1'] - $newamount['amount1'];
			} elseif ( isset( $newamount['amount2'] ) ) {
				$this->discount_amount = $amount['amount']['amount3'] - $newamount['amount2'];
			} elseif ( isset( $newamount['amount3'] ) ) {
				$this->discount_amount = $amount['amount']['amount3'] - $newamount['amount3'];
			}
		} else {
			$this->discount_amount = $amount['amount'] - $newamount;
		}

		$action = '';

		// Convert chosen rules to user information
		if ( $this->discount['percent_first'] ) {
			if ( $this->discount['amount_percent_use'] ) {
				$action .= '-' . $this->discount['amount_percent'] . '%';
			}
			if ( $this->discount['amount_use'] ) {
				if ( !( $action === '' ) ) {
					$action .= ' &amp; ';
				}
				$action .= '-' . $this->discount['amount'];
			}
		} else {
			if ( $this->discount['amount_use']) {
				$action .= '-' . $this->discount['amount'];
			}
			if ($this->discount['amount_percent_use']) {
				if ( !( $action === '' ) ) {
					$action .= ' &amp; ';
				}
				$action .= '-' . $this->discount['amount_percent'] . '%';
			}
		}

		$this->action = $action;
	}

	function getCombinations()
	{
		$combinations = array();

		$cpl = array( 'bad_combinations', 'good_combinations', 'bad_combinations_cart', 'good_combinations_cart' );

		foreach ( $cpl as $cpn ) {
			$cmd = str_replace( "combinations", "combination", $cpn );

			if ( strpos( $cpn, 'bad' ) !== false ) {
				$cmd = str_replace( "bad", "restrict", $cmd );
			} else {
				$cmd = str_replace( "good", "allow", $cmd );
			}

			if ( !empty( $this->restrictions[$cmd] ) && !empty( $this->restrictions[$cpn] ) ) {
				$combinations[$cpn] = $this->restrictions[$cpn];
			} elseif ( !empty( $this->restrictions[$cmd] ) ) {
				$combinations[$cpn] = true;
			} else {
				$combinations[$cpn] = false;
			}
		}

		return $combinations;
	}

	function applyCoupon( $amount )
	{
		// Distinguish between recurring and one-off payments
		if ( is_array( $amount ) ) {
			// Check for Trial Rules
			if ( isset( $amount['amount1'] ) ) {
				if ( $this->discount['useon_trial'] ) {
					if ( $amount['amount1'] > 0 ) {
						$amount['amount1'] = $this->applyDiscount( $amount['amount1'] );
					}
				}
			}

			// Check for Full Rules
			if ( isset( $amount['amount3'] ) ) {
				if ( $this->discount['useon_full'] ) {
					if ( $this->discount['useon_full_all'] ) {
						$amount['amount3']	= $this->applyDiscount( $amount['amount3'] );
					} else {
						// If we have no trial yet, the one-off discount will be one
						if ( empty( $amount['period1'] ) ) {
							$amount['amount1']	= $this->applyDiscount( $amount['amount3'] );
							$amount['period1']	= $amount['period3'];
							$amount['unit1']	= $amount['unit3'];
						} else {
							if ( $amount['amount1'] > 0 ) {
								// If we already have a trial that costs, we can put the discount on that
								$amount['amount1']	= $this->applyDiscount( $amount['amount1'] );
								$amount['period1']	= $amount['period1'];
								$amount['unit1']	= $amount['unit1'];
							} else {
								// Otherwise we need to create a new period
								// Even in case the user cannot get it - then it will just be skipped anyhow
								$amount['amount2']	= $this->applyDiscount( $amount['amount3'] );
								$amount['period2']	= $amount['period3'];
								$amount['unit2']	= $amount['unit3'];
							}
						}
					}
				}
			}
		} else {
			$amount = $this->applyDiscount( $amount );
		}

		return $amount;
	}

	function applyDiscount( $amount )
	{
		// Apply Discount according to rules
		if ( $this->discount['percent_first'] ) {
			if ( $this->discount['amount_percent_use'] ) {
				$amount -= round( ( ( $amount / 100 ) * $this->discount['amount_percent'] ), 2 );
			}
			if ( $this->discount['amount_use'] ) {
				$amount -= $this->discount['amount'];
			}
		} else {
			if ( $this->discount['amount_use'] ) {
				$amount -= $this->discount['amount'];
			}
			if ( $this->discount['amount_percent_use'] ) {
				$amount -= round( ( ( $amount / 100 ) * $this->discount['amount_percent'] ), 2 );
			}
		}

		$amount = round( $amount, 2 );

		if ( $amount <= 0 ) {
			return "0.00";
		} else {
			// Fix Amount if broken and return
			return AECToolbox::correctAmount( $amount );
		}
	}

	function applyToTerms( $terms, $temp_coupon=false )
	{
		$offset = 0;

		// Only allow application on trial when there is one and the pointer is correct
		if ( $this->discount['useon_trial'] && $terms->hasTrial && ( $terms->pointer == 0 ) ) {
			$offset = 0;
		} elseif( $terms->hasTrial ) {
			$offset = 1;
		}

		$info = array();
		$info['coupon'] = $this->coupon->coupon_code;

		if ( $temp_coupon ) {
			$info['temp_coupon'] = true;
		}

		$initcount = count( $terms->terms );

		for ( $i = $offset; $i < $initcount; $i++ ) {
			// Check if this is only applied on Trial
			if ( !$this->discount['useon_full'] && ( $i > 0 ) ) {
				continue;
			}

			// Check whether it's only on ONE full period and whether we already have a nondiscounted copy set up
			if ( !$this->discount['useon_full_all'] && ( $i < $initcount ) && ( count($terms->terms[$i]->cost) < 3 ) ) {
				// Duplicate current term
				$newterm = unserialize( serialize( $terms->terms[$i] ) );

				$terms->addTerm( $newterm );
			}

			if ( $this->discount['percent_first'] ) {
				if ( $this->discount['amount_percent_use'] ) {
					$info['details'] = '-' . $this->discount['amount_percent'] . '%';
					$terms->terms[$i]->discount( null, $this->discount['amount_percent'], $info );
				}
				if ( $this->discount['amount_use'] ) {
					$info['details'] = null;
					$terms->terms[$i]->discount( $this->discount['amount'], null, $info );
				}
			} else {
				if ( $this->discount['amount_use'] ) {
					$info['details'] = null;
					$terms->terms[$i]->discount( $this->discount['amount'], null, $info );
				}
				if ( $this->discount['amount_percent_use'] ) {
					$info['details'] = '-' . $this->discount['amount_percent'] . '%';
					$terms->terms[$i]->discount( null, $this->discount['amount_percent'], $info );
				}
			}

			if ( $this->discount['useon_full'] && !$this->discount['useon_full_all'] ) {
				break;
			}
		}

		$terms->checkFree();

		return $terms;
	}

	function triggerMIs( $metaUser, $invoice, $new_plan )
	{
		global $aecConfig;

		$db = &JFactory::getDBO();

		// See whether this coupon has micro integrations
		if ( empty( $this->coupon->micro_integrations ) ) {
			return null;
		}

		foreach ( $this->coupon->micro_integrations as $mi_id ) {
			$mi = new microIntegration( $db );

			// Only call if it exists
			if ( !$mi->mi_exists( $mi_id ) ) {
				continue;
			}

			$mi->load( $mi_id );

			// Check whether we can really call
			if ( !$mi->callIntegration() ) {
				continue;
			}

			if ( is_object( $metaUser ) ) {
				if ( $mi->action( $metaUser, null, $invoice, $new_plan ) === false ) {
					if ( $aecConfig->cfg['breakon_mi_error'] ) {
						return false;
					}
				}
			} else {
				if ( $mi->action( false, null, $invoice, $new_plan ) === false ) {
					if ( $aecConfig->cfg['breakon_mi_error'] ) {
						return false;
					}
				}
			}
		}
	}
}

class Coupon extends serialParamDBTable
{
	/** @var int Primary key */
	var $id					= null;
	/** @var int */
	var $active				= null;
	/** @var string */
	var $coupon_code		= null;
	/** @var datetime */
	var $created_date 		= null;
	/** @var string */
	var $name				= null;
	/** @var string */
	var $desc				= null;
	/** @var text */
	var $discount			= null;
	/** @var text */
	var $restrictions		= null;
	/** @var text */
	var $params				= null;
	/** @var int */
	var $usecount			= null;
	/** @var text */
	var $micro_integrations	= null;

	function Coupon( &$db, $type=0 )
	{
		if ( $type ) {
			parent::__construct( '#__acctexp_coupons_static', 'id', $db );
		} else {
			parent::__construct( '#__acctexp_coupons', 'id', $db );
		}
	}

	function load( $id )
	{
		parent::load( $id );

		$this->getType();
	}

	function getType()
	{
		$this->type = 0;

		if ( strpos( $this->getTableName(), 'acctexp_coupons_static' ) ) {
			$this->type = 1;
		}

		return $this->type;
	}

	function declareParamFields()
	{
		return array( 'discount', 'restrictions', 'params', 'micro_integrations'  );
	}

	function deactivate()
	{
		$this->active = 0;
		$this->storeload();
	}

	function createNew( $code=null, $created=null )
	{
		$this->id		= 0;
		$this->active	= 1;

		// Override creation of new Coupon Code if one is supplied
		if ( is_null( $code ) ) {
			$this->coupon_code = $this->generateCouponCode();
		} else {
			$this->coupon_code = $code;
		}

		// Set created date if supplied
		if ( is_null( $created ) ) {
			$app = JFactory::getApplication();

			$this->created_date = date( 'Y-m-d H:i:s', ( (int) gmdate('U') ) );
		} else {
			$this->created_date = $created;
		}

		$this->usecount = 0;

		$this->storeload();

		$this->getType();

		$this->id = $this->getMax();
	}

	function savePOSTsettings( $post )
	{
		$db = &JFactory::getDBO();

		if ( !empty( $post['coupon_code'] ) ) {
			$query = 'SELECT `id`'
					. ' FROM #__acctexp_coupons_static'
					. ' WHERE `coupon_code` = \'' . $post['coupon_code'] . '\''
					;
			$db->setQuery( $query );
			$couponid = $db->loadResult();

			if ( empty( $couponid ) ) {
				$query = 'SELECT `id`'
						. ' FROM #__acctexp_coupons'
						. ' WHERE `coupon_code` = \'' . $post['coupon_code'] . '\''
						;
				$db->setQuery( $query );
				$couponid = $db->loadResult();
			}

			if ( !empty( $couponid ) && ( $couponid !== $this->id ) ) {
				$post['coupon_code'] = $this->generateCouponCode();
			}
		}

		// Filter out fixed variables
		$fixed = array( 'active', 'name', 'desc', 'coupon_code', 'usecount', 'micro_integrations' );

		foreach ( $fixed as $varname ) {
			if ( isset( $post[$varname] ) ) {
				$this->$varname = $post[$varname];
				unset( $post[$varname] );
			} else {
				$this->$varname = null;
			}
		}

		// Filter out params
		$fixed = array( 'amount_use', 'amount', 'amount_percent_use', 'amount_percent', 'percent_first', 'useon_trial', 'useon_full', 'useon_full_all' );

		$params = array();
		foreach ( $fixed as $varname ) {
			if ( !isset( $post[$varname] ) ) {
				continue;
			}

			$params[$varname] = $post[$varname];
			unset( $post[$varname] );
		}

		$this->saveDiscount( $params );

		// the rest is restrictions
		$this->saveRestrictions( $post );
	}

	function saveDiscount( $params )
	{
		// Correct a malformed Amount
		if ( !strlen( $params['amount'] ) ) {
			$params['amount_use'] = 0;
		} else {
			$params['amount'] = AECToolbox::correctAmount( $params['amount'] );
		}

		$this->discount = $params;
	}

	function saveRestrictions( $restrictions )
	{
		$this->restrictions = $restrictions;
	}

	function incrementCount()
	{
		$this->usecount += 1;
		$this->storeload();
	}

	function decrementCount()
	{
		$this->usecount -= 1;
		$this->storeload();
	}

	function generateCouponCode( $maxlength = 6 )
	{
		$db = &JFactory::getDBO();

		$numberofrows = 1;

		while ( $numberofrows ) {
			$inum =	strtoupper( substr( base64_encode( md5( rand() ) ), 0, $maxlength ) );
			// check single coupons
			$query = 'SELECT count(*)'
					. ' FROM #__acctexp_coupons'
					. ' WHERE `coupon_code` = \'' . $inum . '\''
					;
			$db->setQuery( $query );
			$numberofrows_normal = $db->loadResult();

			// check static coupons
			$query = 'SELECT count(*)'
					. ' FROM #__acctexp_coupons_static'
					. ' WHERE `coupon_code` = \'' . $inum . '\''
					;
			$db->setQuery( $query );
			$numberofrows_static = $db->loadResult();

			$numberofrows = $numberofrows_normal + $numberofrows_static;
		}

		return $inum;
	}

	function copy()
	{
		$this->id = 0;
		$this->coupon_code = $this->generateCouponCode();
		$this->check();
		$this->store();
	}

	function check()
	{
		if ( isset( $this->type ) ) {
			unset( $this->type );
		}

		parent::check();
	}
}

class couponXuser extends serialParamDBTable
{
	/** @var int Primary key */
	var $id					= null;
	/** @var int */
	var $coupon_id			= null;
	/** @var int */
	var $coupon_type		= null;
	/** @var string */
	var $coupon_code		= null;
	/** @var int */
	var $userid				= null;
	/** @var datetime */
	var $created_date 		= null;
	/** @var datetime */
	var $last_updated		= null;
	/** @var text */
	var $params				= null;
	/** @var int */
	var $usecount			= null;

	function couponXuser( &$db )
	{
		parent::__construct( '#__acctexp_couponsxuser', 'id', $db );
	}

	function declareParamFields()
	{
		return array( 'params'  );
	}

	function createNew( $userid, $coupon, $type, $params=null )
	{
		$app = JFactory::getApplication();

		$this->id = 0;
		$this->coupon_id = $coupon->id;
		$this->coupon_type = $type;
		$this->coupon_code = $coupon->coupon_code;
		$this->userid = $userid;
		$this->created_date = date( 'Y-m-d H:i:s', ( (int) gmdate('U') ) );
		$this->last_updated = date( 'Y-m-d H:i:s', ( (int) gmdate('U') ) );

		if ( is_array( $params ) ) {
			$this->params = $params;
		}

		$this->usecount = 1;

		$this->storeload();
	}

	function getInvoiceList()
	{
		$invoicelist = array();
		if ( isset( $this->params['invoices'] ) ) {
			$invoices = explode( ';', $this->params['invoices'] );

			foreach ( $invoices as $invoice ) {
				$inv = explode( ',', $invoice );

				if ( isset( $invoice[1] ) ) {
					$invoicelist[$invoice[0]] = $invoice[1];
				} else {
					$invoicelist[$invoice[0]] = 1;
				}
			}
		}

		return $invoicelist;
	}

	function setInvoiceList( $invoicelist )
	{
		$invoices = array();

		foreach ( $invoicelist as $invoicenumber => $counter ) {
			$invoices[] = $invoicenumber . ',' . $counter;
		}

		$params['invoices'] = implode( ';', $invoices );

		$this->addParams( $params );
	}

	function addInvoice( $invoicenumber )
	{
		$invoicelist = $this->getInvoiceList();

		if ( isset( $invoicelist[$invoicenumber] ) ) {
			$invoicelist[$invoicenumber] += 1;
		} else {
			$invoicelist[$invoicenumber] = 1;
		}

		$this->setInvoiceList( $invoicelist );
	}

	function delInvoice( $invoicenumber )
	{
		$invoicelist = $this->getInvoiceList();

		if ( isset( $invoicelist[$invoicenumber] ) ) {
			$invoicelist[$invoicenumber] -= 1;

			if ( $invoicelist[$invoicenumber] === 0 ) {
				unset( $invoicelist[$invoicenumber] );
			}
		}

		$this->setInvoiceList( $invoicelist );
	}
}

class aecRestrictionHelper
{
	function checkRestriction( $restrictions, $metaUser )
	{
		if ( count( $restrictions ) ) {
			$status = array();

			if ( isset( $restrictions['custom_restrictions'] ) ) {
				$status = array_merge( $status, $metaUser->CustomRestrictionResponse( $restrictions['custom_restrictions'] ) );
				unset( $restrictions['custom_restrictions'] );
			}

			$status = array_merge( $status, $metaUser->permissionResponse( $restrictions ) );

			if ( count( $status ) ) {
				foreach ( $status as $stname => $ststatus ) {
					if ( !$ststatus ) {
						return false;
					}
				}
			}
		}

		return true;
	}

	function getRestrictionsArray( $restrictions )
	{
		$newrest = array();

		// Check for a fixed GID - this certainly overrides the others
		if ( !empty( $restrictions['fixgid_enabled'] ) ) {
			$newrest['fixgid'] = (int) $restrictions['fixgid'];
		} else {
			// No fixed GID, check for min GID
			if ( !empty( $restrictions['mingid_enabled'] ) ) {
				$newrest['mingid'] = (int) $restrictions['mingid'];
			}
			// Check for max GID
			if ( !empty( $restrictions['maxgid_enabled'] ) ) {
				$newrest['maxgid'] = (int) $restrictions['maxgid'];
			}
		}

		// First we sort out the group restrictions and convert them into plan restrictions

		// Check for a directly previously used group
		if ( !empty( $restrictions['previousgroup_req_enabled'] ) ) {
			if ( !empty( $restrictions['previousgroup_req'] ) ) {
				$restrictions = aecRestrictionHelper::addGroupPlans( $restrictions, 'previousgroup_req', 'previousplan_req' );

				$restrictions['previousplan_req_enabled'] = true;
			}
		}

		// Check for a directly previously used group
		if ( !empty( $restrictions['previousgroup_req_enabled_excluded'] ) ) {
			if ( !empty( $restrictions['previousgroup_req_excluded'] ) ) {
				$restrictions = aecRestrictionHelper::addGroupPlans( $restrictions, 'previousgroup_req_excluded', 'previousplan_req_excluded' );

				$restrictions['previousplan_req_enabled_excluded'] = true;
			}
		}

		// Check for a currently used group
		if ( !empty( $restrictions['currentgroup_req_enabled'] ) ) {
			if ( !empty( $restrictions['currentgroup_req'] ) ) {
				$restrictions = aecRestrictionHelper::addGroupPlans( $restrictions, 'currentgroup_req', 'currentplan_req' );

				$restrictions['currentplan_req_enabled'] = true;
			}
		}

		// Check for a currently used group
		if ( !empty( $restrictions['currentgroup_req_enabled_excluded'] ) ) {
			if ( !empty( $restrictions['currentgroup_req_excluded'] ) ) {
				$restrictions = aecRestrictionHelper::addGroupPlans( $restrictions, 'currentgroup_req_excluded', 'currentplan_req_excluded' );

				$restrictions['currentplan_req_enabled_excluded'] = true;
			}
		}

		// Check for a overall used group
		if ( !empty( $restrictions['overallgroup_req_enabled'] ) ) {
			if ( !empty( $restrictions['overallgroup_req'] ) ) {
				$restrictions = aecRestrictionHelper::addGroupPlans( $restrictions, 'overallgroup_req', 'overallplan_req' );

				$restrictions['overallplan_req_enabled'] = true;
			}
		}

		// Check for a overall used group
		if ( !empty( $restrictions['overallgroup_req_enabled_excluded'] ) ) {
			if ( !empty( $restrictions['overallgroup_req_excluded'] ) ) {
				$restrictions = aecRestrictionHelper::addGroupPlans( $restrictions, 'overallgroup_req_excluded', 'overallplan_req_excluded' );

				$restrictions['overallplan_req_enabled_excluded'] = true;
			}
		}

		// And now we prepare the individual plan restrictions

		// Check for a directly previously used plan
		if ( !empty( $restrictions['previousplan_req_enabled'] ) ) {
			if ( !empty( $restrictions['previousplan_req'] ) ) {
				$newrest['plan_previous'] = $restrictions['previousplan_req'];
			}
		}

		// Check for a directly previously used plan
		if ( !empty( $restrictions['previousplan_req_enabled_excluded'] ) ) {
			if ( !empty( $restrictions['previousplan_req_excluded'] ) ) {
				$newrest['plan_previous_excluded'] = $restrictions['previousplan_req_excluded'];
			}
		}

		// Check for a currently used plan
		if ( !empty( $restrictions['currentplan_req_enabled'] ) ) {
			if ( !empty( $restrictions['currentplan_req'] ) ) {
				$newrest['plan_present'] = $restrictions['currentplan_req'];
			}
		}

		// Check for a currently used plan
		if ( !empty( $restrictions['currentplan_req_enabled_excluded'] ) ) {
			if ( !empty( $restrictions['currentplan_req_excluded'] ) ) {
				$newrest['plan_present_excluded'] = $restrictions['currentplan_req_excluded'];
			}
		}

		// Check for a overall used plan
		if ( !empty( $restrictions['overallplan_req_enabled'] ) ) {
			if ( !empty( $restrictions['overallplan_req'] ) ) {
				$newrest['plan_overall'] = $restrictions['overallplan_req'];
			}
		}

		// Check for a overall used plan
		if ( !empty( $restrictions['overallplan_req_enabled_excluded'] ) ) {
			if ( !empty( $restrictions['overallplan_req_excluded'] ) ) {
				$newrest['plan_overall_excluded'] = $restrictions['overallplan_req_excluded'];
			}
		}

		if ( !empty( $restrictions['used_plan_min_enabled'] ) ) {
			if ( !empty( $restrictions['used_plan_min_amount'] ) && isset( $restrictions['used_plan_min'] ) ) {
				if ( !isset( $newrest['plan_amount_min'] ) ) {
					$newrest['plan_amount_min'] = array();
				}

				if ( is_array( $restrictions['used_plan_min'] ) ) {
					foreach ( $restrictions['used_plan_min'] as $planid ) {
						if ( $planid ) {
							$newrest['plan_amount_min'][] = ( (int) $planid ) . ',' . ( (int) $restrictions['used_plan_min_amount'] );
						}
					}
				} else {
					$newrest['plan_amount_min'][] = array( ( (int) $restrictions['used_plan_min'] ) . ',' . ( (int) $restrictions['used_plan_min_amount'] ) );
				}
			}
		}

		// Check for a overall used group with amount minimum
		if ( !empty( $restrictions['used_group_min_enabled'] ) ) {
			if ( !empty( $restrictions['used_group_min_amount'] ) && isset( $restrictions['used_group_min'] ) ) {
				$temp = aecRestrictionHelper::addGroupPlans( $restrictions, 'used_group_min', 'used_plan_min', array() );

				$ps = array();
				foreach ( $temp['used_plan_min'] as $planid ) {
					if ( $planid ) {
						$newrest['plan_amount_min'][] = ( (int) $planid ) . ',' . ( (int) $restrictions['used_group_min_amount'] );
					}
				}
			}
		}

		// Check for a overall used plan with amount maximum
		if ( !empty( $restrictions['used_plan_max_enabled'] ) ) {
			if ( !empty( $restrictions['used_plan_max_amount'] ) && isset( $restrictions['used_plan_max'] ) ) {
				if ( !isset( $newrest['plan_amount_max'] ) ) {
					$newrest['plan_amount_max'] = array();
				}

				if ( is_array( $restrictions['used_plan_max'] ) ) {
					foreach ( $restrictions['used_plan_max'] as $planid ) {
						if ( $planid ) {
							$newrest['plan_amount_max'][] = ( (int) $planid ) . ',' . ( (int) $restrictions['used_plan_max_amount'] );
						}
					}
				} else {
					$newrest['plan_amount_max'][] = ( (int) $restrictions['used_plan_max'] ) . ',' . ( (int) $restrictions['used_plan_max_amount'] );
				}
			}
		}

		// Check for a overall used group with amount maximum
		if ( !empty( $restrictions['used_group_max_enabled'] ) ) {
			if ( !empty( $restrictions['used_group_max_amount'] ) && isset( $restrictions['used_group_max'] ) ) {
				$temp = aecRestrictionHelper::addGroupPlans( $restrictions, 'used_group_max', 'used_plan_max', array() );

				$ps = array();
				foreach ( $temp['used_plan_max'] as $planid ) {
					if ( $planid ) {
						$newrest['plan_amount_max'][] = ( (int) $planid ) . ',' . ( (int) $restrictions['used_group_max_amount'] );
					}
				}
			}
		}

		// Check for custom restrictions
		if ( !empty( $restrictions['custom_restrictions_enabled'] ) ) {
			if ( !empty( $restrictions['custom_restrictions'] ) ) {
				$newrest['custom_restrictions'] = aecRestrictionHelper::transformCustomRestrictions( $restrictions['custom_restrictions'] );
			}
		}

		return $newrest;
	}

	function addGroupPlans( $source, $gkey, $pkey, $target=null )
	{
		$okey = str_replace( '_req', '_req_enabled', $pkey );

		if ( !is_array( $source[$pkey] ) || empty($source[$okey]) ) {
			$plans = array();
		} else {
			$plans = $source[$pkey];
		}

		$newplans = ItemGroupHandler::getGroupsPlans( $source[$gkey] );

		$plans = array_merge( $plans, $newplans );

		$plans = array_unique( $plans );

		if ( is_null( $target ) ) {
			$restrictions[$pkey] = $plans;

			return $restrictions;
		} else {
			$target[$pkey] = $plans;

			return $target;
		}
	}

	function transformCustomRestrictions( $customrestrictions )
	{
		$cr = explode( "\n", $customrestrictions);

		$custom = array();
		foreach ( $cr as $field ) {
			// WAT?! yes.
			if ( strpos( nl2br( substr( $field, -1, 1 ) ), "<br" ) !== false ) {
				$field = substr( $field, 0, -1 );
			}

			$custom[] = explode( ' ', $field, 3 );
		}

		return $custom;
	}

	function paramList()
	{
		return array( 'mingid_enabled', 'mingid', 'fixgid_enabled', 'fixgid',
						'maxgid_enabled', 'maxgid', 'previousplan_req_enabled', 'previousplan_req',
						'currentplan_req_enabled', 'currentplan_req', 'overallplan_req_enabled', 'overallplan_req',
						'previousplan_req_enabled_excluded', 'previousplan_req_excluded', 'currentplan_req_enabled_excluded', 'currentplan_req_excluded',
						'overallplan_req_enabled_excluded', 'overallplan_req_excluded', 'used_plan_min_enabled', 'used_plan_min_amount',
						'used_plan_min', 'used_plan_max_enabled', 'used_plan_max_amount', 'used_plan_max',
						'custom_restrictions_enabled', 'custom_restrictions', 'previousgroup_req_enabled', 'previousgroup_req',
						'previousgroup_req_enabled_excluded', 'previousgroup_req_excluded', 'currentgroup_req_enabled', 'currentgroup_req',
						'currentgroup_req_enabled_excluded', 'currentgroup_req_excluded', 'overallgroup_req_enabled', 'overallgroup_req',
						'overallgroup_req_enabled_excluded', 'overallgroup_req_excluded', 'used_group_min_enabled', 'used_group_min_amount',
						'used_group_min', 'used_group_max_enabled', 'used_group_max_amount', 'used_group_max' );
	}

	function getParams()
	{
		$params = array();
		$params['mingid_enabled']					= array( 'toggle', 0 );
		$params['mingid']							= array( 'list', 18 );
		$params['fixgid_enabled']					= array( 'toggle', 0 );
		$params['fixgid']							= array( 'list', 19 );
		$params['maxgid_enabled']					= array( 'toggle', 0 );
		$params['maxgid']							= array( 'list', 21 );
		$params['previousplan_req_enabled'] 		= array( 'toggle', 0 );
		$params['previousplan_req']					= array( 'list', 0 );
		$params['previousplan_req_enabled_excluded']	= array( 'toggle', 0 );
		$params['previousplan_req_excluded']			= array( 'list', 0 );
		$params['currentplan_req_enabled']			= array( 'toggle', 0 );
		$params['currentplan_req']					= array( 'list', 0 );
		$params['currentplan_req_enabled_excluded']	= array( 'toggle', 0 );
		$params['currentplan_req_excluded']			= array( 'list', 0 );
		$params['overallplan_req_enabled']			= array( 'toggle', 0 );
		$params['overallplan_req']					= array( 'list', 0 );
		$params['overallplan_req_enabled_excluded']	= array( 'toggle', 0 );
		$params['overallplan_req_excluded']			= array( 'list', 0 );
		$params['used_plan_min_enabled']			= array( 'toggle', 0 );
		$params['used_plan_min_amount']				= array( 'inputB', 0 );
		$params['used_plan_min']					= array( 'list', 0 );
		$params['used_plan_max_enabled']			= array( 'toggle', 0 );
		$params['used_plan_max_amount']				= array( 'inputB', 0 );
		$params['used_plan_max']					= array( 'list', 0 );
		$params['previousgroup_req_enabled'] 		= array( 'toggle', 0 );
		$params['previousgroup_req']				= array( 'list', 0 );
		$params['previousgroup_req_enabled_excluded']	= array( 'toggle', 0 );
		$params['previousgroup_req_excluded']		= array( 'list', 0 );
		$params['currentgroup_req_enabled']			= array( 'toggle', 0 );
		$params['currentgroup_req']					= array( 'list', 0 );
		$params['currentgroup_req_enabled_excluded']	= array( 'toggle', 0 );
		$params['currentgroup_req_excluded']		= array( 'list', 0 );
		$params['overallgroup_req_enabled']			= array( 'toggle', 0 );
		$params['overallgroup_req']					= array( 'list', 0 );
		$params['overallgroup_req_enabled_excluded']	= array( 'toggle', 0 );
		$params['overallgroup_req_excluded']		= array( 'list', 0 );
		$params['used_group_min_enabled']			= array( 'toggle', 0 );
		$params['used_group_min_amount']			= array( 'inputB', 0 );
		$params['used_group_min']					= array( 'list', 0 );
		$params['used_group_max_enabled']			= array( 'toggle', 0 );
		$params['used_group_max_amount']			= array( 'inputB', 0 );
		$params['used_group_max']					= array( 'list', 0 );
		$params['custom_restrictions_enabled']		= array( 'toggle', '' );
		$params['custom_restrictions']				= array( 'inputD', '' );

		return $params;
	}

	function getLists( $params_values, $restrictions_values )
	{
		$db = &JFactory::getDBO();

		$user = &JFactory::getUser();

		$gtree = aecACLhandler::getGroupTree( array( 28, 29, 30 ) );

		// Create GID related Lists
		$lists['gid'] 		= JHTML::_( 'select.genericlist', $gtree, 'gid', 'size="6"', 'value', 'text', arrayValueDefault($params_values, 'gid', 18) );
		$lists['mingid'] 	= JHTML::_( 'select.genericlist', $gtree, 'mingid', 'size="6"', 'value', 'text', arrayValueDefault($restrictions_values, 'mingid', 18) );
		$lists['fixgid'] 	= JHTML::_( 'select.genericlist', $gtree, 'fixgid', 'size="6"', 'value', 'text', arrayValueDefault($restrictions_values, 'fixgid', 19) );
		$lists['maxgid'] 	= JHTML::_( 'select.genericlist', $gtree, 'maxgid', 'size="6"', 'value', 'text', arrayValueDefault($restrictions_values, 'maxgid', 21) );

		$available_plans = array();

		// Fetch Payment Plans
		$query = 'SELECT `id` AS value, `name` AS text'
				. ' FROM #__acctexp_plans'
				;
		$db->setQuery( $query );
		$plans = $db->loadObjectList();

	 	if ( empty( $plans ) ) {
	 		$plans = array();
	 	} else {
	 		$all_plans	= $available_plans;
	 	}

		$planrest = array( 'previousplan_req', 'currentplan_req', 'overallplan_req', 'used_plan_min', 'used_plan_max', 'previousplan_req_excluded', 'currentplan_req_excluded', 'overallplan_req_excluded'  );

		foreach ( $planrest as $name ) {
			$lists[$name] = JHTML::_( 'select.genericlist', $plans, $name.'[]', 'size="1" multiple="multiple"', 'value', 'text', arrayValueDefault($restrictions_values, $name, 0) );
		}

		$available_groups = array();

		// Fetch Item Groups
		$query = 'SELECT `id` AS value, `name` AS text'
				. ' FROM #__acctexp_itemgroups'
				;
		$db->setQuery( $query );
		$groups = $db->loadObjectList();

	 	if ( empty( $groups ) ) {
	 		$groups = array();
	 	}

		$grouprest = array( 'previousgroup_req', 'currentgroup_req', 'overallgroup_req', 'used_group_min', 'used_group_max', 'previousgroup_req_excluded', 'currentgroup_req_excluded', 'overallgroup_req_excluded' );

		foreach ( $grouprest as $name ) {
			$lists[$name] = JHTML::_( 'select.genericlist', $groups, $name.'[]', 'size="1" multiple="multiple"', 'value', 'text', arrayValueDefault($restrictions_values, $name, 0) );
		}

		return $lists;
	}

	function echoSettings( $aecHTML )
	{
		$stdvars =	array(	array(
									array( 'mingid_enabled', 'mingid' ),
									array( 'fixgid_enabled', 'fixgid' ),
									array( 'maxgid_enabled', 'maxgid' )
							),
							array(
									array( 'custom_restrictions_enabled', 'custom_restrictions' )
							),	array(
									array( 'previous*_req_enabled', 'previous*_req' ),
									array( 'previous*_req_enabled_excluded', 'previous*_req_excluded' ),
									array( 'current*_req_enabled', 'current*_req' ),
									array( 'current*_req_enabled_excluded', 'current*_req_excluded' ),
									array( 'overall*_req_enabled', 'overall*_req' ),
									array( 'overall*_req_enabled_excluded', 'overall*_req_excluded' )
							), array(
									array( 'used_*_min_enabled', 'used_*_min_amount', 'used_*_min' ),
									array( 'used_*_max_enabled', 'used_*_max_amount', 'used_*_max' )
							)
					);

		$types = array( 'plan', 'group' );

		foreach ( $types as $type ) {
			foreach ( $stdvars as $block ) {
				// non-* blocks only once
				if ( ( strpos( $block[0][0], '*' ) === false ) && ( $type != 'plan') ) {
					continue;
				}

				echo '<div class="aec_userinfobox_sub">';

				$firstitem = str_replace( '*', $type, $block[0][0] );
				echo '<h4>' . JText::_( strtoupper( 'aec_restrictions_' . substr( $firstitem, 0, strpos( $firstitem, '_', strpos( $firstitem, '_' )+3 ) ) . '_header' ) )  . '</h4>';

				foreach ( $block as $sblock ) {

					if ( count( $block ) < 2 ) {
						echo '<div class="aec_userinfobox_sub_inline">';
					} else {
						echo '<div class="aec_userinfobox_sub_inline form-stacked" style="width:214px;">';
					}

					foreach ( $sblock as $vname ) {
						echo $aecHTML->createSettingsParticle( str_replace( '*', $type, $vname ) );
					}
					echo '</div>';
				}
				echo '</div>';
			}
		}
	}
}

class aecAPI
{
	var $request	= '';
	var $metaUser	= '';
	var $focus		= '';
	var $error		= '';
	var $response	= '';

	function load( $request )
	{
		$this->request = $request;

		if ( !empty( $this->request->action ) ) {
			$this->action = $this->request->action;
		} else {
			$this->error = 'action missing or empty';
		}

		if ( !empty( $this->request->user ) ) {
			$this->loadUser();
		} else {
			$this->error = 'user missing or empty';
		}

		$this->response = new stdClass();
	}

	function loadUser()
	{
		$users = array();

		if ( is_object( $this->request->user ) ) {
			$db = &JFactory::getDBO();

			if ( isset( $this->request->user->username ) ) {
				$query = 'SELECT `id`'
						. ' FROM #__users'
						. ' WHERE LOWER( `username` ) LIKE \'%' . $db->getEscaped( strtolower( $this->request->user->username ) ) . '%\''
						;
				$db->setQuery( $query );

				$users = $db->loadResultArray();
			}

			if ( empty( $users ) && isset( $this->request->user->name ) ) {
				$query = 'SELECT `id`'
						. ' FROM #__users'
						. ' WHERE LOWER( `name` ) LIKE \'%' . $db->getEscaped( strtolower( $this->request->user->name ) ) . '%\''
						;
				$db->setQuery( $query );

				$users = $db->loadResultArray();
			}

			if ( empty( $users ) && isset( $this->request->user->email ) ) {
				$query = 'SELECT `id`'
						. ' FROM #__users'
						. ' WHERE LOWER( `email` ) = \'' . $db->getEscaped( $this->request->user->email ) . '\''
						;
				$db->setQuery( $query );

				$users = $db->loadResultArray();
			}

			if ( empty( $users ) && isset( $this->request->user->userid ) ) {
				$query = 'SELECT `id`'
						. '  FROM #__users'
						. ' WHERE `id` = \'' . $db->getEscaped( $this->request->user->userid ) . '\''
						;
				$db->setQuery( $query );

				$users = $db->loadResultArray();
			}

			if ( empty( $users ) && isset( $this->request->user->invoice_number ) ) {
				$query = 'SELECT `userid`'
						. 'FROM #__acctexp_invoices'
						. ' WHERE LOWER( `invoice_number` ) = \'' . $db->getEscaped( $this->request->user->invoice_number ) . '\''
						. ' OR LOWER( `secondary_ident` ) = \'' . $db->getEscaped( $this->request->user->invoice_number ) . '\''
						;
				$db->setQuery( $query );

				$users = $db->loadResultArray();
			}
		} else {
			$users = AECToolbox::searchUser( $this->request->user );
		}

		if ( !count( $users ) ) {
			$this->error = 'user not found';
		} elseif ( count( $users ) > 1 ) {
			$this->error = 'multiple users found';
		} else {
			if ( !empty( $this->metaUser->userid ) ) {
				if ( $this->metaUser->userid != $users[0] ) {
					$this->metaUser = new metaUser( $users[0] );
				}
			} else {
				$this->metaUser = new metaUser( $users[0] );
			}
		}
	}

	function resolve()
	{
		$cmd = 'action' . $this->action;

		if ( method_exists( $this, $cmd ) ) {
			$this->$cmd();
		} else {
			$this->error = 'chosen action ' . $cmd . ' does not exist - check spelling (especially upper- and lowercase)';
		}
	}

	function actionUserExists()
	{
		$this->response->result = !empty( $this->metaUser->userid );
	}

	function actionMembershipDetails()
	{
		$this->actionUserExists();

		$this->response->status = AECToolbox::VerifyMetaUser( $this->metaUser );

		if ( $this->response->status === true ) {
			$this->response->status = $this->metaUser->objSubscription->status;
		}

		switch ( strtolower( $this->response->status ) ) {
			case 'active':			$this->response->status_long = 'Account is fine.'; break;
			case 'trial':			$this->response->status_long = 'Account is fine (using a trial right now).'; break;
			case 'expired':			$this->response->status_long = 'Account has expired.'; break;
			case 'pending':			$this->response->status_long = 'Account is pending - awaiting payment for the last invoice to clear.'; break;
			case 'open_invoice':	$this->response->status_long = 'Account is pending - there is an open invoice waiting to be paid.'; break;
			case 'hold':			$this->response->status_long = 'Account is on manual hold.'; break;
			default:				$this->response->status_long = 'No long status explanation for this.'; break;
		}

		if ( !empty( $this->request->details ) ) {
			if ( !is_object( $this->request->details ) ) {
				$this->error = 'details need to be an objects (with "key" and "value" as properties)';
			} else {
				$details = get_object_vars( $this->request->details );

				foreach ( $details as $k => $v ) {
					if ( empty( $k ) || empty( $v ) ) {
						$this->error = 'one or more details empty or malformed';
					} else {
						$this->response->$k = AECToolbox::rewriteEngineRQ( '{aecjson}'.json_encode($v).'{/aecjson}', null, $this->metaUser );
					}
				}
			}
		}
	}

	function actionAuth()
	{
		if ( empty( $this->request->user->username ) || empty( $this->request->user->password ) ) {
			$this->error = 'must provide username and password to authenticate';

			$this->response->result =  false;

			return;
		}

		$credentials = array();
		$credentials['username'] = $this->request->user->username;
		$credentials['password'] = $this->request->user->password;

		// Get the global JAuthentication object.
		jimport('joomla.user.authentication');

		$authenticate = JAuthentication::getInstance();
		$response	= $authenticate->authenticate($credentials, array());

		$this->response->result = ( $response->status === JAUTHENTICATE_STATUS_SUCCESS );
	}

	function actionRestrictionCheck()
	{
		$db = &JFactory::getDBO();

		$this->response->result = false;

		if ( !empty( $this->request->details->plan ) ) {
			$plan = new SubscriptionPlan( $db );
			$plan->load( $this->request->details->plan );

			if ( $plan->id != $this->request->details->plan ) {
				$this->error = 'could not find plan to check restrictions for';

				return;
			}

			$restrictions = $plan->getRestrictionsArray();

			if ( aecRestrictionHelper::checkRestriction( $restrictions, $this->metaUser ) !== false ) {
				if ( !ItemGroupHandler::checkParentRestrictions( $plan, 'item', $this->metaUser ) ) {
					$this->error = 'user is denied permission - plans parent group is restricted from this user';
				}
			} else {
				$this->error = 'user is denied permission - plan is restricted from this user';
			}
			
			unset( $this->request->details->plan );
		}

		if ( !empty( $this->request->details->group ) ) {
			$group = new ItemGroup( $db );
			$group->load( $this->request->details->group );

			if ( $group->id != $this->request->details->group ) {
				$this->error = 'could not find group to check restrictions for';

				return;
			}

			$restrictions = $group->getRestrictionsArray();

			if ( aecRestrictionHelper::checkRestriction( $restrictions, $this->metaUser ) !== false ) {
				if ( !ItemGroupHandler::checkParentRestrictions( $group, 'group', $this->metaUser ) ) {
					$this->error = 'user is denied permission - groups parent group is restricted from this user';
				}
			} else {
				$this->error = 'user is denied permission - group is restricted from this user';
			}
			
			unset( $this->request->details->group );
		}


		if ( !empty( $this->request->details ) ) {
			$re = get_object_vars( $this->request->details );

			$restrictions = aecRestrictionHelper::getRestrictionsArray( $re );

			if ( aecRestrictionHelper::checkRestriction( $restrictions, $this->metaUser ) === false ) {
				$this->error = 'user is denied permission - at least one restriction result was negative';
			}
		}

		if ( empty( $this->error ) ) {
			$this->response->result = true;
		}
	}
}

?>
