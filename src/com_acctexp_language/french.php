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

define( '_AEC_EXPIRE_TODAY',				'Ce compte est actif jusqu\'&agrave; aujourd\'hui' );
define( '_AEC_EXPIRE_FUTURE',				'Ce compte est actif jusqu\'au' );
define( '_AEC_EXPIRE_PAST',					'Ce compte &eacute;tait actif jusqu\'au' );
define( '_AEC_DAYS_ELAPSED',				'jour(s) &eacute;coul&eacute;s');

define( '_AEC_EXPIRE_NOT_SET',				'Non d&eacute;termin&eacute;' );
define( '_AEC_GEN_ERROR',					'<h1>Erreur g&eacute;n&eacute;rale</h1><p>Nous avons rencontr&eacute; des probl&egrave;mes pour traiter votre demande. Veuillez contacter l\'administrateur du site Web.</p>' );

// payments
define( '_AEC_PAYM_METHOD_FREE',			'Gratuit' );
define( '_AEC_PAYM_METHOD_NONE',			'Aucun' );
define( '_AEC_PAYM_METHOD_TRANSFER',		'Transfert' );

// processor errors
define( '_AEC_MSG_PROC_INVOICE_FAILED_SH',			'Le paiement de la facture &agrave; &eacute;chou&eacute;' );
define( '_AEC_MSG_PROC_INVOICE_FAILED_EV',			'La notification du processeur %s pour %s a &eacute;chou&eacute; - ce num&eacute;ro de facture n\'existe pas :' );
define( '_AEC_MSG_PROC_INVOICE_ACTION_SH',			'Action de paiement de la facture' );
define( '_AEC_MSG_PROC_INVOICE_ACTION_EV',			'Le parseur de notification de paiement r&eacute;pond :' );
define( '_AEC_MSG_PROC_INVOICE_ACTION_EV_STATUS',	'Etat de la facture :' );
define( '_AEC_MSG_PROC_INVOICE_ACTION_EV_FRAUD',	'La v&eacute;rification du montant a &eacute;chou&eacute;, r&eacute;gl&eacute;: %s, facture: %s - paiement annul&eacute;' );
define( '_AEC_MSG_PROC_INVOICE_ACTION_EV_CURR',		'La v&eacute;rification de la monnaie a &eacute;chou&eacute;, r&eacute;gl&eacute;: %s, facture: %s - paiement annul&eacute;' );
define( '_AEC_MSG_PROC_INVOICE_ACTION_EV_VALID',	'Paiement valid&eacute;, l\'action est engag&eacute;e' );
define( '_AEC_MSG_PROC_INVOICE_ACTION_EV_VALID_APPFAIL',	'Payment valid, Application failed!' );
define( '_AEC_MSG_PROC_INVOICE_ACTION_EV_TRIAL',	'Paiement valid&eacute; - p&eacute;riode d\'essai gratuite' );
define( '_AEC_MSG_PROC_INVOICE_ACTION_EV_PEND',		'Paiement non valid&eacute; - compte en suspens ; raison : %s' );
define( '_AEC_MSG_PROC_INVOICE_ACTION_EV_CANCEL',	'Pas de paiement - Abonnement annul&eacute;' );
define( '_AEC_MSG_PROC_INVOICE_ACTION_EV_USTATUS',	', le compte utilisateur a &eacute;t&eacute; mis &agrave; jour &agrave; \'Annul&eacute;\'' );
define( '_AEC_MSG_PROC_INVOICE_ACTION_EV_EOT',		'Pas de paiment - Fin de la p&eacute;riode d\'abonnement' );
define( '_AEC_MSG_PROC_INVOICE_ACTION_EV_DUPLICATE','No Payment - Duplicate' );
define( '_AEC_MSG_PROC_INVOICE_ACTION_EV_U_ERROR',	'Erreur inconnue' );
define( '_AEC_MSG_PROC_INVOICE_ACTION_EV_REFUND',	'No Payment - Subscription Deleted (refund)' );
define( '_AEC_MSG_PROC_INVOICE_ACTION_EV_EXPIRED',	', User has been expired' );

// --== COUPON INFO ==--
define( '_COUPON_INFO',						'Bons de r&eacute;duction :' );
define( '_COUPON_INFO_CONFIRM',				'Si vous voulez utiliser un ou plusieurs bons de r&eacute;duction pour ce paiement, vous pouvez le faire &agrave; partir de la page de règlement.' );
define( '_COUPON_INFO_CHECKOUT',			'Veuillez saisir ici le code de votre bon de r&eacute;duction et cliquer sur le bouton pour qu\'il soit pris en compte.' );

// end mic ########################################################

// --== PAGE PLANS ==--
define( '_PAYPLANS_HEADER', ' Plans');
define( '_NOPLANS_ERROR', 'Aucun plan disponible pour le moment.');

// --== PAGE INFORMATIONS DU COMPTE ==--
define( '_CHK_USERNAME_AVAIL', "Le nom d\'utilisateur %s est disponible");
define( '_CHK_USERNAME_NOTAVAIL', "Le nom d\'utilisateur %s est d&eacute;j&agrave; pris!");

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
define( '_RENEW_DAYSLEFT_EXCLUDED', 'Vous n\'êtes pas concerné par l\'expiration');
define( '_RENEW_DAYSLEFT_INFINITE', '&#8734');
define( '_RENEW_INFO', 'Vous utilisez les paiements r&eacute;currents.');
define( '_RENEW_OFFLINE', 'Renouveler');
define( '_RENEW_BUTTON_UPGRADE', 'Mettre &agrave; jour');
define( '_PAYMENT_PENDING_REASON_ECHECK', '&eacute;chec non r&eacute;solu (1-4 jours ouvr&eacute;s)');
define( '_PAYMENT_PENDING_REASON_TRANSFER', 'en attente de transfert du paiement');
define( '_YOUR_SUBSCRIPTION', 'Your Subscription');
define( '_YOUR_FURTHER_SUBSCRIPTIONS', 'Further Subscriptions');
define( '_PLAN_PROCESSOR_ACTIONS', 'For this, you have the following options:');

// --== PAGE EXPIRATION ==--
define( '_EXPIRE_INFO', 'Votre compte est actif jusqu\'au');
define( '_RENEW_BUTTON', 'Renouveler Maintenant');
define( '_ACCT_DATE_FORMAT', '%d-%m-%Y');
define( '_EXPIRED', "Votre compte est d&eacute;sactiv&eacute;<br> Merci de nous contacter pour renouveler votre inscription.<br>Date d\'expiration :");
define( '_EXPIRED_TRIAL', 'Votre p&eacute;riode d\'essai a expir&eacute; le : ');
define( '_ERRTIMESTAMP', 'Impossible de convertir l\'horodatage.');
define( '_EXPIRED_TITLE', 'Compte expir&eacute; !');
define( '_DEAR', 'Cher(e) %s');

// --== FORMULAIRE DE CONFIRMATION ==--
define( '_CONFIRM_TITLE', 'Formulaire de confirmation');
define( '_CONFIRM_COL1_TITLE', 'Compte');
define( '_CONFIRM_COL2_TITLE', 'Informations');
define( '_CONFIRM_COL3_TITLE', 'Montant');
define( '_CONFIRM_ROW_NAME', 'Nom: ');
define( '_CONFIRM_ROW_USERNAME', 'Nom d\'utilisateur: ');
define( '_CONFIRM_ROW_EMAIL', 'mail:');
define( '_CONFIRM_INFO', 'Veuillez utiliser le bouton Continuer pour compl&eacute;ter votre enregistrement.');
define( '_BUTTON_CONFIRM', 'Continuer');
define( '_CONFIRM_TOS', "J'ai lu et j\'accepte les <a href=\"%s\" target=\"_BLANK\">conditions d\'utilisation</a>");
define( '_CONFIRM_TOS_ERROR', 'Veuillez lire et accepter nos conditions d\'utilisation');
define( '_CONFIRM_COUPON_INFO', 'Si vous avez un code de bon de réduction, vous pourrez le saisir sur la page du r&egrave;glement pour qu\'il soit pris en compte.');
define( '_CONFIRM_COUPON_INFO_BOTH', 'If you have a coupon code, you can enter it here, or on the Checkout Page to get a discount on your payment');
define( '_CONFIRM_FREETRIAL', 'Free Trial');

// --== PROMPT PASSWORD FORM ==--
define( '_AEC_PROMPT_PASSWORD', 'For security reasons, you need to put in your password to continue.');
define( '_AEC_PROMPT_PASSWORD_WRONG', 'The Password you have entered does not match with the one we have registered for you in our database. Please try again.');
define( '_AEC_PROMPT_PASSWORD_BUTTON', 'Continue');

// --== FORMULAIRE DE PAIEMENT ==--
define( '_CHECKOUT_TITLE', 'Effectuer votre r&egrave;glement');
define( '_CHECKOUT_INFO', 'Your Registration has been saved now. On this page, you can complete your payment. <br /> If something goes wrong along the way, you can always come back to this step by logging in to our site with your new details - Our System will give you an option to try your payment again.');
define( '_CHECKOUT_INFO_REPEAT', 'Thank you for coming back. On this page, you can complete your payment. <br /> If something goes wrong along the way, you can always come back to this step by logging in to our site with your new details - Our System will give you an option to try your payment again.');
define( '_BUTTON_CHECKOUT', 'Effectuer votre r&egrave;glement');
define( '_BUTTON_APPEND', 'Ajouter');
define( '_CHECKOUT_COUPON_CODE', 'Code du bon de r&eacute;duction');
define( '_CHECKOUT_INVOICE_AMOUNT', 'Montant de la facture');
define( '_CHECKOUT_INVOICE_COUPON', 'Bon de r&eactue;duction');
define( '_CHECKOUT_INVOICE_COUPON_REMOVE', 'supprimer');
define( '_CHECKOUT_INVOICE_TOTAL_AMOUNT', 'Montant total');
define( '_CHECKOUT_COUPON_INFO', 'Si vous avez un code de bon de réduction, vous pouvez le saisir ici pour qu\'il soit pris en compte.');

// --== ALLOPASS ==--
define( '_REGTITLE','INSCRIPTION');
define( '_ERRORCODE','Erreur de code Allopass');
define( '_FTEXTA','Le code que vous avez utilis&eacute; n\'est pas valide! Pour obtenir un code valide, composez le num&eacute;ro de t&eacute;l&eacute;phone, indiqu&eacute; dans une fen&ecirc;tre pop-up, apr&egrave;s avoir cliqu&eacute; sur le drapeau de votre pays. Votre navigateur doit accepter les cookies.<br><br>Si vous &ecirc;tes certain, que vous avez le bon code, attendez quelques secondes et r&eacute;essayez encore une fois!<br><br>Sinon prenez note de la date et de l\'heure de cet avertissement d\'erreur et informez le Webmaster de ce probl&egrave;me en indiquant le code utilis&eacute;.');
define( '_RECODE','Saisir de nouveau le code Allopass');

// --== ETAPES D'ENREGISTREMENT ==--
define( '_STEP_DATA', 'Vos informations');
define( '_STEP_CONFIRM', 'Confirmer');
define( '_STEP_PLAN', 'S&eacute;lectionner le plan');
define( '_STEP_EXPIRED', 'Expir&eacute;!');

// --== PAGE NON DISPONIBLE ==--
define( '_NOT_ALLOWED_HEADLINE', 'Abonnement requis!');
define( '_NOT_ALLOWED_FIRSTPAR', 'Le Contenu que vous tentez d\'acc&eacute;der est uniquement accessible aux abonn&eacute;es de notre site. Si vous &ecirc;tes d&eacute;j&agrave; abonn&eacute; vous devez d\'abord vous connecter. Veuillez suivre ce lien si vous d&eacute;sirez vous abonner:');
define( '_NOT_ALLOWED_REGISTERLINK', 'Page d\'enregistrement');
define( '_NOT_ALLOWED_FIRSTPAR_LOGGED', 'Le contenu que vous essayez de consulter n\'est accessible qu\'&agrave; nos membres ayant souscrit à un abonnement sp&eacute;cifique. Veuillez suivre ce lien si vous souhaitez vous abonner : ');
define( '_NOT_ALLOWED_REGISTERLINK_LOGGED', 'Page d\'bonnement');
define( '_NOT_ALLOWED_SECONDPAR', 'Vous abonner ne prendra que quelques minutes - nous utilisons les services de :');

// --== PAGE ANNULATION ==--
define( '_CANCEL_TITLE', 'R&eacute;sultat d\'abonnement: Annul&eacute;!');
define( '_CANCEL_MSG', 'Notre syst&egrave;me &agrave; re&ccedil;u un message indiquant que vous avez choisi d\'annuler votre paiement. Si cela est du &agrave; un probl&egrave;me rencontr&eacute; sur notre site, n\'h&eacute;sitez pas &agrave; nous contacter !');

// --== PAGE EN ATTENTE ==--
define( '_WARN_PENDING', 'Votre compte est toujours en attente. Si vous avez ce statut depuis plusieurs heures et que votre paiement a &eacute;t&eacute; confirm&eacute;, veuillez contacter l\'administrateur du site Internet.');
define( '_PENDING_OPENINVOICE', 'Il semble que vous ayez une facture non pay&eacute;e dans notre syst&egrave;me - Si jamais vous rencontrez un probl&egrave;me lors de la proc&eacute;dure, vous pourrez revenir &agrave; la page de paiement et r&eacute;essayer :');
define( '_GOTO_CHECKOUT', 'Retourner &agrave; la page de paiement &agrave; nouveau');
define( '_GOTO_CHECKOUT_CANCEL', 'vous avez &eacute;galement la possibilibt&eacute; d\'annuler votre r&egrave;glement (vous pourrez alors retourner à l\'&eacute;cran de s&eacute;lection du plan) :');
define( '_PENDING_NOINVOICE', 'Il apparait que vous avez annul&eacute; la seule facture restante de votre compte. Veuillez cliquer sur le bouton ci-dessous pour retourner à l\'&eacute;cran de s&eacute;lection du plan :');
define( '_PENDING_NOINVOICE_BUTTON', 'S&eacute;lection du plan');
define( '_PENDING_REASON_ECHECK', '(According to our information however, you decided to pay by echeck, so you it might be that you just have to wait until this payment is cleared - which usually takes 1-4 days.)');
define( '_PENDING_REASON_TRANSFER', '(According to our information however, you decided to pay by an offline payment means, so you it might be that you just have to wait until this payment is cleared - which can take a couple of days.)');

// --== PAGE REMERCIEMENT ==--
define( '_THANKYOU_TITLE', 'Merci !');
define( '_SUB_FEPARTICLE_HEAD', 'Abonnement compl&eacute;t&eacute;!');
define( '_SUB_FEPARTICLE_HEAD_RENEW', 'Renouvellement d\'abonnement compl&eacute;t&eacute;!');
define( '_SUB_FEPARTICLE_LOGIN', 'Vous pouvez vous connecter maintenant.');
define( '_SUB_FEPARTICLE_THANKS', 'Merci de vous &ecirc;tre abonn&eacute;. ');
define( '_SUB_FEPARTICLE_THANKSRENEW', 'Merci d\'avoir renouvel&eacute; votre abonnement. ');
define( '_SUB_FEPARTICLE_PROCESS', 'Notre syst&egrave;me va maintenant traiter votre demande. ');
define( '_SUB_FEPARTICLE_PROCESSPAY', 'Notre syst&egrave;me est en attente de votre paiement. ');
define( '_SUB_FEPARTICLE_ACTMAIL', 'Vous allez recevoir un message par mail qui contiendra un lien d\'activation quand notre syst&egrave;me aura trait&eacute; votre demande. ');
define( '_SUB_FEPARTICLE_MAIL', 'Vous allez recevoir un mail une fois que notre syst&egrave;me. ');

// --== CHECKOUT ERROR PAGE ==--
define( '_CHECKOUT_ERROR_TITLE', 'Error while processing the payment!');
define( '_CHECKOUT_ERROR_EXPLANATION', 'An error occured while processing your payment');
define( '_CHECKOUT_ERROR_OPENINVOICE', 'This leaves your invoice uncleared. To retry the payment, you can go to the checkout page once again to try again:');

// --== COUPON ERROR MESSAGES ==--
define( '_COUPON_WARNING_AMOUNT', 'Un des bons de r&eacute;duction que vous avez ajout&eacute; &agrave; cette facture ne modifie pas le montant du prochain paiement. Bien qu\'il semble ne pas affecter cette facture, il modifie en r&eacute;alit&eacute; un paiement ult&eacute;rieur.');
define( '_COUPON_ERROR_PRETEXT', 'Nous sommes d&eacute;sol&eacute;s :');
define( '_COUPON_ERROR_EXPIRED', 'Ce bon de r&eacute;duction est expir&eacute;.');
define( '_COUPON_ERROR_NOTSTARTED', 'vous ne pouvez pas encore utiliser ce bon de r&eacute;duction.');
define( '_COUPON_ERROR_NOTFOUND', 'Ce bon de r&eacute;duction n\'existe pas.');
define( '_COUPON_ERROR_MAX_REUSE', 'Ce bon de r&eacute;duction a d&eacute;j&agrave; &eacute;t&eacute; utilis&eacute; le nombre de fois pr&eacute;.');
define( '_COUPON_ERROR_PERMISSION', 'Vous n\'avez pas l\'autorisation d\'utiliser ce bon de r&eacute;duction.');
define( '_COUPON_ERROR_WRONG_USAGE', 'Vous ne pouvez pas utiliser ce bon de r&eacute;duction dans ce cas-ci.');
define( '_COUPON_ERROR_WRONG_PLAN', 'Vous n\'&ecirc;tes pas dans le bon plan d\'abonnement pour ce bon de r&eacute;duction.');
define( '_COUPON_ERROR_WRONG_PLAN_PREVIOUS', 'Pour pouvoir utiliser ce bon de r&eacute;duction, votre dernier plan d\'abonnement doit &ecirc;tre diff&eacute;rent.');
define( '_COUPON_ERROR_WRONG_PLANS_OVERALL', 'Vous n\'avez pas dans le bon plan d\'abonnement dans votre historique pour ce bon de r&eacute;duction.');
define( '_COUPON_ERROR_TRIAL_ONLY', 'Vous ne pouvez utiliser ce bon de r&eacute;duction que pour une p&eacute;riode d\'essai.');
define( '_COUPON_ERROR_COMBINATION', 'You cannot use this coupon with one of the other coupons.');
define( '_COUPON_ERROR_SPONSORSHIP_ENDED', 'Sponsorship for this Coupon has ended or is currently inactive.');

// ----======== TEXTE POUR MESSAGES mailS ========----

define( '_ACCTEXP_SEND_MSG','Abonnement du %s au %s');
define( '_ACCTEXP_SEND_MSG_RENEW','Renouvellement de l\'abonnement pour la p&eacute;riode du %s au %s');
define( '_ACCTEXP_MAILPARTICLE_GREETING', "Bonjour %s,\n\n");
define( '_ACCTEXP_MAILPARTICLE_THANKSREG', "Merci de vous &ecirc;tre abonn&eacute; &agrave; %s.\n");
define( '_ACCTEXP_MAILPARTICLE_THANKSREN', "Merci d\'avoir renouvel&eacute; votre abonnement &agrave; %s. ");
define( '_ACCTEXP_MAILPARTICLE_PAYREC', "Votre paiement pour votre abonnement a &eacute;t&eacute; re&ccedil;u. ");
define( '_ACCTEXP_MAILPARTICLE_LOGIN', "Vous pouvez maintenant vous connecter &agrave; %s avec votre nom d\'utilisateur et mot de passe. ");
define( '_ACCTEXP_MAILPARTICLE_FOOTER',"\n\nNe pas r&eacute;pondre &agrave; ce message, il a &eacute;t&eacute; g&eacute;n&eacute;r&eacute; automatiquement et seulement pour votre information.");

define( '_ACCTEXP_ASEND_MSG',				"Bonjour %s,\n\nun nouvel utilisateur a cr&eacute;&eacute; un abonnement &agrave; [ %s ].\n\nLes informations pour cet utilisateur sont:\n\nNom..............: %s\nmail.........: %s\nNom d\'utilisateur.: %s\nSubscr.-ID.......: %s\nSubscription.....: %s\nIP...............: %s\nISP..............: %s\n\nNe pas r&eacute;pondre &agrave; ce message il a &eacute;t&eacute; g&eacute;n&eacute;r&eacute; automatiquement et seulement pour votre information." );
define( '_ACCTEXP_ASEND_MSG_RENEW',			"Bonjour %s,\n\nun abonn&eacte;bonn&eacute; a renouvell&eacute; son abonnement &agrave; [ %s ].\n\nLes informations pour cet utilisateur sont:\n\nNom..............: %s\nmail.........: %s\nNom d\'utilisateur.: %s\nSubscr.-ID.......: %s\nAbonnement.....: %s\nIP...............: %s\nISP..............: %s\n\nNe pas r&eacute;pondre &agrave; ce message, il a &eacute;t&eacute; g&eacute;n&eacute;r&eacute; automatiquement et seulement pour votre information." );
define( '_AEC_ASEND_MSG_NEW_REG',			"Bonjour %s,\n\nvoici un nouvel inscrit &agrave; [ %s ].\n\nVoici les d&eacute;tails :\n\nNom.....: %s\nNom d\'utilisateur: %s\nEmail....: %s\nIP.......: %s\nFAI......: %s\n\nNe pas r&eacute;pondre &agrave; ce message, il a &eacute;t&eacute; g&eacute;n&eacute;r&eacute; automatiquement et seulement pour votre information." );
define( '_AEC_ASEND_NOTICE',				"AEC %s: %s at %s" );
define( '_AEC_ASEND_NOTICE_MSG',		"According to the E-Mail reporting level you have selected, this is an automatic notification about an EventLog entry.\n\nThe details of this message are:\n\n--- --- --- ---\n\n%s\n\n--- --- --- ---\n\nPlease do not respond to this message as it is automatically generated and is for information purposes only. You can change the level of reported entries in your AEC Settings." );

?>