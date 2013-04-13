<?php

include 'common.php';

$count = versionGet();

// And since we knoweth that we shall be incrementing the counter, we shall do so

$count++;

// And changeth be the class file
fsnr( __DIR__.'/../../newsrc/code/components/com_acctexp/acctexp.class.php', "define( '_AEC_REVISION', '", $count );
