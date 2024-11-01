<?php

namespace App\Services;

use App\DataProviderMappers\DataProviderFactory;
use Illuminate\Support\Collection;

class UserTransactionService
{
    public function get(array $filters = []): Collection
    {
        $data = collect();

        $providers = isset($filters['provider']) ? [$filters['provider']] : DataProviderFactory::allProviders();

        foreach ($providers as $providerName) {
            try {
                $provider = DataProviderFactory::create($providerName);
                $data = $data->merge($provider->getData());
            } catch (\InvalidArgumentException $e) {
                return response()->json(['error' => $e->getMessage()], 404);
            }
        }
        return $this->applyFilters($data, $filters);
    }

    protected function applyFilters(Collection $data, array $filters): Collection
    {
        foreach ($filters as $key => $value) {
            switch ($key) {
                case 'provider':
                    break;
                case 'statusCode':
                    $data = $data->where('status', $value);
                    break;
                case 'balanceMin':
                    $data = $data->where('amount', '>=', $value);
                    break;
                case 'balanceMax':
                    $data = $data->where('amount', '<=', $value);
                    break;
                case 'currency':
                    $data = $data->where('currency', $value);
                    break;
            }
        }
        return $data;
    }
}
