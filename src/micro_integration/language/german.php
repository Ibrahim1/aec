<?php
/**
 * @version $Id: german.php 16 2007-07-01 21:00:00Z mic $
 * @package AEC - Account Control Expiration - Subscription component for Joomla! OS CMS
 * @subpackage Language - MicroIntegrations - German Formal
 * @copyright Copyright (C) 2004-2007, All Rights Reserved, Helder Garcia, David Deutsch
 * @author Team AEC - http://www.gobalnerd.org
 * @license GNU/GPL v.2 http://www.gnu.org/copyleft/gpl.html
 */

// Dont allow direct linking
defined( '_VALID_MOS' ) or die( 'Not really ....' );

if( defined( '_AEC_LANG_MI_INCLUDED' ) ) {
	return;
}else{
	define( '_AEC_LANG_MI_INCLUDED', true );
}

// acajoom
define( '_AEC_MI_NAME_ACAJOOM',		'Acajoom' );
define( '_AEC_MI_DESC_ACAJOOM',		'Binded den Newsletter Acajoom ein (freie Version)' );

// htaccess
define( '_AEC_MI_NAME_HTACCESS',	'.htaccess' );
define( '_AEC_MI_DESC_HTACCESS',	'Sch&uuml;tzt einen Ordner mit einer .htaccess Datei und erlaubt nur berechtigten Abonnenten Zugriff darauf' );
define( '_AEC_MI_SET1_HTACCESS',	'Ordner' );
define( '_AEC_MI_SET1_1_HTACCESS',	'Der zu sch&uuml;tzende Ordner. Folgende Schl&uuml;sselw&ouml;rter werden ersetzt<br />[cmsstammordner] -> %s<br />Hinweis: keine abschie&szlig;ender Slash - der Ordnername darf ebenso keinen haben!' );
define( '_AEC_MI_SET2_HTACCESS',	'Passwortordner' );
define( '_AEC_MI_SET2_1_HTACCESS',	'Datei f&uuml;r die Passw&oumlrter. Sollte <strong>nicht</strong> innerhalb des vom Web zug&auml;glichen CMS gespeichert werden!' );
define( '_AEC_MI_SET3_HTACCESS',	'Bereichsname' );
define( '_AEC_MI_SET3_1_HTACCESS',	'Name des gesch&uuml;tzten Bereiches' );
define( '_AEC_MI_SET4_HTACCESS',	'md5 verwenden' );
define( '_AEC_MI_SET4_1_HTACCESS',	'<strong>Wichtig!</strong> Wenn diese Integration verwendet werden soll, um Ordner auf einem Apacheserver zu sch&uuml;tzen, muss "crypt" verwendet werden. In so einem Fall hier auf "Nein" einstellen.<br />Wird jedoch eine andere Software/anderer Server (wie z.B. ein icecast Server), dann hier auf "Ja" stellen, es wird dann die Standard md5 Verschl&uuml;sselung verwendet.' );
define( '_AEC_MI_SET5_HTACCESS',	'Wiederherstellung' );
define( '_AEC_MI_SET5_1_HTACCESS',	'Sollte die htaccess-Datei ge&auml;ndert oder diese gel&ouml;scht werden, stellt diese Einstellung sicher, da&szlig; die gesamte .htaccess Wiederhergestellt wird' );

//affiliate PRO
define( '_AEC_MI_NAME_AFFPRO',		'AffiliatePRO' );
define( '_AEC_MI_DESC_AFFPRO',		'Verbindet AEC-Zahlungen mit AffilitePRO' );
define( '_AEC_MI_SET1_AFFPRO',		'Weitere Infos' );
define( '_AEC_MI_SET1_1_AFFPRO',	'Weitere Informationen welche an AffiliatePRO gesendet werden' );
define( '_AEC_MI_SET2_AFFPRO',		'AffiliatePRO URL' );
define( '_AEC_MI_SET2_1_AFFPRO',	'Hier die AffiliatePRO URL (ohne http://) angeben' );
define( '_AEC_MI_SET3_AFFPRO',		'AffiliatePRO ID' );
define( '_AEC_MI_SET3_1_AFFPRO',	'Hier die AffiliatePRO ID angeben welche f&uuml;r die Provisionierung verwendet wird' );

// docman
define( '_AEC_MI_NAME_DOCMAN',		'DocMan' );
define( '_AEC_MI_DESC_DOCMAN',		'Anzahl der m&ouml;glichen Dateien sowie die DocMan-Gruppe w&auml;hlen zu welcher dieser Benutzer z&auml;hlen soll');
define( '_AEC_MI_SET1_DOCMAN',		'Anzahl der Anzeigen welche zu diesem Aufruf angezeigt werden' );
define( '_AEC_MI_SET2_DOCMAN',		'Anzahl der maximalen Anzeigen zu diesem Aufruf' );
define( '_AEC_MI_SET3_DOCMAN',		'Verwende DM-Gruppe' );
define( '_AEC_MI_SET3_1_DOCMAN',	'Auf "Ja" setzen wenn die DocMan-Benutzergruppe f&uuml;r diese Integration verwendet werden soll' );
define( '_AEC_MI_SET4_DOCMAN',		'DM-Gruppe' );
define( '_AEC_MI_SET4_1_DOCMAN',	'Die DocMan-Gruppe welcher diese Benutzer angeh&ouml;ren soll' );
define( '_AEC_MI_SET5_DOCMAN',		'DM-Gruppe bei Ablauf' );
define( '_AEC_MI_SET5_1_DOCMAN',	'Auf "Ja" setzen, wenn die DocMan-Gruppe nach Abonnementablauf verwendet werden soll' );
define( '_AEC_MI_SET6_DOCMAN',		'DM-Gruppe definieren' );
define( '_AEC_MI_SET6_1_DOCMAN',	'Diejenige DocMan-Gruppe definieren welche nach Aboablauf g&uuml;ltig sein soll' );
define( '_AEC_MI_HACK1_DOCMAN',		'Erstellt eine Downloadeinschr&auml;nkung f&uuml;r DocMan' );

// email
define( '_AEC_MI_NAME_EMAIL',		'Email' );
define( '_AEC_MI_DESC_EMAIL',		'Sendet ein Emial an eine oder mehrere Adressen bei Abschluss oder Beendigung eines Abonnements' );
define( '_AEC_MI_SET1_EMAIL',		'Absenderemail' );
define( '_AEC_MI_SET1_1_EMAIL',		'Emailadresse des Absenders' );
define( '_AEC_MI_SET2_EMAIL',		'Absendername' );
define( '_AEC_MI_SET2_1_EMAIL',		'Anzuzeigender Name des Absenders' );
define( '_AEC_MI_SET3_EMAIL',		'Empf&auml;nger' );
define( '_AEC_MI_SET3_1_EMAIL',		'Wer soll dieses Email empfangen? Mehrere Empf&auml;nger mit Komma trennen!' );
define( '_AEC_MI_SET4_EMAIL',		'Betreff' );
define( '_AEC_MI_SET4_1_EMAIL',		'Betreff bei Neuerwerbung/Kauf eines Abos' );
define( '_AEC_MI_SET5_EMAIL',		'HTML Format' );
define( '_AEC_MI_SET5_1_EMAIL',		'Soll dieses Email im HTML-Format gesendet werden? (Achtung: dann d&uuml;rfen keine TAGS enthalten sein falls das nicht gew&uuml;nscht ist)' );
define( '_AEC_MI_SET6_EMAIL',		'Text' );
define( '_AEC_MI_SET6_1_EMAIL',		'Text des Emails wenn ein ABO erworben wird (siehe weitere Felder unten)' );
define( '_AEC_MI_SET7_EMAIL',		'Betreff' );
define( '_AEC_MI_SET7_1_EMAIL',		'Text bei Ablauf eines Abos' );
define( '_AEC_MI_SET8_EMAIL',		'Ablauftext');
define( '_AEC_MI_SET_8_1_EMAIL',	'Text des Emails wenn das Abo abl&auml;ft (siehe weitere Felder unten)' );
define( '_AEC_MI_SET9_EMAIL',		'Betreff' );
define( '_AEC_MI_SET9_1_EMAIL',		'Text bevor das Abo abl&auml;ft' );
define( '_AEC_MI_SET10_EMAIL',		'Text vor Ablauf' );
define( '_AEC_MI_SET10_1_EMAIL',	'Text welcher gesendet wird bevor das Abo abl&auml;ft (siehe weitere Felder unten)' );
define( '_AEC_MI_SET11_EMAIL',		'Weitere Infos' );

// iDevAffiliate
define( '_AEC_MI_NAME_IDEV',		'iDevAffiliate' );
define( '_AEC_MI_DESC_IDEV',		'Zahlungen mit iDevAffiliate verbinden' );
define( '_AEC_MI_SET1_IDEV',		'Bestell ID' );
define( '_AEC_MI_SET1_1_IDEV',		'Die Bestellnummer.<br />Automatische Erg&auml;zungen:<br />\'[invoice]\' = Rechnungsnummer<br />\'[planid]\' = BenutzerID' );
define( '_AEC_MI_SET2_IDEV',		'Zwischensumme' );
define( '_AEC_MI_SET2_1_IDEV',		'Bisherige Summe dieser Bestellung. Das Feld \'[auto]\' angeben um es dem Rechnngsbetrag zu zuweisen' );
define( '_AEC_MI_DIV1_IDEV',		'Zugriff erfolgt' );
define( '_AEC_MI_DIV2_IDEV',		'Schluessel ist' );
define( '_AEC_MI_DIV3_IDEV',		'und Wert =' );

// MosetsTree
define( '_AEC_MI_NAME_MOSETS',		'MosetsTree' );
define( '_AEC_MI_DESC_MOSETS',		'Anzahl der maximalen Links ein Abonnent ver&ouml;ffentlichen darf' );
define( '_AEC_MI_SET1_MOSETS',		'Anzahl der Links' );
define( '_AEC_MI_SET1_1_MOSETS',	'Anzahl der maximalen Links f&uuml;r diesen Aufruf' );
define( '_AEC_MI_HACK1_MOSETS',		'Keine weiteren Links m&ouml;glich' );
define( '_AEC_MI_HACK2_MOSETS',		'Registrierung erforderlich' );
define( '_AEC_MI_HACK3_MOSETS',		'L&auml;sst keine weiteren neuen Links als die erlaubte Maximalanzahl zu' );
define( '_AEC_MI_HACK4_MOSETS',		'L&auml;sst keine weiteren Listings als die erlaubte Maximalanzahl zu' );
define( '_AEC_MI_DIV1_MOSETS',		'Es sind noch <strong>%s</strong> Listings m&ouml;glich' );

// MySQL Query
define( '_AEC_MI_NAME_MYSQL',		'MySQL Abfrage' );
define( '_AEC_MI_DESC_MYSQL',		'Definiert eine MySQL-Abfrage welche mit diesem Abonnement oder bei Aboablauf ausgef&uuml;hrt wird' );
define( '_AEC_MI_SET1_MYSQL',		'Abfrage' );
define( '_AEC_MI_SET1_1_MYSQL',		'MySQL-Abfrage welche ausgef&uuml;hrt wird wenn diese Integration aufgerufen wird' );
define( '_AEC_MI_SET2_MYSQL',		'Abfrage Ablauf' );
define( '_AEC_MI_SET2_1_MYSQL',		'MySQL-Abfrage welche ausgef&uuml;hrt wird, wenn das Abo abl&auml;ft' );
define( '_AEC_MI_SET3_MYSQL',		'Abfrage vor Ablauf' );
define( '_AEC_MI_SET3_1_MYSQL',		'MySQL-Abfrage welche ausgef&uuml;hrt wird, bevor das Abo abl&auml;ft (Datum siehe ersten Reiter)' );
define( '_AEC_MI_SET4_MYSQL',		'Weitere Infos' );

// reMOSitory
define( '_AEC_MI_NAME_REMOS',		'reMOSitory' );
define( '_AEC_MI_DESC_REMOS',		'Anzahl der Dateien welche der Abonnent downloaden kann und welcher reMOSitory-Gruppe er angeh&ouml;rt' );
define( '_AEC_MI_SET1_REMOS',		'Anzahl der Listings' );
define( '_AEC_MI_SET1_1_REMOS',		'Anzahl der maximalen Anzeigen' );
define( '_AEC_MI_SET2_REMOS',		'In Gruppe' );
define( '_AEC_MI_SET2_1_REMOS',		'Hier "Ja" angeben wenn die reMOSitory-Gruppe verwendet werden soll' );
define( '_AEC_MI_SET3_REMOS',		'Gruppe' );
define( '_AEC_MI_SET3_1_REMOS',		'Welche reMOSitory-Gruppe soll verwendet werden' );
define( '_AEC_MI_SET4_REMOS',		'Gruppe bei Ablauf' );
define( '_AEC_MI_SET4_1_REMOS',		'Mit "Ja" best&auml;tigen wenn die reMOSitory-Gruppe bei Aboablauf verwendet werden soll' );
define( '_AEC_MI_SET5_REMOS',		'Gruppe' );
define( '_AEC_MI_SET5_1_REMOS',		'Hier die reMOSitory-Gruppe definieren welche nach Aboablauf f&uuml;r die Benutzer gelten soll' );
define( '_AEC_MI_HACK1_REMOS',		'Kein Guthaben' );
define( '_AEC_MI_HACK2_REMOS',		'Bildet eine Downloadeinschr&auml;nkung f&uuml;reMOSitory' );

// VirtueMart
define( '_AEC_MI_NAME_VIRTM',		'VirtueMart' );
define( '_AEC_MI_DESC_VIRTM',		'Welcher VirtueMart-Gruppe soll der Benutzer angeh&ouml;hren' );
define( '_AEC_MI_SET1_VIRTM',		'Verwende VM-Gruppe' );
define( '_AEC_MI_SET1_1_VIRTM',		'Mit "Ja" best&auml;tigen wenn die VirtueMart-Einkaufsgruppe verwendet werden soll' );
define( '_AEC_MI_SET2_VIRTM',		'Gruppe' );
define( '_AEC_MI_SET2_1_VIRTM',		'Die VirtueMart-Einkaufsgruppe welche verwendet werden soll' );
define( '_AEC_MI_SET3_VIRTM',		'Gruppe bei Ablauf' );
define( '_AEC_MI_SET3_1_VIRTM',		'Mit "Ja" best&auml;tigen wenn nach Aboablauf eine VM-Gruppe verwendet werden soll' );
define( '_AEC_MI_SET4_VIRTM',		'Gruppe' );
define( '_AEC_MI_SET4_1_VIRTM',		'Die VirtueMart-Gruppe definieren welche nach Aboablauf g&uuml;ltig sein soll' );
?>