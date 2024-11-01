<?php

namespace App\DataProviderMappers\Contracts;

interface DataProviderStrategy
{
    public function getData(): array;
    public function getStatusMapping(): array;
}
