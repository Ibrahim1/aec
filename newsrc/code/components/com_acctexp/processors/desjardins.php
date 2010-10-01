<?php
/**
 * @version $Id: desjardins.php
 * @package AEC - Account Control Expiration - Membership Manager
 * @subpackage Processors - desjardins XML
 * @copyright 2006-2010 Copyright (C) David Deutsch
 * @author David Deutsch <skore@skore.de> & Team AEC - http://www.valanx.org
 * @license GNU/GPL v.2 http://www.gnu.org/licenses/old-licenses/gpl-2.0.html or, at your option, any later version
 */

// Dont allow direct linking
( defined('_JEXEC') || defined( '_VALID_MOS' ) ) or die( 'Direct Access to this location is not allowed.' );

class processor_desjardins extends XMLprocessor
{
	function info()
	{
		$info = array();
		$info['name']			= 'Desjardins';
		$info['longname']		= _CFG_DESJARDINS_LONGNAME;
		$info['statement']		= _CFG_DESJARDINS_STATEMENT;
		$info['description']	= _CFG_DESJARDINS_DESCRIPTION;
		$info['cc_list']		= 'visa,mastercard';
		$info['recurring']		= 0;
		$info['notify_trail_thanks'] = true;

		return $info;
	}

	function settings()
	{
		$settings = array();
		$settings['testmode']		= "0";
		$settings['custId']			= "";
		$settings['transactionKey']	= "";
		$settings['item_name']		= sprintf( _CFG_PROCESSOR_ITEM_NAME_DEFAULT, '[[cms_live_site]]', '[[user_name]]', '[[user_username]]' );
		$settings['rewriteInfo']	= '';
		$settings['SiteTitle']		= '';

		return $settings;
	}

	function backend_settings()
	{
		$settings = array();
		$settings['testmode']		= array( 'list_yesno' );
		$settings['custId']			= array( 'inputC' );
		$settings['transactionKey']	= array( 'inputC' );
		$settings['SiteTitle']		= array( 'inputC' );
		$settings['item_name']		= array( 'inputE' );

        $settings = AECToolbox::rewriteEngineInfo( null, $settings );

		return $settings;
	}

	function checkoutform( $request, $vcontent=null, $updated=null )
	{
		$var = array();

		return $var;
	}

	function createRequestXML( $request )
	{
$xml_request_str = <<<XML
<?xml version="1.0" encoding="ISO-8859-15"?><request></request>
XML;
		$xml_step1_request = new SimpleXMLElement($xml_request_str);
		$merchant = $xml_step1_request->addChild('merchant');
		$merchant->addAttribute('key', trim($this->settings['transactionKey']));
		$login = $merchant->addChild('login');
		$trx = $login->addChild('trx');
		$trx->addAttribute('id','trx_' . $request->invoice->id);
		
		return $xml_step1_request->asXML();
	}
		
	function createRequestStep3XML( $resp, $request ) {
	
		$xml_step1_Obj = simplexml_load_string($resp);
		$amount = $request->int_var['amount'] * 100;

		$return = JURI::root() . 'index.php';

		$xml_step3_request = '<?xml version="1.0" encoding="ISO-8859-15" ?>'."\n";
		$xml_step3_request .= '	<request>'."\n";
		$xml_step3_request .= '	  <merchant id="'.trim($xml_step1_Obj->merchant['id']).'" key="'.trim($xml_step1_Obj->merchant['key']).'">'."\n";
		$xml_step3_request .= '		<transactions>'."\n";
		$xml_step3_request .= '		  <transaction id="' . trim($xml_step1_Obj->merchant->login->trx['id']) . '" key="'.trim($xml_step1_Obj->merchant->login->trx['key']) .'" type="purchase" currency="CAD" currencyText="$CAD">'."\n";
		$xml_step3_request .= '			<amount>'.$amount.'</amount>'."\n";
		$xml_step3_request .= '			<language>fr</language>'."\n";
		$xml_step3_request .= '			<urls>'."\n";
		$xml_step3_request .= '			  <url name="response">'."\n";
		$xml_step3_request .= '			    <path>' . $return . '</path>'."\n";
		$xml_step3_request .= '			    <parameters>'."\n";
		$xml_step3_request .= '			      <parameter name="option">com_acctexp</parameter>'."\n";
		$xml_step3_request .= '			      <parameter name="task">desjardinsnotification</parameter>'."\n";
		$xml_step3_request .= '			      <parameter name="invoice_number">' . $request->invoice->invoice_number . '</parameter>'."\n";
		$xml_step3_request .= '			    </parameters>'."\n";
		$xml_step3_request .= '			  </url>'."\n";
		$xml_step3_request .= '			  <url name="success">'."\n";
		$xml_step3_request .= '				<path>' . $return . '</path>'."\n";
		$xml_step3_request .= '			    <parameters>'."\n";
		$xml_step3_request .= '			      <parameter name="option">com_acctexp</parameter>'."\n";
		$xml_step3_request .= '			      <parameter name="task">thanks</parameter>'."\n";
		$xml_step3_request .= '			      <parameter name="usage">' . $request->plan->id . '</parameter>'."\n";
		$xml_step3_request .= '			    </parameters>'."\n";
		$xml_step3_request .= '			  </url>'."\n";
		$xml_step3_request .= '			  <url name="cancel">'."\n";
		$xml_step3_request .= '				<path>' . $return . '</path>'."\n";
		$xml_step3_request .= '			    <parameters>'."\n";
		$xml_step3_request .= '			      <parameter name="option">com_acctexp</parameter>'."\n";
		$xml_step3_request .= '			      <parameter name="task">desjardinsnotification</parameter>'."\n";
		$xml_step3_request .= '			      <parameter name="invoice_number">' . $request->invoice->invoice_number . '</parameter>'."\n";
		$xml_step3_request .= '			    </parameters>'."\n";
		$xml_step3_request .= '			  </url>'."\n";
		$xml_step3_request .= '			  <url name="error">'."\n";
		$xml_step3_request .= '				<path>' . $return . '</path>'."\n";
		$xml_step3_request .= '			    <parameters>'."\n";
		$xml_step3_request .= '			      <parameter name="option">com_acctexp</parameter>'."\n";
		$xml_step3_request .= '			      <parameter name="task">desjardinsnotification</parameter>'."\n";
		$xml_step3_request .= '			      <parameter name="invoice_number">' . $request->invoice->invoice_number . '</parameter>'."\n";
		$xml_step3_request .= '			    </parameters>'."\n";
		$xml_step3_request .= '			  </url>'."\n";
		$xml_step3_request .= '			</urls>'."\n";
		$xml_step3_request .= '			<details>'."\n";
		$xml_step3_request .= '			 <![CDATA['."\n";

		$xml_step3_request .= '			  ]]>'."\n";
		$xml_step3_request .= '			</details>'."\n";
		$xml_step3_request .= '			<details_text>'."\n";
		$xml_step3_request .= '			   <![CDATA['."\n";
		$xml_step3_request .= 'Information de la commande'."\n";
		$xml_step3_request .= "No d'authorisation : ".$request->invoice->invoice_number ."\n";
		$xml_step3_request .= 'Abonnement : '.$request->plan->name ."\n";
		$xml_step3_request .= 'Subscription Cost : ' . ((int)($amount/1.075/1.05)/100) ."\n";
		$xml_step3_request .= 'TPS 5.0 % : '. (($amount/1.075/1.05)*0.05)/100 ."\n";  
		$xml_step3_request .= 'TVQ 7.5 % : '. ((($amount/1.075/1.05)*0.05 + ($amount/1.075/1.05))*0.075)/100 ."\n";        
		$xml_step3_request .= 'Total                 : '. $amount/100 ."\n";
		$xml_step3_request .= '			   ]]>'."\n";
		$xml_step3_request .= '			</details_text>'."\n";
		$xml_step3_request .= '		  </transaction>'."\n";
		$xml_step3_request .= '		</transactions>'."\n";
		$xml_step3_request .= '	  </merchant>'."\n";
		$xml_step3_request .= '	</request>'."\n";
		
		return $xml_step3_request;
	}

	function transmitRequestXML( $xml, $request )
	{
		global $mainframe;
		$database = &JFactory::getDBO();
		
		$path = '/catch';
		$url = 'https://www.labdevtrx3.com' . $path;

		$header = array();
		$header['MIME-Version']		= "1.0";
		$header['Content-type']		= "text/xml";
		$header['Accept']			= "text/xml";
		$header['Cache-Control']	= "no-cache";

		$curlextra[CURLOPT_RETURNTRANSFER]	= 1;
		$curlextra[CURLOPT_TIMEOUT]			= 25;
		$curlextra[CURLOPT_VERBOSE]			= 0;
		$curlextra[CURLOPT_CUSTOMREQUEST]	= 'POST';
		$curlextra[CURLOPT_SSL_VERIFYPEER]	= false;

		$resp = $this->transmitRequest( $url, $path, $xml, 443, $curlextra, $header );

		$xml = $this->createRequestStep3XML( $resp, $request );
		
		$resp = $this->transmitRequest( $url, $path, $xml, 443, $curlextra, $header );

		$xml_step3_Obj = simplexml_load_string( $resp );

		echo "<br>simplexml string<br>" . $xml_step3_Obj->asXML() . "<br><br>";

		$redir_url = $xml_step3_Obj->merchant->transactions->transaction->urls->url->path;
		$trx_number = $xml_step3_Obj->xpath('merchant/transactions/transaction/urls/url/parameters/parameter[@name="transaction_id"]');
		$trx_key = $xml_step3_Obj->xpath('merchant/transactions/transaction/urls/url/parameters/parameter[@name="transaction_key"]');
		$trx_merch_id = $xml_step3_Obj->xpath('merchant/transactions/transaction/urls/url/parameters/parameter[@name="merchant_id"]');

		$url = $redir_url . "?transaction_id=".$trx_number[0];
		$url .= "&merchant_id=".$trx_merch_id[0];
		$url .= "&transaction_key=".$trx_key[0];

		$mainframe->redirect($url);
			
		$response = true;
		return $response;
	}

	function parseNotification( $post )
	{
		$database = &JFactory::getDBO();

		$response = array();
		$response['invoice'] = aecGetParam( 'invoice_number', 0, true, array( 'word', 'string', 'clear_nonalnum' ) );;
aecDebug($_REQUEST);
		return $response;
	}

	function validateNotification( $response, $post, $invoice )
	{
		$response['valid'] = 0;
aecDebug("Here is where we will validate JAKE");aecDebug($response);
		return $response;
	}
}
?>
