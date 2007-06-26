<?php
// Dont allow direct linking
defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );

/**
* AcctExp Component
* @package AcctExp
* @subpackage processor
* @copyright 2007 Helder Garcia, David Deutsch
* @license http://www.gnu.org/copyleft/gpl.html. GNU Public License
* @author Bruno Pourtier <bruno.pourtier@gmail.com>
**/

class processor_eway {

	function processor_eway () {
		
	}

	function info () {
		$info = array();
		$info['name'] = "eWAY";
		$info['longname'] = "eWAY";
		$info['statement'] = "Make payments with eWAY Shared Payment Solution!";
		$info['description'] = "eWAY is the easiest and most affordable payment gateway in Australia. Process credit card payments via eWAY's own secure Shared Payment Page in real-time.";
		$info['cc_list'] = "visa,mastercard";
		$info['recurring'] = 0;		
        
		return $info;
	}

	function settings () {
		$settings = array();
		$settings['testmode'] = "1";
		$settings['custId'] = "87654321";		
		$settings['tax'] = "10";
		$settings['autoRedirect'] = 1;		
		$settings['testAmount'] = "00";

		return $settings;
	}

	function backend_settings () {
		$settings = array();
		$settings['testmode'] = array("list_yesno", _CFG_TAB12_OPT1NAME, _CFG_TAB12_OPT1DESC);
		$settings['custId'] = array("inputC", _CFG_TAB12_OPT2NAME, _CFG_TAB12_OPT2DESC);
		$settings['tax'] = array("inputA", _CFG_TAB2_OPT9NAME, _CFG_TAB2_OPT9DESC);
		$settings['autoRedirect'] = array("list_yesno", _CFG_TAB12_OPT3NAME, _CFG_TAB12_OPT3DESC);
		$settings['testAmount'] = array("inputA", _CFG_TAB12_OPT4NAME, _CFG_TAB12_OPT4DESC);

		return $settings;
	}

	function createGatewayLink ( $int_var, $metaUser, $cfg ) {
		global $mosConfig_live_site;

		//URL returned by eWay
		$return_url = AECToolbox::deadsureURL("/index.php?option=com_acctexp&amp;task=ewaynotification");  

		//Genere un identifiant unique pour la transaction
		$my_trxn_number = uniqid( "eway_" );
		
		if ($cfg['testmode']) {	
			$order_total = ($int_var['amount'] * 100).$cfg['testAmount'];
		} else {	
			$order_total = $int_var['amount'];  
		}
		
		$var = array(	"post_url" => "https://www.eWAY.com.au/gateway/payment.asp",
						"ewayCustomerID" => $cfg['custId'],                                              
						"ewayTotalAmount" => $order_total,
						"ewayCustomerFirstName" => $metaUser->cmsUser->username,
						"ewayCustomerLastName" => $metaUser->cmsUser->name,
						"ewayCustomerInvoiceDescription" => "Subscription at " . $mosConfig_live_site . " - User: ". $metaUser->cmsUser->name . " (" . $metaUser->cmsUser->username . ")",
						"ewayCustomerInvoiceRef" => $int_var['invoice'],
						"ewayOption1" => $metaUser->cmsUser->id, //Send in option1, the id of the user
						"ewayOption2" => $int_var['invoice'], //Send in option2, the invoice number
						"eWAYTrxnNumber" => $my_trxn_number,
						"eWAYAutoRedirect" => $cfg['autoRedirect'],
						"eWAYSiteTitle" => "CrazyCow Membership Subscriptions",                                                               
						"eWAYURL" => $return_url
					);

		return $var;
	}

	function parseNotification ( $post, $cfg ) {
		$eWAYResponseText = $_POST['eWAYresponseText'];
		$eWAYTrxnNumber = $_POST['ewayTrxnNumber'];
		$eWAYResponseCode = $_POST['eWAYresponseCode'];
		$ewayTrxnReference = $_POST['ewayTrxnReference'];
		$eWAYAuthCode = $_POST['eWAYAuthCode'];
		$total = $_POST['eWAYReturnAmount'];
		$userid = $_POST['eWAYoption1'];

		$response = array();
		$response['invoice'] = $_POST['eWAYoption2'];
		if($_POST['ewayTrxnStatus'] == "True" && isset($eWAYAuthCode)) {
			$response['valid'] = 1;
		} else {
			 $response['valid'] = 0;
		}

		return $response;
	}

}
?>