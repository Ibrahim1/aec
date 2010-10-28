<?php
/**
 * @version 0.1
 * @package Amigos
 * @author  Dioscouri Design
 * @link    http://www.dioscouri.com
 * @copyright Copyright (C) 2007 Dioscouri Design. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
*/

/** ensure this file is being included by a parent file */
( defined('_JEXEC') || defined( '_VALID_MOS' ) ) or die( 'Restricted Access' );

class mi_amigos
{
	function Info()
	{
		$info = array();
		$info['name'] = _AEC_MI_NAME_AMIGOS;
		$info['desc'] = _AEC_MI_DESC_AMIGOS;

		return $info;
	}

	function Settings()
	{
		$settings = array();
		$settings['amigos_domain']	= array( 'inputC' );
		$settings['amigos_curl']	= array( 'list_yesno' );

		return $settings;
	}

	function CommonData()
	{
		return array( 'amigos_domain', 'amigos_curl' );
	}

	function action( $request )
	{
		if ( empty( $_REQUEST['amigosid'] ) ) {
			return true;
		}

		$domain = $this->settings['amigos_domain'];

		// if domain was incorrectly entered, add http:// to it
		if ( substr( $this->settings['amigos_domain'], 0, 7 ) != 'http://' ) {
			$domain = "http://".$this->settings['amigos_domain'];
		}

		if ( substr( $domain, -1 ) == '/' ) {
			$domain = substr( $domain, 0, -1 );
		}
aecDebug($request);
		$amigos_id			= $_REQUEST['amigosid'];
		$amigos_ordertype	= 'com_acctexp';
		$amigos_orderid		= $request->invoice->invoice_number;
		$amigos_orderamount	= $request->invoice->amount;
		$amigos_ipaddress	= $_SERVER['REMOTE_ADDR'];

		$tmpl = "%s/index.php?option=com_amigos&task=sale&amigos_id=%s&amigos_ordertype=%s&amigos_orderid=%s&amigos_orderamount=%s&amigos_ipaddress=%s";
		$url = sprintf( $tmpl, $domain, $amigos_id, $amigos_ordertype, $amigos_orderid, $amigos_orderamount, $amigos_ipaddress );

		if ( !empty( $this->settings['amigos_curl'] ) )
		{
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url );
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_exec($ch);
			curl_close($ch);
		} else {
			$text = '<img border="0" '
					.'src="' . $url . '" '
					.'border="0" width="1" height="1" />';

			$db = &JFactory::getDBO();
			$displaypipeline = new displayPipeline($db);
			$displaypipeline->create( $request->metaUser->userid, 1, 0, 0, null, 1, $text );
		}

		return true;
		}

}