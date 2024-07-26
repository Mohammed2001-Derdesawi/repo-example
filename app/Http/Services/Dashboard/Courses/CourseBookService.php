<?php

namespace App\Http\Services\Dashboard\Courses;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use App\Repository\BookPartRepositoryInterface;
use App\Http\Services\Mutual\FileManagerService;
use App\Repository\Eloquent\CourseBookRepository;

use Pion\Laravel\ChunkUpload\Receiver\FileReceiver;
use App\Http\Requests\Dashboard\Courses\BookRequest;
use App\Http\Requests\Dashboard\Fields\FieldRequest;
use Pion\Laravel\ChunkUpload\Handler\HandlerFactory;
use Pion\Laravel\ChunkUpload\Exceptions\UploadMissingFileException;

class CourseBookService
{
    private CourseBookRepository $courseBookRepository;
    private BookPartRepositoryInterface $bookPartRepository;
    private FileManagerService $fileManager;

    public function __construct(CourseBookRepository $courseBookRepository , FileManagerService $fileManager , BookPartRepositoryInterface $bookPartRepository){
        $this->courseBookRepository = $courseBookRepository;
        $this->bookPartRepository = $bookPartRepository;
        $this->fileManager = $fileManager;
    }

    public function storeBooks($books , $course_id){
        DB::beginTransaction();
        try {
            foreach ($books as $key => $book){
                if(isset($book['book_pdf']))
                    $book['book_pdf'] = $this->fileManager->handle('books.' . $key . '.book_pdf', 'courses/books');
                if(isset($book['image']))
                    $book['image'] = $this->fileManager->handle('books.' . $key . '.image', 'courses/books');
                $this->courseBookRepository->create([
                    'course_id' => $course_id,
                    'name_ar' => $book['name_ar'],
                    'name_en' => $book['name_en'],
                    'description_ar' => $book['description_ar'] ?? null,
                    'description_en' => $book['description_en'] ?? null,
                    'book_pdf' => $book['book_pdf'] ?? null,
                    'image' => $book['image'] ?? null,
                    'price' => $book['price'],
                    'show_in_store' => isset($book['show_in_store']) && $book['show_in_store'] == 'on',
                ]);
                DB::commit();
                return to_route('books.index')->with('success' , __('messages.created successfully'));
            }
        }catch (\Exception $e){
            DB::rollBack();
            dd($e);
            return back()->with(['error' => __('messages.Something went wrong')]);
        }
    }

    public function update($id , BookRequest $request){
        $book = $this->courseBookRepository->getById($id);
//        if ($book->course){
//            abort_unless(Gate::allows('control-course', $book->course), 401);
//        }
        DB::beginTransaction();
        try {
            $data = $request['books'][0];
            $data['show_in_store'] = $data['show_in_store'] == 'on';
            if(isset($data['book_pdf'])){
                $data['book_pdf'] = $this->fileManager->handle('books.0.book_pdf', 'courses/books');
            }

            if(isset($data['image'])){
                $data['image'] = $this->fileManager->handle('books.0.image', 'courses/books');
            }
//            if ($data['course_id'] == 0 )
//                $data['course_id'] = null;
//            $data = $this->handlePrices($data);
            $this->courseBookRepository->update($id , $data);
            DB::commit();
            return back()->with(['success' => __('messages.updated successfully')]);
        }catch (\Exception $e){
            DB::rollBack();
            return back()->with(['error' => __('messages.Something went wrong')]);
        }
    }

    public function handlePrices($data){
        if (isset($data['is_bw'])){
            $data['is_bw'] = 1;
        }else{
            $data['is_bw'] = 0;
            $data['bw_price'] = null;
        }
        if (isset($data['is_coloured'])){
            $data['is_coloured'] = 1;
        }else{
            $data['is_coloured'] = 0;
            $data['coloured_price'] = null;
        }
        return $data;
    }

    public function delete($id) {
        $book = $this->courseBookRepository->getById($id);
        if ($book->course !== null) {
            abort_unless(Gate::allows('control-course', $book->course), 401);
        }
        try {
            $this->courseBookRepository->delete($id);
            return back()->with(['success' => __('messages.deleted successfully')]);
        }catch (\Exception $e){
            return back()->with(['error' => __('messages.Something went wrong')]);
        }
    }



    // Training Bags

    public function storeTrainingBag($request){
        DB::beginTransaction();
        try {
            $data = $request->validated();
            if(isset($data['image'])){
                $data['image'] = $this->fileManager->handle('image', 'courses/books');
            }
            $data['show_in_store'] = false;
            $data['is_printable'] = $request->is_printable == 'on';

            $this->courseBookRepository->create($data);
            DB::commit();
            return redirect()->route('courses.show' , $data['course_id'])->with('success' , __('messages.created successfully'));
        }catch (\Exception $e){
            DB::rollBack();
            return back()->with('error' , __('messages.Something went wrong'));
        }
    }

    public function updateTrainingBag($id , $request){
        DB::beginTransaction();
        $bag = $this->courseBookRepository->getById($id);
        try {
            $data = $request->validated();
            if(isset($data['image'])){
                $data['image'] = $this->fileManager->handle('image', 'courses/books');
            }
            $data['is_printable'] = $request->is_printable == 'on';
            $bag->update($data);
            DB::commit();
            return redirect()->route('courses.show' , $data['course_id'])->with('success' , __('messages.updated successfully'));
        }catch (\Exception $e){
            DB::rollBack();
            return back()->with('error' , __('messages.Something went wrong'));
        }
    }

    public function storePart($request){
        DB::beginTransaction();
        try {
            $data = $request->validated();
            if(isset($data['pdf_file'])){
                $data['pdf_file'] =$data['pdf_file'];
            }
            $this->bookPartRepository->create($data);
            DB::commit();
            return back()->with('success' , __('messages.created successfully'));
        }catch (\Exception $e){
            DB::rollBack();
//            return $e->getMessage();
            return back()->with('error' , __('messages.Something went wrong'));
        }
    }

    public function updatePart($id , $request){
        DB::beginTransaction();
        $part = $this->bookPartRepository->getById($id);
        try {
            $data = $request->validated();
            if(isset($data['pdf_file'])){
                $data['pdf_file'] =$data['pdf_file'];
            }
            $part->update($data);
            DB::commit();
            return back()->with('success' , __('messages.updated successfully'));
        }catch (\Exception $e){
            DB::rollBack();
            return back()->with('error' , __('messages.Something went wrong'));
        }
    }

    public function deletePart($id) {
        try {
            $this->bookPartRepository->delete($id);
            return back()->with(['success' => __('messages.deleted successfully')]);
        }catch (\Exception $e){
            return back()->with(['error' => __('messages.Something went wrong')]);
        }
    }

    public function uploadFilePart($request) {
        // create the file receiver
        $receiver = new FileReceiver("file", $request, HandlerFactory::classFromRequest($request));

        // check if the upload is success, throw exception or return response you need
        if ($receiver->isUploaded() === false) {
            throw new UploadMissingFileException();
        }

        // receive the file
        $save = $receiver->receive();

        // check if the upload has finished (in chunk mode it will send smaller files)
        if ($save->isFinished()) {
            // save the file and return any response you need, current example uses `move` function. If you are
            // not using move, you need to manually delete the file by unlink($save->getFile()->getPathname())
            $path=$this->fileManager->handleChunk($save->getFile(), 'courses/books/');
            unlink($save->getFile()->getPathname());
            return $path;
        }

        // we are in chunk mode, lets send the current progress
        /** @var AbstractHandler $handler */
        $handler = $save->handler();

        return response()->json([
            "done" => $handler->getPercentageDone(),
        ]);
    }

}
