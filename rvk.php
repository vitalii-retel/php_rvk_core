<?php

    define('TIME_START', microtime());

    require __DIR__ . '/core/Singleton.php';
    require __DIR__ . '/core/AutoLoad.php';

    spl_autoload_register(AutoLoad::getInstance());

    set_error_handler(ErrorHandler::getInstance());

    set_exception_handler(ExceptionHandler::getInstance());

    Registry::set('SERVER', new ArrayClass($_SERVER));

?>