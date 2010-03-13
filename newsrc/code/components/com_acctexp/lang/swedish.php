<?php
/**
 * @version $Id: swedish.php
 * @package AEC - Account Control Expiration - Membership Manager
 * @subpackage Language - Frontend - English
 * @copyright 2006-2008 Copyright (C) David Deutsch
 * @author David Deutsch <skore@skore.de> & Team AEC - http://www.valanx.org

 * @Translation into Swedish: Anders Carlén <anders@carlencommunications.se> - http://www.carlencommunications.s
 * @license GNU/GPL v.2 http://www.gnu.org/licenses/old-licenses/gpl-2.0.html or, at your option, any later version
 */

// Dont allow direct linking
( defined('_JEXEC') || defined( '_VALID_MOS' ) ) or die( 'Direct Access to this location is not allowed.' );

if( defined( '_AEC_LANG' ) ) {
	return;
}

// new 0.12.4 - mic

define( '_AEC_EXPIRE_TODAY',				'Detta medlemskap gäller till och med idag' ); // This account is active until today
define( '_AEC_EXPIRE_FUTURE',				'Detta medlemskap gäller till och med' ); // // This account is active until
define( '_AEC_EXPIRE_PAST',					'Detta medlemskap gällde fram till och med' ); //This account was valid until
define( '_AEC_DAYS_ELAPSED',				'dag(ar) sedan'); //day(s) elapsed
define( '_AEC_EXPIRE_TRIAL_TODAY',			'Detta provmedlemskap gäller till och med idag' ); // This trial is active until today
define( '_AEC_EXPIRE_TRIAL_FUTURE',			'Detta medlemskap gäller till och med' ); // This trial is active until
define( '_AEC_EXPIRE_TRIAL_PAST',			'Detta medlemskap gällde fram till och med' ); // This trial was valid until

define( '_AEC_EXPIRE_NOT_SET',				'obestämt' ); // Not Set
define( '_AEC_GEN_ERROR',					'<h1>Ett oväntat fel har inträffat</h1><p>Vi stötte på ett oväntat problem. Vänligen försök igen eller kontakta sidans administratör.</p>' ); //<h1>General Error</h1><p>We had problems processing your request. Please contact the web site administrator.</p>

// payments
define( '_AEC_PAYM_METHOD_FREE',			'Gratis' ); //Free
define( '_AEC_PAYM_METHOD_NONE',			'Behövs ej' ); //None
define( '_AEC_PAYM_METHOD_TRANSFER',		'Flytta' ); //Transfer

// processor errors
define( '_AEC_MSG_PROC_INVOICE_FAILED_SH',			'Fakturan kunde ej betalas' ); //Failed Invoice Payment
define( '_AEC_MSG_PROC_INVOICE_FAILED_EV',			'Betalningserkännande från  %s för faktura %s har ej mottagits - angivet fakturanummer kan ej hittas:' ); //Processor %s notification for %s has failed - invoice number does not exist:
define( '_AEC_MSG_PROC_INVOICE_ACTION_SH',			'Fakturabetalning' ); //Invoice Payment Action
define( '_AEC_MSG_PROC_INVOICE_ACTION_EV',			'Betalningsmodul svarar:' ); //Payment Notification Parser responds:
define( '_AEC_MSG_PROC_INVOICE_ACTION_EV_STATUS',	'Faktura-status:' ); //Invoice status:
define( '_AEC_MSG_PROC_INVOICE_ACTION_EV_FRAUD',	'Summan stämmer ej, betalning: %s, faktura: %s - Transaktion avbruten' ); //Amount verification failed, paid: %s, invoice: %s - payment aborted
define( '_AEC_MSG_PROC_INVOICE_ACTION_EV_CURR',		'Valuta information stämmer ej, betalning: %s, faktura: %s - Transaktion avbruten' ); //Currency verification failed, paid %s, invoice: %s - payment aborted
define( '_AEC_MSG_PROC_INVOICE_ACTION_EV_VALID',	'Betalning godkänd' ); //Payment valid, Action carried out
define( '_AEC_MSG_PROC_INVOICE_ACTION_EV_VALID_APPFAIL',	'Betalningen godkänd, men tillämpningen misslyckades!' ); //Payment valid, Application failed!
define( '_AEC_MSG_PROC_INVOICE_ACTION_EV_TRIAL',	'Betalning godkänd, gratis prova-på period' ); //Payment valid - free trial
define( '_AEC_MSG_PROC_INVOICE_ACTION_EV_PEND',		'Betalnning ej godkänd - faktura-status: Avvaktar. Anledning: %s' ); //Payment invalid - status is pending, reason: %s
define( '_AEC_MSG_PROC_INVOICE_ACTION_EV_CANCEL',	'Ingen betalning - prenumeration avbruten' ); //No Payment - Subscription Cancel
define( '_AEC_MSG_PROC_INVOICE_ACTION_EV_CHARGEBACK',	'Ingen betalning - Återbetalning' ); //No Payment - Chargeback
define( '_AEC_MSG_PROC_INVOICE_ACTION_EV_CHARGEBACK_SETTLE',	'Ingen betalning - Överenskommen återbetalning' ); //No Payment - Chargeback Settlement
define( '_AEC_MSG_PROC_INVOICE_ACTION_EV_USTATUS',	', Användarstatus har ändrats till \'Annulerad\'' ); //, Userstatus has been updated to \'Cancelled\'
define( '_AEC_MSG_PROC_INVOICE_ACTION_EV_USTATUS_HOLD',	', Användarstatus har ändrats till \'Väntar\'' ); //, Userstatus has been updated to \'Hold\'
define( '_AEC_MSG_PROC_INVOICE_ACTION_EV_USTATUS_ACTIVE',	', Användarstatus har ändrats till \'Aktiv\'' ); //, Userstatus has been updated to \'Active\'
define( '_AEC_MSG_PROC_INVOICE_ACTION_EV_EOT',		'Ingen betalning - Prenumerationen har löpt ut' ); //No Payment - Subscription End Of Term
define( '_AEC_MSG_PROC_INVOICE_ACTION_EV_DUPLICATE','Ingen betalning - Dublett' ); //No Payment - Duplicate
define( '_AEC_MSG_PROC_INVOICE_ACTION_EV_NULL','Ingen betalning - Noll' ); //No Payment - Null
define( '_AEC_MSG_PROC_INVOICE_ACTION_EV_U_ERROR',	'Oväntat fel' ); //Unknown Error
define( '_AEC_MSG_PROC_INVOICE_ACTION_EV_REFUND',	'Ingen betalning - Prenumerationen avbruten (pengarna tillbaka)' ); //No Payment - Subscription Deleted (refund)
define( '_AEC_MSG_PROC_INVOICE_ACTION_EV_EXPIRED',	', Användaren har blivit inaktiverad' ); //, User has been expired

// end mic ########################################################

// --== PAYMENT PLANS PAGE ==--
define( '_PAYPLANS_HEADER', 'Välj medlemskap'); //
define( '_NOPLANS_ERROR', 'Inga medlemsalterntiv finns tillgängliga. Kontakta sidans administratör för fler frågor.'); //No payment plans available. Please contact administrator.
define( '_NOPLANS_AUTHERROR', 'Endast för medlemmar. Du har inte tillräcklig behörighet för att se denna sida. Kontakta sidans administratör om du har fler frågor.'); //You are not authorized to access this option. Please contact administrator if you have any further questions.
define( '_PLANGROUP_BACK', '&lt; Tillbaka'); //&lt; Go back

// --== ACCOUNT DETAILS PAGE ==--
define( '_CHK_USERNAME_AVAIL', "Användarnamnet %s är ledigt."); //Username %s is available
define( '_CHK_USERNAME_NOTAVAIL', "Användarnamnet %s är tyvärr upptaget."); //Username %s is already taken!

// --== MY SUBSCRIPTION PAGE ==--
define( '_MYSUBSCRIPTION_TITLE', 'Mitt medlemskap'); //My Membership
define( '_MEMBER_SINCE', 'Medlem sedan'); //Member since
define( '_HISTORY_COL1_TITLE', 'Faktura'); //Invoice
define( '_HISTORY_COL2_TITLE', 'Belopp'); //Amount
define( '_HISTORY_COL3_TITLE', 'Betalningsdatum'); //Payment Date
define( '_HISTORY_COL4_TITLE', 'Betalsätt'); //Method
define( '_HISTORY_COL5_TITLE', 'Funktion'); //Action
define( '_HISTORY_COL6_TITLE', 'Plan'); //Plan
define( '_HISTORY_ACTION_REPEAT', 'Betala'); //pay
define( '_HISTORY_ACTION_CANCEL', 'Avbryt'); //cancel
define( '_RENEW_LIFETIME', 'Du har en livslång prenumeration.'); //You have a lifetime subscription.
define( '_RENEW_DAYSLEFT_EXCLUDED', 'Din prenumeration är fortlöpande'); //You are excluded from expiration
define( '_RENEW_DAYSLEFT_INFINITE', '&#8734'); //&#8734
define( '_RENEW_DAYSLEFT', 'dagar kvar'); //Days left
define( '_RENEW_DAYSLEFT_TRIAL', 'dagar kvar på din provperiod'); //Days left in Trial
define( '_RENEW_INFO', 'Du använder abonnemang/ återkommande betalningar.'); //You are using recurring payments.
define( '_RENEW_OFFLINE', 'Förnya'); //Renew
define( '_RENEW_BUTTON_UPGRADE', 'Uppgradera/Förnya'); //Upgrade/Renew
define( '_PAYMENT_PENDING_REASON_ECHECK', 'Väntande e-check. (1-4 bankdagar) '); //echeck uncleared (1-4 business days)
define( '_PAYMENT_PENDING_REASON_TRANSFER', 'väntar på överföring av betalning'); //awaiting transfer payment
define( '_YOUR_SUBSCRIPTION', 'Ditt medlemskap'); //Your Membership
define( '_YOUR_FURTHER_SUBSCRIPTIONS', 'Ytterligare abonnemang'); //Further Memberships
define( '_PLAN_PROCESSOR_ACTIONS', 'För detta har du följande alternativ:'); //For this, you have the following options:
define( '_AEC_SUBDETAILS_TAB_OVERVIEW', 'Översikt'); //Overview
define( '_AEC_SUBDETAILS_TAB_INVOICES', 'Fakturor'); //Invoices
define( '_AEC_SUBDETAILS_TAB_DETAILS', 'Detaljer'); //Details

define( '_HISTORY_ACTION_PRINT', 'Skriv ut'); //print
define( '_INVOICEPRINT_DATE', 'Datum'); //Date
define( '_INVOICEPRINT_ID', 'ID'); //ID
define( '_INVOICEPRINT_REFERENCE_NUMBER', 'Referens nummer'); //Reference Number
define( '_INVOICEPRINT_ITEM_NAME', 'Benämning'); //Item Name
define( '_INVOICEPRINT_UNIT_PRICE', 'á-pris'); //Unit Price
define( '_INVOICEPRINT_QUANTITY', 'Antal'); //Quantity
define( '_INVOICEPRINT_TOTAL', 'Summa'); //Total
define( '_INVOICEPRINT_GRAND_TOTAL', 'Totalsumma'); //Grand Total

define( '_INVOICEPRINT_ADDRESSFIELD', 'Ange din adress här, så inkluderas den i fakturautskriften.'); //Enter your Address here - it will then show on the printout.
define( '_INVOICEPRINT_PRINT', 'Skriv ut'); //Print
define( '_INVOICEPRINT_BLOCKNOTICE', 'Varken denna ruta, text-fältet ovan, eller skriv-ut knappen kommer att synas på den utskrivna fakturan.'); //This block (including the text field and print button) will not show on your printout.
define( '_INVOICEPRINT_PRINT_TYPEABOVE', 'Skriv in din adress i rutan ovan, tack!'); //Please type your address into the field above.
define( '_INVOICEPRINT_PAIDSTATUS_UNPAID', '<strong>Denna faktura är EJ BETALD.</strong>'); //<strong>This invoice has not been paid yet.</strong>
define( '_INVOICEPRINT_PAIDSTATUS_PAID', 'Denna faktura betalades den %s'); //This invoice has been paid on: %s'

define( '_AEC_YOUSURE', 'Är du säker?'); //Are you sure?

define( '_AEC_WILLEXPIRE', 'This membership will expire');
define( '_AEC_WILLRENEW', 'This membership will renew');
define( '_AEC_ISLIFETIME', 'Lifetime Membership');

// --== EXPIRATION PAGE ==--
define( '_EXPIRE_INFO', 'Din prenumeration gäller till och med'); //Your account is active until
define( '_RENEW_BUTTON', 'Förnya nu'); //Renew Now
define( '_RENEW_BUTTON_CONTINUE', 'Förläng tidigare abonnemang'); //Extend Previous Membership
define( '_ACCT_DATE_FORMAT', '%d-%m-%Y'); //%d-%m-%Y
define( '_EXPIRED', 'Din prenumeration löpte ut den'); //You Subscription has expired on:
define( '_EXPIRED_TRIAL', 'Din provperiod löpte ut den'); //Your trial period has expired on
define( '_ERRTIMESTAMP', 'Kan ej konvertera tidstämpel.'); //Cannot convert timestamp.
define( '_EXPIRED_TITLE', 'Prenumeration har gått ut!'); //Subscription Expired!
define( '_DEAR', 'Hej %s'); //Dear %s

// --== CONFIRMATION FORM ==--
define( '_CONFIRM_TITLE', 'Orderbekräftelse'); //Confirmation
define( '_CONFIRM_COL1_TITLE', 'Konto'); //Account
define( '_CONFIRM_COL2_TITLE', 'Detaljer'); //Detail
define( '_CONFIRM_COL3_TITLE', 'Belopp'); //Amount
define( '_CONFIRM_ROW_NAME', 'Namn: '); //Name
define( '_CONFIRM_ROW_USERNAME', 'Användarnamn: '); //Username
define( '_CONFIRM_ROW_EMAIL', 'E-post:'); //E-mail
define( '_CONFIRM_INFO', 'Klicka på Fortsätt-knappen för att fullfölja ditt köp.'); //Please use the Continue-Button to complete your registration.
define( '_BUTTON_CONFIRM', 'Fortsätt'); //Continue
define( '_CONFIRM_TOS', "Jag har läst och godkänt <a href=\"%s\" target=\"_blank\" title=\"ToS\">villkoren</a>."); //I have read and agree to the <a href=\"%s\" target=\"_blank\" title=\"ToS\">Terms of Service</a>.
define( '_CONFIRM_TOS_IFRAME', "Jag har läst och godkänt villkoren (ovan)"); //I have read and agree to the Terms of Service (above)
define( '_CONFIRM_TOS_ERROR', 'Du måste först läsa och godkänna våra villkor.'); //Please read and agree to our Terms of Service.
define( '_CONFIRM_COUPON_INFO', 'Om du har en kupongkod kan du ange den i kassan, och då dras din rabatt av automatiskt.'); //If you have a coupon code, you can enter it on the Checkout Page to get a discount on your payment.
define( '_CONFIRM_COUPON_INFO_BOTH', 'Om du har en kupongkod kan du ange den här, eller i kassan, så dras din rabatt av automatiskt.'); //If you have a coupon code, you can enter it here, or on the Checkout Page to get a discount on your payment.
define( '_CONFIRM_FREETRIAL', 'Gratis prova-på-period'); //Free Trial
define( '_CONFIRM_YOU_HAVE_SELECTED', 'You have selected');

define( '_CONFIRM_DIFFERENT_USER_DETAILS', 'Want to change the user details?');
define( '_CONFIRM_DIFFERENT_ITEM', 'Wanted to select a different item?');

// --== SHOPPING CART FORM ==--
define( '_CART_TITLE', 'Varukorg'); //Shopping Cart
define( '_CART_ROW_TOTAL', 'Summa'); //Total
define( '_CART_INFO', 'Använd knappen Fortsätt nedan för att fullfölja ditt köp.'); //Please use the continue button below to complete your purchase.

// --== EXCEPTION FORM ==--
define( '_EXCEPTION_TITLE', 'Ytterligare information behövs'); //Additional Information Required
define( '_EXCEPTION_TITLE_NOFORM', 'Observera:'); //Please note:
define( '_EXCEPTION_INFO', 'För att slutföra ditt köp behöver vi ytterligare information från dig:'); //To proceed with your checkout, we need you to provide additional information as specified below:

// --== PROMPT PASSWORD FORM ==--
define( '_AEC_PROMPT_PASSWORD', 'Ange ditt lösenord för att fortsätta.'); //For security reasons, you need to put in your password to continue
define( '_AEC_PROMPT_PASSWORD_WRONG', 'Felaktigt lösenord. Prova igen.'); //he Password you have entered does not match with the one we have registered for you in our database. Please try again
define( '_AEC_PROMPT_PASSWORD_BUTTON', 'Fortsätt'); //Continue

// --== CHECKOUT FORM ==--
define( '_CHECKOUT_TITLE', 'Kassa'); //Checkout
define( '_CHECKOUT_INFO', 'Din beställning har registrerats hos oss. Här kan du betala fakura %s. <br /><br /> Om något går under betalningsprocessn kan du alltid komma tillbaka till denna sida genom att logga in på vår sida med ditt användarnamn och lösenord. Du kommmer då att få en ny chans att fullfölja din betalning.'); //our Registration has been saved now. On this page, you can complete your payment for invoice %s. <br /><br /> If something goes wrong along the way, you can always come back to this step by logging in to our site with your username and password - Our System will give you an option to try your payment again.
define( '_CHECKOUT_INFO_REPEAT', 'Välkommen tillbaka! Här kan du betala fakura %s. <br /><br /> Om något går under betalningsprocessn kan du alltid komma tillbaka till denna sida genom att logga in på vår sida med ditt användarnamn och lösenord. Du kommmer då att få en ny chans att fullfölja din betalning.'); //Thank you for coming back. On this page, you can complete your payment for invoice %s. <br /><br /> If something goes wrong along the way, you can always come back to this step by logging in to our site with your username and password - Our System will give you an option to try your payment again.
define( '_BUTTON_CHECKOUT', 'Kassan'); //Checkout
define( '_BUTTON_APPEND', 'Lägg till'); //Append
define( '_BUTTON_APPLY', 'Spara'); //Apply
define( '_BUTTON_EDIT', 'Ändra'); //Edit
define( '_BUTTON_SELECT', 'Välj'); //Select
define( '_CHECKOUT_COUPON_CODE', 'Kupongkod'); //Coupon Code'
define( '_CHECKOUT_INVOICE_AMOUNT', 'Fakturabelopp'); //Invoice Amount
define( '_CHECKOUT_INVOICE_COUPON', 'Kupong'); //Coupon
define( '_CHECKOUT_INVOICE_COUPON_REMOVE', 'Ta bort'); //remove
define( '_CHECKOUT_INVOICE_TOTAL_AMOUNT', 'Totalsumma'); //Total Amount
define( '_CHECKOUT_COUPON_INFO', 'Om du har en kupongkod, ange den här så dras din rabatt automatiskt.'); //If you have a coupon code, you can enter it here to get a discount on your payment
define( '_CHECKOUT_GIFT_HEAD', 'Presentkort'); //Gift to another user
define( '_CHECKOUT_GIFT_INFO', 'Ange användaruppgifterna för den avändare du vill ge detta presentkort till.'); //Enter details for another user of this site to give the item(s) you are about to purchase to that account.

define( '_AEC_TERMTYPE_TRIAL', 'Första faktureringstillfället'); //Initial Billing
define( '_AEC_TERMTYPE_TERM', 'Normalt faktureringstillfälle'); //Regular Billing Term
define( '_AEC_CHECKOUT_TERM', 'Faktureringsperiod'); //Billing Term'
define( '_AEC_CHECKOUT_NOTAPPLICABLE', '- '); //not applicable
define( '_AEC_CHECKOUT_FUTURETERM', 'Kommand period'); //future term
define( '_AEC_CHECKOUT_COST', 'Pris'); //Cost
define( '_AEC_CHECKOUT_TAX', 'Moms'); //Tax
define( '_AEC_CHECKOUT_DISCOUNT', 'Rabatt'); //Discount
define( '_AEC_CHECKOUT_TOTAL', 'Summa'); //Total
define( '_AEC_CHECKOUT_DURATION', 'Längd'); //Duration

define( '_AEC_CHECKOUT_DUR_LIFETIME', 'Livslångt medlemskap'); //Lifetime

define( '_AEC_CHECKOUT_DUR_DAY', 'dag'); //Day
define( '_AEC_CHECKOUT_DUR_DAYS', 'dagar'); //Days
define( '_AEC_CHECKOUT_DUR_WEEK', 'vecka'); //Week
define( '_AEC_CHECKOUT_DUR_WEEKS', 'veckor'); //Weeks
define( '_AEC_CHECKOUT_DUR_MONTH', 'månad'); //Month
define( '_AEC_CHECKOUT_DUR_MONTHS', 'månader'); //Months
define( '_AEC_CHECKOUT_DUR_YEAR', 'år'); //Year
define( '_AEC_CHECKOUT_DUR_YEARS', 'år'); //Years');

// --== ALLOPASS SPECIFIC ==--
define( '_REGTITLE','INSCRIPTION'); //
define( '_ERRORCODE','Erreur de code Allopass'); //
define( '_FTEXTA','Le code que vous avez utilis n\'est pas valide! Pour obtenir un code valable, composez le numero de tlphone, indiqu dans une fenetre pop-up, aprs avoir clicker sur le drapeau de votre pays. Votre browser doit accepter les cookies d\'usage.<br><br>Si vous tes certain, que vous avez le bon code, attendez quelques secondes et ressayez encore une fois!<br><br>Sinon prenez note de la date et heure de cet avertissement d\'erreur et informez le Webmaster de ce problme en indiquant le code utilis.'); //
define( '_RECODE','Saisir de nouveau le code Allopass'); //

// --== REGISTRATION STEPS ==--
define( '_STEP_DATA', 'Dina uppgifter'); //Your Data
define( '_STEP_CONFIRM', 'Bekräfta'); //Confirm
define( '_STEP_PLAN', 'Välj alternativ'); //Select Plan
define( '_STEP_EXPIRED', 'Prenumerationen har gått ut!'); //Expired!

// --== NOT ALLOWED PAGE ==--
define( '_NOT_ALLOWED_HEADLINE', 'Endast för medlemmar!'); //Membership required!
define( '_NOT_ALLOWED_FIRSTPAR', 'Detta innehåll är bara tillgängligt för registrerade medlemmar. Är du redan medlem behöver du först logga in för att se sidan. Följ denna länk för att bli medlem: '); //The Content you are trying to see is available only for members of our site. If you already have a Membership you need to log in to see it. Please follow this link if you want to register:
define( '_NOT_ALLOWED_REGISTERLINK', 'Registrera medlemskap'); //Registration Page
define( '_NOT_ALLOWED_FIRSTPAR_LOGGED', 'Detta innehåll är bara tillgängligt för betalande medlemmar. Följ denna länk för att köpa eller uppdatera ditt abonnemang: '); //The Content you are trying to see is available only for members of our site who have a certain subscription. Please follow this link if you want to change your subscription:
define( '_NOT_ALLOWED_REGISTERLINK_LOGGED', 'Abonnemang'); //Subscription Page
define( '_NOT_ALLOWED_SECONDPAR', 'Det går snabbt och enkelt att registrera sig - vi använder oss av:'); //Joining will take you less than a minute - we use the service of:

// --== CANCELLED PAGE ==--
define( '_CANCEL_TITLE', 'Prenumeration: Avbruten!'); //Subscription Result: Cancelled!
define( '_CANCEL_MSG', 'Du har valt att avbryta betalningen av din prenumeration. Om detta beror på problem du upplevt på vår sida, får du gärna ta kontakt med oss så ska vi försöka hjälpa dig vidare. Se vidare under "Kontakta oss". '); //Our System has received the message, that you have chosen to cancel your payment. If this is due to problems that you encountered with our site, please don\'t hesitate to contact us!

// --== PENDING PAGE ==--
define( '_PENDING_TITLE', 'Konto kontrolleras'); //Account Pending
define( '_WARN_PENDING', 'Ditt konto kontrolleras eller har blivit tillfälligt avstängt. Om problemet kvarstår även efter ett par timmar och din betalning är godkänd, vänligen kontakta oss.'); //Your account is pending or temporarily suspended. If you are in this state for more than some hours and your payment is confirmed, please contact the administrator of this web site.
define( '_PENDING_OPENINVOICE', 'Det verkar som om du har en obetald faktura i vårt register. Om du glömt fullfölja betalningen eller något annat problem hände under tiden kan du alltid gå till Kassan för att betala fakturan igen:'); //
define( '_GOTO_CHECKOUT', 'Gå till Kassan igen'); //Go to the checkout page again
define( '_GOTO_CHECKOUT_CANCEL', 'you may also cancel the payment (you will have the possibility to go to the Plan Selection screen once again):'); //you may also cancel the payment (you will have the possibility to go to the Plan Selection screen once again):
define( '_PENDING_NOINVOICE', 'It appears that you have cancelled the only invoice that was left for your account. Please use the button below to go to the Plan Selection page again:'); //It appears that you have cancelled the only invoice that was left for your account. Please use the button below to go to the Plan Selection page again:
define( '_PENDING_NOINVOICE_BUTTON', 'Plan Selection'); //Plan Selection
define( '_PENDING_REASON_ECHECK', '(According to our information however, you decided to pay by echeck (or similar), so you it might be that you just have to wait until this payment is cleared - which usually takes 1-4 days.)'); //(According to our information however, you decided to pay by echeck (or similar), so you it might be that you just have to wait until this payment is cleared - which usually takes 1-4 days.)
define( '_PENDING_REASON_WAITING_RESPONSE', '(According to our information however, we are just waiting for a response from the payment processor. You will be notified once that has happened. Sorry for the delay.)'); //(According to our information however, we are just waiting for a response from the payment processor. You will be notified once that has happened. Sorry for the delay.)
define( '_PENDING_REASON_TRANSFER', '(According to our information however, you decided to pay by an offline payment means, so you it might be that you just have to wait until this payment is cleared - which can take a couple of days.)'); //(According to our information however, you decided to pay by an offline payment means, so you it might be that you just have to wait until this payment is cleared - which can take a couple of days.)

// --== HOLD PAGE ==--
define( '_HOLD_TITLE', 'Konta inväntar bekräftelse'); //Account on Hold
define( '_HOLD_EXPLANATION', 'Ditt konto väntar fortfarande på godkännande. Detta vanligaste skälet till detta är att ett problem uppstått i samband med betalningen du nyss genomförde. Om du inte får ett e-postmeddelande från oss inom de närmaste 24 timmarna får du gärna ta kontakt med oss.'); //Your account is currently on hold. The most likely cause for this is that there was a problem with a payment you recently made. If you don\'t receive an email within the next 24 hours, please contact the site administrator.

// --== THANK YOU PAGE ==--
define( '_THANKYOU_TITLE', 'Tack så mycket!'); //Thank You!
define( '_SUB_FEPARTICLE_HEAD', 'Prenumeration slutförd!'); //Subscription Complete!
define( '_SUB_FEPARTICLE_HEAD_RENEW', 'Din prenumeration är nu förnyad!'); //Subscription Renew Complete!
define( '_SUB_FEPARTICLE_LOGIN', 'Du kan nu logga in.'); //You may login now
define( '_SUB_FEPARTICLE_THANKS', 'Tack för att du registrerat dig hos oss.'); //Thank you for your registration
define( '_SUB_FEPARTICLE_THANKSRENEW', 'Tack för att du förnyat din prenumeration. '); //Thank you for renewing your subscription.
define( '_SUB_FEPARTICLE_PROCESS', 'Vi arbetar nu med din beställning. '); //Our system will now work on your request.
define( '_SUB_FEPARTICLE_PROCESSPAY', 'Vi väntar nu på din betalning. '); //Our system will now await your payment.
define( '_SUB_FEPARTICLE_ACTMAIL', 'Du kommer att få ett e-postmeddelande med en aktivieringskod så snart vårt system behandlat din beställning.'); //You will receive an e-mail with an activation link once our system has processed your request.
define( '_SUB_FEPARTICLE_MAIL', 'Du kommer att få ett e-postmeddelande så snart vårt system behandlat din beställning.'); //You will receive an e-mail once our system has processed your request.

// --== CHECKOUT ERROR PAGE ==--
define( '_CHECKOUT_ERROR_TITLE', 'Problem med betalningen!'); //Error while processing the payment!
define( '_CHECKOUT_ERROR_EXPLANATION', 'Ett fel inträffade under betalningsprocessen.'); //An error occurred while processing your payment
define( '_CHECKOUT_ERROR_OPENINVOICE', 'Detta innebär att fakturan fortfarande är obetald. För att betala fakturan, gå tillbaka till kassan och försök igen:'); //This leaves your invoice uncleared. To retry the payment, you can go to the checkout page once again to try again:
define( '_CHECKOUT_ERROR_FURTHEREXPLANATION', 'This leaves your invoice uncleared, but you can try to check out again below. If you experience further problems or need any assistance with your checkout, please do not hesitate to contact us.');

// --== COUPON INFO ==--
define( '_COUPON_INFO', 'Kupong:'); //Coupons
define( '_COUPON_INFO_CONFIRM', 'Om du vill använda en eller flera kupongkoder, kan du ange dem i kassan.'); //If you want to use one or more coupons for this payment, you can do so on the checkout page
define( '_COUPON_INFO_CHECKOUT', 'Skriv in din kupongkod här och klicka på Lägg till.'); //Please enter your coupon code here and click the button to append it to this payment

// --== COUPON ERROR MESSAGES ==--
define( '_COUPON_WARNING_AMOUNT', 'En av de kupongkoder du angett påverkar inte nuvarande faktura. Trots att det inte verkar som om den påverkar fakturan nu, kommer den att påverka kommande betalning(ar).'); //One Coupon that you have added to this invoice does not affect the next payment, so although it seems that it does not affect this invoice, it will affect a subsequent payment.
define( '_COUPON_ERROR_PRETEXT', 'Tyvärr:'); //We are sorry:
define( '_COUPON_ERROR_EXPIRED', 'Giltighetstiden för denna kupong har gått ut. .'); //This coupon has expired.
define( '_COUPON_ERROR_NOTSTARTED', 'Denna kupong är inte giltig ännu.'); //Using this coupon is not permitted yet.
define( '_COUPON_ERROR_NOTFOUND', 'Denna kupong kod hittades ej.'); //This coupon code could not be found.
define( '_COUPON_ERROR_MAX_REUSE', 'Denna kupong har redan använts maximalt antal gånger.'); //This coupon has exceeded the maximum uses.
define( '_COUPON_ERROR_PERMISSION', 'Du har ej tillåtelse att använda denna kupong.'); //You don\'t have the permission to use this coupon.
define( '_COUPON_ERROR_WRONG_USAGE', 'Du kan inte använda kupong för detta ändamål.'); //You can not use this coupon for this.
define( '_COUPON_ERROR_WRONG_PLAN', 'Du har inte rätt prenumerations alternativ för att använda denna kupong.'); //You are not in the correct Subscription Plan for this coupon.
define( '_COUPON_ERROR_WRONG_PLAN_PREVIOUS', 'För att använda denna kupong, krävs en annan prenumeration som senaste prenumeration. '); //To use this coupon, your last Subscription Plan must be different.
define( '_COUPON_ERROR_WRONG_PLANS_OVERALL', 'Du har inte rätt prenumeration i din historik för att få använda denna kupong.'); //You don\'t have the right Subscription Plans in your usage history to be allowed to use this coupon.
define( '_COUPON_ERROR_TRIAL_ONLY', 'Du kan endast använda denna kupong för en prova-på period.'); //You may only use this coupon for a Trial Period.
define( '_COUPON_ERROR_COMBINATION', 'Du kan inte använda denna kupong tillsammans med en av de andra kupongerna.'); //You cannot use this coupon with one of the other coupons.
define( '_COUPON_ERROR_SPONSORSHIP_ENDED', 'Denna kupong stöds inte för tillfället eller har helt upphört.'); //Sponsorship for this Coupon has ended or is currently inactive.

// ----======== EMAIL TEXT ========----
define( '_AEC_SEND_SUB',			"Kontouppgifter för %s hos %s" ); //Account details for %s at %s
define( '_AEC_USEND_MSG',			"Hej %s,\n\nTack för att du registrerat dig hos %s.\n\nDu kan nu logga in på %s med ditt användarnamn och lösenord." ); //Hello %s,\n\nThank you for registering at %s.\n\nYou may now login to %s using the username and password you registered with.
define( '_AEC_USEND_MSG_ACTIVATE',		"Hej %s,\n\nTack för att du registrerat dig hos %s. Ditt konto har skapats men måste aktiveras innan du kan logga in.\nKlicka på länken för att aktivera ditt konto, eller kopiera adressen och klistra in den i din webbläsare:\n%s\n\nNär kontot aktiverats kan du logga in hos %s med följande uppgifter:\n\nAnvändarnamn - %s\nLösenord - %s" ); //Hello %s,\n\nThank you for registering at %s. Your account is created and must be activated before you can use it.\nTo activate the account click on the following link or copy-paste it in your browser:\n%s\n\nAfter activation you may login to %s using the following username and password:\n\nUsername - %s\nPassword - %s
define( '_ACCTEXP_SEND_MSG',			"Prenumeration för %s hos %s"); //Subscription for %s at %s
define( '_ACCTEXP_SEND_MSG_RENEW',		"Prenumeration förnyad för %s hos %s"); //Subscription renew for %s at %s
define( '_ACCTEXP_MAILPARTICLE_GREETING', 	"Hej %s, \n\n"); //Hello %s, \n\n
define( '_ACCTEXP_MAILPARTICLE_THANKSREG', 	"Tack för att du registrerat dig hos %s."); //Thank you for registering at %s.
define( '_ACCTEXP_MAILPARTICLE_THANKSREN', 	"Tack för att du förnyat din prenumeration hos %s."); //Thank you for renewing your subscription at %s.
define( '_ACCTEXP_MAILPARTICLE_PAYREC', 	"Vi har tagit emot betalningen för ditt medlemskap."); //Your payment for your membership has been received.
define( '_ACCTEXP_MAILPARTICLE_LOGIN', 		"Du kan nu logga in hos %s med ditt användarnamn och lösenord."); //You may now login to %s with your username and password.
define( '_ACCTEXP_MAILPARTICLE_FOOTER',		"\n\nObservera. Detta e-postmeddelande är ett automatiskt utskick och går inte att svara på."); //\n\nPlease do not respond to this message as it is automatically generated and is for information purposes only.
define( '_ACCTEXP_ASEND_MSG',			"Hej %s,\n\nEn ny användare har skapat en prenumeration hos [ %s ].\n\nHär följer ytterligare detaljer:\n\nNamn.........: %s\nE-post.......: %s\nAnvändarnamn.: %s\nPrenum.-ID...: %s\nPrenumeration: %s\nIP...........: %s\nISP..........: %s\n\nObservera. Detta e-postmeddelande är ett automatiskt utskick och går inte att svara på." ); //Hello %s,\n\nA new user has created a subscription at [ %s ].\n\nHere further details:\n\nName.........: %s\nEmail........: %s\nUsername.....: %s\nSubscr.-ID...: %s\nSubscription.: %s\nIP...........: %s\nISP..........: %s\n\nPlease do not respond to this message as it is automatically generated and is for information purposes only.
define( '_ACCTEXP_ASEND_MSG_RENEW',		"Hej %s,\n\nEn användare har förnyat sin prenumeration hos [ %s ].\n\nHär följer ytterligare detaljer:\n\nNamn.........: %s\nE-post.......: %s\nAnvändarnamn.: %s\nPrenum.-ID...: %s\nPrenumeration: %s\nIP...........: %s\nISP..........: %s\n\nObservera. Detta e-postmeddelande är ett automatiskt utskick och går inte att svara på." ); //Hello %s,\n\nA user has renewed his subscription at [ %s ].\n\nHere further details:\n\nName.........: %s\nEmail........: %s\nUsername.....: %s\nSubscr.-ID...: %s\nSubscription.: %s\nIP...........: %s\nISP..........: %s\n\nPlease do not respond to this message as it is automatically generated and is for information purposes only.
define( '_AEC_ASEND_MSG_NEW_REG',		"Hej %s,\n\nEn ny användare har registrerat sig hos [ %s ].\n\nHär följer ytterligare detaljer:\n\nNamn.........: %s\nE-post.......: %s\nAnvändarnamn.: %s\nIP...........: %s\nISP..........: %s\n\nObservera. Detta e-postmeddelande är ett automatiskt utskick och går inte att svara på." ); //Hello %s,\n\nThere has been a new registration at [ %s ].\n\nHere further details:\n\nName.....: %s\nEmail.: %s\nUsername....: %s\nIP.......: %s\nISP......: %s\n\nPlease do not respond to this message as it is automatically generated and is for information purposes only.
define( '_AEC_ASEND_NOTICE',			"AEC %s: %s på %s" ); //AEC %s: %s at %s
define( '_AEC_ASEND_NOTICE_MSG',		"Du har i inställningarna för logghändelser angett att du vill få meddelande vid denna typ av logghändelser.\n\nHär följer ytterligare detaljer:\n\n--- --- --- ---\n\n%s\n\n--- --- --- ---\n\nObservera. Detta e-postmeddelande är ett automatiskt utskick och går inte att svara på. För att ändra vilka logghändelser som genererar ett e-postmeddelande, gå till AEC och fliken Inställningar (Settings)." ); //According to the E-Mail reporting level you have selected, this is an automatic notification about an EventLog entry.\n\nThe details of this message are:\n\n--- --- --- ---\n\n%s\n\n--- --- --- ---\n\nPlease do not respond to this message as it is automatically generated and is for information purposes only. You can change the level of reported entries in your AEC Settings.

// ----======== COUNTRY CODES ========----

define( 'COUNTRYCODE_SELECT', 'Välj land' ); //Select Country

define( 'COUNTRYCODE_AF', 'Afghanistan' );

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
