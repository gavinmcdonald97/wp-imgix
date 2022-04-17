<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use WPImgix\Plugin;

class PluginTest extends TestCase
{
    public function testItRemovesWordpressSizeFromImageURL(): void
    {
        $plugin = Plugin::instance();
        $expected = 'https://gavdev.com/wp-content/uploads/2022/04/pexels-layla-yehia-3849373-1.jpg';
        $actual = $plugin->stripSizeFromImageURL(
            'https://gavdev.com/wp-content/uploads/2022/04/pexels-layla-yehia-3849373-1-1024x768.jpg'
        );
        $this->assertEquals($expected, $actual);
    }
}