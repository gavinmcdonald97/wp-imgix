<?php

namespace WPImgix;

use WPImgix\Framework\Singleton;

class Plugin extends Singleton
{
    protected $api;
    protected $settings = [];

    protected function __construct()
    {
        $this->setupSettings();
        if (!$this->settings['enable_imgix'] || empty($this->settings['imgix_domain'])) {
            return;
        }
        $this->api = Imgix::instance($this->settings);
        add_action('admin_enqueue_scripts', array($this, 'admin_assets'));
        add_filter('wp_get_attachment_image_src', array($this, 'convertImageURL'), 10, 3);
        add_filter('wp_calculate_image_srcset', array($this, 'convertImageSrcSet'), 10, 5);
        add_filter('wp_get_attachment_image_attributes', array($this, 'setImageSizesAttribute'));
    }

    protected function setupSettings()
    {
        $this->settings = [
            'enable_imgix' => false,
            'imgix_domain' => '',
            'sign_key' => '',
            'use_signed_urls' => true,
            'image_sizes' => [],
            'default_params' => [
                'auto' => 'format,compress,enhance'
            ]
        ];

        if (function_exists('get_option')) {
            $this->settings['enable_imgix'] = get_option('wp-imgix-enable-imgix');
            $this->settings['imgix_domain'] = get_option('wp-imgix-domain');
            $this->settings['sign_key'] = get_option('wp-imgix-sign-key');
        }

        if (function_exists('wp_get_registered_image_subsizes')) {
            $registered_sizes = wp_get_registered_image_subsizes();

            $this->settings['image_sizes'][$registered_sizes['medium']['width']] = array(
                'descriptor' => 'w',
                'value' => $registered_sizes['medium']['width'],
                'height' => $registered_sizes['medium']['height']
            );

            $this->settings['image_sizes'][$registered_sizes['medium_large']['width']] = array(
                'descriptor' => 'w',
                'value' => $registered_sizes['medium_large']['width'],
                'height' => $registered_sizes['medium_large']['height']
            );

            $this->settings['image_sizes'][$registered_sizes['large']['width']] = array(
                'descriptor' => 'w',
                'value' => $registered_sizes['large']['width'],
                'height' => $registered_sizes['large']['height']
            );
        }
    }

    public static function activate()
    {
    }

    public function admin_assets(): void
    {
        wp_enqueue_style(
            'wp-imgix-plugin-settings-page',
            WPIMGIX_PLUGIN_URL . 'assets/css/plugin-settings-page.css',
            array(),
            WPIMGIX_PLUGIN_VERSION
        );
    }

    public function convertImageURL($image, $attachment_id, $size)
    {
        if (empty($image) || empty($image[0])) {
            return $image;
        }
        // Check if imgix already applied to URL
        if (strpos($image[0], $this->settings['imgix_domain']) !== false) {
            return $image;
        }
        // Always pass full size image to imgix
        $source = $this->stripSizeFromImageURL($image[0]);
        $width = $image[1];
        $height = $image[2];
        $image[0] = $this->api->getURL($source, ['w' => $width, 'h' => $height]);
        return $image;
    }

    public function convertImageSrcSet($sizes, $size_array, $source, $image_meta, $attachment_id): array
    {
        if (empty($sizes)) {
            return [];
        }

        $image_source_url = wp_get_attachment_image_url($attachment_id, 'full');

        // Remove Imgix domain and params from source URL
        if (strpos($image_source_url, trailingslashit($this->settings['imgix_domain'])) !== false) {
            $image_source_url = explode(
                trailingslashit($this->settings['imgix_domain']),
                urldecode($image_source_url)
            )[1];
            $image_source_url = explode('?', $image_source_url)[0];
        }

        $sizes = $this->settings['image_sizes'];

        foreach ($sizes as $width => $size) {
            $params = [];
            if ($width > 0) {
                $params['w'] = $width;
            }
            if ($size['height'] > 0) {
                $params['h'] = $size['height'];
            }
            $sizes[$width]['url'] = $this->api->getURL($image_source_url, $params);
        }

        return $sizes;
    }

    public function setImageSizesAttribute($attributes): array
    {
        $attributes['sizes'] = '100w';
        return $attributes;
    }

    public function stripSizeFromImageURL(string $url = ''): string
    {
        return preg_replace('/-\d+[Xx]\d+\./', '.', $url);
    }
}