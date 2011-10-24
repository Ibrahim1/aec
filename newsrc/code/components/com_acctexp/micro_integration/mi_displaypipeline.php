<?php
/**
 * @version $Id: mi_displaypipeline.php
 * @package AEC - Account Control Expiration - Membership Manager
 * @subpackage Micro Integrations - DisplayPipeline
 * @copyright 2006-2011 Copyright (C) David Deutsch
 * @author David Deutsch <skore@skore.de> & Team AEC - http://www.valanx.org
 * @license GNU/GPL v.2 http://www.gnu.org/licenses/old-licenses/gpl-2.0.html or, at your option, any later version
 */

// Dont allow direct linking
( defined('_JEXEC') || defined( '_VALID_MOS' ) ) or die( 'Direct Access to this location is not allowed.' );

class mi_displaypipeline
{

	function Info()
	{
		$info = array();
		$info['name'] = JText::_('AEC_MI_NAME_DISPLAYPIPELINE');
		$info['desc'] = JText::_('AEC_MI_DESC_DISPLAYPIPELINE');

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

		$settings						= AECToolbox::rewriteEngineInfo( $rewriteswitches, $settings );

		return $settings;
	}

	function action( $request )
	{
		$db = &JFactory::getDBO();

		$text = AECToolbox::rewriteEngineRQ( $this->settings['text'], $request );

		$displaypipeline = new displayPipeline($db);
		$displaypipeline->create( $metaUser->userid, $this->settings['only_user'], $this->settings['once_per_user'], $this->settings['expire'], $this->settings['expiration'], $this->settings['displaymax'], $text );
		return true;
	}

}

?>
