<?php

include 'common.php';

$count = versionGet();

// And since we knoweth that we shall be incrementing the counter, we shall do so

$count++;

// And changeth be the class file
fsnr( __DIR__.'/../../newsrc/code/components/com_acctexp/acctexp.class.php', "define( '_AEC_REVISION', '", $count );

shell_exec( 'git add newsrc/code/components/com_acctexp/acctexp.class.php' );

// And the xml file
fsnr( __DIR__.'/../build/build.xml', '<property name="git.lastrevision" value="', $count );

shell_exec( 'git add scripts/build/build.xml' );
