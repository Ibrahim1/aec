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

// Load Identifier
define( '_AEC_LANG_INCLUDED_MI', 1);

// acajoom
define( '_AEC_MI_NAME_ACAJOOM',		'Acajoom' );
define( '_AEC_MI_DESC_ACAJOOM',		'Bindet den Newsletter Acajoom ein' );
define( '_MI_MI_ACAJOOM_LIST_NAME',		'Set List' );
define( '_MI_MI_ACAJOOM_LIST_DESC',		'Which Mailing list do you want to assign this user to?' );
define( '_MI_MI_ACAJOOM_LIST_EXP_NAME',		'Set Expiration List' );
define( '_MI_MI_ACAJOOM_LIST_EXP_DESC',		'Which Mailing list do you want to assign this user to after expiration?' );

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
define( '_MI_MI_AFFILIATEPRO_URL_NAME',				'AffiliatePRO URL' );
define( '_MI_MI_AFFILIATEPRO_URL_DESC',				'Hier die AffiliatePRO URL angeben (wie: "http://www.demo.qualityunit.com/postaffiliatepro3/scripts/sale.js")' );

// docman
define( '_AEC_MI_NAME_DOCMAN',		'DocMan' );
define( '_AEC_MI_DESC_DOCMAN',		'Anzahl der m&ouml;glichen Dateien sowie die DocMan-Gruppe w&auml;hlen zu welcher dieser Benutzer z&auml;hlen soll');
define( '_MI_MI_DOCMAN_SET_DOWNLOADS_NAME',			'Downloads setzen' );
define( '_MI_MI_DOCMAN_SET_DOWNLOADS_DESC',			'Die Anzahl der Downloads auf die ein Benutzer (zur&uuml;ck) gesetzt wird. &Uuml;berschreibt den bisherigen Wert!' );
define( '_MI_MI_DOCMAN_ADD_DOWNLOADS_NAME',			'Downloads anf&uuml;gen' );
define( '_MI_MI_DOCMAN_ADD_DOWNLOADS_DESC',			'Anzahl der Downloads, die dem Benutzerkonto hinzugef&uuml;gt werden.' );
define( '_MI_MI_DOCMAN_SET_UNLIMITED_NAME',			'Set Unlimited' );
define( '_MI_MI_DOCMAN_SET_UNLIMITED_DESC',			'Grant the user unlimited downloads.' );
define( '_MI_MI_DOCMAN_SET_GROUP_NAME',				'Verwende DocMan Gruppe' );
define( '_MI_MI_DOCMAN_SET_GROUP_DESC',				'Auf "Ja" setzen wenn die DocMan-Benutzergruppe f&uuml;r diese Integration verwendet werden soll' );
define( '_MI_MI_DOCMAN_GROUP_NAME',					'DocMan Gruppe' );
define( '_MI_MI_DOCMAN_GROUP_DESC',					'Die DocMan-Gruppe welcher diese Benutzer angeh&ouml;ren soll' );
define( '_MI_MI_DOCMAN_GROUP_EXP_NAME',				'DM-Gruppe bei Ablauf' );
define( '_MI_MI_DOCMAN_GROUP_EXP_DESC',				'Auf "Ja" setzen, wenn die DocMan-Gruppe nach Abonnementablauf verwendet werden soll' );
define( '_MI_MI_DOCMAN_SET_GROUP_EXP_NAME',			'DM-Gruppe definieren' );
define( '_MI_MI_DOCMAN_SET_GROUP_EXP_DESC',			'Diejenige DocMan-Gruppe definieren welche nach Aboablauf g&uuml;ltig sein soll' );
define( '_MI_MI_DOCMAN_REBUILD_NAME',				'Neu Erstellen' );
define( '_MI_MI_DOCMAN_REBUILD_DESC',				'Die Gruppenzuweisung aufgrund der Benutzer-Plan-MI Beziehung neu aufbauen.' );
define( '_AEC_MI_HACK1_DOCMAN',						'Erstellt eine Downloadeinschr&auml;nkung f&uuml;r DocMan' );
define( '_AEC_MI_DOCMAN_NOCREDIT',					'Es tut uns au&szlig;erordentlich leid, aber Sie haben keine verbleibenden Downloads &uuml;brig.' );
define( '_MI_MI_DOCMAN_DELETE_ON_EXP_NAME', 		'Action for existing groups when account expires:');
define( '_MI_MI_DOCMAN_DELETE_ON_EXP_DESC',			'Choose what action you want to happen to already defined DocMan groups when the user expires.');
define( '_MI_MI_DOCMAN_REMOVE_NAME', 				'Remove: ' );
define( '_MI_MI_DOCMAN_REMOVE_NAME_DESC',			'Carry out the expiration action for all users with an active plan attached to this micro-integration' );

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
define( '_MI_MI_EMAIL_SUBJECT_DESC',				'Betreff bei Kauf eines Abos (benutzt die unten genannten Text-Feld Regeln)' );
define( '_MI_MI_EMAIL_TEXT_HTML_NAME',				'HTML Format' );
define( '_MI_MI_EMAIL_TEXT_HTML_DESC',				'Soll dieses Email im HTML-Format gesendet werden? (Achtung: dann sollten keine TAGS enthalten sein!)' );
define( '_MI_MI_EMAIL_TEXT_NAME',					'Text' );
define( '_MI_MI_EMAIL_TEXT_DESC',					'Text des Emails wenn ein Abo erworben wird (benutzt die unten genannten Text-Feld Regeln)' );
define( '_MI_MI_EMAIL_SUBJECT_FIRST_NAME',			'Betreff (Neu)' );
define( '_MI_MI_EMAIL_SUBJECT_FIRST_DESC',			'Betreff bei Kauf eines Abos - nur bei allererstem Abo.' );
define( '_MI_MI_EMAIL_TEXT_FIRST_HTML_NAME',		'HTML Format (Neu)' );
define( '_MI_MI_EMAIL_TEXT_FIRST_HTML_DESC',		'Soll diese Email im HTML-Format gesendet werden? (Achtung: dann sollten keine TAGS enthalten sein!)' );
define( '_MI_MI_EMAIL_TEXT_FIRST_NAME',				'Text' );
define( '_MI_MI_EMAIL_TEXT_FIRST_DESC',				'Text der Email wenn ein Abo erworben wird - jedoch nur beim allerersten Abo (benutzt die unten genannten Text-Feld Regeln)' );
define( '_MI_MI_EMAIL_SUBJECT_EXP_NAME',			'Betreff (Ablauf)' );
define( '_MI_MI_EMAIL_SUBJECT_EXP_DESC',			'Betreff bei Ablauf eines Abos' );
define( '_MI_MI_EMAIL_TEXT_EXP_HTML_NAME',			'HTML Format (Ablauf)' );
define( '_MI_MI_EMAIL_TEXT_EXP_HTML_DESC',			'Soll diese Email im HTML-Format gesendet werden? (Achtung: dann sollten keine TAGS enthalten sein!)' );
define( '_MI_MI_EMAIL_TEXT_EXP_NAME',				'Text (Ablauf)' );
define( '_MI_MI_EMAIL_TEXT_EXP_DESC',				'Text der Email wenn ein Abo abl&auml;ft (benutzt die unten genannten Text-Feld Regeln)' );
define( '_MI_MI_EMAIL_SUBJECT_PRE_EXP_NAME',		'Betreff (vor Ablauf)' );
define( '_MI_MI_EMAIL_SUBJECT_PRE_EXP_DESC',		'Betreff welcher gesendet wird bevor das Abo abl&auml;ft (siehe weitere Felder unten)' );
define( '_MI_MI_EMAIL_TEXT_PRE_EXP_HTML_NAME',		'HTML Format (vor Ablauf)' );
define( '_MI_MI_EMAIL_TEXT_PRE_EXP_HTML_DESC',		'Soll diese Email im HTML-Format gesendet werden? (Achtung: dann sollten keine TAGS enthalten sein!)' );
define( '_MI_MI_EMAIL_TEXT_PRE_EXP_NAME',			'Text (vor Ablauf)' );
define( '_MI_MI_EMAIL_TEXT_PRE_EXP_DESC',			'Text der Email bevor ein Abo abl&auml;ft - im vorherigen Reiter konfigurierbar (benutzt die unten genannten Text-Feld Regeln)' );
define( '_AEC_MI_SET11_EMAIL',		'Text-Felder zum Ersetzen durch dynamischem Text' );

// iDevAffiliate
define( '_AEC_MI_NAME_IDEV',		'iDevAffiliate' );
define( '_AEC_MI_DESC_IDEV',		'Connect your sales to the iDevAffiliate Component' );
define( '_MI_MI_IDEVAFFILIATE_SETUPINFO_NAME',		'Important Information' );
define( '_MI_MI_IDEVAFFILIATE_SETUPINFO_DESC',		'Since you surely don\'t have your sale.php in your root directory, you must specify it otherwise, please do so in the directory setting below. You may leave out the root url that joomla sits in as that will be filled automatically. If you do put in a full path (since you might have your joomla in a completely different directory as iDev), the MI will understand that and NOT prefix any root url.' );
define( '_MI_MI_IDEVAFFILIATE_PROFILE_NAME',		'Profile' );
define( '_MI_MI_IDEVAFFILIATE_PROFILE_DESC',		'The Profile identification within iDevAffiliate' );
define( '_MI_MI_IDEVAFFILIATE_DIRECTORY_NAME',		'iDev Directory' );
define( '_MI_MI_IDEVAFFILIATE_DIRECTORY_DESC',		'Specify a subdirectory if iDevAffiliate does not rest in the directory as explained in the above box.' );
define( '_MI_MI_IDEVAFFILIATE_USE_CURL_NAME',		'Use CURL' );
define( '_MI_MI_IDEVAFFILIATE_USE_CURL_DESC',		'Normally, this MI will write to the DisplayPipeline (a module is required and the tracking code will be displayed to the user), but you can also do the tracking internally - the Payment Data and IP Address of the user will be transmitted directly when the payment arrives andd this MI is triggered.' );
define( '_MI_MI_IDEVAFFILIATE_ONLYCUSTOMPARAMS_NAME',		'Only Custom Parameters' );
define( '_MI_MI_IDEVAFFILIATE_ONLYCUSTOMPARAMS_DESC',		'Only transmit the custom parameters as specified below (instead of transmitting the regular - invoice number and amount - and the profile id if set).' );
define( '_MI_MI_IDEVAFFILIATE_CUSTOMPARAMS_NAME',		'Custom Parameters' );
define( '_MI_MI_IDEVAFFILIATE_CUSTOMPARAMS_DESC',		'If you want to transmit custom parameters instead of or additional to the regular parameters, please put them in here. Separated by linebreaks in the form of "parameter_name=parameter_value". The RewriteEngine works as specified below.' );

// MosetsTree
define( '_AEC_MI_NAME_MOSETS',		'MosetsTree' );
define( '_AEC_MI_DESC_MOSETS',		'Anzahl der maximalen Eintr&auml;ge, die ein Abonnent ver&ouml;ffentlichen darf' );
define( '_MI_MI_MOSETS_TREE_SET_LISTINGS_NAME',		'Eintr&auml;ge setzen' );
define( '_MI_MI_MOSETS_TREE_SET_LISTINGS_DESC',		'Die Anzahl der Eintr&auml;ge, die der Benutzer einstellen darf wird auf diesen Wert (zur&uuml;ck)gesetzt' );
define( '_MI_MI_MOSETS_TREE_ADD_LISTINGS_NAME',		'Eintr&auml;ge hinzuf&uuml;gen' );
define( '_MI_MI_MOSETS_TREE_ADD_LISTINGS_DESC',		'Anzahl der Eintr&auml;ge, die dem Benutzerkonto hinzugef&uuml;gt werden.' );
define( '_MI_MI_MOSETS_TREE_PUBLISH_ALL_NAME',		'Publish listings' );
define( '_MI_MI_MOSETS_TREE_PUBLISH_ALL_DESC',		'(Re-) Publish all listings of this user on action' );
define( '_MI_MI_MOSETS_TREE_UNPUBLISH_ALL_NAME',	'Unpublish listings' );
define( '_MI_MI_MOSETS_TREE_UNPUBLISH_ALL_DESC',	'Unpublish all listings of this user on expiration' );
define( '_AEC_MI_HACK1_MOSETS',		'Keine weiteren Eintr&auml;ge m&ouml;glich' );
define( '_AEC_MI_HACK2_MOSETS',		'Registrierung erforderlich' );
define( '_AEC_MI_HACK3_MOSETS',		'L&auml;sst keine weiteren neuen Eintr&auml;ge als die erlaubte Maximalanzahl zu' );
define( '_AEC_MI_HACK4_MOSETS',		'L&auml;sst beim Speichern eines Eintr&auml;ge keine weiteren Eintr&auml;ge als die erlaubte Maximalanzahl zu. Falls Eintr&auml;ge vom Admin best&auml;tigt werden m&uuml;ssen, bitte ausserdem den n&auml;chsten Hack nutzen.' );
define( '_AEC_MI_HACK5_MOSETS',		'Wenn Eintr&auml;ge von Admins best&auml;tigt werden m&uuml;ssen, wird dieser Hack bei der Ausf&uuml;hrung dieser Aktion die erlaubte Maximalanzahl &uuml;berpr&uuml;fen. Falls der Benutzer weitere Eintr&auml;ge anlegen darf, werden diese freigegeben und der Z&auml;hler entsprechend ge&auml;ndert.' );
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
define( '_MI_MI_REMOSITORY_ADD_DOWNLOADS_NAME',		'Downloads Addieren' );
define( '_MI_MI_REMOSITORY_ADD_DOWNLOADS_DESC',		'Anzahl der Downloads die dem Benutzer zus&auml;tzlich gestattet werden sollen' );
define( '_MI_MI_REMOSITORY_SET_DOWNLOADS_NAME',		'Downloads Setzen' );
define( '_MI_MI_REMOSITORY_SET_DOWNLOADS_DESC',		'Anzahl der Downloads die dem Benutzer insgesamt gestattet werden sollen - &uuml;berschreibt den bisherigen Wert!' );
define( '_MI_MI_REMOSITORY_SET_UNLIMITED_NAME',		'Set Unlimited' );
define( '_MI_MI_REMOSITORY_SET_UNLIMITED_DESC',		'Grant the user unlimited downloads.' );
define( '_MI_MI_REMOSITORY_SET_GROUP_NAME',			'Gruppe' );
define( '_MI_MI_REMOSITORY_SET_GROUP_DESC',			'Mit "Ja" best&auml;tigen wenn die reMOSitory-Gruppe bei Aboablauf verwendet werden soll' );
define( '_MI_MI_REMOSITORY_GROUP_NAME',				'Gruppe' );
define( '_MI_MI_REMOSITORY_GROUP_DESC',				'Welche reMOSitory-Gruppe soll verwendet werden?' );
define( '_MI_MI_REMOSITORY_SET_GROUP_EXP_NAME',		'Gruppe bei Ablauf' );
define( '_MI_MI_REMOSITORY_SET_GROUP_EXP_DESC',		'Hier die reMOSitory-Gruppe definieren welche nach Aboablauf f&uuml;r die Benutzer gelten soll' );
define( '_MI_MI_REMOSITORY_GROUP_EXP_NAME',			'Expiration group' );
define( '_MI_MI_REMOSITORY_GROUP_EXP_DESC',			'Mit "Ja" best&auml;tigen wenn die reMOSitory-Gruppe bei Aboablauf verwendet werden soll' );
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
define( '_MI_MI_VIRTUEMART_CREATE_ACCOUNT_NAME',	'Konto erstellen' );
define( '_MI_MI_VIRTUEMART_CREATE_ACCOUNT_DESC',	'Legt automatisch ein neues Benutzerkonto an, falls keines vorhanden ist.' );
define( '_MI_MI_VIRTUEMART_REBUILD_NAME',	'Rebuild' );
define( '_MI_MI_VIRTUEMART_REBUILD_DESC',	'Attempt to rebuild the list of users assigned to the usergroup according to their relationship to a plan that holds this MI.' );

// Joomlauser
define( '_AEC_MI_NAME_JOOMLAUSER',					'Joomla Benutzer' );
define( '_AEC_MI_DESC_JOOMLAUSER',					'Aktionen die das Joomla Benutzerkonto betreffen.' );
define( '_MI_MI_JOOMLAUSER_ACTIVATE_NAME',			'Aktivieren' );
define( '_MI_MI_JOOMLAUSER_ACTIVATE_DESC',			'Mit "Ja" wird der Benutzer automatisch aktiviert, braucht also keinen Aktivierungslink mehr zu benutzen.' );

// CommunityBuilder
define( '_AEC_MI_NAME_COMMUNITYBUILDER',				'Community Builder' );
define( '_AEC_MI_DESC_COMMUNITYBUILDER',				'Aktionen das Community-Builder-Benutzerkonto betreffend' );
define( '_MI_MI_COMMUNITYBUILDER_APPROVE_NAME',			'Admin Freigabe' );
define( '_MI_MI_COMMUNITYBUILDER_APPROVE_DESC',			'Setzt die Freigabe durch den Admin wenn diese Integration aufgerufen wird.' );
define( '_MI_MI_COMMUNITYBUILDER_UNAPPROVE_EXP_NAME',	'Admin Freigabe zur&uuml;cknehmen' );
define( '_MI_MI_COMMUNITYBUILDER_UNAPPROVE_EXP_DESC',	'Setzt die Admin-Freigabe wieder auf "Nein" zur&uuml;ck wenn die Mitgliedschaft abl&auml;uft.' );
define( '_MI_MI_COMMUNITYBUILDER_SET_FIELDS_NAME',		'Felder setzen' );
define( '_MI_MI_COMMUNITYBUILDER_SET_FIELDS_DESC',		'Automatisch Felder eines Benutzerkontos &auml;ndern (siehe unten - Eintr&auml;ge, die nicht mit "(ablauf)"), gekennzeichnet sind) sobald der Plan bezahlt ist' );
define( '_MI_MI_COMMUNITYBUILDER_SET_FIELDS_EXP_NAME',	'Felder setzen (Ablauf)' );
define( '_MI_MI_COMMUNITYBUILDER_SET_FIELDS_EXP_DESC',	'Automatisch Felder eines Benutzerkontos &auml;ndern (siehe unten - Eintr&auml;ge, die mit "(ablauf)"), gekennzeichnet sind) sobald der Plan bezahlt ist' );
define( '_MI_MI_COMMUNITYBUILDER_EXPMARKER',			'(ablauf)' );

// JUGA
define( '_AEC_MI_NAME_JUGA',		'JUGA' );
define( '_AEC_MI_DESC_JUGA',		'Set JUGA groups on apply or expire plan' );
define( '_MI_MI_JUGA_SET_ENROLL_GROUP_NAME',		'Add to Group' );
define( '_MI_MI_JUGA_SET_ENROLL_GROUP_DESC',		'Set to yes, and pick groups below to enroll the user in on application of plan? (Multiple select allowed)' );
define( '_MI_MI_JUGA_ENROLL_GROUP_NAME',			'JUGA Group' );
define( '_MI_MI_JUGA_ENROLL_GROUP_DESC',			'Select a plan to enroll the user in on application of plan:' );
define( '_MI_MI_JUGA_SET_REMOVE_GROUP_NAME',		'Remove Groups' );
define( '_MI_MI_JUGA_SET_REMOVE_GROUP_DESC',		'Set to yes, to delete all groups for this user before the groups below are applied, otherwise these groups will be added to existing groups.' );
define( '_MI_MI_JUGA_SET_ENROLL_GROUP_EXP_NAME',	'Add to Group Exp' );
define( '_MI_MI_JUGA_SET_ENROLL_GROUP_EXP_DESC',	'Set to yes, and pick groups below to enroll the user in on expiration of plan? (Multiple select allowed)' );
define( '_MI_MI_JUGA_ENROLL_GROUP_EXP_NAME',		'JUGA Group Exp' );
define( '_MI_MI_JUGA_ENROLL_GROUP_EXP_DESC',		'Select a plan to enroll the user in on expiration of plan:' );
define( '_MI_MI_JUGA_SET_REMOVE_GROUP_EXP_NAME',	'Remove Groups Exp' );
define( '_MI_MI_JUGA_SET_REMOVE_GROUP_EXP_DESC',	'Set to yes, to delete all groups for this user before the groups below are applied, otherwise these groups will be added to existing groups.' );
define( '_MI_MI_JUGA_REBUILD_NAME',					'Rebuild' );
define( '_MI_MI_JUGA_REBUILD_DESC',					'Select YES to rebuild the groups relations after saving this' );

// DisplayPipeline
define( '_AEC_MI_NAME_DISPLAYPIPELINE',		'DisplayPipeline' );
define( '_AEC_MI_DESC_DISPLAYPIPELINE',		'Display Text on the AEC Module' );
define( '_MI_MI_DISPLAYPIPELINE_ONLY_USER_NAME',		'Only to User' );
define( '_MI_MI_DISPLAYPIPELINE_ONLY_USER_DESC',		'Only display this text to the user who issued this request' );
define( '_MI_MI_DISPLAYPIPELINE_ONCE_PER_USER_NAME',	'Once per User' );
define( '_MI_MI_DISPLAYPIPELINE_ONCE_PER_USER_DESC',	'Only display this text once to a user. This will be set to no automatically if you set the above switch to save ressources.' );
define( '_MI_MI_DISPLAYPIPELINE_EXPIRE_NAME',			'Expire' );
define( '_MI_MI_DISPLAYPIPELINE_EXPIRE_DESC',			'Do not display after a certain date.' );
define( '_MI_MI_DISPLAYPIPELINE_EXPIRATION_NAME',		'Expiration' );
define( '_MI_MI_DISPLAYPIPELINE_EXPIRATION_DESC',		'Set this as Expiration. Refer to <a href="http://www.php.net/manual/en/function.strtotime.php" alt="php.net manual on strtotime function">this manual</a> to see what you can use as input here.' );
define( '_MI_MI_DISPLAYPIPELINE_DISPLAYMAX_NAME',		'Display Max' );
define( '_MI_MI_DISPLAYPIPELINE_DISPLAYMAX_DESC',		'Set amount of times this can be displayed' );
define( '_MI_MI_DISPLAYPIPELINE_TEXT_NAME',				'Text' );
define( '_MI_MI_DISPLAYPIPELINE_TEXT_DESC',				'Text that is displayed to the user. You can use the rewrite strings explained below to insert dynamic data.' );

// GoogleAnalytics
define( '_AEC_MI_NAME_GOOGLEANALYTICS',		'Google Analytics' );
define( '_AEC_MI_DESC_GOOGLEANALYTICS',		'With this, you can attach Google Analytics e-commerce tracking code to the DisplayPipeline. [Experimental - please give feedback to the developers!]' );
define( '_MI_MI_GOOGLEANALYTICS_ACCOUNT_ID_NAME',		'Google Account ID' );
define( '_MI_MI_GOOGLEANALYTICS_ACCOUNT_ID_DESC',		'Your Google Account id, it should look like this: UA-xxxx-x' );

// Fireboard
define( '_AEC_MI_NAME_FIREBOARD','Fireboard Micro Integration');
define( '_AEC_MI_DESC_FIREBOARD','Will automate addition of a user to a group in FB. *NOTE* FB currently has limitted support for FB groups. You are advised to check the fireboard forums for limtiations.  Full use will not come until FB 1.1.  In 1.0.0 to 1.0.2 this can be used with a CSS change to show group information under the user\'s avatar as happens on www.bestofjoomla.org with admin team members');
define( '_MI_MI_FIREBOARD_SET_GROUP_NAME','Set group on plan application');
define( '_MI_MI_FIREBOARD_SET_GROUP_DESC','Choose Yes if you wish a fireboard group to be applied when the plan is applied');
define( '_MI_MI_FIREBOARD_GROUP_NAME','Fireboard group to apply member to on application');
define( '_MI_MI_FIREBOARD_GROUP_DESC','The group you wish applied - if you chose yes. Manually create groups in table jos_fb_groups');
define( '_MI_MI_FIREBOARD_SET_GROUP_EXP_NAME','Set group on expiration of plan');
define( '_MI_MI_FIREBOARD_SET_GROUP_EXP_DESC','Choose Yes if you wish the fireboard group to be changed when the plan expires');
define( '_MI_MI_FIREBOARD_GROUP_EXP_NAME','Fireboard group to apply member to on expiration of plan.');
define( '_MI_MI_FIREBOARD_GROUP_EXP_DESC','The group you wish to use if the plan expires.  Manually add groups to the table jos_fb_groups');
define( '_MI_MI_FIREBOARD_REBUILD_NAME',				'Rebuild Groups' );
define( '_MI_MI_FIREBOARD_REBUILD_DESC',				'This option will rebuild your whole Fireboard group assignment by looking for each plan that has this MI applied and then add each user that uses one of these plans to the file.' );

// Coupon
define( '_AEC_MI_NAME_COUPON', 'Coupons');
define( '_AEC_MI_DESC_COUPON', 'Create and send out coupons');
define( '_MI_MI_COUPON_MASTER_COUPON_NAME', 'Master Coupon');
define( '_MI_MI_COUPON_MASTER_COUPON_DESC', 'Which Master Coupon should these be copied from?');
define( '_MI_MI_COUPON_BIND_SUBSCRIPTION_NAME', 'Bind to Subscription');
define( '_MI_MI_COUPON_BIND_SUBSCRIPTION_DESC', 'If activated, the coupons will only be usable if the subscription they have been created with is still active as well');
define( '_MI_MI_COUPON_CREATE_NEW_COUPONS_NAME', 'Amount');
define( '_MI_MI_COUPON_CREATE_NEW_COUPONS_DESC', 'The amount of coupons that should be created');
define( '_MI_MI_COUPON_MAX_REUSE_NAME', 'Max Reuse Coupons');
define( '_MI_MI_COUPON_MAX_REUSE_DESC', 'The amount of times these coupons can be used');
define( '_MI_MI_COUPON_MAIL_OUT_COUPONS_NAME', 'Mail out Coupons');
define( '_MI_MI_COUPON_MAIL_OUT_COUPONS_DESC', 'This will send the coupons in an email to the address specified below');
define( '_MI_MI_COUPON_ALWAYS_NEW_COUPONS_NAME', 'Always new ones?');
define( '_MI_MI_COUPON_ALWAYS_NEW_COUPONS_DESC', 'Always create new coupons (Yes) if the MI is triggered or only on the first time (No)?');
define( '_MI_MI_COUPON_INC_OLD_COUPONS_NAME', 'Increment Old Coupons');
define( '_MI_MI_COUPON_INC_OLD_COUPONS_DESC', 'Will increment the uses of old coupons by the given amount, so that they can be used again after the subscription has been renewed');
define( '_MI_MI_COUPON_SENDER_NAME',				'Sender E-Mail' );
define( '_MI_MI_COUPON_SENDER_DESC',				'Sender E-Mail Address' );
define( '_MI_MI_COUPON_SENDER_NAME_NAME',			'Sender Name' );
define( '_MI_MI_COUPON_SENDER_NAME_DESC',			'The displayed name of the Sender' );
define( '_MI_MI_COUPON_RECIPIENT_NAME',				'Recipient(s)' );
define( '_MI_MI_COUPON_RECIPIENT_DESC',				'Who is to receive this E-Mail? Separate with comma. The rewriting routines explained below will work for this field.' );
define( '_MI_MI_COUPON_SUBJECT_NAME',				'Subject' );
define( '_MI_MI_COUPON_SUBJECT_DESC',				'Subject of this email (Uses RewriteEngine explained below as well)' );
define( '_MI_MI_COUPON_TEXT_HTML_NAME',				'HTML Encoding' );
define( '_MI_MI_COUPON_TEXT_HTML_DESC',				'Do you want this email to be HTML encoded? (Make sure that there are not tags in it if you do not want this)' );
define( '_MI_MI_COUPON_TEXT_NAME',					'Text' );
define( '_MI_MI_COUPON_TEXT_DESC',					'Text to be sent when the coupons are created. The rewriting routines explained below will work for this field. Mark the point where the coupon codes are displayed with "%s"!' );

// Attend Events
define( '_AEC_MI_NAME_ATTEND_EVENTS',		'Attend Events' );
define( '_AEC_MI_DESC_ATTEND_EVENTS',		'Dummy MicroIntegration - for now only links payments from AE to AEC' );

// HTTP QUERY
define( '_AEC_MI_NAME_HTTP_QUERY',		'HTTP Query' );
define( '_AEC_MI_DESC_HTTP_QUERY',		'Sends out a HTTP request to an url, including GET variables' );
define( '_MI_MI_HTTP_QUERY_URL_NAME',			'URL' );
define( '_MI_MI_HTTP_QUERY_URL_DESC',			'The URL that this Request should go to.' );
define( '_MI_MI_HTTP_QUERY_QUERY_NAME',			'Query Variables' );
define( '_MI_MI_HTTP_QUERY_QUERY_DESC',			'Transmit these variables via HTTP GET when calling the URL. Separated by linebreaks in the form of "parameter_name=parameter_value". The RewriteEngine works as specified below.' );
define( '_MI_MI_HTTP_QUERY_URL_EXP_NAME',			'URL (Expiration)' );
define( '_MI_MI_HTTP_QUERY_URL_EXP_DESC',			'The URL that this Request should go to when the plan is expired.' );
define( '_MI_MI_HTTP_QUERY_QUERY_EXP_NAME',			'Query Variables' );
define( '_MI_MI_HTTP_QUERY_QUERY_EXP_DESC',			'Transmit these variables via HTTP GET when calling the URL. Separated by linebreaks in the form of "parameter_name=parameter_value". The RewriteEngine works as specified below.' );
define( '_MI_MI_HTTP_QUERY_URL_PRE_EXP_NAME',			'URL (Before Expiration)' );
define( '_MI_MI_HTTP_QUERY_URL_PRE_EXP_DESC',			'The URL that this Request should go to before the plan is expired.' );
define( '_MI_MI_HTTP_QUERY_QUERY_PRE_EXP_NAME',			'Query Variables' );
define( '_MI_MI_HTTP_QUERY_QUERY_PRE_EXP_DESC',			'Transmit these variables via HTTP GET when calling the URL. Separated by linebreaks in the form of "parameter_name=parameter_value". The RewriteEngine works as specified below.' );

// MySMS
define('_AEC_MI_NAME_MYSMS', 'MySMS Micro Integration');
define('_AEC_MI_DESC_MYSMS', 'Erlaubt es einem Benutzer Sms mit MySMS zu versenden, indem x Credits dem Benuterkonto gutgeschrieben.');
define('_MI_MI_MYSMS_DISABLE_EXP_NAME', 'Disable Account (Expiration)');
define('_MI_MI_MYSMS_DISABLE_EXP_DESC', 'Disable the user account on expiration.');

// ACL
define('_AEC_MI_NAME_ACL', 'Usergroup MI (ACL)');
define('_AEC_MI_DESC_ACL', 'Set the usergroup for the user account.');
define('_MI_MI_ACL_CHANGE_SESSION_NAME', 'Change Session');
define('_MI_MI_ACL_CHANGE_SESSION_DESC', 'Do a direct write on the Session data, so that the user account is immediately changed and not just on the next login.');
define('_MI_MI_ACL_SET_GID_NAME', 'Set GID?');
define('_MI_MI_ACL_SET_GID_DESC', 'Activate setting of a GID when applying the plan');
define('_MI_MI_ACL_GID_NAME', 'GID');
define('_MI_MI_ACL_GID_DESC', 'Set this Usergroup for the Account.');
define('_MI_MI_ACL_SET_GID_EXP_NAME', 'Set on Expir.?');
define('_MI_MI_ACL_SET_GID_EXP_DESC', 'Activate setting of a GID on Expiration');
define('_MI_MI_ACL_GID_EXP_NAME', 'Expir. GID');
define('_MI_MI_ACL_GID_EXP_DESC', 'Set this Usergroup for the Account when it expires.');
define('_MI_MI_ACL_SET_GID_PRE_EXP_NAME', 'Set PreExpir.?');
define('_MI_MI_ACL_SET_GID_PRE_EXP_DESC', 'Activate setting of a GID before expiration');
define('_MI_MI_ACL_GID_PRE_EXP_NAME', 'PreExpir. GID');
define('_MI_MI_ACL_GID_PRE_EXP_DESC', 'Set this Usergroup for the Account when before expires');
define('_MI_MI_ACL_JACLPLUSPRO_NAME', 'Use JACLplus PRO');
define('_MI_MI_ACL_JACLPLUSPRO_DESC', 'With JACLplus PRO, you can use a few other ACL features specified below');
define('_MI_MI_ACL_DELETE_SUBGROUPS_NAME', 'Clear Subgroups');
define('_MI_MI_ACL_DELETE_SUBGROUPS_DESC', 'Always delete all Subgroups that the user holds before applying new ones');
define('_MI_MI_ACL_SUB_SET_GID_NAME', 'Set Subgroups');
define('_MI_MI_ACL_SUB_SET_GID_DESC', 'Activate setting of Subgroups when applying the plan');
define('_MI_MI_ACL_SUB_GID_DEL_NAME', 'Delete Subgroups');
define('_MI_MI_ACL_SUB_GID_DEL_DESC', 'Delete these Subgroups if the user holds them (CTRL+click to select multiple)');
define('_MI_MI_ACL_SUB_GID_NAME', 'Add Subgroups');
define('_MI_MI_ACL_SUB_GID_DESC', 'Add these Subgroups (CTRL+click to select multiple)');
define('_MI_MI_ACL_SUB_SET_GID_EXP_NAME', 'Set Subgroups Expiration');
define('_MI_MI_ACL_SUB_SET_GID_EXP_DESC', 'Activate setting of Subgroups when the plan expires');
define('_MI_MI_ACL_SUB_GID_EXP_DEL_NAME', 'Delete Subgroups');
define('_MI_MI_ACL_SUB_GID_EXP_DEL_DESC', 'Delete these Subgroups if the user holds them (CTRL+click to select multiple)');
define('_MI_MI_ACL_SUB_GID_EXP_NAME', 'Add Subgroups');
define('_MI_MI_ACL_SUB_GID_EXP_DESC', 'Add these Subgroups (CTRL+click to select multiple)');
define('_MI_MI_ACL_SUB_SET_GID_PRE_EXP_NAME', 'Set Subgroups PreExpiration');
define('_MI_MI_ACL_SUB_SET_GID_PRE_EXP_DESC', 'Activate setting of Subgroups before the plan expires');
define('_MI_MI_ACL_SUB_GID_PRE_EXP_DEL_NAME', 'Delete Subgroups');
define('_MI_MI_ACL_SUB_GID_PRE_EXP_DEL_DESC', 'Delete these Subgroups if the user holds them (CTRL+click to select multiple)');
define('_MI_MI_ACL_SUB_GID_PRE_EXP_NAME', 'Add Subgroups');
define('_MI_MI_ACL_SUB_GID_PRE_EXP_DESC', 'Add these Subgroups (CTRL+click to select multiple)');

// eventlog
define( '_AEC_MI_EVENTLOG_NAME', 'Eventlog' );
define( '_AEC_MI_EVENTLOG_DESC', 'Make entries into the Eventlog' );
define( '_MI_MI_EVENTLOG_SHORT_NAME', 'Short' );
define( '_MI_MI_EVENTLOG_SHORT_DESC', 'The short explanation or title of the entry.' );
define( '_MI_MI_EVENTLOG_TAGS_NAME', 'Tags' );
define( '_MI_MI_EVENTLOG_TAGS_DESC', 'Tags for this entry' );
define( '_MI_MI_EVENTLOG_TEXT_NAME', 'Text' );
define( '_MI_MI_EVENTLOG_TEXT_DESC', 'Text or long explanation of the entry.' );
define( '_MI_MI_EVENTLOG_LEVEL_NAME', 'Level' );
define( '_MI_MI_EVENTLOG_LEVEL_DESC', 'Importance Level of the entry' );
define( '_MI_MI_EVENTLOG_FORCE_NOTIFY_NAME', 'Force Notification' );
define( '_MI_MI_EVENTLOG_FORCE_NOTIFY_DESC', 'Force appearance of this entry on the central page, regardless of the settings for notification.' );
define( '_MI_MI_EVENTLOG_FORCE_EMAIL_NAME', 'Force E-Mail' );
define( '_MI_MI_EVENTLOG_FORCE_EMAIL_DESC', 'Force emailing of this entry, regardless of the settings for notification.' );
define( '_MI_MI_EVENTLOG_SHORT_EXP_NAME', 'Short (Expiration)' );
define( '_MI_MI_EVENTLOG_SHORT_EXP_DESC', 'The short explanation or title of the entry.' );
define( '_MI_MI_EVENTLOG_TAGS_EXP_NAME', 'Tags (Expiration)' );
define( '_MI_MI_EVENTLOG_TAGS_EXP_DESC', 'Tags for this entry' );
define( '_MI_MI_EVENTLOG_TEXT_EXP_NAME', 'Text (Expiration)' );
define( '_MI_MI_EVENTLOG_TEXT_EXP_DESC', 'Text or long explanation of the entry.' );
define( '_MI_MI_EVENTLOG_LEVEL_EXP_NAME', 'Level (Expiration)' );
define( '_MI_MI_EVENTLOG_LEVEL_EXP_DESC', 'Importance Level of the entry' );
define( '_MI_MI_EVENTLOG_FORCE_NOTIFY_EXP_NAME', 'Force Notification (Expiration)' );
define( '_MI_MI_EVENTLOG_FORCE_NOTIFY_EXP_DESC', 'Force appearance of this entry on the central page, regardless of the settings for notification.' );
define( '_MI_MI_EVENTLOG_FORCE_EMAIL_EXP_NAME', 'Force E-Mail (Expiration)' );
define( '_MI_MI_EVENTLOG_FORCE_EMAIL_EXP_DESC', 'Force emailing of this entry, regardless of the settings for notification.' );
define( '_MI_MI_EVENTLOG_SHORT_PRE_EXP_NAME', 'Short (Pre-Expiration)' );
define( '_MI_MI_EVENTLOG_SHORT_PRE_EXP_DESC', 'The short explanation or title of the entry.' );
define( '_MI_MI_EVENTLOG_TAGS_PRE_EXP_NAME', 'Tags (Pre-Expiration)' );
define( '_MI_MI_EVENTLOG_TAGS_PRE_EXP_DESC', 'Tags for this entry' );
define( '_MI_MI_EVENTLOG_TEXT_PRE_EXP_NAME', 'Text (Pre-Expiration)' );
define( '_MI_MI_EVENTLOG_TEXT_PRE_EXP_DESC', 'Text or long explanation of the entry.' );
define( '_MI_MI_EVENTLOG_LEVEL_PRE_EXP_NAME', 'Level (Pre-Expiration)' );
define( '_MI_MI_EVENTLOG_LEVEL_PRE_EXP_DESC', 'Importance Level of the entry' );
define( '_MI_MI_EVENTLOG_FORCE_NOTIFY_PRE_EXP_NAME', 'Force Notification (Pre-Expiration)' );
define( '_MI_MI_EVENTLOG_FORCE_NOTIFY_PRE_EXP_DESC', 'Force appearance of this entry on the central page, regardless of the settings for notification.' );
define( '_MI_MI_EVENTLOG_FORCE_EMAIL_PRE_EXP_NAME', 'Force E-Mail (Pre-Expiration)' );
define( '_MI_MI_EVENTLOG_FORCE_EMAIL_PRE_EXP_DESC', 'Force emailing of this entry, regardless of the settings for notification.' );

// JARC
define( '_AEC_MI_NAME_JARC', 'JARC' );
define( '_AEC_MI_DESC_JARC', 'Create affililates and track payments in JARC' );
define( '_MI_MI_JARC_CREATE_AFFILIATES_NAME', 'Create Affiliates' );
define( '_MI_MI_JARC_CREATE_AFFILIATES_DESC', 'Create new affiliate accounts when the user is just registering at your site.' );
define( '_MI_MI_JARC_LOG_PAYMENTS_NAME', 'Log Payments' );
define( '_MI_MI_JARC_LOG_PAYMENTS_DESC', 'Log transactions in JARC.' );

// APC
define( '_AEC_MI_NAME_APC', 'APC' );
define( '_AEC_MI_DESC_APC', 'Manage Advanced Profile Control access groups' );
define( '_MI_MI_APC_SET_GROUP_NAME',		'Set APC Group' );
define( '_MI_MI_APC_SET_GROUP_DESC',		'Choose Yes if you want this MI to set the APC Group when it is called.' );
define( '_MI_MI_APC_SET_DEFAULT_NAME',		'Set Default' );
define( '_MI_MI_APC_SET_DEFAULT_DESC',		'Disregard the group setting below and apply the default group.' );
define( '_MI_MI_APC_GROUP_NAME',			'APC Group' );
define( '_MI_MI_APC_GROUP_DESC',			'The APC group that you want the user to be in.' );
define( '_MI_MI_APC_SET_GROUP_EXP_NAME',	'Expiration group' );
define( '_MI_MI_APC_SET_GROUP_EXP_DESC',	'The APC group that you want the user to be in when the subscription runs out.' );
define( '_MI_MI_APC_SET_DEFAULT_EXP_NAME',	'Set Default (exp)' );
define( '_MI_MI_APC_SET_DEFAULT_EXP_DESC',	'Disregard the group setting below and apply the default group.' );
define( '_MI_MI_APC_GROUP_EXP_NAME',		'Set APC Group expiration' );
define( '_MI_MI_APC_GROUP_EXP_DESC',		'Choose Yes if you want this MI to set the APC Group when the calling payment plan expires.' );
define( '_MI_MI_APC_REBUILD_NAME',			'Rebuild' );
define( '_MI_MI_APC_REBUILD_DESC',			'Attempt to rebuild the list of users assigned to the usergroup - >Set APC Group< and >APC Group< have to both be set for this.' );

// Hot Property
define( '_AEC_MI_HOTPROPERTY_NAME', 'Hot Property' );
define( '_AEC_MI_HOTPROPERTY_DESC', 'Create and change Agents and Companies with this MI' );
define( '_MI_MI_HOTPROPERTY_CREATE_AGENT_NAME',		'Create Agent' );
define( '_MI_MI_HOTPROPERTY_CREATE_AGENT_DESC',		'Choose Yes if you want this MI to create an agent on Subscription (if there is none yet for this user).' );
define( '_MI_MI_HOTPROPERTY_AGENT_FIELDS_NAME',		'Agent Fields' );
define( '_MI_MI_HOTPROPERTY_AGENT_FIELDS_DESC',		'Tell the AEC which fields should be associated in setting up the agent account. As seen in the example, part individual columns with a newline break and for each column, make it look like this: "fieldname=content". You can use the RewriteEngine as mentioned below.' );
define( '_MI_MI_HOTPROPERTY_UPDATE_AGENT_NAME',		'Update Agent' );
define( '_MI_MI_HOTPROPERTY_UPDATE_AGENT_DESC',		'Choose Yes if you want this MI to update the agent related to this user on Subscription.' );
define( '_MI_MI_HOTPROPERTY_UPDATE_AFIELDS_NAME',		'Update Agent Fields' );
define( '_MI_MI_HOTPROPERTY_UPDATE_AFIELDS_DESC',		'Tell the AEC which fields should be associated in changing up the agent account. As seen in the example, part individual columns with a newline break and for each column, make it look like this: "fieldname=content". You can use the RewriteEngine as mentioned below.' );
define( '_MI_MI_HOTPROPERTY_CREATE_COMPANY_NAME',	'Create Company' );
define( '_MI_MI_HOTPROPERTY_CREATE_COMPANY_DESC',	'Choose Yes if you want this MI to create a company on Subscription (if there is none yet for this user).' );
define( '_MI_MI_HOTPROPERTY_COMPANY_FIELDS_NAME',	'Company Fields' );
define( '_MI_MI_HOTPROPERTY_COMPANY_FIELDS_DESC',	'Tell the AEC which fields should be associated in setting up the company account. As seen in the example, part individual columns with a newline break and for each column, make it look like this: "fieldname=content". You can use the RewriteEngine as mentioned below.' );
define( '_MI_MI_HOTPROPERTY_UPDATE_COMPANY_NAME',		'Update Company' );
define( '_MI_MI_HOTPROPERTY_UPDATE_COMPANY_DESC',		'Choose Yes if you want this MI to update the agent related to this user on Subscription.' );
define( '_MI_MI_HOTPROPERTY_UPDATE_CFIELDS_NAME',		'Update Company Fields' );
define( '_MI_MI_HOTPROPERTY_UPDATE_CFIELDS_DESC',		'Tell the AEC which fields should be associated in changing up the company account. As seen in the example, part individual columns with a newline break and for each column, make it look like this: "fieldname=content". You can use the RewriteEngine as mentioned below.' );
define( '_MI_MI_HOTPROPERTY_PUBLISH_ALL_NAME',		'Publish properties' );
define( '_MI_MI_HOTPROPERTY_PUBLISH_ALL_DESC',		'(Re-) Publish all properties of this user on action' );
define( '_MI_MI_HOTPROPERTY_UNPUBLISH_ALL_NAME',	'Unpublish properties' );
define( '_MI_MI_HOTPROPERTY_UNPUBLISH_ALL_DESC',	'Unpublish all properties of this user on action' );

define( '_MI_MI_HOTPROPERTY_CREATE_AGENT_EXP_NAME',		'Create Agent (Expiration)' );
define( '_MI_MI_HOTPROPERTY_CREATE_AGENT_EXP_DESC',		'Choose Yes if you want this MI to create an agent on Expiration (if there is none yet for this user).' );
define( '_MI_MI_HOTPROPERTY_AGENT_FIELDS_EXP_NAME',		'Agent Fields (Expiration)' );
define( '_MI_MI_HOTPROPERTY_AGENT_FIELDS_EXP_DESC',		'Tell the AEC which fields should be associated in setting up the agent account. As seen in the example, part individual columns with a newline break and for each column, make it look like this: "fieldname=content". You can use the RewriteEngine as mentioned below.' );
define( '_MI_MI_HOTPROPERTY_UPDATE_AGENT_EXP_NAME',		'Update Agent (Expiration)' );
define( '_MI_MI_HOTPROPERTY_UPDATE_AGENT_EXP_DESC',		'Choose Yes if you want this MI to update the agent related to this user on Expiration.' );
define( '_MI_MI_HOTPROPERTY_UPDATE_AFIELDS_EXP_NAME',		'Update Agent Fields (Expiration)' );
define( '_MI_MI_HOTPROPERTY_UPDATE_AFIELDS_EXP_DESC',		'Tell the AEC which fields should be associated in changing up the agent account. As seen in the example, part individual columns with a newline break and for each column, make it look like this: "fieldname=content". You can use the RewriteEngine as mentioned below.' );
define( '_MI_MI_HOTPROPERTY_CREATE_COMPANY_EXP_NAME',	'Create Company (Expiration)' );
define( '_MI_MI_HOTPROPERTY_CREATE_COMPANY_EXP_DESC',	'Choose Yes if you want this MI to create a company on Expiration (if there is none yet for this user).' );
define( '_MI_MI_HOTPROPERTY_COMPANY_FIELDS_EXP_NAME',	'Company Fields (Expiration)' );
define( '_MI_MI_HOTPROPERTY_COMPANY_FIELDS_EXP_DESC',	'Tell the AEC which fields should be associated in setting up the company account. As seen in the example, part individual columns with a newline break and for each column, make it look like this: "fieldname=content". You can use the RewriteEngine as mentioned below.' );
define( '_MI_MI_HOTPROPERTY_UPDATE_COMPANY_EXP_NAME',		'Update Company (Expiration)' );
define( '_MI_MI_HOTPROPERTY_UPDATE_COMPANY_EXP_DESC',		'Choose Yes if you want this MI to update the agent related to this user on Expiration.' );
define( '_MI_MI_HOTPROPERTY_UPDATE_CFIELDS_EXP_NAME',		'Update Company Fields (Expiration)' );
define( '_MI_MI_HOTPROPERTY_UPDATE_CFIELDS_EXP_DESC',		'Tell the AEC which fields should be associated in changing up the company account. As seen in the example, part individual columns with a newline break and for each column, make it look like this: "fieldname=content". You can use the RewriteEngine as mentioned below.' );
define( '_MI_MI_HOTPROPERTY_PUBLISH_ALL_EXP_NAME',		'Publish properties (Expiration)' );
define( '_MI_MI_HOTPROPERTY_PUBLISH_ALL_EXP_DESC',		'(Re-) Publish all properties of this user on expiration' );
define( '_MI_MI_HOTPROPERTY_UNPUBLISH_ALL_EXP_NAME',	'Unpublish properties (Expiration)' );
define( '_MI_MI_HOTPROPERTY_UNPUBLISH_ALL_EXP_DESC',	'Unpublish all properties of this user on expiration' );

define( '_MI_MI_HOTPROPERTY_CREATE_AGENT_PRE_EXP_NAME',		'Create Agent (Pre Expiration)' );
define( '_MI_MI_HOTPROPERTY_CREATE_AGENT_PRE_EXP_DESC',		'Choose Yes if you want this MI to create an agent before Expiration (if there is none yet for this user).' );
define( '_MI_MI_HOTPROPERTY_AGENT_FIELDS_PRE_EXP_NAME',		'Agent Fields (Pre Expiration)' );
define( '_MI_MI_HOTPROPERTY_AGENT_FIELDS_PRE_EXP_DESC',		'Tell the AEC which fields should be associated in setting up the agent account. As seen in the example, part individual columns with a newline break and for each column, make it look like this: "fieldname=content". You can use the RewriteEngine as mentioned below.' );
define( '_MI_MI_HOTPROPERTY_UPDATE_AGENT_PRE_EXP_NAME',		'Update Agent (Pre Expiration)' );
define( '_MI_MI_HOTPROPERTY_UPDATE_AGENT_PRE_EXP_DESC',		'Choose Yes if you want this MI to update the agent related to this user before Expiration.' );
define( '_MI_MI_HOTPROPERTY_UPDATE_AFIELDS_PRE_EXP_NAME',		'Update Agent Fields (Pre Expiration)' );
define( '_MI_MI_HOTPROPERTY_UPDATE_AFIELDS_PRE_EXP_DESC',		'Tell the AEC which fields should be associated in changing up the agent account. As seen in the example, part individual columns with a newline break and for each column, make it look like this: "fieldname=content". You can use the RewriteEngine as mentioned below.' );
define( '_MI_MI_HOTPROPERTY_CREATE_COMPANY_PRE_EXP_NAME',	'Create Company (Pre Expiration)' );
define( '_MI_MI_HOTPROPERTY_CREATE_COMPANY_PRE_EXP_DESC',	'Choose Yes if you want this MI to create a company before Expiration (if there is none yet for this user).' );
define( '_MI_MI_HOTPROPERTY_COMPANY_FIELDS_PRE_EXP_NAME',	'Company Fields (Pre Expiration)' );
define( '_MI_MI_HOTPROPERTY_COMPANY_FIELDS_PRE_EXP_DESC',	'Tell the AEC which fields should be associated in setting up the company account. As seen in the example, part individual columns with a newline break and for each column, make it look like this: "fieldname=content". You can use the RewriteEngine as mentioned below.' );
define( '_MI_MI_HOTPROPERTY_UPDATE_COMPANY_PRE_EXP_NAME',		'Update Company (Pre Expiration)' );
define( '_MI_MI_HOTPROPERTY_UPDATE_COMPANY_PRE_EXP_DESC',		'Choose Yes if you want this MI to update the agent related to this user before Expiration.' );
define( '_MI_MI_HOTPROPERTY_UPDATE_CFIELDS_PRE_EXP_NAME',		'Update Company Fields (Pre Expiration)' );
define( '_MI_MI_HOTPROPERTY_UPDATE_CFIELDS_PRE_EXP_DESC',		'Tell the AEC which fields should be associated in changing up the company account. As seen in the example, part individual columns with a newline break and for each column, make it look like this: "fieldname=content". You can use the RewriteEngine as mentioned below.' );
define( '_MI_MI_HOTPROPERTY_PUBLISH_ALL_PRE_EXP_NAME',		'Publish properties (Pre Expiration' );
define( '_MI_MI_HOTPROPERTY_PUBLISH_ALL_PRE_EXP_DESC',		'(Re-) Publish all properties of this user before Expiration' );
define( '_MI_MI_HOTPROPERTY_UNPUBLISH_ALL_PRE_EXP_NAME',	'Unpublish properties (Pre Expiration' );
define( '_MI_MI_HOTPROPERTY_UNPUBLISH_ALL_PRE_EXP_DESC',	'Unpublish all properties of this user before Expiration' );

define( '_MI_MI_HOTPROPERTY_SET_LISTINGS_NAME',		'Set listings' );
define( '_MI_MI_HOTPROPERTY_SET_LISTINGS_DESC',		'Input the amount of listings you want as an overwriting set for this call' );
define( '_MI_MI_HOTPROPERTY_ADD_LISTINGS_NAME',		'Add listings' );
define( '_MI_MI_HOTPROPERTY_ADD_LISTINGS_DESC',		'Input the amount of listings you want to add with this call' );
define( '_MI_MI_HOTPROPERTY_SET_LISTINGS_EXP_NAME',		'Set listings (Expiration)' );
define( '_MI_MI_HOTPROPERTY_SET_LISTINGS_EXP_DESC',		'Input the amount of listings you want as an overwriting set on expiration' );
define( '_MI_MI_HOTPROPERTY_ADD_LISTINGS_EXP_NAME',		'Add listings (Expiration)' );
define( '_MI_MI_HOTPROPERTY_ADD_LISTINGS_EXP_DESC',		'Input the amount of listings you want to add with on expiration' );
define( '_MI_MI_HOTPROPERTY_SET_LISTINGS_PRE_EXP_NAME',		'Set listings (Pre Expiration)' );
define( '_MI_MI_HOTPROPERTY_SET_LISTINGS_PRE_EXP_DESC',		'Input the amount of listings you want as an overwriting set before expiration' );
define( '_MI_MI_HOTPROPERTY_ADD_LISTINGS_PRE_EXP_NAME',		'Add listings (Pre Expiration)' );
define( '_MI_MI_HOTPROPERTY_ADD_LISTINGS_PRE_EXP_DESC',		'Input the amount of listings you want to add before expiration' );

define( '_MI_MI_HOTPROPERTY_SET_UNLIMITED_NAME',		'Set unlimited' );
define( '_MI_MI_HOTPROPERTY_SET_UNLIMITED_DESC',		'Grant unlimited downloads on application' );
define( '_MI_MI_HOTPROPERTY_SET_UNLIMITED_EXP_NAME',		'Set unlimited (Expiration)' );
define( '_MI_MI_HOTPROPERTY_SET_UNLIMITED_EXP_DESC',		'Grant unlimited downloads on expiration' );
define( '_MI_MI_HOTPROPERTY_SET_UNLIMITED_PRE_EXP_NAME',		'Set unlimited (Pre Expiration)' );
define( '_MI_MI_HOTPROPERTY_SET_UNLIMITED_PRE_EXP_DESC',		'Grant unlimited downloads before expiration' );

define( '_MI_MI_HOTPROPERTY_ASSOC_COMPANY_NAME',	'Associate Company' );
define( '_MI_MI_HOTPROPERTY_ASSOC_COMPANY_DESC',	'Automatically associate the new user account with the new company account.' );
define( '_MI_MI_HOTPROPERTY_REBUILD_NAME',	'Rebuild' );
define( '_MI_MI_HOTPROPERTY_REBUILD_DESC',	'Attempt to rebuild the effect this MI has on the users who are in a plan that has this MI assigned.' );
define( '_MI_MI_HOTPROPERTY_REMOVE_NAME',	'Remove' );
define( '_MI_MI_HOTPROPERTY_REMOVE_NAME',	'Attempt to remove the effect this MI has on the users who are in a plan that has this MI assigned.' );

define( '_MI_MI_HOTPROPERTY_ADD_LIST_USERCHOICE_NAME',	'Listings Userchoice' );
define( '_MI_MI_HOTPROPERTY_ADD_LIST_USERCHOICE_DESC',	'Select whether you want the user to select the amount of listings' );
define( '_MI_MI_HOTPROPERTY_ADD_LIST_USERCHOICE_AMT_NAME',	'Userchoice' );
define( '_MI_MI_HOTPROPERTY_ADD_LIST_USERCHOICE_AMT_DESC',	'Semicolon-separated list of choices (eg "2;4;6"). You can insert custom text for the frontend like so: "2,only two listings;4,4 listings;6,six listings".' );
define( '_MI_MI_HOTPROPERTY_ADD_LIST_CUSTOMPRICE_NAME',	'Custom Price' );
define( '_MI_MI_HOTPROPERTY_ADD_LIST_CUSTOMPRICE_DESC',	'Modify the Membership price with coupons like this: "2,COUPONCODE2;4,COUPONCODE4;6,COUPONCODE6".' );

define( '_AEC_MI_HACK1_HOTPROPERTY',		'No Listings left' );
define( '_AEC_MI_HACK2_HOTPROPERTY',		'Registration and correct Subscription Required!' );
define( '_AEC_MI_HACK3_HOTPROPERTY',		'Prevent user from creating a new listing if he or she has run out of granted listings' );
define( '_AEC_MI_HACK4_HOTPROPERTY',		'Prevent user from saving a new listing if he or she has run out of granted listings. Also use a listing if the user has one left and it does not need to be approved - if it does, his listings count will be updated on the following hack.' );
define( '_AEC_MI_HACK5_HOTPROPERTY',		'Check for allowed listings and update the Used Listings counter when approving listings in the backend (see above for reference).' );
define( '_AEC_MI_DIV1_HOTPROPERTY',		'You can create <strong>%s</strong> more listings in our directory.' );

define( '_MI_MI_HOTPROPERTY_USERSELECT_ADDAMOUNT_NAME',		'Select Amount of Listings' );
define( '_MI_MI_HOTPROPERTY_USERSELECT_ADDAMOUNT_DESC',		'Select Amount of Listings' );

define( '_MI_MI_HOTPROPERTY_EASY_LIST_USERCHOICE_NAME',		'Easy Custom Price' );
define( '_MI_MI_HOTPROPERTY_EASY_LIST_USERCHOICE_DESC',		'Set a custom price in an easy fashion' );
define( '_MI_MI_HOTPROPERTY_EASY_LIST_USERCHOICE_N_NAME',		'Easy Custom Fields' );
define( '_MI_MI_HOTPROPERTY_EASY_LIST_USERCHOICE_N_DESC',		'The amount of conditional fields you want to put in' );

define( '_AEC_MI_HOTPROPERTY_EASYLIST_OP_NAME',		'Condition: Selection:' );
define( '_AEC_MI_HOTPROPERTY_EASYLIST_OP_DESC',		'Choose the conditional operator' );
define( '_AEC_MI_HOTPROPERTY_EASYLIST_NO_NAME',		'Condition: ...Number:' );
define( '_AEC_MI_HOTPROPERTY_EASYLIST_NO_DESC',		'The number you want to compare to' );
define( '_AEC_MI_HOTPROPERTY_EASYLIST_CH_NAME',		'Sets it to Price:' );
define( '_AEC_MI_HOTPROPERTY_EASYLIST_CH_DESC',		'Change the price to this' );

// Directory
define( '_AEC_MI_NAME_DIRECTORY', 'Directory' );
define( '_AEC_MI_DESC_DIRECTORY', 'Create Directories with this MI' );
define( '_MI_MI_DIRECTORY_MKDIR_NAME',		'Create Directory' );
define( '_MI_MI_DIRECTORY_MKDIR_DESC',		'Create a directory with this path' );
define( '_MI_MI_DIRECTORY_MKDIR_MODE_NAME',		'Directory Mode' );
define( '_MI_MI_DIRECTORY_MKDIR_MODE_DESC',		'The octal mode number (always 4 characters!) for access restriction. Default is 0644.' );
define( '_MI_MI_DIRECTORY_MKDIR_EXP_NAME',		'Create Directory (Exp)' );
define( '_MI_MI_DIRECTORY_MKDIR_EXP_DESC',		'Create a directory with this path on Expiration' );
define( '_MI_MI_DIRECTORY_MKDIR_MODE_EXP_NAME',		'Directory Mode' );
define( '_MI_MI_DIRECTORY_MKDIR_MODE_EXP_DESC',		'The octal mode number (always 4 characters!) for access restriction on Expiration. Default is 0644.' );
define( '_MI_MI_DIRECTORY_MKDIR_PRE_EXP_NAME',		'Create Directory (Pre Exp)' );
define( '_MI_MI_DIRECTORY_MKDIR_PRE_EXP_DESC',		'Create a directory with this path before Expiration' );
define( '_MI_MI_DIRECTORY_MKDIR_MODE_PRE_EXP_NAME',		'Directory Mode (Pre Exp)' );
define( '_MI_MI_DIRECTORY_MKDIR_MODE_PRE_EXP_DESC',		'The octal mode number (always 4 characters!) for access restriction before Expiration. Default is 0644.' );

// Gallery2
define( '_AEC_MI_NAME_G2', 'Gallery2' );
define( '_AEC_MI_DESC_G2', 'Manage Gallery2 users and their permissions' );
define( '_MI_MI_G2_SET_GROUPS_NAME',		'Set Groups' );
define( '_MI_MI_G2_SET_GROUPS_DESC',		'Global Setting - add the user to groups' );
define( '_MI_MI_G2_GROUPS_NAME',			'Groups' );
define( '_MI_MI_G2_GROUPS_DESC',			'Which groups should the user be added to?' );
define( '_MI_MI_G2_SET_GROUPS_USER_NAME',		'Set Groups (User Selection)' );
define( '_MI_MI_G2_SET_GROUPS_USER_DESC',		'Allow the user to select groups.' );
define( '_MI_MI_G2_GROUPS_SEL_AMT_NAME',		'Group Amount' );
define( '_MI_MI_G2_GROUPS_SEL_AMT_DESC',		'How many groups can the user select' );
define( '_MI_MI_G2_GROUPS_SEL_SCOPE_NAME',		'Group Scope' );
define( '_MI_MI_G2_GROUPS_SEL_SCOPE_DESC',		'From which groups can the user choose?' );
define( '_MI_MI_G2_DEL_GROUPS_EXP_NAME',		'Delete Groups (Expiration)' );
define( '_MI_MI_G2_DEL_GROUPS_EXP_DESC',		'Remove the user from the previously assigned groups on expiration' );
define( '_MI_MI_G2_USERSELECT_GROUP_NAME',		'Select Gallery' );
define( '_MI_MI_G2_USERSELECT_GROUP_DESC',		'Please select a Gallery' );

// RSgallery2
define( '_AEC_MI_RSGALLERY2_NAME', 'RSgallery2' );
define( '_AEC_MI_RSGALLERY2_DESC', 'Create User galleries and manage gallery publication status' );
define( '_MI_MI_RSGALLERY2_CREATE_GALLERIES_NAME',		'Create Galleries' );
define( '_MI_MI_RSGALLERY2_CREATE_GALLERIES_DESC',		'General switch for whether or not galleries will be created' );
define( '_MI_MI_RSGALLERY2_GALLERIES_NAME_NAME',		'Gallery Name' );
define( '_MI_MI_RSGALLERY2_GALLERIES_NAME_DESC',		'How will the new gallery/ies be named?' );
define( '_MI_MI_RSGALLERY2_GALLERIES_DESC_NAME',		'Gallery Description' );
define( '_MI_MI_RSGALLERY2_GALLERIES_DESC_DESC',		'Enter a description for the new gallery/ies' );
define( '_MI_MI_RSGALLERY2_SET_GALLERIES_NAME',			'Set Galleries' );
define( '_MI_MI_RSGALLERY2_SET_GALLERIES_DESC',			'Add the user to galleries' );
define( '_MI_MI_RSGALLERY2_GALLERIES_NAME',				'Galleries' );
define( '_MI_MI_RSGALLERY2_GALLERIES_DESC',				'In which galleries will the user get his/her own?' );
define( '_MI_MI_RSGALLERY2_SET_GALLERIES_USER_NAME',	'Set Galleries (User Selection)' );
define( '_MI_MI_RSGALLERY2_SET_GALLERIES_USER_DESC',	'Allow the user to select galleries in which a personal gallery will be granted.' );
define( '_MI_MI_RSGALLERY2_GALLERY_SEL_AMT_NAME',		'Gallery Amount' );
define( '_MI_MI_RSGALLERY2_GALLERY_SEL_AMT_DESC',		'How many galleries can the user select' );
define( '_MI_MI_RSGALLERY2_GALLERY_SEL_SCOPE_NAME',		'Gallery Scope' );
define( '_MI_MI_RSGALLERY2_GALLERY_SEL_SCOPE_DESC',		'From which galleries can the user choose?' );
define( '_MI_MI_RSGALLERY2_PUBLISH_ALL_NAME',			'Publish Galleries' );
define( '_MI_MI_RSGALLERY2_PUBLISH_ALL_DESC',			'Automatically publish the user-galleries on plan application (if they were previously unpublished)' );
define( '_MI_MI_RSGALLERY2_UNPUBLISH_ALL_NAME',			'Unpublish Galleries (Expiration)' );
define( '_MI_MI_RSGALLERY2_UNPUBLISH_ALL_DESC',			'Unpublish the user-galleries on expiration' );
define( '_MI_MI_RSGALLERY2_GALLERY_USERSELECT_NAME',	'Select Gallery' );
define( '_MI_MI_RSGALLERY2_GALLERY_USERSELECT_DESC',	'Please select a Gallery' );

// AEC Plan MI
define( '_AEC_MI_AECPLAN_NAME', 'AEC Plan Application' );
define( '_AEC_MI_AECPLAN_DESC', 'Apply a payment plan to a user' );
define( '_MI_MI_AECPLAN_PLAN_APPLY_NAME',		'Apply Plan' );
define( '_MI_MI_AECPLAN_PLAN_APPLY_DESC',		'Apply this payment plan (for free) when the MI is carried out' );
define( '_MI_MI_AECPLAN_PLAN_APPLY_EXP_NAME',		'Apply Plan (Expiration)' );
define( '_MI_MI_AECPLAN_PLAN_APPLY_EXP_DESC',		'Apply this payment plan (for free) when the MI is carried out on expiration' );
define( '_MI_MI_AECPLAN_PLAN_APPLY_PRE_EXP_NAME',		'Apply Plan (Before Expiration)' );
define( '_MI_MI_AECPLAN_PLAN_APPLY_PRE_EXP_DESC',		'Apply this payment plan (for free) when the MI is carried out before expiration' );

// SOBI
define( '_AEC_MI_SOBI_NAME',		'SOBI' );
define( '_AEC_MI_SOBI_DESC',		'Publish or unpublish listings in Joomla\'s Sigsiu Online Business Index component' );
define( '_MI_MI_SOBI_PUBLISH_ALL_NAME',			'Publish All: ' );
define( '_MI_MI_SOBI_PUBLISH_ALL_DESC',			'Choose yes to publish all SOBI listings for this user on activation of the MI' );
define( '_MI_MI_SOBI_PUBLISH_ALL_EXP_NAME',			'Publish All on Expiration: ' );
define( '_MI_MI_SOBI_PUBLISH_ALL_EXP_DESC',			'Choose yes to publish all SOBI listings for this user on activation of the MI' );
define( '_MI_MI_SOBI_PUBLISH_ALL_PRE_EXP_NAME',			'Publish All on Pre-Expiration:' );
define( '_MI_MI_SOBI_PUBLISH_ALL_PRE_EXP_DESC',			'Choose yes to publish all SOBI listings for this user when a pre-expiration action occurs for this MI' );

define( '_MI_MI_SOBI_UNPUBLISH_ALL_NAME',			'Unpublish All: ' );
define( '_MI_MI_SOBI_UNPUBLISH_ALL_DESC',			'Choose yes to unpublish all SOBI listings for this user on activation of the MI' );
define( '_MI_MI_SOBI_UNPUBLISH_ALL_EXP_NAME',			'Unpublish All on Expiration: ' );
define( '_MI_MI_SOBI_UNPUBLISH_ALL_EXP_DESC',			'Choose yes to unpublish all SOBI listings for this user on activation of the MI' );
define( '_MI_MI_SOBI_UNPUBLISH_ALL_PRE_EXP_NAME',			'Unpublish All on Pre-Expiration:' );
define( '_MI_MI_SOBI_UNPUBLISH_ALL_PRE_EXP_DESC',			'Choose yes to unpublish all SOBI listings for this user when a pre-expiration action occurs for this MI' );

define( '_MI_MI_SOBI_REBUILD_NAME',			'Rebuild: ' );
define( '_MI_MI_SOBI_REBUILD_DESC',			'Choose yes and then save the MI to recreate the actions fo all users with this MI on a currently active plan.' );
define( '_MI_MI_SOBI_REMOVE_NAME',			'Remove: ' );
define( '_MI_MI_SOBI_REMOVE_DESC',			'Choose yes and then save the MI to carry out the expiration action for all users with this MI on a currently active plan.' );

?>