<?php
/**
 * @version $Id: mi_email.php
 * @package AEC - Account Control Expiration - Membership Manager
 * @subpackage Micro Integrations - Email
 * @copyright 2006-2008 Copyright (C) David Deutsch
 * @author David Deutsch <skore@skore.de> & Team AEC - http://www.globalnerd.org
 * @license GNU/GPL v.2 http://www.gnu.org/licenses/old-licenses/gpl-2.0.html or, at your option, any later version
 */

// Dont allow direct linking
( defined('_JEXEC') || defined( '_VALID_MOS' ) ) or die( 'Direct Access to this location is not allowed.' );

class mi_email
{
	function Info()
	{
		$info = array();
		$info['name'] = _AEC_MI_NAME_EMAIL_FILES;
		$info['desc'] = _AEC_MI_DESC_EMAIL_FILES;

		return $info;
	}

	function Settings()
	{
		$settings = array();
		$settings['sender']				= array( 'inputE' );
		$settings['sender_name']		= array( 'inputE' );

		$settings['recipient']			= array( 'inputE' );

		$settings['subject']			= array( 'inputE' );
		$settings['text_html']			= array( 'list_yesno' );
		$settings['text']				= array( !empty( $this->settings['text_html'] ) ? 'editor' : 'inputD' );

		$settings['base_path']			= array( 'inputE' );
		$settings['path_list']			= array( 'inputD' );
		$settings['desc_list']			= array( 'inputD' );

		$rewriteswitches				= array( 'cms', 'user', 'expiration', 'subscription', 'plan', 'invoice' );
		$settings['rewriteInfo']		= array( 'fieldset', _AEC_MI_SET11_EMAIL, AECToolbox::rewriteEngineInfo( $rewriteswitches ) );

		return $settings;
	}

	function getMIform()
	{
		global $database;

		$settings = array();

		if ( !empty( $this->settings['user_choice_files'] ) ) {
			$list = explode( "\n", $this->settings['user_choice_files'] );

			$gr = array();
			foreach ( $list as $id => $choice ) {
				$choice = trim( $choice );

				$gr[] = mosHTML::makeOption( $id, $choice );
			}

			$settings['lists']['mi_email_files'] = mosHTML::selectList( $gr, 'mi_email_files', 'size="6"', 'value', 'text', '' );
		} else {
			return false;
		}

		$settings['mi_email_files'] = array( 'list', _MI_MI_USER_CHOICE_FILES_NAME, _MI_MI_USER_CHOICE_FILES_DESC );

		return $settings;
	}

	function relayAction( $request, $area )
	{
		if ( $area == '' ) {
			if ( !empty( $this->settings['text_first'] ) ) {
				if ( empty( $request->metaUser->objSubscription->previous_plan ) && ( $request->metaUser->objSubscription->status == 'Pending' ) ) {
					$area = '_first';
				}
			}
		}

		$message	= AECToolbox::rewriteEngineRQ( $this->settings['text' . $area], $request );
		$subject	= AECToolbox::rewriteEngineRQ( $this->settings['subject' . $area], $request );

		if ( empty( $message ) ) {
			return null;
		}

		$recipients = AECToolbox::rewriteEngineRQ( $this->settings['recipient'], $request );
		$recips = explode( ',', $recipients );

        $recipients2 = array();
        foreach ( $recips as $k => $email ) {
            $recipients2[$k] = trim( $email );
        }
        $recipients = $recipients2;

		mosMail( $this->settings['sender'], $this->settings['sender_name'], $recipients, $subject, $message, $this->settings['text' . $area . '_html'] );

		return true;
	}
}
?>
