<?php
/**
 * @version $Id: acctexp.class.php 16 2007-06-27 09:04:04Z mic $
 * @package AEC - Account Control Expiration - Subscription component for Joomla! OS CMS
 * @subpackage Core Class
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

global $mosConfig_absolute_path, $mosConfig_offset_user, $aecConfig;

if ( !is_object( $aecConfig ) ) {
	$aecConfig = new Config_General( $database );
}

if ( !defined ( 'AEC_FRONTEND' ) && !defined( '_AEC_LANG' ) ) {
	// mic: call only if called from the backend
	$langPath = $mosConfig_absolute_path . '/administrator/components/com_acctexp/com_acctexp_language_backend/';
	if ( file_exists( $langPath . $GLOBALS['mosConfig_lang'] . '.php' )) {
			include_once( $langPath . $GLOBALS['mosConfig_lang'] . '.php' );
	} else {
			include_once( $langPath. 'english.php' );
	}
	include_once( $langPath . 'general.php' );
}

if ( !class_exists( 'paramDBTable' ) ) {
	include_once( $mosConfig_absolute_path . '/components/com_acctexp/lib/eucalib/eucalib.common.php' );
}

// compatibility w/ Mambo
if ( empty( $mosConfig_offset_user ) ) {
	global $mosConfig_offset;
	$mosConfig_offset_user = $mosConfig_offset;
}

class metaUser
{
	/** @var int */
	var $userid				= null;
	/** @var object */
	var $cmsUser			= null;
	/** @var object */
	var $objExpiration		= null;
	/** @var object */
	var $objSubscription	= null;
	/** @var int */
	var $hasExpiration		= null;
	/** @var int */
	var $hasSubscription	= null;

	function metaUser( $userid )
	{
		global $database;

		$this->userid = $userid;

		$this->cmsUser = new mosUser( $database );
		$this->cmsUser->load( $userid );

		$aecexpid = AECfetchfromDB::ExpirationIDfromUserID( $userid );
		$this->objExpiration = new AcctExp( $database );
		if ( $aecexpid ) {
			$this->objExpiration->load( $aecexpid );
			$this->hasExpiration = 1;
		} else {
			$this->hasExpiration = 0;
		}

		$aecid = AECfetchfromDB::SubscriptionIDfromUserID( $userid );
		$this->objSubscription = new Subscription( $database );
		if ( $aecid ) {
			$this->objSubscription->load( $aecid );
			$this->hasSubscription = 1;
		} else {
			$this->hasSubscription = 0;
		}

	}

	function instantGIDchange( $gid )
	{
		global $database, $acl;

		if ( $this->cmsUser->gid >= 24 ) {
			$query = 'SELECT count(*)'
			. ' FROM #__core_acl_groups_aro_map'
			. ' WHERE group_id = \'25\''
			;
			$database->setQuery( $query );
			if ( $database->loadResult() <= 1) {
				return false;
			}
		}

		// Get ARO ID for user
		$query = 'SELECT aro_id'
		. ' FROM #__core_acl_aro'
		. ' WHERE value = \'' . (int) $this->userid . '\''
		;
		$database->setQuery( $query );
		$aro_id = $database->loadResult();

		// Carry out ARO ID -> ACL group mapping
		$query = 'UPDATE #__core_acl_groups_aro_map'
		. ' SET group_id = \'' . (int) $gid . '\''
		. ' WHERE aro_id = \'' . $aro_id . '\''
		;
		$database->setQuery( $query );
		$database->query() or die( $database->stderr() );

		// Moxie Mod - updated to add usertype to users table and update session table for immediate access to usertype features
		$gid_name = $acl->get_group_name( $gid, 'ARO' );

		$query = 'UPDATE #__users'
		. ' SET gid = \'' .  (int) $gid . '\', usertype = \'' . $gid_name . '\''
		. ' WHERE id = \''  . (int) $this->userid . '\''
		;
		$database->setQuery( $query );
		$database->query() or die( $database->stderr() );

		$query = 'UPDATE #__session'
		. ' SET usertype = \'' . $gid_name . '\''
		. ' WHERE userid = \'' . (int) $this->userid . '\''
		;
		$database->setQuery( $query );
		$database->query() or die( $database->stderr() );
	}

	function loadCBuser()
	{
		global $database;

				$query = 'SELECT name'
				. ' FROM #__comprofiler_fields'
				. ' WHERE `table` != \'#__users\''
				. ' AND `name` != \'NA\'';
		$database->setQuery( $query );
		$fields = $database->loadResultArray();

		$query = 'SELECT cbactivation' . ( !empty( $fields ) ? ', ' . implode( ', ', $fields ) : '')
				. ' FROM #__comprofiler'
				. ' WHERE user_id = \'' . (int) $this->userid . '\'';
		$database->setQuery( $query );
		$database->loadObject( $this->cbUser );

		if ( is_object( $this->cbUser ) ) {
			$this->hasCBprofile = true;
		} else {
			$this->hasCBprofile = false;
		}
	}

	function CustomRestrictionResponse( $restrictions )
	{
		$s = array();
		$n = 0;
		foreach ( $restrictions as $restriction ) {
			$check1 = AECToolbox::rewriteEngine( $restriction[0], $this );
			$check2 = AECToolbox::rewriteEngine( $restriction[2], $this );

			switch ( $restriction[1] ) {
				case '=':
					$status = (bool) ( $check1 == $check2 );
					break;
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

			$s['customchecker'.$n] = $status;
			$n++;
		}

		return $s;
	}

	function permissionResponse( $restrictions )
	{
		if ( is_array( $restrictions ) ) {
			$return = array();
			foreach ( $restrictions as $name => $value ) {
				if ( !is_null( $value ) && !($value === "") ) {
					switch ( $name ) {
						// Check for set userid
						case 'userid':
							if ( $this->cmsUser->id === $value ) {
								$status = true;
							} else {
								$status = false;
							}
							break;
						// Check for a certain GID
						case 'fixgid':
							if ( (int) $value === (int) $this->cmsUser->gid ) {
								$status = true;
							} else {
								$status = false;
							}
							break;
						// Check for Minimum GID
						case 'mingid':
							$groups = GeneralInfoRequester::getLowerACLGroup( (int) $this->cmsUser->gid );
							if ( in_array( (int) $value, (array) $groups ) ) {
								$status = true;
							} else {
								$status = false;
							}
							break;
						// Check for Maximum GID
						case 'maxgid':
							$groups = GeneralInfoRequester::getLowerACLGroup( $value );
							if ( in_array( (int) $this->cmsUser->gid, (array) $groups) ) {
								$status = true;
							} else {
								$status = false;
							}
							break;
						// Check whether the user is currently in the right plan
						case 'plan_present':
							if ( $this->hasSubscription ) {
								if ( (int) $this->objSubscription->plan === (int) $value ) {
									$status = true;
								} else {
									$status = false;
								}
							} else {
								if ( $value === 0 ) {
									$status = true;
								} else {
									$status = false;
								}
							}
							break;
						// Check whether the user was in the correct plan before
						case 'plan_previous':
							if ( $this->hasSubscription ) {
								if (
									( (int) $this->objSubscription->previous_plan === (int) $value )
									|| ( ( ( (int) $value ) === 0 ) && is_null( $this->objSubscription->previous_plan ) )
									) {
									$status = true;
								} else {
									$status = false;
								}
							} else {
								if ( ( (int) $value ) === 0 ) {
									$status = true;
								} else {
									$status = false;
								}
							}
							break;
						// Check whether the user has used the right plan before
						case 'plan_overall':
							if ( $this->hasSubscription ) {
								$array = $this->objSubscription->getUsedPlans();
								if ( ( (int) $value ) === 0 ) {
									$status = false;
								} elseif ( ( isset( $array[(int) $value] ) || ( $this->objSubscription->plan == $value ) ) ) {
									$status = true;
								} else {
									$status = false;
								}
							} else {
								if ( ( (int) $value ) === 0 ) {
									$status = true;
								} else {
									$status = false;
								}
							}
							break;
						// Check whether the user has used the plan at least a certain number of times
						case 'plan_amount_min':
							if ( $this->hasSubscription ) {
								$usage = $this->objSubscription->getUsedPlans();
								$check = explode( ',', $value );
								if ( isset( $usage[(int) $check[0]] ) ) {
									// We have to add one here if the user is currently in the plan
									if ( (int) $this->objSubscription->plan === (int) $check[0] ) {
										$used_times = (int) $check[1] + 1;
									} else {
										$used_times = (int) $check[1];
									}

									if ( $usage[(int) $check[0]] >= (int) $used_times ) {
										$status = true;
									} else {
										$status = false;
									}
								} else {
									$status = false;
								}
							} else {
								$status = false;
							}
							break;
						// Check whether the user has used the plan at max a certain number of times
						case 'plan_amount_max':
							if ( $this->hasSubscription ) {
								$usage = $this->objSubscription->getUsedPlans();
								$check = explode( ',', $value );
								if ( isset( $usage[(int) $check[0]] ) ) {
									// We have to add one here if the user is currently in the plan
									if ( (int) $this->objSubscription->plan === (int) $check[0] ) {
										$used_times = (int) $check[1] + 1;
									} else {
										$used_times = (int) $check[1];
									}

									if ( $usage[(int) $check[0]] <= (int) $used_times ) {
										$status = true;
									} else {
										$status = false;
									}
								} else {
									$status = true;
								}
							} else {
								$status = true;
							}
							break;
					}
				}
				$return[$name] = $status;
			}
			return $return;
		} else {
			return false;
		}
	}
}

/**
 * acctexp table class
 */

class AcctExp extends mosDBTable
{
	/** @var int Primary key */
	var $id=null;
	/** @var int */
	var $userid=null;
	/** @var time */
	var $expiration=null;

	/**
	 * @param database A database connector object
	 */
	function AcctExp( &$db )
	{
	 	$this->mosDBTable( '#__acctexp', 'id', $db );
	}

	function loadUserid( $userid )
	{
		global $database;

		$query = 'SELECT id'
		. ' FROM #__acctexp'
		. ' WHERE userid = \'' . (int) $userid . '\''
		;
		$database->setQuery( $query );

		$id = $database->loadResult();

		$this->load( $id ? $id : 0 );
	}

	function is_expired( $offset=false )
	{
		global $database, $mosConfig_offset_user, $aecConfig;

		if ( !($this->expiration === '9999-12-31 00:00:00') ) {
			$expiration_cushion = str_pad( $aecConfig->cfg['expiration_cushion'], 2, '0', STR_PAD_LEFT );

			if ( $offset ) {
				$expstamp = strtotime( ( '-' . $offset . ' days' ), strtotime( $this->expiration ) );
			} else {
				$expstamp = strtotime( ( '+' . $expiration_cushion . ' hours' ), strtotime( $this->expiration ) );
			}

			if ( ( $expstamp > 0 ) && ( ( $expstamp - ( time() + $mosConfig_offset_user*3600 ) ) < 0 ) ) {
				return true;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}

	function manualVerify()
	{
		if ( $this->is_expired() ) {
			mosRedirect( AECToolbox::deadsureURL( '/index.php?option=com_acctexp&task=expired&userid=' . (int) $this->userid ) );
			return false;
		} else {
			return true;
		}
	}

	function setExpiration( $unit, $value, $extend )
	{
		global $mainframe, $mosConfig_offset_user;

		$now = time() + $mosConfig_offset_user*3600;

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
	}

	function frontendping()
	{
		global $database, $aecConfig;

		if ( !is_null( $aecConfig->cfg['heartbeat_cycle'] ) || ($aecConfig->cfg['heartbeat_cycle'] == 0) ) {
			$this->ping( $aecConfig->cfg['heartbeat_cycle'] );
		}
	}

	function backendping()
	{
		global $database, $aecConfig;

		if ( !is_null( $aecConfig->cfg['heartbeat_cycle_backend'] ) || !($aecConfig->cfg['heartbeat_cycle_backend'] == 0) ) {
			$this->ping( $aecConfig->cfg['heartbeat_cycle_backend'] );
		}
	}

	function ping( $configCycle )
	{
		global $mainframe, $mosConfig_offset_user;

		if ( $this->last_beat ) {
			$ping	= strtotime( $this->last_beat ) + $configCycle*3600;
		} else {
			$ping = 0;
		}

		if ( ( $ping - (time() + $mosConfig_offset_user*3600) ) <= 0 ) {
			$this->last_beat = date( 'Y-m-d H:i:s', time() + $mosConfig_offset_user*3600 );
			$this->check();
			$this->store();

			$this->beat();
		} else {
			// sleep, mechanical Hound, but do not sleep / kept awake with wolves teeth
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
		. ' WHERE active = \'1\''
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
		$query = 'SELECT a.id'
		. ' FROM #__acctexp as a'
		. ' INNER JOIN #__acctexp_subscr as b ON a.userid = b.userid'
		. ' WHERE a.expiration <= \'' . $expiration_limit . '\''
		. ' AND b.status != \'Expired\''
		. ' AND b.status != \'Closed\''
		. ' AND b.status != \'Excluded\''
		. ' ORDER BY a.expiration'
		;
		$database->setQuery( $query );
		$user_list = $database->loadResultArray();

		$expired_users		= array();
		$pre_expired_users	= array();
		$found_expired		= 1;
		$e					= 0;
		$pe					= 0;
		$exp_actions		= 0;
		$exp_users			= 0;

		// Efficient way to check for expired users without checking on each one
		if ( !empty( $user_list ) ) {
			foreach ( $user_list as $exp_id ) {
				$expiration = new AcctExp($database);
				$expiration->load( $exp_id );

				if ( $expiration->id ) {
					$query = 'SELECT id'
					. ' FROM #__users'
					. ' WHERE id = \'' . $expiration->userid . '\''
					;
					$database->setQuery( $query );
					if ( $database->loadResult() ) {
						if ( $found_expired ) {
							$found_expired = $expiration->is_expired();

							if ( $found_expired && !in_array( $expiration->userid, $expired_users ) ) {
								$expired_users[] = $expiration->userid;
							}
						}

						if ( !$found_expired && !in_array( $expiration->userid, $pre_expired_users ) ) {
							if ( $pre_expiration ) {
								$pre_expired_users[] = $expiration->userid;
							}
						}
					}
				}
			}

			if ( !empty( $expired_users ) ) {
				foreach ( $expired_users as $n ) {
					$subscription = new Subscription( $database );
					$subscription->loadUserID($n);

					if ( $subscription->expire() ) {
						$e++;
					}
				}
			}

			// Only go for pre expiration action if we have at least one user for it
			if ( $pre_expiration && !empty( $pre_expired_users ) ) {
				// Get all the MIs which have a pre expiration check
				$query = 'SELECT id'
				. ' FROM #__acctexp_microintegrations'
				. ' WHERE pre_exp_check > \'0\''
				;
				$database->setQuery( $query );
				$mi_pexp = $database->loadResultArray();

				// Get all the plans which have MIs
				$query = 'SELECT id'
				. ' FROM #__acctexp_plans'
				. ' WHERE micro_integrations != \'\''
				;
				$database->setQuery( $query );
				$plans_mi = $database->loadResultArray();

				// Filter out plans which have not got the right MIs applied
				$expmi_plans = array();
				foreach ( $plans_mi as $plan_id ) {
					$query = 'SELECT micro_integrations'
					. ' FROM #__acctexp_plans'
					. ' WHERE id= \'' . $plan_id . '\''
					;
					$database->setQuery( $query );
					$plan_mis = explode( ';', $database->loadResult() );
					$pexp_mis = array_intersect( $plan_mis, $mi_pexp );

					if ( count( $pexp_mis ) ) {
						$expmi_plans[] = $plan_id;
					}
				}

				// Filter out the users which dont have the correct plan
				$query = 'SELECT id'
				. ' FROM #__acctexp'
				. ' WHERE userid IN (' . implode( ',', $pre_expired_users ) . ')'
				. ' AND plan IN (' . implode( ',', $expmi_plans) . ')'
				;
				$database->setQuery( $query );
				$user_list = $database->loadResultArray();

				foreach ( $pre_expired_users as $userid ) {
					$metaUser = new metaUser( $userid );

					// Two double checks here, just to be sure
					if ( !( strcmp( $metaUser->objSubscription->status, 'Expired' ) === 0 ) && !$metaUser->objSubscription->recurring ) {
						if ( in_array( $metaUser->objSubscription->plan, $expmi_plans ) ) {
							// Its ok - load the plan
							$subscription_plan = new SubscriptionPlan( $database );
							$subscription_plan->load( $metaUser->objSubscription->plan );
							$userplan_mis = explode( ';', $subscription_plan->micro_integrations );

							// Get the right MIs
							$user_pexpmis = array_intersect( $userplan_mis, $mi_pexp );

							// loop through MIs and apply pre exp action
							$check_actions = $exp_actions;

							foreach ( $user_pexpmis as $mi_id ) {
								$mi = new microIntegration( $database );
								$mi->load( $mi_id );

								if ( $mi->callIntegration() ) {
									// Do the actual pre expiration check on this MI
									if ( $metaUser->objExpiration->is_expired( $mi->pre_exp_check ) ) {
										$result = $mi->pre_expiration_action( $userid, $subscription_plan );
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

		// Travel through users to see which ones are soon to expire

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
			$event .= $exp_actions . ' Pre-expiration action' . ( $exp_actions > 1 ) ? 's' : ''
						. ' for ' . $exp_users . ' user' . ( $exp_users > 1 ) ? 's' : '';
			$tags[] = 'pre-expiration';
		}

		if ( strcmp( _AEC_LOG_LO_HEARTBEAT . ' ', $event ) === 0 ) {
			$event .= _AEC_LOG_AD_HEARTBEAT_DO_NOTHING;
		}

		$eventlog = new eventLog( $database );
		$eventlog->issue( $short, implode( ',', $tags ), $event );

	}

}

class displayPipelineHandler
{
	function displayPipelineHandler()
	{

	}

	function getUserPipelineEvents( $userid )
	{
		global $database, $mosConfig_offset_user;

		$query = 'SELECT id'
		. ' FROM #__acctexp_displaypipeline'
		. ' WHERE userid = \'' . $userid . '\' AND only_user = \'1\''
		;
		$database->setQuery( $query );
		$events = $database->loadResultArray();

		$query = 'SELECT id'
		. ' FROM #__acctexp_displaypipeline'
		. ' WHERE only_user = \'0\''
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
					if ( ( $expstamp - ( time() + $mosConfig_offset_user*3600 ) ) < 0 ) {
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

				if ( $displayPipeline->once_per_user ) {
					$params = $displayPipeline->getParams();

					if ( isset( $params['displayedto'] ) ) {
						$users = explode( ';', $params['displayedto'] );
						if ( in_array( $userid, $users ) ) {
							continue;
						} else {
							$users[] = $userid;
							$params['displayedto'] = implode( ';', $users );
							$displayPipeline->setParams( $params );
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

class displayPipeline extends paramDBTable
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

	function create( $userid, $only_user, $once_per_user, $expire, $expiration, $displaymax, $displaytext, $params=null )
	{
		global $mosConfig_offset_user;

		$this->id				= 0;
		$this->userid			= $userid;
		$this->only_user		= $only_user;
		$this->once_per_user	= $once_per_user;
		$this->timestamp		= gmstrftime ( '%Y-%m-%d %H:%M:%S', time() + $mosConfig_offset_user*3600 );
		$this->expire			= $expire;
		$this->expstamp			= gmstrftime ( '%Y-%m-%d %H:%M:%S', strtotime( $expiration ) );
		$this->displaycount		= 0;
		$this->displaymax		= $displaymax;

		if ( !get_magic_quotes_gpc() ) {
			$this->displaytext	= addslashes( $displaytext );
		} else {
			$this->displaytext	= $displaytext;
		}

		if ( is_array( $params ) ) {
			$this->setParams( $params );
		}

		$this->check();

		if ( $this->store() ) {
			return true;
		} else {
			return false;
		}
	}
}


class eventLog extends paramDBTable
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
	/** @var text */
	var $params		= null;

	/**
	 * @param database A database connector object
	 */
	function eventLog( &$db )
	{
	 	$this->mosDBTable( '#__acctexp_eventlog', 'id', $db );
	}

	function issue( $short, $tags, $text, $params = null )
	{
		global $mosConfig_offset_user;

		$this->datetime	= gmstrftime ( '%Y-%m-%d %H:%M:%S', time() + $mosConfig_offset_user*3600 );
		$this->short	= $short;
		$this->tags		= $tags;
		$this->event	= $text;

		if ( !is_null( $params ) && is_array( $params ) ) {
			$this->setParams( $params );
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

		$query = 'SELECT id'
		. ' FROM #__acctexp_config_processors'
		. ' WHERE name = \'' . $name . '\'';
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

		$query = 'SELECT id, active, name'
		. ' FROM #__acctexp_config_processors'
		;
		if ( $active ) {
			$query .= ' WHERE active = \'1\'';
		}
		$database->setQuery( $query );

		return $database->loadObjectList();
	}

	function getInstalledNameList($active=false)
	{
		global $database;

		$query = 'SELECT name'
		. ' FROM #__acctexp_config_processors'
		;
		if ( $active !== false ) {
			$query .= ' WHERE active = \'' . $active . '\'';
		}
		$database->setQuery( $query );

		return $database->loadResultArray();
	}

	function processorReply( $url, $reply, $get = 0 )
	{
		$fp = null;
		// try to use fsockopen. some hosting systems disable fsockopen (godaddy.com)
		$fp = $this->doTheHttp( $url, $reply, $get );
		if ( !$fp ) {
			// If fsockopen doesn't work try using curl
			$fp = $this->doTheCurl( $url, $reply );
		}

		return $fp;
	}

	function doTheCurl( $url, $req )
	{
		$ch = curl_init();
		curl_setopt( $ch, CURLOPT_VERBOSE, 1 );
		//curl_setopt ($ch, CURLOPT_HTTPPROXYTUNNEL, TRUE);
		curl_setopt( $ch, CURLOPT_PROXYTYPE,		CURLPROXY_HTTP );
		curl_setopt( $ch, CURLOPT_PROXY,			'http://proxy.shr.secureserver.net:3128' );
		curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER,	FALSE );
		curl_setopt( $ch, CURLOPT_URL,				$url );
		curl_setopt( $ch, CURLOPT_POST,				1 );
		curl_setopt( $ch, CURLOPT_POSTFIELDS,		$req );
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER,	1 );
		curl_setopt( $ch, CURLOPT_TIMEOUT,			120 );
		$fp = curl_exec( $ch );
		curl_close( $ch );

		return $fp;
	}

	function doTheHttp( $url, $req, $get)
	{
		$header  = ''
		. 'POST https://' . $url . '/cgi-bin/webscr HTTP/1.0' . "\r\n"
		. 'Host: ' . $url  . ':80' . "\r\n"
		. 'Content-Type: application/x-www-form-urlencoded' . "\r\n"
		. 'Content-Length: ' . strlen($req) . "\r\n\r\n"
		;
		$fp = fsockopen( $url, 80, $errno, $errstr, 30 );

		if ( !$fp ) {
			return 'ERROR';
		} else {
			fputs( $fp, $header . $req );
			while ( !feof( $fp ) ) {
				$res = fgets( $fp, 1024 );
				if ( strcmp( $res, 'VERIFIED' ) == 0 ) {
					return 'VERIFIED';
				} elseif ( strcmp( $res, 'INVALID' ) == 0 ) {
					return 'INVALID';
				}
			}
			fclose( $fp );
		}
		return 'ERROR';
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
		. ' WHERE name = \'' . $this->processor_name . '\''
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
		$query = 'SELECT name'
		. ' FROM #__acctexp_config_processors'
		. ' WHERE id = \'' . $ppid . '\''
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
		global $database;

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
		$this->processor->setParams( $this->info, 'info' );
		$this->processor->setParams( $this->settings, 'settings' );
		$this->processor->check();
		$this->processor->store();

		$query = 'SELECT id'
		. ' FROM #__acctexp_config_processors'
		. ' WHERE name = \'' . $this->processor_name . '\''
		;
		$database->setQuery( $query );
		$result = $database->loadResult();

		$this->id = $result ? $result : 0;
	}

	function getInfo()
	{
		$this->info	= $this->processor->getParams( 'info' );
		$original	= $this->processor->info();

		foreach ( $original as $name => $var ) {
			if ( !isset( $this->info[$name] ) ) {
				$this->info[$name] = $var;
			}
		}
	}

	function getSettings()
	{
		$this->settings	= $this->processor->getParams( 'settings' );
		$original		= $this->processor->settings();

		foreach ( $original as $name => $var ) {
			if ( !isset( $this->settings[$name] ) ) {
				$this->settings[$name] = $var;
			}
		}
	}

	function setSettings()
	{
		// Test if values really are an array and write them to db
		if ( is_array( $this->settings ) ) {
			$this->processor->setParams( $this->settings, 'settings' );
			$this->processor->check();
			$this->processor->store();
		}
	}

	function setInfo()
	{
		// Test if values really are an array and write them to db
		if ( is_array( $this->info ) ) {
			$this->processor->setParams( $this->info, 'info' );
			$this->processor->check();
			$this->processor->store();
		}
	}

	function getBackendSettings()
	{
		if ( !isset( $this->settings ) ) {
			$this->getSettings();
		}

		return $this->processor->backend_settings( $this->settings );
	}

	function checkoutAction( $int_var, $metaUser, $new_subscription )
	{
		$this->getSettings();

		return $this->processor->checkoutAction( $int_var, $this->settings, $metaUser, $new_subscription );
	}

	function getParamsHTML( $params, $values )
	{
		$return['params'] = false;
		if ( !empty( $values['params'] ) ) {
			if ( is_array( $values['params'] ) ) {
				if ( isset( $values['params']['lists'] ) ) {
					$lists = $values['params']['lists'];
					unset( $values['params']['lists'] );
				} else {
					$lists = null;
				}

				foreach ( $values['params'] as $name => $entry ) {
					if ( !is_null( $name ) && !( $name == '' ) ) {
						if ( !isset( $entry[3] ) ) {
							$entry[3] = $name;
						}
						$return['params'] .= aecHTML::createFormParticle( $name, $entry, $lists ) . "\n";
					}
				}
				unset( $values['params'] );
			}
		}

		return $return;
	}

	function getParams( $params )
	{
		$this->getSettings();

		if ( method_exists( $this->processor, 'Params' ) ) {
			return $this->processor->Params( $this->settings, $params );
		} else {
			return false;
		}
	}

	function getCustomPlanParams()
	{
		$this->getSettings();

		if ( method_exists( $this->processor, 'CustomPlanParams' ) ) {
			return $this->processor->CustomPlanParams( $this->settings );
		} else {
			return false;
		}
	}

	function invoiceCreationAction( $objinvoice )
	{
		$this->getSettings();

		if ( method_exists( $this->processor, 'invoiceCreationAction' ) ) {
			$this->processor->invoiceCreationAction( $this->settings, $objinvoice );
		} else {
			return false;
		}
	}

	function parseNotification( $post )
	{
		$this->getSettings();

		return $this->processor->parseNotification( $post, $this->settings );
	}

}

class processor extends paramDBTable
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

	function loadName( $name )
	{
		global $database;

		$query = 'SELECT id'
		. ' FROM #__acctexp_config_processors'
		. ' WHERE name = \'' . $name . '\''
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

		$this->check();
		$this->store();
	}

	function checkoutAction( $int_var, $settings, $metaUser, $new_subscription )
	{
		return '<p>' . $settings['info'] . '</p>';
	}
}

class XMLprocessor extends processor
{
	function checkoutAction( $int_var, $settings, $metaUser, $new_subscription )
	{
		$var = $this->checkoutform( $int_var, $settings, $metaUser, $new_subscription );

		$return = '<form action="' . AECToolbox::deadsureURL( '/index.php?option=com_acctexp', true ) . '" method="post">' . "\n";
		$return .= $this->getParamsHTML( $var );
		$return .= '<input type="hidden" name="invoice" value="' . $int_var['invoice'] . '" />' . "\n";
		$return .= '<input type="hidden" name="userid" value="' . $metaUser->userid . '" />' . "\n";
		$return .= '<input type="hidden" name="task" value="checkout" />' . "\n";
		$return .= '<input type="submit" class="button" value="' . _BUTTON_CHECKOUT . '" />' . "\n";
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

				foreach ( $params['params'] as $name => $entry ) {
					if ( !is_null( $name ) && !( $name == '' ) ) {
						if ( !isset( $entry[3] ) ) {
							$entry[3] = $name;
						}
						$return['params'] .= aecHTML::createFormParticle( $name, $entry, $lists ) . "\n";
					}
				}
				unset( $params['params'] );
			}
		}

		return $return;
	}

	function checkoutProcess( $int_var, $settings, $metaUser, $new_subscription )
	{
		global $database;

		// Create the xml string
		$xml = $this->createRequestXML( $int_var, $settings, $metaUser, $new_subscription );

		// Transmit xml to server
		$response = $this->transmitRequestXML( $xml, $int_var, $settings, $metaUser, $new_subscription );

		if ( $response != false ) {

			$invoice = new Invoice( $database );
			$invoice->loadInvoiceNumber( $response['invoice'] );

			if ( isset( $response['raw'] ) ) {
				$responsestring = $response['raw'];
				unset( $response['raw'] );
			} else {
				$responsestring = '';
			}

			if ( isset( $response['invoiceparams'] ) ) {
				$invoice->addParams( $response['invoiceparams'] );
			}

			$invoice->processorResponse( $this, $response, $responsestring );
		} else {
			return false;
		}
	}

	function transmitRequest( $url, $path, $content, $port=443 )
	{
		$response = null;

		$response = $this->doTheCurl( $url, $content );
		if ( !$response ) {
			// If curl doesn't work try using fsockopen
			$response = $this->doTheHttp( $url, $path, $content, $port );
		}

		return $response;
	}

	function doTheHttp( $url, $path, $content, $port=443 )
	{
		$header  =	"Host: " . $url  . "\r\n"
					. "User-Agent: PHP Script\r\n"
					. "Content-Type: text/xml\r\n"
					. "Content-Length: " . strlen($content) . "\r\n\r\n"
					. "Connection: close\r\n\r\n";
					;
		$connection = fsockopen( $url, $port, $errno, $errstr, 30 );

		if ( !$connection ) {
			return false;
		} else {
			fwrite( $connection, "POST " . $path . " HTTP/1.1\r\n" );
			fwrite( $connection, $header . $content );

			while ( !feof( $connection ) ) {
				$res = fgets( $connection, 1024 );
				if ( strcmp( $res, 'VERIFIED' ) == 0 ) {
					return true;
				} elseif ( strcmp( $res, 'INVALID' ) == 0 ) {
					return false;
				}
			}
			fclose( $connection );
		}
		return false;
	}

	function doTheCurl( $url, $content )
	{
		$ch = curl_init();
		curl_setopt( $ch, CURLOPT_URL, $url );
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
		curl_setopt( $ch, CURLOPT_HTTPHEADER, Array("Content-Type: text/xml") );
		curl_setopt( $ch, CURLOPT_HEADER, 1 );
		curl_setopt( $ch, CURLOPT_POST, 1 );
		curl_setopt( $ch, CURLOPT_POSTFIELDS, $content );
		curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, FALSE );
		$response = curl_exec( $ch );
		curl_close( $ch );

		return $response;
	}

}

class POSTprocessor extends processor
{
	function checkoutAction( $int_var, $settings, $metaUser, $new_subscription )
	{
		$var = $this->createGatewayLink( $int_var, $settings, $metaUser, $new_subscription );

		$return = '<form action="' . $var['post_url'] . '" method="post">' . "\n";
		unset( $var['post_url'] );

		foreach ( $var as $key => $value ) {
			$return .= '<input type="hidden" name="' . $key . '" value="' . $value . '" />' . "\n";
		}

		$return .= '<input type="submit" class="button" value="' . _BUTTON_CHECKOUT . '" />' . "\n";
		$return .= '</form>' . "\n";

		return $return;
	}
}

class GETprocessor extends processor
{
	function checkoutAction( $int_var, $settings, $metaUser, $new_subscription )
	{
		$var = $this->createGatewayLink( $int_var, $settings, $metaUser, $new_subscription );

		$return = '<form action="' . $var['post_url'] . '" method="get">' . "\n";
		unset( $var['post_url'] );

		foreach ( $var as $key => $value ) {
			$return .= '<input type="hidden" name="' . $key . '" value="' . $value . '" />' . "\n";
		}

		$return .= '<input type="submit" class="button" value="' . _BUTTON_CHECKOUT . '" />' . "\n";
		$return .= '</form>' . "\n";

		return $return;
	}
}

class URLprocessor extends processor
{
	function checkoutAction( $int_var, $settings, $metaUser, $new_subscription )
	{
		$var = $this->createGatewayLink( $int_var, $settings, $metaUser, $new_subscription );

		$return = '<a href="' . $var['post_url'];
		unset( $var['post_url'] );

		$vars = array();
		foreach ( $var as $key => $value ) {
			$vars[] .= $key . '=' . $value;
		}

		$return .= implode( '&amp;', $vars );
		$return .= '" >' . _BUTTON_CHECKOUT . '</a>' . "\n";

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

			if ( !isset( $content[2] ) || !$content[2] ) {
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

	function remap_list_date( $name, $value )
	{
		// mic: fix wrong name
		$this->lists[$name] = '<input class="text_area" type="text" name="' . $name . '" id="' . $name . '" size="19" maxlength="19" value="' . $value . '"/>'
		.'<input type="reset" name="reset" class="button" onClick="return showCalendar(\'' . $name . '\', \'y-mm-dd\');" value="..." />';
		return 'list';
	}
}

class aecHTML
{

	function aecHTML( $rows, $lists=null )
	{
		//$this->area = $area;
		//$this->fallback = $fallback;

		$this->rows		= $rows;
		$this->lists	= $lists;
	}

	function createSettingsParticle( $name )
	{

		$row	= $this->rows[$name];
		$type	= $row[0];

		if ( isset( $row[3] ) ) {
			$value = $row[3];
		} else {
			$value = '';
		}

		$return = '<div class="setting_desc">' . $this->ToolTip( $row[2], $row[1]) . $row[1] . '</div>';

		switch ( $type ) {
			case 'inputA':
				$return .= '<div class="setting_form">';
				$return .= '<input name="' . $name . '" type="text" size="4" value="' . $value . '" />';
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
			case 'editor':
				$return = '<p>' . $this->ToolTip( $row[2], $row[1]) . $row[1] . '</p>';
				$return .= '<div class="setting_form">';
				$return .= editorArea( $name, $value, $name, '100%;', '250', '10', '60' );
				$return .= '</div>';
				break;
			case 'list':
				$return .= '<div class="setting_form">';
				$return .= $this->lists[$name];
				$return .= '</div>';
				break;
			case 'fieldset':
				$return .= '<fieldset><legend>' . $row[1] . '</legend>' . "\n"
				. '<table cellpadding="1" cellspacing="1" border="0">' . "\n"
				. '<tr align="left" valign="middle" ><td>' . $row[2] . '</td></tr>' . "\n"
				. '</table>' . "\n"
				. '</fieldset>' . "\n"
				;
				break;
		}
		return $return;
	}

	function createFormParticle( $name, $row, $lists )
	{
		$type = $row[0];

		if ( isset( $row[3] ) ) {
			$value = $row[3];
		}

		$return = '<p><strong>' . $row[1] . '</strong></p>' . '<p>' . $row[2] . '</p>';

		switch ( $type ) {
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
			case "list":
				$return .= $lists[$value];
				break;
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

		$mousover = 'return overlib(\''. $tooltip .'\''. $title .', BELOW, RIGHT'. $width .');';

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

class Config_General extends paramDBTable
{
	/** @var int Primary key */
	var $id 				= null;
	/** @var text */
	var $settings 			= null;

	function Config_General( &$db )
	{
		$this->mosDBTable( '#__acctexp_config', 'id', $db );

		$this->load(1);

		if ( empty( $this->settings ) ) {
			$this->load(0);

			$this->initParams();
			$this->cfg = $this->getParams( 'settings' );
		} else {
			$this->cfg = $this->getParams( 'settings' );
		}
	}

	function initParams()
	{
		$def = array();
		$def['require_subscription']				= 0;
		$def['alertlevel2']							= 7;
		$def['alertlevel1']							= 3;
		$def['expiration_cushion']					= 12;
		$def['heartbeat_cycle']						= 24;
		$def['heartbeat_cycle_backend']				= 1;
		$def['plans_first']							= 0;
		$def['simpleurls']							= 0;
		$def['display_date_frontend']				= "%a, %d %b %Y %T %Z";
		$def['display_date_backend']				= "%a, %d %b %Y %T %Z";
		$def['enable_mimeta']						= 0;
		$def['enable_coupons']						= 0;
		$def['gwlist']								= '';
		$def['milist']								= "mi_email;mi_htaccess;mi_mysql_query;mi_email;mi_virtuemart";
		$def['displayccinfo']						= 1;
		$def['customtext_confirm_keeporiginal']		= 1;
		$def['customtext_checkout_keeporiginal']	= 1;
		$def['customtext_notallowed_keeporiginal']	= 1;
		$def['customtext_pending_keeporiginal']		= 1;
		$def['customtext_expired_keeporiginal']		= 1;
		// new 0.12.4
		$def['bypassintegration']					= '';
		$def['customintro']							= '';
		$def['customthanks']						= '';
		$def['customcancel']						= '';
		$def['customnotallowed']					= '';
		$def['tos']									= '';
		$def['customtext_plans']					= '';
		$def['customtext_confirm']					= '';
		$def['customtext_checkout']					= '';
		$def['customtext_notallowed']				= '';
		$def['customtext_pending']					= '';
		$def['customtext_expired']					= '';
		// new 0.12.4.2
		$def['adminaccess']							= 1;
		$def['noemails']							= 0;
		$def['nojoomlaregemails']					= 0;
		// new 0.12.4.10
		$def['debugmode']							= 0;

		// Write to Params, do not overwrite existing data
		$this->addParams( $def, 'settings', false );

		unset( $this->cfg );

		$this->check();
		$this->store();

		return true;
	}

	function saveSettings()
	{
		if ( $this->RowDuplicationCheck() ) {
			$this->CleanDuplicatedRows();
			$this->load(1);
		}

		$this->setParams( $this->cfg, 'settings' );

		unset( $this->cfg );

		$this->check();
		$this->store();
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
		. ' WHERE id != \'' . $max . '\''
		;
		$database->setQuery( $query );
		$database->query();

		if ( !( $max == 1 ) ) {
			$query = 'UPDATE #__acctexp_config'
			. ' SET id = \'1\''
			. ' WHERE id =\'' . $max . '\''
			;
			$database->setQuery( $query );
			$database->query();
		}
	}
}

class SubscriptionPlanHandler
{
	function getPlanUserlist( $planid )
	{
		global $database;

		$query = 'SELECT userid'
		. ' FROM #__acctexp_subscr'
		. ' WHERE plan = \'' . $planid . '\' AND ( status = \'Active\' OR status = \'Trial\' ) '
		;
		$database->setQuery( $query );

		return $database->loadResultArray();
	}
}

class SubscriptionPlan extends paramDBTable
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

	function getProperty( $name )
	{
		if ( isset( $this->$name ) ) {
			return stripslashes( $this->$name );
		} else {
			return null;
		}
	}

	function applyPlan( $userid, $processor = 'none', $silent = 0 )
	{
		global $database, $mainframe, $mosConfig_offset_user, $aecConfig;

		$metaUser = new metaUser($userid);

		$comparison		= $this->doPlanComparison( $metaUser->objSubscription );
		$renew			= $comparison['renew'];

		$is_pending		= ( strcmp($metaUser->objSubscription->status, 'Pending' ) === 0 );
		$is_trial		= ( strcmp($metaUser->objSubscription->status, 'Trial' ) === 0 );
		$lifetime		= $metaUser->objSubscription->lifetime;

		$params			= $this->getParams();

		if ( !$comparison['comparison'] ) {
			$metaUser->objSubscription->expire(1);
		}

		if ( $comparison['total_comparison'] === false || $is_pending ) {
			// If user is using global trial period he still can use the trial period of a plan
			if ( ( $params['trial_period'] > 0 ) && !$is_trial ) {
				$value		= $params['trial_period'];
				$perunit	= $params['trial_periodunit'];
				$params['lifetime']	= 0; // We are entering the trial period. The lifetime will come at the renew.
			} else {
				$value		= $params['full_period'];
				$perunit	= $params['full_periodunit'];
			}
		} elseif ( !$is_pending ) {
			$value		= $params['full_period'];
			$perunit	= $params['full_periodunit'];
		} else {
			return;
		}

		if ( !$metaUser->hasExpiration ) {
			$metaUser->objExpiration = new AcctExp( $database );
			$metaUser->objExpiration->load( 0 );
			$metaUser->objExpiration->userid = $metaUser->userid;
			$metaUser->hasExpiration = 1;
		}

		if ( $params['lifetime'] ) {
			$metaUser->objExpiration->expiration = '9999-12-31 00:00:00';
			$metaUser->objSubscription->lifetime = 1;
		} else {
			$metaUser->objSubscription->lifetime = 0;
			if ( ( $comparison['comparison'] == 2 ) && !$lifetime ) {
				$metaUser->objExpiration->setExpiration( $perunit, $value, 1 );
			} else {
				$metaUser->objExpiration->setExpiration( $perunit, $value, 0 );
			}
		}

		$metaUser->objExpiration->check();
		$metaUser->objExpiration->store();

		if ( $is_pending ) {
			// Is new = set signup date
			$metaUser->objSubscription->signup_date = gmstrftime( '%Y-%m-%d %H:%M:%S', time() + $mosConfig_offset_user*3600 );
			if ( $params['trial_period'] > 0 && !$is_trial ) {
				$status = 'Trial';
			} else {
				if ( $params['full_period'] || $params['lifetime'] ) {
					if ( !isset( $params['make_active'] ) ) {
						$status = 'Active';
					} else {
						$status = ( $params['make_active'] ? 'Active' : 'Pending');
					}
				} else {
					// This should not happen
					$status = 'Pending';
				}
			}
		} else {
			// Renew subscription - Do NOT set signup_date
			if ( !isset( $params['make_active'] ) ) {
				$status = 'Active';
			} else {
				$status = ( $params['make_active'] ? 'Active' : 'Pending');
			}
			$renew = 1;
		}

		$metaUser->objSubscription->status = $status;
		$metaUser->objSubscription->setPlanID( $this->id );

		$metaUser->objSubscription->lastpay_date	= gmstrftime( '%Y-%m-%d %H:%M:%S', time() + $mosConfig_offset_user*3600 );
		$metaUser->objSubscription->type = $processor;

		$pp = new PaymentProcessor();
		if ( $pp->loadName( strtolower( $processor ) ) ) {
			$pp->init();
			$pp->getInfo();
			$metaUser->objSubscription->recurring = $pp->info['recurring'];
		} else {
			$metaUser->objSubscription->recurring = 0;
		}

		$metaUser->objSubscription->check();
		$metaUser->objSubscription->store();

		if ( $params['gid_enabled'] ) {
			$metaUser->instantGIDchange($params['gid']);
		}

		$micro_integrations = $this->getMicroIntegrations();

		if ( is_array( $micro_integrations ) ) {
			foreach ( $micro_integrations as $mi_id ) {
				$mi = new microIntegration( $database );
				if ( $mi->mi_exists( $mi_id ) ) {
					$mi->load( $mi_id );
					if ( $mi->callIntegration() ) {
						$mi->action( $metaUser->userid, $this );
					}
				}
				unset($mi);
			}
		}

		if ( !( $silent && $aecConfig->cfg['noemails'] ) ) {
			if ( ( $this->id !== $aecConfig->cfg['entry_plan'] ) ) {
				$metaUser->objSubscription->sendEmailRegistered( $renew );
			}
		}

		return $renew;
	}

	function SubscriptionAmount( $recurring, $user_subscription )
	{
		global $database;

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
		$params		= $this->getParams();

		if ( $recurring ) {
			$amount = array();

			// Only Allow a Trial when the User is coming from a different PlanGroup or is new
			if ( ( $plans_comparison === false ) && ( $plans_comparison_total === false ) && !empty( $params['trial_period'] ) ) {
				if ( $params['trial_free'] ) {
					$amount['amount1'] = '0.00';
					$free_trial = 1;
				} else {
					$amount['amount1']	= $params['trial_amount'];
				}
				$amount['period1']	= $params['trial_period'];
				$amount['unit1']	= $params['trial_periodunit'];
			}

			if ( $params['full_free'] ) {
				$amount['amount3'] = '0.00';
			} else {
				$amount['amount3']	= $params['full_amount'];
			}

			$amount['amount3']		= $params['full_amount'];
			$amount['period3']		= $params['full_period'];
			$amount['unit3']		= $params['full_periodunit'];
		} else {
			if ( !$params['trial_period'] && $params['full_free'] && $params['trial_free'] ) {
				$amount = '0.00';
			} else {
				if ( ( $plans_comparison === false ) && ( $plans_comparison_total === false ) ) {
					if ( !$is_trial && !empty($params['trial_period']) ) {
						if ( $params['trial_free'] ) {
							$amount = '0.00';
							$free_trial = 1;
						} else {
							$amount = $params['trial_amount'];
						}
					} else {
						if ( $params['full_free'] ) {
							$amount = '0.00';
						} else {
							$amount = $params['full_amount'];
						}
					}
				} else {
					if ( $params['full_free'] ) {
						$amount = '0.00';
					} else {
						$amount = $params['full_amount'];
					}
				}
			}
		}

		$return_url	= AECToolbox::deadsureURL( '/index.php?option=com_acctexp&amp;task=thanks&amp;renew=' . $renew );

		$return['return_url']	= $return_url;
		$return['amount']		= $amount;
		$return['free_trial']	= $free_trial;

		return $return;
	}

	function doPlanComparison( $user_subscription )
	{
		global $database;

		$return['total_comparison']	= false;
		$return['comparison']		= false;
		$return['renew']			= 0;

		if ( !is_null( $user_subscription->plan ) ) {
			$return['renew'] = 1;

			if ( $user_subscription->used_plans ) {
				$used_plans			= explode( ';', $user_subscription->used_plans );
				$plans_comparison	= false;
				$thisparams			= $this->getParams();

				if ( is_array( $used_plans ) ) {
					foreach ( $used_plans as $used_plan_id ) {
						if ( $used_plan_id ) {
							$planid = explode( ',', $used_plan_id);

							if ( isset( $planid[0] ) ){
								if ( empty( $planid[0] ) ) {
									continue;
								} else {
									$planid = $planid[0];
								}
							} else {
								continue;
							}

							$used_subscription = new SubscriptionPlan( $database );
							$used_subscription->load( $planid );

							if ( $this->id === $used_subscription->id ) {
								$used_comparison = 2;
							} elseif ( empty( $thisparams['similarplans'] ) && empty( $thisparams['equalplans'] ) ) {
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
		$thisparams = $this->getParams();
		$planparams = $plan->getParams();

		$spg1		= explode( ';', $thisparams['similarplans'] );
		$spg2		= explode( ';', $planparams['similarplans'] );

		$epg1		= explode( ';', $thisparams['equalplans'] );
		$epg2		= explode( ';', $planparams['equalplans'] );

		if ( in_array( $this->id, $epg2 ) || in_array( $plan->id, $epg1 ) ) {
			return 2;
		} elseif ( in_array( $this->id, $spg2 ) || in_array( $plan->id, $spg1 ) ) {
			return 1;
		} else {
			return false;
		}
	}

	function getMicroIntegrations()
	{
		if ( strlen( $this->micro_integrations ) ) {
			return explode( ';', $this->micro_integrations );
		} else {
			return false;
		}
	}

	function getProcessorParameters( $processorid )
	{
		$params = $this->getParams( 'custom_params' );

		$procparams = array();
		if ( !empty( $params ) ) {
			foreach ( $params as $name => $value ) {
				$realname = explode( '_', $name, 2 );

				if ( $realname[0] == $processorid ) {
					$procparams[$realname[1]] = $value;
				}
			}
		}

		return $procparams;
	}

	function getRestrictionsArray()
	{
		$restrictions = $this->getParams( 'restrictions' );

		$planrestrictions = array();

		// Check for a fixed GID - this certainly overrides the others
		if ( !empty( $restrictions['fixgid_enabled'] ) ) {
			$planrestrictions['fixgid'] = (int) $restrictions['fixgid'];
		} else {
			// No fixed GID, check for min GID
			if ( !empty( $restrictions['mingid_enabled'] ) ) {
				$planrestrictions['mingid'] = (int) $restrictions['mingid'];
			}
			// Check for max GID
			if ( !empty( $restrictions['maxgid_enabled'] ) ) {
				$planrestrictions['maxgid'] = (int) $restrictions['maxgid'];
			}
		}

		// Check for a directly previously used plan
		if ( !empty( $restrictions['previousplan_req_enabled'] ) ) {
			if ( isset( $restrictions['previousplan_req'] ) ) {
				$planrestrictions['plan_previous'] = (int) $restrictions['previousplan_req'];
			}
		}

		// Check for a currently used plan
		if ( !empty( $restrictions['currentplan_req_enabled'] ) ) {
			if ( isset( $restrictions['currentplan_req'] ) ) {
				$planrestrictions['plan_present'] = (int) $restrictions['currentplan_req'];
			}
		}

		// Check for a overall used plan
		if ( !empty( $restrictions['overallplan_req_enabled'] ) ) {
			if ( isset( $restrictions['overallplan_req'] ) ) {
				$planrestrictions['plan_overall'] = (int) $restrictions['overallplan_req'];
			}
		}

		// Check for a overall used plan with amount minimum
		if ( !empty( $restrictions['used_plan_min_enabled'] ) ) {
			if ( isset( $restrictions['used_plan_min_amount'] ) && isset( $restrictions['used_plan_min'] ) ) {
				$planrestrictions['plan_amount_min'] = ( (int) $restrictions['used_plan_min'] )
				. ',' . ( (int) $restrictions['used_plan_min_amount'] );
			}
		}

		// Check for a overall used plan with amount maximum
		if ( !empty( $restrictions['used_plan_max_enabled'] ) ) {
			if ( isset( $restrictions['used_plan_max_amount'] ) && isset( $restrictions['used_plan_max'] ) ) {
				$planrestrictions['plan_amount_max'] = ( (int) $restrictions['used_plan_max'] )
				. ',' . ( (int) $restrictions['used_plan_max_amount'] );
			}
		}

		// Check for a directly previously used plan
		if ( !empty( $restrictions['custom_restrictions_enabled'] ) ) {
			if ( isset( $restrictions['custom_restrictions'] ) ) {
				$planrestrictions['custom_restrictions'] = $this->transformCustomRestrictions( $restrictions['custom_restrictions'] );
			}
		}

		return $planrestrictions;
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

	function savePOSTsettings( $post )
	{
		global $database;

		// Fake knowing the planid if is zero. TODO: This needs to replaced with something better later on!
		if ( !empty( $post['id'] ) ) {
			$planid = $post['id'];
		} else {
			$query = 'SELECT MAX(id)'
			. ' FROM #__acctexp_plans'
			;
			$database->setQuery( $query );
			$planid = $database->loadResult() + 1;
		}

		if ( isset( $post['id'] ) ) {
			unset( $post['id'] );
		}

		// Filter out fixed variables
		$fixed = array( 'active', 'visible', 'name', 'desc', 'email_desc', 'micro_integrations' );

		foreach ( $fixed as $varname ) {
			if ( is_array( $post[$varname] ) ) {
				$this->$varname = implode( ';', $post[$varname] );
			} else {
				if ( !get_magic_quotes_gpc() ) {
					$this->$varname = addslashes( $post[$varname] );
				} else {
					$this->$varname = $post[$varname];
				}
			}
			unset( $post[$varname] );
		}

		// Filter out params
		$fixed = array( 'full_free', 'full_amount', 'full_period', 'full_periodunit', 'trial_free', 'trial_amount', 'trial_period', 'trial_periodunit', 'gid_enabled', 'gid', 'lifetime', 'processors', 'fallback', 'similarplans', 'equalplans', 'make_active' );

		$params = array();
		foreach ( $fixed as $varname ) {
			if ( is_array( $post[$varname] ) ) {
				$params[$varname] = implode( ';', $post[$varname] );
			} elseif ( empty( $post[$varname] ) ) {
				$params[$varname] = 0;
			} else {
				$params[$varname] = $post[$varname];
			}
			unset( $post[$varname] );
		}

		$this->saveParams( $params );

		// The rest of the vars are restrictions
		$restrictions = array();
		foreach ( $post as $varname => $content ) {
			// mic: fix for NOT including JCE-settings into aec.database
			if ( substr( $varname, 0, 4 ) != 'mce_' ) {
				if ( is_array( $content ) ) {
					$restrictions[$varname] = implode( ';', $content );
				} else {
					$restrictions[$varname] = $content;
				}
			}
			unset( $post[$varname] );
		}

		$this->saveRestrictions($restrictions);
	}

	function saveParams( $params )
	{
		global $database;

		// If the admin wants this to be a free plan, we have to make this more explicit
		// Setting processors to zero and full_free
		if ( $params['full_free'] && ( $params['processors'] == '' ) ) {
			$params['processors']	= '0';
		} elseif ( !$params['full_amount'] || ( $params['full_amount'] == '0.00' ) || ( $params['full_amount'] == '' ) ) {
			$params['full_free']	= 1;
			$params['processors']	= '0';
		}

		// Correct a malformed Full Amount
		if ( !strlen( $params['full_amount'] ) ) {
			$params['full_amount']	= '0.00';
			$params['full_free']	= 1;
			$params['processors']	= '0';
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

		$this->setParams( $params );
	}

	function saveRestrictions( $restrictions )
	{
		$this->setParams( $restrictions, 'restrictions' );
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
		global $database, $mosConfig_offset_user;

		$user = new mosUser($database);
		$user->load( $objInvoice->userid );

		$plan = new SubscriptionPlan( $database );
		$plan->load( $objInvoice->usage );

		$this->proc_id			= $pp->id;
		$this->proc_name		= $pp->processor_name;
		$this->user_id			= $user->id;
		$this->user_name		= $user->username;
		$this->plan_id			= $plan->id;
		$this->plan_name		= $plan->name;
	    $this->transaction_date	= gmstrftime ( '%Y-%m-%d %H:%M:%S', time() + $mosConfig_offset_user*3600 );
	    $this->amount			= $objInvoice->amount;
	    $this->invoice_number	= $objInvoice->invoice_number;
	    $this->response			= $response;

		$short	= 'history entry';
		$event	= 'Processor (' . $pp->processor_name . ') notification for ' . $objInvoice->invoice_number;
		$tags	= 'history,processor,payment';
		$params = array( 'invoice_number' => $objInvoice->invoice_number );

		$eventlog = new eventLog( $database );
		$eventlog->issue( $short, $tags, $event, $params );

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

	function InvoiceFactory( $userid=null, $usage=null, $processor=null, $invoice=null )
	{
		global $database, $mainframe, $my;

		$this->userid = $userid;

		require_once( $mainframe->getPath( 'front_html', 'com_acctexp' ) );

		// Check whether this call is legitimate
		if ( !$my->id ) {
			if ( !$this->userid ) {
				// Its ok, this is a registration/subscription hybrid call
			} elseif ( $this->userid ) {
				if ( AECToolbox::quickVerifyUserID( $this->userid ) === true ) {
					// This user is not expired, so he could log in...
					mosNotAuth();
					return;
				} else {
					$this->userid = $userid;
				}
			}
		} else {
			// Overwrite the given userid when user is logged in
			$this->userid = $my->id;
		}

		// Init variables
		$this->usage		= $usage;
		$this->processor	= $processor;
		$this->invoice		= $invoice;

		if ( !is_null( $this->userid ) ) {
			$query = 'SELECT id'
			. ' FROM #__users'
			. ' WHERE id = \'' . $this->userid . '\'';
			$database->setQuery( $query );

			if ( !$database->loadResult() ) {
				$this->userid = null;
			}
		} else {
			$this->userid = null;
		}

		if ( $this->usage ) {
			$this->verifyUsage();
		}
	}

	function verifyUsage()
	{
		global $database;

		$metaUser = new metaUser( $this->userid );

		$row = new SubscriptionPlan( $database );
		$row->load( $this->usage );

		$restrictions = $row->getRestrictionsArray();

		if ( count( $restrictions ) ) {
			$status = $metaUser->permissionResponse( $restrictions );

			foreach ( $status as $stname => $ststatus ) {
				if ( !( $ststatus === true ) ) {
					mosNotAuth();
				}
			}
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
			switch ( $this->processor ) {
				case 'free':
					$this->payment->method_name = _AEC_PAYM_METHOD_FREE;
					$this->pp					= false;
					$this->recurring			= 0;
					$currency					= '';
					break;

				case 'none':
					$this->payment->method_name = _AEC_PAYM_METHOD_NONE;
					$this->pp					= false;
					$this->recurring			= 0;
					$currency					= '';
					break;

				default:
					$this->pp = new PaymentProcessor();
					if ( $this->pp->loadName( $this->processor ) ) {
						$this->pp->fullInit();
						$this->payment->method_name	= $this->pp->info['longname'];
						$this->recurring			= isset( $this->pp->info['recurring'] ) ? $this->pp->info['recurring'] : 0;
						$currency					= isset( $this->pp->settings['currency'] ) ? $this->pp->settings['currency'] : '';
					} else {
						$this->payment->method_name = _AEC_PAYM_METHOD_NONE;
						$this->pp					= false;
						$this->recurring			= 0;
						$currency					= '';
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
			if ( AECfetchfromDB::SubscriptionIDfromUserID( $this->userid ) ) {
				$user_subscription = new Subscription( $database );
				$user_subscription->loadUserID( $this->userid );

				if ( !( strcmp( $user_subscription->lastpay_date, '0000-00-00 00:00:00' ) === 0 ) ) {
					$this->renew = 1;
				}
			} else {
				if ( isset( $this->confirmed ) ) {
					if ( $this->confirmed ) {
						$user_subscription = new Subscription( $database );
						$user_subscription->load(0);
						$user_subscription->createNew( $this->userid, $this->processor, 1 );
					}
				}
			}
		} else {
			if ( isset( $this->confirmed ) ) {
				if ( $this->confirmed ) {
					$user_subscription = new Subscription( $database );
					$user_subscription->load(0);
					$user_subscription->createNew( $this->userid, $this->processor, 1 );
				}
			}
		}

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

		$this->payment->currency = $currency;

		return;
	}

	function touchInvoice( $option, $invoice_number=false )
	{
		global $database;

		// Checking whether we are trying to repeat an invoice
		if ( $invoice_number !== false ) {
			// Make sure the invoice really exists and that its the correct user carrying out this action
			$invoiceid = AECfetchfromDB::InvoiceIDfromNumber($invoice_number, $this->userid);

			if ( $invoiceid ) {
				$this->invoice = $invoice_number;
			}
		}

		$this->objInvoice = new Invoice( $database );

		if ( $this->invoice ) {
			$this->objInvoice->loadInvoiceNumber($this->invoice);

			$this->processor = $this->objInvoice->method;
			$this->usage = $this->objInvoice->usage;

			if ( empty( $this->usage ) ) {
				$this->create( $option, 0, 0, $this->invoice_number );
			} elseif ( empty( $this->processor ) ) {
				$this->create( $option, 0, $this->usage, $this->invoice_number );
			}
		} else {
			$this->objInvoice->create( $this->userid, $this->usage, $this->processor );

			// Reset parameters
			$this->processor	= $this->objInvoice->method;
			$this->usage		= $this->objInvoice->usage;
			$this->invoice		= $this->objInvoice->invoice_number;
		}

		$this->objInvoice->computeAmount();

		return;
	}

	function create ( $option, $intro=0, $usage=0, $processor=null, $invoice=0, $passthrough=false )
	{
		global $database, $mainframe, $my, $aecConfig;

		if ( !$this->userid ) {
			// Creating a dummy user object
			$metaUser = new metaUser( 0 );
			$metaUser->cmsUser = new stdClass();
			$metaUser->cmsUser->gid = 29;
			$metaUser->hasSubscription = false;
			$metaUser->hasExpiration = false;
			$register = 1;

			if ( $passthrough ) {
				$cpass = $passthrough;
				unset( $cpass['id'] );

				$cmsfields = array( 'name', 'username', 'email', 'password' );

				foreach( $cmsfields as $cmsfield ) {
					$metaUser->cmsUser->$cmsfield = $cpass[$cmsfield];
					unset( $cpass[$cmsfield] );
				}

				if ( GeneralInfoRequester::detect_component( 'CB' ) || GeneralInfoRequester::detect_component( 'CBE' ) ) {
					$metaUser->hasCBprofile = 1;
					$metaUser->cbUser = new stdClass();

					foreach ( $cpass as $cbfield => $cbvalue ) {
						if ( is_array( $cbvalue ) ) {
							$metaUser->cbUser->$cbfield = implode( ';', $cbvalue );
						} else {
							$metaUser->cbUser->$cbfield = $cbvalue;
						}
					}
				}
			}
		} else {
			// Loading the actual user
			$metaUser = new metaUser( $this->userid );
			$register = 0;
		}

		$where = array();

		if ( $metaUser->hasSubscription ) {
			$subscriptionClosed = ( strcmp( $metaUser->objSubscription->status, 'Closed' ) === 0 );
		} else {
			$subscriptionClosed = false;
			// TODO: Check if the user has already subscribed once, if not - link to intro
			// TODO: Make sure a registration hybrid wont get lost here
			if ( !$intro && ( $aecConfig->cfg['customintro'] != '' ) && !is_null( $aecConfig->cfg['customintro'] ) ) {
				mosRedirect( $aecConfig->cfg['customintro'] );
			}
		}

		$where[] = 'active = \'1\'';

		if ( $usage ) {
			$where[] = 'id=' . $usage;
		} else {
			$where[] = 'visible != \'0\'';
		}

		$query = 'SELECT id'
		. ' FROM #__acctexp_plans'
		. ( count( $where ) ? ' WHERE ' . implode( ' AND ', $where ) : '' )
		. ' ORDER BY ordering'
		;
	 	$database->setQuery( $query );
		$rows = $database->loadResultArray();
	 	if ( $database->getErrorNum() ) {
	 		echo $database->stderr();
	 		return false;
	 	}

	 	if ( $mainframe->getCfg( 'debug' ) ) {
	 		echo 'DEBUG - '. __FILE__ . ' - ' . __LINE__ . '<br />';
			echo 'query: ' . $query . '<br />';
		 	echo 'rows:<hr />';
		 	print_r( $rows );
		}

	 	// There are no plans to begin with, so we need to punch out an error here
		if ( count( $rows ) == 0 ) {
			mosRedirect( AECToolbox::deadsureURL( '/index.php?mosmsg=' . _NOPLANS_ERROR ) );
	 		return;
	 	}

		$plans	= array();
		$i		= 0;

		foreach ( $rows as $planid ) {
			$row = new SubscriptionPlan($database);
			$row->load($planid);

			$restrictions = $row->getRestrictionsArray();

			if ( count( $restrictions ) ) {
				$status = array();

				if ( isset( $restrictions['custom_restrictions'] ) ) {
					$status = array_merge( $status, $metaUser->CustomRestrictionResponse( $restrictions['custom_restrictions'] ) );
					unset( $restrictions['custom_restrictions'] );
				}

				$status = array_merge( $status, $metaUser->permissionResponse( $restrictions ) );

				foreach ( $status as $stname => $ststatus ) {
					if ( !$ststatus ) {
						continue 2;
					}
				}
			}

			$plan_params = $row->getParams();

			$plans[$i]['name']		= $row->name;
			$plans[$i]['desc']		= $row->getProperty( 'desc' );
			$plans[$i]['id']		= $row->id;
			$plans[$i]['ordering']	= $row->ordering;
			$plans[$i]['lifetime']	= $plan_params['lifetime'];
			$plans[$i]['gw']		= array();

			if ( $plan_params['full_free'] ) {
				$plans[$i]['gw'][0]					= array();
				$plans[$i]['gw'][0]['name']			= 'free';
				$plans[$i]['gw'][0]['recurring']	= 0;
				$plans[$i]['gw'][0]['statement']	= '';
				$i++;
			} else {
				if ( ( $plan_params['processors'] != '' ) && !is_null( $plan_params['processors'] ) ) {
					$processors = explode( ';', $plan_params['processors'] );

					if ( !is_null( $this->processor ) ) {
						$processorid = PaymentProcessorHandler::getProcessorIdfromName( $this->processor );
						if ( in_array( $processorid, $processors ) ) {
							$processors = array( $processorid );
						}
					}

					$plan_gw = array();
					if ( count( $processors ) ) {
						$k = 0;
						foreach ( $processors as $n ) {
							if ($n) {
								$pp = new PaymentProcessor();
								$loadproc = $pp->loadId( $n );
								if ( $loadproc ) {
									$pp->init();
									$pp->getInfo();

									if ( !($plan_params['lifetime'] && $pp->info['recurring'] ) ) {
										// TODO: This following line produces a notice under some circumstances - find out why!
										$plan_gw[$k]['name']		= $pp->processor_name;
										$plan_gw[$k]['statement']	= $pp->info->statement;
									}
									$k++;
								}
							}
						}
					}

					if ( !empty( $plan_gw ) ) {
						$plans[$i]['gw'] = $plan_gw;
					} else {
						unset( $plans[$i] );
					}
					unset( $plan_gw );
					$i++;
				} else {
					unset( $plans[$i] );
				}
			}
			unset( $row );
		}

	 	// After filtering out the processors, no plan can be used, so we have to again issue an error
		 if ( count( $plans ) == 0 ) {
			mosRedirect( AECToolbox::deadsureURL( '/index.php?mosmsg=' . _NOPLANS_ERROR ) );
	 		return;
	 	}

	 	$nochoice = ( count( $plans ) === 1 ) && ( count( $plans[0]['gw'] ) === 1 );

		// If we have only one processor on one plan, there is no need for a decision
		if ( $nochoice ) {
			// If the user also needs to register, we need to guide him there after the selection has now been made
			if ( $register ) {
				// The plans are supposed to be first, so the details form should hold the values
				if ( $aecConfig->cfg['plans_first'] ) {
					$_POST['usage']		= $plans[0]['id'];
					$_POST['processor'] = $plans[0]['gw'][0]['name'];
				}

				// Send to CB or joomla!
				if ( GeneralInfoRequester::detect_component( 'CB' ) || GeneralInfoRequester::detect_component( 'CBE' ) ) {
					// This is a CB registration, borrowing their code to register the user

					include_once( $mainframe->getCfg( 'absolute_path' ) . '/components/com_comprofiler/comprofiler.html.php' );
					include_once( $mainframe->getCfg( 'absolute_path' ) . '/components/com_comprofiler/comprofiler.php' );

					registerForm($option, $mainframe->getCfg( 'emailpass' ), null);

				} else {
					//include_once( $mainframe->getCfg( 'absolute_path' ) . '/components/com_acctexp/acctexp.html.php' );
					joomlaregisterForm( $option, $mainframe->getCfg( 'useractivation' ) );
				}
			} else {
				// The user is already existing, so we need to move on to the confirmation page with the details

				$this->usage		= $plans[0]['id'];
				if ( isset( $plans[0]['gw'][0]['recurring'] ) ) {
					$this->recurring	= $plans[0]['gw'][0]['recurring'];
				} else {
					$this->recurring	= 0;
				}
				$this->processor	= $plans[0]['gw'][0]['name'];

				if ( ( $invoice != 0 ) && !is_null( $invoice ) ) {
					$this->invoice	= $invoice;
				}

				$this->confirm ( $option, array(), $passthrough );
			}
		} else {
			// Reset $register if we seem to have all data
			// TODO: find better solution for this
			if ( $register && isset( $passthrough['username'] ) ) {
				$register = 0;
			} else {

			}

			// Of to the Subscription Plan Selection Page!
			Payment_HTML::selectSubscriptionPlanForm( $option, $this->userid, $plans, $subscriptionClosed, $passthrough, $register );
		}
	}

	function confirm( $option, $var=array(), $passthrough=false )
	{
		global $database, $my;

		if ( isset( $var['task'] ) ) {
			unset( $var['task'] );
			unset( $var['option'] );
		}

		if ( $this->userid ) {
			$user = new mosUser( $database );
			$user->load( $this->userid );

			$passthrough = false;
		} else {
			unset( $var['usage'] );
			unset( $var['processor'] );
			unset( $var['currency'] );
			unset( $var['amount'] );

			if ( is_array( $passthrough ) ) {
				$user = new mosUser( $database );
				$user->name		= $passthrough['name'];
				$user->username = $passthrough['username'];
				$user->email	= $passthrough['email'];
			} else {
				$user = new mosUser( $database );
				$user->name		= $var['name'];
				$user->username = $var['username'];
				$user->email	= $var['email'];

				$passthrough = array();
				foreach ( $var as $ke => $va ) {
					// We have to support arrays for CB
					if ( is_array( $va ) ) {
						foreach ( $va as $con ) {
							$passthrough[$ke . '[]'] = $con;
						}
					} else {
						$passthrough[$ke] = $va;
					}
				}
			}
		}

		$this->puffer( $option );

		$tos = ( !is_null( $aecConfig->cfg['tos'] ) ) ? $aecConfig->cfg['tos'] : 0;

		$this->coupons = array();
		$this->coupons['active'] = $aecConfig->cfg['enable_coupons'];

		Payment_HTML::confirmForm( $option, $this, $user, $tos, $passthrough );
	}


	function save( $option, $var )
	{
		global $database, $mainframe, $task, $acl, $aecConfig; // Need to load $acl for CBE

		if ( isset( $var['task'] ) ) {
			unset( $var['task'] );
			unset( $var['option'] );
		}

		// Let CB think that everything is going fine
		if ( GeneralInfoRequester::detect_component( 'CB' ) || GeneralInfoRequester::detect_component( 'CBE' ) ) {
			if ( GeneralInfoRequester::detect_component( 'CBE' ) ) {
				global $ueConfig;
			}

			$savetask	= $task;
			$task		= 'done';
			include_once ( $mainframe->getCfg( 'absolute_path' ) . '/components/com_comprofiler/comprofiler.php' );
			$task		= $savetask;
		}

		if ( $this->usage == '' ) {
			$this->usage = $var['usage'];
		}
		if ( $this->processor == '' ) {
			$this->processor = $var['processor'];
		}
		$this->confirmed = 1;

		if ( $this->userid ) {
			$user = new mosUser( $database );
			$user->load( $this->userid );

			$passthrough = false;
		} else {
			// For joomla and CB, we must filter out some internal variables before handing over the POST data
			$badbadvars = array( 'userid', 'method_name', 'usage', 'processor', 'currency', 'amount', 'invoice', 'id', 'gid' );
			foreach ( $badbadvars as $badvar ) {
				if ( isset( $var[$badvar] ) ) {
					unset( $var[$badvar] );
				}
			}

			$_POST = $var;

			if ( GeneralInfoRequester::detect_component( 'CB' ) || GeneralInfoRequester::detect_component( 'CBE' ) ) {
				// This is a CB registration, borrowing their code to save the user

				saveRegistration( $option );

			} else {
				// This is a joomla registration, borrowing their code to save the user
				global $acl, $mosConfig_useractivation, $mosConfig_sitename, $mosConfig_live_site;

				// simple spoof check security
				if ( function_exists( 'josSpoofCheck' ) ) {
					if ( class_exists( 'JConfig' ) ) {
						$token	= JUtility::getToken();
						if(!JRequest::getInt($token, 0, 'post')) {
							JError::raiseError(403, 'Request Forbidden');
						}
					} else {
						josSpoofCheck();
					}
				}

				$row = new mosUser( $database );

				if ( !$row->bind( $_POST, 'usertype' )) {
					mosErrorAlert( $row->getError() );
				}

				mosMakeHtmlSafe( $row );

				$row->id 		= 0;
				$row->usertype 	= '';
				if ( class_exists( 'JConfig' ) ) {
					$authorize	=& JFactory::getACL();

					// Initialize new usertype setting
					$usersConfig = &JComponentHelper::getParams( 'com_users' );
					$newUsertype = $usersConfig->get( 'new_usertype' );
					if (!$newUsertype) {
						$newUsertype = 'Registered';
					}

					$row->gid 		= $authorize->get_group_id( '', $newUsertype, 'ARO' );
				} else {
					$row->gid 		= $acl->get_group_id( 'Registered', 'ARO' );
				}

				if ( $mosConfig_useractivation == 1 ) {
					$row->activation = md5( mosMakePassword() );
					$row->block = '1';
				}

				if ( !$row->check() ) {
					echo '<script>alert(\''
					. html_entity_decode( $row->getError() )
					. '\');window.history.go(-1);</script>' . "\n";
					exit();
				}

				if ( class_exists( 'JConfig' ) ) {
					jimport('joomla.user.helper');

					$salt = JUserHelper::genRandomPassword(32);
					$crypt = JUserHelper::getCryptedPassword($row->password, $salt);
					$row->password = $crypt.':'.$salt;
				} else {
					$pwd 				= $row->password;
					$row->password 		= md5( $row->password );
				}

				$row->registerDate 	= date( 'Y-m-d H:i:s' );

				if ( !$row->store() ) {
					echo '<script>alert(\''
					. html_entity_decode( $row->getError())
					. '\');window.history.go(-1);</script>' . "\n";
					exit();
				}
				$row->checkin();

				$name 		= $row->name;
				$email 		= $row->email;
				$username 	= $row->username;

				$subject 	= sprintf (_SEND_SUB, $name, $mainframe->getCfg( 'sitename' ) );
				$subject 	= html_entity_decode( $subject, ENT_QUOTES );

				if ($mosConfig_useractivation == 1){
					$message = sprintf (_USEND_MSG_ACTIVATE, $name, $mosConfig_sitename, $mosConfig_live_site."/index.php?option=com_registration&task=activate&activation=".$row->activation, $mosConfig_live_site, $username, $pwd);
				} else {
					$message = sprintf (_USEND_MSG, $name, $mosConfig_sitename, $mosConfig_live_site);
				}

				$message = html_entity_decode( $message, ENT_QUOTES );

				// check if Global Config `mailfrom` and `fromname` values exist
				if ( $mainframe->getCfg( 'mailfrom' ) != '' && $mainframe->getCfg( 'fromname' ) != '' ) {
					$adminName2 	= $mainframe->getCfg( 'fromname' );
					$adminEmail2 	= $mainframe->getCfg( 'mailfrom' );
				} else {
					// use email address and name of first superadmin for use in email sent to user
					$query = 'SELECT name, email'
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
				if ( !$aecConfig->cfg['nojoomlaregemails'] ) {
					mosMail( $adminEmail2, $adminName2, $email, $subject, $message );
				}

				// Send notification to all administrators
				$aecUser	= AECToolbox::_aecIP();

				$subject2	= sprintf( _SEND_SUB, $name, $mainframe->getCfg( 'sitename' ) );
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
			$query = 'SELECT id'
			. ' FROM #__users'
			. ' WHERE username = \'' . $var['username'] . '\''
			;
			$database->setQuery( $query );
			$this->userid = $database->loadResult();
		}

		$this->touchInvoice( $option );
		$this->checkout( $option );
	}

	function checkout( $option, $repeat=0 )
	{
		global $database;

		$metaUser = new metaUser( $this->userid );

		$this->puffer( $option );

		if ( ( strcmp( strtolower( $this->processor ), 'none' ) === 0 )
		|| ( strcmp( strtolower( $this->processor ), 'free' ) === 0 ) ) {
			$params = $this->objUsage->getParams();

		 	if ( $params['full_free'] || ( $params['trial_free'] &&
		 	( strcmp( $this->objInvoice->transaction_date, '0000-00-00 00:00:00' ) === 0 ) ) ) {
				$this->objInvoice->pay();
				thanks ( $option, $this->renew, 1 );
				return;
		 	} else {
		 		return;
		 	}
		} elseif ( strcmp( strtolower( $this->processor ), 'error' ) === 0 ) {
	 		// Nope, won't work buddy
		 	notAllowed();
		}

		$user_subscription	= new Subscription( $database );
		$user_subscription->loadUserID( $this->userid );

		$amount				= $this->objUsage->SubscriptionAmount( $this->recurring, $user_subscription );
		$original_amount	= $this->objUsage->SubscriptionAmount( $this->recurring, $user_subscription );
		$warning			= 0;

		if ( !empty( $aecConfig->cfg['enable_coupons'] ) ) {
			$this->coupons['active'] = 1;

			if ( $this->objInvoice->coupons ) {
				$coupons = explode( ';', $this->objInvoice->coupons );

				$this->coupons['coupons'] = array();

				$applied_coupons = array();
				$global_nomix = array();
				foreach ( $coupons as $id => $coupon_code ) {
					$cph = new couponHandler();
					$cph->load( $coupon_code );

					if ( $cph->restrictions['restrict_combination'] ) {
						$nomix = explode( ';', $cph->restrictions['bad_combinations'] );
					} else {
						$nomix = array();
					}

					if ( count( array_intersect( $applied_coupons, $nomix ) ) || in_array( $coupon_code, $global_nomix ) ) {
						// This coupon either interferes with one of the coupons already applied, or the other way round
						$cph->setError( _COUPON_ERROR_COMBINATION );
					} else {
						$cph->getInfo( $amount );
						$cph->checkRestrictions( $metaUser, $original_amount, $this );

						if ( $cph->status ) {
							$this->coupons['coupons'][$id]['code']		= $cph->code;
							$this->coupons['coupons'][$id]['name']		= $cph->name;
							$this->coupons['coupons'][$id]['discount']	= AECToolbox::correctAmount( $cph->discount_amount );
							$this->coupons['coupons'][$id]['action']	= $cph->action;
							$this->coupons['coupons'][$id]['nodirectaction'] = 0;

							$applied_coupons[] = $coupon_code;
							$global_nomix = array_merge( $global_nomix, $nomix );

							// Set a warning notice that the amount doesn't seem to have changed althout its only for the next amount
							if ( is_array( $amount ) ) {
								if ( isset( $amount['amount']['amount1'] ) ) {
									if ( $amount['amount']['amount1'] == $cph->amount ) {
										$this->coupons['coupons'][$id]['nodirectaction'] = 1;
										$warning = 1;
									}
								} elseif ( isset( $amount['amount']['amount2'] ) ) {
									if ( $amount['amount']['amount2'] == $cph->amount ) {
										$this->coupons['coupons'][$id]['nodirectaction'] = 1;
										$warning = 1;
									}
								} elseif ( isset( $amount['amount']['amount3'] ) ) {
									if ( $amount['amount']['amount3'] == $cph->amount ) {
										$this->coupons['coupons'][$id]['nodirectaction'] = 1;
										$warning = 1;
									}
								}
							} else {
								if ( $amount == $cph->amount ) {
									$this->coupons['coupons'][$id]['nodirectaction'] = 1;
									$warning = 1;
								}
							}
							$amount = AECToolbox::correctAmount( $cph->amount );
						}
					}

					if ( !$cph->status ) {
						// Set Error
						$this->coupons['error']		= 1;
						$this->coupons['errormsg']	= $cph->error;
						// Remove Coupon
						$this->objInvoice->removeCoupon( $coupon_code );
						// Recalculate Amount
						$this->objInvoice->computeAmount();
						// Check and store
						$this->objInvoice->check();
						$this->objInvoice->store();
					}
				}
			}

			if ( $warning ) {
				$this->coupons['warning'] = 1;
				$this->coupons['warningmsg'] = html_entity_decode( _COUPON_WARNING_AMOUNT );
			}
		} else {
			$this->coupons['active'] = 0;
		}

		if ( $amount <= 0 )	{
			$this->objInvoice->pay();
			thanks ( $option, $this->renew, 1 );
			return;
		}

		$var = $this->objInvoice->prepareProcessorLink();

		Payment_HTML::checkoutForm( $option, $var['var'], $var['params'], $this, $repeat );
	}

	function internalcheckout( $option, $invoice, $userid )
	{
		global $database;

		$metaUser = new metaUser( $this->userid );

		$this->puffer( $option );

		$var = $this->objInvoice->prepareProcessorLink();

		$new_subscription = new SubscriptionPlan( $database );
		$new_subscription->load( $this->objInvoice->usage );

		$response = $this->pp->checkoutProcess( $var['var'], $this->pp->settings, $metaUser, $new_subscription );

		if ( isset( $response['error'] ) ) {
			$this->error( $option, $metaUser->cmsUser, $this->objInvoice->invoice_number, $response['error'] );
		}
	}

	function thanks( $option, $renew, $free )
	{
		global $database, $mosConfig_useractivation, $ueConfig, $mosConfig_dbprefix;

		if ( $renew ) {
			$msg = _SUB_FEPARTICLE_HEAD_RENEW
			. '</p><p>'
			. _SUB_FEPARTICLE_THANKSRENEW;
			if ( $free ) {
				$msg .= _SUB_FEPARTICLE_LOGIN;
			} else {
				$msg .= _SUB_FEPARTICLE_PROCESSPAY
				. _SUB_FEPARTICLE_MAIL;
			}
		} else {
			$msg = _SUB_FEPARTICLE_HEAD
			. '</p><p>'
			. _SUB_FEPARTICLE_THANKS;
			if ( $free ) {
				if ( $mosConfig_useractivation ) {
					$msg .= _SUB_FEPARTICLE_PROCESS
					. _SUB_FEPARTICLE_ACTMAIL;
				} else {
					$msg .= _SUB_FEPARTICLE_PROCESS
					. _SUB_FEPARTICLE_MAIL;
				}
			} else {
				if ( $mosConfig_useractivation ) {
					$msg .= _SUB_FEPARTICLE_PROCESSPAY
					. _SUB_FEPARTICLE_ACTMAIL;
				} else {
					$msg .= _SUB_FEPARTICLE_PROCESSPAY
					. _SUB_FEPARTICLE_MAIL;
				}
			}
		}

		// Look whether we have a custom ThankYou page
		if ( $aecConfig->cfg['customthanks'] ) {
			mosRedirect( $aecConfig->cfg['customthanks'] );
		} else {
			HTML_Results::thanks( $option, $msg );
		}
	}

	function error( $option, $objUser, $invoice, $error )
	{
		Payment_HTML::error( $option, $objUser, $invoice, $error );
	}
}

class Invoice extends paramDBTable
{
	/** @var int Primary key */
	var $id					= null;
	/** @var int */
	var $active 			= null;
	/** @var int */
	var $userid 			= null;
	/** @var string */
	var $invoice_number 	= null;
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
	var $params 			= null;

	function Invoice(&$db)
	{
		$this->mosDBTable( '#__acctexp_invoices', 'id', $db );
	}

	function loadInvoiceNumber( $invoiceNum )
	{
		global $database;

		$query = 'SELECT id'
		. ' FROM #__acctexp_invoices'
		. ' WHERE invoice_number = \'' . $invoiceNum . '\''
		;
		$database->setQuery( $query );
		$this->load($database->loadResult());
	}

	function hasDuplicate( $userid, $invoiceNum )
	{
		$db2 = $this->get( "_db" );
		$query = 'SELECT count(*)'
		. ' FROM #__acctexp_invoices'
		. ' WHERE userid = ' . (int) $userid
		. ' AND invoice_number = \'' . $invoiceNum . '\''
		;
		$db2->setQuery( $query );
		return $db2->loadResult();
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

						$recurring		= $pp->info['recurring'];

						if ( empty( $this->currency ) ) {
							$this->currency = isset( $pp->settings['currency'] ) ? $pp->settings['currency'] : '';
						}
					} else {
						// Log Error
						return;
					}
			}

			if ( $metaUser->hasSubscription ) {
				$return = $plan->SubscriptionAmount( $recurring, $metaUser->objSubscription );
			} else {
				$return = $plan->SubscriptionAmount( $recurring, false );
			}

			if ( $this->coupons ) {
				$coupons = explode( ';', $this->coupons );

				$cpsh = new couponsHandler();

				$return['amount'] = $cpsh->applyCoupons( $return['amount'], $coupons, $metaUser );
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
			}

			if ( ( strcmp( $this->amount, '0.00' ) === 0 ) && !$recurring ) {
				$this->method = 'free';
			} elseif ( strcmp( $this->method, 'free' ) === 0 ) {
				$this->method = 'error';
				// TODO: Log Error
			}
		}
	}

	function create( $userid, $usage, $processor )
	{
		global $mosConfig_offset_user;

		$invoice_number			= $this->generateInvoiceNumber();

		$this->load(0);
		$this->invoice_number	= $invoice_number;
		$this->active			= 1;
		$this->fixed			= 0;
		$this->created_date		= gmstrftime ( '%Y-%m-%d %H:%M:%S', time() + $mosConfig_offset_user*3600 );
		$this->transaction_date	= '0000-00-00 00:00:00';
		$this->userid			= $userid;
		$this->method			= $processor;
		$this->usage			= $usage;

		$pp = new PaymentProcessor();
		if ( $pp->loadName( $processor ) ) {
			$pp->init();
			$pp->invoiceCreationAction( $this );
		}

		$this->computeAmount();

		if ( !$this->check() ) {
			echo "<script> alert('problem with storing an invoice: ".$this->getError()."'); window.history.go(-1); </script>\n";
			exit();
		}

		if ( !$this->store() ) {
			echo "<script> alert('problem with storing an invoice: ".$this->getError()."'); window.history.go(-1); </script>\n";
			exit();
		}
	}

	function generateInvoiceNumber( $maxlength = 16 )
	{
		global $database;

		$numberofrows	= 1;
		while ( $numberofrows ) {
			// seed random number generator
			srand( (double) microtime() * 10000 );
			$inum =	'I' . substr( base64_encode( md5( rand() ) ), 0, $maxlength );
			// Check if already exists
			$query = 'SELECT count(*)'
			. ' FROM #__acctexp_invoices'
			. ' WHERE invoice_number = \'' . $inum . '\''
			;
			$database->setQuery( $query );
			$numberofrows = $database->loadResult();
		}
		return $inum;
	}

	function processorResponse( $pp, $response, $responsestring )
	{
		global $database;

		$this->computeAmount();

		// Create history entry
		$history = new logHistory( $database );
		$history->entryFromInvoice ( $this, $responsestring, $pp );

		$short = _AEC_MSG_PROC_INVOICE_ACTION_SH;
		$event = _AEC_MSG_PROC_INVOICE_ACTION_EV . "\n";
		foreach ($response as $key => $value) {
			$event .= $key . "=" . $value . "\n";
		}
		$event	.= _AEC_MSG_PROC_INVOICE_ACTION_EV_STATUS;
		$tags	= 'invoice,processor';
		$params = array( 'invoice_number' => $this->invoice_number );

		$event .= ' ';

		if ( $response['valid'] ) {
			$break = 0;

			if ( isset( $response['amount_paid'] ) ) {
				if ( $response['amount_paid'] != $this->amount ) {
					// Amount Fraud, cancel payment and create error log addition
					$event	.= sprintf( _AEC_MSG_PROC_INVOICE_ACTION_EV_FRAUD, $response['amount_paid'], $this->amount );
					$tags	.= ',fraud_attempt,amount_fraud';
					$break	= 1;
				}
			}
			if ( isset($response['amount_currency'] ) ) {
				if ( $response['amount_currency'] != $this->currency ) {
					// Amount Fraud, cancel payment and create error log addition
					$event	.= sprintf( _AEC_MSG_PROC_INVOICE_ACTION_EV_CURR, $response['amount_currency'], $this->currency );
					$tags	.= ',fraud_attempt,currency_fraud';
					$break	= 1;
				}
			}

			if ( !$break ) {
				$renew	= $this->pay();
				thanks( 'com_acctexp', $renew, ($pp === 0) );
				$event	.= _AEC_MSG_PROC_INVOICE_ACTION_EV_VALID;
				$tags	.= ',payment,action';
			}
		} else {
			if ( isset( $response['pending'] ) ) {
				if ( strcmp( $response['pending_reason'], 'signup' ) === 0 ) {
					$plan = new SubscriptionPlan( $database );
					$plan->load( $this->usage );
					$plan_params = $plan->getParams( 'params' );

					if ( $plan_params['trial_free'] ) {
						$this->pay();
						$this->setParams( array( 'free_trial' => $response['pending_reason'] ) );
						$event	.= _AEC_MSG_PROC_INVOICE_ACTION_EV_TRIAL;
						$tags	.= ',payment,action,trial';
					}
				} else {
					$this->setParams( array( 'pending_reason' => $response['pending_reason'] ) );
					$event	.= sprintf( _AEC_MSG_PROC_INVOICE_ACTION_EV_PEND, $response['pending_reason'] );
					$tags	.= ',payment,pending' . $response['pending_reason'];
				}

				$this->check();
				$this->store();
			} elseif ( isset( $response['cancel'] ) ) {
				$metaUser = new metaUser();
				$metaUser->load( $this->userid );
				$event	.= _AEC_MSG_PROC_INVOICE_ACTION_EV_CANCEL;
				$tags	.= ',cancel';

				if ( $metaUser->objSubscription->hasSubscription ) {
					$metaUser->objSubscription->setStatus( 'Cancelled' );
					$event .= _AEC_MSG_PROC_INVOICE_ACTION_EV_USTATUS;
				}
			} elseif ( isset( $response['delete'] ) ) {
				$metaUser = new metaUser();
				$metaUser->load( $this->userid );
				$event	.= _AEC_MSG_PROC_INVOICE_ACTION_EV_REFUND;
				$tags	.= ',refund';

				if ( $metaUser->objSubscription->hasSubscription ) {
					$metaUser->objSubscription->expire();
					$event .= _AEC_MSG_PROC_INVOICE_ACTION_EV_EXPIRED;
				}
			} elseif (isset($response['eot'])) {
				$metaUser = new metaUser();
				$metaUser->load($this->userid);
				$event	.= _AEC_MSG_PROC_INVOICE_ACTION_EV_EOT;
				$tags	.= ',eot';
			} elseif (isset($response['duplicate'])) {
				$metaUser = new metaUser();
				$metaUser->load($this->userid);
				$event	.= _AEC_MSG_PROC_INVOICE_ACTION_EV_DUPLICATE;
				$tags	.= ',duplicate';
			} else {
				$event	.= _AEC_MSG_PROC_INVOICE_ACTION_EV_U_ERROR;
				$tags	.= ',general_error';
			}
		}

		$eventlog = new eventLog( $database );
		$eventlog->issue( $short, $tags, $event, $params );
	}

	function pay()
	{
		global $database;

		// Get Subscription record
		$metaUser = new metaUser( $this->userid );

		// This should not happen, but we might have an fluid subscription system in the future
		if ( !$metaUser->hasSubscription ) {
			$metaUser->objSubscription->load(0);
			$metaUser->objSubscription->createNew ( $this->userid, $this->method, 0 );
		}

		// Apply the Plan
		$renew = $metaUser->objSubscription->applyUsage( $this->usage, $this->method );

		$new_plan = new SubscriptionPlan( $database );
		$new_plan->load( $this->usage );

		if ( $this->coupons ) {
			$coupons = explode( ';', $this->coupons );
			foreach ( $coupons as $coupon_code ) {
				$cph = new couponHandler();
				$cph->load( $coupon_code );

				if ( $cph->coupon->micro_integrations ) {
					$micro_integrations = explode( ';', $cph->coupon->micro_integrations );
					foreach ( $micro_integrations as $mi_id ) {
						$mi = new microIntegration( $database );
						if ( $mi->mi_exists( $mi_id ) ) {
							$mi->load( $mi_id );
							if ( $mi->callIntegration() ) {
								$mi->action( $metaUser->userid, $new_plan );
							}
						}
					}
				}
			}
		}

		$this->setTransactionDate();

		return $renew;
	}

	function setTransactionDate()
	{
		global $database, $mosConfig_offset_user, $aecConfig;

		$time_passed		= ( strtotime( $this->transaction_date ) - time() + $mosConfig_offset_user*3600 ) / 3600;
		$transaction_date	= gmstrftime ( '%Y-%m-%d %H:%M:%S', time() + $mosConfig_offset_user*3600 );

		if ( ( strcmp( $this->transaction_date, '0000-00-00 00:00:00' ) === 0 )
			|| ( $time_passed > $aecConfig->cfg['invoicecushion'] ) ) {
			$this->counter = $this->counter + 1;
			$this->transaction_date	= $transaction_date;

			$transactions = $this->getParams( 'transactions' );
			$transactions[] = $transaction_date . ";" . $this->amount . ";" . $this->currency . ";" . $this->method;
			$transactions = $this->setParams( $transactions, 'transactions' );

			$this->check();
			$this->store();
		} else {
			return;
		}
	}

	function prepareProcessorLink()
	{
		global $database, $mosConfig_live_site;

		$int_var['params'] = $this->getParams();

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
		 	notAllowed();
		}

		$pp->init();
		$pp->getInfo();

		$int_var['planparams'] = $new_subscription->getProcessorParameters( $pp->id );
		$int_var['recurring'] = $pp->info['recurring'];

		$amount = $new_subscription->SubscriptionAmount( $int_var['recurring'], $metaUser->objsubscription );

		if ( !empty( $this->coupons ) ) {
			$coupons = explode( ';', $this->coupons);

			$cph = new couponsHandler();

			$amount['amount'] = $cph->applyCoupons( $amount['amount'], $coupons, $metaUser );
		}

		$int_var['amount']		= $amount['amount'];
		$int_var['return_url']	= $amount['return_url'];
		$int_var['invoice']		= $this->invoice_number;
		$int_var['usage']		= $this->invoice_number;

		// Assemble Checkout Response
		$return['var']		= $pp->checkoutAction( $int_var, $metaUser, $new_subscription );
		$return['params']	= $pp->getParamsHTML( $int_var['params'], $pp->getParams( $int_var['params'] ) );

		return $return;
	}

	function addCoupon( $couponcode )
	{
		if ( $this->coupons ) {
			$oldcoupons = explode( ';', $this->coupons );
		} else {
			$oldcoupons = array();
		}

		if ( !in_array( $couponcode, $oldcoupons ) ) {
			$oldcoupons[] = $couponcode;

			$cph = new couponHandler();
			$cph->load( $couponcode );

			if ( $cph->status ) {
				$cph->coupon->incrementCount();
			}
		}

		$this->coupons = implode( ';', $oldcoupons );
	}

	function removeCoupon( $couponcode )
	{
		$oldcoupons = explode( ';', $this->coupons );

		if ( in_array( $couponcode, $oldcoupons ) ) {
			foreach ( $oldcoupons as $id => $cc ) {
				if ( $cc == $couponcode ) {
					unset( $oldcoupons[$id] );
				}
			}

			$cph = new couponHandler();
			$cph->load( $couponcode );
			if ($cph->status) {
				$cph->coupon->decrementCount();
			}
		}

		$this->coupons = implode( ';', $oldcoupons );
	}

	function savePostParams( $array )
	{
		unset( $array['task'] );
		unset( $array['option'] );
		unset( $array['invoice'] );

		$this->addParams( $array );
		return true;
	}

}

/**
 * User management
 *
 */
class Subscription extends paramDBTable
{
	/** @var int Primary key */
	var $id					= null;
	/** @var int */
	var $userid				= null;
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
	/** @var int */
	var $previous_plan		= null;
	/** @var string */
	var $used_plans			= null;
	/** @var string */
	var $recurring			= null;
	/** @var int */
	var $lifetime			= null;
	/** @var text */
	var $params 			= null;
	/** @var text */
	var $custom_params		= null;

	/**
	* @param database A database connector object
	*/
	function Subscription( &$db )
	{
		$this->mosDBTable( '#__acctexp_subscr', 'id', $db );
	}

	/**
	 * loads specified user
	 *
	 * @param int $userid
	 */
	function loadUserid( $userid )
	{
		global $database;

		$query = 'SELECT id'
		. ' FROM #__acctexp_subscr'
		. ' WHERE userid = \'' . $userid . '\''
		;
		$database->setQuery( $query );

		$this->load($database->loadResult());
	}

	function createNew( $userid, $processor, $pending )
	{
		global $mosConfig_offset_user;

		$this->userid		= $userid;
		$this->signup_date	= date( 'Y-m-d H:i:s', time() + $mosConfig_offset_user*3600 );
		$this->status		= $pending ? 'Pending' : 'Active';
		$this->type			= $processor;

		$this->check();
		$this->store();
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
		global $database, $mosConfig_offset_user, $aecConfig;

		$aecexpid = AECfetchfromDB::ExpirationIDfromUserID( $this->userid );

		if ( $aecexpid ) {
			$alert['level']		= -1;
			$alert['daysleft']	= 0;

			$objexpiration = new AcctExp( $database );
			$objexpiration->load( $aecexpid );

			$expstamp = strtotime( $objexpiration->expiration );

			// Get how many days left to expire (3600 sec = 1 hour)
			$alert['daysleft']	= round( ( $expstamp - ( time() + $mosConfig_offset_user*3600 ) ) / ( 3600 * 24 ) );

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

	function verifylogin( $block )
	{
		global $mosConfig_live_site, $database, $aecConfig;

		if ( strcmp( $this->status, 'Excluded' ) === 0 ) {
			$expired = false;
		} elseif ( strcmp( $this->status, 'Expired' ) === 0 ) {
			$expired = true;
		} else {
			$expiration = new AcctExp( $database );
			$expiration->load( AECfetchfromDB::ExpirationIDfromUserID( $this->userid ) );

			if ( $expiration->id > 0 ) {
				$expired = $expiration->is_expired();
			} else {
				$expired = false;
			}
		}

		if ( ( $expired || ( strcmp( $this->status, 'Closed' ) === 0 ) ) && $aecConfig->cfg['require_subscription'] ) {
			$expire = $this->expire();

			if ( $expire ) {
				mosRedirect( AECToolbox::deadsureURL( '/index.php?option=com_acctexp&task=expired&Itemid=' . $this->userid ) );
				exit();
			}
		} elseif ( ( strcmp( $this->status, 'Pending' ) === 0 ) || $block ) {
			mosRedirect( AECToolbox::deadsureURL( '/index.php?option=com_acctexp&task=pending&Itemid=' . $this->userid ) );
			exit();
		}
	}

	function expire( $overridefallback=false )
	{
		global $database;

		if ( strcmp( $this->status, 'Excluded' ) === 0 ) {
			return false;
		}

		if ( $this->plan ) {
			$subscription_plan = new SubscriptionPlan( $database );
			$subscription_plan->load( $this->plan );
			$plan_params = $subscription_plan->getParams();
		} else {
			$plan_params = array();
			$subscription_plan = false;
		}

		$this_params = $this->getParams();

		if ( $plan_params['fallback'] && !$overridefallback ) {

			$mih = new microIntegrationHandler();
			$mih->userPlanExpireActions( $this->userid, $subscription_plan );

			$this->applyUsage( $plan_params['fallback'], 'none', 1 );
			return false;
		} else {
			// Set a Trial flag if this is an expired Trial for further reference
			if ( strcmp( $this->status, 'Trial' ) === 0 ) {
				$this->addParams( array( 'trialflag' => 1 ) );
			} elseif ( is_array( $this_params ) ) {
				if ( in_array( 'trialflag', $this_params ) ) {
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

			$mih = new microIntegrationHandler();
			$mih->userPlanExpireActions( $this->userid, $subscription_plan );

			return true;
		}
	}

	function setStatus( $status )
	{
		$this->status = $status;
		$this->check();
		$this->store();
	}

	function applyUsage( $usage = 0, $processor = 'none', $silent = 0 )
	{
		global $database;

		if ( !$usage ) {
			$usage = $this->plan;
		}

		$new_plan = new SubscriptionPlan( $database );
		$new_plan->load($usage);

		if ( $new_plan->id ) {
			$renew = $new_plan->applyPlan( $this->userid, $processor, $silent );
			return $renew;
		} else {
			return false;
		}
	}

	function setPlanID( $id )
	{
		if ( $this->plan ) {
			$this->previous_plan = $this->plan;
			$this->setUsedPlan( $this->plan );
		}
		$this->plan	= $id;
	}

	function setUsedPlan( $id )
	{
		$used_plans = $this->getUsedPlans();

		if ( isset( $used_plans[$id] ) ) {
			$used_plans[$id]++;
		} else {
			$used_plans[$id] = 1;
		}

		$new_used_plans = array();
		foreach ( $used_plans as $planid => $n ) {
			$new_used_plans[] = $planid . ',' . $n;
		}

		$this->used_plans = implode( ';', $new_used_plans );

	}

	function getUsedPlans()
	{
		$used_plans = explode( ';', $this->used_plans );

		$array = array();
		foreach ( $used_plans as $entry ) {
			$entryarray = explode( ',', $entry );

			if ( !empty( $entryarray ) ) {
				if ( isset( $entryarray[1] ) ) {
					if ( !empty( $entryarray[1] ) ) {
						$amount = $entryarray[1];
					} else {
						$amount = 1;
					}
				} else {
					$amount = 1;
				}

				$array[$entryarray[0]] += $amount;
			}
		}

		return $array;
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
			$query = 'SELECT name, email'
			. ' FROM #__users'
			. ' WHERE usertype = \'superadministrator\''
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

		foreach ( $admins['users'] AS $id ) {
			$query = 'SELECT email, sendEmail'
			. ' FROM #__users'
			. ' WHERE id = \'' . $id . '\''
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
		// Get Params
		$params = $this->getParams();

		// Create the Params Prefix
		$flag_name = 'MI_FLAG_USAGE_' . strtoupper( $usage ) . '_MI_' . strtoupper( $mi );

		// Filter out the params for this usage and MI
		$mi_params = array();
		if ( $params ) {
			foreach ( $params as $name => $value ) {
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
		// Get Params
		$params = $this->getParams();

		// Create the Params Prefix
		$flag_name = 'MI_FLAG_USAGE_' . strtoupper( $usage ) . '_MI_' . $mi;

		// Write to $params array
		foreach ( $flags as $name => $value ) {
			$param_name = $flag_name . '_' . strtoupper( $name );
			$params[$param_name] = $value;
		}

		// Set Params
		$this->setParams( $params );
		$this->check();
		$this->store();
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
		} elseif ( class_exists( 'JConfig' ) ) {
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

		$overrides = explode( ' ', $aecConfig->cfg['bypassintegration'] );

		if ( in_array( $component, $overrides ) ) {
			return false;
		}

		$pathCB		= $mainframe->getCfg( 'absolute_path' ) . '/components/com_comprofiler/';
		$pathSMF	= $mainframe->getCfg( 'absolute_path' ) . '/administrator/components/com_smf/';
		switch ( $component ) {
			case 'CB': // Community Builder
				$is_cbe	= ( is_dir( $pathCB. 'enhanced' ) || is_dir( $pathCB . 'enhanced_admin' ) );
				$is_cb	= ( is_dir( $pathCB ) && !$is_cbe );
				return $is_cb;
				break;

			case 'CBE': // Community Builder Enhanced
				$is_cbe = ( is_dir( $pathCB . 'enhanced' ) || is_dir( $pathCB . 'enhanced_admin' ) );
				return $is_cbe;
				break;

			case 'CBM': // Community Builder Moderator for Workflows
				return file_exists( $mainframe->getCfg( 'absolute_path' ) . '/modules/mod_comprofilermoderator.php' );
				break;

			case 'UE': // User Extended
				return in_array( $mainframe->getCfg( 'dbprefix' ) . 'user_extended', $tables );
				break;

			case 'SMF': // Simple Machines Forum
				return file_exists( $pathSMF . 'config.smf.php') || file_exists( $pathSMF . 'smf.php' );
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

		$query = 'SELECT g2.group_id'
		. ' FROM #__core_acl_aro_groups AS g1'
		. ' INNER JOIN #__core_acl_aro_groups AS g2 ON g1.lft >= g2.lft AND g1.lft <= g2.rgt'
		. ' WHERE g1.group_id = ' . $group_id
		. ' GROUP BY g2.group_id'
		. ' ORDER BY g2.lft'
		;
		$database->setQuery( $query );
		$rows = $database->loadObjectList();

		for( $i = 0, $n = count( $rows ); $i < $n; $i++ ) {
		    $row = &$rows[$i];
		    $group_list[$i] = $row->group_id;
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

		$query = 'SELECT userid'
		. ' FROM #__acctexp_invoices'
		. ' WHERE invoice_number = \'' . $invoice_number . '\''
		;
		$database->setQuery( $query );
		return $database->loadResult();
	}

	function InvoiceIDfromNumber( $invoice_number, $userid = 0 )
	{
		global $database;

		$query = 'SELECT id'
		. ' FROM #__acctexp_invoices'
		. ' WHERE invoice_number = \'' . $invoice_number . '\''
		. ' AND active = \'1\''
		;
		if ( $userid ) {
			$query .= ' AND userid = \'' . $userid . '\'';
		}
		$database->setQuery( $query );
		return $database->loadResult();
	}

	function lastUnclearedInvoiceIDbyUserID( $userid )
	{
		global $database;

		$query = 'SELECT invoice_number'
		. ' FROM #__acctexp_invoices'
		. ' WHERE userid = \'' . (int) $userid . '\''
		. ' AND transaction_date = \'0000-00-00 00:00:00\''
		. ' AND active = \'1\''
		;
		$database->setQuery( $query );
		return $database->loadResult();
	}

	function lastClearedInvoiceIDbyUserID( $userid, $planid=0 )
	{
		global $database;

		$query = 'SELECT id'
		. ' FROM #__acctexp_invoices as a'
		. ' WHERE a.userid = \'' . (int) $userid . '\'';

		if ( $planid ) {
			$query .= ' AND a.usage = \'' . (int) $planid . '\'';
		}

		$query .= ' ORDER BY transaction_date';

		$database->setQuery( $query );
		return $database->loadResult();
	}

	function InvoiceCountbyUserID( $userid )
	{
		global $database;

		$query = 'SELECT count(*)'
		. ' FROM #__acctexp_invoices'
		. ' WHERE userid = \'' . (int) $userid . '\''
		. ' AND active = \'1\''
		;
		$database->setQuery( $query );
		return $database->loadResult();
	}

	function SubscriptionIDfromUserID( $userid )
	{
		global $database;

		$query = 'SELECT id'
		. ' FROM #__acctexp_subscr'
		. ' WHERE userid = \'' . (int) $userid . '\''
		;
		$database->setQuery( $query );
		return $database->loadResult();
	}

	function ExpirationIDfromUserID( $userid )
	{
		global $database;

		$query = 'SELECT id'
		. ' FROM #__acctexp'
		. ' WHERE userid = \'' . (int) $userid . '\''
		;
		$database->setQuery( $query );
		return $database->loadResult();
	}

	function UserIDfromExpirationID( $id )
	{
		global $database;

		$query = 'SELECT userid'
		. ' FROM #__acctexp'
		. ' WHERE id = \'' . (int) $id . '\''
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
			. 'BRL,BND,BIF,CVE,KYD,GHC,XOF,XAF,XPF,'
			. 'CLP,COP,KMF,BAM,NIO,CRC,CUP,GMD,'
			. 'MKD,AED,DJF,STD,DOP,VND,XCD,SVC,'
			. 'ETB,FKP,FJD,CDF,FRF,HTG,PYG,GNF,'
			. 'GWP,GYD,HKD,UAH,INR,IRR,IQD,JMD,'
			. 'JOD,KES,PGK,LAK,KWD,MWK,ZMK,AOR,MMK,'
			. 'LBP,ALL,HNL,SLL,LRD,LYD,SZL,'
			. 'LSL,MGF,MYR,TMM,MUR,MZM,MXN,'
			. 'MXV,MAD,ERN,NAD,NPR,ANG,'
			. 'AON,TWD,ZRN,BTN,KPW,PEN,MRO,TOP,'
			. 'PKR,XPD,MOP,UYU,PHP,XPT,BWP,QAR,GTQ,'
			. 'ZAL,OMR,KHR,MVR,IDR,RWF,SAR,'
			. 'SCR,XAG,SGD,SBD,KGS,SOS,LKR,SHP,ECS,'
			. 'SDD,SRG,SYP,TJR,BDT,WST,TZS,KZT,TPE,'
			. 'TTD,MNT,TND,UGX,ECV,CLF,USN,USS,UZS,'
			. 'VUV,KRW,YER,CNY,ZWD'
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

	/**
	 * get user ip & isp
	 *
	 * @return array w/ values
	 */
	function _aecIP()
	{
		// userip & hostname
		$aecUser['ip'] 	= $_SERVER['REMOTE_ADDR'];
		$aecUser['isp'] = gethostbyaddr( $_SERVER['REMOTE_ADDR'] );

		return $aecUser;
	}

	/**
	 * Return a URL based on the sef and user settings
	 * @parameter url
	 * @return string
	 */
	function backendTaskLink( $task, $text )
	{
		global $mosConfig_live_site;

		return '<a href="' .  $mosConfig_live_site . '/administrator/index2.php?option=com_acctexp&amp;task=' . $task . '" title="' . $text . '">' . $text . '</a>';
	}

	/**
	 * Return a URL based on the sef and user settings
	 * @parameter url
	 * @return string
	 */
	function deadsureURL( $url, $secure=false )
	{
		global $mosConfig_live_site, $mosConfig_absolute_path, $database, $aecConfig;

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
		. ' WHERE username = \'' . $username . '\''
		;
		$database->setQuery( $query );
		$id = $database->loadResult();

		$metaUser = new metaUser( $id );

		if ( $metaUser->hasSubscription ) {
			$metaUser->objSubscription->verifyLogin( $metaUser->cmsUser->block );
		} else {
			$aecexpid = AECfetchfromDB::ExpirationIDfromUserID( $id );

			if ( $metaUser->hasExpiration ) {
				$metaUser->objExpiration->manualVerify();
			} else {
				if ( $aecConfig->cfg['require_subscription'] ) {
					if ( $aecConfig->cfg['entry_plan'] ) {
						$user_subscription = new Subscription( $database );
						$user_subscription->load(0);
						$user_subscription->createNew( $id, 'Free', 1 );

						$metaUser = new metaUser( $id );
						$metaUser->objSubscription->applyUsage( $aecConfig->cfg['entry_plan'], 'none', 1 );
						AECToolbox::VerifyUsername( $username );
					} else {
						mosRedirect( AECToolbox::deadsureURL( '/index.php?option=com_acctexp&task=subscribe&Itemid=' . $id ) );
						return null;
					}
				}
			}
		}
		return true;
	}

	function quickVerifyUserID( $userid )
	{
		global $database;

		$query = 'SELECT status'
		. ' FROM #__acctexp_subscr'
		. ' WHERE userid = \'' . (int) $userid . '\''
		;
	 	$database->setQuery( $query );
		$aecstatus = $database->loadResult();

		if ( $aecstatus ) {
			if ( strcmp( $aecstatus, 'Active' ) === 0 ) {
				return true;
			} else {
				return false;
			}
		} else {
			$query = 'SELECT id'
			. ' FROM #__acctexp'
			. ' WHERE userid = \'' . (int) $userid . '\''
			;
			$database->setQuery( $query );
			$aecexpid = $database->loadResult();

			if ( $aecexpid ) {
				$aecuser = new AcctExp( $database );
				$aecuser->load( $aecexpid );
				if ( $aecuser->is_expired() ) {
					return false;
				} else {
					return true;
				}
			} else {
				return null;
			}
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

		$a		= explode( '.', $amount );
		$amount = $a[0] . '.' . substr( str_pad( $a[1], 2, '0' ), 0, 2 );

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

		return $post;
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

	function rewriteEngine( $subject, $metaUser=null, $subscriptionPlan=null )
	{
		global $database, $mosConfig_absolute_path, $mosConfig_live_site;

		// Check whether a replacement exists at all
		if ( strpos( $subject, '[[' ) === false ) {
			return $subject;
		}

		$rewrite = array();

		$rewrite['cms_absolute_path']	= $mosConfig_absolute_path;
		$rewrite['cms_live_site']		= $mosConfig_live_site;

		if ( is_object( $metaUser ) ) {

			if ( $metaUser->hasExpiration ) {
				$rewrite['expiration_date'] = $metaUser->objExpiration->expiration;
			}

			$rewrite['user_id']			= $metaUser->cmsUser->id;
			$rewrite['user_username']	= $metaUser->cmsUser->username;
			$rewrite['user_name']		= $metaUser->cmsUser->name;
			$rewrite['user_email']		= $metaUser->cmsUser->email;

			if ( GeneralInfoRequester::detect_component( 'CB' ) || GeneralInfoRequester::detect_component( 'CBE' ) ) {
				if ( !$metaUser->hasCBprofile ) {
					$metaUser->loadCBuser();
				}

				if ( $metaUser->hasCBprofile ) {
					$query = 'SELECT name'
							. ' FROM #__comprofiler_fields'
							. ' WHERE `table` != \'#__users\''
							. ' AND name != \'NA\'';
					$database->setQuery( $query );
					$fields = $database->loadResultArray();

					if ( !empty( $fields ) ) {
						foreach ( $fields as $fieldname ) {
							$rewrite['user_' . $fieldname] = $metaUser->cbUser->$fieldname;
						}
					}

					$rewrite['user_activationcode']		= $metaUser->cbUser->cbactivation;
					$rewrite['user_activationlink']		= $mosConfig_live_site."/index.php?option=com_comprofiler&task=confirm&confirmcode=" . $metaUser->cbUser->cbactivation;
				} else {
					$rewrite['user_activationcode']		= $metaUser->cmsUser->activation;
					$rewrite['user_activationlink']		= $mosConfig_live_site."/index.php?option=com_registration&task=activate&activation=" . $metaUser->cmsUser->activation;
				}
			} else {
				$rewrite['user_activationcode']		= $metaUser->cmsUser->activation;
				$rewrite['user_activationlink']		= $mosConfig_live_site."/index.php?option=com_registration&task=activate&activation=" . $metaUser->cmsUser->activation;
			}

			if ( $metaUser->hasSubscription ) {
				$rewrite['subscription_type']			= $metaUser->objSubscription->type;
				$rewrite['subscription_status']			= $metaUser->objSubscription->status;
				$rewrite['subscription_signup_date']	= $metaUser->objSubscription->signup_date;
				$rewrite['subscription_lastpay_date']	= $metaUser->objSubscription->lastpay_date;
				$rewrite['subscription_plan']			= $metaUser->objSubscription->plan;
				$rewrite['subscription_previous_plan']	= $metaUser->objSubscription->previous_plan;
				$rewrite['subscription_recurring']		= $metaUser->objSubscription->recurring;
				$rewrite['subscription_lifetime']		= $metaUser->objSubscription->lifetime;
			}

			$lastinvoice = AECfetchfromDB::lastClearedInvoiceIDbyUserID( $metaUser->cmsUser->id );

			if ( $lastinvoice ) {
				$invoice = new Invoice( $database );
				$invoice->load( $lastinvoice );

				$rewrite['invoice_id']					= $invoice->id;
				$rewrite['invoice_number']				= $invoice->invoice_number;
				$rewrite['invoice_created_date']		= $invoice->created_date;
				$rewrite['invoice_transaction_date']	= $invoice->transaction_date;
				$rewrite['invoice_method']				= $invoice->method;
				$rewrite['invoice_amount']				= $invoice->amount;
				$rewrite['invoice_currency']			= $invoice->currency;
				$rewrite['invoice_coupons']				= $invoice->coupons;
			} else {
				$rewrite['invoice_id']					= '';
				$rewrite['invoice_number']				= '';
				$rewrite['invoice_created_date']		= '';
				$rewrite['invoice_transaction_date']	= '';
				$rewrite['invoice_method']				= '';
				$rewrite['invoice_amount']				= '';
				$rewrite['invoice_currency']			= '';
				$rewrite['invoice_coupons']				= '';
			}
		}

		if ( is_object( $subscriptionPlan ) ) {
			$rewrite['plan_name'] = $subscriptionPlan->name;
			$rewrite['plan_desc'] = $subscriptionPlan->getProperty( 'desc' );
		}

		$search = array();
		$replace = array();
		foreach ( $rewrite as $name => $replacement ) {
			$search[]	= '[[' . $name . ']]';
			$replace[]	= $replacement;
		}

		return str_replace( $search, $replace, $subject );
	}

	function rewriteEngineInfo( $switches )
	{
		$rewrite = array();

		if ( in_array( 'cms', $switches ) ) {
			$rewrite['cms'][] = 'absolute_path';
			$rewrite['cms'][] = 'live_site';
		}

		if ( in_array( 'expiration', $switches ) ) {
			$rewrite['expiration'][] = 'date';
		}

		if ( in_array( 'user', $switches ) ) {
			$rewrite['user'][] = 'id';
			$rewrite['user'][] = 'username';
			$rewrite['user'][] = 'name';
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
		}

		if ( in_array( 'invoice', $switches ) ) {
			$rewrite['invoice'][] = 'id';
			$rewrite['invoice'][] = 'number';
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

		return $return;
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

		$query = 'SELECT id'
		. ' FROM #__acctexp_plans'
		. ' WHERE micro_integrations != \'\''
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

	function userPlanExpireActions( $userid, $subscription_plan )
	{
		global $database;

		$mi_autointegrations = $this->getAutoIntegrations();

		if ( is_array( $mi_autointegrations ) || ( $subscription_plan !== false ) ) {

			$user_integrations		= explode( ';', $subscription_plan->micro_integrations );
			$user_auto_integrations = array_intersect( $user_integrations, $mi_autointegrations );

			if ( count( $user_auto_integrations ) ) {
				foreach ( $user_auto_integrations as $mi_id ) {
					$mi = new microIntegration( $database );
					$mi->load( $mi_id );
					if ( $mi->callIntegration() ) {
						$mi->expiration_action( $userid, $subscription_plan );
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

		$query = 'SELECT id'
		. ' FROM #__acctexp_microintegrations'
		. ' WHERE auto_check = \'1\''
		;
		$database->setQuery( $query );
		return $database->loadResultArray();
	}

	function getUserChangeIntegrations()
	{
		global $database;

		$query = 'SELECT id'
		. ' FROM #__acctexp_microintegrations'
		. ' WHERE active = \'1\''
		. ' AND on_userchange = \'1\''
		;
		$database->setQuery( $query );
		return $database->loadResultArray();
	}

	function userchange( $row, $post, $trace = '' )
	{
		global $database;

		$mi_list = $this->getUserChangeIntegrations();

		if ( !is_object( $row ) ) {
			$userid = $row;

			$row = new mosUser( $database );
			$row->load( $userid );
		}

		if ( count( $mi_list ) > 0 ) {
			foreach ( $mi_list as $mi_id ) {
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
}

class microIntegration extends paramDBTable
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
		. ' WHERE id = \'' . $mi_id . '\''
		;
		$database->setQuery( $query );
		return $database->loadResult();
	}

	function callIntegration( $override = 0 )
	{
		global $mosConfig_absolute_path;

		$filename = $mosConfig_absolute_path . '/components/com_acctexp/micro_integration/' . $this->class_name . '.php';

		if ( ( ( !$this->active && $this->id ) || !file_exists( $filename ) ) && !$override ) {
			// MI does not exist or is deactivated
			return false;
		} elseif ( file_exists( $filename ) ){
			include_once $filename;

			$class = $this->class_name;

			$this->mi_class = new $class ();

			$info = $this->mi_class->Info();

			if ( is_null( $this->name ) || ( $this->name == '' ) ) {
				$this->name = $info['name'];
			}

			if ( is_null( $this->desc ) || ( $this->desc == '' ) ) {
				$this->desc = $info['desc'];
			}

			return true;
		}
	}

	function action( $userid, $objplan=null )
	{
		$params = $this->getParams();

		if ( is_array( $userid ) ){
			foreach ( $userid as $id ) {
				$this->mi_class->action( $params, $id, $objplan );
			}
		} else {
			$this->mi_class->action( $params, $userid, $objplan );
		}
	}

	function pre_expiration_action( $userid, $objplan=null )
	{
		$params = $this->getParams();

		if ( method_exists( $this->mi_class, 'pre_expiration_action' ) ) {
			if ( is_array( $userid ) ) {
				foreach ( $userid as $id ) {
					$return = $this->mi_class->pre_expiration_action( $params, $id, $objplan, $this->id );
				}
			} else {
				$return = $this->mi_class->pre_expiration_action( $params, $userid, $objplan, $this->id );
			}
		}

		return $return;
	}

	function expiration_action( $userid, $objplan=null )
	{
		$params = $this->getParams();

		if ( method_exists( $this->mi_class, 'expiration_action' ) ) {
			if ( is_array( $userid ) ){
				foreach ( $userid as $id ) {
					$this->mi_class->expiration_action( $params, $id, $objplan );
				}
			} else {
				$this->mi_class->expiration_action( $params, $userid, $objplan );
			}
		}
	}

	function on_userchange_action( $row, $post, $trace )
	{
		$params = $this->getParams();

		if ( method_exists( $this->mi_class, 'on_userchange_action' ) ) {
			$this->mi_class->on_userchange_action( $params, $row, $post, $trace );
		}
	}

	function profile_info( $userid )
	{
		$params = $this->getParams();

		if ( method_exists( $this->mi_class, 'profile_info' ) ) {
			$return = $this->mi_class->profile_info( $params, $userid );
			return $return;
		} else {
			return false;
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

		$params = $this->getParams();

		if ( method_exists( $this->mi_class, 'Settings' ) ) {
			$settings = $this->mi_class->Settings( $params );

			// Autoload Params if they have not been called in by the MI
			foreach ( $settings as $name => $setting ) {
				// Do we have a parameter at first position?
				if ( isset( $setting[1] ) && !isset( $setting[3] ) ) {
					if ( isset( $params[$name] ) ) {
						$settings[$name][3] = $params[$name];
					}
				} else {
					if ( isset( $params[$name] ) ) {
						$settings[$name][1] = $params[$name];
					}
				}
			}

			return $settings;
		} else {
			return false;
		}
	}

	function savePostParams( $array )
	{
		$params = $this->stripNonParams($array);

		$params = $this->commonParamInfo($params);

		if ( method_exists( $this->mi_class, 'saveparams' ) ) {
			$new_params = $this->mi_class->saveparams( $params );
		} else {
			$new_params = $params;
		}

		$new_params = $this->stripcommonParamInfo( $new_params );

		$this->setParams( $new_params );
		return true;
	}

	function stripNonParams( $array )
	{
		$vars = get_class_vars( 'microIntegration' );

		foreach ( $vars as $name => $blind ) {
			if ( isset( $array[$name] ) ) {
				unset( $array[$name] );
			}
		}
		return $array;
	}

	function commonParamInfo( $params=array() )
	{
		$params['MI_ID'] = $this->id;

		return $params;
	}

	function stripcommonParamInfo( $params )
	{
		$commonparams = $this->commonParamInfo();

		foreach ($commonparams as $key) {
			if (isset($params[$key])) {
				unset($params[$key]);
			}
		}

		return $params;
	}

	function getSettingsDescriptions()
	{

	}

	function delete ()
	{
		$params = $this->getParams();

		if ( method_exists( $this->mi_class, 'delete' ) ){
			$this->mi_class->delete( $params );
		}
	}
}

class couponsHandler
{
	function applyCoupons( $amount, $coupons, $metaUser )
	{
		$applied_coupons = array();
		$global_nomix = array();
		foreach ( $coupons as $arrayid => $coupon_code ) {
			$cph = new couponHandler();
			$cph->load( $coupon_code );

			if ( $cph->restrictions['restrict_combination'] ) {
				$nomix = explode( ';', $cph->restrictions['bad_combinations'] );
			} else {
				$nomix = array();
			}

			if ( count( array_intersect( $applied_coupons, $nomix ) ) || in_array( $coupon_code, $global_nomix ) ) {
				// This coupon either interferes with one of the coupons already applied, or the other way round
			} else {
				if ( $cph->status ) {
					// Coupon approved, checking restrictions
					$cph->checkRestrictions( $metaUser );
					if ( $cph->status ) {
						$amount = $cph->applyCoupon( $amount );
						$applied_coupons[] = $coupon_code;
						$global_nomix = array_merge( $global_nomix, $nomix );
					} else {
						// Coupon restricted for this user, thus it needs to be deleted later on
					}
				} else {
					// Coupon not approved, thus it needs to be deleted later on
				}
			}
		}

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

		$query = 'SELECT id'
		. ' FROM #__acctexp_coupons_static'
		. ' WHERE coupon_code = \'' . $coupon_code . '\''
		;
		$database->setQuery( $query );
		$couponid = $database->loadResult();

		if ( $couponid ) {
			$this->type = 1;
		} else {
			$query = 'SELECT id'
			. ' FROM #__acctexp_coupons'
			. ' WHERE coupon_code = \'' . $coupon_code . '\''
			;
			$database->setQuery( $query );
			$couponid = $database->loadResult();
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
			$this->discount		= $this->coupon->getParams( 'discount' );
			$this->restrictions = $this->coupon->getParams( 'restrictions' );

			// Check whether coupon can be used yet
			if ( $this->restrictions['has_start_date'] ) {
				$expstamp = strtotime( $this->restrictions['start_date'] );

				if ( ( $expstamp > 0 ) && ( ( $expstamp-time() ) > 0 ) ) {
					$this->setError( _COUPON_ERROR_NOTSTARTED );
				}
			}

			// Check whether coupon is expired
			if ( $this->restrictions['has_expiration'] ) {
				$expstamp = strtotime( $this->restrictions['expiration'] );

				if ( ( $expstamp > 0 ) && ( ( $expstamp-time() ) < 0 ) ) {
					$this->setError( _COUPON_ERROR_EXPIRED );
					$this->coupon->deactivate();
				}
			}

			// Check for max reuse
			if ( $this->restrictions['has_max_reuse'] ) {
				if ( $this->restrictions['max_reuse'] ) {
					if ( (int) $this->coupon->usecount > (int) $this->restrictions['max_reuse'] ) {
						$this->setError( _COUPON_ERROR_MAX_REUSE );
						return;
					}
				}
			}
		} else {
			$this->setError( _COUPON_ERROR_NOTFOUND );
		}
	}

	function checkRestrictions( $metaUser, $original_amount=null, $invoiceFactory=null )
	{
		$restrictions	= $this->getRestrictionsArray();
		$permissions	= $metaUser->permissionResponse( $restrictions );

		// Check for a set usage
		if ( !empty( $this->restrictions['usage_plans_enabled'] ) && !is_null( $invoiceFactory ) ) {
			if ( $this->restrictions['usage_plans'] ) {
				if ( strpos( $this->restrictions['usage_plans'], ';' ) !== false ) {
					$plans = explode( ';', $this->restrictions['usage_plans'] );
					if ( in_array( $invoiceFactory->usage, $plans ) ) {
						$permissions['usage'] = true;
					} else {
						$permissions['usage'] = false;
					}
				} else {
					if ( (int) $invoiceFactory->usage === (int) $this->restrictions['usage_plans'] ) {
						$permissions['usage'] = true;
					} else {
						$permissions['usage'] = false;
					}
				}
			}
		}

		// Check for Trial only
		if ( $this->discount['useon_trial'] && !$this->discount['useon_full'] && !is_null( $original_amount ) ) {
			if ( !is_null( $original_amount ) ) {
				if ( is_array( $original_amount ) ) {
					if ( isset( $original_amount['amount']['amount1'] ) ) {
						$permissions['trial_only'] = true;
					} else {
						$permissions['trial_only'] = false;
					}
				}
			}
		}

		if ( count( $permissions ) ) {
			foreach ( $permissions as $name => $value ) {
				if ( !$permissions[$name] ) {
					switch ( $name ) {
						case 'mingid':			$this->setError( _COUPON_ERROR_PERMISSION );			break;
						case 'maxgid':			$this->setError( _COUPON_ERROR_PERMISSION );			break;
						case 'setgid':			$this->setError( _COUPON_ERROR_PERMISSION );			break;
						case 'usage':			$this->setError( _COUPON_ERROR_WRONG_USAGE );			break;
						case 'trial_only':		$this->setError( _COUPON_ERROR_TRIAL_ONLY );			break;
						case 'plan_previous':	$this->setError( _COUPON_ERROR_WRONG_PLAN_PREVIOUS );	break;
						case 'plan_present':	$this->setError( _COUPON_ERROR_WRONG_PLAN_PRESENT );	break;
						case 'plan_overall':	$this->setError( _COUPON_ERROR_WRONG_PLANS_OVERALL );	break;
						case 'plan_amount_min': $this->setError( _COUPON_ERROR_WRONG_PLAN_PRESENT );	break;
						case 'plan_amount_max': $this->setError( _COUPON_ERROR_WRONG_PLANS_OVERALL );	break;
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

		if ( is_array( $amount ) ) {
			if ( isset( $amount['amount1'] ) ) {
				if ( $this->discount['useon_trial'] ) {
					if ( $amount['amount1'] > 0 ) {
						$amount['amount1'] = $this->applyDiscount( $amount['amount1'] );
					}
				}
			}
			if ( isset( $amount['amount3'] ) ) {
				if ( $this->discount['useon_full'] ) {
					if ( $this->discount['useon_full_all'] ) {
						$amount['amount3']	= $this->applyDiscount($amount['amount3']);
					} else {
						if ( $amount['amount1'] > 0 ) {
							$amount['amount2']	= $this->applyDiscount($amount['amount3']);
							$amount['period2']	= $amount['period3'];
							$amount['unit2']	= $amount['unit3'];
						} else {
							$amount['amount1']	= $this->applyDiscount($amount['amount3']);
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
		return AECToolbox::correctAmount( $amount );
	}
}

class coupon extends paramDBTable
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
			$this->created_date = date( 'Y-m-d H:i:s' );
		} else {
			$this->created_date = $created;
		}
		$this->usecount = 0;
	}

	function savePOSTsettings( $post )
	{

		// Filter out fixed variables
		$fixed = array( 'active', 'name', 'desc', 'coupon_code', 'usecount', 'micro_integrations' );

		foreach ( $fixed as $varname ) {
			if ( is_array( $post[$varname] ) ) {
				$this->$varname = implode( ';', $post[$varname] );
			} else {
				$this->$varname = $post[$varname];
			}
			unset( $post[$varname] );
		}

		// Filter out params
		$fixed = array( 'amount_use', 'amount', 'amount_percent_use', 'amount_percent', 'percent_first', 'useon_trial', 'useon_full', 'useon_full_all' );

		$params = array();
		foreach ( $fixed as $varname ) {
			if ( is_array( $post[$varname] ) ) {
				$params[$varname] = implode( ';', $post[$varname] );
			} else {
				$params[$varname] = $post[$varname];
			}
			unset( $post[$varname] );
		}

		$this->saveDiscount( $params );

		// The rest of the vars are restrictions
		$restrictions = array();

		foreach ( $post as $varname => $content ) {
			if ( is_array( $content ) ) {
				$restrictions[$varname] = implode( ';', $content );
			} else {
				$restrictions[$varname] = $content;
			}
			unset( $post[$varname] );
		}

		$this->saveRestrictions( $restrictions );
	}

	function saveDiscount( $params )
	{
		global $database;

		// Correct a malformed Amount
		if ( !strlen( $params['amount'] ) ) {
			$params['amount_use'] = 0;
		} else {
			$params['amount'] = AECToolbox::correctAmount( $params['amount'] );
		}

		$this->setParams( $params, 'discount' );
	}

	function saveRestrictions( $restrictions )
	{
		$this->setParams( $restrictions, 'restrictions' );
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
			// seed random number generator
			srand( (double) microtime() * 10000 );
			$inum =	strtoupper( substr( base64_encode( md5( rand() ) ), 0, $maxlength ) );
			// check single coupons
			$query = 'SELECT count(*)'
			. ' FROM #__acctexp_coupons'
			. ' WHERE coupon_code = \'' . $inum . '\''
			;
			$database->setQuery( $query );
			$numberofrows_normal = $database->loadResult();

			// check static coupons
			$query = 'SELECT count(*)'
			. ' FROM #__acctexp_coupons_static'
			. ' WHERE coupon_code = \'' . $inum . '\''
			;
			$database->setQuery( $query );
			$numberofrows_static = $database->loadResult();

			$numberofrows = $numberofrows_normal + $numberofrows_static;
		}
		return $inum;
	}
}

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

		$query = 'SELECT id'
		. ' FROM #__acctexp_usertokens'
		. ' WHERE userid = \'' . (int) $userid . '\''
		. ' AND token_id = \'' . $token . '\''
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
		global $database, $mosConfig_offset_user;

		$now = time() + $mosConfig_offset_user*3600;

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
?>