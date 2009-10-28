<?php
/**
 * @version $Id: mi_aecinvoiceprintmod.php
 * @package AEC - Account Control Expiration - Membership Manager
 * @subpackage Micro Integrations - Invoice Printout Modification
 * @copyright 2006-2008 Copyright (C) David Deutsch
 * @author David Deutsch <skore@skore.de> & Team AEC - http://www.valanx.org
 * @license GNU/GPL v.2 http://www.gnu.org/licenses/old-licenses/gpl-2.0.html or, at your option, any later version
 */

// Dont allow direct linking
( defined('_JEXEC') || defined( '_VALID_MOS' ) ) or die( 'Direct Access to this location is not allowed.' );

class mi_aecinvoiceprintmod
{
	function Info()
	{
		$info = array();
		$info['name'] = _AEC_MI_NAME_AECINVOICEPRINTMOD;
		$info['desc'] = _AEC_MI_DESC_AECINVOICEPRINTMOD;

		return $info;
	}

	function Settings()
	{
		$s = array( "before_header", "header", "after_header",
					"before_content", "after_content",
					"before_footer", "footer", "after_footer",
					);

 		$modelist = array();
		$modelist[] = mosHTML::makeOption ( "none", CONSTANT_ );
		$modelist[] = mosHTML::makeOption ( "before", CONSTANT_ );
		$modelist[] = mosHTML::makeOption ( "after", CONSTANT_ );
		$modelist[] = mosHTML::makeOption ( "replace", CONSTANT_ );
		$modelist[] = mosHTML::makeOption ( "delete", CONSTANT_ );

		$settings = array();
		foreach ( $s as $x ) {
			$settings[$x]			= array( "editor" );

			$y = $x."_mode";

			if ( isset( $this->settings[$y] ) ) {
				$dv = $this->settings[$y];
			} else {
				$dv = null;
			}

			$settings[$y]			= array( "group" );
			$settings['lists'][$y]	= mosHTML::selectList( $modelist, $y, 'size="1"', 'value', 'text', $dv );
		}

		return $settings;
	}

	function action( $request )
	{
		$database = &JFactory::getDBO();

		foreach ( $request->add as $k => $v ) {
			if ( isset( $this->settings[$k] ) ) {
				if ( isset( $this->settings[$k."_mode"] ) ) {
					switch ( $this->settings[$k."_mode"] ) {
						case "none":
							continue;
							break;
						case "before":
							$value = $this->settings[$k] . $v;
							break;
						case "after":
							$value = $v . $this->settings[$k];
							break;
						case "replace":
							$value = $this->settings[$k];
							break;
						case "delete":
							$value = "";
							break;
					}
				} else {
					$value = $this->settings[$k];
				}

				$request->add[$k] = $value;
			}
		}

		return true;
	}
}
?>
