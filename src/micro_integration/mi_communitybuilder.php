<?php
/**
 * @version $Id: mi_communitybuilder.php 1 2007-07-17 12:07:07Z mic $
 * @package AEC - Account Control Expiration - Subscription component for Joomla! OS CMS
 * @subpackage Micro Integrations - CommunityBuilder (CB)
 * @copyright 2007 Copyright (C) David Deutsch
 * @author David Deutsch <skore@skore.de> & Team AEC - http://www.gobalnerd.org
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */

defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );

class mi_communitybuilder
{
	function Info ()
	{
		$info = array();
		$info['name'] = _AEC_MI_NAME_COMMUNITYBUILDER;
		$info['desc'] = _AEC_MI_DESC_COMMUNITYBUILDER;

		return $info;
	}

	function Settings( $params )
	{
		$settings = array();
		$settings['approve']		= array( 'list_yesno' );
		$settings['unapprove_exp']	= array( 'list_yesno' );

		return $settings;
	}

	function pre_expiration_action($params, $userid, $plan, $mi_id)
	{
	}

	function expiration_action($params, $userid, $plan)
	{
		global $database;

		if( $params['unapprove_exp'] ) {
			$query = 'UPDATE #__comprofiler'
			.' SET approved = \'0\''
			.' WHERE user_id = \'' . (int) $userid . '\'';
			$database->setQuery( $query );
			$database->query() or die( $database->stderr() );
		}
	}

	function action($params, $userid, $plan)
	{
		global $database;

		if( $params['approve'] ) {
			$query = 'UPDATE #__comprofiler'
			.' SET approved = \'1\''
			.' WHERE user_id = \'' . (int) $userid . '\'';
			$database->setQuery( $query );
			$database->query() or die( $database->stderr() );
		}
	}

}
?>