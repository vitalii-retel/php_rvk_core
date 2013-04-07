<?php

namespace core\url;

class Url {

    private $url;
    private $urlArray;

    const TRIM_CHARACTERS = '/ ';

    function __construct($url = '') {
        $this->setUrl($url);
    }

    public function setUrl($url) {
        $this->url = trim($url, self::TRIM_CHARACTERS);
        $this->urlArray = self::explodeUrl($this->url, true);
    }

    public function getUrl() {
        return $this->url;
    }

    public function setUrlArray(array $urlArray) {
        $this->urlArray = $urlArray;
        $this->url = self::implodeUrl($this->urlArray);
    }

    public function getUrlArray() {
        return $this->urlArray;
    }

    public function getSegment($position) {
        return array_key_exists($position, $this->urlArray) ? $this->urlArray[$position] : null;
    }

    public function getSegments() {
        return $this->getUrlArray();
    }

    static public function explodeUrl($url, $noTrim = false) {
        $urlP = $noTrim ? $url : trim($url, self::TRIM_CHARACTERS);
        return empty($urlP) ? array() : explode('/', $urlP);
    }

    static public function implodeUrl(array $url) {
        return implode('/', $url);
    }

}
