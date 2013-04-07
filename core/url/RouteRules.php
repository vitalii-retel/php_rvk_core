<?php

namespace core\url;

class RouteRules extends \core\common\ArrayList {

    private $url;
    private $routeUrl;

    public function addRouteRules(RouteRules $routeRules) {
        $this->addItems($routeRules->getItems());
    }

    public function getControllerAndMethod() {
        $routeSegments = $this->getRouteUrl()->getSegments();
        $controller = count($routeSegments) > 0 ? $routeSegments[0] : null;
        $method = count($routeSegments) >= 1 ? $routeSegments[1] : null;
        return array($controller, $method);
    }

    public function detectUrlString() {
        $s = new \core\common\ArrayClass($_SERVER);
        if ($s->PATH_INFO) {
            $url = $s->PATH_INFO;
        } else if ($s->REQUEST_URI) {
            $url = $s->REQUEST_URI;
        } else if ($s->REDIRECT_URL) {
            $url = $s->REDIRECT_URL;
        } else {
            $url = str_ireplace('index.php', '', $s->PHP_SELF);
        }
        return $url;
    }

    public function getUrl() {
        if ($this->url == null) {
            $this->url = new Url($this->detectUrlString());
        }
        return $this->url;
    }

    public function getRouteUrl() {
        if ($this->routeUrl == null) {
            $this->routeUrl = new Url($this->applyRules($this->getUrl()->getUrl()));
        }
        return $this->routeUrl;
    }

    public function applyRules($urlString) {
        foreach ($this->getItems() as $ruleItem) {
            if (array_key_exists('url', $ruleItem) && array_key_exists('rUrl', $ruleItem)) {
                $r = preg_filter('/' . $ruleItem['url'] . '/i', $ruleItem['rUrl'], $urlString);
                if ($r != null) {
                    return $r;
                }
            } else {
                trigger_error('Bad route rule. ' . var_export($ruleItem, true), E_USER_WARNING);
            }
        }
        return $urlString;
    }

}
