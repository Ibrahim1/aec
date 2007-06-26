<?php
//
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
// Italian translation: Luca Scarpa - http://www.luscarpa.eu
//
// Dont allow direct linking
defined( '_VALID_MOS' ) or die( 'Accesso diretto non acconsentito a questo file.' );

DEFINE ('_DESCRIPTION_PAYPAL', 'Paypal ti lascia inviare soldi a qualcuno attraverso l\'utilizzo delle mail. Paypal è gratuito per i consumatori e lavora associando alla tua carta di credito al tuo account.');
DEFINE ('_DESCRIPTION_PAYPAL_SUBSCRIPTION', 'PayPal Subscription is the Subscription Service that will <strong>automatically bill your account each subscription period</strong>. You can cancel a subscription any time you want from your PayPal account. PayPal is free for consumers and works seamlessly with your existing credit card and checking account.');
DEFINE ('_DESCRIPTION_AUTHORIZE', 'Il gateway di pagamento consente ai commercianti in internet di accettare pagamenti online tramite carta di credito.');
DEFINE ('_DESCRIPTION_VIAKLIX', 'Questo provide integra pagamenti con carte di credito e di debito, controlli eletronici di conversione e relativi applicativi sofware..');
DEFINE ('_DESCRIPTION_ALLOPASS', 'AlloPass, leader europeo nel relativo dominio è un micro sistema di pagamento e consente fatturazione telefonica, via sms e carta di credito.');
DEFINE ('_DESCRIPTION_2CHECKOUT', 'Account di servizi di gestione carta di credito per commercianti con lavori in internet.');
DEFINE ('_DESCRIPTION_EPSNETPAY', 'Der eps ist das einfache, sichere und kostenlose Zahlungssystem der &ouml;sterreichischen Banken f&uuml;r Eink&auml;ufe im Internet.');
DEFINE ('_DESCRIPTION_ALERTPAY', 'Your money is safe with AlertPay\'s account safety policy. AlertPay is open to all businesses.');

// Generic Processor Names&Descs
DEFINE ('_CFG_PROCESSOR_TESTMODE_NAME', 'Test Mode?');
DEFINE ('_CFG_PROCESSOR_TESTMODE_DESC', 'Select Yes if you want to run this processor in test mode. Transactions will not be forwarded to the real processor, but will be either redirected to a testing environment or always return an approved result. If you do not know what this is, just leave it No.');
DEFINE ('_CFG_PROCESSOR_CURRENCY_NAME', 'Currency Selection');
DEFINE ('_CFG_PROCESSOR_CURRENCY_DESC', 'Select the currency that you want to use for this processor.');
DEFINE ('_CFG_PROCESSOR_NAME_NAME', 'Displayed Name');
DEFINE ('_CFG_PROCESSOR_NAME_DESC', 'Change how this Processor is called.');
DEFINE ('_CFG_PROCESSOR_DESC_NAME', 'Displayed Description');
DEFINE ('_CFG_PROCESSOR_DESC_DESC', 'Change the description of this Processor, which is for example shown on the NotAllowed page, Confirmation and Checkout.');
DEFINE ('_CFG_PROCESSOR_ITEM_NAME_NAME', 'Item Description');
DEFINE ('_CFG_PROCESSOR_ITEM_NAME_DESC', 'The Item Description transmitted to the processor.');

// Paypal Settings
DEFINE ('_CFG_PAYPAL_BUSINESS_NAME', 'Business ID:');
DEFINE ('_CFG_PAYPAL_BUSINESS_DESC', 'Il tuo Merchant ID (email) su PayPal.');
DEFINE ('_CFG_PAYPAL_CHECKBUSINESS_NAME', 'Check Business ID:');
DEFINE ('_CFG_PAYPAL_CHECKBUSINESS_DESC', 'Seleziona Si se volete abilitare la procedura di controllo sicurezza quando ricevete la conferma di un pagamento. L\'ID ricevuto dovr&agrave; essere uguale al vostro ID business affiche il vostro pagamento sia accettato, se questo check &egrave; abilitato if this checking is enabled.');
DEFINE ('_CFG_PAYPAL_ALTIPNURL_NAME', 'Alternate IPN Notification Domain:');
DEFINE ('_CFG_PAYPAL_ALTIPNURL_DESC', 'If you use server workload balancing (switching between IP adresses), it might be that Paypal dislikes this and breaks the connection when trying to send an IPN. To work around this, you can for example create a new subdomain on this server and disable the loadbalancing for this. Putting this address in here (In the form "http://subdomain.domain.com" - no trailing slash and whatever you want for subdomain and domain) will make sure that Paypal sends only the IPN back to this Address. <strong>If you are not sure what this means, leave it completely blank!</strong>');
DEFINE ('_CFG_PAYPAL_LC_NAME', 'Language:');
DEFINE ('_CFG_PAYPAL_LC_DESC', 'Select one of the possible language settings for the paypal site that your user will see when issuing a payment.');
DEFINE ('_CFG_PAYPAL_TAX_NAME', 'Tax:');
DEFINE ('_CFG_PAYPAL_TAX_DESC', 'Set the percentage that should be split to taxes. For example if you want 10% of 10$ to be tax - put in a 10. This will result in an amount of 9.09 and a tax amount of additional 0.91.');

// Paypal Subscriptions Settings
DEFINE ('_CFG_PAYPAL_SUBSCRIPTION_BUSINESS_NAME', 'Business ID:');
DEFINE ('_CFG_PAYPAL_SUBSCRIPTION_BUSINESS_DESC', 'Il tuo Merchant ID (email) su PayPal.');
DEFINE ('_CFG_PAYPAL_SUBSCRIPTION_CHECKBUSINESS_NAME', 'Check Business ID:');
DEFINE ('_CFG_PAYPAL_SUBSCRIPTION_CHECKBUSINESS_DESC', 'Seleziona Si se volete abilitare la procedura di controllo sicurezza quando ricevete la conferma di un pagamento. L\'ID ricevuto dovr&agrave; essere uguale al vostro ID business affiche il vostro pagamento sia accettato, se questo check &egrave; abilitato if this checking is enabled.');
DEFINE ('_CFG_PAYPAL_SUBSCRIPTION_ALTIPNURL_NAME', 'Alternate IPN Notification Domain:');
DEFINE ('_CFG_PAYPAL_SUBSCRIPTION_ALTIPNURL_DESC', 'If you use server workload balancing (switching between IP adresses), it might be that Paypal dislikes this and breaks the connection when trying to send an IPN. To work around this, you can for example create a new subdomain on this server and disable the loadbalancing for this. Putting this address in here (In the form "http://subdomain.domain.com" - no trailing slash and whatever you want for subdomain and domain) will make sure that Paypal sends only the IPN back to this Address. <strong>If you are not sure what this means, leave it completely blank!</strong>');
DEFINE ('_CFG_PAYPAL_SUBSCRIPTION_LC_NAME', 'Language:');
DEFINE ('_CFG_PAYPAL_SUBSCRIPTION_LC_DESC', 'Select one of the possible language settings for the paypal site that your user will see when issuing a payment.');
DEFINE ('_CFG_PAYPAL_SUBSCRIPTION_TAX_NAME', 'Tax:');
DEFINE ('_CFG_PAYPAL_SUBSCRIPTION_TAX_DESC', 'Set the percentage that should be split to taxes. For example if you want 10% of 10$ to be tax - put in a 10. This will result in an amount of 9.09 and a tax amount of additional 0.91.');
DEFINE ('_PAYPAL_SUBSCRIPTION_CANCEL_INFO', 'If you want to change your subscription, you first have to cancel your current subscription in your PayPal account!');

// Transfer Settings
DEFINE ('_CFG_TRANSFER_TITLE', 'Transfer');
DEFINE ('_CFG_TRANSFER_SUBTITLE', 'Non-Automatic Payments.');
DEFINE ('_CFG_TRANSFER_ENABLE_NAME', 'Consenti pagamenti non automatici?');
DEFINE ('_CFG_TRANSFER_ENABLE_DESC', 'Seleziona Si se vuoi fornire un opzione agli utenti di pagamenti non automatici, tipo trasferimenti bancari. Registrandosi, l\'utente vedr&agrave; le instruzioni fornite da te (quadro sottostante) su come pagare per inscriversi. Questa opzione non ha processi automatici, per tal ragione &egrave; necessario configurare la data di scadenza direttamente dal pannello di amministrazione.');
DEFINE ('_CFG_TRANSFER_INFO_NAME', 'Informazioni per pagamenti manuali/alternativi:');
DEFINE ('_CFG_TRANSFER_INFO_DESC', 'Testo da presentare all\'utente dopo che ha inizialo la sua registrazione (usate pure tag html). Dopo la registrazione, al primo login una automatica scadenza (configurata nella prima tab) setter&agrave; l\'account dell\'utente. l\'utente deve seguire le vostre instruzioni sul come pagare per registrarsi. E\' necessario che confermarmiate voi stessi il suo pagamento e riconfigurate la data di scadenza.');

// Viaklix Settings
DEFINE ('_CFG_VIAKLIX_ACCOUNTID_NAME', 'Account ID');
DEFINE ('_CFG_VIAKLIX_ACCOUNTID_DESC', 'Il tuo Account ID su viaKLIX.');
DEFINE ('_CFG_VIAKLIX_USERID_NAME', 'User ID');
DEFINE ('_CFG_VIAKLIX_USERID_DESC', 'Il tuo User ID su viaKLIX.');
DEFINE ('_CFG_VIAKLIX_PIN_NAME', 'PIN');
DEFINE ('_CFG_VIAKLIX_PIN_DESC', 'PIN del terminale.');

// Authorize.net Settings
DEFINE ('_CFG_AUTHORIZE_LOGIN_NAME', 'API Login ID');
DEFINE ('_CFG_AUTHORIZE_LOGIN_DESC', 'Il tuo API Login ID su Authorize.net.');
DEFINE ('_CFG_AUTHORIZE_TRANSACTION_KEY_NAME', 'Transaction Key');
DEFINE ('_CFG_AUTHORIZE_TRANSACTION_KEY_DESC', 'La tua Transaction Key su Authorize.net.');

// Allopass Settings
DEFINE ('_CFG_ALLOPASS_SITEID_NAME', 'SITE_ID');
DEFINE ('_CFG_ALLOPASS_SITEID_DESC', 'Il tuo SITE_ID su AlloPass.');
DEFINE ('_CFG_ALLOPASS_DOCID_NAME', 'DOC_ID');
DEFINE ('_CFG_ALLOPASS_DOCID_DESC', 'Il tuo DOC_ID su AlloPass.');
DEFINE ('_CFG_ALLOPASS_AUTH_NAME', 'AUTH');
DEFINE ('_CFG_ALLOPASS_AUTH_DESC', 'AUTH su AlloPass.');

// 2Checkout Settings
DEFINE ('_CFG_2CHECKOUT_SID_NAME', 'SID');
DEFINE ('_CFG_2CHECKOUT_SID_DESC', 'Numero di account 2checkout.');
DEFINE ('_CFG_2CHECKOUT_SECRET_WORD_NAME', 'Parola segreta');
DEFINE ('_CFG_2CHECKOUT_SECRET_WORD_DESC', 'La stessa parola segreta settata da voi nella pagina "Look and Feel".');
DEFINE ('_CFG_2CHECKOUT_INFO_NAME', 'NOTE IMPORTANTI!');
DEFINE ('_CFG_2CHECKOUT_INFO_DESC', 'Nella homepage dell\'account 2Checkout, sezione "Helpful Links", individuate e cliccate sul link "Look and Feel". Settate il campo "Approved URL" con l\' URL "http://yoursite.com/index.php?option=com_acctexp&task=2conotification". Rimuovete "yoursite.com" con il vostro dominio.');
DEFINE ('_CFG_2CHECKOUT_ALT2COURL_NAME', 'Alternate Url');
DEFINE ('_CFG_2CHECKOUT_ALT2COURL_DESC', 'Try this in case you encounter a parameter error.');

// WorldPay Settings
DEFINE ('_CFG_WORLDPAY_INSTID_NAME', 'instId');
DEFINE ('_CFG_WORLDPAY_INSTID_DESC', 'Your WorldPay Installation Id.');

// epsNetpay Settings
DEFINE ('_CFG_EPSNETPAY_MERCHANTID_NAME', 'Merchant ID');
DEFINE ('_CFG_EPSNETPAY_MERCHANTID_DESC', 'It numero di account epsNetpay.');
DEFINE ('_CFG_EPSNETPAY_MERCHANTPIN_NAME', 'Merchant PIN');
DEFINE ('_CFG_EPSNETPAY_MERCHANTPIN_DESC', 'Il tuo Merchant PIN.');
DEFINE ('_CFG_EPSNETPAY_ACTIVATE_NAME', 'Activate');
DEFINE ('_CFG_EPSNETPAY_ACTIVATE_DESC', 'Offer this Bank.');
DEFINE ('_CFG_EPSNETPAY_ACCEPTVOK_NAME', 'Accept VOK');
DEFINE ('_CFG_EPSNETPAY_ACCEPTVOK_DESC', 'It might be that due to the account type you have, you will never get an "OK" response, but always "VOK". If that is the case, please switch this on.');

// Paysignet Settings
DEFINE ('_CFG_PAYSIGNET_MERCHANT_NAME', 'Merchant');
DEFINE ('_CFG_PAYSIGNET_MERCHANT_DESC', 'Your Merchant Name.');

// AlertPay Settings
DEFINE ('_CFG_ALERTPAY_MERCHANT_NAME', 'Merchant');
DEFINE ('_CFG_ALERTPAY_MERCHANT_DESC', 'Your Merchant Name.');
DEFINE ('_CFG_ALERTPAY_SECURITYCODE_NAME', 'Security Code');
DEFINE ('_CFG_ALERTPAY_SECURITYCODE_DESC', 'Your Security Code.');

?>