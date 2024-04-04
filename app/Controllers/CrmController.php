<?php

namespace App\Controllers;

use App\Services\AmoServices\AmoAuthService;
use App\Services\AmoServices\AmoLeadService;
use App\Services\BitrixServices\BitrixContactService;
use App\Services\BitrixServices\BitrixDealService;
use App\View\View;

class CrmController extends BaseController
{
    public function index(): false|string
    {
        return View::get('index');
    }


    public function createLead()
    {
        (new BitrixDealService())->createDeal($_POST);

        return View::get('success');
    }
}