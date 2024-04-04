<?php

namespace App\Services\AmoServices;

use App\Models\Contact;
use App\Services\AmoServices\Enums\AmoFieldIds;

class AmoContactService extends AmoModelService
{
    private function createContact(array $data)
    {
        return Contact::create($data);
    }

    private function toRequestData(Contact $contact, $customFieldsValues)
    {
        $contactObj = new \stdClass();

        $contactObj->name = $contact->name;
        $contactObj->custom_fields_values = $customFieldsValues;

        return $contactObj;
    }

    public function createContactForRequest(array $data)
    {
        $contact = $this->createContact($data);

        $customFieldsValues[] = $this->createCustomValue(AmoFieldIds::Telephone->value, $contact->telephone);

        return $this->toRequestData($contact, $customFieldsValues);
    }
}