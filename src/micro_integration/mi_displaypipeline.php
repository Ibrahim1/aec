<?php
/**
 * @version $Id: mi_displaypipeline.php
 * @package AEC - Account Control Expiration - Subscription component for Joomla! OS CMS
 * @subpackage Micro Integrations - DisplayPipeline
 * @copyright 2006/2007 Copyright (C) David Deutsch
 * @author David Deutsch <skore@skore.de> & Team AEC - http://www.globalnerd.org
 * @license GNU/GPL v.2 http://www.gnu.org/copyleft/gpl.html
 */

// Dont allow direct linking
defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );

class mi_displaypipeline
{

	function Info()
	{
		$info = array();
		$info['name'] = _AEC_MI_NAME_DISPLAYPIPELINE;
		$info['desc'] = _AEC_MI_DESC_DISPLAYPIPELINE;

		return $info;
	}

	function Settings()
	{
		$settings = array();
		$settings['only_user']			= array( 'list_yesno' );
		$settings['once_per_user']		= array( 'list_yesno' );

		$settings['expire']				= array( 'list_yesno' );
		$settings['expiration']			= array( 'inputE' );

		$settings['displaymax']			= array( 'inputB' );
		$settings['text']				= array( 'inputE' );

		$rewriteswitches				= array( 'cms', 'user', 'expiration', 'subscription', 'plan', 'invoice' );
		$settings['rewriteInfo']		= array( 'fieldset', _AEC_MI_SET11_EMAIL,
										AECToolbox::rewriteEngineInfo( $rewriteswitches ) );

		return $settings;
	}

	function action( $request )
	{
		global $database;

		$text = AECToolbox::rewriteEngine( $this->settings['text'], $metaUser, $plan, $invoice );

		$displaypipeline = new displayPipeline($database);
		$displaypipeline->create( $metaUser->userid, $this->settings['only_user'], $this->settings['once_per_user'], $this->settings['expire'], $this->settings['expiration'], $this->settings['displaymax'], $text );
		return true;
	}

}

?>