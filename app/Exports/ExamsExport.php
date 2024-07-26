<?php

namespace App\Exports;

use App\Http\Traits\Exportable;
use Maatwebsite\Excel\Concerns\FromView;

class ExamsExport implements FromView
{
    use Exportable;
}
