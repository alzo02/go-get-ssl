<?php

namespace GoGetSSL;

/**
 * Go Get SSL Rest API Wrapper
 *
 * @author Andrzej Tracz
 * @package GoGetSSL
 */
class Api
{
    use Request;

    /**
     * Base URLs of API
     */
    const LIVE_URL = "https://gogetssl.com/api";
    const SANDBOX_URL = "https://sandbox.gogetssl.com/api";

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

        $this->init($username, $password, $url);
    }
}