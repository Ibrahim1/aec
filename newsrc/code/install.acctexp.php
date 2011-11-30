<?php
/**
 * @version $Id: install.acctexp.php
 * @package AEC - Account Control Expiration - Membership Manager
 * @subpackage Installation
 * @copyright 2006-2011 Copyright (C) David Deutsch
 * @author David Deutsch <skore@valanx.org> & Team AEC - http://www.valanx.org
 * @license GNU/GPL v.2 http://www.gnu.org/licenses/old-licenses/gpl-2.0.html or, at your option, any later version
 */

// Dont allow direct linking
( defined('_JEXEC') || defined( '_VALID_MOS' ) ) or die( 'Direct Access to this location is not allowed.' );
//error_reporting(E_ALL);

// Trying to buy us some time
@set_time_limit( 240 );

if ( !defined( '_JEXEC' ) && !defined( 'JPATH_SITE' ) ) {
	global $mosConfig_absolute_path;

	define( 'JPATH_SITE', $mosConfig_absolute_path );
} elseif ( defined( '_JEXEC' ) ) {
	JLoader::register('JTableUser', JPATH_LIBRARIES.DS.'joomla'.DS.'database'.DS.'table'.DS.'user.php');
}

// Make sure we are compatible with php4
if (version_compare(phpversion(), '5.0') < 0) {
	include_once( JPATH_SITE . '/components/com_acctexp/lib/php4/php4.php' );
}

if ( !function_exists( 'com_install' ) ) {

// Joomla 1.7 - throwing errors like there's no tomorrow
ini_set('display_errors', 'off');

function com_install()
{
	$db = &JFactory::getDBO();

	$user = &JFactory::getUser();

	$app = JFactory::getApplication();

	$document=& JFactory::getDocument();
	$document->addCustomTag( '<link rel="stylesheet" type="text/css" media="all" href="' . JURI::root() . '/media/com_acctexp/css/admin.css" />' );

	$is_j16 = defined( 'JPATH_MANIFESTS' );

	// Tracking arrays
	$queri		= array();
	$errors		= array();

	// Overall Variables
	$newinstall = false;
	$tables		= $db->getTableList();

	$langlist = array(	'com_acctexp.admin' => JPATH_ADMINISTRATOR,
					'com_acctexp' => JPATH_SITE,
					'com_acctexp.microintegrations' => JPATH_SITE,
					'com_acctexp.processors' => JPATH_SITE
					);

	$lang =& JFactory::getLanguage();

	foreach ( $langlist as $name => $path ) {
		$lang->load( $name, $path, 'en-GB', true );
		$lang->load( $name, $path, $lang->getDefault(), true );
		$lang->load( $name, $path, null, true );
	}

	if ( !defined( 'JPATH_MANIFESTS' ) ) {
		foreach ( $lang->_strings as $k => $v ) {
			$lang->_strings[$k]= str_replace( '"_QQ_"', '"', $v );
		}
	}

	// Make sure we are compatible with php4
	include_once( JPATH_SITE . '/components/com_acctexp/lib/php4/php4.php' );

	require_once( JPATH_SITE . '/components/com_acctexp/lib/eucalib/eucalib.php' );
	require_once( JPATH_SITE . '/components/com_acctexp/lib/eucalib/eucalib.install.php' );

	// Load root install and database object
	$eucaInstall	= new eucaInstall();
	$eucaInstalldb	= new eucaInstallDB();
	$eucaInstallef	= new eucaInstalleditfile();

	// Slot in DB tables that do not exist yet
	$incpath = JPATH_SITE . '/administrator/components/com_acctexp/install/inc';

	require_once( $incpath . '/dbtables.inc.php' );

	$eucaInstalldb->multiQueryExec( $queri );

	// Upgrade ancient settings
	include_once( $incpath . '/settings_oldupgrade.inc.php' );

	// Upgrade Settings to 0.12.6 status
	include_once( $incpath . '/settings_0_12_6_upgrade.inc.php' );

	// Load Class (and thus aecConfig)
	require_once( JApplicationHelper::getPath( 'class', 'com_acctexp' ) );

	global $aecConfig;

	if ( isset( $aecConfig->cfg['aec_version'] ) ) {
		$oldversion = $aecConfig->cfg['aec_version'];
	} else {
		$oldversion = false;
	}

	if ( !$newinstall ) {
		// Check if we are upgrading from before 0.12.6RC2j - then we need to check everything before that
		if ( empty( $oldversion ) || ( version_compare( $oldversion, '0.12.6RC2j' ) === 0 ) ) {
			if ( version_compare( $oldversion, '0.12.6RC2j' ) === 0 ) {
				$oldupdates = array( '0_12_6RC2j' );
			} else {
				$oldupdates = array( '0_6_0', '0_8_0', '0_10_0', '0_12_0', '0_12_6RC2j' );
			}

			foreach ( $oldupdates as $upd ) {
				require_once( $incpath . '/upgrade_' . $upd . '.inc.php' );
			}
		}

		$incfiles = AECToolbox::getFileArray( $incpath, 'inc.php', false, true );

		$versions = array();
		foreach ( $incfiles as $filename ) {
			if ( strpos( $filename, 'upgrade_' ) === false ) {
				continue;
			} else {
				$versions[] = str_replace( array( 'upgrade_', '.inc.php' ), array( '', '' ), $filename );
			}
		}

		$incf = AECToolbox::versionSort( $versions );

		$versions = array();
		foreach ( $incf as $version ) {
			if ( version_compare( AECToolbox::normVersionName( $version ), AECToolbox::normVersionName( $oldversion ), '>=' ) ) {
				require_once( $incpath . '/upgrade_' . $version . '.inc.php' );
			}
		}

		$updates = array();


	}

	// Set Version
	//$aecConfig->cfg['aec_version'] = _AEC_VERSION;

	// --- [ END OF DATABASE UPGRADE ACTIONS ] ---

	// Make sure settings & info = updated
	$pplist = PaymentProcessorHandler::getInstalledNameList();

	foreach ( $pplist as $ppname ) {
		$pp = new PaymentProcessor();

		if ( $pp->loadName( $ppname ) ) {
			$pp->fullInit();

			// Infos often change, so we protect the name and description and so on, but replace everything else
			$original	= $pp->processor->info();

			$protect = array( 'name', 'longname', 'statement', 'description' );

			foreach ( $original as $name => $var ) {
				if ( !in_array( $name, $protect ) ) {
					$pp->info[$name] = $var;
				}
			}

			$pp->processor->storeload();
		}

		$pp = null;
	}

	// Force Init Params
	$aecConfig = new Config_General( $db );
	$aecConfig->initParams();

	// --- [ END OF STANDARD UPGRADE ACTIONS ] ---

	// Create the Joomla Backend Menu
	//require_once( $incpath . '/menusetup.inc.php' );

	// Create root group
	require_once( $incpath . '/create_rootgroup.inc.php' );

	// Make all Superadmins excluded by default
	$administrators = aecACLhandler::getSuperAdmins();

	if ( !empty( $administrators ) ) {
		foreach ( $administrators as $admin ) {
			$metaUser = new metaUser( $admin->id );

			if ( !$metaUser->hasSubscription ) {
				$metaUser->objSubscription = new Subscription( $db );
				$metaUser->objSubscription->createNew( $admin->id, 'free', 0 );
				$metaUser->objSubscription->setStatus( 'Excluded' );
			}
		}
	}

	// icons
	$files = array(
					array( 'lib/krumo/krumo.tar.gz',		'lib/krumo/',		0 ),
					array( 'lib/mootools/mootools.tar.gz',	'lib/mootools/',	0 ),
					array( 'processors/ideal_advanced/ideal_advanced.tar.gz',					'processors/ideal_advanced/',	0 )
					);

	// check if joomfish (joomla) or nokkaew (mambo) exists)
	$translation = false;
	if ( file_exists( JPATH_SITE . '/administrator/components/com_joomfish/admin.joomfish.php' ) ) {
		$translation = 'joomfish';
	} elseif ( file_exists( JPATH_SITE . '/administrator/components/com_joomfish/joomfish.php' ) ) {
		$translation = 'joomfish';
	} elseif ( file_exists( JPATH_SITE . '/administrator/components/com_nokkaew/admin.nokkaew.php' ) ) {
		$translation = 'nokkaew';
	}

	if ( $translation ) {
		$lang =& JFactory::getLanguage();

		$lcode = substr( $lang->get('tag'), 0, 2 );

		if ( file_exists( JPATH_SITE . '/administrator/components/com_acctexp/install/jf_content_elements_aec.' . $lcode . '.tar.gz' ) ) {
			$xmlInst = 'install/jf_content_elements_aec.' . $lcode . '.tar.gz';
		} else {
			$xmlInst = 'install/jf_content_elements_aec.en.tar.gz';
		}
		$files[] = array( $xmlInst, '../com_' . $translation . '/contentelements/', 1 );
	}

	$eucaInstall->unpackFileArray( $files );

	$krumoabspath = JPATH_SITE . '/components/com_acctexp/lib/krumo/';
	$krumourlpath = JURI::root() . '/components/com_acctexp/lib/krumo/';

	$eucaInstallef->fileEdit( $krumoabspath . 'krumo.ini', 'http://www.example.com/Krumo/', $krumourlpath, "Krumor Debug Lib did not receive a proper url path, due to writing permission problems" );

	// log installation
	$short		= JText::_('AEC_LOG_SH_INST');
	$event		= sprintf( JText::_('AEC_LOG_LO_INST'), _AEC_VERSION." Revision "._AEC_REVISION );
	$tags		= 'install,system';

	$eventlog	= new eventLog( $db );
	$params		= array( 'userid' => $user->id );
	$eventlog->issue( $short, $tags, $event, 2, $params, 1 );

	$errors = array_merge( $errors, $eucaInstall->getErrors(), $eucaInstalldb->getErrors(), $eucaInstallef->getErrors() );

	if ( !empty( $errors ) ) {
		foreach ( $errors as $error ) {
			$eventlog	= new eventLog( $db );
			$eventlog->issue( '', $tags, $error );
		}
	}

	// Install plugins and modules, if we have them
	jimport('joomla.installer.installer');

	$install_list = array(	'plg_aecaccess' => array ( 'type' => 'user', 'element' => 'aecaccess' ),
							'plg_aecerror' => array ( 'type' => 'system', 'element' => 'aecerrorhandler' ),
							'plg_aecrouting' => array ( 'type' => 'system', 'element' => 'aecrouting' ),
							'plg_aecuser' => array ( 'type' => 'user', 'element' => 'aecuser' ),
							'mod_acctexp' => array ( 'position' => 'left' ),
							'mod_acctexp_cart' => array ( 'position' => 'left' )
	);

	$componentInstaller =& JInstaller::getInstance();

	if ( defined( 'JPATH_MANIFESTS' ) ) {
		$src = dirname(__FILE__);
	} else {
		$src = $componentInstaller->getPath('source');
	}

	$pckgs = 0;
	foreach ( $install_list as $name => $details ) {
		if ( !is_dir( $src.'/'.$name ) ) {
			continue;
		}		

		if ( !strpos( $name, 'plg' ) === 0 ) {
			$query = "SELECT id, position, published FROM #__modules WHERE module = '".$name."'";
			$db->setQuery( $query );
			$lemodule = $db->loadObject();

			if ( $menu->id ) {
				$details['menuid']		= $lemodule->id;
				$details['position']	= $lemodule->position;
				$details['published']	= $lemodule->published;
			} else {
				$details['menuid']		= 0;
				$details['published']	= "0";
			}
		}

		$installer = new JInstaller();
		$result = $installer->install( $src.'/'.$name );

		if ( $result ) {
			if ( strpos( $name, 'plg' ) === 0 ) {
				$query = "UPDATE #__" . ( defined( 'JPATH_MANIFESTS' ) ? "extensions" : "plugins" ) . " SET " . ( defined( 'JPATH_MANIFESTS' ) ? "enabled=1" : "published=1" ) . " WHERE element='".$details['element']."' AND folder='".$details['type']."'";

				$db->setQuery( $query );
				$db->query();
			} else {
				if ( empty( $details['menuid'] ) ) {
					$query = "UPDATE #__modules SET position='".$details['position']."', published=".$details['published']." WHERE module='".$name."'";
					$db->setQuery( $query );
					$db->query();

					$query = "SELECT id FROM #__modules WHERE module = '".$name."'";
					$db->setQuery( $query );
					$module_id = $db->loadResult();
	
					$query = "REPLACE INTO #__modules_menu (moduleid,menuid) VALUES (" . $module_id . ", 0)";

					$db->setQuery( $query );
					$db->query();
				}
			}

			$pckgs++;
		}
	}

	?>

	<style type="text/css">
		.installnote {
			width: 92%;
			margin: 6px 24px;
			color: #ddd;
		}
		.installnote h1 {
			color: #ddd;
			padding: 0;
		}
		.installnote p {
			color: #ddd;
			padding: 0 12px;
		}
		div.packages_installed {
		padding: 0 10px 0 120px;
		border-bottom: 3px solid #4c7000;
		border-top: 3px solid #4c7000;
		color: #fff;
		background: url("../media/com_acctexp/images/admin/icons/aec_symbol_importance_1.png") no-repeat scroll 28px center #7caa00;
		margin-bottom: 14px;
		}
		div.packages_none {
		padding: 0 10px 0 120px;
		border-bottom: 3px solid #706100;
		border-top: 3px solid #706100;
		color: #fff;
		background: url("../media/com_acctexp/images/admin/icons/aec_symbol_importance_2.png") no-repeat scroll 28px center #aa9900;
		margin-bottom: 14px;
		}
		div.packages_installed p, div.packages_none p {
		font-size: 180%;
		}
		div.packages_installed br, div.packages_none br {
		margin-bottom: 20px;
		}
		</style>
	<div style="width: 1024px; margin: 0 auto;">
	<div style="float: left; width: 600px; background: #000 url(<?php echo JURI::root(); ?>media/com_acctexp/images/admin/icons/aec_dist_gfx_0_14.png) no-repeat top right; margin: 0 6px;">
		<div style="width: 100%; height: 290px;"></div>
		<?php
		if ( $errors ) {
			echo '<div style="color: #FF0000; text-align: left; border: 1px solid #FF0000; background-color: #fff; margin: 12px; padding: 8px;">' . "\n"
			. JText::_('AEC_INST_ERRORS')
			. '<ul>' . "\n";
			foreach ( $errors AS $error ) {
				if ( is_array( $error ) ) {
					echo '<li>' . $error[0] . ' - ' . $error[1] . '</li>';
				} else {
					echo '<li>' . $error . '</li>';
				}

			}
			echo '</ul>' . "\n"
			. '</div>' . "\n";
		} ?>
		<div class="<?php echo $pckgs ? 'packages_installed' : 'packages_none'; ?>">
			<p><?php echo $pckgs ? JText::_('AEC_INST_PACKAGES_YES') : JText::_('AEC_INST_PACKAGES_NO'); ?></p>
		</div>
		<div class="installnote">
			<h1><?php echo JText::_('AEC_INST_HINTS'); ?></h1>
			<p><?php echo sprintf( JText::_('AEC_INST_HINT1'), 'http://valanx.org' ); ?></p>
			<p><?php echo JText::_('AEC_INST_HINT2'); ?></p>
		</div>
		<div style="width: 100%; height: 60px;"></div>
	</div>
	<div style="float: left; width: 300px; margin: 0 50px;">
		<div style="margin-left:auto;margin-right:auto;text-align:center;">
			<br />
			<p><img src="<?php echo JURI::root(); ?>media/com_acctexp/images/admin/icons/aec_logo_big.png" border="0" alt="" /></p>
			<br /><br />
			<p><strong>Account Expiration Control</strong> Component - Version <?php echo str_replace( 'omega', '&Omega;', _AEC_VERSION ); ?> - Revision <?php echo _AEC_REVISION; ?></p>
			<p><?php echo JText::_('AEC_FOOT_TX_CHOOSING'); ?></p>
			<div style="margin: 0 auto;text-align:center;">
				<a href="https://www.valanx.org" target="_blank"><img src="<?php echo JURI::root(); ?>media/com_acctexp/images/admin/icons/valanx_logo.png" border="0" alt="valanx.org" /></a>
				<p><?php echo JText::_('AEC_FOOT_TX_GPL'); ?></p>
				<p><?php echo JText::_('AEC_FOOT_TX_SUBSCRIBE'); ?></p>
				<p><?php printf( JText::_('AEC_FOOT_CREDIT'), AECToolbox::backendTaskLink( 'credits', JText::_('AEC_FOOT_CREDIT_LTEXT') ) ); ?></p>
			</div>
		</div>
	</div>
	</div>
	<?php
}

} ?>
