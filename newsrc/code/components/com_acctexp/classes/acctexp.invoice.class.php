<?php
/**
 * @version $Id: acctexp.invoice.class.php
 * @package AEC - Account Control Expiration - Membership Manager
 * @subpackage Core Class
 * @copyright 2006-2015 Copyright (C) David Deutsch
 * @author David Deutsch <skore@valanx.org> & Team AEC - http://www.valanx.org
 * @license GNU/GPL v.3 http://www.gnu.org/licenses/gpl.html or, at your option, any later version
 */

// Dont allow direct linking
defined('_JEXEC') or die( 'Direct Access to this location is not allowed.' );

class aecInvoiceHelper
{
	static function UserIDfromInvoiceNumber( $invoice_number )
	{
		$db = JFactory::getDBO();

		$query = 'SELECT `userid`'
				. ' FROM #__acctexp_invoices'
				. ' WHERE `invoice_number` = \'' . $invoice_number . '\''
				;
		$db->setQuery( $query );

		return $db->loadResult();
	}

	static function InvoiceIDfromNumber( $invoice_number, $userid=0, $override_active=false )
	{
		$db = JFactory::getDBO();

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

	static function InvoiceNumberfromId( $id, $override_active = false )
	{
		$db = JFactory::getDBO();

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

	static function lastUnclearedInvoiceIDbyUserID( $userid, $excludedusage=null )
	{
		global $aecConfig;

		if ( empty( $excludedusage ) ) {
			$excludedusage = array();
		}

		$db = JFactory::getDBO();

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

					return aecInvoiceHelper::lastUnclearedInvoiceIDbyUserID( $userid, $excludedusage );
				}
			}
		}

		return false;
	}

	static function lastClearedInvoiceIDbyUserID( $userid, $planid=0 )
	{
		$db = JFactory::getDBO();

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

	static function InvoiceCountbyUserID( $userid )
	{
		$db = JFactory::getDBO();

		$query = 'SELECT count(*)'
				. ' FROM #__acctexp_invoices'
				. ' WHERE `userid` = \'' . (int) $userid . '\''
				. ' AND `active` = \'1\''
				;
		$db->setQuery( $query );

		return $db->loadResult();
	}

	static function UnpaidInvoiceCountbyUserID( $userid )
	{
		$db = JFactory::getDBO();

		$query = 'SELECT count(*)'
				. ' FROM #__acctexp_invoices'
				. ' WHERE `userid` = \'' . (int) $userid . '\''
				. ' AND `active` = \'1\''
				. ' AND `transaction_date` = \'0000-00-00 00:00:00\''
				;
		$db->setQuery( $query );

		return $db->loadResult();
	}

	static function PaidInvoiceCountbyUserID( $userid )
	{
		$db = JFactory::getDBO();

		$query = 'SELECT count(*)'
				. ' FROM #__acctexp_invoices'
				. ' WHERE `userid` = \'' . (int) $userid . '\''
				. ' AND `active` = \'1\''
				. ' AND `transaction_date` != \'0000-00-00 00:00:00\''
				;
		$db->setQuery( $query );

		return $db->loadResult();
	}

	/**
	 * @param integer $userid
	 */
	static function InvoiceNumberbyCartId( $userid, $cartid )
	{
		$db = JFactory::getDBO();

		$query = 'SELECT `invoice_number`'
				. ' FROM #__acctexp_invoices'
				. ' WHERE `userid` = \'' . $userid . '\''
				. ' AND `usage` = \'c.' . $cartid . '\''
				;

		$db->setQuery( $query );

		return $db->loadResult();
	}

	/**
	 * @param integer $userid
	 * @param integer $start
	 * @param integer $limit
	 */
	static function InvoiceIdList( $userid, $start, $limit, $sort='`transaction_date` DESC' )
	{
		$db = JFactory::getDBO();

		$query = 'SELECT `id`'
				. ' FROM #__acctexp_invoices'
				. ' WHERE `userid` = \'' . $userid . '\''
				. ' AND `active` = \'1\''
				. ' ORDER BY ' . $sort . ', `id` DESC'
				. ' LIMIT ' . $start . ',' . $limit
				;
		$db->setQuery( $query );

		return xJ::getDBArray( $db );
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

	public function __construct( $userid=null, $usage=null, $group=null, $processor=null, $invoice=null, $passthrough=null, $alert=true, $forceinternal=false )
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

	public function initUser( $userid=null, $alert=true, $forceinternal=false )
	{
		$user = JFactory::getUser();

		$this->userid = $userid;
		$this->authed = false;

		// Check whether this call is legitimate
		if ( !empty( $user->id ) && !$forceinternal ) {
			// Overwrite the given userid when user is logged in
			$this->userid = $user->id;
			$this->authed = true;

			return null;
		}

		if ( empty( $this->userid ) || $forceinternal ) {
			// setup hybrid or internal call
			$this->authed = null;

			return null;
		}

		if ( !$this->userid ) {
			return null;
		}

		if ( AECToolbox::quickVerifyUserID( $this->userid ) === true ) {
			// This user is not expired, so she could log in...
			if ( $alert ) {
				return getView( 'access_denied' );
			}
		} else {
			$db = JFactory::getDBO();

			$this->userid = xJ::escape( $db, $userid );

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

		return null;
	}

	public function initPassthrough( $passthrough )
	{
		if ( empty( $passthrough ) ) {
			$passthrough = aecPostParamClear( $_POST, '', true );

			$get = aecPostParamClear( $_GET, '', true );

			foreach ( $get as $k => $v ) {
				if ( strpos( $k, 'mi_' ) === 0 ) {
					$passthrough[$k] = $v;
				}
			}
		}

		if ( !isset( $passthrough['aec_passthrough'] ) ) {
			$this->passthrough = $passthrough;
		} else {
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
		}
	}

	public function verifyUsage()
	{
		if ( empty( $this->usage ) ) {
			return null;
		}

		$this->loadMetaUser();

		$plan = new SubscriptionPlan();
		$plan->load( $this->usage );

		$restrictions = $plan->getRestrictionsArray();

		if ( !aecRestrictionHelper::checkRestriction( $restrictions, $this->metaUser ) ) {
			return getView( 'access_denied' );
		}

		if ( !ItemGroupHandler::checkParentRestrictions( $plan, 'item', $this->metaUser ) ) {
			return getView( 'access_denied' );
		}

		return true;
	}

	public function usageStatus()
	{
		if ( $this->isCart() ) {
			$this->getCart();

			foreach ( $this->cart as $citem ) {
				if ( is_object( $citem['obj'] ) ) {
					if ( !$citem['obj']->active || !$citem['obj']->checkInventory() ) {
						return false;
					}
				}
			}

			return true;
		}

		if ( !empty( $this->usage ) ) {
			return SubscriptionPlanHandler::PlanStatus( $this->usage );
		}

		return true;
	}

	public function getPassthrough( $unset=null )
	{
		if ( empty( $this->passthrough ) ) {
			return '';
		}

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
	}

	public function puffer( $testmi=false )
	{
		$this->loadPlanObject( $testmi );

		$this->loadProcessorObject();

		$this->loadRenewStatus();

		$this->loadPaymentInfo();

		return;
	}

	function getInvoiceState( $cached=false, $cache=false )
	{
		$this->puffer();

		if ( $cached ) {
			$state = $this->invoice->loadItemState();

			if ( !empty( $state ) ) {
				$this->items = $state;

				return;
			}
		}
		$this->loadItems();

		$this->loadItemTotal();

		if ( $cache ) {
			$this->invoice->cacheItemState( $this );
		}
	}

	public function loadPlanObject( $testmi=false, $quick=false )
	{
		if ( empty( $this->usage ) && !empty( $this->passthrough['usage'] ) ) {
			$this->usage = $this->passthrough['usage'];
		}

		if ( !$this->isCart() ) {
			// get the payment plan
			$this->plan = new SubscriptionPlan();
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
				return getView( 'access_denied' );
			}
		} elseif ( !empty( $this->usage ) ) {
			if ( empty( $this->metaUser ) ) {
				return getView( 'access_denied' );
			}

			if ( !isset( $this->cartprocexceptions ) ) {
				$this->getCart();

				$this->usage = 'c.' . $this->cartobject->id;

				$procs = aecCartHelper::getCartProcessorList( $this->cartobject );

				if ( count( $procs ) > 1 ) {
					$this->recurring = null;

					$this->cartItemsPPselectForm();
				} else {
					if ( isset( $procs[0] ) ) {
						$pgroups = aecCartHelper::getCartProcessorGroups( $this->cartobject, $this->recurring );

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
				return null;
			}

			foreach ( $this->cart as $cid => $cartitem ) {
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

	public function cartItemsPPselectForm()
	{
		$pgroups = aecCartHelper::getCartProcessorGroups( $this->cartobject, $this->recurring );

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
					$recurring = true;

					if ( strpos( $pgproc, '_recurring' ) ) {
						$pgproc = str_replace( '_recurring', '', $pgproc );
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

				$groupkeys = array_keys( $pgroups );

				$mpg = array_pop( $groupkeys );
				if ( ( count( $pgroups ) > 1 ) || ( count( $pgroups[$mpg]['members'] ) > 1 ) ) {
					// We have more than one item for this processor, create temporary cart
					$tempcart = new aecCart();
					$tempcart->userid = $this->userid;

					foreach ( $pgroups as $pgr ) {
						foreach ( $pgr['members'] as $member ) {
							$tempcart->addItem( array(), $this->cartobject->content[$member]['id'] );
						}
					}

					$tempcart->storeload();

					$carthash = 'c.' . $tempcart->id;

					// Create a cart invoice
					$invoice = new Invoice();
					$invoice->create( $this->userid, $carthash, $processor_name, null, true, $this, $procrecurring );
				} else {
					// Only one item in this, create a simple invoice
					$member = $pgroups[$mpg]['members'][0];

					$invoice = new Invoice();
					$invoice->create( $this->userid, $this->cartobject->content[$member]['id'], $processor_name, null, true, $this, $procrecurring );
				}

				if ( $invoice->amount == "0.00" ) {
					$invoice->pay();
				} else {
					$finalinvoice = $invoice;
				}
			}

			$ex = array();
			$ex['head'] = "Invoice split up";
			$ex['desc'] = "The contents of your shopping cart cannot be processed in one go. This is why we have split up the invoice - you can pay for the first part right now and access the other parts as separate invoices later from your membership page.";
			$ex['rows'] = array();

			$this->raiseException( $ex );

			$this->invoice_number = $finalinvoice->invoice_number;
			$this->invoice = $finalinvoice;

			$this->touchInvoice();

			$objUsage = $this->invoice->getObjUsage();

			if ( is_a( $objUsage, 'aecCart' ) ) {
				$this->cartobject = $objUsage;

				$this->getCart();
			} else {
				$this->plan = $objUsage;
			}
		}
	}

	public function loadProcessorObject()
	{
		$this->pp					= false;

		if ( empty( $this->processor ) ) {
			return;
		}

		if ( !isset( $this->payment ) ) {
			$this->payment = new stdClass();
		}

		$this->payment->method_name = JText::_('AEC_PAYM_METHOD_NONE');
		$this->payment->currency	= '';

		if ( !isset( $this->recurring ) ) {
			$this->recurring		= 0;
		}

		switch ( $this->processor ) {
			case 'free': $this->payment->method_name = JText::_('AEC_PAYM_METHOD_FREE'); break;
			case 'none': break;
			case 'select': break;
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
					$short	= 'processor loading failure';
					$event	= 'Tried to load processor: ' . $this->processor;
					$tags	= 'processor,loading,error';
					$params = array();

					$eventlog = new eventLog();
					$eventlog->issue( $short, $tags, $event, 128, $params );
				}
				break;
		}
	}

	public function loadRenewStatus()
	{
		$this->renew = 0;

		if ( empty( $this->userid ) ) {
			return;
		}

		if ( !empty( $this->metaUser ) ) {
			$this->renew = $this->metaUser->meta->is_renewing();
		} elseif ( aecUserHelper::SubscriptionIDfromUserID( $this->userid ) ) {
			$user_subscription = new Subscription();
			$user_subscription->loadUserID( $this->userid );

			if ( ( strcmp( $user_subscription->lastpay_date, '0000-00-00 00:00:00' ) !== 0 )  ) {
				$this->renew = true;
			}
		}
	}

	public function loadPaymentInfo()
	{
		$this->payment->freetrial = 0;
		$this->payment->amount = null;
		$this->payment->method_name = null;

		if ( !empty( $this->passthrough['task'] ) ) {
			$task = $this->passthrough['task'];
		} else {
			$task = "";
		}

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
		} elseif ( !empty( $this->cartobject->id ) || ( $task == 'confirmCart' ) ) {
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

	public function raiseException( $exception )
	{
		if ( empty( $this->exceptions ) ) {
			$this->exceptions = array();
		}

		$this->exceptions[] = $exception;
	}

	public function hasExceptions()
	{
		return !empty( $this->exceptions );
	}

	public function addressExceptions()
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

	public function isActualProcessor()
	{
		return self::isActualProcessorName($this->method);
	}

	public static function isActualProcessorName( $name )
	{
		return !empty($name) && !in_array(
			$name,
			array('none', 'free', 'select')
		);
	}

	public function isCart()
	{
		return ( !empty( $this->usage ) && ( strpos( $this->usage, 'c' ) !== false ) );
	}

	public function getCart()
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

	public function loadItems( $force=false )
	{
		$this->items = new stdClass();
		$this->items->itemlist = array();

		if ( !$this->isCart() ) {
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

			$this->items->itemlist[] = array(
				'obj'		=> $this->plan,
				'name'		=> $this->plan->getProperty( 'name' ),
				'desc'		=> $this->plan->getProperty( 'desc' ),
				'quantity'	=> 1,
				'terms'		=> $terms,
				'params'	=> $params
			);

			$itemlist = array_keys( $this->items->itemlist );

			$cid = array_pop( $itemlist );

			$this->cartobject = new aecCart();
			$this->cartobject->addItem( array(), $this->plan );
		} elseif ( !empty( $this->usage ) ) {
			$this->getCart();

			foreach ( $this->cart as $cid => $citem ) {
				if ( $citem['obj'] !== false ) {
					$this->items->itemlist[$cid] = $citem;

					$terms = $citem['obj']->getTermsForUser( $this->recurring, $this->metaUser );

					$c = $citem['obj']->doPlanComparison( $this->metaUser->focusSubscription );

					// Do not allow a Trial if the user has used this or a similar plan
					if ( $this->invoice->counter ) {
						$terms->setPointer( $this->invoice->counter );
					} elseif ( $terms->hasTrial && !$c['full_comparison'] ) {
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

	public function loadItemTotal()
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

				$cost->cost['amount'] += $ccost->cost['amount'] * $citem['quantity'];
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

	public function applyCoupons()
	{
		global $aecConfig;

		if (
			empty( $aecConfig->cfg['checkout_coupons'] )
			&& empty( $aecConfig->cfg['confirmation_coupons'] )
			&& empty( $this->invoice->coupons )
		) {
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

	public function addtoCart( $usage, $returngroup=null )
	{
		global $aecConfig;

		if ( empty( $this->cartobject ) ) {
			$this->cartobject = aecCartHelper::getCartbyUserid( $this->userid );
		}

		if ( !is_array( $usage ) ) {
			$usage = array( $usage );
		}

		foreach ( $usage as $us ) {
			$this->cartobject->action( 'addItem', $us );

			$plan = new SubscriptionPlan();
			$plan->load( $us );
		}

		if ( !empty( $plan->params['addtocart_redirect'] ) ) {
			aecRedirect( $plan->params['addtocart_redirect'] );
		}

		if ( $aecConfig->cfg['additem_stayonpage'] ) {
			if ( !empty( $returngroup ) ) {
				return $this->create( 0, 0, $returngroup );
			} else {
				return $this->create();
			}
		} else {
			$this->cart();
		}

		return true;
	}

	public function updateCart( $data )
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

	public function clearCart()
	{
		if ( empty( $this->cartobject ) ) {
			$this->cartobject = aecCartHelper::getCartbyUserid( $this->userid );
		}

		$this->cartobject->action( 'clearCart' );
	}

	public function clearCartItem( $item )
	{
		if ( empty( $this->cartobject ) ) {
			$this->cartobject = aecCartHelper::getCartbyUserid( $this->userid );
		}

		$this->cartobject->action( 'updateItems', array( $item => 0 ) );
	}

	public function touchInvoice( $invoice_number=false, $storenew=false, $anystatus=false )
	{
		// Checking whether we are trying to repeat an invoice
		if ( !empty( $invoice_number ) ) {
			// Make sure the invoice really exists and that its the correct user carrying out this action
			if ( aecInvoiceHelper::InvoiceIDfromNumber( $invoice_number, $this->userid, $anystatus ) ) {
				$this->invoice_number = $invoice_number;
			}
		}

		$recurring = null;
		if ( !empty( $this->invoice_number ) ) {
			if ( $this->loadInvoice() === false ) {
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

	public function loadInvoice( $redirect=true )
	{
		if ( !isset( $this->invoice ) ) {
			$this->invoice = null;
		}

		if ( !is_object( $this->invoice ) ) {
			$this->invoice = new Invoice();
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
			return $this->create( 0, 0, $this->invoice_number );
		} elseif ( empty( $this->processor ) && ( strpos( $this->usage, 'c' ) === false ) ) {
			return $this->create( 0, $this->usage, $this->invoice_number );
		}

		return true;
	}

	public function createInvoice( $storenew=false )
	{
		$this->invoice = new Invoice();

		$id = 0;
		if ( $this->isCart() ) {
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

	public function InvoiceAddCoupon( $coupon )
	{
		if ( !empty( $coupon ) ) {
			$this->invoice->addCoupon( $coupon );

			$this->invoice->computeAmount( $this );
		}
	}

	public function storeInvoice()
	{
		$this->invoice->computeAmount( $this );

		if ( is_object( $this->pp ) ) {
			$this->pp->invoiceCreationAction( $this->invoice );
		}

		$exchange = $add = $silent = null;

		$this->triggerMIs( 'invoice_creation', $exchange, $add, $silent );
	}

	/**
	 * @param string $action
	 */
	public function triggerMIs( $action, &$exchange, &$add, &$silent )
	{
		if ( !empty( $this->cart ) && !empty( $this->cartobject ) ) {
			$this->cartobject->triggerMIs( $action, $this->metaUser, $exchange, $this->invoice, $add, $silent );
		} elseif ( !empty( $this->plan ) ) {
			$this->plan->triggerMIs( $action, $this->metaUser, $exchange, $this->invoice, $add, $silent );
		}
	}

	public function loadMetaUser( $force=false )
	{
		if ( isset( $this->metaUser ) ) {
			if ( is_object( $this->metaUser ) && !$force ) {
				return empty( $this->metaUser->_incomplete );
			}
		}

		if ( empty( $this->userid ) ) {
			// Creating a dummy user object
			$this->metaUser = new metaUser( 0 );
			return $this->metaUser->dummyUser( $this->passthrough );
		} else {
			// Loading the actual user
			$this->metaUser = new metaUser( $this->userid );

			return true;
		}
	}

	public function checkAuth()
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

		if ( $this->authed === false ) {
			if ( !$this->metaUser->getTempAuth() ) {
				if ( isset( $this->passthrough['password'] ) ) {
					if ( !$this->metaUser->setTempAuth( $this->passthrough['password'] ) ) {
						unset( $this->passthrough['password'] );
						$this->promptpassword( true );
						$return = false;
					}
				} elseif ( !empty( $this->metaUser->cmsUser->password ) ) {
					$this->promptpassword();
					$return = false;
				}
			}
		}

		return $return;
	}

	public function promptpassword( $wrong=false )
	{
		return getView( 'passwordprompt', array( 'passthrough' => $this->getPassthrough(), 'wrong' => $wrong ) );
	}

	public function create( $intro=0, $usage=0, $group=0, $processor=null, $invoice=0, $autoselect=false )
	{
		global $aecConfig;

		$register = !$this->loadMetaUser( true );

		$this->getPassthrough();

		// Reset $register if we seem to have all data
		if ( ( $register && !empty( $this->passthrough['username'] ) )
			|| !empty( $aecConfig->cfg['skip_registration'] ) ) {
			$register = 0;
		}

		if ( $intro && $this->metaUser->hasSubscription && !$aecConfig->cfg['customintro_always'] ) {
			$intro = false;
		}

		if (
			empty( $this->usage )
			&& empty( $group )
			&& !$intro
			&& !empty( $aecConfig->cfg['customintro'] )
		) {
			if ( !empty( $aecConfig->cfg['customintro_userid'] ) ) {
				aecRedirect( $aecConfig->cfg['customintro'], $this->userid, "aechidden" );
			} else {
				aecRedirect( $aecConfig->cfg['customintro'] );
			}
		}

		$this->recurring = aecGetParam( 'recurring', null );

		$planlist = new SubscriptionPlanList( $usage, $group, $this->metaUser, $this->recurring );

		$nochoice = false;

		// There is no choice if we have only one group or only one item with one payment option
		if ( count( $planlist->list ) === 1 ) {
			if ( $planlist->list[0]['type'] == 'item' ) {
				if ( count( $planlist->list[0]['gw'] ) === 1 ) {
					$nochoice = true;
				} elseif ( !empty( $processor ) ) {
					foreach ( $planlist->list[0]['gw'] as $gw ) {
						if ( $gw->processor_name == $processor ) {
							$nochoice = true;
						}
					}

					if ( $nochoice ) {
						$first = 0;
						foreach ( $planlist->list[0]['gw'] as $gwid => $gw ) {
							if ( $gw->processor_name != $processor ) {
								unset( $planlist->list[0]['gw'][$gwid] );
							} else {
								$first = $gwid;
							}
						}

						if ( !empty( $first ) ) {
							$planlist->list[0]['gw'][0] = $planlist->list[0]['gw'][$first];

							unset( $planlist->list[0]['gw'][$first] );
						}
					}
				}
			} else {
				// Jump back and use the only group we've found
				return $this->create( $intro, 0, $planlist->list[0]['id'], null, 0, true );
			}
		}

		// If we have only one processor on one plan, there is no need for a decision
		if ( $nochoice && !( $aecConfig->cfg['show_fixeddecision'] && empty( $processor ) ) ) {
			// If the user also needs to register, we need to guide him there after the selection has now been made
			if ( $register && empty( $aecConfig->cfg['skip_registration'] ) ) {
				aecRegistration::registerRedirect( $intro, $planlist->list[0] );
			} else {
				// Existing user account - so we need to move on to the confirmation page with the details
				$this->usage		= $planlist->list[0]['id'];

				if ( isset( $planlist->list[0]['gw'][0]->recurring ) ) {
					$this->recurring	= $planlist->list[0]['gw'][0]->recurring;
				} else {
					$this->recurring	= 0;
				}

				$this->processor	= $planlist->list[0]['gw'][0]->processor_name;

				if ( ( $invoice != 0 ) && !is_null( $invoice ) ) {
					$this->invoice_number	= $invoice;
				}

				return $this->confirm();
			}
		}

		if ( $group ) {
			$g = new ItemGroup();
			$g->load( $group );

			$planlist->list['group'] = ItemGroupHandler::getGroupListItem( $g );
		}

		$cart = $this->userid ? aecCartHelper::getCartidbyUserid( $this->userid ) : false;

		$selected = ( !empty( $group ) || !empty( $usage ) ) && !$autoselect;

		if ( !$selected && !empty( $planlist->list['group'] ) ) {
			unset( $planlist->list['group'] );
		}

		$passthrough = $this->getPassthrough();

		$csslist = $planlist->addButtons( $register, $passthrough );

		return getView( 'plans',
						array(
							'userid' => $this->userid,
							'list' => $planlist->list,
							'passthrough' => $passthrough,
							'register' => $register,
							'cart' => $cart,
							'selected' => $selected,
							'group' => $group,
							'csslist' => $csslist
						)
		);
	}

	public function confirm()
	{
		global $aecConfig;

		if ( empty( $this->passthrough ) ) {
			if ( !$this->checkAuth() ) {
				return false;
			}
		}

		if ( empty( $aecConfig->cfg['skip_registration'] ) ) {
			if ( !$this->reCaptchaCheck() ) {
				return false;
			}
		}

		$this->puffer();

		$this->coupons = array();
		$this->coupons['active'] = !empty( $aecConfig->cfg['confirmation_coupons'] );

		if ( empty( $this->mi_error ) ) {
			$this->mi_error = array();
		}

		if ( !empty( $this->plan ) ) {
			$this->mi_form = $this->plan->getMIforms( $this->metaUser, $this->mi_error, $this->passthrough );
		}

		$this->jsvalidation = array();
		$this->formatting = array();
		if ( !empty( $this->mi_form ) && is_array( $this->passthrough ) ) {
			$params = $this->plan->getMIformParams( $this->metaUser );

			foreach ( $params as $mik => $miv ) {
				if ( $mik == 'lists' ) {
					continue;
				} elseif ( $mik == 'validation' ) {
					if ( !empty( $miv ) ) {
						$this->jsvalidation = array_merge( $this->jsvalidation, $miv );
					}

					continue;
				} elseif ( $mik == 'formatting' ) {
					if ( !empty( $miv ) ) {
						$this->formatting = array_merge( $this->formatting, $miv );
					}

					continue;
				}

				foreach ( $this->passthrough as $pid => $pk ) {
					if ( !is_array( $pk ) ) {
						continue;
					}

					if ( !empty( $pk[0] ) ) {
						if ( ( $pk[0] == $mik ) || ( $pk[0] == $mik.'[]' ) ) {
							unset($this->passthrough[$pid]);
						}
					}
				}
			}
		}

		if ( ( $aecConfig->cfg['skip_confirmation'] && empty( $this->mi_form ) ) ) {
			$this->getPassthrough();

			return $this->save();
		}

		$this->userdetails = "";
		foreach ( array( 'name', 'username', 'email' ) as $k ) {
			if ( !empty( $this->metaUser->cmsUser->$k ) ) {
				$this->userdetails .= '<p>' . JText::_('CONFIRM_ROW_'.strtoupper($k))
									. "&nbsp;" . $this->metaUser->cmsUser->$k . '</p>';
			}
		}

		$this->processorSelect = false;
		if (
			($this->processor == 'select' && !empty($this->plan->params['processor_selectmode']) && ($this->plan->params['processor_selectmode'] == 1))
			|| (!empty($this->plan->params['processor_selectmode']) && ($this->plan->params['processor_selectmode'] == 2))
		) {
			$this->processorSelect = array();

			$processors = array_reverse( $this->plan->params['processors'] );

			foreach ( $processors as $kn => $n ) {
				$pp = new PaymentProcessor();

				if ( !$pp->loadId( $n ) ) {
					continue;
				}

				if ( !$pp->processor->active ) {
					continue;
				}

				$pp->init();
				$pp->getInfo();

				if ($this->processor == 'select') {
					$selected = $kn == 0;
				} else {
					$selected = $this->processor == $pp->info['name'];
				}

				$this->processorSelect[] = (object) array(
					'id' => $pp->processor->id,
					'handle' => $pp->info['name'],
					'name' => $pp->info['longname'],
					'selected' => $selected
				);
			}
		}

		return getView( 'confirmation', array( 'InvoiceFactory' => $this, 'passthrough' => $this->getPassthrough() ) );
	}

	public function cart()
	{
		global $aecConfig;

		$this->getCart();

		$this->coupons = array( 'active' => !empty( $aecConfig->cfg['confirmation_coupons'] ) );

		$in = aecInvoiceHelper::InvoiceNumberbyCartId( $this->userid, $this->cartobject->id );

		if ( !empty( $in ) ) {
			$this->invoice_number = $in;

			$this->touchInvoice();
		}

		return getView( 'cart', array( 'InvoiceFactory' => $this ) );
	}

	public function confirmcart( $coupon=null, $testmi=false )
	{
		$this->confirmed = 1;

		$this->loadMetaUser( false, true );

		$this->metaUser->setTempAuth();

		$this->puffer();

		$this->touchInvoice();

		if ( $this->hasExceptions() ) {
			$this->addressExceptions();
		} else {
			$this->checkout( 0, null, $coupon );
		}
	}

	public function save( $coupon=null )
	{
		global $aecConfig;

		$this->confirmed = 1;

		$this->loadPlanObject();

		$add =& $this;

		$exchange = $silent = null;

		$this->triggerMIs( 'before_invoice_confirm', $exchange, $add, $silent );

		if ( empty( $this->userid ) ) {
			if ( !empty( $aecConfig->cfg['skip_registration'] ) ) {
				if ( !$this->reCaptchaCheck() ) {
					return false;
				}
			}

			$dbtmpl = new configTemplate();
			$dbtmpl->loadDefault();
			if ( !empty( $dbtmpl->settings['tos'] ) ) {
				$tos = aecGetParam( 'tos', 0, true, array( 'bool' ) );

				if ( !$tos ) {
					$this->confirmed = 0;

					return $this->confirm();
				}
			}

			if ( !empty( $this->plan ) ) {
				if ( !isset( $this->plan->params['override_activation'] ) ) {
					$this->plan->params['override_activation'] = false;
				}

				if ( !isset( $this->plan->params['override_regmail'] ) ) {
					$this->plan->params['override_regmail'] = false;
				}

				$this->userid = aecRegistration::saveUserRegistration( $this->passthrough, false, $this->plan->params['override_activation'], $this->plan->params['override_regmail'] );
			} else {
				$this->userid = aecRegistration::saveUserRegistration( $this->passthrough );
			}

			if ( !$this->userid ) {
				$errors = JError::getErrors();

				aecErrorAlert( JText::_( 'COM_USERS_REGISTRATION_SAVE_FAILED' ) );
			}
		}

		$this->loadMetaUser( true );
		$this->metaUser->setTempAuth();

		if ( !empty( $this->plan ) ) {
			if ( $this->verifyMIForms( $this->plan ) === false ) {
				$this->confirmed = 0;

				return $this->confirm();
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

				return $this->confirm();
			}
		}

		return $this->checkout( 0, null, $coupon );
	}

	public function verifyMIForms( $plan, $mi_form=null, $prefix="" )
	{
		if ( empty( $plan ) ) {
			return null;
		} elseif ( !is_object( $plan ) ) {
			return null;
		}

		if ( empty( $mi_form ) ) {
			$mi_form = $plan->getMIformParams( $this->metaUser );
		}

		if ( empty( $mi_form ) ) {
			return null;
		}

		$params = array();
		foreach ( $mi_form as $key => $value ) {
			if ( strpos( $key, '[]' ) ) {
				$key = str_replace( '[]', '', $key );
			}

			if ( !empty( $value[5] ) ) {
				if ( strpos( $value[5], '[]' ) ) {
					$key = str_replace( '[]', '', $value[5] );
				}
			} elseif ( !empty( $value[1] ) ) {
				if ( strpos( $value[1], '[]' ) ) {
					$key = str_replace( '[]', '', $value[1] );
				}
			}

			$post_value = aecGetParam( $prefix.$key, '__DEL' );

			if ( !empty( $prefix ) ) {
				if ( strpos( $key, $prefix ) !== false ) {
					$key = str_replace( $prefix, '', $key );
				}
			}

			if ( $post_value !== '__DEL' ) {
				$k = explode( '_', $key, 3 );

				if ( !isset( $params[$k[1]] ) ) {
					$params[$k[1]] = array();
				}

				$params[$k[1]][$k[2]] = $post_value;
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

		return true;
	}

	public function checkout( $repeat=0, $error=null, $coupon=null )
	{
		global $aecConfig;

		if ( !$this->checkAuth() ) {
			return false;
		}

		$this->puffer();

		$this->touchInvoice( false, true );

		if ( $this->invoice->method != $this->processor ) {
			$this->invoice->method = $this->processor;

			$this->invoice->storeload();
		}

		// Delete TempToken - the data is now safe with the invoice
		$temptoken = new aecTempToken();
		$temptoken->getComposite();

		if ( $temptoken->id ) {
			$temptoken->delete();
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

		$recurring = false;

		if ( !in_array( strtolower( $this->processor ), $exceptproc ) ) {
			if ( is_object( $this->pp ) ) {
				if ( isset( $this->invoice->params['userselect_recurring'] ) ) {
					$recurring_choice = $this->invoice->params['userselect_recurring'];
				} else {
					$recurring_choice = null;
				}

				$recurring = $this->pp->is_recurring( $recurring_choice );
			}
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
						return $this->thanks( false, true );
					}
				}
			}

			return getView( 'access_denied' );
		} elseif ( in_array( strtolower( $this->processor ), $exceptproc ) ) {
			if ( !empty( $this->invoice->made_free ) ) {
				// mark paid
				if ( $this->invoice->pay() !== false ) {
					return $this->thanks( false, true );
				}
			}

			return getView( 'access_denied' );
		} elseif ( strcmp( strtolower( $this->processor ), 'error' ) === 0 ) {
			// Nope, won't work buddy
			return getView( 'access_denied' );
		}

		if ( !empty($this->pp) ) {
			if ( $this->pp->requireSSLcheckout() && empty( $_SERVER['HTTPS'] ) && !$aecConfig->cfg['override_reqssl'] ) {
				aecRedirect( AECToolbox::deadsureURL( "index.php?option=com_acctexp&task=repeatPayment&invoice=" . $this->invoice->invoice_number . "&first=" . ( $repeat ? 0 : 1 ) . '&'. xJ::token() .'=1', true, true ) );
				exit();
			}
		}

		$this->loadItems();

		$this->loadItemTotal();

		$exchange = $silent = null;

		$this->triggerMIs( 'invoice_items_checkout', $exchange, $this->items, $silent );

		// Either this is fully free, or the next term is free and this is non recurring
		if ( !empty( $this->items->grand_total ) && !$recurring ) {
			if ( $this->items->grand_total->isFree() && !$recurring ) {
				$this->invoice->pay();

				return $this->thanks( false, true );
			}
		}

		return $this->InvoiceToCheckout( $repeat, $error );
	}

	public function InvoiceToCheckout( $repeat=0, $error=null, $data=null )
	{
		global $aecConfig;

		if ( $this->hasExceptions() ) {
			return $this->addressExceptions();
		}

		if ( !empty( $data ) ) {
			$int_var = $data;
		} else {
			$int_var = $this->invoice->getWorkingData( $this );
		}

		// Assemble Checkout Response
		if ( !empty( $int_var['objUsage'] ) ) {
			if ( is_a( $int_var['objUsage'], 'SubscriptionPlan' ) ) {
				$int_var['var'] = $this->pp->checkoutAction( $int_var, $this->metaUser, $int_var['objUsage'], $this );
			} else {
				$int_var['var'] = $this->pp->checkoutAction( $int_var, $this->metaUser, null, $this, $int_var['objUsage'] );
			}
		} else {
			$int_var['var'] = $this->pp->checkoutAction( $int_var, $this->metaUser, null, $this, $int_var['objUsage'] );
		}

		$int_var['params'] = $this->pp->getParamsHTML( $int_var['params'], $this->pp->getParams( $int_var['params'] ) );

		$this->invoice->formatInvoiceNumber();

		$introtext = JText::_('CHECKOUT_INFO'. ( $repeat ? '_REPEAT' : '' ));

		$this->checkout = array(
			'checkout_title'            => JText::_('CHECKOUT_TITLE'),
			'introtext'                 => sprintf( $introtext, $this->invoice->invoice_number ),
			'enable_coupons'            => !empty( $aecConfig->cfg['checkout_coupons'] ),
			'customtext_checkout_table' => JText::_('CHECKOUT_TITLE')
		);

		$this->display_error = $error;

		if ( is_object( $this->pp ) ) {
			$this->pp->modifyCheckout( $int_var, $this );
		}

		$exchange = $silent = null;

		$this->triggerMIs( '_checkout_form', $exchange, $int_var, $silent );

		return getView( 'checkout', array( 'var' => $int_var['var'], 'params' => $int_var['params'], 'InvoiceFactory' => $this ) );
	}

	public function getObjUsage()
	{
		if ( isset( $this->invoice->usage ) ) {
			return $this->invoice->getObjUsage();
		}

		if ( !empty( $this->usage ) ) {
			$u = explode( '.', $this->usage );

			switch ( strtolower( $u[0] ) ) {
				case 'c':
				case 'cart':
					$objUsage = new aecCart();
					$objUsage->load( $u[1] );
					break;
				case 'p':
				case 'plan':
				default:
					if ( !isset( $u[1] ) ) {
						$u[1] = $u[0];
					}

					$objUsage = new SubscriptionPlan();
					$objUsage->load( $u[1] );
					break;
			}

			return $objUsage;
		}

		return null;
	}

	public function internalcheckout()
	{
		$this->metaUser = new metaUser( $this->userid );

		$this->touchInvoice();

		$this->getInvoiceState( false, true );

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

			if ( isset( $response['errormsg'] ) ) {
				$error = $response['errormsg'];
			} else {
				$error = $response['error'];
			}

			$this->checkout( true, $error );
		} elseif ( isset( $response['doublecheckout'] ) ) {
			$exchange = $silent = null;

			$this->triggerMIs( 'invoice_items_checkout', $exchange, $this->items, $silent );

			$this->InvoiceToCheckout( true, null, $var );
		} else {
			$this->thanks();
		}
	}

	public function processorResponse( $response )
	{
		$this->touchInvoice();

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

		$this->getInvoiceState(true);

		$response = $this->invoice->processorResponse( $this, $response, '', false );

		if ( isset( $response['error'] ) ) {
			if ( !empty( $this->pp->info['custom_notify_trail'] ) ) {
				$this->pp->notify_trail( $this, $response );
			} else {
				unset( $this->cart );
				unset( $this->cartobject );
				unset( $this->items );
				unset( $this->pp );

				$this->checkout( true, $response['error'] );
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
				$this->thanks();
			} elseif ( !empty( $this->pp->info['custom_notify_trail'] ) ) {
				$this->pp->notify_trail( $this, $response );
			} else {
				if ( !headers_sent() ) {
					header("HTTP/1.0 200 OK");
				}

				exit;
			}
		}
	}

	public function planprocessoraction( $action, $subscr=null )
	{
		$this->loadMetaUser();

		$this->invoice = new Invoice();

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

		$this->getInvoiceState(true);

		if ( !$this->pp->id ) {
			return getView( 'access_denied' );
		}

		$this->pp->fullInit();

		$usage = $this->getObjUsage();

		if ( is_a( $usage, 'aecCart' ) ) {
			foreach ( $usage->content as $c ) {
				$new_plan = new SubscriptionPlan();
				$new_plan->load( $c['id'] );

				$this->pp->exchangeSettingsByPlan( $new_plan );
			}
		} elseif ( is_a( $usage, 'SubscriptionPlan' ) ) {
			$this->pp->exchangeSettingsByPlan( $usage );
		} else {
			return getView( 'access_denied' );
		}

		$response = $this->pp->customAction( $action, $this->invoice, $this->metaUser );

		$response = $this->invoice->processorResponse( $this, $response, '', true );

		if ( isset( $response['cancel'] ) ) {
			return getView( 'cancel' );
		} else {
			return true;
		}
	}

	public function invoiceprocessoraction( $action, $invoiceNum=null )
	{
		$this->loadMetaUser();

		$this->getInvoiceState(true);

		$var = $this->invoice->getWorkingData( $this );

		$response = $this->pp->customAction( $action, $this->invoice, $this->metaUser, $var );

		if ( isset( $response['InvoiceToCheckout'] ) ) {
			return $this->InvoiceToCheckout( true, false );
		} else {
			$response = $this->invoice->processorResponse( $this, $response, '', true );

			if ( isset( $response['cancel'] ) ) {
				return getView( 'cancel' );
			}
		}

		return null;
	}

	public function invoiceprint( $invoice_number, $standalone=true, $extradata=null, $forcecleared=false, $forcecounter=null )
	{
		$this->loadMetaUser();

		$this->touchInvoice( $invoice_number, false, true );

		if ( $this->invoice->invoice_number != $invoice_number ) {
			return getView( 'access_denied' );
		}

		$this->getInvoiceState(true);

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

		$data = AECToolbox::rewriteEngineRQ( $data, $this );

		return getView( 'invoice', array( 'data' => $data, 'standalone' => $standalone, 'InvoiceFactory' => $this ) );
	}

	public function thanks( $renew=false, $free=false )
	{
		global $aecConfig;

		if ( $this->userid ) {
			$this->loadMetaUser();

			if ( isset( $this->renew ) ) {
				$renew = $this->renew;
			} else {
				$renew = $this->metaUser->is_renewing();
			}
		}

		$url = 'index.php?option=com_acctexp&task=thanks&userid=' . ((int) $this->userid) . '&free=' . $free . '&renew=' . $renew;

		if ( !empty( $this->plan ) ) {
			aecRedirect( $url. '&usage=' . $this->plan->id );
		} else {
			aecRedirect( $url );
		}
	}

	public function error( $objUser, $invoice, $error )
	{
		$document= JFactory::getDocument();

		$document->setTitle( html_entity_decode( JText::_('CHECKOUT_ERROR_TITLE'), ENT_COMPAT, 'UTF-8' ) );

		getView( 'error', array( 'objUser' => $objUser, 'invoice' => $invoice, 'error' => $error ) );
	}

	public function reCaptchaCheck()
	{
		global $aecConfig;

		if ( $aecConfig->cfg['use_recaptcha'] && !empty( $aecConfig->cfg['recaptcha_privatekey'] ) && empty( $this->userid ) ) {
			// require the recaptcha library
			require_once( JPATH_SITE . '/components/com_acctexp/lib/recaptcha/recaptchalib.php' );

			if ( !isset( $_POST["recaptcha_challenge_field"] ) || !isset( $_POST["recaptcha_response_field"] ) ) {
				echo "<script> alert('The reCAPTCHA was not correct. Please try again.'); window.history.go(-1);</script>\n";

				return false;
			}

			// finally chack with reCAPTCHA if the entry was correct
			$resp = recaptcha_check_answer ( $aecConfig->cfg['recaptcha_privatekey'], $_SERVER["REMOTE_ADDR"], $_POST["recaptcha_challenge_field"], $_POST["recaptcha_response_field"] );

			// if the response is INvalid, then go back one page, and try again. Give a nice message
			if (!$resp->is_valid) {
				echo "<script> alert('The reCAPTCHA was not correct. Please try again.'); window.history.go(-1);</script>\n";

				return false;
			}
		}

		return true;
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
	/** @var string */
	var $coupons 			= null;
	/** @var string */
	var $transactions		= null;
	/** @var string */
	var $params 			= null;
	/** @var string */
	var $conditions			= null;

	public function __construct()
	{
		parent::__construct( '#__acctexp_invoices', 'id' );
	}

	public function declareParamFields()
	{
		return array( 'coupons', 'transactions', 'params', 'conditions' );
	}

	public function load( $id=null, $reset=true )
	{
		parent::load( $id );

		if ( empty( $this->counter ) && ( $this->transaction_date != '0000-00-00 00:00:00' ) && !is_null( $this->transaction_date ) ) {
			$this->counter = 1;
		}
	}

	public function loadLatest( $userid, $plan, $subscr=null )
	{
		if ( !empty( $subscr ) ) {
			$this->loadbySubscriptionId( $subscr, $userid );
		}

		if ( empty( $this->id ) ) {
			$this->load( aecInvoiceHelper::lastClearedInvoiceIDbyUserID( $userid, $plan ) );
		}

		if ( empty( $this->id ) ) {
			$this->load( aecInvoiceHelper::lastUnclearedInvoiceIDbyUserID( $userid, $plan ) );
		}
	}

	public function loadInvoiceNumber( $invoiceNum )
	{
		$query = 'SELECT id'
		. ' FROM #__acctexp_invoices'
		. ' WHERE invoice_number = \'' . $invoiceNum . '\''
		. ' OR secondary_ident = \'' . $invoiceNum . '\''
		;
		$this->_db->setQuery( $query );
		$this->load($this->_db->loadResult());
	}

	public function formatInvoiceNumber( $invoice=null, $nostore=false )
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

		if ( !empty( $invoice ) ) {
			return $invoice_number;
		}

		if ( $aecConfig->cfg['invoicenum_doformat'] && empty( $this->invoice_number_format ) && !empty( $invoice_number ) && !$nostore ) {
			if ( $invoice_number != "JSON PARSE ERROR - Malformed String!" ) {
				$this->invoice_number_format = $invoice_number;
				$this->storeload();
			}
		}

		$this->invoice_number = $invoice_number;

		return true;
	}

	public function deformatInvoiceNumber()
	{
		global $aecConfig;

		$query = 'SELECT invoice_number'
		. ' FROM #__acctexp_invoices'
		. ' WHERE id = \'' . xJ::escape( $this->_db, $this->id ) . '\''
		. ' OR secondary_ident = \'' . xJ::escape( $this->_db, $this->invoice_number ) . '\''
		;
		$this->_db->setQuery( $query );

		$this->invoice_number = $this->_db->loadResult();
	}

	public function loadbySubscriptionId( $subscrid, $userid=null )
	{
		$query = 'SELECT `id`'
				. ' FROM #__acctexp_invoices'
				. ' WHERE `subscr_id` = \'' . $subscrid . '\''
				;

		if ( !empty( $userid ) ) {
			$query .= ' AND `userid` = \'' . $userid . '\'';
		}

		$query .= ' ORDER BY `transaction_date` DESC';

		$this->_db->setQuery( $query );
		$this->load( $this->_db->loadResult() );
	}

	public function isRecurring()
	{
		if ( !empty( $this->subscr_id ) ) {


			$query = 'SELECT `recurring`'
					. ' FROM #__acctexp_subscr'
					. ' WHERE `id` = \'' . $this->subscr_id . '\''
					;

			$this->_db->setQuery( $query );
			return $this->_db->loadResult();
		}

		if ( isset( $this->params['userselect_recurring'] ) ) {
			$recurring_choice = $this->params['userselect_recurring'];
		} else {
			$recurring_choice = null;
		}

		if ( InvoiceFactory::isActualProcessorName($this->method) ) {
			$pp = new PaymentProcessor();
			if ( $pp->loadName( $this->method ) ) {
				$pp->fullInit();

				return $pp->is_recurring( $recurring_choice );
			}
		}

		return false;
	}

	function cacheItemState( $InvoiceFactory )
	{
		$this->params['items'] = $InvoiceFactory->items;

		$this->storeload();
	}

	function loadItemState()
	{
		if ( !empty( $this->params['items'] ) ) {
			return $this->params['items'];
		}

		return false;
	}

	public function computeAmount( $InvoiceFactory=null, $save=true, $recurring_choice=null )
	{
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

		if ( empty( $this->usage ) ) {
			return null;
		}

		$recurring = 0;

		if ( InvoiceFactory::isActualProcessorName($this->method) ) {
			if ( empty( $InvoiceFactory->pp ) ) {
				$pp = new PaymentProcessor();
				if ( !$pp->loadName( $this->method ) ) {
					$short	= 'processor loading failure';
					$event	= 'When computing invoice amount, tried to load processor: ' . $this->method;
					$tags	= 'processor,loading,error';
					$params = array();

					$eventlog = new eventLog();
					$eventlog->issue( $short, $tags, $event, 128, $params );

					return;
				}

				$pp->fullInit();
			} else {
				$pp = $InvoiceFactory->pp;
			}

			if ( $pp->is_recurring( $recurring_choice ) ) {
				$recurring = $pp->is_recurring( $recurring_choice );
			}

			if ( empty( $this->currency ) ) {
				$this->currency = isset( $pp->settings['currency'] ) ? $pp->settings['currency'] : '';
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

					if ( ( $this->currency != $pp->settings['currency'] ) && !empty( $pp->settings['currency'] ) ) {
						$this->currency = $pp->settings['currency'];
					}

					if ( $pp->is_recurring( $recurring_choice ) ) {
						$recurring = $pp->is_recurring( $recurring_choice );
					} else {
						$recurring = 0;
					}
				}

				$terms = $plan->getTermsForUser( $recurring, $metaUser );

				$terms->incrementPointer( $this->counter );

				if ( $this->coupons ) {
					$item = array( 'item' => array( 'obj' => $plan ), 'terms' => $terms );

					$cpsh = new couponsHandler( $metaUser, $InvoiceFactory, $this->coupons );

					$item = $cpsh->applyAllToItems( 0, $item );

					$terms = $item['terms'];

					// Coupons might have changed the terms - reset pointer
					$terms->setPointer( $this->counter );
				}

				$allfree = $terms->checkFree();

				if ( is_object( $terms->nextterm ) ) {
					$this->amount = $terms->nextterm->renderTotal();
				} else {
					$this->amount = '0.00';
				}
			break;
		}

		// TODO: This is just a bandaid. We really need to move stuff from the factory over to the invoice class
		if ( !empty( $InvoiceFactory->items->grand_total->cost['amount'] ) ) {
			$this->amount = $InvoiceFactory->items->grand_total->cost['amount'];
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

				$eventlog = new eventLog();
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

	/**
	 * @param InvoiceFactory $InvoiceFactory
	 */
	public function create( $userid, $usage, $processor, $second_ident=null, $store=true, $InvoiceFactory=null, $recurring_choice=null )
	{
		if ( !$userid ) {
			return false;
		}

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

	public function generateInvoiceNumber( $maxlength = 16 )
	{
		$numberofrows = 1;
		while ( $numberofrows ) {
			$inum =	'I' . substr( base64_encode( md5( rand() ) ), 0, $maxlength );
			// Check if already exists
			$query = 'SELECT count(*)'
					. ' FROM #__acctexp_invoices'
					. ' WHERE `invoice_number` = \'' . $inum . '\''
					. ' OR `secondary_ident` = \'' . $inum . '\''
					;
			$this->_db->setQuery( $query );
			$numberofrows = $this->_db->loadResult();
		}

		return $inum;
	}

	/**
	 * @param InvoiceFactory $InvoiceFactory
	 */
	public function processorResponse( $InvoiceFactory, $response, $resp='', $altvalidation=false )
	{
		global $aecConfig;

		if ( !is_array( $response ) ) {
			$response = array( 'original_response' => $response );
		}

		$this->computeAmount( $InvoiceFactory );

		$objUsage = $this->getObjUsage();

		if ( is_a( $objUsage, 'SubscriptionPlan' ) ) {
			$plan = $objUsage;
		} else {
			$plan = $objUsage->getTopPlan();
		}

		$response['planparams'] = $plan->getProcessorParameters( $InvoiceFactory->pp );

		$post = aecPostParamClear( $_POST );

		$response['userid'] = $this->userid;

		$InvoiceFactory->pp->exchangeSettingsByPlan( $plan, $plan->params );

		if ( $altvalidation ) {
			$response = $InvoiceFactory->pp->instantvalidateNotification( $response, $post, $this );
		} else {
			$response = $InvoiceFactory->pp->validateNotification( $response, $post, $this );
		}

		if ( !empty( $aecConfig->cfg['invoice_cushion'] )
			&& ( $this->transaction_date !== '0000-00-00 00:00:00' ) ) {

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

					$eventlog = new eventLog();
					$eventlog->issue( $short, $tags, $event, $level, $params );

					return $response;
				}
			}
		}

		if ( isset( $response['userid'] ) ) {
			unset( $response['userid'] );
		}

		if ( isset( $response['planparams'] ) ) {
			unset( $response['planparams'] );
		}

		if ( isset( $response['secondary_ident'] ) ) {
			$this->secondary_ident = $response['secondary_ident'];
			$this->storeload();

			unset( $response['secondary_ident'] );
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
		$history = new logHistory();
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
					// In some cases, a straight up != can still come out as an error, so forcing INT
					$ampaid = (int) ( $response['amount_paid'] * 100 );
					$amasked = (int) ( $this->amount * 100 );

					if ( $ampaid != $amasked ) {
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

				$event	.= 'Error:' . $response['errormsg'];
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
			$objUsage = new SubscriptionPlan();
			$objUsage->load( $this->usage );

			$exchange = $silent = null;

			$objUsage->triggerMIs( $mi_event, $metaUser, $exchange, $this, $response, $silent );
		}

		if ( isset( $response['explanation'] ) ) {
			$event .= " (" . $response['explanation'] . ")";
		}

		$eventlog = new eventLog();
		$eventlog->issue( $short, $tags, $event, $level, $params, $forcedisplay );

		if ( !empty( $notificationerror ) ) {
			$InvoiceFactory->pp->notificationError( $response, $notificationerror );
		} else {
			$InvoiceFactory->pp->notificationSuccess( $response );
		}

		return $response;
	}

	public function pay( $multiplicator=1, $noclear=false )
	{
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
						$this->params['cart'] = new aecCart();
						$this->params['cart']->load( $usage[1] );

						if ( !empty( $this->params['cart']->content ) ) {
							foreach ( $this->params['cart']->content as $c ) {
								$new_plan = new SubscriptionPlan();
								$new_plan->load( $c['id'] );

								for ( $i=0; $i<$c['quantity']; $i++ ) {
									$plans[] = $new_plan;
								}
							}
						}

						$this->params['cart']->clear();

						$this->storeload();

						// Load and delete original entry
						$cart = new aecCart();
						$cart->load( $usage[1] );
						if ( $cart->id ) {
							$cart->delete();
						}
					} else {
						foreach ( $this->params['cart']->content as $c ) {
							$new_plan = new SubscriptionPlan();
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
					$new_plan = new SubscriptionPlan();
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

						if ( $metaUser->userid !== $targetUser->userid ) {
							$targetUser->meta->setMIParams( $mi_id, $plan, $content );
						}
					}
				}

				$metaUser->meta->storeload();

				if ( $metaUser->userid !== $targetUser->userid ) {
					$targetUser->meta->storeload();
				}
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
				$start_position = strpos( $this->conditions, '<registration_id>' ) + strlen( '<registration_id>' );
				$end_position = strpos( $this->conditions, '</registration_id>' );

				$micro_integration['name'] = 'mi_attendevents';
				$micro_integration['parameters'] = array( 'registration_id' => substr( $this->conditions, $start_position, $end_position - $start_position ) );

				$micro_integrations = array();
				$micro_integrations[] = $micro_integration;
			}
		}

		// Reload the meta- & targetUser since they have been updated
		if ( !empty( $this->userid ) ) {
			$metaUser = new metaUser( $this->userid );
		}

		if ( !empty( $this->params['target_user'] ) ) {
			$targetUser = new metaUser( $this->params['target_user'] );
		} else {
			$targetUser =& $metaUser;
		}

		if ( !empty( $micro_integrations ) ) {
			if ( is_array( $micro_integrations ) ) {
				foreach ( $micro_integrations as $micro_int ) {
					$mi = new microIntegration();

					if ( isset( $micro_integration['parameters'] ) ) {
						$exchange = $micro_integration['parameters'];
					} else {
						$exchange = null;
					}

					if ( isset( $micro_int['name'] ) ) {
						if ( $mi->callDry( $micro_int['name'] ) ) {
							if ( is_object( $targetUser ) ) {
								$mi->action( $targetUser, $exchange, $this, $new_plan );
							} else {
								$mi->action( $targetUser, $exchange, $this, $new_plan );
							}
						}
					} elseif ( isset( $micro_int['id'] ) ) {
						if ( $mi->mi_exists( $micro_int['id'] ) ) {
							$mi->load( $micro_int['id'] );
							if ( $mi->callIntegration() ) {
								if ( is_object( $metaUser ) ) {
									$mi->action( $targetUser, $exchange, $this, $new_plan );
								} else {
									$mi->action( $targetUser, $exchange, $this, $new_plan );
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

				$cph->triggerMIs( $targetUser, $this, $plans[0] );
			}
		}

		// We need to at least warn the admin if there is an invoice with nothing to do
		if ( empty( $this->usage ) && empty( $this->conditions ) && empty( $this->coupons ) ) {
			$short	= 'Nothing to do';
			$event	= JText::_('AEC_MSG_PROC_INVOICE_ACTION_EV_VALID_APPFAIL');
			$tags	= 'invoice,application,payment,action_failed';
			$params = array( 'invoice_number' => $this->invoice_number );

			$eventlog = new eventLog();
			$eventlog->issue( $short, $tags, $event, 32, $params );
		}

		if ( !$noclear ) {
			$this->setTransactionDate();
		}

		return true;
	}

	public function cancel()
	{
		if ( $this->fixed ) {
			return false;
		}

		if ( !empty( $this->coupons ) ) {
			foreach ( $this->coupons as $cid ) {
				$this->removeCoupon( $cid );
			}

			$this->coupons = array();
		}

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
					$this->_db->setQuery( $query );
					$this->_db->query();
					break;
			}
		}

		return true;
	}

	public function setTransactionDate()
	{
		global $aecConfig;

		$tdate				= strtotime( $this->transaction_date );
		$time_passed		= ( ( ( (int) gmdate('U') ) ) - $tdate ) / 3600;
		$transaction_date	= date( 'Y-m-d H:i:s', ( (int) gmdate('U') ) );

		if ( !empty( $aecConfig->cfg['invoicecushion'] ) ) {
			$cushion = $aecConfig->cfg['invoicecushion']*60;
		} else {
			$cushion = 0;
		}

		if ( $time_passed <= $cushion ) {
			return;
		}

		if ( !empty( $aecConfig->cfg['invoice_spawn_new'] ) && $this->counter ) {
			$invoice = clone( $this );
			$invoice->id = 0;
			$invoice->counter = 0;

			$invoice->invoice_number = $invoice->generateInvoiceNumber();
			$invoice->created_date		= $transaction_date;
			$invoice->transaction_date	= $transaction_date;

			$invoice->addParams( array( 'spawned_from_invoice' => $this->invoice_number ) );
		} else {
			$invoice =& $this;
		}

		$invoice->counter += 1;
		$invoice->transaction_date	= $transaction_date;

		$c = new stdClass();

		$c->timestamp	= $transaction_date;
		$c->amount		= $invoice->amount;
		$c->currency	= $invoice->currency;
		$c->processor	= $invoice->method;

		$invoice->transactions[] = $c;

		$invoice->storeload();
	}

	public function getWorkingData( $InvoiceFactory )
	{
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
					$itemlist = array_keys( $InvoiceFactory->items->itemlist );

					$max = array_pop( $itemlist );

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

	public function getObjUsage()
	{
		$usage = null;
		if ( !empty( $this->usage ) ) {
			$usage = $this->usage;
		}

		if ( empty( $usage ) ) {
			return null;
		}

		$u = explode( '.', $usage );

		switch ( strtolower( $u[0] ) ) {
			case 'c':
			case 'cart':
				if ( isset( $this->params['cart'] ) ) {
					$objUsage = $this->params['cart'];
				} else {
					$objUsage = new aecCart();
					$objUsage->load( $u[1] );
				}
				break;
			case 'p':
			case 'plan':
			default:
				if ( !isset( $u[1] ) ) {
					$u[1] = $u[0];
				}

				$objUsage = new SubscriptionPlan();
				$objUsage->load( $u[1] );
				break;
		}

		return $objUsage;
	}

	public function addTargetUser( $user_ident )
	{
		global $aecConfig;

		if ( empty( $aecConfig->cfg['checkout_as_gift'] ) ) {
			return false;
		}

		if ( !empty( $aecConfig->cfg['checkout_as_gift_access'] ) ) {
			$metaUser = new metaUser( $this->userid );

			if ( !$metaUser->hasGroup( $aecConfig->cfg['checkout_as_gift_access'] ) ) {
				return false;
			}
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

		foreach ( $queries as $base_query ) {
			$query = 'SELECT `id`, `username`, `email` ' . $base_query;
			$this->_db->setQuery( $query );
			$res = $this->_db->loadObject();

			if ( !empty( $res ) ) {
				$this->params['target_user'] = $res->id;
				$this->params['target_username'] = $user_ident;
				return true;
			}
		}

		return false;
	}

	public function removeTargetUser()
	{
		if ( isset( $this->params['target_user'] ) ) {
			unset( $this->params['target_user'] );
			unset( $this->params['target_username'] );

			return true;
		} else {
			return null;
		}
	}

	public function addCoupon( $couponcode, $fromId=false )
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

		if ( $fromId ) {
			$couponcode = couponHandler::codeFromId($couponcode);

			$couponcode = $couponcode['coupon_code'];
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

	public function removeCoupon( $coupon_code, $fromId=false )
	{
		if ( empty( $this->coupons ) ) {
			return null;
		}

		if ( $fromId ) {
			$coupon_code = couponHandler::codeFromId($coupon_code);

			$coupon_code = $coupon_code['coupon_code'];
		}

		if ( !in_array( $coupon_code, $this->coupons ) ) {
			return null;
		}

		foreach ( $this->coupons as $id => $cc ) {
			if ( $cc == $coupon_code ) {
				unset( $this->coupons[$id] );
			}
		}

		$cph = new couponHandler();
		$cph->load( $coupon_code );

		if ( !empty( $cph->coupon->id ) ) {
			$cph->decrementCount( $this );
		}

		if ( empty( $this->usage ) ) {
			return;
		}

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
				$cart = new aecCart();
				$cart->load( $usage[1] );

				$cart->removeCoupon( $coupon_code );
				$cart->storeload();
				break;
		}
	}

	public function preparePickup( $array )
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

	public function getPrintout( $InvoiceFactory, $forcecleared=false, $forcecounter=null )
	{
		global $aecConfig;

		if ( is_null( $forcecounter ) ) {
			$this->counter = $forcecounter;
		}

		if ( ( $this->transaction_date == '0000-00-00 00:00:00' ) && $forcecleared ) {
			$this->transaction_date = date( 'Y-m-d H:i:s', ( (int) gmdate('U') ) );
		}

		$data = $this->getWorkingData( $InvoiceFactory );

		$data['invoice_id'] = $this->id;
		$data['invoice_number'] = $this->invoice_number;

		$data['invoice_date'] = aecTemplate::date( $InvoiceFactory->invoice->created_date );

		$data['itemlist'] = array();
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

				$pp->getInfo();

				if ( !empty( $InvoiceFactory->plan->id ) ) {
					$pp->exchangeSettingsByPlan( $InvoiceFactory->plan->id, $InvoiceFactory->plan->params );
				}
			}
		} else {
			$pp = null;
		}

		$pplist[$this->method] = $pp;

		$recurring = false;
		if ( !empty($pp) ) {
			$recurring = $pplist[$this->method]->is_recurring();
		}

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

		$s = array( "before_header", "header", "after_header", "address",
					"before_content", "after_content",
					"before_footer", "footer", "after_footer",
					);

		foreach ( $s as $k ) {
			if ( empty( $data[$k] ) ) {
				$data[$k] = "";
			}
		}

		return $data;
	}

	public function getTransactionStatus()
	{
		$lang = JFactory::getLanguage();

		if ( $this->transaction_date == '0000-00-00 00:00:00' ) {
			$transactiondate = JText::_('INVOICEPRINT_PAIDSTATUS_UNPAID');

			if ( empty( $this->params ) || empty( $this->params['pending_reason'] ) ) {
				return $transactiondate;
			}

			if ( $lang->hasKey( 'PAYMENT_PENDING_REASON_' . strtoupper( $this->params['pending_reason'] ) ) ) {
				$transactiondate = JText::_( 'PAYMENT_PENDING_REASON_' . strtoupper( $this->params['pending_reason'] ) );
			} else {
				$transactiondate = $this->params['pending_reason'];
			}
		} else {
			$transactiondate = aecTemplate::date( $this->transaction_date );
		}

		return $transactiondate;
	}

	public function savePOSTsettings( $post )
	{
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
	}

	public function savePostParams( $array )
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

	public function check( $fields=array() )
	{
		$unset = array( 'made_free' );

		foreach ( $unset as $varname ) {
			if ( isset( $this->$varname ) ) {
				unset( $this->$varname );
			}
		}

		$this->amount = AECToolbox::correctAmount( $this->amount );

		parent::check($fields);

		return true;
	}

	public function delete( $pk=null )
	{
		if ( !empty( $this->coupons ) ) {
			foreach ( $this->coupons as $cid ) {
				$this->removeCoupon( $cid );
			}
		}

		return parent::delete($pk);
	}

}
