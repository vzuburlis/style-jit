<?php

namespace StyleJit;

class StyleJit
{
    static public $path = "";
    static public $refresh = false;
    static private $style = "";
    static private $classMap = [];
    static private $mediaMap = [];

    static function fileName()
    {
        $fileName = $_SERVER['REQUEST_URI'].'.css';

        if(self::$refresh === false) {
            return $fileName;
        }

        // render the stylesheet on exit of the script
        register_shutdown_function(function(){
          $output = ob_get_contents();
          $style = renderStyle($output);
          file_put_contents(self::$path.'/'.$fileName, $style);
        });

        return $fileName;
    }

    static function renderStyle($markup)
    {
        // return the css
        $output_array = [];
        preg_match_all('/class="[^"]+"/', $markup, $output_array);
        foreach($output_array[0] as $classQuote) {
            $classList = explode(' ', substr($classQuote, 7, -1));
            foreach($classList as $className) {
                self::addClass($className);
            }
        }
        return self::$style;
    }

    static function addClass($name)
    {
        if(isset(self::$classMap[$name])) {
          return;
        }

        $string = $name;
        $attribute = "";
        $value = "";
        $pseudo = "";
        $media = "";
        $classValue = "";
        // name should have this format
        // attribute:value[:pseudo-elements][@media]
        $pos = strpos($string, ':');
        if($pos === false) {
          // this is a semantic name
          // TODO get the class from a css resource
        } else {
          $attribute = substr($string, 0, $pos);
          $value = $string = substr($string, $pos+1);
        }
        // check if follows pseudo-elements, keep first ':'
        $pos = strpos($string, ':');
        if($pos !== false) {
          $value = substr($value, 0, $pos);
          $pseudo = $string = substr($string, $pos);
        }
        // check if follows a media key
        $pos = strpos($string, '@');
        if($pos !== false) {
          $value = substr($value, 0, $pos);
          $pseudo = $string = substr($string, $pos);
        }

        $attribute = self::findBestAttribute($attribute);

        $classValue = $attribute.':'.$value;
        
        $definition = '.'.strtr($name, [':'=>'\\:']).$pseudo.'{'.$classValue.'}';
        if($media !== "") {
          // TODO seperate media clausures to reduce output size
          $definition = '@media '.(self::$mediaMap[$media]??$media).$definition.'}';
        }

        self::$style .= $definition;
        self::$classMap[$name] = $classValue;
    }

    static function findBestAttribute($attr)
    {
      // TODO find the best fit attribute from a list
      return $attr;
    }
}
