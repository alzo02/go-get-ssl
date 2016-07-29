<?php

class ExampleTest extends \PHPUnit_Framework_TestCase
{
    public function testRequest()
    {
        $user = 'your@email';
        $pass = 'yourPaSs';

        $api = new \GoGetSSL\Api($user, $pass);
        $productsApi  = new \GoGetSSL\Product($api);

        $products = $productsApi->getAll();
        $details = $productsApi->getDetails(104);
        $price = $productsApi->getPrice(104);

        $this->assertTrue(is_object($products));
        $this->assertTrue($products->success);
    }
}