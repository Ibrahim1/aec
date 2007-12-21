<?php
/**
 * @version $Id: mi_mysql_query.php 16 2007-07-02 13:29:29Z mic $
 * @package AEC - Account Control Expiration - Subscription component for Joomla! OS CMS
 * @subpackage Micro Integrations - MySQL Query
 * @copyright 2006/2007 Copyright (C) David Deutsch
 * @author David Deutsch <skore@skore.de> & Team AEC - http://www.globalnerd.org
 * @license GNU/GPL v.2 http://www.gnu.org/copyleft/gpl.html
 */

defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );

class mi_http_query_cod
{
	function Info()
	{
		$info = array();
		$info['name'] = _AEC_MI_NAME_HTTP_QUERY . "-COD";
		$info['desc'] = _AEC_MI_DESC_HTTP_QUERY . " COD EDITION";

		return $info;
	}

	function Settings( $params )
	{
        $settings = array();
        $settings['url']			= array( 'inputE' );
        $settings['query']			= array( 'inputD' );
        $settings['url_exp']		= array( 'inputE' );
        $settings['query_exp']		= array( 'inputD' );
        $settings['url_pre_exp']	= array( 'inputE' );
        $settings['query_pre_exp']	= array( 'inputD' );
		$rewriteswitches			= array( 'cms', 'user', 'expiration', 'subscription', 'plan', 'invoice' );
		$settings['rewriteInfo']	= array( 'fieldset', _AEC_MI_SET4_MYSQL, AECToolbox::rewriteEngineInfo( $rewriteswitches ) );

		return $settings;
	}

	function action( $params, $metaUser, $invoice, $plan )
	{
		global $database;

		if ( $metaUser->focusSubscription->previous_plan == 8 ) {
			if ( in_array( $plan->id, array( 6, 10, 12 ) ) ) {
				$query = 'UPDATE #__comprofiler'
					. ' SET cb_orgid = \'' . (int) $metaUser->username . '\''
					. ' WHERE user_id = \'' . (int) $metaUser->userid . '\'';
				$database->setQuery( $query );
				$database->query();
			}
		}

		$metaUser->loadCBuser();
//print_r($metaUser);print_r($this);exit();
		$plansabbrev = array(
								6 => 'AS',
								7 => 'AP',
								8 => 'Cons',
								9 => 'eTP',
								10 => 'eTS',
								11 => 'NSS',
								12 => 'NSP'
							);

		if ( !empty( $metaUser->focusSubscription->previous_plan ) ) {
			if ( isset( $plansabbrev[$metaUser->focusSubscription->previous_plan] ) ) {
				$prev = $plansabbrev[$metaUser->focusSubscription->previous_plan];
			} else {
				$prev = 'UNKNOWN';
			}
		} else {
			$prev = 'NONE';
		}

		if ( isset( $plansabbrev[$plan->id] ) ) {
			$cur = $plansabbrev[$plan->id];
		} else {
			$cur = 'UNKNOWN';
		}

		$a = array();
		$a['Operation'] = "RegisterTransaction";
		$a['CustomerID'] = $metaUser->cmsUser->username;
		$a['UserID'] = $metaUser->cbUser->cb_orgid;
		$a['TransactionTypeID'] = "JOOMLA";
		$a['Description'] = $prev . '-' . $cur . '-' . $invoice->counter;

		$la = array();
		foreach ( $a as $n => $v ) {
			$la[] = $n . '=' . $v;
		}

		$link = "https://www.catalog-on-demand.com/smanager/api.do?" . implode( "&", $la );

		$return = $this->fetchURL( $link );

		if ( strpos( $return, "<Errors>" ) === false ) {
			return true;
		} else {
			global $mainframe, $acl, $database;

			// The plan transition should fail
			// The user plan should go to the fallback as specified in AEC for that plan
			$plan = new SubscriptionPlan( $database );
			$plan->load( $metaUser->focusSubscription->plan );

			$metaUser->focusSubscription->expire();

			// The error should be logged somewhere … a file? the AEC event log?
			$eventlog = new eventLog( $database );
			$eventlog->issue( 'plan assignment failed', 'plan assignment, failure, smanager', 'A user ("' . $metaUser->cmsUser->username . '") could not be identified by smanager' );

			// An email notification should go to the super-admins
			// get superadministrators id
			$admins = $acl->get_group_objects( 25, 'ARO' );

			if ( $mainframe->getCfg( 'mailfrom' ) != '' && $mainframe->getCfg( 'fromname' ) != '' ) {
				$adminName2		= $mainframe->getCfg( 'fromname' );
				$adminEmail2	= $mainframe->getCfg( 'mailfrom' );
			} else {
				$query = 'SELECT name, email'
				. ' FROM #__users'
				. ' WHERE usertype = \'superadministrator\''
				;
				$database->setQuery( $query );
				$rows = $database->loadObjectList();
				$row2 = $rows[0];

				$adminName2		= $row2->name;
				$adminEmail2	= $row2->email;
			}

			$msgtemp = "Hello %s,\n\nA user tried to subscribe to a plan at [ %s ].\n\nHere further details:\n\nName.........: %s\nEmail........: %s\nUsername.....: %s\nSubscr.-ID...: %s\nSubscription.: %s\nIP...........: %s\nISP..........: %s\n\nThe plan application failed because smanager reported an error on this userid.\n\n Please do not respond to this message as it is automatically generated and is for information purposes only.";

			$aecUser = AECToolbox::_aecIP();

			$message = sprintf( $msgtemp, $adminName2, $mainframe->getCfg( 'sitename' ), $metaUser->cmsUser->name, $metaUser->cmsUser->email, $metaUser->cmsUser->username, $plan->id, $plan->name, $aecUser['ip'], $aecUser['isp'] );

			foreach ( $admins['users'] AS $id ) {
				$query = 'SELECT email, sendEmail'
				. ' FROM #__users'
				. ' WHERE id = \'' . $id . '\''
				;
				$database->setQuery( $query );
				$rows = $database->loadObjectList();

				$row = $rows[0];

				if ( $row->sendEmail ) {
					mosMail( $adminEmail2, $adminName2, $row->email, "Plan Application failed", $message );
				}
			}

			// The user should be given the error message
			Payment_HTML::error( 'com_acctexp', $metaUser->cmsUser, $invoice->invoice_number, "You are not listed in our customer database. Please contact the System Administrator!", true );
		}
	}

	function createURL( $url, $params ) {
		$urlsplit = explode( '?', $url );

		$p = explode( "\n", $params );

		if ( !empty( $urlsplit[1] ) ) {
			$p2 = explode( '&', $urlsplit[1] );

			if ( !empty( $p2 ) ) {
				$p = array_merge( $p2, $p );
			}
		}

		return $urlsplit[0] . '?' . implode( '&', $p );
	}

	function fetchURL( $url ) {
		$url_parsed = parse_url($url);

		$host = $url_parsed["host"];
		$port = $url_parsed["port"];
		if ( $port == 0 ) {
			$port = 80;
		}
		$path = $url_parsed["path"];

		//if url is http://example.com without final "/"
		//I was getting a 400 error
		if ( empty( $path ) ) {
			$path="/";
		}

		if ( $url_parsed["query"] != "" ) {
			$path .= "?".$url_parsed["query"];
		}

		$out = "GET $path HTTP/1.0\r\nHost: $host\r\n\r\n";
		$fp = fsockopen( $host, $port, $errno, $errstr, 30 );

		if ( $fp ) {
			fwrite( $fp, $out );

			$return = '';
			while ( !feof( $fp ) ) {
				$return .= fgets($fp, 1024);
			}

			fclose( $fp );

			return $return;
		} else {
			return false;
		}
	}
}
?>