<?php
/**
 * @version $Id: upgrade_0_12_6.inc.php
 * @package AEC - Account Control Expiration - Membership Manager
 * @subpackage Install Includes
 * @copyright 2006-2008 Copyright (C) David Deutsch
 * @author David Deutsch <skore@skore.de> & Team AEC - http://www.globalnerd.org
 * @license GNU/GPL v.2 http://www.gnu.org/licenses/old-licenses/gpl-2.0.html or, at your option, any later version
 */

$eucaInstalldb->addColifNotExists( 'hidden', "int(4) NOT NULL default '0'", 'microintegrations' );

/*
// Modifying MI tables for MI Scope

// Make sure we have MIs in the first place

// Loop through MIs putting settings in subarrays (convert old _exp _pre_exp stuff)

// Convert old interpretative pre-expiration into new event table scheme
// Get all MIs that have pre-exp
// Get plans that have that MI
// Get active users with that plan
// Create events for users

// Delete old mi table columns
$eucaInstalldb->dropColifExists( 'auto_check', 'microintegrations' );
$eucaInstalldb->dropColifExists( 'on_userchange', 'microintegrations' );
$eucaInstalldb->dropColifExists( 'pre_exp_check', 'microintegrations' );

*/

?>