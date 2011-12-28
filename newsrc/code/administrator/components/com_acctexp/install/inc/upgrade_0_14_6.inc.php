<?php
/**
 * @version $Id: upgrade_0_14_6.inc.php
 * @package AEC - Account Control Expiration - Membership Manager
 * @subpackage Install Includes
 * @copyright 2011 Copyright (C) David Deutsch
 * @author David Deutsch <skore@valanx.org> & Team AEC - http://www.valanx.org
 * @license GNU/GPL v.2 http://www.gnu.org/licenses/old-licenses/gpl-2.0.html or, at your option, any later version
 */

$ktables = array('metauser', 'displaypipeline', 'invoices', 'cart', 'event', 'subscr', 'couponsxuser');

foreach ( $ktables as $table ) {
	$db->setQuery("ALTER TABLE #__acctexp_" . $table . " ADD KEY (`userid`)");
	if ( !$db->query() ) {
		$errors[] = array( $db->getErrorMsg(), $query );
	}
}

?>