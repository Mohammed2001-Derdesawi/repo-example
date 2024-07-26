<?php

namespace App\Exports;

use App\Http\Traits\Exportable;
use Maatwebsite\Excel\Concerns\FromView;

class ManagersExport implements FromView
{
    use Exportable;
}
