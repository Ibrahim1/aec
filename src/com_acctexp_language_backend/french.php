<?php
/**
* @version $Id: french.php 16 2007-06-25 09:04:04Z mic $
* @package AEC - Account Control Expiration - Subscription component for Joomla! OS CMS
* @subpackage Language - Backend - French
* @copyright Copyright (C) 2004-2007 Helder Garcia, David Deutsch
* @author David Faurio / Lenamtl
* @license GNU/GPL v.2 http://www.gnu.org/copyleft/gpl.html
*/

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

// Dont allow direct linking
defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );

// mic: NEW 0.12.4
define( '_AEC_LANGUAGE',						'fr' ); // DO NOT CHANGE!!
define( '_CFG_GENERAL_ACTIVATE_PAID_NAME',		'Activate Paid Subscriptions' );
define( '_CFG_GENERAL_ACTIVATE_PAID_DESC',		'Always activate Subscriptions that are paid for automatically instead of requiring an activation code' );

// hacks/install >> ATTENTION: MUST BE HERE AT THIS POSITION, needed later!
define( '_AEC_SPEC_MENU_ENTRY',					'My Subscription' );

// common
define( '_DESCRIPTION_PAYSIGNET',				'mic: Description Paysignnet - CHECK! -');
define( '_AEC_CONFIG_SAVED',					'Configuration saved' );
define( '_AEC_CONFIG_CANCELLED',				'Configuration cancelled' );
define( '_AEC_TIP_NO_GROUP_PF_PB',				'Public Frontend" is NOT a usergroup - and neither is "Public Backend' );
define( '_AEC_CGF_LINK_ABO_FRONTEND',			'Direct Frontend link' );
define( '_AEC_NOT_SET',							'Not set' );
define( '_AEC_COUPON',							'Coupon' );
define( '_AEC_CMN_NEW',							'New' );
define( '_AEC_CMN_CLICK_TO_EDIT',				'Click to edit' );
define( '_AEC_CMN_LIFETIME',					'Lifetime' );
define( '_AEC_CMN_UNKOWN',						'Unknown' );
define( '_AEC_CMN_EDIT_CANCELLED',				'Changes cancelled' );
define( '_AEC_CMN_PUBLISHED',					'Published' );
define( '_AEC_CMN_NOT_PUBLISHED',				'Not Published' );
define( '_AEC_CMN_CLICK_TO_CHANGE',				'Click on icon to toggle state' );
define( '_AEC_CMN_NONE_SELECTED',				'None Selected' );
define( '_AEC_CMN_MADE_VISIBLE',				'made visible' );
define( '_AEC_CMN_MADE_INVISIBLE',				'made invisible' );
define( '_AEC_CMN_TOPUBLISH',					'publish' ); // to ...
define( '_AEC_CMN_TOUNPUBLISH',					'unpublish' ); // to ...
define( '_AEC_CMN_FILE_SAVED',					'File saved' );
define( '_AEC_CMN_ID',							'ID' );
define( '_AEC_CMN_DATE',						'Date' );
define( '_AEC_CMN_EVENT',						'Event' );
define( '_AEC_CMN_TAGS',						'Tags' );
define( '_AEC_CMN_ACTION',						'Action' );
define( '_AEC_CMN_PARAMETER',					'Parameter' );
define( '_AEC_CMN_NONE',						'None' );
define( '_AEC_CMN_WRITEABLE',					'Writeable' );
define( '_AEC_CMN_UNWRITEABLE',					'Unwriteable!' );
define( '_AEC_CMN_UNWRITE_AFTER_SAVE',			'Make unwriteable after saving' );
define( '_AEC_CMN_OVERRIDE_WRITE_PROT',			'Override write protection while saving' );
define( '_AEC_CMN_NOT_SET',						'Not set' );
define( '_AEC_CMN_SEARCH',						'Search' );
define( '_AEC_CMN_APPLY',						'Apply' );
define( '_AEC_CMN_STATUS',						'Status' );
define( '_AEC_FEATURE_NOT_ACTIVE',				'This feature is not active yet' );
define( '_AEC_CMN_YES',							'Yes' );
define( '_AEC_CMN_NO',							'No' );
define( '_AEC_CMN_LANG_CONSTANT_IS_MISSING',	'Language constant <strong>%s</strong> is missing' );
define( '_AEC_CMN_INVISIBLE',					'Invisible' );
define( '_AEC_CMN_EXCLUDED',					'Excluded' );
define( '_AEC_CMN_PENDING',						'Pending' );
define( '_AEC_CMN_ACTIVE',						'Active' );
define( '_AEC_CMN_TRIAL',						'Trial' );
define( '_AEC_CMN_CANCEL',						'Cancelled' );
define( '_AEC_CMN_EXPIRED',						'Expired' );
define( '_AEC_CMN_CLOSED',						'Closed' );

// user(info)
define( '_AEC_USER_USER_INFO',					'User Info' );
define( '_AEC_USER_USERID',						'UserID' );
define( '_AEC_USER_STATUS',						'Status' );
define( '_AEC_USER_ACTIVE',						'Active' );
define( '_AEC_USER_BLOCKED',					'Blocked' );
define( '_AEC_USER_ACTIVE_LINK',				'Activation Link' );
define( '_AEC_USER_PROFILE',					'Profile' );
define( '_AEC_USER_PROFILE_LINK',				'View Profile' );
define( '_AEC_USER_USERNAME',					'Username' );
define( '_AEC_USER_NAME',						'Name' );
define( '_AEC_USER_EMAIL',						'E-Mail' );
define( '_AEC_USER_SEND_MAIL',					'send email' );
define( '_AEC_USER_TYPE',						'Usertype' );
define( '_AEC_USER_REGISTERED',					'Registered' );
define( '_AEC_USER_LAST_VISIT',					'Last Visit' );
define( '_AEC_USER_EXPIRATION',					'Expiration' );
define( '_AEC_USER_CURR_EXPIRE_DATE',			'Current Expiration Date' );
define( '_AEC_USER_LIFETIME',					'Lifetime' );
define( '_AEC_USER_RESET_EXP_DATE',				'Reset Expiration Date' );
define( '_AEC_USER_RESET_STATUS',				'Reset Status' );
define( '_AEC_USER_SUBSCRIPTION',				'Subscription' );
define( '_AEC_USER_PAYMENT_PROC',				'Payment Processor' );
define( '_AEC_USER_CURR_SUBSCR_PLAN',			'Current Subscription Plan' );
define( '_AEC_USER_PREV_SUBSCR_PLAN',			'Previous Subscription Plan' );
define( '_AEC_USER_USED_PLANS',					'Used Subscription Plans' );
define( '_AEC_USER_NO_PREV_PLANS',				'No Subscriptions till yet' );
define( '_AEC_USER_ASSIGN_TO_PLAN',				'Assign to Plan' );
define( '_AEC_USER_TIME',						'time' );
define( '_AEC_USER_TIMES',						'times' );
define( '_AEC_USER_INVOICES',					'Invoices' );
define( '_AEC_USER_NO_INVOICES',				'No invoices till yet' );
define( '_AEC_USER_INVOICE_FACTORY',			'Invoice Factory' );

// new (additional)
define( '_AEC_MSG_MIS_NOT_DEFINED',				'You have not defined any integrations till now - see config' );

// headers
define( '_AEC_HEAD_SETTINGS',					'Settings' );
define( '_AEC_HEAD_HACKS',						'Hacks' );
define( '_AEC_HEAD_PLAN_INFO',					'Subscriptions' );
define( '_AEC_HEAD_LOG',						'Events Log' );
define( '_AEC_HEAD_CSS_EDITOR',					'CSS Editor' );
define( '_AEC_HEAD_MICRO_INTEGRATION',			'Micro Integration Info' );
define( '_AEC_HEAD_ACTIVE_SUBS',				'Active Subscriber' );
define( '_AEC_HEAD_EXCLUDED_SUBS',				'Excluded Subscriber' );
define( '_AEC_HEAD_EXPIRED_SUBS',				'Expired Subscriber' );
define( '_AEC_HEAD_PENDING_SUBS',				'Pending Subscriber' );
define( '_AEC_HEAD_CANCELLED_SUBS',				'Cancelled Subscriber' );
define( '_AEC_HEAD_CLOSED_SUBS',				'Closed Subscriber' );
define( '_AEC_HEAD_MANUAL_SUBS',				'Manual Subscriber' );
define( '_AEC_HEAD_SUBCRIBER',					'Subscriber' );

// hacks (special)
define( '_AEC_HACK_HACK',						'Hack' );
define( '_AEC_HACKS_ISHACKED',					'is hacked' );
define( '_AEC_HACKS_NOTHACKED',					'is not hacked!' );
define( '_AEC_HACKS_UNDO',						'undo now' );
define( '_AEC_HACKS_COMMIT',					'commit' );
define( '_AEC_HACKS_FILE',						'File' );
define( '_AEC_HACKS_LOOKS_FOR',					'The Hack will look for this' );
define( '_AEC_HACKS_REPLACE_WITH',				'... and replace it with this' );

define( '_AEC_HACKS_NOTICE',					'IMPORTANT NOTICE' );
define( '_AEC_HACKS_NOTICE_DESC',				'For security reason you need apply hacks to joomla core files. To do so, please click the "hack file now" links for these files. You may also Add a link to your User Menu so that your Users can have a look at their Subscription Record.' );
define( '_AEC_HACKS_NOTICE_DESC2',				'<strong>All functionally important hacks are marked with an arrow and an exclamation mark.</strong>' );
define( '_AEC_HACKS_NOTICE_DESC3',				'These hacks are <strong>not necessarily in a correct numerical order</strong> - so don\'t wonder if they go #1, #3, #6 - the missing numbers are most likely legacy hacks that you would only see if you had them (incorrectly) applied.' );
define( '_AEC_HACKS_NOTICE_JACL',				'JACL NOTICE' );
define( '_AEC_HACKS_NOTICE_JACL_DESC',			'In case you plan to install the JACLplus component, please make sure that the hacks are <strong>not commited</strong> when installing it. JACL also commits hacks to the core files and it is important that our hacks are committed after the JACL ones.' );

define( '_AEC_HACKS_MENU_ENTRY',				'Menu Entry' );
define( '_AEC_HACKS_MENU_ENTRY_DESC',			'Adds a <strong>' . _AEC_SPEC_MENU_ENTRY . '</strong> menu entry to the Usermenu. With this, a user can manage his invoices and upgrade/renew his or her subscription.' );
define( '_AEC_HACKS_LEGACY',					'<strong>This is a Legacy Hack, please undo!</strong>' );
define( '_AEC_HACKS_NOTAUTH',					'This will correctly link your users to the NotAllowed page with information about your subscriptions' );
define( '_AEC_HACKS_SUB_REQUIRED',				'This will make sure a user has a subscription in order to log in.<br /><strong>Remember to also set [ Require Subscription ] in the AEC Settings!</strong>' );
define( '_AEC_HACKS_REG2',						'This will redirect a registering user to the payment plans after filling out the registration form. Leave this alone to have plan selection only on login (if \'Require Subscription\' is active), or completely voluntary (without requiring a subscription). <strong>Please note that there are two hacks following this, once you have committed it! If you want to have the plans before the user details, these are required as well.</strong>' );
define( '_AEC_HACKS_REG3',						'This will redirect the user to the payment plans page when he or she has not made that selection yet.' );
define( '_AEC_HACKS_REG4',						'This Hack will transmit the AEC variables from the user details form.' );
define( '_AEC_HACKS_REG5',						'This Hack will make the Plans First feature possible - you need to set the switch for this in the settings as well!' );
define( '_AEC_HACKS_MI1',						'Some Micro Integrations rely on receiving a cleartext password for each user. This hack will make sure that the Micro Integrations will be notified when a user changes his/her account.' );
define( '_AEC_HACKS_MI2',						'Some Micro Integrations rely on receiving a cleartext password for each user. This hack will make sure that the Micro Integrations will be notified when a user registers an account.' );
define( '_AEC_HACKS_MI3',						'Some Micro Integrations rely on receiving a cleartext password for each user. This hack will make sure that the Micro Integrations will be notified when an admin changes a user-account.' );
define( '_AEC_HACKS_CB2',						'This will redirect a registering user to the payment plans after filling out the registration form in CB. Leave this alone to have plan selection only on login (if \'Require Subscription\' is active), or completely voluntary (without requiring a subscription). <strong>Please note that there are two hacks following this, once you have committed it! If you want to have the plans before the user details, these are required as well.</strong>' );
define( '_AEC_HACKS_CB6',						'This will redirect the user to the payment plans page when he or she has not made that selection yet.' );
define( '_AEC_HACKS_CB_HTML2',					'This Hack will transmit the AEC variables from the user details form. <strong>In order to make this work, set \'Plans First\' in the AEC Settings.</strong>' );
define( '_AEC_HACKS_UHP2',						'UHP2 Menu Entry' );
define( '_AEC_HACKS_UHP2_DESC',					'Adds a <strong>' . _AEC_SPEC_MENU_ENTRY . '</strong> menu entry to the UHP2 Usermenu. With this, a user can manage his invoices and upgrade/renew his or her subscription.' );
define( '_AEC_HACKS_CBM',						'If you are using the Comprofiler Moderator Module, you have to hack it in order to prevent an infinite loops issue.' );

// log
	// settings
define( '_AEC_LOG_SH_SETT_SAVED',				'settings change' );
define( '_AEC_LOG_LO_SETT_SAVED',				'The Settings for AEC have been saved' );
	// heartbeat
define( '_AEC_LOG_SH_HEARTBEAT',				'Heartbeat' );
define( '_AEC_LOG_LO_HEARTBEAT',				'Heartbeat carried out:' );
define( '_AEC_LOG_AD_HEARTBEAT_DO_NOTHING',		'does nothing' );
	// install
define( '_AEC_LOG_SH_INST',						'AEC install' );
define( '_AEC_LOG_LO_INST',						'The AEC Version %s has been installed' );

// install texts
define( '_AEC_INST_NOTE_IMPORTANT',				'Important Notice' );
define( '_AEC_INST_NOTE_SECURITY',				'For <strong>your system security</strong> you need apply hacks to joomla core files. For your convenience, we have included an autohacking feature that does just that with the click of a link' );
define( '_AEC_INST_APPLY_HACKS',				'In order to commit these hacks right now, go to the %s. (You can also access this page later on from the AEC central view or menu)' );
define( '_AEC_INST_APPLY_HACKS_LTEXT',			'hacks page' );
define( '_AEC_INST_NOTE_UPGRADE',				'<strong>If you are upgrading, make sure to check the hacks page anyways, since there are changes from time to time!!!</strong>' );
define( '_AEC_INST_NOTE_HELP',					'To help you along with frequently encountered problems, we have created a %s that will help you ship around the most common setup problems (access is also avaliable from the AEC central.' );
define( '_AEC_INST_NOTE_HELP_LTEXT',			'help function' );
define( '_AEC_INST_HINTS',						'Hints' );
define( '_AEC_INST_HINT1',						'We encourage you to visit the <a href="%s" target="_blank">globalnerd.org forums</a> and to <strong>participate in the discussion there</strong>. Chances are, that other users have found the same bugs and it is equally likely that there is at least a fix to hack in or even a new version.' );
define( '_AEC_INST_HINT2',						'In any case (and especially if you use this on a live site): go through your settings and make a test subscription to see whether everything is working to your satisfaction! Although we try our best to make upgrading as flawless as possible, some fundamental changes to our program may not be possible to cushion for all users.' );
define( '_AEC_INST_ATTENTION',					'No need to use the old logins!' );
define( '_AEC_INST_ATTENTION1',					'If you still have the old AEC login modules installed, please uninstall it (no matter which one, regular or CB) and use the normal joomla or CB login module. There is no need to use these old modules anymore.' );
define( '_AEC_INST_MAIN_COMP_ENTRY',			'AEC Subscription Mgmnt' );
define( '_AEC_INST_ERRORS',						'<strong>Attention</strong><br />AEC could not be installed completely, following errors occured during the install process:<br />' );

// help
define( '_AEC_CMN_HELP',						'Help' );
define( '_AEC_HELP_DESC',						'On this page, the AEC takes a look at its own configuration and tells you whenever it finds errors that need to be adressed.' );
define( '_AEC_HELP_GREEN',						'Green</strong> items indicate trivial problems or notifications, or problems that have already been solved.' );
define( '_AEC_HELP_YELLOW',						'Yellow</strong> items are mostly of cosmetical importance (like additions to the user frontend), but also issues that are most likely a deliberate choice of the administrator.' );
define( '_AEC_HELP_RED',						'Red</strong> items are of high importance to either the way the AEC works or the security of your System.' );
define( '_AEC_HELP_GEN',						'Please note that even though we try to cover as many errors and problems as possible, this page can only point at the most obvious ones and is by far not completed yet (beta&trade;)' );
define( '_AEC_HELP_QS_HEADER',					'AEC Quickstart Manual' );
define( '_AEC_HELP_QS_DESC',					'Before doing anything about the below issues, please read our %s!' );
define( '_AEC_HELP_QS_DESC_LTEXT',				'Quickstart Manual' );
define( '_AEC_HELP_SER_SW_DIAG1',				'File Permissions Problems' );
define( '_AEC_HELP_SER_SW_DIAG1_DESC',			'AEC has detected that you are using an Apache Webserver - To be able to hack files on such a server, those files have to be owned by the webserver user, which apparently is not so for at least one of the neccessary files.' );
define( '_AEC_HELP_SER_SW_DIAG1_DESC2',			'We recommend that you temporarily change the file permissions to 777, then commit the hacks and change it back after that. <strong>Contact your server host or administrator for the possibly quickest response when experiencing problems.</strong> This is the same for the file permission related suggestion(s) below.' );
define( '_AEC_HELP_SER_SW_DIAG2',				'joomla.php/mambo.php File Permissions' );
define( '_AEC_HELP_SER_SW_DIAG2_DESC',			'The AEC has detected that your joomla.php is not owned by the webserver.' );
define( '_AEC_HELP_SER_SW_DIAG2_DESC2',			'Access your webserver via ssh and go to the directory \"<yoursiteroot>/includes\". There, type in this command: \"chown wwwrun joomla.php\" (or \"chown wwwrun mambo.php\" in case you are using mambo).' );
define( '_AEC_HELP_SER_SW_DIAG3',				'Legacy Hacks Detected!' );
define( '_AEC_HELP_SER_SW_DIAG3_DESC',			'You appear to have old Hacks commited to your System.' );
define( '_AEC_HELP_SER_SW_DIAG3_DESC2',			'In Order for the AEC to function correctly, please review the Hacks page and follow the steps presented there.' );
define( '_AEC_HELP_SER_SW_DIAG4',				'File Permissions Problems' );
define( '_AEC_HELP_SER_SW_DIAG4_DESC',			'AEC can not detect the write permission status of the files it wants to hack as it appears that your installation of php has been compiled with the option \"--disable-posix\". <strong>You can still try to commit the hacks - if it does not work, its most likely a file permission problem</strong>.' );
define( '_AEC_HELP_SER_SW_DIAG4_DESC2',			'We recommend that you either recompile your php version with the said option left out or contact your webserver administrator on this matter.' );
define( '_AEC_HELP_DIAG_CMN1',					'joomla.php/mambo.php Hack' );
define( '_AEC_HELP_DIAG_CMN1_DESC',				'In order for the AEC to function, this hack is required to redirect users to the AEC Verification Routines on Login.' );
define( '_AEC_HELP_DIAG_CMN1_DESC2',			'Go to the Hacks page and commit the hack' );
define( '_AEC_HELP_DIAG_CMN2',					'"My Subscription" Menu Entry' );
define( '_AEC_HELP_DIAG_CMN2_DESC',				'A link to a MySubscription page for your users makes it easy for them to track their subscription.' );
define( '_AEC_HELP_DIAG_CMN2_DESC2',			'Go to the Hacks page and create the link.' );
define( '_AEC_HELP_DIAG_CMN3',					'JACL not installed' );
define( '_AEC_HELP_DIAG_CMN3_DESC',				'If you plan to install JACLplus in your joomla!/mambo system, please make sure that the AEC hacks are not committed when doing so. If you have already committed them, you can easily undo them in our hacks page.' );
define( '_AEC_HELP_DIAG_NO_PAY_PLAN',			'No Active Payment Plan!' );
define( '_AEC_HELP_DIAG_NO_PAY_PLAN_DESC',		'There seems to be no Payment Plan published yet - The AEC needs at least one active plan to function' );
define( '_AEC_HELP_DIAG_GLOBAL_PLAN',			'Global Entry Plan' );
define( '_AEC_HELP_DIAG_GLOBAL_PLAN_DESC',		'There is a Global Entry Plan active in your configuration. If you are not sure what this is, you should probably disable it - Its an entry plan that each new user will be assigned to without having a choice.' );
define( '_AEC_HELP_DIAG_SERVER_NOT_REACHABLE',	'Server Apparantly Not Reachable' );
define( '_AEC_HELP_DIAG_SERVER_NOT_REACHABLE_DESC',	'It seems that you have installed the AEC on a local machine. In order to retrieve notifications (and thus to have the component work correctly), you need to install it on a server that is reachable by a fixed IP or Domain!' );
define( '_AEC_HELP_DIAG_SITE_OFFLINE',			'Site Offline' );
define( '_AEC_HELP_DIAG_SITE_OFFLINE_DESC',		'You have decided to take your site offline - please note that this can have an effect on notification processes and thus on your payment workflow.' );
define( '_AEC_HELP_DIAG_REG_DISABLED',			'User Registration Disabled' );
define( '_AEC_HELP_DIAG_REG_DISABLED_DESC',		'Your User Registration is disabled. This way, no new customer can subscribe to your website.' );
define( '_AEC_HELP_DIAG_LOGIN_DISABLED',		'User Login Disabled' );
define( '_AEC_HELP_DIAG_LOGIN_DISABLED_DESC',	'Your have disabled the Frontend Login functionality. Because of this, none of your customers can use your website.' );
define( '_AEC_HELP_DIAG_PAYPAL_BUSS_ID',		'Paypal Check Business ID' );
define( '_AEC_HELP_DIAG_PAYPAL_BUSS_ID_DESC',	'This routine checks for a matching paypal business ID to enhance security with Paypal Transactions.' );
define( '_AEC_HELP_DIAG_PAYPAL_BUSS_ID_DESC1',	'Please disable this setting in case you encounter problems where you receive payments correctly, but without users being enabled. Disable the Setting in general in case you are using multiple e-mail adresses with your Paypal account.' );

// micro integration
	// general
define( '_AEC_MI_REWRITING_INFO',				'Rewriting Info' );
define( '_AEC_MI_SET1_INAME',					'Subscription at %s - User: %s (%s)' );
	// htaccess
define( '_AEC_MI_HTACCESS_INFO_DESC',			'Protect a folder with a .htaccess file and only allow users of this subscription to access it with their joomla account details.' );
	// email
define( '_AEC_MI_EMAIL_INFO_DESC',				'Send an Email to one or more adresses on application or expiration of the subscription' );
	// idev
define( '_AEC_MI_IDEV_DESC',					'Connect your sales to iDevAffiliate' );
	// mosetstree
define( '_AEC_MI_MOSETSTREE_DESC',				'Restrict the amount of listings a user can publish' );
	// mysql-query
define( '_AEC_MI_MYSQL_DESC',					'Specify a mySQL query that should be carried out with this subscription or on its expiration' );
	// remository
define( '_AEC_MI_REMOSITORY_DESC',				'Choose the amount of files a user can download and what reMOSitory group should be assigned to the user account' );
	// VirtueMart
define( '_AEC_MI_VIRTUEMART_DESC',				'Choose the VirtueMart usergroup this user should be applied to' );

// central
define( '_AEC_CENTR_CENTRAL',					'AEC Central' );
define( '_AEC_CENTR_EXCLUDED',					'Excluded' );
define( '_AEC_CENTR_PLANS',						'Plans' );
define( '_AEC_CENTR_PENDING',					'Pending' );
define( '_AEC_CENTR_ACTIVE',					'Active' );
define( '_AEC_CENTR_EXPIRED',					'Expired' );
define( '_AEC_CENTR_CANCELLED',					'Cancelled' );
define( '_AEC_CENTR_CLOSED',					'Closed' );
define( '_AEC_CENTR_SETTINGS',					'Settings' );
define( '_AEC_CENTR_EDIT_CSS',					'Edit CSS' );
define( '_AEC_CENTR_V_INVOICES',				'View Invoices' );
define( '_AEC_CENTR_COUPONS',					'Coupons' );
define( '_AEC_CENTR_COUPONS_STATIC',			'Coupons Static' );
define( '_AEC_CENTR_VIEW_HISTORY',				'View History' );
define( '_AEC_CENTR_HACKS',						'Hacks' );
define( '_AEC_CENTR_M_INTEGRATION',				'Micro Integr.' );
define( '_AEC_CENTR_HELP',						'Help' );
define( '_AEC_CENTR_LOG',						'EventLog' );
define( '_AEC_CENTR_MANUAL',					'Manual' );
define( '_AEC_QUICKSEARCH',						'Quick Search' );
define( '_AEC_QUICKSEARCH_DESC',				'Put in a users name, username, userid or an invoice number to get directly linke to the users profile. If there are more than one result, they will be shown below.' );
define( '_AEC_QUICKSEARCH_MULTIRES',			'Multiple Results!' );
define( '_AEC_QUICKSEARCH_MULTIRES_DESC',		'Please pick one of the following users:' );
define( '_AEC_QUICKSEARCH_THANKS',				'Thank you for making a simple function very happy.' );
define( '_AEC_QUICKSEARCH_NOTFOUND',			'User not found' );

// select lists
define( '_AEC_SEL_EXCLUDED',					'Excluded' );
define( '_AEC_SEL_PENDING',						'Pending' );
define( '_AEC_SEL_ACTIVE',						'Active' );
define( '_AEC_SEL_EXPIRED',						'Expired' );
define( '_AEC_SEL_CLOSED',						'Closed' );
define( '_AEC_SEL_CANCELLED',					'Storno' );
define( '_AEC_SEL_NOT_CONFIGURED',				'Not Configured' );

// footer
define( '_AEC_FOOT_TX_CHOOSING',				'Thank you for choosing the Account Expiration Control Component!' );
define( '_AEC_FOOT_TX_GPL',						'This Joomla/Mambo component was developed and released under the <a href="http://www.gnu.org/copyleft/gpl.html" target="_blank">GNU/GPL</a> license by Helder Garcia and David Deutsch from <a href="http://www.globalnerd.org" target="_blank">globalnerd.org</a>' );
define( '_AEC_FOOT_TX_SUBSCRIBE',				'If you want more features, professional service, updates, manuals and online help for this component, you can subscribe to our services at the above link. It helps us a lot in our development!' );
define( '_AEC_FOOT_CREDIT',						'Please read our %s' );
define( '_AEC_FOOT_CREDIT_LTEXT',				'full credits' );
define( '_AEC_FOOT_VERSION_CHECK',				'Check for latest Version' );
define( '_AEC_FOOT_MEMBERSHIP',					'Get a membership with documentation and support' );

// alerts
define( '_AEC_ALERT_SELECT_FIRST',				'Select an item to configure' );
define( '_AEC_ALERT_SELECT_FIRST_TO',			'Please select first an item to' );

// messages
define( '_AEC_MSG_NODELETE_SUPERADMIN',			'You cannot delete a Super Administrator' );
define( '_AEC_MSG_NODELETE_YOURSELF',			'You cannot delete Yourself!' );
define( '_AEC_MSG_NODELETE_EXCEPT_SUPERADMIN',	'Only Superadmins can perform this action!' );
define( '_AEC_MSG_SUBS_RENEWED',				'subscription(s) renewed' );
define( '_AEC_MSG_SUBS_ACTIVATED',				'subscription(s) activated' );
define( '_AEC_MSG_NO_ITEMS_TO_DELETE',			'No item found to delete' );
define( '_AEC_MSG_NO_DEL_W_ACTIVE_SUBSCRIBER',	'You cannot delete plans with active subscribers' );
define( '_AEC_MSG_ITEMS_DELETED',				'Item(s) deleted' );
define( '_AEC_MSG_ITEMS_SUCESSFULLY',			'%s Item(s) successfully' );
define( '_AEC_MSG_SUCESSFULLY_SAVED',			'Changes successfully saved' );
define( '_AEC_MSG_ITEMS_SUCC_PUBLISHED',		'Item(s) successfully Published' );
define( '_AEC_MSG_ITEMS_SUCC_UNPUBLISHED',		'Item(s) successfully Unpublished' );
define( '_AEC_MSG_NO_COUPON_CODE',				'You must specify a coupon code!' );
define( '_AEC_MSG_OP_FAILED',					'Operation Failed: Could not open %s' );
define( '_AEC_MSG_OP_FAILED_EMPTY',				'Operation failed: Content empty' );
define( '_AEC_MSG_OP_FAILED_NOT_WRITEABLE',		'Operation failed: The file is not writable.' );
define( '_AEC_MSG_OP_FAILED_NO_WRITE',			'Operation failed: Failed to open file for writing' );
define( '_AEC_MSG_INVOICE_CLEARED',				'Invoice cleared' );

// languages (e.g. PayPal) - must be ISO 3166 Two-Character Country Codes
define( '_AEC_LANG_DE',							'German' );
define( '_AEC_LANG_GB',							'English' );
define( '_AEC_LANG_FR',							'French' );
define( '_AEC_LANG_IT',							'Italian' );
define( '_AEC_LANG_ES',							'Spanish' );
define( '_AEC_LANG_US',							'English US' );

// currencies
define( '_CURRENCY_RSD',						'Serbian Dinar' );

// --== COUPON EDIT ==--
define( '_COUPON_DETAIL_TITLE', 'Coupon');
define( '_COUPON_RESTRICTIONS_TITLE', 'Restrict.');
define( '_COUPON_RESTRICTIONS_TITLE_FULL', 'Restrictions');
define( '_COUPON_MI', 'Micro Int.');
define( '_COUPON_MI_FULL', 'Micro Integrations');

define( '_COUPON_GENERAL_NAME_NAME', 'Name');
define( '_COUPON_GENERAL_NAME_DESC', 'Enter the (internal&amp;external) name for this coupon');
define( '_COUPON_GENERAL_COUPON_CODE_NAME', 'Coupon Code');
define( '_COUPON_GENERAL_COUPON_CODE_DESC', 'Enter the Coupon Code for this coupon - the randomly generated coupon code is checked to be unique within the system');
define( '_COUPON_GENERAL_DESC_NAME', 'Description');
define( '_COUPON_GENERAL_DESC_DESC', 'Enter the (internal) description for this coupon');
define( '_COUPON_GENERAL_ACTIVE_NAME', 'Active');
define( '_COUPON_GENERAL_ACTIVE_DESC', 'Set whether this coupon is active (can be used)');
define( '_COUPON_GENERAL_TYPE_NAME', 'Static');
define( '_COUPON_GENERAL_TYPE_DESC', 'Select whether you want this to be a static coupon. These are stored in a separate table for quicker access, the general distinction being that static coupons are coupons that are used by a lot of users while non-static coupons are for one user.');

define( '_COUPON_GENERAL_MICRO_INTEGRATIONS_NAME', 'Micro Integrations');
define( '_COUPON_GENERAL_MICRO_INTEGRATIONS_DESC', 'Select the Micro Integration(s) which you want to be called when this coupon is used');

define( '_COUPON_PARAMS_AMOUNT_USE_NAME', 'Use Amount');
define( '_COUPON_PARAMS_AMOUNT_USE_DESC', 'Select whether you want to use a direct discount amount');
define( '_COUPON_PARAMS_AMOUNT_NAME', 'Discount Amount');
define( '_COUPON_PARAMS_AMOUNT_DESC', 'Enter the Amount that you want to deduct from the next amount');
define( '_COUPON_PARAMS_AMOUNT_PERCENT_USE_NAME', 'Use Percentage');
define( '_COUPON_PARAMS_AMOUNT_PERCENT_USE_DESC', 'Select whether you want a percentage deducted from the actual amount');
define( '_COUPON_PARAMS_AMOUNT_PERCENT_NAME', 'Discount Percentage');
define( '_COUPON_PARAMS_AMOUNT_PERCENT_DESC', 'Enter the percentage that you want deducted from the amount');
define( '_COUPON_PARAMS_PERCENT_FIRST_NAME', 'Percent First');
define( '_COUPON_PARAMS_PERCENT_FIRST_DESC', 'If you combine percentage and amount, do you want the percentage to be deducted first?');
define( '_COUPON_PARAMS_USEON_TRIAL_NAME', 'Use On Trial?');
define( '_COUPON_PARAMS_USEON_TRIAL_DESC', 'Do you want to let the user apply this discount to a trial amount?');
define( '_COUPON_PARAMS_USEON_FULL_NAME', 'Use On Full?');
define( '_COUPON_PARAMS_USEON_FULL_DESC', 'Do you want to let the user apply this discount to the actual amount? (With recurring billing: to the first regular payment)');
define( '_COUPON_PARAMS_USEON_FULL_ALL_NAME', 'Every Full?');
define( '_COUPON_PARAMS_USEON_FULL_ALL_DESC', 'If the user is using recurring payments, do you want to grant this discount for each subsequent payment? (Or just for the first, if that applies - then select no)');

define( '_COUPON_PARAMS_HAS_START_DATE_NAME', 'Use Start Date');
define( '_COUPON_PARAMS_HAS_START_DATE_DESC', 'Do you want to allow your users to use this coupon from a certain date on?');
define( '_COUPON_PARAMS_START_DATE_NAME', 'Start Date');
define( '_COUPON_PARAMS_START_DATE_DESC', 'Select the date at which you want to start allowing the usage of this coupon');
define( '_COUPON_PARAMS_HAS_EXPIRATION_NAME', 'Use Expiration Date');
define( '_COUPON_PARAMS_HAS_EXPIRATION_DESC', 'Do you want to restrict the usage of this coupon to a certain date?');
define( '_COUPON_PARAMS_EXPIRATION_NAME', 'Expiration Date');
define( '_COUPON_PARAMS_EXPIRATION_DESC', 'Select the date at which you want to stop allowing the usage of this coupon');
define( '_COUPON_PARAMS_HAS_MAX_REUSE_NAME', 'Restrict Reuse?');
define( '_COUPON_PARAMS_HAS_MAX_REUSE_DESC', 'Do you want to restrict the number of times this coupon may be used?');
define( '_COUPON_PARAMS_MAX_REUSE_NAME', 'Max Uses');
define( '_COUPON_PARAMS_MAX_REUSE_DESC', 'Choose the number of times this coupon can be used');

define( '_COUPON_PARAMS_USECOUNT_NAME', 'Use Count');
define( '_COUPON_PARAMS_USECOUNT_DESC', 'Reset the number of times this Coupon has been used');

define( '_COUPON_PARAMS_USAGE_PLANS_ENABLED_NAME', 'Set Plan');
define( '_COUPON_PARAMS_USAGE_PLANS_ENABLED_DESC', 'Do you want to allow this coupon only for certain plans?');
define( '_COUPON_PARAMS_USAGE_PLANS_NAME', 'Plans');
define( '_COUPON_PARAMS_USAGE_PLANS_DESC', 'Choose which plans this coupon can be used for');

define( '_COUPON_RESTRICTIONS_MINGID_ENABLED_NAME', 'Enable Min GID:');
define( '_COUPON_RESTRICTIONS_MINGID_ENABLED_DESC', 'Enable this setting if you want to restrict whether a user can use this coupon by a minimum usergroup.');
define( '_COUPON_RESTRICTIONS_MINGID_NAME', 'Visibility Group:');
define( '_COUPON_RESTRICTIONS_MINGID_DESC', 'The minimum user level required to use this coupon.');
define( '_COUPON_RESTRICTIONS_FIXGID_ENABLED_NAME', 'Enable Fixed GID:');
define( '_COUPON_RESTRICTIONS_FIXGID_ENABLED_DESC', 'Enable this setting if you want to restrict this coupon to one usergroup.');
define( '_COUPON_RESTRICTIONS_FIXGID_NAME', 'Set Group:');
define( '_COUPON_RESTRICTIONS_FIXGID_DESC', 'Only users with this usergroup can use this coupon.');
define( '_COUPON_RESTRICTIONS_MAXGID_ENABLED_NAME', 'Enable Max GID:');
define( '_COUPON_RESTRICTIONS_MAXGID_ENABLED_DESC', 'Enable this setting if you want to restrict whether a user use this coupon by a maximum usergroup.');
define( '_COUPON_RESTRICTIONS_MAXGID_NAME', 'Maximum Group:');
define( '_COUPON_RESTRICTIONS_MAXGID_DESC', 'The maximum user level a user can have to use this coupon.');

define( '_COUPON_RESTRICTIONS_PREVIOUSPLAN_REQ_ENABLED_NAME', 'Required Prev. Plan:');
define( '_COUPON_RESTRICTIONS_PREVIOUSPLAN_REQ_ENABLED_DESC', 'Enable checking for previous payment plan');
define( '_COUPON_RESTRICTIONS_PREVIOUSPLAN_REQ_NAME', 'Plan:');
define( '_COUPON_RESTRICTIONS_PREVIOUSPLAN_REQ_DESC', 'A user will only be able to use this coupon if he or she used the selected plan before the one currently in use');
define( '_COUPON_RESTRICTIONS_CURRENTPLAN_REQ_ENABLED_NAME', 'Required Curr. Plan:');
define( '_COUPON_RESTRICTIONS_CURRENTPLAN_REQ_ENABLED_DESC', 'Enable checking for currently present payment plan');
define( '_COUPON_RESTRICTIONS_CURRENTPLAN_REQ_NAME', 'Plan:');
define( '_COUPON_RESTRICTIONS_CURRENTPLAN_REQ_DESC', 'A user will only be able to use this coupon if he or she is currently assigned to, or has expired from the plan selected here');
define( '_COUPON_RESTRICTIONS_OVERALLPLAN_REQ_ENABLED_NAME', 'Required Used Plan:');
define( '_COUPON_RESTRICTIONS_OVERALLPLAN_REQ_ENABLED_DESC', 'Enable checking for overall used payment plan');
define( '_COUPON_RESTRICTIONS_OVERALLPLAN_REQ_NAME', 'Plan:');
define( '_COUPON_RESTRICTIONS_OVERALLPLAN_REQ_DESC', 'A user will only be able to use this coupon if he or she has used the selected plan once, no matter when');

define( '_COUPON_RESTRICTIONS_USED_PLAN_MIN_ENABLED_NAME', 'Min Used Plan:');
define( '_COUPON_RESTRICTIONS_USED_PLAN_MIN_ENABLED_DESC', 'Enable checking for the minimum number of times your consumers have subscribed to a specified payment plan in order to be able to use this coupon');
define( '_COUPON_RESTRICTIONS_USED_PLAN_MIN_AMOUNT_NAME', 'Used Amount:');
define( '_COUPON_RESTRICTIONS_USED_PLAN_MIN_AMOUNT_DESC', 'The minimum amount a user has to have used the selected plan');
define( '_COUPON_RESTRICTIONS_USED_PLAN_MIN_NAME', 'Plan:');
define( '_COUPON_RESTRICTIONS_USED_PLAN_MIN_DESC', 'The payment plan that the user has to have used the specified number of times at least');
define( '_COUPON_RESTRICTIONS_USED_PLAN_MAX_ENABLED_NAME', 'Max Used Plan:');
define( '_COUPON_RESTRICTIONS_USED_PLAN_MAX_ENABLED_DESC', 'Enable checking for the maximum number of times your consumers have subscribed to a specified payment plan in order to be able to use this coupon');
define( '_COUPON_RESTRICTIONS_USED_PLAN_MAX_AMOUNT_NAME', 'Used Amount:');
define( '_COUPON_RESTRICTIONS_USED_PLAN_MAX_AMOUNT_DESC', 'The maximum amount a user can have used the selected plan');
define( '_COUPON_RESTRICTIONS_USED_PLAN_MAX_NAME', 'Plan:');
define( '_COUPON_RESTRICTIONS_USED_PLAN_MAX_DESC', 'The payment plan that the user has to have used the specified number of times at most');

define( '_COUPON_RESTRICTIONS_RESTRICT_COMBINATION_NAME', 'Restrict Combination:');
define( '_COUPON_RESTRICTIONS_RESTRICT_COMBINATION_DESC', 'Choose to not let your users combine this coupon with one of the following');
define( '_COUPON_RESTRICTIONS_BAD_COMBINATIONS_NAME', 'Coupons:');
define( '_COUPON_RESTRICTIONS_BAD_COMBINATIONS_DESC', 'Make a selection which coupons this one is not to be used with');

// end new 0.12.4 (mic)

// --== BARRE OUTIL BACKEND ==--
define( '_EXPIRE_SET','Set Expiration:');
define( '_EXPIRE_NOW','Expire');
define( '_EXPIRE_EXCLUDE','Exclude');
define( '_EXPIRE_INCLUDE','Include');
define( '_EXPIRE_CLOSE','Close');
define( '_EXPIRE_01MONTH','set 1 Month');
define( '_EXPIRE_03MONTH','set 3 Months');
define( '_EXPIRE_12MONTH','set 12 Months');
define( '_EXPIRE_ADD01MONTH','add 1 Month');
define( '_EXPIRE_ADD03MONTH','add 3 Months');
define( '_EXPIRE_ADD12MONTH','add 12 Months');
define( '_CONFIGURE','Configurer');
define( '_REMOVE','D&eacute;placer vers la liste d&acute;exclusion');
define( '_CNAME','Nom');
define( '_USERLOGIN','Nom d&acute;utilisateur');
define( '_EXPIRATION','Date d&acute;expiration');
define( '_USERS','Utilisateurs');
define( '_DISPLAY','Afficher');
define( '_NOTSET','Exclu');
define( '_SAVE','Enregistrer');
define( '_CANCEL','Annuler');
define( '_EXP_ASC','Expiration ordre Croissant');
define( '_EXP_DESC','Expiration ordre D&eacute;croissant');
define( '_NAME_ASC','Nom ordre Croissant');
define( '_NAME_DESC','Nom ordre D&eacute;croissant');
define( '_LOGIN_ASC','Utilisateur ordre Croissant');
define( '_LOGIN_DESC','Utilisateur ordre D&eacute;croissant');
define( '_SIGNUP_ASC','Signup Date Asc');
define( '_SIGNUP_DESC','Signup Date Desc');
define( '_LASTPAY_ASC','Last Payment Asc');
define( '_LASTPAY_DESC','Last Payment Desc');
define( '_PLAN_ASC','Plan Asc');
define( '_PLAN_DESC','Plan Desc');
define( '_STATUS_ASC','Status Asc');
define( '_STATUS_DESC','Status Desc');
define( '_TYPE_ASC','Payment Type Asc');
define( '_TYPE_DESC','Payment Type Desc');
define( '_ORDER_BY','Class&eacute; par :');
define( '_SAVED', 'Enregistr&eacute;');
define( '_CANCELED', 'Annul&eacute;');
define( '_CONFIGURED', 'Objet configur&eacute;');
define( '_REMOVED', 'Objet retir&eacute; de la liste.');
define( '_EOT_TITLE', 'Comptes ferm&eacute;s');
define( '_EOT_DESC', 'Cette liste n&acute;inclut pas les comptes g&eacute;r&eacute;s manuellement, mais uniquement ceux g&eacute;r&eacute;s avec PayPal. Quand vous effacez une entr&eacute;e, l&acute;utilisateur est retir&eacute; de la base de donn&eacute;es et tous les mouvements li&eacute;s &agrave; son activit&eacute; sont retir&eacute;s de la table locale historique Paypal.');
define( '_EOT_DATE', 'Date de fin de terme');
define( '_EOT_CAUSE', 'Cause');
define( '_EOT_CAUSE_FAIL', '&Eacute;chec de paiement');
define( '_EOT_CAUSE_BUYER', 'Annul&eacute; par l&acute;utilisateur');
define( '_EOT_CAUSE_FORCED', 'Annul&eacute; par l&acute;administrateur');
define( '_REMOVE_CLOSED', 'Effacer l&acute;utilisateur');
define( '_FORCE_CLOSE', 'Forcer la fermeture');
define( '_PUBLISH_PAYPLAN', 'Publier');
define( '_UNPUBLISH_PAYPLAN', 'D&eacute;publier');
define( '_NEW_PAYPLAN', 'Nouveau');
define( '_EDIT_PAYPLAN', '&Eacute;diter');
define( '_REMOVE_PAYPLAN', 'Effacer');
define( '_SAVE_PAYPLAN', 'Enregistrer');
define( '_CANCEL_PAYPLAN', 'Annuler');
define( '_PAYPLANS_TITLE', 'Gestionnaire des Plans');
define( '_PAYPLANS_MAINDESC', 'Les Plans Publi&eacute;s seront des options pour l&acute;utilisateur sur le frontend du site. Ces plans sont valides uniquement pour les paiements Paypal.');
define( '_PAYPLAN_NAME', 'Nom');
define( '_PAYPLAN_DESC', 'Description (premiers 50 caract&egrave;res)');
define( '_PAYPLAN_ACTIVE', 'Publi&eacute;');
define( '_PAYPLAN_VISIBLE', 'Visible');
define( '_PAYPLAN_A3', 'Taux');
define( '_PAYPLAN_P3', 'P&eacute;riode');
define( '_PAYPLAN_T3', 'Unit&eacute; de temps');
define( '_PAYPLAN_USERCOUNT', 'Abonn&eacute;');
define( '_PAYPLAN_EXPIREDCOUNT', 'Expired');
define( '_PAYPLAN_TOTALCOUNT', 'Total');
define( '_PAYPLAN_REORDER', 'R&eacute;ordonner');
define( '_PAYPLAN_DETAIL', 'R&eacute;gulier');
define( '_ALTERNATIVE_PAYMENT', 'Virement bancaire');
define( '_SUBSCR_DATE', 'Date d&acute;abonnement');
define( '_ACTIVE_TITLE', 'Abonnements Activ&eacute;s');
define( '_ACTIVE_DESC', 'Cette liste n&acute;inclut pas les comptes g&eacute;r&eacute;s manuellement, mais uniquement ceux g&eacute;r&eacute;s avec PayPal.');
define( '_LASTPAY_DATE', 'Date du dernier paiement');
define( '_USERPLAN', 'Plan');
define( '_CANCELLED_TITLE', 'Abonnements annul&eacute;s');
define( '_CANCELLED_DESC', 'Cette liste n&acute;inclut pas les comptes g&eacute;r&eacute;s manuellement, mais uniquement ceux g&eacute;r&eacute;s avec PayPal. Ce sont les abonnements annul&eacute;s par l&acute;utilisateur et n&acute;ayant pas atteint leurs termes.');
define( '_CANCEL_DATE', 'Date d&acute;annulation');
define( '_MANUAL_DESC', 'Lorsque vous effacez un utilisateur, il sera compl&egrave;tement effac&eacute; de la base de donn&eacute;es.');
define( '_REPEND_ACTIVE', 'En attente de nouveau');
define( '_FILTER_PLAN', '- S&eacute;lectionner le Plan -');
define( '_BIND_USER', 'Assign&eacute; &agrave;:');
define( '_PLAN_FILTER','Filtre Plan:');
define( '_CENTRAL_PAGE','Central');

// --== USER FORM ==--
define( '_USERINVOICE_ACTION_REPEAT','repeat&nbsp;Link');
define( '_USERINVOICE_ACTION_CANCEL','cancel');
define( '_USERINVOICE_ACTION_CLEAR','mark&nbsp;cleared');
define( '_USERINVOICE_ACTION_CLEAR_APPLY','clear&nbsp;&amp;&nbsp;apply&nbsp;Plan');

// --== PARAMETRES BACKEND ==--

// TAB 1 - Param�tres de AEC
define( '_HISTORY_COL1_TITLE', 'Invoice');
define( '_HISTORY_COL2_TITLE', 'Amount');
define( '_HISTORY_COL3_TITLE', 'Payment Date');
define( '_HISTORY_COL4_TITLE', 'Method');
define( '_HISTORY_COL5_TITLE', 'Action');
define( '_HISTORY_COL6_TITLE', 'Plan');
define( '_CFG_TAB1_TITLE', ' Configuration');
define( '_CFG_TAB1_SUBTITLE', 'Options utilisateur');

define( '_CFG_TAB1_OPT1NAME', 'P&eacute;riode d&acute;expiration initiale
:');
define( '_CFG_TAB1_OPT1DESC', 'La p&eacute;riode d&acute;expiration par d&eacute;faut, en jours, pour les nouvelles inscriptions. Relative &agrave; la date d&acute;inscription. Si vous voulez que les inscriptions de vos nouveaux utilisateurs expirent par d&eacute;faut, utilisez -1 (moins un) ici. Ceci n&acute;a aucun impact si les utilisateurs se servent du syst&egrave;me de paiement automatis&eacute; lors de l&acute;inscription (par exemple Paypal).');
define( '_CFG_TAB1_OPT3NAME', 'Alerte Niveau 2:');
define( '_CFG_TAB1_OPT3DESC', 'Dans jours. C&acute;est le premier seuil pour informer l&acute;utilisateur que son inscription est sur le point d&acute;expirer.');
define( '_CFG_TAB1_OPT4NAME', 'Alerte Niveau 1:');
define( '_CFG_TAB1_OPT4DESC', 'Dans jours. C&acute;est le seuil final pour informer l&acute;utilisateur que son inscription est sur le point d&acute;expirer. Cela doit &ecirc;tre l&acute;intervalle le plus pr&egrave;s de l&acute;expiration.');
define( '_CFG_TAB1_OPT5NAME', 'Entry Plan:');
define( '_CFG_TAB1_OPT5DESC', 'Every user will be subscribed to this plan (no payment!) when the user has no subscription yet');
define( '_CFG_TAB1_OPT9NAME', 'Require Subscription:');
define( '_CFG_TAB1_OPT9DESC', 'When enabled, a user MUST have a subscription. If disabled, users will be able to log in without one.');

define( '_CFG_TAB1_OPT10NAME', 'Description de la Passerelle:');
define( '_CFG_TAB1_OPT10DESC', 'Liste des Passerelles que vous d&eacute;sirez expliquer sur les pages interdites (page que les utilisateurs voient lorsqu&acute;ils essaient d&acute;acc&eacute;der une page qui n&acute;est pas autoris&eacute;e par leur mode de plan).');
define( '_CFG_TAB1_OPT20NAME', 'Activated Gateways:');
define( '_CFG_TAB1_OPT20DESC', 'Select the gateways you want to be activated (use the CTRL key to select more than one). After saving, the settings tabs for these gateways will show up. Deactivating a gateway will not erase its settings.');
define( '_CFG_TAB1_OPT11NAME', 'Require Subscription:');
define( '_CFG_TAB1_OPT11DESC', 'By default, a user who has no subscription set up with the AEC will be able to log in just fine. With this setting, you can make subscription a requirement.');
define( '_CFG_ENTRYPLAN_NAME', 'Plan de D&eacute;part par d&eacute;faut');
define( '_CFG_ENTRYPLAN_DESC', 'Plan d&acute;essai gratuit par d&eacute;faut..');

define( '_CFG_TAB1_OPT15NAME', 'D&eacute;sactiver Int&eacute;gration:');
define( '_CFG_TAB1_OPT15DESC', 'Entrer un nom ou une liste de noms (s&eacute;par&eacute;e par un espace) de l&acute;int&eacute;gration que vous voulez d&eacute;sactiver. Actuellement sont support&eacute;s: <strong>CB,CBE,CBM,JACL,SMF,UE,UHP2,VM</strong>. Cela peut &ecirc;tre utile quand vous avez d&eacute;sinstall&eacute; CB mais n&acute;avez pas supprim&eacute; les tables de la BD (dans le cas o&ugrave; AEC pourrait encore reconna&icirc;tre ce qui a d&eacute;j&agrave; &eacute;t&eacute; install&eacute;).');
define( '_CFG_TAB1_OPT16NAME', 'URLs simples:');
define( '_CFG_TAB1_OPT16DESC', 'D&eacute;sactiver l&acute;utilisation des routines SEF de Joomla/Mambo pour les Urls. Avec certaines installations utiliser cette option va donner un message d&acute;erreur 404 &agrave; cause d&acute;une mauvaise r&eacute;&eacute;criture. Essayer cette option si vous rencontrez des probl&egrave;mes avec les redirections.');
define( '_CFG_TAB1_OPT17NAME', 'Coussin d&acute;Expiration:');
define( '_CFG_TAB1_OPT17DESC', 'Entrer une valeur de coussin pour d&eacute;terminer l&acute;expiration. Utiliser une valeur assez grande, car les paiements arrivent plus tard que l&acute;expiration (avec Paypal environ 6-8 heures plus tard).');
define( '_CFG_TAB1_OPT18NAME', 'Heartbeat Cycle:');
define( '_CFG_TAB1_OPT18DESC', 'Number of hours that the AEC waits until understanding a login as a trigger for sending out Emails or doing other actions that you chose to be performed periodically.');
define( '_CFG_TAB1_OPT21NAME', 'Plans First:');
define( '_CFG_TAB1_OPT21DESC', 'If you have commited all three hacks to have an integrated Registration with direct Subscription, this switch will activate this behavior. Don\'t use it if you don\'t want that behavior or only commited the first hack (which means that the plans come after the user has put in his or her details) .');

define( '_CFG_TAB_CUSTOMIZATION_TITLE', 'Customize');
define( '_CFG_TAB1_OPT12NAME', 'Custom intro page link:');
define( '_CFG_TAB1_OPT12DESC', 'Provide a full link (including http://) that leads to your custom intro page. That page has to contain a link like this: http://www.yourdomain.com/index.php?option=com_acctexp&task=subscribe&intro=1 which bypasses the intro and correctly forwards the user to the payment plans or registration details page. Leave this field blank if you don\'t want this at all.');
define( '_CFG_TAB1_OPT13NAME', 'Custom thanks page link:');
define( '_CFG_TAB1_OPT13DESC', 'Provide a full link (including http://) that leads to your custom thanks page. Leave this field blank if you don\'t want this at all.');
define( '_CFG_TAB1_OPT14NAME', 'Custom cancel page link:');
define( '_CFG_TAB1_OPT14DESC', 'Provide a full link (including http://) that leads to your custom cancel page. Leave this field blank if you don\'t want this at all.');
define( '_CFG_TAB1_OPT19NAME', 'Terms of Service:');
define( '_CFG_TAB1_OPT19DESC', 'Enter a URL to your Terms of Service. The user will have to select a checkbox when confirming his account. If left blank, nothing will show up.');
define( '_CFG_GENERAL_CUSTOMNOTALLOWED_NAME', 'Custom NotAllowed link:');
define( '_CFG_GENERAL_CUSTOMNOTALLOWED_DESC', 'Provide a full link (including http://) that leads to your custom NotAllowed page. Leave this field blank if you don\'t want this at all.');

define( '_CFG_GENERAL_DISPLAY_DATE_FRONTEND_NAME', 'Frontend Date Format');
define( '_CFG_GENERAL_DISPLAY_DATE_FRONTEND_DESC', 'Specify the way a date is displayed on the frontend. Refer to <a href="http://www.php.net/manual/en/function.strftime.php">the php manual</a> for the correct syntax.');
define( '_CFG_GENERAL_DISPLAY_DATE_BACKEND_NAME', 'Backend Date Format');
define( '_CFG_GENERAL_DISPLAY_DATE_BACKEND_DESC', 'Specify the way a date is displayed on the backend. Refer to <a href="http://www.php.net/manual/en/function.strftime.php">the php manual</a> for the correct syntax.');
define( '_CFG_GENERAL_CUSTOMTEXT_PLANS_NAME', 'Custom Text Plans Page');
define( '_CFG_GENERAL_CUSTOMTEXT_PLANS_DESC', 'Text that will be displayed on the Plans Page');
define( '_CFG_GENERAL_CUSTOMTEXT_CONFIRM_NAME', 'Custom Text Confirm Page');
define( '_CFG_GENERAL_CUSTOMTEXT_CONFIRM_DESC', 'Text that will be displayed on the Confirmation Page');
define( '_CFG_GENERAL_CUSTOMTEXT_CHECKOUT_NAME', 'Custom Text Checkout Page');
define( '_CFG_GENERAL_CUSTOMTEXT_CHECKOUT_DESC', 'Text that will be displayed on the Checkout Page');
define( '_CFG_GENERAL_CUSTOMTEXT_NOTALLOWED_NAME', 'Custom Text NotAllowed Page');
define( '_CFG_GENERAL_CUSTOMTEXT_NOTALLOWED_DESC', 'Text that will be displayed on the NotAllowed Page');
define( '_CFG_GENERAL_CUSTOMTEXT_PENDING_NAME', 'Custom Text Pending Page');
define( '_CFG_GENERAL_CUSTOMTEXT_PENDING_DESC', 'Text that will be displayed on the Pending Page');
define( '_CFG_GENERAL_CUSTOMTEXT_EXPIRED_NAME', 'Custom Text Expired Page');
define( '_CFG_GENERAL_CUSTOMTEXT_EXPIRED_DESC', 'Text that will be displayed on the Expired Page');

define( '_CFG_GENERAL_CUSTOMTEXT_CONFIRM_KEEPORIGINAL_NAME', 'Keep Original Text');
define( '_CFG_GENERAL_CUSTOMTEXT_CONFIRM_KEEPORIGINAL_DESC', 'Select this option if you want to keep the original text on the Confirmation Page');
define( '_CFG_GENERAL_CUSTOMTEXT_CHECKOUT_KEEPORIGINAL_NAME', 'Keep Original Text');
define( '_CFG_GENERAL_CUSTOMTEXT_CHECKOUT_KEEPORIGINAL_DESC', 'Select this option if you want to keep the original text on the Checkout Page');
define( '_CFG_GENERAL_CUSTOMTEXT_NOTALLOWED_KEEPORIGINAL_NAME', 'Keep Original Text');
define( '_CFG_GENERAL_CUSTOMTEXT_NOTALLOWED_KEEPORIGINAL_DESC', 'Select this option if you want to keep the original text on the NotAllowed Page');
define( '_CFG_GENERAL_CUSTOMTEXT_PENDING_KEEPORIGINAL_NAME', 'Keep Original Text');
define( '_CFG_GENERAL_CUSTOMTEXT_PENDING_KEEPORIGINAL_DESC', 'Select this option if you want to keep the original text on the Pending Page');
define( '_CFG_GENERAL_CUSTOMTEXT_EXPIRED_KEEPORIGINAL_NAME', 'Keep Original Text');
define( '_CFG_GENERAL_CUSTOMTEXT_EXPIRED_KEEPORIGINAL_DESC', 'Select this option if you want to keep the original text on the Expired Page');

define( '_CFG_GENERAL_HEARTBEAT_CYCLE_BACKEND_NAME', 'Heartbeat Cycle Backend:');
define( '_CFG_GENERAL_HEARTBEAT_CYCLE_BACKEND_DESC', 'Number of hours that the AEC waits until understanding a backend access as heartbeat trigger.');
define( '_CFG_GENERAL_ENABLE_COUPONS_NAME', 'Enable Coupons:');
define( '_CFG_GENERAL_ENABLE_COUPONS_DESC', 'Enable the usage of coupons for your subscriptions.');
define( '_CFG_GENERAL_DISPLAYCCINFO_NAME', 'Enable CC Display:');
define( '_CFG_GENERAL_DISPLAYCCINFO_DESC', 'Enable the display of CreditCard icons for each payment processor.');
define( '_CFG_GENERAL_ADMINACCESS_NAME', 'Administrator Access:');
define( '_CFG_GENERAL_ADMINACCESS_DESC', 'Grant Access to the AEC not only to Super Administrators, but regular Administrators as well.');
define( '_CFG_GENERAL_NOEMAILS_NAME', 'No Emails');
define( '_CFG_GENERAL_NOEMAILS_DESC', 'Set this to prevent AEC System Emails (to the user in events of invoices paid or alike) from being sent out. This does not affect emails being sent from MicroIntegrations.');
define( '_CFG_GENERAL_NOJOOMLAREGEMAILS_NAME', 'No Joomla Emails');
define( '_CFG_GENERAL_NOJOOMLAREGEMAILS_DESC', 'Set this to prevent Joomla Registration Confirmation emails from being sent out.');
define( '_CFG_GENERAL_DEBUGMODE_NAME', 'Debug Mode');
define( '_CFG_GENERAL_DEBUGMODE_DESC', 'Activates the display of debug information.');
define( '_CFG_GENERAL_OVERRIDE_REQSSL_NAME', 'Override SSL Requirement');
define( '_CFG_GENERAL_OVERRIDE_REQSSL_DESC', 'Some payment processors require an SSL secured connection to the user - for example when sensitive information (like CreditCard data) is required on the frontend.');

// Global Micro Integration Settings
define( '_CFG_TAB_MICROINTEGRATION_TITLE', 'MicroIntegr');
define( '_CFG_MI_ACTIVELIST_NAME', 'Active MicroIntegrations');
define( '_CFG_MI_ACTIVELIST_DESC', 'Select which MicroIntegrations you want to use');
define( '_CFG_MI_META_NAME', 'MI Meta Transmit');
define( '_CFG_MI_META_DESC', 'Allow MicroIntegrations to share an internal array of variables to communicate');

//Invoice settings
define( '_CFG_GENERAL_SENDINVOICE_NAME', 'Send an invoice email');
define( '_CFG_GENERAL_SENDINVOICE_DESC', 'Send and invoice/purchase order email (for tax reasons)');
define( '_CFG_GENERAL_INVOICETMPL_NAME', 'Invoice Template');
define( '_CFG_GENERAL_INVOICETMPL_DESC', 'Template for invoices/purchase orders');

// --== PAYMENT PLAN PAGE ==--
// Additions of variables for free trial periods by Michael Spredemann (scubaguy)

define( '_PAYPLAN_PERUNIT1', 'Days');
define( '_PAYPLAN_PERUNIT2', 'Weeks');
define( '_PAYPLAN_PERUNIT3', 'Months');
define( '_PAYPLAN_PERUNIT4', 'Years');

// General Params

define( '_PAYPLAN_DETAIL_TITLE', 'Plan');
define( '_PAYPLAN_GENERAL_NAME_NAME', 'Name:');
define( '_PAYPLAN_GENERAL_NAME_DESC', 'Name or title for this plan. Max.: 40 characters.');
define( '_PAYPLAN_GENERAL_DESC_NAME', 'Description:');
define( '_PAYPLAN_GENERAL_DESC_DESC', 'Full description of plan as you want to be presented to user. Max.: 255 characters.');
define( '_PAYPLAN_GENERAL_ACTIVE_NAME', 'Published:');
define( '_PAYPLAN_GENERAL_ACTIVE_DESC', 'A published plan will be available to the user on frontend.');
define( '_PAYPLAN_GENERAL_VISIBLE_NAME', 'Visible:');
define( '_PAYPLAN_GENERAL_VISIBLE_DESC', 'Visible Plans will show on the frontend. Invisible plans will not show and thus only be available to automatic application (like Fallbacks or Entry Plans).');

define( '_PAYPLAN_PARAMS_GID_ENABLED_NAME', 'Enable usergroup');
define( '_PAYPLAN_PARAMS_GID_ENABLED_DESC', 'Switch this to Yes if you want users to be assigned the selected usergroup.');
define( '_PAYPLAN_PARAMS_GID_NAME', 'Add User to Group:');
define( '_PAYPLAN_PARAMS_GID_DESC', 'Users will be associated to this usergroup when the plan is applied.');
define( '_PAYPLAN_PARAMS_MAKE_ACTIVE_NAME', 'Make Active:');
define( '_PAYPLAN_PARAMS_MAKE_ACTIVE_DESC', 'Set this to >No< if you want to manually activate a user after he or she has paid.');

define( '_PAYPLAN_TEXT_TITLE', 'Plan Text');
define( '_PAYPLAN_GENERAL_EMAIL_DESC_NAME', 'Email Description:');
define( '_PAYPLAN_GENERAL_EMAIL_DESC_DESC', 'Text that should be added into the email that the user receives when a plan is activated for him');
define( '_PAYPLAN_GENERAL_FALLBACK_NAME', 'Plan Fallback:');
define( '_PAYPLAN_GENERAL_FALLBACK_DESC', 'When a user subscription expires - make them a member of the following plan');

define( '_PAYPLAN_GENERAL_PROCESSORS_NAME', 'Payment Gateways:');
define( '_PAYPLAN_NOPLAN', 'No Plan');
define( '_PAYPLAN_NOGW', 'No Gateway');
define( '_PAYPLAN_GENERAL_PROCESSORS_DESC', 'Select the payment gateways you want to be available to this plan. Hold Control or Shift key to select multiple options. Selecting ' . _PAYPLAN_NOPLAN . ' all other selected options will be ignored. If you see only ' . _PAYPLAN_NOPLAN . ' here this means you have no payment processor enabled on your config settings.');
define( '_PAYPLAN_PARAMS_LIFETIME_NAME', 'Lifetime:');
define( '_PAYPLAN_PARAMS_LIFETIME_DESC', 'Make this a lifetime subscription which never expires.');

define( '_PAYPLAN_AMOUNT_NOTICE', 'Notice on Periods');
define( '_PAYPLAN_AMOUNT_NOTICE_TEXT', 'For Paypal Subscriptions, there is a limit on the maximum amount of that you can enter for the period. If you want to use Paypal Subscriptions, <strong>please limit days to 90, weeks to 52, months to 24 and years to 5 at maximum</strong>.');
define( '_PAYPLAN_AMOUNT_EDITABLE_NOTICE', 'There is one or more users using recurring payments for this plan, so it would be wise not to change the terms until these are cancelled.');

define( '_PAYPLAN_REGULAR_TITLE', 'Normal Subscription');
define( '_PAYPLAN_PARAMS_FULL_FREE_NAME', 'Free:');
define( '_PAYPLAN_PARAMS_FULL_FREE_DESC', 'Set this to yes if you want to offer this plan for free');
define( '_PAYPLAN_PARAMS_FULL_AMOUNT_NAME', 'Regular Rate:');
define( '_PAYPLAN_PARAMS_FULL_AMOUNT_DESC', 'The price of the subscription. If there are subscribers to this plan this field can not be changed. If you want to replace this plan, unpublish it and create a new one.');
define( '_PAYPLAN_PARAMS_FULL_PERIOD_NAME', 'Period:');
define( '_PAYPLAN_PARAMS_FULL_PERIOD_DESC', 'This is the length of the billing cycle. The number is modified by the regular billing cycle unit (below).  If there are subscribers to this plan this field can not be changed. If you want to replace this plan, unpublish it and create a new one.');
define( '_PAYPLAN_PARAMS_FULL_PERIODUNIT_NAME', 'Period Unit:');
define( '_PAYPLAN_PARAMS_FULL_PERIODUNIT_DESC', 'This is the units of the regular billing cycle (above). If there are subscribers to this plan this field can not be changed. If you want to replace this plan, unpublish it and create a new one.');

define( '_PAYPLAN_TRIAL_TITLE', 'Trial Period');
define( '_PAYPLAN_TRIAL', '(Optional)');
define( '_PAYPLAN_TRIAL_DESC', 'Skip this section if you do not want to offer Trial periods with your subscriptions. <strong>Trials only work automatically with PayPal Subscriptions!</strong>');
define( '_PAYPLAN_PARAMS_TRIAL_FREE_NAME', 'Free:');
define( '_PAYPLAN_PARAMS_TRIAL_FREE_DESC', 'Set this to yes if you want to offer this trial for free');
define( '_PAYPLAN_PARAMS_TRIAL_AMOUNT_NAME', 'Trial Rate:');
define( '_PAYPLAN_PARAMS_TRIAL_AMOUNT_DESC', 'Enter the amount to immediately bill the subscriber.');
define( '_PAYPLAN_PARAMS_TRIAL_PERIOD_NAME', 'Trial Period:');
define( '_PAYPLAN_PARAMS_TRIAL_PERIOD_DESC', 'This is the length of the trial period. The number is modified by the relugar billing cycle unit (below).  If there are subscribers to this plan this field can not be changed. If you want to replace this plan, unpublish it and create a new one.');
define( '_PAYPLAN_PARAMS_TRIAL_PERIODUNIT_NAME', 'Trial Period Unit:');
define( '_PAYPLAN_PARAMS_TRIAL_PERIODUNIT_DESC', 'This is the units of the trial period (above). If there are subscribers to this plan this field can not be changed. If you want to replace this plan, unpublish it and create a new one.');

// Payplan Relations

define( '_PAYPLAN_RELATIONS_TITLE', 'Relations');
define( '_PAYPLAN_PARAMS_SIMILARPLANS_NAME', 'Similar Plans:');
define( '_PAYPLAN_PARAMS_SIMILARPLANS_DESC', 'Select which plans are similar to this one. A user is not allowed to use a Trial period when buying a plan that he or she has purchased before and this will also be the same for similar plans.');
define( '_PAYPLAN_PARAMS_EQUALPLANS_NAME', 'Equal Plans:');
define( '_PAYPLAN_PARAMS_EQUALPLANS_DESC', 'Select which plans are equal to this one. A user switching between equal plans will have his or her period extended instead of reset. Trials are also not permitted (see similar plans info).');

// Payplan Restrictions

define( '_PAYPLAN_RESTRICTIONS_TITLE', 'Restrictions');
define( '_PAYPLAN_RESTRICTIONS_MINGID_ENABLED_NAME', 'Enable Min GID:');
define( '_PAYPLAN_RESTRICTIONS_MINGID_ENABLED_DESC', 'Enable this setting if you want to restrict whether a user is shown this plan by a minimum usergroup.');
define( '_PAYPLAN_RESTRICTIONS_MINGID_NAME', 'Visibility Group:');
define( '_PAYPLAN_RESTRICTIONS_MINGID_DESC', 'The minimum user level required to see this plan when building the payment plans page. New users will always see the plans with the lowest gid available.');
define( '_PAYPLAN_RESTRICTIONS_FIXGID_ENABLED_NAME', 'Enable Fixed GID:');
define( '_PAYPLAN_RESTRICTIONS_FIXGID_ENABLED_DESC', 'Enable this setting if you want to restrict this plan to one usergroup.');
define( '_PAYPLAN_RESTRICTIONS_FIXGID_NAME', 'Set Group:');
define( '_PAYPLAN_RESTRICTIONS_FIXGID_DESC', 'Only users with this usergroup can view this plan.');
define( '_PAYPLAN_RESTRICTIONS_MAXGID_ENABLED_NAME', 'Enable Max GID:');
define( '_PAYPLAN_RESTRICTIONS_MAXGID_ENABLED_DESC', 'Enable this setting if you want to restrict whether a user is shown this plan by a maximum usergroup.');
define( '_PAYPLAN_RESTRICTIONS_MAXGID_NAME', 'Maximum Group:');
define( '_PAYPLAN_RESTRICTIONS_MAXGID_DESC', 'The maximum user level a user can have to see this plan when building the payment plans page.');

define( '_PAYPLAN_RESTRICTIONS_PREVIOUSPLAN_REQ_ENABLED_NAME', 'Required Prev. Plan:');
define( '_PAYPLAN_RESTRICTIONS_PREVIOUSPLAN_REQ_ENABLED_DESC', 'Enable checking for previous payment plan');
define( '_PAYPLAN_RESTRICTIONS_PREVIOUSPLAN_REQ_NAME', 'Plan:');
define( '_PAYPLAN_RESTRICTIONS_PREVIOUSPLAN_REQ_DESC', 'A user will only see this plan if he or she used the selected plan before the one currently in use');
define( '_PAYPLAN_RESTRICTIONS_CURRENTPLAN_REQ_ENABLED_NAME', 'Required Curr. Plan:');
define( '_PAYPLAN_RESTRICTIONS_CURRENTPLAN_REQ_ENABLED_DESC', 'Enable checking for currently present payment plan');
define( '_PAYPLAN_RESTRICTIONS_CURRENTPLAN_REQ_NAME', 'Plan:');
define( '_PAYPLAN_RESTRICTIONS_CURRENTPLAN_REQ_DESC', 'A user will only see this plan if he or she is currently assigned to, or has expired from the plan selected here');
define( '_PAYPLAN_RESTRICTIONS_OVERALLPLAN_REQ_ENABLED_NAME', 'Required Used Plan:');
define( '_PAYPLAN_RESTRICTIONS_OVERALLPLAN_REQ_ENABLED_DESC', 'Enable checking for overall used payment plan');
define( '_PAYPLAN_RESTRICTIONS_OVERALLPLAN_REQ_NAME', 'Plan:');
define( '_PAYPLAN_RESTRICTIONS_OVERALLPLAN_REQ_DESC', 'A user will only see this plan if he or she has used the selected plan once, no matter when');

define( '_PAYPLAN_RESTRICTIONS_USED_PLAN_MIN_ENABLED_NAME', 'Min Used Plan:');
define( '_PAYPLAN_RESTRICTIONS_USED_PLAN_MIN_ENABLED_DESC', 'Enable checking for the minimum number of times your consumers have subscribed to a specified payment plan in order to see THIS plan');
define( '_PAYPLAN_RESTRICTIONS_USED_PLAN_MIN_AMOUNT_NAME', 'Used Amount:');
define( '_PAYPLAN_RESTRICTIONS_USED_PLAN_MIN_AMOUNT_DESC', 'The minimum amount a user has to have used the selected plan');
define( '_PAYPLAN_RESTRICTIONS_USED_PLAN_MIN_NAME', 'Plan:');
define( '_PAYPLAN_RESTRICTIONS_USED_PLAN_MIN_DESC', 'The payment plan that the user has to have used the specified number of times at least');
define( '_PAYPLAN_RESTRICTIONS_USED_PLAN_MAX_ENABLED_NAME', 'Max Used Plan:');
define( '_PAYPLAN_RESTRICTIONS_USED_PLAN_MAX_ENABLED_DESC', 'Enable checking for the maximum number of times your consumers have subscribed to a specified payment plan in order to see THIS plan');
define( '_PAYPLAN_RESTRICTIONS_USED_PLAN_MAX_AMOUNT_NAME', 'Used Amount:');
define( '_PAYPLAN_RESTRICTIONS_USED_PLAN_MAX_AMOUNT_DESC', 'The maximum amount a user can have used the selected plan');
define( '_PAYPLAN_RESTRICTIONS_USED_PLAN_MAX_NAME', 'Plan:');
define( '_PAYPLAN_RESTRICTIONS_USED_PLAN_MAX_DESC', 'The payment plan that the user has to have used the specified number of times at most');
define( '_PAYPLAN_RESTRICTIONS_CUSTOM_RESTRICTIONS_NAME', 'Custom Restrictions:');
define( '_PAYPLAN_RESTRICTIONS_CUSTOM_RESTRICTIONS_DESC', 'Use RewriteEngine fields to check for specific strings in this form:<br />[[user_id]] >= 1500<br />[[parametername]] = value<br />(Create separate rules by entering a new line).<br />You can use =, <=, >=, <, >, <> as comparing elements. You MUST use spaces between parameters, values and comparators!');

define( '_PAYPLAN_PROCESSORS_TITLE', 'Processors');
define( '_PAYPLAN_PROCESSORS_TITLE_LONG', 'Payment Processors');

define( '_PAYPLAN_PROCESSORS_ACTIVATE_NAME', 'Active');
define( '_PAYPLAN_PROCESSORS_ACTIVATE_DESC', 'Offer this Payment Processor for this Payment Plan.');
define( '_PAYPLAN_PROCESSORS_OVERWRITE_SETTINGS_NAME', 'Overwrite Global Settings');
define( '_PAYPLAN_PROCESSORS_OVERWRITE_SETTINGS_DESC', 'If you want to, you can check this box and after saving the plan edit every setting from the global configuration to be different for this plan individually.');

define( '_PAYPLAN_MI', 'Micro Integr.');
define( '_PAYPLAN_GENERAL_MICRO_INTEGRATIONS_NAME', 'Micro Integrations:');
define( '_PAYPLAN_GENERAL_MICRO_INTEGRATIONS_DESC', 'Select the Micro Integrations that you want to apply to the user with the plan.');

define( '_PAYPLAN_CURRENCY', 'Devises');

define( '_CURRENCY_AFA', 'Afghani');
define( '_CURRENCY_ALL', 'Lek');
define( '_CURRENCY_DZD', 'Algerian Dinar');
define( '_CURRENCY_ADP', 'Andorran Peseta');
define( '_CURRENCY_AON', 'New Kwanza');
define( '_CURRENCY_ARS', 'Argentine Peso');
define( '_CURRENCY_AMD', 'Armenian Dram');
define( '_CURRENCY_AWG', 'Aruban Guilder');
define( '_CURRENCY_AUD', 'Australian Dollar');
define( '_CURRENCY_AZM', 'Azerbaijanian Manat ');
define( '_CURRENCY_EUR', 'Euro');
define( '_CURRENCY_BSD', 'Bahamian Dollar');
define( '_CURRENCY_BHD', 'Bahraini Dinar');
define( '_CURRENCY_BDT', 'Taka');
define( '_CURRENCY_BBD', 'Barbados Dollar');
define( '_CURRENCY_BYB', 'Belarussian Ruble');
define( '_CURRENCY_BEF', 'Belgian Franc');
define( '_CURRENCY_BZD', 'Belize Dollar');
define( '_CURRENCY_BMD', 'Bermudian Dollar');
define( '_CURRENCY_BOB', 'Boliviano');
define( '_CURRENCY_BAD', 'Bosnian Dinar');
define( '_CURRENCY_BWP', 'Pula');
define( '_CURRENCY_BRL', 'Real');
define( '_CURRENCY_BND', 'Brunei Dollar');
define( '_CURRENCY_BGL', 'Lev');
define( '_CURRENCY_BGN', 'Lev');
define( '_CURRENCY_XOF', 'CFA Franc BCEAO');
define( '_CURRENCY_BIF', 'Burundi Franc');
define( '_CURRENCY_KHR', 'Cambodia Riel');
define( '_CURRENCY_XAF', 'CFA Franc BEAC');
define( '_CURRENCY_CAD', 'Dollar Canadien');
define( '_CURRENCY_CVE', 'Cape Verde Escudo');
define( '_CURRENCY_KYD', 'Cayman Islands Dollar');
define( '_CURRENCY_CLP', 'Chilean Peso');
define( '_CURRENCY_CNY', 'Yuan Renminbi');
define( '_CURRENCY_COP', 'Colombian Peso');
define( '_CURRENCY_KMF', 'Comoro Franc');
define( '_CURRENCY_BAM', 'Convertible Marks');
define( '_CURRENCY_CRC', 'Costa Rican Colon');
define( '_CURRENCY_HRK', 'Croatian Kuna');
define( '_CURRENCY_CUP', 'Cuban Peso');
define( '_CURRENCY_CYP', 'Cyprus Pound');
define( '_CURRENCY_CZK', 'Czech Koruna');
define( '_CURRENCY_DKK', 'Danish Krone');
define( '_CURRENCY_DEM', 'Deutsche Mark');
define( '_CURRENCY_DJF', 'Djibouti Franc');
define( '_CURRENCY_XCD', 'East Caribbean Dollar');
define( '_CURRENCY_DOP', 'Dominican Peso');
define( '_CURRENCY_GRD', 'Drachma');
define( '_CURRENCY_TPE', 'Timor Escudo');
define( '_CURRENCY_ECS', 'Ecuador Sucre');
define( '_CURRENCY_EGP', 'Egyptian Pound');
define( '_CURRENCY_SVC', 'El Salvador Colon');
define( '_CURRENCY_EEK', 'Kroon');
define( '_CURRENCY_ETB', 'Ethiopian Birr');
define( '_CURRENCY_FKP', 'Falkland Islands Pound');
define( '_CURRENCY_FJD', 'Fiji Dollar');
define( '_CURRENCY_XPF', 'CFP Franc');
define( '_CURRENCY_FRF', 'Franc');
define( '_CURRENCY_CDF', 'Franc Congolais');
define( '_CURRENCY_GMD', 'Dalasi');
define( '_CURRENCY_GHC', 'Cedi');
define( '_CURRENCY_GIP', 'Gibraltar Pound');
define( '_CURRENCY_GTQ', 'Quetzal');
define( '_CURRENCY_GNF', 'Guinea Franc');
define( '_CURRENCY_GWP', 'Guinea - Bissau Peso');
define( '_CURRENCY_GYD', 'Guyana Dollar');
define( '_CURRENCY_HTG', 'Gourde');
define( '_CURRENCY_XAU', 'Gold');
define( '_CURRENCY_HNL', 'Lempira');
define( '_CURRENCY_HKD', 'Hong Kong Dollar');
define( '_CURRENCY_HUF', 'Forint');
define( '_CURRENCY_ISK', 'Iceland Krona');
define( '_CURRENCY_INR', 'Indian Rupee');
define( '_CURRENCY_IDR', 'Rupiah');
define( '_CURRENCY_IRR', 'Iranian Rial');
define( '_CURRENCY_IQD', 'Iraqi Dinar');
define( '_CURRENCY_IEP', 'Irish Pound');
define( '_CURRENCY_ITL', 'Italian Lira');
define( '_CURRENCY_ILS', 'Shekel');
define( '_CURRENCY_JMD', 'Jamaican Dollar');
define( '_CURRENCY_JPY', 'Japan Yen');
define( '_CURRENCY_JOD', 'Jordanian Dinar');
define( '_CURRENCY_KZT', 'Tenge');
define( '_CURRENCY_KES', 'Kenyan Shilling');
define( '_CURRENCY_KRW', 'Won');
define( '_CURRENCY_KPW', 'North Korean Won');
define( '_CURRENCY_KWD', 'Kuwaiti Dinar');
define( '_CURRENCY_KGS', 'Som');
define( '_CURRENCY_LAK', 'Kip');
define( '_CURRENCY_GEL', 'Lari');
define( '_CURRENCY_LVL', 'Latvian Lats');
define( '_CURRENCY_LBP', 'Lebanese Pound');
define( '_CURRENCY_LSL', 'Loti');
define( '_CURRENCY_LRD', 'Liberian Dollar');
define( '_CURRENCY_LYD', 'Libyan Dinar');
define( '_CURRENCY_LTL', 'Lithuanian Litas');
define( '_CURRENCY_LUF', 'Luxembourg Franc');
define( '_CURRENCY_AOR', 'Kwanza Reajustado');
define( '_CURRENCY_MOP', 'Pataca');
define( '_CURRENCY_MKD', 'Denar');
define( '_CURRENCY_MGF', 'Malagasy Franc');
define( '_CURRENCY_MWK', 'Kwacha');
define( '_CURRENCY_MYR', 'Malaysian Ringitt');
define( '_CURRENCY_MVR', 'Rufiyaa');
define( '_CURRENCY_MTL', 'Maltese Lira');
define( '_CURRENCY_MRO', 'Ouguiya');
define( '_CURRENCY_TMM', 'Manat');
define( '_CURRENCY_FIM', 'Markka');
define( '_CURRENCY_MUR', 'Mauritius Rupee');
define( '_CURRENCY_MXN', 'Mexico Peso');
define( '_CURRENCY_MXV', 'Mexican Unidad de Inversion');
define( '_CURRENCY_MNT', 'Mongolia Tugrik');
define( '_CURRENCY_MAD', 'Moroccan Dirham');
define( '_CURRENCY_MDL', 'Moldovan Leu');
define( '_CURRENCY_MZM', 'Metical');
define( '_CURRENCY_BOV', 'Mvdol');
define( '_CURRENCY_MMK', 'Myanmar Kyat');
define( '_CURRENCY_ERN', 'Nakfa');
define( '_CURRENCY_NAD', 'Namibian Dollar');
define( '_CURRENCY_NPR', 'Nepalese Rupee');
define( '_CURRENCY_ANG', 'Netherlands Antilles Guilder');
define( '_CURRENCY_NLG', 'Netherlands Guilder');
define( '_CURRENCY_NZD', 'New Zealand Dollar');
define( '_CURRENCY_NIO', 'Cordoba Oro');
define( '_CURRENCY_NGN', 'Naira');
define( '_CURRENCY_BTN', 'Ngultrum');
define( '_CURRENCY_NOK', 'Norwegian Krone');
define( '_CURRENCY_OMR', 'Rial Omani');
define( '_CURRENCY_PKR', 'Pakistan Rupee');
define( '_CURRENCY_PAB', 'Balboa');
define( '_CURRENCY_PGK', 'New Guinea Kina');
define( '_CURRENCY_PYG', 'Guarani');
define( '_CURRENCY_PEN', 'Nuevo Sol');
define( '_CURRENCY_XPD', 'Palladium');
define( '_CURRENCY_PHP', 'Philippine Peso');
define( '_CURRENCY_XPT', 'Platinum');
define( '_CURRENCY_PTE', 'Portuguese Escudo');
define( '_CURRENCY_PLN', 'New Zloty');
define( '_CURRENCY_QAR', 'Qatari Rial');
define( '_CURRENCY_ROL', 'Romanian Leu');
define( '_CURRENCY_RON', 'New Romanian Leu');
define( '_CURRENCY_RUB', 'Russian Ruble');
define( '_CURRENCY_RWF', 'Rwanda Franc');
define( '_CURRENCY_WST', 'Tala');
define( '_CURRENCY_STD', 'Dobra');
define( '_CURRENCY_SAR', 'Saudi Riyal');
define( '_CURRENCY_SCR', 'Seychelles Rupee');
define( '_CURRENCY_SLL', 'Leone');
define( '_CURRENCY_SGD', 'Singapore Dollar');
define( '_CURRENCY_SKK', 'Slovak Koruna');
define( '_CURRENCY_SIT', 'Tolar');
define( '_CURRENCY_SBD', 'Solomon Islands Dollar');
define( '_CURRENCY_SOS', 'Somalia Shilling');
define( '_CURRENCY_ZAL', 'Rand (Financial)');
define( '_CURRENCY_ZAR', 'Rand (South Africa)');
define( '_CURRENCY_RUR', 'Russian Ruble');
define( '_CURRENCY_ATS', 'Schilling');
define( '_CURRENCY_XAG', 'Silver');
define( '_CURRENCY_ESP', 'Spanish Peseta');
define( '_CURRENCY_LKR', 'Sri Lanka Rupee');
define( '_CURRENCY_SHP', 'St Helena Pound');
define( '_CURRENCY_SDP', 'Sudanese Pound');
define( '_CURRENCY_SDD', 'Sudanese Dinar');
define( '_CURRENCY_SRG', 'Suriname Guilder');
define( '_CURRENCY_SZL', 'Swaziland Lilangeni');
define( '_CURRENCY_SEK', 'Sweden Krona');
define( '_CURRENCY_CHF', 'Swiss Franc');
define( '_CURRENCY_SYP', 'Syrian Pound');
define( '_CURRENCY_TWD', 'New Taiwan Dollar');
define( '_CURRENCY_TJR', 'Tajik Ruble');
define( '_CURRENCY_TZS', 'Tanzanian Shilling');
define( '_CURRENCY_TRL', 'Turkish Lira');
define( '_CURRENCY_THB', 'Baht');
define( '_CURRENCY_TOP', 'Tonga Pa\'anga');
define( '_CURRENCY_TTD', 'Trinidad &amp; Tobago Dollar');
define( '_CURRENCY_TND', 'Tunisian Dinar');
define( '_CURRENCY_TRY', 'Turkish Lira');
define( '_CURRENCY_UGX', 'Uganda Shilling');
define( '_CURRENCY_UAH', 'Ukrainian Hryvnia');
define( '_CURRENCY_ECV', 'Unidad de Valor Constante');
define( '_CURRENCY_CLF', 'Unidades de fomento');
define( '_CURRENCY_AED', 'United Arab Emirates Dirham');
define( '_CURRENCY_GBP', 'Pounds Sterling');
define( '_CURRENCY_USD', 'US Dollar');
define( '_CURRENCY_UYU', 'Uruguayan Peso');
define( '_CURRENCY_UZS', 'Uzbekistan Sum');
define( '_CURRENCY_VUV', 'Vanuatu Vatu');
define( '_CURRENCY_VEB', 'Venezuela Bolivar');
define( '_CURRENCY_VND', 'Viet Nam Dong');
define( '_CURRENCY_YER', 'Yemeni Rial');
define( '_CURRENCY_YUM', 'Yugoslavian New Dinar');
define( '_CURRENCY_ZRN', 'New Zaire');
define( '_CURRENCY_ZMK', 'Zambian Kwacha');
define( '_CURRENCY_ZWD', 'Zimbabwe Dollar');
define( '_CURRENCY_USN', 'US Dollar (Next day)');
define( '_CURRENCY_USS', 'US Dollar (Same day)');

// --== MICRO INTEGRATION OVERVIEW ==--
define( '_MI_TITLE', 'Micro Integrations');
define( '_MI_NAME', 'Name');
define( '_MI_DESC', 'Description');
define( '_MI_ACTIVE', 'Active');
define( '_MI_REORDER', 'Order');
define( '_MI_FUNCTION', 'Function Name');

// --== MICRO INTEGRATION EDIT ==--
define( '_MI_E_TITLE', 'MI');
define( '_MI_E_TITLE_LONG', 'Micro Integration');
define( '_MI_E_SETTINGS', 'Settings');
define( '_MI_E_NAME_NAME', 'Name');
define( '_MI_E_NAME_DESC', 'Choose a name for this Micro Integration');
define( '_MI_E_DESC_NAME', 'Description');
define( '_MI_E_DESC_DESC', 'Briefly Describe the Integration');
define( '_MI_E_ACTIVE_NAME', 'Active');
define( '_MI_E_ACTIVE_DESC', 'Set the Integration to active');
define( '_MI_E_ACTIVE_AUTO_NAME', 'Expiration Action');
define( '_MI_E_ACTIVE_AUTO_DESC', 'If the function allows this, you can enable expiration features: actions that have to be carried out when a user expires (if supported by the MI).');
define( '_MI_E_ACTIVE_USERUPDATE_NAME', 'User Account Update Action');
define( '_MI_E_ACTIVE_USERUPDATE_DESC', 'If the function allows this, you can enable actions that happen when a user account is being changed (if supported by the MI).');
define( '_MI_E_PRE_EXP_NAME', 'Pre Expiration');
define( '_MI_E_PRE_EXP_DESC', 'Set the amount of days before expiration when a pre-expiration action should be triggered (if supported by the MI).');
define( '_MI_E_FUNCTION_NAME', 'Function Name');
define( '_MI_E_FUNCTION_DESC', 'Please choose which of these integrations should be used');
define( '_MI_E_FUNCTION_EXPLANATION', 'Before you can setup the Micro Integration, you have to select which of the integration files we should use for this. Make a selection and save the Micro Integration. When you edit it again, you will be able to set the parameters. Note also, that the function name cannot be changed once its set.');

// --== REWRITE EXPLANATION ==--
define( '_REWRITE_AREA_USER', 'User Account Related');
define( '_REWRITE_KEY_USER_ID', 'User Account ID');
define( '_REWRITE_KEY_USER_USERNAME', 'Username');
define( '_REWRITE_KEY_USER_NAME', 'Name');
define( '_REWRITE_KEY_USER_EMAIL', 'E-Mail Address');
define( '_REWRITE_KEY_USER_ACTIVATIONCODE', 'Activation Code');
define( '_REWRITE_KEY_USER_ACTIVATIONLINK', 'Activation Link');

define( '_REWRITE_AREA_EXPIRATION', 'User Expiration Related');
define( '_REWRITE_KEY_EXPIRATION_DATE', 'Expiration Date');

define( '_REWRITE_AREA_SUBSCRIPTION', 'User Subscription Related');
define( '_REWRITE_KEY_SUBSCRIPTION_TYPE', 'Payment Processor');
define( '_REWRITE_KEY_SUBSCRIPTION_STATUS', 'Subscription Status');
define( '_REWRITE_KEY_SUBSCRIPTION_SIGNUP_DATE', 'Date this Subscription was established');
define( '_REWRITE_KEY_SUBSCRIPTION_LASTPAY_DATE', 'Last Payment Date');
define( '_REWRITE_KEY_SUBSCRIPTION_PLAN', 'Payment Plan ID');
define( '_REWRITE_KEY_SUBSCRIPTION_PREVIOUS_PLAN', 'Previous Payment Plan ID');
define( '_REWRITE_KEY_SUBSCRIPTION_RECURRING', 'Recurring Payment Flag');
define( '_REWRITE_KEY_SUBSCRIPTION_LIFETIME', 'Lifetime Subscription Flag');

define( '_REWRITE_AREA_PLAN', 'Payment Plan Related');
define( '_REWRITE_KEY_PLAN_NAME', 'Name');
define( '_REWRITE_KEY_PLAN_DESC', 'Description');

define( '_REWRITE_AREA_CMS', 'CMS Related');
define( '_REWRITE_KEY_CMS_ABSOLUTE_PATH', 'Absolute path to cms directory');
define( '_REWRITE_KEY_CMS_LIVE_SITE', 'Your Site URL');

define( '_REWRITE_AREA_INVOICE', 'Invoice Related');
define( '_REWRITE_KEY_INVOICE_ID', 'Invoice ID');
define( '_REWRITE_KEY_INVOICE_NUMBER', 'Invoice Number');
define( '_REWRITE_KEY_INVOICE_CREATED_DATE', 'Date of Creation');
define( '_REWRITE_KEY_INVOICE_TRANSACTION_DATE', 'Date of Transaction');
define( '_REWRITE_KEY_INVOICE_METHOD', 'Payment Method');
define( '_REWRITE_KEY_INVOICE_AMOUNT', 'Amount Paid');
define( '_REWRITE_KEY_INVOICE_CURRENCY', 'Currency');
define( '_REWRITE_KEY_INVOICE_COUPONS', 'List of Coupons');

// --== COUPONS OVERVIEW ==--
define( '_COUPON_TITLE', 'Coupons');
define( '_COUPON_TITLE', 'Static Coupons');
define( '_COUPON_NAME', 'Name');
define( '_COUPON_DESC', 'Description (first 50 chars)');
define( '_COUPON_ACTIVE', 'Published');
define( '_COUPON_REORDER', 'Reorder');
define( '_COUPON_USECOUNT', 'Use Count');

// --== FACTURATION ==--
define( '_INVOICE_TITLE', 'Factures');
define( '_INVOICE_SEARCH', 'Rechercher');
define( '_INVOICE_USERID', 'Nom Utilisateur');
define( '_INVOICE_INVOICE_NUMBER', 'Num&eacute;ro de Facture');
define( '_INVOICE_TRANSACTION_DATE', 'Date de Transaction');
define( '_INVOICE_METHOD', 'M&eacute;thode de Facturation');
define( '_INVOICE_AMOUNT', 'Montant de la Facture');
define( '_INVOICE_CURRENCY', 'Devise de la Facture');

// --== HISTORIQUE DES PAIEMENTS ==--
define( '_HISTORY_TITLE2', 'Votre Historique de Facturation');
define( '_HISTORY_SEARCH', 'Rechercher');
define( '_HISTORY_USERID', 'Nom Utilisateur');
define( '_HISTORY_INVOICE_NUMBER', 'Num&eacute;ro de Facture');
define( '_HISTORY_PLAN_NAME', 'Plan abonn&eacute; &agrave;');
define( '_HISTORY_TRANSACTION_DATE', 'Date de Transaction');
define( '_INVOICE_CREATED_DATE', 'Created Date');
define( '_HISTORY_METHOD', 'M&eacute;thode de Facturation');
define( '_HISTORY_AMOUNT', 'Montant de la Facture');
define( '_HISTORY_RESPONSE', 'R&eacute;ponse du serveur');

// --== PAGE POUR TOUS LES UTILISATEURS ==--
define( '_METHOD', 'M&eacute;thode');

// --== PAGE EN ATTENTE ==--
define( '_PEND_DATE', 'En attente depuis');
define( '_PEND_TITLE', 'Abonnements en attente');
define( '_PEND_DESC', 'Abonnement qui n a pas compl&eacute;t&eacute; le processus. Ce statut est commun le temps que le syst&egrave;me attend le paiement.');
define( '_ACTIVATE', 'Activ&eacute;');
define( '_ACTIVATED', 'Utilisateur activ&eacute;.');
?>