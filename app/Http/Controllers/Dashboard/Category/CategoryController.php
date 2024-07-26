<?php

namespace App\Http\Controllers\Dashboard\Category;

use App\Http\Contracts\Exportable;
use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\Categories\CategoryRequest;
use App\Http\Services\Dashboard\Categories\CategoryService;
use App\Http\Services\Mutual\ExportService;
use App\Repository\CategoryRepositoryInterface;
use App\Repository\FieldRepositoryInterface;

class CategoryController extends Controller implements Exportable
{

    private CategoryRepositoryInterface $categoryRepository;
    private FieldRepositoryInterface $fieldRepository;
    private CategoryService $categoryService;
    private ExportService $export;
    public function __construct(CategoryRepositoryInterface $categoryRepository, FieldRepositoryInterface $fieldRepository , CategoryService $categoryService, ExportService $exportService){
        $this->categoryRepository = $categoryRepository;
        $this->fieldRepository = $fieldRepository;
        $this->categoryService = $categoryService;
        $this->export = $exportService;
        $this->middleware('permission:categories-read')->only('index' , 'show');
        $this->middleware('permission:categories-create')->only('create', 'store');
        $this->middleware('permission:categories-update')->only('edit' , 'update');
        $this->middleware('permission:categories-delete')->only('destroy');

    }

    public function index(){
        $categories = $this->categoryRepository->getAll(['*'] , ['field']);
        return view('dashboard.site.categories.index' , ['categories' => $categories]);
    }

    public function show($id){
        $category = $this->categoryRepository->getById($id , ['*'] , ['field']);
        return view('dashboard.site.categories.show' , ['category' => $category]);
    }

    public function create(){
        $fields = $this->fieldRepository->getAll(['id' , 'name_ar' , 'name_en']);
        return view('dashboard.site.categories.create', ['fields' => $fields]);
    }

    public function store(CategoryRequest $request){
        return $this->categoryService->store($request);
    }

    public function edit($id){
        $category = $this->categoryRepository->getById($id);
        $fields = $this->fieldRepository->getAll(['id' , 'name_ar' , 'name_en']);
        return view('dashboard.site.categories.edit' , ['category' => $category , 'fields' => $fields]);
    }

    public function update($id , CategoryRequest $request){
        return $this->categoryService->update($id , $request);
    }

    public function destroy($id){
        return $this->categoryService->delete($id);
    }

    public function export(string $type)
    {
        $categories = $this->categoryRepository->getAll(['*'] , ['field']);

        $data = [
            'categories' => $categories,
        ];

        return $this->export->handle('categories', 'dashboard.site.categories.export', $data, 'categories', $type);
    }
}
