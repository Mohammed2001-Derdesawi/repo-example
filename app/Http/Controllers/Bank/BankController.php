<?php

namespace App\Http\Controllers\Bank;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\Bank\BankRequest;
use App\Http\Services\Dashboard\Bank\BankService;
use App\Http\Services\Mutual\ExportService;
use App\Repository\BankRepositoryInterface;
use Illuminate\Http\Request;

class BankController extends Controller
{
    private BankRepositoryInterface $bankRepository;
    private BankService $bankService;
    private ExportService $export;

    public function __construct(BankRepositoryInterface $bankRepository , BankService $bankService , ExportService $export){
        $this->bankRepository = $bankRepository;
        $this->bankService = $bankService;
        $this->export = $export;
    }

    public function index(){
        $banks = $this->bankRepository->paginate(25);
        return view('dashboard.site.banks.index' , [
            'banks' => $banks
        ]);
    }

    public function create(){
        return view('dashboard.site.banks.create');
    }

    public function store(BankRequest $request){
        return $this->bankService->store($request);
    }

    public function edit($id){
        $bank = $this->bankRepository->getById($id);
        return view('dashboard.site.banks.edit' , [
           'bank' =>  $bank
        ]);
    }

    public function update($id , BankRequest $request){
        return $this->bankService->update($id , $request);
    }

    public function destroy($id){
        return $this->bankService->delete($id);
    }

    public function export(string $type)
    {
        $banks = $this->bankRepository->getAll();
        $data = [
            'banks' => $banks
        ];

        return $this->export->handle('banks', 'dashboard.site.banks.export', $data, 'banks', $type);
    }
}
