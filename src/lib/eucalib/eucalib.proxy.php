<?php
/**
 * @version $Id: eucalib.php
 * @package Eucalib: Component library the Joomla! CMS
 * @subpackage Frontend Proxy to relay to subtask files
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

$task		= trim( mosGetParam( $_REQUEST, 'task', null ) );
$returntask = trim( mosGetParam( $_REQUEST, 'returntask', null ) );

resolveProxy( $task, $returntask );

?>