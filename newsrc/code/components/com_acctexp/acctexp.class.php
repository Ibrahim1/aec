<?php
/**
 * @version $Id: acctexp.class.php
 * @package AEC - Account Control Expiration - Membership Manager
 * @subpackage Core Class
 * @copyright 2006-2013 Copyright (C) David Deutsch
 * @author David Deutsch <skore@valanx.org> & Team AEC - http://www.valanx.org
 * @license GNU/GPL v.3 http://www.gnu.org/licenses/gpl.html or, at your option, any later version
 */

// Dont allow direct linking
defined('_JEXEC') or die( 'Direct Access to this location is not allowed.' );

define( '_AEC_VERSION', '1.2RC' );
define( '_AEC_REVISION', '6194' );

include_once( JPATH_SITE . '/components/com_acctexp/lib/compat.php' );

$langlist = array(	'com_acctexp' => JPATH_SITE,
					'com_acctexp.iso3166-1a2' => JPATH_SITE,
					'com_acctexp.iso639-1' => JPATH_SITE,
					'com_acctexp.microintegrations' => JPATH_SITE,
					'com_acctexp.processors' => JPATH_SITE
				);

xJLanguageHandler::loadList( $langlist );

if ( !class_exists( 'paramDBTable' ) ) {
	include_once( JPATH_SITE . '/components/com_acctexp/lib/eucalib/eucalib.php' );
}

$aecclasses = array(	'api',				'bucket',			'cart',				'component',
						'config',			'coupon',			'displaypipeline',	'event',
						'eventlog',			'functions',		'heartbeat',		'history',
						'html',				'invoice',			'itemgroup',		'itemterms',
						'microintegration',	'paymentprocessor',	'registration',		'restriction',
						'rewriteengine',	'settings',			'subscription',		'subscriptionplan',
						'template',			'temptoken',		'toolbox',			'user'
					);

foreach ( $aecclasses as $class ) {
	include_once( dirname(__FILE__) . '/classes/acctexp.' . $class . '.class.php' );
}

global $aecConfig;

if ( !is_object( $aecConfig ) ) {
	$aecConfig = new aecConfig();
}

?>
