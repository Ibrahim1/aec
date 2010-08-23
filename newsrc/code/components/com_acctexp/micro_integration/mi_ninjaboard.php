<?php
/**
 * @version $Id: mi_ninjaboard.php 01 2007-08-11 13:29:29Z SBS $
 * @package AEC - Account Control Expiration - Subscription component for Joomla! OS CMS
 * @subpackage Micro Integrations - Ninjaboard
 * @copyright 2010 Copyright (C) David Deutsch
 * @author Stian Didriksen, David Deutsch & Team AEC - http://www.valanx.org
 * @license GNU/GPL v.2 http://www.gnu.org/copyleft/gpl.html
 */

// Dont allow direct linking
defined('_JEXEC') or die( 'Direct Access to this location is not allowed.' );

define( '_AEC_MI_NAME_NINJABOARD', 'Ninjaboard' );
define( '_AEC_MI_DESC_NINJABOARD', 'Assign Groups in Ninjaboard' );

class mi_ninjaboard
{
	function Info()
	{
		$info = array();
		$info['name'] = _AEC_MI_NAME_NINJABOARD;
		$info['desc'] = _AEC_MI_DESC_NINJABOARD;

		return $info;
	}

	function Settings()
	{
	 	$this->getLang();

	 	$groups = KFactory::tmp('admin::com.ninjaboard.model.usergroups')->getList();

		$sg		= array();
		$sg2	= array();
		if ( !empty( $groups ) ) {
			foreach ( $groups as $group ) {
				$sg[] = mosHTML::makeOption( $group->id, $group->title );
			}
		}

		// Explode the Groups to Exclude
		if ( !empty($this->settings['groups_exclude'] ) ) {
			$selected_groups_exclude = array();

			foreach ( $this->settings['groups_exclude'] as $group_exclude ) {
				$selected_groups_exclude[]->value = $group_exclude;
			}
		} else {
			$selected_groups_exclude			= '';
		}

		$settings = array();

		$s = array( 'group', 'remove_group', 'group_exp', 'remove_group_exp', 'groups_exclude' );

		foreach ( $s as $si ) {
			$v = null;
			if ( isset( $this->settings[$si] ) ) {
				$v = $this->settings[$si];
			}

			$settings['lists'][$si]	= mosHTML::selectList( $sg, $si.'[]', 'size="10" multiple="true"', 'value', 'text', $v );
		}

		$settings['set_group']				= array( 'list_yesno' );
		$settings['group']					= array( 'list' );
		$settings['set_remove_group']		= array( 'list_yesno' );
		$settings['remove_group']			= array( 'list' );
		$settings['set_group_exp']			= array( 'list_yesno' );
		$settings['group_exp']				= array( 'list' );
		$settings['set_remove_group_exp']	= array( 'list_yesno' );
		$settings['remove_group_exp']		= array( 'list' );
		$settings['set_groups_exclude']		= array( 'list_yesno' );
		$settings['groups_exclude']			= array( 'list' );
		$settings['set_clear_groups']		= array( 'list_yesno' );
		$settings['rebuild']				= array( 'list_yesno' );
		$settings['remove']					= array( 'list_yesno' );

		return $settings;
	}

	function action( $request )
	{
		$id = $request->metaUser->userid;

		$model	= KFactory::tmp('admin::com.ninjaboard.model.usergroup_maps');
		$table  = KFactory::get($model->getTable());
		$groups = $model->id($id)->getGroups();

		if ( $this->settings['set_remove_group'] ) {
			foreach ( $this->settings['remove_group'] as $groupid ) {
				if ( in_array( $groupid, $groups ) ) {
					$query = KFactory::tmp('lib.koowa.database.query');
					$table->delete(
						$query->where('joomla_user_id', '=', $id)->where('ninjaboard_user_group_id', '=', $groupid)
					);
				}
			}
		}

		if ( $this->settings['set_group'] ) {
			foreach ( $this->settings['group'] as $groupid ) {
				if ( !in_array( $groupid, $groups ) ) {
					$row = KFactory::tmp('admin::com.ninjaboard.model.usergroup_maps')->getItem()->setData(array(
						'id' => $id,
						'ninjaboard_user_group_id' => $groupid
					));
					$table->insert($row);
				}
			}
		}

		return true;
	}

	function expiration_action( $request )
	{
		$id = $request->metaUser->userid;

		$model	= KFactory::tmp('admin::com.ninjaboard.model.usergroup_maps');
		$table  = KFactory::get($model->getTable());
		$groups = $model->id($id)->getGroups();

		if ( $this->settings['set_clear_groups'] ) {
			if ( $this->settings['set_groups_exclude'] && !empty( $this->settings['groups_exclude'] ) ) {
				$groups = array_diff( $groups, $this->settings['groups_exclude'] );
			}

			$query = KFactory::tmp('lib.koowa.database.query');
			$table->delete(
				$query->where('joomla_user_id', '=', $id)->where('ninjaboard_user_group_id', 'IN', $groups)
			);
		} else {
			if ( $this->settings['set_remove_group_exp'] ) {
				foreach ( $this->settings['remove_group_exp'] as $groupid ) {
					if ( in_array( $groupid, $groups ) ) {
						$query = KFactory::tmp('lib.koowa.database.query');
						$table->delete(
							$query->where('joomla_user_id', '=', $id)->where('ninjaboard_user_group_id', '=', $groupid)
						);
					}
				}
			}

			if ( $this->settings['set_group_exp'] ) {
				foreach ( $this->settings['group_exp'] as $groupid ) {
					if ( !in_array( $groupid, $groups ) ) {
						$row = KFactory::tmp('admin::com.ninjaboard.model.usergroup_maps')->getItem()->setData(array(
							'id' => $id,
							'ninjaboard_user_group_id' => $groupid
						));
						$table->insert($row);
					}
				}
			}
		}

		return true;
	}

	function getLang()
	{
		foreach( array(
			'_MI_MI_NINJABOARD_SET_GROUP_NAME',
			'_MI_MI_NINJABOARD_SET_GROUP_DESC',
			'_MI_MI_NINJABOARD_GROUP_NAME',
			'_MI_MI_NINJABOARD_GROUP_DESC',
			'_MI_MI_NINJABOARD_SET_REMOVE_GROUP_NAME',
			'_MI_MI_NINJABOARD_SET_REMOVE_GROUP_DESC',
			'_MI_MI_NINJABOARD_REMOVE_GROUP_NAME',
			'_MI_MI_NINJABOARD_REMOVE_GROUP_DESC',
			'_MI_MI_NINJABOARD_SET_GROUP_EXP_NAME',
			'_MI_MI_NINJABOARD_SET_GROUP_EXP_DESC',
			'_MI_MI_NINJABOARD_GROUP_EXP_NAME',
			'_MI_MI_NINJABOARD_GROUP_EXP_DESC',
			'_MI_MI_NINJABOARD_SET_REMOVE_GROUP_EXP_NAME',
			'_MI_MI_NINJABOARD_SET_REMOVE_GROUP_EXP_DESC',
			'_MI_MI_NINJABOARD_REMOVE_GROUP_EXP_NAME',
			'_MI_MI_NINJABOARD_REMOVE_GROUP_EXP_DESC',
			'_MI_MI_NINJABOARD_SET_GROUPS_EXCLUDE_NAME',
			'_MI_MI_NINJABOARD_SET_GROUPS_EXCLUDE_DESC',
			'_MI_MI_NINJABOARD_GROUPS_EXCLUDE_NAME',
			'_MI_MI_NINJABOARD_GROUPS_EXCLUDE_DESC',
			'_MI_MI_NINJABOARD_SET_CLEAR_GROUPS_NAME',
			'_MI_MI_NINJABOARD_SET_CLEAR_GROUPS_DESC',
			'_MI_MI_NINJABOARD_REBUILD_NAME',
			'_MI_MI_NINJABOARD_REBUILD_DESC',
			'_MI_MI_NINJABOARD_REMOVE_NAME',
			'_MI_MI_NINJABOARD_REMOVE_DESC'
		) as $translate)
		{
			$text = str_replace( array( '_MI_MI_NINJABOARD_', '_NAME', '_DESC' ), '', $translate);

			define($translate, KInflector::humanize($text));
		}
	}
}