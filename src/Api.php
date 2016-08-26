<?php

namespace GoGetSSL;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Formatter\LineFormatter;

/**
 * Go Get SSL Rest API Wrapper
 * @package GoGetSSL
 * @author alzo02 <alzo02@icloud.com>
 */
class Api
{
    use Request;

    /**
     * Base URLs of API
     */
    const LIVE_URL = "https://my.gogetssl.com/api";
    const SANDBOX_URL = "https://sandbox.gogetssl.com/api";

    public $log;

    private $logPath;

    private $mode;
    /**
     * Create API context, default sandbox mode is enabled.
     *
     * @param $username
     * @param $password
     * @param string $mode
     */
    function __construct($username, $password, $mode = 'sandbox')
    {
        $url = self::SANDBOX_URL;
        $this->mode = $mode;

        if ($mode == 'live') {
            $url = self::LIVE_URL;
        }

        $this->init($username, $password, $url);
    }

    /**
     * @param $path
     * @return $this
     */
    public function setLogPath($path)
    {
        $this->logPath = $path;

        return $this;
    }

    /**
     * @return string
     */
    public function getLogPath()
    {
        return $this->logPath;
    }

    /**
     * @return $this
     */
    public function enableLog()
    {
        $name = $this->mode == 'live' ? "API_PROD" : "API_DEV";

        $this->log = new Logger($name);
        $handler = new StreamHandler($this->getLogPath(), Logger::INFO);
        $handler->setFormatter(new LineFormatter(null, null, true, true));
        $this->log->pushHandler($handler);
        $this->log->info("Log init");
        return $this;
    }

}