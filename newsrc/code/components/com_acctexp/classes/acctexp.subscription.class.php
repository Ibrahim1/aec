<?php
/**
 * @version $Id: acctexp.subscription.class.php
 * @package AEC - Account Control Expiration - Membership Manager
 * @subpackage Core Class
 * @copyright 2006-2013 Copyright (C) David Deutsch
 * @author David Deutsch <skore@valanx.org> & Team AEC - http://www.valanx.org
 * @license GNU/GPL v.3 http://www.gnu.org/licenses/gpl.html or, at your option, any later version
 */

// Dont allow direct linking
defined('_JEXEC') or die( 'Direct Access to this location is not allowed.' );

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
	/** @var string */
	var $params 			= null;
	/** @var string */
	var $customparams		= null;

	function Subscription()
	{
		parent::__construct( '#__acctexp_subscr', 'id' );
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

	function loadUserid( $userid )
	{
		$this->load( $this->getSubscriptionID( $userid ) );
	}

	function getSubscriptionID( $userid, $usage=null, $primary=1, $similar=false, $bias=null )
	{
		$query = 'SELECT `id`'
				. ' FROM #__acctexp_subscr'
				. ' WHERE `userid` = \'' . $userid . '\''
				;

		if ( !empty( $usage ) ) {
			$plan = new SubscriptionPlan();
			$plan->load( $usage );

			$allplans = array( $usage, $plan->getSimilarPlans() );

			if ( count( $allplans ) > 1 ) {
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

		$this->_db->setQuery( $query );

		if ( !empty( $bias ) ) {
			$subscriptionids = xJ::getDBArray( $this->_db );

			if ( in_array( $bias, $subscriptionids ) ) {
				$subscriptionid = $bias;
			}
		} else {
			$subscriptionid = $this->_db->loadResult();
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
		$query = 'UPDATE #__acctexp_subscr'
				. ' SET `primary` = \'0\''
				. ' WHERE `userid` = \'' . $this->userid . '\''
				;
		$this->_db->setQuery( $query );
		$this->_db->query();

		$this->primary = 1;
		$this->storeload();
	}

	function manualVerify()
	{
		if ( $this->isExpired() ) {
			aecSelfRedirect( 'expired', array('userid'=>((int) $this->userid)) );
		} else {
			return true;
		}
	}

	function createNew( $userid, $processor, $pending, $primary=1, $plan=null )
	{
		if ( !$userid ) {
			return false;
		}

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

	function isStatus( $status )
	{
		return ( strcmp( $this->status, ucfirst($status) ) === 0 );
	}

	function isExcluded() { return $this->isStatus('Excluded'); }
	function isTrial() { return $this->isStatus('Trial'); }
	function isActive() { return $this->isStatus('Active'); }
	function isPending() { return $this->isStatus('Pending'); }
	function isHold() { return $this->isStatus('Hold'); }
	function isClosed() { return $this->isStatus('Closed'); }
	function isCancelled() { return $this->isStatus('Cancelled'); }

	function isExpired( $offset=false )
	{
		if ( $this->isExcluded() ) {
			return false;
		} elseif ( $this->isStatus('Expired') ) {
			return true;
		}

		global $aecConfig;

		if ( !$this->isLifetime() ) {
			$expiration = strtotime( $this->expiration );

			if ( $offset ) {
				$expstamp = strtotime( ( '-' . $offset . ' days' ), $expiration );
			} else {
				$expstamp = strtotime( ( '+' . $aecConfig->cfg['expiration_cushion'] . ' hours' ), $expiration );
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

	function hasExpiration()
	{
		if ( empty( $this->expiration )
			|| ( $this->expiration === '9999-12-31 00:00:00' )
			|| ( $this->expiration === '0000-00-00 00:00:00' ) ) {
			return false;
		} else {
			return true;
		}
	}

	function isLifetime()
	{
		if ( !$this->hasExpiration() || $this->lifetime ) {
			return true;
		} else {
			return false;
		}
	}

	function isPrimary()
	{
		return $this->primary;
	}

	function getPlan( $id=null )
	{
		$id = empty($id) ? $this->plan : $id;

		if ( !$id ) {
			return false;
		}

		$plan = new SubscriptionPlan();
		$plan->load( $id );

		return $plan;
	}

	function setExpiration( $unit, $value, $extend )
	{
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
	* @param int
	* @return Object alert
	* alert['level'] = -1 means no threshold has been reached
	* alert['level'] =  0 means subscription expired
	* alert['level'] =  1 means most critical threshold has been reached (default: 7 days or less to expire)
	* alert['level'] =  2 means second level threshold has been reached (default: 14 days or less to expire)
	* alert['daysleft'] = number of days left to expire
	*/
	function GetAlertLevel()
	{
		global $aecConfig;

		if ( $this->expiration ) {
			$alert['level']		= -1;
			$alert['daysleft']	= 0;

			$expstamp = strtotime( $this->expiration );

			// Get how many days left to expire (86400 sec = 1 day)
			$alert['daysleft']	= round( ( $expstamp - ( (int) gmdate('U') ) ) / 86400 );

			if ( $alert['daysleft'] < 0 ) {
				// Subscription already expired. Alert Level 0!
				$alert['level']			= 1;
			} else {
				// Get alert levels
				if ( $alert['daysleft'] <= $aecConfig->cfg['alertlevel1'] ) {
					// Less than $numberofdays to expire! This is a level 1
					$alert['level']		= 1;
				} elseif ( ( $alert['daysleft'] > $aecConfig->cfg['alertlevel1'] )
						&& ( $alert['daysleft'] <= $aecConfig->cfg['alertlevel2'] ) ) {
					$alert['level']		= 2;
				} elseif ( $alert['daysleft'] > $aecConfig->cfg['alertlevel2'] ) {
					// Everything is ok. Level 3 means no threshold was reached
					$alert['level']		= 3;
				}
			}
		}

		return $alert;
	}

	function verifylogin( $metaUser=false )
	{
		$verify = $this->verify();

		if ( $verify !== true ) {
			aecSelfRedirect($verify, array('userid'=>$this->userid));
		} else {
			return true;
		}
	}

	function verify( $metaUser=false )
	{
		global $aecConfig;

		$expired = $this->isExpired();

		if ( $expired ) {
			$pp = new PaymentProcessor();

			if ( $pp->loadName( $this->type ) ) {
				$expired = !$pp->validateSubscription( $this );
			}
		}

		$block = false;
		if ( $metaUser !== false ) {
			if ( !empty( $metaUser->cmsUser->block ) ) {
				$block = $metaUser->cmsUser->block;
			}
		}
		if ( ( $expired || $this->isClosed() )
			&& $aecConfig->cfg['require_subscription'] ) {
			if ( $metaUser !== false ) {
				$metaUser->setTempAuth();
			}

			if ( !$expired ) {
				$this->expire();
			}

			return 'expired';
		} elseif ( $this->isPending() ) {
			return 'pending';
		} elseif ( $this->isHold() || !empty( $block ) ) {
			return 'hold';
		}

		return true;
	}

	function expire( $overridefallback=false, $special=null )
	{
		// Users who are excluded cannot expire
		if ( $this->isExcluded() ) {
			return false;
		}

		$plan = $this->getPlan();

		if ( empty( $plan ) ) {
			return $this->setStatus( 'Expired' );
		}

		$expired = true;

		// Recognize the fallback plan, if not overridden
		if ( !empty( $plan->params['fallback'] ) && !$overridefallback ) {
			// Prevent fallback if an active parent is required, but not this or not present
			if ( !$this->isPrimary()
				&& !$plan->params['make_primary']
				&& !empty( $plan->params['fallback_req_parent'] )
				) {

				$metaUser = new metaUser( $this->userid );

				$overridefallback = $metaUser->objSubscription->isExpired();
			}

			if ( !$overridefallback ) {
				$this->applyUsage( $plan->params['fallback'], 'none', 1 );

				$expired = false;
			}
		} else {
			// Set a Trial flag if this is an expired Trial for further reference
			if ( $this->isTrial() ) {
				$this->addParams( array( 'trialflag' => 1 ) );
			} elseif ( is_array( $this->params ) ) {
				if ( in_array( 'trialflag', $this->params ) ) {
					$this->delParams( array( 'trialflag' ) );
				}
			}

			if ( !$this->isExpired() && !$this->isClosed() ) {
				$this->setStatus( 'Expired' );
			}

			$metaUser = new metaUser( $this->userid );

			if ( $metaUser->moveFocus( $this->id ) ) {
				// Call Expiration MIs
				$mih = new microIntegrationHandler();
				$mih->userPlanExpireActions( $metaUser, $plan, $special );
			}
		}

		$this->reload();

		return $expired;
	}

	function cancel( $invoice=null )
	{
		$this->setStatus( 'Cancelled' );

		$plan = $this->getPlan();

		if ( empty( $plan ) ) {
			return true;
		}

		// Since some processors do not notify each period, we need to check whether the expiration
		// lies too far in the future and cut it down to the end of the period the user has paid

		switch ( $plan->params['full_periodunit'] ) {
			case 'W': $period = $plan->params['full_period']*86400*7; break;
			case 'M': $period = $plan->params['full_period']*86400*31; break;
			case 'Y': $period = $plan->params['full_period']*86400*365; break;
			default: $period = $plan->params['full_period']*86400; break;
		}

		$newexpiration = strtotime( $this->expiration );

		// cut away blocks until we are in the past, but not too much
		while ( ($newexpiration+$period) > gmdate('U') ) {
			$newexpiration -= $period;
		}

		$this->expiration = date( 'Y-m-d H:i:s', $newexpiration );

		return true;
	}

	function hold( $invoice=null )
	{
		return $this->setStatus( 'Hold' );
	}

	function hold_settle( $invoice=null )
	{
		return $this->setStatus( 'Active' );
	}

	function setStatus( $status )
	{
		$this->status = $status;

		return $this->storeload();
	}

	function applyUsage( $usage=0, $processor='none', $silent=0, $multiplicator=1, $invoice=null )
	{
		$plan = $this->getPlan($usage);

		if ( $plan->id ) {
			return $plan->applyPlan( $this, $processor, $silent, $multiplicator, $invoice );
		} else {
			return false;
		}
	}

	function triggerPreExpiration( $metaUser, $mi_pexp )
	{
		// No actions on expired, trial or recurring
		if ( ( $this->isExpired() || $this->isTrial() ) || $this->recurring ) {
			return false;
		}

		$plan = $this->getPlan();

		$micro_integrations = $plan->getMicroIntegrations();

		$actions = 0;

		if ( empty( $micro_integrations ) ) {
			return $actions;
		}

		foreach ( $micro_integrations as $mi_id ) {
			if ( !in_array( $mi_id, $mi_pexp ) ) {
				continue;
			}

			$mi = new microIntegration();

			if ( !$mi->mi_exists( $mi_id ) ) {
				continue;
			}

			$mi->load( $mi_id );

			if ( !$mi->callIntegration() ) {
				continue;
			}

			// Do the actual pre expiration check on this MI
			if ( $this->isExpired( $mi->pre_exp_check ) ) {
				$result = $mi->pre_expiration_action( $metaUser, $plan );
				if ( $result ) {
					$actions++;
				}
			}


			unset( $mi );
		}

		return $actions;
	}

	function sendEmailRegistered( $renew, $adminonly=false, $invoice=null )
	{
		global $aecConfig;

		$app = JFactory::getApplication();

		$free = ( strcmp( strtolower( $this->type ), 'none' ) == 0 || strcmp( strtolower( $this->type ), 'free' ) == 0 );

		$urow = new cmsUser();
		$urow->load( $this->userid );

		$plan = new SubscriptionPlan();
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
			$rows = xJACLhandler::getSuperAdmins();
			$row2 			= $rows[0];

			$adminName2		= $row2->name;
			$adminEmail2	= $row2->email;
		}

		if ( !$adminonly ) {
			xJ::sendMail( $adminEmail2, $adminEmail2, $email, $subject, $message );
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
				xJ::sendMail( $adminEmail2, $adminEmail2, $adminemail, $subject2, $message2 );
			}
		}
	}

	function addCustomParams( $params )
	{
		$this->addParams( $params, 'customparams' );
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

?>
