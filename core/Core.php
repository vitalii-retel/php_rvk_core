<?php

namespace core;

class Core {

    const PRODUCTION = 0;
    const DEVELOPMENT = 1;
    const TESTING = 2;

    private $env;
    private $errorHandler;
    private $exceptionHandler;
    private $eventManager;
    private $console;
    private $config;
    private $routeRules;

    private $mode = self::DEVELOPMENT;

    function __construct() {
        $this->getConsole()->addMessage(T::CONSOLE_CORE_CONSTRUCT);
    }

    public function process() {
        if ($this->isDevelopment()) {
            $this->console->addMessage(T::CONSOLE_ROUTE_RULES_URL . "\"{$this->getRouteRules()->getUrl()->getUrl()}\"");
            $this->console->addMessage(T::CONSOLE_ROUTE_RULES_RURL . "\"{$this->getRouteRules()->getRouteUrl()->getUrl()}\"");
        }

        list($controllerName, $methodName) = $this->getRouteRules()->getControllerAndMethod();
        if ($controllerName == null || !class_exists($controllerName)) {
            trigger_error(sprintf(T::ERROR_CORE_CONTROLLER_CLASS_NOT_EXISTS, $controllerName), E_USER_ERROR);
        }
        $controller = new $controllerName($this);
        if ($methodName == null || !method_exists($controller, $methodName)) {
            trigger_error(sprintf(T::ERROR_CORE_CONTROLLER_METHOD_NOT_EXISTS, $methodName, $controllerName), E_USER_ERROR);
        }
        EventManager::getInstance()->trigger(EventManager::EVENT_BEFORE_CONTROLLER_METHOD, array('core' => $this));
        $controller->$methodName(count($this->getRouteRules()->getRouteUrl()->getSegments()) > 2 ? array_slice($this->getRouteRules()->getRouteUrl()->getSegments(), 2) : array());
        EventManager::getInstance()->trigger(EventManager::EVENT_AFTER_CONTROLLER_METHOD, array('core' => $this));
    }

    public function isProduction() {
        return $this->mode === self::PRODUCTION;
    }

    public function isDevelopment() {
        return $this->mode === self::DEVELOPMENT;
    }

    public function isTesting() {
        return $this->mode === self::TESTING;
    }

    public function setMode($mode) {
        $this->mode = $this->checkMode($mode);
    }

    public function checkMode($mode) {
        if (!in_array($mode, array(self::PRODUCTION, self::DEVELOPMENT, self::TESTING)))
            $mode = self::DEVELOPMENT;
        return $mode;
    }

    public function setConfig($config) {
        $this->config = $config;
    }

    public function getConfig() {
        if ($this->config == null)
            $this->setConfig(new Config());
        return $this->config;
    }

    public function setConsole($console) {
        $this->console = $console;
    }

    public function getConsole() {
        if ($this->console == null)
            $this->setConsole(Console::getInstance());
        return $this->console;
    }

    public function setRouteRules($routeRules) {
        $this->routeRules = $routeRules;
    }

    public function getRouteRules() {
        if ($this->routeRules == null)
            $this->setRouteRules(new RouteRules());
        return $this->routeRules;
    }

    public function setErrorHandler($errorHandler) {
        $this->errorHandler = $errorHandler;
    }

    public function getErrorHandler() {
        if ($this->errorHandler == null)
            $this->setErrorHandler(new ErrorHandler($this));
        return $this->errorHandler;
    }

    public function setEventManager($eventManager) {
        $this->eventManager = $eventManager;
    }

    public function getEventManager() {
        if ($this->eventManager == null)
            $this->setEventManager(new EventManager($this));
        return $this->eventManager;
    }

    public function setExceptionHandler($exceptionHandler) {
        $this->exceptionHandler = $exceptionHandler;
    }

    public function getExceptionHandler() {
        if ($this->exceptionHandler == null)
            $this->setExceptionHandler(new ExceptionHandler($this));
        return $this->exceptionHandler;
    }

    public function setEnv($env) {
        $this->env = $env;
    }

    public function getEnv() {
        if ($this->env == null)
            $this->setEnv(new Env());
        return $this->env;
    }


}
