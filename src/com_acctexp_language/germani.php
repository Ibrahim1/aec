<?php
/**
 * @version $Id: germani.php 16 2007-06-25 09:04:04Z mic $
 * @package AEC - Account Control Expiration - Subscription component for Joomla! OS CMS
 * @subpackage Language - Frontend - German Informal
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
defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );

// new 0.12.4 (mic)
define( '_AEC_EXPIRE_NOT_SET',				'Nicht definiert' );
define( '_AEC_GEN_ERROR',					'<h1>FEHLER!</h1><p>Leider trat w&auml;hrend der Bearbeitung ein Fehler auf - bitte informieren Sie auch den Administrator. Danke.</p>' );

// end mic ########################################################

// --== PAYMENT PLANS PAGE ==--
DEFINE ('_NOPLANS_ERROR',					'Es trat in interner Fehler auf, dadurch sind momentan keine Abonnements vorhanden, bitte den Administrator informieren - danke!');

// --== ACCOUNT DETAILS PAGE ==--
DEFINE ('_CHK_USERNAME_AVAIL', "Benutzername %s ist verf&uuml;gbar");
DEFINE ('_CHK_USERNAME_NOTAVAIL', "Benutzername %s ist leider bereits vergeben!");

// --== MY SUBSCRIPTION PAGE ==--
DEFINE ('_HISTORY_TITLE', 'Abonnements-Verlauf - die letzten Vorg&auml;nge');
DEFINE ('_HISTORY_SUBTITLE', 'Abonnent seit ');
DEFINE ('_HISTORY_COL1_TITLE', 'Rechnung');
DEFINE ('_HISTORY_COL2_TITLE', 'Wert');
DEFINE ('_HISTORY_COL3_TITLE', 'Zahlungsdatum');
DEFINE ('_HISTORY_COL4_TITLE', 'Methode');
DEFINE ('_HISTORY_COL5_TITLE', 'Aktion');
DEFINE ('_HISTORY_COL6_TITLE', 'Plan');
DEFINE ('_HISTORY_ACTION_REPEAT', 'bezahlen');
DEFINE ('_HISTORY_ACTION_CANCEL', 'l&ouml;schen');
DEFINE ('_RENEW_LIFETIME', 'Sie haben ein nicht auslaufendes Benutzerkonto.');
DEFINE ('_RENEW_DAYSLEFT', 'Tage &uuml;brig');
DEFINE ('_RENEW_DAYSLEFT_EXCLUDED', 'Ihr Konto unterliegt keinem Ablauf.');
DEFINE ('_RENEW_DAYSLEFT_INFINITE', '&#8734');
DEFINE ('_RENEW_INFO', 'Sie benutzen automatisch wiederkehrende Zahlungen.');
DEFINE ('_RENEW_OFFLINE', 'Erneuern');
DEFINE ('_RENEW_BUTTON_UPGRADE', 'Ver&auml;ndern');
DEFINE ('_PAYMENT_PENDING_REASON_ECHECK', 'echeck uncleared (1-4 business days)');
DEFINE ('_PAYMENT_PENDING_REASON_TRANSFER', 'awaiting transfer payment');

// --== EXPIRATION PAGE ==--
DEFINE ('_EXPIRE_INFO', 'Ihr Konto ist aktiv bis');
DEFINE ('_RENEW_BUTTON', 'Erneuern');
DEFINE ('_ACCT_DATE_FORMAT', '%m-%d-%Y');
DEFINE ('_EXPIRED', 'Ihr Abonnement ist ausgelaufen. Ende des letzten Abonnements: ');
DEFINE ('_EXPIRED_TRIAL', 'Ihre Testphase ist ausgelaufen. Ende der Testphase: ');
DEFINE ('_ERRTIMESTAMP', 'Kann Zeitstempel nicht &auml;ndern.');
DEFINE ('_EXPIRED_TITLE', 'Konto ausgelaufen!!');
DEFINE ('_DEAR', 'Sehr geehrte(r) ');

// --== CONFIRMATION FORM ==--
DEFINE ('_CONFIRM_TITLE', 'Best&auml;tigungs Formular');
DEFINE ('_CONFIRM_COL1_TITLE', 'Ihr Konto');
DEFINE ('_CONFIRM_COL2_TITLE', 'Detail');
DEFINE ('_CONFIRM_COL3_TITLE', 'Preis');
DEFINE ('_CONFIRM_ROW_NAME', 'Name: ');
DEFINE ('_CONFIRM_ROW_USERNAME', 'Benutzername: ');
DEFINE ('_CONFIRM_ROW_EMAIL', 'E-mail:');
DEFINE ('_CONFIRM_INFO', 'Benutzen Sie bitte den Best&auml;tigen-Button um Ihre Bestellung abzuschlie&szlig;en.');
DEFINE ('_BUTTON_CONFIRM', 'Best&auml;tigen');
DEFINE ('_CONFIRM_TOS', "Ich habe die <a href=\"%s\" target=\"_BLANK\">AGB</a> gelesen und akzeptiert.");
DEFINE ('_CONFIRM_TOS_ERROR', 'Sie m&uuml;ssen unsere AGB lesen und akzeptieren');
DEFINE ('_CONFIRM_COUPON_INFO', 'If you have a coupon code, you can enter it on the Checkout Page to get a rebate on your payment');

// --== CHECKOUT FORM ==--
DEFINE ('_CHECKOUT_TITLE', 'Auschecken');
DEFINE ('_CHECKOUT_INFO', 'Ihr Eintrag wurde nun gespeichert. Es ist erforderlich, dass Sie mit der Bezahlung ihrer Auswahl fortfahren.<br />Falls dabei etwas schief l&auml;uft, k&ouml;nnen Sie immer zu dieser Seite zur&uuml;ckkehren, indem Sie sich mit ihrem Konto einw&auml;hlen.');
DEFINE ('_CHECKOUT_INFO_REPEAT', 'Willkommen zur&uuml;ck! Es ist erforderlich, dass Sie mit der Bezahlung ihrer Auswahl fortfahren .<br />Falls dabei etwas schief l&auml;uft, k&ouml;nnen Sie immer zu dieser Seite zur&uuml;ckkehren, indem Sie sich mit ihrem Konto einw&auml;hlen.');
DEFINE ('_CHECKOUT_INFO_TRANSFER', 'Ihr Eintrag wurde nun gespeichert. Es ist erforderlich, dass Sie mit der Bezahlung ihrer Auswahl fortfahren indem sie die unten beschriebenen Schritte befolgen.<br />Falls dabei etwas schief l&auml;uft, k&ouml;nnen Sie immer zu dieser Seite zur&uuml;ckkehren, indem Sie sich mit ihrem Konto einw&auml;hlen.');
DEFINE ('_CHECKOUT_INFO_TRANSFER_REPEAT', 'Willkommen zur&uuml;ck! Es ist erforderlich, dass Sie mit der Bezahlung ihrer Auswahl fortfahren indem sie die unten beschriebenen Schritte befolgen.<br />Falls dabei etwas schief l&auml;uft, k&ouml;nnen Sie immer zu dieser Seite zur&uuml;ckkehren, indem Sie sich mit ihrem Konto einw&auml;hlen.');
DEFINE ('_BUTTON_CHECKOUT', 'Fortfahren');
DEFINE ('_BUTTON_APPEND', 'Anf&uuml;gen');
DEFINE ('_CHECKOUT_COUPON_CODE', 'Coupon Code');
DEFINE ('_CHECKOUT_INVOICE_AMOUNT', 'Rechnungsbetrag');
DEFINE ('_CHECKOUT_INVOICE_COUPON', 'Coupon');
DEFINE ('_CHECKOUT_INVOICE_COUPON_REMOVE', 'entfernen');
DEFINE ('_CHECKOUT_INVOICE_TOTAL_AMOUNT', 'Summe');
DEFINE ('_CHECKOUT_COUPON_INFO', 'Falls Sie einen Coupon-Code haben, k&ouml;nnen Sie diesen hier eingeben.');

// --== ALLOPASS SPECIFIC ==--
DEFINE ('_REGTITLE','INSCRIPTION');
DEFINE ('_ERRORCODE','Erreur de code Allopass');
DEFINE ('_FTEXTA','Le code que vous avez utilis n\'est pas valide! Pour obtenir un code valable, composez le numero de tlphone, indiqu dans une fenetre pop-up, aprs avoir clicker sur le drapeau de votre pays. Votre browser doit accepter les cookies d\'usage.<br><br>Si vous tes certain, que vous avez le bon code, attendez quelques secondes et ressayez encore une fois!<br><br>Sinon prenez note de la date et heure de cet avertissement d\'erreur et informez le Webmaster de ce problme en indiquant le code utilis.');
DEFINE ('_RECODE','Saisir de nouveau le code Allopass');

// --== REGISTRATION STEPS ==--
DEFINE ('_STEP_DATA', 'Ihre Daten');
DEFINE ('_STEP_CONFIRM', 'Best&auml;tigen');
DEFINE ('_STEP_PLAN', 'Plan w&auml;hlen');
DEFINE ('_STEP_EXPIRED', 'Abgelaufen!');

// --== NOT ALLOWED PAGE ==--
DEFINE ('_NOT_ALLOWED_HEADLINE', 'Mitgliedschaft erforderlich!');
DEFINE ('_NOT_ALLOWED_FIRSTPAR', 'Die Inhalte, die sie versuchen zu erreichen, sind nur f&uuml;r Mitglieder dieser Seite zug&auml;nglich. Falls sie also bereits registriert sind, benutzen sie bitte unseren Login um sich einzuw&auml;hlen. Falls sie sich registrieren m&ouml;chten, erhalten sie hier einen &Uuml;berblick &uuml;ber die Mitgliedschaften, die wir anbieten:');
DEFINE ('_NOT_ALLOWED_REGISTERLINK', 'Registrierungs-&Uuml;bersicht');
DEFINE ('_NOT_ALLOWED_FIRSTPAR_LOGGED', 'The Content you are trying to see is available only for members of our site who have a certain subscription. Please follow this link if you want to change your subscription: ');
DEFINE ('_NOT_ALLOWED_REGISTERLINK_LOGGED', 'Subscription Page');
DEFINE ('_NOT_ALLOWED_SECONDPAR', 'Um dieser Seite beizutreten ben&ouml;tigen sie nicht mehr als eine Minute, wir nutzen den Service von:');

// --== CANCELLED PAGE ==--
DEFINE ('_CANCEL_TITLE', 'Ergebnis der Registrierung: Abgebrochen!');
DEFINE ('_CANCEL_MSG', 'Unsere Datenverarbeitung hat die R&uuml;ckmeldung erhalten, dass Sie sich entschieden haben, die Registrierung abzubrechen. Falls Sie die Registrierung aufgrund von Problemen mit dieser Internetseite abgebrochen haben, z&ouml;gern Sie nicht, uns von ihren Problemen in Kenntnis zu setzen!');

// --== PENDING PAGE ==--
DEFINE ('_WARN_PENDING', 'Ihr Konto ist noch immer nicht vollst&auml;ndig. Sollte dies f&uuml;r l&auml;ngere Zeit so bleiben obwohl Ihre Zahlung durchgef&uuml;hrt wurde, kontaktieren sie bitte den Administrator dieser Internetseite.');
DEFINE ('_PENDING_OPENINVOICE', 'Es scheint, Sie haben eine unbezahlte Rechnung in unserer Datenbank - Falls mit der Bezahlung etwas schief gelaufen ist, k&ouml;nnen Sie diese gerne erneut in Auftrag geben:');
DEFINE ('_GOTO_CHECKOUT', 'Noch einmal zum Auschecken gehen');
DEFINE ('_GOTO_CHECKOUT_CANCEL', 'Sie k&ouml;nnen die Rechnung auch abbrechen (Sie werden dann zu einer neuen Auswahl umgeleitet):');
DEFINE ('_PENDING_NOINVOICE', 'Es scheint, Sie haben die letzte offene Rechnung in unserer Datenbank abgebrochen. Bitte benutzen Sie diesen Button um erneut zur Auswahl eines Plans zu gelangen:');
DEFINE ('_PENDING_NOINVOICE_BUTTON', 'Plan Auswahl');
DEFINE ('_PENDING_REASON_ECHECK', '(Desweiteren haben wir jedoch auch die Information, dass Sie sich entschieden haben, mit einem echeck zu bezahlen. M&ouml;glicherweise m&uuml;ssen Sie also nur warten bis dieser verarbeitet wurde - dies dauert gew&ouml;hnlich 1-4 Tage.)');
DEFINE ('_PENDING_REASON_TRANSFER', '(Desweiteren haben wir jedoch auch die Information, dass Sie sich entschieden haben, die Rechnung auf herk&ouml;mmlichem Wege zu bezahlen bezahlen. Die Verarbeitung einer solchen Zahlung kann einige Tage dauern.)');

// --== THANK YOU PAGE ==--
DEFINE ('_PENDING_TITLE', 'Account Schwebend!');
DEFINE ('_THANKYOU_TITLE', 'Vielen Dank!');
DEFINE ('_SUB_FEPARTICLE_HEAD', 'Abonnement Abgeschlossen!');
DEFINE ('_SUB_FEPARTICLE_HEAD_RENEW', 'Erneuerung ihres Abonnements Abgeschlossen!');
DEFINE ('_SUB_FEPARTICLE_LOGIN', 'Sie k&ouml;nnen sich nun einloggen.');
DEFINE ('_SUB_FEPARTICLE_THANKS', 'Vielen Dank! ');
DEFINE ('_SUB_FEPARTICLE_THANKSRENEW', 'Vielen Dank f&uuml;r ihre Treue! ');
DEFINE ('_SUB_FEPARTICLE_PROCESS', 'Wir werden ihren Auftrag nun verarbeiten. ');
DEFINE ('_SUB_FEPARTICLE_PROCESSPAY', 'Wir werden nun ihre Bezahlung abwarten. ');
DEFINE ('_SUB_FEPARTICLE_ACTMAIL', 'Sie werden eine E-Mail mit einem Aktivierungscode erhalten sobald wir ihre Anfrage verarbeitet haben. ');
DEFINE ('_SUB_FEPARTICLE_MAIL', 'Sie werden eine E-Mail erhalten sobald wir ihre Anfrage verarbeitet haben. ');

// --== COUPON ERROR MESSAGES ==--
DEFINE ('_COUPON_WARNING_AMOUNT', 'One Coupon that you have added to this invoice does not affect the next payment, so although it seems that it does not affect this invoice, it will affect a subsequent payment.');
DEFINE ('_COUPON_ERROR_PRETEXT', 'We are terribly sorry:');
DEFINE ('_COUPON_ERROR_EXPIRED', 'This coupon has expired.');
DEFINE ('_COUPON_ERROR_NOTSTARTED', 'Using this coupon is not permitted yet.');
DEFINE ('_COUPON_ERROR_NOTFOUND', 'This coupon code could not be found.');
DEFINE ('_COUPON_ERROR_MAX_REUSE', 'This coupon has exceeded the maximum uses.');
DEFINE ('_COUPON_ERROR_PERMISSION', 'You don\'t have the permission to use this coupon.');
DEFINE ('_COUPON_ERROR_WRONG_USAGE', 'You can not use this coupon for this.');
DEFINE ('_COUPON_ERROR_WRONG_PLAN', 'You are not in the correct Subscription Plan for this coupon.');
DEFINE ('_COUPON_ERROR_WRONG_PLAN_PREVIOUS', 'To use this coupon, your last Subscription Plan must be different.');
DEFINE ('_COUPON_ERROR_WRONG_PLANS_OVERALL', 'You don\'t have the right Subscription Plans in your usage history to be allowed to use this coupon.');
DEFINE ('_COUPON_ERROR_TRIAL_ONLY', 'You may only use this coupon for a Trial Period.');

// ----======== EMAIL TEXT ========----

DEFINE ('_ACCTEXP_SEND_MSG','Abonnement: %s bei %s');
DEFINE ('_ACCTEXP_SEND_MSG_RENEW','Erneuerung eines Abonnements: %s bei %s');
DEFINE ('_ACCTEXP_MAILPARTICLE_GREETING', "Hallo %s,

");
DEFINE ('_ACCTEXP_MAILPARTICLE_THANKSREG', "Vielen Dank für ihr Abonnement bei %s. ");
DEFINE ('_ACCTEXP_MAILPARTICLE_THANKSREN', "Vielen Dank für die Erneuerung ihres Abonnements bei %s. ");
DEFINE ('_ACCTEXP_MAILPARTICLE_PAYREC', "Ihre Bezahlung wurde dankend entgegengenommen. ");
DEFINE ('_ACCTEXP_MAILPARTICLE_LOGIN', "Sie k&ouml;nnen sich nun auf %s mit ihrem Benutzernamen und Passwort einw&auml;hlen. ");
DEFINE ('_ACCTEXP_MAILPARTICLE_FOOTER',"

Bitte antworten Sie nicht auf diese E-Mail da sie automatisch generiert wurde und nur der Information dient.");

DEFINE ('_ACCTEXP_ASEND_MSG',"Hello %s,

A new user has created a subscription at %s.

The details for this user are:

Name - %s
e-mail - %s
Username - %s

Please do not respond to this message as it is automatically generated and is for information purposes only.");
DEFINE ('_ACCTEXP_ASEND_MSG_RENEW',"Hello %s,

A user has renewed his subscription at %s.

The details for this user are:

Name - %s
e-mail - %s
Username - %s

Please do not respond to this message as it is automatically generated and is for information purposes only.");
?>