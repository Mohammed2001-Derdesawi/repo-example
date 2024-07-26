<?php

namespace App\Http\Controllers\Dashboard\BookStore;

use App\Http\Contracts\Exportable;
use App\Http\Controllers\Controller;
use App\Http\Services\Dashboard\BookStore\BookStoreService;
use App\Http\Services\Mutual\ExportService;
use App\Repository\BookUserRepositoryInterface;
use App\Repository\FieldRepositoryInterface;
use Illuminate\Http\Request;

class BookStoreController extends Controller implements Exportable
{
    private BookUserRepositoryInterface $bookUserRepository;
    private BookStoreService $bookStoreService;
    private ExportService $export;

    public function __construct(BookUserRepositoryInterface $bookUserRepository , BookStoreService $bookStoreService, ExportService $exportService){
        $this->bookUserRepository = $bookUserRepository;
        $this->bookStoreService = $bookStoreService;
        $this->middleware('permission:payments-update')->only('toggleActivate');
        $this->middleware('permission:payments-read')->only('index');
        $this->export = $exportService;
    }

    public function index(){
        $sells = $this->bookUserRepository->paginate(orderBy: "DESC" , filters: function ($query){
            if (\request()->has('search') && \request('search') != "" ){
                $query->where(function ($query){
                    $query->whereHas('user' , function ($query){
                        $query->where('name' , 'LIKE' , '%' . \request('search') . '%')->orWhere('email' , 'LIKE' , '%' .\request('search') . '%')
                            ->orWhere('phone' , 'LIKE' , '%' . \request('search') . '%');
                    });
                });
            }
            if (\request()->has('from_date') && \request('from_date') != ""){
                $query->whereDate('created_at' , '>=' , \request('from_date'));
            }
            if (\request()->has('to_date') && \request('to_date') != ""){
                $query->whereDate('created_at' , '<=' , \request('to_date'));
            }
        });
        return view('dashboard.site.book_store.index' ,  [
            'sells' => $sells,
        ]);
    }

    public function toggleActivate($id){
        return $this->bookStoreService->toggleActivate($id);
    }

    public function export(string $type)
    {
        $sells = $this->bookUserRepository->getAll(orderBy: "DESC" , filters: function ($query){
            if (\request()->has('search') && \request('search') != "" ){
                $query->where(function ($query){
                    $query->whereHas('user' , function ($query){
                        $query->where('name' , 'LIKE' , '%' . \request('search') . '%')->orWhere('email' , 'LIKE' , '%' .\request('search') . '%')
                            ->orWhere('phone' , 'LIKE' , '%' . \request('search') . '%');
                    });
                });
            }
            if (\request()->has('from_date') && \request('from_date') != ""){
                $query->whereDate('created_at' , '>=' , \request('from_date'));
            }
            if (\request()->has('to_date') && \request('to_date') != ""){
                $query->whereDate('created_at' , '<=' , \request('to_date'));
            }
        });

        $data = [
            'sells' => $sells,
        ];

        return $this->export->handle('book_store', 'dashboard.site.book_store.export', $data, 'book_store_sells', $type);
    }
}
