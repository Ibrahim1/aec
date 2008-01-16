<?php
/**
 * @version $Id: mi_email.php
 * @package AEC - Account Control Expiration - Subscription component for Joomla! OS CMS
 * @subpackage Micro Integrations - Email
 * @copyright 2006/2007 Copyright (C) David Deutsch
 * @author David Deutsch <skore@skore.de> & Team AEC - http://www.globalnerd.org
 * @license GNU/GPL v.2 http://www.gnu.org/copyleft/gpl.html
 */

// Copyright (C) 2006-2007 David Deutsch
// All rights reserved.
// This source file is part of the Account Expiration Control Component, a  Joomla
// custom Component By Helder Garcia and David Deutsch - http://www.globalnerd.org
//
// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License (GPL)
// as published by the Free Software Foundation; either version 2
// of the License, or (at your option) any later version.
//
// Please note that the GPL states that any headers in files and
// Copyright notices as well as credits in headers, source files
// and output (screens, prints, etc.) can not be removed.
// You can extend them with your own credits, though...
//
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with this program; if not, write to the Free Software
// Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
//
// The "GNU General Public License" (GPL) is available at
// http://www.gnu.org/copyleft/gpl.html.

// Dont allow direct linking
defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );

class mi_email
{
	function Info()
	{
		$info = array();
		$info['name'] = _AEC_MI_NAME_EMAIL;
		$info['desc'] = _AEC_MI_DESC_EMAIL;

		return $info;
	}

	function Settings( $params )
	{
		$settings = array();
		$settings['sender']				= array( 'inputE' );
		$settings['sender_name']		= array( 'inputE' );

		$settings['recipient']			= array( 'inputE' );

		$settings['subject']			= array( 'inputE' );
		$settings['text_html']			= array( 'list_yesno' );
		$settings['text']				= array( $params['text_html'] ? 'editor' : 'inputD' );

		$settings['subject_first']		= array( 'inputE' );
		$settings['text_first_html']	= array( 'list_yesno' );
		$settings['text_first']			= array( $params['text_first_html'] ? 'editor' : 'inputD' );

		$settings['subject_exp']		= array( 'inputE' );
		$settings['text_exp_html']		= array( 'list_yesno' );
		$settings['text_exp']			= array( $params['text_exp_html'] ? 'editor' : 'inputD' );

		$settings['subject_pre_exp']	= array( 'inputE' );
		$settings['text_pre_exp_html']	= array( 'list_yesno' );
		$settings['text_pre_exp']		= array( $params['text_pre_exp_html'] ? 'editor' : 'inputD' );

		$rewriteswitches				= array( 'cms', 'user', 'expiration', 'subscription', 'plan', 'invoice' );
		$settings['rewriteInfo']		= array( 'fieldset', _AEC_MI_SET11_EMAIL, AECToolbox::rewriteEngineInfo( $rewriteswitches ) );

		return $settings;
	}

	function pre_expiration_action( $params, $metaUser, $plan )
	{
		$userflags = $metaUser->objSubscription->getMIflags( $plan->id, $this->id );

		if ( is_array( $userflags ) ) {
			if ( isset( $userflags['EXP_MAIL_SENT'] ) ) {
				if ( !( time() > $userflags['EXP_MAIL_ABANDONCHECK'] ) ) {
					return false;
				}
			}
		}

		$newflags['exp_mail_abandoncheck']	= strtotime( $metaUser->objSubscription->expiration );
		$newflags['exp_mail_sent']			= time();

		$metaUser->objSubscription->setMIflags( $plan->id, $this->id, $newflags );

		return $this->mailOut( $params, $metaUser, $plan, '_pre_exp' );
	}

	function expiration_action( $params, $metaUser, $plan )
	{
		return $this->mailOut( $params, $metaUser, $plan, '_exp' );
	}

	function action( $params, $metaUser, $invoice, $plan )
	{
		return $this->mailOut( $params, $metaUser, $plan, '' );
	}

	function mailOut( $params, $metaUser, $plan, $area )
	{
		if ( $area == '' ) {
			if ( !empty( $params['text_first'] ) ) {
				if ( empty( $metaUser->objSubscription->previous_plan ) ) {
					$area = '_first';
				}
			}
		}

		$message	= AECToolbox::rewriteEngine( $params['text' . $area], $metaUser, $plan );
		$subject	= AECToolbox::rewriteEngine( $params['subject' . $area], $metaUser, $plan );

		if ( empty( $message ) ) {
			return null;
		}

		$recipients = explode( ',', $params['recipient'] );

		foreach ( $recipients as $current => $email ) {
			$recipients[$current] = AECToolbox::rewriteEngine( trim( $email ), $metaUser, $plan );
		}

		mosMail( $params['sender'], $params['sender_name'], $recipients, $subject, $message, $params['text' . $area . '_html'] );

		return true;
	}
}
?>