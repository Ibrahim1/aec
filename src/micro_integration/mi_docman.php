<?php
// Copyright (C) 2006-2007 David Deutsch
//
// This Micro Integration was written by Calum Polwart (Shiny Black Shoe Systems) - based directly on 
// David Deutsch's reMOSitory MI.
//
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

class mi_docman {

	function Info () {
		$info = array();
		$info['name'] = "DocMan";
		$info['desc'] = "Choose the amount of files a user can download and what DocMan group should be assigned to the user account";

		return $info;
	}

	function checkInstallation () {
		global $database, $mosConfig_dbprefix;

		$tables	= array();
		$tables	= $database->getTableList();

		return in_array($mosConfig_dbprefix."__acctexp_mi_docman", $tables);
	}

	function install () {
		global $database;

		$query =	"CREATE TABLE IF NOT EXISTS `#__acctexp_mi_docman` (" . "\n" .
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

	 	$database->setQuery( "SELECT groups_id, groups_name, groups_description"
	 	. "\nFROM #__docman_groups");
	 	$groups = $database->loadObjectList();

		$sg = array();
		foreach ($groups as $group) {
			$sg[] = mosHTML::makeOption( $group->groups_id, $group->groups_name . " - " . substr(strip_tags($group->groups_description), 0, 30) );
		}

        $settings = array();
		$settings['add_downloads'] = array("inputA", "ADD", "input the amount of listings you want added for this call");
		$settings['set_downloads'] = array("inputA", "SET", "input the amount of listings you want as a overwriting set for this call");

		$settings['lists']['group'] = mosHTML::selectList($sg, 'group', 'size="4"', 'value', 'text', $params['group']);
		$settings['lists']['group_exp'] = mosHTML::selectList($sg, 'group_exp', 'size="4"', 'value', 'text', $params['group_exp']);

		$settings['set_group'] = array("list_yesno", "Set group", "Choose Yes if you want this MI to set the DocMan Group when it is called.");
		$settings['group'] = array("list", "Group", "The DocMan group that you want the user to be in.");
		$settings['set_group_exp'] = array("list_yesno", "Set group Expiration", "Choose Yes if you want this MI to set the DocMan Group when the calling payment plan expires.");
		$settings['group_exp'] = array("list", "Expiration group", "The DocMan group that you want the user to be in when the subscription runs out.");

		return $settings;
	}

	function detect_application () {
		global $mosConfig_absolute_path;

		return is_dir($mosConfig_absolute_path . "components/com_docman");
	}

	function hacks () {
		global $mosConfig_absolute_path;

		$hacks = array();

		$downloadhack =		'global $my, $mosConfig_absolute_path;' . "\n"
							.'include $mosConfig_absolute_path . "/components/com_acctexp/micro_integration/mi_docman.php";' . "\n\n"
							.'$restrictionhandler = new docman_restriction($database);' . "\n"
							.'$restrict_id = $restrictionhandler->getIDbyUserID($my->id);' . "\n"
							.'$restrictionhandler->load($restrict_id);' . "\n\n"
							.'if (!$restrictionhandler->hasDownloadsLeft()) {' . "\n"
							.'	mosRedirect("index.php?option=com_docman",_NO_CREDITS);' . "\n"
							."} else {" . "\n"
							.'	$restrictionhandler->useDownload();' . "\n"
							."}" . "\n";

		$n = "docmanstartdown";
		$hacks[$n]['name']				=	"com_docman_startdown.php";
		$hacks[$n]['desc']				=	"Build in a downloads restriction for DocMan, to be used with Micro Integrations.";
		$hacks[$n]['type']				=	'file';
		$hacks[$n]['filename']			=	$mosConfig_absolute_path . "/components/com_docman/com_docman_startdown.php";
		$hacks[$n]['read']				=	"//Update download count";
		$hacks[$n]['insert']			=	$downloadhack . "\n"  . $hacks[$n]['read'];

		return $hacks;
	}

	function expiration_action($params, $userid, $plan) {
		global $database;
#####
# THIS BIT IS DIFFERENT FROM remository.  Everything else is just changes to references...
# reMOSitroy holds group membership in a table with one group = one user (possibly user can't be in two groups??)
# DocMan holds group member id's in its group definition table with userid's listed in a single field holding multivalues
# seperated by comas, user can be in multiple groups...
#####
		if ($params['set_group_exp']) {
			// There MUST be a better way to do this (or perhaps DocMan is the issue ;-) )
			// Read the group membership list from the table for the 'live' group
			# $query = "SELECT group_id #__mbt_group_member WHERE member_id='" . $userid . "'";
			$query = "SELECT groups_members FROM #__docman_groups WHERE groups_id ='".$params['group']."'";
			$database->setQuery($query);
			// if in live group - remove
			// parse the mutli-entry list into an array
			$rows = $database->loadResult();
			$livegroup = explode(",",$rows);
			// search and retrun the array id which equals $userid
			$entry = array_search($userid, $livegroup);
			if ($entry) {

			// delete the entry which equals userid
			unset ($livegroup[$entry]);
			// now rebuild the array into a multivalue field
			$newvalue = implode(",",$livegroup);
			// now UPDATE the database...
			$query = "UPDATE #__docman_groups SET groups_members = '".$newvalue."' WHERE groups_id='".$params['group']."'";
			$database->setQuery($query);
			$database->query();
			} else {
			// Do nothing
			}

			//check if already in expired group (DocMan allows multiple group entries.
			// There MUST be a better way to do this (or perhaps DocMan is the issue ;-) )
			// Read the group membership list from the table for the 'expired' group
			$query = "SELECT groups_members FROM #__docman_groups WHERE groups_id ='".$params['group_exp']."'";
			$database->setQuery($query);

			// if in expired group - leave
			// parse the mutli-entry list into an array
			$rows = $database->loadResult();
			$expgroup = explode(",",$rows);
			// search and retrun the array id which equals $userid
			$entry = array_search($userid, $expgroup);
			if ($entry) {
			// Do nothing - user is already in expired group
			} else {
			// Add to expired group
			array_push($expgroup,$userid);		
			// now rebuild the array into a multivalue field
			$newvalue = implode(",",$expgroup);
			// now UPDATE the database...
			$query = "UPDATE #__docman_groups SET groups_members = '".$newvalue."' WHERE groups_id='".$params['group_exp']."'";
			$database->setQuery($query);
			$database->query();}
####
#END OF DocMan changes - more further down
####

		}

		$mi_docmanhandler = new docman_restriction($database);
		$id = $mi_docmanhandler->getIDbyUserID($userid);
		$mi_id = $id ? $id : 0;
		$mi_docmanhandler->load($mi_id);

		if ($mi_id) {
			$mi_docmanhandler->active = 0;
			$mi_docmanhandler->check();
			$mi_docmanhandler->store();
		}

		return true;
	}

	function action($params, $userid, $plan) {
		global $database;

		if ($params['set_group']) {
########
# BIG CHANGES HERE FOR DocMan too...
########

			// There MUST be a better way to do this (or perhaps DocMan is the issue ;-) )
			// Read the group membership list from the table for the 'live' group
			$query = "SELECT groups_members FROM #__docman_groups WHERE groups_id ='".$params['group']."'";
			$database->setQuery($query);
			// if NOT in live group - ADD
			// parse the mutli-entry list into an array
			$rows = $database->loadResult();
			//$livegroup = array();
			$livegroup = explode(",",$rows);
			}
			// search and return the array id which equals $userid
			$entry = array_search($userid, $livegroup);
			if ($entry) {
			// Do nothing - already in the group
			} else { 
			array_push($livegroup,$userid);
			// now rebuild the array into a multivalue field
			$newvalue = implode(",",$livegroup);
			// now UPDATE the database...
			$query = "UPDATE #__docman_groups SET groups_members = '".$newvalue."' WHERE groups_id='".$params['group']."'";
			$database->setQuery($query);
			$database->query(); 

		}
## END OF THOSE CHANGES
		$mi_docmanhandler = new docman_restriction($database);
		$id = $mi_docmanhandler->getIDbyUserID($userid);
		$mi_id = $id ? $id : 0;
		$mi_docmanhandler->load($mi_id);

		if (!$mi_id) {
			$mi_docmanhandler->userid = $userid;
			$mi_docmanhandler->active = 1;
		}

		if ($params['set_downloads']) {
			$mi_docmanhandler->setDownloads($params['set_downloads']);
		} elseif ($params['add_downloads']) {
			$mi_docmanhandler->addDownloads($params['add_downloads']);
		}

		$mi_docmanhandler->check();
		$mi_docmanhandler->store();

		return true;
	}

}

class docman_restriction extends mosDBTable {
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

		$database->setQuery( "SELECT id FROM #__acctexp_mi_docman WHERE userid='" . $userid . "'" );
		return $database->loadResult();
	}

	function docman_restriction (&$db) {
		$this->mosDBTable( '#__acctexp_mi_docman', 'id', $db );
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

