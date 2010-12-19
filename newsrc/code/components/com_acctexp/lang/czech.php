<?php
/**
 * @version $Id: english.php
 * @package AEC - Account Control Expiration - Membership Manager
 * @subpackage Language - Frontend - English
 * @copyright 2006-2010 Copyright (C) David Deutsch
 * @author David Deutsch <skore@skore.de> & Team AEC - http://www.valanx.org
 * @license GNU/GPL v.2 http://www.gnu.org/licenses/old-licenses/gpl-2.0.html or, at your option, any later version
 */

// Dont allow direct linking
( defined('_JEXEC') || defined( '_VALID_MOS' ) ) or die( 'P&#345;&iacute;m&yacute; p&#345;&iacute;stup k tomuto um&iacute;stn&#283;n&iacute; nen&iacute; povolen.' );

if( defined( '_AEC_LANG' ) ) {
	return;
}

define( '_AEC_EXPIRE_TODAY',				'P&#345;edplatn&eacute; je platn&eacute; do dne&#353;n&iacute;ho dne' );
define( '_AEC_EXPIRE_FUTURE',				'P&#345;edplatn&eacute; je platn&eacute; do' );
define( '_AEC_EXPIRE_PAST',					'P&#345;edplatn&eacute; bylo platn&eacute; do' );
define( '_AEC_DAYS_ELAPSED',				'dn&iacute; uplynulo');
define( '_AEC_EXPIRE_TRIAL_TODAY',			'Zku&scaron;ebn&iacute; p&#345;edplatn&eacute; je aktivn&iacute; do dne&scaron;n&iacute;ho dne' );
define( '_AEC_EXPIRE_TRIAL_FUTURE',			'Zku&#353;ebn&iacute; p&#345;edplatn&eacute; je platn&eacute; do' );
define( '_AEC_EXPIRE_TRIAL_PAST',			'Zku&#353;ebn&iacute; p&#345;edplatn&eacute; bylo platn&eacute; do' );

define( '_AEC_EXPIRE_NOT_SET',				'Nen&iacute; Nastaveno' );
define( '_AEC_GEN_ERROR',					'<h1>Obecn&aacute; chyba</h1><p>Nastal probl&eacute;m p&#345;i zpracov&aacute;n&iacute; va&#353;eho požadavku. Kontaktujte pros&iacute;m administr&aacute;tora.</p>' );

// payments
define( '_AEC_PAYM_METHOD_FREE',			'Zdarma' );
define( '_AEC_PAYM_METHOD_NONE',			'Ž&aacute;dn&eacute;' );
define( '_AEC_PAYM_METHOD_TRANSFER',		'P&#345;evodem' );

// processor errors
define( '_AEC_MSG_PROC_INVOICE_FAILED_SH',			'Platba selhala' );
define( '_AEC_MSG_PROC_INVOICE_FAILED_EV',			'Zp&#367;sob platby %s na %s selhal - &#269;&aacute;slo objedn&aacute;vky neexistuje:' );
define( '_AEC_MSG_PROC_INVOICE_ACTION_SH',			'&#268;innost Platby' );
define( '_AEC_MSG_PROC_INVOICE_ACTION_EV',			'Informace o platb&#283;:' );
define( '_AEC_MSG_PROC_INVOICE_ACTION_EV_STATUS',	'Stav platby:' );
define( '_AEC_MSG_PROC_INVOICE_ACTION_EV_FRAUD',	'Kontrola &#269;&aacute;stky selhala,k zaplacen&iacute;: %s, objedn&aacute;vka: %s - platba zru&scaron;ena' );
define( '_AEC_MSG_PROC_INVOICE_ACTION_EV_CURR',		'Kontrola m&#283;ny selhala,k zaplacen&iacute; %s, objedn&aacute;vka: %s - platba p&#345;eru&scaron;ena' );
define( '_AEC_MSG_PROC_INVOICE_ACTION_EV_VALID',	'Platba byla potvrzena a provedena' );
define( '_AEC_MSG_PROC_INVOICE_ACTION_EV_VALID_APPFAIL',	'Platba byla potvrzena, ale nebyla provedena!' );
define( '_AEC_MSG_PROC_INVOICE_ACTION_EV_TRIAL',	'Platba potvrzena - zku&scaron;ebn&iacute; p&#345;edplatn&eacute;' );
define( '_AEC_MSG_PROC_INVOICE_ACTION_EV_PEND',		'Platba nebyla potvrzena - &#269;ek&aacute; se na proveden&iacute; platby, d&#367;vod: %s' );
define( '_AEC_MSG_PROC_INVOICE_ACTION_EV_CANCEL',	'Platba nebyla provedena - P&#345;edplatn&eacute; zru&scaron;eno' );
define( '_AEC_MSG_PROC_INVOICE_ACTION_EV_CHARGEBACK',	'Platba nebyla potvrzena - Platba vr&aacute;cena' );
define( '_AEC_MSG_PROC_INVOICE_ACTION_EV_CHARGEBACK_SETTLE',	'Platba nebyla provedena' );
define( '_AEC_MSG_PROC_INVOICE_ACTION_EV_USTATUS',	', Stav uživatele byl aktualizov&aacute;n na \'Zru&scaron;eno\'' );
define( '_AEC_MSG_PROC_INVOICE_ACTION_EV_USTATUS_HOLD',	', Stav uživatele byl aktualizov&aacute;n na \'&#268;ek&aacute;\'' );
define( '_AEC_MSG_PROC_INVOICE_ACTION_EV_USTATUS_ACTIVE',	', Stav uživatele byl aktualizov&aacute;n na \'Aktivn&iacute;\'' );
define( '_AEC_MSG_PROC_INVOICE_ACTION_EV_EOT',		'Platba nbyla provedena - P&#345;edplatn&eacute; skon&#269;ilo' );
define( '_AEC_MSG_PROC_INVOICE_ACTION_EV_DUPLICATE','Platba nebyla provedena - Duplikace platby' );
define( '_AEC_MSG_PROC_INVOICE_ACTION_EV_NULL','Platba nebyla provedena - Nulov&aacute; &#269;&aacute;stka' );
define( '_AEC_MSG_PROC_INVOICE_ACTION_EV_U_ERROR',	'Nezn&aacute;m&aacute; chyba' );
define( '_AEC_MSG_PROC_INVOICE_ACTION_EV_REFUND',	'Platba nebyla provedena - P&#345;edplatn&eacute; smaz&aacute;no (vr&aacute;ceno)' );
define( '_AEC_MSG_PROC_INVOICE_ACTION_EV_EXPIRED',	', &#268;as uživatele expiroval' );

// --== PAYMENT PLANS PAGE ==--
define( '_PAYPLANS_HEADER', 'Typy P&#345;edplatn&eacute;ho');
define( '_NOPLANS_ERROR', 'Ž&aacute;dn&eacute; typy p&#345;edplatn&eacute;ho nejsou nastaveny. Kontaktujte administr&aacute;tora.');
define( '_NOPLANS_AUTHERROR', 'Nejste opr&aacute;vn&#283;ni k p&#345;&iacute;stupu k t&eacute;to možnosti. Kontaktujte administr&aacute;tora.');
define( '_PLANGROUP_BACK', '&lt; Zp&#283;t');

// --== ACCOUNT DETAILS PAGE ==--
define( '_CHK_USERNAME_AVAIL', "Uživatelsk&eacute; jm&eacute;no %s je dostupn&eacute;");
define( '_CHK_USERNAME_NOTAVAIL', "Uživatelsk&eacute; jm&eacute;no %s je již použit&eacute;!");

// --== MY SUBSCRIPTION PAGE ==--
define( '_MYSUBSCRIPTION_TITLE', 'Moje P&#345;edplatn&eacute;');
define( '_MEMBER_SINCE', '&#268;len od');
define( '_HISTORY_COL1_TITLE', 'Faktura');
define( '_HISTORY_COL2_TITLE', '&#268;&aacute;stka');
define( '_HISTORY_COL3_TITLE', 'Datum Platby');
define( '_HISTORY_COL4_TITLE', 'Zp&#367;sob Platby');
define( '_HISTORY_COL5_TITLE', 'Akce');
define( '_HISTORY_COL6_TITLE', 'Pl&aacute;n');
define( '_HISTORY_ACTION_REPEAT', 'zaplatit');
define( '_HISTORY_ACTION_CANCEL', 'zru&scaron;it');
define( '_RENEW_LIFETIME', 'M&aacute;te doživotn&iacute; p&#345;edplatn&eacute;.');
define( '_RENEW_DAYSLEFT_EXCLUDED', 'M&aacute;te vyj&iacute;mku z &#269;asov&eacute; expirace p&#345;edplatn&eacute;ho');
define( '_RENEW_DAYSLEFT_INFINITE', '&#8734');
define( '_RENEW_DAYSLEFT', 'Dn&iacute; zb&yacute;v&aacute;');
define( '_RENEW_DAYSLEFT_TRIAL', 'Dn&iacute; zb&yacute;v&aacute; do konce zku&scaron;ebn&iacute;ho p&#345;edplatn&eacute;ho');
define( '_RENEW_INFO', 'Použ&iacute;v&aacute;te opakovanou platbu.');
define( '_RENEW_OFFLINE', 'Obnovit');
define( '_RENEW_BUTTON_UPGRADE', 'Upgradovat/Obnovit');
define( '_PAYMENT_PENDING_REASON_ECHECK', 'echeck neproplacen (1-4 pracovn&iacute; dny)');
define( '_PAYMENT_PENDING_REASON_TRANSFER', '&#269;ek&aacute;n&iacute; na p&#345;evod platby');
define( '_YOUR_SUBSCRIPTION', 'Va&scaron;e P&#345;edplatn&eacute;');
define( '_YOUR_FURTHER_SUBSCRIPTIONS', 'Dal&scaron;&iacute; P&#345;edplatn&eacute;');
define( '_PLAN_PROCESSOR_ACTIONS', 'k tomuto m&aacute;te n&aacute;sleduj&iacute;c&iacute; možnosti:');
define( '_AEC_SUBDETAILS_TAB_OVERVIEW', 'P&#345;ehled');
define( '_AEC_SUBDETAILS_TAB_INVOICES', 'Faktury');
define( '_AEC_SUBDETAILS_TAB_DETAILS', 'Detaily');

define( '_HISTORY_ACTION_PRINT', 'tisk');
define( '_INVOICEPRINT_DATE', 'Datum');
define( '_INVOICEPRINT_ID', 'ID');
define( '_INVOICEPRINT_REFERENCE_NUMBER', 'Referen&#269;n&iacute; &#268;&iacute;slo');
define( '_INVOICEPRINT_ITEM_NAME', 'N&aacute;zev Položky');
define( '_INVOICEPRINT_UNIT_PRICE', 'Cena za Jednotku');
define( '_INVOICEPRINT_QUANTITY', 'Množstv&iacute;');
define( '_INVOICEPRINT_TOTAL', 'Celkem');
define( '_INVOICEPRINT_GRAND_TOTAL', 'Total');

define( '_INVOICEPRINT_ADDRESSFIELD', 'Zde napi&scaron;te Va&scaron;i adresu - bude zobrazena p&#345;i vytisknut&iacute;.');
define( '_INVOICEPRINT_PRINT', 'Tisk');
define( '_INVOICEPRINT_BLOCKNOTICE', 'tento &uacute;sek (v&#269;etn&#283; textov&eacute;ho pole a tla&#269;&iacute;tka tisk) nebude vyti&scaron;t&#283;n.');
define( '_INVOICEPRINT_PRINT_TYPEABOVE', 'Pros&iacute;m vložte Va&scaron;i adresu do v&yacute;&scaron;e uveden&eacute;ho pole.');
define( '_INVOICEPRINT_PAIDSTATUS_UNPAID', '<strong>tato platba je&scaron;te nebyla zaplacena.</strong>');
define( '_INVOICEPRINT_PAIDSTATUS_CANCEL', '<strong>This payment was canceled.</strong>');
define( '_INVOICEPRINT_PAIDSTATUS_PAID', 'Platba byla zaplacena: %s');
define( '_INVOICEPRINT_RECURRINGSTATUS_ONCE', 'This invoice is billed on a recurring basis. The invoice amount listing may represent that of the next billing cycle, not of the one that has been paid for last. The list of payment dates above clarifies which amount has been paid and when.');

define( '_AEC_YOUSURE', 'Jste si jist&yacute;?');

define( '_AEC_WILLEXPIRE', 'Toto p&#345;edplatn&eacute; vypr&scaron;&iacute;');
define( '_AEC_WILLRENEW', 'Toto p&#345;edplatn&eacute; bude obnoveno');
define( '_AEC_ISLIFETIME', 'Doživotn&iacute; P&#345;edplatn&eacute;');

// --== EXPIRATION PAGE ==--
define( '_EXPIRE_INFO', 'V&aacute;&scaron; &uacute;&#269;et je aktivn&iacute; do');
define( '_RENEW_BUTTON', 'Obnovit Nyn&iacute;');
define( '_RENEW_BUTTON_CONTINUE', 'Prodloužit P&#345;edchoz&iacute; P&#345;edplatn&eacute;');
define( '_ACCT_DATE_FORMAT', '%d-%m-%Y');
define( '_EXPIRED', 'Va&scaron;e p&#345;edplatn&eacute; vypr&scaron;elo: ');
define( '_EXPIRED_TRIAL', 'Va&scaron;e zku&scaron;ebn&iacute; p&#345;edplatn&eacute; vypr&scaron;elo: ');
define( '_ERRTIMESTAMP', 'Nelze konvertovat timestamp.');
define( '_EXPIRED_TITLE', 'P&#345;edplatn&eacute; Vypr&scaron;elo!');
define( '_DEAR', 'V&aacute;žen&yacute; %s');

// --== CONFIRMATION FORM ==--
define( '_CONFIRM_TITLE', 'Potvrzen&iacute;');
define( '_CONFIRM_COL1_TITLE', '&Uacute;&#269;et');
define( '_CONFIRM_COL2_TITLE', 'Podrobnosti');
define( '_CONFIRM_COL3_TITLE', '&#268;&aacute;stka');
define( '_CONFIRM_ROW_NAME', 'Jm&eacute;no: ');
define( '_CONFIRM_ROW_USERNAME', 'Uživatelsk&eacute; Jm&eacute;no: ');
define( '_CONFIRM_ROW_EMAIL', 'E-mail:');
define( '_CONFIRM_INFO', 'Pros&iacute;m použijte tla&#269;&iacute;tko "DAL&Scaron;&Iacute;"" pro kompletaci Va&scaron;i registrace.');
define( '_BUTTON_CONFIRM', 'Dal&scaron;&iacute;');
define( '_CONFIRM_TOS', "Potvrzuji, že jsem &#269;etl, rozum&iacute;m a souhlas&iacute;m s <a href=\"%s\" target=\"_blank\" title=\"ToS\">Podm&iacute;nkami Služby</a>.");
define( '_CONFIRM_TOS_IFRAME', "&#268;etl jsem a souhlas&iacute;m s Podm&iacute;nkami Služby (v&yacute;&scaron;e)");
define( '_CONFIRM_TOS_ERROR', 'Pros&iacute;m p&#345;e&#269;t&#283;te si Podm&iacute;nky Služby.');
define( '_CONFIRM_COUPON_INFO', 'Pokud m&aacute;te slevov&yacute; kup&oacute;n, m&#367;žete ho použ&iacute;t na str&aacute;nce "Platba"');
define( '_CONFIRM_COUPON_INFO_BOTH', 'Pokud m&aacute;te slevov&yacute; kup&oacute;n, m&#367;žete ho použ&iacute;t na str&aacute;nce "Platba"');
define( '_CONFIRM_FREETRIAL', 'Zku&scaron;ebn&iacute; P&#345;edplatn&eacute; Zdarma');
define( '_CONFIRM_YOU_HAVE_SELECTED', 'Vybrali jste');

define( '_CONFIRM_DIFFERENT_USER_DETAILS', 'Chcete zm&#283;nit podrobnosti uživatele?');
define( '_CONFIRM_DIFFERENT_ITEM', 'Chcete zvolit jinou položku?');

// --== SHOPPING CART FORM ==--
define( '_CART_TITLE', 'N&aacute;kupn&iacute; Ko&scaron;&iacute;k');
define( '_CART_ROW_TOTAL', 'Celkem');
define( '_CART_INFO', 'Pros&iacute;m použijte tla&#269;&iacute;tko "POKRA&#268;OVAT" k dokon&#269;en&iacute; n&aacute;kupu.');
define( '_CART_CLEAR_ALL', 'vymazat n&aacute;kupn&iacute; ko&scaron;&iacute;k');
define( '_CART_DELETE_ITEM', 'odstranit');

// --== EXCEPTION FORM ==--
define( '_EXCEPTION_TITLE', 'Jsou Požadov&aacute;ny Dopl&#328;uj&iacute;c&iacute; Informace');
define( '_EXCEPTION_TITLE_NOFORM', 'Upozorn&#283;n&iacute;:');
define( '_EXCEPTION_INFO', 'K dokon&#269;en&iacute; platby n&aacute;m pros&iacute;m poskytn&#283;te n&iacute;že uveden&eacute; dopl&#328;uj&iacute;c&iacute; informace:');

// --== PROMPT PASSWORD FORM ==--
define( '_AEC_PROMPT_PASSWORD', 'Z bezpe&#269;nostn&iacute;ch d&#367;vod&#367; je t&#345;eba pro pokra&#269;ov&aacute;n&iacute; vložit heslo.');
define( '_AEC_PROMPT_PASSWORD_WRONG', 'Vložen&eacute; heslo se neshoduje s heslem v na&scaron;i datab&aacute;zi registrac&iacute;. Zkuste znovu');
define( '_AEC_PROMPT_PASSWORD_BUTTON', 'Pokra&#269;ovat');

// --== CHECKOUT FORM ==--
define( '_CHECKOUT_TITLE', 'Platba');
define( '_CHECKOUT_INFO', 'Va&scaron;e objedn&aacute;vka byla uložena. Na t&eacute;to str&aacute;nce nyn&iacute; m&#367;žete dokon&#269;it platbu %s. <br /><br /> Pokud se platba nezda&#345;&iacute;, m&#367;žete se vr&aacute;tit bez probl&eacute;m&#367; zp&#283;t k tomuto kroku t&iacute;m, že se p&#345;ihl&aacute;s&iacute;te na n&aacute;&scaron; website s Va&scaron;&iacute;m Uživatelsk&yacute;m jm&eacute;nem a heslem. Na z&aacute;ložce "platby" pak m&#367;žete prov&eacute;st platbu znovu nebo ji zru&scaron;it.');
define( '_CHECKOUT_INFO_REPEAT', 'D&#283;kujeme za V&aacute;&scaron; n&aacute;vrat. Na t&eacute;to str&aacute;nce m&#367;žete dokon&#269;it Va&scaron;i platbu %s. <br /><br /> Pokud se platba nezda&#345;&iacute;, m&#367;žete se vr&aacute;tit bez probl&eacute;m&#367; zp&#283;t k tomuto kroku t&iacute;m, že se p&#345;ihl&aacute;s&iacute;te na n&aacute;&scaron; website s Va&scaron;&iacute;m Uživatelsk&yacute;m jm&eacute;nem a heslem. Na z&aacute;ložce "platby" pak m&#367;žete prov&eacute;st platbu znovu nebo ji zru&scaron;it.');
define( '_BUTTON_CHECKOUT', 'Zaplatit');
define( '_BUTTON_APPEND', 'P&#345;ipojit');
define( '_BUTTON_APPLY', 'Použ&iacute;t');
define( '_BUTTON_EDIT', 'Upravit');
define( '_BUTTON_SELECT', 'Vybrat');
define( '_CHECKOUT_COUPON_CODE', 'Slevov&yacute; Kup&oacute;n');
define( '_CHECKOUT_INVOICE_AMOUNT', 'V&yacute;&scaron;e Platby');
define( '_CHECKOUT_INVOICE_COUPON', 'Kup&oacute;n');
define( '_CHECKOUT_INVOICE_COUPON_REMOVE', 'odstranit');
define( '_CHECKOUT_INVOICE_TOTAL_AMOUNT', 'Celkov&#283;');
define( '_CHECKOUT_COUPON_INFO', 'Pokud m&aacute;te slevov&yacute; kup&Oacute;n, m&#367;žete ho vložit zde pro z&iacute;sk&aacute;n&iacute; slevy');
define( '_CHECKOUT_GIFT_HEAD', 'D&aacute;rek jin&eacute;mu uživateli');
define( '_CHECKOUT_GIFT_INFO', 'Vložte podrobnosti o jin&eacute;m uživateli tohoto website a v&#283;nujete mu položku, kterou nyn&iacute; koup&iacute;te.');

define( '_AEC_TERMTYPE_TRIAL', 'V&yacute;choz&iacute; Platba');
define( '_AEC_TERMTYPE_TERM', '&Uacute;daje k Platb&#283;');
define( '_AEC_CHECKOUT_TERM', '&Uacute;daje k Platb&#283;');
define( '_AEC_CHECKOUT_NOTAPPLICABLE', 'nelze aplikovat');
define( '_AEC_CHECKOUT_FUTURETERM', 'budouc&iacute; obdob&iacute;');
define( '_AEC_CHECKOUT_COST', 'Cena');
define( '_AEC_CHECKOUT_TAX', 'Da&#328;');
define( '_AEC_CHECKOUT_DISCOUNT', 'Sleva');
define( '_AEC_CHECKOUT_TOTAL', 'Celkem');
define( '_AEC_CHECKOUT_GRAND_TOTAL', 'Total');
define( '_AEC_CHECKOUT_DURATION', 'Obdob&iacute;');

define( '_AEC_CHECKOUT_DUR_LIFETIME', 'Navždy');

define( '_AEC_CHECKOUT_DUR_DAY', 'Den');
define( '_AEC_CHECKOUT_DUR_DAYS', 'Dn&iacute;');
define( '_AEC_CHECKOUT_DUR_WEEK', 'T&yacute;den');
define( '_AEC_CHECKOUT_DUR_WEEKS', 'T&yacute;dny');
define( '_AEC_CHECKOUT_DUR_MONTH', 'M&#283;s&iacute;c');
define( '_AEC_CHECKOUT_DUR_MONTHS', 'M&#283;s&iacute;ce');
define( '_AEC_CHECKOUT_DUR_YEAR', 'Rok');
define( '_AEC_CHECKOUT_DUR_YEARS', 'Roky');

// --== ALLOPASS SPECIFIC ==--
define( '_REGTITLE','INSCRIPTION');
define( '_ERRORCODE','Erreur de code Allopass');
define( '_FTEXTA','Le code que vous avez utilis n\'est pas valide! Pour obtenir un code valable, composez le numero de tlphone, indiqu dans une fenetre pop-up, aprs avoir clicker sur le drapeau de votre pays. Votre browser doit accepter les cookies d\'usage.<br><br>Si vous tes certain, que vous avez le bon code, attendez quelques secondes et ressayez encore une fois!<br><br>Sinon prenez note de la date et heure de cet avertissement d\'erreur et informez le Webmaster de ce problme en indiquant le code utilis.');
define( '_RECODE','Saisir de nouveau le code Allopass');

// --== REGISTRATION STEPS ==--
define( '_STEP_DATA', 'Va&scaron;e Data');
define( '_STEP_CONFIRM', 'Potvrdit');
define( '_STEP_PLAN', 'Vyberte P&#345;edplatn&eacute;');
define( '_STEP_EXPIRED', 'Vypr&scaron;elo!');

// --== NOT ALLOWED PAGE ==--
define( '_NOT_ALLOWED_HEADLINE', 'Požadovan&aacute; Registrace!');
define( '_NOT_ALLOWED_FIRSTPAR', 'Obsah, kter&yacute; chcete zobrazit je dostupn&yacute; pouze registrovan&yacute;m uživatel&#367;m. Pokud již jste registrov&aacute;ni mus&iacute;te se p&#345;ihl&aacute;sit. Pokud se chcete registrovat, klikn&#283;te na tento odkaz: ');
define( '_NOT_ALLOWED_REGISTERLINK', 'Stránka Registrace');
define( '_NOT_ALLOWED_FIRSTPAR_LOGGED', 'Obsah str&aacute;nky, kterou se snaž&iacute;te otev&#345;&iacute;t je dostupn&yacute; pouze registrovan&yacute;m uživatel&#367;m s p&#345;edplatn&yacute;m.Pros&iacute;m klikn&#283;te na n&aacute;sleduj&iacute;c&iacute; odkaz pokud si chcete objednat p&#345;edplatn&eacute;: ');
define( '_NOT_ALLOWED_REGISTERLINK_LOGGED', 'Str&aacute;nky Registrace');
define( '_NOT_ALLOWED_SECONDPAR', 'Platba V&aacute;m zabere jen okamžik a m&#367;žete využ&iacute;t tyto možnosti:');

// --== CANCELLED PAGE ==--
define( '_CANCEL_TITLE', 'P&#345;edplatn&eacute; Zru&scaron;eno!');
define( '_CANCEL_MSG', 'N&aacute;&scaron; syst&eacute;m n&aacute;s informoval, že chcete zru&scaron;it Va&scaron;i platbu. Pokud nastal n&#283;j&aacute;k&yacute; probl&eacute;m s na&scaron;im syst&eacute;mem pros&iacute;m kontaktujte n&aacute;s!');

// --== PENDING PAGE ==--
define( '_PENDING_TITLE', 'Nevy&#345;&iacute;zen&eacute; Platby');
define( '_WARN_PENDING', 'Va&scaron;e p&#345;edplatn&eacute; &#269;ek&aacute; na vy&#345;&iacute;zen&iacute; nebo bylo p&#345;echodn&#283; suspendov&aacute;no. Pokud se tento stav b&#283;hem n&#283;kolika hodin nezm&#283;n&iacute; a platba byla potvrzena, kontaktujte pros&iacute;m administr&aacute;tora.');
define( '_PENDING_OPENINVOICE', 'Na&scaron;e datab&aacute;ze indikuje , že z&#345;ejm&#283; m&aacute;te nevy&uacute;&#269;tovanou platbu - pokud do&scaron;lo k n&#283;j&aacute;k&eacute;mu probl&eacute;mu, jd&#283;te na str&aacute;nku Plateb a zkuste znovu:');
define( '_GOTO_CHECKOUT', 'J&iacute;t znovu na str&aacute;nku Plateb');
define( '_GOTO_CHECKOUT_CANCEL', 'tak&eacute; m&#367;žete platbu zru&scaron;it (m&aacute;te možnost j&iacute;t znovu na str&aacute;nku v&yacute;b&#283;ru pl&aacute;nu):');
define( '_PENDING_NOINVOICE', 'Z&#345;ejm&#283; jste zru&scaron;ili jedinou platbu, kter&aacute; zbyla na Va&scaron;em &uacute;&#269;tu. Použijte n&iacute;že zobrazen&eacute; tla&#269;&iacute;tko pro op&#283;tovn&yacute; p&#345;echod na str&aacute;nku v&yacute;b&#283;ru pl&aacute;n&#367;:');
define( '_PENDING_NOINVOICE_BUTTON', 'V&yacute;b&#283;r Pl&aacute;nu');
define( '_PENDING_REASON_ECHECK', '(Z&#345;ejm&#283; jste zvolili platbu echeck (nebo podobnou), nyn&iacute; mus&iacute;te &#269;ekat dokud platba nebude potvrzena - obvykle 1-4 dny)');
define( '_PENDING_REASON_WAITING_RESPONSE', '(Z&#345;ejm&#283; &#269;ek&aacute;te na odpov&#283;ď zvolen&eacute; platebn&iacute; br&aacute;ny. Jakmila nastane odezva budeme V&aacute;s informovat. Omlouv&aacute;me se za zdržen&iacute;.)');
define( '_PENDING_REASON_TRANSFER', '(Z&#345;ejm&#283; jste se rozhodli platit neelektronick&yacute;m zp&#367;sobem takže mus&iacute;te &#269;ekat až bude platba provedena - to m&#367;že trvat n&#283;kolik dn&iacute;.)');

// --== HOLD PAGE ==--
define( '_HOLD_TITLE', '&Uacute;&#269;et &#268;ek&aacute;');
define( '_HOLD_EXPLANATION', 'V&aacute;&scaron; &uacute;&#269;et nyn&iacute; &#269;ek&aacute;. Z&#345;ejm&#283; do&scaron;lo k probl&eacute;mu s Va&#269;i platbou. Pokud do 24 hodin neobdrž&iacute;te email, kontaktujte administr&aacute;tora.');

// --== THANK YOU PAGE ==--
define( '_THANKYOU_TITLE', 'D&#283;kujeme!');
define( '_SUB_FEPARTICLE_HEAD', 'P&#345;edplatn&eacute; dokon&#269;eno!');
define( '_SUB_FEPARTICLE_HEAD_RENEW', 'Obnoven&iacute; P&#345;edplatn&eacute;ho Dokon&#269;eno!');
define( '_SUB_FEPARTICLE_LOGIN', 'Nyn&iacute; se m&#367;žete p&#345;ihl&aacute;sit.');
define( '_SUB_FEPARTICLE_THANKS', 'D&#283;kujeme za Va&scaron;i registraci. ');
define( '_SUB_FEPARTICLE_THANKSRENEW', 'D&#283;kujeme za obnoven&iacute; Va&scaron;eho p&#345;edplatn&eacute;ho. ');
define( '_SUB_FEPARTICLE_PROCESS', 'N&aacute;&scaron; syst&eacute;m nyn&iacute; zpracov&aacute;v&aacute; V&aacute;&scaron; požadavek. ');
define( '_SUB_FEPARTICLE_PROCESSPAY', 'N&aacute;&scaron; syst&eacute;m nyn&iacute; &#269;ek&aacute; na Va&scaron;i platbu. ');
define( '_SUB_FEPARTICLE_ACTMAIL', 'Po zpracov&aacute;n&iacute; Va&scaron;eho požadavku na&scaron;&iacute;m syst&eacute;mem obdrž&iacute;te email s aktiva&#269;n&iacute;m odkazem. ');
define( '_SUB_FEPARTICLE_MAIL', 'Po zpracov&aacute;n&iacute; Va&scaron;eho požadavku obdrž&iacute;te email. ');

// --== CHECKOUT ERROR PAGE ==--
define( '_CHECKOUT_ERROR_TITLE', 'Chyba b&#283;hem zpracov&aacute;n&iacute; platby!');
define( '_CHECKOUT_ERROR_EXPLANATION', 'Chyba b&#283;hem zpracov&aacute;n&iacute; platby');
define( '_CHECKOUT_ERROR_OPENINVOICE', 'Toto zanech&aacute; Va&scaron;i platbu nezaplacenou. Pro opakov&aacute;n&iacute; platby m&#367;žete j&iacute;t na str&aacute;nku Platby a zkusit znovu:');
define( '_CHECKOUT_ERROR_FURTHEREXPLANATION', 'Toto zp&#367;sob&iacute;, že Va&scaron;e platba nebude za&uacute;&#269;tov&aacute;na.M&#367;žete zkusit znovu. Pokud m&aacute;te n&#283;j&aacute;ke ot&aacute;zky kontaktujte n&aacute;s');

// --== COUPON INFO ==--
define( '_COUPON_INFO', 'Kup&oacute;ny:');
define( '_COUPON_INFO_CONFIRM', 'Pokud chcete pro tuto platbu použ&iacute;t slevov&eacute; kup&oacute;ny, m&#367;žete tak u&#269;init na str&aacute;nce "PLATBY".');
define( '_COUPON_INFO_CHECKOUT', 'Vložte pros&iacute;m slevov&yacute; k&oacute;d a klikn&#283;te na tla&#269;&iacute;tko pro aplikaci slevy na tuto platbu.');

// --== COUPON ERROR MESSAGES ==--
define( '_COUPON_WARNING_AMOUNT', 'Jeden z kup&oacute;n&#367; nebude m&iacute;t efekt na dal&scaron;&iacute; platbu, p&#345;estože se m&#367;že zd&aacute;t, že kup&oacute;n nem&aacute; vliv na platbu bude m&iacute;t vliv na doplatek.');
define( '_COUPON_ERROR_PRETEXT', 'Bohužel:');
define( '_COUPON_ERROR_EXPIRED', 'Tento kup&oacute;n již expiroval.');
define( '_COUPON_ERROR_NOTSTARTED', 'Použit&iacute; kup&oacute;nu nen&iacute; je&scaron;t&#283; povoleno.');
define( '_COUPON_ERROR_NOTFOUND', 'Tento kup&oacute;n nebyl nalezen.');
define( '_COUPON_ERROR_MAX_REUSE', 'Tento kup&oacute;n již dos&aacute;hl maxim&aacute;ln&iacute;ho po&#269;tu použit&iacute;.');
define( '_COUPON_ERROR_PERMISSION', 'Nem&aacute;te opr&aacute;vn&#283;n&iacute; použ&iacute;t tento kup&oacute;n.');
define( '_COUPON_ERROR_WRONG_USAGE', 'Pro tento &uacute;&#269;el nem&#367;že b&yacute;t kup&oacute;n použit.');
define( '_COUPON_ERROR_WRONG_PLAN', 'Nejste ve spr&aacute;vn&eacute;m typu p&#345;edplatn&eacute;ho pro tento kup&oacute;n.');
define( '_COUPON_ERROR_WRONG_PLAN_PREVIOUS', 'Pro použit&iacute; tohoto kup&oacute;nu mus&iacute;te m&iacute;t odli&scaron;n&yacute; sou&#269;asn&yacute; pl&aacute;n.');
define( '_COUPON_ERROR_WRONG_PLANS_OVERALL', 'Nem&aacute;te spr&aacute;vn&yacute; pl&aacute;n p&#345;edplatn&eacute;ho pro použit&iacute; tohoto kup&oacute;nu.');
define( '_COUPON_ERROR_TRIAL_ONLY', 'tento kup&oacute;n m&#367;žete použ&iacute;t jen pro zku&scaron;ebn&iacute; p&#345;edplatn&eacute;.');
define( '_COUPON_ERROR_COMBINATION', 'Tento kup&oacute;n nem&#367;že b&yacute;t použit sou&#269;asn&#283; s jin&yacute;mi kup&oacute;ny.');
define( '_COUPON_ERROR_SPONSORSHIP_ENDED', 'Sponzorstv&iacute; pro tento kup&oacute;n již skon&#269;ilo nebo nen&iacute; aktivn&iacute;.');

// ----======== EMAIL TEXT ========----
define( '_AEC_SEND_SUB',				"Podrobnosti &uacute;&#269;tu %s na %s" );
define( '_AEC_USEND_MSG',				"Dobr&yacute; den %s,\n\nD&#283;kujeme za registraci na %s.\n\nNyn&iacute; se m&#367;žete p&#345;ihl&aacute;sit na %s použit&iacute;m uživatelsk&eacute;ho jm&eacute;na a hesla s kte&yacute;mi jste se registrovali." );
define( '_AEC_USEND_MSG_ACTIVATE',				"Dobr&yacute; den %s,\n\nD&#283;kujeme za registraci na %s. V&aacute;&scaron; &uacute;&#269;et byl vytvo&#345;en a mus&iacute; b&yacute;t aktivov&aacute;n.\nPro aktivaci &uacute;&#269;tu klikn&#283;te na n&aacute;sleduj&iacute;c&iacute; odkaz nebo ho zkop&iacute;rujte a vložte do Va&scaron;eho prohl&iacute;že&#269;e:\n%s\n\nPo aktivaci se m&#367;žete p&#345;ihl&aacute;sit na %s použit&iacute;m n&aacute;sleduj&iacute;c&iacute;ho uživatelsk&eacute;ho jm&eacute;na a hesla:\n\nUživatelsk&eacute; jm&eacute;no - %s\nHeslo - %s" );
define( '_ACCTEXP_SEND_MSG',"P&#345;edplatn&eacute; pro %s na %s");
define( '_ACCTEXP_SEND_MSG_RENEW',"Obnoven&iacute; p&#345;edplatn&eacute;ho pro %s na %s");
define( '_ACCTEXP_MAILPARTICLE_GREETING', " %s, \n\n");
define( '_ACCTEXP_MAILPARTICLE_THANKSREG', "D&#283;kujeme za registraci na %s.");
define( '_ACCTEXP_MAILPARTICLE_THANKSREN', "D&#283;kujeme za obnoven&iacute; p&#345;edplatn&eacute;ho na %s.");
define( '_ACCTEXP_MAILPARTICLE_PAYREC', "Va&scaron;e platba za &#269;lenstv&iacute; byla p&#345;ijata.");
define( '_ACCTEXP_MAILPARTICLE_LOGIN', "Nyn&iacute; se m&#367;žete p&#345;ihl&aacute;sit na %s s použit&iacute;m uživatelsk&eacute;ho jm&eacute;na a hesla.");
define( '_ACCTEXP_MAILPARTICLE_FOOTER',"\n\nPros&iacute;m neodpov&iacute;dejte na tuto zpr&aacute;vu - je automaticky generovan&aacute;.");
define( '_ACCTEXP_ASEND_MSG',				"Dobr&yacute; den %s,\n\nNov&yacute; uživatel vytvo&#345;il p&#345;edplatn&eacute; na [ %s ].\n\nDetaily:\n\nJm&eacute;no.........: %s\nEmail........: %s\nUživatelsk&eacute; Jm&eacute;no.....: %s\nP&#345;edplat.-ID...: %s\nP&#345;edplatn&eacute;.: %s\nIP...........: %s\nISP..........: %s\n\nTato zpr&aacute;va je automaticky generovan&aacute; - neodpov&iacute;dejte na ni pros&iacute;m." );
define( '_ACCTEXP_ASEND_MSG_RENEW',			"Dobr&yacute; den %s,\n\nUživatel obnovil p&#345;edplatn&eacute; na [ %s ].\n\nDetaily:\n\nJm&eacute;no.........: %s\nEmail........: %s\Uživatelsk&eacute; Jm&eacute;no.....: %s\nP&#345;edplat.-ID...: %s\nP&#345;edplatn&eacute;.: %s\nIP...........: %s\nISP..........: %s\n\nTato zpr&aacute;va je automaticky generovan&aacute; - neodpov&iacute;dejte na ni pros&iacute;m." );
define( '_AEC_ASEND_MSG_NEW_REG',			"Dobr&yacute; den %s,\n\nNov&aacute; registrace na [ %s ].\n\nDetaily:\n\nJm&eacute;no.....: %s\nEmail.: %s\nUživatelsk&eacute; Jm&eacute;no....: %s\nIP.......: %s\nISP......: %s\n\nTato zpr&aacute;va je automaticky generovan&aacute; - neodpov&iacute;dejte na ni pros&iacute;m." );
define( '_AEC_ASEND_NOTICE',				"AEC %s: %s na %s" );
define( '_AEC_ASEND_NOTICE_MSG',		"Vzhledem k nastaven&iacute; Email reportu toto je automatick&eacute; upozorn&#283;n&iacute; na novou ud&aacute;lost.\n\nDetaily:\n\n--- --- --- ---\n\n%s\n\n--- --- --- ---\n\nTato zpr&aacute;va je automaticky generovan&aacute; - neodpov&iacute;dejte na ni pros&iacute;m. Množstv&iacute; pol&iacute; reportu m&#367;žete zm&#283;nit ve Va&scaron;em AEC nastaven&iacute;." );

// ----======== COUNTRY CODES ========----

define( 'COUNTRYCODE_SELECT', 'Vyberte Zemi' );

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