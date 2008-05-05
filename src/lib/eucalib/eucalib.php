<?php
/**
 * @version $Id: eucalib.php
 * @package Eucalib: Component library the for Joomla! CMS
 * @subpackage Abstract Library for Joomla Components
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

if ( !defined( '_EUCA_CFG_LOADED' ) ){
	$require_file = dirname( __FILE__ ).'/eucalib.config.php';

	if( file_exists( $require_file ) ) {
		require_once( $require_file );
	}

	$require_file = dirname( __FILE__ ).'/eucalib.common.php';

	if( !class_exists( 'paramDBTable') ) {
		require_once( $require_file );
	}
}

?>