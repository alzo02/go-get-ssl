<?php

namespace GoGetSSL;

/**
 * Class Product
 *
 * @package GoGetSSL
 * @author alzo02 <alzo02@icloud.com>
 */
class Product
{
    /**
     * @var Api
     */
    protected $api;

    /**
     * Product constructor.
     * @param Api $api
     */
    function __construct(Api $api)
    {
        $this->api = $api;
    }

    /**
     * Gets all products
     *
     * @return mixed
     */
    public function getAll()
    {
        return $this->api->get("/products/");
    }

    /**
     * Get details about product
     *
     * @param $id
     * @return mixed
     */
    public function getDetails($id)
    {
        return $this->api->get("/products/details/{$id}");
    }

    /**
     * Get prices for given product
     *
     * @param $id
     * @return mixed
     */
    public function getPrice($id)
    {
        return $this->api->get("/products/price/{$id}");
    }
}