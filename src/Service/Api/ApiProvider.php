<?php


namespace App\Service\Api;


use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class ApiProvider
{

    private $httpClient;
    private $apiUrl;

    /**
     * ApiRequest constructor.
     * @param HttpClientInterface $httpClient
     * @param string $apiUrl
     */
    public function __construct(HttpClientInterface $httpClient, string $apiUrl = "http://127.0.0.1:8000/api/")
{
    $this->httpClient = $httpClient;
    $this->apiUrl = $apiUrl;
}

    /**
     * @param string $url
     * @param string $method
     * @return array
     * @throws DecodingExceptionInterface
     * @throws TransportExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     */
    public function request(string $url, string $method = 'GET')
{

    try {

        $response = $this->httpClient->request($method, $url);

        if ($response->getStatusCode() == 200) {

            try {

                return $response->toArray();

            } catch (DecodingExceptionInterface $exception) {

                throw $exception;
            }

        }
    } catch (TransportExceptionInterface $exception) {

        throw $exception;
    }
}

}
