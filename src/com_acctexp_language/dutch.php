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

define( '_AEC_EXPIRE_TODAY',				'Dit account is geldig tot vandaag' );
define( '_AEC_EXPIRE_FUTURE',				'Dit account is geldig tot' );
define( '_AEC_EXPIRE_PAST',					'Dit account was geldig tot' );
define( '_AEC_DAYS_ELAPSED',				'dag(en) verstreken');

define( '_AEC_EXPIRE_NOT_SET',				'Not Set' );
define( '_AEC_GEN_ERROR',					'<h1>Algemene foutmelding</h1><p>Er zijn problemen met het uitvoeren van je aanvraag. Neem contact op met de webmaster.</p>' );

// payments
define( '_AEC_PAYM_METHOD_FREE',			'Gratis' );
define( '_AEC_PAYM_METHOD_NONE',			'Geen' );
define( '_AEC_PAYM_METHOD_TRANSFER',		'Transfer' );

// processor errors
define( '_AEC_MSG_PROC_INVOICE_FAILED_SH',			'Factuurbetaling mislukt' );
define( '_AEC_MSG_PROC_INVOICE_FAILED_EV',			'Proces %s notificatie voor %s is mislukt - Factuurnummer bestaat niet:' );
define( '_AEC_MSG_PROC_INVOICE_ACTION_SH',			'Factuurbetaling Actie' );
define( '_AEC_MSG_PROC_INVOICE_ACTION_EV',			'Betalingsnotificatie Parser geeft aan:' );
define( '_AEC_MSG_PROC_INVOICE_ACTION_EV_STATUS',	'Factuur status:' );
define( '_AEC_MSG_PROC_INVOICE_ACTION_EV_FRAUD',	'Bedrag verificatie mislukt, betaald: %s, factuur: %s - betaling afgebroken' );
define( '_AEC_MSG_PROC_INVOICE_ACTION_EV_CURR',		'Valuta verificatie mislukt, betaald %s, factuur: %s - betalingt afgebroken' );
define( '_AEC_MSG_PROC_INVOICE_ACTION_EV_VALID',	'Geldige betaling, Actie uitgevoerd' );
define( '_AEC_MSG_PROC_INVOICE_ACTION_EV_TRIAL',	'Geldige betaling - Gratis proefabonnement' );
define( '_AEC_MSG_PROC_INVOICE_ACTION_EV_PEND',		'Ongeldige betaling - status is pending, reden: %s' );
define( '_AEC_MSG_PROC_INVOICE_ACTION_EV_CANCEL',	'Geen Betaling - Aanvraag geannuleerd' );
define( '_AEC_MSG_PROC_INVOICE_ACTION_EV_USTATUS',	', Gebruikerstatus is gewijzigd naar \'Afgebroken\'' );
define( '_AEC_MSG_PROC_INVOICE_ACTION_EV_EOT',		'Geen Betaling - Abonnementstermijn verlopen' );
define( '_AEC_MSG_PROC_INVOICE_ACTION_EV_DUPLICATE','No Payment - Duplicate' );
define( '_AEC_MSG_PROC_INVOICE_ACTION_EV_U_ERROR',	'Onbekende foutmelding' );
define( '_AEC_MSG_PROC_INVOICE_ACTION_EV_REFUND',	'No Payment - Subscription Deleted (refund)' );
define( '_AEC_MSG_PROC_INVOICE_ACTION_EV_EXPIRED',	', User has been expired' );

// --== COUPON INFO ==--
define( '_COUPON_INFO',						'Coupons:' );
define( '_COUPON_INFO_CONFIRM',				'Indien je meerdere coupons wilt gebruiken voor deze betaling, kun je dit doen op de checkout pagina.' );
define( '_COUPON_INFO_CHECKOUT',			'Voer hier je couponcode in en selecteer de button om de betaling uit te voeren.' );

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
define( '_HISTORY_COL6_TITLE', 'Abonnement');
define( '_HISTORY_ACTION_REPEAT', 'Betaal');
define( '_HISTORY_ACTION_CANCEL', 'Annuleer');
define( '_RENEW_LIFETIME', 'Je hebt een abonnement voor het leven.');
define( '_RENEW_DAYSLEFT', 'Resterende dagen');
define( '_RENEW_DAYSLEFT_EXCLUDED', 'Afloop abonnement is niet van toepassing');
define( '_RENEW_DAYSLEFT_INFINITE', '&#8734');
define( '_RENEW_INFO', 'Je maakt gebruik van automatische betalingen.');
define( '_RENEW_OFFLINE', 'Vernieuwen');
define( '_RENEW_BUTTON_UPGRADE', 'Opwaarderen');
define( '_PAYMENT_PENDING_REASON_ECHECK', 'echeck nog open (1-4 werkdagen)');
define( '_PAYMENT_PENDING_REASON_TRANSFER', 'In afwachting van betalingsverwerking');
define( '_YOUR_SUBSCRIPTION', 'Your Subscription');
define( '_YOUR_FURTHER_SUBSCRIPTION', 'Further Subscriptions');
define( '_PLAN_PROCESSOR_ACTIONS', 'For this, you have the following options:');

// --== EXPIRATION PAGE ==--
define( '_EXPIRE_INFO', 'Je abonnement is geldig tot ');
define( '_RENEW_BUTTON', 'Nu vernieuwen');
define( '_ACCT_DATE_FORMAT', '%d-%m-%Y');
define( '_EXPIRED', 'Je abonnement is verlopen op: ');
define( '_EXPIRED_TRIAL', 'Je proefabonnement is verlopen op: ');
define( '_ERRTIMESTAMP', 'Cannot convert timestamp.');
define( '_EXPIRED_TITLE', 'Abonnement verlopen!!');
define( '_DEAR', 'Beste %s');

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
define( '_CONFIRM_TOS', "Ik ga akkoord met de <a href=\"%s\" target=\"_BLANK\">Algemene Voorwaarden</a>");
define( '_CONFIRM_TOS_ERROR', 'U dient akkoord te gaan met de Algemene Voorwaarden');
define( '_CONFIRM_COUPON_INFO', 'Indien u een couponcode bezit, kunt u deze invoeren op de Checkout Pagina voor een korting op uw betaling');
define( '_CONFIRM_FREETRIAL', 'Free Trial');

// --== CHECKOUT FORM ==--
define( '_CHECKOUT_TITLE', 'Checkout');
define( '_CHECKOUT_INFO', 'Your Registration has been saved now. On this page, you can complete your payment. <br /> If something goes wrong along the way, you can always come back to this step by logging in to our site with your new details - Our System will give you an option to try your payment again.');
define( '_CHECKOUT_INFO_REPEAT', 'Thank you for coming back. On this page, you can complete your payment. <br /> If something goes wrong along the way, you can always come back to this step by logging in to our site with your new details - Our System will give you an option to try your payment again.');
define( '_BUTTON_CHECKOUT', 'Checkout');
define( '_BUTTON_APPEND', 'Append');
define( '_CHECKOUT_COUPON_CODE', 'Coupon Code');
define( '_CHECKOUT_INVOICE_AMOUNT', 'Factuurbedrag');
define( '_CHECKOUT_INVOICE_COUPON', 'Coupon');
define( '_CHECKOUT_INVOICE_COUPON_REMOVE', 'Verwijder');
define( '_CHECKOUT_INVOICE_TOTAL_AMOUNT', 'Totaal Bedrag');
define( '_CHECKOUT_COUPON_INFO', 'Indien u een couponcode bezit, kunt u deze invoeren op de Checkout Pagina voor een korting op uw betaling');

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
define( '_NOT_ALLOWED_FIRSTPAR', 'Je probeert toegang te krijgen tot een pagina op deze website die alleen bestemd is voor abonnees. Wanneer je abonnee bent, moet je eerst inloggen om verder te kunnen. Klik op deze link om nu abonnee te worden: ');
define( '_NOT_ALLOWED_REGISTERLINK', 'Registratie Pagina');
define( '_NOT_ALLOWED_FIRSTPAR_LOGGED', 'Je probeert toegang te krijgen tot een pagina op deze website die alleen bestemd is voor abonnees. Wanneer je abonnee bent, moet je eerst inloggen om verder te kunnen. Klik op deze link om nu abonnee te worden: ');
define( '_NOT_ALLOWED_REGISTERLINK_LOGGED', 'Registratie Pagina');
define( '_NOT_ALLOWED_SECONDPAR', 'Abonneren kost je minder dan een minuut van je tijd - onze digitale abonnee service wordt verzorgd door ');

// --== CANCELLED PAGE ==--
define( '_CANCEL_TITLE', 'Abonnementregistratie: geannuleerd!');
define( '_CANCEL_MSG', 'We hebben bericht ontvangen van je beslissing om je abonnement te annuleren. Indien de annulatie het gevolg is van technische problemen op de site, aarzel dan niet om onmiddellijk contact met ons op te nemen!');

// --== PENDING PAGE ==--
define( '_WARN_PENDING', 'Je abonnement wordt momenteel geverifieerd. Dit mag niet langer dan uiterlijk 30 minuten duren - meestal duurt deze fase minder dan een minuut. Wanneer je na 30 minuten nog altijd niet kunt inloggen op de site en je wel een betalingsbevestiging hebt ontvangen, neem dan contact op met de webmaster van deze website.');
define( '_WARN_PENDING', 'Je abonnement wordt momenteel geverifieerd. Indien je betaling al is verwerkt en is goedgekeurd, neem dan contact op met de webmaster.');
define( '_PENDING_OPENINVOICE', 'U heeft een openstaande factuur in onzer database - Indien er zich tijdens de betaling een fout heeft voorgedaan, kunt u terugkeren naar de checkout pagina en het opnieuw proberen:');
define( '_GOTO_CHECKOUT', 'Ga opnieuw naar de checkout pagina');
define( '_GOTO_CHECKOUT_CANCEL', 'U kunt uw betaling annuleren (U heeft altijd de mogelijkheid om terug te keren naar de pagina met de abonnementsvormen):');
define( '_PENDING_NOINVOICE', 'U heeft uw enige factuur voor uw account geannuleerd. Gebruik de onderstaande button om terug te gaan naar de pagina met abonnementsvormen:');
define( '_PENDING_NOINVOICE_BUTTON', 'Abonnement Selectie');
define( '_PENDING_REASON_ECHECK', '(Volgens onze informatie wilt u betalen met een echeck, het kan enkele dagen duren voordat deze betaling wordt verwerkt - gebruikelijk duurt dit 1-4 werkdagen.)');
define( '_PENDING_REASON_TRANSFER', '(Volgens onze informatie heeft u besloten te betalen via een offline methode (bankoverschrijving), Nadat deze betaling is verwerkt kunt u gegevens benaderen - waarschijnlijk binnen enkele werkdagen.)');

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

// --== CHECKOUT ERROR PAGE ==--
define( '_CHECKOUT_ERROR_TITLE', 'Error while processing the payment!');
define( '_CHECKOUT_ERROR_EXPLANATION', 'An error occured while processing your payment');
define( '_CHECKOUT_ERROR_OPENINVOICE', 'This leaves your invoice uncleared. To retry the payment, you can go to the checkout page once again to try again:');

// --== COUPON ERROR MESSAGES ==--
define( '_COUPON_WARNING_AMOUNT', 'De coupon die u aan deze factuur toevoegt heeft geen invloed op een eventuele volgende betaling, Voor deze factuur heeft dit geen gevolgen, echter wel voor een volgend abonnement.');
define( '_COUPON_ERROR_PRETEXT', 'Het spijt ons:');
define( '_COUPON_ERROR_EXPIRED', 'Deze coupon is verlopen.');
define( '_COUPON_ERROR_NOTSTARTED', 'Deze coupon kan nog niet gebruikt worden.');
define( '_COUPON_ERROR_NOTFOUND', 'Deze coupon code is onbekend.');
define( '_COUPON_ERROR_MAX_REUSE', 'Deze coupon heeft zijn maximum bereikt.');
define( '_COUPON_ERROR_PERMISSION', 'U bent niet geautoriseerd om deze coupon te gebruiken.');
define( '_COUPON_ERROR_WRONG_USAGE', 'Hiervoor kunt u deze coupon niet gebruiken.');
define( '_COUPON_ERROR_WRONG_PLAN', 'U gebruikt de verkeerde abonnementsvorm voor deze coupon.');
define( '_COUPON_ERROR_WRONG_PLAN_PREVIOUS', 'Om deze coupon te gebruiken moet uw laatste abonnementsvorm verschillend zijn.');
define( '_COUPON_ERROR_WRONG_PLANS_OVERALL', 'Uw gebruikersgeschiedenis beschikt niet over de juiste abonnementsvormen om deze coupon te gebruiken.');
define( '_COUPON_ERROR_TRIAL_ONLY', 'U kunt deze coupon alleen gebruiken voor een proefabonnement.');
define( '_COUPON_ERROR_COMBINATION', 'You cannot use this coupon with one of the other coupons.');
define( '_COUPON_ERROR_SPONSORSHIP_ENDED', 'Sponsorship for this Coupon has ended or is currently inactive.');

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

define( '_ACCTEXP_ASEND_MSG',				"Beste %s,\n\neen nieuwe gebruiker heeft een abonnement afgesloten op [ %s ].\n\nDe details voor deze abonnee zijn als volgt:\n\nNaam..........: %s\nEmail.........: %s\nGebruikersnaam: %s\nSubscr.-ID....: %s\nSubscription..: %s\nIP............: %s\nISP...........: %s\n\nStuur alsjeblieft geen antwoord naar aanleiding van dit bericht. Dit is een informatieve tekst die automatisch gegenereerd is door het systeem. Antwoorden worden niet door echte personen gelezen..." );
define( '_ACCTEXP_ASEND_MSG_RENEW',			"Beste %s,\n\na user has renewed his subscription at [ %s ].\n\nDe details voor deze abonnee zijn als volgt:\n\nNaam..........: %s\nEmail.........: %s\nGebruikersnaam: %s\nSubscr.-ID....: %s\nSubscription..: %s\nIP............: %s\nISP...........: %s\n\nStuur alsjeblieft geen antwoord naar aanleiding van dit bericht. Dit is een informatieve tekst die automatisch gegenereerd is door het systeem. Antwoorden worden niet door echte personen gelezen..." );
define( '_AEC_ASEND_MSG_NEW_REG',			"Beste %s,\n\nThere has been a new registration at [ %s ].\n\nHere further details:\n\nNaam..........: %s\nEmail: %s\nGebruikersnaam.........: %s\nIP............: %s\nISP...........: %s\n\nPlease do not respond to this message as it is automatically generated and is for information purposes only." );
?>