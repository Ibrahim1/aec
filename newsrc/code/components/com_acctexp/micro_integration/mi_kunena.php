<?php
/**
 * @version $Id: mi_kunena.php
 * @package AEC - Account Control Expiration - Membership Manager
 * @subpackage Micro Integrations - Kunena
 * @copyright Copyright (C) 2010 David Deutsch
 * @author David Deutsch <skore@skore.de> & Team AEC - http://www.valanx.org
 * @license GNU/GPL v.2 http://www.gnu.org/licenses/old-licenses/gpl-2.0.html or, at your option, any later version
 */

// Dont allow direct linking
defined('_JEXEC') OR defined( '_VALID_MOS' ) OR die( 'Direct Access to this location is not allowed.' );

class mi_kunena extends MI
{
	function Info()
	{
		$info = array();
		$info['name'] = _AEC_MI_NAME_KUNENA;
		$info['desc'] = _AEC_MI_DESC_KUNENA;

		return $info;
	}

	function Settings()
	{
		$settings = array();

		if ( !@include_once( rtrim( JPATH_ROOT, DS ) . DS . 'components' . DS . 'com_kunena' . DS . 'lib' . DS . 'kunena.user.class.php' ) ) {
			echo 'This module can not work without the Kunena Forum Component';
			return $settings;
		}

		$database = &JFactory::getDBO();
		$database->setQuery( 'SELECT * FROM #__fb_ranks' );

		$ranks = $database->loadObjectList();

		$ranklist = array();
		$ranklist[] = mosHTML::makeOption ( 0, "--- --- ---" );

		foreach ( $ranks as $id => $row ) {
			$ranklist[] = mosHTML::makeOption ( $row->rank_id, $row->rank_id . ': ' . $row->rank_title );
		}

		$settings['rank']	= array( 'list' );
		$settings['unrank']	= array( 'list' );

		$settings = $this->autoduplicatesettings( $settings );

		foreach ( $settings as $k => $v ) {
			if ( isset( $this->settings[$k] ) ) {
				$settings['lists'][$k]	= mosHTML::selectList( $ranklist, $k, 'size="1"', 'value', 'text', $this->settings[$k] );
			} else {
				$settings['lists'][$k]	= mosHTML::selectList( $ranklist, $k, 'size="1"', 'value', 'text', '' );
			}
		}

		$settings['rebuild']	= array( 'list_yesno' );
		$settings['remove']		= array( 'list_yesno' );

		return $settings;
	}

	function relayAction( $request )
	{
		if ( !empty( $this->settings['rank' . $request->area] ) || !empty( $this->settings['unrank' . $request->area] ) ) {
			$this->changeRank( $request->metaUser->userid, $this->settings['rank' . $request->area], $this->settings['unrank' . $request->area] );
		}
	}

	function changeRank( $userid, $add, $remove )
	{
		$database = &JFactory::getDBO();

		$query = 'SELECT `userid`, `group_id`'
				. ' FROM #__fb_users'
				. ' WHERE `userid` = \'' . $userid . '\''
				;
		$database->setQuery( $query );

		$kuser = $database->loadObject();

		if ( isset( $kuser->group_id ) ) {
			$rank = $kuser->group_id;
		} else {
			$rank = 0;
		}

		if ( !empty( $remove ) && $kuser->userid ) {
			if ( $remove == $rank ) {
				$newrank = 0;
			}
		}

		if ( !empty( $add ) && $kuser->userid ) {
			if ( $add == $rank ) {
				// Already in the correct usergroup
				return null;
			} else {
				$newrank = $add;
			}
		}

		if ( !isset( $rank ) && !empty( $add ) ) {
			$rank = $add;
		} else {
			// Nothing to do
			return null;
		}

		if ( $kuser->userid ) {
			$query = 'UPDATE #__fb_users'
					. ' SET `group_id` = \'' . $newrank . '\''
					. ' WHERE `userid` = \'' . $userid . '\''
					;
		} else {
			$query = 'INSERT INTO #__fb_users'
					. ' ( `group_id` , `userid` )'
					. ' VALUES (\'' . $newrank . '\', \'' . $userid . '\')'
					;
		}

		$database->setQuery( $query );
		$database->query();
	}

}