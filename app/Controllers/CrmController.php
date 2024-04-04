<?php

namespace App\Controllers;

use App\Services\AmoServices\AmoAuthService;
use App\Services\AmoServices\AmoLeadService;
use App\Services\AmoServices\AmoRequestService;
use App\Services\BitrixServices\BitrixDealService;
use App\View\View;

class CrmController
{
    public function index(): void
    {
        View::get('index');
    }

    public function createLead(): void
    {
        (new BitrixDealService())->createDeal($_POST);
        (new AmoLeadService())->createLead($_POST);

        View::get('success');
    }
}