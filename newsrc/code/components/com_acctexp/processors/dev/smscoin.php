<?php
/**
 * @version $Id: smscoin.php
 * @package AEC - Account Control Expiration - Membership Manager
 * @subpackage Processors - smscoin
 * @copyright 2007-2011 Copyright (C) smscoin.com
 * @license GNU/GPL v.2 http://www.gnu.org/licenses/old-licenses/gpl-2.0.html or, at your option, any later version
 */

// Dont allow direct linking
( defined('_JEXEC') || defined( '_VALID_MOS' ) ) or die( 'Direct Access to this location is not allowed.' );

class processor_smscoin extends POSTprocessor
{
	function info()
	{
		$info = array();
		$info['name']			= "smscoin";
		$info['longname']		= "SMSCoin";
		$info['statement']		= "Make payments with smscoin - it\'s fast, free and secure!";
		$info['currencies']		= "y.e.";
		$info['description']		= "SMSCoin is the easiest and most affordable payment gateway.";
		$info['recurring']		= 0;

		return $info;
	}

	function settings()
	{
		$settings = array();
		$settings['s_purse']		= "3849";
		$settings['password']		= "1234";
		$settings['s_clear_amount']	= '0';
		$settings['s_description']	= 'Payout';
	
		return $settings;
	}

	function backend_settings()
	{
		$settings = array();

		$settings['s_purse']		= array("inputE");
		$settings['password']		= array("inputE");
		$settings['s_clear_amount']	= array('list_yesno');
		$settings['s_description']	= array('inputE');

		$settings = AECToolbox::rewriteEngineInfo( null, $settings );

		return $settings;
	}

	function createGatewayLink( $request )
	{
		$var = array();
		$var['post_url']	= 'http://service.smscoin.com/bank/';
		$var['s_purse']		= $this->settings['s_purse'];
		$var['s_order_id']	= time();
		$var['invoice']		= $request->invoice->invoice_number;
		if ( is_array( $request->int_var['amount'] ) ) {
			$var['s_amount']= $request->int_var['amount']['amount'];
		} else {
			$var['s_amount']= $request->int_var['amount'];
		}
		$var['s_clear_amount']	= $this->settings['s_clear_amount'];
		$var['s_description']	= $this->settings['s_description'];
		$var['s_sign']		= $this->ref_sign($this->settings['s_purse'], 
							$var['s_order_id'], 
							$var['s_amount'], 
							$this->settings['s_clear_amount'],
							$this->settings['s_description'], 
							$this->settings['password']);
		return $var;
	}

	function parseNotification( $post )
	{
		$s_purse	= $post['s_purse'];
		$s_order_id	= $post['s_order_id'];
		$s_amount	= $post['s_amount'];
		$s_clear_amount	= $post['s_clear_amount'];
		$s_phone	= $post['s_phone'];
		$s_inv		= $post['s_inv'];
		$s_sign_v2	= $post['s_sign_v2'];
		$item_name	= $post['item_name'];

		$response = array();

		$response['invoice'] = $post['invoice'];

		if ( $s_sign_v2 == $this->ref_sign($this->settings['password'], $s_purse, $s_order_id, $s_amount, $s_clear_amount, $s_inv, $s_phone)) {
			$response['valid'] = 1;   
		} else {
			$response['valid'] = 0;
		}

		return $response;
	}

	function validateNotification( $response, $post, $invoice )
	{
		$s_purse	= $post['s_purse'];
		$s_order_id	= $post['s_order_id'];
		$s_amount	= $post['s_amount'];
		$s_clear_amount	= $post['s_clear_amount'];
		$s_phone	= $post['s_phone'];
		$s_inv		= $post['s_inv'];
		$s_sign_v2	= $post['s_sign_v2'];
		$item_name	= $post['item_name'];

		$response = array();

		$response['invoice'] = $post['invoice'];

		if ( $s_sign_v2 == $this->ref_sign($this->settings['password'], $s_purse, $s_order_id, $s_amount, $s_clear_amount, $s_inv, $s_phone)) {
			$response['valid'] = 1;    
		} else {
			$response['valid'] = 0;
		}

		return $response;
	}

	function ref_sign()
	{
		$params = func_get_args();
		$prehash = implode("::", $params);
		return md5($prehash);
	}

}

?>
