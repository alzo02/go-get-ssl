<?php

use GoGetSSL\Order;

class ToolsTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \GoGetSSL\Order
     */
    protected $tools;

    function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);

        $pass = include __DIR__ . "/../config.php";
        $api = new \GoGetSSL\Api($pass['user'], $pass['pass']);
        $api->setLogPath(__DIR__ . "/../log/api.log")->enableLog();

        $this->tools = new \GoGetSSL\Tools($api);

        $api->onResponse(function ($response) use ($api) {
            $api->log->info(json_encode($response, JSON_PRETTY_PRINT));
        });
    }


    public function testGetDomainEmails()
    {
        $response = $this->tools->getDomainEmails("cosdobrego.pl");
        $this->assertTrue(is_object($response));
        $this->assertTrue($response->success);
    }

    public function testGetDomainEmailsForGeotrust()
    {
        $response = $this->tools->getDomainEmailsForGeotrust("cosdobrego.pl");
        $this->assertTrue(is_object($response));
        $this->assertTrue($response->success);
    }
}