<?php

namespace App\Http\Services\Mutual;

use App\Exports\BanksExport;
use App\Exports\BooksExport;
use App\Exports\BookStoreExport;
use App\Exports\CartsExport;
use App\Exports\CategoriesExport;
use App\Exports\ContactsExport;
use App\Exports\CouponsExport;
use App\Exports\CourseSubscriptionsExport;
use App\Exports\ExamsExport;
use App\Exports\FieldsExport;
use App\Exports\InquiriesExport;
use App\Exports\LecturesExport;
use App\Exports\ManagersExport;
use App\Exports\PaymentsExport;
use App\Exports\PrintRequestsExport;
use App\Exports\StandardsExport;
use App\Exports\UsersExport;
use Exception;
use Maatwebsite\Excel\Facades\Excel;

class ExportService
{
    private array $exportables = [
        'students' => UsersExport::class,
        'subscriptions' => CourseSubscriptionsExport::class,
        'book_store' => BookStoreExport::class,
        'managers' => ManagersExport::class,
        'fields' => FieldsExport::class,
        'categories' => CategoriesExport::class,
        'lectures' => LecturesExport::class,
        'exams' => ExamsExport::class,
        'standards' => StandardsExport::class,
        'books' => BooksExport::class,
        'coupons' => CouponsExport::class,
        'banks' => BanksExport::class,
        'payments' => PaymentsExport::class,
        'carts' => CartsExport::class,
        'inquiries' => InquiriesExport::class,
        'contacts' => ContactsExport::class,
        'printRequests' => PrintRequestsExport::class
    ];

    private array $types = [
        'excel' => [
            'class' => null,
            'extension' => '.xlsx'
        ],
        'pdf' => [
            'class' => \Maatwebsite\Excel\Excel::MPDF,
            'extension' => '.pdf'
        ]
    ];

    public function handle(string $exportable, string $view, array $data, string $file_name = null, string $type = 'excel') {
        try {
            $file_name = ($file_name ?? $exportable) . $this->types[$type]['extension'];
            return array_key_exists($exportable, $this->exportables)
                ? Excel::download(new $this->exportables[$exportable]($view, $data), $file_name, $this->types[$type]['class'])
                : redirect()->back()->with(['error' => __('messages.Something went wrong')]);
        } catch (Exception $e) {
           return $e->getMessage();
            return redirect()->back()->with(['error' => __('messages.Something went wrong')]);
        }
    }
}
