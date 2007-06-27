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

define( '_DESCRIPTION_PAYPAL',				'Mit PayPal k&ouml;nnen Sie Zahlungen an jeden senden, der eine Emailadresse besitzt. PayPal ist kostenlos und wird in das vorhandene Kreditkarten- und Bankkonto integriert.' );

define( '_DESCRIPTION_PAYPAL_SUBSCRIPTION', 'PayPal Abonnement ist der PayPal Service f&uuml;r wiederkehrende Zahlungen mit dem <strong>ihr Konto ihrem Abonnement entsprechend automatisch wiederkehrend belastet wird</strong>. Dies k&ouml;nnen sie selbstverst&auml;ndlich jederzeit in ihrem PayPal Konto abbrechen. PayPal ist kostenlos und wird in Ihr vorhandenes Kreditkarten- und Bankkonto integriert.' );

define( '_DESCRIPTION_AUTHORIZE',			'Das Zahlungsschnittstelle erm&ouml;glicht es Interneth&auml;ndlern Onlinezahlungen mit Kreditkarte, Bankzahlung und E-Check sicher abzuwickeln.' );
define( '_DESCRIPTION_VIAKLIX',				'Bietet integriertes Kreditkarten und Lastschriftverfahren, elektronische &Uuml;berpr&uuml;fung and verwandte Softwareapplikationen an ...' );
define( '_DESCRIPTION_ALLOPASS',			'AlloPass, das europ&auml;ische Institut f&uuml;Micropayment, erlaubt Zahlungen via SMS, Telefon und Kreditkarten.' );
define( '_DESCRIPTION_2CHECKOUT',			'Sofortige Kreditkartenabwicklung f&uuml;r Interneth&auml;ndler.' );
define( '_DESCRIPTION_EPSNETPAY',			'EPS ist das einfache, sichere und kostenlose Zahlungssystem der &ouml;sterreichischen Banken f&uuml;r Eink&auml;ufe im Internet.' );
define( '_DESCRIPTION_ALERTPAY',			'Ihr Geld ist sicher mit AlertPay\'s Sicherheitsgrundsatz. Offen f&uuml;r alle Gesch&auml;ftsm&ouml;glichkeiten.' );

// Generic Processor Names&Descs
define( '_CFG_PROCESSOR_TESTMODE_NAME',		'Test Modus?' );
define( '_CFG_PROCESSOR_TESTMODE_DESC',		'W&auml;hlen Sie "Ja", um den Test-Modus zu aktivieren, hierbei wird entweder zu einer Testumgebung des Bezahlservices umgeleitet, oder die Bezahlung wird ohne Umweg direkt bezahlt. Falls sie nicht wissen, was dies bedeutet, belassen sie es als "Nein" - f&uuml;r einige Testumgebungen ben&ouml;tigen Sie einen Testzugang (z.B. PayPal) um keine realen Zahlungen durchzuf&uuml;hren.' );
define( '_CFG_PROCESSOR_CURRENCY_NAME',		'W&auml;hrung' );
define( '_CFG_PROCESSOR_CURRENCY_DESC',		'W&auml;hlen sie die W&auml;hrung die sie f&uuml;r diesen Bezahlservice nutzen m&ouml;chten' );
define( '_CFG_PROCESSOR_NAME_NAME',			'Angezeigter Name' );
define( '_CFG_PROCESSOR_NAME_DESC',			'Wie soll dieser Zahlungsvorgang genannt werden' );
define( '_CFG_PROCESSOR_DESC_NAME',			'Beschreibung anzeigen' );
define( '_CFG_PROCESSOR_DESC_DESC',			'Soll die Beschreibung f&uuml;r diesen Zahlvorgang angezeigt werden (z.B. auf der Nichteingeloggt-, Best&auml;tigungs- und Abschlussseite.' );
define( '_CFG_PROCESSOR_ITEM_NAME_NAME',	'Artikelbeschreibung' );
define( '_CFG_PROCESSOR_ITEM_NAME_DESC',	'Artikelbeschreibung dieses Bezahlsystems' );

// Paypal Settings
define( '_CFG_PAYPAL_BUSINESS_NAME',		'PayPal Gesch&auml;fts ID:' );
define( '_CFG_PAYPAL_BUSINESS_DESC',		'Die PayPal-ID um Gesch&auml;fte durchf&uuml;hren zu k&ouml;nnen' );
define( '_CFG_PAYPAL_CHECKBUSINESS_NAME',	'Sicherheits&uuml;berpr&uuml;fung:' );
define( '_CFG_PAYPAL_CHECKBUSINESS_DESC',	'Wenn dies aktiviert ist, wird bei der &Uuml;bermittlung der Daten an Paypal getestet, ob die Empf&auml;ngeremailadresse mit der von ihnen angegebenen &uuml;bereinstimmt. Dies kann einen Fehler produzieren, falls sie bei Paypal mehr als nur eine Adresse benutzen. Wenn Benutzer also nicht aktiviert werden obwohl sie korrekt bezahlt haben, w&auml;hlen sie "Nein"' );
define( '_CFG_PAYPAL_ALTIPNURL_NAME',		'Alternative IPN Benachrichtigungsdomain:' );
define( '_CFG_PAYPAL_ALTIPNURL_DESC',		'Falls Sie Server-Workload-Balancing einsetzen (autom. Wechseln zwischen mehreren IP-Adressen) k&ouml;nnte es vorkommen dass PayPal die Verbindung abbricht w&auml;hrend die Best&auml;tigung (IPN) retour gesendet wird. Um diesen Fehler zu umgehen, kann z.B. eine neue Subdomain zu diesem Webserver/Webseite erstellt werden und das LOADBALANCING deaktivieren. Dann hier diese Subdomainadresse (z.B. http://subdomain.domain.com - KEIN Slash am Ende!) eintragen - PayPal wird dann nur an diese Adresse die IPN senden.<br /><strong>Feld leer lassen wenn keine Verwendung daf&uuml;r!</strong>' );
define( '_CFG_PAYPAL_LC_NAME',				'Sprache:' );
define( '_CFG_PAYPAL_LC_DESC',				'Eine der vorhandenen Sprachen ausw&auml;hlen, die Benutzer werden dann zu dieser PayPalseite mit der gew&auml;hlten Sprache beim Zahlvorgang umgeleitet.' );
define( '_CFG_PAYPAL_TAX_NAME',				'Steuer:' );
define( '_CFG_PAYPAL_TAX_DESC',				'Steuer in % angeben. Z.B bei einem Bruttobetrag von 120 Euro dann hier 20 angeben, es wird der Nettobetrag von 100 und die Steuer von 20 Euro ausgewiesen' );

// Paypal Subscriptions Settings
define( '_CFG_PAYPAL_SUBSCRIPTION_BUSINESS_NAME',	'Gesch&auml;fts ID:' );
define( '_CFG_PAYPAL_SUBSCRIPTION_BUSINESS_DESC',	'Ihre Gesch&auml;fts-ID (= Email) bei PayPalI:' );
define( '_CFG_PAYPAL_SUBSCRIPTION_CHECKBUSINESS_NAME', 'Sicherheist&uuml;berpr&uuml;fung:' );
define( '_CFG_PAYPAL_SUBSCRIPTION_CHECKBUSINESS_DESC', 'Wenn dies aktiviert ist, wird bei der &Uuml;bermittlung der Daten an Paypal getestet, ob die Empf&auml;ngeremailadresse mit der von ihnen angegebenen &uuml;bereinstimmt. Dies kann einen Fehler produzieren, falls sie bei Paypal mehr als nur eine Adresse benutzen. Wenn Benutzer also nicht aktiviert werden obwohl sie korrekt bezahlt haben, w&auml;hlen sie "Nein"' );
define( '_CFG_PAYPAL_SUBSCRIPTION_ALTIPNURL_NAME', 'Alternative IPN Benachrichtigungsdomain:' );
define( '_CFG_PAYPAL_SUBSCRIPTION_ALTIPNURL_DESC', 'Falls Sie Server-Workload-Balancing einsetzen (autom. Wechseln zwischen mehreren IP-Adressen) k&ouml;nnte es vorkommen dass PayPal die Verbindung abbricht w&auml;hrend die Best&auml;tigung (IPN) retour gesendet wird. Um diesen Fehler zu umgehen, kann z.B. eine neue Subdomain zu diesem Webserver/Webseite erstellt werden und das LOADBALANCING deaktivieren. Dann hier diese Subdomainadresse (z.B. http://subdomain.domain.com - KEIN Slash am Ende!) eintragen - PayPal wird dann nur an diese Adresse die IPN senden.<br /><strong>Feld leer lassen wenn keine Verwendung daf&uuml;r!</strong>' );
define( '_CFG_PAYPAL_SUBSCRIPTION_LC_NAME', 	'Sprache:' );
define( '_CFG_PAYPAL_SUBSCRIPTION_LC_DESC',		'Eine der vorhandenen Sprachen ausw&auml;hlen, die Benutzer werden dann zu dieser PayPalseite mit der gew&auml;hlten Sprache beim Zahlvorgang umgeleitet.' );
define( '_CFG_PAYPAL_SUBSCRIPTION_TAX_NAME',	'Steuer:' );
define( '_CFG_PAYPAL_SUBSCRIPTION_TAX_DESC',	'Steuer in % angeben. Z.B bei einem Bruttobetrag von 120 Euro dann hier 20 angeben, es wird der Nettobetrag von 100 und die Steuer von 20 Euro ausgewiesen' );
define( '_PAYPAL_SUBSCRIPTION_CANCEL_INFO',		'Wenn Sie ihr Abonnement &auml;ndern m&ouml;chten, muss vorher das Aktuelle bei PayPal storniert werden!' );

// Transfer Settings
define( '_CFG_TRANSFER_TITLE',					'Manuell' );
define( '_CFG_TRANSFER_SUBTITLE',				'Nichtautomatische Zahlungen' );
define( '_CFG_TRANSFER_ENABLE_NAME',			'Nichtautomatische Zahlungen erlauben?' );
define( '_CFG_TRANSFER_ENABLE_DESC',			'Hier JA w&auml;hlen falls den Besuchern die M&ouml;glichkeit zur Bezahlung mittels Bankeinzug, -&uuml;berweisung, Geldanweisung, etc. angezeigt werden soll.' );
define( '_CFG_TRANSFER_INFO_NAME',				'Info manuelle/alternative Bezahlung:' );
define( '_CFG_TRANSFER_INFO_DESC',				'Text welcher Besuchern angezeigt wird falls diese keines der angebotenen automatischen Zahlungsschnittstellen (PayPAl, usw.) verwenden m&ouml;chten. Nach der Registrierung werden solche Abonnenten automatisch als <strong>nicht aktiviert</strong> eingestuft, die Freigabe muss manuell im Backend erteilt werden (HTML.Tags sind erlaubt)' );

// Viaklix Settings
define( '_CFG_VIAKLIX_ACCOUNTID_NAME',			'Account ID' );
define( '_CFG_VIAKLIX_ACCOUNTID_DESC',			'Ihre Account ID bei viaKLIX.' );
define( '_CFG_VIAKLIX_USERID_NAME',				'User ID' );
define( '_CFG_VIAKLIX_USERID_DESC',				'Ihre User ID bei viaKLIX.' );
define( '_CFG_VIAKLIX_PIN_NAME',				'PIN' );
define( '_CFG_VIAKLIX_PIN_DESC',				'PIN des Terminals.' );

// Authorize.net Settings
define( '_CFG_AUTHORIZE_LOGIN_NAME',			'Login ID' );
define( '_CFG_AUTHORIZE_LOGIN_DESC',			'Ihre Login ID bei Authorize.net.' );
define( '_CFG_AUTHORIZE_TRANSACTION_KEY_NAME',	'Transaktions Schl&uuml;ssel' );
define( '_CFG_AUTHORIZE_TRANSACTION_KEY_DESC',	'Ihr Transaktions-Schl&uuml;ssel bei Authorize.net.' );

// Allopass Settings
define( '_CFG_ALLOPASS_SITEID_NAME',			'SITE_ID' );
define( '_CFG_ALLOPASS_SITEID_DESC',			'Ihre SITE_ID bei AlloPass.' );
define( '_CFG_ALLOPASS_DOCID_NAME',				'DOC_ID' );
define( '_CFG_ALLOPASS_DOCID_DESC',				'Ihre DOC_ID bei AlloPass.' );
define( '_CFG_ALLOPASS_AUTH_NAME',				'AUTH' );
define( '_CFG_ALLOPASS_AUTH_DESC',				'AUTH bei AlloPass.' );

// 2Checkout Settings
define( '_CFG_2CHECKOUT_SID_NAME',				'SID' );
define( '_CFG_2CHECKOUT_SID_DESC',				'Ihre 2checkout Account-Nummer.' );
define( '_CFG_2CHECKOUT_SECRET_WORD_NAME',		'Geheimes Wort' );
define( '_CFG_2CHECKOUT_SECRET_WORD_DESC',		'Dasselbe geheime Wort, welches sie auf der "Look and Feel" Seite angegeben haben.' );
define( '_CFG_2CHECKOUT_INFO_NAME',				'WICHTIG!' );
define( '_CFG_2CHECKOUT_INFO_DESC',				'Sie m&uuml;ssen auf ihrer 2Checkout Account Homepage noch eine Einstellung vornehmen! Gehen sie daf&uuml;r auf ihrer 2CO Homepage in die Kategorie "Helpful Links" und klicken dort auf den "Look and Feel" link. &Auml;ndern das Feld "Approved URL" mit der URL "http://<strong>yoursite.com</strong>/index.php?option=com_acctexp&task=2conotification". Ersetzen sie "yoursite.com" mit ihrer Domain.' );
define( '_CFG_2CHECKOUT_ALT2COURL_NAME',		'Alternativ-Url' );
define( '_CFG_2CHECKOUT_ALT2COURL_DESC',		'Alternative Url bei Problemen mit 2Checkout' );

// WorldPay Settings
define( '_CFG_WORLDPAY_INSTID_NAME',			'instId' );
define( '_CFG_WORLDPAY_INSTID_DESC',			'Ihre WorldPay Installations ID.' );

// epsNetpay Settings
define( '_CFG_EPSNETPAY_MERCHANTID_NAME',		'H&auml;ndler ID' );
define( '_CFG_EPSNETPAY_MERCHANTID_DESC',		'Ihre epsNetpay Konto Nummer.' );
define( '_CFG_EPSNETPAY_MERCHANTPIN_NAME',		'H&auml;ndler PIN' );
define( '_CFG_EPSNETPAY_MERCHANTPIN_DESC',		'Ihr H&auml;ndler PIN.' );
define( '_CFG_EPSNETPAY_ACTIVATE_NAME',			'Aktivieren' );
define( '_CFG_EPSNETPAY_ACTIVATE_DESC',			'Diese Bankverbidung anbieten.' );
define( '_CFG_EPSNETPAY_ACCEPTVOK_NAME',		'VOK Akzeptieren' );
define( '_CFG_EPSNETPAY_ACCEPTVOK_DESC',		'Aufgrund Ihres Kontotyps kann es sein, dass Sie nie den Status "OK", sondern immer nur "VOK" gemeldet bekommen. Bitte aktivieren Sie dieses Feld sollte dies der Fall sein.' );

// Paysignet Settings
define( '_CFG_PAYSIGNET_MERCHANT_NAME',			'H&auml;ndlername' );
define( '_CFG_PAYSIGNET_MERCHANT_DESC',			'Ihr H&auml;ndlername' );

// AlertPay Settings
define( '_CFG_ALERTPAY_MERCHANT_NAME',			'H&auml;ndler' );
define( '_CFG_ALERTPAY_MERCHANT_DESC',			'Ihr H&auml;ndlername' );
define( '_CFG_ALERTPAY_SECURITYCODE_NAME',		'Sicherheitscode' );
define( '_CFG_ALERTPAY_SECURITYCODE_DESC',		'Ihr Sicherheitscode' );
?>