<?php
/**
 * @version $Id: mi_mysql_query.php 16 2007-07-02 13:29:29Z mic $
 * @package AEC - Account Control Expiration - Subscription component for Joomla! OS CMS
 * @subpackage Micro Integrations - MySQL Query
 * @copyright 2006/2007 Copyright (C) David Deutsch
 * @author David Deutsch <skore@skore.de> & Team AEC - http://www.globalnerd.org
 * @license GNU/GPL v.2 http://www.gnu.org/copyleft/gpl.html
 */

defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );

class mi_eventlog extends MI
{
	function Info()
	{
		$info = array();
		$info['name'] = _AEC_MI_EVENTLOG_NAME;
		$info['desc'] = _AEC_MI_EVENTLOG_DESC;

		return $info;
	}

	function Settings( $params )
	{
		$settings = array();
		$settings['short']			= array( 'inputE' );
		$settings['tags']			= array( 'inputE' );
		$settings['text']			= array( 'inputD' );
		$settings['level']			= array( 'list' );
		$settings['force_notify']	= array( 'list_yesno' );
		$settings['force_email']	= array( 'list_yesno' );
		$settings['params']		= array( 'inputD' );

		$settings = $this->autoduplicatesettings( $settings );

		$rewriteswitches			= array( 'cms', 'user', 'expiration', 'subscription', 'plan', 'invoice' );
		$settings['rewriteInfo']	= array( 'fieldset', _AEC_MI_SET4_MYSQL, AECToolbox::rewriteEngineInfo( $rewriteswitches ) );

		$levels[] = mosHTML::makeOption( 2, _AEC_NOTICE_NUMBER_2 );
		$levels[] = mosHTML::makeOption( 8, _AEC_NOTICE_NUMBER_8 );
		$levels[] = mosHTML::makeOption( 32, _AEC_NOTICE_NUMBER_32 );
		$levels[] = mosHTML::makeOption( 128, _AEC_NOTICE_NUMBER_128 );

		$settings['lists']['level'] = mosHTML::selectList($levels, 'level', 'size="5"', 'value', 'text', $params['level'] );
		$settings['lists']['level_exp'] = mosHTML::selectList($levels, 'level_exp', 'size="5"', 'value', 'text', $params['level_exp'] );
		$settings['lists']['level_pre_exp'] = mosHTML::selectList($levels, 'level_pre_exp', 'size="5"', 'value', 'text', $params['level_pre_exp'] );

		return $settings;
	}


	function relayAction( $params, $metaUser, $plan, $invoice, $area )
	{
		global $database;

		$rewriting = array( 'short', 'tags', 'text', 'params' );

		foreach ( $rewriting as $rw_name ) {
			$params[$rw_name.$area] = AECToolbox::rewriteEngine( $params[$rw_name.$area], $metaUser, $plan, $invoice );
		}

		$log_entry = new EventLog( $database );
		$log_entry->issue( $params['short'.$area], $params['tags'.$area], $params['text'.$area], $params['level'.$area], $params['params'.$area], $params['force_notify'.$area], $params['force_email'.$area] );
	}
}
?>
