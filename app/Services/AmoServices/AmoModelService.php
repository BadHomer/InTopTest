<?php

namespace App\Services\AmoServices;

class AmoModelService
{
    public AmoRequestService $requestService;

    public function __construct()
    {
        $this->requestService = new AmoRequestService();
    }

    protected function createCustomValue(int $fieldId, $value)
    {
        $customFieldsValue = new \stdClass();

        $customFieldsValue->field_id = $fieldId;
        $customFieldsValue->field_code = null;
        $customFieldsValue->values = [];
        $customFieldsValue->values[] = (object)['value' => $value];

        return $customFieldsValue;
    }
}