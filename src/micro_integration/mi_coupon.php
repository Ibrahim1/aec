<?php
/**
 * @version $Id: mi_coupon.php
 * @package AEC - Account Control Expiration - Subscription component for Joomla! OS CMS
 * @subpackage Micro Integrations - Coupon
 * @copyright 2006/2007 Copyright (C) David Deutsch
 * @author David Deutsch <skore@skore.de> & Team AEC - http://www.globalnerd.org
 * @license GNU/GPL v.2 http://www.gnu.org/copyleft/gpl.html
 */

defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );

class mi_coupon
{
	function Info()
	{
		$info = array();
		$info['name'] = _AEC_MI_NAME_COUPON;
		$info['desc'] = _AEC_MI_DESC_COUPON;

		return $info;
	}

	function Settings( $params )
	{
		$settings = array();
		$settings['setupinfo'] = array( 'fieldset' );
		$settings['coupon_amount'] = array( 'inputC' );
		$settings['master_coupon'] = array( 'inputC' );
		$settings['bind_subscription'] = array( 'inputC' );
		$settings['rebill_new_coupons'] = array( 'inputC' );
		$settings['rebill_old_coupons'] = array( 'inputC' );
		return $settings;
	}

	function action( $params, $userid, $plan )
	{
		global $database, $mosConfig_live_site;



		// Send out X coupons based on master coupon Y
		// Bind coupons to plan
		// Add coupon usages on rebill
		// for that, coupons have to be remembered on invoice!

		return true;
	}

}
?>