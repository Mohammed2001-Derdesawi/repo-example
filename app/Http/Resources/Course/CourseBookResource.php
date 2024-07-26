<?php

namespace App\Http\Resources\Course;

use Illuminate\Http\Resources\Json\JsonResource;

class CourseBookResource extends JsonResource
{

    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->t('name'),
            'book_pdf' => $this->book_pdf,
            'price' => $this->getRawOriginal('price'),
            'app_price' => $this->app_price,
            'image' => $this->image == null ? $this->course->image : $this->image,
            'is_printable' => $this->is_printable,
            'is_printed_before' => $this->is_printed_before,
            'is_purchased_before' => $this->is_purchased_before,
            'parts' => BookPartResource::collection($this->parts)
        ];
    }
}
