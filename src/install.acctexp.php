<?php
/**
 * @version $Id: install.acctexp.php 16 2007-06-27 09:04:04Z mic $
 * @package AEC - Account Control Expiration - Subscription component for Joomla! OS CMS
 * @subpackage Installation
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

function com_install()
{
	global $database, $mainframe, $mosConfig_absolute_path, $mosConfig_live_site, $mosConfig_dbprefix, $my;

	$queri		= NULL;
	$errors		= array();

	$pathLang = $mainframe->getCfg( 'absolute_path' ) . '/administrator/components/com_acctexp/com_acctexp_language_backend/';
	if ( file_exists( $pathLang . $mainframe->getCfg( 'lang' ) . '.php' ) ) {
		include_once( $pathLang . $mainframe->getCfg( 'lang' ) . '.php' );
	} else {
		include_once( $pathLang . 'english.php' );
	}

	include_once( $pathLang . 'general.php' );
	require_once( $mainframe->getPath( 'class', 'com_acctexp' ) );

	// in any case, delete an already existing menu entry
	$query = 'DELETE'
	. ' FROM #__menu'
	. ' WHERE link LIKE \'index.php?option=com_acctexp%\''
	;
	$database->setQuery( $query );
	$database->query();

	// first check if this is a new install (no older AEC tables are existing)
	$query = 'SELECT count(id)'
	. ' FROM #__acctexp'
	;
	$database->setQuery( $query );
	$total = $database->loadResult();

	if ( !$total ) {
		// must be new, go ahead
		$queri[] = 'CREATE TABLE IF NOT EXISTS `#__acctexp` ('
		. '`id` int(11) NOT NULL auto_increment,'
		. '`userid` int(11) NOT NULL default \'0\','
		. '`expiration` datetime NOT NULL default \'0000-00-00 00:00:00\','
		. ' PRIMARY KEY (`id`)'
		. ') TYPE=MyISAM AUTO_INCREMENT=1;'
		;

		$queri[] = 'CREATE TABLE IF NOT EXISTS `#__acctexp_config` ('
		. '`id` INT NOT NULL AUTO_INCREMENT,'
		. '`settings` text NULL,'
		. ' PRIMARY KEY (`id`)'
		. ') TYPE=MyISAM;'
		;

		$queri[] = 'CREATE TABLE IF NOT EXISTS `#__acctexp_heartbeat` ('
		. '`id` int(11) NOT NULL auto_increment,'
		. '`last_beat` datetime NOT NULL default \'0000-00-00 00:00:00\','
		. '	PRIMARY KEY (`id`)'
		. ') TYPE=MyISAM AUTO_INCREMENT=1;'
		;

		$queri[] = 'CREATE TABLE IF NOT EXISTS `#__acctexp_eventlog` ('
		. '`id` int(11) NOT NULL auto_increment,'
	    . '`datetime` datetime NULL default \'0000-00-00 00:00:00\','
		. '`short` varchar(60) NOT NULL,'
		. '`tags` text NULL,'
		. '`event` text NULL,'
		. '`params` text NULL,'
		. ' PRIMARY KEY (`id`)'
		. ') TYPE=MyISAM AUTO_INCREMENT=1;'
		;

		$queri[] = 'CREATE TABLE IF NOT EXISTS `#__acctexp_config_processors` ('
		. '`id` INT NOT NULL AUTO_INCREMENT,'
		. '`name` varchar(60) NOT NULL,'
		. '`active` int(4) NOT NULL default \'1\','
		. '`info` text NULL,'
		. '`settings` text NULL,'
		. '`params` text NULL,'
		. ' PRIMARY KEY (`id`)'
		. ') TYPE=MyISAM;'
		;

		$queri[] = 'CREATE TABLE IF NOT EXISTS `#__acctexp_invoices` ('
		. '`id` int(11) NOT NULL auto_increment,'
		. '`active` int(4) NOT NULL default \'1\','
		. '`userid` int(11) NULL default \'0\','
		. '`invoice_number` varchar(64) NULL,'
		. '`created_date` datetime NULL default \'0000-00-00 00:00:00\','
		. '`transaction_date` datetime NULL default \'0000-00-00 00:00:00\','
		. '`usage` varchar(255) NULL,'
		. '`method` varchar(40) NULL,'
		. '`amount` varchar(40) NULL,'
		. '`currency` varchar(10) NULL,'
		. '`fixed` int(4) NOT NULL default \'0\','
		. '`coupons` varchar(255) NULL,'
		. '`params` text NULL,'
		. ' PRIMARY KEY (`id`)'
		. ') TYPE=MyISAM;'
		;

		$queri[] = 'CREATE TABLE IF NOT EXISTS `#__acctexp_log_history` ('
		. '`id` int(11) NOT NULL auto_increment,'
		. '`proc_id` int(4) NULL,'
		. '`proc_name` varchar(100) NULL,'
		. '`user_id` int(4) NULL,'
		. '`user_name` varchar(100) NULL,'
		. '`plan_id` int(4) NULL,'
		. '`plan_name` varchar(100) NULL,'
		. '`transaction_date` datetime NULL default \'0000-00-00 00:00:00\','
		. '`amount` varchar(40) NULL,'
		. '`invoice_number` varchar(60) NULL,'
		. '`response` text NULL,'
		. ' PRIMARY KEY (`id`)'
		. ') TYPE=MyISAM;'
		;

		$queri[] = 'CREATE TABLE IF NOT EXISTS `#__acctexp_plans` ('
		. '`id` int(11) NOT NULL auto_increment,'
		. '`active` int(4) NOT NULL default \'1\','
		. '`visible` int(4) NOT NULL default \'1\','
		. '`ordering` int(11) NOT NULL default \'999999\','
		. '`name` varchar(40) NULL,'
		. '`desc` text NULL,'
		. '`email_desc` text NULL,'
		. '`email_desc_exp` text NULL,'
		. '`send_exp_mail` int(4) NOT NULL default \'0\','
		. '`params` text NULL,'
		. '`custom_params` text NULL,'
		. '`restrictions` text NULL,'
		. '`micro_integrations` text NULL,'
		. ' PRIMARY KEY (`id`)'
		. ') TYPE=MyISAM;'
		;

		$queri[] = 'CREATE TABLE IF NOT EXISTS `#__acctexp_microintegrations` ('
		. '`id` int(11) NOT NULL auto_increment,'
		. '`active` int(4) NOT NULL default \'1\','
		. '`system` int(4) NOT NULL default \'0\','
		. '`ordering` int(11) NOT NULL default \'999999\','
		. '`name` varchar(40) NULL,'
		. '`desc` text NULL,'
		. '`class_name` varchar(40) NULL,'
		. '`params` text NULL,'
		. '`auto_check` int(4) NOT NULL default \'0\','
		. '`on_userchange` int(4) NOT NULL default \'0\','
		. '`pre_exp_check` int(4) NULL,'
		. ' PRIMARY KEY (`id`)'
		. ') TYPE=MyISAM;'
		;

		$queri[] = 'CREATE TABLE IF NOT EXISTS `#__acctexp_subscr` ('
		. '`id` int(11) NOT NULL auto_increment,'
		. '`userid` int(11) NULL,'
		. '`type` varchar(40) NULL,'
		. '`status` varchar(10) NULL,'
		. '`signup_date` datetime NULL default \'0000-00-00 00:00:00\','
		. '`lastpay_date` datetime NULL default \'0000-00-00 00:00:00\','
		. '`cancel_date` datetime NULL default \'0000-00-00 00:00:00\','
		. '`eot_date` datetime NULL default \'0000-00-00 00:00:00\','
		. '`eot_cause` varchar(100) NULL,'
		. '`plan` int(11) NULL,'
		. '`previous_plan` int(11) NULL,'
		. '`used_plans` varchar(255) NULL,'
		. '`recurring` int(1) NOT NULL default \'0\','
		. '`lifetime` int(1) NOT NULL default \'0\','
		. '`email_sent` datetime NULL default \'0000-00-00 00:00:00\','
		. ' PRIMARY KEY (`id`)'
		. ') TYPE=MyISAM;'
		;

		$queri[] = 'CREATE TABLE IF NOT EXISTS `#__acctexp_coupons` ('
		. '`id` int(11) NOT NULL auto_increment,'
		. '`active` int(4) NOT NULL default \'1\','
		. '`ordering` int(11) NOT NULL default \'999999\','
		. '`coupon_code` varchar(255) NULL,'
	    . '`created_date` datetime NULL default \'0000-00-00 00:00:00\','
		. '`name` varchar(255) NULL,'
		. '`desc` text NULL,'
		. '`discount` text NULL,'
		. '`restrictions` text NULL,'
		. '`params` text NULL,'
		. '`usecount` int(64) NOT NULL default \'0\','
		. '`micro_integrations` text NULL,'
		. ' PRIMARY KEY (`id`)'
		. ') TYPE=MyISAM;'
		;

		$queri[] = 'CREATE TABLE IF NOT EXISTS `#__acctexp_coupons_static` ('
		. '`id` int(11) NOT NULL auto_increment,'
		. '`active` int(4) NOT NULL default \'1\','
		. '`ordering` int(11) NOT NULL default \'999999\','
		. '`coupon_code` varchar(255) NULL,'
	    . '`created_date` datetime NULL default \'0000-00-00 00:00:00\','
		. '`name` varchar(255) NULL,'
		. '`desc` text NULL,'
		. '`discount` text NULL,'
		. '`restrictions` text NULL,'
		. '`params` text NULL,'
		. '`usecount` int(64) NOT NULL default \'0\','
		. '`micro_integrations` text NULL,'
		. ' PRIMARY KEY (`id`)'
		. ') TYPE=MyISAM;'
		;

		foreach ( $queri AS $query ) {
			$database->setQuery( $query );
		    if ( !$database->query() ) {
		        $errors[] = array( $database->getErrorMsg(), $query );
		    }
		}

	} else {
		// mic: TODO: AEC is/was already installed, maybe we want to backup the files (not implemented yet)

		// Update routine 0.3.0 -> 0.6.0
		$tables	= array();
		$tables	= $database->getTableList();
		if ( in_array( $mosConfig_dbprefix . "acctexp_payplans", $tables ) ) {
			// Update routine 0.3.0 -> 0.6.0
			// Check for existence of 'gid' column on table #__acctexp_payplans
			// It is existent only from version 0.6.0
			$result = null;
			$database->setQuery("SHOW COLUMNS FROM #__acctexp_payplans LIKE 'gid'");
			$database->loadObject($result);
			if (strcmp($result->Field, 'gid') === 0) {
				// You're already running version 0.6.0 or later. No action required.
			} else {
				// You're not running version 0.6.0 or later. Update required.
				$database->setQuery("ALTER TABLE #__acctexp_payplans ADD `gid` int(3) default NULL");
				if ( !$database->query() ) {
			    	$errors[] = array( $database->getErrorMsg(), $query );
				}
			}
		}

		// Update routine 0.6.0 -> 0.8.0
		$queri	= null;
		$result = null;

		$database->setQuery("SHOW COLUMNS FROM #__acctexp_config LIKE 'alertlevel1'");
		$database->loadObject($result);

		if (!strcmp($result->Field, 'alertlevel1') === 0) {
			$queri[] = "ALTER TABLE #__acctexp_config DROP `email`";
			$queri[] = "ALTER TABLE #__acctexp_config DROP `paypal`";
			$queri[] = "ALTER TABLE #__acctexp_config DROP `business`";
			$queri[] = "ALTER TABLE #__acctexp_config DROP `testmode`";

			// Updating _config, adding alertlevel1, alertlevel2, alertlevel3
			$queri[] = "ALTER TABLE #__acctexp_config ADD `alertlevel1` int(6) default '7'";
			$queri[] = "ALTER TABLE #__acctexp_config ADD `alertlevel2` int(6) default '14'";
			$queri[] = "ALTER TABLE #__acctexp_config ADD `alertlevel3` int(6) default '21'";

			foreach ( $queri AS $query ) {
				$database->setQuery( $query );
			    if ( !$database->query() ) {
			        $errors[] = array( $database->getErrorMsg(), $query );
			    }
			}

			$queri = null;

			// Copy id -> id || active -> active || ordering -> ordering || name -> name || desc -> desc
			// a1 -> amount1 || p1 -> period1 || t1 -> perunit1
			// a2 -> amount2 || p2 -> period2 || t2 -> perunit2
			// a3 -> amount3 || p3 -> period3 || t3 -> perunit3
			// gid -> gid

			// Drop new table __acctexp_plans. We going to recreate it from old __acctexp_payplans
			$queri[] = "DROP TABLE  #__acctexp_plans";
			// Rename __acctexp_payplans to __acctexp_plans... Magic!!
			$queri[] = "ALTER TABLE #__acctexp_payplans RENAME TO #__acctexp_plans";
			// Get rid of old stuff.
			$queri[] = "ALTER TABLE #__acctexp_plans DROP `button`";
			$queri[] = "ALTER TABLE #__acctexp_plans DROP `button_custom`";
			$queri[] = "ALTER TABLE #__acctexp_plans DROP `src`";
			$queri[] = "ALTER TABLE #__acctexp_plans DROP `sra`";
			$queri[] = "ALTER TABLE #__acctexp_plans DROP `srt`";
			$queri[] = "ALTER TABLE #__acctexp_plans DROP `invoice`";
			$queri[] = "ALTER TABLE #__acctexp_plans DROP `tax`";
			$queri[] = "ALTER TABLE #__acctexp_plans DROP `currency_code`";
			$queri[] = "ALTER TABLE #__acctexp_plans DROP `modify`";
			$queri[] = "ALTER TABLE #__acctexp_plans DROP `lc`";
			$queri[] = "ALTER TABLE #__acctexp_plans DROP `page_style`";
			// And rename fields that shoudl remain, but with generic names.
			$queri[] = "ALTER TABLE #__acctexp_plans CHANGE `a1` `amount1` varchar(40) NULL";
			$queri[] = "ALTER TABLE #__acctexp_plans CHANGE `a2` `amount2` varchar(40) NULL";
			$queri[] = "ALTER TABLE #__acctexp_plans CHANGE `a3` `amount3` varchar(40) NULL";
			$queri[] = "ALTER TABLE #__acctexp_plans CHANGE `p1` `period1` varchar(40) NULL";
			$queri[] = "ALTER TABLE #__acctexp_plans CHANGE `p2` `period2` varchar(40) NULL";
			$queri[] = "ALTER TABLE #__acctexp_plans CHANGE `p3` `period3` varchar(40) NULL";
			$queri[] = "ALTER TABLE #__acctexp_plans CHANGE `t1` `perunit1` varchar(40) NULL";
			$queri[] = "ALTER TABLE #__acctexp_plans CHANGE `t2` `perunit2` varchar(40) NULL";
			$queri[] = "ALTER TABLE #__acctexp_plans CHANGE `t3` `perunit3` varchar(40) NULL";
			// Drop new table __acctexp_log_paypal. We going to recreate it from old __acctexp_paylog
			$queri[] = "DROP TABLE  #__acctexp_log_paypal";
			// Rename __acctexp_paylog to __acctexp_log_paypal...
			$queri[] = "ALTER TABLE #__acctexp_paylog RENAME TO #__acctexp_log_paypal";

			foreach ( $queri AS $query ) {
				$database->setQuery( $query );
			    if ( !$database->query() ) {
			        $errors[] = array( $database->getErrorMsg(), $query );
			    }
			}

			// Associate all those old plans with PayPal processors.
			$database->setQuery("SELECT id FROM  #__acctexp_plans");
			$rows = $database->loadObjectList();
			for ($i=0, $n=count( $rows ); $i < $n; $i++) {
				$row = &$rows[$i];
				$database->setQuery("INSERT INTO `#__acctexp_processors_plans` VALUES ($row->id, '1')");
				if ( !$database->query() ) {
			    	$errors[] = array( $database->getErrorMsg(), $query );
				}
			}

			// Configure extra01 field indicating recurring subscriptions...
			$database->setQuery("UPDATE #__acctexp_subscr SET extra01='1' WHERE extra01 is NULL");
			if ( !$database->query() ) {
		    	$errors[] = array( $database->getErrorMsg(), $query );
			}

		} else {
			// You're running version 0.8.0 or later. No Update required here.
		}

		if ( in_array( $mosConfig_dbprefix . "acctexp_log_paypal", $tables ) ) {

			$queri = null;

			$queri[] = "DROP TABLE  #__acctexp_log_paypal";
			$queri[] = "DROP TABLE  #__acctexp_log_2checkout";
			$queri[] = "DROP TABLE  #__acctexp_log_allopass";
			$queri[] = "DROP TABLE  #__acctexp_log_authorize";
			$queri[] = "DROP TABLE  #__acctexp_log_vklix";

			foreach ( $queri AS $query ) {
				$database->setQuery( $query );
			    if ( !$database->query() ) {
			        $errors[] = array( $database->getErrorMsg(), $query );
			    }
			}

			// Update routine 0.8.0x -> 1.0.0 RC1
			$result = null;
			$database->setQuery("SHOW COLUMNS FROM #__acctexp_plans LIKE 'entry'");
			$database->loadObject($result);

			if (strcmp($result->Field, 'entry') === 0) {
				$database->setQuery("ALTER TABLE #__acctexp_plans DROP `entry`");
				if ( !$database->query() ) {
			    	$errors[] = array( $database->getErrorMsg(), $query );
				}
			} else {
				$database->setQuery("ALTER TABLE #__acctexp_invoices CHANGE `invoice_number` `invoice_number` varchar(64) NULL");
				if ( !$database->query() ) {
			    	$errors[] = array( $database->getErrorMsg(), $query );
				}
			}

			$result = null;
			$database->setQuery("SHOW COLUMNS FROM #__acctexp_plans LIKE 'desc'");
			$database->loadObject($result);
			if ( (strcmp($result->Field, 'desc') === 0) && (strcmp($result->Type, 'varchar(255)') === 0) ) {
				// Give extra space for plan description
				$database->setQuery("ALTER TABLE #__acctexp_plans CHANGE `desc` `desc` text NULL");
				if ( !$database->query() ) {
			    	$errors[] = array( $database->getErrorMsg(), $query );
				}
			}

			$result = null;
			$database->setQuery("SHOW COLUMNS FROM #__acctexp_plans LIKE 'lifetime'");
			$database->loadObject($result);
			if (!(strcmp($result->Field, 'lifetime') === 0)) {
				$database->setQuery("ALTER TABLE #__acctexp_plans ADD `lifetime` int(4) default '0'");
				if ( !$database->query() ) {
			    	$errors[] = array( $database->getErrorMsg(), $query );
				}
			}

			$result = null;
			$database->setQuery("SHOW COLUMNS FROM #__acctexp_subscr LIKE 'lifetime'");
			$database->loadObject($result);
			if (!(strcmp($result->Field, 'lifetime') === 0)) {
				$database->setQuery("ALTER TABLE #__acctexp_subscr CHANGE `extra05` `lifetime` int(1) default '0'");
				if ( !$database->query() ) {
			    	$errors[] = array( $database->getErrorMsg(), $query );
				}
			}

			$result = null;
			$database->setQuery("SHOW COLUMNS FROM #__acctexp_plans LIKE 'mingid'");
			$database->loadObject($result);
			if (!(strcmp($result->Field, 'mingid') === 0)) {
				$database->setQuery("ALTER TABLE #__acctexp_plans ADD `mingid` int(3) default '0'");
				if ( !$database->query() ) {
			    	$errors[] = array( $database->getErrorMsg(), $query );
				}
			}

			$result = null;
			$database->setQuery("SHOW COLUMNS FROM #__acctexp_plans LIKE 'similarpg'");
			$database->loadObject($result);
			if (!(strcmp($result->Field, 'similarpg') === 0)) {
				$database->setQuery("ALTER TABLE #__acctexp_plans ADD `similarpg` varchar(255) NULL");
				if ( !$database->query() ) {
			    	$errors[] = array( $database->getErrorMsg(), $query );
				}
			}

			$result = null;
			$database->setQuery("SHOW COLUMNS FROM #__acctexp_plans LIKE 'equalpg'");
			$database->loadObject($result);
			if (!(strcmp($result->Field, 'equalpg') === 0)) {
				$database->setQuery("ALTER TABLE #__acctexp_plans ADD `equalpg` varchar(255) NULL");
				if ( !$database->query() ) {
			    	$errors[] = array( $database->getErrorMsg(), $query );
				}
			}

			$result = null;
			$database->setQuery("SHOW COLUMNS FROM #__acctexp_subscr LIKE 'previous_plan'");
			$database->loadObject($result);
			if (!(strcmp($result->Field, 'previous_plan') === 0)) {
				$database->setQuery("ALTER TABLE #__acctexp_subscr ADD `previous_plan` int(11) NULL");
				if ( !$database->query() ) {
			    	$errors[] = array( $database->getErrorMsg(), $query );
				}
			}

			$result = null;
			$database->setQuery("SHOW COLUMNS FROM #__acctexp_subscr LIKE 'used_plans'");
			$database->loadObject($result);
			if (!(strcmp($result->Field, 'used_plans') === 0)) {
				$database->setQuery("ALTER TABLE #__acctexp_subscr ADD `used_plans` varchar(255) NULL");
				if ( !$database->query() ) {
			    	$errors[] = array( $database->getErrorMsg(), $query );
				}
			}

			$result = null;
			$database->setQuery("SHOW COLUMNS FROM #__acctexp_subscr LIKE 'email_sent'");
			$database->loadObject($result);
			if (!(strcmp($result->Field, 'email_sent') === 0)) {
				$database->setQuery("ALTER TABLE #__acctexp_subscr ADD `email_sent` datetime default '0000-00-00 00:00:00'");
				if ( !$database->query() ) {
			    	$errors[] = array( $database->getErrorMsg(), $query );
				}
			}

			$result = null;
			$database->setQuery("SHOW COLUMNS FROM #__acctexp_plans LIKE 'email_desc_exp'");
			$database->loadObject($result);
			if (!(strcmp($result->Field, 'email_desc_exp') === 0)) {
				$database->setQuery("ALTER TABLE #__acctexp_plans ADD `email_desc_exp` text NULL");
				if ( !$database->query() ) {
			    	$errors[] = array( $database->getErrorMsg(), $query );
				}
			}

			$result = null;
			$database->setQuery("SHOW COLUMNS FROM #__acctexp_plans LIKE 'desc_email'");
			$database->loadObject($result);
			if (strcmp($result->Field, 'desc_email') === 0) {
				$database->setQuery("ALTER TABLE #__acctexp_plans DROP `desc_email`");
				if ( !$database->query() ) {
			    	$errors[] = array( $database->getErrorMsg(), $query );
				}
			}

			$result = null;
			$database->setQuery("SHOW COLUMNS FROM #__acctexp_plans LIKE 'email_desc'");
			$database->loadObject($result);
			if (!(strcmp($result->Field, 'email_desc') === 0)) {
				$database->setQuery("ALTER TABLE #__acctexp_plans ADD `email_desc` text NULL");
				if ( !$database->query() ) {
			    	$errors[] = array( $database->getErrorMsg(), $query );
				}
			}

			$result = null;
			$database->setQuery("SHOW COLUMNS FROM #__acctexp_plans LIKE 'send_exp_mail'");
			$database->loadObject($result);
			if (!(strcmp($result->Field, 'send_exp_mail') === 0)) {
				$database->setQuery("ALTER TABLE #__acctexp_plans ADD `send_exp_mail` int(4) default '0'");
				if ( !$database->query() ) {
			    	$errors[] = array( $database->getErrorMsg(), $query );
				}
			}

			$result = null;
			$database->setQuery("SHOW COLUMNS FROM #__acctexp_plans LIKE 'fallback'");
			$database->loadObject($result);
			if ( !( strcmp( $result->Field, 'fallback') === 0 ) ) {
				$database->setQuery("ALTER TABLE #__acctexp_plans ADD `fallback` int(11) NULL");
				if ( !$database->query() ) {
			    	$errors[] = array( $database->getErrorMsg(), $query );
				}
			}

			$result = null;
			$database->setQuery("SHOW COLUMNS FROM #__acctexp_invoices LIKE 'coupons'");
			$database->loadObject($result);
			if (!(strcmp($result->Field, 'coupons') === 0)) {
				$database->setQuery("ALTER TABLE #__acctexp_invoices ADD `coupons` varchar(255) NULL");
				if ( !$database->query() ) {
			    	$errors[] = array( $database->getErrorMsg(), $query );
				}
			}

			$result = null;
			$database->setQuery("SHOW COLUMNS FROM #__acctexp_plans LIKE 'maxgid'");
			$database->loadObject($result);
			if (!(strcmp($result->Field, 'maxgid') === 0)) {
				$database->setQuery("ALTER TABLE #__acctexp_plans ADD `maxgid` int(3) default '0'");
				if ( !$database->query() ) {
			    	$errors[] = array( $database->getErrorMsg(), $query );
				}
			}

			$result = null;
			$database->setQuery("SHOW COLUMNS FROM #__acctexp_plans LIKE 'micro_integrations'");
			$database->loadObject($result);
			if (!(strcmp($result->Field, 'micro_integrations') === 0)) {
				$database->setQuery("ALTER TABLE #__acctexp_plans ADD `micro_integrations` text NULL");
				if ( !$database->query() ) {
			    	$errors[] = array( $database->getErrorMsg(), $query );
				}
			}

			$result = null;
			$database->setQuery("SHOW COLUMNS FROM #__acctexp_invoices LIKE 'params'");
			$database->loadObject($result);
			if (!(strcmp($result->Field, 'params') === 0)) {
				$database->setQuery("ALTER TABLE #__acctexp_invoices ADD `params` text NULL");
				if ( !$database->query() ) {
			    	$errors[] = array( $database->getErrorMsg(), $query );
				}
			}

			$result = null;
			$database->setQuery("SHOW COLUMNS FROM #__acctexp_invoices LIKE 'fixed'");
			$database->loadObject($result);

			if (!(strcmp($result->Field, 'fixed') === 0)) {
				$query = "ALTER TABLE #__acctexp_invoices ADD `fixed` int(4) default '0'";
			} elseif ( (strcmp($result->Field, 'fixed') === 0) && (strcmp($result->Type, 'int(4)') === 0) && $result->Null ) {
				$query = "ALTER TABLE #__acctexp_invoices CHANGE `fixed` `fixed` int(4) default '0'";
			} elseif ( (strcmp($result->Field, 'fixed') === 0) && (strcmp($result->Type, "int(4)") === 0) && $result->Default ) {
				$query = "ALTER TABLE #__acctexp_invoices CHANGE `fixed` `fixed` int(4) default '0'";
			}

			$database->setQuery( $query );
			if ( !$database->query() ) {
		    	$errors[] = array( $database->getErrorMsg(), $query );
			}

			$result = null;
			$database->setQuery("SHOW COLUMNS FROM #__acctexp_plans LIKE 'visible'");
			$database->loadObject($result);
			if (!(strcmp($result->Field, 'visible') === 0)) {
				$database->setQuery("ALTER TABLE #__acctexp_plans ADD `visible` int(4) default '1'");
				if ( !$database->query() ) {
			    	$errors[] = array( $database->getErrorMsg(), $query );
				}
			}

			$result = null;
			$database->setQuery("SHOW COLUMNS FROM #__acctexp_plans LIKE 'recurring'");
			$database->loadObject($result);
			if (strcmp($result->Field, 'recurring') === 0) {
				$database->setQuery("ALTER TABLE #__acctexp_plans DROP `recurring`");
				if ( !$database->query() ) {
			    	$errors[] = array( $database->getErrorMsg(), $query );
				}
			}

			$result = null;
			$database->setQuery("SHOW COLUMNS FROM #__acctexp_microintegrations LIKE 'on_userchange'");
			$database->loadObject($result);
			if ( !( strcmp( $result->Field, 'on_userchange') === 0 ) ) {
				$database->setQuery("ALTER TABLE #__acctexp_microintegrations ADD `on_userchange` int(4) default '0'");
				if ( !$database->query() ) {
			    	$errors[] = array( $database->getErrorMsg(), $query );
				}
			}

			$result = null;
			$database->setQuery("SHOW COLUMNS FROM #__acctexp_invoices LIKE 'created_date'");
			$database->loadObject($result);
			if (!(strcmp($result->Field, 'created_date') === 0)) {
				$database->setQuery("ALTER TABLE #__acctexp_invoices ADD `created_date` datetime NULL default '0000-00-00 00:00:00'");
				if ( !$database->query() ) {
			    	$errors[] = array( $database->getErrorMsg(), $query );
				}
			}

			$result = null;
			$database->setQuery("SHOW COLUMNS FROM #__acctexp_invoices LIKE 'planid'");
			$database->loadObject($result);
			if (strcmp($result->Field, 'planid') === 0) {
				$database->setQuery("ALTER TABLE #__acctexp_invoices CHANGE `planid` `usage` varchar(255) NULL");
				if ( !$database->query() ) {
			    	$errors[] = array( $database->getErrorMsg(), $query );
				}
			} else {
				$database->setQuery("ALTER TABLE #__acctexp_invoices ADD `usage` varchar(255) NULL");
				if ( !$database->query() ) {
			    	$errors[] = array( $database->getErrorMsg(), $query );
				}
			}

			$result = null;
			$database->setQuery("SHOW COLUMNS FROM #__acctexp_plans LIKE 'reuse'");
			$database->loadObject($result);
			if ((strcmp($result->Field, 'reuse') === 0)) {
				$database->setQuery("ALTER TABLE #__acctexp_plans DROP COLUMN `reuse`");
				if ( !$database->query() ) {
			    	$errors[] = array( $database->getErrorMsg(), $query );
				}
			}

			$result = null;
			$database->setQuery("SHOW COLUMNS FROM #__acctexp_plans LIKE 'processors'");
			$database->loadObject($result);
			if (!(strcmp($result->Field, 'processors') === 0)) {
				$database->setQuery("ALTER TABLE #__acctexp_plans ADD `processors` varchar(255) NULL");
				if ( !$database->query() ) {
			    	$errors[] = array( $database->getErrorMsg(), $query );
				}
			}

			$result = null;
			$database->setQuery("SHOW COLUMNS FROM #__acctexp_invoices LIKE 'active'");
			$database->loadObject($result);
			if (!(strcmp($result->Field, 'active') === 0)) {
				$database->setQuery("ALTER TABLE #__acctexp_invoices ADD `active` int(4) default '1'");
				if ( !$database->query() ) {
			    	$errors[] = array( $database->getErrorMsg(), $query );
				}
			}

			$result = null;
			$database->setQuery("SHOW COLUMNS FROM #__acctexp_subscr LIKE 'extra01'");
			$database->loadObject($result);

			if (strcmp($result->Field, 'extra01') === 0) {

				$queri = null;
				$queri[] = "ALTER TABLE #__acctexp_subscr CHANGE `extra01` `recurring` int(1) NOT NULL default '0'";
				$queri[] = "ALTER TABLE #__acctexp_subscr DROP `extra02`";
				$queri[] = "ALTER TABLE #__acctexp_subscr DROP `extra03`";
				$queri[] = "ALTER TABLE #__acctexp_subscr DROP `extra04`";

				foreach ( $queri AS $query ) {
					$database->setQuery( $query );
				    if ( !$database->query() ) {
				        $errors[] = array( $database->getErrorMsg(), $query );
				    }
				}
			}

			$database->setQuery("SELECT count(*) FROM  #__acctexp_config_processors");
			if ($database->loadResult() == 0) {

				$database->setQuery( "SELECT proc_id FROM #__acctexp_processors_plans" );
				$db_processors = $database->loadResultArray();

				if ( is_array( $db_processors ) ) {
					$used_processors = array_unique($db_processors);

					$legacy_processors_db = array("", "paypal", "vklix", "authorize", "allopass", "2checkout", "epsnetpay", "paysignet", "worldpay", "alertpay");
					$legacy_processors_name = array("", "paypal", "viaklix", "authorize", "allopass", "2checkout", "epsnetpay", "paysignet", "worldpay", "alertpay");

					foreach ( $used_processors AS $i => $n ) {

						$old_cfg = null;
						$database->setQuery( "SELECT * FROM #__acctexp_config_" . $legacy_processors_db[$n] );
						$database->loadObject($old_cfg);

						$pp = new PaymentProcessor();
						$pp->loadName($legacy_processors_name[$n]);
						$pp->init();

						switch ($legacy_processors_name[$n]) {
							case 'paypal':
								$pp->settings['business']		= $old_cfg->business;
								$pp->settings['testmode']		= $old_cfg->testmode;
								$pp->settings['tax']			= $old_cfg->tax;
								$pp->settings['currency']		= $old_cfg->currency_code;
								$pp->settings['checkbusiness']	= $old_cfg->checkbusiness;
								$pp->settings['lc']				= $old_cfg->lc;
								$pp->settings['altipnurl']		= $old_cfg->altipnurl;
								$pp->setSettings();

								$pp = new PaymentProcessor();
								$pp->loadName("paypal_subscription");
								$pp->init();
								$pp->settings['business']		= $old_cfg->business;
								$pp->settings['testmode']		= $old_cfg->testmode;
								$pp->settings['tax']			= $old_cfg->tax;
								$pp->settings['currency']		= $old_cfg->currency_code;
								$pp->settings['checkbusiness']	= $old_cfg->checkbusiness;
								$pp->settings['lc']				= $old_cfg->lc;
								$pp->settings['altipnurl']		= $old_cfg->altipnurl;

								break;
							case 'viaklix':
								$pp->settings['accountid']		= $old_cfg->accountid;
								$pp->settings['userid']			= $old_cfg->userid;
								$pp->settings['pin']			= $old_cfg->pin;
								$pp->settings['testmode']		= $old_cfg->testmode;
								break;
							case 'authorize':
								$pp->settings['login']			= $old_cfg->login;
								$pp->settings['transaction_key'] = $old_cfg->transaction_key;
								$pp->settings['testmode']		= $old_cfg->testmode;
								$pp->settings['currency']		= $old_cfg->currency_code;
								break;
							case 'allopass':
								$pp->settings['siteid']			= $old_cfg->siteid;
								$pp->settings['docid']			= $old_cfg->docid;
								$pp->settings['auth']			= $old_cfg->auth;
								$pp->settings['testmode']		= $old_cfg->testmode;
								break;
							case '2checkout':
								$pp->settings['sid']			= $old_cfg->sid;
								$pp->settings['secret_word']	= $old_cfg->secret_word;
								$pp->settings['alt2courl']		= $old_cfg->alt2courl;
								$pp->settings['testmode']		= $old_cfg->testmode;
								break;
							case 'paysignet':
								$pp->settings['merchant']		= $old_cfg->merchant;
								$pp->settings['testmode']		= $old_cfg->testmode;
								break;
							case 'worldpay':
								$pp->settings['instId']			= $old_cfg->instId;
								$pp->settings['testmode']		= $old_cfg->testmode;
								$pp->settings['currency']		= $old_cfg->currency_code;
								break;
							case 'alertpay':
								$pp->settings['merchant']		= $old_cfg->ap_merchant;
								$pp->settings['securitycode']	= $old_cfg->ap_securitycode;
								$pp->settings['tax']			= $old_cfg->tax;
								$pp->settings['testmode']		= $old_cfg->testmode;
								break;
							default:
								break;
						}

						$pp->setSettings();
					}

					$database->setQuery( "SELECT * FROM #__acctexp_processors_plans" );
					$procplans = $database->loadObjectList();

					foreach ($procplans as $planentry) {
						$database->setQuery( "SELECT processors FROM #__acctexp_plans WHERE id='" . $planentry->plan_id . "'" );
						$plan_procs = explode(";", $database->loadResult());

						if (count($plan_procs) && $plan_procs[0]) {
							if (!in_array($planentry->proc_id, $plan_procs)) {
								$database->setQuery( "SELECT id FROM #__acctexp_config_processors WHERE name='" . $legacy_processors_name[$planentry->proc_id] . "'" );
								$proc_realid = $database->loadResult();

								if (($proc_realid > 0) &&!is_null($proc_realid)) {
									$plan_procs[] = $proc_realid;
								}
							}
						} else {
							// Re-init to prevent null entries
							$plan_procs = array();
							$database->setQuery( "SELECT id FROM #__acctexp_config_processors WHERE name='" . $legacy_processors_name[$planentry->proc_id] . "'" );
							$proc_realid = $database->loadResult();

							if (($proc_realid > 0) &&!is_null($proc_realid)) {
								$plan_procs[] = $proc_realid;
							}
						}

						if (count($plan_procs)) {
							$database->setQuery( "UPDATE #__acctexp_plans SET processors='" . implode(";", $plan_procs) . "' WHERE id='" . $planentry->plan_id . "'" );
							if ( !$database->query() ) {
						    	$errors[] = array( $database->getErrorMsg(), $query );
							}
						}
					}
				}

				$queri = null;

				$queri[] = "DROP TABLE IF EXISTS #__acctexp_processors_plans";
				$queri[] = "DROP TABLE IF EXISTS #__acctexp_processors";
				$queri[] = "DROP TABLE IF EXISTS #__acctexp_config_paypal";
				$queri[] = "DROP TABLE IF EXISTS #__acctexp_config_vklix";
				$queri[] = "DROP TABLE IF EXISTS #__acctexp_config_authorize";
				$queri[] = "DROP TABLE IF EXISTS #__acctexp_config_allopass";
				$queri[] = "DROP TABLE IF EXISTS #__acctexp_config_2checkout";
				$queri[] = "DROP TABLE IF EXISTS #__acctexp_config_epsnetpay";
				$queri[] = "DROP TABLE IF EXISTS #__acctexp_config_paysignet";
				$queri[] = "DROP TABLE IF EXISTS #__acctexp_config_worldpay";
				$queri[] = "DROP TABLE IF EXISTS #__acctexp_config_alertpay";

				foreach ( $queri AS $query ) {
					$database->setQuery( $query );
				    if ( !$database->query() ) {
				        $errors[] = array( $database->getErrorMsg(), $query );
				    }
				}
			}
		}
	} // end update

	// go ahead if no erros occured and add menu entries
	if ( !$errors ) {

		// delete first old menu entries
		$query = 'DELETE *'
		. ' FROM #__components'
		. ' WHERE option = \'option=com_acctexp\''
		;
		$database->setQuery( $query );
		$database->query();

		// insert first component entry
		$query = 'INSERT INTO #__components VALUES '
		. '(\'\', \'' . _AEC_INST_MAIN_COMP_ENTRY . '\', \'\', 0, \'\', \'option=com_acctexp&task=showCentral\', \''
		. _AEC_INST_MAIN_COMP_ENTRY . '\', \'com_acctexp\', 0,'
		. ' \'../administrator/components/com_acctexp/images/icons/aec_logo_tiny.png\', 0, \'\')'
		;

		$database->setQuery( $query );
		if ( !$database->query() ) {
	    	$errors[] = array( $database->getErrorMsg(), $query );
		}

		// get id from first entry
		$query = 'SELECT id'
		. ' FROM #__components'
		. ' WHERE link = \'option=com_acctexp\''
		;
		$database->setQuery( $query );
		$database->query();
		$id = $database->insertid();

		// insert components | image | task | menutext | menuid
		$menu = array();
		$menu[] = array( 'logo',			'showCentral',			_AEC_CENTR_CENTRAL,			0 );
		$menu[] = array( 'symbol_plans',	'showSubscriptionPlans',_AEC_CENTR_PLANS,			1 );
		$menu[] = array( 'symbol_active',	'showActive',			_AEC_CENTR_ACTIVE,			2 );
		$menu[] = array( 'symbol_pending',	'showPending',			_AEC_CENTR_PENDING,			3 );
		$menu[] = array( 'symbol_cancelled','showCancelled',		_AEC_CENTR_CANCELLED,		4 );
		$menu[] = array( 'symbol_closed',	'showClosed',			_AEC_CENTR_CLOSED,			5 );
		$menu[] = array( 'symbol_excluded',	'showExcluded',			_AEC_CENTR_EXCLUDED,		6 );
		$menu[] = array( 'symbol_manual',	'showManual',			_AEC_CENTR_MANUAL,			7 );
		$menu[] = array( 'symbol_mi',		'showMicroIntegrations',_AEC_CENTR_M_INTEGRATION,	8 );
		$menu[] = array( 'symbol_settings',	'showSettings',			_AEC_CENTR_SETTINGS,		9 );
		$menu[] = array( 'symbol_css',		'editCSS',				_AEC_CENTR_EDIT_CSS,		10 );
		$menu[] = array( 'symbol_hacks',	'hacks',				_AEC_CENTR_SPECIAL,			11 );
		$menu[] = array( 'symbol_help',		'help',					_AEC_CENTR_HELP,			12 );

		for( $i = 0; $i < count( $menu ); $i++ ) {
			$query = 'INSERT INTO #__components VALUES '
			. '(\'\', \'' . $menu[$i][2] . '\', \'\', ' . $menu[$i][3] . ', '
			. $id . ', \'option=com_acctexp&task=' . $menu[$i][1] . '\', \''
			. $menu[$i][2] . '\', \'com_acctexp\', ' . $menu[$i][3] . ','
			. ' \'../administrator/components/com_acctexp/images/icons/aec_'
			. $menu[$i][0] . '_tiny.png\', 0, \'\')'
			;

			$database->setQuery( $query );
			if ( !$database->query() ) {
		    	$errors[] = array( $database->getErrorMsg(), $query );
			}
		}

		/**
		 * initialize new database
		 */
		// now set some standard values


		$result = null;

		$database->setQuery( "SHOW COLUMNS FROM #__acctexp_config LIKE 'settings'" );
		$database->loadObject($result);

		if ( !( strcmp( $result->Field, 'settings' ) === 0 ) ) {
			$columns = array("transferinfo", "initialexp", "alertlevel1", "alertlevel2", "alertlevel3", "gwlist", "customintro", "customthanks", "customcancel", "bypassintegration", "simpleurls", "expiration_cushion", "currency_code", "heartbeat_cycle", "tos", "require_subscription", "entry_plan", "plans_first", "transfer", "checkusername", "activate_paid" );

			$newcfg = array();
			foreach ($columns as $column) {
				$result = null;
				$database->setQuery("SHOW COLUMNS FROM #__acctexp_config LIKE '" . $column . "'");
				$database->loadObject($result);
				if (strcmp($result->Field, $column) === 0) {
					$database->setQuery( "SELECT " . $column . " FROM #__acctexp_config WHERE id='1'" );
					$newcfg[$column] = $database->loadResult();

					$database->setQuery("ALTER TABLE #__acctexp_config DROP COLUMN " . $column);
					if ( !$database->query() ) {
				    	$errors[] = array( $database->getErrorMsg(), $query );
					}
				}
			}

			$database->setQuery("ALTER TABLE #__acctexp_config ADD `settings` text");
			if ( !$database->query() ) {
		    	$errors[] = array( $database->getErrorMsg(), $query );
			}

			$cfg = new Config_General($database);
			$cfg->cfg = $newcfg;
			$cfg->saveSettings();
		}

		// create settings parameters that got added in this version
		$cfg = new Config_General($database);
		$cfg->initParams();
		$cfg->saveSettings();

		$result = null;
		$database->setQuery("SHOW COLUMNS FROM #__acctexp_plans LIKE 'maxgid'");
		if ( $database->loadObject( $result ) ) {
			if ( strcmp( $result->Field, 'maxgid' ) === 0 ) {
				$database->setQuery("ALTER TABLE #__acctexp_plans DROP COLUMN `maxgid`");
				if ( !$database->query() ) {
			    	$errors[] = array( $database->getErrorMsg(), $query );
				}
			}
		}

		$result = null;
		$database->setQuery("SHOW COLUMNS FROM #__acctexp_plans LIKE 'email_desc_exp'");
		if ( $database->loadObject( $result ) ) {
			if ( strcmp( $result->Field, 'email_desc_exp' ) === 0 ) {
				$database->setQuery("ALTER TABLE #__acctexp_plans DROP COLUMN `email_desc_exp`");
				if ( !$database->query() ) {
			    	$errors[] = array( $database->getErrorMsg(), $query );
				}
			}
		}

		$result = null;
		$database->setQuery("SHOW COLUMNS FROM #__acctexp_plans LIKE 'send_exp_mail'");
		if ( $database->loadObject($result) ) {
			if ( strcmp( $result->Field, 'send_exp_mail' ) === 0 ) {
				$database->setQuery("ALTER TABLE #__acctexp_plans DROP COLUMN `send_exp_mail`");
				if ( !$database->query() ) {
			    	$errors[] = array( $database->getErrorMsg(), $query );
				}
			}
		}

		// check for old values and update (if happen) old tables
		$result = null;
		$database->setQuery("SHOW COLUMNS FROM #__acctexp_plans LIKE 'params'");
		$database->loadObject($result);

		if ( !( strcmp( $result->Field, 'params' ) === 0 ) ) {
			$result = null;
			$database->setQuery("SHOW COLUMNS FROM #__acctexp_plans LIKE 'mingid'");
			$database->loadObject($result);

			if ( strcmp( $result->Field, 'mingid' ) === 0 ) {
				$database->setQuery("ALTER TABLE #__acctexp_plans ADD `restrictions` text NULL");
				if ( !$database->query() ) {
			    	$errors[] = array( $database->getErrorMsg(), $query );
				}

				$database->setQuery("ALTER TABLE #__acctexp_plans ADD `params` text NULL");
				if ( !$database->query() ) {
			    	$errors[] = array( $database->getErrorMsg(), $query );
				}

				$remap_params = array();
				$remap_params["amount1"]	= "trial_amount";
				$remap_params["period1"]	= "trial_period";
				$remap_params["perunit1"]	= "trial_periodunit";
				$remap_params["amount2"]	= "trial2_amount";
				$remap_params["period2"]	= "trial2_period";
				$remap_params["perunit2"]	= "trial2_periodunit";
				$remap_params["amount3"]	= "full_amount";
				$remap_params["period3"]	= "full_period";
				$remap_params["perunit3"]	= "full_periodunit";
				$remap_params["processors"] = "processors";
				$remap_params["lifetime"]	= "lifetime";
				$remap_params["fallback"]	= "fallback";
				$remap_params["similarpg"]	= "similarpg";
				$remap_params["equalpg"]	= "equalpg";
				$remap_params["gid"]		= "gid";
				$remap_params["mingid"]		= "mingid";
				$remap_params["processors"] = "processors";

				$database->setQuery("SELECT * FROM  #__acctexp_plans");
				$plans = $database->loadObjectList();

				$plans_new = array();
				foreach ( $remap_params as $field => $arrayfield ) {
					foreach ( $plans as $plan ) {
						if ( isset( $plan->$field ) ) {
							$plans_new[$plan->id][$arrayfield] = $plan->$field;
						} else {
							$plans_new[$plan->id][$arrayfield] = "";
						}
					}

					$database->setQuery("ALTER TABLE #__acctexp_plans DROP COLUMN `" . $field . "`");
					if ( !$database->query() ) {
				    	$errors[] = array( $database->getErrorMsg(), $query );
					}
				}

				foreach ( $plans_new as $id => $content ) {
					$params			= '';
					$restrictions	= '';

					foreach ( $content as $name => $var ) {
						// For some values, we need to set an accompaning switch
						switch ($name) {
							case 'mingid':
								if ($var && ($var != 29) && ($var != 18)) {
									$restrictions .= "mingid_enabled=1\n";
									$restrictions .= $name . "=" . $var . "\n";
								} else {
									$restrictions .= "mingid_enabled=0\n";
									$restrictions .= $name . "=" . $var . "\n";
								}
							break;
							case 'full_amount':
								if (strcmp("0.00", $var) === 0) {
									$params .= "full_free=1\n";
									$params .= $name . "=" . $var . "\n";
								} else {
									$params .= "full_free=0\n";
									$params .= $name . "=" . $var . "\n";
								}
								break;
							case 'trial_amount':
								if (strcmp("0.00", $var) === 0) {
									$params .= "trial_free=1\n";
									$params .= $name . "=" . $var . "\n";
								} else {
									$params .= "trial_free=0\n";
									$params .= $name . "=" . $var . "\n";
								}
								break;
							default:
								$params .= $name . "=" . $var . "\n";
								break;
						}
					}

					// Making sure that plans act the same as they did before .49
					$params .= "gid_enabled=1\n";

					$database->setQuery("UPDATE #__acctexp_plans SET params='" . $params . "', restrictions='" . $restrictions . "' WHERE id='" . $id . "'");
					if ( !$database->query() ) {
				    	$errors[] = array( $database->getErrorMsg(), $query );
					}
				}

			}
		}

		$result = null;
		$database->setQuery("SHOW COLUMNS FROM #__acctexp_plans LIKE 'customparams'");
		if ( $database->loadObject( $result ) ) {
			if (strcmp($result->Field, 'customparams') === 0) {
				$result = null;
				$database->setQuery("SHOW COLUMNS FROM #__acctexp_plans LIKE 'custom_params'");
				if ( $database->loadObject( $result ) ) {
					if (!(strcmp($result->Field, 'custom_params') === 0)) {
						$database->setQuery("ALTER TABLE #__acctexp_plans CHANGE `customparams` `custom_params` text NULL");
						if ( !$database->query() ) {
					    	$errors[] = array( $database->getErrorMsg(), $query );
						}
					}
				}
			}
		}

		$result = null;
		$database->setQuery("SHOW COLUMNS FROM #__acctexp_plans LIKE 'custom_params'");
		if ( $database->loadObject( $result ) ) {
			if (!(strcmp($result->Field, 'custom_params') === 0)) {
				$database->setQuery("ALTER TABLE #__acctexp_plans ADD `custom_params` text NULL");
				if ( !$database->query() ) {
			    	$errors[] = array( $database->getErrorMsg(), $query );
				}
			}
		}

		$result = null;
		$database->setQuery("SHOW COLUMNS FROM #__acctexp_microintegrations LIKE 'system'");
		if ( $database->loadObject( $result ) ) {
			if (!(strcmp($result->Field, 'system') === 0)) {
				$database->setQuery("ALTER TABLE #__acctexp_microintegrations ADD `system` NOT NULL default '0'");
				if ( !$database->query() ) {
			    	$errors[] = array( $database->getErrorMsg(), $query );
				}
			}
		}

		$result = null;
		$database->setQuery("SHOW COLUMNS FROM #__acctexp_subscr LIKE 'params'");
		if ( $database->loadObject( $result ) ) {
			if (!(strcmp($result->Field, 'params') === 0)) {
				$database->setQuery("ALTER TABLE #__acctexp_subscr ADD `params` text NULL");
				if ( !$database->query() ) {
			    	$errors[] = array( $database->getErrorMsg(), $query );
				}
			}
		}

		$result = null;
		$database->setQuery("SHOW COLUMNS FROM #__acctexp_subscr LIKE 'customparams'");
		if ( $database->loadObject( $result ) ) {
			if (!(strcmp($result->Field, 'customparams') === 0)) {
				$database->setQuery("ALTER TABLE #__acctexp_subscr ADD `customparams` text NULL");
				if ( !$database->query() ) {
			    	$errors[] = array( $database->getErrorMsg(), $query );
				}
			}
		}

		$result = null;
		$database->setQuery("SHOW COLUMNS FROM #__acctexp_microintegrations LIKE 'pre_exp_check'");
		if ( $database->loadObject( $result ) ) {
			if (!(strcmp($result->Field, 'pre_exp_check') === 0)) {
				$database->setQuery("ALTER TABLE #__acctexp_microintegrations ADD `pre_exp_check` int(4) NULL");
				if ( !$database->query() ) {
			    	$errors[] = array( $database->getErrorMsg(), $query );
				}
			}
		}

		$result = null;
		$database->setQuery("SHOW COLUMNS FROM #__acctexp LIKE 'expiration'");
		if ( $database->loadObject( $result ) ) {
			if ( (strcmp($result->Field, 'expiration') === 0) && (strcmp($result->Type, 'date') === 0) ) {
				// Give extra space for plan description
				$database->setQuery("ALTER TABLE #__acctexp CHANGE `expiration` `expiration` datetime NOT NULL default '0000-00-00 00:00:00'");
				if ( !$database->query() ) {
			    	$errors[] = array( $database->getErrorMsg(), $query );
				}
			}
		}

		$database->setQuery("SELECT id FROM #__users WHERE gid='25'");
		$administrators = $database->loadResultArray();

		foreach ( $administrators as $adminid ) {
			$metaUser = new metaUser( $adminid );

			if ( !$metaUser->hasSubscription ) {
				$metaUser->objSubscription = new Subscription( $database ); // mic old: mosSubscription
				$metaUser->objSubscription->createNew( $adminid, 'free', 0 );
				$metaUser->objSubscription->setStatus( 'Excluded' );
			}
		}

		// log
		$short		= _AEC_LOG_SH_INST;
		$event		= sprintf( _AEC_LOG_LO_INST, _AEC_VERSION );
		$tags		= 'install,system';

		$eventlog	= new eventLog($database);
		$params		= array( 'userid' => $my->id );
		$eventlog->issue( $short, $tags, $event, $params );

		if ( !class_exists( 'Archive_Tar' ) ) {
			require_once( $mosConfig_absolute_path . '/includes/Archive/Tar.php' );
		}

		// Code borrowed from VirtueMart
		// Workaround for Window$
		/*
		if ( strstr( $mosConfig_absolute_path , ':' ) ) {
		 	$path_begin = substr( $mosConfig_absolute_path, strpos( $mosConfig_absolute_path , ':' ) + 1, strlen($mosConfig_absolute_path ) );
		 	$mosConfig_absolute_path = str_replace( "//", "/", $path_begin );
		}
		*/

		// Now let's re-declare the paths for Window$
		// mic: needed ???
		/*
		$frontend_dir	= $mosConfig_absolute_path . '/components/com_acctexp/';
		$frontend_file	= $mosConfig_absolute_path . '/components/com_acctexp/frontend_files.tar.gz';
		$admin_dir		= $mosConfig_absolute_path . '/administrator/components/com_acctexp/';
		$admin_file		= $mosConfig_absolute_path . '/administrator/components/com_acctexp/admin_files.tar.gz';
		*/

		// dirs
		$dir0			= $mosConfig_absolute_path . '/administrator/components/com_acctexp/';	// admin
		$dir1			= $mosConfig_absolute_path . '/components/com_acctexp/';				// user
		$dir2			= $mosConfig_absolute_path . '/administrator/components/';				// generic components dir

		$files = array();
		// icons
		$files[] = array( $dir0, 'images/icons/backend_icons.tar.gz',		'images/icons/' );
		$files[] = array( $dir0, 'images/icons/silk_icons.tar.gz',			'images/icons/' );
		$files[] = array( $dir0, 'images/backend_gfx/backend_gfx.tar.gz',	'images/backend_gfx/' );
		$files[] = array( $dir1, 'images/cc_icons/cc_icons.tar.gz',			'images/cc_icons/' );
		$files[] = array( $dir1, 'images/gateway_buttons.tar.gz',			'images/' );
		$files[] = array( $dir1, 'images/gateway_logos.tar.gz',				'images/' );

		// check if joomfish (joomla) or nokkaew (mambo) exists)
		$translation = false;
		if ( file_exists( $mosConfig_absolute_path . '/administrator/components/com_joomfish/admin.joomfish.php' ) ) {
			$translation = 'joomfish';
		} elseif ( file_exists( $mosConfig_absolute_path . '/administrator/components/com_nokkaew/admin.nokkaew.php' ) ) {
			$translation = 'nokkaew';
		}

		if ( $translation ) {
			if ( file_exists( $dir2 . 'com_acctexp/install/jf_content_elements_aec.' . _AEC_LANGUAGE . '.tar.gz' ) ) {
				$xmlInst = 'com_acctexp/install/jf_content_elements_aec.' . _AEC_LANGUAGE . '.tar.gz';
			} else {
				$xmlInst = 'com_acctexp/install/jf_content_elements_aec.en.tar.gz';
			}
			$files[] = array( $dir2, $xmlInst, 'com_' . $translation . '/contentelements/' );
		}

		foreach ( $files as $file ) {

			$fullpath	= $file[0] . $file[1];
			$deploypath = $file[0] . $file[2];

			$archive = new Archive_Tar( $fullpath, 'gz' );

			if ( !@is_dir( $deploypath ) ) {
				// Borrowed from php.net page on mkdir. Created by V-Tec (vojtech.vitek at seznam dot cz)
				$folder_path = array( strstr( $deploypath, '.' ) ? dirname( $deploypath ) : $deploypath );

				while ( !@is_dir( dirname( end( $folder_path ) ) )
						&& dirname(end($folder_path)) != '/'
						&& dirname(end($folder_path)) != '.'
						&& dirname(end($folder_path)) != '' ) {
					array_push( $folder_path, dirname( end( $folder_path ) ) );
				}

				while ( $parent_folder_path = array_pop( $folder_path ) ) {
					@mkdir( $parent_folder_path, 0644 );
				}
			}
			if ( $archive->extract( $deploypath ) ) {
				@unlink( $fullpath );
			}
		}
	} ?>

	<style type="text/css">
		.usernote {
			position: relative;
			float: left;
			width: 98%;
			background: url(<?php echo $mosConfig_live_site; ?>/administrator/components/com_acctexp/images/backend_gfx/note_lowerright.png) no-repeat bottom right;
			padding: 6px 18px;
			color: #000;
		}
	</style>

	<?php
	if ( $errors ) {
		echo '<div style="color: #FF0000; text-align: left; border: 1px solid #FF0000;">' . "\n"
		. _AEC_INST_ERRORS
		. '<ul>' . "\n";
		foreach ( $errors AS $error ) {
			echo '<li>' . $error[0] . ' - ' . $error[1] . '</li>';
		}
		echo '</ul>' . "\n"
		. '</div>' . "\n";
	} ?>
	<table border="0">
		<tr>
			<td width="60%" valign="top">
				<div class="usernote" style="width:350px;margin:8px;">
					<h1 style="color: #FF0000;"><?php echo _AEC_INST_NOTE_IMPORTANT; ?>:</h1>
					<img src="<?php echo $mosConfig_live_site; ?>/administrator/components/com_acctexp/images/backend_gfx/hacks_scribble.png" border="0" alt="" style="position:relative;float:left;padding:4px;" />
					<p><?php echo _AEC_INST_NOTE_SECURITY; ?></p>
					<p><?php printf( _AEC_INST_APPLY_HACKS, AECToolbox::backendTaskLink( 'hacks', _AEC_INST_APPLY_HACKS_LTEXT ) ); ?></p>
					<p><?php echo _AEC_INST_NOTE_UPGRADE; ?></p>
				</div>
				<div class="usernote" style="width:350px;margin:8px;">
					<h1><?php echo _AEC_INST_NOTE_IMPORTANT; ?>:</h1>
					<img src="<?php echo $mosConfig_live_site; ?>/administrator/components/com_acctexp/images/backend_gfx/help_scribble.png" border="0" alt="" style="position:relative;float:left;padding:4px;" />
					<p><?php printf( _AEC_INST_NOTE_HELP, AECToolbox::backendTaskLink( 'help', _AEC_INST_NOTE_HELP_LTEXT ) ); ?></p>
				</div>
				<div class="usernote" style="width:350px;margin:8px;">
					<h1><?php echo _AEC_INST_HINTS; ?></h1>
					<p><?php echo sprintf( _AEC_INST_HINT1, 'https://globalnerd.org/index.php?option=com_fireboard&Itemid=88' ); ?></p>
					<p><?php echo _AEC_INST_HINT2; ?></p>
				</div>
				<div class="usernote" style="width:350px;margin:8px;">
					<h1><?php echo _AEC_INST_ATTENTION; ?></h1>
					<p><?php echo _AEC_INST_ATTENTION1; ?></p>
				</div>
			</td>
			<td width="30%" valign="top">
				<br />
				<center><img src="<?php echo $mosConfig_live_site; ?>/administrator/components/com_acctexp/images/icons/aec_logo_big.png" border="0" alt="" /></center>
				<br />
				<div style="margin-left:auto;margin-right:auto;width:400px;text-align:center;">
					<p><strong>Account Expiration Control</strong> Component - Version <?php echo _AEC_VERSION; ?></p>
					<p><?php echo _AEC_FOOT_TX_CHOOSING; ?></p>
					<div style="margin: 0 auto;text-align:center;">
						<a href="https://www.globalnerd.org" target="_blank"> <img src="<?php echo $mosConfig_live_site; ?>/administrator/components/com_acctexp/images/icons/globalnerd_logo_tiny.png" border="0" alt="globalnerd" /></a>
						<p><?php echo _AEC_FOOT_TX_GPL; ?></p>
						<p><?php echo _AEC_FOOT_TX_SUBSCRIBE; ?></p>
						<p><?php printf( _AEC_FOOT_CREDIT, AECToolbox::backendTaskLink( 'credits', _AEC_FOOT_CREDIT_LTEXT ) ); ?></p>
				</div>
			</td>
		</tr>
	</table>
	<?php
} ?>