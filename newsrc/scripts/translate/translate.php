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
		echo "Root Language found: " . $rootlang . " (=>" . vTranslate::ISO3166_2ify( $rootlang ) . ")" . "\n";
	}

	$translations = array();
	foreach ( $files as $file ) {
		if ( $file != $rootlang.'.php' ) {
			$lang = str_replace( '.php', '', $file );

			$translations[] = $lang;
			$translations_echo[] = $lang . " (=>" . vTranslate::ISO3166_2ify( $lang ) . ")" ;
		}
	}

	echo "Translations found: " . implode( ", ", $translations_echo ) . "\n";

	$translator = array();
	$file = new SplFileObject( $sourcedir.'/'.$rootlang.'.php' );

	while ( !$file->eof() ) {
		$line = vTranslate::parseLine( $file->fgets() );

		if ( $line['type'] == 'empty' ) {
			
		} elseif ( $line['type'] == 'comment' ) {
			
		}
	}

	$file = new SplFileObject( $sourcedir.'/'.$rootlang.'.php' );

	while ( !$file->eof() ) {
		$line = vTranslate::parseLine( $file->fgets() );

		if ( $line['type'] == 'empty' ) {
			
		} elseif ( $line['type'] == 'comment' ) {
			
		}
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
		if ( empty( $line ) ) {
			$return['type']		= 'empty';
		} elseif ( !empty( $comment ) ) {
			$return['type']		= 'comment';
			$return['content']	= $comment;
		} elseif ( strpos( $line, 'define' ) === 0 ) {
			$return['type'] = 'ham';
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
			return 'english';
		}
	}
}
?>
