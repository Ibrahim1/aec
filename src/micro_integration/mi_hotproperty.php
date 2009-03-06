<?php
/**
 * @version $Id: mi_hotproperty.php
 * @package AEC - Account Control Expiration - Membership Manager
 * @subpackage Micro Integrations - Mosets Hot Property
 * @copyright 2006-2008 Copyright (C) David Deutsch
 * @author David Deutsch <skore@skore.de> & Team AEC - http://www.globalnerd.org
 * @license GNU/GPL v.2 http://www.gnu.org/licenses/old-licenses/gpl-2.0.html or, at your option, any later version
 */

// Dont allow direct linking
( defined('_JEXEC') || defined( '_VALID_MOS' ) ) or die( 'Direct Access to this location is not allowed.' );

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
		$settings['set_unlimited']	= array( 'list_yesno' );
		$settings['publish_all']	= array( 'list_yesno' );
		$settings['unpublish_all']	= array( 'list_yesno' );

		$settings = $this->autoduplicatesettings( $settings );

		$settings['add_list_userchoice']		= array( 'list_yesno' );
		$settings['add_list_userchoice_amt']	= array( 'inputD' );
		$settings['add_list_customprice']		= array( 'inputD' );

		$settings['easy_list_userchoice']		= array( 'list_yesno' );
		$settings['easy_list_userchoice_n']		= array( 'inputA' );

		if ( !empty( $this->settings['easy_list_userchoice_n'] ) ) {
	 		$opts = array();
			$opts[0] = mosHTML::makeOption ( "EQ", "Equal to" ); // Should probably be langauge file defined?
			$opts[1] = mosHTML::makeOption ( "LT", "Lesser than" );
			$opts[2] = mosHTML::makeOption ( "GT", "Greater than" );

			for( $i=0; $i<$this->settings['easy_list_userchoice_n']; $i++ ) {
				$settings['lists']['elu_'.$i.'_op']	= mosHTML::selectList( $opts, 'elu_'.$i.'_op', 'size="1"', 'value', 'text', $this->settings['elu_'.$i.'_op'] );

				$settings[] = array( '', 'hr', '' );
				$settings['elu_'.$i.'_op'] = array( 'list', _AEC_MI_HOTPROPERTY_EASYLIST_OP_NAME, _AEC_MI_HOTPROPERTY_EASYLIST_OP_DESC );
				$settings['elu_'.$i.'_no'] = array( 'inputA', _AEC_MI_HOTPROPERTY_EASYLIST_NO_NAME, _AEC_MI_HOTPROPERTY_EASYLIST_NO_DESC );
				$settings['elu_'.$i.'_ch'] = array( 'inputA', _AEC_MI_HOTPROPERTY_EASYLIST_CH_NAME, _AEC_MI_HOTPROPERTY_EASYLIST_CH_DESC );
			}

			$settings[] = array( '', 'hr', '' );
		}

		$settings['assoc_company']	= array( 'list_yesno' );
		$settings['rebuild']		= array( 'list_yesno' );
		$settings['remove']			= array( 'list_yesno' );

		$rewriteswitches			= array( 'cms', 'user', 'expiration', 'subscription', 'plan', 'invoice' );
		$settings['rewriteInfo']	= array( 'fieldset', _AEC_MI_SET11_EMAIL, AECToolbox::rewriteEngineInfo( $rewriteswitches ) );

		return $settings;
	}

	function getMIform()
	{
		global $database;

		$settings = array();

		if ( ( !empty( $this->settings['add_list_userchoice'] ) && !empty( $this->settings['add_list_userchoice_amt'] ) ) || !empty( $this->settings['easy_list_userchoice'] ) ) {
			$groups = explode( ';', $this->settings['add_list_userchoice_amt'] );
			$gr = array();
			foreach ( $groups as $group ) {
				if ( strpos( $group, ',' ) ) {
					$gg = explode( ',', $group );
					$gr[] = mosHTML::makeOption( $gg[0], $gg[1] );
				} else {
					$gr[] = mosHTML::makeOption( $group, $group.' Listings' );
				}
			}

			$settings['hpamt']			= array( 'list', _MI_MI_HOTPROPERTY_USERSELECT_ADDAMOUNT_NAME, _MI_MI_HOTPROPERTY_USERSELECT_ADDAMOUNT_DESC );
			$settings['lists']['hpamt']	= mosHTML::selectList( $gr, 'hpamt', 'size="6"', 'value', 'text', '' );
		} else {
			return false;
		}

		return $settings;
	}

	function modifyPrice( $request )
	{
		if ( !empty( $request->params['hpamt'] ) ) {
			if ( !empty( $this->settings['easy_list_userchoice'] ) && !empty( $this->settings['easy_list_userchoice_n'] ) ) {
				for( $i=0; $i<$this->settings['easy_list_userchoice_n']; $i++ ) {
					switch ( $this->settings['elu_'.$i.'_op'] ) {
						case 'EQ':
							if ( $request->params['hpamt'] == $this->settings['elu_'.$i.'_no'] ) {
								$request->add->price = $this->parseEasyPrice( $request->add->price, $request->params['hpamt'], $this->settings['elu_'.$i.'_ch'] );
							}
							break;
						case 'GT':
							if ( $request->params['hpamt'] > $this->settings['elu_'.$i.'_no'] ) {
								$request->add->price = $this->parseEasyPrice( $request->add->price, $request->params['hpamt'], $this->settings['elu_'.$i.'_ch'] );
							}
							break;
						case 'LT':
							if ( $request->params['hpamt'] < $this->settings['elu_'.$i.'_no'] ) {
								$request->add->price = $this->parseEasyPrice( $request->add->price, $request->params['hpamt'], $this->settings['elu_'.$i.'_ch'] );
							}
							break;
					}
				}

				return true;
			} else {
				$groups = explode( ';', $this->settings['add_list_userchoice_amt'] );

				$discount = 0;
				foreach ( $groups as $group ) {
					if ( strpos( $group, ',' ) ) {
						$gg = explode( ',', $group );

						if ( strpos( '<', $gg[0] ) !== false ) {
							$s = str_replace( '<', '', $gg[0] );
							if ( $request->params['hpamt'] < $s ) {
								$discount = $gg[1];
								continue;
							}
						} elseif ( strpos( '>', $group ) !== false ) {
							$s = str_replace( '>', '', $gg[0] );
							if ( $request->params['hpamt'] > $s ) {
								$discount = $gg[1];
								continue;
							}
						} else {
							if ( $request->params['hpamt'] == $gg[0] ) {
								$discount = $gg[1];
								continue;
							}
						}
					} else {
						return null;
					}
				}

				$cph = new couponHandler();
				if ( $cph->forceload( $discount ) ) {
					$cph->applyCoupon( $request->add->price );
				}

				return true;
			}
		}

		return null;
	}

	function parseEasyPrice( $p, $a, $parse )
	{
		if ( strpos( $parse, 'p' ) !== false ) {
			$parse = str_replace( 'p', $p, $parse );
		}

		if ( strpos( $parse, 'a' ) !== false ) {
			$parse = str_replace( 'a', $a, $parse );
		}

		if ( strpos( $parse, '*' ) !== false ) {
			$pp = explode( '*', $parse );

			return $pp[0] * $pp[1];
		} elseif ( strpos( $parse, '+' )  !== false) {
			$pp = explode( '+', $parse );

			return $pp[0] + $pp[1];
		} elseif ( strpos( $parse, '-' ) !== false ) {
			$pp = explode( '-', $parse );

			return $pp[0] - $pp[1];
		} elseif ( strpos( $parse, '/' ) !== false ) {
			$pp = explode( '/', $parse );

			return $pp[0] / $pp[1];
		} else {
			return $parse;
		}
	}

	function Defaults()
	{
		$defaults = array();
		$defaults['agent_fields']	= "user=[[user_id]]\nname=[[user_name]]\nemail=[[user_email]]\nneed_approval=0";
		$defaults['company_fields']	= "name=[[user_name]]\naddress=\nsuburb=\ncountry=\nstate=\npostcode=\ntelephone=\nfax=\nwebsite=\nemail=[[user_email]]";

		return $defaults;
	}

	function detect_application()
	{
		global $mosConfig_absolute_path;

		return is_dir( $mosConfig_absolute_path . '/components/com_hotproperty' );
	}

	function hacks()
	{
		global $mosConfig_absolute_path;

		$v10 = is_dir( $mosConfig_absolute_path . '/components/com_hotproperty/helpers' );

		$hacks = array();

		$edithack = '// AEC HACK hotproperty1 START' . "\n"
		. 'global $mosConfig_absolute_path;' . "\n"
		. 'include_once( $mosConfig_absolute_path . \'/components/com_acctexp/acctexp.class.php\' );' . "\n"
		. 'include_once( $mosConfig_absolute_path . \'/components/com_acctexp/micro_integration/mi_hotproperty.php\' );' . "\n"
		. '$mi_hphandler = new aec_hotproperty( $database );' . "\n"
		. '$mi_hphandler->loadUserID( $my->id );' . "\n"
		. 'if( $mi_hphandler->id ) {' . "\n"
		. 'if( !$mi_hphandler->hasListingsLeft() ) {' . "\n"
		. 'echo "' . _AEC_MI_HACK1_HOTPROPERTY . '";' . "\n"
		. 'return;' . "\n"
		. '}' . "\n"
		. '} else {' . "\n"
		. 'echo "' . _AEC_MI_HACK2_HOTPROPERTY . '";' . "\n"
		. 'return;' . "\n"
		. '}' . "\n"
		. '// AEC HACK hotproperty1 END' . "\n"
		;

		$edithack2 = '// AEC HACK hotproperty2 START' . "\n"
		. 'global $mosConfig_absolute_path;' . "\n"
		. 'include_once( $mosConfig_absolute_path . \'/components/com_acctexp/acctexp.class.php\' );' . "\n"
		. 'include_once( $mosConfig_absolute_path . \'/components/com_acctexp/micro_integration/mi_hotproperty.php\' );' . "\n"
		. '$mi_hphandler = new aec_hotproperty( $database );' . "\n"
		. '$mi_hphandler->loadUserID( $my->id );' . "\n"
		. 'if( $mi_hphandler->id ) {' . "\n"
		. 'if( $mi_hphandler->hasListingsLeft() ) {' . "\n"
		. '$mi_hphandler->useListing();' . "\n"
		. '} else {' . "\n"
		. 'echo "' . _AEC_MI_HACK1_HOTPROPERTY . '";' . "\n"
		. 'return;' . "\n"
		. '}' . "\n"
		. '} else {' . "\n"
		. 'echo "' . _AEC_MI_HACK2_HOTPROPERTY . '";' . "\n"
		. 'return;' . "\n"
		. '}' . "\n"
		. '// AEC HACK hotproperty2 END' . "\n"
		;

		/*$edithack3 = '// AEC HACK adminhotproperty3 START' . "\n"
		. 'global $mosConfig_absolute_path;' . "\n"
		. 'include_once( $mosConfig_absolute_path . \'/components/com_acctexp/acctexp.class.php\' );' . "\n"
		. 'include_once( $mosConfig_absolute_path . \'/components/com_acctexp/micro_integration/mi_hotproperty.php\' );' . "\n"
		. '$mi_hphandler = new aec_hotproperty( $database );' . "\n"
		. '$mi_hphandler->loadUserID( $mtLinks->user_id );' . "\n"
		. 'if( $mi_hphandler->id ) {' . "\n"
		. 'if( $mi_hphandler->hasListingsLeft() ) {' . "\n"
		. '$mi_hphandler->useListing();' . "\n"
		. '} else {' . "\n"
		. 'continue;' . "\n"
		. '}' . "\n"
		. '} else {' . "\n"
		. 'continue;' . "\n"
		. '}' . "\n"
		. '// AEC HACK adminhotproperty3 END' . "\n"
		;*/

		$edithack4 = '// AEC HACK adminhotproperty4 START' . "\n"
		. 'global $mosConfig_absolute_path;' . "\n"
		. 'include_once( $mosConfig_absolute_path . \'/components/com_acctexp/acctexp.class.php\' );' . "\n"
		. 'include_once( $mosConfig_absolute_path . \'/components/com_acctexp/micro_integration/mi_hotproperty.php\' );' . "\n"
		. '$mi_hphandler = new aec_hotproperty( $database );' . "\n"
		. '$mi_hphandler->loadLinkID( $id[$i] );' . "\n"
		. 'if( $mi_hphandler->id ) {' . "\n"
		. '$mi_hphandler->removeListing();' . "\n"
		. '}' . "\n"
		. '// AEC HACK adminhotproperty4 END' . "\n"
		;

		$n = 'hotproperty1';
		$hacks[$n]['name']				=	'hotproperty.php #1';
		$hacks[$n]['desc']				=	_AEC_MI_HACK3_HOTPROPERTY;
		$hacks[$n]['type']				=	'file';
		if ( $v10 ) {
			$hacks[$n]['filename']			=	$mosConfig_absolute_path . '/components/com_hotproperty/property.php';
			$hacks[$n]['read']				=	'# Assign default value for new data';
		} else {
			$hacks[$n]['filename']			=	$mosConfig_absolute_path . '/components/com_hotproperty/property.php';
			$hacks[$n]['read']				=	'# Assign default value for new data';
		}
		$hacks[$n]['insert']			=	$edithack . "\n"  . $hacks[$n]['read'];

		$n = 'hotproperty2';
		$hacks[$n]['name']				=	'hotproperty.php #2';
		$hacks[$n]['desc']				=	_AEC_MI_HACK4_HOTPROPERTY;
		$hacks[$n]['type']				=	'file';
		if ( $v10 ) {
			$hacks[$n]['filename']			=	$mosConfig_absolute_path . '/components/com_hotproperty/property.php';
			$hacks[$n]['read']				=	'# Assign current logon user to Agent field';
			$hacks[$n]['insert']			=	$edithack2 . "\n"  . $hacks[$n]['read'];
		} else {
			$hacks[$n]['filename']			=	$mosConfig_absolute_path . '/components/com_hotproperty/property.php';
			$hacks[$n]['oldread']			=	'# Assign current logon user to Agent field';
			$hacks[$n]['oldinsert']			=	$hacks[$n]['oldread'] . "\n" . $edithack2;
			$hacks[$n]['read']				=	'if ($row->id < 1) {';
			$hacks[$n]['insert']			=	$hacks[$n]['read'] . "\n" . $edithack2;
		}

		/*
		$n = 'adminhotproperty3';
		$hacks[$n]['name']				=	'admin.hotproperty.php #3';
		$hacks[$n]['desc']				=	_AEC_MI_HACK5_HOTPROPERTY;
		$hacks[$n]['type']				=	'file';
		$hacks[$n]['filename']			=	$mosConfig_absolute_path . '/administrator/components/com_hotproperty/admin.mtree.php';
		$hacks[$n]['read']				=	'if ( $mtLinks->link_approved == 0 ) {';
		$hacks[$n]['insert']			=	$hacks[$n]['read'] . "\n" . $edithack3;
		*/

		$n = 'adminhotproperty4';
		$hacks[$n]['name']				=	'admin.hotproperty.php #4';
		$hacks[$n]['desc']				=	_AEC_MI_HACK5_HOTPROPERTY;
		$hacks[$n]['type']				=	'file';
		if ( $v10 ) {
			$hacks[$n]['filename']			=	$mosConfig_absolute_path . '/administrator/components/com_hotproperty/admin.hotproperty.php';
			$hacks[$n]['read']				=	'# Remove property from database';
		} else {
			$hacks[$n]['filename']			=	$mosConfig_absolute_path . '/administrator/components/com_hotproperty/admin.hotproperty.php';
			$hacks[$n]['read']				=	'# Remove property from database';
		}
		$hacks[$n]['insert']			=	$hacks[$n]['read'] . "\n" . $edithack4;

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

		if ( $area == 'modifyPrice' ) {
			return $this->modifyPrice( $request );
		}

		if ( $this->settings['create_agent'.$area] ){
			if ( !empty( $this->settings['agent_fields'.$area] ) ) {
				$agent = $this->createAgent( $this->settings['agent_fields'.$area], $request );
			}
		}

		if ( $agent === false ) {
			$this->setError( 'Agent was not found and could not be created' );
			return false;
		}

		if ( $this->settings['update_agent'.$area] ){
			if ( !empty( $this->settings['update_afields'.$area] ) ) {
				if ( !empty( $agent ) ) {
					$agent = $this->update( 'agents', 'user', $this->settings['update_afields'.$area], $request );
				}
			}
		}

		if ( $agent === false ) {
			$this->setError( 'Agent was not found and could not be updated' );
			return false;
		}

		if ( $this->settings['create_company'.$area] ){
			if ( !empty( $this->settings['company_fields'.$area] ) ) {
				$company = $this->createCompany( $this->settings['company_fields'.$area], $this->settings['assoc_company'], $request );
			}

			if ( $company === false ) {
				$this->setError( 'Company was not found and could not be created' );
				return false;
			}
		}

		if ( $this->settings['update_company'.$area] ){
			if ( !empty( $this->settings['update_cfields'.$area] ) ) {
				if ( empty( $company ) ) {
					$company = $this->companyExists( $metaUser->userid );
				}

				if ( !empty( $company ) ) {
					$company = $this->update( 'companies', 'id', $this->settings['update_cfields'.$area], $request, $company );
				}

				if ( $company === false ) {
					$this->setError( 'Company was not found and could not be updated' );
					return false;
				}
			}
		}

		if ( $this->settings['unpublish_all'.$area] ) {
			$this->unpublishProperties( $agent );
		}

		if ( $this->settings['publish_all'.$area] ) {
			$this->publishProperties( $agent );
		}

		if ( !empty( $this->settings['set_listings'.$area] ) || !empty( $this->settings['set_listings'.$area] ) || ( !empty( $this->settings['add_list_userchoice'] ) && !empty( $request->params['hpamt']  ) )  ) {
			global $database;

			$mi_hphandler = new aec_hotproperty( $database );
			$id = $mi_hphandler->getIDbyUserID( $request->metaUser->userid );
			$mi_id = $id ? $id : 0;
			$mi_hphandler->load( $mi_id );

			if ( !$mi_id ){
				$mi_hphandler->userid = $request->metaUser->userid;
				$mi_hphandler->active = 1;
			}

			if ( $this->settings['set_listings'] ) {
				$mi_hphandler->setListings( $this->settings['set_listings'] );
			}

			if ( $this->settings['add_listings'] ) {
				$mi_hphandler->addListings( $this->settings['add_listings'] );
			}

			if ( $this->settings['add_list_userchoice'] ) {
				if ( strpos( $request->params['hpamt'], '>' ) ) {
					$mi_hphandler->unlimitedListings();
				} else {
					$mi_hphandler->addListings( $request->params['hpamt'] );
				}
			}

			$mi_hphandler->storeload();
		}

		return true;
	}

	function agentExists( $userid )
	{
		global $database;

		$query = 'SELECT id FROM #__hp_agents'
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
			return $check;
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
			$query = 'SELECT max(id)'
					. ' FROM #__hp_agents'
					;
			$database->setQuery( $query );
			return $database->loadResult();
		} else {
			$this->setError( $database->getErrorMsg() );
			return false;
		}
	}

	function companyExists( $userid )
	{
		global $database;

		$query = 'SELECT company FROM #__hp_agents'
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

	function createCompany( $fields, $assoc, $request )
	{
		global $database;

		$check = $this->companyExists( $request->metaUser->userid );
		if ( !empty( $check ) ) {
			return $check;
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

		$query = 'SELECT max(id)'
				. ' FROM #__hp_companies'
				;
		$database->setQuery( $query );
		$result = $database->loadResult();

		if ( $result ) {
			if ( $assoc ) {
				if ( $result ) {
					$query = 'UPDATE #__hp_agents'
							. ' SET company = \'' . $result . '\''
							. ' WHERE user = \'' . $request->metaUser->userid . '\''
							;

					$database->setQuery( $query );
					if ( $database->query() ) {
						return $result;
					}
				}
			} else {
				return $result;
			}
		}

		$this->setError( $database->getErrorMsg() );
		return false;
	}

	function update( $table, $id, $fields, $request, $sid=false )
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
				. ' WHERE ' . $id . ' = \'' . ( $sid ? $sid : $request->metaUser->userid ) . '\''
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

class aec_hotproperty extends serialParamDBTable
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

	function declareParamFields(){ return array( 'params' ); }

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

	function getIDbyLinkID( $linkid )
	{
		global $database;

		$query = 'SELECT `agent`'
				. ' FROM #__hp_properties'
				. ' WHERE `id` = \'' . $linkid . '\''
				;
		$database->setQuery( $query );
		$agent = $database->loadResult();

		if ( empty( $agent ) ) {
			return $agent;
		}

		$query = 'SELECT `user`'
				. ' FROM #__hp_agents'
				. ' WHERE `id` = \'' . $agent . '\''
				;
		$database->setQuery( $query );
		$userid = $database->loadResult();

		if ( empty( $userid ) ) {
			return $userid;
		}

		return $this->getIDbyUserID( $userid );
	}

	function loadLinkID( $linkid )
	{
		$id = $this->getIDbyLinkID( $linkid );
		$this->load( $id );
	}

	function is_active()
	{
		if( $this->active ) {
			return true;
		} else {
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
		if ( !empty( $this->params['unlimited'] ) ) {
			return true;
		} elseif( $this->getListingsLeft() > 0 ) {
			return true;
		} else {
			return false;
		}
	}

	function useListing()
	{
		if ( $this->hasListingsLeft() && $this->is_active() ) {
			$this->used_listings++;
			$this->storeload();
			return true;
		} else {
			return false;
		}
	}

	function removeListing()
	{
		if ( $this->is_active() ) {
			$this->used_listings--;
			$this->storeload();
			return true;
		} else {
			return false;
		}
	}

	function setListings( $set )
	{
		$this->granted_listings = $set;
	}

	function unlimitedListings()
	{
		$this->addParams( array( 'unlimited' => true ) );
	}

	function addListings( $add )
	{
		$this->granted_listings += $add;
	}
}
?>
