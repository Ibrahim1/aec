<?php
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

	function Settings( $params )
	{
		$settings = array();
		$settings['only_user']			= array( 'list_yesno' );
		$settings['once_per_user']		= array( 'list_yesno' );

		$settings['expire']				= array( 'list_yesno' );
		$settings['expstamp']			= array( 'inputE' );

		$settings['displaymax']			= array( 'inputB' );
		$settings['text']				= array( 'inputE' );

		$rewriteswitches				= array( 'cms', 'user', 'expiration', 'subscription', 'plan' );
		$settings['rewriteInfo']		= array( 'fieldset', _AEC_MI_SET11_EMAIL,
										AECToolbox::rewriteEngineInfo( $rewriteswitches ) );

		return $settings;
	}

	function action( $params, $userid, $plan )
	{
		global $database;

		$metaUser = new metaUser( $userid );

		$text = AECToolbox::rewriteEngine( $params['text'], $metaUser, $plan );

		$displaypipeline = new displayPipeline($database);
		$displaypipeline->create( $userid, $params['only_user'], $params['once_per_user'], $params['expire'], $params['expiration'], $params['displaymax'], $text );
		return true;
	}

}

?>