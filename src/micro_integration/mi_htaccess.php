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

class mi_htaccess {

	function Info () {
		$info = array();
		$info['name'] = "htaccess";
		$info['desc'] = "Protect a folder with a .htaccess file and only allow users of this subscription to access it with their joomla account details.";

		return $info;
	}

	function mi_htaccess () {
		global $mosConfig_absolute_path;
		include_once $mosConfig_absolute_path . "/components/com_acctexp/micro_integration/mi_htaccess/htaccess.class.php";
	}

	function checkInstallation () {
		global $database, $mosConfig_dbprefix;

		$tables	= array();
		$tables	= $database->getTableList();

		return in_array($mosConfig_dbprefix."_acctexp_mi_htaccess_apachepw", $tables);
	}

	function install () {
		global $database;

		$query =	"CREATE TABLE IF NOT EXISTS `#__acctexp_mi_htaccess_apachepw` (" . "\n" .
					"`id` int(11) NOT NULL auto_increment," . "\n" .
					"`userid` int(11) NOT NULL," . "\n" .
					"`apachepw` varchar(255) NOT NULL default '1'," . "\n" .
					"PRIMARY KEY  (`id`)" . "\n" .
					")";
		$database->setQuery( $query );
		$database->query();
		return;
	}

	function Settings ( $params ) {
		global $mosConfig_absolute_path;

		$settings = array();
		// field type; name; variable value, description, extra (variable name)
		$settings['mi_folder'] = array("inputC", "Folder", "Your protected folder name. Following keywords will be replaced:<br />[cmsroot] ->  " . $mosConfig_absolute_path . "<br />Remember that this has no trailing slash! Your foldername should have none as well!");
		$settings['mi_passwordfolder'] = array("inputC", "Password Folder", "A place where you want to store the password file. This should be a directory which is not web-accessible. Use [abovecmsroot] to put in the directory above the cms root directory - this is recommended.");
		$settings['mi_name'] = array("inputC", "Area Name", "The name of the protected Area");
		$settings['use_md5'] = array("list_yesno", "use md5", "<strong>Important!</strong> If you want to use this MI to restrict folders with apache, you have to use crypt - so just leave this at NO. If you want to use a different software which uses htaccess/htuser files (like an icecast server for instance), set this to yes and it will use standard md5 encryption.");
		$settings['rebuild'] = array("list_yesno", "rebuild htaccess", "If you changed something important or lost your htaccess file, this option will rebuild your whole htaccess files by looking for each plan that has this MI applied and then add each user that uses one of these plans to the file.");

		return $settings;
	}

	function saveparams ( $params ) {
		global $mosConfig_absolute_path, $database;

		$mosConfig_absolute_path_above = substr( $mosConfig_absolute_path, 0, strrpos($mosConfig_absolute_path, "/") );

		$newparams = $params;
		// Rewrite foldername to include cmsroot directory
		if (strpos("[cmsroot]", $params['mi_folder'])) {
			$newparams['mi_folder'] = str_replace("[cmsroot]", $mosConfig_absolute_path, $params['mi_folder']);
		}
		if (strpos("[abovecmsroot]", $params['mi_passwordfolder'])) {
			$newparams['mi_passwordfolder'] = str_replace("[abovecmsroot]", $mosConfig_absolute_path_above, $params['mi_passwordfolder']);
		}

		$newparams['mi_folder_fullpath'] = $newparams['mi_folder'] . "/.htaccess";
		
		$newparams['mi_folder_user_fullpath'] = $newparams['mi_passwordfolder'] .  "/.htuser" . str_replace("/", "_", str_replace(".", "/", $newparams['mi_folder']));

		if (!file_exists($newparams['mi_folder_fullpath']) && !$params['rebuild']) {
			$ht = new htaccess();
			$ht->setFPasswd( $newparams['mi_folder_user_fullpath']);
			$ht->setFHtaccess( $newparams['mi_folder_fullpath']);
			if (isset($newparams['mi_name'])) {
				$ht->setAuthName($newparams['mi_name']);
			}
			$ht->addLogin();
		}

		if ($params['rebuild']) {
			$ht = new htaccess();
			$ht->setFPasswd( $newparams['mi_folder_user_fullpath']);
			$ht->setFHtaccess( $newparams['mi_folder_fullpath']);
			if (isset($newparams['mi_name'])) {
				$ht->setAuthName($newparams['mi_name']);
			}

			$planlist = MicroIntegrationHandler::getPlansbyMI($params['MI_ID']);

			foreach ($planlist as $planid) {
				$userlist = SubscriptionPlanHandler::getPlanUserlist($planid);
				foreach ($userlist as $userid) {
					if ($params['use_md5']) {
						$ht->addUser($row->username, $row->password);
					} else {
						$apachepw = new apachepw ($database);
						$apwid = $apachepw->getIDbyUserID($userid);
				
						if ($apwid) {
							$apachepw->load($apwid);
							$ht->addUser($row->username, $apachepw->apachepw);
						}
					}
				}
			}

			$ht->addLogin();
			$newparams['rebuild'] = 0;
		}

		return $newparams;
	}

	function expiration_action($params, $userid, $plan) {
		global $database;

		$user = new mosUser($database);
		$user->load($userid);

		$ht = new htaccess();
		$ht->setFPasswd( $params['mi_folder_user_fullpath']);
		$ht->setFHtaccess( $params['mi_folder_fullpath']);
		if (isset($params['mi_name'])) {
			$ht->setAuthName($params['mi_name']);
		}
		$ht->delUser($user->username);
	}

	function action($params, $userid, $plan) {
		global $database;

		$user = new mosUser($database);
		$user->load($userid);

		$ht = new htaccess();
		$ht->setFPasswd( $params['mi_folder_user_fullpath']);
		$ht->setFHtaccess( $params['mi_folder_fullpath']);
		if (isset($params['mi_name'])) {
			$ht->setAuthName($params['mi_name']);
		}

		if ($params['use_md5']) {
			$ht->addUser($row->username, $row->password);
		} else {
			$apachepw = new apachepw ($database);
			$apwid = $apachepw->getIDbyUserID($userid);
	
			if ($apwid) {
				$apachepw->load($apwid);
			} else {
				// notify User? Admin?
				return false;
			}

			$ht->addUser($row->username, $apachepw->apachepw);
		}
		$ht->addLogin();
		return true;
	}

	function on_userchange_action($params, $row, $post, $trace) {
		global $database;

		$apachepw = new apachepw ($database);
		$apwid = $apachepw->getIDbyUserID($row->id);

		if ($apwid) {
			$apachepw->load($apwid);
		} else {
			$apachepw->load(0);
			$apachepw->userid = $row->id;
		}

		if (isset($post['password']) && $post['password'] != '') {
			$apachepw->apachepw = crypt($post['password']);
			$apachepw->check();
			$apachepw->store();
		} elseif (!$apwid) {
			// No new password and no existing password - nothing to be done here
			return;
		}

		if (!(strcmp($trace, "registration") === 0)) {
			$ht = new htaccess();
			$ht->setFPasswd( $params['mi_folder_user_fullpath']);
			$ht->setFHtaccess( $params['mi_folder_fullpath'] );
			if (isset($params['mi_name'])) {
				$ht->setAuthName($params['mi_name']);
			}

			$userlist = $ht->getUsers();

			if (in_array($row->username, $userlist)) {
				$ht->delUser($row->username);
				if ($params['use_md5']) {
					$ht->addUser($row->username, $row->password);
				} else {
					$ht->addUser($row->username, $apachepw->apachepw);
				}
				$ht->addLogin();
			}
		}
		return true;
	}

	function delete ( $params ) {
		if (!file_exists($params['mi_folder_fullpath'])) {
			$ht = new htaccess();
			$ht->setFPasswd( $params['mi_folder_user_fullpath']);
			$ht->setFHtaccess( $params['mi_folder_fullpath']);

			$ht->delLogin();
			return true;
		}
		return false;
	}

	function crypt_apr1_md5($plainpasswd) {
		$salt = substr(str_shuffle("abcdefghijklmnopqrstuvwxyz0123456789"), 0, 8);
		$len = strlen($plainpasswd);
		$text = $plainpasswd.'$apr1$'.$salt;
		$bin = pack("H32", md5($plainpasswd.$salt.$plainpasswd));

		for ($i = $len; $i > 0; $i -= 16) {
			$text .= substr($bin, 0, min(16, $i));
		}

		for ($i = $len; $i > 0; $i >>= 1) {
			$text .= ($i & 1) ? chr(0) : $plainpasswd{0};
		}

		$bin = pack("H32", md5($text));

		for ($i = 0; $i < 1000; $i++) {
			$new = ($i & 1) ? $plainpasswd : $bin;
			if ($i % 3) {
				$new .= $salt;
			}
			if ($i % 7) {
				$new .= $plainpasswd;
			}
			$new .= ($i & 1) ? $bin : $plainpasswd;
			$bin = pack("H32", md5($new));
		}

		for ($i = 0; $i < 5; $i++) {
			$k = $i + 6;
			$j = $i + 12;
			if ($j == 16) {
				$j = 5;
			}
			$tmp = $bin[$i].$bin[$k].$bin[$j].$tmp;
		}

		$tmp = chr(0).chr(0).$bin[11].$tmp;
		$tmp = strtr(strrev(substr(base64_encode($tmp), 2)),
			"ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/",
			"./0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz");
		return "$"."apr1"."$".$salt."$".$tmp;
	}

}

class apachepw extends mosDBTable {
	/** @var int Primary key */
	var $id					= null;
	/** @var int */
	var $userid 			= null;
	/** @var string */
	var $apachepw			= null;

	function apachepw (&$db) {
		$this->mosDBTable( '#__acctexp_mi_htaccess_apachepw', 'id', $db );
	}

	function getIDbyUserID ($userid) {
		global $database;

		$database->setQuery( "SELECT id FROM #__acctexp_mi_htaccess_apachepw WHERE userid='" . $userid . "'" );
		return $database->loadResult();
	}

}

?>