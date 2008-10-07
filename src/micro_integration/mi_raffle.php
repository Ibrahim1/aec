<?php
/**
 * @version $Id: mi_raffle.php
 * @package AEC - Account Control Expiration - Membership Manager
 * @subpackage Micro Integrations - Raffle
 * @copyright 2006-2008 Copyright (C) David Deutsch
 * @author David Deutsch <skore@skore.de> & Team AEC - http://www.globalnerd.org
 * @license GNU/GPL v.2 http://www.gnu.org/licenses/old-licenses/gpl-2.0.html or, at your option, any later version
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

		return true;
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

		if ( $raffleuser->runs >= $this->settings['max_participations'] ) {
			return null;
		}

		$list_group = empty( $this->settings['list_group'] ) ? 0 : $this->settings['list_group'];

		$rafflelist = new AECMI_rafflelist( $database );

		if ( $rafflelist->loadMax( $list_group ) === false ) {
			$rafflelist->id = 0;
			$rafflelist->group = $list_group;

			$rafflelist->params = new stdClass();

			$rafflelist->params->participants = array();
			$rafflelist->params->settings = array();
			$rafflelist->params->settings['draw_range'] = $this->settings['draw_range'];
		}

		if ( in_array( $raffleuser->id, $rafflelist->params->participants ) ) {
			return null;
		}

		$rafflelist->params->participants[] = $raffleuser->id;

		$miinfo = array();
		$miinfo['listid']		= $rafflelist->id;
		$miinfo['sequenceid']	= count( $rafflelist->params->participants );

		$request->metaUser->meta->setMIParams( $request->parent->id, $request->plan->id, $miinfo );
		$request->metaUser->meta->storeload();

		if ( count( $rafflelist->params->participants ) >= $rafflelist->params->settings['draw_range'] ) {
			global $mainframe, $mosConfig_sitename;

			$range = (int) $rafflelist->params->settings['draw_range'];

			$winner = rand( 1, $range );

			$rafflelist->params->winid = $rafflelist->params->participants[($winner-1)];

			$result = $rafflelist->closeRun( $rafflelist->params->winid );

			// TODO: Multiple winners
			$winnerMeta = new metaUser( $result['winners'][0] );

			$colET = 'The current draw results are in:' . "\n" . "\n";
			$colET .= 'List ID: ' . $rafflelist->id . "\n" . "\n";
			$colET .= 'Winner:' . "\n";
			$colET .= 'Sequence ID:' . count( $rafflelist->params->participants ) . "\n";
			$colET .= 'Userid: ' . $winnerMeta->userid . '; Username: ' . $winnerMeta->username . '; Email: ' . $winnerMeta->email . "\n" . "\n";
			$colET .= 'Further Participants:' . "\n" . "\n";

			foreach ( $result['participants'] as $userid ) {
				$u = null;

				$query = 'SELECT `username`, `email`'
					. ' FROM #__users'
					. ' WHERE `id` = \'' . $userid . '\'';
					;
				$database->setQuery( $query );
				$database->loadObject( $u );

				$colET .= $u->userid . ';' . $u->username . ';' . $u->email . "\n";
			}

			// check if Global Config `mailfrom` and `fromname` values exist
			if ( $mainframe->getCfg( 'mailfrom' ) != '' && $mainframe->getCfg( 'fromname' ) != '' ) {
				$adminName2 	= $mainframe->getCfg( 'fromname' );
				$adminEmail2 	= $mainframe->getCfg( 'mailfrom' );
			} else {
				// use email address and name of first superadmin for use in email sent to user
				$query = 'SELECT `name`, `email`'
						. ' FROM #__users'
						. ' WHERE LOWER( usertype ) = \'superadministrator\''
						. ' OR LOWER( usertype ) = \'super administrator\''
						;
				$database->setQuery( $query );
				$rows = $database->loadObjectList();

				$adminName2 	= $rows[0]->name;
				$adminEmail2 	= $rows[0]->email;
			}

			$recipients = explode( ',', $this->settings['col_recipient'] );

			foreach ( $recipients as $current => $email ) {
				$recipients[$current] = AECToolbox::rewriteEngineRQ( trim( $email ), $request );
			}

			$subject = 'Raffle Drawing Results for ' . $mosConfig_sitename;

			mosMail( $adminEmail2, $adminName2, $recipients, $subject, $colET );
		}

		$rafflelist->check();
		$rafflelist->store();

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
		$id = $database->loadResult();
		if ( empty( $id ) ) {
			return false;
		} else {
			return $this->load( $id );
		}
	}

	function closeRun( $winid )
	{
		global $database;

		$participants = array();
		$winners = array();
		foreach ( $this->params->participants as $rid ) {
			$raffleuser = new AECMI_raffleuser( $database );
			$raffleuser->load( $rid );

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
