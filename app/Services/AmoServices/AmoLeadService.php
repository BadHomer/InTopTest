<?php

namespace App\Services\AmoServices;

use App\Models\Lead;
use App\Services\AmoServices\Enums\AmoFieldIds;
use GuzzleHttp\Exception\ClientException;

class AmoLeadService extends AmoModelService
{

    public function createLead(array $data)
    {
        $leadData = [
            'name' => 'Заявка с сайта ' . date('d-m-y/H:i'),
            'comment' => $data['lead']['comment']
        ];

        $lead = Lead::create($leadData);

        $customFieldsValues = $this->createCustomFieldsValues($lead);

        $_embedded = $this->createEmbedded($data);

        $requestData = $this->toRequestData($lead, $_embedded, $customFieldsValues);

        try {
            return $this->requestService->sendRequest('POST', '/api/v4/leads/complex', $requestData);
        } catch (ClientException $e) {
            return $e->getResponse()->getBody()->getContents();
        }
    }

    private function toRequestData(Lead $lead, object $_embedded = null, array $customFieldsValues = [])
    {
        $leadObj = new \stdClass();

        $leadObj->name = $lead->name;
        $leadObj->custom_fields_values = $customFieldsValues;
        $leadObj->_embedded = $_embedded;
        $leadObj->tags_to_add = [
            (object)['name' => 'сайт']
        ];

        return [$leadObj];

    }

    private function createEmbedded(array $data): ?\stdClass
    {
        $contactData = $data['contact'] ?? null;

        if ($contactData === null) {
            return null;
        }

        $_embedded = new \stdClass();

        $_embedded->contacts = [(new AmoContactService())->createContactForRequest($contactData)];

        return $_embedded;
    }

    private function createCustomFieldsValues(Lead $lead)
    {
        $customFieldsValues[] = $this->createCustomValue(AmoFieldIds::LeadOrigin->value, 'Сайт');
        $customFieldsValues[] = $this->createCustomValue(AmoFieldIds::LeadComment->value, $lead->comment);

        return $customFieldsValues;
    }

}