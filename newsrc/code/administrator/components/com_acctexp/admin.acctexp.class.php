<?php
/**
 * @version $Id: acctexp.class.php
 * @package AEC - Account Control Expiration - Membership Manager
 * @subpackage Core Admin Class
 * @copyright 2012 Copyright (C) David Deutsch
 * @author David Deutsch <skore@valanx.org> & Team AEC - http://www.valanx.org
 * @license GNU/GPL v.2 http://www.gnu.org/licenses/old-licenses/gpl-2.0.html or, at your option, any later version
 */

// Dont allow direct linking
( defined('_JEXEC') || defined( '_VALID_MOS' ) ) or die( 'Direct Access to this location is not allowed.' );

class aecSuperCommand
{
	function aecSuperCommand()
	{

	}

	function parseString( $string )
	{
		$particles = explode( '|', str_replace( 'supercommand:', '', str_replace( '!supercommand:', '', $string ) ) );

		if ( count( $particles ) == 3 ) {
			$this->focus = $particles[0];

			$this->audience = $this->getParticle( $particles[1] );
			$this->action = $this->getParticle( $particles[2] );

			return true;
		} elseif ( count( $particles ) == 2 ) {
			$this->focus = 'users';

			$this->audience = $this->getParticle( $particles[0] );
			$this->action = $this->getParticle( $particles[1] );

			return true;
		} else {
			return false;
		}
	}

	function getParticle( $data )
	{
		$d = explode( ':', $data, 2 );

		$return = array();
		$return['command'] = $d[0];

		if ( !empty( $d[1] ) ) {
			$return['parameters'] = explode( ':', $d[1] );
		}

		return $return;
	}

	function query( $armed )
	{
		$userlist = $this->getAudience();

		$users = count( $userlist );

		if ( $armed && $users ) {
			$x = 0;
			foreach( $userlist as $userid ) {
				if ( ( $this->focus == 'users' ) ) {
					$metaUser = new metaUser( $userid );
				} else {
					$metaUser = new metaUser( null, $userid );
				}

				$r = $this->action( $metaUser );

				if ( $r === false ) {
					return $x;
				}

				$x++;
			}
		}

		return $users;
	}

	function getAudience()
	{
		switch ( $this->audience['command'] ) {
			case 'all':
			case 'everybody':
				$db = &JFactory::getDBO();

				$query = 'SELECT `id`'
						. ' FROM #__users'
						;
				$db->setQuery( $query );
				$userlist = $db->loadResultArray();
				break;
			case 'orphans':
				/*$this->focus == 'subscriptions';

				$db = &JFactory::getDBO();

				$query = 'SELECT id'
						. ' FROM #__acctexp_subscr AS subs'
						. ' WHERE subs.userid NOT IN ('
						. ' SELECT juser.id'
						. ' FROM #__users AS juser)'
						;
				$query = 'SELECT `id`'
						. ' FROM #__users'
						. ' WHERE `userid` IN (' . $params[1] . ')'
						;
				$db->setQuery( $query );*/
				return $db->loadResultArray();
				break;
			case 'subscribers':
				$db = &JFactory::getDBO();

				$query = 'SELECT ' . ( $this->focus == 'users' ) ? 'DISTINCT `userid`' : '`id`';

				$query .= ' FROM #__acctexp_subscr';

				if ( !empty( $this->audience['parameters'][0] ) ) {
					$status = explode( ',', $this->audience['parameters'][0] );

					$stats = array();
					foreach ( $status as $stat ) {
						$stats[] = 'LOWER( `status` ) = \'' . strtolower( $stat ) . '\'';
					}

					$query .= ' WHERE ' . implode( ' AND ', $stats);
				} else {
					$query .= ' WHERE `status` != \'Expired\''
							. ' AND `status` != \'Closed\''
							. ' AND `status` != \'Hold\''
							;
				}

				$db->setQuery( $query );
				return $db->loadResultArray();
			default:
				$cmd = 'cmd' . ucfirst( strtolower( $this->audience['command'] ) );

				if ( method_exists( $this, $cmd ) ) {
					$userlist = $this->$cmd( $this->audience['parameters'] );
				} else {
					return false;
				}
		}

		return $userlist;
	}

	function action( $metaUser )
	{
		switch ( $this->action['command'] ) {
			case 'expire':
				$metaUser->focusSubscription->expire();
				break;
			case 'forget':
				if ( $this->focus == 'users' ) {
					$metaUser->cmsUser->delete();
				} else {
					$metaUser->focusSubscription->delete();
				}
				break;
			case 'forget_it_all':
				$metaUser->delete();
				break;
			case 'amnesia':
				$metaUser->meta->delete();
				break;
			default:
				$cmd = 'cmd' . ucfirst( strtolower( $this->action['command'] ) );

				if ( method_exists( $this, $cmd ) ) {
					$userlist = $this->$cmd( $metaUser, $this->action['parameters'] );
				} else {
					return false;
				}
		}

		return true;
	}

	function cmdHas( $params )
	{
		switch ( strtolower( $params[0] ) ) {
			case 'subscriptionid':
				return explode( ',', $params[1] );
				break;
			case 'userid':
				$db = &JFactory::getDBO();

				$query = 'SELECT `id`'
						. ' FROM #__users'
						. ' WHERE `userid` IN (' . $params[1] . ')'
						;
				$db->setQuery( $query );
				return $db->loadResultArray();
				break;
			case 'username':
				$db = &JFactory::getDBO();

				$query = 'SELECT `id`'
						. ' FROM #__users'
						. ' WHERE LOWER( `username` ) LIKE \'%' . $params[1] . '%\''
						;
				$db->setQuery( $query );
				$ids = $db->loadResultArray();

				$p = array();
				$p[0] = 'userid';
				$p[1] = implode( ',', $ids );

				return $this->cmdHas( $p );
				break;
			case 'plan':
				$db = &JFactory::getDBO();

				$query = 'SELECT ' . ( ( $this->focus == 'users' ) ? 'DISTINCT `userid`' : '`id`' )
						. ' FROM #__acctexp_subscr'
						. ' WHERE `plan` IN (' . $params[1] . ')'
						. ' AND `status` != \'Expired\''
						. ' AND `status` != \'Closed\''
						. ' AND `status` != \'Hold\''
						;
				$db->setQuery( $query );
				return $db->loadResultArray();
				break;
			case 'mi':
				$db = &JFactory::getDBO();

				$mis = explode( ',', $params[1] );

				$plans = array();
				foreach ( $plans as $plan ) {
					$plans = array_merge( $plans, microIntegrationHandler::getPlansbyMI( $params[1] ) );
				}

				$plans = array_unique( $plans );

				$p = array();
				$p[0] = 'plan';
				$p[1] = implode( ',', $plans );

				return $this->cmdHas( $p );
				break;
		}
	}

	function cmdApply( $metaUser, $params )
	{
		$db = &JFactory::getDBO();

		switch ( strtolower( $params[0] ) ) {
			case 'plan':
				$plans = explode( ',', $params[1] );

				foreach ( $plans as $planid ) {
					$plan = new SubscriptionPlan( $db );
					$plan->load( $planid );

					$metaUser->establishFocus( $plan );

					$metaUser->focusSubscription->applyUsage( $planid, 'none', 1 );
				}
				break;
			case 'mi':
				$micro_integrations = explode( ',', $params[1] );

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

						if ( isset( $params[2] ) ) {
							$action = $params[2];
						} else {
							$action = 'action';
						}

						$invoice = $exchange = $add = null;

						if ( $mi->relayAction( $metaUser, $exchange, $invoice, null, $action, $add ) === false ) {
							if ( $aecConfig->cfg['breakon_mi_error'] ) {
								return false;
							}
						}

						unset( $mi );
					}
				}
				break;
		}
	}
}

class aecImport
{
	function aecImport( $file, $options )
	{
		$this->filepath = $file;

		$this->options = $options;

		$this->errors = 0;
	}

	function read()
	{
		if ( is_readable( $this->filepath ) ) {
			return true;
		} else {
			return false;
		}
	}

	function parse()
	{
		include_once( JPATH_SITE . '/components/com_acctexp/lib/parsecsv/parsecsv.lib.php' );

		$csv = new parseCSV();
		$csv->heading = false;

		$this->rows = $csv->parse( $this->filepath );

		if ( $csv->parse( $this->filepath ) ) {
			$this->rows = $csv->data;

			return true;
		} else {
			return false;
		}
	}

	function getConversionList()
	{
		foreach( $this->rows[0] as $k => $v ) {
			if ( isset( $_POST['convert_field_'.$k] ) ) {
				$this->conversion[$k] = $_POST['convert_field_'.$k];
			}
		}
	}

	function convertRow( $row )
	{
		$converted = array();
		foreach ( $this->conversion as $k => $v ) {
			if ( isset( $row[$k] ) ) {
				$converted[$v] = $row[$k];
			} else {
				$converted[$v] = "";
			}
		}

		return $converted;
	}

	function import()
	{
		$db = &JFactory::getDBO();

		foreach( $this->rows as $row ) {
			$userid = null;

			$user = $this->convertRow( $row );

			if ( empty( $user['username'] ) && empty( $user['id'] ) ) {
				continue;
			}

			if ( !empty( $user['id'] ) ) {
				$query = 'SELECT `id`'
						. ' FROM #__users'
						. ' WHERE `id` = \'' . $user['id'] . '\''
						;
				$db->setQuery( $query );

				$userid = $db->loadResult();
			}

			if ( empty( $userid ) ) {
				$query = 'SELECT `id`'
						. ' FROM #__users'
						. ' WHERE `username` = \'' . $user['username'] . '\''
						;
				$db->setQuery( $query );

				$userid = $db->loadResult();
			}

			if ( !$userid ) {
				// We cannot find any user by this id or name, create one
				if ( !empty( $user['email'] ) && !empty( $user['username'] ) ) {
					if ( empty( $user['password'] ) ) {
						$user['password'] = AECToolbox::randomstring( 8, true );
					}

					if ( empty( $user['name'] ) ) {
						$user['name'] = $user['username'];
					}

					if ( !empty( $user['password'] ) ) {
						$user['password2'] = $user['password'];
					}

					$fields = $user;

					$excludefields = array( 'plan_id', 'invoice_number', 'expiration' );

					foreach ( $excludefields as $field ) {
						if ( isset( $fields[$field] ) ) {
							unset( $fields[$field] );
						}
					}

					$userid = $this->createUser( $fields );
				} else {
					continue;
				}
			}

			if ( empty( $userid ) ) {
				$this->errors++;
			}

			$metaUser = new metaUser( $userid );

			if ( !empty( $user['plan_id'] ) ) {
				$pid = $user['plan_id'];
			} else {
				$pid = $this->options['assign_plan'];
			}

			$subscr_action = false;

			if ( !empty( $pid ) ) {
				$plan = new SubscriptionPlan( $db );
				$plan->load( $pid );

				$d = $metaUser->establishFocus( $plan, 'none', true );

				$metaUser->focusSubscription->applyUsage( $pid, 'none', 1 );

				$subscr_action = true;
			}

			if ( !empty( $user['expiration'] ) && !empty( $metaUser->focusSubscription->id ) ) {
				$metaUser->focusSubscription->expiration = date( 'Y-m-d H:i:s', strtotime( $user['expiration'] ) );

				if ( $metaUser->focusSubscription->status == 'Trial' ) {
					$metaUser->focusSubscription->status = 'Trial';
				} else {
					$metaUser->focusSubscription->status = 'Active';
				}

				$metaUser->focusSubscription->lifetime = 0;

				$metaUser->focusSubscription->storeload();

				$subscr_action = true;
			}

			if ( !empty( $user['invoice_number'] ) && !empty( $pid ) ) {
				// Create Invoice
				$invoice = new Invoice( $db );
				$invoice->create( $userid, $pid, 'none', $user['invoice_number'] );

				if ( $subscr_action ) {
					$invoice->subscr_id = $metaUser->focusSubscription->id;
				}

				$invoice->setTransactionDate();
			}
		}
	}

	function createUser( $fields )
	{
		return AECToolbox::saveUserRegistration( 'com_acctexp', $fields, true, true, true, true );
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

	function aecExport( &$db, $type=false )
	{
		$this->type = $type;

		if ( $type ) {
			parent::__construct( '#__acctexp_export_sales', 'id', $db );
		} else {
			parent::__construct( '#__acctexp_export', 'id', $db );
		}
	}

	function load( $id )
	{
		parent::load($id);

		$params = $this->declareParamFields();

		foreach ( $params as $field ) {
			if ( is_object( $this->$field ) ) {
				$this->$field = get_object_vars( $this->$field );
			}
		}
	}

	function declareParamFields()
	{
		return array( 'filter', 'options', 'params'  );
	}

	function useExport()
	{
		$app = JFactory::getApplication();

		$this->getHandler();

		if ( $this->type ) {
			$this->exportSales();
		} else {
			$this->exportMembers();
		}

		$this->setUsedDate();

		$this->exphandler->finishExport();
	}

	function getHandler()
	{
		// Load Exporting Class
		$filename = JPATH_SITE . '/components/com_acctexp/lib/export/' . $this->params['export_method'] . '.php';
		$classname = 'AECexport_' . $this->params['export_method'];

		require_once( $filename );

		$this->exphandler = new $classname();
		$this->exphandler->name = $this->name;
		$this->exphandler->params = $this->params;

		if ( $this->type ) {
			$this->exphandler->type = array('sales', 'sale');
		} else {
			$this->exphandler->type = array('members', 'member');
		}

		$this->exphandler->prepareExport();
	}

	function prepareExport()
	{
		$fname = 'aecexport_' . urlencode( stripslashes( $this->name ) ) . '_' . date( 'Y_m_d', ( (int) gmdate('U') ) );

		// Send download header
		header("Pragma: public");
		header("Expires: 0");
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");

		header("Content-Type: application/force-download");
		header("Content-Type: application/octet-stream");

		header("Content-Type: application/download");
		header('Content-Disposition: inline; filename="' . $fname . '.' . $this->params['export_method'] . '"');
	}

	function putDescription( $array )
	{
		$this->description = $array;
	}

	function putSum( $array )
	{
		$this->sum = $array;
	}

	function putln( $array )
	{
		$this->lines[] = $array;
	}

	function finishExport()
	{
		exit;
	}

	function exportSales()
	{
		$db = &JFactory::getDBO();

		$query = 'SELECT `id`'
				. ' FROM #__acctexp_log_history'
				. ' WHERE transaction_date >= \'' . $this->filter['date_start'] . '\''
				. ' AND transaction_date <= \'' . $this->filter['date_end'] . '\''
				. ' ORDER BY transaction_date ASC'
				;
		$db->setQuery( $query );
		$entries = $db->loadResultArray();

		switch ( $this->options['collate'] ) {
			default:
			case 'day':
				$collation = 'Y-m-d';
				break;
			case 'week':
				$collation = 'Y-W';
				break;
			case 'month':
				$collation = 'Y-m';
				break;				
			case 'year':
				$collation = 'Y';
				break;				
		}

		$collators = array();

		switch ( $this->options['breakdown'] ) {
			default:
			case 'plan':
				break;
			case 'group':
				$all_groups = ItemGroupHandler::getGroups();

				$collators = array();
				foreach ( $all_groups as $gid ) {
					$collators[$gid] = ItemGroupHandler::getChildren( $gid, 'item' );
				}
				break;
		}

		$historylist = array();
		$groups = array();
		foreach ( $entries as $id ) {
			$entry = new logHistory( $db );
			$entry->load( $id );

			if ( empty( $entry->plan_id ) || empty( $entry->amount ) ) {
				continue;
			}

			if ( !empty( $this->filter['groupid'] ) ) {
				if ( empty( $this->filter['planid'] ) ) {
					$this->filter['planid'] = array();
				}

				$children = ItemGroupHandler::getChildren( $this->filter['groupid'], 'item' );

				if ( !empty( $children ) ) {
					$this->filter['planid'] = array_merge( $this->filter['planid'], $children );

					$this->filter['planid'] = array_unique( $this->filter['planid'] );
				}
			}

			if ( !empty( $this->filter['planid'] ) ) {
				if ( !in_array( $entry->plan_id, $this->filter['planid'] ) ) {
					continue;
				}
			}

			if ( !empty( $this->filter['method'] ) ) {
				if ( !in_array( $entry->proc_id, $this->filter['method'] ) ) {
					continue;
				}
			}

			$refund = false;
			if ( is_array( $entry->response ) ) {
				$filter = array( 'new_case', 'subscr_signup', 'paymentreview', 'subscr_eot', 'subscr_failed', 'subscr_cancel', 'Pending', 'Denied' );

				$refund = false;
				foreach ( $entry->response as $v ) {
					if ( in_array( $v, $filter ) ) {
						continue 2;
					} elseif ( ( $v == 'refund' ) || ( $v == 'Reversed' ) || ( $v == 'Refunded' ) ) {
						$refund = true;
					}
				}
			}

			$date = date( $collation, strtotime( $entry->transaction_date ) );

			if ( $this->options['breakdown'] == 'plan' ) {
				if ( !array_key_exists( $entry->plan_id, $collators ) ) {
					$collators[$entry->plan_id] = 0;
				}
			}

			if ( !isset( $historylist[$date] ) ) {
				$historylist[$date] = array();
			}

			$historylist[$date][] = $entry;
		}

		$line = array( "line" => "Date" );

		if ( $this->options['breakdown'] == 'plan' ) {
			foreach ( $collators as $col => $colamount ) {
				$line['plan-'.$col] = "Plan #$col: ".SubscriptionPlanHandler::planName( $col );
			}
		} elseif ( $this->options['breakdown'] == 'group' ) {
			$grouplist = ItemGroupHandler::getTree();

			foreach ( $collators as $col => $colplans ) {
				$line['group-'.$col] = "Group #$col:".ItemGroupHandler::groupName( $col );
			}
		}

		$line['total_sum'] = "Total";

		// Remove whitespaces and newlines
		foreach( $line as $larrid => $larrval ) {
			$line[$larrid] = trim($larrval);

			if ( is_numeric( $larrval ) ) {
				$line[$larrid] = AECToolbox::correctAmount($larrval);
			}
		}

		$this->exphandler->putDescription( $line );

		$totalsum = 0;
		$collate_all = array();

		foreach ( $collators as $col => $colv ) {
			$collate_all[$col] = 0;
		}

		foreach ( $historylist as $date => $collater ) {
			$linesum = 0;
			$collatex = array();

			foreach ( $collators as $col => $colv ) {
				$collatex[$col] = 0;
			}

			foreach ( $collater as $entry ) {
				if ( $this->options['breakdown'] == 'plan' ) {
					$collatex[$entry->plan_id] += $entry->amount;
					$collate_all[$entry->plan_id] += $entry->amount;

					$linesum += $entry->amount;
					$totalsum += $entry->amount;
				} else {
					$pgroup = 0;
					foreach ( $collators as $gid => $gplans ) {
						if ( $entry->plan_id == $gid ) {
							$pgroup = $gid;
							break;
						}
					}

					if ( $pgroup ) {
						$collatex[$pgroup] += $entry->amount;
						$collate_all[$pgroup] += $entry->amount;
					}

					$linesum += $entry->amount;
					$totalsum += $entry->amount;
				}
			}

			$line = array( "date" => $date );

			foreach ( $collators as $col => $colamount ) {
				if ( $this->options['breakdown'] == 'plan' ) {
					$line['plan-'.$col] = $collatex[$col];
				} else {
					$line['group-'.$col] = $collatex[$col];
				}
			}

			$line['total_sum'] = $linesum;

			// Remove whitespaces and newlines
			$i = 0;
			foreach( $line as $larrid => $larrval ) {
				$line[$larrid] = trim($larrval);

				if ( is_numeric( $larrval ) && $i ) {
					$line[$larrid] = AECToolbox::correctAmount($larrval);
				}

				$i++;
			}

			$this->exphandler->putln( $line );
		}

		$line = array( "line" => "Grand Total" );

		foreach ( $collate_all as $col => $colamount ) {
			if ( $this->options['breakdown'] == 'plan' ) {
				$line['plan-'.$col] = $colamount;
			} else {
				$line['group-'.$col] = $colamount;
			}
		}

		$line['total_sum'] = $totalsum;

		// Remove whitespaces and newlines
		foreach( $line as $larrid => $larrval ) {
			$line[$larrid] = trim($larrval);

			if ( is_numeric( $larrval ) ) {
				$line[$larrid] = AECToolbox::correctAmount($larrval);
			}
		}

		$this->exphandler->putSum( $line );
	}

	function exportMembers()
	{
		$db = &JFactory::getDBO();

		foreach ( $this->filter as $k => $v ) {
			if ( empty( $v ) ) {
				$this->filter[$k] = array();
			}
		}

		// Assemble Database call
		if ( !in_array( 'manual', $this->filter['status'] ) ) {
			$where = array();
			if ( !empty( $this->filter['planid'] ) ) {
				$where[] = '`plan` IN (' . implode( ',', $this->filter['planid'] ) . ')';
			}

			$query = 'SELECT a.id, a.userid'
					. ' FROM #__acctexp_subscr AS a'
					. ' INNER JOIN #__users AS b ON a.userid = b.id';

			if ( !empty( $where ) ) {
				$query .= ' WHERE ( ' . implode( ' OR ', $where ) . ' )';
			}

			if ( !empty( $this->filter['status'] ) ) {
				$stati = array();
				foreach ( $this->filter['status'] as $status ) {
					$stati[] = 'LOWER( `status` ) = \'' . strtolower( $status ) . '\'';
				}

				if ( !empty( $where ) ) {
					$query .= ' AND (' . implode( ' OR ', $stati ) . ')';
				} else {
					$query .= ' WHERE (' . implode( ' OR ', $stati ) . ')';
				}
			}

			if ( !empty( $this->filter['orderby'] ) ) {
				$query .= ' ORDER BY ' . $this->filter['orderby'] . '';
			}
		} else {
			$query = 'SELECT DISTINCT b.id AS `userid`'
					. ' FROM #__users as b'
					. ' WHERE b.id NOT IN ('
					. ' SELECT a.userid'
					. ' FROM #__acctexp_subscr as a);'
					;
		}

		$db->setQuery( $query );

		$descriptions = AECToolbox::rewriteEngineExplain( $this->options['rewrite_rule'] );

		$descarray = explode( ';', $descriptions );

		$this->exphandler->putDescription( $descarray );

		// Fetch Userlist
		$userlist = $db->loadObjectList();

		// Plans Array
		$plans = array();

		// Iterate through userlist
		if ( !empty( $userlist ) ) {
			foreach ( $userlist as $entry ) {
				$metaUser = new metaUser( $entry->userid );

				if ( !empty( $entry->id ) ) {
					$metaUser->moveFocus( $entry->id );
				}

				if ( $metaUser->hasSubscription ) {
					$planid = $metaUser->focusSubscription->plan;

					if ( !isset( $plans[$planid] ) ) {
						$plans[$planid] = new SubscriptionPlan( $db );
						$plans[$planid]->load( $planid );
					}

					$invoiceid = AECfetchfromDB::lastClearedInvoiceIDbyUserID( $metaUser->userid, $planid );

					if ( $invoiceid ) {
						$invoice = new Invoice( $db );
						$invoice->load( $invoiceid );

						$line = AECToolbox::rewriteEngine( $this->options['rewrite_rule'], $metaUser, $plans[$planid], $invoice );
					} else {
						$line = AECToolbox::rewriteEngine( $this->options['rewrite_rule'], $metaUser, $plans[$planid] );
					}
				} else {
					$line = AECToolbox::rewriteEngine( $this->options['rewrite_rule'], $metaUser );
				}

				$larray = explode( ';', $line );

				// Remove whitespaces and newlines
				foreach( $larray as $larrid => $larrval ) {
					$larray[$descarray[$larrid]] = trim($larrval);
					
					unset($larray[$larrid]);
				}

				$this->exphandler->putln( $larray );
			}
		}
	}


	function setUsedDate()
	{
		$app = JFactory::getApplication();

		$this->lastused_date = date( 'Y-m-d H:i:s', ( (int) gmdate('U') ) );
		$this->storeload();
	}

	function save( $name, $filter, $options, $params, $system=false, $is_test=false )
	{
		$app = JFactory::getApplication();

		$db = &JFactory::getDBO();

		// Drop old system saves to always keep 10 records
		if ( $system ) {
			$query = 'SELECT count(*) '
					. ' FROM ' . $this->_tbl
					. ' WHERE `system` = \'1\''
					;
			$db->setQuery( $query );
			$sysrows = $db->loadResult();

			if ( $sysrows > 9 ) {
				$query = 'DELETE'
						. ' FROM ' . $this->_tbl
						. ' WHERE `system` = \'1\''
						. ' ORDER BY `id` ASC'
						. ' LIMIT 1'
						;
				$db->setQuery( $query );
				$db->query();
			}
		}

		$this->name = $name;
		$this->system = $system ? 1 : 0;
		$this->filter = $filter;
		$this->options = $options;
		$this->params = $params;

		if ( ( strcmp( $this->created_date, '0000-00-00 00:00:00' ) === 0 ) || empty( $this->created_date ) ) {
			$this->created_date = date( 'Y-m-d H:i:s', ( (int) gmdate('U') ) );
		}

		$type = 0;

		if ( !$is_test ) {
			if ( isset( $this->type ) ) {
				$type = $this->type;

				unset( $this->type );
			}

			if ( isset( $this->lines ) ) {
				unset( $this->lines );
			}

			$this->storeload();

			$this->type = $type;
		}
	}

}

class aecReadout
{

	function aecReadout( $optionlist, $method )
	{
		$this->optionlist = $optionlist;
		$this->method = "conversionHelper" . strtoupper( $method );

		$this->lists = array();

		$this->acllist = aecACLhandler::aclList();

		foreach ( $this->acllist as $aclitem ) {
			$this->lists['gid'][$aclitem->group_id] = $aclitem->name;
		}

		$this->planlist = SubscriptionPlanHandler::getFullPlanList();

		foreach ( $this->planlist as $planitem ) {
			$this->lists['plan'][$planitem->id] = $planitem->name;
		}

		$this->milist = microIntegrationHandler::getMIList( null, null, isset( $_POST['use_ordering'] ), true );

		foreach ( $this->milist as $miitem ) {
			$this->lists['mi'][$miitem->id] = $miitem->name;
		}
	}

	function conversionHelper( $content, $obj )
	{
		return $this->{$this->method}( $content, $obj );
	}

	function readSettings()
	{
		global $aecConfig;

		$r = array();
		$r['head'] = "Settings";
		$r['type'] = "table";

		$setdef = Config_General::paramsList();

		$r['def'] = array();
		foreach ( $setdef as $sd => $sdd ) {
			if ( ( $sdd === 0 ) || ( $sdd === 1 ) ) {
				$tname = str_replace( ':', '', JText::_( 'CFG_GENERAL_' . strtoupper( $sd ) . '_NAME' ) );

				$r['def'][$tname] = array( $sd, 'bool' );
			}
		}

		$r['set'][] = $aecConfig->cfg;

		if ( !empty( $_POST['show_extsettings'] ) ) {
			$readout[] = $r;

			unset($r);

			$r['head'] = "";
			$r['type'] = "table";

			$setdef = Config_General::paramsList();

			$r['def'] = array();
			foreach ( $setdef as $sd => $sdd ) {
				if ( ( $sdd !== 0 ) && ( $sdd !== 1 ) ) {
					$reg = array( 'GENERAL', 'MI' );

					foreach ( $reg as $regg ) {
						$cname = 'CFG_' . $regg . '_' . strtoupper( $sd ) . '_NAME';

						if ( defined( $cname ) )  {
							$tname = str_replace( ':', '', JText::_( $cname ) );
						}
					}

					$r['def'][$tname] = array( $sd );
				}
			}

			$r['set'][] = $aecConfig->cfg;
		}

		$readout[] = $r;

		return $readout;
	}

	function readProcessors()
	{
		$db = &JFactory::getDBO();

		$lang = JFactory::getLanguage();

		$readout = array();

		$r = array();
		$r['head'] = "Processors";

		$processors = PaymentProcessorHandler::getInstalledNameList();

		foreach ( $processors as $procname ) {
			$pp = null;
			$pp = new PaymentProcessor( $db );

			if ( !$pp->loadName( $procname ) ) {
				continue;
			}

			$pp->fullInit();

			$readout[] = $r;

			$r = array();

			$r['head'] = $pp->info['longname'];
			$r['type'] = "table";
			$r['sub'] = true;

			$r['def'] = array (
				"ID" => array( 'id' ),
				"Published" => array( 'active', 'bool' )
			);

			foreach ( $pp->info as $iname => $ic ) {
				if ( empty( $iname ) ) {
					continue;
				}

				$cname = 'CFG_' . strtoupper( $procname ) . '_' . strtoupper($iname) . '_NAME';
				$gname = 'CFG_PROCESSOR_' . strtoupper($iname) . '_NAME';

				if ( $lang->hasKey( $cname ) ) {
					$tname = JText::_( $cname );
				} elseif ( $lang->hasKey( $gname ) )  {
					$tname = JText::_( $gname );
				} else {
					$tname = $iname;
				}

				$r['def'][$tname] = array( array( 'info', $iname ), 'smartlimit' );
			}

			$bsettings = $pp->getBackendSettings();

			foreach ( $bsettings as $psname => $sc ) {
				if ( empty( $psname ) || is_numeric( $psname ) || ( $psname == 'lists') ) {
					continue;
				}

				$cname = 'CFG_' . strtoupper( $procname ) . '_' . strtoupper($psname) . '_NAME';
				$gname = 'CFG_PROCESSOR_' . strtoupper($psname) . '_NAME';

				if ( $lang->hasKey( $cname ) )  {
					$tname = JText::_( $cname );
				} elseif ( $lang->hasKey( $gname ) )  {
					$tname = JText::_( $gname );
				} else {
					$tname = $psname;
				}

				if ( $sc[0] == 'list_yesno' ) {
					$stype = 'bool';
				} else {
					$stype = 'smartlimit';
				}

				$r['def'][$tname] = array( array( 'settings', $psname ), $stype );
			}

			$ps = array();
			foreach ( $r['def'] as $nn => $def ) {
				$ps = array_merge( $ps, $this->conversionHelper( $def, $pp ) );
			}

			$r['set'] = array( 0 => $ps );
		}

		$readout[] = $r;

		return $readout;
	}

	function readPlans()
	{
		$db = &JFactory::getDBO();

		$r = array();
		$r['head'] = "Payment Plans";
		$r['type'] = "table";

		$r['def'] = array (
			"ID" => array( 'id' ),
			"Published" => array( 'active', 'bool' ),
			"Visible" => array( 'visible', 'bool' ),
			"Name" => array( 'name', 'smartlimit haslink', 'editSubscriptionPlan', 'id' ),
			"Desc" => array( 'desc', 'notags smartlimit' ),
			"Primary" => array( array( 'params', 'make_primary' ), 'bool' ),
			"Activate" => array( array( 'params', 'make_active' ), 'bool' ),
			"Update Exist." => array( array( 'params', 'update_existing' ), 'bool' ),
			"Override Activat." => array( array( 'params', 'override_activation' ), 'bool' ),
			"Override Reg. Email" => array( array( 'params', 'override_regmail' ), 'bool' ),
			"Set GID" => array( array( 'params', 'gid_enabled' ), 'bool' ),
			"GID" => array( array( 'params', 'gid' ), 'list', 'gid' ),

			"Standard Parent Plan" => array( array( 'params', 'standard_parent' ), 'list', 'plan' ),
			"Fallback Plan" => array( array( 'params', 'fallback' ), 'list', 'plan' ),

			"Free" => array( array( 'params', 'full_free' ), 'bool' ),
			"Cost" => array( array( 'params', 'full_amount' ) ),
			"Lifetime" => array( array( 'params', 'lifetime' ), 'bool' ),
			"Period" => array( array( 'params', 'full_period' ) ),
			"Unit" => array( array( 'params', 'full_periodunit' ) ),

			"Free Trial" => array( array( 'params', 'trial_free' ), 'bool' ),
			"Trial Cost" => array( array( 'params', 'trial_amount' ) ),
			"Trial Period" => array( array( 'params', 'trial_period' ) ),
			"Trial Unit" => array( array( 'params', 'trial_periodunit' ) ),

			"Has MinGID" => array( array( 'restrictions', 'mingid_enabled' ), 'bool' ),
			"MinGID" => array( array( 'restrictions', 'mingid' ), 'list', 'gid' ),
			"Has FixGID" => array( array( 'restrictions', 'fixgid_enabled' ), 'bool' ),
			"FixGID" => array( array( 'restrictions', 'fixgid' ), 'list', 'gid' ),
			"Has MaxGID" => array( array( 'restrictions', 'fixgid_enabled' ), 'bool' ),
			"MaxGID" => array( array( 'restrictions', 'fixgid' ), 'list', 'gid' ),

			"Requires Prev. Plan" => array( array( 'restrictions', 'previousplan_req_enabled' ), 'bool' ),
			"Prev. Plan" => array( array( 'restrictions', 'previousplan_req' ), 'list', 'plan' ),
			"Excluding Prev. Plan" => array( array( 'restrictions', 'previousplan_req_enabled_excluded' ), 'bool' ),
			"Excl. Prev. Plan" => array( array( 'restrictions', 'previousplan_req_excluded' ), 'list', 'plan' ),
			"Requires Curr. Plan" => array( array( 'restrictions', 'currentplan_req_enabled' ), 'bool' ),
			"Curr. Plan" => array( array( 'restrictions', 'currentplan_req' ), 'list', 'plan' ),
			"Excluding Curr. Plan" => array( array( 'restrictions', 'currentplan_req_enabled_excluded' ), 'bool' ),
			"Excl. Curr. Plan" => array( array( 'restrictions', 'currentplan_req_excluded' ), 'list', 'plan' ),
			"Requires Overall Plan" => array( array( 'restrictions', 'overallplan_req_enabled' ), 'bool' ),
			"Overall Plan" => array( array( 'restrictions', 'overallplan_req' ), 'list', 'plan' ),
			"Excluding Overall. Plan" => array( array( 'restrictions', 'overallplan_req_enabled_excluded' ), 'bool' ),
			"Excl. Overall. Plan" => array( array( 'restrictions', 'overallplan_req_excluded' ), 'list', 'plan' ),

			"Min Used Plan" => array( array( 'restrictions', 'used_plan_min_enabled' ), 'bool' ),
			"Min Used Plan Amount" => array( array( 'restrictions', 'used_plan_min_amount' ) ),
			"Min Used Plans" => array( array( 'restrictions', 'used_plan_min' ), 'list', 'plan' ),
			"Max Used Plan" => array( array( 'restrictions', 'used_plan_max_enabled' ), 'bool' ),
			"Max Used Plan Amount" => array( array( 'restrictions', 'used_plan_max_amount' ) ),
			"Max Used Plans" => array( array( 'restrictions', 'used_plan_max' ), 'list', 'plan' ),

			"Custom Restrictions" => array( array( 'restrictions', 'custom_restrictions_enabled' ), 'bool' ),
			"Restrictions" => array( array( 'restrictions', 'custom_restrictions' ) )
		);

		$plans = SubscriptionPlanHandler::getPlanList( null, null, isset( $_POST['use_ordering'] ) );

		$r['set'] = array();
		foreach ( $plans as $planid ) {
			$plan = new SubscriptionPlan( $db );
			$plan->load( $planid );

			$ps = array();
			foreach ( $r['def'] as $nn => $def ) {
				$ps = array_merge( $ps, $this->conversionHelper( $def, $plan ) );
			}

			$r['set'][] = $ps;
		}

		return $r;
	}

	function readPlanMIrel()
	{
		$db = &JFactory::getDBO();

		$r = array();
		$r['head'] = "Payment Plan - MicroIntegration relationships";
		$r['type'] = "table";

		$r['def'] = array (
			"ID" => array( 'id' ),
			"Published" => array( 'active', 'bool' ),
			"Visible" => array( 'visible', 'bool' ),
			"Name" => array( 'name', 'smartlimit' )
		);

		$micursor = '';
		$mis = array();
		foreach ( $this->milist as $miobj ) {
			$mi = new microIntegration( $db );
			$mi->load( $miobj->id );
			if ( !$mi->callIntegration() ) {
				continue;
			}

			if ( $miobj->class_name != $micursor ) {
				if ( !empty( $mi->info ) ) {
					$miname = $mi->info['name'];
				} else {
					$miname = $miobj->class_name;
				}
				$r['def'][$miname] = array( $miobj->class_name, 'list', 'mi' );

				$micursor = $miobj->class_name;
			}

			$mis[$mi->id] = array( $miobj->class_name, $mi->name );
		}

		$r['set'] = array();
		foreach ( $this->planlist as $planid => $planobj ) {
			$plan = new SubscriptionPlan( $db );
			$plan->load( $planobj->id );

			if ( !empty( $plan->micro_integrations ) ) {
				foreach ( $plan->micro_integrations as $pmi ) {
					if ( isset( $mis[$pmi] ) ) {
						$plan->{$mis[$pmi][0]}[] = $pmi;
					}
				}
			}

			$ps = array();
			foreach ( $r['def'] as $nn => $def ) {
				$ps = array_merge( $ps, $this->conversionHelper( $def, $plan ) );
			}

			$r['set'][] = $ps;
		}

		return $r;
	}

	function readMIs()
	{
		$db = &JFactory::getDBO();

		$lang = JFactory::getLanguage();

		$r = array();
		$r['head'] = "Micro Integration";
		$r['type'] = "";

		$micursor = '';
		foreach ( $this->milist as $miobj ) {
			$mi = new microIntegration( $db );
			$mi->load( $miobj->id );
			$mi->callIntegration(true);

			if ( $miobj->class_name != $micursor ) {
				$readout[] = $r;
				unset($r);
				$r = array();
				if ( !empty( $mi->info ) ) {
					$r['head'] = $mi->info['name'];
				} else {
					$r['head'] = $miobj->class_name;
				}
				$r['type'] = "table";
				$r['sub'] = true;
				$r['set'] = array();

				$r['def'] = array (
					"ID" => array( 'id' ),
					"Published" => array( 'active', 'bool' ),
					"Visible" => array( 'visible', 'bool' ),
					"Name" => array( 'name', 'smartlimit haslink', 'editMicroIntegration', 'id' ),
					"Desc" => array( 'desc', 'notags smartlimit' ),
					"Exp Action" => array( 'auto_check', 'bool' ),
					"PreExp Action" => array( 'pre_exp_check' ),
					"UserChange Action" => array( 'on_userchange', 'bool' )
					);

				$settings = $mi->getSettings();

				if ( isset( $settings['lists'] ) ) {
					unset( $settings['lists'] );
				}

				if ( !empty( $settings ) ) {
					foreach ( $settings as $sname => $setting ) {
						if ( is_numeric( $sname ) || ( strpos( $sname, 'aectab_' ) !== false ) ) {
							continue;
						}

						$name =  'MI_' . strtoupper( $miobj->class_name ) . '_' . strtoupper( $sname ) .'_NAME';
				
						if ( $lang->hasKey( $name ) ) {
							$r['def'][JText::_($name)] = array( array( 'settings', $sname ), 'notags smartlimit' );
						} else {
							$r['def'][$sname] = array( array( 'settings', $sname ), 'notags smartlimit' );
						}
					}
				}
			}

			$ps = array();
			foreach ( $r['def'] as $nn => $def ) {
				$ps = array_merge( $ps, $this->conversionHelper( $def, $mi ) );
			}

			$r['set'][] = $ps;

			$micursor = $miobj->class_name;
		}

		$readout[] = $r;

		return $readout;
	}

	function conversionHelperHTML( $content, $obj )
	{
		$cc = $content[0];

		if ( is_array( $cc ) ) {
			$dname = $cc[0].'_'.$cc[1];
			if ( !isset( $obj->{$cc[0]}[$cc[1]] ) ) {
				return array( $dname => '' );
			}
			$dvalue = $obj->{$cc[0]}[$cc[1]];
		} else {
			$dname = $cc;
			if ( !isset( $obj->{$cc} ) ) {
				return array( $dname => '' );
			}
			$dvalue = $obj->{$cc};
		}

		if ( isset( $content[1] ) ) {
			$type = $content[1];
		} else {
			$type = null;
		}

		if ( isset( $_POST['noformat_newlines'] ) ) {
			$nnl = ', ';
		} else {
			$nnl = ',<br />';
		}

		if ( !empty( $type ) ) {
			$types = explode( ' ', $type );

			if ( is_array( $dvalue ) ) {
				foreach ( $dvalue as $dv ) {
					if ( is_array( $dv ) ) {
						return array( $dname => '' );
					}
				}

				$dvalue = implode( ',', $dvalue );
			}

			foreach ( $types as $tt ) {
				switch ( $tt ) {
					case 'notags':
						$dvalue = strip_tags( $dvalue );
						break;
					case 'limit32':
						$dvalue = substr( $dvalue, 0, 32 );
						break;
					case 'smartlimit':
						if ( isset( $_POST['truncation_length'] ) ) {
							$truncation = $_POST['truncation_length'];
						} else {
							$truncation = 42;
						}

						if ( $truncation > 12 ) {
							$tls = 12;
						} else {
							$tls = $truncation/2;
						}

						if ( is_array( $dvalue ) ) {
							$vv = array();
							foreach ( $dvalue as $val ) {
								if ( strlen( $val ) > $truncation ) {
									$vv[] = substr( $val, 0, $truncation-$tls ) . '<strong title="' . htmlentities($val) . '">[...]</strong>' . substr( $val, -$tls, $tls );
								} else {
									$vv[] = $val;
								}
							}
							$dvalue = implode( $nnl, $vv );
						} else {
							if ( strlen( $dvalue ) > $truncation ) {
								$dvalue = substr( $dvalue, 0, $truncation-$tls ) . '<strong title="' . htmlentities($dvalue) . '">[...]</strong>' . substr( $dvalue, -$tls, $tls );
							}
						}
						break;
					case 'list':
						if ( is_array( $dvalue ) ) {
							$vv = array();
							foreach ( $dvalue as $val ) {
								if ( $val == 0 ) {
									$vv[] = '--';
								} else {
									$vv[] = "#" . $val . ":&nbsp;<strong>" . $this->lists[$content[2]][$val] . "</strong>";
								}
							}
							$dvalue = implode( $nnl, $vv );
						} else {
							if ( $dvalue == 0 ) {
								$dvalue = '--';
							} else {
								$dvalue = "#" . $dvalue . ":&nbsp;<strong>" . $this->lists[$content[2]][$dvalue] . "</strong>";
							}
						}
						break;
					case 'haslink':
						if ( isset( $content[3] ) ) {
							$tasklink = $content[2] . "&amp;" . $content[3] . "=" . $obj->{$content[3]};
							$dvalue = AECToolbox::backendTaskLink( $tasklink, $dvalue );
						} else {
							$dvalue = AECToolbox::backendTaskLink( $content[2], $dvalue );
						}
						break;
				}
			}
		}

		return array( $dname => $dvalue );
	}

	function conversionHelperCSV( $content, $obj )
	{
		$cc = $content[0];

		if ( is_array( $cc ) ) {
			$dname = $cc[0].'_'.$cc[1];
			if ( !isset( $obj->{$cc[0]}[$cc[1]] ) ) {
				return array( $dname => '' );
			}
			$dvalue = $obj->{$cc[0]}[$cc[1]];
		} else {
			$dname = $cc;
			if ( !isset( $obj->{$cc} ) ) {
				return array( $dname => '' );
			}
			$dvalue = $obj->{$cc};
		}

		if ( isset( $content[1] ) ) {
			$type = $content[1];
		} else {
			$type = null;
		}

		if ( isset( $_POST['noformat_newlines'] ) ) {
			$nnl = ', ';
		} else {
			$nnl = ',' . "\n";
		}

		if ( !empty( $type ) ) {
			$types = explode( ' ', $type );

			foreach ( $types as $tt ) {
				switch ( $tt ) {
					case 'notags':
						$dvalue = strip_tags( $dvalue );
						break;
					case 'limit32':
						$dvalue = substr( $dvalue, 0, 32 );
						break;
					case 'smartlimit':
						if ( isset( $_POST['truncation_length'] ) ) {
							$truncation = $_POST['truncation_length'];
						} else {
							$truncation = 42;
						}

						if ( $truncation > 12 ) {
							$tls = 12;
						} else {
							$tls = $truncation/2;
						}

						if ( is_array( $dvalue ) ) {
							$vv = array();
							foreach ( $dvalue as $val ) {
								if ( strlen( $val ) > $truncation ) {
									$vv[] = substr( $val, 0, $truncation-$tls ) . '[...]' . substr( $val, -$tls, $tls );
								} else {
									$vv[] = $val;
								}
							}
							$dvalue = implode( $nnl, $vv );
						} else {
							if ( strlen( $dvalue ) > $truncation ) {
								$dvalue = substr( $dvalue, 0, $truncation-$tls ) . '[...]' . substr( $dvalue, -$tls, $tls );
							}
						}
						break;
					case 'list':
						if ( is_array( $dvalue ) ) {
							$vv = array();
							foreach ( $dvalue as $val ) {
								if ( $dvalue == 0 ) {
									$vv[] = '--';
								} else {
									$vv[] = "#" . $val . ": " . $this->lists[$content[2]][$val];
								}
							}
							$dvalue = implode( $nnl, $vv );
						} else {
							if ( $dvalue == 0 ) {
								$dvalue = '--';
							} else {
								$dvalue = "#" . $dvalue . ": " . $this->lists[$content[2]][$dvalue];
							}
						}
						break;
					case 'haslink':
						$dvalue = $dvalue;
						break;
				}
			}
		}

		return array( $dname => $dvalue );
	}
}

?>