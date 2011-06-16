<?php
/**
* @version $Id: sparkassen_internetkasse_formularservice.php
* @package AEC - Account Control Expiration - Membership Manager
* @subpackage Processors - Sparkassen Internetkasse Formularservice
* @copyright 2007-2008 Copyright (C) David Deutsch
* @author David Deutsch <skore@skore.de> & Team AEC - http://www.valanx.org
* @license GNU/GPL v.2 http://www.gnu.org/licenses/old-licenses/gpl-2.0.html or, at your option, any later version
*/

// Dont allow direct linking
( defined('_JEXEC') || defined('_VALID_MOS') ) or die('Direct Access to this location is not allowed.');

class processor_sparkassen_internetkasse_formularservice extends POSTprocessor
{
	function info()
	{
		$info = array();
		$info['name']			= 'Sparkassen Internetkasse Formularservice';
		$info['longname']		= 'Sparkassen-Internetkasse Formularservice';
		$info['statement']		= 'Sparkassen Internetkasse Formularservice - Die E-Payment-Lösung im Internet';
		$info['description']	= 'Die Sparkassen-Internetkasse ist ein Management-System für das E-Payment im Internet.';
		$info['currencies']		= 'EUR';
		$info['cc_list']		= 'visa,mastercard,eurocard';
		$info['languages']		= AECToolbox::getISO3166_1a2_codes();
		$info['recurring']		= 2;

		return $info;
	}

	function settings()
	{
		$settings = array();
		$settings['testmode']			= 0;
		$settings['pseudocreditcard']	= 0;
		$settings['additionalnote']		= '';
		$settings['currency']			= 'EUR';        
		$settings['sslmerchant']		= '';
		$settings['sslmerchantpass']	= '';
		$settings['merchant']			= '';
		$settings['merchantpass']		= '';

		$settings['item_name']			= sprintf(_CFG_PROCESSOR_ITEM_NAME_DEFAULT, '[[cms_live_site]]', '[[user_name]]', '[[user_username]]');

		return $settings;
	}

	function backend_settings()
	{
		$settings = array();
		$settings['testmode']						= array('list_yesno');
		$settings['pseudocreditcard']				= array('list_yesno');
		$settings['additionalnote']					= array('inputC');
		$settings['sslmerchant']					= array('inputC');
		$settings['sslmerchantpass']				= array('inputC');
		$settings['merchant']						= array('inputC');
		$settings['merchantpass']					= array('inputC');
		$settings['creditcardexpiredmailsubject']	= array('inputC');
		$settings['creditcardexpiredmailbody']		= array('inputE');

		$settings['redirecturls_server']			= array('inputC');
		$settings['redirecturls_testserver']		= array('inputC');

		$settings = AECToolbox::rewriteEngineInfo( null, $settings );

		return $settings;
	}

/*
* The checkoutAciton gets called by the user when he/she clicks on the checkout button.
* It usually forwards the request to the createGatewayLink() function.
* 
* In case we silently want to contact the payment processor, we do handle
* the payment processor right here in this func
* 
*/

function checkoutAction($request, $InvoiceFactory=null) {
	$settings['command'] = 'sslform'; // FIX
	$settings['post_url_shopschnittstelle_test'] = 'https://testsystem.sparkassen-internetkasse.de/request/request/prot/Request.po';
	$settings['post_url_shopschnittstelle'] = 'https://system.sparkassen-internetkasse.de/request/request/prot/Request.po';
	$settings['post_url_formularservice_test'] = 'https://testsystem.sparkassen-internetkasse.de/vbv/mpi_legacy';
	$settings['post_url_formularservice'] = 'https://system.sparkassen-internetkasse.de/vbv/mpi_legacy';
	$settings['payment_options'] = 'cardholder;generate_ppan'; // cardholder;generate_ppan
	$settings['paymentmethod'] = 'creditcard'; // FIX creditcard|registerpan??
	$settings['sessionid'] = session_id(); // [optional]
	$settings['transactiontype'] = 'authorization';  // preauthorization|authorization
	$settings['version'] = '1.5'; // FIX
	$settings['item_number'] = '[[user_id]]';

	// define some parameter to make things clearer
	$IS_TEST = $this->settings['testmode'];
	$IS_SILENT = false; // silent communication, no creditcard form
	$TIMESTAMP = date('YmdHis');


	#echo 'int_var[amount]: ' . $request->int_var['amount'];
	#print_r($_POST);

	$task = $_POST['task'];


	// if we have a ppan, we can make a 'silent' bank transfer
	if ($this->settings['pseudocreditcard']) {

	$ppan = $this->_getPpan();
	#echo 'ppan: '.$ppan;
	if ($ppan != null && $ppan != '') {

		#if (!isset($_POST['aec_passthrough'])) {
		$IS_SILENT = true;
		#}
	}
	}
	#$IS_SILENT = false;
	#echo 'IS_SILENT: '.$IS_SILENT;
	#exit;
	IF ($IS_SILENT) {
	// ACHTUNG: CSSURL HIER EINTRAGEN (dann muss sie nicht in der Händleroberfläche konfiguriert werden)
	if ($IS_TEST) {
		// HTTP-Basic authtentication                
		$post_url = $this->settings['post_url_shopschnittstelle_test'];
	} else {
		$post_url = $this->settings['post_url_shopschnittstelle'];
	}

	$silentmsg = '';

	$silentmsg .= 'command=authorization';  // 
	$silentmsg .= '&payment_options=creditcard';
	$silentmsg .= '&orderid=' . $TIMESTAMP;
	$silentmsg .= '&basketnr=' . trim($request->invoice->invoice_number);  // 
	$silentmsg .= '&amount=' . trim(str_replace('.', '', $request->int_var['amount']));
	$silentmsg .= '&currency=' . trim($this->settings['currency']);
	$silentmsg .= '&ppan=' . $ppan;


	#echo $post_url . '<br>';
	$c = curl_init();
	curl_setopt($c, CURLOPT_URL, $post_url);

	// no header in output
	curl_setopt($c, CURLOPT_HEADER, 0);

	// return reponse as result
	curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);

	// do a POST-Request
	curl_setopt($c, CURLOPT_POST, 1);
	curl_setopt($c, CURLOPT_TIMEOUT, 60);
	curl_setopt($c, CURLOPT_USERPWD, $this->settings['merchant'] . ':' . $this->settings['merchantpass']);
	curl_setopt($c, CURLOPT_POSTFIELDS, $silentmsg);

	$resultstring = curl_exec($c);
	curl_close($c);



	echo "Plain request: " . $silentmsg . "\n";
	echo "Plain result : " . $resultstring . "\n";

	$server = $this->settings['redirecturls_server'];
	if ($this->settings['testmode']) {
		$server = $this->settings['redirecturls_testserver'];
	}
	$c = curl_init();
	curl_setopt($c, CURLOPT_URL, 'http://' . $server . '/index.php?option=com_acctexp&task=sparkassen_internetkasse_formularservicenotification');

	// no header in output
	curl_setopt($c, CURLOPT_HEADER, 0);

	// return reponse as result
	curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);

	// do a POST-Request
	curl_setopt($c, CURLOPT_POST, 1);
	curl_setopt($c, CURLOPT_TIMEOUT, 60);
	curl_setopt($c, CURLOPT_POSTFIELDS, $resultstring.='&response_shopschnittstelle=1');

	$result = curl_exec($c);
	curl_close($c);

	print_r($result);
	exit;

	//return $this->checkoutResponse($request, $response, $InvoiceFactory);
	}
	// Display standard CC collection Form
	return parent::checkoutAction($request, $InvoiceFactory);
}

/**
* organizes the communication toward Sparkassen-Internetkasse.
* 
* If we have a ppan - we will use it to make a silent payment (via 
* 'Shopschnittstelle' without need to involve user action). 
* In all other cases we will present the user the payment form 
* (via 'Formularservice').
* 
* @param Array $request
* @return Array 
*/
function createGatewayLink($request) {

	$IS_TEST = $this->settings['testmode'];
	$TIMESTAMP = date('YmdHis');

	if (is_array($request->int_var['amount'])) {
	#echo 'int_var[amount]: ' . $request->int_var['amount'];
	}
	// ACHTUNG: CSSURL HIER EINTRAGEN (dann muss sie nicht in der Händleroberfläche konfiguriert werden)
	if ($IS_TEST) {
	// 2011.05.24 URL für Testsystem telefonisch bestätigt
	if ($IS_SILENT) {
		// HTTP-Basic authtentication                
		$post_url = $this->settings['post_url_shopschnittstelle_test'];
	} else {
		$post_url = $this->settings['post_url_formularservice_test'];
	}
	} else {
	// 2011.05.24 URL muss nach Bestellung Sparkassen-Internetkasse erfragt werden
	if ($IS_SILENT) {
		$post_url = $this->settings['post_url_shopschnittstelle'];
	} else {
		$post_url = $this->settings['post_url_formularservice'];
	}
	}

	// Calculate MAC
	/*
	*  IMPORTANT NOTE: Because the $var array elements gets printed into a
	*  string the order of the values is essential !!!! 
	*/
	#print_r($request->int_var['amount']);
	#print_r($request);
	#print_r($request->invoice);
	#$var['additionalnote']  = trim($this->settings['additionalnote']);

	$var['amount'] = trim(str_replace('.', ',', $request->int_var['amount']));
	$var['basketid'] = trim($request->invoice->invoice_number);  // 
	$var['command'] = trim($this->settings['command']);
	$var['currency'] = trim($this->settings['currency']);
	#$var['date']            = date('Ymd_H:m:i');  
	$var['orderid'] = $TIMESTAMP;
	$var['payment_options'] = trim($this->settings['payment_options']);
	$var['paymentmethod'] = trim($this->settings['paymentmethod']);
	$var['sessionid'] = session_id();
	$var['sslmerchant'] = trim($this->settings['sslmerchant']);
	$var['transactiontype'] = $this->settings['transactiontype'];
	$var['version'] = $this->settings['version'];

	#$var['item_number']		= AECToolbox::rewriteEngineRQ( $this->settings['item_number'], $request );
	//$var['item_name']		= AECToolbox::rewriteEngineRQ( $this->settings['item_name'], $request );
	#$var['item_name']	= $request->items->itemlist['0']['name'];

	$mac_string = '';
	$redirect = $post_url . '?';
	$counter = 0;
	foreach ($var as $k => $v) {
	$counter++;
	$mac_string .= $v;
	if ($counter > 1) {
		$redirect .= '&amp;';
	}
	$redirect .= $k . '=' . $v;
	}
	// This gets printed out on the overview page
	#echo '<br><br><br><br><br><br>';
	#echo 'url: ' . $post_url . '<br>';
	#echo 'key: ' . $this->settings['sslmerchantpass'] . '<br>';
	#echo 'mac_string: ' . $mac_string . '<br>';
	$var['mac'] = $this->_hmac($this->settings['sslmerchantpass'], $mac_string);  // generate

	#echo 'mac: ' . $var['mac'] . '<br>';

	#echo 'mac_array: <br/>';
	#print_r($var);

	$redirect .= '&amp;mac=' . $var['mac'];

	$var['post_url'] = '/kasse';
	//$var['post_url'] = $post_url;

	$var['redirect'] = $redirect;
	return $var;
}

function parseNotification($post) {

	#echo '<h2>Parse Notification</h2>';
	#echo '<h3>POST</h3>';
	#print_r($post);
	// 2011.06.07 Response String
	//timestamp=&txn_date=07%2F06%2F2011&txn_time=11%3A43&txntype=&pcode=&posem=&poscc=&aid=&merch_name=Homeier&merch_street=&merch_town=&merch_tid=57042347&rc=&rmsg=Parameter%20%27payment_options%27%20falsch.&retrefnr=&posherr=310&posh_version=1.0.118&cai=&txn_card=&txn_expdat=&orderid=IYWNkMmQ3NmNkODcx&trefnum=&basketnr=&amount=1%2C00&currency=EUR


	$response = array();

	if (array_key_exists('response_shopschnittstelle', $post)) {
	$response['aid'] = $post['aid'];
	$response['amount'] = $post['amount'] / 100;
	$response['basketnr'] = $post['basketnr'];
	$response['cai'] = $post['cai'];
	$response['creditc'] = $post['creditc'];
	$response['currency'] = $post['currency'];
	$response['cvcode'] = $post['cvcode'];
	$response['expdat'] = $post['expdat'];
	$response['merch_name'] = $post['merch_name'];
	$response['merch_street'] = $post['merch_street'];
	$response['merch_tid'] = $post['merch_tid'];
	$response['merch_town'] = $post['merch_town'];
	$response['orderid'] = $post['orderid'];
	$response['pcode'] = $post['pcode'];
	$response['poscc'] = $post['poscc'];
	$response['posem'] = $post['posem'];
	$response['posh_version'] = $post['posh_version'];
	$response['posherr'] = $post['posherr'];
	$response['ppan'] = $post['ppan'];
	$response['rc'] = $post['rc'];
	$response['retrefnr'] = $post['retrefnr'];
	$response['rmsg'] = $post['rmsg'];
	$response['timestamp'] = $post['timestamp'];
	$response['trefnum'] = $post['trefnum'];
	$response['txn_card'] = $post['txn_card'];
	$response['txn_date'] = $post['txn_date'];
	$response['txn_expdat'] = $post['txn_expdat'];
	$response['txn_time'] = $post['txn_time'];
	$response['txntype'] = $post['txntype'];


	// TODO save ppan to database
	// Values AEC needs
	$response['invoice'] = $post['basketnr'];
	$response['recurring'] = 1;
	$response['amount_paid'] = $post['amount'] / 100;
	$response['amount_currency'] = $post['currency'];
	} else {

	$response['aid'] = $post['aid'];
	$response['amount'] = str_replace(',', '.', $post['amount']);
	$response['basketid'] = $post['basketid'];
	$response['basketnr'] = $post['basketnr'];
	$response['currency'] = $post['currency'];
	$response['deliverycountry'] = $post['deliverycountry'];
	$response['directPosErrorCode'] = $post['directPosErrorCode'];
	$response['directPosErrorMessage'] = utf8_decode($post['directPosErrorMessage']);
	$response['mac'] = $post['mac'];
	$response['orderid'] = $post['orderid'];
	$response['ppan'] = $post['ppan'];
	$response['rc'] = $post['rc'];
	$response['rc_avsamex'] = $post['rc_avsamex'];
	$response['rc_score'] = $post['rc_score'];
	$response['retrefnum'] = $post['retrefnum'];
	$response['sessionid'] = $post['sessionid'];
	$response['trefnum'] = $post['trefnum'];


	// ALTERNATIV: Registrierung einer Pseudokartennummer
	$response['cardholder'] = $post['cardholder'];
	$response['creditc'] = $post['creditc'];
	$response['expdat'] = $post['expdat'];
	$response['txn_card'] = $post['txn_card'];

	#echo '<h3>Response Array</h3>';
	#print_r($response);
	// Values PseudoKreditkarten-Transactions will return
	$response['posh_version'] = $post['posh_version'];
	$response['txntype'] = $post['txntype']; //Pflichtfeld
	$response['posherr'] = $post['posherr']; //Pflichtfeld
	$response['rmsg'] = $post['rmsg']; //Pflichtfeld
	$response['rc'] = $post['rc']; //Pflichtfeld, kann leer sein.
	$response['txn_date'] = $post['txn_date']; //Pflichtfeld
	$response['txn_time'] = $post['txn_time']; //Pflichtfeld
	$response['merch_name'] = $post['merch_name']; //Pflichtfeld, kann leer sein.
	$response['merch_street'] = $post['merch_street']; //Pflichtfeld, kann leer sein.
	$response['merch_town'] = $post['merch_town']; //Pflichtfeld, kann leer sein.
	$response['merch_tid'] = $post['merch_tid']; //Pflichtfeld, kann leer sein.
	//Im Response enthalten, wenn Parameter rc einen Wert enthält.
	$response['pcode'] = $post['pcode']; //Pflichtfeld
	$response['posem'] = $post['posem']; //Pflichtfeld
	$response['poscc'] = $post['poscc']; //Pflichtfeld
	$response['aid'] = $post['aid']; //Pflichtfeld
	$response['trefnum'] = $post['trefnum']; //Pflichtfeld
	$response['retrefnr'] = $post['retrefnr']; //Pflichtfeld
	$response['txntype'] = $post['txntype']; //Pflichtfeld
	$response['timestamp'] = $post['timestamp']; //Pflichtfeld
	$response['cai'] = $post['cai']; //Pflichtfeld
	$response['txn_card'] = $post['txn_card']; //Pflichtfeld
	$response['txn_expdat'] = $post['txn_expdat']; //Pflichtfeld
	$response['rc_score'] = $post['rc_score']; //Nach American Express Adressverifizierung
	$response['rc_avsamex'] = $post['rc_avsamex']; //Nach American Express Adressverifizierung
	// TODO save ppan to database
	// 
	// Values AEC needs
	if (array_key_exists('basketid', $post) && strcmp($post['basketid'], '') != 0) {
		$response['invoice'] = $response['basketid'];
	} else {
		$response['invoice'] = $response['basketnr'];
	}
	$response['recurring'] = 1;
	$response['amount_paid'] = $response['amount'];
	$response['amount_currency'] = $response['currency'];
	}


	return $response;
}

function validateNotification($response, $post, $invoice) {

	$server = $this->settings['redirecturls_server'];
	if ($this->settings['testmode']) {
	$server = $this->settings['redirecturls_testserver'];
	}

	$response['valid'] = 0;

	if (array_key_exists('response_shopschnittstelle', $post)) {
	if ($response['posherr'] == '0') {
		$response['valid'] = true;
	} else {
		$response['valid'] = 0;

		$msg = '';
		if ($_GET['code'] == '133') {
		$msg = 'Karte abgelaufen.';
		$this->_removePpan($response['invoice'], $is_notify_user = true);
		} else if ($_GET['code'] == '344') {
		$msg = 'Karte in Deutschland nicht gültig. ';
		$this->_removePpan($response['invoice']);
		} else if ($_GET['code'] == '347') {
		$msg = 'Abbruch durch den Benutzer.';
		$this->_removePpan($response['invoice']);
		} else if ($_GET['code'] == '349') {
		$msg = 'Transaktionslimit überschritten.';
		$this->_removePpan($response['invoice']);
		} else if ($_GET['code'] == '350') {
		$msg = 'Karte gesperrt';
		$this->_removePpan($response['invoice']);
		}

		
		// TODO do something, i.e. send an email to the client
	}
	} else {
	if ($response['directPosErrorCode'] == '0') {
		$response['valid'] = true;

		if ($this->settings['pseudocreditcard']) {

		$this->_setPpan($post['ppan'], $response['invoice']);
		}
		#exit;
		echo 'redirecturls=http://' . $server . '/transaction-success';
	} else {
		$response['valid'] = 0;

		$msg = '';
		if ($_GET['code'] == '133') {
		$msg = 'Karte abgelaufen.';
		} else if ($_GET['code'] == '344') {
		$msg = 'Karte in Deutschland nicht gültig. ';
		} else if ($_GET['code'] == '347') {
		$msg = 'Abbruch durch den Benutzer.';
		} else if ($_GET['code'] == '349') {
		$msg = 'Transaktionslimit überschritten.';
		} else if ($_GET['code'] == '350') {
		$msg = 'Karte gesperrt';
		}
		
		$response['pending_reason'] = $msg . ' Fehlercode ' . $response['directPosErrorCode'];
		echo 'redirecturlf=http://' . $server . '/transaction-error?msg=' . urlencode($response['pending_reason']);
	}
	}
	return $response;
}

/**
* Calculates the MAC - Message Authentication Code
* 
* uses the HMAC (RFC 2104)
*       
* @see $DOC, p. 63ff, p. 68
* @param type $key
* @param type $data
* @return type 
*/
function _hmac($key, $data) {
	// RFC 2104 HMAC implementation for php.
	// Creates an SHA-1 HMAC.
	$b = 64; // byte length for SHA-1
	// if the key has more than 64 bytes, hash it
	//if (strlen($key) > $b) {
	// $key = pack("H*",sha1($key));
	//}
	$key = str_pad($key, $b, chr(0x00));
	$ipad = str_pad('', $b, chr(0x36));
	$opad = str_pad('', $b, chr(0x5c));
	$k_ipad = $key ^ $ipad;
	$k_opad = $key ^ $opad;

	//Test:
	//echo hmac("8A!v#6qPc3?+G1on", "10,00ba_100202sslformEUR20091206_12:23:452009120601creditcardNhdz747458sNXmycompanypreauthorization1.5");

	return sha1($k_opad . pack("H*", sha1($k_ipad . $data)));
}

function _setPpan($ppan, $invoice_number) {

	$db = & JFactory::getDBO();
	$ppan_field = 'ppan';

	$query = 'SELECT `userid` FROM #__acctexp_invoices WHERE `invoice_number` = \'' . $invoice_number . '\'';
	$db->setQuery($query);

	$userid = $db->loadResult();

	$db = & JFactory::getDBO();
	$query = 'SHOW COLUMNS FROM #__acctexp_subscr';
	$db->setQuery($query);
	$columns = $db->loadAssocList();
	$exists = false;
	#print_r($columns);
	foreach ($columns as $c) {
	#echo $c['Field'];

	if ($c['Field'] == $ppan_field) {
		$exists = true;
		break;
	}
	}
	if (!$exists) {
	$query = "ALTER TABLE #__acctexp_subscr ADD `" . $ppan_field . "` varchar(250)";
	$db->setQuery($query);
	$db->query() or die($db->stderr());
	}

	$query = "UPDATE #__acctexp_subscr SET `" . $ppan_field . "` = '" . $ppan . "' WHERE `userid` = " . $userid;
	$db->setQuery($query);
	$db->query() or die($db->stderr());
}

function _getPpan() {

	$user = & JFactory::getUser();
	$db = & JFactory::getDBO();
	$ppan_field = 'ppan';

	$query = 'SELECT `'.$ppan_field.'` FROM `#__acctexp_subscr` WHERE `userid` = ' . $user->id;
	$db->setQuery($query);
	
	$ppan = $db->loadResult();

	return $ppan;
}

function _removePpan($invoice_number, $is_notify_user=false) {

	$db = & JFactory::getDBO();
	$ppan_field = 'ppan';

	$query = 'SELECT `userid` FROM #__acctexp_invoices WHERE `invoice_number` = \'' . $invoice_number . '\'';
	$db->setQuery($query);

	$userid = $db->loadResult();
	
	if ($is_notify_user) {

	$query = "SELECT `email`, `name` FROM #__users WHERE `id` = " . $userid;
	$db->setQuery($query);

	$receipient = $db->loadResult();
	
	
	$app = JFactory::getApplication();
	if ($app->getCfg('mailfrom') != '' && $app->getCfg('fromname') != '') {
		$adminName2 = $app->getCfg('fromname');
		$adminEmail2 = $app->getCfg('mailfrom');
	} else {
		$rows = aecACLhandler::getSuperAdmins();
		$row2 = $rows[0];

		$adminName2 = $row2->name;
		$adminEmail2 = $row2->email;
	}
	
	JUTility::sendMail($adminEmail2, $adminEmail2, $receipient, $this->settings['creditcardexpiredmailsubject'], $this->settings['creditcardexpiredmailbody']);
	}
	
	$query = "UPDATE #__acctexp_subscr SET `".$ppan_field."` = '' WHERE `userid` = " . $userid;
	$db->setQuery($query);
	$db->query() or die($db->stderr());
	
	
}

}

?>