<?php

namespace App\Services\BitrixServices;

use App\Models\Lead;
use App\Services\BitrixServices\Enums\BitrixDealOriginListItemsIds;
use App\Services\BitrixServices\Enums\BitrixFieldsIds;


class BitrixDealService
{
    private BitrixRequestService $requestService;

    public function __construct()
    {
        $this->requestService = new BitrixRequestService();
    }

    public function createDeal(array $data)
    {
        $dealData = [
            'name' => 'Заявка с сайта ' . date('d-m-y/H:i'),
            'comment' => $data['lead']['comment'],
        ];

        $deal = Lead::create($dealData);

        $requestData = $this->dealToRequest($deal);

        $response = $this->requestService->sendRequest('/crm.deal.add', ['fields' => $requestData]);

        $dealId = $response->result;

        $this->addContact($dealId, $data['contact']);

        return $response;
    }

    private function dealToRequest(Lead $deal): \stdClass
    {
        $dealObj = new \stdClass();
        $dealObj->TITLE = $deal->name;
        $dealObj->{BitrixFieldsIds::dealComment->value} = $deal->comment;
        $dealObj->{BitrixFieldsIds::dealOriginList->value} = BitrixDealOriginListItemsIds::site->value;

        return $dealObj;

    }

    private function addContact(int $dealId, array $contactData): void
    {
        $contactId = (new BitrixContactService())->createContact($contactData);
        $requestData = [
            'id' => $dealId,
            'fields' => [
                'CONTACT_ID' => $contactId,
                'IS_PRIMARY' => 'Y'
            ]
        ];

        $this->requestService->sendRequest('/crm.deal.contact.add', $requestData);
    }
}