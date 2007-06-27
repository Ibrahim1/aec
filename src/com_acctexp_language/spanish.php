<?php
/**
 * @version $Id: spanish.php 16 2007-06-25 09:04:04Z mic $
 * @package AEC - Account Control Expiration - Subscription component for Joomla! OS CMS
 * @subpackage Language - Frontend - Spanish
 * @copyright Copyright (C) 2004-2007, All Rights Reserved, Helder Garcia, David Deutsch
 * @author MGS Creativa (www.mgscreativa.com.ar) & Team AEC - http://www.gobalnerd.org
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

// new mic
define( '_AEC_EXPIRE_NOT_SET',				'Not Set' );
define( '_AEC_GEN_ERROR',					'<h1>General Error</h1><p>We had problems processing your request. Please contact the web site administrator.</p>' );

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
DEFINE ('_NOPLANS_ERROR', 'No payment plans available. Please contact administrator.');

// --== ACCOUNT DETAILS PAGE ==--
DEFINE ('_CHK_USERNAME_AVAIL', "Username %s is available");
DEFINE ('_CHK_USERNAME_NOTAVAIL', "Username %s is already taken!");

// --== MY SUBSCRIPTION PAGE ==--
DEFINE ('_HISTORY_TITLE', 'Subscription History - Last 10 payments');
DEFINE ('_HISTORY_SUBTITLE', 'Member since ');
DEFINE ('_HISTORY_COL1_TITLE', 'Invoice');
DEFINE ('_HISTORY_COL2_TITLE', 'Amount');
DEFINE ('_HISTORY_COL3_TITLE', 'Payment Date');
DEFINE ('_HISTORY_COL4_TITLE', 'Method');
DEFINE ('_HISTORY_COL5_TITLE', 'Action');
DEFINE ('_HISTORY_COL6_TITLE', 'Plan');
DEFINE ('_HISTORY_ACTION_REPEAT', 'pay');
DEFINE ('_HISTORY_ACTION_CANCEL', 'cancel');
DEFINE ('_RENEW_LIFETIME', 'You have a lifetime subscription.');
DEFINE ('_RENEW_DAYSLEFT_EXCLUDED', 'You are excluded from expiration');
DEFINE ('_RENEW_DAYSLEFT_INFINITE', '&#8734');
DEFINE ('_RENEW_DAYSLEFT', 'Days left');
DEFINE ('_RENEW_INFO', 'You are using recurring payments.');
DEFINE ('_RENEW_OFFLINE', 'Renew');
DEFINE ('_RENEW_BUTTON_UPGRADE', 'Upgrade');
DEFINE ('_PAYMENT_PENDING_REASON_ECHECK', 'echeck uncleared (1-4 business days)');
DEFINE ('_PAYMENT_PENDING_REASON_TRANSFER', 'awaiting transfer payment');

// --== EXPIRATION PAGE ==--
DEFINE ('_EXPIRE_INFO', 'Your account is active until');
DEFINE ('_RENEW_BUTTON', 'Renew Now');
DEFINE ('_ACCT_DATE_FORMAT', '%m-%d-%Y');
DEFINE ('_EXPIRED', 'Tu cuenta a caducado. Por favor contactanos para renovar tu suscripcion. Fecha de caducidad: ');
DEFINE ('_EXPIRED_TRIAL', 'Your trial period has expired on: ');
DEFINE ('_ERRTIMESTAMP', 'No puedo convertir la marca temporal.');
DEFINE ('_EXPIRED_TITLE', 'Cuenta caducada!!');
DEFINE ('_DEAR', 'Estimado ');

// --== CONFIRMATION FORM ==--
DEFINE ('_CONFIRM_TITLE', 'Confirmation Form');
DEFINE ('_CONFIRM_COL1_TITLE', 'Account');
DEFINE ('_CONFIRM_COL2_TITLE', 'Detail');
DEFINE ('_CONFIRM_COL3_TITLE', 'Amount');
DEFINE ('_CONFIRM_ROW_NAME', 'Name: ');
DEFINE ('_CONFIRM_ROW_USERNAME', 'Username: ');
DEFINE ('_CONFIRM_ROW_EMAIL', 'E-mail:');
DEFINE ('_CONFIRM_INFO', 'Please use the Continue-Button to complete your registration.');
DEFINE ('_BUTTON_CONFIRM', 'Continue');
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
DEFINE ('_STEP_DATA', 'Your Data');
DEFINE ('_STEP_CONFIRM', 'Confirm');
DEFINE ('_STEP_PLAN', 'Select Plan');
DEFINE ('_STEP_EXPIRED', 'Expired!');

// --== NOT ALLOWED PAGE ==--
DEFINE ('_NOT_ALLOWED_HEADLINE', 'Membership required!');
DEFINE ('_NOT_ALLOWED_FIRSTPAR', 'The Content you are trying to see is available only for members of our site. If you already have a Membership you need to log in to see it. Please follow this link if you want to register: ');
DEFINE ('_NOT_ALLOWED_REGISTERLINK', 'Registration Page');
DEFINE ('_NOT_ALLOWED_FIRSTPAR_LOGGED', 'The Content you are trying to see is available only for members of our site who have a certain subscription. Please follow this link if you want to change your subscription: ');
DEFINE ('_NOT_ALLOWED_REGISTERLINK_LOGGED', 'Subscription Page');
DEFINE ('_NOT_ALLOWED_SECONDPAR', 'Joining will take you less than a minute - we use the service of:');

// --== CANCELLED PAGE ==--
DEFINE ('_CANCEL_TITLE', 'Subscription Result: Cancelled!');
DEFINE ('_CANCEL_MSG', 'Our System has received the message, that you have chosen to cancel your payment. If this is due to problems that you encountered with our site, please don\'t hesiatate to contact us!');

// --== PENDING PAGE ==--
DEFINE ('_WARN_PENDING', 'Your account is still pending. If you are in this state for more than some hours and your payment is confirmed, please contact the administrator of this web site.');
DEFINE ('_WARN_PENDING', 'Your account is still pending. If you are in this state for more than some hours and your payment is confirmed, please contact the administrator of this web site.');
DEFINE ('_PENDING_OPENINVOICE', 'It seems that you have an uncleared invoice in our database - If something went wrong along the way, you can go to the checkout page once again to try again:');
DEFINE ('_GOTO_CHECKOUT', 'Go to the checkout page again');
DEFINE ('_GOTO_CHECKOUT_CANCEL', 'you may also cancel the payment (you will have the possibility to go to the Plan Selection screen once again):');
DEFINE ('_PENDING_NOINVOICE', 'It appears that you have cancelled the only invoice that was left for your account. Please use the button below to go to the Plan Selection page again:');
DEFINE ('_PENDING_NOINVOICE_BUTTON', 'Plan Selection');
DEFINE ('_PENDING_REASON_ECHECK', '(According to our information however, you decided to pay by echeck, so you it might be that you just have to wait until this payment is cleared - which usually takes 1-4 days.)');
DEFINE ('_PENDING_REASON_TRANSFER', '(According to our information however, you decided to pay by an offline payment means, so you it might be that you just have to wait until this payment is cleared - which can take a couple of days.)');

// --== THANK YOU PAGE ==--
DEFINE ('_THANKYOU_TITLE', 'Thank You!');
DEFINE ('_SUB_FEPARTICLE_HEAD', 'Subscription Complete!');
DEFINE ('_SUB_FEPARTICLE_HEAD_RENEW', 'Subscription Renew Complete!');
DEFINE ('_SUB_FEPARTICLE_LOGIN', 'You may login now.');
DEFINE ('_SUB_FEPARTICLE_THANKS', 'Thank you for your registration. ');
DEFINE ('_SUB_FEPARTICLE_THANKSRENEW', 'Thank you for renewing your subscription. ');
DEFINE ('_SUB_FEPARTICLE_PROCESS', 'Our system will now work on your request. ');
DEFINE ('_SUB_FEPARTICLE_PROCESSPAY', 'Our system will now await your payment. ');
DEFINE ('_SUB_FEPARTICLE_ACTMAIL', 'You will receive an e-mail with an activation link once our system has processed your request. ');
DEFINE ('_SUB_FEPARTICLE_MAIL', 'You will receive an e-mail once our system has processed your request. ');

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

DEFINE ('_ACCTEXP_SEND_MSG','Subscription for %s at %s');
DEFINE ('_ACCTEXP_SEND_MSG_RENEW','Subscription renew for %s at %s');
DEFINE ('_ACCTEXP_MAILPARTICLE_GREETING', "Hello %s,

");
DEFINE ('_ACCTEXP_MAILPARTICLE_THANKSREG', "Thank you for registering at %s. ");
DEFINE ('_ACCTEXP_MAILPARTICLE_THANKSREN', "Thank you for renewing your subscription at %s. ");
DEFINE ('_ACCTEXP_MAILPARTICLE_PAYREC', "Your payment for your membership has been received. ");
DEFINE ('_ACCTEXP_MAILPARTICLE_LOGIN', "You may now login to %s with your username and password. ");
DEFINE ('_ACCTEXP_MAILPARTICLE_FOOTER',"

Please do not respond to this message as it is automatically generated and is for information purposes only.");

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