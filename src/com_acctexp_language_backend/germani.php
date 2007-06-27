<?php
/**
* @version $Id: germani.php 16 2007-06-25 09:04:04Z mic $
* @package AEC - Account Control Expiration - Subscription component for Joomla! OS CMS
* @subpackage Language - Backend - German Informal
* @copyright Copyright (C) 2004-2007 Helder Garcia, David Deutsch
* @author Team AEC - http://www.gobalnerd.org
* @license GNU/GPL v.2 http://www.gnu.org/copyleft/gpl.html
*/

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

// already in english file
define( '_COUPON_CODE',							'Gutscheincode' );


// mic: adopted from the english file (missing here)
define( '_CFG_GENERAL_CUSTOMNOTALLOWED_NAME',	'Link zur Nichterlaubtseite:' );

// mic: NEW 0.12.4

// hacks/install >> ATTENTION: MUST BE HERE AT THIS POSITION, needed later!
define( '_AEC_SPEC_MENU_ENTRY',					'Mein Abonnement' );

// also NOT in english file
define( '_DESCRIPTION_PAYSIGNET',				'mic: Beschreibung zu Paysignnet - CHECKEN! -' );
define( '_AEC_CONFIG_SAVED',					'Konfiguration erfolgreich gesichert' );
define( '_AEC_CONFIG_CANCELLED',				'Konfiguration abgebrochen' );
define( '_AEC_TIP_NO_GROUP_PF_PB',				'Weder "Public Frontend" noch "Public Backend" sind w&auml;hlbare Benutzergruppen!' );
define( '_AEC_CGF_LINK_ABO_FRONTEND',			'Zur Einstiegsseite' );
define( '_AEC_NOT_SET',							'Nein' );
define( '_AEC_COUPON',							'Gutschein' );
define( '_AEC_CMN_NEW',							'Neu' );
define( '_AEC_CMN_CLICK_TO_EDIT',				'Klicken zum Bearbeiten' );
define( '_AEC_CMN_LIFETIME',					'Lebenslang' );
define( '_AEC_CMN_UNKOWN',						'Unbekannt' );
define( '_AEC_CMN_EDIT_CANCELLED',				'Bearbeitung abgebrochen' );
define( '_AEC_CMN_PUBLISHED',					'Ver&ouml;ffentlicht' );
define( '_AEC_CMN_NOT_PUBLISHED',				'Nicht Ver&ouml;ffentlicht' );
define( '_AEC_CMN_CLICK_TO_CHANGE',				'Auf Icon klicken um Status zu &auml;ndern' );
define( '_AEC_CMN_NONE_SELECTED',				'&gt;&gt;&nbsp;Keine Auswahl&nbsp;&lt;&lt;' );
define( '_AEC_CMN_MADE_VISIBLE',				'sichtbar gemacht' );
define( '_AEC_CMN_MADE_INVISIBLE',				'unsichtbar gemacht' );
define( '_AEC_CMN_TOPUBLISH',					'zu ver&ouml;ffentlichen' );
define( '_AEC_CMN_TOUNPUBLISH',					'nicht zu ver&ouml;ffentlichen' );
define( '_AEC_CMN_FILE_SAVED',					'Datei gesichert' );

// headers
define( '_AEC_HEAD_SETTINGS',					'Einstellungen' );
define( '_AEC_HEAD_HACKS',						'Spezial' );

// hacks (special)
define( '_AEC_HACK_HACK',						'&Auml;nderung' );
define( '_AEC_HACKS_ISHACKED',					'Ge&auml;ndert' );
define( '_AEC_HACKS_NOTHACKED',					'Noch nicht ge&auml;ndert!' );
define( '_AEC_HACKS_UNDO',						'Originalzustand wiederherstellen' );
define( '_AEC_HACKS_COMMIT',					'Jetzt durchf&uuml;hren' );
define( '_AEC_HACKS_FILE',						'Datei' );
define( '_AEC_HACKS_LOOKS_FOR',					'Diese &Auml;nderung &uuml;berpr&uuml;ft' );
define( '_AEC_HACKS_REPLACE_WITH',				'... und ersetzt es mit' );

define( '_AEC_HACKS_NOTICE',					'Wichtiger Hinweis' );
define( '_AEC_HACKS_NOTICE_DESC',				'Aus Sicherheitsgr&uuml;nden und damit AEC funktionieren kann, sind nachfolgende &Auml;nderungen notwendig.<br />Diese k&ouml;nnen entweder automatisch durchgef&uuml;hrt werden (auf den Best&auml;tigungslink klicken) oder manuell (bearbeiten der php.Dateien).<br />Damit die Benutzer zu pers&ouml;nlichen Abo&uuml;bericht kommen, muss auch die Benutzerlink&auml;nderung durchgef&uuml;hrt werden.' );
define( '_AEC_HACKS_NOTICE_DESC2',				'<strong>Alle wichtigen Funktions&auml;nderungen sind mit einem Pfeil und Ausrufzeichen markiert!</strong>' );
define( '_AEC_HACKS_NOTICE_DESC3',				'Die nachfolgenden Anzeigen sind <strong>nicht</strong> lt. der Nummerierung (#1, #3, #6, usw.) erforderlich.<br />Falls keine Nummer dabei steht, sind das wahrscheinlich veraltete (fr&uuml;here) &Auml;nderungen welche korrigiert werden m&uuml;ssen.' );
define( '_AEC_HACKS_NOTICE_JACL',				'JACL Anmerkung' );
define( '_AEC_HACKS_NOTICE_JACL_DESC',			'Falls geplant ist Erweiterungen wie JACL-Plus zu installieren, <strong>m&uuml;ssen die AEC-&Auml;nderungen danach durchgef&uuml;hrt werden!</strong>, da solche Komponenten eigene, weitere &Auml;nderungen vornehmen!' );

define( '_AEC_HACKS_MENU_ENTRY',				'Men&uuml;eintrag' );
define( '_AEC_HACKS_MENU_ENTRY_DESC',			'F&uuml;gt dem Benutzermen&uuml; den neuen Eintrag <strong>' . _AEC_SPEC_MENU_ENTRY . '</strong> hinzu.<br />Damit kann dieser Benutzer sowohl die bisherigen Abos und Rechnungen, als auch neue/andere Abos bestellen/erneuern.' );
define( '_AEC_HACKS_LEGACY',					'<strong>Das ist eine veraltete Version, bitte l&ouml;schen oder aktualisieren!</strong>' );
define( '_AEC_HACKS_NOTAUTH',					'Diese &Auml;nderung korrigiert den Link - falls keine Berechtigung zum Ansehen vorliegt - zur Aboseite.' );
define( '_AEC_HACKS_SUB_REQUIRED',				'&Uuml;berpr&uuml;ft das Vorhandensein eines g&uuml;ltigen Abos zum einloggen<br /><strong>Nicht vergessen in der AEC-Konfiguration die Einstellung [ Ben&ouml;tigt Abo ] zu aktivieren!</strong>' );
define( '_AEC_HACKS_REG2',						'Diese &Auml;nderung leitet den Benutzer zur Abo&uuml;bersicht weiter <strong>nachdem er sich registriert hat</strong>.<br />Falls vor der Registrierung die Aboauswahl zur Auswahl angezeigt werden soll, gen&uuml;gt diese &Auml;nderung (AEC-Konfiguration [ Ben&ouml;tigt Abo ] muss dann aktiviert sein), andernfalls <strong>sind noch 2 weitere &Auml;nderungen durchzuf&uuml;hren!</strong><br />Falls die Aboauswahl <strong>vor</strong> den Benutzerdetails angezeigt werden soll, m&uuml;ssen alle 3 &Auml;nderungen durchgef&uuml;hrt werden.' );
define( '_AEC_HACKS_REG3',						'Leitet den Benutzer direkt zur Abo&uuml;bersicht um falls vorher noch keine Wahl getroffen wurde' );
define( '_AEC_HACKS_REG4',						'&Uml;bermittelt die AEC-Daten aus dem Anmeldeforumlar' );
define( '_AEC_HACKS_MI1',						'Einige Integrationen ben&ouml;tigen Klartextdaten.<br />Diese &Auml;nderung stellt sicher, dass die Integrationen die Benutzerdaten bei &Auml;nderung erhalten.' );
define( '_AEC_HACKS_MI2',						'Einige Integrationen ben&ouml;tigen Klartextdaten.<br />Diese &Auml;nderung &uuml;bermittelt die Benutzerdaten nach der Registrierung' );
define( '_AEC_HACKS_MI3',						'Einige Integrationen ben&ouml;tigen Klartextdaten.<br />Diese &Auml;nderung stellt sicher, dass bei Benutzerdaten&auml;nderung durch einen Admin diese weitergeleitet werden.' );

// log
	// settings
define( '_AEC_LOG_SH_SETT_SAVED',				'&Auml;nderung Einstellungen' );
define( '_AEC_LOG_LO_SETT_SAVED',				'AEC Einstellungen wurden gesichert' );
	// heartbeat
define( '_AEC_LOG_SH_HEARTBEAT',				'Heartbeat' );
define( '_AEC_LOG_LO_HEARTBEAT',				'Heartbeataktion:' );
define( '_AEC_LOG_AD_HEARTBEAT_DO_NOTHING',		'keine' );
	// install
define( '_AEC_LOG_SH_INST',						'AEC Installierung' );
define( '_AEC_LOG_LO_INST',						'AEC Version %s wurde installiert' );

// install texts
define( '_AEC_INST_NOTE_IMPORTANT',				'Wichtiger Hinweis' );
define( '_AEC_INST_NOTE_SECURITY',				'Um das CMS <strong>sicher zu betreiben</strong> ist es notwendig einige &Auml;nderungen an den Stammdateien zu machen.<br />Mit dieser Version von AEC wird eine Funktion mitgeliefert die exakt diese Aufgabe &uuml;bernimmt, daf&uuml;r bitte auf den nachfolgenden Link klicken' );
define( '_AEC_INST_APPLY_HACKS',				'Um die erforderlichen &Auml;nderungen durchzuf&uuml;hren bitte <a href="' .  $mosConfig_live_site . '/administrator/index2.php?option=com_acctexp&amp;task=hacks" target="_blank">hier klicken</a><br />Dieser Link kann auch sp&aumL;ter jederzeit aufgerufen werden - siehe AEC Verwaltung' );
define( '_AEC_INST_NOTE_UPGRADE',				'<strong>Falls ein bestehendes AEC upgegraded werden soll, bitte auf alle F&auml;lle das Men&uuml; "Spezial" aufrufen - es gibt immer wieder neue &Auml;nderungen' );
define( '_AEC_INST_NOTE_HELP',					'Um die wichtigsten Antworten auf Fragen bereit zu haben kann jederzeit die interne <a href="' . $mosConfig_live_site . '/administrator/index2.php?option=com_acctexp&task=help" target="_blank">Hilfe</a>  aufgerufen werden (aufruf auch von der AEC-Verwaltung aus). Dort stehen auch weitere Tips zur nachfolgenden Einrichtung von AEC' );
define( '_AEC_INST_HINTS',						'Hinweise' );
define( '_AEC_INST_HINT1',						'Wir w&uuml;rden uns freuen wenn Du das <a href="http://www.globalnerd.org/index.php?option=com_wrapper&amp;Itemid=53" target="_blank">Forum</a> besuchst. Neben Diskussionen k&ouml;nnen auch weitere Tips, Anregungen usw. dort nachgelesen werden' );
define( '_AEC_INST_HINT2',						'Auf alle F&auml;lle (und ganz speziell wenn du AEC auf einer Liveseite einsetzt), gehe in Ruhe alle Einstellungen durch, lege einen Testzugang an und teste die Zahlvorg&auml;nge!' );
define( '_AEC_INST_ATTENTION',					'Immer die aktuellsten Programme von und f&uuml;r AEC einsetzen' );
define( '_AEC_INST_ATTENTION1',					'Falls noch &auml;ltere AEC-Loginmodule in Verwendung sind, bitte deinstallieren und gegen die regul&auml;ren austauschen (egal ob Joomla, Mambo, CB, etc.)' );

// header
define( '_AEC_HEAD_PLAN_INFO',					'Abonnement' );

// help
define( '_AEC_CMN_HELP',						'Hilfe' );
define( '_AEC_HELP_DESC',						'Auf dieser Seite &uuml;berpr&uuml;ft AEC die bestehende Konfiguration und zeigt eventuelle Fehler an welche bereinigt werden sollten' );
define( '_AEC_HELP_GREEN',						'Gr&uuml;n</strong> bedeutet Mitteilungen oder Probleme die bereits gel&ouml;st wurden' );
define( '_AEC_HELP_YELLOW',						'Gelb</strong> weist haupts&auml;chlich auf kosmetische Punkte hin (z.B. Benutzerlink zum Frontent hinzuf&uuml;gen), aber auch Punkte die im Ermessen des Admin liegen' );
define( '_AEC_HELP_RED',						'Rot</strong> weist auf Probleme bez&uuml;glich Sicherheit oder anderer Wichtigkeit hin' );
define( '_AEC_HELP_GEN',						'Hinweis: es wird versucht so viel wie m&ouml;glich zu &uuml;berpr&uuml;fen, dennoch besteht kein Anspruch auf Vollst&auml;ndigkeit!' );
define( '_AEC_HELP_QS_HEADER',					'AEC Schnellstart Handbuch' );
define( '_AEC_HELP_QS_DESC',					'Bevor mit den unten angef&uuml;hrten Aktionen fortgefahren wird, sollte eventuell vorher das <a href="' . $mosConfig_live_site . '/administrator/components/com_acctexp/manual/AEC_Quickstart.pdf" target="_blank" title="Handbuch">Handbuch</a> gelesen werden' );
define( '_AEC_HELP_SER_SW_DIAG1',				'Dateirechteproblem' );
define( '_AEC_HELP_SER_SW_DIAG1_DESC',			'AEC hat den Server als Apache Webserver identifiziert. Um &Auml;nderungen an diesen Dateien durchf&uuml;hren zu k&ouml;nnen, m&uuml;ssen diese dem Webserverbenutzer geh&ouml;ren was momentan offensichtlich nicht so ist.' );
define( '_AEC_HELP_SER_SW_DIAG1_DESC2',			'Es wird empfohlen f&uuml;r die Dauer der &Auml;nderungen die Dateirechte auf 0777 zu &auml;ndern. Nach Durchf&uuml;hrung der &Anderung m&uuml;ssen diese Rechte wieder auf den Originalzustand zur&uuml;ckgesetzt werden!<br />Dies gilt auch f&uuml;r die weiter unten erw&auml;hnten Dateirechte.' );
define( '_AEC_HELP_SER_SW_DIAG2',				'CMS Dateirechte' );
define( '_AEC_HELP_SER_SW_DIAG2_DESC',			'AEC hat erkannt, dass dieses CMS nicht die Rechte des Webservers besitzt.' );
define( '_AEC_HELP_SER_SW_DIAG2_DESC2',			'Wenn du einen SSH-Zugang zum Server besitzt, gehe in das Verzeichnis "<cmsinstallation/includes>" und gib dann entweder "chown wwwrun joomla.php" (oder "chown wwwrun mambo.php" - falls Mambo verwendet wird) ein.' );
define( '_AEC_HELP_SER_SW_DIAG3',				'&Auml;nderungen erkannt' );
define( '_AEC_HELP_SER_SW_DIAG3_DESC',			'Es sieht so aus als wenn die vorhandenen &Auml;nderungen nicht aktuell sein d&uuml;rften! Damit AEC ordnungsgem&auml;ss funktionieren kann, sollten diese &Auml;nderungen nochmals gepr&uuml;ft werden (siehe dazu auf der AEC Webseite nach)' );
define( '_AEC_HELP_SER_SW_DIAG4',				'Dateirechte Probleme' );
define( '_AEC_HELP_SER_SW_DIAG4_DESC',			'AEC kann die Schreibrechte der Dateien welche ge&auml;ndert werden m&uuml;ssen nicht erkennen. Entweder ist das hier ein WINDOWS-Server oder der Apacheserver wurde mit der Option "--disable-posix" kompiliert.<br /><strong>Sollten die &Auml;nderungen durchgef&uuml;hrt werden, dann jedoch nicht funktionieren liegt das Problem bei den Dateirechten</strong>' );
define( '_AEC_HELP_SER_SW_DIAG4_DESC2',			'Es wird empfohlen entweder den Server mit der erw&auml;hnten Option zu kompilieren (Apache) oder den Admin zu kontaktieren' );
define( '_AEC_HELP_DIAG_CMN1',					'CMS &Auml;nderungen' );
define( '_AEC_HELP_DIAG_CMN1_DESC',				'Notwendig damit die Benutzer nach dem Login von AEC &uuml;berpr&uuml;ft werden k&oum;nnen' );
define( '_AEC_HELP_DIAG_CMN1_DESC2',			'Zur Spezialseite gehen und &Auml;nderung durchf&uuml;hren' );
define( '_AEC_HELP_DIAG_CMN2',					'Meine Abos - Men&uuml;eintrag' );
define( '_AEC_HELP_DIAG_CMN2_DESC',				'Ein Link der die Benutzer zu ihren eigenen Abonnements f&uuml;hrt' );
define( '_AEC_HELP_DIAG_CMN2_DESC2',			'Zur Spezialseite gehen und Link erstellen' );
define( '_AEC_HELP_DIAG_CMN3',					'JACL nicht installiert' );
define( '_AEC_HELP_DIAG_CMN3_DESC',				'Sollte geplant sein, JACLPlus (oder &auml;hnliches) zu installieren, muss auf die AEC-&Auml;nderungen R&uuml;cksicht genommen werden! Sollten diese &Auml;nderungen bereits durchgef&uuml;hrt worden sein, kann dies auf der Spezialseite ge&auml;ndert werden' );

// micro integration
	// general
define( '_AEC_MI_REWRITING_INFO',				'Vorlagen Platzhalter' );
	// htaccess
define( '_AEC_MI_HTACCESS_INFO_DESC',			'Sch&uuml;tzt ein Verzeichnis mit einer .htaccess Datei und erlaubt den Zugriff nur Benutzern dieses Abos' );
	// email
define( '_AEC_MI_EMAIL_INFO_DESC',				'Sended an eine oder mehrere Adressen den Status des Abos' );
	// idev
define( '_AEC_MI_IDEV_DESC',					'Verbindet den Verkauf mit iDevAffiliate' );
	// mosetstree
define( '_AEC_MI_MOSETSTREE_DESC',				'Beschr&auml;nkt die Anzahl der anzuzeigenden Links die ein Benutzer ver&ouml;ffentlichen kann' );
	// mysql-query
define( '_AEC_MI_MYSQL_DESC',					'Spezifiziert einen My-SQL-String welcher bei Abo-Beginn/Beendigung ausgef&uuml;hrt wird' );
	// remository
define( '_AEC_MI_REMOSITORY_DESC',				'Definiert die max. Dateianzahl die ein Benutzer in reMOSitory downloaden kann' );
	// VirtueMart
define( '_AEC_MI_VIRTUEMART_DESC',				'Definiert die VirtueMart-Benutzergruppe welcher der Benutzer angeh&ouml;hren soll' );

// central
define( '_AEC_CENTR_CENTRAL',					'AEC Zentrale' );
define( '_AEC_CENTR_EXCLUDED',					'Ausgeschlossen' );
define( '_AEC_CENTR_PLANS',						'Abos' );
define( '_AEC_CENTR_PENDING',					'Wartend' );
define( '_AEC_CENTR_ACTIVE',					'Aktiv' );
define( '_AEC_CENTR_EXPIRED',					'Abgelaufen' );
define( '_AEC_CENTR_CANCELLED',					'Stornos' );
define( '_AEC_CENTR_CLOSED',					'Beendet' );
define( '_AEC_CENTR_SETTINGS',					'Einstellungen' );
define( '_AEC_CENTR_EDIT_CSS',					'CSS Bearbeiten' );
define( '_AEC_CENTR_V_INVOICES',				'Rechnungen' );
define( '_AEC_CENTR_COUPONS',					'Einzel Gutscheine' );
define( '_AEC_CENTR_COUPONS_STATIC',			'Gruppen Gutscheine' );
define( '_AEC_CENTR_VIEW_HISTORY',				'Archiv' );
define( '_AEC_CENTR_SPECIAL',					'Spezial' );
define( '_AEC_CENTR_M_INTEGRATION',				'Integration' );
define( '_AEC_CENTR_HELP',						'Hilfe' );
define( '_AEC_CENTR_LOG',						'Logdatei' );

// select lists
define( '_AEC_SEL_EXCLUDED',					'Ausgeschlossen' );
define( '_AEC_SEL_PENDING',						'Wartend' );
define( '_AEC_SEL_ACTIVE',						'Aktiv' );
define( '_AEC_SEL_EXPIRED',						'Abgelaufen' );
define( '_AEC_SEL_CLOSED',						'Geschlossen' );
define( '_AEC_SEL_CANCELLED',					'Storniert' );
define( '_AEC_SEL_NOT_CONFIGURED',				'Ni. Konfiguriert' );

// footer
define( '_AEC_FOOT_TX_CHOOSING',				'Danke dass Du dich f&uuml;r AEC - Account Expiration Control entschieden hast!' );
define( '_AEC_FOOT_TX_GPL',						'Diese Komponente wurde entwickelt und ver&ouml;ffentlicht unter der <a href="http://www.gnu.org/copyleft/gpl.html" target="_blank" title="GNU/GPL">GNU/GPL</a> von Helder Garcia und David Deutsch von <a href="http://www.globalnerd.org" target="_blank" title="globalnerd.org">globalnerd.org</a>' );
define( '_AEC_FOOT_TX_SUBSCRIBE',				'Wenn du mehr Features m&ouml;chtest, professionelles Service, Updates, Handb&uuml;cher und Online Hilfe, klicke auf den Link oben. Es hilft uns auch bei der weiteren Entwicklung!' );
define( '_AEC_FOOT_CREDIT',						'Bitte lese auch die <a href="' . $mosConfig_live_site . '/administrator/index2.php?option=com_acctexp&amp;amp;task=credits">Credits</a>' );
define( '_AEC_FOOT_VERSION_CHECK',				'Check auf neue Version' );
define( '_AEC_FOOT_MEMBERSHIP',					'Mitglied werden und Zugang zu Dokumentationen und Support bekommen' );

// alerts
define( '_AEC_ALERT_SELECT_FIRST',				'Bitte vorher eine Auswahl treffen!' );
define( '_AEC_ALERT_SELECT_FIRST_TO',			'Bitte vorher eine Auswahl treffen um' );

// messages
define( '_AEC_MSG_NODELETE_SUPERADMIN',			'Superadministratoren k&ouml;nnen nicht gel&ouml;scht werden!' );
define( '_AEC_MSG_NODELETE_YOURSELF',			'Sich selber l&ouml;schen macht auch keinen Sinn - oder?' );
define( '_AEC_MSG_NODELETE_EXCEPT_SUPERADMIN',	'Nur Superadmins k&ouml;nnen diese Aktion ausf&uuml;hren!' );
define( '_AEC_MSG_SUBS_RENEWED',				'Abonnement(s) erneuert' );
define( '_AEC_MSG_SUBS_ACTIVATED',				'Abonnement(s) aktiviert' );
define( '_AEC_MSG_NO_ITEMS_TO_DELETE',			'Kein(e) Datensatz/-s&auml;tze zum l&ouml;schen vorhanden' );
define( '_AEC_MSG_NO_DEL_W_ACTIVE_SUBSCRIBER',	'Abos mit aktiven Abonnenten k&ouml;nnen nicht ge&l&ouml;scht werden!' );
define( '_AEC_MSG_ITEMS_DELETED',				'Datensatz/-s&auml;tze gel&ouml;scht' );
define( '_AEC_MSG_ITEMS_SUCESSFULLY',			'%s Inhalt(e) erfolgreich' ); // %s Item(s) successfully
define( '_AEC_MSG_SUCESSFULLY_SAVED',			'&Auml;nderungen erfolgreich gespeichert' );
define( '_AEC_MSG_ITEMS_SUCC_PUBLISHED',		'Inhalt(e) erfolgreich ver&ouml;ffentlicht' );
define( '_AEC_MSG_ITEMS_SUCC_UNPUBLISHED',		'Inhalt(e) Ver&ouml;ffentlichung erfolgreich zur&uuml;ck genommen' );
define( '_AEC_MSG_NO_COUPON_CODE',				'Es muss ein Gutscheincode angegeben werden!' );
define( '_AEC_MSG_OP_FAILED',					'Vorgang fehlgeschlagen, konnte %s nicht &ouml;ffnen' );
define( '_AEC_MSG_OP_FAILED_EMPTY',				'Vorgang fehlgeschlagen, kein Inhalt' );
define( '_AEC_MSG_OP_FAILED_NOT_WRITEABLE',		'Vorgang fehlgeschlagen, Datei nicht beschreibbar' );
define( '_AEC_MSG_OP_FAILED_NO_WRITE',			'Vorgang fehlgeschlagen, Datei kann nicht zum Schreiben ge&oumL;ffnet werden' );
define( '_AEC_MSG_INVOICE_CLEARED',				'Rechnung bereinigt' );

// languages (e.g. PayPal) - must be ISO 3166 Two-Character Country Codes
define( '_AEC_LANG_DE',							'Deutsch' );
define( '_AEC_LANG_GB',							'Englisch GB' );
define( '_AEC_LANG_FR',							'Franz&ouml;sisch' );
define( '_AEC_LANG_IT',							'Italienisch' );
define( '_AEC_LANG_ES',							'Spanisch' );
define( '_AEC_LANG_US',							'Englisch US' );

// currencies
define( '_CURRENCY_RSD',						'Serbische Dinar' );

// --== COUPON EDIT ==--
define( '_COUPON_DETAIL_TITLE',					'Gutschein' );
define( '_COUPON_RESTRICTIONS_TITLE',			'Einschr.' );
define( '_COUPON_RESTRICTIONS_TITLE_FULL',		'Einschr&auml;nkungen' );
define( '_COUPON_MI',							'Zahlsystem' );
define( '_COUPON_MI_FULL',						'Bezahlsysteme' );

define( '_COUPON_GENERAL_NAME_NAME',			'Name' );
define( '_COUPON_GENERAL_NAME_DESC',			'Der interne und externe Name f&uuml;r diesen Gutschein' );
define( '_COUPON_GENERAL_COUPON_CODE_NAME',		'Gutscheincode' );
define( '_COUPON_GENERAL_COUPON_CODE_DESC',		'Den Gutscheincode hier eintragen - der angezeigte (zuf&auml;llig generierte) Code wurde vom System erzeugt.<hr /><strong>Hinweis:</strong><br />Der COde muss einmalig sein!' );
define( '_COUPON_GENERAL_DESC_NAME',			'Beschreibung' );
define( '_COUPON_GENERAL_DESC_DESC',			'Die interne Beschreibung f&uuml;r diesen Gutschein' );
define( '_COUPON_GENERAL_ACTIVE_NAME',			'Aktiv' );
define( '_COUPON_GENERAL_ACTIVE_DESC',			'Ist dieser Gutschein aktiv (momentan g&uuml;ltig)' );
define( '_COUPON_GENERAL_TYPE_NAME',			'Gruppe' );
define( '_COUPON_GENERAL_TYPE_DESC',			'Soll dieser Gutschein einmalig oder f&uuml;r eine Gruppe von mehreren Personen gelten (Einzel- oder Gruppengutschein)' );

define( '_COUPON_GENERAL_MICRO_INTEGRATIONS_NAME',	'Bezahlsysteme' );
define( '_COUPON_GENERAL_MICRO_INTEGRATIONS_DESC',	'Diejenigen Bezahlsysteme ausw&auml;hlen welche f&uuml;r diesen Gutschein g&uuml;ltig sein sollen' );

define( '_COUPON_PARAMS_AMOUNT_USE_NAME',		'Betrag verwenden' );
define( '_COUPON_PARAMS_AMOUNT_USE_DESC', 		'Soll von der Rechnung direkt ein Betrag abgezogen werden' );
define( '_COUPON_PARAMS_AMOUNT_NAME',			'Betrag' );
define( '_COUPON_PARAMS_AMOUNT_DESC',			'Hier den Betrag angeben welcher direkt von der Rechnung abgezogenw erden soll' );
define( '_COUPON_PARAMS_AMOUNT_PERCENT_USE_NAME',	'Prozente' );
define( '_COUPON_PARAMS_AMOUNT_PERCENT_USE_DESC',	'Sollen x Prozente vom Rechnungsbetrag abgezogen werden' );
define( '_COUPON_PARAMS_AMOUNT_PERCENT_NAME',	'Prozent in %' );
define( '_COUPON_PARAMS_AMOUNT_PERCENT_DESC',	'Hier angeben wieviele Prozente vom betrag abgezogen werden sollen' );
define( '_COUPON_PARAMS_PERCENT_FIRST_NAME',	'Prozente vor Betrag' );
define( '_COUPON_PARAMS_PERCENT_FIRST_DESC',	'Wenn die Kombination von Prozente und Betrag angewendet werden soll, solen die Prozente vorher abgezogen werden?' );
define( '_COUPON_PARAMS_USEON_TRIAL_NAME',		'Bei Testabo?' );
define( '_COUPON_PARAMS_USEON_TRIAL_DESC',		'Sollen die Benutzer diesen Diskont auch bei einerm testabo auswa&auml;hlen d&uuml;rfen?' );
define( '_COUPON_PARAMS_USEON_FULL_NAME',		'Bei Vollabo?' );
define( '_COUPON_PARAMS_USEON_FULL_DESC',		'Sollen die Benutzer diesen Diskont vom aktuellen betrag abzihen k&ouml;nnen? (bei wiederholenden Abos wird nur vom ersten Rechnungsbetrag der Diskont abgezogen!)' );
define( '_COUPON_PARAMS_USEON_FULL_ALL_NAME',	'Jede Rechnung?' );
define( '_COUPON_PARAMS_USEON_FULL_ALL_DESC',	'Falls der Benutzer wiederholende Abos besitzt, solle der Diskont jedes Mal abgezogen werden? Wenn nur beim ersten Mal dann Nein)' );

define( '_COUPON_PARAMS_HAS_START_DATE_NAME',	'Beginndatum' );
define( '_COUPON_PARAMS_HAS_START_DATE_DESC',	'Soll der Gutschein f&uuml;r einen bestimmtern Zeitraum gelten?' );
define( '_COUPON_PARAMS_START_DATE_NAME',		'Datum' );
define( '_COUPON_PARAMS_START_DATE_DESC',		'Beginndatum der Periode ausw&auml;hlen f&uuml;den dieser Gutschein g&uuml;ltig sein soll' );
define( '_COUPON_PARAMS_HAS_EXPIRATION_NAME',	'Ablaufdatum' );
define( '_COUPON_PARAMS_HAS_EXPIRATION_DESC',	'Soll dieser Gutschein mit Datum x auslaufen?' );
define( '_COUPON_PARAMS_EXPIRATION_NAME',		'Datum' );
define( '_COUPON_PARAMS_EXPIRATION_DESC',		'Datum ausw&auml;hlen bis wann dieser Gutschein g&uuml;ltig sein soll' );
define( '_COUPON_PARAMS_HAS_MAX_REUSE_NAME',	'Eingeschr&auml;nkt?' );
define( '_COUPON_PARAMS_HAS_MAX_REUSE_DESC',	'Soll dieser max. x verwendet werden d&uuml;rfen?' );
define( '_COUPON_PARAMS_MAX_REUSE_NAME',		'Anzahl' );
define( '_COUPON_PARAMS_MAX_REUSE_DESC',		'Hier die Anzahl eintragen wieoft dieser Gutschein verwendet werden darf' );

define( '_COUPON_PARAMS_USECOUNT_NAME',			'Reset' );
define( '_COUPON_PARAMS_USECOUNT_DESC',			'Hier kann der Z&auml;hler r&uuml;ckgesetzt werden' );

define( '_COUPON_PARAMS_USAGE_PLANS_ENABLED_NAME',	'Abo' );
define( '_COUPON_PARAMS_USAGE_PLANS_ENABLED_DESC',	'Soll dieser Gutschein nur f&uuml;r ein bestimmtes Abo gelten?' );
define( '_COUPON_PARAMS_USAGE_PLANS_NAME',			'Abos' );
define( '_COUPON_PARAMS_USAGE_PLANS_DESC',			'Welche Abos werden angewendet' );

define( '_COUPON_RESTRICTIONS_MINGID_ENABLED_NAME', 'Mindest Gruppen ID:' );
define( '_COUPON_RESTRICTIONS_MINGID_ENABLED_DESC', 'Hier die Mindestgruppen ID definieren f&uuml;r welche dieser Gutschein g&uuml;ltig sein soll' );
define( '_COUPON_RESTRICTIONS_MINGID_NAME',			'Sichtbare Gruppe:' );
define( '_COUPON_RESTRICTIONS_MINGID_DESC',			'Die Mindestbenutzerebene welche diesen Gutschein einsetzen kann' );
define( '_COUPON_RESTRICTIONS_FIXGID_ENABLED_NAME', 'Fixe Gruppen ID:' );
define( '_COUPON_RESTRICTIONS_FIXGID_ENABLED_DESC', 'Soll dieser Gutschein nur f&uuml;r eine bestimmte Gruppe gelten' );
define( '_COUPON_RESTRICTIONS_FIXGID_NAME',			'Gruppe:' );
define( '_COUPON_RESTRICTIONS_FIXGID_DESC',			'Nur Benutzer dieser Gruppe k&ouml;nnen diesen Gutschein einsetzen' );
define( '_COUPON_RESTRICTIONS_MAXGID_ENABLED_NAME', 'Maximale Gruppen ID:' );
define( '_COUPON_RESTRICTIONS_MAXGID_ENABLED_DESC', 'Welche maximale Gruppen ID darf diesen Gutschein verwenden d&uuml;rfen?' );
define( '_COUPON_RESTRICTIONS_MAXGID_NAME',			'Gruppe:' );
define( '_COUPON_RESTRICTIONS_MAXGID_DESC',			'Die am h&ouml;chsten eingestufte Gruppe welche diesen Gutschein einsetzen darf' );

define( '_COUPON_RESTRICTIONS_PREVIOUSPLAN_REQ_ENABLED_NAME',	'Voriges Abo:' );
define( '_COUPON_RESTRICTIONS_PREVIOUSPLAN_REQ_ENABLED_DESC',	'Wird f&uuml;r diesen Gutschein ein bestimmtes Abo vorher ben&ouml;tigt' );
define( '_COUPON_RESTRICTIONS_PREVIOUSPLAN_REQ_NAME',			'Abo:' );
define( '_COUPON_RESTRICTIONS_PREVIOUSPLAN_REQ_DESC',			'Benutzer welche dieses Abo schon einmal verwendet hatten sind f&uuml;r diesen Gutschein berechtigt' );
define( '_COUPON_RESTRICTIONS_CURRENTPLAN_REQ_ENABLED_NAME',	'Aktuelles Abo:' );
define( '_COUPON_RESTRICTIONS_CURRENTPLAN_REQ_ENABLED_DESC',	'Aktuelles Abo ist mindestens Voraussetzung' );
define( '_COUPON_RESTRICTIONS_CURRENTPLAN_REQ_NAME',			'Abo:' );
define( '_COUPON_RESTRICTIONS_CURRENTPLAN_REQ_DESC',			'Nur Benutzer welche aktuell im Besitz dieses Abos sind oder es schon mal vorher hatten d&uuml;rfen diesen Gutschein verwenden' );
define( '_COUPON_RESTRICTIONS_OVERALLPLAN_REQ_ENABLED_NAME',	'Erforderlich:' );
define( '_COUPON_RESTRICTIONS_OVERALLPLAN_REQ_ENABLED_DESC',	'Akivieren wenn Abo vorher erforderlich ist' );
define( '_COUPON_RESTRICTIONS_OVERALLPLAN_REQ_NAME',			'Abo:' );
define( '_COUPON_RESTRICTIONS_OVERALLPLAN_REQ_DESC',			'Nur Benutzer welche schon irgendwan mal vorher dieses Abo verwendet hatten d&uuml;rfen diesen Gutschein verwenden' );

define( '_COUPON_RESTRICTIONS_USED_PLAN_MIN_ENABLED_NAME',		'Mind. Aboanzahl:' );
define( '_COUPON_RESTRICTIONS_USED_PLAN_MIN_ENABLED_DESC',		'Aktivieren wenn Benutzer schon vorher x-mal ein bestimmtes Abo verwendet haben m&uuml;ssen' );
define( '_COUPON_RESTRICTIONS_USED_PLAN_MIN_AMOUNT_NAME',		'Anzahl:' );
define( '_COUPON_RESTRICTIONS_USED_PLAN_MIN_AMOUNT_DESC',		'Die Mindestanzahl der bisherigen Verwendungen des angegebenen Abos' );
define( '_COUPON_RESTRICTIONS_USED_PLAN_MIN_NAME',				'Abo:' );
define( '_COUPON_RESTRICTIONS_USED_PLAN_MIN_DESC',				'Das Abo welches der Benutzer schon vorher x-mal verwendet haben muss' );
define( '_COUPON_RESTRICTIONS_USED_PLAN_MAX_ENABLED_NAME',		'Abo:' );
define( '_COUPON_RESTRICTIONS_USED_PLAN_MAX_ENABLED_DESC',		'Maximumanzahl der bisherigen Verwendungen des angegebenen Abos um diesen Gutschein einsetzen zu d&uuml;rfen' );
define( '_COUPON_RESTRICTIONS_USED_PLAN_MAX_AMOUNT_NAME',		'Anzahl:' );
define( '_COUPON_RESTRICTIONS_USED_PLAN_MAX_AMOUNT_DESC',		'Maximale Anzahl der bisherigen Verwendungen dieses Abos' );
define( '_COUPON_RESTRICTIONS_USED_PLAN_MAX_NAME',				'Abo:' );
define( '_COUPON_RESTRICTIONS_USED_PLAN_MAX_DESC',				'Welches Abo muss vorher verwendet werden' );

// END 0.12.4 (mic) ####################################################

// --== BACKEND TOOLBAR ==--
DEFINE ('_EXPIRE_SET','Verfallsdatum:');
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
DEFINE ('_EXP_ASC','Auslauf Aufst');
DEFINE ('_EXP_DESC','Auslauf Abst');
DEFINE ('_NAME_ASC','Name Aufst');
DEFINE ('_NAME_DESC','Name Abst');
DEFINE ('_LOGIN_ASC','Login Aufst');
DEFINE ('_LOGIN_DESC','Login Abst');
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
DEFINE ('_ORDER_BY','Sortieren nach:');
DEFINE ('_SAVED', 'Gespeichert.');
DEFINE ('_CANCELED', 'Abgebrochen.');
DEFINE ('_CONFIGURED', 'Eintrag eingerichtet.');
DEFINE ('_REMOVED', 'Eintrag von Liste gel&ouml;scht.');
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
DEFINE ('_PAYPLANS_MAINDESC', 'Ver&ouml;ffentlichte Pl&auml;ne werden dem Benutzer pr&auml;sentiert.');
DEFINE ('_PAYPLAN_NAME', 'Name');
DEFINE ('_PAYPLAN_DESC', 'Beschreibung (ersten 50 Buchstaben)');
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
DEFINE ('_ALTERNATIVE_PAYMENT', 'Bank-&Uuml;berweisung');
DEFINE ('_SUBSCR_DATE', 'Anmeldedatum');
DEFINE ('_ACTIVE_TITLE', 'Aktive Abonnements');
DEFINE ('_ACTIVE_DESC', 'Diese Liste enth&auml;lt keine manuellen Abonnements, nur solche, die durch ein Zahlungsgateway abgeschlossen wurden.');
DEFINE ('_LASTPAY_DATE', 'Datum der letzten Zahlung');
DEFINE ('_USERPLAN', 'Plan');
DEFINE ('_CANCELLED_TITLE', 'Abgebrochene Abonnements');
DEFINE ('_CANCELLED_DESC', 'Diese Liste enth&auml;lt keine manuellen Abonnements, nur solche, die durch ein Zahlungsgateway abgeschlossen wurden. Es sind die Abonnements, die von den Benutzern abgebrochen wurden, aber noch nicht ausgelaufen sind.');
DEFINE ('_CANCEL_DATE', 'Datum des Abbruches');
DEFINE ('_MANUAL_DESC', 'Wenn du einen Eintrag l&ouml;schst, wird der Benutzer vollst&auml;ndig aus der Datenbank gel&ouml;scht.');
DEFINE ('_REPEND_ACTIVE',			'Wiederaktivierung');
DEFINE ('_FILTER_PLAN',				'- Abo aussuchen -');
DEFINE ('_BIND_USER',				'Zuweisen an:');
DEFINE ('_PLAN_FILTER',				'Abo Filter:');
DEFINE ('_CENTRAL_PAGE',			'Zentrale');

// --== USER FORM ==--
DEFINE ('_HISTORY_COL1_TITLE',		'Rechnung');
DEFINE ('_HISTORY_COL2_TITLE',		'Betrag');
DEFINE ('_HISTORY_COL3_TITLE',		'Bezahlt am');
DEFINE ('_HISTORY_COL4_TITLE',		'Methode');
DEFINE ('_HISTORY_COL5_TITLE',		'Aktion');
DEFINE ('_HISTORY_COL6_TITLE',		'Abo');
DEFINE ('_USERINVOICE_ACTION_REPEAT','Wiederholungs&nbsp;Link');
DEFINE ('_USERINVOICE_ACTION_CANCEL','l&ouml;schen');
DEFINE ('_USERINVOICE_ACTION_CLEAR','als&nbsp;bezahlt&nbsp;markieren');
DEFINE ('_USERINVOICE_ACTION_CLEAR_APPLY','als&nbsp;bezahlt&nbsp;markieren&nbsp;&amp;&nbsp;Plan&nbsp;anwenden');

// --== BACKEND SETTINGS ==--

// TAB 1 - Global AEC Settings
DEFINE ('_CFG_TAB1_TITLE', 'Konfigurations-Optionen');
DEFINE ('_CFG_TAB1_SUBTITLE', 'Optionen f&uuml;r die Interaktion mit dem Benutzer');

DEFINE ('_CFG_TAB1_OPT1NAME', 'Unmittelbarer Auslauf:');
DEFINE ('_CFG_TAB1_OPT1DESC', 'Standard Auslaufzeitraum, in Tagen, f&uuml;r neue Registrierungen. Die Zahl verh&auml;lt sich relativ zum Registrierungsdatum, wenn du also Benutzer grunds&auml;tzlich als abgelaufen registrieren m&ouml;chtest, w&auml;hle  -1 (minus eins). Dies hat keinen Effekt auf den normalen Anmeldevorgang &uuml;ber einen Zahlungsgateway');
DEFINE ('_CFG_TAB1_OPT3NAME', 'Alarm Level 2:');
DEFINE ('_CFG_TAB1_OPT3DESC', 'In Tagen. Dies ist die erste Grenze die beginnt, den Benutzer auf den Auslauf seines Abonnements hinzuweisen.');
DEFINE ('_CFG_TAB1_OPT4NAME', 'Alarm Level 1:');
DEFINE ('_CFG_TAB1_OPT4DESC', 'In Tagen. Dies ist die letzte Grenze die beginnt, den Benutzer auf den Auslauf seines Abonnements hinzuweisen.');
DEFINE ('_CFG_TAB1_OPT5NAME', 'Entry Plan:');
DEFINE ('_CFG_TAB1_OPT5DESC', 'Every user will be subscribed to this plan (no payment!) when the user has no subscription yet');
DEFINE ('_CFG_TAB1_OPT9NAME', 'Require Subscription:');
DEFINE ('_CFG_TAB1_OPT9DESC', 'When enabled, a user MUST have a subscription. If disabled, users will be able to log in without one.');

DEFINE ('_CFG_TAB1_OPT10NAME', 'Gateway Erkl&auml;rungen:');
DEFINE ('_CFG_TAB1_OPT10DESC', 'Trage hier eine Liste der Bezahlm&ouml;glichkeiten ein, die auf der NotAllowed-Seite erscheinen sollen (diese Liste sehen die Benutzer, wenn sie versuchen eine Seite anzusehen, f&uuml;r die sie keine Berechtigung haben).');
DEFINE ('_CFG_TAB1_OPT20NAME', 'Activated Gateways:');
DEFINE ('_CFG_TAB1_OPT20DESC', 'Select the gateways you want to be activated (use the CTRL key to select more than one). After saving, the settings tabs for these gateways will show up. Deactivating a gateway will not erase its settings.');
DEFINE ('_CFG_TAB1_OPT11NAME', 'Require Subscription:');
DEFINE ('_CFG_TAB1_OPT11DESC', 'By default, a user who has no subscription set up with the AEC will be able to log in just fine. With this setting, you can make subscription a requirement.');
DEFINE ('_CFG_ENTRYPLAN_NAME', 'Standart Probe-Plan');
DEFINE ('_CFG_ENTRYPLAN_DESC', 'Der Standart-Plan für die freie Probezeit.');

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

DEFINE ('_PAYPLAN_CURRENCY', 'W&auml;hrung');;

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
DEFINE ('_COUPON_TITLE_STATIC', 'Statische Coupons');
DEFINE ('_COUPON_NAME', 'Name');
DEFINE ('_COUPON_DESC', 'Beschreibung (erste 50 Zeichen)');
DEFINE ('_COUPON_ACTIVE', 'Ver&ouml;ffentlicht');
DEFINE ('_COUPON_REORDER', 'Ordnen');
DEFINE ('_COUPON_USECOUNT', 'Benutzungen');

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
DEFINE ('_HISTORY_PLAN_NAME', 'Plan Subscribed To');
DEFINE ('_HISTORY_TRANSACTION_DATE', 'Transaction Date');
DEFINE ('_INVOICE_CREATED_DATE', 'Created Date');
DEFINE ('_HISTORY_METHOD', 'Invoice Method');
DEFINE ('_HISTORY_AMOUNT', 'Invoice Amount');
DEFINE ('_HISTORY_RESPONSE', 'Server Response');

// --== ALL USER RELATED PAGES ==--
DEFINE ('_METHOD', 'Methode');

// --== PENDING PAGE ==--
DEFINE ('_PEND_DATE', 'schwebend seit');
DEFINE ('_PEND_TITLE', 'Schwebende Abonnements');
DEFINE ('_PEND_DESC', 'Abonnements, die nicht abgeschlossen wurden. Dies ist normal, falls das System auf die Zahlungbenachrichtigung des Bezahlservices wartet.');
DEFINE ('_ACTIVATED', 'Benutzer aktiviert.');
DEFINE ('_ACTIVATE', 'Aktivieren');

?>