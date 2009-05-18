<?php
/**
 * @version $Id: mi_joomlauser.php
 * @package AEC - Account Control Expiration - Membership Manager
 * @subpackage Micro Integrations - Joomla User
 * @copyright 2006-2008 Copyright (C) David Deutsch
 * @author David Deutsch <skore@skore.de> & Team AEC - http://www.valanx.org
 * @license GNU/GPL v.2 http://www.gnu.org/licenses/old-licenses/gpl-2.0.html or, at your option, any later version
 */

// Dont allow direct linking
( defined('_JEXEC') || defined( '_VALID_MOS' ) ) or die( 'Direct Access to this location is not allowed.' );

class mi_joomlauser
{
	function Info()
	{
		$info = array();
		$info['name'] = _AEC_MI_NAME_JOOMLAUSER;
		$info['desc'] = _AEC_MI_DESC_JOOMLAUSER;

		return $info;
	}

	function Settings()
	{
		$settings = array();
		$settings['activate'] = array( 'list_yesno' );

		return $settings;
	}

	function action( $request )
	{
		global $database;

		if ( $this->settings['activate'] ) {
			$query = 'UPDATE #__users'
					.' SET `block` = \'0\', `activation` = \'\''
					.' WHERE `id` = \'' . (int) $request->metaUser->userid . '\''
					;
			$database->setQuery( $query );
			$database->query() or die( $database->stderr() );
		}
	}
}

?>