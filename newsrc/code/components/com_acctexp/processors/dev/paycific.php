<?php
/**
 * @version $Id: paycific.php
 * @package AEC - Account Control Expiration - Membership Manager
 * @subpackage Processors - Paycific Subscription
 * @copyright 2012 Copyright (C) Nguyen Chi Trung
 * @author Nguyen Chi Trung <dnctrung@live.com> & Team SYB - http://www.sybt.tk
 * @license GNU/GPL v.3 http://www.gnu.org/licenses/gpl.html or, at your option, any later version
 */

// Dont allow direct linking
( defined('_JEXEC') || defined( '_VALID_MOS' ) ) or die( 'Direct Access to this location is not allowed.' );

class processor_paycific extends POSTprocessor
{
    function info() {
  		$info = array();
		$info['name']					= 'paycific';
		$info['longname']				= JText::_('AEC_PROC_INFO_PC_LNAME');
		$info['statement']				= JText::_('AEC_PROC_INFO_PC_STMNT');
        $info['description']			= JText::_('DESCRIPTION_PAYCIFIC');
        $info['currencies']				= 'USD,EUR,GBP,CHF';
        $info['cc_list']				= 'visa,mastercard,discover,americanexpress,echeck';
        return $info;
    }
    
    function settings() {
        $settings = array();
        $settings['testmode']		= 0;
        $settings['merchantsecret']	= 'xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx';
        $settings['websiteid']      = '';
        $settings['currency']		= 'EUR';
        $settings['acceptpendingecheck'] = 0;
        $settings['item_name']		= sprintf( JText::_('CFG_PROCESSOR_ITEM_NAME_DEFAULT'), '[[cms_live_site]]', '[[user_name]]', '[[user_username]]' );
        
        return $settings;
    }
    function backend_settings() {
        $settings = array();
        $settings['testmode']		= array( 'toggle' );
        $settings['merchantsecret']	= str_replace(" ","",array( 'inputC' ));
        $settings['websiteid']      = array( 'inputC' );
        $settings['currency']		= array( 'list_currency' );
        $settings['acceptpendingecheck']	= array( 'toggle' );
        $settings['item_name']		= array( 'inputE' );
        $settings['customparams']	= array( 'inputD' );
        $settings					= AECToolbox::rewriteEngineInfo( null, $settings );
        return $settings;
    }
    function createGatewayLink( $request ) {
        
        $paycific_url = 'https://www.paycific.com/en/shops/'.$this->settings['websiteid'].'/create_otp_code';
        $p = array(
            "userfield_1"       => $request->metaUser->cmsUser->id,
            "userfield_2"       => $request->invoice->invoice_number,
            "description"       => AECToolbox::rewriteEngineRQ( $this->settings['item_name'], $request ),
            "order_number"      => $request->invoice->invoice_number,
            "amount"            => $request->int_var['amount']*100,
            "currency_code"     => $this->settings['currency']
        );
        ksort($p);
        $hash = md5(implode("",$p).$this->settings['merchantsecret']);
        
        $p_list = "";
        foreach($p as $k => $v)	{
            $p_list .= $k."=".$v."&";
        }
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_POST,1);
        curl_setopt($ch, CURLOPT_POSTFIELDS,$p_list."hash=".$hash);
        curl_setopt($ch, CURLOPT_URL, $paycific_url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST,  2);
        curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        
        if(!$paycific_url = curl_exec ($ch))	{
            die("Sorry, but the PayCific server is not reachable at the moment.");
        }
        curl_close ($ch);
        
        $secondaryident = substr($paycific_url,89,32);
        $invoice_number = $request->invoice->invoice_number;
        processor_paycific::updateSecondaryIdent($secondaryident,$invoice_number);
        
        $var['post_url']		= $paycific_url;
        return $var;
        
    }
    
	function convertPeriodUnit( $unit, $period )
	{
		$return = array();
		$return['period'] = $period;
		switch ( $unit ) {
			case 'D':
				$return['unit'] = 'Day';
				break;
			case 'W':
				$return['unit'] = 'Week';
				break;
			case 'M':
				$return['unit'] = 'Month';
				break;
			case 'Y':
				$return['unit'] = 'Year';
				break;
		}

		return $return;
	}
    
    function parseNotification( $post ) {
        
        $invoice_no     = $_GET['orderid'];
        $response = array();
        $response['invoice']        = $invoice_no;
        
        return $response;
    }


    function validateNotification( $response, $post, $invoice ) {
        
        $checkhash      = $_GET['hash'];
        $hashreport     = processor_paycific::isValidMd5($checkhash);
        $paidreport     = intval($_GET['paid']);
        $hash           = $request->invoice->secondary_ident;
        
        if ( strcmp($hashreport,$hash) == 0 || $paidreport == 1) {
            if ( $this->settings['acceptpendingecheck'] ) {
                if ( is_object( $invoice ) ) {
                    $invoice->addParams( array( 'acceptedpendingecheck' => 1 ) );
                    $invoice->storeload();
                }            
                $response['valid']			= 1;
            } else {
                $response['pending']		= 1;
                $response['pending_reason'] = 'echeck';
            }
        } else {
            $response['valid']      = 0;
            $response['cancel']		= 1;
        }
        
        return $response;
    }


    
    function notificationError( $response, $error )
	{
		echo 'OK=0 ERROR: ' . $error;
	}



	function notificationSuccess( $response )
	{
		aecRedirect(AECToolbox::deadsureURL( 'index.php?option=com_acctexp&amp;task=thanks'));
	}
    
    function updateSecondaryIdent($secondaryident, $invoice_number) {
        $db = &JFactory::getDBO();        
        $query  =    'UPDATE #__acctexp_invoices '
                    .' SET secondary_ident="'.$secondaryident.'"'
                    .' WHERE invoice_number="'.$invoice_number.'"';
        $db->setQuery( $query );
        $db->query();
    }
    
    function isValidMd5($checkhask) {
        return !empty($checkhask) && preg_match('/^[a-f0-9]{32}$/', $checkhask);
    }
    
}

?>