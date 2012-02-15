<?php
/**
 * @version $Id: mi_joomlauser.php
 * @package AEC - Account Control Expiration - Membership Manager
 * @subpackage Micro Integrations - Joomla User
 * @copyright 2006-2012 Copyright (C) David Deutsch
 * @author David Deutsch <skore@valanx.org> & Team AEC - http://www.valanx.org
 * @license GNU/GPL v.2 http://www.gnu.org/licenses/old-licenses/gpl-2.0.html or, at your option, any later version
 */

// Dont allow direct linking
( defined('_JEXEC') || defined( '_VALID_MOS' ) ) or die( 'Direct Access to this location is not allowed.' );

class mi_joomlauser
{
	function Info()
	{
		$info = array();
		$info['name'] = JText::_('AEC_MI_NAME_JOOMLAUSER');
		$info['desc'] = JText::_('AEC_MI_DESC_JOOMLAUSER');

		return $info;
	}

	function Settings()
	{
		$settings = array();
		$settings['activate']		= array( 'toggle' );
		$settings['block']			= array( 'toggle' );
		$settings['username']		= array( 'inputD' );
		$settings['username_rand']	= array( 'inputC' );
		$settings['password']		= array( 'inputD' );

		if ( defined( 'JPATH_MANIFESTS' ) ) {
			$settings['set_fields']		= array( 'toggle' );
			$settings['set_fields_exp']	= array( 'toggle' );

			$db = &JFactory::getDBO();

			$query = 'SELECT DISTINCT `profile_key`'
					. ' FROM #__user_profiles';
			$db->setQuery( $query );
			$pkeys = $db->loadResultArray();

			if ( !empty( $pkeys ) ) {
				foreach ( $pkeys as $k ) {
					if ( strpos( $object->title, '_' ) === 0 ) {
						$title = $object->name;
					} else {
						$title = $object->title;
					}

					$settings['jprofile_' . str_replace( ".", "_", $k )] = array( 'inputE', $title, $title );
					$expname = $title . " "  . JText::_('MI_MI_COMMUNITYBUILDER_EXPMARKER');
					$settings['jprofile_' . str_replace( ".", "_", $k ) . '_exp' ] = array( 'inputE', $expname, $expname );
				}
			}
		}

		$rewriteswitches			= array( 'cms', 'user', 'expiration', 'subscription', 'plan', 'invoice' );

		$settings					= AECToolbox::rewriteEngineInfo( $rewriteswitches, $settings );

		return $settings;
	}

	function action( $request )
	{
		$db = &JFactory::getDBO();

		$set = array();

		if ( $this->settings['activate'] ) {
			$set[] = '`block` = \'0\'';
			$set[] = '`activation` = \'\'';
		}

		$username = $this->getUsername( $request );

		if ( !empty( $username ) ) {
			$set[] = '`username` = \'' . $username . '\'';
		}

		if ( !empty( $this->settings['password'] ) ) {
			$pw = AECToolbox::rewriteEngineRQ( $this->settings['password'], $request );

			jimport('joomla.user.helper');

			$salt  = JUserHelper::genRandomPassword( 32 );
			$crypt = JUserHelper::getCryptedPassword( $pw, $salt );
			$password = $crypt.':'.$salt;

			$set[] = '`password` = \'' . $password . '\'';
		}

		if ( !empty( $set ) ) {
			$query = 'UPDATE #__users';
			$query .= ' SET ' . implode( ', ', $set );
			$query .= ' WHERE `id` = \'' . (int) $request->metaUser->userid . '\'';

			$db->setQuery( $query );
			$db->query() or die( $db->stderr() );

			$userid = $request->metaUser->userid;

			// Reloading metaUser object for other MIs
			$request->metaUser = new metaUser( $userid );
		}

		if ( !empty( $this->settings['set_fields'] ) ) {
			$this->setFields( $request->metaUser->userid );
		}
	}

	function getUsername( $request )
	{
		if ( !empty( $this->settings['username_rand'] ) ) {
			$db = &JFactory::getDBO();

			$numberofrows	= 1;
			while ( $numberofrows ) {
				$uname =	strtolower( substr( base64_encode( md5( rand() ) ), 0, $this->settings['username_rand'] ) );
				// Check if already exists
				$query = 'SELECT count(*)'
						. ' FROM #__users'
						. ' WHERE `username` = \'' . $uname . '\''
						;
				$db->setQuery( $query );
				$numberofrows = $db->loadResult();
			}

			return $uname;
		} elseif ( !empty( $this->settings['username'] ) ) {
			return AECToolbox::rewriteEngineRQ( $this->settings['username'], $request );
		}
	}

	function expiration_action( $request )
	{
		if ( $this->settings['block'] ) {
			$db = &JFactory::getDBO();

			$query = 'UPDATE #__users'
				. ' SET `block` = \'1\''
				. ' WHERE `id` = \'' . (int) $request->metaUser->userid . '\''
				;

			$db->setQuery( $query );
			$db->query() or die( $db->stderr() );
		}

		if ( !empty( $this->settings['set_fields_exp'] ) ) {
			$this->setFields( $request->metaUser->userid, '_exp' );
		}
	}

	function setFields( $request, $stage="" )
	{
		$query = 'SELECT `profile_key`, `profile_value`'
				. ' FROM #__user_profiles'
				. ' WHERE `user_id` = \'' . $this->data['metaUser']->userid . '\'';
		$db->setQuery( $query );
		$objects = $db->loadObjectList();

		$changes = $additions = array();
		foreach ( $objects as $object ) {
			if ( !empty( $this->settings['jprofile_' . str_replace( ".", "_", $object->profile_key ) . $stage] ) ) {
				$changes[$object->name] = $this->settings['jprofile_' . str_replace( ".", "_", $object->profile_key ) . $stage];
			}
		}

		if ( !empty( $changes ) ) {
			$alterstring = array();
			foreach ( $changes as $name => $value ) {
				if ( ( $value === 0 ) || ( $value === "0" ) ) {
					$alterstring[] = "`" . $name . "`" . ' = \'0\'';
				} elseif ( ( $value === 1 ) || ( $value === "1" ) ) {
					$alterstring[] = "`" . $name . "`" . ' = \'1\'';
				} elseif ( strcmp( $value, 'NULL' ) === 0 ) {
					$alterstring[] = "`" . $name . "`" . ' = NULL';
				} else {
					$alterstring[] = "`" . $name . "`" . ' = \'' . AECToolbox::rewriteEngineRQ( $value, $request ) . '\'';
				}
			}

			$query = 'UPDATE #__comprofiler'
					. ' SET ' . implode( ', ', $alterstring )
					. ' WHERE `user_id` = \'' . (int) $request->metaUser->userid . '\''
					;
			$db->setQuery( $query );
			$db->query() or die( $db->stderr() );
		}
	}

}

?>