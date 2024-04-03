<?php

namespace App\services\AmoServices;

use App\Models\Contact;

class ContactService
{
    public function createContact(array $data)
    {
        //return Contact::create($data);
        $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
        $dotenv->load();
    }
}