<?php
/**
 * @version $Id: ogoneeur.php
 * @package AEC - Account Control Expiration - Membership Manager
 * @subpackage Processors - Ogone
 * @author Vijay Jawalapersad
 * @license GNU/GPL v.2 http://www.gnu.org/licenses/old-licenses/gpl-2.0.html or, at your option, any later version
 */

// Dont allow direct linking
( defined('_JEXEC') || defined( '_VALID_MOS' ) ) or die( 'Direct Access to this location is not allowed.' );

class processor_ogoneeur extends POSTprocessor
{
	function info()
	{
		$info = array();
		$info['name']			= 'Ogone EUR';
		$info['description']	= 'Ogone (EUR) ReAT tv account.';
		$info['currencies']		= 'EUR';
		$info['cc_list']		= 'iDEAL,American Express';
		$info['recurring']		= 0;

		return $info;
	}

	function settings()
	{
		$settings = array();
		$settings['currency']	= 'EUR';
		$settings['item_number']	= '[[user_id]]';
		$settings['customparams']	= "";
		return $settings;
	}

	function backend_settings()
	{
		$settings = array();
		$settings['customparams']	= "";
		return $settings;
	}

	function createGatewayLink( $request )
	{

		//TESTACCOUNT url
		$var['post_url']	= 'https://secure.ogone.com/ncol/test/orderstandard.asp';
		$var['PSPID']		= 'reattv';
		$var['orderID']		= $request->invoice->invoice_number;
		
		//Ogone -> for example, 5.00 euro should be sent to Ogone as 500
		//this transformation is done in the bottom two lines.
		$bedragString	 = ereg_replace("[^0-9]", "", $request->int_var['amount']); 
		$bedragInt		 = (int)$bedragString;
		
		$var['amount']		= $bedragInt;
		$var['currency']	= $this->settings['currency'];
		$var['language']	= 'en_US';
		$var['PMLIST']		= 'iDEAL;American Express';
		
		
		$var['accepturl']	= AECToolbox::deadsureURL( 'index.php?option=com_acctexp&task=ogoneeurnotification', false, true );
		
		/* SHA IN
		How the SHA string is processed:
		All the variables that are being sent to Ogone in alphabetical order. After every variable the so called secret sign has to be set. This secret sign has to be set in the Ogone admin panel. 
		In the above string this secret sign is 1234?reatsecrsig
		
		Ogone has the possibility to send about 20 (hidden) post values with each request, if a value is sent, this one should be included in the SHA string, if this value is empty (not sent), this 
		should be totally excluded from the SHA string.
		
		Ogone has a "Parameter Cookbook" for more information about the implementation
		*/
		//TESTACCOUNT SHA String (to verify the POST variables have not been tampered with)
		$Shastring = 'ACCEPTURL='.$var['accepturl'].'1234?reatsecrsigAMOUNT='.$var['amount'].'1234?reatsecrsigCURRENCY='.$var['currency'].'1234?reatsecrsigLANGUAGE=en_US1234?reatsecrsigORDERID='.$var['orderID'].'1234?reatsecrsigPMLIST='.$var['PMLIST'].'1234?reatsecrsigPSPID=reattv1234?reatsecrsig';
		
		$Shastr = strtoupper(sha1($Shastring));
		$var['SHASign']		= $Shastr;
		
		return $var;
	}

	function parseNotification( $get )	
	{	
		$response = array();
		$response['invoice']	= $_GET['orderID'];
		return $response;
	}

	function validateNotification( $response, $get, $invoice )
	{
		$response['valid'] = 0;
		$ShaCheckquery = $_GET['SHASIGN'];
		
		
		/* SHA OUT
		Ogone has the optionally option to send the processed values with a SHA string. This string is processed the same way as the SHA IN string (alphabetically, only filled paramaters/values)
		What values are sent back can be set in the Ogone admin panel.
		*/
		//TESTACCOUNT SHA String (to verify the sent back data have not been tampered with)
		$ShaCheckstring = 'BRAND='.$_GET['BRAND'].'1234?reatsecrsigCURRENCY='.$_GET['currency'].'1234?reatsecrsigNCERROR='.$_GET['NCERROR'].'1234?reatsecrsigORDERID='.$_GET['orderID'].'1234?reatsecrsigPAYID='.$_GET['PAYID'].'1234?reatsecrsigPM='.$_GET['PM'].'1234?reatsecrsigSTATUS='.$_GET['STATUS'].'1234?reatsecrsig';	
		
		$ShaCheckstr = strtoupper(sha1($ShaCheckstring));
		
		//Status 9 and 5 are successfull statuses. There are several other statuses, but these two are the most important.
		//The other statuses can be found in the Parameter Cookbook
		
		//The forwards are dirty, but they work.
		if((($_GET['STATUS'] == 5)||($_GET['STATUS'] == 9)) && (strcmp($ShaCheckstr, $ShaCheckquery) == 0)){
			$response['valid'] = 1;
				echo '		
				<script type="text/javascript">
					<!--
						window.location = "http://www.reat.tv/index.php?option=com_content&view=article&id=14&catid=5&Itemid=18"
					//-->
				</script>
				';
			}
		else{
			$response['valid'] = 0;
				echo '		
				<script type="text/javascript">
					<!--
						window.location = "http://www.reat.tv/index.php?option=com_content&view=article&id=41&catid=5&Itemid=17"
					//-->
				</script>
				';
			}
		return $response;
	}
}
