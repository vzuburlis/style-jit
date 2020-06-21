# Style JIT
A php parser to create stylesheet files just in time

## Setup
```
StyleJit\StyleJist
```

## How to use
```
StyleJit\StyleJit::$path = "assets"; // set the path to save the stylesheets
StyleJit\StyleJit::$refresh = true;  // comment this on production

...
$cssFile = StyleJit\StyleJit::fileName();
echo '<link href="assets/' . $cssFile . '" type="stylesheet">';
```
