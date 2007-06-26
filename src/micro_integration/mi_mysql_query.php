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

class mi_mysql_query {

	function Info () {
		$info = array();
		$info['name'] = "mySQL Query";
		$info['desc'] = "Specify a mySQL query that should be carried out with this subscription or on its expiration";

		return $info;
	}

	function Settings ( $params ) {
        $settings = array();
        $settings['query'] = array("inputD", "Query", "MySQL query to be carried out when this MI is called.");
        $settings['query_exp'] = array("inputD", "Expiration Query", "MySQL query to be carried out when this MI is called on expiration.");
        $settings['query_pre_exp'] = array("inputD", "Pre Expiration Query", "MySQL query to be carried out when this MI is called before expiration (specify when on the first tab).");
		$rewriteswitches = array("cms", "user", "expiration", "subscription", "plan");
		$settings['rewriteInfo'] = array("fieldset", "Rewriting Info", AECToolbox::rewriteEngineInfo($rewriteswitches));

		return $settings;
	}

	function pre_expiration_action($params, $userid, $plan, $mi_id) {
		global $database;

		$metaUser = new metaUser ($userid);

		$userflags = $metaUser->objSubscription->getMIflags($plan->id, $mi_id);

		if (is_array($userflags)) {
			if (isset($userflags['DB_QUERY'])) {
				if (!(time() > $userflags['DB_QUERY_ABANDONCHECK'])) {
					return false;
				}
			}
		}

		$newflags['db_query_abandoncheck'] = strtotime($metaUser->objExpiration->expiration);
		$newflags['db_query'] = time();
		$metaUser->objSubscription->setMIflags($plan->id, $mi_id, $newflags);

		$query = AECToolbox::rewriteEngine($params['query_pre_exp'], $metaUser, $plan);

		$database->setQuery($query);
		$database->query();

		return true;
	}

	function expiration_action($params, $userid, $plan) {
		global $database;

		$metaUser = new metaUser ($userid);

		$query = AECToolbox::rewriteEngine($params['query_exp'], $metaUser, $plan);

		$database->setQuery($query);
		$database->query();

		return true;
	}

	function action($params, $userid, $plan) {
		global $database;

		$metaUser = new metaUser ($userid);

		$query = AECToolbox::rewriteEngine($params['query'], $metaUser, $plan);

		$database->setQuery($query);
		$database->query();

		return true;
	}
}
?>