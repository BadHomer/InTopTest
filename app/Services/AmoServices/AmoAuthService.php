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
            'code' => 'def502008edbc387ca2b7348b75e9006a9c6db510a00b4ef8464ce58cc2dff560083ece1d6f4100c307cae94a25dd3ff9c63339a80f31a29826caca5f0c5a82527db49bc5710ab83481f9f20b340e50f773334e24c81c16c7eef581cac8d5d9c1b3e723d895408838cab809a08762de03563572dc7331d6036f09752704e75eeadb551f1069812e5490a29b0f0b285f3bdce02a51dd06dd80a9122c650b8a1794209756eeb8f87947f9b8a1fb4f4098f0f15837f53837def28513deed81fe3130ff57577f5a389267498ad08a3df540574c0aea12396bd5179fe571e705643ea98c386d9da3ae18ead76f4e57dd4b3457aef510af8d9555a33fcd7742504074df4b785142e4edf97e3b166526c8c5e2ef00e36b249ec200192e0fec0229302d8dbedd8ec741fcfe69e54587e98b31413e7285a4a00bf79a56b0858875f9b82e7236f6cf3f1eb4261642d8c1415961e7e6b4136a6e0fa7aff0c1b69a82a6122353f6d9b05889577c987526dc0e5c42c4411d5f361b2fc9c4012672e14caa0055e0915a9dac6685c710c477f2428f86841488615dc9dd44784539c0abf9cc4337987d7b09f6a71581256e705627f1f6b3797408e1a7ae3144035bdbcb69edd00e395093f5c1b4cd4e4a3a975ed5cca2cda8524169456072a2c28c64ab48fa7abfb447a8f625f2b4d1f25',
            'redirect_uri' => 'http://test-smartcard.ru',
        ];

        (new Client())->request('POST', $link, ['form_params' => $data]);
    }
}