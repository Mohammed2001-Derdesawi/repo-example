<?php

namespace App\Http\Controllers\Dashboard\Cart;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Services\Mutual\ExportService;
use App\Jobs\SendWhatsupMessageForUsersJob;
use App\Repository\CartRepositoryInterface;
use App\Repository\CourseRepositoryInterface;
use App\Repository\CourseBookRepositoryInterface;
use App\Http\Services\Dashboard\Carts\CartService;
use App\Http\Requests\Dashboard\Cart\SendWhatsupMessageForUsersRequest;
use App\Http\Services\Api\WhatsApp\WhatsAppService;
use App\Http\Services\Api\WhatsApp\WhatsAppServiceInterface;
use App\Repository\UserRepositoryInterface;

class CartController extends Controller
{
    private CartRepositoryInterface $cartRepository;
    private CourseRepositoryInterface $courseRepository;
    private CartService $cartService;
    private ExportService $export;

    public function __construct(CartRepositoryInterface $cartRepository, CourseRepositoryInterface $courseRepository , CartService $cartService , ExportService $export){
        $this->cartRepository = $cartRepository;
        $this->courseRepository = $courseRepository;
        $this->cartService = $cartService;
        $this->export = $export;

        $this->middleware('permission:carts-read')->only('index' , 'show');
    }

    public function index(){
        $courses = $this->courseRepository->getAll(['id' , 'name_ar' , 'name_en']);
        $carts = $this->cartRepository->getLeftCarts()->paginate(25);
        return view('dashboard.site.carts.index' , ['carts' => $carts , 'courses' => $courses]);
    }

    public function show($id){
        $cart = $this->cartRepository->getById($id , ['*'] , ['user' , 'items']);
        return view('dashboard.site.carts.show' , ['cart' => $cart]);
    }


    public function export(string $type)
    {
        $carts = $this->cartRepository->getLeftCarts()->get();
        $data = [
            'carts' => $carts
        ];

        return $this->export->handle('carts', 'dashboard.site.carts.export', $data, 'carts', $type);
    }

    public function sendWhatsupMessages(SendWhatsupMessageForUsersRequest $request){
        dispatch(new SendWhatsupMessageForUsersJob($request->usersIds,$request->whatsup_message));
        return redirect()->back()->with('success',__('dashboard.message_was_send'));
    }
}
