<?php
/**
 * @version $Id: germanf.php 16 2007-06-25 09:04:04Z mic $
 * @package AEC - Account Expiration Control / Subscription management for Joomla
 * @subpackage Processor languages
 * @author mic [ http://www.joomx.com ]
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

// Dont allow direct linking
defined( '_VALID_MOS' ) or die( 'Not Really ....' );

define( '_AEC_LANG_PROCESSOR', 1 );

// ################## new 0.12.4 (mic)
	// paypal
define( '_AEC_PROC_INFO_PP_LNAME',			'PayPal' );
define( '_AEC_PROC_INFO_PP_STMNT',			'Zahlungen mit PayPal - schnell, gratis und sicher!');
	// paypal subscription
define( '_AEC_PROC_INFO_PPS_LNAME',			'PayPal Abonnement' );
define( '_AEC_PROC_INFO_PPS_STMNT',			'Zahlungen mit PayPal - schnell, gratis und sicher!');
	// 2CheckOut
define( '_AEC_PROC_INFO_2CO_LNAME',			'2CheckOut' );
define( '_AEC_PROC_INFO_2CO_STMNT',			'Zahlung mit 2Checkout!' );

// (new 2007.07.16)
	// alertpay
define( '_AEC_PROC_INFO_AP_LNAME',			'AlertPay' );
define( '_AEC_PROC_INFO_AP_STMNT',			'Zahlungen mit AlertPay');
	// alertpay
define( '_AEC_PROC_INFO_WP_LNAME',			'WorldPay' );
define( '_AEC_PROC_INFO_WP_STMNT',			'Zahlungen mit WorldPay');

// end 2007.07.16

// END 0.12.4

define( '_DESCRIPTION_PAYPAL',				'Mit PayPal k&ouml;nnen Zahlungen an jeden gesendet werden, der eine Emailadresse besitzt. PayPal ist kostenlos und wird in das vorhandene Kreditkarten- und Bankkonto integriert.' );
define( '_DESCRIPTION_PAYPAL_SUBSCRIPTION', 'PayPal Abonnement ist der kostenlose PayPal Service f&uuml;r wiederkehrende Zahlungen mit dem <strong>das  Konto dem Abonnement entsprechend automatisch wiederkehrend belastet wird</strong>. Diese Einstellung kann jederzeit im PayPal Konto ge&auml;ndert werden.' );

define( '_DESCRIPTION_AUTHORIZE',			'Dieses Gateway erm&ouml;glicht es Interneth&auml;ndlern Onlinezahlungen mit Kreditkarte, Bankzahlung und E-Check sicher abzuwickeln.' );
define( '_DESCRIPTION_VIAKLIX',				'Bietet integriertes Kreditkarten und Lastschriftverfahren, elektronische &Uuml;berpr&uuml;fung and verwandte Softwareapplikationen an ...' );
define( '_DESCRIPTION_ALLOPASS',			'AlloPass, das europ&auml;ische Institut f&uuml;Micropayment, erlaubt Zahlungen via SMS, Telefon und Kreditkarten.' );
define( '_DESCRIPTION_2CHECKOUT',			'Sofortige Kreditkartenabwicklung f&uuml;r Interneth&auml;ndler.' );
define( '_DESCRIPTION_EPSNETPAY',			'EPS ist das einfache, sichere und kostenlose Zahlungssystem der &ouml;sterreichischen Banken f&uuml;r Eink&auml;ufe im Internet.' );

define( '_DESCRIPTION_ALERTPAY',			'Sicheres Geld mit AlertPay\'s Sicherheitsgrundsatz. Offen f&uuml;r alle Gesch&auml;ftsm&ouml;glichkeiten.' );

define( '_DESCRIPTION_WORLDPAY',			'Zahlungen f&uuml;r Internettransaktion mit Telefon, Fax, Email, Kredit- und Bankkarten, &Uuml;berweisungen und Ratenzahlungen. In jeder Sprache und fast allen W&auml;hrungen' );

// Generic Processor Names&Descs
define( '_CFG_PROCESSOR_TESTMODE_NAME',		'Test Modus?' );
define( '_CFG_PROCESSOR_TESTMODE_DESC',		'Um den Testmodus zu aktivieren, "Ja" ausw&auml;hlen. Einige der Gateways bieten einen Testmodus an mit welchem Zahlungen simuliert werden k&ouml;nnen. F&uuml;r einige Testumgebungen (z.B. PayPal) wird ein eigener Testzugang ben&ouml;tigt.' );
define( '_CFG_PROCESSOR_CURRENCY_NAME',		'W&auml;hrung' );
define( '_CFG_PROCESSOR_CURRENCY_DESC',		'W&auml;hrung f&uuml;r dieses Gateway' );
define( '_CFG_PROCESSOR_NAME_NAME',			'Angezeigter Name' );
define( '_CFG_PROCESSOR_NAME_DESC',			'Wie soll dieses Gateway genannt werden' );
define( '_CFG_PROCESSOR_DESC_NAME',			'Beschreibung anzeigen' );
define( '_CFG_PROCESSOR_DESC_DESC',			'Soll die Beschreibung f&uuml;r dieses Gateway angezeigt werden (z.B. auf der Nichteingeloggt-, Best&auml;tigungs- und Abschlussseite.' );
define( '_CFG_PROCESSOR_ITEM_NAME_NAME',	'Artikelbeschreibung' );
define( '_CFG_PROCESSOR_ITEM_NAME_DESC',	'Artikelbeschreibung dieses Services' );
define( '_CFG_PROCESSOR_ITEM_NAME_DEFAULT',	'Abonnement auf %s - Benutzer: %s (%s)' );
define( '_CFG_PROCESSOR_CUSTOMPARAMS_NAME', 'Custom Parameters');
define( '_CFG_PROCESSOR_CUSTOMPARAMS_DESC', 'Custom Parameters that the AEC should transmit to the Payment Processor on Checkout. Separated by linebreaks in the form of "parameter_name=parameter_value". The RewriteEngine works as specified below.');
define( '_CFG_PROCESSOR_PLAN_PARAMS_RECURRING_NAME', 'Recurring Payment');
define( '_CFG_PROCESSOR_PLAN_PARAMS_RECURRING_DESC', 'Choose what type of billing to use for this.');
define( '_CFG_PROCESSOR_LANGUAGE_NAME', 'Language');
define( '_CFG_PROCESSOR_LANGUAGE_DESC', 'Select one of the possible language settings for site that your user will see when issuing a payment.');
define( '_CFG_PROCESSOR_RECURRING_NAME', 'Recurring Payment');
define( '_CFG_PROCESSOR_RECURRING_DESC', 'Choose what type of billing to use for this.');

define( '_AEC_SELECT_RECURRING_NO', 'Non-Recurring');
define( '_AEC_SELECT_RECURRING_YES', 'Recurring');
define( '_AEC_SELECT_RECURRING_BOTH', 'Both');

// Generic Credit Card Form
define( '_AEC_CCFORM_CARDHOLDER_NAME', 'Card owner Name');
define( '_AEC_CCFORM_CARDHOLDER_DESC', 'The name of the credit card holder');
define( '_AEC_CCFORM_CARDNUMBER_NAME', 'Card Number');
define( '_AEC_CCFORM_CARDNUMBER_DESC', 'The number of your credit card');
define( '_AEC_CCFORM_EXPIRATIONYEAR_NAME', 'Expiration Year');
define( '_AEC_CCFORM_EXPIRATIONYEAR_DESC', 'The Year your card will expire');
define( '_AEC_CCFORM_EXPIRATIONMONTH_NAME', 'Expiration Month');
define( '_AEC_CCFORM_EXPIRATIONMONTH_DESC', 'The Month your card will expire');

// Generic Wire Transfer Form
define( '_AEC_WTFORM_ACCOUNTNAME_NAME', 'Konto Besitzer');
define( '_AEC_WTFORM_ACCOUNTNAME_DESC', 'Der Name des Kontobesitzers oder der Kontobesitzerin');
define( '_AEC_WTFORM_ACCOUNTNUMBER_NAME', 'Kontonummer');
define( '_AEC_WTFORM_ACCOUNTNUMBER_DESC', 'Die Kontonummer der Bankverbindung');
define( '_AEC_WTFORM_BANKNUMBER_NAME', 'BLZ');
define( '_AEC_WTFORM_BANKNUMBER_DESC', 'Die Bankleitzahl');
define( '_AEC_WTFORM_BANKNAME_NAME', 'Bank Name');
define( '_AEC_WTFORM_BANKNAME_DESC', 'Der Name der Bank');

// Paypal Settings
define( '_CFG_PAYPAL_BUSINESS_NAME',		'PayPal Gesch&auml;fts ID:' );
define( '_CFG_PAYPAL_BUSINESS_DESC',		'Die PayPal-ID um Gesch&auml;fte durchf&uuml;hren zu k&ouml;nnen' );
define( '_CFG_PAYPAL_CHECKBUSINESS_NAME',	'Sicherheits&uuml;berpr&uuml;fung:' );
define( '_CFG_PAYPAL_CHECKBUSINESS_DESC',	'Wenn aktiviert, wird bei der &Uuml;bermittlung der Daten an Paypal getestet, ob die Empf&auml;ngeremailadresse mit der angegebenen &uuml;bereinstimmt. Dies kann einen Fehler produzieren, falls bei Paypal mehr als eine Adresse genutzt wird. Wenn Benutzer also nicht aktiviert werden obwohl sie korrekt bezahlt haben, dann hier auf "Nein" stellen' );
define( '_CFG_PAYPAL_NO_SHIPPING_NAME', 	'Keine Lieferaddresse:');
define( '_CFG_PAYPAL_NO_SHIPPING_DESC', 	'Stellen Sie diese Einstellung auf "nein", falls sie m&ouml;chten, dass der Benutzer eine Lieferaddresse bei PayPal angibt.');
define( '_CFG_PAYPAL_ALTIPNURL_NAME',		'Alternative IPN Benachrichtigungsdomain:' );
define( '_CFG_PAYPAL_ALTIPNURL_DESC',		'Falls Server-Workload-Balancing eingesetzt wird (autom. Wechseln zwischen mehreren IP-Adressen) k&ouml;nnte es vorkommen dass PayPal die Verbindung abbricht w&auml;hrend die Best&auml;tigung (IPN) retour gesendet wird. Um diesen Fehler zu umgehen, kann z.B. eine neue Subdomain zu diesem Webserver/Webseite erstellt werden und das LOADBALANCING deaktivieren. Dann hier diese Subdomainadresse (z.B. http://subdomain.domain.com - KEIN Slash am Ende!) eintragen - PayPal wird dann nur an diese Adresse die IPN senden.<br /><strong>Feld leer lassen wenn keine Verwendung daf&uuml;r!</strong>' );
define( '_CFG_PAYPAL_LC_NAME',				'Sprache:' );
define( '_CFG_PAYPAL_LC_DESC',				'Eine der vorhandenen Sprachen ausw&auml;hlen, die Benutzer werden dann zu dieser PayPalseite mit der gew&auml;hlten Sprache beim Zahlungsvorgang umgeleitet.' );
define( '_CFG_PAYPAL_TAX_NAME',				'Steuer:' );
define( '_CFG_PAYPAL_TAX_DESC',				'Steuer in % angeben. Z.B bei einem Bruttobetrag von 120 Euro dann hier 20 angeben, es wird der Nettobetrag von 100 und die Steuer von 20 Euro ausgewiesen' );
define( '_CFG_PAYPAL_ACCEPTPENDINGECHECK_NAME', 'Accept Pending eCheck:');
define( '_CFG_PAYPAL_ACCEPTPENDINGECHECK_DESC', 'Accept Pending eChecks which usually take 4 days to clear. Set this to No to prevent eCheck fraud.');

define( '_CFG_PAYPAL_CBT_NAME', 'Continue Button');
define( '_CFG_PAYPAL_CBT_DESC', 'Sets the text for the Continue button on the PayPal "Payment Complete" page.');
define( '_CFG_PAYPAL_CN_NAME', 'Note Label');
define( '_CFG_PAYPAL_CN_DESC', 'The label above the note field.');
define( '_CFG_PAYPAL_CPP_HEADER_IMAGE_NAME', 'Header Image');
define( '_CFG_PAYPAL_CPP_HEADER_IMAGE_DESC', 'URL for the image at the top left of the payment page (the maximum image size being 750x90 pixels)');
define( '_CFG_PAYPAL_CPP_HEADERBACK_COLOR_NAME', 'Headerback Color');
define( '_CFG_PAYPAL_CPP_HEADERBACK_COLOR_DESC', 'Background color for the payment page header (6 character HTML hexadecimal color code in ASCII)');
define( '_CFG_PAYPAL_CPP_HEADERBORDER_COLOR_NAME', 'Headerborder Color');
define( '_CFG_PAYPAL_CPP_HEADERBORDER_COLOR_DESC', 'Border color for the payment page header (6 character HTML hexadecimal color code in ASCII)');
define( '_CFG_PAYPAL_CPP_PAYFLOW_COLOR_NAME', 'Payflow Color');
define( '_CFG_PAYPAL_CPP_PAYFLOW_COLOR_DESC', 'Background color for the payment page below the header (6 character HTML hexadecimal color code in ASCII)');
define( '_CFG_PAYPAL_CS_NAME', 'Background Tint');
define( '_CFG_PAYPAL_CS_DESC', 'The default - "No" - leaves the overall background color at white, setting it to "Yes" will change it to black');
define( '_CFG_PAYPAL_IMAGE_URL_NAME', 'Logo');
define( '_CFG_PAYPAL_IMAGE_URL_DESC', 'URL of the image displayed as your logo in the upperleft corner of PayPals pages (150x50 pixels)');
define( '_CFG_PAYPAL_PAGE_STYLE_NAME', 'Page Style');
define( '_CFG_PAYPAL_PAGE_STYLE_DESC', 'Sets the custom payment page style for payment pages. Reserved: "primary" - Always use the page style set as primary, "paypal" - Use the default PayPal style. Any other name has to refer to the page style you have defined in the PayPal Backend (alphanumeric ASCII lower 7-bit characters only, no underscore nor spaces)');

// Paypal Subscriptions Settings
define( '_CFG_PAYPAL_SUBSCRIPTION_BUSINESS_NAME', _CFG_PAYPAL_BUSINESS_NAME);
define( '_CFG_PAYPAL_SUBSCRIPTION_BUSINESS_DESC', _CFG_PAYPAL_BUSINESS_DESC);
define( '_CFG_PAYPAL_SUBSCRIPTION_CHECKBUSINESS_NAME', _CFG_PAYPAL_CHECKBUSINESS_NAME);
define( '_CFG_PAYPAL_SUBSCRIPTION_CHECKBUSINESS_DESC', _CFG_PAYPAL_CHECKBUSINESS_DESC);
define( '_CFG_PAYPAL_SUBSCRIPTION_NO_SHIPPING_NAME', _CFG_PAYPAL_NO_SHIPPING_NAME);
define( '_CFG_PAYPAL_SUBSCRIPTION_NO_SHIPPING_DESC', _CFG_PAYPAL_NO_SHIPPING_DESC);
define( '_CFG_PAYPAL_SUBSCRIPTION_ALTIPNURL_NAME', _CFG_PAYPAL_ALTIPNURL_NAME);
define( '_CFG_PAYPAL_SUBSCRIPTION_ALTIPNURL_DESC', _CFG_PAYPAL_ALTIPNURL_DESC);
define( '_CFG_PAYPAL_SUBSCRIPTION_LC_NAME', _CFG_PAYPAL_LC_NAME);
define( '_CFG_PAYPAL_SUBSCRIPTION_LC_DESC', _CFG_PAYPAL_LC_DESC);
define( '_CFG_PAYPAL_SUBSCRIPTION_TAX_NAME', _CFG_PAYPAL_TAX_NAME);
define( '_CFG_PAYPAL_SUBSCRIPTION_TAX_DESC', _CFG_PAYPAL_TAX_DESC);
define( '_PAYPAL_SUBSCRIPTION_CANCEL_INFO',		'Wenn das aktuelle Abonnement ge&auml;ndert werden soll, muss vorher das Aktuelle bei PayPal storniert werden!' );
define( '_CFG_PAYPAL_SUBSCRIPTION_ACCEPTPENDINGECHECK_NAME', _CFG_PAYPAL_ACCEPTPENDINGECHECK_NAME);
define( '_CFG_PAYPAL_SUBSCRIPTION_ACCEPTPENDINGECHECK_DESC', _CFG_PAYPAL_ACCEPTPENDINGECHECK_DESC);
define( '_CFG_PAYPAL_SUBSCRIPTION_SRT_NAME', 'Total Occurances');
define( '_CFG_PAYPAL_SUBSCRIPTION_SRT_DESC', 'If you want to limit the number of total subscription payments, you can do so with this field.');

define( '_CFG_PAYPAL_SUBSCRIPTION_CBT_NAME', _CFG_PAYPAL_CBT_NAME);
define( '_CFG_PAYPAL_SUBSCRIPTION_CBT_DESC', _CFG_PAYPAL_CBT_DESC);
define( '_CFG_PAYPAL_SUBSCRIPTION_CN_NAME', _CFG_PAYPAL_CN_NAME);
define( '_CFG_PAYPAL_SUBSCRIPTION_CN_DESC', _CFG_PAYPAL_CN_DESC);
define( '_CFG_PAYPAL_SUBSCRIPTION_CPP_HEADER_IMAGE_NAME', _CFG_PAYPAL_CPP_HEADER_IMAGE_NAME);
define( '_CFG_PAYPAL_SUBSCRIPTION_CPP_HEADER_IMAGE_DESC', _CFG_PAYPAL_CPP_HEADER_IMAGE_DESC);
define( '_CFG_PAYPAL_SUBSCRIPTION_CPP_HEADERBACK_COLOR_NAME', _CFG_PAYPAL_CPP_HEADERBACK_COLOR_NAME);
define( '_CFG_PAYPAL_SUBSCRIPTION_CPP_HEADERBACK_COLOR_DESC', _CFG_PAYPAL_CPP_HEADERBACK_COLOR_DESC);
define( '_CFG_PAYPAL_SUBSCRIPTION_CPP_HEADERBORDER_COLOR_NAME', _CFG_PAYPAL_CPP_HEADERBORDER_COLOR_NAME);
define( '_CFG_PAYPAL_SUBSCRIPTION_CPP_HEADERBORDER_COLOR_DESC', _CFG_PAYPAL_CPP_HEADERBORDER_COLOR_DESC);
define( '_CFG_PAYPAL_SUBSCRIPTION_CPP_PAYFLOW_COLOR_NAME', _CFG_PAYPAL_CPP_PAYFLOW_COLOR_NAME);
define( '_CFG_PAYPAL_SUBSCRIPTION_CPP_PAYFLOW_COLOR_DESC', _CFG_PAYPAL_CPP_PAYFLOW_COLOR_DESC);
define( '_CFG_PAYPAL_SUBSCRIPTION_CS_NAME', _CFG_PAYPAL_CS_NAME);
define( '_CFG_PAYPAL_SUBSCRIPTION_CS_DESC', _CFG_PAYPAL_CS_DESC);
define( '_CFG_PAYPAL_SUBSCRIPTION_IMAGE_URL_NAME', _CFG_PAYPAL_IMAGE_URL_NAME);
define( '_CFG_PAYPAL_SUBSCRIPTION_IMAGE_URL_DESC', _CFG_PAYPAL_IMAGE_URL_DESC);
define( '_CFG_PAYPAL_SUBSCRIPTION_PAGE_STYLE_NAME', _CFG_PAYPAL_PAGE_STYLE_NAME);
define( '_CFG_PAYPAL_SUBSCRIPTION_PAGE_STYLE_DESC', _CFG_PAYPAL_PAGE_STYLE_DESC);

// Transfer Settings
define( '_CFG_TRANSFER_TITLE',					'Manuell' );
define( '_CFG_TRANSFER_SUBTITLE',				'Nichtautomatische Zahlungen' );
define( '_CFG_TRANSFER_ENABLE_NAME',			'Nichtautomatische Zahlungen erlauben?' );
define( '_CFG_TRANSFER_ENABLE_DESC',			'Hier JA w&auml;hlen falls den Besuchern die M&ouml;glichkeit zur Bezahlung mittels Bankeinzug, -&uuml;berweisung, Geldanweisung, etc. angezeigt werden soll.' );
define( '_CFG_TRANSFER_INFO_NAME',				'Info manuelle/alternative Bezahlung:' );
define( '_CFG_TRANSFER_INFO_DESC',				'Text welcher Besuchern angezeigt wird falls diese keines der angebotenen automatischen Zahlungsschnittstellen/Gateways (PayPAl, usw.) verwenden m&ouml;chten. Nach der Registrierung werden solche Abonnenten automatisch als <strong>nicht aktiviert</strong> eingestuft, die Freigabe muss manuell im Backend erteilt werden (HTML.Tags sind erlaubt)' );

// Viaklix Settings
define( '_CFG_VIAKLIX_ACCOUNTID_NAME',			'Account ID' );
define( '_CFG_VIAKLIX_ACCOUNTID_DESC',			'Die Account ID bei viaKLIX.' );
define( '_CFG_VIAKLIX_USERID_NAME',				'User ID' );
define( '_CFG_VIAKLIX_USERID_DESC',				'Die User ID bei viaKLIX.' );
define( '_CFG_VIAKLIX_PIN_NAME',				'PIN' );
define( '_CFG_VIAKLIX_PIN_DESC',				'PIN des Terminals.' );

// Authorize.net Settings
define( '_CFG_AUTHORIZE_LOGIN_NAME',			'Login ID' );
define( '_CFG_AUTHORIZE_LOGIN_DESC',			'Die Login ID bei Authorize.net.' );
define( '_CFG_AUTHORIZE_TRANSACTION_KEY_NAME',	'Transaktions Schl&uuml;ssel' );
define( '_CFG_AUTHORIZE_TRANSACTION_KEY_DESC',	'Der Transaktions-Schl&uuml;ssel bei Authorize.net.' );
define( '_CFG_AUTHORIZE_TIMESTAMP_OFFSET_NAME', 'Timestamp offest');
define( '_CFG_AUTHORIZE_TIMESTAMP_OFFSET_DESC', 'If you get an Error 97 when trying to create a transaction, please <a href="http://developer.authorize.net/tools/responsecode97/">take a look at this</a>. It might be that you need to set a timestamp offset here.');
define( '_CFG_AUTHORIZE_X_LOGO_URL_NAME', 'Logo URL');
define( '_CFG_AUTHORIZE_X_LOGO_URL_DESC', 'This field is ideal for displaying a merchant logo on a page. The target of this URL will be displayed on the header of the Payment Form and Receipt Page.');
define( '_CFG_AUTHORIZE_X_BACKGROUND_URL_NAME', 'Background URL');
define( '_CFG_AUTHORIZE_X_BACKGROUND_URL_DESC', 'This field will allow the merchant to customize the background image of the Payment Form and Receipt Page. The target of the specified URL will be displayed as the background.');
define( '_CFG_AUTHORIZE_X_COLOR_BACKGROUND_NAME', 'Background Color');
define( '_CFG_AUTHORIZE_X_COLOR_BACKGROUND_DESC', 'Value in this field will set the background color for the Payment Form and Receipt Page.');
define( '_CFG_AUTHORIZE_X_COLOR_LINK_NAME', 'Color Link');
define( '_CFG_AUTHORIZE_X_COLOR_LINK_DESC', 'This field allows the color of the HTML links for the Payment Form and Receipt Page to be set to the value submitted in this field.');
define( '_CFG_AUTHORIZE_X_COLOR_TEXT_NAME', 'Color Text');
define( '_CFG_AUTHORIZE_X_COLOR_TEXT_DESC', 'This field allows the color of the text on the Payment Form and the Receipt Page to be set to the value submitted in this field.');
define( '_CFG_AUTHORIZE_X_HEADER_HTML_RECEIPT_NAME', 'Header Receipt Page');
define( '_CFG_AUTHORIZE_X_HEADER_HTML_RECEIPT_DESC', 'The text contained in this field will be displayed at the top of the Receipt Page.');
define( '_CFG_AUTHORIZE_X_FOOTER_HTML_RECEIPT_NAME', 'Footer Receipt Page');
define( '_CFG_AUTHORIZE_X_FOOTER_HTML_RECEIPT_DESC', 'The text contained in this field will be displayed at the bottom of the Receipt Page.');

// Allopass Settings
define( '_CFG_ALLOPASS_SITEID_NAME',			'SITE_ID' );
define( '_CFG_ALLOPASS_SITEID_DESC',			'Die SITE_ID bei AlloPass.' );
define( '_CFG_ALLOPASS_DOCID_NAME',				'DOC_ID' );
define( '_CFG_ALLOPASS_DOCID_DESC',				'Die DOC_ID bei AlloPass.' );
define( '_CFG_ALLOPASS_AUTH_NAME',				'AUTH' );
define( '_CFG_ALLOPASS_AUTH_DESC',				'AUTH bei AlloPass.' );

// 2Checkout Settings
define( '_CFG_2CHECKOUT_SID_NAME',				'SID' );
define( '_CFG_2CHECKOUT_SID_DESC',				'Die 2checkout Account-Nummer.' );
define( '_CFG_2CHECKOUT_SECRET_WORD_NAME',		'Geheimes Wort' );
define( '_CFG_2CHECKOUT_SECRET_WORD_DESC',		'Dasselbe geheime Wort, welches auf der "Look and Feel" Seite angegeben wurde.' );
define( '_CFG_2CHECKOUT_INFO_NAME',				'WICHTIG!' );
define( '_CFG_2CHECKOUT_INFO_DESC',				'Auf der 2Checkout Account Homepage mus&szlig noch eine Einstellung vorgenommen werden! Dazu auf der 2CO Homepage in die Kategorie "Helpful Links" gehen und dort auf den "Look and Feel" Link klicken. &Auml;ndern das Feld "Approved URL" mit der URL "http://<strong>yoursite.com</strong>/index.php?option=com_acctexp&task=2conotification". Ersetzen "yoursite.com" mit der eigenen Domain.' );
define( '_CFG_2CHECKOUT_ALT2COURL_NAME',		'Alternativ-Url' );
define( '_CFG_2CHECKOUT_ALT2COURL_DESC',		'Alternative Url bei Problemen mit 2Checkout' );

// WorldPay Settings
define( '_CFG_WORLDPAY_LONGNAME',		'WorldPay' );
define( '_CFG_WORLDPAY_STATEMENT',		'Payments with WorldPay' );
define( '_CFG_WORLDPAY_DESCRIPTION',	'Accept payments on the internet, by phone, fax or mail. Credit and debit cards, bank transfers and instalments. In any language and most currencies' );
define( '_CFG_WORLDPAY_INSTID_NAME', 	'instId');
define( '_CFG_WORLDPAY_INSTID_DESC', 	'Your WorldPay Installation Id.');
define( '_CFG_WORLDPAY_INFO_NAME', 	'Callback URL');
define( '_CFG_WORLDPAY_INFO_DESC', 	'You need to set the Callback URL in the Configuration Options of your installation on the Customer Management System in your Worldpay Account... the url is:<br />http://[YOUR JOOMLA LOCATION]/index.php?option=com_acctexp&task=worldpaynotification<br />Thats it. More detailed information <a href="http://support.worldpay.com/kb/integration_guides/junior/integration/help/payment_response/sjig_5127.html">here</a>');
define( '_CFG_WORLDPAY_CALLBACKPW_NAME', 	'Callback Password');
define( '_CFG_WORLDPAY_CALLBACKPW_DESC', 	'Please set a Callback Password in your Worldpay Account and enter it here once again. With this, the payment notifications can .');

// WorldPay Futurepay Settings
define( '_CFG_WORLDPAY_FUTUREPAY_LONGNAME',		'WorldPay Futurepay' );
define( '_CFG_WORLDPAY_FUTUREPAY_STATEMENT',	'Recurring Payments with WorldPay' );
define( '_CFG_WORLDPAY_FUTUREPAY_DESCRIPTION',	'Accepts payments on the internet, by phone, fax or mail. Credit and debit cards, bank transfers and instalments. In any language and most currencies' );
define( '_CFG_WORLDPAY_FUTUREPAY_INSTID_NAME', 	'instId');
define( '_CFG_WORLDPAY_FUTUREPAY_INSTID_DESC', 	'Your WorldPay Installation Id.');
define( '_CFG_WORLDPAY_FUTUREPAY_INFO_NAME', 	'Callback URL');
define( '_CFG_WORLDPAY_FUTUREPAY_INFO_DESC', 	'You need to set the Callback URL in the Configuration Options of your installation on the Customer Management System in your Worldpay Account... the url is:<br />http://[YOUR JOOMLA LOCATION]/index.php?option=com_acctexp&task=worldpay_futurepaynotification<br />Thats it. More detailed information <a href="http://support.worldpay.com/kb/integration_guides/junior/integration/help/payment_response/sjig_5127.html">here</a>');
define( '_CFG_WORLDPAY_FUTUREPAY_CALLBACKPW_NAME', 	'Callback Password');
define( '_CFG_WORLDPAY_FUTUREPAY_CALLBACKPW_DESC', 	'Please set a Callback Password in your Worldpay Account and enter it here once again. With this, the payment notifications can .');

// epsNetpay Settings
define( '_CFG_EPSNETPAY_MERCHANTID_NAME',		'H&auml;ndler ID' );
define( '_CFG_EPSNETPAY_MERCHANTID_DESC',		'Die epsNetpay Konto Nummer.' );
define( '_CFG_EPSNETPAY_MERCHANTPIN_NAME',		'H&auml;ndler PIN' );
define( '_CFG_EPSNETPAY_MERCHANTPIN_DESC',		'Die H&auml;ndler PIN.' );
define( '_CFG_EPSNETPAY_ACTIVATE_NAME',			'Aktivieren' );
define( '_CFG_EPSNETPAY_ACTIVATE_DESC',			'Diese Bankverbidung anbieten.' );
define( '_CFG_EPSNETPAY_ACCEPTVOK_NAME',		'VOK Akzeptieren' );
define( '_CFG_EPSNETPAY_ACCEPTVOK_DESC',		'Aufgrund des Kontotyps kann es sein, dass nie den Status "OK" sondern immer nur "VOK" gemeldet wird. In so einem Fall dieses Feld aktivieren.' );

// Paysignet Settings
define( '_CFG_PAYSIGNET_MERCHANT_NAME',			'H&auml;ndlername' );
define( '_CFG_PAYSIGNET_MERCHANT_DESC',			'Der H&auml;ndlername' );

// AlertPay Settings
define( '_CFG_ALERTPAY_MERCHANT_NAME',			'H&auml;ndler' );
define( '_CFG_ALERTPAY_MERCHANT_DESC',			'Der H&auml;ndlername' );
define( '_CFG_ALERTPAY_SECURITYCODE_NAME',		'Sicherheitscode' );
define( '_CFG_ALERTPAY_SECURITYCODE_DESC',		'Der Sicherheitscode' );

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

// eWayXML Settings
define( '_CFG_EWAYXML_LONGNAME', 'eWayXML');
define( '_CFG_EWAYXML_STATEMENT', 'Make payments with eWAY Shared Payment Solution!');
define( '_CFG_EWAYXML_DESCRIPTION', 'eWAY is the easiest and most affordable payment gateway in Australia. Process credit card payments via eWAY\'s own secure Shared Payment Page in real-time.');
define( '_CFG_EWAYXML_CUSTID_NAME', 'Customer ID');
define( '_CFG_EWAYXML_CUSTID_DESC', 'Your Customer ID.');
define( '_CFG_EWAYXML_AUTOREDIRECT_NAME', 'Autoredirect');
define( '_CFG_EWAYXML_AUTOREDIRECT_DESC', 'Automatic Redirect for eWay Transaction');
define( '_CFG_EWAYXML_SITETITLE_NAME', 'Site Title');
define( '_CFG_EWAYXML_SITETITLE_DESC', 'The Site Title of the eWay Transaction');

// MoneyProxy Settings
define( '_CFG_MONEYPROXY_LONGNAME', 'MoneyProxy');
define( '_CFG_MONEYPROXY_STATEMENT', 'Make Payments in different digital currencies with Money Proxy!');
define( '_CFG_MONEYPROXY_DESCRIPTION', 'Accept payments on a website in different digital currencies with a single merchant account.');
define( '_CFG_MONEYPROXY_MERCHANT_ID_NAME', 'Merchant ID');
define( '_CFG_MONEYPROXY_MERCHANT_ID_DESC', 'Your merchant identifier at MoneyProxy.');
define( '_CFG_MONEYPROXY_FORCE_CLIENT_RECEIPT_NAME', 'Force Receipt');
define( '_CFG_MONEYPROXY_FORCE_CLIENT_RECEIPT_DESC', 'By setting this parameter to "Yes", it forces Money Proxy to ask an e-mail address where to send a receipt of the payment. By default, the customer can skip the receipt without entering any e-mail address.');
define( '_CFG_MONEYPROXY_SECRET_KEY_NAME', 'Site Title');
define( '_CFG_MONEYPROXY_SECRET_KEY_DESC', 'Your secret key at MoneyProxy.');
define( '_CFG_MONEYPROXY_SUGGESTEDMEMO_NAME', 'Suggested Memo');
define( '_CFG_MONEYPROXY_SUGGESTEDMEMO_DESC', 'This parameter is used to pre-fill the memo field for many payment system. Unfortunately, it is possible that some payment systems do not support this feature. Maximum of 40 characters.');
define( '_CFG_MONEYPROXY_PAYMENT_ID_NAME', 'Payment ID');
define( '_CFG_MONEYPROXY_PAYMENT_ID_DESC', 'The merchant can use this field to track the payment when the status URL is called. It can be up to 10 digits with only letters and numbers (0-9a-zA-Z). You can use Rewrite tags here.');

// Offline Payment
define( '_CFG_OFFLINE_PAYMENT_LONGNAME', 'Offline Zahlungen');
define( '_CFG_OFFLINE_PAYMENT_STATEMENT', 'W&auml;hlen sie diese Option, um auf anderem Wege als &uuml;ber das Internet zu zahlen');
define( '_CFG_OFFLINE_PAYMENT_DESCRIPTION', 'W&auml;hlen sie diese Option, um auf anderem Wege als &uuml;ber das Internet zu zahlen');
define( '_CFG_OFFLINE_PAYMENT_INFO_NAME', 'Info');
define( '_CFG_OFFLINE_PAYMENT_INFO_DESC', 'Die Information, die dem Benutzer auf der Checkout-Seite pr&auml;sentiert wird');
define( '_CFG_OFFLINE_PAYMENT_WAITINGPLAN_NAME', 'Warte-Plan');
define( '_CFG_OFFLINE_PAYMENT_WAITINGPLAN_DESC', 'Mit dieser Option wird der Benutzer einem Plan zugewiesen w&auml;hrend die Zahlung unterwegs ist');

// Verotel
define( '_CFG_VEROTEL_LONGNAME', 'Verotel');
define( '_CFG_VEROTEL_STATEMENT', 'Use Verotel: Putting Trust in Global Payments');
define( '_CFG_VEROTEL_DESCRIPTION', 'Verotel offers a range of billing methods for your website, including VISA/MasterCard/JCB, Chinese debit, Direct Debit in European countries, pay per phonecall, pay per minute, SMS billing and more!');
define( '_CFG_VEROTEL_MERCHANTID_NAME', 'Merchant ID');
define( '_CFG_VEROTEL_MERCHANTID_DESC', 'Your merchant identifier at Verotel.');
define( '_CFG_VEROTEL_SITEID_NAME', 'Site ID');
define( '_CFG_VEROTEL_SITEID_DESC', 'Your site identifier for this website.');
define( '_CFG_VEROTEL_RESELLERID_NAME', 'Reseller ID');
define( '_CFG_VEROTEL_RESELLERID_DESC', 'Your Reseller ID (if any).');
define( '_CFG_VEROTEL_SECRETCODE_NAME', 'Secret Code');
define( '_CFG_VEROTEL_SECRETCODE_DESC', 'Your secret Verotel code.');
define( '_CFG_VEROTEL_USE_TICKETSCLUB_NAME', 'Tickets Club');
define( '_CFG_VEROTEL_USE_TICKETSCLUB_DESC', 'Use Tickets Club?');
define( '_CFG_VEROTEL_PLAN_PARAMS_VEROTEL_PRODUCT_NAME', 'Product Name');
define( '_CFG_VEROTEL_PLAN_PARAMS_VEROTEL_PRODUCT_DESC', 'The Product Name identifying this plan to Verotel');

// Cybermut
define( '_CFG_CYBERMUT_LONGNAME', 'Cybermut');
define( '_CFG_CYBERMUT_STATEMENT', 'Cybermut - Le groupe Cr&eacute;dit Mutuel');
define( '_CFG_CYBERMUT_DESCRIPTION', 'Cybermut - Le groupe Cr&eacute;dit Mutuel');
define( '_CFG_CYBERMUT_TPE_NAME', 'TPE');
define( '_CFG_CYBERMUT_TPE_DESC', 'No TPE &racute; 7 chiffres, fourni par la banque');
define( '_CFG_CYBERMUT_VER_NAME', 'Version');
define( '_CFG_CYBERMUT_VER_DESC', 'The Protocol Version - leave at "1.2open" if you don\'t know what this is');
define( '_CFG_CYBERMUT_SOC_NAME', 'Code Soci&eacute;t&eacute;');
define( '_CFG_CYBERMUT_SOC_DESC', 'Code Soci&eacute;t&eacute;, fourni par la banque');
define( '_CFG_CYBERMUT_PASS_NAME', 'pass');
define( '_CFG_CYBERMUT_PASS_DESC', 'Valeur de la variable pass');
define( '_CFG_CYBERMUT_KEY_NAME', 'Cl&eacute;');
define( '_CFG_CYBERMUT_KEY_DESC', 'cl&eacute; info');
define( '_CFG_CYBERMUT_SERVER_NAME', 'Serveur bancair');
define( '_CFG_CYBERMUT_SERVER_DESC', 'Choissisez votre banque');
define( '_CFG_CYBERMUT_LANGUAGE_NAME', 'Language:');
define( '_CFG_CYBERMUT_LANGUAGE_DESC', 'Select one of the possible language settings for the paypal site that your user will see when issuing a payment.');

// Authorize.net ARB
define( '_CFG_AUTHORIZE_ARB_LONGNAME', 'Authorize.net ARB');
define( '_CFG_AUTHORIZE_ARB_STATEMENT', 'Make recurring payments with Authorize.net');
define( '_CFG_AUTHORIZE_ARB_DESCRIPTION', 'Make recurring payments with Authorize.net');
define( '_CFG_AUTHORIZE_ARB_LOGIN_NAME', 'API Login ID');
define( '_CFG_AUTHORIZE_ARB_LOGIN_DESC', 'Your API Login ID on Authorize.net.');
define( '_CFG_AUTHORIZE_ARB_TRANSACTION_KEY_NAME', 'Transaction Key');
define( '_CFG_AUTHORIZE_ARB_TRANSACTION_KEY_DESC', 'Your Transaction Key on Authorize.net.');
define( '_CFG_AUTHORIZE_ARB_PROMPTADDRESS_NAME', 'Prompt for Address');
define( '_CFG_AUTHORIZE_ARB_PROMPTADDRESS_DESC', 'Ask the user to put in an Address with the Billing Name.');
define( '_CFG_AUTHORIZE_ARB_TOTALOCCURRENCES_NAME', 'Total Occurances');
define( '_CFG_AUTHORIZE_ARB_TOTALOCCURRENCES_DESC', 'Authorize.net requires that you set the total amount of occurances of a payment. Make sure the total lifespan of a subscription does not exceed three years');
define( '_CFG_AUTHORIZE_ARB_TRIALOCCURRENCES_NAME', 'Trial Occurances');
define( '_CFG_AUTHORIZE_ARB_TRIALOCCURRENCES_DESC', 'Specify the amount of trial periods that are granted to the user. This amount will be substracted from the total occurances.');
define( '_AEC_AUTHORIZE_ARB_PARAMS_BILLFIRSTNAME_NAME', 'First Name');
define( '_AEC_AUTHORIZE_ARB_PARAMS_BILLLASTNAME_NAME', 'Last Name');
define( '_AEC_AUTHORIZE_ARB_PARAMS_BILLADDRESS_NAME', 'Address');
define( '_AEC_AUTHORIZE_ARB_PARAMS_BILLCITY_NAME', 'City');
define( '_AEC_AUTHORIZE_ARB_PARAMS_BILLSTATE_NAME', 'State');
define( '_AEC_AUTHORIZE_ARB_PARAMS_BILLZIP_NAME', 'Zip');
define( '_AEC_AUTHORIZE_ARB_PARAMS_BILLCOUNTRY_NAME', 'Country');
define( '_CFG_AUTHORIZE_ARB_USESILENTPOSTRESPONSE_NAME', 'Use Silent Post Response');
define( '_CFG_AUTHORIZE_ARB_USESILENTPOSTRESPONSE_DESC', 'Please read explanation below');
define( '_CFG_AUTHORIZE_ARB_SILENTPOST_INFO_NAME', 'Silent Postback');
define( '_CFG_AUTHORIZE_ARB_SILENTPOST_INFO_DESC', 'When a recurring payment is set up with ARB, the AEC normally applies a multiplicated subscription period accordig to the Total Occurances. This way, the user will stay active throughout the subscription until it runs out or is cancelled. However, this also means that you would have to check for unpaid bills and manually deactivate the subscriptions if such a thing occurs. The other option is to use the Silent Postback which sends notifications for each subsequent payment that was successful. This in turn triggers the AEC to activate the user for another term. Please consult <a href="http://www.authorize.net/support/Merchant/Integration_Settings/Receipt_Page_Options.htm">this page</a> to find out how to set up the Silent Post Url. Enter http://[YOUR JOOMLA LOCATION]/index.php?option=com_acctexp&task=authorize_arbnotification as the Url.');

// CCBill
define( '_CFG_CCBILL_LONGNAME', 'CCBill');
define( '_CFG_CCBILL_STATEMENT', 'Make payments with CCBill!');
define( '_CFG_CCBILL_DESCRIPTION', 'CCBill');
define( '_CFG_CCBILL_CLIENTACCNUM_NAME', 'Client Account');
define( '_CFG_CCBILL_CLIENTACCNUM_DESC', 'Your CCBill Client Account Number');
define( '_CFG_CCBILL_CLIENTSUBACC_NAME', 'Client SubAccount');
define( '_CFG_CCBILL_CLIENTSUBACC_DESC', 'Your CCBill Client Sub Account Number');
define( '_CFG_CCBILL_SECRETWORD_NAME', 'Secret Word');
define( '_CFG_CCBILL_SECRETWORD_DESC', 'Your secret word used to encrypt and protect transactions');
define( '_CFG_CCBILL_FORMNAME_NAME', 'Form ID');
define( '_CFG_CCBILL_FORMNAME_DESC', 'The CCBill layout you wish to use (look at the HTML form downloaded from CCBILL)');
define( '_CFG_CCBILL_INFO_NAME', 'Postback URL');
define( '_CFG_CCBILL_INFO_DESC', 'You need to remember to set the \'postback\' url in the CCBILL control panel... for both approves and declines this should be...<br />http://[YOUR JOOMLA LOCATION]/index.php?option=com_acctexp&task=ccbillnotification<br />Thats it.');
define( '_CFG_CCBILL_PLAN_PARAMS_ALLOWEDTYPES_NAME', 'Allowed types');
define( '_CFG_CCBILL_PLAN_PARAMS_ALLOWEDTYPES_DESC', 'insert the payment options that the user is supposed to have after being led on to CCBill (refer to your CCBill account for the product IDs you have set up)');
define( '_CFG_CCBILL_DATALINK_USERNAME_NAME', 'Datalink Username');
define( '_CFG_CCBILL_DATALINK_USERNAME_DESC', 'If you want to use Recurring Billing, you need to supply a Datalink Username here. Also remember to set the "recurring" switch in the payment plans that are supposed to have this functionality.');

// iDeal Basic
define( '_CFG_IDEAL_BASIC_LONGNAME', 'iDeal');
define( '_CFG_IDEAL_BASIC_STATEMENT', 'Make payments with iDeal');
define( '_CFG_IDEAL_BASIC_DESCRIPTION', 'De veilige manier van betalen op internet.');
define( '_CFG_IDEAL_BASIC_MERCHANTID_NAME', 'Merchant ID');
define( '_CFG_IDEAL_BASIC_MERCHANTID_DESC', 'Your Merchant ID');
define( '_CFG_IDEAL_BASIC_TESTMODESTAGE_NAME', 'Testmode Stage');
define( '_CFG_IDEAL_BASIC_TESTMODESTAGE_DESC', 'Which of the tests (1-7) do you want to conduct?');
define( '_CFG_IDEAL_BASIC_SUBID_NAME', 'Testmode Stage');
define( '_CFG_IDEAL_BASIC_SUBID_DESC', 'Set to 0 unless you have specific reasons not to');
define( '_CFG_IDEAL_BASIC_KEY_NAME', 'Key');
define( '_CFG_IDEAL_BASIC_KEY_DESC', 'Your secret key');
define( '_CFG_IDEAL_BASIC_DESCRIPTION_NAME', 'Item Description');
define( '_CFG_IDEAL_BASIC_DESCRIPTION_DESC', 'The Item description that is shown on the invoice - max 32 characters!');

// Authorize.net AIM
define( '_CFG_AUTHORIZE_AIM_LONGNAME', 'Authorize.net AIM');
define( '_CFG_AUTHORIZE_AIM_STATEMENT', 'Make on-site CreditCard payments with Authorize.net');
define( '_CFG_AUTHORIZE_AIM_DESCRIPTION', 'Make on-site CreditCard payments with Authorize.net');
define( '_CFG_AUTHORIZE_AIM_LOGIN_NAME', 'API Login ID');
define( '_CFG_AUTHORIZE_AIM_LOGIN_DESC', 'Your API Login ID on Authorize.net.');
define( '_CFG_AUTHORIZE_AIM_TRANSACTION_KEY_NAME', 'Transaction Key');
define( '_CFG_AUTHORIZE_AIM_TRANSACTION_KEY_DESC', 'Your Transaction Key on Authorize.net.');
define( '_CFG_AUTHORIZE_AIM_PROMPTADDRESS_NAME', 'Prompt for Address');
define( '_CFG_AUTHORIZE_AIM_PROMPTADDRESS_DESC', 'Ask the user to put in an Address with the Billing Name.');
define( '_CFG_AUTHORIZE_AIM_PROMPTZIPONLY_NAME', 'Prompt for Zip only');
define( '_CFG_AUTHORIZE_AIM_PROMPTZIPONLY_DESC', 'Ask the user to put in a Zip code only.');
define( '_AEC_AUTHORIZE_AIM_PARAMS_BILLFIRSTNAME_NAME', 'First Name');
define( '_AEC_AUTHORIZE_AIM_PARAMS_BILLLASTNAME_NAME', 'Last Name');
define( '_AEC_AUTHORIZE_AIM_PARAMS_BILLADDRESS_NAME', 'Address');
define( '_AEC_AUTHORIZE_AIM_PARAMS_BILLCITY_NAME', 'City');
define( '_AEC_AUTHORIZE_AIM_PARAMS_BILLSTATE_NAME', 'State');
define( '_AEC_AUTHORIZE_AIM_PARAMS_BILLZIP_NAME', 'Zip');
define( '_AEC_AUTHORIZE_AIM_PARAMS_BILLCOUNTRY_NAME', 'Country');

// iPayment Silent
define( '_CFG_IPAYMENT_SILENT_LONGNAME', 'iPayment');
define( '_CFG_IPAYMENT_SILENT_STATEMENT', 'Kreditkarten-Zahlungen mit iPayment');
define( '_CFG_IPAYMENT_SILENT_DESCRIPTION', 'Kreditkarten-Zahlungen mit iPayment');
define( '_CFG_IPAYMENT_SILENT_FAKE_ACCOUNT_NAME', 'Falscher Account');
define( '_CFG_IPAYMENT_SILENT_FAKE_ACCOUNT_DESC', 'Einen falschen Account (99999) zum Testen benutzen.');
define( '_CFG_IPAYMENT_SILENT_USER_ID_NAME', 'User Id');
define( '_CFG_IPAYMENT_SILENT_USER_ID_DESC', 'Die User Id Ihres iPayment Accounts.');
define( '_CFG_IPAYMENT_SILENT_PASSWORD_NAME', 'Passwort');
define( '_CFG_IPAYMENT_SILENT_PASSWORD_DESC', 'Das Passwort Ihres iPayment Accounts.');
define( '_CFG_IPAYMENT_SILENT_PROMPTADDRESS_NAME', 'Addressdaten abfragen');
define( '_CFG_IPAYMENT_SILENT_PROMPTADDRESS_DESC', 'Separat zu Rechnungsvor- und Nachnahme auch Rechnungsadresse abfragen.');
define( '_AEC_IPAYMENT_SILENT_PARAMS_BILLINFO_NAME', 'Rechnungs-Details');
define( '_AEC_IPAYMENT_SILENT_PARAMS_BILLINFO_DESC', '');
define( '_AEC_IPAYMENT_SILENT_PARAMS_BILLINFO_ELV_NAME', 'Lastschrift');
define( '_AEC_IPAYMENT_SILENT_PARAMS_BILLINFO_ELV_DESC', 'Ihre Kontoverbindung für das Lastschriftverfahren');
define( '_AEC_IPAYMENT_SILENT_PARAMS_BILLINFO_CC_NAME', 'Kreditkarte');
define( '_AEC_IPAYMENT_SILENT_PARAMS_BILLINFO_CC_DESC', 'Anstatt der Kontoverbindung k&ouml;nnen Sie auch mit Kreditkarte bezahlen');
define( '_AEC_IPAYMENT_SILENT_PARAMS_BILLFIRSTNAME_NAME', 'Vorname');
define( '_AEC_IPAYMENT_SILENT_PARAMS_BILLLASTNAME_NAME', 'Nachname');
define( '_AEC_IPAYMENT_SILENT_PARAMS_BILLADDRESS_NAME', 'Adresse');
define( '_AEC_IPAYMENT_SILENT_PARAMS_BILLCITY_NAME', 'Stadt');
define( '_AEC_IPAYMENT_SILENT_PARAMS_BILLSTATE_NAME', 'Bundesland');
define( '_AEC_IPAYMENT_SILENT_PARAMS_BILLZIP_NAME', 'PLZ');
define( '_AEC_IPAYMENT_SILENT_PARAMS_BILLCOUNTRY_NAME', 'Land');
define( '_AEC_IPAYMENT_SILENT_PARAMS_BILLTELEPHONE_NAME', 'Telefonnummer');

// Paysite Cash
define( '_CFG_PAYSITE_CASH_LONGNAME', 'Paysite Cash');
define( '_CFG_PAYSITE_CASH_STATEMENT', 'Make payments with Paysite Cash');
define( '_CFG_PAYSITE_CASH_DESCRIPTION', 'Make payments with Paysite Cash');
define( '_CFG_PAYSITE_CASH_SITEID_NAME', 'Site ID');
define( '_CFG_PAYSITE_CASH_SITEID_DESC', 'Site number in Editor interface Pbiz');
define( '_CFG_PAYSITE_CASH_SECRET_NAME', 'Secret Word');
define( '_CFG_PAYSITE_CASH_SECRET_DESC', 'Choose a secret word or string that will internally secure the data transmitted and received from fraud.');

?>