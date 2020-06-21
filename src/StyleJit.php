<?php

namespace StyleJit;

class StyleJit
{
    public $path = "";
    public $refresh = false;

    function fileName()
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

    function renderStyle($markup)
    {
       // return the css
       return "";
    }
}
