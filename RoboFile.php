<?php

require_once __DIR__ . '/vendor/autoload.php';

use Symfony\Component\Finder\Finder;

class RoboFile extends Robo\Tasks
{
	public function release()
	{
		// On master, new commits == new patch version
		if ( $this->getBranch() == 'master' ) {
			$this->taskSemVer(__DIR__ . '/.semver')->increment('patch')->run();
		}

		$this->makeBundle();

		if ( $this->getBranch() == 'master' ) {
			$this->taskExec(
				"git tag -a "
				. ((string) $this->taskSemVer(__DIR__ . '/.semver'))
				. " -m 'robo build releasing "
				. ((string) $this->taskSemVer(__DIR__ . '/.semver')) . "'"
			)->run();


			$this->taskExec("git push origin master --tags")->run();

			$this->taskGitHubRelease(
				str_replace('v', '', $this->taskSemVer(__DIR__ . '/.semver'))
			)->uri('daviddeutsch/aec')->run();

		}
	}

	public function makeBundle()
	{
		if ( is_dir(__DIR__ . '/tmp') ) {
			$this->_deleteDir('tmp');
		}

		//$this->taskExec('mkdir tmp')->run();
		$this->taskFilesystemStack()->mkdir('tmp')->run();

		$this->_mirrorDir('newsrc/code', 'tmp');

		$this->extractDir('modules');

		$this->extractDir('plugins');

		$this->taskExec(
			'zip -r aec-'
			. $this->getVersion()
			. '-' . str_replace('/', '-', $this->getBranch() )
			. '-' . $this->getHash()
			. '.zip .'
		)
			->dir('tmp')
			->printed(false)
			->run();

	}

	public function extractDir($dir)
	{
		foreach ( glob(__DIR__ . '/tmp/' . $dir  . '/*') as $file ) {
			$this->taskFilesystemStack()->rename(
				$file,
				str_replace('/' . $dir, '', $file)
			)->run();
		}

		$this->_deleteDir('tmp/' . $dir);
	}

	public function versionBumpClass()
	{
		$this->replaceInFile(
			__DIR__ . '/newsrc/code/components/com_acctexp/acctexp.class.php',
			"define( '_AEC_REVISION', '",
			$this->revisionGet()
		);
	}

	public function replaceInFile( $path, $search, $count )
	{
		$content = file_get_contents( $path );

		$start = strpos( $content, $search ) + strlen($search);

		$delim = substr( $search, -1, 1 );

		$length = strpos( $content, $delim, $start ) - $start;

		$old = substr( $content, $start, $length );

		if ( $old != $count ) {
			$content = str_replace(
				$search . $old . $delim,
				$search . $count . $delim,
				$content
			);

			return file_put_contents( $path, $content );
		} else {
			return null;
		}
	}

	public function getVersion()
	{
		return
			((string) $this->taskSemVer(__DIR__ . '/.semver'))
			. '-rev' . $this->revisionGet();
	}

	public function getBranch()
	{
		return trim(
			(string) $this->taskExec('git rev-parse --abbrev-ref HEAD')->run()->getMessage()
		);
	}

	public function getHash()
	{
		return trim(
			(string) $this->taskExec("git log --pretty=format:'%h' -n 1")->run()->getMessage()
		);
	}

	public function revisionGet()
	{
		// And HE told me the count of the git
		$count = trim( (string) $this->taskExec('git rev-list HEAD --count')->run()->getMessage() );

		// And on the thirteenth day of the fourth month of the year two thousand and thirteen,
		// The hardware counter stood at six thousand one hundred and two,
		// Yet THE COMPUTER spoke of four thousand eight hundred and ninety eight,
		// Thus it was deemed that the difference shall be one thousand two hundred and four
		// One Thousand two hundred and four shall be the number, no more, no less
		// Not one thousand two hundred and three, nor one thousand two hundred and five

		$count += 1204;

		return $count;
	}
}
