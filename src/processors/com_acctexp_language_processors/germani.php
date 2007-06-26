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
defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );

DEFINE ('_DESCRIPTION_PAYPAL', 'Mit PayPal k&ouml;nnen Sie Zahlungen an jeden senden, der eine E-Mail-Adresse hat. PayPal ist f&uuml;r Kunden kostenlos und wird in Ihr vorhandenes Kreditkarten- und Bankkonto integriert.');
DEFINE ('_DESCRIPTION_PAYPAL_SUBSCRIPTION', 'PayPal Subscription ist der PayPal Service f&uuml;r wiederkehrende Zahlungen mit dem <strong>dein Konto deinem Abonnement entsprechend automatisch wiederkehrend belastet wird</strong>. Dies kannst du selbstverst&auml;ndlich jederzeit in deinem PayPal Konto abbrechen. PayPal ist f&uuml;r Kunden kostenlos und wird in dein vorhandenes Kreditkarten- und Bankkonto integriert.');
DEFINE ('_DESCRIPTION_AUTHORIZE', 'Payment gateway enables internet merchants to accept online payments via credit card and e-check.');
DEFINE ('_DESCRIPTION_VIAKLIX', 'Provides integrated credit and debit card payment processing, electronic check conversion and related software applications..');
DEFINE ('_DESCRIPTION_ALLOPASS', 'AlloPass, European leader in its domain is a micropayment system and allows phone, sms and credit card billing.');
DEFINE ('_DESCRIPTION_2CHECKOUT', 'Instant credit card processing services accounts for merchants with internet businesses.');
DEFINE ('_DESCRIPTION_EPSNETPAY', 'Der eps ist das einfache, sichere und kostenlose Zahlungssystem der &ouml;sterreichischen Banken f&uuml;r Eink&auml;ufe im Internet.');
DEFINE ('_DESCRIPTION_ALERTPAY', 'Your money is safe with AlertPay\'s account safety policy. AlertPay is open to all businesses.');

// Generic Processor Names&Descs
DEFINE ('_CFG_PROCESSOR_TESTMODE_NAME', 'Test Modus?');
DEFINE ('_CFG_PROCESSOR_TESTMODE_DESC', 'W&auml;hlen Sie "Ja", um den Test-Modus zu aktivieren, hierbei wird entweder zu einer Testumgebung des Bezahlservices umgeleitet, oder die Bezahlung wird ohne Umweg direkt bezahlt. Falls sie nicht wissen, was dies bedeutet, belassen sie es als "Nein".');
DEFINE ('_CFG_PROCESSOR_CURRENCY_NAME', 'W&auml;hrung');
DEFINE ('_CFG_PROCESSOR_CURRENCY_DESC', 'W&auml;hlen sie die W&auml;hrung die sie f&uuml;r diesen Bezahlservice nutzen m&ouml;chten');
DEFINE ('_CFG_PROCESSOR_NAME_NAME', 'Displayed Name');
DEFINE ('_CFG_PROCESSOR_NAME_DESC', 'Change how this Processor is called.');
DEFINE ('_CFG_PROCESSOR_DESC_NAME', 'Displayed Description');
DEFINE ('_CFG_PROCESSOR_DESC_DESC', 'Change the description of this Processor, which is for example shown on the NotAllowed page, Confirmation and Checkout.');
DEFINE ('_CFG_PROCESSOR_ITEM_NAME_NAME', 'Item Description');
DEFINE ('_CFG_PROCESSOR_ITEM_NAME_DESC', 'The Item Description transmitted to the processor.');

// Paypal Settings
DEFINE ('_CFG_PAYPAL_BUSINESS_NAME', 'Business ID:');
DEFINE ('_CFG_PAYPAL_BUSINESS_DESC', 'Ihre Merchant ID (email) bei PayPal.');
DEFINE ('_CFG_PAYPAL_CHECKBUSINESS_NAME', 'Business ID Check:');
DEFINE ('_CFG_PAYPAL_CHECKBUSINESS_DESC', 'Sicherheits-&Uuml;berpr&uuml;fung - Wenn dies aktiviert ist, wird bei der &Uuml;bermittlung der Paypal Daten getestet, ob die Empf&auml;nger-E-Mail Adresse mit der von ihnen angegebenen &uuml;bereinstimmt. Dies kann einen Fehler produzieren, falls sie bei Paypal mehr als nur eine Adresse benutzen. Wenn Benutzer also nicht aktiviert werden obwohl sie korrekt bezahlt haben, w&auml;hlen sie "Nein"');
DEFINE ('_CFG_PAYPAL_ALTIPNURL_NAME', 'Alternate IPN Notification Domain:');
DEFINE ('_CFG_PAYPAL_ALTIPNURL_DESC', 'If you use server workload balancing (switching between IP adresses), it might be that Paypal dislikes this and breaks the connection when trying to send an IPN. To work around this, you can for example create a new subdomain on this server and disable the loadbalancing for this. Putting this address in here (In the form "http://subdomain.domain.com" - no trailing slash and whatever you want for subdomain and domain) will make sure that Paypal sends only the IPN back to this Address. <strong>If you are not sure what this means, leave it completely blank!</strong>');
DEFINE ('_CFG_PAYPAL_LC_NAME', 'Language:');
DEFINE ('_CFG_PAYPAL_LC_DESC', 'Select one of the possible language settings for the paypal site that your user will see when issuing a payment.');
DEFINE ('_CFG_PAYPAL_TAX_NAME', 'Tax:');
DEFINE ('_CFG_PAYPAL_TAX_DESC', 'Set the percentage that should be split to taxes. For example if you want 10% of 10$ to be tax - put in a 10. This will result in an amount of 9.09 and a tax amount of additional 0.91.');

// Paypal Subscriptions Settings
DEFINE ('_CFG_PAYPAL_SUBSCRIPTION_BUSINESS_NAME', 'Business ID:');
DEFINE ('_CFG_PAYPAL_SUBSCRIPTION_BUSINESS_DESC', 'Ihre Merchant ID (email) bei PayPal.');
DEFINE ('_CFG_PAYPAL_SUBSCRIPTION_CHECKBUSINESS_NAME', 'Business ID Check:');
DEFINE ('_CFG_PAYPAL_SUBSCRIPTION_CHECKBUSINESS_DESC', 'Sicherheits-&Uuml;berpr&uuml;fung - Wenn dies aktiviert ist, wird bei der &Uuml;bermittlung der Paypal Daten getestet, ob die Empf&auml;nger-E-Mail Adresse mit der von ihnen angegebenen &uuml;bereinstimmt. Dies kann einen Fehler produzieren, falls sie bei Paypal mehr als nur eine Adresse benutzen. Wenn Benutzer also nicht aktiviert werden obwohl sie korrekt bezahlt haben, w&auml;hlen sie "Nein"');
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
DEFINE ('_CFG_TRANSFER_ENABLE_NAME', 'Nichtautomatische Bezahlungen erlauben?');
DEFINE ('_CFG_TRANSFER_ENABLE_DESC', 'W&auml;hlen sie Ja, falls sie eine Mglichkeit f&uuml;r nichtautomatische Bezahlung einr&auml;umen m&ouml;chten. Hierf&uuml;r ist es m&ouml;glich, dem Benutzer Anweisungen zur alternativen Bezahlung zu &uuml;bermitteln.');
DEFINE ('_CFG_TRANSFER_INFO_NAME', 'Info f&uuml;r manuelle/alternative Bezahlung:');
DEFINE ('_CFG_TRANSFER_INFO_DESC', 'Text, den der Benutzer nach seiner ersten Anmeldung sieht (HTML tags k&ouml;nnen benutzt werden). Nach der Registrierung wird der Benutzer automatisch deaktiviert sein und ihren Anweisungen folgen m&uuml;ssen. Sie m&uuml;ssen seine Registrierung manuell im Backend einstellen.');

// Viaklix Settings
DEFINE ('_CFG_VIAKLIX_ACCOUNTID_NAME', 'Account ID');
DEFINE ('_CFG_VIAKLIX_ACCOUNTID_DESC', 'Ihre Account ID bei viaKLIX.');
DEFINE ('_CFG_VIAKLIX_USERID_NAME', 'User ID');
DEFINE ('_CFG_VIAKLIX_USERID_DESC', 'Ihre User ID bei viaKLIX.');
DEFINE ('_CFG_VIAKLIX_PIN_NAME', 'PIN');
DEFINE ('_CFG_VIAKLIX_PIN_DESC', 'PIN des Terminals.');

// Authorize.net Settings
DEFINE ('_CFG_AUTHORIZE_LOGIN_NAME', 'Login ID');
DEFINE ('_CFG_AUTHORIZE_LOGIN_DESC', 'Ihre Login ID bei Authorize.net.');
DEFINE ('_CFG_AUTHORIZE_TRANSACTION_KEY_NAME', 'Transaktions Schlssel');
DEFINE ('_CFG_AUTHORIZE_TRANSACTION_KEY_DESC', 'Ihr Transaktions-Schlssel bei Authorize.net.');

// Allopass Settings
DEFINE ('_CFG_ALLOPASS_SITEID_NAME', 'SITE_ID');
DEFINE ('_CFG_ALLOPASS_SITEID_DESC', 'Your SITE_ID on AlloPass.');
DEFINE ('_CFG_ALLOPASS_DOCID_NAME', 'DOC_ID');
DEFINE ('_CFG_ALLOPASS_DOCID_DESC', 'Your DOC_ID on AlloPass.');
DEFINE ('_CFG_ALLOPASS_AUTH_NAME', 'AUTH');
DEFINE ('_CFG_ALLOPASS_AUTH_DESC', 'AUTH on AlloPass.');

// 2Checkout Settings
DEFINE ('_CFG_2CHECKOUT_SID_NAME', 'SID');
DEFINE ('_CFG_2CHECKOUT_SID_DESC', 'Ihre 2checkout Account-Nummer.');
DEFINE ('_CFG_2CHECKOUT_SECRET_WORD_NAME', 'Geheimes Wort');
DEFINE ('_CFG_2CHECKOUT_SECRET_WORD_DESC', 'Das selbe geheime Wort, welches sie auf der "Look and Feel" Seite angegeben haben.');
DEFINE ('_CFG_2CHECKOUT_INFO_NAME', 'WICHTIG!');
DEFINE ('_CFG_2CHECKOUT_INFO_DESC', 'Sie m&uuml;ssen auf ihrer 2Checkout Account Homepage noch eine Einstellung vornehmen! Gehen sie dafür auf ihrer 2CO Homepage in die Kategorie "Helpful Links" und clicken sie dort auf den "Look and Feel" link. &Auml;ndern sie das Feld "Approved URL" mit der URL "http://yoursite.com/index.php?option=com_acctexp&task=2conotification". Ersetzen sie "yoursite.com" mit ihrer domain.');
DEFINE ('_CFG_2CHECKOUT_ALT2COURL_NAME', 'Alternate Url');
DEFINE ('_CFG_2CHECKOUT_ALT2COURL_DESC', 'Try this in case you encounter a parameter error.');

// WorldPay Settings
DEFINE ('_CFG_WORLDPAY_INSTID_NAME', 'instId');
DEFINE ('_CFG_WORLDPAY_INSTID_DESC', 'Your WorldPay Installation Id.');

// epsNetpay Settings
DEFINE ('_CFG_EPSNETPAY_MERCHANTID_NAME', 'H&auml;ndler ID');
DEFINE ('_CFG_EPSNETPAY_MERCHANTID_DESC', 'Ihre epsNetpay Konto Nummer.');
DEFINE ('_CFG_EPSNETPAY_MERCHANTPIN_NAME', 'H&auml;ndler PIN');
DEFINE ('_CFG_EPSNETPAY_MERCHANTPIN_DESC', 'Ihr H&auml;ndler PIN.');
DEFINE ('_CFG_EPSNETPAY_ACTIVATE_NAME', 'Aktivieren');
DEFINE ('_CFG_EPSNETPAY_ACTIVATE_DESC', 'Diese Bankverbidung anbieten.');
DEFINE ('_CFG_EPSNETPAY_ACCEPTVOK_NAME', 'VOK Akzeptieren');
DEFINE ('_CFG_EPSNETPAY_ACCEPTVOK_DESC', 'Aufgrund Ihres Kontotyps kann es sein, dass Sie nie den Status "OK", sondern immer nur "VOK" gemeldet bekommen. Bitte aktivieren Sie dieses Feld sollte dies der Fall sein.');

// Paysignet Settings
DEFINE ('_CFG_PAYSIGNET_MERCHANT_NAME', 'Merchant');
DEFINE ('_CFG_PAYSIGNET_MERCHANT_DESC', 'Your Merchant Name.');

// AlertPay Settings
DEFINE ('_CFG_ALERTPAY_MERCHANT_NAME', 'Merchant');
DEFINE ('_CFG_ALERTPAY_MERCHANT_DESC', 'Your Merchant Name.');
DEFINE ('_CFG_ALERTPAY_SECURITYCODE_NAME', 'Security Code');
DEFINE ('_CFG_ALERTPAY_SECURITYCODE_DESC', 'Your Security Code.');

?>