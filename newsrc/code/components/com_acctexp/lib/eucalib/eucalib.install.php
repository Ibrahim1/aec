<?php
/**
 * @version $Id: eucalib.common.php
 * @package AEC - Account Control Expiration - Membership Manager
 * @subpackage Eucalib Common Files
 * @copyright 2006-2010 Copyright (C) David Deutsch
 * @author David Deutsch <skore@skore.de> & Team AEC - http://www.valanx.org
 * @license GNU/GPL v.2 http://www.gnu.org/licenses/old-licenses/gpl-2.0.html or, at your option, any later version
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

( defined('_JEXEC') || defined( '_VALID_MOS' ) ) or die( 'Restricted access' );

class eucaInstall extends eucaObject
{
	function eucaInstall()
	{}

	function unpackFileArray( $array )
	{
		if ( !class_exists( 'Archive_Tar' ) || function_exists( 'PclTarExtract' ) ) {
			require_once( JPATH_SITE . '/administrator/includes/pcl/pcltar.lib.php' );
		}

		foreach ( $array as $file ) {
			if ( !empty( $file[3] ) ) {
				if ( $file[3] == 2 ) {
					$basepath = JPATH_SITE . '/media/' . _EUCA_APP_COMPNAME . '/js/';
				} elseif ( $file[2] ) {
					$basepath = JPATH_SITE . '/media/' . _EUCA_APP_COMPNAME . '/images/admin/';
				} else {
					$basepath = JPATH_SITE . '/media/' . _EUCA_APP_COMPNAME . '/images/site/';
				}

				$fullpath	= $basepath . $file[0];
				$deploypath = $basepath . $file[1];
			} else {
				if ( $file[2] ) {
					$basepath = JPATH_SITE . '/administrator/components/' . _EUCA_APP_COMPNAME . '/';
				} else {
					$basepath = JPATH_SITE . '/components/' . _EUCA_APP_COMPNAME . '/';
				}

				$fullpath	= $basepath . $file[0];
				$deploypath = $basepath . $file[1];
			}

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

			if ( PclTarExtract( $fullpath, $deploypath) !== 0 ) {
				@unlink( $fullpath );
			} else {
				$this->setError( array( 'Extraction Error', 'the file ' . $file[0] . ' could not be extracted to ' . $deploypath . '. You can try to unpack the files yourself.' ) );
			}
		}
	}

	function deleteAdminMenuEntries()
	{
		$db = &JFactory::getDBO();

		$query = 'DELETE'
				. ' FROM #__components'
				. ' WHERE `option` LIKE "%option=' . _EUCA_APP_COMPNAME . '%"'
				. ' OR `option`=\'' . _EUCA_APP_COMPNAME . '\''
				;
		$db->setQuery( $query );

		if ( !$db->query() ) {
	    	$this->setError( array( $db->getErrorMsg(), $query ) );
		}
	}

	function createAdminMenuEntry( $entry )
	{
		// Create new entry
		$return = $this->AdminMenuEntry( $entry, 0, 0, 1 );

		if ( $return === true ) {
			return;
		} else {
			return array( $return );
		}
	}

	function populateAdminMenuEntry( $array )
	{
		$db = &JFactory::getDBO();

		// get id from component entry
		$query = 'SELECT `id`'
				. ' FROM #__components'
				. ' WHERE `link` = \'option=' . _EUCA_APP_COMPNAME . '\''
				;
		$db->setQuery( $query );
		$id = $db->loadResult();

		$k = 0;
		$errors = array();
		foreach ( $array as $entry ) {
			if ( $this->AdminMenuEntry( $entry, $id, $k ) ) {
				$k++;
			}
		}
	}

	function AdminMenuEntry ( $entry, $id, $ordering, $frontend=0 )
	{
		$db = &JFactory::getDBO();

		$values = array();
		$fields = array();

		$fields[] = 'id';
		$values[] = '';
		$fields[] = 'name';
		$values[] = $entry[1];
		$fields[] = 'link';
		$values[] = $frontend ? ( 'option=' . _EUCA_APP_COMPNAME ) : '' ;
		$fields[] = 'parent';
		$values[] = $id;
		$fields[] = 'admin_menu_link';
		$values[] = 'option=' . _EUCA_APP_COMPNAME . '&task=' . $entry[0];
		$fields[] = 'admin_menu_alt';
		$values[] = $entry[1];
		$fields[] = 'option';
		$values[] = _EUCA_APP_COMPNAME;
		$fields[] = 'ordering';
		$values[] = isset( $entry[3] ) ? $entry[3] : $ordering;
		$fields[] = 'admin_menu_img';
		$values[] = $entry[2];

		$query = 'INSERT INTO #__components'
				. ' (`' . implode( '`, `', $fields) . '`)'
				. ' VALUES '
				. '(\'' . implode( '\', \'', $values) . '\')'
				;
		$db->setQuery( $query );

		if ( !$db->query() ) {
	    	$this->setError( array( $db->getErrorMsg(), $query ) );
	    	return false;
		} else {
			return true;
		}
	}
}

class eucaInstallDB extends eucaObject
{
	function eucaInstallDB()
	{}

	function multiQueryExec( $queri )
	{
		$db = &JFactory::getDBO();

		foreach ( $queri as $query ) {
			$db->setQuery( $query );
		    if ( !$db->query() ) {
		        $this->setError( array( $db->getErrorMsg(), $query ) );
		    }
		}
	}

	function ColumninTable( $column=null, $table=null, $prefix=true )
	{
		$db = &JFactory::getDBO();

		if ( !empty( $column ) ) {
			$this->column = $column;
		}

		if ( !empty( $table ) ) {
			if ( $prefix ) {
				$this->table = _EUCA_APP_SHORTNAME . '_' . $table;
			} else {
				$this->table = $table;
			}
		}

		$query = 'SHOW COLUMNS FROM #__' . $this->table
				. ' LIKE \'' . $this->column . '\''
				;

		$db->setQuery( $query );
		$result = $db->loadObject();

		if( is_object( $result ) ) {
			if ( strcmp($result->Field, $column) === 0 ) {
				return true;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}

	function dropColifExists( $column, $table, $prefix=true )
	{
		if ( $this->ColumninTable( $column, $table, $prefix ) ) {
			return $this->dropColumn( $column );
		}
	}

	function addColifNotExists( $column, $options, $table, $prefix=true )
	{
		if ( !$this->ColumninTable( $column, $table, $prefix ) ) {
			return $this->addColumn( $options );
		}
	}

	function addColumn( $options )
	{
		$db = &JFactory::getDBO();

		$query = 'ALTER TABLE #__' . $this->table
				. ' ADD COLUMN `' . $this->column . '` ' . $options
				;

		$db->setQuery( $query );

		$result = $db->query();

		if ( !$result ) {
	    	$this->setError( array( $db->getErrorMsg(), $query ) );
	    	return false;
		} else {
			return true;
		}
	}

	function dropTableifExists( $table, $prefix=true )
	{
		$db = &JFactory::getDBO();

		if ( !empty( $table ) ) {
			if ( $prefix ) {
				$this->table = _EUCA_APP_SHORTNAME . '_' . $table;
			} else {
				$this->table = $table;
			}
		}

		$query = 'DROP TABLE IF EXISTS #__' . $this->table
		;

		$db->setQuery( $query );

		$result = $db->query();

		if ( !$result ) {
	    	$this->setError( array( $db->getErrorMsg(), $query ) );
	    	return false;
		} else {
			return true;
		}
	}

	function dropColumn( $options )
	{
		$db = &JFactory::getDBO();

		$query = 'ALTER TABLE #__' . $this->table
				. ' DROP COLUMN `' . $this->column . '`'
				;

		$db->setQuery( $query );

		$result = $db->query();

		if ( !$result ) {
	    	$this->setError( array( $db->getErrorMsg(), $query ) );
	    	return false;
		} else {
			return true;
		}
	}

}

class eucaInstalleditfile extends eucaObject
{
	function eucaInstalleditfile()
	{}

	function fileEdit( $path, $search, $replace, $throwerror )
	{
		$originalFileHandle = fopen( $path, 'r' );

		if ( $originalFileHandle != false ) {
			// Transfer File into variable $oldData
			$oldData = fread( $originalFileHandle, filesize( $path ) );
			fclose( $originalFileHandle );

			$newData = str_replace( $search, $replace, $oldData );

			$oldperms = fileperms( $path );
			@chmod( $path, $oldperms | 0222 );

			if ( $fp = fopen( $path, 'wb' ) ) {
				if ( fwrite( $fp, $newData, strlen( $newData ) ) != -1 ) {
					fclose( $fp );
					@chmod( $path, $oldperms );
					return true;
				}
		    }
		}

		$this->setError( $throwerror );
		return false;
	}

}

?>
