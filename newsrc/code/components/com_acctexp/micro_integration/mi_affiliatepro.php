<?php
/**
 * @version $Id: mi_affiliatepro.php 16 2007-07-01 12:07:07Z mic $
 * @package AEC - Account Control Expiration - Membership Manager
 * @subpackage Micro Integrations - AffiliatePRO
 * @copyright 2006-2012 Copyright (C) David Deutsch
 * @author David Deutsch <skore@valanx.org> & Team AEC - http://www.valanx.org
 * @license GNU/GPL v.3 http://www.gnu.org/licenses/gpl.html or, at your option, any later version
 */

// Dont allow direct linking
( defined('_JEXEC') || defined( '_VALID_MOS' ) ) or die( 'Direct Access to this location is not allowed.' );

class mi_affiliatepro
{
	function Info()
	{
		$info = array();
		$info['name'] = JText::_('AEC_MI_NAME_AFFPRO');
		$info['desc'] = JText::_('AEC_MI_DESC_AFFPRO');

		return $info;
	}

	function Settings()
	{
		$settings = array();
		$settings['url']		= array( 'inputC' );
		$settings['cookie']		= array( 'toggle' );
		$settings['path']		= array( 'inputC' );
		$settings['merchant']	= array( 'inputC' );
		$settings['password']	= array( 'inputC' );

		return $settings;
	}

	function invoice_creation( $request )
	{
		if ( empty( $this->settings['cookie'] ) ) {
			return null;
		}

		if ( empty( $this->settings['path'] ) ) {
			aecQuickLog( 'warning', 'mi,invoice_creation,mi_affiliatepro', 'You need to provide a path to your Affiliate Pro Directory.', 32 );

			return null;
		}

		if ( empty( $this->settings['merchant'] ) || empty( $this->settings['password'] ) ) {
			aecQuickLog( 'warning', 'mi,invoice_creation,mi_affiliatepro', 'You need to provide a merchant name and password in order to track cookies in Affiliate Pro.', 32 );

			return null;
		}

		if ( substr( $this->settings['path'], -1, 1 ) != '/' ) {
			$this->settings['path'] .= '/';
		}

		if ( !file_exists( $this->settings['path'] . 'PapApi.class.php' ) ) {
			aecQuickLog( 'warning', 'mi,invoice_creation,mi_affiliatepro', 'Could not locate the Affiliate Pro API at this Directory.', 32 );

			return null;
		}

		include_once( $this->settings['path'] . 'PapApi.class.php' );

		if ( strpos( $this->settings['url'], '/sales.js' ) ) {
			$url = str_replace( '/sales.js', '/server.php', $this->settings['url'] );
		} else {
			if ( substr( $this->settings['url'], -1, 1 ) != '/' ) {
				$url = $this->settings['url'] . '/server.php';
			} else {
				$url = $this->settings['url'] . 'server.php';
			}
		}

		$session = new Gpf_Api_Session( $url ); 

		if( !$session->login( $this->settings['merchant'], empty( $this->settings['password'] ) ) ) { 
			aecQuickLog( 'warning', 'mi,invoice_creation,mi_affiliatepro', "Cannot login. Message: ".$session->getMessage(), 32 );

			return null;
		}

		if ( $clickTracker->getAffiliate() ) {
			$request->invoice->params['mi_affiliatepro_referrer'] = $clickTracker->getUserid();
			$request->invoice->storeload();
		}
	}
 
	function CommonData()
	{
		return array( 'url', 'path', 'cookie', 'merchant', 'password' );
	}

	function action( $request )
	{
		$db = &JFactory::getDBO();

		if ( strpos( $this->settings['url'], '/sales.js' ) ) {
			$url = $this->settings['url'];
		} else {
			$url = $this->settings['url'] . '/sales.js';
		}

		$text = '<script id="pap_x2s6df8d" src="' . $url . '" type="text/javascript"></script>'
				. '<script type="text/javascript">'
				;

		$referrer = "";
		if ( !empty( $request->invoice->params['mi_affiliatepro_referrer'] ) ) {
			$referrer = $request->invoice->params['mi_affiliatepro_referrer'];
		}

		if ( !empty( $request->cart ) ) {
			$sid = 0;
			foreach ( $request->cart as $ciid => $ci ) {
				if ( !empty( $ci['is_total'] ) ) {
					continue;
				}

				$sid++;

				if ( $sid == 1 ) {
					$no = '';
				} else {
					$no = $sid;
				}

				$text .= 'var sale'.$no.' = PostAffTracker.createSale();'
					. "sale".$no.".setTotalCost('" . $ci['cost_total'] . "');"
					. "sale".$no.".setOrderID('" . $request->invoice->invoice_number . "');"
					. "sale".$no.".setProductID('" . $ci['obj']->id . "');"
					. "sale".$no.".setStatus('A');"
					;

				if ( !empty( $referrer ) ) {
					$text .= "sale".$no.".setAffiliateID('".$referrer."');";
				}
			}
		} else {
			$text .= 'var sale = PostAffTracker.createSale();'
				. "sale.setTotalCost('" . $request->invoice->amount . "');"
				. "sale.setOrderID('" . $request->invoice->invoice_number . "');"
				. "sale.setProductID('" . $request->plan->id . "');"
				. "sale.setStatus('A');"
				;

				if ( !empty( $referrer ) ) {
					$text .= "sale.setAffiliateID('".$referrer."');";
				}
		}


		$text .= 'PostAffTracker.register();'
				. '</script>';
// TODO:
// sale.setAffiliateID('testaff'); - force Affiliate ID
// sale.setCampaignID('11111111'); - campaign
// sale.setCustomCommission('10.23'); - force Custom Commission

// PostAffTracker.setChannel('testchannel'); - force channel
// PostAffTracker.setCookieValue('testchannel'); - force to register commission with this cookie value. The cookie value saves affiliate ID and campaign ID. It is in format AFFILIATEID_CAMPAIGNID, for example e2r48sv3_d3425s9f.
		$displaypipeline = new displayPipeline( $db );
		$displaypipeline->create( $request->metaUser->userid, 1, 0, 0, null, 1, $text );

		return true;
	}

	function later()
	{
        //-------------PAP affiliate approval-----------------------------
            if ( $response['valid'] ) {

                include_once('/PATH_TO/PapApi.class.php');
        
                $session = new Gpf_Api_Session("http://merkacity.postaffiliatepro.com/scripts/server.php");
                    if(!$session->login("USERNAME", "PASSWORD")) {
                        //die("Cannot login. Message: ".$session->getMessage());
                    }

                    // loading affiliate by his ID
                    $affiliate = new Pap_Api_Affiliate($session);
                    $affiliate->setData(25, $response['userid']);
                    //$affiliate->setUsername("infoweb@merkacity.com");
                    try {
                        if(!$affiliate->load()) {
                            //die('Cannot load affiliate, error: '.$affiliate->getMessage());
                        }
                    } catch (Exception $e) {
                        //die('Cannot load affiliate, error: '.$e->getMessage());
                      }

                   $affiliate->setStatus('A');  //A - Approved, D - Declined, P - Pending
                   try {
                       $affiliate->save();
                   } catch (Exception $e) {
                       //die('Failed to save affiliate: ' . $e->getMessage());
                   }

            }
        //------------------ end PAP ------------------------------

		/* PAN integration */
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, "http://USERNAME.postaffiliatepro.com/plugins/PayPal/paypal.php");
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $_POST);
		curl_exec($ch);
		/* end of PAN integration */
	}
}
?>
