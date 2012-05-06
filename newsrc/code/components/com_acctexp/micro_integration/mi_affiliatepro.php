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
		$info['type'] = array( 'tracking.affiliate', 'vendor.qualityunit' );

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

		if ( !file_exists( $this->settings['path'] . 'api/PapApi.class.php' ) ) {
			aecQuickLog( 'warning', 'mi,invoice_creation,mi_affiliatepro', 'Could not locate the Affiliate Pro API at this Directory. Please install the PHP API into the /api folder in your PAP directory.', 32 );

			if ( is_dir( $this->settings['path'] . 'api/' ) ) {
				$files = AECToolbox::getFileArray( $this->settings['path'] . 'api/', false, true );

				aecQuickLog( 'warning', 'mi,invoice_creation,mi_affiliatepro', 'Directory exists, though.', 32 );aecDebug($files);
			}

			return null;
		}

		include_once( $this->settings['path'] . 'api/PapApi.class.php' );

		if ( strpos( $this->settings['url'], '/sales.js' ) ) {
			$url = str_replace( '/sales.js', '/scripts/server.php', $this->settings['url'] );
		} else {
			if ( substr( $this->settings['url'], -1, 1 ) != '/' ) {
				$url = $this->settings['url'] . '/scripts/server.php';
			} else {
				$url = $this->settings['url'] . 'scripts/server.php';
			}
		}

		$session = new Gpf_Api_Session( $url ); 

		if( !$session->login( $this->settings['merchant'], $this->settings['password'] ) ) { 
			aecQuickLog( 'warning', 'mi,invoice_creation,mi_affiliatepro', "Cannot login. Message: ".$session->getMessage(), 32 );

			return null;
		}

		$clickTracker = new Pap_Api_ClickTracker($session);

		$affiliate = $clickTracker->getAffiliate();

		if ( is_object( $affiliate ) ) {
			$affililate_id = $affiliate->getValue('userid');

			if ( $affililate_id ) {
				$request->invoice->params['mi_affiliatepro_referrer'] = $affililate_id;
				$request->invoice->storeload();
			}
		}
	}
 
	function CommonData()
	{
		return array( 'url', 'path', 'cookie', 'merchant', 'password' );
	}

	function action( $request )
	{
		if ( strpos( $this->settings['url'], '/scripts/salejs.php' ) ) {
			$url = $this->settings['url'];
		} else {
			if ( substr( $this->settings['url'], -1, 1 ) != '/' ) {
				$url = $this->settings['url'] . '/scripts/salejs.php';
			} else {
				$url = $this->settings['url'] . 'scripts/salejs.php';
			}
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
		// sale.setCampaignID('11111111'); - campaign
		// sale.setCustomCommission('10.23'); - force Custom Commission

		// PostAffTracker.setChannel('testchannel'); - force channel
		// PostAffTracker.setCookieValue('testchannel'); - force to register commission with this cookie value. The cookie value saves affiliate ID and campaign ID. It is in format AFFILIATEID_CAMPAIGNID, for example e2r48sv3_d3425s9f.

		$db = &JFactory::getDBO();

		$displaypipeline = new displayPipeline( $db );
		$displaypipeline->create( $request->metaUser->userid, 1, 0, 0, null, 1, $text );

		return true;
	}

}
?>
