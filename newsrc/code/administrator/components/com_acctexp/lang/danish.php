<?php
/**
* @version $Id: english.php
* @package AEC - Account Control Expiration - Membership Manager
* @subpackage Language - Backend - English
* @copyright 2006-2010 Copyright (C) David Deutsch
* @author David Deutsch <skore@skore.de> & Team AEC - http://www.valanx.org
* @license GNU/GPL v.2 http://www.gnu.org/licenses/old-licenses/gpl-2.0.html or, at your option, any later version
*/

// Dont allow direct linking
( defined('_JEXEC') || defined( '_VALID_MOS' ) ) or die( 'Direct Access to this location is not allowed.' );

define( '_AEC_LANGUAGE',						'en' ); // DO NOT CHANGE!!
define( '_CFG_GENERAL_ACTIVATE_PAID_NAME',		'Activate Paid Subscriptions' );
define( '_CFG_GENERAL_ACTIVATE_PAID_DESC',		'Always activate Subscriptions that are paid for automatically instead of requiring an activation code' );

// hacks/install >> ATTENTION: MUST BE HERE AT THIS POSITION, needed later!
define( '_AEC_SPEC_MENU_ENTRY',					'My Subscription' );

// common
define( '_DESCRIPTION_PAYSIGNET',				'mic: Description Paysignet - CHECK! -');
define( '_AEC_CONFIG_SAVED',					'Konfigurationen er gemt' );
define( '_AEC_CONFIG_CANCELLED',				'Konfiguration annulleret' );
define( '_AEC_TIP_NO_GROUP_PF_PB',				'Public Frontend er IKKE en brugergruppe - og det er Public Backend heller ikke' );
define( '_AEC_CGF_LINK_ABO_FRONTEND',			'Direkte frontend link' );
define( '_AEC_CGF_LINK_CART_FRONTEND',			'Direkte Tilf&oslash;j til kurv link' );
define( '_AEC_NOT_SET',							'Ikke sat' );
define( '_AEC_COUPON',							'Kupon' );
define( '_AEC_CMN_NEW',							'Ny' );
define( '_AEC_CMN_CLICK_TO_EDIT',				'Klik for at redigere' );
define( '_AEC_CMN_LIFETIME',					'Livstid' );
define( '_AEC_CMN_UNKOWN',						'Ukendt' );
define( '_AEC_CMN_EDIT_CANCELLED',				'&AElig;ndringer annulleret' );
define( '_AEC_CMN_PUBLISHED',					'Publiceret' );
define( '_AEC_CMN_NOT_PUBLISHED',				'Ikke publiceret' );
define( '_AEC_CMN_CLICK_TO_CHANGE',				'Klik p&aring; ikon for at &aelig;ndre tilstand' );
define( '_AEC_CMN_NONE_SELECTED',				'Ingen valgt' );
define( '_AEC_CMN_MADE_VISIBLE',				'gjort synlig' );
define( '_AEC_CMN_MADE_INVISIBLE',				'gjort usynlig' );
define( '_AEC_CMN_TOPUBLISH',					'publicer' );
define( '_AEC_CMN_TOUNPUBLISH',					'afpublicer' );
define( '_AEC_CMN_FILE_SAVED',					'Fil gemt' );
define( '_AEC_CMN_ID',							'ID' );
define( '_AEC_CMN_DATE',						'Dato' );
define( '_AEC_CMN_EVENT',						'Event' );
define( '_AEC_CMN_TAGS',						'Tags' );
define( '_AEC_CMN_ACTION',						'Handling' );
define( '_AEC_CMN_PARAMETER',					'Parameter' );
define( '_AEC_CMN_NONE',						'Ingen' );
define( '_AEC_CMN_WRITEABLE',					'Skrivbar' );
define( '_AEC_CMN_UNWRITEABLE',					'Ikke skrivbar!' );
define( '_AEC_CMN_UNWRITE_AFTER_SAVE',			'G&oslash;r skrivbar efter lagring' );
define( '_AEC_CMN_OVERRIDE_WRITE_PROT',			'Tilsides&aelig;t skrivebeskyttelse under lagring' );
define( '_AEC_CMN_NOT_SET',						'Ikke sat' );
define( '_AEC_CMN_SEARCH',						'S&oslash;g' );
define( '_AEC_CMN_APPLY',						'Anvend' );
define( '_AEC_CMN_STATUS',						'Status' );
define( '_AEC_FEATURE_NOT_ACTIVE',				'Denne funktion er endnu ikke aktiv' );
define( '_AEC_CMN_YES',							'Ja' );
define( '_AEC_CMN_NO',							'Nej' );
define( '_AEC_CMN_INHERIT',						'Nedarv' );
define( '_AEC_CMN_LANG_CONSTANT_IS_MISSING',	'Sprogkonstant <strong>%s</strong> mangler' );
define( '_AEC_CMN_VISIBLE',						'Synlig' );
define( '_AEC_CMN_INVISIBLE',					'Usynlig' );
define( '_AEC_CMN_EXCLUDED',					'Ekskluderet' );
define( '_AEC_CMN_PENDING',						'Afventer' );
define( '_AEC_CMN_ACTIVE',						'Aktiv' );
define( '_AEC_CMN_TRIAL',						'Pr&oslash;ve' );
define( '_AEC_CMN_CANCEL',						'Annulleret' );
define( '_AEC_CMN_HOLD',						'Hold' );
define( '_AEC_CMN_EXPIRED',						'Udl&oslash;bet' );
define( '_AEC_CMN_CLOSED',						'Lukket' );

// user(info)
define( '_AEC_USER_USER_INFO',					'Brugerinfo' );
define( '_AEC_USER_USERID',						'BrugerID' );
define( '_AEC_USER_STATUS',						'Status' );
define( '_AEC_USER_ACTIVE',						'Aktiv' );
define( '_AEC_USER_BLOCKED',					'Blokeret' );
define( '_AEC_USER_ACTIVE_LINK',				'Aktiveringslink' );
define( '_AEC_USER_PROFILE',					'Profil' );
define( '_AEC_USER_PROFILE_LINK',				'Vis profil' );
define( '_AEC_USER_USERNAME',					'Brugernavn' );
define( '_AEC_USER_NAME',						'Navn' );
define( '_AEC_USER_EMAIL',						'E-Mail' );
define( '_AEC_USER_SEND_MAIL',					'send email' );
define( '_AEC_USER_TYPE',						'Brugertype' );
define( '_AEC_USER_REGISTERED',					'Registreret' );
define( '_AEC_USER_LAST_VISIT',					'Seneste bes&oslash;g' );
define( '_AEC_USER_EXPIRATION',					'Udl&oslash;b' );
define( '_AEC_USER_CURR_EXPIRE_DATE',			'Aktuel udl&oslash;bsdato' );
define( '_AEC_USER_LIFETIME',					'Livstid' );
define( '_AEC_USER_RESET_EXP_DATE',				'Nulstil udl&oslash;bsdato' );
define( '_AEC_USER_RESET_STATUS',				'Nulstil status' );
define( '_AEC_USER_SUBSCRIPTION',				'Abonnement' );
define( '_AEC_USER_PAYMENT_PROC',				'Betalingsprocessor' );
define( '_AEC_USER_CURR_SUBSCR_PLAN',			'Aktueal abonnementsplan' );
define( '_AEC_USER_PREV_SUBSCR_PLAN',			'Forrige abonnementsplan' );
define( '_AEC_USER_USED_PLANS',					'Anvendt abonnementsplaner' );
define( '_AEC_USER_NO_PREV_PLANS',				'Endnu intet abonnement' );
define( '_AEC_USER_ASSIGN_TO_PLAN',				'Tilknyt til plan' );
define( '_AEC_USER_TIME',							'tidspunkt' );
define( '_AEC_USER_TIMES',						'tidspunkter' );
define( '_AEC_USER_INVOICES',					'Fakturaer' );
define( '_AEC_USER_NO_INVOICES',				'Endnu ingen fakturaer' );
define( '_AEC_USER_INVOICE_FACTORY',			'Faktura fabrik' );
define( '_AEC_USER_ALL_SUBSCRIPTIONS',			'Alle abonnementer for denne bruger' );
define( '_AEC_USER_ALL_SUBSCRIPTIONS_NOPE',	'Dette er det eneste abonnement som denne bruger pt har.' );
define( '_AEC_USER_ALL_SUBSCRIPTIONS_NONE',	'Denne bruger har endnu ikke noget abonnement.' );
define( '_AEC_USER_SUBSCRIPTIONS_ID',			'ID' );
define( '_AEC_USER_SUBSCRIPTIONS_STATUS',		'Status' );
define( '_AEC_USER_SUBSCRIPTIONS_PROCESSOR',	'Processor' );
define( '_AEC_USER_SUBSCRIPTIONS_SINGUP',		'Tilmeld' );
define( '_AEC_USER_SUBSCRIPTIONS_EXPIRATION',	'Udl&oslash;b' );
define( '_AEC_USER_SUBSCRIPTIONS_PRIMARY',		'prim&aelig;r' );
define( '_AEC_USER_CURR_SUBSCR_PLAN_PRIMARY',	'Prim&aelig;r' );
define( '_AEC_USER_COUPONS',					'Kuponner' );
define( '_HISTORY_COL_COUPON_CODE',				'Kuponkode' );
define( '_AEC_USER_NO_COUPONS',					'Ingen kuponbrug i poster' );
define( '_AEC_USER_MICRO_INTEGRATION',			'Micro integration info' );
define( '_AEC_USER_MICRO_INTEGRATION_USER',		'Frontend' );
define( '_AEC_USER_MICRO_INTEGRATION_ADMIN',	'Backend' );
define( '_AEC_USER_MICRO_INTEGRATION_DB',		'Database udl&aelig;sning' );

// new (additional)
define( '_AEC_MSG_MIS_NOT_DEFINED',				'Du har ikke defineret nogen integrationer - se konfig' );

// headers
define( '_AEC_HEAD_SETTINGS',					'Indstillinger' );
define( '_AEC_HEAD_HACKS',						'Hacks' );
define( '_AEC_HEAD_PLAN_INFO',					'Abonnementer' );
define( '_AEC_HEAD_LOG',						'Events log' );
define( '_AEC_HEAD_CSS_EDITOR',					'CSS editor' );
define( '_AEC_HEAD_MICRO_INTEGRATION',			'Micro itegration info' );
define( '_AEC_HEAD_ACTIVE_SUBS',				'Aktive abonnenter' );
define( '_AEC_HEAD_EXCLUDED_SUBS',				'Ekskluderede abonnenter' );
define( '_AEC_HEAD_EXPIRED_SUBS',				'Udl&oslash;bne abonnenter' );
define( '_AEC_HEAD_PENDING_SUBS',				'Afventende abonnenter' );
define( '_AEC_HEAD_CANCELLED_SUBS',				'Annullerede abonnenter' );
define( '_AEC_HEAD_HOLD_SUBS',					'Abonnenter p&aring; hold' );
define( '_AEC_HEAD_CLOSED_SUBS',				'Lukkede abonnenter' );
define( '_AEC_HEAD_MANUAL_SUBS',				'Ikke abonnenerende' );
define( '_AEC_HEAD_SUBCRIBER',					'Abonnent' );
define( '_AEC_HEAD_TOOLBOX',					'V&aelig;rkt&oslash;jer' );

// hacks
define( '_AEC_HACK_HACK',						'Hack' );
define( '_AEC_HACKS_ISHACKED',					'er hacket' );
define( '_AEC_HACKS_NOTHACKED',					'er ikke hacket!' );
define( '_AEC_HACKS_UNDO',						'fortryd nu' );
define( '_AEC_HACKS_COMMIT',					'bekr&aelig;ft' );
define( '_AEC_HACKS_FILE',						'Fil' );
define( '_AEC_HACKS_LOOKS_FOR',					'Hacket ser efter dette' );
define( '_AEC_HACKS_REPLACE_WITH',				'... og erstatte det med dette' );

define( '_AEC_HACKS_NOTICE',					'VIGTIG BESKED' );
define( '_AEC_HACKS_NOTICE_DESC',				'I nogle tilf&aelig;lde er det n&oslash;dvendigt at tilf&oslash;je hacks til nogle filer. For at g&oslash;re dette skal du klikke p&aring; linket "hack fil nu" for disse filer. Du kan ogs&aring; tilf&oslash;je et link til din brugermenu s&aring; at brugere kan se deres abonnementsoplysninger.' );
define( '_AEC_HACKS_NOTICE_DESC2',				'<strong>Alle funktionelt vigtige hacks er markeret med en pil og et udr&aring;bstegn.</strong>' );
define( '_AEC_HACKS_NOTICE_DESC3',				'Disse hacks er <strong>ikke n&oslash;dvendigivs i korrekt nummerisk orden</strong> - s&aring; lad v&aelig;re med at undre dig hvis de kommer som #1, #3, #6 - de manglende numre er h&oslash;jst sandsynligt bagudkompatible hacks som du kun ville se hvis du havde dem (fejlagtigt) tilf&oslash;jet.' );
define( '_AEC_HACKS_NOTICE_JACL',				'JACL BEM&AElig;RKNING' );
define( '_AEC_HACKS_NOTICE_JACL_DESC',			'I tilf&aelig;lde af at du planl&aelig;gger at installere komponenten JACLplus, s&aring; v&aelig;r venligst sikker p&aring; at disse hacks <strong>ikke er udf&oslash;rt</strong> n&aring;r du installerer den. JACL tilf&oslash;jer ogs&aring; hacks til kernefiler og det er vigtigt at vore hacks bliver tilf&oslash;jet efter dem fra JACL.' );

define( '_AEC_HACKS_MENU_ENTRY',				'Menuindf&oslash;rsel' );
define( '_AEC_HACKS_MENU_ENTRY_DESC',			'Tilf&oslash;jer en <strong>' . _AEC_SPEC_MENU_ENTRY . '</strong> menuindf&oslash;rsel til brugermenuen. Med denne, kan en bruger styre sine fakturaer og opgradere/forny sit abonnement.' );
define( '_AEC_HACKS_LEGACY',					'<strong>Dette er et bagudkompatibelt hack, fortryd venligst!</strong>' );
define( '_AEC_HACKS_LEGACY_MAMBOT',				'<strong>Dette er et bagudkompatibelt hack som er erstattet af Joomla 1.0 Mamboten, fortryd venligst og brug "Hacks Mambot" istedet!</strong>' );
define( '_AEC_HACKS_LEGACY_PLUGIN',				'<strong>Dette er et bagudkompatibelt hack som er erstattet af Joomla 1.5 programudvidelsen, fortryd venligst og brug programudvidelsen istedet!</strong>' );
define( '_AEC_HACKS_LEGACY_PLUGIN_ERROR',		'<strong>Dette er et bagudkompatibelt hack som er erstattet af Joomla 1.5 Fejl programudvidelse, fortryd venligst og brug AEC fejl programudvidelsen istedet!</strong>' );
define( '_AEC_HACKS_LEGACY_PLUGIN_USER',		'<strong>Dette er et bagudkompatibelt hack som er erstattet af Joomla 1.5 Bruger programudvidelsen, fortryd venligst og brug AEC bruger programudvidelsen istedet!</strong>' );
define( '_AEC_HACKS_LEGACY_PLUGIN_ACCESS',		'<strong>Dette er et bagudkompatibelt hack som er erstattet af Joomla 1.5 Adgangs programudvidelse, fortryd venligst og brug AEC adgangs programudvidelsen istedet!</strong>' );
define( '_AEC_HACKS_NOTAUTH',					'Dette vil linke dine brugere korrekt til siden IkkeTilladt med information omkring dine abonnementer' );
define( '_AEC_HACKS_SUB_REQUIRED',				'Dette sikrer at en brugere har et abonnement for at kunne logge p&aring;.<br /><strong>Husk ogs&aring; at s&aelig;tte AEC indstillingen [ Kr&aelig;v abonnement ] !</strong>' );
define( '_AEC_HACKS_REG2',						'Dette vil omdirigere eb registrerende bruger til betalingsplanen efter udfyldning af registreringsformularen. Lad dette v&aelig;re hvis du kun &oslash;nsker at man skal v&aelig;lge plan efter log p&aring; (hvis \'Kr&aelig;v abonnement\' er aktiv), eller fuldst&aelig;ndig frivilligt (uden krav om abonnement).' );
define( '_AEC_HACKS_REG3',						'Dette vil omdirigere brugeren til betalingsplanssiden n&aring;r han eller hun ikke har foretaget dette valg endnu.' );
define( '_AEC_HACKS_REG4',						'Dette hack vil transformere AEC variablerne fra brugerens detalje formular.' );
define( '_AEC_HACKS_REG5',						'Dette hack vil g&oslash;re planernes f&oslash;rste funktion mulig - du skal s&aelig;tte indstillingen for dette i indstillinger ogs&aring;!' );
define( '_AEC_HACKS_MI1',						'Nogle micro integrationer hviler p&aring; at modtage en rentekst adgangskode for hver bruger. Dette hack sikrer at micor integrationerne f&aring;r oplysninger n&aring;r en bruger &aelig;ndrer hans/hendes konto.' );
define( '_AEC_HACKS_MI2',						'Nogle micro integrationer hviler p&aring; at modtage en rentekst adgangskode for hver bruger. Dette hack sikrer at micor integrationerne f&aring;r oplysninger n&aring;r en bruger registrerer en konto.' );
define( '_AEC_HACKS_MI3',						'Nogle micro integrationer hviler p&aring; at modtage en rentekst adgangskode for hver bruger. Dette hack sikrer at micor integrationerne f&aring;r oplysninger n&aring;r en admin &aelig;ndrer en brugerkonto.' );
define( '_AEC_HACKS_CB2',						'Dette vil omdirigere en registrerende bruger til betalingsplanerne efter udfyldelse af registreringsformularen i CB. Lad dette v&aelig;re for kun at have plan valg ved log p&aring; (hvis \'Kr&aelig;v abonnement\' er aktiv), eller fuldst&aelig;ndig frivilligt (uden krav om abonnement). <strong>L&aelig;g venligst m&aelig;rke til at der er to efterf&oslash;lgende dette, n&aring;r du har tilf&oslash;jet det! Hvis du &oslash;nsker at have planerne f&oslash;r brugerdetaljerne, s&aring; er de ogs&aring; kr&aelig;vet.</strong>' );
define( '_AEC_HACKS_CB6',						'Dette vil omdirigere brugeren til betalingsplansiden n&aring;r han eller hun endnu ikke har foretaget dette valg.' );
define( '_AEC_HACKS_CB_HTML2',					'Dette hack vil overf&oslash;re AEC variablerne fra brugerdetaljeformularen. <strong>For at dette virker, s&aelig;t \'Planer f&oslash;rst\' i AEC indstillingerne.</strong>' );
define( '_AEC_HACKS_UHP2',						'UHP2 Menuindf&oslash;rsel' );
define( '_AEC_HACKS_UHP2_DESC',					'Tilf&oslash;jer en <strong>' . _AEC_SPEC_MENU_ENTRY . '</strong> menuindf&oslash;rsel til UHP2 brugermenuen. Med dette kan en bruger styre sine fakturaer og opgradere/forny hans eller hendes abonnementer.' );
define( '_AEC_HACKS_CBM',						'Hvis du bruger modulet Comprofiler Moderator, skal du tilf&oslash;je et hack for at forhindre problemet med uendelige l&oslash;kker.' );

define( '_AEC_HACKS_JUSER_HTML1',				'Dette vil omdirigere en registrerende bruger til betalingsplanssiden efter at have udfyldt registreringsformularen i JUser. Lad dette v&aelig;re hvis du kun &oslash;nsker valg af plan efter log p&aring; (hvis \'Kr&aelig;v abonnement\' er aktiv), eller fuldst&aelig;ndig frivillig (uden krav om abonnement). <strong>L&aelig;g venligst m&aelig;rke til at der er to efterf&oslash;lgende dette, n&aring;r du har tilf&oslash;jet det! Hvis du &oslash;nsker at have planerne f&oslash;r brugerdetaljerne, s&aring; er de ogs&aring; kr&aelig;vet.</strong>' );
define( '_AEC_HACKS_JUSER_PHP1',				'Dette vil omdirigere brugeren til betalingsplanssiden n&aring;r han eller hun endnu ikke har foretaget dette valg.' );
define( '_AEC_HACKS_JUSER_PHP2',				'Dette er en fejlrettelse som tillader AEC at loade JUser funktionerne uden at tvinge det til at reagere p&aring; POST data.' );

// log
	// settings
define( '_AEC_LOG_SH_SETT_SAVED',				'indstillinger &aelig;ndrer' );
define( '_AEC_LOG_LO_SETT_SAVED',				'Indstillingerne for AEC er blevet gemt, &aelig;ndringer:' );
	// heartbeat
define( '_AEC_LOG_SH_HEARTBEAT',				'Hjerteslag' );
define( '_AEC_LOG_LO_HEARTBEAT',				'Hjerteslag udf&oslash;rt:' );
define( '_AEC_LOG_AD_HEARTBEAT_DO_NOTHING',		'g&oslash;r ingenting' );
	// install
define( '_AEC_LOG_SH_INST',						'AEC installation' );
define( '_AEC_LOG_LO_INST',						'AEC version %s er blevet installeret. Velkommen til vores nye version af komponenten Account Expiration Control!' );

// install texts
define( '_AEC_INST_NOTE_IMPORTANT',				'Vigtig besked' );
define( '_AEC_INST_NOTE_SECURITY',				'For visse funktioner, skal du m&aring;ske tilf&oslash;je hacks til andre komponenter. For at g&oslash;re det let for dig, har vi inkluderet en autohacking funktion som g&oslash;r dette ved et enkelt klik p&aring; et link' );
define( '_AEC_INST_APPLY_HACKS',				'For at tilf&oslash;je disse hacks lige nu, g&aring; til %s. (Du kan ogs&aring; tilg&aring; denne side senere fra AEC centralvisningen eller menuen)' );
define( '_AEC_INST_APPLY_HACKS_LTEXT',			'hack side' );
define( '_AEC_INST_NOTE_UPGRADE',				'<strong>Hvis du opgraderer, v&aelig;r sikker p&aring; ogs&aring; at tjekke hack siden, da der sker &aelig;ndringer fra tid til anden!!!</strong>' );
define( '_AEC_INST_NOTE_HELP',					'For at hj&aelig;lpe dig p&aring; vej med almindelige problemstillinger, har vi oprettet en %s som vil hj&aelig;lpe dig omkring de mest almindelig installationsproblemer (adgang er ogs&aring; mulig via AEC centralen).' );
define( '_AEC_INST_NOTE_HELP_LTEXT',			'hj&aelig;lpefunktion' );
define( '_AEC_INST_HINTS',						'Hints' );
define( '_AEC_INST_HINT1',						'Vi opfordrer dig til at bes&oslash;ge <a href="%s" target="_blank">valanx.org fora</a> og <strong>deltage i diskussionerne der</strong>. Der er store chance for at andre brugere har fundet den samme fejl og det er liges&aring; sandsynligt at der i det mindste er en l&oslash;sning til at tilf&oslash;je som hack eller endda en ny version.' );
define( '_AEC_INST_HINT2',						'I hvert tilf&aelig;lde (og specielt hvis du bruger dette p&aring; et live websted): <strong>gennemg&aring; dine indstillinger lav et testabonnement</strong> for at se om alt virker som det skal! Selvom vi g&oslash;r vort bedste for at opgradering skal v&aelig;re s&aring; let som mulig, s&aring; vil nogle fundamentale &aelig;ndringer af vort program m&aring;ske ikke v&aelig;re tilg&aelig;ngelige for alle brugere.</p><p><strong>Tak fordi du valgte komponenten AEC!</strong></p>' );
define( '_AEC_INST_ATTENTION',					'Ingen grund til at bruge de gamle log p&aring;!' );
define( '_AEC_INST_ATTENTION1',					'Hvis du stadig har det gamle modul AEC log p&aring; installeret, s&aring; afinstaller det venligst (uanset hvilket, regul&aelig;r eller CB) og brug det normale Joomla eller CB log p&aring; modul. Der er ingen grund til at anvende disse gamle moduler l&aelig;ngere.' );
define( '_AEC_INST_MAIN_COMP_ENTRY',			'AEC Medlemskab' );
define( '_AEC_INST_ERRORS',						'<strong>Advarsel</strong><br />AEC kunne ikke installeres fuldst&aelig;ndig, f&oslash;lgende fejl opstod under installationsproceduren:<br />' );

define( '_AEC_INST_ROOT_GROUP_NAME',			'Rod' );
define( '_AEC_INST_ROOT_GROUP_DESC',			'Rod gruppe. Denne indf&oslash;rsel kan ikke slettes, &aelig;ndringer er begr&aelig;nset.' );

// help
define( '_AEC_CMN_HELP',						'Hj&aelig;lp' );
define( '_AEC_HELP_DESC',						'P&aring; denne side, tager AEC takes et kig p&aring; sin egen konfiguration og fort&aelig;ller n&aring;r den finder fejlsom skal rettes.' );
define( '_AEC_HELP_GREEN',						'Gr&oslash;nne</strong> elementer indikerer trivielle problemer eller beskeder, eller problemer som allerede er blevet l&oslash;st.' );
define( '_AEC_HELP_YELLOW',						'Gule</strong> elementer er oftest af kosmetisk betydning (s&aring;som tilf&oslash;jelser til bruger frontend), men h&oslash;jst sandsynligt ogs&aring; problemer der skyldes et bevidst valg fra dministratoren.' );
define( '_AEC_HELP_RED',						'R&oslash;de</strong> elementer er af h&oslash;j vigtighed i forhold til enten m&aring;den AEC virker p&aring; eller sikkerheden i dit system.' );
define( '_AEC_HELP_GEN',						'L&aelig;g venligst m&aelig;rke til ar selvom vi fors&oslash;ger at d&aelig;kke s&aring; mange fejl og problemer som muligt, s&aring; kan denne side kun henvise til de mest &oslash;jensynlige og er langtfra komplet (beta&trade;)' );
define( '_AEC_HELP_QS_HEADER',					'AEC hurtigstart manual' );
define( '_AEC_HELP_QS_DESC',					'Inden du g&oslash;r noget ved problemet nedenfor s&aring; l&aelig;s venligst vores %s!' );
define( '_AEC_HELP_QS_DESC_LTEXT',				'Hurtigstart manual' );
define( '_AEC_HELP_SER_SW_DIAG1',				'Filrettighedsproblemer' );
define( '_AEC_HELP_SER_SW_DIAG1_DESC',			'AEC har opdaget at du bruger en Apache Webserver - For at kunne tilf&oslash;je hacks til filer p&aring; s&aring;dan en server, s&aring; skal disse filer v&aelig;re ejet af webserver brugeren, som &aring;benbart ikke er tilf&aelig;ldet for mindst en af de n&oslash;dvendige filer. <strong>Hvis hacksene allerede virker fint, s&aring; kan du ignorere dette!</strong>' );
define( '_AEC_HELP_SER_SW_DIAG1_DESC2',			'Vi anbefaler at du midlertidigt &aelig;ndrer filtilladelserne til 777, derefter tilf&oslash;jer hacksene og s&aring; &aelig;ndre tilladelserne tilbage igen. <strong>Kontakt din server host eller administrator for den om muligt hurtigste respons n&aring;r du oplever problemer.</strong> Dette er det samme for til rettigheds relaterede forslag nedenunder.' );
define( '_AEC_HELP_SER_SW_DIAG2',				'joomla.php/mambo.php Fil tilladelser' );
define( '_AEC_HELP_SER_SW_DIAG2_DESC',			'AEC har detekteret at din joomla.php ikke ejes af webserveren.' );
define( '_AEC_HELP_SER_SW_DIAG2_DESC2',			'Tilg&aring; din webserver via ssh og g&aring; til mappen \"<rodenp&aring;ditwebsted>/includes\". Her skal du indtaste kommandoen: \"chown wwwrun joomla.php\" (eller \"chown wwwrun mambo.php\" hvis du bruger mambo). <strong>Hvis hacksene allerede virker, s&aring; kan du ignorere dette!</strong>' );
define( '_AEC_HELP_SER_SW_DIAG3',				'Bagudkompatible hacks detekteret!' );
define( '_AEC_HELP_SER_SW_DIAG3_DESC',			'Det ser ud til at du har nogle gamle hacks tilf&oslash;jet til dit system.' );
define( '_AEC_HELP_SER_SW_DIAG3_DESC2',			'For at AEC kan fungere korrekt, s&aring; gennemse venligst hacks siden og f&oslash;lg skridtene der bliver anvist der.' );
define( '_AEC_HELP_SER_SW_DIAG4',				'Fil rettighedsproblemer' );
define( '_AEC_HELP_SER_SW_DIAG4_DESC',			'AEC ikke detektere skrive rettighedsstatus p&aring; filerne som den &oslash;nsker til tilf&oslash;je hacks til, da det ser ud til at din installation af php er blevet kompileret med indstilligen \"--disable-posix\". <strong>Du kan stadig fors&oslash;ge at tilf&oslash;je hacks - hvis det ikke virker, s&aring; er det h&aelig;jst sandsynligt et sp&oslash;rgsm&aring;l om fil rettigheder</strong>. <strong>Hvis hacksene allerede virker fint, s&aring; kan du ignorere dette!</strong>' );
define( '_AEC_HELP_SER_SW_DIAG4_DESC2',			'Vi anbefaler at du enten rekompilerer din php version med den n&aelig;vnte indstilling udeladt eller kontakter din webserver administrator ang&aring;ende dette problem.' );
define( '_AEC_HELP_DIAG_CMN1',					'joomla.php/mambo.php hack' );
define( '_AEC_HELP_DIAG_CMN1_DESC',				'For at AEC kan fungere, s&aring; er dette hack p&aring;kr&aelig;vet for at omdirigere brugere til AEC verificerings rutiner ved log p&aring;.' );
define( '_AEC_HELP_DIAG_CMN1_DESC2',			'G&aring; til hacks siden og tilf&oslash;j hacket' );
define( '_AEC_HELP_DIAG_CMN2',					'"Mit medlemskab" Menuindf&oslash;rsel' );
define( '_AEC_HELP_DIAG_CMN2_DESC',				'Et link til en MitMedlemskabsside for dine brugere g&oslash;r det let for dem at overskue deres medlemskab.' );
define( '_AEC_HELP_DIAG_CMN2_DESC2',			'G&aring; til hacks siden og opret et link.' );
define( '_AEC_HELP_DIAG_CMN3',					'JACL ikke installeret' );
define( '_AEC_HELP_DIAG_CMN3_DESC',				'Hvis du planl&aelig;gger at installere JACLplus p&aring; dit joomla!/mambo system, s&aring; v&aelig;r venligst sikker p&aring; at AEC hacks ikke er tilf&oslash;jet n&aring;r du g&oslash;r dette. Hvis du allerede har tilf&oslash;jet dem, s&aring; kan du let fjerne dem igen p&aring; vores hacks side.' );
define( '_AEC_HELP_DIAG_NO_PAY_PLAN',			'Ingen aktiv betalingsplan!' );
define( '_AEC_HELP_DIAG_NO_PAY_PLAN_DESC',		'Det ser ud til at der ikke er publiceret en betalingsplan endnu - AEC skal bruge miondst en aktiv plan for at fungere' );
define( '_AEC_HELP_DIAG_GLOBAL_PLAN',			'Global indgangsplan' );
define( '_AEC_HELP_DIAG_GLOBAL_PLAN_DESC',		'Der er en aktiv global indgangsplan i din konfiguration. Hvis du ikke er sikker p&aring; hvad dette er, s&aring; b&oslash;r du formentlig deaktivere den - Det er en indgangsplan som hver ny bruger vil blive tilknyttet til uden at f&aring; valget.' );
define( '_AEC_HELP_DIAG_SERVER_NOT_REACHABLE',	'Server er &aring;benbart ikke kontaktbar' );
define( '_AEC_HELP_DIAG_SERVER_NOT_REACHABLE_DESC',	'Det ser ud til at du har installeret AEC p&aring; en lokal maskine. For at hente notifikationer (og deraf f&aring; komponenten til at fungere korrekt), skal du installere det p&aring; en server som er tilg&aelig;ngelig via fast IP eller dom&aelig;ne!' );
define( '_AEC_HELP_DIAG_SITE_OFFLINE',			'Websted offline' );
define( '_AEC_HELP_DIAG_SITE_OFFLINE_DESC',		'Du har besluttet at s&aelig;tte dit websted offline - bem&aelig;rk venligst at dette kan have en effekt p&aring; notifikations processer og deraf p&aring; betalingsflowet.' );
define( '_AEC_HELP_DIAG_REG_DISABLED',			'Brugerregistrering deaktiveret' );
define( '_AEC_HELP_DIAG_REG_DISABLED_DESC',		'Din brugerregistrering er deaktiveret. Det betyder, at ingen nye kunder kan tegne medlemskab p&aring; dit websted.' );
define( '_AEC_HELP_DIAG_LOGIN_DISABLED',		'Bruger log p&aring; deaktiveret' );
define( '_AEC_HELP_DIAG_LOGIN_DISABLED_DESC',	'Du har deaktiveret frontend log p&aring; funktionaliteten. Derfor kan ingen brugere anvende dit websted.' );
define( '_AEC_HELP_DIAG_PAYPAL_BUSS_ID',		'Paypal kontrol forretnings ID' );
define( '_AEC_HELP_DIAG_PAYPAL_BUSS_ID_DESC',	'Denne rutine kontrollerer for matchende paypal forretnings ID for at udvide sikkerheden ved Paypal transaktioner.' );
define( '_AEC_HELP_DIAG_PAYPAL_BUSS_ID_DESC1',	'Deaktiver venligst denne indstilling hvis du st&oslash;der p&aring; problemer n&aring;r du korrekt modtager betalinger, men uden at brugeren bliver aktiveret. Deaktiver generelt indstillingen hvis du bruger multiple e-mail adresser sammen med din Paypal konto.' );

// micro integration
	// general
define( '_AEC_MI_REWRITING_INFO',				'Omskrivnings info' );
define( '_AEC_MI_SET1_INAME',					'Medlemskab hos %s - Bruger: %s (%s)' );
	// htaccess
define( '_AEC_MI_HTACCESS_INFO_DESC',			'Beskyt en mappe med en .htaccess fil og tillad brugere med dette medlemskab at tilg&aring; den med deres joomla konto detaljer.' );
	// email
define( '_AEC_MI_EMAIL_INFO_DESC',				'Send en email til en eller flere adresser ved oprettelse eller udl&oslash;b af medlemskabet' );
	// idev
define( '_AEC_MI_IDEV_DESC',					'Forbind dine salg til iDevAffiliate' );
	// mosetstree
define( '_AEC_MI_MOSETSTREE_DESC',				'Begr&aelig;ns antallet af listninger som en bruger kan publicere' );
	// mysql-query
define( '_AEC_MI_MYSQL_DESC',					'Angiv en mySQL foresp&oslash;rgsel som skal udf&oslash;res med dette medlemskab eller ved dets udl&oslash;b' );
	// remository
define( '_AEC_MI_REMOSITORY_DESC',				'V&aelig;lg antallet af filer som en brug kan downloade og hvilken reMOSitory gruppe der skal tilknyttes til brugerens konto' );
	// VirtueMart
define( '_AEC_MI_VIRTUEMART_DESC',				'V&aelig;lg VirtueMart brugergruppen som denne bruger skal tilf&oslash;jes til' );

// central
define( '_AEC_CENTR_CENTRAL',					'AEC Central' );
define( '_AEC_CENTR_EXCLUDED',					'Ekskluderet' );
define( '_AEC_CENTR_PLANS',						'Planer' );
define( '_AEC_CENTR_PENDING',					'Afventer' );
define( '_AEC_CENTR_ACTIVE',					'Aktiv' );
define( '_AEC_CENTR_EXPIRED',					'Udl&oslash;bet' );
define( '_AEC_CENTR_CANCELLED',					'Annulleret' );
define( '_AEC_CENTR_HOLD',						'P&aring; hold' );
define( '_AEC_CENTR_CLOSED',					'Lukket' );
define( '_AEC_CENTR_PROCESSORS',				'Processorer' );
define( '_AEC_CENTR_SETTINGS',					'Indstillinger' );
define( '_AEC_CENTR_EDIT_CSS',					'Rediger CSS' );
define( '_AEC_CENTR_V_INVOICES',				'Vis fakturaer' );
define( '_AEC_CENTR_COUPONS',					'Kuponner' );
define( '_AEC_CENTR_COUPONS_STATIC',			'Statiske kuponner' );
define( '_AEC_CENTR_VIEW_HISTORY',				'Vis historik' );
define( '_AEC_CENTR_HACKS',						'Hacks' );
define( '_AEC_CENTR_M_INTEGRATION',				'Micro Integr.' );
define( '_AEC_CENTR_HELP',						'Hj&aelig;lp' );
define( '_AEC_CENTR_TOOLBOX',					'V&aelig;rkt&oslash;jer' );
define( '_AEC_CENTR_LOG',						'EventLog' );
define( '_AEC_CENTR_MANUAL',					'Ikke medlemmer' );
define( '_AEC_CENTR_EXPORT',					'Eksport' );
define( '_AEC_CENTR_IMPORT',					'Import' );
define( '_AEC_CENTR_GROUPS',					'Grupper' );
define( '_AEC_CENTR_AREA_MEMBERSHIPS',			'Medlemskaber' );
define( '_AEC_CENTR_AREA_PAYMENT',				'Betalingsplaner &amp; relateret funktionalitet' );
define( '_AEC_CENTR_AREA_SETTINGS',				'Indstillinger, Logs &amp; speciel funktionalitet' );
define( '_AEC_QUICKSEARCH',						'Hurtig s&oslash;gning' );
define( '_AEC_QUICKSEARCH_DESC',				'Angiv en brugers navn, brugernavn, email adresse, brugerid eller et fakturanummer for at blivet linket direkte til brugerens profil. Hvis der er flere end et resultat, vil de vises nedenunder.' );
define( '_AEC_QUICKSEARCH_MULTIRES',			'Multiple resultater!' );
define( '_AEC_QUICKSEARCH_MULTIRES_DESC',		'Vlkg venligst en af f&oslash;lgende brugere:' );
define( '_AEC_QUICKSEARCH_THANKS',				'Tak fordi du g&oslash;r en simpel funktion meget glad.' );
define( '_AEC_QUICKSEARCH_NOTFOUND',			'Bruger ikke fundet' );

define( '_AEC_NOTICES_FOUND',					'Eventlog bem&aelig;rkninger' );
define( '_AEC_NOTICES_FOUND_DESC',				'De f&oslash;lgende indf&oslash;rsler i eventloggen kr&aelig;ver din opm&aelig;rksomhed. Du kan markere dem som l&aelig;st hvis du &oslash;nsker at de skal forsvinde. Du kan ogs&aring; &aelig;ndre typen af bem&aelig;rkninger der vises her, i indstillingerne.' );
define( '_AEC_NOTICE_MARK_READ',				'marker som l&aelig;st' );
define( '_AEC_NOTICE_MARK_ALL_READ',			'Marker alle bem&aelig;rkninger som l&aelig;st' );
define( '_AEC_NOTICE_NUMBER_1',					'Event' );
define( '_AEC_NOTICE_NUMBER_2',					'Event' );
define( '_AEC_NOTICE_NUMBER_8',					'Bem&aelig;rkning' );
define( '_AEC_NOTICE_NUMBER_32',				'Advarsel' );
define( '_AEC_NOTICE_NUMBER_128',				'Fejl' );
define( '_AEC_NOTICE_NUMBER_512',				'Ingen' );

// select lists
define( '_AEC_SEL_EXCLUDED',					'Ekskluderet' );
define( '_AEC_SEL_PENDING',						'Afventer' );
define( '_AEC_SEL_TRIAL',						'Pr&oslash;ve' );
define( '_AEC_SEL_ACTIVE',						'Aktiv' );
define( '_AEC_SEL_EXPIRED',						'Udl&oslash;bet' );
define( '_AEC_SEL_CLOSED',						'Lukket' );
define( '_AEC_SEL_CANCELLED',					'Annulleret' );
define( '_AEC_SEL_HOLD',						'P&aring; hold' );
define( '_AEC_SEL_NOT_CONFIGURED',				'Ikke meldem' );

// footer
define( '_AEC_FOOT_TX_CHOOSING',				'Tak for dit valg af komponenten Account Expiration Control!' );
define( '_AEC_FOOT_TX_GPL',						'Denne Joomla komponent blev udviklet og udgivet under <a href="http://www.gnu.org/copyleft/gpl.html" target="_blank">GNU/GPL</a> licens af David Deutsch & Team AEC fra <a href="http://www.valanx.org" target="_blank">valanx.org</a>' );
define( '_AEC_FOOT_TX_SUBSCRIBE',				'Hvis du &oslash;nsker flere funktioner, professionelle ydelser, opdateringer, manualer og online hj&aelig;lp til denne komponent, s&aring; kan du tilmelde dig til vores services p&aring; det ovenst&aring;ende link. Det hj&aelig;lper os meget i vores udvikling!' );
define( '_AEC_FOOT_CREDIT',						'L&aelig;s venligst vores %s' );
define( '_AEC_FOOT_CREDIT_LTEXT',				'fulde krediteringer' );
define( '_AEC_FOOT_VERSION_CHECK',				'Kontroller for seneste version' );
define( '_AEC_FOOT_MEMBERSHIP',					'F&aring; et medlemskab med dokumentation og support' );

// alerts
define( '_AEC_ALERT_SELECT_FIRST',				'V&aelig;lg et element der skal konfigureres' );
define( '_AEC_ALERT_SELECT_FIRST_TO',			'V&aelig;lg venligst f&oslash;rst et element til at' );

// messages
define( '_AEC_MSG_NODELETE_SUPERADMIN',			'Du kan ikke slette en Super Administrator' );
define( '_AEC_MSG_NODELETE_YOURSELF',			'Du kan ikke slette dig selv!' );
define( '_AEC_MSG_NODELETE_EXCEPT_SUPERADMIN',	'Kun Superadmins kan udf&oslash;re denne handling!' );
define( '_AEC_MSG_SUBS_RENEWED',				'medlemskab(er) fornyet' );
define( '_AEC_MSG_SUBS_ACTIVATED',				'Medlemskab(er) aktiveret' );
define( '_AEC_MSG_NO_ITEMS_TO_DELETE',			'Intet element fundet til sletning' );
define( '_AEC_MSG_NO_DEL_W_ACTIVE_SUBSCRIBER',	'Du kan ikke slette planer med aktive medlemmer' );
define( '_AEC_MSG_ITEMS_DELETED',				'Element(er) slettet' );
define( '_AEC_MSG_ITEMS_SUCESSFULLY',			'%s element(er)' );
define( '_AEC_MSG_SUCESSFULLY_SAVED',			'&AElig;ndringer gemt' );
define( '_AEC_MSG_ITEMS_SUCC_PUBLISHED',		'Element(er) publiceret' );
define( '_AEC_MSG_ITEMS_SUCC_UNPUBLISHED',		'Element(er) afpubliceret' );
define( '_AEC_MSG_NO_COUPON_CODE',				'Du skal angive en kupon kode!' );
define( '_AEC_MSG_OP_FAILED',					'Operation fejlede: Kunne ikke &aring;bne %s' );
define( '_AEC_MSG_OP_FAILED_EMPTY',				'Operation fejlede: Indhold tomt' );
define( '_AEC_MSG_OP_FAILED_NOT_WRITEABLE',		'Operation fejlede: Filen er ikke skrivbar.' );
define( '_AEC_MSG_OP_FAILED_NO_WRITE',			'Operation fejlede: Kunne ikke &aring;bne filen for skrivning' );
define( '_AEC_MSG_INVOICE_CLEARED',				'Faktura renset' );

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
define( '_AEC_LANG_DK', 'Danmark' );
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

// --== BACKEND TOOLBAR ==--
define( '_EXPIRE_SET','S&aelig;t udl&oslash;bstidspunkt:');
define( '_EXPIRE_NOW','Udl&oslash;b');
define( '_EXPIRE_EXCLUDE','Ekskluder');
define( '_EXPIRE_INCLUDE','Aktiv/Inkluder');
define( '_EXPIRE_CLOSE','Luk');
define( '_EXPIRE_HOLD','P&aring; hold');
define( '_EXPIRE_01MONTH','s&aelig;t 1 m&aring;ned');
define( '_EXPIRE_03MONTH','s&aelig;t 3 mpneder');
define( '_EXPIRE_12MONTH','s&aelig;t 12 m&aring;neder');
define( '_EXPIRE_ADD01MONTH','tilf&oslash;j 1 m&aring;ned');
define( '_EXPIRE_ADD03MONTH','tilf&oslash;j 3 m&aring;neder');
define( '_EXPIRE_ADD12MONTH','tilf&oslash;j 12 m&aring;neder');
define( '_CONFIGURE','Konfigurer');
define( '_REMOVE','Ekskluder');
define( '_CNAME','Navn');
define( '_USERLOGIN','Log p&aring;');
define( '_EXPIRATION','Udl&oslash;b');
define( '_USERS','Brugere');
define( '_DISPLAY','Visning');
define( '_NOTSET','Ekskluder');
define( '_SAVE','Gem');
define( '_CANCEL','Annuller');
define( '_EXP_ASC','Udl&oslash;b stigende');
define( '_EXP_DESC','Udl&oslash;b faldende');
define( '_NAME_ASC','Navn stigende');
define( '_NAME_DESC','Navn faldende');
define( '_LASTNAME_ASC','Efternavn stigende');
define( '_LASTNAME_DESC','Efternavn faldende');
define( '_LOGIN_ASC','Log p&aring; stigende');
define( '_LOGIN_DESC','Log p&aring; faldende');
define( '_SIGNUP_ASC','Tilmeldelsesdato stigende');
define( '_SIGNUP_DESC','Tilmeldelsesdato faldende');
define( '_LASTPAY_ASC','Seneste bateling stigende');
define( '_LASTPAY_DESC','Seneste betaling faldende');
define( '_PLAN_ASC','Plan stigende');
define( '_PLAN_DESC','Plan faldende');
define( '_STATUS_ASC','Status stigende');
define( '_STATUS_DESC','Status faldende');
define( '_TYPE_ASC','Betalingstype stigende');
define( '_TYPE_DESC','Betalingstype faldende');
define( '_ORDERING_ASC','Sortering stigende');
define( '_ORDERING_DESC','Sortering faldende');
define( '_ID_ASC','ID stigende');
define( '_ID_DESC','ID faldende');
define( '_CLASSNAME_ASC','Funktionsnavn stigende');
define( '_CLASSNAME_DESC','Funktionsnavn faldende');
define( '_ORDER_BY','Sorter efter:');
define( '_SAVED', 'Gemt.');
define( '_CANCELED', 'Annulleret.');
define( '_CONFIGURED', 'Element konfigureret.');
define( '_REMOVED', 'Elementer fjernet fra listen.');
define( '_EOT_TITLE', 'Lukkede medlemskaber');
define( '_EOT_DESC', 'Denne liste inkludere ikke manuelt satte medlemskaber, kun de der er gennemf&oslash;rt via betalingsgateway. N&aring;r du sletter en indf&oslash;rsel, s&aring; fjernes brugeren fra databasen og alt relateret aktivitetet slettes fra historikken.');
define( '_EOT_DATE', 'Dato for slut p&aring; Term');
define( '_EOT_CAUSE', 'Grund');
define( '_EOT_CAUSE_FAIL', 'Betalingsfejl');
define( '_EOT_CAUSE_BUYER', 'Annuleret af bruger');
define( '_EOT_CAUSE_FORCED', 'Annulleret af administrator');
define( '_REMOVE_CLOSED', 'Slet');
define( '_FORCE_CLOSE', 'Luk nu');
define( '_PUBLISH_PAYPLAN', 'Publicer');
define( '_UNPUBLISH_PAYPLAN', 'Afpublicer');
define( '_NEW_PAYPLAN', 'Ny');
define( '_COPY_PAYPLAN', 'Kopier');
define( '_APPLY_PAYPLAN', 'Anvend');
define( '_EDIT_PAYPLAN', 'Rediger');
define( '_REMOVE_PAYPLAN', 'Slet');
define( '_SAVE_PAYPLAN', 'Gem');
define( '_CANCEL_PAYPLAN', 'Annuller');
define( '_PAYPLANS_TITLE', 'Betalingsplaner');
define( '_PAYPLANS_MAINDESC', 'Publiverede planer vil v&aelig;re muligheder for brugeren i frontend. Disse planer er kun gyildige for gateway betalinger.');
define( '_PAYPLAN_GROUP', 'Gruppe');
define( '_PAYPLAN_NOGROUP', 'Ingen gruppe');
define( '_PAYPLAN_NAME', 'Navn');
define( '_PAYPLAN_DESC', 'Beskrivelse (f&oslash;rste 50 karakterer)');
define( '_PAYPLAN_ACTIVE', 'Publiceret');
define( '_PAYPLAN_VISIBLE', 'Synlig');
define( '_PAYPLAN_A3', 'Rate');
define( '_PAYPLAN_P3', 'Periode');
define( '_PAYPLAN_T3', 'Periode enhed');
define( '_PAYPLAN_USERCOUNT', 'Medlemmer');
define( '_PAYPLAN_EXPIREDCOUNT', 'Udl&oslash;bet');
define( '_PAYPLAN_TOTALCOUNT', 'Total');
define( '_PAYPLAN_REORDER', 'Gensorter');
define( '_PAYPLAN_DETAIL', 'Indstillinger');
define( '_ALTERNATIVE_PAYMENT', 'Bankoverf&oslash;rsel');
define( '_SUBSCR_DATE', 'Dato for tegning af medlemskab');
define( '_ACTIVE_TITLE', 'Aktive medlemskaber');
define( '_ACTIVE_DESC', 'Denne liste inkludere ikke manuelt oprettede medlemskaber, kun medlemskaber der er proccesseret igennem betaligsgateway.');
define( '_LASTPAY_DATE', 'Sidste betalingsdato');
define( '_USERPLAN', 'Plan');
define( '_CANCELLED_TITLE', 'Annulerede medlemskaber');
define( '_CANCELLED_DESC', 'Denne liste inkluderer ikke manuelt oprettede medlemskaber, kun medlemskaber der er oprettet gennem betaligsgateway. Dette er medlemskaberne der er annulleret af brugere, men som ikke er udl&oslash;bet endnu.');
define( '_CANCEL_DATE', 'Annulleringsdato');
define( '_MANUAL_DESC', 'N&aring;r du sletter en indf&oslash;rsel, s&aring; bliver brugeren fuldst&aelig;ndig fjernet fra databasen.');
define( '_REPEND_ACTIVE', 'Gen-afvent');
define( '_FILTER_PLAN', '- V&aelig;lg plan -');
define( '_BIND_USER', 'Tilknyt til:');
define( '_PLAN_FILTER','Filtrer efter plan:');
define( '_CENTRAL_PAGE','Central');

// --== USER FORM ==--
define( '_HISTORY_COL_INVOICE', 'Faktura');
define( '_HISTORY_COL_AMOUNT', 'Bel&oslash;b');
define( '_HISTORY_COL_DATE', 'Betalingsdato');
define( '_HISTORY_COL_METHOD', 'Metode');
define( '_HISTORY_COL_ACTION', 'Handling');
define( '_HISTORY_COL_PLAN', 'Plan');
define( '_USERINVOICE_ACTION_REPEAT','gentag&nbsp;link');
define( '_USERINVOICE_ACTION_CANCEL','annuller');
define( '_USERINVOICE_ACTION_CLEAR','marker&nbsp;ryddet');
define( '_USERINVOICE_ACTION_CLEAR_APPLY','ryd&nbsp;&amp;&nbsp;anvend&nbsp;Plan');

// --== BACKEND SETTINGS ==--

// TAB 1 - Global AEC Settings
define( '_CFG_TAB1_TITLE', 'Global');
define( '_CFG_TAB1_SUBTITLE', 'Generel konfiguration');

define( '_CFG_GENERAL_SUB_ACCESS', 'Adgang');
define( '_CFG_GENERAL_SUB_SYSTEM', 'System');
define( '_CFG_GENERAL_SUB_EMAIL', 'Email');
define( '_CFG_GENERAL_SUB_DEBUG', 'Fejls&oslash;gning');
define( '_CFG_GENERAL_SUB_REGFLOW', 'Registreringsflow');
define( '_CFG_GENERAL_SUB_PLANS', 'Medlems planer');
define( '_CFG_GENERAL_SUB_CONFIRMATION', 'Bekr&aelig;ftelsesside');
define( '_CFG_GENERAL_SUB_CHECKOUT', 'Betalingsside');
define( '_CFG_GENERAL_SUB_PROCESSORS', 'Betalings processorer');
define( '_CFG_GENERAL_SUB_SECURITY', 'Sikkerhed');

define( '_CFG_GENERAL_ALERTLEVEL2_NAME', 'Alarm niveau 2:');
define( '_CFG_GENERAL_ALERTLEVEL2_DESC', 'I dage. Dette er f&oslash;rst t&aelig;rskel for at starte med at advare brugeren om at hans medlemskab er ved at udl&oslash;be. <strong>Dette udsender ikke en email!</strong>');
define( '_CFG_GENERAL_ALERTLEVEL1_NAME', 'Alarm niveau 1:');
define( '_CFG_GENERAL_ALERTLEVEL1_DESC', 'I dage. Dette er sidste t&aelig;rskel for advarsel til brugeren om at hans medlemskab er ved at udl&oslash;be. Dette b&oslash;r v&aelig;re det t&aelig;tteste interval til udl&oslash;bsdatoen. <strong>Dette udsender ikke en email!</strong>');
define( '_CFG_GENERAL_ENTRY_PLAN_NAME', 'Tillad indgangsplan:');
define( '_CFG_GENERAL_ENTRY_PLAN_DESC', 'Hver bruger vil blive tilknyttet til denne plan (ingen betaling!) n&aring;r brugeren endnu ikke har et medlemskab');
define( '_CFG_GENERAL_REQUIRE_SUBSCRIPTION_NAME', 'Kr&aelig;v medlemskab:');
define( '_CFG_GENERAL_REQUIRE_SUBSCRIPTION_DESC', 'Hvis aktiveret, s&aring; SKAL en bruger have et medlemskab. Hvis deaktiveret, s&aring; vil brugere kunne logge p&aring; uden medlemskab.');

define( '_CFG_GENERAL_GWLIST_NAME', 'Gateway beskrivelser:');
define( '_CFG_GENERAL_GWLIST_DESC', 'List de Gateways du &oslash;nsker at beskrive p&aring; siden IkkeTilladt (som dine kunder ser n&aring;r de pr&oslash;ver at tilg&aring; en siden som de ikke har tilladelse til if&oslash;lge deres betalingsplan).');
define( '_CFG_GENERAL_GWLIST_ENABLED_NAME', 'Aktiverede gateways:');
define( '_CFG_GENERAL_GWLIST_ENABLED_DESC', 'V&aelig;lg de gateways som du &oslash;nsker skal v&aelig;re aktiverede (brug tasten CTRL for at v&aelig;lge mere end en). Efter at du har gemt vil fanen for gateways blive vist. Deaktivering af en gateway vil ikke slette dets indstillinger.');

define( '_CFG_GENERAL_BYPASSINTEGRATION_NAME', 'Deaktiver integration:');
define( '_CFG_GENERAL_BYPASSINTEGRATION_DESC', 'Angiv et navn eller en liste over navne (separeret af mellemrum) over integrationer som du &oslash;nsker at deaktivere. Aktuelt underst&oslash;ttede strenge: <strong>CB,CBE,CBM,JACL,SMF,UE,UHP2,VM</strong>. Dette kan v&aelig;re brugbart hvis du har afinstalleret CB men ikke slettet dets tabeller (i hvilket tilf&aelig;lde AEC stadig ville tro at CB var installeret).');
define( '_CFG_GENERAL_SIMPLEURLS_NAME', 'Simple URLer:');
define( '_CFG_GENERAL_SIMPLEURLS_DESC', 'Deaktiver brugen af Joomla/Mambo SEF rutiner for URLer. Med nogle ops&aelig;tninger vil disse resultere i 404 fejl p&aring; grund af forkert omskrivning. Pr&oslash;v denne indstilling hvis du st&oslash;der p&aring; problemer med omdirigeringer.');
define( '_CFG_GENERAL_EXPIRATION_CUSHION_NAME', 'Udl&oslash;bs buffer:');
define( '_CFG_GENERAL_EXPIRATION_CUSHION_DESC', 'Antal af timer som AEC buffer n&aring;r den beregner udl&oslash;bsdato. Brug et gener&oslash;s antal fordi betalinger indl&oslash;ber senere end den aktuelle udl&oslash;bsdato (med Paypal omkring 6-8 timer senere).');
define( '_CFG_GENERAL_HEARTBEAT_CYCLE_NAME', 'Hjerteslags cyclus:');
define( '_CFG_GENERAL_HEARTBEAT_CYCLE_DESC', 'Antal af timer som AEC venter inden den forst&aring;r et log p&aring; som trigger for udsendelse af emails eller foretage handlinger som du v&aelig;lger at udf&oslash;re periodisk.');
define( '_CFG_GENERAL_ROOT_GROUP_NAME', 'Rod gruppe:');
define( '_CFG_GENERAL_ROOT_GROUP_DESC', 'V&aelig;lg rod gruppen som brugeren f&aring;r vist n&aring;r han tilg&aring;r plan siden uden nogen pr&aelig; sat variabel.');
define( '_CFG_GENERAL_ROOT_GROUP_RW_NAME', 'Rod gruppe OmSkriv:');
define( '_CFG_GENERAL_ROOT_GROUP_RW_DESC', 'V&aelig;lg rod gruppen som brugeren f&aring;r vist n&aring;r han tilg&aring;r plan siden ved at returnere et gruppenummer eller et samling af grupper med funktionaliteten ReWriteEngine. Dette falder tilbage p&aring; den generelle indstilling (ovenover) hvis resultatet er tomt.');
define( '_CFG_GENERAL_PLANS_FIRST_NAME', 'Plan f&oslash;rst:');
define( '_CFG_GENERAL_PLANS_FIRST_DESC', 'Hvis du har tif&oslash;jet alle tre hacks for at have en integreret registrering med direkte medlemstegning, s&aring; vil denne indstilling aktivere denne funktionsm&aring;de. Brug den ikke hvis du ikke &oslash;nsker denne funktionsm&aring;de eller kun har tilf&oslash;jet det f&oslash;rste hack (hvilket betyder at planerne kommer efter at brugeren har udfyldt hans eller hendes detaljer) .');
define( '_CFG_GENERAL_INTEGRATE_REGISTRATION_NAME', 'Integrer registrering');
define( '_CFG_GENERAL_INTEGRATE_REGISTRATION_DESC', 'Med denne mulighed, kan du f&aring;r AEC Mambot/Programudvidelsen til at fange registreringskald og omdirigere dem til AEC medlemstegningssystemet. Med denne indstilling deaktiveret kan brugere frit registrere sig p&aring; siden, og hvis et medlemskab er prkr&aelig;vet, tegne dette efter f&oslash;rste log p&aring;. Hvis b&aring;de denne indstilling og "kr&aelig;v medlemskab" er deaktiveret, s&aring; er medlemsskabstegning fuldst&aelig;ndig frivilligt.');

define( '_CFG_TAB_CUSTOMIZATION_TITLE', 'Brugerdefiner');
define( '_CFG_TAB_CUSTOMIZATION_SUBTITLE', 'Brugerdefinering');

define( '_CFG_CUSTOMIZATION_SUB_CREDIRECT', 'Brugerdefineret omdirigeringer');
define( '_CFG_CUSTOMIZATION_SUB_PROXY', 'Proxy');
define( '_CFG_CUSTOMIZATION_SUB_BUTTONS_SUB', 'Medlemsknapper');
define( '_CFG_CUSTOMIZATION_SUB_FORMAT_DATE', 'Dato formatering');
define( '_CFG_CUSTOMIZATION_SUB_FORMAT_PRICE', 'Pris formatering');
define( '_CFG_CUSTOMIZATION_SUB_FORMAT_INUM', 'Faktura tal formatering');
define( '_CFG_CUSTOMIZATION_SUB_CAPTCHA', 'ReCAPTACHA');

define( '_CFG_GENERAL_CUSTOMINTRO_NAME', 'Brugerdefineret intro side link:');
define( '_CFG_GENERAL_CUSTOMINTRO_DESC', 'Aniv et fuldst&aelig;ndigt link (inklusiv http://) som f&oslash;rer til din brugerdefinerede intro side. Den side skal indeholde et link som dette: http://www.ditdom&aelig;ne.dk/index.php?option=com_acctexp&task=subscribe&intro=1 som bypaser introen korrekt viderestiller brugeren  til betalingsplanerne eller registreringsdetalje siden. Efterlad feltet tom hvis du ikke &oslash;nsker dette overhovedet.');
define( '_CFG_GENERAL_CUSTOMINTRO_USERID_NAME', 'Viderled brugerid');
define( '_CFG_GENERAL_CUSTOMINTRO_USERID_DESC', 'Viderled brugerid via en Joomla notifikation. Dette kan v&aelig;re anvendeligt for fleksibel brugerdefineret tilmeldelsessider som skal fungere slevom brugeren ikke er logget p&aring;. Du kan bruge Javascript til at modificere tilmeldelses links if&oslash;lge det videreledte brugerid.');
define( '_CFG_GENERAL_CUSTOMINTRO_ALWAYS_NAME', 'Vis altid intro');
define( '_CFG_GENERAL_CUSTOMINTRO_ALWAYS_DESC', 'Vis introen uanset om brugeren allerede er registreret.');
define( '_CFG_GENERAL_CUSTOMTHANKS_NAME', 'Brugerdefineret takke side link:');
define( '_CFG_GENERAL_CUSTOMTHANKS_DESC', 'Angiv et fuldst&aelig;ndigt link (inklusiv http://) som f&oslash;rer til en brugerdefineret takke side. Efterlad dette felt tomt hvis du ikke &oslash;nsker dette.');
define( '_CFG_GENERAL_CUSTOMCANCEL_NAME', 'Brugerdefineret annulleringsside link:');
define( '_CFG_GENERAL_CUSTOMCANCEL_DESC', 'Angiv et fuldst&aelig;ndigt link (inklusiv http://) som f&oslash;rer til din brugerdefinerede annulleringsside. Efterlad dette felt tomt hvis du ikke &oslash;nsker dette.');
define( '_CFG_GENERAL_TOS_NAME', 'Handelsbetingelser:');
define( '_CFG_GENERAL_TOS_DESC', 'Angiv en URL til dine handelsbetingelser. Brugeren skal markere en afkrydsningsboks n&aring;r han bekr&aelig;fter sin konto. Hvis dette efterlades tomt, s&aring; vil intet vises.');
define( '_CFG_GENERAL_TOS_IFRAME_NAME', 'Handelsbetingelser Iframe:');
define( '_CFG_GENERAL_TOS_IFRAME_DESC', 'Vis handelsbetingelserne (som specificeret ovenover) i en iframe ved bekr&aelig;ftelse');
define( '_CFG_GENERAL_CUSTOMNOTALLOWED_NAME', 'Brugerdefineret IkkeTilladt link:');
define( '_CFG_GENERAL_CUSTOMNOTALLOWED_DESC', 'Angiv et fuldst&aelig;ndigt ink (inklusiv http://) som f&oslash;rer til din brugerdefinerede IkkeTilladet side. Efterlad dette tomt hvis du ikke &oslash;nsker dette.');

define( '_CFG_CUSTOMIZATION_INVOICE_PRINTOUT', 'Fakture udskrift');
define( '_CFG_CUSTOMIZATION_INVOICE_PRINTOUT_DETAILS', 'Faktura udskriv detaljer');

define( '_CFG_TAB_CUSTOMINVOICE_TITLE', 'Faktura brugerdefinering');
define( '_CFG_TAB_CUSTOMINVOICE_SUBTITLE', 'Faktura brugerdefinering');
define( '_CFG_TAB_CUSTOMPAGES_TITLE', 'Side brugerdefinering');
define( '_CFG_TAB_CUSTOMPAGES_SUBTITLE', 'Side brugerdefinering');
define( '_CFG_TAB_EXPERT_TITLE', 'Ekspert');
define( '_CFG_TAB_EXPERT_SUBTITLE', 'Ekspert indstillinger');

define( '_AEC_CUSTOM_INVOICE_PAGE_TITLE', 'Faktura');
define( '_AEC_CUSTOM_INVOICE_HEADER', 'Faktura');
define( '_AEC_CUSTOM_INVOICE_BEFORE_CONTENT', 'Faktura/kvittering for:');
define( '_AEC_CUSTOM_INVOICE_AFTER_CONTENT', 'Tak fordi du valgte vores service!');
define( '_AEC_CUSTOM_INVOICE_FOOTER', ' - Tilf&oslash;j dine butiksinformationer her - ');

define( '_CFG_GENERAL_INVOICE_PAGE_TITLE', 'Faktura');
define( '_CFG_GENERAL_INVOICE_PAGE_TITLE_NAME', 'Sidetitel');
define( '_CFG_GENERAL_INVOICE_PAGE_TITLE_DESC', 'Sidetitel p&aring; faktura udskriften');
define( '_CFG_GENERAL_INVOICE_HEADER_NAME', 'Faktura sidehoved');
define( '_CFG_GENERAL_INVOICE_HEADER_DESC', 'Sidehoved tekst p&aring; faktura udskriften');
define( '_CFG_GENERAL_INVOICE_AFTER_HEADER_NAME', 'Faktura under sidehoved');
define( '_CFG_GENERAL_INVOICE_AFTER_HEADER_DESC', 'Tekst under sidehoved p&aring; faktura udskriften');
define( '_CFG_GENERAL_INVOICE_ADDRESS_NAME', 'Faktura adressefelt');
define( '_CFG_GENERAL_INVOICE_ADDRESS_DESC', 'Tekst i adressefeltet p&aring; faktura udskriften');
define( '_CFG_GENERAL_INVOICE_ADDRESS_ALLOW_EDIT_NAME', 'Tillad brugeredigering');
define( '_CFG_GENERAL_INVOICE_ADDRESS_ALLOW_EDIT_DESC', 'Dette giver brugeren mulighed for at redigere adressefeltet direkte p&aring; udskriften.');
define( '_CFG_GENERAL_INVOICE_BEFORE_CONTENT_NAME', 'Faktura f&oslash;r indhold');
define( '_CFG_GENERAL_INVOICE_BEFORE_CONTENT_DESC', 'Teksten f&oslash;r fakturaindholdet p&aring; faktura udskriften');
define( '_CFG_GENERAL_INVOICE_AFTER_CONTENT_NAME', 'Faktura efter indhold');
define( '_CFG_GENERAL_INVOICE_AFTER_CONTENT_DESC', 'Teksten efter fakturaindholdet p&aring; faktura udskriften');
define( '_CFG_GENERAL_INVOICE_BEFORE_FOOTER_NAME', 'Faktura f&oslash;r sidefod');
define( '_CFG_GENERAL_INVOICE_BEFORE_FOOTER_DESC', 'Teksten f&oslash;r sidefoden p&aring; faktura udskriften');
define( '_CFG_GENERAL_INVOICE_FOOTER_NAME', 'Faktura sidefod');
define( '_CFG_GENERAL_INVOICE_FOOTER_DESC', 'Sidefodstekst p&aring; faktura udskriften');
define( '_CFG_GENERAL_INVOICE_AFTER_FOOTER_NAME', 'Faktura efter sidefod');
define( '_CFG_GENERAL_INVOICE_AFTER_FOOTER_DESC', 'Teksten efter sidefoden p&aring; faktura udskriften');

define( '_CFG_GENERAL_CHECKOUT_DISPLAY_DESCRIPTIONS_NAME', 'Vis bekskrivelser:');
define( '_CFG_GENERAL_CHECKOUT_DISPLAY_DESCRIPTIONS_DESC', 'Hvis du har flere planer, eller har sprunget over bekr&aelig;ftelsen, s&aring; kan det v&aelig;re en god idé at vise plan beskrivelsen igen. Denne indstilling g&oslash;r netop dette.');
define( '_CFG_GENERAL_CHECKOUT_AS_GIFT_NAME', 'Tillad gave betaling:');
define( '_CFG_GENERAL_CHECKOUT_AS_GIFT_DESC', 'Med denne indstilling, s&aring; kan en bruger give et medlemskab til en anden bruger - alle planer og tilknyttet funktionalitet bliver s&aring; udf&oslash;rt p&aring; modtagerens brugerkonto.');
define( '_CFG_GENERAL_CHECKOUT_AS_GIFT_ACCESS_NAME', 'Gave adgang:');
define( '_CFG_GENERAL_CHECKOUT_AS_GIFT_ACCESS_DESC', 'Hvilken brugergruppe kr&aelig;ves (minimum) for at kunne give et medlemskab i gave?');
define( '_CFG_GENERAL_CONFIRM_AS_GIFT_NAME', 'Tillad gave ved bekr&aelig;ftelse:');
define( '_CFG_GENERAL_CONFIRM_AS_GIFT_DESC', 'Tilbyd den samme gave mulighed p&aring; bekr&aelig;ftelsessiden ogs&aring;.');

define( '_CFG_GENERAL_DISPLAY_DATE_FRONTEND_NAME', 'Frontend dato format');
define( '_CFG_GENERAL_DISPLAY_DATE_FRONTEND_DESC', 'Angiv m&aring;den hvorp&aring; datoen vises p&aring; i frontend. Referer til <a href="http://www.php.net/manual/en/function.strftime.php">php manualen</a> for den korrekte syntaks.');
define( '_CFG_GENERAL_DISPLAY_DATE_BACKEND_NAME', 'Backend dato format');
define( '_CFG_GENERAL_DISPLAY_DATE_BACKEND_DESC', 'Angiv m&aring;den hvorp&aring; datoen vises p&aring; i frontend. Referer til <a href="http://www.php.net/manual/en/function.strftime.php">php manualen</a> for den korrekte syntaks.');

define( '_CFG_GENERAL_INVOICENUM_DOFORMAT_NAME', 'Format fakturanummer');
define( '_CFG_GENERAL_INVOICENUM_DOFORMAT_DESC', 'Vis en formateret streng istedet for det originale FakturaNummer til brugeren. Du skal angive en formateringsregel nedenunder.');
define( '_CFG_GENERAL_INVOICENUM_FORMATTING_NAME', 'Formatering');
define( '_CFG_GENERAL_INVOICENUM_FORMATTING_DESC', 'Formatering - Du kan bruge RewriteEngine som specificeret nedenunder');

define( '_CFG_GENERAL_CUSTOMTEXT_PLANS_NAME', 'Brugerdefineret tekst plan side');
define( '_CFG_GENERAL_CUSTOMTEXT_PLANS_DESC', 'Tekst som vil vises p&aring; plan siden');
define( '_CFG_GENERAL_CUSTOMTEXT_CONFIRM_NAME', 'Brugerdefineret tekst bekr&aelig;ftelsesside');
define( '_CFG_GENERAL_CUSTOMTEXT_CONFIRM_DESC', 'Tekst som vil vises p&aring; bekr&aelig;ftelsessiden');
define( '_CFG_GENERAL_CUSTOM_CONFIRM_USERDETAILS_NAME', 'Brugerdefineret tekst bekr&aelig;ft brugerdetaljer');
define( '_CFG_GENERAL_CUSTOM_CONFIRM_USERDETAILS_DESC', 'Brugerdefiner hvad feltet brugerdetaljer viser ved bekr&aelig;ftelse');
define( '_CFG_GENERAL_CUSTOMTEXT_CHECKOUT_NAME', 'Brugerdefineret tekst betalingsside');
define( '_CFG_GENERAL_CUSTOMTEXT_CHECKOUT_DESC', 'Tekst der bliver vist p&aring; betalingssiden');
define( '_CFG_GENERAL_CUSTOMTEXT_NOTALLOWED_NAME', 'Brugerdefineret IkkeTilladt side');
define( '_CFG_GENERAL_CUSTOMTEXT_NOTALLOWED_DESC', 'Tekst der vises p&aring; siden IkkeTilladt');
define( '_CFG_GENERAL_CUSTOMTEXT_PENDING_NAME', 'Brugerdefineret tekst p&aring; siden Afventer');
define( '_CFG_GENERAL_CUSTOMTEXT_PENDING_DESC', 'Tekst der vises p&aring; siden Afventer');
define( '_CFG_GENERAL_CUSTOMTEXT_EXPIRED_NAME', 'Brugerdefineret tekst p&aring; siden Udl&oslash;bet');
define( '_CFG_GENERAL_CUSTOMTEXT_EXPIRED_DESC', 'Tekst der vises p&aring; siden Udl&oslash;bet');

define( '_CFG_GENERAL_CUSTOMTEXT_CONFIRM_KEEPORIGINAL_NAME', 'Behold original tekst');
define( '_CFG_GENERAL_CUSTOMTEXT_CONFIRM_KEEPORIGINAL_DESC', 'V&aelig;lg denne mulighed hvis du &oslash;nsker at beholde den originale tekst p&aring; bekr&aelig;ftelsessiden');
define( '_CFG_GENERAL_CUSTOMTEXT_CHECKOUT_KEEPORIGINAL_NAME', 'Behold original tekst');
define( '_CFG_GENERAL_CUSTOMTEXT_CHECKOUT_KEEPORIGINAL_DESC', 'V&aelig;lg denne mulighed hvis du &oslash;nsker at beholde den originale tekst p&aring; betalingssiden');
define( '_CFG_GENERAL_CUSTOMTEXT_NOTALLOWED_KEEPORIGINAL_NAME', 'Behold original tekst');
define( '_CFG_GENERAL_CUSTOMTEXT_NOTALLOWED_KEEPORIGINAL_DESC', 'V&aelig;lg denne mulighed hvis du &oslash;nsker at beholde den originale tekst p&aring; siden IkkeTilladt');
define( '_CFG_GENERAL_CUSTOMTEXT_PENDING_KEEPORIGINAL_NAME', 'Behold original tekst');
define( '_CFG_GENERAL_CUSTOMTEXT_PENDING_KEEPORIGINAL_DESC', 'V&aelig;lg denne mulighed hvis du &oslash;nsker at beholde den originale tekst p&aring; siden Afventer');
define( '_CFG_GENERAL_CUSTOMTEXT_EXPIRED_KEEPORIGINAL_NAME', 'Behold original tekst');
define( '_CFG_GENERAL_CUSTOMTEXT_EXPIRED_KEEPORIGINAL_DESC', 'V&aelig;lg denne mulighed hvis du &oslash;nsker at beholde den originale tekst p&aring; siden Udl&oslash;bet');

define( '_CFG_GENERAL_CUSTOMTEXT_THANKS_KEEPORIGINAL_NAME', 'Behold original tekst');
define( '_CFG_GENERAL_CUSTOMTEXT_THANKS_KEEPORIGINAL_DESC', 'V&aelig;lg denne mulighed hvis du &oslash;nsker at beholde den originale tekst p&aring; siden Tak');
define( '_CFG_GENERAL_CUSTOMTEXT_THANKS_NAME', 'Brugerdefineret tekst p&aring; siden tak');
define( '_CFG_GENERAL_CUSTOMTEXT_THANKS_DESC', 'Tekst der vises p&aring; siden Tak');
define( '_CFG_GENERAL_CUSTOMTEXT_CANCEL_KEEPORIGINAL_NAME', 'Behold original tekst');
define( '_CFG_GENERAL_CUSTOMTEXT_CANCEL_KEEPORIGINAL_DESC', 'V&aelig;lg denne mulighed hvis du &oslash;nsker at beholde den originale tekst p&aring; Cancel Page');
define( '_CFG_GENERAL_CUSTOMTEXT_CANCEL_NAME', 'Brugerdefineret tekst p&aring; siden Annuller');
define( '_CFG_GENERAL_CUSTOMTEXT_CANCEL_DESC', 'Tekst der vises p&aring; siden Annuller');

define( '_CFG_GENERAL_CUSTOMTEXT_HOLD_KEEPORIGINAL_NAME', 'Behold original tekst');
define( '_CFG_GENERAL_CUSTOMTEXT_HOLD_KEEPORIGINAL_DESC', 'V&aelig;lg denne mulighed hvis du &oslash;nsker at beholde den originale tekst p&aring; Hold Page');
define( '_CFG_GENERAL_CUSTOMTEXT_HOLD_NAME', 'Brugerdefinertekst p&aring; siden Hold');
define( '_CFG_GENERAL_CUSTOMTEXT_HOLD_DESC', 'Tekst der vises p&aring; siden Hold');

define( '_CFG_GENERAL_CUSTOMTEXT_EXCEPTION_KEEPORIGINAL_NAME', 'Behold original tekst');
define( '_CFG_GENERAL_CUSTOMTEXT_EXCEPTION_KEEPORIGINAL_DESC', 'V&aelig;lg denne mulighed hvis du &oslash;nsker at beholde den originale tekst p&aring; siden Undtaget');
define( '_CFG_GENERAL_CUSTOMTEXT_EXCEPTION_NAME', 'Brugerdefineret tekst p&aring; siden Undtaget');
define( '_CFG_GENERAL_CUSTOMTEXT_EXCEPTION_DESC', 'Tekst der vises p&aring; siden Undtaget (vises typisk n&aring;r en bruger skal angive hvilken betalingsm&aring;de der skal anvendes til k&oslash;bet, eller hvilket element en rabatkode skal anvendes p&aring;).');

define( '_CFG_GENERAL_USE_RECAPTCHA_NAME', 'Brug ReCAPTCHA');
define( '_CFG_GENERAL_USE_RECAPTCHA_DESC', 'Hvis du har en konto til <a href="http://recaptcha.net/">ReCAPTCHA</a>, s&aring; kan du aktivere denne indstilling. Glem IKKE at indtaste n&oslash;glerne nedenunder.');
define( '_CFG_GENERAL_RECAPTCHA_PRIVATEKEY_NAME', 'Privat ReCAPTCHA n&oslash;gle');
define( '_CFG_GENERAL_RECAPTCHA_PRIVATEKEY_DESC', 'Din private ReCAPTCHA n&oslash;gle.');
define( '_CFG_GENERAL_RECAPTCHA_PUBLICKEY_NAME', 'Offentlig ReCAPTCHA n&oslash;gle');
define( '_CFG_GENERAL_RECAPTCHA_PUBLICKEY_DESC', 'Din offentlige ReCAPTCHA n&oslash;gle.');

define( '_CFG_GENERAL_TEMP_AUTH_EXP_NAME', 'Faktura adgang inden log af');
define( '_CFG_GENERAL_TEMP_AUTH_EXP_DESC', 'Tidsrummet (i minutter) som en bruger har mulighed for at tilf&aring; fakturaen ved kun at referere til brugerid. N&aring;r dette tidsrum udl&oslash;ber, skal der indtastes en adgangskode inden der tillades adgang i det samme tidsrum igen.');

define( '_CFG_GENERAL_HEARTBEAT_CYCLE_BACKEND_NAME', 'Hjertefrekvens cyclus backend:');
define( '_CFG_GENERAL_HEARTBEAT_CYCLE_BACKEND_DESC', 'Antal timer som AEC venter inden en backend adgang forst&aring;s som et hjerteslags trigger.');
define( '_CFG_GENERAL_ENABLE_COUPONS_NAME', 'Aktiver rabatkuponner:');
define( '_CFG_GENERAL_ENABLE_COUPONS_DESC', 'Aktiver brugen af rabatkuponner for dine medlemskaber.');
define( '_CFG_GENERAL_DISPLAYCCINFO_NAME', 'Aktiver CC visning:');
define( '_CFG_GENERAL_DISPLAYCCINFO_DESC', 'Aktiver visningen af kreditkort ikoner for hver betalingsprocessor.');
define( '_CFG_GENERAL_ADMINACCESS_NAME', 'Administrator adgang:');
define( '_CFG_GENERAL_ADMINACCESS_DESC', 'Tillad adgang til AEC ikke kun til Super Administratorer, men ogs&aring; til regul&aelig;re administratorer.');
define( '_CFG_GENERAL_NOEMAILS_NAME', 'Ingen emails');
define( '_CFG_GENERAL_NOEMAILS_DESC', 'S&aelig;t dette for at undg&aring; at AEC udsender system emails (til brugeren hvis faktura er betalt eller lignende). Dette p&aring;virker ikke emails being der sendes fra MicroIntegrations.');
define( '_CFG_GENERAL_NOJOOMLAREGEMAILS_NAME', 'Ingen Joomla emails');
define( '_CFG_GENERAL_NOJOOMLAREGEMAILS_DESC', 'S&aelig;t dette for at undg&aring; at Joomla registrerings bekr&aelig;ftelses emails udsendes.');
define( '_CFG_GENERAL_DEBUGMODE_NAME', 'Fejls&oslash;gnings tilstand');
define( '_CFG_GENERAL_DEBUGMODE_DESC', 'Aktiverer visningen af fejls&oslash;gningsinformation.');
define( '_CFG_GENERAL_EMAIL_DEFAULT_ADMINS_NAME', 'Email administratorer');
define( '_CFG_GENERAL_EMAIL_DEFAULT_ADMINS_DESC', 'Alle administratorer p&aring; dit CMS, som har valgt at modtage system emails, vil modtage AEC system beskeder n&aring;r denne indstilling er aktiveret.');
define( '_CFG_GENERAL_OVERRIDE_REQSSL_NAME', 'Tilsides&aelig;t SSL krav');
define( '_CFG_GENERAL_OVERRIDE_REQSSL_DESC', 'Nogle betalingsprocessorer kr&aelig;ver en SSL sikker forbindelse til brugeren - for eksempel n&aring;r f&oslash;lsom information (s&aring;som kreditkortdata) kr&aelig;ves i frontend.');
define( '_CFG_GENERAL_ALTSSLURL_NAME', 'Alternativ SSL Url');
define( '_CFG_GENERAL_ALTSSLURL_DESC', 'Brug denne URL i stedet for basis url som er konfigureret i Joomla! n&aring;r du router gennem SSL.');
define( '_CFG_GENERAL_OVERRIDEJ15_NAME', 'Tilsides&aelig;t Joomla 1.5 integration');
define( '_CFG_GENERAL_OVERRIDEJ15_DESC', 'Nogle tilf&oslash;jelser narrer 1.0 Joomla til at tro at det rent faktisk er 1.5 (du hved hvem du er - stop det!) - som AEC f&oslash;lger og fejler. Dette laver et permanent skift der tvinger til 1.0 tilstand.');
define( '_CFG_GENERAL_SSL_SIGNUP_NAME', 'SSL tilmelding');
define( '_CFG_GENERAL_SSL_SIGNUP_DESC', 'Brug SSL kryptering p&aring; alle link der har med bruger tilmelding i AEC at g&oslash;re.');
define( '_CFG_GENERAL_SSL_PROFILE_NAME', 'SSL profil');
define( '_CFG_GENERAL_SSL_PROFILE_DESC', 'Brug SSL kryptering p&aring; alle link der har med brugerens tilgang til profilen (MySubscription page) at g&oslash;re.');
define( '_CFG_GENERAL_SSL_VERIFYPEER_NAME', 'SSL Peer verifikation');
define( '_CFG_GENERAL_SSL_VERIFYPEER_DESC', 'N&aring;r cURL bruges, s&aring; lad det verificere certifikatet p&aring; peer. Alternative certifiketer til at verificere imod kan specificeres med indstillingerne nedenunder');
define( '_CFG_GENERAL_SSL_VERIFYHOST_NAME', 'SSL Host verifikation');
define( '_CFG_GENERAL_SSL_VERIFYHOST_DESC', 'Definerer hvilken type verificering du &oslash;nsker imode peer\'ets certifikat.');
define( '_CFG_GENERAL_SSL_CAINFO_NAME', 'Certifikatfil');
define( '_CFG_GENERAL_SSL_CAINFO_DESC', 'Navnet p&aring; filen der indeholder et eller flere cirtifikater til verificering af peer. Dette giver kun mening hvis det bruges i kombination med Peer verificering.');
define( '_CFG_GENERAL_SSL_CAPATH_NAME', 'Certifikat mappe');
define( '_CFG_GENERAL_SSL_CAPATH_DESC', 'En mappe som indeholder multiple CA certifikater. Brug denne indstilling sammen med peer verifikation.');
define( '_CFG_GENERAL_USE_PROXY_NAME', 'Brug proxy');
define( '_CFG_GENERAL_USE_PROXY_DESC', 'Brug en proxy server til alle udg&aring;ende foresp&oslash;rgsler.');
define( '_CFG_GENERAL_PROXY_NAME', 'Proxy adresse');
define( '_CFG_GENERAL_PROXY_DESC', 'Angiv proxy serveren som du vil forbinde til.');
define( '_CFG_GENERAL_PROXY_PORT_NAME', 'Proxy port');
define( '_CFG_GENERAL_PROXY_PORT_DESC', 'Angiv proxy server porten som du vil forbinde til.');
define( '_CFG_GENERAL_PROXY_USERNAME_NAME', 'Proxy brugernavn');
define( '_CFG_GENERAL_PROXY_USERNAME_DESC', 'Hvis din proxy kr&aelig;ver log p&aring;, s&aring; angiv brugernavnet her.');
define( '_CFG_GENERAL_PROXY_PASSWORD_NAME', 'Proxy adgangskode');
define( '_CFG_GENERAL_PROXY_PASSWORD_DESC', 'Hvis din proxy kr&aelig;ver log p&aring;, s&aring; angiv adgangskoden her.');
define( '_CFG_GENERAL_GETHOSTBYADDR_NAME', 'Log host med IP');
define( '_CFG_GENERAL_GETHOSTBYADDR_DESC', 'Ved log handlinger som gemmer en IP adresse, vil denne indstilling ogs&aring; gemme internet host navnet. I nogle hosting situationer kan dette tage over et minut og b&oslash;r da deaktiveres.');
define( '_CFG_GENERAL_RENEW_BUTTON_NEVER_NAME', 'Ingen forny knap');
define( '_CFG_GENERAL_RENEW_BUTTON_NEVER_DESC', 'V&aelig;lg "Ja" for aldrig at vise knappen forny/opgrader p&aring; siden MitMedlemskab.');
define( '_CFG_GENERAL_RENEW_BUTTON_NOLIFETIMERECURRING_NAME', 'Begr&aelig;nset forny knap');
define( '_CFG_GENERAL_RENEW_BUTTON_NOLIFETIMERECURRING_DESC', 'Vis kun knappen forny hvis det giver mening i en et-medlemskabs ops&aelig;tning (gentagende betalinger eller livstid f&aring;r knappen til at forsvinde).');
define( '_CFG_GENERAL_CONTINUE_BUTTON_NAME', 'Forts&aelig;t knap');
define( '_CFG_GENERAL_CONTINUE_BUTTON_DESC', 'Hvis brugeren har haft et medlemskab tidligere, s&aring; vil denne knap vises p&aring; udl&oslash;bssk&aelig;rmen og linke direkte til den forrige plan, s&aring; brugeren hurtigt kan forts&aelig;tte medlemskabet som det var tidligere');

define( '_CFG_GENERAL_ERROR_NOTIFICATION_LEVEL_NAME', 'Notifikations niveau');
define( '_CFG_GENERAL_ERROR_NOTIFICATION_LEVEL_DESC', 'V&aelig;lg hvilket niveau af indf&oslash;rsler til handlingsloggen der kr&aelig;ves for at de kommer frem p&aring; centralsiden til din opm&aelig;rksomhed.');
define( '_CFG_GENERAL_EMAIL_NOTIFICATION_LEVEL_NAME', 'Email notifikations niveau');
define( '_CFG_GENERAL_EMAIL_NOTIFICATION_LEVEL_DESC', 'V&aelig;lg hvilket niveau af indf&oslash;rsler til handlingsloggen der kr&aelig;ves for at AEC sender dem som en E-Mail til alle administratorer.');

define( '_CFG_GENERAL_SKIP_CONFIRMATION_NAME', 'Spring over bekr&aelig;ftelse');
define( '_CFG_GENERAL_SKIP_CONFIRMATION_DESC', 'Vis ikke en bekr&aelig;ftelsessk&aelig;rm inden betaling (som lader din bruger gennemse de tidligere foretagede valg).');
define( '_CFG_GENERAL_SHOW_FIXEDDECISION_NAME', 'Vis fastlagte valg');
define( '_CFG_GENERAL_SHOW_FIXEDDECISION_DESC', 'AEC springer normalt over betalingsplansiden hvis der ikke skal v&aelig;lges noget (en betalingsplan en betalingsprocessor). Med denne indstilling kan du tvinge den til at vise siden.');
define( '_CFG_GENERAL_CONFIRMATION_COUPONS_NAME', 'Rabatkuponner ved bekr&aelig;ftelse');
define( '_CFG_GENERAL_CONFIRMATION_COUPONS_DESC', 'Tilbyd rabatkuponkoder n&aring;r der klikkes p&aring; knappen Bekr&aelig;ft p&aring; bekr&aelig;ftelsessiden');
define( '_CFG_GENERAL_BREAKON_MI_ERROR_NAME', 'Stop ved MI fejl');
define( '_CFG_GENERAL_BREAKON_MI_ERROR_DESC', 'Stop plan applikation hvis en af dets tilknyttede MIer st&oslash;der p&aring; fejl (der vil v&aelig;re en sti i handlingsloggen uanset)');

define( '_CFG_GENERAL_ENABLE_SHOPPINGCART_NAME', 'Aktiver indk&oslash;bskurv');
define( '_CFG_GENERAL_ENABLE_SHOPPINGCART_DESC', 'Behandel k&oslash;b igennem indk&oslash;bskurv. Kun tilg&aelig;ngelig for brugere der er logget p&aring;.');
define( '_CFG_GENERAL_CUSTOMLINK_CONTINUESHOPPING_NAME', 'Brugerdefineret link - forts&aelig;t med at handle');
define( '_CFG_GENERAL_CUSTOMLINK_CONTINUESHOPPING_DESC', 'Istedet for at route en bruger til standard medlemssiden, route hertil.');
define( '_CFG_GENERAL_ADDITEM_STAYONPAGE_NAME', 'Bliv p&aring; siden');
define( '_CFG_GENERAL_ADDITEM_STAYONPAGE_DESC', 'Istedet for at flytte til indk&oslash;bskurven efter at have valgt et element, s&aring; bliv p&aring; den samme side.');

define( '_CFG_GENERAL_CURL_DEFAULT_NAME', 'Brug cURL');
define( '_CFG_GENERAL_CURL_DEFAULT_DESC', 'Brug cURL istedet for fsockopen som standard (vil falde tilbage til den anden hvis det f&oslash;rste valg fejler)');
define( '_CFG_GENERAL_AMOUNT_CURRENCY_SYMBOL_NAME', 'Valutasymbol');
define( '_CFG_GENERAL_AMOUNT_CURRENCY_SYMBOL_DESC', 'Vis et valutasymbol (hvis der eksisterer et) istedet for ISO forkortelsen');
define( '_CFG_GENERAL_AMOUNT_CURRENCY_SYMBOLFIRST_NAME', 'Valuta f&oslash;rst');
define( '_CFG_GENERAL_AMOUNT_CURRENCY_SYMBOLFIRST_DESC', 'Vis valuta foran bel&oslash;bet');
define( '_CFG_GENERAL_AMOUNT_USE_COMMA_NAME', 'Brug komma');
define( '_CFG_GENERAL_AMOUNT_USE_COMMA_DESC', 'Brug et komma istedet for et punktum i bel&oslash;b');
define( '_CFG_GENERAL_ALLOW_FRONTEND_HEARTBEAT_NAME', 'Tillad brugerdefineret frontend hjerteslag');
define( '_CFG_GENERAL_ALLOW_FRONTEND_HEARTBEAT_DESC', 'Trigger et brugerdefineret hjerteslag frontend (via linket index.php?option=com_acctexp&task=heartbeat) - for eksempel med et Cronjob');
define( '_CFG_GENERAL_DISABLE_REGULAR_HEARTBEAT_NAME', 'Deaktiver automatisk hjerteslag');
define( '_CFG_GENERAL_DISABLE_REGULAR_HEARTBEAT_DESC', 'Hvis du kun vil trigger de brugerdefinerede hjerteslag, s&aring; kan du deaktivere de automatiske her.');
define( '_CFG_GENERAL_CUSTOM_HEARTBEAT_SECUREHASH_NAME', 'Brugerdefineret frontend hjerteslag sikkerhedstegn');
define( '_CFG_GENERAL_CUSTOM_HEARTBEAT_SECUREHASH_DESC', 'En kode som skal tilf&oslash;jes ved brugerdefineret frontend hjerteslag (med denne tilf&oslash;jelse &hash=YOURHASHCODE) - hvis en er sat, men ikke sendt, s&aring; vil AEC ikke trigge hjerteslaget.');
define( '_CFG_GENERAL_QUICKSEARCH_TOP_NAME', 'Hurtigs&oslash;g &oslash;verst');
define( '_CFG_GENERAL_QUICKSEARCH_TOP_DESC', 'Dette er indstillingen for alle hurtigs&oslash;gningsjunkier - den flytter hurtigs&oslash;g til over hovedikonerne p&aring; hovedsiden');

define( '_CFG_GENERAL_SUB_UNINSTALL', 'Afinstaller');
define( '_CFG_GENERAL_DELETE_TABLES_NAME', 'Slet tabeller');
define( '_CFG_GENERAL_DELETE_TABLES_DESC', '&Oslash;nsker du at slette AEC tabellerne n&aring;r du afinstallerer softwaren?');
define( '_CFG_GENERAL_DELETE_TABLES_SURE_NAME', 'Sikker?');
define( '_CFG_GENERAL_DELETE_TABLES_SURE_DESC', 'Sikkerheds switch - n&aring;r du sletter dine AEC tabeller, S&Aring; VIL ALLE DINE MEDLEMSKABSDATA V&AElig;RE TABT!');
define( '_CFG_GENERAL_STANDARD_CURRENCY_NAME', 'Standard valuta');
define( '_CFG_GENERAL_STANDARD_CURRENCY_DESC', 'Hvilken valuta skal AEC bruge hvis der ikke er nogen information tilg&aelig;ngelig (for eksempel, hvis en plan er gratis, s&aring; vil den ikke have tilknyttet en processor og f&aring;r da valutainformationen herfra)');

define( '_CFG_GENERAL_CONFIRMATION_CHANGEUSERNAME_NAME', 'Mulighed: skift brugernavn');
define( '_CFG_GENERAL_CONFIRMATION_CHANGEUSERNAME_DESC', 'Giv nye brugere muligheden for at g&aring; tilbage til registreringssk&aelig;rmen fra bekr&aelig;ftelsessiden (i tilf&aelig;lde af at de lavede en fejl)');
define( '_CFG_GENERAL_CONFIRMATION_CHANGEUSAGE_NAME', 'Mulighed: skift element');
define( '_CFG_GENERAL_CONFIRMATION_CHANGEUSAGE_DESC', 'Giv nye brugere mulighed for at g&aring; tilbage til plan valgsk&aelig;rmen fra bekr&aelig;ftelsessiden (i tilf&aelig;lde af at de lavede en fejl)');

define( '_CFG_GENERAL_CHECKOUTFORM_JSVALIDATION_NAME', 'Betaling - JS validering');
define( '_CFG_GENERAL_CHECKOUTFORM_JSVALIDATION_DESC', 'Brug grundl&aelig;ggende Javascript validering p&aring; betalings kreditkortformularen');

define( '_CFG_GENERAL_MANAGERACCESS_NAME', 'Manager adgang:');
define( '_CFG_GENERAL_MANAGERACCESS_DESC', 'Tillad ikke kun adgang til AEC for administratorer, men ogs&aring; for managere.');
define( '_CFG_GENERAL_PER_PLAN_MIS_NAME', 'Per plan MIer:');
define( '_CFG_GENERAL_PER_PLAN_MIS_DESC', 'Vis per-plan MIer som kun er redigerbare indenfor betalingsplaner og kun er tilknyettet til den ene plan de blev oprettet i.');
define( '_CFG_GENERAL_INTRO_EXPIRED_NAME', 'Intro for udl&oslash;bet');
define( '_CFG_GENERAL_INTRO_EXPIRED_DESC', 'AEC viser normalt ikke intro siden (som du sat eller ej) n&aring;r brugere, hvis medlemskab er udl&oslash;bet, &oslash;nsker at tegne et nyt. Denne indstilling tilsides&aelig;tter denne opf&oslash;rsel.');

define( '_CFG_GENERAL_INVOICE_CUSHION_NAME', 'Faktura buffer');
define( '_CFG_GENERAL_INVOICE_CUSHION_DESC', 'Buffer perioden i hvilken AEC ikke accepterer nye notifikationer for en faktura der allerede er betalt.');

define( '_CFG_GENERAL_COUNTRIES_AVAILABLE_NAME', 'Tilg&aelig;ngelige lande');
define( '_CFG_GENERAL_COUNTRIES_AVAILABLE_DESC', 'Med denne indstilling, kan du v&aelig;lge at reduceret s&aelig;t af lande som bliver vist p&aring; en standard landeliste (som for eksempel bruges i nogle betalingsprocessoreres betalingsside for at lade brugeren v&aelig;lge deres lokation)');
define( '_CFG_GENERAL_COUNTRIES_TOP_NAME', 'Top lande');
define( '_CFG_GENERAL_COUNTRIES_TOP_DESC', '´Dette vil sortere de valgte lande til toppen af listen, n&aring;r der vises en standard landeliste (som for eksempel bruges i nogle betalingsprocessoreres betalingsside for at lade brugeren v&aelig;lge deres lokation)');

define( '_CFG_GENERAL_EMAIL_EXTRA_ADMINS_NAME', 'Yderligere email modtagere');
define( '_CFG_GENERAL_EMAIL_EXTRA_ADMINS_DESC', 'Hvis du &oslash;nsker at sende AEC system advarsler til yderligere modtagere, s&aring; kan du angive dem her - du kan tilf&oslash;je mere end en ved at adskille dem med komma');

define( '_CFG_GENERAL_ALLOW_INVOICE_UNPUBLISHED_ITEM_NAME', 'Tillad betaling for afpublicerede elementer');
define( '_CFG_GENERAL_ALLOW_INVOICE_UNPUBLISHED_ITEM_DESC', 'N&aring;r en bruger opretter en faktura med et element og lader denne faktura v&aelig;re ubetalt mens at elementet bliver afpubliceret, s&aring; er standard opf&oslash;rslen at n&aelig;gte adgang til elementet hvis brugeren senere fors&oslash;ger at betale det. Hvis du &oslash;nsker det, s&aring; kan du eksplicit tillade det modsatte med denne indstilling.');

define( '_CFG_GENERAL_SUB_SUBSCRIPTIONDETAILS', 'MineMedlemskaber side');
define( '_CFG_GENERAL_SUBSCRIPTIONDETAILS_MENU_NAME', 'Vis menu');
define( '_CFG_GENERAL_SUBSCRIPTIONDETAILS_MENU_DESC', 'Vis menuen for skifte mellem den generelle detaljeside, fakturaer og (hvis de eksisterer) MI detaljer');

define( '_CFG_CUSTOMIZATION_SUB_ITEMID', 'AEC side ElementIDer');
define( '_CFG_GENERAL_ITEMID_DEFAULT_NAME', 'Standard Elementid');
define( '_CFG_GENERAL_ITEMID_DEFAULT_DESC', 'ElementIDet hvis ingen anden relation kan laves fra valgene nedenunder.');
define( '_CFG_GENERAL_ITEMID_CART_NAME', 'ElementID indk&oslash;bskurv side');
define( '_CFG_GENERAL_ITEMID_CART_DESC', 'ElementIDet for indk&oslash;bskurvs siden.');
define( '_CFG_GENERAL_ITEMID_CHECKOUT_NAME', 'ElementID betalings side');
define( '_CFG_GENERAL_ITEMID_CHECKOUT_DESC', 'ElementIDet for betalings siden.');
define( '_CFG_GENERAL_ITEMID_CONFIRMATION_NAME', 'ElementID bekr&aelig;ftelsesside');
define( '_CFG_GENERAL_ITEMID_CONFIRMATION_DESC', 'ElementIDet for bekr&aelig;ftelsessiden.');
define( '_CFG_GENERAL_ITEMID_SUBSCRIBE_NAME', 'ElementID planer side');
define( '_CFG_GENERAL_ITEMID_SUBSCRIBE_DESC', 'ElementIDet for siden planer.');
define( '_CFG_GENERAL_ITEMID_EXCEPTION_NAME', 'ElementID undtagelsesside');
define( '_CFG_GENERAL_ITEMID_EXCEPTION_DESC', 'ElementID for undtagelsessiden.');
define( '_CFG_GENERAL_ITEMID_THANKS_NAME', 'ElementID takkeside');
define( '_CFG_GENERAL_ITEMID_THANKS_DESC', 'ElementIDet for takkesiden.');
define( '_CFG_GENERAL_ITEMID_EXPIRED_NAME', 'ElementID udl&oslash;bet side');
define( '_CFG_GENERAL_ITEMID_EXPIRED_DESC', 'ElementIDet for siden udl&oslash;bet.');
define( '_CFG_GENERAL_ITEMID_HOLD_NAME', 'ElementID hold side');
define( '_CFG_GENERAL_ITEMID_HOLD_DESC', 'ElementIDet for siden hold.');
define( '_CFG_GENERAL_ITEMID_NOTALLOWED_NAME', 'ElementID Ikke Tilladet side');
define( '_CFG_GENERAL_ITEMID_NOTALLOWED_DESC', 'ElementIDet for siden "adgang ikke tilladt".');
define( '_CFG_GENERAL_ITEMID_PENDING_NAME', 'ElementID afventer side');
define( '_CFG_GENERAL_ITEMID_PENDING_DESC', 'ElementIDet for siden afventer.');
define( '_CFG_GENERAL_ITEMID_SUBSCRIPTIONDETAILS_NAME', 'ElementID medlemsdetaljer side');
define( '_CFG_GENERAL_ITEMID_SUBSCRIPTIONDETAILS_DESC', 'ElementIDet for siden medlemsdetaljer.');
define( '_CFG_GENERAL_ITEMID_SUBSCRIPTIONDETAILS_INVOICES_NAME', 'ElementID medlemsdetaljer - faktura side');
define( '_CFG_GENERAL_ITEMID_SUBSCRIPTIONDETAILS_INVOICES_DESC', 'ElementIDet for medlemsdetaljer faktura under-siden.');
define( '_CFG_GENERAL_ITEMID_SUBSCRIPTIONDETAILS_DETAILS_NAME', 'ElementID medlemsdetaljer - detaljeside');
define( '_CFG_GENERAL_ITEMID_SUBSCRIPTIONDETAILS_DETAILS_DESC', 'ElementIDet for medelemsdetaljer detalje under-siden.');

// Invoice settings
define( '_CFG_GENERAL_SENDINVOICE_NAME', 'Send en fakturaemail');
define( '_CFG_GENERAL_SENDINVOICE_DESC', 'Send en faktura/ordrebekr&aelig;ftelse email (til moms)');
define( '_CFG_GENERAL_INVOICETMPL_NAME', 'Faktura skabelon');
define( '_CFG_GENERAL_INVOICETMPL_DESC', 'Skabelon til fakturaer/ordrebekr&aelig;ftelser');

// --== Processors PAGE ==--

define( '_PROCESSORS_TITLE', 'Processorer');
define( '_PROCESSOR_NAME', 'Navn');
define( '_PROCESSOR_DESC', 'Beskrivelse (f&oslash;rste 50 kar)');
define( '_PROCESSOR_ACTIVE', 'Publiceret');
define( '_PROCESSOR_VISIBLE', 'Synlig');
define( '_PROCESSOR_REORDER', 'Gensorter');
define( '_PROCESSOR_INFO', 'Information');

define( '_PUBLISH_PROCESSOR', 'Publicer');
define( '_UNPUBLISH_PROCESSOR', 'Afpublicer');
define( '_NEW_PROCESSOR', 'Ny');
define( '_COPY_PROCESSOR', 'Kopier');
define( '_APPLY_PROCESSOR', 'Anvend');
define( '_EDIT_PROCESSOR', 'Rediger');
define( '_REMOVE_PROCESSOR', 'Slet');
define( '_SAVE_PROCESSOR', 'Gem');
define( '_CANCEL_PROCESSOR', 'Annuller');

define( '_PP_GENERAL_PROCESSOR_NAME', 'Betalingsporocessor');
define( '_PP_GENERAL_PROCESSOR_DESC', 'V&aelig;lg hvilken betaligsprocessor du &oslash;nsker at anvende.');
define( '_PP_GENERAL_ACTIVE_NAME', 'Aktiv');
define( '_PP_GENERAL_ACTIVE_DESC', 'V&aelig;lg om denne processor pt er aktiv (og defor kan udf&oslash;re dets funktion og v&aelig;re tilg&aelig;ngelig for dine brugere)');
define( '_PP_GENERAL_PLEASE_NOTE', 'L&aelig;g venligst m&aelig;rke til');
define( '_PP_GENERAL_EXPERIMENTAL', 'Betalingsprocessoren er stadig ikke 100% f&aelig;rdig - den er enten blevet tilf&oslash;jet til kodebasen for nylig (og er derfor ikke gennemtestet) eller blev delvist afvist p&aring; grund af en kunde der ikke &oslash;nskede at vi lavede den alligevel. Hvis du &oslash;nsker at bruge den, s&aring; vil vi v&aelig;re taknemmelige for enhver hj&aelig;lp som du kan give - enten med yderligere information omkring integrationen, med fejlrapporter, eller med sponsorrater.');
define( '_PP_GENERAL_INSECURE', 'M&aring;de denne betaligsprocessor er designet p&aring; er usikker - hvilket betyder at der er lille til ingen p&aring;lidelig automatisk verfirkation af autenciteten af betalingsnotifikationer. Hvis du &oslash;nsker at bruge den, s&aring; kontroller venligst p&aring; hver eneste transaktion som du processerer. Detter ikke noget som vi kan fikse i - det er en begr&aelig;nsning der er lavet af betalingsservicen selv!');

// --== PAYMENT PLAN PAGE ==--

define( '_PAYPLAN_PERUNIT1', 'Dage');
define( '_PAYPLAN_PERUNIT2', 'Uger');
define( '_PAYPLAN_PERUNIT3', 'M&aring;neder');
define( '_PAYPLAN_PERUNIT4', '&Aring;r');

// General Params

define( '_PAYPLAN_DETAIL_TITLE', 'Plan');
define( '_PAYPLAN_GENERAL_NAME_NAME', 'Navn:');
define( '_PAYPLAN_GENERAL_NAME_DESC', 'Navn eller titel p&aring; denne plan. Maks.: 40 karakterer.');
define( '_PAYPLAN_GENERAL_DESC_NAME', 'Beskrivelse:');
define( '_PAYPLAN_GENERAL_DESC_DESC', 'Fuld beskrivelse af planen, som du &oslash;nsker at den skal pr&aelig;senteres for brugeren. Maks.: 255 karakterer.');
define( '_PAYPLAN_GENERAL_ACTIVE_NAME', 'Publiceret:');
define( '_PAYPLAN_GENERAL_ACTIVE_DESC', 'En publiceret plan vil v&aelig;re tilg&aelig;neglig for brugeren i frontend.');
define( '_PAYPLAN_GENERAL_VISIBLE_NAME', 'Synlig:');
define( '_PAYPLAN_GENERAL_VISIBLE_DESC', 'Synlige planer vil vises i frontend. Usynlige planer vil ikke vises og derfor kun v&aelig;re tilg&aelig;ngelige for automatiske applikationer (s&aring;som Tilbagefald eller indf&oslash;rselsplaner).');

define( '_PAYPLAN_GENERAL_CUSTOMAMOUNTFORMAT_NAME', 'Brugerdefineret bel&oslash;b formatering:');
define( '_PAYPLAN_GENERAL_CUSTOMAMOUNTFORMAT_DESC', 'Brug venligst en aecJSON streng som den der allerede er indtastet for at modificere hvordan prisen for denne plan vises.');
define( '_PAYPLAN_GENERAL_CUSTOMTHANKS_NAME', 'Brugerdefineret takke side link:');
define( '_PAYPLAN_GENERAL_CUSTOMTHANKS_DESC', 'Angiv et fuldst&aelig;ndigt link (inklusiv http://) som f&oslash;rer til din brugerdefinerede takkeside. Efterlad dette felt tomt hvis du ikke &oslash;nsker at bruge dette overhovedet.');
define( '_PAYPLAN_GENERAL_CUSTOMTEXT_THANKS_KEEPORIGINAL_NAME', 'Behold original tekst');
define( '_PAYPLAN_GENERAL_CUSTOMTEXT_THANKS_KEEPORIGINAL_DESC', 'V&aelig;lg denne indstilling hvis du &oslash;nsker at beholde den originale tekst p&aring; Takke siden');
define( '_PAYPLAN_GENERAL_CUSTOMTEXT_THANKS_NAME', 'Brugeredefineret tekst p&aring; takke side');
define( '_PAYPLAN_GENERAL_CUSTOMTEXT_THANKS_DESC', 'Tekst der vil blive vist p&aring; takke siden');

define( '_PAYPLAN_PARAMS_OVERRIDE_ACTIVATION_NAME', 'Tilsides&aelig;t aktivering');
define( '_PAYPLAN_PARAMS_OVERRIDE_ACTIVATION_DESC', 'Tilsides&aelig;t kravet om at en bruger skal aktivere kontoen (via email aktiveringskode) i tilf&aelig;lde af at denne betalingsplan bruges sammen med en registrering.');
define( '_PAYPLAN_PARAMS_OVERRIDE_REGMAIL_NAME', 'Tilsides&aelig;t registreringsemail');
define( '_PAYPLAN_PARAMS_OVERRIDE_REGMAIL_DESC', 'Udsend ikke nogen registreringsemail (dette giver mening for betalte planer som ikke kr&aelig;ver aktivering og hvor en email udsendes n&aring;r betalingen modtages - med email MI).');

define( '_PAYPLAN_PARAMS_GID_ENABLED_NAME', 'Aktiver brugergruppe');
define( '_PAYPLAN_PARAMS_GID_ENABLED_DESC', 'Skift dette til Ja hvis du &oslash;nsker at brugere skal tilknyttes til den valgte brugergruppe.');
define( '_PAYPLAN_PARAMS_GID_NAME', 'Tilf&oslash;j bruger til gruppe:');
define( '_PAYPLAN_PARAMS_GID_DESC', 'Brugere vil blive tilknyttet til denne brugergruppe n&aring;r planen er anvendt.');
define( '_PAYPLAN_PARAMS_MAKE_ACTIVE_NAME', 'S&aelig;t aktiv:');
define( '_PAYPLAN_PARAMS_MAKE_ACTIVE_DESC', 'S&aelig;t dette til >Nej< hvis du &oslash;nsker at aktivere en bruger manuelt efter at han eller hun har betalt.');
define( '_PAYPLAN_PARAMS_MAKE_PRIMARY_NAME', 'Prim&aelig;r:');
define( '_PAYPLAN_PARAMS_MAKE_PRIMARY_DESC', 'S&aelig;t dette til "Ja" for at g&oslash;re dette til den prim&aelig;re medlemsplan for brugeren. Det prim&aelig;re medlemskab er det der st&aring;r for det overordnede websteds udl&oslash;b.');
define( '_PAYPLAN_PARAMS_UPDATE_EXISTING_NAME', 'Opdater eksisterende:');
define( '_PAYPLAN_PARAMS_UPDATE_EXISTING_DESC', 'Hvis det ikke er en prim&aelig;r plan, skal denne plan s&aring; opdatere andre eksisterende ikke-prim&aelig;re medlemskaber for denne bruger? Dette kan v&aelig;re anvendeligt for sekund&aelig;re medlemskaber som brugeren kun skal have en ad gangen.');

define( '_PAYPLAN_TEXT_TITLE', 'R&aring; tekst');
define( '_PAYPLAN_GENERAL_EMAIL_DESC_NAME', 'Email beskrivelse:');
define( '_PAYPLAN_GENERAL_EMAIL_DESC_DESC', 'Tekst som skal tilf&oslash;jes til emailen som brugeren modtager n&aring;r en plan bliver aktiveret for ham');
define( '_PAYPLAN_GENERAL_FALLBACK_NAME', 'Plan tilbagefald:');
define( '_PAYPLAN_GENERAL_FALLBACK_DESC', 'N&aring;r en brugers medlemskab udl&oslash;ber - s&aring; g&oslash;r ham til medlem af den f&oslash;lgende plan');
define( '_PAYPLAN_GENERAL_FALLBACK_REQ_PARENT_NAME', 'Kr&aelig;v aktiv overordnet:');
define( '_PAYPLAN_GENERAL_FALLBACK_REQ_PARENT_DESC', 'Tillad kun tilbagefaldsplanen hvis dette er et sekund&aelig;rt medlemskab og det prim&aelig;re medlemskab for denne bruger stadig er aktivt.');
define( '_PAYPLAN_GENERAL_STANDARD_PARENT_NAME', 'Standard overordnet plan');
define( '_PAYPLAN_GENERAL_STANDARD_PARENT_DESC', 'Tilknytter aktuelt denne plan som brugeren rod medlemskab i tilf&aelig;lde af at han eller hun kun tilmelder sig en sekund&aelig;r plan.');

define( '_PAYPLAN_GENERAL_PROCESSORS_NAME', 'Betalinsgateway:');
define( '_PAYPLAN_NOPLAN', 'Ingen plan');
define( '_PAYPLAN_NOGW', 'Ingen gateway');
define( '_PAYPLAN_GENERAL_PROCESSORS_DESC', 'V&aelig;lg de betalings gateways som du &oslash;nsker skal v&aelig;re tilg&aelig;ngelige for denne plan. Hold Control eller Shift tasten nede for at v&aelig;lge flere muligheder. Ved valg af ' . _PAYPLAN_NOPLAN . ' s&aring; vil alle andre valg blive ignoreret. Hvis du kun ser ' . _PAYPLAN_NOPLAN . ' her betyder det at du ikke har aktiveret nogen betalings processor i dine konfigurationsindstillinger.');
define( '_PAYPLAN_PARAMS_LIFETIME_NAME', 'Livstid:');
define( '_PAYPLAN_PARAMS_LIFETIME_DESC', 'G&oslash;r dette til et livstidsmedlemskab som aldrig udl&oslash;ber.');

define( '_PAYPLAN_AMOUNT_NOTICE', 'Bem&aelig;rk for perioder');
define( '_PAYPLAN_AMOUNT_NOTICE_TEXT', 'For Paypal medlemskaber, er der en gr&aelig;nse for det maksimale bel&oslash;b af det du kan angive for perioden. Hvis du &oslash;nsker at bruge Paypal medlemskaber, <strong>begr&aelig;ns venligst dage til 90, uger til 52, m&aring;neder til 24 og &aring;r til 5 som maksimum</strong>.');
define( '_PAYPLAN_AMOUNT_EDITABLE_NOTICE', 'Der er en eller flere brugere der brugere gentagne betalinger for denne plan, s&aring; det anbefales ikke at &aelig;ndre forholdende indtil disse er annulleret.');

define( '_PAYPLAN_REGULAR_TITLE', 'Normalt medlemskab');
define( '_PAYPLAN_PARAMS_FULL_FREE_NAME', 'Gratis:');
define( '_PAYPLAN_PARAMS_FULL_FREE_DESC', 'S&aelig;t dette til Ja hvis du &oslash;nsker at tilbyde denne plan gratis');
define( '_PAYPLAN_PARAMS_FULL_AMOUNT_NAME', 'Regul&aelig;r rate:');
define( '_PAYPLAN_PARAMS_FULL_AMOUNT_DESC', 'Prisen for medlemskabet. Hvis der ikke er nogle medlemmer til denne plan s&aring; kan dette felt ikke &aelig;ndres. Hvis du &oslash;nsker at erstatte denne plan, s&aring; afpublicer den og lav en ny.');
define( '_PAYPLAN_PARAMS_FULL_PERIOD_NAME', 'Periode:');
define( '_PAYPLAN_PARAMS_FULL_PERIOD_DESC', 'Dette er betalingsperioden. Tallet bliver modificeret af den regul&aelig;re betalingsperiode enhed (nedenunder). Hvis der er medlemmer til denne plan s&aring; kan dette felt ikke &aelig;ndres. Hvis du &oslash;nsker at erstatte denne plan, s&aring; afpublicer den og lav en ny.');
define( '_PAYPLAN_PARAMS_FULL_PERIODUNIT_NAME', 'Periode enhed:');
define( '_PAYPLAN_PARAMS_FULL_PERIODUNIT_DESC', 'Dette er enhederne for betalingsperioden (ovenover). Hvis der er medlemmer til denne plan s&aring; kan dette felt ikke &aelig;ndres. Hvis du &oslash;nsker at erstatte denne plan, s&aring; afpublicer den og lav en ny.');

define( '_PAYPLAN_TRIAL_TITLE', 'Pr&oslash;veperiode');
define( '_PAYPLAN_TRIAL', '(Valgfri)');
define( '_PAYPLAN_TRIAL_DESC', 'Spring denne sektion over hvis du ikke &oslash;nsker at tilbyde pr&oslash;veperioder for dine medlemskaber. <strong>Pr&oslash;veperioder virker kun automatisk med PayPal medlemskaber!</strong>');
define( '_PAYPLAN_PARAMS_TRIAL_FREE_NAME', 'Gratis:');
define( '_PAYPLAN_PARAMS_TRIAL_FREE_DESC', 'S&aelig;t dette til ja hvis du &oslash;nsker at tilbyd gratis pr&oslash;veperioder');
define( '_PAYPLAN_PARAMS_TRIAL_AMOUNT_NAME', 'Pr&oslash;ve rate:');
define( '_PAYPLAN_PARAMS_TRIAL_AMOUNT_DESC', 'Angiv bel&oslash;bet som straks skal tr&aelig;kkes fra medlemstegneren.');
define( '_PAYPLAN_PARAMS_TRIAL_PERIOD_NAME', 'Pr&oslash;verperioder:');
define( '_PAYPLAN_PARAMS_TRIAL_PERIOD_DESC', 'Dette er l&aelig;ngden af pr&oslash;veperioden. Tallet bliver modificeret af den regul&aelig;re betalings cyclus enhed (nedenunder). Hvis der er medlemmer i denne plan s&aring; kan feltet ikke &aelig;ndres. Hvis du &oslash;nsker at erstatte denne plan, s&aring; afpublicer den og opret en ny.');
define( '_PAYPLAN_PARAMS_TRIAL_PERIODUNIT_NAME', 'Pr&oslash;veperiode enhed:');
define( '_PAYPLAN_PARAMS_TRIAL_PERIODUNIT_DESC', 'Dette er enhederne for pr&oslash;verperioden (ovenover).Hvis der er medlemmer i denne plan s&aring; kan feltet ikke &aelig;ndres. Hvis du &oslash;nsker at erstatte denne plan, s&aring; afpublicer den og opret en ny.');

define( '_PAYPLAN_PARAMS_FIXED_REDIRECT_NAME', 'Omdiriger altid');
define( '_PAYPLAN_PARAMS_FIXED_REDIRECT_DESC', 'Omdiriger til en anden URL hvis brugeren f&oslash;lger et direkte link til dette element.');
define( '_PAYPLAN_PARAMS_NOTAUTH_REDIRECT_NAME', 'Adgang n&aelig;gtet omdiriger');
define( '_PAYPLAN_PARAMS_NOTAUTH_REDIRECT_DESC', 'Omdiriger til en anden URL hvis brugeren f&oslash;lger et direkte link til dette element uden at have adgangstilladelser til dette.');
define( '_PAYPLAN_PARAMS_HIDE_DURATION_CHECKOUT_NAME', 'Skjul varighed');
define( '_PAYPLAN_PARAMS_HIDE_DURATION_CHECKOUT_DESC', 'Skift dette til Ja for at skjule den automatiske visning af plan varigheden ved betaling.');

define( '_PAYPLAN_PARAMS_CART_BEHAVIOR_NAME', 'Indk&oslash;bsvogn opf&oslash;rsel');
define( '_PAYPLAN_PARAMS_CART_BEHAVIOR_DESC', 'V&aelig;lg hvordan indk&oslash;bsvognen skal opf&oslash;re sig. Du kan lade systemet afg&oslash;re hvad der skal g&oslash;res ("Nedarv"), tvinge systemet til at oprette en indk&oslash;bskurv ("Gennemtving indk&oslash;bskurv") - selvom det er deaktiveret (brugeren skal dog stadig v&aelig;re logget p&aring;), eller du kan s&aelig;tte det til altid at foretage en direkte betaling ("Gennemtving direkte betaling") og der vil ikke oprettes en indk&oslash;bskurv selvom det m&aring;ske er systemindstillingen.');
define( '_PAYPLAN_PARAMS_ADDTOCART_REDIRECT_NAME', 'Omdiriger ved Tilf&oslash;j til indk&oslash;bskurv');
define( '_PAYPLAN_PARAMS_ADDTOCART_REDIRECT_DESC', 'Omdiriger til en fast URL (istedet for til indk&oslash;bskurven eller plan siden) n&aring;r brugeren placerer et element i indk&oslash;bskurven.');

define( '_PAYPLAN_CARTMODE_INHERIT', 'Nedarv');
define( '_PAYPLAN_CARTMODE_FORCE_CART', 'Gennetving indk&oslash;bskurv');
define( '_PAYPLAN_CARTMODE_FORCE_DIRECT', 'Gennemtving direkte betaling');

// Payplan Relations

define( '_PAYPLAN_RELATIONS_TITLE', 'Relationer');
define( '_PAYPLAN_PARAMS_SIMILARPLANS_NAME', 'Lignenede planer:');
define( '_PAYPLAN_PARAMS_SIMILARPLANS_DESC', 'V&aelig;lg hvilke planer der ligner denne. En bruger har ikke tilladelse til at bruge en pr&oslash;veperiode n&aring;r der k&oslash;bes en plan som han eller hun har k&oslash;bt inden og det vil ogs&aring; v&aelig;re g&aelig;ldende for lignende planer.');
define( '_PAYPLAN_PARAMS_EQUALPLANS_NAME', 'Ens planer:');
define( '_PAYPLAN_PARAMS_EQUALPLANS_DESC', 'V&aelig;lg hvilke planer der er ens med denne. En bruger der skifter mellem ens planer vil f&aring; hans eller hendes periode udvidet i stedet for nulstillet. Pr&oslash;verperioder er heller ikke tilladt (se info for lignende planer).');

// Payplan Restrictions

define( '_PAYPLAN_RESTRICTIONS_TITLE', 'Restriktioner');

define( '_PAYPLAN_RESTRICTIONS_MINGID_ENABLED_NAME', 'Aktiver Min GID:');
define( '_PAYPLAN_RESTRICTIONS_MINGID_ENABLED_DESC', 'Aktiver denne indstilling hvis du &oslash;nsker at begr&aelig;nse om en bruger f&aring;r vist denne plan i en minimum brugergruppe.');
define( '_PAYPLAN_RESTRICTIONS_MINGID_NAME', 'Synlighed gruppe:');
define( '_PAYPLAN_RESTRICTIONS_MINGID_DESC', 'Det minimale brugerniveau kr&aelig;vet for at se denne plan n&aring;r betalingsplanssiden bygges. Nye brugere vil altid se planerne med den lavest tilg&aelig;ngelig gid.');
define( '_PAYPLAN_RESTRICTIONS_FIXGID_ENABLED_NAME', 'Aktiver fast GID:');
define( '_PAYPLAN_RESTRICTIONS_FIXGID_ENABLED_DESC', 'Aktiver denne indstilling hvis du &oslash;nsker at begr&aelig;nse denne plan til en brugergruppe.');
define( '_PAYPLAN_RESTRICTIONS_FIXGID_NAME', 'S&aelig;t gruppe:');
define( '_PAYPLAN_RESTRICTIONS_FIXGID_DESC', 'Kun brugere med denne brugergruppe kan se denne plan.');
define( '_PAYPLAN_RESTRICTIONS_MAXGID_ENABLED_NAME', 'Aktiver maks GID:');
define( '_PAYPLAN_RESTRICTIONS_MAXGID_ENABLED_DESC', 'Aktiver denne indstilling hvis du &oslash;nsker at begr&aelig;nse om en bruger f&aring;r vist denne plan ved en maksimum brugergruppe.');
define( '_PAYPLAN_RESTRICTIONS_MAXGID_NAME', 'Maksimum gruppe:');
define( '_PAYPLAN_RESTRICTIONS_MAXGID_DESC', 'Maksimum brugerniveau som en bruger kan have for at se denne plan n&aring;r plan betalingssiden bygges.');

define( '_PAYPLAN_RESTRICTIONS_PREVIOUSPLAN_REQ_ENABLED_NAME', 'Kr&aelig;vet tidligere plan:');
define( '_PAYPLAN_RESTRICTIONS_PREVIOUSPLAN_REQ_ENABLED_DESC', 'Aktiver tjek for tidligere betalingsplan');
define( '_PAYPLAN_RESTRICTIONS_PREVIOUSPLAN_REQ_NAME', 'Plan:');
define( '_PAYPLAN_RESTRICTIONS_PREVIOUSPLAN_REQ_DESC', 'En bruger vil kun se denne plan hvis han eller hun brugte den valgte plan f&oslash;r den der aktuelt er i brug');
define( '_PAYPLAN_RESTRICTIONS_CURRENTPLAN_REQ_ENABLED_NAME', 'Kr&aelig;vet aktuel plan:');
define( '_PAYPLAN_RESTRICTIONS_CURRENTPLAN_REQ_ENABLED_DESC', 'Aktiver tjek for aktuel betalingsplan');
define( '_PAYPLAN_RESTRICTIONS_CURRENTPLAN_REQ_NAME', 'Plan:');
define( '_PAYPLAN_RESTRICTIONS_CURRENTPLAN_REQ_DESC', 'En bruger vil kun se denne plan hvis han eller hun aktuelt er tilknyttet til, eller nyligt har udl&oslash;bet et medlemskab til planen angivet her');
define( '_PAYPLAN_RESTRICTIONS_OVERALLPLAN_REQ_ENABLED_NAME', 'Kr&aelig;vet brugt plan:');
define( '_PAYPLAN_RESTRICTIONS_OVERALLPLAN_REQ_ENABLED_DESC', 'Aktiver tjek for overordent brugt betalingsplan');
define( '_PAYPLAN_RESTRICTIONS_OVERALLPLAN_REQ_NAME', 'Plan:');
define( '_PAYPLAN_RESTRICTIONS_OVERALLPLAN_REQ_DESC', 'En bruger vil kun se denne plan hvis han eller hun har brugt den valgte plan en gang uanset hvorn&aring;r');

define( '_PAYPLAN_RESTRICTIONS_PREVIOUSPLAN_REQ_ENABLED_EXCLUDED_NAME', 'Ekskluderet tidligere plan:');
define( '_PAYPLAN_RESTRICTIONS_PREVIOUSPLAN_REQ_ENABLED_EXCLUDED_DESC', 'Vis IKKE denne plan til brugere som havde den valgte plan som deres tidligere betalingsplan');
define( '_PAYPLAN_RESTRICTIONS_PREVIOUSPLAN_REQ_EXCLUDED_NAME', 'Plan:');
define( '_PAYPLAN_RESTRICTIONS_PREVIOUSPLAN_REQ_EXCLUDED_DESC', 'En bruger kan ikke se denne plan hvis han eller hun brugte den valgte plan f&oslash;r den der aktuelt er i brug');
define( '_PAYPLAN_RESTRICTIONS_CURRENTPLAN_REQ_ENABLED_EXCLUDED_NAME', 'Ekskluderet aktuel plan:');
define( '_PAYPLAN_RESTRICTIONS_CURRENTPLAN_REQ_ENABLED_EXCLUDED_DESC', 'Vis IKKE denne plan til brugere som har den valgte plan som deres aktuelle betalingsplan');
define( '_PAYPLAN_RESTRICTIONS_CURRENTPLAN_REQ_EXCLUDED_NAME', 'Plan:');
define( '_PAYPLAN_RESTRICTIONS_CURRENTPLAN_REQ_EXCLUDED_DESC', 'En bruger vil ikke kunne se denne plan hvis han eller hun aktuelt er tilknyttet til, eller medlemskabet lige er udl&oslash;bet for den valgte plan');
define( '_PAYPLAN_RESTRICTIONS_OVERALLPLAN_REQ_ENABLED_EXCLUDED_NAME', 'Ekskluderet brugt plan:');
define( '_PAYPLAN_RESTRICTIONS_OVERALLPLAN_REQ_ENABLED_EXCLUDED_DESC', 'Vis IKKE denne plan til brugere som har brugt den valgte plan f&oslash;r');
define( '_PAYPLAN_RESTRICTIONS_OVERALLPLAN_REQ_EXCLUDED_NAME', 'Plan:');
define( '_PAYPLAN_RESTRICTIONS_OVERALLPLAN_REQ_EXCLUDED_DESC', 'En bruger vil ikke kunne se denne plan hvis han eller hun har brugt den valgte plan engang, uanset hvorn&aring;r');

define( '_PAYPLAN_RESTRICTIONS_USED_PLAN_MIN_ENABLED_NAME', 'Min brugte plan:');
define( '_PAYPLAN_RESTRICTIONS_USED_PLAN_MIN_ENABLED_DESC', 'Aktiver tjek for det minimale antal gange dine brugere har tegnet medlemskab til en angivet betalingsplan for at se DENNE plan');
define( '_PAYPLAN_RESTRICTIONS_USED_PLAN_MIN_AMOUNT_NAME', 'Antal gange:');
define( '_PAYPLAN_RESTRICTIONS_USED_PLAN_MIN_AMOUNT_DESC', 'Det minimale antal gange en bruger skal have brugt den valgte plan');
define( '_PAYPLAN_RESTRICTIONS_USED_PLAN_MIN_NAME', 'Plan:');
define( '_PAYPLAN_RESTRICTIONS_USED_PLAN_MIN_DESC', 'Betalingsplanen som brugeren skal have anvendt mindst det angivne antal gange');
define( '_PAYPLAN_RESTRICTIONS_USED_PLAN_MAX_ENABLED_NAME', 'Maks brugt plan:');
define( '_PAYPLAN_RESTRICTIONS_USED_PLAN_MAX_ENABLED_DESC', 'Aktiver tjek for det maksimale antal gange dine brugere har tegnet medlemskab til den angivet plan for at kunne se DENNE plan');
define( '_PAYPLAN_RESTRICTIONS_USED_PLAN_MAX_AMOUNT_NAME', 'Antal gange:');
define( '_PAYPLAN_RESTRICTIONS_USED_PLAN_MAX_AMOUNT_DESC', 'Det maksimale antal gange en bruger kan have brugt den valgte plan');
define( '_PAYPLAN_RESTRICTIONS_USED_PLAN_MAX_NAME', 'Plan:');
define( '_PAYPLAN_RESTRICTIONS_USED_PLAN_MAX_DESC', 'Betalingsplanen som brugeren maksimalt skal have brugt det angivne antal gange');

define( '_PAYPLAN_RESTRICTIONS_PREVIOUSGROUP_REQ_ENABLED_NAME', 'Kr&aelig;vet tidligere gruppe:');
define( '_PAYPLAN_RESTRICTIONS_PREVIOUSGROUP_REQ_ENABLED_DESC', 'Aktiver tjek for tidligere betalingsplan i denne gruppe');
define( '_PAYPLAN_RESTRICTIONS_PREVIOUSGROUP_REQ_NAME', 'Gruppe:');
define( '_PAYPLAN_RESTRICTIONS_PREVIOUSGROUP_REQ_DESC', 'En bruger vil kun se denne plan hvis han eller hun  har brugt en plan i denne gruppe f&oslash;r den der aktuelt er i brug');
define( '_PAYPLAN_RESTRICTIONS_CURRENTGROUP_REQ_ENABLED_NAME', 'Kr&aelig;vet aktuel gruppe:');
define( '_PAYPLAN_RESTRICTIONS_CURRENTGROUP_REQ_ENABLED_DESC', 'Aktiver tjek for aktuelt betalingsplan i denne gruppe');
define( '_PAYPLAN_RESTRICTIONS_CURRENTGROUP_REQ_NAME', 'Gruppe:');
define( '_PAYPLAN_RESTRICTIONS_CURRENTGROUP_REQ_DESC', 'En bruger vil kun se denne plan hvis han eller hun aktuelt er tilknyttet til, eller medlemskabet just er udl&oslash;bet for en plan i den her valgte gruppe');
define( '_PAYPLAN_RESTRICTIONS_OVERALLGROUP_REQ_ENABLED_NAME', 'Kr&aelig;vet anvendt gruppe:');
define( '_PAYPLAN_RESTRICTIONS_OVERALLGROUP_REQ_ENABLED_DESC', 'Aktiver tjek for overordnet anvendt betalingsplan i denne gruppe');
define( '_PAYPLAN_RESTRICTIONS_OVERALLGROUP_REQ_NAME', 'Gruppe:');
define( '_PAYPLAN_RESTRICTIONS_OVERALLGROUP_REQ_DESC', 'En bruger vil kun kunne se denne plan hvis han eller hun har brugt den valgte plan tidliger i denne gruppe, uanset hvorn&aring;r');

define( '_PAYPLAN_RESTRICTIONS_PREVIOUSGROUP_REQ_ENABLED_EXCLUDED_NAME', 'Ekskluderet forrige gruppe:');
define( '_PAYPLAN_RESTRICTIONS_PREVIOUSGROUP_REQ_ENABLED_EXCLUDED_DESC', 'Vis IKKE denne plan til brugere som har haft en plan i denne gruppe som deres tidligere betalingsplan');
define( '_PAYPLAN_RESTRICTIONS_PREVIOUSGROUP_REQ_EXCLUDED_NAME', 'Gruppe:');
define( '_PAYPLAN_RESTRICTIONS_PREVIOUSGROUP_REQ_EXCLUDED_DESC', 'En bruger ser ikke denne plan hvis han eller hun anvendte en plan i denne gruppe f&oslash;r den der aktuelt er i brug');
define( '_PAYPLAN_RESTRICTIONS_CURRENTGROUP_REQ_ENABLED_EXCLUDED_NAME', 'Ekskluderet aktuel gruppe:');
define( '_PAYPLAN_RESTRICTIONS_CURRENTGROUP_REQ_ENABLED_EXCLUDED_DESC', 'Vis IKKE denne plan til brugere som har en plan i denne gruppe som deres aktuelle betalingsplan');
define( '_PAYPLAN_RESTRICTIONS_CURRENTGROUP_REQ_EXCLUDED_NAME', 'Gruppe:');
define( '_PAYPLAN_RESTRICTIONS_CURRENTGROUP_REQ_EXCLUDED_DESC', 'En bruger vil ikke kunne se denne plan hvis han eller hun aktuelt er tilknyttet til, eller et medlemskab for en plan i denne gruppe just er udl&oslash;bet');
define( '_PAYPLAN_RESTRICTIONS_OVERALLGROUP_REQ_ENABLED_EXCLUDED_NAME', 'Ekskluderet anvendt gruppe:');
define( '_PAYPLAN_RESTRICTIONS_OVERALLGROUP_REQ_ENABLED_EXCLUDED_DESC', 'Vis IKKE denne plan til brugere som har brugt planen i denne gruppe f&oslash;r');
define( '_PAYPLAN_RESTRICTIONS_OVERALLGROUP_REQ_EXCLUDED_NAME', 'Gruppe:');
define( '_PAYPLAN_RESTRICTIONS_OVERALLGROUP_REQ_EXCLUDED_DESC', 'En bruger vil ikke kunne se denne plan hvis han eller hun har anvendt en plan i denne gruppe tidligere, uanset hvorn&aring;r');

define( '_PAYPLAN_RESTRICTIONS_USED_GROUP_MIN_ENABLED_NAME', 'Min. brugt gruppe:');
define( '_PAYPLAN_RESTRICTIONS_USED_GROUP_MIN_ENABLED_DESC', 'Aktiver tjek for det minim ale antal gange dine brugere har tegnet medlemskab til en plan i denne gruppe for at se DENNE plan');
define( '_PAYPLAN_RESTRICTIONS_USED_GROUP_MIN_AMOUNT_NAME', 'Brugt antal gange:');
define( '_PAYPLAN_RESTRICTIONS_USED_GROUP_MIN_AMOUNT_DESC', 'Det minimale antal gange en bruger skal have brugt en plan i denne gruppe');
define( '_PAYPLAN_RESTRICTIONS_USED_GROUP_MIN_NAME', 'Gruppe:');
define( '_PAYPLAN_RESTRICTIONS_USED_GROUP_MIN_DESC', 'Gruppen som brugeren skal have anvendt en plan fra - mindst det angivne antal gange');
define( '_PAYPLAN_RESTRICTIONS_USED_GROUP_MAX_ENABLED_NAME', 'Maks. brugt gruppe:');
define( '_PAYPLAN_RESTRICTIONS_USED_GROUP_MAX_ENABLED_DESC', 'Aktiver tjek for det maksimale antal ganeg dine brugere har tegnet medlemskab til en plan i denne gruppe for at se DENNE plan');
define( '_PAYPLAN_RESTRICTIONS_USED_GROUP_MAX_AMOUNT_NAME', 'Brugt antal gange:');
define( '_PAYPLAN_RESTRICTIONS_USED_GROUP_MAX_AMOUNT_DESC', 'Det maksimale antal gange en bruger kan have brugt en plan i denne gruppe');
define( '_PAYPLAN_RESTRICTIONS_USED_GROUP_MAX_NAME', 'Gruppe:');
define( '_PAYPLAN_RESTRICTIONS_USED_GROUP_MAX_DESC', 'Gruppen som brugeren skal have brugt en plan fra - maksimalt det angivne antal gange');

define( '_PAYPLAN_RESTRICTIONS_CUSTOM_RESTRICTIONS_ENABLED_NAME', 'Brug brugderdefinerede begr&aelig;nsninger:');
define( '_PAYPLAN_RESTRICTIONS_CUSTOM_RESTRICTIONS_ENABLED_DESC', 'Aktiver brugen af det nedenunder angivne begr&aelig;nsninger');
define( '_PAYPLAN_RESTRICTIONS_CUSTOM_RESTRICTIONS_NAME', 'Brugerdefinerede begr&aelig;nsninger:');
define( '_PAYPLAN_RESTRICTIONS_CUSTOM_RESTRICTIONS_DESC', 'Brug RewriteEngine felter for at tjekke specifikke strenge i formen:<br />[[user_id]] >= 1500<br />[[parametername]] = value<br />(Opret seperate regler ved at angive en ny linje).<br />Du kan bruge =, <=, >=, <, >, <> som sammenlignende elementer. Du SKAL bruger mellemrum mellem parametre, v&aelig;rdier og komparatorer!');

define( '_PAYPLAN_PROCESSORS_TITLE', 'Processorer');
define( '_PAYPLAN_PROCESSORS_TITLE_LONG', 'Betalingsprocessorer');

define( '_PAYPLAN_PROCESSORS_ACTIVATE_NAME', 'Aktiv');
define( '_PAYPLAN_PROCESSORS_ACTIVATE_DESC', 'Tilbyd betalingsgatewayen for denne betalingsplan.');
define( '_PAYPLAN_PROCESSORS_OVERWRITE_SETTINGS_NAME', 'Tilsides&aelig;t globale indstillinger');
define( '_PAYPLAN_PROCESSORS_OVERWRITE_SETTINGS_DESC', 'Hvis du &oslash;nsker det, kan du markere denne boks og efter lagning af denne plan redigere enhver indstilling fra den globale konfiguration til at v&aelig;re for forskellige for denne individuelle plan.');

define( '_PAYPLAN_MI', 'Micro integr.');
define( '_PAYPLAN_GENERAL_MICRO_INTEGRATIONS_NAME', 'Micro integrationer:');
define( '_PAYPLAN_GENERAL_MICRO_INTEGRATIONS_DESC', 'V&aelig;lg de micro integrationer som du &oslash;nsker at anvende p&aring; brugeren med denne plan.');
define( '_PAYPLAN_GENERAL_MICRO_INTEGRATIONS_PLAN_NAME', 'Lokale micro integrationer:');
define( '_PAYPLAN_GENERAL_MICRO_INTEGRATIONS_PLAN_DESC', 'V&aelig;lg de micro integrationer som du &oslash;nsker at anvende p&aring; brugeren med denne plan. Istedet for globale instanser, vil disse MIer kun v&aelig;re specifikke for netop denne betalingsplan.');
define( '_PAYPLAN_GENERAL_MICRO_INTEGRATIONS_INHERITED_NAME', 'Nedarvet:');
define( '_PAYPLAN_GENERAL_MICRO_INTEGRATIONS_INHERITED_DESC', 'Viser hvilke micro integrationer der er nedarvet fra overordnede grupper som denne plan er medlem af.');

define( '_PAYPLAN_CURRENCY', 'Valuta');

// --== Group PAGE ==--

define( '_ITEMGROUPS_TITLE', 'Grupper');
define( '_ITEMGROUP_NAME', 'Navn');
define( '_ITEMGROUP_DESC', 'Beskrivelse (f&oslash;rste 50 karak.)');
define( '_ITEMGROUP_ACTIVE', 'Publiceret');
define( '_ITEMGROUP_VISIBLE', 'Synlig');
define( '_ITEMGROUP_REORDER', 'Gensorter');

define( '_PUBLISH_ITEMGROUP', 'Publicer');
define( '_UNPUBLISH_ITEMGROUP', 'Afpublicer');
define( '_NEW_ITEMGROUP', 'Ny');
define( '_COPY_ITEMGROUP', 'Kopier');
define( '_APPLY_ITEMGROUP', 'Anvend');
define( '_EDIT_ITEMGROUP', 'Rediger');
define( '_REMOVE_ITEMGROUP', 'Slet');
define( '_SAVE_ITEMGROUP', 'Gem');
define( '_CANCEL_ITEMGROUP', 'Annuller');

define( '_ITEMGROUP_DETAIL_TITLE', 'Gruppe');
define( '_AEC_HEAD_ITEMGROUP_INFO', 'Gruppe' );
define( '_ITEMGROUP_GENERAL_NAME_NAME', 'Navn:');
define( '_ITEMGROUP_GENERAL_NAME_DESC', 'Navn eller titel for denne gruppe. Maks.: 40 karakterer.');
define( '_ITEMGROUP_GENERAL_DESC_NAME', 'Beskrivelse:');
define( '_ITEMGROUP_GENERAL_DESC_DESC', 'Fuld beskrivelse af gruppe. Maks.: 255 karakterer.');
define( '_ITEMGROUP_GENERAL_ACTIVE_NAME', 'Publiceret:');
define( '_ITEMGROUP_GENERAL_ACTIVE_DESC', 'En publiceret gruppe vil v&aelig;re tilg&aelig;ngelig for brugeren i frontend.');
define( '_ITEMGROUP_GENERAL_VISIBLE_NAME', 'Synlig:');
define( '_ITEMGROUP_GENERAL_VISIBLE_DESC', 'Synlige grupper vil blive vist i frontend.');
define( '_ITEMGROUP_GENERAL_COLOR_NAME', 'Farve:');
define( '_ITEMGROUP_GENERAL_COLOR_DESC', 'Farvemarkeringen af denne gruppe.');
define( '_ITEMGROUP_GENERAL_ICON_NAME', 'Ikon:');
define( '_ITEMGROUP_GENERAL_ICON_DESC', 'Ikonet der markerer denne gruppe.');

define( '_ITEMGROUP_GENERAL_REVEAL_CHILD_ITEMS_NAME', 'Vis underordnede elementer');
define( '_ITEMGROUP_GENERAL_REVEAL_CHILD_ITEMS_DESC', 'Hvis du s&aelig;tter dette til "Ja", s&aring; vil AEC ikke vis en gruppeknap (der linker videre til indholdet af denne gruppe), men vise indholdet af denne gruppe i enhver overordnet gruppe direkte.');
define( '_ITEMGROUP_GENERAL_SYMLINK_NAME', 'Gruppe symlink');
define( '_ITEMGROUP_GENERAL_SYMLINK_DESC', 'Hvis du angivet et link her s&aring; vil brugeren omdirigeres til dette link n&aring;r han eller hun v&aelig;lger denne gruppe p&aring; plan valg siden. Tildsides&aelig;tter enhver linkning til indhold i denne gruppe!');
define( '_ITEMGROUP_GENERAL_SYMLINK_USERID_NAME', 'Symlink Brugerid');
define( '_ITEMGROUP_GENERAL_SYMLINK_USERID_DESC', 'Videregiv brugerid via en Joomla notifikation. Dette kan v&aelig;re anvendeligt for fleksible brugerdefinerede tilmeldingssider som skal fungere selvom brugeren ikke er logget p&aring;. Du kan bruge Javascript til at modificere dine tilmeldingslink i f&oslash;lge det videregivne brugerid.');

define( '_ITEMGROUP_GENERAL_NOTAUTH_REDIRECT_NAME', 'Adgang forbudt omdirigering');
define( '_ITEMGROUP_GENERAL_NOTAUTH_REDIRECT_DESC', 'Omdiriger til en anden URL hvis brugeren f&oslash;lger et direkte link  til dette element uden at have tilstr&aelig;kkelige rettigheder.');
define( '_ITEMGROUP_GENERAL_MICRO_INTEGRATIONS_NAME', 'Micro integrationer');
define( '_ITEMGROUP_GENERAL_MICRO_INTEGRATIONS_DESC', 'V&aelig;lg hvilke micro integrationer som du &oslash;nsker at tilknytte til alle underordnede elementer i denne gruppe.');

// Group Restrictions

define( '_ITEMGROUP_RESTRICTIONS_TITLE', 'Begr&aelig;nsninger');

define( '_ITEMGROUP_RESTRICTIONS_MINGID_ENABLED_NAME', 'Aktiver min. GID:');
define( '_ITEMGROUP_RESTRICTIONS_MINGID_ENABLED_DESC', 'Aktiver denne indstilling hvis du &oslash;nsker at begr&aelig;nse om en bruger f&aring;r vidst denne gruppe baseret p&aring; en midste brugergruppe.');
define( '_ITEMGROUP_RESTRICTIONS_MINGID_NAME', 'Synlighedsgruppe:');
define( '_ITEMGROUP_RESTRICTIONS_MINGID_DESC', 'Det mindste brugerniveau der kr&aelig;ves for at se denne gruppe n&aring;r betalingsplan siden opbygges. Nye brugere vil altid se gruppen med det lavest tilg&aelig;ngelige gid.');
define( '_ITEMGROUP_RESTRICTIONS_FIXGID_ENABLED_NAME', 'Aktiver fast GID:');
define( '_ITEMGROUP_RESTRICTIONS_FIXGID_ENABLED_DESC', 'Aktiver denne indstilling hvis du &oslash;nsker at begr&aelig;nse denne gruppe til en brugergruppe.');
define( '_ITEMGROUP_RESTRICTIONS_FIXGID_NAME', 'Set Gruppe:');
define( '_ITEMGROUP_RESTRICTIONS_FIXGID_DESC', 'Kun brugere med denne brugergruppe kan se denne gruppe.');
define( '_ITEMGROUP_RESTRICTIONS_MAXGID_ENABLED_NAME', 'Aktiver maks. GID:');
define( '_ITEMGROUP_RESTRICTIONS_MAXGID_ENABLED_DESC', 'Aktiver denne indstilling hvis du &oslash;nsker at begr&aelig;nse om en bruger f&aring;r vist denne gruppe baseret p&aring; at maksimum brugergruppe.');
define( '_ITEMGROUP_RESTRICTIONS_MAXGID_NAME', 'Maksimum gruppe:');
define( '_ITEMGROUP_RESTRICTIONS_MAXGID_DESC', 'Det maksimale brugerniveau som en bruger kan have for at se denne gruppe n&aring;r betalingsplan siden opbygges.');

define( '_ITEMGROUP_RESTRICTIONS_PREVIOUSPLAN_REQ_ENABLED_NAME', 'Kr&aelig;vet tidligere plan:');
define( '_ITEMGROUP_RESTRICTIONS_PREVIOUSPLAN_REQ_ENABLED_DESC', 'Aktiver tjek for tidligere betalingsplan');
define( '_ITEMGROUP_RESTRICTIONS_PREVIOUSPLAN_REQ_NAME', 'Plan:');
define( '_ITEMGROUP_RESTRICTIONS_PREVIOUSPLAN_REQ_DESC', 'En bruger vil kun kunne se denne gruppe hvis han eller hun har anvendt den valgte plan f&oslash;r den aktuelt anvendte');
define( '_ITEMGROUP_RESTRICTIONS_CURRENTPLAN_REQ_ENABLED_NAME', 'Kr&aelig;vet aktuel plan:');
define( '_ITEMGROUP_RESTRICTIONS_CURRENTPLAN_REQ_ENABLED_DESC', 'Aktiver tjek for aktuel betalingsplan');
define( '_ITEMGROUP_RESTRICTIONS_CURRENTPLAN_REQ_NAME', 'Plan:');
define( '_ITEMGROUP_RESTRICTIONS_CURRENTPLAN_REQ_DESC', 'En bruger vil kun kunne se denne gruppe hvis han eller hun aktuelt er tilknyttet til, eller hvis medlemskabet just er udl&oslash;bet for den valgte plan');
define( '_ITEMGROUP_RESTRICTIONS_OVERALLPLAN_REQ_ENABLED_NAME', 'Kr&aelig;vet brugt plan:');
define( '_ITEMGROUP_RESTRICTIONS_OVERALLPLAN_REQ_ENABLED_DESC', 'Aktiver tjek for overordnet anvendt betalingsplan');
define( '_ITEMGROUP_RESTRICTIONS_OVERALLPLAN_REQ_NAME', 'Plan:');
define( '_ITEMGROUP_RESTRICTIONS_OVERALLPLAN_REQ_DESC', 'En bruger vil kun kunne se denne plan hvis han eller hun har brugt den valgte plan tidligere, uanset hvorn&aring;r');

define( '_ITEMGROUP_RESTRICTIONS_PREVIOUSPLAN_REQ_ENABLED_EXCLUDED_NAME', 'Ekskluderet tidligere plan:');
define( '_ITEMGROUP_RESTRICTIONS_PREVIOUSPLAN_REQ_ENABLED_EXCLUDED_DESC', 'Vis IKKE denne gruppe til brugere som havde den valgte plan som deres forrige betalingsplan');
define( '_ITEMGROUP_RESTRICTIONS_PREVIOUSPLAN_REQ_EXCLUDED_NAME', 'Plan:');
define( '_ITEMGROUP_RESTRICTIONS_PREVIOUSPLAN_REQ_EXCLUDED_DESC', 'En bruger vil ikke kunne se denne gruppe hvis han eller hun har brugt den valgte plan f&oslash;r den aktuelt anvendte');
define( '_ITEMGROUP_RESTRICTIONS_CURRENTPLAN_REQ_ENABLED_EXCLUDED_NAME', 'Ekskluderet aktuel plan:');
define( '_ITEMGROUP_RESTRICTIONS_CURRENTPLAN_REQ_ENABLED_EXCLUDED_DESC', 'Vis IKKE denne gruppe til brugere som har den valgte plan som deres aktuelle betalingsplan');
define( '_ITEMGROUP_RESTRICTIONS_CURRENTPLAN_REQ_EXCLUDED_NAME', 'Plan:');
define( '_ITEMGROUP_RESTRICTIONS_CURRENTPLAN_REQ_EXCLUDED_DESC', 'En bruger vil ikke kunne se denne gruppe hvis han eller hun aktuelt er tilknyttet til den, eller medlemskabet lige er udl&oslash;bet for den valgte plan');
define( '_ITEMGROUP_RESTRICTIONS_OVERALLPLAN_REQ_ENABLED_EXCLUDED_NAME', 'Ekskluderet anvendt plan:');
define( '_ITEMGROUP_RESTRICTIONS_OVERALLPLAN_REQ_ENABLED_EXCLUDED_DESC', 'Vis IKKE denne gruppe til brugere som har anvendt den valgte plan tidligere');
define( '_ITEMGROUP_RESTRICTIONS_OVERALLPLAN_REQ_EXCLUDED_NAME', 'Plan:');
define( '_ITEMGROUP_RESTRICTIONS_OVERALLPLAN_REQ_EXCLUDED_DESC', 'En bruger vil ikke kunne se denne gruppe hvis han eller hun har anvendt den valgte plan tidligere, uanset hvorn&aring;r');

define( '_ITEMGROUP_RESTRICTIONS_USED_PLAN_MIN_ENABLED_NAME', 'Min. brugt plan:');
define( '_ITEMGROUP_RESTRICTIONS_USED_PLAN_MIN_ENABLED_DESC', 'Aktiver tjek for det mindste antal gange dine brugere har tegnet medlemskab til en angivet betalingsplan for at se denne gruppe');
define( '_ITEMGROUP_RESTRICTIONS_USED_PLAN_MIN_AMOUNT_NAME', 'Brugt antal gange:');
define( '_ITEMGROUP_RESTRICTIONS_USED_PLAN_MIN_AMOUNT_DESC', 'Det mindste antal gange en bruger skal have anvendte den valgte plan');
define( '_ITEMGROUP_RESTRICTIONS_USED_PLAN_MIN_NAME', 'Plan:');
define( '_ITEMGROUP_RESTRICTIONS_USED_PLAN_MIN_DESC', 'Betalingsplanen som brugeren skal have anvendt mindst det angivne antal gange');
define( '_ITEMGROUP_RESTRICTIONS_USED_PLAN_MAX_ENABLED_NAME', 'Maks. brugt plan:');
define( '_ITEMGROUP_RESTRICTIONS_USED_PLAN_MAX_ENABLED_DESC', 'Aktiver tjek for det maksimale antal gange en bruger m&aring; have tegnet medlemskab til en angivet betalingsplan for at kunne se denne gruppe');
define( '_ITEMGROUP_RESTRICTIONS_USED_PLAN_MAX_AMOUNT_NAME', 'Brugt antal gange:');
define( '_ITEMGROUP_RESTRICTIONS_USED_PLAN_MAX_AMOUNT_DESC', 'Det maksimale antal gange en bruger m&aring; have brugt den valgte plan');
define( '_ITEMGROUP_RESTRICTIONS_USED_PLAN_MAX_NAME', 'Plan:');
define( '_ITEMGROUP_RESTRICTIONS_USED_PLAN_MAX_DESC', 'Betalingsplanen som brugeren maksimalt m&aring; have anvendt det angivne antal gange');

define( '_ITEMGROUP_RESTRICTIONS_PREVIOUSGROUP_REQ_ENABLED_NAME', 'Kr&aelig;vet tidligere gruppe:');
define( '_ITEMGROUP_RESTRICTIONS_PREVIOUSGROUP_REQ_ENABLED_DESC', 'Aktiver tjek for en tidligere betalingsplan i denne gruppe');
define( '_ITEMGROUP_RESTRICTIONS_PREVIOUSGROUP_REQ_NAME', 'Gruppe:');
define( '_ITEMGROUP_RESTRICTIONS_PREVIOUSGROUP_REQ_DESC', 'En bruger vil kun kunne se denne gruppe hvis han eller hun har anvendt en plan i denne gruppe f&oslash;r den aktuelle plan');
define( '_ITEMGROUP_RESTRICTIONS_CURRENTGROUP_REQ_ENABLED_NAME', 'Kr&aelig;vet aktuel gruppe:');
define( '_ITEMGROUP_RESTRICTIONS_CURRENTGROUP_REQ_ENABLED_DESC', 'Aktiver tjek for aktuel betalingsplan i denne gruppe');
define( '_ITEMGROUP_RESTRICTIONS_CURRENTGROUP_REQ_NAME', 'Gruppe:');
define( '_ITEMGROUP_RESTRICTIONS_CURRENTGROUP_REQ_DESC', 'En bruger vil kun kunne se denne gruppe hvis han eller hun aktuelt er tilknyttet til, eller hvis medlemskabet just er udl&oslash;bet fra en plan i den valgte gruppe');
define( '_ITEMGROUP_RESTRICTIONS_OVERALLGROUP_REQ_ENABLED_NAME', 'Kr&aelig;vet brugt gruppe:');
define( '_ITEMGROUP_RESTRICTIONS_OVERALLGROUP_REQ_ENABLED_DESC', 'Aktiver tjek for overordnet anvendt betalingsplan i denne gruppe');
define( '_ITEMGROUP_RESTRICTIONS_OVERALLGROUP_REQ_NAME', 'Gruppe:');
define( '_ITEMGROUP_RESTRICTIONS_OVERALLGROUP_REQ_DESC', 'En bruger vil kun kunne se denne gruppe hvis han eller hun har anvendt en plan fra den valgte gruppe tidligere, uanset hvorn&aring;r');

define( '_ITEMGROUP_RESTRICTIONS_PREVIOUSGROUP_REQ_ENABLED_EXCLUDED_NAME', 'Ekskluderet tidliger gruppe:');
define( '_ITEMGROUP_RESTRICTIONS_PREVIOUSGROUP_REQ_ENABLED_EXCLUDED_DESC', 'Vis IKKE denne gruppe til brugere som havde en plan i den valgte gruppe som deres tidligere betalingsplan');
define( '_ITEMGROUP_RESTRICTIONS_PREVIOUSGROUP_REQ_EXCLUDED_NAME', 'Gruppe:');
define( '_ITEMGROUP_RESTRICTIONS_PREVIOUSGROUP_REQ_EXCLUDED_DESC', 'En bruger vil ikke kunne se denne gruppe hvis han eller hun har anvendt en plan fra den valgte gruppe f&oslash;r den aktuelle plan');
define( '_ITEMGROUP_RESTRICTIONS_CURRENTGROUP_REQ_ENABLED_EXCLUDED_NAME', 'Ekskluderet aktuel gruppe:');
define( '_ITEMGROUP_RESTRICTIONS_CURRENTGROUP_REQ_ENABLED_EXCLUDED_DESC', 'Vis IKKE denne gruppe til brugere som har en plan i den valgte gruppe som deres aktuelle betalingsplan');
define( '_ITEMGROUP_RESTRICTIONS_CURRENTGROUP_REQ_EXCLUDED_NAME', 'Gruppe:');
define( '_ITEMGROUP_RESTRICTIONS_CURRENTGROUP_REQ_EXCLUDED_DESC', 'En bruger vil ikke kunne se denne gruppe hvis han eller hun aktuelt er tilknyttet til, eller medlemskabet just er udl&oslash;bet for en plan i den valgte gruppe');
define( '_ITEMGROUP_RESTRICTIONS_OVERALLGROUP_REQ_ENABLED_EXCLUDED_NAME', 'Ekskluderet brugt gruppe:');
define( '_ITEMGROUP_RESTRICTIONS_OVERALLGROUP_REQ_ENABLED_EXCLUDED_DESC', 'Vis IKKE denne gruppe til brugere som har anvendt en plan i den valgte gruppe tidligere');
define( '_ITEMGROUP_RESTRICTIONS_OVERALLGROUP_REQ_EXCLUDED_NAME', 'Gruppe:');
define( '_ITEMGROUP_RESTRICTIONS_OVERALLGROUP_REQ_EXCLUDED_DESC', 'En bruger vil ikke kunne se denne gruppe hvis han eller hun har anvendt en plan i gruppen tidligere, uanset hvorn&aring;r');

define( '_ITEMGROUP_RESTRICTIONS_USED_GROUP_MIN_ENABLED_NAME', 'Min. brugt gruppe:');
define( '_ITEMGROUP_RESTRICTIONS_USED_GROUP_MIN_ENABLED_DESC', 'Aktiver tjek for det mindste antal gange dine bruger skal have tegnet medlemskab til en betalingsplan i den valgte gruppe for at se DENNE gruppe');
define( '_ITEMGROUP_RESTRICTIONS_USED_GROUP_MIN_AMOUNT_NAME', 'Brugt antal gange:');
define( '_ITEMGROUP_RESTRICTIONS_USED_GROUP_MIN_AMOUNT_DESC', 'Det mindste antal gange en bruger skal have anvendt en plan i den valgte gruppe');
define( '_ITEMGROUP_RESTRICTIONS_USED_GROUP_MIN_NAME', 'Gruppe:');
define( '_ITEMGROUP_RESTRICTIONS_USED_GROUP_MIN_DESC', 'Gruppen som brugeren skal have anvendt en plan fra - mindst det angivne antal gange');
define( '_ITEMGROUP_RESTRICTIONS_USED_GROUP_MAX_ENABLED_NAME', 'Maks. brugt antal gange:');
define( '_ITEMGROUP_RESTRICTIONS_USED_GROUP_MAX_ENABLED_DESC', 'Aktiver tjek for det maksimale antal gange en bruger m&aring; have tegnet medlemskab til en plan i den valgte gruppe for at se DENNE gruppe');
define( '_ITEMGROUP_RESTRICTIONS_USED_GROUP_MAX_AMOUNT_NAME', 'Brugt antal gange:');
define( '_ITEMGROUP_RESTRICTIONS_USED_GROUP_MAX_AMOUNT_DESC', 'Det maksimale antal gange en bruger m&aring; have anvendt en plan i den valgte gruppe');
define( '_ITEMGROUP_RESTRICTIONS_USED_GROUP_MAX_NAME', 'Gruppe:');
define( '_ITEMGROUP_RESTRICTIONS_USED_GROUP_MAX_DESC', 'Gruppen som brugeren skal have anvendt en plan fra - maksimalt det angivne antal gange');

define( '_ITEMGROUP_RESTRICTIONS_CUSTOM_RESTRICTIONS_ENABLED_NAME', 'Brug brugerdefinerede begr&aelig;nsninger:');
define( '_ITEMGROUP_RESTRICTIONS_CUSTOM_RESTRICTIONS_ENABLED_DESC', 'Aktiver brugen af de nedenfor angivne restriktioner');
define( '_ITEMGROUP_RESTRICTIONS_CUSTOM_RESTRICTIONS_NAME', 'Brugerdefinerede begr&aelig;nsninger:');
define( '_ITEMGROUP_RESTRICTIONS_CUSTOM_RESTRICTIONS_DESC', 'Brug RewriteEngine felter for at tjekke specifikke strenge i formen:<br />[[user_id]] >= 1500<br />[[parametername]] = value<br />(Opret seperate regler ved at angive en ny linje).<br />Du kan bruge =, <=, >=, <, >, <> som sammenlignende elementer. Du SKAL bruger mellemrum mellem parametre, v&aelig;rdier og komparatorer!');

// Group Relations

define( '_ITEMGROUP_RELATIONS_TITLE', 'Relationer');
define( '_ITEMGROUP_PARAMS_SIMILARITEMGROUPS_NAME', 'Lignende grupper:');
define( '_ITEMGROUP_PARAMS_SIMILARITEMGROUPS_DESC', 'V&aelig;lg hvilke grupper der ligner denne. En bruger kan ikke anvende en pr&oslash;veperiode n&aring;r brugeren k&oslash;ber en plan som han eller hun har k&oslash;bt tidligere og det vil ogs&aring; g&aelig;lde for lignende planer (eller planer i lignende grupper).');
define( '_ITEMGROUP_PARAMS_EQUALITEMGROUPS_NAME', 'Ens grupper:');
define( '_ITEMGROUP_PARAMS_EQUALITEMGROUPS_DESC', 'V&aelig;lg hvilke grupper der er lig denne. En bruger der skifter mellem ens planer (eller planer i ens grupper) vil f&aring; hans eller hendes periode udvidet i stedet for nulstillet. Pr&oslash;verperioder er ikke tilladte (se infor for lignende planer).');

// Currencies

define( '_CURRENCY_AFA', 'Afghani');
define( '_CURRENCY_ALL', 'Lek');
define( '_CURRENCY_DZD', 'Algerian Dinar');
define( '_CURRENCY_ADP', 'Andorran Peseta');
define( '_CURRENCY_AON', 'New Kwanza');
define( '_CURRENCY_ARS', 'Argentine Peso');
define( '_CURRENCY_AMD', 'Armenian Dram');
define( '_CURRENCY_AWG', 'Aruban Guilder');
define( '_CURRENCY_AUD', 'Australian Dollar');
define( '_CURRENCY_AZM', 'Azerbaijanian Manat ');
define( '_CURRENCY_EUR', 'Euro');
define( '_CURRENCY_BSD', 'Bahamian Dollar');
define( '_CURRENCY_BHD', 'Bahraini Dinar');
define( '_CURRENCY_BDT', 'Taka');
define( '_CURRENCY_BBD', 'Barbados Dollar');
define( '_CURRENCY_BYB', 'Belarussian Ruble');
define( '_CURRENCY_BEF', 'Belgian Franc');
define( '_CURRENCY_BZD', 'Belize Dollar');
define( '_CURRENCY_BMD', 'Bermudian Dollar');
define( '_CURRENCY_BOB', 'Boliviano');
define( '_CURRENCY_BAD', 'Bosnian Dinar');
define( '_CURRENCY_BWP', 'Pula');
define( '_CURRENCY_BRL', 'Real');
define( '_CURRENCY_BND', 'Brunei Dollar');
define( '_CURRENCY_BGL', 'Lev');
define( '_CURRENCY_BGN', 'Lev');
define( '_CURRENCY_XOF', 'CFA Franc BCEAO');
define( '_CURRENCY_BIF', 'Burundi Franc');
define( '_CURRENCY_KHR', 'Cambodia Riel');
define( '_CURRENCY_XAF', 'CFA Franc BEAC');
define( '_CURRENCY_CAD', 'Canadian Dollar');
define( '_CURRENCY_CVE', 'Cape Verde Escudo');
define( '_CURRENCY_KYD', 'Cayman Islands Dollar');
define( '_CURRENCY_CLP', 'Chilean Peso');
define( '_CURRENCY_CNY', 'Yuan Renminbi');
define( '_CURRENCY_COP', 'Colombian Peso');
define( '_CURRENCY_KMF', 'Comoro Franc');
define( '_CURRENCY_BAM', 'Convertible Marks');
define( '_CURRENCY_CRC', 'Costa Rican Colon');
define( '_CURRENCY_HRK', 'Croatian Kuna');
define( '_CURRENCY_CUP', 'Cuban Peso');
define( '_CURRENCY_CYP', 'Cyprus Pound');
define( '_CURRENCY_CZK', 'Czech Koruna');
define( '_CURRENCY_DKK', 'Danske kroner');
define( '_CURRENCY_DEM', 'Deutsche Mark');
define( '_CURRENCY_DJF', 'Djibouti Franc');
define( '_CURRENCY_XCD', 'East Caribbean Dollar');
define( '_CURRENCY_DOP', 'Dominican Peso');
define( '_CURRENCY_GRD', 'Drachma');
define( '_CURRENCY_TPE', 'Timor Escudo');
define( '_CURRENCY_ECS', 'Ecuador Sucre');
define( '_CURRENCY_EGP', 'Egyptian Pound');
define( '_CURRENCY_SVC', 'El Salvador Colon');
define( '_CURRENCY_EEK', 'Kroon');
define( '_CURRENCY_ETB', 'Ethiopian Birr');
define( '_CURRENCY_FKP', 'Falkland Islands Pound');
define( '_CURRENCY_FJD', 'Fiji Dollar');
define( '_CURRENCY_XPF', 'CFP Franc');
define( '_CURRENCY_FRF', 'Franc');
define( '_CURRENCY_CDF', 'Franc Congolais');
define( '_CURRENCY_GMD', 'Dalasi');
define( '_CURRENCY_GHC', 'Cedi');
define( '_CURRENCY_GIP', 'Gibraltar Pound');
define( '_CURRENCY_GTQ', 'Quetzal');
define( '_CURRENCY_GNF', 'Guinea Franc');
define( '_CURRENCY_GWP', 'Guinea - Bissau Peso');
define( '_CURRENCY_GYD', 'Guyana Dollar');
define( '_CURRENCY_HTG', 'Gourde');
define( '_CURRENCY_XAU', 'Gold');
define( '_CURRENCY_HNL', 'Lempira');
define( '_CURRENCY_HKD', 'Hong Kong Dollar');
define( '_CURRENCY_HUF', 'Forint');
define( '_CURRENCY_ISK', 'Iceland Krona');
define( '_CURRENCY_INR', 'Indian Rupee');
define( '_CURRENCY_IDR', 'Rupiah');
define( '_CURRENCY_IRR', 'Iranian Rial');
define( '_CURRENCY_IQD', 'Iraqi Dinar');
define( '_CURRENCY_IEP', 'Irish Pound');
define( '_CURRENCY_ITL', 'Italian Lira');
define( '_CURRENCY_ILS', 'Shekel');
define( '_CURRENCY_JMD', 'Jamaican Dollar');
define( '_CURRENCY_JPY', 'Japan Yen');
define( '_CURRENCY_JOD', 'Jordanian Dinar');
define( '_CURRENCY_KZT', 'Tenge');
define( '_CURRENCY_KES', 'Kenyan Shilling');
define( '_CURRENCY_KRW', 'Won');
define( '_CURRENCY_KPW', 'North Korean Won');
define( '_CURRENCY_KWD', 'Kuwaiti Dinar');
define( '_CURRENCY_KGS', 'Som');
define( '_CURRENCY_LAK', 'Kip');
define( '_CURRENCY_GEL', 'Lari');
define( '_CURRENCY_LVL', 'Latvian Lats');
define( '_CURRENCY_LBP', 'Lebanese Pound');
define( '_CURRENCY_LSL', 'Loti');
define( '_CURRENCY_LRD', 'Liberian Dollar');
define( '_CURRENCY_LYD', 'Libyan Dinar');
define( '_CURRENCY_LTL', 'Lithuanian Litas');
define( '_CURRENCY_LUF', 'Luxembourg Franc');
define( '_CURRENCY_AOR', 'Kwanza Reajustado');
define( '_CURRENCY_MOP', 'Pataca');
define( '_CURRENCY_MKD', 'Denar');
define( '_CURRENCY_MGF', 'Malagasy Franc');
define( '_CURRENCY_MWK', 'Kwacha');
define( '_CURRENCY_MYR', 'Malaysian Ringitt');
define( '_CURRENCY_MVR', 'Rufiyaa');
define( '_CURRENCY_MTL', 'Maltese Lira');
define( '_CURRENCY_MRO', 'Ouguiya');
define( '_CURRENCY_TMM', 'Manat');
define( '_CURRENCY_FIM', 'Markka');
define( '_CURRENCY_MUR', 'Mauritius Rupee');
define( '_CURRENCY_MXN', 'Mexico Peso');
define( '_CURRENCY_MXV', 'Mexican Unidad de Inversion');
define( '_CURRENCY_MNT', 'Mongolia Tugrik');
define( '_CURRENCY_MAD', 'Moroccan Dirham');
define( '_CURRENCY_MDL', 'Moldovan Leu');
define( '_CURRENCY_MZM', 'Metical');
define( '_CURRENCY_BOV', 'Mvdol');
define( '_CURRENCY_MMK', 'Myanmar Kyat');
define( '_CURRENCY_ERN', 'Nakfa');
define( '_CURRENCY_NAD', 'Namibian Dollar');
define( '_CURRENCY_NPR', 'Nepalese Rupee');
define( '_CURRENCY_ANG', 'Netherlands Antilles Guilder');
define( '_CURRENCY_NLG', 'Netherlands Guilder');
define( '_CURRENCY_NZD', 'New Zealand Dollar');
define( '_CURRENCY_NIO', 'Cordoba Oro');
define( '_CURRENCY_NGN', 'Naira');
define( '_CURRENCY_BTN', 'Ngultrum');
define( '_CURRENCY_NOK', 'Norwegian Krone');
define( '_CURRENCY_OMR', 'Rial Omani');
define( '_CURRENCY_PKR', 'Pakistan Rupee');
define( '_CURRENCY_PAB', 'Balboa');
define( '_CURRENCY_PGK', 'New Guinea Kina');
define( '_CURRENCY_PYG', 'Guarani');
define( '_CURRENCY_PEN', 'Nuevo Sol');
define( '_CURRENCY_XPD', 'Palladium');
define( '_CURRENCY_PHP', 'Philippine Peso');
define( '_CURRENCY_XPT', 'Platinum');
define( '_CURRENCY_PTE', 'Portuguese Escudo');
define( '_CURRENCY_PLN', 'New Zloty');
define( '_CURRENCY_QAR', 'Qatari Rial');
define( '_CURRENCY_ROL', 'Romanian Leu');
define( '_CURRENCY_RON', 'New Romanian Leu');
define( '_CURRENCY_RSD', 'Serbian dinar');
define( '_CURRENCY_RUB', 'Russian Ruble');
define( '_CURRENCY_RWF', 'Rwanda Franc');
define( '_CURRENCY_WST', 'Tala');
define( '_CURRENCY_STD', 'Dobra');
define( '_CURRENCY_SAR', 'Saudi Riyal');
define( '_CURRENCY_SCR', 'Seychelles Rupee');
define( '_CURRENCY_SLL', 'Leone');
define( '_CURRENCY_SGD', 'Singapore Dollar');
define( '_CURRENCY_SKK', 'Slovak Koruna');
define( '_CURRENCY_SIT', 'Tolar');
define( '_CURRENCY_SBD', 'Solomon Islands Dollar');
define( '_CURRENCY_SOS', 'Somalia Shilling');
define( '_CURRENCY_ZAL', 'Rand (Financial)');
define( '_CURRENCY_ZAR', 'Rand (South Africa)');
define( '_CURRENCY_RUR', 'Russian Ruble');
define( '_CURRENCY_ATS', 'Schilling');
define( '_CURRENCY_XAG', 'Silver');
define( '_CURRENCY_ESP', 'Spanish Peseta');
define( '_CURRENCY_LKR', 'Sri Lanka Rupee');
define( '_CURRENCY_SHP', 'St Helena Pound');
define( '_CURRENCY_SDP', 'Sudanese Pound');
define( '_CURRENCY_SDD', 'Sudanese Dinar');
define( '_CURRENCY_SRG', 'Suriname Guilder');
define( '_CURRENCY_SZL', 'Swaziland Lilangeni');
define( '_CURRENCY_SEK', 'Sweden Krona');
define( '_CURRENCY_CHF', 'Swiss Franc');
define( '_CURRENCY_SYP', 'Syrian Pound');
define( '_CURRENCY_TWD', 'New Taiwan Dollar');
define( '_CURRENCY_TJR', 'Tajik Ruble');
define( '_CURRENCY_TZS', 'Tanzanian Shilling');
define( '_CURRENCY_TRL', 'Turkish Lira');
define( '_CURRENCY_THB', 'Baht');
define( '_CURRENCY_TOP', 'Tonga Pa\'anga');
define( '_CURRENCY_TTD', 'Trinidad &amp; Tobago Dollar');
define( '_CURRENCY_TND', 'Tunisian Dinar');
define( '_CURRENCY_TRY', 'Turkish Lira');
define( '_CURRENCY_UGX', 'Uganda Shilling');
define( '_CURRENCY_UAH', 'Ukrainian Hryvnia');
define( '_CURRENCY_ECV', 'Unidad de Valor Constante');
define( '_CURRENCY_CLF', 'Unidades de fomento');
define( '_CURRENCY_AED', 'United Arab Emirates Dirham');
define( '_CURRENCY_GBP', 'Pounds Sterling');
define( '_CURRENCY_USD', 'US Dollar');
define( '_CURRENCY_UYU', 'Uruguayan Peso');
define( '_CURRENCY_UZS', 'Uzbekistan Sum');
define( '_CURRENCY_VUV', 'Vanuatu Vatu');
define( '_CURRENCY_VEB', 'Venezuela Bolivar');
define( '_CURRENCY_VND', 'Viet Nam Dong');
define( '_CURRENCY_YER', 'Yemeni Rial');
define( '_CURRENCY_YUM', 'Yugoslavian New Dinar');
define( '_CURRENCY_ZRN', 'New Zaire');
define( '_CURRENCY_ZMK', 'Zambian Kwacha');
define( '_CURRENCY_ZWD', 'Zimbabwe Dollar');
define( '_CURRENCY_USN', 'US Dollar (Next day)');
define( '_CURRENCY_USS', 'US Dollar (Same day)');

// --== MICRO INTEGRATION OVERVIEW ==--
define( '_MI_TITLE', 'Micro integrationer');
define( '_MI_NAME', 'Navn');
define( '_MI_DESC', 'Beskrivelse');
define( '_MI_ACTIVE', 'Aktiv');
define( '_MI_REORDER', 'R&aelig;kkef&oslash;lge');
define( '_MI_FUNCTION', 'Funktionsnavn');

// --== MICRO INTEGRATION EDIT ==--
define( '_MI_E_TITLE', 'MI');
define( '_MI_E_TITLE_LONG', 'Micro integration');
define( '_MI_E_SETTINGS', 'Indstillinger');
define( '_MI_E_NAME_NAME', 'Navn');
define( '_MI_E_NAME_DESC', 'V&aelig;lg et navn for denne micro integration');
define( '_MI_E_DESC_NAME', 'Beskrivelse');
define( '_MI_E_DESC_DESC', 'Beskriv kort integrationen');
define( '_MI_E_ACTIVE_NAME', 'Aktiv');
define( '_MI_E_ACTIVE_DESC', 'S&aelig;t integration til aktiv');
define( '_MI_E_AUTO_CHECK_NAME', 'Udl&oslash;bshandling');
define( '_MI_E_AUTO_CHECK_DESC', 'Hvis funktionen tillader det, s&aring; kan du aktivere udl&oslash;bshandlinger: handlinger der skal udf&oslash;res n&aring;r et medlemskab udl&oslash;ber (hvis underst&oslash;ttet af MIen).');
define( '_MI_E_ON_USERCHANGE_NAME', 'Brugerkonto update handling');
define( '_MI_E_ON_USERCHANGE_DESC', 'Hvis funktionen tilladet det, s&aring; kan du aktivere handlinger der sker n&aring;r en brugerkonto bliver &aelig;ndret (hvis underst&oslash;ttet af MIen).');
define( '_MI_E_PRE_EXP_CHECK_NAME', 'Pre udl&oslash;b');
define( '_MI_E_PRE_EXP_CHECK_DESC', 'S&aelig;t antallet af dage f&oslash;r udl&oslash;b at en pre-udl&oslash;bs handling skal trigges (hvis underst&oslash;ttet af MIen).');
define( '_MI_E__AEC_GLOBAL_EXP_ALL_NAME', 'Udl&oslash;b alle instanser');
define( '_MI_E__AEC_GLOBAL_EXP_ALL_DESC', 'Trig udl&oslash;bshandlingen selvom brugeren har en anden betalingsplan med denne MI tilknyttet. Standard opf&oslash;rslen er at kun at kalde udl&oslash;bs handlingen p&aring; en MI hvis det virkelig er den sidste MI instans som denne bruger har i alle betalingsplaner.');
define( '_MI_E_FUNCTION_NAME', 'Funktionsnavn');
define( '_MI_E_FUNCTION_DESC', 'V&aelig;lg venligst hvilke af disse integrationer der skal anvendes');
define( '_MI_E_FUNCTION_EXPLANATION', 'For du kan ops&aelig;tte micro integrationen, skal du v&aelig;lge hvilke integrationsfiler der skal bruges til den. Foretag et valg og gem micro integrationen. N&aring;r du redigerer den igen kan de s&aelig;tte parametre. L&aelig;g ogs&aring; m&aelig;rke til at funktionsnavnet ikke kan &aelig;ndres n&aring;r det er blevet sat.');

define( '_MI_E__AEC_ACTION_NAME', 'Regul&aelig;r handling');
define( '_MI_E__AEC_ACTION_DESC', 'Udf&oslash;r standard handlingen for denne MI n&aring;r betalingsplanen som den er tilknyttet bliver anvendt.');
define( '_MI_E__AEC_ONLY_FIRST_BILL_NAME', 'Kun f&oslash;rste betaling');
define( '_MI_E__AEC_ONLY_FIRST_BILL_DESC', 'Udf&oslash;r kun handlingen ved f&oslash;rste betaling, ikke efterf&oslash;lgende betalinger p&aring; den samme faktura (gentagende betaling).');

// --== REWRITE EXPLANATION ==--
define( '_REWRITE_AREA_USER', 'Brugerkonto relateret');
define( '_REWRITE_KEY_USER_ID', 'Brugerkonto ID');
define( '_REWRITE_KEY_USER_USERNAME', 'Brugernavn');
define( '_REWRITE_KEY_USER_NAME', 'Navn');
define( '_REWRITE_KEY_USER_FIRST_NAME', 'Fornavn');
define( '_REWRITE_KEY_USER_FIRST_FIRST_NAME', 'F&oslash;rste fornavn');
define( '_REWRITE_KEY_USER_LAST_NAME', 'Efternavn');
define( '_REWRITE_KEY_USER_EMAIL', 'E-Mail adresse');
define( '_REWRITE_KEY_USER_ACTIVATIONCODE', 'Aktiveringskode');
define( '_REWRITE_KEY_USER_ACTIVATIONLINK', 'Aktiveringslink');

define( '_REWRITE_AREA_SUBSCRIPTION', 'Bruger medlemskab relateret');
define( '_REWRITE_KEY_SUBSCRIPTION_TYPE', 'Betalingsprocessor');
define( '_REWRITE_KEY_SUBSCRIPTION_STATUS', 'Medlemskabsstatus');
define( '_REWRITE_KEY_SUBSCRIPTION_SIGNUP_DATE', 'Dato for tegning af medlemskab');
define( '_REWRITE_KEY_SUBSCRIPTION_LASTPAY_DATE', 'Seneste betalingsdato');
define( '_REWRITE_KEY_SUBSCRIPTION_PLAN', 'Betalingsplan ID');
define( '_REWRITE_KEY_SUBSCRIPTION_PREVIOUS_PLAN', 'Forrige betalingsplan ID');
define( '_REWRITE_KEY_SUBSCRIPTION_RECURRING', 'Tilbagevendende betalings markering');
define( '_REWRITE_KEY_SUBSCRIPTION_LIFETIME', 'Livstidsmedlemskab markering');
define( '_REWRITE_KEY_SUBSCRIPTION_EXPIRATION_DATE', 'Udl&oslash;bsdato (Frontend formatering)');
define( '_REWRITE_KEY_SUBSCRIPTION_EXPIRATION_DATE_BACKEND', 'Udl&oslash;bsdato (Backend formatering)');

define( '_REWRITE_AREA_PLAN', 'Betalingsplan relateret');
define( '_REWRITE_KEY_PLAN_NAME', 'Navn');
define( '_REWRITE_KEY_PLAN_DESC', 'Beskrivelse');

define( '_REWRITE_AREA_CMS', 'CMS relateret');
define( '_REWRITE_KEY_CMS_ABSOLUTE_PATH', 'Absolut sti til cms mappe');
define( '_REWRITE_KEY_CMS_LIVE_SITE', 'Din websteds URL');

define( '_REWRITE_AREA_SYSTEM', 'System relateret');
define( '_REWRITE_KEY_SYSTEM_TIMESTAMP', 'Tidsstempel (Frontend formatering)');
define( '_REWRITE_KEY_SYSTEM_TIMESTAMP_BACKEND', 'Tidsstempel (Backend formatering)');
define( '_REWRITE_KEY_SYSTEM_SERVER_TIMESTAMP', 'Server tidsstempel (Frontend formatering)');
define( '_REWRITE_KEY_SYSTEM_SERVER_TIMESTAMP_BACKEND', 'Server tidsstempel (Backend formatering)');

define( '_REWRITE_AREA_INVOICE', 'Faktura relateret');
define( '_REWRITE_KEY_INVOICE_ID', 'Faktura ID');
define( '_REWRITE_KEY_INVOICE_NUMBER', 'Fakturanummer');
define( '_REWRITE_KEY_INVOICE_NUMBER_FORMAT', 'Fakturanummer (formateret)');
define( '_REWRITE_KEY_INVOICE_CREATED_DATE', 'Dato for oprettelse');
define( '_REWRITE_KEY_INVOICE_TRANSACTION_DATE', 'Dato for transaktion');
define( '_REWRITE_KEY_INVOICE_METHOD', 'Betalingsmetode');
define( '_REWRITE_KEY_INVOICE_AMOUNT', 'Betalt bel&oslash;b');
define( '_REWRITE_KEY_INVOICE_CURRENCY', 'Valuta');
define( '_REWRITE_KEY_INVOICE_COUPONS', 'List over kuponner');

define( '_REWRITE_ENGINE_TITLE', 'Rewrite Engine');
define( '_REWRITE_ENGINE_DESC', 'For at oprette dynamisk tekst, s&aring; kan du tilf&oslash;je disse wiki-style tags i RWengine-aktiverede felter. Klik gennem bj&aelig;lkerne for at se hvilke der er tilg&aelig;ngelige');
define( '_REWRITE_ENGINE_AECJSON_TITLE', 'aecJSON');
define( '_REWRITE_ENGINE_AECJSON_DESC', 'Du kan ogs&aring; anvende funktioner kodet i JSON markup, som dette:<br />{aecjson} { "cmd":"date", "vars": [ "Y", { "cmd":"rw_constant", "vars":"invoice_created_date" } ] } {/aecjson}<br />Det returnerer kun &aring;r i datoen. Referer til manualen og fora for yderligere instruktioner!');

// --== COUPONS OVERVIEW ==--
define( '_COUPON_TITLE', 'Kuponner');
define( '_COUPON_TITLE_STATIC', 'Statiske kuponner');
define( '_COUPON_NAME', 'Navn');
define( '_COUPON_DESC', 'Beskrivelse (f&oslash;rste 50 karak.)');
define( '_COUPON_CODE', 'Kupon kode');
define( '_COUPON_ACTIVE', 'Publiceret');
define( '_COUPON_REORDER', 'Gensorter');
define( '_COUPON_USECOUNT', 'Brug antal');

// --== COUPON EDIT ==--
define( '_COUPON_DETAIL_TITLE', 'Kupon');
define( '_COUPON_RESTRICTIONS_TITLE', 'Begr&aelig;ns.');
define( '_COUPON_RESTRICTIONS_TITLE_FULL', 'Begr&aelig;nsninger');
define( '_COUPON_MI', 'Micro int.');
define( '_COUPON_MI_FULL', 'Micro integrationer');

define( '_COUPON_GENERAL_NAME_NAME', 'Navn');
define( '_COUPON_GENERAL_NAME_DESC', 'Angiv (intern&amp;ekstern) navnet for denne kupon');
define( '_COUPON_GENERAL_COUPON_CODE_NAME', 'Kupon kode');
define( '_COUPON_GENERAL_COUPON_CODE_DESC', 'Angiv kupon koden for denne kupon - den tilf&aelig;ldigt genererede kupon kode bliver kontrolleret for at v&aelig;re unik i systemet');
define( '_COUPON_GENERAL_DESC_NAME', 'Beskrivelse');
define( '_COUPON_GENERAL_DESC_DESC', 'Angiv (intern) beskrivelsen for denne kupon');
define( '_COUPON_GENERAL_ACTIVE_NAME', 'Aktiv');
define( '_COUPON_GENERAL_ACTIVE_DESC', 'S&aelig;t om denne kupon er aktiv (kan bruges)');
define( '_COUPON_GENERAL_TYPE_NAME', 'Statisk');
define( '_COUPON_GENERAL_TYPE_DESC', 'V&aelig;lg om du &oslash;nsker at dette skal v&aelig;re en statisk kupon. Disse gemmes i seperate tabeller for hurtigere adgang, den generelle forskel er at statiske kuponner er kuponner der bruges af mange brugere mens at ikke-statiske er for en bruger.');

define( '_COUPON_GENERAL_MICRO_INTEGRATIONS_NAME', 'Micro integrationer');
define( '_COUPON_GENERAL_MICRO_INTEGRATIONS_DESC', 'V&aelig;lg micro integration(erne) som du &oslash;nsker at kalde hvis denne kupon anvendes');

define( '_COUPON_PARAMS_AMOUNT_USE_NAME', 'Brug bel&oslash;b');
define( '_COUPON_PARAMS_AMOUNT_USE_DESC', 'V&aelig;lg om du vil bruge et direkte rabatbel&oslash;b');
define( '_COUPON_PARAMS_AMOUNT_NAME', 'Rabatbel&oslash;b');
define( '_COUPON_PARAMS_AMOUNT_DESC', 'Angiv bel&oslash;bet som du &oslash;nsker at fratr&aelig;kke det n&aelig;ste bel&oslash;b');
define( '_COUPON_PARAMS_AMOUNT_PERCENT_USE_NAME', 'Brug procentsats');
define( '_COUPON_PARAMS_AMOUNT_PERCENT_USE_DESC', 'V&aelig;lg om du &oslash;nsker at fratr&aelig;kke en procentsats fra det aktuelle bel&oslash;b');
define( '_COUPON_PARAMS_AMOUNT_PERCENT_NAME', 'Rabatprocentsats');
define( '_COUPON_PARAMS_AMOUNT_PERCENT_DESC', 'Angiv procentsatsen som du &oslash;nsker at fratr&aelig;kke bel&oslash;bet');
define( '_COUPON_PARAMS_PERCENT_FIRST_NAME', 'Procent f&oslash;rst');
define( '_COUPON_PARAMS_PERCENT_FIRST_DESC', 'Hvis du kombinerer procentsats og bel&oslash;b, &oslash;nsker du s&aring; at procentsatsen bliver trukket f&oslash;rst?');
define( '_COUPON_PARAMS_USEON_TRIAL_NAME', 'Brug ved pr&oslash;veperioder?');
define( '_COUPON_PARAMS_USEON_TRIAL_DESC', '&Oslash;nsker du at lade brugeren anvende rabat p&aring; et pr&oslash;veperiode bel&oslash;b?');
define( '_COUPON_PARAMS_USEON_FULL_NAME', 'Brug p&aring; fuld?');
define( '_COUPON_PARAMS_USEON_FULL_DESC', '&Oslash;nsker du at lade brugeren anvende denne rabat p&aring; det aktuelle bel&oslash;b? (Ved gentagende betaling: p&aring; den f&oslash;rste regul&aelig;re betaling)');
define( '_COUPON_PARAMS_USEON_FULL_ALL_NAME', 'Hver fuld?');
define( '_COUPON_PARAMS_USEON_FULL_ALL_DESC', 'Hvis brugeren bruger gentagende betaling, &oslash;nsker du da at give denne rabat p&aring; hver efterf&oslash;lgende betaling? (Eller kun p&aring; den f&oslash;rste, hvis dette er tilf&aelig;ldet, s&aring; v&aelig;lg Nej)');

define( '_COUPON_PARAMS_HAS_START_DATE_NAME', 'Brug startdato');
define( '_COUPON_PARAMS_HAS_START_DATE_DESC', '&Oslash;nsker du at tillade dine brugere at anvende denne kupon fra en bestemt dato?');
define( '_COUPON_PARAMS_START_DATE_NAME', 'Startdato');
define( '_COUPON_PARAMS_START_DATE_DESC', 'V&aelig;lg datoen fra hvilken du &oslash;nsker at tillade brugen af denne rabatkupon');
define( '_COUPON_PARAMS_HAS_EXPIRATION_NAME', 'Brug udl&oslash;bsdato');
define( '_COUPON_PARAMS_HAS_EXPIRATION_DESC', '&Oslash;nsker du at begr&aelig;nse brugen af denne kupon til en bestemt slutdato?');
define( '_COUPON_PARAMS_EXPIRATION_NAME', 'Udl&oslash;bsdato');
define( '_COUPON_PARAMS_EXPIRATION_DESC', 'V&aelig;lg datoen indtil hvilken at denne rabatkupon kan anvendes');
define( '_COUPON_PARAMS_HAS_MAX_REUSE_NAME', 'Begr&aelig;ns genbrug?');
define( '_COUPON_PARAMS_HAS_MAX_REUSE_DESC', '&Oslash;nsker du at begr&aelig;nse antallet af gange denne kupon m&aring; anvendes?');
define( '_COUPON_PARAMS_MAX_REUSE_NAME', 'Maks. brug');
define( '_COUPON_PARAMS_MAX_REUSE_DESC', 'V&aelig;lg antallet af gange som denne kupon kan bruges');
define( '_COUPON_PARAMS_HAS_MAX_PERUSER_REUSE_NAME', 'Begr&aelig;ns genbrug per bruger?');
define( '_COUPON_PARAMS_HAS_MAX_PERUSER_REUSE_DESC', '&Oslash;nsker du at begr&aelig;nse antallet af gange som hver bruger kan anvende denne kupon?');
define( '_COUPON_PARAMS_MAX_PERUSER_REUSE_NAME', 'Maks brug per bruger');
define( '_COUPON_PARAMS_MAX_PERUSER_REUSE_DESC', 'V&aelig;lg antallet af gange som denne kupon m&aring; bruges af hver bruger');

define( '_COUPON_PARAMS_USECOUNT_NAME', 'Brug antal');
define( '_COUPON_PARAMS_USECOUNT_DESC', 'Nulstil antallet af gange som denne kupon er blevet brugt');

define( '_COUPON_PARAMS_USAGE_PLANS_ENABLED_NAME', 'S&aelig;t plan');
define( '_COUPON_PARAMS_USAGE_PLANS_ENABLED_DESC', '&Oslash;nsker du kun at tillade denne kupon for visse planer?');
define( '_COUPON_PARAMS_USAGE_PLANS_NAME', 'Planer');
define( '_COUPON_PARAMS_USAGE_PLANS_DESC', 'V&aelig;lg hvilke planer denne kupon kan bruges til');
define( '_COUPON_PARAMS_USAGE_CART_FULL_NAME', 'Brug p&aring; indk&oslash;bskurv');
define( '_COUPON_PARAMS_USAGE_CART_FULL_DESC', 'Tillad anvendelse p&aring; en fuld indk&oslash;bskurv');
define( '_COUPON_PARAMS_CART_MULTIPLE_ITEMS_NAME', 'Multiple elementer');
define( '_COUPON_PARAMS_CART_MULTIPLE_ITEMS_DESC', 'Lad brugeren anvende kuponnen p&aring; multiple elementer i  en indk&oslash;bskurv, hvis overordnede begr&aelig;nsninger tillader det');
define( '_COUPON_PARAMS_CART_MULTIPLE_ITEMS_AMOUNT_NAME', 'Multiple elementer bel&oslash;b');
define( '_COUPON_PARAMS_CART_MULTIPLE_ITEMS_AMOUNT_DESC', 'S&aelig;t en gr&aelig;nse for anvendelse p&aring; multiple elementer i en indk&oslash;bskurv');

define( '_COUPON_RESTRICTIONS_MINGID_ENABLED_NAME', 'Aktiver min. GID:');
define( '_COUPON_RESTRICTIONS_MINGID_ENABLED_DESC', 'Aktiver denne indstilling hvis du &oslash;nsker at begr&aelig;nse om en bruger kan anvende denne kupon baseret p&aring; en mindste brugergruppe.');
define( '_COUPON_RESTRICTIONS_MINGID_NAME', 'Synlighedsgruppe:');
define( '_COUPON_RESTRICTIONS_MINGID_DESC', 'Det mindst kr&aelig;vede brugerniveau for at anvende denne kupon.');
define( '_COUPON_RESTRICTIONS_FIXGID_ENABLED_NAME', 'Aktiver fast GID:');
define( '_COUPON_RESTRICTIONS_FIXGID_ENABLED_DESC', 'Aktiver denne indstilling hvis du &oslash;nsker at begr&aelig;nse denne kupon til en brugergruppe.');
define( '_COUPON_RESTRICTIONS_FIXGID_NAME', 'S&aelig;t gruppe:');
define( '_COUPON_RESTRICTIONS_FIXGID_DESC', 'Kun brugere med denne brugergruppe kan bruge denne kupon.');
define( '_COUPON_RESTRICTIONS_MAXGID_ENABLED_NAME', 'Aktiver maks. GID:');
define( '_COUPON_RESTRICTIONS_MAXGID_ENABLED_DESC', 'Aktiver denne indstilling hvis du &oslash;nsker at begr&aelig;nse om en bruger kan bruge denne kupon baseret p&aring; en maksimal brugergruppe.');
define( '_COUPON_RESTRICTIONS_MAXGID_NAME', 'Maksimum gruppe:');
define( '_COUPON_RESTRICTIONS_MAXGID_DESC', 'Det maksimale brugerniveau som en bruger m&aring; have for at bruge denne kupon.');

define( '_COUPON_RESTRICTIONS_PREVIOUSPLAN_REQ_ENABLED_NAME', 'Kr&aelig;vet tidligere plan:');
define( '_COUPON_RESTRICTIONS_PREVIOUSPLAN_REQ_ENABLED_DESC', 'Aktiver tjek for tidligere betalingsplan');
define( '_COUPON_RESTRICTIONS_PREVIOUSPLAN_REQ_NAME', 'Plan:');
define( '_COUPON_RESTRICTIONS_PREVIOUSPLAN_REQ_DESC', 'En bruger kan kun bruge denne kupon hvis han eller hun har anvendt den valgte plan f&oslash;r den aktuelle plan');
define( '_COUPON_RESTRICTIONS_CURRENTPLAN_REQ_ENABLED_NAME', 'Kr&aelig;vet aktuel plan:');
define( '_COUPON_RESTRICTIONS_CURRENTPLAN_REQ_ENABLED_DESC', 'Aktiver tjek for aktuel betalingsplan');
define( '_COUPON_RESTRICTIONS_CURRENTPLAN_REQ_NAME', 'Plan:');
define( '_COUPON_RESTRICTIONS_CURRENTPLAN_REQ_DESC', 'En bruger kan kun bruge denne kupon hvis han eller hun aktuelt er tilknyttet til, eller et medlemskab just er udl&oslash;bet for den valgte plan');
define( '_COUPON_RESTRICTIONS_OVERALLPLAN_REQ_ENABLED_NAME', 'Kr&aelig;vet brugt plan:');
define( '_COUPON_RESTRICTIONS_OVERALLPLAN_REQ_ENABLED_DESC', 'Aktiver tjek for overordnet anvendt betalingsplan');
define( '_COUPON_RESTRICTIONS_OVERALLPLAN_REQ_NAME', 'Plan:');
define( '_COUPON_RESTRICTIONS_OVERALLPLAN_REQ_DESC', 'En bruger kan kun bruger denne kupon hvis han eller hun har brugt den valgte plan tidligere, uanset hvorn&aring;r');

define( '_COUPON_RESTRICTIONS_USED_PLAN_MIN_ENABLED_NAME', 'Min. brugt plan:');
define( '_COUPON_RESTRICTIONS_USED_PLAN_MIN_ENABLED_DESC', 'Aktiver tjek for det mindste antal gange en bruger skal have anvendt en angivet betalingsplan for at kunne bruge denne kupon');
define( '_COUPON_RESTRICTIONS_USED_PLAN_MIN_AMOUNT_NAME', 'Brugt antal:');
define( '_COUPON_RESTRICTIONS_USED_PLAN_MIN_AMOUNT_DESC', 'Det mindste antal gange en bruger skal have brugt den valgte plan');
define( '_COUPON_RESTRICTIONS_USED_PLAN_MIN_NAME', 'Plan:');
define( '_COUPON_RESTRICTIONS_USED_PLAN_MIN_DESC', 'Betalingsplanen som brugeren mindst skal have brugt det angivne antal gange');
define( '_COUPON_RESTRICTIONS_USED_PLAN_MAX_ENABLED_NAME', 'Maks. brugt plan:');
define( '_COUPON_RESTRICTIONS_USED_PLAN_MAX_ENABLED_DESC', 'Aktiver tjek for det maksimale antal gange en bruger m&aring; have brugt en valgt plan for at kunne bruge denne kupon');
define( '_COUPON_RESTRICTIONS_USED_PLAN_MAX_AMOUNT_NAME', 'Brugt antal:');
define( '_COUPON_RESTRICTIONS_USED_PLAN_MAX_AMOUNT_DESC', 'Det maksimale antal gange en bruger kan have brugt den valgte plan');
define( '_COUPON_RESTRICTIONS_USED_PLAN_MAX_NAME', 'Plan:');
define( '_COUPON_RESTRICTIONS_USED_PLAN_MAX_DESC', 'Betalingsplanen som brugeren m&aring; have brugt det maksimale angivne antal gange');

define( '_COUPON_RESTRICTIONS_PREVIOUSPLAN_REQ_ENABLED_EXCLUDED_NAME', 'Ekskluderet tidliger plan:');
define( '_COUPON_RESTRICTIONS_PREVIOUSPLAN_REQ_ENABLED_EXCLUDED_DESC', 'Tillad IKKE at bruge denne kupon for brugere som havde den valgte plan som deres tidligere betalingsplan');
define( '_COUPON_RESTRICTIONS_PREVIOUSPLAN_REQ_EXCLUDED_NAME', 'Plan:');
define( '_COUPON_RESTRICTIONS_PREVIOUSPLAN_REQ_EXCLUDED_DESC', 'En bruger vil kun kunne bruge kuponnen hvis han eller hun anvendte den valgte plan f&oslash;r den aktuelt aktive');
define( '_COUPON_RESTRICTIONS_CURRENTPLAN_REQ_ENABLED_EXCLUDED_NAME', 'Ekskluderet aktuel plan:');
define( '_COUPON_RESTRICTIONS_CURRENTPLAN_REQ_ENABLED_EXCLUDED_DESC', 'Tillad IKKE brugen af denne kupon for brugere som har den valgte plan som deres aktuelle plan');
define( '_COUPON_RESTRICTIONS_CURRENTPLAN_REQ_EXCLUDED_NAME', 'Plan:');
define( '_COUPON_RESTRICTIONS_CURRENTPLAN_REQ_EXCLUDED_DESC', 'En bruger vil kun kunne anvende denne kupon hvis han eller hun aktuelt er tilknyttet til, eller medlemskabet just er udl&oslash;bet for den valgte plan');
define( '_COUPON_RESTRICTIONS_OVERALLPLAN_REQ_ENABLED_EXCLUDED_NAME', 'Ekskluderet brugt plan:');
define( '_COUPON_RESTRICTIONS_OVERALLPLAN_REQ_ENABLED_EXCLUDED_DESC', 'Tillad IKKE brugen af denne kupon for brugere som har brugt den valgte plan f&oslash;r');
define( '_COUPON_RESTRICTIONS_OVERALLPLAN_REQ_EXCLUDED_NAME', 'Plan:');
define( '_COUPON_RESTRICTIONS_OVERALLPLAN_REQ_EXCLUDED_DESC', 'En bruger vil kun kunne bruge denne kupon hvis han eller hun har anvendt den valgte plan tidligere, uanset hvorn&aring;r');

define( '_COUPON_RESTRICTIONS_PREVIOUSGROUP_REQ_ENABLED_NAME', 'Kr&aelig;vet tidligere gruppe:');
define( '_COUPON_RESTRICTIONS_PREVIOUSGROUP_REQ_ENABLED_DESC', 'Aktiver tjek for tidligere batelingsplan i denne gruppe');
define( '_COUPON_RESTRICTIONS_PREVIOUSGROUP_REQ_NAME', 'Gruppe:');
define( '_COUPON_RESTRICTIONS_PREVIOUSGROUP_REQ_DESC', 'En bruger vil kun kunne bruge denn kupon hvis han eller hun anvendte en plan i denne gruppe f&oslash;r den aktuelle plan');
define( '_COUPON_RESTRICTIONS_CURRENTGROUP_REQ_ENABLED_NAME', 'Kr&aelig;vet aktuel gruppe:');
define( '_COUPON_RESTRICTIONS_CURRENTGROUP_REQ_ENABLED_DESC', 'Aktiver tjek for aktuel betalingsplan i denne gruppe');
define( '_COUPON_RESTRICTIONS_CURRENTGROUP_REQ_NAME', 'Gruppe:');
define( '_COUPON_RESTRICTIONS_CURRENTGROUP_REQ_DESC', 'En bruger vil kun kunne bruge denne kupon hvis han eller hun aktuelt er tilknyttet til, eller medlemskabet just er udl&oslash;bet for en plan i den valgte gruppe');
define( '_COUPON_RESTRICTIONS_OVERALLGROUP_REQ_ENABLED_NAME', 'Kr&aelig;vet brugt gruppe:');
define( '_COUPON_RESTRICTIONS_OVERALLGROUP_REQ_ENABLED_DESC', 'Aktiver tjek for anvendelse af overordnet betalingsplan i denne gruppe');
define( '_COUPON_RESTRICTIONS_OVERALLGROUP_REQ_NAME', 'Gruppe:');
define( '_COUPON_RESTRICTIONS_OVERALLGROUP_REQ_DESC', 'En bruger vil kun kunne bruge denne kupon hvis han eller hun har anvendt den valgte plan i denne gruppe tidligere, uanset hvorn&aring;r');

define( '_COUPON_RESTRICTIONS_PREVIOUSGROUP_REQ_ENABLED_EXCLUDED_NAME', 'Ekskluderet tidliger gruppe:');
define( '_COUPON_RESTRICTIONS_PREVIOUSGROUP_REQ_ENABLED_EXCLUDED_DESC', 'Tillad IKKE brug af denne kupon for brugere som har haft en plan i denne gruppe som deres tidligere betalingsplan');
define( '_COUPON_RESTRICTIONS_PREVIOUSGROUP_REQ_EXCLUDED_NAME', 'Gruppe:');
define( '_COUPON_RESTRICTIONS_PREVIOUSGROUP_REQ_EXCLUDED_DESC', 'En bruger vil ikke kunne anvende denne kupon hvis han eller hun har anvendt en plan i denne gruppe f&oslash;r den aktuelle plan');
define( '_COUPON_RESTRICTIONS_CURRENTGROUP_REQ_ENABLED_EXCLUDED_NAME', 'Ekskluderet aktuel gruppe:');
define( '_COUPON_RESTRICTIONS_CURRENTGROUP_REQ_ENABLED_EXCLUDED_DESC', 'Tillad IKKE brugen af denne kupon for brugere der har en plan i denne gruppe som deres aktuelle betalingsplan');
define( '_COUPON_RESTRICTIONS_CURRENTGROUP_REQ_EXCLUDED_NAME', 'Gruppe:');
define( '_COUPON_RESTRICTIONS_CURRENTGROUP_REQ_EXCLUDED_DESC', 'En bruger vil ikke kunne anvende denne kupon hvis han eller hun aktuelt er tilknyttet til, eller medlemskabet just er udl&oslash;bet for en plan i denne gruppe');
define( '_COUPON_RESTRICTIONS_OVERALLGROUP_REQ_ENABLED_EXCLUDED_NAME', 'Ekskludere brugt gruppe:');
define( '_COUPON_RESTRICTIONS_OVERALLGROUP_REQ_ENABLED_EXCLUDED_DESC', 'Tillad IKKE brugen af denne kupon for brugere som har anvendt en plan i denne gruppe tidligere');
define( '_COUPON_RESTRICTIONS_OVERALLGROUP_REQ_EXCLUDED_NAME', 'Gruppe:');
define( '_COUPON_RESTRICTIONS_OVERALLGROUP_REQ_EXCLUDED_DESC', 'En bruger kan ikke anvende denne kupon hvis han eller hun har bruget en plan i denne gruppe tidligere, uanset hvorn&aring;r');

define( '_COUPON_RESTRICTIONS_USED_GROUP_MIN_ENABLED_NAME', 'Min. brugt gruppe:');
define( '_COUPON_RESTRICTIONS_USED_GROUP_MIN_ENABLED_DESC', 'Aktiver tjek for det mindste antal gange dine brugere m&aring; have anvendt en betalingsplan i denne gruppe for at kunne bruge denne kupon');
define( '_COUPON_RESTRICTIONS_USED_GROUP_MIN_AMOUNT_NAME', 'Brugt antal gange:');
define( '_COUPON_RESTRICTIONS_USED_GROUP_MIN_AMOUNT_DESC', 'Det mindste antal gange en bruger m&aring; have anvendt en plan i denne gruppe');
define( '_COUPON_RESTRICTIONS_USED_GROUP_MIN_NAME', 'Gruppe:');
define( '_COUPON_RESTRICTIONS_USED_GROUP_MIN_DESC', 'Gruppen som brugeren skal have brugt en plan fra - det angivne mindste antal gange');
define( '_COUPON_RESTRICTIONS_USED_GROUP_MAX_ENABLED_NAME', 'Maks brugt gange:');
define( '_COUPON_RESTRICTIONS_USED_GROUP_MAX_ENABLED_DESC', 'Aktiver tjek for det maksimale antal gange en bruger m&aring; have brugt en betalingsplan i denne gruppe for at kunne bruge denne kupon');
define( '_COUPON_RESTRICTIONS_USED_GROUP_MAX_AMOUNT_NAME', 'Brugt antal gange:');
define( '_COUPON_RESTRICTIONS_USED_GROUP_MAX_AMOUNT_DESC', 'Det maksimale antal gange en bruger m&aring; have brugt en plan fra denne gruppe');
define( '_COUPON_RESTRICTIONS_USED_GROUP_MAX_NAME', 'Gruppe:');
define( '_COUPON_RESTRICTIONS_USED_GROUP_MAX_DESC', 'Gruppen som brugeren m&aring; have brugt en plan fra - det maksimalt angivne antal gange');

define( '_COUPON_RESTRICTIONS_RESTRICT_COMBINATION_NAME', 'Begr&aelig;ns kombination:');
define( '_COUPON_RESTRICTIONS_RESTRICT_COMBINATION_DESC', 'V&aelig;lg ikke at lade dine bruger kombinere denne kupon med en af de f&oslash;lgende');
define( '_COUPON_RESTRICTIONS_BAD_COMBINATIONS_NAME', 'Kuponner:');
define( '_COUPON_RESTRICTIONS_BAD_COMBINATIONS_DESC', 'Foretag et valg af hvilke andre kuponner denne ikke m&aring; anvendes sammen med');
define( '_COUPON_RESTRICTIONS_DEPEND_ON_SUBSCR_ID_NAME', 'Afh&aelig;ng af medlemskab:');
define( '_COUPON_RESTRICTIONS_DEPEND_ON_SUBSCR_ID_DESC', 'Lad kuponnen afh&aelig;nge af et s&aelig;rligt medlemskab for at v&aelig;re funktionsdygtig.');
define( '_COUPON_RESTRICTIONS_SUBSCR_ID_DEPENDENCY_NAME', 'Medlemskabs ID');
define( '_COUPON_RESTRICTIONS_SUBSCR_ID_DEPENDENCY_DESC', 'Medlemskabs IDet som en kupon vil afh&aelig;nge af.');
define( '_COUPON_RESTRICTIONS_ALLOW_TRIAL_DEPEND_SUBSCR_NAME', 'Tillad pr&oslash;verperioder:');
define( '_COUPON_RESTRICTIONS_ALLOW_TRIAL_DEPEND_SUBSCR_DESC', 'Tillad brugen af kuponnen n&aring;r den afh&aelig;nger af et medlemskab som stadig er i pr&oslash;veperioden.');
define( '_COUPON_RESTRICTIONS_RESTRICT_COMBINATION_CART_NAME', 'Begr&aelig;ns kombination indk&oslash;bskurv:');
define( '_COUPON_RESTRICTIONS_RESTRICT_COMBINATION_CART_DESC', 'V&aelig;lg ikke at lade dine brugere kobinere denne kupon med en af de f&oslash;lgende n&aring;r den anvendes p&aring; indk&oslash;bskurv');
define( '_COUPON_RESTRICTIONS_BAD_COMBINATIONS_CART_NAME', 'Kuponner:');
define( '_COUPON_RESTRICTIONS_BAD_COMBINATIONS_CART_DESC', 'Foretag et valg af hvilke kuponner denne ikke m&aring; bruges sammen med');
define( '_COUPON_RESTRICTIONS_ALLOW_COMBINATION_NAME', 'Tillad kombination:');
define( '_COUPON_RESTRICTIONS_ALLOW_COMBINATION_DESC', 'V&aelig;lg kun at lade dine brugere kombinere denne kupon med de f&oslash;lgende. V&aelig;lg ingen for at n&aelig;gte nogen kombination.');
define( '_COUPON_RESTRICTIONS_GOOD_COMBINATIONS_NAME', 'Kuponner:');
define( '_COUPON_RESTRICTIONS_GOOD_COMBINATIONS_DESC', 'Foretag et valg af kuponner som denne kupon kan anvendes sammen med');
define( '_COUPON_RESTRICTIONS_ALLOW_COMBINATION_CART_NAME', 'Tillad kombination indk&oslash;bskurv:');
define( '_COUPON_RESTRICTIONS_ALLOW_COMBINATION_CART_DESC', 'V&aelig;lg kun at lade dine brugere kombinere denne kupon med det f&oslash;lgende i en indk&oslash;bskurv. V&aelig;lg ingen for at n&aelig;gte alle kombinationer.');
define( '_COUPON_RESTRICTIONS_GOOD_COMBINATIONS_CART_NAME', 'Kuponner:');
define( '_COUPON_RESTRICTIONS_GOOD_COMBINATIONS_CART_DESC', 'Foretag et valg af kuponner som denne kan anvendes sammen med p&aring; en indk&oslash;bskurv');

// --== INVOICE OVERVIEW ==--
define( '_INVOICE_TITLE', 'Fakturaer');
define( '_INVOICE_SEARCH', 'S&oslash;g');
define( '_INVOICE_USERID', 'Brugernavn');
define( '_INVOICE_INVOICE_NUMBER', 'Fakturanummer');
define( '_INVOICE_SECONDARY_IDENT', 'Sekund&aelig;r identifikation');
define( '_INVOICE_TRANSACTION_DATE', 'Transaktionsdato');
define( '_INVOICE_CREATED_DATE', 'Oprettelsesdato');
define( '_INVOICE_METHOD', 'Metode');
define( '_INVOICE_AMOUNT', 'Bel&oslash;b');
define( '_INVOICE_CURRENCY', 'Valuta');
define( '_INVOICE_COUPONS', 'Kuponner');

// --== PAYMENT HISTORY OVERVIEW ==--
define( '_HISTORY_TITLE2', 'Din aktuelle transaktionshistorik');
define( '_HISTORY_SEARCH', 'S&oslash;g');
define( '_HISTORY_USERID', 'Brugernavn');
define( '_HISTORY_INVOICE_NUMBER', 'Fakturanummer');
define( '_HISTORY_PLAN_NAME', 'Plan tilknyttet til');
define( '_HISTORY_TRANSACTION_DATE', 'Transaktionsdato');
define( '_HISTORY_METHOD', 'Fakturametode');
define( '_HISTORY_AMOUNT', 'Fakturabel&oslash;b');
define( '_HISTORY_RESPONSE', 'Server respons');

// --== ALL USER RELATED PAGES ==--
define( '_METHOD', 'Method');

// --== PENDING PAGE ==--
define( '_PEND_DATE', 'Afventer siden');
define( '_PEND_TITLE', 'Afvendtende medlemskaber');
define( '_PEND_DESC', 'Medlemskaber som ikke fuldf&oslash;rer processen. Denne tilstand er almindelig for en kort periode mens systemet afventer betalingen.');
define( '_ACTIVATE', 'Aktiver');
define( '_ACTIVATED', 'Bruger aktiveret.');

// --== IMPORT ==--
define( '_AEC_HEAD_IMPORT', 'Import');
define( '_IMPORT_LOAD', 'Indl&aelig;s');
define( '_IMPORT_APPLY', 'Anvend');
define( '_IMPORT_GENERAL_FILE_SELECT_NAME', 'V&aelig;lg fil');
define( '_IMPORT_GENERAL_FILE_SELECT_DESC', 'V&aelig;lg en eksisterende fil fra mappen /tmp.');

// --== EXPORT ==--
define( '_AEC_HEAD_EXPORT', 'Eksport');
define( '_EXPORT_LOAD', 'Indl&aelig;se');
define( '_EXPORT_APPLY', 'Anvend');
define( '_EXPORT_GENERAL_SELECTED_EXPORT_NAME', 'Eksport preset');
define( '_EXPORT_GENERAL_SELECTED_EXPORT_DESC', 'V&aelig;lg et preset (eller en automatisk tidligere gemt eksport) istedet for at foretage valgene nedenunder. Du kan ogs&aring; klikke p&aring; anvend i &oslash;verste h&oslash;jre og gennemse presettet.');
define( '_EXPORT_GENERAL_DELETE_NAME', 'Slet');
define( '_EXPORT_GENERAL_DELETE_DESC', 'Slette denne preset (ved anvend)');
define( '_EXPORT_PARAMS_PLANID_NAME', 'Betalingsplan');
define( '_EXPORT_PARAMS_PLANID_DESC', 'Filtrer medlemskaber med denne betalingsplan ud');
define( '_EXPORT_PARAMS_STATUS_NAME', 'Status');
define( '_EXPORT_PARAMS_STATUS_DESC', 'Eksporter kun medlemskaber med denne status');
define( '_EXPORT_PARAMS_ORDERBY_NAME', 'Sorter efter');
define( '_EXPORT_PARAMS_ORDERBY_DESC', 'Sorter efter en af f&oslash;lgende');
define( '_EXPORT_PARAMS_REWRITE_RULE_NAME', 'Felter');
define( '_EXPORT_PARAMS_REWRITE_RULE_DESC', 'Inds&aelig;t ReWrite Engine felterne, separeret med semikoloner, som du &oslash;nsker at hvert eksporteret element skal indeholde.');
define( '_EXPORT_PARAMS_SAVE_NAME', 'Gem som ny?');
define( '_EXPORT_PARAMS_SAVE_DESC', 'Marker denne boks hvis du &oslash;nsker at gemme dine indstillinger som en ny preset');
define( '_EXPORT_PARAMS_SAVE_NAME_NAME', 'Lagringsnavn');
define( '_EXPORT_PARAMS_SAVE_NAME_DESC', 'Gem preset med dette navn');
define( '_EXPORT_PARAMS_EXPORT_METHOD_NAME', 'Eksportmetode');
define( '_EXPORT_PARAMS_EXPORT_METHOD_DESC', 'Filtypen som du &oslash;nsker at eksportere til');

// --== READOUT ==--
define( '_AEC_READOUT', 'AEC udl&aelig;sning');
define( '_READOUT_GENERAL_SHOW_SETTINGS_NAME', 'Indstillinger');
define( '_READOUT_GENERAL_SHOW_SETTINGS_DESC', 'Vis AEC systemindstillinger p&aring; udl&aelig;sningen');
define( '_READOUT_GENERAL_SHOW_EXTSETTINGS_NAME', 'Udvidede indstillinger');
define( '_READOUT_GENERAL_SHOW_EXTSETTINGS_DESC', 'Vis udvidede AEC systemindstillinger p&aring; udl&aelig;sningen');
define( '_READOUT_GENERAL_SHOW_PROCESSORS_NAME', 'Processorindstillinger');
define( '_READOUT_GENERAL_SHOW_PROCESSORS_DESC', 'Vis processorindstillinger p&aring; udl&aelig;sningen');
define( '_READOUT_GENERAL_SHOW_PLANS_NAME', 'Planer');
define( '_READOUT_GENERAL_SHOW_PLANS_DESC', 'Vis planer p&aring; udl&aelig;sningen');
define( '_READOUT_GENERAL_SHOW_MI_RELATIONS_NAME', 'Plan -> MI relationer');
define( '_READOUT_GENERAL_SHOW_MI_RELATIONS_DESC', 'Vis plan -> MI relationer p&aring; udl&aelig;sningen');
define( '_READOUT_GENERAL_SHOW_MIS_NAME', 'Micro integrationer');
define( '_READOUT_GENERAL_SHOW_MIS_DESC', 'Vis micro integrationer og deres indstillinger p&aring; udl&aelig;sningen');
define( '_READOUT_GENERAL_STORE_SETTINGS_NAME', 'Husk indstillinger');
define( '_READOUT_GENERAL_STORE_SETTINGS_DESC', 'Husk indstillinger p&aring; denne side for din admin konto');
define( '_READOUT_GENERAL_TRUNCATION_LENGTH_NAME', 'Tekstafkortelse');
define( '_READOUT_GENERAL_TRUNCATION_LENGTH_DESC', 'Reducer indhold af felterne til denne l&aelig;ngde hvor det er passende');
define( '_READOUT_GENERAL_USE_ORDERING_NAME', 'Brug sortering');
define( '_READOUT_GENERAL_USE_ORDERING_DESC', 'Istedet for at vise indf&oslash;rsler efter deres database r&aelig;kkef&oslash;lge, s&aring; vis dem efter deres satte sortering - hvis mulig');
define( '_READOUT_GENERAL_COLUMN_HEADERS_NAME', 'Kolonneoverskrifter');
define( '_READOUT_GENERAL_COLUMN_HEADERS_DESC', 'Vis kolonneoverskrifter for hver X r&aelig;kke');
define( '_READOUT_GENERAL_NOFORMAT_NEWLINES_NAME', 'Format: ingen linjeskift');
define( '_READOUT_GENERAL_NOFORMAT_NEWLINES_DESC', 'Multiple indf&oslash;rsler for en tabel celle bliver normalt vist i seperate linje, med denne indstilling bliver disse indf&oslash;rsler kun vist i en enkelt tekstblok.');
define( '_READOUT_GENERAL_EXPORT_CSV_NAME', 'Eksporter som .csv');
define( '_READOUT_GENERAL_EXPORT_CSV_DESC', 'Eksporter data som en kommasepareret fil som kan indl&aelig;ses i en regnearksapplikation.');

// new for errors
define( '_AEC_ERR_NO_SUBSCRIPTION', 'Brugeren har ingen medlemskaber');
?>
