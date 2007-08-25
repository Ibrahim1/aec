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

class processor_eway
{
	function processor_eway ()
	{
		global $mosConfig_absolute_path;

		if( !defined( '_AEC_LANG_PROCESSOR' ) ) {
			$langPath = $mosConfig_absolute_path . '/components/com_acctexp/processors/com_acctexp_language_processors/';
			if (file_exists( $langPath . $GLOBALS['mosConfig_lang'] . '.php' )) {
				include_once( $langPath . $GLOBALS['mosConfig_lang'] . '.php' );
			}else{
				include_once( $langPath . 'english.php' );
			}
		}
	}

	function info()
	{
		$info = array();
		$info['name']			= 'eway';
		$info['longname']		= _CFG_EWAY_LONGNAME;
		$info['statement']		= _CFG_EWAY_STATEMENT;
		$info['description']	= _CFG_EWAY_DESCRIPTION;
		$info['cc_list']		= 'visa,mastercard';
		$info['recurring']		= 0;

		return $info;
	}

	function settings()
	{
		$settings = array();
		$settings['testmode']		= "1";
		$settings['custId']			= "87654321";
		$settings['tax']			= "10";
		$settings['autoRedirect']	= 1;
		$settings['testAmount']		= "00";
		$settings['item_name']		= sprintf( _CFG_PROCESSOR_ITEM_NAME_DEFAULT, '[[cms_live_site]]', '[[user_name]]', '[[user_username]]' );
		$settings['rewriteInfo']	= ''; // added mic

		return $settings;
	}

	function backend_settings()
	{
		$settings = array();
		$rewriteswitches			= array( 'cms', 'user', 'expiration', 'subscription', 'plan' );

		$settings['testmode']		= array( 'list_yesno' );
		$settings['custId']			= array( 'inputC' );
		$settings['autoRedirect']	= array( 'list_yesno' ) ;
		$settings['SiteTitle']		= array( 'inputC' );
		$settings['item_name']		= array( 'inputE' );

        $settings['rewriteInfo']	= array( 'fieldset', _AEC_MI_REWRITING_INFO, AECToolbox::rewriteEngineInfo( $rewriteswitches ) );

		return $settings;
	}

	function createGatewayLink( $int_var, $cfg, $metaUser, $new_subscription )
	{
		global $mosConfig_live_site;

		//URL returned by eWay
		$return_url = AECToolbox::deadsureURL("/index.php?option=com_acctexp&amp;task=ewaynotification");

		//Genere un identifiant unique pour la transaction
		$my_trxn_number = uniqid( "eway_" );

		$order_total = $int_var['amount'] * 100;

		$var = array(	"post_url" => "https://www.eWAY.com.au/gateway/payment.asp",
						"ewayCustomerID" => $cfg['custId'],
						"ewayTotalAmount" => $order_total,
						"ewayCustomerFirstName" => $metaUser->cmsUser->username,
						"ewayCustomerLastName" => $metaUser->cmsUser->name,
						"ewayCustomerInvoiceDescription" => AECToolbox::rewriteEngine( $cfg['item_name'], $metaUser, $new_subscription ),
						"ewayCustomerInvoiceRef" => $int_var['invoice'],
						"ewayOption1" => $metaUser->cmsUser->id, //Send in option1, the id of the user
						"ewayOption2" => $int_var['invoice'], //Send in option2, the invoice number
						"eWAYTrxnNumber" => $my_trxn_number,
						"eWAYAutoRedirect" => $cfg['autoRedirect'],
						"eWAYSiteTitle" => $cfg['SiteTitle'],
						"eWAYURL" => $return_url
					);

		return $var;
	}

	function parseNotification( $post, $cfg )
	{
		$eWAYResponseText = $post['eWAYresponseText'];
		$eWAYTrxnNumber = $post['ewayTrxnNumber'];
		$eWAYResponseCode = $post['eWAYresponseCode'];
		$ewayTrxnReference = $post['ewayTrxnReference'];
		$eWAYAuthCode = $post['eWAYAuthCode'];
		$total = $post['eWAYReturnAmount'];
		$userid = $post['eWAYoption1'];

		$response = array();
		$response['invoice'] = $post['eWAYoption2'];
		if ( $post['ewayTrxnStatus'] == "True" && isset( $eWAYAuthCode ) ) {
			$response['valid'] = 1;
		} else {
			$response['valid'] = 0;
		}

		return $response;
	}

}
?>