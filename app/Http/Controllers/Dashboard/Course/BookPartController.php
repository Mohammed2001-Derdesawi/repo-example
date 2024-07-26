<?php

namespace App\Http\Controllers\Dashboard\Course;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\Courses\BookPartRequest;
use App\Http\Services\Dashboard\Courses\CourseBookService;
use App\Repository\BookPartRepositoryInterface;
use Illuminate\Http\Request;

class BookPartController extends Controller
{

    private BookPartRepositoryInterface $bookPartRepository;
    private CourseBookService $courseBookService;

    public function __construct(BookPartRepositoryInterface $bookPartRepository , CourseBookService $courseBookService){
        $this->bookPartRepository = $bookPartRepository;
        $this->courseBookService = $courseBookService;
    }

    public function create($book_id){
        return view('dashboard.site.courses.TrainingBags.parts.create' , [
            'book_id' => $book_id
        ]);
    }

    public function store(BookPartRequest $request){
        return $this->courseBookService->storePart($request);
    }

    public function edit($id){
        $part = $this->bookPartRepository->getById($id);
        return view('dashboard.site.courses.TrainingBags.parts.edit' , [
            'part' => $part
        ]);
    }

    public function update($id , BookPartRequest $request){
        return $this->courseBookService->updatePart($id , $request);
    }

    public function destroy($id){
        return $this->courseBookService->deletePart($id);
    }

    public function uploadFilePart(Request $request){
        return $this->courseBookService->uploadFilePart($request);
    }
}
