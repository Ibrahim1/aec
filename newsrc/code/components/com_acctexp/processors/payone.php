<?php
/**
 * @version $Id: payone.php
 * @package AEC - Account Control Expiration - Membership Manager
 * @subpackage Processors - Payone Frontend
 * @copyright 2012 Copyright (C) David Deutsch
 * @author David Deutsch <skore@valanx.org> & Team AEC - http://www.valanx.org
 * @license GNU/GPL v.3 http://www.gnu.org/licenses/gpl.html or, at your option, any later version
 */

// Dont allow direct linking
( defined('_JEXEC') || defined( '_VALID_MOS' ) ) or die( 'Direct Access to this location is not allowed.' );

class processor_payone extends POSTprocessor
{
	function info()
	{
		$info = array();
		$info['name']			= 'payone';
		$info['longname']		= JText::_('CFG_PAYONE_LONGNAME');
		$info['statement']		= JText::_('CFG_PAYONE_STATEMENT');
		$info['description']	= JText::_('CFG_PAYONE_DESCRIPTION');
		$info['currencies']		= 'EUR,USD,GBP,AUD,CAD,JPY,NZD,CHF,HKD,SGD,SEK,DKK,PLN,NOK,HUF,CZK,MXN,ILS,BRL,MYR,PHP,TWD,THB,ZAR';
		$info['languages']		= AECToolbox::getISO639_1_codes();
		$info['cc_list']		= 'visa,mastercard,discover,americanexpress,echeck,giropay';
		$info['recurring']		= 2;

		return $info;
	}

	function settings()
	{
		$settings = array();
		$settings['mid']			= '10001';
		$settings['aid']			= '10002';
		$settings['key']			= 'secret';
		$settings['portalid']		= '2000001';
		$settings['language']		= 'DE';
		$settings['currency']		= 'USD';
		$settings['item_name']		= sprintf( JText::_('CFG_PROCESSOR_ITEM_NAME_DEFAULT'), '[[cms_live_site]]', '[[user_name]]', '[[user_username]]' );
		$settings['customparams']	= "";

		return $settings;
	}

	function backend_settings()
	{
		$settings = array();

		$settings['testmode']				= array( 'toggle' );
		$settings['mid']					= array( 'inputC' );
		$settings['aid']					= array( 'inputC' );
		$settings['key']					= array( 'inputC' );
		$settings['language']				= array( 'list_language' );
		$settings['currency']				= array( 'list_currency' );
		$settings['item_name']				= array( 'inputE' );
		$settings['customparams']			= array( 'inputD' );

		$settings = AECToolbox::rewriteEngineInfo( null, $settings );

		return $settings;
	}

	function CustomPlanParams()
	{
		return array( 'productid' => array( 'inputC' ) );
	}

	function createGatewayLink( $request )
	{
		$var['post_url']	= 'https://secure.pay1.de/frontend/';

		$var['portalid']	= $this->settings['portalid'];
		$var['aid']			= $this->settings['aid'];

		$var['mode']		= $this->settings['testmode'] ? 'test' : 'live';

		if ( is_array( $request->int_var['amount'] ) && !empty( $request->int_var['planparams']['productid'] ) ) {
			$var['request']		= 'createaccess';

			$var['productid'] = $request->int_var['planparams']['productid'];
		} else {
			$var['request']		= 'authorization';

			$var['amount']		= (int) ( $request->int_var['amount']*100 );
			$var['currency']	= $this->settings['currency'];

			$var['id[1]']		= $this->settings['currency'];
			$var['pr[1]']		= $var['amount'];
			$var['no[1]']		= '1';
			$var['de[1]']		= AECToolbox::rewriteEngineRQ( $this->settings['item_name'], $request );
		}
		
		$var['reference']		= $request->invoice->invoice_number;

		$var['backurl']			= AECToolbox::deadsureURL( 'index.php?option=com_acctexp&amp;task=cancel' );
		$var['successurl']		= AECToolbox::deadsureURL( 'index.php?option=com_acctexp&amp;task=thanks' );
		//$var['successurl']		= AECToolbox::deadsureURL( 'index.php?option=com_acctexp&amp;task=payonenotification' );

		$var['language']		= strtolower( $this->settings['language'] );

		$var['hash']			= $this->getHash( $var );

		return $var;
	}

	function parseNotification( $post )
	{
		$response = array();
		$response['invoice']			= $post['reference'];
		$response['amount_currency']	= $post['currency'];
		$response['amount_paid']		= $post['receiveable'];

		return $response;
	}

	function validateNotification( $response, $post, $invoice )
	{
		$response['valid'] = 0;

		switch ( $post['txaction'] ) {
			case 'paid':
				$response['valid'] = 1;
				break;
			case 'refund':
				$response['delete'] = 1;
				break;
			case 'cancelation':
				$response['chargeback'] = 1;
				break;
			default:
				$response['pending_reason'] = ucfirst( $post['txaction'] );
		}

		return $response;
	}

	function getHash( $var )
	{
		$params = array(	'aid', 'access_aboperiod', 'access_aboprice', 'access_canceltime', 'access_expiretime',
							'access_starttime', 'access_period', 'access_price', 'access_vat', 'accessname',
							'accesscode', 'addresschecktype', 'amount', 'backurl', 'checktype',
							'clearingtype', 'currency', 'customerid', 'consumerscoretype', 'due_time',
							'display_name', 'display_address', 'autosubmit',
							'eci', 'encoding', 'errorurl', 'exiturl', 'invoice_deliverymode',
							'invoiceappendix', 'invoiceid', 'mid', 'mode', 'narrative_text',
							'param', 'portalid', 'productid', 'reference', 'request',
							'responsetype', 'settleperiod', 'settletime', 'storecarddata', 'successurl',
							'targetwindow', 'userid', 'vaccountname', 'vreference'
						);

		$xparams = array( 'id','pr','no','de','ti','va' );

		$data = array();
		foreach ( $var as $k => $v ) {
			if ( in_array( $k, $params ) || in_array( substr( $k, 0, 2 ), $params ) ) {
				$data[$k] = $v;
			}
		}

		asort( $data );

		return md5( implode( '', $data ) . $this->settings['key'] );
	}
}
?>
