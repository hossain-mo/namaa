<?php

namespace App\DataProviderMappers\Providers;

use App\DataProviderMappers\Contracts\DataProviderStrategy;
use App\DataProviderMappers\Enums\StatusEnum;

class DataProviderX implements DataProviderStrategy
{
    protected $data;

    public function __construct()
    {
        $filePath = storage_path('DataProviders/DataProviderX.json');
        if (!file_exists($filePath)) {
            throw new \RuntimeException("Data file for Provider X not found");
        }

        $decodedData = json_decode(file_get_contents($filePath), true);
        $this->data = $decodedData['transactions'];
    }

    public function getStatusMapping(): array
    {
        return [
            1 => StatusEnum::Authorised->value,
            2 => StatusEnum::Decline->value,
            3 => StatusEnum::Refunded->value
        ];
    }

    public function getData(): array
    {
        return array_map(function ($item) {
            return [
                'amount' => $item['parentAmount'],
                'currency' => $item['Currency'],
                'email' => $item['parentEmail'],
                'status' => $this->getStatusMapping()[$item['statusCode']],
                'date' => $item['registerationDate'],
                'id' => $item['parentIdentification']
            ];
        }, $this->data);
    }
}
