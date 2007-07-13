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
define( '_AEC_MI_DESC_ACAJOOM',		'Bindet den Newsletter Acajoom ein (freie Version)' );

// htaccess
define( '_AEC_MI_NAME_HTACCESS',	'.htaccess' );
define( '_AEC_MI_DESC_HTACCESS',	'Sch&uuml;tzt einen Ordner mit einer .htaccess Datei und erlaubt nur berechtigten Abonnenten Zugriff darauf' );
define( '_MI_MI_HTACCESS_MI_FOLDER_NAME',	'Ordner' );
define( '_MI_MI_HTACCESS_MI_FOLDER_DESC',	'Der zu sch&uuml;tzende Ordner. Folgende Schl&uuml;sselw&ouml;rter werden ersetzt<br />[cmsstammordner] -> %s<br />Hinweis: keine abschie&szlig;ender Slash - der Ordnername darf ebenso keinen haben!' );
define( '_MI_MI_HTACCESS_MI_PASSWORDFOLDER_NAME',	'Passwortordner' );
define( '_MI_MI_HTACCESS_MI_PASSWORDFOLDER_DESC',	'Datei f&uuml;r die Passw&oumlrter. Sollte <strong>nicht</strong> innerhalb des vom Web zug&auml;glichen CMS gespeichert werden!' );
define( '_MI_MI_HTACCESS_MI_NAME_NAME',	'Bereichsname' );
define( '_MI_MI_HTACCESS_MI_NAME_DESC',	'Name des gesch&uuml;tzten Bereiches' );
define( '_MI_MI_HTACCESS_USE_MD5_NAME',	'md5 verwenden' );
define( '_MI_MI_HTACCESS_USE_MD5_DESC',	'<strong>Wichtig!</strong> Wenn diese Integration verwendet werden soll, um Ordner auf einem Apacheserver zu sch&uuml;tzen, muss "crypt" verwendet werden. In so einem Fall hier auf "Nein" einstellen.<br />Wird jedoch eine andere Software/anderer Server (wie z.B. ein icecast Server), dann hier auf "Ja" stellen, es wird dann die Standard md5 Verschl&uuml;sselung verwendet.' );
define( '_MI_MI_HTACCESS_REBUILD_NAME',	'Wiederherstellung' );
define( '_MI_MI_HTACCESS_REBUILD_DESC',	'Sollte die htaccess-Datei ge&auml;ndert oder diese gel&ouml;scht werden, stellt diese Einstellung sicher, da&szlig; die gesamte .htaccess Wiederhergestellt wird' );

//affiliate PRO
define( '_AEC_MI_NAME_AFFPRO',		'AffiliatePRO' );
define( '_AEC_MI_DESC_AFFPRO',		'Verbindet AEC-Zahlungen mit AffilitePRO' );
define( '_MI_MI_AFFILIATEPRO_ADD_INFO_NAME',		'Weitere Infos' );
define( '_MI_MI_AFFILIATEPRO_ADD_INFO_DESC',		'Weitere Informationen welche an AffiliatePRO gesendet werden' );
define( '_MI_MI_AFFILIATEPRO_URL_NAME',				'AffiliatePRO URL' );
define( '_MI_MI_AFFILIATEPRO_URL_DESC',				'Hier die AffiliatePRO URL (ohne http://) angeben' );
define( '_MI_MI_AFFILIATEPRO_GROUP_ID_NAME',		'AffiliatePRO ID' );
define( '_MI_MI_AFFILIATEPRO_GROUP_ID_DESC',		'Hier die AffiliatePRO ID angeben welche f&uuml;r die Provisionierung verwendet wird' );

// docman
define( '_AEC_MI_NAME_DOCMAN',		'DocMan' );
define( '_AEC_MI_DESC_DOCMAN',		'Anzahl der m&ouml;glichen Dateien sowie die DocMan-Gruppe w&auml;hlen zu welcher dieser Benutzer z&auml;hlen soll');
define( '_MI_MI_DOCMAN_SET_DOWNLOADS_NAME',			'Downloads setzen' );
define( '_MI_MI_DOCMAN_SET_DOWNLOADS_DESC',			'Die Anzahl der Downloads auf die ein Benutzer (zur&uuml;ck) gesetzt wird.' );
define( '_MI_MI_DOCMAN_ADD_DOWNLOADS_NAME',			'Downloads anf&uuml;gen' );
define( '_MI_MI_DOCMAN_ADD_DOWNLOADS_DESC',			'Anzahl der Downloads, die dem Benutzerkonto hinzugef&uuml;gt werden.' );
define( '_MI_MI_DOCMAN_SET_GROUP_NAME',				'Verwende DocMan Gruppe' );
define( '_MI_MI_DOCMAN_SET_GROUP_DESC',				'Auf "Ja" setzen wenn die DocMan-Benutzergruppe f&uuml;r diese Integration verwendet werden soll' );
define( '_MI_MI_DOCMAN_GROUP_NAME',					'DocMan Gruppe' );
define( '_MI_MI_DOCMAN_GROUP_DESC',					'Die DocMan-Gruppe welcher diese Benutzer angeh&ouml;ren soll' );
define( '_MI_MI_DOCMAN_GROUP_EXP_NAME',				'DM-Gruppe bei Ablauf' );
define( '_MI_MI_DOCMAN_GROUP_EXP_DESC',				'Auf "Ja" setzen, wenn die DocMan-Gruppe nach Abonnementablauf verwendet werden soll' );
define( '_MI_MI_DOCMAN_SET_GROUP_EXP_NAME',			'DM-Gruppe definieren' );
define( '_MI_MI_DOCMAN_SET_GROUP_EXP_DESC',			'Diejenige DocMan-Gruppe definieren welche nach Aboablauf g&uuml;ltig sein soll' );
define( '_AEC_MI_HACK1_DOCMAN',		'Erstellt eine Downloadeinschr&auml;nkung f&uuml;r DocMan' );

// email
define( '_AEC_MI_NAME_EMAIL',		'Email' );
define( '_AEC_MI_DESC_EMAIL',		'Sendet ein Emial an eine oder mehrere Adressen bei Abschluss oder Beendigung eines Abonnements' );
define( '_MI_MI_EMAIL_SENDER_NAME',					'Absenderemail' );
define( '_MI_MI_EMAIL_SENDER_DESC',					'Emailadresse des Absenders' );
define( '_MI_MI_EMAIL_SENDER_NAME_NAME',			'Absendername' );
define( '_MI_MI_EMAIL_SENDER_NAME_DESC',			'Anzuzeigender Name des Absenders' );
define( '_MI_MI_EMAIL_RECIPIENT_NAME',				'Empf&auml;nger' );
define( '_MI_MI_EMAIL_RECIPIENT_DESC',				'Wer soll dieses Email empfangen? Mehrere Empf&auml;nger mit Komma trennen!' );
define( '_MI_MI_EMAIL_SUBJECT_NAME',				'Betreff' );
define( '_MI_MI_EMAIL_SUBJECT_DESC',				'Betreff bei Neuerwerbung/Kauf eines Abos' );
define( '_MI_MI_EMAIL_TEXT_HTML_NAME',				'HTML Format' );
define( '_MI_MI_EMAIL_TEXT_HTML_DESC',				'Soll dieses Email im HTML-Format gesendet werden? (Achtung: dann d&uuml;rfen keine TAGS enthalten sein falls das nicht gew&uuml;nscht ist)' );
define( '_MI_MI_EMAIL_TEXT_NAME',					'Text' );
define( '_MI_MI_EMAIL_TEXT_DESC',					'Text des Emails wenn ein ABO erworben wird (siehe weitere Felder unten)' );
define( '_MI_MI_EMAIL_SUBJECT_EXP_NAME',			'Betreff' );
define( '_MI_MI_EMAIL_SUBJECT_EXP_DESC',			'Text bei Ablauf eines Abos' );
define( '_MI_MI_EMAIL_TEXT_EXP_HTML_NAME',			'HTML Format' );
define( '_MI_MI_EMAIL_TEXT_EXP_HTML_DESC',			'Soll dieses Email im HTML-Format gesendet werden? (Achtung: dann d&uuml;rfen keine TAGS enthalten sein falls das nicht gew&uuml;nscht ist)' );
define( '_MI_MI_EMAIL_SUBJECT_PRE_EXP_NAME',		'Text vor Ablauf' );
define( '_MI_MI_EMAIL_SUBJECT_PRE_EXP_DESC',		'Text welcher gesendet wird bevor das Abo abl&auml;ft (siehe weitere Felder unten)' );
define( '_MI_MI_EMAIL_TEXT_PRE_EXP_HTML_NAME',		'HTML Format' );
define( '_MI_MI_EMAIL_TEXT_PRE_EXP_HTML_DESC',		'Soll dieses Email im HTML-Format gesendet werden? (Achtung: dann d&uuml;rfen keine TAGS enthalten sein falls das nicht gew&uuml;nscht ist)' );
define( '_AEC_MI_SET11_EMAIL',		'Weitere Infos' );

// iDevAffiliate
define( '_AEC_MI_NAME_IDEV',		'iDevAffiliate' );
define( '_AEC_MI_DESC_IDEV',		'Zahlungen mit iDevAffiliate verbinden' );
define( '_MI_MI_IDEVAFFILIATE_MI_ORDER_ID_NAME',		'Bestell ID' );
define( '_MI_MI_IDEVAFFILIATE_MI_ORDER_ID_DESC',		'Die Bestellnummer.<br />Automatische Erg&auml;zungen:<br />\'[invoice]\' = Rechnungsnummer<br />\'[planid]\' = BenutzerID' );
define( '_MI_MI_IDEVAFFILIATE_MI_ORDER_SUBTOTAL_NAME',	'Zwischensumme' );
define( '_MI_MI_IDEVAFFILIATE_MI_ORDER_SUBTOTAL_DESC',	'Bisherige Summe dieser Bestellung. Das Feld \'[auto]\' angeben um es dem Rechnngsbetrag zu zuweisen' );
define( '_AEC_MI_DIV1_IDEV',		'Zugriff erfolgt' );
define( '_AEC_MI_DIV2_IDEV',		'Schluessel ist' );
define( '_AEC_MI_DIV3_IDEV',		'und Wert =' );

// MosetsTree
define( '_AEC_MI_NAME_MOSETS',		'MosetsTree' );
define( '_AEC_MI_DESC_MOSETS',		'Anzahl der maximalen Links ein Abonnent ver&ouml;ffentlichen darf' );
define( '_MI_MI_MOSETS_TREE_SET_LISTINGS_NAME',		'Eintr&auml;ge setzen' );
define( '_MI_MI_MOSETS_TREE_SET_LISTINGS_DESC',		'Die Anzahl der Eintr&auml;ge, die der Benutzer einstellen darf wird auf diesen Wert (zur&uuml;ck)gesetzt' );
define( '_MI_MI_MOSETS_TREE_ADD_LISTINGS_NAME',		'Eintr&auml;ge hinzuf&uuml;gen' );
define( '_MI_MI_MOSETS_TREE_ADD_LISTINGS_DESC',		'Anzahl der Eintr&auml;ge, die dem Benutzerkonto hinzugef&uuml;gt werden.' );
define( '_AEC_MI_HACK1_MOSETS',		'Keine weiteren Eintr&auml;ge m&ouml;glich' );
define( '_AEC_MI_HACK2_MOSETS',		'Registrierung erforderlich' );
define( '_AEC_MI_HACK3_MOSETS',		'L&auml;sst keine weiteren neuen Eintr&auml;ge als die erlaubte Maximalanzahl zu' );
define( '_AEC_MI_HACK4_MOSETS',		'L&auml;sst keine weiteren Listings als die erlaubte Maximalanzahl zu' );
define( '_AEC_MI_DIV1_MOSETS',		'Es sind noch <strong>%s</strong> Listings m&ouml;glich' );

// MySQL Query
define( '_AEC_MI_NAME_MYSQL',		'MySQL Abfrage' );
define( '_AEC_MI_DESC_MYSQL',		'Definiert eine MySQL-Abfrage welche mit diesem Abonnement oder bei Aboablauf ausgef&uuml;hrt wird' );
define( '_MI_MI_MYSQL_QUERY_QUERY_NAME',			'Abfrage' );
define( '_MI_MI_MYSQL_QUERY_QUERY_DESC',			'MySQL-Abfrage welche ausgef&uuml;hrt wird wenn diese Integration aufgerufen wird' );
define( '_MI_MI_MYSQL_QUERY_QUERY_EXP_NAME',		'Abfrage Ablauf' );
define( '_MI_MI_MYSQL_QUERY_QUERY_EXP_DESC',		'MySQL-Abfrage welche ausgef&uuml;hrt wird, wenn das Abo abl&auml;ft' );
define( '_MI_MI_MYSQL_QUERY_QUERY_PRE_EXP_NAME',	'Abfrage vor Ablauf' );
define( '_MI_MI_MYSQL_QUERY_QUERY_PRE_EXP_DESC',	'MySQL-Abfrage welche ausgef&uuml;hrt wird, bevor das Abo abl&auml;ft (Datum siehe ersten Reiter)' );
define( '_AEC_MI_SET4_MYSQL',		'Weitere Infos' );

// reMOSitory
define( '_AEC_MI_NAME_REMOS',		'reMOSitory' );
define( '_AEC_MI_DESC_REMOS',		'Anzahl der Dateien welche der Abonnent downloaden kann und welcher reMOSitory-Gruppe er angeh&ouml;rt' );
define( '_MI_MI_REMOSITORY_ADD_DOWNLOADS_NAME',		'Anzahl der Listings' );
define( '_MI_MI_REMOSITORY_ADD_DOWNLOADS_DESC',		'Anzahl der maximalen Anzeigen' );
define( '_MI_MI_REMOSITORY_SET_DOWNLOADS_NAME',		'In Gruppe' );
define( '_MI_MI_REMOSITORY_SET_DOWNLOADS_DESC',		'Hier "Ja" angeben wenn die reMOSitory-Gruppe verwendet werden soll' );
define( '_MI_MI_REMOSITORY_SET_GROUP_NAME',			'Gruppe' );
define( '_MI_MI_REMOSITORY_SET_GROUP_DESC',			'Welche reMOSitory-Gruppe soll verwendet werden' );
define( '_MI_MI_REMOSITORY_GROUP_NAME',				'Gruppe bei Ablauf' );
define( '_MI_MI_REMOSITORY_GROUP_DESC',				'Mit "Ja" best&auml;tigen wenn die reMOSitory-Gruppe bei Aboablauf verwendet werden soll' );
define( '_MI_MI_REMOSITORY_SET_GROUP_EXP_NAME',		'Gruppe' );
define( '_MI_MI_REMOSITORY_SET_GROUP_EXP_DESC',		'Hier die reMOSitory-Gruppe definieren welche nach Aboablauf f&uuml;r die Benutzer gelten soll' );
define( '_AEC_MI_HACK1_REMOS',		'Kein Guthaben' );
define( '_AEC_MI_HACK2_REMOS',		'Bildet eine Downloadeinschr&auml;nkung f&uuml;reMOSitory' );

// VirtueMart
define( '_AEC_MI_NAME_VIRTM',		'VirtueMart' );
define( '_AEC_MI_DESC_VIRTM',		'Welcher VirtueMart-Gruppe soll der Benutzer angeh&ouml;hren' );
define( '_MI_MI_VIRTUEMART_SET_SHOPPER_GROUP_NAME',	'Verwende VM-Gruppe' );
define( '_MI_MI_VIRTUEMART_SET_SHOPPER_GROUP_DESC',	'Mit "Ja" best&auml;tigen wenn die VirtueMart-Einkaufsgruppe verwendet werden soll' );
define( '_MI_MI_VIRTUEMART_SHOPPER_GROUP_NAME',		'Gruppe' );
define( '_MI_MI_VIRTUEMART_SHOPPER_GROUP_DESC',		'Die VirtueMart-Einkaufsgruppe welche verwendet werden soll' );
define( '_MI_MI_VIRTUEMART_SET_SHOPPER_GROUP_EXP_NAME',		'Gruppe bei Ablauf' );
define( '_MI_MI_VIRTUEMART_SET_SHOPPER_GROUP_EXP_DESC',		'Mit "Ja" best&auml;tigen wenn nach Aboablauf eine VM-Gruppe verwendet werden soll' );
define( '_MI_MI_VIRTUEMART_SHOPPER_GROUP_EXP_NAME',	'Gruppe' );
define( '_MI_MI_VIRTUEMART_SHOPPER_GROUP_EXP_DESC',	'Die VirtueMart-Gruppe definieren welche nach Aboablauf g&uuml;ltig sein soll' );
?>