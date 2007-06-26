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
// Italian translation: Luca Scarpa - http://www.luscarpa.eu
//
// Dont allow direct linking
defined( '_VALID_MOS' ) or die( 'Accesso diretto non acconsentito a questo file.' );

// ----======== BACKEND TEXT ========----

// --== BACKEND TOOLBAR ==--
DEFINE ('_EXPIRE_SET','Set Expiration:');
DEFINE ('_EXPIRE_NOW','Expire');
DEFINE ('_EXPIRE_EXCLUDE','Exclude');
DEFINE ('_EXPIRE_INCLUDE','Include');
DEFINE ('_EXPIRE_CLOSE','Close');
DEFINE ('_EXPIRE_01MONTH','set 1 Month');
DEFINE ('_EXPIRE_03MONTH','set 3 Months');
DEFINE ('_EXPIRE_12MONTH','set 12 Months');
DEFINE ('_EXPIRE_ADD01MONTH','add 1 Month');
DEFINE ('_EXPIRE_ADD03MONTH','add 3 Months');
DEFINE ('_EXPIRE_ADD12MONTH','add 12 Months');
DEFINE ('_CONFIGURE','Configura');
DEFINE ('_REMOVE','Escludi');
DEFINE ('_CNAME','Nome');
DEFINE ('_USERLOGIN','Login');
DEFINE ('_EXPIRATION','Scadenza');
DEFINE ('_USERS','Utenti');
DEFINE ('_DISPLAY','Visualizza');
DEFINE ('_NOTSET','Escludi');
DEFINE ('_SAVE','Salva');
DEFINE ('_CANCEL','Cancella');
DEFINE ('_EXP_ASC','Scadenza Asc');
DEFINE ('_EXP_DESC','Scadenza Desc');
DEFINE ('_NAME_ASC','Nome Asc');
DEFINE ('_NAME_DESC','Nome Desc');
DEFINE ('_LOGIN_ASC','Login Asc');
DEFINE ('_LOGIN_DESC','Login Desc');
DEFINE ('_SIGNUP_ASC','Signup Date Asc');
DEFINE ('_SIGNUP_DESC','Signup Date Desc');
DEFINE ('_LASTPAY_ASC','Last Payment Asc');
DEFINE ('_LASTPAY_DESC','Last Payment Desc');
DEFINE ('_PLAN_ASC','Plan Asc');
DEFINE ('_PLAN_DESC','Plan Desc');
DEFINE ('_STATUS_ASC','Status Asc');
DEFINE ('_STATUS_DESC','Status Desc');
DEFINE ('_TYPE_ASC','Payment Type Asc');
DEFINE ('_TYPE_DESC','Payment Type Desc');
DEFINE ('_ORDER_BY','Ordina per:');
DEFINE ('_SAVED', 'Salvato.');
DEFINE ('_CANCELED', 'Cancellato.');
DEFINE ('_CONFIGURED', 'Oggetto configurato.');
DEFINE ('_REMOVED', 'Oggetto rimosso dalla lista.');
DEFINE ('_EOT_TITLE', 'Iscrizioni Chiuse');
DEFINE ('_EOT_DESC', 'Questa lista non include le iscrizioni effettuate manualmente, solo quelle processate tramite Gateway. Quando ne cancelli una, l\'utente viene rimosso dal database e tutte le sue attivit&agrave; saranno rimosse dalla storia.');
DEFINE ('_EOT_DATE', 'Data di terminazione');
DEFINE ('_EOT_CAUSE', 'Causa');
DEFINE ('_EOT_CAUSE_FAIL', 'Pagamento fallito');
DEFINE ('_EOT_CAUSE_BUYER', 'Cancellato dall\'utente');
DEFINE ('_EOT_CAUSE_FORCED', 'Cancellato dall\'amministratore');
DEFINE ('_REMOVE_CLOSED', 'Elimina');
DEFINE ('_FORCE_CLOSE', 'Chiudi Ora');
DEFINE ('_PUBLISH_PAYPLAN', 'Pubblica');
DEFINE ('_UNPUBLISH_PAYPLAN', 'Non Pubblicare');
DEFINE ('_NEW_PAYPLAN', 'Nuovo');
DEFINE ('_EDIT_PAYPLAN', 'Modifica');
DEFINE ('_REMOVE_PAYPLAN', 'Elimina');
DEFINE ('_SAVE_PAYPLAN', 'Salva');
DEFINE ('_CANCEL_PAYPLAN', 'Cancella');
DEFINE ('_PAYPLANS_TITLE', 'Gestione Piano Pagamento');
DEFINE ('_PAYPLANS_MAINDESC', 'Questa lista non include le iscrizioni effettuate manualmente, solo quelle processate tramite Gateway.');
DEFINE ('_PAYPLAN_NAME', 'Nome');
DEFINE ('_PAYPLAN_DESC', 'Descrizione (primi 50 caratteri)');
DEFINE ('_PAYPLAN_ACTIVE', 'Pubblicato');
DEFINE ('_PAYPLAN_VISIBLE', 'Visible');
DEFINE ('_PAYPLAN_A3', 'Importo');
DEFINE ('_PAYPLAN_P3', 'Periodo');
DEFINE ('_PAYPLAN_T3', 'Unit&agrave; del periodo');
DEFINE ('_PAYPLAN_USERCOUNT', 'Inscizioni');
DEFINE ('_PAYPLAN_EXPIREDCOUNT', 'Expired');
DEFINE ('_PAYPLAN_TOTALCOUNT', 'Total');
DEFINE ('_PAYPLAN_REORDER', 'Riordina');
DEFINE ('_PAYPLANS_HEADER', 'Piani Pagamento');
DEFINE ('_PAYPLAN_DETAIL', 'Settings');
DEFINE ('_ALTERNATIVE_PAYMENT', 'Trasferimenti Bancari');
DEFINE ('_SUBSCR_DATE', 'Registra Data');
DEFINE ('_ACTIVE_TITLE', 'Attiva le iscizioni');
DEFINE ('_ACTIVE_DESC', 'Questa lista non include le iscrizioni effettuate manualmente, solo quelle processate tramite Gateway.');
DEFINE ('_LASTPAY_DATE', 'Ultima Data di Pagamento');
DEFINE ('_USERPLAN', 'Piano');
DEFINE ('_CANCELLED_TITLE', 'Iscrizione Cancellate');
DEFINE ('_CANCELLED_DESC', 'Questa lista non include le iscrizioni effettuate manualmente, solo quelle processate tramite Gateway. Quando ne cancelli una, l\'utente viene rimosso dal database e tutte le sue attivit&agrave; saranno rimosse dalla storia.');
DEFINE ('_CANCEL_DATE', 'Data di cancellazione');
DEFINE ('_MANUAL_DESC', 'Quando elimini una riga, l\'utente viene completamente rimosso dal database.');
DEFINE ('_REPEND_ACTIVE', 'Re-Pend');
DEFINE ('_FILTER_PLAN', '- Seleziona il Piano -');
DEFINE ('_BIND_USER', 'Assign To:');
DEFINE ('_PLAN_FILTER','Filter Plan:');
DEFINE ('_CENTRAL_PAGE','Central');

// --== USER FORM ==--
DEFINE ('_HISTORY_COL1_TITLE', 'Invoice');
DEFINE ('_HISTORY_COL2_TITLE', 'Amount');
DEFINE ('_HISTORY_COL3_TITLE', 'Payment Date');
DEFINE ('_HISTORY_COL4_TITLE', 'Method');
DEFINE ('_HISTORY_COL5_TITLE', 'Action');
DEFINE ('_HISTORY_COL6_TITLE', 'Plan');
DEFINE ('_USERINVOICE_ACTION_REPEAT','repeat&nbsp;Link');
DEFINE ('_USERINVOICE_ACTION_CANCEL','cancel');
DEFINE ('_USERINVOICE_ACTION_CLEAR','mark&nbsp;cleared');
DEFINE ('_USERINVOICE_ACTION_CLEAR_APPLY','clear&nbsp;&amp;&nbsp;apply&nbsp;Plan');

// --== BACKEND SETTINGS ==--

// TAB 1 - Global AEC Settings
DEFINE ('_CFG_TAB1_TITLE', 'Globale');
DEFINE ('_CFG_TAB1_SUBTITLE', 'Opzioni di interazione con l\'utente');

DEFINE ('_CFG_TAB1_OPT1NAME', 'Periodo di scadenza iniziale:');
DEFINE ('_CFG_TAB1_OPT1DESC', 'Periodo di scadenza di default, in giorni, per i nuovi registrati. Questo &egrave; relativo alla data di registrazione, cos&igrave; se tu vuoi che i nuovi siano esclusi di default utilizza -1 (meno 1) qui. Questo non ha effetto quando l\'utente utilizza pagamenti automatici (e.g. PayPal Integration).');
DEFINE ('_CFG_TAB1_OPT3NAME', 'Livello di pericolo 2:');
DEFINE ('_CFG_TAB1_OPT3DESC', 'In giorni. Questo &egrave; il primo limite per cominciare ad avvisare l\'utente che la sua iscrizione sta per scadere.');
DEFINE ('_CFG_TAB1_OPT4NAME', 'Livello di pericolo 1:');
DEFINE ('_CFG_TAB1_OPT4DESC', 'In giorni. Questo &egrave; l\'ultimo limite per avvisare l\'utente che la sua iscrizione sta per scadere. Questo potrebbe essere l\'intervallo di chiusura per la scadenza.');
DEFINE ('_CFG_TAB1_OPT5NAME', 'Entry Plan:');
DEFINE ('_CFG_TAB1_OPT5DESC', 'Every user will be subscribed to this plan (no payment!) when the user has no subscription yet');
DEFINE ('_CFG_TAB1_OPT9NAME', 'Require Subscription:');
DEFINE ('_CFG_TAB1_OPT9DESC', 'When enabled, a user MUST have a subscription. If disabled, users will be able to log in without one.');

DEFINE ('_CFG_TAB1_OPT10NAME', 'Descrizioni Gateway:');
DEFINE ('_CFG_TAB1_OPT10DESC', 'Elenca i Gateways che vuoi esporre nel NotAllowed page (che i tuoi clienti vedranno quanto cercheranno di accedere a una pagina che non è concessa a loro).');
DEFINE ('_CFG_TAB1_OPT20NAME', 'Activated Gateways:');
DEFINE ('_CFG_TAB1_OPT20DESC', 'Select the gateways you want to be activated (use the CTRL key to select more than one). After saving, the settings tabs for these gateways will show up. Deactivating a gateway will not erase its settings.');
DEFINE ('_CFG_TAB1_OPT11NAME', 'Require Subscription:');
DEFINE ('_CFG_TAB1_OPT11DESC', 'By default, a user who has no subscription set up with the AEC will be able to log in just fine. With this setting, you can make subscription a requirement.');
DEFINE ('_CFG_ENTRYPLAN_NAME', 'Default Entry Plan');
DEFINE ('_CFG_ENTRYPLAN_DESC', 'Piano Free trial di default.');

DEFINE ('_CFG_TAB1_OPT15NAME', 'Disable Integration:');
DEFINE ('_CFG_TAB1_OPT15DESC', 'Provide one name or a list of names (seperated by a whitespace) of integrations that you want to have disabled. Currently supporting the strings: CB CBE SMF. This can be helpful when you have uninstalled CB but not deleted its tables (in which case the AEC would still recognize it as being installed).');
DEFINE ('_CFG_TAB1_OPT16NAME', 'Simple URLs:');
DEFINE ('_CFG_TAB1_OPT16DESC', 'Disable the use of Joomla/Mambo SEF Routines for Urls. With some setups using these will result in 404 Errors due to wrong rewriting. Try this option if you encounter any problems with redirects.');
DEFINE ('_CFG_TAB1_OPT17NAME', 'Expiration Cushion:');
DEFINE ('_CFG_TAB1_OPT17DESC', 'Number of hours that the AEC takes as cushion when determining expiration. Take a generous amount since payments arive later than the actual expiration (with Paypal about 6-8 hours later).');
DEFINE ('_CFG_TAB1_OPT18NAME', 'Heartbeat Cycle:');
DEFINE ('_CFG_TAB1_OPT18DESC', 'Number of hours that the AEC waits until understanding a login as a trigger for sending out Emails or doing other actions that you chose to be performed periodically.');
DEFINE ('_CFG_TAB1_OPT21NAME', 'Plans First:');
DEFINE ('_CFG_TAB1_OPT21DESC', 'If you have commited all three hacks to have an integrated Registration with direct Subscription, this switch will activate this behavior. Don\'t use it if you don\'t want that behavior or only commited the first hack (which means that the plans come after the user has put in his or her details) .');

DEFINE ('_CFG_TAB_CUSTOMIZATION_TITLE', 'Customize');
DEFINE ('_CFG_TAB1_OPT12NAME', 'Custom intro page link:');
DEFINE ('_CFG_TAB1_OPT12DESC', 'Provide a full link (including http://) that leads to your custom intro page. That page has to contain a link like this: http://www.yourdomain.com/index.php?option=com_acctexp&task=subscribe&intro=1 which bypasses the intro and correctly forwards the user to the payment plans or registration details page. Leave this field blank if you don\'t want this at all.');
DEFINE ('_CFG_TAB1_OPT13NAME', 'Custom thanks page link:');
DEFINE ('_CFG_TAB1_OPT13DESC', 'Provide a full link (including http://) that leads to your custom thanks page. Leave this field blank if you don\'t want this at all.');
DEFINE ('_CFG_TAB1_OPT14NAME', 'Custom cancel page link:');
DEFINE ('_CFG_TAB1_OPT14DESC', 'Provide a full link (including http://) that leads to your custom cancel page. Leave this field blank if you don\'t want this at all.');
DEFINE ('_CFG_TAB1_OPT19NAME', 'Terms of Service:');
DEFINE ('_CFG_TAB1_OPT19DESC', 'Enter a URL to your Terms of Service. The user will have to select a checkbox when confirming his account. If left blank, nothing will show up.');
DEFINE ('_CFG_GENERAL_CUSTOMNOTALLOWED_NAME', 'Custom NotAllowed link:');
DEFINE ('_CFG_GENERAL_CUSTOMNOTALLOWED_DESC', 'Provide a full link (including http://) that leads to your custom NotAllowed page. Leave this field blank if you don\'t want this at all.');

DEFINE ('_CFG_GENERAL_DISPLAY_DATE_FRONTEND_NAME', 'Frontend Date Format');
DEFINE ('_CFG_GENERAL_DISPLAY_DATE_FRONTEND_DESC', 'Specify the way a date is displayed on the frontend. Refer to <a href="http://www.php.net/manual/en/function.strftime.php">the php manual</a> for the correct syntax.');
DEFINE ('_CFG_GENERAL_DISPLAY_DATE_BACKEND_NAME', 'Backend Date Format');
DEFINE ('_CFG_GENERAL_DISPLAY_DATE_BACKEND_DESC', 'Specify the way a date is displayed on the backend. Refer to <a href="http://www.php.net/manual/en/function.strftime.php">the php manual</a> for the correct syntax.');
DEFINE ('_CFG_GENERAL_CUSTOMTEXT_PLANS_NAME', 'Custom Text Plans Page');
DEFINE ('_CFG_GENERAL_CUSTOMTEXT_PLANS_DESC', 'Text that will be displayed on the Plans Page');
DEFINE ('_CFG_GENERAL_CUSTOMTEXT_CONFIRM_NAME', 'Custom Text Confirm Page');
DEFINE ('_CFG_GENERAL_CUSTOMTEXT_CONFIRM_DESC', 'Text that will be displayed on the Confirmation Page');
DEFINE ('_CFG_GENERAL_CUSTOMTEXT_CHECKOUT_NAME', 'Custom Text Checkout Page');
DEFINE ('_CFG_GENERAL_CUSTOMTEXT_CHECKOUT_DESC', 'Text that will be displayed on the Checkout Page');
DEFINE ('_CFG_GENERAL_CUSTOMTEXT_NOTALLOWED_NAME', 'Custom Text NotAllowed Page');
DEFINE ('_CFG_GENERAL_CUSTOMTEXT_NOTALLOWED_DESC', 'Text that will be displayed on the NotAllowed Page');
DEFINE ('_CFG_GENERAL_CUSTOMTEXT_PENDING_NAME', 'Custom Text Pending Page');
DEFINE ('_CFG_GENERAL_CUSTOMTEXT_PENDING_DESC', 'Text that will be displayed on the Pending Page');
DEFINE ('_CFG_GENERAL_CUSTOMTEXT_EXPIRED_NAME', 'Custom Text Expired Page');
DEFINE ('_CFG_GENERAL_CUSTOMTEXT_EXPIRED_DESC', 'Text that will be displayed on the Expired Page');

DEFINE ('_CFG_GENERAL_CUSTOMTEXT_CONFIRM_KEEPORIGINAL_NAME', 'Keep Original Text');
DEFINE ('_CFG_GENERAL_CUSTOMTEXT_CONFIRM_KEEPORIGINAL_DESC', 'Select this option if you want to keep the original text on the Confirmation Page');
DEFINE ('_CFG_GENERAL_CUSTOMTEXT_CHECKOUT_KEEPORIGINAL_NAME', 'Keep Original Text');
DEFINE ('_CFG_GENERAL_CUSTOMTEXT_CHECKOUT_KEEPORIGINAL_DESC', 'Select this option if you want to keep the original text on the Checkout Page');
DEFINE ('_CFG_GENERAL_CUSTOMTEXT_NOTALLOWED_KEEPORIGINAL_NAME', 'Keep Original Text');
DEFINE ('_CFG_GENERAL_CUSTOMTEXT_NOTALLOWED_KEEPORIGINAL_DESC', 'Select this option if you want to keep the original text on the NotAllowed Page');
DEFINE ('_CFG_GENERAL_CUSTOMTEXT_PENDING_KEEPORIGINAL_NAME', 'Keep Original Text');
DEFINE ('_CFG_GENERAL_CUSTOMTEXT_PENDING_KEEPORIGINAL_DESC', 'Select this option if you want to keep the original text on the Pending Page');
DEFINE ('_CFG_GENERAL_CUSTOMTEXT_EXPIRED_KEEPORIGINAL_NAME', 'Keep Original Text');
DEFINE ('_CFG_GENERAL_CUSTOMTEXT_EXPIRED_KEEPORIGINAL_DESC', 'Select this option if you want to keep the original text on the Expired Page');

DEFINE ('_CFG_GENERAL_HEARTBEAT_CYCLE_BACKEND_NAME', 'Heartbeat Cycle Backend:');
DEFINE ('_CFG_GENERAL_HEARTBEAT_CYCLE_BACKEND_DESC', 'Number of hours that the AEC waits until understanding a backend access as heartbeat trigger.');
DEFINE ('_CFG_GENERAL_ENABLE_COUPONS_NAME', 'Enable Coupons:');
DEFINE ('_CFG_GENERAL_ENABLE_COUPONS_DESC', 'Enable the usage of coupons for your subscriptions.');
DEFINE ('_CFG_GENERAL_DISPLAYCCINFO_NAME', 'Enable CC Display:');
DEFINE ('_CFG_GENERAL_DISPLAYCCINFO_DESC', 'Enable the display of CreditCard icons for each payment processor.');

// Global Micro Integration Settings
DEFINE ('_CFG_TAB_MICROINTEGRATION_TITLE', 'MicroIntegr');
DEFINE ('_CFG_MI_ACTIVELIST_NAME', 'Active MicroIntegrations');
DEFINE ('_CFG_MI_ACTIVELIST_DESC', 'Select which MicroIntegrations you want to use');
DEFINE ('_CFG_MI_META_NAME', 'MI Meta Transmit');
DEFINE ('_CFG_MI_META_DESC', 'Allow MicroIntegrations to share an internal array of variables to communicate');

// --== PAYMENT PLAN PAGE ==--
// Additions of variables for free trial periods by Michael Spredemann (scubaguy)

DEFINE ('_PAYPLAN_PERUNIT1', 'Days');
DEFINE ('_PAYPLAN_PERUNIT2', 'Weeks');
DEFINE ('_PAYPLAN_PERUNIT3', 'Months');
DEFINE ('_PAYPLAN_PERUNIT4', 'Years');

// General Params

DEFINE ('_PAYPLAN_DETAIL_TITLE', 'Plan');
DEFINE ('_PAYPLAN_GENERAL_NAME_NAME', 'Name:');
DEFINE ('_PAYPLAN_GENERAL_NAME_DESC', 'Name or title for this plan. Max.: 40 characters.');
DEFINE ('_PAYPLAN_GENERAL_DESC_NAME', 'Description:');
DEFINE ('_PAYPLAN_GENERAL_DESC_DESC', 'Full description of plan as you want to be presented to user. Max.: 255 characters.');
DEFINE ('_PAYPLAN_GENERAL_ACTIVE_NAME', 'Published:');
DEFINE ('_PAYPLAN_GENERAL_ACTIVE_DESC', 'A published plan will be available to the user on frontend.');
DEFINE ('_PAYPLAN_GENERAL_VISIBLE_NAME', 'Visible:');
DEFINE ('_PAYPLAN_GENERAL_VISIBLE_DESC', 'Visible Plans will show on the frontend. Invisible plans will not show and thus only be available to automatic application (like Fallbacks or Entry Plans).');

DEFINE ('_PAYPLAN_PARAMS_GID_ENABLED_NAME', 'Enable usergroup');
DEFINE ('_PAYPLAN_PARAMS_GID_ENABLED_DESC', 'Switch this to Yes if you want users to be assigned the selected usergroup.');
DEFINE ('_PAYPLAN_PARAMS_GID_NAME', 'Add User to Group:');
DEFINE ('_PAYPLAN_PARAMS_GID_DESC', 'Users will be associated to this usergroup when the plan is applied.');

DEFINE ('_PAYPLAN_TEXT_TITLE', 'Plan Text');
DEFINE ('_PAYPLAN_GENERAL_EMAIL_DESC_NAME', 'Email Description:');
DEFINE ('_PAYPLAN_GENERAL_EMAIL_DESC_DESC', 'Text that should be added into the email that the user receives when a plan is activated for him');
DEFINE ('_PAYPLAN_GENERAL_FALLBACK_NAME', 'Plan Fallback:');
DEFINE ('_PAYPLAN_GENERAL_FALLBACK_DESC', 'When a user subscription expires - make them a member of the following plan');

DEFINE ('_PAYPLAN_GENERAL_PROCESSORS_NAME', 'Payment Gateways:');
DEFINE ('_PAYPLAN_NOPLAN', 'No Plan');
DEFINE ('_PAYPLAN_NOGW', 'No Gateway');
DEFINE ('_PAYPLAN_GENERAL_PROCESSORS_DESC', 'Select the payment gateways you want to be available to this plan. Hold Control or Shift key to select multiple options. Selecting ' . _PAYPLAN_NOPLAN . ' all other selected options will be ignored. If you see only ' . _PAYPLAN_NOPLAN . ' here this means you have no payment processor enabled on your config settings.');
DEFINE ('_PAYPLAN_PARAMS_LIFETIME_NAME', 'Lifetime:');
DEFINE ('_PAYPLAN_PARAMS_LIFETIME_DESC', 'Make this a lifetime subscription which never expires.');

DEFINE ('_PAYPLAN_AMOUNT_NOTICE', 'Notice on Periods');
DEFINE ('_PAYPLAN_AMOUNT_NOTICE_TEXT', 'For Paypal Subscriptions, there is a limit on the maximum amount of that you can enter for the period. If you want to use Paypal Subscriptions, <strong>please limit days to 90, weeks to 52, months to 24 and years to 5 at maximum</strong>.');
DEFINE ('_PAYPLAN_AMOUNT_EDITABLE_NOTICE', 'There is one or more users using recurring payments for this plan, so it would be wise not to change the terms until these are cancelled.');

DEFINE ('_PAYPLAN_REGULAR_TITLE', 'Normal Subscription');
DEFINE ('_PAYPLAN_PARAMS_FULL_FREE_NAME', 'Free:');
DEFINE ('_PAYPLAN_PARAMS_FULL_FREE_DESC', 'Set this to yes if you want to offer this plan for free');
DEFINE ('_PAYPLAN_PARAMS_FULL_AMOUNT_NAME', 'Regular Rate:');
DEFINE ('_PAYPLAN_PARAMS_FULL_AMOUNT_DESC', 'The price of the subscription. If there are subscribers to this plan this field can not be changed. If you want to replace this plan, unpublish it and create a new one.');
DEFINE ('_PAYPLAN_PARAMS_FULL_PERIOD_NAME', 'Period:');
DEFINE ('_PAYPLAN_PARAMS_FULL_PERIOD_DESC', 'This is the length of the billing cycle. The number is modified by the regular billing cycle unit (below).  If there are subscribers to this plan this field can not be changed. If you want to replace this plan, unpublish it and create a new one.');
DEFINE ('_PAYPLAN_PARAMS_FULL_PERIODUNIT_NAME', 'Period Unit:');
DEFINE ('_PAYPLAN_PARAMS_FULL_PERIODUNIT_DESC', 'This is the units of the regular billing cycle (above). If there are subscribers to this plan this field can not be changed. If you want to replace this plan, unpublish it and create a new one.');

DEFINE ('_PAYPLAN_TRIAL_TITLE', 'Trial Period');
DEFINE ('_PAYPLAN_TRIAL', '(Optional)');
DEFINE ('_PAYPLAN_TRIAL_DESC', 'Skip this section if you do not want to offer Trial periods with your subscriptions. <strong>Trials only work automatically with PayPal Subscriptions!</strong>');
DEFINE ('_PAYPLAN_PARAMS_TRIAL_FREE_NAME', 'Free:');
DEFINE ('_PAYPLAN_PARAMS_TRIAL_FREE_DESC', 'Set this to yes if you want to offer this trial for free');
DEFINE ('_PAYPLAN_PARAMS_TRIAL_AMOUNT_NAME', 'Trial Rate:');
DEFINE ('_PAYPLAN_PARAMS_TRIAL_AMOUNT_DESC', 'Enter the amount to immediately bill the subscriber.');
DEFINE ('_PAYPLAN_PARAMS_TRIAL_PERIOD_NAME', 'Trial Period:');
DEFINE ('_PAYPLAN_PARAMS_TRIAL_PERIOD_DESC', 'This is the length of the trial period. The number is modified by the relugar billing cycle unit (below).  If there are subscribers to this plan this field can not be changed. If you want to replace this plan, unpublish it and create a new one.');
DEFINE ('_PAYPLAN_PARAMS_TRIAL_PERIODUNIT_NAME', 'Trial Period Unit:');
DEFINE ('_PAYPLAN_PARAMS_TRIAL_PERIODUNIT_DESC', 'This is the units of the trial period (above). If there are subscribers to this plan this field can not be changed. If you want to replace this plan, unpublish it and create a new one.');

// Payplan Relations

DEFINE ('_PAYPLAN_RELATIONS_TITLE', 'Relations');
DEFINE ('_PAYPLAN_PARAMS_SIMILARPLANS_NAME', 'Similar Plans:');
DEFINE ('_PAYPLAN_PARAMS_SIMILARPLANS_DESC', 'Select which plans are similar to this one. A user is not allowed to use a Trial period when buying a plan that he or she has purchased before and this will also be the same for similar plans.');
DEFINE ('_PAYPLAN_PARAMS_EQUALPLANS_NAME', 'Equal Plans:');
DEFINE ('_PAYPLAN_PARAMS_EQUALPLANS_DESC', 'Select which plans are equal to this one. A user switching between equal plans will have his or her period extended instead of reset. Trials are also not permitted (see similar plans info).');

// Payplan Restrictions

DEFINE ('_PAYPLAN_RESTRICTIONS_TITLE', 'Restrictions');
DEFINE ('_PAYPLAN_RESTRICTIONS_MINGID_ENABLED_NAME', 'Enable Min GID:');
DEFINE ('_PAYPLAN_RESTRICTIONS_MINGID_ENABLED_DESC', 'Enable this setting if you want to restrict whether a user is shown this plan by a minimum usergroup.');
DEFINE ('_PAYPLAN_RESTRICTIONS_MINGID_NAME', 'Visibility Group:');
DEFINE ('_PAYPLAN_RESTRICTIONS_MINGID_DESC', 'The minimum user level required to see this plan when building the payment plans page. New users will always see the plans with the lowest gid available.');
DEFINE ('_PAYPLAN_RESTRICTIONS_FIXGID_ENABLED_NAME', 'Enable Fixed GID:');
DEFINE ('_PAYPLAN_RESTRICTIONS_FIXGID_ENABLED_DESC', 'Enable this setting if you want to restrict this plan to one usergroup.');
DEFINE ('_PAYPLAN_RESTRICTIONS_FIXGID_NAME', 'Set Group:');
DEFINE ('_PAYPLAN_RESTRICTIONS_FIXGID_DESC', 'Only users with this usergroup can view this plan.');
DEFINE ('_PAYPLAN_RESTRICTIONS_MAXGID_ENABLED_NAME', 'Enable Max GID:');
DEFINE ('_PAYPLAN_RESTRICTIONS_MAXGID_ENABLED_DESC', 'Enable this setting if you want to restrict whether a user is shown this plan by a maximum usergroup.');
DEFINE ('_PAYPLAN_RESTRICTIONS_MAXGID_NAME', 'Maximum Group:');
DEFINE ('_PAYPLAN_RESTRICTIONS_MAXGID_DESC', 'The maximum user level a user can have to see this plan when building the payment plans page.');

DEFINE ('_PAYPLAN_RESTRICTIONS_PREVIOUSPLAN_REQ_ENABLED_NAME', 'Required Prev. Plan:');
DEFINE ('_PAYPLAN_RESTRICTIONS_PREVIOUSPLAN_REQ_ENABLED_DESC', 'Enable checking for previous payment plan');
DEFINE ('_PAYPLAN_RESTRICTIONS_PREVIOUSPLAN_REQ_NAME', 'Plan:');
DEFINE ('_PAYPLAN_RESTRICTIONS_PREVIOUSPLAN_REQ_DESC', 'A user will only see this plan if he or she used the selected plan before the one currently in use');
DEFINE ('_PAYPLAN_RESTRICTIONS_CURRENTPLAN_REQ_ENABLED_NAME', 'Required Curr. Plan:');
DEFINE ('_PAYPLAN_RESTRICTIONS_CURRENTPLAN_REQ_ENABLED_DESC', 'Enable checking for currently present payment plan');
DEFINE ('_PAYPLAN_RESTRICTIONS_CURRENTPLAN_REQ_NAME', 'Plan:');
DEFINE ('_PAYPLAN_RESTRICTIONS_CURRENTPLAN_REQ_DESC', 'A user will only see this plan if he or she is currently assigned to, or has expired from the plan selected here');
DEFINE ('_PAYPLAN_RESTRICTIONS_OVERALLPLAN_REQ_ENABLED_NAME', 'Required Used Plan:');
DEFINE ('_PAYPLAN_RESTRICTIONS_OVERALLPLAN_REQ_ENABLED_DESC', 'Enable checking for overall used payment plan');
DEFINE ('_PAYPLAN_RESTRICTIONS_OVERALLPLAN_REQ_NAME', 'Plan:');
DEFINE ('_PAYPLAN_RESTRICTIONS_OVERALLPLAN_REQ_DESC', 'A user will only see this plan if he or she has used the selected plan once, no matter when');

DEFINE ('_PAYPLAN_RESTRICTIONS_USED_PLAN_MIN_ENABLED_NAME', 'Min Used Plan:');
DEFINE ('_PAYPLAN_RESTRICTIONS_USED_PLAN_MIN_ENABLED_DESC', 'Enable checking for the minimum number of times your consumers have subscribed to a specified payment plan in order to see THIS plan');
DEFINE ('_PAYPLAN_RESTRICTIONS_USED_PLAN_MIN_AMOUNT_NAME', 'Used Amount:');
DEFINE ('_PAYPLAN_RESTRICTIONS_USED_PLAN_MIN_AMOUNT_DESC', 'The minimum amount a user has to have used the selected plan');
DEFINE ('_PAYPLAN_RESTRICTIONS_USED_PLAN_MIN_NAME', 'Plan:');
DEFINE ('_PAYPLAN_RESTRICTIONS_USED_PLAN_MIN_DESC', 'The payment plan that the user has to have used the specified number of times at least');
DEFINE ('_PAYPLAN_RESTRICTIONS_USED_PLAN_MAX_ENABLED_NAME', 'Max Used Plan:');
DEFINE ('_PAYPLAN_RESTRICTIONS_USED_PLAN_MAX_ENABLED_DESC', 'Enable checking for the maximum number of times your consumers have subscribed to a specified payment plan in order to see THIS plan');
DEFINE ('_PAYPLAN_RESTRICTIONS_USED_PLAN_MAX_AMOUNT_NAME', 'Used Amount:');
DEFINE ('_PAYPLAN_RESTRICTIONS_USED_PLAN_MAX_AMOUNT_DESC', 'The maximum amount a user can have used the selected plan');
DEFINE ('_PAYPLAN_RESTRICTIONS_USED_PLAN_MAX_NAME', 'Plan:');
DEFINE ('_PAYPLAN_RESTRICTIONS_USED_PLAN_MAX_DESC', 'The payment plan that the user has to have used the specified number of times at most');

DEFINE ('_PAYPLAN_MI', 'Micro Integr.');
DEFINE ('_PAYPLAN_GENERAL_MICRO_INTEGRATIONS_NAME', 'Micro Integrations:');
DEFINE ('_PAYPLAN_GENERAL_MICRO_INTEGRATIONS_DESC', 'Select the Micro Integrations that you want to apply to the user with the plan.');

DEFINE ('_PAYPLAN_CURRENCY', 'Valuta');

DEFINE ('_CURRENCY_AFA', 'Afghani');
DEFINE ('_CURRENCY_ALL', 'Lek');
DEFINE ('_CURRENCY_DZD', 'Algerian Dinar');
DEFINE ('_CURRENCY_ADP', 'Andorran Peseta');
DEFINE ('_CURRENCY_AON', 'New Kwanza');
DEFINE ('_CURRENCY_ARS', 'Argentine Peso');
DEFINE ('_CURRENCY_AMD', 'Armenian Dram');
DEFINE ('_CURRENCY_AWG', 'Aruban Guilder');
DEFINE ('_CURRENCY_AUD', 'Australian Dollar');
DEFINE ('_CURRENCY_AZM', 'Azerbaijanian Manat ');
DEFINE ('_CURRENCY_EUR', 'Euro');
DEFINE ('_CURRENCY_BSD', 'Bahamian Dollar');
DEFINE ('_CURRENCY_BHD', 'Bahraini Dinar');
DEFINE ('_CURRENCY_BDT', 'Taka');
DEFINE ('_CURRENCY_BBD', 'Barbados Dollar');
DEFINE ('_CURRENCY_BYB', 'Belarussian Ruble');
DEFINE ('_CURRENCY_BEF', 'Belgian Franc');
DEFINE ('_CURRENCY_BZD', 'Belize Dollar');
DEFINE ('_CURRENCY_BMD', 'Bermudian Dollar');
DEFINE ('_CURRENCY_BOB', 'Boliviano');
DEFINE ('_CURRENCY_BAD', 'Bosnian Dinar');
DEFINE ('_CURRENCY_BWP', 'Pula');
DEFINE ('_CURRENCY_BRL', 'Real');
DEFINE ('_CURRENCY_BND', 'Brunei Dollar');
DEFINE ('_CURRENCY_BGL', 'Lev');
DEFINE ('_CURRENCY_BGN', 'Lev');
DEFINE ('_CURRENCY_XOF', 'CFA Franc BCEAO');
DEFINE ('_CURRENCY_BIF', 'Burundi Franc');
DEFINE ('_CURRENCY_KHR', 'Cambodia Riel');
DEFINE ('_CURRENCY_XAF', 'CFA Franc BEAC');
DEFINE ('_CURRENCY_CAD', 'Canadian Dollar');
DEFINE ('_CURRENCY_CVE', 'Cape Verde Escudo');
DEFINE ('_CURRENCY_KYD', 'Cayman Islands Dollar');
DEFINE ('_CURRENCY_CLP', 'Chilean Peso');
DEFINE ('_CURRENCY_CNY', 'Yuan Renminbi');
DEFINE ('_CURRENCY_COP', 'Colombian Peso');
DEFINE ('_CURRENCY_KMF', 'Comoro Franc');
DEFINE ('_CURRENCY_BAM', 'Convertible Marks');
DEFINE ('_CURRENCY_CRC', 'Costa Rican Colon');
DEFINE ('_CURRENCY_HRK', 'Croatian Kuna');
DEFINE ('_CURRENCY_CUP', 'Cuban Peso');
DEFINE ('_CURRENCY_CYP', 'Cyprus Pound');
DEFINE ('_CURRENCY_CZK', 'Czech Koruna');
DEFINE ('_CURRENCY_DKK', 'Danish Krone');
DEFINE ('_CURRENCY_DEM', 'Deutsche Mark');
DEFINE ('_CURRENCY_DJF', 'Djibouti Franc');
DEFINE ('_CURRENCY_XCD', 'East Caribbean Dollar');
DEFINE ('_CURRENCY_DOP', 'Dominican Peso');
DEFINE ('_CURRENCY_GRD', 'Drachma');
DEFINE ('_CURRENCY_TPE', 'Timor Escudo');
DEFINE ('_CURRENCY_ECS', 'Ecuador Sucre');
DEFINE ('_CURRENCY_EGP', 'Egyptian Pound');
DEFINE ('_CURRENCY_SVC', 'El Salvador Colon');
DEFINE ('_CURRENCY_EEK', 'Kroon');
DEFINE ('_CURRENCY_ETB', 'Ethiopian Birr');
DEFINE ('_CURRENCY_FKP', 'Falkland Islands Pound');
DEFINE ('_CURRENCY_FJD', 'Fiji Dollar');
DEFINE ('_CURRENCY_XPF', 'CFP Franc');
DEFINE ('_CURRENCY_FRF', 'Franc');
DEFINE ('_CURRENCY_CDF', 'Franc Congolais');
DEFINE ('_CURRENCY_GMD', 'Dalasi');
DEFINE ('_CURRENCY_GHC', 'Cedi');
DEFINE ('_CURRENCY_GIP', 'Gibraltar Pound');
DEFINE ('_CURRENCY_GTQ', 'Quetzal');
DEFINE ('_CURRENCY_GNF', 'Guinea Franc');
DEFINE ('_CURRENCY_GWP', 'Guinea - Bissau Peso');
DEFINE ('_CURRENCY_GYD', 'Guyana Dollar');
DEFINE ('_CURRENCY_HTG', 'Gourde');
DEFINE ('_CURRENCY_XAU', 'Gold');
DEFINE ('_CURRENCY_HNL', 'Lempira');
DEFINE ('_CURRENCY_HKD', 'Hong Kong Dollar');
DEFINE ('_CURRENCY_HUF', 'Forint');
DEFINE ('_CURRENCY_ISK', 'Iceland Krona');
DEFINE ('_CURRENCY_INR', 'Indian Rupee');
DEFINE ('_CURRENCY_IDR', 'Rupiah');
DEFINE ('_CURRENCY_IRR', 'Iranian Rial');
DEFINE ('_CURRENCY_IQD', 'Iraqi Dinar');
DEFINE ('_CURRENCY_IEP', 'Irish Pound');
DEFINE ('_CURRENCY_ITL', 'Italian Lira');
DEFINE ('_CURRENCY_ILS', 'Shekel');
DEFINE ('_CURRENCY_JMD', 'Jamaican Dollar');
DEFINE ('_CURRENCY_JPY', 'Japan Yen');
DEFINE ('_CURRENCY_JOD', 'Jordanian Dinar');
DEFINE ('_CURRENCY_KZT', 'Tenge');
DEFINE ('_CURRENCY_KES', 'Kenyan Shilling');
DEFINE ('_CURRENCY_KRW', 'Won');
DEFINE ('_CURRENCY_KPW', 'North Korean Won');
DEFINE ('_CURRENCY_KWD', 'Kuwaiti Dinar');
DEFINE ('_CURRENCY_KGS', 'Som');
DEFINE ('_CURRENCY_LAK', 'Kip');
DEFINE ('_CURRENCY_GEL', 'Lari');
DEFINE ('_CURRENCY_LVL', 'Latvian Lats');
DEFINE ('_CURRENCY_LBP', 'Lebanese Pound');
DEFINE ('_CURRENCY_LSL', 'Loti');
DEFINE ('_CURRENCY_LRD', 'Liberian Dollar');
DEFINE ('_CURRENCY_LYD', 'Libyan Dinar');
DEFINE ('_CURRENCY_LTL', 'Lithuanian Litas');
DEFINE ('_CURRENCY_LUF', 'Luxembourg Franc');
DEFINE ('_CURRENCY_AOR', 'Kwanza Reajustado');
DEFINE ('_CURRENCY_MOP', 'Pataca');
DEFINE ('_CURRENCY_MKD', 'Denar');
DEFINE ('_CURRENCY_MGF', 'Malagasy Franc');
DEFINE ('_CURRENCY_MWK', 'Kwacha');
DEFINE ('_CURRENCY_MYR', 'Malaysian Ringitt');
DEFINE ('_CURRENCY_MVR', 'Rufiyaa');
DEFINE ('_CURRENCY_MTL', 'Maltese Lira');
DEFINE ('_CURRENCY_MRO', 'Ouguiya');
DEFINE ('_CURRENCY_TMM', 'Manat');
DEFINE ('_CURRENCY_FIM', 'Markka');
DEFINE ('_CURRENCY_MUR', 'Mauritius Rupee');
DEFINE ('_CURRENCY_MXN', 'Mexico Peso');
DEFINE ('_CURRENCY_MXV', 'Mexican Unidad de Inversion');
DEFINE ('_CURRENCY_MNT', 'Mongolia Tugrik');
DEFINE ('_CURRENCY_MAD', 'Moroccan Dirham');
DEFINE ('_CURRENCY_MDL', 'Moldovan Leu');
DEFINE ('_CURRENCY_MZM', 'Metical');
DEFINE ('_CURRENCY_BOV', 'Mvdol');
DEFINE ('_CURRENCY_MMK', 'Myanmar Kyat');
DEFINE ('_CURRENCY_ERN', 'Nakfa');
DEFINE ('_CURRENCY_NAD', 'Namibian Dollar');
DEFINE ('_CURRENCY_NPR', 'Nepalese Rupee');
DEFINE ('_CURRENCY_ANG', 'Netherlands Antilles Guilder');
DEFINE ('_CURRENCY_NLG', 'Netherlands Guilder');
DEFINE ('_CURRENCY_NZD', 'New Zealand Dollar');
DEFINE ('_CURRENCY_NIO', 'Cordoba Oro');
DEFINE ('_CURRENCY_NGN', 'Naira');
DEFINE ('_CURRENCY_BTN', 'Ngultrum');
DEFINE ('_CURRENCY_NOK', 'Norwegian Krone');
DEFINE ('_CURRENCY_OMR', 'Rial Omani');
DEFINE ('_CURRENCY_PKR', 'Pakistan Rupee');
DEFINE ('_CURRENCY_PAB', 'Balboa');
DEFINE ('_CURRENCY_PGK', 'New Guinea Kina');
DEFINE ('_CURRENCY_PYG', 'Guarani');
DEFINE ('_CURRENCY_PEN', 'Nuevo Sol');
DEFINE ('_CURRENCY_XPD', 'Palladium');
DEFINE ('_CURRENCY_PHP', 'Philippine Peso');
DEFINE ('_CURRENCY_XPT', 'Platinum');
DEFINE ('_CURRENCY_PTE', 'Portuguese Escudo');
DEFINE ('_CURRENCY_PLN', 'New Zloty');
DEFINE ('_CURRENCY_QAR', 'Qatari Rial');
DEFINE ('_CURRENCY_ROL', 'Leu');
DEFINE ('_CURRENCY_RUB', 'Russian Ruble');
DEFINE ('_CURRENCY_RWF', 'Rwanda Franc');
DEFINE ('_CURRENCY_WST', 'Tala');
DEFINE ('_CURRENCY_STD', 'Dobra');
DEFINE ('_CURRENCY_SAR', 'Saudi Riyal');
DEFINE ('_CURRENCY_SCR', 'Seychelles Rupee');
DEFINE ('_CURRENCY_SLL', 'Leone');
DEFINE ('_CURRENCY_SGD', 'Singapore Dollar');
DEFINE ('_CURRENCY_SKK', 'Slovak Koruna');
DEFINE ('_CURRENCY_SIT', 'Tolar');
DEFINE ('_CURRENCY_SBD', 'Solomon Islands Dollar');
DEFINE ('_CURRENCY_SOS', 'Somalia Shilling');
DEFINE ('_CURRENCY_ZAL', 'Rand (Financial)');
DEFINE ('_CURRENCY_ZAR', 'Rand (South Africa)');
DEFINE ('_CURRENCY_RUR', 'Russian Ruble');
DEFINE ('_CURRENCY_ATS', 'Schilling');
DEFINE ('_CURRENCY_XAG', 'Silver');
DEFINE ('_CURRENCY_ESP', 'Spanish Peseta');
DEFINE ('_CURRENCY_LKR', 'Sri Lanka Rupee');
DEFINE ('_CURRENCY_SHP', 'St Helena Pound');
DEFINE ('_CURRENCY_SDP', 'Sudanese Pound');
DEFINE ('_CURRENCY_SDD', 'Sudanese Dinar');
DEFINE ('_CURRENCY_SRG', 'Suriname Guilder');
DEFINE ('_CURRENCY_SZL', 'Swaziland Lilangeni');
DEFINE ('_CURRENCY_SEK', 'Sweden Krona');
DEFINE ('_CURRENCY_CHF', 'Swiss Franc');
DEFINE ('_CURRENCY_SYP', 'Syrian Pound');
DEFINE ('_CURRENCY_TWD', 'New Taiwan Dollar');
DEFINE ('_CURRENCY_TJR', 'Tajik Ruble');
DEFINE ('_CURRENCY_TZS', 'Tanzanian Shilling');
DEFINE ('_CURRENCY_TRL', 'Turkish Lira');
DEFINE ('_CURRENCY_THB', 'Baht');
DEFINE ('_CURRENCY_TOP', 'Tonga Pa\'anga');
DEFINE ('_CURRENCY_TTD', 'Trinidad &amp; Tobago Dollar');
DEFINE ('_CURRENCY_TND', 'Tunisian Dinar');
DEFINE ('_CURRENCY_TRY', 'Turkish Lira');
DEFINE ('_CURRENCY_UGX', 'Uganda Shilling');
DEFINE ('_CURRENCY_UAH', 'Ukrainian Hryvnia');
DEFINE ('_CURRENCY_ECV', 'Unidad de Valor Constante');
DEFINE ('_CURRENCY_CLF', 'Unidades de fomento');
DEFINE ('_CURRENCY_AED', 'United Arab Emirates Dirham');
DEFINE ('_CURRENCY_GBP', 'Pounds Sterling');
DEFINE ('_CURRENCY_USD', 'US Dollar');
DEFINE ('_CURRENCY_UYU', 'Uruguayan Peso');
DEFINE ('_CURRENCY_UZS', 'Uzbekistan Sum');
DEFINE ('_CURRENCY_VUV', 'Vanuatu Vatu');
DEFINE ('_CURRENCY_VEB', 'Venezuela Bolivar');
DEFINE ('_CURRENCY_VND', 'Viet Nam Dong');
DEFINE ('_CURRENCY_YER', 'Yemeni Rial');
DEFINE ('_CURRENCY_YUM', 'Yugoslavian New Dinar');
DEFINE ('_CURRENCY_ZRN', 'New Zaire');
DEFINE ('_CURRENCY_ZMK', 'Zambian Kwacha');
DEFINE ('_CURRENCY_ZWD', 'Zimbabwe Dollar');
DEFINE ('_CURRENCY_USN', 'US Dollar (Next day)');
DEFINE ('_CURRENCY_USS', 'US Dollar (Same day)');

// --== MICRO INTEGRATION OVERVIEW ==--
DEFINE ('_MI_TITLE', 'Micro Integrations');
DEFINE ('_MI_NAME', 'Name');
DEFINE ('_MI_DESC', 'Description');
DEFINE ('_MI_ACTIVE', 'Active');
DEFINE ('_MI_REORDER', 'Order');
DEFINE ('_MI_FUNCTION', 'Function Name');

// --== MICRO INTEGRATION EDIT ==--
DEFINE ('_MI_E_TITLE', 'MI');
DEFINE ('_MI_E_TITLE_LONG', 'Micro Integration');
DEFINE ('_MI_E_SETTINGS', 'Settings');
DEFINE ('_MI_E_NAME_NAME', 'Name');
DEFINE ('_MI_E_NAME_DESC', 'Choose a name for this Micro Integration');
DEFINE ('_MI_E_DESC_NAME', 'Description');
DEFINE ('_MI_E_DESC_DESC', 'Briefly Describe the Integration');
DEFINE ('_MI_E_ACTIVE_NAME', 'Active');
DEFINE ('_MI_E_ACTIVE_DESC', 'Set the Integration to active');
DEFINE ('_MI_E_ACTIVE_AUTO_NAME', 'Expiration Action');
DEFINE ('_MI_E_ACTIVE_AUTO_DESC', 'If the function allows this, you can enable expiration features: actions that have to be carried out when a user expires (if supported by the MI).');
DEFINE ('_MI_E_ACTIVE_USERUPDATE_NAME', 'User Account Update Action');
DEFINE ('_MI_E_ACTIVE_USERUPDATE_DESC', 'If the function allows this, you can enable actions that happen when a user account is being changed (if supported by the MI).');
DEFINE ('_MI_E_PRE_EXP_NAME', 'Pre Expiration');
DEFINE ('_MI_E_PRE_EXP_DESC', 'Set the amount of days before expiration when a pre-expiration action should be triggered (if supported by the MI).');
DEFINE ('_MI_E_FUNCTION_NAME', 'Function Name');
DEFINE ('_MI_E_FUNCTION_DESC', 'Please choose which of these integrations should be used');
DEFINE ('_MI_E_FUNCTION_EXPLANATION', 'Before you can setup the Micro Integration, you have to select which of the integration files we should use for this. Make a selection and save the Micro Integration. When you edit it again, you will be able to set the parameters. Note also, that the function name cannot be changed once its set.');

// --== REWRITE EXPLANATION ==--
DEFINE ('_REWRITE_AREA_USER', 'User Account Related');
DEFINE ('_REWRITE_KEY_USER_ID', 'User Account ID');
DEFINE ('_REWRITE_KEY_USER_USERNAME', 'Username');
DEFINE ('_REWRITE_KEY_USER_NAME', 'Name');
DEFINE ('_REWRITE_KEY_USER_EMAIL', 'E-Mail Address');

DEFINE ('_REWRITE_AREA_EXPIRATION', 'User Expiration Related');
DEFINE ('_REWRITE_KEY_EXPIRATION_DATE', 'Expiration Date');

DEFINE ('_REWRITE_AREA_SUBSCRIPTION', 'User Subscription Related');
DEFINE ('_REWRITE_KEY_SUBSCRIPTION_TYPE', 'Payment Processor');
DEFINE ('_REWRITE_KEY_SUBSCRIPTION_STATUS', 'Subscription Status');
DEFINE ('_REWRITE_KEY_SUBSCRIPTION_SIGNUP_DATE', 'Date this Subscription was established');
DEFINE ('_REWRITE_KEY_SUBSCRIPTION_LASTPAY_DATE', 'Last Payment Date');
DEFINE ('_REWRITE_KEY_SUBSCRIPTION_PLAN', 'Payment Plan ID');
DEFINE ('_REWRITE_KEY_SUBSCRIPTION_PREVIOUS_PLAN', 'Previous Payment Plan ID');
DEFINE ('_REWRITE_KEY_SUBSCRIPTION_RECURRING', 'Recurring Payment Flag');
DEFINE ('_REWRITE_KEY_SUBSCRIPTION_LIFETIME', 'Lifetime Subscription Flag');

DEFINE ('_REWRITE_AREA_PLAN', 'Payment Plan Related');
DEFINE ('_REWRITE_KEY_PLAN_NAME', 'Name');
DEFINE ('_REWRITE_KEY_PLAN_DESC', 'Description');

DEFINE ('_REWRITE_AREA_CMS', 'CMS Related');
DEFINE ('_REWRITE_KEY_CMS_ABSOLUTE_PATH', 'Absolute path to cms directory');
DEFINE ('_REWRITE_KEY_CMS_LIVE_SITE', 'Your Site URL');

// --== COUPONS OVERVIEW ==--
DEFINE ('_COUPON_TITLE', 'Coupons');
DEFINE ('_COUPON_TITLE_STATIC', 'Static Coupons');
DEFINE ('_COUPON_NAME', 'Name');
DEFINE ('_COUPON_DESC', 'Description (first 50 chars)');
DEFINE ('_COUPON_ACTIVE', 'Published');
DEFINE ('_COUPON_REORDER', 'Reorder');
DEFINE ('_COUPON_USECOUNT', 'Use Count');

// --== INVOICE OVERVIEW ==--
DEFINE ('_INVOICE_TITLE', 'Invoices');
DEFINE ('_INVOICE_SEARCH', 'Search');
DEFINE ('_INVOICE_USERID', 'User Name');
DEFINE ('_INVOICE_INVOICE_NUMBER', 'Invoice Number');
DEFINE ('_INVOICE_TRANSACTION_DATE', 'Transaction Date');
DEFINE ('_INVOICE_METHOD', 'Invoice Method');
DEFINE ('_INVOICE_AMOUNT', 'Invoice Amount');
DEFINE ('_INVOICE_CURRENCY', 'Invoices Currency');

// --== PAYMENT HISTORY OVERVIEW ==--
DEFINE ('_HISTORY_TITLE2', 'Your Current Transaction History');
DEFINE ('_HISTORY_SEARCH', 'Search');
DEFINE ('_HISTORY_USERID', 'User Name');
DEFINE ('_HISTORY_INVOICE_NUMBER', 'Invoice Number');
DEFINE ('_INVOICE_CREATED_DATE', 'Created Date');
DEFINE ('_HISTORY_PLAN_NAME', 'Plan Subscribed To');
DEFINE ('_HISTORY_TRANSACTION_DATE', 'Transaction Date');
DEFINE ('_HISTORY_METHOD', 'Invoice Method');
DEFINE ('_HISTORY_AMOUNT', 'Invoice Amount');
DEFINE ('_HISTORY_RESPONSE', 'Server Response');

// --== ALL USER RELATED PAGES ==--
DEFINE ('_METHOD', 'Metodo');

// --== PENDING PAGE ==--
DEFINE ('_PEND_DATE', 'Ancora in sospeso');
DEFINE ('_PEND_TITLE', 'Iscirzioni in sospeso');
DEFINE ('_PEND_DESC', 'Iscrizioni che non hanno completato il processo. Questo stato &egrave; normale per un ristretto periodo di tempo mentre il sistema attende il pagamento.');
DEFINE ('_ACTIVATED', 'Utente attivato.');
DEFINE ('_ACTIVATE', 'Attivato');

?>