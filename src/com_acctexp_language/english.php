<?php
/**
 * @version $Id: english.php 16 2007-06-25 09:04:04Z mic $
 * @package AEC - Account Control Expiration - Subscription component for Joomla! OS CMS
 * @subpackage Language - Frontend - English
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

// new 0.12.4 - mic

define( '_AEC_EXPIRE_TODAY',				'This account is active until today' );
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
define( '_AEC_MSG_PROC_INVOICE_ACTION_EV_DUPLICATE','No Payment - Duplicate' );
define( '_AEC_MSG_PROC_INVOICE_ACTION_EV_U_ERROR',	'Unknown Error' );
define( '_AEC_MSG_PROC_INVOICE_ACTION_EV_REFUND',	'No Payment - Subscription Deleted (refund)' );
define( '_AEC_MSG_PROC_INVOICE_ACTION_EV_EXPIRED',	', User has been expired' );

// end mic ########################################################

// --== PAYMENT PLANS PAGE ==--
define( '_PAYPLANS_HEADER', 'Payment Plans');
define( '_NOPLANS_ERROR', 'No payment plans available. Please contact administrator.');

// --== ACCOUNT DETAILS PAGE ==--
define( '_CHK_USERNAME_AVAIL', "Username %s is available");
define( '_CHK_USERNAME_NOTAVAIL', "Username %s is already taken!");

// --== MY SUBSCRIPTION PAGE ==--
define( '_HISTORY_TITLE', 'Subscription History - Last 10 payments');
define( '_HISTORY_SUBTITLE', 'Member since ');
define( '_HISTORY_COL1_TITLE', 'Invoice');
define( '_HISTORY_COL2_TITLE', 'Amount');
define( '_HISTORY_COL3_TITLE', 'Payment Date');
define( '_HISTORY_COL4_TITLE', 'Method');
define( '_HISTORY_COL5_TITLE', 'Action');
define( '_HISTORY_COL6_TITLE', 'Plan');
define( '_HISTORY_ACTION_REPEAT', 'pay');
define( '_HISTORY_ACTION_CANCEL', 'cancel');
define( '_RENEW_LIFETIME', 'You have a lifetime subscription.');
define( '_RENEW_DAYSLEFT_EXCLUDED', 'You are excluded from expiration');
define( '_RENEW_DAYSLEFT_INFINITE', '&#8734');
define( '_RENEW_DAYSLEFT', 'Days left');
define( '_RENEW_INFO', 'You are using recurring payments.');
define( '_RENEW_OFFLINE', 'Renew');
define( '_RENEW_BUTTON_UPGRADE', 'Upgrade/Renew');
define( '_PAYMENT_PENDING_REASON_ECHECK', 'echeck uncleared (1-4 business days)');
define( '_PAYMENT_PENDING_REASON_TRANSFER', 'awaiting transfer payment');
define( '_YOUR_SUBSCRIPTION', 'Your Subscription');
define( '_PLAN_PROCESSOR_ACTIONS', 'For this, you have the following options:');

// --== EXPIRATION PAGE ==--
define( '_EXPIRE_INFO', 'Your account is active until');
define( '_RENEW_BUTTON', 'Renew Now');
define( '_ACCT_DATE_FORMAT', '%d-%m-%Y');
define( '_EXPIRED', 'You Subscription has expired on: ');
define( '_EXPIRED_TRIAL', 'Your trial period has expired on: ');
define( '_ERRTIMESTAMP', 'Cannot convert timestamp.');
define( '_EXPIRED_TITLE', 'Subscription Expired!');
define( '_DEAR', 'Dear %s');

// --== CONFIRMATION FORM ==--
define( '_CONFIRM_TITLE', 'Confirmation');
define( '_CONFIRM_COL1_TITLE', 'Account');
define( '_CONFIRM_COL2_TITLE', 'Detail');
define( '_CONFIRM_COL3_TITLE', 'Amount');
define( '_CONFIRM_ROW_NAME', 'Name: ');
define( '_CONFIRM_ROW_USERNAME', 'Username: ');
define( '_CONFIRM_ROW_EMAIL', 'E-mail:');
define( '_CONFIRM_INFO', 'Please use the Continue-Button to complete your registration.');
define( '_BUTTON_CONFIRM', 'Continue');
define( '_CONFIRM_TOS', "I have read and agree to the <a href=\"%s\" target=\"_BLANK\">Terms of Service</a>");
define( '_CONFIRM_TOS_ERROR', 'Please read and agree to our Terms of Service');
define( '_CONFIRM_COUPON_INFO', 'If you have a coupon code, you can enter it on the Checkout Page to get a discount on your payment');
define( '_CONFIRM_FREETRIAL', 'Free Trial');

// --== CHECKOUT FORM ==--
define( '_CHECKOUT_TITLE', 'Checkout');
define( '_CHECKOUT_INFO', 'Your Registration has been saved now. On this page, you can complete your payment. <br /> If something goes wrong along the way, you can always come back to this step by logging in to our site with your new details - Our System will give you an option to try your payment again.');
define( '_CHECKOUT_INFO_REPEAT', 'Thank you for coming back. On this page, you can complete your payment. <br /> If something goes wrong along the way, you can always come back to this step by logging in to our site with your new details - Our System will give you an option to try your payment again.');
define( '_BUTTON_CHECKOUT', 'Checkout');
define( '_BUTTON_APPEND', 'Append');
define( '_CHECKOUT_COUPON_CODE', 'Coupon Code');
define( '_CHECKOUT_INVOICE_AMOUNT', 'Invoice Amount');
define( '_CHECKOUT_INVOICE_COUPON', 'Coupon');
define( '_CHECKOUT_INVOICE_COUPON_REMOVE', 'remove');
define( '_CHECKOUT_INVOICE_TOTAL_AMOUNT', 'Total Amount');
define( '_CHECKOUT_COUPON_INFO', 'If you have a coupon code, you can enter it here to get a discount on your payment');

// --== ALLOPASS SPECIFIC ==--
define( '_REGTITLE','INSCRIPTION');
define( '_ERRORCODE','Erreur de code Allopass');
define( '_FTEXTA','Le code que vous avez utilis n\'est pas valide! Pour obtenir un code valable, composez le numero de tlphone, indiqu dans une fenetre pop-up, aprs avoir clicker sur le drapeau de votre pays. Votre browser doit accepter les cookies d\'usage.<br><br>Si vous tes certain, que vous avez le bon code, attendez quelques secondes et ressayez encore une fois!<br><br>Sinon prenez note de la date et heure de cet avertissement d\'erreur et informez le Webmaster de ce problme en indiquant le code utilis.');
define( '_RECODE','Saisir de nouveau le code Allopass');

// --== REGISTRATION STEPS ==--
define( '_STEP_DATA', 'Your Data');
define( '_STEP_CONFIRM', 'Confirm');
define( '_STEP_PLAN', 'Select Plan');
define( '_STEP_EXPIRED', 'Expired!');

// --== NOT ALLOWED PAGE ==--
define( '_NOT_ALLOWED_HEADLINE', 'Membership required!');
define( '_NOT_ALLOWED_FIRSTPAR', 'The Content you are trying to see is available only for members of our site. If you already have a Membership you need to log in to see it. Please follow this link if you want to register: ');
define( '_NOT_ALLOWED_REGISTERLINK', 'Registration Page');
define( '_NOT_ALLOWED_FIRSTPAR_LOGGED', 'The Content you are trying to see is available only for members of our site who have a certain subscription. Please follow this link if you want to change your subscription: ');
define( '_NOT_ALLOWED_REGISTERLINK_LOGGED', 'Subscription Page');
define( '_NOT_ALLOWED_SECONDPAR', 'Joining will take you less than a minute - we use the service of:');

// --== CANCELLED PAGE ==--
define( '_CANCEL_TITLE', 'Subscription Result: Cancelled!');
define( '_CANCEL_MSG', 'Our System has received the message, that you have chosen to cancel your payment. If this is due to problems that you encountered with our site, please don\'t hesiatate to contact us!');

// --== PENDING PAGE ==--
define( '_PENDING_TITLE', 'Account Pending');
define( '_WARN_PENDING', 'Your account is pending or temporarily suspended. If you are in this state for more than some hours and your payment is confirmed, please contact the administrator of this web site.');
define( '_PENDING_OPENINVOICE', 'It seems that you have an uncleared invoice in our database - If something went wrong along the way, you can go to the checkout page to try again:');
define( '_GOTO_CHECKOUT', 'Go to the checkout page again');
define( '_GOTO_CHECKOUT_CANCEL', 'you may also cancel the payment (you will have the possibility to go to the Plan Selection screen once again):');
define( '_PENDING_NOINVOICE', 'It appears that you have cancelled the only invoice that was left for your account. Please use the button below to go to the Plan Selection page again:');
define( '_PENDING_NOINVOICE_BUTTON', 'Plan Selection');
define( '_PENDING_REASON_ECHECK', '(According to our information however, you decided to pay by echeck, so you it might be that you just have to wait until this payment is cleared - which usually takes 1-4 days.)');
define( '_PENDING_REASON_TRANSFER', '(According to our information however, you decided to pay by an offline payment means, so you it might be that you just have to wait until this payment is cleared - which can take a couple of days.)');

// --== THANK YOU PAGE ==--
define( '_THANKYOU_TITLE', 'Thank You!');
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

// --== COUPON INFO ==--
define( '_COUPON_INFO', 'Coupons:');
define( '_COUPON_INFO_CONFIRM', 'If you want to use one or more coupons for this payment, you can do so on the checkout page.');
define( '_COUPON_INFO_CHECKOUT', 'Please enter your coupon code here and click the button to append it to this payment.');

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
?>