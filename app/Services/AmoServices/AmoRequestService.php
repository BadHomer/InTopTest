<?php

namespace App\Services\AmoServices;

use App\Services\AmoServices\Enums\AmoGrantTypeEnums;
use GuzzleHttp\Client;
use http\Header;

class AmoRequestService
{
    private function baseSendRequest(string $method, string $uri, array $data = [], array $headers = []): mixed
    {
        $url = $_ENV['AMO_URL'] . $uri;
        $options = [
            'json' => $data,
            'headers' => $headers
        ];

        $response = (new Client())->request($method, $url, $options);

        $responseJsonData = $response->getBody()->getContents();

        if ($responseJsonData !== '') {
            return json_decode($responseJsonData, false, 512, JSON_THROW_ON_ERROR);
        }

        return [];
    }

    public function sendAuthRequest(AmoGrantTypeEnums $grant_type, string $code)
    {
        $data = [
            'client_id' => $_ENV['CLIENT_ID'],
            'client_secret' => $_ENV['CLIENT_SECRET'],
            'grant_type' => $grant_type->name,
            $grant_type->value => $code,
            'redirect_uri' => $_ENV['APP_URL'],
        ];

        return $this->baseSendRequest('POST', '/oauth2/access_token', $data);
    }

    public function sendRequest(string $method, string $uri, array $data = [])
    {
        $tokens = (new AmoAuthService())->getTokens();
        $headers = [
            'Authorization' => 'Bearer ' . $tokens->access_token->token,
            'Content-type' => ' application/json'
        ];
        return $this->baseSendRequest($method, $uri, $data, $headers);
    }
}