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

// acajoom
define( '_AEC_MI_NAME_ACAJOOM',		'Acajoom' );
define( '_AEC_MI_DESC_ACAJOOM',		'Includes the newsletter Acajoom (free edition)' );

// htaccess
define( '_AEC_MI_NAME_HTACCESS',	'.htaccess' );
define( '_AEC_MI_DESC_HTACCESS',	'Protect a folder with a .htaccess file and only allow users of this subscription to access it with their joomla account details.' );
define( '_AEC_MI_SET1_HTACCESS',	'Folder' );
define( '_AEC_MI_SET1_1_HTACCESS',	'Your protected folder name. Following keywords will be replaced:<br />[cmsroot] -> %s<br />Remember that this has no trailing slash! Your foldername should have none as well!' );
define( '_AEC_MI_SET2_HTACCESS',	'Password Folder' );
define( '_AEC_MI_SET2_1_HTACCESS',	'A place where you want to store the password file. This should be a directory which is not web-accessible. Use [abovecmsroot] to put in the directory above the cms root directory - this is recommended.' );
define( '_AEC_MI_SET3_HTACCESS',	'Area Name' );
define( '_AEC_MI_SET3_1_HTACCESS',	'The name of the protected Area' );
define( '_AEC_MI_SET4_HTACCESS',	'use md5' );
define( '_AEC_MI_SET4_1_HTACCESS',	'<strong>Important!</strong> If you want to use this MI to restrict folders with apache, you have to use crypt - so just leave this at NO. If you want to use a different software which uses htaccess/htuser files (like an icecast server for instance), set this to yes and it will use standard md5 encryption.' );
define( '_AEC_MI_SET5_HTACCESS',	'Rebuild htaccess' );
define( '_AEC_MI_SET5_1_HTACCESS',	'If you changed something important or lost your htaccess file, this option will rebuild your whole htaccess files by looking for each plan that has this MI applied and then add each user that uses one of these plans to the file.' );

//affiliate PRO
define( '_AEC_MI_NAME_AFFPRO',		'AffiliatePRO' );
define( '_AEC_MI_DESC_AFFPRO',		'Connect your AEC sales to AffiliatePRO' );
define( '_AEC_MI_SET1_AFFPRO',		'Additional Info' );
define( '_AEC_MI_SET1_1_AFFPRO',	'Any additional info to be passed to AffiliatePRO' );
define( '_AEC_MI_SET2_AFFPRO',		'Affiliate PRO URL' );
define( '_AEC_MI_SET2_1_AFFPRO',	'Enter the AffiliatePRO Url (without the http://) that points to your AffiliatePRO installation.' );
define( '_AEC_MI_SET3_AFFPRO',		'AffiliatePRO Group ID' );
define( '_AEC_MI_SET3_1_AFFPRO',	'Enter the Affiliate PRO product group identity to be used to calculate commission.' );

// docman
define( '_AEC_MI_NAME_DOCMAN',		'DocMan' );
define( '_AEC_MI_DESC_DOCMAN',		'Choose the amount of files a user can download and what DocMan group should be assigned to the user account' );
define( '_AEC_MI_SET1_DOCMAN',		'Amount of listings' );
define( '_AEC_MI_SET2_DOCMAN',		'Input the amount of listings you want as a overwriting set for this call' );
define( '_AEC_MI_SET3_DOCMAN',		'Set DM-group' );
define( '_AEC_MI_SET3_1_DOCMAN',	'Choose Yes if you want this MI to set the DocMan Group when it is called.' );
define( '_AEC_MI_SET4_DOCMAN',		'DM-Group' );
define( '_AEC_MI_SET4_1_DOCMAN',	'The DocMan group that you want the user to be in.' );
define( '_AEC_MI_SET5_DOCMAN',		'Set DM-group expiration' );
define( '_AEC_MI_SET5_1_DOCMAN',	'Choose Yes if you want this MI to set the DocMan Group when the calling payment plan expires.' );
define( '_AEC_MI_SET6_DOCMAN',		'Expiration group' );
define( '_AEC_MI_SET6_1_DOCMAN',	'The DocMan group that you want the user to be in when the subscription runs out.' );
define( '_AEC_MI_HACK1_DOCMAN',		'Build in a downloads restriction for DocMan, to be used with Micro Integrations.' );

// email
define( '_AEC_MI_NAME_EMAIL',		'Email' );
define( '_AEC_MI_DESC_EMAIL',		'Send an Email to one or more adresses on application or expiration of the subscription' );
define( '_AEC_MI_SET1_EMAIL',		'Sender E-Mail' );
define( '_AEC_MI_SET1_1_EMAIL',		'Sender E-Mail Address' );
define( '_AEC_MI_SET2_EMAIL',		'Sender Name' );
define( '_AEC_MI_SET2_1_EMAIL',		'The displayed name of the Sender' );
define( '_AEC_MI_SET3_EMAIL',		'Recipient(s)' );
define( '_AEC_MI_SET3_1_EMAIL',		'Who is to receive this E-Mail? Separate with comma. The rewriting routines explained below will work for this field.' );
define( '_AEC_MI_SET4_EMAIL',		'Subject' );
define( '_AEC_MI_SET4_1_EMAIL',		'Subject of this email' );
define( '_AEC_MI_SET5_EMAIL',		'HTML Encoding' );
define( '_AEC_MI_SET5_1_EMAIL',		'Do you want this email to be HTML encoded? (Make sure that there are not tags in it if you do not want this)' );
define( '_AEC_MI_SET6_EMAIL',		'Text' );
define( '_AEC_MI_SET6_1_EMAIL',		'Text to be sent when the plan is purchased. The rewriting routines explained below will work for this field.' );
define( '_AEC_MI_SET7_EMAIL',		'Expiration Subject' );
define( '_AEC_MI_SET7_1_EMAIL',		'Expiration Subject' );
define( '_AEC_MI_SET8_EMAIL',		'Expiration Text' );
define( '_AEC_MI_SET_8_1_EMAIL',	'Text to be sent when the plan expires. The rewriting routines explained below will work for this field.' );
define( '_AEC_MI_SET9_EMAIL',		'Subject' );
define( '_AEC_MI_SET9_1_EMAIL',		'Pre Expiration Subject' );
define( '_AEC_MI_SET10_EMAIL',		'Pre Expiration Text' );
define( '_AEC_MI_SET10_1_EMAIL',	'Text to be sent when the plan is about to expire (specify when on the previous tab). The rewriting routines explained below will work for this field.' );
define( '_AEC_MI_SET11_EMAIL',		'Rewriting Info' );

// iDevAffiliate
define( '_AEC_MI_NAME_IDEV',		'iDevAffiliate' );
define( '_AEC_MI_DESC_IDEV',		'Connect your sales to iDevAffiliate' );
define( '_AEC_MI_SET1_IDEV',		'Order ID' );
define( '_AEC_MI_SET1_1_IDEV',		'The order number. <br />Automatic mappings:<br />\'[invoice]\' = invoice number<br />\'[planid]\' = plan id<br />\'[userid]\' = user id' );
define( '_AEC_MI_SET2_IDEV',		'Order Subtotal' );
define( '_AEC_MI_SET2_1_IDEV',		'The amount spent on this order. Type \'[auto]\' to assign the invoice amount.' );
define( '_AEC_MI_DIV1_IDEV',		'an access has been made' );
define( '_AEC_MI_DIV2_IDEV',		'key is' );
define( '_AEC_MI_DIV3_IDEV',		'and value =' );

// MosetsTree
define( '_AEC_MI_NAME_MOSETS',		'MosetsTree' );
define( '_AEC_MI_DESC_MOSETS',		'Restrict the amount of listings a user can publish' );
define( '_AEC_MI_SET1_MOSETS',		'Amount of listings' );
define( '_AEC_MI_SET1_1_MOSETS',	'Input the amount of listings you want as a overwriting set for this call' );
define( '_AEC_MI_HACK1_MOSETS',		'No Listings left' );
define( '_AEC_MI_HACK2_MOSETS',		'Registration Required' );
define( '_AEC_MI_HACK3_MOSETS',		'Prevent user from creating a new listing if he or she has run out of granted listings' );
define( '_AEC_MI_HACK4_MOSETS',		'Prevent user from saving a new listing if he or she has run out of granted listings' );
define( '_AEC_MI_DIV1_MOSETS',		'You have <strong>%s</strong> listings left in our directory.' );

// MySQL Query
define( '_AEC_MI_NAME_MYSQL',		'mySQL Query' );
define( '_AEC_MI_DESC_MYSQL',		'Specify a mySQL query that should be carried out with this subscription or on its expiration' );
define( '_AEC_MI_SET1_MYSQL',		'Query' );
define( '_AEC_MI_SET1_1_MYSQL',		'MySQL query to be carried out when this MI is called.' );
define( '_AEC_MI_SET2_MYSQL',		'Expiration Query' );
define( '_AEC_MI_SET2_1_MYSQL',		'MySQL query to be carried out when this MI is called on expiration.' );
define( '_AEC_MI_SET3_MYSQL',		'Pre Expiration Query' );
define( '_AEC_MI_SET3_1_MYSQL',		'MySQL query to be carried out when this MI is called before expiration (specify when on the first tab)' );
define( '_AEC_MI_SET4_MYSQL',		'Rewriting Info' );

// reMOSitory
define( '_AEC_MI_NAME_REMOS',		'reMOSitory' );
define( '_AEC_MI_DESC_REMOS',		'Choose the amount of files a user can download and what reMOSitory group should be assigned to the user account' );
define( '_AEC_MI_SET1_REMOS',		'Amount of listings' );
define( '_AEC_MI_SET1_1_REMOS',		'Input the amount of listings you want as a overwriting set for this call' );
define( '_AEC_MI_SET2_REMOS',		'Set group' );
define( '_AEC_MI_SET2_1_REMOS',		'Choose Yes if you want this MI to set the Shopper Group when it is called.' );
define( '_AEC_MI_SET3_REMOS',		'Group' );
define( '_AEC_MI_SET3_1_REMOS',		'The ReMOSitory group that you want the user to be in.' );
define( '_AEC_MI_SET4_REMOS',		'Set group Expiration' );
define( '_AEC_MI_SET4_1_REMOS',		'Choose Yes if you want this MI to set the ReMOSitory Group when the calling payment plan expires' );
define( '_AEC_MI_SET5_REMOS',		'Expiration group' );
define( '_AEC_MI_SET5_1_REMOS',		'The ReMOSitory group that you want the user to be in when the subscription runs out.' );
define( '_AEC_MI_HACK1_REMOS',		'No Credits' );
define( '_AEC_MI_HACK2_REMOS',		'Build in a downloads restriction for reMOSitory, to be used with Micro Integrations.' );

// VirtueMart
define( '_AEC_MI_NAME_VIRTM',		'VirtueMart' );
define( '_AEC_MI_DESC_VIRTM',		'Choose the VM usergroup this user should be applied to' );
define( '_AEC_MI_SET1_VIRTM',		'Set Shopper group' );
define( '_AEC_MI_SET1_1_VIRTM',		'Choose Yes if you want this MI to set the Shopper Group when it is called.' );
define( '_AEC_MI_SET2_VIRTM',		'Shopper group' );
define( '_AEC_MI_SET2_1_VIRTM',		'The VirtueMart Shopper group that you want the user to be in.' );
define( '_AEC_MI_SET3_VIRTM',		'Set group Expiration' );
define( '_AEC_MI_SET3_1_VIRTM',		'Choose Yes if you want this MI to set the Shopper Group when the calling payment plan expires.' );
define( '_AEC_MI_SET4_VIRTM',		'Expiration Shopper group' );
define( '_AEC_MI_SET4_1_VIRTM',		'The VirtueMart Shopper group that you want the user to be in when the subscription runs out.' );
?>