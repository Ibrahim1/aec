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
// Dont allow direct linking
defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );

// ----======== FRONTEND TEXT ========----

// --== PAYMENT PLANS PAGE ==--
DEFINE ('_NOPLANS_ERROR', 'Er zijn geen abonnementsvormen beschikbaar. Neem alsjeblieft contact op met de webmaster.');

// --== ACCOUNT DETAILS PAGE ==--
DEFINE ('_CHK_USERNAME_AVAIL', "Gebruikersnaam %s is beschikbaar");
DEFINE ('_CHK_USERNAME_NOTAVAIL', "Gebruikersnaam %s is al vergeven!");

// --== MY SUBSCRIPTION PAGE ==--
DEFINE ('_HISTORY_TITLE', 'Abonnementsgeschiedenis - Laatste 10 betalingen');
DEFINE ('_HISTORY_SUBTITLE', 'Abonnee sinds ');
DEFINE ('_HISTORY_COL1_TITLE', 'Factuur');
DEFINE ('_HISTORY_COL2_TITLE', 'Bedrag');
DEFINE ('_HISTORY_COL3_TITLE', 'Datum betaling');
DEFINE ('_HISTORY_COL4_TITLE', 'Methode');
DEFINE ('_HISTORY_COL5_TITLE', 'Action');
DEFINE ('_HISTORY_COL6_TITLE', 'Plan');
DEFINE ('_HISTORY_ACTION_REPEAT', 'pay');
DEFINE ('_HISTORY_ACTION_CANCEL', 'cancel');
DEFINE ('_RENEW_LIFETIME', 'Je hebt een abonnement voor het leven.');
DEFINE ('_RENEW_DAYSLEFT', 'Resterende dagen');
DEFINE ('_RENEW_DAYSLEFT_EXCLUDED', 'You are excluded from expiration');
DEFINE ('_RENEW_DAYSLEFT_INFINITE', '&#8734');
DEFINE ('_RENEW_INFO', 'Je maakt gebruik van automatische betalingen.');
DEFINE ('_RENEW_OFFLINE', 'Vernieuwen');
DEFINE ('_RENEW_BUTTON_UPGRADE', 'Opwaarderen');
DEFINE ('_PAYMENT_PENDING_REASON_ECHECK', 'echeck uncleared (1-4 business days)');
DEFINE ('_PAYMENT_PENDING_REASON_TRANSFER', 'awaiting transfer payment');

// --== EXPIRATION PAGE ==--
DEFINE ('_EXPIRE_INFO', 'Je abonnement is geldig tot ');
DEFINE ('_RENEW_BUTTON', 'Nu Vernieuwen');
DEFINE ('_ACCT_DATE_FORMAT', '%d-%m-%Y');
DEFINE ('_EXPIRED', 'Je abonnement is verlopen op: ');
DEFINE ('_EXPIRED_TRIAL', 'Your trial period has expired on: ');
DEFINE ('_ERRTIMESTAMP', 'Cannot convert timestamp.');
DEFINE ('_EXPIRED_TITLE', 'Abonnement verlopen!!');
DEFINE ('_DEAR', 'Beste ');

// --== CONFIRMATION FORM ==--
DEFINE ('_CONFIRM_TITLE', 'Bevestigingsformulier');
DEFINE ('_CONFIRM_COL1_TITLE', 'Rekening');
DEFINE ('_CONFIRM_COL2_TITLE', 'Details');
DEFINE ('_CONFIRM_COL3_TITLE', 'Bedrag');
DEFINE ('_CONFIRM_ROW_NAME', 'Naam: ');
DEFINE ('_CONFIRM_ROW_USERNAME', 'Gebruikersnaam: ');
DEFINE ('_CONFIRM_ROW_EMAIL', 'E-mail:');
DEFINE ('_CONFIRM_INFO', 'Klik op de vervolg knop om het registratieproces te voltooien.');
DEFINE ('_BUTTON_CONFIRM', 'Vervolg');
DEFINE ('_CONFIRM_TOS', "I have read and agree to the <a href=\"%s\" target=\"_BLANK\">Terms of Service</a>");
DEFINE ('_CONFIRM_TOS_ERROR', 'Please read and agree to our Terms of Service');
DEFINE ('_CONFIRM_COUPON_INFO', 'If you have a coupon code, you can enter it on the Checkout Page to get a rebate on your payment');

// --== CHECKOUT FORM ==--
DEFINE ('_CHECKOUT_TITLE', 'Checkout');
DEFINE ('_CHECKOUT_INFO', 'Your Registration has been saved now. To be able to use your selected features, you need to proceed to the Payment Gateway by clicking the button below. <br /> If something goes wrong along the way, you can always come back to this step by logging in to our site with your new details - Our System will give you an option to try your payment again.');
DEFINE ('_CHECKOUT_INFO_REPEAT', 'Thank you for coming back. To complete your payment, you need to proceed to the Payment Gateway by clicking the button below. <br /> If something goes wrong along the way, you can always come back to this step by logging in to our site with your new details - Our System will give you an option to try your payment again.');
DEFINE ('_CHECKOUT_INFO_TRANSFER', 'Your Registration has been saved now. To be able to use your new account, you need to follow the details below. <br /> If something goes wrong along the way, you can always come back to this step by logging in to our site with your new details - Our System will give you an option to view this page again.');
DEFINE ('_CHECKOUT_INFO_TRANSFER_REPEAT', 'Thank you for coming back. To complete your payment, you need to follow the details below. <br /> If something goes wrong along the way, you can always come back to this step by logging in to our site with your new details - Our System will give you an option to view this page again.');
DEFINE ('_BUTTON_CHECKOUT', 'Checkout');
DEFINE ('_BUTTON_APPEND', 'Append');
DEFINE ('_CHECKOUT_COUPON_CODE', 'Coupon Code');
DEFINE ('_CHECKOUT_INVOICE_AMOUNT', 'Invoice Amount');
DEFINE ('_CHECKOUT_INVOICE_COUPON', 'Coupon');
DEFINE ('_CHECKOUT_INVOICE_COUPON_REMOVE', 'remove');
DEFINE ('_CHECKOUT_INVOICE_TOTAL_AMOUNT', 'Total Amount');
DEFINE ('_CHECKOUT_COUPON_INFO', 'If you have a coupon code, you can enter it here to get a rebate on your payment');

// --== ALLOPASS SPECIFIC ==--
DEFINE ('_REGTITLE','INSCRIPTION');
DEFINE ('_ERRORCODE','Erreur de code Allopass');
DEFINE ('_FTEXTA','Le code que vous avez utilis n\'est pas valide! Pour obtenir un code valable, composez le numero de tlphone, indiqu dans une fenetre pop-up, aprs avoir clicker sur le drapeau de votre pays. Votre browser doit accepter les cookies d\'usage.<br><br>Si vous tes certain, que vous avez le bon code, attendez quelques secondes et ressayez encore une fois!<br><br>Sinon prenez note de la date et heure de cet avertissement d\'erreur et informez le Webmaster de ce problme en indiquant le code utilis.');
DEFINE ('_RECODE','Saisir de nouveau le code Allopass');

// --== REGISTRATION STEPS ==--
DEFINE ('_STEP_DATA', 'Je gegevens');
DEFINE ('_STEP_CONFIRM', 'Bevestig');
DEFINE ('_STEP_PLAN', 'Selecteer abonnementsvorm');
DEFINE ('_STEP_EXPIRED', 'Verlopen!');

// --== NOT ALLOWED PAGE ==--
DEFINE ('_NOT_ALLOWED_HEADLINE', 'Alleen voor abonnees!');
DEFINE ('_NOT_ALLOWED_FIRSTPAR', 'Je probeert toegang te krijgen tot een pagina op deze website die alleen bestemd is voor abonnees. Wanneer je abonnee bent, moet je eerst inloggen om verder te kunnen. Klik op deze link om nu abonnee te te worden: ');
DEFINE ('_NOT_ALLOWED_REGISTERLINK', 'Registratie Pagina');
DEFINE ('_NOT_ALLOWED_FIRSTPAR_LOGGED', 'The Content you are trying to see is available only for members of our site who have a certain subscription. Please follow this link if you want to change your subscription: ');
DEFINE ('_NOT_ALLOWED_REGISTERLINK_LOGGED', 'Subscription Page');
DEFINE ('_NOT_ALLOWED_SECONDPAR', 'Abonneren kost je minder dan een minuut van je tijd - onze digitale abonnee service wordt verzorgd door ');

// --== CANCELLED PAGE ==--
DEFINE ('_CANCEL_TITLE', 'Abonnementregistratie: geannuleerd!');
DEFINE ('_CANCEL_MSG', 'We hebben bericht ontvangen van je beslissing om je abonnement te annuleren. Indien de annulatie het gevolg is van technische problemen op de site, aarzel dan niet om onmiddellijk contact met ons op te nemen!');

// --== PENDING PAGE ==--
DEFINE ('_WARN_PENDING', 'Je abonnement wordt momenteel geverifieerd. Dit mag niet langer dan uiterlijk 30 minuten duren - meestal duurt deze fase minder dan een minuut. Wanneer je na 30 minuten nog altijd niet kunt inloggen op de site en je wel een betalingsbevestiging hebt ontvangen, neem dan contact op met de webmaster van deze website.');
DEFINE ('_WARN_PENDING', 'Your account is still pending. If you are in this state for more than some hours and your payment is confirmed, please contact the administrator of this web site.');
DEFINE ('_PENDING_OPENINVOICE', 'It seems that you have an uncleared invoice in our database - If something went wrong along the way, you can go to the checkout page once again to try again:');
DEFINE ('_GOTO_CHECKOUT', 'Go to the checkout page again');
DEFINE ('_GOTO_CHECKOUT_CANCEL', 'you may also cancel the payment (you will have the possibility to go to the Plan Selection screen once again):');
DEFINE ('_PENDING_NOINVOICE', 'It appears that you have cancelled the only invoice that was left for your account. Please use the button below to go to the Plan Selection page again:');
DEFINE ('_PENDING_NOINVOICE_BUTTON', 'Plan Selection');
DEFINE ('_PENDING_REASON_ECHECK', '(According to our information however, you decided to pay by echeck, so you it might be that you just have to wait until this payment is cleared - which usually takes 1-4 days.)');
DEFINE ('_PENDING_REASON_TRANSFER', '(According to our information however, you decided to pay by an offline payment means, so you it might be that you just have to wait until this payment is cleared - which can take a couple of days.)');

// --== THANK YOU PAGE ==--
DEFINE ('_THANKYOU_TITLE', 'Dank je!');
DEFINE ('_SUB_FEPARTICLE_HEAD', 'Abonnementregistratie compleet!');
DEFINE ('_SUB_FEPARTICLE_HEAD_RENEW', 'Vernieuwing Abonnement Compleet!');
DEFINE ('_SUB_FEPARTICLE_LOGIN', 'Je kunt nu inloggen.');
DEFINE ('_SUB_FEPARTICLE_THANKS', 'Dank je wel voor je registratie. ');
DEFINE ('_SUB_FEPARTICLE_THANKSRENEW', 'Dank je wel voor het vernieuwen van je registratie. ');
DEFINE ('_SUB_FEPARTICLE_PROCESS', 'Ons systeem is aan de slag gegaan met je verzoek. ');
DEFINE ('_SUB_FEPARTICLE_PROCESSPAY', 'Ons systeem is in afwachting van je betaling. ');
DEFINE ('_SUB_FEPARTICLE_ACTMAIL', 'Je ontvangt een email met een activatie link zodra ons systeem je verzoek heeft verwerkt. ');
DEFINE ('_SUB_FEPARTICLE_MAIL', 'Je ontvangt een email You will receive an e-mail zodra ons systeem je verzoek heeft verwerkt. ');

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

DEFINE ('_ACCTEXP_SEND_MSG','Abonnement voor %s op %s');
DEFINE ('_ACCTEXP_SEND_MSG_RENEW','Vernieuwing abonnement voor %s op %s');
DEFINE ('_ACCTEXP_MAILPARTICLE_GREETING', "Beste %s,

");
DEFINE ('_ACCTEXP_MAILPARTICLE_THANKSREG', "Dank je voor het nemen van een abonnement op %s. ");
DEFINE ('_ACCTEXP_MAILPARTICLE_THANKSREN', "Dank je voor het vernieuwen van je abonnement op %s. ");
DEFINE ('_ACCTEXP_MAILPARTICLE_PAYREC', "We hebben je betaling voor je abonnement ontvangen. ");
DEFINE ('_ACCTEXP_MAILPARTICLE_LOGIN', "Je kunt nu inloggen op %s met je gebruikersnaam en wachtwoord. ");
DEFINE ('_ACCTEXP_MAILPARTICLE_FOOTER',"

Stuur alsjeblieft geen antwoord naar aanleiding van dit bericht. Dit is een informatieve tekst die automatisch gegenereerd is door het systeem. Antwoorden worden niet door echte personen gelezen...");

DEFINE ('_ACCTEXP_ASEND_MSG',"Beste %s,

Een nieuwe gebruiker heeft een abonnement afgesloten op %s.

De details voor deze abonnee zijn als volgt:

Naam - %s
e-mail - %s
Gebruikersnaam - %s

Stuur alsjeblieft geen antwoord naar aanleiding van dit bericht. Dit is een informatieve tekst die automatisch gegenereerd is door het systeem. Antwoorden worden niet door echte personen gelezen...");
DEFINE ('_ACCTEXP_ASEND_MSG_RENEW',"Beste %s,

Een abonnee heeft zojuist zijn/haar abonnement vernieuwd op %s.

De details voor deze abonnee zijn als volgt:

Naam - %s
e-mail - %s
Gebruikersnaam - %s

Stuur alsjeblieft geen antwoord naar aanleiding van dit bericht. Dit is een informatieve tekst die automatisch gegenereerd is door het systeem. Antwoorden worden niet door echte personen gelezen...");
?>