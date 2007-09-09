<?php
/**
 * @version $Id: mi_htaccess.php 16 2007-07-01 12:07:07Z mic $
 * @package AEC - Account Control Expiration - Subscription component for Joomla! OS CMS
 * @subpackage Micro Integrations - .htaccess
 * @copyright Copyright (C) 2007, All Rights Reserved, David Deutsch
 * @author David Deutsch <skore@skore.de> & Team AEC - http://www.gobalnerd.org
 * @license GNU/GPL v.2 http://www.gnu.org/copyleft/gpl.html
 */

defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );

class mi_md5password
{
	function Info()
	{
		$info = array();
		$info['name'] = _AEC_MI_NAME_MD5PASSWORD;
		$info['desc'] = _AEC_MI_DESC_MD5PASSWORD;

		return $info;
	}

	function mi_md5password()
	{
	}

	function checkInstallation()
	{
		global $database, $mosConfig_dbprefix;

		$tables	= array();
		$tables	= $database->getTableList();

		return in_array( $mosConfig_dbprefix .'_acctexp_mi_htaccess_md5pw', $tables );
	}

	function install()
	{
		global $database;

		$query = 'CREATE TABLE IF NOT EXISTS `#__acctexp_mi_htaccess_md5pw`'
		. ' (`userid` int(11) NOT NULL,'
		. '`md5pw` varchar(255) NOT NULL,'
		. ' PRIMARY KEY (`userid`)'
		. ')'
		;
		$database->setQuery( $query );
		$database->query();

		$query = 'SELECT userid, password'
		. ' FROM #__users'
		;
		$database->setQuery( $query );
		$users = $database->loadObjectList();

		foreach ( $users as $objuser ) {
			$md5pw = new md5pw( $database );
			$md5pw->load(0);
			$md5pw->userid = $objuser->id;
			$md5pw->md5pw = $objuser->password;
			$md5pw->check();
			$md5pw->store();
		}

		return;
	}

	function Settings( $params )
	{
		global $mosConfig_absolute_path;

		$settings = array();

		return $settings;
	}

	function expiration_action( $params, $userid, $plan )
	{
	}

	function action($params, $userid, $plan)
	{
		return true;
	}

	function on_userchange_action( $params, $row, $post, $trace )
	{
		global $database;

		$apachepw = new apachepw( $database );

		if ( $apachepw->load( $row->id ) ) {
			;
		} else {
			$apachepw->load(0);
			$apachepw->userid = $row->id;
		}

		if ( isset( $post['password']) && $post['password'] != '' ) {
			$apachepw->apachepw = crypt( $post['password'] );
			$apachepw->check();
			$apachepw->store();
		} elseif ( !$apwid ) {
			// No new password and no existing password - nothing to be done here
			return;
		}
	}
}

class md5pw extends mosDBTable
{
	/** @var int Primary key */
	var $userid 			= null;
	/** @var string */
	var $md5pw				= null;

	function md5pw( &$db )
	{
		$this->mosDBTable( '#__acctexp_mi_md5password_md5pw', 'id', $db );
	}
}
?>