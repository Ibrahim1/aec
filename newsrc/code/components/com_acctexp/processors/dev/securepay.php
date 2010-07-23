<?php
// Dont allow direct linking
( defined('_JEXEC') || defined( '_VALID_MOS' ) ) or die( 'Direct Access to this location is not allowed.' );

class processor_securepay extends XMLprocessor
{
	 function info()
	 {
			$info = array();
			$info['name']			= 'securepayxml';
			$info['longname']		= _CFG_SECUREPAY_LONGNAME;
			$info['statement']		= _CFG_SECUREPAY_STATEMENT;
			$info['description']	= _CFG_SECUREPAY_DESCRIPTION;
			$info['cc_list']		= 'visa,mastercard';
			$info['recurring']		= 0;

			return $info;
	 }

	 function settings()
	 {
			$settings = array();
			$settings['testmode']		= "1";
			$settings['merchantid']		= '';
			$settings['password']		= '';
			$settings['tax']			= "10";
			$settings['testAmount']		= "00";
			$settings['item_name']		= sprintf( _CFG_PROCESSOR_ITEM_NAME_DEFAULT, '[[cms_live_site]]', '[[user_name]]', '[[user_username]]' );
			$settings['rewriteInfo']	= '';
			$settings['SiteTitle']		= '';

			return $settings;
	 }

	 function backend_settings()
	 {
			$settings = array();
			$settings['testmode']	= array( 'list_yesno' );
			$settings['merchantid']	= array( 'inputC' );
			$settings['password']	= array( 'inputC' );
			$settings['SiteTitle']	= array( 'inputC' );
			$settings['item_name']	= array( 'inputE' );

			$rewriteswitches         = array( 'cms', 'user', 'expiration', 'subscription', 'plan');
			$settings = AECToolbox::rewriteEngineInfo( $rewriteswitches, $settings );

			return $settings;
	 }

	 function createRequestXML( $request )
	 {
			$order_total = $request->int_var['amount'] * 100;

			$xml .= '<?xml version="1.0" encoding="UTF-8"?>';
			$xml .= '<SecurePayMessage>';

			$xml .= '<MessageInfo>';
			$xml .= '<messageID>8af793f9af34bea0cf40f5fb5c630c</messageID>';
			$xml .= '<messageTimestamp>'.time().'</messageTimestamp>';
			$xml .= '<timeoutValue>60</timeoutValue>';
			$xml .= '<apiVersion>xml-4.2</apiVersion>';
			$xml .= '</MessageInfo>';

			$xml .= '<MerchantInfo>';
			// $xml .= '<merchantID>'.$this->$settings['testmerchantid'].'</merchantID>';
			// $xml .= '<password>'.$this->$settings['password'].'</password>';
			$xml .= '<merchantID>[xxxxxxxxxx]</merchantID>';
			$xml .= '<password>[xxxxxxxxxx]</password>';
			$xml .= '</MerchantInfo>';

			$xml .= '<RequestType>Payment</RequestType>';

			$xml .= '<Payment>';
			$xml .= '<TxnList count="1">';
			$xml .= '<txnType>0</txnType>';
			$xml .= '<txnSource>0</txnSource>';
			$xml .= '<amount>'.$order_total.'</amount>';
			$xml .= '<purchaseOrderNo>'.$request->invoice->invoice_number.'</purchaseOrderNo>';

			$xml .= '<CreditCardInfo>';
			//$xml .= '<cardHolder>'.$request->int_var['params']['billFirstName'] . ' ' . $request->int_var['params']['billLastName'].'</cardNumber>';
			$xml .= '<cardNumber>'.$request->int_var['params']['cardNumber'].'</cardNumber>';
			$xml .= '<expiryDate>'.$request->int_var['params']['expirationYear'].'/'.$request->int_var['params']['expirationMonth'].'</expiryDate>';
			$xml .= '</CreditCardInfo>';

			$xml .= '</Txn>';
			$xml .= '</TxnList>';
			$xml .= '</Payment>';
			$xml .= '</SecurePayMessage>';

			$xml .= '</SecurePayMessage>';

			return $xml;
	 }

	 function transmitRequestXML( $xml, $request )
	 {
			if ( $this->settings['testmode'] ) {
				 $url = 'http://test.securepay.com.au/xmlapi/payment';
			} else {
				 $url = 'https://www.securepay.com.au/xmlapi/payment';
			}

			$response = array();

			/* I REALISE THE REST OF THIS PART NEEDS TO BE UPDATED TO REFLECT SECURE PAY'S RESPONSE XML

			if ( $objResponse = simplexml_load_string( $this->transmitRequest( $url, '', $xml ) ) ) {
				 $response['amount_paid'] = $objResponse->securepayReturnAmount / 100;
				 $response['invoice'] = $objResponse->securepayTrxnOption2;

				 if ( $objResponse->securepayTrxnStatus == 'True' ) {
						$response['valid'] = 1;
				 } else {
						$response['valid'] = 0;
						$response['error'] = $objResponse->securepayTrxnError;
				 }
			} else {
				 $response['valid'] = 0;
				 $response['error'] = _CFG_SECUREPAY_CONNECTION_ERROR;
			}

			return $response;
								*/
	 }

	 function checkoutform()
	 {
			$var = $this->getUserform();
			$var = $this->getCCform();

			return $var;
	 }

}
?>