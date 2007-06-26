<?php
//
// Copyright (C) 2004-2007 Helder Garcia, David Deutsch
// All rights reserved.
// This source file is part of the Account Expiration Control Component, a  Joomla
// custom Component By Helder Garcia and David Deutsch - http://www.globalnerd.org
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
//
// Dont allow direct linking
defined( '_VALID_MOS' ) or die( '不容许直接访问这儿.' );

DEFINE ('_DESCRIPTION_PAYPAL', 'PayPal可以通过电子邮件发送一些钱给任何人.PayPal是对用户来说是非常自由的,可以跟你的信用卡和常用帐户完美的结合到一起.');
DEFINE ('_DESCRIPTION_PAYPAL_SUBSCRIPTION', 'PayPal Subscription is the Subscription Service that will <strong>automatically bill your account each subscription period</strong>. You can cancel a subscription any time you want from your PayPal account. PayPal is free for consumers and works seamlessly with your existing credit card and checking account.');
DEFINE ('_DESCRIPTION_AUTHORIZE', '启用支付网关后,网店店主可以通过信用卡和电子银行支付.');
DEFINE ('_DESCRIPTION_VIAKLIX', '提供综合的信用卡和借记卡支付处理,电子转帐和相关应用软件..');
DEFINE ('_DESCRIPTION_ALLOPASS', 'AlloPass是欧洲最主流的网络支付平台,他容许通过电话,短信和信用卡付账.');
DEFINE ('_DESCRIPTION_2CHECKOUT', '即时的信用卡处理服务,方便做互联网生意的商人.');
DEFINE ('_DESCRIPTION_EPSNETPAY', 'Der eps ist das einfache, sichere und kostenlose Zahlungssystem der &ouml;sterreichischen Banken f&uuml;r Eink&auml;ufe im Internet.');
DEFINE ('_DESCRIPTION_ALERTPAY', 'Your money is safe with AlertPay\'s account safety policy. AlertPay is open to all businesses.');

// Generic Processor Names&Descs
DEFINE ('_CFG_PROCESSOR_TESTMODE_NAME', 'Test Mode?');
DEFINE ('_CFG_PROCESSOR_TESTMODE_DESC', 'Select Yes if you want to run this processor in test mode. Transactions will not be forwarded to the real processor, but will be either redirected to a testing environment or always return an approved result. If you do not know what this is, just leave it No.');
DEFINE ('_CFG_PROCESSOR_CURRENCY_NAME', 'Currency Selection');
DEFINE ('_CFG_PROCESSOR_CURRENCY_DESC', 'Select the currency that you want to use for this processor.');
DEFINE ('_CFG_PROCESSOR_NAME_NAME', 'Displayed Name');
DEFINE ('_CFG_PROCESSOR_NAME_DESC', 'Change how this Processor is called.');
DEFINE ('_CFG_PROCESSOR_DESC_NAME', 'Displayed Description');
DEFINE ('_CFG_PROCESSOR_DESC_DESC', 'Change the description of this Processor, which is for example shown on the NotAllowed page, Confirmation and Checkout.');
DEFINE ('_CFG_PROCESSOR_ITEM_NAME_NAME', 'Item Description');
DEFINE ('_CFG_PROCESSOR_ITEM_NAME_DESC', 'The Item Description transmitted to the processor.');

// Paypal Settings
DEFINE ('_CFG_PAYPAL_BUSINESS_NAME', '商业ID:');
DEFINE ('_CFG_PAYPAL_BUSINESS_DESC', '你在PayPal上的商业ID(email).');
DEFINE ('_CFG_PAYPAL_CHECKBUSINESS_NAME', '核对商业ID:');
DEFINE ('_CFG_PAYPAL_CHECKBUSINESS_DESC', '选择是将在收到支付确认时使用一个安全的检测程序.如果检测被启用,接收者的ID和PayPal交易ID必须相同,支付才会被接受.');
DEFINE ('_CFG_PAYPAL_ALTIPNURL_NAME', 'Alternate IPN Notification Domain:');
DEFINE ('_CFG_PAYPAL_ALTIPNURL_DESC', 'If you use server workload balancing (switching between IP adresses), it might be that Paypal dislikes this and breaks the connection when trying to send an IPN. To work around this, you can for example create a new subdomain on this server and disable the loadbalancing for this. Putting this address in here (In the form "http://subdomain.domain.com" - no trailing slash and whatever you want for subdomain and domain) will make sure that Paypal sends only the IPN back to this Address. <strong>If you are not sure what this means, leave it completely blank!</strong>');
DEFINE ('_CFG_PAYPAL_LC_NAME', 'Language:');
DEFINE ('_CFG_PAYPAL_LC_DESC', 'Select one of the possible language settings for the paypal site that your user will see when issuing a payment.');
DEFINE ('_CFG_PAYPAL_TAX_NAME', 'Tax:');
DEFINE ('_CFG_PAYPAL_TAX_DESC', 'Set the percentage that should be split to taxes. For example if you want 10% of 10$ to be tax - put in a 10. This will result in an amount of 9.09 and a tax amount of additional 0.91.');

// Paypal Subscriptions Settings
DEFINE ('_CFG_PAYPAL_SUBSCRIPTION_BUSINESS_NAME', '商业ID:');
DEFINE ('_CFG_PAYPAL_SUBSCRIPTION_BUSINESS_DESC', '你在PayPal上的商业ID(email).');
DEFINE ('_CFG_PAYPAL_SUBSCRIPTION_CHECKBUSINESS_NAME', '核对商业ID:');
DEFINE ('_CFG_PAYPAL_SUBSCRIPTION_CHECKBUSINESS_DESC', '选择是将在收到支付确认时使用一个安全的检测程序.如果检测被启用,接收者的ID和PayPal交易ID必须相同,支付才会被接受.');
DEFINE ('_CFG_PAYPAL_SUBSCRIPTION_ALTIPNURL_NAME', 'Alternate IPN Notification Domain:');
DEFINE ('_CFG_PAYPAL_SUBSCRIPTION_ALTIPNURL_DESC', 'If you use server workload balancing (switching between IP adresses), it might be that Paypal dislikes this and breaks the connection when trying to send an IPN. To work around this, you can for example create a new subdomain on this server and disable the loadbalancing for this. Putting this address in here (In the form "http://subdomain.domain.com" - no trailing slash and whatever you want for subdomain and domain) will make sure that Paypal sends only the IPN back to this Address. <strong>If you are not sure what this means, leave it completely blank!</strong>');
DEFINE ('_CFG_PAYPAL_SUBSCRIPTION_LC_NAME', 'Language:');
DEFINE ('_CFG_PAYPAL_SUBSCRIPTION_LC_DESC', 'Select one of the possible language settings for the paypal site that your user will see when issuing a payment.');
DEFINE ('_CFG_PAYPAL_SUBSCRIPTION_TAX_NAME', 'Tax:');
DEFINE ('_CFG_PAYPAL_SUBSCRIPTION_TAX_DESC', 'Set the percentage that should be split to taxes. For example if you want 10% of 10$ to be tax - put in a 10. This will result in an amount of 9.09 and a tax amount of additional 0.91.');
DEFINE ('_PAYPAL_SUBSCRIPTION_CANCEL_INFO', 'If you want to change your subscription, you first have to cancel your current subscription in your PayPal account!');

// Transfer Settings
DEFINE ('_CFG_TRANSFER_TITLE', 'Transfer');
DEFINE ('_CFG_TRANSFER_SUBTITLE', 'Non-Automatic Payments.');
DEFINE ('_CFG_TRANSFER_ENABLE_NAME', '不容许自动支付?');
DEFINE ('_CFG_TRANSFER_ENABLE_DESC', '如果你想提供一个不自动支付(类似银行转账)的选项,请选择是.注册用户时看为您提供的说明(下面的部分)如何支付订金.这个选项没有自动处理,所以你需要从配置界面手动配置一下过期日期.');
DEFINE ('_CFG_TRANSFER_INFO_NAME', '人工支付信息(选择一个):');
DEFINE ('_CFG_TRANSFER_INFO_DESC', 'Text to be presented to the user after his initial registration (use HTML tags). After registration, on first login an automatic expiration (configured on first tab) will be set for the user account. User must follow your instructions on how to pay for subscription. You need to confirm by yourself his payment and reconfigure his expiration date.');

// Viaklix Settings
DEFINE ('_CFG_VIAKLIX_ACCOUNTID_NAME', '帐号ID');
DEFINE ('_CFG_VIAKLIX_ACCOUNTID_DESC', '你的帐号Your Account ID on viaKLIX.');
DEFINE ('_CFG_VIAKLIX_USERID_NAME', '用户ID');
DEFINE ('_CFG_VIAKLIX_USERID_DESC', '你在viaKLIX的用户ID.');
DEFINE ('_CFG_VIAKLIX_PIN_NAME', 'PIN码');
DEFINE ('_CFG_VIAKLIX_PIN_DESC', '终端的PIN码.');

// Authorize.net Settings
DEFINE ('_CFG_AUTHORIZE_LOGIN_NAME', 'API登陆ID');
DEFINE ('_CFG_AUTHORIZE_LOGIN_DESC', '你在Authorize.net的API登陆ID.');
DEFINE ('_CFG_AUTHORIZE_TRANSACTION_KEY_NAME', '事务密码');
DEFINE ('_CFG_AUTHORIZE_TRANSACTION_KEY_DESC', '你在Authorize.net上的事务密码.');

// Allopass Settings
DEFINE ('_CFG_ALLOPASS_SITEID_NAME', 'SITE_ID');
DEFINE ('_CFG_ALLOPASS_SITEID_DESC', '你在AlloPass的SITE_ID.');
DEFINE ('_CFG_ALLOPASS_DOCID_NAME', 'DOC_ID');
DEFINE ('_CFG_ALLOPASS_DOCID_DESC', '你在AlloPass的DOC_ID.');
DEFINE ('_CFG_ALLOPASS_AUTH_NAME', 'AUTH');
DEFINE ('_CFG_ALLOPASS_AUTH_DESC', '在AlloPass的AUTH.');

// 2Checkout Settings
DEFINE ('_CFG_2CHECKOUT_SID_NAME', 'SID');
DEFINE ('_CFG_2CHECKOUT_SID_DESC', '你的2Checkout帐号.');
DEFINE ('_CFG_2CHECKOUT_SECRET_WORD_NAME', '密码');
DEFINE ('_CFG_2CHECKOUT_SECRET_WORD_DESC', '设置一个密码在"Look and Feel"页面.');
DEFINE ('_CFG_2CHECKOUT_INFO_NAME', '重要注释!');
DEFINE ('_CFG_2CHECKOUT_INFO_DESC', '在你的2Checkout帐号主页,"Helpful Links"部分,找到"Look and Feel"并点击这个链接.设置"Approved URL"为"http://yoursite.com/index.php?option=com_acctexp&task=2conotification". 替换"yoursite.com"为你的域名.');
DEFINE ('_CFG_2CHECKOUT_ALT2COURL_NAME', 'Alternate Url');
DEFINE ('_CFG_2CHECKOUT_ALT2COURL_DESC', 'Try this in case you encounter a parameter error.');

// WorldPay Settings
DEFINE ('_CFG_WORLDPAY_INSTID_NAME', 'instId');
DEFINE ('_CFG_WORLDPAY_INSTID_DESC', 'Your WorldPay Installation Id.');

// epsNetpay Settings
DEFINE ('_CFG_EPSNETPAY_MERCHANTID_NAME', '店主ID');
DEFINE ('_CFG_EPSNETPAY_MERCHANTID_DESC', '你的epsNetpay帐号.');
DEFINE ('_CFG_EPSNETPAY_MERCHANTPIN_NAME', '店主PIN码');
DEFINE ('_CFG_EPSNETPAY_MERCHANTPIN_DESC', '你的批发商的PIN码.');
DEFINE ('_CFG_EPSNETPAY_ACTIVATE_NAME', 'Activate');
DEFINE ('_CFG_EPSNETPAY_ACTIVATE_DESC', 'Offer this Bank.');
DEFINE ('_CFG_EPSNETPAY_ACCEPTVOK_NAME', 'Accept VOK');
DEFINE ('_CFG_EPSNETPAY_ACCEPTVOK_DESC', 'It might be that due to the account type you have, you will never get an "OK" response, but always "VOK". If that is the case, please switch this on.');

// Paysignet Settings
DEFINE ('_CFG_PAYSIGNET_MERCHANT_NAME', 'Merchant');
DEFINE ('_CFG_PAYSIGNET_MERCHANT_DESC', 'Your Merchant Name.');

// AlertPay Settings
DEFINE ('_CFG_ALERTPAY_MERCHANT_NAME', 'Merchant');
DEFINE ('_CFG_ALERTPAY_MERCHANT_DESC', 'Your Merchant Name.');
DEFINE ('_CFG_ALERTPAY_SECURITYCODE_NAME', 'Security Code');
DEFINE ('_CFG_ALERTPAY_SECURITYCODE_DESC', 'Your Security Code.');

?>