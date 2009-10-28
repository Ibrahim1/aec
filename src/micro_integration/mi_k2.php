<?php
/**
 * @version $Id: mi_k2.php
 * @package AEC - Account Control Expiration - Membership Manager
 * @subpackage Micro Integrations - K2
 * @copyright 2006-2008 Copyright (C) David Deutsch
 * @author David Deutsch <skore@skore.de> & Team AEC - http://www.valanx.org
 * @license GNU/GPL v.2 http://www.gnu.org/licenses/old-licenses/gpl-2.0.html or, at your option, any later version
 */

// Dont allow direct linking
( defined('_JEXEC') || defined( '_VALID_MOS' ) ) or die( 'Direct Access to this location is not allowed.' );

class mi_k2
{
	function Info()
	{
		$info = array();
		$info['name'] = _AEC_MI_NAME_K2;
		$info['desc'] = _AEC_MI_DESC_K2;

		return $info;
	}

	function Settings()
	{
		$database = &JFactory::getDBO();

        $settings = array();
		$settings['set_group']		= array( 'list_yesno' );
		$settings['group']			= array( 'list' );
		$settings['set_group_exp']	= array( 'list_yesno' );
		$settings['group_exp']		= array( 'list' );
		$settings['rebuild']		= array( 'list_yesno' );
		$settings['remove']			= array( 'list_yesno' );

		$query = 'SELECT groups_id, name'
			 	. ' FROM #__k2_user_groups'
			 	;
	 	$database->setQuery( $query );
	 	$groups = $database->loadObjectList();

		$sg = array();
		$sge = array();

		$gr = array();
		if ( !empty( $groups ) ) {
			foreach( $groups as $group ) {
				$gr[] = mosHTML::makeOption( $group->id, $group->name );

				if ( !empty( $this->settings['group'] ) ) {
					if ( in_array( $group->id, $this->settings['group'] ) ) {
						$sg[] = mosHTML::makeOption( $group->id, $group->name );
					}
				}

				if ( !empty( $this->settings['group_exp'] ) ) {
					if ( in_array( $group->id, $this->settings['group_exp'] ) ) {
						$sge[] = mosHTML::makeOption( $group->id, $group->name );
					}
				}
			}
		}

		$settings['lists']['group']			= mosHTML::selectList( $gr, 'group', 'size="4"', 'value', 'text', $sg );
		$settings['lists']['group_exp'] 	= mosHTML::selectList( $gr, 'group_exp', 'size="4"', 'value', 'text', $sge );

		return $settings;
	}

	function expiration_action( $request )
	{
		$database = &JFactory::getDBO();

		if ( $this->settings['set_group_exp'] && !empty( $this->settings['group_exp'] ) ) {
			$this->AddUserToGroup( $request->metaUser->userid, $this->settings['group_exp'] );
		}

		return true;
	}

	function action( $request )
	{
		$database = &JFactory::getDBO();

		if ( $this->settings['set_group'] && !empty( $this->settings['group'] ) ) {
			$this->AddUserToGroup( $request->metaUser->userid, $this->settings['group'] );
		}

		return true;
	}

	function AddUserToGroup( $userid, $groupid )
	{
		$database = &JFactory::getDBO();

		$query = 'UPDATE #__k2_users'
			. ' SET `group` = \'' . $groupid . '\''
			. ' WHERE `userID` = \'' . $userid . '\''
			;
		$database->setQuery( $query );
		$database->query();

		return true;
	}

}

?>
