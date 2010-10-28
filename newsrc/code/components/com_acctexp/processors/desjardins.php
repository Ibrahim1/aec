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
		$info['notify_trail_thanks'] = false;
		$info['custom_notify_trail'] = true;

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

		$merchant = $xml_step1_request->addChild( 'merchant' );
		$merchant->addAttribute( 'key', trim( $this->settings['transactionKey'] ) );

		$login = $merchant->addChild( 'login' );

		$trx = $login->addChild( 'trx' );
		$trx->addAttribute( 'id', $request->invoice->invoice_number );
		
		return $xml_step1_request->asXML();
	}
		
	function createRequestStep3XML( $resp, $request )
	{
		$xml_step1_Obj = simplexml_load_string($resp);
		$amount = $request->int_var['amount'] * 100;

		$return = JURI::root() . 'components/com_acctexp/processors/notify/notify_redirect.php';

		$xml_step3_request = '<?xml version="1.0" encoding="ISO-8859-15" ?>'."\n";
		$xml_step3_request .= '	<request>'."\n";
		$xml_step3_request .= '	  <merchant id="'.trim($this->settings['custId']).'" key="'.trim($this->settings['transactionKey']).'">'."\n";
		$xml_step3_request .= '		<transactions>'."\n";
		$xml_step3_request .= '		  <transaction id="' . trim($xml_step1_Obj->merchant->login->trx['id']) . '" key="'.trim($xml_step1_Obj->merchant->login->trx['key']) .'" type="purchase" currency="CAD" currencyText="$CAD">'."\n";
		$xml_step3_request .= '			<amount>'.$amount.'</amount>'."\n";
		$xml_step3_request .= '			<language>fr</language>'."\n";
		$xml_step3_request .= '			<urls>'."\n";
		$xml_step3_request .= '			  <url name="response">'."\n";
		$xml_step3_request .= '			    <path>' . $return . '</path>'."\n";

		$request = array( 'get' => array( 'task' => 'desjardinsnotification' ), 'post' => array( 'status' => 'response', 'invoice' => $request->invoice->invoice_number ) );

		$xml_step3_request .= '			    <parameters>'."\n";
		$xml_step3_request .= '			      <parameter name="aec_request">' . base64_encode( serialize( $request ) ) . '</parameter>'."\n";
		$xml_step3_request .= '			    </parameters>'."\n";
		$xml_step3_request .= '			  </url>'."\n";
		$xml_step3_request .= '			  <url name="success">'."\n";
		$xml_step3_request .= '				<path>' . $return . '</path>'."\n";

		$request = array( 'get' => array( 'task' => 'desjardinsnotification' ), 'post' => array( 'status' => 'success', 'invoice' => $request->invoice->invoice_number ) );

		$xml_step3_request .= '			    <parameters>'."\n";
		$xml_step3_request .= '			      <parameter name="aec_request">' . base64_encode( serialize( $request ) ) . '</parameter>'."\n";
		$xml_step3_request .= '			    </parameters>'."\n";
		$xml_step3_request .= '			  </url>'."\n";
		$xml_step3_request .= '			  <url name="cancel">'."\n";
		$xml_step3_request .= '				<path>' . $return . '</path>'."\n";
		$xml_step3_request .= '			    <parameters>'."\n";

		$request = array( 'get' => array( 'task' => 'desjardinsnotification' ), 'post' => array( 'status' => 'cancel', 'invoice' => $request->invoice->invoice_number ) );

		$xml_step3_request .= '			      <parameter name="aec_request">' . base64_encode( serialize( $request ) ) . '</parameter>'."\n";
		$xml_step3_request .= '			    </parameters>'."\n";
		$xml_step3_request .= '			  </url>'."\n";
		$xml_step3_request .= '			  <url name="error">'."\n";
		$xml_step3_request .= '				<path>' . $return . '</path>'."\n";
		$xml_step3_request .= '			    <parameters>'."\n";

		$request = array( 'get' => array( 'task' => 'desjardinsnotification' ), 'post' => array( 'status' => 'error', 'invoice' => $request->invoice->invoice_number ) );

		$xml_step3_request .= '			      <parameter name="aec_request">' . base64_encode( serialize( $request ) ) . '</parameter>'."\n";
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
		$xml_step3_request .= 'Subscription Cost : ' . $request->items->total->cost['amount'] ."\n";

		foreach ( $request->items->tax as $tax ) {
			$xml_step3_request .= $tax['terms']->terms[0]->cost[0]->cost['details'] . ' : '. $tax['cost'] ."\n";
		}

		$xml_step3_request .= 'Total : '. $request->items->grand_total->cost['amount'] ."\n";
		$xml_step3_request .= '			   ]]>'."\n";
		$xml_step3_request .= '			</details_text>'."\n";
		$xml_step3_request .= '		  </transaction>'."\n";
		$xml_step3_request .= '		</transactions>'."\n";
		$xml_step3_request .= '	  </merchant>'."\n";
		$xml_step3_request .= '	</request>'."\n";

		return $xml_step3_request;
	}

	function transmitRequestDesjardin( $url, $path, $xml )
	{
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

		return $this->transmitRequest( $url, $path, $xml, 443, $curlextra, $header );
	}

	function transmitRequestXML( $xml, $request )
	{
		$app = JFactory::getApplication();
		
		$path = '/catch';
		$url = 'https://www.labdevtrx3.com' . $path;

		$resp = $this->transmitRequestDesjardin( $url, $path, $xml );

		$xml = $this->createRequestStep3XML( $resp, $request );

		$resp = $this->transmitRequestDesjardin( $url, $path, $xml );

		$xml_step3_Obj = simplexml_load_string( $resp );

		//print_r($resp);exit;

		$redir_url = $xml_step3_Obj->merchant->transactions->transaction->urls->url->path;
		$trx_number = $xml_step3_Obj->xpath('merchant/transactions/transaction/urls/url/parameters/parameter[@name="transaction_id"]');
		$trx_key = $xml_step3_Obj->xpath('merchant/transactions/transaction/urls/url/parameters/parameter[@name="transaction_key"]');
		$trx_merch_id = $xml_step3_Obj->xpath('merchant/transactions/transaction/urls/url/parameters/parameter[@name="merchant_id"]');

		$url = $redir_url . "?transaction_id=".$trx_number[0];
		$url .= "&merchant_id=".$trx_merch_id[0];
		$url .= "&transaction_key=".$trx_key[0];

		$app->redirect($url);
			
		$response = true;
		return $response;
	}

	function parseNotification( $post )
	{
		$response = array();
aecDebug($_REQUEST);aecDebug($post);
		if ( !empty( $post['original'] ) ) {
			$post['original'] = base64_decode( $post['original'] );

			$response['invoice'] = $this->substring_between( $post['original'], '<transaction id="', '"' );
		} else {
			$response['invoice'] = aecGetParam( 'ResponseFile', 0, true, array( 'word', 'string', 'clear_nonalnum' ) );
		}

		return $response;
	}

	function validateNotification( $response, $post, $invoice )
	{
		$response['valid'] = 0;

		if ( strpos( base64_decode( $post['original'] ), '<confirm>' ) ) {
			$response['valid'] = 1;
		} else {
$xml_request_str = <<<XML
<?xml version="1.0" encoding="ISO-8859-15"?><response></response>
XML;

			$xml_step1_request = new SimpleXMLElement($xml_request_str);

			$merchant = $xml_step1_request->addChild( 'merchant' );
			$merchant->addAttribute( 'id', trim( $this->settings['custId'] ) );

			$trx = $merchant->addChild( 'transaction' );
			$trx->addAttribute( 'id', $response['invoice'] );
			$trx->addAttribute( 'accepted', 'yes' );

			$xml = $xml_step1_request->asXML();

			echo $xml;exit;
		}

		return $response;
	}

	function notify_trail( $InvoiceFactory )
	{
		$path = '/catch';
		$url = 'https://www.labdevtrx3.com' . $path;

$xml_request_str = <<<XML
<?xml version="1.0" encoding="ISO-8859-15"?><response></response>
XML;

		$xml_step1_request = new SimpleXMLElement($xml_request_str);

		$merchant = $xml_step1_request->addChild( 'merchant' );
		$merchant->addAttribute( 'id', trim( $this->settings['custId'] ) );

		$trx = $merchant->addChild( 'transaction' );
		$trx->addAttribute( 'id', $InvoiceFactory->invoice->invoice_number );
		$trx->addAttribute( 'accepted', 'yes' );

		$xml = $xml_step1_request->asXML();

		echo $xml;exit;
		exit;
	}

	function substring_between( $haystack, $start, $end )
	{
		if ( strpos( $haystack, $start ) === false || strpos( $haystack, $end ) === false ) {
			return false;
		 } else {
			$start_position = strpos( $haystack, $start ) + strlen( $start );
			$end_position = strpos( $haystack, $end, $start_position );
			return substr( $haystack, $start_position, $end_position - $start_position );
		}
	}

}
?>
