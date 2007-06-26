<?php
/**
* @version $Id: german.php 16 2007-06-25 09:04:04Z mic $
* @package AEC - Account Control Expiration - Subscription component for Joomla! OS CMS
* @subpackage Language - Backend - German Formal
* @copyright Copyright (C) 2004-2007 Helder Garcia, David Deutsch
* @author Team AEC - http://www.gobalnerd.org
* @license GNU/GPL v.2 http://www.gnu.org/copyleft/gpl.html
*/
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

// Dont allow direct linking
defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );

// ----======== BACKEND TEXT ========----

// hacks/install >> ATTENTION: MUST BE HERE AT THIS POSITION, needed later!
define( '_AEC_SPEC_MENU_ENTRY',					'Mein Abonnement' ); // My Subscription

// mic: adopted from the english file (missing here)
DEFINE ('_CFG_GENERAL_CUSTOMNOTALLOWED_NAME',	'Link zur Nichterlaubtseite:');

// mic: NEW
// also NOT in english file
define( '_DESCRIPTION_PAYSIGNET',				'mic: Beschreibung zu Paysignnet - CHECKEN! -');
define( '_AEC_CONFIG_SAVED',					'Konfiguration erfolgreich gesichert' ); // Configuration saved
define( '_AEC_CONFIG_CANCELLED',				'Konfiguration abgebrochen' ); // Configuration cancelled
define( '_AEC_TIP_NO_GROUP_PF_PB',				'Weder "Public Frontend" noch "Public Backend" sind w&auml;hlbare Benutzergruppen!' ); // Public Frontend" is NOT a usergroup - and neither is "Public Backend
define( '_AEC_CGF_LINK_ABO_FRONTEND',			'Zur Einstiegsseite' ); // Direct Frontend link
define( '_AEC_NOT_SET',							'Nein' ); // Not set
define( '_AEC_COUPON',							'Gutschein' ); // Coupon
define( '_AEC_CMN_NEW',							'Neu' ); // New
define( '_AEC_CMN_CLICK_TO_EDIT',				'Klicken zum Bearbeiten' ); // Click to edit
define( '_AEC_CMN_LIFETIME',					'Lebenslang' ); // Lifetime
define( '_AEC_CMN_UNKOWN',						'Unbekannt' ); // Unknown
define( '_AEC_CMN_EDIT_CANCELLED',				'Bearbeitung abgebrochen' ); // Changes cancelled
define( '_AEC_CMN_PUBLISHED',					'Ver&ouml;ffentlicht' ); // Published
define( '_AEC_CMN_NOT_PUBLISHED',				'Nicht Ver&ouml;ffentlicht' ); // Not Published
define( '_AEC_CMN_CLICK_TO_CHANGE',				'Auf Icon klicken um Status zu &auml;ndern' ); // Click on icon to toggle state.
define( '_AEC_CMN_NONE_SELECTED',				'&gt;&gt;&nbsp;Keine Auswahl&nbsp;&lt;&lt;' ); // None Selected
define( '_AEC_CMN_MADE_VISIBLE',				'sichtbar gemacht' ); // made visible
define( '_AEC_CMN_MADE_INVISIBLE',				'unsichtbar gemacht' ); // made invisible
define( '_AEC_CMN_TOPUBLISH',					'zu ver&ouml;ffentlichen' ); // publish // to ...
define( '_AEC_CMN_TOUNPUBLISH',					'nicht zu ver&ouml;ffentlichen' ); // unpublish // to ...
define( '_AEC_CMN_FILE_SAVED',					'Datei gesichert' ); // File saved.

// headers
define( '_AEC_HEAD_SETTINGS',					'Einstellungen' ); // Settings
define( '_AEC_HEAD_HACKS',						'Spezial' ); // Hacks

// hacks (special)
define( '_AEC_HACK_HACK',						'&Auml;nderung' ); // Hack
define( '_AEC_HACKS_ISHACKED',					'Ge&auml;ndert' ); // is hacked
define( '_AEC_HACKS_NOTHACKED',					'Noch nicht ge&auml;ndert!' ); // is not hacked!
define( '_AEC_HACKS_UNDO',						'Originalzustand wiederherstellen' ); // undo now
define( '_AEC_HACKS_COMMIT',					'Jetzt durchf&uuml;hren' ); // commit
define( '_AEC_HACKS_FILE',						'Datei' ); // File
define( '_AEC_HACKS_LOOKS_FOR',					'Diese &Auml;nderung &uuml;berpr&uuml;ft' ); // The Hack will look for this
define( '_AEC_HACKS_REPLACE_WITH',				'... und ersetzt es mit' ); // ... and replace it with this

define( '_AEC_HACKS_NOTICE',					'Wichtiger Hinweis' ); // IMPORTANT NOTICE
define( '_AEC_HACKS_NOTICE_DESC',				'Aus Sicherheitsgr&uuml;nden und damit AEC funktionieren kann, sind nachfolgende &Auml;nderungen notwendig.<br />Diese k&ouml;nnen entweder automatisch durchgef&uuml;hrt werden (auf den Best&auml;tigungslink klicken) oder manuell (bearbeiten der php.Dateien).<br />Damit die Benutzer zru pers&ouml;nlichen Abo&uuml;bericht kommen, muss auch die Benutzerlink&auml;nderung durchgef&uuml;hrt werden.' ); //   For security reason you need apply hacks to joomla core files. To do so, please click the "hack file now" links for these files. You may also Add a link to your User Menu so that your Users can have a look at their Subscription Record.
define( '_AEC_HACKS_NOTICE_DESC2',				'<strong>Alle wichtigen Funktions&auml;nderungen sind mit einem Pfeil und Ausrufzeichen markiert!</strong>' ); // <strong>All functionally important hacks are marked with an arrow and an exclamation mark.</strong>')
define( '_AEC_HACKS_NOTICE_DESC3',				'Die nachfolgenden Anzeigen sind <strong>nicht</strong> lt. der Nummerierung (#1, #3, #6, usw.) erforderlich.<br />Falls keine Nummer dabei steht, sind das wahrscheinlich veraltete (fr&uuml;here) &Auml;nderungen welche korrigiert werden m&uuml;ssen.' ); // These hacks are <strong>not necessarily in a correct numerical order</strong> - so don't wonder if they go #1, #3, #6 - the missing numbers are most likely legacy hacks that you would only see if you had them (incorrectly) applied.
define( '_AEC_HACKS_NOTICE_JACL',				'JACL Anmerkung' ); // JACL NOTICE
define( '_AEC_HACKS_NOTICE_JACL_DESC',			'Falls geplant ist Erweiterungen wie JACL-Plus zu installieren, <strong>m&uuml;ssen die AEC-&Auml;nderungen danacj durchgef&uuml;hrt werden!</strong>, da solche Komponenten eigene, weitere &Auml;nderungen vornehmen!' ); //  In case you plan to install the JACLplus component, please make sure that the hacks are <strong>not commited</strong> when installing it. JACL also commits hacks to the core files and it is important that our hacks are committed after the JACL ones.

define( '_AEC_HACKS_MENU_ENTRY',				'Men&uuml;eintrag' ); // Menu Entry
define( '_AEC_HACKS_MENU_ENTRY_DESC',			'F&uuml;gt dem Benutzermen&uuml; den neuen Eintrag <strong>' . _AEC_SPEC_MENU_ENTRY . '</strong> hinzu.<br />Damit kann dieser Benutzer sowohl die bisherigen Abos und Rechnungen, als auch neue/andere Abos bestellen/erneuern.' ); // Adds a <strong>My Subscription</strong> menu entry to the Usermenu. With this, a user can manage his invoices and upgrade/renew his or her subscription.' );
define( '_AEC_HACKS_LEGACY',					'<strong>Das ist eine veraltete Version, bitte l&ouml;schen oder aktualisieren!</strong>' ); // <strong>This is a Legacy Hack, please undo!</strong>'
define( '_AEC_HACKS_NOTAUTH',					'Diese &Auml;nderung korrigiert den Link - falls keine Berechtigung zum Ansehen vorliegt - zur Aboseite.' ); // This will correctly link your users to the NotAllowed page with information about your subscriptions')
define( '_AEC_HACKS_SUB_REQUIRED',				'&Uuml;berpr&uuml;ft das Vorhandensein eines g&uuml;ltigen Abos zum einloggen<br /><strong>Nicht vergessen in der AEC-Konfiguration die Einstellung [ Ben&ouml;tigt Abo ] zu aktivieren!</strong>' ); // This will make sure a user has a subscription in order to log in.<br /><strong>Remember to also set [ Require Subscription ] in the AEC Settings!</strong>')
define( '_AEC_HACKS_REG2',						'Diese &Auml;nderung leitet den Benutzer zur Abo&uuml;bersicht weiter <strong>nachdem er sich registriert hat</strong>.<br />Falls vor der Registrierung die Aboauswahl zur Auswahl angezeigt werden soll, gen&uuml;gt diese &Auml;nderung (AEC-Konfiguration [ Ben&ouml;tigt Abo ] muss dann aktiviert sein), andernfalls <strong>sind noch 2 weitere &Auml;nderungen durchzuf&uuml;hren!</strong><br />Falls die Aboauswahl <strong>vor</strong> den Benutzerdetails angezeigt werden soll, m&uuml;ssen alle 3 &Auml;nderungen durchgef&uuml;hrt werden.' ); //  This will redirect a registering user to the payment plans after filling out the registration form. Leave this alone to have plan selection only on login (if \'Require Subscription\' is active), or completely voluntary (without requiring a subscription). <strong>Please note that there are two hacks following this, once you have committed it! If you want to have the plans before the user details, these are required as well.</strong>
define( '_AEC_HACKS_REG3',						'Leitet den Benutzer direkt zur Abo&uuml;bersicht um falls vorher noch keine Wahl getroffen wurde' ); // This will redirect the user to the payment plans page when he or she has not made that selection yet.
define( '_AEC_HACKS_REG4',						'&Uml;bermittelt die AEC-Daten aus dem Anmeldeforumlar' ); // This Hack will transmit the AEC variables from the user details form.
define( '_AEC_HACKS_MI1',						'Einige Integrationen ben&ouml;tigen Klartextdaten.<br />Diese &Auml;nderung stellt sicher, dass die Integrationen die Benutzerdaten bei &Auml;nderung erhalten.' ); // Some Micro Integrations rely on receiving a cleartext password for each user. This hack will make sure that the Micro Integrations will be notified when a user changes his/her account.
define( '_AEC_HACKS_MI2',						'Einige Integrationen ben&ouml;tigen Klartextdaten.<br />Diese &Auml;nderung &uuml;bermittelt die Benutzerdaten nach der Registrierung' ); // Some Micro Integrations rely on receiving a cleartext password for each user. This hack will make sure that the Micro Integrations will be notified when a user registers an account.
define( '_AEC_HACKS_MI3',						'Einige Integrationen ben&ouml;tigen Klartextdaten.<br />Diese &Auml;nderung stellt sicher, dass bei Benutzerdaten&auml;nderung durch einen Admin diese weitergeleitet werden.' ); // Some Micro Integrations rely on receiving a cleartext password for each user. This hack will make sure that the Micro Integrations will be notified when an admin changes a user-account.

// log
	// settings
define( '_AEC_LOG_SH_SETT_SAVED',				'&Auml;nderung Einstellungen' ); // settings change
define( '_AEC_LOG_LO_SETT_SAVED',				'Die Einstellungen von AEC wurden gesichert' ); // The Settings for the AEC have been saved
	// heartbeat
define( '_AEC_LOG_SH_HEARTBEAT',				'Heartbeat' ); // Heartbeat
define( '_AEC_LOG_LO_HEARTBEAT',				'Heartbeataktion:' ); // Heartbeat carried out:
define( '_AEC_LOG_AD_HEARTBEAT_DO_NOTHING',		'keine' ); // does nothing
	// install
define( '_AEC_LOG_SH_INST',						'AEC Installierung' ); // AEC install
define( '_AEC_LOG_LO_INST',						'AEC Version %s wurde installiert' ); // The AEC Version %s has been installed

// install texts
define( '_AEC_INST_NOTE_IMPORTANT',				'Wichtiger Hinweis' ); // Important Notice
define( '_AEC_INST_NOTE_SECURITY',				'Um das CMS <strong>sicher zu bertreiben</strong> ist es notwendig einige &Auml;nderungen an den Stammdateien zu machen.<br />Mit dieser version von AEC wird eine Funktion mitgeliefert die exakt diese Aufgabe &uuml;bernimmt, daf&uuml;r bitte auch den nachfolgenden Link klicken' ); // For <strong>your system security</strong> you need apply hacks to joomla core files. For your convenience, we have included an autohacking feature that does just that with the click of a link
define( '_AEC_INST_APPLY_HACKS',				'Um die erforderlichen &Auml;nderungen durchzuf&uuml;hren bitte <a href="' .  $mosConfig_live_site . '/administrator/index2.php?option=com_acctexp&amp;task=hacks" target="_blank">hier klicken</a><br />Dieser Link kann auch sp&aumL;ter jederzeit aufgerufen werden - siehe AEC Verwaltung' ); // In order to commit these hacks right now, go to the <a href="' . '/administrator/index2.php?option=com_acctexp&task=hacks" target="_blank">hacks page</a>. (You can also access this page later on from the AEC central view or menu)
define( '_AEC_INST_NOTE_UPGRADE',				'<strong>Falls ein bestehendes AEC upgegraded werden soll, bitte auf alle F&auml;lle das Men&uuml; "Spezial" aufrufen - es gibt immer wieder neue &Auml;nderungen' ); // <strong>If you are upgrading, make sure to check the hacks page anyways, since there are changes from time to time!!!</strong>
define( '_AEC_INST_NOTE_HELP',					'Um die wichtigsten Antworten auf Fragen bereit zu haben kann jederzeit die interne <a href="' . $mosConfig_live_site . '/administrator/index2.php?option=com_acctexp&task=help" target="_blank">Hilfe</a>  aufgerufen werden (aufruf auch von der AEC-Verwaltung aus). Dort stehen auf weitere Tips zur nachfolgenden Einrichtung von AEC' ); // To help you along with frequently encountered problems, we have created a <a href="' . $mosConfig_live_site . '/administrator/index2.php?option=com_acctexp&task=help" target="_blank"><strong>help function</strong></a> that will help you ship around the most common setup problems (access is also avaliable from the AEC central.')
define( '_AEC_INST_HINTS',						'Hinweise' ); // Hints
define( '_AEC_INST_HINT1',						'Wir w&auml;rden uns freuen wenn Sie das <a href="http://www.globalnerd.org/index.php?option=com_wrapper&amp;Itemid=53" target="_blank">Forum</a> besuchen. Neben Diskussionen k&ouml;nnen auch weitere Tips, Anregungen usw. dort nachgelesen werden' ); // We encourage you to visit the <a href="http://www.globalnerd.org/index.php?option=com_wrapper&amp;Itemid=53" target="_blank">globalnerd.org forums</a> and to <strong>participate in the discussion there</strong>. Chances are, that other users have found the same bugs and it is equally likely that there is at least a fix to hack in or even a new version.
define( '_AEC_INST_HINT2',						'Auf alle F&auml;lle (und ganz speziell wenn sie AEC auf einer Liveseite einsetzen), gehen Sie in Ruhe alle einstellungen durch, legen einen Testzugang an und testen die Zahlvorg&auml;nge!' ); // In any case (and especially if you use this on a live site): go through your settings and make a test subscription to see whether everything is working to your satisfaction! Although we try our best to make upgrading as flawless as possible, some fundamental changes to our program may not be possible to cushion for all users.
define( '_AEC_INST_ATTENTION',					'Immer die aktuellsten Programme von und f&uuml;r AEC einsetzen' ); // No need to use the old logins!
define( '_AEC_INST_ATTENTION1',					'Falls noch &auml;ltere AEC-Loginmodule in Verwendung sind, bitte deinstallieren und gegen die regul&auml;ren austauschen (egal ob Joomla, mambo, CB, etc.)' ); // If you still have the old AEC login modules installed, please uninstall it (no matter which one, regular or CB) and use the normal joomla or CB login module. There is no need to use these old modules anymore.

// already in english file
DEFINE ('_COUPON_CODE',							'Gutscheincode');

// --== COUPON EDIT ==--
DEFINE ('_COUPON_DETAIL_TITLE',					'Gutschein');
DEFINE ('_COUPON_RESTRICTIONS_TITLE',			'Einschr.');
DEFINE ('_COUPON_RESTRICTIONS_TITLE_FULL',		'Einschr&auml;nkungen');
DEFINE ('_COUPON_MI',							'Zahlsystem');
DEFINE ('_COUPON_MI_FULL',						'Bezahlsysteme');

DEFINE ('_COUPON_GENERAL_NAME_NAME',			'Name');
DEFINE ('_COUPON_GENERAL_NAME_DESC',			'Der interne und externe Name f&uuml;r diesen Gutschein');
DEFINE ('_COUPON_GENERAL_COUPON_CODE_NAME',		'Gutscheincode');
DEFINE ('_COUPON_GENERAL_COUPON_CODE_DESC',		'Den Gutscheincode hier eintragen - der angezeigte (zuf&auml;llig generierte) Code wurde vom System erzeugt.<hr /><strong>Hinweis:</strong><br />Der COde muss einmalig sein!');
DEFINE ('_COUPON_GENERAL_DESC_NAME',			'Beschreibung');
DEFINE ('_COUPON_GENERAL_DESC_DESC',			'Die interne Beschreibung f&uuml;r diesen Gutschein');
DEFINE ('_COUPON_GENERAL_ACTIVE_NAME',			'Aktiv');
DEFINE ('_COUPON_GENERAL_ACTIVE_DESC',			'Ist dieser Gutschein aktiv (momentan g&uuml;ltig)');
DEFINE ('_COUPON_GENERAL_TYPE_NAME',			'Gruppe');
DEFINE ('_COUPON_GENERAL_TYPE_DESC',			'Soll dieser Gutschein einmalig oder f&uuml;r eine Gruppe von mehreren Personen gelten (Einzel- oder Gruppengutschein)');

DEFINE ('_COUPON_GENERAL_MICRO_INTEGRATIONS_NAME',	'Bezahlsysteme');
DEFINE ('_COUPON_GENERAL_MICRO_INTEGRATIONS_DESC',	'Diejenigen Bezahlsysteme ausw&auml;hlen welche f&uuml;r diesen Gutschein g&uuml;ltig sein sollen');

DEFINE ('_COUPON_PARAMS_AMOUNT_USE_NAME',		'Betrag verwenden');
DEFINE ('_COUPON_PARAMS_AMOUNT_USE_DESC', 		'Soll von der Rechnung direkt ein Betrag abgezogen werden');
DEFINE ('_COUPON_PARAMS_AMOUNT_NAME',			'Betrag');
DEFINE ('_COUPON_PARAMS_AMOUNT_DESC',			'Hier den Betrag angeben welcher direkt von der Rechnung abgezogenw erden soll');
DEFINE ('_COUPON_PARAMS_AMOUNT_PERCENT_USE_NAME',	'Prozente');
DEFINE ('_COUPON_PARAMS_AMOUNT_PERCENT_USE_DESC',	'Sollen x Prozente vom Rechnungsbetrag abgezogen werden');
DEFINE ('_COUPON_PARAMS_AMOUNT_PERCENT_NAME',	'Prozent in %');
DEFINE ('_COUPON_PARAMS_AMOUNT_PERCENT_DESC',	'Hier angeben wieviele Prozente vom betrag abgezogen werden sollen');
DEFINE ('_COUPON_PARAMS_PERCENT_FIRST_NAME',	'Prozente vor Betrag');
DEFINE ('_COUPON_PARAMS_PERCENT_FIRST_DESC',	'Wenn die Kombination von Prozente und Betrag angewendet werden soll, solen die Prozente vorher abgezogen werden?');
DEFINE ('_COUPON_PARAMS_USEON_TRIAL_NAME',		'Bei Testabo?');
DEFINE ('_COUPON_PARAMS_USEON_TRIAL_DESC',		'Sollen die Benutzer diesen Diskont auch bei einerm testabo auswa&auml;hlen d&uuml;rfen?');
DEFINE ('_COUPON_PARAMS_USEON_FULL_NAME',		'Bei Vollabo?');
DEFINE ('_COUPON_PARAMS_USEON_FULL_DESC',		'Sollen die Benutzer diesen Diskont vom aktuellen betrag abzihen k&ouml;nnen? (bei wiederholenden Abos wird nur vom ersten Rechnungsbetrag der Diskont abgezogen!)');
DEFINE ('_COUPON_PARAMS_USEON_FULL_ALL_NAME',	'Jede Rechnung?');
DEFINE ('_COUPON_PARAMS_USEON_FULL_ALL_DESC',	'Falls der Benutzer wiederholende Abos besitzt, solle der Diskont jedes Mal abgezogen werden? Wenn nur beim ersten Mal dann Nein)');

DEFINE ('_COUPON_PARAMS_HAS_START_DATE_NAME',	'Beginndatum');
DEFINE ('_COUPON_PARAMS_HAS_START_DATE_DESC',	'Soll der Gutschein f&uuml;r einen bestimmtern Zeitraum gelten?');
DEFINE ('_COUPON_PARAMS_START_DATE_NAME',		'Datum');
DEFINE ('_COUPON_PARAMS_START_DATE_DESC',		'Beginndatum der Periode ausw&auml;hlen f&uuml;den dieser Gutschein g&uuml;ltig sein soll');
DEFINE ('_COUPON_PARAMS_HAS_EXPIRATION_NAME',	'Ablaufdatum');
DEFINE ('_COUPON_PARAMS_HAS_EXPIRATION_DESC',	'Soll dieser Gutschein mit Datum x auslaufen?');
DEFINE ('_COUPON_PARAMS_EXPIRATION_NAME',		'Datum');
DEFINE ('_COUPON_PARAMS_EXPIRATION_DESC',		'Datum ausw&auml;hlen bis wann dieser Gutschein g&uuml;ltig sein soll');
DEFINE ('_COUPON_PARAMS_HAS_MAX_REUSE_NAME',	'Eingeschr&auml;nkt?');
DEFINE ('_COUPON_PARAMS_HAS_MAX_REUSE_DESC',	'Soll dieser max. x verwendet werden d&uuml;rfen?');
DEFINE ('_COUPON_PARAMS_MAX_REUSE_NAME',		'Anzahl');
DEFINE ('_COUPON_PARAMS_MAX_REUSE_DESC',		'Hier die Anzahl eintragen wieoft dieser Gutschein verwendet werden darf');

DEFINE ('_COUPON_PARAMS_USECOUNT_NAME',			'Reset');
DEFINE ('_COUPON_PARAMS_USECOUNT_DESC',			'Hier kann der Z&auml;hler r&uuml;ckgesetzt werden');

DEFINE ('_COUPON_PARAMS_USAGE_PLANS_ENABLED_NAME',	'Abo');
DEFINE ('_COUPON_PARAMS_USAGE_PLANS_ENABLED_DESC',	'Soll dieser Gutschein nur f&uuml;r ein bestimmtes Abo gelten?');
DEFINE ('_COUPON_PARAMS_USAGE_PLANS_NAME',			'Abos');
DEFINE ('_COUPON_PARAMS_USAGE_PLANS_DESC',			'Welche Abos werden angewendet');

DEFINE ('_COUPON_RESTRICTIONS_MINGID_ENABLED_NAME', 'Mindest Gruppen ID:');
DEFINE ('_COUPON_RESTRICTIONS_MINGID_ENABLED_DESC', 'Hier die Mindestgruppen ID definieren f&uuml;r welche dieser Gutschein g&uuml;ltig sein soll');
DEFINE ('_COUPON_RESTRICTIONS_MINGID_NAME',			'Sichtbare Gruppe:');
DEFINE ('_COUPON_RESTRICTIONS_MINGID_DESC',			'Die Mindestbenutzerebene welche diesen Gutschein einsetzen kann');
DEFINE ('_COUPON_RESTRICTIONS_FIXGID_ENABLED_NAME', 'Fixe Gruppen ID:');
DEFINE ('_COUPON_RESTRICTIONS_FIXGID_ENABLED_DESC', 'Soll dieser Gutschein nur f&uuml;r eine bestimmte Gruppe gelten');
DEFINE ('_COUPON_RESTRICTIONS_FIXGID_NAME',			'Gruppe:');
DEFINE ('_COUPON_RESTRICTIONS_FIXGID_DESC',			'Nur Benutzer dieser Gruppe k&ouml;nnen diesen Gutschein einsetzen');
DEFINE ('_COUPON_RESTRICTIONS_MAXGID_ENABLED_NAME', 'Maximale Gruppen ID:');
DEFINE ('_COUPON_RESTRICTIONS_MAXGID_ENABLED_DESC', 'Welche maximale Gruppen ID darf diesen Gutschein verwenden d&uuml;rfen?');
DEFINE ('_COUPON_RESTRICTIONS_MAXGID_NAME',			'Gruppe:');
DEFINE ('_COUPON_RESTRICTIONS_MAXGID_DESC',			'Die am h&ouml;chsten eingestufte Gruppe welche diesen Gutschein einsetzen darf');

DEFINE ('_COUPON_RESTRICTIONS_PREVIOUSPLAN_REQ_ENABLED_NAME',	'Voriges Abo:');
DEFINE ('_COUPON_RESTRICTIONS_PREVIOUSPLAN_REQ_ENABLED_DESC',	'Wird f&uuml;r diesen Gutschein ein bestimmtes Abo vorher ben&ouml;tigt');
DEFINE ('_COUPON_RESTRICTIONS_PREVIOUSPLAN_REQ_NAME',			'Abo:');
DEFINE ('_COUPON_RESTRICTIONS_PREVIOUSPLAN_REQ_DESC',			'Benutzer welche dieses Abo schon einmal verwendet hatten sind f&uuml;r diesen Gutschein berechtigt');
DEFINE ('_COUPON_RESTRICTIONS_CURRENTPLAN_REQ_ENABLED_NAME',	'Aktuelles Abo:');
DEFINE ('_COUPON_RESTRICTIONS_CURRENTPLAN_REQ_ENABLED_DESC',	'Aktuelles Abo ist mindestens Voraussetzung');
DEFINE ('_COUPON_RESTRICTIONS_CURRENTPLAN_REQ_NAME',			'Abo:');
DEFINE ('_COUPON_RESTRICTIONS_CURRENTPLAN_REQ_DESC',			'Nur Benutzer welche aktuell im Besitz dieses Abos sind oder es schon mal vorher hatten d&uuml;rfen diesen Gutschein verwenden');
DEFINE ('_COUPON_RESTRICTIONS_OVERALLPLAN_REQ_ENABLED_NAME',	'Erforderlich:');
DEFINE ('_COUPON_RESTRICTIONS_OVERALLPLAN_REQ_ENABLED_DESC',	'Akivieren wenn Abo vorher erforderlich ist');
DEFINE ('_COUPON_RESTRICTIONS_OVERALLPLAN_REQ_NAME',			'Abo:');
DEFINE ('_COUPON_RESTRICTIONS_OVERALLPLAN_REQ_DESC',			'Nur Benutzer welche schon irgendwan mal vorher dieses Abo verwendet hatten d&uuml;rfen diesen Gutschein verwenden');

DEFINE ('_COUPON_RESTRICTIONS_USED_PLAN_MIN_ENABLED_NAME',		'Mind. Aboanzahl:');
DEFINE ('_COUPON_RESTRICTIONS_USED_PLAN_MIN_ENABLED_DESC',		'Aktivieren wenn Benutzer schon vorher x-mal ein bestimmtes Abo verwendet haben m&uuml;ssen');
DEFINE ('_COUPON_RESTRICTIONS_USED_PLAN_MIN_AMOUNT_NAME',		'Anzahl:');
DEFINE ('_COUPON_RESTRICTIONS_USED_PLAN_MIN_AMOUNT_DESC',		'Die Mindestanzahl der bisherigen Verwendungen des angegebenen Abos');
DEFINE ('_COUPON_RESTRICTIONS_USED_PLAN_MIN_NAME',				'Abo:');
DEFINE ('_COUPON_RESTRICTIONS_USED_PLAN_MIN_DESC',				'Das Abo welches der Benutzer schon vorher x-mal verwendet haben muss');
DEFINE ('_COUPON_RESTRICTIONS_USED_PLAN_MAX_ENABLED_NAME',		'Abo:');
DEFINE ('_COUPON_RESTRICTIONS_USED_PLAN_MAX_ENABLED_DESC',		'Maximumanzahl der bisherigen Verwendungen des angegebenen Abos um diesen Gutschein einsetzen zu d&uuml;rfen');
DEFINE ('_COUPON_RESTRICTIONS_USED_PLAN_MAX_AMOUNT_NAME',		'Anzahl:');
DEFINE ('_COUPON_RESTRICTIONS_USED_PLAN_MAX_AMOUNT_DESC',		'Maximale Anzahl der bisherigen Verwendungen dieses Abos');
DEFINE ('_COUPON_RESTRICTIONS_USED_PLAN_MAX_NAME',				'Abo:');
DEFINE ('_COUPON_RESTRICTIONS_USED_PLAN_MAX_DESC',				'Welches Abo muss vorher verwendet werden');

// header
define( '_AEC_HEAD_PLAN_INFO',					'Abonnement' ); // Plan Info

// help
define( '_AEC_CMN_HELP',						'Hilfe' ); // Help
define( '_AEC_HELP_DESC',						'Auf dieser Seite &uuml;berpr&uuml;ft AEC die bestehende Konfiguration und zeigt eventuelle Fehler an welche bereinigt werden sollten' ); // On this page, the AEC takes a look at its own configuration and tells you whenever it finds errors that need to be adressed.
define( '_AEC_HELP_GREEN',						'Gr&uuml;n</strong> bedeutet Mitteilungen oder Probleme die bereits gel&ouml;st wurden' ); // Green</strong> items indicate trivial problems or notifications, or problems that have already been solved.
define( '_AEC_HELP_YELLOW',						'Gelb</strong> weist haupts&auml;chlich auf kosmetische Punkte hin (z.B. Benutzer zum Fronent hinzuf&uuml;gen), aber auch Punkte die im Ermessen des Admin liegen' ); // Yellow</strong> items are mostly of cosmetical importance (like additions to the user frontend), but also issues that are most likely a deliberate choice of the administrator.
define( '_AEC_HELP_RED',						'Rot</strong> weist auf Probleme bez&uuml;glich Sicherheit oder anderer Wichtigkeit hin' ); // Red</strong> items are of high importance to either the way the AEC works or the security of your System.')
define( '_AEC_HELP_GEN',						'Hinweis: es wird versucht so viel wie m&ouml;glich zu &uuml;berpr&uuml;fen, dennoch besteht kein Anspruch auf Vollst&auml;ndigkeit!' ); // Please note that even though we try to cover as many errors and problems as possible, this page can only point at the most obvious ones and is by far not completed yet (beta&trade;)')
define( '_AEC_HELP_QS_HEADER',					'AEC Schnellstart Handbuch' ); // AEC Quickstart Manual
define( '_AEC_HELP_QS_DESC',					'Bevor mit den unten angef&uuml;hrten Aktionen fortgefahren wird, sollte eventuell vorher das <a href="' . $mosConfig_live_site . '/administrator/components/com_acctexp/manual/AEC_Quickstart.pdf" target="_blank" title="Handbuch">Handbuch</a> gelesen werden' ); // Before doing anything about the below issues, please read our <a href="' . $mosConfig_live_site . '"/administrator/components/com_acctexp/manual/AEC_Quickstart.pdf" target="_blank">Quickstart Manual</a>
define( '_AEC_HELP_SER_SW_DIAG1',				'Dateirechteproblem' ); // File Permissions Problems
define( '_AEC_HELP_SER_SW_DIAG1_DESC',			'AEC hat den Server als Apache Webserver identifiziert. Um &Auml;nderungen an diesen Dateien durchf&uuml;hren zu k&ouml;nnen, m&uuml;ssen diese dem Webserverbenutzer geh&ouml;ren was momentan offensichtlich nicht so ist.' ); // The AEC has detected that you are using an Apache Webserver - To be able to hack files on such a server, those files have to be owned by the webserver user, which apparently is not so for at least one of the neccessary files.
define( '_AEC_HELP_SER_SW_DIAG1_DESC2',			'Es wird empfohlen f&uuml;r die Dauer der &Auml;nderungen die Dateirechte auf 0777 zu &auml;ndern. Nach Durchf&uuml;hrung der &Anderung m&uuml;ssen diese Rechte wieder auf den Originalzustand zur&uuml;ckgesetzt werden!<br />Dies gilt auch f�r die weiter unten erw&auml;hnten Dateirechte.' ); // We recommend that you temporarily change the file permissions to 777, then commit the hacks and change it back after that. <strong>Contact your server host or administrator for the possibly quickest response when experiencing problems.</strong> This is the same for the file permission related suggestion(s) below.
define( '_AEC_HELP_SER_SW_DIAG2',				'CMS Dateirechte' ); // joomla.php/mambo.php File Permissions
define( '_AEC_HELP_SER_SW_DIAG2_DESC',			'AEC hat erkannt, dass dieses CMS nicht die Rechte des Webservers besitzt.' ); // The AEC has detected that your joomla.php is not owned by the webserver.
define( '_AEC_HELP_SER_SW_DIAG2_DESC2',			'Wenn Sie einen SSH-Zugang zum Server besitzen, gehen Sie in das Verzeichnis "<cmsinstallation/includes>" und geben dann entweder "chown wwwrun joomla.php" (oder "chown wwwrun mambo.php" - falls Mambo verwendet wird) ein.' ); // Access your webserver via ssh and go to the directory \"<yoursiteroot>/includes\". There, type in this command: \"chown wwwrun joomla.php\" (or \"chown wwwrun mambo.php\" in case you are using mambo).
define( '_AEC_HELP_SER_SW_DIAG3',				'&Auml;nderungen erkannt' ); // Legacy Hacks Detected!
define( '_AEC_HELP_SER_SW_DIAG3_DESC',			'Es sieht so aus als wenn die vorhandenen &Auml;nderungen nicht aktuell sein d&uuml;rften! Damit AEC ordnungsgem&auml;ss funktionieren kann, sollten diese &Auml;nderungen nochmals gepr&uuml;ft werden (sehen Sie dazu auf der AEC Webseite nach)' ); // You appear to have old Hacks commited to your System.", "In Order for the AEC to function correctly, please review the Hacks page and follow the steps presented there.
define( '_AEC_HELP_SER_SW_DIAG4',				'Dateirechte Probleme' ); // File Permissions Problems
define( '_AEC_HELP_SER_SW_DIAG4_DESC',			'AEC kann die Schreibrechte der Dateien welche ge&auml;ndert werden m&uuml;ssen nicht erkennen. Entweder ist das hier ein WINDOWS-Server oder der Apacheserver wurde mit der Option "--disable-posix" kompiliert.<br /><strong>Sollten die &Auml;nderungen durchgef&uuml;hrt werden, dann jedoch nicht funktionieren liegt das Problem bei den Dateirechten</strong>' ); // The AEC can not detect the write permission status of the files it wants to hack as it appears that your installation of php has been compiled with the option \"--disable-posix\". <strong>You can still try to commit the hacks - if it does not work, its most likely a file permission problem</strong>.
define( '_AEC_HELP_SER_SW_DIAG4_DESC2',			'Es wird empfohlen entweder den Server mit der erw&auml;hnten Option zu kompilieren (Apache) oder den Admin zu kontaktieren' ); // We recommend that you either recompile your php version with the said option left out or contact your webserver administrator on this matter.
define( '_AEC_HELP_DIAG_CMN1',					'CMS &Auml;nderungen' ); // joomla.php/mambo.php Hack
define( '_AEC_HELP_DIAG_CMN1_DESC',				'Notwendig damit die Benutzer nach dem Login von AEC &uuml;berpr&uuml;ft werden k&oum;nnen' ); // In order for the AEC to function, this hack is required to redirect users to the AEC Verification Routines on Login.
define( '_AEC_HELP_DIAG_CMN1_DESC2',			'Zur Spezialseite gehen und &Auml;nderung durchf&uuml;hren' ); // Go to the Hacks page and commit the hack
define( '_AEC_HELP_DIAG_CMN2',					'Meine Abos - Men&uuml;eintrag' ); // MySubscription Menu Entry
define( '_AEC_HELP_DIAG_CMN2_DESC',				'Ein Link der die Benutzer zu ihren eigenen Abonnements f&uuml;hrt' ); // A link to a MySubscription page for your users makes it easy for them to track their subscription.
define( '_AEC_HELP_DIAG_CMN2_DESC2',			'Zur Spezialseite gehen und Link erstellen' ); // Go to the Hacks page and create the link.')
define( '_AEC_HELP_DIAG_CMN3',					'JACL nicht installiert' ); // JACL not installed
define( '_AEC_HELP_DIAG_CMN3_DESC',				'Sollte geplant sein, JACLPlus (oder &auml;hnliches) zu installieren, muss auf die AEC-&Auml;nderungen R&uuml;cksicht genommen werden! Sollten diese &Auml;nderungen bereits durchgef&uuml;hrt worden sein, kann dies auf der Spezialseite ge&auml;ndert werden' ); // If you plan to install JACLplus in your joomla!/mambo system, please make sure that the AEC hacks are not committed when doing so. If you have already committed them, you can easily undo them in our hacks page.

// micro integration
	// general
define( '_AEC_MI_REWRITING_INFO',				'Vorlagen Platzhalter' ); // Rewriting Info
	// htaccess
define( '_AEC_MI_HTACCESS_INFO_DESC',			'Sch&uuml;tzt ein Verzeichnis mit einer .htaccess Datei und erlaubt den Zugriff nur Benutzern dieses Abos' ); // xx_TEST_xx Protect a folder with a .htaccess file and only allow users of this subscription to access it with their joomla account details.)

	// email
define( '_AEC_MI_EMAIL_INFO_DESC',				'Sended an eine oder mehrere Adressen den Status des Abos' ); // Send an Email to one or more adresses on application or expiration of the subscription

	// idev
define( '_AEC_MI_IDEV_DESC',					'Verbindet den Verkauf mit iDevAffiliate' ); // Connect your sales to iDevAffiliate

	// mosetstree
define( '_AEC_MI_MOSETSTREE_DESC',				'Beschr&auml;nkt die Anzahl der anzuzeigenden Links die ein Benutzer ver&ouml;ffentlichen kann' ); // Restrict the amount of listings a user can publish

	// mysql-query
define( '_AEC_MI_MYSQL_DESC',					'Spezifiziert einen My-SQL-String welcher bei Abo-Beendigung ausgef&uuml;hrt wird'); // Specify a mySQL query that should be carried out with this subscription or on its expiration

	// remository
define( '_AEC_MI_REMOSITORY_DESC',				'Definiert die max. Dateianzahl die ein Benutzer in reMOSitory downloaden kann' ); // Choose the amount of files a user can download and what reMOSitory group should be assigned to the user account
	// VirtueMart
define( '_AEC_MI_VIRTUEMART_DESC',				'Definiert die VirtueMart-Benutzergruppe welcher der Benutzer angeh&ouml;hren soll' ); // Choose the VM usergroup this user should be applied to

// central
define( '_AEC_CENTR_CENTRAL',					'AEC Zentrale' ); // AEC Central
define( '_AEC_CENTR_EXCLUDED',					'Ausgeschlossen' ); // Excluded
define( '_AEC_CENTR_PLANS',						'Abos' ); // Plans
define( '_AEC_CENTR_PENDING',					'Wartend' ); // Pending
define( '_AEC_CENTR_ACTIVE',					'Aktiv' ); // Active
define( '_AEC_CENTR_EXPIRED',					'Abgelaufen' ); // Expired
define( '_AEC_CENTR_CANCELLED',					'Stornos' ); // Cancelled
define( '_AEC_CENTR_CLOSED',					'Beendet' ); // Closed
define( '_AEC_CENTR_SETTINGS',					'Einstellungen' ); // Settings
define( '_AEC_CENTR_EDIT_CSS',					'CSS Bearbeiten' ); // Edit CSS
define( '_AEC_CENTR_V_INVOICES',				'Rechnungen' ); // View Invoices
define( '_AEC_CENTR_COUPONS',					'Einzelgutscheine' ); // Coupons
define( '_AEC_CENTR_COUPONS_STATIC',			'Gruppengutscheine' ); // Coupons Statis
define( '_AEC_CENTR_VIEW_HISTORY',				'Archiv' ); // View History
define( '_AEC_CENTR_SPECIAL',					'Spezial' ); // Hacks
define( '_AEC_CENTR_M_INTEGRATION',				'Zahlsysteme' ); // Micro Integr.
define( '_AEC_CENTR_HELP',						'Hilfe' );
define( '_AEC_CENTR_LOG',						'Logdatei' ); // EventLog

// select lists
define( '_AEC_SEL_EXCLUDED',					'Ausgeschlossen' ); // Excluded
define( '_AEC_SEL_PENDING',						'Wartend' ); // Pending
define( '_AEC_SEL_ACTIVE',						'Aktiv' );
define( '_AEC_SEL_EXPIRED',						'Angelaufen' ); // Expired
define( '_AEC_SEL_CLOSED',						'Geschlossen' ); // Closed
define( '_AEC_SEL_CANCELLED',					'Storniert' );
define( '_AEC_SEL_NOT_CONFIGURED',				'Ni. Konfiguriert' ); // Not Configured

// footer
define( '_AEC_FOOT_TX_CHOOSING',				'Danke dass Sie sich f&uuml;r AEC- Account Expiration Control entschieden haben!' ); // Thank you for choosing the Account Expiration Control Component!
define( '_AEC_FOOT_TX_GPL',						'Diese Komponente wurde entwickelt und ver&ouml;ffentlicht unter der <a href="http://www.gnu.org/copyleft/gpl.html" target="_blank" title="GNU/GPL">GNU/GPL</a> von Helder Garcia und David Deutsch von <a href="http://www.globalnerd.org" target="_blank" title="globalnerd.org">globalnerd.org</a>'); // This Joomla/Mambo component was developed and released under the <a href="http://www.gnu.org/copyleft/gpl.html">GNU/GPL</a> license by Helder Garcia and David Deutsch from <a href="http://www.globalnerd.org">globalnerd.org</a>
define( '_AEC_FOOT_TX_SUBSCRIBE',				'Wenn Sie mehr Features m&ouml;chten, professionelles Service, Updates, Handb&uuml;cher und Online Hilfe, klicken Sie uaf den Link oben. Es hilft uns bei der weiteren Entwicklung!' ); // If you want more features, professional service, updates, manuals and online help for this component, you can subscribe to our services at the above link. It helps us a lot in our development!
define( '_AEC_FOOT_CREDIT',						'Bitte lesen Sie auch die <a href="' . $mosConfig_live_site . '/administrator/index2.php?option=com_acctexp&amp;amp;task=credits">Credits</a>'); // Please read our <a href="' . $mosConfig_live_site . '/administrator/index2.php?option=com_acctexp&amp;amp;task=credits">full credits
define( '_AEC_FOOT_VERSION_CHECK',				'Check auf neue Version' ); // Check for latest Version
define( '_AEC_FOOT_MEMBERSHIP',					'Mitglied werden und haben Zugang zu Dokumentationen und Support bekommen' ); // Get a membership with documentation and support

// alerts
define( '_AEC_ALERT_SELECT_FIRST',				'Bitte vorher eine Auswahl treffen!' ); // Select an item to configure
define( '_AEC_ALERT_SELECT_FIRST_TO',			'Bitte vorher eine Auswahl treffen um' ); // Please select first an item to

// messages
define( '_AEC_MSG_NODELETE_SUPERADMIN',			'Superadministratoren k&ouml;nnen nicht gel&ouml;scht werden!' ); // You cannot delete a Super Administrator
define( '_AEC_MSG_NODELETE_YOURSELF',			'Sich selber l&ouml;schen macht auch keinen Sinn - oder?' ); // You cannot delete Yourself!
define( '_AEC_MSG_NODELETE_EXCEPT_SUPERADMIN',	'Nur Superadmins k&ouml;nnen diese Aktion ausf&uuml;hren!' ); // Only Superadmins can perform this action!
define( '_AEC_MSG_SUBS_RENEWED',				'Abonnement(s) erneuert' ); // subscription(s) renewed
define( '_AEC_MSG_SUBS_ACTIVATED',				'Abonnement(s) aktiviert' ); // subscription(s) activated
define( '_AEC_MSG_NO_ITEMS_TO_DELETE',			'Keine Datens&auml;tze zum l&ouml;schen vorhanden' ); // No item found to delete
define( '_AEC_MSG_NO_DEL_W_ACTIVE_SUBSCRIBER',	'Abos mit aktiven Abonnenten k&ouml;nnen nicht ge&l&ouml;scht werden!' ); // You cannot delete plans with active subscribers
define( '_AEC_MSG_ITEMS_DELETED',				'Datens&auml;tze gel&ouml;scht' ); // Item(s) deleted
define( '_AEC_MSG_ITEMS_SUCESSFULLY',			'%s Inhalte erfolgreich' ); // %s Item(s) successfully
define( '_AEC_MSG_SUCESSFULLY_SAVED',			'&Auml;nderungen erfolgreich gespeichert' ); // Changes successfully saved
define( '_AEC_MSG_ITEMS_SUCC_PUBLISHED',		'Inhalt(e) erfolgreich ver&ouml;ffentlicht' ); // Item(s) successfully Published
define( '_AEC_MSG_ITEMS_SUCC_UNPUBLISHED',		'Inhalt(e) Ver&ouml;ffentlichung erfolgreich zur&uuml;ck genommen' ); // Item(s) successfully Unpublished
define( '_AEC_MSG_NO_COUPON_CODE',				'Es muss ein Gutscheincode angegeben werden!' ); // You must specify a coupon code!
define( '_AEC_MSG_OP_FAILED',					'Vorgang fehlgeschlagen, konnte %s nicht &ouml;ffnen' ); // Operation Failed: Could not open %s
define( '_AEC_MSG_OP_FAILED_EMPTY',				'Vorgang fehlgeschlagen, kein Inhalt' ); // Operation failed: Content empty
define( '_AEC_MSG_OP_FAILED_NOT_WRITEABLE',		'Vorgang fehlgeschlagen, Datei nicht beschreibbar' ); // Operation failed: The file is not writable.')
define( '_AEC_MSG_OP_FAILED_NO_WRITE',			'Vorgang fehlgeschlagen, Datei kann nicht zum Schreiben ge&oumL;ffnet werden' ); // Operation failed: Failed to open file for writing.
define( '_AEC_MSG_INVOICE_CLEARED',				'Rechnung bereinigt' ); // Invoice cleared

// languages (e.g. PayPal) - must be ISO 3166 Two-Character Country Codes
define( '_AEC_LANG_DE',							'Deutsch' ); // German
define( '_AEC_LANG_GB',							'Englisch' ); // English
define( '_AEC_LANG_FR',							'Franz&ouml;sisch' ); // French
define( '_AEC_LANG_IT',							'Italienisch' ); // Italian
define( '_AEC_LANG_ES',							'Spanisch' ); // Spanish

// END MIC ####################################################

// --== BACKEND TOOLBAR ==--
DEFINE ('_EXPIRE_SET',				'Ablaufdatum:');
DEFINE ('_EXPIRE_NOW','Jetzt');
DEFINE ('_EXPIRE_EXCLUDE','Herausnehmen');
DEFINE ('_EXPIRE_INCLUDE','Wiedereinstellen');
DEFINE ('_EXPIRE_CLOSE','Schlie&szlig;en');
DEFINE ('_EXPIRE_01MONTH','in 1 Monat');
DEFINE ('_EXPIRE_03MONTH','in 3 Monaten');
DEFINE ('_EXPIRE_12MONTH','in 12 Monaten');
DEFINE ('_EXPIRE_ADD01MONTH','plus 1 Monat');
DEFINE ('_EXPIRE_ADD03MONTH','plus 3 Monate');
DEFINE ('_EXPIRE_ADD12MONTH','plus 12 Monate');
DEFINE ('_CONFIGURE','Konfigurieren');
DEFINE ('_REMOVE','Ausschliessen');
DEFINE ('_CNAME','Name');
DEFINE ('_USERLOGIN','Login');
DEFINE ('_EXPIRATION','Auslauf');
DEFINE ('_USERS','Benutzer');
DEFINE ('_DISPLAY','Anzeigen');
DEFINE ('_NOTSET','Ausgenommen');
DEFINE ('_SAVE','Speichern');
DEFINE ('_CANCEL','Abbrechen');
DEFINE ('_EXP_ASC',					'Auslauf A-Z');
DEFINE ('_EXP_DESC',				'Auslauf Z-A');
DEFINE ('_NAME_ASC',				'Name A-Z');
DEFINE ('_NAME_DESC',				'Name Z-A');
DEFINE ('_LOGIN_ASC',				'Login A-Z');
DEFINE ('_LOGIN_DESC',				'Login Z-A');
DEFINE ('_SIGNUP_ASC',				'Abschlussdatum A-Z');
DEFINE ('_SIGNUP_DESC',				'Abschlussdatum Z-A');
DEFINE ('_LASTPAY_ASC',				'Letzte Zahlung A-Z');
DEFINE ('_LASTPAY_DESC',			'Letzte Zahlung Z-A');
DEFINE ('_PLAN_ASC',				'Abos A-Z');
DEFINE ('_PLAN_DESC',				'Abos Z-A');
DEFINE ('_STATUS_ASC',				'Status A-Z');
DEFINE ('_STATUS_DESC',				'Status Z-A');
DEFINE ('_TYPE_ASC',				'Zahlung Type A-Z');
DEFINE ('_TYPE_DESC',				'Zahlung Type Z-A');
DEFINE ('_ORDER_BY','Sortieren nach:');
DEFINE ('_SAVED', 'Gespeichert.');
DEFINE ('_CANCELED', 'Abgebrochen.');
DEFINE ('_CONFIGURED',				'Eintrag konfiguriert.');
DEFINE ('_REMOVED',					'Eintrag aus Liste gel&ouml;scht.');
DEFINE ('_EOT_TITLE', 'Geschlossene Abonnements');
DEFINE ('_EOT_DESC', 'Diese Liste enth&auml;lt keine manuellen Abonnements, nur solche, die durch Online-Zahlung abgeschlossen wurden. Das L&ouml;schen eines Eintrages l&ouml;scht den betreffenden Benutzer aus der Datenbank und seine Eintr&auml;ge im Zahlungsverlauf.');
DEFINE ('_EOT_DATE', 'Ende des Zeitraums');
DEFINE ('_EOT_CAUSE', 'Grund');
DEFINE ('_EOT_CAUSE_FAIL', 'Zahlung fehlgeschlagen');
DEFINE ('_EOT_CAUSE_BUYER', 'Vom Benutzer abgebrochen');
DEFINE ('_EOT_CAUSE_FORCED', 'Vom Administrator abgebrochen');
DEFINE ('_REMOVE_CLOSED', 'Benutzer l&ouml;schen');
DEFINE ('_FORCE_CLOSE', 'Schliessen erzwingen');
DEFINE ('_PUBLISH_PAYPLAN', 'Ver&ouml;ffentlichen');
DEFINE ('_UNPUBLISH_PAYPLAN', 'Unver&ouml;ffentlichen');
DEFINE ('_NEW_PAYPLAN', 'Neu');
DEFINE ('_EDIT_PAYPLAN', 'Bearbeiten');
DEFINE ('_REMOVE_PAYPLAN', 'L&ouml;schen');
DEFINE ('_SAVE_PAYPLAN', 'Speichern');
DEFINE ('_CANCEL_PAYPLAN', 'Abbrechen');
DEFINE ('_PAYPLANS_TITLE', 'Zahlungsplan-Manager');
DEFINE ('_PAYPLANS_MAINDESC',		'Ver&ouml;ffentlichte Pl&auml;ne werden dem Benutzer angezeigt.');
DEFINE ('_PAYPLAN_NAME', 'Name');
DEFINE ('_PAYPLAN_DESC', 'Beschreibung (erste 50 Buchstaben)');
DEFINE ('_PAYPLAN_ACTIVE', 'Aktiv');
DEFINE ('_PAYPLAN_VISIBLE', 'Sichtbar');
DEFINE ('_PAYPLAN_A3', 'Rate');
DEFINE ('_PAYPLAN_P3', 'Zeitraum');
DEFINE ('_PAYPLAN_T3', 'Einheit des Zeitraumes');
DEFINE ('_PAYPLAN_USERCOUNT', 'Abonnenten');
DEFINE ('_PAYPLAN_EXPIREDCOUNT', 'abgelaufen');
DEFINE ('_PAYPLAN_TOTALCOUNT', 'Total');
DEFINE ('_PAYPLAN_REORDER', 'Neuordnen');
DEFINE ('_PAYPLANS_HEADER', 'Bezahlungspl&auml;ne');
DEFINE ('_PAYPLAN_DETAIL', 'Einstellungen');
DEFINE ('_ALTERNATIVE_PAYMENT',		'Bank&uuml;berweisung');
DEFINE ('_SUBSCR_DATE', 'Anmeldedatum');
DEFINE ('_ACTIVE_TITLE', 'Aktive Abonnements');
DEFINE ('_ACTIVE_DESC', 'Diese Liste enth&auml;lt keine manuellen Abonnements, nur solche, die durch ein Zahlungsgateway abgeschlossen wurden.');
DEFINE ('_LASTPAY_DATE', 'Datum der letzten Zahlung');
DEFINE ('_USERPLAN', 'Plan');
DEFINE ('_CANCELLED_TITLE', 'Abgebrochene Abonnements');
DEFINE ('_CANCELLED_DESC', 'Diese Liste enth&auml;lt keine manuellen Abonnements, nur solche, die durch ein Zahlungsgateway abgeschlossen wurden. Es sind die Abonnements, die von den Benutzern abgebrochen wurden, aber noch nicht ausgelaufen sind.');
DEFINE ('_CANCEL_DATE', 'Datum des Abbruches');
DEFINE ('_MANUAL_DESC', 'Wenn sie einen Eintrag l&ouml;schen, wird der Benutzer vollst&auml;ndig aus der Datenbank gel&ouml;scht.');
DEFINE ('_REPEND_ACTIVE',			'Wiederaufgenommen');
DEFINE ('_FILTER_PLAN',				'- Plan ausw&auml;hlen -');
DEFINE ('_BIND_USER',				'Zuweisen zu:');
DEFINE ('_PLAN_FILTER',				'Abofilter:');
DEFINE ('_CENTRAL_PAGE',			'Zentrale');

// --== USER FORM ==--
DEFINE ('_HISTORY_COL1_TITLE',			'Rechnung');
DEFINE ('_HISTORY_COL2_TITLE',			'Betrag');
DEFINE ('_HISTORY_COL3_TITLE',			'Zahlungsdatum');
DEFINE ('_HISTORY_COL4_TITLE',			'Methode');
DEFINE ('_HISTORY_COL5_TITLE',			'Aktion');
DEFINE ('_HISTORY_COL6_TITLE',			'Plan');
DEFINE ('_USERINVOICE_ACTION_REPEAT',	'Wiederholung');
DEFINE ('_USERINVOICE_ACTION_CANCEL','l&ouml;schen');
DEFINE ('_USERINVOICE_ACTION_CLEAR','als&nbsp;bezahlt&nbsp;markieren');
DEFINE ('_USERINVOICE_ACTION_CLEAR_APPLY','als&nbsp;bezahlt&nbsp;markieren&nbsp;&amp;&nbsp;Plan&nbsp;anwenden');

// --== BACKEND SETTINGS ==--

// TAB 1 - Global AEC Settings
DEFINE ('_CFG_TAB1_TITLE',				'Konfigurationsoptionen');
DEFINE ('_CFG_TAB1_SUBTITLE', 'Optionen f&uuml;r die Interaktion mit dem Benutzer');

DEFINE ('_CFG_TAB1_OPT1NAME',			'Unmittelbarer Ablauf:');
DEFINE ('_CFG_TAB1_OPT1DESC',			'Standard Ablaufzeitraum, in Tagen, f&uuml;r neue Registrierungen. Die Zahl verh&auml;lt sich relativ zum Registrierungsdatum, wenn Sie also Benutzer grunds&auml;tzlich als Abgelaufen registrieren m&ouml;chten, w&auml;hlen sie -1 (minus eins). Dies hat keinen Effekt auf den normalen Anmeldevorgang &uuml;ber ein Zahlungsgateway');
DEFINE ('_CFG_TAB1_OPT3NAME',			'Alarmebene 2:');
DEFINE ('_CFG_TAB1_OPT3DESC',			'In Tagen. Dies ist die erste Grenze die beginnt den Benutzer auf den Auslauf seines Abonnements hinzuweisen.');
DEFINE ('_CFG_TAB1_OPT4NAME',			'Alarmebene 1:');
DEFINE ('_CFG_TAB1_OPT4DESC',			'In Tagen. Dies ist die letzte Grenze die beginnt den Benutzer auf den Auslauf seines Abonnements hinzuweisen.');
DEFINE ('_CFG_TAB1_OPT5NAME',			'Einstiegsplan:');
DEFINE ('_CFG_TAB1_OPT5DESC',			'Jeder Benutzer wird - wenn keine Abonnement - ohne Bezahlung diesem Plan zugewiesen');
DEFINE ('_CFG_TAB1_OPT9NAME',			'Erfordert Einschreibung:');
DEFINE ('_CFG_TAB1_OPT9DESC',			'Wenn aktiviert, <strong>muss</strong> der Benutzer ein g&uuml;ltiges Abonnement besitzen. Nicht aktiviert, Benutzer k&ouml;nnen ohne Abo einloggen.');

DEFINE ('_CFG_TAB1_OPT10NAME', 'Gateway Erkl&auml;rungen:');
DEFINE ('_CFG_TAB1_OPT10DESC',			'Hier Bezahlm&ouml;glichkeiten markieren welche auf der Nichterlaubt-Seite angezeigt werden sollen (diese Liste sehen die Benutzer, wenn sie versuchen eine Seite anzusehen f&uuml;r die sie keine Berechtigung haben).<br />Hinweis: es werden nur die oben, zur Zeit Aktiven angezeigt');
DEFINE ('_CFG_TAB1_OPT20NAME',			'Aktivierte Zahlungsgateways:');
DEFINE ('_CFG_TAB1_OPT20DESC',			'Alle gateways markieren welche aktiv sein sollen (STRG-Taste dr&uuml;cken f&uuml;r mehrere). Nach dem Sichern wirden die Gatewayeinstellungen angezeigt - deaktivieren eines Gateways l&ouml;scht nicht die bisherigen Einstellungen.');
DEFINE ('_CFG_TAB1_OPT11NAME',			'Erfordert Abschluss:');
DEFINE ('_CFG_TAB1_OPT11DESC',			'Als Standardvorgabe gilt, wenn ein Benutzer kein Abo besitzt kann er sich trotzdem einloggen. Mit dieser Einstellung <strong>muss</strong> er ein Abo abschliessen.');
DEFINE ('_CFG_ENTRYPLAN_NAME',			'Standard Probeplan');
DEFINE ('_CFG_ENTRYPLAN_DESC',			'Der Standard-Plan f&uuml;r die freie Probezeit.');

DEFINE ('_CFG_TAB1_OPT15NAME',			'Integration abschalten:');
DEFINE ('_CFG_TAB1_OPT15DESC',			'Alle zu deaktivierenden Integrationen angeben (mit Komma trennen!). Zur werden unterst&uuml;tzt: CB CBE SMF. Sollte z.B. CB (CommunityBuilder) deinstalliert werden aber dessen Datenbanktabellen noch vorhanden sein (AEC wird dann weiterhin CB als installiert ansehen).');
DEFINE ('_CFG_TAB1_OPT16NAME',			'Einfache URLs:');
DEFINE ('_CFG_TAB1_OPT16DESC',			'SEF-URLs der jeweiligen Komponenten abschalten. Falls bei der Verendung von SEF-URLs Fehler auftauchen (FEHLER 404) wurde in der SEF-Konfiguration ein Fehler gemacht - das Abschalten dieser Option hier kann diese Fehler beseitigen.');
DEFINE ('_CFG_TAB1_OPT17NAME',			'Ablaufschonfrist:');
DEFINE ('_CFG_TAB1_OPT17DESC',			'Anzahl der Stunden welche AEC als Polster nimmt bevor der Account abgeschalten wird. Es sollte bedacht werden, dass der Zahlungseingang etliche Stunden dauern kann (t.w. bis zu 14 Stunden!)');
DEFINE ('_CFG_TAB1_OPT18NAME',			'Cronjob Zyklus:');
DEFINE ('_CFG_TAB1_OPT18DESC',			'Anzahl der Stunden die AEC als Trigger nimmt um anstehende, wiederkehrende Aktionen (wie z.B. Emailversand) auszuf&uuml;hren.');
DEFINE ('_CFG_TAB1_OPT21NAME',			'Plan Zuerst:');
DEFINE ('_CFG_TAB1_OPT21DESC',			'Wenn alle 3 Integrationen mit einer Aboaktion verbunden sind, aktiviert diese Option diese M&ouml;glichkeit. Falls das nicht gew&uuml;nscht ist oder nur die erste Integrationsm&ouml;glichkeit zur Auswahl stehen soll, dann hier nicht aktivieren - Die Aboauswahl kommt dann <strong>nach</strong> der Anmeldung/Registrierung.');

DEFINE ('_CFG_TAB_CUSTOMIZATION_TITLE',	'Anpassen');
DEFINE ('_CFG_TAB1_OPT12NAME',			'Individuelle Einstiegsseite:');
DEFINE ('_CFG_TAB1_OPT12DESC',			'Hier den kompletten Link (inkl. http://) angeben der zur Einstiegsseite f&uuml;hren soll. Diese Seite sollte einen Link wie z.B. http://www.yourdomain.com/index.php?option=com_acctexp&amp;task=subscribe&amp;intro=1 beinhalten welcher die Einf&uuml;hrung &uuml;bergeht und den Benutzer direkt zur Aboseite oder Registreirungsseite f&uuml;hrt.<br />Wenn diese Option nicht gew&uuml;nscht wurd, dann dieses Feld leer lassen.');
DEFINE ('_CFG_TAB1_OPT13NAME',			'Link zu individueller Dankeseite:');
DEFINE ('_CFG_TAB1_OPT13DESC',			'Kompletten Link (inkl. http://) angeben welchen die Besucher zur Dankeseite f&uuml;hrt. Wenn nicht gew&uuml;nscht dann leer lassen.');
DEFINE ('_CFG_TAB1_OPT14NAME',			'Link zu individueller Abbruchseite:');
DEFINE ('_CFG_TAB1_OPT14DESC',			'Kompletten Link (inkl. http://) angeben welchen die Besucher - wenn Abbruch - zu dieser  Seite f&uuml;hrt. Wenn nicht gew&uuml;nscht dann leer lassen.');
DEFINE ('_CFG_TAB1_OPT19NAME',			'Link zu den AGBs:');
DEFINE ('_CFG_TAB1_OPT19DESC',			'Hier den Link zu den AGBS angeben. Die Benutzer m&uuml;ssen dann dort zum Einverst&auml;ndnis eine Checkbox aktivieren. Leer lassen wenn nicht gew&uuml;nscht');
DEFINE ('_CFG_GENERAL_CUSTOMNOTALLOWED_DESC',	'Hier den kompletten Link (inkl. http://) angeben welche die Besucher zur Nichterlaubtseite f&uuml;hrt. Leer lassen wenn nicht gew&uuml;nscht.');

DEFINE ('_CFG_GENERAL_DISPLAY_DATE_FRONTEND_NAME',	'Frontend Datumsformat');
DEFINE ('_CFG_GENERAL_DISPLAY_DATE_FRONTEND_DESC',	'Hier angeben wie die Datumsangaben den Besuchern gegen&uuml;ber erfolgen sollen. Mehr dazu im <a href="http://www.php.net/manual/de/function.strftime.php" target="_blank" title="PHP Handbuch">PHP Handbuch</a>');
DEFINE ('_CFG_GENERAL_DISPLAY_DATE_BACKEND_NAME',	'Backend Datumsformat');
DEFINE ('_CFG_GENERAL_DISPLAY_DATE_BACKEND_DESC',	'Hier angeben wie die Datumsangaben im Backend erfolgen sollen. Mehr dazu im <a href="http://www.php.net/manual/de/function.strftime.php" target="_blank" title="PHP Handbuch">PHP Handbuch</a>');
DEFINE ('_CFG_GENERAL_CUSTOMTEXT_PLANS_NAME',		'Text Abo&uuml;bersicht');
DEFINE ('_CFG_GENERAL_CUSTOMTEXT_PLANS_DESC',		'Individueller Text zur &Uuml;bersicht der Abonnements');
DEFINE ('_CFG_GENERAL_CUSTOMTEXT_CONFIRM_NAME',		'Text Best&auml;tigungsseite');
DEFINE ('_CFG_GENERAL_CUSTOMTEXT_CONFIRM_DESC',		'Individueller Text der Best&auml;tigungsseite');
DEFINE ('_CFG_GENERAL_CUSTOMTEXT_CHECKOUT_NAME',	'Text Checkout Seite');
DEFINE ('_CFG_GENERAL_CUSTOMTEXT_CHECKOUT_DESC',	'Individueller Text der Checkoutseite');
DEFINE ('_CFG_GENERAL_CUSTOMTEXT_NOTALLOWED_NAME',	'Text Nichterlaubt Seite');
DEFINE ('_CFG_GENERAL_CUSTOMTEXT_NOTALLOWED_DESC',	'Individueller Text der Nichterlaubtseite');
DEFINE ('_CFG_GENERAL_CUSTOMTEXT_PENDING_NAME',		'Text Warteseite');
DEFINE ('_CFG_GENERAL_CUSTOMTEXT_PENDING_DESC',		'Individueller Text der Warteseite');
DEFINE ('_CFG_GENERAL_CUSTOMTEXT_EXPIRED_NAME',		'Text Abgelaufenseite');
DEFINE ('_CFG_GENERAL_CUSTOMTEXT_EXPIRED_DESC',		'Individueller Text der Abgelaufenseite');

DEFINE ('_CFG_GENERAL_CUSTOMTEXT_CONFIRM_KEEPORIGINAL_NAME',	'Behalte Originaltext');
DEFINE ('_CFG_GENERAL_CUSTOMTEXT_CONFIRM_KEEPORIGINAL_DESC',	'Diese Option aktivieren, wenn der Originaltext auf der Best&auml;tigungseite angezeigt werden soll (anstatt des Individuellen)');
DEFINE ('_CFG_GENERAL_CUSTOMTEXT_CHECKOUT_KEEPORIGINAL_NAME',	'Behalte Originaltext');
DEFINE ('_CFG_GENERAL_CUSTOMTEXT_CHECKOUT_KEEPORIGINAL_DESC',	'Diese Option aktivieren, wenn der Originaltext auf der Checkoutseite angezeigt werden soll (anstatt des Individuellen)');
DEFINE ('_CFG_GENERAL_CUSTOMTEXT_NOTALLOWED_KEEPORIGINAL_NAME', 'Behalte Originaltext');
DEFINE ('_CFG_GENERAL_CUSTOMTEXT_NOTALLOWED_KEEPORIGINAL_DESC', 'Diese Option aktivieren, wenn der Originaltext auf der Nichterlaubtseite angezeigt werden soll (anstatt des Individuellen)');
DEFINE ('_CFG_GENERAL_CUSTOMTEXT_PENDING_KEEPORIGINAL_NAME',	'Behalte Originaltext');
DEFINE ('_CFG_GENERAL_CUSTOMTEXT_PENDING_KEEPORIGINAL_DESC',	'Diese Option aktivieren, wenn der Originaltext auf der Warteseite angezeigt werden soll (anstatt des Individuellen)');
DEFINE ('_CFG_GENERAL_CUSTOMTEXT_EXPIRED_KEEPORIGINAL_NAME',	'Behalte Originaltext');
DEFINE ('_CFG_GENERAL_CUSTOMTEXT_EXPIRED_KEEPORIGINAL_DESC',	'Diese Option aktivieren, wenn der Originaltext auf der Abgelaufenseite angezeigt werden soll (anstatt des Individuellen)');

DEFINE ('_CFG_GENERAL_HEARTBEAT_CYCLE_BACKEND_NAME',			'Heartbeat Zyklus Backend:');
DEFINE ('_CFG_GENERAL_HEARTBEAT_CYCLE_BACKEND_DESC',			'Heartbeat - Herzklopfen: eine Funktion welche angegebene Funktionen am Leben h&auml;lt. Hier die Anzahl der Stunden angeben welche AEC als Abhorchrythmus nehmen soll um dann die spezifizierten Funktionen auszuf&uuml;hren');
DEFINE ('_CFG_GENERAL_ENABLE_COUPONS_NAME',				'Gutscheine Aktiviert:');
DEFINE ('_CFG_GENERAL_ENABLE_COUPONS_DESC',				'Sollen Gutscheine akzeptiert werden');
DEFINE ('_CFG_GENERAL_DISPLAYCCINFO_NAME',				'Zeige Kreditkartenicons:');
DEFINE ('_CFG_GENERAL_DISPLAYCCINFO_DESC',				'Sollen die Icons f&uuml;r jedes Zahlsystem angezeigt werden');

// Global Micro Integration Settings
DEFINE ('_CFG_TAB_MICROINTEGRATION_TITLE',				'Integration');
DEFINE ('_CFG_MI_ACTIVELIST_NAME',						'Aktive IntegrationenBezahlsysteme');
DEFINE ('_CFG_MI_ACTIVELIST_DESC',						'W&auml;hlen welche Integrationen aktiv sein sollen');
DEFINE ('_CFG_MI_META_NAME',							'Integrationen Meta');
DEFINE ('_CFG_MI_META_DESC',							'Sollen die Integrationen untereinander Variablen austauschen d&uuml;rfen');

// --== PAYMENT PLAN PAGE ==--
// Additions of variables for free trial periods by Michael Spredemann (scubaguy)

DEFINE ('_PAYPLAN_PERUNIT1',							'Tage');
DEFINE ('_PAYPLAN_PERUNIT2',							'Wochen');
DEFINE ('_PAYPLAN_PERUNIT3',							'Monate');
DEFINE ('_PAYPLAN_PERUNIT4',							'Jahre');

// General Params

DEFINE ('_PAYPLAN_DETAIL_TITLE',						'Abo');
DEFINE ('_PAYPLAN_GENERAL_NAME_NAME',					'Name:');
DEFINE ('_PAYPLAN_GENERAL_NAME_DESC',					'Name oder Titel f&uuml;r dieses Abonnement (max. 40 Zeichen)');
DEFINE ('_PAYPLAN_GENERAL_DESC_NAME',					'Beschreibung:');
DEFINE ('_PAYPLAN_GENERAL_DESC_DESC',					'Volltext (max. 255 Zeichen) zu diesem Abonnement wie er den Benutzern angezeigt werden soll');
DEFINE ('_PAYPLAN_GENERAL_ACTIVE_NAME',					'Ver&ouml;ffentlicht:');
DEFINE ('_PAYPLAN_GENERAL_ACTIVE_DESC',					'Ein ver&ouml;ffentlichtes Abo wird den Besuchern im Frontend angezeigt');
DEFINE ('_PAYPLAN_GENERAL_VISIBLE_NAME',				'Sichtbar:');
DEFINE ('_PAYPLAN_GENERAL_VISIBLE_DESC',				'Sichtbare Abos werden im Frontend angezeigt. Unsichtbare werden ncith angezeigt und sind nur verf&uuml;gbar als Ersatz bei Problemen');

DEFINE ('_PAYPLAN_PARAMS_GID_ENABLED_NAME',				'Benutzergruppe');
DEFINE ('_PAYPLAN_PARAMS_GID_ENABLED_DESC',				'Auf JA setzen wennd er Benutzer zu dieser Benutzergruppe geh&ouml;ren soll');
DEFINE ('_PAYPLAN_PARAMS_GID_NAME',						'Zur Gruppe dazu:');
DEFINE ('_PAYPLAN_PARAMS_GID_DESC',						'Benutzer werden dieser Gruppe hinzugef&uuml;gt wenn das Abo gew&auml;hlt wird');

DEFINE ('_PAYPLAN_TEXT_TITLE',							'Abotext');
DEFINE ('_PAYPLAN_GENERAL_EMAIL_DESC_NAME',				'Emailtext:');
DEFINE ('_PAYPLAN_GENERAL_EMAIL_DESC_DESC',				'Text welcher im Email an den Benutzer angezeigt wird wennd as Abo best&auml;tigt wurde');
DEFINE ('_PAYPLAN_GENERAL_FALLBACK_NAME',				'Abo Ersatz:');
DEFINE ('_PAYPLAN_GENERAL_FALLBACK_DESC',				'Wenn ein Abo endet, aktiviere dieses Abo f&uuml;r diesen Benutzer');

DEFINE ('_PAYPLAN_GENERAL_PROCESSORS_NAME',				'Bezahlsysteme:');
DEFINE ('_PAYPLAN_NOPLAN',								'Kein Abo');
DEFINE ('_PAYPLAN_NOGW',								'Kein Bezahlsystem');
DEFINE ('_PAYPLAN_GENERAL_PROCESSORS_DESC',				'Diejenigen Zahlungssystem ausw&auml;hlen welche f�r ideses Abonnement g&uuml;ltig sein sollen (STRG oder HOCHSTELLTASTE um mehrere auszuw&auml;hlen.<hr />Wird ' . _PAYPLAN_NOPLAN . ' gew&auml;hlt, werden alle anderen Optionen ignoriert.<br />ISt hier nur ' . _PAYPLAN_NOPLAN . ' sichtbar, heisst das, dass noch keine Bezahlsysteme ausgew&auml;hlt/konfiguriert wurden' );
DEFINE ('_PAYPLAN_PARAMS_LIFETIME_NAME',				'Immerw&auml;hrend:');
DEFINE ('_PAYPLAN_PARAMS_LIFETIME_DESC',				'Bedeuted ein Abo OHNE Ablaufzeit');

DEFINE ('_PAYPLAN_AMOUNT_NOTICE',						'Anmerkung');
DEFINE ('_PAYPLAN_AMOUNT_NOTICE_TEXT',					'F&uuml;r PayPal gilt, dass f&uuml;r jede Periode ein maximales Limit existiert! Werden also Abos mit PayPal m&ouml;glich, <strong>m&uuml;ssen Tage mit 90, Wochen mit 52, Monate mit 24 und Jahre mit 5 limitiert werden</strong>');
DEFINE ('_PAYPLAN_AMOUNT_EDITABLE_NOTICE',				'Momentan ist 1 oder mehrere Benutzer f&uuml;r dieses Abo eingeschrieben, es ist daher nicht vern&uuml;nftig die Konditionen daf&uuml;r zu &auml;ndern!');

DEFINE ('_PAYPLAN_REGULAR_TITLE',						'Normales Abo');
DEFINE ('_PAYPLAN_PARAMS_FULL_FREE_NAME',				'Frei:');
DEFINE ('_PAYPLAN_PARAMS_FULL_FREE_DESC',				'Ja, wenn das ein Gratisabo sein soll');
DEFINE ('_PAYPLAN_PARAMS_FULL_AMOUNT_NAME',				'Normalpreis:');
DEFINE ('_PAYPLAN_PARAMS_FULL_AMOUNT_DESC',				'Der Betrag f�r dieses Abo. Sind daf&uuml;r bereits Abonnenten eingetragen, kann dieses Feld nicht ge&auml;ndert werden. Soll dennoch eine &Auml;nderung durchgef&uuml;hrt werden, dann Ver&ouml;ffentlichung zur&uuml;ckziehen und ein neues Abo erstellen');
DEFINE ('_PAYPLAN_PARAMS_FULL_PERIOD_NAME',				'Periode:');
DEFINE ('_PAYPLAN_PARAMS_FULL_PERIOD_DESC',				'Die L&auml;nge der Rechnungsperiode (siehe unten). Die Anzahl wird mit dem Zyklus (siehe unten) modifiziert. Sind daf&uuml;r bereits Abonnenten eingetragen, kann dieses Feld nicht ge&auml;ndert werden. Soll dennoch eine &Auml;nderung durchgef&uuml;hrt werden, dann Ver&ouml;ffentlichung zur&uuml;ckziehen und ein neues Abo erstellen');
DEFINE ('_PAYPLAN_PARAMS_FULL_PERIODUNIT_NAME',			'Zyklus:');
DEFINE ('_PAYPLAN_PARAMS_FULL_PERIODUNIT_DESC',			'Anzahl der Zykluseinheiten. Sind daf&uuml;r bereits Abonnenten eingetragen, kann dieses Feld nicht ge&auml;ndert werden. Soll dennoch eine &Auml;nderung durchgef&uuml;hrt werden, dann Ver&ouml;ffentlichung zur&uuml;ckziehen und ein neues Abo erstellen');

DEFINE ('_PAYPLAN_TRIAL_TITLE',							'Testperiode');
DEFINE ('_PAYPLAN_TRIAL',								'(Optional)');
DEFINE ('_PAYPLAN_TRIAL_DESC',							'Dieser Bereich kann &uuml;bergangen werden wenn es keine Testperiode geben soll. <strong>Testperioden sind nur mit dem automatischen PayPal System m&ouml;glich!</strong>');
DEFINE ('_PAYPLAN_PARAMS_TRIAL_FREE_NAME',				'Gratis:');
DEFINE ('_PAYPLAN_PARAMS_TRIAL_FREE_DESC',				'Ja, wenn dieses Abo Gratis sein soll');
DEFINE ('_PAYPLAN_PARAMS_TRIAL_AMOUNT_NAME',			'Testpreis:');
DEFINE ('_PAYPLAN_PARAMS_TRIAL_AMOUNT_DESC',			'Preis f&uuml;r die Testperiode');
DEFINE ('_PAYPLAN_PARAMS_TRIAL_PERIOD_NAME',			'Testperiode:');
DEFINE ('_PAYPLAN_PARAMS_TRIAL_PERIOD_DESC',			'L&auml;nge der testperiode.  Die Anzahl wird mit dem Zyklus (siehe unten) modifiziert. Sind daf&uuml;r bereits Abonnenten eingetragen, kann dieses Feld nicht ge&auml;ndert werden. Soll dennoch eine &Auml;nderung durchgef&uuml;hrt werden, dann Ver&ouml;ffentlichung zur&uuml;ckziehen und ein neues Abo erstellen');
DEFINE ('_PAYPLAN_PARAMS_TRIAL_PERIODUNIT_NAME',		'Testperiodenzyklus:');
DEFINE ('_PAYPLAN_PARAMS_TRIAL_PERIODUNIT_DESC',		'Anzahl der Zykluseinheiten. Sind daf&uuml;r bereits Abonnenten eingetragen, kann dieses Feld nicht ge&auml;ndert werden. Soll dennoch eine &Auml;nderung durchgef&uuml;hrt werden, dann Ver&ouml;ffentlichung zur&uuml;ckziehen und ein neues Abo erstellen');

// Payplan Relations

DEFINE ('_PAYPLAN_RELATIONS_TITLE',						'Beziehungen');
DEFINE ('_PAYPLAN_PARAMS_SIMILARPLANS_NAME',			'&Auml;hnliche Abos:');
DEFINE ('_PAYPLAN_PARAMS_SIMILARPLANS_DESC',			'Abos welche dem heir &Auml;hlich sind ausw&auml;hlen. Einem Benutzer ist es nicht erlaubt ein Testabo auszuw&auml;hlen, wenn ein &auml;hnliches Abos schon verher bezogen wurde');
DEFINE ('_PAYPLAN_PARAMS_EQUALPLANS_NAME',				'Gleiche Abos:');
DEFINE ('_PAYPLAN_PARAMS_EQUALPLANS_DESC',				'Abos welche dem hier Gleich sind ausw&auml;hlen. Ein Benutzer welcher zwischen solchen Abos wechselt, verl&auml;ngert damit sein Abo anstatt es zu erneuern. Test-/Gratisabos sind dann nicht erlaubt');

// Payplan Restrictions

DEFINE ('_PAYPLAN_RESTRICTIONS_TITLE',					'Einschr&auml;nkungen');
DEFINE ('_PAYPLAN_RESTRICTIONS_MINGID_ENABLED_NAME',	'Mindest Gruppen ID:');
DEFINE ('_PAYPLAN_RESTRICTIONS_MINGID_ENABLED_DESC',	'Aktivieren wenn dieses Abo nur <strong>AB</strong> einer bestimmten Benutzergruppe angezeigt werden soll');
DEFINE ('_PAYPLAN_RESTRICTIONS_MINGID_NAME',			'Sichtbare Gruppe:');
DEFINE ('_PAYPLAN_RESTRICTIONS_MINGID_DESC',			'Die Benutzerebenen ID <strong>AB</strong> welcher dieses Abo gesehen werden kann. Neue Benutzer werden nur die Abos mit der geringsten Gruppen ID sehen');
DEFINE ('_PAYPLAN_RESTRICTIONS_FIXGID_ENABLED_NAME',	'Fixe Gruppen ID:');
DEFINE ('_PAYPLAN_RESTRICTIONS_FIXGID_ENABLED_DESC',	'Aktivieren wenn <strong>NUR</strong> eine bestimmte Benutzergruppe dieses Abo ssehen soll');
DEFINE ('_PAYPLAN_RESTRICTIONS_FIXGID_NAME',			'Gruppe:');
DEFINE ('_PAYPLAN_RESTRICTIONS_FIXGID_DESC',			'Nur Benutzer aus dieser Gruppe sehen dieses Abo');
DEFINE ('_PAYPLAN_RESTRICTIONS_MAXGID_ENABLED_NAME',	'Maximum Gruppen ID:');
DEFINE ('_PAYPLAN_RESTRICTIONS_MAXGID_ENABLED_DESC',	'Aktivieren wenn dieses Abo <strong>BIS</strong> z ueiner maximalen Gruppen ID sichtbar sein soll');
DEFINE ('_PAYPLAN_RESTRICTIONS_MAXGID_NAME',			'Maximum Gruppe:');
DEFINE ('_PAYPLAN_RESTRICTIONS_MAXGID_DESC',			'Die Benutzerebenen ID <strong>BIS</strong> zu welcher dieses Abo sichtbar ist');

DEFINE ('_PAYPLAN_RESTRICTIONS_PREVIOUSPLAN_REQ_ENABLED_NAME',	'Erfordert voriges Abo:');
DEFINE ('_PAYPLAN_RESTRICTIONS_PREVIOUSPLAN_REQ_ENABLED_DESC',	'Aktiviern wenn voriges Abo erforderlich ist');
DEFINE ('_PAYPLAN_RESTRICTIONS_PREVIOUSPLAN_REQ_NAME',			'Abo:');
DEFINE ('_PAYPLAN_RESTRICTIONS_PREVIOUSPLAN_REQ_DESC',			'Die Benutzer werden dieses Abo nur dann sehen, wenn <strong>vorher</strong> das gew&auml;hlte Abo verwendet wurde/wird');
DEFINE ('_PAYPLAN_RESTRICTIONS_CURRENTPLAN_REQ_ENABLED_NAME',	'Erforderliches Abo:');
DEFINE ('_PAYPLAN_RESTRICTIONS_CURRENTPLAN_REQ_ENABLED_DESC',	'Aktivieren f&uuml;r momentan aktuelles Abo');
DEFINE ('_PAYPLAN_RESTRICTIONS_CURRENTPLAN_REQ_NAME',			'Abo:');
DEFINE ('_PAYPLAN_RESTRICTIONS_CURRENTPLAN_REQ_DESC',			'Nur sichtbar wenn der Benutzer momentan dieses Abo aktiv innehat oder vorher es abonniert hatte');
DEFINE ('_PAYPLAN_RESTRICTIONS_OVERALLPLAN_REQ_ENABLED_NAME',	'Erfordleriches Abo:');
DEFINE ('_PAYPLAN_RESTRICTIONS_OVERALLPLAN_REQ_ENABLED_DESC',	'Aktivieren f&uuml;r generelles Abo');
DEFINE ('_PAYPLAN_RESTRICTIONS_OVERALLPLAN_REQ_NAME',			'Abo:');
DEFINE ('_PAYPLAN_RESTRICTIONS_OVERALLPLAN_REQ_DESC',			'Nur sichtbar wenn der Benutzer dieses Abo bereits gew&auml;hlt hatte');

DEFINE ('_PAYPLAN_RESTRICTIONS_USED_PLAN_MIN_ENABLED_NAME',		'Mindest Abo:');
DEFINE ('_PAYPLAN_RESTRICTIONS_USED_PLAN_MIN_ENABLED_DESC',		'Aktivieren wenn <strong>mindestens x Mal</strong> ein spezielles Abo <strong>vorher</strong> abonniert war');
DEFINE ('_PAYPLAN_RESTRICTIONS_USED_PLAN_MIN_AMOUNT_NAME',		'Anzahl:');
DEFINE ('_PAYPLAN_RESTRICTIONS_USED_PLAN_MIN_AMOUNT_DESC',		'Die MIndestanzahl an Abozeitr&auml;men um dieses Abo abonnieren zu k&ouml;nnen');
DEFINE ('_PAYPLAN_RESTRICTIONS_USED_PLAN_MIN_NAME',				'Mindest Abo:');
DEFINE ('_PAYPLAN_RESTRICTIONS_USED_PLAN_MIN_DESC',				'Das Abo das der Benutzer <strong>vorher abonniert haben musste</strong> um dieses Abo w&auml;hlen zu k&ouml;nnen');
DEFINE ('_PAYPLAN_RESTRICTIONS_USED_PLAN_MAX_ENABLED_NAME',		'Maximal vewendet:');
DEFINE ('_PAYPLAN_RESTRICTIONS_USED_PLAN_MAX_ENABLED_DESC',		'Aktivieren wenn Benutzer eine maximale Anzahl an einem speziellen Abo vorher hatten mussten um <strong>dieses</strong> Abo zu sehen');
DEFINE ('_PAYPLAN_RESTRICTIONS_USED_PLAN_MAX_AMOUNT_NAME',		'Anzahl:');
DEFINE ('_PAYPLAN_RESTRICTIONS_USED_PLAN_MAX_AMOUNT_DESC',		'Maximale Anzhal die dieses Abo verwendet werden darf');
DEFINE ('_PAYPLAN_RESTRICTIONS_USED_PLAN_MAX_NAME',				'Abo:');
DEFINE ('_PAYPLAN_RESTRICTIONS_USED_PLAN_MAX_DESC',				'Das Abo welches maximal verwendet werden darf');

DEFINE ('_PAYPLAN_MI',											'Zahlsystemintegr.');
DEFINE ('_PAYPLAN_GENERAL_MICRO_INTEGRATIONS_NAME',				'Bezahlsysteme:');
DEFINE ('_PAYPLAN_GENERAL_MICRO_INTEGRATIONS_DESC',				'Die Bezahlsysteme ausw&auml;hlen welche f&uuml;r dieses Abo g&uuml;ltig sein sollen');

DEFINE ('_PAYPLAN_CURRENCY',					'W&auml;hrung');;

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
DEFINE ('_CURRENCY_DEM', 'Deutsche Mark'); // mic: not used anymore
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
DEFINE ('_MI_TITLE',						'Bezahlsysteme');
DEFINE ('_MI_NAME',							'Name');
DEFINE ('_MI_DESC',							'Beschreibung');
DEFINE ('_MI_ACTIVE',						'Aktiv');
DEFINE ('_MI_REORDER',						'Reihenfolge');
DEFINE ('_MI_FUNCTION',						'Funktionsname');

// --== MICRO INTEGRATION EDIT ==--
DEFINE ('_MI_E_TITLE',						'Bezahlsystem');
DEFINE ('_MI_E_SETTINGS',					'Einstellungen');
DEFINE ('_MI_E_NAME_NAME',					'Name');
DEFINE ('_MI_E_NAME_DESC',					'Name f&uuml;r dieses Bezahlservice');
DEFINE ('_MI_E_DESC_NAME',					'Beschreibung');
DEFINE ('_MI_E_DESC_DESC',					'Kurzbeschreibung');
DEFINE ('_MI_E_ACTIVE_NAME',				'Aktiv');
DEFINE ('_MI_E_ACTIVE_DESC',				'Als Aktiv markieren');
DEFINE ('_MI_E_ACTIVE_AUTO_NAME',			'Aktion bei Ablauf');
DEFINE ('_MI_E_ACTIVE_AUTO_DESC',			'Falls dieses Bezahlsystem es erlaubt k&ouml;nnen Aktionen definiert werden wenn ein Abo ausl&auml;ft');
DEFINE ('_MI_E_ACTIVE_USERUPDATE_NAME',		'Benutzeraktion');
DEFINE ('_MI_E_ACTIVE_USERUPDATE_DESC',		'Falls vom Bezahlsystem unterst&uuml;tzt k&ouml;nnen Aktionen definiert werden wenn ein Benutzerabo ausl&auml;ft');
DEFINE ('_MI_E_PRE_EXP_NAME',				'Tage vor Ablauf');
DEFINE ('_MI_E_PRE_EXP_DESC',				'Anzahl der Tage vor dem Ablauf ab wann die Aktionen gelten sollen');
DEFINE ('_MI_E_FUNCTION_NAME',				'Funktionsname');
DEFINE ('_MI_E_FUNCTION_DESC',				'Welche der System sollen verwendet weren');
DEFINE ('_MI_E_FUNCTION_EXPLANATION',		'Bevor die Integration defineirt wird muss bestimmt werden welche der Integrationsdateien g&uuml;ltig sind. Wahl treffen und Speichern. Dann nochmals bearbeiten, die Einstellungen sind dann sichtbar. HINWEIS: einmal definiert lassen sich die Einstellungen nicht R&uuml;ckg&auml;ngig machen');

// --== REWRITE EXPLANATION ==--
DEFINE ('_REWRITE_AREA_USER',				'Benutzeraccount Bezogen');
DEFINE ('_REWRITE_KEY_USER_ID',				'Benutzer ID');
DEFINE ('_REWRITE_KEY_USER_USERNAME',		'Benutzername' );
DEFINE ('_REWRITE_KEY_USER_NAME',			'Name');
DEFINE ('_REWRITE_KEY_USER_EMAIL',			'Emailadresse');

DEFINE ('_REWRITE_AREA_EXPIRATION',			'Benutzer Ablaufbezogen');
DEFINE ('_REWRITE_KEY_EXPIRATION_DATE',		'Ablaufdatum');

DEFINE ('_REWRITE_AREA_SUBSCRIPTION',		'Benutzer Abobezogen');
DEFINE ('_REWRITE_KEY_SUBSCRIPTION_TYPE',	'Bezahlsystem');
DEFINE ('_REWRITE_KEY_SUBSCRIPTION_STATUS', 'Abonnentenstatus');
DEFINE ('_REWRITE_KEY_SUBSCRIPTION_SIGNUP_DATE',	'Datum ab wann Abo erfolgte');
DEFINE ('_REWRITE_KEY_SUBSCRIPTION_LASTPAY_DATE',	'Letztes Zahlungsdatum');
DEFINE ('_REWRITE_KEY_SUBSCRIPTION_PLAN',			'Aktuelle Abo ID');
DEFINE ('_REWRITE_KEY_SUBSCRIPTION_PREVIOUS_PLAN',	'Vorige Abo ID');
DEFINE ('_REWRITE_KEY_SUBSCRIPTION_RECURRING',		'Wiederkehrender Zahlung');
DEFINE ('_REWRITE_KEY_SUBSCRIPTION_LIFETIME',		'Immerw&auml;hrendes Abo');

DEFINE ('_REWRITE_AREA_PLAN', 				'Abo Bezogen');
DEFINE ('_REWRITE_KEY_PLAN_NAME',			'Name');
DEFINE ('_REWRITE_KEY_PLAN_DESC',			'Beschreibung');

DEFINE ('_REWRITE_AREA_CMS',				'CMS Bezogen');
DEFINE ('_REWRITE_KEY_CMS_ABSOLUTE_PATH',	'Absoluter Pfad zum CMS-Hauptverzeichnis (z.B. ../www/html/...');
DEFINE ('_REWRITE_KEY_CMS_LIVE_SITE',		'Relativer Pfad zur Webseite (z.B. http://www.meineseite.com)');

// --== COUPONS OVERVIEW ==--
DEFINE ('_COUPON_TITLE',					'Gutscheine');
DEFINE ('_COUPON_TITLE_STATIC',				'Gruppengutscheine');
DEFINE ('_COUPON_NAME',						'Name');
DEFINE ('_COUPON_DESC',						'Beschreibung (erste 50 Zeichen)');
DEFINE ('_COUPON_ACTIVE',					'Ver&ouml;ffentlicht');
DEFINE ('_COUPON_REORDER',					'Ordnen');
DEFINE ('_COUPON_USECOUNT',					'Angewendet');

// --== INVOICE OVERVIEW ==--
DEFINE ('_INVOICE_TITLE',					'Rechnungen');
DEFINE ('_INVOICE_SEARCH',					'Suche');
DEFINE ('_INVOICE_USERID',					'Benutzername');
DEFINE ('_INVOICE_INVOICE_NUMBER',			'Rechnungsnummer');
DEFINE ('_INVOICE_TRANSACTION_DATE',		'Durchf&uuml;hrungsdatum');
DEFINE ('_INVOICE_METHOD',					'Rechnungsart');
DEFINE ('_INVOICE_AMOUNT',					'Rechnungsbetrag');
DEFINE ('_INVOICE_CURRENCY',				'W&auml;hrung');

// --== PAYMENT HISTORY OVERVIEW ==--
DEFINE ('_HISTORY_TITLE2',					'Acktuelle Transaktionsgeschichte');
DEFINE ('_HISTORY_SEARCH',					'Suche');
DEFINE ('_HISTORY_USERID',					'Benutzername');
DEFINE ('_HISTORY_INVOICE_NUMBER',			'Rechnungsnummer');
DEFINE ('_HISTORY_PLAN_NAME',				'Abonnement');
DEFINE ('_HISTORY_TRANSACTION_DATE',		'Durchf&uuml;hrungsdatum');
DEFINE ('_INVOICE_CREATED_DATE',			'Erstellt');
DEFINE ('_HISTORY_METHOD',					'Rechnungsart');
DEFINE ('_HISTORY_AMOUNT',					'Rechnungsbetrag');
DEFINE ('_HISTORY_RESPONSE',				'Serverantwort');

// --== ALL USER RELATED PAGES ==--
DEFINE ('_METHOD',							'Methode');

// --== PENDING PAGE ==--
DEFINE ('_PEND_DATE',						'schwebend seit');
DEFINE ('_PEND_TITLE',						'Schwebende Abonnements');
DEFINE ('_PEND_DESC',						'Abonnements, die nicht abgeschlossen wurden. Dies ist normal, falls das System auf die Zahlungbenachrichtigung des Bezahlservices wartet.');
DEFINE ('_ACTIVATED',						'Benutzer aktiviert.');
DEFINE ('_ACTIVATE',						'Aktivieren');
?>