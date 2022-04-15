<?php

namespace WPImgix;

abstract class Singleton
{
    protected function __construct(...$args) {}

    final public static function instance(...$args)
    {
        static $instances = array();

        $calledClass = get_called_class();

        if ( !isset($instances[$calledClass]) ) {
            $instances[$calledClass] = new $calledClass(...$args);
        }

        return $instances[$calledClass];
    }

    final private function __clone() {}
}