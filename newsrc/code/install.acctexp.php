<?php
/**
 * @version $Id: install.acctexp.php
 * @package AEC - Account Control Expiration - Membership Manager
 * @subpackage Installation
 * @copyright 2006-2015 Copyright (C) David Deutsch
 * @author David Deutsch <skore@valanx.org> & Team AEC - http://www.valanx.org
 * @license GNU/GPL v.3 http://www.gnu.org/licenses/gpl.html or, at your option, any later version
 */

// Dont allow direct linking
defined('_JEXEC') or die( 'Direct Access to this location is not allowed.' );

// Trying to buy us some time
@set_time_limit( 240 );

if ( !defined( '_JEXEC' ) && !defined( 'JPATH_SITE.' ) ) {
	global $mosConfig_absolute_path;

	define( 'JPATH_SITE', $mosConfig_absolute_path );
} elseif ( defined( '_JEXEC' ) ) {
	JLoader::register('JTableUser', JPATH_LIBRARIES.'/joomla/database/table/user.php');
}

// Make sure we are compatible with php4, oh boy
if ( version_compare(phpversion(), '5.0') < 0 ) {
	include_once( JPATH_SITE . '/components/com_acctexp/lib/php4/php4.php' );
}

if ( !class_exists( 'Com_AcctexpInstallerScript' ) ) {
	class Com_AcctexpInstallerScript
	{
		public function postflight( $type, $parent )
		{
			$this->install();
		}

		public function install()
		{
			if ( defined('AEC_INSTALLER_CALLED') ) return true;

			define( 'AEC_INSTALLER_CALLED', true );

			$errors = array();

			$this->prepare();

			$this->bootstrap( $errors );

			// Load root install and database object
			$eucaInstall	= new eucaInstall();
			$eucaInstalldb	= new eucaInstallDB();

			$this->initDB( $errors, $eucaInstalldb );

			$this->upgrade( $errors, $eucaInstall, $eucaInstalldb );

			$this->checkJinstall();

			$this->touchProcessors();
			$this->touchMIs();

			$pkgs = $this->installPackages();

			$this->initTemplate();

			$this->initAdmins();

			$this->installTranslators( $eucaInstall );

			$this->popIndex( $eucaInstall );
			$this->lessen( $errors );

			$this->logInstall();
			$this->logErrors( $errors, $eucaInstall, $eucaInstalldb );

			$this->splash( $pkgs, $errors );
		}

		public function prepare()
		{
			if ( defined( 'JPATH_MANIFESTS' ) ) {
				$this->src = dirname(__FILE__);
			} else {
				$componentInstaller = JInstaller::getInstance();

				$this->src = $componentInstaller->getPath('source');
			}
		}
		public function checkJinstall()
		{
			$engbpath = $this->src.'/../../language/en-GB';

			// So Joomla sometimes fails to install our en-GB...
			if ( !file_exists( $engbpath.'/en-GB.com_acctexp.ini' ) ) {
				$files = array(
					'',
					'.iso3166-1a2',
					'.iso639-1',
					'.microintegrations',
					'.processors'
				);

				foreach ( $files as $file ) {
					copy(
						$this->src.'/language/en-GB/en-GB.com_acctexp' . $file . '.ini',
						$engbpath.'/en-GB.com_acctexp' . $file . '.ini'
					);
				}
			}

			$engbpath = $this->src.'/../../administrator/language/en-GB';

			if ( !file_exists( $engbpath.'/en-GB.com_acctexp.ini' ) ) {
				$files = array( '', '.iso4217', '.menu', '.sys' );

				foreach ( $files as $file ) {
					copy(
						$this->src.'/language/en-GB/en-GB.com_acctexp' . $file . '.ini',
						$engbpath.'/en-GB.com_acctexp' . $file . '.ini'
					);
				}
			}
		}

		public function bootstrap( &$errors )
		{
			$is_j16 = defined( 'JPATH_MANIFESTS' );

			$langlist = array(	array( 'com_acctexp', JPATH_ADMINISTRATOR ),
					array( 'com_acctexp.sys', JPATH_ADMINISTRATOR ),
					array( 'com_acctexp', JPATH_SITE ),
					array( 'com_acctexp.microintegrations', JPATH_SITE ),
					array( 'com_acctexp.processors', JPATH_SITE )
			);

			$lang = JFactory::getLanguage();

			foreach ( $langlist as $array ) {
				$lang->load( $array[0] );
				$lang->load( $array[0], $array[1], 'en-GB', true );
				$lang->load( $array[0], $array[1], $lang->getDefault(), true );
				$lang->load( $array[0], $array[1], null, true );
			}

			if ( !defined( 'JPATH_MANIFESTS' ) ) {
				foreach ( $lang->_strings as $k => $v ) {
					$lang->_strings[$k]= str_replace( '"_QQ_"', '"', $v );
				}
			}

			require_once( JPATH_SITE . '/components/com_acctexp/lib/compat.php' );

			require_once( JPATH_SITE . '/components/com_acctexp/lib/eucalib/eucalib.php' );
			require_once( JPATH_SITE . '/components/com_acctexp/lib/eucalib/eucalib.install.php' );
		}

		public function initDB( $errors, $eucaInstalldb )
		{
			$db = JFactory::getDBO();

			// Slot in DB tables that do not exist yet
			$incpath = JPATH_SITE . '/administrator/components/com_acctexp/install/inc';

			$tables = $db->getTableList();

			$this->new = true;

			foreach ( $tables as $table ) {
				if ( strpos( 'acctexp', $table ) !== false ) {
					$this->new = false;
					break;
				}
			}

			$queri = array();
			require_once( $incpath . '/dbtables.inc.php' );

			$eucaInstalldb->multiQueryExec( $queri );
		}

		public function upgrade( &$errors, $eucaInstall, $eucaInstalldb )
		{
			$db = JFactory::getDBO();
			$app = JFactory::getApplication();

			// Overall Variables
			$tables		= $db->getTableList();

			$incpath = JPATH_SITE . '/administrator/components/com_acctexp/install/inc';

			// Upgrade ancient settings
			include_once( $incpath . '/settings_oldupgrade.inc.php' );

			// Upgrade Settings to 0.12.6 status
			include_once( $incpath . '/settings_0_12_6_upgrade.inc.php' );

			// Load Class (and thus aecConfig)
			require_once( JPATH_SITE . '/components/com_acctexp/acctexp.class.php' );

			$aecConfig = new aecConfig();

			if ( !empty($aecConfig->cfg) ) {
				$this->new = false;
			}

			$aecConfig->initParams();

			$document= JFactory::getDocument();
			$document->addCustomTag( '<link rel="stylesheet" type="text/css" media="all" href="' . JURI::root() . 'media/com_acctexp/css/admin.css?rev=' . _AEC_REVISION .'" />' );

			if ( isset( $aecConfig->cfg['aec_version'] ) ) {
				$oldversion = $aecConfig->cfg['aec_version'];
			} else {
				$oldversion = '0.0.1';

				if ( !$eucaInstalldb->ColumninTable( 'ordering', 'coupons' ) ) {
					$oldversion = '1.0.0';
				} else {
					$db->setQuery("SHOW INDEXES FROM #__acctexp_subscr");

					$indexes = $db->loadObjectList();

					foreach ( $indexes as $index ) {
						if ( strpos( $index->Key_name, 'userid' ) !== false ) {
							$oldversion = '0.14.6';
						}
					}
				}
			}

			if ( $this->new ) return;

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

			$incfiles = xJUtility::getFileArray( $incpath, 'inc.php', false, true );

			$versions = array();
			foreach ( $incfiles as $filename ) {
				if ( strpos( $filename, 'upgrade_' ) === false ) {
					continue;
				} else {
					$versions[] = str_replace( array( 'upgrade_', '.inc.php' ), array( '', '' ), $filename );
				}
			}

			$versions = xJUtility::versionSort( $versions );

			$old = xJUtility::normVersionName( $oldversion );

			foreach ( $versions as $version ) {
				$new = xJUtility::normVersionName( $version );

				if ( version_compare( $new, $old, '>=' ) ) {
					require_once( $incpath . '/upgrade_' . $version . '.inc.php' );
				}
			}

			$aecConfig->cfg['aec_version'] = _AEC_VERSION;

			$aecConfig->saveSettings();
		}

		public function touchProcessors()
		{
			// Make sure settings & info are updated
			$pplist = PaymentProcessorHandler::getInstalledNameList();

			foreach ( $pplist as $ppname ) {
				$pp = new PaymentProcessor();

				if ( !$pp->loadName( $ppname ) ) continue;

				$pp->copyAssets();

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
		}

		public function touchMIs()
		{
			// Make sure mi language files are updated
			$milist = microIntegrationHandler::getMIList();

			$midone = array();
			foreach ( $milist as $miobj ) {
				if ( in_array( $miobj->class_name, $midone ) ) {
					continue;
				}

				$mifobj = new microIntegration();

				if ( $mifobj->load( $miobj->id ) ) {
					$mifobj->copyAssets();

					$midone[] = $miobj->class_name;
				}
			}
		}

		public function installPackages()
		{
			// Install plugins and modules, if we have them
			jimport('joomla.installer.installer');

			$install_list = array(
				'plg_aecaccess' => array ( 'type' => 'user', 'element' => 'aecaccess' ),
				'plg_aecerror' => array ( 'type' => 'system', 'element' => 'aecerrorhandler' ),
				'plg_aecrouting' => array ( 'type' => 'system', 'element' => 'aecrouting' ),
				'plg_aecuser' => array ( 'type' => 'user', 'element' => 'aecuser' ),
				'plg_aecrewrite' => array ( 'type' => 'content', 'element' => 'aecrewrite' ),
				// 'plg_aecrestriction' => array ( 'type' => 'content', 'element' => 'aecrestriction' ),
				'mod_acctexp' => array ( 'position' => 'left' ),
				'mod_acctexp_cart' => array ( 'position' => 'left' )
			);

			$db = JFactory::getDBO();

			$pckgs = 0;
			foreach ( $install_list as $name => $details ) {
				if ( !is_dir( $this->src.'/'.$name ) ) continue;

				if ( strpos( $name, 'mod' ) === 0 ) {
					$query = "SELECT id, position, published FROM #__modules WHERE module = '" . $name . "'";
					$db->setQuery( $query );

					$lemodule = $db->loadObject();

					if ( $lemodule->id ) {
						$details['menuid']		= $lemodule->id;
						$details['position']	= $lemodule->position;
						$details['published']	= $lemodule->published;
					} else {
						$details['menuid']		= 0;
						$details['published']	= "0";
					}
				}

				$installer = new JInstaller();
				$result = $installer->install( $this->src . '/' . $name );

				if ( !$result ) continue;

				if ( ( strpos( $name, 'plg' ) === 0 ) && ( strpos( $name, 'plg_aecrewrite' ) !== 0 ) ) {
					if ( defined('JPATH_MANIFESTS') ) {
						$query = "UPDATE #__extensions SET enabled=1"
							. " WHERE element='" . $details['element'] . "'";
					} else {
						$query = "UPDATE #__plugins SET published=1"
							. " WHERE element='" . $details['element'] . "'";
					}

					$db->setQuery( $query );
					$db->query();
				} elseif ( strpos( $name, 'plg' ) !== 0 ) {
					if ( empty( $details['menuid'] ) ) {
						if ( empty( $details['published'] ) ) {
							$details['published'] = "0";
						}

						// Kill the old XML and replace it with the new one, taking out the suffix
						/*if ( defined('JPATH_MANIFESTS') ) {
							$xml_path = $this->src.'/../../../modules/' . $name . '/' . $name . '.xml';
							$xml_path_suffixed = str_replace('.xml', '_3.xml', $xml_path);

							if ( file_exists($xml_path_suffixed) && file_exists($xml_path) ) {
								//unlink($xml_path);

								//rename($xml_path_suffixed, $xml_path);
							}
						}*/

						$query = "UPDATE #__modules SET position='"
							. $details['position'] . "', published=" . $details['published'] . " WHERE module='" . $name . "'";

						$db->setQuery( $query );
						$db->query();

						$query = "SELECT id FROM #__modules WHERE module = '".$name."'";
						$db->setQuery( $query );
						$module_id = $db->loadResult();

						if ( $module_id ) {
							$db->setQuery(
								"REPLACE INTO #__modules_menu (moduleid, menuid) VALUES (" . $module_id . ", 0)"
							);

							$db->query();
						}
					}
				}

				$pckgs++;
			}

			return $pckgs;
		}

		public function initTemplate()
		{
			// Set up new template for new installs
			$template = new configTemplate();
			$template->loadDefault();

			if ( !empty( $template->id ) ) return;

			$template->name = 'etacarinae';
			$template->default = 1;

			$template->storeload();
		}

		public function initAdmins()
		{
			$db = JFactory::getDBO();

			$incpath = JPATH_SITE . '/administrator/components/com_acctexp/install/inc';

			// Create root group
			require_once( $incpath . '/create_rootgroup.inc.php' );

			// Make all Superadmins excluded by default
			$administrators = xJACLhandler::getSuperAdmins();

			if ( empty( $administrators ) ) return;

			foreach ( $administrators as $admin ) {
				$metaUser = new metaUser( $admin->id );

				if ( $metaUser->hasSubscription ) continue;

				$metaUser->objSubscription = new Subscription();
				$metaUser->objSubscription->createNew( $admin->id, 'free', 0 );
				$metaUser->objSubscription->setStatus( 'Excluded' );
			}
		}

		public function installTranslators( $eucaInstall )
		{
			$files = array();
			/*$files = array(
				array(
					'processors/ideal_advanced/lib/ideal_advanced.tar.gz',
					'processors/ideal_advanced/lib/',
					0
				)
			);*/

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
				$lang = JFactory::getLanguage();

				$lcode = substr( $lang->get('tag'), 0, 2 );

				if ( file_exists( JPATH_SITE . '/administrator/components/com_acctexp/install/jf_content_elements_aec.' . $lcode . '.tar.gz' ) ) {
					$xmlInst = 'install/jf_content_elements_aec.' . $lcode . '.tar.gz';
				} else {
					$xmlInst = 'install/jf_content_elements_aec.en.tar.gz';
				}
				$files[] = array( $xmlInst, '../com_' . $translation . '/contentelements/', 1 );
			}

			if ( !empty($files) ) $eucaInstall->unpackFileArray($files);
		}

		public function popIndex( $eucaInstall )
		{
			$eucaInstall->popIndex(
				array(
					JPATH_ADMINISTRATOR . '/components/com_acctexp',
					JPATH_SITE . '/components/com_acctexp',
					JPATH_SITE . '/plugins/system/aecerrorhandler',
					JPATH_SITE . '/plugins/system/aecrouting',
					JPATH_SITE . '/plugins/content/aecrewrite',
					JPATH_SITE . '/plugins/user/aecaccess',
					JPATH_SITE . '/plugins/user/aecuser',
					JPATH_SITE . '/modules/mod_acctexp',
					JPATH_SITE . '/modules/mod_acctexp_cart',
					JPATH_SITE . '/media/mod_acctexp'
				)
			);
		}

		public function lessen( &$errors )
		{
			// Convert LESS files
			include_once( JPATH_SITE . '/components/com_acctexp/lib/lessphp/lessc.inc.php' );

			$less = new lessc();
			$less->setImportDir( array(JPATH_SITE . '/media/com_acctexp/less/') );
			$less->setPreserveComments(true);

			$v = new JVersion();

			if ( $v->isCompatible('3.0') ) {
				$less->compileFile(
					JPATH_SITE . "/media/com_acctexp/less/admin-j3.less",
					JPATH_SITE . '/media/com_acctexp/css/admin.css' );
			} else {
				$less->compileFile(
					JPATH_SITE . "/media/com_acctexp/less/admin.less",
					JPATH_SITE . '/media/com_acctexp/css/admin.css'
				);
			}
		}

		public function logInstall()
		{
			$user = JFactory::getUser();

			$eventlog = new eventLog();
			$eventlog->issue(
				JText::_('AEC_LOG_SH_INST'),
				'install,system',
				sprintf( JText::_('AEC_LOG_LO_INST'), _AEC_VERSION." Revision "._AEC_REVISION ),
				2,
				array( 'userid' => $user->id ),
				1
			);
		}

		public function logErrors( $errors, $eucaInstall, $eucaInstalldb )
		{
			$errors = array_merge( $errors, $eucaInstall->getErrors(), $eucaInstalldb->getErrors() );

			$tags = array();

			if ( empty( $errors ) ) return;

			foreach ( $errors as $error ) {
				$eventlog	= new eventLog();
				$eventlog->issue( '', $tags, $error );
			}
		}

		public function splash( $pckgs, $errors )
		{
		?>
			<link rel="stylesheet" type="text/css" media="all" href="<?php echo JURI::root() ?>media/com_acctexp/css/admin.css?rev=<?php echo _AEC_REVISION ?>" />
			<style type="text/css">
				dl#system-message {
					display: none;
				}
				table.adminform tr:first-child {
					display: none;
				}
				table.adminform {
					border: none;
					background: none;
				}
			</style>
			<div style="width: 1024px; margin: 12px auto;">
			<div class="installmain">
				<div style="width: 100%; height: 290px;"></div>
				<?php
				if ( $errors ) {
					echo '<div style="color: #f00; text-align: left; border: 1px solid #foo; background-color: #fff; margin: 12px; padding: 8px;">' . "\n"
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
				<div class="<?php echo $pckgs ? 'packages-installed' : 'packages-none'; ?>">
					<p><?php echo $pckgs ? JText::_('AEC_INST_PACKAGES_YES') : JText::_('AEC_INST_PACKAGES_NO'); ?></p>
				</div>
				<div class="install-golink">
					<p><a href="index.php?option=com_acctexp"><?php echo JText::_('Use AEC Now'); ?>!</a></p>
				</div>
			</div>
			<div style="float: left; width: 300px; margin: 0 50px;">
				<div style="margin-left:auto;margin-right:auto;text-align:center;">
					<p><img src="<?php echo JURI::root(); ?>media/com_acctexp/images/admin/icons/aec_logo_big.png" border="0" alt="" /></p>
					<p><strong>Account Expiration Control</strong> Component - Version <?php echo str_replace( 'beta', '&beta;', _AEC_VERSION ); ?> - Revision <?php echo _AEC_REVISION; ?></p>
					<p><?php echo JText::_('AEC_FOOT_TX_CHOOSING'); ?></p>
					<div style="margin: 0 auto;text-align:center;">
						<a href="https://www.valanx.org" target="_blank"><img src="<?php echo JURI::root(); ?>media/com_acctexp/images/admin/gfx/valanx_logo.png" border="0" alt="valanx.org" /></a>
						<p><?php echo JText::_('AEC_FOOT_TX_GPL'); ?></p>
					</div>
					<p><?php printf( JText::_('AEC_FOOT_CREDIT'), AECToolbox::backendTaskLink( 'credits', JText::_('AEC_FOOT_CREDIT_LTEXT') ) ); ?></p>
				</div>
			</div>
			</div>
			<?php
		}
	}
}

if ( !function_exists( 'com_install' ) ) {
	function com_install()
	{
		$installer = new Com_AcctexpInstallerScript;
		$installer->install();
	}
}
