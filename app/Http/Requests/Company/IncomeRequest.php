<?php

namespace App\Http\Requests\Company;

use Illuminate\Foundation\Http\FormRequest;

class IncomeRequest extends FormRequest
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
            'category' => 'required|string|max:255',
            'comment' => 'nullable|string|max:255',
            'date' => 'required|date',
            'service' => 'nullable|string|max:255',
            'pay_service' => 'nullable|string|max:255',
//            'is_payed' => 'nullable|boolean',
        ];
    }
}
