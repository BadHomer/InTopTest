<?php

namespace App\Services\AmoServices\Enums;

enum AmoGrantTypeEnums: string
{
    case authorization_code = 'code';
    case refresh_token = 'refresh_token';
}
