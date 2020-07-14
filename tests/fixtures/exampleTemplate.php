<?php

use StyleJit\StyleJit;

include_once __DIR__.'/../../vendor/autoload.php';

$_SERVER['REQUEST_URI'] = 'fixtures/example.php';

StyleJit::$path = __DIR__.'/assets'; // set the path to save the stylesheets
StyleJit::$refresh = true;  // comment this on production

ob_start();
?>
<html lang="en">
    <head>
        <title>Example HTML for testing StyleJit</title>
        <link href="<?= StyleJit::$path.'/'.StyleJit::fileName() ?>" type="stylesheet">
    </head>
    <body>
        <div><a class="padding:1em">Save</a></div>
        <div><a class='margin:1em'>Save</a></div>
        <div><a class="pad:1em">Save</a></div>
        <div><a class="p-lft:1em">Save</a></div>
        <div><a class="p-l:1em">Save</a></div>
        <div class="dis:grid gr-t-c:1fr_1fr">Save</div>
    </body>
</html>
