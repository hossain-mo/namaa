<?php

namespace App\DataProviderMappers;

use App\DataProviderMappers\Contracts\DataProviderStrategy;
use App\DataProviderMappers\Providers\DataProviderX;
use App\DataProviderMappers\Providers\DataProviderY;
use InvalidArgumentException;

class DataProviderFactory
{
    public static function create(string $provider): DataProviderStrategy
    {
        return match ($provider) {
            'DataProviderX' => new DataProviderX(),
            'DataProviderY' => new DataProviderY(),
            default => throw new InvalidArgumentException("Provider $provider not found.")
        };
    }

    public static function allProviders(): array
    {
        return ['DataProviderX', 'DataProviderY'];
    }
}
