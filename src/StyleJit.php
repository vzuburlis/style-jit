<?php

declare(strict_types=1);

namespace StyleJit;

class StyleJit
{
    public static $path = '';

    public static $refresh = false;

    private static $style = '';

    private static $classMap = [];

    private static $mediaMap = [];

    private static $cssProperties = [];

    public static function setOptions($args): void
    {
        if (isset($args['class'])) {
            self::$classMap = $args['class'];
        }
        if (isset($args['media'])) {
            self::$mediaMap = $args['media'];
        }
        if (isset($args['path'])) {
            self::$path = $args['path'];
        }
        if (isset($args['refresh'])) {
            self::$refresh = $args['refresh'];
        }
    }

    public static function fileName(): string
    {
        $fileName = $_SERVER['REQUEST_URI'].'.css';

        if (self::$refresh === false) {
            return $fileName;
        }

        // render the stylesheet on exit of the script
        register_shutdown_function(static function () use ($fileName) {
            $output = ob_get_contents();
            $style = self::renderStyle($output);
            file_put_contents(self::$path.'/'.$fileName, $style);
        });

        return $fileName;
    }

    public static function renderStyle(string $markup): string
    {
        // return the css
        self::$style = '';
        self::$cssProperties = include __DIR__.'/data/css-properties.php';

        $classQuotes = self::findClassQuotes($markup);
        foreach ($classQuotes as $classQuote) {
            $classList = explode(' ', substr($classQuote, 7, -1));
            foreach ($classList as $className) {
                self::addClass($className);
            }
        }

        return self::$style;
    }

    public static function addClass(string $name): void
    {
        if (isset(self::$classMap[$name])) {
            return;
        }

        $string = $name;
        $attribute = '';
        $value = '';
        $pseudo = '';
        $media = '';
        $classValue = '';
        // name should have this format
        // attribute:value[:pseudo-elements][@media]
        $pos = strpos($string, ':');
        if ($pos === false) {
            // this is a semantic name
          // TODO get the class from a css resource
        } else {
            $attribute = substr($string, 0, $pos);
            $value = $string = substr($string, $pos + 1);
        }
        // check if follows pseudo-elements, keep first ':'
        $pos = strpos($string, ':');
        if ($pos !== false) {
            $value = substr($value, 0, $pos);
            $pseudo = $string = substr($string, $pos);
        }
        // check if follows a media key
        $pos = strpos($string, '@');
        if ($pos !== false) {
            $value = substr($value, 0, $pos);
            $pseudo = $string = substr($string, $pos);
        }

        $attribute = self::findBestAttribute($attribute);
        $value = self::readAttributeValue($value);

        $classValue = $attribute.':'.$value;

        $definition = '.'.strtr($name, [':' => '\\:']).$pseudo.'{'.$classValue.'}';
        if ($media !== '') {
            // TODO separate media closures to reduce output size
            $definition = '@media '.(self::$mediaMap[$media] ?? $media).$definition.'}';
        }

        self::$style .= $definition;
        self::$classMap[$name] = $classValue;
    }

    public static function findBestAttribute(string $attr)
    {
        // find the best fit attribute from a list
        if (isset(self::$cssProperties[$attr])) {
            return $attr;
        }

        $output_array = [];
        $regex = '';
        $length = strlen($attr);
        for ($i = 0; $i < $length; ++$i) {
            if ($attr[$i] === '-' && $i < $length - 1) {
                // after dash expect that the word starts with next character
                $regex .= '[-]';
                ++$i;
            }
            $regex .= '['.$attr[$i].'](.*)';
        }

        foreach (self::$cssProperties as $property) {
            if ($attr === $property) {
                return $property;
            }
            preg_match('/'.$regex.'/', $property, $output_array);
            if (
                !empty($output_array) && $attr[0] === $property[0] &&
                substr_count($attr, '-') === substr_count($property, '-')
            ) {
                return $property;
            }
        }

        return $attr;
    }

    public static function readAttributeValue(string $value): string
    {
        // add the spaces and commas in attributes
        $value = strtr($value, ['_' => ' ', '\\,' => ',']);

        if (strpos($value, '-') === 0) {
            $value = 'var('.$value.')';
        }

        return $value;
    }

    public static function findClassQuotes(string $markup): array
    {
        $array1 = [];
        $array2 = [];
        preg_match_all('/class="[^"]+"/', $markup, $array1);
        preg_match_all("/class='[^']+'/", $markup, $array2);

        return array_merge($array1[0], $array2[0]);
    }
}
