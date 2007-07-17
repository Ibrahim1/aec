<?php
/**
 * @version $Id: mi_idevaffiliate.php 16 2007-07-02 13:29:29Z mic $
 * @package AEC - Account Control Expiration - Subscription component for Joomla! OS CMS
 * @subpackage Micro Integrations - Email
 * @copyright 2006/2007 Copyright (C) David Deutsch
 * @author David Deutsch <skore@skore.de> & Team AEC - http://www.gobalnerd.org
 * @license GNU/GPL v.2 http://www.gnu.org/copyleft/gpl.html
 */

defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );

class mi_mosets_tree {

	function Info () {
		$info = array();
		$info['name'] = _AEC_MI_NAME_MOSETS;
		$info['desc'] = _AEC_MI_DESC_MOSETS;

		return $info;
	}

	function checkInstallation () {
		global $database, $mosConfig_dbprefix;

		$tables	= array();
		$tables	= $database->getTableList();

		return in_array( $mosConfig_dbprefix . '_acctexp_mi_mosetstree', $tables );
	}

	function install () {
		global $database;

		$query = 'CREATE TABLE IF NOT EXISTS `#__acctexp_mi_mosetstree` ('
		. '`id` int(11) NOT NULL auto_increment,'
		. '`userid` int(11) NOT NULL,'
		. '`active` int(4) NOT NULL default \'1\','
		. '`granted_listings` int(11) NULL,'
		. '`used_listings` int(11) NULL,'
		. '`params` text NULL,'
		. ' PRIMARY KEY (`id`)'
		. ')'
		;
		$database->setQuery( $query );
		$database->query();
		return;
	}

	function Settings ( $params ) {
		// field type; name; variable value, description, extra (variable name)

		$settings = array();
		$settings['add_listings'] = array( 'inputA' );
		$settings['set_listings'] = array( 'inputA' );

		return $settings;
	}

	function expiration_action( $params, $userid, $plan ) {
		global $database;

		$mi_mosetshandler = new mosetstree( $database );
		$id = $mi_mosetshandler->getIDbyUserID( $userid );

		$mi_mosetshandler->load( $id );
		$mi_mosetshandler->active = 0;

		$mi_mosetshandler->check();
		$mi_mosetshandler->store();

		return true;
	}

	function action( $params, $userid, $plan ) {
		global $database;

		$mi_mosetshandler = new mosetstree( $database );
		$id = $mi_mosetshandler->getIDbyUserID( $userid );
		$mi_id = $id ? $id : 0;
		$mi_mosetshandler->load( $mi_id );

		if( !$mi_id ) {
			$mi_mosetshandler->userid = $userid;
			$mi_mosetshandler->active = 1;
		}

		if( $params['set_listings'] ) {
			$mi_mosetshandler->setListings( $params['set_listings'] );
		}elseif( $params['add_listings'] ) {
			$mi_mosetshandler->addListings( $params['add_listings'] );
		}

		$mi_mosetshandler->check();
		$mi_mosetshandler->store();

		return true;
	}

	function detect_application () {
		global $mosConfig_absolute_path;

		return is_dir( $mosConfig_absolute_path . '/components/com_mtree' );
	}
	
	function hacks () {
		global $mosConfig_absolute_path;

		$hacks = array();

		$edithack = '// AEC HACK mtree1 START' . "\n"
		. 'if (!$link_id) {' . "\n"
		. 'include_once( $mosConfig_absolute_path . \'/components/com_acctexp/micro_integration/mi_mosets_tree.php\' );' . "\n"
		. '$mi_mosetshandler = new mosetstree( $database );' . "\n"
		. '$mi_mosetshandler->loadUserID( $my->id );' . "\n"
		. 'if( $mi_mosetshandler->id ) {' . "\n"
		. 'if( !$mi_mosetshandler->hasListingsLeft() ) {' . "\n"
		. 'echo "' . _AEC_MI_HACK1_MOSETS . '";' . "\n"
		. 'return;' . "\n"
		. '}' . "\n"
		. '}else{' . "\n"
		. 'echo "' . _AEC_MI_HACK2_MOSETS . '";' . "\n"
		. 'return;' . "\n"
		. '}' . "\n"
		. '// AEC HACK mtree1 END' . "\n"
		;

		$edithack2 = '// AEC HACK mtree2 START' . "\n"
		. 'if ($row->link_approved == 1) {' . "\n"
		. 'include_once( $mosConfig_absolute_path . \'/components/com_acctexp/micro_integration/mi_mosets_tree.php\' );' . "\n"
		. '$mi_mosetshandler = new mosetstree( $database );' . "\n"
		. '$mi_mosetshandler->loadUserID( $my->id );' . "\n"
		. 'if( $mi_mosetshandler->id ) {' . "\n"
		. 'if( !$mi_mosetshandler->hasListingsLeft() ) {' . "\n"
		. '$mi_mosetshandler->useListing();' . "\n"
		. '}else{' . "\n"
		. 'echo "' . _AEC_MI_HACK1_MOSETS . '";' . "\n"
		. 'return;' . "\n"
		. '}' . "\n"
		. '}else{' . "\n"
		. 'echo "' . _AEC_MI_HACK2_MOSETS . '";' . "\n"
		. 'return;' . "\n"
		. '}' . "\n"
		. '// AEC HACK mtree2 END' . "\n"
		;

		$edithack3 = '// AEC HACK adminmtree3 START' . "\n"
		. 'include_once( $mosConfig_absolute_path . \'/components/com_acctexp/micro_integration/mi_mosets_tree.php\' );' . "\n"
		. '$mi_mosetshandler = new mosetstree( $database );' . "\n"
		. '$mi_mosetshandler->loadUserID( $mtLinks->user_id );' . "\n"
		. 'if( $mi_mosetshandler->id ) {' . "\n"
		. 'if( !$mi_mosetshandler->hasListingsLeft() ) {' . "\n"
		. '$mi_mosetshandler->useListing();' . "\n"
		. '}else{' . "\n"
		. 'continue;' . "\n"
		. '}' . "\n"
		. '}else{' . "\n"
		. 'continue;' . "\n"
		. '}' . "\n"
		. '// AEC HACK adminmtree3 END' . "\n"
		;

		$n = 'mtree1';
		$hacks[$n]['name']				=	'mtree.php #1';
		$hacks[$n]['desc']				=	_AEC_MI_HACK3_MOSETS;
		$hacks[$n]['type']				=	'file';
		$hacks[$n]['filename']			=	$mosConfig_absolute_path . '/components/com_mtree/mtree.php';
		$hacks[$n]['read']				=	'# OK, you can edit';
		$hacks[$n]['insert']			=	$edithack . "\n"  . $hacks[$n]['read'];

		$n = 'mtree2';
		$hacks[$n]['name']				=	'mtree.php #2';
		$hacks[$n]['desc']				=	_AEC_MI_HACK4_MOSETS;
		$hacks[$n]['type']				=	'file';
		$hacks[$n]['filename']			=	$mosConfig_absolute_path . '/components/com_mtree/mtree.php';
		$hacks[$n]['read']				=	'# Modification to existing record';
		$hacks[$n]['insert']			=	$edithack2 . "\n"  . $hacks[$n]['read'];

		$n = 'adminmtree3';
		$hacks[$n]['name']				=	'admin.mtree.php #3';
		$hacks[$n]['desc']				=	_AEC_MI_HACK5_MOSETS;
		$hacks[$n]['type']				=	'file';
		$hacks[$n]['filename']			=	$mosConfig_absolute_path . '/administrator/components/com_mtree/admin.mtree.php';
		$hacks[$n]['read']				=	'if ( $mtLinks->link_approved == 0 ) {';
		$hacks[$n]['insert']			=	$hacks[$n]['read'] . "\n" . $edithack3;

		return $hacks;
	}


	function profile_info( $params, $userid ) {
		global $database;

		$mi_mosetshandler = new mosetstree( $database );
		$id = $mi_mosetshandler->getIDbyUserID( $userid );

		if( $id ) {
			$mi_mosetshandler->load( $id );
			return '<p>' . sprintf( _AEC_MI_DIV1_MOSETS, $mi_mosetshandler->getListingsLeft() ) . '</p>';
		}else{
			return '';
		}
	}

}

class mosetstree extends mosDBTable {
	/** @var int Primary key */
	var $id					= null;
	/** @var int */
	var $userid 			= null;
	/** @var int */
	var $active				= null;
	/** @var int */
	var $granted_listings	= null;
	/** @var text */
	var $used_listings		= null;
	/** @var text */
	var $params				= null;

	function mosetstree( &$db ) {
		global $mainframe;

		$langPathMI = $mainframe->getCfg( 'absolute_path' ) . '/components/com_acctexp/micro_integration/language/';
		if( file_exists( $langPathMI . $mainframe->getCfg( 'lang' ) . '.php' ) ) {
			include_once( $langPathMI . $mainframe->getCfg( 'lang' ) . '.php' );
		}else{
			include_once( $langPathMI . 'english.php' );
		}

		$this->mosDBTable( '#__acctexp_mi_mosetstree', 'id', $db );
	}

	function getIDbyUserID( $userid ) {
		global $database;

		$query = 'SELECT id'
		. ' FROM #__acctexp_mi_mosetstree'
		. ' WHERE userid = \'' . $userid . '\''
		;
		$database->setQuery( $query );
		return $database->loadResult();
	}

	function loadUserID( $userid ) {
		$id = $this->getIDbyUserID( $userid );
		$this->load( $id );
	}

	function is_active () {
		if( $this->active ) {
			return true;
		}else{
			return false;
		}
	}

	function getListingsLeft () {
		$listings_left = $this->granted_listings - $this->used_listings;
		return $listings_left;
	}

	function hasListingsLeft () {
		if( $this->getListingsLeft() > 0 ) {
			return true;
		}else{
			return false;
		}
	}

	function useListing () {
		if( $this->hasListingsLeft() && $this->is_active() ) {
			$this->used_listings++;
			$this->check();
			$this->store();
			return true;
		}else{
			return false;
		}
	}

	function setListings( $set ) {
		$this->granted_listings = $set;
	}

	function addListings( $add ) {
		$this->granted_listings += $add;
	}
}
?>