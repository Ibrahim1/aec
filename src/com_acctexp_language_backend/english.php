<?php
/**
* @version $Id: english.php 16 2007-06-25 09:04:04Z mic $
* @package AEC - Account Control Expiration - Subscription component for Joomla! OS CMS
* @subpackage Language - Backend - English
* @copyright Copyright (C) 2004-2007 Helder Garcia, David Deutsch
* @author Team AEC - http://www.gobalnerd.org
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
define( '_AEC_HACKS_MI1',						'Some Micro Integrations rely on receiving a cleartext password for each user. This hack will make sure that the Micro Integrations will be notified when a user changes his/her account.' );
define( '_AEC_HACKS_MI2',						'Some Micro Integrations rely on receiving a cleartext password for each user. This hack will make sure that the Micro Integrations will be notified when a user registers an account.' );
define( '_AEC_HACKS_MI3',						'Some Micro Integrations rely on receiving a cleartext password for each user. This hack will make sure that the Micro Integrations will be notified when an admin changes a user-account.' );

// log
	// settings
define( '_AEC_LOG_SH_SETT_SAVED',				'settings change' );
define( '_AEC_LOG_LO_SETT_SAVED',				'The Settings for AEC have been saved, changes:' );
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
define( '_AEC_INST_APPLY_HACKS',				'In order to commit these hacks right now, go to the <a href="' .  $mosConfig_live_site . '/administrator/index2.php?option=com_acctexp&amp;task=hacks" target="_blank">hacks page</a>. (You can also access this page later on from the AEC central view or menu)' );
define( '_AEC_INST_NOTE_UPGRADE',				'<strong>If you are upgrading, make sure to check the hacks page anyways, since there are changes from time to time!!!</strong>' );
define( '_AEC_INST_NOTE_HELP',					'To help you along with frequently encountered problems, we have created a <a href="' . $mosConfig_live_site . '/administrator/index2.php?option=com_acctexp&amp;task=help" target="_blank"><strong>help function</strong></a> that will help you ship around the most common setup problems (access is also avaliable from the AEC central.' );
define( '_AEC_INST_HINTS',						'Hints' );
define( '_AEC_INST_HINT1',						'We encourage you to visit the <a href="http://www.globalnerd.org/index.php?option=com_wrapper&amp;Itemid=53" target="_blank">globalnerd.org forums</a> and to <strong>participate in the discussion there</strong>. Chances are, that other users have found the same bugs and it is equally likely that there is at least a fix to hack in or even a new version.' );
define( '_AEC_INST_HINT2',						'In any case (and especially if you use this on a live site): go through your settings and make a test subscription to see whether everything is working to your satisfaction! Although we try our best to make upgrading as flawless as possible, some fundamental changes to our program may not be possible to cushion for all users.' );
define( '_AEC_INST_ATTENTION',					'No need to use the old logins!' );
define( '_AEC_INST_ATTENTION1',					'If you still have the old AEC login modules installed, please uninstall it (no matter which one, regular or CB) and use the normal joomla or CB login module. There is no need to use these old modules anymore.' );

// header
define( '_AEC_HEAD_PLAN_INFO',					'Plan Info' );

// help
define( '_AEC_CMN_HELP',						'Help' );
define( '_AEC_HELP_DESC',						'On this page, the AEC takes a look at its own configuration and tells you whenever it finds errors that need to be adressed.' );
define( '_AEC_HELP_GREEN',						'Green</strong> items indicate trivial problems or notifications, or problems that have already been solved.' );
define( '_AEC_HELP_YELLOW',						'Yellow</strong> items are mostly of cosmetical importance (like additions to the user frontend), but also issues that are most likely a deliberate choice of the administrator.' );
define( '_AEC_HELP_RED',						'Red</strong> items are of high importance to either the way the AEC works or the security of your System.' );
define( '_AEC_HELP_GEN',						'Please note that even though we try to cover as many errors and problems as possible, this page can only point at the most obvious ones and is by far not completed yet (beta&trade;)' );
define( '_AEC_HELP_QS_HEADER',					'AEC Quickstart Manual' );
define( '_AEC_HELP_QS_DESC',					'Before doing anything about the below issues, please read our <a href="' . $mosConfig_live_site . '"/administrator/components/com_acctexp/manual/AEC_Quickstart.pdf" target="_blank">Quickstart Manual</a>' );
define( '_AEC_HELP_SER_SW_DIAG1',				'File Permissions Problems' );
define( '_AEC_HELP_SER_SW_DIAG1_DESC',			'AEC has detected that you are using an Apache Webserver - To be able to hack files on such a server, those files have to be owned by the webserver user, which apparently is not so for at least one of the neccessary files.' );
define( '_AEC_HELP_SER_SW_DIAG1_DESC2',			'We recommend that you temporarily change the file permissions to 777, then commit the hacks and change it back after that. <strong>Contact your server host or administrator for the possibly quickest response when experiencing problems.</strong> This is the same for the file permission related suggestion(s) below.' );
define( '_AEC_HELP_SER_SW_DIAG2',				'joomla.php/mambo.php File Permissions' );
define( '_AEC_HELP_SER_SW_DIAG2_DESC',			'The AEC has detected that your joomla.php is not owned by the webserver.' );
define( '_AEC_HELP_SER_SW_DIAG2_DESC2',			'Access your webserver via ssh and go to the directory \"<yoursiteroot>/includes\". There, type in this command: \"chown wwwrun joomla.php\" (or \"chown wwwrun mambo.php\" in case you are using mambo).' );
define( '_AEC_HELP_SER_SW_DIAG3',				'Legacy Hacks Detected!' );
define( '_AEC_HELP_SER_SW_DIAG3_DESC',			'You appear to have old Hacks commited to your System.", "In Order for the AEC to function correctly, please review the Hacks page and follow the steps presented there.' );
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

// micro integration
	// general
define( '_AEC_MI_REWRITING_INFO',				'Rewriting Info' );
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
define( '_AEC_CENTR_SPECIAL',					'Hacks' );
define( '_AEC_CENTR_M_INTEGRATION',				'Micro Integr.' );
define( '_AEC_CENTR_HELP',						'Help' );
define( '_AEC_CENTR_LOG',						'EventLog' );

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
define( '_AEC_FOOT_CREDIT',						'Please read our <a href="' . $mosConfig_live_site . '/administrator/index2.php?option=com_acctexp&amp;task=credits">full credits' );
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

// END 0.12.4 (mic) #################################

// --== BACKEND TOOLBAR ==--
DEFINE ('_EXPIRE_SET','Set Expiration:');
DEFINE ('_EXPIRE_NOW','Expire');
DEFINE ('_EXPIRE_EXCLUDE','Exclude');
DEFINE ('_EXPIRE_INCLUDE','Include');
DEFINE ('_EXPIRE_CLOSE','Close');
DEFINE ('_EXPIRE_01MONTH','set 1 Month');
DEFINE ('_EXPIRE_03MONTH','set 3 Months');
DEFINE ('_EXPIRE_12MONTH','set 12 Months');
DEFINE ('_EXPIRE_ADD01MONTH','add 1 Month');
DEFINE ('_EXPIRE_ADD03MONTH','add 3 Months');
DEFINE ('_EXPIRE_ADD12MONTH','add 12 Months');
DEFINE ('_CONFIGURE','Configure');
DEFINE ('_REMOVE','Exclude');
DEFINE ('_CNAME','Name');
DEFINE ('_USERLOGIN','Login');
DEFINE ('_EXPIRATION','Expiration');
DEFINE ('_USERS','Users');
DEFINE ('_DISPLAY','Display');
DEFINE ('_NOTSET','Excluded');
DEFINE ('_SAVE','Save');
DEFINE ('_CANCEL','Cancel');
DEFINE ('_EXP_ASC','Expiration Asc');
DEFINE ('_EXP_DESC','Expiration Desc');
DEFINE ('_NAME_ASC','Name Asc');
DEFINE ('_NAME_DESC','Name Desc');
DEFINE ('_LOGIN_ASC','Login Asc');
DEFINE ('_LOGIN_DESC','Login Desc');
DEFINE ('_SIGNUP_ASC','Signup Date Asc');
DEFINE ('_SIGNUP_DESC','Signup Date Desc');
DEFINE ('_LASTPAY_ASC','Last Payment Asc');
DEFINE ('_LASTPAY_DESC','Last Payment Desc');
DEFINE ('_PLAN_ASC','Plan Asc');
DEFINE ('_PLAN_DESC','Plan Desc');
DEFINE ('_STATUS_ASC','Status Asc');
DEFINE ('_STATUS_DESC','Status Desc');
DEFINE ('_TYPE_ASC','Payment Type Asc');
DEFINE ('_TYPE_DESC','Payment Type Desc');
DEFINE ('_ORDER_BY','Order by:');
DEFINE ('_SAVED', 'Saved.');
DEFINE ('_CANCELED', 'Canceled.');
DEFINE ('_CONFIGURED', 'Item configured.');
DEFINE ('_REMOVED', 'Item removed from list.');
DEFINE ('_EOT_TITLE', 'Closed Subscriptions');
DEFINE ('_EOT_DESC', 'This list does not include manually set subscriptions, only Gateway processed. When you delete an entry, the user is removed from the database and all related activity is erased from the history.');
DEFINE ('_EOT_DATE', 'End of Term Date');
DEFINE ('_EOT_CAUSE', 'Cause');
DEFINE ('_EOT_CAUSE_FAIL', 'Payment failure');
DEFINE ('_EOT_CAUSE_BUYER', 'Cancelled by user');
DEFINE ('_EOT_CAUSE_FORCED', 'Cancelled by administrator');
DEFINE ('_REMOVE_CLOSED', 'Delete');
DEFINE ('_FORCE_CLOSE', 'Close Now');
DEFINE ('_PUBLISH_PAYPLAN', 'Publish');
DEFINE ('_UNPUBLISH_PAYPLAN', 'Unpublish');
DEFINE ('_NEW_PAYPLAN', 'New');
DEFINE ('_EDIT_PAYPLAN', 'Edit');
DEFINE ('_REMOVE_PAYPLAN', 'Delete');
DEFINE ('_SAVE_PAYPLAN', 'Save');
DEFINE ('_CANCEL_PAYPLAN', 'Cancel');
DEFINE ('_PAYPLANS_TITLE', 'Payment Plans Manager');
DEFINE ('_PAYPLANS_MAINDESC', 'Published plans will be options for the user on frontend. These plans are only valid for Gateway payments.');
DEFINE ('_PAYPLAN_NAME', 'Name');
DEFINE ('_PAYPLAN_DESC', 'Description (first 50 chars)');
DEFINE ('_PAYPLAN_ACTIVE', 'Published');
DEFINE ('_PAYPLAN_VISIBLE', 'Visible');
DEFINE ('_PAYPLAN_A3', 'Rate');
DEFINE ('_PAYPLAN_P3', 'Period');
DEFINE ('_PAYPLAN_T3', 'Period Unit');
DEFINE ('_PAYPLAN_USERCOUNT', 'Subscribers');
DEFINE ('_PAYPLAN_EXPIREDCOUNT', 'Expired');
DEFINE ('_PAYPLAN_TOTALCOUNT', 'Total');
DEFINE ('_PAYPLAN_REORDER', 'Reorder');
DEFINE ('_PAYPLANS_HEADER', 'Payment Plans');
DEFINE ('_PAYPLAN_DETAIL', 'Settings');
DEFINE ('_ALTERNATIVE_PAYMENT', 'Bank Transfer');
DEFINE ('_SUBSCR_DATE', 'Sign Up Date');
DEFINE ('_ACTIVE_TITLE', 'Active Subscriptions');
DEFINE ('_ACTIVE_DESC', 'This list does not include manually set subscriptions, only Gateway processed.');
DEFINE ('_LASTPAY_DATE', 'Last Payment Date');
DEFINE ('_USERPLAN', 'Plan');
DEFINE ('_CANCELLED_TITLE', 'Cancelled Subscriptions');
DEFINE ('_CANCELLED_DESC', 'This list does not include manually set subscriptions, only Gateway processed. These are the subscriptions cancelled by users but that do not reach the end of terms.');
DEFINE ('_CANCEL_DATE', 'Cancel Date');
DEFINE ('_MANUAL_DESC', 'When you delete an entry, the user is completely removed from databases.');
DEFINE ('_REPEND_ACTIVE', 'Re-Pend');
DEFINE ('_FILTER_PLAN', '- Select Plan -');
DEFINE ('_BIND_USER', 'Assign To:');
DEFINE ('_PLAN_FILTER','Filter by Plan:');
DEFINE ('_CENTRAL_PAGE','Central');

// --== USER FORM ==--
DEFINE ('_HISTORY_COL1_TITLE', 'Invoice');
DEFINE ('_HISTORY_COL2_TITLE', 'Amount');
DEFINE ('_HISTORY_COL3_TITLE', 'Payment Date');
DEFINE ('_HISTORY_COL4_TITLE', 'Method');
DEFINE ('_HISTORY_COL5_TITLE', 'Action');
DEFINE ('_HISTORY_COL6_TITLE', 'Plan');
DEFINE ('_USERINVOICE_ACTION_REPEAT','repeat&nbsp;Link');
DEFINE ('_USERINVOICE_ACTION_CANCEL','cancel');
DEFINE ('_USERINVOICE_ACTION_CLEAR','mark&nbsp;cleared');
DEFINE ('_USERINVOICE_ACTION_CLEAR_APPLY','clear&nbsp;&amp;&nbsp;apply&nbsp;Plan');

// --== BACKEND SETTINGS ==--

// TAB 1 - Global AEC Settings
DEFINE ('_CFG_TAB1_TITLE', 'Global');
DEFINE ('_CFG_TAB1_SUBTITLE', 'General Setup');

DEFINE ('_CFG_TAB1_OPT1NAME', 'Initial Expiration Period:');
DEFINE ('_CFG_TAB1_OPT1DESC', 'Default expiration period, in days, for new registrations. This is relative to the registration date, so if you want new users be expired by default use -1 (minus one) here. This has no effect when user register using automatic payment system (e.g. PayPal Integration).');
DEFINE ('_CFG_TAB1_OPT3NAME', 'Alert Level 2:');
DEFINE ('_CFG_TAB1_OPT3DESC', 'In days. This is the first threshold to start warning user that his subscription is about to expire. <strong>This does not send out an email!</strong>');
DEFINE ('_CFG_TAB1_OPT4NAME', 'Alert Level 1:');
DEFINE ('_CFG_TAB1_OPT4DESC', 'In days. This is the final threshold to alert user that his subscription is about to expire. This should be the closest interval to expiration. <strong>This does not send out an email!</strong>');
DEFINE ('_CFG_TAB1_OPT5NAME', 'Entry Plan:');
DEFINE ('_CFG_TAB1_OPT5DESC', 'Every user will be subscribed to this plan (no payment!) when the user has no subscription yet');
DEFINE ('_CFG_TAB1_OPT9NAME', 'Require Subscription:');
DEFINE ('_CFG_TAB1_OPT9DESC', 'When enabled, a user MUST have a subscription. If disabled, users will be able to log in without one.');

DEFINE ('_CFG_TAB1_OPT10NAME', 'Gateway Descriptions:');
DEFINE ('_CFG_TAB1_OPT10DESC', 'List the Gateways you want to explain on the NotAllowed page (which your customers see when trying to access a page that they are not allowed to by their payment plan).');
DEFINE ('_CFG_TAB1_OPT20NAME', 'Activated Gateways:');
DEFINE ('_CFG_TAB1_OPT20DESC', 'Select the gateways you want to be activated (use the CTRL key to select more than one). After saving, the settings tabs for these gateways will show up. Deactivating a gateway will not erase its settings.');
DEFINE ('_CFG_TAB1_OPT11NAME', 'Require Subscription:');
DEFINE ('_CFG_TAB1_OPT11DESC', 'By default, a user who has no subscription set up with the AEC will be able to log in just fine. With this setting, you can make subscription a requirement.');
DEFINE ('_CFG_ENTRYPLAN_NAME', 'Default Entry Plan');
DEFINE ('_CFG_ENTRYPLAN_DESC', 'Free trial default plan.');
DEFINE ('_CFG_TAB1_OPT15NAME', 'Disable Integration:');
DEFINE ('_CFG_TAB1_OPT15DESC', 'Provide one name or a list of names (seperated by a whitespace) of integrations that you want to have disabled. Currently supporting the strings: <strong>CB,CBE,CBM,JACL,SMF,UE,UHP2,VM</strong>. This can be helpful when you have uninstalled CB but not deleted its tables (in which case the AEC would still recognize it as being installed).');
DEFINE ('_CFG_TAB1_OPT16NAME', 'Simple URLs:');
DEFINE ('_CFG_TAB1_OPT16DESC', 'Disable the use of Joomla/Mambo SEF Routines for Urls. With some setups using these will result in 404 Errors due to wrong rewriting. Try this option if you encounter any problems with redirects.');
DEFINE ('_CFG_TAB1_OPT17NAME', 'Expiration Cushion:');
DEFINE ('_CFG_TAB1_OPT17DESC', 'Number of hours that the AEC takes as cushion when determining expiration. Take a generous amount since payments arive later than the actual expiration (with Paypal about 6-8 hours later).');
DEFINE ('_CFG_TAB1_OPT18NAME', 'Heartbeat Cycle:');
DEFINE ('_CFG_TAB1_OPT18DESC', 'Number of hours that the AEC waits until understanding a login as a trigger for sending out Emails or doing other actions that you chose to be performed periodically.');
DEFINE ('_CFG_TAB1_OPT21NAME', 'Plans First:');
DEFINE ('_CFG_TAB1_OPT21DESC', 'If you have commited all three hacks to have an integrated Registration with direct Subscription, this switch will activate this behavior. Don\'t use it if you don\'t want that behavior or only commited the first hack (which means that the plans come after the user has put in his or her details) .');

DEFINE ('_CFG_TAB_CUSTOMIZATION_TITLE', 'Customize');
DEFINE ('_CFG_TAB1_OPT12NAME', 'Custom intro page link:');
DEFINE ('_CFG_TAB1_OPT12DESC', 'Provide a full link (including http://) that leads to your custom intro page. That page has to contain a link like this: http://www.yourdomain.com/index.php?option=com_acctexp&task=subscribe&intro=1 which bypasses the intro and correctly forwards the user to the payment plans or registration details page. Leave this field blank if you don\'t want this at all.');
DEFINE ('_CFG_TAB1_OPT13NAME', 'Custom thanks page link:');
DEFINE ('_CFG_TAB1_OPT13DESC', 'Provide a full link (including http://) that leads to your custom thanks page. Leave this field blank if you don\'t want this at all.');
DEFINE ('_CFG_TAB1_OPT14NAME', 'Custom cancel page link:');
DEFINE ('_CFG_TAB1_OPT14DESC', 'Provide a full link (including http://) that leads to your custom cancel page. Leave this field blank if you don\'t want this at all.');
DEFINE ('_CFG_TAB1_OPT19NAME', 'Terms of Service:');
DEFINE ('_CFG_TAB1_OPT19DESC', 'Enter a URL to your Terms of Service. The user will have to select a checkbox when confirming his account. If left blank, nothing will show up.');
DEFINE ('_CFG_GENERAL_CUSTOMNOTALLOWED_NAME', 'Custom NotAllowed link:');
DEFINE ('_CFG_GENERAL_CUSTOMNOTALLOWED_DESC', 'Provide a full link (including http://) that leads to your custom NotAllowed page. Leave this field blank if you don\'t want this at all.');

DEFINE ('_CFG_GENERAL_DISPLAY_DATE_FRONTEND_NAME', 'Frontend Date Format');
DEFINE ('_CFG_GENERAL_DISPLAY_DATE_FRONTEND_DESC', 'Specify the way a date is displayed on the frontend. Refer to <a href="http://www.php.net/manual/en/function.strftime.php">the php manual</a> for the correct syntax.');
DEFINE ('_CFG_GENERAL_DISPLAY_DATE_BACKEND_NAME', 'Backend Date Format');
DEFINE ('_CFG_GENERAL_DISPLAY_DATE_BACKEND_DESC', 'Specify the way a date is displayed on the backend. Refer to <a href="http://www.php.net/manual/en/function.strftime.php">the php manual</a> for the correct syntax.');
DEFINE ('_CFG_GENERAL_CUSTOMTEXT_PLANS_NAME', 'Custom Text Plans Page');
DEFINE ('_CFG_GENERAL_CUSTOMTEXT_PLANS_DESC', 'Text that will be displayed on the Plans Page');
DEFINE ('_CFG_GENERAL_CUSTOMTEXT_CONFIRM_NAME', 'Custom Text Confirm Page');
DEFINE ('_CFG_GENERAL_CUSTOMTEXT_CONFIRM_DESC', 'Text that will be displayed on the Confirmation Page');
DEFINE ('_CFG_GENERAL_CUSTOMTEXT_CHECKOUT_NAME', 'Custom Text Checkout Page');
DEFINE ('_CFG_GENERAL_CUSTOMTEXT_CHECKOUT_DESC', 'Text that will be displayed on the Checkout Page');
DEFINE ('_CFG_GENERAL_CUSTOMTEXT_NOTALLOWED_NAME', 'Custom Text NotAllowed Page');
DEFINE ('_CFG_GENERAL_CUSTOMTEXT_NOTALLOWED_DESC', 'Text that will be displayed on the NotAllowed Page');
DEFINE ('_CFG_GENERAL_CUSTOMTEXT_PENDING_NAME', 'Custom Text Pending Page');
DEFINE ('_CFG_GENERAL_CUSTOMTEXT_PENDING_DESC', 'Text that will be displayed on the Pending Page');
DEFINE ('_CFG_GENERAL_CUSTOMTEXT_EXPIRED_NAME', 'Custom Text Expired Page');
DEFINE ('_CFG_GENERAL_CUSTOMTEXT_EXPIRED_DESC', 'Text that will be displayed on the Expired Page');

DEFINE ('_CFG_GENERAL_CUSTOMTEXT_CONFIRM_KEEPORIGINAL_NAME', 'Keep Original Text');
DEFINE ('_CFG_GENERAL_CUSTOMTEXT_CONFIRM_KEEPORIGINAL_DESC', 'Select this option if you want to keep the original text on the Confirmation Page');
DEFINE ('_CFG_GENERAL_CUSTOMTEXT_CHECKOUT_KEEPORIGINAL_NAME', 'Keep Original Text');
DEFINE ('_CFG_GENERAL_CUSTOMTEXT_CHECKOUT_KEEPORIGINAL_DESC', 'Select this option if you want to keep the original text on the Checkout Page');
DEFINE ('_CFG_GENERAL_CUSTOMTEXT_NOTALLOWED_KEEPORIGINAL_NAME', 'Keep Original Text');
DEFINE ('_CFG_GENERAL_CUSTOMTEXT_NOTALLOWED_KEEPORIGINAL_DESC', 'Select this option if you want to keep the original text on the NotAllowed Page');
DEFINE ('_CFG_GENERAL_CUSTOMTEXT_PENDING_KEEPORIGINAL_NAME', 'Keep Original Text');
DEFINE ('_CFG_GENERAL_CUSTOMTEXT_PENDING_KEEPORIGINAL_DESC', 'Select this option if you want to keep the original text on the Pending Page');
DEFINE ('_CFG_GENERAL_CUSTOMTEXT_EXPIRED_KEEPORIGINAL_NAME', 'Keep Original Text');
DEFINE ('_CFG_GENERAL_CUSTOMTEXT_EXPIRED_KEEPORIGINAL_DESC', 'Select this option if you want to keep the original text on the Expired Page');

DEFINE ('_CFG_GENERAL_HEARTBEAT_CYCLE_BACKEND_NAME', 'Heartbeat Cycle Backend:');
DEFINE ('_CFG_GENERAL_HEARTBEAT_CYCLE_BACKEND_DESC', 'Number of hours that the AEC waits until understanding a backend access as heartbeat trigger.');
DEFINE ('_CFG_GENERAL_ENABLE_COUPONS_NAME', 'Enable Coupons:');
DEFINE ('_CFG_GENERAL_ENABLE_COUPONS_DESC', 'Enable the usage of coupons for your subscriptions.');
DEFINE ('_CFG_GENERAL_DISPLAYCCINFO_NAME', 'Enable CC Display:');
DEFINE ('_CFG_GENERAL_DISPLAYCCINFO_DESC', 'Enable the display of CreditCard icons for each payment processor.');

// Global Micro Integration Settings
DEFINE ('_CFG_TAB_MICROINTEGRATION_TITLE', 'MicroIntegr');
DEFINE ('_CFG_MI_ACTIVELIST_NAME', 'Active MicroIntegrations');
DEFINE ('_CFG_MI_ACTIVELIST_DESC', 'Select which MicroIntegrations you want to use');
DEFINE ('_CFG_MI_META_NAME', 'MI Meta Transmit');
DEFINE ('_CFG_MI_META_DESC', 'Allow MicroIntegrations to share an internal array of variables to communicate');

// --== PAYMENT PLAN PAGE ==--
// Additions of variables for free trial periods by Michael Spredemann (scubaguy)

DEFINE ('_PAYPLAN_PERUNIT1', 'Days');
DEFINE ('_PAYPLAN_PERUNIT2', 'Weeks');
DEFINE ('_PAYPLAN_PERUNIT3', 'Months');
DEFINE ('_PAYPLAN_PERUNIT4', 'Years');

// General Params

DEFINE ('_PAYPLAN_DETAIL_TITLE', 'Plan');
DEFINE ('_PAYPLAN_GENERAL_NAME_NAME', 'Name:');
DEFINE ('_PAYPLAN_GENERAL_NAME_DESC', 'Name or title for this plan. Max.: 40 characters.');
DEFINE ('_PAYPLAN_GENERAL_DESC_NAME', 'Description:');
DEFINE ('_PAYPLAN_GENERAL_DESC_DESC', 'Full description of plan as you want to be presented to user. Max.: 255 characters.');
DEFINE ('_PAYPLAN_GENERAL_ACTIVE_NAME', 'Published:');
DEFINE ('_PAYPLAN_GENERAL_ACTIVE_DESC', 'A published plan will be available to the user on frontend.');
DEFINE ('_PAYPLAN_GENERAL_VISIBLE_NAME', 'Visible:');
DEFINE ('_PAYPLAN_GENERAL_VISIBLE_DESC', 'Visible Plans will show on the frontend. Invisible plans will not show and thus only be available to automatic application (like Fallbacks or Entry Plans).');

DEFINE ('_PAYPLAN_PARAMS_GID_ENABLED_NAME', 'Enable usergroup');
DEFINE ('_PAYPLAN_PARAMS_GID_ENABLED_DESC', 'Switch this to Yes if you want users to be assigned the selected usergroup.');
DEFINE ('_PAYPLAN_PARAMS_GID_NAME', 'Add User to Group:');
DEFINE ('_PAYPLAN_PARAMS_GID_DESC', 'Users will be associated to this usergroup when the plan is applied.');

DEFINE ('_PAYPLAN_TEXT_TITLE', 'Plan Text');
DEFINE ('_PAYPLAN_GENERAL_EMAIL_DESC_NAME', 'Email Description:');
DEFINE ('_PAYPLAN_GENERAL_EMAIL_DESC_DESC', 'Text that should be added into the email that the user receives when a plan is activated for him');
DEFINE ('_PAYPLAN_GENERAL_FALLBACK_NAME', 'Plan Fallback:');
DEFINE ('_PAYPLAN_GENERAL_FALLBACK_DESC', 'When a user subscription expires - make them a member of the following plan');

DEFINE ('_PAYPLAN_GENERAL_PROCESSORS_NAME', 'Payment Gateways:');
DEFINE ('_PAYPLAN_NOPLAN', 'No Plan');
DEFINE ('_PAYPLAN_NOGW', 'No Gateway');
DEFINE ('_PAYPLAN_GENERAL_PROCESSORS_DESC', 'Select the payment gateways you want to be available to this plan. Hold Control or Shift key to select multiple options. Selecting ' . _PAYPLAN_NOPLAN . ' all other selected options will be ignored. If you see only ' . _PAYPLAN_NOPLAN . ' here this means you have no payment processor enabled on your config settings.');
DEFINE ('_PAYPLAN_PARAMS_LIFETIME_NAME', 'Lifetime:');
DEFINE ('_PAYPLAN_PARAMS_LIFETIME_DESC', 'Make this a lifetime subscription which never expires.');

DEFINE ('_PAYPLAN_AMOUNT_NOTICE', 'Notice on Periods');
DEFINE ('_PAYPLAN_AMOUNT_NOTICE_TEXT', 'For Paypal Subscriptions, there is a limit on the maximum amount of that you can enter for the period. If you want to use Paypal Subscriptions, <strong>please limit days to 90, weeks to 52, months to 24 and years to 5 at maximum</strong>.');
DEFINE ('_PAYPLAN_AMOUNT_EDITABLE_NOTICE', 'There is one or more users using recurring payments for this plan, so it would be wise not to change the terms until these are cancelled.');

DEFINE ('_PAYPLAN_REGULAR_TITLE', 'Normal Subscription');
DEFINE ('_PAYPLAN_PARAMS_FULL_FREE_NAME', 'Free:');
DEFINE ('_PAYPLAN_PARAMS_FULL_FREE_DESC', 'Set this to yes if you want to offer this plan for free');
DEFINE ('_PAYPLAN_PARAMS_FULL_AMOUNT_NAME', 'Regular Rate:');
DEFINE ('_PAYPLAN_PARAMS_FULL_AMOUNT_DESC', 'The price of the subscription. If there are subscribers to this plan this field can not be changed. If you want to replace this plan, unpublish it and create a new one.');
DEFINE ('_PAYPLAN_PARAMS_FULL_PERIOD_NAME', 'Period:');
DEFINE ('_PAYPLAN_PARAMS_FULL_PERIOD_DESC', 'This is the length of the billing cycle. The number is modified by the regular billing cycle unit (below).  If there are subscribers to this plan this field can not be changed. If you want to replace this plan, unpublish it and create a new one.');
DEFINE ('_PAYPLAN_PARAMS_FULL_PERIODUNIT_NAME', 'Period Unit:');
DEFINE ('_PAYPLAN_PARAMS_FULL_PERIODUNIT_DESC', 'This is the units of the regular billing cycle (above). If there are subscribers to this plan this field can not be changed. If you want to replace this plan, unpublish it and create a new one.');

DEFINE ('_PAYPLAN_TRIAL_TITLE', 'Trial Period');
DEFINE ('_PAYPLAN_TRIAL', '(Optional)');
DEFINE ('_PAYPLAN_TRIAL_DESC', 'Skip this section if you do not want to offer Trial periods with your subscriptions. <strong>Trials only work automatically with PayPal Subscriptions!</strong>');
DEFINE ('_PAYPLAN_PARAMS_TRIAL_FREE_NAME', 'Free:');
DEFINE ('_PAYPLAN_PARAMS_TRIAL_FREE_DESC', 'Set this to yes if you want to offer this trial for free');
DEFINE ('_PAYPLAN_PARAMS_TRIAL_AMOUNT_NAME', 'Trial Rate:');
DEFINE ('_PAYPLAN_PARAMS_TRIAL_AMOUNT_DESC', 'Enter the amount to immediately bill the subscriber.');
DEFINE ('_PAYPLAN_PARAMS_TRIAL_PERIOD_NAME', 'Trial Period:');
DEFINE ('_PAYPLAN_PARAMS_TRIAL_PERIOD_DESC', 'This is the length of the trial period. The number is modified by the relugar billing cycle unit (below).  If there are subscribers to this plan this field can not be changed. If you want to replace this plan, unpublish it and create a new one.');
DEFINE ('_PAYPLAN_PARAMS_TRIAL_PERIODUNIT_NAME', 'Trial Period Unit:');
DEFINE ('_PAYPLAN_PARAMS_TRIAL_PERIODUNIT_DESC', 'This is the units of the trial period (above). If there are subscribers to this plan this field can not be changed. If you want to replace this plan, unpublish it and create a new one.');

// Payplan Relations

DEFINE ('_PAYPLAN_RELATIONS_TITLE', 'Relations');
DEFINE ('_PAYPLAN_PARAMS_SIMILARPLANS_NAME', 'Similar Plans:');
DEFINE ('_PAYPLAN_PARAMS_SIMILARPLANS_DESC', 'Select which plans are similar to this one. A user is not allowed to use a Trial period when buying a plan that he or she has purchased before and this will also be the same for similar plans.');
DEFINE ('_PAYPLAN_PARAMS_EQUALPLANS_NAME', 'Equal Plans:');
DEFINE ('_PAYPLAN_PARAMS_EQUALPLANS_DESC', 'Select which plans are equal to this one. A user switching between equal plans will have his or her period extended instead of reset. Trials are also not permitted (see similar plans info).');

// Payplan Restrictions

DEFINE ('_PAYPLAN_RESTRICTIONS_TITLE', 'Restrictions');
DEFINE ('_PAYPLAN_RESTRICTIONS_MINGID_ENABLED_NAME', 'Enable Min GID:');
DEFINE ('_PAYPLAN_RESTRICTIONS_MINGID_ENABLED_DESC', 'Enable this setting if you want to restrict whether a user is shown this plan by a minimum usergroup.');
DEFINE ('_PAYPLAN_RESTRICTIONS_MINGID_NAME', 'Visibility Group:');
DEFINE ('_PAYPLAN_RESTRICTIONS_MINGID_DESC', 'The minimum user level required to see this plan when building the payment plans page. New users will always see the plans with the lowest gid available.');
DEFINE ('_PAYPLAN_RESTRICTIONS_FIXGID_ENABLED_NAME', 'Enable Fixed GID:');
DEFINE ('_PAYPLAN_RESTRICTIONS_FIXGID_ENABLED_DESC', 'Enable this setting if you want to restrict this plan to one usergroup.');
DEFINE ('_PAYPLAN_RESTRICTIONS_FIXGID_NAME', 'Set Group:');
DEFINE ('_PAYPLAN_RESTRICTIONS_FIXGID_DESC', 'Only users with this usergroup can view this plan.');
DEFINE ('_PAYPLAN_RESTRICTIONS_MAXGID_ENABLED_NAME', 'Enable Max GID:');
DEFINE ('_PAYPLAN_RESTRICTIONS_MAXGID_ENABLED_DESC', 'Enable this setting if you want to restrict whether a user is shown this plan by a maximum usergroup.');
DEFINE ('_PAYPLAN_RESTRICTIONS_MAXGID_NAME', 'Maximum Group:');
DEFINE ('_PAYPLAN_RESTRICTIONS_MAXGID_DESC', 'The maximum user level a user can have to see this plan when building the payment plans page.');

DEFINE ('_PAYPLAN_RESTRICTIONS_PREVIOUSPLAN_REQ_ENABLED_NAME', 'Required Prev. Plan:');
DEFINE ('_PAYPLAN_RESTRICTIONS_PREVIOUSPLAN_REQ_ENABLED_DESC', 'Enable checking for previous payment plan');
DEFINE ('_PAYPLAN_RESTRICTIONS_PREVIOUSPLAN_REQ_NAME', 'Plan:');
DEFINE ('_PAYPLAN_RESTRICTIONS_PREVIOUSPLAN_REQ_DESC', 'A user will only see this plan if he or she used the selected plan before the one currently in use');
DEFINE ('_PAYPLAN_RESTRICTIONS_CURRENTPLAN_REQ_ENABLED_NAME', 'Required Curr. Plan:');
DEFINE ('_PAYPLAN_RESTRICTIONS_CURRENTPLAN_REQ_ENABLED_DESC', 'Enable checking for currently present payment plan');
DEFINE ('_PAYPLAN_RESTRICTIONS_CURRENTPLAN_REQ_NAME', 'Plan:');
DEFINE ('_PAYPLAN_RESTRICTIONS_CURRENTPLAN_REQ_DESC', 'A user will only see this plan if he or she is currently assigned to, or has expired from the plan selected here');
DEFINE ('_PAYPLAN_RESTRICTIONS_OVERALLPLAN_REQ_ENABLED_NAME', 'Required Used Plan:');
DEFINE ('_PAYPLAN_RESTRICTIONS_OVERALLPLAN_REQ_ENABLED_DESC', 'Enable checking for overall used payment plan');
DEFINE ('_PAYPLAN_RESTRICTIONS_OVERALLPLAN_REQ_NAME', 'Plan:');
DEFINE ('_PAYPLAN_RESTRICTIONS_OVERALLPLAN_REQ_DESC', 'A user will only see this plan if he or she has used the selected plan once, no matter when');

DEFINE ('_PAYPLAN_RESTRICTIONS_USED_PLAN_MIN_ENABLED_NAME', 'Min Used Plan:');
DEFINE ('_PAYPLAN_RESTRICTIONS_USED_PLAN_MIN_ENABLED_DESC', 'Enable checking for the minimum number of times your consumers have subscribed to a specified payment plan in order to see THIS plan');
DEFINE ('_PAYPLAN_RESTRICTIONS_USED_PLAN_MIN_AMOUNT_NAME', 'Used Amount:');
DEFINE ('_PAYPLAN_RESTRICTIONS_USED_PLAN_MIN_AMOUNT_DESC', 'The minimum amount a user has to have used the selected plan');
DEFINE ('_PAYPLAN_RESTRICTIONS_USED_PLAN_MIN_NAME', 'Plan:');
DEFINE ('_PAYPLAN_RESTRICTIONS_USED_PLAN_MIN_DESC', 'The payment plan that the user has to have used the specified number of times at least');
DEFINE ('_PAYPLAN_RESTRICTIONS_USED_PLAN_MAX_ENABLED_NAME', 'Max Used Plan:');
DEFINE ('_PAYPLAN_RESTRICTIONS_USED_PLAN_MAX_ENABLED_DESC', 'Enable checking for the maximum number of times your consumers have subscribed to a specified payment plan in order to see THIS plan');
DEFINE ('_PAYPLAN_RESTRICTIONS_USED_PLAN_MAX_AMOUNT_NAME', 'Used Amount:');
DEFINE ('_PAYPLAN_RESTRICTIONS_USED_PLAN_MAX_AMOUNT_DESC', 'The maximum amount a user can have used the selected plan');
DEFINE ('_PAYPLAN_RESTRICTIONS_USED_PLAN_MAX_NAME', 'Plan:');
DEFINE ('_PAYPLAN_RESTRICTIONS_USED_PLAN_MAX_DESC', 'The payment plan that the user has to have used the specified number of times at most');

DEFINE ('_PAYPLAN_MI', 'Micro Integr.');
DEFINE ('_PAYPLAN_GENERAL_MICRO_INTEGRATIONS_NAME', 'Micro Integrations:');
DEFINE ('_PAYPLAN_GENERAL_MICRO_INTEGRATIONS_DESC', 'Select the Micro Integrations that you want to apply to the user with the plan.');

DEFINE ('_PAYPLAN_CURRENCY', 'Currency');

DEFINE ('_CURRENCY_AFA', 'Afghani');
DEFINE ('_CURRENCY_ALL', 'Lek');
DEFINE ('_CURRENCY_DZD', 'Algerian Dinar');
DEFINE ('_CURRENCY_ADP', 'Andorran Peseta');
DEFINE ('_CURRENCY_AON', 'New Kwanza');
DEFINE ('_CURRENCY_ARS', 'Argentine Peso');
DEFINE ('_CURRENCY_AMD', 'Armenian Dram');
DEFINE ('_CURRENCY_AWG', 'Aruban Guilder');
DEFINE ('_CURRENCY_AUD', 'Australian Dollar');
DEFINE ('_CURRENCY_AZM', 'Azerbaijanian Manat ');
DEFINE ('_CURRENCY_EUR', 'Euro');
DEFINE ('_CURRENCY_BSD', 'Bahamian Dollar');
DEFINE ('_CURRENCY_BHD', 'Bahraini Dinar');
DEFINE ('_CURRENCY_BDT', 'Taka');
DEFINE ('_CURRENCY_BBD', 'Barbados Dollar');
DEFINE ('_CURRENCY_BYB', 'Belarussian Ruble');
DEFINE ('_CURRENCY_BEF', 'Belgian Franc');
DEFINE ('_CURRENCY_BZD', 'Belize Dollar');
DEFINE ('_CURRENCY_BMD', 'Bermudian Dollar');
DEFINE ('_CURRENCY_BOB', 'Boliviano');
DEFINE ('_CURRENCY_BAD', 'Bosnian Dinar');
DEFINE ('_CURRENCY_BWP', 'Pula');
DEFINE ('_CURRENCY_BRL', 'Real');
DEFINE ('_CURRENCY_BND', 'Brunei Dollar');
DEFINE ('_CURRENCY_BGL', 'Lev');
DEFINE ('_CURRENCY_BGN', 'Lev');
DEFINE ('_CURRENCY_XOF', 'CFA Franc BCEAO');
DEFINE ('_CURRENCY_BIF', 'Burundi Franc');
DEFINE ('_CURRENCY_KHR', 'Cambodia Riel');
DEFINE ('_CURRENCY_XAF', 'CFA Franc BEAC');
DEFINE ('_CURRENCY_CAD', 'Canadian Dollar');
DEFINE ('_CURRENCY_CVE', 'Cape Verde Escudo');
DEFINE ('_CURRENCY_KYD', 'Cayman Islands Dollar');
DEFINE ('_CURRENCY_CLP', 'Chilean Peso');
DEFINE ('_CURRENCY_CNY', 'Yuan Renminbi');
DEFINE ('_CURRENCY_COP', 'Colombian Peso');
DEFINE ('_CURRENCY_KMF', 'Comoro Franc');
DEFINE ('_CURRENCY_BAM', 'Convertible Marks');
DEFINE ('_CURRENCY_CRC', 'Costa Rican Colon');
DEFINE ('_CURRENCY_HRK', 'Croatian Kuna');
DEFINE ('_CURRENCY_CUP', 'Cuban Peso');
DEFINE ('_CURRENCY_CYP', 'Cyprus Pound');
DEFINE ('_CURRENCY_CZK', 'Czech Koruna');
DEFINE ('_CURRENCY_DKK', 'Danish Krone');
DEFINE ('_CURRENCY_DEM', 'Deutsche Mark');
DEFINE ('_CURRENCY_DJF', 'Djibouti Franc');
DEFINE ('_CURRENCY_XCD', 'East Caribbean Dollar');
DEFINE ('_CURRENCY_DOP', 'Dominican Peso');
DEFINE ('_CURRENCY_GRD', 'Drachma');
DEFINE ('_CURRENCY_TPE', 'Timor Escudo');
DEFINE ('_CURRENCY_ECS', 'Ecuador Sucre');
DEFINE ('_CURRENCY_EGP', 'Egyptian Pound');
DEFINE ('_CURRENCY_SVC', 'El Salvador Colon');
DEFINE ('_CURRENCY_EEK', 'Kroon');
DEFINE ('_CURRENCY_ETB', 'Ethiopian Birr');
DEFINE ('_CURRENCY_FKP', 'Falkland Islands Pound');
DEFINE ('_CURRENCY_FJD', 'Fiji Dollar');
DEFINE ('_CURRENCY_XPF', 'CFP Franc');
DEFINE ('_CURRENCY_FRF', 'Franc');
DEFINE ('_CURRENCY_CDF', 'Franc Congolais');
DEFINE ('_CURRENCY_GMD', 'Dalasi');
DEFINE ('_CURRENCY_GHC', 'Cedi');
DEFINE ('_CURRENCY_GIP', 'Gibraltar Pound');
DEFINE ('_CURRENCY_GTQ', 'Quetzal');
DEFINE ('_CURRENCY_GNF', 'Guinea Franc');
DEFINE ('_CURRENCY_GWP', 'Guinea - Bissau Peso');
DEFINE ('_CURRENCY_GYD', 'Guyana Dollar');
DEFINE ('_CURRENCY_HTG', 'Gourde');
DEFINE ('_CURRENCY_XAU', 'Gold');
DEFINE ('_CURRENCY_HNL', 'Lempira');
DEFINE ('_CURRENCY_HKD', 'Hong Kong Dollar');
DEFINE ('_CURRENCY_HUF', 'Forint');
DEFINE ('_CURRENCY_ISK', 'Iceland Krona');
DEFINE ('_CURRENCY_INR', 'Indian Rupee');
DEFINE ('_CURRENCY_IDR', 'Rupiah');
DEFINE ('_CURRENCY_IRR', 'Iranian Rial');
DEFINE ('_CURRENCY_IQD', 'Iraqi Dinar');
DEFINE ('_CURRENCY_IEP', 'Irish Pound');
DEFINE ('_CURRENCY_ITL', 'Italian Lira');
DEFINE ('_CURRENCY_ILS', 'Shekel');
DEFINE ('_CURRENCY_JMD', 'Jamaican Dollar');
DEFINE ('_CURRENCY_JPY', 'Japan Yen');
DEFINE ('_CURRENCY_JOD', 'Jordanian Dinar');
DEFINE ('_CURRENCY_KZT', 'Tenge');
DEFINE ('_CURRENCY_KES', 'Kenyan Shilling');
DEFINE ('_CURRENCY_KRW', 'Won');
DEFINE ('_CURRENCY_KPW', 'North Korean Won');
DEFINE ('_CURRENCY_KWD', 'Kuwaiti Dinar');
DEFINE ('_CURRENCY_KGS', 'Som');
DEFINE ('_CURRENCY_LAK', 'Kip');
DEFINE ('_CURRENCY_GEL', 'Lari');
DEFINE ('_CURRENCY_LVL', 'Latvian Lats');
DEFINE ('_CURRENCY_LBP', 'Lebanese Pound');
DEFINE ('_CURRENCY_LSL', 'Loti');
DEFINE ('_CURRENCY_LRD', 'Liberian Dollar');
DEFINE ('_CURRENCY_LYD', 'Libyan Dinar');
DEFINE ('_CURRENCY_LTL', 'Lithuanian Litas');
DEFINE ('_CURRENCY_LUF', 'Luxembourg Franc');
DEFINE ('_CURRENCY_AOR', 'Kwanza Reajustado');
DEFINE ('_CURRENCY_MOP', 'Pataca');
DEFINE ('_CURRENCY_MKD', 'Denar');
DEFINE ('_CURRENCY_MGF', 'Malagasy Franc');
DEFINE ('_CURRENCY_MWK', 'Kwacha');
DEFINE ('_CURRENCY_MYR', 'Malaysian Ringitt');
DEFINE ('_CURRENCY_MVR', 'Rufiyaa');
DEFINE ('_CURRENCY_MTL', 'Maltese Lira');
DEFINE ('_CURRENCY_MRO', 'Ouguiya');
DEFINE ('_CURRENCY_TMM', 'Manat');
DEFINE ('_CURRENCY_FIM', 'Markka');
DEFINE ('_CURRENCY_MUR', 'Mauritius Rupee');
DEFINE ('_CURRENCY_MXN', 'Mexico Peso');
DEFINE ('_CURRENCY_MXV', 'Mexican Unidad de Inversion');
DEFINE ('_CURRENCY_MNT', 'Mongolia Tugrik');
DEFINE ('_CURRENCY_MAD', 'Moroccan Dirham');
DEFINE ('_CURRENCY_MDL', 'Moldovan Leu');
DEFINE ('_CURRENCY_MZM', 'Metical');
DEFINE ('_CURRENCY_BOV', 'Mvdol');
DEFINE ('_CURRENCY_MMK', 'Myanmar Kyat');
DEFINE ('_CURRENCY_ERN', 'Nakfa');
DEFINE ('_CURRENCY_NAD', 'Namibian Dollar');
DEFINE ('_CURRENCY_NPR', 'Nepalese Rupee');
DEFINE ('_CURRENCY_ANG', 'Netherlands Antilles Guilder');
DEFINE ('_CURRENCY_NLG', 'Netherlands Guilder');
DEFINE ('_CURRENCY_NZD', 'New Zealand Dollar');
DEFINE ('_CURRENCY_NIO', 'Cordoba Oro');
DEFINE ('_CURRENCY_NGN', 'Naira');
DEFINE ('_CURRENCY_BTN', 'Ngultrum');
DEFINE ('_CURRENCY_NOK', 'Norwegian Krone');
DEFINE ('_CURRENCY_OMR', 'Rial Omani');
DEFINE ('_CURRENCY_PKR', 'Pakistan Rupee');
DEFINE ('_CURRENCY_PAB', 'Balboa');
DEFINE ('_CURRENCY_PGK', 'New Guinea Kina');
DEFINE ('_CURRENCY_PYG', 'Guarani');
DEFINE ('_CURRENCY_PEN', 'Nuevo Sol');
DEFINE ('_CURRENCY_XPD', 'Palladium');
DEFINE ('_CURRENCY_PHP', 'Philippine Peso');
DEFINE ('_CURRENCY_XPT', 'Platinum');
DEFINE ('_CURRENCY_PTE', 'Portuguese Escudo');
DEFINE ('_CURRENCY_PLN', 'New Zloty');
DEFINE ('_CURRENCY_QAR', 'Qatari Rial');
DEFINE ('_CURRENCY_ROL', 'Leu');
DEFINE ('_CURRENCY_RUB', 'Russian Ruble');
DEFINE ('_CURRENCY_RWF', 'Rwanda Franc');
DEFINE ('_CURRENCY_WST', 'Tala');
DEFINE ('_CURRENCY_STD', 'Dobra');
DEFINE ('_CURRENCY_SAR', 'Saudi Riyal');
DEFINE ('_CURRENCY_SCR', 'Seychelles Rupee');
DEFINE ('_CURRENCY_SLL', 'Leone');
DEFINE ('_CURRENCY_SGD', 'Singapore Dollar');
DEFINE ('_CURRENCY_SKK', 'Slovak Koruna');
DEFINE ('_CURRENCY_SIT', 'Tolar');
DEFINE ('_CURRENCY_SBD', 'Solomon Islands Dollar');
DEFINE ('_CURRENCY_SOS', 'Somalia Shilling');
DEFINE ('_CURRENCY_ZAL', 'Rand (Financial)');
DEFINE ('_CURRENCY_ZAR', 'Rand (South Africa)');
DEFINE ('_CURRENCY_RUR', 'Russian Ruble');
DEFINE ('_CURRENCY_ATS', 'Schilling');
DEFINE ('_CURRENCY_XAG', 'Silver');
DEFINE ('_CURRENCY_ESP', 'Spanish Peseta');
DEFINE ('_CURRENCY_LKR', 'Sri Lanka Rupee');
DEFINE ('_CURRENCY_SHP', 'St Helena Pound');
DEFINE ('_CURRENCY_SDP', 'Sudanese Pound');
DEFINE ('_CURRENCY_SDD', 'Sudanese Dinar');
DEFINE ('_CURRENCY_SRG', 'Suriname Guilder');
DEFINE ('_CURRENCY_SZL', 'Swaziland Lilangeni');
DEFINE ('_CURRENCY_SEK', 'Sweden Krona');
DEFINE ('_CURRENCY_CHF', 'Swiss Franc');
DEFINE ('_CURRENCY_SYP', 'Syrian Pound');
DEFINE ('_CURRENCY_TWD', 'New Taiwan Dollar');
DEFINE ('_CURRENCY_TJR', 'Tajik Ruble');
DEFINE ('_CURRENCY_TZS', 'Tanzanian Shilling');
DEFINE ('_CURRENCY_TRL', 'Turkish Lira');
DEFINE ('_CURRENCY_THB', 'Baht');
DEFINE ('_CURRENCY_TOP', 'Tonga Pa\'anga');
DEFINE ('_CURRENCY_TTD', 'Trinidad &amp; Tobago Dollar');
DEFINE ('_CURRENCY_TND', 'Tunisian Dinar');
DEFINE ('_CURRENCY_TRY', 'Turkish Lira');
DEFINE ('_CURRENCY_UGX', 'Uganda Shilling');
DEFINE ('_CURRENCY_UAH', 'Ukrainian Hryvnia');
DEFINE ('_CURRENCY_ECV', 'Unidad de Valor Constante');
DEFINE ('_CURRENCY_CLF', 'Unidades de fomento');
DEFINE ('_CURRENCY_AED', 'United Arab Emirates Dirham');
DEFINE ('_CURRENCY_GBP', 'Pounds Sterling');
DEFINE ('_CURRENCY_USD', 'US Dollar');
DEFINE ('_CURRENCY_UYU', 'Uruguayan Peso');
DEFINE ('_CURRENCY_UZS', 'Uzbekistan Sum');
DEFINE ('_CURRENCY_VUV', 'Vanuatu Vatu');
DEFINE ('_CURRENCY_VEB', 'Venezuela Bolivar');
DEFINE ('_CURRENCY_VND', 'Viet Nam Dong');
DEFINE ('_CURRENCY_YER', 'Yemeni Rial');
DEFINE ('_CURRENCY_YUM', 'Yugoslavian New Dinar');
DEFINE ('_CURRENCY_ZRN', 'New Zaire');
DEFINE ('_CURRENCY_ZMK', 'Zambian Kwacha');
DEFINE ('_CURRENCY_ZWD', 'Zimbabwe Dollar');
DEFINE ('_CURRENCY_USN', 'US Dollar (Next day)');
DEFINE ('_CURRENCY_USS', 'US Dollar (Same day)');

// --== MICRO INTEGRATION OVERVIEW ==--
DEFINE ('_MI_TITLE', 'Micro Integrations');
DEFINE ('_MI_NAME', 'Name');
DEFINE ('_MI_DESC', 'Description');
DEFINE ('_MI_ACTIVE', 'Active');
DEFINE ('_MI_REORDER', 'Order');
DEFINE ('_MI_FUNCTION', 'Function Name');

// --== MICRO INTEGRATION EDIT ==--
DEFINE ('_MI_E_TITLE', 'MI');
DEFINE ('_MI_E_TITLE_LONG', 'Micro Integration');
DEFINE ('_MI_E_SETTINGS', 'Settings');
DEFINE ('_MI_E_NAME_NAME', 'Name');
DEFINE ('_MI_E_NAME_DESC', 'Choose a name for this Micro Integration');
DEFINE ('_MI_E_DESC_NAME', 'Description');
DEFINE ('_MI_E_DESC_DESC', 'Briefly Describe the Integration');
DEFINE ('_MI_E_ACTIVE_NAME', 'Active');
DEFINE ('_MI_E_ACTIVE_DESC', 'Set the Integration to active');
DEFINE ('_MI_E_ACTIVE_AUTO_NAME', 'Expiration Action');
DEFINE ('_MI_E_ACTIVE_AUTO_DESC', 'If the function allows this, you can enable expiration features: actions that have to be carried out when a user expires (if supported by the MI).');
DEFINE ('_MI_E_ACTIVE_USERUPDATE_NAME', 'User Account Update Action');
DEFINE ('_MI_E_ACTIVE_USERUPDATE_DESC', 'If the function allows this, you can enable actions that happen when a user account is being changed (if supported by the MI).');
DEFINE ('_MI_E_PRE_EXP_NAME', 'Pre Expiration');
DEFINE ('_MI_E_PRE_EXP_DESC', 'Set the amount of days before expiration when a pre-expiration action should be triggered (if supported by the MI).');
DEFINE ('_MI_E_FUNCTION_NAME', 'Function Name');
DEFINE ('_MI_E_FUNCTION_DESC', 'Please choose which of these integrations should be used');
DEFINE ('_MI_E_FUNCTION_EXPLANATION', 'Before you can setup the Micro Integration, you have to select which of the integration files we should use for this. Make a selection and save the Micro Integration. When you edit it again, you will be able to set the parameters. Note also, that the function name cannot be changed once its set.');

// --== REWRITE EXPLANATION ==--
DEFINE ('_REWRITE_AREA_USER', 'User Account Related');
DEFINE ('_REWRITE_KEY_USER_ID', 'User Account ID');
DEFINE ('_REWRITE_KEY_USER_USERNAME', 'Username');
DEFINE ('_REWRITE_KEY_USER_NAME', 'Name');
DEFINE ('_REWRITE_KEY_USER_EMAIL', 'E-Mail Address');

DEFINE ('_REWRITE_AREA_EXPIRATION', 'User Expiration Related');
DEFINE ('_REWRITE_KEY_EXPIRATION_DATE', 'Expiration Date');

DEFINE ('_REWRITE_AREA_SUBSCRIPTION', 'User Subscription Related');
DEFINE ('_REWRITE_KEY_SUBSCRIPTION_TYPE', 'Payment Processor');
DEFINE ('_REWRITE_KEY_SUBSCRIPTION_STATUS', 'Subscription Status');
DEFINE ('_REWRITE_KEY_SUBSCRIPTION_SIGNUP_DATE', 'Date this Subscription was established');
DEFINE ('_REWRITE_KEY_SUBSCRIPTION_LASTPAY_DATE', 'Last Payment Date');
DEFINE ('_REWRITE_KEY_SUBSCRIPTION_PLAN', 'Payment Plan ID');
DEFINE ('_REWRITE_KEY_SUBSCRIPTION_PREVIOUS_PLAN', 'Previous Payment Plan ID');
DEFINE ('_REWRITE_KEY_SUBSCRIPTION_RECURRING', 'Recurring Payment Flag');
DEFINE ('_REWRITE_KEY_SUBSCRIPTION_LIFETIME', 'Lifetime Subscription Flag');

DEFINE ('_REWRITE_AREA_PLAN', 'Payment Plan Related');
DEFINE ('_REWRITE_KEY_PLAN_NAME', 'Name');
DEFINE ('_REWRITE_KEY_PLAN_DESC', 'Description');

DEFINE ('_REWRITE_AREA_CMS', 'CMS Related');
DEFINE ('_REWRITE_KEY_CMS_ABSOLUTE_PATH', 'Absolute path to cms directory');
DEFINE ('_REWRITE_KEY_CMS_LIVE_SITE', 'Your Site URL');

// --== COUPONS OVERVIEW ==--
DEFINE ('_COUPON_TITLE', 'Coupons');
DEFINE ('_COUPON_TITLE_STATIC', 'Static Coupons');
DEFINE ('_COUPON_NAME', 'Name');
DEFINE ('_COUPON_DESC', 'Description (first 50 chars)');
DEFINE ('_COUPON_CODE', 'Coupon Code');
DEFINE ('_COUPON_ACTIVE', 'Published');
DEFINE ('_COUPON_REORDER', 'Reorder');
DEFINE ('_COUPON_USECOUNT', 'Use Count');

// --== COUPON EDIT ==--
DEFINE ('_COUPON_DETAIL_TITLE', 'Coupon');
DEFINE ('_COUPON_RESTRICTIONS_TITLE', 'Restrict.');
DEFINE ('_COUPON_RESTRICTIONS_TITLE_FULL', 'Restrictions');
DEFINE ('_COUPON_MI', 'Micro Int.');
DEFINE ('_COUPON_MI_FULL', 'Micro Integrations');

DEFINE ('_COUPON_GENERAL_NAME_NAME', 'Name');
DEFINE ('_COUPON_GENERAL_NAME_DESC', 'Enter the (internal&amp;external) name for this coupon');
DEFINE ('_COUPON_GENERAL_COUPON_CODE_NAME', 'Coupon Code');
DEFINE ('_COUPON_GENERAL_COUPON_CODE_DESC', 'Enter the Coupon Code for this coupon - the randomly generated coupon code is checked to be unique within the system');
DEFINE ('_COUPON_GENERAL_DESC_NAME', 'Description');
DEFINE ('_COUPON_GENERAL_DESC_DESC', 'Enter the (internal) description for this coupon');
DEFINE ('_COUPON_GENERAL_ACTIVE_NAME', 'Active');
DEFINE ('_COUPON_GENERAL_ACTIVE_DESC', 'Set whether this coupon is active (can be used)');
DEFINE ('_COUPON_GENERAL_TYPE_NAME', 'Static');
DEFINE ('_COUPON_GENERAL_TYPE_DESC', 'Select whether you want this to be a static coupon. These are stored in a separate table for quicker access, the general distinction being that static coupons are coupons that are used by a lot of users while non-static coupons are for one user.');

DEFINE ('_COUPON_GENERAL_MICRO_INTEGRATIONS_NAME', 'Micro Integrations');
DEFINE ('_COUPON_GENERAL_MICRO_INTEGRATIONS_DESC', 'Select the Micro Integration(s) which you want to be called when this coupon is used');

DEFINE ('_COUPON_PARAMS_AMOUNT_USE_NAME', 'Use Amount');
DEFINE ('_COUPON_PARAMS_AMOUNT_USE_DESC', 'Select whether you want to use a direct discount amount');
DEFINE ('_COUPON_PARAMS_AMOUNT_NAME', 'Discount Amount');
DEFINE ('_COUPON_PARAMS_AMOUNT_DESC', 'Enter the Amount that you want to deduct from the next amount');
DEFINE ('_COUPON_PARAMS_AMOUNT_PERCENT_USE_NAME', 'Use Percentage');
DEFINE ('_COUPON_PARAMS_AMOUNT_PERCENT_USE_DESC', 'Select whether you want a percentage deducted from the actual amount');
DEFINE ('_COUPON_PARAMS_AMOUNT_PERCENT_NAME', 'Discount Percentage');
DEFINE ('_COUPON_PARAMS_AMOUNT_PERCENT_DESC', 'Enter the percentage that you want deducted from the amount');
DEFINE ('_COUPON_PARAMS_PERCENT_FIRST_NAME', 'Percent First');
DEFINE ('_COUPON_PARAMS_PERCENT_FIRST_DESC', 'If you combine percentage and amount, do you want the percentage to be deducted first?');
DEFINE ('_COUPON_PARAMS_USEON_TRIAL_NAME', 'Use On Trial?');
DEFINE ('_COUPON_PARAMS_USEON_TRIAL_DESC', 'Do you want to let the user apply this discount to a trial amount?');
DEFINE ('_COUPON_PARAMS_USEON_FULL_NAME', 'Use On Full?');
DEFINE ('_COUPON_PARAMS_USEON_FULL_DESC', 'Do you want to let the user apply this discount to the actual amount? (With recurring billing: to the first regular payment)');
DEFINE ('_COUPON_PARAMS_USEON_FULL_ALL_NAME', 'Every Full?');
DEFINE ('_COUPON_PARAMS_USEON_FULL_ALL_DESC', 'If the user is using recurring payments, do you want to grant this discount for each subsequent payment? (Or just for the first, if that applies - then select no)');

DEFINE ('_COUPON_PARAMS_HAS_START_DATE_NAME', 'Use Start Date');
DEFINE ('_COUPON_PARAMS_HAS_START_DATE_DESC', 'Do you want to allow your users to use this coupon from a certain date on?');
DEFINE ('_COUPON_PARAMS_START_DATE_NAME', 'Start Date');
DEFINE ('_COUPON_PARAMS_START_DATE_DESC', 'Select the date at which you want to start allowing the usage of this coupon');
DEFINE ('_COUPON_PARAMS_HAS_EXPIRATION_NAME', 'Use Expiration Date');
DEFINE ('_COUPON_PARAMS_HAS_EXPIRATION_DESC', 'Do you want to restrict the usage of this coupon to a certain date?');
DEFINE ('_COUPON_PARAMS_EXPIRATION_NAME', 'Expiration Date');
DEFINE ('_COUPON_PARAMS_EXPIRATION_DESC', 'Select the date at which you want to stop allowing the usage of this coupon');
DEFINE ('_COUPON_PARAMS_HAS_MAX_REUSE_NAME', 'Restrict Reuse?');
DEFINE ('_COUPON_PARAMS_HAS_MAX_REUSE_DESC', 'Do you want to restrict the number of times this coupon may be used?');
DEFINE ('_COUPON_PARAMS_MAX_REUSE_NAME', 'Max Uses');
DEFINE ('_COUPON_PARAMS_MAX_REUSE_DESC', 'Choose the number of times this coupon can be used');

DEFINE ('_COUPON_PARAMS_USECOUNT_NAME', 'Use Count');
DEFINE ('_COUPON_PARAMS_USECOUNT_DESC', 'Reset the number of times this Coupon has been used');

DEFINE ('_COUPON_PARAMS_USAGE_PLANS_ENABLED_NAME', 'Set Plan');
DEFINE ('_COUPON_PARAMS_USAGE_PLANS_ENABLED_DESC', 'Do you want to allow this coupon only for certain plans?');
DEFINE ('_COUPON_PARAMS_USAGE_PLANS_NAME', 'Plans');
DEFINE ('_COUPON_PARAMS_USAGE_PLANS_DESC', 'Choose which plans this coupon can be used for');

DEFINE ('_COUPON_RESTRICTIONS_MINGID_ENABLED_NAME', 'Enable Min GID:');
DEFINE ('_COUPON_RESTRICTIONS_MINGID_ENABLED_DESC', 'Enable this setting if you want to restrict whether a user can use this coupon by a minimum usergroup.');
DEFINE ('_COUPON_RESTRICTIONS_MINGID_NAME', 'Visibility Group:');
DEFINE ('_COUPON_RESTRICTIONS_MINGID_DESC', 'The minimum user level required to use this coupon.');
DEFINE ('_COUPON_RESTRICTIONS_FIXGID_ENABLED_NAME', 'Enable Fixed GID:');
DEFINE ('_COUPON_RESTRICTIONS_FIXGID_ENABLED_DESC', 'Enable this setting if you want to restrict this coupon to one usergroup.');
DEFINE ('_COUPON_RESTRICTIONS_FIXGID_NAME', 'Set Group:');
DEFINE ('_COUPON_RESTRICTIONS_FIXGID_DESC', 'Only users with this usergroup can use this coupon.');
DEFINE ('_COUPON_RESTRICTIONS_MAXGID_ENABLED_NAME', 'Enable Max GID:');
DEFINE ('_COUPON_RESTRICTIONS_MAXGID_ENABLED_DESC', 'Enable this setting if you want to restrict whether a user use this coupon by a maximum usergroup.');
DEFINE ('_COUPON_RESTRICTIONS_MAXGID_NAME', 'Maximum Group:');
DEFINE ('_COUPON_RESTRICTIONS_MAXGID_DESC', 'The maximum user level a user can have to use this coupon.');

DEFINE ('_COUPON_RESTRICTIONS_PREVIOUSPLAN_REQ_ENABLED_NAME', 'Required Prev. Plan:');
DEFINE ('_COUPON_RESTRICTIONS_PREVIOUSPLAN_REQ_ENABLED_DESC', 'Enable checking for previous payment plan');
DEFINE ('_COUPON_RESTRICTIONS_PREVIOUSPLAN_REQ_NAME', 'Plan:');
DEFINE ('_COUPON_RESTRICTIONS_PREVIOUSPLAN_REQ_DESC', 'A user will only be able to use this coupon if he or she used the selected plan before the one currently in use');
DEFINE ('_COUPON_RESTRICTIONS_CURRENTPLAN_REQ_ENABLED_NAME', 'Required Curr. Plan:');
DEFINE ('_COUPON_RESTRICTIONS_CURRENTPLAN_REQ_ENABLED_DESC', 'Enable checking for currently present payment plan');
DEFINE ('_COUPON_RESTRICTIONS_CURRENTPLAN_REQ_NAME', 'Plan:');
DEFINE ('_COUPON_RESTRICTIONS_CURRENTPLAN_REQ_DESC', 'A user will only be able to use this coupon if he or she is currently assigned to, or has expired from the plan selected here');
DEFINE ('_COUPON_RESTRICTIONS_OVERALLPLAN_REQ_ENABLED_NAME', 'Required Used Plan:');
DEFINE ('_COUPON_RESTRICTIONS_OVERALLPLAN_REQ_ENABLED_DESC', 'Enable checking for overall used payment plan');
DEFINE ('_COUPON_RESTRICTIONS_OVERALLPLAN_REQ_NAME', 'Plan:');
DEFINE ('_COUPON_RESTRICTIONS_OVERALLPLAN_REQ_DESC', 'A user will only be able to use this coupon if he or she has used the selected plan once, no matter when');

DEFINE ('_COUPON_RESTRICTIONS_USED_PLAN_MIN_ENABLED_NAME', 'Min Used Plan:');
DEFINE ('_COUPON_RESTRICTIONS_USED_PLAN_MIN_ENABLED_DESC', 'Enable checking for the minimum number of times your consumers have subscribed to a specified payment plan in order to be able to use this coupon');
DEFINE ('_COUPON_RESTRICTIONS_USED_PLAN_MIN_AMOUNT_NAME', 'Used Amount:');
DEFINE ('_COUPON_RESTRICTIONS_USED_PLAN_MIN_AMOUNT_DESC', 'The minimum amount a user has to have used the selected plan');
DEFINE ('_COUPON_RESTRICTIONS_USED_PLAN_MIN_NAME', 'Plan:');
DEFINE ('_COUPON_RESTRICTIONS_USED_PLAN_MIN_DESC', 'The payment plan that the user has to have used the specified number of times at least');
DEFINE ('_COUPON_RESTRICTIONS_USED_PLAN_MAX_ENABLED_NAME', 'Max Used Plan:');
DEFINE ('_COUPON_RESTRICTIONS_USED_PLAN_MAX_ENABLED_DESC', 'Enable checking for the maximum number of times your consumers have subscribed to a specified payment plan in order to be able to use this coupon');
DEFINE ('_COUPON_RESTRICTIONS_USED_PLAN_MAX_AMOUNT_NAME', 'Used Amount:');
DEFINE ('_COUPON_RESTRICTIONS_USED_PLAN_MAX_AMOUNT_DESC', 'The maximum amount a user can have used the selected plan');
DEFINE ('_COUPON_RESTRICTIONS_USED_PLAN_MAX_NAME', 'Plan:');
DEFINE ('_COUPON_RESTRICTIONS_USED_PLAN_MAX_DESC', 'The payment plan that the user has to have used the specified number of times at most');

// --== INVOICE OVERVIEW ==--
DEFINE ('_INVOICE_TITLE', 'Invoices');
DEFINE ('_INVOICE_SEARCH', 'Search');
DEFINE ('_INVOICE_USERID', 'User Name');
DEFINE ('_INVOICE_INVOICE_NUMBER', 'Invoice Number');
DEFINE ('_INVOICE_TRANSACTION_DATE', 'Transaction Date');
DEFINE ('_INVOICE_CREATED_DATE', 'Created Date');
DEFINE ('_INVOICE_METHOD', 'Invoice Method');
DEFINE ('_INVOICE_AMOUNT', 'Invoice Amount');
DEFINE ('_INVOICE_CURRENCY', 'Invoices Currency');

// --== PAYMENT HISTORY OVERVIEW ==--
DEFINE ('_HISTORY_TITLE2', 'Your Current Transaction History');
DEFINE ('_HISTORY_SEARCH', 'Search');
DEFINE ('_HISTORY_USERID', 'User Name');
DEFINE ('_HISTORY_INVOICE_NUMBER', 'Invoice Number');
DEFINE ('_HISTORY_PLAN_NAME', 'Plan Subscribed To');
DEFINE ('_HISTORY_TRANSACTION_DATE', 'Transaction Date');
DEFINE ('_HISTORY_METHOD', 'Invoice Method');
DEFINE ('_HISTORY_AMOUNT', 'Invoice Amount');
DEFINE ('_HISTORY_RESPONSE', 'Server Response');

// --== ALL USER RELATED PAGES ==--
DEFINE ('_METHOD', 'Method');

// --== PENDING PAGE ==--
DEFINE ('_PEND_DATE', 'Pending Since');
DEFINE ('_PEND_TITLE', 'Pending Subscriptions');
DEFINE ('_PEND_DESC', 'Subscriptions that does not complete the process. This state is common for a short time while system waits for the payment.');
DEFINE ('_ACTIVATE', 'Activate');
DEFINE ('_ACTIVATED', 'User activated.');

?>