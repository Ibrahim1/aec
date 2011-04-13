<?php
/**
 * @version $Id: translate.php
 * @package AEC - Account Control Expiration - Membership Manager
 * @subpackage Translate J!1.0 -> J!1.5/1.6 language files
 * @copyright 2006-2011 Copyright (C) David Deutsch
 * @author David Deutsch <skore@skore.de> & Team AEC - http://www.valanx.org
 * @license GNU/GPL v.3 http://www.gnu.org/licenses/gpl.html or, at your option, any later version
 */

// Point this to the root directory of your software
$path = realpath( dirname(__FILE__) .'/../../code' );

// The root language that everything else is based and formatted after
$rootlang = 'english';

// Creating a temp directory
$temppath = dirname(__FILE__) .'/temp';

// (Make sure we have a fresh start)
if ( file_exists( $temppath ) ) {
	vTranslate::rrmdir( $temppath );
}

mkdir( $temppath );

// Log - this is broken cause I'm too stupid
if ( file_exists( $temppath."/log.txt" ) ) {
	unlink( $temppath."/log.txt" );
}

$log = new SplFileObject( $temppath."/log.txt", 'w' );

// Get all our folders, including project root
$stddirs = array();
$stddirs[] = $path;
$stddirs = vTranslate::getFolders( $path, $stddirs );

// Convert EVERYTHINGWOOOOOT
foreach( $stddirs as $sourcedir ) {
	vTranslate::log( "Processing: " . $sourcedir . "\n", $log );

	$targetpath = str_replace( $path, $temppath, $sourcedir );

	if ( !is_dir( $targetpath ) ) {
		mkdir( $targetpath );
	}

	$files = vTranslate::getFiles( $sourcedir );

	foreach ( $files as $f ) {
		$file = new SplFileObject( $sourcedir.'/'.$f );

		$targetfile = new SplFileObject( $targetpath.'/'.$f, 'w' );

		while ( !$file->eof() ) {
			$line = $file->fgets();

			if ( strpos( $file->getFilename(), '.ini' ) ) {
				if ( strpos( $line, '_' ) === 0 ) {
					$line = substr( $line, 1 );
				}
			} elseif ( strpos( $file->getFilename(), '.php' ) ) {
				$line = str_replace( "JText::_('_", "JText::_('", $line );
			}

			$targetfile->fwrite( $line );
		}
	}

}

// et voilà
vTranslate::log( "All done." . "\n\n", $log );

class vTranslate
{
	function getFolders( $path, $list=array(), $other=false )
	{
		$iterator = new DirectoryIterator( $path );

		foreach( $iterator as $object ) {
			if ( $object->isDot() ) {
				continue;
			}

			if ( $object->isDir() ) {
				$list[] = $object->getPathname();

				$list = array_merge( vTranslate::getFolders($object->getPathname(), $list, $other) );
			}
		}

		return $list;
	}

	function getFiles($path)
	{
		$iterator = new DirectoryIterator( $path );

		$arr = array();
		foreach( $iterator as $object ) {
			if ( !$object->isDot() && !$object->isDir() ) {
				if ( strpos( $object->getFilename(), '.php' ) || strpos( $object->getFilename(), '.ini' ) ) {
					$arr[] = $object->getFilename();
				}
			}
		}

		return $arr;
	}

	function rrmdir( $dir )
	{
		if ( is_dir($dir) ) {
			$objects = scandir($dir);
			foreach ( $objects as $object ) {
				if ( $object != "." && $object != ".." ) {
					if ( filetype($dir."/".$object) == "dir" ) {
						vTranslate::rrmdir( $dir."/".$object );
					} else {
						unlink( $dir."/".$object );
					}
				}
			}

			reset($objects);

			rmdir($dir);
		}
	}

	function log( $thing, $log )
	{
		echo $thing;

		$log->fwrite( $thing );
	}
}
?>
