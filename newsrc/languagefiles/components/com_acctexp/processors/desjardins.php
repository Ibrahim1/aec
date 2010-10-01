<?php
/**
 * @version $Id: desjardinsxml.php
 * @package AEC - Account Control Expiration - Membership Manager
 * @subpackage Processors - desjardins XML
 * @copyright 2006-2008 Copyright (C) David Deutsch
 * @author David Deutsch <skore@skore.de> & Team AEC - http://www.valanx.org
 * @license GNU/GPL v.2 http://www.gnu.org/licenses/old-licenses/gpl-2.0.html or, at your option, any later version
 */

// Dont allow direct linking
( defined('_JEXEC') || defined( '_VALID_MOS' ) ) or die( 'Direct Access to this location is not allowed.' );

/**
* AcctExp Component
* @package AEC - Account Control Expiration - Membership Manager
* @subpackage processor
* @copyright 2006-2008 Copyright (C) David Deutsch
* @license GNU/GPL v.2 http://www.gnu.org/licenses/old-licenses/gpl-2.0.html or, at your option, any later version
* @author David Deutsch <skore@skore.de> & Team AEC - http://www.valanx.org
**/

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
		$settings['testmode']		= "1";
		$settings['custId']		= "131302";
		$settings['transactionKey']		= "3f463a64393d16c94593da4d263e55b3";
		$settings['tax']			= "10";
		$settings['testAmount']	= "00";
		$settings['item_name']	= sprintf( _CFG_PROCESSOR_ITEM_NAME_DEFAULT, '[[cms_live_site]]', '[[user_name]]', '[[user_username]]' );
		$settings['rewriteInfo']	= ''; // added mic
		$settings['SiteTitle']	= '';

		return $settings;
	}

	function backend_settings()
	{
		$settings = array();
		$settings['testmode']		= array( 'list_yesno' );
		$settings['custId']		= array( 'inputC' );
		$settings['transactionKey']		= array( 'inputC' );
		$settings['SiteTitle']	= array( 'inputC' );
		$settings['item_name']	= array( 'inputE' );

        $settings = AECToolbox::rewriteEngineInfo( null, $settings );

		return $settings;
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
		$trx->addAttribute('id','trx_' . rand(0,999999));
		$order_total = $request->int_var['amount'] * 100;
		$my_trxn_number = uniqid( "desjardins_" );

		/*$nodes = array(	"desjardinsCustomerID" => $this->settings['custId'],
						"desjardinsTotalAmount" => $order_total,
						"desjardinsCustomerFirstName" => $request->metaUser->cmsUser->username,
						"desjardinsCustomerLastName" => $request->metaUser->cmsUser->name,
						"desjardinsCustomerInvoiceDescription" => AECToolbox::rewriteEngineRQ( $this->settings['item_name'], $request ),
						"desjardinsCustomerInvoiceRef" => $request->int_var['invoice'],
						"desjardinsOption1" => $request->metaUser->cmsUser->id, //Send in option1, the id of the user
						"desjardinsOption2" => $request->int_var['invoice'], //Send in option2, the invoice number
						"desjardinsTrxnNumber" => $my_trxn_number,
						"desjardinsSiteTitle" => $this->settings['SiteTitle'],
						"desjardinsCardHoldersName" => $request->int_var['params']['billFirstName'] . ' ' . $request->int_var['params']['billLastName'],
						"desjardinsCardNumber" => $request->int_var['params']['cardNumber'],
						"desjardinsCardExpiryMonth" => $request->int_var['params']['expirationMonth'],
						"desjardinsCardExpiryYear" => $request->int_var['params']['expirationYear'],
						"desjardinsCustomerEmail" => $request->metaUser->cmsUser->email,
						"desjardinsCustomerAddress" => '',
						"desjardinsCustomerPostcode" => '',
						"desjardinsOption3" => ''
						);
		$xml = '<desjardinsgatdesjardins>';*/
		
		return $xml_step1_request->asXML();
	}
		
	function createRequestStep3XML($resp,$request){
	
		$xml_step1_Obj = simplexml_load_string($resp);
		$amount = $request->int_var['amount'] * 100;
		//$host  = $_SERVER['HTTP_HOST'];// & request)URI

		$xml_step3_request = '<?xml version="1.0" encoding="ISO-8859-15" ?>'."\n";
		$xml_step3_request .= '	<request>'."\n";
		$xml_step3_request .= '	  <merchant id="'.trim($xml_step1_Obj->merchant['id']).'" key="'.trim($xml_step1_Obj->merchant['key']).'">'."\n";
		$xml_step3_request .= '		<transactions>'."\n";
		$xml_step3_request .= '		  <transaction id="' . trim($xml_step1_Obj->merchant->login->trx['id']) . '" key="'.trim($xml_step1_Obj->merchant->login->trx['key']) .'" type="purchase" currency="CAD" currencyText="$CAD">'."\n";
		$xml_step3_request .= '			<amount>'.$amount.'</amount>'."\n";
		$xml_step3_request .= '			<language>fr</language>'."\n";
		$xml_step3_request .= '			<urls>'."\n";
		$xml_step3_request .= '			  <url name="response">'."\n";
		$xml_step3_request .= '			  <path>http://www.aqlass.org/dej_return.php</path>'."\n";
		$xml_step3_request .= '			  </url>'."\n";
		$xml_step3_request .= '			  <url name="success">'."\n";
		$xml_step3_request .= '				<path>http://www.aqlass.org/dej_reg_success.php</path>'."\n";
		$xml_step3_request .= '			  </url>'."\n";
		$xml_step3_request .= '			  <url name="cancel">'."\n";
		$xml_step3_request .= '				<path>http://www.aqlass.org/dej_cancel.php</path>'."\n";
		$xml_step3_request .= '			  </url>'."\n";
		$xml_step3_request .= '			  <url name="error">'."\n";
		$xml_step3_request .= '				<path>http://www.aqlass.org/dej_error.php</path>'."\n";
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
		$xml_step3_request .= 'Subscription Cost : '. (Int($amount/1.075/1.05)/100) ."\n";
		$xml_step3_request .= 'Tax TPS: '. (($amount/1.075/1.05)*0.05)/100 ."\n";  
		$xml_step3_request .= 'Tax TVQ : '. ((($amount/1.075/1.05)*0.05 + ($amount/1.075/1.05))*0.075)/100 ."\n";        
		$xml_step3_request .= 'Total                 : '. $amount/100 ."\n";
		$xml_step3_request .= '			   ]]>'."\n";
		$xml_step3_request .= '			</details_text>'."\n";
		$xml_step3_request .= '		  </transaction>'."\n";
		$xml_step3_request .= '		</transactions>'."\n";
		$xml_step3_request .= '	  </merchant>'."\n";
		$xml_step3_request .= '	</request>'."\n";
		
		return $xml_step3_request;
	}

	function transmitRequest( $url, $path, $content )
	{
		echo("starting transmit -> ");
		global $aecConfig;

		$response = null;

		echo ("doing the curl -> ");
		$response = $this->doTheCurl( $url, $content );
		echo ("curl done -> ");
		
		return $response;
	}
	
	function transmitRequestXML( $xml, $request )
	{
		global $mainframe;
		$database = &JFactory::getDBO();
		if ( $this->settings['testmode'] ) {
			$url = 'https://www.labdevtrx3.com/catch';
		} else {
			$url = 'https://www.labdevtrx3.com/catch';
		}
		//$response = array();
		
		//step 1
		$resp =  $this->transmitRequest( $url, '', $xml );
	
		
		//record transaction attempt
		$xml_step1_Obj = simplexml_load_string($resp);
		$sql = "INSERT INTO #__acctexp_desjardins_log (fname , username ,email ,planID, planName ,amount,invoiceNo, trxId , paid, error, date, uid) ";
		$sql .= "VALUES (";
		$sql .= "'". $request->metaUser->cmsUser->name."',";
		//$sql .= "'". $request->metaUser->cmsUser->last_name."',";
		$sql .= "'". $request->metaUser->cmsUser->username."',";
		$sql .= "'". $request->metaUser->cmsUser->email."',";
		$sql .= "'". $request->plan->id ."',";
		$sql .= "'". $request->plan->name. "',";
		$sql .= "'". $request->invoice->amount ."', ";
		$sql .= "'". $request->invoice->invoice_number . "',";
		$sql .= "'". $xml_step1_Obj->merchant->login->trx['id'] ."',"; 
		$sql .= " 0 ,"; //paid == false;
		$sql .= "'',"; //no error yet;
		$sql .= "NOW(),";
		$sql .= $request->metaUser->userid . ")";
		
		$database->setQuery($sql);
		$database->query() or die( $database->stderr() ); 
		
		//step 3
			
		$xml = $this->createRequestStep3XML($resp,$request);
		
		$resp = $this->transmitRequest( $url, '', $xml );
		
		$xml_step3_Obj = simplexml_load_string($resp);
		echo "<br>simplexml string<br>" . $xml_step3_Obj->asXML() . "<br><br>";
		$redir_url = $xml_step3_Obj->merchant->transactions->transaction->urls->url->path;
		$trx_number = $xml_step3_Obj->xpath('merchant/transactions/transaction/urls/url/parameters/parameter[@name="transaction_id"]');
		$trx_key = $xml_step3_Obj->xpath('merchant/transactions/transaction/urls/url/parameters/parameter[@name="transaction_key"]');
		$trx_merch_id = $xml_step3_Obj->xpath('merchant/transactions/transaction/urls/url/parameters/parameter[@name="merchant_id"]');
		$url = $redir_url . "?transaction_id=".$trx_number[0];
		$url .= "&merchant_id=".$trx_merch_id[0];
		$url .= "&transaction_key=".$trx_key[0];
		echo('redir url<br><br>' . $url. '<br><br>');
		$mainframe->redirect($url);
			
		$response = true;
		return $response;
	}

	function checkoutform()
	{
		$var = '';
		//$var = $this->getUserform();
		//$var = $this->getCCform();

		return $var;
	}

	function l($message) {
		//$fh = fopen(echo (dirname(__FILE__) ) . '\log.txt', "a");
		$file = dirname(__FILE__) . '\log\log.text' ;
		$fh = fopen($file, "a");
		fwrite($fh, $message . "\n");
		fclose($fh);
		
	}
	
	function doTheCurl( $url, $content )
	{
		$header[] = "MIME-Version: 1.0";
		$header[] = "Content-type: text/xml";
		$header[] = "Accept: text/xml";
		$header[] = "Content-length: " . strlen($content);
		$header[] = "Cache-Control: no-cache";
		$header[] = "Connection: close";
		$this->l("the message is");
		$this->l(print_r($content, true));
		$ch = curl_init($url);
//		curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER["PHP_SELF"].' VERSION:'. VERSION .' (PHP-' . phpversion() . ')');
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
		curl_setopt( $ch, CURLOPT_POST, 1 );
		curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
		curl_setopt($ch, CURLOPT_TIMEOUT, 25); //times out after 25s
		curl_setopt( $ch, CURLOPT_POSTFIELDS, $content );
		curl_setopt($ch, CURLOPT_VERBOSE, 0);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST,'POST');
		curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, FALSE );
		
		$response = curl_exec( $ch );
		$this->l("the response is");
		$this->l(print_r($response, true));
		if (curl_errno($ch)) {
			 "\nError: " . curl_error($ch);
		} else {
			echo"\nNoError";
		}
		curl_close( $ch );
		return $response;
	}
	function confirm($trxid) 
	{
		$sql = "SELECT * FROM #__acctexp_desjardins_log WHERE trxId ='" . $trxid . "'";
		$this->l("in confirm");
		$database->setQuery($sql);
		$database->query() or die( $database->stderr() );
		$log=$database->loadObjectList();
		$response = array();
		if (count($log) > 0 )
		{
			$sql = "UPDATE #__acctexp_desjardins_log SET paid = '1' WHERE logId =" . $log->logId;
			$database->setQuery($sql);
			$database->query() or die( $database->stderr() );
			$invoice = new InvoiceFactory( null, null, null, 'desjardins', $log->invoiceNo );
			//$invoice->invoice->loadInvoiceNumber($log->invoiceNo);
			
			$response['amount_paid'] = $log->amount;
			$response['invoice'] = $log->invoiceNo;
			$response['valid'] = 1;
			$invoiceFactory->processorResponse('com_acctexp', $response );
			
		} else {
			//something is wrong
		} 
	}
}
?>
