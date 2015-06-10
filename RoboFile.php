<?php

require_once __DIR__ . '/vendor/autoload.php';

use Symfony\Component\Finder\Finder;

class RoboFile extends Robo\Tasks
{
	public function release()
	{
		$this->makeBundle();
	}

	public function makeBundle()
	{
		/*$pharTask = $this->taskPackPhar('package/aec.zip')
			->compress();

		$finder = Finder::create()
			->ignoreVCS(true)
			->in('newsrc/code');

		foreach ($finder as $file) {
			/** var SplFileInfo $file  */
			/*$pharTask->addFile('src/'.$file->getRelativePathname(), $file->getRealPath());
		}*/

		if ( is_dir(__DIR__ . '/tmp') ) {
			$this->_deleteDir('tmp');
		}

		$this->taskExec('mkdir tmp')->run();

		$this->_mirrorDir('newsrc/code', 'tmp');

		foreach ( glob(__DIR__ . '/tmp/modules/*') as $file ) {
			echo $file . "\n";
		}

		$this->taskExec('zip -r aec.zip .')->dir('tmp')->printed(false)->run();

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

	public function revisionGet()
	{
		// And HE told me the count of the git
		$count = trim( $this->taskExec('git rev-list HEAD --count')->run() );

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
