<?php

namespace App\Http\Requests\Company;

use Illuminate\Foundation\Http\FormRequest;

class ExpenseRequest extends FormRequest
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
            'category' => 'required|integer|exists:expense_categories,id',
            'sum' => 'required|integer|min:0',
            'user' => 'required|string|max:255',
            'comment' => 'nullable|string|max:700',
            'date' => 'required|string'
        ];
    }
}
