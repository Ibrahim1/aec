<?php
/**
 * @version $Id: english.php 16 2007-06-27 09:04:04Z mic $
 * @package AEC - Account Control Expiration - Subscription component for Joomla! OS CMS
 * @subpackage Language - MicroIntegrations - English
 * @copyright Copyright (C) 2004-2007, All Rights Reserved, Helder Garcia, David Deutsch
 * @author Team AEC - http://www.gobalnerd.org
 * @license GNU/GPL v.2 http://www.gnu.org/copyleft/gpl.html
 */

// Dont allow direct linking
defined( '_VALID_MOS' ) or die( 'Not really ....' );

// Load Identifier
define( '_AEC_LANG_INCLUDED_MI', 1);

// acajoom
define( '_AEC_MI_NAME_ACAJOOM',		'Acajoom (beta!)' );
define( '_AEC_MI_DESC_ACAJOOM',		'Includes the newsletter Acajoom [Experimental - please give feedback to the developers!]' );
define( '_MI_MI_ACAJOOM_LIST_NAME',		'Set List' );
define( '_MI_MI_ACAJOOM_LIST_DESC',		'Which Mailing list do you want to assign this user to?' );
define( '_MI_MI_ACAJOOM_LIST_EXP_NAME',		'Set Expiration List' );
define( '_MI_MI_ACAJOOM_LIST_EXP_DESC',		'Which Mailing list do you want to assign this user to after expiration?' );

// htaccess
define( '_AEC_MI_NAME_HTACCESS',	'.htaccess' );
define( '_AEC_MI_DESC_HTACCESS',	'Protect a folder with a .htaccess file and only allow users of this subscription to access it with their joomla account details.' );
define( '_MI_MI_HTACCESS_MI_FOLDER_NAME',			'Folder' );
define( '_MI_MI_HTACCESS_MI_FOLDER_DESC',			'Your protected folder name. Following keywords will be replaced:<br />[cmsroot] -> %s<br />Remember that this has no trailing slash! Your foldername should have none as well!' );
define( '_MI_MI_HTACCESS_MI_PASSWORDFOLDER_NAME',	'Password Folder' );
define( '_MI_MI_HTACCESS_MI_PASSWORDFOLDER_DESC',	'A place where you want to store the password file. This should be a directory which is not web-accessible. Use [abovecmsroot] to put in the directory above the cms root directory - this is recommended.' );
define( '_MI_MI_HTACCESS_MI_NAME_NAME',				'Area Name' );
define( '_MI_MI_HTACCESS_MI_NAME_DESC',				'The name of the protected Area' );
define( '_MI_MI_HTACCESS_USE_MD5_NAME',				'use md5' );
define( '_MI_MI_HTACCESS_USE_MD5_DESC',				'<strong>Important!</strong> If you want to use this MI to restrict folders with apache, you have to use crypt - so just leave this at NO. If you want to use a different software which uses htaccess/htuser files (like an icecast server for instance), set this to yes and it will use standard md5 encryption.' );
define( '_MI_MI_HTACCESS_REBUILD_NAME',				'Rebuild htaccess' );
define( '_MI_MI_HTACCESS_REBUILD_DESC',				'If you changed something important or lost your htaccess file, this option will rebuild your whole htaccess files by looking for each plan that has this MI applied and then add each user that uses one of these plans to the file.' );

//affiliate PRO
define( '_AEC_MI_NAME_AFFPRO',		'AffiliatePRO' );
define( '_AEC_MI_DESC_AFFPRO',		'Connect your AEC sales to AffiliatePRO' );
define( '_MI_MI_AFFILIATEPRO_URL_NAME',				'Affiliate PRO URL' );
define( '_MI_MI_AFFILIATEPRO_URL_DESC',				'Enter the AffiliatePRO Url that points to your AffiliatePRO sale.js (It should look like this: "http://www.demo.qualityunit.com/postaffiliatepro3/scripts/sale.js").' );

// docman
define( '_AEC_MI_NAME_DOCMAN',		'DocMan' );
define( '_AEC_MI_DESC_DOCMAN',		'Choose the amount of files a user can download and what DocMan group should be assigned to the user account' );
define( '_MI_MI_DOCMAN_SET_DOWNLOADS_NAME',			'Set Downloads' );
define( '_MI_MI_DOCMAN_SET_DOWNLOADS_DESC',			'SET this amount of downloads granted to the user - OVERRIDES THE >>ADD<< Setting! (does NOT reset the amount of downloads a user has already used!)' );
define( '_MI_MI_DOCMAN_ADD_DOWNLOADS_NAME',			'Add Downloads' );
define( '_MI_MI_DOCMAN_ADD_DOWNLOADS_DESC',			'Add this amount of downloads to the total granted amount of downloads for this user. Will be overridden by SET if you put a value for that as well!.' );
define( '_MI_MI_DOCMAN_SET_GROUP_NAME',				'Set DocMan Group' );
define( '_MI_MI_DOCMAN_SET_GROUP_DESC',				'Choose Yes if you want this MI to set the DocMan Group when it is called.' );
define( '_MI_MI_DOCMAN_GROUP_NAME',					'DocMan Group' );
define( '_MI_MI_DOCMAN_GROUP_DESC',					'The DocMan group that you want the user to be in.' );
define( '_MI_MI_DOCMAN_GROUP_EXP_NAME',				'Set DocMan Group expiration' );
define( '_MI_MI_DOCMAN_GROUP_EXP_DESC',				'Choose Yes if you want this MI to set the DocMan Group when the calling payment plan expires.' );
define( '_MI_MI_DOCMAN_SET_GROUP_EXP_NAME',			'Expiration group' );
define( '_MI_MI_DOCMAN_SET_GROUP_EXP_DESC',			'The DocMan group that you want the user to be in when the subscription runs out.' );
define( '_MI_MI_DOCMAN_REBUILD_NAME',				'Rebuild' );
define( '_MI_MI_DOCMAN_REBUILD_DESC',				'Attempt to rebuild the list of users assigned to the usergroup - >Set DocMan Group< and >DocMan Group< have to both be set for this.' );
define( '_AEC_MI_HACK1_DOCMAN',						'Build in a downloads restriction for DocMan, to be used with Micro Integrations.' );
define( '_AEC_MI_DOCMAN_NOCREDIT',					'We are terribly sorry: You have no downloads left.' );

// email
define( '_AEC_MI_NAME_EMAIL',		'Email' );
define( '_AEC_MI_DESC_EMAIL',		'Send an Email to one or more adresses on application or expiration of the subscription' );
define( '_MI_MI_EMAIL_SENDER_NAME',					'Sender E-Mail' );
define( '_MI_MI_EMAIL_SENDER_DESC',					'Sender E-Mail Address' );
define( '_MI_MI_EMAIL_SENDER_NAME_NAME',			'Sender Name' );
define( '_MI_MI_EMAIL_SENDER_NAME_DESC',			'The displayed name of the Sender' );
define( '_MI_MI_EMAIL_RECIPIENT_NAME',				'Recipient(s)' );
define( '_MI_MI_EMAIL_RECIPIENT_DESC',				'Who is to receive this E-Mail? Separate with comma. The rewriting routines explained below will work for this field.' );
define( '_MI_MI_EMAIL_SUBJECT_NAME',				'Subject' );
define( '_MI_MI_EMAIL_SUBJECT_DESC',				'Subject of this email (Uses RewriteEngine explained below as well)' );
define( '_MI_MI_EMAIL_TEXT_HTML_NAME',				'HTML Encoding' );
define( '_MI_MI_EMAIL_TEXT_HTML_DESC',				'Do you want this email to be HTML encoded? (Make sure that there are not tags in it if you do not want this)' );
define( '_MI_MI_EMAIL_TEXT_NAME',					'Text' );
define( '_MI_MI_EMAIL_TEXT_DESC',					'Text to be sent when the plan is purchased. The rewriting routines explained below will work for this field.' );
define( '_MI_MI_EMAIL_SUBJECT_FIRST_NAME',			'Subject (New)' );
define( '_MI_MI_EMAIL_SUBJECT_FIRST_DESC',			'Subject of this email only when a user first signs up (Uses RewriteEngine explained below as well)' );
define( '_MI_MI_EMAIL_TEXT_FIRST_HTML_NAME',		'HTML Encoding (New)' );
define( '_MI_MI_EMAIL_TEXT_FIRST_HTML_DESC',		'Do you want this email to be HTML encoded? (Make sure that there are not tags in it if you do not want this)' );
define( '_MI_MI_EMAIL_TEXT_FIRST_NAME',				'Text' );
define( '_MI_MI_EMAIL_TEXT_FIRST_DESC',				'Text to be sent when the plan is purchased and only when a user first signs up. The rewriting routines explained below will work for this field.' );
define( '_MI_MI_EMAIL_SUBJECT_EXP_NAME',			'Expiration Subject' );
define( '_MI_MI_EMAIL_SUBJECT_EXP_DESC',			'Expiration Subject (Uses RewriteEngine explained below as well)' );
define( '_MI_MI_EMAIL_TEXT_EXP_HTML_NAME',			'HTML Encoding (Expiration)' );
define( '_MI_MI_EMAIL_TEXT_EXP_HTML_DESC',			'Do you want this email to be HTML encoded? (Make sure that there are not tags in it if you do not want this)' );
define( '_MI_MI_EMAIL_TEXT_EXP_NAME',				'Expiration Text' );
define( '_MI_MI_EMAIL_TEXT_EXP_DESC',				'Text to be sent when the plan expires. The rewriting routines explained below will work for this field.' );
define( '_MI_MI_EMAIL_SUBJECT_PRE_EXP_NAME',		'Subject' );
define( '_MI_MI_EMAIL_SUBJECT_PRE_EXP_DESC',		'Pre Expiration Subject (Uses RewriteEngine explained below as well)' );
define( '_MI_MI_EMAIL_TEXT_PRE_EXP_HTML_NAME',		'HTML Encoding (Pre-Expiration)' );
define( '_MI_MI_EMAIL_TEXT_PRE_EXP_HTML_DESC',		'Do you want this email to be HTML encoded? (Make sure that there are not tags in it if you do not want this)' );
define( '_MI_MI_EMAIL_TEXT_PRE_EXP_NAME',			'Pre Expiration Text' );
define( '_MI_MI_EMAIL_TEXT_PRE_EXP_DESC',			'Text to be sent when the plan is about to expire (specify when on the previous tab). The rewriting routines explained below will work for this field.' );
define( '_AEC_MI_SET11_EMAIL',		'Rewriting Info' );

// iDevAffiliate
define( '_AEC_MI_NAME_IDEV',		'iDevAffiliate' );
define( '_AEC_MI_DESC_IDEV',		'Connect your sales to the iDevAffiliate Component' );
define( '_MI_MI_IDEVAFFILIATE_SETUPINFO_NAME',		'Important Information' );
define( '_MI_MI_IDEVAFFILIATE_SETUPINFO_DESC',		'Using the micro integration, you NEED TO make sure that you are using the official joomla component by the iDev Team. This MI will automatically call to <yourjoomlaroot>/idevaffiliate. If you want to specify it otherwise, please do so in the directory setting below. You may leave out the root url that joomla sits in.' );
define( '_MI_MI_IDEVAFFILIATE_PROFILE_NAME',		'Profile' );
define( '_MI_MI_IDEVAFFILIATE_PROFILE_DESC',		'The Profile identification within iDevAffiliate' );
define( '_MI_MI_IDEVAFFILIATE_DIRECTORY_NAME',		'iDev Directory' );
define( '_MI_MI_IDEVAFFILIATE_DIRECTORY_DESC',		'Specify a subdirectory if iDevAffiliate does not rest in the directory as explained in the above box.' );
define( '_MI_MI_IDEVAFFILIATE_ONLYCUSTOMPARAMS_NAME',		'Only Custom Parameters' );
define( '_MI_MI_IDEVAFFILIATE_ONLYCUSTOMPARAMS_DESC',		'Only transmit the custom parameters as specified below (instead of transmitting the regular - invoice number and amount - and the profile id if set).' );
define( '_MI_MI_IDEVAFFILIATE_CUSTOMPARAMS_NAME',		'Custom Parameters' );
define( '_MI_MI_IDEVAFFILIATE_CUSTOMPARAMS_DESC',		'If you want to transmit custom parameters instead of or additional to the regular parameters, please put them in here. Separated by linebreaks in the form of "parameter_name=parameter_value". The RewriteEngine works as specified below.' );

// MosetsTree
define( '_AEC_MI_NAME_MOSETS',		'MosetsTree' );
define( '_AEC_MI_DESC_MOSETS',		'Restrict the amount of listings a user can publish' );
define( '_MI_MI_MOSETS_TREE_SET_LISTINGS_NAME',		'Set listings' );
define( '_MI_MI_MOSETS_TREE_SET_LISTINGS_DESC',		'Input the amount of listings you want as an overwriting set for this call' );
define( '_MI_MI_MOSETS_TREE_ADD_LISTINGS_NAME',		'Add listings' );
define( '_MI_MI_MOSETS_TREE_ADD_LISTINGS_DESC',		'Input the amount of listings you want to add with this call' );
define( '_AEC_MI_HACK1_MOSETS',		'No Listings left' );
define( '_AEC_MI_HACK2_MOSETS',		'Registration and correct Subscription Required!' );
define( '_AEC_MI_HACK3_MOSETS',		'Prevent user from creating a new listing if he or she has run out of granted listings' );
define( '_AEC_MI_HACK4_MOSETS',		'Prevent user from saving a new listing if he or she has run out of granted listings. Also use a listing if the user has one left and it does not need to be approved - if it does, his listings count will be updated on the following hack.' );
define( '_AEC_MI_HACK5_MOSETS',		'Check for allowed listings and update the Used Listings counter when approving listings in the backend (see above for reference).' );
define( '_AEC_MI_DIV1_MOSETS',		'You can create <strong>%s</strong> more listings in our directory.' );

// MySQL Query
define( '_AEC_MI_NAME_MYSQL',		'mySQL Query' );
define( '_AEC_MI_DESC_MYSQL',		'Specify a mySQL query that should be carried out with this subscription or on its expiration' );
define( '_MI_MI_MYSQL_QUERY_QUERY_NAME',			'Query' );
define( '_MI_MI_MYSQL_QUERY_QUERY_DESC',			'MySQL query to be carried out when this MI is called.' );
define( '_MI_MI_MYSQL_QUERY_QUERY_EXP_NAME',		'Expiration Query' );
define( '_MI_MI_MYSQL_QUERY_QUERY_EXP_DESC',		'MySQL query to be carried out when this MI is called on expiration.' );
define( '_MI_MI_MYSQL_QUERY_QUERY_PRE_EXP_NAME',	'Pre Expiration Query' );
define( '_MI_MI_MYSQL_QUERY_QUERY_PRE_EXP_DESC',	'MySQL query to be carried out when this MI is called before expiration (specify when on the first tab)' );
define( '_AEC_MI_SET4_MYSQL',		'Rewriting Info' );

// reMOSitory
define( '_AEC_MI_NAME_REMOS',		'reMOSitory' );
define( '_AEC_MI_DESC_REMOS',		'Choose the amount of files a user can download and what reMOSitory group should be assigned to the user account' );
define( '_MI_MI_REMOSITORY_ADD_DOWNLOADS_NAME',		'Add listings' );
define( '_MI_MI_REMOSITORY_ADD_DOWNLOADS_DESC',		'Input the amount of listings you want added to the users account for this call' );
define( '_MI_MI_REMOSITORY_SET_DOWNLOADS_NAME',		'Set listings' );
define( '_MI_MI_REMOSITORY_SET_DOWNLOADS_DESC',		'Input the amount of listings you want as a overwriting set for this call' );
define( '_MI_MI_REMOSITORY_SET_GROUP_NAME',			'Set group' );
define( '_MI_MI_REMOSITORY_SET_GROUP_DESC',			'Choose Yes if you want this MI to set the ReMOSitory Group when the calling payment plan expires' );
define( '_MI_MI_REMOSITORY_GROUP_NAME',				'Group' );
define( '_MI_MI_REMOSITORY_GROUP_DESC',				'The ReMOSitory group that you want the user to be in.' );
define( '_MI_MI_REMOSITORY_SET_GROUP_EXP_NAME',		'Set group Expiration' );
define( '_MI_MI_REMOSITORY_SET_GROUP_EXP_DESC',		'Choose Yes if you want this MI to set the ReMOSitory Group when the calling payment plan expires' );
define( '_MI_MI_REMOSITORY_GROUP_EXP_NAME',			'Expiration group' );
define( '_MI_MI_REMOSITORY_GROUP_EXP_DESC',			'The ReMOSitory group that you want the user to be in when the subscription runs out.' );
define( '_AEC_MI_HACK1_REMOS',		'No Credits' );
define( '_AEC_MI_HACK2_REMOS',		'Build in a downloads restriction for reMOSitory, to be used with Micro Integrations.' );

// VirtueMart
define( '_AEC_MI_NAME_VIRTM',		'VirtueMart' );
define( '_AEC_MI_DESC_VIRTM',		'Choose the VM usergroup this user should be applied to' );
define( '_MI_MI_VIRTUEMART_SET_SHOPPER_GROUP_NAME',	'Set Shopper group' );
define( '_MI_MI_VIRTUEMART_SET_SHOPPER_GROUP_DESC',	'Choose Yes if you want this MI to set the Shopper Group when it is called.' );
define( '_MI_MI_VIRTUEMART_SHOPPER_GROUP_NAME',		'Shopper group' );
define( '_MI_MI_VIRTUEMART_SHOPPER_GROUP_DESC',		'The VirtueMart Shopper group that you want the user to be in.' );
define( '_MI_MI_VIRTUEMART_SET_SHOPPER_GROUP_EXP_NAME',		'Set group Expiration' );
define( '_MI_MI_VIRTUEMART_SET_SHOPPER_GROUP_EXP_DESC',		'Choose Yes if you want this MI to set the Shopper Group when the calling payment plan expires.' );
define( '_MI_MI_VIRTUEMART_SHOPPER_GROUP_EXP_NAME',	'Expiration Shopper group' );
define( '_MI_MI_VIRTUEMART_SHOPPER_GROUP_EXP_DESC',	'The VirtueMart Shopper group that you want the user to be in when the subscription runs out.' );
define( '_MI_MI_VIRTUEMART_CREATE_ACCOUNT_NAME',	'Auto Create Account' );
define( '_MI_MI_VIRTUEMART_CREATE_ACCOUNT_DESC',	'Select "Yes" if you want this MI to also create a new VirtueMart account if there is none for the user.' );
define( '_MI_MI_VIRTUEMART_REBUILD_NAME',	'Rebuild' );
define( '_MI_MI_VIRTUEMART_REBUILD_DESC',	'Attempt to rebuild the list of users assigned to the usergroup according to their relationship to a plan that holds this MI.' );

// Joomlauser
define( '_AEC_MI_NAME_JOOMLAUSER',		'Joomla User' );
define( '_AEC_MI_DESC_JOOMLAUSER',		'Actions that affect the joomla user account' );
define( '_MI_MI_JOOMLAUSER_ACTIVATE_NAME',			'Activate' );
define( '_MI_MI_JOOMLAUSER_ACTIVATE_DESC',			'Setting this to "Yes" will unblock a user and clean the activation code' );

// CommunityBuilder
define( '_AEC_MI_NAME_COMMUNITYBUILDER',				'Community Builder' );
define( '_AEC_MI_DESC_COMMUNITYBUILDER',				'Actions that affect the Community Builder user account' );
define( '_MI_MI_COMMUNITYBUILDER_APPROVE_NAME',			'Approve' );
define( '_MI_MI_COMMUNITYBUILDER_APPROVE_DESC',			'Carry out an Admin Approval when this MI is triggered.' );
define( '_MI_MI_COMMUNITYBUILDER_UNAPPROVE_EXP_NAME',	'Reset Approval' );
define( '_MI_MI_COMMUNITYBUILDER_UNAPPROVE_EXP_DESC',	'Set the Admin Approval of a user to "No" when expired.' );
define( '_MI_MI_COMMUNITYBUILDER_SET_FIELDS_NAME',		'Set Fields' );
define( '_MI_MI_COMMUNITYBUILDER_SET_FIELDS_DESC',		'Automatically set the fields (which are not marked with "(expiration)" when the plan is paid for.' );
define( '_MI_MI_COMMUNITYBUILDER_SET_FIELDS_EXP_NAME',	'Set Fields Expiration' );
define( '_MI_MI_COMMUNITYBUILDER_SET_FIELDS_EXP_DESC',	'Automatically set the fields (which are marked with "(expiration)" when the plan is paid for.' );
define( '_MI_MI_COMMUNITYBUILDER_EXPMARKER',			'(expiration)' );

// JUGA
define( '_AEC_MI_NAME_JUGA',		'JUGA' );
define( '_AEC_MI_DESC_JUGA',		'Set JUGA groups on application or expiration of a plan' );
define( '_MI_MI_JUGA_SET_ENROLL_GROUP_NAME',		'Add to Group' );
define( '_MI_MI_JUGA_SET_ENROLL_GROUP_DESC',		'Set to yes, and pick groups below to enroll the user in on application of plan? (Multiple select allowed)' );
define( '_MI_MI_JUGA_ENROLL_GROUP_NAME',			'JUGA Group' );
define( '_MI_MI_JUGA_ENROLL_GROUP_DESC',			'Select a plan to enroll the user in on application of plan:' );
define( '_MI_MI_JUGA_SET_REMOVE_GROUP_NAME',		'Remove Groups' );
define( '_MI_MI_JUGA_SET_REMOVE_GROUP_DESC',		'Set to yes, to delete all groups for this user before the groups below are applied, otherwise these groups will be added to existing groups.' );
define( '_MI_MI_JUGA_SET_ENROLL_GROUP_EXP_NAME',	'Add to Group Exp' );
define( '_MI_MI_JUGA_SET_ENROLL_GROUP_EXP_DESC',	'Set to yes, and pick groups below to enroll the user in on expiration of plan? (Multiple select allowed)' );
define( '_MI_MI_JUGA_ENROLL_GROUP_EXP_NAME',		'JUGA Group Exp' );
define( '_MI_MI_JUGA_ENROLL_GROUP_EXP_DESC',		'Select a plan to enroll the user in on expiration of plan:' );
define( '_MI_MI_JUGA_SET_REMOVE_GROUP_EXP_NAME',	'Remove Groups Exp' );
define( '_MI_MI_JUGA_SET_REMOVE_GROUP_EXP_DESC',	'Set to yes, to delete all groups for this user before the groups below are applied, otherwise these groups will be added to existing groups.' );
define( '_MI_MI_JUGA_REBUILD_NAME',					'Rebuild' );
define( '_MI_MI_JUGA_REBUILD_DESC',					'Select YES to rebuild the groups relations after saving this' );

// DisplayPipeline
define( '_AEC_MI_NAME_DISPLAYPIPELINE',		'DisplayPipeline' );
define( '_AEC_MI_DESC_DISPLAYPIPELINE',		'Display Text on the AEC Module' );
define( '_MI_MI_DISPLAYPIPELINE_ONLY_USER_NAME',		'Only to User' );
define( '_MI_MI_DISPLAYPIPELINE_ONLY_USER_DESC',		'Only display this text to the user who issued this request' );
define( '_MI_MI_DISPLAYPIPELINE_ONCE_PER_USER_NAME',	'Once per User' );
define( '_MI_MI_DISPLAYPIPELINE_ONCE_PER_USER_DESC',	'Only display this text once to a user. This will be set to no automatically if you set the above switch to save ressources.' );
define( '_MI_MI_DISPLAYPIPELINE_EXPIRE_NAME',			'Expire' );
define( '_MI_MI_DISPLAYPIPELINE_EXPIRE_DESC',			'Do not display after a certain date.' );
define( '_MI_MI_DISPLAYPIPELINE_EXPIRATION_NAME',		'Expiration' );
define( '_MI_MI_DISPLAYPIPELINE_EXPIRATION_DESC',		'Set this as Expiration. Refer to <a href="http://www.php.net/manual/en/function.strtotime.php" alt="php.net manual on strtotime function">this manual</a> to see what you can use as input here.' );
define( '_MI_MI_DISPLAYPIPELINE_DISPLAYMAX_NAME',		'Display Max' );
define( '_MI_MI_DISPLAYPIPELINE_DISPLAYMAX_DESC',		'Set amount of times this can be displayed' );
define( '_MI_MI_DISPLAYPIPELINE_TEXT_NAME',				'Text' );
define( '_MI_MI_DISPLAYPIPELINE_TEXT_DESC',				'Text that is displayed to the user. You can use the rewrite strings explained below to insert dynamic data.' );

// GoogleAnalytics
define( '_AEC_MI_NAME_GOOGLEANALYTICS',		'Google Analytics [beta]' );
define( '_AEC_MI_DESC_GOOGLEANALYTICS',		'With this, you can attach Google Analytics e-commerce tracking code to the DisplayPipeline. [Experimental - please give feedback to the developers!]' );
define( '_MI_MI_GOOGLEANALYTICS_ACCOUNT_ID_NAME',		'Google Account ID' );
define( '_MI_MI_GOOGLEANALYTICS_ACCOUNT_ID_DESC',		'Your Google Account id, it should look like this: UA-xxxx-x' );

// Fireboard
define( '_AEC_MI_NAME_FIREBOARD','Fireboard Micro Integration');
define( '_AEC_MI_DESC_FIREBOARD','Will automate addition of a user to a group in FB. *NOTE* FB currently has limited support for FB groups. You are advised to check the fireboard forums for limtiations.  Full use will not come until FB 1.1.  In 1.0.0 to 1.0.2 this can be used with a CSS change to show group information under the user\'s avatar as happens on www.bestofjoomla.org with admin team members');
define( '_MI_MI_FIREBOARD_SET_GROUP_NAME','Set group on plan application');
define( '_MI_MI_FIREBOARD_SET_GROUP_DESC','Choose Yes if you wish a fireboard group to be applied when the plan is applied');
define( '_MI_MI_FIREBOARD_GROUP_NAME','Fireboard group to apply member to on application');
define( '_MI_MI_FIREBOARD_GROUP_DESC','The group you wish applied - if you chose yes. Manually create groups in table jos_fb_groups');
define( '_MI_MI_FIREBOARD_SET_GROUP_EXP_NAME','Set group on expiration of plan');
define( '_MI_MI_FIREBOARD_SET_GROUP_EXP_DESC','Choose Yes if you wish the fireboard group to be changed when the plan expires');
define( '_MI_MI_FIREBOARD_GROUP_EXP_NAME','Fireboard group to apply member to on expiration of plan.');
define( '_MI_MI_FIREBOARD_GROUP_EXP_DESC','The group you wish to use if the plan expires.  Manually add groups to the table jos_fb_groups');
define( '_MI_MI_FIREBOARD_REBUILD_NAME',				'Rebuild Groups' );
define( '_MI_MI_FIREBOARD_REBUILD_DESC',				'This option will rebuild your whole Fireboard group assignment by looking for each plan that has this MI applied and then add each user that uses one of these plans to the file.' );

// Coupon
define( '_AEC_MI_NAME_COUPON', 'Coupons');
define( '_AEC_MI_DESC_COUPON', 'Create and send out coupons');
define( '_MI_MI_COUPON_MASTER_COUPON_NAME', 'Master Coupon');
define( '_MI_MI_COUPON_MASTER_COUPON_DESC', 'Which Master Coupon should these be copied from?');
define( '_MI_MI_COUPON_BIND_SUBSCRIPTION_NAME', 'Bind to Subscription');
define( '_MI_MI_COUPON_BIND_SUBSCRIPTION_DESC', 'If activated, the coupons will only be usable if the subscription they have been created with is still active as well');
define( '_MI_MI_COUPON_CREATE_NEW_COUPONS_NAME', 'Amount');
define( '_MI_MI_COUPON_CREATE_NEW_COUPONS_DESC', 'The amount of coupons that should be created');
define( '_MI_MI_COUPON_MAX_REUSE_NAME', 'Max Reuse Coupons');
define( '_MI_MI_COUPON_MAX_REUSE_DESC', 'The amount of times these coupons can be used');
define( '_MI_MI_COUPON_MAIL_OUT_COUPONS_NAME', 'Mail out Coupons');
define( '_MI_MI_COUPON_MAIL_OUT_COUPONS_DESC', 'This will send the coupons in an email to the address specified below');
define( '_MI_MI_COUPON_ALWAYS_NEW_COUPONS_NAME', 'Amount');
define( '_MI_MI_COUPON_ALWAYS_NEW_COUPONS_DESC', 'Always create new coupons (Yes) if the MI is triggered or only on the first time (No)?');
define( '_MI_MI_COUPON_INC_OLD_COUPONS_NAME', 'Increment Old Coupons');
define( '_MI_MI_COUPON_INC_OLD_COUPONS_DESC', 'Will increment the uses of old coupons by the given amount, so that they can be used again after the subscription has been renewed');
define( '_MI_MI_COUPON_SENDER_NAME',				'Sender E-Mail' );
define( '_MI_MI_COUPON_SENDER_DESC',				'Sender E-Mail Address' );
define( '_MI_MI_COUPON_SENDER_NAME_NAME',			'Sender Name' );
define( '_MI_MI_COUPON_SENDER_NAME_DESC',			'The displayed name of the Sender' );
define( '_MI_MI_COUPON_RECIPIENT_NAME',				'Recipient(s)' );
define( '_MI_MI_COUPON_RECIPIENT_DESC',				'Who is to receive this E-Mail? Separate with comma. The rewriting routines explained below will work for this field.' );
define( '_MI_MI_COUPON_SUBJECT_NAME',				'Subject' );
define( '_MI_MI_COUPON_SUBJECT_DESC',				'Subject of this email (Uses RewriteEngine explained below as well)' );
define( '_MI_MI_COUPON_TEXT_HTML_NAME',				'HTML Encoding' );
define( '_MI_MI_COUPON_TEXT_HTML_DESC',				'Do you want this email to be HTML encoded? (Make sure that there are not tags in it if you do not want this)' );
define( '_MI_MI_COUPON_TEXT_NAME',					'Text' );
define( '_MI_MI_COUPON_TEXT_DESC',					'Text to be sent when the coupons are created. The rewriting routines explained below will work for this field. Mark the point where the coupon codes are displayed with "%s"!' );

// Attend Events
define( '_AEC_MI_NAME_ATTEND_EVENTS',		'Attend Events' );
define( '_AEC_MI_DESC_ATTEND_EVENTS',		'Dummy MicroIntegration - for now only links payments from AE to AEC' );
?>