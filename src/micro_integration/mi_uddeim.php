<?php
/**
 * @version $Id: mi_uddeim.php
 * @package AEC - Account Control Expiration - Membership Manager
 * @subpackage Micro Integrations - UddeIM
 * @copyright 2006-2008 Copyright (C) David Deutsch
 * @author David Deutsch <skore@skore.de> & Team AEC - http://www.valanx.org
 * @license GNU/GPL v.2 http://www.gnu.org/licenses/old-licenses/gpl-2.0.html or, at your option, any later version
 */

// Dont allow direct linking
( defined('_JEXEC') || defined( '_VALID_MOS' ) ) or die( 'Direct Access to this location is not allowed.' );

class mi_uddeim
{
	function Info()
	{
		$info = array();
		$info['name'] = _AEC_MI_NAME_UDDEIM;
		$info['desc'] = _AEC_MI_DESC_UDDEIM;

		return $info;
	}

	function checkInstallation()
	{
		global $database, $mosConfig_dbprefix;

		$tables	= array();
		$tables	= $database->getTableList();

		return in_array( $mosConfig_dbprefix . 'acctexp_mi_uddeim', $tables );
	}

	function detect_application()
	{
		global $mosConfig_absolute_path;

		return is_dir( $mosConfig_absolute_path . '/components/com_uddeim' );
	}

	function install()
	{
		global $database;

		$query = 'CREATE TABLE IF NOT EXISTS `#__acctexp_mi_uddeim` ('
		. '`id` int(11) NOT NULL auto_increment,'
		. '`userid` int(11) NOT NULL,'
		. '`active` int(4) NOT NULL default \'1\','
		. '`granted_messages` int(11) NULL,'
		. '`unlimited_messages` int(3) NULL,'
		. '`used_messages` int(11) NULL,'
		. '`params` text NULL,'
		. ' PRIMARY KEY (`id`)'
		. ')'
		;
		$database->setQuery( $query );
		$database->query();
		return;
	}

	function Settings( $params )
	{
		global $database;

        $settings = array();
		$settings['add_messages']	= array( 'inputA' );
		$settings['set_messages']	= array( 'inputA' );
		$settings['set_unlimited']	= array( 'list_yesno' );

		$settings['unset_unlimited']	= array( 'list_yesno' );
		$settings['remove']			= array( 'list_yesno' );

		return $settings;
	}

	function profile_info( $userid )
	{
		global $database;
		$mi_uddeimhandler = new uddeim_restriction( $database );
		$id = $mi_uddeimhandler->getIDbyUserID( $userid );

		if ( $id ) {
			$mi_uddeimhandler->load( $id );
			if ( $mi_uddeimhandler->active ) {
				$left = $mi_uddeimhandler->getMessagesLeft();
				if ( !$mi_uddeimhandler->used_messages) {
					$used = 0 ;
				} else {
					$used = $mi_uddeimhandler->used_messages;
				}
				$unlimited = $mi_uddeimhandler->unlimited_messages;
				$message = '<p>'.sprintf(_AEC_MI_DIV1_UDDEIM_USED, $used).'</p>';
				if ( $unlimited > 0 ) {
					$message .='<p>' . sprintf( _AEC_MI_DIV1_UDDEIM_REMAINING, _AEC_MI_DIV1_UDDEIM_UNLIMITED ) . '</p>';
				} else {
					$message .= '<p>' . sprintf( _AEC_MI_DIV1_UDDEIM_REMAINING, $left ) . '</p>';
				}
				return $message;
			}
		} else {
			return '';
		}
	}

	function hacks()
	{
		global $mosConfig_absolute_path;

		$hacks = array();

		$messagehack =	'// AEC HACK %s START' . "\n"
		. 'global $my, $mosConfig_absolute_path;' . "\n"
		. 'include( $mosConfig_absolute_path . \'/components/com_acctexp/micro_integration/mi_uddeim.php\');' . "\n\n"
		. '$restrictionhandler = new uddeim_restriction( $database );' . "\n"
		. '$restrict_id = $restrictionhandler->getIDbyUserID( $my->id );' . "\n"
		. 'if($restrictionhandler->active){'. "\n\n"
		. '$restrictionhandler->load( $restrict_id );' . "\n\n"
		. '\tif (!$restrictionhandler->hasMessagesLeft()) {' . "\n"
		. "\t\t" . '$restrictionhandler->noMessagesLeft();' . "\n"
		. '\t}else{' . "\n"
		. "\t\t" . '$restrictionhandler->useMessage();' . "\n"
		. '\t}' . "\n"
		. '}' . "\n"
		. '// AEC HACK %s END' . "\n"
		;

		$n = 'uddeimphp';
		$hacks[$n]['name']				=	'uddeim.php';
		$hacks[$n]['desc']				=	_AEC_MI_HACK1_UDDEIM;
		$hacks[$n]['type']				=	'file';
		$hacks[$n]['filename']			=	$mosConfig_absolute_path . '/components/com_uddeim/uddeim.php';
		$hacks[$n]['read']				=	'// I could have modified this function to process mails to public users but instead of adding';
		$hacks[$n]['insert']			=	sprintf($messagehack, $n, $n) . "\n"  . $hacks[$n]['read'];

		$n = 'pmsuddeimphp';
		$hacks[$n]['name']				=	'pms.uddeim.php';
		$hacks[$n]['desc']				=	_AEC_MI_HACK2_UDDEIM;
		$hacks[$n]['type']				=	'file';
		$hacks[$n]['filename']			=	$mosConfig_absolute_path . '/components/com_comprofiler/plugin/user/plug_pmsuddeim/pms.uddeim.php';
		$hacks[$n]['read']				=	'$adminpath = $this->absolute_path."/administrator/components/com_uddeim";
';
		$hacks[$n]['insert']			=	sprintf($messagehack, $n, $n) . "\n"  . $hacks[$n]['read'];

		return $hacks;
	}

	function expiration_action( $params, $userid, $plan )
	{
		global $database;

		$mi_uddeimhandler = new uddeim_restriction( $database );
		$id = $mi_uddeimhandler->getIDbyUserID( $userid );
		$mi_id = $id ? $id : 0;
		$mi_uddeimhandler->load( $mi_id );


		if ( $mi_id ) {
			if ( $this->settings['unset_unlimited'] ) {
				$mi_uddeimhandler->unlimited_messages = 0 ;
			}
			$mi_uddeimhandler->active = 0;
			$mi_uddeimhandler->check();
			$mi_uddeimhandler->store();
		}

		return true;
	}

	function action( $request )
	{
		global $database;

		$userid = $request->metaUser->userid;

		$mi_uddeimhandler = new uddeim_restriction( $database );
		$id = $mi_uddeimhandler->getIDbyUserID( $userid );
		$mi_id = $id ? $id : 0;
		$mi_uddeimhandler->load( $mi_id );

		if ( !$mi_id ) {
			$mi_uddeimhandler->userid = $userid;
			$mi_uddeimhandler->active = 1;
		}

		$params = $request->params;
		if ( $params['set_messages'] ) {
			$mi_uddeimhandler->setMessages( $params['set_messages'] );
		} elseif ( $params['add_messages'] ) {
			$mi_uddeimhandler->addMessages( $params['add_messages'] );
		}
		if ( $params['set_unlimited'] ) {
			$mi_uddeimhandler->unlimited_messages = true ;
		}

		$mi_uddeimhandler->check();
		$mi_uddeimhandler->store();

		return true;
	}
}

class uddeim_restriction extends mosDBTable {
	/** @var int Primary key */
	var $id						= null;
	/** @var int */
	var $userid 				= null;
	/** @var int */
	var $active					= null;
	/** @var int */
	var $granted_messages		= null;
	/** @var int */
	var $unlimited_messages	= null;
	/** @var text */
	var $used_messages			= null;
	/** @var text */
	var $params					= null;

	function getIDbyUserID( $userid ) {
		global $database;

		$query = 'SELECT `id`'
			. ' FROM #__acctexp_mi_uddeim'
			. ' WHERE `userid` = \'' . $userid . '\''
			;
		$database->setQuery( $query );
		return $database->loadResult();
	}

	function uddeim_restriction( &$db ) {
		$this->mosDBTable( '#__acctexp_mi_uddeim', 'id', $db );
	}

	function is_active()
	{
		if ( $this->active ) {
			return true;
		} else {
			return false;
		}
	}

	function getMessagesLeft()
	{
		if (  $this->unlimited_messages > 0 ) {
			return 'unlimited';
		} else {
			$messages_left = $this->granted_messages - $this->used_messages;
			return $messages_left;
		}
	}

	function hasMessagesLeft()
	{
		$check = $this->getMessagesLeft();

		if ( empty ( $check ) ) {
				return false;
		} elseif  (  is_numeric ($check)  )  {
				if ( $check > 0 ) {
						return true;
				} else {
						return false;
				}
		} elseif ( $check == "unlimited" ) {
				return true;
		}
	}

	function noMessagesLeft()
	{
		if ( !defined( '_AEC_LANG_INCLUDED_MI' ) ) {
			global $mainframe;

			$langPathMI = $mainframe->getCfg( 'absolute_path' ) . '/components/com_acctexp/micro_integration/language/';
			if ( file_exists( $langPathMI . $mainframe->getCfg( 'lang' ) . '.php' ) ) {
				include_once( $langPathMI . $mainframe->getCfg( 'lang' ) . '.php' );
			} else {
				include_once( $langPathMI . 'english.php' );
			}
		}

		mosErrorAlert( _AEC_MI_UDDEIM_NOCREDIT );
	}

	function useMessage()
	{
		if ( $this->hasMessagesLeft() && $this->is_active() ) {
			$this->used_messages++;
			$this->check();
			$this->store();
			return true;
		} else {
			return false;
		}
	}

	function setMessages( $set )
	{
		$this->granted_messages = $set + $this->used_messages;
	}

	function addMessages( $add )
	{

		$this->granted_messages += $add;
	}
}
?>