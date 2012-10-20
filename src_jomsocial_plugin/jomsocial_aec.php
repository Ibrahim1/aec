<?php
defined('_JEXEC') or die('Restricted access');

require_once( JPATH_BASE'/components/com_community/libraries/core.php');

class plgCommunityAEC extends CApplications {

	var $name	= "This is a AEC Subscription plugin for JomSocial";
	var $_name	= 'AEC';
	var $_path	= '';
	var $_user	= '';
	var $_my	= '';

	function plgCommunityAECSubscription(& $subject, $config)
	{
			parent::__construct( $subject, $config );
	}

	function onProfileDisplay()
	{
		$document=& JFactory::getDocument();

		// Attach CSS
		$css        = JURI::base() . 'plugins/community/aecsubscription/style.css';
		$document->addStyleSheet($css);

		// manage caching
		$cache =& JFactory::getCache( 'plgCommunityAECSubscription' );
		$cache->setCaching( $this->params->get( 'cache', 0 ) );

		$callback = array( 'plgCommunityAECSubscription', '_getOriginalHTML' );
		$content = $cache->call( $callback );

		return $content;
	}

	function _getOriginalHTML()
	{
			if ( defined( 'JPATH_ROOT.' ) ) {
				$path = JPATH_ROOT;
			} else {
				global $mosConfig_absolute_path;

				$path = $mosConfig_absolute_path;
			}

			$status = include( $path . DIRECTORY_SEPARATOR . 'acctexp' . DIRECTORY_SEPARATOR  . 'acctexp.php' );

			if ( !$status && !defined( 'INSTALLER_FOLDER' ) ) {
					echo "We were unable to load the AEC library. If you removed the AEC folder, please also remove this plugins from the Joomla plugins manager.";
			}

			$user    =& CFactory::getActiveProfile();

			// get the memberID
			$membersSessionC = &WGet::classes('members.session');
			$myMember = $membersSessionC->getMember( $user->_userid, true );
			$memberId = $myMember->uid;

			// if we dont have member we dont show the view
			if ( empty( $memberId ) ) {
				return;
			}

			// set the value for the view filter
			WGlobals::setVar( 'integrationmemberid', $memberId );
			WGlobals::setVar( 'extensionKEY', 'subscription.node', 'acctexp');

			// Show the view
			$integrationV = WView::get( 'subscription_widget_integration' );
			if ( empty($integrationV) ) return '';
			$HTMLoutput .= $integrationV->make();

			return $HTMLoutput;
	}

}