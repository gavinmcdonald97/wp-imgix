<?php

namespace WPImgix;

use Imgix\UrlBuilder;

class Imgix extends Singleton
{
    protected $settings, $urlBuilder;

    protected function __construct($settings = [])
    {
        $this->settings = $settings;
        $this->urlBuilder = new UrlBuilder($this->settings['imgix_domain']);
        $this->urlBuilder->setIncludeLibraryParam(false);

        if ( $this->settings['use_signed_urls'] && !empty($this->settings['sign_key']) )
            $this->urlBuilder->setSignKey($this->settings['sign_key']);
    }

    public function getURL(string $url = '', array $params = []): string
    {
        if ( empty($url) ) return '';
        $params = array_merge($this->settings['default_params'], $params);
        return $this->urlBuilder->createURL($url, $params);
    }

    public function getSrcSet(string $url = '', array $params = [], array $options = []): string
    {
        if ( empty($url) ) return '';
        $params = array_merge($this->settings['default_params'], $params);
        $options = array_merge($this->settings['default_srcset_options'], $options);
        return $this->urlBuilder->createSrcSet($url, $params, $options);
    }
}