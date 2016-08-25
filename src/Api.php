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

        if ($mode == 'live') {
            $url = self::LIVE_URL;
        }

        $name = $mode == 'live' ? "API_PROD" : "API_DEV";
        $this->log = new Logger($name);
        $handler = new StreamHandler(__DIR__ . '/../log/api.log', Logger::INFO);
        $handler->setFormatter(new LineFormatter(null, null, true, true));

        $this->log->pushHandler($handler);
        $this->init($username, $password, $url);
    }
}