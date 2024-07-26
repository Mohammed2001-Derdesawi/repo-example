<?php

namespace App\Http\Controllers\Api\CategoryField;

use App\Http\Controllers\Controller;
use App\Http\Services\Api\CategoryField\CategoryFieldService;
use App\Repository\FieldRepositoryInterface;

class CategoryFieldController extends Controller
{
    private CategoryFieldService $categoryFieldService;

    public function __construct(CategoryFieldService $categoryFieldService){
        $this->categoryFieldService = $categoryFieldService;
    }

    public function index(){
        return $this->categoryFieldService->index();
    }


    public function getField($id){
        return $this->categoryFieldService->getField($id);
    }

    public function getNavbarFields() {
        return $this->categoryFieldService->getNavbarFields();
    }

}
