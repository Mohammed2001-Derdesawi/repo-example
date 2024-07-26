<?php

namespace App\Http\Controllers\Api\Bank;

use App\Http\Controllers\Controller;
use App\Http\Services\Api\Bank\BankService;
use App\Repository\BankRepositoryInterface;
use Illuminate\Http\Request;

class BankController extends Controller
{
    private BankService $bankService;

    public function __construct(BankService $bankService)
    {
        $this->bankService = $bankService;
    }


    public function index(){
        return $this->bankService->getBanks();
    }
}
