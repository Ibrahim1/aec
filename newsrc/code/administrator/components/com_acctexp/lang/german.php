<?php
/**
 * @version $Id: german.php
 * @package AEC - Account Control Expiration - Membership Manager
 * @subpackage Language - Backend - German Formal
 * @copyright 2006-2008 Copyright (C) David Deutsch
 * @author David Deutsch <skore@skore.de> & Team AEC - http://www.valanx.org
 * @license GNU/GPL v.2 http://www.gnu.org/licenses/old-licenses/gpl-2.0.html or, at your option, any later version
 */

// Dont allow direct linking
( defined('_JEXEC') || defined( '_VALID_MOS' ) ) or die( 'Direct Access to this location is not allowed.' );

define( '_AEC_LANGUAGE',						'de' );
define( '_COUPON_CODE',							'Gutscheincode' );
define( '_CFG_GENERAL_CUSTOMNOTALLOWED_NAME',	'Link zur Nichterlaubtseite:' );

// hacks/install >> ATTENTION: MUST BE HERE AT THIS POSITION, needed later!
define( '_AEC_SPEC_MENU_ENTRY',					'Mein Abonnement' );

// common
define( '_DESCRIPTION_PAYSIGNET',				'Zahlungen mit allen g&auml;ngigen Kreditkarten und Bank&uuml;berweisung' );
define( '_AEC_CONFIG_SAVED',					'Konfiguration erfolgreich gesichert' );
define( '_AEC_CONFIG_CANCELLED',				'Konfiguration abgebrochen' );
define( '_AEC_TIP_NO_GROUP_PF_PB',				'Weder "Public Frontend" noch "Public Backend" sind w&auml;hlbare Benutzergruppen!' );
define( '_AEC_CGF_LINK_ABO_FRONTEND',			'Direkter Link zum Abo' );
define( '_AEC_CGF_LINK_CART_FRONTEND',			'Direct Add To Cart link' );
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
define( '_AEC_CMN_INHERIT',						'Inherit' );
define( '_AEC_CMN_LANG_CONSTANT_IS_MISSING',	'Sprachenkonstante <strong>%s</strong> fehlt' );
define( '_AEC_CMN_VISIBLE',						'Sichtbar' );
define( '_AEC_CMN_INVISIBLE',					'Unsichtbar' );
define( '_AEC_CMN_EXCLUDED',					'Ausgenommen' );
define( '_AEC_CMN_PENDING',						'Schwebend' );
define( '_AEC_CMN_ACTIVE',						'Aktiv' );
define( '_AEC_CMN_TRIAL',						'Testzeit' );
define( '_AEC_CMN_CANCEL',						'Abgebrochen/Storniert' );
define( '_AEC_CMN_HOLD',						'Halt' );
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
define( '_AEC_USER_ALL_SUBSCRIPTIONS',			'All Subscriptions by this User' );
define( '_AEC_USER_ALL_SUBSCRIPTIONS_NOPE',	'This is the only subscription this user currently holds.' );
define( '_AEC_USER_SUBSCRIPTIONS_ID',			'ID' );
define( '_AEC_USER_SUBSCRIPTIONS_STATUS',		'Status' );
define( '_AEC_USER_SUBSCRIPTIONS_PROCESSOR',	'Processor' );
define( '_AEC_USER_SUBSCRIPTIONS_SINGUP',		'Signup' );
define( '_AEC_USER_SUBSCRIPTIONS_EXPIRATION',	'Expiration' );
define( '_AEC_USER_SUBSCRIPTIONS_PRIMARY',		'primary' );
define( '_AEC_USER_CURR_SUBSCR_PLAN_PRIMARY',	'Primary' );
define( '_AEC_USER_COUPONS',					'Coupons' );
define( '_HISTORY_COL_COUPON_CODE',				'Coupon Code' );
define( '_AEC_USER_NO_COUPONS',					'No coupon use recorded' );
define( '_AEC_USER_MICRO_INTEGRATION',			'Micro Integration Info' );
define( '_AEC_USER_MICRO_INTEGRATION_USER',		'Frontend' );
define( '_AEC_USER_MICRO_INTEGRATION_ADMIN',	'Backend' );
define( '_AEC_USER_MICRO_INTEGRATION_DB',		'Database Readout' );

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
define( '_AEC_HEAD_HOLD_SUBS',					'Angehaltene Abonnenten' );
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
define( '_AEC_HACKS_LEGACY_MAMBOT',				'<strong>This is a Legacy Hack which is superceded by the Joomla 1.0 Mambot, please undo and use the "Hacks Mambot" instead!</strong>' );
define( '_AEC_HACKS_LEGACY_PLUGIN',				'<strong>This is a Legacy Hack which is superceded by the Joomla 1.5 Plugin, please undo and use the Plugin instead!</strong>' );
define( '_AEC_HACKS_LEGACY_PLUGIN_ERROR',		'<strong>This is a Legacy Hack which is superceded by the Joomla 1.5 Error Plugin, please undo and use the AEC Error Plugin instead!</strong>' );
define( '_AEC_HACKS_LEGACY_PLUGIN_USER',		'<strong>This is a Legacy Hack which is superceded by the Joomla 1.5 User Plugin, please undo and use the AEC User Plugin instead!</strong>' );
define( '_AEC_HACKS_LEGACY_PLUGIN_ACCESS',		'<strong>This is a Legacy Hack which is superceded by the Joomla 1.5 Access Plugin, please undo and use the AEC Access Plugin instead!</strong>' );
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

define( '_AEC_HACKS_JUSER_HTML1',				'This will redirect a registering user to the payment plans after filling out the registration form in JUser. Leave this alone to have plan selection only on login (if \'Require Subscription\' is active), or completely voluntary (without requiring a subscription). <strong>Please note that there are two hacks following this, once you have committed it! If you want to have the plans before the user details, these are required as well.</strong>' );
define( '_AEC_HACKS_JUSER_PHP1',				'This will redirect the user to the payment plans page when he or she has not made that selection yet.' );
define( '_AEC_HACKS_JUSER_PHP2',				'This is a bugfix which allows the AEC to load the JUser functions without forcing it to react to the POST data.' );

// log
	// settings
define( '_AEC_LOG_SH_SETT_SAVED',				'&Auml;nderung Einstellungen' );
define( '_AEC_LOG_LO_SETT_SAVED',				'AEC Einstellungen wurden gesichert, &Auml;nderungen:' );
	// heartbeat
define( '_AEC_LOG_SH_HEARTBEAT',				'Heartbeat' );
define( '_AEC_LOG_LO_HEARTBEAT',				'Heartbeataktion:' );
define( '_AEC_LOG_AD_HEARTBEAT_DO_NOTHING',		'keine' );
	// install
define( '_AEC_LOG_SH_INST',						'AEC Installation' );
define( '_AEC_LOG_LO_INST',						'AEC Version %s wurde installiert' );

// install texts
define( '_AEC_INST_NOTE_IMPORTANT',				'Wichtiger Hinweis' );
define( '_AEC_INST_NOTE_SECURITY',				'Um die Integration mit manchen anderen Komponenten es notwendig einige &Auml;nderungen an deren Dateien zu machen.<br />Mit dieser Version von AEC wird eine Funktion mitgeliefert die exakt diese Aufgabe &uuml;bernimmt, daf&uuml;r bitte auf den nachfolgenden Link klicken' );
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

define( '_AEC_INST_ROOT_GROUP_NAME',			'Root' );
define( '_AEC_INST_ROOT_GROUP_DESC',			'Root Group. This entry cannot be deleted, modification is limited.' );

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
define( '_AEC_CENTR_HOLD',						'Gehalten' );
define( '_AEC_CENTR_CLOSED',					'Beendet' );
define( '_AEC_CENTR_PROCESSORS',				'Processors' );
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
define( '_AEC_CENTR_EXPORT',					'Export' );
define( '_AEC_CENTR_IMPORT',					'Import' );
define( '_AEC_CENTR_GROUPS',					'Gruppen' );
define( '_AEC_CENTR_AREA_MEMBERSHIPS',			'Mitgliedschaften' );
define( '_AEC_CENTR_AREA_PAYMENT',				'Bezahlung &amp; zugeh&ouml;rige Funktionen' );
define( '_AEC_CENTR_AREA_SETTINGS',				'Einstellungen, Logbuch &amp; weitere Funktionen' );
define( '_AEC_QUICKSEARCH',						'Schnellsuche' );
define( '_AEC_QUICKSEARCH_DESC',				'Geben sie den Namen, Benutzernamen, Email Addresse, die Benutzer ID oder eine Rechnungsnummer ein um direkt zum Konto eines Benutzers weitergeleitet zu werden. Sollten mehrere Benutzer gefunden werden, so wird unten eine Auswahl angezeigt.' );
define( '_AEC_QUICKSEARCH_MULTIRES',			'Mehrere Benutzer gefunden!' );
define( '_AEC_QUICKSEARCH_MULTIRES_DESC',		'Bitte w&auml;hlen sie einen Benutzer aus:' );
define( '_AEC_QUICKSEARCH_THANKS',				'Danke - Sie haben eine einfache Funktion sehr gl&uuml;cklick gemacht.' );
define( '_AEC_QUICKSEARCH_NOTFOUND',			'Benutzer nicht gefunden' );

define( '_AEC_NOTICES_FOUND',					'Eventlog Notices' );
define( '_AEC_NOTICES_FOUND_DESC',				'The following entries in the Eventlog deserve your attention. You can mark them read if you want them to disappear. You can also change the types of notices that show up here in the Settings.' );
define( '_AEC_NOTICE_MARK_READ',				'mark read' );
define( '_AEC_NOTICE_MARK_ALL_READ',			'Mark all Notices read' );
define( '_AEC_NOTICE_NUMBER_1',					'Event' );
define( '_AEC_NOTICE_NUMBER_2',					'Event' );
define( '_AEC_NOTICE_NUMBER_8',					'Notice' );
define( '_AEC_NOTICE_NUMBER_32',				'Warning' );
define( '_AEC_NOTICE_NUMBER_128',				'Error' );
define( '_AEC_NOTICE_NUMBER_512',				'None' );

// select lists
define( '_AEC_SEL_EXCLUDED',					'Ausgeschlossen' );
define( '_AEC_SEL_PENDING',						'Wartend' );
define( '_AEC_SEL_TRIAL',						'Trial' );
define( '_AEC_SEL_ACTIVE',						'Aktiv' );
define( '_AEC_SEL_EXPIRED',						'Abgelaufen' );
define( '_AEC_SEL_CLOSED',						'Geschlossen' );
define( '_AEC_SEL_CANCELLED',					'Storniert' );
define( '_AEC_SEL_NOT_CONFIGURED',				'Ni. Konfiguriert / Neu' );

// footer
define( '_AEC_FOOT_TX_CHOOSING',				'Danke dass Sie sich f&uuml;r AEC - Account Expiration Control entschieden haben!' );
define( '_AEC_FOOT_TX_GPL',						'Diese Komponente wurde entwickelt und ver&ouml;ffentlicht unter der <a href="http://www.gnu.org/copyleft/gpl.html" target="_blank" title="GNU/GPL">GNU/GPL</a> von David Deutsch und dem Team von <a href="https://valanx.org" target="_blank" title="valanx.org">valanx.org</a>' );
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

// ISO 3166 Two-Character Country Codes
define( '_AEC_LANG_AD', 'Andorra' );
define( '_AEC_LANG_AE', 'United Arab Emirates' );
define( '_AEC_LANG_AF', 'Afghanistan' );
define( '_AEC_LANG_AG', 'Antigua and Barbuda' );
define( '_AEC_LANG_AI', 'Anguilla' );
define( '_AEC_LANG_AL', 'Albania' );
define( '_AEC_LANG_AM', 'Armenia' );
define( '_AEC_LANG_AN', 'Netherlands Antilles' );
define( '_AEC_LANG_AO', 'Angola' );
define( '_AEC_LANG_AQ', 'Antarctica' );
define( '_AEC_LANG_AR', 'Argentina' );
define( '_AEC_LANG_AS', 'American Samoa' );
define( '_AEC_LANG_AT', 'Austria' );
define( '_AEC_LANG_AU', 'Australia' );
define( '_AEC_LANG_AW', 'Aruba' );
define( '_AEC_LANG_AX', 'Aland Islands &#65279;land Island\'s' );
define( '_AEC_LANG_AZ', 'Azerbaijan' );
define( '_AEC_LANG_BA', 'Bosnia and Herzegovina' );
define( '_AEC_LANG_BB', 'Barbados' );
define( '_AEC_LANG_BD', 'Bangladesh' );
define( '_AEC_LANG_BE', 'Belgium' );
define( '_AEC_LANG_BF', 'Burkina Faso' );
define( '_AEC_LANG_BG', 'Bulgaria' );
define( '_AEC_LANG_BH', 'Bahrain' );
define( '_AEC_LANG_BI', 'Burundi' );
define( '_AEC_LANG_BJ', 'Benin' );
define( '_AEC_LANG_BL', 'Saint Barth&eacute;lemy' );
define( '_AEC_LANG_BM', 'Bermuda' );
define( '_AEC_LANG_BN', 'Brunei Darussalam' );
define( '_AEC_LANG_BO', 'Bolivia, Plurinational State of' );
define( '_AEC_LANG_BR', 'Brazil' );
define( '_AEC_LANG_BS', 'Bahamas' );
define( '_AEC_LANG_BT', 'Bhutan' );
define( '_AEC_LANG_BV', 'Bouvet Island' );
define( '_AEC_LANG_BW', 'Botswana' );
define( '_AEC_LANG_BY', 'Belarus' );
define( '_AEC_LANG_BZ', 'Belize' );
define( '_AEC_LANG_CA', 'Canada' );
define( '_AEC_LANG_CC', 'Cocos (Keeling) Islands' );
define( '_AEC_LANG_CD', 'Congo, the Democratic Republic of the' );
define( '_AEC_LANG_CF', 'Central African Republic' );
define( '_AEC_LANG_CG', 'Congo' );
define( '_AEC_LANG_CH', 'Switzerland' );
define( '_AEC_LANG_CI', 'Cote d\'Ivoire' );
define( '_AEC_LANG_CK', 'Cook Islands' );
define( '_AEC_LANG_CL', 'Chile' );
define( '_AEC_LANG_CM', 'Cameroon' );
define( '_AEC_LANG_CN', 'China' );
define( '_AEC_LANG_CO', 'Colombia' );
define( '_AEC_LANG_CR', 'Costa Rica' );
define( '_AEC_LANG_CU', 'Cuba' );
define( '_AEC_LANG_CV', 'Cape Verde' );
define( '_AEC_LANG_CX', 'Christmas Island' );
define( '_AEC_LANG_CY', 'Cyprus' );
define( '_AEC_LANG_CZ', 'Czech Republic' );
define( '_AEC_LANG_DE', 'Germany' );
define( '_AEC_LANG_DJ', 'Djibouti' );
define( '_AEC_LANG_DK', 'Denmark' );
define( '_AEC_LANG_DM', 'Dominica' );
define( '_AEC_LANG_DO', 'Dominican Republic' );
define( '_AEC_LANG_DZ', 'Algeria' );
define( '_AEC_LANG_EC', 'Ecuador' );
define( '_AEC_LANG_EE', 'Estonia' );
define( '_AEC_LANG_EG', 'Egypt' );
define( '_AEC_LANG_EH', 'Western Sahara' );
define( '_AEC_LANG_ER', 'Eritrea' );
define( '_AEC_LANG_ES', 'Spain' );
define( '_AEC_LANG_ET', 'Ethiopia' );
define( '_AEC_LANG_FI', 'Finland' );
define( '_AEC_LANG_FJ', 'Fiji' );
define( '_AEC_LANG_FK', 'Falkland Islands (Malvinas)' );
define( '_AEC_LANG_FM', 'Micronesia, Federated States of' );
define( '_AEC_LANG_FO', 'Faroe Islands' );
define( '_AEC_LANG_FR', 'France' );
define( '_AEC_LANG_GA', 'Gabon' );
define( '_AEC_LANG_GB', 'United Kingdom' );
define( '_AEC_LANG_GD', 'Grenada' );
define( '_AEC_LANG_GE', 'Georgia' );
define( '_AEC_LANG_GF', 'French Guiana' );
define( '_AEC_LANG_GG', 'Guernsey' );
define( '_AEC_LANG_GH', 'Ghana' );
define( '_AEC_LANG_GI', 'Gibraltar' );
define( '_AEC_LANG_GL', 'Greenland' );
define( '_AEC_LANG_GM', 'Gambia' );
define( '_AEC_LANG_GN', 'Guinea' );
define( '_AEC_LANG_GP', 'Guadeloupe' );
define( '_AEC_LANG_GQ', 'Equatorial Guinea' );
define( '_AEC_LANG_GR', 'Greece' );
define( '_AEC_LANG_GS', 'South Georgia and the South Sandwich Islands' );
define( '_AEC_LANG_GT', 'Guatemala' );
define( '_AEC_LANG_GU', 'Guam' );
define( '_AEC_LANG_GW', 'Guinea-Bissau' );
define( '_AEC_LANG_GY', 'Guyana' );
define( '_AEC_LANG_HK', 'Hong Kong' );
define( '_AEC_LANG_HM', 'Heard Island and McDonald Islands' );
define( '_AEC_LANG_HN', 'Honduras' );
define( '_AEC_LANG_HR', 'Croatia' );
define( '_AEC_LANG_HT', 'Haiti' );
define( '_AEC_LANG_HU', 'Hungary' );
define( '_AEC_LANG_ID', 'Indonesia' );
define( '_AEC_LANG_IE', 'Ireland' );
define( '_AEC_LANG_IL', 'Israel' );
define( '_AEC_LANG_IM', 'Isle of Man' );
define( '_AEC_LANG_IN', 'India' );
define( '_AEC_LANG_IO', 'British Indian Ocean Territory' );
define( '_AEC_LANG_IQ', 'Iraq' );
define( '_AEC_LANG_IR', 'Iran, Islamic Republic of' );
define( '_AEC_LANG_IS', 'Iceland' );
define( '_AEC_LANG_IT', 'Italy' );
define( '_AEC_LANG_JE', 'Jersey' );
define( '_AEC_LANG_JM', 'Jamaica' );
define( '_AEC_LANG_JO', 'Jordan' );
define( '_AEC_LANG_JP', 'Japan' );
define( '_AEC_LANG_KE', 'Kenya' );
define( '_AEC_LANG_KG', 'Kyrgyzstan' );
define( '_AEC_LANG_KH', 'Cambodia' );
define( '_AEC_LANG_KI', 'Kiribati' );
define( '_AEC_LANG_KM', 'Comoros' );
define( '_AEC_LANG_KN', 'Saint Kitts and Nevis' );
define( '_AEC_LANG_KP', 'Korea, Democratic People\'s Republic of' );
define( '_AEC_LANG_KR', 'Korea, Republic of' );
define( '_AEC_LANG_KW', 'Kuwait' );
define( '_AEC_LANG_KY', 'Cayman Islands' );
define( '_AEC_LANG_KZ', 'Kazakhstan' );
define( '_AEC_LANG_LA', 'Lao People\'s Democratic Republic' );
define( '_AEC_LANG_LB', 'Lebanon' );
define( '_AEC_LANG_LC', 'Saint Lucia' );
define( '_AEC_LANG_LI', 'Liechtenstein' );
define( '_AEC_LANG_LK', 'Sri Lanka' );
define( '_AEC_LANG_LR', 'Liberia' );
define( '_AEC_LANG_LS', 'Lesotho' );
define( '_AEC_LANG_LT', 'Lithuania' );
define( '_AEC_LANG_LU', 'Luxembourg' );
define( '_AEC_LANG_LV', 'Latvia' );
define( '_AEC_LANG_LY', 'Libyan Arab Jamahiriya' );
define( '_AEC_LANG_MA', 'Morocco' );
define( '_AEC_LANG_MC', 'Monaco' );
define( '_AEC_LANG_MD', 'Moldova, Republic of' );
define( '_AEC_LANG_ME', 'Montenegro' );
define( '_AEC_LANG_MF', 'Saint Martin (French part)' );
define( '_AEC_LANG_MG', 'Madagascar' );
define( '_AEC_LANG_MH', 'Marshall Islands' );
define( '_AEC_LANG_MK', 'Macedonia, the former Yugoslav Republic of' );
define( '_AEC_LANG_ML', 'Mali' );
define( '_AEC_LANG_MM', 'Myanmar' );
define( '_AEC_LANG_MN', 'Mongolia' );
define( '_AEC_LANG_MO', 'Macao' );
define( '_AEC_LANG_MP', 'Northern Mariana Islands' );
define( '_AEC_LANG_MQ', 'Martinique' );
define( '_AEC_LANG_MR', 'Mauritania' );
define( '_AEC_LANG_MS', 'Montserrat' );
define( '_AEC_LANG_MT', 'Malta' );
define( '_AEC_LANG_MU', 'Mauritius' );
define( '_AEC_LANG_MV', 'Maldives' );
define( '_AEC_LANG_MW', 'Malawi' );
define( '_AEC_LANG_MX', 'Mexico' );
define( '_AEC_LANG_MY', 'Malaysia' );
define( '_AEC_LANG_MZ', 'Mozambique' );
define( '_AEC_LANG_NA', 'Namibia' );
define( '_AEC_LANG_NC', 'New Caledonia' );
define( '_AEC_LANG_NE', 'Niger' );
define( '_AEC_LANG_NF', 'Norfolk Island' );
define( '_AEC_LANG_NG', 'Nigeria' );
define( '_AEC_LANG_NI', 'Nicaragua' );
define( '_AEC_LANG_NL', 'Netherlands' );
define( '_AEC_LANG_NO', 'Norway' );
define( '_AEC_LANG_NP', 'Nepal' );
define( '_AEC_LANG_NR', 'Nauru' );
define( '_AEC_LANG_NU', 'Niue' );
define( '_AEC_LANG_NZ', 'New Zealand' );
define( '_AEC_LANG_OM', 'Oman' );
define( '_AEC_LANG_PA', 'Panama' );
define( '_AEC_LANG_PE', 'Peru' );
define( '_AEC_LANG_PF', 'French Polynesia' );
define( '_AEC_LANG_PG', 'Papua New Guinea' );
define( '_AEC_LANG_PH', 'Philippines' );
define( '_AEC_LANG_PK', 'Pakistan' );
define( '_AEC_LANG_PL', 'Poland' );
define( '_AEC_LANG_PM', 'Saint Pierre and Miquelon' );
define( '_AEC_LANG_PN', 'Pitcairn' );
define( '_AEC_LANG_PR', 'Puerto Rico' );
define( '_AEC_LANG_PS', 'Palestinian Territory, Occupied' );
define( '_AEC_LANG_PT', 'Portugal' );
define( '_AEC_LANG_PW', 'Palau' );
define( '_AEC_LANG_PY', 'Paraguay' );
define( '_AEC_LANG_QA', 'Qatar' );
define( '_AEC_LANG_RE', 'Reunion' );
define( '_AEC_LANG_RO', 'Romania' );
define( '_AEC_LANG_RS', 'Serbia' );
define( '_AEC_LANG_RU', 'Russian Federation' );
define( '_AEC_LANG_RW', 'Rwanda' );
define( '_AEC_LANG_SA', 'Saudi Arabia' );
define( '_AEC_LANG_SB', 'Solomon Islands' );
define( '_AEC_LANG_SC', 'Seychelles' );
define( '_AEC_LANG_SD', 'Sudan' );
define( '_AEC_LANG_SE', 'Sweden' );
define( '_AEC_LANG_SG', 'Singapore' );
define( '_AEC_LANG_SH', 'Saint Helena' );
define( '_AEC_LANG_SI', 'Slovenia' );
define( '_AEC_LANG_SJ', 'Svalbard and Jan Mayen' );
define( '_AEC_LANG_SK', 'Slovakia' );
define( '_AEC_LANG_SL', 'Sierra Leone' );
define( '_AEC_LANG_SM', 'San Marino' );
define( '_AEC_LANG_SN', 'Senegal' );
define( '_AEC_LANG_SO', 'Somalia' );
define( '_AEC_LANG_SR', 'Suriname' );
define( '_AEC_LANG_ST', 'Sao Tome and Principe' );
define( '_AEC_LANG_SV', 'El Salvador' );
define( '_AEC_LANG_SY', 'Syrian Arab Republic' );
define( '_AEC_LANG_SZ', 'Swaziland' );
define( '_AEC_LANG_TC', 'Turks and Caicos Islands' );
define( '_AEC_LANG_TD', 'Chad' );
define( '_AEC_LANG_TF', 'French Southern Territories' );
define( '_AEC_LANG_TG', 'Togo' );
define( '_AEC_LANG_TH', 'Thailand' );
define( '_AEC_LANG_TJ', 'Tajikistan' );
define( '_AEC_LANG_TK', 'Tokelau' );
define( '_AEC_LANG_TL', 'Timor-Leste' );
define( '_AEC_LANG_TM', 'Turkmenistan' );
define( '_AEC_LANG_TN', 'Tunisia' );
define( '_AEC_LANG_TO', 'Tonga' );
define( '_AEC_LANG_TR', 'Turkey' );
define( '_AEC_LANG_TT', 'Trinidad and Tobago' );
define( '_AEC_LANG_TV', 'Tuvalu' );
define( '_AEC_LANG_TW', 'Taiwan, Province of Republic of China' );
define( '_AEC_LANG_TZ', 'Tanzania, United Republic of' );
define( '_AEC_LANG_UA', 'Ukraine' );
define( '_AEC_LANG_UG', 'Uganda' );
define( '_AEC_LANG_UM', 'United States Minor Outlying Islands' );
define( '_AEC_LANG_US', 'United States' );
define( '_AEC_LANG_UY', 'Uruguay' );
define( '_AEC_LANG_UZ', 'Uzbekistan' );
define( '_AEC_LANG_VA', 'Holy See (Vatican City State)' );
define( '_AEC_LANG_VC', 'Saint Vincent and the Grenadines' );
define( '_AEC_LANG_VE', 'Venezuela, Bolivarian Republic of' );
define( '_AEC_LANG_VG', 'Virgin Islands, British' );
define( '_AEC_LANG_VI', 'Virgin Islands, U.S.' );
define( '_AEC_LANG_VN', 'Viet Nam' );
define( '_AEC_LANG_VU', 'Vanuatu' );
define( '_AEC_LANG_WF', 'Wallis and Futuna' );
define( '_AEC_LANG_WS', 'Samoa' );
define( '_AEC_LANG_YE', 'Yemen' );
define( '_AEC_LANG_YT', 'Mayotte' );
define( '_AEC_LANG_ZA', 'South Africa' );
define( '_AEC_LANG_ZM', 'Zambia' );
define( '_AEC_LANG_ZW', 'Zimbabwe' );

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
define( '_COUPON_PARAMS_MAX_REUSE_DESC',		'Hier die Anzahl eintragen wie oft dieser Gutschein verwendet werden darf' );
define( '_COUPON_PARAMS_HAS_MAX_PERUSER_REUSE_NAME', 'Restrict Reuse per User?');
define( '_COUPON_PARAMS_HAS_MAX_PERUSER_REUSE_DESC', 'Do you want to restrict the number of times every user is allowed to use this coupon?');
define( '_COUPON_PARAMS_MAX_PERUSER_REUSE_NAME', 'Max Uses per User');
define( '_COUPON_PARAMS_MAX_PERUSER_REUSE_DESC', 'Choose the number of times this coupon can be used by each user');

define( '_COUPON_PARAMS_USECOUNT_NAME',			'Reset' );
define( '_COUPON_PARAMS_USECOUNT_DESC',			'Hier kann der Z&auml;hler r&uuml;ckgesetzt werden' );

define( '_COUPON_PARAMS_USAGE_PLANS_ENABLED_NAME',	'Abo' );
define( '_COUPON_PARAMS_USAGE_PLANS_ENABLED_DESC',	'Soll dieser Gutschein nur f&uuml;r ein bestimmtes Abo gelten?' );
define( '_COUPON_PARAMS_USAGE_PLANS_NAME',			'Abos' );
define( '_COUPON_PARAMS_USAGE_PLANS_DESC',			'Welche Abos werden angewendet' );
define( '_COUPON_PARAMS_USAGE_CART_FULL_NAME', 'Use on Cart');
define( '_COUPON_PARAMS_USAGE_CART_FULL_DESC', 'Allow Application to a full shopping card');
define( '_COUPON_PARAMS_CART_MULTIPLE_ITEMS_NAME', 'Multiple Items');
define( '_COUPON_PARAMS_CART_MULTIPLE_ITEMS_DESC', 'Let the user apply the coupon to multiple items of a shopping cart, if overall restrictions permit it');
define( '_COUPON_PARAMS_CART_MULTIPLE_ITEMS_AMOUNT_NAME', 'Multiple Items Amount');
define( '_COUPON_PARAMS_CART_MULTIPLE_ITEMS_AMOUNT_DESC', 'Set a limit for application to multiple items of one shopping cart');

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
define( '_COUPON_RESTRICTIONS_DEPEND_ON_SUBSCR_ID_NAME', 'Depend on Subscription:');
define( '_COUPON_RESTRICTIONS_DEPEND_ON_SUBSCR_ID_DESC', 'Make the coupon depend on a certain subscription to be functional.');
define( '_COUPON_RESTRICTIONS_SUBSCR_ID_DEPENDENCY_NAME', 'Subscription ID');
define( '_COUPON_RESTRICTIONS_SUBSCR_ID_DEPENDENCY_DESC', 'The Subscription ID that the coupon will depend on.');
define( '_COUPON_RESTRICTIONS_ALLOW_TRIAL_DEPEND_SUBSCR_NAME', 'Allow Trial Subscriptions:');
define( '_COUPON_RESTRICTIONS_ALLOW_TRIAL_DEPEND_SUBSCR_DESC', 'Allow the use of the coupon when depending on a subscription that is still a trial.');
define( '_COUPON_RESTRICTIONS_RESTRICT_COMBINATION_CART_NAME', 'Restrict Combination Cart:');
define( '_COUPON_RESTRICTIONS_RESTRICT_COMBINATION_CART_DESC', 'Choose to not let your users combine this coupon with one of the following when applied to a cart');
define( '_COUPON_RESTRICTIONS_BAD_COMBINATIONS_CART_NAME', 'Coupons:');
define( '_COUPON_RESTRICTIONS_BAD_COMBINATIONS_CART_DESC', 'Make a selection which coupons this one is not to be used with');
define( '_COUPON_RESTRICTIONS_ALLOW_COMBINATION_NAME', 'Allow Combination:');
define( '_COUPON_RESTRICTIONS_ALLOW_COMBINATION_DESC', 'Choose to let your users combine this coupon with only with the following. Select none to disallow any combination.');
define( '_COUPON_RESTRICTIONS_GOOD_COMBINATIONS_NAME', 'Coupons:');
define( '_COUPON_RESTRICTIONS_GOOD_COMBINATIONS_DESC', 'Make a selection which coupons this one can to be used with');
define( '_COUPON_RESTRICTIONS_ALLOW_COMBINATION_CART_NAME', 'Allow Combination Cart:');
define( '_COUPON_RESTRICTIONS_ALLOW_COMBINATION_CART_DESC', 'Choose to let your users combine this coupon with only with the following in a cart. Select none to disallow any combination.');
define( '_COUPON_RESTRICTIONS_GOOD_COMBINATIONS_CART_NAME', 'Coupons:');
define( '_COUPON_RESTRICTIONS_GOOD_COMBINATIONS_CART_DESC', 'Make a selection which coupons this one can to be used with in a cart');

// END 0.12.4 (mic) ####################################################

// --== BACKEND TOOLBAR ==--
define( '_EXPIRE_SET',				'Ablaufdatum:' );
define( '_EXPIRE_NOW','Jetzt' );
define( '_EXPIRE_EXCLUDE','Herausnehmen' );
define( '_EXPIRE_INCLUDE','Wiedereinstellen' );
define( '_EXPIRE_CLOSE','Schlie&szlig;en' );
define( '_EXPIRE_HOLD','Halten');
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
define( '_LASTNAME_ASC','Last Name Asc');
define( '_LASTNAME_DESC','Last Name Desc');
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
define( '_ORDERING_ASC','Ordering Asc');
define( '_ORDERING_DESC','Ordering Desc');
define( '_ID_ASC','ID Asc');
define( '_ID_DESC','ID Desc');
define( '_CLASSNAME_ASC','Function Name Asc');
define( '_CLASSNAME_DESC','Function Desc');
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
define( '_COPY_PAYPLAN', 'Kopieren');
define( '_APPLY_PAYPLAN', 'Anwenden');
define( '_EDIT_PAYPLAN', 'Bearbeiten' );
define( '_REMOVE_PAYPLAN', 'L&ouml;schen' );
define( '_SAVE_PAYPLAN', 'Speichern' );
define( '_CANCEL_PAYPLAN', 'Abbrechen' );
define( '_PAYPLANS_TITLE', 			'Abonnement Verwaltung' );
define( '_PAYPLANS_MAINDESC',		'Ver&ouml;ffentlichte Abos werden den Benutzern angezeigt' );
define( '_PAYPLAN_GROUP', 'Group');
define( '_PAYPLAN_NOGROUP', 'No Group');
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
define( '_HISTORY_COL_INVOICE', 'Rechnung');
define( '_HISTORY_COL_AMOUNT', 'Betrag');
define( '_HISTORY_COL_DATE', 'Bezahlt');
define( '_HISTORY_COL_METHOD', 'Methode');
define( '_HISTORY_COL_ACTION', 'Aktion');
define( '_HISTORY_COL_PLAN', 'Abo');
define( '_USERINVOICE_ACTION_REPEAT',	'Wiederholung' );
define( '_USERINVOICE_ACTION_CANCEL',	'l&ouml;schen' );
define( '_USERINVOICE_ACTION_CLEAR',	'als&nbsp;bezahlt&nbsp;markieren' );
define( '_USERINVOICE_ACTION_CLEAR_APPLY',	'als&nbsp;bezahlt&nbsp;markieren&nbsp;&amp;&nbsp;Abo&nbsp;anwenden' );

// --== BACKEND SETTINGS ==--

// TAB 1 - Global AEC Settings
define( '_CFG_TAB1_TITLE',				'Konfigurationsoptionen' );
define( '_CFG_TAB1_SUBTITLE', 'Optionen f&uuml;r die Interaktion mit dem Benutzer' );

define( '_CFG_GENERAL_SUB_ACCESS', 'Access');
define( '_CFG_GENERAL_SUB_SYSTEM', 'System');
define( '_CFG_GENERAL_SUB_EMAIL', 'Email');
define( '_CFG_GENERAL_SUB_DEBUG', 'Debug');
define( '_CFG_GENERAL_SUB_REGFLOW', 'Registration Flow');
define( '_CFG_GENERAL_SUB_PLANS', 'Subscription Plans');
define( '_CFG_GENERAL_SUB_CONFIRMATION', 'Confirmation Page');
define( '_CFG_GENERAL_SUB_CHECKOUT', 'Checkout Page');
define( '_CFG_GENERAL_SUB_PROCESSORS', 'Payment Processors');
define( '_CFG_GENERAL_SUB_SECURITY', 'Security');

define( '_CFG_GENERAL_ALERTLEVEL2_NAME',			'Alarmebene 2:' );
define( '_CFG_GENERAL_ALERTLEVEL2_DESC',			'In Tagen. Dies ist die erste Grenze die beginnt den Benutzer auf den Auslauf seines Abonnements hinzuweisen.' );
define( '_CFG_GENERAL_ALERTLEVEL1_NAME',			'Alarmebene 1:' );
define( '_CFG_GENERAL_ALERTLEVEL1_DESC',			'In Tagen. Dies ist die letzte Grenze die beginnt den Benutzer auf den Auslauf seines Abonnements hinzuweisen.' );
define( '_CFG_GENERAL_ENTRY_PLAN_NAME',			'Einstiegsplan:' );
define( '_CFG_GENERAL_ENTRY_PLAN_DESC',			'Jeder Benutzer wird - wenn bisher kein Abonnement besteht - ohne Bezahlung diesem Plan zugewiesen' );
define( '_CFG_GENERAL_REQUIRE_SUBSCRIPTION_NAME',			'Erfordert Einschreibung:' );
define( '_CFG_GENERAL_REQUIRE_SUBSCRIPTION_DESC',			'Wenn aktiviert, <strong>muss</strong> der Benutzer ein g&uuml;ltiges Abonnement besitzen. Nicht aktiviert, Benutzer k&ouml;nnen ohne Abo einloggen.' );

define( '_CFG_GENERAL_GWLIST_NAME',			'Gateway Erkl&auml;rungen:' );
define( '_CFG_GENERAL_GWLIST_DESC',			'Hier Bezahlm&ouml;glichkeiten markieren welche auf der Nichterlaubt-Seite angezeigt werden sollen (diese Liste sehen die Benutzer, wenn sie versuchen eine Seite anzusehen f&uuml;r die sie keine Berechtigung haben).<br /><strong>Hinweis: es werden nur die oben, zur Zeit Aktiven angezeigt</strong>' );
define( '_CFG_GENERAL_GWLIST_ENABLED_NAME',			'Aktivierte Zahlungsgateways:' );
define( '_CFG_GENERAL_GWLIST_ENABLED_DESC',			'Alle Gateways markieren welche aktiv sein sollen (STRG-Taste dr&uuml;cken f&uuml;r mehrere).<br /><strong>Um die ge&auml;nderten Einstellungen anzuzeigen, den Button Speichern anklicken</strong><br />Deaktivieren eines Gateways l&ouml;scht nicht die bisherigen Einstellungen.' );

define( '_CFG_GENERAL_BYPASSINTEGRATION_NAME',			'Komponenten abschalten:' );
define( '_CFG_GENERAL_BYPASSINTEGRATION_DESC',			'Alle zu deaktivierenden Zusatzkomponenten angeben (mit Komma trennen!). Zur werden unterst&uuml;tzt: <strong>CB,CBE,CBM,JACL,SMF,UE,UHP2,VM</strong>.<br />Sollte z.B. CB (CommunityBuilder) deinstalliert werden aber dessen Datenbanktabellen noch vorhanden sein, jedoch hier <strong>kein</strong> Eintrag vermerkt sein, wird AEC dann weiterhin CB als installiert ansehen!.' );

define( '_CFG_GENERAL_SIMPLEURLS_NAME',			'Einfache URLs:' );
define( '_CFG_GENERAL_SIMPLEURLS_DESC',			'SEF-URLs der jeweiligen Komponenten abschalten. Falls bei der Verendung von SEF-URLs Fehler auftauchen (FEHLER 404) wurde in der SEF-Konfiguration ein Fehler gemacht - das Abschalten dieser Option hier kann diese Fehler beseitigen.' );
define( '_CFG_GENERAL_EXPIRATION_CUSHION_NAME',			'Ablaufschonfrist:' );
define( '_CFG_GENERAL_EXPIRATION_CUSHION_DESC',			'Anzahl der Stunden welche AEC als Polster nimmt bevor der Account abgeschalten wird. Es sollte bedacht werden, dass der Zahlungseingang etliche Stunden dauern kann (t.w. bis zu 14 Stunden!)' );
define( '_CFG_GENERAL_HEARTBEAT_CYCLE_NAME',			'Cronjob Zyklus:' );
define( '_CFG_GENERAL_HEARTBEAT_CYCLE_DESC',			'Anzahl der Stunden die AEC als Trigger nimmt um anstehende, wiederkehrende Aktionen (wie z.B. Emailversand) auszuf&uuml;hren.' );
define( '_CFG_GENERAL_ROOT_GROUP_NAME', 'Root Group:');
define( '_CFG_GENERAL_ROOT_GROUP_DESC', 'Choose the Root Group that the user is displayed when accessing the plans page without any preset variable.');
define( '_CFG_GENERAL_ROOT_GROUP_RW_NAME', 'Root Group ReWrite:');
define( '_CFG_GENERAL_ROOT_GROUP_RW_DESC', 'Choose the Root Group that the user is displayed when accessing the plans page by returning a group number or an array of groups with the ReWriteEngine functionality. This will fall back to the general option (above) if the results are empty.');
define( '_CFG_GENERAL_PLANS_FIRST_NAME',			'Plan Zuerst:' );
define( '_CFG_GENERAL_PLANS_FIRST_DESC',			'Wenn alle Zusatzkomponenten mit einer Aboaktion verbunden sind, zeigt diese Option die Bezahlpl&auml;ne zuerst. Falls das nicht gew&uuml;nscht ist oder nur die erste Integrationsm&ouml;glichkeit zur Auswahl stehen soll, dann hier nicht aktivieren - die Aboauswahl kommt dann <strong>nach</strong> der Anmeldung/Registrierung.' );
define( '_CFG_GENERAL_INTEGRATE_REGISTRATION_NAME', 'Integrate Registration');
define( '_CFG_GENERAL_INTEGRATE_REGISTRATION_DESC', 'With this switch, you can make the AEC Mambot/Plugin intercept registration calls and redirect them into the AEC subscription system. Having this option disabled means that the users would freely register and, if a subscription is required, subscribe on their first login. If both this option and "require subscription" are disabled, subscription is completely voluntary.');

define( '_CFG_TAB_CUSTOMIZATION_TITLE',	'Anpassen' );
define( '_CFG_TAB_CUSTOMIZATION_SUBTITLE', 'Anpassungen');

define( '_CFG_CUSTOMIZATION_SUB_CREDIRECT', 'Custom Redirects');
define( '_CFG_CUSTOMIZATION_SUB_PROXY', 'Proxy');
define( '_CFG_CUSTOMIZATION_SUB_BUTTONS_SUB', 'Subscribed Member Buttons');
define( '_CFG_CUSTOMIZATION_SUB_FORMAT_DATE', 'Date Formatting');
define( '_CFG_CUSTOMIZATION_SUB_FORMAT_PRICE', 'Price Formatting');
define( '_CFG_CUSTOMIZATION_SUB_FORMAT_INUM', 'Invoice Number Format');
define( '_CFG_CUSTOMIZATION_SUB_CAPTCHA', 'ReCAPTACHA');

define( '_CFG_GENERAL_CUSTOMINTRO_NAME',			'Individuelle Einstiegsseite:' );
define( '_CFG_GENERAL_CUSTOMINTRO_DESC',			'Hier den kompletten Link (inkl. http://) angeben der zur Einstiegsseite f&uuml;hren soll. Diese Seite sollte einen Link wie z.B. http://www.yourdomain.com/index.php?option=com_acctexp&amp;task=subscribe&amp;intro=1 beinhalten welcher die Einf&uuml;hrung &uuml;bergeht und den Benutzer direkt zur Aboseite oder Registrierungsseite f&uuml;hrt.<br />Wenn diese Option nicht gew&uuml;nscht wird, dann dieses Feld leer lassen.' );
define( '_CFG_GENERAL_CUSTOMINTRO_USERID_NAME', 'Pass Userid');
define( '_CFG_GENERAL_CUSTOMINTRO_USERID_DESC', 'Pass Userid via a Joomla notification. This can be helpful for flexible custom signup pages that need to function even if the user is not logged in. You can use Javascript to modify your signup links according to the passed userid.');
define( '_CFG_GENERAL_CUSTOMINTRO_ALWAYS_NAME', 'Always Show Intro');
define( '_CFG_GENERAL_CUSTOMINTRO_ALWAYS_DESC', 'Display the Intro regardless of whether the user is already registered.');
define( '_CFG_GENERAL_CUSTOMTHANKS_NAME',			'Link zu individueller Dankeseite:' );
define( '_CFG_GENERAL_CUSTOMTHANKS_DESC',			'Kompletten Link (inkl. http://) angeben welchen die Besucher zur Dankeseite f&uuml;hrt. Wenn nicht gew&uuml;nscht dann leer lassen.' );
define( '_CFG_GENERAL_CUSTOMCANCEL_NAME',			'Link zu individueller Abbruchseite:' );
define( '_CFG_GENERAL_CUSTOMCANCEL_DESC',			'Kompletten Link (inkl. http://) angeben welchen die Besucher - wenn Abbruch - zu dieser  Seite f&uuml;hrt. Wenn nicht gew&uuml;nscht dann leer lassen.' );
define( '_CFG_GENERAL_TOS_NAME',			'Link zu den AGBs:' );
define( '_CFG_GENERAL_TOS_DESC',			'Hier den Link zu den AGBS angeben. Die Benutzer m&uuml;ssen dann dort zum Einverst&auml;ndnis eine Checkbox aktivieren. Leer lassen wenn nicht gew&uuml;nscht' );
define( '_CFG_GENERAL_TOS_IFRAME_NAME', 'ToS Iframe:');
define( '_CFG_GENERAL_TOS_IFRAME_DESC', 'Display the Terms of Service (as specified above) in an iframe on confirmation');
define( '_CFG_GENERAL_CUSTOMNOTALLOWED_DESC',	'Hier den kompletten Link (inkl. http://) angeben welche die Besucher zur Nichterlaubtseite f&uuml;hrt. Leer lassen wenn nicht gew&uuml;nscht.' );

define( '_CFG_CUSTOMIZATION_INVOICE_PRINTOUT', 'Invoice Printout');
define( '_CFG_CUSTOMIZATION_INVOICE_PRINTOUT_DETAILS', 'Invoice Printout Details');

define( '_CFG_TAB_CUSTOMINVOICE_TITLE', 'Invoice Customization');
define( '_CFG_TAB_CUSTOMINVOICE_SUBTITLE', 'Invoice Customization');
define( '_CFG_TAB_CUSTOMPAGES_TITLE', 'Page Customization');
define( '_CFG_TAB_CUSTOMPAGES_SUBTITLE', 'Page Customization');
define( '_CFG_TAB_EXPERT_TITLE', 'Expert');
define( '_CFG_TAB_EXPERT_SUBTITLE', 'Expert Settings');

define( '_AEC_CUSTOM_INVOICE_PAGE_TITLE', 'Invoice');
define( '_AEC_CUSTOM_INVOICE_HEADER', 'Invoice');
define( '_AEC_CUSTOM_INVOICE_BEFORE_CONTENT', 'Invoice/Receipt for:');
define( '_AEC_CUSTOM_INVOICE_AFTER_CONTENT', 'Thank you very much for choosing our service!');
define( '_AEC_CUSTOM_INVOICE_FOOTER', ' - Add your company information here - ');

define( '_CFG_GENERAL_INVOICE_PAGE_TITLE', 'Invoice');
define( '_CFG_GENERAL_INVOICE_HEADER_NAME', 'Invoice Header');
define( '_CFG_GENERAL_INVOICE_HEADER_DESC', 'Header Text for the Invoice Printout');
define( '_CFG_GENERAL_INVOICE_AFTER_HEADER_NAME', 'Invoice After Header');
define( '_CFG_GENERAL_INVOICE_AFTER_HEADER_DESC', 'Text after Header for the Invoice Printout');
define( '_CFG_GENERAL_INVOICE_BEFORE_CONTENT_NAME', 'Invoice Before Content');
define( '_CFG_GENERAL_INVOICE_BEFORE_CONTENT_DESC', 'Text before Invoice Content for the Invoice Printout');
define( '_CFG_GENERAL_INVOICE_AFTER_CONTENT_NAME', 'Invoice After Content');
define( '_CFG_GENERAL_INVOICE_AFTER_CONTENT_DESC', 'Text after Invoice Content for the Invoice Printout');
define( '_CFG_GENERAL_INVOICE_BEFORE_FOOTER_NAME', 'Invoice Before Footer');
define( '_CFG_GENERAL_INVOICE_BEFORE_FOOTER_DESC', 'Text before Footer for the Invoice Printout');
define( '_CFG_GENERAL_INVOICE_FOOTER_NAME', 'Invoice Footer');
define( '_CFG_GENERAL_INVOICE_FOOTER_DESC', 'Footer Text for the Invoice Printout');
define( '_CFG_GENERAL_INVOICE_AFTER_FOOTER_NAME', 'Invoice After Footer');
define( '_CFG_GENERAL_INVOICE_AFTER_FOOTER_DESC', 'Text after Footer for the Invoice Printout');

define( '_CFG_GENERAL_CHECKOUT_DISPLAY_DESCRIPTIONS_NAME', 'Display Descriptions:');
define( '_CFG_GENERAL_CHECKOUT_DISPLAY_DESCRIPTIONS_DESC', 'If you have multiple plans on checkout, or skipped the confirmation, it might be helpful to show the plan description again. This switch does just that.');
define( '_CFG_GENERAL_CHECKOUT_AS_GIFT_NAME', 'Allow Gift Checkout:');
define( '_CFG_GENERAL_CHECKOUT_AS_GIFT_DESC', 'With this option, users can gift a checkout to another user - all the plans and attached functionality is then carried out on the recipients user account.');
define( '_CFG_GENERAL_CHECKOUT_AS_GIFT_ACCESS_NAME', 'Gifts Access:');
define( '_CFG_GENERAL_CHECKOUT_AS_GIFT_ACCESS_DESC', 'What user group is required (minimum) to can make a checkout into a gift?');
define( '_CFG_GENERAL_CONFIRM_AS_GIFT_NAME', 'Allow Gift on Confirmation:');
define( '_CFG_GENERAL_CONFIRM_AS_GIFT_DESC', 'Offer the same gift option on the confirmation page as well.');

define( '_CFG_GENERAL_DISPLAY_DATE_FRONTEND_NAME',	'Frontend Datumsformat' );
define( '_CFG_GENERAL_DISPLAY_DATE_FRONTEND_DESC',	'Hier angeben wie die Datumsangaben den Besuchern gegen&uuml;ber erfolgen sollen. Mehr dazu im <a href="http://www.php.net/manual/de/function.strftime.php" target="_blank" title="PHP Handbuch">PHP Handbuch</a>' );
define( '_CFG_GENERAL_DISPLAY_DATE_BACKEND_NAME',	'Backend Datumsformat' );
define( '_CFG_GENERAL_DISPLAY_DATE_BACKEND_DESC',	'Hier angeben wie die Datumsangaben im Backend erfolgen sollen. Mehr dazu im <a href="http://www.php.net/manual/de/function.strftime.php" target="_blank" title="PHP Handbuch">PHP Handbuch</a>' );

define( '_CFG_GENERAL_INVOICENUM_DOFORMAT_NAME', 'Format Invoice Number');
define( '_CFG_GENERAL_INVOICENUM_DOFORMAT_DESC', 'Display a formatted string instead of the original InvoiceNumber to the user. Must provide a formatting rule below.');
define( '_CFG_GENERAL_INVOICENUM_FORMATTING_NAME', 'Formatting');
define( '_CFG_GENERAL_INVOICENUM_FORMATTING_DESC', 'The Formatting - You can use the RewriteEngine as specified below');

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

define( '_CFG_GENERAL_CUSTOMTEXT_THANKS_KEEPORIGINAL_NAME', 'Keep Original Text');
define( '_CFG_GENERAL_CUSTOMTEXT_THANKS_KEEPORIGINAL_DESC', 'Select this option if you want to keep the original text on the ThankYou Page');
define( '_CFG_GENERAL_CUSTOMTEXT_THANKS_NAME', 'Custom Text ThankYou Page');
define( '_CFG_GENERAL_CUSTOMTEXT_THANKS_DESC', 'Text that will be displayed on the ThankYou Page');
define( '_CFG_GENERAL_CUSTOMTEXT_CANCEL_KEEPORIGINAL_NAME', 'Keep Original Text');
define( '_CFG_GENERAL_CUSTOMTEXT_CANCEL_KEEPORIGINAL_DESC', 'Select this option if you want to keep the original text on the Cancel Page');
define( '_CFG_GENERAL_CUSTOMTEXT_CANCEL_NAME', 'Custom Text Cancel Page');
define( '_CFG_GENERAL_CUSTOMTEXT_CANCEL_DESC', 'Text that will be displayed on the Cancel Page');

define( '_CFG_GENERAL_CUSTOMTEXT_HOLD_KEEPORIGINAL_NAME', 'Keep Original Text');
define( '_CFG_GENERAL_CUSTOMTEXT_HOLD_KEEPORIGINAL_DESC', 'Select this option if you want to keep the original text on the Hold Page');
define( '_CFG_GENERAL_CUSTOMTEXT_HOLD_NAME', 'Custom Text Hold Page');
define( '_CFG_GENERAL_CUSTOMTEXT_HOLD_DESC', 'Text that will be displayed on the Hold Page');

define( '_CFG_GENERAL_CUSTOMTEXT_EXCEPTION_KEEPORIGINAL_NAME', 'Keep Original Text');
define( '_CFG_GENERAL_CUSTOMTEXT_EXCEPTION_KEEPORIGINAL_DESC', 'Select this option if you want to keep the original text on the Exception Page');
define( '_CFG_GENERAL_CUSTOMTEXT_EXCEPTION_NAME', 'Custom Text Exception Page');
define( '_CFG_GENERAL_CUSTOMTEXT_EXCEPTION_DESC', 'Text that will be displayed on the Exception Page (typically showing up when a user has to specify which payment processor to use for a shopping cart, or what item a coupon should be applied to).');

define( '_CFG_GENERAL_USE_RECAPTCHA_NAME', 'Use ReCAPTCHA');
define( '_CFG_GENERAL_USE_RECAPTCHA_DESC', 'If you have an account for <a href="http://recaptcha.net/">ReCAPTCHA</a>, you can activate this option. Do NOT forget to put in the keys below.');
define( '_CFG_GENERAL_RECAPTCHA_PRIVATEKEY_NAME', 'Private ReCAPTCHA Key');
define( '_CFG_GENERAL_RECAPTCHA_PRIVATEKEY_DESC', 'Your Private ReCAPTCHA Key.');
define( '_CFG_GENERAL_RECAPTCHA_PUBLICKEY_NAME', 'Public ReCAPTCHA Key');
define( '_CFG_GENERAL_RECAPTCHA_PUBLICKEY_DESC', 'Your Public ReCAPTCHA Key.');

define( '_CFG_GENERAL_TEMP_AUTH_EXP_NAME', 'Logged-out Invoice Access');
define( '_CFG_GENERAL_TEMP_AUTH_EXP_DESC', 'The time (in minutes) that a user is able to access an invoice only by referencing the userid. When that period expires, a password is prompted before allowing access for the same period again.');

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
define( '_CFG_GENERAL_DEBUGMODE_NAME', 'Debug Mode');
define( '_CFG_GENERAL_DEBUGMODE_DESC', 'Activates the display of debug information.');
define( '_CFG_GENERAL_OVERRIDE_REQSSL_NAME', 'Override SSL Requirement');
define( '_CFG_GENERAL_OVERRIDE_REQSSL_DESC', 'Some payment processors require an SSL secured connection to the user - for example when sensitive information (like CreditCard data) is required on the frontend.');
define( '_CFG_GENERAL_ALTSSLURL_NAME', 'Alternative SSL Url');
define( '_CFG_GENERAL_ALTSSLURL_DESC', 'Use this URL instead of the base url that is configured in Joomla! when routing through SSL.');
define( '_CFG_GENERAL_OVERRIDEJ15_NAME', 'Override Joomla 1.5 Integration');
define( '_CFG_GENERAL_OVERRIDEJ15_DESC', 'Some Addons trick a 1.0 Joomla into believing it really is 1.5 (you know who you are! stop it!) - which AEC follows and fails. This makes a permanent switch forcing 1.0 mode.');
define( '_CFG_GENERAL_SSL_SIGNUP_NAME', 'SSL Signup');
define( '_CFG_GENERAL_SSL_SIGNUP_DESC', 'Use SSL Encryption on all links that have to do with the user singing up within the AEC.');
define( '_CFG_GENERAL_SSL_PROFILE_NAME', 'SSL Profile');
define( '_CFG_GENERAL_SSL_PROFILE_DESC', 'Use SSL Encryption on all links that have to do with the user accessing the profile (MySubscription page).');
define( '_CFG_GENERAL_SSL_VERIFYPEER_NAME', 'SSL Peer Verification');
define( '_CFG_GENERAL_SSL_VERIFYPEER_DESC', 'When using cURL, make it verify the peer\'s certificate. Alternate certificates to verify against can be specified with the options below');
define( '_CFG_GENERAL_SSL_VERIFYHOST_NAME', 'SSL Host Verification');
define( '_CFG_GENERAL_SSL_VERIFYHOST_DESC', 'Defines what kind of verification against the peer\'s certificate you want.');
define( '_CFG_GENERAL_SSL_CAINFO_NAME', 'Certificate File');
define( '_CFG_GENERAL_SSL_CAINFO_DESC', 'The name of a file holding one or more certificates to verify the peer with. This only makes sense when used in combination with Peer Verification.');
define( '_CFG_GENERAL_SSL_CAPATH_NAME', 'Certificate Directory');
define( '_CFG_GENERAL_SSL_CAPATH_DESC', 'A directory that holds multiple CA certificates. Use this option alongside Peer Verification.');
define( '_CFG_GENERAL_USE_PROXY_NAME', 'Use Proxy');
define( '_CFG_GENERAL_USE_PROXY_DESC', 'Use a proxy server for all outgoing requests.');
define( '_CFG_GENERAL_PROXY_NAME', 'Proxy Address');
define( '_CFG_GENERAL_PROXY_DESC', 'Specify the proxy server that you want to connect to.');
define( '_CFG_GENERAL_PROXY_PORT_NAME', 'Proxy Port');
define( '_CFG_GENERAL_PROXY_PORT_DESC', 'Specify the port of the proxy server that you want to connect to.');
define( '_CFG_GENERAL_PROXY_USERNAME_NAME', 'Proxy Username');
define( '_CFG_GENERAL_PROXY_USERNAME_DESC', 'If your proxy needs a custom login, put the username here.');
define( '_CFG_GENERAL_PROXY_PASSWORD_NAME', 'Proxy Password');
define( '_CFG_GENERAL_PROXY_PASSWORD_DESC', 'If your proxy needs a custom login, put the password here.');
define( '_CFG_GENERAL_GETHOSTBYADDR_NAME', 'Log Host with IP');
define( '_CFG_GENERAL_GETHOSTBYADDR_DESC', 'On logging Events that store an IP address, this option will also store the internet host name as well. In some hosting situations, this can take over a minute and thus should be disabled.');
define( '_CFG_GENERAL_RENEW_BUTTON_NEVER_NAME', 'No Renew Button');
define( '_CFG_GENERAL_RENEW_BUTTON_NEVER_DESC', 'Select "Yes" to never show the renew/upgrade button on the MySubscription page.');
define( '_CFG_GENERAL_RENEW_BUTTON_NOLIFETIMERECURRING_NAME', 'Restricted Renew Button');
define( '_CFG_GENERAL_RENEW_BUTTON_NOLIFETIMERECURRING_DESC', 'Only show the renew button if it makes sense in a one-subscription setup (recurring payments or a lifetime make the button disappear).');
define( '_CFG_GENERAL_CONTINUE_BUTTON_NAME', 'Continue Button');
define( '_CFG_GENERAL_CONTINUE_BUTTON_DESC', 'If the user has had a membership before, this button will show up on the expiration screen and link directly to the previous plan, so that the user is quicker to continue the membership as it was before');

define( '_CFG_GENERAL_ERROR_NOTIFICATION_LEVEL_NAME', 'Notification Level');
define( '_CFG_GENERAL_ERROR_NOTIFICATION_LEVEL_DESC', 'Select which level of entries to the EventLog is required to make it appear on the central page for your convenience.');
define( '_CFG_GENERAL_EMAIL_NOTIFICATION_LEVEL_NAME', 'Email Notification Level');
define( '_CFG_GENERAL_EMAIL_NOTIFICATION_LEVEL_DESC', 'Select which level of entries to the EventLog is required to make the AEC send them as an E-Mail to all Administrators.');

define( '_CFG_GENERAL_SKIP_CONFIRMATION_NAME', 'Skip Confirmation');
define( '_CFG_GENERAL_SKIP_CONFIRMATION_DESC', 'Do not display a Confirmation screen before checkout (which lets the user revisit the current decision).');
define( '_CFG_GENERAL_SHOW_FIXEDDECISION_NAME', 'Show Fixed Decisions');
define( '_CFG_GENERAL_SHOW_FIXEDDECISION_DESC', 'The AEC normally skips the payment plans page if there is no decision to be made (one payment plan with only one processor). With this option, you can force it to display the page.');
define( '_CFG_GENERAL_CONFIRMATION_COUPONS_NAME', 'Coupons on Confirmation');
define( '_CFG_GENERAL_CONFIRMATION_COUPONS_DESC', 'Offer to provide coupon codes when clicking the Confirm Button on the Confirmation page');
define( '_CFG_GENERAL_BREAKON_MI_ERROR_NAME', 'Break on MI Error');
define( '_CFG_GENERAL_BREAKON_MI_ERROR_DESC', 'Stop plan application if one of its attached MIs encounters an error (there will be trace in the eventlog either way)');

define( '_CFG_GENERAL_ENABLE_SHOPPINGCART_NAME', 'Enable Shopping Cart');
define( '_CFG_GENERAL_ENABLE_SHOPPINGCART_DESC', 'Handle purchases via shopping cart. Available only for logged-in users.');
define( '_CFG_GENERAL_CUSTOMLINK_CONTINUESHOPPING_NAME', 'Custom Continue Shopping Link');
define( '_CFG_GENERAL_CUSTOMLINK_CONTINUESHOPPING_DESC', 'Instead of routing a user to the standard subscription page, route here.');
define( '_CFG_GENERAL_ADDITEM_STAYONPAGE_NAME', 'Stay on Page');
define( '_CFG_GENERAL_ADDITEM_STAYONPAGE_DESC', 'Instead of moving to the shopping cart after selecting an item, stay on the same page.');

define( '_CFG_GENERAL_CURL_DEFAULT_NAME', 'Use cURL');
define( '_CFG_GENERAL_CURL_DEFAULT_DESC', 'Use cURL instead of fsockopen as default (will fall back to the other one if the first choice fails)');
define( '_CFG_GENERAL_AMOUNT_CURRENCY_SYMBOL_NAME', 'Currency Symbol');
define( '_CFG_GENERAL_AMOUNT_CURRENCY_SYMBOL_DESC', 'Display a currency symbol (if one exists) instead of the ISO abbreviation');
define( '_CFG_GENERAL_AMOUNT_CURRENCY_SYMBOLFIRST_NAME', 'Currency first');
define( '_CFG_GENERAL_AMOUNT_CURRENCY_SYMBOLFIRST_DESC', 'Display the currency in front of the amount');
define( '_CFG_GENERAL_AMOUNT_USE_COMMA_NAME', 'Use Comma');
define( '_CFG_GENERAL_AMOUNT_USE_COMMA_DESC', 'Use a comma instead of a dot in amounts');
define( '_CFG_GENERAL_ALLOW_FRONTEND_HEARTBEAT_NAME', 'Allow Custom Frontend Heartbeat');
define( '_CFG_GENERAL_ALLOW_FRONTEND_HEARTBEAT_DESC', 'Trigger a custom heartbeat from the frontend (via the link index.php?option=com_acctexp&task=heartbeat) - for example with a Cronjob');
define( '_CFG_GENERAL_DISABLE_REGULAR_HEARTBEAT_NAME', 'Disable Automatic Heartbeat');
define( '_CFG_GENERAL_DISABLE_REGULAR_HEARTBEAT_DESC', 'If you only want to trigger custom heartbeats, you can disable the automatic ones here.');
define( '_CFG_GENERAL_CUSTOM_HEARTBEAT_SECUREHASH_NAME', 'Custom Frontend Heartbeat Securehash');
define( '_CFG_GENERAL_CUSTOM_HEARTBEAT_SECUREHASH_DESC', 'A code that has to be passed on custom Frontend Heartbeat (with the option &hash=YOURHASHCODE) - if one is set, but not passed, the AEC will not trigger the heartbeat.');
define( '_CFG_GENERAL_QUICKSEARCH_TOP_NAME', 'Quicksearch on top');
define( '_CFG_GENERAL_QUICKSEARCH_TOP_DESC', 'This is the setting for all you quicksearch junkies - it will switch it to be above the main icons on the central page');

define( '_CFG_GENERAL_SUB_UNINSTALL', 'Uninstall');
define( '_CFG_GENERAL_DELETE_TABLES_NAME', 'Delete Tables');
define( '_CFG_GENERAL_DELETE_TABLES_DESC', 'Do you want to delete the AEC tables when uninstalling the software?');
define( '_CFG_GENERAL_DELETE_TABLES_SURE_NAME', 'Really?');
define( '_CFG_GENERAL_DELETE_TABLES_SURE_DESC', 'Security switch - when deleting the AEC tables, ALL YOUR MEMBERSHIP DATA WILL BE GONE!');
define( '_CFG_GENERAL_STANDARD_CURRENCY_NAME', 'Standard Currency');
define( '_CFG_GENERAL_STANDARD_CURRENCY_DESC', 'Which currency should the AEC use if no information is available (for example, if a plan is free, it will have no processor attached to it and get its currency information from here)');

define( '_CFG_GENERAL_CONFIRMATION_CHANGEUSERNAME_NAME', 'Option: Change Username');
define( '_CFG_GENERAL_CONFIRMATION_CHANGEUSERNAME_DESC', 'Give new users the possibility to go back to the registration screen from confirmation (in case they made an error)');
define( '_CFG_GENERAL_CONFIRMATION_CHANGEUSAGE_NAME', 'Option: Change Item');
define( '_CFG_GENERAL_CONFIRMATION_CHANGEUSAGE_DESC', 'Give new users the possibility to go back to the plan selection screen from confirmation (in case they made an error)');

// Global Authentication Settins
define( '_CFG_TAB_AUTHENTICATION_TITLE', 'Authentication');
define( '_CFG_TAB_AUTHENTICATION_SUBTITLE', 'Authentication Plugins');
define( '_CFG_AUTH_AUTHLIST_NAME', 'Active Authentication Plugins');
define( '_CFG_AUTH_AUTHLIST_DESC', 'Select which Authentication (as in: at least one of them has to be successful for the login to pass) Plugins will be used. AECaeccss Plugin must be the only Authentication Plugin enabled in the Joomla Plugin Manager.');
define( '_CFG_AUTH_AUTHORIZATION_LIST_NAME', 'Active Authorization Plugins');
define( '_CFG_AUTH_AUTHORIZATION_LIST_DESC', 'Select which Authorization (as in: all of them have to be successful for the login to pass) Plugins will be used. AECaeccss Plugin must be the only Authentication Plugin enabled in the Joomla Plugin Manager.');

//Invoice settings
define( '_CFG_GENERAL_SENDINVOICE_NAME', 'Send an invoice email');
define( '_CFG_GENERAL_SENDINVOICE_DESC', 'Send and invoice/purchase order email (for tax reasons)');
define( '_CFG_GENERAL_INVOICETMPL_NAME', 'Invoice Template');
define( '_CFG_GENERAL_INVOICETMPL_DESC', 'Template for invoices/purchase orders');

// --== Processors PAGE ==--

define( '_PROCESSORS_TITLE', 'Processors');
define( '_PROCESSOR_NAME', 'Name');
define( '_PROCESSOR_DESC', 'Description (first 50 chars)');
define( '_PROCESSOR_ACTIVE', 'Published');
define( '_PROCESSOR_VISIBLE', 'Visible');
define( '_PROCESSOR_REORDER', 'Reorder');
define( '_PROCESSOR_INFO', 'Information');

define( '_PUBLISH_PROCESSOR', 'Publish');
define( '_UNPUBLISH_PROCESSOR', 'Unpublish');
define( '_NEW_PROCESSOR', 'New');
define( '_COPY_PROCESSOR', 'Copy');
define( '_APPLY_PROCESSOR', 'Apply');
define( '_EDIT_PROCESSOR', 'Edit');
define( '_REMOVE_PROCESSOR', 'Delete');
define( '_SAVE_PROCESSOR', 'Save');
define( '_CANCEL_PROCESSOR', 'Cancel');

define( '_PP_GENERAL_PROCESSOR_NAME', 'Payment Processor');
define( '_PP_GENERAL_PROCESSOR_DESC', 'Select which payment processor you want to use.');
define( '_PP_GENERAL_ACTIVE_NAME', 'Active');
define( '_PP_GENERAL_ACTIVE_DESC', 'Select whether this processor is currently active (and thus can carry out its function and be available to your users)');
define( '_PP_GENERAL_PLEASE_NOTE', 'Please note');
define( '_PP_GENERAL_EXPERIMENTAL', 'This payment processor is still not 100% complete - it has either been added to the codebase very recently (and is thus not fully tested) or was partly abandoned due to a customer suddenly not being interested in having us finish it anymore. If you want to use it, we would be very thankful for any kind of helping hand you can give us - either with further information on the integration, with bugreports or fixes, or with sponsorship.');

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

define( '_PAYPLAN_GENERAL_CUSTOMAMOUNTFORMAT_NAME', 'Custom amount formatting:');
define( '_PAYPLAN_GENERAL_CUSTOMAMOUNTFORMAT_DESC', 'Please use a aecJSON string like the one already filled in to modify how the cost of this plan are displayed.');
define( '_PAYPLAN_GENERAL_CUSTOMTHANKS_NAME', 'Custom thanks page link:');
define( '_PAYPLAN_GENERAL_CUSTOMTHANKS_DESC', 'Provide a full link (including http://) that leads to your custom thanks page. Leave this field blank if you don\'t want this at all.');
define( '_PAYPLAN_GENERAL_CUSTOMTEXT_THANKS_KEEPORIGINAL_NAME', 'Keep Original Text');
define( '_PAYPLAN_GENERAL_CUSTOMTEXT_THANKS_KEEPORIGINAL_DESC', 'Select this option if you want to keep the original text on the ThankYou Page');
define( '_PAYPLAN_GENERAL_CUSTOMTEXT_THANKS_NAME', 'Custom Text ThankYou Page');
define( '_PAYPLAN_GENERAL_CUSTOMTEXT_THANKS_DESC', 'Text that will be displayed on the ThankYou Page');

define( '_PAYPLAN_PARAMS_OVERRIDE_ACTIVATION_NAME', 'Override Activation');
define( '_PAYPLAN_PARAMS_OVERRIDE_ACTIVATION_DESC', 'Override the requirement for a user to activate the account (via email activation code) in case this payment plan is used with a registration.');
define( '_PAYPLAN_PARAMS_OVERRIDE_REGMAIL_NAME', 'Override Registration Email');
define( '_PAYPLAN_PARAMS_OVERRIDE_REGMAIL_DESC', 'Do not send out any Registration Email (makes sense for paid plans which do not need activation and where an email would be sent out when the payment arrives - with the email MI).');

define( '_PAYPLAN_PARAMS_GID_ENABLED_NAME',				'Benutzergruppe' );
define( '_PAYPLAN_PARAMS_GID_ENABLED_DESC',				'Auf JA setzen wenn der Benutzer zu dieser Benutzergruppe geh&ouml;ren soll' );
define( '_PAYPLAN_PARAMS_GID_NAME',						'Zur Gruppe dazu:' );
define( '_PAYPLAN_PARAMS_GID_DESC',						'Benutzer werden dieser Gruppe hinzugef&uuml;gt wenn das Abo gew&auml;hlt wird' );
define( '_PAYPLAN_PARAMS_MAKE_ACTIVE_NAME', 			'Benutzer Aktivieren:');
define( '_PAYPLAN_PARAMS_MAKE_ACTIVE_DESC',				'Auf >Nein< setzen, falls der Benutzer von Hand in die aktive Gruppe verschoben werden soll.');
define( '_PAYPLAN_PARAMS_MAKE_PRIMARY_NAME', 'Primary:');
define( '_PAYPLAN_PARAMS_MAKE_PRIMARY_DESC', 'Set this to "yes" to make this the primary subscription plan for the user. The primary subscription is the one which governs overal site expiration.');
define( '_PAYPLAN_PARAMS_UPDATE_EXISTING_NAME', 'Update Existing:');
define( '_PAYPLAN_PARAMS_UPDATE_EXISTING_DESC', 'If not a primary plan, should this plan update other existing non-primary subscriptions of the user? This can be helpful for secondary subscriptions of which the user should have only one at a time.');

define( '_PAYPLAN_TEXT_TITLE',							'Abotext' );
define( '_PAYPLAN_GENERAL_EMAIL_DESC_NAME',				'Emailtext:' );
define( '_PAYPLAN_GENERAL_EMAIL_DESC_DESC',				'Text welcher im Email an den Benutzer angezeigt wird wenn das Abo best&auml;tigt wurde' );
define( '_PAYPLAN_GENERAL_FALLBACK_NAME',				'Abo Ersatz:' );
define( '_PAYPLAN_GENERAL_FALLBACK_DESC',				'Wenn ein Abo endet, aktiviere dieses Abo f&uuml;r diesen Benutzer' );
define( '_PAYPLAN_GENERAL_STANDARD_PARENT_NAME', 'Standard Parent Plan');
define( '_PAYPLAN_GENERAL_STANDARD_PARENT_DESC', 'Currently assigns this plan as the users root membership in case he or she signs up only for a secondary plan.');

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

define( '_PAYPLAN_PARAMS_NOTAUTH_REDIRECT_NAME', 'Denied Access Redirect');
define( '_PAYPLAN_PARAMS_NOTAUTH_REDIRECT_DESC', 'Redirect to a different URL should the user follow a direct link to this item without having the right authorization.');

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

define( '_PAYPLAN_RESTRICTIONS_PREVIOUSPLAN_REQ_ENABLED_EXCLUDED_NAME', 'Excluded Prev. Plan:');
define( '_PAYPLAN_RESTRICTIONS_PREVIOUSPLAN_REQ_ENABLED_EXCLUDED_DESC', 'Do NOT show this plan to users who had the selected plan as their previous payment plan');
define( '_PAYPLAN_RESTRICTIONS_PREVIOUSPLAN_REQ_EXCLUDED_NAME', 'Plan:');
define( '_PAYPLAN_RESTRICTIONS_PREVIOUSPLAN_REQ_EXCLUDED_DESC', 'A user will not see this plan if he or she used the selected plan before the one currently in use');
define( '_PAYPLAN_RESTRICTIONS_CURRENTPLAN_REQ_ENABLED_EXCLUDED_NAME', 'Excluded Curr. Plan:');
define( '_PAYPLAN_RESTRICTIONS_CURRENTPLAN_REQ_ENABLED_EXCLUDED_DESC', 'Do NOT show this plan to users who have the selected plan as their currently present payment plan');
define( '_PAYPLAN_RESTRICTIONS_CURRENTPLAN_REQ_EXCLUDED_NAME', 'Plan:');
define( '_PAYPLAN_RESTRICTIONS_CURRENTPLAN_REQ_EXCLUDED_DESC', 'A user will not see this plan if he or she is currently assigned to, or has just expired from the plan selected here');
define( '_PAYPLAN_RESTRICTIONS_OVERALLPLAN_REQ_ENABLED_EXCLUDED_NAME', 'Excluded Used Plan:');
define( '_PAYPLAN_RESTRICTIONS_OVERALLPLAN_REQ_ENABLED_EXCLUDED_DESC', 'Do NOT show this plan to users who have used the selected plan before');
define( '_PAYPLAN_RESTRICTIONS_OVERALLPLAN_REQ_EXCLUDED_NAME', 'Plan:');
define( '_PAYPLAN_RESTRICTIONS_OVERALLPLAN_REQ_EXCLUDED_DESC', 'A user will not see this plan if he or she has used the selected plan once, no matter when');

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

define( '_PAYPLAN_RESTRICTIONS_PREVIOUSGROUP_REQ_ENABLED_NAME', 'Required Prev. Group:');
define( '_PAYPLAN_RESTRICTIONS_PREVIOUSGROUP_REQ_ENABLED_DESC', 'Enable checking for previous payment plan in this group');
define( '_PAYPLAN_RESTRICTIONS_PREVIOUSGROUP_REQ_NAME', 'Group:');
define( '_PAYPLAN_RESTRICTIONS_PREVIOUSGROUP_REQ_DESC', 'A user will only see this plan if he or she used a plan in this group before the one currently in use');
define( '_PAYPLAN_RESTRICTIONS_CURRENTGROUP_REQ_ENABLED_NAME', 'Required Curr. Group:');
define( '_PAYPLAN_RESTRICTIONS_CURRENTGROUP_REQ_ENABLED_DESC', 'Enable checking for currently present payment plan in this group');
define( '_PAYPLAN_RESTRICTIONS_CURRENTGROUP_REQ_NAME', 'Group:');
define( '_PAYPLAN_RESTRICTIONS_CURRENTGROUP_REQ_DESC', 'A user will only see this plan if he or she is currently assigned to, or has just expired from a plan in this group selected here');
define( '_PAYPLAN_RESTRICTIONS_OVERALLGROUP_REQ_ENABLED_NAME', 'Required Used Group:');
define( '_PAYPLAN_RESTRICTIONS_OVERALLGROUP_REQ_ENABLED_DESC', 'Enable checking for overall used payment plan in this group');
define( '_PAYPLAN_RESTRICTIONS_OVERALLGROUP_REQ_NAME', 'Group:');
define( '_PAYPLAN_RESTRICTIONS_OVERALLGROUP_REQ_DESC', 'A user will only see this plan if he or she has used the selected plan in this group once, no matter when');

define( '_PAYPLAN_RESTRICTIONS_PREVIOUSGROUP_REQ_ENABLED_EXCLUDED_NAME', 'Excluded Prev. Group:');
define( '_PAYPLAN_RESTRICTIONS_PREVIOUSGROUP_REQ_ENABLED_EXCLUDED_DESC', 'Do NOT show this plan to users who had a plan in this group as their previous payment plan');
define( '_PAYPLAN_RESTRICTIONS_PREVIOUSGROUP_REQ_EXCLUDED_NAME', 'Group:');
define( '_PAYPLAN_RESTRICTIONS_PREVIOUSGROUP_REQ_EXCLUDED_DESC', 'A user will not see this plan if he or she used a plan in this group before the one currently in use');
define( '_PAYPLAN_RESTRICTIONS_CURRENTGROUP_REQ_ENABLED_EXCLUDED_NAME', 'Excluded Curr. Group:');
define( '_PAYPLAN_RESTRICTIONS_CURRENTGROUP_REQ_ENABLED_EXCLUDED_DESC', 'Do NOT show this plan to users who have a plan in this group as their currently present payment plan');
define( '_PAYPLAN_RESTRICTIONS_CURRENTGROUP_REQ_EXCLUDED_NAME', 'Group:');
define( '_PAYPLAN_RESTRICTIONS_CURRENTGROUP_REQ_EXCLUDED_DESC', 'A user will not see this plan if he or she is currently assigned to, or has just expired from a plan in this group');
define( '_PAYPLAN_RESTRICTIONS_OVERALLGROUP_REQ_ENABLED_EXCLUDED_NAME', 'Excluded Used Group:');
define( '_PAYPLAN_RESTRICTIONS_OVERALLGROUP_REQ_ENABLED_EXCLUDED_DESC', 'Do NOT show this plan to users who have used the a plan in this group before');
define( '_PAYPLAN_RESTRICTIONS_OVERALLGROUP_REQ_EXCLUDED_NAME', 'Group:');
define( '_PAYPLAN_RESTRICTIONS_OVERALLGROUP_REQ_EXCLUDED_DESC', 'A user will not see this plan if he or she has used a plan in this group once, no matter when');

define( '_PAYPLAN_RESTRICTIONS_USED_GROUP_MIN_ENABLED_NAME', 'Min Used Group:');
define( '_PAYPLAN_RESTRICTIONS_USED_GROUP_MIN_ENABLED_DESC', 'Enable checking for the minimum number of times your consumers have subscribed to a payment plan in this group in order to see THIS plan');
define( '_PAYPLAN_RESTRICTIONS_USED_GROUP_MIN_AMOUNT_NAME', 'Used Amount:');
define( '_PAYPLAN_RESTRICTIONS_USED_GROUP_MIN_AMOUNT_DESC', 'The minimum amount a user has to have used the a plan in this group');
define( '_PAYPLAN_RESTRICTIONS_USED_GROUP_MIN_NAME', 'Group:');
define( '_PAYPLAN_RESTRICTIONS_USED_GROUP_MIN_DESC', 'The group that the user has to have used a plan from - the specified number of times at least');
define( '_PAYPLAN_RESTRICTIONS_USED_GROUP_MAX_ENABLED_NAME', 'Max Used Group:');
define( '_PAYPLAN_RESTRICTIONS_USED_GROUP_MAX_ENABLED_DESC', 'Enable checking for the maximum number of times your users have subscribed to a payment plan in this group in order to see THIS plan');
define( '_PAYPLAN_RESTRICTIONS_USED_GROUP_MAX_AMOUNT_NAME', 'Used Amount:');
define( '_PAYPLAN_RESTRICTIONS_USED_GROUP_MAX_AMOUNT_DESC', 'The maximum amount a user can have used a plan in this group');
define( '_PAYPLAN_RESTRICTIONS_USED_GROUP_MAX_NAME', 'Group:');
define( '_PAYPLAN_RESTRICTIONS_USED_GROUP_MAX_DESC', 'The group that the user has to have used a plan from - the specified number of times at most');

define( '_PAYPLAN_RESTRICTIONS_CUSTOM_RESTRICTIONS_ENABLED_NAME', 'Use Custom Restrictions:');
define( '_PAYPLAN_RESTRICTIONS_CUSTOM_RESTRICTIONS_ENABLED_DESC', 'Enable the use of the below specified restrictions');
define( '_PAYPLAN_RESTRICTIONS_CUSTOM_RESTRICTIONS_NAME', 'Custom Restrictions:');
define( '_PAYPLAN_RESTRICTIONS_CUSTOM_RESTRICTIONS_DESC', 'Use RewriteEngine fields to check for specific strings in this form:<br />[[user_id]] >= 1500<br />[[parametername]] = value<br />(Create separate rules by entering a new line).<br />You can use =, <=, >=, <, >, <> as comparing elements. You MUST use spaces between parameters, values and comparators!');

define( '_PAYPLAN_PROCESSORS_TITLE', 'Bezahldienste');
define( '_PAYPLAN_PROCESSORS_TITLE_LONG', 'Zugewiesene Bezahldienste');

define( '_PAYPLAN_PROCESSORS_ACTIVATE_NAME', 'Aktiv');
define( '_PAYPLAN_PROCESSORS_ACTIVATE_DESC', 'Offer this Payment Processor for this Payment Plan.');
define( '_PAYPLAN_PROCESSORS_OVERWRITE_SETTINGS_NAME', 'Overwrite Global Settings');
define( '_PAYPLAN_PROCESSORS_OVERWRITE_SETTINGS_DESC', 'If you want to, you can check this box and after saving the plan edit every setting from the global configuration to be different for this plan individually.');

define( '_PAYPLAN_MI',											'Komponenten' );
define( '_PAYPLAN_GENERAL_MICRO_INTEGRATIONS_NAME',				'Komponentenname:' );
define( '_PAYPLAN_GENERAL_MICRO_INTEGRATIONS_DESC',				'Komponente(n) ausw&auml;hlen welche Benutzern mit diesem Abo zugewiesen werden sollen' );
define( '_PAYPLAN_GENERAL_MICRO_INTEGRATIONS_PLAN_NAME', 'Local Micro Integrations:');
define( '_PAYPLAN_GENERAL_MICRO_INTEGRATIONS_PLAN_DESC', 'Select the Micro Integrations that you want to apply to the user with the plan. Instead of global instances, these MIs will be specific only to this payment plan.');

define( '_PAYPLAN_CURRENCY',					'W&auml;hrung' );

// --== Group PAGE ==--

define( '_ITEMGROUPS_TITLE', 'Groups');
define( '_ITEMGROUP_NAME', 'Name');
define( '_ITEMGROUP_DESC', 'Description (first 50 chars)');
define( '_ITEMGROUP_ACTIVE', 'Published');
define( '_ITEMGROUP_VISIBLE', 'Visible');
define( '_ITEMGROUP_REORDER', 'Reorder');

define( '_PUBLISH_ITEMGROUP', 'Publish');
define( '_UNPUBLISH_ITEMGROUP', 'Unpublish');
define( '_NEW_ITEMGROUP', 'New');
define( '_COPY_ITEMGROUP', 'Copy');
define( '_APPLY_ITEMGROUP', 'Apply');
define( '_EDIT_ITEMGROUP', 'Edit');
define( '_REMOVE_ITEMGROUP', 'Delete');
define( '_SAVE_ITEMGROUP', 'Save');
define( '_CANCEL_ITEMGROUP', 'Cancel');

define( '_ITEMGROUP_DETAIL_TITLE', 'Group');
define( '_AEC_HEAD_ITEMGROUP_INFO', 'Group' );
define( '_ITEMGROUP_GENERAL_NAME_NAME', 'Name:');
define( '_ITEMGROUP_GENERAL_NAME_DESC', 'Name or title for this group. Max.: 40 characters.');
define( '_ITEMGROUP_GENERAL_DESC_NAME', 'Description:');
define( '_ITEMGROUP_GENERAL_DESC_DESC', 'Full description of group. Max.: 255 characters.');
define( '_ITEMGROUP_GENERAL_ACTIVE_NAME', 'Published:');
define( '_ITEMGROUP_GENERAL_ACTIVE_DESC', 'A published group will be available to the user on frontend.');
define( '_ITEMGROUP_GENERAL_VISIBLE_NAME', 'Visible:');
define( '_ITEMGROUP_GENERAL_VISIBLE_DESC', 'Visible Groups will show on the frontend.');
define( '_ITEMGROUP_GENERAL_COLOR_NAME', 'Color:');
define( '_ITEMGROUP_GENERAL_COLOR_DESC', 'The color marking of this group.');
define( '_ITEMGROUP_GENERAL_ICON_NAME', 'Icon:');
define( '_ITEMGROUP_GENERAL_ICON_DESC', 'The icon marking of this group.');

define( '_ITEMGROUP_GENERAL_REVEAL_CHILD_ITEMS_NAME', 'Reveal Child Items');
define( '_ITEMGROUP_GENERAL_REVEAL_CHILD_ITEMS_DESC', 'If you set this switch to "yes", the AEC will not show a group button (linking the user on to this contents of the group), but directly display the contents of this group in any parent group.');
define( '_ITEMGROUP_GENERAL_SYMLINK_NAME', 'Group Symlink');
define( '_ITEMGROUP_GENERAL_SYMLINK_DESC', 'Entering a link here will redirect a user to this link when selecting this group in the plans selection page. Overrides any linking to contents of this group!');

define( '_ITEMGROUP_GENERAL_NOTAUTH_REDIRECT_NAME', 'Denied Access Redirect');
define( '_ITEMGROUP_GENERAL_NOTAUTH_REDIRECT_DESC', 'Redirect to a different URL should the user follow a direct link to this item without having the right authorization.');

// Group Restrictions

define( '_ITEMGROUP_RESTRICTIONS_TITLE', 'Restrictions');

define( '_ITEMGROUP_RESTRICTIONS_MINGID_ENABLED_NAME', 'Enable Min GID:');
define( '_ITEMGROUP_RESTRICTIONS_MINGID_ENABLED_DESC', 'Enable this setting if you want to restrict whether a user is shown this group by a minimum usergroup.');
define( '_ITEMGROUP_RESTRICTIONS_MINGID_NAME', 'Visibility Group:');
define( '_ITEMGROUP_RESTRICTIONS_MINGID_DESC', 'The minimum user level required to see this group when building the payment plans page. New users will always see the group with the lowest gid available.');
define( '_ITEMGROUP_RESTRICTIONS_FIXGID_ENABLED_NAME', 'Enable Fixed GID:');
define( '_ITEMGROUP_RESTRICTIONS_FIXGID_ENABLED_DESC', 'Enable this setting if you want to restrict this group to one usergroup.');
define( '_ITEMGROUP_RESTRICTIONS_FIXGID_NAME', 'Set Group:');
define( '_ITEMGROUP_RESTRICTIONS_FIXGID_DESC', 'Only users with this usergroup can view this group.');
define( '_ITEMGROUP_RESTRICTIONS_MAXGID_ENABLED_NAME', 'Enable Max GID:');
define( '_ITEMGROUP_RESTRICTIONS_MAXGID_ENABLED_DESC', 'Enable this setting if you want to restrict whether a user is shown this grroup by a maximum usergroup.');
define( '_ITEMGROUP_RESTRICTIONS_MAXGID_NAME', 'Maximum Group:');
define( '_ITEMGROUP_RESTRICTIONS_MAXGID_DESC', 'The maximum user level a user can have to see this group when building the payment plans page.');

define( '_ITEMGROUP_RESTRICTIONS_PREVIOUSPLAN_REQ_ENABLED_NAME', 'Required Prev. Plan:');
define( '_ITEMGROUP_RESTRICTIONS_PREVIOUSPLAN_REQ_ENABLED_DESC', 'Enable checking for previous payment plan');
define( '_ITEMGROUP_RESTRICTIONS_PREVIOUSPLAN_REQ_NAME', 'Plan:');
define( '_ITEMGROUP_RESTRICTIONS_PREVIOUSPLAN_REQ_DESC', 'A user will only see this group if he or she used the selected plan before the one currently in use');
define( '_ITEMGROUP_RESTRICTIONS_CURRENTPLAN_REQ_ENABLED_NAME', 'Required Curr. Plan:');
define( '_ITEMGROUP_RESTRICTIONS_CURRENTPLAN_REQ_ENABLED_DESC', 'Enable checking for currently present payment plan');
define( '_ITEMGROUP_RESTRICTIONS_CURRENTPLAN_REQ_NAME', 'Plan:');
define( '_ITEMGROUP_RESTRICTIONS_CURRENTPLAN_REQ_DESC', 'A user will only see this group if he or she is currently assigned to, or has just expired from the plan selected here');
define( '_ITEMGROUP_RESTRICTIONS_OVERALLPLAN_REQ_ENABLED_NAME', 'Required Used Plan:');
define( '_ITEMGROUP_RESTRICTIONS_OVERALLPLAN_REQ_ENABLED_DESC', 'Enable checking for overall used payment plan');
define( '_ITEMGROUP_RESTRICTIONS_OVERALLPLAN_REQ_NAME', 'Plan:');
define( '_ITEMGROUP_RESTRICTIONS_OVERALLPLAN_REQ_DESC', 'A user will only see this plan if he or she has used the selected plan once, no matter when');

define( '_ITEMGROUP_RESTRICTIONS_PREVIOUSPLAN_REQ_ENABLED_EXCLUDED_NAME', 'Excluded Prev. Plan:');
define( '_ITEMGROUP_RESTRICTIONS_PREVIOUSPLAN_REQ_ENABLED_EXCLUDED_DESC', 'Do NOT show this group to users who had the selected plan as their previous payment plan');
define( '_ITEMGROUP_RESTRICTIONS_PREVIOUSPLAN_REQ_EXCLUDED_NAME', 'Plan:');
define( '_ITEMGROUP_RESTRICTIONS_PREVIOUSPLAN_REQ_EXCLUDED_DESC', 'A user will not see this group if he or she used the selected plan before the one currently in use');
define( '_ITEMGROUP_RESTRICTIONS_CURRENTPLAN_REQ_ENABLED_EXCLUDED_NAME', 'Excluded Curr. Plan:');
define( '_ITEMGROUP_RESTRICTIONS_CURRENTPLAN_REQ_ENABLED_EXCLUDED_DESC', 'Do NOT show this group to users who have the selected plan as their currently present payment plan');
define( '_ITEMGROUP_RESTRICTIONS_CURRENTPLAN_REQ_EXCLUDED_NAME', 'Plan:');
define( '_ITEMGROUP_RESTRICTIONS_CURRENTPLAN_REQ_EXCLUDED_DESC', 'A user will not see this group if he or she is currently assigned to, or has just expired from the plan selected here');
define( '_ITEMGROUP_RESTRICTIONS_OVERALLPLAN_REQ_ENABLED_EXCLUDED_NAME', 'Excluded Used Plan:');
define( '_ITEMGROUP_RESTRICTIONS_OVERALLPLAN_REQ_ENABLED_EXCLUDED_DESC', 'Do NOT show this group to users who have used the selected plan before');
define( '_ITEMGROUP_RESTRICTIONS_OVERALLPLAN_REQ_EXCLUDED_NAME', 'Plan:');
define( '_ITEMGROUP_RESTRICTIONS_OVERALLPLAN_REQ_EXCLUDED_DESC', 'A user will not see this group if he or she has used the selected plan once, no matter when');

define( '_ITEMGROUP_RESTRICTIONS_USED_PLAN_MIN_ENABLED_NAME', 'Min Used Plan:');
define( '_ITEMGROUP_RESTRICTIONS_USED_PLAN_MIN_ENABLED_DESC', 'Enable checking for the minimum number of times your consumers have subscribed to a specified payment plan in order to see this group');
define( '_ITEMGROUP_RESTRICTIONS_USED_PLAN_MIN_AMOUNT_NAME', 'Used Amount:');
define( '_ITEMGROUP_RESTRICTIONS_USED_PLAN_MIN_AMOUNT_DESC', 'The minimum amount a user has to have used the selected plan');
define( '_ITEMGROUP_RESTRICTIONS_USED_PLAN_MIN_NAME', 'Plan:');
define( '_ITEMGROUP_RESTRICTIONS_USED_PLAN_MIN_DESC', 'The payment plan that the user has to have used the specified number of times at least');
define( '_ITEMGROUP_RESTRICTIONS_USED_PLAN_MAX_ENABLED_NAME', 'Max Used Plan:');
define( '_ITEMGROUP_RESTRICTIONS_USED_PLAN_MAX_ENABLED_DESC', 'Enable checking for the maximum number of times your consumers have subscribed to a specified payment plan in order to see this group');
define( '_ITEMGROUP_RESTRICTIONS_USED_PLAN_MAX_AMOUNT_NAME', 'Used Amount:');
define( '_ITEMGROUP_RESTRICTIONS_USED_PLAN_MAX_AMOUNT_DESC', 'The maximum amount a user can have used the selected plan');
define( '_ITEMGROUP_RESTRICTIONS_USED_PLAN_MAX_NAME', 'Plan:');
define( '_ITEMGROUP_RESTRICTIONS_USED_PLAN_MAX_DESC', 'The payment plan that the user has to have used the specified number of times at most');

define( '_ITEMGROUP_RESTRICTIONS_PREVIOUSGROUP_REQ_ENABLED_NAME', 'Required Prev. Group:');
define( '_ITEMGROUP_RESTRICTIONS_PREVIOUSGROUP_REQ_ENABLED_DESC', 'Enable checking for a previous payment plan in this group');
define( '_ITEMGROUP_RESTRICTIONS_PREVIOUSGROUP_REQ_NAME', 'Group:');
define( '_ITEMGROUP_RESTRICTIONS_PREVIOUSGROUP_REQ_DESC', 'A user will only see this group if he or she used a plan in the selected group before the plan currently in use');
define( '_ITEMGROUP_RESTRICTIONS_CURRENTGROUP_REQ_ENABLED_NAME', 'Required Curr. Group:');
define( '_ITEMGROUP_RESTRICTIONS_CURRENTGROUP_REQ_ENABLED_DESC', 'Enable checking for currently present payment plan in this group');
define( '_ITEMGROUP_RESTRICTIONS_CURRENTGROUP_REQ_NAME', 'Group:');
define( '_ITEMGROUP_RESTRICTIONS_CURRENTGROUP_REQ_DESC', 'A user will only see this group if he or she is currently assigned to, or has just expired from a plan in the group selected here');
define( '_ITEMGROUP_RESTRICTIONS_OVERALLGROUP_REQ_ENABLED_NAME', 'Required Used Group:');
define( '_ITEMGROUP_RESTRICTIONS_OVERALLGROUP_REQ_ENABLED_DESC', 'Enable checking for overall used payment plan in this group');
define( '_ITEMGROUP_RESTRICTIONS_OVERALLGROUP_REQ_NAME', 'Group:');
define( '_ITEMGROUP_RESTRICTIONS_OVERALLGROUP_REQ_DESC', 'A user will only see this group if he or she has used a plan in the selected group once, no matter when');

define( '_ITEMGROUP_RESTRICTIONS_PREVIOUSGROUP_REQ_ENABLED_EXCLUDED_NAME', 'Excluded Prev. Group:');
define( '_ITEMGROUP_RESTRICTIONS_PREVIOUSGROUP_REQ_ENABLED_EXCLUDED_DESC', 'Do NOT show this group to users who had a plan in the selected group as their previous payment plan');
define( '_ITEMGROUP_RESTRICTIONS_PREVIOUSGROUP_REQ_EXCLUDED_NAME', 'Group:');
define( '_ITEMGROUP_RESTRICTIONS_PREVIOUSGROUP_REQ_EXCLUDED_DESC', 'A user will not see this group if he or she used a plan in the selected group before the one currently in use');
define( '_ITEMGROUP_RESTRICTIONS_CURRENTGROUP_REQ_ENABLED_EXCLUDED_NAME', 'Excluded Curr. Group:');
define( '_ITEMGROUP_RESTRICTIONS_CURRENTGROUP_REQ_ENABLED_EXCLUDED_DESC', 'Do NOT show this group to users who have a plan in the selected group as their currently present payment plan');
define( '_ITEMGROUP_RESTRICTIONS_CURRENTGROUP_REQ_EXCLUDED_NAME', 'Group:');
define( '_ITEMGROUP_RESTRICTIONS_CURRENTGROUP_REQ_EXCLUDED_DESC', 'A user will not see this group if he or she is currently assigned to, or has just expired from a plan in the selected group');
define( '_ITEMGROUP_RESTRICTIONS_OVERALLGROUP_REQ_ENABLED_EXCLUDED_NAME', 'Excluded Used Group:');
define( '_ITEMGROUP_RESTRICTIONS_OVERALLGROUP_REQ_ENABLED_EXCLUDED_DESC', 'Do NOT show this group to users who have used the a plan in the selected group before');
define( '_ITEMGROUP_RESTRICTIONS_OVERALLGROUP_REQ_EXCLUDED_NAME', 'Group:');
define( '_ITEMGROUP_RESTRICTIONS_OVERALLGROUP_REQ_EXCLUDED_DESC', 'A user will not see this group if he or she has used a plan in the selected group once, no matter when');

define( '_ITEMGROUP_RESTRICTIONS_USED_GROUP_MIN_ENABLED_NAME', 'Min Used Group:');
define( '_ITEMGROUP_RESTRICTIONS_USED_GROUP_MIN_ENABLED_DESC', 'Enable checking for the minimum number of times your users have subscribed to a payment plan in the selected group in order to see THIS group');
define( '_ITEMGROUP_RESTRICTIONS_USED_GROUP_MIN_AMOUNT_NAME', 'Used Amount:');
define( '_ITEMGROUP_RESTRICTIONS_USED_GROUP_MIN_AMOUNT_DESC', 'The minimum amount a user has to have used the a plan in the selected group');
define( '_ITEMGROUP_RESTRICTIONS_USED_GROUP_MIN_NAME', 'Group:');
define( '_ITEMGROUP_RESTRICTIONS_USED_GROUP_MIN_DESC', 'The group that the user has to have used a plan from - the specified number of times at least');
define( '_ITEMGROUP_RESTRICTIONS_USED_GROUP_MAX_ENABLED_NAME', 'Max Used Group:');
define( '_ITEMGROUP_RESTRICTIONS_USED_GROUP_MAX_ENABLED_DESC', 'Enable checking for the maximum number of times your users may have subscribed to a payment plan in the selected group in order to see THIS group');
define( '_ITEMGROUP_RESTRICTIONS_USED_GROUP_MAX_AMOUNT_NAME', 'Used Amount:');
define( '_ITEMGROUP_RESTRICTIONS_USED_GROUP_MAX_AMOUNT_DESC', 'The maximum amount a user can have used a plan in the selected group');
define( '_ITEMGROUP_RESTRICTIONS_USED_GROUP_MAX_NAME', 'Group:');
define( '_ITEMGROUP_RESTRICTIONS_USED_GROUP_MAX_DESC', 'The group that the user has to have used a plan from - the specified number of times at most');

define( '_ITEMGROUP_RESTRICTIONS_CUSTOM_RESTRICTIONS_ENABLED_NAME', 'Use Custom Restrictions:');
define( '_ITEMGROUP_RESTRICTIONS_CUSTOM_RESTRICTIONS_ENABLED_DESC', 'Enable the use of the below specified restrictions');
define( '_ITEMGROUP_RESTRICTIONS_CUSTOM_RESTRICTIONS_NAME', 'Custom Restrictions:');
define( '_ITEMGROUP_RESTRICTIONS_CUSTOM_RESTRICTIONS_DESC', 'Use RewriteEngine fields to check for specific strings in this form:<br />[[user_id]] >= 1500<br />[[parametername]] = value<br />(Create separate rules by entering a new line).<br />You can use =, <=, >=, <, >, <> as comparing elements. You MUST use spaces between parameters, values and comparators!');

// Group Relations

define( '_ITEMGROUP_RELATIONS_TITLE', 'Relations');
define( '_ITEMGROUP_PARAMS_SIMILARITEMGROUPS_NAME', 'Similar Groups:');
define( '_ITEMGROUP_PARAMS_SIMILARITEMGROUPS_DESC', 'Select which groups are similar to this one. A user is not allowed to use a Trial period when buying a plan that he or she has purchased before and this will also be the same for similar plans (or plans in similar groups).');
define( '_ITEMGROUP_PARAMS_EQUALITEMGROUPS_NAME', 'Equal Groups:');
define( '_ITEMGROUP_PARAMS_EQUALITEMGROUPS_DESC', 'Select which groups are equal to this one. A user switching between equal plans (or plans in equal groups) will have his or her period extended instead of reset. Trials are also not permitted (see similar groups info).');

// Currencies

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
define( '_CURRENCY_RSD', 'Serbian dinar');
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
define( '_MI_E_TITLE_LONG',					'Micro Integration' );
define( '_MI_E_SETTINGS',					'Einstellungen' );
define( '_MI_E_NAME_NAME',					'Name' );
define( '_MI_E_NAME_DESC',					'Name f&uuml;r diese MicroIntegration' );
define( '_MI_E_DESC_NAME',					'Beschreibung' );
define( '_MI_E_DESC_DESC',					'Kurzbeschreibung' );
define( '_MI_E_ACTIVE_NAME',				'Aktiv' );
define( '_MI_E_ACTIVE_DESC',				'Als Aktiv markieren' );
define( '_MI_E_AUTO_CHECK_NAME',			'Aktion bei Ablauf' );
define( '_MI_E_AUTO_CHECK_DESC',			'Falls diese Komponente es erlaubt k&ouml;nnen Aktionen definiert werden wenn ein Abo ausl&auml;ft' );
define( '_MI_E_ON_USERCHANGE_NAME',		'Benutzeraktion' );
define( '_MI_E_ON_USERCHANGE_DESC',		'Falls von der Komponente unterst&uuml;tzt k&ouml;nnen Aktionen definiert werden wenn ein Benutzerabo ausl&auml;ft' );
define( '_MI_E_PRE_EXP_CHECK_NAME',				'Tage vor Ablauf' );
define( '_MI_E_PRE_EXP_CHECK_DESC',				'Anzahl der Tage vor dem Ablauf ab wann die Aktionen gelten sollen' );
define( '_MI_E__AEC_GLOBAL_EXP_ALL_NAME', 'Expire all instances');
define( '_MI_E__AEC_GLOBAL_EXP_ALL_DESC', 'Trigger the expiration action even if the user has another payment plan with this MI attached. The standard behavior is to call the expiration action on an MI only when it really is the last MI instance that this user has in all payment plans.');
define( '_MI_E_FUNCTION_NAME',				'Funktionsname' );
define( '_MI_E_FUNCTION_DESC',				'Welche der Systeme sollen verwendet weren' );
define( '_MI_E_FUNCTION_EXPLANATION',		'Bevor die MicroIntegration definiert wird, muss bestimmt werden welche der MicroIntegrationen g&uuml;ltig/aktiv sind. Wahl treffen und speichern. Dann nochmals bearbeiten, die Einstellungen sind erst dann sichtbar. HINWEIS: einmal definiert lassen sich die Einstellungen nicht r&uuml;ckg&auml;ngig machen' );

// --== REWRITE EXPLANATION ==--
define( '_REWRITE_AREA_USER',				'Benutzeraccount Bezogen' );
define( '_REWRITE_KEY_USER_ID',				'Benutzer ID' );
define( '_REWRITE_KEY_USER_USERNAME',		'Benutzername' );
define( '_REWRITE_KEY_USER_NAME',			'Name' );
define( '_REWRITE_KEY_USER_FIRST_NAME', 'Vorname');
define( '_REWRITE_KEY_USER_FIRST_FIRST_NAME', 'Erster Vorname');
define( '_REWRITE_KEY_USER_LAST_NAME', 'Nachname');
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
define( '_REWRITE_KEY_SUBSCRIPTION_EXPIRATION_DATE', 'Ablaufdatum (Frontend Formatting)');
define( '_REWRITE_KEY_SUBSCRIPTION_EXPIRATION_DATE_BACKEND', 'Ablaufdatum (Backend Formatting)');

define( '_REWRITE_AREA_PLAN', 				'Abo Bezogen' );
define( '_REWRITE_KEY_PLAN_NAME',			'Name' );
define( '_REWRITE_KEY_PLAN_DESC',			'Beschreibung' );

define( '_REWRITE_AREA_CMS',				'CMS Bezogen' );
define( '_REWRITE_KEY_CMS_ABSOLUTE_PATH',	'Absoluter Pfad zum CMS-Hauptverzeichnis (z.B. ../www/html/...' );
define( '_REWRITE_KEY_CMS_LIVE_SITE',		'Relativer Pfad zur Webseite (z.B. http://www.meineseite.com)' );

define( '_REWRITE_AREA_SYSTEM', 'System Related');
define( '_REWRITE_KEY_SYSTEM_TIMESTAMP', 'Zeitstempel (Frontend Formatting)');
define( '_REWRITE_KEY_SYSTEM_TIMESTAMP_BACKEND', 'Zeitstempel (Backend Formatting)');
define( '_REWRITE_KEY_SYSTEM_SERVER_TIMESTAMP', 'Server Zeitstempel (Frontend Formatting)');
define( '_REWRITE_KEY_SYSTEM_SERVER_TIMESTAMP_BACKEND', 'Server Zeitstempel (Backend Formatting)');

define( '_REWRITE_AREA_INVOICE', 'Rechnungs Bezogen');
define( '_REWRITE_KEY_INVOICE_ID', 'Rechnungs ID');
define( '_REWRITE_KEY_INVOICE_NUMBER', 'Rechnungs Nummer');
define( '_REWRITE_KEY_INVOICE_NUMBER_FORMAT', 'Rechnungs Nummer (formattierd)');
define( '_REWRITE_KEY_INVOICE_CREATED_DATE', 'Datum der Erstellung');
define( '_REWRITE_KEY_INVOICE_TRANSACTION_DATE', 'Datum der Bezahlung');
define( '_REWRITE_KEY_INVOICE_METHOD', 'Bezahlungsmethode');
define( '_REWRITE_KEY_INVOICE_AMOUNT', 'Betrag');
define( '_REWRITE_KEY_INVOICE_CURRENCY', 'W&auml;hrung');
define( '_REWRITE_KEY_INVOICE_COUPONS', 'Coupon Liste');

define( '_REWRITE_ENGINE_TITLE', 'Rewrite Engine');
define( '_REWRITE_ENGINE_DESC', 'To create dynamic text, you can add these wiki-style tags in RWengine-enabled fields. Flick through the togglers below to see which tags are available');
define( '_REWRITE_ENGINE_AECJSON_TITLE', 'aecJSON');
define( '_REWRITE_ENGINE_AECJSON_DESC', 'You can also use functions encoded in JSON markup, like this:<br />{aecjson} { "cmd":"date", "vars": [ "Y", { "cmd":"rw_constant", "vars":"invoice_created_date" } ] } {/aecjson}<br />It returns only the Year of a date. Refer to the manual and forums for further instructions!');

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
define( '_INVOICE_SECONDARY_IDENT', 'Secondary Identification');
define( '_INVOICE_TRANSACTION_DATE',		'Durchf&uuml;hrungsdatum' );
define( '_INVOICE_METHOD',					'Art' );
define( '_INVOICE_AMOUNT',					'Betrag' );
define( '_INVOICE_CURRENCY',				'W&auml;hrung' );
define( '_INVOICE_COUPONS', 'Coupons');

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

// --== EXPORT ==--
define( '_EXPORT', 'Export');
define( '_EXPORT_LOAD', 'Load');
define( '_EXPORT_APPLY', 'Apply');
define( '_EXPORT_GENERAL_SELECTED_EXPORT_NAME', 'Export Preset');
define( '_EXPORT_GENERAL_SELECTED_EXPORT_DESC', 'Select a preset (or an automatically saved previous export) instead of making the selections below. You can also click apply on the upper right and preview the preset.');
define( '_EXPORT_GENERAL_DELETE_NAME', 'Delete');
define( '_EXPORT_GENERAL_DELETE_DESC', 'Delete this Preset (on apply)');
define( '_EXPORT_PARAMS_PLANID_NAME', 'Payment Plan');
define( '_EXPORT_PARAMS_PLANID_DESC', 'Filter out subscriptions with this Payment Plan');
define( '_EXPORT_PARAMS_STATUS_NAME', 'Status');
define( '_EXPORT_PARAMS_STATUS_DESC', 'Only export subscriptions with this status');
define( '_EXPORT_PARAMS_ORDERBY_NAME', 'Order by');
define( '_EXPORT_PARAMS_ORDERBY_DESC', 'Order by one of the following');
define( '_EXPORT_PARAMS_REWRITE_RULE_NAME', 'Fields');
define( '_EXPORT_PARAMS_REWRITE_RULE_DESC', 'Put in the ReWrite Engine fields, separated by semicolons, that you want each exported item to hold.');
define( '_EXPORT_PARAMS_SAVE_NAME', 'Save as New?');
define( '_EXPORT_PARAMS_SAVE_DESC', 'Check this box to save your settings as a new preset');
define( '_EXPORT_PARAMS_SAVE_NAME_NAME', 'Save Name');
define( '_EXPORT_PARAMS_SAVE_NAME_DESC', 'Save new preset under this name');
define( '_EXPORT_PARAMS_EXPORT_METHOD_NAME', 'Exporting Method');
define( '_EXPORT_PARAMS_EXPORT_METHOD_DESC', 'The filetype you want to export to');

// --== READOUT ==--
define( '_AEC_READOUT', 'AEC Readout');
define( '_READOUT_GENERAL_SHOW_SETTINGS_NAME', 'Settings');
define( '_READOUT_GENERAL_SHOW_SETTINGS_DESC', 'Display AEC System Settings on the Readout');
define( '_READOUT_GENERAL_SHOW_EXTSETTINGS_NAME', 'Extended Settings');
define( '_READOUT_GENERAL_SHOW_EXTSETTINGS_DESC', 'Display extended AEC System Settings on the Readout');
define( '_READOUT_GENERAL_SHOW_PROCESSORS_NAME', 'Processor Settings');
define( '_READOUT_GENERAL_SHOW_PROCESSORS_DESC', 'Display Processor Settings on the Readout');
define( '_READOUT_GENERAL_SHOW_PLANS_NAME', 'Plans');
define( '_READOUT_GENERAL_SHOW_PLANS_DESC', 'Display Plans on the Readout');
define( '_READOUT_GENERAL_SHOW_MI_RELATIONS_NAME', 'Plan -> MI Relations');
define( '_READOUT_GENERAL_SHOW_MI_RELATIONS_DESC', 'Display Plan -> MI Relations on the Readout');
define( '_READOUT_GENERAL_SHOW_MIS_NAME', 'Micro Integrations');
define( '_READOUT_GENERAL_SHOW_MIS_DESC', 'Display Micro Integrations and their Settings on the Readout');
define( '_READOUT_GENERAL_STORE_SETTINGS_NAME', 'Remember Settings');
define( '_READOUT_GENERAL_STORE_SETTINGS_DESC', 'Remember Settings on this page for your admin account');
define( '_READOUT_GENERAL_TRUNCATION_LENGTH_NAME', 'Truncation Length');
define( '_READOUT_GENERAL_TRUNCATION_LENGTH_DESC', 'Reduce content of fields to this length where appropriate');
define( '_READOUT_GENERAL_USE_ORDERING_NAME', 'Use Ordering');
define( '_READOUT_GENERAL_USE_ORDERING_DESC', 'Instead of showing entries by their database order, show them by their set ordering - if applicable');
define( '_READOUT_GENERAL_COLUMN_HEADERS_NAME', 'Column Headers');
define( '_READOUT_GENERAL_COLUMN_HEADERS_DESC', 'Show Column Headers every X rows');
define( '_READOUT_GENERAL_NOFORMAT_NEWLINES_NAME', 'Format: no linebreaks');
define( '_READOUT_GENERAL_NOFORMAT_NEWLINES_DESC', 'Multiple entries for a table cell are normally displayed in separate lines, with this option, these entries are just displayed in a single block of text.');
define( '_READOUT_GENERAL_EXPORT_CSV_NAME', 'Export as .csv');
define( '_READOUT_GENERAL_EXPORT_CSV_DESC', 'Export data as a comma separated file that can be loaded in a spreadsheet application.');

?>
