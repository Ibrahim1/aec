<?php

error_reporting(E_ALL);

$path = __DIR__ . "/../../newsrc/code/components/com_acctexp/micro_integration";

$list = array();
$types = array();

define('_JEXEC', true);

foreach ( glob($path . '/*', GLOB_ONLYDIR) as $mi_path ) {
	$name= basename($mi_path);

	if ( strpos($name, '.') !== false ) {
		continue;
	}

	$file = $mi_path . '/' .$name. '.php';

	if ( !is_file($file) ) {
		continue;
	}

	include_once $file;

	$class = 'mi_' . $name;

	$mi = new $class();

	$lang = $mi_path . '/language/en-GB/en-GB.com_acctexp.mi.' .$name. '.ini';

	if ( is_file($lang) ) {
		JText::append($lang);
	}

	$info = $mi->Info();

	if ( empty($info) ) {
		echo $name. "\n";

		var_dump($mi);

		continue;
	}

	$info['handle'] = $name;

	foreach ( $info['type'] as $type ) {
		if ( !in_array($type, $types) ) {
			$types[] = $type;
		}
	}

	$list[] = $info;
}

file_put_contents(__DIR__ . '/mis.json', json_encode($list, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

$type_tree = array();
foreach ( $types as $type ) {
	$t = explode('.', $type);

	$key = 0;
	foreach ( $type_tree as $k => $node ) {
		if ( $node['handle'] == $t[0] ) {
			$key = $k;
		}
	}

	if ( !$key ) {
		$type_tree[] = array( 'handle' => $t[0], 'children' => array() );

		$key = count($type_tree);
	}

	if ( !empty($t[1]) ) {
		if ( $type_tree[$key]['handle'] == $t[0] ) {
			$type_tree[$key]['children'][] = $t[1];
		}
	}
}

file_put_contents(__DIR__ . '/mitypes.json', json_encode($type_tree, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

class JText
{
	private static $list;

	public static function append( $source )
	{
		foreach ( parse_ini_file($source) as $k => $v ) {
			self::$list[$k] = $v;
		}
	}

	public static function _( $key )
	{
		return self::$list[$key];
	}
}

class MI
{

}

class serialParamDBTable
{

}

class JFactory
{
	public function getDBO()
	{
		return new FakeDBO();
	}
}

class FakeDBO
{
	public function setQuery($v)
	{

	}

	public function getPrefix()
	{
		return '';
	}

	public function loadResult()
	{
		return false;
	}
}
