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

if ( file_exists( $temppath ) ) {
	vTranslate::rrmdir( $temppath );
}

mkdir( $temppath );

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
		echo "Root Language found: " . $rootlang . " (=>" . vTranslate::ISO3166_2ify( $rootlang ) . ")" . "\n";
	}

	$translations = array();
	foreach ( $files as $file ) {
		$lang = str_replace( '.php', '', $file );

		$translations[] = $lang;

		if ( $file != $rootlang.'.php' ) {
			$translations_echo[] = $lang . " (=>" . vTranslate::ISO3166_2ify( $lang ) . ")" ;
		}
	}

	echo "Translations found: " . implode( ", ", $translations_echo ) . "\n\n";

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
		$line = vTranslate::parseLine( $file->fgets() );

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
				$translatef[$translation]->fwrite( utf8_encode( $content ) );
			}
		} elseif ( $line['type'] == 'ham' ) {
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
					$translatef[$translation]->fwrite( utf8_encode( $content ) );
				}
			}
		}
	}
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

	function parseLine( $line )
	{
		$line = trim( $line );

		$comments = array( '/**', '* ', '//' );

		$comment = '';
		foreach ( $comments as $c ) {
			if ( strpos( $line, $c ) === 0 ) {
				$comment = trim( str_replace( $c, '', $line ) );
			}
		}

		$return = array();

		$return['type']		= 'empty';

		if ( $comment == 'Dont allow direct linking' ) {
			$comment = "";
		}

		if ( empty( $line ) ) {

		} elseif ( !empty( $comment ) ) {
			$comment = str_replace( '2010', '2011', $comment );
			$comment = str_replace( '.php', '.ini', $comment );

			$return['type']		= 'comment';
			$return['content']	= $comment;
		} elseif ( strpos( $line, 'define' ) === 0 ) {
			$return['type'] = 'ham';

			if ( strpos( $line, '\', "' ) && strpos( $line, '");' ) ) {
				$line = str_replace( '\', "', '\', \'', $line );
				$line = str_replace( '");', '\');', $line );
			}

			$defstart	= strpos( $line, '\'' );
			$defend		= strpos( $line, '\'', $defstart+1 );

			$name = substr( $line, $defstart+1, $defend-$defstart-1 );
			
			$constart	= strpos( $line, '\'', $defend+1 );
			$conend		= strrpos( $line, '\'' );

			$content = substr( $line, $constart+1, $conend-$constart-1 );

			$return['content'] = array( 'name' => $name, 'content' => $content );
		}

		return $return;
	}


	function ISO3166_2ify( $lang )
	{
		$ll = explode( '-', $lang );

		$lang_codes = array( 	'brazilian_portoguese' => 'pt-BR',
								'brazilian_portuguese' => 'pt-BR',
								'czech' => 'cz-CZ',
								'danish' => 'da-da',
								'dutch' => 'nl-nl',
								'english' => 'en-GB',
								'french' => 'fr-fr',
								'german' => 'de-DE',
								'germani' => 'de-DE-informal',
								'germanf' => 'de-DE-formal',
								'greek' => 'el-GR',
								'italian' => 'it-IT',
								'japanese' => 'ja-JP',
								'russian' => 'ru-RU',
								'simplified_chinese' => 'zh-CN',
								'spanish' => 'es-ES',
								'swedish' => 'sv-SE',
								'arabic' => 'ar-DZ',
								'belarusian' => 'be-BY',
								'bulgarian' => 'bg-BG',
								'bengali' => 'bn-IN',
								'bosnian' => 'bs-BA',
								'esperanto' => 'eo-XX',
								'basque' => 'eu-ES',
								'persian' => 'fa-IR',
								'finnish' => 'fi-FI',
								'hebrew' => 'he-IL',
								'croatian' => 'hr-HR',
								'hungarian' => 'hu-HU',
								'korean' => 'ko-KR',
								'lao' => 'lo-LA',
								'lithuanian' => 'lt-LT',
								'latvian' => 'lv-LV',
								'macedonian' => 'mk-MK',
								'norwegian' => 'nb-NO',
								'polish' => 'pl-PL',
								'portoguese' => 'pt-PT',
								'romanian' => 'ro-RO',
								'sindhi' => 'sd-PK',
								'sinhala' => 'si-LK',
								'slovak' => 'sk-SK',
								'shqip' => 'sq-AL',
								'montenegrin' => 'sr-ME',
								'serbian' => 'sr-RS',
								'syriac' => 'sy-IQ',
								'tamil' => 'ta-LK',
								'thai' => 'th-TH',
								'turkish' => 'tr-TR',
								'ukrainian' => 'uk-UA',
								'vietnamese' => 'vi-VN',
								'traditional_chinese' => 'zh-TW'
								);

		if ( isset( $lang_codes[$ll[0]] ) ) {
			return $lang_codes[$ll[0]];
		} else {
			return 'error-'.$ll[0];
		}
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
}
?>
