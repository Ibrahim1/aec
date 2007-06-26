<?php
// Copyright (C) 2006-2007 David Deutsch
// All rights reserved.
// This source file is part of the Account Expiration Control Component, a  Joomla
// custom Component By Helder Garcia and David Deutsch - http://www.globalnerd.org
//
// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License (GPL)
// as published by the Free Software Foundation; either version 2
// of the License, or (at your option) any later version.
//
// Please note that the GPL states that any headers in files and
// Copyright notices as well as credits in headers, source files
// and output (screens, prints, etc.) can not be removed.
// You can extend them with your own credits, though...
//
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with this program; if not, write to the Free Software
// Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
//
// The "GNU General Public License" (GPL) is available at
// http://www.gnu.org/copyleft/gpl.html.

// Dont allow direct linking
defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );

class mi_remository {

	function Info () {
		$info = array();
		$info['name'] = "reMOSitory";
		$info['desc'] = "Choose the amount of files a user can download and what reMOSitory group should be assigned to the user account";

		return $info;
	}

	function checkInstallation () {
		global $database, $mosConfig_dbprefix;

		$tables	= array();
		$tables	= $database->getTableList();

		return in_array($mosConfig_dbprefix."__acctexp_mi_remository", $tables);
	}

	function install () {
		global $database;

		$query =	"CREATE TABLE IF NOT EXISTS `#__acctexp_mi_remository` (" . "\n" .
					"`id` int(11) NOT NULL auto_increment," . "\n" .
					"`userid` int(11) NOT NULL," . "\n" .
					"`active` int(4) NOT NULL default '1'," . "\n" .
					"`granted_downloads` int(11) NULL," . "\n" .
					"`used_downloads` int(11) NULL," . "\n" .
					"`params` text NULL," . "\n" .
					"PRIMARY KEY  (`id`)" . "\n" .
					")";
		$database->setQuery( $query );
		$database->query();
		return;
	}

	function Settings ( $params ) {
		global $database;

	 	$database->setQuery( "SELECT group_id, group_name, group_description"
	 	. "\nFROM #__mbt_group");
	 	$groups = $database->loadObjectList();

		$sg = array();
		foreach ($groups as $group) {
			$sg[] = mosHTML::makeOption( $group->group_id, $group->group_name . " - " . substr(strip_tags($group->group_name), 0, 30) );
		}

        $settings = array();
		$settings['add_downloads'] = array("inputA", "ADD", "input the amount of listings you want added for this call");
		$settings['set_downloads'] = array("inputA", "SET", "input the amount of listings you want as a overwriting set for this call");

		$settings['lists']['group'] = mosHTML::selectList($sg, 'group', 'size="4"', 'value', 'text', $params['group']);
		$settings['lists']['group_exp'] = mosHTML::selectList($sg, 'group_exp', 'size="4"', 'value', 'text', $params['group_exp']);

		$settings['set_group'] = array("list_yesno", "Set group", "Choose Yes if you want this MI to set the Shopper Group when it is called.");
		$settings['group'] = array("list", "Group", "The ReMOSitory group that you want the user to be in.");
		$settings['set_group_exp'] = array("list_yesno", "Set group Expiration", "Choose Yes if you want this MI to set the ReMOSitory Group when the calling payment plan expires.");
		$settings['group_exp'] = array("list", "Expiration group", "The ReMOSitory group that you want the user to be in when the subscription runs out.");

		return $settings;
	}

	function detect_application () {
		global $mosConfig_absolute_path;

		return is_dir($mosConfig_absolute_path . "components/com_remository");
	}

	function hacks () {
		global $mosConfig_absolute_path;

		$hacks = array();

		$downloadhack =		'global $my, $mosConfig_absolute_path;' . "\n"
							.'include $mosConfig_absolute_path . "/components/com_acctexp/micro_integration/mi_remository.php";' . "\n\n"
							.'$restrictionhandler = new remository_restriction($database);' . "\n"
							.'$restrict_id = $restrictionhandler->getIDbyUserID($my->id);' . "\n"
							.'$restrictionhandler->load($restrict_id);' . "\n\n"
							.'if (!$restrictionhandler->hasDownloadsLeft()) {' . "\n"
							.'	mosRedirect("index.php?option=com_remository",_NO_CREDITS);' . "\n"
							."} else {" . "\n"
							.'	$restrictionhandler->useDownload();' . "\n"
							."}" . "\n";

		$n = "remositorystartdown";
		$hacks[$n]['name']				=	"com_remository_startdown.php";
		$hacks[$n]['desc']				=	"Build in a downloads restriction for reMOSitory, to be used with Micro Integrations.";
		$hacks[$n]['type']				=	'file';
		$hacks[$n]['filename']			=	$mosConfig_absolute_path . "/components/com_remository/com_remository_startdown.php";
		$hacks[$n]['read']				=	"//Update download count";
		$hacks[$n]['insert']			=	$downloadhack . "\n"  . $hacks[$n]['read'];

		return $hacks;
	}

	function expiration_action($params, $userid, $plan) {
		global $database;

		if ($params['set_group_exp']) {
			// Check if exists
			$query = "SELECT group_id #__mbt_group_member WHERE member_id='" . $userid . "'";
			$database->setQuery($query);
	
			// If already an entry exists -> update, if not -> create
			if ($database->loadResult()) {
				$query = "UPDATE #__mbt_group_member SET group_id = '" . $params['group_exp'] . "'  WHERE member_id='" . $userid . "'";
			} else {
				$query = "INSERT INTO `#__mbt_group_member` ( `group_id` , `member_id` ) VALUES ('" . $params['group_exp'] . "', '" . $userid . "')";
			}

			// Carry out query
			$database->setQuery($query);
			$database->query();
		}

		$mi_remositoryhandler = new remository_restriction($database);
		$id = $mi_remositoryhandler->getIDbyUserID($userid);
		$mi_id = $id ? $id : 0;
		$mi_remositoryhandler->load($mi_id);

		if ($mi_id) {
			$mi_remositoryhandler->active = 0;
			$mi_remositoryhandler->check();
			$mi_remositoryhandler->store();
		}

		return true;
	}

	function action($params, $userid, $plan) {
		global $database;

		if ($params['set_group']) {
			// Check if exists
			$query = "SELECT group_id #__mbt_group_member WHERE member_id='" . $userid . "'";
			$database->setQuery($query);
	
			// If already an entry exists -> update, if not -> create
			if ($database->loadResult()) {
				$query = "UPDATE #__mbt_group_member SET group_id = '" . $params['group'] . "'  WHERE member_id='" . $userid . "'";
			} else {
				$query = "INSERT INTO `#__mbt_group_member` ( `group_id` , `member_id` ) VALUES ('" . $params['group'] . "', '" . $userid . "')";
			}

			// Carry out query
			$database->setQuery($query);
			$database->query();
		}

		$mi_remositoryhandler = new remository_restriction($database);
		$id = $mi_remositoryhandler->getIDbyUserID($userid);
		$mi_id = $id ? $id : 0;
		$mi_remositoryhandler->load($mi_id);

		if (!$mi_id) {
			$mi_remositoryhandler->userid = $userid;
			$mi_remositoryhandler->active = 1;
		}

		if ($params['set_downloads']) {
			$mi_remositoryhandler->setDownloads($params['set_downloads']);
		} elseif ($params['add_downloads']) {
			$mi_remositoryhandler->addDownloads($params['add_downloads']);
		}

		$mi_remositoryhandler->check();
		$mi_remositoryhandler->store();

		return true;
	}

}

class remository_restriction extends mosDBTable {
	/** @var int Primary key */
	var $id					= null;
	/** @var int */
	var $userid 			= null;
	/** @var int */
	var $active				= null;
	/** @var int */
	var $granted_downloads	= null;
	/** @var text */
	var $used_downloads		= null;
	/** @var text */
	var $params				= null;

	function getIDbyUserID ($userid) {
		global $database;

		$database->setQuery( "SELECT id FROM #__acctexp_mi_remository WHERE userid='" . $userid . "'" );
		return $database->loadResult();
	}

	function remository_restriction (&$db) {
		$this->mosDBTable( '#__acctexp_mi_remository', 'id', $db );
	}

	function is_active () {
		if ($this->active) {
			return true;
		} else {
			return false;
		}
	}

	function getDownloadsLeft () {
		$downloads_left = $this->granted_downloads - $this->used_downloads;
		return $downloads_left;
	}

	function hasDownloadsLeft () {
		if ($this->getDownloadsLeft() > 0) {
			return true;
		} else {
			return false;
		}
	}

	function useDownload () {
		if ($this->hasDownloadsLeft() && $this->is_active()) {
			$this->used_downloads++;
			$this->check();
			$this->store();
			return true;
		} else {
			return false;
		}
	}

	function setDownloads ($set) {
		$this->granted_downloads = $set;
	}

	function addDownloads ($add) {
		$this->granted_downloads += $add;
	}
}

?>