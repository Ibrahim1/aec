<?php
/**
 * @version $Id: danish.php
 * @package AEC - Account Control Expiration - Membership Manager
 * @subpackage Language - Frontend - Danish
 * @copyright 2006-2009 Copyright (C) Svend Gundestrup
 * @author Svend Gundestrup <svend@gundestrup.dk>
 * @license GNU/GPL v.2 http://www.gnu.org/licenses/old-licenses/gpl-2.0.html or, at your option, any later version
 */

// Dont allow direct linking
( defined('_JEXEC') || defined( '_VALID_MOS' ) ) or die( 'Direct Access to this location is not allowed.' );

if( defined( '_AEC_LANG' ) ) {
	return;
}

// new 0.12.4 - mic

define( '_AEC_EXPIRE_TODAY',				'Denne konto udl&oslash;ber idag' );
define( '_AEC_EXPIRE_FUTURE',				'Denne konto er aktiv indtil' );
define( '_AEC_EXPIRE_PAST',					'Denne konto var aktiv indtil' );
define( '_AEC_DAYS_ELAPSED',				'dage siden');
define( '_AEC_EXPIRE_TRIAL_TODAY',			'Pr&oslash;vetiden er aktiv til idag' );
define( '_AEC_EXPIRE_TRIAL_FUTURE',			'Pr&oslash;vetiden er aktiv indtil' );
define( '_AEC_EXPIRE_TRIAL_PAST',			'Pr&oslash;vetiden var aktiv indtil' );

define( '_AEC_EXPIRE_NOT_SET',				'Ikke sat' );
define( '_AEC_GEN_ERROR',					'<h1>Generel fejl</h1><p>Vi har problemer med din betaling. Kontakt venligst sidens administrator.</p>' );

// payments
define( '_AEC_PAYM_METHOD_FREE',			'Gratis' );
define( '_AEC_PAYM_METHOD_NONE',			'Ingen' );
define( '_AEC_PAYM_METHOD_TRANSFER',		'Overf&oslash;r' );

// processor errors
define( '_AEC_MSG_PROC_INVOICE_FAILED_SH',			'Fejl ved betaling af faktura' );
define( '_AEC_MSG_PROC_INVOICE_FAILED_EV',			'Proces %s bem&aelig;rkning for %s har fejlet - fakturanummeret eksisterer ikke:' );
define( '_AEC_MSG_PROC_INVOICE_ACTION_SH',			'Faktura betaling' );
define( '_AEC_MSG_PROC_INVOICE_ACTION_EV',			'Betalings informations process bem&aelig;rkning:' );
define( '_AEC_MSG_PROC_INVOICE_ACTION_EV_STATUS',	'Faktura status:' );
define( '_AEC_MSG_PROC_INVOICE_ACTION_EV_FRAUD',	'Bel&oslash;bs verifikation fejlet, betal: %s, faktura: %s - betaling annulleret' );
define( '_AEC_MSG_PROC_INVOICE_ACTION_EV_CURR',		'Valuta verifikation fejlet, betal %s, faktura: %s - betaling annulleret' );
define( '_AEC_MSG_PROC_INVOICE_ACTION_EV_VALID',	'Betaling korrekt, handlingen udf&oslash;rt' );
define( '_AEC_MSG_PROC_INVOICE_ACTION_EV_VALID_APPFAIL',	'Betaling korrekt, software fejl!' );
define( '_AEC_MSG_PROC_INVOICE_ACTION_EV_TRIAL',	'Betaling korrekt - gratis pr&oslash;ve' );
define( '_AEC_MSG_PROC_INVOICE_ACTION_EV_PEND',		'Betaling forkert - status er under opdatering pga: %s' );
define( '_AEC_MSG_PROC_INVOICE_ACTION_EV_CANCEL',	'Ingen betaling - Abonnement annulleret' );
define( '_AEC_MSG_PROC_INVOICE_ACTION_EV_CHARGEBACK',	'Ingen betaling - tilbagef&oslash;rsel' );
define( '_AEC_MSG_PROC_INVOICE_ACTION_EV_CHARGEBACK_SETTLE',	'Ingen betaling - tilbagef&oslash;rsels aftale' );
define( '_AEC_MSG_PROC_INVOICE_ACTION_EV_USTATUS',	', Bruger status er opdateret til \'Annuleret\'' );
define( '_AEC_MSG_PROC_INVOICE_ACTION_EV_USTATUS_HOLD',	', Bruger status er opdateret til \'Stoppet\'' );
define( '_AEC_MSG_PROC_INVOICE_ACTION_EV_USTATUS_ACTIVE',	', Bruger status er opdateret til \'Aktiv\'' );
define( '_AEC_MSG_PROC_INVOICE_ACTION_EV_EOT',		'Ingen betaling - Abonnement udl&oslash;bet' );
define( '_AEC_MSG_PROC_INVOICE_ACTION_EV_DUPLICATE','Ingen betaling - Duplet' );
define( '_AEC_MSG_PROC_INVOICE_ACTION_EV_NULL','Ingen betaling - Nul' );
define( '_AEC_MSG_PROC_INVOICE_ACTION_EV_U_ERROR',	'Ukendt fejl' );
define( '_AEC_MSG_PROC_INVOICE_ACTION_EV_REFUND',	'Ingen betaling - Abonnement slettet (refundering)' );
define( '_AEC_MSG_PROC_INVOICE_ACTION_EV_EXPIRED',	', Brugerabonnement er udl&oslash;bet' );

// end mic ########################################################

// --== PAYMENT PLANS PAGE ==--
define( '_PAYPLANS_HEADER', 'Betalingsmuligheder');
define( '_NOPLANS_ERROR', 'Der er ingen tilgængelige betalingsmuligheder. Kontakt venligst administrator.');
define( '_NOPLANS_AUTHERROR', 'Du har ikke adgang til dette. Kontakt venligst sidens administrator, hvis du har yderligere sp&oslash;rgsm&aring;l.');
define( '_PLANGROUP_BACK', '&lt; G&aring; tilbage');

// --== ACCOUNT DETAILS PAGE ==--
define( '_CHK_USERNAME_AVAIL', "Brugernavnet %s er ledigt");
define( '_CHK_USERNAME_NOTAVAIL', "Brugernavnet %s er desv&aelig;rre optaget!");

// --== MY SUBSCRIPTION PAGE ==--
define( '_MYSUBSCRIPTION_TITLE', 'Mit abonnement');
define( '_MEMBER_SINCE', 'Medlem siden');
define( '_HISTORY_COL1_TITLE', 'Faktura');
define( '_HISTORY_COL2_TITLE', 'Bel&oslash;b');
define( '_HISTORY_COL3_TITLE', 'Betalings dato');
define( '_HISTORY_COL4_TITLE', 'Metode');
define( '_HISTORY_COL5_TITLE', 'Handling');
define( '_HISTORY_COL6_TITLE', 'Plan');
define( '_HISTORY_ACTION_REPEAT', 'betal');
define( '_HISTORY_ACTION_CANCEL', 'annuller');
define( '_RENEW_LIFETIME', 'Du har et livstids-abonnement.');
define( '_RENEW_DAYSLEFT_EXCLUDED', 'Dit abonnement har ingen udl&oslash;bsdato');
define( '_RENEW_DAYSLEFT_INFINITE', '&#8734');
define( '_RENEW_DAYSLEFT', 'Dage tilbage');
define( '_RENEW_DAYSLEFT_TRIAL', 'Dage tilbage af pr&oslash;vetiden');
define( '_RENEW_INFO', 'Du bruger gentagne betalinger.');
define( '_RENEW_OFFLINE', 'Forny');
define( '_RENEW_BUTTON_UPGRADE', 'Opgrader/Forny');
define( '_PAYMENT_PENDING_REASON_ECHECK', 'echeck uafklaret (1-4 forretnings dage)');
define( '_PAYMENT_PENDING_REASON_TRANSFER', 'afventer betaling');
define( '_YOUR_SUBSCRIPTION', 'Dit abonnement');
define( '_YOUR_FURTHER_SUBSCRIPTIONS', 'Fremtidigt medlemsskab');
define( '_PLAN_PROCESSOR_ACTIONS', 'For dette har du flg. muligheder');
define( '_AEC_SUBDETAILS_TAB_OVERVIEW', 'Oversigt');
define( '_AEC_SUBDETAILS_TAB_INVOICES', 'Faktura');
define( '_AEC_SUBDETAILS_TAB_DETAILS', 'Detaljer');

define( '_HISTORY_ACTION_PRINT', 'print');
define( '_INVOICEPRINT_DATE', 'Dato');
define( '_INVOICEPRINT_ID', 'ID');
define( '_INVOICEPRINT_REFERENCE_NUMBER', 'Reference-nummer');
define( '_INVOICEPRINT_ITEM_NAME', 'Abonnements-navn');
define( '_INVOICEPRINT_UNIT_PRICE', 'Pris');
define( '_INVOICEPRINT_QUANTITY', 'Antal');
define( '_INVOICEPRINT_TOTAL', 'Total');
define( '_INVOICEPRINT_GRAND_TOTAL', 'Samlet total');

define( '_INVOICEPRINT_ADDRESSFIELD', 'Skriv din adresse her - det vil blive vist på printet.');
define( '_INVOICEPRINT_PRINT', 'Print');
define( '_INVOICEPRINT_BLOCKNOTICE', 'Denne blok (inklusiv tekstfeltet og print-knappen) bliver ikke vist på printet.');
define( '_INVOICEPRINT_PRINT_TYPEABOVE', 'Indtast venligst din adresse i feltet ovenfor.');
define( '_INVOICEPRINT_PAIDSTATUS_UNPAID', '<strong>Denne faktura er ikke betalt endnu.</strong>');
define( '_INVOICEPRINT_PAIDSTATUS_PAID', 'Fakturaen er betalt d.: %s');
define( '_INVOICEPRINT_RECURRINGSTATUS_ONCE', 'Abonnementet faktureres løbende. Fakturabeløbet dækker muligvis den næste fakturering, og ikke den, der blev betalt sidst. Listen over betalingsdatoer ovenfor viser hvilke beløb, der er betalt hvornår.');

define( '_AEC_YOUSURE', 'Er du sikker?');

define( '_AEC_WILLEXPIRE', 'Dette medlemsskab udløber');
define( '_AEC_WILLRENEW', 'Dette medlemsskab bliver fornyet');
define( '_AEC_ISLIFETIME', 'Livslangt medlemsskab');

// --== EXPIRATION PAGE ==--
define( '_EXPIRE_INFO', 'Din konto er aktiv indtil');
define( '_RENEW_BUTTON', 'Forny nu');
define( '_RENEW_BUTTON_CONTINUE', 'Forl&aelig;ng tidligere medlemsskab');
define( '_ACCT_DATE_FORMAT', '%d-%m-%Y');
define( '_EXPIRED', 'Dit medlemsskab udl&oslash;b: ');
define( '_EXPIRED_TRIAL', 'Din pr&oslash;ve periode udl&oslash;b: ');
define( '_ERRTIMESTAMP', 'Kan ikke konvertere tidspunkt.');
define( '_EXPIRED_TITLE', 'Medlemsskab udl&oslash;bet!');
define( '_DEAR', 'Hej %s');

// --== CONFIRMATION FORM ==--
define( '_CONFIRM_TITLE', 'Bekr&aelig;ft');
define( '_CONFIRM_COL1_TITLE', 'Konto');
define( '_CONFIRM_COL2_TITLE', 'Detaljer');
define( '_CONFIRM_COL3_TITLE', 'Bel&oslash;b');
define( '_CONFIRM_ROW_NAME', 'Navn: ');
define( '_CONFIRM_ROW_USERNAME', 'Brugernavn: ');
define( '_CONFIRM_ROW_EMAIL', 'E-mail:');
define( '_CONFIRM_INFO', 'Klik på Fors&aelig;t for at f&aelig;rdigg&oslash;re din registrering.');
define( '_BUTTON_CONFIRM', 'Fors&aelig;t');
define( '_CONFIRM_TOS', "Jeg har l&aelig;st og accepterer <a href=\"%s\" target=\"_blank\" title=\"ToS\">Betingelser for brug</a>");
define( '_CONFIRM_TOS_IFRAME', "Jeg har l&aelig;st og accepterer Betingelser for brug (som vist ovenfor)");
define( '_CONFIRM_TOS_ERROR', 'Jeg har l&aelig;st og accepterer Betingelser for brug');
define( '_CONFIRM_COUPON_INFO', 'Hvis du har en rabatkode, kan du indtaste den undervejs for at f&aring; rabat p&aring; din betaling');
define( '_CONFIRM_COUPON_INFO_BOTH', 'Hvis du har en rabatkode, kan du indtaste den her eller senere i processen for at f&aring; rabat p&aring; din betaling');
define( '_CONFIRM_FREETRIAL', 'Gratis pr&oslash;veperiode');
define( '_CONFIRM_YOU_HAVE_SELECTED', 'Du har valgt');

define( '_CONFIRM_DIFFERENT_USER_DETAILS', 'Vil du ændre brugeroplysningerne?');
define( '_CONFIRM_DIFFERENT_ITEM', 'Ville du vælge et andet abonnement?');

// --== SHOPPING CART FORM ==--
define( '_CART_TITLE', 'Indkøbskurv');
define( '_CART_ROW_TOTAL', 'Total');
define( '_CART_INFO', 'Klik på Fortsæt nedenfor for at gennemføre købet.');
define( '_CART_CLEAR_ALL', 'Tøm kurven');
define( '_CART_DELETE_ITEM', 'fjern');

// --== EXCEPTION FORM ==--
define( '_EXCEPTION_TITLE', 'Yderligere information påkrævet');
define( '_EXCEPTION_TITLE_NOFORM', 'Vær opmærksom på:');
define( '_EXCEPTION_INFO', 'For at gennemføre købet har vi brug for yderligere oplysninger som specificeret nedenfor:');

// --== PROMPT PASSWORD FORM ==--
define( '_AEC_PROMPT_PASSWORD', 'Af sikkerhedsgrunde skal du indtaste dit kodeord for at fors&aelig;tte.');
define( '_AEC_PROMPT_PASSWORD_WRONG', 'Det indtastede kodeord passer ikke til det, vi har registreret tilh&oslash;rende din konto. Pr&oslash;v venligst igen.');
define( '_AEC_PROMPT_PASSWORD_BUTTON', 'Forts&aelig;t');

// --== CHECKOUT FORM ==--
define( '_CHECKOUT_TITLE', 'Tjek-ud');
define( '_CHECKOUT_INFO', 'Din registrering er gemt. P&aring; denne side kan du afslutte din betaling - %s. <br /> Hvis noget g&aring;r galt undervejs, kan du altid komme tilbage til dette trin, ved at logge ind p&aring; siden med dit brugernavn og kodeord. Vores system vil s&aring; give dig en mulighed for at betale igen.');
define( '_CHECKOUT_INFO_REPEAT', 'Tak fordi du vender tilbage. P&aring; denne side kan du afslutte din betaling - %s. <br /> Hvis noget g&aring;r galt undervejs, kan du altid komme tilbage til dette trin, ved at logge ind p&aring; siden med dit brugernavn og kodeord. Vores system vil s&aring; give dig en mulighed for at betale igen.');
define( '_BUTTON_CHECKOUT', 'Tjek-ud');
define( '_BUTTON_APPEND', 'Tilf&oslash;j');
define( '_BUTTON_APPLY', 'Tilf&oslash;j');
define( '_BUTTON_EDIT', 'Ret');
define( '_BUTTON_SELECT', 'v&aelig;lg');
define( '_CHECKOUT_COUPON_CODE', 'Rabatkode');
define( '_CHECKOUT_INVOICE_AMOUNT', 'Fakturabel&oslash;b');
define( '_CHECKOUT_INVOICE_COUPON', 'Rabatkode');
define( '_CHECKOUT_INVOICE_COUPON_REMOVE', 'fjern');
define( '_CHECKOUT_INVOICE_TOTAL_AMOUNT', 'Totalbel&oslash;b');
define( '_CHECKOUT_COUPON_INFO', 'Hvis du har en rabatkode, kan du skrive den her for at f&aring; rabat p&aring; din betaling');
define( '_CHECKOUT_GIFT_HEAD', 'Gave til en anden bruger');
define( '_CHECKOUT_GIFT_INFO', 'Indtast oplysningerne på en anden bruger på sitet for at give den/de vare(r), du er ved at købe, til den bruger.');

define( '_AEC_TERMTYPE_TRIAL', 'Første betaling');
define( '_AEC_TERMTYPE_TERM', 'Normal pris');
define( '_AEC_CHECKOUT_TERM', 'Pris');
define( '_AEC_CHECKOUT_NOTAPPLICABLE', 'kan ikke anvendes');
define( '_AEC_CHECKOUT_FUTURETERM', 'fremtidig pris');
define( '_AEC_CHECKOUT_COST', 'Pris');
define( '_AEC_CHECKOUT_TAX', 'Moms');
define( '_AEC_CHECKOUT_DISCOUNT', 'Rabat');
define( '_AEC_CHECKOUT_TOTAL', 'Total');
define( '_AEC_CHECKOUT_GRAND_TOTAL', 'Samlet pris');
define( '_AEC_CHECKOUT_DURATION', 'Varighed');

define( '_AEC_CHECKOUT_DUR_LIFETIME', 'Livstid');

define( '_AEC_CHECKOUT_DUR_DAY', 'Dag');
define( '_AEC_CHECKOUT_DUR_DAYS', 'Dage');
define( '_AEC_CHECKOUT_DUR_WEEK', 'Uge');
define( '_AEC_CHECKOUT_DUR_WEEKS', 'Uger');
define( '_AEC_CHECKOUT_DUR_MONTH', 'M&aring;ned');
define( '_AEC_CHECKOUT_DUR_MONTHS', 'M&aring;neder');
define( '_AEC_CHECKOUT_DUR_YEAR', '&Aring;r');
define( '_AEC_CHECKOUT_DUR_YEARS', '&Aring;r');

// --== ALLOPASS SPECIFIC ==--
define( '_REGTITLE','INSCRIPTION');
define( '_ERRORCODE','Erreur de code Allopass');
define( '_FTEXTA','Le code que vous avez utilis n\'est pas valide! Pour obtenir un code valable, composez le numero de tlphone, indiqu dans une fenetre pop-up, aprs avoir clicker sur le drapeau de votre pays. Votre browser doit accepter les cookies d\'usage.<br><br>Si vous tes certain, que vous avez le bon code, attendez quelques secondes et ressayez encore une fois!<br><br>Sinon prenez note de la date et heure de cet avertissement d\'erreur et informez le Webmaster de ce problme en indiquant le code utilis.');
define( '_RECODE','Saisir de nouveau le code Allopass');

// --== REGISTRATION STEPS ==--
define( '_STEP_DATA', 'Dine data');
define( '_STEP_CONFIRM', 'Bekr&aelig;ft');
define( '_STEP_PLAN', 'V&aelig;lg abonnement');
define( '_STEP_EXPIRED', 'Udl&oslash;bet!');

// --== NOT ALLOWED PAGE ==--
define( '_NOT_ALLOWED_HEADLINE', 'Medlemsskab p&aring;kr&aelig;vet!');
define( '_NOT_ALLOWED_FIRSTPAR', 'Det indhold, du pr&oslash;ver at f&aring; adgang til, er kun tilg&aelig;ngeligt for medlemmer. Hvis du allerede er medlem, skal du logge p&aring;. F&oslash;lg dette link hvis du vil blive medlem: ');
define( '_NOT_ALLOWED_REGISTERLINK', 'Registeringsside');
define( '_NOT_ALLOWED_FIRSTPAR_LOGGED', 'Det indhold, du pr&oslash;ve at se, er kun tilg&aelig;ngeligt for medlemmer med et s&aelig;rligt abonnement. F&oslash;lg venligst dette link for at &aelig;ndre dit abonnement: ');
define( '_NOT_ALLOWED_REGISTERLINK_LOGGED', 'Abonnements-side');
define( '_NOT_ALLOWED_SECONDPAR', 'Du kan blive medlem p&aring; mindre end et minut - vi bruger denne service:');

// --== CANCELLED PAGE ==--
define( '_CANCEL_TITLE', 'Abonnements resultat: Annulleret!');
define( '_CANCEL_MSG', 'Vi har fået besked om, at du &oslash;nsker at stoppe din betaling. Hvis det er grundet problemer du har oplevet p&aring; vores side, vil vi bede dig om at kontakte os, så vi kan få løst dem.');

// --== PENDING PAGE ==--
define( '_PENDING_TITLE', 'Konto under behandling');
define( '_WARN_PENDING', 'Din konto er midlertidigt blevet suspenderet eller er under opdatering. Hvis denne besked vises i mere end et par timer og din betaling er bekr&aelig;ftet, så kontakt os venligst.');
define( '_PENDING_OPENINVOICE', 'Det ser ud som om du har en faktura i systemet der ikke er betalt - Hvis der er sket en fejl i processen, kan du gå til kassen og pr&oslash;ve igen:');
define( '_GOTO_CHECKOUT', 'G&aring; til kassen igen');
define( '_GOTO_CHECKOUT_CANCEL', 'du kan annullere betalingen (du får mulighed for at komme til abonnementssiden igen):');
define( '_PENDING_NOINVOICE', 'Det ser ud som om du har annulleret den eneste faktura, der er tilbage p&aring; din konto. Brug venligst knappen nedenfor til at g&aring; til abonnementsoversigten igen:');
define( '_PENDING_NOINVOICE_BUTTON', 'Abonnementsoversigt');
define( '_PENDING_REASON_ECHECK', '(Eftersom du betaler med echeck (eller lignende), m&aring; du desværre vente til betalingen er registreret - hvilket normalt tager 1-4 dage.');
define( '_PENDING_REASON_WAITING_RESPONSE', '(Iflg. vores informationer venter vi p&aring; godkendelse af betalingen. Du vil f&aring; besked n&aring;r dette sker. Vi beklager forsinkelsen)');
define( '_PENDING_REASON_TRANSFER', '(Iflg. vores informationer har du valgt at betale offline. Dette betyder desv&aelig;rre en lille forsinkelse til din betaling er registreret. Dette kan tage et par bankdage.)');

// --== HOLD PAGE ==--
define( '_HOLD_TITLE', 'Din konto er sat i bero');
define( '_HOLD_EXPLANATION', 'Din konto er sat i bero. Det er formenligt fordi der har v&aelig;ret et problem med betalingen. Hvis du ikke har modtaget en mail inden for de n&aelig;ste 24 timer, så kontakt os venligst.');

// --== THANK YOU PAGE ==--
define( '_THANKYOU_TITLE', 'Tak!');
define( '_SUB_FEPARTICLE_HEAD', 'Dit abonnement er aktiveret!');
define( '_SUB_FEPARTICLE_HEAD_RENEW', 'Fornyelsen er aktiveret!');
define( '_SUB_FEPARTICLE_LOGIN', 'Du kan nu logge ind.');
define( '_SUB_FEPARTICLE_THANKS', 'Tak for din registrering. ');
define( '_SUB_FEPARTICLE_THANKSRENEW', 'Tak for at du ville fornye din registrering. ');
define( '_SUB_FEPARTICLE_PROCESS', 'Vores system vil nu bearbejde din forsp&oslash;rgsel. ');
define( '_SUB_FEPARTICLE_PROCESSPAY', 'Vores system vil nu afvente din betaling. ');
define( '_SUB_FEPARTICLE_ACTMAIL', 'Du vil modtage en e-mail indeholdende et aktiverings link, så snart vores system har bearbejdet din forsp&oslash;rgsel. ');
define( '_SUB_FEPARTICLE_MAIL', 'Du vil modtage en e-mail, så snart vores system har bearbejdet din forsp&oslash;rgsel. ');

// --== CHECKOUT ERROR PAGE ==--
define( '_CHECKOUT_ERROR_TITLE', 'Fejl under betalingen!');
define( '_CHECKOUT_ERROR_EXPLANATION', 'Der er sket en fejl under din betaling');
define( '_CHECKOUT_ERROR_OPENINVOICE', 'Dette efterlader din faktura ubetalt. For at betale igen, skal du g&aring; til kassen igen:');
define( '_CHECKOUT_ERROR_FURTHEREXPLANATION', 'Dette efterlader din faktura ubetalt, men du kan prøve at gennemføre igen nedenfor. Hvis du fortsat oplever problemer eller har brug for hjælp til at gennemføre købet, er du meget velkommen til at kontakte os.');

// --== COUPON INFO ==--
define( '_COUPON_INFO', 'Rabatkode:');
define( '_COUPON_INFO_CONFIRM', 'Hvis du vil bruge en eller flere rabatkoder ved denne betaling, kan du g&oslash;re det undervejs i købet.');
define( '_COUPON_INFO_CHECKOUT', 'Indtast venligst din rabatkode her og klik p&aring; knappen for at aktivere den.');

// --== COUPON ERROR MESSAGES ==--
define( '_COUPON_WARNING_AMOUNT', 'En rabatkode, du har indtastet, p&aring;virker ikke denne betaling. S&aring; selvom om det ser ud som om den ingen effekt har, vil den p&aring;virke en fremtidig betaling.');
define( '_COUPON_ERROR_PRETEXT', 'Vi beklager:');
define( '_COUPON_ERROR_EXPIRED', 'Denne rabatkode er desv&aelig;rre udl&oslash;bet.');
define( '_COUPON_ERROR_NOTSTARTED', 'Du kan ikke bruge denne rabatkode her endnu.');
define( '_COUPON_ERROR_NOTFOUND', 'Rabatkoden blev ikke fundet.');
define( '_COUPON_ERROR_MAX_REUSE', 'Rabatkoden er blevet brugt det maximale antal gange.');
define( '_COUPON_ERROR_PERMISSION', 'Du har ikke tilladelse til at bruge denne rabatkode.');
define( '_COUPON_ERROR_WRONG_USAGE', 'Du kan ikke bruge den rabatkode til dette.');
define( '_COUPON_ERROR_WRONG_PLAN', 'Du har ikke det korrekte abonnement til at bruge denne kode.');
define( '_COUPON_ERROR_WRONG_PLAN_PREVIOUS', 'For at bruge denne rabatkode skal dit sidste abonnement v&aelig;re anderledes.');
define( '_COUPON_ERROR_WRONG_PLANS_OVERALL', 'Du har ikke det korrekte abonnement i din brugerhistorie til at bruge denne rabatkode.');
define( '_COUPON_ERROR_TRIAL_ONLY', 'Du kan kun bruge denne rabatkode i pr&oslash;veperioden.');
define( '_COUPON_ERROR_COMBINATION', 'Du kan ikke bruge denne rabatkode sammen med andre rabatkoder.');
define( '_COUPON_ERROR_SPONSORSHIP_ENDED', 'Kampagne for denne rabatkode er stoppet eller deaktiveret.');

// ----======== EMAIL TEXT ========----
define( '_AEC_SEND_SUB',				"Konto detaljer for %s p&aring; %s" );
define( '_AEC_USEND_MSG',				"Hej %s,\n\nTak for at du har registret dig hos %s.\n\nDu kan nu logge ind med dit brugernavn samt kodeord." );
define( '_AEC_USEND_MSG_ACTIVATE',				"Hej %s,\n\nTak for at du har registreret dig hos %s. Din konto er oprettet og skal aktiveres f&oslash;r du kan logge ind p&aring; siden.\nFor at aktivere din konto skal du klikke p&aring; dette link, eller kopiere det ind i din browser:\n%s\n\nEfter aktiveringen kan du logge ind på %s med dit brugernavn samt kodeord:\n\nBrugernavn - %s\nKodeord - %s" );
define( '_ACCTEXP_SEND_MSG',"Abonnement for %s p&aring; %s");
define( '_ACCTEXP_SEND_MSG_RENEW',"Abonnementsfornyelse for %s p&aring; %s");
define( '_ACCTEXP_MAILPARTICLE_GREETING', "Hej %s, \n\n");
define( '_ACCTEXP_MAILPARTICLE_THANKSREG', "Tak fordi du har oprettet dig som bruger hos %s.");
define( '_ACCTEXP_MAILPARTICLE_THANKSREN', "Tak fordi du har fornyet dit abonnement hos %s.");
define( '_ACCTEXP_MAILPARTICLE_PAYREC', "Betalingen for dit abonnement er modtaget.");
define( '_ACCTEXP_MAILPARTICLE_LOGIN', "Du kan nu logge ind på %s med dit brugernavn samt kodeord.");
define( '_ACCTEXP_MAILPARTICLE_FOOTER',"\n\nV&aelig;r venlig ikke at besvare denne besked. Den er automatisk genereret og blot til information.");
define( '_ACCTEXP_ASEND_MSG',				"Hej %s,\n\na en ny bruger har oprettet et abonnement p&aring; [ %s ].\n\nHer er yderligere detaljer:\n\nNavn.........: %s\nEmail........: %s\nBrugernavn.....: %s\nSubscr.-ID...: %s\nAbonnement.: %s\nIP...........: %s\nISP..........: %s\n\nV&aelig;r venlig ikke at svare p&aring; denne mail - den er automatisk genereret." );
define( '_ACCTEXP_ASEND_MSG_RENEW',			"Hej %s,\n\na en bruger har fornyet sit abonnement p&aring; [ %s ].\n\nHer er yderligere detaljer:\n\nNavn.........: %s\nEmail........: %s\nBrugernavn.....: %s\nSubscr.-ID...: %s\nAbonnement.: %s\nIP...........: %s\nISP..........: %s\n\nV&aelig;r venlig ikke at svare p&aring; denne mail - den er automatisk genereret." );
define( '_AEC_ASEND_MSG_NEW_REG',			"Hej %s,\n\nDer er en ny bruger, der har registreret sig p&aring; [ %s ].\n\nYderligere detaljer:\n\nNavn.....: %s\nEmail.: %s\nBrugernavn....: %s\nIP.......: %s\nISP......: %s\n\nV&aelig;r venlig ikke at svare p&aring; denne mail - den er automatisk genereret." );
define( '_AEC_ASEND_NOTICE',				"AEC %s: %s p&aring; %s" );
define( '_AEC_ASEND_NOTICE_MSG',		"Dette er en automatisk besked fra EventLog, baseret på det email rapporterings-niveau, du har valgt.\n\nDetaljerne for beskeder er:\n\n--- --- --- ---\n\n%s\n\n--- --- --- ---\n\nV&aelig;r venlig ikke at svare p&aring; denne mail, da den er automatisk genereret. Du kan &aelig;ndre niveauet for rapporter i dine AEC indstillinger." );

// ----======== COUNTRY CODES ========----

define( 'COUNTRYCODE_SELECT', 'Select Country' );

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
