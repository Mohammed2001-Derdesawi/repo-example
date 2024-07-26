<?php

namespace App\Http\Controllers\Api\BookStore;

use App\Http\Controllers\Controller;
use App\Http\Services\Api\Book\BookService;

class BooksController extends Controller
{

    private BookService $bookService;

    public function __construct(BookService $bookService){
        $this->bookService = $bookService;
    }

    public function filter(){
       return $this->bookService->filter();
    }

    public function show($id){
        return $this->bookService->show($id);
    }
}
