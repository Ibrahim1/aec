<?php
/**
 * @version $Id: firstdata.php
 * @package AEC - Account Control Expiration - Membership Manager
 * @subpackage Processors - First Data Subscription
 * @copyright 2013 Copyright (C) David Deutsch
 * @author David Deutsch <skore@skore.de> & Team AEC - http://www.valanx.org
 * @license GNU/GPL v.2 http://www.gnu.org/licenses/old-licenses/gpl-2.0.html or, at your option, any later version
 */

// Dont allow direct linking
defined('_JEXEC') or die( 'Direct Access to this location is not allowed.' );

class processor_firstdata extends SOAPprocessor
{
	function info()
	{
		$info = array();
		$info['name']			= 'firstdata';
		$info['longname'] 		= JText::_('CFG_FIRSTDATA_LONGNAME');
		$info['statement'] 		= JText::_('CFG_FIRSTDATA_STATEMENT');
		$info['description'] 	= JText::_('CFG_FIRSTDATA_DESCRIPTION');
		$info['currencies'] 	= 'USD';
		$info['languages'] 		= AECToolbox::getISO3166_1a2_codes();
		$info['cc_list']		= 'visa,mastercard,discover,americanexpress,echeck,giropay';
		$info['recurring']		= 2;
		$info['actions']		= array( 'cancel' => array( 'confirm' ) );

		return $info;
	}

	function settings()
	{
		$settings = array();
		$settings['testmode']				= 0;
		$settings['password']				= '';
		$settings['ssl_cert']				= '/path/to/certificate.pem';
		$settings['ssl_key']				= '/path/to/certificate.key';
		$settings['ssl_key_password']		= '';
		$settings['currency']				= 'USD';

		return $settings;
	}

	function backend_settings()
	{
		$settings = array();
		$settings['testmode']				= array( 'list_yesno' );
		$settings['password']				= array( 'inputC' );
		$settings['ssl_cert']				= array( 'inputC' );
		$settings['ssl_key']				= array( 'inputC' );
		$settings['ssl_key_password']		= array( 'inputC' );
		$settings['currency']				= array( 'list_currency' );

		return $settings;
	}

	function checkoutform( $request )
	{
		$var = $this->getUserform( array(), array( 'firstname', 'lastname', 'address', 'city', 'state', 'zip', 'country_list' ), $request->metaUser );

		$var = $this->getCCform( $var, array( 'card_number', 'card_exp_month', 'card_exp_year', 'card_cvv2' ) );

		return $var;
	}

	function createRequestXML( $request )
	{
		if ( is_array( $request->int_var['amount'] ) ) {
$body = "<SOAP-ENV:Envelope xmlns:SOAP-ENV=\"http://schemas.xmlsoap.org/soap/envelope/\">
	<SOAP-ENV:Header/>
	<SOAP-ENV:Body>
		<fdggwsapi:FDGGWSApiActionRequest xmlns:v1=\"http://secure.linkpt.net/fdggwsapi/schemas_us/v1\" xmlns:a1=\"http://secure.linkpt.net/fdggwsapi/schemas_us/a1\" xmlns:fdggwsapi=\"http://secure.linkpt.net/fdggwsapi/schemas_us/fdggwsapi\">
			<a1:Action>
				<a1:RecurringPayment>
					<a1:RecurringPaymentInformation>
						<a1:RecurringStartDate>".date('Ymd')."</a1:RecurringStartDate>
						<a1:InstallmentCount></a1:InstallmentCount>
						<a1:InstallmentFrequency>".$request->int_var['amount']['period3']."</a1:InstallmentFrequency>
						<a1:InstallmentPeriod>".$this->convertPeriodUnit($request->int_var['amount']['unit3'])."</a1:InstallmentPeriod>
						<a1:MaximumFailures>1</a1:MaximumFailures>
					</a1:RecurringPaymentInformation>
					<a1:TransactionDataType>
						<a1:CreditCardData>
							<v1:CardNumber>".$request->int_var['params']['cardNumber']."</v1:CardNumber>
							<v1:ExpMonth>".$request->int_var['params']['expirationMonth']."</v1:ExpMonth>
							<v1:ExpYear>".substr($request->int_var['params']['expirationYear'],-2)."</v1:ExpYear>
						</a1:CreditCardData>
					</a1:TransactionDataType>
					<v1:Payment>
						<v1:ChargeTotal>".$request->int_var['amount']['amount3']."</v1:ChargeTotal>
						<v1:SubTotal>".$request->int_var['amount']['amount3']."</v1:SubTotal>
					</v1:Payment>
					<v1:Billing>
						<v1:Name>".$request->int_var['params']['billFirstName']." ".$request->int_var['params']['billLastName']."</v1:Name>
						<v1:Address1>".$request->int_var['params']['billAddress']."</v1:Address1>
						<v1:City>".$request->int_var['params']['billCity']."</v1:City>
						<v1:Country>".$request->int_var['params']['billCountry']."</v1:Country>
						<v1:State>".$request->int_var['params']['billState']."</v1:State>
						<v1:Zip>".$request->int_var['params']['billZip']."</v1:Zip>
					</v1:Billing>
					<a1:Function>install</a1:Function>
				</a1:RecurringPayment>
			</a1:Action>
		</fdggwsapi:FDGGWSApiActionRequest>
	</SOAP-ENV:Body>
</SOAP-ENV:Envelope>";
		} else {
$body = "<SOAP-ENV:Envelope xmlns:SOAP-ENV=\"http://schemas.xmlsoap.org/soap/envelope/\">
	<SOAP-ENV:Header/>
	<SOAP-ENV:Body>
		<fdggwsapi:FDGGWSApiOrderRequest xmlns:v1=\"http://secure.linkpt.net/fdggwsapi/schemas_us/v1\">
			<v1:Transaction>
					<v1:CreditCardTxType>
						<v1:Type>Sale</v1:Type>
					</v1:CreditCardTxType>
					<v1:CreditCardData>
						<v1:CardNumber>".$request->int_var['params']['cardNumber']."</v1:CardNumber>
						<v1:ExpMonth>".$request->int_var['params']['expirationMonth']."</v1:ExpMonth>
						<v1:ExpYear>".substr($request->int_var['params']['expirationYear'],-2)."</v1:ExpYear>
					</v1:CreditCardData>
				<v1:Payment>
					<v1:ChargeTotal>".$request->int_var['amount']."</v1:ChargeTotal>
					<v1:SubTotal>".$request->int_var['amount']."</v1:SubTotal>
				</v1:Payment>
			</v1:Transaction>
		</fdggwsapi:FDGGWSApiOrderRequest>
	</SOAP-ENV:Body>
</SOAP-ENV:Envelope>";
		}

		return $body;
	}

	function convertPeriodUnit( $unit )
	{
		$return = array( 'D' => 'day', 'W' => 'week', 'M' => 'month', 'Y' => 'year' );

		if ( isset( $return[$unit] ) ) {
			return $return[$unit];
		} else {
			return $unit;
		}
	}

	function transmitRequestXML( $content, $request )
	{
		if ( $this->settings['testmode'] ) {
			$url	= 'https://ws.merchanttest.firstdataglobalgateway.com';
		} else {
			$url	= 'https://ws.firstdataglobalgateway.com';
		}

		$path = '/fdggwsapi/services/order.wsdl';

		$curlextra = array(
							CURLOPT_HTTPAUTH => CURLAUTH_BASIC,
							CURLOPT_USERPWD => $this->settings['password'],
							CURLOPT_SSLCERT => $this->settings['ssl_cert'],
							CURLOPT_SSLKEY => $this->settings['ssl_key'],
							CURLOPT_SSLKEYPASSWD => $this->settings['ssl_key_password']
					);
		$this->transmitRequest( $url, $path, $content, 443, $curlextra );

		$return['valid'] = false;

		$status = XMLsubstring_tag( $result, 'fdggwsapi:TransactionResult' );
		if ( $status == 'APPROVED' ) {
			$return['valid'] = true;
		}

		return $return;
	}

}
?>