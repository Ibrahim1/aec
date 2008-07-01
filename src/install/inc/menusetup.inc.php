<?php
// first delete old menu entries
$eucaInstall->deleteAdminMenuEntries();

if (  defined( 'JPATH_BASE' ) ) {
	$iconroot = '../administrator/components/com_acctexp/images/icons/';
} else {
	$iconroot = '../administrator/components/com_acctexp/images/icons/';
}

// insert first component entry
$eucaInstall->createAdminMenuEntry( array( 'showCentral', _AEC_INST_MAIN_COMP_ENTRY, $iconroot . 'aec_logo_tiny.png', 0 ) );

// insert components | image | task | menutext | menuid
$menu = array();
$menu[] = array( 'showCentral',			_AEC_CENTR_CENTRAL,			$iconroot . 'aec_logo_tiny.png' );
$menu[] = array( 'showSubscriptionPlans',_AEC_CENTR_PLANS,			$iconroot . 'aec_symbol_plans_tiny.png' );
$menu[] = array( 'showActive',			_AEC_CENTR_ACTIVE,			$iconroot . 'aec_symbol_active_tiny.png' );
$menu[] = array( 'showPending',			_AEC_CENTR_PENDING,			$iconroot . 'aec_symbol_pending_tiny.png' );
$menu[] = array( 'showCancelled',		_AEC_CENTR_CANCELLED,		$iconroot . 'aec_symbol_cancelled_tiny.png' );
$menu[] = array( 'showClosed',			_AEC_CENTR_CLOSED,			$iconroot . 'aec_symbol_closed_tiny.png' );
$menu[] = array( 'showExcluded',		_AEC_CENTR_EXCLUDED,		$iconroot . 'aec_symbol_excluded_tiny.png' );
$menu[] = array( 'showManual',			_AEC_CENTR_MANUAL,			$iconroot . 'aec_symbol_manual_tiny.png' );
$menu[] = array( 'showMicroIntegrations',_AEC_CENTR_M_INTEGRATION,	$iconroot . 'aec_symbol_mi_tiny.png' );
$menu[] = array( 'showSettings',		_AEC_CENTR_SETTINGS,		$iconroot . 'aec_symbol_settings_tiny.png' );
$menu[] = array( 'editCSS',				_AEC_CENTR_EDIT_CSS,		$iconroot . 'aec_symbol_css_tiny.png' );
$menu[] = array( 'hacks',				_AEC_CENTR_HACKS,			$iconroot . 'aec_symbol_hacks_tiny.png' );
$menu[] = array( 'help',				_AEC_CENTR_HELP,			$iconroot . 'aec_symbol_help_tiny.png' );

$eucaInstall->populateAdminMenuEntry( $menu );
?>
