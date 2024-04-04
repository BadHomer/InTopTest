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

    private function getNewTokens()
    {
        $code = 'def502007c3676867cb61a1ebb7f2f7ba2cd6cad89beed8b9597aee5de4cc5a27cb6d10c379f35007cd32fa3684b3f1dd6eadae89bfc60944518c1ca16666391370abcc646da8206bbe85ef2ea2118ebe385813b90b79b846bd32713953193daf5359e09ec3e0cf813abed8d9e9429a7349fdbbbf3187d53b8726b94eb669b4fd5b8ca7ac7fb63cd546592abe54ef15d86c54dd16541cb0741617707af3e1b5713121949fd4cf36566d3084d26844be889f466316c95a9148842b7494f6487fb9cf972d0522eb500a98be7330620ceb4ccc285744af94ff70487bf9fae06890692526db58578d4e3979b2b7fb1f553b47611490a1be66fb3f1905cf2bfb4f8542a1db3fd1f51964c4d2cdf0b811dd9b96783d9c39f808bf7b696f0fe129f60a3f4b73c663b5a093c18c23d4ca9e90dc5be679e1bb5c7903bc84675530941aeff60dbec4853c6df27bca83139fd570fcafb42d823654447bc53daefaf5e93e7132d660a78d591844f129dd697b132b24b494ca1d4b8ff64d18bdddcc2758d0be762943499902dc7681cd35c8098fbbc2003a9ccd266b6fb9163c2098c241b75f7831cea32a69b7d2b4e1c43821deacbd98d525c99a8cd498abd9c5ac19d1c52f24bf7c26a3323055ce33a4a8fe0efb26682eb54fc343f42fd9738a5cf7fdc9dbd344c74fd5ebd82f91c';

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