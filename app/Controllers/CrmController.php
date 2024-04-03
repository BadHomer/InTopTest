<?php

namespace App\Controllers;

use App\Services\AmoServices\AmoAuthService;
use App\View\View;

class CrmController extends BaseController
{
    public function index(): false|string
    {
        return View::get('index');
    }


    public function createLead()
    {
        echo (new AmoAuthService())->getNewTokens();
        return View::get('success');
    }
}