<?php
/**
 * @version $Id: mi_aectax.php
 * @package AEC - Account Control Expiration - Membership Manager
 * @subpackage Micro Integrations - Overall Tax Management MI
 * @copyright 2006-2008 Copyright (C) David Deutsch
 * @author David Deutsch <skore@skore.de> & Team AEC - http://www.valanx.org
 * @license GNU/GPL v.2 http://www.gnu.org/licenses/old-licenses/gpl-2.0.html or, at your option, any later version
 */

// Dont allow direct linking
( defined('_JEXEC') || defined( '_VALID_MOS' ) ) or die( 'Direct Access to this location is not allowed.' );

class mi_aectax
{
	function Info()
	{
		$info = array();
		$info['name'] = _AEC_MI_NAME_AECTAX;
		$info['desc'] = _AEC_MI_DESC_AECTAX;

		return $info;
	}

	function Settings()
	{
		$settings = array();

		// Tax Modes

		return $settings;
	}

	function invoice_printout( $request )
	{
		// Append Tax Data to content

		return true;
	}
}
?>
