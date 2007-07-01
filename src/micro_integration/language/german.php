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
?>