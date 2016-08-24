<?php

class ExampleTest extends \PHPUnit_Framework_TestCase
{
    private function credentials()
    {
        return include __DIR__ ."/../config.php";
    }

    public function testRequest()
    {
        $data = $this->credentials();
        $api = new \GoGetSSL\Api($data['user'], $data['pass']);

        $productsApi  = new \GoGetSSL\Product($api);
        $products = $productsApi->getAll();
        $details = $productsApi->getDetails(104);
        $price = $productsApi->getPrice(104);

        $this->assertTrue(is_object($products));
        $this->assertTrue($products->success);
        $this->assertTrue(is_object($details));
        $this->assertTrue(is_object($price));
    }
}