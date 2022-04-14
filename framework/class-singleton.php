<?php

namespace WPImgix;

class Singleton
{
    private static $instance;

    private function __construct() {}

    public static function instance(): self
    {
        if ( !isset($instance) )
            self::$instance = new self;
        return self::$instance;
    }
}