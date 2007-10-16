<?php
/**
 * @version $Id: eucalib.common.php
 * @package Eucalib: Component library for the Joomla! CMS
 * @subpackage Eucalib Common Files
 * @copyright Copyright (C) 2007 David Deutsch, All Rights Reserved
 * @author David Deutsch <skore@skore.de>
 * @license GNU/GPL v.2 or later http://www.gnu.org/copyleft/gpl.html
 *
 *                         _ _ _
 *                        | (_) |
 *     ___ _   _  ___ __ _| |_| |__
 *    / _ \ | | |/ __/ _` | | | '_ \
 *   |  __/ |_| | (_| (_| | | | |_) |
 *    \___|\__,_|\___\__,_|_|_|_.__/  v1.0
 *
 * The Extremely Useful Component LIBrary will rock your socks. Seriously. Reuse it!
 */

defined( '_VALID_MOS' ) or die( 'Restricted access' );

global $mainframe, $mosConfig_absolute_path, $mosConfig_live_site;

include_once( $mosConfig_absolute_path . '/components/com_acctexp/lib/eucalib/eucalib.php' );

define( '_EUCA_CFG_LOADED', 1 );
define( '_EUCA_APP_SHORTNAME', 'acctexp' );
define( '_EUCA_APP_COMPNAME', 'com_' . _EUCA_APP_SHORTNAME );
define( '_EUCA_BASEDIR', $mosConfig_absolute_path . '/components/' . _EUCA_APP_COMPNAME . '/lib/eucalib' );
define( '_EUCA_APP_COMPDIR', $mosConfig_absolute_path . '/components/' . _EUCA_APP_COMPNAME );
define( '_EUCA_APP_ICONSDIR', $mosConfig_live_site . '/components/' . _EUCA_APP_COMPNAME . '/images/icons' );
define( '_EUCA_APP_ADMINDIR', $mosConfig_absolute_path . '/administrator/components/' . _EUCA_APP_COMPNAME );
define( '_EUCA_APP_ADMINICONSDIR', $mosConfig_live_site . '/administrator/components/' . _EUCA_APP_COMPNAME . '/images/icons' );
define( '_EUCA_APP_ADMINACTIONURL', $mosConfig_live_site . '/administrator/index2.php?option=' . _EUCA_APP_COMPNAME );
?>