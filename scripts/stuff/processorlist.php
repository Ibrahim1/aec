<?php

foreach ( glob( __DIR__.'/../../newsrc/code/components/com_acctexp/processors'.'/*', GLOB_ONLYDIR ) as $path ) {
	echo ucfirst(basename($path)).', ';
}
