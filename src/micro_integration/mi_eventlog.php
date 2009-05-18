<?php
/**
 * @version $Id: mi_eventlog.php
 * @package AEC - Account Control Expiration - Membership Manager
 * @subpackage Micro Integrations - Eventlog
 * @copyright 2006-2008 Copyright (C) David Deutsch
 * @author David Deutsch <skore@skore.de> & Team AEC - http://www.valanx.org
 * @license GNU/GPL v.2 http://www.gnu.org/licenses/old-licenses/gpl-2.0.html or, at your option, any later version
 */

// Dont allow direct linking
( defined('_JEXEC') || defined( '_VALID_MOS' ) ) or die( 'Direct Access to this location is not allowed.' );

class mi_eventlog extends MI
{
	function Info()
	{
		$info = array();
		$info['name'] = _AEC_MI_EVENTLOG_NAME;
		$info['desc'] = _AEC_MI_EVENTLOG_DESC;

		return $info;
	}

	function Settings()
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

		$settings['lists']['level'] = mosHTML::selectList($levels, 'level', 'size="5"', 'value', 'text', $this->settings['level'] );
		$settings['lists']['level_exp'] = mosHTML::selectList($levels, 'level_exp', 'size="5"', 'value', 'text', $this->settings['level_exp'] );
		$settings['lists']['level_pre_exp'] = mosHTML::selectList($levels, 'level_pre_exp', 'size="5"', 'value', 'text', $this->settings['level_pre_exp'] );

		return $settings;
	}


	function relayAction( $request, $area )
	{
		global $database;

		$rewriting = array( 'short', 'tags', 'text', 'params' );

		foreach ( $rewriting as $rw_name ) {
			$this->settings[$rw_name.$area] = AECToolbox::rewriteEngineRQ( $this->settings[$rw_name.$area], $request );
		}

		$log_entry = new EventLog( $database );
		$log_entry->issue( $this->settings['short'.$area], $this->settings['tags'.$area], $this->settings['text'.$area], $this->settings['level'.$area], $this->settings['params'.$area], $this->settings['force_notify'.$area], $this->settings['force_email'.$area] );
	}
}
?>
