<?php
/**
 * @version $Id: tool_dbsearchreplace.php
 * @package AEC - Account Control Expiration - Membership Manager
 * @subpackage Toolbox - DB Search & Replace
 * @copyright 2012 Copyright (C) David Deutsch
 * @author David Deutsch <skore@valanx.org> & Team AEC - http://www.valanx.org
 * @license GNU/GPL v.3 http://www.gnu.org/licenses/gpl.html or, at your option, any later version
 */

// Dont allow direct linking
( defined('_JEXEC') || defined( '_VALID_MOS' ) ) or die( 'Direct Access to this location is not allowed.' );

class tool_dbsearchreplace
{
	function Info()
	{
		$info = array();
		$info['name'] = "Database Search&Replace";
		$info['desc'] = "Find and replace entries in the AEC database tables.";

		return $info;
	}

	function Settings()
	{
		$db = &JFactory::getDBO();

		$settings = array();

		$settings['type']	= array( 'list', 'Table', 'The tables you want to run the search&replace on' );

		$types = array(	'config' => 'Main Configuration',
						'processor' => 'Payment Processors',
						'coupons' => 'Coupons',
						'displaypipeline' => 'Display Pipeline',
						'eventlog' => 'Event Log',
						'invoice' => 'Invoice',
						'itemgroups' => 'Payment Plan Groups',
						'history' => 'History',
						'metauser' => 'MetaUser Information Table',
						'mi' => 'Micro Integrations',
						'plans' => 'Payment Plans',
						'subscr' => 'Subscriptions'
				);

		$typelist = array();
		foreach ( $types as $type => $typename ) {
			$typelist[] = JHTML::_('select.option', $type, $typename );
		}

		$settings['lists']['type'] = JHTML::_('select.genericlist', $typelist, 'type[]', 'size="1"', 'value', 'text', array());

		return $settings;
	}

	function Action()
	{
		if ( empty( $_POST['type'] ) || empty( $_POST['search'] ) ) ) {
			return "<h3>Incomplete Query.</h3>";
		}

		$db = &JFactory::getDBO();

		$types = array(	'config' => array( 'config', 'aecConfig' ),
				'processor' => array( 'config_processors', 'PaymentProcessor' ),
				'coupons' => array( 'coupons', 'Coupon' ),
				'displaypipeline' => array( 'displaypipeline', 'displayPipeline' ),
				'eventlog' => array( 'eventlog', 'eventLog' ),
				'invoice' => array( 'invoices', 'Invoice' ),
				'itemgroups' => array( 'itemgroups', 'ItemGroup' ),
				'history' => array( 'log_history', 'logHistory' ),
				'metauser' => array( 'metauser', 'metaUserDB' ),
				'mi' => array( 'microintegrations', 'microIntegration' ),
				'plans' => array( 'plans', 'SubscriptionPlan' ),
				'subscr' => array( 'subscr', 'Subscription' )
		);

		$changes = 0;
		foreach ( $_POST['type'] as $type ) {
			$query = 'SELECT `id` FROM `#__acctexp_' . $types[$type][0];

			$result = $db->query( $query );

			while ( $row = $db->loadAssoc($result) ) {
				$obj = new {$types[$type][1]}();
				$obj->load( $row[0] );

				if ( !empty( $_POST['armed'] ) && !empty( $_POST['replace'] ) {
					$mod = AECToolbox::searchreplaceObjectProperties( $obj, $_POST['search'], $_POST['replace'] );
					if ( $mod != $obj ) {
						$mod->check();
						$mod->store();

						$changes++;
					}
				} else {
					if ( AECToolbox::searchObjectProperties( $obj, $_POST['search'] ) ) {
						$changes++;
					}
				}
		}

		$return = '';
		$return .= "<h3>Query Result:</h3>";
		$return .= "<p>Searching for <strong>" . $_POST['search'] . "</strong></p>";
		$return .= "<p>Replacing it with <strong>" . $_POST['replace'] . "</strong></p>";

		$return .= "<p>Found <strong>" . $changes . "</strong> database entries.</p>";
		if ( $_POST['armed'] ) {
			$return .= "<p>Modified <strong>" . $changes . "</strong> database entries.</p>";
		}

		return $return;
	}

}

?>
