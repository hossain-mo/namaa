<?php

namespace App\DataProviderMappers\Providers;

use App\DataProviderMappers\Contracts\DataProviderStrategy;
use App\DataProviderMappers\Enums\StatusEnum;

class DataProviderY implements DataProviderStrategy
{
    protected $data;

    public function __construct()
    {
        $filePath = storage_path('DataProviders/DataProviderY.json');
        if (!file_exists($filePath)) {
            throw new \RuntimeException("Data file for Provider Y not found");
        }

        $decodedData = json_decode(file_get_contents($filePath), true);
        $this->data = $decodedData['transactions'];
    }

    public function getStatusMapping(): array
    {
        return [
            100 => StatusEnum::Authorised->value,
            200 => StatusEnum::Decline->value,
            300 => StatusEnum::Refunded->value
        ];
    }

    public function getData(): array
    {
        return array_map(function ($item) {
            return [
                'amount' => $item['balance'],
                'currency' => $item['currency'],
                'email' => $item['email'],
                'status' => $this->getStatusMapping()[$item['status']],
                'date' => $item['created_at'],
                'id' => $item['id']
            ];
        }, $this->data);
    }
}
