<?php

namespace App\Http\Controllers\Api\Info;

use App\Http\Controllers\Controller;
use App\Http\Services\Api\Info\InfoService;
use Illuminate\Http\Request;

class InfoController extends Controller
{
    private InfoService $info;

    public function __construct(
        InfoService $info,
    )
    {
        $this->info = $info;
    }

    public function get($info)
    {
        return $this->info->get($info);
    }


}
