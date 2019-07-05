<?php


namespace App\Api;


use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class ApiRequest
{

    private $httpClient, $apiUrl;

    public function __construct(HttpClientInterface $httpClient, string $apiUrl = "http://127.0.0.1:8000/api")
    {
        $this->httpClient = $httpClient;
        $this->apiUrl = $apiUrl;
    }

    public function request(string $url, string $method = 'GET')
    {

        try {

            $response = $this->httpClient->request($method, $url);

            if ($response->getStatusCode() == 200) {

                try {

                    return $response->toArray();

                } catch (DecodingExceptionInterface $exception) {

                    # TODO A traiter. Loger, ...
                    dump($exception);
                    die;

                }

            }
        } catch (TransportExceptionInterface $exception) {

            # TODO A traiter.
            dump($exception);
            die;

        }
    }
}