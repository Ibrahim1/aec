<?php
/**
 * @version $Id: english.php 16 2007-06-25 09:04:04Z mic $
 * @package AEC - Account Expiration Control / Subscription management for Joomla
 * @subpackage Processor languages
 * @author AEC - YOUR NAME HERE
 * @copyright 2004-2007 Helder Garcia, David Deutsch
 * @license http://www.gnu.org/copyleft/gpl.html. GNU Public License
 */

// Copyright (C) 2004-2007 Helder Garcia, David Deutsch
// All rights reserved.
// This source file is part of the Account Expiration Control Component, a  Joomla
// custom Component By Helder Garcia and David Deutsch - http://www.globalnerd.org
//
// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License (GPL)
// as published by the Free Software Foundation; either version 2
// of the License, or (at your option) any later version.
//
// Please note that the GPL states that any headers in files and
// Copyright notices as well as credits in headers, source files
// and output (screens, prints, etc.) can not be removed.
// You can extend them with your own credits, though...
//
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with this program; if not, write to the Free Software
// Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
//
// The "GNU General Public License" (GPL) is available at
// http://www.gnu.org/copyleft/gpl.html.
//
// Dont allow direct linking
defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );

define( '_AEC_LANG_PROCESSOR', 1 );

// ################## new 0.12.4
	// paypal
define( '_AEC_PROC_INFO_PP_LNAME',			'PayPal' );
define( '_AEC_PROC_INFO_PP_STMNT',			'Make payments with PayPal - it\'s fast, free and secure!' );
	// paypal subscription
define( '_AEC_PROC_INFO_PPS_LNAME',			'PayPal Subscription' );
define( '_AEC_PROC_INFO_PPS_STMNT',			'Make payments with PayPal - it\'s fast, free and secure!' );
	// 2CheckOut
define( '_AEC_PROC_INFO_2CO_LNAME',			'2CheckOut' );
define( '_AEC_PROC_INFO_2CO_STMNT',			'Make payments with 2Checkout!' );
	// alertpay
define( '_AEC_PROC_INFO_AP_LNAME',			'AlertPay' );
define( '_AEC_PROC_INFO_AP_STMNT',			'Payments with AlertPay' );
	// worldpay
define( '_AEC_PROC_INFO_WP_LNAME',			'WorldPay' );
define( '_AEC_PROC_INFO_WP_STMNT',			'Payments with WorldPay' );
define( '_DESCRIPTION_WORLDPAY',			'Accept payments on the internet, by phone, fax or mail. Credit and debit cards, bank transfers and instalments. In any language and most currencies' );
// END 0.12.4

define( '_DESCRIPTION_PAYPAL', 'PayPal lets you send money to anyone with email. PayPal is free for consumers and works seamlessly with your existing credit card and checking account.');
define( '_DESCRIPTION_PAYPAL_SUBSCRIPTION', 'PayPal Subscription is the Subscription Service that will <strong>automatically bill your account each subscription period</strong>. You can cancel a subscription any time you want from your PayPal account. PayPal is free for consumers and works seamlessly with your existing credit card and checking account.');
define( '_DESCRIPTION_AUTHORIZE', 'Payment gateway enables internet merchants to accept online payments via credit card and e-check.');
define( '_DESCRIPTION_VIAKLIX', 'Provides integrated credit and debit card payment processing, electronic check conversion and related software applications..');
define( '_DESCRIPTION_ALLOPASS', 'AlloPass, European leader in its domain is a micropayment system and allows phone, sms and credit card billing.');
define( '_DESCRIPTION_2CHECKOUT', 'Instant credit card processing services accounts for merchants with internet businesses.');
define( '_DESCRIPTION_EPSNETPAY', 'Der eps ist das einfache, sichere und kostenlose Zahlungssystem der &ouml;sterreichischen Banken f&uuml;r Eink&auml;ufe im Internet.');
define( '_DESCRIPTION_ALERTPAY', 'Your money is safe with AlertPay\'s account safety policy. AlertPay is open to all businesses.');

// Generic Processor Names&Descs
define( '_CFG_PROCESSOR_TESTMODE_NAME', 'Test Mode?');
define( '_CFG_PROCESSOR_TESTMODE_DESC', 'Select Yes if you want to run this processor in test mode. Transactions will not be forwarded to the real processor, but will be either redirected to a testing environment or always return an approved result. If you do not know what this is, just leave it No.');
define( '_CFG_PROCESSOR_CURRENCY_NAME', 'Currency Selection');
define( '_CFG_PROCESSOR_CURRENCY_DESC', 'Select the currency that you want to use for this processor.');
define( '_CFG_PROCESSOR_NAME_NAME', 'Displayed Name');
define( '_CFG_PROCESSOR_NAME_DESC', 'Change how this Processor is called.');
define( '_CFG_PROCESSOR_DESC_NAME', 'Displayed Description');
define( '_CFG_PROCESSOR_DESC_DESC', 'Change the description of this Processor, which is for example shown on the NotAllowed page, Confirmation and Checkout.');
define( '_CFG_PROCESSOR_ITEM_NAME_NAME', 'Item Description');
define( '_CFG_PROCESSOR_ITEM_NAME_DESC', 'The Item Description transmitted to the processor.');
define( '_CFG_PROCESSOR_ITEM_NAME_DEFAULT',	'Subscription at %s - User: %s (%s)' );

// Paypal Settings
define( '_CFG_PAYPAL_BUSINESS_NAME', 'Business ID:');
define( '_CFG_PAYPAL_BUSINESS_DESC', 'Your Merchant ID (email) on PayPal.');
define( '_CFG_PAYPAL_CHECKBUSINESS_NAME', 'Check Business ID:');
define( '_CFG_PAYPAL_CHECKBUSINESS_DESC', 'Select Yes to enable a security check procedure when receiving payment confirmation. The receiver ID field must be equal to the PayPal business ID to the payment be accepted, if this checking is enabled.');
define( '_CFG_PAYPAL_NO_SHIPPING_NAME', 'No Shipping Required:');
define( '_CFG_PAYPAL_NO_SHIPPING_DESC', 'Set this to NO if you want your customers to specify a shipping address - in case you offer a product that needs to be physically distributed');
define( '_CFG_PAYPAL_ALTIPNURL_NAME', 'Alternate IPN Notification Domain:');
define( '_CFG_PAYPAL_ALTIPNURL_DESC', 'If you use server workload balancing (switching between IP adresses), it might be that Paypal dislikes this and breaks the connection when trying to send an IPN. To work around this, you can for example create a new subdomain on this server and disable the loadbalancing for this. Putting this address in here (In the form "http://subdomain.domain.com" - no trailing slash and whatever you want for subdomain and domain) will make sure that Paypal sends only the IPN back to this Address. <strong>If you are not sure what this means, leave it completely blank!</strong>');
define( '_CFG_PAYPAL_LC_NAME', 'Language:');
define( '_CFG_PAYPAL_LC_DESC', 'Select one of the possible language settings for the paypal site that your user will see when issuing a payment.');
define( '_CFG_PAYPAL_TAX_NAME', 'Tax:');
define( '_CFG_PAYPAL_TAX_DESC', 'Set the percentage that should be split to taxes. For example if you want 10% of 10$ to be tax - put in a 10. This will result in an amount of 9.09 and a tax amount of additional 0.91.');

// Paypal Subscriptions Settings
define( '_CFG_PAYPAL_SUBSCRIPTION_BUSINESS_NAME', 'Business ID:');
define( '_CFG_PAYPAL_SUBSCRIPTION_BUSINESS_DESC', 'Your Merchant ID (email) on PayPal.');
define( '_CFG_PAYPAL_SUBSCRIPTION_CHECKBUSINESS_NAME', 'Check Business ID:');
define( '_CFG_PAYPAL_SUBSCRIPTION_CHECKBUSINESS_DESC', 'Select Yes to enable a security check procedure when receiving payment confirmation. The receiver ID field must be equal to the PayPal business ID to the payment be accepted, if this checking is enabled.');
define( '_CFG_PAYPAL_SUBSCRIPTION_NO_SHIPPING_NAME', 'No Shipping Required:');
define( '_CFG_PAYPAL_SUBSCRIPTION_NO_SHIPPING_DESC', 'Set this to NO if you want your customers to specify a shipping address - in case you offer a product that needs to be physically distributed');
define( '_CFG_PAYPAL_SUBSCRIPTION_ALTIPNURL_NAME', 'Alternate IPN Notification Domain:');
define( '_CFG_PAYPAL_SUBSCRIPTION_ALTIPNURL_DESC', 'If you use server workload balancing (switching between IP adresses), it might be that Paypal dislikes this and breaks the connection when trying to send an IPN. To work around this, you can for example create a new subdomain on this server and disable the loadbalancing for this. Putting this address in here (In the form "http://subdomain.domain.com" - no trailing slash and whatever you want for subdomain and domain) will make sure that Paypal sends only the IPN back to this Address. <strong>If you are not sure what this means, leave it completely blank!</strong>');
define( '_CFG_PAYPAL_SUBSCRIPTION_LC_NAME', 'Language:');
define( '_CFG_PAYPAL_SUBSCRIPTION_LC_DESC', 'Select one of the possible language settings for the paypal site that your user will see when issuing a payment.');
define( '_CFG_PAYPAL_SUBSCRIPTION_TAX_NAME', 'Tax:');
define( '_CFG_PAYPAL_SUBSCRIPTION_TAX_DESC', 'Set the percentage that should be split to taxes. For example if you want 10% of 10$ to be tax - put in a 10. This will result in an amount of 9.09 and a tax amount of additional 0.91.');
define( '_PAYPAL_SUBSCRIPTION_CANCEL_INFO', 'If you want to change your subscription, you first have to cancel your current subscription in your PayPal account!');

// Transfer Settings
define( '_CFG_TRANSFER_TITLE', 'Transfer');
define( '_CFG_TRANSFER_SUBTITLE', 'Non-Automatic Payments.');
define( '_CFG_TRANSFER_ENABLE_NAME', 'Allow non automatic payments?');
define( '_CFG_TRANSFER_ENABLE_DESC', 'Select Yes if you want to provide an option for non automatic payments, like bank transfers. Registering user will see instructions provided by you (field below) on how to pay for subscription. This option has no automatic processing, so you have to configure expiration dates manually from backend interface.');
define( '_CFG_TRANSFER_INFO_NAME', 'Info for Manual/Alternative Payment:');
define( '_CFG_TRANSFER_INFO_DESC', 'Text to be presented to the user after his initial registration (use HTML tags). After registration, on first login an automatic expiration (configured on first tab) will be set for the user account. User must follow your instructions on how to pay for subscription. You need to confirm by yourself his payment and reconfigure his expiration date.');

// Viaklix Settings
define( '_CFG_VIAKLIX_ACCOUNTID_NAME', 'Account ID');
define( '_CFG_VIAKLIX_ACCOUNTID_DESC', 'Your Account ID on viaKLIX.');
define( '_CFG_VIAKLIX_USERID_NAME', 'User ID');
define( '_CFG_VIAKLIX_USERID_DESC', 'Your User ID on viaKLIX.');
define( '_CFG_VIAKLIX_PIN_NAME', 'PIN');
define( '_CFG_VIAKLIX_PIN_DESC', 'PIN of the terminal.');

// Authorize.net Settings
define( '_CFG_AUTHORIZE_LOGIN_NAME', 'API Login ID');
define( '_CFG_AUTHORIZE_LOGIN_DESC', 'Your API Login ID on Authorize.net.');
define( '_CFG_AUTHORIZE_TRANSACTION_KEY_NAME', 'Transaction Key');
define( '_CFG_AUTHORIZE_TRANSACTION_KEY_DESC', 'Your Transaction Key on Authorize.net.');

// Allopass Settings
define( '_CFG_ALLOPASS_SITEID_NAME', 'SITE_ID');
define( '_CFG_ALLOPASS_SITEID_DESC', 'Your SITE_ID on AlloPass.');
define( '_CFG_ALLOPASS_DOCID_NAME', 'DOC_ID');
define( '_CFG_ALLOPASS_DOCID_DESC', 'Your DOC_ID on AlloPass.');
define( '_CFG_ALLOPASS_AUTH_NAME', 'AUTH');
define( '_CFG_ALLOPASS_AUTH_DESC', 'AUTH on AlloPass.');

// 2Checkout Settings
define( '_CFG_2CHECKOUT_SID_NAME', 'SID');
define( '_CFG_2CHECKOUT_SID_DESC', 'Your 2checkout account number.');
define( '_CFG_2CHECKOUT_SECRET_WORD_NAME', 'Secret Word');
define( '_CFG_2CHECKOUT_SECRET_WORD_DESC', 'Same secret word set by yourself on the Look and Feel page.');
define( '_CFG_2CHECKOUT_INFO_NAME', 'IMPORTANT NOTE!');
define( '_CFG_2CHECKOUT_INFO_DESC', 'On your 2Checkout Account Homepage, "Helpful Links" section, locate and click the "Look and Feel" link. Set up the field "Approved URL" with the URL "http://yoursite.com/index.php?option=com_acctexp&task=2conotification". Replace "yoursite.com" with your own domain.');
define( '_CFG_2CHECKOUT_ALT2COURL_NAME', 'Alternate Url');
define( '_CFG_2CHECKOUT_ALT2COURL_DESC', 'Try this in case you encounter a parameter error.');

// WorldPay Settings
define( '_CFG_WORLDPAY_INSTID_NAME', 	'instId');
define( '_CFG_WORLDPAY_INSTID_FIELD',	'Your WorldPay ID' ); // new 0.12.4
define( '_CFG_WORLDPAY_INSTID_DESC', 	'Your WorldPay Installation Id.');

// epsNetpay Settings
define( '_CFG_EPSNETPAY_MERCHANTID_NAME', 'Merchant ID');
define( '_CFG_EPSNETPAY_MERCHANTID_DESC', 'Your epsNetpay account number.');
define( '_CFG_EPSNETPAY_MERCHANTPIN_NAME', 'Merchant PIN');
define( '_CFG_EPSNETPAY_MERCHANTPIN_DESC', 'Your Merchant PIN.');
define( '_CFG_EPSNETPAY_ACTIVATE_NAME', 'Activate');
define( '_CFG_EPSNETPAY_ACTIVATE_DESC', 'Offer this Bank.');
define( '_CFG_EPSNETPAY_ACCEPTVOK_NAME', 'Accept VOK');
define( '_CFG_EPSNETPAY_ACCEPTVOK_DESC', 'It might be that due to the account type you have, you will never get an "OK" response, but always "VOK". If that is the case, please switch this on.');

// Paysignet Settings
define( '_CFG_PAYSIGNET_MERCHANT_NAME', 'Merchant');
define( '_CFG_PAYSIGNET_MERCHANT_DESC', 'Your Merchant Name.');

// AlertPay Settings
define( '_CFG_ALERTPAY_MERCHANT_NAME', 'Merchant');
define( '_CFG_ALERTPAY_MERCHANT_DESC', 'Your Merchant Name.');
define( '_CFG_ALERTPAY_SECURITYCODE_NAME', 'Security Code');
define( '_CFG_ALERTPAY_SECURITYCODE_DESC', 'Your Security Code.');

// eWay Settings
define( '_CFG_EWAY_LONGNAME', 'eWay');
define( '_CFG_EWAY_STATEMENT', 'Make payments with eWAY Shared Payment Solution!');
define( '_CFG_EWAY_DESCRIPTION', 'eWAY is the easiest and most affordable payment gateway in Australia. Process credit card payments via eWAY\'s own secure Shared Payment Page in real-time.');
define( '_CFG_EWAY_CUSTID_NAME', 'Customer ID');
define( '_CFG_EWAY_CUSTID_DESC', 'Your Customer ID.');
define( '_CFG_EWAY_AUTOREDIRECT_NAME', 'Autoredirect');
define( '_CFG_EWAY_AUTOREDIRECT_DESC', 'Automatic Redirect for eWay Transaction');
define( '_CFG_EWAY_SITETITLE_NAME', 'Site Title');
define( '_CFG_EWAY_SITETITLE_DESC', 'The Site Title of the eWay Transaction');
?>