<?php

namespace App\Http\Resources\Field\Mobile;

use Illuminate\Http\Resources\Json\JsonResource;

class FieldResource extends JsonResource
{

    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->t('name'),
            'show_department' => $this->show_department,
            'color_code' => $this->color_code
        ];
    }
}
