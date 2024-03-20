<?php


namespace App\Services;


use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class MockyService
{
    /**
     * @var Client
     */
    private $client;

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => 'https://run.mocky.io/'
        ]);
    }

    public function authorizeTransaction(): array
    {
        $uri = '/v3/5794d450-d2e2-4412-8131-73d0293ac1cc';
        try {
            $response = $this->client->request('GET',$uri);
            return json_decode($response->getBody(), true);
        } catch (GuzzleException $exception) {
            return ['message' => 'Não Autorizado'];
        }

    }

    public function notifyUser(): array
    {
        $uri = 'v3/b19f7b9f-9cbf-4fc6-ad22-dc30601aec04';
        try {
            $response = $this->client->request($uri);

            return json_decode($response->getBody(), true);
        } catch (GuzzleException $exception) {
            return ['error when communicating with external service'];
        }

    }
}
