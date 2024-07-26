<?php

namespace App\Http\Controllers\Dashboard\Contact;
use App\Http\Services\Dashboard\ContactUs\ContactUsService;
use App\Http\Controllers\Controller;
use App\Http\Services\Mutual\ExportService;
use App\Repository\ContactRepositoryInterface;
use Illuminate\Http\Request;
use App\Http\Requests\Dashboard\ContactUs\ContactUsRequest;

class ContactController extends Controller
{
    private ContactRepositoryInterface $contactRepository;
    private ExportService $export;
    private ContactUsService $contactService;

    public function __construct(ContactRepositoryInterface $contactRepository , ExportService $export , ContactUsService $contactService)
    {
        $this->contactRepository = $contactRepository;
        $this->export = $export;
        $this->contactService = $contactService;
    }

    public function index(){
        $contacts = $this->contactRepository->paginate(15 , orderBy: "DESC",filters:function($query){
            if(in_array(request()->is_readed,["0","1"]))
           {
           return $query->where('is_readed','=',request()->is_readed);
           }
        });
        return view('dashboard.site.contacts.index' , [
           'contacts' => $contacts
        ]);
    }

    public function show($id){
        $contact = $this->contactRepository->getById($id);
        return view('dashboard.site.contacts.show' , [
            'contact' => $contact
        ]);
    }

    public function destroy($id) {
        $this->contactRepository->delete($id);
        return to_route('contacts.index')->with('success' , __('messages.Contact deleted successfully'));

    }

    public function export(string $type)
    {
        $contacts = $this->contactRepository->getAll();
        $data = [
            'contacts' => $contacts
        ];

        return $this->export->handle('inquiries', 'dashboard.site.contacts.export', $data, 'contacts', $type);
    }

    public function update(ContactUsRequest $request,$id)
    {
        $this->contactService->reply($request,$id);
        return redirect()->route('contacts.index')->with('success' , __('dashboard.Reply_is_send'));
    }
}
