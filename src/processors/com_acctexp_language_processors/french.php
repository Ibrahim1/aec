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
	// alertpay
define( '_AEC_PROC_INFO_AP_LNAME',			'AlertPay' );
define( '_AEC_PROC_INFO_AP_STMNT',			'Payments with AlertPay' );

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

// Generic Credit Card Form
define( '_AEC_CCFORM_CARDHOLDER_NAME', 'Cardholder\'s Name');
define( '_AEC_CCFORM_CARDHOLDER_DESC', 'The name of the credit card holder');
define( '_AEC_CCFORM_CARDNUMBER_NAME', 'Card Number');
define( '_AEC_CCFORM_CARDNUMBER_DESC', 'The number of your credit card');
define( '_AEC_CCFORM_EXPIRATIONYEAR_NAME', 'Expiration Year');
define( '_AEC_CCFORM_EXPIRATIONYEAR_DESC', 'The Year your card will expire');
define( '_AEC_CCFORM_EXPIRATIONMONTH_NAME', 'Expiration Month');
define( '_AEC_CCFORM_EXPIRATIONMONTH_DESC', 'The Month your card will expire');

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
define( '_CFG_PAYPAL_ACCEPTPENDINGECHECK_NAME', 'Accept Pending eCheck:');
define( '_CFG_PAYPAL_ACCEPTPENDINGECHECK_DESC', 'Accept Pending eChecks which usually take 4 days to clear. Set this to No to prevent eCheck fraud.');

define( '_CFG_PAYPAL_CBT_NAME', 'Continue Button');
define( '_CFG_PAYPAL_CBT_DESC', 'Sets the text for the Continue button on the PayPal "Payment Complete" page.');
define( '_CFG_PAYPAL_CN_NAME', 'Note Label');
define( '_CFG_PAYPAL_CN_DESC', 'The label above the note field.');
define( '_CFG_PAYPAL_CPP_HEADER_IMAGE_NAME', 'Header Image');
define( '_CFG_PAYPAL_CPP_HEADER_IMAGE_DESC', 'URL for the image at the top left of the payment page (the maximum image size being 750x90 pixels)');
define( '_CFG_PAYPAL_CPP_HEADERBACK_COLOR_NAME', 'Headerback Color');
define( '_CFG_PAYPAL_CPP_HEADERBACK_COLOR_DESC', 'Background color for the payment page header (6 character HTML hexadecimal color code in ASCII)');
define( '_CFG_PAYPAL_CPP_HEADERBORDER_COLOR_NAME', 'Headerborder Color');
define( '_CFG_PAYPAL_CPP_HEADERBORDER_COLOR_DESC', 'Border color for the payment page header (6 character HTML hexadecimal color code in ASCII)');
define( '_CFG_PAYPAL_CPP_PAYFLOW_COLOR_NAME', 'Payflow Color');
define( '_CFG_PAYPAL_CPP_PAYFLOW_COLOR_DESC', 'Background color for the payment page below the header (6 character HTML hexadecimal color code in ASCII)');
define( '_CFG_PAYPAL_CS_NAME', 'Background Tint');
define( '_CFG_PAYPAL_CS_DESC', 'The default - "No" - leaves the overall background color at white, setting it to "Yes" will change it to black');
define( '_CFG_PAYPAL_IMAGE_URL_NAME', 'Logo');
define( '_CFG_PAYPAL_IMAGE_URL_DESC', 'URL of the image displayed as your logo in the upperleft corner of PayPals pages (150x50 pixels)');
define( '_CFG_PAYPAL_PAGE_STYLE_NAME', 'Page Style');
define( '_CFG_PAYPAL_PAGE_STYLE_DESC', 'Sets the custom payment page style for payment pages. Reserved: "primary" - Always use the page style set as primary, "paypal" - Use the default PayPal style. Any other name has to refer to the page style you have defined in the PayPal Backend (alphanumeric ASCII lower 7-bit characters only, no underscore nor spaces)');

// Parametres pour Paypal Subscriptions
define( '_CFG_PAYPAL_SUBSCRIPTION_BUSINESS_NAME', _CFG_PAYPAL_BUSINESS_NAME);
define( '_CFG_PAYPAL_SUBSCRIPTION_BUSINESS_DESC', _CFG_PAYPAL_BUSINESS_DESC);
define( '_CFG_PAYPAL_SUBSCRIPTION_CHECKBUSINESS_NAME', _CFG_PAYPAL_CHECKBUSINESS_NAME);
define( '_CFG_PAYPAL_SUBSCRIPTION_CHECKBUSINESS_DESC', _CFG_PAYPAL_CHECKBUSINESS_DESC);
define( '_CFG_PAYPAL_SUBSCRIPTION_NO_SHIPPING_NAME', _CFG_PAYPAL_NO_SHIPPING_NAME);
define( '_CFG_PAYPAL_SUBSCRIPTION_NO_SHIPPING_DESC', _CFG_PAYPAL_NO_SHIPPING_DESC);
define( '_CFG_PAYPAL_SUBSCRIPTION_ALTIPNURL_NAME', _CFG_PAYPAL_ALTIPNURL_NAME);
define( '_CFG_PAYPAL_SUBSCRIPTION_ALTIPNURL_DESC', _CFG_PAYPAL_ALTIPNURL_DESC);
define( '_CFG_PAYPAL_SUBSCRIPTION_LC_NAME', _CFG_PAYPAL_LC_NAME);
define( '_CFG_PAYPAL_SUBSCRIPTION_LC_DESC', _CFG_PAYPAL_LC_DESC);
define( '_CFG_PAYPAL_SUBSCRIPTION_TAX_NAME', _CFG_PAYPAL_TAX_NAME);
define( '_CFG_PAYPAL_SUBSCRIPTION_TAX_DESC', _CFG_PAYPAL_TAX_DESC);
define( '_PAYPAL_SUBSCRIPTION_CANCEL_INFO', 'If you want to change your subscription, you first have to cancel your current subscription in your PayPal account!');
define( '_CFG_PAYPAL_SUBSCRIPTION_ACCEPTPENDINGECHECK_NAME', _CFG_PAYPAL_ACCEPTPENDINGECHECK_NAME);
define( '_CFG_PAYPAL_SUBSCRIPTION_ACCEPTPENDINGECHECK_DESC', _CFG_PAYPAL_ACCEPTPENDINGECHECK_DESC);

define( '_CFG_PAYPAL_SUBSCRIPTION_CBT_NAME', _CFG_PAYPAL_CBT_NAME);
define( '_CFG_PAYPAL_SUBSCRIPTION_CBT_DESC', _CFG_PAYPAL_CBT_DESC);
define( '_CFG_PAYPAL_SUBSCRIPTION_CN_NAME', _CFG_PAYPAL_CN_NAME);
define( '_CFG_PAYPAL_SUBSCRIPTION_CN_DESC', _CFG_PAYPAL_CN_DESC);
define( '_CFG_PAYPAL_SUBSCRIPTION_CPP_HEADER_IMAGE_NAME', _CFG_PAYPAL_CPP_HEADER_IMAGE_NAME);
define( '_CFG_PAYPAL_SUBSCRIPTION_CPP_HEADER_IMAGE_DESC', _CFG_PAYPAL_CPP_HEADER_IMAGE_DESC);
define( '_CFG_PAYPAL_SUBSCRIPTION_CPP_HEADERBACK_COLOR_NAME', _CFG_PAYPAL_CPP_HEADERBACK_COLOR_NAME);
define( '_CFG_PAYPAL_SUBSCRIPTION_CPP_HEADERBACK_COLOR_DESC', _CFG_PAYPAL_CPP_HEADERBACK_COLOR_DESC);
define( '_CFG_PAYPAL_SUBSCRIPTION_CPP_HEADERBORDER_COLOR_NAME', _CFG_PAYPAL_CPP_HEADERBORDER_COLOR_NAME);
define( '_CFG_PAYPAL_SUBSCRIPTION_CPP_HEADERBORDER_COLOR_DESC', _CFG_PAYPAL_CPP_HEADERBORDER_COLOR_DESC);
define( '_CFG_PAYPAL_SUBSCRIPTION_CPP_PAYFLOW_COLOR_NAME', _CFG_PAYPAL_CPP_PAYFLOW_COLOR_NAME);
define( '_CFG_PAYPAL_SUBSCRIPTION_CPP_PAYFLOW_COLOR_DESC', _CFG_PAYPAL_CPP_PAYFLOW_COLOR_DESC);
define( '_CFG_PAYPAL_SUBSCRIPTION_CS_NAME', _CFG_PAYPAL_CS_NAME);
define( '_CFG_PAYPAL_SUBSCRIPTION_CS_DESC', _CFG_PAYPAL_CS_DESC);
define( '_CFG_PAYPAL_SUBSCRIPTION_IMAGE_URL_NAME', _CFG_PAYPAL_IMAGE_URL_NAME);
define( '_CFG_PAYPAL_SUBSCRIPTION_IMAGE_URL_DESC', _CFG_PAYPAL_IMAGE_URL_DESC);
define( '_CFG_PAYPAL_SUBSCRIPTION_PAGE_STYLE_NAME', _CFG_PAYPAL_PAGE_STYLE_NAME);
define( '_CFG_PAYPAL_SUBSCRIPTION_PAGE_STYLE_DESC', _CFG_PAYPAL_PAGE_STYLE_DESC);

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
define( '_CFG_AUTHORIZE_TIMESTAMP_OFFSET_NAME', 'Timestamp offest');
define( '_CFG_AUTHORIZE_TIMESTAMP_OFFSET_DESC', 'If you get an Error 97 when trying to create a transaction, please <a href="http://developer.authorize.net/tools/responsecode97/">take a look at this</a>. It might be that you need to set a timestamp offset here.');
define( '_CFG_AUTHORIZE_X_LOGO_URL_NAME', 'Logo URL');
define( '_CFG_AUTHORIZE_X_LOGO_URL_DESC', 'This field is ideal for displaying a merchant logo on a page. The target of this URL will be displayed on the header of the Payment Form and Receipt Page.');
define( '_CFG_AUTHORIZE_X_BACKGROUND_URL_NAME', 'Background URL');
define( '_CFG_AUTHORIZE_X_BACKGROUND_URL_DESC', 'This field will allow the merchant to customize the background image of the Payment Form and Receipt Page. The target of the specified URL will be displayed as the background.');
define( '_CFG_AUTHORIZE_X_COLOR_BACKGROUND_NAME', 'Background Color');
define( '_CFG_AUTHORIZE_X_COLOR_BACKGROUND_DESC', 'Value in this field will set the background color for the Payment Form and Receipt Page.');
define( '_CFG_AUTHORIZE_X_COLOR_LINK_NAME', 'Color Link');
define( '_CFG_AUTHORIZE_X_COLOR_LINK_DESC', 'This field allows the color of the HTML links for the Payment Form and Receipt Page to be set to the value submitted in this field.');
define( '_CFG_AUTHORIZE_X_COLOR_TEXT_NAME', 'Color Text');
define( '_CFG_AUTHORIZE_X_COLOR_TEXT_DESC', 'This field allows the color of the text on the Payment Form and the Receipt Page to be set to the value submitted in this field.');
define( '_CFG_AUTHORIZE_X_HEADER_HTML_RECEIPT_NAME', 'Header Receipt Page');
define( '_CFG_AUTHORIZE_X_HEADER_HTML_RECEIPT_DESC', 'The text contained in this field will be displayed at the top of the Receipt Page.');
define( '_CFG_AUTHORIZE_X_FOOTER_HTML_RECEIPT_NAME', 'Footer Receipt Page');
define( '_CFG_AUTHORIZE_X_FOOTER_HTML_RECEIPT_DESC', 'The text contained in this field will be displayed at the bottom of the Receipt Page.');

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

// WorldPay Settings
define( '_CFG_WORLDPAY_LONGNAME',		'WorldPay' );
define( '_CFG_WORLDPAY_STATEMENT',		'Payments with WorldPay' );
define( '_CFG_WORLDPAY_DESCRIPTION',	'Accept payments on the internet, by phone, fax or mail. Credit and debit cards, bank transfers and instalments. In any language and most currencies' );
define( '_CFG_WORLDPAY_INSTID_NAME', 	'instId');
define( '_CFG_WORLDPAY_INSTID_DESC', 	'Your WorldPay Installation Id.');
define( '_CFG_WORLDPAY_CALLBACKPW_NAME', 	'Callback Password');
define( '_CFG_WORLDPAY_CALLBACKPW_DESC', 	'Please set a Callback Password in your Worldpay Account and enter it here once again. With this, the payment notifications can .');

// WorldPay Futurepay Settings
define( '_CFG_WORLDPAY_FUTUREPAY_LONGNAME',		'WorldPay Futurepay' );
define( '_CFG_WORLDPAY_FUTUREPAY_STATEMENT',	'Recurring Payments with WorldPay' );
define( '_CFG_WORLDPAY_FUTUREPAY_DESCRIPTION',	'Accepts payments on the internet, by phone, fax or mail. Credit and debit cards, bank transfers and instalments. In any language and most currencies' );
define( '_CFG_WORLDPAY_FUTUREPAY_INSTID_NAME', 	'instId');
define( '_CFG_WORLDPAY_FUTUREPAY_INSTID_DESC', 	'Your WorldPay Installation Id.');
define( '_CFG_WORLDPAY_FUTUREPAY_CALLBACKPW_NAME', 	'Callback Password');
define( '_CFG_WORLDPAY_FUTUREPAY_CALLBACKPW_DESC', 	'Please set a Callback Password in your Worldpay Account and enter it here once again. With this, the payment notifications can .');

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

// eWay Settings
define( '_CFG_EWAY_LONGNAME', 'eWay');
define( '_CFG_EWAY_STATEMENT', 'Make payments with eWAY Shared Payment Solution!');
define( '_CFG_EWAY_DESCRIPTION', 'eWAY is the easiest and most affordable payment gateway in Australia. Process credit card payments via eWAY\'s own secure Shared Payment Page in real-time.');
define( '_CFG_EWAY_CUSTID_NAME', 'Customer ID');
define( '_CFG_EWAY_CUSTID_DESC', 'Your Customer ID.');
define( '_CFG_EWAY_AUTOREDIRECT_NAME', 'Autoredirect');
define( '_CFG_EWAY_AUTOREDIRECT_DESC', 'Automatic Redirect for eWay Transaction');
define( '_CFG_EWAY_SITETITLE_NAME', 'Site Title');
define( '_CFG_EWAY_SITETITLE_DESC', 'The Site Title of the eWay Transaction');

// eWayXML Settings
define( '_CFG_EWAYXML_LONGNAME', 'eWayXML');
define( '_CFG_EWAYXML_STATEMENT', 'Make payments with eWAY Shared Payment Solution!');
define( '_CFG_EWAYXML_DESCRIPTION', 'eWAY is the easiest and most affordable payment gateway in Australia. Process credit card payments via eWAY\'s own secure Shared Payment Page in real-time.');
define( '_CFG_EWAYXML_CUSTID_NAME', 'Customer ID');
define( '_CFG_EWAYXML_CUSTID_DESC', 'Your Customer ID.');
define( '_CFG_EWAYXML_AUTOREDIRECT_NAME', 'Autoredirect');
define( '_CFG_EWAYXML_AUTOREDIRECT_DESC', 'Automatic Redirect for eWay Transaction');
define( '_CFG_EWAYXML_SITETITLE_NAME', 'Site Title');
define( '_CFG_EWAYXML_SITETITLE_DESC', 'The Site Title of the eWay Transaction');

// MoneyProxy Settings
define( '_CFG_MONEYPROXY_LONGNAME', 'MoneyProxy');
define( '_CFG_MONEYPROXY_STATEMENT', 'Make Payments in different digital currencies with Money Proxy!');
define( '_CFG_MONEYPROXY_DESCRIPTION', 'Accept payments on a website in different digital currencies with a single merchant account.');
define( '_CFG_MONEYPROXY_MERCHANT_ID_NAME', 'Merchant ID');
define( '_CFG_MONEYPROXY_MERCHANT_ID_DESC', 'Your merchant identifier at MoneyProxy.');
define( '_CFG_MONEYPROXY_FORCE_CLIENT_RECEIPT_NAME', 'Force Receipt');
define( '_CFG_MONEYPROXY_FORCE_CLIENT_RECEIPT_DESC', 'By setting this parameter to "Yes", it forces Money Proxy to ask an e-mail address where to send a receipt of the payment. By default, the customer can skip the receipt without entering any e-mail address.');
define( '_CFG_MONEYPROXY_SECRET_KEY_NAME', 'Site Title');
define( '_CFG_MONEYPROXY_SECRET_KEY_DESC', 'Your secret key at MoneyProxy.');
define( '_CFG_MONEYPROXY_SUGGESTEDMEMO_NAME', 'Suggested Memo');
define( '_CFG_MONEYPROXY_SUGGESTEDMEMO_DESC', 'This parameter is used to pre-fill the memo field for many payment system. Unfortunately, it is possible that some payment systems do not support this feature. Maximum of 40 characters.');
define( '_CFG_MONEYPROXY_PAYMENT_ID_NAME', 'Payment ID');
define( '_CFG_MONEYPROXY_PAYMENT_ID_DESC', 'The merchant can use this field to track the payment when the status URL is called. It can be up to 10 digits with only letters and numbers (0-9a-zA-Z). You can use Rewrite tags here.');

// Offline Payment
define( '_CFG_OFFLINE_PAYMENT_LONGNAME', 'Offline Payment');
define( '_CFG_OFFLINE_PAYMENT_STATEMENT', 'You can use this option to not pay through the Internet');
define( '_CFG_OFFLINE_PAYMENT_DESCRIPTION', 'You can use this option to not pay through the Internet');
define( '_CFG_OFFLINE_PAYMENT_INFO_NAME', 'Info');
define( '_CFG_OFFLINE_PAYMENT_INFO_DESC', 'The Info that will be displayed to the user on checkout');
define( '_CFG_OFFLINE_PAYMENT_WAITINGPLAN_NAME', 'Waiting Plan');
define( '_CFG_OFFLINE_PAYMENT_WAITINGPLAN_DESC', 'You can assign a user to this plan while he or she waits for the payment to arrive');

// Verotel
define( '_AEC_VEROTEL_LONGNAME', 'Verotel');
define( '_AEC_VEROTEL_STATEMENT', 'Use Verotel: Putting Trust in Global Payments');
define( '_AEC_VEROTEL_DESCRIPTION', 'Verotel offers a range of billing methods for your website, including VISA/MasterCard/JCB, Chinese debit, Direct Debit in European countries, pay per phonecall, pay per minute, SMS billing and more!');
define( '_CFG_VEROTEL_MERCHANTID_NAME', 'Merchant ID');
define( '_CFG_VEROTEL_MERCHANTID_DESC', 'Your merchant identifier at Verotel.');
define( '_CFG_VEROTEL_SITEID_NAME', 'Site ID');
define( '_CFG_VEROTEL_SITEID_DESC', 'Your site identifier for this website.');
define( '_CFG_VEROTEL_SECRETCODE_NAME', 'Secret Code');
define( '_CFG_VEROTEL_SECRETCODE_DESC', 'Your secret Verotel code.');
define( '_CFG_VEROTEL_PLAN_PARAMS_VEROTEL_PRODUCT_NAME', 'Product Name');
define( '_CFG_VEROTEL_PLAN_PARAMS_VEROTEL_PRODUCT_DESC', 'The Product Name identifying this plan to Verotel');

// Cybermut
define( '_CFG_CYBERMUT_LONGNAME', 'Cybermut');
define( '_CFG_CYBERMUT_STATEMENT', 'Cybermut - Le groupe Cr&eacute;dit Mutuel');
define( '_CFG_CYBERMUT_DESCRIPTION', 'Cybermut - Le groupe Cr&eacute;dit Mutuel');
define( '_CFG_CYBERMUT_TPE_NAME', 'TPE');
define( '_CFG_CYBERMUT_TPE_DESC', 'No TPE &racute; 7 chiffres, fourni par la banque');
define( '_CFG_CYBERMUT_VER_NAME', 'Version');
define( '_CFG_CYBERMUT_VER_DESC', 'The Protocol Version - leave at "1.2open" if you don\'t know what this is');
define( '_CFG_CYBERMUT_SOC_NAME', 'Code Soci&eacute;t&eacute;');
define( '_CFG_CYBERMUT_SOC_DESC', 'Code Soci&eacute;t&eacute;, fourni par la banque');
define( '_CFG_CYBERMUT_PASS_NAME', 'pass');
define( '_CFG_CYBERMUT_PASS_DESC', 'Valeur de la variable pass');
define( '_CFG_CYBERMUT_KEY_NAME', 'Cl&eacute;');
define( '_CFG_CYBERMUT_KEY_DESC', 'cl&eacute; info');
define( '_CFG_CYBERMUT_SERVER_NAME', 'Serveur bancair');
define( '_CFG_CYBERMUT_SERVER_DESC', 'Choissisez votre banque');
define( '_CFG_CYBERMUT_LANGUAGE_NAME', 'Language:');
define( '_CFG_CYBERMUT_LANGUAGE_DESC', 'Select one of the possible language settings for the paypal site that your user will see when issuing a payment.');

// Authorize.net ARB Settings
define( '_CFG_AUTHORIZE_ARB_LONGNAME', 'Authorize.net ARB');
define( '_CFG_AUTHORIZE_ARB_STATEMENT', 'Make recurring payments with Authorize.net');
define( '_CFG_AUTHORIZE_ARB_DESCRIPTION', 'Make recurring payments with Authorize.net');
define( '_CFG_AUTHORIZE_ARB_LOGIN_NAME', 'API Login ID');
define( '_CFG_AUTHORIZE_ARB_LOGIN_DESC', 'Your API Login ID on Authorize.net.');
define( '_CFG_AUTHORIZE_ARB_TRANSACTION_KEY_NAME', 'Transaction Key');
define( '_CFG_AUTHORIZE_ARB_TRANSACTION_KEY_DESC', 'Your Transaction Key on Authorize.net.');
define( '_CFG_AUTHORIZE_ARB_TOTALOCCURRENCES_NAME', 'Total Occurances');
define( '_CFG_AUTHORIZE_ARB_TOTALOCCURRENCES_DESC', 'Authorize.net requires that you set the total amount of occurances of a payment. Make sure the total lifespan of a subscription does not exceed three years');
define( '_CFG_AUTHORIZE_ARB_TRIALOCCURRENCES_NAME', 'Trial Occurances');
define( '_CFG_AUTHORIZE_ARB_TRIALOCCURRENCES_DESC', 'Specify the amount of trial periods that are granted to the user. This amount will be substracted from the total occurances.');
define( '_AEC_AUTHORIZE_ARB_PARAMS_BILLFIRSTNAME_NAME', 'First Name');
define( '_AEC_AUTHORIZE_ARB_PARAMS_BILLFIRSTNAME_DESC', 'The First Name this should be billed to.');
define( '_AEC_AUTHORIZE_ARB_PARAMS_BILLLASTNAME_NAME', 'Last Name');
define( '_AEC_AUTHORIZE_ARB_PARAMS_BILLLASTNAME_DESC', 'The Last Name this should be billed to.');

// CCBill
define( '_CFG_CCBILL_LONGNAME', 'CCBill');
define( '_CFG_CCBILL_STATEMENT', 'Make payments with CCBill!');
define( '_CFG_CCBILL_DESCRIPTION', 'CCBill');
define( '_CFG_CCBILL_CLIENTACCNUM_NAME', 'Client Account');
define( '_CFG_CCBILL_CLIENTACCNUM_DESC', 'Your CCBill Client Account Number');
define( '_CFG_CCBILL_CLIENTSUBACC_NAME', 'Client SubAccount');
define( '_CFG_CCBILL_CLIENTSUBACC_DESC', 'Your CCBill Client Sub Account Number');
define( '_CFG_CCBILL_SECRETWORD_NAME', 'Secret Word');
define( '_CFG_CCBILL_SECRETWORD_DESC', 'Your secret word used to encrypt and protect transactions');
define( '_CFG_CCBILL_FORMNAME_NAME', 'Form ID');
define( '_CFG_CCBILL_FORMNAME_DESC', 'The CCBill layout you wish to use (look at the HTML form downloaded from CCBILL)');
define( '_CFG_CCBILL_INFO_NAME', 'Postback URL');
define( '_CFG_CCBILL_INFO_DESC', 'You need to remember to set the \'postback\' url in the CCBILL control panel... for both approves and declines this should be...<br />http://[YOUR JOOMLA LOCATION]/index.php?option=com_acctexp&task=ccbillnotification<br />Thats it.');
define( '_CFG_CCBILL_PLAN_PARAMS_ALLOWEDTYPES_NAME', 'Allowed types');
define( '_CFG_CCBILL_PLAN_PARAMS_ALLOWEDTYPES_DESC', 'insert the payment options that the user is supposed to have after being led on to CCBill (refer to your CCBill account for the product IDs you have set up)');

?>