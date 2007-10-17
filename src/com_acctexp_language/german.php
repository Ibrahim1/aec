<?php
/**
 * @version $Id: german.php 16 2007-06-25 09:04:04Z mic $
 * @package AEC - Account Control Expiration - Subscription component for Joomla! OS CMS
 * @subpackage Language - Frontend - German Formal
 * @copyright Copyright (C) 2004-2007, All Rights Reserved, Helder Garcia, David Deutsch
 * @author Helder Garcia <helder.garcia@gmail.com>, David Deutsch <skore@skore.de> & Team AEC - http://www.gobalnerd.org
 * @license GNU/GPL v.2 http://www.gnu.org/copyleft/gpl.html
 */

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

// Dont allow direct linking
defined( '_VALID_MOS' ) or die( 'Restricted access' );

if( defined( '_AEC_LANG' ) ) {
	return;
}

// new 2007.07.10
define( '_AEC_EXPIRE_TODAY',				'Das Konto ist bis heute aktiv' );
define( '_AEC_EXPIRE_FUTURE',				'Das Konto ist aktiv bis' );
define( '_AEC_EXPIRE_PAST',					'Das Konto war aktiv bis' );
define( '_AEC_DAYS_ELAPSED',				'Tag(e) abgelaufen' );

// new 0.12.4 (mic)
define( '_AEC_EXPIRE_NOT_SET',				'Nicht definiert' );
define( '_AEC_GEN_ERROR',					'<h1>FEHLER!</h1><p>Leider trat w&auml;hrend der Bearbeitung ein Fehler auf - bitte informieren Sie auch den Administrator. Danke.</p>' );

// payments
define( '_AEC_PAYM_METHOD_FREE',			'Gratis/Frei' );
define( '_AEC_PAYM_METHOD_NONE',			'Kein/Frei' );
define( '_AEC_PAYM_METHOD_TRANSFER',		'&Uuml;berweisung' );

// processor errors
define( '_AEC_MSG_PROC_INVOICE_FAILED_SH',			'FEHLER: Fehlende Rechnungsnummer' );
define( '_AEC_MSG_PROC_INVOICE_FAILED_EV',			'Benachrichtigung f&uuml;r %s zu Rechnungsnummer %s - Re.Nummer existiert nicht:' );
define( '_AEC_MSG_PROC_INVOICE_ACTION_SH',			'Bezahlung' );
define( '_AEC_MSG_PROC_INVOICE_ACTION_EV',			'Meldung zur Zahlungsnachricht:' );
define( '_AEC_MSG_PROC_INVOICE_ACTION_EV_STATUS',	'Rechnungsstatus:' );
define( '_AEC_MSG_PROC_INVOICE_ACTION_EV_FRAUD',	'Betrags&uuml;berpr&uuml;fung fehlerhaft, gezahlt: %s, lt. Rechnung: %s - Zahlung abgebrochen' );
define( '_AEC_MSG_PROC_INVOICE_ACTION_EV_CURR',		'Falsche W&auml;hrung, gezahlt in %s, lt. Rechnung %s, Zahlung abgebrochen' );
define( '_AEC_MSG_PROC_INVOICE_ACTION_EV_VALID',	'G&uuml;ltige Zahlung' );
define( '_AEC_MSG_PROC_INVOICE_ACTION_EV_TRIAL',	'G&uuml;ltige Zahlung - Gratiszeitraum' );
define( '_AEC_MSG_PROC_INVOICE_ACTION_EV_PEND',		'G&uuml;ltige Zahlung - Status Wartend, Grund: %s' );
define( '_AEC_MSG_PROC_INVOICE_ACTION_EV_CANCEL',	'Keine Zahlung - Storno' );
define( '_AEC_MSG_PROC_INVOICE_ACTION_EV_USTATUS',	', Benutzerstatus wurde auf \'Storno\' gesetzt' );
define( '_AEC_MSG_PROC_INVOICE_ACTION_EV_EOT',		'Keine Zahlung - Abo ist abgelaufen' );
define( '_AEC_MSG_PROC_INVOICE_ACTION_EV_DUPLICATE','Keine Zahlung - Duplikat' );
define( '_AEC_MSG_PROC_INVOICE_ACTION_EV_U_ERROR',	'Unbekannter Fehler' );
define( '_AEC_MSG_PROC_INVOICE_ACTION_EV_REFUND',	'No Payment - Subscription Deleted (refund)' );
define( '_AEC_MSG_PROC_INVOICE_ACTION_EV_EXPIRED',	', User has been expired' );

// end mic ########################################################

// --== PAYMENT PLANS PAGE ==--
define( '_PAYPLANS_HEADER',					'Bezahlungspl&auml;ne' );
define( '_NOPLANS_ERROR',					'Es trat ein interner Fehler auf, dadurch sind momentan keine Abonnements vorhanden, bitte den Administrator informieren - danke!');

// --== ACCOUNT DETAILS PAGE ==--
define( '_CHK_USERNAME_AVAIL',				'Benutzername %s ist verf&uuml;gbar' );
define( '_CHK_USERNAME_NOTAVAIL',			'Leider ist dieser Benutzername %s bereits vergeben!');

// --== MY SUBSCRIPTION PAGE ==--
define( '_HISTORY_TITLE',					'Abonnementverlauf - die letzten 10 Vorg&auml;nge');
define( '_HISTORY_SUBTITLE',				'Abonnent seit ');
define( '_HISTORY_COL1_TITLE',				'Rechnung');
define( '_HISTORY_COL2_TITLE',				'Wert');
define( '_HISTORY_COL3_TITLE',				'Zahlungsdatum');
define( '_HISTORY_COL4_TITLE',				'Methode');
define( '_HISTORY_COL5_TITLE',				'Aktion');
define( '_HISTORY_COL6_TITLE', 				'Plan');
define( '_HISTORY_ACTION_REPEAT', 			'bezahlen');
define( '_HISTORY_ACTION_CANCEL', 			'l&ouml;schen');
define( '_RENEW_LIFETIME', 					'Sie haben ein permanentes Benutzerkonto.');
define( '_RENEW_DAYSLEFT', 					'Tag(e) &uuml;brig');
define( '_RENEW_DAYSLEFT_EXCLUDED', 		'Ihr Konto unterliegt keinem Ablauf.');
define( '_RENEW_DAYSLEFT_INFINITE', 		'&#8734');
define( '_RENEW_INFO', 						'Sie verwenden automatisch wiederkehrende Zahlungen.');
define( '_RENEW_OFFLINE', 					'Erneuern');
define( '_RENEW_BUTTON_UPGRADE', 			'Upgraden / Erneuern');
define( '_PAYMENT_PENDING_REASON_ECHECK',	'ECheck noch ausst&auml;ndig (1-4 Arbeitstage)');
define( '_PAYMENT_PENDING_REASON_TRANSFER', 'Zahlungsanweisung wird erwartet');

// --== EXPIRATION PAGE ==--
define( '_EXPIRE_INFO',						'Ihr Konto ist aktiv bis');
define( '_RENEW_BUTTON',					'Erneuern');
define( '_ACCT_DATE_FORMAT',				'%m-%d-%Y');
define( '_EXPIRED',							'Ihr Abonnement ist abgelaufen, Ende des letzten Abonnements: ');
define( '_EXPIRED_TRIAL', 					'Ihre Testphase ist ausgelaufen, Ende der Testphase: ');
define( '_ERRTIMESTAMP', 					'Kann Zeitstempel nicht &auml;ndern.');
define( '_EXPIRED_TITLE', 					'Konto abgelaufen!!');
define( '_DEAR', 							'Sehr geehrte(r) %s,');

// --== CONFIRMATION FORM ==--
define( '_CONFIRM_TITLE', 					'Best&auml;tigungsformular');
define( '_CONFIRM_COL1_TITLE', 				'Ihr Konto');
define( '_CONFIRM_COL2_TITLE', 				'Detail');
define( '_CONFIRM_COL3_TITLE',				'Preis');
define( '_CONFIRM_ROW_NAME',				'Name: ');
define( '_CONFIRM_ROW_USERNAME',			'Benutzername: ');
define( '_CONFIRM_ROW_EMAIL',				'Email:');
define( '_CONFIRM_INFO',					'Benutzen Sie bitte den Best&auml;tigungsbutton um Ihre Bestellung abzuschlie&szlig;en.');
define( '_BUTTON_CONFIRM',					'Best&auml;tigen');
define( '_CONFIRM_TOS',						'Ich habe die <a href="%s" target="_blank" title="AGBs>AGBs</a> gelesen und bin einverstanden.');
define( '_CONFIRM_TOS_ERROR',				'Sie m&uuml;ssen unsere AGBs lesen und akzeptieren');
define( '_CONFIRM_COUPON_INFO',				'Falls Sie einen Gutscheincode haben geben Sie ihn bitte auf den nachfolgenden Seiten an, um einen allf&auml;lligen Abzug zu ber&uuml;cksichtigen');
define( '_CONFIRM_FREETRIAL', 'Kostenlose Testphase');

// --== CHECKOUT FORM ==--
define( '_CHECKOUT_TITLE',					'Abschlie&szlig;ssen');
define( '_CHECKOUT_INFO',					'Die Angaben wurden gespeichert, es ist jetzt erforderlich, dass Sie mit der Bezahlung ihrer getroffenen Auswahl fortfahren.<br />Sollte es im Folgenden Unklarheiten geben, k&ouml;nnen Sie immer zu dieser Seite zur&uuml;ckkehren, indem Sie sich mit ihren Zugangsdaten einw&auml;hlen.');
define( '_CHECKOUT_INFO_REPEAT',			'Willkommen zur&uuml;ck! Die Bezahlung ihrer getroffenen Auswahl ist noch ausst&auml;ndig.<br />Sollte es im Folgenden Unklarheiten geben, k&ouml;nnen Sie immer zu dieser Seite zur&uuml;ckkehren, indem Sie sich mit ihren Zugangsdaten einw&auml;hlen.');
define( '_BUTTON_CHECKOUT',					'Fortfahren');
define( '_BUTTON_APPEND',					'Hinzuf&uuml;gen');
define( '_CHECKOUT_COUPON_CODE',			'Gutscheincode');
define( '_CHECKOUT_INVOICE_AMOUNT',			'Rechnungsbetrag');
define( '_CHECKOUT_INVOICE_COUPON',			'Gutschein');
define( '_CHECKOUT_INVOICE_COUPON_REMOVE',	'entfernen');
define( '_CHECKOUT_INVOICE_TOTAL_AMOUNT',	'Summe');
define( '_CHECKOUT_COUPON_INFO',			'Falls Sie einen Gutscheincode haben, geben Sie ihn bitte hier an.');

// --== ALLOPASS SPECIFIC ==--
define( '_REGTITLE',						'INSCRIPTION');
define( '_ERRORCODE',						'Erreur de code Allopass');
define( '_FTEXTA',							'Le code que vous avez utilis n\'est pas valide! Pour obtenir un code valable, composez le numero de tlphone, indiqu dans une fenetre pop-up, aprs avoir clicker sur le drapeau de votre pays. Votre browser doit accepter les cookies d\'usage.<br /><br />Si vous tes certain, que vous avez le bon code, attendez quelques secondes et ressayez encore une fois!<br><br>Sinon prenez note de la date et heure de cet avertissement d\'erreur et informez le Webmaster de ce problme en indiquant le code utilis.');
define( '_RECODE',							'Saisir de nouveau le code Allopass');

// --== REGISTRATION STEPS ==--
define( '_STEP_DATA',						'Ihre Daten');
define( '_STEP_CONFIRM',					'Best&auml;tigen');
define( '_STEP_PLAN',						'Plan w&auml;hlen');
define( '_STEP_EXPIRED',					'Abgelaufen!');

// --== NOT ALLOWED PAGE ==--
define( '_NOT_ALLOWED_HEADLINE',			'Mitgliedschaft erforderlich!');
define( '_NOT_ALLOWED_FIRSTPAR',			'Die Inhalte auf die Sie zugreifen m&ouml;chten, sind nur f&uuml;r Mitglieder dieser Seite zug&auml;nglich. Falls Sie also bereits registriert sind, benutzen Sie bitte unseren Login um sich einzuw&auml;hlen. Falls Sie sich registrieren m&ouml;chten, erhalten Sie hier einen &Uuml;berblick &uuml;ber die angebotenen Mitgliedschaften,:');
define( '_NOT_ALLOWED_REGISTERLINK',		'Registrierungs&uuml;bersicht');
define( '_NOT_ALLOWED_FIRSTPAR_LOGGED',		'Die Inhalte auf die Sie zugreifen m&ouml;chten, sind nur f&uuml;r Mitglieder dieser Seite zug&auml;nglich. Falls Sie also bereits registriert sind, folgend sie diesem Link um ihr Abonnement zu &auml;ndern: ');
define( '_NOT_ALLOWED_REGISTERLINK_LOGGED', 'Abonnement&uuml;bersicht');
define( '_NOT_ALLOWED_SECONDPAR',			'Um dieser Seite betreten zu k&ouml;nnen, ben&ouml;tigen sie nicht mehr als eine Minute, wir nutzen den Service von:');

// --== CANCELLED PAGE ==--
define( '_CANCEL_TITLE',					'Ergebnis der Registrierung: Abgebrochen!');
define( '_CANCEL_MSG',						'Unsere Datenverarbeitung hat die R&uuml;ckmeldung erhalten, dass Sie sich entschieden haben, die Registrierung abzubrechen. Falls Sie die Registrierung aufgrund von Problemen mit dieser Internetseite abgebrochen haben, benachrichtigen Sie bitte auch den Webseitenadmin davon.');

// --== PENDING PAGE ==--
define( '_PENDING_TITLE',					'Account Schwebend!');
define( '_WARN_PENDING',					'Ihr Konto ist noch immer nicht vollst&auml;ndig. Sollte dies f&uuml;r l&auml;ngere Zeit so bleiben obwohl Ihre Zahlung durchgef&uuml;hrt wurde, kontaktieren sie bitte den Administrator dieser Internetseite.');
define( '_PENDING_OPENINVOICE',				'Es scheint, Sie haben eine unbezahlte Rechnung in unserer Datenbank - Falls mit der Bezahlung etwas schief gelaufen ist, k&ouml;nnen Sie diese gerne erneut in Auftrag geben:');
define( '_GOTO_CHECKOUT',					'Noch einmal zum Auschecken gehen');
define( '_GOTO_CHECKOUT_CANCEL',			'Sie k&ouml;nnen die Rechnungserstellung auch abbrechen (Sie werden dann zu einer neuen Auswahl umgeleitet):');
define( '_PENDING_NOINVOICE',				'Es scheint, Sie haben die letzte offene Rechnung nicht beglichen. Bitte benutzen Sie diesen Button um erneut zur Auswahl eines Abos zu gelangen:');
define( '_PENDING_NOINVOICE_BUTTON',		'Aboauswahl');
define( '_PENDING_REASON_ECHECK',			'(Desweiteren haben wir jedoch auch die Information, dass Sie sich entschieden haben, mit Echeck zu bezahlen. M&ouml;glicherweise m&uuml;ssen Sie also nur warten bis dieser verarbeitet wurde - dies dauert gew&ouml;hnlich 1-4 Tage.)');
define( '_PENDING_REASON_TRANSFER',			'(Desweiteren haben wir jedoch auch die Information, dass Sie sich entschieden haben, die Rechnung auf herk&ouml;mmlichem Weg zu bezahlen. Die Verarbeitung einer solchen Zahlung kann einige Tage dauern.)');

// --== THANK YOU PAGE ==--
define( '_THANKYOU_TITLE',					'Vielen Dank!');
define( '_SUB_FEPARTICLE_HEAD',				'Abonnementerneuerung abgeschlossen!');
define( '_SUB_FEPARTICLE_HEAD_RENEW',		'Erneuerung ihres Abonnements abgeschlossen!');
define( '_SUB_FEPARTICLE_LOGIN',			'Sie k&ouml;nnen sich nun einloggen.');
define( '_SUB_FEPARTICLE_THANKS',			'Vielen Dank!' );
define( '_SUB_FEPARTICLE_THANKSRENEW',		'Vielen Dank f&uuml;r ihre Treue!' );
define( '_SUB_FEPARTICLE_PROCESS',			'Wir werden ihren Auftrag nun verarbeiten.' );
define( '_SUB_FEPARTICLE_PROCESSPAY',		'Wir werden nun ihre Bezahlung abwarten.' );
define( '_SUB_FEPARTICLE_ACTMAIL',			'Sobald die Anfrage abgeschlossen ist, erhalten sie ein Email mit dem Aktivierungscode');
define( '_SUB_FEPARTICLE_MAIL',				'Sobald die Anfrage abgeschlossen ist, erhalten Sie von uns ein Email');

// --== COUPON INFO ==--
define( '_COUPON_INFO',						'Gutscheine:');
define( '_COUPON_INFO_CONFIRM',				'Falls Sie einen Gutschein f&uuml;r die Bezahlung verwenden m&ouml;chten, geben Sie diesen bitte auf der Rechnungsseite an.');
define( '_COUPON_INFO_CHECKOUT',			'Bitte geben Sie jetzt den Gutschein an und best&auml;tigen durch dr&uuml;cken des Buttons');

// --== COUPON ERROR MESSAGES ==--
define( '_COUPON_WARNING_AMOUNT',			'Die angegebene Gutscheinnummer hat keinen Einflu&szlig; auf die Rechnungssumme.');
define( '_COUPON_ERROR_PRETEXT',			'Wir bedauern sehr:');
define( '_COUPON_ERROR_EXPIRED',			'Dieser Gutschein ist bereits abgelaufen.');
define( '_COUPON_ERROR_NOTSTARTED',			'Die Verwendung dieses Gutscheins ist momentan nicht erlaubt.');
define( '_COUPON_ERROR_NOTFOUND',			'Der Gutscheincode konnte nicht gefunden werden.');
define( '_COUPON_ERROR_MAX_REUSE',			'Dieser Gutschein wurde bereits von anderen Besuchern verwendet und hat das Maximum erreicht.');
define( '_COUPON_ERROR_PERMISSION',			'Sie haben nicht die erforderliche Berechtigung zur Verwendung dieses Gutscheins.');
define( '_COUPON_ERROR_WRONG_USAGE',		'Diese Gutschein kann daf&uuml;r nicht verwendet werden.');
define( '_COUPON_ERROR_WRONG_PLAN',			'Dieser Gutschein gilt nicht f&uuml;r dieses Abonnement.');
define( '_COUPON_ERROR_WRONG_PLAN_PREVIOUS',	'Um diesen Gutschein zu verwenden mu&szlig; ein anderes Abonnement gew&auml;hlt werden.');
define( '_COUPON_ERROR_WRONG_PLANS_OVERALL',	'Sie haben leider nicht das richtige Abonnement in den bisherigen Abos um diesen Gutschein zu verwenden.');
define( '_COUPON_ERROR_TRIAL_ONLY',			'Dieser Gutschein gilt nur f&uuml;r ein Probezeit-/Gratisabo.');
define( '_COUPON_ERROR_COMBINATION', 'Dieser Gutschein kann nicht mit einem der zuvor eingegebenen Gutscheine verwendet werden.');

// ----======== EMAIL TEXT ========----

define( '_ACCTEXP_SEND_MSG',				'Abonnement: %s bei %s');
define( '_ACCTEXP_SEND_MSG_RENEW',			'Erneuerung eines Abonnements: %s bei %s');
define( '_ACCTEXP_MAILPARTICLE_GREETING',	"Hallo %s,\n\n");
define( '_ACCTEXP_MAILPARTICLE_THANKSREG',	'Vielen Dank f&uuml;r ihr Abonnement auf %s.');
define( '_ACCTEXP_MAILPARTICLE_THANKSREN',	'Vielen Dank f&uuml;r die Erneuerung ihres Abonnements auf %s.' );
define( '_ACCTEXP_MAILPARTICLE_PAYREC',		'Ihre Bezahlung wurde dankend entgegengenommen.' );
define( '_ACCTEXP_MAILPARTICLE_LOGIN',		'Sie k&ouml;nnen sich nun auf %s mit dem gew&auml;hlten Benutzernamen und Passwort einw&auml;hlen.');
define( '_ACCTEXP_MAILPARTICLE_FOOTER',		"\n\nBitte nicht auf dieses Email antworten, es wurde automatisch generiert und dient nur der Information." );
define( '_ACCTEXP_ASEND_MSG',				"Hallo %s,\n\nein neues Abonnement wurde auf [ %s ] abgeschlossen.\n\nHier die Details:\n\nName.........: %s\nEmail........: %s\nBenutzername : %s\nAbo-ID.......: %s\nAbonnement...: %s\nIP...........: %s\nISP..........: %s\n\nDas ist eine automatische Benachrichtigung, bitte nicht antworten." );
define( '_ACCTEXP_ASEND_MSG_RENEW',			"Hallo %s,\n\neine Aboverl&auml;ngerung auf %s.\n\nHier die Benutzerdetails:\n\nName.........: %s\nEmail........: %s\nBenutzername : %s\nAbo-ID.......: %s\nAbonnement...: %s\nIP...........: %s\nISP..........: %s\n\nDas ist eine automatische Benachrichtigung, bitte nicht antworten." );
define( '_AEC_ASEND_MSG_NEW_REG',			"Hallo %s,\n\nEin neuer Benutzer wurde auf [ %s ] registriert.\n\nHier die Details:\n\nName . . . . : %s\nEmail : %s\nBenutzername  . . . : %s\nIP . . . . . : %s\nISP	 . . . . : %s\n\nDas ist eine automatische Benachrichtigung, bitte nicht antworten." );
?>