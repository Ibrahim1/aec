<?php
/**
 * @version $Id: payos.php
 * @package AEC - Account Control Expiration - Membership Manager
 * @subpackage Processors - PayPal Buy Now
 * @copyright 2007-2008 Copyright (C) David Deutsch
 * @author David Deutsch <skore@skore.de> & Team AEC - http://www.globalnerd.org
 * @license GNU/GPL v.2 http://www.gnu.org/licenses/old-licenses/gpl-2.0.html or, at your option, any later version
 */

// Dont allow direct linking
defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );

class processor_payos extends URLprocessor
{
	function info()
	{
		$info = array();
		$info['name']				= 'payos';
		$info['longname']			= _CFG_PAYOS_LONGNAME;
		$info['statement']			= _CFG_PAYOS_LONGNAME;
		$info['description'] 		= _CFG_PAYOS_DESCRIPTION;
		$info['currencies']			= 'EUR,USD,GBP,AUD,CAD,JPY,NZD,CHF,HKD,SGD,SEK,DKK,PLN,NOK,HUF,CZK,MXN,ILS';
		$info['languages']			= 'GB,DE,FR,IT,ES,US,NL';
		$info['cc_list']			= 'visa,mastercard,discover,americanexpress,echeck,giropay';
		$info['recurring']			= 2;

		return $info;
	}

	function settings()
	{
		$settings = array();
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

		//$var['item_number']		= $request->metaUser->cmsUser->id;

		if ( !empty( $ppParams->customerid ) ) {
			$cust = $ppParams->customerid;
		} else {
			$cust = '';
		}

		$var['WMID']		= $this->settings['webmaster_id'];
		$var['CON']			= $this->settings['content_id'];
		$var['VAR1']		= $request->int_var['invoice'];
		$var['VAR2']		= "";//implode( "|", array() );
		$var['PAY_type']	= 2;
		$var['Customer']	= $cust;
		$var['_language']	= 'de';
		$var['Country']		= 'DE';

		if ( $this->settings['recurring'] ) {
			// Have to disable javascript checkout as it does not provide all variables we need
			$this->settings['javascript_checkout'] = 0;

			$var['AboAmount'] = $request->int_var['amount']['amount3'];

			switch ( $request->int_var['amount']['unit3'] ) {
				case 'D':
					$unit = 3;
					break;
				case 'W':
					$unit = 4;
					break;
				case 'M':
					$unit = 5;
					break;
				case 'Y':
					$unit = 6;
					break;
				default:
					$unit = 3;
					break;
			}

			$var['AboTermtype'] = $unit;
			$var['AboTermValue'] = $request->int_var['amount']['period3'];
		}

		if ( $this->settings['javascript_checkout'] ) {
			// Link to PayOS Javascript from Checkout link
			$var['_aec_checkout_onclick'] = 'PO_pay(\'' . $var['VAR1'] . '\',\'' . $var['VAR2'] . '\',\'' . $var['PAY_type'] . '\',\'' . $var['Customer'] . '\',\'' . $var['_language'] . '\',\'' . $var['Country'] . '\');return false;';

			foreach ( $var as $name => $var ) {
				if ( $name != '_aec_checkout_onclick' ) {
					unset( $var[$name] );
				}
			}

			$var['post_url'] = "http://www.payos.de/central/public/javascript_disabled";

			// Attach PayOS Javascript
			$var['_aec_html_head'] = '<!-- PayOS Version 1.0 Start -->
										<script type="text/javascript"
										src="http://www.payos.de/pay/PY_jsfunk.php?WMID=' . $this->settings['webmaster_id'] . '&CON=' . $this->settings['content_id'] . '">
										</script>
										<!-- PayOS Version 1.0 End -->';
		} else {
			$var['post_url']	= "http://www.payos.de/pay/index.php?";

		}

		return $var;
	}

	function parseNotification( $post )
	{
		global $database;

		$response = array();
		$response['invoice']		= $post['VAR1'];
		$response['amount_paid']	= str_replace( ",", ".", $post['pay_amount'] );

		return $response;
	}

	function validateNotification( $response, $post, $invoice )
	{
		$response['valid'] = 0;

		$allowedips = array( "213.69.111.70", "213.69.111.71", "213.69.234.76", "213.69.234.74", "195.126.100.14", "213.69.111.78" );

		if ( !in_array( $_SERVER["REMOTE_ADDR"], $allowedips ) ) {
			$response['pending_reason'] = "Wrong IP tried to send notification: " . $_SERVER["REMOTE_ADDR"];
			return $response;
		}

		$metaUser = new metaUser( $response['userid'] );

		$ppParams = $metaUser->meta->getProcessorParams( $this->id );

		// Check whether we have already recorded a profile
		if ( empty( $ppParams->customerid ) ) {
			// None found - create it
			$ppParams = new stdClass();
			$ppParams->customerid = $post['customer_id'];

			$metaUser->meta->setProcessorParams( $request->parent->id, $ppParams );
		} elseif ( $ppParams->customerid != $post['customer_id'] ) {
			// Profile found, but does not match, create new relation
			$ppParams->customerid = $post['customer_id'];

			$metaUser->meta->setProcessorParams( $request->parent->id, $ppParams );
		}

		if ( $this->settings['secret'] == $post['password'] ) {
			$response['valid'] = 1;

			echo 'OK=100';
		} else {
			echo 'OK=0 ERROR:PASSWORD MISMATCH';
		}

		return $response;
	}

}
?>
