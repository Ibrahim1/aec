<?php
/**
 * @version $Id: mi_jomsocial.php
 * @package AEC - Account Control Expiration - Membership Manager
 * @subpackage Micro Integrations - JomSocial
 * @copyright 2006-2010 Copyright (C) David Deutsch
 * @author David Deutsch <skore@skore.de> & Team AEC - http://www.valanx.org
 * @license GNU/GPL v.2 http://www.gnu.org/licenses/old-licenses/gpl-2.0.html or, at your option, any later version
 */

( defined('_JEXEC') || defined( '_VALID_MOS' ) ) or die( 'Direct Access to this location is not allowed.' );

class mi_jomsocial
{
	function Info ()
	{
		$info = array();
		$info['name'] = _AEC_MI_NAME_JOMSOCIAL;
		$info['desc'] = _AEC_MI_DESC_JOMSOCIAL;

		return $info;
	}

	function Settings()
	{
		$db = &JFactory::getDBO();

		$settings = array();
		$settings['overwrite_existing']	= array( 'list_yesno' );
		$settings['assign_group']		= array( 'inputC' );
		$settings['remove_group']		= array( 'inputC' );
		$settings['assign_group_exp']	= array( 'inputC' );
		$settings['remove_group_exp']	= array( 'inputC' );
		$settings['set_fields']			= array( 'list_yesno' );
		$settings['set_fields_exp']		= array( 'list_yesno' );

		$query = 'SELECT `id`, `name`'
				. ' FROM #__community_fields'
				. ' WHERE `type` != \'group\''
				;
		$db->setQuery( $query );
		$objects = $db->loadObjectList();

		if ( !empty( $objects ) ) {
			foreach ( $objects as $object ) {
				$settings['jsfield_' . $object->id] = array( 'inputE', $object->name, $object->name );
				$expname = $object->name . " " . _MI_MI_JOMSOCIAL_EXPMARKER;
				$settings['jsfield_' . $object->id . '_exp' ] = array( 'inputE', $expname, $expname );
			}
		}

		$rewriteswitches			= array( 'cms', 'user', 'expiration', 'subscription', 'plan', 'invoice' );

		$settings					= AECToolbox::rewriteEngineInfo( $rewriteswitches, $settings );

		return $settings;
	}

	function relayAction( $request )
	{
		if ( ( $request->action == 'action' ) || ( $request->action == 'expiration_action' ) ) {
			$db = &JFactory::getDBO();

			if ( $this->settings['set_fields'.$request->area] ) {
				$query = 'SELECT `id`, `name`'
						. ' FROM #__community_fields'
						. ' WHERE `type` != \'group\''
						;
				$db->setQuery( $query );
				$objects = $db->loadObjectList();

				foreach ( $objects as $object ) {
					if ( isset( $this->settings['jsfield_' . $object->id.$request->area] ) ) {
						if ( $this->settings['jsfield_' . $object->id.$request->area] !== '' ) {
							$changes[$object->id] = AECToolbox::rewriteEngineRQ( $this->settings['jsfield_' . $object->id.$request->area], $request );
						}
					}
				}

				if ( !empty( $changes ) ) {
					$this->setFields( $changes, $request->metaUser->userid );
				}

				if ( !empty( $this->settings['assign_group'.$request->area] ) ) {
					if ( strpos( ',', $this->settings['assign_group'.$request->area] ) !== false ) {
						$grouplist = explode( ',', $this->settings['assign_group'.$request->area] );
					} else {
						$grouplist = array( $this->settings['assign_group'.$request->area] );
					}

					foreach ( $grouplist as $groupid ) {
						$this->addToGroup( $request->metaUser->userid, $groupid );
					}
				}

				if ( !empty( $this->settings['remove_group'.$request->area] ) ) {
					if ( strpos( ',', $this->settings['remove_group'.$request->area] ) !== false ) {
						$grouplist = explode( ',', $this->settings['remove_group'.$request->area] );
					} else {
						$grouplist = array( $this->settings['remove_group'.$request->area] );
					}

					foreach ( $grouplist as $groupid ) {
						$this->removeFromGroup( $request->metaUser->userid, $groupid );
					}
				}
			}
		}
	}

	function setFields( $fields, $userid )
	{
		$db = &JFactory::getDBO();

		$ids = array();
		foreach ( $fields as $fi => $ff ) {
			$ids[] = $fi;
		}

		$query = 'SELECT `field_id`, `value`'
				. ' FROM #__community_fields_values'
					. ' WHERE `field_id` IN (' . implode( ',', $ids ) . ')'
					. ' AND `user_id` = \'' . (int) $userid . '\'';
				;
		$db->setQuery( $query );
		$existingfields = $db->loadObjectList();

		foreach( $fields as $id => $value ) {
			$existingfield = false;
			if ( !empty( $existingfields ) ) {
				foreach ( $existingfields as $ff ) {
					if ( $ff->field_id == $id ) {
						$existingfield = true;

						continue;
					}
				}
			}

			$query = null;
			if ( $existingfield && $this->settings['overwrite_existing'] ) {
				$query	= 'UPDATE #__community_fields_values SET '
						. ' `value` = \'' . $value . '\''
						. ' WHERE `user_id` = \'' . (int) $userid . '\''
						. ' AND `field_id` = \'' . (int) $id . '\''
						;
			} elseif ( !$existingfield ) {
				$query	= 'INSERT INTO #__community_fields_values'
						. ' (`user_id`, `field_id`, `value` )'
						. ' VALUES ( \'' . (int) $userid . '\', \'' . (int) $id . '\', \'' . $db->getEscaped( $value ) . '\' )'
						;
			}

			if ( !empty( $query ) ) {
				$db->setQuery( $query );
				$db->query();
			}
		}
	}

	function addToGroup( $userid, $groupid )
	{
		$db = &JFactory::getDBO();

		// Check whether group exists
		$query = 'SELECT `id`'
				. ' FROM #__community_groups'
					. ' WHERE `id` = \'' . (int) $groupid . '\''
				;
		$db->setQuery( $query );

		if( $db->loadResult() ) {
			// Check whether user already has the group
			$query = 'SELECT `groupid`'
					. ' FROM #__community_groups_members'
						. ' WHERE `groupid` = \'' . (int) $groupid . '\''
						. ' AND `memberid` = \'' . (int) $userid . '\''
					;
			$db->setQuery( $query );

			if( !$db->loadResult() ) {
				$query	= 'INSERT INTO #__community_groups_members'
						. ' (`groupid`, `memberid`, `approved` )'
						. ' VALUES ( \'' . (int) $groupid . '\', \'' . (int) $userid . '\', \'1\' )'
						;

				$db->setQuery( $query );
				$db->query();
			}
		}
	}

	function removeFromGroup( $userid, $groupid )
	{
		$db = &JFactory::getDBO();

		// Check whether group exists
		$query = 'SELECT `id`'
				. ' FROM #__community_groups'
					. ' WHERE `id` = \'' . (int) $groupid . '\''
				;
		$db->setQuery( $query );

		if( $db->loadResult() ) {
			$query	= 'DELETE'
					. ' FROM #__community_groups_members'
					. ' WHERE `groupid` = \'' . (int) $groupid . '\''
					. ' AND `memberid` = \'' . (int) $userid . '\''
					;

			$db->setQuery( $query );
			$db->query();
		}
	}
}
?>
