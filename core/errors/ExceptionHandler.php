<?php

namespace core\errors;

class ExceptionHandler extends \core\common\HasCore {

    public static $SEVERITIES = array(
        E_ALL => 'E_ALL',
        E_WARNING => 'E_WARNING',
        E_COMPILE_ERROR => 'E_COMPILE_ERROR',
        E_COMPILE_WARNING => 'E_COMPILE_WARNING',
        E_CORE_ERROR => 'E_CORE_ERROR',
        E_CORE_WARNING => 'E_CORE_WARNING',
        E_DEPRECATED => 'E_DEPRECATED',
        E_ERROR => 'E_ERROR',
        E_NOTICE => 'E_NOTICE',
        E_PARSE => 'E_PARSE',
        E_RECOVERABLE_ERROR => 'E_RECOVERABLE_ERROR',
        E_STRICT => 'E_STRICT',
        E_USER_DEPRECATED => 'E_USER_DEPRECATED',
        E_USER_ERROR => 'E_USER_ERROR',
        E_USER_NOTICE => 'E_USER_NOTICE',
        E_USER_WARNING => 'E_USER_WARNING'
    );

    private $logToFile;
    private $logFilesPath;
    private $logFileNameFormat;

    function __construct($core) {
        parent::__construct($core);
        $this->setLogToFile(false);
        $this->setLogFilesPath(__DIR__ . '/../logs');
        $this->setLogFileNameFormat(date('Y-m-d') . '.log');
    }

    public function __invoke(Exception $exception) {
        $this->log($exception, true);
    }

    public function log(Exception $e, $fatal = false) {
        if ($this->getLogToFile()) {
            $message = "\n" . '[' . date('Y-m-d H:i:s') . ']' . "\n";
            $message .= 'Message: ' . $e->getMessage() . "\n";
            if ($e instanceof ErrorException) {
                $message .= 'Severity: ' . (array_key_exists($e->getSeverity(), self::$SEVERITIES) ? self::$SEVERITIES[$e->getSeverity()] : $e->getSeverity()) . '. ';
            }
            $message .= 'Code: ' . $e->getCode() . '. ';
            $message .= 'File: ' . $e->getFile() . '. ';
            $message .= 'Line: ' . $e->getLine() . '.' . "\n";
            $message .= 'Trace: ' . "\n" . $e->getTraceAsString() . "\n";
            $fileName = rtrim($this->getLogFilesPath(), '/') . '/' . $this->getLogFileNameFormat();
            if (@file_put_contents($fileName, $message, FILE_APPEND) === false) {
                echo '<dl class="rvk-error-box">';
                echo '<dt class="title"><b>Error has been occurred</b></dt>';
                echo '<dt>Message:</dt>';
                echo '<dd>Can not write log message into the file "', $fileName, '".</dd>';
                echo '</dl>';
            };
        }
        if (!$this->getLogToFile() || $fatal) {
            if (EventManager::getInstance()->trigger(EventManager::EVENT_BEFORE_ERROR_DISPLAY, array('exception' => $e)) !== false) {
                echo '<dl class="rvk-error-box">';
                echo '<dt class="title"><b>Error has occurred</b></dt>';
                echo '<dt>Message:</dt>';
                echo '<dd>', $e->getMessage(), '</dd>';
                if ($e instanceof ErrorException) {
                    echo '<dt>Severity:</dt>';
                    echo '<dd>', (array_key_exists($e->getSeverity(), self::$SEVERITIES) ? self::$SEVERITIES[$e->getSeverity()] : $e->getSeverity()), '</dd>';
                }
                echo '<dt>Code:</dt>';
                echo '<dd>', $e->getCode(), '</dd>';
                echo '<dt>File:</dt>';
                echo '<dd>', $e->getFile(), '</dd>';
                echo '<dt>Line:</dt>';
                echo '<dd>', $e->getLine(), '</dd>';
                echo '<dt>Trace:</dt>';
                echo '<dd>', nl2br($e->getTraceAsString()), '</dd>';
                echo '</dl>';
            }
        }
    }

    public function setLogFileNameFormat($logFileNameFormat) {
        $this->logFileNameFormat = $logFileNameFormat;
    }

    public function getLogFileNameFormat() {
        return $this->logFileNameFormat;
    }

    public function setLogFilesPath($logFilesPath) {
        $this->logFilesPath = $logFilesPath;
    }

    public function getLogFilesPath() {
        return $this->logFilesPath;
    }

    public function setLogToFile($logToFile) {
        $this->logToFile = $logToFile;
    }

    public function getLogToFile() {
        return $this->logToFile;
    }

}
