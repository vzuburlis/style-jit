# Style JIT
A php parser to create stylesheet files just in time


## How to use
```
StyleJit\StyleJit::$path = "assets"; // set the path to save the stylesheets
StyleJit\StyleJit::$refresh = true;  // comment this on production

...
$cssFile = StyleJit\StyleJit::fileName();
echo '<link href="assets/' . $cssFile . '" type="stylesheet">';
```

## Run the tests
```
./vendor/phpunit/phpunit/phpunit tests/StyleJitTest.php
```

## Run php PHP-CS-Fixer
```
php-cs-fixer fix
```
