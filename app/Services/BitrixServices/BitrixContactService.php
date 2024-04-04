<?php

namespace App\Services\BitrixServices;

use App\Models\Contact;


class BitrixContactService
{
    private BitrixRequestService $requestService;

    public function __construct()
    {
        $this->requestService = new BitrixRequestService();
    }

    public function createContact(array $data)
    {
        $contact = Contact::create($data);

        $requestData = $this->contactToRequest($contact);

        $response = $this->requestService->sendRequest('/crm.contact.add', ['fields' => $requestData]);

        return $response->result;
    }

    private function contactToRequest(Contact $contact): \stdClass
    {
        $phone = $this->createPhoneObj($contact);

        $contactObj = new \stdClass();

        $contactObj->NAME = $contact->name;
        $contactObj->PHONE = [$phone];

        return $contactObj;

    }

    private function createPhoneObj(Contact $contact): \stdClass
    {
        $phone = new \stdClass();

        $phone->VALUE = $contact->telephone;
        $phone->VALUE_TYPE = 'WORK';

        return $phone;
    }
}