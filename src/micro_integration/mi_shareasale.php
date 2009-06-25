<?php
/**
 * @version $Id: mi_shareasale.php
 * @package AEC - Account Control Expiration - Membership Manager
 * @subpackage Micro Integrations - Share a Sale
 * @copyright 2006-2009 Copyright (C) David Deutsch
 * @author David Deutsch <skore@skore.de> & Team AEC - http://www.valanx.org
 * @license GNU/GPL v.2 http://www.gnu.org/licenses/old-licenses/gpl-2.0.html or, at your option, any later version
 */

// Dont allow direct linking
( defined('_JEXEC') || defined( '_VALID_MOS' ) ) or die( 'Direct Access to this location is not allowed.' );

class mi_shareasale
{
	function Info()
	{
		$info = array();
		$info['name'] = _AEC_MI_NAME_SHAREASALE;
		$info['desc'] = _AEC_MI_DESC_SHAREASALE;

		return $info;
	}

	function Settings()
	{
		$settings = array();
		$settings['setupinfo']			= array( 'fieldset' );
		$settings['profile']			= array( 'inputC' );
		$settings['directory']			= array( 'inputC' );
		$settings['use_curl']			= array( 'list_yesno' );
		$settings['onlycustomparams']	= array( 'list_yesno' );
		$settings['customparams']		= array( 'inputD' );
		$rewriteswitches				= array( 'cms', 'user', 'expiration', 'subscription', 'plan', 'invoice' );
		$settings['rewriteInfo']		= array( 'fieldset', _AEC_MI_SET11_EMAIL, AECToolbox::rewriteEngineInfo( $rewriteswitches ) );

		return $settings;
	}

	function action( $request )
	{
		global $database, $mosConfig_live_site;

		$rooturl = $this->getPath();

		$getparams = array();

		if ( !empty( $this->settings['profile'] ) ) {
			$getparams[] = 'profile=' . $this->settings['profile'];
		}

		$getparams[] = 'idev_saleamt=' . $request->invoice->amount;
		$getparams[] = 'idev_ordernum=' . $request->invoice->invoice_number;

		if ( !empty( $this->settings['onlycustomparams'] ) && !empty( $this->settings['customparams'] ) ) {
			$getparams = array();
		}

		$userflags = $request->metaUser->focusSubscription->getMIflags( $request->plan->id, $this->id );

		if ( !empty( $userflags['SHAREASALE_IP_ADDRESS'] ) ) {
			$ip = $userflags['SHAREASALE_IP_ADDRESS'];
		} else {
			if ( isset( $request->metaUser->focusSubscription->params['creator_ip'] ) ) {
				$ip = $request->metaUser->focusSubscription->params['creator_ip'];
			} else {
				$ip = $_SERVER['REMOTE_ADDR'];
			}

			$newflags['idev_ip_address'] = $ip;
			$request->metaUser->objSubscription->setMIflags( $request->plan->id, $this->id, $newflags );
		}

		$getparams[] = 'ip_address=' . $ip;

		if ( !empty( $this->settings['customparams'] ) ) {
			$rw_params = AECToolbox::rewriteEngineRQ( $this->settings['customparams'], $request );

			if ( strpos( $rw_params, "\r\n" ) !== false ) {
				$cps = explode( "\r\n", $rw_params );
			} else {
				$cps = explode( "\n", $rw_params );
			}

			foreach ( $cps as $cp ) {
				$getparams[] = $cp;
			}
		}

		$newget = array();
		foreach ( $getparams as $v ) {
			$va = explode( '=', $v, 2 );

			$newget[] = urlencode($va[0]) . '=' . urlencode($va[1]);
		}

		if ( !empty( $this->settings['use_curl'] ) ) {
			$ch = curl_init();
			$curl_url = $rooturl . "/sale.php?" . implode( '&', $newget );
			curl_setopt($ch, CURLOPT_URL, $curl_url );
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_exec($ch);
			curl_close($ch);
		} else {
			$text = '<img border="0" '
					.'src="' . $rooturl .'/sale.php?' . implode( '&amp;', $newget ) . '" '
					.'width="1" height="1" />';

			$displaypipeline = new displayPipeline($database);
			$displaypipeline->create( $request->metaUser->userid, 1, 0, 0, null, 1, $text );
		}

		return true;
	}

    function logpayment( $invoice )
    {
        $user = JFactory::getUser($invoice->userid);
        $SSAID = $user->getParam('SSAID');
        $SSAIDDATA = $user->getParam('SSAIDDATA');
        if($SSAID != '' && $SSAIDDATA != '') {
            $amount = $invoice->amount;
            $tracking = $invoice->id;
            $yourmerchantid = 22068;

            $ch = curl_init();
            $send_to_url = "https://shareasale.com/q.cfm";
            $my_curl_url = sprintf("%s?amount=%s&tracking=%s&transtype=sale&merchantID=%s&userID=%s&SSAIDDATA=%s", $send_to_url, $amount, $tracking, $yourmerchantid, $SSAID, $SSAIDDATA);
            curl_setopt($ch, CURLOPT_URL, $my_curl_url);
            curl_setopt($ch, CURLOPT_POST, TRUE);
            curl_setopt($ch, CURLOPT_PORT, 443);
            curl_exec($ch);
            curl_close($ch);
        }
        return true;
    }

	function getPath()
	{
		global $mosConfig_live_site;

		if ( !empty( $this->settings['directory'] ) ) {
			if ( ( strpos( $this->settings['directory'], 'http://' ) === 0 ) || ( strpos( $this->settings['directory'], 'https://' ) === 0 ) ) {
				$rooturl = $this->settings['directory'];
			} else {
				if ( ( strpos( $this->settings['directory'], 'www.' ) === 0 ) ) {
					$rooturl = "http://" . $this->settings['directory'];
				} elseif ( strpos( "/", $this->settings['directory'] ) !== 0 ) {
					$rooturl = $mosConfig_live_site . "/" . $this->settings['directory'];
				} else {
					$rooturl = $mosConfig_live_site . $this->settings['directory'];
				}
			}
		} else {
			$rooturl = $mosConfig_live_site . '/idevaffiliate';
		}

		return $rooturl;
	}
}
?>
