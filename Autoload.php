<?php
class Autoload
{
    public static function register($path)
    {
        if (function_exists('__autoload')) {
            spl_autoload_register('__autoload');
        }
        spl_autoload_register(function ($class) use(&$path)
        {
            set_include_path($path);
            spl_autoload($class);
        });
    }
}
