<?php
/**
 * @version $Id: mi_raffle.php 16 2007-07-01 12:07:07Z mic $
 * @package AEC - Account Control Expiration - Subscription component for Joomla! OS CMS
 * @subpackage Micro Integrations - DocMan
 * @copyright 2006/2007 Copyright (C) David Deutsch
 * @author David Deutsch <skore@skore.de> & Team AEC - http://www.globalnerd.org
 * @author Calum Polwart (Shiny Black Shoe Systems)
 * @license GNU/GPL v.2 http://www.gnu.org/copyleft/gpl.html
 *
 * based on David Deutsch's reMOSitory MI
 */

defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );

class mi_raffle
{
	function checkInstallation()
	{
		global $database, $mosConfig_dbprefix;

		$tables	= array();
		$tables	= $database->getTableList();

		return in_array( $mosConfig_dbprefix . 'acctexp_mi_raffle', $tables );
	}

	function install()
	{
		global $database;

		$query = 'CREATE TABLE IF NOT EXISTS `#__acctexp_mi_raffle` ('
		. '`id` int(11) NOT NULL auto_increment,'
		. '`userid` int(11) NOT NULL,'
		. '`active` int(4) NOT NULL default \'1\','
		. '`granted_downloads` int(11) NULL,'
		. '`unlimited_downloads` int(3) NULL,'
		. '`used_downloads` int(11) NULL,'
		. '`params` text NULL,'
		. ' PRIMARY KEY (`id`)'
		. ')'
		;
		$database->setQuery( $query );
		$database->query();
		return;
	}

	function Settings()
	{
		global $database;

        $settings = array();
		$settings['draw_range']		= array( 'inputA' );
		$settings['set_downloads']	= array( 'inputA' );
		$settings['set_unlimited']	= array( 'list_yesno' );

		return $settings;
	}

	function action( $request )
	{
		global $database;

		if ( $this->settings['set_group'] ) {
			$this->AddUserToGroup( $request->metaUser->userid, $this->settings['group'] );
		}

		$mi_rafflehandler = new raffle_restriction( $database );
		$id = $mi_rafflehandler->getIDbyUserID( $request->metaUser->userid );
		$mi_id = $id ? $id : 0;
		$mi_rafflehandler->load( $mi_id );

		if ( !$mi_id ) {
			$mi_rafflehandler->userid = $request->metaUser->userid;
			$mi_rafflehandler->active = 1;
		}

		if ( $this->settings['set_downloads'] ) {
			$mi_rafflehandler->setDownloads( $this->settings['set_downloads'] );
		} elseif ( $this->settings['add_downloads'] ) {
			$mi_rafflehandler->addDownloads( $this->settings['add_downloads'] );
		}

		$mi_rafflehandler->check();
		$mi_rafflehandler->store();

		return true;
	}

}

class AECMI_raffle extends mosDBTable {
	/** @var int Primary key */
	var $id						= null;
	/** @var int */
	var $userid 				= null;
	/** @var int */
	var $active					= null;
	/** @var int */
	var $granted_downloads		= null;
	/** @var int */
	var $unlimited_downloads	= null;
	/** @var text */
	var $used_downloads			= null;
	/** @var text */
	var $params					= null;

	function getLastEntry( $userid ) {
		global $database;

		$query = 'SELECT `id`'
			. ' FROM #__acctexp_mi_raffle'
			. ' WHERE id = max( 'id' )'
			;
		$database->setQuery( $query );
		return $database->loadResult();
	}

	function raffle_restriction( &$db ) {
		$this->mosDBTable( '#__acctexp_mi_raffle', 'id', $db );
	}

	function is_active()
	{
		if ( $this->active ) {
			return true;
		} else {
			return false;
		}
	}

	function getDownloadsLeft()
	{
		if ( !empty( $this->unlimited_downloads ) ) {
			return true;
		} else {
			$downloads_left = $this->granted_downloads - $this->used_downloads;
			return $downloads_left;
		}
	}

	function hasDownloadsLeft()
	{
		$check = $this->getDownloadsLeft();

		if ( !empty( $check ) ) {
			return true;
		} else {
			return false;
		}
	}

	function noDownloadsLeft()
	{
		if ( !defined( '_AEC_LANG_INCLUDED_MI' ) ) {
			global $mainframe;

			$langPathMI = $mainframe->getCfg( 'absolute_path' ) . '/components/com_acctexp/micro_integration/language/';
			if ( file_exists( $langPathMI . $mainframe->getCfg( 'lang' ) . '.php' ) ) {
				include_once( $langPathMI . $mainframe->getCfg( 'lang' ) . '.php' );
			} else {
				include_once( $langPathMI . 'english.php' );
			}
		}

		mosRedirect(  'index.php?option=com_raffle' , _AEC_MI_RAFFLE_NOCREDIT );
	}

	function useDownload()
	{
		if ( $this->hasDownloadsLeft() && $this->is_active() ) {
			$this->used_downloads++;
			$this->check();
			$this->store();
			return true;
		} else {
			return false;
		}
	}

	function setDownloads( $set )
	{
		$this->granted_downloads = $set;
	}

	function addDownloads( $add )
	{
		$this->granted_downloads += $add;
	}
}
?>