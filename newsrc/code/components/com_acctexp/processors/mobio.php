<?php
/**
 * @version $Id: mobio.php
 * @package AEC - Account Control Expiration - Membership Manager
 * @subpackage Processors - Mobio.bg
 * @copyright 2004-2008 Copyright (C) David Deutsch
 * @author David Deutsch <skore@skore.de> & Team AEC - http://www.valanx.org
 * @license GNU/GPL v.2 http://www.gnu.org/licenses/old-licenses/gpl-2.0.html or, at your option, any later version
 */

// Dont allow direct linking
( defined('_JEXEC') || defined( '_VALID_MOS' ) ) or die( 'Direct Access to this location is not allowed.' );



define('_CFG_MOBIO_INVALID_SMSCODE', 'Invalid or expired SMS code.');

define('_CFG_MOBIO_SERVID_NAME', 'servID');
define('_CFG_MOBIO_SERVID_DESC', 'Please fill in your Mobio.bg\'s service servID');

define('_CFG_MOBIO_USER_MESSAGE_NAME', 'SMS service description');
define('_CFG_MOBIO_USER_MESSAGE_DESC', 'Fill in the SMS services description. For example: To receive access code, please send SMS with text CODE to shortcode 1092(2.40lv)');

define('_PAYPLAN_PLAN_PARAMS_3_SERVID_NAME', _CFG_MOBIO_SERVID_NAME);

define('_CFG_MOBIO_PLAN_PARAMS_SERVID_NAME', _CFG_MOBIO_SERVID_NAME);
define('_CFG_MOBIO_PLAN_PARAMS_SERVID_DESC', _CFG_MOBIO_SERVID_DESC);

define('_CFG_MOBIO_PLAN_PARAMS_USER_MESSAGE_NAME', _CFG_MOBIO_USER_MESSAGE_NAME);
define('_CFG_MOBIO_PLAN_PARAMS_USER_MESSAGE_DESC', _CFG_MOBIO_USER_MESSAGE_DESC);

class processor_mobio extends processor {
	function info()
	{
		$info = array();
		$info['name'] = 'mobio';
		$info['longname'] = 'Mobio.bg SMS payment';
		$info['statement'] = '';
		$info['description'] = '';
		$info['currencies'] = 'EUR';
		$info['cc_list'] = "";
		$info['recurring'] = 0;

		return $info;
	}

	function settings()
	{
		$settings = array();
		$settings['servID']			= '29';
		$settings['user_message']		= 'За да получите код за достъп изпратете SMS с текст CODE на номер 1092 (2.40лв.)';


		return $settings;
	}

	function backend_settings()
	{
		$settings = array();
		$settings['servID']	= array("inputC");
		$settings['user_message'] = array("inputD");


		return $settings;
	}

	function CustomPlanParams()
	{
		$p = array();

		//$p['servID']    = array( 'inputC' );
		//$p['user_message']    = array( 'inputD' );


		return $p;
	}

	function checkoutAction($request) {

		$return .= $this->settings['user_message'];
		$return .= '<form action="' . AECToolbox::deadsureURL( 'index.php?option=com_acctexp&amp;task=checkout', $this->info['secure'] ) . '" method="post">' . "\n";
		$return .= '<input type="hidden" name="invoice" value="' . $request->int_var['invoice'] . '" />' . "\n";
		$return .= '<input type="hidden" name="userid" value="' . $request->metaUser->userid . '" />' . "\n";
		$return .= '<input type="text" name="smscode" value="" />' . "\n";
		$return .= '<input type="submit" class="button" id="aec_checkout_btn" value="' . _BUTTON_CHECKOUT . '" /><br /><br />' . "\n";
		$return .= '</form>';

		return $return;

	}

	function checkoutProcess( $request ) {

		if($this->mobioCheckcode($this->settings['servID'], $_REQUEST['smscode'])) {
			$database = &JFactory::getDBO();
			$invoice = new Invoice( $database );
			$invoice->load( $request->invoice->id );
			$invoice->pay();

			$invoice->addParams( array( 'smscode' => $_REQUEST['smscode']) );
			$invoice->check();
			$invoice->store();

			$return['valid'] = true;
			$return['invoice'] = $request->invoice->invoice_number;

		}else{
			$return['error'] = _CFG_MOBIO_INVALID_SMSCODE;
		}

		return $return;
	}

	function mobioCheckcode($servID, $code) {

		$res_lines = file("http://www.mobio.bg/code/checkcode.php?servID=$servID&code=$code");

		$ret = 0;
		if($res_lines) {

			if(strstr($res_lines[0], "PAYBG=OK")) {
				$ret = 1;
			}else{
				if($debug)
					echo $line."\n";
			}
		}else{
			if($debug)
				echo "Unable to connect to mobio.bg server.\n";
			$ret = 0;
		}

		return $ret;
	}

	function parseNotification( $post ) {

	}




}
?>
