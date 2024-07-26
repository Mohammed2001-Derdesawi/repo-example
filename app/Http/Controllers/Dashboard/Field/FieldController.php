<?php

namespace App\Http\Controllers\Dashboard\Field;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\Fields\FieldRequest;
use App\Http\Services\Dashboard\Fields\FieldService;
use App\Http\Services\Mutual\ExportService;
use App\Http\Services\Mutual\FileManagerService;
use App\Repository\CategoryRepositoryInterface;
use App\Repository\Eloquent\CategoryRepository;
use App\Repository\FieldRepositoryInterface;

class FieldController extends Controller
{

    private FieldRepositoryInterface $fieldRepository;
    private CategoryRepositoryInterface $categoryRepository;
    private FieldService $fieldService;
    private ExportService $export;
    public function __construct(FieldRepositoryInterface $fieldRepository ,CategoryRepositoryInterface $categoryRepository , FieldService $fieldService, ExportService $exportService){
        $this->fieldRepository = $fieldRepository;
        $this->categoryRepository = $categoryRepository;
        $this->fieldService = $fieldService;
        $this->export = $exportService;
        $this->middleware('permission:fields-read')->only('index' , 'show');
        $this->middleware('permission:fields-create')->only('create' , 'store');
        $this->middleware('permission:fields-update')->only('edit' , 'update');
        $this->middleware('permission:fields-delete')->only('edit' , 'destroy');
    }

    public function index(){
        $fields = $this->fieldRepository->paginate(10 , ['categories']);
        return view('dashboard.site.fields.index' , ['fields' => $fields]);
    }
    public function show($id){
        $field = $this->fieldRepository->getById($id , ["*"] , ['categories']);
        return view('dashboard.site.fields.show' , ['field' => $field]);
    }
    public function create(){
        $categories = $this->categoryRepository->getAll(['id' , 'name_ar' , 'name_en']);
        return view('dashboard.site.fields.create' , ['categories' => $categories]);
    }

    public function store(FieldRequest $request){
        return $this->fieldService->store($request);
    }

    public function edit($id){
        $field = $this->fieldRepository->getById($id);
        $categories = $this->categoryRepository->getAll(['id' , 'name_ar' , 'name_en']);
        return view('dashboard.site.fields.edit' , ['field' => $field , 'categories' => $categories]);
    }

    public function update($id , FieldRequest $request){
        return $this->fieldService->update($id , $request);
    }

    public function destroy($id){
        return $this->fieldService->delete($id);
    }

    public function export(string $type)
    {
        $fields = $this->fieldRepository->getAll(relations: ['categories']);

        $data = [
            'fields' => $fields,
        ];

        return $this->export->handle('fields', 'dashboard.site.fields.export', $data, 'fields', $type);
    }

}
