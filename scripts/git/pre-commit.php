<?php

include 'common.php';

$count = versionGet();

// And changeth be the class file
fsnr( __DIR__.'/../../newsrc/code/components/com_acctexp/acctexp.class.php', "define( '_AEC_REVISION', '", $count );
