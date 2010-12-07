<?php
/**
 * @version $Id: mi_acymail.php
 * @package AEC - Account Control Expiration - Membership Manager
 * @subpackage Micro Integrations - Kunena
 * @copyright Copyright (C) 2010 David Deutsch
 * @author David Deutsch <skore@skore.de> & Team AEC - http://www.valanx.org
 * @license GNU/GPL v.2 http://www.gnu.org/licenses/old-licenses/gpl-2.0.html or, at your option, any later version
 */

// Dont allow direct linking
defined('_JEXEC') OR defined( '_VALID_MOS' ) OR die( 'Direct Access to this location is not allowed.' );

class mi_acymail extends MI
{
	function Info()
	{
		$info = array();
		$info['name'] = _AEC_MI_NAME_ACYMAIL;
		$info['desc'] = _AEC_MI_DESC_ACYMAIL;

		return $info;
	}

	function Settings()
	{
		$settings = array();

		if ( !file_exists( rtrim( JPATH_ROOT, DS ) . DS . 'administrator' . DS . 'components' . DS . 'com_acymailing' . DS . 'helpers' . DS . 'list.php' ) ) {
			echo 'This module can not work without the ACY Mailing Component';

			return $settings;
		} else {
			@include_once( rtrim( JPATH_ADMINISTRATOR, DS ).DS.'components'.DS.'com_acymailing'.DS.'helpers'.DS.'helper.php' );
		}

		$db = &JFactory::getDBO();
		$db->setQuery( 'SELECT * FROM #__acymailing_list' );

		$lists = $db->loadObjectList();

		$listslist = array();
		$listslist[] = JHTML::_('select.option', 0, "--- --- ---" );

		foreach ( $lists as $id => $row ) {
			$listslist[] = JHTML::_('select.option', $row->listid, $row->listid . ': ' . $row->name );
		}

		$settings['addlist']	= array( 'list' );
		$settings['removelist']	= array( 'list' );

		$settings = $this->autoduplicatesettings( $settings );

		foreach ( $settings as $k => $v ) {
			if ( isset( $this->settings[$k] ) ) {
				$settings['lists'][$k]	= JHTML::_( 'select.genericlist', $listslist, $k.'[]', 'size="4" multiple="multiple"', 'value', 'text', $this->settings[$k] );
			} else {
				$settings['lists'][$k]	= JHTML::_( 'select.genericlist', $listslist, $k.'[]', 'size="4" multiple="multiple"', 'value', 'text', '' );
			}
		}

		$xsettings = array();
		$xsettings['user_checkbox']		= array( 'list_yesno' );
		$xsettings['custominfo']		= array( 'inputD' );

		return array_merge( $xsettings, $settings );
	}

	function getMIform( $request )
	{
		$settings = array();

		if ( empty( $this->settings['user_checkbox'] ) ) {
			return $settings;
		}

		if ( !empty( $this->settings['custominfo'] ) ) {
			$settings['exp'] = array( 'p', "", $this->settings['custominfo'] );
		} else {
			$settings['exp'] = array( 'p', "", _MI_MI_ACAJOOM_DEFAULT_NOTICE );
		}

		$settings['get_newsletter'] = array( 'checkbox', 'mi_'.$this->id.'_get_newsletter', 1, 0, "Sign up to our Newsletter" );

		return $settings;
	}

	function relayAction( $request )
	{
		$new_allowed = false;

		if ( empty( $this->settings['user_checkbox'] ) ) {
			$new_allowed = true;
		} elseif ( !empty( $this->settings['user_checkbox'] ) && !empty( $request->params['get_newsletter'] ) ) {
			$new_allowed = true;
		}

		$subscriber = $this->getSubscriber( $request, $new_allowed );

		if ( empty( $subscriber ) ) {
			return null;
		}

		if ( empty( $subscriber->confirmed ) && $config->get( 'require_confirmation',false ) ) {
			$statusAdd = 2;
		} else {
			$statusAdd = 1;
		}

		$lists = array();
		if ( !empty( $this->settings['addlist' . $request->area] ) && $new_allowed ) {
			foreach ( $this->settings['addlist' . $request->area] as $list ) {
				$lists[$list] = array( 'status' => $statusAdd );
			}
		}

		if ( !empty( $this->settings['removelist' . $request->area] ) ) {
			$lists[$list] = array( 'status' => 0 );
		}

		if ( !empty( $lists ) ) {
			$subscriber->saveSubscription( $subscriber->id, $lists );
		}
	}

	function getSubscriber( $request, $new_allowed )
	{
		$config = acymailing::config();

		$subid = $userClass->subid( $request->metaUser->cmsUser->email );

		if ( empty( $subid ) && !$new_allowed ) {
			return false;
		} elseif ( empty( $subid ) ) {
			$joomUser = new stdClass();

			$joomUser->email = $request->metaUser->cmsUser->email;
			$joomUser->name = $request->metaUser->cmsUser->name;

			if ( empty( $request->metaUser->cmsUser->block ) ) {
				$joomUser->confirmed = 1;
			}

			$joomUser->enabled = $config->get('require_confirmation',false) ? (1 - (int)$user->block) : 1;
			$joomUser->userid = $request->metaUser->userid;

			$userClass = acymailing::get('class.subscriber');

			$userClass->checkVisitor = false;
			$userClass->sendConf = false;

			$subid = $userClass->save( $joomUser );
		}

		$userClass = acymailing::get('class.subscriber');

		return $userClass->get( $subid );
	}

}
?>
