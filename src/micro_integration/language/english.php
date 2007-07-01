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
?>