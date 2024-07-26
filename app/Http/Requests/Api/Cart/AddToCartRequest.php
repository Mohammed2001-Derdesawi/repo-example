<?php

namespace App\Http\Requests\Api\Cart;

use App\Repository\CourseBookRepositoryInterface;
use App\Repository\CourseRepositoryInterface;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AddToCartRequest extends FormRequest
{
    private CourseRepositoryInterface $courseRepository;
    private CourseBookRepositoryInterface $courseBookRepository;
    public function __construct(
        CourseRepositoryInterface $courseRepository,
        CourseBookRepositoryInterface $courseBookRepository,
        array $query = [],
        array $request = [],
        array $attributes = [],
        array $cookies = [],
        array $files = [],
        array $server = [],
        $content = null
    )
    {
        parent::__construct($query, $request, $attributes, $cookies, $files, $server, $content);
        $this->courseRepository = $courseRepository;
        $this->courseBookRepository = $courseBookRepository;
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        $itemsTypes = ['course', 'courseBook'];
        return [
            'item_type' => ['required', Rule::in($itemsTypes)],
            'item_id' => ['required', function($attribute, $value, $fail) {
                $isAddable = $this->{$this->input('item_type').'Repository'}->isExisted($this->input('item_id'));
                if (!$isAddable) {
                    $fail(__('messages.Not existed item'));
                }
            }],
            'option' => $this->input('item_type') == 'courseBook' ? ['required', Rule::in(['PDF', 'PRINT'])] : ['nullable', 'exclude'],
            'quantity' => 'nullable'
            ];
    }
}
