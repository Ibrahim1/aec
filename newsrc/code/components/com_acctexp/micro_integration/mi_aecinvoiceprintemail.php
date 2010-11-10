<?php
/**
 * @version $Id: mi_aecinvoiceprintemail.php
 * @package AEC - Account Control Expiration - Membership Manager
 * @subpackage Micro Integrations - Invoice Printout Mailout
 * @copyright 2006-2010 Copyright (C) David Deutsch
 * @author David Deutsch <skore@skore.de> & Team AEC - http://www.valanx.org
 * @license GNU/GPL v.2 http://www.gnu.org/licenses/old-licenses/gpl-2.0.html or, at your option, any later version
 */

// Dont allow direct linking
( defined('_JEXEC') || defined( '_VALID_MOS' ) ) or die( 'Direct Access to this location is not allowed.' );

class mi_aecinvoiceprintemail
{
	function Info()
	{
		$info = array();
		$info['name'] = _AEC_MI_NAME_AECINVOICEPRINTEMAIL;
		$info['desc'] = _AEC_MI_DESC_AECINVOICEPRINTEMAIL;

		return $info;
	}

	function Settings()
	{
		$rewriteswitches				= array( 'cms', 'user', 'expiration', 'subscription', 'plan', 'invoice' );

		$settings = array();
		$settings['sender']				= array( 'inputE' );
		$settings['sender_name']		= array( 'inputE' );

		$settings['recipient']			= array( 'inputE' );
		$settings['customcss']			= array( 'inputD' );
		$settings						= AECToolbox::rewriteEngineInfo( $rewriteswitches, $settings );

		$settings['aectab_reg']			= array( 'tab', 'Modify Invoice', 'Modify Invoice' );

		$s = array( "before_header", "header", "after_header", "address",
					"before_content", "after_content",
					"before_footer", "footer", "after_footer",
					);

 		$modelist = array();
		$modelist[] = JHTML::_('select.option', "none", AEC_TEXTMODE_NONE );
		$modelist[] = JHTML::_('select.option', "before", AEC_TEXTMODE_BEFORE );
		$modelist[] = JHTML::_('select.option', "after", AEC_TEXTMODE_AFTER );
		$modelist[] = JHTML::_('select.option', "replace", AEC_TEXTMODE_REPLACE );
		$modelist[] = JHTML::_('select.option', "delete", AEC_TEXTMODE_DELETE );

		$settings = array();
		foreach ( $s as $x ) {
			$y = $x."_mode";

			if ( isset( $this->settings[$y] ) ) {
				$dv = $this->settings[$y];
			} else {
				$dv = null;
			}

			$settings[$y]			= array( "list" );
			$settings['lists'][$y]	= JHTML::_('select.genericlist', $modelist, $y, 'size="1"', 'value', 'text', $dv );

			$settings[$x]			= array( "editor" );
		}

		return $settings;
	}

	function relayAction( $request )
	{
		if ( $request->action == 'action' ) {
			if ( !empty( $this->settings['text_first'] ) ) {
				if ( empty( $request->metaUser->objSubscription->previous_plan ) ) {
					$request->area = '_first';
				}
			}
		}

		if ( !isset( $this->settings['text' . $request->area] ) || !isset( $this->settings['subject' . $request->area] ) ) {
			return null;
		}

		$subject	= AECToolbox::rewriteEngineRQ( $this->settings['subject' . $request->area], $request );

		$message = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>' . $subject . '</title>
	<meta http-equiv="Content-Type" content="text/html;" />
	<style type="text/css">' . $this->settings['customcss'] . '</style>
</head>
<body style="padding:0;margin:0;background-color:#fff;" >';

		$message	.= $this->getInvoice( $request->invoice ) . '</body></html>';

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

		JUTility::sendMail( $this->settings['sender'], $this->settings['sender_name'], $recipients, $subject, $message, true );

		return true;
	}

	function getInvoice( $invoice )
	{
		ob_start();

		$iFactory = new InvoiceFactory( $invoice->userid, null, null, null, null, null, false );
		$iFactory->invoiceprint( 'com_acctexp', $invoice->invoice_number, false, array( 'mi_aecinvoiceprintemail' => true ) );

		$content = ob_get_contents();
		ob_end_clean();

		return $content;
	}

	function invoice_printout( $request )
	{
		// Only handle self-calls
		if ( !isset( $request->add['mi_aecinvoiceprintemail'] ) ) {
			return true;
		}
		
		$db = &JFactory::getDBO();

		foreach ( $request->add as $k => $v ) {
			if ( isset( $this->settings[$k] ) ) {
				if ( isset( $this->settings[$k."_mode"] ) ) {
					switch ( $this->settings[$k."_mode"] ) {
						case "none":
							$value = $v;
							break;
						case "before":
							$value = $this->settings[$k] . $v;
							break;
						case "after":
							$value = $v . $this->settings[$k];
							break;
						case "replace":
							$value = $this->settings[$k];
							break;
						case "delete":
							$value = "";
							break;
					}
				} else {
					$value = $this->settings[$k];
				}

				$request->add[$k] = $value;
			}
		}

		return true;
	}
}
?>
