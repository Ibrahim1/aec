<?php
/**
 * @version $Id: dutch.php 16 2007-06-25 09:04:04Z mic $
 * @package AEC - Account Control Expiration - Subscription component for Joomla! OS CMS
 * @subpackage Language - Frontend - Dutch
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

if( defined( '_AEC_LANG' ) ) {
	return;
}

// new 0.12.4 (mic)

define( '_AEC_EXPIRE_TODAY',				'Heute' );
define( '_AEC_EXPIRE_FUTURE',				'This account is active until' );
define( '_AEC_EXPIRE_PAST',					'This account was valid until' );
define( '_AEC_DAYS_ELAPSED',				'day(s) elapsed');

define( '_AEC_EXPIRE_NOT_SET',				'Not Set' );
define( '_AEC_GEN_ERROR',					'<h1>General Error</h1><p>We had problems processing your request. Please contact the web site administrator.</p>' );

// payments
define( '_AEC_PAYM_METHOD_FREE',			'Free' );
define( '_AEC_PAYM_METHOD_NONE',			'None' );
define( '_AEC_PAYM_METHOD_TRANSFER',		'Transfer' );

// processor errors
define( '_AEC_MSG_PROC_INVOICE_FAILED_SH',			'Failed Invoice Payment' );
define( '_AEC_MSG_PROC_INVOICE_FAILED_EV',			'Processor %s notification for %s has failed - invoice number does not exist:' );
define( '_AEC_MSG_PROC_INVOICE_ACTION_SH',			'Invoice Payment Action' );
define( '_AEC_MSG_PROC_INVOICE_ACTION_EV',			'Payment Notification Parser responds:' );
define( '_AEC_MSG_PROC_INVOICE_ACTION_EV_STATUS',	'Invoice status:' );
define( '_AEC_MSG_PROC_INVOICE_ACTION_EV_FRAUD',	'Amount verification failed, paid: %s, invoice: %s - payment aborted' );
define( '_AEC_MSG_PROC_INVOICE_ACTION_EV_CURR',		'Currency verification failed, paid %s, invoice: %s - payment aborted' );
define( '_AEC_MSG_PROC_INVOICE_ACTION_EV_VALID',	'Payment valid, Action carried out' );
define( '_AEC_MSG_PROC_INVOICE_ACTION_EV_TRIAL',	'Payment valid - free trial' );
define( '_AEC_MSG_PROC_INVOICE_ACTION_EV_PEND',		'Payment invalid - status is pending, reason: %s' );
define( '_AEC_MSG_PROC_INVOICE_ACTION_EV_CANCEL',	'No Payment - Subscription Cancel' );
define( '_AEC_MSG_PROC_INVOICE_ACTION_EV_USTATUS',	', Userstatus has been updated to \'Cancelled\'' );
define( '_AEC_MSG_PROC_INVOICE_ACTION_EV_EOT',		'No Payment - Subscription End Of Term' );
define( '_AEC_MSG_PROC_INVOICE_ACTION_EV_U_ERROR',	'Unknown Error' );

// --== COUPON INFO ==--
define( '_COUPON_INFO',						'Coupons:' );
define( '_COUPON_INFO_CONFIRM',				'If you want to use one or more coupons for this payment, you can do so on the checkout page.' );
define( '_COUPON_INFO_CHECKOUT',			'Please enter your coupon code here and click the button to append it to this payment.' );

// end mic ########################################################

// --== PAYMENT PLANS PAGE ==--
define( '_PAYPLANS_HEADER', 'Abonnementsvormen');
define( '_NOPLANS_ERROR', 'Er zijn geen abonnementsvormen beschikbaar. Neem alsjeblieft contact op met de webmaster.');

// --== ACCOUNT DETAILS PAGE ==--
define( '_CHK_USERNAME_AVAIL', "Gebruikersnaam %s is beschikbaar");
define( '_CHK_USERNAME_NOTAVAIL', "Gebruikersnaam %s is al vergeven!");

// --== MY SUBSCRIPTION PAGE ==--
define( '_HISTORY_TITLE', 'Abonnementsgeschiedenis - Laatste 10 betalingen');
define( '_HISTORY_SUBTITLE', 'Abonnee sinds ');
define( '_HISTORY_COL1_TITLE', 'Factuur');
define( '_HISTORY_COL2_TITLE', 'Bedrag');
define( '_HISTORY_COL3_TITLE', 'Datum betaling');
define( '_HISTORY_COL4_TITLE', 'Methode');
define( '_HISTORY_COL5_TITLE', 'Action');
define( '_HISTORY_COL6_TITLE', 'Plan');
define( '_HISTORY_ACTION_REPEAT', 'pay');
define( '_HISTORY_ACTION_CANCEL', 'cancel');
define( '_RENEW_LIFETIME', 'Je hebt een abonnement voor het leven.');
define( '_RENEW_DAYSLEFT', 'Resterende dagen');
define( '_RENEW_DAYSLEFT_EXCLUDED', 'You are excluded from expiration');
define( '_RENEW_DAYSLEFT_INFINITE', '&#8734');
define( '_RENEW_INFO', 'Je maakt gebruik van automatische betalingen.');
define( '_RENEW_OFFLINE', 'Vernieuwen');
define( '_RENEW_BUTTON_UPGRADE', 'Opwaarderen');
define( '_PAYMENT_PENDING_REASON_ECHECK', 'echeck uncleared (1-4 business days)');
define( '_PAYMENT_PENDING_REASON_TRANSFER', 'awaiting transfer payment');

// --== EXPIRATION PAGE ==--
define( '_EXPIRE_INFO', 'Je abonnement is geldig tot ');
define( '_RENEW_BUTTON', 'Nu Vernieuwen');
define( '_ACCT_DATE_FORMAT', '%d-%m-%Y');
define( '_EXPIRED', 'Je abonnement is verlopen op: ');
define( '_EXPIRED_TRIAL', 'Your trial period has expired on: ');
define( '_ERRTIMESTAMP', 'Cannot convert timestamp.');
define( '_EXPIRED_TITLE', 'Abonnement verlopen!!');
define( '_DEAR', 'Beste ');

// --== CONFIRMATION FORM ==--
define( '_CONFIRM_TITLE', 'Bevestigingsformulier');
define( '_CONFIRM_COL1_TITLE', 'Rekening');
define( '_CONFIRM_COL2_TITLE', 'Details');
define( '_CONFIRM_COL3_TITLE', 'Bedrag');
define( '_CONFIRM_ROW_NAME', 'Naam: ');
define( '_CONFIRM_ROW_USERNAME', 'Gebruikersnaam: ');
define( '_CONFIRM_ROW_EMAIL', 'E-mail:');
define( '_CONFIRM_INFO', 'Klik op de vervolg knop om het registratieproces te voltooien.');
define( '_BUTTON_CONFIRM', 'Vervolg');
define( '_CONFIRM_TOS', "I have read and agree to the <a href=\"%s\" target=\"_BLANK\">Terms of Service</a>");
define( '_CONFIRM_TOS_ERROR', 'Please read and agree to our Terms of Service');
define( '_CONFIRM_COUPON_INFO', 'If you have a coupon code, you can enter it on the Checkout Page to get a rebate on your payment');

// --== CHECKOUT FORM ==--
define( '_CHECKOUT_TITLE', 'Checkout');
define( '_CHECKOUT_INFO', 'Your Registration has been saved now. To be able to use your selected features, you need to proceed to the Payment Gateway by clicking the button below. <br /> If something goes wrong along the way, you can always come back to this step by logging in to our site with your new details - Our System will give you an option to try your payment again.');
define( '_CHECKOUT_INFO_REPEAT', 'Thank you for coming back. To complete your payment, you need to proceed to the Payment Gateway by clicking the button below. <br /> If something goes wrong along the way, you can always come back to this step by logging in to our site with your new details - Our System will give you an option to try your payment again.');
define( '_CHECKOUT_INFO_TRANSFER', 'Your Registration has been saved now. To be able to use your new account, you need to follow the details below. <br /> If something goes wrong along the way, you can always come back to this step by logging in to our site with your new details - Our System will give you an option to view this page again.');
define( '_CHECKOUT_INFO_TRANSFER_REPEAT', 'Thank you for coming back. To complete your payment, you need to follow the details below. <br /> If something goes wrong along the way, you can always come back to this step by logging in to our site with your new details - Our System will give you an option to view this page again.');
define( '_BUTTON_CHECKOUT', 'Checkout');
define( '_BUTTON_APPEND', 'Append');
define( '_CHECKOUT_COUPON_CODE', 'Coupon Code');
define( '_CHECKOUT_INVOICE_AMOUNT', 'Invoice Amount');
define( '_CHECKOUT_INVOICE_COUPON', 'Coupon');
define( '_CHECKOUT_INVOICE_COUPON_REMOVE', 'remove');
define( '_CHECKOUT_INVOICE_TOTAL_AMOUNT', 'Total Amount');
define( '_CHECKOUT_COUPON_INFO', 'If you have a coupon code, you can enter it here to get a rebate on your payment');

// --== ALLOPASS SPECIFIC ==--
define( '_REGTITLE','INSCRIPTION');
define( '_ERRORCODE','Erreur de code Allopass');
define( '_FTEXTA','Le code que vous avez utilis n\'est pas valide! Pour obtenir un code valable, composez le numero de tlphone, indiqu dans une fenetre pop-up, aprs avoir clicker sur le drapeau de votre pays. Votre browser doit accepter les cookies d\'usage.<br><br>Si vous tes certain, que vous avez le bon code, attendez quelques secondes et ressayez encore une fois!<br><br>Sinon prenez note de la date et heure de cet avertissement d\'erreur et informez le Webmaster de ce problme en indiquant le code utilis.');
define( '_RECODE','Saisir de nouveau le code Allopass');

// --== REGISTRATION STEPS ==--
define( '_STEP_DATA', 'Je gegevens');
define( '_STEP_CONFIRM', 'Bevestig');
define( '_STEP_PLAN', 'Selecteer abonnementsvorm');
define( '_STEP_EXPIRED', 'Verlopen!');

// --== NOT ALLOWED PAGE ==--
define( '_NOT_ALLOWED_HEADLINE', 'Alleen voor abonnees!');
define( '_NOT_ALLOWED_FIRSTPAR', 'Je probeert toegang te krijgen tot een pagina op deze website die alleen bestemd is voor abonnees. Wanneer je abonnee bent, moet je eerst inloggen om verder te kunnen. Klik op deze link om nu abonnee te te worden: ');
define( '_NOT_ALLOWED_REGISTERLINK', 'Registratie Pagina');
define( '_NOT_ALLOWED_FIRSTPAR_LOGGED', 'The Content you are trying to see is available only for members of our site who have a certain subscription. Please follow this link if you want to change your subscription: ');
define( '_NOT_ALLOWED_REGISTERLINK_LOGGED', 'Subscription Page');
define( '_NOT_ALLOWED_SECONDPAR', 'Abonneren kost je minder dan een minuut van je tijd - onze digitale abonnee service wordt verzorgd door ');

// --== CANCELLED PAGE ==--
define( '_CANCEL_TITLE', 'Abonnementregistratie: geannuleerd!');
define( '_CANCEL_MSG', 'We hebben bericht ontvangen van je beslissing om je abonnement te annuleren. Indien de annulatie het gevolg is van technische problemen op de site, aarzel dan niet om onmiddellijk contact met ons op te nemen!');

// --== PENDING PAGE ==--
define( '_WARN_PENDING', 'Je abonnement wordt momenteel geverifieerd. Dit mag niet langer dan uiterlijk 30 minuten duren - meestal duurt deze fase minder dan een minuut. Wanneer je na 30 minuten nog altijd niet kunt inloggen op de site en je wel een betalingsbevestiging hebt ontvangen, neem dan contact op met de webmaster van deze website.');
define( '_WARN_PENDING', 'Your account is still pending. If you are in this state for more than some hours and your payment is confirmed, please contact the administrator of this web site.');
define( '_PENDING_OPENINVOICE', 'It seems that you have an uncleared invoice in our database - If something went wrong along the way, you can go to the checkout page once again to try again:');
define( '_GOTO_CHECKOUT', 'Go to the checkout page again');
define( '_GOTO_CHECKOUT_CANCEL', 'you may also cancel the payment (you will have the possibility to go to the Plan Selection screen once again):');
define( '_PENDING_NOINVOICE', 'It appears that you have cancelled the only invoice that was left for your account. Please use the button below to go to the Plan Selection page again:');
define( '_PENDING_NOINVOICE_BUTTON', 'Plan Selection');
define( '_PENDING_REASON_ECHECK', '(According to our information however, you decided to pay by echeck, so you it might be that you just have to wait until this payment is cleared - which usually takes 1-4 days.)');
define( '_PENDING_REASON_TRANSFER', '(According to our information however, you decided to pay by an offline payment means, so you it might be that you just have to wait until this payment is cleared - which can take a couple of days.)');

// --== THANK YOU PAGE ==--
define( '_THANKYOU_TITLE', 'Dank je!');
define( '_SUB_FEPARTICLE_HEAD', 'Abonnementregistratie compleet!');
define( '_SUB_FEPARTICLE_HEAD_RENEW', 'Vernieuwing Abonnement Compleet!');
define( '_SUB_FEPARTICLE_LOGIN', 'Je kunt nu inloggen.');
define( '_SUB_FEPARTICLE_THANKS', 'Dank je wel voor je registratie. ');
define( '_SUB_FEPARTICLE_THANKSRENEW', 'Dank je wel voor het vernieuwen van je registratie. ');
define( '_SUB_FEPARTICLE_PROCESS', 'Ons systeem is aan de slag gegaan met je verzoek. ');
define( '_SUB_FEPARTICLE_PROCESSPAY', 'Ons systeem is in afwachting van je betaling. ');
define( '_SUB_FEPARTICLE_ACTMAIL', 'Je ontvangt een email met een activatie link zodra ons systeem je verzoek heeft verwerkt. ');
define( '_SUB_FEPARTICLE_MAIL', 'Je ontvangt een email You will receive an e-mail zodra ons systeem je verzoek heeft verwerkt. ');

// --== COUPON ERROR MESSAGES ==--
define( '_COUPON_WARNING_AMOUNT', 'One Coupon that you have added to this invoice does not affect the next payment, so although it seems that it does not affect this invoice, it will affect a subsequent payment.');
define( '_COUPON_ERROR_PRETEXT', 'We are terribly sorry:');
define( '_COUPON_ERROR_EXPIRED', 'This coupon has expired.');
define( '_COUPON_ERROR_NOTSTARTED', 'Using this coupon is not permitted yet.');
define( '_COUPON_ERROR_NOTFOUND', 'This coupon code could not be found.');
define( '_COUPON_ERROR_MAX_REUSE', 'This coupon has exceeded the maximum uses.');
define( '_COUPON_ERROR_PERMISSION', 'You don\'t have the permission to use this coupon.');
define( '_COUPON_ERROR_WRONG_USAGE', 'You can not use this coupon for this.');
define( '_COUPON_ERROR_WRONG_PLAN', 'You are not in the correct Subscription Plan for this coupon.');
define( '_COUPON_ERROR_WRONG_PLAN_PREVIOUS', 'To use this coupon, your last Subscription Plan must be different.');
define( '_COUPON_ERROR_WRONG_PLANS_OVERALL', 'You don\'t have the right Subscription Plans in your usage history to be allowed to use this coupon.');
define( '_COUPON_ERROR_TRIAL_ONLY', 'You may only use this coupon for a Trial Period.');

// ----======== EMAIL TEXT ========----

define( '_ACCTEXP_SEND_MSG','Abonnement voor %s op %s');
define( '_ACCTEXP_SEND_MSG_RENEW','Vernieuwing abonnement voor %s op %s');
define( '_ACCTEXP_MAILPARTICLE_GREETING', "Beste %s,\n\n");
define( '_ACCTEXP_MAILPARTICLE_THANKSREG', "Dank je voor het nemen van een abonnement op %s. ");
define( '_ACCTEXP_MAILPARTICLE_THANKSREN', "Dank je voor het vernieuwen van je abonnement op %s. ");
define( '_ACCTEXP_MAILPARTICLE_PAYREC', "We hebben je betaling voor je abonnement ontvangen. ");
define( '_ACCTEXP_MAILPARTICLE_LOGIN', "Je kunt nu inloggen op %s met je gebruikersnaam en wachtwoord. ");
define( '_ACCTEXP_MAILPARTICLE_FOOTER',"\n\nStuur alsjeblieft geen antwoord naar aanleiding van dit bericht. Dit is een informatieve tekst die automatisch gegenereerd is door het systeem. Antwoorden worden niet door echte personen gelezen...");
define( '_ACCTEXP_ASEND_MSG',"Beste %s,\n\nEen nieuwe gebruiker heeft een abonnement afgesloten op %s.\n\nDe details voor deze abonnee zijn als volgt:\n\nNaam - %s\ne-mail - %s\nGebruikersnaam - %s\n\nStuur alsjeblieft geen antwoord naar aanleiding van dit bericht. Dit is een informatieve tekst die automatisch gegenereerd is door het systeem. Antwoorden worden niet door echte personen gelezen...");
define( '_ACCTEXP_ASEND_MSG_RENEW',"Beste %s,\n\nEen abonnee heeft zojuist zijn/haar abonnement vernieuwd op %s.\n\nDe details voor deze abonnee zijn als volgt:\n\nNaam - %s\ne-mail - %s\nGebruikersnaam - %s\n\nStuur alsjeblieft geen antwoord naar aanleiding van dit bericht. Dit is een informatieve tekst die automatisch gegenereerd is door het systeem. Antwoorden worden niet door echte personen gelezen...");
?>