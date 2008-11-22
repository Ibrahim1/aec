<?php
/**
 * @version $Id: acctexp.class.php
 * @package AEC - Account Control Expiration - Membership Manager
 * @subpackage Core Class
 * @copyright 2006-2008 Copyright (C) David Deutsch
 * @author David Deutsch <skore@skore.de> & Team AEC - http://www.globalnerd.org
 * @license GNU/GPL v.2 http://www.gnu.org/licenses/old-licenses/gpl-2.0.html or, at your option, any later version
 */

// Dont allow direct linking
( defined('_JEXEC') || defined( '_VALID_MOS' ) ) or die( 'Direct Access to this location is not allowed.' );

//error_reporting(E_ALL);

global $mosConfig_absolute_path, $mosConfig_offset, $aecConfig;

if ( !defined ( 'AEC_FRONTEND' ) && !defined( '_AEC_LANG' ) ) {
	$langPath = $mosConfig_absolute_path . '/administrator/components/com_acctexp/com_acctexp_language_backend/';
	if ( file_exists( $langPath . $GLOBALS['mosConfig_lang'] . '.php' ) ) {
		include_once( $langPath . $GLOBALS['mosConfig_lang'] . '.php' );
	} else {
		include_once( $langPath. 'english.php' );
	}
	include_once( $langPath . 'general.php' );
}

if ( !defined( '_AEC_LANG' ) ) {
	$langPath = $mosConfig_absolute_path . '/components/com_acctexp/com_acctexp_language/';
	if ( file_exists( $langPath . $GLOBALS['mosConfig_lang'] . '.php' ) ) {
		include_once( $langPath . $GLOBALS['mosConfig_lang'] . '.php' );
	} else {
		include_once( $langPath . 'english.php' );
	}
	define( '_AEC_LANG', 1 );
}

// Make sure we are compatible with php4
include_once( $mosConfig_absolute_path . '/components/com_acctexp/lib/php4/php4.php' );

// Make sure we are compatible with joomla1.0
include_once( $mosConfig_absolute_path . '/components/com_acctexp/lib/j15/j15.php' );

if ( !class_exists( 'paramDBTable' ) ) {
	include_once( $mosConfig_absolute_path . '/components/com_acctexp/lib/eucalib/eucalib.php' );
}

include_once( $mosConfig_absolute_path . '/components/com_acctexp/lib/mammontini/mammontini.php' );

// Catch all debug function
function aecDebug( $text, $level = 128 )
{
	global $database;

	$eventlog = new eventLog( $database );
	$eventlog->issue( 'debug', 'debug', json_encode( $text ), $level );
}

if ( !function_exists( 'aecJoomla15check' ) ) {
	function aecJoomla15check()
	{
		global $aecConfig;

		if ( !defined( 'AECJOOMLA15CHECK' ) ) {
			if ( !empty( $aecConfig->cfg['overrideJ15'] ) ) {
				define( 'AECJOOMLA15CHECK', false );
			} else {
				define( 'AECJOOMLA15CHECK', defined( 'JPATH_BASE' ) );
			}
		}

		return AECJOOMLA15CHECK;
	}
}

function aecGetParam( $name, $default='', $safe=false, $safe_params=array() )
{
	if ( aecJoomla15check() ) {
		$return = JArrayHelper::getValue( $_REQUEST, $name );
	} else {
		$return = mosGetParam( $_REQUEST, $name, $default, 0x0002 );
	}

	if ( !is_array( $return ) ) {
		$return = trim( $return );
	}

	if ( !empty( $_POST[$name] ) ) {
		if ( is_array( $_POST[$name] ) && !is_array( $return ) ) {
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
	global $database;

	if ( get_magic_quotes_gpc() ) {
		$return = stripslashes( $value );
	} else {
		$return = $value;
	}

	if ( in_array( 'clear_nonalnum', $safe_params ) ) {
		$value = preg_replace( "/[^a-z \d]/i", "", $value );
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
					for ( $n=0; $n<strlen($return); $n++ ) {
						if ( eregi( "[\<|\>|\"|\'|\%|\;|\(|\)]", $return[$n] ) ) {
							 $r = substr( $return, 0, $n );
							 continue;
						}
					}
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

	return $database->getEscaped( $return );
}

function aecPostParamClear( $array, $safe=false, $safe_params=array( 'string', 'badchars' ) )
{
	$cleararray = array();
	foreach ( $array as $key => $value ) {
		$cleararray[$key] = aecGetParam( $key, $safe, $safe_params );
	}

	return $cleararray;
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

	function metaUser( $userid )
	{
		global $database;

		$this->meta = new metaUserDB( $database );
		$this->meta->loadUserid( $userid );

		$this->cmsUser = false;
		$this->hasCBprofile = false;
		$this->userid = 0;

		$this->hasSubscription = 0;
		$this->objSubscription = null;
		$this->focusSubscription = null;

		if ( $userid ) {
			$this->cmsUser = new mosUser( $database );
			$this->cmsUser->load( $userid );

			$this->userid = $userid;

			$aecid = AECfetchfromDB::SubscriptionIDfromUserID( $userid );
			if ( $aecid ) {
				$this->objSubscription = new Subscription( $database );
				$this->objSubscription->load( $aecid );
				$this->focusSubscription = new Subscription( $database );
				$this->focusSubscription->load( $aecid );
				$this->hasSubscription = 1;
				$this->temporaryRFIX();
			}
		}
	}

	function temporaryRFIX()
	{
		if ( !empty( $this->meta->plan_history->used_plans ) ) {
			$used_plans= $this->meta->plan_history->used_plans;
		} else {
			$used_plans = array();
		}

		if ( !empty( $this->meta->plan_history->plan_history ) && is_array( $this->meta->plan_history->plan_history ) ) {
			$prev = count( $this->meta->plan_history->plan_history ) - 2;
			if ( isset( $this->meta->plan_history->plan_history[$prev] ) ) {
				$previous_plan = $this->meta->plan_history->plan_history[$prev];
			} else {
				$previous_plan = 0;
			}
		} else {
			$previous_plan = 0;
		}

		$this->focusSubscription->used_plans = $used_plans;
		$this->focusSubscription->previous_plan = $previous_plan;
		$this->objSubscription->used_plans = $used_plans;
		$this->objSubscription->previous_plan = $previous_plan;
	}

	function getCMSparams( $name )
	{
		$userParams =& new mosParameters( $this->cmsUser->params );

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
		global $database;

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

				$value = $database->getEscaped( $value );

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
			if ( ( $this->meta->custom_params['tempauth_ip'] == $_SERVER['REMOTE_ADDR'] ) && ( $this->meta->custom_params['tempauth_exptime'] >= time() ) ) {
				return true;
			}
		}

		return false;
	}

	function setTempAuth( $password=false )
	{
		global $aecConfig;

		// Make sure we catch traditional and new joomla passwords
		if ( $password !== false) {
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

		// Set params
		$params = array();
		$params['tempauth_ip'] = $_SERVER['REMOTE_ADDR'];
		$params['tempauth_exptime'] = strtotime( '+' . max( 10, $aecConfig->cfg['temp_auth_exp'] ) . ' minutes', time() );

		// Save params either to subscription or to _user entry
		$this->meta->addCustomParams( $params );
		$this->meta->storeload();

		return true;
	}

	function getSecondarySubscriptions()
	{
		global $database;

		$query = 'SELECT `id`, `plan`, `type`'
				. ' FROM #__acctexp_subscr'
				. ' WHERE `userid` = \'' . (int) $this->userid . '\''
				. ' AND `primary` = \'0\''
				. ' ORDER BY `lastpay_date` DESC'
				;
		$database->setQuery( $query );
		return $database->loadObjectList();
	}

	function procTriggerCreate( $user, $payment, $usage )
	{
		global $database, $aecConfig;

		// Create a new cmsUser from user details - only allowing basic details so far
		// Try different types of usernames to make sure we have a unique one
		$usernames = array( $user['username'],
							$user['username'] . substr( md5( $user['name'] ), 0, 3 ),
							$user['username'] . substr( md5( ( $user['name'] . time() ) ), 0, 3 )
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
			$database->setQuery( $query );

			$id = $database->loadResult();
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
		$invoice = new Invoice( $database );
		$invoice->create( $userid, $usage, $payment['processor'], $payment['secondary_ident'] );

		// return nothing, the invoice will be handled by the second ident anyways
		return;
	}

	function establishFocus( $payment_plan, $processor='none' )
	{
		global $database;

		$plan_params = $payment_plan->params;

		// Check whether a record exists
		if ( $this->hasSubscription ) {
			$existing_record = $this->focusSubscription->getSubscriptionID( $this->userid, $payment_plan->id, null );
		} else {
			$existing_record = 0;
		}

		if ( !isset( $plan_params['make_primary'] ) ) {
			$plan_params['make_primary'] = 1;
		}

		// To be failsafe, a new subscription may have to be added in here
		if ( empty( $this->hasSubscription ) || !$plan_params['make_primary'] ) {
			if ( $existing_record && ( $plan_params['update_existing'] || $plan_params['make_primary'] ) ) {
				// Update existing non-primary subscription
				$this->focusSubscription = new Subscription( $database );
				$this->focusSubscription->load( $existing_record );
			} else {
				// Create a root new subscription
				if ( !$plan_params['make_primary'] && !empty( $plan_params['standard_parent'] ) ) {
					$this->focusSubscription = new Subscription( $database );
					$this->focusSubscription->load( 0 );
					$this->focusSubscription->createNew( $this->userid, 'none', 1, 1 );
					$this->focusSubscription->applyUsage( $plan_params['standard_parent'], 'none', 1, 0 );

					$this->objSubscription = $this->focusSubscription;
				}

				// Create new subscription
				$this->focusSubscription = new Subscription( $database );
				$this->focusSubscription->load( 0 );
				$this->focusSubscription->createNew( $this->userid, $processor, 1, $plan_params['make_primary'] );
				$this->hasSubscription = 1;

				if ( $plan_params['make_primary'] ) {
					$this->objSubscription = $this->focusSubscription;
				}
			}
		}

		$this->temporaryRFIX();
	}

	function moveFocus( $subscrid )
	{
		global $database;

		$subscription = new Subscription( $database );
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
		global $database;

		// Get all the users subscriptions
		$query = 'SELECT id'
				. ' FROM #__acctexp_subscr'
				. ' WHERE `userid` = \'' . (int) $this->userid . '\''
				;
		$database->setQuery( $query );
		$subscrids = $database->loadResultArray();

		if ( count( $subscrids ) > 1 ) {
			$this->allSubscriptions = array();

			foreach ( $subscrids as $subscrid ) {
			$subscription = new Subscription( $database );
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

	function instantGIDchange( $gid )
	{
		global $database, $acl;

		// Always protect last administrator
		if ( ( $this->cmsUser->gid == 24 ) || ( $this->cmsUser->gid == 25 ) ) {
			$query = 'SELECT count(*)'
					. ' FROM #__core_acl_groups_aro_map'
					. ' WHERE `group_id` = \'25\''
					;
			$database->setQuery( $query );
			if ( $database->loadResult() <= 1) {
				return false;
			}

			$query = 'SELECT count(*)'
					. ' FROM #__core_acl_groups_aro_map'
					. ' WHERE `group_id` = \'24\''
					;
			$database->setQuery( $query );
			if ( $database->loadResult() <= 1) {
				return false;
			}
		}

		// Get ARO ID for user
		$query = 'SELECT `' . ( aecJoomla15check() ? 'id' : 'aro_id' )  . '`'
				. ' FROM #__core_acl_aro'
				. ' WHERE `value` = \'' . (int) $this->userid . '\''
				;
		$database->setQuery( $query );
		$aro_id = $database->loadResult();

		// If we have no aro id, something went wrong and we need to create it
		if ( empty( $aro_id ) ) {
			$query2 = 'INSERT INTO #__core_acl_aro'
					. ' (`section_value`, `value`, `order_value`, `name`, `hidden` )'
					. ' VALUES ( \'users\', \'' . $this->userid . '\', \'0\', \'' . $this->cmsUser->name . '\', \'0\' )'
					;
			$database->setQuery( $query2 );
			$database->query();

			$database->setQuery( $query );
			$aro_id = $database->loadResult();
		}

		// TODO: Fallback case if groups_aro_map is broken

		// Carry out ARO ID -> ACL group mapping
		$query = 'UPDATE #__core_acl_groups_aro_map'
				. ' SET `group_id` = \'' . (int) $gid . '\''
				. ' WHERE `aro_id` = \'' . $aro_id . '\''
				;
		$database->setQuery( $query );
		$database->query() or die( $database->stderr() );

		// Moxie Mod - updated to add usertype to users table and update session table for immediate access to usertype features
		$gid_name = $acl->get_group_name( $gid, 'ARO' );

		// Set GID and usertype
		$query = 'UPDATE #__users'
				. ' SET `gid` = \'' .  (int) $gid . '\', `usertype` = \'' . $gid_name . '\''
				. ' WHERE `id` = \''  . (int) $this->userid . '\''
				;
		$database->setQuery( $query );
		$database->query() or die( $database->stderr() );

		// Update Session
		$query = 'UPDATE #__session'
				. ' SET `usertype` = \'' . $gid_name . '\''
				. ' WHERE `userid` = \'' . (int) $this->userid . '\''
				;
		$database->setQuery( $query );
		$database->query() or die( $database->stderr() );
	}

	function loadCBuser()
	{
		global $database;

		$query = 'SELECT `name`'
				. ' FROM #__comprofiler_fields'
				. ' WHERE `table` != \'#__users\''
				. ' AND `name` != \'NA\'';
		$database->setQuery( $query );
		$fields = $database->loadResultArray();

		$query = 'SELECT cbactivation' . ( !empty( $fields ) ? ', ' . implode( ', ', $fields ) : '')
				. ' FROM #__comprofiler'
				. ' WHERE `user_id` = \'' . (int) $this->userid . '\'';
		$database->setQuery( $query );
		$database->loadObject( $this->cbUser );

		if ( is_object( $this->cbUser ) ) {
			$this->hasCBprofile = true;
		}
	}

	function CustomRestrictionResponse( $restrictions )
	{
		$s = array();
		$n = 0;
		foreach ( $restrictions as $restriction ) {
			$check1 = AECToolbox::rewriteEngine( $restriction[0], $this );
			$check2 = AECToolbox::rewriteEngine( $restriction[2], $this );
			$eval = $restriction[1];

			$s['customchecker'.$n] = AECToolbox::compare( $eval, $check1, $check2 );
			$n++;
		}

		return $s;
	}

	function permissionResponse( $restrictions )
	{
		if ( is_array( $restrictions ) ) {
			$return = array();
			foreach ( $restrictions as $name => $value ) {
				// TODO: Tautological && ? Use empty instead?
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
								if ( (int) $value === (int) $this->cmsUser->gid ) {
									$status = true;
								}
							}
							break;
						// Check for Minimum GID
						case 'mingid':
							if ( is_object( $this->cmsUser ) ) {
								$groups = GeneralInfoRequester::getLowerACLGroup( (int) $this->cmsUser->gid );
								if ( in_array( (int) $value, (array) $groups ) ) {
									$status = true;
								}
							}
							break;
						// Check for Maximum GID
						case 'maxgid':
							if ( is_object( $this->cmsUser ) ) {
								$groups = GeneralInfoRequester::getLowerACLGroup( $value );
								if ( in_array( (int) $this->cmsUser->gid, (array) $groups) ) {
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
								if ( in_array( (int) $this->focusSubscription->plan, $check ) ) {
									$status = true;
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
								if (
									( in_array( (int) $this->meta->getPreviousPlan(), $check ) )
									|| ( ( in_array( 0, $check ) ) && is_null( $this->meta->getPreviousPlan() ) )
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
								$array = $this->meta->getUsedPlans();
								foreach ( $check as $v ) {
									if ( ( !empty( $array[(int) $v] ) || ( $this->focusSubscription->plan == $v ) ) ) {
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
								$usage = $this->meta->getUsedPlans();
								$check = explode( ',', $value );
								if ( isset( $usage[(int) $check[0]] ) ) {
									// We have to add one here if the user is currently in the plan
									if ( (int) $this->focusSubscription->plan === (int) $check[0] ) {
										$used_times = (int) $check[1] + 1;
									} else {
										$used_times = (int) $check[1];
									}

									if ( $usage[(int) $check[0]] >= (int) $used_times ) {
										$status = true;
									}
								}
							}
							break;
						// Check whether the user has used the plan at max a certain number of times
						case 'plan_amount_max':
							if ( $this->hasSubscription ) {
								$usage = $this->meta->getUsedPlans();
								$check = explode( ',', $value );
								if ( isset( $usage[(int) $check[0]] ) ) {
									// We have to add one here if the user is currently in the plan
									if ( (int) $this->focusSubscription->plan === (int) $check[0] ) {
										$used_times = (int) $check[1] + 1;
									} else {
										$used_times = (int) $check[1];
									}

									if ( $usage[(int) $check[0]] <= (int) $used_times ) {
										$status = true;
									}
								} else {
									$status = true;
								}
							} else {
								// New user will always pass max plan amount test
								$status = true;
							}
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
			return false;
		}
	}

	function usedCoupon ( $couponid, $type )
	{
		global $database;

		$query = 'SELECT `usecount`'
				. ' FROM #__acctexp_couponsxuser'
				. ' WHERE `userid` = \'' . $this->userid . '\''
				. ' AND `coupon_id` = \'' . $couponid . '\''
				. ' AND `coupon_type` = \'' . $type . '\''
				;
		$database->setQuery( $query );
		$usecount = $database->loadResult();

		if ( $usecount ) {
			return $usecount;
		} else {
			return false;
		}
	}

	function getProperty( $key )
	{
		if ( !is_array( $key ) ) {
			return $this->$key;
		} else {
			$subject = $this;

			foreach ( $key as $k ) {
					if ( is_object( $subject ) ) {
						if ( isset( $subject->{$k} ) ) {
							$return =& $subject->{$k};
						} else {
							global $database;

							$props = get_object_vars( $subject );

							$event = 'Syntax Parser cannot parse next property: ' . $k . '; does not exist! Possible values are: ' . implode( ';', $props );

							$eventlog = new eventLog( $database );
							$eventlog->issue( 'AECjson cmd:metaUser Syntax Error', 'aecjson,metaUser,syntax,error', $event, 128, array() );
						}
					} elseif ( is_array( $subject ) ) {
						if ( isset( $subject[$k] ) ) {
							$return =& $subject[$k];
						} else {
							global $database;

							$props = array_keys( $subject );

							$event = 'Syntax Parser cannot parse next key: ' . $k . '; does not exist! Possible values are: ' . implode( ';', $props );

							$eventlog = new eventLog( $database );
							$eventlog->issue( 'AECjson cmd:metaUser Syntax Error', 'aecjson,metaUser,syntax,error', $event, 128, array() );
						}

					} else {
						global $database;

						$event = 'Syntax Parser cannot parse next reference: ' . $k . '; neither property nor array field';

						$eventlog = new eventLog( $database );
						$eventlog->issue( 'AECjson cmd:metaUser Syntax Error', 'aecjson,metaUser,syntax,error', $event, 128, array() );
						return false;
					}

				$subject =& $return;
			}

			return $return;
		}
	}

	function getUserMIs(){
		global $database;

		$return = array();
		if ( $this->objSubscription->plan ) {
			$selected_plan = new SubscriptionPlan( $database );
			$selected_plan->load( $this->objSubscription->plan );

			$mis = $selected_plan->micro_integrations;

			if ( count( $mis ) ) {
				foreach ( $mis as $mi_id ) {
					if ( $mi_id ) {
						$mi = new MicroIntegration( $database );
						$mi->load( $mi_id );

						if ( !$mi->callIntegration() ) {
							continue;
						}

						$return[] = $mi;
					}
				}
			}
		}

		return $return;
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
		$this->mosDBTable( '#__acctexp_metauser', 'id', $db );
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
		global $database;

		$query = 'SELECT `id`'
				. ' FROM #__acctexp_metauser'
				. ' WHERE `userid` = \'' . $userid . '\''
				;
		$database->setQuery( $query );

		return $database->loadResult();
	}

	function createNew( $userid )
	{
		global $mosConfig_offset;

		$this->userid			= $userid;
		$this->created_date		= date( 'Y-m-d H:i:s', time() + $mosConfig_offset*3600 );
		$this->modified_date	= date( 'Y-m-d H:i:s', time() + $mosConfig_offset*3600 );

		$this->check();
		$this->store();
		$this->id = $this->getMax();

	}

	function getProcessorParams( $processorid )
	{
		if ( isset( $this->processor_params->$processorid ) ) {
			return $this->processor_params->$processorid;
		} else {
			return false;
		}
	}

	function setProcessorParams( $processorid, $params )
	{
		global $mosConfig_offset;

		if ( !isset( $this->processor_params ) ) {
			$this->processor_params = new stdClass();
		}

		if ( !is_object( $this->processor_params ) ) {
			$this->processor_params = new stdClass();
		}

		$this->processor_params->$processorid = $params;

		$this->modified_date	= date( 'Y-m-d H:i:s', time() + $mosConfig_offset*3600 );

		$this->storeload();
	}

	function getMIParams( $miid, $usageid=false )
	{
		if ( $usageid ) {
			if ( isset( $this->plan_params[$usageid] ) ) {
				if ( isset( $this->plan_params[$usageid][$miid] ) ) {
					return $this->plan_params[$usageid][$miid];
				}
			}
		} else {
			if ( isset( $this->params->mi[$miid] ) ) {
				return $this->params->mi[$miid];
			}
		}

		return false;
	}

	function setMIParams( $miid, $usageid=false, $params )
	{
		global $mosConfig_offset;

		if ( $usageid ) {
			if ( isset( $this->plan_params[$usageid] ) ) {
				if ( isset( $this->plan_params[$usageid][$miid] ) ) {
					$this->plan_params[$usageid][$miid] = $this->mergeParams( $this->plan_params[$usageid][$miid], $params );
				} else {
					$this->plan_params[$usageid][$miid] = $params;
				}
			} else {
				$this->plan_params[$usageid][$miid] = $params;
			}
		}

		if ( isset( $this->params->mi[$miid] ) ) {
			$this->params->mi[$miid] = $this->mergeParams( $this->params->mi[$miid], $params );
		} else {
			$this->params->mi[$miid] = $params;
		}

		$this->modified_date	= date( 'Y-m-d H:i:s', time() + $mosConfig_offset*3600 );

		return true;
	}

	function addCustomParams( $params )
	{
		global $mosConfig_offset;

		$this->addParams( $params, 'custom_params' );

		$this->modified_date	= date( 'Y-m-d H:i:s', time() + $mosConfig_offset*3600 );
	}

	function addPreparedMIParams( $plan_mi, $mi=false )
	{
		global $mosConfig_offset;

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

		$this->modified_date	= date( 'Y-m-d H:i:s', time() + $mosConfig_offset*3600 );

		$this->storeload();
	}

	function addPlanID( $id )
	{
		global $mosConfig_offset;

		$this->plan_history->plan_history[] = $id;

		if ( isset( $this->plan_history->used_plans[$id] ) ) {
			$this->plan_history->used_plans[$id]++;
		} else {
			$this->plan_history->used_plans[$id] = 1;
		}

		$this->modified_date	= date( 'Y-m-d H:i:s', time() + $mosConfig_offset*3600 );

		$this->storeload();

		return true;
	}

	function getUsedPlans()
	{
		return $this->plan_history->used_plans;
	}

	function getPreviousPlan()
	{
		$last = count( $this->plan_history->plan_history ) - 1;

		if ( $last < 0 ) {
			return null;
		} elseif ( isset( $this->plan_history->plan_history[$last] ) ) {
			return $this->plan_history->plan_history[$last];
		} else {
			return null;
		}
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
		$this->mosDBTable( '#__acctexp_config', 'id', $db );

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

	function initParams()
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
		$def['display_date_frontend']			= "%a, %d %b %Y %T %Z";
		$def['display_date_backend']			= "%a, %d %b %Y %T %Z";
		$def['enable_mimeta']					= 0;
		$def['enable_coupons']					= 0;
		$def['gwlist']							= array();
		$def['milist']							= array( 'mi_email','mi_htaccess','mi_mysql_query','mi_email','mi_aecplan','mi_joomlauser' );
		$def['displayccinfo']					= 1;
		$def['customtext_confirm_keeporiginal']	= 1;
		$def['customtext_checkout_keeporiginal']	= 1;
		$def['customtext_notallowed_keeporiginal']	= 1;
		$def['customtext_pending_keeporiginal']	= 1;
		$def['customtext_expired_keeporiginal']	= 1;
		// new 0.12.4
		$def['bypassintegration']				= '';
		$def['customintro']						= '';
		$def['customthanks']					= '';
		$def['customcancel']					= '';
		$def['customnotallowed']				= '';
		$def['tos']								= '';
		$def['customtext_plans']				= '';
		$def['customtext_confirm']				= '';
		$def['customtext_checkout']				= '';
		$def['customtext_notallowed']			= '';
		$def['customtext_pending']				= '';
		$def['customtext_expired']				= '';
		// new 0.12.4.2
		$def['adminaccess']						= 1;
		$def['noemails']						= 0;
		$def['nojoomlaregemails']				= 0;
		// new 0.12.4.10
		$def['debugmode']						= 0;
		// new 0.12.4.12
		$def['override_reqssl']					= 0;
		// new 0.12.4.16
		$def['invoicenum_doformat']				= 0;
		$def['invoicenum_formatting']			= '{aecjson}{"cmd":"concat","vars":[{"cmd":"date","vars":["Y",{"cmd":"rw_constant","vars":"invoice_created_date"}]},"-",{"cmd":"rw_constant","vars":"invoice_id"}]}{/aecjson}';
		$def['use_recaptcha']					= 0;
		$def['recaptcha_privatekey']			= '';
		$def['recaptcha_publickey']				= '';
		$def['ssl_signup']						= 0;
		$def['error_notification_level']		= 32;
		$def['email_notification_level']		= 128;
		$def['temp_auth_exp']					= 60;
		$def['skip_confirmation']				= 0;
		$def['show_fixeddecision']				= 0;
		$def['confirmation_coupons']			= 0;
		$def['breakon_mi_error']				= 0;
		$def['curl_default']					= 1;
		$def['amount_currency_symbol']			= 0;
		$def['amount_currency_symbolfirst']		= 0;
		$def['amount_use_comma']				= 0;
		$def['tos_iframe']						= 0;
		$def['use_proxy']						= 0;
		$def['proxy']							= '';
		$def['proxy_port']						= '';
		$def['ssl_profile']						= 0;
		$def['customtext_thanks_keeporiginal']	= 1;
		$def['customtext_thanks']				= '';
		$def['customtext_cancel_keeporiginal']	= 1;
		$def['customtext_cancel']				= '';
		$def['renew_button_never']				= 0;
		$def['renew_button_nolifetimerecurring']= 1;
		$def['continue_button']					= 1;
		// new 0.12.6
		$def['overrideJ15']						= 0;
		$def['authlist']						= array( 'joomla' );
		$def['authorization_list']				= array();
		$def['customtext_hold_keeporiginal']	= 1;
		$def['customtext_hold']					= '';
		$def['proxy_username']					= '';
		$def['proxy_password']					= '';
		$def['gethostbyaddr']					= 1;
		$def['root_group']						= 1;
		$def['root_group_rw']					= '';
		// TODO: $def['show_groups_first']						= 1;
		// TODO: $def['show_empty_groups']						= 1;

		// Insert a new entry if there is none yet
		if ( empty( $this->settings ) ) {
			global $database;

			$query = 'SELECT * FROM #__acctexp_config'
			. ' WHERE `id` = \'1\''
			;
			$database->setQuery( $query );

			if ( !$database->loadResult() ) {
				$query = 'INSERT INTO #__acctexp_config'
				. ' VALUES( \'1\', \'\' )'
				;
				$database->setQuery( $query );
				$database->query() or die( $database->stderr() );
			}

			$this->id = 1;
			$this->settings = '';
		}

		// Write to Params, do not overwrite existing data
		$this->addParams( $def, 'settings', false );

		$this->storeload();

		return true;
	}

	function saveSettings()
	{
		// Extra check for duplicated rows
		// TODO: Sometime in the future, this can be abandoned, but not without a check!
		if ( $this->RowDuplicationCheck() ) {
			$this->CleanDuplicatedRows();
			$this->load(1);
		}

		$this->cfg['aec_version'] = _AEC_VERSION;

		$this->storeload();
	}

	function RowDuplicationCheck()
	{
		global $database;

		$query = 'SELECT count(*)'
				. ' FROM #__acctexp_config'
				;
		$database->setQuery( $query );
		$rows = $database->loadResult();

		if ( $rows > 1 ) {
			return true;
		} else {
			return false;
		}
	}

	function CleanDuplicatedRows()
	{
		global $database;

		$query = 'SELECT max(id)'
				. ' FROM #__acctexp_config'
				;
		$database->setQuery( $query );
		$database->query();
		$max = $database->loadResult();

		$query = 'DELETE'
				. ' FROM #__acctexp_config'
				. ' WHERE `id` != \'' . $max . '\''
				;
		$database->setQuery( $query );
		$database->query();

		if ( !( $max == 1 ) ) {
			$query = 'UPDATE #__acctexp_config'
					. ' SET `id` = \'1\''
					. ' WHERE `id` =\'' . $max . '\''
					;
			$database->setQuery( $query );
			$database->query();
		}
	}
}

if ( !is_object( $aecConfig ) ) {
	global $database, $aecConfig;

	$aecConfig = new Config_General( $database );
}

class aecHeartbeat extends mosDBTable
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
	 	$this->mosDBTable( '#__acctexp_heartbeat', 'id', $db );
	 	$this->load(1);

		if ( empty( $this->last_beat ) ) {
			global $aecConfig;

			$query = 'INSERT INTO #__acctexp_heartbeat'
			. ' VALUES( \'1\', \'' . date( 'Y-m-d H:i:s', ( time() - $aecConfig->cfg['heartbeat_cycle'] * 3600 ) ) . '\' )'
			;
			$this->_db->setQuery( $query );
			$this->_db->query() or die( $this->_db->stderr() );
		}
	}

	function frontendping()
	{
		global $aecConfig;

		if ( !empty( $aecConfig->cfg['heartbeat_cycle'] ) ) {
			$this->ping( $aecConfig->cfg['heartbeat_cycle'] );
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
		global $mainframe;

		if ( empty( $this->last_beat ) ) {
			$this->load(1);
		}

		if ( $this->last_beat ) {
			$ping	= strtotime( $this->last_beat ) + $configCycle*3600;
		} else {
			$ping = 0;
		}

		if ( ( $ping - time() ) <= 0 ) {
			$this->last_beat = date( 'Y-m-d H:i:s', time() );
			$this->check();
			$this->store();
			$this->load(1);

			$this->beat();
		} else {
			// sleep, mechanical Hound, but do not sleep
			// kept awake with wolves teeth
		}
	}

	function beat()
	{
		global $database, $aecConfig;
		// Other ideas: Clean out old Coupons

		// TODO: function to clean up database before doing the checks - could improve performance
		// maybe just set a database flag for this, so that database cleanup is done only every X days

		// Receive maximum pre expiration time
		$query = 'SELECT MAX(pre_exp_check)'
				. ' FROM #__acctexp_microintegrations'
				. ' WHERE `active` = \'1\''
				;
		$database->setQuery( $query );
		$pre_expiration = $database->loadResult();

		if ( $pre_expiration ) {
			// pre-expiration found, search limit set to the maximum pre-expiration time
			$expiration_limit = AECToolbox::computeExpiration( ( $pre_expiration + 1 ), 'D', time() );
		} else {
			// No pre-expiration actions found, limiting search to all users who expire until tomorrow (just to be safe)
			$pre_expiration		= false;
			$expiration_limit	= AECToolbox::computeExpiration( 1, 'D', time() );
		}

		// Select all the users that are Active and have an expiration date
		$query = 'SELECT `id`'
				. ' FROM #__acctexp_subscr'
				. ' WHERE `expiration` <= \'' . $expiration_limit . '\''
				. ' AND `status` != \'Expired\''
				. ' AND `status` != \'Closed\''
				. ' AND `status` != \'Excluded\''
				. ' ORDER BY `expiration`'
				;
		$database->setQuery( $query );
		$subscription_list = $database->loadResultArray();

		$expired_users		= array();
		$pre_expired_users	= array();
		$found_expired		= 1;
		$e					= 0;
		$pe					= 0;
		$exp_actions		= 0;
		$exp_users			= 0;
		$pps				= array();

		// Efficient way to check for expired users without checking on each one
		if ( !empty( $subscription_list ) ) {
			foreach ( $subscription_list as $sub_id ) {
				$subscription = new Subscription($database);
				$subscription->load( $sub_id );

				if ( $found_expired ) {
					// Check whether this user really is expired
					// If this check fails, this user and all following users will be put into pre expiration check
					$found_expired = $subscription->is_expired();

					if ( $found_expired && !in_array( $subscription->userid, $expired_users ) ) {
						// We may need to carry out processor functions
						if ( !isset( $pps[$subscription->type] ) ) {
							// Load payment processor into overall array
							$pps[$subscription->type] = new PaymentProcessor();
							if ( $pps[$subscription->type]->loadName( $subscription->type ) ) {
								$pps[$subscription->type]->init();

								// Load prepare validation function
								$prepval = $pps[$subscription->type]->prepareValidation( $subscription_list );
								if ( $prepval === null ) {
									// This Processor has no such function, set to false to ignore later calls
									$pps[$subscription->type] = false;
								} elseif ( $prepval === false ) {
									// Break - we have a problem with one processor
									$eventlog = new eventLog( $database );
									$eventlog->issue( 'heartbeat failed - processor', 'heartbeat, failure,'.$subscription->type, 'The payment processor failed to respond to validation request - waiting for next turn', 128 );
									return;
								}
							} else {
								// Processor does not exist
								$pps[$subscription->type] = false;
							}
						}

						// Carry out validation if possible
						if ( !empty( $pps[$subscription->type] ) ) {
							$validation = $pps[$subscription->type]->validateSubscription( $sub_id, $subscription_list );
						} else {
							$validation = false;
						}

						// Validation failed or was not possible for this processor - expire
						if ( empty( $validation ) ) {
							if ( $subscription->expire() ) {
								$e++;
							}
						} else {

						}
					}
				}

				// If we have found all expired users, put all others into pre expiration
				if ( !$found_expired && !in_array( $subscription->id, $pre_expired_users ) ) {
					if ( $pre_expiration ) {
						$pre_expired_users[] = $subscription->id;
					}
				}
			}

			// Only go for pre expiration action if we have at least one user for it
			if ( $pre_expiration && !empty( $pre_expired_users ) ) {
				// Get all the MIs which have a pre expiration check
				$query = 'SELECT `id`'
						. ' FROM #__acctexp_microintegrations'
						. ' WHERE `pre_exp_check` > 0'
						;
				$database->setQuery( $query );
				$mi_pexp = $database->loadResultArray();

				// Get all the plans which have MIs
				$query = 'SELECT `id`'
						. ' FROM #__acctexp_plans'
						. ' WHERE `micro_integrations` IS NOT NULL'
						;
				$database->setQuery( $query );
				$plans_mi = $database->loadResultArray();

				// Filter out plans which have not got the right MIs applied
				$expmi_plans = array();
				foreach ( $plans_mi as $plan_id ) {
					$query = 'SELECT `micro_integrations`'
							. ' FROM #__acctexp_plans'
							. ' WHERE `id` = \'' . $plan_id . '\''
							;
					$database->setQuery( $query );
					$plan_mis = unserialize( base64_decode( $database->loadResult() ) );
					$pexp_mis = array_intersect( $plan_mis, $mi_pexp );

					if ( count( $pexp_mis ) ) {
						$expmi_plans[] = $plan_id;
					}
				}

				// Filter out the users which dont have the correct plan
				$query = 'SELECT `id`, `userid`'
						. ' FROM #__acctexp_subscr'
						. ' WHERE `id` IN (' . implode( ',', $pre_expired_users ) . ')'
						. ' AND `plan` IN (' . implode( ',', $expmi_plans) . ')'
						;
				$database->setQuery( $query );
				$sub_list = $database->loadObjectList();

				if ( !empty( $sub_list ) ) {
					foreach ( $sub_list as $sl ) {
						$metaUser = new metaUser( $sl->userid );
						$metaUser->moveFocus( $sl->id );

						// Two double checks here, just to be sure
						if ( !( strcmp( $metaUser->focusSubscription->status, 'Expired' ) === 0 ) && !$metaUser->focusSubscription->recurring ) {
							if ( in_array( $metaUser->focusSubscription->plan, $expmi_plans ) ) {
								// Its ok - load the plan
								$subscription_plan = new SubscriptionPlan( $database );
								$subscription_plan->load( $metaUser->focusSubscription->plan );
								$userplan_mis = $subscription_plan->micro_integrations;

								// Get the right MIs
								$user_pexpmis = array_intersect( $userplan_mis, $mi_pexp );

								// loop through MIs and apply pre exp action
								$check_actions = $exp_actions;

								foreach ( $user_pexpmis as $mi_id ) {
									$mi = new microIntegration( $database );
									$mi->load( $mi_id );

									if ( $mi->callIntegration() ) {
										// Do the actual pre expiration check on this MI
										if ( $metaUser->focusSubscription->is_expired( $mi->pre_exp_check ) ) {
											$result = $mi->pre_expiration_action( $metaUser, $subscription_plan );
											if ( $result ) {
												$exp_actions++;
											}
										}
									}
								}

								if ( $exp_actions > $check_actions ) {
									$exp_users++;
								}
							}
						}
					}
				}
			}
		}

		$short	= _AEC_LOG_SH_HEARTBEAT;
		$event	= _AEC_LOG_LO_HEARTBEAT . ' ';
		$tags	= array( 'heartbeat' );

		if ( $e ) {
			if ( $e > 1 ) {
				$event .= 'Expires ' . $e . ' users';
			} else {
				$event .= 'Expires 1 user';
			}
			$tags[] = 'expiration';
			if ( $exp_actions ) {
				$event .= ', ';
			}
		}
		if ( $exp_actions ) {
			$event .= $exp_actions . ' Pre-expiration action';
			$event .= ( $exp_actions > 1 ) ? 's' : '';
			$event .= ' for ' . $exp_users . ' user';
			$event .= ( $exp_users > 1 ) ? 's' : '';
			$tags[] = 'pre-expiration';
		}

		if ( strcmp( _AEC_LOG_LO_HEARTBEAT . ' ', $event ) === 0 ) {
			$event .= _AEC_LOG_AD_HEARTBEAT_DO_NOTHING;
		}

		$eventlog = new eventLog( $database );
		$eventlog->issue( $short, implode( ',', $tags ), $event, 2 );

	}

}

class displayPipelineHandler
{
	function displayPipelineHandler()
	{

	}

	function getUserPipelineEvents( $userid )
	{
		global $database, $mosConfig_offset;

		// Entries for this user only
		$query = 'SELECT `id`'
				. ' FROM #__acctexp_displaypipeline'
				. ' WHERE `userid` = \'' . $userid . '\' AND `only_user` = \'1\''
				;
		$database->setQuery( $query );
		$events = $database->loadResultArray();

		// Entries for all users
		$query = 'SELECT `id`'
				. ' FROM #__acctexp_displaypipeline'
				. ' WHERE `only_user` = \'0\''
				;
		$database->setQuery( $query );
		$events = array_merge( $events, $database->loadResultArray() );

		$return = '';
		if ( empty( $events ) ) {
			return $return;
		}

		foreach ( $events as $eventid ) {
			$displayPipeline = new displayPipeline( $database );
			$displayPipeline->load( $eventid );
			if ( $displayPipeline->id ) {

				// If expire & expired -> delete
				if ( $displayPipeline->expire ) {
					$expstamp = strtotime( $displayPipeline->expstamp );
					if ( ( $expstamp - ( time() + $mosConfig_offset*3600 ) ) < 0 ) {
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
	 	$this->mosDBTable( '#__acctexp_displaypipeline', 'id', $db );
	}

	function declareParamFields()
	{
		return array( 'params' );
	}

	function create( $userid, $only_user, $once_per_user, $expire, $expiration, $displaymax, $displaytext, $params=null )
	{
		global $mosConfig_offset;

		$this->id				= 0;
		$this->userid			= $userid;
		$this->only_user		= $only_user;
		$this->once_per_user	= $once_per_user;
		$this->timestamp		= date( 'Y-m-d H:i:s', time() + $mosConfig_offset*3600 );
		$this->expire			= $expire ? 1 : 0;
		if ( $expire ) {
			$this->expstamp			= date( 'Y-m-d H:i:s', strtotime( $expiration ) + $mosConfig_offset*3600 );
		}
		$this->displaycount		= 0;
		$this->displaymax		= $displaymax;

		if ( !get_magic_quotes_gpc() ) {
			$this->displaytext	= addslashes( $displaytext );
		} else {
			$this->displaytext	= $displaytext;
		}

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
	 	$this->mosDBTable( '#__acctexp_eventlog', 'id', $db );
	}

	function declareParamFields()
	{
		return array( 'params' );
	}

	function issue( $short, $tags, $text, $level = 2, $params = null, $force_notify = 0, $force_email = 0 )
	{
		global $mosConfig_offset, $aecConfig;

		$legal_levels = array( 2, 8, 32, 128 );

		if ( !in_array( (int) $level, $legal_levels ) ) {
			$level = $legal_levels[0];
		}

		$this->datetime	= date( 'Y-m-d H:i:s', time() + $mosConfig_offset*3600 );
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
			global $mainframe, $database;

			// check if Global Config `mailfrom` and `fromname` values exist
			if ( $mainframe->getCfg( 'mailfrom' ) != '' && $mainframe->getCfg( 'fromname' ) != '' ) {
				$adminName2 	= $mainframe->getCfg( 'fromname' );
				$adminEmail2 	= $mainframe->getCfg( 'mailfrom' );
			} else {
				// use email address and name of first superadmin for use in email sent to user
				$query = 'SELECT `name`, `email`'
						. ' FROM #__users'
						. ' WHERE LOWER( usertype ) = \'superadministrator\''
						. ' OR LOWER( usertype ) = \'super administrator\''
						;
				$database->setQuery( $query );
				$rows = $database->loadObjectList();

				$adminName2 	= $rows[0]->name;
				$adminEmail2 	= $rows[0]->email;
			}

			if ( !defined( "_AEC_NOTICE_NUMBER_" . $this->level ) ) {
				global $mosConfig_absolute_path;

				$langPath = $mosConfig_absolute_path . '/administrator/components/com_acctexp/com_acctexp_language_backend/';
				if ( file_exists( $langPath . $GLOBALS['mosConfig_lang'] . '.php' ) ) {
					include_once( $langPath . $GLOBALS['mosConfig_lang'] . '.php' );
				} else {
					include_once( $langPath. 'english.php' );
				}
			}

			// Send notification to all administrators
			$subject2	= sprintf( _AEC_ASEND_NOTICE, constant( "_AEC_NOTICE_NUMBER_" . $this->level ), $this->short, $mainframe->getCfg( 'sitename' ) );
			$message2	= sprintf( _AEC_ASEND_NOTICE_MSG, $this->event  );

			$subject2	= html_entity_decode( $subject2, ENT_QUOTES );
			$message2	= html_entity_decode( $message2, ENT_QUOTES );

			// get email addresses of all admins and superadmins set to recieve system emails
			$query = 'SELECT `email`'
					. ' FROM #__users'
					. ' WHERE ( `gid` = 24 OR `gid` = 25 )'
					. ' AND `sendEmail` = 1'
					. ' AND `block` = 0'
					;
			$database->setQuery( $query );
			$admins = $database->loadObjectList();

			foreach ( $admins as $admin ) {
				// send email to admin & super admin set to recieve system emails
				mosMail( $adminEmail2, $adminName2, $admin->email, $subject2, $message2 );
			}
		}

		if ( !empty( $params ) && is_array( $params ) ) {
			$this->params = $params;
		}

		$this->check();
		$this->store();
	}

}

class PaymentProcessorHandler
{

	function PaymentProcessorHandler()
	{
		global $mosConfig_absolute_path;
		$this->pp_dir = $mosConfig_absolute_path . '/components/com_acctexp/processors';
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
		global $database;

		$query = 'SELECT `id`'
				. ' FROM #__acctexp_config_processors'
				. ' WHERE `name` = \'' . $database->getEscaped( $name ) . '\'';
		$database->setQuery( $query );

		return $database->loadResult();
	}

	/**
	 * gets installed and active processors
	 *
	 * @param bool	$active		get only active objects
	 * @return array of (active) payment processors
	 */
	function getInstalledObjectList( $active = false )
	{
		global $database;

		$query = 'SELECT `id`, `active`, `name`'
				. ' FROM #__acctexp_config_processors'
				;
		if ( $active ) {
			$query .= ' WHERE `active` = \'1\'';
		}
		$database->setQuery( $query );

		return $database->loadObjectList();
	}

	function getInstalledNameList($active=false)
	{
		global $database;

		$query = 'SELECT `name`'
				. ' FROM #__acctexp_config_processors'
				;
		if ( $active !== false ) {
			$query .= ' WHERE `active` = \'' . $active . '\'';
		}
		$database->setQuery( $query );

		return $database->loadResultArray();
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
		global $database;

		// Set Name
		$this->processor_name = strtolower( $name );

		// See if the processor is installed & set id
		$query = 'SELECT id'
				. ' FROM #__acctexp_config_processors'
				. ' WHERE `name` = \'' . $this->processor_name . '\''
				;
		$database->setQuery( $query );
		$result = $database->loadResult();
		$this->id = $result ? $result : 0;

		$file = $this->pph->pp_dir . '/' . $this->processor_name . '.php';

		// Check whether processor exists
		if ( file_exists( $file ) ) {
			if ( !defined( '_AEC_LANG_PROCESSOR' ) ) {
				$langPath = $this->pph->pp_dir . '/com_acctexp_language_processors/';
				// Include language files for processors
				if ( file_exists( $langPath . $GLOBALS['mosConfig_lang'] . '.php' ) ) {
					include_once( $langPath . $GLOBALS['mosConfig_lang'] . '.php' );
				} else {
					include_once( $langPath . 'english.php' );
				}
			}

			// Call Integration file
			include_once $this->pph->pp_dir . '/' . $this->processor_name . '.php';

			// Initiate Payment Processor Class
			$class_name = 'processor_' . $this->processor_name;
			$this->processor = new $class_name( $database );
			return true;
		} else {
			return false;
		}
	}

	function loadId( $ppid )
	{
		global $database;

		// Fetch name from db and load processor
		$query = 'SELECT `name`'
				. ' FROM #__acctexp_config_processors'
				. ' WHERE `id` = \'' . $ppid . '\''
				;
		$database->setQuery( $query );
		$name = $database->loadResult();
		if ( $name ) {
			return $this->loadName( $name );
		} else {
			return false;
		}
	}

	function fullInit()
	{
		$this->init();
		$this->getInfo();
		$this->getSettings();
	}

	function init()
	{
		if ( !$this->id ) {
			// Install and recurse
			$this->install();
			$this->init();
		} else {
			// Initiate processor from db
			$this->processor->load( $this->id );
		}
	}

	function install()
	{
		global $database;

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

		$query = 'SELECT `id`'
				. ' FROM #__acctexp_config_processors'
				. ' WHERE `name` = \'' . $database->getEscaped( $this->processor_name ) . '\''
				;
		$database->setQuery( $query );
		$result = $database->loadResult();

		$this->id = $result ? $result : 0;
	}

	function getInfo()
	{
		$this->info	=& $this->processor->info;
		$original	= $this->processor->info();

		foreach ( $original as $name => $var ) {
			if ( !isset( $this->info[$name] ) ) {
				$this->info[$name] = $var;
			}
		}
	}

	function getSettings()
	{
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

	function exchangeSettings( $settings )
	{
		 if ( !empty( $settings ) ) {
			 foreach ( $settings as $key => $value ) {
				if ( is_array( $value ) ) {
					continue;
				}

				if ( strcmp( $value, '[[SET_TO_NULL]]' ) === 0 ) {
					$this->settings[$key] = null;
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
			$planparams = $plan->getProcessorParameters( $this->id );
		}

		if ( isset( $plan_params['aec_overwrite_settings'] ) ) {
			unset( $plan_params['aec_overwrite_settings'] );
		}

		$this->exchangeSettings( $plan_params );
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

		if ( !isset( $this->info['recurring'] ) ) {
			// Keep false
		} elseif ( $this->info['recurring'] > 1 ) {
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

	function setInfo()
	{
		$this->processor->storeload();
	}

	function storeload()
	{
		if ( $this->id ) {
			$this->processor->storeload();
		} else {
			$this->id = $this->getMax();
			$this->processor->storeload();
		}
	}

	function getBackendSettings()
	{
		if ( empty( $this->settings ) ) {
			$this->getSettings();
		}

		if ( is_int( $this->is_recurring() ) ) {
			$settings = array_merge( array( 'recurring' => array( 'list_recurring' ) ), $this->processor->backend_settings() );
		} else {
			$settings = $this->processor->backend_settings();
		}

		$default_settings = array();
		$default_settings['generic_buttons']	= array( 'list_yesno' );

		return array_merge( $settings, $default_settings );
	}

	function checkoutAction( $int_var=null, $metaUser=null, $new_subscription=null, $invoice=null )
	{
		if ( empty( $this->settings ) ) {
			$this->getSettings();
		}

		if ( isset( $int_var['planparams']['aec_overwrite_settings'] ) ) {
			if ( !empty( $int_var['planparams']['aec_overwrite_settings'] ) ) {
				$this->exchangeSettingsByPlan( null, $int_var['planparams'] );
			}
		}

		$request = new stdClass();
		$request->parent			=& $this;
		$request->int_var			=& $int_var;
		$request->metaUser			=& $metaUser;
		$request->new_subscription	=& $new_subscription;
		$request->invoice			=& $invoice;

		return $this->processor->checkoutAction( $request );
	}

	function checkoutProcess( $int_var=null, $metaUser=null, $new_subscription=null, $invoice=null )
	{
		if ( empty( $this->settings ) ) {
			$this->getSettings();
		}

		if ( isset( $int_var['planparams']['aec_overwrite_settings'] ) ) {
			if ( !empty( $int_var['planparams']['aec_overwrite_settings'] ) ) {
				$this->exchangeSettingsByPlan( null, $int_var['planparams'] );
			}
		}

		$request = new stdClass();
		$request->parent			=& $this;
		$request->int_var			=& $int_var;
		$request->metaUser			=& $metaUser;
		$request->new_subscription	=& $new_subscription;
		$request->invoice			=& $invoice;

		return $this->processor->checkoutProcess( $request );
	}

	function customAction( $action, $invoice, $metaUser )
	{
		if ( empty( $this->settings ) ) {
			$this->getSettings();
		}

		$method = 'customaction_' . $action;

		if ( method_exists( $this->processor, $method ) ) {
			$request = new stdClass();
			$request->parent			=& $this;
			$request->metaUser			=& $metaUser;
			$request->invoice			=& $invoice;

			return $this->processor->$method( $request );
		} else {
			return false;
		}
	}

	function customProfileTab( $action, $metaUser )
	{
		if ( empty( $this->settings ) ) {
			$this->getSettings();
		}

		$method = 'customtab_' . $action;

		if ( method_exists( $this->processor, $method ) ) {
			global $database;

			$request = new stdClass();
			$request->parent			=& $this;
			$request->metaUser			=& $metaUser;

			$invoice = new Invoice( $database );
			$invoice->loadbySubscriptionId( $metaUser->objSubscription->id );

			$request->invoice			=& $invoice;


			return $this->processor->$method( $request );
		} else {
			return false;
		}
	}

	function getParamsHTML( $params, $values )
	{
		$return = false;
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

				unset( $values['params'] );
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

		if ( method_exists( $this->processor, 'CustomPlanParams' ) ) {
			return $this->processor->CustomPlanParams();
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
		global $database;

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
			$invoice = new Invoice( $database );
			$invoice->create( $userid, $usage, $this->processor_name );
			$invoice->computeAmount();

			// Set new return variable - we now know what invoice this is
			$return['invoice'] = $invoice->invoice_number;

			unset( $return['_aec_createuser'] );
		} elseif ( !empty( $return['_aec_createuser'] ) ) {
			unset( $return['_aec_createuser'] );
		} elseif ( !empty( $return['secondary_ident'] )&& !empty( $return['invoice'] ) ) {
			unset( $return['_aec_createuser'] );

			$invoice = new Invoice( $database );
			$invoice->loadInvoiceNumber( $return['invoice'] );
			$invoice->secondary_ident = $return['secondary_ident'];
			$invoice->storeload();
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

		if ( method_exists( $this->processor, 'validateSubscription' ) ) {
			$response = $this->processor->validateSubscription( $subscription_id );
		} else {
			$response = false;
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
		$this->mosDBTable( '#__acctexp_config_processors', 'id', $db );
	}

	function declareParamFields()
	{
		return array( 'info', 'settings', 'params' );
	}

	function loadName( $name )
	{
		global $database;

		$query = 'SELECT `id`'
				. ' FROM #__acctexp_config_processors'
				. ' WHERE `name` = \'' . $database->getEscaped( $name ) . '\''
				;
		$database->setQuery( $query );
		$this->load( $database->loadResult() );
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

	function checkoutAction( $request )
	{
		return '<p>' . $this->settings['info'] . '</p>';
	}

	function exchangeSettings( $settings, $planvars )
	{
		foreach ( $settings as $key => $value ) {
			if ( isset( $planvars[$key] ) ) {
				if ( !is_null( $planvars[$key] ) && ( $planvars[$key] != '' ) ) {
					if ( strcmp( $planvars[$key], '[[SET_TO_NULL]]' ) === 0 ) {
						$settings[$key] = '';
					} else {
						$settings[$key] = $planvars[$key];
					}
				}
			}
		}

		return $settings;
	}

	function customParams( $custom, $var, $request )
	{
		if ( !empty( $custom ) ) {
			$rw_params = AECToolbox::rewriteEngine( $custom, $request->metaUser, $request->new_subscription, $request->invoice );

			$params = explode( "\n", $rw_params );

			foreach ( $params as $custom ) {
				$paramsarray = explode( '=', $custom );

				if ( !empty( $paramsarray[0] ) && isset( $paramsarray[1] ) ) {
					$var[$paramsarray[0]] = $paramsarray[1];
				}
			}
		}

		return $var;
	}

	function transmitRequest( $url, $path, $content, $port=443, $curlextra=null )
	{
		global $aecConfig;

		$response = null;

		if ( $aecConfig->cfg['curl_default'] ) {
			$response = $this->doTheCurl( $url, $content, $curlextra );
			if ( $response === false ) {
				// If curl doesn't work try using fsockopen
				$response = $this->doTheHttp( $url, $path, $content, $port );
			}
		} else {
			$response = $this->doTheHttp( $url, $path, $content, $port );
			if ( $response === false ) {
				// If fsockopen doesn't work try using curl
				$response = $this->doTheCurl( $url, $content, $curlextra );
			}
		}

		return $response;
	}

	function doTheHttp( $url, $path, $content, $port=443 )
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
				$port = $aecConfig->cfg['proxy_port'];
			}

			$connection = fsockopen( $aecConfig->cfg['proxy'], $port, $errno, $errstr, 30 );
		} else {
			$connection = fsockopen( $url, $port, $errno, $errstr, 30 );
		}

		if ( $connection === false ) {
			global $database;

			$short	= 'fsockopen failure';
			$event	= 'Trying to establish connection with ' . $url . ' failed with Error #' . $errno . ' ( "' . $errstr . '" ) - will try cURL instead. If Error persists and cURL works, please permanently switch to using that!';
			$tags	= 'processor,payment,phperror';
			$params = array();

			$eventlog = new eventLog( $database );
			$eventlog->issue( $short, $tags, $event, 128, $params );

			return false;
		} else {
			$header  =	"Host: " . $url  . "\r\n"
						. "User-Agent: PHP Script\r\n"
						. "Content-Type: text/xml\r\n"
						. "Content-Length: " . strlen( $content ) . "\r\n\r\n"
						. "Connection: close\r\n\r\n";
						;

			fwrite( $connection, "POST " . $path . " HTTP/1.1\r\n" );
			fwrite( $connection, $header . $content );

			while ( !feof( $connection ) ) {
				$res = fgets( $connection, 1024 );
			}

			fclose( $connection );

			return $res;
		}
	}

	function doTheCurl( $url, $content, $curlextra=null )
	{
		global $aecConfig;

		if ( function_exists ( curl_init )) {
			$response = false ;
			global $database ;
			$short	= 'cURL failure';
			$event	= 'Trying to establish connection with ' . $url . ' failed - curl_init is not available - will try fsockopen instead. If Error persists and fsockopen works, please permanently switch to using that!';
			$tags	= 'processor,payment,phperror';
			$params = array();

			$eventlog = new eventLog( $database );
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
		$curl_calls[CURLOPT_POST]			= true;
		$curl_calls[CURLOPT_POSTFIELDS]		= $content;
		$curl_calls[CURLOPT_SSL_VERIFYPEER]	= false;
		$curl_calls[CURLOPT_SSL_VERIFYHOST]	= false;

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
			global $database;

			$short	= 'cURL failure';
			$event	= 'Trying to establish connection with ' . $url . ' failed with Error #' . curl_errno( $ch ) . ' ( "' . curl_error( $ch ) . '" ) - will try fsockopen instead. If Error persists and fsockopen works, please permanently switch to using that!';
			$tags	= 'processor,payment,phperror';
			$params = array();

			$eventlog = new eventLog( $database );
			$eventlog->issue( $short, $tags, $event, 128, $params );
		}

		curl_close( $ch );

		return $response;
	}

}

class XMLprocessor extends processor
{
	function checkoutAction( $request )
	{
		$var = $this->checkoutform( $request );

		$return = '<form action="' . AECToolbox::deadsureURL( 'index.php?option=com_acctexp&amp;task=checkout', true ) . '" method="post">' . "\n";
		$return .= $this->getParamsHTML( $var ) . '<br /><br />';
		$return .= '<input type="hidden" name="invoice" value="' . $request->int_var['invoice'] . '" />' . "\n";
		$return .= '<input type="hidden" name="userid" value="' . $request->metaUser->userid . '" />' . "\n";
		$return .= '<input type="hidden" name="task" value="checkout" />' . "\n";
		$return .= '<input type="submit" class="button" value="' . _BUTTON_CHECKOUT . '" /><br /><br />' . "\n";
		$return .= '</form>' . "\n";

		return $return;
	}

	function getParamsHTML( $params )
	{
		$return = '';
		if ( !empty( $params['params'] ) ) {
			if ( is_array( $params['params'] ) ) {
				if ( isset( $params['params']['lists'] ) ) {
					$lists = $params['params']['lists'];
					unset( $params['params']['lists'] );
				} else {
					$lists = null;
				}

				if ( count( $params['params'] ) > 2 ) {
					$table = 1;
					$return .= '<table id="aec_checkout_params">';
				} else {
					$table = 0;
				}

				foreach ( $params['params'] as $name => $entry ) {
					if ( !empty( $name ) || ( $name === 0 ) ) {
						$return .= aecHTML::createFormParticle( $name, $entry, $lists, $table ) . "\n";
					}
				}

				$return .= $table ? '</table>' : '';

				unset( $params['params'] );
			}
		}

		return $return;
	}

	function getMULTIPAYform( $var, $array )
	{
		global $mainframe;

		// Include Mootools tabber
		$mainframe->addCustomHeadTag( '<script type="text/javascript" src="' . $mainframe->getCfg( 'live_site' ) . '/components/com_acctexp/lib/mootools/mootools.js"></script>' );
		$mainframe->addCustomHeadTag( '<script type="text/javascript" src="' . $mainframe->getCfg( 'live_site' ) . '/components/com_acctexp/lib/mootools/mootabs.js"></script>' );
		$mainframe->addCustomHeadTag( '<script type="text/javascript" charset="utf-8">window.addEvent(\'domready\', init);function init() {myTabs1 = new mootabs(\'myTabs\');}</script>' );

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
				$prefix[] = array( 'tabregister', $nl.'details', constant( '_AEC_'.$nu.'FORM_TABNAME' ), true );

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
			if ( isset( $content[$value] ) ) {
				$vcontent = $content[$value];
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
						$options[] = mosHTML::makeOption( $ccname, $cclongname );
					}

					$var['params']['lists']['cardType'] = mosHTML::selectList( $options, 'cardType', 'size="1" style="width:120px;"', 'value', 'text', $vcontent );
					$var['params']['cardType'] = array( 'list', _AEC_CCFORM_CARDTYPE_NAME, $vcontent );
					break;
				case 'card_number':
					// Request the Card number
					$var['params']['cardNumber'] = array( 'inputC', _AEC_CCFORM_CARDNUMBER_NAME, _AEC_CCFORM_CARDNUMBER_DESC, $vcontent );
					break;
				case 'card_exp_month':
					// Create a selection box with 12 months
					$months = array();
					for( $i = 1; $i < 13; $i++ ){
						$month = str_pad( $i, 2, "0", STR_PAD_LEFT );
						$months[] = mosHTML::makeOption( $month, $month );
					}

					$var['params']['lists']['expirationMonth'] = mosHTML::selectList( $months, 'expirationMonth', 'size="1" style="width:50px;"', 'value', 'text', $vcontent );
					$var['params']['expirationMonth'] = array( 'list', _AEC_CCFORM_EXPIRATIONMONTH_NAME, _AEC_CCFORM_EXPIRATIONMONTH_DESC, $vcontent );
					break;
				case 'card_exp_year':
					// Create a selection box with the next 10 years
					$year = date('Y');
					$years = array();
					for( $i = $year; $i < $year + 15; $i++ ) {
						$years[] = mosHTML::makeOption( $i, $i );
					}

					$var['params']['lists']['expirationYear'] = mosHTML::selectList( $years, 'expirationYear', 'size="1" style="width:70px;"', 'value', 'text', $vcontent );
					$var['params']['expirationYear'] = array( 'list', _AEC_CCFORM_EXPIRATIONYEAR_NAME, _AEC_CCFORM_EXPIRATIONYEAR_DESC, $vcontent );
					break;
				case 'card_cvv2':
					$var['params']['cardVV2'] = array( 'inputC', _AEC_CCFORM_CARDVV2_NAME, _AEC_CCFORM_CARDVV2_DESC, $vcontent );
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
			if ( isset( $content[$value] ) ) {
				$vcontent = $content[$value];
			} else {
				$vcontent = '';
			}

			switch ( strtolower( $value ) ) {
				case 'routing_no':
					$var['params']['routing_no'] = array( 'inputC', _AEC_ECHECKFORM_ROUTING_NO_NAME, _AEC_ECHECKFORM_ROUTING_NO_DESC, $vcontent );
					break;
				case 'account_no':
					$var['params']['account_no'] = array( 'inputC', _AEC_ECHECKFORM_ACCOUNT_NO_NAME, _AEC_ECHECKFORM_ACCOUNT_NO_DESC, $vcontent );
					break;
				case 'account_name':
					$var['params']['account_name'] = array( 'inputC', _AEC_ECHECKFORM_ACCOUNT_NAME_NAME, _AEC_ECHECKFORM_ACCOUNT_NAME_DESC, $vcontent );
					break;
				case 'bank_name':
					$var['params']['bank_name'] = array( 'inputC', _AEC_ECHECKFORM_BANK_NAME_NAME, _AEC_ECHECKFORM_BANK_NAME_DESC, $vcontent );
					break;
			}
		}

		return $var;
	}

	function getUserform( $var=array(), $values=null, $metaUser=null, $content=array() )
	{
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

		foreach ( $values as $value ) {
			if ( isset( $content[$value] ) ) {
				$vcontent = $content[$value];
			} else {
				$vcontent = '';
			}

			switch ( strtolower( $value ) ) {
				case 'firstname':
					$var['params']['billFirstName'] = array( 'inputC', _AEC_USERFORM_BILLFIRSTNAME_NAME, _AEC_USERFORM_BILLFIRSTNAME_NAME, $vcontent );
					break;
				case 'lastname':
					$var['params']['billLastName'] = array( 'inputC', _AEC_USERFORM_BILLLASTNAME_NAME, _AEC_USERFORM_BILLLASTNAME_NAME, $vcontent );
					break;
				case 'address':
					$var['params']['billAddress'] = array( 'inputC', _AEC_USERFORM_BILLADDRESS_NAME, _AEC_USERFORM_BILLCOMPANY_NAME, $vcontent );
					break;
				case 'address2':
					$var['params']['billAddress2'] = array( 'inputC', _AEC_USERFORM_BILLADDRESS2_NAME, _AEC_USERFORM_BILLADDRESS2_NAME, $vcontent );
					break;
				case 'city':
					$var['params']['billCity'] = array( 'inputC', _AEC_USERFORM_BILLCITY_NAME, _AEC_USERFORM_BILLCITY_NAME, $vcontent );
					break;
				case 'state':
					$var['params']['billState'] = array( 'inputC', _AEC_USERFORM_BILLSTATE_NAME, _AEC_USERFORM_BILLSTATE_NAME, $vcontent );
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
							$statelist[] = mosHTML::makeOption( '" disabled="disabled', $state );
						} else {
							$statelist[] = mosHTML::makeOption( $state, $state );
						}
					}

					$var['params']['lists']['billState'] = mosHTML::selectList( $statelist, 'billState', 'size="1"', 'value', 'text', $vcontent );
					$var['params']['billState'] = array( 'list', _AEC_USERFORM_BILLSTATE_NAME, $vcontent );
					break;
				case 'state_usca':
					$states = array( '', '--- United States ---', 'AK', 'AL', 'AR', 'AZ', 'CA', 'CO', 'CT', 'DC', 'DE', 'FL', 'GA', 'HI',
										'IA', 'ID', 'IL', 'IN', 'KS', 'KY', 'LA', 'MA', 'MD', 'ME',
										'MI', 'MN', 'MO', 'MS', 'MT', 'NC', 'ND', 'NE', 'NH', 'NJ',
										'NM', 'NV', 'NY', 'OH', 'OK', 'OR', 'PA', 'RI', 'SC', 'SD',
										'TN', 'TX', 'UT', 'VA', 'VT', 'WA', 'WI', 'WV', 'WY', 'AA',
										'AE', 'AP', 'AS', 'FM', 'GU', 'MH', 'MP', 'PR', 'PW', 'VI',
										'--- Canada ---','BC','MB','NB','NL','NT','NS','NU','ON','PE','QC','SK','YT'
										);

					$statelist = array();
					foreach ( $states as $state ) {
						if ( strpos( $state, '---' ) !== false ) {
							$statelist[] = mosHTML::makeOption( '" disabled="disabled', $state );
						} else {
							$statelist[] = mosHTML::makeOption( $state, $state );
						}
					}

					$var['params']['lists']['billState'] = mosHTML::selectList( $statelist, 'billState', 'size="1"', 'value', 'text', $vcontent );
					$var['params']['billState'] = array( 'list', _AEC_USERFORM_BILLSTATEPROV_NAME, $vcontent );
					break;
				case 'zip':
					$var['params']['billZip'] = array( 'inputC', _AEC_USERFORM_BILLZIP_NAME, _AEC_USERFORM_BILLZIP_NAME, $vcontent );
					break;
				case 'country_list':
					$countries = array(  'US', 'AL', 'DZ', 'AD', 'AO', 'AI', 'AG', 'AR', 'AM', 'AW',
										'AU', 'AT', 'AZ', 'BS', 'BH', 'BB', 'BE', 'BZ', 'BJ', 'BM',
										'BT', 'BO', 'BA', 'BW', 'BR', 'VG', 'BN', 'BG', 'BF', 'BI',
										'KH', 'CA', 'CV', 'KY', 'TD', 'CL', 'C2', 'CO', 'KM', 'CK',
										'CR', 'HR', 'CY', 'CZ', 'CD', 'DK', 'DJ', 'DM', 'DO', 'EC',
										'SV', 'ER', 'EE', 'ET', 'FK', 'FO', 'FM', 'FJ', 'FI', 'FR',
										'GF', 'PF', 'GA', 'GM', 'DE', 'GI', 'GR', 'GL', 'GD', 'GP',
										'GT', 'GN', 'GW', 'GY', 'HN', 'HK', 'HU', 'IS', 'IN', 'ID',
										'IE', 'IL', 'IT', 'JM', 'JP', 'JO', 'KZ', 'KE', 'KI', 'KW',
										'KG', 'LA', 'LV', 'LS', 'LI', 'LT', 'LU', 'MG', 'MW', 'MY',
										'MV', 'ML', 'MT', 'MH', 'MQ', 'MR', 'MU', 'YT', 'MX', 'MN',
										'MS', 'MA', 'MZ', 'NA', 'NR', 'NP', 'NL', 'AN', 'NC', 'NZ',
										'NI', 'NE', 'NU', 'NF', 'NO', 'OM', 'PW', 'PA', 'PG', 'PE',
										'PH', 'PN', 'PL', 'PT', 'QA', 'CG', 'RE', 'RO', 'RU', 'RW',
										'VC', 'WS', 'SM', 'ST', 'SA', 'SN', 'SC', 'SL', 'SG', 'SK',
										'SI', 'SB', 'SO', 'ZA', 'KR', 'ES', 'LK', 'SH', 'KN', 'LC',
										'PM', 'SR', 'SJ', 'SZ', 'SE', 'CH', 'TW', 'TJ', 'TZ', 'TH',
										'TG', 'TO', 'TT', 'TN', 'TR', 'TM', 'TC', 'TV', 'UG', 'UA',
										'AE', 'GB', 'UY', 'VU', 'VA', 'VE', 'VN', 'WF', 'YE', 'ZM'
										);

					$countrylist[] = mosHTML::makeOption( '" disabled="disabled', COUNTRYCODE_SELECT );

					$countrylist = array();
					foreach ( $countries as $country ) {
						$cname = constant( 'COUNTRYCODE_' . $country );

						if ( $vcontent == $cname ) {
							$vcontent = $country;
						}

						$countrylist[] = mosHTML::makeOption( $country, $cname );
					}

					$var['params']['lists']['billCountry'] = mosHTML::selectList( $countrylist, 'billCountry', 'size="1"', 'value', 'text', $vcontent );
					$var['params']['billCountry'] = array( 'list', _AEC_USERFORM_BILLCOUNTRY_NAME, $vcontent );
					break;
				case 'country':
					$var['params']['billCountry'] = array( 'inputC', _AEC_USERFORM_BILLCOUNTRY_NAME, _AEC_USERFORM_BILLCOUNTRY_NAME, $vcontent );
					break;
				case 'phone':
					$var['params']['billPhone'] = array( 'inputC', _AEC_USERFORM_BILLPHONE_NAME, _AEC_USERFORM_BILLPHONE_NAME, $vcontent );
					break;
				case 'fax':
					$var['params']['billFax'] = array( 'inputC', _AEC_USERFORM_BILLFAX_NAME, _AEC_USERFORM_BILLPHONE_NAME, $vcontent );
					break;
				case 'company':
					$var['params']['billCompany'] = array( 'inputC', _AEC_USERFORM_BILLCOMPANY_NAME, _AEC_USERFORM_BILLCOMPANY_NAME, $vcontent );
					break;
			}
		}

		return $var;
	}

	function checkoutProcess( $request )
	{
		global $database;

		// Create the xml string
		$xml = $this->createRequestXML( $request );

		// Transmit xml to server
		$response = $this->transmitRequestXML( $xml, $request );

		if ( empty( $response['invoice'] ) ) {
			$response['invoice'] = $request->invoice->invoice_number;
		}

		if ( $request->invoice->invoice_number != $response['invoice'] ) {
			$request->invoice = new Invoice( $database );
			$request->invoice->loadInvoiceNumber( $response['invoice'] );
		}

		if ( !empty( $response['error'] ) ) {
			return $response;
		}

		if ( $response != false ) {
			if ( isset( $response['raw'] ) ) {
				$responsestring = $response['raw'];
				unset( $response['raw'] );
			} else {
				$responsestring = '';
			}

			$request->invoice->processorResponse( $request->parent, $response, $responsestring, true );
		} else {
			return false;
		}
	}

	function XMLtoArray( $xml )
	{
		if (!($xml->children())) {
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

}

class PROFILEprocessor extends XMLprocessor
{

	function ppProfileSelect( $var, $ppParams, $select=false, $btn=true )
	{
		$profiles = get_object_vars( $ppParams->paymentProfiles );

		$var['params'][] = array( 'p', _AEC_USERFORM_BILLING_DETAILS_NAME, '' );

		if ( !empty( $profiles ) ) {
			// Single-Select Payment Option
			foreach ( $profiles as $pid => $pobj ) {
				$info = array();

				$info_array = get_object_vars( $pobj->profilehash );

				foreach ( $info_array as $iak => $iav ) {
					if ( !empty( $iav ) ) {
						$info[] = $iav;
					}
				}

				if ( $ppParams->paymentprofileid == $pid ) {
					$text = '<strong>' . implode( '<br />', $info ) . '</strong>';
				} else {
					$text = implode( '<br />', $info );
				}

				$var['params'][] = array( 'radio', 'payprofileselect', $pid, $ppParams->paymentprofileid, $text );
			}

			if ( count( $profiles ) < 10 ) {
				$var['params'][] = array( 'radio', 'payprofileselect', "new", $ppParams->paymentprofileid, 'create new profile' );
			}

			if ( $btn ) {
				$var['params']['edit_payprofile'] = array( 'submit', '', '', ( $select ? _BUTTON_SELECT : _BUTTON_EDIT ) );
			}
		}

		return $var;
	}

	function ProfileAdd( $request, $profileid )
	{
		$ppParams = new stdClass();

		$ppParams->profileid			= $profileid;

		$ppParams->paymentprofileid		= '';
		$ppParams->paymentProfiles		= new stdClass();

		$request->metaUser->meta->setProcessorParams( $request->parent->id, $ppParams );

		return $ppParams;
	}

	function payProfileAdd( $request, $profileid, $details, $ppParams )
	{
		$profiles = get_object_vars( $ppParams->paymentProfiles );
		$pointer = count( $profiles );

		$data = new stdClass();
		$data->profileid = $profileid;
		$data->profilehash = $this->payProfileHash( $details );

		$ppParams->paymentProfiles->$pointer = $data;

		$ppParams->paymentprofileid = $pointer;

		$request->metaUser->meta->setProcessorParams( $request->parent->id, $ppParams );

		return $ppParams;
	}

	function payProfileUpdate( $request, $profileid, $details, $ppParams )
	{
		$ppParams->paymentProfiles->$profileid->profilehash = $this->payProfileHash( $details );

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

}

class POSTprocessor extends processor
{
	function checkoutAction( $request )
	{
		$var = $this->createGatewayLink( $request );

		if ( !empty( $this->settings['customparams'] ) ) {
			$var = $this->customParams( $this->settings['customparams'], $var, $request );
		}

		if ( isset( $var['_aec_checkout_onclick'] ) ) {
			$onclick = ' onclick="' . $var['_aec_checkout_onclick'] . '"';
			unset( $var['_aec_checkout_onclick'] );
		} else {
			$onclick = '';
		}

		$return = '<form action="' . $var['post_url'] . '" method="post">' . "\n";
		unset( $var['post_url'] );

		foreach ( $var as $key => $value ) {
			$return .= '<input type="hidden" name="' . $key . '" value="' . $value . '" />' . "\n";
		}

		$return .= '<input type="submit" class="button"' . $onclick . ' value="' . _BUTTON_CHECKOUT . '" />' . "\n";
		$return .= '</form>' . "\n";

		return $return;
	}
}

class GETprocessor extends processor
{
	function checkoutAction( $request )
	{
		$var = $this->createGatewayLink( $request );

		if ( !empty( $this->settings['customparams'] ) ) {
			$var = $this->customParams( $this->settings['customparams'], $var, $request );
		}

		if ( isset( $var['_aec_checkout_onclick'] ) ) {
			$onclick = ' onclick="' . $var['_aec_checkout_onclick'] . '"';
			unset( $var['_aec_checkout_onclick'] );
		} else {
			$onclick = '';
		}

		$return = '<form action="' . $var['post_url'] . '" method="get">' . "\n";
		unset( $var['post_url'] );

		foreach ( $var as $key => $value ) {
			$return .= '<input type="hidden" name="' . $key . '" value="' . $value . '" />' . "\n";
		}

		$return .= '<input type="submit" class="button"' . $onclick . ' value="' . _BUTTON_CHECKOUT . '" />' . "\n";
		$return .= '</form>' . "\n";

		return $return;
	}
}

class URLprocessor extends processor
{
	function checkoutAction( $request )
	{
		global $mainframe;

		$var = $this->createGatewayLink( $request );

		if ( isset( $var['_aec_html_head'] ) ) {
			if ( is_array( $var['_aec_html_head'] ) ) {
				foreach ( $var['_aec_html_head'] as $content ) {
					$mainframe->addCustomHeadTag( $content );
				}
			} else {
				$mainframe->addCustomHeadTag( $var['_aec_html_head'] );
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

		$vars = array();
		if ( !empty( $var ) ) {
			foreach ( $var as $key => $value ) {
				$vars[] .= $key . '=' . $value;
			}

			$return .= implode( '&amp;', $vars );
		}

		$return .= '"' . $onclick . ' class="linkbutton" >' . _BUTTON_CHECKOUT . '</a>' . "\n";

		return $return;
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

	function fullSettingsArray( $params, $params_values, $lists = array(), $settings = array() ) {
		$this->params			= $params;
		$this->params_values	= $params_values;
		$this->lists			= $lists;
		$this->settings			= $settings;

		foreach ( $this->params as $name => $content ) {

			// $content[0] = type
			// $content[1] = value
			// $content[2] = disabled?
			// $content[3] = set name
			// $content[4] = set description

			if ( isset( $this->params_values[$name] ) ) {
				$value = $this->params_values[$name];
			} else {
				if ( isset( $content[3] ) ) {
					$value						= $content[3];
					$this->params_values[$name] = $content[3];
				} elseif ( isset( $content[1] ) && !isset( $content[2] ) ) {
					$value						= $content[1];
					$this->params_values[$name] = $content[1];
				} else {
					$value						= '';
					$this->params_values[$name] = '';
				}
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

			if ( !isset( $content[2] ) || ( !$content[2] && ( $content[2] !== '' ) ) ) {
				// Create constant names
				$constant_generic	= '_' . strtoupper($this->area)
										. '_' . strtoupper( $this->original_subarea )
										. '_' . strtoupper( $name );
				$constant			= '_' . strtoupper( $this->area )
										. '_' . strtoupper( $this->subarea )
										. '_' . strtoupper( $name );
				$constantname		= $constant . '_NAME';
				$constantdesc		= $constant . '_DESC';

				// If the constantname does not exists, try a generic name or insert an error
				if ( defined( $constantname ) ) {
					$info_name = constant( $constantname );
				} else {
					$genericname = $constant_generic . '_NAME';
					if ( defined( $genericname ) ) {
						$info_name = constant( $genericname );
					} else {
						$info_name = sprintf( _AEC_CMN_LANG_CONSTANT_IS_MISSING, $constantname );
					}
				}

				// If the constantname does not exists, try a generic name or insert an error
				if ( defined( $constantdesc ) ) {
					$info_desc = constant( $constantdesc );
				} else {
					$genericdesc = $constant_generic . '_DESC';
					if ( defined( $genericname ) ) {
						$info_desc = constant( $genericdesc );
					} else {
						$info_desc = sprintf( _AEC_CMN_LANG_CONSTANT_IS_MISSING, $constantdesc );
					}
				}
			} else {
				$info_name = $content[1];
				$info_desc = $content[2];
			}

			$this->settings[$name] = array($type, $info_name, $info_desc, $value);
		}
	}

	function remap_subarea_change( $name, $value )
	{
		$this->subarea = $value;
		return 'DEL';
	}

	function remap_list_yesno( $name, $value )
	{
		$this->lists[$name] = mosHTML::yesnoSelectList( $name, '', $value );
		return 'list';
	}

	function remap_list_yesnoinherit( $name, $value )
	{
		$this->lists[$name] = mosHTML::yesnoSelectList( $name, '', $value );

		$arr = array(
		mosHTML::makeOption( '0', _AEC_CMN_NO ),
		mosHTML::makeOption( '1', _AEC_CMN_YES ),
		mosHTML::makeOption( '1', _AEC_CMN_INHERIT ),
		);

		$this->lists[$name] = mosHTML::selectList( $arr, $name, '', 'value', 'text', $value );
		return 'list';
	}

	function remap_list_recurring( $name, $value )
	{
		$recurring[] = mosHTML::makeOption( 0, _AEC_SELECT_RECURRING_NO );
		$recurring[] = mosHTML::makeOption( 1, _AEC_SELECT_RECURRING_YES );
		$recurring[] = mosHTML::makeOption( 2, _AEC_SELECT_RECURRING_BOTH );

		$this->lists[$name] = mosHTML::selectList( $recurring, $name, 'size="3"', 'value', 'text', $value );

		return 'list';
	}

	function remap_list_date( $name, $value )
	{
		$this->lists[$name] = '<input class="text_area" type="text" name="' . $name . '" id="' . $name . '" size="19" maxlength="19" value="' . $value . '"/>'
		.'<input type="reset" name="reset" class="button" onClick="return showCalendar(\'' . $name . '\', \'y-mm-dd\');" value="..." />';
		return 'list';
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

	function createSettingsParticle( $name )
	{

		$row	= $this->rows[$name];
		$type	= $row[0];

		$return = '';

		if ( isset( $row[2] ) ) {
			if ( isset( $row[3] ) ) {
				$value = $row[3];
			} else {
				$value = '';
			}

			if ( !empty( $row[1] ) && !empty( $row[2] ) ) {
				if (  aecJoomla15check() ) {
					$return = '<div class="setting_desc">';
					$return .= '<span class="editlinktip hasTip" title="';
					$return .= htmlentities( $row[1], ENT_QUOTES ) . ( ( strpos( $row[1], ':' ) === false ) ? ':' : '' ) . ':' . htmlentities( $row[2], ENT_QUOTES );
					$return .= '">' . $this->Icon( 'help.png') . htmlentities( $row[1], ENT_QUOTES ) . ( ( strpos( $row[1], ':' ) === false ) ? ':' : '' ) . '</span>';
					$return .= '</div>';
				} else {
					$return = '<div class="setting_desc">' . $this->ToolTip( $row[2], $row[1] ) . $row[1] . '</div>';
				}
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
				$return .= '<div class="setting_form">';
				$return .= '<input name="' . $name . '" type="text" size="4" value="' . $value . '" />';
				$return .= '</div>';
				break;
			case 'inputB':
				$return .= '<div class="setting_form">';
				$return .= '<input class="inputbox" type="text" name="' . $name . '" size="8" value="' . $value . '" />';
				$return .= '</div>';
				break;
			case 'inputC':
				$return .= '<div class="setting_form">';
				$return .= '<input type="text" size="20" name="' . $name . '" class="inputbox" value="' . $value . '" />';
				$return .= '</div>';
				break;
			case 'inputD':
				$return .= '<div class="setting_form">';
				$return .= '<textarea cols="50" rows="5" name="' . $name . '" />' . $value . '</textarea>';
				$return .= '</div>';
				break;
			case 'inputE':
				$return .= '<div class="setting_form">';
				$return .= '<textarea style="width:520px" cols="450" rows="1" name="' . $name . '" />' . $value . '</textarea>';
				$return .= '</div>';
				break;
			case 'checkbox':
				$return .= '<div class="setting_form">';
				$return .= '<input type="checkbox" name="' . $name . '" ' . ( $value ? 'checked="checked" ' : '' ) . '/>';
				$return .= '</div>';
				break;
			case 'editor':
				$return .= '<div class="setting_form">';
				$return .= '<div><div>' . editorArea( $name, $value, $name, '100%;', '250', '10', '60' ) . '</div></div>';
				$return .= '</div>';
				break;
			case 'list':
				$return .= '<div class="setting_form">';
				$return .= $this->lists[$name];
				$return .= '</div>';
				break;
			case 'accordion_start':
				if ( !isset( $this->accordions ) ) {
					$this->accordions = 1;
				} else {
					$this->accordions++;
				}

				// small accordion code
				$this->js[] = "window.addEvent('domready',function(){ var accordion" . $this->accordions . " = new Accordion($('accordion" . $this->accordions . "'), '#accordion" . $this->accordions . " h3.atStart', '#accordion" . $this->accordions . " div.atStart', {
					duration: 200, alwaysHide: true,
					onActive: function(toggler, element){
						var activeFX = new Fx.Styles(toggler, {duration: 300, transition: Fx.Transitions.Expo.easeOut});
						activeFX.start({ 'color':'#222', 'paddingLeft':'20px' });
						toggler.setStyle('font-weight', 'bold');
					},
					onBackground: function(toggler, element){
						var backFX = new Fx.Styles(toggler, {duration: 300, transition: Fx.Transitions.Expo.easeOut});
						backFX.start({ 'color':'#444', 'paddingLeft':'10px' });
						toggler.setStyle('font-weight', 'normal');
					} } ); });";
				$return = '<div id="accordion' . $this->accordions . '"' . ( !empty( $value ) ? ('class="'.$value.'"') : 'accordion') . '>';
				break;
			case 'accordion_itemstart':
				$return = '<h3 class="aec_toggler atStart">' . $value . '</h3><div class="element atStart">';
				break;
			case 'div_end':
				$return = '</div>';
				break;
			case '2div_end':
				$return = '</div></div>';
				break;
			case 'userinfobox':
				$return = '<div style="position:relative;float:left;width:' . $value . '%;padding:4px;"><div class="userinfobox">';
				break;
			case 'fieldset':
				$return = '<div class="setting_form">' . "\n"
				. '<fieldset><legend>' . $row[1] . '</legend>' . "\n"
				. '<table cellpadding="1" cellspacing="1" border="0">' . "\n"
				. '<tr align="left" valign="middle" ><td>' . $row[2] . '</td></tr>' . "\n"
				. '</table>' . "\n"
				. '</fieldset>' . "\n"
				. '</div>'
				;
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

	function returnFull()
	{
		$return = '';
		foreach ( $this->rows as $rowname => $rowcontent ) {
			$return .= $this->createSettingsParticle( $rowname );
		}

		return $return;
	}

	function printFull()
	{
		foreach ( $this->rows as $rowname => $rowcontent ) {
			echo $this->createSettingsParticle( $rowname );
		}
	}

	function createFormParticle( $name, $row, $lists, $table=0 )
	{
		$return = '';
		if ( isset( $row[3] ) ) {
			$value = $row[3];
		} else {
			$value = '';
		}

		$return .= $table ? '<tr><td class="cleft">' : '<p>';

		if ( !empty( $row[1] ) ) {
			$return .= '<strong>' . $row[1] . ':</strong>';
		}

		$return .= $table ? '</td><td class="cright">' : ' ';

		$noappend = false;
		switch ( $row[0] ) {
			case 'submit':
				$return .= '<input type="submit" class="button" name="' . $name . '" value="' . $value . '" />' . "\n";
				break;
			case "inputA":
				$return .= '<input name="' . $name . '" type="text" size="4" maxlength="5" value="' . $value . '"/>';
				break;
			case "inputB":
				$return .= '<input class="inputbox" type="text" name="' . $name . '" size="2" maxlength="10" value="' . $value . '" />';
				break;
			case "inputC":
				$return .= '<input type="text" size="20" name="' . $name . '" class="inputbox" value="' . $value . '" />';
				break;
			case "inputD":
				$return .= '<textarea align="left" cols="60" rows="5" name="' . $name . '" />' . $value . '</textarea>';
				break;
			case 'radio':
				$return = '<tr><td class="cleft">';
				$return .= '<input type="radio" name="' . $row[1] . '"' . ( ( $row[3] == $row[2] ) ? ' checked="checked"' : '' ) . ' value="' . $row[2] . '" />';
				$return .= '</td><td class="cright">' . $row[4];
				break;
			case "list":
				$return .= $lists[$value ? $value : $name];
				break;
			case 'tabberstart':
				$return = '<tr><td colspan="2"><div id="myTabs">';
				break;
			case 'tabregisterstart':
				$return = '<ul class="mootabs_title">';
				break;
			case 'tabregister':
				$return = '<li title="' . $row[1] . '">' . $row[2] . '</li> ';
				break;
			case 'tabregisterend':
				$return = '</ul>';
				break;
			case 'tabstart':
				$return = '<div id="' . $row[1] . '" class="mootabs_panel"><table>';
				break;
			case 'tabend':
				$return = '</table></div>';
				break;
			case 'tabberend':
				$return = '</div></td></tr>';
				break;
			default:
				if ( !empty( $row[0] ) ) {
					$return .= '<' . $row[0] . '>' . $row[2] . '</' . $row[0] . '>';
				} elseif ( empty( $row[0] ) && empty( $row[2] ) ) {
					$return .= '<' . $row[1] . ' />';
				} else {
					$return .= $row[2];
				}
				break;
		}

		if ( strpos( $return, ($table ? '<tr><td class="cleft">' : '<p>') ) !== false ) {
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
		global $mosConfig_live_site;

		if ( $width ) {
			$width = ', WIDTH, \''.$width .'\'';
		}
		if ( $title ) {
			$title = ', CAPTION, \''.$title .'\'';
		}
		if ( !$text ) {
			$image 	= $mosConfig_live_site . '/administrator/components/com_acctexp/images/icons/'. $image;
			$text 	= '<img src="'. $image .'" border="0" alt=""/>';
		}
		$style = 'style="text-decoration: none; color: #586C79;"';
		if ( $href ) {
			$style = '';
		} else{
			$href = '#';
		}

		$mousover = 'return overlib(\''. htmlentities( $tooltip, ENT_QUOTES ) .'\''. $title .', BELOW, RIGHT'. $width .');';

		$tip = '';
		if ( $link ) {
			$tip .= '<a href="'. $href .'" onmouseover="'. $mousover .'" onmouseout="return nd();" '. $style .'>'. $text .'</a>';
		} else {
			$tip .= '<span onmouseover="'. $mousover .'" onmouseout="return nd();" '. $style .'>'. $text .'</span>';
		}

		return $tip . '&nbsp;';
	}

	/**
	 * displays an icon
	 * mic: corrected name
	 *
	 * @param 	string	$image	image name
	 * @param	string	$alt	optional alt/title text
	 * @return html string
	 */
	function Icon( $image = 'error.png', $alt = '' )
	{
		global $mosConfig_live_site;

		if ( !$alt ) {
			$name	= explode( '.', $image );
			$alt	= $name[0];
		}
		$image 	= $mosConfig_live_site . '/administrator/components/com_acctexp/images/icons/'. $image;

		return '<img src="'. $image .'" border="0" alt="' . $alt . '" title="' . $alt . '" class="aec_icon" />';
	}

}

class ItemGroupHandler
{
	function getGroups()
	{
		global $database;

		$query = 'SELECT id'
				. ' FROM #__acctexp_itemgroups'
				;
		$database->setQuery( $query );
		return $database->loadResultArray();
	}

	function getTree()
	{
		$tree = ItemGroupHandler::resolveTreeItem( 1 );

		$list = array();
		return ItemGroupHandler::indentList( $tree, $list );
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
		global $database;

		$query = 'SELECT name'
				. ' FROM #__acctexp_itemgroups'
				. ' WHERE `id` = \'' . $groupid . '\''
				;
		$database->setQuery( $query );
		return $database->loadResult();
	}

	function groupColor( $groupid )
	{
		global $database;

		$group = new ItemGroup( $database );
		$group->load( $groupid );

		return $group->params['color'];
	}

	function groupIcon( $groupid )
	{
		global $database;

		$group = new ItemGroup( $database );
		$group->load( $groupid );

		return $group->params['icon'];
	}

	function parentGroups( $item_id, $type='item' )
	{
		global $database;

		$query = 'SELECT group_id'
				. ' FROM #__acctexp_itemxgroup'
				. ' WHERE `type` = \'' . $type . '\''
				. ' AND `item_id` = \'' . $item_id . '\''
				;
		$database->setQuery( $query );
		return $database->loadResultArray();
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

	function setChild( $item_id, $group_id, $type='item' )
	{
		global $database;

		$ig = new itemXgroup( $database );
		return $ig->createNew( $type, $item_id, $group_id );
	}

	function setChildren( $item_id, $groups, $type='item' )
	{
		global $database;

		foreach ( $groups as $group_id ) {
			// Check bogus assignments
			if ( $type == 'group' ) {
				// Don't let a group be assigned to itself
				if ( ( $group_id == $item_id ) ) {
					continue;
				}

				$children = ItemGroupHandler::getChildren( $group_id, 'group' );

				// Don't allow circular assignment
				if ( in_array( $item_id, $children ) ) {
					continue;
				}
			}

			$ig = new itemXgroup( $database );

			if ( !$ig->createNew( $type, $group_id, $item_id ) ) {
				return false;
			}
		}

		return true;
	}

	function getChildren( $groups, $type )
	{
		global $database;

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

		$database->setQuery( $query );
		$result = $database->loadResultArray();

		if ( !empty( $result ) ) {
		// Order results
			$query = 'SELECT id'
					. ' FROM #__acctexp_' . ( ( $type == 'group' ) ? 'itemgroups' : 'plans' )
					. ' WHERE id IN (' . implode( ',', $result ) . ')'
					. ' ORDER BY `ordering`'
					;
			$database->setQuery( $query );

			return $database->loadResultArray();
		} else {
			return $result;
		}
	}

	function checkParentRestrictions( $item, $type, $metaUser )
	{
		$parents = ItemGroupHandler::parentGroups( $item->id, $type );

		if ( !empty( $parents ) ) {
			foreach ( $parents as $parent ) {
				$g = new ItemGroup( $item->_db );
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
		$children = ItemGroupHandler::getChildren( $group->id, 'item' );
		if ( !empty( $children ) ) {
			$i = 0;
			foreach( $children as $itemid ) {
				$plan = new SubscriptionPlan( $group->_db );
				$plan->load( $itemid );

				if ( $plan->checkVisibility( $metaUser ) ) {
					return true;
				}
			}
		}

		$groups = ItemGroupHandler::getChildren( $group->id, 'group' );
		if ( !empty( $groups ) ) {
			foreach ( $groups as $groupid ) {
				$g = new ItemGroup( $group->_db );
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

	function getTotalAllowedChildItems( $gids, $metaUser, $list=array() )
	{
		global $database;

		$groups = ItemGroupHandler::getChildren( $gids, 'group' );

		foreach ( $groups as $groupid ) {
			$group = new ItemGroup( $database );
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

		$items = ItemGroupHandler::getChildren( $gids, 'item' );

		$check = count($list);

		$i = 0;
		foreach( $items as $itemid ) {
			$plan = new SubscriptionPlan( $database );
			$plan->load( $itemid );

			if ( !$plan->checkVisibility( $metaUser ) ) {
				continue;
			}

			$list[] = ItemGroupHandler::getItemListItem( $plan );
			$i++;
		}

		return $list;
	}

	function getGroupListItem( $group )
	{
		return array(	'type'	=> 'group',
						'id'	=> $group->id,
						'name'	=> $group->getProperty( 'name' ),
						'desc'	=> $group->getProperty( 'desc' )
						);
	}

	function getItemListItem( $plan )
	{
		return array(	'type'		=> 'item',
						'id'		=> $plan->id,
						'plan'		=> $plan,
						'name'		=> $plan->getProperty( 'name' ),
						'desc'		=> $plan->getProperty( 'desc' ),
						'ordering'	=> $plan->ordering,
						'lifetime'	=> $plan->params['lifetime']
						);
	}

	function removeChildren( $item_id, $groups, $type='item' )
	{
		global $database;

		$query = 'DELETE'
				. ' FROM #__acctexp_itemxgroup'
				. ' WHERE `type` = \'' . $type . '\''
				. ' AND `item_id` = \'' . $item_id . '\''
				. ' AND `group_id` IN (' . implode( ',', $groups ) . ')'
				;
		$database->setQuery( $query );
		return $database->loadResultArray();
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
		$this->mosDBTable( '#__acctexp_itemgroups', 'id', $db );
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

	function savePOSTsettings( $post )
	{
		global $database;

		// Fake knowing the planid if is zero. TODO: This needs to replaced with something better later on!
		if ( !empty( $post['id'] ) ) {
			$groupid = $post['id'];
		} else {
			$groupid = $this->getMax() + 1;
		}

		if ( isset( $post['id'] ) ) {
			unset( $post['id'] );
		}

		if ( !empty( $post['add_group'] ) ) {
			ItemGroupHandler::setChildren( $post['add_group'], array( $groupid ), 'group' );
		}

		if ( $this->id == 1 ) {
			$post['active']				= 1;
			$post['visible']			= 1;
			$post['name']				= _AEC_INST_ROOT_GROUP_NAME;
			$post['desc']				= _AEC_INST_ROOT_GROUP_DESC;
			$post['reveal_child_items']	= 1;
		}

		// Filter out fixed variables
		$fixed = array( 'active', 'visible', 'name', 'desc' );

		foreach ( $fixed as $varname ) {
			$this->$varname = $post[$varname];
			unset( $post[$varname] );
		}

		// Filter out params
		$fixed = array( 'color', 'icon', 'reveal_child_items', 'symlink' );

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
			if ( strpos( $varname, 'group_delete_' ) !== false ) {
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
		global $database;

		if ( $this->id == 1 ) {
			return false;
		}

		// Delete possible item connections
		$query = 'DELETE FROM #__acctexp_itemxgroup'
				. ' WHERE `group_id` = \'' . $this->id . '\''
				. ' AND `type` = \'item\''
				;
		$database->setQuery( $query );
		if ( !$database->query() ) {
			echo "<script> alert('".$database->getErrorMsg()."'); window.history.go(-1); </script>\n";
			exit();
		}

		// Delete possible group connections
		$query = 'DELETE FROM #__acctexp_itemxgroup'
				. ' WHERE `group_id` = \'' . $this->id . '\''
				. ' AND `type` = \'group\''
				;
		$database->setQuery( $query );
		if ( !$database->query() ) {
			echo "<script> alert('".$database->getErrorMsg()."'); window.history.go(-1); </script>\n";
			exit();
		}

		return parent::delete();
	}
}

class itemXgroup extends mosDBTable
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
		$this->mosDBTable( '#__acctexp_itemxgroup', 'id', $db );
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

class ItemRelationHandler
{
	/**
	 * Well well, we have lots of similar stuff going on
	 * maybe we need a root relation handler that others can bind to
	 * in any case, the ItemRelationHandler should do stuff like
	 * keeping track of similar or equal plans or groups
	 */
}

class SubscriptionPlanHandler
{
	function getPlanUserlist( $planid )
	{
		global $database;

		$query = 'SELECT `userid`'
				. ' FROM #__acctexp_subscr'
				. ' WHERE `plan` = \'' . $database->getEscaped( $planid ) . '\' AND ( `status` = \'Active\' OR `status` = \'Trial\' ) '
				;
		$database->setQuery( $query );

		return $database->loadResultArray();
	}

	function PlanStatus( $planid )
	{
		global $database;

		$query = 'SELECT `active`'
				. ' FROM #__acctexp_plans'
				. ' WHERE `id` = \'' . $database->getEscaped( $planid ) . '\''
				;
		$database->setQuery( $query );

		return $database->loadResult();
	}

	function listPlans()
	{
		global $database;

		$query = 'SELECT id'
				. ' FROM #__acctexp_plans'
				;
		$database->setQuery( $query );

		return $database->loadResultArray();
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
		$this->mosDBTable( '#__acctexp_plans', 'id', $db );
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

		$restrictions = $this->getRestrictionsArray();

		return aecRestrictionHelper::checkRestriction( $restrictions, $metaUser );
	}

	function applyPlan( $userid, $processor = 'none', $silent = 0, $multiplicator = 1, $invoice = null, $tempparams = null )
	{
		global $database, $mainframe, $mosConfig_offset, $aecConfig;

		$forcelifetime = false;

		if ( is_string( $multiplicator ) ) {
			if ( strcmp( $multiplicator, 'lifetime' ) === 0 ) {
				$forcelifetime = true;
			}
		} elseif ( is_int( $multiplicator ) && ( $multiplicator < 1 ) ) {
			$multiplicator = 1;
		}

		if ( $userid ) {
			$metaUser = new metaUser( $userid );

			if ( !isset( $this->params['make_primary'] ) ) {
				$this->params['make_primary'] = 1;
			}

			if ( !$metaUser->hasSubscription || empty( $this->params['make_primary'] ) ) {
				$metaUser->establishFocus( $this, $processor );

				$is_pending	= true;
				$is_trial	= false;
			} else {
				$is_pending	= ( strcmp( $metaUser->focusSubscription->status, 'Pending' ) === 0 );
				$is_trial	= ( strcmp( $metaUser->focusSubscription->status, 'Trial' ) === 0 );
			}

			$comparison		= $this->doPlanComparison( $metaUser->focusSubscription );
			if ( empty( $comparison['renew'] ) ) {
				$renew = 0;
			} else {
				$renew = 1;
			}

			$lifetime		= $metaUser->focusSubscription->lifetime;

			if ( ( $comparison['total_comparison'] === false ) || $is_pending ) {
				// If user is using global trial period he still can use the trial period of a plan
				if ( ( $this->params['trial_period'] > 0 ) && !$is_trial ) {
					$trial		= true;
					$value		= $this->params['trial_period'];
					$perunit	= $this->params['trial_periodunit'];
					$this->params['lifetime']	= 0; // We are entering the trial period. The lifetime will come at the renew.
				} else {
					$trial		= false;
					$value		= $this->params['full_period'];
					$perunit	= $this->params['full_periodunit'];
				}
			} elseif ( !$is_pending ) {
				$trial		= false;
				$value		= $this->params['full_period'];
				$perunit	= $this->params['full_periodunit'];
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
				$metaUser->focusSubscription->signup_date = date( 'Y-m-d H:i:s', time() + $mosConfig_offset*3600 );
				if ( $this->params['trial_period'] > 0 && !$is_trial ) {
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
				$renew = 1;
			}

			$metaUser->focusSubscription->status = $status;
			$metaUser->focusSubscription->plan = $this->id;

			$metaUser->meta->addPlanID( $this->id );

			$metaUser->focusSubscription->lastpay_date = date( 'Y-m-d H:i:s', time() + $mosConfig_offset*3600 );
			$metaUser->focusSubscription->type = $processor;

			// Clear parameters
			$metaUser->focusSubscription->params = array();

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
				$metaUser->focusSubscription->recurring = $pp->is_recurring();
			} else {
				$metaUser->focusSubscription->recurring = 0;
			}
		}

		$result = $this->triggerMIs( 'action', $metaUser, null, $invoice, false, $silent );

		if ( $result === false ) {
			return false;
		}

		if ( $userid ) {
			if ( $this->params['gid_enabled'] ) {
				$metaUser->instantGIDchange($this->params['gid']);
			}

			$metaUser->focusSubscription->storeload();
		}

		if ( !( $silent || $aecConfig->cfg['noemails'] ) ) {
			if ( ( $this->id !== $aecConfig->cfg['entry_plan'] ) ) {
				$metaUser->focusSubscription->sendEmailRegistered( $renew );
			}
		}

		return $renew;
	}

	function SubscriptionAmount( $recurring, $user_subscription, $metaUser=false )
	{
		if ( is_object( $user_subscription ) ) {
			$comparison				= $this->doPlanComparison( $user_subscription );
			$plans_comparison		= $comparison['comparison'];
			$plans_comparison_total	= $comparison['total_comparison'];
			$renew					= $comparison['renew'] ? 1 : 0;
			$is_trial				= (strcmp($user_subscription->status, 'Trial') === 0);
		} else {
			$plans_comparison		= false;
			$plans_comparison_total	= false;
			$renew					= 0;
			$is_trial				= 0;
		}

		$var		= null;
		$free_trial = 0;

		if ( !empty( $recurring ) ) {
			$amount = array();

			// Only Allow a Trial when the User is coming from a different PlanGroup or is new
			if ( ( $plans_comparison === false ) && ( $plans_comparison_total === false ) && !empty( $this->params['trial_period'] ) ) {
				if ( $this->params['trial_free'] ) {
					$amount['amount1'] = '0.00';
					$free_trial = 1;
				} else {
					$amount['amount1']	= $this->params['trial_amount'];
				}
				$amount['period1']	= $this->params['trial_period'];
				$amount['unit1']	= $this->params['trial_periodunit'];
			}

			if ( $this->params['full_free'] ) {
				$amount['amount3'] = '0.00';
			} else {
				$amount['amount3']	= $this->params['full_amount'];
			}

			$amount['period3']		= $this->params['full_period'];
			$amount['unit3']		= $this->params['full_periodunit'];
		} else {
			if ( empty( $this->params['trial_period'] ) && $this->params['full_free'] && $this->params['trial_free'] ) {
				$amount = '0.00';
			} else {
				if ( ( $plans_comparison === false ) && ( $plans_comparison_total === false ) ) {
					if ( !$is_trial && !empty($this->params['trial_period']) ) {
						if ( $this->params['trial_free'] ) {
							$amount = '0.00';
							$free_trial = 1;
						} else {
							$amount = $this->params['trial_amount'];
						}
					} else {
						if ( $this->params['full_free'] ) {
							$amount = '0.00';
						} else {
							$amount = $this->params['full_amount'];
						}
					}
				} else {
					if ( $this->params['full_free'] ) {
						$amount = '0.00';
					} else {
						$amount = $this->params['full_amount'];
					}
				}
			}
		}

		if ( !empty( $this->micro_integrations ) && ( is_object( $user_subscription ) || is_object( $metaUser ) ) ) {
			$mih = new microIntegrationHandler();

			if ( !is_object( $metaUser ) ) {
				$metaUser = new metaUser( $user_subscription->userid );
			}

			$mih->applyMIs( $amount, $this, $metaUser );
		}

		$return_url	= AECToolbox::deadsureURL( 'index.php?option=com_acctexp&amp;task=thanks&amp;renew=' . $renew );

		$return['return_url']	= $return_url;
		$return['amount']		= $amount;
		$return['free_trial']	= $free_trial;

		return $return;
	}

	function termsParamsRequest( $recurring, $metaUser )
	{
		if ( !empty( $this->micro_integrations ) ) {
			$mih = new microIntegrationHandler();

			if ( is_object( $metaUser->objSubscription ) ) {
				$comparison				= $this->doPlanComparison( $metaUser->objSubscription );
				$plans_comparison		= $comparison['comparison'];
				$plans_comparison_total	= $comparison['total_comparison'];
				$renew					= $comparison['renew'] ? 1 : 0;
				$is_trial				= ( strcmp( $metaUser->objSubscription->status, 'Trial' ) === 0 );
			} else {
				$plans_comparison		= false;
				$plans_comparison_total	= false;
				$renew					= 0;
				$is_trial				= 0;
			}

			$var		= null;
			$free_trial = 0;

			if ( !empty( $recurring ) ) {
				$amount = array();

				// Only Allow a Trial when the User is coming from a different PlanGroup or is new
				if ( ( $plans_comparison === false ) && ( $plans_comparison_total === false ) && !empty( $this->params['trial_period'] ) ) {
					if ( !$this->params['trial_free'] ) {
						$mih->applyMIs( $this->params['trial_amount'], $this, $metaUser );
					}
				}

				if ( !$this->params['full_free'] ) {
					$mih->applyMIs( $this->params['full_amount'], $this, $metaUser );
				}
			} else {
				if ( !$this->params['trial_period'] && $this->params['full_free'] && $this->params['trial_free'] ) {
					// Huh?
				} else {
					if ( ( $plans_comparison === false ) && ( $plans_comparison_total === false ) ) {
						if ( !$is_trial && !empty($this->params['trial_period']) ) {
							if ( !$this->params['trial_free'] ) {
								$mih->applyMIs( $this->params['trial_amount'], $this, $metaUser );
							}
						} else {
							if ( !$this->params['full_free'] ) {
								$mih->applyMIs( $this->params['full_amount'], $this, $metaUser );
							}
						}
					} else {
						if ( !$this->params['full_free'] ) {
							$mih->applyMIs( $this->params['full_amount'], $this, $metaUser );
						}
					}
				}
			}
		}

		return $this->params;
	}

	function doPlanComparison( $user_subscription )
	{
		global $database;

		$return['total_comparison']	= false;
		$return['comparison']		= false;
		$return['renew']			= 0;

		if ( !empty( $user_subscription->plan ) ) {
			$return['renew'] = 1;

			if ( !empty( $user_subscription->used_plans ) ) {
				$plans_comparison	= false;

				if ( is_array( $user_subscription->used_plans ) ) {
					foreach ( $user_subscription->used_plans as $planid ) {
						if ( $planid ) {
							if ( isset( $planid[0] ) ){
								if ( empty( $planid[0] ) ) {
									continue;
								} else {
									$pid = $planid[0];
								}
							} else {
								continue;
							}

							$used_subscription = new SubscriptionPlan( $database );
							$used_subscription->load( $pid );

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

			$last_subscription = new SubscriptionPlan( $database );
			$last_subscription->load( $user_subscription->plan );

			if ( $this->id === $last_subscription->id ) {
				$return['comparison'] = 2;
			} else {
				$return['comparison'] = $this->compareToPlan( $last_subscription );
			}
		}

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

	function getMIformParams()
	{
		$mis = $this->getMicroIntegrations();

		if ( !empty( $mis ) ) {
			global $database;

			$params = array();
			$lists = array();
			foreach ( $mis as $mi_id ) {

				$mi = new MicroIntegration( $database );
				$mi->load( $mi_id );

				if ( !$mi->callIntegration() ) {
					continue;
				}

				$mi_form = $mi->getMIform( $this );

				if ( !empty( $mi_form ) ) {
					if ( !empty( $mi_form['lists'] ) ) {
						foreach ( $mi_form['lists'] as $lname => $lcontent ) {
							$tempname = 'mi_'.$mi->id.'_'.$lname;
							$lists[$tempname] = str_replace( $lname, $tempname, $lcontent );
						}

						unset( $mi_form['lists'] );
					}

					$params['mi_'.$mi->id.'_remap_area'] = array( 'subarea_change', $mi->class_name );

					foreach ( $mi_form as $fname => $fcontent ) {
						$params['mi_'.$mi->id.'_'.$fname] = $fcontent;
					}
				}
			}

			$params['lists'] = $lists;

			return $params;
		} else {
			return false;
		}
	}

	function getMIforms()
	{
		$params = $this->getMIformParams();

		if ( empty( $params ) ) {
			return false;
		} else {
			$lists = $params['lists'];
			unset( $params['lists'] );

			$settings = new aecSettings ( 'mi', 'frontend_forms' );
			$settings->fullSettingsArray( $params, array(), $lists ) ;

			$aecHTML = new aecHTML( $settings->settings, $settings->lists );
			return $aecHTML->returnFull();
		}
	}

	function getMicroIntegrations()
	{
		if ( !empty( $this->micro_integrations ) ) {
			$query = 'SELECT `id`'
					. ' FROM #__acctexp_microintegrations'
					. ' WHERE `id` IN (' . $this->_db->getEscaped( implode( ',', $this->micro_integrations ) ) . ')'
					. ' ORDER BY `ordering` ASC'
					;
			$this->_db->setQuery( $query );
			$mi_list = $this->_db->loadResultArray();

			return $mi_list;
		} else {
			return false;
		}
	}

	function triggerMIs( $action, $metaUser, $exchange, $invoice, $add=false, $silent=false )
	{
		global $aecConfig;

		$micro_integrations = $this->getMicroIntegrations();

		if ( is_array( $micro_integrations ) ) {
			foreach ( $micro_integrations as $mi_id ) {
				$mi = new microIntegration( $this->_db );

				if ( !$mi->mi_exists( $mi_id ) ) {
					continue;
				}

				$mi->load( $mi_id );

				if ( !$mi->callIntegration() ) {
					continue;
				}

				$is_email = strcmp( $mi->class_name, 'mi_email' ) === 0;

				// Only trigger if this is not email or made not silent
				if ( ( $is_email === false ) || ( $is_email && !$silent ) ) {
					if ( method_exists( $metaUser, $action ) ) {
						if ( $mi->$action( $metaUser, null, $invoice, $this ) === false ) {
							if ( $aecConfig->cfg['breakon_mi_error'] ) {
								return false;
							}
						}
					} else {
						if ( $mi->relayAction( $metaUser, $exchange, $invoice, $this, $action, $add ) === false ) {
							if ( $aecConfig->cfg['breakon_mi_error'] ) {
								return false;
							}
						}
					}


				}

				unset( $mi );
			}
		}
	}

	function getProcessorParameters( $processorid )
	{
		$procparams = array();
		if ( !empty( $this->custom_params ) ) {
			foreach ( $this->custom_params as $name => $value ) {
				$realname = explode( '_', $name, 2 );

				if ( ( $realname[0] == $processorid ) && isset( $realname[1] ) ) {
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
		global $database;

		// Fake knowing the planid if is zero. TODO: This needs to replaced with something better later on!
		if ( !empty( $post['id'] ) ) {
			$planid = $post['id'];
		} else {
			$planid = $this->getMax() + 1;
		}

		if ( isset( $post['id'] ) ) {
			unset( $post['id'] );
		}

		if ( !empty( $post['add_group'] ) ) {
			ItemGroupHandler::setChildren( $post['add_group'], array( $planid ) );
		}

		// Filter out fixed variables
		$fixed = array( 'active', 'visible', 'name', 'desc', 'email_desc', 'micro_integrations' );

		foreach ( $fixed as $varname ) {
			$this->$varname = $post[$varname];
			unset( $post[$varname] );
		}

		// Get selected processors ( have to be filtered out )

		$processors = array();
		foreach ( $post as $key => $value ) {
			if ( ( strpos( $key, 'processor_' ) === 0 ) && ( $value == 'on') ) {
				$processors[] = str_replace( 'processor_', '', $key );
				unset( $post[$key] );
			}
		}

		// Filter out params
		$fixed = array( 'full_free', 'full_amount', 'full_period', 'full_periodunit',
						'trial_free', 'trial_amount', 'trial_period', 'trial_periodunit',
						'gid_enabled', 'gid', 'lifetime', 'standard_parent',
						'fallback', 'similarplans', 'equalplans', 'make_active',
						'make_primary', 'update_existing', 'customthanks', 'customtext_thanks_keeporiginal',
						'customtext_thanks', 'override_activation', 'override_regmail'
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
			if ( strpos( $varname, 'group_delete_' ) !== false ) {
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
		global $database;

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

		// TODO: Check for Similarity/Equality relations on other plans

		$this->params = $params;
	}
}

class logHistory extends mosDBTable
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
		$this->mosDBTable( '#__acctexp_log_history', 'id', $db );
	}

	function entryFromInvoice( $objInvoice, $response, $pp )
	{
		global $database, $mosConfig_offset;

		$user = new mosUser( $database );
		$user->load( $objInvoice->userid );

		$plan = new SubscriptionPlan( $database );
		$plan->load( $objInvoice->usage );

		$this->proc_id			= $pp->id;
		$this->proc_name		= $pp->processor_name;
		$this->user_id			= $user->id;
		$this->user_name		= $user->username;
		$this->plan_id			= $plan->id;
		$this->plan_name		= $plan->name;
		$this->transaction_date	= date( 'Y-m-d H:i:s', time() + $mosConfig_offset*3600 );
		$this->amount			= $objInvoice->amount;
		$this->invoice_number	= $objInvoice->invoice_number;
		$this->response			= $response;

		$short	= 'history entry';
		$event	= 'Processor (' . $pp->processor_name . ') notification for ' . $objInvoice->invoice_number;
		$tags	= 'history,processor,payment';
		$params = array( 'invoice_number' => $objInvoice->invoice_number );

		$eventlog = new eventLog( $database );
		$eventlog->issue( $short, $tags, $event, 2, $params );

		if ( !$this->check() ) {
			echo "<script> alert('".$this->getError()."'); window.history.go(-1); </script>\n";
			exit();
		}
		if ( !$this->store() ) {
			echo "<script> alert('".$this->getError()."'); window.history.go(-1); </script>\n";
			exit();
		}
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

	function InvoiceFactory( $userid=null, $usage=null, $group=null, $processor=null, $invoice=null )
	{
		global $database, $mainframe, $my;

		$this->userid = $userid;
		$this->authed = false;

		require_once( $mainframe->getPath( 'front_html', 'com_acctexp' ) );

		// Check whether this call is legitimate
		if ( !$my->id ) {
			if ( !$this->userid ) {
				// Its ok, this is a registration/subscription hybrid call
				$this->authed = true;
			} elseif ( $this->userid ) {
				if ( AECToolbox::quickVerifyUserID( $this->userid ) === true ) {
					// This user is not expired, so he could log in...
					mosNotAuth();
					return;
				} else {
					$this->userid = $database->getEscaped( $userid );
				}
			}
		} else {
			// Overwrite the given userid when user is logged in
			$this->userid = $my->id;
			$this->authed = true;
		}

		// Init variables
		$this->usage		= $usage;
		$this->group		= $group;
		$this->processor	= $processor;
		$this->invoice		= $invoice;

		// Delete set userid if it doesn't exist
		if ( !is_null( $this->userid ) ) {
			$query = 'SELECT `id`'
					. ' FROM #__users'
					. ' WHERE `id` = \'' . $this->userid . '\'';
			$database->setQuery( $query );

			if ( !$database->loadResult() ) {
				$this->userid = null;
			}
		}

		if ( $this->usage ) {
			$this->verifyUsage();
		}
	}

	function verifyUsage()
	{
		global $database;

		$this->loadMetaUser( false, true );

		$row = new SubscriptionPlan( $database );
		$row->load( $this->usage );

		$restrictions = $row->getRestrictionsArray();

		if ( !aecRestrictionHelper::checkRestriction( $restrictions, $this->metaUser ) ) {
			mosNotAuth();
		}

		if ( !ItemGroupHandler::checkParentRestrictions( $row, 'item', $this->metaUser ) ) {
			mosNotAuth();
		}
	}

	function puffer( $option )
	{
		global $database;

		if ( $this->usage ) {
			// get the payment plan
			$this->objUsage = new SubscriptionPlan( $database );
			$this->objUsage->load( $this->usage );
		} else {
			mosNotAuth();
		}

		if ( !is_null( $this->processor ) && !( $this->processor == '' ) ) {
			$this->pp					= false;
			$this->recurring			= 0;
			$this->payment->method_name = _AEC_PAYM_METHOD_NONE;
			$this->payment->currency	= '';

			switch ( $this->processor ) {
				case 'free': $this->payment->method_name = _AEC_PAYM_METHOD_FREE; break;
				case 'none': break;
				default:
					$this->pp = new PaymentProcessor();
					if ( $this->pp->loadName( $this->processor ) ) {
						$this->pp->fullInit();
						$this->pp->exchangeSettingsByPlan( $this->objUsage );

						$this->payment->method_name	= $this->pp->info['longname'];

						// Check whether we have a recurring payment
						// If it has been selected just now, or earlier, check whether that is still permitted
						if ( isset( $this->recurring ) ) {
							$this->recurring		= $this->pp->is_recurring( $this->recurring );
						} else {
							if ( isset( $_POST['recurring'] ) ) {
								$this->recurring	= $this->pp->is_recurring( $_POST['recurring'] );
							} else {
								$this->recurring	= $this->pp->is_recurring();
							}
						}

						$this->payment->currency	= isset( $this->pp->settings['currency'] ) ? $this->pp->settings['currency'] : '';
					} else {
						// TODO: Log Error
					}
					break;
			}
		} else {
			mosNotAuth();
		}

		$user_subscription = false;
		$this->renew = 0;

		if ( !empty( $this->userid ) ) {
			if ( !empty( $this->metaUser ) ) {
				$this->renew = count( $this->metaUser->meta->plan_history ) ? 1 : 0;
			} elseif ( AECfetchfromDB::SubscriptionIDfromUserID( $this->userid ) ) {
				$user_subscription = new Subscription( $database );
				$user_subscription->loadUserID( $this->userid );

				if ( ( strcmp( $user_subscription->lastpay_date, '0000-00-00 00:00:00' ) !== 0 ) ) {
					$this->renew = 1;
				}
			}
		}

		$this->terms = new mammonTerms();
		$this->terms->readParams( $this->objUsage->params );

		$return = $this->objUsage->SubscriptionAmount( $this->recurring, $user_subscription );

		$this->payment->freetrial = 0;

		if ( is_array( $return['amount'] ) ) {
			$this->payment->amount = false;

			if ( isset( $return['amount']['amount1'] ) ) {
				if ( !is_null( $return['amount']['amount1'] ) ) {
					$this->payment->amount = $return['amount']['amount1'];
					if ( $this->payment->amount == '0.00' ) {
						$this->payment->freetrial = 1;
					}
				}
			}

			if ( $this->payment->amount === false ) {
				if ( isset( $return['amount']['amount2'] ) ) {
					if ( !is_null( $return['amount']['amount2'] ) ) {
						$this->payment->amount = $return['amount']['amount2'];
						if ( $this->payment->amount == '0.00' ) {
							$this->payment->freetrial = 1;
						}
					}
				}
			}

			if ( $this->payment->amount === false ) {
				if ( isset( $return['amount']['amount3'] ) ) {
					if ( !is_null( $return['amount']['amount3'] ) ) {
						$this->payment->amount = $return['amount']['amount3'];
					}
				}
			}
		} else {
			$this->payment->amount = $return['amount'];
			if ( ( $this->payment->amount == '0.00' ) && $return['free_trial'] ) {
				$this->payment->freetrial = 1;
			}
		}

		return;
	}

	function touchInvoice( $option, $invoice_number=false )
	{
		// Checking whether we are trying to repeat an invoice
		if ( $invoice_number !== false ) {
			// Make sure the invoice really exists and that its the correct user carrying out this action
			$invoiceid = AECfetchfromDB::InvoiceIDfromNumber($invoice_number, $this->userid);

			if ( $invoiceid ) {
				$this->invoice = $invoice_number;
			}
		}

		if ( !empty( $this->invoice ) ) {
			if ( !isset( $this->objInvoice ) ) {
				$this->objInvoice = null;
			}

			if ( !is_object( $this->objInvoice ) ) {
				global $database;
				$this->objInvoice = new Invoice( $database );
			}

			$this->objInvoice->loadInvoiceNumber( $this->invoice );
			$this->objInvoice->computeAmount();

			$this->processor = $this->objInvoice->method;
			$this->usage = $this->objInvoice->usage;

			if ( empty( $this->usage ) && empty( $this->objInvoice->conditions ) ) {
				$this->create( $option, 0, 0, $this->invoice_number );
			} elseif ( empty( $this->processor ) ) {
				$this->create( $option, 0, $this->usage, $this->invoice_number );
			}
		} else {
			global $database;

			$this->objInvoice = new Invoice( $database );

			$this->objInvoice->create( $this->userid, $this->usage, $this->processor );
			$this->objInvoice->computeAmount();

			if ( is_object( $this->pp ) ) {
				$this->pp->invoiceCreationAction( $this );
			}

			if ( !empty( $this->objUsage ) ) {
				$this->objUsage->triggerMIs( '_invoice_creation', $this->metaUser, null, $this->objInvoice );
			}

			// Reset parameters
			$this->processor	= $this->objInvoice->method;
			$this->usage		= $this->objInvoice->usage;
			$this->invoice		= $this->objInvoice->invoice_number;
		}

		$recurring = aecGetParam( 'recurring', null );

		if ( isset( $this->objInvoice->params['userselect_recurring'] ) ) {
			$this->recurring = $this->objInvoice->params['userselect_recurring'];
		} elseif ( !is_null( $recurring ) ) {
			$this->objInvoice->addParams( array( 'userselect_recurring' => $recurring ) );
			$this->objInvoice->check();
			$this->objInvoice->store();
		}

		return;
	}

	function loadMetaUser( $passthrough=false, $force=false )
	{
		if ( isset( $this->metaUser ) ) {
			if ( is_object( $this->metaUser ) && !$force ) {
				return false;
			}
		}

		if ( empty( $this->userid ) ) {
			// Creating a dummy user object
			$this->metaUser = new metaUser( 0 );
			$this->metaUser->cmsUser = new stdClass();
			$this->metaUser->cmsUser->gid = 29;
			$this->metaUser->hasSubscription = false;
			$this->metaUser->hasExpiration = false;

			if ( is_array( $passthrough ) && !empty( $passthrough ) ) {
				$cpass = $passthrough;
				unset( $cpass['id'] );

				$cmsfields = array( 'name', 'username', 'email', 'password' );

				// Create dummy CMS user
				foreach( $cmsfields as $cmsfield ) {
					foreach ( $cpass as $id => $array ) {
						if ( $array[0] == $cmsfield ) {
							$this->metaUser->cmsUser->$cmsfield = $array[1];
							unset( $cpass[$id] );
						}
					}
				}

				// Create dummy CB/CBE user
				if ( GeneralInfoRequester::detect_component( 'CB' ) || GeneralInfoRequester::detect_component( 'CBE' ) ) {
					$this->metaUser->hasCBprofile = 1;
					$this->metaUser->cbUser = new stdClass();

					foreach ( $cpass as $cbfield => $cbvalue ) {
						if ( is_array( $cbvalue ) ) {
							$this->metaUser->cbUser->$cbfield = implode( ';', $cbvalue );
						} else {
							$this->metaUser->cbUser->$cbfield = $cbvalue;
						}
					}
				}

				return false;
			} else {
				return true;
			}
		} else {
			// Loading the actual user
			$this->metaUser = new metaUser( $this->userid );
			return false;
		}
	}

	function checkAuth( $option, $var )
	{
		$return = true;

		$this->loadMetaUser();

		// Add in task in case this is not set in passthrough
		if ( !isset( $var['task'] ) ) {
			$var['task'] = 'subscribe';
		}

		// Add in userid in case this is not set in passthrough
		if ( !isset( $var['userid'] ) ) {
			$var['userid'] = $this->userid;
		}

		if ( empty( $this->authed ) ) {
			if ( !$this->metaUser->getTempAuth() ) {
				if ( isset( $var['password'] ) ) {
					if ( !$this->metaUser->setTempAuth( $var['password'] ) ) {
						unset( $var['password'] );
						$this->promptpassword( $option, $var, true );
						$return = false;
					}
				} else {
					$this->promptpassword( $option, $var );
					$return = false;
				}
			}
		}

		return $return;
	}

	function promptpassword( $option, $var, $wrong=false )
	{
		global $mainframe;

		$passthrough = array();
		foreach ( $var as $ke => $va ) {
			if ( is_array( $va ) ) {
				foreach ( $va as $con ) {
					$passthrough[] = array( $ke . '[]', $con );
				}
			} else {
				$passthrough[] = array( $ke, $va );
			}
		}

		$mainframe->SetPageTitle( _AEC_PROMPT_PASSWORD );

		Payment_HTML::promptpassword( $option, $passthrough, $wrong );
	}

	function create( $option, $intro=0, $usage=0, $group=0, $processor=null, $invoice=0, $passthrough=false )
	{
		global $database, $mainframe, $my, $aecConfig;

		$register = $this->loadMetaUser( $passthrough, true );

		$subscriptionClosed = false;
		if ( $this->metaUser->hasSubscription ) {
			$subscriptionClosed = ( strcmp( $this->metaUser->objSubscription->status, 'Closed' ) === 0 );
		} elseif ( empty( $this->usage ) ) {
			// TODO: Check if the user has already subscribed once, if not - link to intro
			// TODO: Make sure a registration hybrid wont get lost here
			if ( !$intro && !empty( $aecConfig->cfg['customintro'] ) ) {
				mosRedirect( $aecConfig->cfg['customintro'] );
			}
		}

		$recurring = aecGetParam( 'recurring', null );

		if ( !is_null( $recurring ) ) {
			$this->recurring = $recurring;
		} else {
			$this->recurring = null;
		}

		$list = array();

		if ( !empty( $usage ) ) {
			$query = 'SELECT `id`'
					. ' FROM #__acctexp_plans'
					. ' WHERE `id` = \'' . $usage . '\' AND `active` = \'1\''
					;
			$database->setQuery( $query );
			$id = $database->loadResult();

			if ( $database->getErrorNum() ) {
				echo $database->stderr();
				return false;
			}

			if ( $id ) {
				$plan = new SubscriptionPlan( $database );
				$plan->load( $id );

				$restrictions = $plan->getRestrictionsArray();

				if ( aecRestrictionHelper::checkRestriction( $restrictions, $this->metaUser ) === false ) {
					continue;
				}

				if ( ItemGroupHandler::checkParentRestrictions( $plan, 'item', $this->metaUser ) ) {
					$list[] = ItemGroupHandler::getItemListItem( $plan );
				}
			}
		} elseif ( !empty( $group ) ) {
			$g = new ItemGroup( $database );
			$g->load( $group );

			if ( $g->checkVisibility( $this->metaUser ) ) {
				if ( !empty( $g->params['symlink'] ) ) {
					mosRedirect( $g->params['symlink'] );
				}

				$list = ItemGroupHandler::getTotalAllowedChildItems( array( $group ), $this->metaUser );
			}
		} else {
			if ( !empty( $aecConfig->cfg['root_group_rw'] ) ) {
				$x = AECToolbox::rewriteEngine( $this->metaUser, $this->metaUser );
			} else {
				$x = array( $aecConfig->cfg['root_group'] );
			}

			if ( !is_array( $x ) && !empty( $x ) ) {
				$x = array( $x );
			} else {
				$x = array( $aecConfig->cfg['root_group'] );
			}

			$list = ItemGroupHandler::getTotalAllowedChildItems( $x, $this->metaUser );

			// Retry in case a RWengine call didn't work out
			if ( empty( $list ) && !empty( $aecConfig->cfg['root_group_rw'] ) ) {
				$list = ItemGroupHandler::getTotalAllowedChildItems( $aecConfig->cfg['root_group'], $this->metaUser );
			}
		}

		// There are no plans to begin with, so we need to punch out an error here
		if ( count( $list ) == 0 ) {
			mosRedirect( AECToolbox::deadsureURL( 'index.php?mosmsg=' . _NOPLANS_ERROR ), false, true );
			return;
		}

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

							$pp->init();
							$pp->getInfo();
							$pp->exchangeSettingsByPlan( $plan['plan'] );

							$recurring = $pp->is_recurring( $this->recurring );

							if ( $recurring > 1 ) {
								$pp->recurring = 0;
								$plan_gw[] = $pp;

								if ( !$plan['plan']->params['lifetime'] ) {
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

		$list = array_merge( $groups, $plans );

		// After filtering out the processors, no plan or group can be used, so we have to again issue an error
		 if ( count( $list ) == 0 ) {
			mosRedirect( AECToolbox::deadsureURL( 'index.php?mosmsg=' . _NOPLANS_ERROR ), false, true );
			return;
		}

		$nochoice = false;

		// There is no choice if we have only one group or only one item with one payment option
		if ( count( $list ) === 1 ) {
			if ( $list[0]['type'] == 'item' ) {
				if ( count( $plans[0]['gw'] ) === 1 ) {
					$nochoice = true;
				}
			} else {
				$nochoice = true;
			}
		}

		// If we have only one processor on one plan, there is no need for a decision
		if ( $nochoice && !( $aecConfig->cfg['show_fixeddecision'] && empty( $processor ) ) ) {
			// If the user also needs to register, we need to guide him there after the selection has now been made
			if ( $register ) {
				// The plans are supposed to be first, so the details form should hold the values
				if ( !empty( $plans[0]['id'] ) ) {
					$_POST['usage']		= $plans[0]['id'];
					$_POST['processor']	= $plans[0]['gw'][0]->processor_name;
					if ( isset( $plans[0]['gw'][0]->recurring ) ) {
						$_POST['recurring']	= $plans[0]['gw'][0]->recurring;
					}
				}

				// Send to CB or joomla!
				if ( GeneralInfoRequester::detect_component( 'CB' ) || GeneralInfoRequester::detect_component( 'CBE' ) ) {
					// This is a CB registration, borrowing their code to register the user

					global $task;

					$savetask	= $task;
					$_REQUEST['task'] = 'done';

					include_once( $mainframe->getCfg( 'absolute_path' ) . '/components/com_comprofiler/comprofiler.html.php' );
					include_once( $mainframe->getCfg( 'absolute_path' ) . '/components/com_comprofiler/comprofiler.php' );

					$task = $savetask;

					registerForm($option, $mainframe->getCfg( 'emailpass' ), null);
				} elseif ( GeneralInfoRequester::detect_component( 'JUSER' ) ) {
					// This is a JUSER registration, borrowing their code to register the user

					global $task, $mosConfig_absolute_path;

					$savetask	= $task;
					$task = 'blind';

					include_once( $mainframe->getCfg( 'absolute_path' ) . '/components/com_juser/juser.html.php' );
					include_once( $mainframe->getCfg( 'absolute_path' ) . '/components/com_juser/juser.php' );

					$task = $savetask;

					userRegistration( $option, null );
				} else {
					if ( !isset( $_POST['usage'] ) ) {
						$_POST['intro'] = $intro;
						$_POST['usage'] = $usage;
					}

					if ( aecJoomla15check() ) {
						$usersConfig = &JComponentHelper::getParams( 'com_users' );
						$activation = $usersConfig->get('useractivation');
					} else {
						$activation = $mainframe->getCfg( 'useractivation' );
					}

					//include_once( $mainframe->getCfg( 'absolute_path' ) . '/components/com_acctexp/acctexp.html.php' );
					joomlaregisterForm( $option, $activation );
				}
			} else {
				// The user is already existing, so we need to move on to the confirmation page with the details

				$this->usage		= $plans[0]['id'];

				if ( isset( $plans[0]['gw'][0]->recurring ) ) {
					$this->recurring	= $plans[0]['gw'][0]->recurring;
				} else {
					$this->recurring	= 0;
				}

				$this->processor	= $plans[0]['gw'][0]->processor_name;

				if ( ( $invoice != 0 ) && !is_null( $invoice ) ) {
					$this->invoice	= $invoice;
				}

				$password = aecGetParam( 'password', null );

				$var = array();
				if ( !is_null( $password ) ) {
					$var['password'] = $password;
				}

				$this->confirm( $option, $var, $passthrough );
			}
		} else {
			// Reset $register if we seem to have all data
			// TODO: find better solution for this
			if ( $register && isset( $passthrough['username'] ) ) {
				$register = 0;
			} else {

			}

			$mainframe->SetPageTitle( _PAYPLANS_HEADER );

			if ( $group ) {
				$g = new ItemGroup( $database );
				$g->load( $group );

				$list['group'] = ItemGroupHandler::getGroupListItem( $g );
			}

			// Of to the Subscription Plan Selection Page!
			Payment_HTML::selectSubscriptionPlanForm( $option, $this->userid, $list, $subscriptionClosed, $passthrough, $register );
		}
	}

	function confirm( $option, $var=array(), $passthrough=false )
	{
		global $database, $my, $aecConfig, $mosConfig_absolute_path;

		if ( empty( $passthrough ) ) {
			if ( !$this->checkAuth( $option, $var ) ) {
				return false;
			}
		}

		if ( isset( $var['task'] ) ) {
			unset( $var['task'] );
			unset( $var['option'] );
		}

		if ( $this->userid ) {
			$user = new mosUser( $database );
			$user->load( $this->userid );

			$passthrough = false;
		} else {
			if ( isset( $var['usage'] ) ) {
				unset( $var['usage'] );
				unset( $var['processor'] );
				unset( $var['currency'] );
				unset( $var['amount'] );
			}

			if ( is_array( $passthrough ) ) {
				$user = new mosUser( $database );

				$details = array( 'name', 'username', 'email' );

				foreach ( $passthrough as $id => $array ) {
					if ( in_array( $array[0], $details ) ) {
						$user->{$array[0]} = $array[1];
					}
				}
			} else {
				$user = new mosUser( $database );
				if ( isset( $var['name'] ) ) {
					$user->name		= $var['name'];
				}
				$user->username = $var['username'];
				$user->email	= $var['email'];

				$passthrough = array();
				foreach ( $var as $ke => $va ) {
					if ( is_array( $va ) ) {
						foreach ( $va as $con ) {
							$passthrough[] = array( $ke . '[]', $con );
						}
					} else {
						$passthrough[] = array( $ke, $va );
					}
				}
			}
		}

		if ( $aecConfig->cfg['use_recaptcha'] && !empty( $aecConfig->cfg['recaptcha_privatekey'] ) && isset( $_POST["recaptcha_challenge_field"] ) && isset( $_POST["recaptcha_response_field"] ) ) {
			// require the recaptcha library
			require_once( $mosConfig_absolute_path . '/components/com_acctexp/lib/recaptcha/recaptchalib.php' );

			//finally chack with reCAPTCHA if the entry was correct
			$resp = recaptcha_check_answer ( $aecConfig->cfg['recaptcha_privatekey'], $_SERVER["REMOTE_ADDR"], $_POST["recaptcha_challenge_field"], $_POST["recaptcha_response_field"] );

			//if the response is INvalid, then go back one page, and try again. Give a nice message
			if (!$resp->is_valid) {
				echo "<script> alert('The reCAPTCHA entered incorrectly. Go back and try it again. reCAPTCHA said: " . $resp->error . "'); window.history.go(-1); </script>\n";
				exit();
			}
		}

		$this->puffer( $option );

		$this->coupons = array();
		$this->coupons['active'] = $aecConfig->cfg['enable_coupons'];

		if ( !empty( $aecConfig->cfg['skip_confirmation'] ) ) {
			if ( $passthrough ) {
				$this->loadMetaUser( $passthrough, true );
			}

			$this->save( $option, $var );
		} else {
			global $mainframe;

			$this->mi_form = $this->objUsage->getMIforms();

			$mainframe->SetPageTitle( _CONFIRM_TITLE );

			Payment_HTML::confirmForm( $option, $this, $user, $passthrough );
		}
	}


	function save( $option, $var, $coupon=null )
	{
		global $database, $mainframe, $task;

		if ( isset( $var['task'] ) ) {
			unset( $var['task'] );
			unset( $var['option'] );
		}

		if ( $this->usage == '' ) {
			$this->usage = $var['usage'];
		}

		if ( $this->processor == '' ) {
			$this->processor = $var['processor'];
		}

		$this->confirmed = 1;

		if ( !empty( $this->usage ) ) {
			// get the payment plan
			$this->objUsage = new SubscriptionPlan( $database );
			$this->objUsage->load( $this->usage );
		}

		if ( empty( $this->userid ) ) {
			if ( !empty( $this->objUsage ) ) {
				if ( isset( $this->objUsage->params['override_activation'] ) ) {
					$overrideActivation = $this->objUsage->params['override_activation'];
				} else {
					$overrideActivation = false;
				}

				if ( isset( $this->objUsage->params['override_regmail'] ) ) {
					$overrideEmail = $this->objUsage->params['override_regmail'];
				} else {
					$overrideEmail = false;
				}

				$this->userid = AECToolbox::saveUserRegistration( $option, $var, false, $overrideActivation, $overrideEmail );
			} else {
				$this->userid = AECToolbox::saveUserRegistration( $option, $var );
			}
		}

		$this->loadMetaUser( false, true );
		$this->metaUser->setTempAuth();

		$this->puffer( $option );

		$this->touchInvoice( $option );

		if ( !empty( $coupon ) ) {
			$this->objInvoice->addCoupon( $coupon );
			$this->objInvoice->check();
			$this->objInvoice->store();

			// Make sure we have the correct amount loaded
			$this->touchInvoice( $option );
		}

		if ( !empty( $this->objUsage ) ) {
			if ( is_object( $this->objUsage ) ) {
				$mi_form = $this->objUsage->getMIformParams();

				if ( !empty( $mi_form ) ) {
					$params = array();
					foreach ( $mi_form as $key => $value ) {
						$val = aecGetParam( $key );

						$k = explode( '_', $key, 3 );

						if ( !empty( $val ) ) {
							$params[$k[1]][$k[2]] = $val;
						}
					}

					if ( !empty( $params ) ) {
						foreach ( $params as $mi_id => $content ) {
							$this->metaUser->meta->setMIParams( $mi_id, $this->objUsage->id, $content );
						}

						$this->metaUser->meta->storeload();
					}
				}

				$this->touchInvoice( $option );
			}
		}

		$this->checkout( $option );
	}

	function checkout( $option, $repeat=0 )
	{
		global $database, $aecConfig;

		if ( !$this->checkAuth( $option, aecPostParamClear( $_POST ) ) ) {
			return false;
		}

		$this->puffer( $option );

		$repeat = empty( $repeat ) ? 0 : $repeat;

		$exceptproc = array( 'none', 'free' );

		// If this is marked as supposedly free
		if ( in_array( strtolower( $this->processor ), $exceptproc ) ) {
			// And if it is either made free through coupons
			if ( !empty( $this->objInvoice->made_free )
				// Or a free full period that the user CAN use
				|| ( $this->objUsage->params['full_free'] && $this->objInvoice->counter )
				|| ( $this->objUsage->params['full_free'] && empty( $this->objInvoice->counter ) && empty( $this->objUsage->params['trial_period'] ) )
				// Or a free trial that the user CAN use
				|| ( $this->objUsage->params['trial_free'] && empty( $this->objInvoice->counter ) ) ) {
				// Then mark payed
				if ( $this->objInvoice->pay() !== false ) {
					$this->thanks( $option, $this->renew, 1 );
					return;
				}
			}

			notAllowed( $option );
			return;
		} elseif ( strcmp( strtolower( $this->processor ), 'error' ) === 0 ) {
			// Nope, won't work buddy
			notAllowed( $option );
			return;
		}

		if ( !empty( $this->pp->info['secure'] ) && empty( $_SERVER['HTTPS'] ) && !$aecConfig->cfg['override_reqssl'] ) {
			mosRedirect( AECToolbox::deadsureURL( "index.php?option=" . $option . "&task=repeatPayment&invoice=" . $this->objInvoice->invoice_number . "&first=" . ( $repeat ? 0 : 1 ), true, false ) );
			exit();
		};

		$this->terms = new mammonTerms();
		$this->terms->readParams( $this->objUsage->termsParamsRequest( $this->recurring, $this->metaUser ) );

		$c = $this->objUsage->doPlanComparison( $this->metaUser->objSubscription );

		// Do not allow a Trial if the user has used this or a similar plan
		if ( $this->terms->hasTrial && !( ( $c['comparison'] === false ) && ( $c['total_comparison'] === false ) ) ) {
			$this->terms->incrementPointer();
		}

		$this->amount = $this->objUsage->SubscriptionAmount( $this->recurring, $this->metaUser->objSubscription, $this->metaUser );

		if ( !empty( $aecConfig->cfg['enable_coupons'] ) && !empty( $this->objInvoice->coupons ) ) {
			$coupons = $this->objInvoice->coupons;
			$orcoupn = $coupons;

			$cpsh = new couponsHandler();

			$this->terms = $cpsh->applyCouponsToTerms( $this->terms, $coupons, $this->metaUser, $this->amount, $this );

			if ( count( $orcoupn ) != count( $coupons ) ) {
				foreach ( $orcoupn as $couponcode ) {
					if ( !in_array( $couponcode, $coupons ) ) {
						$this->objInvoice->removeCoupon( $couponcode );
					}
				}

				$this->objInvoice->check();
				$this->objInvoice->store();
			}

			$cpsh_err = $cpsh->getErrors();

			if ( count( $cpsh_err ) ) {
				$this->terms->errors = $cpsh_err;
			}
		}

		// Either this is fully free, or the next term is free and this is non recurring
		if ( $this->terms->checkFree() || ( $this->terms->nextterm->free && !$this->recurring ) ) {
			$this->objInvoice->pay();
			$this->thanks( $option, $this->renew, 1 );
			return;
		}

		$this->InvoiceToCheckout( $option, $repeat );
	}

	function InvoiceToCheckout( $option, $repeat=0 )
	{
		global $mainframe;

		$var = $this->objInvoice->prepareProcessorLink( $this );

		$this->objInvoice->formatInvoiceNumber();

		$mainframe->SetPageTitle( _CHECKOUT_TITLE );

		Payment_HTML::checkoutForm( $option, $var['var'], $var['params'], $this, $repeat );
	}

	function internalcheckout( $option )
	{
		global $database;

		$this->metaUser = new metaUser( $this->userid );

		$this->puffer( $option );

		$var = $this->objInvoice->getFullVars();

		$new_subscription = new SubscriptionPlan( $database );
		$new_subscription->load( $this->objInvoice->usage );

		$badbadvars = array( 'userid', 'invoice', 'task', 'option' );
		foreach ( $badbadvars as $badvar ) {
			if ( isset( $_POST[$badvar] ) ) {
				unset( $_POST[$badvar] );
			}
		}

		$var['params'] = aecPostParamClear( $_POST );

		$response = $this->pp->checkoutProcess( $var, $this->metaUser, $new_subscription, $this->objInvoice );

		if ( isset( $response['error'] ) ) {
			$this->error( $option, $this->metaUser->cmsUser, $this->objInvoice->invoice_number, $response['error'] );
		} else { // TODO: Check for renew!!!
			$this->thanks( $option, 1, false );
		}
	}

	function planprocessoraction( $action, $subscr=null )
	{
		global $database;

		$this->metaUser = new metaUser( $this->userid );

		$invoice = new Invoice( $database );

		if ( !empty( $subscr ) ) {
			if ( $this->metaUser->moveFocus( $subscr ) ) {
				$invoice->loadbySubscriptionId( $this->metaUser->focusSubscription->id, $this->metaUser->userid );
			}
		}

		if ( empty( $invoice->id ) ) {
			$invoice->load( AECfetchfromDB::lastClearedInvoiceIDbyUserID( $this->userid, $this->metaUser->focusSubscription->plan ) );
		}

		if ( empty( $invoice->id ) ) {
			$invoice->load( AECfetchfromDB::lastUnclearedInvoiceIDbyUserID( $this->userid, $this->metaUser->focusSubscription->plan ) );
		}

		$pp = new PaymentProcessor( $database );
		if ( $pp->loadName( $invoice->method ) ) {
			$pp->fullInit();
		}

		$response = $pp->customAction( $action, $invoice, $this->metaUser );

		$invoice->processorResponse( $pp, $response );

		if ( isset( $response['cancel'] ) ) {
			HTML_Results::cancel( 'com_acctexp' );
		}
	}

	function thanks( $option, $renew, $free )
	{
		global $mosConfig_useractivation, $aecConfig, $mainframe;

		if ( is_object( $this->objUsage ) ) {
			if ( !empty( $this->objUsage->params['customthanks'] ) ) {
				mosRedirect( $this->objUsage->params['customthanks'] );
			} elseif ( $aecConfig->cfg['customthanks'] ) {
				mosRedirect( $aecConfig->cfg['customthanks'] );
			}
		} else {
			$this->simplethanks( $option, $renew, $free );
		}

		if ( isset( $this->renew ) ) {
			$renew = $this->renew;
		}

		if ( $renew ) {
			$msg = _SUB_FEPARTICLE_HEAD_RENEW . '</p><p>' . _SUB_FEPARTICLE_THANKSRENEW;
			if ( $free ) {
				$msg .= _SUB_FEPARTICLE_LOGIN;
			} else {
				$msg .= _SUB_FEPARTICLE_PROCESSPAY . _SUB_FEPARTICLE_MAIL;
			}
		} else {
			$msg = _SUB_FEPARTICLE_HEAD . '</p><p>' . _SUB_FEPARTICLE_THANKS;

			$msg .=  $free ? _SUB_FEPARTICLE_PROCESS : _SUB_FEPARTICLE_PROCESSPAY;

			$msg .= $mosConfig_useractivation ? _SUB_FEPARTICLE_ACTMAIL : _SUB_FEPARTICLE_MAIL;
		}

		$b = '';
		if ( $aecConfig->cfg['customtext_thanks_keeporiginal'] ) {
			$b .= '<div class="componentheading"' . _THANKYOU_TITLE . '</div>';
		}

		if ( $aecConfig->cfg['customtext_thanks'] ) {
			$b .= $aecConfig->cfg['customtext_thanks'];
		}

		if ( $aecConfig->cfg['customtext_thanks_keeporiginal'] ) {
			$b .= '<div id="thankyou_page">' . '<p>' . $msg . '</p>' . '</div>';
		}

		$up =& $this->objUsage->params;

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
		}

		$mainframe->SetPageTitle( _THANKYOU_TITLE );

		HTML_Results::thanks( $option, $msg );
	}

	function simplethanks( $option, $renew, $free )
	{
		global $mosConfig_useractivation, $aecConfig, $mainframe;

		// Look whether we have a custom ThankYou page
		if ( $aecConfig->cfg['customthanks'] ) {
			mosRedirect( $aecConfig->cfg['customthanks'] );
		}

		if ( isset( $this->renew ) ) {
			$renew = $this->renew;
		}

		if ( $renew ) {
			$msg = _SUB_FEPARTICLE_HEAD_RENEW . '</p><p>' . _SUB_FEPARTICLE_THANKSRENEW;
			if ( $free ) {
				$msg .= _SUB_FEPARTICLE_LOGIN;
			} else {
				$msg .= _SUB_FEPARTICLE_PROCESSPAY . _SUB_FEPARTICLE_MAIL;
			}
		} else {
			$msg = _SUB_FEPARTICLE_HEAD . '</p><p>' . _SUB_FEPARTICLE_THANKS;

			$msg .=  $free ? _SUB_FEPARTICLE_PROCESS : _SUB_FEPARTICLE_PROCESSPAY;

			$msg .= $mosConfig_useractivation ? _SUB_FEPARTICLE_ACTMAIL : _SUB_FEPARTICLE_MAIL;
		}

		$b = '';
		if ( $aecConfig->cfg['customtext_thanks_keeporiginal'] ) {
			$b .= '<div class="componentheading"' . _THANKYOU_TITLE . '</div>';
		}

		if ( $aecConfig->cfg['customtext_thanks'] ) {
			$b .= $aecConfig->cfg['customtext_thanks'];
		}

		if ( $aecConfig->cfg['customtext_thanks_keeporiginal'] ) {
			$b .= '<div id="thankyou_page">' . '<p>' . $msg . '</p>' . '</div>';
		}

		$mainframe->SetPageTitle( _THANKYOU_TITLE );

		HTML_Results::thanks( $option, $b );
	}

	function error( $option, $objUser, $invoice, $error )
	{
		global $mainframe;

		$mainframe->SetPageTitle( _CHECKOUT_ERROR_TITLE );

		Payment_HTML::error( $option, $objUser, $invoice, $error );
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
		$this->mosDBTable( '#__acctexp_invoices', 'id', $db );

		if ( empty( $this->counter ) && ( $this->transaction_date != '0000-00-00 00:00:00' ) ) {
			$this->counter = 1;
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
		global $database;

		$query = 'SELECT id'
		. ' FROM #__acctexp_invoices'
		. ' WHERE invoice_number = \'' . $invoiceNum . '\''
		. ' OR secondary_ident = \'' . $invoiceNum . '\''
		;
		$database->setQuery( $query );
		$this->load($database->loadResult());
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
		global $database, $aecConfig;

		$query = 'SELECT invoice_number'
		. ' FROM #__acctexp_invoices'
		. ' WHERE id = \'' . $database->getEscaped( $this->id ) . '\''
		. ' OR secondary_ident = \'' . $database->getEscaped( $this->invoice_number ) . '\''
		;
		$database->setQuery( $query );

		$this->invoice_number = $database->loadResult();
	}

	function loadbySubscriptionId( $subscrid, $userid=null )
	{
		global $database;

		$query = 'SELECT `id`'
				. ' FROM #__acctexp_invoices'
				. ' WHERE `subscr_id` = \'' . $subscrid . '\''
				. ' ORDER BY `transaction_date` DESC'
				;

		if ( !empty( $userid ) ) {
			$query .= ' AND `userid` = \'' . $userid . '\'';
		}

		$database->setQuery( $query );
		$this->load( $database->loadResult() );
	}

	function hasDuplicate( $userid, $invoiceNum )
	{
		global $database;

		$query = 'SELECT count(*)'
				. ' FROM #__acctexp_invoices'
				. ' WHERE `userid` = ' . (int) $userid
				. ' AND `invoice_number` = \'' . $invoiceNum . '\''
				;
		$database->setQuery( $query );
		return $database->loadResult();
	}

	function computeAmount()
	{
		global $database;

		if ( !is_null( $this->usage ) && !( $this->usage == '' ) ) {
			$plan = new SubscriptionPlan( $database );
			$plan->load( $this->usage );

			$metaUser = new metaUser( $this->userid ? $this->userid : 0 );

			$recurring = '';

			switch ( $this->method ) {
				case 'none':
				case 'free':
					break;
				default:
					$pp = new PaymentProcessor();
					if ( $pp->loadName( $this->method ) ) {
						$pp->fullInit();
						$pp->exchangeSettingsByPlan( $plan );

						if ( $pp->is_recurring() ) {
							$recurring = $pp->is_recurring();
						} else {
							$recurring = 0;
						}

						if ( empty( $this->currency ) ) {
							$this->currency = isset( $pp->settings['currency'] ) ? $pp->settings['currency'] : '';
						}
					} else {
						// Log Error
						return;
					}
			}

			if ( $metaUser->hasSubscription ) {
				$return = $plan->SubscriptionAmount( $recurring, $metaUser->objSubscription, $metaUser );
			} else {
				$return = $plan->SubscriptionAmount( $recurring, false, $metaUser );
			}

			if ( $this->coupons ) {
				$cpsh = new couponsHandler();

				$sc = $this->coupons;

				$return['amount'] = $cpsh->applyCoupons( $return['amount'], $this->coupons, $metaUser );

				$this->coupons = $sc;
			}

			if ( is_array( $return['amount'] ) ) {
				// Check whether we have a trial amount and whether this invoice has had a trial with a payment already
				$this->amount = false;

				if ( isset( $return['amount']['amount1'] ) ) {
					if ( !is_null( $return['amount']['amount1'] )
					&& !( ( $this->amount == $return['amount']['amount1'] )
					&& !( strcmp( $this->transaction_date, '0000-00-00 00:00:00' ) === 0 ) ) ) {
						$this->amount = $return['amount']['amount1'];
					}
				}

				if ( $this->amount === false ) {
					if ( isset( $return['amount']['amount2'] ) ) {
						if ( !is_null( $return['amount']['amount2'] ) ) {
							$this->amount = $return['amount']['amount2'];
						}
					}
				}

				if ( $this->amount === false ) {
					if ( isset( $return['amount']['amount3'] ) ) {
						if ( !is_null( $return['amount']['amount3'] ) ) {
							$this->amount = $return['amount']['amount3'];
						}
					}
				}

				if ( $this->amount === false ) {
					$this->amount = '0.00';
				}
			} else {
				$this->amount = $return['amount'];
			}

			// We cannot afford to have this ever come out as null, so we will rather have it as gratis
			if ( empty( $this->amount ) ) {
				$this->amount = '0.00';
				$this->made_free = true;
			}

			if ( ( strcmp( $this->amount, '0.00' ) === 0 ) && !$recurring ) {
				$this->method = 'free';
				$this->made_free = true;
			} elseif ( strcmp( $this->method, 'free' ) === 0 ) {
				$this->method = 'error';
				// TODO: Log Error
			}

			$temp = $this->amount;
			$this->amount = AECToolbox::correctAmount( $this->amount );
		}
	}

	function create( $userid, $usage, $processor, $second_ident=null )
	{
		global $mosConfig_offset;

		$invoice_number			= $this->generateInvoiceNumber();

		$this->load(0);
		$this->invoice_number	= $invoice_number;

		if ( !is_null( $second_ident ) ) {
			$this->secondary_ident		= $second_ident;
		}

		$this->active			= 1;
		$this->fixed			= 0;
		$this->created_date		= date( 'Y-m-d H:i:s', time() + $mosConfig_offset*3600 );
		$this->transaction_date	= '0000-00-00 00:00:00';
		$this->userid			= $userid;
		$this->method			= $processor;
		$this->usage			= $usage;

		$this->computeAmount();

		$this->addParams( array( 'creator_ip' => $_SERVER['REMOTE_ADDR'] ), 'params', false );

		$this->storeload();
	}

	function generateInvoiceNumber( $maxlength = 16 )
	{
		global $database;

		$numberofrows	= 1;
		while ( $numberofrows ) {
			$inum =	'I' . substr( base64_encode( md5( rand() ) ), 0, $maxlength );
			// Check if already exists
			$query = 'SELECT count(*)'
					. ' FROM #__acctexp_invoices'
					. ' WHERE `invoice_number` = \'' . $inum . '\''
					. ' OR `secondary_ident` = \'' . $inum . '\''
					;
			$database->setQuery( $query );
			$numberofrows = $database->loadResult();
		}
		return $inum;
	}

	function processorResponse( $pp, $response, $responsestring='', $altvalidation=false )
	{
		global $database;

		$this->computeAmount();

		$plan = new SubscriptionPlan( $database );
		$plan->load( $this->usage );

		$post = aecPostParamClear( $_POST );
		$post['planparams'] = $plan->getProcessorParameters( $pp->id );

		$response['userid'] = $this->userid;

		$pp->exchangeSettingsByPlan( $plan, $plan->params );
		if ( $altvalidation ) {
			$response = $pp->instantvalidateNotification( $response, $post, $this );
		} else {
			$response = $pp->validateNotification( $response, $post, $this );
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

		if ( isset( $response['responsestring'] ) ) {
			$responsestring = $response['responsestring'];
			unset( $response['responsestring'] );
		}

		$metaUser = new metaUser( $this->userid );

		$mi_event = null;

		// Create history entry
		$history = new logHistory( $database );
		$history->entryFromInvoice( $this, $responsestring, $pp );

		$short = _AEC_MSG_PROC_INVOICE_ACTION_SH;
		$event = _AEC_MSG_PROC_INVOICE_ACTION_EV . "\n";

		if ( !empty( $response ) ) {
			foreach ( $response as $key => $value ) {
				$event .= $key . "=" . $value . "\n";
			}
		}

		$event	.= _AEC_MSG_PROC_INVOICE_ACTION_EV_STATUS;
		$tags	= 'invoice,processor';
		$level	= 2;
		$params = array( 'invoice_number' => $this->invoice_number );

		$forcedisplay = false;

		$event .= ' ';

		$notificationerror = null;

		$nothanks = 1;
		if ( $response['valid'] ) {
			$break = 0;

			// Only check on the amount on the first transaction to make up for coupon errors
			// TODO: This is very bad right here and a potential loophole, needs to be replaced with a more thorough check
			// ...once we have more precise invoices
			if ( !empty( $pp->settings['testmode'] ) ) {
				if ( isset( $response['amount_paid'] ) ) {
					if ( $response['amount_paid'] != $this->amount ) {
						// Amount Fraud, cancel payment and create error log addition
						$event	.= sprintf( _AEC_MSG_PROC_INVOICE_ACTION_EV_FRAUD, $response['amount_paid'], $this->amount );
						$tags	.= ',fraud_attempt,amount_fraud';
						$break	= 1;

						$notificationerror = 'Wrong amount for invoice. Amount provided: "' . $response['amount_paid'] . '"';
					}
				}

				if ( isset( $response['amount_currency'] ) ) {
					if ( $response['amount_currency'] != $this->currency ) {
						// Amount Fraud, cancel payment and create error log addition
						$event	.= sprintf( _AEC_MSG_PROC_INVOICE_ACTION_EV_CURR, $response['amount_currency'], $this->currency );
						$tags	.= ',fraud_attempt,currency_fraud';
						$break	= 1;

						$notificationerror = 'Wrong currency for invoice. Currency provided: "' . $response['amount_currency'] . '"';
					}
				}
			}

			if ( !$break ) {
				$renew = $this->pay( $multiplicator );
				if ( $renew === false ) {
					$notificationerror = 'Item Application failed. Please contact the System Administrator';

					// Something went wrong
					$event	.= _AEC_MSG_PROC_INVOICE_ACTION_EV_VALID_APPFAIL;
					$tags	.= ',payment,action_failed';
				} else {
					if ( !empty( $pp->info['notify_trail_thanks'] ) ) {
						$nothanks = 0;
					}
					$event	.= _AEC_MSG_PROC_INVOICE_ACTION_EV_VALID;
					$tags	.= ',payment,action';
				}
			}
		} else {
			if ( isset( $response['pending'] ) ) {
				if ( strcmp( $response['pending_reason'], 'signup' ) === 0 ) {
					if ( $plan->params['trial_free'] || ( $this->amount == '0.00' ) ) {
						$renew	= $this->pay( $multiplicator );
						$this->addParams( array( 'free_trial' => $response['pending_reason'] ), 'params', true );
						$event	.= _AEC_MSG_PROC_INVOICE_ACTION_EV_TRIAL;
						$tags	.= ',payment,action,trial';
					}
				} else {
					$this->addParams( array( 'pending_reason' => $response['pending_reason'] ), 'params', true );
					$event	.= sprintf( _AEC_MSG_PROC_INVOICE_ACTION_EV_PEND, $response['pending_reason'] );
					$tags	.= ',payment,pending' . $response['pending_reason'];

					$mi_event = '_payment_pending';
				}

				$this->storeload();
			} elseif ( isset( $response['cancel'] ) ) {
				$mi_event = '_payment_cancel';

				$event	.= _AEC_MSG_PROC_INVOICE_ACTION_EV_CANCEL;
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

					$event .= _AEC_MSG_PROC_INVOICE_ACTION_EV_USTATUS;
				}
			} elseif ( isset( $response['chargeback'] ) ) {
				$mi_event = '_payment_chargeback';

				$event	.= _AEC_MSG_PROC_INVOICE_ACTION_EV_CHARGEBACK;
				$tags	.= ',chargeback';
				$level = 128;

				if ( $metaUser->hasSubscription ) {
					if ( !empty( $this->subscr_id ) ) {
						$metaUser->moveFocus( $this->subscr_id );
					}

					$metaUser->focusSubscription->hold( $this );

					$event .= _AEC_MSG_PROC_INVOICE_ACTION_EV_USTATUS_HOLD;
				}
			} elseif ( isset( $response['chargeback_settle'] ) ) {
				$mi_event = '_payment_chargeback_settle';

				$event	.= _AEC_MSG_PROC_INVOICE_ACTION_EV_CHARGEBACK_SETTLE;
				$tags	.= ',chargeback_settle';
				$level = 8;
				$forcedisplay = true;

				if ( $metaUser->hasSubscription ) {
					if ( !empty( $this->subscr_id ) ) {
						$metaUser->moveFocus( $this->subscr_id );
					}

					$metaUser->focusSubscription->hold_settle( $this );

					$event .= _AEC_MSG_PROC_INVOICE_ACTION_EV_USTATUS_ACTIVE;
				}
			} elseif ( isset( $response['delete'] ) ) {
				$mi_event = '_payment_refund';

				$event	.= _AEC_MSG_PROC_INVOICE_ACTION_EV_REFUND;
				$tags	.= ',refund';
				if ( $metaUser->hasSubscription ) {
					if ( !empty( $this->subscr_id ) ) {
						$metaUser->moveFocus( $this->subscr_id );
					}

					$metaUser->focusSubscription->expire();
					$event .= _AEC_MSG_PROC_INVOICE_ACTION_EV_EXPIRED;
				}
			} elseif ( isset( $response['eot'] ) ) {
				$mi_event = '_payment_eot';

				$event	.= _AEC_MSG_PROC_INVOICE_ACTION_EV_EOT;
				$tags	.= ',eot';
			} elseif ( isset( $response['duplicate'] ) ) {
				$mi_event = '_payment_duplicate';

				$event	.= _AEC_MSG_PROC_INVOICE_ACTION_EV_DUPLICATE;
				$tags	.= ',duplicate';
			} elseif ( isset( $response['null'] ) ) {
				$mi_event = '_payment_null';

				$event	.= _AEC_MSG_PROC_INVOICE_ACTION_EV_NULL;
				$tags	.= ',null';
			} elseif ( isset( $response['error'] ) && isset( $response['errormsg'] ) ) {
				$mi_event = '_payment_error';

				$event	.= _AEC_MSG_PROC_INVOICE_ACTION_EV_U_ERROR . ' Error:' . $response['errormsg'] ;
				$tags	.= ',error';
				$level = 128;

				$notificationerror = $response['errormsg'];
			} else {
				$event	.= _AEC_MSG_PROC_INVOICE_ACTION_EV_U_ERROR;
				$tags	.= ',general_error';
				$level = 128;

				$notificationerror = 'General Error. Please contact the System Administrator.';
			}
		}

		if ( !empty( $mi_event ) && !empty( $this->usage ) ) {
			$objUsage = new SubscriptionPlan( $database );
			$objUsage->load( $this->usage );

			$objUsage->triggerMIs( $mi_event, $metaUser, null, $this, $response );
		}

		if ( isset( $response['explanation'] ) ) {
			$event .= " (" . $response['explanation'] . ")";
		}

		$eventlog = new eventLog( $database );
		$eventlog->issue( $short, $tags, $event, $level, $params, $forcedisplay );

		if ( !empty( $notificationerror ) ) {
			$pp->notificationError( $response, $pp->notificationError( $response, $notificationerror ) );
		} else {
			$pp->notificationSuccess( $response );
		}

		if ( !$nothanks ) {
			thanks( 'com_acctexp', $renew, ($pp === 0) );
		} else {
			exit;
		}
	}

	function pay( $multiplicator=1 )
	{
		global $database, $aecConfig;

		$metaUser = false;
		$new_plan = false;

		if ( !empty( $this->userid ) ) {
			$metaUser = new metaUser( $this->userid );
		}

		if ( !empty( $this->usage ) ) {
			$new_plan = new SubscriptionPlan( $database );
			$new_plan->load( $this->usage );
		}

		if ( is_object( $metaUser ) && is_object( $new_plan ) ) {
			if ( $metaUser->userid ) {
				if ( empty( $this->subscr_id ) ) {
					$metaUser->establishFocus( $new_plan, $this->method );

					$this->subscr_id = $metaUser->focusSubscription->id;
				} else {
					$metaUser->moveFocus( $this->subscr_id );
				}

				// Apply the Plan
				$application = $metaUser->focusSubscription->applyUsage( $this->usage, $this->method, 0, $multiplicator, $this );
			} else {
				$application = $new_plan->applyPlan( 0, $this->method, 0, $multiplicator, $this );
			}

			if ( $application === false ) {
				return false;
			}
		}

		if ( !empty( $this->conditions ) ) {
			$micro_integrations = false;

			if ( strpos( $this->conditions, 'mi_attendevents' ) ) {
				$micro_integration['name'] = 'mi_attendevents';
				$micro_integration['parameters'] = array( 'registration_id' => $this->substring_between( $this->conditions, '<registration_id>', '</registration_id>' ) );
				$micro_integrations = array();
				$micro_integrations[] = $micro_integration;
			}

			if ( is_array( $micro_integrations ) ) {
				foreach ( $micro_integrations as $micro_int ) {
					$mi = new microIntegration( $database );

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

				// See whether this coupon has micro integrations
				if ( empty( $cph->coupon->micro_integrations ) ) {
					continue;
				}

				foreach ( $cph->coupon->micro_integrations as $mi_id ) {
					$mi = new microIntegration( $database );

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
						if ( $mi->action( $metaUser, null, $this, $new_plan ) === false ) {
							if ( $aecConfig->cfg['breakon_mi_error'] ) {
								return false;
							}
						}
					} else {
						if ( $mi->action( false, null, $this, $new_plan ) === false ) {
							if ( $aecConfig->cfg['breakon_mi_error'] ) {
								return false;
							};
						}
					}
				}
			}
		}

		// We need to at least warn the admin if there is an invoice with nothing to do
		if ( empty( $this->usage ) && empty( $this->conditions ) && empty( $this->coupons ) ) {
			$short	= 'Nothing to do';
			$event	= _AEC_MSG_PROC_INVOICE_ACTION_EV_VALID_APPFAIL;
			$tags	= 'invoice,application,payment,action_failed';
			$params = array( 'invoice_number' => $this->invoice_number );

			$eventlog = new eventLog( $database );
			$eventlog->issue( $short, $tags, $event, 32, $params );
		}

		$this->setTransactionDate();

		return $application;
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
		global $database, $mosConfig_offset, $aecConfig;

		$tdate = strtotime( $this->transaction_date );
		$time_passed		= ( ( time() + $mosConfig_offset*3600 ) - $tdate ) / 3600;
		$transaction_date	= date( 'Y-m-d H:i:s', time() + $mosConfig_offset*3600 );

		if ( ( strcmp( $this->transaction_date, '0000-00-00 00:00:00' ) === 0 )
			|| empty( $tdate )
			|| ( $time_passed > $aecConfig->cfg['invoicecushion'] ) ) {
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

	function getFullVars()
	{
		global $database, $mosConfig_live_site;

		if ( is_array( $this->params ) ) {
			$int_var['params'] = $this->params;
		} else {
			$int_var['params'] = array();
		}

		// Filter non-processor params
		$nonproc = array( 'pending_reason', 'deactivated' );
		foreach ( $nonproc as $param ) {
			if ( isset( $int_var['params'][$param] ) ) {
				unset( $int_var['params'][$param] );
			}
		}

		$metaUser = new metaUser( $this->userid );

		$new_subscription = new SubscriptionPlan( $database );
		$new_subscription->load( $this->usage );

		$pp = new PaymentProcessor();
		if ( !$pp->loadName( strtolower( $this->method ) ) ) {
	 		// Nope, won't work buddy
		 	notAllowed( 'com_acctexp' );
		}

		$pp->init();
		$pp->getInfo();

		$int_var['planparams'] = $new_subscription->getProcessorParameters( $pp->id );

		if ( $pp->is_recurring() ) {
			$int_var['recurring'] = $pp->is_recurring();
		} else {
			$int_var['recurring'] = 0;
		}

		$amount = $new_subscription->SubscriptionAmount( $int_var['recurring'], $metaUser->objSubscription, $metaUser );

		if ( !empty( $this->coupons ) ) {
			$cph = new couponsHandler();

			$amount['amount'] = $cph->applyCoupons( $amount['amount'], $this->coupons, $metaUser );
		}

		$int_var['amount']		= $amount['amount'];
		$int_var['return_url']	= $amount['return_url'];
		$int_var['invoice']		= $this->invoice_number;
		$int_var['usage']		= $this->invoice_number;

		return $int_var;
	}

	function prepareProcessorLink( $InvoiceFactory=null )
	{
		global $database, $mosConfig_live_site;

		if ( is_array( $this->params ) ) {
			$int_var['params'] = $this->params;
		} else {
			$int_var['params'] = array();
		}

		// Filter non-processor params
		$nonproc = array( 'pending_reason', 'deactivated' );
		foreach ( $nonproc as $param ) {
			if ( isset( $int_var['params'][$param] ) ) {
				unset( $int_var['params'][$param] );
			}
		}

		$urladd = '';
		if ( $this->usage ) {
			$new_subscription = new SubscriptionPlan( $database );
			$new_subscription->load( $this->usage );

			$int_var['planparams'] = $new_subscription->getProcessorParameters( $InvoiceFactory->pp->id );

			if ( isset( $int_var['params']['userselect_recurring'] ) ) {
				$recurring = $InvoiceFactory->pp->is_recurring( $int_var['params']['userselect_recurring'], true );
			} else {
				$recurring = $InvoiceFactory->pp->is_recurring();
			}

			if ( $recurring ) {
				$int_var['recurring'] = $recurring;
			} else {
				$int_var['recurring'] = 0;
			}

			if ( $InvoiceFactory->metaUser->hasSubscription ) {
				$amount = $new_subscription->SubscriptionAmount( $int_var['recurring'], $InvoiceFactory->metaUser->objSubscription, $InvoiceFactory->metaUser );
			} else {
				$amount = $new_subscription->SubscriptionAmount( $int_var['recurring'], false, $InvoiceFactory->metaUser );
			}

			if ( !empty( $new_subscription->params['customthanks'] ) || !empty( $new_subscription->params['customtext_thanks'] ) ) {
				$urladd .= '&amp;u=' . $this->usage;
			}
		} else {
			$amount['amount'] = $this->amount;
			$int_var['recurring'] = 0;
		}

		if ( !empty( $this->coupons ) ) {
			$cph = new couponsHandler();

			$amount['amount'] = $cph->applyCoupons( $amount['amount'], $this->coupons, $InvoiceFactory->metaUser, $InvoiceFactory );
		}

		$int_var['amount'] = $amount['amount'];

		if ( !empty( $amount['return_url'] ) ) {
			$int_var['return_url'] = $amount['return_url'] . $urladd;
		} else {
			$int_var['return_url'] = AECToolbox::deadsureURL( 'index.php?option=com_acctexp&amp;task=thanks&amp;renew=0' . $urladd );
		}

		$int_var['invoice']		= $this->invoice_number;
		$int_var['usage']		= $this->invoice_number;

		// Assemble Checkout Response
		$return['var']		= $InvoiceFactory->pp->checkoutAction( $int_var, $InvoiceFactory->metaUser, $new_subscription, $this );
		$return['params']	= $InvoiceFactory->pp->getParamsHTML( $int_var['params'], $InvoiceFactory->pp->getParams( $int_var['params'] ) );

		if ( empty( $return['params'] ) ) {
			$return['params'] = null;
		}

		return $return;
	}

	function addCoupon( $couponcode )
	{
		if ( !empty( $this->coupons ) ) {
			$oldcoupons = $this->coupons;
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

	function removeCoupon( $couponcode )
	{
		$oldcoupons = $this->coupons;

		if ( in_array( $couponcode, $oldcoupons ) ) {
			foreach ( $oldcoupons as $id => $cc ) {
				if ( $cc == $couponcode ) {
					unset( $oldcoupons[$id] );
				}
			}

			$cph = new couponHandler();
			$cph->load( $couponcode );
			if ( $cph->coupon->id ) {
				$cph->decrementCount( $this );
			}
		}

		$this->coupons = $oldcoupons;
	}

	function savePostParams( $array )
	{
		unset( $array['task'] );
		unset( $array['option'] );
		unset( $array['invoice'] );

		$this->addParams( $array );
		return true;
	}

	function printInvoice($option)
	{
		global $mosConfig_sitename, $mosConfig_mailfrom, $database;


		if($this->usage != 2 && $this->usage != 4){

			$query = "SELECT * FROM #__vm_user_info WHERE user_id = ".$this->userid;
			$database->setQuery($query);
			$vm_user_details = $database->loadAssocList();
			$vm_user_details = $vm_user_details[0];

			$user = new metaUser($this->userid);
			$body = '';
			$body = str_replace("{inv_no}",$this->invoice_number,$body);
			$body = str_replace("{date}",date('D, jS M Y',strtotime($this->created_date)),$body);
			$body = str_replace("{name}",$user->cmsUser->name,$body);
			$body = str_replace("{username}",$user->cmsUser->username,$body);
			$body = str_replace("{company}",$vm_user_details['company'],$body);
			$body = str_replace("{address}",$vm_user_details['address_1'],$body);
			$body = str_replace("{city}",$vm_user_details['city'],$body);
			$body = str_replace("{state}",$vm_user_details['state'],$body);
			$body = str_replace("{postcode}",$vm_user_details['zip'],$body);
			$body = str_replace("{country}",$vm_user_details['country'],$body);
			$body = str_replace("{phone}",$vm_user_details['phone_1'],$body);
			$body = str_replace("{email}",$user->cmsUser->email,$body);
			if($this->usage == 3){
				$body = str_replace("{invoice_desc}","",$body);
			}else{
				$body = str_replace("{invoice_desc}","",$body);
			}

			$body = str_replace("{cost}","$".$this->amount,$body);
			$body = str_replace("{total}","$".$this->amount,$body);
			$body = str_replace("{gst}","$".number_format($this->amount / 11,2),$body);

			$subject = "".$this->invoice_number;

			$query = "SELECT * FROM #__vm_vendor WHERE vendor_id = 1";
			$database->setQuery($query);
			$vm_vendor = $database->loadAssocList();
			$vm_vendor = $vm_vendor[0];

			if($this->method == 'transfer'){
				if($this->transaction_date == '0000-00-00 00:00:00'){

					mosMail($mosConfig_mailfrom, $mosConfig_sitename, $user->cmsUser->email, $subject, $body, 1);
					mosMail($mosConfig_mailfrom, $mosConfig_sitename, $vm_vendor['contact_email'], $subject, $body, 1);
				}
			}else{
				if($this->transaction_date != '0000-00-00 00:00:00'){

					mosMail($mosConfig_mailfrom, $mosConfig_sitename, $user->cmsUser->email, $subject, $body, 1);
					mosMail($mosConfig_mailfrom, $mosConfig_sitename, $vm_vendor['contact_email'], $subject, $body, 1);
				}
			}
		}
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
		$this->mosDBTable( '#__acctexp_subscr', 'id', $db );
	}

	function declareParamFields()
	{
		return array( 'used_plans', 'params', 'customparams' );
	}

	function check()
	{
		if ( isset( $this->used_plans ) ) {
			unset( $this->used_plans );
		}

		if ( isset( $this->previous_plan ) ) {
			unset( $this->previous_plan );
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

	function getSubscriptionID( $userid, $usage=null, $primary=1, $similar=false )
	{
		global $database;

		$query = 'SELECT `id`'
				. ' FROM #__acctexp_subscr'
				. ' WHERE `userid` = \'' . $userid . '\''
				;

		if ( !empty( $usage ) ) {
			if ( $similar ) {
				$plan = new SubscriptionPlan( $database );
				$plan->load( $usage );

				if ( !empty( $plan->params['similarplans'] ) && !empty( $plan->params['equalplans'] ) ) {
					$similar = $plan->params['similarplans'];
					$equalpl = $plan->params['equalplans'];

					$allplans = array_merge( $similar, $equalpl, array( $usage ) );
				} else {
					$allplans = array( $usage );
				}

				foreach ( $allplans as $apid => $pid ) {
					$allplans[$apid] = '`plan` = \'' . $pid . '\'';
				}

				$query .= ' AND ' . implode( ' OR ', $allplans );
			} else {
				$query .= ' AND ' . '`plan` = \'' . $usage . '\'';
			}
		}

		if ( $primary ) {
			$query .= ' AND `primary` = \'1\'';
		} elseif ( $primary === false ) {
			$query .= ' AND `primary` = \'0\'';
		}

		$database->setQuery( $query );

		$subscriptionid = $database->loadResult();

		if ( empty( $subscriptionid ) && !$similar ) {
			return $this->getSubscriptionID( $userid, $usage, $primary, true );
		}

		return $subscriptionid;
	}

	function makePrimary()
	{
		global $database;

		$query = 'UPDATE #__acctexp_subscr'
				. ' SET `primary` = \'0\''
				. ' WHERE `userid` = \'' . $this->userid . '\''
				;
		$database->setQuery( $query );
		$database->query();

		$this->primary = 1;
		$this->check();
		$this->store();
	}

	function manualVerify()
	{
		if ( $this->is_expired() ) {
			mosRedirect( AECToolbox::deadsureURL( 'index.php?option=com_acctexp&task=expired&userid=' . (int) $this->userid ), false, true );
			return false;
		} else {
			return true;
		}
	}

	function createNew( $userid, $processor, $pending, $primary=1 )
	{
		global $mosConfig_offset;

		$this->userid		= $userid;
		$this->primary		= $primary;
		$this->signup_date	= date( 'Y-m-d H:i:s', time() + $mosConfig_offset*3600 );
		$this->expiration	= date( 'Y-m-d H:i:s', time() + $mosConfig_offset*3600 );
		$this->status		= $pending ? 'Pending' : 'Active';
		$this->type			= $processor;

		$this->check();
		$this->store();
		$this->id = $this->getMax();
	}

	function is_expired( $offset=false )
	{
		global $database, $mosConfig_offset, $aecConfig;

		if ( !($this->expiration === '9999-12-31 00:00:00') ) {
			$expiration_cushion = str_pad( $aecConfig->cfg['expiration_cushion'], 2, '0', STR_PAD_LEFT );

			if ( $offset ) {
				$expstamp = strtotime( ( '-' . $offset . ' days' ), strtotime( $this->expiration ) );
			} else {
				$expstamp = strtotime( ( '+' . $expiration_cushion . ' hours' ), strtotime( $this->expiration ) );
			}

			if ( ( $expstamp > 0 ) && ( ( $expstamp - ( time() + $mosConfig_offset*3600 ) ) < 0 ) ) {
				return true;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}

	function setExpiration( $unit, $value, $extend )
	{
		global $mainframe, $mosConfig_offset;

		$now = time() + $mosConfig_offset*3600;

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
		global $database, $mosConfig_offset, $aecConfig;

		if ( $this->expiration ) {
			$alert['level']		= -1;
			$alert['daysleft']	= 0;

			$expstamp = strtotime( $this->expiration );

			// Get how many days left to expire (3600 sec = 1 hour)
			$alert['daysleft']	= round( ( $expstamp - ( time() + $mosConfig_offset*3600 ) ) / ( 3600 * 24 ) );

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
		global $mosConfig_live_site, $database, $aecConfig;

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
			$expire = $this->expire();

			if ( $expire ) {
				if ( $metaUser !== false ) {
					$metaUser->setTempAuth();
				}
				mosRedirect( AECToolbox::deadsureURL( 'index.php?option=com_acctexp&task=expired&userid=' . $this->userid ), false, true );
			}
		} elseif ( ( strcmp( $this->status, 'Pending' ) === 0 ) || $block ) {
			if ( $metaUser !== false ) {
				$metaUser->setTempAuth();
			}
			mosRedirect( AECToolbox::deadsureURL( 'index.php?option=com_acctexp&task=pending&userid=' . $this->userid ), false, true );
		} elseif ( ( strcmp( $this->status, 'Hold' ) === 0 ) || $block ) {
			mosRedirect( AECToolbox::deadsureURL( 'index.php?option=com_acctexp&task=hold&userid=' . $this->userid ), false, true );
		}
	}

	function verify( $block )
	{
		global $mosConfig_live_site, $database, $aecConfig;

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
			$expire = $this->expire();

			if ( $expire ) {
				return 'expired';
			}
		} elseif ( ( strcmp( $this->status, 'Pending' ) === 0 ) || $block ) {
			return 'pending';
		} elseif ( ( strcmp( $this->status, 'Hold' ) === 0 ) || $block ) {
			return 'hold';
		}

		return true;
	}

	function expire( $overridefallback=false )
	{
		global $database;

		// Users who are excluded cannot expire
		if ( strcmp( $this->status, 'Excluded' ) === 0 ) {
			return false;
		}

		// Load plan variables, otherwise load dummies
		if ( $this->plan ) {
			$subscription_plan = new SubscriptionPlan( $database );
			$subscription_plan->load( $this->plan );
		} else {
			$subscription_plan = false;
		}

		// Move the focus Subscription
		$metaUser = new metaUser( $this->userid );
		$metaUser->moveFocus( $this->id );

		// Recognize the fallback plan, if not overridden
		if ( !empty( $subscription_plan->params['fallback'] ) && !$overridefallback ) {
			if ( $subscription_plan !== false ) {
				$mih = new microIntegrationHandler();
				$mih->userPlanExpireActions( $metaUser, $subscription_plan );
			}

			$this->applyUsage( $subscription_plan->params['fallback'], 'none', 1 );
			$this->load( $this->id );
			return false;
		} else {
			// Set a Trial flag if this is an expired Trial for further reference
			if ( strcmp( $this->status, 'Trial' ) === 0 ) {
				$this->addParams( array( 'trialflag' => 1 ) );
			} elseif ( is_array( $this->params ) ) {
				if ( in_array( 'trialflag', $this->params ) ) {
					$this->delParams( array( 'trialflag' ) );
				}
			}

			if ( !( strcmp( $this->status, 'Expired' ) === 0 ) || !( strcmp( $this->status, 'Closed' ) === 0 ) ) {
				$this->status = 'Expired';
				$this->check();
				$this->store();
			} else {
				return false;
			}

			// Call Expiration MIs
			if ( $subscription_plan !== false ) {
				$mih = new microIntegrationHandler();
				$mih->userPlanExpireActions( $metaUser, $subscription_plan );
			}

			return true;
		}
	}

	function cancel( $invoice=null )
	{
		global $database;

		// Since some processors do not notify each period, we need to check whether the expiration
		// lies too far in the future and cut it down to the end of the period the user has paid

		if ( $this->plan ) {
			global $mosConfig_offset;

			$subscription_plan = new SubscriptionPlan( $database );
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
			$now = time() + $mosConfig_offset*3600;

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
		$this->check();
		$this->store();
	}

	function applyUsage( $usage = 0, $processor = 'none', $silent = 0, $multiplicator = 1, $invoice=null )
	{
		global $database;

		if ( !$usage ) {
			$usage = $this->plan;
		}

		$new_plan = new SubscriptionPlan( $database );
		$new_plan->load( $usage );

		if ( $new_plan->id ) {
			return $new_plan->applyPlan( $this->userid, $processor, $silent, $multiplicator, $invoice );
		} else {
			return false;
		}
	}

	function sendEmailRegistered( $renew )
	{
		global $database, $acl, $mainframe;

		$langPath = $mainframe->getCfg( 'absolute_path' ) . '/components/com_acctexp/com_acctexp_language/';
		if ( file_exists( $langPath . $mainframe->getCfg( 'mosConfig_lang' ) . '.php' ) ) {
			include_once( $langPath . $mainframe->getCfg( 'mosConfig_lang' ) . '.php' );
		} else {
			include_once( $langPath . 'english.php' );
		}

		$free = ( strcmp( strtolower( $this->type ), 'none' ) == 0 || strcmp( strtolower( $this->type ), 'free' ) == 0 );

		$urow = new mosUser( $database );
		$urow->load( $this->userid );

		$plan = new SubscriptionPlan( $database );
		$plan->load( $this->plan );

		$name			= $urow->name;
		$email			= $urow->email;
		$username		= $urow->username;
		$pwd			= $urow->password;
		$activationcode	= $urow->activation;

		$message = sprintf( _ACCTEXP_MAILPARTICLE_GREETING, $name );

		// Assemble E-Mail Subject & Message
		if ( $renew ) {
			$subject = sprintf( _ACCTEXP_SEND_MSG_RENEW, $name, $mainframe->getCfg( 'sitename' ) );

			$message .= sprintf( _ACCTEXP_MAILPARTICLE_THANKSREN, $mainframe->getCfg( 'sitename' ) );

			if ( $plan->email_desc ) {
				$message .= "\n\n" . $plan->email_desc . "\n\n";
			} else {
				$message .= " ";
			}

			if ( $free ) {
				$message .= sprintf( _ACCTEXP_MAILPARTICLE_LOGIN, $mainframe->getCfg( 'live_site' ) );
			} else {
				$message .= _ACCTEXP_MAILPARTICLE_PAYREC . " "
				. sprintf( _ACCTEXP_MAILPARTICLE_LOGIN, $mainframe->getCfg( 'live_site' ) );
			}
		} else {
			$subject = sprintf( _ACCTEXP_SEND_MSG, $name, $mainframe->getCfg( 'sitename' ) );

			$message .= sprintf(_ACCTEXP_MAILPARTICLE_THANKSREG, $mainframe->getCfg( 'sitename' ) );

			if ( $plan->email_desc ) {
				$message .= "\n\n" . $plan->email_desc . "\n\n";
			} else {
				$message .= " ";
			}

			if ( $free ) {
				$message .= sprintf( _ACCTEXP_MAILPARTICLE_LOGIN, $mainframe->getCfg( 'live_site' ) );
			} else {
				$message .= _ACCTEXP_MAILPARTICLE_PAYREC . " "
				. sprintf( _ACCTEXP_MAILPARTICLE_LOGIN, $mainframe->getCfg( 'live_site' ) );
			}
		}

		$message .= _ACCTEXP_MAILPARTICLE_FOOTER;

		$subject = html_entity_decode( $subject, ENT_QUOTES );
		$message = html_entity_decode( $message, ENT_QUOTES );

		// Send email to user
		if ( $mainframe->getCfg( 'mailfrom' ) != '' && $mainframe->getCfg( 'fromname' ) != '' ) {
			$adminName2		= $mainframe->getCfg( 'fromname' );
			$adminEmail2	= $mainframe->getCfg( 'mailfrom' );
		} else {
			$query = 'SELECT `name`, `email`'
					. ' FROM #__users'
					. ' WHERE `usertype` = \'superadministrator\''
					;
			$database->setQuery( $query );
			$rows = $database->loadObjectList();
			$row2 = $rows[0];

			$adminName2		= $row2->name;
			$adminEmail2	= $row2->email;
		}

		mosMail( $adminEmail2, $adminName2, $email, $subject, $message );

		// Send notification to all administrators
		$aecUser = AECToolbox::_aecIP();

		if ( $renew ) {
			$subject2 = sprintf( _ACCTEXP_SEND_MSG_RENEW, $name, $mainframe->getCfg( 'sitename' ) );
			$message2 = sprintf( _ACCTEXP_ASEND_MSG_RENEW, $adminName2, $mainframe->getCfg( 'sitename' ), $name, $email, $username, $plan->id, $plan->name, $aecUser['ip'], $aecUser['isp'] );
		} else {
			$subject2 = sprintf( _ACCTEXP_SEND_MSG, $name, $mainframe->getCfg( 'sitename' ) );
			$message2 = sprintf( _ACCTEXP_ASEND_MSG, $adminName2, $mainframe->getCfg( 'sitename' ), $name, $email, $username, $plan->id, $plan->name, $aecUser['ip'], $aecUser['isp'] );
		}

		$subject2 = html_entity_decode( $subject2, ENT_QUOTES );
		$message2 = html_entity_decode( $message2, ENT_QUOTES );

		// get superadministrators id
		$admins = $acl->get_group_objects( 25, 'ARO' );

		foreach ( $admins['users'] as $id ) {
			$query = 'SELECT `email`, `sendEmail`'
					. ' FROM #__users'
					. ' WHERE `id` = \'' . $id . '\''
					;
			$database->setQuery( $query );
			$rows = $database->loadObjectList();

			$row = $rows[0];

			if ( $row->sendEmail ) {
				mosMail( $adminEmail2, $adminName2, $row->email, $subject2, $message2 );
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
					$paramname = substr( strtoupper( $name ), strlen( $flag_name ) + 1 );
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
	 * Check which CMS system is running
	 * @return string
	 */
	function getCMSName()
	{
		global $mosConfig_absolute_path;

		$filename	= $mosConfig_absolute_path . '/includes/version.php';

		if ( file_exists( $filename ) ) {
			$originalFileHandle = fopen( $filename, 'r' ) or die ( "Cannot open $filename<br />" );
			// Transfer File into variable
			$Data = fread( $originalFileHandle, filesize( $filename ) );
			fclose( $originalFileHandle );

			if ( strpos( $Data, '@package Joomla' ) ) {
				return 'Joomla';
			} elseif ( strpos( $Data, '@package Mambo' ) ) {
				return 'Mambo';
			} else {
				return 'UNKNOWN'; // mic: DO NOT CHANGE THIS VALUE!! (used later)
			}
		} elseif (  aecJoomla15check() ) {
			return 'Joomla15';
		}
	}

	/**
	 * Check whether a component is installed
	 * @return Bool
	 */
	function detect_component( $component )
	{
		global $database, $mainframe, $aecConfig;

		$tables	= array();
		$tables	= $database->getTableList();

		if ( !empty( $aecConfig->cfg['bypassintegration'] ) ) {
			$bypass = str_replace( ',', ' ', $aecConfig->cfg['bypassintegration'] );

			$overrides = explode( ' ', $bypass );

			foreach ( $overrides as $i => $c ) {
				$overrides[$i] = trim($c);
			}

			if ( in_array( 'CB', $overrides ) ) {
				$overrides[] = 'CB1.2';
			}

			if ( in_array( $component, $overrides ) ) {
				return false;
			}
		}

		$pathCB		= $mainframe->getCfg( 'absolute_path' ) . '/components/com_comprofiler';
		$pathSMF	= $mainframe->getCfg( 'absolute_path' ) . '/administrator/components/com_smf';
		switch ( $component ) {
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
				return file_exists( $mainframe->getCfg( 'absolute_path' ) . '/modules/mod_comprofilermoderator.php' );
				break;

			case 'UE': // User Extended
				return in_array( $mainframe->getCfg( 'dbprefix' ) . 'user_extended', $tables );
				break;

			case 'SMF': // Simple Machines Forum
				return file_exists( $pathSMF . '/config.smf.php') || file_exists( $pathSMF . '/smf.php' );
				break;

			case 'VM': // VirtueMart
				return in_array( $mainframe->getCfg( 'dbprefix' ) . 'vm_orders', $tables );
				break;

			case 'JACL': // JACL
				return in_array( $mainframe->getCfg( 'dbprefix' ) . 'jaclplus', $tables );
				break;

			case 'UHP2':
				return file_exists( $mainframe->getCfg( 'absolute_path' ) . '/modules/mod_uhp2_manage.php' );
				break;

			case 'JUSER':
				return file_exists( $mainframe->getCfg( 'absolute_path' ) . '/components/com_juser/juser.php' );
				break;
		}
	}

	/**
	 * Return the list of group id with lower priviledge
	 * @parameter group id
	 * @return string
	 */
	function getLowerACLGroup( $group_id )
	{
		global $database;

		$group_list	= array();
		$groups		= '';

		$query = 'SELECT g2.' . ( aecJoomla15check() ? 'id' : 'group_id' )  . ''
				. ' FROM #__core_acl_aro_groups AS g1'
				. ' INNER JOIN #__core_acl_aro_groups AS g2 ON g1.lft >= g2.lft AND g1.lft <= g2.rgt'
				. ' WHERE g1.' . ( aecJoomla15check() ? 'id' : 'group_id' )  . ' = ' . $group_id
				. ' GROUP BY g2.' . ( aecJoomla15check() ? 'id' : 'group_id' )  . ''
				. ' ORDER BY g2.lft'
				;
		$database->setQuery( $query );
		$rows = $database->loadObjectList();

		for( $i = 0, $n = count( $rows ); $i < $n; $i++ ) {
			$row = &$rows[$i];
			if ( aecJoomla15check() ) {
				$group_list[$i] = $row->id;
			} else {
				$group_list[$i] = $row->group_id;
			}
		}

		if ( count( $group_list ) > 0 ) {
			return $group_list;
		} else {
			return array();
		}
	}
}

class AECfetchfromDB
{
	function UserIDfromInvoiceNumber( $invoice_number )
	{
		global $database;

		$query = 'SELECT `userid`'
				. ' FROM #__acctexp_invoices'
				. ' WHERE `invoice_number` = \'' . $invoice_number . '\''
				;
		$database->setQuery( $query );
		return $database->loadResult();
	}

	function InvoiceIDfromNumber( $invoice_number, $userid = 0, $override_active = false )
	{
		global $database;

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
			$query .= ' AND `userid` = \'' . $userid . '\'';
		}

		$database->setQuery( $query );
		return $database->loadResult();
	}

	function lastUnclearedInvoiceIDbyUserID( $userid )
	{
		global $database;

		$query = 'SELECT `invoice_number`'
				. ' FROM #__acctexp_invoices'
				. ' WHERE `userid` = \'' . (int) $userid . '\''
				. ' AND `transaction_date` = \'0000-00-00 00:00:00\''
				. ' AND `active` = \'1\''
				;
		$database->setQuery( $query );
		return $database->loadResult();
	}

	function lastClearedInvoiceIDbyUserID( $userid, $planid=0 )
	{
		global $database;

		$query = 'SELECT id'
				. ' FROM #__acctexp_invoices'
				. ' WHERE `userid` = \'' . (int) $userid . '\''
				;

		if ( $planid ) {
			$query .= ' AND `usage` = \'' . (int) $planid . '\'';
		}

		$query .= ' ORDER BY `transaction_date` DESC';

		$database->setQuery( $query );
		return $database->loadResult();
	}

	function InvoiceCountbyUserID( $userid )
	{
		global $database;

		$query = 'SELECT count(*)'
				. ' FROM #__acctexp_invoices'
				. ' WHERE `userid` = \'' . (int) $userid . '\''
				. ' AND `active` = \'1\''
				;
		$database->setQuery( $query );
		return $database->loadResult();
	}

	function SubscriptionIDfromUserID( $userid )
	{
		global $database;

		$query = 'SELECT `id`'
				. ' FROM #__acctexp_subscr'
				. ' WHERE `userid` = \'' . (int) $userid . '\''
				. ' ORDER BY `primary` DESC'
				;
		$database->setQuery( $query );
		return $database->loadResult();
	}

	function UserIDfromSubscriptionID( $susbcriptionid )
	{
		global $database;

		$query = 'SELECT `userid`'
				. ' FROM #__acctexp_subscr'
				. ' WHERE `id` = \'' . (int) $susbcriptionid . '\''
				. ' ORDER BY `primary` DESC'
				;
		$database->setQuery( $query );
		return $database->loadResult();
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
	function _aecCurrencyField( $currMain = false, $currGen = false, $currOth = false, $list_only = false )
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
					$currency_code_list[] = mosHTML::makeOption( $currency, constant( '_CURRENCY_' . $currency ) );
				}

				$currency_code_list[] = mosHTML::makeOption( '" disabled="disabled', '- - - - - - - - - - - - - -' );
			}
		}

		return $currency_code_list;
	}

	function _aecNumCurrency( $string )
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

	function _aecCurrencyExp( $string )
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

	/**
	 * get user ip & isp
	 *
	 * @return array w/ values
	 */
	function _aecIP()
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
		global $mosConfig_live_site;

		return '<a href="' .  $mosConfig_live_site . '/administratorindex2.php?option=com_acctexp&amp;task=' . $task . '" title="' . $text . '">' . $text . '</a>';
	}

	/**
	 * Return a URL based on the sef and user settings
	 * @parameter url
	 * @return string
	 */
	function deadsureURL( $url, $secure=false, $internal=false )
	{
		global $mosConfig_live_site, $mosConfig_absolute_path, $database, $aecConfig;

		if ( $aecConfig->cfg['override_reqssl'] ) {
			$secure = false;
		}

		if ( $aecConfig->cfg['simpleurls'] ) {
			$new_url = $mosConfig_live_site . $url;
		} else {
			if ( !strrpos( strtolower( $url ), 'itemid' ) ) {
				global $Itemid;
				if ( $Itemid ) {
					$url .= '&amp;Itemid=' . $Itemid;
				} else {
					$url .= '&amp;Itemid=';
				}
			}

			if ( !function_exists( 'sefRelToAbs' ) ) {
				include_once( $mosConfig_absolute_path . '/includes/sef.php' );
			}

			$new_url = sefRelToAbs( $url );

			if ( !( strpos( $new_url, $mosConfig_live_site ) === 0 ) ) {
				// look out for malformed live_site
				if ( strpos( $mosConfig_live_site, '/' ) === strlen( $mosConfig_live_site ) ) {
					$new_url = substr( $mosConfig_live_site, 0, -1 ) . $new_url;
				} else {
					// It seems we have a sefRelToAbs malfunction (subdirectory is not appended)
					$metaurl = explode( '/', $mosConfig_live_site );
					$rooturl = $metaurl[0] . '//' . $metaurl[2];

					// Replace root to include subdirectory - if all fails, just prefix the live site
					if ( strpos( $new_url, $rooturl ) === 0 ) {
						$new_url = $mosConfig_live_site . substr( $new_url, strlen( $rooturl ) );
					} else {
						$new_url = $mosConfig_live_site . $new_url;
					}
				}
			}
		}

		if ( $secure && ( strpos( $new_url, 'https:' ) !== 0 ) ) {
			$new_url = str_replace( 'http:', 'https:', $new_url );
		}

		if ( $internal ) {
			$new_url = str_replace( '&amp;', '&', $new_url );
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
		global $database, $aecConfig;

		$heartbeat = new aecHeartbeat( $database );
		$heartbeat->frontendping();

		$query = 'SELECT id'
		. ' FROM #__users'
		. ' WHERE username = \'' . aecEscape( $username, array( 'string', 'badchars' ) ) . '\''
		;
		$database->setQuery( $query );
		$id = $database->loadResult();

		$metaUser = new metaUser( $id );

		if ( $metaUser->hasSubscription ) {
			$metaUser->objSubscription->verifyLogin( $metaUser->cmsUser->block, $metaUser );
		} else {
			if ( $aecConfig->cfg['require_subscription'] ) {
				if ( $aecConfig->cfg['entry_plan'] ) {
					$payment_plan = new SubscriptionPlan( $database );
					$payment_plan->load( $aecConfig->cfg['entry_plan'] );

					$metaUser->establishFocus( $payment_plan, 'Free' );

					return AECToolbox::VerifyUsername( $username );
				} else {
					$invoices = AECfetchfromDB::InvoiceCountbyUserID( $metaUser->userid );

					if ( $invoices ) {
						$invoice = AECfetchfromDB::lastUnclearedInvoiceIDbyUserID( $metaUser->userid );

						if ( $invoice ) {
							$metaUser->setTempAuth();
							mosRedirect( AECToolbox::deadsureURL( 'index.php?option=com_acctexp&task=pending&userid=' . $id ), false, true );
							return null;
						}
					}

					$metaUser->setTempAuth();
					mosRedirect( AECToolbox::deadsureURL( 'index.php?option=com_acctexp&task=subscribe&userid=' . $id . '&intro=1' ), false, true );
					return null;
				}
			}
		}
		return true;
	}

	function VerifyUser( $username )
	{
		global $database, $aecConfig;

		$heartbeat = new aecHeartbeat( $database );
		$heartbeat->frontendping();

		$query = 'SELECT id'
		. ' FROM #__users'
		. ' WHERE username = \'' . aecEscape( $username, array( 'string', 'badchars' ) ) . '\''
		;
		$database->setQuery( $query );
		$id = $database->loadResult();

		$metaUser = new metaUser( $id );

		if ( $metaUser->hasSubscription ) {
			$result = $metaUser->objSubscription->verify( $metaUser->cmsUser->block );

			if ( ( $result == 'expired' ) || ( $result == 'pending' ) ) {
				$metaUser->setTempAuth();
			}

			return $result;
		} else {
			if ( $aecConfig->cfg['require_subscription'] ) {
				if ( $aecConfig->cfg['entry_plan'] ) {
					$payment_plan = new SubscriptionPlan( $database );
					$payment_plan->load( $aecConfig->cfg['entry_plan'] );

					$metaUser->establishFocus( $payment_plan, 'Free' );

					return AECToolbox::VerifyUser( $username );
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
		}
		return true;
	}

	function saveUserRegistration( $option, $var, $internal=false, $overrideActivation=false, $overrideEmails=false )
	{
		global $database, $mainframe, $task, $acl, $aecConfig; // Need to load $acl for Joomla and CBE

		// Let CB/JUSER think that everything is going fine
		if ( GeneralInfoRequester::detect_component( 'CB' ) || GeneralInfoRequester::detect_component( 'CBE' ) ) {
			if ( GeneralInfoRequester::detect_component( 'CBE' ) || $overrideActivation ) {
				global $ueConfig;
			}

			$savetask	= $task;
			$_REQUEST['task']	= 'done';
			include_once ( $mainframe->getCfg( 'absolute_path' ) . '/components/com_comprofiler/comprofiler.php' );
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
			global $mosConfig_absolute_path;

			$savetask	= $task;
			$task		= 'blind';
			include_once( $mainframe->getCfg( 'absolute_path' ) . '/components/com_juser/juser.php' );
			include_once( $mosConfig_absolute_path .'/administrator/components/com_juser/juser.class.php' );
			$task		= $savetask;
		}

		// For joomla and CB, we must filter out some internal variables before handing over the POST data
		$badbadvars = array( 'userid', 'method_name', 'usage', 'processor', 'recurring', 'currency', 'amount', 'invoice', 'id', 'gid' );
		foreach ( $badbadvars as $badvar ) {
			if ( isset( $var[$badvar] ) ) {
				unset( $var[$badvar] );
			}
		}

		$_POST = $var;

		$var['username'] = aecEscape( $var['username'], array( 'string', 'badchars' ) );

		if ( GeneralInfoRequester::detect_component( 'CB' ) || GeneralInfoRequester::detect_component( 'CBE' ) ) {
			// This is a CB registration, borrowing their code to save the user
			@saveRegistration( $option );
		} elseif ( GeneralInfoRequester::detect_component( 'JUSER' ) ) {
			// This is a JUSER registration, borrowing their code to save the user
			saveRegistration( $option );

			$query = 'SELECT `id`'
					. ' FROM #__users'
					. ' WHERE `username` = \'' . $var['username'] . '\''
					;
			$database->setQuery( $query );
			$uid = $database->loadResult();
			JUser::saveUser_ext( $uid );
			//synchronize dublicate user data
			$query = 'SELECT `id`' .
					' FROM #__juser_integration' .
					' WHERE `published` = \'1\'' .
					' AND `export_status` = \'1\'';
			$database->setQuery( $query );
			$components = $database->loadObjectList();
			if ( !empty( $components ) ) {
				foreach ( $components as $component ) {
					$synchronize = require_integration( $component->id );
					$synchronize->synchronizeFrom( $uid );
				}
			}
		} else {
			// This is a joomla registration, borrowing their code to save the user
			global $mosConfig_useractivation, $mosConfig_sitename, $mosConfig_live_site;

			if ( aecJoomla15check() ) {
				global $mainframe;

				// Check for request forgeries
				JRequest::checkToken() or die( 'Invalid Token' );

				// Get required system objects
				$user 		= clone(JFactory::getUser());
				$pathway 	=& $mainframe->getPathway();
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
				if (!$user->bind( JRequest::get('post'), 'usertype' )) {
					JError::raiseError( 500, $user->getError());

					unset($_POST);
					subscribe();
					return false;
				}

				// Set some initial user values
				$user->set('id', 0);
				$user->set('usertype', '');
				$user->set('gid', $authorize->get_group_id( '', $newUsertype, 'ARO' ));

				// TODO: Should this be JDate?
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

				$row = $user;
			} else {
				// simple spoof check security
				if ( function_exists( 'josSpoofCheck' ) && !$internal ) {
					josSpoofCheck();
				}

				$row = new mosUser( $database );

				if ( !$row->bind( $_POST, 'usertype' )) {
					mosErrorAlert( $row->getError() );
				}

				mosMakeHtmlSafe( $row );

				$row->id 		= 0;
				$row->usertype 	= '';
				$row->gid 		= $acl->get_group_id( 'Registered', 'ARO' );

				if ( ( $mosConfig_useractivation == 1 ) && !$overrideActivation ) {
					$row->activation = md5( mosMakePassword() );
					$row->block = '1';
				}

				if ( !$row->check() ) {
					echo '<script>alert(\''
					. html_entity_decode( $row->getError() )
					. '\');window.history.go(-1);</script>' . "\n";
					exit();
				}

				$pwd 				= $row->password;
				$row->password 		= md5( $row->password );

				$row->registerDate 	= date( 'Y-m-d H:i:s' );

				if ( !$row->store() ) {
					echo '<script>alert(\''
					. html_entity_decode( $row->getError())
					. '\');window.history.go(-1);</script>' . "\n";
					exit();
				}
				$row->checkin();
			}

			$mih = new microIntegrationHandler();
			$mih->userchange($row, $_POST, 'registration');

			$name 		= $row->name;
			$email 		= $row->email;
			$username 	= $row->username;

			$subject 	= sprintf ( _AEC_SEND_SUB, $name, $mainframe->getCfg( 'sitename' ) );
			$subject 	= html_entity_decode( $subject, ENT_QUOTES );
			if ( aecJoomla15check() ) {
				$usersConfig = &JComponentHelper::getParams( 'com_users' );
				$activation = $usersConfig->get('useractivation');
			} else {
				$activation = $mainframe->getCfg( 'useractivation' );
			}

			if ( ( $activation == 1 ) && !$overrideActivation ) {
				$message = sprintf( _AEC_USEND_MSG_ACTIVATE, $name, $mosConfig_sitename, $mosConfig_live_site."index.php?option=com_registration&task=activate&activation=".$row->activation, $mosConfig_live_site, $username, $pwd );
			} else {
				$message = sprintf( _AEC_USEND_MSG, $name, $mosConfig_sitename, $mosConfig_live_site );
			}

			$message = html_entity_decode( $message, ENT_QUOTES );

			// check if Global Config `mailfrom` and `fromname` values exist
			if ( $mainframe->getCfg( 'mailfrom' ) != '' && $mainframe->getCfg( 'fromname' ) != '' ) {
				$adminName2 	= $mainframe->getCfg( 'fromname' );
				$adminEmail2 	= $mainframe->getCfg( 'mailfrom' );
			} else {
				// use email address and name of first superadmin for use in email sent to user
				$query = 'SELECT `name`, `email`'
						. ' FROM #__users'
						. ' WHERE LOWER( usertype ) = \'superadministrator\''
						. ' OR LOWER( usertype ) = \'super administrator\''
						;
				$database->setQuery( $query );
				$rows = $database->loadObjectList();
				$row2 			= $rows[0];

				$adminName2 	= $row2->name;
				$adminEmail2 	= $row2->email;
			}

			// Send email to user
			if ( !$aecConfig->cfg['nojoomlaregemails'] || $overrideEmails ) {
				mosMail( $adminEmail2, $adminName2, $email, $subject, $message );
			}

			// Send notification to all administrators
			$aecUser	= AECToolbox::_aecIP();

			$subject2	= sprintf( _AEC_SEND_SUB, $name, $mainframe->getCfg( 'sitename' ) );
			$message2	= sprintf( _AEC_ASEND_MSG_NEW_REG, $adminName2, $mainframe->getCfg( 'sitename' ), $row->name, $email, $username, $aecUser['ip'], $aecUser['isp'] );

			$subject2	= html_entity_decode( $subject2, ENT_QUOTES );
			$message2	= html_entity_decode( $message2, ENT_QUOTES );

			// get email addresses of all admins and superadmins set to recieve system emails
			$query = 'SELECT email, sendEmail'
			. ' FROM #__users'
			. ' WHERE ( gid = 24 OR gid = 25 )'
			. ' AND sendEmail = 1'
			. ' AND block = 0'
			;
			$database->setQuery( $query );
			$admins = $database->loadObjectList();

			foreach ( $admins as $admin ) {
				// send email to admin & super admin set to recieve system emails
				mosMail( $adminEmail2, $adminName2, $admin->email, $subject2, $message2 );
			}
		}

		// We need the new userid, so we're fetching it from the newly created entry here
		$query = 'SELECT `id`'
				. ' FROM #__users'
				. ' WHERE `username` = \'' . $var['username'] . '\''
				;
		$database->setQuery( $query );
		return $database->loadResult();
	}

	function quickVerifyUserID( $userid )
	{
		global $database;

		$query = 'SELECT `status`'
				. ' FROM #__acctexp_subscr'
				. ' WHERE `userid` = \'' . (int) $userid . '\''
				. ' AND `primary` = \'1\''
				;
		$database->setQuery( $query );
		$aecstatus = $database->loadResult();

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

	function formatAmount( $amount, $currency=null ) {
		global $aecConfig;

		if ( !empty( $currency ) ) {
			if ( !empty( $aecConfig->cfg['amount_currency_symbol'] ) ) {
				switch ( $currency ) {
					case 'USD':
						$currency = '$';
						break;
					case 'GBP':
						$currency = '&pound;';
						break;
					case 'EUR':
						$currency = '&euro;';
						break;
					case 'CNY':
					case 'JPY':
						$currency = '&yen;';
						break;
				}
			}

			$amount = AECToolbox::correctAmount( $amount );

			if ( $aecConfig->cfg['amount_use_comma'] ) {
				$amount = str_replace( '.', ',', $amount );
			}

			if ( $aecConfig->cfg['amount_currency_symbolfirst'] ) {
				return $currency . '&nbsp;' . $amount;
			} else {
				return $amount . '&nbsp;' . $currency;
			}
		} else {
			if ( $aecConfig->cfg['amount_use_comma'] ) {
				$amount = str_replace( '.', ',', $amount );
			}

			return $amount;
		}
	}

	function correctAmount( $amount )
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

		$amount = round( $amount, 2 );

		$a		= explode( '.', $amount );
		if ( empty( $a[1] ) ) {
			$amount = $a[0] . '.00';
		} else {
			$amount = $a[0] . '.' . substr( str_pad( $a[1], 2, '0' ), 0, 2 );
		}

		return $amount;
	}

	function computeExpiration( $value, $unit, $timestamp )
	{
		$sign = strpos( $value, '-' ) ? '-' : '+';

		switch ( $unit ) {
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

		$timestamp = strtotime( $add, $timestamp );
		return date( 'Y-m-d H:i:s', $timestamp );
	}

	function cleanPOST( $post )
	{
		$badparams = array( 'option', 'task' );

		foreach ( $badparams as $param ) {
			if ( isset( $post[$param] ) ) {
				unset( $post[$param] );
			}
		}

		return aecPostParamClear( $post );
	}


	function getFileArray( $dir, $extension=false, $listDirectories=false, $skipDots=true )
	{
		$dirArray	= array();
		$handle		= dir( $dir );

		while ( false !== ( $file = $handle->read() ) ) {
			if ( ( $file != '.' && $file != '..' ) || $skipDots === true ) {
				if ( $listDirectories === false ) {
					if ( is_dir( $file ) ) {
						continue;
					}
				}
				if ( $extension !== false ) {
					if ( strpos( basename( $file ), $extension ) === false ) {
						continue;
					}
				}

				array_push( $dirArray, basename( $file ) );
				}
		}
		$handle->close();
		return $dirArray;
	}

	function visualstrlen( $string )
	{
		// Visually Short Chars
		$srt = array( 'i', 'j', 'l', ',', '.' );
		// Visually Long Chars
		$lng = array( 'm', 'w', 'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Y', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z' );

		// Break String into individual characters
		$char_array = preg_split( '#(?<=.)(?=.)#s', $string );

		$vlen = 0;
		// Iterate through array counting the visual length of the string
		foreach ( $char_array as $char ) {
			if ( in_array( $char, $srt ) ) {
				$vlen += 0.5;
			} elseif ( in_array( $char, $srt ) ) {
				$vlen += 2;
			} else {
				$vlen += 1;
			}
		}

		return (int) $vlen;
	}

	function rewriteEngineInfo( $switches=array(), $params=null )
	{
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

			if ( GeneralInfoRequester::detect_component( 'CB' ) || GeneralInfoRequester::detect_component( 'CBE' ) ) {
				global $database;

				$query = 'SELECT name, title'
						. ' FROM #__comprofiler_fields'
						. ' WHERE `table` != \'#__users\''
						. ' AND name != \'NA\'';
				$database->setQuery( $query );
				$objects = $database->loadObjectList();

				if ( is_array( $objects ) ) {
					foreach ( $objects as $object ) {
						$rewrite['user'][] = $object->name;

						if ( strpos( $object->title, '_' ) === 0 ) {
							$content = $object->name;
						} else {
							$content = $object->title;
						}

						$name = '_REWRITE_KEY_USER_' . strtoupper( $object->name );
						if ( !defined( $name ) ) {
							define( $name, $content );
						}
					}
				}
			}
		}

		if ( in_array( 'subscription', $switches ) ) {
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
		}

		if ( !empty( $params ) ) {
			$params[] = array( 'accordion_start', 'small_accordion' );

			$params[] = array( 'accordion_itemstart', constant( '_REWRITE_ENGINE_TITLE' ) );
			$list = '<div class="rewriteinfoblock">' . "\n"
			. '<p>' . constant( '_REWRITE_ENGINE_DESC' ) . '</p>' . "\n"
			. '</div>' . "\n";
			$params[] = array( 'literal', $list );
			$params[] = array( 'div_end', '' );

			foreach ( $rewrite as $area => $keys ) {
				$params[] = array( 'accordion_itemstart', constant( '_REWRITE_AREA_' . strtoupper( $area ) ) );

				$list = '<div class="rewriteinfoblock">' . "\n"
				. '<ul>' . "\n";

				foreach ( $keys as $key ) {
					$list .= '<li>[[' . $area . "_" . $key . ']] =&gt; ' . constant( '_REWRITE_KEY_' . strtoupper( $area . "_" . $key ) ) . '</li>' . "\n";
				}
				$list .= '</ul>' . "\n"
				. '</div>' . "\n";

				$params[] = array( 'literal', $list );
				$params[] = array( 'div_end', '' );
			}

			$params[] = array( 'accordion_itemstart', constant( '_REWRITE_ENGINE_AECJSON_TITLE' ) );
			$list = '<div class="rewriteinfoblock">' . "\n"
			. '<p>' . constant( '_REWRITE_ENGINE_AECJSON_DESC' ) . '</p>' . "\n"
			. '</div>' . "\n";
			$params[] = array( 'literal', $list );
			$params[] = array( 'div_end', '' );

			$params[] = array( 'div_end', '' );

			return $params;
		} else {
			$return = '';
			foreach ( $rewrite as $area => $keys ) {
				$return .= '<div class="rewriteinfoblock">' . "\n"
				. '<p><strong>' . constant( '_REWRITE_AREA_' . strtoupper( $area ) ) . '</strong></p>' . "\n"
				. '<ul>' . "\n";

				foreach ( $keys as $key ) {
					$return .= '<li>[[' . $area . "_" . $key . ']] =&gt; ' . constant( '_REWRITE_KEY_' . strtoupper( $area . "_" . $key ) ) . '</li>' . "\n";
				}
				$return .= '</ul>' . "\n"
				. '</div>' . "\n";
			}

			$return .= '<div class="rewriteinfoblock">' . "\n"
			. '<p><strong>' . constant( '_REWRITE_ENGINE_AECJSON_TITLE' ) . '</strong></p>' . "\n"
			. '<p>' . constant( '_REWRITE_ENGINE_AECJSON_DESC' ) . '</p>' . "\n"
			. '</div>' . "\n";

			return $return;
		}
	}

	function rewriteEngineRQ( $content, $request, $metaUser=null, $subscriptionPlan=null, $invoice=null )
	{
		if ( isset( $request->metaUser ) ) {
			$metaUser = $request->metaUser;
		}

		if ( isset( $request->plan ) ) {
			$subscriptionPlan = $request->plan;
		}

		if ( isset( $request->invoice ) ) {
			$invoice = $request->invoice;
		}

		return AECToolbox::rewriteEngine( $content, $metaUser, $subscriptionPlan, $invoice );
	}

	function rewriteEngine( $subject, $metaUser=null, $subscriptionPlan=null, $invoice=null )
	{
		global $aecConfig, $database, $mosConfig_absolute_path, $mosConfig_live_site, $mosConfig_offset;

		// Check whether a replacement exists at all
		if ( ( strpos( $subject, '[[' ) === false ) && ( strpos( $subject, '{aecjson}' ) === false ) ) {
			return $subject;
		}

		$rewrite = array();

		$rewrite['system_timestamp']			= strftime( $aecConfig->cfg['display_date_frontend'],  time() + $mosConfig_offset * 3600 );
		$rewrite['system_timestamp_backend']	= strftime( $aecConfig->cfg['display_date_backend'], time() + $mosConfig_offset * 3600 );
		$rewrite['system_serverstamp_time']	= strftime( $aecConfig->cfg['display_date_frontend'], time() );
		$rewrite['system_server_timestamp_backend']	= strftime( $aecConfig->cfg['display_date_backend'], time() );

		$rewrite['cms_absolute_path']	= $mosConfig_absolute_path;
		$rewrite['cms_live_site']		= $mosConfig_live_site;

		if ( is_object( $metaUser ) ) {
			if ( !empty( $metaUser->hasExpiration ) ) {
				$rewrite['expiration_date'] = $metaUser->objExpiration->expiration;
			}

			// Explode Name
			if ( is_array( $metaUser->cmsUser->name ) ) {
				$namearray	= $metaUser->cmsUser->name;
			} else {
				$namearray	= explode( " ", $metaUser->cmsUser->name );
			}
			$firstfirstname	= $namearray[0];
			$maxname		= count($namearray) - 1;
			$lastname		= $namearray[$maxname];
			unset( $namearray[$maxname] );
			$firstname = implode( ' ', $namearray );

			$rewrite['user_id']					= $metaUser->cmsUser->id;
			$rewrite['user_username']			= $metaUser->cmsUser->username;
			$rewrite['user_name']				= $metaUser->cmsUser->name;
			$rewrite['user_first_name']			= $firstname;
			$rewrite['user_first_first_name']	= $firstfirstname;
			$rewrite['user_last_name']			= $lastname;
			$rewrite['user_email']				= $metaUser->cmsUser->email;

			if ( GeneralInfoRequester::detect_component( 'CB' ) || GeneralInfoRequester::detect_component( 'CBE' ) ) {
				if ( !$metaUser->hasCBprofile ) {
					$metaUser->loadCBuser();
				}

				if ( !empty( $metaUser->hasCBprofile ) ) {
					$query = 'SELECT `name`'
							. ' FROM #__comprofiler_fields'
							. ' WHERE `table` != \'#__users\''
							. ' AND `name` != \'NA\'';
					$database->setQuery( $query );
					$fields = $database->loadResultArray();

					if ( !empty( $fields ) ) {
						foreach ( $fields as $fieldname ) {
							$rewrite['user_' . $fieldname] = $metaUser->cbUser->$fieldname;
						}
					}

					$rewrite['user_activationcode']		= $metaUser->cbUser->cbactivation;
					$rewrite['user_activationlink']		= $mosConfig_live_site."index.php?option=com_comprofiler&task=confirm&confirmcode=" . $metaUser->cbUser->cbactivation;
				} else {
					$rewrite['user_activationcode']		= $metaUser->cmsUser->activation;
					$rewrite['user_activationlink']		= $mosConfig_live_site."index.php?option=com_registration&task=activate&activation=" . $metaUser->cmsUser->activation;
				}
			} else {
				$rewrite['user_activationcode']		= $metaUser->cmsUser->activation;
				$rewrite['user_activationlink']		= $mosConfig_live_site."index.php?option=com_registration&task=activate&activation=" . $metaUser->cmsUser->activation;
			}

			if ( $metaUser->hasSubscription ) {
				$rewrite['subscription_type']			= $metaUser->focusSubscription->type;
				$rewrite['subscription_status']			= $metaUser->focusSubscription->status;
				$rewrite['subscription_signup_date']	= $metaUser->focusSubscription->signup_date;
				$rewrite['subscription_lastpay_date']	= $metaUser->focusSubscription->lastpay_date;
				$rewrite['subscription_plan']			= $metaUser->focusSubscription->plan;
				$rewrite['subscription_previous_plan']	= $metaUser->focusSubscription->previous_plan;
				$rewrite['subscription_recurring']		= $metaUser->focusSubscription->recurring;
				$rewrite['subscription_lifetime']		= $metaUser->focusSubscription->lifetime;
				$rewrite['subscription_expiration_date']		= strftime( $aecConfig->cfg['display_date_frontend'], strtotime( $metaUser->focusSubscription->expiration ) );
				$rewrite['subscription_expiration_date_backend']		= strftime( $aecConfig->cfg['display_date_backend'], strtotime( $metaUser->focusSubscription->expiration ) );
			}

			if ( is_null( $invoice ) ) {
				$lastinvoice = AECfetchfromDB::lastClearedInvoiceIDbyUserID( $metaUser->cmsUser->id );

				$invoice = new Invoice( $database );
				$invoice->load( $lastinvoice );
			}
		}

		if ( is_object( $invoice ) ) {
			$rewrite['invoice_id']					= $invoice->id;
			$rewrite['invoice_number']				= $invoice->invoice_number;
			$rewrite['invoice_created_date']		= $invoice->created_date;
			$rewrite['invoice_transaction_date']	= $invoice->transaction_date;
			$rewrite['invoice_method']				= $invoice->method;
			$rewrite['invoice_amount']				= $invoice->amount;
			$rewrite['invoice_currency']			= $invoice->currency;
			if ( !empty( $invoice->coupons ) && is_array( $invoice->coupons ) ) {
				$rewrite['invoice_coupons']			=  implode( ';', $invoice->coupons );
			} else {
				$rewrite['invoice_coupons']			=  '';
			}

			if ( !is_null( $metaUser ) && !is_null( $subscriptionPlan ) ) {
				$invoice->formatInvoiceNumber();
				$rewrite['invoice_number_format']	= $invoice->invoice_number;
				$invoice->deformatInvoiceNumber();
			}
		}

		if ( is_object( $subscriptionPlan ) ) {
			$rewrite['plan_name'] = $subscriptionPlan->getProperty( 'name' );
			$rewrite['plan_desc'] = $subscriptionPlan->getProperty( 'desc' );
		}

		if ( strpos( $subject, '{aecjson}' ) !== false ) {
			// We have at least one JSON object, switching to JSON mode
			return AECToolbox::decodeTags( $subject, $rewrite, $metaUser );
		} else {
			// No JSON found, do traditional parsing
			$search = array();
			$replace = array();
			foreach ( $rewrite as $name => $replacement ) {
				$search[]	= '[[' . $name . ']]';
				$replace[]	= $replacement;
			}

			return str_replace( $search, $replace, $subject );
		}
	}

	function decodeTags( $subject, $rewrite, $metaUser )
	{
		global $mosConfig_absolute_path;

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

			$result = AECToolbox::resolveJSONitem( $json, $rewrite, $metaUser );

			$subject = str_replace( $match, $result, $subject );
		}

		return $result;
	}

	function resolveJSONitem( $current, $rewrite, $metaUser )
	{
		if ( is_object( $current ) ) {
			if ( !isset( $current->cmd ) || !isset( $current->vars ) ) {
				// Malformed String
				return "JSON PARSE ERROR - Malformed String!";
			}

			$command = $current->cmd;
			$variables = $current->vars;

			$variables = AECToolbox::resolveJSONitem( $variables, $rewrite, $metaUser );

			$current = AECToolbox::executeCommand( $command, $variables, $rewrite, $metaUser );

		} elseif ( is_array( $current ) ) {
			foreach( $current as $id => $item ) {
				$current[$id] = AECToolbox::resolveJSONitem( $item, $rewrite, $metaUser );
			}
		}

		return $current;
	}

	function executeCommand( $command, $vars, $rewrite, $metaUser )
	{
		$result = '';
		switch( $command ) {
			case 'rw_constant':
				if ( isset( $rewrite[$vars] ) ) {
					$result = $rewrite[$vars];
				}
				break;
			case 'metaUser':
				if ( !is_object( $metaUser ) ) {
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

				$result = $metaUser->getProperty( $vars );
				break;
			case 'constant':
				if ( defined( $vars ) ) {
					$result = constant( $vars );
				}
				break;
			case 'global':
				if ( is_array( $vars ) ) {
					if ( isset( $vars[0] ) && isset( $vars[1] ) ) {
						$call = strtoupper( $vars[0] );

						$allowed = array( 'SERVER', 'GET', 'POST', 'FILES', 'COOKIE', 'SESSION', 'REQUEST', 'ENV' );

						if ( in_array( $vars[0], $allowed ) ) {
							$v = '_' . $vars[0];

							if ( isset( $$v[$vars[1]] ) ) {
								$result = $$v[$vars[1]];
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
					if ( isset( $vars[2] ) && !isset( $vars[1] ) ) {
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
			case 'crop':
				if ( isset( $vars[2] ) ) {
					$result = substr( $vars[0], (int) $vars[1], (int) $vars[2] );
				} else {
					$result = substr( $vars[0], (int) $vars[1] );
				}
				break;
			case 'pad':
				if ( isset( $vars[3] ) ) {
					$result = str_pad($vars[0], (int) $vars[1], $vars[2], ( constant( "STR_PAD_" . strtoupper( $vars[3] ) ) ) );
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
			case 'php_function':
				if ( isset( $vars[1] ) ) {
					$result = call_user_func_array( $vars[0], $vars[1] );
				} else {
					$result = call_user_func_array( $vars[0] );
				}
				break;
			case 'php_method':
				if ( function_exists( 'call_user_method_array' ) ) {
					if ( isset( $vars[2] ) ) {
						$result = call_user_method_array( $vars[0], $vars[1], $vars[2] );
					} else {
						$result = call_user_method_array( $vars[0], $vars[1] );
					}
				} else {
					// TODO: Check whether this is right
					$callback = array( $vars[0], $vars[1] );

					if ( isset( $vars[2] ) ) {
						$result = call_user_func_array( $callback, $vars[2] );
					} else {
						$result = call_user_func_array( $callback );
					}
				}
				break;
			default:
				$result = $command;
				break;
		}

		return $result;
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

}

class microIntegrationHandler
{
	function microIntegrationHandler()
	{
		global $mainframe;

		$this->mi_dir = $mainframe->getCfg( 'absolute_path' ) . '/components/com_acctexp/micro_integration';
	}

	function getIntegrationList()
	{
		$list = AECToolbox::getFileArray( $this->mi_dir, 'php', false, true );

		$integration_list = array();
		foreach ( $list as $name ) {
			$parts = explode( '.', $name );
			$integration_list[] = $parts[0];
		}

		return $integration_list;
	}

	function getPlansbyMI ( $mi_id )
	{
		global $database;

		$query = 'SELECT `id`'
				. ' FROM #__acctexp_plans'
				. ' WHERE `micro_integrations` != \'\''
				;
		$database->setQuery( $query );
		$plans = $database->loadResultArray();

		$plan_list = array();
		foreach ( $plans as $planid ) {
			$plan = new SubscriptionPlan( $database );
			$plan->load( $planid );
			$mis = $plan->getMicroIntegrations();
			if ( is_array( $mis ) ) {
				if ( in_array( $mi_id, $mis ) ) {
					$plan_list[] = $planid;
				}
			}
		}

		return $plan_list;
	}

	function userPlanExpireActions( $metaUser, $subscription_plan )
	{
		global $database;

		$mi_autointegrations = $this->getAutoIntegrations();

		if ( is_array( $mi_autointegrations ) || ( $subscription_plan !== false ) ) {
			if ( is_array( $subscription_plan->micro_integrations ) ) {
				$user_auto_integrations = array_intersect( $subscription_plan->micro_integrations, $mi_autointegrations );
			} else {
				$user_auto_integrations = $mi_autointegrations;
			}

			if ( count( $user_auto_integrations ) ) {
				foreach ( $user_auto_integrations as $mi_id ) {
					$mi = new microIntegration( $database );
					$mi->load( $mi_id );
					if ( $mi->callIntegration() ) {
						$mi->expiration_action( $metaUser, $subscription_plan );
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

	function getAutoIntegrations()
	{
		global $database;

		$query = 'SELECT `id`'
				. ' FROM #__acctexp_microintegrations'
				. ' WHERE `auto_check` = \'1\''
				;
		$database->setQuery( $query );
		return $database->loadResultArray();
	}

	function getUserChangeIntegrations()
	{
		global $database;

		$query = 'SELECT id'
				. ' FROM #__acctexp_microintegrations'
				. ' WHERE `active` = \'1\''
				. ' AND `on_userchange` = \'1\''
				;
		$database->setQuery( $query );
		return $database->loadResultArray();
	}

	function userchange( $row, $post, $trace = '' )
	{
		global $database;

		$mi_list = $this->getUserChangeIntegrations();

		if ( is_int( $row ) ) {
			$userid = $row;
		} elseif ( is_string( $row ) ){
			$query = 'SELECT id'
			. ' FROM #__users'
			. ' WHERE username = \'' . $row . '\''
			;
			$database->setQuery( $query );
			$userid = $database->loadResult();
		} elseif ( is_array( $row ) ) {
			$userid = $row['id'];
		} elseif ( !is_object( $row ) ) {
			$userid = $row;
		}

		if ( !is_object( $row ) ) {
			$row = new mosUser( $database );
			$row->load( $userid );
		}

		if ( !empty( $mi_list ) ) {
			foreach ( $mi_list as $mi_id ) {;
				if ( !is_null( $mi_id ) && ( $mi_id != '' ) && $mi_id ) {
					$mi = new microIntegration($database);
					$mi->load( $mi_id );
					if ( $mi->callIntegration() ) {
						$mi->on_userchange_action( $row, $post, $trace );
					}
				}
			}
		}
	}

	function applyMIs( &$amount, $subscription, $metaUser )
	{
		global $database;

		$add = new stdClass();
		$add->price =& $amount;

		if ( !empty( $subscription->micro_integrations ) ) {
			foreach ( $subscription->micro_integrations as $mi_id ) {
				$mi = new microIntegration( $database );

				if ( !$mi->mi_exists( $mi_id ) ) {
					continue;
				}

				$mi->load( $mi_id );

				if ( !$mi->callIntegration() ) {
					continue;
				}

				if ( method_exists( $mi->mi_class, 'modifyPrice' )  ) {
					$mi->relayAction( $metaUser, null, null, $subscription, 'modifyPrice', $add );
				}

				unset( $mi );
			}
		}
	}
}

class MI
{
	function autoduplicatesettings( $settings, $ommit=array() )
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
			if ( in_array( $name, $ommit) ) {
				continue;
			}

			$new_settings[$name]			= $content;
			$new_settings[$name.'_exp']		= $content;
			$new_settings[$name.'_pre_exp']	= $content;
		}

		if ( !empty( $new_lists ) ) {
			$new_settings['lists'] = $lists;
		}

		return $new_settings;
	}

	function pre_expiration_action( $params, $metaUser, $plan )
	{
		return $this->relayAction( $params, $metaUser, $plan, null, '_pre_exp', $add=false );
	}

	function expiration_action( $params, $metaUser, $plan )
	{
		return $this->relayAction( $params, $metaUser, $plan, null, '_exp', $add=false );
	}

	function action( $params, $metaUser, $invoice, $plan )
	{
		return $this->relayAction( $params, $metaUser, $plan, $invoice, '', $add=false );
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
	var $ordering			= null;
	/** @var string */
	var $name				= null;
	/** @var text */
	var $desc				= null;
	/** @var string */
	var $class_name			= null;
	/** @var text */
	var $params				= null;
	/** @var int */
	var $auto_check			= null;
	/** @var int */
	var $pre_exp_check		= null;
	/** @var int */
	var $on_userchange		= null;

	function microIntegration(&$db)
	{
		$this->mosDBTable( '#__acctexp_microintegrations', 'id', $db );

		if ( !defined( '_AEC_LANG_INCLUDED_MI' ) ) {
			$this->_callMILanguage();
		}
	}

	function declareParamFields()
	{
		return array( 'params' );
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

	function _callMILanguage()
	{
		global $mainframe;

		$langPathMI = $mainframe->getCfg( 'absolute_path' ) . '/components/com_acctexp/micro_integration/language/';
		if ( file_exists( $langPathMI . $mainframe->getCfg( 'lang' ) . '.php' ) ) {
			include_once( $langPathMI . $mainframe->getCfg( 'lang' ) . '.php' );
		} else {
			include_once( $langPathMI . 'english.php' );
		}
	}

	function mi_exists( $mi_id )
	{
		global $database;

		$query = 'SELECT count(*)'
				. ' FROM #__acctexp_microintegrations'
				. ' WHERE `id` = \'' . $mi_id . '\''
				;
		$database->setQuery( $query );
		return $database->loadResult();
	}

	function callDry( $mi_name )
	{
		global $mosConfig_absolute_path;

		$this->class_name = $mi_name;
		$this->callIntegration( true );
	}

	function callIntegration( $override = 0 )
	{
		global $mosConfig_absolute_path;

		$filename = $mosConfig_absolute_path . '/components/com_acctexp/micro_integration/' . $this->class_name . '.php';

		$file_exists = file_exists( $filename );

		if ( ( ( !$this->active && $this->id ) || !$file_exists ) && !$override ) {
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
			$this->mi_class->settings	=& $this->settings;

			return true;
		} else {
			return false;
		}
	}

	function action( $metaUser, $exchange=null, $invoice=null, $objplan=null )
	{
		return $this->relayAction( $metaUser, $exchange, $invoice, $objplan, 'action', $add=false );
	}

	function pre_expiration_action( $metaUser, $objplan=null )
	{
		if ( method_exists( $this->mi_class, 'pre_expiration_action' ) || method_exists( $this->mi_class, 'relayAction' ) ) {
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
						if ( time() > $current_expiration ) {
							// This trigger comes too late as the expiration already happened => break
							return false;
						}
					}
				}
			}

			$newflags[$spc]		= $current_expiration;
			$newflags[$spca]	= time();

			// Create the new flags
			$metaUser->meta->setMIParams( $this->id, $objplan->id, $newflags );

			$metaUser->meta->storeload();

			return $this->relayAction( $metaUser, null, null, $objplan, 'pre_expiration_action', $add=false );
		} else {
			return null;
		}
	}

	function expiration_action( $metaUser, $objplan=null )
	{
		return $this->relayAction( $metaUser, null, null, $objplan, 'expiration_action', $add=false );
	}

	function relayAction( $metaUser, $exchange=null, $invoice=null, $objplan=null, $stage='action', &$add )
	{
		// Exchange Settings
		if ( is_array( $exchange ) && !empty( $exchange ) ) {
			$this->exchangeSettings( $exchange );
		}

		$params = $metaUser->meta->getMIParams( $this->id, $objplan->id );

		$request = new stdClass();
		$request->parent			=& $this;
		$request->metaUser			=& $metaUser;
		$request->invoice			=& $invoice;
		$request->plan				=& $objplan;
		$request->params			=& $params;

		if ( $add !== false ) {
			$request->add			=& $add;
		}

		// Call Action
		if ( method_exists( $this->mi_class, 'relayAction' ) ) {
			switch ( $stage ) {
				case 'action':
					$area = '';
					break;
				case 'pre_expiration_action':
					$area = '_pre_exp';
					break;
				case 'expiration_action':
					$area = '_exp';
					break;
				default:
					$area = $stage;
					break;
			}

			$return = $this->mi_class->relayAction( $request, $area );
		} elseif ( method_exists( $this->mi_class, $stage ) ) {
			$return = $this->mi_class->$stage( $request );
		} else {
			return null;
			/*$eventlog = new eventLog( $this->_db );
			$eventlog->issue( 'MI application problems', 'mi, problems, '.$this->class_name, 'Action not found: '.$stage, 32 );
			$return = null;*/
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

			$eventlog = new eventLog( $this->_db );
			$eventlog->issue( 'MI application problems', 'mi, problems, '.$this->class_name, $error, $level );
		}

		// If returning fatal error, issue additional entry
		if ( $return === false ) {
			global $database;

			$error = 'The MI "' . $this->name . '" ('.$this->class_name.') could not be carried out due to errors, plan application was halted';

			$err = $this->_db->getErrorMsg();
			if ( !empty( $err ) ) {
				$error .= ' Last Database Error: ' . $err;
			}

			$eventlog = new eventLog( $database );
			$eventlog->issue( 'MI application failed', 'mi, failure, '.$this->class_name, $error, 128 );
		}

		return $return;
	}

	function getMIform( $plan )
	{
		if ( method_exists( $this->mi_class, 'getMIform' ) ) {
			return $this->mi_class->getMIform( $plan );
		} else {
			return null;
		}
	}

	function getErrors()
	{
		if ( !empty( $this->mi_class->error ) ) {
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

	function on_userchange_action( $row, $post, $trace )
	{
		$request = new stdClass();
		$request->parent			=& $this;
		$request->row				=& $row;
		$request->post				=& $post;
		$request->trace				=& $trace;

		if ( method_exists( $this->mi_class, 'on_userchange_action' ) ) {
			return $this->mi_class->on_userchange_action( $request );
		} else {
			return null;
		}
	}

	function profile_info( $userid )
	{
		if ( method_exists( $this->mi_class, 'profile_info' ) ) {
			return $this->mi_class->profile_info( $userid );
		} else {
			return null;
		}
	}

	function admin_info ( $userid )
	{
		if ( method_exists( $this->mi_class, 'admin_info' ) ) {
			return $this->mi_class->admin_info( $userid );
		} else {
			return $this->profile_info( $userid );
		}
	}

	function getInfo()
	{
		if ( method_exists( $this->mi_class, 'Info' ) ) {
			$this->info = $this->mi_class->Info();
		} else {
			$nname = strtoupper( '_aec_' . $this->class_name . '_name' );
			$ndesc = strtoupper( '_aec_' . $this->class_name . '_desc' );

			$this->info = array();
			if ( defined( $nname ) && defined( $ndesc ) ) {
				$this->info['name'] = constant( $nname );
				$this->info['desc'] = constant( $ndesc );
			} else {
				$this->info['name'] = 'NONAME';
				$this->info['desc'] = 'NODESC';
			}
		}
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
			if ( method_exists( $this->mi_class, 'Defaults' ) && empty( $this->settings ) ) {
				$defaults = $this->mi_class->Defaults();
			} else {
				$defaults = array();
			}

			$settings = $this->mi_class->Settings();

			// Autoload Params if they have not been called in by the MI
			foreach ( $settings as $name => $setting ) {
				// Do we have a parameter at first position?
				if ( isset( $setting[1] ) && !isset( $setting[3] ) ) {
					if ( isset( $this->settings[$name] ) ) {
						$settings[$name][3] = $this->settings[$name];
					} elseif( isset( $defaults[$name] ) ) {
						$settings[$name][3] = $defaults[$name];
					}
				} else {
					if ( isset( $this->settings[$name] ) ) {
						$settings[$name][1] = $this->settings[$name];
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
		foreach ( $this->settings as $key => $value ) {
			if ( !isset( $exchange[$key] ) ) {
				continue;
			}

			if ( !empty( $exchange[$key] ) ) {
				// Exception for NULL case
				// TODO: SET_TO_NULL undocumented!!!
				if ( strcmp( $exchange[$key], '[[SET_TO_NULL]]' ) === 0 ) {
					$this->settings[$key] = '';
				} else {
					$this->settings[$key] = $exchange[$key];
				}
			}
		}
	}

	function savePostParams( $array )
	{
		// Strip out params that we don't need
		$params = $this->stripNonParams( $array );

		// Check whether there is a custom function for saving params
		if ( method_exists( $this->mi_class, 'saveparams' ) ) {
			$new_params = $this->mi_class->saveparams( $params );
		} else {
			$new_params = $params;
		}

		if ( !empty( $new_params['rebuild'] ) ) {
			global $database;

			$planlist = MicroIntegrationHandler::getPlansbyMI( $this->id );

			foreach ( $planlist as $planid ) {
				$plan = new SubscriptionPlan( $database );
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
			global $database;

			$planlist = MicroIntegrationHandler::getPlansbyMI( $this->id );

			foreach ( $planlist as $planid ) {
				$plan = new SubscriptionPlan( $database );
				$plan->load( $planid );

				$userlist = SubscriptionPlanHandler::getPlanUserlist( $planid );
				foreach ( $userlist as $userid ) {
					$metaUser = new metaUser( $userid );

					$this->expiration_action( $params, $metaUser, null, $plan );
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
			global $database;

			$request = new stdClass();
			$request->parent			=& $this;
			$request->metaUser			=& $metaUser;

			$invoice = new Invoice( $database );
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
	function applyCoupons( $amount, &$coupons, $metaUser, $original_amount=null, $invoiceFactory=null )
	{
		$applied_coupons = array();
		$global_nomix = array();

		if ( empty( $coupons ) || !is_array( $coupons ) ) {
			return $amount;
		}

		foreach ( $coupons as $arrayid => $coupon_code ) {
			$cph = new couponHandler();
			$cph->load( $coupon_code );

			if ( !$cph->status ) {
				$this->setError( $cph->error );
				unset( $coupons[$arrayid] );
				continue;
			}

			// Get the coupons that this one cannot be mixed with
			if ( !empty( $cph->restrictions['restrict_combination'] ) && !empty( $cph->restrictions['bad_combinations'] ) ) {
				$nomix = $cph->restrictions['bad_combinations'];
			} else {
				$nomix = array();
			}

			if ( count( array_intersect( $applied_coupons, $nomix ) ) || in_array( $coupon_code, $global_nomix ) ) {
				// This coupon either interferes with one of the coupons already applied, or the other way round
				$this->setError( _COUPON_ERROR_COMBINATION );
				unset( $coupons[$arrayid] );
			} else {
				if ( $cph->status ) {
					// Coupon approved, checking restrictions
					$cph->checkRestrictions( $metaUser, $amount, $original_amount, $invoiceFactory );
					if ( $cph->status ) {
						$amount = $cph->applyCoupon( $amount );
						$applied_coupons[] = $coupon_code;
						$global_nomix = array_merge( $global_nomix, $nomix );
					} else {
						// Coupon restricted for this user, thus it needs to be deleted later on
						$this->setError( $cph->error );
						unset( $coupons[$arrayid] );
					}
				} else {
					// Coupon not approved, thus it needs to be deleted later on
					// Set Error
					$this->setError( $cph->error );
					unset( $coupons[$arrayid] );
				}
			}
		}

		return $amount;
	}

	function applyCouponsToTerms( $terms, &$coupons, $metaUser, $original_amount, $invoiceFactory )
	{
		$applied_coupons = array();
		$global_nomix = array();
		foreach ( $coupons as $arrayid => $coupon_code ) {
			$cph = new couponHandler();
			$cph->load( $coupon_code );

			if ( !$cph->status ) {
				$this->setError( $cph->error );
				unset( $coupons[$arrayid] );
				continue;
			}

			// Get the coupons that this one cannot be mixed with
			if ( !empty( $cph->restrictions['restrict_combination'] ) && !empty( $cph->restrictions['bad_combinations'] ) ) {
				$nomix = $cph->restrictions['bad_combinations'];
			} else {
				$nomix = array();
			}

			if ( count( array_intersect( $applied_coupons, $nomix ) ) || in_array( $coupon_code, $global_nomix ) ) {
				// This coupon either interferes with one of the coupons already applied, or the other way round
				$this->setError( _COUPON_ERROR_COMBINATION );
				unset( $coupons[$arrayid] );
			} else {
				if ( $cph->status ) {
					// Coupon approved, checking restrictions
					$cph->checkRestrictions( $metaUser, $original_amount, $invoiceFactory );
					if ( $cph->status ) {
						$start = 0;

						if ( $cph->discount['useon_trial'] && $terms->hasTrial && ( $terms->pointer == 0 ) ) {
							$start = 0;
						} elseif( $terms->hasTrial ) {
							$start = 1;
						}

						$info = array();
						$info['coupon'] = $coupon_code;

						for ( $i = $start; $i < count( $terms->terms ); $i++ ) {
							if ( !$cph->discount['useon_full'] && ( $i > 0 ) ) {
								continue;
							}

							if ( $cph->discount['percent_first'] ) {
								if ( $cph->discount['amount_percent_use'] ) {
									$info['details'] = '-' . $cph->discount['amount_percent'] . '%';
									$terms->terms[$i]->discount( null, $cph->discount['amount_percent'], $info );
								}
								if ( $cph->discount['amount_use'] ) {
									$info['details'] = null;
									$terms->terms[$i]->discount( $cph->discount['amount'], null, $info );
								}
							} else {
								if ( $cph->discount['amount_use'] ) {
									$info['details'] = null;
									$terms->terms[$i]->discount( $cph->discount['amount'], null, $info );
								}
								if ( $cph->discount['amount_percent_use'] ) {
									$info['details'] = '-' . $cph->discount['amount_percent'] . '%';
									$terms->terms[$i]->discount( null, $cph->discount['amount_percent'], $info );
								}
							}
						}

						$applied_coupons[] = $coupon_code;
						$global_nomix = array_merge( $global_nomix, $nomix );
					} else {
						// Coupon restricted for this user, thus it needs to be deleted later on
						$this->setError( $cph->error );
						unset( $coupons[$arrayid] );
					}
				} else {
					// Coupon not approved, thus it needs to be deleted later on
					$this->setError( $cph->error );
					unset( $coupons[$arrayid] );
				}
			}
		}

		return $terms;
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

	function couponHandler()
	{
	}

	function setError( $error )
	{
		// Status = NOT OK
		$this->status = false;
		// Set error message
		$this->error = $error;
	}

	function load( $coupon_code )
	{
		global $database;

		// Get this coupons id from the static table
		$query = 'SELECT `id`'
				. ' FROM #__acctexp_coupons_static'
				. ' WHERE `coupon_code` = \'' . $coupon_code . '\''
				;
		$database->setQuery( $query );
		$couponid = $database->loadResult();

		if ( $couponid ) {
			// Its static, so set type to 1
			$this->type = 1;
		} else {
			// Coupon not found, take the regular table
			$query = 'SELECT `id`'
					. ' FROM #__acctexp_coupons'
					. ' WHERE `coupon_code` = \'' . $coupon_code . '\''
					;
			$database->setQuery( $query );
			$couponid = $database->loadResult();

			// Its not static, so set type to 0
			$this->type = 0;
		}

		if ( $couponid ) {
			// Status = OK
			$this->status = true;

			// establish coupon object
			$this->coupon = new coupon( $database, $this->type );
			$this->coupon->load( $couponid );

			// Check whether coupon is active
			if ( !$this->coupon->active ) {
				$this->setError( _COUPON_ERROR_EXPIRED );
			}

			// load parameters into local array
			$this->discount		= $this->coupon->discount;
			$this->restrictions = $this->coupon->restrictions;

			// Check whether coupon can be used yet
			if ( $this->restrictions['has_start_date'] && !empty( $this->restrictions['start_date'] ) ) {
				$expstamp = strtotime( $this->restrictions['start_date'] );

				// Error: Use of this coupon has not started yet
				if ( ( $expstamp > 0 ) && ( ( $expstamp-time() ) > 0 ) ) {
					$this->setError( _COUPON_ERROR_NOTSTARTED );
				}
			}

			// Check whether coupon is expired
			if ( $this->restrictions['has_expiration'] ) {
				$expstamp = strtotime( $this->restrictions['expiration'] );

				// Error: Use of this coupon has expired
				if ( ( $expstamp > 0 ) && ( ( $expstamp-time() ) < 0 ) ) {
					$this->setError( _COUPON_ERROR_EXPIRED );
					$this->coupon->deactivate();
				}
			}

			// Check for max reuse
			if ( $this->restrictions['has_max_reuse'] ) {
				if ( $this->restrictions['max_reuse'] ) {
					// Error: Max Reuse of this coupon is exceeded
					if ( (int) $this->coupon->usecount > (int) $this->restrictions['max_reuse'] ) {
						$this->setError( _COUPON_ERROR_MAX_REUSE );
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
					$database->setQuery( $query );

					$subscr_status = strtolower( $database->loadResult() );

					// Error: The Subscription this Coupon depends on has run out
					if ( ( strcmp( $subscr_status, 'active' ) === 0 ) || ( ( strcmp( $subscr_status, 'trial' ) === 0 ) && $this->restrictions['allow_trial_depend_subscr'] ) ) {
						$this->setError( _COUPON_ERROR_SPONSORSHIP_ENDED );
						return;
					}
				}
			}
		} else {
			// Error: Coupon does not exist
			$this->setError( _COUPON_ERROR_NOTFOUND );
		}
	}

	function forceload( $coupon_code )
	{
		global $database;

		// Get this coupons id from the static table
		$query = 'SELECT `id`'
				. ' FROM #__acctexp_coupons_static'
				. ' WHERE `coupon_code` = \'' . $coupon_code . '\''
				;
		$database->setQuery( $query );
		$couponid = $database->loadResult();

		if ( $couponid ) {
			// Its static, so set type to 1
			$this->type = 1;
		} else {
			// Coupon not found, take the regular table
			$query = 'SELECT `id`'
					. ' FROM #__acctexp_coupons'
					. ' WHERE `coupon_code` = \'' . $coupon_code . '\''
					;
			$database->setQuery( $query );
			$couponid = $database->loadResult();

			// Its not static, so set type to 0
			$this->type = 0;
		}

		if ( $couponid ) {
			// Status = OK
			$this->status = true;

			// establish coupon object
			$this->coupon = new coupon( $database, $this->type );
			$this->coupon->load( $couponid );
			return true;
		} else {
			return false;
		}
	}

	function switchType()
	{
		global $database;

		// Duplicate Coupon at other table
		$newcoupon = new coupon( $database, !$this->type );
		$newcoupon->createNew( $this->coupon->coupon_code, $this->coupon->created_date );

		// Switch id over to new table max
		$oldid = $this->coupon->id;
		$newid = $newcoupon->getMax();

		// Delete old coupon
		$this->coupon->delete();

		// Create new entry
		$this->coupon = $newcoupon;

		// Migrate usage entries
		$query = 'UPDATE #__acctexp_couponsxuser'
				. ' SET `coupon_id` = \'' . $newid . '\''
				. ' WHERE `coupon_id` = \'' . $oldid . '\''
				;

		$database->setQuery( $query );
		$database->query();
	}

	function incrementCount( $invoice )
	{
		global $database;

		// Get existing coupon relations for this user
		$query = 'SELECT `id`'
				. ' FROM #__acctexp_couponsxuser'
				. ' WHERE `userid` = \'' . $invoice->userid . '\''
				. ' AND `coupon_id` = \'' . $this->coupon->id . '\''
				. ' AND `coupon_type` = \'' . $this->type . '\''
				;

		$database->setQuery( $query );
		$id = $database->loadResult();

		$couponxuser = new couponXuser( $database );

		if ( !empty( $id ) ) {
			// Relation exists, update count
			global $mosConfig_offset;

			$couponxuser->load( $id );
			$couponxuser->usecount += 1;
			$couponxuser->addInvoice( $invoice->invoice_number );
			$couponxuser->last_updated = date( 'Y-m-d H:i:s', time() + $mosConfig_offset*3600 );
			$couponxuser->check();
			$couponxuser->store();
		} else {
			// Relation does not exist, create one
			$couponxuser->createNew( $invoice->userid, $this->coupon, $this->type );
			$couponxuser->addInvoice( $invoice->invoice_number );
			$couponxuser->check();
			$couponxuser->store();
		}

		$this->coupon->incrementcount();
	}

	function decrementCount( $invoice )
	{
		global $database;

		// Get existing coupon relations for this user
		$query = 'SELECT `id`'
				. ' FROM #__acctexp_couponsxuser'
				. ' WHERE `userid` = \'' . $invoice->userid . '\''
				. ' AND `coupon_id` = \'' . $this->coupon->id . '\''
				. ' AND `coupon_type` = \'' . $this->type . '\''
				;

		$database->setQuery( $query );
		$id = $database->loadResult();

		$couponxuser = new couponXuser( $database );

		// Only do something if a relation exists
		if ( $id ) {
			global $mosConfig_offset;

			// Decrement use count
			$couponxuser->load( $id );
			$couponxuser->usecount -= 1;
			$couponxuser->last_updated = date( 'Y-m-d H:i:s', time() + $mosConfig_offset*3600 );

			if ( $couponxuser->usecount ) {
				// Use count is 1 or above - break invoice relation but leave overall relation intact
				$couponxuser->delInvoice( $invoice->invoice_number );
				$couponxuser->check();
				$couponxuser->store();
			} else {
				// Use count is 0 or below - delete relationship
				$couponxuser->delete();
			}
		}

		$this->coupon->decrementCount();
	}

	function checkRestrictions( $metaUser, $original_amount=null, $invoiceFactory=null )
	{
		// Load Restrictions and resulting Permissions
		$restrictions	= $this->getRestrictionsArray();
		$permissions	= $metaUser->permissionResponse( $restrictions );

		// Check for a set usage
		if ( !empty( $this->restrictions['usage_plans_enabled'] ) && !is_null( $invoiceFactory ) ) {
			if ( !empty( $this->restrictions['usage_plans'] ) ) {
				// Check whether this usage is restricted
				$plans = $this->restrictions['usage_plans'];

				if ( in_array( $invoiceFactory->usage, $plans ) ) {
					$permissions['usage'] = true;
				} else {
					$permissions['usage'] = false;
				}
			}
		}

		// Check for Trial only
		if ( $this->discount['useon_trial'] && !$this->discount['useon_full'] && !is_null( $original_amount ) ) {
			if ( !is_null( $original_amount ) ) {
				if ( is_array( $original_amount ) ) {
					if ( isset( $original_amount['amount']['amount1'] ) || isset( $original_amount['amount1'] ) ) {
						$permissions['trial_only'] = true;
					} else {
						$permissions['trial_only'] = false;
					}
				}
			}
		}

		// Check for max reuse per user
		if ( !empty( $this->restrictions['has_max_peruser_reuse'] ) && !empty( $this->restrictions['max_peruser_reuse'] ) ) {
			$used = $metaUser->usedCoupon( $this->coupon->id, $this->type );

			if ( $used == false ) {
				$permissions['max_peruser_reuse'] = true;
			} elseif ( (int) $used  <= (int) $this->restrictions['max_peruser_reuse'] ) {
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
										'plan_overall'		=> 'wrong_plan_overall',
										'plan_amount_min'	=> 'wrong_plan',
										'plan_amount_max'	=> 'wrong_plan_overall',
										'max_reuse'			=> 'max_reuse'
									);

					if ( isset( $errors[$name] ) ) {
						$this->setError( constant( strtoupper( '_coupon_error_' . $errors[$name] ) ) );
					}

					return false;
				}
			}
		}

		return true;
	}

	function getRestrictionsArray()
	{
		$restrictions = array();

		// Check for a fixed GID - this certainly overrides the others
		if ( !empty( $this->restrictions['fixgid_enabled'] ) ) {
			$restrictions['fixgid'] = (int) $this->restrictions['fixgid'];
		} else {
			// No fixed GID, check for min GID
			if ( !empty( $this->restrictions['mingid_enabled'] ) ) {
				$restrictions['mingid'] = (int) $this->restrictions['mingid'];
			}
			// Check for max GID
			if ( !empty( $this->restrictions['maxgid_enabled'] ) ) {
				$restrictions['maxgid'] = (int) $this->restrictions['maxgid'];
			}
		}

		// Check for a directly previously used plan
		if ( !empty( $this->restrictions['previousplan_req_enabled'] ) ) {
			if ( $this->restrictions['previousplan_req'] ) {
				$restrictions['plan_previous'] = (int) $this->restrictions['previousplan_req'];
			}
		}

		// Check for a currently used plan
		if ( !empty( $this->restrictions['currentplan_req_enabled'] ) ) {
			if ( $this->restrictions['currentplan_req'] ) {
				$restrictions['plan_present'] = (int) $this->restrictions['currentplan_req'];
			}
		}

		// Check for a overall used plan
		if ( !empty( $this->restrictions['currentplan_req_enabled'] ) ) {
			if ( $this->restrictions['currentplan_req'] ) {
				$restrictions['plan_overall'] = (int) $this->restrictions['currentplan_req'];
			}
		}

		// Check for a overall used plan with amount minimum
		if ( !empty( $this->restrictions['used_plan_min_enabled'] ) ) {
			if ( $this->restrictions['used_plan_min_amount'] && $this->restrictions['used_plan_min'] ) {
				$restrictions['plan_amount_min'] = ( (int) $this->restrictions['used_plan_min'] )
				. ',' . ( (int) $this->restrictions['used_plan_min_amount'] );
			}
		}

		// Check for a overall used plan with amount maximum
		if ( !empty( $this->restrictions['used_plan_max_enabled'] ) ) {
			if ( $this->restrictions['used_plan_max_amount'] && $this->restrictions['used_plan_max'] ) {
				$restrictions['plan_amount_max'] = ( (int) $this->restrictions['used_plan_max'] )
				. ',' . ( (int) $this->restrictions['used_plan_max_amount'] );
			}
		}

		return $restrictions;
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
						if ( $amount['amount1'] > 0 ) {
							$amount['amount2']	= $this->applyDiscount( $amount['amount3'] );
							$amount['period2']	= $amount['period3'];
							$amount['unit2']	= $amount['unit3'];
						} else {
							$amount['amount1']	= $this->applyDiscount( $amount['amount3'] );
							$amount['period1']	= $amount['period3'];
							$amount['unit1']	= $amount['unit3'];
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
				$amount -= ( ( $amount / 100 ) * $this->discount['amount_percent'] );
			}
			if ( $this->discount['amount_use'] ) {
				$amount -= $this->discount['amount'];
			}
		} else {
			if ( $this->discount['amount_use'] ) {
				$amount -= $this->discount['amount'];
			}
			if ( $this->discount['amount_percent_use'] ) {
				$amount -= ( ( $amount / 100 ) * $this->discount['amount_percent'] );
			}
		}

		$amount = round( $amount, 2 );

		// Fix Amount if broken and return
		return AECToolbox::correctAmount( $amount );
	}
}

class coupon extends serialParamDBTable
{
	/** @var int Primary key */
	var $id					= null;
	/** @var int */
	var $active				= null;
	/** @var int */
	var $ordering			= null;
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

	function coupon( &$db, $type )
	{
		if ( $type ) {
			$this->mosDBTable( '#__acctexp_coupons_static', 'id', $db );
		} else {
			$this->mosDBTable( '#__acctexp_coupons', 'id', $db );
		}
	}

	function declareParamFields()
	{
		return array( 'discount', 'restrictions', 'params', 'micro_integrations'  );
	}

	function deactivate()
	{
		$this->active = 0;
		$this->check();
		$this->store();
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
			global $mosConfig_offset;

			$this->created_date = date( 'Y-m-d H:i:s', time() + $mosConfig_offset*3600 );
		} else {
			$this->created_date = $created;
		}
		$this->usecount = 0;
	}

	function savePOSTsettings( $post )
	{
		if ( !empty( $post['coupon_code'] ) ) {
			$query = 'SELECT `id`'
					. ' FROM #__acctexp_coupons_static'
					. ' WHERE `coupon_code` = \'' . $post['coupon_code'] . '\''
					;
			$this->_db->setQuery( $query );
			$couponid = $this->_db->loadResult();

			if ( empty( $couponid ) ) {
				$query = 'SELECT `id`'
						. ' FROM #__acctexp_coupons'
						. ' WHERE `coupon_code` = \'' . $post['coupon_code'] . '\''
						;
				$this->_db->setQuery( $query );
				$couponid = $this->_db->loadResult();
			}

			if ( !empty( $couponid ) && ( $couponid !== $this->id ) ) {
				$post['coupon_code'] = $this->generateCouponCode();
			}
		}

		// Filter out fixed variables
		$fixed = array( 'active', 'name', 'desc', 'coupon_code', 'usecount', 'micro_integrations' );

		foreach ( $fixed as $varname ) {
			$this->$varname = $post[$varname];
			unset( $post[$varname] );
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

		// The rest of the vars are restrictions
		$restrictions = array();

		foreach ( $post as $varname => $content ) {
			$restrictions[$varname] = $content;
			unset( $post[$varname] );
		}

		$this->saveRestrictions( $restrictions );
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
		$this->check();
		$this->store();
	}

	function decrementCount()
	{
		$this->usecount -= 1;
		$this->check();
		$this->store();
	}

	function generateCouponCode( $maxlength = 6 )
	{
		global $database;

		$numberofrows = 1;

		while ( $numberofrows ) {
			$inum =	strtoupper( substr( base64_encode( md5( rand() ) ), 0, $maxlength ) );
			// check single coupons
			$query = 'SELECT count(*)'
					. ' FROM #__acctexp_coupons'
					. ' WHERE `coupon_code` = \'' . $inum . '\''
					;
			$database->setQuery( $query );
			$numberofrows_normal = $database->loadResult();

			// check static coupons
			$query = 'SELECT count(*)'
					. ' FROM #__acctexp_coupons_static'
					. ' WHERE `coupon_code` = \'' . $inum . '\''
					;
			$database->setQuery( $query );
			$numberofrows_static = $database->loadResult();

			$numberofrows = $numberofrows_normal + $numberofrows_static;
		}
		return $inum;
	}
}

class couponXuser extends paramDBTable
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
		$this->mosDBTable( '#__acctexp_couponsxuser', 'id', $db );
	}

	function createNew( $userid, $coupon, $type, $params=null )
	{
		global $mosConfig_offset;

		$this->id = 0;
		$this->coupon_id = $coupon->id;
		$this->coupon_type = $type;
		$this->coupon_code = $coupon->coupon_code;
		$this->userid = $userid;
		$this->created_date = date( 'Y-m-d H:i:s', time() + $mosConfig_offset*3600 );
		$this->last_updated = date( 'Y-m-d H:i:s', time() + $mosConfig_offset*3600 );

		if ( is_array( $params ) ) {
			$this->params = $params;
		}

		$this->usecount = 1;

		$this->check();
		$this->store();
	}

	function getInvoiceList()
	{
		$params = $this->getParams();

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

class aecExport extends serialParamDBTable
{
	/** @var int Primary key */
	var $id					= null;
	/** @var int */
	var $system				= null;
	/** @var string */
	var $name				= null;
	/** @var datetime */
	var $created_date 		= null;
	/** @var datetime */
	var $lastused_date 		= null;
	/** @var text */
	var $filter				= null;
	/** @var text */
	var $options			= null;
	/** @var text */
	var $params				= null;

	function aecExport( &$db )
	{
		$this->mosDBTable( '#__acctexp_export', 'id', $db );
	}

	function useExport()
	{
		global $mosConfig_offset, $mosConfig_absolute_path;

		// Load Exporting Class
		$filename = $mosConfig_absolute_path . '/components/com_acctexp/lib/export/' . $this->params->export_method . '.php';
		$classname = 'AECexport_' . $this->params->export_method;

		include_once( $filename );

		$exphandler = new $classname();

		$fname = 'aecexport_' . urlencode( stripslashes( $this->name ) ) . '_' . date( 'Y_m_d', time() + $mosConfig_offset*3600 );

		// Send download header
		header("Pragma: public");
		header("Expires: 0");
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");

		header("Content-Type: application/force-download");
		header("Content-Type: application/octet-stream");

		header("Content-Type: application/download");
		header('Content-Disposition: inline; filename="' . $fname . '.csv"');

		// Assemble Database call
		$where = array();
		if ( !empty( $this->filter->planid ) ) {
			$where[] = '`plan` IN (' . implode( ',', $this->filter->planid ) . ')';
		}

		$query = 'SELECT a.id, a.userid'
				. ' FROM #__acctexp_subscr AS a'
				. ' INNER JOIN #__users AS b ON a.userid = b.id';

		if ( !empty( $where ) ) {
			$query .= ' WHERE ( ' . implode( ' OR ', $where ) . ' )';
		}

		if ( !empty( $this->filter->status ) ) {
			foreach ( $this->filter->status as $status ) {
				if ( !empty( $where ) ) {
					$query .= ' AND LOWER( `status` ) = \'' . strtolower( $status ) . '\'';
				} else {
					$query .= ' WHERE LOWER( `status` ) = \'' . strtolower( $status ) . '\'';
				}
			}
		}

		if ( !empty( $this->filter->orderby ) ) {
			$query .= ' ORDER BY ' . $this->filter->orderby . '';
		}

		$this->_db->setQuery( $query );

		// Fetch Userlist
		$userlist = $this->_db->loadObjectList();

		// Plans Array
		$plans = array();

		// Iterate through userlist
		if ( !empty( $userlist ) ) {
			foreach ( $userlist as $entry ) {
				$metaUser = new metaUser( $entry->userid );
				$metaUser->moveFocus( $entry->id );

				$planid = $metaUser->focusSubscription->plan;

				if ( !isset( $plans[$planid] ) ) {
					$plans[$planid] = new SubscriptionPlan( $this->_db );
					$plans[$planid]->load( $planid );
				}

				$line = AECToolbox::rewriteEngine( $this->options->rewrite_rule, $metaUser, $plans[$planid] );
				$larray = explode( ';', $line );

				// Remove whitespaces and newlines
				foreach( $larray as $larrid => $larrval ) {
					$larray[$larrid] = trim($larrval);
				}

				echo $exphandler->export_line( $larray );
			}
		}

		$this->setUsedDate();
		exit;
	}

	function setUsedDate()
	{
		global $mosConfig_offset;

		$this->lastused_date = date( 'Y-m-d H:i:s', time() + $mosConfig_offset*3600 );
		$this->check();
		$this->store();
	}

	function save( $name, $filter, $options, $params, $system=false )
	{
		global $mosConfig_offset;

		// Drop old system saves to always keep 10 records
		if ( $system ) {
			$query = 'SELECT count(*) '
					. ' FROM #__acctexp_export'
					. ' WHERE `system` = \'1\''
					;
			$this->_db->setQuery( $query );
			$sysrows = $this->_db->loadResult();

			if ( $sysrows > 9 ) {
				$query = 'DELETE'
						. ' FROM #__acctexp_export'
						. ' WHERE `system` = \'1\''
						. ' ORDER BY `id` ASC'
						. ' LIMIT 1'
						;
				$this->_db->setQuery( $query );
				$this->_db->query();
			}
		}

		$this->name = $name;
		$this->system = $system ? 1 : 0;
		$this->filter = $filter;
		$this->options = $options;
		$this->params = $params;

		if ( ( strcmp( $this->created_date, '0000-00-00 00:00:00' ) === 0 ) || empty( $this->created_date ) ) {
			$this->created_date = date( 'Y-m-d H:i:s', time() + $mosConfig_offset*3600 );
		}

		$this->check();
		$this->store();
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

			foreach ( $status as $stname => $ststatus ) {
				if ( !$ststatus ) {
					return false;
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

		// Check for a directly previously used plan
		if ( !empty( $restrictions['previousplan_req_enabled'] ) ) {
			if ( isset( $restrictions['previousplan_req'] ) ) {
				$newrest['plan_previous'] = $restrictions['previousplan_req'];
			}
		}

		// Check for a currently used plan
		if ( !empty( $restrictions['currentplan_req_enabled'] ) ) {
			if ( isset( $restrictions['currentplan_req'] ) ) {
				$newrest['plan_present'] = $restrictions['currentplan_req'];
			}
		}

		// Check for a overall used plan
		if ( !empty( $restrictions['overallplan_req_enabled'] ) ) {
			if ( isset( $restrictions['overallplan_req'] ) ) {
				$newrest['plan_overall'] = $restrictions['overallplan_req'];
			}
		}

		// Check for a directly previously used plan
		if ( !empty( $restrictions['previousplan_req_enabled_excluded'] ) ) {
			if ( isset( $restrictions['previousplan_req_excluded'] ) ) {
				$newrest['plan_previous_excluded'] = $restrictions['previousplan_req_excluded'];
			}
		}

		// Check for a currently used plan
		if ( !empty( $restrictions['currentplan_req_enabled_excluded'] ) ) {
			if ( isset( $restrictions['currentplan_req_excluded'] ) ) {
				$newrest['plan_present_excluded'] = $restrictions['currentplan_req_excluded'];
			}
		}

		// Check for a overall used plan
		if ( !empty( $restrictions['overallplan_req_enabled_excluded'] ) ) {
			if ( isset( $restrictions['overallplan_req_excluded'] ) ) {
				$newrest['plan_overall_excluded'] = $restrictions['overallplan_req_excluded'];
			}
		}

		// Check for a overall used plan with amount minimum
		if ( !empty( $restrictions['used_plan_min_enabled'] ) ) {
			if ( isset( $restrictions['used_plan_min_amount'] ) && isset( $restrictions['used_plan_min'] ) ) {
				$newrest['plan_amount_min'] = ( (int) $restrictions['used_plan_min'] )
				. ',' . ( (int) $restrictions['used_plan_min_amount'] );
			}
		}

		// Check for a overall used plan with amount maximum
		if ( !empty( $restrictions['used_plan_max_enabled'] ) ) {
			if ( isset( $restrictions['used_plan_max_amount'] ) && isset( $restrictions['used_plan_max'] ) ) {
				$newrest['plan_amount_max'] = ( (int) $restrictions['used_plan_max'] )
				. ',' . ( (int) $restrictions['used_plan_max_amount'] );
			}
		}

		// Check for a directly previously used plan
		if ( !empty( $restrictions['custom_restrictions_enabled'] ) ) {
			if ( isset( $restrictions['custom_restrictions'] ) ) {
				$newrest['custom_restrictions'] = aecRestrictionHelper::transformCustomRestrictions( $restrictions['custom_restrictions'] );
			}
		}

		return $newrest;
	}

	function transformCustomRestrictions( $customrestrictions )
	{
		$cr = explode( "\n", $customrestrictions);

		$custom = array();
		foreach ( $cr as $field ) {
			$custom[] = explode( ' ', $field );
		}

		return $custom;
	}

	function paramList()
	{
		$list = array( 'mingid_enabled', 'mingid', 'fixgid_enabled', 'fixgid',
						'maxgid_enabled', 'maxgid', 'previousplan_req_enabled', 'previousplan_req',
						'currentplan_req_enabled', 'currentplan_req', 'overallplan_req_enabled', 'overallplan_req',
						'previousplan_req_enabled_excluded', 'previousplan_req_excluded', 'currentplan_req_enabled_excluded', 'currentplan_req_excluded',
						'overallplan_req_enabled_excluded', 'overallplan_req_excluded', 'used_plan_min_enabled', 'used_plan_min_amount',
						'used_plan_min', 'used_plan_max_enabled', 'used_plan_max_amount', 'used_plan_max',
						'custom_restrictions_enabled', 'custom_restrictions' );

		return $list;
	}

	function getParams()
	{
		$params = array();
		$params['mingid_enabled']					= array( 'list_yesno', 0 );
		$params['mingid']							= array( 'list', 18 );
		$params['fixgid_enabled']					= array( 'list_yesno', 0 );
		$params['fixgid']							= array( 'list', 19 );
		$params['maxgid_enabled']					= array( 'list_yesno', 0 );
		$params['maxgid']							= array( 'list', 21 );
		$params['previousplan_req_enabled'] 		= array( 'list_yesno', 0 );
		$params['previousplan_req']					= array( 'list', 0 );
		$params['previousplan_req_enabled_excluded']	= array( 'list_yesno', 0 );
		$params['previousplan_req_excluded']			= array( 'list', 0 );
		$params['currentplan_req_enabled']			= array( 'list_yesno', 0 );
		$params['currentplan_req']					= array( 'list', 0 );
		$params['currentplan_req_enabled_excluded']	= array( 'list_yesno', 0 );
		$params['currentplan_req_excluded']			= array( 'list', 0 );
		$params['overallplan_req_enabled']			= array( 'list_yesno', 0 );
		$params['overallplan_req']					= array( 'list', 0 );
		$params['overallplan_req_enabled_excluded']	= array( 'list_yesno', 0 );
		$params['overallplan_req_excluded']			= array( 'list', 0 );
		$params['used_plan_min_enabled']			= array( 'list_yesno', 0 );
		$params['used_plan_min_amount']				= array( 'inputB', 0 );
		$params['used_plan_min']					= array( 'list', 0 );
		$params['used_plan_max_enabled']			= array( 'list_yesno', 0 );
		$params['used_plan_max_amount']				= array( 'inputB', 0 );
		$params['used_plan_max']					= array( 'list', 0 );
		$params['custom_restrictions_enabled']		= array( 'list_yesno', '' );
		$params['custom_restrictions']				= array( 'inputD', '' );

		return $params;
	}

	function getLists( $params_values, $restrictions_values )
	{
		global $database, $my, $acl;

		// ensure user can't add group higher than themselves
		$my_groups = $acl->get_object_groups( 'users', $my->id, 'ARO' );
		if ( is_array( $my_groups ) && count( $my_groups ) > 0) {
			$ex_groups = $acl->get_group_children( $my_groups[0], 'ARO', 'RECURSE' );
		} else {
			$ex_groups = array();
		}

		$gtree = $acl->get_group_children_tree( null, 'USERS', true );

		// mic: exclude public front- & backend
		$ex_groups[] = 28;
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

		// Create GID related Lists
		$lists['gid'] 		= mosHTML::selectList( $gtree, 'gid', 'size="6"', 'value', 'text', arrayValueDefault($params_values, 'gid', 18) );
		$lists['mingid'] 	= mosHTML::selectList( $gtree, 'mingid', 'size="6"', 'value', 'text', arrayValueDefault($restrictions_values, 'mingid', 18) );
		$lists['fixgid'] 	= mosHTML::selectList( $gtree, 'fixgid', 'size="6"', 'value', 'text', arrayValueDefault($restrictions_values, 'fixgid', 19) );
		$lists['maxgid'] 	= mosHTML::selectList( $gtree, 'maxgid', 'size="6"', 'value', 'text', arrayValueDefault($restrictions_values, 'maxgid', 21) );

		$available_plans = array();
		$available_plans[] = mosHTML::makeOption( '0', _PAYPLAN_NOPLAN );

		// Fetch Payment Plans
		$query = 'SELECT `id` AS value, `name` AS text'
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

		$planrest = array( 'previousplan_req', 'currentplan_req', 'overallplan_req', 'used_plan_min', 'used_plan_max', 'previousplan_req_excluded', 'currentplan_req_excluded', 'overallplan_req_excluded'  );

		foreach ( $planrest as $name ) {
			$lists[$name] = mosHTML::selectList( $all_plans, $name.'[]', 'size="' . $total_all_plans . '" multiple="multiple"', 'value', 'text', arrayValueDefault($restrictions_values, $name, 0) );
		}

		$available_groups = array();
		$available_groups[] = mosHTML::makeOption( '0', _PAYPLAN_NOGROUP );

		// Fetch Item Groups
		$query = 'SELECT `id` AS value, `name` AS text'
				. ' FROM #__acctexp_itemgroups'
				;
		$database->setQuery( $query );
		$groups = $database->loadObjectList();

	 	if ( is_array( $groups ) ) {
	 		$all_groups	= array_merge( $available_groups, $groups );
	 	} else {
	 		$all_groups	= $available_groups;
	 	}
		$total_all_groups	= min( max( ( count( $all_groups ) + 1 ), 4 ), 20 );

		$grouprest = array( 'previousgroup_req', 'currentgroup_req', 'overallgroup_req', 'used_group_min', 'used_group_max', 'previousgroup_req_excluded', 'currentgroup_req_excluded', 'overallgroup_req_excluded' );

		foreach ( $grouprest as $name ) {
			$lists[$name] = mosHTML::selectList( $all_groups, $name.'[]', 'size="' . $total_all_groups . '" multiple="multiple"', 'value', 'text', arrayValueDefault($restrictions_values, $name, 0) );
		}

		return $lists;
	}

	function echoSettings( $aecHTML )
	{
		$width = 1200;

		$stdvars =	array(	array(
									array( 'mingid_enabled', 'mingid' ),
									array( 'fixgid_enabled', 'fixgid' ),
									array( 'maxgid_enabled', 'maxgid' ),
							),	array(
									array( 'previous*_req_enabled', 'previous*_req' ),
									array( 'previous*_req_enabled_excluded', 'previous*_req_excluded' ),
									array( 'current*_req_enabled', 'current*_req' ),
									array( 'current*_req_enabled_excluded', 'current*_req_excluded' ),
									array( 'overall*_req_enabled', 'overall*_req' ),
									array( 'overall*_req_enabled_excluded', 'overall*_req_excluded' ),
							), array(
									array( 'used_*_min_enabled', 'used_*_min_amount', 'used_*_min' ),
									array( 'used_*_max_enabled', 'used_*_max_amount', 'used_*_max' ),
							)
					);

		$types = array( 'plan', 'group' );

		foreach ( $types as $type ) {
			foreach ( $stdvars as $block ) {
				// non-* blocks only once
				if ( ( strpos( $block[0][0], '*' ) === false ) && ( $type != 'plan') ) {
					continue;
				}

				echo '<tr><td><div class="userinfobox">';
				$sblockwidth = $width / count( $block );
				foreach ( $block as $sblock ) {
					echo '<div style="position:relative;float:left;width:' . $sblockwidth . 'px;">';
					foreach ( $sblock as $vname ) {
						echo $aecHTML->createSettingsParticle( str_replace( '*', $type, $vname ) );
					}
					echo '</div>';
				}
				echo '</div></td></tr>';
			}
		}
	}
}

// --- Not yet active code for future features ---

class tokenCheck
{
	function tokenCheck( $token_id, $userid )
	{
		global $database;

		$token = new accessToken($database);
		$token->load( $token_id );

		$user = new mosUser( $database );
		$user->load( $userid );
		$groups	= GeneralInfoRequester::getLowerACLGroup( $this->mingid );

		$status = array();
		$status['status'] = true;
		$status['reason'] = 'none';

		if ( !$token->active ) {
			$status['status'] = false;
			$status['reason'] = 'deactivated';
		} elseif ( !in_array( $user->gid, $groups ) ) {
			$status['status'] = false;
			$status['reason'] = 'permissions';
		}

		if ( !$status['reason'] ) {
			return $status;
		}

		$query = 'SELECT `id`'
				. ' FROM #__acctexp_usertokens'
				. ' WHERE `userid` = \'' . (int) $userid . '\''
				. ' AND `token_id` = \'' . $token . '\''
				;
		$database->setQuery( $query );
		$usertoken_id = $database->loadResult();

		if ( $usertoken_id ) {
			$usertoken = new userToken( $database );
			$usertoken->load( $usertoken_id );
		} else {
			return false;
		}
	}
}

class userToken extends paramDBTable
{
	/** @var int Primary key */
	var $id					= null;
	/** @var int */
	var $userid				= null;
	/** @var int */
	var $active				= null;
	/** @var string */
	var $token_id			= null;
	/** @var datetime */
	var $created_date 		= null;
	/** @var datetime */
	var $firstused_date		= null;
	/** @var datetime */
	var $expiration			= null;

	function userToken( &$db )
	{
		$this->mosDBTable( '#__acctexp_usertokens', 'id', $db );
	}

	function createToken( $groupid, $tokenid, $userid )
	{
		global $database, $mosConfig_offset;

		$now = time() + $mosConfig_offset*3600;

		$token_group = new tokenGroup($database);
		$token_group->load($groupid);

		$this->id			= 0;
		$this->userid		= $userid;
		$this->token_id		= $tokenid;
		$this->created_date	= $now;

		if ( $token_group->peramount ) {
			$expiration = AECToolbox::computeExpiration( $token_group->peramount, $token_group->perunit, $now );
			$this->expiration = $expiration;
		} else {
			// We'll leave this at zero - the best indication that there is no expiration
		}
	}
}

class tokenBatch extends mosDBTable
{
	/** @var int Primary key */
	var $id					= null;
	/** @var int */
	var $userid				= null;
	/** @var int */
	var $amount				= null;
	/** @var int */
	var $group_id			= null;
	/** @var datetime */
	var $created_date 		= null;
	/** @var datetime */
	var $expiration			= null;

	function tokenBatch(&$db)
	{
		$this->mosDBTable( '#__acctexp_tokenbatches', 'id', $db );
	}

	function tearToken( $tokenid, $userid )
	{
		global $database;

		$token = new userToken($database);
		$result = $token->createToken( $this->group_id, $tokenid, $userid );

		if ( $result === true ) {

		} else {

		}
	}
}

class accessToken extends mosDBTable
{
	/** @var int Primary key */
	var $id					= null;
	/** @var int */
	var $token_id			= null;
	/** @var int */
	var $active				= null;
	/** @var int */
	var $token_group_id		= null;

	function accessToken(&$db)
	{
		$this->mosDBTable( '#__acctexp_accesstokens', 'id', $db );
	}
}

class tokenGroup extends mosDBTable
{
	/** @var int Primary key */
	var $id					= null;
	/** @var int */
	var $active				= null;
	/** @var string */
	var $name				= null;
	/** @var string */
	var $desc				= null;
	/** @var string */
	var $amount				= null;
	/** @var int */
	var $period				= null;
	/** @var string */
	var $perunit			= null;
	/** @var datetime */
	var $created_date 		= null;
	/** @var int */
	var $has_start_date		= null;

	function tokenGroup(&$db)
	{
		$this->mosDBTable( '#__acctexp_tokengroups', 'id', $db );
	}
}

class PluginHandler
{
	function PluginHandler() { }

		function &getPlugin($type, $plugin = null)
		{
				$result = array();

				$plugins = PluginHandler::_load();

				$total = count($plugins);
				for($i = 0; $i < $total; $i++)
				{
						if(is_null($plugin))
						{
								if($plugins[$i]->type == $type) {
										$result[] = $plugins[$i];
								}
						}
						else
						{
								if($plugins[$i]->type == $type && $plugins[$i]->name == $plugin) {
										$result = $plugins[$i];
										break;
								}
						}

				}

				return $result;
	}

	function _load()
	{
				$db        =& JFactory::getDBO();
				$user    =& JFactory::getUser();

				if (isset($user))
				{
						$aid = $user->get('aid', 0);

						$query = 'SELECT folder AS type, element AS name, params'
								. ' FROM #__plugins'
								. ' WHERE access <= ' . (int) $aid
								. ' ORDER BY ordering';
				}
				else
				{
						$query = 'SELECT folder AS type, element AS name, params'
								. ' FROM #__plugins'
								. ' ORDER BY ordering';
				}

				$db->setQuery( $query );

				if (!($plugins = $db->loadObjectList())) {
						JError::raiseWarning( 'SOME_ERROR_CODE', "Error loading Plugins: " . $db->getErrorMsg());
						return false;
				}

				return $plugins;
	}
}
?>