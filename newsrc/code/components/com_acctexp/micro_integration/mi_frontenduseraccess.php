<?php
/*
AEC micro-integration plugin
for Frontend-User-Access
connects subscription plans to Frontend-User-Access-usergroups
version 2.0.2
*/


// Dont allow direct linking
( defined('_JEXEC') || defined( '_VALID_MOS' ) ) or die( 'Direct Access to this location is not allowed.' );

class mi_frontenduseraccess
{

	function Info()
	{
		$info = array();
		$info['name'] = _AEC_MI_NAME_FRONTENDUSERACCESS;
		$info['desc'] = _AEC_MI_DESC_FRONTENDUSERACCESS;

		return $info;
	}

	function Settings()
	{
		$db = &JFactory::getDBO();

		$query = 'SELECT `id`, `name`'
				. ' FROM #__fua_usergroups'
				. ' WHERE id <> 9 AND id <> 10'
				. ' ORDER BY name ASC'
				;
		$db->setQuery( $query );

		$groups = $db->loadObjectList();

		$fuagroups = array();
		$fuagroups[] = JHTML::_('select.option', 0, 'no group');

		if ( !empty( $groups ) ) {
			foreach ( $groups as $group ) {
				$fuagroups[] = JHTML::_('select.option', $group->id, $group->name );
			}
		}

		//if no array, reformat to make into array, as of version 2 of this MI, needed for FUA from version 3.1.0 and up.
		if ( !empty( $this->settings['group'] ) ) {
			if( !is_array( $this->settings['group'] ) ) {
				$this->settings['group'] = array( $this->settings['group'] );
			}
		} else {
			$this->settings['group'] = array();;
		}

		if ( isset( $this->settings['group_exp'] ) ) {
			if ( !is_array($this->settings['group_exp'] ) ) {
				$this->settings['group_exp'] = array( $this->settings['group_exp'] );
			}
		} else {
			$this->settings['group_exp'] = array();
		}


		if ( !empty( $this->settings['group'] ) ) {
			$fua_groups = array();

			foreach ( $this->settings['group'] as $temp ) {
				$fua_groups[]->value = $temp;
			}
		} else {
			$fua_groups	= '';
		}

		if ( !empty( $this->settings['group_exp'] ) ) {
			$fua_groups_exp = array();

			foreach ( $this->settings['group_exp'] as $temp ) {
				$fua_groups_exp[]->value = $temp;
			}
		} else {
			$fua_groups_exp	= '';
		}

		$settings = array();

		$settings['lists']['group']		= JHTML::_('select.genericlist', $fuagroups, 'group[]', 'size="7" multiple="true"', 'value', 'text', $fua_groups );
		$settings['lists']['group_exp'] = JHTML::_('select.genericlist', $fuagroups, 'group_exp[]', 'size="7" multiple="true"', 'value', 'text', $fua_groups_exp );

		$settings['set_group']			= array( 'list_yesno' );
		$settings['group']				= array( 'list' );
		$settings['keep_groups']		= array( 'list_yesno' );
		$settings['set_group_exp']		= array( 'list_yesno' );
		$settings['group_exp']			= array( 'list' );
		$settings['keep_groups_exp']	= array( 'list_yesno' );
		$settings['rebuild']			= array( 'list_yesno' );
		$settings['remove']				= array( 'list_yesno' );

		return $settings;
	}

	function action( $request )
	{
		if ( !empty( $this->settings['set_group'] ) && !empty( $this->settings['group'] ) ) {
			if ( !isset( $this->settings['keep_groups'] ) ) {
				$this->settings['keep_groups'] = false;
			}

			$this->update_fua_group( $request->metaUser->userid, $this->settings['group'], $this->settings['keep_groups'] );
		}

		return true;
	}

	function expiration_action( $request )
	{
		if ( !empty( $this->settings['set_group_exp'] ) && !empty( $this->settings['group_exp'] ) ) {
			if ( !isset( $this->settings['keep_groups_exp'] ) ) {
				$this->settings['keep_groups_exp'] = false;
			}

			$this->update_fua_group( $request->metaUser->userid, $this->settings['group_exp'], $this->settings['keep_groups_exp'] );
		}

		return true;
	}

	function update_fua_group( $user_id, $fua_group, $keep_groups )
	{
		$db = &JFactory::getDBO();

		$query = 'SELECT user_id, group_id'
				. ' FROM #__fua_userindex'
				. ' WHERE user_id = \'' . $user_id . '\' '
				." LIMIT 1 "			
				;
		$db->setQuery( $query );

		$fua_user = $db->loadObject();

		if ( ( $fua_user->user_id == $user_id ) && $keep_groups ) {
			$groups = array_unique( array_merge( $fua_group, $this->csv_to_array( $fua_user->group_id ) ) );
		} else {
			$groups = $fua_group;
		}

		sort( $groups );

		$fua_group = $this->array_to_csv( $groups );

		if ( $fua_user->user_id == $user_id ) {		
			$query = 'INSERT INTO #__fua_userindex'
					. ' SET group_id = \'' . $fua_group . '\', user_id = \'' . $user_id . '\' '
					;
		} else {	
			$query = 'UPDATE #__fua_userindex'
					. ' SET `group_id` = \'' . $fua_group . '\''
					. ' WHERE `user_id` = \'' . $user_id . '\''
					;
		}

		$db->setQuery( $query );
		$db->query();
	}

	function csv_to_array($json){		
		$array = array();
		$temp = explode(',', $json);
		for($n = 0; $n < count($temp); $n++){
			$value = str_replace('"','',$temp[$n]);
			$array[] = $value;
		}
		return $array;
	}

	function array_to_csv($array){	
		$return = '';	
		for($n = 0; $n < count($array); $n++){
			if($n){
				$return .= ',';
			}
			$row = each($array);
			$value = $row['value'];
			if(is_string($value)){
				$value = addslashes($value);
			}	
			$return .= '"'.$value.'"';		
		}		
		return $return;
	}
	
	function detect_application()
	{
		return is_dir( JPATH_SITE . '/components/com_frontenduseraccess' );
	}

}

?>