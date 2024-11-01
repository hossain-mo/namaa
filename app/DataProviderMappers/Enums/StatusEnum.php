<?php

namespace App\DataProviderMappers\Enums;

enum StatusEnum: string
{
    case Authorised = "authorised";
    case Decline = "decline";
    case Refunded = "refunded";
}