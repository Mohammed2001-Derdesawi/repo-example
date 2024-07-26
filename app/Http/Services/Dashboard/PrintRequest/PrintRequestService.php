<?php

namespace App\Http\Services\Dashboard\PrintRequest;

use App\Http\Requests\Dashboard\PrintRequests\PrintRequestRequest;
use App\Repository\PrintRequestRepositoryInterface;
use Illuminate\Support\Facades\DB;

class PrintRequestService
{
    private PrintRequestRepositoryInterface $printRequestRepository;

    public function __construct(PrintRequestRepositoryInterface $printRequestRepository){
        $this->printRequestRepository = $printRequestRepository;
    }

    public function changeStatus($id , PrintRequestRequest $request){
        DB::beginTransaction();
        try {
            $this->printRequestRepository->update($id , [
               'status' => $request['status']
            ]);
            DB::commit();
            return back()->with('success' , __('messages.request_status_changed'));
        }catch (\Exception $exception){
            DB::rollBack();
            return back()->with(['error' => __('messages.Something went wrong')]);

        }
    }

    public function delete($id) {
        DB::beginTransaction();
        try {
            $this->printRequestRepository->delete($id);
            DB::commit();
            return back()->with(['success' => __('messages.deleted successfully')]);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with(['error' => __('messages.Something went wrong')]);
        }
    }

}
