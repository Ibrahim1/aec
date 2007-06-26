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

class mi_email {

	function Info () {
		$info = array();
		$info['name'] = "Email";
		$info['desc'] = "Send an Email to one or more adresses on application or expiration of the subscription";

		return $info;
	}

	function Settings ( $params ) {
		$settings = array();
		$settings['sender'] = array("inputE", "Sender E-Mail", "Sender E-Mail Address");
		$settings['sender_name'] = array("inputE", "Sender Name", "The displayed name of the Sender");

		$settings['recipient'] = array("inputE", "Recipient(s)", "Who is to receive this E-Mail? Separate with comma. The rewriting routines explained below will work for this field.");

		$settings['subject'] = array("inputE", "Subject", "Subject");
		$settings['text_html'] = array("list_yesno", "HTML Encoding", "Do you want this email to be HTML encoded? (Make sure that there are not tags in it if you don't want this)");
		$settings['text'] = array("editor", "Text", "Text to be sent when the plan is purchased. The rewriting routines explained below will work for this field.");

		$settings['subject_exp'] = array("inputE", "Expiration Subject", "Expiration Subject");
		$settings['text_exp_html'] = array("list_yesno", "HTML Encoding", "Do you want this email to be HTML encoded? (Make sure that there are not tags in it if you don't want this)");
		$settings['text_exp'] = array("editor", "Expiration Text", "Text to be sent when the plan expires. The rewriting routines explained below will work for this field.");

		$settings['subject_pre_exp'] = array("inputE", "Pre Exp. Subject", "Pre Expiration Subject");
		$settings['text_pre_exp_html'] = array("list_yesno", "HTML Encoding", "Do you want this email to be HTML encoded? (Make sure that there are not tags in it if you don't want this)");
		$settings['text_pre_exp'] = array("editor", "Pre Expiration Text", "Text to be sent when the plan is about to expire (specify when on the previous tab). The rewriting routines explained below will work for this field.");

		$rewriteswitches = array("cms", "user", "expiration", "subscription", "plan");
		$settings['rewriteInfo'] = array("fieldset", "Rewriting Info", AECToolbox::rewriteEngineInfo($rewriteswitches));

		return $settings;
	}

	function pre_expiration_action($params, $userid, $plan, $mi_id) {
		$metaUser = new metaUser ($userid);

		$userflags = $metaUser->objSubscription->getMIflags($plan->id, $mi_id);

		if (is_array($userflags)) {
			if (isset($userflags['EXP_MAIL_SENT'])) {
				if (!(time() > $userflags['EXP_MAIL_ABANDONCHECK'])) {
					return false;
				}
			}
		}

		$newflags['exp_mail_abandoncheck'] = strtotime($metaUser->objExpiration->expiration);
		$newflags['exp_mail_sent'] = time();
		$metaUser->objSubscription->setMIflags($plan->id, $mi_id, $newflags);

		$message = AECToolbox::rewriteEngine($params['text_pre_exp'], $metaUser, $plan);

		$recipients = explode(",", $params['recipient']);

		foreach ($recipients as $current => $email) {
			$recipients[$current] = AECToolbox::rewriteEngine(trim($email), $metaUser, $plan);
		}

		mosMail($params['sender'], $params['sender_name'], $recipients, $params['subject_pre_exp'], $message, $params['text_pre_exp_html']);
		return true;
	}

	function expiration_action($params, $userid, $plan) {
		$metaUser = new metaUser ($userid);

		$message = AECToolbox::rewriteEngine($params['text_exp'], $metaUser, $plan);

		$recipients = explode(",", $params['recipient']);

		foreach ($recipients as $current => $email) {
			$recipients[$current] = AECToolbox::rewriteEngine(trim($email), $metaUser, $plan);
		}

		mosMail($params['sender'], $params['sender_name'], $recipients, $params['subject_exp'], $message, $params['text_exp_html']);
		return true;
	}

	function action($params, $userid, $plan) {
		$metaUser = new metaUser ($userid);

		$message = AECToolbox::rewriteEngine($params['text'], $metaUser, $plan);

		$recipients = explode(",", $params['recipient']);

		foreach ($recipients as $current => $email) {
			$recipients[$current] = AECToolbox::rewriteEngine(trim($email), $metaUser, $plan);
		}

		mosMail($params['sender'], $params['sender_name'], $recipients, $params['subject'], $message, $params['text_html']);
		return true;
	}
}
?>