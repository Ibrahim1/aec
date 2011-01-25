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
		$info['name']					= 'Desjardins';
		$info['longname']				= _CFG_DESJARDINS_LONGNAME;
		$info['statement']				= _CFG_DESJARDINS_STATEMENT;
		$info['description']			= _CFG_DESJARDINS_DESCRIPTION;
		$info['cc_list']				= 'visa,mastercard';
		$info['currencies']				= "CAD";
		$info['recurring']				= 0;
		$info['notify_trail_thanks']	= false;
		$info['custom_notify_trail']	= true;

		return $info;
	}

	function settings()
	{
		$settings = array();
		$settings['testmode']		= "0";
		$settings['currency']		= "CAD";
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
		$settings['currency']		= array( 'list_currency' );
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

		$suffix = '';
		if ( isset( $request->invoice->params['desjardin_retries'] ) ) {
			$suffix = $request->invoice->params['desjardin_retries'];
		}

		$trx = $login->addChild( 'trx' );
		$trx->addAttribute( 'id', $request->invoice->invoice_number.$suffix );
		
		return $xml_step1_request->asXML();
	}
		
	function createRequestStep3XML( $resp, $request )
	{
		$xml_step1_Obj = simplexml_load_string($resp);
		$amount = $request->int_var['amount'] * 100;

		$suffix = '';
		if ( isset( $request->invoice->params['desjardin_attempts'] ) ) {
			$suffix = $request->invoice->params['desjardin_attempts'];
		}

		$return = JURI::root() . 'components/com_acctexp/processors/notify/notify_redirect.php';

		$xml_step3_request = '<?xml version="1.0" encoding="ISO-8859-15" ?>'."\n";
		$xml_step3_request .= '	<request>'."\n";
		$xml_step3_request .= '	  <merchant id="'.trim($this->settings['custId']).'" key="'.trim($this->settings['transactionKey']).'">'."\n";
		$xml_step3_request .= '		<transactions>'."\n";
		$xml_step3_request .= '		  <transaction id="' . trim($xml_step1_Obj->merchant->login->trx['id']).$suffix . '" key="'.trim($xml_step1_Obj->merchant->login->trx['key']) .'" type="purchase" currency="CAD" currencyText="$CAD">'."\n";
		$xml_step3_request .= '			<amount>'.$amount.'</amount>'."\n";
		$xml_step3_request .= '			<language>fr</language>'."\n";
		$xml_step3_request .= '			<urls>'."\n";
		$xml_step3_request .= '			  <url name="response">'."\n";
		$xml_step3_request .= '			    <path>' . $return . '</path>'."\n";
		$xml_step3_request .= '			    <parameters>'."\n";
		$xml_step3_request .= '			      <parameter name="aec_request">djd_' . $request->invoice->invoice_number . '_response</parameter>'."\n";
		$xml_step3_request .= '			    </parameters>'."\n";
		$xml_step3_request .= '			  </url>'."\n";
		$xml_step3_request .= '			  <url name="success">'."\n";
		$xml_step3_request .= '				<path>' . $return . '</path>'."\n";
		$xml_step3_request .= '			    <parameters>'."\n";
		$xml_step3_request .= '			      <parameter name="aec_request">djd_' . $request->invoice->invoice_number . '_success</parameter>'."\n";
		$xml_step3_request .= '			    </parameters>'."\n";
		$xml_step3_request .= '			  </url>'."\n";
		$xml_step3_request .= '			  <url name="cancel">'."\n";
		$xml_step3_request .= '				<path>' . $return . '</path>'."\n";
		$xml_step3_request .= '			    <parameters>'."\n";
		$xml_step3_request .= '			      <parameter name="aec_request">djd_' . $request->invoice->invoice_number . '_cancel</parameter>'."\n";
		$xml_step3_request .= '			    </parameters>'."\n";
		$xml_step3_request .= '			  </url>'."\n";
		$xml_step3_request .= '			  <url name="error">'."\n";
		$xml_step3_request .= '				<path>' . $return . '</path>'."\n";
		$xml_step3_request .= '			    <parameters>'."\n";
		$xml_step3_request .= '			      <parameter name="aec_request">djd_' . $request->invoice->invoice_number . '_error</parameter>'."\n";
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
 
		$redir_url = $xml_step3_Obj->merchant->transactions->transaction->urls->url->path;
		$trx_number = $xml_step3_Obj->xpath('merchant/transactions/transaction/urls/url/parameters/parameter[@name="transaction_id"]');
		$trx_key = $xml_step3_Obj->xpath('merchant/transactions/transaction/urls/url/parameters/parameter[@name="transaction_key"]');
		$trx_merch_id = $xml_step3_Obj->xpath('merchant/transactions/transaction/urls/url/parameters/parameter[@name="merchant_id"]');

		$suffix = '';
		if ( isset( $request->invoice->params['desjardin_attempts'] ) ) {
			$suffix = $request->invoice->params['desjardin_attempts'];
		}

		$url = $redir_url . "?transaction_id=".$trx_number[0].$suffix;
		$url .= "&merchant_id=".$trx_merch_id[0];
		$url .= "&transaction_key=".$trx_key[0];

		if ( isset( $request->invoice->params['desjardin_attempts'] ) ) {
			$attempts = $request->invoice->params['desjardin_attempts'] + 1;
		} else {
			$attempts = 1;
		}

		$request->invoice->addParams( array( 'desjardin_attempts' => $attempts ) );
		$request->invoice->storeload();

		$app->redirect($url);
			
		return true;
	}

	function parseNotification( $post )
	{
		$response = array();
		$response['invoice'] = $post['invoice_number'];

		if ( empty( $response['invoice'] ) ) {
			$response['invoice'] = aecGetParam( 'ResponseFile', 0, true, array( 'word', 'string', 'clear_nonalnum' ) );
		}

		if ( empty( $response['invoice'] ) && !empty( $post['original'] ) ) {
			$post['original'] = base64_decode( $post['original'] );

			$response['invoice'] = $this->substring_between( $post['original'], '<transaction id="', '"' );
		}

		// Make sure we don't have a subinvoice number
		$response['invoice'] = substr( $response['invoice'], 0, 17 );

		return $response;
	}

	function validateNotification( $response, $post, $invoice )
	{
		$response['valid'] = 0;
aecDebug($invoice->params);
		$td = null;
		if ( !empty( $post['original'] ) ) {
			$xml = base64_decode( $post['original'] );
aecDebug($post['status']);aecDebug($xml);
			$auth = $this->XMLsubstring_tag( $xml, 'authorization_no' );

			if ( empty( $invoice->params['authorization_no'] ) && !empty( $auth ) ) {
				switch ( strtolower( $post['status'] ) ) {
					case 'success': $status = 'APPROVED'; break;
					case 'cancel': $status = 'CANCELLED'; break;
					case 'error': $status = 'DECLINED'; break;
					default: $status = 'NOT COMPLETED'; break;
				}

				$td = array(	'authorization_no' => $auth,
								'reference_no' => $this->XMLsubstring_tag( $xml, 'sequence_no' ) . ' ' . $this->XMLsubstring_tag( $xml, 'terminal_id' ),
								'card_type' => $this->XMLsubstring_tag( $xml, 'card_type' ),
								'transaction_type' => 'Purchase',
								'transaction_status' => $status
							);

				$success = true;
				foreach ( $td as $v ) {
					if ( empty( $v ) ) {
						$success = false;
					}
				}

				if ( $success ) {
					$td['transaction_status'] = 'APPROVED';
				}
			}
		} elseif ( !isset( $invoice->params['authorization_no'] ) ) {
				$td = array(	'authorization_no' => '',
								'reference_no' => '',
								'card_type' => '',
								'transaction_type' => '',
								'transaction_status' => ''
							);
		}
aecDebug($td);
		if ( !empty( $td ) ) {
			$invoice->addParams( $td );
			$invoice->storeload();
		}

		if ( $post['status'] == 'error' ) {
			$invoice->transaction_date == '0000-00-00 00:00:00';
			$invoice->storeload();

			$error = "Erreur de procession de vos d&eacute;tails de paiement: Nous ne pouvons effectuer votre transaction par carte de cr&eacute;dit";

			$response['customthanks'] = $this->displayError( $invoice, $error ) . '<br /><br />' . $this->displayInvoice( $invoice );
			$response['break_processing'] = true;

			return $response;
		}

		if ( $post['status'] == 'cancel' ) {
			$invoice->cancel();

			$response['customthanks'] = $this->displayInvoice( $invoice );
			$response['break_processing'] = true;

			return $response;
		}

		if ( $post['status'] == 'success' ) {
			$response['customthanks'] = $this->displayInvoice( $invoice );
			$response['break_processing'] = true;

			return $response;
		}

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

			echo $xml;

			exit;
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

		echo $xml;

		exit;
	}

	function displayInvoice( $invoice )
	{
		ob_start();

		$iFactory = new InvoiceFactory( $invoice->userid, null, null, null, null, null, false );
		$iFactory->invoiceprint( 'com_acctexp', $invoice->invoice_number, false );

		$content = ob_get_contents();
		ob_end_clean();

		return $content;
	}

	function displayError( $invoice, $error )
	{
		$db = &JFactory::getDBO();

		$user = new JTableUser( $db );
		$user->load( $invoice->userid );

		ob_start();

		Payment_HTML::error( 'com_acctexp', $user, $invoice->invoice_number, $error );

		$content = ob_get_contents();
		ob_end_clean();

		return $content;
	}

}
?>
