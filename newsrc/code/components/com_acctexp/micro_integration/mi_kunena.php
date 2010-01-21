<?php
/*
 * <name>mi_kunena</name>
 * <creationDate>January 2010</creationDate>
 * <version>1.5.0</version>
 * <author>Joel Bassett</author>
 * <authorEmail>joel@aqsg.com.au</authorEmail>
 * <authorUrl>http://www.aqsg.com.au</authorUrl>
 * <copyright>(C) 2005-2010 Australian Quality Solutions Group. All rights reserved.</copyright>
 * <license>AGPLv3 - http://www.aqsg.com.au/products/simple-meta-management-suite/licence.html</license>
 */
// Dont allow direct linking
defined('_JEXEC') OR defined( '_VALID_MOS' ) OR die( 'Direct Access to this location is not allowed.' );

class mi_kunena
{
	function Info()
	{
		$info = array();
		$info['name'] = 'Kunena Forum';
		$info['desc'] = 'Change the Users Rank in Kunena';

		return $info;
	}

	function Settings()
	{
		$settings = array();

		if(!include_once(rtrim(JPATH_ROOT, DS) . DS . 'components' . DS . 'com_kunena' . DS . 'lib' . DS . 'kunena.user.class.php')){
			echo 'This module can not work without the Kunena Forum Component';
			return $settings;
		};

		$database = &JFactory::getDBO();
		$database->setQuery('SELECT * FROM ' . $database->nameQuote('#__fb_ranks'));
		$ranks = $database->loadObjectList();

		$listsValues[] = JHTML::_('select.option', 0, '--- Not Set ---' );

		foreach ($ranks as $id=>$row) {
			$listsValues[] = JHTML::_('select.option', $row->rank_id, $row->rank_id . ' : ' . $row->rank_title );
		}

		$settings['sublists'] = array( 'list','[Activation] Change the user\'s rank to the selected rank','Select the rank you want to apply to the user on plan activation' );
		$settings['unsublists'] = array( 'list','[Activation] Remove the user\'s from the following rank','Select the rank you want to remove the user from on plan activation' );
		$settings['sublistsexpir'] = array( 'list','[Expiration] Change the user\'s rank to the selected rank','Select the rank you want to apply to the user on plan expiration' );
		$settings['unsublistsexpir'] = array( 'list','[Expiration] Remove the user\'s from the following rank','Select the rank you want to remove the user from on plan expiration' );

		$settings['lists']['sublists']		= JHTML::_('select.genericlist',   $listsValues, 'sublists[]', 'class="inputbox"', 'value', 'text', empty($this->settings['sublists']) ? '' : $this->settings['sublists']);
		$settings['lists']['unsublists']	= JHTML::_('select.genericlist',   $listsValues, 'unsublists[]', 'class="inputbox"', 'value', 'text', empty($this->settings['unsublists']) ? '' : $this->settings['unsublists']);
		$settings['lists']['sublistsexpir']		= JHTML::_('select.genericlist',   $listsValues, 'sublistsexpir[]', 'class="inputbox"', 'value', 'text', empty($this->settings['sublistsexpir']) ? '' : $this->settings['sublistsexpir']);
		$settings['lists']['unsublistsexpir']	= JHTML::_('select.genericlist',   $listsValues, 'unsublistsexpir[]', 'class="inputbox"', 'value', 'text', empty($this->settings['unsublistsexpir']) ? '' : $this->settings['unsublistsexpir']);

		return $settings;
	}

	function expiration_action( $request )
	{
		$this->changeRank($request->metaUser->userid, $this->settings['sublistsexpir'], $this->settings['unsublistsexpir']);
	}

	function action( $request )
	{
		$this->changeRank($request->metaUser->userid, $this->settings['sublists'], $this->settings['unsublists']);
	}

	function changeRank($userid, $subscribe_array, $remove_array)
	{
		$database = &JFactory::getDBO();

		if(empty($subscribe_array) AND empty($remove_array)) return;

		$remove = $remove_array[0];
		$subscribe = $subscribe_array[0];

		$database->setQuery('SELECT COUNT(*) FROM ' . $database->nameQuote('#__fb_users') . ' WHERE userid=' . $database->Quote($userid));
		$count = $database->loadResult();
		if ($count < 1) {
			$query = 'INSERT INTO ' . $database->nameQuote('#__fb_users') . ' (' . $database->nameQuote('userid') . ', ' . $database->nameQuote('view') . ', ' . $database->nameQuote('moderator') . ', ' . $database->nameQuote('ordering') . ', ' . $database->nameQuote('posts') . ', ' . $database->nameQuote('karma') . ', ' . $database->nameQuote('karma_time') . ', ' . $database->nameQuote('group_id') . ', ' . $database->nameQuote('uhits') . ', ' . $database->nameQuote('gender') . ', ' . $database->nameQuote('birthdate') . ', ' . $database->nameQuote('rank') . ', ' . $database->nameQuote('hideEmail') . ', ' . $database->nameQuote('showOnline') . ') VALUES ';
			if($remove) {
				$query .= "(" . $database->Quote($userid) . ", " . $database->Quote('flat') . ", " . $database->Quote('0') . ", " . $database->Quote('0') . ", " . $database->Quote('0') . ", " . $database->Quote('0') . ", " . $database->Quote('0') . ", " . $database->Quote('1') . ", " . $database->Quote('0') . ", " . $database->Quote('0') . ", " . $database->Quote('0001-01-01') . ", " . $database->Quote('0') . ", " . $database->Quote('1') . ", " . $database->Quote('1') . ")";
				$database->setQuery($query);
				$database->query();
			} elseif($subscribe) {
				$query .= "(" . $database->Quote($userid) . ", " . $database->Quote('flat') . ", " . $database->Quote('0') . ", " . $database->Quote('0') . ", " . $database->Quote('0') . ", " . $database->Quote('0') . ", " . $database->Quote('0') . ", " . $database->Quote('1') . ", " . $database->Quote('0') . ", " . $database->Quote('0') . ", " . $database->Quote('0001-01-01') . ", " . $database->Quote($subscribe) . ", " . $database->Quote('1') . ", " . $database->Quote('1') . ")";
				$database->setQuery($query);
				$database->query();
			}
		} else {
			if($remove) {
				$database->setQuery('UPDATE ' . $database->nameQuote('#__fb_users') . ' SET rank=' . $database->Quote('0') . ' WHERE userid=' . $database->Quote($userid));
				$database->query();
			} elseif($subscribe) {
				$database->setQuery('UPDATE ' . $database->nameQuote('#__fb_users') . ' SET rank=' . $subscribe . ' WHERE userid=' . $userid);
				$database->query();
			}
		}
	}

}