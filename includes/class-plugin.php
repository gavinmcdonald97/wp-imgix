<?php

namespace WPImgix;

class Plugin extends Singleton
{
    protected $api;
    protected $settings = [];

    protected function __construct()
    {
        $this->setupSettings();
        $this->api = Imgix::instance($this->settings);
        add_action('admin_enqueue_scripts', array($this, 'admin_assets'));
        //add_filter('wp_get_attachment_url', array($this, 'convertImageURL'));
        add_filter('wp_get_attachment_image_src', array($this, 'convertImageURL'), 10, 3);
        add_filter('wp_calculate_image_srcset', array($this, 'convertImageSrcSet'), 10, 3);
    }

    protected function setupSettings()
    {
        $this->settings = [
            'enable_imgix' => get_option('wp-imgix-enable-imgix'),
            'imgix_domain' => get_option('wp-imgix-domain'),
            'sign_key' => get_option('wp-imgix-sign-key'),
            'use_signed_urls' => true,
            'default_params' => [
                'auto' => 'format,compress,enhance'
            ]
        ];
    }

    public static function activate() {}

    public function admin_assets(): void
    {
        wp_enqueue_style('wp-imgix-plugin-settings-page', WPIMGIX_PLUGIN_URL . 'assets/css/plugin-settings-page.css', array(), WPIMGIX_PLUGIN_VERSION);
    }

    public function convertImageURL($image, $attachment_id, $size)
    {
        if ( empty($image) || empty($image[0]) ) return $image;
        $source = $image[0];
        $width = $image[1];
        $height = $image[2];
        $image[0] = $this->api->getURL($source, ['w' => $width, 'h' => $height]);
        return $image;
    }

//    public function convertImageURL($url): string
//    {
//        if ( empty($url) ) return '';
//
//        return $this->api->getURL($url, []);
//    }

    public function convertImageSrcSet($sizes, $size_array, $source): array
    {
        if ( empty($sizes) ) return [];

        // Remove Imgix domain and params from source URL
        if ( strpos($source, trailingslashit($this->settings['imgix_domain'])) !== false ) {
            $source = explode(trailingslashit($this->settings['imgix_domain']), urldecode($source))[1];
            $source = explode('?', $source)[0];
        }

        $registered_sizes = wp_get_registered_image_subsizes();

        $sizes = [];

        $sizes[$registered_sizes['medium']['width']] = array(
            'descriptor' => 'w',
            'value' => $registered_sizes['medium']['width'],
            'height' => $registered_sizes['medium']['height']
        );

        $sizes[$registered_sizes['medium_large']['width']] = array(
            'descriptor' => 'w',
            'value' => $registered_sizes['medium_large']['width'],
            'height' => $registered_sizes['medium_large']['height']
        );

        $sizes[$registered_sizes['large']['width']] = array(
            'descriptor' => 'w',
            'value' => $registered_sizes['large']['width'],
            'height' => $registered_sizes['large']['height']
        );

        foreach ( $sizes as $width => $size ) {
            $params = [];
            if ( $width > 0 )
                $params['w'] = $width;
            if ( $size['height'] > 0 )
                $params['h'] = $size['height'];
            $sizes[$width]['url'] = $this->api->getURL($source, $params);
        }

        return $sizes;
    }


}