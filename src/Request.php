<?php

namespace GoGetSSL;

/**
 * Class Request
 * @package GoGetSSL
 *
 * @author Andrzej Tracz
 */
trait Request
{
    /**
     * Private ApiKey
     *
     * @var string
     */
    private $apiKey;

    /**
     * Request method
     *
     * @var string
     */
    private $method;

    /**
     * Response body
     *
     * @var string
     */
    private $response;

    /**
     * Response headers
     * @var array
     */
    private $responseHeaders;

    /**
     * @var string
     */
    private $url;

    /**
     * Request constructor.
     *
     * @param $url
     * @param $username
     * @param $password
     *
     * @throws \Exception
     */
    private function init($username, $password, $url)
    {
        $this->url = $url;
        $this->apiKey = $this->getKey($username, $password);

        if (strlen($this->apiKey) == 0) {
            throw new \Exception("GoGetSSL Api Key is not set. Response: {$this->response}");
        }
    }

    /**
     * @param $username
     * @param $password
     *
     * @return null
     */
    private function getKey($username, $password)
    {
        $response = $this->post('/auth/', [
            'user' => $username,
            'pass' => $password
        ]);

        if ($response && isset($response->key)) {
            return $response->key;
        }

        return null;
    }

    /**
     * @param $url
     * @return mixed
     */
    public function get($url)
    {
        $this->method = 'GET';
        return $this->send($url);
    }

    /**
     * @param $url
     * @param array $data
     * @return mixed
     */
    public function post($url, array $data = [])
    {
        $this->method = 'POST';
        return $this->send($url, $data);
    }

    /**
     * @param $url
     * @param array $data
     * @return mixed
     */
    public function put($url, array $data = [])
    {
        $this->method = 'PUT';
        return $this->send($url, $data);
    }

    /**
     * @param $url
     * @param array $data
     * @return mixed
     */
    public function delete($url, array $data = [])
    {
        $this->method = 'DELETE';
        return $this->send($url, $data);
    }

    /**
     * @param $url
     * @param array $data
     * @return mixed
     * @throws \Exception
     */
    private function send($url, array $data = [])
    {
        $headers = $this->setupHeaders([
            //
        ]);

        $ch = curl_init();
        $url = $this->url . $url;
        if (null !== $this->apiKey) {
            $url .= (strpos($url, '?') !== false ? '&' : '?') . 'auth_key=' . rawurlencode($this->apiKey);
        }

        if ($this->isGet() && count($data)) {
            $url .= (strpos($url, '?') !== false ? '&' : '?') . http_build_query($data);
        };

        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_URL, $url);

        if (false == $this->isGet()) {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $this->method);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        }

        $response = curl_exec($ch);
        $this->responseHeaders = (object) curl_getinfo($ch);
        curl_close($ch);

        $this->response = $response;

        return $this->handleResponse();
    }

    /**
     * @param array $extra
     * @return array
     */
    private function setupHeaders(array $extra = [])
    {
        $headers = [
            //
        ];

        return array_merge($headers, $extra);
    }


    private function handleResponse($parseJson = true)
    {
        if ($this->isRequestSuccess()) {
            if ($parseJson) {
                $json = json_decode($this->response);

                if (strlen($this->response) && null == $json) {
                    throw new \Exception("Json Parse Error: {$this->response}");
                }

                return $json;
            }
            return $this->response;
        }

        throw new \Exception(sprintf("API Response: Code: %d - %s", $this->responseHeaders->http_code, $this->response));
    }

    /**
     * Check if request was successful
     *
     * @return bool
     */
    private function isRequestSuccess()
    {
        return isset($this->responseHeaders->http_code) &&
            in_array($this->responseHeaders->http_code, [
                200, 201, 202, 204
            ]);
    }

    /**
     * Check is current request method is GET
     *
     * @return bool
     */
    private function isGet()
    {
        return $this->method === 'GET';
    }

}