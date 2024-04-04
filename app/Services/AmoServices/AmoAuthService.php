<?php

namespace App\Services\AmoServices;

use App\Services\AmoServices\Enums\AmoGrantTypeEnums;

class AmoAuthService
{
    private AmoRequestService $requestService;

    public function __construct()
    {
        $this->requestService = new AmoRequestService();
    }

    public function getNewTokens()
    {
        $code = 'def502000302e687c85301e3b06a1a1a5966f15b8b2d91b5fa3a9a96f4897fdb3df3984fd7bf7fef7c36355781cb2a8a9e3ae41003769787d3bfc8982a460d7177290281318609ac3d04d1b49fce9f77a54994c1525f5c9653366eca8faa48816f76435e1f0fe620ebc6b8b652c2c74106e3d1a76c575c4bb4619d1da4af70ff895552842fe9b8b85ae6b638cb3b79d5070dae0225dd8cfe604f7e22410152b5d1bcb09be2d4b68720464669704c7948b8e6db730bebd206651f37de4fda92e7f5e8cd6734d4b83f1f78e57c470658d0c30ec2f2cb0f881defbc00fb3b1f3592be905255f02d795309a850f90d1181c5dfcce24633027e48da9558b3f306c4d17f0623ec74ecb1fb0c4e02d5084d90479f62781a466d93c9259e3d8f446098ffe58420385c34f2915512f183589a7f0b626a4df0ebd8f532ed121fd8cee0cee065939b0426b314e90420c840010c5c28fddaff2d5a7e966b788bc1bcb49ed2aaecaf3ede4d5cb812ab7ea9805bc784d3b13bcb24ae0ff994fb18566ee5534a5441054ac39604b2464c5ea137962fa959e143b09c94ce89718035d2f8d4d0b7ba6fb03a97f3aa9b95742d8f64a7369ca817ce451e26ba7716d3a07bbadf70c3c4e279344584a84f1dabae163b611681f94c1c9c4f4a7a09c952ecc4b886880962293a8a32e90c9b0bab';
        $responseData = $this->requestService->sendAuthRequest(AmoGrantTypeEnums::authorization_code, $code);

        return $this->saveTokens($responseData->access_token, $responseData->refresh_token, $responseData->expires_in);
    }

    private function saveTokens(string $accessToken, string $refreshToken, int $expires_in)
    {
        $tokensFile = fopen($_SERVER['DOCUMENT_ROOT'] . '/../tokens.json', 'wb');

        $tokensArray = [
            'access_token' => [
                'token' => $accessToken,
                'expires_in' => date("d/m/y H:i", strtotime("+{$expires_in} seconds")),
            ],
            'refresh_token' => $refreshToken,
        ];

        $tokensJson = json_encode($tokensArray, JSON_THROW_ON_ERROR);

        fwrite($tokensFile, $tokensJson);

        fclose($tokensFile);

        return json_decode($tokensJson);
    }

    public function getTokens()
    {
        $tokens = $this->getTokensFromFile();

        if ($tokens->access_token->expires_in <= date('d/m/y H:i')) {
            $tokens = $this->refreshTokens();
        }

        return $tokens;
    }

    private function getTokensFromFile(): mixed
    {
        $tokensJson = file_get_contents($_SERVER['DOCUMENT_ROOT'] . '/../tokens.json');

        return json_decode($tokensJson, false, 512, JSON_THROW_ON_ERROR);
    }

    private function refreshTokens()
    {
        $tokens = $this->getTokensFromFile();

        $refreshedTokens = $this->requestService->sendAuthRequest(AmoGrantTypeEnums::refresh_token, $tokens->refresh_token);

        return $this->saveTokens($refreshedTokens->access_token, $refreshedTokens->refresh_token, $refreshedTokens->expires_in);
    }
}