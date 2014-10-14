<?php
/**
 * @version $Id: acctexp.component.class.php
 * @package AEC - Account Control Expiration - Membership Manager
 * @subpackage Core Class
 * @copyright 2006-2013 Copyright (C) David Deutsch
 * @author David Deutsch <skore@valanx.org> & Team AEC - http://www.valanx.org
 * @license GNU/GPL v.3 http://www.gnu.org/licenses/gpl.html or, at your option, any later version
 */

class aecComponentHelper
{
	/**
	 * Check whether a component is installed
	 * @return Bool
	 */
	static function detect_component( $component )
	{
		$db = &JFactory::getDBO();

		global $aecConfig;

		$app = JFactory::getApplication();

		$tables	= array();
		$tables	= $db->getTableList();

		if ( !empty( $aecConfig->cfg['bypassintegration'] ) ) {
			$bypass = str_replace( ',', ' ', $aecConfig->cfg['bypassintegration'] );

			$overrides = explode( ' ', $bypass );

			foreach ( $overrides as $i => $c ) {
				$overrides[$i] = trim($c);
			}

			if ( in_array( 'CB', $overrides ) ) {
				$overrides[] = 'CB1.2';
			}

			if ( in_array( 'CB', $overrides ) || in_array( 'CB1.2', $overrides ) || in_array( 'CBE', $overrides ) ) {
				$overrides[] = 'anyCB';
			}

			if ( in_array( $component, $overrides ) ) {
				return false;
			}
		}

		$pathCB		= JPATH_SITE . '/components/com_comprofiler';

		switch ( $component ) {
			case 'anyCB': // any Community Builder
				return is_dir( $pathCB );
				break;

			case 'CB1.2': // Community Builder 1.2
				$is_cbe	= ( is_dir( $pathCB. '/enhanced' ) || is_dir( $pathCB . '/enhanced_admin' ) );
				$is_cb	= ( is_dir( $pathCB ) && !$is_cbe );

				$is12 = file_exists( $pathCB . '/js/cb12.js' );

				return ( $is_cb && $is12 );
				break;

			case 'CB': // Community Builder
				$is_cbe	= ( is_dir( $pathCB. '/enhanced' ) || is_dir( $pathCB . '/enhanced_admin' ) );
				$is_cb	= ( is_dir( $pathCB ) && !$is_cbe );
				return $is_cb;
				break;

			case 'CBE': // Community Builder Enhanced
				$is_cbe = ( is_dir( $pathCB . '/enhanced' ) || is_dir( $pathCB . '/enhanced_admin' ) );
				return $is_cbe;
				break;

			case 'CBM': // Community Builder Moderator for Workflows
				return file_exists( JPATH_SITE . '/modules/mod_comprofilermoderator.php' );
				break;

			case 'ALPHA': // AlphaRegistration
				return file_exists( JPATH_SITE . '/components/com_alpharegistration/alpharegistration.php' );
				break;

			case 'UE': // User Extended
				return in_array( $app->getCfg( 'dbprefix' ) . 'user_extended', $tables );
				break;

			case 'SMF': // Simple Machines Forum
				$pathSMF	= JPATH_SITE . '/administrator/components/com_smf';

				return file_exists( $pathSMF . '/config.smf.php') || file_exists( $pathSMF . '/smf.php' );
				break;

			case 'VM': // VirtueMart
				return in_array( $app->getCfg( 'dbprefix' ) . 'vm_orders', $tables );
				break;

			case 'JACL': // JACL
				return in_array( $app->getCfg( 'dbprefix' ) . 'jaclplus', $tables );
				break;

			case 'UHP2':
				return file_exists( JPATH_SITE . '/modules/mod_uhp2_manage.php' );
				break;

			case 'JUSER':
				return file_exists( JPATH_SITE . '/components/com_juser/juser.php' );
				break;

			case 'JOMSOCIAL':
				return file_exists( JPATH_SITE . '/components/com_community/community.php' );
				break;
		}
	}
}
