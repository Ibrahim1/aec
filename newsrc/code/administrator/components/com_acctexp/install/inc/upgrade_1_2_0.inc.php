<?php
/**
 * @version $Id: upgrade_1_2_0.inc.php
 * @package AEC - Account Control Expiration - Membership Manager
 * @subpackage Install Includes
 * @copyright 2011-2013 Copyright (C) David Deutsch
 * @author David Deutsch <skore@valanx.org> & Team AEC - http://www.valanx.org
 * @license GNU/GPL v.3 http://www.gnu.org/licenses/gpl.html or, at your option, any later version
 */

// Dont allow direct linking
defined('_JEXEC') or die( 'Direct Access to this location is not allowed.' );

$eucaInstalldb->addColifNotExists( 'hidden', "int(4) NOT NULL default '0'", 'microintegrations' );

$eucaInstalldb->addColifNotExists( 'restrictions', "text NULL", 'microintegrations' );
