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

// --== PAGE PLANS ==--
DEFINE ('_NOPLANS_ERROR', 'Aucun plan disponible pour le moment.');

// --== PAGE INFORMATIONS DU COMPTE ==--
DEFINE ('_CHK_USERNAME_AVAIL', "Le nom d&acute;utilisateur %s est disponible");
DEFINE ('_CHK_USERNAME_NOTAVAIL', "Le nom d&acute;utilisateur %s est d&eacute;j&agrave; pris!");

// --== PAGE ABONNEMENT ==--
DEFINE ('_HISTORY_TITLE', 'Historique des abonnements - 10 Derniers paiements');
DEFINE ('_HISTORY_SUBTITLE', 'Membre depuis ');
DEFINE ('_HISTORY_COL1_TITLE', 'Facture');
DEFINE ('_HISTORY_COL2_TITLE', 'Montant');
DEFINE ('_HISTORY_COL3_TITLE', 'Date de Paiement');
DEFINE ('_HISTORY_COL4_TITLE', 'M&eacute;thode');
DEFINE ('_HISTORY_COL5_TITLE', 'Action');
DEFINE ('_HISTORY_COL6_TITLE', 'Plan');
DEFINE ('_HISTORY_ACTION_REPEAT', 'pay');
DEFINE ('_HISTORY_ACTION_CANCEL', 'cancel');
DEFINE ('_RENEW_LIFETIME', 'Vous avez un abonnement &agrave; vie.');
DEFINE ('_RENEW_DAYSLEFT', 'jours restants');
DEFINE ('_RENEW_DAYSLEFT_EXCLUDED', 'You are excluded from expiration');
DEFINE ('_RENEW_DAYSLEFT_INFINITE', '&#8734');
DEFINE ('_RENEW_INFO', 'Vous utilisez les paiements r&eacute;currents.');
DEFINE ('_RENEW_OFFLINE', 'Renouveler');
DEFINE ('_RENEW_BUTTON_UPGRADE', 'Mettre &agrave; jour');
DEFINE ('_PAYMENT_PENDING_REASON_ECHECK', 'echeck uncleared (1-4 business days)');
DEFINE ('_PAYMENT_PENDING_REASON_TRANSFER', 'awaiting transfer payment');

// --== PAGE EXPIRATION ==--
DEFINE ('_EXPIRE_INFO', 'Votre compte est actif jusqu&acute;&agrave;');
DEFINE ('_RENEW_BUTTON', 'Renouveler Maintenant');
DEFINE ('_ACCT_DATE_FORMAT', '%d-%m-%Y');
DEFINE ('_EXPIRED', "Votre compte est d&eacute;sactiv&eacute;<br> Merci de nous contacter pour renouveler votre inscription.<br>Date d&acute;expiration :");
DEFINE ('_EXPIRED_TRIAL', 'Votre p&eacute;riode d&acute;essai a expir&eacute; le: ');
DEFINE ('_ERRTIMESTAMP', 'Impossible de convertir l&acute;horodatage.');
DEFINE ('_EXPIRED_TITLE', 'Compte Expir&eacute; !');
DEFINE ('_DEAR', 'Cher(e) ');

// --== FORMULAIRE DE CONFIRMATION ==--
DEFINE ('_CONFIRM_TITLE', 'Formulaire de Confirmation');
DEFINE ('_CONFIRM_COL1_TITLE', 'Compte');
DEFINE ('_CONFIRM_COL2_TITLE', 'Informations');
DEFINE ('_CONFIRM_COL3_TITLE', 'Montant');
DEFINE ('_CONFIRM_ROW_NAME', 'Nom: ');
DEFINE ('_CONFIRM_ROW_USERNAME', 'Nom d&acute;utilisateur: ');
DEFINE ('_CONFIRM_ROW_EMAIL', 'Courriel:');
DEFINE ('_CONFIRM_INFO', 'Veuillez utiliser le bouton Continuer pour compl&eacute;ter votre enregistrement.');
DEFINE ('_BUTTON_CONFIRM', 'Continuer');
DEFINE ('_CONFIRM_TOS', "I have read and agree to the <a href=\"%s\" target=\"_BLANK\">Terms of Service</a>");
DEFINE ('_CONFIRM_TOS_ERROR', 'Please read and agree to our Terms of Service');
DEFINE ('_CONFIRM_COUPON_INFO', 'If you have a coupon code, you can enter it on the Checkout Page to get a rebate on your payment');

// --== FORMULAIRE DE PAIEMENT ==--
DEFINE ('_CHECKOUT_TITLE', 'Passer &agrave; la caisse');
DEFINE ('_CHECKOUT_INFO', 'Votre Enregistrement a &eacute;t&eacute; sauvegard&eacute;. Afin d&acute;utiliser votre nouveau compte, vous devez passer &agrave; la caisse via une passerelle de paiement en cliquant sur le bouton ci-dessous. <br /> Si un probl&egrave;me arrive durant la proc&eacute;dure, vous pouvez toujours revenir en arri&egrave;re, &agrave; cette &eacute;tape en vous connectant &agrave; notre site avec vos nouveaux renseignements - Notre Syst&egrave;me vous redonnera la chance de r&eacute;essayer le paiement.');
DEFINE ('_CHECKOUT_INFO_REPEAT', 'Thank you for coming back. To complete your payment, you need to proceed to the Payment Gateway by clicking the button below. <br /> If something goes wrong along the way, you can always come back to this step by logging in to our site with your new details - Our System will give you an option to try your payment again.');
DEFINE ('_CHECKOUT_INFO_TRANSFER', 'Votre Enregistrement a &eacute;t&eacute; sauvegard&eacute;. Afin d&acute;utiliser votre nouveau compte, vous devez suivre les &eacute;tapes ci-dessous. <br /> Si un probl&egrave;me arrive durant la proc&eacute;dure, vous pouvez toujours revenir en arri&egrave;re, &agrave; cette &eacute;tape en vous connectant &agrave; note site avec vos nouveaux renseignements - Notre Syst&egrave;me vous redonnera la chance de revoir cette page.');
DEFINE ('_CHECKOUT_INFO_TRANSFER_REPEAT', 'Thank you for coming back. To complete your payment, you need to follow the details below. <br /> If something goes wrong along the way, you can always come back to this step by logging in to our site with your new details - Our System will give you an option to view this page again.');
DEFINE ('_BUTTON_CHECKOUT', 'Passer &agrave; la caisse');
DEFINE ('_BUTTON_APPEND', 'Append');
DEFINE ('_CHECKOUT_COUPON_CODE', 'Coupon Code');
DEFINE ('_CHECKOUT_INVOICE_AMOUNT', 'Invoice Amount');
DEFINE ('_CHECKOUT_INVOICE_COUPON', 'Coupon');
DEFINE ('_CHECKOUT_INVOICE_COUPON_REMOVE', 'remove');
DEFINE ('_CHECKOUT_INVOICE_TOTAL_AMOUNT', 'Total Amount');
DEFINE ('_CHECKOUT_COUPON_INFO', 'If you have a coupon code, you can enter it here to get a rebate on your payment');

// --== ALLOPASS ==--
DEFINE ('_REGTITLE','INSCRIPTION');
DEFINE ('_ERRORCODE','Erreur de code Allopass');
DEFINE ('_FTEXTA','Le code que vous avez utilis&eacute; n&acute;est pas valide! Pour obtenir un code valide, composez le num&eacute;ro de t&eacute;l&eacute;phone, indiqu&eacute; dans une fen&ecirc;tre pop-up, apr&egrave;s avoir cliqu&eacute; sur le drapeau de votre pays. Votre navigateur doit accepter les cookies.<br><br>Si vous &ecirc;tes certain, que vous avez le bon code, attendez quelques secondes et r&eacute;essayez encore une fois!<br><br>Sinon prenez note de la date et de l&acute;heure de cet avertissement d&acute;erreur et informez le Webmaster de ce probl&egrave;me en indiquant le code utilis&eacute;.');
DEFINE ('_RECODE','Saisir de nouveau le code Allopass');

// --== ETAPES D'ENREGISTREMENT ==--
DEFINE ('_STEP_DATA', 'Vos informations');
DEFINE ('_STEP_CONFIRM', 'Confirmer');
DEFINE ('_STEP_PLAN', 'S&eacute;lectionner le Plan');
DEFINE ('_STEP_EXPIRED', 'Expir&eacute;!');

// --== PAGE NON DISPONIBLE ==--
DEFINE ('_NOT_ALLOWED_HEADLINE', 'Abonnement requis!');
DEFINE ('_NOT_ALLOWED_FIRSTPAR', 'Le Contenu que vous tentez d&acute;acc&eacute;der est disponible aux abonn&eacute;es de notre site uniquement. Si vous &ecirc;tes d&eacute;j&agrave; abonn&eacute; vous devez vous connecter d&acute;abord. Veuillez suivre ce lien si vous d&eacute;sirez vous enregistrer:');
DEFINE ('_NOT_ALLOWED_REGISTERLINK', 'Page d&acute;enregistrement');
DEFINE ('_NOT_ALLOWED_FIRSTPAR_LOGGED', 'The Content you are trying to see is available only for members of our site who have a certain subscription. Please follow this link if you want to change your subscription: ');
DEFINE ('_NOT_ALLOWED_REGISTERLINK_LOGGED', 'Subscription Page');
DEFINE ('_NOT_ALLOWED_SECONDPAR', 'Vous abonnez ne prendra que quelques minutes - nous utilisons les services de:');

// --== PAGE ANNULATION ==--
DEFINE ('_CANCEL_TITLE', 'R&eacute;sultat d&acute;abonnement: Annul&eacute;!');
DEFINE ('_CANCEL_MSG', 'Notre Syst&egrave;me &agrave; re&ccedil;u un message, que vous avez choisi d&acute;annuler votre paiement. Si cela est d� &agrave; un probl&egrave;me rencontr&eacute; sur notre site, n&acute;h&eacute;sitez pas &agrave; nous contacter!');

// --== PAGE EN ATTENTE ==--
DEFINE ('_WARN_PENDING', 'Votre compte est toujours en attente. Si vous avez ce statut  depuis plusieurs heures et que votre paiement a &eacute;t&eacute; confirm&eacute;, veuillez contacter l&acute;administrateur du site Internet.');
DEFINE ('_PENDING_OPENINVOICE', 'Il semble que vous ayez une facture non pay&eacute;e dans notre syst&egrave;me - Si jamais vous rencontrez un probl&egrave;me lors de la proc&eacute;dure, vous pourrez revenir &agrave; la page de paiement de nouveau et r&eacute;essayer de nouveau:');
DEFINE ('_GOTO_CHECKOUT', 'Retourner &agrave; la page de paiement &agrave; nouveau');
DEFINE ('_GOTO_CHECKOUT_CANCEL', 'you may also cancel the payment (you will have the possibility to go to the Plan Selection screen once again):');
DEFINE ('_PENDING_NOINVOICE', 'It appears that you have cancelled the only invoice that was left for your account. Please use the button below to go to the Plan Selection page again:');
DEFINE ('_PENDING_NOINVOICE_BUTTON', 'Plan Selection');
DEFINE ('_PENDING_REASON_ECHECK', '(According to our information however, you decided to pay by echeck, so you it might be that you just have to wait until this payment is cleared - which usually takes 1-4 days.)');
DEFINE ('_PENDING_REASON_TRANSFER', '(According to our information however, you decided to pay by an offline payment means, so you it might be that you just have to wait until this payment is cleared - which can take a couple of days.)');

// --== PAGE REMERCIEMENT ==--
DEFINE ('_THANKYOU_TITLE', 'Merci!');
DEFINE ('_SUB_FEPARTICLE_HEAD', 'Abonnement Compl&eacute;t&eacute;!');
DEFINE ('_SUB_FEPARTICLE_HEAD_RENEW', 'Renouvellement d&acute;abonnement Compl&eacute;t&eacute;!');
DEFINE ('_SUB_FEPARTICLE_LOGIN', 'Vous pouvez vous connecter maintenant.');
DEFINE ('_SUB_FEPARTICLE_THANKS', 'Merci de vous &ecirc;tre abonn&eacute;. ');
DEFINE ('_SUB_FEPARTICLE_THANKSRENEW', 'Merci d&acute;avoir renouvel&eacute; votre abonnement. ');
DEFINE ('_SUB_FEPARTICLE_PROCESS', 'Notre syst&egrave;me va maintenant traiter votre demande. ');
DEFINE ('_SUB_FEPARTICLE_PROCESSPAY', 'Notre syst&egrave;me est en attente de votre paiement. ');
DEFINE ('_SUB_FEPARTICLE_ACTMAIL', 'Vous allez recevoir un message par courriel qui contiendra un lien d&acute;activation quand notre syst&egrave;me aura trait&eacute; votre demande. ');
DEFINE ('_SUB_FEPARTICLE_MAIL', 'Vous allez recevoir un message courriel une fois que notre syst&egrave;me. ');

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

// ----======== TEXTE POUR MESSAGES COURRIELS ========----

DEFINE ('_ACCTEXP_SEND_MSG','Abonnement pour %s &agrave; %s');
DEFINE ('_ACCTEXP_SEND_MSG_RENEW','Renouvellement de l&acute;abonnement pour %s &agrave; %s');
DEFINE ('_ACCTEXP_MAILPARTICLE_GREETING', "Bonjour %s,

");
DEFINE ('_ACCTEXP_MAILPARTICLE_THANKSREG', "Merci de vous &ecirc;tre abonn&eacute; &agrave; %s.
");
DEFINE ('_ACCTEXP_MAILPARTICLE_THANKSREN', "Merci d&acute;avoir renouvel&eacute; votre abonnement &agrave; %s. ");
DEFINE ('_ACCTEXP_MAILPARTICLE_PAYREC', "Votre paiement pour votre abonnement a &eacute;t&eacute; re&ccedil;u. ");
DEFINE ('_ACCTEXP_MAILPARTICLE_LOGIN', "Vous pouvez maintenant vous connecter &agrave; %s avec votre nom d&acute;utilisateur et mot de passe. ");
DEFINE ('_ACCTEXP_MAILPARTICLE_FOOTER',"

Ne pas r&eacute;pondre &agrave; ce message il a &eacute;t&eacute; g&eacute;n&eacute;r&eacute; automatiquement et seulement pour votre information.");

DEFINE ('_ACCTEXP_ASEND_MSG',"Bonjour %s,

Un nouvel utilisateur a cr&eacute;&eacute; un abonnement &agrave; %s.

Les informations pour cet utilisateur sont:

Nom - %s
Courriel - %s
Nom d&acute;utilisateur - %s

Ne pas r&eacute;pondre &agrave; ce message il a &eacute;t&eacute; g&eacute;n&eacute;r&eacute; automatiquement et seulement pour votre information.");
DEFINE ('_ACCTEXP_ASEND_MSG_RENEW',"Bonjour %s,

Un utilisateur a renouvel&eacute; son abonnement &agrave; %s.

Les informations pour cet utilisateur sont:

Nom - %s
Courriel - %s
Nom d&acute;utilisateur - %s

Ne pas r&eacute;pondre &agrave; ce message il a &eacute;t&eacute; g&eacute;n&eacute;r&eacute; automatiquement et seulement pour votre information.");
?>
