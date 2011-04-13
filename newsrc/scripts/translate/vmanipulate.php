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

$all_targets = array();

// Get all our folders, including project root
$stddirs = array();
$stddirs[] = $path;
$stddirs = vTranslate::getFolders( $path, $stddirs );

// Create all the target directories
foreach( $stddirs as $sourcedir ) {
	$targetpath = str_replace( $path, $temppath, $sourcedir );

	vTranslate::log( "Preparing regular files in: " . $targetpath . "\n", $log );

	if ( !is_dir( $targetpath ) ) {
		mkdir( $targetpath );
	}

	$files = vTranslate::getFiles( $sourcedir );

	// Take note which files we might want to translate later on
	if ( !empty( $files ) ) {
		foreach ( $files as $file ) {
			$all_targets[] = array( 'source' => $sourcedir.'/'.$file, 'target' => $targetpath.'/'.$file );
		}
	}
}

$all_constants = array();

// Convert .php to .ini
foreach( $stddirs as $sourcedir ) {
	vTranslate::log( "Processing: " . $sourcedir . "\n", $log );

	$targetpath = str_replace( $path, $temppath, $sourcedir );

	if ( !is_dir( $targetpath ) ) {
		mkdir( $targetpath );
	}

	$files = vTranslate::getFiles( $sourcedir );

	vTranslate::log( "Translations found: " . implode( ", ", $translations_echo ) . "\n\n", $log );

	foreach ( $files as $f ) {
		$file = new SplFileObject( $sourcedir.'/'.$f );

		if ( strpos( $file->getFilename(), '.ini' ) ) {
			while ( !$file->eof() ) {
				$line = $file->fgets();

				if ( ( $line['type'] == 'empty' ) || ( $line['type'] == 'comment' ) ) {
					$content = "\n";
					if ( $line['type'] == 'comment' ) {
						$emptyline = 0;

						$content = "; " . $line['content'] . "\n";
					} else {
						if ( $emptyline ) {
							continue;
						} else {
							$emptyline = 1;
						}
					}

					foreach ( $translations as $translation ) {
						$translatef[$translation]->fwrite( vTranslate::safeEncode( $content ) );
					}
				} elseif ( $line['type'] == 'ham' ) {
					$all_constants[] = $line['content']['name'];

					foreach ( $translations as $translation ) {
						if ( $translation != $rootlang ) {
							if ( !isset( $translator[$translation][$line['content']['name']] ) ) {
								continue;
							}

							if ( $translator[$translation][$line['content']['name']] == $line['content']['content'] ) {
								continue;
							}
						}

						$content = $line['content']['name'].'='.'"'.html_entity_decode( $translator[$translation][$line['content']['name']]).'"' . "\n";

						if ( !empty( $content ) ) {
							$translatef[$translation]->fwrite( vTranslate::safeEncode( $content ) );
						}
					}
				}
			}
		} elseif ( strpos( $file->getFilename(), '.php' ) ) {
			
		}
	}

	$translator = array();
	$translatef = array();
	foreach ( $translations as $translation ) {
		if ( !isset( $translator[$translation] ) ) {
			$translator[$translation] = array();
		}

		$file = new SplFileObject( $sourcedir.'/'.$translation.'.php' );

		while ( !$file->eof() ) {
			$line = vTranslate::parseLine( $file->fgets() );

			if ( $line['type'] == 'ham' ) {
				$translator[$translation][$line['content']['name']] = $line['content']['content'];
			}
		}

		$inifile = $targetpath.'/'.vTranslate::ISO3166_2ify( $translation ).'.ini';

		if ( file_exists( $inifile ) ) {
			unlink( $inifile );
		}

		$translatef[$translation] = new SplFileObject( $inifile, 'w' );
	}

	$file = new SplFileObject( $sourcedir.'/'.$rootlang.'.php' );

	$emptyline = 1;
	while ( !$file->eof() ) {
		$line = $file->fgets();

		if ( ( $line['type'] == 'empty' ) || ( $line['type'] == 'comment' ) ) {
			$content = "\n";
			if ( $line['type'] == 'comment' ) {
				$emptyline = 0;

				$content = "; " . $line['content'] . "\n";
			} else {
				if ( $emptyline ) {
					continue;
				} else {
					$emptyline = 1;
				}
			}

			foreach ( $translations as $translation ) {
				$translatef[$translation]->fwrite( vTranslate::safeEncode( $content ) );
			}
		} elseif ( $line['type'] == 'ham' ) {
			$all_constants[] = $line['content']['name'];

			foreach ( $translations as $translation ) {
				if ( $translation != $rootlang ) {
					if ( !isset( $translator[$translation][$line['content']['name']] ) ) {
						continue;
					}

					if ( $translator[$translation][$line['content']['name']] == $line['content']['content'] ) {
						continue;
					}
				}

				$content = $line['content']['name'].'='.'"'.html_entity_decode( $translator[$translation][$line['content']['name']]).'"' . "\n";

				if ( !empty( $content ) ) {
					$translatef[$translation]->fwrite( vTranslate::safeEncode( $content ) );
				}
			}
		}
	}

	vTranslate::log( "Translation done.", $log );

	vTranslate::log( "\n\n", $log );
}

vTranslate::log( "Now correcting JText calls that start with an underscore" . "\n\n", $log );

// custom str_replace that doesn't double replace
function stro_replace($search, $replace, $subject)
{
    return strtr( $subject, array_combine($search, $replace) );
}

foreach ( $all_targets as $file ) {
	$source = new SplFileObject( $file['source'] );
	$target = new SplFileObject( $file['target'], 'w' );

	vTranslate::log( $file['target'] . "\n", $log );

	$count = 0;
	$countx = 0;
	$counto = 0;
	$found = false;
	while ( !$source->eof() ) {
		$lfound = false;

		$line = $source->fgets();

		// This is very expensive and takes a while
		// But looks cool in the readout :D
		foreach ( $all_constants as $k ) {
			if ( strpos( $line, $k ) !== false ) {
				$counto++;
				$found = true;
				$lfound = true;
			}
		}

		if ( $lfound ) {
			$countx++;
		}

		$line = stro_replace( "JText::_('_", "JText::_('", $line );

		$target->fwrite( $line );

		$count++;

		// Log every 100 lines whether we found sth
		if ( ( $count%100 ) == 0 ) {
			if ( $found ) {
				vTranslate::log( "+", $log );
			} else {
				vTranslate::log( "-", $log );
			}

			$found = false;
		}
	}

	vTranslate::log( "\n", $log );

	// Give a readout for this file
	if ( $countx ) {
		vTranslate::log( $count . " lines checked\n", $log );
		vTranslate::log( $countx . " lines updated\n", $log );
		vTranslate::log( "Replaced " . $counto . " constants\n\n", $log );
	} else {
		vTranslate::log( $count . " lines checked\n", $log );
		vTranslate::log( "Nothing to update, deleting copy\n\n", $log );

		unlink( $file['target'] );
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
