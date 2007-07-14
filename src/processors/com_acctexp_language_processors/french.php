<?php
/**
 * @version $Id: french.php 16 2007-06-25 09:04:04Z mic $
 * @package AEC - Account Expiration Control / Subscription management for Joomla
 * @subpackage Processor languages
 * @author David Faurio / Lenamtl
 * @copyright 2004-2007 Helder Garcia, David Deutsch
 * @license http://www.gnu.org/copyleft/gpl.html. GNU Public License
 */

// Copyright (C) 2004-2007 Helder Garcia, David Deutsch
// All rights reserved.
// This source file is part of the Account Expiration Control Component, a Joomla

// custom Component By Helder Garcia and David Deutsch http://www.globalnerd.org
//
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
//
// The "GNU General Public License" (GPL) is available at
// http://www.gnu.org/copyleft/gpl.html.

// Dont allow direct linking
defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );

define( '_AEC_LANG_PROCESSOR', 1 );

// ################## new 0.12.4
	// paypal
define( '_AEC_PROC_INFO_PP_LNAME',			'PayPal' );
define( '_AEC_PROC_INFO_PP_STMNT',			'Make payments with PayPal - it\'s fast, free and secure!' );
	// paypal subscription
define( '_AEC_PROC_INFO_PPS_LNAME',			'PayPal Subscription' );
define( '_AEC_PROC_INFO_PPS_STMNT',			'Make payments with PayPal - it\'s fast, free and secure!' );
	// 2CheckOut
define( '_AEC_PROC_INFO_2CO_LNAME',			'2CheckOut' );
define( '_AEC_PROC_INFO_2CO_STMNT',			'Make payments with 2Checkout!' );
// END 0.12.4

define( '_DESCRIPTION_PAYPAL', 'PayPal permet d&acute;envoyer de l&acute;argent via courriel. PayPal est gratuit pour les consommateurs et fonctionne avec votre carte de cr&eacute;dit et compte ch&egrave;que.');
define( '_DESCRIPTION_PAYPAL_SUBSCRIPTION', 'PayPal Subscription is the Subscription Service that will <strong>automatically bill your account each subscription period</strong>. You can cancel a subscription any time you want from your PayPal account. PayPal is free for consumers and works seamlessly with your existing credit card and checking account.');
define( '_DESCRIPTION_AUTHORIZE', 'La passerelle de paiement permet aux entreprises sur Internet d&acute;accepter des paiements en ligne via carte de cr&eacute;dit et e-ch&egrave;que.');
define( '_DESCRIPTION_VIAKLIX', 'Procure un mode de paiement int&eacute;gr&eacute; via carte de cr&eacute;dit et carte de d&eacute;bit, conversion de ch&egrave;que &eacute;lectronique et applications reli&eacute;es..');
define( '_DESCRIPTION_ALLOPASS', 'AlloPass, est un leader Europ&eacute;en dans son domaine le syst&egrave;me de micropaiement et permet la facturation par t&eacute;l&eacute;phone, SMS et carte de cr&eacute;dit.');
define( '_DESCRIPTION_2CHECKOUT', 'Services instantan&eacute;s de traitement de carte de cr&eacute;dit pour les comptes marchand qui ont un commerce &eacute;lectronique.');
define( '_DESCRIPTION_EPSNETPAY', 'Les syst&egrave;mes de paiement eps est simple et s&eacute;curitaire.');
define( '_DESCRIPTION_ALERTPAY', 'Your money is safe with AlertPay\'s account safety policy. AlertPay is open to all businesses.');

// Generic Processor Names&Descs
define( '_CFG_PROCESSOR_TESTMODE_NAME', 'Test Mode?');
define( '_CFG_PROCESSOR_TESTMODE_DESC', 'Select Yes if you want to run this processor in test mode. Transactions will not be forwarded to the real processor, but will be either redirected to a testing environment or always return an approved result. If you do not know what this is, just leave it No.');
define( '_CFG_PROCESSOR_CURRENCY_NAME', 'Currency Selection');
define( '_CFG_PROCESSOR_CURRENCY_DESC', 'Select the currency that you want to use for this processor.');
define( '_CFG_PROCESSOR_NAME_NAME', 'Displayed Name');
define( '_CFG_PROCESSOR_NAME_DESC', 'Change how this Processor is called.');
define( '_CFG_PROCESSOR_DESC_NAME', 'Displayed Description');
define( '_CFG_PROCESSOR_DESC_DESC', 'Change the description of this Processor, which is for example shown on the NotAllowed page, Confirmation and Checkout.');
define( '_CFG_PROCESSOR_ITEM_NAME_NAME', 'Item Description');
define( '_CFG_PROCESSOR_ITEM_NAME_DESC', 'The Item Description transmitted to the processor.');
define( '_CFG_PROCESSOR_ITEM_NAME_DEFAULT',	'Subscription at %s - User: %s (%s)' );

// Parametres pour Paypal
define( '_CFG_PAYPAL_BUSINESS_NAME', 'Identifiant marchand :');
define( '_CFG_PAYPAL_BUSINESS_DESC', 'Votre identifiant marchand (courriel) sur PayPal.');
define( '_CFG_PAYPAL_CHECKBUSINESS_NAME', 'V&eacute;rifier ID Marchand:');
define( '_CFG_PAYPAL_CHECKBUSINESS_DESC', 'S&eacute;lectionner Oui pour activer la proc&eacute;dure de v&eacute;rification de s&eacute;curit&eacute; lors de la r&eacute;ception de la confirmation de paiement. Le champ ID receveur doit &ecirc;tre &eacute;gal &agrave; ID marchand de PayPal pour que le paiement soit accept&eacute;, si la v&eacute;rification est activ&eacute;e.');
define( '_CFG_PAYPAL_NO_SHIPPING_NAME', 'No Shipping Required:');
define( '_CFG_PAYPAL_NO_SHIPPING_DESC', 'Set this to NO if you want your customers to specify a shipping address - in case you offer a product that needs to be physically distributed');
define( '_CFG_PAYPAL_ALTIPNURL_NAME', 'Domaine Alernatif pour l&acute;avis IPN:');
define( '_CFG_PAYPAL_ALTIPNURL_DESC', 'Si vous utilisez un &eacute;quilibre de charge sur le serveur (changement entre les adresses IP), il se peut que que Paypal n&acute;aime pas cela et coupe la connexion lors de l&acute;envoi de l&acute;IPN. Pour contourner ce probl&egrave;me, vous pouvez par exemple cr&eacute;er un nouveau sousdomaine sur ce serveur et d&eacute;sactiver l&acute;&eacute;quilibre de charge. Entrer l&acute;adresse ici (Dans ce format "http://sousdomaine.domaine.com" - sans barre oblique &agrave; la fin) pour &ecirc;tre certain que Paypal envoi seulement le IPN &agrave; cette adresse. <strong>Si vous n&acute;&ecirc;tes pas certain de ce que cela signifie, laisser ce champ vide!</strong>');
define( '_CFG_PAYPAL_LC_NAME', 'Langue:');
define( '_CFG_PAYPAL_LC_DESC', 'S&eacute;lectionner une des langues disponibles pour le site de Paypal que les utilisateurs vont voir lors de leur paiement.');
define( '_CFG_PAYPAL_TAX_NAME', 'Tax:');
define( '_CFG_PAYPAL_TAX_DESC', 'Set the percentage that should be split to taxes. For example if you want 10% of 10$ to be tax - put in a 10. This will result in an amount of 9.09 and a tax amount of additional 0.91.');

// Parametres pour Paypal Subscriptions
define( '_CFG_PAYPAL_SUBSCRIPTION_BUSINESS_NAME', 'Identifiant marchand :');
define( '_CFG_PAYPAL_SUBSCRIPTION_BUSINESS_DESC', 'Votre identifiant marchand (courriel) sur PayPal.');
define( '_CFG_PAYPAL_SUBSCRIPTION_CHECKBUSINESS_NAME', 'V&eacute;rifier ID Marchand:');
define( '_CFG_PAYPAL_SUBSCRIPTION_CHECKBUSINESS_DESC', 'S&eacute;lectionner Oui pour activer la proc&eacute;dure de v&eacute;rification de s&eacute;curit&eacute; lors de la r&eacute;ception de la confirmation de paiement. Le champ ID receveur doit &ecirc;tre &eacute;gal &agrave; ID marchand de PayPal pour que le paiement soit accept&eacute;, si la v&eacute;rification est activ&eacute;e.');
define( '_CFG_PAYPAL_SUBSCRIPTION_NO_SHIPPING_NAME', 'No Shipping Required:');
define( '_CFG_PAYPAL_SUBSCRIPTION_NO_SHIPPING_DESC', 'Set this to NO if you want your customers to specify a shipping address - in case you offer a product that needs to be physically distributed');
define( '_CFG_PAYPAL_SUBSCRIPTION_ALTIPNURL_NAME', 'Domaine Alernatif pour l&acute;avis IPN:');
define( '_CFG_PAYPAL_SUBSCRIPTION_ALTIPNURL_DESC', 'Si vous utilisez un &eacute;quilibre de charge sur le serveur (changement entre les adresses IP), il se peut que que Paypal n&acute;aime pas cela et coupe la connexion lors de l&acute;envoi de l&acute;IPN. Pour contourner ce probl&egrave;me, vous pouvez par exemple cr&eacute;er un nouveau sousdomaine sur ce serveur et d&eacute;sactiver l&acute;&eacute;quilibre de charge. Entrer l&acute;adresse ici (Dans ce format "http://sousdomaine.domaine.com" - sans barre oblique &agrave; la fin) pour &ecirc;tre certain que Paypal envoi seulement le IPN &agrave; cette adresse. <strong>Si vous n&acute;&ecirc;tes pas certain de ce que cela signifie, laisser ce champ vide!</strong>');
define( '_CFG_PAYPAL_SUBSCRIPTION_LC_NAME', 'Langue:');
define( '_CFG_PAYPAL_SUBSCRIPTION_LC_DESC', 'S&eacute;lectionner une des langues disponibles pour le site de Paypal que les utilisateurs vont voir lors de leur paiement.');
define( '_CFG_PAYPAL_SUBSCRIPTION_TAX_NAME', 'Tax:');
define( '_CFG_PAYPAL_SUBSCRIPTION_TAX_DESC', 'Set the percentage that should be split to taxes. For example if you want 10% of 10$ to be tax - put in a 10. This will result in an amount of 9.09 and a tax amount of additional 0.91.');
define( '_PAYPAL_SUBSCRIPTION_CANCEL_INFO', 'If you want to change your subscription, you first have to cancel your current subscription in your PayPal account!');

// Parametres de Transfert
define( '_CFG_TRANSFER_TITLE', 'Transfer');
define( '_CFG_TRANSFER_SUBTITLE', 'Non-Automatic Payments.');
define( '_CFG_TRANSFER_ENABLE_NAME', 'Autoriser les paiements non automatique?');
define( '_CFG_TRANSFER_ENABLE_DESC', 'S&eacute;lectionner Oui si vous voulez fournir une option pour le paiement non automatique, comme un virement bancaire par exemple. Les utilisateurs qui s&acute;inscriront verront les instructions fournies par vous (champ ci-contre) leur expliquant comment payer leur abonnement. Cette option requiert une gestion non automatique, vous aurez donc &agrave; configurer la date d&acute;expiration manuellement depuis l&acute;interface d&acute;administration.');
define( '_CFG_TRANSFER_INFO_NAME', 'Information pour le paiement manuel :');
define( '_CFG_TRANSFER_INFO_DESC', 'Texte pr&eacute;sent&eacute; &agrave; l&acute;utilisateur apr&egrave;s son inscription initiale (utiliser les marqueurs HTML). Apr&egrave;s l&acute;inscription et &agrave; sa premire connexion, une expiration automatique est mise en place sur son compte (premier onglet Configuration). L&acute;utilisateur doit suivre vos instructions pour payer son abonnement. Vous devrez confirmer vous-m&ecirc;me son paiement et la nouvelle date d&acute;expiration de son compte.');

// Parametres Viaklix
define( '_CFG_VIAKLIX_ACCOUNTID_NAME', 'ID du Compte');
define( '_CFG_VIAKLIX_ACCOUNTID_DESC', 'Votre ID deu compte chez viaKLIX.');
define( '_CFG_VIAKLIX_USERID_NAME', 'ID Utilisateur');
define( '_CFG_VIAKLIX_USERID_DESC', 'Votre ID Utilisateur chez viaKLIX.');
define( '_CFG_VIAKLIX_PIN_NAME', 'NIP');
define( '_CFG_VIAKLIX_PIN_DESC', 'NIP sur le terminal.');

// Parametres Authorize.net
define( '_CFG_AUTHORIZE_LOGIN_NAME', 'API ID de connexion');
define( '_CFG_AUTHORIZE_LOGIN_DESC', 'Votre API ID de connexion sur Authorize.net.');
define( '_CFG_AUTHORIZE_TRANSACTION_KEY_NAME', 'Cl&eacute; de transaction');
define( '_CFG_AUTHORIZE_TRANSACTION_KEY_DESC', 'Votre cl&eacute; de transaction sur Authorize.net.');

// Parametres Allopass
define( '_CFG_ALLOPASS_SITEID_NAME', 'SITE_ID');
define( '_CFG_ALLOPASS_SITEID_DESC', 'Votre SITE_ID sur AlloPass.');
define( '_CFG_ALLOPASS_DOCID_NAME', 'DOC_ID');
define( '_CFG_ALLOPASS_DOCID_DESC', 'Your DOC_ID sur AlloPass.');
define( '_CFG_ALLOPASS_AUTH_NAME', 'AUTH');
define( '_CFG_ALLOPASS_AUTH_DESC', 'AUTH sur AlloPass.');

// Parametres 2Checkout
define( '_CFG_2CHECKOUT_SID_NAME', 'SID');
define( '_CFG_2CHECKOUT_SID_DESC', 'Votre num&eacute;ro de compte 2checkout.');
define( '_CFG_2CHECKOUT_SECRET_WORD_NAME', 'Mot Secret');
define( '_CFG_2CHECKOUT_SECRET_WORD_DESC', 'M&ecirc;me mot secret configur&eacute; par vous m&ecirc;me sur la page Look and Feel.');
define( '_CFG_2CHECKOUT_INFO_NAME', 'NOTE IMPORTANTE!');
define( '_CFG_2CHECKOUT_INFO_DESC', 'Sur la page d&acute;accueil de votre compte 2Checkout, trouver la section "Helpful Links", cliquer sur le lien "Look and Feel". Configurer le champ "Approved URL" avec l&acute;URL "http://votresite.com/index.php?option=com_acctexp&task=2conotification". ' . 'Remplacer "votresite.com" avec votre propre nom de domaine.');
define( '_CFG_2CHECKOUT_ALT2COURL_NAME', 'Url Alternative');
define( '_CFG_2CHECKOUT_ALT2COURL_DESC', '&Agrave; essayer si vous rencontrer des erreurs de param&egrave;tres.');

// Parametres WorldPay
define( '_CFG_WORLDPAY_INSTID_NAME', 'instId');
define( '_CFG_WORLDPAY_INSTID_DESC', 'Votre ID Installation WorldPay.');

// Parametres epsNetpay
define( '_CFG_EPSNETPAY_MERCHANTID_NAME', 'ID Marchand');
define( '_CFG_EPSNETPAY_MERCHANTID_DESC', 'Votre num&eacute;ro de compte epsNetpay.');
define( '_CFG_EPSNETPAY_MERCHANTPIN_NAME', 'NIP Marchand');
define( '_CFG_EPSNETPAY_MERCHANTPIN_DESC', 'Votre NIP Marchand.');
define( '_CFG_EPSNETPAY_ACTIVATE_NAME', 'Activate');
define( '_CFG_EPSNETPAY_ACTIVATE_DESC', 'Offer this Bank.');
define( '_CFG_EPSNETPAY_ACCEPTVOK_NAME', 'Accept VOK');
define( '_CFG_EPSNETPAY_ACCEPTVOK_DESC', 'It might be that due to the account type you have, you will never get an "OK" response, but always "VOK". If that is the case, please switch this on.');

// Parametre Paysignet
define( '_CFG_PAYSIGNET_MERCHANT_NAME', 'Marchand');
define( '_CFG_PAYSIGNET_MERCHANT_DESC', 'Votre nom Marchand.');

// AlertPay Settings
define( '_CFG_ALERTPAY_MERCHANT_NAME', 'Merchant');
define( '_CFG_ALERTPAY_MERCHANT_DESC', 'Your Merchant Name.');
define( '_CFG_ALERTPAY_SECURITYCODE_NAME', 'Security Code');
define( '_CFG_ALERTPAY_SECURITYCODE_DESC', 'Your Security Code.');
?>