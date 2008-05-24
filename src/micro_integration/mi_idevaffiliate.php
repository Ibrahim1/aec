<?php
/**
 * @version $Id: mi_idevaffiliate.php 16 2007-07-02 13:29:29Z mic $
 * @package AEC - Account Control Expiration - Subscription component for Joomla! OS CMS
 * @subpackage Micro Integrations - iDevAffiliate
 * @copyright 2006/2007 Copyright (C) David Deutsch
 * @author David Deutsch <skore@skore.de> & Team AEC - http://www.globalnerd.org
 * @license GNU/GPL v.2 http://www.gnu.org/copyleft/gpl.html
 */

defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );

class mi_idevaffiliate
{
	function Info()
	{
		$info = array();
		$info['name'] = _AEC_MI_NAME_IDEV;
		$info['desc'] = _AEC_MI_DESC_IDEV;

		return $info;
	}

	function Settings()
	{
		$settings = array();
		$settings['setupinfo'] = array( 'fieldset' );
		$settings['profile'] = array( 'inputC' );
		$settings['directory'] = array( 'inputC' );
		$settings['use_curl'] = array( 'list_yesno' );
		$settings['onlycustomparams'] = array( 'list_yesno' );
		$settings['customparams'] = array( 'inputD' );
		$rewriteswitches				= array( 'cms', 'user', 'expiration', 'subscription', 'plan', 'invoice' );
		$settings['rewriteInfo']		= array( 'fieldset', _AEC_MI_SET11_EMAIL, AECToolbox::rewriteEngineInfo( $rewriteswitches ) );

		return $settings;
	}

	function action( $request )
	{
		global $database, $mosConfig_live_site;

		$rooturl = $this->getPath( $params );

		$getparams = array();

		if ( !empty( $params['profile'] ) ) {
			$getparams[] = 'profile=' . $params['profile'];
		}

		$getparams[] = 'idev_saleamt=' . $invoice->amount;
		$getparams[] = 'idev_ordernum=' . $invoice->invoice_number;

		if ( !empty( $params['onlycustomparams'] ) && !empty( $params['customparams'] ) ) {
			$getparams = array();
		}

		$userflags = $metaUser->focusSubscription->getMIflags( $plan->id, $this->id );

		if ( !empty( $userflags['IDEV_IP_ADDRESS'] ) ) {
			$ip = $userflags['IDEV_IP_ADDRESS'];
		} else {
			$subscr_params = $metaUser->focusSubscription->getParams();

			if ( isset( $subscr_params['creator_ip'] ) ) {
				$ip = $subscr_params['creator_ip'];
			} else {
				$ip = $_SERVER['REMOTE_ADDR'];
			}

			$newflags['idev_ip_address'] = $ip;
			$metaUser->objSubscription->setMIflags( $plan->id, $this->id, $newflags );
		}

		$getparams[] = 'ip_address=' . $ip;

		if ( !empty( $params['customparams'] ) ) {
			$rw_params = AECToolbox::rewriteEngine( $params['customparams'], $metaUser, $plan, $invoice );

			$cps = explode( "\n", $rw_params );

			foreach ( $cps as $cp ) {
				$getparams[] = $cp;
			}
		}

		if ( !empty( $params['use_curl'] ) ) {
			$ch = curl_init();
			$curl_url = $rooturl . "/sale.php?" . implode( '&', $getparams );
			curl_setopt($ch, CURLOPT_URL, $curl_url );
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_exec($ch);
			curl_close($ch);
		} else {
			$text = '<img border="0" '
					.'src="' . $rooturl .'/sale.php?' . implode( '&amp;', $getparams ) . '" '
					.'width="1" height="1" />';

			$displaypipeline = new displayPipeline($database);
			$displaypipeline->create( $metaUser->userid, 1, 0, 0, null, 1, $text );
		}

		return true;
	}

	function getPath( $params )
	{
		global $mosConfig_live_site;

		if ( !empty( $params['directory'] ) ) {
			if ( ( strpos( $params['directory'], 'http://' ) === 0 ) || ( strpos( $params['directory'], 'https://' ) === 0 ) ) {
				$rooturl = $params['directory'];
			} else {
				if ( ( strpos( $params['directory'], 'www.' ) === 0 ) ) {
					$rooturl = "http://" . $params['directory'];
				} elseif ( strpos( "/", $params['directory'] ) !== 0 ) {
					$rooturl = $mosConfig_live_site . "/" . $params['directory'];
				} else {
					$rooturl = $mosConfig_live_site . $params['directory'];
				}
			}
		} else {
			$rooturl = $mosConfig_live_site . '/idevaffiliate';
		}

		return $rooturl;
	}
}
?>