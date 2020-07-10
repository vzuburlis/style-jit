# Style JIT
A php parser to create stylesheet files just in time


## How to use
```php
<?php

use StyleJit\StyleJit;

include_once 'vendor/autoload.php'; // autoload resources from composer

$_SERVER['REQUEST_URI'] = 'fixtures/example.php';

StyleJit::$path = __DIR__.'/assets'; // set the path to save the stylesheets
StyleJit::$refresh = true;  // comment this on production

// ...
echo '<link href="assets/' . StyleJit::fileName() . '" type="stylesheet">';
```

## Run the tests
```
./vendor/phpunit/phpunit/phpunit tests/StyleJitTest.php
```

## Run php PHP-CS-Fixer
```
php-cs-fixer fix
```
