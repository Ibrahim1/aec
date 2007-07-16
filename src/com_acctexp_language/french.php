<?php
/**
 * @version $Id: german.php 16 2007-06-25 09:04:04Z mic $
 * @package AEC - Account Control Expiration - Subscription component for Joomla! OS CMS
 * @subpackage Language - Frontend
 * @copyright Copyright (C) 2004-2007, All Rights Reserved, Helder Garcia, David Deutsch
 * @author David Faurio & Lenamtl & Team AEC - http://www.gobalnerd.org
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

// new 12.0.4 (mic)

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
define( '_AEC_MSG_PROC_INVOICE_ACTION_EV_U_ERROR',	'Unknown Error' );

// --== COUPON INFO ==--
define( '_COUPON_INFO',						'Coupons:' );
define( '_COUPON_INFO_CONFIRM',				'If you want to use one or more coupons for this payment, you can do so on the checkout page.' );
define( '_COUPON_INFO_CHECKOUT',			'Please enter your coupon code here and click the button to append it to this payment.' );

// end mic ########################################################

// --== PAGE PLANS ==--
define( '_PAYPLANS_HEADER', ' Plans');
define( '_NOPLANS_ERROR', 'Aucun plan disponible pour le moment.');

// --== PAGE INFORMATIONS DU COMPTE ==--
define( '_CHK_USERNAME_AVAIL', "Le nom d&acute;utilisateur %s est disponible");
define( '_CHK_USERNAME_NOTAVAIL', "Le nom d&acute;utilisateur %s est d&eacute;j&agrave; pris!");

// --== PAGE ABONNEMENT ==--
define( '_HISTORY_TITLE', 'Historique des abonnements - 10 Derniers paiements');
define( '_HISTORY_SUBTITLE', 'Membre depuis ');
define( '_HISTORY_COL1_TITLE', 'Facture');
define( '_HISTORY_COL2_TITLE', 'Montant');
define( '_HISTORY_COL3_TITLE', 'Date de Paiement');
define( '_HISTORY_COL4_TITLE', 'M&eacute;thode');
define( '_HISTORY_COL5_TITLE', 'Action');
define( '_HISTORY_COL6_TITLE', 'Plan');
define( '_HISTORY_ACTION_REPEAT', 'pay');
define( '_HISTORY_ACTION_CANCEL', 'cancel');
define( '_RENEW_LIFETIME', 'Vous avez un abonnement &agrave; vie.');
define( '_RENEW_DAYSLEFT', 'jours restants');
define( '_RENEW_DAYSLEFT_EXCLUDED', 'You are excluded from expiration');
define( '_RENEW_DAYSLEFT_INFINITE', '&#8734');
define( '_RENEW_INFO', 'Vous utilisez les paiements r&eacute;currents.');
define( '_RENEW_OFFLINE', 'Renouveler');
define( '_RENEW_BUTTON_UPGRADE', 'Mettre &agrave; jour');
define( '_PAYMENT_PENDING_REASON_ECHECK', 'echeck uncleared (1-4 business days)');
define( '_PAYMENT_PENDING_REASON_TRANSFER', 'awaiting transfer payment');

// --== PAGE EXPIRATION ==--
define( '_EXPIRE_INFO', 'Votre compte est actif jusqu&acute;&agrave;');
define( '_RENEW_BUTTON', 'Renouveler Maintenant');
define( '_ACCT_DATE_FORMAT', '%d-%m-%Y');
define( '_EXPIRED', "Votre compte est d&eacute;sactiv&eacute;<br> Merci de nous contacter pour renouveler votre inscription.<br>Date d&acute;expiration :");
define( '_EXPIRED_TRIAL', 'Votre p&eacute;riode d&acute;essai a expir&eacute; le: ');
define( '_ERRTIMESTAMP', 'Impossible de convertir l&acute;horodatage.');
define( '_EXPIRED_TITLE', 'Compte Expir&eacute; !');
define( '_DEAR', 'Cher(e) ');

// --== FORMULAIRE DE CONFIRMATION ==--
define( '_CONFIRM_TITLE', 'Formulaire de Confirmation');
define( '_CONFIRM_COL1_TITLE', 'Compte');
define( '_CONFIRM_COL2_TITLE', 'Informations');
define( '_CONFIRM_COL3_TITLE', 'Montant');
define( '_CONFIRM_ROW_NAME', 'Nom: ');
define( '_CONFIRM_ROW_USERNAME', 'Nom d&acute;utilisateur: ');
define( '_CONFIRM_ROW_EMAIL', 'Courriel:');
define( '_CONFIRM_INFO', 'Veuillez utiliser le bouton Continuer pour compl&eacute;ter votre enregistrement.');
define( '_BUTTON_CONFIRM', 'Continuer');
define( '_CONFIRM_TOS', "I have read and agree to the <a href=\"%s\" target=\"_BLANK\">Terms of Service</a>");
define( '_CONFIRM_TOS_ERROR', 'Please read and agree to our Terms of Service');
define( '_CONFIRM_COUPON_INFO', 'If you have a coupon code, you can enter it on the Checkout Page to get a rebate on your payment');

// --== FORMULAIRE DE PAIEMENT ==--
define( '_CHECKOUT_TITLE', 'Passer &agrave; la caisse');
define( '_CHECKOUT_INFO', 'Votre Enregistrement a &eacute;t&eacute; sauvegard&eacute;. Afin d&acute;utiliser votre nouveau compte, vous devez passer &agrave; la caisse via une passerelle de paiement en cliquant sur le bouton ci-dessous. <br /> Si un probl&egrave;me arrive durant la proc&eacute;dure, vous pouvez toujours revenir en arri&egrave;re, &agrave; cette &eacute;tape en vous connectant &agrave; notre site avec vos nouveaux renseignements - Notre Syst&egrave;me vous redonnera la chance de r&eacute;essayer le paiement.');
define( '_CHECKOUT_INFO_REPEAT', 'Thank you for coming back. To complete your payment, you need to proceed to the Payment Gateway by clicking the button below. <br /> If something goes wrong along the way, you can always come back to this step by logging in to our site with your new details - Our System will give you an option to try your payment again.');
define( '_CHECKOUT_INFO_TRANSFER', 'Votre Enregistrement a &eacute;t&eacute; sauvegard&eacute;. Afin d&acute;utiliser votre nouveau compte, vous devez suivre les &eacute;tapes ci-dessous. <br /> Si un probl&egrave;me arrive durant la proc&eacute;dure, vous pouvez toujours revenir en arri&egrave;re, &agrave; cette &eacute;tape en vous connectant &agrave; note site avec vos nouveaux renseignements - Notre Syst&egrave;me vous redonnera la chance de revoir cette page.');
define( '_CHECKOUT_INFO_TRANSFER_REPEAT', 'Thank you for coming back. To complete your payment, you need to follow the details below. <br /> If something goes wrong along the way, you can always come back to this step by logging in to our site with your new details - Our System will give you an option to view this page again.');
define( '_BUTTON_CHECKOUT', 'Passer &agrave; la caisse');
define( '_BUTTON_APPEND', 'Append');
define( '_CHECKOUT_COUPON_CODE', 'Coupon Code');
define( '_CHECKOUT_INVOICE_AMOUNT', 'Invoice Amount');
define( '_CHECKOUT_INVOICE_COUPON', 'Coupon');
define( '_CHECKOUT_INVOICE_COUPON_REMOVE', 'remove');
define( '_CHECKOUT_INVOICE_TOTAL_AMOUNT', 'Total Amount');
define( '_CHECKOUT_COUPON_INFO', 'If you have a coupon code, you can enter it here to get a rebate on your payment');

// --== ALLOPASS ==--
define( '_REGTITLE','INSCRIPTION');
define( '_ERRORCODE','Erreur de code Allopass');
define( '_FTEXTA','Le code que vous avez utilis&eacute; n&acute;est pas valide! Pour obtenir un code valide, composez le num&eacute;ro de t&eacute;l&eacute;phone, indiqu&eacute; dans une fen&ecirc;tre pop-up, apr&egrave;s avoir cliqu&eacute; sur le drapeau de votre pays. Votre navigateur doit accepter les cookies.<br><br>Si vous &ecirc;tes certain, que vous avez le bon code, attendez quelques secondes et r&eacute;essayez encore une fois!<br><br>Sinon prenez note de la date et de l&acute;heure de cet avertissement d&acute;erreur et informez le Webmaster de ce probl&egrave;me en indiquant le code utilis&eacute;.');
define( '_RECODE','Saisir de nouveau le code Allopass');

// --== ETAPES D'ENREGISTREMENT ==--
define( '_STEP_DATA', 'Vos informations');
define( '_STEP_CONFIRM', 'Confirmer');
define( '_STEP_PLAN', 'S&eacute;lectionner le Plan');
define( '_STEP_EXPIRED', 'Expir&eacute;!');

// --== PAGE NON DISPONIBLE ==--
define( '_NOT_ALLOWED_HEADLINE', 'Abonnement requis!');
define( '_NOT_ALLOWED_FIRSTPAR', 'Le Contenu que vous tentez d&acute;acc&eacute;der est disponible aux abonn&eacute;es de notre site uniquement. Si vous &ecirc;tes d&eacute;j&agrave; abonn&eacute; vous devez vous connecter d&acute;abord. Veuillez suivre ce lien si vous d&eacute;sirez vous enregistrer:');
define( '_NOT_ALLOWED_REGISTERLINK', 'Page d&acute;enregistrement');
define( '_NOT_ALLOWED_FIRSTPAR_LOGGED', 'The Content you are trying to see is available only for members of our site who have a certain subscription. Please follow this link if you want to change your subscription: ');
define( '_NOT_ALLOWED_REGISTERLINK_LOGGED', 'Subscription Page');
define( '_NOT_ALLOWED_SECONDPAR', 'Vous abonnez ne prendra que quelques minutes - nous utilisons les services de:');

// --== PAGE ANNULATION ==--
define( '_CANCEL_TITLE', 'R&eacute;sultat d&acute;abonnement: Annul&eacute;!');
define( '_CANCEL_MSG', 'Notre Syst&egrave;me &agrave; re&ccedil;u un message, que vous avez choisi d&acute;annuler votre paiement. Si cela est d� &agrave; un probl&egrave;me rencontr&eacute; sur notre site, n&acute;h&eacute;sitez pas &agrave; nous contacter!');

// --== PAGE EN ATTENTE ==--
define( '_WARN_PENDING', 'Votre compte est toujours en attente. Si vous avez ce statut  depuis plusieurs heures et que votre paiement a &eacute;t&eacute; confirm&eacute;, veuillez contacter l&acute;administrateur du site Internet.');
define( '_PENDING_OPENINVOICE', 'Il semble que vous ayez une facture non pay&eacute;e dans notre syst&egrave;me - Si jamais vous rencontrez un probl&egrave;me lors de la proc&eacute;dure, vous pourrez revenir &agrave; la page de paiement de nouveau et r&eacute;essayer de nouveau:');
define( '_GOTO_CHECKOUT', 'Retourner &agrave; la page de paiement &agrave; nouveau');
define( '_GOTO_CHECKOUT_CANCEL', 'you may also cancel the payment (you will have the possibility to go to the Plan Selection screen once again):');
define( '_PENDING_NOINVOICE', 'It appears that you have cancelled the only invoice that was left for your account. Please use the button below to go to the Plan Selection page again:');
define( '_PENDING_NOINVOICE_BUTTON', 'Plan Selection');
define( '_PENDING_REASON_ECHECK', '(According to our information however, you decided to pay by echeck, so you it might be that you just have to wait until this payment is cleared - which usually takes 1-4 days.)');
define( '_PENDING_REASON_TRANSFER', '(According to our information however, you decided to pay by an offline payment means, so you it might be that you just have to wait until this payment is cleared - which can take a couple of days.)');

// --== PAGE REMERCIEMENT ==--
define( '_THANKYOU_TITLE', 'Merci!');
define( '_SUB_FEPARTICLE_HEAD', 'Abonnement Compl&eacute;t&eacute;!');
define( '_SUB_FEPARTICLE_HEAD_RENEW', 'Renouvellement d&acute;abonnement Compl&eacute;t&eacute;!');
define( '_SUB_FEPARTICLE_LOGIN', 'Vous pouvez vous connecter maintenant.');
define( '_SUB_FEPARTICLE_THANKS', 'Merci de vous &ecirc;tre abonn&eacute;. ');
define( '_SUB_FEPARTICLE_THANKSRENEW', 'Merci d&acute;avoir renouvel&eacute; votre abonnement. ');
define( '_SUB_FEPARTICLE_PROCESS', 'Notre syst&egrave;me va maintenant traiter votre demande. ');
define( '_SUB_FEPARTICLE_PROCESSPAY', 'Notre syst&egrave;me est en attente de votre paiement. ');
define( '_SUB_FEPARTICLE_ACTMAIL', 'Vous allez recevoir un message par courriel qui contiendra un lien d&acute;activation quand notre syst&egrave;me aura trait&eacute; votre demande. ');
define( '_SUB_FEPARTICLE_MAIL', 'Vous allez recevoir un message courriel une fois que notre syst&egrave;me. ');

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

// ----======== TEXTE POUR MESSAGES COURRIELS ========----

define( '_ACCTEXP_SEND_MSG','Abonnement pour %s &agrave; %s');
define( '_ACCTEXP_SEND_MSG_RENEW','Renouvellement de l&acute;abonnement pour %s &agrave; %s');
define( '_ACCTEXP_MAILPARTICLE_GREETING', "Bonjour %s,\n\n");
define( '_ACCTEXP_MAILPARTICLE_THANKSREG', "Merci de vous &ecirc;tre abonn&eacute; &agrave; %s.\n");
define( '_ACCTEXP_MAILPARTICLE_THANKSREN', "Merci d&acute;avoir renouvel&eacute; votre abonnement &agrave; %s. ");
define( '_ACCTEXP_MAILPARTICLE_PAYREC', "Votre paiement pour votre abonnement a &eacute;t&eacute; re&ccedil;u. ");
define( '_ACCTEXP_MAILPARTICLE_LOGIN', "Vous pouvez maintenant vous connecter &agrave; %s avec votre nom d&acute;utilisateur et mot de passe. ");
define( '_ACCTEXP_MAILPARTICLE_FOOTER',"\n\nNe pas r&eacute;pondre &agrave; ce message il a &eacute;t&eacute; g&eacute;n&eacute;r&eacute; automatiquement et seulement pour votre information.");

define( '_ACCTEXP_ASEND_MSG',				"Bonjour %s,\n\nun nouvel utilisateur a cr&eacute;&eacute; un abonnement &agrave; [ %s ].\n\nLes informations pour cet utilisateur sont:\n\nNom..............: %s\nCourriel.........: %s\nNom d&acute;utilisateur.: %s\nSubscr.-ID.......: %s\nSubscription.....: %s\nIP...............: %s\nISP..............: %s\n\nNe pas r&eacute;pondre &agrave; ce message il a &eacute;t&eacute; g&eacute;n&eacute;r&eacute; automatiquement et seulement pour votre information." );
define( '_ACCTEXP_ASEND_MSG_RENEW',			"Bonjour %s,\n\na user has renewed his subscription at [ %s ].\n\nLes informations pour cet utilisateur sont:\n\nNom..............: %s\nCourriel.........: %s\nNom d&acute;utilisateur.: %s\nSubscr.-ID.......: %s\nSubscription.....: %s\nIP...............: %s\nISP..............: %s\n\nNe pas r&eacute;pondre &agrave; ce message il a &eacute;t&eacute; g&eacute;n&eacute;r&eacute; automatiquement et seulement pour votre information." );
define( '_AEC_ASEND_MSG_NEW_REG',			"Bonjour %s,\n\na new registration at [ %s ].\n\nHere further details:\n\nName.....: %s\nUsername.: %s\nEmail....: %s\nIP.......: %s\nISP......: %s\n\nPlease do not respond to this message as it is automatically generated and is for information purposes only." );
?>
