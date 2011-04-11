<?php
/**
 * @version $Id: translate.php
 * @package AEC - Account Control Expiration - Membership Manager
 * @subpackage Translate J!1.0 -> J!1.5/1.6 language files
 * @copyright 2006-2011 Copyright (C) David Deutsch
 * @author David Deutsch <skore@skore.de> & Team AEC - http://www.valanx.org
 * @license GNU/GPL v.3 http://www.gnu.org/licenses/gpl.html or, at your option, any later version
 */

$path = realpath( dirname(__FILE__) .'/../../code' );

$rootlang = 'english';

$temppath = dirname(__FILE__) .'/temp';

if ( !is_dir( dirname(__FILE__) .'/temp' ) ) {
	mkdir( dirname(__FILE__) .'/temp' );
}

$dirs = vTranslate::getFolders( $path );

foreach( $dirs as $sourcedir ) {
	echo "Processing: " . $sourcedir . "\n";

	$dpath = explode( '/', $sourcedir );

	$l = count( $dpath );

	$parentpath = $temppath . '/' . $dpath[$l-2];

	if ( !is_dir( $parentpath ) ) {
		mkdir( $parentpath );
	}

	$targetpath = $parentpath . '/' . $dpath[$l-1];

	if ( !is_dir( $targetpath ) ) {
		mkdir( $targetpath );
	}

	$files = vTranslate::getFiles( $sourcedir );

	if ( !in_array($rootlang.'.php', $files) ) {
		echo "ERROR: Root Language not found: " . $sourcedir . "/" . $rootlang.'.php' . "\n";

		continue;
	} else {
		echo "Root Language found: " . $rootlang . "\n";
	}

	$translations = array();
	foreach ( $files as $file ) {
		if ( $file != $rootlang.'.php' ) {
			$translations[] = str_replace( '.php', '', $file );
		}
	}

	echo "Translations found: " . implode( ", ", $translations ) . "\n";

	$file = new SplFileObject( $sourcedir.'/'.$rootlang.'.php' );

	while ( !$file->eof() ) {
		echo $file->fgets();exit;
	}

	echo "\n";
}

class vTranslate
{
	function getFolders( $path, $list=array() )
	{
		$iterator = new DirectoryIterator( $path );

		foreach( $iterator as $object ) {
			if ( $object->isDot() ) {
				continue;
			}

			if ( $object->isDir() ) {
				if ( ($object->getFilename() == 'language') || ($object->getFilename() == 'lang') ) {
					$list[] = $object->getPathname();
				}

				$list = array_merge( vTranslate::getFolders($object->getPathname(), $list) );
			}
		}

		return $list;
	}

	function getFiles($path)
	{
		$iterator = new DirectoryIterator( $path );

		foreach( $iterator as $object ) {
			if ( !$object->isDot() && !$object->isDir() ) {
				if ( strpos( $object->getFilename(), '.php' ) ) {
					$arr[] = $object->getFilename();
				}
			}
		}

		return $arr;
	}
}
?>
