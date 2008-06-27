<?php
/**
 * @version $Id: mi_hotproperty.php
 * @package AEC - Account Control Expiration - Subscription component for Joomla! OS CMS
 * @subpackage Micro Integrations - Mosets Hot Property
 * @copyright 2006/2007 Copyright (C) David Deutsch
 * @author David Deutsch <skore@skore.de> & Team AEC - http://www.globalnerd.org
 * @license GNU/GPL v.2 or later - http://www.gnu.org/copyleft/gpl.html
 */

defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );

class mi_hotproperty extends MI
{

	function checkInstallation()
	{
		global $database, $mosConfig_dbprefix;

		$tables	= array();
		$tables	= $database->getTableList();

		return in_array( $mosConfig_dbprefix . '__acctexp_mi_hotproperty', $tables );
	}

	function install()
	{
		global $database;

		$query = 'CREATE TABLE IF NOT EXISTS `#__acctexp_mi_hotproperty` ('
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

	function Settings()
	{
		global $database;

        $settings = array();
		$settings['create_agent']	= array( 'list_yesno' );
		$settings['agent_fields']	= array( 'inputD' );
		$settings['update_agent']	= array( 'list_yesno' );
		$settings['update_afields']	= array( 'inputD' );
		$settings['create_company']	= array( 'list_yesno' );
		$settings['company_fields']	= array( 'inputD' );
		$settings['update_company']	= array( 'list_yesno' );
		$settings['update_cfields']	= array( 'inputD' );
		$settings['add_listings']	= array( 'inputA' );
		$settings['set_listings']	= array( 'inputA' );
		$settings['publish_all']	= array( 'list_yesno' );
		$settings['unpublish_all']	= array( 'list_yesno' );

		$settings = $this->autoduplicatesettings( $settings );

		$settings['assoc_company']	= array( 'list_yesno' );
		$settings['rebuild']		= array( 'list_yesno' );
		$settings['remove']			= array( 'list_yesno' );

		$rewriteswitches			= array( 'cms', 'user', 'expiration', 'subscription', 'plan', 'invoice' );
		$settings['rewriteInfo']	= array( 'fieldset', _AEC_MI_SET11_EMAIL, AECToolbox::rewriteEngineInfo( $rewriteswitches ) );

		return $settings;
	}

	function Defaults()
	{
		$defaults = array();
		$defaults['agent_fields']	= "user=[[user_id]]\ncb_id=[[user_id]]\nname=[[user_name]]\nemail=[[user_email]]\ncompany=\nneed_approval=1";
		$defaults['company_fields']	= "name=[[user_name]]\naddress=\nsuburb=\ncountry=\nstate=\npostcode=\ntelephone=\nfax=\nwebsite=\ncb_id=[[user_id]]\nemail=[[user_email]]";

		return $defaults;
	}

	function detect_application()
	{
		global $mosConfig_absolute_path;

		return is_dir( $mosConfig_absolute_path . '/components/com_mtree' );
	}

	function hacks()
	{
		global $mosConfig_absolute_path;

		$hacks = array();

		$edithack = '// AEC HACK hotproperty1 START' . "\n"
		. 'if (!$link_id) {' . "\n"
		. 'global $mosConfig_absolute_path;' . "\n"
		. 'include_once( $mosConfig_absolute_path . \'/components/com_acctexp/acctexp.class.php\' );' . "\n"
		. 'include_once( $mosConfig_absolute_path . \'/components/com_acctexp/micro_integration/mi_hotproperty.php\' );' . "\n"
		. '$mi_mosetshandler = new aec_hotproperty( $database );' . "\n"
		. '$mi_mosetshandler->loadUserID( $my->id );' . "\n"
		. 'if( $mi_mosetshandler->id ) {' . "\n"
		. 'if( !$mi_mosetshandler->hasListingsLeft() ) {' . "\n"
		. 'echo "' . _AEC_MI_HACK1_MOSETS . '";' . "\n"
		. 'return;' . "\n"
		. '}' . "\n"
		. '} else {' . "\n"
		. 'echo "' . _AEC_MI_HACK2_MOSETS . '";' . "\n"
		. 'return;' . "\n"
		. '}' . "\n"
		. '}' . "\n"
		. '// AEC HACK hotproperty1 END' . "\n"
		;

		$edithack2 = '// AEC HACK hotproperty2 START' . "\n"
		. 'if ($row->link_approved == 1) {' . "\n"
		. 'global $mosConfig_absolute_path;' . "\n"
		. 'include_once( $mosConfig_absolute_path . \'/components/com_acctexp/acctexp.class.php\' );' . "\n"
		. 'include_once( $mosConfig_absolute_path . \'/components/com_acctexp/micro_integration/mi_hotproperty.php\' );' . "\n"
		. '$mi_mosetshandler = new aec_hotproperty( $database );' . "\n"
		. '$mi_mosetshandler->loadUserID( $my->id );' . "\n"
		. 'if( $mi_mosetshandler->id ) {' . "\n"
		. 'if( !$mi_mosetshandler->hasListingsLeft() ) {' . "\n"
		. '$mi_mosetshandler->useListing();' . "\n"
		. '} else {' . "\n"
		. 'echo "' . _AEC_MI_HACK1_MOSETS . '";' . "\n"
		. 'return;' . "\n"
		. '}' . "\n"
		. '} else {' . "\n"
		. 'echo "' . _AEC_MI_HACK2_MOSETS . '";' . "\n"
		. 'return;' . "\n"
		. '}' . "\n"
		. '}' . "\n"
		. '// AEC HACK hotproperty2 END' . "\n"
		;

		$edithack3 = '// AEC HACK adminhotproperty3 START' . "\n"
		. 'global $mosConfig_absolute_path;' . "\n"
		. 'include_once( $mosConfig_absolute_path . \'/components/com_acctexp/acctexp.class.php\' );' . "\n"
		. 'include_once( $mosConfig_absolute_path . \'/components/com_acctexp/micro_integration/mi_hotproperty.php\' );' . "\n"
		. '$mi_mosetshandler = new aec_hotproperty( $database );' . "\n"
		. '$mi_mosetshandler->loadUserID( $mtLinks->user_id );' . "\n"
		. 'if( $mi_mosetshandler->id ) {' . "\n"
		. 'if( !$mi_mosetshandler->hasListingsLeft() ) {' . "\n"
		. '$mi_mosetshandler->useListing();' . "\n"
		. '} else {' . "\n"
		. 'continue;' . "\n"
		. '}' . "\n"
		. '} else {' . "\n"
		. 'continue;' . "\n"
		. '}' . "\n"
		. '// AEC HACK adminhotproperty3 END' . "\n"
		;

		$n = 'hotproperty1';
		$hacks[$n]['name']				=	'hotproperty.php #1';
		$hacks[$n]['desc']				=	_AEC_MI_HACK3_HOTPROPERTY;
		$hacks[$n]['type']				=	'file';
		$hacks[$n]['filename']			=	$mosConfig_absolute_path . '/components/com_hotproperty/mtree.php';
		$hacks[$n]['read']				=	'# OK, you can edit';
		$hacks[$n]['insert']			=	$edithack . "\n"  . $hacks[$n]['read'];

		$n = 'hotproperty2';
		$hacks[$n]['name']				=	'hotproperty.php #2';
		$hacks[$n]['desc']				=	_AEC_MI_HACK4_HOTPROPERTY;
		$hacks[$n]['type']				=	'file';
		$hacks[$n]['filename']			=	$mosConfig_absolute_path . '/components/com_hotproperty/mtree.php';
		$hacks[$n]['read']				=	'$row->updateLinkCount( 1 );';
		$hacks[$n]['insert']			=	$edithack2 . "\n"  . $hacks[$n]['read'];

		$n = 'adminhotproperty3';
		$hacks[$n]['name']				=	'admin.hotproperty.php #3';
		$hacks[$n]['desc']				=	_AEC_MI_HACK5_HOTPROPERTY;
		$hacks[$n]['type']				=	'file';
		$hacks[$n]['filename']			=	$mosConfig_absolute_path . '/administrator/components/com_hotproperty/admin.mtree.php';
		$hacks[$n]['read']				=	'if ( $mtLinks->link_approved == 0 ) {';
		$hacks[$n]['insert']			=	$hacks[$n]['read'] . "\n" . $edithack3;

		return $hacks;
	}

	function profile_info( $userid )
	{
		global $database;

		$mi_hphandler = new aec_hotproperty( $database );
		$id = $mi_hphandler->getIDbyUserID( $userid );

		if ( $id ) {
			$mi_hphandler->load( $id );
			return '<p>' . sprintf( _AEC_MI_DIV1_HOTPROPERTY, $mi_hphandler->getListingsLeft() ) . '</p>';
		} else {
			return '';
		}
	}

	function relayAction( $request, $area )
	{
		$agent = null;
		$company = null;

		if ( $this->settings['create_agent'.$area] ){
			if ( !empty( $this->settings['agent_fields'.$area] ) ) {
				$agent = $this->createAgent( $this->settings['agent_fields'.$area], $request );
			}
		}

		if ( $agent === false ) {
			return false;
		}

		if ( $this->settings['update_agent'.$area] ){
			if ( !empty( $this->settings['update_afields'.$area] ) ) {
				if ( empty( $agent ) ) {
					$agent = $this->agentExists( $metaUser->userid );
				}

				if ( !empty( $agent ) ) {
					$agent = $this->update( 'agents', 'user', $this->settings['update_afields'.$area], $request );
				}
			}
		}

		if ( $agent === false ) {
			return false;
		}

		if ( $this->settings['create_company'.$area] ){
			if ( !empty( $this->settings['company_fields'.$area] ) ) {
				$company = $this->createCompany( $this->settings['company_fields'.$area], $this->settings['assoc_company'], $request );
			}
		}

		if ( $company === false ) {
			return false;
		}

		if ( $this->settings['update_company'.$area] ){
			if ( !empty( $this->settings['update_cfields'.$area] ) ) {
				if ( empty( $company ) ) {
					$company = $this->companyExists( $metaUser->userid );
				}

				if ( !empty( $company ) ) {
					$company = $this->update( 'companies', 'cb_id', $this->settings['update_cfields'.$area], $request );
				}
			}
		}

		if ( $this->settings['unpublish_all'.$area] ) {
			$this->unpublishProperties( $agent );
		}

		if ( $this->settings['publish_all'.$area] ) {
			$this->publishProperties( $agent );
		}

		if ( !empty( $this->settings['set_listings'.$area] ) || !empty( $this->settings['set_listings'.$area] ) ) {
			global $database;

			$mi_hphandler = new mosetstree( $database );
			$id = $mi_hphandler->getIDbyUserID( $request->metaUser->userid );
			$mi_id = $id ? $id : 0;
			$mi_hphandler->load( $mi_id );

			if ( !$mi_id ){
				$mi_hphandler->userid = $request->metaUser->userid;
				$mi_hphandler->active = 1;
			}

			if ( $this->settings['set_listings'] ) {
				$mi_hphandler->setListings( $this->settings['set_listings'] );
			} elseif ( $this->settings['add_listings'] ) {
				$mi_hphandler->addListings( $this->settings['add_listings'] );
			}

			$mi_hphandler->check();
			$mi_hphandler->store();
		}


		if ( $company === false ) {
			return false;
		} else {
			return true;
		}
	}

	function agentExists( $userid )
	{
		global $database;

		$query = 'SELECT user FROM #__hp_agents'
				. ' WHERE user = \'' . $userid . '\''
				;
		$database->setQuery( $query );
		$id = $database->loadResult();

		if ( $id ) {
			return $id;
		} else {
			return false;
		}
	}

	function createAgent( $fields, $request )
	{
		global $database;

		$check = $this->agentExists( $request->metaUser->userid );

		if ( !empty( $check ) ) {
			return null;
		}

		$fields = AECToolbox::rewriteEngineRQ( $fields, $request );

		$fieldlist = explode( "\n", $fields );

		$keys = array();
		$values = array();
		foreach ( $fieldlist as $content ) {
			$c = explode( '=', $content, 2 );

			if ( !empty( $c[0] ) ) {
				$keys[] = trim( $c[0] );
				$values[] = trim( $c[1] );
			}
		}

		$query = 'INSERT INTO #__hp_agents'
				. ' (' . implode( ',', $keys ) . ')'
				. ' VALUES (\'' . implode( '\',\'', $values ) . '\')'
				;
		$database->setQuery( $query );
		$result = $database->query();

		if ( $result ) {
			return true;
		} else {
			$this->setError( $database->getErrorMsg() );
			return false;
		}
	}

	function companyExists( $userid )
	{
		global $database;

		$query = 'SELECT user FROM #__hp_companies'
				. ' WHERE cb_id = \'' . $userid . '\''
				;
		$database->setQuery( $query );
		$id = $database->loadResult();

		if ( $id ) {
			return $id;
		} else {
			return false;
		}
	}

	function createCompany( $fields, $assoc, $request )
	{
		global $database;

		$check = $this->agentExists( $request->metaUser->userid );
		if ( !empty( $check ) ) {
			return null;
		}

		$fields = AECToolbox::rewriteEngineRQ( $fields, $request );

		$fieldlist = explode( "\n", $fields );

		$keys = array();
		$values = array();
		foreach ( $fieldlist as $content ) {
			$c = explode( '=', $content, 2 );

			if ( !empty( $c[0] ) ) {
				$keys[] = trim( $c[0] );
				$values[] = trim( $c[1] );
			}
		}

		$query = 'INSERT INTO #__hp_companies'
				. ' (' . implode( ',', $keys ) . ')'
				. ' VALUES (\'' . implode( '\',\'', $values ) . '\')'
				;
		$database->setQuery( $query );
		$result = $database->query();

		if ( $result ) {
			if ( $assoc ) {
				$query = 'SELECT count(*)'
						. ' FROM #__hp_companies'
						;
				$database->setQuery( $query );
				$result = $database->query();

				if ( $result ) {
					$query = 'UPDATE #__hp_agents'
							. ' SET company = \'' . $result . '\''
							. ' WHERE cb_id = \'' . $request->metaUser->userid . '\''
							;

					$database->setQuery( $query );
					if ( $database->query() ) {
						return true;
					}
				}
			} else {
				return true;
			}
		}

		$this->setError( $database->getErrorMsg() );
		return false;
	}

	function update( $table, $id, $fields, $request )
	{
		global $database;

		$fields = AECToolbox::rewriteEngineRQ( $fields, $request );

		$fieldlist = explode( "\n", $fields, 2 );

		$set = array();
		foreach ( $fieldlist as $content ) {
			$c = explode( '=', $content, 2 );

			if ( !empty( $c[0] ) ) {
				$set[] = '`' . trim( $c[0] ) . '` = \'' . trim( $c[1] ) . '\'';
			}
		}

		$query = 'UPDATE #__hp_' . $table
				. ' SET ' . implode( ', ', $set )
				. ' WHERE ' . $id . ' = \'' . $request->metaUser->userid . '\''
				;

		$database->setQuery( $query );
		if ( $database->query() ) {
			return true;
		} else {
			$this->setError( $database->getErrorMsg() );
			return false;
		}
	}

	function publishProperties( $agentid )
	{
		global $database;

		$query = 'UPDATE #__hp_properties'
				. ' SET `published` = \'1\''
				. ' WHERE `agent` = \'' . $agentid . '\''
				;
		$database->setQuery( $query );
		if ( $database->query() ) {
			return true;
		} else {
			$this->setError( $database->getErrorMsg() );
			return false;
		}
	}

	function unpublishProperties( $agentid )
	{
		global $database;

		$query = 'UPDATE #__hp_properties'
				. ' SET `published` = \'0\''
				. ' WHERE `agent` = \'' . $agentid . '\''
				;
		$database->setQuery( $query );
		if ( $database->query() ) {
			return true;
		} else {
			$this->setError( $database->getErrorMsg() );
			return false;
		}
	}

}

class aec_hotproperty extends mosDBTable
{
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

	function aec_hotproperty( &$db )
	{
		global $mainframe;

		$langPathMI = $mainframe->getCfg( 'absolute_path' ) . '/components/com_acctexp/micro_integration/language/';
		if ( file_exists( $langPathMI . $mainframe->getCfg( 'lang' ) . '.php' ) ) {
			include_once( $langPathMI . $mainframe->getCfg( 'lang' ) . '.php' );
		} else {
			include_once( $langPathMI . 'english.php' );
		}

		$this->mosDBTable( '#__acctexp_mi_hotproperty', 'id', $db );
	}

	function getIDbyUserID( $userid )
	{
		global $database;

		$query = 'SELECT `id`'
				. ' FROM #__acctexp_mi_hotproperty'
				. ' WHERE `userid` = \'' . $userid . '\''
				;
		$database->setQuery( $query );
		return $database->loadResult();
	}

	function loadUserID( $userid )
	{
		$id = $this->getIDbyUserID( $userid );
		$this->load( $id );
	}

	function is_active()
	{
		if( $this->active ) {
			return true;
		}else{
			return false;
		}
	}

	function getListingsLeft()
	{
		$listings_left = $this->granted_listings - $this->used_listings;
		return $listings_left;
	}

	function hasListingsLeft()
	{
		if( $this->getListingsLeft() > 0 ) {
			return true;
		}else{
			return false;
		}
	}

	function useListing()
	{
		if( $this->hasListingsLeft() && $this->is_active() ) {
			$this->used_listings++;
			$this->check();
			$this->store();
			return true;
		}else{
			return false;
		}
	}

	function setListings( $set )
	{
		$this->granted_listings = $set;
	}

	function addListings( $add )
	{
		$this->granted_listings += $add;
	}
}
?>