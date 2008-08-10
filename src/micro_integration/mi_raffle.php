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

		return in_array( $mosConfig_dbprefix . '_acctexp_mi_rafflelist', $tables );
	}

	function install()
	{
		global $database;

		$query = 'CREATE TABLE IF NOT EXISTS `#__acctexp_mi_rafflelist` ('
		. '`id` int(11) NOT NULL auto_increment,'
		. '`group` int(11) NULL,'
		. '`params` text NULL,'
		. ' PRIMARY KEY (`id`)'
		. ')'
		;
		$database->setQuery( $query );
		$database->query();

		$query = 'CREATE TABLE IF NOT EXISTS `#__acctexp_mi_raffleuser` ('
		. '`id` int(11) NOT NULL auto_increment,'
		. '`userid` int(11) NOT NULL,'
		. '`wins` int(11) NOT NULL default \'0\','
		. '`runs` int(11) NOT NULL default \'0\','
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
		$settings['list_group']			= array( 'inputA' );
		$settings['draw_range']			= array( 'inputA' );
		$settings['max_participations']	= array( 'inputA' );
		$settings['max_wins']			= array( 'inputA' );

		$settings['col_recipient']		= array( 'inputE' );

		return $settings;
	}

	function action( $request )
	{
		global $database;

		$raffleuser = new AECMI_raffleuser( $database );
		$raffleuser->loadUserid( $request->metaUser->userid );

		if ( empty( $raffleuser->id ) ) {
			$raffleuser->userid = $request->metaUser->userid;
			$raffleuser->storeload();
		}

		if ( $raffleuser->wins >= $this->settings['max_wins'] ) {
			return null;
		}

		if ( $raffleuser->runs >= !$this->settings['max_participations'] ) {
			return null;
		}

		$rafflelist = new AECMI_rafflelist( $database );
		$rafflelist->loadMax( $this->settings['list_group'] );

		if ( empty( $rafflelist->id ) ) {
			$rafflelist->group = $this->settings['list_group'];

			$rafflelist->params = new stdClass();

			$rafflelist->params->participants = array();
			$rafflelist->params->settings = array();
			$rafflelist->params->settings['draw_range'] = $this->settings['draw_range'];
		}

		if ( in_array( $raffleuser->id, $rafflelist->participants ) ) {
			continue;
		}

		$rafflelist->participants[] = $raffleuser->id;

		$this->metaUser->meta->setMIParams( $this->parent->id, $this->objUsage->id, $content );

		if ( count( $rafflelist ) >= $rafflelist->params->settings['draw_range'] ) {
			$winner = rand( 1, $rafflelist->params->settings['draw_range'] );

			$rafflelist->params->winid = $rafflelist->params->participants[($winner-1)];

			$result = $rafflelist->closeRun( $rafflelist->params->winid );

			// TODO: Multiple winners
			$winnerMeta = new metaUser( $result['winners'][0] );

			$colET = 'The current draw results are in:' . "\n" . "\n";
			$cloET .= 'Winner:' . "\n";
			$cloET .= 'Userid: ' . $winnerMeta->userid . '; Username: ' . $winnerMeta->username . '; Email: ' . $winnerMeta->email . "\n" . "\n";
			$cloET .= 'Also participated:' . "\n";

			foreach ( $result['participants'] as $userid ) {
				$u = null;

				$query = 'SELECT `username`, `email`'
					. ' FROM #__users'
					. ' WHERE `id` = \'' . $userid . '\'';
					;
				$database->setQuery( $query );
				$database->loadObject( $u );

				$cloET .= $u->userid . ';' . $u->username . ';' . $u->email . "\n";
			}
		}

		return true;
	}

}

class AECMI_rafflelist extends serialParamDBTable {
	/** @var int Primary key */
	var $id						= null;
	/** @var int */
	var $group					= null;
	/** @var text */
	var $params					= null;

	/**
	* @param database A database connector object
	*/
	function AECMI_rafflelist( &$db )
	{
		$this->mosDBTable( '#__acctexp_mi_rafflelist', 'id', $db );
	}

	function declareParamFields()
	{
		return array( 'params' );
	}

	function loadMax( $group=null ) {
		global $database;

		$query = 'SELECT max(`id`)'
			. ' FROM #__acctexp_mi_rafflelist'
			;

		if ( !empty( $group ) ) {
			$query .= ' WHERE `group` = \'' . $group . '\'';
		}

		$database->setQuery( $query );
		return $this->load( $database->loadResult() );
	}

	function closeRun( $winid )
	{
		global $database;

		$participants = array();
		$winners = array();
		foreach ( $this->participants as $rid ) {
			$raffleuser = new AECMI_raffleuser( $database );
			$raffleuser->load( $rafflelist->params->winid );

			$raffleuser->runs += 1;

			if ( $rid == $winid ) {
				$raffleuser->wins += 1;

				$winners[] = $raffleuser->userid;
			} else {
				$participants[] = $raffleuser->userid;
			}

			$raffleuser->storeload();
		}

		return array( 'participants' => $participants, 'winners' => $winners );
	}
}

class AECMI_raffleuser extends serialParamDBTable {
	/** @var int Primary key */
	var $id						= null;
	/** @var int */
	var $userid					= null;
	/** @var int */
	var $wins					= null;
	/** @var int */
	var $runs					= null;
	/** @var text */
	var $params					= null;

	/**
	* @param database A database connector object
	*/
	function AECMI_raffleuser( &$db )
	{
		$this->mosDBTable( '#__acctexp_mi_raffleuser', 'id', $db );
	}

	function declareParamFields()
	{
		return array( 'params' );
	}

	function loadUserid( $userid) {
		global $database;

		$query = 'SELECT `id`'
			. ' FROM #__acctexp_mi_raffleuser'
			. ' WHERE `userid` = \'' . $userid . '\''
			;
		$database->setQuery( $query );
		return $this->load( $database->loadResult() );
	}
}

?>