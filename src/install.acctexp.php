<?php
/**
* AcctExp Installation
* @package AcctExp
* @copyright 2004-2007 Helder Garcia, David Deutsch
* @license http://www.gnu.org/copyleft/gpl.html. GNU Public License
* @version $Revision: 1.2 $
* @author Helder Garcia <helder.garcia@gmail.com>, David Deutsch <skore@skore.de>
**/
//
// Copyright (C) 2004-2007 Helder Garcia, David Deutsch
// All rights reserved.
// This source file is part of the Account Expiration Control Component, a  Joomla
// custom Component By Helder Garcia and David Deutsch - http://www.globalnerd.org
//
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
//
// The "GNU General Public License" (GPL) is available at
// http://www.gnu.org/copyleft/gpl.html.
//
// Dont allow direct linking
defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );

function com_install() {
global $database, $mainframe, $mosConfig_absolute_path, $mosConfig_live_site, $mosConfig_dbprefix, $my;

if (file_exists( $mosConfig_absolute_path . '/administrator/components/com_acctexp/com_acctexp_language_backend/'.$GLOBALS['mosConfig_lang'].'.php' )) {
		include_once( $mosConfig_absolute_path . '/administrator/components/com_acctexp/com_acctexp_language_backend/'.$GLOBALS['mosConfig_lang'].'.php' );
} else {
		include_once( $mosConfig_absolute_path . '/administrator/components/com_acctexp/com_acctexp_language_backend/english.php' );
}

include_once( $mosConfig_absolute_path . '/administrator/components/com_acctexp/com_acctexp_language_backend/general.php' );

require_once( $mainframe->getPath( 'class', "com_acctexp" ) );

$log = "";

// Set up new icons for admin menu
$menu_change = array();
$menu_change[] = array("aec_symbol_settings_tiny", "Settings");
$menu_change[] = array("aec_symbol_css_tiny", "Edit CSS");
$menu_change[] = array("aec_symbol_hacks_tiny", "Hacks");
$menu_change[] = array("aec_logo_tiny", "AEC Subscription Manager");
$menu_change[] = array("aec_logo_tiny", "AEC Central");
$menu_change[] = array("aec_symbol_help_tiny", "Help");
$menu_change[] = array("aec_symbol_excluded_tiny", "Excluded Subscriptions");
$menu_change[] = array("aec_symbol_pending_tiny", "Pending Subscriptions");
$menu_change[] = array("aec_symbol_active_tiny", "Active Subscriptions");
$menu_change[] = array("aec_symbol_cancelled_tiny", "Cancelled Subscriptions");
$menu_change[] = array("aec_symbol_closed_tiny", "Closed Subscriptions");
$menu_change[] = array("aec_symbol_manual_tiny", "Manual Subscriptions");
$menu_change[] = array("aec_symbol_plans_tiny", "Subscription Plans");

for ($i=0; $i<count($menu_change); $i++) {
	$query = "UPDATE #__components SET admin_menu_img='../administrator/components/com_acctexp/images/icons/" . $menu_change[$i][0] . ".png' WHERE admin_menu_link LIKE 'option=com_acctexp%' AND admin_menu_alt='" . $menu_change[$i][1] . "'";
	$database->setQuery($query);
	$res = $database->query();
	$log .= "<p>" . $query . "</p>";
}

// Update routine 0.3.0 -> 0.6.0
$tables	= array();
$tables	= $database->getTableList();
if(in_array($mosConfig_dbprefix."acctexp_payplans", $tables)) {
	// Update routine 0.3.0 -> 0.6.0
	// Check for existence of 'gid' column on table #__acctexp_payplans
	// It is existent only from version 0.6.0
	$result = null;
	$database->setQuery("SHOW COLUMNS FROM #__acctexp_payplans LIKE 'gid'");
	$database->loadObject($result);
	if(strcmp($result->Field, 'gid') === 0) {
		// You're already running version 0.6.0 or later. No action required.
	} else {
		// You're not running version 0.6.0 or later. Update required.
		$database->setQuery("ALTER TABLE #__acctexp_payplans ADD `gid` int(3) default NULL");
		$res = $database->query();
	}
}

// Update routine 0.6.0 -> 0.8.0
$result = null;
$database->setQuery("SHOW COLUMNS FROM #__acctexp_config LIKE 'alertlevel1'");
$database->loadObject($result);
if(!strcmp($result->Field, 'alertlevel1') === 0) {
	$database->setQuery("ALTER TABLE #__acctexp_config DROP `email`");
	$res = $database->query();

	$database->setQuery("ALTER TABLE #__acctexp_config DROP `paypal`");
	$res = $database->query();

	$database->setQuery("ALTER TABLE #__acctexp_config DROP `business`");
	$res = $database->query();

	$database->setQuery("ALTER TABLE #__acctexp_config DROP `testmode`");
	$res = $database->query();

	// Updating _config, adding alertlevel1, alertlevel2, alertlevel3
	$database->setQuery("ALTER TABLE #__acctexp_config ADD `alertlevel1` int(6) default '7'");
	$res = $database->query();

	$database->setQuery("ALTER TABLE #__acctexp_config ADD `alertlevel2` int(6) default '14'");
	$res = $database->query();

	$database->setQuery("ALTER TABLE #__acctexp_config ADD `alertlevel3` int(6) default '21'");
	$res = $database->query();

	// Copy id -> id || active -> active || ordering -> ordering || name -> name || desc -> desc
	// a1 -> amount1 || p1 -> period1 || t1 -> perunit1
	// a2 -> amount2 || p2 -> period2 || t2 -> perunit2
	// a3 -> amount3 || p3 -> period3 || t3 -> perunit3
	// gid -> gid

	// Drop new table __acctexp_plans. We going to recreate it from old __acctexp_payplans
	$database->setQuery("DROP TABLE  #__acctexp_plans");
	$res = $database->query();

	// Rename __acctexp_payplans to __acctexp_plans... Magic!!
	$database->setQuery("ALTER TABLE #__acctexp_payplans RENAME TO #__acctexp_plans");
	$res = $database->query();

	// Get rid of old stuff.
	$database->setQuery("ALTER TABLE #__acctexp_plans DROP `button`");
	$res = $database->query();

	$database->setQuery("ALTER TABLE #__acctexp_plans DROP `button_custom`");
	$res = $database->query();

	$database->setQuery("ALTER TABLE #__acctexp_plans DROP `src`");
	$res = $database->query();

	$database->setQuery("ALTER TABLE #__acctexp_plans DROP `sra`");
	$res = $database->query();

	$database->setQuery("ALTER TABLE #__acctexp_plans DROP `srt`");
	$res = $database->query();

	$database->setQuery("ALTER TABLE #__acctexp_plans DROP `invoice`");
	$res = $database->query();

	$database->setQuery("ALTER TABLE #__acctexp_plans DROP `tax`");
	$res = $database->query();

	$database->setQuery("ALTER TABLE #__acctexp_plans DROP `currency_code`");
	$res = $database->query();

	$database->setQuery("ALTER TABLE #__acctexp_plans DROP `modify`");
	$res = $database->query();

	$database->setQuery("ALTER TABLE #__acctexp_plans DROP `lc`");
	$res = $database->query();

	$database->setQuery("ALTER TABLE #__acctexp_plans DROP `page_style`");
	$res = $database->query();

	// And rename fields that shoudl remain, but with generic names.
	$database->setQuery("ALTER TABLE #__acctexp_plans CHANGE `a1` `amount1` varchar(40) NULL");
	$res = $database->query();

	$database->setQuery("ALTER TABLE #__acctexp_plans CHANGE `a2` `amount2` varchar(40) NULL");
	$res = $database->query();

	$database->setQuery("ALTER TABLE #__acctexp_plans CHANGE `a3` `amount3` varchar(40) NULL");
	$res = $database->query();

	$database->setQuery("ALTER TABLE #__acctexp_plans CHANGE `p1` `period1` varchar(40) NULL");
	$res = $database->query();

	$database->setQuery("ALTER TABLE #__acctexp_plans CHANGE `p2` `period2` varchar(40) NULL");
	$res = $database->query();

	$database->setQuery("ALTER TABLE #__acctexp_plans CHANGE `p3` `period3` varchar(40) NULL");
	$res = $database->query();

	$database->setQuery("ALTER TABLE #__acctexp_plans CHANGE `t1` `perunit1` varchar(40) NULL");
	$res = $database->query();

	$database->setQuery("ALTER TABLE #__acctexp_plans CHANGE `t2` `perunit2` varchar(40) NULL");
	$res = $database->query();

	$database->setQuery("ALTER TABLE #__acctexp_plans CHANGE `t3` `perunit3` varchar(40) NULL");
	$res = $database->query();

	// Drop new table __acctexp_log_paypal. We going to recreate it from old __acctexp_paylog
	$database->setQuery("DROP TABLE  #__acctexp_log_paypal");
	$res = $database->query();

	// Rename __acctexp_paylog to __acctexp_log_paypal...
	$database->setQuery("ALTER TABLE #__acctexp_paylog RENAME TO #__acctexp_log_paypal");
	$res = $database->query();

	// Associate all those old plans with PayPal processors.
	$database->setQuery("SELECT id FROM  #__acctexp_plans");
	$rows = $database->loadObjectList();
	for ($i=0, $n=count( $rows ); $i < $n; $i++) {
		$row = &$rows[$i];
		$database->setQuery("INSERT INTO `#__acctexp_processors_plans` VALUES ($row->id, '1')");
		$res = $database->query();
	}

	// Configure extra01 field indicating recurring subscriptions...
	$database->setQuery("UPDATE #__acctexp_subscr SET extra01='1' WHERE extra01 is NULL");
	$res = $database->query();

} else {
	// You're running version 0.8.0 or later. No Update required here.
}

if(in_array($mosConfig_dbprefix."acctexp_log_paypal", $tables)) {
	$database->setQuery("DROP TABLE  #__acctexp_log_paypal");
	$res = $database->query();
	$database->setQuery("DROP TABLE  #__acctexp_log_2checkout");
	$res = $database->query();
	$database->setQuery("DROP TABLE  #__acctexp_log_allopass");
	$res = $database->query();
	$database->setQuery("DROP TABLE  #__acctexp_log_authorize");
	$res = $database->query();
	$database->setQuery("DROP TABLE  #__acctexp_log_vklix");
	$res = $database->query();
}

// Update routine 0.8.0x -> 1.0.0 RC1
$result = null;
$database->setQuery("SHOW COLUMNS FROM #__acctexp_plans LIKE 'entry'");
$database->loadObject($result);
if(strcmp($result->Field, 'entry') === 0) {
	$database->setQuery("ALTER TABLE #__acctexp_plans DROP `entry`");
	$res = $database->query();
} else {
	$database->setQuery("ALTER TABLE #__acctexp_invoices CHANGE `invoice_number` `invoice_number` varchar(64) NULL");
	$res = $database->query();
}

$result = null;
$database->setQuery("SHOW COLUMNS FROM #__acctexp_plans LIKE 'desc'");
$database->loadObject($result);
if( (strcmp($result->Field, 'desc') === 0) && (strcmp($result->Type, 'varchar(255)') === 0) ) {
	// Give extra space for plan description
	$database->setQuery("ALTER TABLE #__acctexp_plans CHANGE `desc` `desc` text NULL");
	$res = $database->query();
}

$result = null;
$database->setQuery("SHOW COLUMNS FROM #__acctexp_plans LIKE 'lifetime'");
$database->loadObject($result);
if(!(strcmp($result->Field, 'lifetime') === 0)) {
	$database->setQuery("ALTER TABLE #__acctexp_plans ADD `lifetime` int(4) default '0'");
	$res = $database->query();
}

$result = null;
$database->setQuery("SHOW COLUMNS FROM #__acctexp_subscr LIKE 'lifetime'");
$database->loadObject($result);
if(!(strcmp($result->Field, 'lifetime') === 0)) {
	$database->setQuery("ALTER TABLE #__acctexp_subscr CHANGE `extra05` `lifetime` int(1) default '0'");
	$res = $database->query();
}

$result = null;
$database->setQuery("SHOW COLUMNS FROM #__acctexp_plans LIKE 'mingid'");
$database->loadObject($result);
if(!(strcmp($result->Field, 'mingid') === 0)) {
	$database->setQuery("ALTER TABLE #__acctexp_plans ADD `mingid` int(3) default '0'");
	$res = $database->query();
}

$result = null;
$database->setQuery("SHOW COLUMNS FROM #__acctexp_plans LIKE 'similarpg'");
$database->loadObject($result);
if(!(strcmp($result->Field, 'similarpg') === 0)) {
	$database->setQuery("ALTER TABLE #__acctexp_plans ADD `similarpg` varchar(255) NULL");
	$res = $database->query();
}

$result = null;
$database->setQuery("SHOW COLUMNS FROM #__acctexp_plans LIKE 'equalpg'");
$database->loadObject($result);
if(!(strcmp($result->Field, 'equalpg') === 0)) {
	$database->setQuery("ALTER TABLE #__acctexp_plans ADD `equalpg` varchar(255) NULL");
	$res = $database->query();
}

$result = null;
$database->setQuery("SHOW COLUMNS FROM #__acctexp_subscr LIKE 'previous_plan'");
$database->loadObject($result);
if(!(strcmp($result->Field, 'previous_plan') === 0)) {
	$database->setQuery("ALTER TABLE #__acctexp_subscr ADD `previous_plan` int(11) NULL");
	$res = $database->query();
}

$result = null;
$database->setQuery("SHOW COLUMNS FROM #__acctexp_subscr LIKE 'used_plans'");
$database->loadObject($result);
if(!(strcmp($result->Field, 'used_plans') === 0)) {
	$database->setQuery("ALTER TABLE #__acctexp_subscr ADD `used_plans` varchar(255) NULL");
	$res = $database->query();
}

$result = null;
$database->setQuery("SHOW COLUMNS FROM #__acctexp_subscr LIKE 'email_sent'");
$database->loadObject($result);
if(!(strcmp($result->Field, 'email_sent') === 0)) {
	$database->setQuery("ALTER TABLE #__acctexp_subscr ADD `email_sent` datetime default '0000-00-00 00:00:00'");
	$res = $database->query();
}

$result = null;
$database->setQuery("SHOW COLUMNS FROM #__acctexp_plans LIKE 'email_desc_exp'");
$database->loadObject($result);
if(!(strcmp($result->Field, 'email_desc_exp') === 0)) {
	$database->setQuery("ALTER TABLE #__acctexp_plans ADD `email_desc_exp` text NULL");
	$res = $database->query();
}

$result = null;
$database->setQuery("SHOW COLUMNS FROM #__acctexp_plans LIKE 'desc_email'");
$database->loadObject($result);
if(strcmp($result->Field, 'desc_email') === 0) {
	$database->setQuery("ALTER TABLE #__acctexp_plans DROP `desc_email`");
	$res = $database->query();
}

$result = null;
$database->setQuery("SHOW COLUMNS FROM #__acctexp_plans LIKE 'email_desc'");
$database->loadObject($result);
if(!(strcmp($result->Field, 'email_desc') === 0)) {
	$database->setQuery("ALTER TABLE #__acctexp_plans ADD `email_desc` text NULL");
	$res = $database->query();
}

$result = null;
$database->setQuery("SHOW COLUMNS FROM #__acctexp_plans LIKE 'send_exp_mail'");
$database->loadObject($result);
if(!(strcmp($result->Field, 'send_exp_mail') === 0)) {
	$database->setQuery("ALTER TABLE #__acctexp_plans ADD `send_exp_mail` int(4) default '0'");
	$res = $database->query();
}

$result = null;
$database->setQuery("SHOW COLUMNS FROM #__acctexp_plans LIKE 'fallback'");
$database->loadObject($result);
if(strcmp($result->Field, 'fallback') === 0) {
} else {
	$database->setQuery("ALTER TABLE #__acctexp_plans ADD `fallback` int(11) NULL");
	$res = $database->query();
}

$result = null;
$database->setQuery("SHOW COLUMNS FROM #__acctexp_invoices LIKE 'coupons'");
$database->loadObject($result);
if(!(strcmp($result->Field, 'coupons') === 0)) {
	$database->setQuery("ALTER TABLE #__acctexp_invoices ADD `coupons` varchar(255) NULL");
	$res = $database->query();
}

$result = null;
$database->setQuery("SHOW COLUMNS FROM #__acctexp_plans LIKE 'maxgid'");
$database->loadObject($result);
if(!(strcmp($result->Field, 'maxgid') === 0)) {
	$database->setQuery("ALTER TABLE #__acctexp_plans ADD `maxgid` int(3) default '0'");
	$res = $database->query();
}

$result = null;
$database->setQuery("SHOW COLUMNS FROM #__acctexp_plans LIKE 'micro_integrations'");
$database->loadObject($result);
if(!(strcmp($result->Field, 'micro_integrations') === 0)) {
	$database->setQuery("ALTER TABLE #__acctexp_plans ADD `micro_integrations` text NULL");
	$res = $database->query();
}

$result = null;
$database->setQuery("SHOW COLUMNS FROM #__acctexp_invoices LIKE 'params'");
$database->loadObject($result);
if(!(strcmp($result->Field, 'params') === 0)) {
	$database->setQuery("ALTER TABLE #__acctexp_invoices ADD `params` text NULL");
	$res = $database->query();
}

$result = null;
$database->setQuery("SHOW COLUMNS FROM #__acctexp_invoices LIKE 'fixed'");
$database->loadObject($result);
if(!(strcmp($result->Field, 'fixed') === 0)) {
	$database->setQuery("ALTER TABLE #__acctexp_invoices ADD `fixed` int(4) default '0'");
	$res = $database->query();
} elseif( (strcmp($result->Field, 'fixed') === 0) && (strcmp($result->Type, 'int(4)') === 0) && $result->Null ) {
	$database->setQuery("ALTER TABLE #__acctexp_invoices CHANGE `fixed` `fixed` int(4) default '0'");
	$res = $database->query();
} elseif( (strcmp($result->Field, 'fixed') === 0) && (strcmp($result->Type, "int(4)") === 0) && $result->Default ) {
	$database->setQuery("ALTER TABLE #__acctexp_invoices CHANGE `fixed` `fixed` int(4) default '0'");
	$res = $database->query();
}

$result = null;
$database->setQuery("SHOW COLUMNS FROM #__acctexp_plans LIKE 'visible'");
$database->loadObject($result);
if(!(strcmp($result->Field, 'visible') === 0)) {
	$database->setQuery("ALTER TABLE #__acctexp_plans ADD `visible` int(4) default '1'");
	$res = $database->query();
}

$result = null;
$database->setQuery("SHOW COLUMNS FROM #__acctexp_plans LIKE 'recurring'");
$database->loadObject($result);
if(strcmp($result->Field, 'recurring') === 0) {
	$database->setQuery("ALTER TABLE #__acctexp_plans DROP `recurring`");
	$res = $database->query();
}

$result = null;
$database->setQuery("SHOW COLUMNS FROM #__acctexp_microintegrations LIKE 'on_userchange'");
$database->loadObject($result);
if(strcmp($result->Field, 'on_userchange') === 0) {
} else {
	$database->setQuery("ALTER TABLE #__acctexp_microintegrations ADD `on_userchange` int(4) default '0'");
	$res = $database->query();
}

$result = null;
$database->setQuery("SHOW COLUMNS FROM #__acctexp_invoices LIKE 'created_date'");
$database->loadObject($result);
if(!(strcmp($result->Field, 'created_date') === 0)) {
	$database->setQuery("ALTER TABLE #__acctexp_invoices ADD `created_date` datetime NULL default '0000-00-00 00:00:00'");
	$res = $database->query();
}

$result = null;
$database->setQuery("SHOW COLUMNS FROM #__acctexp_invoices LIKE 'planid'");
$database->loadObject($result);
if(strcmp($result->Field, 'planid') === 0) {
	$database->setQuery("ALTER TABLE #__acctexp_invoices CHANGE `planid` `usage` varchar(255) NULL");
	$res = $database->query();
} else {
	$database->setQuery("ALTER TABLE #__acctexp_invoices ADD `usage` varchar(255) NULL");
	$res = $database->query();
}

$result = null;
$database->setQuery("SHOW COLUMNS FROM #__acctexp_plans LIKE 'reuse'");
$database->loadObject($result);
if((strcmp($result->Field, 'reuse') === 0)) {
	$database->setQuery("ALTER TABLE #__acctexp_plans DROP COLUMN `reuse`");
	$res = $database->query();
}

$result = null;
$database->setQuery("SHOW COLUMNS FROM #__acctexp_plans LIKE 'processors'");
$database->loadObject($result);
if(!(strcmp($result->Field, 'processors') === 0)) {
	$database->setQuery("ALTER TABLE #__acctexp_plans ADD `processors` varchar(255) NULL");
	$res = $database->query();
}

$result = null;
$database->setQuery("SHOW COLUMNS FROM #__acctexp_invoices LIKE 'active'");
$database->loadObject($result);
if(!(strcmp($result->Field, 'active') === 0)) {
	$database->setQuery("ALTER TABLE #__acctexp_invoices ADD `active` int(4) default '1'");
	$res = $database->query();
}

$result = null;
$database->setQuery("SHOW COLUMNS FROM #__acctexp_subscr LIKE 'extra01'");
$database->loadObject($result);
if(strcmp($result->Field, 'extra01') === 0) {
	$database->setQuery("ALTER TABLE #__acctexp_subscr CHANGE `extra01` `recurring` int(1) NOT NULL default '0'");
	$res = $database->query();
	$database->setQuery("ALTER TABLE #__acctexp_subscr DROP `extra02`");
	$res = $database->query();
	$database->setQuery("ALTER TABLE #__acctexp_subscr DROP `extra03`");
	$res = $database->query();
	$database->setQuery("ALTER TABLE #__acctexp_subscr DROP `extra04`");
	$res = $database->query();
}

$database->setQuery("SELECT count(*) FROM  #__acctexp_config_processors");
if($database->loadResult() == 0) {

	$database->setQuery( "SELECT proc_id FROM #__acctexp_processors_plans" );
	$db_processors = $database->loadResultArray();
	if (is_array($db_processors)) {
		$used_processors = array_unique($db_processors);

		$legacy_processors_db = array("", "paypal", "vklix", "authorize", "allopass", "2checkout", "epsnetpay", "paysignet", "worldpay", "alertpay");
		$legacy_processors_name = array("", "paypal", "viaklix", "authorize", "allopass", "2checkout", "epsnetpay", "paysignet", "worldpay", "alertpay");

		foreach ($used_processors as $i => $n) {

			$old_cfg = null;
			$database->setQuery( "SELECT * FROM #__acctexp_config_" . $legacy_processors_db[$n] );
			$database->loadObject($old_cfg);

			$pp = new PaymentProcessor();
			$pp->loadName($legacy_processors_name[$n]);
			$pp->init();

			switch ($legacy_processors_name[$n]) {
				case 'paypal':
					$pp->settings['business'] = $old_cfg->business;
					$pp->settings['testmode'] = $old_cfg->testmode;
					$pp->settings['tax'] = $old_cfg->tax;
					$pp->settings['currency'] = $old_cfg->currency_code;
					$pp->settings['checkbusiness'] = $old_cfg->checkbusiness;
					$pp->settings['lc'] = $old_cfg->lc;
					$pp->settings['altipnurl'] = $old_cfg->altipnurl;
					$pp->setSettings();

					$pp = new PaymentProcessor();
					$pp->loadName("paypal_subscription");
					$pp->init();
					$pp->settings['business'] = $old_cfg->business;
					$pp->settings['testmode'] = $old_cfg->testmode;
					$pp->settings['tax'] = $old_cfg->tax;
					$pp->settings['currency'] = $old_cfg->currency_code;
					$pp->settings['checkbusiness'] = $old_cfg->checkbusiness;
					$pp->settings['lc'] = $old_cfg->lc;
					$pp->settings['altipnurl'] = $old_cfg->altipnurl;

					break;
				case 'viaklix':
					$pp->settings['accountid'] = $old_cfg->accountid;
					$pp->settings['userid'] = $old_cfg->userid;
					$pp->settings['pin'] = $old_cfg->pin;
					$pp->settings['testmode'] = $old_cfg->testmode;
					break;
				case 'authorize':
					$pp->settings['login'] = $old_cfg->login;
					$pp->settings['transaction_key'] = $old_cfg->transaction_key;
					$pp->settings['testmode'] = $old_cfg->testmode;
					$pp->settings['currency'] = $old_cfg->currency_code;
					break;
				case 'allopass':
					$pp->settings['siteid'] = $old_cfg->siteid;
					$pp->settings['docid'] = $old_cfg->docid;
					$pp->settings['auth'] = $old_cfg->auth;
					$pp->settings['testmode'] = $old_cfg->testmode;
					break;
				case '2checkout':
					$pp->settings['sid'] = $old_cfg->sid;
					$pp->settings['secret_word'] = $old_cfg->secret_word;
					$pp->settings['alt2courl'] = $old_cfg->alt2courl;
					$pp->settings['testmode'] = $old_cfg->testmode;
					break;
				case 'paysignet':
					$pp->settings['merchant'] = $old_cfg->merchant;
					$pp->settings['testmode'] = $old_cfg->testmode;
					break;
				case 'worldpay':
					$pp->settings['instId'] = $old_cfg->instId;
					$pp->settings['testmode'] = $old_cfg->testmode;
					$pp->settings['currency'] = $old_cfg->currency_code;
					break;
				case 'alertpay':
					$pp->settings['merchant'] = $old_cfg->ap_merchant;
					$pp->settings['securitycode'] = $old_cfg->ap_securitycode;
					$pp->settings['tax'] = $old_cfg->tax;
					$pp->settings['testmode'] = $old_cfg->testmode;
					break;
				default:
					break;
			}

			$pp->setSettings();
		}

		$database->setQuery( "SELECT * FROM #__acctexp_processors_plans" );
		$procplans = $database->loadObjectList();

		foreach($procplans as $planentry) {
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
				$database->query();
			}
		}
	}

	$database->setQuery("DROP TABLE IF EXISTS #__acctexp_processors_plans");
	$res = $database->query();
	$database->setQuery("DROP TABLE IF EXISTS #__acctexp_processors");
	$res = $database->query();
	$database->setQuery("DROP TABLE IF EXISTS #__acctexp_config_paypal");
	$res = $database->query();
	$database->setQuery("DROP TABLE IF EXISTS #__acctexp_config_vklix");
	$res = $database->query();
	$database->setQuery("DROP TABLE IF EXISTS #__acctexp_config_authorize");
	$res = $database->query();
	$database->setQuery("DROP TABLE IF EXISTS #__acctexp_config_allopass");
	$res = $database->query();
	$database->setQuery("DROP TABLE IF EXISTS #__acctexp_config_2checkout");
	$res = $database->query();
	$database->setQuery("DROP TABLE IF EXISTS #__acctexp_config_epsnetpay");
	$res = $database->query();
	$database->setQuery("DROP TABLE IF EXISTS #__acctexp_config_paysignet");
	$res = $database->query();
	$database->setQuery("DROP TABLE IF EXISTS #__acctexp_config_worldpay");
	$res = $database->query();
	$database->setQuery("DROP TABLE IF EXISTS #__acctexp_config_alertpay");
	$res = $database->query();

}

$cfg = new Config_General($database);

$result = null;

$database->setQuery("SHOW COLUMNS FROM #__acctexp_config LIKE 'settings'");
$database->loadObject($result);
if(!(strcmp($result->Field, 'settings') === 0)) {
	$columns = array("transferinfo", "initialexp", "alertlevel1", "alertlevel2", "alertlevel3", "gwlist", "customintro", "customthanks", "customcancel", "bypassintegration", "simpleurls", "expiration_cushion", "currency_code", "heartbeat_cycle", "tos", "require_subscription", "entry_plan", "plans_first", "transfer", "checkusername", "activatepaid");

	foreach ($columns as $column) {
		$result = null;
		$database->setQuery("SHOW COLUMNS FROM #__acctexp_config LIKE '" . $column . "'");
		$database->loadObject($result);
		if(strcmp($result->Field, $column) === 0) {
			$database->setQuery( "SELECT " . $column . " FROM #__acctexp_config WHERE id='1'" );
			$cfg->cfg[$column] = $database->loadResult();

			$database->setQuery("ALTER TABLE #__acctexp_config DROP COLUMN " . $column);
			$database->query();
		}
	
		$database->setQuery("ALTER TABLE #__acctexp_config ADD `settings` text");
		$database->query();
	}

	$cfg->saveSettings();
}

// Init Settings default variables

$settings_defaults = array();
$settings_defaults['require_subscription'] = 0;
$settings_defaults['alertlevel2'] = 7;
$settings_defaults['alertlevel1'] = 3;
$settings_defaults['expiration_cushion'] = 12;
$settings_defaults['heartbeat_cycle'] = 24;
$settings_defaults['heartbeat_cycle_backend'] = 1;
$settings_defaults['plans_first'] = 0;
$settings_defaults['simpleurls'] = 0;
$settings_defaults['display_date_frontend'] = "%a, %d %b %Y %T %Z";
$settings_defaults['display_date_backend'] = "%a, %d %b %Y %T %Z";
$settings_defaults['enable_mimeta'] = 0;
$settings_defaults['enable_coupons'] = 0;
$settings_defaults['milist'] = "mi_email;mi_htaccess;mi_mysql_query;mi_email;mi_virtuemart";
$settings_defaults['displayccinfo'] = 1;
$settings_defaults['customtext_confirm_keeporiginal'] = 1;
$settings_defaults['customtext_checkout_keeporiginal'] = 1;
$settings_defaults['customtext_notallowed_keeporiginal'] = 1;
$settings_defaults['customtext_pending_keeporiginal'] = 1;
$settings_defaults['customtext_expired_keeporiginal'] = 1;
$settings_defaults['activate_paid'] = 1;

foreach ($settings_defaults as $name => $value) {
	if (!isset($cfg->cfg[$name]) || ($cfg->cfg[$name] == "")) {
		$cfg->cfg[$name] = $value;
	} elseif (is_null($cfg->cfg[$name])) {
		$cfg->cfg[$name] = $value;
	}
}

$cfg->saveSettings();

$result = null;
$database->setQuery("SHOW COLUMNS FROM #__acctexp_plans LIKE 'maxgid'");
$database->loadObject($result);
if(strcmp($result->Field, 'maxgid') === 0) {
	$database->setQuery("ALTER TABLE #__acctexp_plans DROP COLUMN `maxgid`");
	$res = $database->query();
}

$result = null;
$database->setQuery("SHOW COLUMNS FROM #__acctexp_plans LIKE 'email_desc_exp'");
$database->loadObject($result);
if(strcmp($result->Field, 'email_desc_exp') === 0) {
	$database->setQuery("ALTER TABLE #__acctexp_plans DROP COLUMN `email_desc_exp`");
	$res = $database->query();
}

$result = null;
$database->setQuery("SHOW COLUMNS FROM #__acctexp_plans LIKE 'send_exp_mail'");
$database->loadObject($result);
if(strcmp($result->Field, 'send_exp_mail') === 0) {
	$database->setQuery("ALTER TABLE #__acctexp_plans DROP COLUMN `send_exp_mail`");
	$res = $database->query();
}

$result = null;
$database->setQuery("SHOW COLUMNS FROM #__acctexp_plans LIKE 'params'");
$database->loadObject($result);
if(!(strcmp($result->Field, 'params') === 0)) {
	$result = null;
	$database->setQuery("SHOW COLUMNS FROM #__acctexp_plans LIKE 'mingid'");
	$database->loadObject($result);

	if(strcmp($result->Field, 'mingid') === 0) {
		$database->setQuery("ALTER TABLE #__acctexp_plans ADD `restrictions` text NULL");
		$res = $database->query();
		$database->setQuery("ALTER TABLE #__acctexp_plans ADD `params` text NULL");
		$res = $database->query();
	
		$remap_params = array();
		$remap_params["amount1"] = "trial_amount";
		$remap_params["period1"] = "trial_period";
		$remap_params["perunit1"] = "trial_periodunit";
		$remap_params["amount2"] = "trial2_amount";
		$remap_params["period2"] = "trial2_period";
		$remap_params["perunit2"] = "trial2_periodunit";
		$remap_params["amount3"] = "full_amount";
		$remap_params["period3"] = "full_period";
		$remap_params["perunit3"] = "full_periodunit";
		$remap_params["processors"] = "processors";
		$remap_params["lifetime"] = "lifetime";
		$remap_params["fallback"] = "fallback";
		$remap_params["similarpg"] = "similarpg";
		$remap_params["equalpg"] = "equalpg";
		$remap_params["gid"] = "gid";
		$remap_params["mingid"] = "mingid";
		$remap_params["processors"] = "processors";
	
		$database->setQuery("SELECT * FROM  #__acctexp_plans");
		$plans = $database->loadObjectList();
	
		$plans_new = array();
		foreach ($remap_params as $field => $arrayfield) {
			foreach ($plans as $plan) {
				if (isset($plan->$field)) {
					$plans_new[$plan->id][$arrayfield] = $plan->$field;
				} else {
					$plans_new[$plan->id][$arrayfield] = "";
				}
			}
	
			$database->setQuery("ALTER TABLE #__acctexp_plans DROP COLUMN `" . $field . "`");
			$res = $database->query();
		}
	
		foreach ($plans_new as $id => $content) {
			$params = "";
			$restrictions = "";
			foreach ($content as $name => $var) {
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
			$res = $database->query();
		}
	
	}
}

$result = null;
$database->setQuery("SHOW COLUMNS FROM #__acctexp_plans LIKE 'customparams'");
$database->loadObject($result);
if(!(strcmp($result->Field, 'customparams') === 0)) {
	$database->setQuery("ALTER TABLE #__acctexp_plans ADD `customparams` text NULL");
	$res = $database->query();
}

$result = null;
$database->setQuery("SHOW COLUMNS FROM #__acctexp_microintegrations LIKE 'system'");
$database->loadObject($result);
if(!(strcmp($result->Field, 'system') === 0)) {
	$database->setQuery("ALTER TABLE #__acctexp_microintegrations ADD `system` NOT NULL default '0'");
	$res = $database->query();
}

$result = null;
$database->setQuery("SHOW COLUMNS FROM #__acctexp_subscr LIKE 'params'");
$database->loadObject($result);
if(!(strcmp($result->Field, 'params') === 0)) {
	$database->setQuery("ALTER TABLE #__acctexp_subscr ADD `params` text NULL");
	$res = $database->query();
}

$result = null;
$database->setQuery("SHOW COLUMNS FROM #__acctexp_subscr LIKE 'customparams'");
$database->loadObject($result);
if(!(strcmp($result->Field, 'customparams') === 0)) {
	$database->setQuery("ALTER TABLE #__acctexp_subscr ADD `customparams` text NULL");
	$res = $database->query();
}

$result = null;
$database->setQuery("SHOW COLUMNS FROM #__acctexp_microintegrations LIKE 'pre_exp_check'");
$database->loadObject($result);
if(!(strcmp($result->Field, 'pre_exp_check') === 0)) {
	$database->setQuery("ALTER TABLE #__acctexp_microintegrations ADD `pre_exp_check` int(4) NULL");
	$res = $database->query();
}

$result = null;
$database->setQuery("SHOW COLUMNS FROM #__acctexp LIKE 'expiration'");
$database->loadObject($result);
if( (strcmp($result->Field, 'expiration') === 0) && (strcmp($result->Type, 'date') === 0) ) {
	// Give extra space for plan description
	$database->setQuery("ALTER TABLE #__acctexp CHANGE `expiration` `expiration` datetime NOT NULL default '0000-00-00 00:00:00'");
	$res = $database->query();
}

$database->setQuery("SELECT id FROM #__users WHERE gid='25'");
$administrators = $database->loadResultArray();

foreach ($administrators as $adminid) {
	$metaUser = new metaUser($adminid);
	if (!$metaUser->hasSubscription) {
		$metaUser->objSubscription = new mosSubscription($database);
		$metaUser->objSubscription->createNew($adminid, "free", 0);
		$metaUser->objSubscription->setStatus("Excluded");
	}
}

$short = "AEC install";
$event = "The AEC Version " . _AEC_VERSION . " has been installed";
$tags = "install,system";

$eventlog = new eventLog($database);
$params = array("userid" => $my->id);
$eventlog->issue( $short, $tags, $event, $params );

require_once( $mosConfig_absolute_path.'/administrator/components/com_acctexp/Tar.php' );	

// Code borrowed from VirtueMart

// Workaround for Window$
if(strstr($mosConfig_absolute_path , ":" )) {
  $path_begin = substr( $mosConfig_absolute_path, strpos( $mosConfig_absolute_path , ":" )+1, strlen($mosConfig_absolute_path));
  $mosConfig_absolute_path = str_replace( "//", "/", $path_begin );
}
// Now let's re-declare the paths for Window$
$frontend_dir = $mosConfig_absolute_path."/components/com_acctexp/";
$frontend_file = $mosConfig_absolute_path."/components/com_acctexp/frontend_files.tar.gz";
$admin_dir = $mosConfig_absolute_path."/administrator/components/com_acctexp/";
$admin_file = $mosConfig_absolute_path."/administrator/components/com_acctexp/admin_files.tar.gz";

$files = array();
$files[] = array(1, "images/icons/backend_icons.tar.gz", "images/icons/");
$files[] = array(1, "images/icons/silk_icons.tar.gz", "images/icons/");
$files[] = array(1, "images/backend_gfx/backend_gfx.tar.gz", "images/backend_gfx/");
$files[] = array(0, "images/cc_icons/cc_icons.tar.gz", "images/cc_icons/");
$files[] = array(0, "images/gateway_buttons.tar.gz", "images/");
$files[] = array(0, "images/gateway_logos.tar.gz", "images/");

foreach ($files as $file) {
	if ($file[0]) {
		$directory = $mosConfig_absolute_path . "/administrator/components/com_acctexp/";
	} else {
		$directory = $mosConfig_absolute_path . "/components/com_acctexp/";
	}

	$fullpath = $directory . $file[1];
	$deploypath = $directory . $file[2];
	$archive = new Archive_Tar( $fullpath, "gz" );

	if (!@is_dir($deploypath)) {
		// Borrowed from php.net page on mkdir. Created by V-Tec (vojtech.vitek at seznam dot cz)
		$folder_path = array(strstr($deploypath, '.') ? dirname($deploypath) : $deploypath);
	
		while(!@is_dir(dirname(end($folder_path)))
				&& dirname(end($folder_path)) != '/'
				&& dirname(end($folder_path)) != '.'
				&& dirname(end($folder_path)) != '') {
			array_push($folder_path, dirname(end($folder_path)));
		}
	
		while($parent_folder_path = array_pop($folder_path)) {
			@mkdir($parent_folder_path, 0644);
		}
	}
	if ($archive->extract($deploypath)) {
		@unlink($fullpath);
	}
}
?>
<style type="text/css">
.usernote {
position: relative;
float: left;
width: 98%;
background: url(/administrator/components/com_acctexp/images/backend_gfx/note_lowerright.png) no-repeat bottom right;
padding: 6px 18px;
color: #000;
}
</style>

<table border="0">
	<tr>
		<td width="60%" valign="top">
			<div class="usernote" style="width:350px;margin:8px;">
			<h1>IMPORTANT NOTICE:</h1>
			<img src="components/com_acctexp/images/backend_gfx/hacks_scribble.png" border="0" style="position:relative;float:left;padding:4px;">
			<p>For <strong>your system security</strong> you need apply hacks to joomla core files. For your convenience, we have included an autohacking feature that does just that with the click of a link.</p>
			<p>In order to commit these hacks right now, go to the <a href="<?php echo $mosConfig_live_site . "/administrator/index2.php?option=com_acctexp&task=hacks" ?>">hacks page</a>. (You can also access this page later on from the AEC central view or menu.)</p>
			<p><strong>If you are upgrading, make sure to check the hacks page anyways, since there are changes from time to time!!!</strong></p>
			</div>
			<div class="usernote" style="width:350px;margin:8px;">
			<h1>ANOTHER IMPORTANT NOTICE:</h1>
			<img src="components/com_acctexp/images/backend_gfx/help_scribble.png" border="0" style="position:relative;float:left;padding:4px;">
			<p>To help you along with frequently encountered problems, we have created a <strong>help function</strong> that will help you ship around the most common setup problems.</p>
			<p>You can choose the help page from the central page, the menu, or <a href="<?php echo $mosConfig_live_site . "/administrator/index2.php?option=com_acctexp&task=help" ?>">go there now</a> directly.</p>
			</div>
			<div class="usernote" style="width:350px;margin:8px;">
			<h1>This is a development release!</h1>
			<p>Although this version of the AEC is <strong>far more stable</strong> then the actual "stable" release, there are <strong>still some bugs and quirks</strong> left over.</p>
			<p>We encourage you to visit the <a href="http://www.globalnerd.org/index.php?option=com_wrapper&amp;Itemid=53">globalnerd.org forums</a> and to <strong>participate in the discussion there</strong>. Chances are, that other users have found the same bugs and it is equally likely that there is at least a fix to hack in or even a new version.</p>
			<p>In any case (and especially if you use this on a live site): go through your settings and make a test subscription to see whether everything is working to your satisfaction! Although we try our best to make upgrading as flawless as possible, some fundamental changes to our program may not be possible to cushion for all users.</p>
			</div>
			<div class="usernote" style="width:350px;margin:8px;">
			<h1>No need to use the old logins!</h1>
			<p>If you still have the old AEC login modules installed, please uninstall it (no matter which one, regular or CB) and use the normal joomla or CB login module. There is no need to use these old modules anymore.</p>
			</div>
		</td>
		<td width="30%" valign="top">
			<br />
			<center><img src="components/com_acctexp/images/icons/aec_logo_big.png" border="0"></center>
			<br />
			<div style="margin-left:auto;margin-right:auto;width:400px;text-align:center;"><p><strong>Account Expiration Control</strong> Component - Version <?php echo _AEC_VERSION ?></p>
				<p>Thank you for choosing Account Expiration Control Component!</p>
					<div style="margin: 0 auto;text-align:center;">
						<a href="http://www.globalnerd.org"> <img src="<?php echo $mosConfig_live_site; ?>/administrator/components/com_acctexp/images/icons/globalnerd_logo_tiny.png" border="0" alt="globalnerd" /></a>
				<p>This Joomla / Mambo component was developed and released under the <a href="http://www.gnu.org/copyleft/gpl.html">GNU/GPL</a> license by Helder Garcia and David Deutsch from <a href="http://www.globalnerd.org">globalnerd.org</a></p>
				<p>If you want more features, professional service, updates, manuals and online help for this component, you may subscribe to our services at the above link. It helps us a lot in our development!</p>
				<p>Please read our <a href="<?php echo $mosConfig_live_site; ?>/administrator/index2.php?option=com_acctexp&amp;task=credits">full credits</a></p>
			</div>
		</td>
	</tr>
</table>
<?php
}
?>