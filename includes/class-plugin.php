<?php

namespace WPImgix;

class Plugin extends Singleton
{
    protected $api;
    protected $settings = [];

    protected function __construct()
    {
        $this->api = Imgix::instance();
        add_action('admin_enqueue_scripts', array($this, 'admin_assets'));
        add_filter('wp_get_attachment_url', array($this, 'convertImageURL'));
        add_filter('wp_calculate_image_srcset', array($this, 'convertImageSrcSet'));
    }

    public static function activate() {}

    public function admin_assets(): void
    {
        wp_enqueue_style('wp-imgix-plugin-settings-page', WPIMGIX_PLUGIN_URL . 'assets/css/plugin-settings-page.css', array(), WPIMGIX_PLUGIN_VERSION);
    }

    public function convertImageURL($url): string
    {
        if ( empty($url) ) return '';
        return $this->api->getURL($url, []);
    }

    public function convertImageSrcSet($sources): array
    {
        if ( empty($sources) ) return [];

        $url = $sources[0]['original_url'];

        $options = [
            'widths' => array_column($sources, 'width')
        ];

        return $this->api->getSrcSet($url, [], $options);
    }


}