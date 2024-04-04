<?php

namespace App\Services\BitrixServices;

use GuzzleHttp\Client;

class BitrixRequestService
{
    public function sendRequest(string $uri, array $data = [])
    {
        $url = $_ENV['BITRIX_URL'] . $uri;

        $options = [
            'query' => $data,
        ];

        $response = (new Client())->request('GET', $url, $options);

        $responseJsonData = $response->getBody()->getContents();

        if ($responseJsonData !== '') {
            return json_decode($responseJsonData, false, 512, JSON_THROW_ON_ERROR);
        }

        return [];
    }

}