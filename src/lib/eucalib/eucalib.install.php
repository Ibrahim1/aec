<?php
/**
 * @version $Id: eucalib.common.php
 * @package Eucalib: Component library the Joomla! CMS
 * @subpackage Eucalib Common Files
 * @copyright Copyright (C) 2007 David Deutsch, All Rights Reserved
 * @author David Deutsch <skore@skore.de>
 * @license GNU/GPL v.2 or later http://www.gnu.org/copyleft/gpl.html
 *
 *                         _ _ _
 *                        | (_) |
 *     ___ _   _  ___ __ _| |_| |__
 *    / _ \ | | |/ __/ _` | | | '_ \
 *   |  __/ |_| | (_| (_| | | | |_) |
 *    \___|\__,_|\___\__,_|_|_|_.__/  v1.0
 *
 * The Extremely Useful Component LIBrary will rock your socks. Seriously. Reuse it!
 */

defined( '_VALID_MOS' ) or die( 'Restricted access' );

class eucaInstall
{
	function eucaInstall()
	{

	}

	function unpackFileArray( $array )
	{
		global $mosConfig_absolute_path;

		if ( !class_exists( 'Archive_Tar' ) ) {
			require_once( $mosConfig_absolute_path . '/includes/Archive/Tar.php' );
		}

		foreach ( $array as $file ) {
			if ( $file[2] ) {
				$basepath = $mosConfig_absolute_path . '/administrator/components/com_' . _EUCA_APP_SHORTNAME;

				$fullpath	= $basepath . $file[0];
				$deploypath = $basepath . $file[1];
			} else {
				$basepath = $mosConfig_absolute_path . '/components/com_' . _EUCA_APP_SHORTNAME;

				$fullpath	= $basepath . $file[0];
				$deploypath = $basepath . $file[1];
			}

			$archive = new Archive_Tar( $fullpath, 'gz' );

			if ( !@is_dir( $deploypath ) ) {
				// Borrowed from php.net page on mkdir. Created by V-Tec (vojtech.vitek at seznam dot cz)
				$folder_path = array( strstr( $deploypath, '.' ) ? dirname( $deploypath ) : $deploypath );

				while ( !@is_dir( dirname( end( $folder_path ) ) )
						&& dirname(end($folder_path)) != '/'
						&& dirname(end($folder_path)) != '.'
						&& dirname(end($folder_path)) != '' ) {
					array_push( $folder_path, dirname( end( $folder_path ) ) );
				}

				while ( $parent_folder_path = array_pop( $folder_path ) ) {
					@mkdir( $parent_folder_path, 0644 );
				}
			}

			if ( $archive->extract( $deploypath ) ) {
				@unlink( $fullpath );
			}
		}
	}

	function createAdminMenuEntry( $entry )
	{
		global $database;

		// Delete old menu entries
		$query = 'DELETE *'
		. ' FROM #__components'
		. ' WHERE option = \'option=com_' . _EUCA_APP_SHORTNAME . '\''
		;
		$database->setQuery( $query );
		$database->query();

		// Create new entry
		$return = $this->AdminMenuEntry( $entry, 0, 0 );

		if ( $return === true ) {
			return;
		} else {
			return array( $return );
		}
	}

	function populateAdminMenuEntry( $array )
	{
		global $database;

		// get id from component entry
		$query = 'SELECT id'
		. ' FROM #__components'
		. ' WHERE link = \'option=com_' . _EUCA_APP_SHORTNAME . '\''
		;
		$database->setQuery( $query );
		$database->query();
		$id = $database->loadResult();

		$k = 0;
		$errors = array();
		foreach ( $array as $entry ) {
			$return = $this->AdminMenuEntry( $entry, $id, $k );

			if ( $return === true ) {
				$k++;
			} else {
				$errors[] = $return;
			}
		}

		return $errors;
	}

	function AdminMenuEntry ( $entry, $id, $ordering )
	{
		global $database;

		// id, name, link, menuid, parent, admin_menu_link, admin_menu_alt, option, ordering, admin_menu_img, iscore, params
		$values = array();
		$values[] = '';
		$values[] = $entry[1];
		$values[] = 'option=com_' . _EUCA_APP_SHORTNAME;
		$values[] = $ordering;
		$values[] = $id;
		$values[] = 'option=com_' . _EUCA_APP_SHORTNAME . '&task=' . $entry[0];
		$values[] = $entry[1];
		$values[] = 'com_' . _EUCA_APP_SHORTNAME;
		$values[] = $ordering;
		$values[] = $entry[2];
		$values[] = '0';
		$values[] = '';

		$query = 'INSERT INTO #__components'
		. ' VALUES '
		. '(\'' . implode( '\', \'', $values) . '\')'
		;

		$database->setQuery( $query );
		if ( !$database->query() ) {
	    	return $database->getErrorMsg();
		} else {
			return true;
		}
	}
}

?>