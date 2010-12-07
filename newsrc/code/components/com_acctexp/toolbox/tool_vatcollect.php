<?php
/**
 * @version $Id: tool_vatcollect.php
 * @package AEC - Account Control Expiration - Membership Manager
 * @subpackage Toolbox - VAT Collect
 * @copyright 2010 Copyright (C) David Deutsch
 * @author David Deutsch <skore@skore.de> & Team AEC - http://www.valanx.org
 * @license GNU/GPL v.2 http://www.gnu.org/licenses/old-licenses/gpl-2.0.html or, at your option, any later version
 */

// Dont allow direct linking
( defined('_JEXEC') || defined( '_VALID_MOS' ) ) or die( 'Direct Access to this location is not allowed.' );

class tool_vatcollect
{
	function Info()
	{
		$info = array();
		$info['name'] = "VAT Tax Report";
		$info['desc'] = "If you're using the Tax MI in VAT mode, this tool will collect the logged taxes into a proper monthly report.";

		return $info;
	}

	function Settings()
	{
		// Compute sensible timeframe for the default
		$day = date('d');

		if ( ( $day < 7 ) || ( $day > 22 ) ) {
			// Show second week
			if ( $day > 22 ) {
				$start	= strtotime( date( 'Y-m' ) . '-15 00:00:00' );
				$end	= strtotime( date( 'Y-m-t' ) );
			} else {
				// This should probably be replaced by something using straight strtotime
				$year = date( 'Y' );
				
				$prevmonth = date( 'm' ) - 1;

				if ( $prevmonth <= 0 ) {
					$year = $year - 1;

					$prevmonth = 12;
				}

				$lastday = date( 't', strtotime( $year . '-' . $prevmonth . '-15 00:00:00' ) );

				$start	= strtotime( $year . '-' . $prevmonth . '-15 00:00:00' );
				$end	= strtotime( $year . '-' . $prevmonth . '-' . $lastday . ' 23:59:59' );
			}
		} else {
			// Show first week
			$start	= strtotime( date( 'Y-m' ) . '-1 00:00:00' );
			$end	= strtotime( date( 'Y-m' ) . '-14 23:59:59' );
		}

		$settings = array();
		$settings['start_date']	= array( 'list_date', 'Start Date', '', date( 'Y-m-d', $start ) );
		$settings['end_date']	= array( 'list_date', 'End Date', '', date( 'Y-m-d', $end ) );

		return $settings;
	}

	function Action()
	{
		if ( empty( $_POST['start_date'] ) ) {
			return null;
		}

		$db = &JFactory::getDBO();

		$start_timeframe = $_POST['start_date'] . ' 00:00:00';

		if ( !empty( $_POST['end_date'] ) ) {
			$end_timeframe = $_POST['end_date'] . ' 23:59:59';
		} else {
			$end_timeframe = date( 'Y-m-d', time() );
		}

		$query = 'SELECT `id`, `plan_id`, `amount`, `transaction_date`'
				. ' FROM #__acctexp_log_history'
				. ' WHERE transaction_date >= \'' . $start_timeframe . '\''
				. ' AND transaction_date <= \'' . $end_timeframe . '\''
				. ' ORDER BY transaction_date ASC'
				;
		$db->setQuery( $query );
		$entries = $db->loadResultArray();

		if ( empty( $entries ) ) {
			return "nothing to list";
		}

		$historylist = array();
		$groups = array();
		foreach ( $entries as $id ) {
			$entry = new logHistory( $db );
			$entry->load( $id );

			$refund = false;
			foreach ( $entry->response as $v ) {
				if ( $v == 'refund' ) {
					$refund = true;
				}
			}

			$date = date( 'Y-m-d', strtotime( $entry->transaction_date ) );

			$pgroups = ItemGroupHandler::parentGroups( $entry->plan_id );

			if ( !in_array( $pgroups[0], $groups ) ) {
				$groups[] = $pgroups[0];
			}

			if ( $refund ) {
				$historylist[$date]['amount'] -= $entry->amount;
				$historylist[$date]['groups'][$pgroups[0]]--;
			} else {
				$historylist[$date]['amount'] += $entry->amount;
				$historylist[$date]['groups'][$pgroups[0]]++;
			}
		}

		foreach ( $historylist as $date => $entry ) {
			ksort( $historylist[$date]['groups'] );
		}

		$return = "";

		$return .= '<table style="background-color: fff; width: 30%; margin: 0 auto; text-align: center !important; font-size: 180%;">';

		$groupnames = array();
		foreach ( $groups as $group ) {
			$groupnames[$group] = ItemGroupHandler::groupName( $group );
		}

		foreach ( $historylist as $date => $history ) {
			if ( date( 'D', strtotime( $date ) ) == 'Mon' ) {
				$week = array();
			}
			
			$return .= '<tr style="border-bottom: 2px solid #999 !important; height: 2em;">';

			$return .= '<td title="Date" style="text-align: left !important; color: #aaa;">' . $date . '</td>';
			$return .= '<td style="width: 5em;">&nbsp;</td>';

			foreach ( $groups as $group ) {
				if ( empty( $history['groups'][$group] ) ) {
					$count = 0;
				} else {
					$count = $history['groups'][$group];
				}

				$return .= '<td title="' . $groupnames[$group] . '" style="font-weight: bold; width: 5em;">' . $count . '</td>';

				if ( isset( $week ) ) {
					$week['groups'][$group] += $count;
				}
			}

			if ( isset( $week ) ) {
				$week['amount'] += $history['amount'];
			}

			$return .= '<td style="width: 5em;">&nbsp;</td>';
			$return .= '<td title="Amount" style="text-align: right !important; color: #608919;">' . AECToolbox::correctAmount( $history['amount'] ) . '</td>';
			$return .= '</tr>';

			$return .= '<tr style="height: 1px; background-color: #999;">';
			$return .= '<td colspan="' . ( count($groups) + 4 ) . '"></td>';
			$return .= '</tr>';

			if ( isset( $week ) && ( date( 'D', strtotime( $date ) ) == 'Sun' ) ) {
				$return .= '<tr style="border-bottom: 2px solid #999 !important; height: 2em; background-color: #ddd;">';

				$return .= '<td title="Date" style="text-align: left !important; color: #aaa;">Week</td>';
				$return .= '<td style="width: 5em;">&nbsp;</td>';

				foreach ( $groups as $group ) {
					if ( empty( $week['groups'][$group] ) ) {
						$count = 0;
					} else {
						$count = $week['groups'][$group];
					}

					$return .= '<td title="' . $groupnames[$group] . '" style="font-weight: bold; width: 5em;">' . $count . '</td>';
				}

				$return .= '<td style="width: 5em;">&nbsp;</td>';
				$return .= '<td title="Amount" style="text-align: right !important; color: #608919;">' . AECToolbox::correctAmount( $week['amount'] ) . '</td>';
				$return .= '</tr>';

				$return .= '<tr style="height: 1px; background-color: #999;">';
				$return .= '<td colspan="' . ( count($groups) + 4 ) . '"></td>';
				$return .= '</tr>';
			}

		}

		$return .= '</table><br /><br />';

		return $return;
	}

}
?>
