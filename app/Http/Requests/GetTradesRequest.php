<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GetTradesRequest extends FormRequest
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
     * @return array
     */
    public function rules()
    {
        return [
            'page' => 'int',
            'type_id' => 'int',
            'start_hub' => 'int',
            'end_hub' => 'int',
        ];
    }
}
