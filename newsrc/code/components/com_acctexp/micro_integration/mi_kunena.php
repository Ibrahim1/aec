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
		$info['name'] = JText::_('_AEC_MI_NAME_KUNENA');
		$info['desc'] = JText::_('_AEC_MI_DESC_KUNENA');

		return $info;
	}

	function Settings()
	{
		$settings = array();

		if ( $this->is16() ) {
			$checkpath = rtrim( JPATH_ROOT, DS ) . DS . 'administrator' . DS . 'components' . DS . 'com_kunena' . DS . 'libraries' . DS . 'tables' . DS . 'kunenauser.php';
		} else {
			$checkpath = rtrim( JPATH_ROOT, DS ) . DS . 'components' . DS . 'com_kunena' . DS . 'lib' . DS . 'kunena.user.class.php';
		}

		if ( !@include_once( $checkpath ) ) {
			echo 'This module can not work without the Kunena Forum Component';
			return $settings;
		}

		$db = &JFactory::getDBO();
		$db->setQuery( 'SELECT * FROM #__' . $this->dbTable() . '_ranks WHERE `rank_special` = 1 ' );

		$ranks = $db->loadObjectList();

		$ranklist = array();
		$ranklist[] = JHTML::_('select.option', 0, "--- --- ---" );

		foreach ( $ranks as $id => $row ) {
			$ranklist[] = JHTML::_('select.option', $row->rank_id, $row->rank_id . ': ' . $row->rank_title );
		}

		$settings['rank']	= array( 'list' );
		$settings['unrank']	= array( 'list' );

		$settings = $this->autoduplicatesettings( $settings );

		foreach ( $settings as $k => $v ) {
			if ( isset( $this->settings[$k] ) ) {
				$settings['lists'][$k]	= JHTML::_( 'select.genericlist', $ranklist, $k, 'size="1"', 'value', 'text', $this->settings[$k] );
			} else {
				$settings['lists'][$k]	= JHTML::_( 'select.genericlist', $ranklist, $k, 'size="1"', 'value', 'text', '' );
			}
		}

		$xsettings = array();
		$xsettings['rebuild']	= array( 'list_yesno' );
		$xsettings['remove']	= array( 'list_yesno' );

		return array_merge( $xsettings, $settings );
	}

	function is16()
	{
		static $is;

		if ( is_null( $is ) ) {
			$app = JFactory::getApplication();

			$db = &JFactory::getDBO();

			$tables = $db->getTableList();

			return in_array( $app->getCfg( 'dbprefix' ) . "kunena_ranks", $tables );
		} else {
			return $is;
		}
	}

	function dbTable()
	{
		if ( $this->is16() ) {
			return 'kunena';
		} else {
			return 'fb';
		}
	}

	function relayAction( $request )
	{
		if ( !empty( $this->settings['rank' . $request->area] ) || !empty( $this->settings['unrank' . $request->area] ) ) {
			$this->changeRank( $request->metaUser->userid, $this->settings['rank' . $request->area], $this->settings['unrank' . $request->area] );
		}
	}

	function changeRank( $userid, $add, $remove )
	{
		$db = &JFactory::getDBO();

		$query = 'SELECT `userid`, `rank`'
				. ' FROM #__' . $this->dbTable() . '_users'
				. ' WHERE `userid` = \'' . $userid . '\''
				;

		$db->setQuery( $query );

		$kuser = $db->loadObject();

		if ( isset( $kuser->rank ) ) {
			$rank = $kuser->rank;
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

		if ( is_null($kuser) && !empty( $add ) ) {
			$newrank = $add;
		} 


		if ( $kuser->userid ) {
			$query = 'UPDATE #__' . $this->dbTable() . '_users'
					. ' SET `rank` = \'' . $newrank . '\''
					. ' WHERE `userid` = \'' . $userid . '\''
					;
		} else {
			$query = 'INSERT INTO #__' . $this->dbTable() . '_users'
					. ' ( `rank` , `userid` )'
					. ' VALUES (\'' . $newrank . '\', \'' . $userid . '\')'
					;
		}

		$db->setQuery( $query );
		$db->query();
	}

}

?>