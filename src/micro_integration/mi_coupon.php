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
		$settings['master_coupon'] = array( 'inputC' );
		$settings['bind_subscription'] = array( 'list_yesno' );
		$settings['create_new_coupons'] = array( 'inputC' );
		$settings['max_reuse'] = array( 'inputC' );
		$settings['mail_out_coupons'] = array( 'list_yesno' );
		$settings['always_new_coupons'] = array( 'list_yesno' );
		$settings['inc_old_coupons'] = array( 'inputC' );

		$settings['sender']				= array( 'inputE' );
		$settings['sender_name']		= array( 'inputE' );

		$settings['recipient']			= array( 'inputE' );

		$settings['subject']			= array( 'inputE' );
		$settings['text_html']			= array( 'list_yesno' );
		$settings['text']				= array( $params['text_html'] ? 'editor' : 'inputD' );

		$rewriteswitches				= array( 'cms', 'user', 'expiration', 'subscription', 'plan', 'invoice' );
		$settings['rewriteInfo']		= array( 'fieldset', _AEC_MI_SET11_EMAIL, AECToolbox::rewriteEngineInfo( $rewriteswitches ) );

		return $settings;
	}

	function action( $params, $metaUser, $plan, $invoice )
	{
		global $database, $mosConfig_live_site;

		$userflags = $metaUser->focusSubscription->getMIflags( $plan->id, $this->id );

		$total_coupons = array();

		$existing_coupons = array();
		if ( is_array( $userflags ) ) {
			if ( !empty( $userflags['COUPONS'] ) ) {
				$existing_coupons = explode( ',', $userflags['COUPONS'] );
				$total_coupons = array_merge( $total_coupons, $existing_coupons );

				if ( !empty( $params['inc_old_coupons'] ) ) {
					foreach ( $existing_coupons as $cid ) {
						$ocph = new couponHandler();
						$ocph->load( $cid );
						$ocph->coupon->active = 1;
						$ocph->restrictions['max_reuse'] + $params['inc_old_coupons'];
						$ocph->coupon->setParams( $ocph->restrictions, 'restrictions' );
						$ocph->coupon->check();
						$ocph->coupon->store();
					}
				}
			}
		}

		$newcodes = array();
		if ( ( !empty( $existing_coupons ) && !empty( $settings['always_new_coupons'] ) ) || empty( $existing_coupons ) ) {
			if ( !empty( $settings['create_new_coupons'] ) && !empty( $settings['master_coupon'] ) ) {

				$cph = new CouponHandler(  );
				$cph->load( $settings['master_coupon'] );

				if ( is_object( $cph->coupon ) ) {
					for ( $i=0; $i<$settings['create_new_coupons']; $i++ ) {
						$newcode = $cph->coupon->generateCouponCode();
						$newcodes[] = $newcode;

						$cph->coupon->id = 0;
						$cph->coupon->coupon_code = $newcode;
						$cph->coupon->active = 1;

						if ( !empty( $settings['create_new_coupons'] ) ) {
							$ocph->restrictions['max_reuse'] = $settings['max_reuse'];
						} else {
							$ocph->restrictions['max_reuse'] = 1;
						}

						if ( !empty( $settings['bind_subscription'] ) ) {
							$cph->restrictions['depend_on_subscr_id'] = 1;
							$cph->restrictions['subscr_id_dependency'] = $metaUser->focusSubscription->id;
							$cph->coupon->setParams( $cph->restrictions, 'restrictions' );
						}

						$cph->coupon->check();
						$cph->coupon->store();
					}

					if ( !empty( $settings['mail_out_coupons'] ) ) {
						$this->mailOut( $params, $metaUser, $plan, $invoice, $newcodes );
					}
				}
			}
		}

		$total_coupons = array_merge( $total_coupons, $newcodes );

		$newflags['coupons'] = implode( ',', $total_coupons );

		$metaUser->objSubscription->setMIflags( $plan->id, $this->id, $newflags );

		return true;
	}

	function mailOut( $params, $metaUser, $plan, $invoice, $newcodes )
	{
		$message	= sprintf( $message, implode( "\n", $newcodes ) );

		$message	= AECToolbox::rewriteEngine( $params['text'], $metaUser, $plan, $invoice );
		$subject	= AECToolbox::rewriteEngine( $params['subject'], $metaUser, $plan, $invoice );

		if ( empty( $message ) ) {
			return false;
		}

		$recipients = explode( ',', $params['recipient'] );

		foreach ( $recipients as $current => $email ) {
			$recipients[$current] = AECToolbox::rewriteEngine( trim( $email ), $metaUser, $plan, $invoice );
		}

		mosMail( $params['sender'], $params['sender_name'], $recipients, $subject, $message, $params['text_html'] );

		return true;
	}

}
?>
