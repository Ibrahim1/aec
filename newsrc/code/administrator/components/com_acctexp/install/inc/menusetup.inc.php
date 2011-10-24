<?php
/**
 * @version $Id: menusetup.inc.php
 * @package AEC - Account Control Expiration - Membership Manager
 * @subpackage Install Includes
 * @copyright 2006-2011 Copyright (C) David Deutsch
 * @author David Deutsch <skore@skore.de> & Team AEC - http://www.valanx.org
 * @license GNU/GPL v.2 http://www.gnu.org/licenses/old-licenses/gpl-2.0.html or, at your option, any later version
 */

// first delete old menu entries
$eucaInstall->deleteAdminMenuEntries();

$iconroot = '../media/com_acctexp/images/admin/icons/';

// insert first component entry
$eucaInstall->createAdminMenuEntry( array( 'showCentral', JText::_('AEC_INST_MAIN_COMP_ENTRY'), $iconroot . 'aec_logo_tiny.png', 0 ) );

// insert components | image | task | menutext | menuid
$menu = array();
$menu[] = array( 'showCentral',			JText::_('AEC_CENTR_CENTRAL'),			$iconroot . 'aec_logo_tiny.png' );
$menu[] = array( 'showSubscriptionPlans',JText::_('AEC_CENTR_PLANS'),			$iconroot . 'aec_symbol_plans_tiny.png' );
$menu[] = array( 'showActive',			JText::_('AEC_CENTR_ACTIVE'),			$iconroot . 'aec_symbol_active_tiny.png' );
$menu[] = array( 'showPending',			JText::_('AEC_CENTR_PENDING'),			$iconroot . 'aec_symbol_pending_tiny.png' );
$menu[] = array( 'showCancelled',		JText::_('AEC_CENTR_CANCELLED'),		$iconroot . 'aec_symbol_cancelled_tiny.png' );
$menu[] = array( 'showClosed',			JText::_('AEC_CENTR_CLOSED'),			$iconroot . 'aec_symbol_closed_tiny.png' );
$menu[] = array( 'showExcluded',		JText::_('AEC_CENTR_EXCLUDED'),		$iconroot . 'aec_symbol_excluded_tiny.png' );
$menu[] = array( 'showManual',			JText::_('AEC_CENTR_MANUAL'),			$iconroot . 'aec_symbol_manual_tiny.png' );
$menu[] = array( 'showMicroIntegrations',JText::_('AEC_CENTR_M_INTEGRATION'),	$iconroot . 'aec_symbol_mi_tiny.png' );
$menu[] = array( 'showSettings',		JText::_('AEC_CENTR_SETTINGS'),		$iconroot . 'aec_symbol_settings_tiny.png' );
$menu[] = array( 'showProcessors',		JText::_('AEC_CENTR_PROCESSORS'),		$iconroot . 'aec_symbol_settings_tiny.png' );
$menu[] = array( 'eventlog',			JText::_('AEC_CENTR_LOG'),				$iconroot . 'aec_symbol_eventlog_tiny.png' );

$eucaInstall->populateAdminMenuEntry( $menu );
?>
