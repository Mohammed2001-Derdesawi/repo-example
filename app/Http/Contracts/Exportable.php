<?php

namespace App\Http\Contracts;

interface Exportable
{

    public function export(string $type);

}
