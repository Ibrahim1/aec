<?php
/**
 * @version $Id: italian.php
 * @package AEC - Account Control Expiration - Membership Manager
 * @subpackage Language - Frontend - Italian
 * @copyright 2006-2008 Copyright (C) David Deutsch
 * @author David Deutsch <skore@skore.de> & Team AEC - http://www.valanx.org
 * @license GNU/GPL v.2 http://www.gnu.org/licenses/old-licenses/gpl-2.0.html or, at your option, any later version
 */

// Dont allow direct linking
( defined('_JEXEC') || defined( '_VALID_MOS' ) ) or die( 'Accesso diretto non acconsentito a questo file.' );

if( defined( '_AEC_LANG' ) ) {
	return;
}

// new 12.0.4
define( '_AEC_EXPIRE_TODAY',				'This account is active until today' );
define( '_AEC_EXPIRE_FUTURE',				'This account is active until' );
define( '_AEC_EXPIRE_PAST',					'This account was valid until' );
define( '_AEC_DAYS_ELAPSED',				'day(s) elapsed');
define( '_AEC_EXPIRE_TRIAL_TODAY',			'This trial is active until today' );
define( '_AEC_EXPIRE_TRIAL_FUTURE',			'This trial is active until' );
define( '_AEC_EXPIRE_TRIAL_PAST',			'This trial was valid until' );

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
define( '_AEC_MSG_PROC_INVOICE_ACTION_EV_VALID_APPFAIL',	'Payment valid, Application failed!' );
define( '_AEC_MSG_PROC_INVOICE_ACTION_EV_TRIAL',	'Payment valid - free trial' );
define( '_AEC_MSG_PROC_INVOICE_ACTION_EV_PEND',		'Payment invalid - status is pending, reason: %s' );
define( '_AEC_MSG_PROC_INVOICE_ACTION_EV_CANCEL',	'No Payment - Subscription Cancel' );
define( '_AEC_MSG_PROC_INVOICE_ACTION_EV_CHARGEBACK',	'No Payment - Chargeback' );
define( '_AEC_MSG_PROC_INVOICE_ACTION_EV_CHARGEBACK_SETTLE',	'No Payment - Chargeback Settlement' );
define( '_AEC_MSG_PROC_INVOICE_ACTION_EV_USTATUS',	', Userstatus has been updated to \'Cancelled\'' );
define( '_AEC_MSG_PROC_INVOICE_ACTION_EV_USTATUS_HOLD',	', Userstatus has been updated to \'Hold\'' );
define( '_AEC_MSG_PROC_INVOICE_ACTION_EV_USTATUS_ACTIVE',	', Userstatus has been updated to \'Active\'' );
define( '_AEC_MSG_PROC_INVOICE_ACTION_EV_EOT',		'No Payment - Subscription End Of Term' );
define( '_AEC_MSG_PROC_INVOICE_ACTION_EV_DUPLICATE','No Payment - Duplicate' );
define( '_AEC_MSG_PROC_INVOICE_ACTION_EV_NULL','No Payment - Null' );
define( '_AEC_MSG_PROC_INVOICE_ACTION_EV_U_ERROR',	'Unknown Error' );
define( '_AEC_MSG_PROC_INVOICE_ACTION_EV_REFUND',	'No Payment - Subscription Deleted (refund)' );
define( '_AEC_MSG_PROC_INVOICE_ACTION_EV_EXPIRED',	', User has been expired' );

// --== COUPON INFO ==--
define( '_COUPON_INFO',						'Coupons:' );
define( '_COUPON_INFO_CONFIRM',				'If you want to use one or more coupons for this payment, you can do so on the checkout page.' );
define( '_COUPON_INFO_CHECKOUT',			'Please enter your coupon code here and click the button to append it to this payment.' );

// end mic ########################################################

// --== PAYMENT PLANS PAGE ==--
define( '_PAYPLANS_HEADER', 'Piani Pagamento');
define( '_NOPLANS_ERROR', 'No payment plans available. Please contact administrator.');
define( '_NOPLANS_AUTHERROR', 'You are not authorized to access this option. Please contact administrator if you have any further questions.');
define( '_PLANGROUP_BACK', '&lt; Go back');

// --== ACCOUNT DETAILS PAGE ==--
define( '_CHK_USERNAME_AVAIL', "Username %s &egrave; utilizzabile");
define( '_CHK_USERNAME_NOTAVAIL', "Username %s &egrave; gi&agrave; stata usata!");

// --== MY SUBSCRIPTION PAGE ==--
define( '_MYSUBSCRIPTION_TITLE', 'My Membership');
define( '_MEMBER_SINCE', 'Membro da');
define( '_HISTORY_COL1_TITLE', 'Contratto');
define( '_HISTORY_COL2_TITLE', 'Ammontare');
define( '_HISTORY_COL3_TITLE', 'Data Pagamento');
define( '_HISTORY_COL4_TITLE', 'Metodo');
define( '_HISTORY_COL5_TITLE', 'Action');
define( '_HISTORY_COL6_TITLE', 'Plan');
define( '_HISTORY_ACTION_REPEAT', 'pay');
define( '_HISTORY_ACTION_CANCEL', 'cancel');
define( '_RENEW_LIFETIME', 'Hai un\' inscrizione a vita.');
define( '_RENEW_DAYSLEFT', 'Giorni mancanti');
define( '_RENEW_DAYSLEFT_TRIAL', 'Days left in Trial');
define( '_RENEW_DAYSLEFT_EXCLUDED', 'You are excluded from expiration');
define( '_RENEW_DAYSLEFT_INFINITE', '&#8734');
define( '_RENEW_BUTTON', 'Rinnova Ora');
define( '_RENEW_INFO', 'State usando pagamenti periodici.');
define( '_RENEW_OFFLINE', 'Rinnova');
define( '_PAYMENT_PENDING_REASON_ECHECK', 'echeck uncleared (1-4 business days)');
define( '_PAYMENT_PENDING_REASON_TRANSFER', 'awaiting transfer payment');
define( '_YOUR_SUBSCRIPTION', 'Your Subscription');
define( '_YOUR_FURTHER_SUBSCRIPTIONS', 'Further Subscriptions');
define( '_PLAN_PROCESSOR_ACTIONS', 'For this, you have the following options:');
define( '_AEC_SUBDETAILS_TAB_OVERVIEW', 'Overview');
define( '_AEC_SUBDETAILS_TAB_INVOICES', 'Invoices');
define( '_AEC_SUBDETAILS_TAB_DETAILS', 'Details');

define( '_HISTORY_ACTION_PRINT', 'print');
define( '_INVOICEPRINT_DATE', 'Date');
define( '_INVOICEPRINT_ID', 'ID');
define( '_INVOICEPRINT_REFERENCE_NUMBER', 'Reference Number');
define( '_INVOICEPRINT_ITEM_NAME', 'Item Name');
define( '_INVOICEPRINT_UNIT_PRICE', 'Unit Price');
define( '_INVOICEPRINT_QUANTITY', 'Quantity');
define( '_INVOICEPRINT_TOTAL', 'Total');
define( '_INVOICEPRINT_GRAND_TOTAL', 'Grand Total');

define( '_INVOICEPRINT_ADDRESSFIELD', 'Enter your Address here - it will then show on the printout.');
define( '_INVOICEPRINT_PRINT', 'Print');
define( '_INVOICEPRINT_BLOCKNOTICE', 'This block (including the text field and print button) will not show on your printout.');
define( '_INVOICEPRINT_PRINT_TYPEABOVE', 'Please type your address into the field above.');
define( '_INVOICEPRINT_PAIDSTATUS_UNPAID', '<strong>This invoice has not been paid yet.</strong>');
define( '_INVOICEPRINT_PAIDSTATUS_PAID', 'This invoice has been paid on: %s');

define( '_AEC_YOUSURE', 'Are you sure?');

define( '_AEC_WILLEXPIRE', 'This membership will expire');
define( '_AEC_WILLRENEW', 'This membership will renew');
define( '_AEC_ISLIFETIME', 'Lifetime Membership');

// --== EXPIRATION PAGE ==--
define( '_EXPIRE_INFO', 'Il tuo account &egrave; attivo dal');
define( '_RENEW_BUTTON', 'Renew Now');
define( '_RENEW_BUTTON_CONTINUE', 'Extend Previous Membership');
define( '_ACCT_DATE_FORMAT', '%m/%d/%Y');
define( '_EXPIRED', 'Il tuo account &egrave; scaduto il: ');
define( '_EXPIRED_TRIAL', 'Your trial period has expired on: ');
define( '_ERRTIMESTAMP', 'Non &egrave; possibile convertire il timestamp.');
define( '_EXPIRED_TITLE', 'Account Scaduto!!');
define( '_DEAR', 'Caro %s');

// --== CONFIRMATION FORM ==--
define( '_CONFIRM_TITLE', 'Riepilogo di conferma');
define( '_CONFIRM_COL1_TITLE', 'Account');
define( '_CONFIRM_COL2_TITLE', 'Detail');
define( '_CONFIRM_COL3_TITLE', 'Amount');
define( '_CONFIRM_ROW_NAME', 'Name: ');
define( '_CONFIRM_ROW_USERNAME', 'Username: ');
define( '_CONFIRM_ROW_EMAIL', 'E-mail:');
define( '_CONFIRM_INFO', 'Please use the Continue-Button to complete your registration.');
define( '_BUTTON_CONFIRM', 'Continua');
define( '_CONFIRM_TOS', "I have read and agree to the <a href=\"%s\" target=\"_blank\" title=\"ToS\">Terms of Service</a>");
define( '_CONFIRM_TOS_IFRAME', "I have read and agree to the Terms of Service (above)");
define( '_CONFIRM_TOS_ERROR', 'Please read and agree to our Terms of Service');
define( '_CONFIRM_COUPON_INFO', 'If you have a coupon code, you can enter it on the Checkout Page to get a rebate on your payment');
define( '_CONFIRM_COUPON_INFO_BOTH', 'If you have a coupon code, you can enter it here, or on the Checkout Page to get a discount on your payment');
define( '_CONFIRM_FREETRIAL', 'Free Trial');
define( '_CONFIRM_YOU_HAVE_SELECTED', 'You have selected');

define( '_CONFIRM_DIFFERENT_USER_DETAILS', 'Want to change the user details?');
define( '_CONFIRM_DIFFERENT_ITEM', 'Wanted to select a different item?');

// --== SHOPPING CART FORM ==--
define( '_CART_TITLE', 'Shopping Cart');
define( '_CART_ROW_TOTAL', 'Total');
define( '_CART_INFO', 'Please use the Continue-Button below to complete your purchase.');

// --== EXCEPTION FORM ==--
define( '_EXCEPTION_TITLE', 'Additional Information Required');
define( '_EXCEPTION_TITLE_NOFORM', 'Please note:');
define( '_EXCEPTION_INFO', 'To proceed with your checkout, we need you to provide additional information as specified below:');

// --== PROMPT PASSWORD FORM ==--
define( '_AEC_PROMPT_PASSWORD', 'For security reasons, you need to put in your password to continue.');
define( '_AEC_PROMPT_PASSWORD_WRONG', 'The Password you have entered does not match with the one we have registered for you in our database. Please try again.');
define( '_AEC_PROMPT_PASSWORD_BUTTON', 'Continue');

// --== CHECKOUT FORM ==--
define( '_CHECKOUT_TITLE', 'Checkout');
define( '_CHECKOUT_INFO', 'Your Registration has been saved now. On this page, you can complete your payment for invoice %s. <br /><br /> If something goes wrong along the way, you can always come back to this step by logging in to our site with your username and password - Our System will give you an option to try your payment again.');
define( '_CHECKOUT_INFO_REPEAT', 'Thank you for coming back. On this page, you can complete your payment for invoice %s. <br /><br /> If something goes wrong along the way, you can always come back to this step by logging in to our site with your username and password - Our System will give you an option to try your payment again.');
define( '_BUTTON_CHECKOUT', 'Checkout');
define( '_BUTTON_APPEND', 'Append');
define( '_BUTTON_APPLY', 'Apply');
define( '_BUTTON_EDIT', 'Edit');
define( '_BUTTON_SELECT', 'Select');
define( '_CHECKOUT_COUPON_CODE', 'Coupon Code');
define( '_CHECKOUT_INVOICE_AMOUNT', 'Invoice Amount');
define( '_CHECKOUT_INVOICE_COUPON', 'Coupon');
define( '_CHECKOUT_INVOICE_COUPON_REMOVE', 'remove');
define( '_CHECKOUT_INVOICE_TOTAL_AMOUNT', 'Total Amount');
define( '_CHECKOUT_COUPON_INFO', 'If you have a coupon code, you can enter it here to get a rebate on your payment');
define( '_CHECKOUT_GIFT_HEAD', 'Gift to another user');
define( '_CHECKOUT_GIFT_INFO', 'Enter details for another user of this site to give the item(s) you are about to purchase to that account.');

define( '_AEC_TERMTYPE_TRIAL', 'Initial Billing');
define( '_AEC_TERMTYPE_TERM', 'Regular Billing Term');
define( '_AEC_CHECKOUT_TERM', 'Billing Term');
define( '_AEC_CHECKOUT_NOTAPPLICABLE', 'not applicable');
define( '_AEC_CHECKOUT_FUTURETERM', 'future term');
define( '_AEC_CHECKOUT_COST', 'Cost');
define( '_AEC_CHECKOUT_TAX', 'Tax');
define( '_AEC_CHECKOUT_DISCOUNT', 'Discount');
define( '_AEC_CHECKOUT_TOTAL', 'Total');
define( '_AEC_CHECKOUT_DURATION', 'Duration');

define( '_AEC_CHECKOUT_DUR_LIFETIME', 'Lifetime');

define( '_AEC_CHECKOUT_DUR_DAY', 'Day');
define( '_AEC_CHECKOUT_DUR_DAYS', 'Days');
define( '_AEC_CHECKOUT_DUR_WEEK', 'Week');
define( '_AEC_CHECKOUT_DUR_WEEKS', 'Weeks');
define( '_AEC_CHECKOUT_DUR_MONTH', 'Month');
define( '_AEC_CHECKOUT_DUR_MONTHS', 'Months');
define( '_AEC_CHECKOUT_DUR_YEAR', 'Year');
define( '_AEC_CHECKOUT_DUR_YEARS', 'Years');

// --== ALLOPASS SPECIFIC ==--
define( '_REGTITLE','ISCRIZIONE');
define( '_ERRORCODE','Codice di errore Allopass');
define( '_FTEXTA','Il codice che avete utilizzato non &egrave; valido! Per ottenere un codice valido, chiamate il num. di telefono, indicato nella finestra di pop-up, che si apre dopo il click. Il vostro browser deve accettare i cookie.<br /><br />Se siete certi di avere un codice corretto, attendete qualche secondo e riprovate un\'altra volta!<br /><br />Altrimenti segnatevi data e ora in cui avete ricevuto l\'avviso di errore e informate il Webmaster del problema indicando il codice che avete utilizzato.');
define( '_RECODE','Inserire nuovamente il codice Allopass');

// --== REGISTRATION STEPS ==--
define( '_STEP_DATA', 'Data');
define( '_STEP_CONFIRM', 'Conferma');
define( '_STEP_PLAN', 'Seleziona Piano');
define( '_STEP_EXPIRED', 'Scaduto!');

// --== NOT ALLOWED PAGE ==--
define( '_NOT_ALLOWED_HEADLINE', 'Richiesta Registrazione!');
define( '_NOT_ALLOWED_FIRSTPAR', 'Il contenuto che state cercando di visualizzare &egrave; avviabile esclusivamente per i membri del nostro sito. Verificate i vostri piani di pagamento seguendo questo link:');
define( '_NOT_ALLOWED_REGISTERLINK', 'Pagina di Registrazione');
define( '_NOT_ALLOWED_FIRSTPAR_LOGGED', 'The Content you are trying to see is available only for members of our site who have a certain subscription. Please follow this link if you want to change your subscription: ');
define( '_NOT_ALLOWED_REGISTERLINK_LOGGED', 'Subscription Page');
define( '_NOT_ALLOWED_SECONDPAR', 'Unirsi alla membership richieder&agrave; meno di un minuto - usiamo i servizi di:');

// --== CANCELLED PAGE ==--
define( '_CANCEL_TITLE', 'Risultato iscrizione: Cancellata!');
define( '_CANCEL_MSG', 'Il nostro sistema ha ricevuto il messaggio, che lei ha deciso di cancellare il suo pagamento. Se questo è causato da problemi che ha riscontrato nel il nostro sito, la preghiamo di contattarci!');

// --== PENDING PAGE ==--
define( '_WARN_PENDING', 'Il suo account &egrave; ancora in sospeso. Se vi trovate in questo stato da pi&ugrave; di qualche ora e il suo pagamento è stato confermato, vi preghiamo di contattare l\'amministratore del sito.');
define( '_WARN_PENDING', 'Your account is still pending. If you are in this state for more than some hours and your payment is confirmed, please contact the administrator of this web site.');
define( '_PENDING_OPENINVOICE', 'It seems that you have an uncleared invoice in our database - If something went wrong along the way, you can go to the checkout page once again to try again:');
define( '_GOTO_CHECKOUT', 'Go to the checkout page again');
define( '_GOTO_CHECKOUT_CANCEL', 'you may also cancel the payment (you will have the possibility to go to the Plan Selection screen once again):');
define( '_PENDING_NOINVOICE', 'It appears that you have cancelled the only invoice that was left for your account. Please use the button below to go to the Plan Selection page again:');
define( '_PENDING_NOINVOICE_BUTTON', 'Plan Selection');
define( '_PENDING_REASON_ECHECK', '(According to our information however, you decided to pay by echeck (or similar), so you it might be that you just have to wait until this payment is cleared - which usually takes 1-4 days.)');
define( '_PENDING_REASON_WAITING_RESPONSE', '(According to our information however, we are just waiting for a response from the payment processor. You will be notified once that has happened. Sorry for the delay.)');
define( '_PENDING_REASON_TRANSFER', '(According to our information however, you decided to pay by an offline payment means, so you it might be that you just have to wait until this payment is cleared - which can take a couple of days.)');

// --== HOLD PAGE ==--
define( '_HOLD_TITLE', 'Account on Hold');
define( '_HOLD_EXPLANATION', 'Your account is currently on hold. The most likely cause for this is that there was a problem with a payment you recently made. If you don\'t receive an email within the next 24 hours, please contact the site administrator.');

// --== THANK YOU PAGE ==--
define( '_THANKYOU_TITLE', 'Grazie!');
define( '_SUB_FEPARTICLE_HEAD', 'Subscription Complete!');
define( '_SUB_FEPARTICLE_HEAD_RENEW', 'Subscription Renew Complete!');
define( '_SUB_FEPARTICLE_LOGIN', 'You may login now.');
define( '_SUB_FEPARTICLE_THANKS', 'Thank you for your registration. ');
define( '_SUB_FEPARTICLE_THANKSRENEW', 'Thank you for renewing your subscription. ');
define( '_SUB_FEPARTICLE_PROCESS', 'Our system will now work on your request. ');
define( '_SUB_FEPARTICLE_PROCESSPAY', 'Our system will now await your payment. ');
define( '_SUB_FEPARTICLE_ACTMAIL', 'You will receive an e-mail with an activation link once our system has processed your request. ');
define( '_SUB_FEPARTICLE_MAIL', 'You will receive an e-mail once our system has processed your request. ');

// --== CHECKOUT ERROR PAGE ==--
define( '_CHECKOUT_ERROR_TITLE', 'Error while processing the payment!');
define( '_CHECKOUT_ERROR_EXPLANATION', 'An error occured while processing your payment');
define( '_CHECKOUT_ERROR_OPENINVOICE', 'This leaves your invoice uncleared. To retry the payment, you can go to the checkout page once again to try again:');
define( '_CHECKOUT_ERROR_FURTHEREXPLANATION', 'This leaves your invoice uncleared, but you can try to check out again below. If you experience further problems or need any assistance with your checkout, please do not hesitate to contact us.');

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
define( '_COUPON_ERROR_COMBINATION', 'You cannot use this coupon with one of the other coupons.');
define( '_COUPON_ERROR_SPONSORSHIP_ENDED', 'Sponsorship for this Coupon has ended or is currently inactive.');

// ----======== EMAIL TEXT ========----

define( '_AEC_SEND_SUB',				"Account details for %s at %s" );
define( '_AEC_USEND_MSG',				"Hello %s,\n\nThank you for registering at %s.\n\nYou may now login to %s using the username and password you registered with." );
define( '_AEC_USEND_MSG_ACTIVATE',				"Hello %s,\n\nThank you for registering at %s. Your account is created and must be activated before you can use it.\nTo activate the account click on the following link or copy-paste it in your browser:\n%s\n\nAfter activation you may login to %s using the following username and password:\n\nUsername - %s\nPassword - %s" );
define( '_ACCTEXP_SEND_MSG','Subscription for %s at %s');
define( '_ACCTEXP_SEND_MSG_RENEW','Subscription renew for %s at %s');
define( '_ACCTEXP_MAILPARTICLE_GREETING', "Hello %s, \n\n");
define( '_ACCTEXP_MAILPARTICLE_THANKSREG', 'Thank you for registering at %s.');
define( '_ACCTEXP_MAILPARTICLE_THANKSREN', 'Thank you for renewing your subscription at %s.');
define( '_ACCTEXP_MAILPARTICLE_PAYREC', 'Your payment for your membership has been received.');
define( '_ACCTEXP_MAILPARTICLE_LOGIN', 'You may now login to %s with your username and password.');
define( '_ACCTEXP_MAILPARTICLE_FOOTER',"\n\nPlease do not respond to this message as it is automatically generated and is for information purposes only.");
define( '_ACCTEXP_ASEND_MSG',				"Hello %s,\n\na new user has created a subscription at [ %s ].\n\nHere further details:\n\nName.........: %s\nEmail........: %s\nUsername.....: %s\nSubscr.-ID...: %s\nSubscription.: %s\nIP...........: %s\nISP..........: %s\n\nPlease do not respond to this message as it is automatically generated and is for information purposes only." );
define( '_ACCTEXP_ASEND_MSG_RENEW',			"Hello %s,\n\na user has renewed his subscription at [ %s ].\n\nHere further details:\n\nName.........: %s\nEmail........: %s\nUsername.....: %s\nSubscr.-ID...: %s\nSubscription.: %s\nIP...........: %s\nISP..........: %s\n\nPlease do not respond to this message as it is automatically generated and is for information purposes only." );
define( '_AEC_ASEND_MSG_NEW_REG',			"Hello %s,\n\nThere has been a new registration at [ %s ].\n\nHere further details:\n\nName.....: %s\nEmail.: %s\nUsername....: %s\nIP.......: %s\nISP......: %s\n\nPlease do not respond to this message as it is automatically generated and is for information purposes only." );
define( '_AEC_ASEND_NOTICE',				"AEC %s: %s at %s" );
define( '_AEC_ASEND_NOTICE_MSG',		"According to the E-Mail reporting level you have selected, this is an automatic notification about an EventLog entry.\n\nThe details of this message are:\n\n--- --- --- ---\n\n%s\n\n--- --- --- ---\n\nPlease do not respond to this message as it is automatically generated and is for information purposes only. You can change the level of reported entries in your AEC Settings." );

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
