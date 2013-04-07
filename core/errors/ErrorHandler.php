<?php

namespace core\errors;

class ErrorHandler extends \core\common\HasCore {

    private $nonFatalErrors;

    protected function __construct($core) {
        parent::__construct($core);
        $this->setNonFatalErrors(E_NOTICE | E_WARNING | E_DEPRECATED | E_STRICT | E_CORE_WARNING | E_COMPILE_WARNING | E_USER_DEPRECATED | E_USER_NOTICE | E_USER_WARNING);
    }

    public function __invoke($errno, $errstr, $errfile = '', $errline = 0, $errcontext = array()) {
        $errorException = new \ErrorException($errstr, 0, $errno, $errfile, $errline);
        if ($this->getCore() != null && ($errorException->getSeverity() & $this->getNonFatalErrors()) == $errorException->getSeverity()) {
            $this->getCore()->getExceptionHandler()->log($errorException);
            return true;    /* non fatal */
        } else {
            throw $errorException;  /* fatal */
        }
    }

    public function setNonFatalErrors($nonFatalErrors) {
        $this->nonFatalErrors = $nonFatalErrors;
    }

    public function getNonFatalErrors() {
        return $this->nonFatalErrors;
    }


}
