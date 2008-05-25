<?php
/**
 * @version $Id: mi_g2.php 16 2007-07-02 13:29:29Z mic $
 * @package AEC - Account Control Expiration - Subscription component for Joomla! OS CMS
 * @subpackage Micro Integrations - G2
 * @copyright 2008 Copyright (C) David Deutsch
 * @author David Deutsch <skore@skore.de> & Team AEC - http://www.globalnerd.org
 * @license GNU/GPL v.2 http://www.gnu.org/copyleft/gpl.html
 */

// Dont allow direct linking
defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );

class mi_g2 extends MI
{
	function Info()
	{
		$info = array();
		$info['name'] = '';
		$info['desc'] = '';

		return $info;
	}

	function checkInstallation()
	{
		return true;
	}

	function install()
	{
		return;
	}

	function Settings()
	{
		$settings = array();
		$settings['param1'] = array( 'inputA' );
		$settings['param2'] = array( 'inputD' );

		$query = 'SELECT `g_id`, `g_groupType`, `g_groupName`'
			 	. ' FROM g2_Group'
			 	;
	 	$database->setQuery( $query );
	 	$groups = $database->loadObjectList();

		$g = explode( ';', $params['group'] );
		$sg = array();
		$ge = explode( ';', $params['group_exp'] );
		$sge = array();

		$gr = array();
		foreach( $groups as $group ) {
			$desc = $group->groups_name . ' - ' . substr( strip_tags( $group->groups_description ), 0, 30 );

			$gr[] = mosHTML::makeOption( $group->groups_id, $desc );

			if ( in_array( $group->groups_id, $ge ) ) {
				$sg[] = mosHTML::makeOption( $group->groups_id, $desc );
			}
			if ( in_array( $group->groups_id, $g ) ) {
				$sge[] = mosHTML::makeOption( $group->groups_id, $desc );
			}
		}

		$sel_groups = array();
		foreach ($groups as $name ) {
			$selected_groups[] = mosHTML::makeOption( $name, $name );
		}

		return $settings;
	}

	function pre_expiration_action( $request )
	{}

	function expiration_action( $request )
	{}

	function action( $request )
	{}

	function on_userchange_action( $request )
	{}

	function delete( $params )
	{}

	function profile_info( $userid )
	{}

	function G2userid( $metaUser )
	{
		global $database;

		$query = 'SELECT g_id'
				. ' FROM g2_User'
				. ' WHERE `g_username` = \'' . $metaUser->username . '\''
				;
		$database->setQuery( $query );

		$g2id = $database->loadResult();

		if ( $g2id ) {
			// User found, return id
			return $g2id;
		} else {
			// User not found, create user, then recurse
			$this->createG2User( $metaUser );
			$this->G2userid( $metaUser);
		}
	}

	function createG2User( $metaUser )
	{
		global $database;

		$query = 'INSERT INTO g2_User'
				. ' ( `g_userName`, `g_fullName`, `g_hashedPassword`, `g_email` )'
				. ' VALUES ( \'' . $metaUser->username . '\', \'' . $metaUser->name . '\', \'' . $metaUser->password . '\', \'' . $metaUser->email . '\' )'
				;
		$database->setQuery( $query );

		if ( $database->query() ) {
			return true;
		} else {
			$this->setError( $database->getErrorMsg() );
			return false;
		}
	}
}

?>