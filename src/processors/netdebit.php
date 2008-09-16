<?php
/**
 * @version $Id: netdebit.php
 * @package AEC - Account Control Expiration - Membership Manager
 * @subpackage Processors - PayPal Buy Now
 * @copyright 2007-2008 Copyright (C) David Deutsch
 * @author David Deutsch <skore@skore.de> & Team AEC - http://www.globalnerd.org
 * @license GNU/GPL v.2 http://www.gnu.org/licenses/old-licenses/gpl-2.0.html or, at your option, any later version
 */

// Dont allow direct linking
defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );

class processor_netdebit extends URLprocessor
{
	function info()
	{
		$info = array();
		$info['name']				= 'netdebit';
		$info['longname']			= _CFG_NETDEBIT_LONGNAME;
		$info['statement']			= _CFG_NETDEBIT_LONGNAME;
		$info['description'] 		= _CFG_NETDEBIT_DESCRIPTION;
		$info['currencies']			= 'EUR,USD,GBP,AUD,CAD,JPY,NZD,CHF,HKD,SGD,SEK,DKK,PLN,NOK,HUF,CZK,MXN,ILS';
		$info['languages']			= 'GB,DE,FR,IT,ES,US,NL';
		$info['cc_list']			= 'visa,mastercard,discover,americanexpress,echeck,giropay';
		$info['recurring']			= 0;

		return $info;
	}

	function settings()
	{
		$settings = array();
		$settings['testmode']		= 0;

		$settings['webmaster_id']	= 'webmaster';
		$settings['content_id']		= 'content_id';
		$settings['secret']			= 'secret';

		$settings['javascript_checkout']	= 0;

		$settings['customparams']	= "";

		return $settings;
	}

	function backend_settings()
	{
		$settings = array();
		$settings['testmode']		= array( 'list_yesno' );

		$settings['webmaster_id']	= array( 'inputC' );
		$settings['content_id']		= array( 'inputC' );
		$settings['secret']			= array( 'inputC' );

		$settings['javascript_checkout']	= array( 'list_yesno' );

		$settings['customparams']	= array( 'inputD' );

		$settings = AECToolbox::rewriteEngineInfo( null, $settings );

		return $settings;
	}

	function createGatewayLink( $request )
	{
		global $mosConfig_live_site;

		$ppParams = $request->metaUser->meta->getProcessorParams( $request->parent->id );

		$var['invoice']			= $request->int_var['invoice'];

		$var['item_number']		= $request->metaUser->cmsUser->id;
		$var['item_name']		= AECToolbox::rewriteEngine( $this->settings['item_name'], $request->metaUser, $request->new_subscription, $request->invoice );

		$var1 = $request->int_var['invoice'];
		$var2 = "";//implode( "|", array() );
		$type = "";
		$lang = 'de';
		$coun = 'DE';

		if ( !empty( $ppParams->customerid ) ) {
			$cust = $ppParams->customerid;
			$iscust	= 1;
		} else {
			$cust = '';
			$iscust	= 0;
		}

		if ( $this->settings['javascript_checkout'] ) {
			$var['post_url'] = "http://www.netdebit.de/central/public/javascript_disabled";

			// Attach PayOS Javascript
			$var['_aec_html_head'] = '<!-- Beginn NetDebit - Payment V3.0 -->
										<script LANGUAGE="JavaScript"
										src="http://www.fixport.de/secuSYS/FUNK.php?F=' . $this->settings['webmaster_id'] . '&PID=' . $this->settings['webmaster_id'] . '&CON=' . $this->settings['webmaster_id'] . '&SID=' . $this->settings['webmaster_id'] . '" type="text/javascript"></script>
										<!-- Ende   NetDebit - Payment V3.0 -->';

			// Link to PayOS Javascript from Checkout link
			$var['_aec_checkout_onclick'] = 'GATE_NDV2(\'' . $var1 . '\',\'' . $var2 . '\',\'' . $type . '\',\'' . $cust . '\',\'' . $lang . '\',\'' . $coun . '\');return false;';
		} else {
			$var['post_url']	= "https://www.netdebit-payment.de/pay/index.php?";

			$var['CON']			= $this->settings['content_id'];
			$var['WMID']		= $this->settings['webmaster_id'];
			$var['GoVAR1']		= $var1;
			$var['GoVAR2']		= $var2;
			$var['GoZAH']		= $type; //1 = Lastschrift, 2 = Kreditkarte
			$var['GoPOS']		= $type; //ausgewählte Tarifposition
			$var['GoKUN']		= $iscust;
			$var['GoKNR']		= $cust;
		}

		return $var;
	}

	function parseNotification( $post )
	{
		global $database;

		$response = array();
		$response['invoice']		= $post['VAR1'];
		$response['amount_paid']	= $post['pay_amount'];

		return $response;
	}

	function validateNotification( $response, $post, $invoice )
	{
		$response['valid'] = 0;

		echo 'OK=100';

		$allowedips = array( "213.69.111.70", "213.69.111.71", "213.69.234.76", "213.69.234.74", "195.126.100.14", "213.69.111.78" );

		if ( !in_array( $_SERVER["REMOTE_ADDR"], $allowedips ) ) {
			$response['pending_reason'] = "Wrong IP tried to send notification: " . $_SERVER["REMOTE_ADDR"];
			return $response;
		}

		$ppParams = $request->metaUser->meta->getProcessorParams( $request->parent->id );

		// Check whether we have already recorded a profile
		if ( empty( $ppParams->customerid ) ) {
			// None found - create it
			$ppParams = new stdClass();
			$ppParams->customerid = $post['customer_id'];

			$request->metaUser->meta->setProcessorParams( $request->parent->id, $ppParams );
		} elseif ( $ppParams->customerid != $post['customer_id'] ) {
			// Profile found, but does not match, create new relation
			$ppParams->customerid = $post['customer_id'];

			$request->metaUser->meta->setProcessorParams( $request->parent->id, $ppParams );
		}

		if ( $this->settings['secret'] == $post['password'] ) {
			$response['valid'] = 1;
		}

		return $response;
	}

}
?>
