<?php
/**
 * @version $Id: mi_email_multi.php
 * @package AEC - Account Control Expiration - Membership Manager
 * @subpackage Micro Integrations - Multi Email
 * @copyright 2006-2009 Copyright (C) David Deutsch
 * @author David Deutsch <skore@skore.de> & Team AEC - http://www.valanx.org
 * @license GNU/GPL v.2 http://www.gnu.org/licenses/old-licenses/gpl-2.0.html or, at your option, any later version
 */

// Dont allow direct linking
( defined('_JEXEC') || defined( '_VALID_MOS' ) ) or die( 'Direct Access to this location is not allowed.' );

class mi_email_multi extends MI
{
	function Info()
	{
		$info = array();
		$info['name'] = _AEC_MI_NAME_EMAIL_MULTI;
		$info['desc'] = _AEC_MI_DESC_EMAIL_MULTI;

		return $info;
	}

	function Settings()
	{
		$settings = array();
		$settings['sender']				= array( 'inputE' );
		$settings['sender_name']		= array( 'inputE' );

		$settings['emails_count']		= array( 'inputC' );

		if ( !empty( $this->settings['emails_count'] ) ) {
			for ( $i=0; $i<$this->settings['emails_count']; $i++ ) {
				$pf = 'email_' . $i . '_';

				$settings[$pf.'timing']				= array( 'inputE' );
				$settings[$pf.'recipient']			= array( 'inputE' );
				$settings[$pf.'subject']			= array( 'inputE' );
				$settings[$pf.'text_html']			= array( 'list_yesno' );
				$settings[$pf.'text']				= array( !empty( $this->settings[$pf.'text_html'] ) ? 'editor' : 'inputD' );
			}
		}

		$rewriteswitches				= array( 'cms', 'user', 'expiration', 'subscription', 'plan', 'invoice' );
		$settings['rewriteInfo']		= array( 'fieldset', _AEC_MI_SET11_EMAIL, AECToolbox::rewriteEngineInfo( $rewriteswitches ) );

		return $settings;
	}

	function action( $request )
	{
		if ( !empty( $this->settings['emails_count'] ) && !empty( $this->settings['sender'] ) && !empty( $this->settings['sender_name'] ) ) {
			for ( $i=0; $i<$this->settings['emails_count']; $i++ ) {
				$pf = 'email_' . $i . '_';

				if ( !empty( $this->settings[$pf.'recipient'] ) && !empty( $this->settings[$pf.'timing'] ) ) {
					if ( strpos( $this->settings[$pf.'timing'], '-' ) === 0 ) {
						// Go back from Expiration date
						$tstamp = strtotime( $request->metaUser->focusSubscription->expiration );
					} else {
						// Go from current timestamp
						$tstamp = time();
					}

					$due_date = strtotime( $this->settings[$pf.'timing'], $tstamp );

					$this->issueEvent( $request, 'email', $due_date, array(), array( 'emailid' => $i ) );
				}
			}
		}

		return true;
	}

	function aecEventHookEmail( $request )
	{
		$pf = 'email_' . $request->event->params['emailid'] . '_';

		$message	= AECToolbox::rewriteEngineRQ( $this->settings[$pf.'text'], $request );
		$subject	= AECToolbox::rewriteEngineRQ( $this->settings[$pf.'subject'], $request );

		if ( empty( $message ) ) {
			return null;
		}

		$recipients = AECToolbox::rewriteEngineRQ( $this->settings[$pf.'recipient'], $request );
		$recips = explode( ',', $recipients );

        $recipients2 = array();
        foreach ( $recips as $k => $email ) {
            $recipients2[$k] = trim( $email );
        }
        $recipients = $recipients2;

		mosMail( $this->settings['sender'], $this->settings['sender_name'], $recipients, $subject, $message, $this->settings[$pf.'text_html'] );
	}
}
?>
