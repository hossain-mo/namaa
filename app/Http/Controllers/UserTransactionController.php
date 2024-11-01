<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserTransactionRequest;
use App\Services\UserTransactionService;

class UserTransactionController extends Controller
{
    protected $service;

    public function __construct(UserTransactionService $service)
    {
        $this->service = $service;
    }

    public function index(UserTransactionRequest $request)
    {        
        $usersTransactions = $this->service->get($request->validated());

        return response()->json($usersTransactions->values()->all());
    }
}
