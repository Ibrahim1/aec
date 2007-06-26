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

class mi_mosets_tree {

	function Info () {
		$info = array();
		$info['name'] = "MosetsTree";
		$info['desc'] = "Restrict the amount of listings a user can publish";

		return $info;
	}

	function checkInstallation () {
		global $database, $mosConfig_dbprefix;

		$tables	= array();
		$tables	= $database->getTableList();

		return in_array($mosConfig_dbprefix."_acctexp_mi_mosetstree", $tables);
	}

	function install () {
		global $database;

		$query =	"CREATE TABLE IF NOT EXISTS `#__acctexp_mi_mosetstree` (" . "\n" .
					"`id` int(11) NOT NULL auto_increment," . "\n" .
					"`userid` int(11) NOT NULL," . "\n" .
					"`active` int(4) NOT NULL default '1'," . "\n" .
					"`granted_listings` int(11) NULL," . "\n" .
					"`used_listings` int(11) NULL," . "\n" .
					"`params` text NULL," . "\n" .
					"PRIMARY KEY  (`id`)" . "\n" .
					")";
		$database->setQuery( $query );
		$database->query();
		return;
	}

	function Settings ( $params ) {
		// field type; name; variable value, description, extra (variable name)

		$settings = array();
		$settings['add_listings'] = array("inputA", "ADD", "input the amount of listings you want added for this call");
		$settings['set_listings'] = array("inputA", "SET", "input the amount of listings you want as a overwriting set for this call");

		return $settings;
	}

	function expiration_action($params, $userid, $plan) {
		global $database;

		$mi_mosetshandler = new mosetstree($database);
		$id = $mi_mosetshandler->getIDbyUserID($userid);

		$mi_mosetshandler->load($id);
		$mi_mosetshandler->active = 0;

		$mi_mosetshandler->check();
		$mi_mosetshandler->store();

		return true;
	}

	function action($params, $userid, $plan) {
		global $database;

		$mi_mosetshandler = new mosetstree($database);
		$id = $mi_mosetshandler->getIDbyUserID($userid);
		$mi_id = $id ? $id : 0;
		$mi_mosetshandler->load($mi_id);

		if (!$mi_id) {
			$mi_mosetshandler->userid = $userid;
			$mi_mosetshandler->active = 1;
		}

		if ($params['set_listings']) {
			$mi_mosetshandler->setListings($params['set_listings']);
		} elseif ($params['add_listings']) {
			$mi_mosetshandler->addListings($params['add_listings']);
		}

		$mi_mosetshandler->check();
		$mi_mosetshandler->store();

		return true;
	}

	function hacks () {
		global $mosConfig_absolute_path;

		$hacks = array();

		$edithack = 'if (!$link_id) {' . "\n" .
					'include_once $mosConfig_absolute_path . "/components/com_acctexp/micro_integration/mi_mosets_tree.php";' . "\n".
					'$mi_mosetshandler = new mosetstree($database);' . "\n" .
					'$mi_mosetshandler->loadUserID($my->id);' . "\n" .
					'if ($mi_mosetshandler->id) {' . "\n" .
					'	if (!$mi_mosetshandler->hasListingsLeft()) {' . "\n" .
					'		echo "No Listings left";' . "\n" .
					'		return;' . "\n" .
					'	}' . "\n" .
					'	echo "Registration Required";' . "\n" .
					'	return;' . "\n" .
					'}' . "\n" .
					'}' . "\n";

		$n = "mtree1";
		$hacks[$n]['name']				=	"mtree.php #1";
		$hacks[$n]['desc']				=	"Prevent user from creating a new listing if he or she has run out of granted listings";
		$hacks[$n]['type']				=	'file';
		$hacks[$n]['filename']			=	$mosConfig_absolute_path . "/components/com_mtree/mtree.php";
		$hacks[$n]['read']				=	'# OK, you can edit';
		$hacks[$n]['insert']			=	$edithack . "\n"  . $hacks[$n]['read'];

		$n = "mtree2";
		$hacks[$n]['name']				=	"mtree.php #2";
		$hacks[$n]['desc']				=	"Prevent user from saving a new listing if he or she has run out of granted listings";
		$hacks[$n]['type']				=	'file';
		$hacks[$n]['filename']			=	$mosConfig_absolute_path . "/components/com_mtree/mtree.php";
		$hacks[$n]['read']				=	'$row->link_created = date( "Y-m-d H:i:s" );';
		$hacks[$n]['insert']			=	$edithack . "\n"  . $hacks[$n]['read'];

		return $hacks;
	}


	function profile_info($params, $userid) {
		global $database;

		$mi_mosetshandler = new mosetstree($database);
		$id = $mi_mosetshandler->getIDbyUserID($userid);

		if ($id) {
			$mi_mosetshandler->load($id);
			return "<p>You have <strong>" . $mi_mosetshandler->getListingsLeft . "</strong> Listings left in our directory.</p>";
		} else {
			return false;
		}
	}

}

class mosetstree extends mosDBTable {
	/** @var int Primary key */
	var $id					= null;
	/** @var int */
	var $userid 			= null;
	/** @var int */
	var $active				= null;
	/** @var int */
	var $granted_listings	= null;
	/** @var text */
	var $used_listings		= null;
	/** @var text */
	var $params				= null;

	function mosetstree (&$db) {
		$this->mosDBTable( '#__acctexp_mi_mosetstree', 'id', $db );
	}

	function getIDbyUserID ($userid) {
		global $database;

		$database->setQuery( "SELECT id FROM #__acctexp_mi_mosetstree WHERE userid='" . $userid . "'" );
		return $database->loadResult();
	}

	function loadUserID ($userid) {
		$id = $this->getIDbyUserID($userid);
		$this->load($id);
	}

	function is_active () {
		if ($this->active) {
			return true;
		} else {
			return false;
		}
	}

	function getListingsLeft () {
		$listings_left = $this->granted_listings - $this->used_listings;
		return $listings_left;
	}

	function hasListingsLeft () {
		if ($this->getListingsLeft() > 0) {
			return true;
		} else {
			return false;
		}
	}

	function useListing () {
		if ($this->hasListingsLeft() && $this->is_active()) {
			$this->used_listings++;
			$this->check();
			$this->store();
			return true;
		} else {
			return false;
		}
	}

	function setListings ($set) {
		$this->granted_listings = $set;
	}

	function addListings ($add) {
		$this->granted_listings += $add;
	}
}

?>