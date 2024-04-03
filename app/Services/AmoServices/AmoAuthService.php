<?php

namespace App\Services\AmoServices;

use GuzzleHttp\Client;
use JsonException;

class AmoAuthService
{
    public function saveTokens(string $accessToken, string $refreshToken, int $expires_in)
    {
        $tokensFile = fopen($_SERVER['DOCUMENT_ROOT'] . '/../tokens', 'wb');

        $tokensArray = [
            'access_token' => [
                'token' => $accessToken,
                'expires_in' => date("d/m/y H:i", strtotime("+{$expires_in} seconds")),
            ]
        ];

        $tokensJson = json_encode($tokensArray, JSON_THROW_ON_ERROR);

        fwrite($tokensFile, $tokensJson);

        fclose($tokensFile);
        return $tokensJson;
    }

    public function getExistTokens(): mixed
    {
        $tokensJson = file_get_contents($_SERVER['DOCUMENT_ROOT'] . '/../tokens');

        return json_decode($tokensJson, true, 512, JSON_THROW_ON_ERROR);
    }

    public function getNewTokens()
    {
        $subdomain = 'bespalvv2002';
        $link = 'https://' . $subdomain . '.amocrm.ru/oauth2/access_token';

        $data = [
            'client_id' => $_ENV['CLIENT_ID'],
            'client_secret' => $_ENV['CLIENT_SECRET'],
            'grant_type' => 'authorization_code',
            'code' => 'def502007156d96170e79874f00704537fc1c4038823cd829efe79e864021409f63c99cc8ff442cd52029d848b4f4b1e22ff45f7def097a930d45fc5d11ceffc0b4d147a321bb292769844f69f8053ee64e4657a3827158f2d1c83b2ec4c573f392bf9ffd40e4b8d6c1542219259d5fac2a4bb2dfd0eac4b2520ecfeee9547f7a818a51aab514523d21d98277446eb318df4d2e0a60ac0d4cec97c75388629d61cf2427bb561bb1a79eb752e4f15199b678827e9eb15b846473a54c13993ce6b7d205d2a625ef425b66b8c28b5804a4fdc1d673e5dec24f27be98021ee8925fb54ab7b7dbea95da437b0113ee52ca63b8f007d3941a64c2646b7fba44d28cb5cc8b1a7ec76499ddb16d1e8e409a017dd582540fa179a59c8f19819ecfab1bc49978bfc52940c2bafc13904359791c1fa5b91716d499fcc83df1c13ac915dab8b68200766f6313a5fd4a68e98ea2027829f576c7cee4649462de1f6e82d28d2602d61d7a326359d9f0aebdc0f5726ba436286b33c594bb68c6bef4d5ec15d35e7d2eeccf7b73cd74720a0667564a2f784333316c92d97ada05f4f6f99cd3b128ae313f60e363e4950894080df44109cfe4a09c47f12d5a25fd62f453da6becbcfe36842e1681bd0a1360ba4d94d2af87c1acc080a8d04b21fe0ae688d1fc2f9656c5a9a37c4f6443530',
            'redirect_uri' => 'http://test-smartcard.ru',
        ];

        return (new Client())->request('POST', $link, ['form_params' => $data])->getBody();
    }
}