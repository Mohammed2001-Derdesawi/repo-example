<?php

namespace App\Http\Traits;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Events\AfterSheet;

trait Exportable
{
    private string $view;
    private array $data;

    public function __construct(string $view, array $data)
    {
        $this->view = $view;
        $this->data = $data;
    }

    public function view(): View
    {
        return view($this->view, $this->data);
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $event->sheet->getDelegate()->getColumnDimension('A')->setWidth(30);

            },
        ];
    }
}
