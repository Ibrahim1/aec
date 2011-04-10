<?php
/**
 * @version $Id: dutch.php
 * @package AEC - Account Control Expiration - Membership Manager
 * @subpackage Language - Frontend - Dutch
 * @copyright 2006-2010 Copyright (C) David Deutsch
 * @author David Deutsch <skore@skore.de> & Team AEC - http://www.valanx.org & Jarno en Mark Baselier from Q5 Grafisch Webdesign
 * @license GNU/GPL v.2 http://www.gnu.org/licenses/old-licenses/gpl-2.0.html or, at your option, any later version
 */

// Dont allow direct linking
( defined('_JEXEC') || defined( '_VALID_MOS' ) ) or die( 'Direct Access to this location is not allowed.' );

if( defined( '_AEC_LANG' ) ) {
	return;
}

// new 0.12.4 - mic

define( '_AEC_EXPIRE_TODAY',				'Dit account is actief tot: vandaag' );
define( '_AEC_EXPIRE_FUTURE',				'Dit account is actief tot' );
define( '_AEC_EXPIRE_PAST',					'Dit account was actief tot' );
define( '_AEC_DAYS_ELAPSED',				'dag(en) verlopen');
define( '_AEC_EXPIRE_TRIAL_TODAY',			'De proef periode is geldig tot vandaag' );
define( '_AEC_EXPIRE_TRIAL_FUTURE',			'De proef periode is geldig tot' );
define( '_AEC_EXPIRE_TRIAL_PAST',			'De proef periode was geldig tot' );

define( '_AEC_EXPIRE_NOT_SET',				'Niet ingesteld' );
define( '_AEC_GEN_ERROR',					'<h1>Algemene foutmelding</h1><p>Er zijn problemen om uw aanvraag te verwerken. Contact de website administrator.</p>' );

// betalingen
define( '_AEC_PAYM_METHOD_FREE',			'Gratis' );
define( '_AEC_PAYM_METHOD_NONE',			'Geen' );
define( '_AEC_PAYM_METHOD_TRANSFER',		'Verzenden' );

// processor meldingen
define( '_AEC_MSG_PROC_INVOICE_FAILED_SH',			'Mislukte betaling' );
define( '_AEC_MSG_PROC_INVOICE_FAILED_EV',			'Processor %s melding voor %s is mislukt - factuurnummer bestaat niet:' );
define( '_AEC_MSG_PROC_INVOICE_ACTION_SH',			'Factuur betalings actie' );
define( '_AEC_MSG_PROC_INVOICE_ACTION_EV',			'Betalingsmethode Parser vermeld:' );
define( '_AEC_MSG_PROC_INVOICE_ACTION_EV_STATUS',	'Factuur status:' );
define( '_AEC_MSG_PROC_INVOICE_ACTION_EV_FRAUD',	'Bedrag verificatie mislukt, betaald: %s, factuur: %s - betaling geannuleerd' );
define( '_AEC_MSG_PROC_INVOICE_ACTION_EV_CURR',		'Bedrag verificatie mislukt, betaald %s, factuur: %s - betaling geannuleerd' );
define( '_AEC_MSG_PROC_INVOICE_ACTION_EV_VALID',	'Betaling gelukt, Actie uitgevoerd' );
define( '_AEC_MSG_PROC_INVOICE_ACTION_EV_VALID_APPFAIL',	'Payment valid, Application failed!' );
define( '_AEC_MSG_PROC_INVOICE_ACTION_EV_TRIAL',	'Betaling geldig - gratis trail actief' );
define( '_AEC_MSG_PROC_INVOICE_ACTION_EV_PEND',		'Betaling niet geldig - Status is nog in de wachtrij, De reden hiervan is: %s' );
define( '_AEC_MSG_PROC_INVOICE_ACTION_EV_CANCEL',	'Geen betaling - Aanmelding geannuleerd' );
define( '_AEC_MSG_PROC_INVOICE_ACTION_EV_CHARGEBACK',	'No Payment - Chargeback' );
define( '_AEC_MSG_PROC_INVOICE_ACTION_EV_CHARGEBACK_SETTLE',	'No Payment - Chargeback Settlement' );
define( '_AEC_MSG_PROC_INVOICE_ACTION_EV_USTATUS',	', Gebruikersstatus is geupdate naar \'Cancelled\'' );
define( '_AEC_MSG_PROC_INVOICE_ACTION_EV_USTATUS_HOLD',	', Userstatus has been updated to \'Hold\'' );
define( '_AEC_MSG_PROC_INVOICE_ACTION_EV_USTATUS_ACTIVE',	', Userstatus has been updated to \'Active\'' );
define( '_AEC_MSG_PROC_INVOICE_ACTION_EV_EOT',		'Geen betaling' );
define( '_AEC_MSG_PROC_INVOICE_ACTION_EV_DUPLICATE','Geen betaling - Dubbele input' );
define( '_AEC_MSG_PROC_INVOICE_ACTION_EV_NULL','No Payment - Null' );
define( '_AEC_MSG_PROC_INVOICE_ACTION_EV_U_ERROR',	'Onbekende fout' );
define( '_AEC_MSG_PROC_INVOICE_ACTION_EV_REFUND',	'No Payment - Subscription Deleted (refund)' );
define( '_AEC_MSG_PROC_INVOICE_ACTION_EV_EXPIRED',	', User has been expired' );

// --== COUPON INFO ==--
define( '_COUPON_INFO',						'Coupons:' );
define( '_COUPON_INFO_CONFIRM',				'Indien je meerdere coupons wilt gebruiken voor deze betaling, kun je dit doen op de checkout pagina.' );
define( '_COUPON_INFO_CHECKOUT',			'Voer hier je couponcode in en selecteer de button om de betaling uit te voeren.' );

// end mic ########################################################

// --== BETAAL PLANNEN PAGINA ==--
define( '_PAYPLANS_HEADER', 'Betalingsplannen');
define( '_NOPLANS_ERROR', 'Geen betalingsplan beschikbaar. Neem contact op met de administrator.');
define( '_NOPLANS_AUTHERROR', 'You are not authorized to access this option. Please contact administrator if you have any further questions.');
define( '_PLANGROUP_BACK', '&lt; ga terug');

// --== ACCOUNT DETAILS PAGINA ==--
define( '_CHK_USERNAME_AVAIL', "Gebruikersnaam %s is nog beschikbaar");
define( '_CHK_USERNAME_NOTAVAIL', "Gebruikersnaam %s is al in gebruik!");

// --== MIJN ABONNEMENTEN PAGINA ==--
define( '_MYSUBSCRIPTION_TITLE', 'Mijn abonnement');
define( '_MEMBER_SINCE', 'Lid sinds');
define( '_HISTORY_COL1_TITLE', 'Factuur');
define( '_HISTORY_COL2_TITLE', 'Bedrag');
define( '_HISTORY_COL3_TITLE', 'Betalingsdatum');
define( '_HISTORY_COL4_TITLE', 'Methode');
define( '_HISTORY_COL5_TITLE', 'Actie');
define( '_HISTORY_COL6_TITLE', 'Plan');
define( '_HISTORY_ACTION_REPEAT', 'betalen');
define( '_HISTORY_ACTION_CANCEL', 'annuleren');
define( '_RENEW_LIFETIME', 'Uw account verloopt nooit.');
define( '_RENEW_DAYSLEFT', 'Resterende dagen');
define( '_RENEW_DAYSLEFT_TRIAL', 'dagen over van testperiode');
define( '_RENEW_DAYSLEFT_EXCLUDED', 'Uw account zal niet verlopen');
define( '_RENEW_DAYSLEFT_INFINITE', '&#8734');
define( '_RENEW_INFO', 'U betaald d.m.v. terugkerende betalingen.');
define( '_RENEW_OFFLINE', 'Vernieuwen');
define( '_RENEW_BUTTON_UPGRADE', 'Upgraden/Vernieuwen');
define( '_PAYMENT_PENDING_REASON_ECHECK', 'Echeck nog niet ontvangen (1-4 werkdagen)');
define( '_PAYMENT_PENDING_REASON_TRANSFER', 'In afwachting van betaling');
define( '_YOUR_SUBSCRIPTION', 'Uw Abonnement');
define( '_YOUR_FURTHER_SUBSCRIPTIONS', 'Andere Abonnementen');
define( '_PLAN_PROCESSOR_ACTIONS', 'Voor dit heeft u de volgende opties:');
define( '_AEC_SUBDETAILS_TAB_OVERVIEW', 'Overview');
define( '_AEC_SUBDETAILS_TAB_INVOICES', 'Facturen');
define( '_AEC_SUBDETAILS_TAB_DETAILS', 'Details');

define( '_HISTORY_ACTION_PRINT', 'print');
define( '_INVOICEPRINT_DATE', 'Datum');
define( '_INVOICEPRINT_ID', 'ID');
define( '_INVOICEPRINT_REFERENCE_NUMBER', 'Referentie nummer');
define( '_INVOICEPRINT_ITEM_NAME', 'Product naam');
define( '_INVOICEPRINT_UNIT_PRICE', 'Prijs');
define( '_INVOICEPRINT_QUANTITY', 'Hoeveelheid');
define( '_INVOICEPRINT_TOTAL', 'Subotaal');
define( '_INVOICEPRINT_GRAND_TOTAL', 'Totaal');

define( '_INVOICEPRINT_ADDRESSFIELD', 'Voer uw adres hier in - dit zal te zien zijn bij het afdrukken');
define( '_INVOICEPRINT_PRINT', 'Printen');
define( '_INVOICEPRINT_BLOCKNOTICE', 'Dit blok zal niet te zien zijn bij het afdrukken');
define( '_INVOICEPRINT_PRINT_TYPEABOVE', 'Typ uw adres in het bovenstaande vak');
define( '_INVOICEPRINT_PAIDSTATUS_UNPAID', '<strong>Deze factuur is nog niet betaald.</strong>');
define( '_INVOICEPRINT_PAIDSTATUS_CANCEL', '<strong>This payment was canceled.</strong>');
define( '_INVOICEPRINT_PAIDSTATUS_PAID', 'Deze factuur is betaald op: %s');
define( '_INVOICEPRINT_RECURRINGSTATUS_RECURRING', 'This invoice is billed on a recurring basis. The invoice amount listing may represent that of the next billing cycle, not of the one that has been paid for last. The list of payments above clarifies what has been paid and when.');
define( '_INVOICEPRINT_RECURRINGSTATUS_ONCE', 'This invoice involves multiple, separate, payments. The invoice amount listing may represent that of the next billing cycle, not of the one that has been paid for last. The list of payments above clarifies what has been paid and when.');

define( '_AEC_YOUSURE', 'Weet u het zeker?');

define( '_AEC_WILLEXPIRE', 'Dit lidmaatschap zal vervallen');
define( '_AEC_WILLRENEW', 'Dit lidmaatschap zal vernieuwen');
define( '_AEC_ISLIFETIME', 'Levenslange lidmaatschap');

// --== VERVAL PAGINA ==--
define( '_EXPIRE_INFO', 'Uw account is actief tot');
define( '_RENEW_BUTTON', 'Nu vernieuwen');
define( '_RENEW_BUTTON_CONTINUE', 'Vernieuw bestaand lidmaatschap');
define( '_ACCT_DATE_FORMAT', '%d-%m-%Y');
define( '_EXPIRED', 'Uw account is beeindigd op: ');
define( '_EXPIRED_TRIAL', 'Uw proef periode is geeindigd op: ');
define( '_ERRTIMESTAMP', 'Kan de datum niet converteren.');
define( '_EXPIRED_TITLE', 'Account is beeindigd!');
define( '_DEAR', 'Geachte %s');

// --== BEVESTIGINGS FORMULIER ==--
define( '_CONFIRM_TITLE', 'Bevestiging');
define( '_CONFIRM_COL1_TITLE', 'Account');
define( '_CONFIRM_COL2_TITLE', 'Detail');
define( '_CONFIRM_COL3_TITLE', 'Bedrag');
define( '_CONFIRM_ROW_NAME', 'Naam: ');
define( '_CONFIRM_ROW_USERNAME', 'Gebruikersnaam: ');
define( '_CONFIRM_ROW_EMAIL', 'E-mail:');
define( '_CONFIRM_INFO', 'Gebruik s.v.p. de afrekenen knop om de registratie af te ronden.');
define( '_BUTTON_CONFIRM', 'Afrekenen');
define( '_CONFIRM_TOS', "Ik heb de <a href=\"%s\" target=\"_BLANK\">Algemene Voorwaarden</a> gelezen en ga akkoord");
define( '_CONFIRM_TOS_IFRAME', "ik heb de algemene voorwaarden gelezen en ga hiermee akkoord");
define( '_CONFIRM_TOS_ERROR', 'Ga akkoord met de algemene voorwaarden');
define( '_CONFIRM_COUPON_INFO', 'Als u een speciale coupon heeft, dan kunt u die invullen op de afreken pagina voor een betalings korting');
define( '_CONFIRM_COUPON_INFO_BOTH', 'If you have a coupon code, you can enter it here, or on the afreken pagina to get a discount on your payment');
define( '_CONFIRM_FREETRIAL', 'Gratis proef periode');
define( '_CONFIRM_YOU_HAVE_SELECTED', 'U heeft geselecteerd');

define( '_CONFIRM_DIFFERENT_USER_DETAILS', 'Gegevens aanpassen');
define( '_CONFIRM_DIFFERENT_ITEM', 'Abonnement aanpassen');

// --== SHOPPING CART FORM ==--
define( '_CART_TITLE', 'Shopping Cart');
define( '_CART_ROW_TOTAL', 'Total');
define( '_CART_INFO', 'Please use the Continue-Button below to complete your purchase.');
define( '_CART_CLEAR_ALL', 'clear the whole cart');
define( '_CART_DELETE_ITEM', 'remove');

// --== EXCEPTION FORM ==--
define( '_EXCEPTION_TITLE', 'Additional Information Required');
define( '_EXCEPTION_TITLE_NOFORM', 'Please note:');
define( '_EXCEPTION_INFO', 'To proceed with your checkout, we need you to provide additional information as specified below:');

// --== PROMPT PASSWORD FORM ==--
define( '_AEC_PROMPT_PASSWORD', 'For security reasons, you need to put in your password to continue.');
define( '_AEC_PROMPT_PASSWORD_WRONG', 'The Password you have entered does not match with the one we have registered for you in our database. Please try again.');
define( '_AEC_PROMPT_PASSWORD_BUTTON', 'Continue');

// --== AFREKEN FORMULIER ==--
define( '_CHECKOUT_TITLE', 'Afrekenen');
define( '_CHECKOUT_INFO', 'Uw registratie is nu opgeslagen. Op deze page kunt u uw betaling voltooien - %s. <br /> Als er iets mis gaat kunt u altijd terug keren naar deze pagina door in te loggen op de site. Het systeem geeft u een melding om de betaling opnieuw te proberen.');
define( '_CHECKOUT_INFO_REPEAT', 'Dank u voor uw bezoek. Op deze pagina kunt u uw betaling voltooien - %s. <br /> Als er iets mis gaat kunt u altijd terug keren naar deze pagina door in te loggen op de site. Het systeem geeft u een melding om de betaling opnieuw te proberen.');
define( '_BUTTON_CHECKOUT', 'Afrekenen');
define( '_BUTTON_APPEND', 'Toepassen');
define( '_BUTTON_APPLY', 'Verder');
define( '_BUTTON_EDIT', 'Aanpassen');
define( '_BUTTON_SELECT', 'Selecteren');
define( '_CHECKOUT_COUPON_CODE', 'Coupon code');
define( '_CHECKOUT_INVOICE_AMOUNT', 'Factuur bedrag');
define( '_CHECKOUT_INVOICE_COUPON', 'Coupon');
define( '_CHECKOUT_INVOICE_COUPON_REMOVE', 'verwijderen');
define( '_CHECKOUT_INVOICE_TOTAL_AMOUNT', 'Totaal bedrag');
define( '_CHECKOUT_COUPON_INFO', 'Als u een coupon code heeft kunt u die hier invullen om een korting op het bedrag te betalen.');
define( '_CHECKOUT_GIFT_HEAD', 'Gift to another user');
define( '_CHECKOUT_GIFT_INFO', 'Enter details for another user of this site to give the item(s) you are about to purchase to that account.');

define( '_AEC_TERMTYPE_TRIAL', 'Initial Billing');
define( '_AEC_TERMTYPE_TERM', 'Regular Billing Term');
define( '_AEC_CHECKOUT_TERM', 'Billing Term');
define( '_AEC_CHECKOUT_NOTAPPLICABLE', 'not applicable');
define( '_AEC_CHECKOUT_FUTURETERM', 'betaal termijn');
define( '_AEC_CHECKOUT_COST', 'Kosten');
define( '_AEC_CHECKOUT_TAX', 'BTW');
define( '_AEC_CHECKOUT_DISCOUNT', 'Korting');
define( '_AEC_CHECKOUT_TOTAL', 'Subtotaal');
define( '_AEC_CHECKOUT_GRAND_TOTAL', 'Totaal');
define( '_AEC_CHECKOUT_DURATION', 'Duur');

define( '_AEC_CHECKOUT_DUR_LIFETIME', 'Levenslang');

define( '_AEC_CHECKOUT_DUR_DAY', 'Dag');
define( '_AEC_CHECKOUT_DUR_DAYS', 'Dagen');
define( '_AEC_CHECKOUT_DUR_WEEK', 'Week');
define( '_AEC_CHECKOUT_DUR_WEEKS', 'Weken');
define( '_AEC_CHECKOUT_DUR_MONTH', 'Maand');
define( '_AEC_CHECKOUT_DUR_MONTHS', 'Maanden');
define( '_AEC_CHECKOUT_DUR_YEAR', 'Jaar');
define( '_AEC_CHECKOUT_DUR_YEARS', 'Jaren');

// --== ALLOPASS SPECIFIC ==--
define( '_REGTITLE','INSCRIPTION');
define( '_ERRORCODE','Erreur de code Allopass');
define( '_FTEXTA','Le code que vous avez utilis n\'est pas valide! Pour obtenir un code valable, composez le numero de tlphone, indiqu dans une fenetre pop-up, aprs avoir clicker sur le drapeau de votre pays. Votre browser doit accepter les cookies d\'usage.<br><br>Si vous tes certain, que vous avez le bon code, attendez quelques secondes et ressayez encore une fois!<br><br>Sinon prenez note de la date et heure de cet avertissement d\'erreur et informez le Webmaster de ce problme en indiquant le code utilis.');
define( '_RECODE','Saisir de nouveau le code Allopass');

// --== REGISTRATIESTAPPEN ==--
define( '_STEP_DATA', 'Uw Data');
define( '_STEP_CONFIRM', 'Bevestigen');
define( '_STEP_PLAN', 'Selecteer abonnement');
define( '_STEP_EXPIRED', 'Verlopen!');

// --== NIET TOEGESTAAN PAGINA ==--
define( '_NOT_ALLOWED_HEADLINE', 'U bent verplicht om een account te hebben!');
define( '_NOT_ALLOWED_FIRSTPAR', 'De items die u probeert zijn alleen beschikbaar voor leden. Als u al lid bent kunt u inloggen op onze site. Als u nog geen account heeft kunt u de volgende link gebruikern om te registreren: ');
define( '_NOT_ALLOWED_REGISTERLINK', 'Registratie pagina');
define( '_NOT_ALLOWED_FIRSTPAR_LOGGED', 'De items die u probeert op te vragen is alleen van toepassing voor bepaalde leden. Om uw account te upgraden: ');
define( '_NOT_ALLOWED_REGISTERLINK_LOGGED', 'Registratiepagina');
define( '_NOT_ALLOWED_SECONDPAR', 'Aanmelden kost u minder dan een minuut - Wij gebruiken de aanmelding voor:');

// --== GEANNULEERDE PAGINA ==--
define( '_CANCEL_TITLE', 'Registratie mislukt');
define( '_CANCEL_MSG', 'Ons systeem heeft uw aanvraag ontvangen dat u besloten heeft om uw registratie te stoppen. Als dit komt door problemen die u ondervonden heeft op onze website neem dan s.v.p. contact met ons op!');

// --== IN WACHTRIJ PAGINA ==--
define( '_PENDING_TITLE', 'Registratie nog in afwachting');
define( '_WARN_PENDING', 'Uw registratie is nog in afwachting van goedkeuring of is tijdelijk stop gezet. Als deze status al meer dan 24 uur wordt weergegeven, en uw betaling is goedgekeurd, neem dan s.v.p. contact op met de beheerder van deze website.');
define( '_PENDING_OPENINVOICE', 'U heeft een onvoltooide factuur. - Als er iets mis gegaan is, probeer dan af te loggen en opnieuw te proberen:');
define( '_GOTO_CHECKOUT', 'Ga s.v.p. weer naar de "Afreken" pagina');
define( '_GOTO_CHECKOUT_CANCEL', 'U mag ook de registrie cancellen, en opnieuw te registreren:');
define( '_PENDING_NOINVOICE', 'Het lijkt erop alsof u de enige openstaande factuur gecancelled heeft. Gebruik de knop hieronder om een nieuw plan te selecteren:');
define( '_PENDING_NOINVOICE_BUTTON', 'Plan Selectie');
define( '_PENDING_REASON_ECHECK', '(Volgens onze informatie heeft u besloten om te betalen met Echeck, Wacht s.v.p. tot deze betaling is voltooid. Dit duurt meestal 4 tot 8 dagen.)');
define( '_PENDING_REASON_WAITING_RESPONSE', '(According to our information however, we are just waiting for a response from the payment processor. You will be notified once that has happened. Sorry for the delay.)');
define( '_PENDING_REASON_TRANSFER', '(Volgens onze informatie heeft u besloten om te betalen d.m.v. een offline betaalmethode. Dit houdt in dat u moet wachten tot deze betaling voltooid is. Dit duurt meestal 3 tot 6 dagen.)');

// --== HOLD PAGE ==--
define( '_HOLD_TITLE', 'Account inactief');
define( '_HOLD_EXPLANATION', 'Uw account is nog inactief, mogelijk omdat er een probleem is opgetreden met de betaling. als uw geen mail ontvangt van de betaling binnen 24 uur, neem dan even contact op met de beheerder van de website.');

// --== DANK U PAGINA ==--
define( '_THANKYOU_TITLE', 'Bedankt voor het registreren!');
define( '_SUB_FEPARTICLE_HEAD', 'Registratie compleet!');
define( '_SUB_FEPARTICLE_HEAD_RENEW', 'Registratie vernieuwing compleet');
define( '_SUB_FEPARTICLE_LOGIN', 'U kunt nu inloggen.');
define( '_SUB_FEPARTICLE_THANKS', 'Dan u voor uw registratie. ');
define( '_SUB_FEPARTICLE_THANKSRENEW', 'Dank u voor het vernieuwen van uw registratie. ');
define( '_SUB_FEPARTICLE_PROCESS', 'Ons systeem verwerkt nu uw aanmelding. ');
define( '_SUB_FEPARTICLE_PROCESSPAY', 'Ons systeem wacht nu tot uw betaling is voltooid. ');
define( '_SUB_FEPARTICLE_ACTMAIL', 'U ontvangt een email met een bevestigings link.');
define( '_SUB_FEPARTICLE_MAIL', 'U ontvangt een email wanneer uw betaling ontvangen is. ');

// --== CHECKOUT ERROR PAGE ==--
define( '_CHECKOUT_ERROR_TITLE', 'Er is een fout opgetreden');
define( '_CHECKOUT_ERROR_EXPLANATION', 'Er is een fout opgetreden tijdens het betalen.');
define( '_CHECKOUT_ERROR_OPENINVOICE', 'This leaves your invoice uncleared. To retry the payment, you can go to the checkout page once again to try again:');
define( '_CHECKOUT_ERROR_FURTHEREXPLANATION', 'This leaves your invoice uncleared, but you can try to check out again below. If you experience further problems or need any assistance with your checkout, please do not hesitate to contact us.');

// --== COUPON INFORMATIE ==--
define( '_COUPON_INFO', 'Coupons:');
define( '_COUPON_INFO_CONFIRM', 'Als u een of meer coupons wilt gebruiken voor deze betaling kunt u dit doen op de afreken pagina.');
define( '_COUPON_INFO_CHECKOUT', 'Vul een coupon code in, en kik op de knop om deze te activeren.');

// --== COUPON FOUTMELDINGEN ==--
define( '_COUPON_WARNING_AMOUNT', 'De coupon die u heeft ingevuld heeft geen effect op uw volgende betaling.ent, dus als het lijkt of deze niet wordt toegepast op deze factuur, neem dan contact op met de website beheerder.');
define( '_COUPON_ERROR_PRETEXT', 'Het spijt ons:');
define( '_COUPON_ERROR_EXPIRED', 'Deze coupon is verlopen.');
define( '_COUPON_ERROR_NOTSTARTED', 'Deze coupon kan nog niet gebruikt worden.');
define( '_COUPON_ERROR_NOTFOUND', 'De code van de ingevoerde coupon is niet gevonden.');
define( '_COUPON_ERROR_MAX_REUSE', 'Deze coupon kan niet meer gebruikt worden omdat deze al gebruikt is.');
define( '_COUPON_ERROR_PERMISSION', 'U heeft geen rechten om deze coupon te gebruiken.');
define( '_COUPON_ERROR_WRONG_USAGE', 'U kunt deze coupon niet voor dit product / dienst gebruiken.');
define( '_COUPON_ERROR_WRONG_PLAN', 'Uw account heeft niet voldoende rechten om deze coupon te gebruiken.');
define( '_COUPON_ERROR_WRONG_PLAN_PREVIOUS', 'Om deze coupon te gebruiken moet u een ander account hebben.');
define( '_COUPON_ERROR_WRONG_PLANS_OVERALL', 'U heeft niet het juiste account om deze coupon te gebruiken.');
define( '_COUPON_ERROR_TRIAL_ONLY', 'U mag deze coupon alleen gebruiken voor een trail periode.');
define( '_COUPON_ERROR_COMBINATION', 'U kunt deze coupon niet in samenwerking met de andere coupons gebruiken');
define( '_COUPON_ERROR_SPONSORSHIP_ENDED', 'Sponsorship for this Coupon has ended or is currently inactive.');

// ----======== EMAIL TEKSTEN ========----

define( '_AEC_SEND_SUB',				"Account informatie voor %s op %s" );
define( '_AEC_USEND_MSG',				"Hallo %s,\n\nBedankt voor het registreren op %s.\n\nU kunt nu inloggen met uw gebruikersnaam en wachtwoord." );
define( '_AEC_USEND_MSG_ACTIVATE',				"Hallo %s,\n\nBedankt voor het registreren op %s. Uw account is aangemaakt en moet nog worden geactiveerd.\nOm uw account te activeren klik op onderstaande link of plak deze in uw browser:\n%s\n\nNadat uw account is geactiveerd kunt u inloggen met de volgende gegevens:\n\nGebruikersnaam - %s\nWachtwoord - %s" );
define( '_ACCTEXP_SEND_MSG','Registratie voor %s op %s');
define( '_ACCTEXP_SEND_MSG_RENEW','Registratie vernieuwing voor %s op %s');
define( '_ACCTEXP_MAILPARTICLE_GREETING', "Hallo %s, \n\n");
define( '_ACCTEXP_MAILPARTICLE_THANKSREG', 'Bedankt voor uw registratie op %s.');
define( '_ACCTEXP_MAILPARTICLE_THANKSREN', 'Bedankt voor het vernieuwen van uw registratie op %s.');
define( '_ACCTEXP_MAILPARTICLE_PAYREC', 'De betaling voor uw registratie is voltooid.');
define( '_ACCTEXP_MAILPARTICLE_LOGIN', 'U kunt nu inloggen op %s met uw gebruikersnaam en wachtwoord.');
define( '_ACCTEXP_MAILPARTICLE_FOOTER',"\n\nReageer s.v.p. niet op dit bericht. Dit bericht is automatisch gegenereerd.");
define( '_ACCTEXP_ASEND_MSG',				"Hallo %s,\n\na nieuwe gebruikersaccount gemaakt op [ %s ].\n\nMeer details worden hier vermeldt:\n\nNaam.........: %s\nEmail........: %s\nGebruikersnaam.....: %s\nRegistratie-ID...: %s\Registratie.: %s\nIP...........: %s\nISP..........: %s\n\nReageer s.v.p. niet op dit bericht. Dit bericht is automatisch gegenereerd, en dient alleen ter informatieve doeleinden." );
define( '_ACCTEXP_ASEND_MSG_RENEW',			"Hallo %s,\n\na Gebruiker heeft zijn account vernieuwd op [ %s ].\n\nMeer details worden hier vermeldt:\n\nNaam.........: %s\nEmail........: %s\nGebruikersnaam.....: %s\nRegistratie-ID...: %s\nRegisatratie.: %s\nIP...........: %s\nISP..........: %s\n\nReageer s.v.p. niet op dit bericht. Dit bericht is automatisch gegenereerd, en dient alleen ter informatieve doeleinden." );

define( '_ACCTEXP_ASEND_MSG',				"Beste %s,\n\neen nieuwe gebruiker heeft een abonnement afgesloten op [ %s ].\n\nDe details voor deze abonnee zijn als volgt:\n\nNaam..........: %s\nEmail.........: %s\nGebruikersnaam: %s\nSubscr.-ID....: %s\nSubscription..: %s\nIP............: %s\nISP...........: %s\n\nStuur alsjeblieft geen antwoord naar aanleiding van dit bericht. Dit is een informatieve tekst die automatisch gegenereerd is door het systeem. Antwoorden worden niet door echte personen gelezen..." );
define( '_ACCTEXP_ASEND_MSG_RENEW',			"Beste %s,\n\na user has renewed his subscription at [ %s ].\n\nDe details voor deze abonnee zijn als volgt:\n\nNaam..........: %s\nEmail.........: %s\nGebruikersnaam: %s\nSubscr.-ID....: %s\nSubscription..: %s\nIP............: %s\nISP...........: %s\n\nStuur alsjeblieft geen antwoord naar aanleiding van dit bericht. Dit is een informatieve tekst die automatisch gegenereerd is door het systeem. Antwoorden worden niet door echte personen gelezen..." );
define( '_AEC_ASEND_MSG_NEW_REG',			"Hallo %s,\n\nEr is een nieuwe registrant aangemeld op [ %s ].\n\nMeer details worden hier vermeldt:\n\nNaam.....: %s\nEmail.: %s\nGebruikersnaam....: %s\nIP.......: %s\nISP......: %s\n\nReageer s.v.p. niet op dit bericht. Dit bericht is automatisch gegenereerd, en dient alleen ter informatieve doeleinden." );
define( '_AEC_ASEND_NOTICE',				"AEC %s: %s at %s" );
define( '_AEC_ASEND_NOTICE_MSG',		"Afhankelijk van de instellingen is dit een notificatiemail.\n\nDe details zijn als volgt:\n\n--- --- --- ---\n\n%s\n\n--- --- --- ---\n\nReageer niet op deze mail, want deze is automatisch gegenereerd. U kunt het instellen binnen de AEC centrale." );

// ----======== COUNTRY CODES ========----

define( 'COUNTRYCODE_SELECT', 'Selecteer Land' );

define( 'COUNTRYCODE_AD', 'Andorra' );
define( 'COUNTRYCODE_AE', 'United Arab Emirates' );
define( 'COUNTRYCODE_AF', 'Afghanistan' );
define( 'COUNTRYCODE_AG', 'Antigua and Barbuda' );
define( 'COUNTRYCODE_AI', 'Anguilla' );
define( 'COUNTRYCODE_AL', 'Albania' );
define( 'COUNTRYCODE_AM', 'Armenia' );
define( 'COUNTRYCODE_AN', 'Netherlands Antilles' );
define( 'COUNTRYCODE_AO', 'Angola' );
define( 'COUNTRYCODE_AQ', 'Antarctica' );
define( 'COUNTRYCODE_AR', 'Argentina' );
define( 'COUNTRYCODE_AS', 'American Samoa' );
define( 'COUNTRYCODE_AT', 'Austria' );
define( 'COUNTRYCODE_AU', 'Australia' );
define( 'COUNTRYCODE_AW', 'Aruba' );
define( 'COUNTRYCODE_AX', 'Aland Islands &#65279;land Island\'s' );
define( 'COUNTRYCODE_AZ', 'Azerbaijan' );
define( 'COUNTRYCODE_BA', 'Bosnia and Herzegovina' );
define( 'COUNTRYCODE_BB', 'Barbados' );
define( 'COUNTRYCODE_BD', 'Bangladesh' );
define( 'COUNTRYCODE_BE', 'Belgium' );
define( 'COUNTRYCODE_BF', 'Burkina Faso' );
define( 'COUNTRYCODE_BG', 'Bulgaria' );
define( 'COUNTRYCODE_BH', 'Bahrain' );
define( 'COUNTRYCODE_BI', 'Burundi' );
define( 'COUNTRYCODE_BJ', 'Benin' );
define( 'COUNTRYCODE_BL', 'Saint Barth&eacute;lemy' );
define( 'COUNTRYCODE_BM', 'Bermuda' );
define( 'COUNTRYCODE_BN', 'Brunei Darussalam' );
define( 'COUNTRYCODE_BO', 'Bolivia, Plurinational State of' );
define( 'COUNTRYCODE_BR', 'Brazil' );
define( 'COUNTRYCODE_BS', 'Bahamas' );
define( 'COUNTRYCODE_BT', 'Bhutan' );
define( 'COUNTRYCODE_BV', 'Bouvet Island' );
define( 'COUNTRYCODE_BW', 'Botswana' );
define( 'COUNTRYCODE_BY', 'Belarus' );
define( 'COUNTRYCODE_BZ', 'Belize' );
define( 'COUNTRYCODE_CA', 'Canada' );
define( 'COUNTRYCODE_CC', 'Cocos (Keeling) Islands' );
define( 'COUNTRYCODE_CD', 'Congo, the Democratic Republic of the' );
define( 'COUNTRYCODE_CF', 'Central African Republic' );
define( 'COUNTRYCODE_CG', 'Congo' );
define( 'COUNTRYCODE_CH', 'Switzerland' );
define( 'COUNTRYCODE_CI', 'Cote d\'Ivoire' );
define( 'COUNTRYCODE_CK', 'Cook Islands' );
define( 'COUNTRYCODE_CL', 'Chile' );
define( 'COUNTRYCODE_CM', 'Cameroon' );
define( 'COUNTRYCODE_CN', 'China' );
define( 'COUNTRYCODE_CO', 'Colombia' );
define( 'COUNTRYCODE_CR', 'Costa Rica' );
define( 'COUNTRYCODE_CU', 'Cuba' );
define( 'COUNTRYCODE_CV', 'Cape Verde' );
define( 'COUNTRYCODE_CX', 'Christmas Island' );
define( 'COUNTRYCODE_CY', 'Cyprus' );
define( 'COUNTRYCODE_CZ', 'Czech Republic' );
define( 'COUNTRYCODE_DE', 'Germany' );
define( 'COUNTRYCODE_DJ', 'Djibouti' );
define( 'COUNTRYCODE_DK', 'Denmark' );
define( 'COUNTRYCODE_DM', 'Dominica' );
define( 'COUNTRYCODE_DO', 'Dominican Republic' );
define( 'COUNTRYCODE_DZ', 'Algeria' );
define( 'COUNTRYCODE_EC', 'Ecuador' );
define( 'COUNTRYCODE_EE', 'Estonia' );
define( 'COUNTRYCODE_EG', 'Egypt' );
define( 'COUNTRYCODE_EH', 'Western Sahara' );
define( 'COUNTRYCODE_ER', 'Eritrea' );
define( 'COUNTRYCODE_ES', 'Spain' );
define( 'COUNTRYCODE_ET', 'Ethiopia' );
define( 'COUNTRYCODE_FI', 'Finland' );
define( 'COUNTRYCODE_FJ', 'Fiji' );
define( 'COUNTRYCODE_FK', 'Falkland Islands (Malvinas)' );
define( 'COUNTRYCODE_FM', 'Micronesia, Federated States of' );
define( 'COUNTRYCODE_FO', 'Faroe Islands' );
define( 'COUNTRYCODE_FR', 'France' );
define( 'COUNTRYCODE_GA', 'Gabon' );
define( 'COUNTRYCODE_GB', 'United Kingdom' );
define( 'COUNTRYCODE_GD', 'Grenada' );
define( 'COUNTRYCODE_GE', 'Georgia' );
define( 'COUNTRYCODE_GF', 'French Guiana' );
define( 'COUNTRYCODE_GG', 'Guernsey' );
define( 'COUNTRYCODE_GH', 'Ghana' );
define( 'COUNTRYCODE_GI', 'Gibraltar' );
define( 'COUNTRYCODE_GL', 'Greenland' );
define( 'COUNTRYCODE_GM', 'Gambia' );
define( 'COUNTRYCODE_GN', 'Guinea' );
define( 'COUNTRYCODE_GP', 'Guadeloupe' );
define( 'COUNTRYCODE_GQ', 'Equatorial Guinea' );
define( 'COUNTRYCODE_GR', 'Greece' );
define( 'COUNTRYCODE_GS', 'South Georgia and the South Sandwich Islands' );
define( 'COUNTRYCODE_GT', 'Guatemala' );
define( 'COUNTRYCODE_GU', 'Guam' );
define( 'COUNTRYCODE_GW', 'Guinea-Bissau' );
define( 'COUNTRYCODE_GY', 'Guyana' );
define( 'COUNTRYCODE_HK', 'Hong Kong' );
define( 'COUNTRYCODE_HM', 'Heard Island and McDonald Islands' );
define( 'COUNTRYCODE_HN', 'Honduras' );
define( 'COUNTRYCODE_HR', 'Croatia' );
define( 'COUNTRYCODE_HT', 'Haiti' );
define( 'COUNTRYCODE_HU', 'Hungary' );
define( 'COUNTRYCODE_ID', 'Indonesia' );
define( 'COUNTRYCODE_IE', 'Ireland' );
define( 'COUNTRYCODE_IL', 'Israel' );
define( 'COUNTRYCODE_IM', 'Isle of Man' );
define( 'COUNTRYCODE_IN', 'India' );
define( 'COUNTRYCODE_IO', 'British Indian Ocean Territory' );
define( 'COUNTRYCODE_IQ', 'Iraq' );
define( 'COUNTRYCODE_IR', 'Iran, Islamic Republic of' );
define( 'COUNTRYCODE_IS', 'Iceland' );
define( 'COUNTRYCODE_IT', 'Italy' );
define( 'COUNTRYCODE_JE', 'Jersey' );
define( 'COUNTRYCODE_JM', 'Jamaica' );
define( 'COUNTRYCODE_JO', 'Jordan' );
define( 'COUNTRYCODE_JP', 'Japan' );
define( 'COUNTRYCODE_KE', 'Kenya' );
define( 'COUNTRYCODE_KG', 'Kyrgyzstan' );
define( 'COUNTRYCODE_KH', 'Cambodia' );
define( 'COUNTRYCODE_KI', 'Kiribati' );
define( 'COUNTRYCODE_KM', 'Comoros' );
define( 'COUNTRYCODE_KN', 'Saint Kitts and Nevis' );
define( 'COUNTRYCODE_KP', 'Korea, Democratic People\'s Republic of' );
define( 'COUNTRYCODE_KR', 'Korea, Republic of' );
define( 'COUNTRYCODE_KW', 'Kuwait' );
define( 'COUNTRYCODE_KY', 'Cayman Islands' );
define( 'COUNTRYCODE_KZ', 'Kazakhstan' );
define( 'COUNTRYCODE_LA', 'Lao People\'s Democratic Republic' );
define( 'COUNTRYCODE_LB', 'Lebanon' );
define( 'COUNTRYCODE_LC', 'Saint Lucia' );
define( 'COUNTRYCODE_LI', 'Liechtenstein' );
define( 'COUNTRYCODE_LK', 'Sri Lanka' );
define( 'COUNTRYCODE_LR', 'Liberia' );
define( 'COUNTRYCODE_LS', 'Lesotho' );
define( 'COUNTRYCODE_LT', 'Lithuania' );
define( 'COUNTRYCODE_LU', 'Luxembourg' );
define( 'COUNTRYCODE_LV', 'Latvia' );
define( 'COUNTRYCODE_LY', 'Libyan Arab Jamahiriya' );
define( 'COUNTRYCODE_MA', 'Morocco' );
define( 'COUNTRYCODE_MC', 'Monaco' );
define( 'COUNTRYCODE_MD', 'Moldova, Republic of' );
define( 'COUNTRYCODE_ME', 'Montenegro' );
define( 'COUNTRYCODE_MF', 'Saint Martin (French part)' );
define( 'COUNTRYCODE_MG', 'Madagascar' );
define( 'COUNTRYCODE_MH', 'Marshall Islands' );
define( 'COUNTRYCODE_MK', 'Macedonia, the former Yugoslav Republic of' );
define( 'COUNTRYCODE_ML', 'Mali' );
define( 'COUNTRYCODE_MM', 'Myanmar' );
define( 'COUNTRYCODE_MN', 'Mongolia' );
define( 'COUNTRYCODE_MO', 'Macao' );
define( 'COUNTRYCODE_MP', 'Northern Mariana Islands' );
define( 'COUNTRYCODE_MQ', 'Martinique' );
define( 'COUNTRYCODE_MR', 'Mauritania' );
define( 'COUNTRYCODE_MS', 'Montserrat' );
define( 'COUNTRYCODE_MT', 'Malta' );
define( 'COUNTRYCODE_MU', 'Mauritius' );
define( 'COUNTRYCODE_MV', 'Maldives' );
define( 'COUNTRYCODE_MW', 'Malawi' );
define( 'COUNTRYCODE_MX', 'Mexico' );
define( 'COUNTRYCODE_MY', 'Malaysia' );
define( 'COUNTRYCODE_MZ', 'Mozambique' );
define( 'COUNTRYCODE_NA', 'Namibia' );
define( 'COUNTRYCODE_NC', 'New Caledonia' );
define( 'COUNTRYCODE_NE', 'Niger' );
define( 'COUNTRYCODE_NF', 'Norfolk Island' );
define( 'COUNTRYCODE_NG', 'Nigeria' );
define( 'COUNTRYCODE_NI', 'Nicaragua' );
define( 'COUNTRYCODE_NL', 'Netherlands' );
define( 'COUNTRYCODE_NO', 'Norway' );
define( 'COUNTRYCODE_NP', 'Nepal' );
define( 'COUNTRYCODE_NR', 'Nauru' );
define( 'COUNTRYCODE_NU', 'Niue' );
define( 'COUNTRYCODE_NZ', 'New Zealand' );
define( 'COUNTRYCODE_OM', 'Oman' );
define( 'COUNTRYCODE_PA', 'Panama' );
define( 'COUNTRYCODE_PE', 'Peru' );
define( 'COUNTRYCODE_PF', 'French Polynesia' );
define( 'COUNTRYCODE_PG', 'Papua New Guinea' );
define( 'COUNTRYCODE_PH', 'Philippines' );
define( 'COUNTRYCODE_PK', 'Pakistan' );
define( 'COUNTRYCODE_PL', 'Poland' );
define( 'COUNTRYCODE_PM', 'Saint Pierre and Miquelon' );
define( 'COUNTRYCODE_PN', 'Pitcairn' );
define( 'COUNTRYCODE_PR', 'Puerto Rico' );
define( 'COUNTRYCODE_PS', 'Palestinian Territory, Occupied' );
define( 'COUNTRYCODE_PT', 'Portugal' );
define( 'COUNTRYCODE_PW', 'Palau' );
define( 'COUNTRYCODE_PY', 'Paraguay' );
define( 'COUNTRYCODE_QA', 'Qatar' );
define( 'COUNTRYCODE_RE', 'Reunion' );
define( 'COUNTRYCODE_RO', 'Romania' );
define( 'COUNTRYCODE_RS', 'Serbia' );
define( 'COUNTRYCODE_RU', 'Russian Federation' );
define( 'COUNTRYCODE_RW', 'Rwanda' );
define( 'COUNTRYCODE_SA', 'Saudi Arabia' );
define( 'COUNTRYCODE_SB', 'Solomon Islands' );
define( 'COUNTRYCODE_SC', 'Seychelles' );
define( 'COUNTRYCODE_SD', 'Sudan' );
define( 'COUNTRYCODE_SE', 'Sweden' );
define( 'COUNTRYCODE_SG', 'Singapore' );
define( 'COUNTRYCODE_SH', 'Saint Helena' );
define( 'COUNTRYCODE_SI', 'Slovenia' );
define( 'COUNTRYCODE_SJ', 'Svalbard and Jan Mayen' );
define( 'COUNTRYCODE_SK', 'Slovakia' );
define( 'COUNTRYCODE_SL', 'Sierra Leone' );
define( 'COUNTRYCODE_SM', 'San Marino' );
define( 'COUNTRYCODE_SN', 'Senegal' );
define( 'COUNTRYCODE_SO', 'Somalia' );
define( 'COUNTRYCODE_SR', 'Suriname' );
define( 'COUNTRYCODE_ST', 'Sao Tome and Principe' );
define( 'COUNTRYCODE_SV', 'El Salvador' );
define( 'COUNTRYCODE_SY', 'Syrian Arab Republic' );
define( 'COUNTRYCODE_SZ', 'Swaziland' );
define( 'COUNTRYCODE_TC', 'Turks and Caicos Islands' );
define( 'COUNTRYCODE_TD', 'Chad' );
define( 'COUNTRYCODE_TF', 'French Southern Territories' );
define( 'COUNTRYCODE_TG', 'Togo' );
define( 'COUNTRYCODE_TH', 'Thailand' );
define( 'COUNTRYCODE_TJ', 'Tajikistan' );
define( 'COUNTRYCODE_TK', 'Tokelau' );
define( 'COUNTRYCODE_TL', 'Timor-Leste' );
define( 'COUNTRYCODE_TM', 'Turkmenistan' );
define( 'COUNTRYCODE_TN', 'Tunisia' );
define( 'COUNTRYCODE_TO', 'Tonga' );
define( 'COUNTRYCODE_TR', 'Turkey' );
define( 'COUNTRYCODE_TT', 'Trinidad and Tobago' );
define( 'COUNTRYCODE_TV', 'Tuvalu' );
define( 'COUNTRYCODE_TW', 'Taiwan, Province of Republic of China' );
define( 'COUNTRYCODE_TZ', 'Tanzania, United Republic of' );
define( 'COUNTRYCODE_UA', 'Ukraine' );
define( 'COUNTRYCODE_UG', 'Uganda' );
define( 'COUNTRYCODE_UM', 'United States Minor Outlying Islands' );
define( 'COUNTRYCODE_US', 'United States' );
define( 'COUNTRYCODE_UY', 'Uruguay' );
define( 'COUNTRYCODE_UZ', 'Uzbekistan' );
define( 'COUNTRYCODE_VA', 'Holy See (Vatican City State)' );
define( 'COUNTRYCODE_VC', 'Saint Vincent and the Grenadines' );
define( 'COUNTRYCODE_VE', 'Venezuela, Bolivarian Republic of' );
define( 'COUNTRYCODE_VG', 'Virgin Islands, British' );
define( 'COUNTRYCODE_VI', 'Virgin Islands, U.S.' );
define( 'COUNTRYCODE_VN', 'Viet Nam' );
define( 'COUNTRYCODE_VU', 'Vanuatu' );
define( 'COUNTRYCODE_WF', 'Wallis and Futuna' );
define( 'COUNTRYCODE_WS', 'Samoa' );
define( 'COUNTRYCODE_YE', 'Yemen' );
define( 'COUNTRYCODE_YT', 'Mayotte' );
define( 'COUNTRYCODE_ZA', 'South Africa' );
define( 'COUNTRYCODE_ZM', 'Zambia' );
define( 'COUNTRYCODE_ZW', 'Zimbabwe' );

?>