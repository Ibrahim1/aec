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

class mi_mysql_query
{
	function Info()
	{
		$info = array();
		$info['name'] = _AEC_MI_NAME_MYSQL;
		$info['desc'] = _AEC_MI_DESC_MYSQL;

		return $info;
	}

	function Settings()
	{
        $settings = array();
        $settings['query']			= array( 'inputD' );
        $settings['query_exp']		= array( 'inputD' );
        $settings['query_pre_exp']	= array( 'inputD' );
		$rewriteswitches			= array( 'cms', 'user', 'expiration', 'subscription', 'plan', 'invoice' );
		$settings['rewriteInfo']	= array( 'fieldset', _AEC_MI_SET4_MYSQL, AECToolbox::rewriteEngineInfo( $rewriteswitches ) );

		return $settings;
	}

	function pre_expiration_action( $request )
	{
		$query = AECToolbox::rewriteEngine( $this->settings['query_pre_exp'], $request->metaUser, $request->plan, $request->invoice );

		$database->setQuery( $query );
		$database->query();

		return true;
	}

	function expiration_action( $request )
	{
		global $database;

		$query = AECToolbox::rewriteEngine( $this->settings['query_exp'], $request->metaUser, $request->plan, $request->invoice );

		$database->setQuery( $query );
		$database->query();

		return true;
	}

	function action( $request )
	{
		global $database;

		$query = AECToolbox::rewriteEngine( $this->settings['query'], $request->metaUser, $request->plan, $request->invoice );

		$database->setQuery( $query );
		$database->query();

		return true;
	}
}
?>