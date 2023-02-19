<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateTicketRequest extends FormRequest
{
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
        return [
            'title'=>"string|max:100|required",
            'description'=>'string|max:1000|required',
            'files'=>"nullable",
            'files.*'=>"file",
            'priority'=>'string|required',
            'category_id'=>'required|exists:\App\Models\Categories,id',
            'label_id'=>'required|exists:\App\Models\Labels,id'
        ];
    }
}
