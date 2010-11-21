<?php
/**
 * @version $Id: tool_cleanup.php
 * @package AEC - Account Control Expiration - Membership Manager
 * @subpackage Toolbox - Cleanup
 * @copyright 2010 Copyright (C) David Deutsch
 * @author David Deutsch <skore@skore.de> & Team AEC - http://www.valanx.org
 * @license GNU/GPL v.2 http://www.gnu.org/licenses/old-licenses/gpl-2.0.html or, at your option, any later version
 */

// Dont allow direct linking
( defined('_JEXEC') || defined( '_VALID_MOS' ) ) or die( 'Direct Access to this location is not allowed.' );

class tool_cleanup
{
	function Info()
	{
		$info = array();
		$info['name'] = "System Cleanup";
		$info['desc'] = "Removes subscription data for users that have been deleted.";

		return $info;
	}

	function Settings()
	{
		$settings = array();
		$settings['delete']				= array( 'checkbox', 'Delete', 'Do the cleanup (this can delete a lot of data - do a check first)' );

		return $settings;
	}

	function Action( $request )
	{
		$db = &JFactory::getDBO();

		// Find all entries lacking an existing user account
		$tables = array( 'cart', 'couponsxuser', 'invoices', 'metauser', 'subscr' );

		$found = array( 'total' => 0 );
		foreach ( $tables as $table ) {
			$query = 'SELECT count(*)'
					. ' FROM #__acctexp_' . $table . ' AS a'
					. ' LEFT JOIN #__users AS b ON a.userid = b.id'
					. ' WHERE b.id is null'
					;
			$db->setQuery( $query );
			$count = $db->loadResult();

			$found[$table] = $count;
			$found['total'] += $count;
		}

		if ( !empty( $_POST['delete'] ) ) {
			$return = '<p>Deleted a total of ' . $found['total'] . ' entries.<p>'
					. '<ul>'
					;

			foreach ( $found as $table => $count ) {
				if ( ( $table != 'total' ) && $count ) {
					$query = 'SELECT a.id'
							. ' FROM #__acctexp_' . $table . ' AS a'
							. ' LEFT JOIN #__users AS b ON a.userid = b.id'
							. ' WHERE b.id is null'
							;
					$db->setQuery( $query );
					$ids = $db->loadResultArray();

					$query = 'DELETE'
							. ' FROM #__acctexp_' . $table
							. ' WHERE id IN (' . implode( ',', $ids ) . ')'
							;
					$db->setQuery( $query );
					$db->query();

					$return .= '<li>' . $count . ' entries in table ' . $table . '</li>';
				}
			}

			return $return;
		} else {
			$return = '<p>Found a total of ' . $found['total'] . ' entries.<p>'
					. '<ul>'
					;

			foreach ( $found as $table => $count ) {
				if ( ( $table != 'total' ) ) {
					$return .= '<li>' . $count . ' entries in table ' . $table . '</li>';
				}
			}

			return $return;
		}
	}

}
?>
