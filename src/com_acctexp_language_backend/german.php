<?php
/**
 * @version $Id: german.php 16 2007-06-27 09:04:04Z mic $
 * @package AEC - Account Control Expiration - Subscription component for Joomla! OS CMS
 * @subpackage Language - Backend - German Formal
 * @copyright Copyright (C) 2004-2007, All Rights Reserved, Helder Garcia, David Deutsch
 * @author Helder Garcia <helder.garcia@gmail.com>, David Deutsch <skore@skore.de> & Team AEC - http://www.gobalnerd.org
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

// mic: NEW 0.12.4 start
define( '_AEC_LANGUAGE',						'de' ); // NICHT AENDERN!!
define( '_COUPON_CODE',							'Gutscheincode' );
define( '_CFG_GENERAL_CUSTOMNOTALLOWED_NAME',	'Link zur Nichterlaubtseite:' );

// hacks/install >> ATTENTION: MUST BE HERE AT THIS POSITION, needed later!
define( '_AEC_SPEC_MENU_ENTRY',					'Mein Abonnement' );

// common
define( '_DESCRIPTION_PAYSIGNET',				'Zahlungen mit allen g&auml;ngigen Kreditkarten und Bank&uuml;berweisung' );
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
define( '_AEC_CMN_ID',							'ID' );
define( '_AEC_CMN_DATE',						'Datum' );
define( '_AEC_CMN_EVENT',						'Ereignis' );
define( '_AEC_CMN_TAGS',						'Kennzeichen' );
define( '_AEC_CMN_ACTION',						'Aktion' );
define( '_AEC_CMN_PARAMETER',					'Parameter' );
define( '_AEC_CMN_NONE',						'Keine' );
define( '_AEC_CMN_WRITEABLE',					'Beschreibbar' );
define( '_AEC_CMN_UNWRITEABLE',					'Nicht beschreibbar!' );
define( '_AEC_CMN_UNWRITE_AFTER_SAVE',			'Nach dem Sichern als nicht beschreibbar kennzeichnen' );
define( '_AEC_CMN_OVERRIDE_WRITE_PROT',			'Schreibschutz zum Sichern deaktivieren' );
define( '_AEC_CMN_NOT_SET',						'Nicht gesetzt' );
define( '_AEC_CMN_SEARCH',						'Suche' );
define( '_AEC_CMN_APPLY',						'Anwenden' );
define( '_AEC_CMN_STATUS',						'Status' );
define( '_AEC_FEATURE_NOT_ACTIVE',				'Dieses Feature ist noch nicht aktiv' );
define( '_AEC_CMN_YES',							'Ja' );
define( '_AEC_CMN_NO',							'Nein' );
define( '_AEC_CMN_LANG_CONSTANT_IS_MISSING',	'Sprachenkonstante <strong>%s</strong> fehlt' );
define( '_AEC_CMN_INVISIBLE',					'Unsichtbar' );
define( '_AEC_CMN_EXCLUDED',					'Ausgenommen' );
define( '_AEC_CMN_PENDING',						'Schwebend' );
define( '_AEC_CMN_ACTIVE',						'Aktiv' );
define( '_AEC_CMN_TRIAL',						'Testzeit' );
define( '_AEC_CMN_CANCEL',						'Abgebrochen/Storniert' );
define( '_AEC_CMN_EXPIRED',						'Abgelaufen' );
define( '_AEC_CMN_CLOSED',						'Geschlossen' );

// user(info)
define( '_AEC_USER_USER_INFO',					'Benutzerinfos' );
define( '_AEC_USER_USERID',						'Benutzer-ID' );
define( '_AEC_USER_STATUS',						'Status' );
define( '_AEC_USER_ACTIVE',						'Aktiv' );
define( '_AEC_USER_BLOCKED',					'Geblockt' );
define( '_AEC_USER_ACTIVE_LINK',				'Aktivierungslink' );
define( '_AEC_USER_PROFILE',					'Profil' );
define( '_AEC_USER_PROFILE_LINK',				'Profil ansehen' );
define( '_AEC_USER_USERNAME',					'Benutzername' );
define( '_AEC_USER_NAME',						'Name' );
define( '_AEC_USER_EMAIL',						'Email' );
define( '_AEC_USER_SEND_MAIL',					'Email senden' );
define( '_AEC_USER_TYPE',						'Benutzertyp' );
define( '_AEC_USER_REGISTERED',					'Registriert' );
define( '_AEC_USER_LAST_VISIT',					'Letzter Besuch' );
define( '_AEC_USER_EXPIRATION',					'Ablaufdatum' );
define( '_AEC_USER_CURR_EXPIRE_DATE',			'Aktuelles Ablaufdatum' );
define( '_AEC_USER_LIFETIME',					'Lebenslang' );
define( '_AEC_USER_RESET_EXP_DATE',				'Ablaufdatum &auml;ndern' );
define( '_AEC_USER_RESET_STATUS',				'Status &auml;ndern' );
define( '_AEC_USER_SUBSCRIPTION',				'Abonnement' );
define( '_AEC_USER_PAYMENT_PROC',				'Zahlungsabwicklung' );
define( '_AEC_USER_CURR_SUBSCR_PLAN',			'Aktuelles Abo' );
define( '_AEC_USER_PREV_SUBSCR_PLAN',			'Voriges Abo' );
define( '_AEC_USER_USED_PLANS',					'Bisherige Abos' );
define( '_AEC_USER_NO_PREV_PLANS',				'Bisher keine Abos' );
define( '_AEC_USER_ASSIGN_TO_PLAN',				'Abo zuweisen' );
define( '_AEC_USER_TIME',						'Mal' );
define( '_AEC_USER_TIMES',						'Male' );
define( '_AEC_USER_INVOICES',					'Rechnungen' );
define( '_AEC_USER_NO_INVOICES',				'Keine Rechnungen bisher' );
define( '_AEC_USER_INVOICE_FACTORY',			'Rechnungserstellung' );

// new (additional)
define( '_AEC_MSG_MIS_NOT_DEFINED',				'Es wurden noch keine MicroIntegrationen definiert - siehe Konfiguration' );

// headers
define( '_AEC_HEAD_SETTINGS',					'Einstellungen' );
define( '_AEC_HEAD_HACKS',						'Hacks' );
define( '_AEC_HEAD_PLAN_INFO',					'Plan Info' );
define( '_AEC_HEAD_LOG',						'Logdatei' );
define( '_AEC_HEAD_CSS_EDITOR',					'CSS Editor' );
define( '_AEC_HEAD_MICRO_INTEGRATION',			'MicroIntegration' );
define( '_AEC_HEAD_ACTIVE_SUBS',				'Aktive Abonnenten' );
define( '_AEC_HEAD_EXCLUDED_SUBS',				'Ausgenommene Benutzer' );
define( '_AEC_HEAD_EXPIRED_SUBS',				'Abgelaufene Abonnenten' );
define( '_AEC_HEAD_PENDING_SUBS',				'Wartende Abonnenten' );
define( '_AEC_HEAD_CANCELLED_SUBS',				'Stornierte Abonnenten' );
define( '_AEC_HEAD_CLOSED_SUBS',				'Abgeschlossene Abonnenten' );
define( '_AEC_HEAD_MANUAL_SUBS',				'Manuelle Abonnenten' );
define( '_AEC_HEAD_SUBCRIBER',					'Abonnent' );

// hacks (special)
define( '_AEC_HACK_HACK',						'&Auml;nderung' );
define( '_AEC_HACKS_ISHACKED',					'Ge&auml;ndert' );
define( '_AEC_HACKS_NOTHACKED',					'Noch nicht ge&auml;ndert!' );
define( '_AEC_HACKS_UNDO',						'Originalzustand wiederherstellen' );
define( '_AEC_HACKS_COMMIT',					'Jetzt durchf&uuml;hren' );
define( '_AEC_HACKS_FILE',						'Datei' );
define( '_AEC_HACKS_LOOKS_FOR',					'Diese &Auml;nderung sucht nach' );
define( '_AEC_HACKS_REPLACE_WITH',				'... und ersetzt es mit' );

define( '_AEC_HACKS_NOTICE',					'Wichtiger Hinweis' );
define( '_AEC_HACKS_NOTICE_DESC',				'Aus Sicherheitsgr&uuml;nden und damit AEC funktionieren kann, sind nachfolgende &Auml;nderungen notwendig.<br />Diese k&ouml;nnen entweder automatisch durchgef&uuml;hrt werden (auf den Best&auml;tigungslink klicken) oder manuell (bearbeiten der php.Dateien).<br />Damit die Benutzer zu pers&ouml;nlichen Abo&uuml;bericht kommen, muss auch die Benutzerlink&auml;nderung durchgef&uuml;hrt werden.' );
define( '_AEC_HACKS_NOTICE_DESC2',				'<strong>Alle wichtigen Funktions&auml;nderungen sind mit einem Pfeil und Ausrufzeichen markiert!</strong>' );
define( '_AEC_HACKS_NOTICE_DESC3',				'Die nachfolgenden Anzeigen sind <strong>nicht</strong> lt. der Nummerierung (#1, #3, #6, usw.) erforderlich.<br />Falls keine Nummer dabei steht, sind das wahrscheinlich veraltete (fr&uuml;here) &Auml;nderungen welche korrigiert werden m&uuml;ssen.' );
define( '_AEC_HACKS_NOTICE_JACL',				'JACL Anmerkung' );
define( '_AEC_HACKS_NOTICE_JACL_DESC',			'Falls geplant ist Erweiterungen wie JACL-Plus zu installieren (welche ebenfalls Dateien &auml;ndern), <strong>m&uuml;ssen die AEC-&Auml;nderungen danach durchgef&uuml;hrt werden!</strong>' );

define( '_AEC_HACKS_MENU_ENTRY',				'Men&uuml;eintrag' );
define( '_AEC_HACKS_MENU_ENTRY_DESC',			'F&uuml;gt dem Benutzermen&uuml; den neuen Eintrag <strong>' . _AEC_SPEC_MENU_ENTRY . '</strong> hinzu.<br />Damit kann dieser Benutzer sowohl die bisherigen Abos und Rechnungen, als auch neue/andere Abos bestellen/erneuern.' );
define( '_AEC_HACKS_LEGACY',					'<strong>Das ist eine veraltete Version, bitte l&ouml;schen oder aktualisieren!</strong>' );
define( '_AEC_HACKS_NOTAUTH',					'Diese &Auml;nderung korrigiert den Link - falls keine Berechtigung zum Ansehen vorliegt - zur Aboseite.' );
define( '_AEC_HACKS_SUB_REQUIRED',				'&Uuml;berpr&uuml;ft das Vorhandensein eines g&uuml;ltigen Abos zum einloggen<br /><strong>Nicht vergessen in der AEC-Konfiguration die Einstellung [ Ben&ouml;tigt Abo ] zu aktivieren!</strong>' );
define( '_AEC_HACKS_REG2',						'Diese &Auml;nderung leitet den Benutzer zur Abo&uuml;bersicht weiter <strong>nachdem er sich registriert hat</strong>.<br />Falls vor der Registrierung die Aboauswahl zur Auswahl angezeigt werden soll, gen&uuml;gt diese &Auml;nderung (AEC-Konfiguration [ Ben&ouml;tigt Abo ] muss dann aktiviert sein), andernfalls <strong>sind noch 2 weitere &Auml;nderungen durchzuf&uuml;hren!</strong><br />Falls die Aboauswahl <strong>vor</strong> den Benutzerdetails angezeigt werden soll, m&uuml;ssen alle 3 &Auml;nderungen durchgef&uuml;hrt werden.' );
define( '_AEC_HACKS_REG3',						'Leitet den Benutzer direkt zur Abo&uuml;bersicht um falls vorher noch keine Wahl getroffen wurde' );
define( '_AEC_HACKS_REG4',						'&Uml;bermittelt die AEC-Daten aus dem Anmeldeforumlar' );
define( '_AEC_HACKS_REG5',						'Mit diesem Hack k&ouml;nnen sie das "Bezahlpl&auml;ne zuerst"-Feature benutzen. Nicht vergessen in den AEC Einstellungen den Schalter hierf&uuml;r zu setzen!' );
define( '_AEC_HACKS_MI1',						'Einige MicroIntegrationen ben&ouml;tigen Klartextdaten.<br />Diese &Auml;nderung stellt sicher, dass die Integrationen die Benutzerdaten bei &Auml;nderung erhalten.' );
define( '_AEC_HACKS_MI2',						'Einige MicroIntegrationen ben&ouml;tigen Klartextdaten.<br />Diese &Auml;nderung &uuml;bermittelt die Benutzerdaten nach der Registrierung' );
define( '_AEC_HACKS_MI3',						'Einige MicroIntegrationen ben&ouml;tigen Klartextdaten.<br />Diese &Auml;nderung stellt sicher, dass bei Benutzerdaten&auml;nderung durch einen Admin diese weitergeleitet werden.' );
define( '_AEC_HACKS_CB2',						'Leitet den Besucher nach der Registrierung in CB (Community Builder) zur Abonnementauswahl weiter.<br />Nur diese &Auml;nderung bewirkt, da&szlig; ein Abo beim Login ausgew&auml;hlt werden muss, anderenfalls sind noch 2 weitere A&uml;nderungen notwendig.<br /><strong>Soll vor dem Abschluss der Benutzerdetails (zur Registrierung) ein Abo ausgew&auml;hlt werden, sind alle 3 &Auml;nderungen erforderlich!</strong>' );
define( '_AEC_HACKS_CB6',						'Leitet den Besucher zur Aboauwahl weiter wenn keine Auswahl bisher getroffen wurde.' );
define( '_AEC_HACKS_CB_HTML2',					'Leitet die Benutzerdetails intern an AEC weiter.<br /><strong>Um diese &Auml;nderung wirksam werden zu lassen, muss in der AEC-Konfiguarion die Einstellung "Abo Zuerst" aktiviert werden</strong>' );
define( '_AEC_HACKS_UHP2',						'UHP2 Men&uuml;eintrag' );
define( '_AEC_HACKS_UHP2_DESC',					'F&uuml;gt dem UHP2 Men&uuml; den Eintrag <strong>' . _AEC_SPEC_MENU_ENTRY . '</strong> hinzu. Damit k&ouml;nnen diese Benutzer ihre Abos und Rechnungen verwalten' );
define( '_AEC_HACKS_CBM',						'Wenn das Comprofiler Moderator Modul verwendet wird, muss diese &auml;nderung durchgef&uuml;hrt werden um eine Endlosschleife zu vermeiden!' );

// log
	// settings
define( '_AEC_LOG_SH_SETT_SAVED',				'&Auml;nderung Einstellungen' );
define( '_AEC_LOG_LO_SETT_SAVED',				'AEC Einstellungen wurden gesichert, &Auml;nderungen:' );
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
define( '_AEC_INST_APPLY_HACKS',				'Um die erforderlichen &Auml;nderungen durchzuf&uuml;hren bitte %s<br />Dieser Link kann auch sp&auml;ter jederzeit aufgerufen werden - siehe AEC Verwaltung' );
define( '_AEC_INST_APPLY_HACKS_LTEXT',			'hier klicken' );
define( '_AEC_INST_NOTE_UPGRADE',				'<strong>Falls ein bestehendes AEC upgedated werden soll, bitte auf alle F&auml;lle das Men&uuml; "Hacks" aufrufen - es gibt immer wieder neue &Auml;nderungen</strong>' );
define( '_AEC_INST_NOTE_HELP',					'Um die wichtigsten Antworten auf Fragen bereit zu haben kann jederzeit die interne %s aufgerufen werden (Aufruf auch von der AEC-Verwaltung aus). Dort stehen auch weitere Tips zur nachfolgenden Einrichtung von AEC' );
define( '_AEC_INST_NOTE_HELP_LTEXT',			'Hilfe' );
define( '_AEC_INST_HINTS',						'Hinweise' );
define( '_AEC_INST_HINT1',						'Bitte besuchen Sie auch unser <a href="%s" target="_blank">Forum</a>. Neben Diskussionen k&ouml;nnen auch weitere Tips, Anregungen usw. dort nachgelesen werden' );
define( '_AEC_INST_HINT2',						'Auf alle F&auml;lle (und ganz speziell wenn AEC auf einer Liveseite eingesetzt wird), in Ruhe alle Einstellungen durchgehen, einen Testzugang f&uuml;r die diversen Bezahl-Gateways anlegen und diese ausgiebig testen!' );
define( '_AEC_INST_ATTENTION',					'Immer die aktuellsten Programme von und f&uuml;r AEC einsetzen' );
define( '_AEC_INST_ATTENTION1',					'Falls noch &auml;ltere AEC-Loginmodule in Verwendung sind, bitte deinstallieren und gegen die regul&auml;ren austauschen (egal ob Joomla, Mambo, CB, etc.)' );
define( '_AEC_INST_MAIN_COMP_ENTRY',			'AEC Abo Verwaltung' );
define( '_AEC_INST_ERRORS',						'<strong>Achtung</strong><br />leider traten w&auml;hrend der Installation folgende Fehler auf - AEC konnte <strong>nicht</strong>vollst&auml;ndig installiert werden:<br />' );

// help
define( '_AEC_CMN_HELP',						'Hilfe' );
define( '_AEC_HELP_DESC',						'Auf dieser Seite &uuml;berpr&uuml;ft AEC die bestehende Konfiguration und zeigt eventuelle Fehler an welche bereinigt werden sollten' );
define( '_AEC_HELP_GREEN',						'Gr&uuml;n</strong> bedeutet Mitteilungen oder Probleme die bereits gel&ouml;st wurden' );
define( '_AEC_HELP_YELLOW',						'Gelb</strong> weist haupts&auml;chlich auf kosmetische Punkte hin (z.B. Benutzerlink zum Frontent hinzuf&uuml;gen), aber auch Punkte die im Ermessen des Admin liegen' );
define( '_AEC_HELP_RED',						'Rot</strong> weist auf Probleme bez&uuml;glich Sicherheit oder anderer Wichtigkeit hin' );
define( '_AEC_HELP_GEN',						'Hinweis: es wird versucht so viel wie m&ouml;glich zu &uuml;berpr&uuml;fen, dennoch besteht kein Anspruch auf Vollst&auml;ndigkeit!' );
define( '_AEC_HELP_QS_HEADER',					'AEC Schnellstart Handbuch' );
define( '_AEC_HELP_QS_DESC',					'Bevor mit den unten angef&uuml;hrten Aktionen fortgefahren wird, sollte vorher das %s gelesen werden!' );
define( '_AEC_HELP_QS_DESC_LTEXT',				'Schnellstart Handbuch' );
define( '_AEC_HELP_SER_SW_DIAG1',				'Dateirechteproblem' );
define( '_AEC_HELP_SER_SW_DIAG1_DESC',			'AEC hat den Server als Apache Webserver identifiziert. Um &Auml;nderungen an diesen Dateien durchf&uuml;hren zu k&ouml;nnen, m&uuml;ssen diese dem Webserverbenutzer geh&ouml;ren was momentan offensichtlich nicht so ist.' );
define( '_AEC_HELP_SER_SW_DIAG1_DESC2',			'Es wird empfohlen f&uuml;r die Dauer der &Auml;nderungen die Dateirechte auf 0777 zu &auml;ndern. Nach Durchf&uuml;hrung der &Anderung m&uuml;ssen diese Rechte wieder auf den Originalzustand zur&uuml;ckgesetzt werden!<br />Dies gilt auch f&uuml;r die weiter unten erw&auml;hnten Dateirechte.' );
define( '_AEC_HELP_SER_SW_DIAG2',				'CMS Dateirechte' );
define( '_AEC_HELP_SER_SW_DIAG2_DESC',			'AEC hat erkannt, dass dieses CMS nicht die Rechte des Webservers besitzt.' );
define( '_AEC_HELP_SER_SW_DIAG2_DESC2',			'Wenn ein SSH-Zugang zum Server vorhanden ist, in das Verzeichnis "<cmsinstallation/includes>" und geben dann entweder "chown wwwrun joomla.php" (oder "chown wwwrun mambo.php" - falls Mambo verwendet wird) eingeben.' );
define( '_AEC_HELP_SER_SW_DIAG3',				'Veraltete &Auml;nderungen erkannt' );
define( '_AEC_HELP_SER_SW_DIAG3_DESC',			'Es sieht so aus als wenn wenigstens eine &Auml;nderung nicht aktuell ist!<br />Damit AEC ordnungsgem&auml;ss funktionieren kann, m&uuml;ssen fr&uuml;her gemachte, veraltete &Auml;nderungen wieder aus den Dateien entfernt werden. Dazu entweder den Abschnitt mit der neuen (ge&auml;nderten) Funktion &uuml;ber die Hacks-Seite entfernen, oder die ge&auml;nderte Datei mit der Originaldatei &uuml;berschreiben.' );
define( '_AEC_HELP_SER_SW_DIAG4',				'Dateirechte Probleme' );
define( '_AEC_HELP_SER_SW_DIAG4_DESC',			'AEC kann die Schreibrechte der Dateien welche ge&auml;ndert werden m&uuml;ssen nicht erkennen. Entweder ist das hier ein WINDOWS-Server oder der Apacheserver wurde mit der Option "--disable-posix" kompiliert.<br /><strong>Sollten die &Auml;nderungen durchgef&uuml;hrt werden, dann jedoch nicht funktionieren liegt das Problem bei den Dateirechten</strong>' );
define( '_AEC_HELP_SER_SW_DIAG4_DESC2',			'Es wird empfohlen entweder den Server mit der erw&auml;hnten Option zu kompilieren (Apache) oder den Admin zu kontaktieren' );
define( '_AEC_HELP_DIAG_CMN1',					'CMS &Auml;nderungen' );
define( '_AEC_HELP_DIAG_CMN1_DESC',				'Notwendig damit die Benutzer nach dem Login von AEC &uuml;berpr&uuml;ft werden k&ouml;nnen' );
define( '_AEC_HELP_DIAG_CMN1_DESC2',			'Zur Hacks-Seite gehen und &Auml;nderung durchf&uuml;hren' );
define( '_AEC_HELP_DIAG_CMN2',					'Meine Abos - Men&uuml;eintrag' );
define( '_AEC_HELP_DIAG_CMN2_DESC',				'Ein Link der die Benutzer zu ihren eigenen Abonnements f&uuml;hrt' );
define( '_AEC_HELP_DIAG_CMN2_DESC2',			'Zur Hacks-Seite gehen und Link erstellen' );
define( '_AEC_HELP_DIAG_CMN3',					'JACL nicht installiert' );
define( '_AEC_HELP_DIAG_CMN3_DESC',				'Sollte geplant sein, JACLPlus (oder &auml;hnliches) zu installieren, muss auf die AEC-&Auml;nderungen R&uuml;cksicht genommen werden! Sollten diese &Auml;nderungen bereits durchgef&uuml;hrt worden sein, kann dies auf der Hacks-Seite ge&auml;ndert werden' );
define( '_AEC_HELP_DIAG_NO_PAY_PLAN',			'Kein aktives Abonnement vorhanden!' );
define( '_AEC_HELP_DIAG_NO_PAY_PLAN_DESC',		'Entweder wurde noch kein Abonnement definiert oder es wurde vergessen ein Vorhandes zu aktivieren - AEC ben&ouml;nigt mindestens ein aktives Abo' );
define( '_AEC_HELP_DIAG_GLOBAL_PLAN',			'Generelles Abonnement' );
define( '_AEC_HELP_DIAG_GLOBAL_PLAN_DESC',		'In der AEC-Konfiguration wurde ein Abo als globales Einstiegsabo definiert welches jeder neue Abonnent automatisch ohne Wahl zugewiesen bekommt.<br />Falls das nicht so sein soll, muss dieses Abo in der Konfiguration deaktiviert werden' );
define( '_AEC_HELP_DIAG_SERVER_NOT_REACHABLE',	'Server nicht erreichbar' );
define( '_AEC_HELP_DIAG_SERVER_NOT_REACHABLE_DESC',	'Es sieht so aus, als ob der Server momentan nicht erreichbar ist!<br />Entweder wurde AEC lokal installiert oder die Verbindung zum Server ist unterbrochen. Um jedoch alle AEC-Funktionen und Zahlungsbenachrichtigungen ausf&uuml;hren zu k&ouml;nnen, muss AEC auf einem erreichbarem Server installiert sein!' );
define( '_AEC_HELP_DIAG_SITE_OFFLINE',			'Webseite ist Offline' );
define( '_AEC_HELP_DIAG_SITE_OFFLINE_DESC',		'Die Webseite ist momentan <strong>OFFLINE</strong> geschaltet. Dies kann einen Einflu&szlig; auf die Zahlungen und Benachrichtigungen dazu haben!' );
define( '_AEC_HELP_DIAG_REG_DISABLED',			'Benutzerregistrierung abgeschaltet' );
define( '_AEC_HELP_DIAG_REG_DISABLED_DESC',		'Die Benutzerregistrierung ist abgeschaltet. Dadurch k&ouml;nnen sich keine neuen Abonnenten einschreiben bzw. Besucher registrieren' );
define( '_AEC_HELP_DIAG_LOGIN_DISABLED',		'Benutzerlogin abgeschaltet' );
define( '_AEC_HELP_DIAG_LOGIN_DISABLED_DESC',	'Der Benutzerlogin im Frontend ist abgeschaltet! Dadurch kann keiner der Abonnenten die Webseite betreten' );
define( '_AEC_HELP_DIAG_PAYPAL_BUSS_ID',		'PayPal &Uuml;berpr&uuml;fung Gesch&auml;fts-ID' );
define( '_AEC_HELP_DIAG_PAYPAL_BUSS_ID_DESC',	'Diese Funktion &uuml;berpr&uuml;ft die vorhandene PayPal-ID auf erweiterte Sicherheit bei Transaktionen' );
define( '_AEC_HELP_DIAG_PAYPAL_BUSS_ID_DESC1',	'Falls PayPal-Zahlungen eintreffen sollten, die Abonnenten jedoch nicht automatisch aktiviert werden, ist diese Einstellung in der AEC-Konfiguration abzuschalten.<br />Ebenfalls deaktivieren, wenn mehrere PayPal-Emailadressen in Verwendung sind!' );

// micro integration
	// general
define( '_AEC_MI_REWRITING_INFO',				'Vorlagen Platzhalter' );
define( '_AEC_MI_SET1_INAME',					'Abonnement auf %s - Benutzer: %s (%s)' );
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
define( '_AEC_CENTR_COUPONS',					'Gutscheine' );
define( '_AEC_CENTR_COUPONS_STATIC',			'Feste Gutscheine' );
define( '_AEC_CENTR_VIEW_HISTORY',				'Archiv' );
define( '_AEC_CENTR_HACKS',						'Hacks' );
define( '_AEC_CENTR_M_INTEGRATION',				'MicroIntegration' );
define( '_AEC_CENTR_HELP',						'Hilfe' );
define( '_AEC_CENTR_LOG',						'Logdatei' );
define( '_AEC_CENTR_MANUAL',					'Manuell' );
define( '_AEC_QUICKSEARCH',						'Schnellsuche' );
define( '_AEC_QUICKSEARCH_DESC',				'Geben sie den Namen, Benutzernamen, Email Addresse, die Benutzer ID oder eine Rechnungsnummer ein um direkt zum Konto eines Benutzers weitergeleitet zu werden. Sollten mehrere Benutzer gefunden werden, so wird unten eine Auswahl angezeigt.' );
define( '_AEC_QUICKSEARCH_MULTIRES',			'Mehrere Benutzer gefunden!' );
define( '_AEC_QUICKSEARCH_MULTIRES_DESC',		'Bitte w&auml;hlen sie einen Benutzer aus:' );
define( '_AEC_QUICKSEARCH_THANKS',				'Danke - Sie haben eine einfache Funktion sehr gl&uuml;cklick gemacht.' );
define( '_AEC_QUICKSEARCH_NOTFOUND',			'Benutzer nicht gefunden' );

// select lists
define( '_AEC_SEL_EXCLUDED',					'Ausgeschlossen' );
define( '_AEC_SEL_PENDING',						'Wartend' );
define( '_AEC_SEL_ACTIVE',						'Aktiv' );
define( '_AEC_SEL_EXPIRED',						'Abgelaufen' );
define( '_AEC_SEL_CLOSED',						'Geschlossen' );
define( '_AEC_SEL_CANCELLED',					'Storniert' );
define( '_AEC_SEL_NOT_CONFIGURED',				'Ni. Konfiguriert / Neu' );

// footer
define( '_AEC_FOOT_TX_CHOOSING',				'Danke dass Sie sich f&uuml;r AEC - Account Expiration Control entschieden haben!' );
define( '_AEC_FOOT_TX_GPL',						'Diese Komponente wurde entwickelt und ver&ouml;ffentlicht unter der <a href="http://www.gnu.org/copyleft/gpl.html" target="_blank" title="GNU/GPL">GNU/GPL</a> von Helder Garcia, David Deutsch und dem Team von <a href="https://globalnerd.org" target="_blank" title="globalnerd.org">globalnerd.org</a>' );
define( '_AEC_FOOT_TX_SUBSCRIBE',				'Weitere Features, professionelles Service, Updates, Handb&uuml;cher und Online Hilfe, einfach auf den Link oben klicken. Es hilft uns vor allem auch bei der weiteren Entwicklung!' );
define( '_AEC_FOOT_CREDIT',						'Bitte auch die %s lesen' );
define( '_AEC_FOOT_CREDIT_LTEXT',				'Credits' );
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
define( '_AEC_MSG_NO_ITEMS_TO_DELETE',			'Kein(e) Datensatz/-s&auml;tze zum L&ouml;schen vorhanden' );
define( '_AEC_MSG_NO_DEL_W_ACTIVE_SUBSCRIBER',	'Abos mit aktiven Abonnenten k&ouml;nnen nicht ge&l&ouml;scht werden!' );
define( '_AEC_MSG_ITEMS_DELETED',				'Datensatz/-s&auml;tze gel&ouml;scht' );
define( '_AEC_MSG_ITEMS_SUCESSFULLY',			'%s Inhalt(e) erfolgreich' );
define( '_AEC_MSG_SUCESSFULLY_SAVED',			'&Auml;nderungen erfolgreich gespeichert' );
define( '_AEC_MSG_ITEMS_SUCC_PUBLISHED',		'Inhalt(e) erfolgreich ver&ouml;ffentlicht' );
define( '_AEC_MSG_ITEMS_SUCC_UNPUBLISHED',		'Inhalt(e) Ver&ouml;ffentlichung erfolgreich zur&uuml;ckgenommen' );
define( '_AEC_MSG_NO_COUPON_CODE',				'Es muss ein Gutscheincode angegeben werden!' );
define( '_AEC_MSG_OP_FAILED',					'Vorgang fehlgeschlagen, konnte %s nicht &ouml;ffnen' );
define( '_AEC_MSG_OP_FAILED_EMPTY',				'Vorgang fehlgeschlagen, kein Inhalt' );
define( '_AEC_MSG_OP_FAILED_NOT_WRITEABLE',		'Vorgang fehlgeschlagen, Datei nicht beschreibbar' );
define( '_AEC_MSG_OP_FAILED_NO_WRITE',			'Vorgang fehlgeschlagen, Datei kann nicht zum Schreiben ge&ouml;ffnet werden' );
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
define( '_COUPON_MI',							'Gateway' );
define( '_COUPON_MI_FULL',						'Gateways' );

define( '_COUPON_GENERAL_NAME_NAME',			'Name' );
define( '_COUPON_GENERAL_NAME_DESC',			'Der interne und externe Name f&uuml;r diesen Gutschein' );
define( '_COUPON_GENERAL_COUPON_CODE_NAME',		'Gutscheincode' );
define( '_COUPON_GENERAL_COUPON_CODE_DESC',		'Den Gutscheincode hier eintragen - der angezeigte (zuf&auml;llig generierte) Code wurde vom System erzeugt.<hr /><strong>Hinweis:</strong><br />Der Code muss einmalig sein!' );
define( '_COUPON_GENERAL_DESC_NAME',			'Beschreibung' );
define( '_COUPON_GENERAL_DESC_DESC',			'Die interne Beschreibung f&uuml;r diesen Gutschein' );
define( '_COUPON_GENERAL_ACTIVE_NAME',			'Aktiv' );
define( '_COUPON_GENERAL_ACTIVE_DESC',			'Ist dieser Gutschein aktiv (momentan g&uuml;ltig)' );
define( '_COUPON_GENERAL_TYPE_NAME',			'Gruppe' );
define( '_COUPON_GENERAL_TYPE_DESC',			'Soll dieser Gutschein einmalig oder f&uuml;r eine Gruppe von mehreren Personen gelten (Einzel- oder Gruppengutschein)' );

define( '_COUPON_GENERAL_MICRO_INTEGRATIONS_NAME',	'Bezahldienste' );
define( '_COUPON_GENERAL_MICRO_INTEGRATIONS_DESC',	'Diejenigen Bezahldienste ausw&auml;hlen welche f&uuml;r diesen Gutschein g&uuml;ltig sein sollen' );

define( '_COUPON_PARAMS_AMOUNT_USE_NAME',		'Betrag verwenden' );
define( '_COUPON_PARAMS_AMOUNT_USE_DESC', 		'Soll von der Rechnung direkt ein Betrag abgezogen werden' );
define( '_COUPON_PARAMS_AMOUNT_NAME',			'Betrag' );
define( '_COUPON_PARAMS_AMOUNT_DESC',			'Hier den Betrag angeben welcher direkt von der Rechnung abgezogen werden soll' );
define( '_COUPON_PARAMS_AMOUNT_PERCENT_USE_NAME',	'Prozente' );
define( '_COUPON_PARAMS_AMOUNT_PERCENT_USE_DESC',	'Sollen x Prozente vom Rechnungsbetrag abgezogen werden' );
define( '_COUPON_PARAMS_AMOUNT_PERCENT_NAME',	'Prozent in %' );
define( '_COUPON_PARAMS_AMOUNT_PERCENT_DESC',	'Hier angeben wieviele Prozente vom Betrag abgezogen werden sollen' );
define( '_COUPON_PARAMS_PERCENT_FIRST_NAME',	'Prozente vor Betrag' );
define( '_COUPON_PARAMS_PERCENT_FIRST_DESC',	'Wenn die Kombination von Prozente und Betrag angewendet werden soll, vorher die Prozente abziehen?' );
define( '_COUPON_PARAMS_USEON_TRIAL_NAME',		'Bei Testabo?' );
define( '_COUPON_PARAMS_USEON_TRIAL_DESC',		'Sollen die Benutzer diese Erm&auml;ssigung auch bei einem Testabo ausw&auml;hlen d&uuml;rfen?' );
define( '_COUPON_PARAMS_USEON_FULL_NAME',		'Bei Vollabo?' );
define( '_COUPON_PARAMS_USEON_FULL_DESC',		'Sollen die Benutzer diese Erm&auml;ssigung vom aktuellen Betrag abziehen k&ouml;nnen? (bei wiederholenden Abos wird die Erm&auml;ssigung nur vom ersten Rechnungsbetrag abgezogen!)' );
define( '_COUPON_PARAMS_USEON_FULL_ALL_NAME',	'Jede Rechnung?' );
define( '_COUPON_PARAMS_USEON_FULL_ALL_DESC',	'Falls der Benutzer wiederholende Abos besitzt, soll die Erm&auml;ssigung jedes Mal abgezogen werden? Wenn nur beim ersten Mal, dann Nein)' );

define( '_COUPON_PARAMS_HAS_START_DATE_NAME',	'Beginndatum' );
define( '_COUPON_PARAMS_HAS_START_DATE_DESC',	'Soll der Gutschein f&uuml;r einen bestimmten Zeitraum gelten?' );
define( '_COUPON_PARAMS_START_DATE_NAME',		'Datum' );
define( '_COUPON_PARAMS_START_DATE_DESC',		'Beginndatum der Periode ausw&auml;hlen f&uuml;r den dieser Gutschein g&uuml;ltig sein soll' );
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
define( '_COUPON_RESTRICTIONS_MINGID_ENABLED_DESC', 'Hier die Mindestgruppen ID definieren f&uuml;r welche diesen Gutschein g&uuml;ltig sein soll' );
define( '_COUPON_RESTRICTIONS_MINGID_NAME',			'Sichtbare Gruppe:' );
define( '_COUPON_RESTRICTIONS_MINGID_DESC',			'Die Mindestbenutzerebene welche diesen Gutschein einsetzen kann' );
define( '_COUPON_RESTRICTIONS_FIXGID_ENABLED_NAME', 'Fixe Gruppen ID:' );
define( '_COUPON_RESTRICTIONS_FIXGID_ENABLED_DESC', 'Soll dieser Gutschein nur f&uuml;r eine bestimmte Gruppe gelten' );
define( '_COUPON_RESTRICTIONS_FIXGID_NAME',			'Gruppe:' );
define( '_COUPON_RESTRICTIONS_FIXGID_DESC',			'Nur Benutzer dieser Gruppe k&ouml;nnen diesen Gutschein einsetzen' );
define( '_COUPON_RESTRICTIONS_MAXGID_ENABLED_NAME', 'Maximale Gruppen ID:' );
define( '_COUPON_RESTRICTIONS_MAXGID_ENABLED_DESC', 'Welche maximale Gruppen ID darf f&uuml;r diesen Gutschein verwenden d&uuml;rfen?' );
define( '_COUPON_RESTRICTIONS_MAXGID_NAME',			'Gruppe:' );
define( '_COUPON_RESTRICTIONS_MAXGID_DESC',			'Die am h&ouml;chsten eingestufte Gruppe welche diesen Gutschein einsetzen darf' );

define( '_COUPON_RESTRICTIONS_PREVIOUSPLAN_REQ_ENABLED_NAME',	'Voriges Abo:' );
define( '_COUPON_RESTRICTIONS_PREVIOUSPLAN_REQ_ENABLED_DESC',	'Wird f&uuml;r diesen Gutschein ein bestimmtes Abo <strong>vorher</strong> ben&ouml;tigt' );
define( '_COUPON_RESTRICTIONS_PREVIOUSPLAN_REQ_NAME',			'Abo:' );
define( '_COUPON_RESTRICTIONS_PREVIOUSPLAN_REQ_DESC',			'Benutzer welche dieses Abo schon einmal verwendet hatten sind f&uuml;r diesen Gutschein berechtigt' );
define( '_COUPON_RESTRICTIONS_CURRENTPLAN_REQ_ENABLED_NAME',	'Aktuelles Abo:' );
define( '_COUPON_RESTRICTIONS_CURRENTPLAN_REQ_ENABLED_DESC',	'Aktuelles Abo ist mindestens Voraussetzung' );
define( '_COUPON_RESTRICTIONS_CURRENTPLAN_REQ_NAME',			'Abo:' );
define( '_COUPON_RESTRICTIONS_CURRENTPLAN_REQ_DESC',			'Nur Benutzer welche aktuell im Besitz dieses Abos sind oder es schon mal vorher hatten d&uuml;rfen diesen Gutschein verwenden' );
define( '_COUPON_RESTRICTIONS_OVERALLPLAN_REQ_ENABLED_NAME',	'Erforderlich:' );
define( '_COUPON_RESTRICTIONS_OVERALLPLAN_REQ_ENABLED_DESC',	'Akivieren wenn Abo vorher erforderlich ist' );
define( '_COUPON_RESTRICTIONS_OVERALLPLAN_REQ_NAME',			'Abo:' );
define( '_COUPON_RESTRICTIONS_OVERALLPLAN_REQ_DESC',			'Nur Benutzer welche schon irgendwann vorher dieses Abo verwendet hatten d&uuml;rfen diesen Gutschein verwenden' );

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

define( '_COUPON_RESTRICTIONS_RESTRICT_COMBINATION_NAME', 'Restrict Combination:');
define( '_COUPON_RESTRICTIONS_RESTRICT_COMBINATION_DESC', 'Choose to not let your users combine this coupon with one of the following');
define( '_COUPON_RESTRICTIONS_BAD_COMBINATIONS_NAME', 'Coupons:');
define( '_COUPON_RESTRICTIONS_BAD_COMBINATIONS_DESC', 'Make a selection which coupons this one is not to be used with');

// END 0.12.4 (mic) ####################################################

// --== BACKEND TOOLBAR ==--
define( '_EXPIRE_SET',				'Ablaufdatum:' );
define( '_EXPIRE_NOW','Jetzt' );
define( '_EXPIRE_EXCLUDE','Herausnehmen' );
define( '_EXPIRE_INCLUDE','Wiedereinstellen' );
define( '_EXPIRE_CLOSE','Schlie&szlig;en' );
define( '_EXPIRE_01MONTH','in 1 Monat' );
define( '_EXPIRE_03MONTH','in 3 Monaten' );
define( '_EXPIRE_12MONTH','in 12 Monaten' );
define( '_EXPIRE_ADD01MONTH','plus 1 Monat' );
define( '_EXPIRE_ADD03MONTH','plus 3 Monate' );
define( '_EXPIRE_ADD12MONTH','plus 12 Monate' );
define( '_CONFIGURE','Konfigurieren' );
define( '_REMOVE','Ausschliessen' );
define( '_CNAME','Name' );
define( '_USERLOGIN','Login' );
define( '_EXPIRATION','Auslauf' );
define( '_USERS','Benutzer' );
define( '_DISPLAY','Anzeigen' );
define( '_NOTSET','Ausgenommen' );
define( '_SAVE','Speichern' );
define( '_CANCEL','Abbrechen' );
define( '_EXP_ASC',					'Auslauf A-Z' );
define( '_EXP_DESC',				'Auslauf Z-A' );
define( '_NAME_ASC',				'Name A-Z' );
define( '_NAME_DESC',				'Name Z-A' );
define( '_LOGIN_ASC',				'Login A-Z' );
define( '_LOGIN_DESC',				'Login Z-A' );
define( '_SIGNUP_ASC',				'Abschlussdatum A-Z' );
define( '_SIGNUP_DESC',				'Abschlussdatum Z-A' );
define( '_LASTPAY_ASC',				'Letzte Zahlung A-Z' );
define( '_LASTPAY_DESC',			'Letzte Zahlung Z-A' );
define( '_PLAN_ASC',				'Abos A-Z' );
define( '_PLAN_DESC',				'Abos Z-A' );
define( '_STATUS_ASC',				'Status A-Z' );
define( '_STATUS_DESC',				'Status Z-A' );
define( '_TYPE_ASC',				'Zahlung Type A-Z' );
define( '_TYPE_DESC',				'Zahlung Type Z-A' );
define( '_ORDER_BY','Sortieren nach:' );
define( '_SAVED', 'Gespeichert.' );
define( '_CANCELED', 'Abgebrochen.' );
define( '_CONFIGURED',				'Eintrag konfiguriert.' );
define( '_REMOVED',					'Eintrag aus Liste gel&ouml;scht.' );
define( '_EOT_TITLE', 'Geschlossene Abonnements' );
define( '_EOT_DESC', 'Diese Liste enth&auml;lt keine manuellen Abonnements, nur solche, die durch Online-Zahlung abgeschlossen wurden. Das L&ouml;schen eines Eintrages l&ouml;scht den betreffenden Benutzer aus der Datenbank und seine Eintr&auml;ge im Zahlungsverlauf.' );
define( '_EOT_DATE', 'Ende des Zeitraums' );
define( '_EOT_CAUSE', 'Grund' );
define( '_EOT_CAUSE_FAIL', 'Zahlung fehlgeschlagen' );
define( '_EOT_CAUSE_BUYER', 'Vom Benutzer abgebrochen' );
define( '_EOT_CAUSE_FORCED', 'Vom Administrator abgebrochen' );
define( '_REMOVE_CLOSED', 'Benutzer l&ouml;schen' );
define( '_FORCE_CLOSE', 'Schliessen erzwingen' );
define( '_PUBLISH_PAYPLAN', 'Ver&ouml;ffentlichen' );
define( '_UNPUBLISH_PAYPLAN', 'Unver&ouml;ffentlichen' );
define( '_NEW_PAYPLAN', 'Neu' );
define( '_EDIT_PAYPLAN', 'Bearbeiten' );
define( '_REMOVE_PAYPLAN', 'L&ouml;schen' );
define( '_SAVE_PAYPLAN', 'Speichern' );
define( '_CANCEL_PAYPLAN', 'Abbrechen' );
define( '_PAYPLANS_TITLE', 			'Abonnement Verwaltung' );
define( '_PAYPLANS_MAINDESC',		'Ver&ouml;ffentlichte Abos werden den Benutzern angezeigt' );
define( '_PAYPLAN_NAME', 'Name' );
define( '_PAYPLAN_DESC', 'Beschreibung (ersten 50 Zeichen)' );
define( '_PAYPLAN_ACTIVE', 'Aktiv' );
define( '_PAYPLAN_VISIBLE', 'Sichtbar' );
define( '_PAYPLAN_A3', 'Rate' );
define( '_PAYPLAN_P3', 'Zeitraum' );
define( '_PAYPLAN_T3', 'Einheit des Zeitraumes' );
define( '_PAYPLAN_USERCOUNT', 'Abonnenten' );
define( '_PAYPLAN_EXPIREDCOUNT', 'abgelaufen' );
define( '_PAYPLAN_TOTALCOUNT', 'Total' );
define( '_PAYPLAN_REORDER', 'Neuordnen' );
define( '_PAYPLAN_DETAIL', 'Einstellungen' );
define( '_ALTERNATIVE_PAYMENT',		'Bank&uuml;berweisung' );
define( '_SUBSCR_DATE', 'Anmeldedatum' );
define( '_ACTIVE_TITLE', 'Aktive Abonnements' );
define( '_ACTIVE_DESC', 'Diese Liste enth&auml;lt keine manuellen Abonnements, nur solche, die durch ein Zahlungsgateway abgeschlossen wurden.' );
define( '_LASTPAY_DATE', 'Datum der letzten Zahlung' );
define( '_USERPLAN', 'Plan' );
define( '_CANCELLED_TITLE', 'Abgebrochene Abonnements' );
define( '_CANCELLED_DESC', 'Diese Liste enth&auml;lt keine manuellen Abonnements, nur solche, die durch ein Zahlungsgateway abgeschlossen wurden. Es sind die Abonnements, die von den Benutzern abgebrochen wurden, aber noch nicht ausgelaufen sind.' );
define( '_CANCEL_DATE', 'Datum des Abbruches' );
define( '_MANUAL_DESC', 'Wird ein Eintrag gel&ouml;scht, wird der Benutzer vollst&auml;ndig aus der Datenbank gel&ouml;scht.' );
define( '_REPEND_ACTIVE',			'Wiederaufgenommen' );
define( '_FILTER_PLAN',				'- Plan ausw&auml;hlen -' );
define( '_BIND_USER',				'Zuweisen zu:' );
define( '_PLAN_FILTER',				'Abofilter:' );
define( '_CENTRAL_PAGE',			'Zentrale' );

// --== USER FORM ==--
define( '_HISTORY_COL1_TITLE',			'Rechnung' );
define( '_HISTORY_COL2_TITLE',			'Betrag' );
define( '_HISTORY_COL3_TITLE',			'Bezahlt' );
define( '_HISTORY_COL4_TITLE',			'Methode' );
define( '_HISTORY_COL5_TITLE',			'Aktion' );
define( '_HISTORY_COL6_TITLE',			'Abo' );
define( '_USERINVOICE_ACTION_REPEAT',	'Wiederholung' );
define( '_USERINVOICE_ACTION_CANCEL',	'l&ouml;schen' );
define( '_USERINVOICE_ACTION_CLEAR',	'als&nbsp;bezahlt&nbsp;markieren' );
define( '_USERINVOICE_ACTION_CLEAR_APPLY',	'als&nbsp;bezahlt&nbsp;markieren&nbsp;&amp;&nbsp;Abo&nbsp;anwenden' );

// --== BACKEND SETTINGS ==--

// TAB 1 - Global AEC Settings
define( '_CFG_TAB1_TITLE',				'Konfigurationsoptionen' );
define( '_CFG_TAB1_SUBTITLE', 'Optionen f&uuml;r die Interaktion mit dem Benutzer' );

define( '_CFG_TAB1_OPT1NAME',			'Unmittelbarer Ablauf:' );
define( '_CFG_TAB1_OPT1DESC',			'Standard Ablaufzeitraum, in Tagen, f&uuml;r neue Registrierungen. Die Zahl verh&auml;lt sich relativ zum Registrierungsdatum, wenn also Benutzer grunds&auml;tzlich als Abgelaufen registriert werden sollen, -1 (minus eins) w&auml;hlen.<br />Dies hat keinen Effekt auf den normalen Anmeldevorgang &uuml;ber ein Zahlungsgateway' );
define( '_CFG_TAB1_OPT3NAME',			'Alarmebene 2:' );
define( '_CFG_TAB1_OPT3DESC',			'In Tagen. Dies ist die erste Grenze die beginnt den Benutzer auf den Auslauf seines Abonnements hinzuweisen.' );
define( '_CFG_TAB1_OPT4NAME',			'Alarmebene 1:' );
define( '_CFG_TAB1_OPT4DESC',			'In Tagen. Dies ist die letzte Grenze die beginnt den Benutzer auf den Auslauf seines Abonnements hinzuweisen.' );
define( '_CFG_TAB1_OPT5NAME',			'Einstiegsplan:' );
define( '_CFG_TAB1_OPT5DESC',			'Jeder Benutzer wird - wenn keine Abonnement - ohne Bezahlung diesem Plan zugewiesen' );
define( '_CFG_TAB1_OPT9NAME',			'Erfordert Einschreibung:' );
define( '_CFG_TAB1_OPT9DESC',			'Wenn aktiviert, <strong>muss</strong> der Benutzer ein g&uuml;ltiges Abonnement besitzen. Nicht aktiviert, Benutzer k&ouml;nnen ohne Abo einloggen.' );

define( '_CFG_TAB1_OPT10NAME',			'Gateway Erkl&auml;rungen:' );
define( '_CFG_TAB1_OPT10DESC',			'Hier Bezahlm&ouml;glichkeiten markieren welche auf der Nichterlaubt-Seite angezeigt werden sollen (diese Liste sehen die Benutzer, wenn sie versuchen eine Seite anzusehen f&uuml;r die sie keine Berechtigung haben).<br /><strong>Hinweis: es werden nur die oben, zur Zeit Aktiven angezeigt</strong>' );
define( '_CFG_TAB1_OPT20NAME',			'Aktivierte Zahlungsgateways:' );
define( '_CFG_TAB1_OPT20DESC',			'Alle Gateways markieren welche aktiv sein sollen (STRG-Taste dr&uuml;cken f&uuml;r mehrere).<br /><strong>Um die ge&auml;nderten Einstellungen anzuzeigen, den Button Speichern anklicken</strong><br />Deaktivieren eines Gateways l&ouml;scht nicht die bisherigen Einstellungen.' );
define( '_CFG_TAB1_OPT11NAME',			'Erfordert Abschluss:' );
define( '_CFG_TAB1_OPT11DESC',			'Als Standardvorgabe gilt, wenn ein Benutzer kein Abo besitzt kann er sich trotzdem einloggen. Mit dieser Einstellung <strong>muss</strong> er ein Abo abschliessen.' );
define( '_CFG_ENTRYPLAN_NAME',			'Standard Probeplan' );
define( '_CFG_ENTRYPLAN_DESC',			'Der Standard-Plan f&uuml;r die freie Probezeit.' );

define( '_CFG_TAB1_OPT15NAME',			'Komponenten abschalten:' );
define( '_CFG_TAB1_OPT15DESC',			'Alle zu deaktivierenden Zusatzkomponenten angeben (mit Komma trennen!). Zur werden unterst&uuml;tzt: <strong>CB,CBE,CBM,JACL,SMF,UE,UHP2,VM</strong>.<br />Sollte z.B. CB (CommunityBuilder) deinstalliert werden aber dessen Datenbanktabellen noch vorhanden sein, jedoch hier <strong>kein</strong> Eintrag vermerkt sein, wird AEC dann weiterhin CB als installiert ansehen!.' );

define( '_CFG_TAB1_OPT16NAME',			'Einfache URLs:' );
define( '_CFG_TAB1_OPT16DESC',			'SEF-URLs der jeweiligen Komponenten abschalten. Falls bei der Verendung von SEF-URLs Fehler auftauchen (FEHLER 404) wurde in der SEF-Konfiguration ein Fehler gemacht - das Abschalten dieser Option hier kann diese Fehler beseitigen.' );
define( '_CFG_TAB1_OPT17NAME',			'Ablaufschonfrist:' );
define( '_CFG_TAB1_OPT17DESC',			'Anzahl der Stunden welche AEC als Polster nimmt bevor der Account abgeschalten wird. Es sollte bedacht werden, dass der Zahlungseingang etliche Stunden dauern kann (t.w. bis zu 14 Stunden!)' );
define( '_CFG_TAB1_OPT18NAME',			'Cronjob Zyklus:' );
define( '_CFG_TAB1_OPT18DESC',			'Anzahl der Stunden die AEC als Trigger nimmt um anstehende, wiederkehrende Aktionen (wie z.B. Emailversand) auszuf&uuml;hren.' );
define( '_CFG_TAB1_OPT21NAME',			'Plan Zuerst:' );
define( '_CFG_TAB1_OPT21DESC',			'Wenn alle o.a. Zusatzkomponenten mit einer Aboaktion verbunden sind, aktiviert diese Option diese M&ouml;glichkeit. Falls das nicht gew&uuml;nscht ist oder nur die erste Integrationsm&ouml;glichkeit zur Auswahl stehen soll, dann hier nicht aktivieren - die Aboauswahl kommt dann <strong>nach</strong> der Anmeldung/Registrierung.' );

define( '_CFG_TAB_CUSTOMIZATION_TITLE',	'Anpassen' );
define( '_CFG_TAB1_OPT12NAME',			'Individuelle Einstiegsseite:' );
define( '_CFG_TAB1_OPT12DESC',			'Hier den kompletten Link (inkl. http://) angeben der zur Einstiegsseite f&uuml;hren soll. Diese Seite sollte einen Link wie z.B. http://www.yourdomain.com/index.php?option=com_acctexp&amp;task=subscribe&amp;intro=1 beinhalten welcher die Einf&uuml;hrung &uuml;bergeht und den Benutzer direkt zur Aboseite oder Registrierungsseite f&uuml;hrt.<br />Wenn diese Option nicht gew&uuml;nscht wird, dann dieses Feld leer lassen.' );
define( '_CFG_TAB1_OPT13NAME',			'Link zu individueller Dankeseite:' );
define( '_CFG_TAB1_OPT13DESC',			'Kompletten Link (inkl. http://) angeben welchen die Besucher zur Dankeseite f&uuml;hrt. Wenn nicht gew&uuml;nscht dann leer lassen.' );
define( '_CFG_TAB1_OPT14NAME',			'Link zu individueller Abbruchseite:' );
define( '_CFG_TAB1_OPT14DESC',			'Kompletten Link (inkl. http://) angeben welchen die Besucher - wenn Abbruch - zu dieser  Seite f&uuml;hrt. Wenn nicht gew&uuml;nscht dann leer lassen.' );
define( '_CFG_TAB1_OPT19NAME',			'Link zu den AGBs:' );
define( '_CFG_TAB1_OPT19DESC',			'Hier den Link zu den AGBS angeben. Die Benutzer m&uuml;ssen dann dort zum Einverst&auml;ndnis eine Checkbox aktivieren. Leer lassen wenn nicht gew&uuml;nscht' );
define( '_CFG_GENERAL_CUSTOMNOTALLOWED_DESC',	'Hier den kompletten Link (inkl. http://) angeben welche die Besucher zur Nichterlaubtseite f&uuml;hrt. Leer lassen wenn nicht gew&uuml;nscht.' );

define( '_CFG_GENERAL_DISPLAY_DATE_FRONTEND_NAME',	'Frontend Datumsformat' );
define( '_CFG_GENERAL_DISPLAY_DATE_FRONTEND_DESC',	'Hier angeben wie die Datumsangaben den Besuchern gegen&uuml;ber erfolgen sollen. Mehr dazu im <a href="http://www.php.net/manual/de/function.strftime.php" target="_blank" title="PHP Handbuch">PHP Handbuch</a>' );
define( '_CFG_GENERAL_DISPLAY_DATE_BACKEND_NAME',	'Backend Datumsformat' );
define( '_CFG_GENERAL_DISPLAY_DATE_BACKEND_DESC',	'Hier angeben wie die Datumsangaben im Backend erfolgen sollen. Mehr dazu im <a href="http://www.php.net/manual/de/function.strftime.php" target="_blank" title="PHP Handbuch">PHP Handbuch</a>' );
define( '_CFG_GENERAL_CUSTOMTEXT_PLANS_NAME',		'Text Abo&uuml;bersicht' );
define( '_CFG_GENERAL_CUSTOMTEXT_PLANS_DESC',		'Individueller Text zur &Uuml;bersicht der Abonnements' );
define( '_CFG_GENERAL_CUSTOMTEXT_CONFIRM_NAME',		'Text Best&auml;tigungsseite' );
define( '_CFG_GENERAL_CUSTOMTEXT_CONFIRM_DESC',		'Individueller Text der Best&auml;tigungsseite' );
define( '_CFG_GENERAL_CUSTOMTEXT_CHECKOUT_NAME',	'Text Checkout Seite' );
define( '_CFG_GENERAL_CUSTOMTEXT_CHECKOUT_DESC',	'Individueller Text der Checkoutseite' );
define( '_CFG_GENERAL_CUSTOMTEXT_NOTALLOWED_NAME',	'Text Nichterlaubt Seite' );
define( '_CFG_GENERAL_CUSTOMTEXT_NOTALLOWED_DESC',	'Individueller Text der Nichterlaubtseite' );
define( '_CFG_GENERAL_CUSTOMTEXT_PENDING_NAME',		'Text Warteseite' );
define( '_CFG_GENERAL_CUSTOMTEXT_PENDING_DESC',		'Individueller Text der Warteseite' );
define( '_CFG_GENERAL_CUSTOMTEXT_EXPIRED_NAME',		'Text Abgelaufenseite' );
define( '_CFG_GENERAL_CUSTOMTEXT_EXPIRED_DESC',		'Individueller Text der Abgelaufenseite' );

define( '_CFG_GENERAL_CUSTOMTEXT_CONFIRM_KEEPORIGINAL_NAME',	'Behalte Originaltext' );
define( '_CFG_GENERAL_CUSTOMTEXT_CONFIRM_KEEPORIGINAL_DESC',	'Diese Option aktivieren, wenn der Originaltext auf der Best&auml;tigungseite angezeigt werden soll (anstatt des Individuellen)' );
define( '_CFG_GENERAL_CUSTOMTEXT_CHECKOUT_KEEPORIGINAL_NAME',	'Behalte Originaltext' );
define( '_CFG_GENERAL_CUSTOMTEXT_CHECKOUT_KEEPORIGINAL_DESC',	'Diese Option aktivieren, wenn der Originaltext auf der Checkoutseite angezeigt werden soll (anstatt des Individuellen)' );
define( '_CFG_GENERAL_CUSTOMTEXT_NOTALLOWED_KEEPORIGINAL_NAME', 'Behalte Originaltext' );
define( '_CFG_GENERAL_CUSTOMTEXT_NOTALLOWED_KEEPORIGINAL_DESC', 'Diese Option aktivieren, wenn der Originaltext auf der Nichterlaubtseite angezeigt werden soll (anstatt des Individuellen)' );
define( '_CFG_GENERAL_CUSTOMTEXT_PENDING_KEEPORIGINAL_NAME',	'Behalte Originaltext' );
define( '_CFG_GENERAL_CUSTOMTEXT_PENDING_KEEPORIGINAL_DESC',	'Diese Option aktivieren, wenn der Originaltext auf der Warteseite angezeigt werden soll (anstatt des Individuellen)' );
define( '_CFG_GENERAL_CUSTOMTEXT_EXPIRED_KEEPORIGINAL_NAME',	'Behalte Originaltext' );
define( '_CFG_GENERAL_CUSTOMTEXT_EXPIRED_KEEPORIGINAL_DESC',	'Diese Option aktivieren, wenn der Originaltext auf der Abgelaufenseite angezeigt werden soll (anstatt des Individuellen)' );

define( '_CFG_GENERAL_HEARTBEAT_CYCLE_BACKEND_NAME',			'Heartbeat Zyklus Backend:' );
define( '_CFG_GENERAL_HEARTBEAT_CYCLE_BACKEND_DESC',			'Heartbeat - Herzklopfen: eine Funktion welche angegebene Funktionen am Leben h&auml;lt. Hier die Anzahl der Stunden angeben welche AEC als Abhorchrythmus nehmen soll um dann die spezifizierten Funktionen auszuf&uuml;hren' );
define( '_CFG_GENERAL_ENABLE_COUPONS_NAME',				'Gutscheine Aktiviert:' );
define( '_CFG_GENERAL_ENABLE_COUPONS_DESC',				'Sollen Gutscheine akzeptiert werden' );
define( '_CFG_GENERAL_DISPLAYCCINFO_NAME',				'Zeige Kreditkartenicons:' );
define( '_CFG_GENERAL_DISPLAYCCINFO_DESC',				'Sollen die Icons f&uuml;r jedes Gateway angezeigt werden' );
define( '_CFG_GENERAL_ADMINACCESS_NAME', 'Administrator Zugriff:');
define( '_CFG_GENERAL_ADMINACCESS_DESC', 'Hiermit d&uuml;rfen nicht nur Super Administratoren, sondern auch normale Administratoren auf das AEC Backend zugreifen.');
define( '_CFG_GENERAL_NOEMAILS_NAME', 'Keine Emails');
define( '_CFG_GENERAL_NOEMAILS_DESC', 'Mit dieser Einstellung versendet die AEC keine System-Emails mehr (bei Bezahlung einer Rechnung etc.), andere Emails, z.B. von MicroIntegrationen sind hiervon nicht betroffen.');
define( '_CFG_GENERAL_NOJOOMLAREGEMAILS_NAME', 'Keine Joomla Emails');
define( '_CFG_GENERAL_NOJOOMLAREGEMAILS_DESC', 'Mit dieser Einstellung verhindern Sie das versenden von Joomla Registrations-Best&auml;tigungsmails.');

// Global Micro Integration Settings
define( '_CFG_TAB_MICROINTEGRATION_TITLE',				'MicroIntegration' );
define( '_CFG_MI_ACTIVELIST_NAME',						'Aktive MicroIntegrationen' );
define( '_CFG_MI_ACTIVELIST_DESC',						'W&auml;hlen welche MicroIntegrationen aktiv sein sollen' );
define( '_CFG_MI_META_NAME',							'MicroIntegrationen Meta' );
define( '_CFG_MI_META_DESC',							'Sollen die MicroIntegrationen untereinander Variablen austauschen d&uuml;rfen' );

// --== PAYMENT PLAN PAGE ==--
// Additions of variables for free trial periods by Michael Spredemann (scubaguy)

define( '_PAYPLAN_PERUNIT1',							'Tage' );
define( '_PAYPLAN_PERUNIT2',							'Wochen' );
define( '_PAYPLAN_PERUNIT3',							'Monate' );
define( '_PAYPLAN_PERUNIT4',							'Jahre' );

// General Params

define( '_PAYPLAN_DETAIL_TITLE',						'Abo' );
define( '_PAYPLAN_GENERAL_NAME_NAME',					'Name:' );
define( '_PAYPLAN_GENERAL_NAME_DESC',					'Name oder Titel f&uuml;r dieses Abonnement (max. 40 Zeichen)' );
define( '_PAYPLAN_GENERAL_DESC_NAME',					'Beschreibung:' );
define( '_PAYPLAN_GENERAL_DESC_DESC',					'Volltext (max. 255 Zeichen) zu diesem Abonnement wie er den Benutzern angezeigt werden soll' );
define( '_PAYPLAN_GENERAL_ACTIVE_NAME',					'Ver&ouml;ffentlicht:' );
define( '_PAYPLAN_GENERAL_ACTIVE_DESC',					'Ein ver&ouml;ffentlichtes Abo wird den Besuchern im Frontend angezeigt' );
define( '_PAYPLAN_GENERAL_VISIBLE_NAME',				'Sichtbar:' );
define( '_PAYPLAN_GENERAL_VISIBLE_DESC',				'Sichtbare Abos werden im Frontend angezeigt. Unsichtbare werden nicht angezeigt und sind nur verf&uuml;gbar als Ersatz bei Problemen' );

define( '_PAYPLAN_PARAMS_GID_ENABLED_NAME',				'Benutzergruppe' );
define( '_PAYPLAN_PARAMS_GID_ENABLED_DESC',				'Auf JA setzen wenn der Benutzer zu dieser Benutzergruppe geh&ouml;ren soll' );
define( '_PAYPLAN_PARAMS_GID_NAME',						'Zur Gruppe dazu:' );
define( '_PAYPLAN_PARAMS_GID_DESC',						'Benutzer werden dieser Gruppe hinzugef&uuml;gt wenn das Abo gew&auml;hlt wird' );
define( '_PAYPLAN_PARAMS_MAKE_ACTIVE_NAME', 			'Benutzer Aktivieren:');
define( '_PAYPLAN_PARAMS_MAKE_ACTIVE_DESC',				'Auf >Nein< setzen, falls der Benutzer von Hand in die aktive Gruppe verschoben werden soll.');

define( '_PAYPLAN_TEXT_TITLE',							'Abotext' );
define( '_PAYPLAN_GENERAL_EMAIL_DESC_NAME',				'Emailtext:' );
define( '_PAYPLAN_GENERAL_EMAIL_DESC_DESC',				'Text welcher im Email an den Benutzer angezeigt wird wenn das Abo best&auml;tigt wurde' );
define( '_PAYPLAN_GENERAL_FALLBACK_NAME',				'Abo Ersatz:' );
define( '_PAYPLAN_GENERAL_FALLBACK_DESC',				'Wenn ein Abo endet, aktiviere dieses Abo f&uuml;r diesen Benutzer' );

define( '_PAYPLAN_GENERAL_PROCESSORS_NAME',				'Gateways:' );
define( '_PAYPLAN_NOPLAN',								'Kein Abo' );
define( '_PAYPLAN_NOGW',								'Kein Gateway' );
define( '_PAYPLAN_GENERAL_PROCESSORS_DESC',				'Diejenigen Zahlungsgateways ausw&auml;hlen welche f&uuml;r dieses Abonnement g&uuml;ltig sein sollen (STRG oder HOCHSTELLTASTE um mehrere auszuw&auml;hlen.<hr />Wird ' . _PAYPLAN_NOPLAN . ' gew&auml;hlt, werden alle anderen Optionen ignoriert.<br />Ist hier nur ' . _PAYPLAN_NOPLAN . ' sichtbar, heisst das, dass noch keine Gateways ausgew&auml;hlt/konfiguriert wurden' );
define( '_PAYPLAN_PARAMS_LIFETIME_NAME',				'Immerw&auml;hrend:' );
define( '_PAYPLAN_PARAMS_LIFETIME_DESC',				'Bedeuted ein Abo OHNE Ablaufzeit' );

define( '_PAYPLAN_AMOUNT_NOTICE',						'Anmerkung' );
define( '_PAYPLAN_AMOUNT_NOTICE_TEXT',					'F&uuml;r PayPal gilt, dass f&uuml;r jede Periode ein maximales Limit existiert! Werden also Abos mit PayPal m&ouml;glich, <strong>m&uuml;ssen Tage mit 90, Wochen mit 52, Monate mit 24 und Jahre mit 5 limitiert werden</strong>' );
define( '_PAYPLAN_AMOUNT_EDITABLE_NOTICE',				'Momentan ist 1 oder mehrere Benutzer f&uuml;r dieses Abo eingeschrieben, es ist daher nicht vern&uuml;nftig die Konditionen daf&uuml;r zu &auml;ndern!' );

define( '_PAYPLAN_REGULAR_TITLE',						'Normales Abo' );
define( '_PAYPLAN_PARAMS_FULL_FREE_NAME',				'Frei:' );
define( '_PAYPLAN_PARAMS_FULL_FREE_DESC',				'Ja, wenn das ein Gratisabo sein soll' );
define( '_PAYPLAN_PARAMS_FULL_AMOUNT_NAME',				'Normalpreis:' );
define( '_PAYPLAN_PARAMS_FULL_AMOUNT_DESC',				'Der Betrag f&uuml;r dieses Abo. Sind daf&uuml;r bereits Abonnenten eingetragen, kann dieses Feld nicht ge&auml;ndert werden. Soll dennoch eine &Auml;nderung durchgef&uuml;hrt werden, dann Ver&ouml;ffentlichung zur&uuml;ckziehen und ein neues Abo erstellen' );
define( '_PAYPLAN_PARAMS_FULL_PERIOD_NAME',				'Periode:' );
define( '_PAYPLAN_PARAMS_FULL_PERIOD_DESC',				'Die L&auml;nge der Rechnungsperiode (siehe unten). Die Anzahl wird mit dem Zyklus (siehe unten) modifiziert. Sind daf&uuml;r bereits Abonnenten eingetragen, kann dieses Feld nicht ge&auml;ndert werden. Soll dennoch eine &Auml;nderung durchgef&uuml;hrt werden, dann Ver&ouml;ffentlichung zur&uuml;ckziehen und ein neues Abo erstellen' );
define( '_PAYPLAN_PARAMS_FULL_PERIODUNIT_NAME',			'Zyklus:' );
define( '_PAYPLAN_PARAMS_FULL_PERIODUNIT_DESC',			'Anzahl der Zykluseinheiten. Sind daf&uuml;r bereits Abonnenten eingetragen, kann dieses Feld nicht ge&auml;ndert werden. Soll dennoch eine &Auml;nderung durchgef&uuml;hrt werden, dann Ver&ouml;ffentlichung zur&uuml;ckziehen und ein neues Abo erstellen' );

define( '_PAYPLAN_TRIAL_TITLE',							'Testperiode' );
define( '_PAYPLAN_TRIAL',								'(Optional)' );
define( '_PAYPLAN_TRIAL_DESC',							'Dieser Bereich kann &uuml;bergangen werden wenn es keine Testperiode geben soll.<hr /><strong>Testperioden sind nur mit dem automatischen PayPal System m&ouml;glich!</strong>' );
define( '_PAYPLAN_PARAMS_TRIAL_FREE_NAME',				'Gratis:' );
define( '_PAYPLAN_PARAMS_TRIAL_FREE_DESC',				'Ja, wenn dieses Abo Gratis sein soll' );
define( '_PAYPLAN_PARAMS_TRIAL_AMOUNT_NAME',			'Testpreis:' );
define( '_PAYPLAN_PARAMS_TRIAL_AMOUNT_DESC',			'Preis f&uuml;r die Testperiode' );
define( '_PAYPLAN_PARAMS_TRIAL_PERIOD_NAME',			'Testperiode:' );
define( '_PAYPLAN_PARAMS_TRIAL_PERIOD_DESC',			'L&auml;nge der Testperiode.  Die Anzahl wird mit dem Zyklus (siehe unten) modifiziert. Sind daf&uuml;r bereits Abonnenten eingetragen, kann dieses Feld nicht ge&auml;ndert werden. Soll dennoch eine &Auml;nderung durchgef&uuml;hrt werden, dann Ver&ouml;ffentlichung zur&uuml;ckziehen und ein neues Abo erstellen' );
define( '_PAYPLAN_PARAMS_TRIAL_PERIODUNIT_NAME',		'Testperiodenzyklus:' );
define( '_PAYPLAN_PARAMS_TRIAL_PERIODUNIT_DESC',		'Anzahl der Zykluseinheiten. Sind daf&uuml;r bereits Abonnenten eingetragen, kann dieses Feld nicht ge&auml;ndert werden. Soll dennoch eine &Auml;nderung durchgef&uuml;hrt werden, dann Ver&ouml;ffentlichung zur&uuml;ckziehen und ein neues Abo erstellen' );

// Payplan Relations
define( '_PAYPLAN_RELATIONS_TITLE',						'Beziehungen' );
define( '_PAYPLAN_PARAMS_SIMILARPLANS_NAME',			'&Auml;hnliche Abos:' );
define( '_PAYPLAN_PARAMS_SIMILARPLANS_DESC',			'Abos welche dem hier &Auml;hnlich sind ausw&auml;hlen. Einem Benutzer ist es nicht erlaubt ein Testabo auszuw&auml;hlen, wenn ein &auml;hnliches Abos schon vorher bezogen wurde' );
define( '_PAYPLAN_PARAMS_EQUALPLANS_NAME',				'Gleiche Abos:' );
define( '_PAYPLAN_PARAMS_EQUALPLANS_DESC',				'Abos welche dem hier Gleich sind ausw&auml;hlen. Ein Benutzer welcher zwischen solchen Abos wechselt, verl&auml;ngert damit sein Abo anstatt es zu erneuern.<hr />Test-/Gratisabos sind dann nicht erlaubt' );

// Payplan Restrictions
define( '_PAYPLAN_RESTRICTIONS_TITLE',					'Einschr&auml;nkungen' );
define( '_PAYPLAN_RESTRICTIONS_MINGID_ENABLED_NAME',	'Mindest Gruppen ID:' );
define( '_PAYPLAN_RESTRICTIONS_MINGID_ENABLED_DESC',	'Aktivieren wenn dieses Abo nur <strong>AB</strong> einer bestimmten Benutzergruppe angezeigt werden soll' );
define( '_PAYPLAN_RESTRICTIONS_MINGID_NAME',			'Sichtbare Gruppe:' );
define( '_PAYPLAN_RESTRICTIONS_MINGID_DESC',			'Die Benutzerebenen ID <strong>AB</strong> welcher dieses Abo gesehen werden kann. Neue Benutzer werden nur die Abos mit der geringsten Gruppen ID sehen' );
define( '_PAYPLAN_RESTRICTIONS_FIXGID_ENABLED_NAME',	'Fixe Gruppen ID:' );
define( '_PAYPLAN_RESTRICTIONS_FIXGID_ENABLED_DESC',	'Aktivieren wenn <strong>NUR</strong> eine bestimmte Benutzergruppe dieses Abo ssehen soll' );
define( '_PAYPLAN_RESTRICTIONS_FIXGID_NAME',			'Gruppe:' );
define( '_PAYPLAN_RESTRICTIONS_FIXGID_DESC',			'Nur Benutzer aus dieser Gruppe sehen dieses Abo' );
define( '_PAYPLAN_RESTRICTIONS_MAXGID_ENABLED_NAME',	'Maximum Gruppen ID:' );
define( '_PAYPLAN_RESTRICTIONS_MAXGID_ENABLED_DESC',	'Aktivieren wenn dieses Abo <strong>BIS</strong> zu einer maximalen Gruppen ID sichtbar sein soll' );
define( '_PAYPLAN_RESTRICTIONS_MAXGID_NAME',			'Maximum Gruppe:' );
define( '_PAYPLAN_RESTRICTIONS_MAXGID_DESC',			'Die Benutzerebenen ID <strong>BIS</strong> zu welcher dieses Abo sichtbar ist' );

define( '_PAYPLAN_RESTRICTIONS_PREVIOUSPLAN_REQ_ENABLED_NAME',	'Erfordert Abo davor:' );
define( '_PAYPLAN_RESTRICTIONS_PREVIOUSPLAN_REQ_ENABLED_DESC',	'Aktivieren wenn ein Abo vorher erforderlich ist' );
define( '_PAYPLAN_RESTRICTIONS_PREVIOUSPLAN_REQ_NAME',			'Abo:' );
define( '_PAYPLAN_RESTRICTIONS_PREVIOUSPLAN_REQ_DESC',			'Die Benutzer werden dieses Abo nur dann sehen, wenn <strong>vorher</strong> das gew&auml;hlte Abo verwendet wurde/wird' );
define( '_PAYPLAN_RESTRICTIONS_CURRENTPLAN_REQ_ENABLED_NAME',	'Erforderliches Abo:' );
define( '_PAYPLAN_RESTRICTIONS_CURRENTPLAN_REQ_ENABLED_DESC',	'Aktivieren f&uuml;r momentan aktuelles Abo' );
define( '_PAYPLAN_RESTRICTIONS_CURRENTPLAN_REQ_NAME',			'Abo:' );
define( '_PAYPLAN_RESTRICTIONS_CURRENTPLAN_REQ_DESC',			'Nur sichtbar wenn der Benutzer momentan dieses Abo aktiv innehat oder vorher es abonniert hatte' );
define( '_PAYPLAN_RESTRICTIONS_OVERALLPLAN_REQ_ENABLED_NAME',	'Erforderliches Abo:' );
define( '_PAYPLAN_RESTRICTIONS_OVERALLPLAN_REQ_ENABLED_DESC',	'Aktivieren f&uuml;r generelles Abo' );
define( '_PAYPLAN_RESTRICTIONS_OVERALLPLAN_REQ_NAME',			'Abo:' );
define( '_PAYPLAN_RESTRICTIONS_OVERALLPLAN_REQ_DESC',			'Nur sichtbar wenn der Benutzer dieses Abo bereits gew&auml;hlt hatte' );

define( '_PAYPLAN_RESTRICTIONS_USED_PLAN_MIN_ENABLED_NAME',		'Mindest Abo:' );
define( '_PAYPLAN_RESTRICTIONS_USED_PLAN_MIN_ENABLED_DESC',		'Aktivieren wenn <strong>mindestens x Mal</strong> ein spezielles Abo <strong>vorher</strong> abonniert war' );
define( '_PAYPLAN_RESTRICTIONS_USED_PLAN_MIN_AMOUNT_NAME',		'Anzahl:' );
define( '_PAYPLAN_RESTRICTIONS_USED_PLAN_MIN_AMOUNT_DESC',		'Die Mindestanzahl an Abozeitr&auml;men um dieses Abo abonnieren zu k&ouml;nnen' );
define( '_PAYPLAN_RESTRICTIONS_USED_PLAN_MIN_NAME',				'Mindest Abo:' );
define( '_PAYPLAN_RESTRICTIONS_USED_PLAN_MIN_DESC',				'Das Abo das der Benutzer <strong>vorher abonniert haben musste</strong> um dieses Abo w&auml;hlen zu k&ouml;nnen' );
define( '_PAYPLAN_RESTRICTIONS_USED_PLAN_MAX_ENABLED_NAME',		'Maximal vewendet:' );
define( '_PAYPLAN_RESTRICTIONS_USED_PLAN_MAX_ENABLED_DESC',		'Aktivieren wenn Benutzer eine maximale Anzahl an einem speziellen Abo vorher hatten mussten um <strong>dieses</strong> Abo zu sehen' );
define( '_PAYPLAN_RESTRICTIONS_USED_PLAN_MAX_AMOUNT_NAME',		'Anzahl:' );
define( '_PAYPLAN_RESTRICTIONS_USED_PLAN_MAX_AMOUNT_DESC',		'Maximale Anzahl an Benutzern die dieses Abo verwendet d&uuml;rfen' );
define( '_PAYPLAN_RESTRICTIONS_USED_PLAN_MAX_NAME',				'Abo:' );
define( '_PAYPLAN_RESTRICTIONS_USED_PLAN_MAX_DESC',				'Das Abo welches maximal verwendet werden darf' );
define( '_PAYPLAN_RESTRICTIONS_CUSTOM_RESTRICTIONS_NAME', 'Custom Restrictions:');
define( '_PAYPLAN_RESTRICTIONS_CUSTOM_RESTRICTIONS_DESC', 'Use RewriteEngine fields to check for specific strings in this form:<br />[[user_id]] >= 1500<br />[[parametername]] = value<br />(Create separate rules by entering a new line).<br />You can use =, <=, >=, <, >, <> as comparing elements. You MUST use spaces between parameters, values and comparators!');

define( '_PAYPLAN_CUSTOMPARAMS_TITLE', 'Proc. Params');

define( '_PAYPLAN_MI',											'Komponenten' );
define( '_PAYPLAN_GENERAL_MICRO_INTEGRATIONS_NAME',				'Komponentenname:' );
define( '_PAYPLAN_GENERAL_MICRO_INTEGRATIONS_DESC',				'Komponente(n) ausw&auml;hlen welche Benutzern mit diesem Abo zugewiesen werden sollen' );

define( '_PAYPLAN_CURRENCY',					'W&auml;hrung' );

define( '_CURRENCY_AFA', 'Afghani' );
define( '_CURRENCY_ALL', 'Albanische Lek' );
define( '_CURRENCY_DZD', 'Algerische Dinar' );
define( '_CURRENCY_AON', 'Angola Kwanza' );
define( '_CURRENCY_ARS', 'Argentinische Peso' );
define( '_CURRENCY_AMD', 'Armenische Dram' );
define( '_CURRENCY_AWG', 'Aruban Guilder' );
define( '_CURRENCY_AUD', 'Australische Dollar' );
define( '_CURRENCY_AZM', 'Azerbaijanian Manat ' );
define( '_CURRENCY_EUR', 'Euro' );
define( '_CURRENCY_BSD', 'Bahamian Dollar' );
define( '_CURRENCY_BHD', 'Bahraini Dinar' );
define( '_CURRENCY_BDT', 'Bangladesh Taka' );
define( '_CURRENCY_BBD', 'Barbados Dollar' );
define( '_CURRENCY_BYB', 'Wei&szlig;russischer Rubel' );
define( '_CURRENCY_BZD', 'Belize Dollar' );
define( '_CURRENCY_BMD', 'Bermudian Dollar' );
define( '_CURRENCY_BOB', 'Bolivianische Boliviano' );
define( '_CURRENCY_BAD', 'Bosnische Dinar' );
define( '_CURRENCY_BWP', 'Botsuana Pula' );
define( '_CURRENCY_BRL', 'Real' );
define( '_CURRENCY_BND', 'Brunei Dollar' );
define( '_CURRENCY_BGN', 'Bulgarische Lev' );
define( '_CURRENCY_XOF', 'CFA Franc BCEAO' );
define( '_CURRENCY_BIF', 'Burundi Franc' );
define( '_CURRENCY_KHR', 'Kambodschanischer Riel' );
define( '_CURRENCY_XAF', 'CFA Franc BEAC' );
define( '_CURRENCY_CAD', 'Kanadische Dollar' );
define( '_CURRENCY_CVE', 'Cape Verde Escudo' );
define( '_CURRENCY_KYD', 'Cayman Islands Dollar' );
define( '_CURRENCY_CLP', 'Chilenische Peso' );
define( '_CURRENCY_CNY', 'Yuan Renminbi' );
define( '_CURRENCY_COP', 'Kolumbianische Peso' );
define( '_CURRENCY_KMF', 'Comoro Franc' );
define( '_CURRENCY_BAM', 'Convertible Marks' );
define( '_CURRENCY_CRC', 'Costa Rican Colon' );
define( '_CURRENCY_HRK', 'Kroatische Kuna' );
define( '_CURRENCY_CUP', 'Kubanische Peso' );
define( '_CURRENCY_CYP', 'Zypriotische Pfund' );
define( '_CURRENCY_CZK', 'Tschechische Krone' );
define( '_CURRENCY_DKK', 'D&auml;nische Krone' );
define( '_CURRENCY_DJF', 'Djibouti Franc' );
define( '_CURRENCY_XCD', 'East Caribbean Dollar' );
define( '_CURRENCY_DOP', 'Dominican Peso' );
define( '_CURRENCY_TPE', 'Timor Escudo' );
define( '_CURRENCY_ECS', 'Ecuador Sucre' );
define( '_CURRENCY_EGP', '&Auml;gyptische Pfund' );
define( '_CURRENCY_SVC', 'El Salvador Colon' );
define( '_CURRENCY_EEK', 'Estnische Krone' );
define( '_CURRENCY_ETB', '&Auml;thiopische Birr' );
define( '_CURRENCY_FKP', 'Falkland Islands Pfund' );
define( '_CURRENCY_FJD', 'Fiji Dollar' );
define( '_CURRENCY_XPF', 'CFP Franc' );
define( '_CURRENCY_FRF', 'Franc' );
define( '_CURRENCY_CDF', 'Kongo Franc' );
define( '_CURRENCY_GMD', 'Dalasi' );
define( '_CURRENCY_GHC', 'Cedi' );
define( '_CURRENCY_GIP', 'Gibraltar Pfund' );
define( '_CURRENCY_GTQ', 'Guatemala Quetzal' );
define( '_CURRENCY_GNF', 'Guinea Franc' );
define( '_CURRENCY_GWP', 'Guinea - Bissau Peso' );
define( '_CURRENCY_GYD', 'Guyana Dollar' );
define( '_CURRENCY_HTG', 'Gourde' );
define( '_CURRENCY_XAU', 'Gold' );
define( '_CURRENCY_HNL', 'Hondurianischer Lempira' );
define( '_CURRENCY_HKD', 'Hong Kong Dollar' );
define( '_CURRENCY_HUF', 'Ungarische Forint' );
define( '_CURRENCY_ISK', 'Isl&auml;ndische Krona' );
define( '_CURRENCY_INR', 'Indische Rupee' );
define( '_CURRENCY_IDR', 'Indonesische Rupiah' );
define( '_CURRENCY_IRR', 'Iranian Rial' );
define( '_CURRENCY_IQD', 'Iraqi Dinar' );
define( '_CURRENCY_ILS', 'Israelischer Shekel' );
define( '_CURRENCY_JMD', 'Jamaikanische Dollar' );
define( '_CURRENCY_JPY', 'Japanische Yen' );
define( '_CURRENCY_JOD', 'Jordanian Dinar' );
define( '_CURRENCY_KZT', 'Kasachstan Tenge' );
define( '_CURRENCY_KES', 'Kenyan Shilling' );
define( '_CURRENCY_KRW', 'S&uuml;dkoreanischer Won' );
define( '_CURRENCY_KPW', 'Nordkoreanischer Won' );
define( '_CURRENCY_KWD', 'Kuwaiti Dinar' );
define( '_CURRENCY_KGS', 'Kirgisischer Som' );
define( '_CURRENCY_LAK', 'Laotische Kip' );
define( '_CURRENCY_GEL', 'Georgischer Lari' );
define( '_CURRENCY_LVL', 'Lettische Lats' );
define( '_CURRENCY_LBP', 'Libanesisches Pfund' );
define( '_CURRENCY_LSL', 'Lesothischer Loti' );
define( '_CURRENCY_LRD', 'Liberian Dollar' );
define( '_CURRENCY_LYD', 'Libyan Dinar' );
define( '_CURRENCY_LTL', 'Litauische Litas' );
define( '_CURRENCY_AOR', 'Kwanza Reajustado' );
define( '_CURRENCY_MOP', 'Macao Pataca' );
define( '_CURRENCY_MKD', 'Denar' );
define( '_CURRENCY_MGF', 'Malagasy Franc' );
define( '_CURRENCY_MWK', 'Malawischer Kwacha' );
define( '_CURRENCY_MYR', 'Malaysian Ringitt' );
define( '_CURRENCY_MVR', 'Malediven Rupie (Rufiyaa)' );
define( '_CURRENCY_MTL', 'Maltesische Lira' );
define( '_CURRENCY_MRO', 'Mauretanischer Ouguiya' );
define( '_CURRENCY_TMM', 'Turkmenischer Manat' );
define( '_CURRENCY_FIM', 'Finnische Mark' );
define( '_CURRENCY_MUR', 'Mauritius Rupee' );
define( '_CURRENCY_MXN', 'Mexico Peso' );
define( '_CURRENCY_MXV', 'Mexican Unidad de Inversion' );
define( '_CURRENCY_MNT', 'Mongolia Tugrik' );
define( '_CURRENCY_MAD', 'Marokanische Dirham' );
define( '_CURRENCY_MDL', 'Moldauischer Leu' );
define( '_CURRENCY_MZM', 'Mosambik Metical' );
define( '_CURRENCY_MMK', 'Myanmar Kyat' );
define( '_CURRENCY_ERN', 'Eretrianischer Nakfa' );
define( '_CURRENCY_NAD', 'Namibischer Dollar' );
define( '_CURRENCY_NPR', 'Nepalesische Rupee' );
define( '_CURRENCY_ANG', 'Niederl&auml;ndische Antillen Gulden' );
define( '_CURRENCY_NZD', 'Neeseeland Dollar' );
define( '_CURRENCY_NIO', 'Cordoba Oro' );
define( '_CURRENCY_NGN', 'Nigerianische Naira' );
define( '_CURRENCY_BTN', 'Bhutan Ngultrum' );
define( '_CURRENCY_NOK', 'Norwegische Kronen' );
define( '_CURRENCY_OMR', 'Rial Omani' );
define( '_CURRENCY_PKR', 'Pakistan Rupee' );
define( '_CURRENCY_PAB', 'Balboa' );
define( '_CURRENCY_PGK', 'Neu Guinea Kina' );
define( '_CURRENCY_PYG', 'Guarani' );
define( '_CURRENCY_PEN', 'Peruanischer Nuevo Sol' );
define( '_CURRENCY_XPD', 'Palladium' );
define( '_CURRENCY_PHP', 'Philippine Peso' );
define( '_CURRENCY_XPT', 'Platin' );
define( '_CURRENCY_PLN', 'Polnische Zloty' );
define( '_CURRENCY_QAR', 'Qatari Rial' );
define( '_CURRENCY_RON', 'Neuer Rum&auml;nischer Leu' );
define( '_CURRENCY_ROL', 'Rum&auml;nischer Leu' );
define( '_CURRENCY_RUB', 'Russische Rubel' );
define( '_CURRENCY_RWF', 'Ruanda Franc' );
define( '_CURRENCY_WST', 'Samoa Tala' );
define( '_CURRENCY_STD', 'Dobra' );
define( '_CURRENCY_SAR', 'Saudi Riyal' );
define( '_CURRENCY_SCR', 'Seychelles Rupee' );
define( '_CURRENCY_SLL', 'Sierra Leone Leone' );
define( '_CURRENCY_SGD', 'Singapore Dollar' );
define( '_CURRENCY_SKK', 'Slovakische Krone' );
define( '_CURRENCY_SBD', 'Solomon Islands Dollar' );
define( '_CURRENCY_SOS', 'Somalia Shilling' );
define( '_CURRENCY_ZAL', 'Rand (Financial)' );
define( '_CURRENCY_ZAR', 'Rand (S&uuml;dafrika)' );
define( '_CURRENCY_XAG', 'Silver' );
define( '_CURRENCY_LKR', 'Sri Lanka Rupee' );
define( '_CURRENCY_SHP', 'St.Helena Pound' );
define( '_CURRENCY_SDP', 'Sudanese Pound' );
define( '_CURRENCY_SDD', 'Sudanese Dinar' );
define( '_CURRENCY_SRG', 'Suriname Guilder' );
define( '_CURRENCY_SZL', 'Swaziland Lilangeni' );
define( '_CURRENCY_SEK', 'Schwedische Kronen' );
define( '_CURRENCY_CHF', 'Schweizer Franken' );
define( '_CURRENCY_SYP', 'Syrian Pound' );
define( '_CURRENCY_TWD', 'Taiwan Dollar' );
define( '_CURRENCY_TJR', 'Tajik Ruble' );
define( '_CURRENCY_TZS', 'Tanzanian Shilling' );
define( '_CURRENCY_THB', 'Thail&auml;ndische Baht' );
define( '_CURRENCY_TOP', 'Tonga Pa\'anga (Dollar)' );
define( '_CURRENCY_TTD', 'Trinidad &amp; Tobago Dollar' );
define( '_CURRENCY_TND', 'Tunisische Dinar' );
define( '_CURRENCY_TRY', 'T&uuml;rkische Lira' );
define( '_CURRENCY_UGX', 'Uganda Shilling' );
define( '_CURRENCY_UAH', 'Ukrainische Hrywnja' );
define( '_CURRENCY_ECV', 'Unidad de Valor Constante' );
define( '_CURRENCY_CLF', 'Chile Unidades de fomento' );
define( '_CURRENCY_AED', 'Vereinigte Arab. Emirate Dirham' );
define( '_CURRENCY_GBP', 'Englische Pfund' );
define( '_CURRENCY_USD', 'US Dollar' );
define( '_CURRENCY_UYU', 'Uruguayan Peso' );
define( '_CURRENCY_UZS', 'Uzbekistan Sum' );
define( '_CURRENCY_VUV', 'Vanuatu Vatu' );
define( '_CURRENCY_VEB', 'Venezuela Bolivar' );
define( '_CURRENCY_VND', 'Viet Nam Dong' );
define( '_CURRENCY_YER', 'Yemeni Rial' );
define( '_CURRENCY_ZRN', 'New Zaire' );
define( '_CURRENCY_ZMK', 'Sambischer Kwacha' );
define( '_CURRENCY_ZWD', 'Zimbabwe Dollar' );
define( '_CURRENCY_USN', 'US Dollar (N&auml;chster Tag)' );
define( '_CURRENCY_USS', 'US Dollar (Gleicher Tag)' );

// --== MICRO INTEGRATION OVERVIEW ==--
define( '_MI_TITLE',						'MicroIntegrationen' );
define( '_MI_NAME',							'Name' );
define( '_MI_DESC',							'Beschreibung' );
define( '_MI_ACTIVE',						'Aktiv' );
define( '_MI_REORDER',						'Reihenfolge' );
define( '_MI_FUNCTION',						'Funktionsname' );

// --== MICRO INTEGRATION EDIT ==--
define( '_MI_E_TITLE',						'MicroIntegration' );
define( '_MI_E_SETTINGS',					'Einstellungen' );
define( '_MI_E_NAME_NAME',					'Name' );
define( '_MI_E_NAME_DESC',					'Name f&uuml;r diese MicroIntegration' );
define( '_MI_E_DESC_NAME',					'Beschreibung' );
define( '_MI_E_DESC_DESC',					'Kurzbeschreibung' );
define( '_MI_E_ACTIVE_NAME',				'Aktiv' );
define( '_MI_E_ACTIVE_DESC',				'Als Aktiv markieren' );
define( '_MI_E_ACTIVE_AUTO_NAME',			'Aktion bei Ablauf' );
define( '_MI_E_ACTIVE_AUTO_DESC',			'Falls diese Komponente es erlaubt k&ouml;nnen Aktionen definiert werden wenn ein Abo ausl&auml;ft' );
define( '_MI_E_ACTIVE_USERUPDATE_NAME',		'Benutzeraktion' );
define( '_MI_E_ACTIVE_USERUPDATE_DESC',		'Falls von der Komponente unterst&uuml;tzt k&ouml;nnen Aktionen definiert werden wenn ein Benutzerabo ausl&auml;ft' );
define( '_MI_E_PRE_EXP_NAME',				'Tage vor Ablauf' );
define( '_MI_E_PRE_EXP_DESC',				'Anzahl der Tage vor dem Ablauf ab wann die Aktionen gelten sollen' );
define( '_MI_E_FUNCTION_NAME',				'Funktionsname' );
define( '_MI_E_FUNCTION_DESC',				'Welche der Systeme sollen verwendet weren' );
define( '_MI_E_FUNCTION_EXPLANATION',		'Bevor die MicroIntegration definiert wird, muss bestimmt werden welche der MicroIntegrationen g&uuml;ltig/aktiv sind. Wahl treffen und speichern. Dann nochmals bearbeiten, die Einstellungen sind erst dann sichtbar. HINWEIS: einmal definiert lassen sich die Einstellungen nicht r&uuml;ckg&auml;ngig machen' );

// --== REWRITE EXPLANATION ==--
define( '_REWRITE_AREA_USER',				'Benutzeraccount Bezogen' );
define( '_REWRITE_KEY_USER_ID',				'Benutzer ID' );
define( '_REWRITE_KEY_USER_USERNAME',		'Benutzername' );
define( '_REWRITE_KEY_USER_NAME',			'Name' );
define( '_REWRITE_KEY_USER_EMAIL',			'Emailadresse' );
define( '_REWRITE_KEY_USER_ACTIVATIONCODE', 'Aktivierungs Code');
define( '_REWRITE_KEY_USER_ACTIVATIONLINK', 'Aktivierungs Link');

define( '_REWRITE_AREA_EXPIRATION',			'Benutzer Ablaufbezogen' );
define( '_REWRITE_KEY_EXPIRATION_DATE',		'Ablaufdatum' );

define( '_REWRITE_AREA_SUBSCRIPTION',		'Benutzer Abobezogen' );
define( '_REWRITE_KEY_SUBSCRIPTION_TYPE',	'Gateway' );
define( '_REWRITE_KEY_SUBSCRIPTION_STATUS', 'Abonnentenstatus' );
define( '_REWRITE_KEY_SUBSCRIPTION_SIGNUP_DATE',	'Datum Aboabschluss' );
define( '_REWRITE_KEY_SUBSCRIPTION_LASTPAY_DATE',	'Letztes Zahlungsdatum' );
define( '_REWRITE_KEY_SUBSCRIPTION_PLAN',			'Aktuelle Abo ID' );
define( '_REWRITE_KEY_SUBSCRIPTION_PREVIOUS_PLAN',	'Vorige Abo ID' );
define( '_REWRITE_KEY_SUBSCRIPTION_RECURRING',		'Wiederkehrende Zahlung' );
define( '_REWRITE_KEY_SUBSCRIPTION_LIFETIME',		'Immerw&auml;hrendes Abo' );

define( '_REWRITE_AREA_PLAN', 				'Abo Bezogen' );
define( '_REWRITE_KEY_PLAN_NAME',			'Name' );
define( '_REWRITE_KEY_PLAN_DESC',			'Beschreibung' );

define( '_REWRITE_AREA_CMS',				'CMS Bezogen' );
define( '_REWRITE_KEY_CMS_ABSOLUTE_PATH',	'Absoluter Pfad zum CMS-Hauptverzeichnis (z.B. ../www/html/...' );
define( '_REWRITE_KEY_CMS_LIVE_SITE',		'Relativer Pfad zur Webseite (z.B. http://www.meineseite.com)' );

define( '_REWRITE_AREA_INVOICE', 'Rechnungs Bezogen');
define( '_REWRITE_KEY_INVOICE_ID', 'Rechnungs ID');
define( '_REWRITE_KEY_INVOICE_NUMBER', 'Rechnungs Nummer');
define( '_REWRITE_KEY_INVOICE_CREATED_DATE', 'Datum der Erstellung');
define( '_REWRITE_KEY_INVOICE_TRANSACTION_DATE', 'Datum der Bezahlung');
define( '_REWRITE_KEY_INVOICE_METHOD', 'Bezahlungsmethode');
define( '_REWRITE_KEY_INVOICE_AMOUNT', 'Betrag');
define( '_REWRITE_KEY_INVOICE_CURRENCY', 'W&auml;hrung');
define( '_REWRITE_KEY_INVOICE_COUPONS', 'Coupon Liste');

// --== COUPONS OVERVIEW ==--
define( '_COUPON_TITLE',					'Gutscheine' );
define( '_COUPON_TITLE_STATIC',				'Gruppengutscheine' );
define( '_COUPON_NAME',						'Name' );
define( '_COUPON_DESC',						'Beschreibung (erste 50 Zeichen)' );
define( '_COUPON_ACTIVE',					'Ver&ouml;ffentlicht' );
define( '_COUPON_REORDER',					'Ordnen' );
define( '_COUPON_USECOUNT',					'Angewendet' );

// --== INVOICE OVERVIEW ==--
define( '_INVOICE_TITLE',					'Rechnungen' );
define( '_INVOICE_SEARCH',					'Suche' );
define( '_INVOICE_USERID',					'Benutzername' );
define( '_INVOICE_INVOICE_NUMBER',			'Rechnungsnummer' );
define( '_INVOICE_TRANSACTION_DATE',		'Durchf&uuml;hrungsdatum' );
define( '_INVOICE_METHOD',					'Rechnungsart' );
define( '_INVOICE_AMOUNT',					'Rechnungsbetrag' );
define( '_INVOICE_CURRENCY',				'W&auml;hrung' );

// --== PAYMENT HISTORY OVERVIEW ==--
define( '_HISTORY_TITLE2',					'Aktuelle Transaktionsgeschichte' );
define( '_HISTORY_SEARCH',					'Suche' );
define( '_HISTORY_USERID',					'Benutzername' );
define( '_HISTORY_INVOICE_NUMBER',			'Rechnungsnummer' );
define( '_HISTORY_PLAN_NAME',				'Abonnement' );
define( '_HISTORY_TRANSACTION_DATE',		'Durchf&uuml;hrungsdatum' );
define( '_INVOICE_CREATED_DATE',			'Erstellt' );
define( '_HISTORY_METHOD',					'Rechnungsart' );
define( '_HISTORY_AMOUNT',					'Rechnungsbetrag' );
define( '_HISTORY_RESPONSE',				'Serverantwort' );

// --== ALL USER RELATED PAGES ==--
define( '_METHOD',							'Methode' );

// --== PENDING PAGE ==--
define( '_PEND_DATE',						'schwebend seit' );
define( '_PEND_TITLE',						'Schwebende Abonnements' );
define( '_PEND_DESC',						'Abonnements, die nicht abgeschlossen wurden. Dies ist normal, falls das System auf die Zahlungbenachrichtigung des Gateways wartet.' );
define( '_ACTIVATED',						'Benutzer aktiviert.' );
define( '_ACTIVATE',						'Aktivieren' );
?>