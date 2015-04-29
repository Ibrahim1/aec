<?php
/**
 * @version $Id: mi_aecinvoicedetails.php
 * @package AEC - Account Control Expiration - Membership Manager
 * @subpackage Micro Integrations - User Subscription Details
 * @copyright 2012 Copyright (C) David Deutsch
 * @author David Deutsch <skore@valanx.org> & Team AEC - http://www.valanx.org
 * @license GNU/GPL v.3 http://www.gnu.org/licenses/gpl.html or, at your option, any later version
 */

// Dont allow direct linking
defined('_JEXEC') or die( 'Direct Access to this location is not allowed.' );

require_once( dirname(__FILE__) . '/../aecuserdetails/aecuserdetails.php' );

class mi_aecinvoicedetails extends mi_aecuserdetails
{
	public function Info()
	{
		$info = array();
		$info['name'] = JText::_('AEC_MI_NAME_AECINVOICEDETAILS');
		$info['desc'] = JText::_('AEC_MI_DESC_AECINVOICEDETAILS');
		$info['type'] = array( 'aec.membership', 'vendor.valanx' );

		return $info;
	}

	public function Settings()
	{
		$settings = parent::Settings();

		unset( $settings['emulate_reg'] );
		unset( $settings['display_emul'] );

		return $settings;
	}

	public function saveParams( $params )
	{
		foreach ( $params as $n => $v ) {
			if ( !empty( $v ) && ( strpos( $n, '_short' ) ) ) {
				$params[$n] = preg_replace( '/[^a-z0-9._+-]+/i', '', trim( strtolower( $v ) ) );
			}
		}

		return $params;
	}

	public function admin_form( $request )
	{
		return null;
	}

    public function profile_form( $request )
	{
		return null;
	}

	public function before_invoice_confirm( $request )
	{
		return true;
	}

	public function action( $request, $jprofile=false )
	{
		if ( is_object($request->invoice) && !empty($request->params) ) {
			$request->invoice->addParams( $request->params );
			$request->invoice->storeload();
		}
	}
}
