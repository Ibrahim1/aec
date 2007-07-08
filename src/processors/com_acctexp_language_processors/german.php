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
// END 0.12.4

define( '_DESCRIPTION_PAYPAL',				'Mit PayPal k&ouml;nnen Zahlungen an jeden gesendet werden, der eine Emailadresse besitzt. PayPal ist kostenlos und wird in das vorhandene Kreditkarten- und Bankkonto integriert.' );

define( '_DESCRIPTION_PAYPAL_SUBSCRIPTION', 'PayPal Abonnement ist der kostenlose PayPal Service f&uuml;r wiederkehrende Zahlungen mit dem <strong>das  Konto dem Abonnement entsprechend automatisch wiederkehrend belastet wird</strong>. Diese Einstellung kann jederzeit im PayPal Konto ge&auml;ndert werden.' );

define( '_DESCRIPTION_AUTHORIZE',			'Dieses Gateway erm&ouml;glicht es Interneth&auml;ndlern Onlinezahlungen mit Kreditkarte, Bankzahlung und E-Check sicher abzuwickeln.' );
define( '_DESCRIPTION_VIAKLIX',				'Bietet integriertes Kreditkarten und Lastschriftverfahren, elektronische &Uuml;berpr&uuml;fung and verwandte Softwareapplikationen an ...' );
define( '_DESCRIPTION_ALLOPASS',			'AlloPass, das europ&auml;ische Institut f&uuml;Micropayment, erlaubt Zahlungen via SMS, Telefon und Kreditkarten.' );
define( '_DESCRIPTION_2CHECKOUT',			'Sofortige Kreditkartenabwicklung f&uuml;r Interneth&auml;ndler.' );
define( '_DESCRIPTION_EPSNETPAY',			'EPS ist das einfache, sichere und kostenlose Zahlungssystem der &ouml;sterreichischen Banken f&uuml;r Eink&auml;ufe im Internet.' );
define( '_DESCRIPTION_ALERTPAY',			'Sicheres Geld mit AlertPay\'s Sicherheitsgrundsatz. Offen f&uuml;r alle Gesch&auml;ftsm&ouml;glichkeiten.' );

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

// Paypal Subscriptions Settings
define( '_CFG_PAYPAL_SUBSCRIPTION_BUSINESS_NAME',	'Gesch&auml;fts ID:' );
define( '_CFG_PAYPAL_SUBSCRIPTION_BUSINESS_DESC',	'Die Gesch&auml;fts-ID (= Email) bei PayPal:' );
define( '_CFG_PAYPAL_SUBSCRIPTION_CHECKBUSINESS_NAME', 'Sicherheits&uuml;berpr&uuml;fung:' );
define( '_CFG_PAYPAL_SUBSCRIPTION_CHECKBUSINESS_DESC', 'Wenn aktiviert, wird bei der &Uuml;bermittlung der Daten an Paypal getestet, ob die Empf&auml;ngeremailadresse mit der Angegebenen &uuml;bereinstimmt. Dies kann einen Fehler produzieren, falls bei Paypal mehr als eine Adresse genutzt wird. Wenn Benutzer also nicht aktiviert werden obwohl sie korrekt bezahlt haben, hier auf "Nein" stellen' );
define( '_CFG_PAYPAL_SUBSCRIPTION_NO_SHIPPING_NAME', 'Keine Lieferaddresse:');
define( '_CFG_PAYPAL_SUBSCRIPTION_NO_SHIPPING_DESC', 'Stellen Sie diese Einstellung auf "nein", falls sie m&ouml;chten, dass der Benutzer eine Lieferaddresse bei PayPal angibt.');
define( '_CFG_PAYPAL_SUBSCRIPTION_ALTIPNURL_NAME', 'Alternative IPN Benachrichtigungsdomain:' );
define( '_CFG_PAYPAL_SUBSCRIPTION_ALTIPNURL_DESC', 'Falls Server-Workload-Balancing eingesetzt wird (autom. Wechseln zwischen mehreren IP-Adressen) k&ouml;nnte es vorkommen dass PayPal die Verbindung abbricht w&auml;hrend die Best&auml;tigung (IPN) retour gesendet wird. Um diesen Fehler zu umgehen, kann z.B. eine neue Subdomain zu diesem Webserver/Webseite erstellt werden und das LOADBALANCING deaktivieren. Dann hier diese Subdomainadresse (z.B. http://subdomain.domain.com - KEIN Slash am Ende!) eintragen - PayPal wird dann nur an diese Adresse die IPN senden.<br /><strong>Feld leer lassen wenn keine Verwendung daf&uuml;r!</strong>' );
define( '_CFG_PAYPAL_SUBSCRIPTION_LC_NAME', 	'Sprache:' );
define( '_CFG_PAYPAL_SUBSCRIPTION_LC_DESC',		'Eine der vorhandenen Sprachen ausw&auml;hlen, die Benutzer werden dann zu dieser PayPalseite mit der gew&auml;hlten Sprache beim Zahlungsvorgang umgeleitet.' );
define( '_CFG_PAYPAL_SUBSCRIPTION_TAX_NAME',	'Steuer:' );
define( '_CFG_PAYPAL_SUBSCRIPTION_TAX_DESC',	'Steuer in % angeben. Z.B bei einem Bruttobetrag von 120 Euro dann hier 20 angeben, es wird der Nettobetrag von 100 und die Steuer von 20 Euro ausgewiesen' );
define( '_PAYPAL_SUBSCRIPTION_CANCEL_INFO',		'Wenn das aktuelle Abonnement ge&auml;ndert werden soll, muss vorher das Aktuelle bei PayPal storniert werden!' );

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
define( '_CFG_WORLDPAY_INSTID_NAME',			'instID' );
define( '_CFG_WORLDPAY_INSTID_DESC',			'Die WorldPay Installations ID.' );

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
?>