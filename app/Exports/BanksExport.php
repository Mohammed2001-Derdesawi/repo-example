<?php

namespace App\Exports;

use App\Http\Traits\Exportable;
use Maatwebsite\Excel\Concerns\FromView;

class BanksExport implements FromView
{
    use Exportable;
}
