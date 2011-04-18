<?php
/**
 * @version $Id: tool_migrator.php
 * @package AEC - Account Control Expiration - Membership Manager
 * @subpackage Toolbox - Migrator
 * @copyright 2010 Copyright (C) David Deutsch
 * @author David Deutsch <skore@skore.de> & Team AEC - http://www.valanx.org
 * @license GNU/GPL v.2 http://www.gnu.org/licenses/old-licenses/gpl-2.0.html or, at your option, any later version
 */

// Dont allow direct linking
( defined('_JEXEC') || defined( '_VALID_MOS' ) ) or die( 'Direct Access to this location is not allowed.' );

class tool_migrator
{
	function Info()
	{
		$info = array();
		$info['name'] = "Migrator";
		$info['desc'] = "Migrate data from other membership or subscription components into AEC. Caution: It is unlikely that we can can migrate and emulate all data and/or behavior. We try our best, though.";

		return $info;
	}

	function Settings()
	{
		$settings = array();
		$settings['component']	= array( 'list', 'Component', 'Which component do you want to migrate from?' );

		$migrations = array( 'ambra' => 'Ambra Subscriptions', 'cbsubs' => 'CBsubs' );

		$component_list = array();
		foreach ( $migrations as $handle => $name ) {
			$component_list[] = JHTML::_('select.option', $handle, $name );
		}

		$settings['lists']['component'] = JHTML::_('select.genericlist', $component_list, 'component[]', 'size="1"', 'value', 'text', array());

		return $settings;
	}

	function Action()
	{
		$db = &JFactory::getDBO();

		// Find all entries lacking an existing user account
		$tables = array(	'cart' => 'userid',
							'couponsxuser' => 'userid',
							'invoices' => 'userid',
							'metauser' => 'userid',
							'subscr' => 'userid',
							'log_history' => 'user_id'
						);

		$found = array( 'total' => 0 );
		foreach ( $tables as $table => $key ) {
			$query = 'SELECT count(*)'
					. ' FROM #__acctexp_' . $table . ' AS a'
					. ' LEFT JOIN #__users AS b ON a.' . $key . ' = b.id'
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
